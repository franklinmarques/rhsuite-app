<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_lifo extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Avaliacaoexp_model', 'avaliacaoexp');
    }

    public function index()
    {
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('pesquisa_lifo1', $data);
    }

    public function gerenciar($id = null)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $pesquisa = $this->db->get_where('pesquisa_modelos', array('id' => $id))->row();
        $data['id_modelo'] = $pesquisa->id;
        $data['modelo'] = $pesquisa->nome;
        switch ($pesquisa->tipo) {
            case 'C':
                $data['tipo'] = 'de clima';
                break;
            case 'E':
                $data['tipo'] = 'de personalidade';
                break;
            case 'P':
                $data['tipo'] = 'de perfil';
                break;
            case 'O':
                $data['tipo'] = 'de LIFO';
                break;
            default:
                $data['tipo'] = 'de clima';
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('pesquisa_lifo1', $data);
    }

    public function estilos()
    {
        $this->load->view('pesquisa_lifo_estilos');
    }

    public function comportamentos($idEstilo)
    {
        $this->db->select('id AS idEstilo, nome', false);
        $this->db->where('id', $idEstilo);
        $data = $this->db->get('pesquisa_lifo_estilos')->row();

        $this->load->view('pesquisa_lifo_comportamentos', $data);
    }

    public function ajaxEstilos()
    {
        $post = $this->input->post();

        $this->db->select('id, nome, indice_resposta');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('indice_resposta', 'asc');
        $rows = $this->db->get('pesquisa_lifo_estilos')->result();

        $data = array();
        foreach ($rows as $row) {
            $data[] = array(
                $row->indice_resposta,
                $row->nome,
                '<button class="btn btn-sm btn-info" title="Editar" onclick="edit_estilo(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_estilo(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" title="Gerenciar comportamentos" onclick="comportamentos(' . "'" . $row->id . "'" . ')">Comportamentos</button>'
            );
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => count($rows),
            "recordsFiltered" => count($rows),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajaxComportamentos()
    {
        $post = $this->input->post();
        $output = array('draw' => $this->input->post('draw'));

        $this->db->select('id, nome');
        $this->db->select("CASE situacao_comportamental WHEN 'N' THEN 'Normal' WHEN 'E' THEN 'Estresse/presão' END AS situacao_comportamental", false);
        $this->db->where('id_estilo', $post['id_estilo']);
        if ($post['situacao_comportamental']) {
            $this->db->where('situacao_comportamental', $post['situacao_comportamental']);
        }
        $output['recordsTotal'] = $this->db->get('pesquisa_lifo_comportamentos')->num_rows();

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        if ($post['search']['value']) {
            $sql .= " WHERE s.nome LIKE '%{$post['search']['value']}%'";
            $output['recordsFiltered'] = $this->db->query($sql)->num_rows();
        } else {
            $output['recordsFiltered'] = $output['recordsTotal'];
        }

        if ($post['order']) {
            $orderBy = [];
            foreach ($post['order'] as $order) {
                $orderBy[] = intval($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();

        $data = array();
        foreach ($rows as $row) {
            $data[] = array(
                $row->nome,
                $row->situacao_comportamental,
                '<button class="btn btn-sm btn-info" title="Editar" onclick="edit_comportamento(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_comportamento(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }
        $output['data'] = $data;

        echo json_encode($output);
    }

    public function ajax_list($id)
    {
        if (empty($id)) {
            $id = $this->session->userdata('empresa');
        }

        if ($this->session->userdata('tipo') != 'empresa') {
            $id_usuario = $this->session->userdata('id');
        } else {
            $id_usuario = '';
        }

        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.avaliador,
                       s.modelo,
                       s.tipo,
                       s.data_inicio,
                       s.data_termino,
                       s.data_valida
                FROM (SELECT c.id, 
                             a.nome,
                             d.nome AS avaliador,
                             b.nome AS modelo,
                             (case b.tipo 
                              when 'O' then 'LIFO' 
                              else '' end) AS tipo,
                             a.data_inicio,
                             a.data_termino,
                             (CASE WHEN (now() BETWEEN a.data_inicio AND a.data_termino) AND COUNT(e.id_avaliador) = 0
                              THEN 'ok'  
                              WHEN now() < a.data_inicio
                              THEN 'espera' 
                              WHEN now() > a.data_termino
                              THEN 'expirada' 
                              WHEN COUNT(e.id_avaliador) > 0
                              THEN 'concluido' 
                              ELSE '' END) AS data_valida
                      FROM pesquisa a
                      INNER JOIN pesquisa_modelos b ON 
                                 b.id = a.id_modelo
                      LEFT JOIN pesquisa_avaliadores c
                                ON c.id_pesquisa = a.id
                      LEFT JOIN usuarios d
                                ON d.id = c.id_avaliador
                      LEFT JOIN pesquisa_resultado e 
                                ON e.id_avaliador = c.id
                      WHERE b.id_usuario_EMPRESA = {$id}
                            AND ('{$id_usuario}' = c.id_avaliador OR CHAR_LENGTH('{$id_usuario}') = 0)
                            AND b.tipo = 'O'
                      GROUP BY c.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.modelo', 's.data_inicio', 's.data_termino');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " 
                        WHERE {$column} LIKE '%{$post['search']['value']}%'";
                }
            }
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $pesquisa) {
            $row = array();
            $row[] = $pesquisa->nome;
            $row[] = $pesquisa->modelo;
            $row[] = $pesquisa->data_inicio ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_inicio))) : '';
            $row[] = $pesquisa->data_termino ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_termino))) : '';

            switch ($pesquisa->data_valida) {
                case 'ok':
                    $row[] = '
                              <a class="btn btn-sm btn-primary btn-block" href="pesquisa_lifo/teste/' . $pesquisa->id . '" target="_blank" title="Iniciar teste">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Iniciar teste</a>
                             ';
                    break;
                case 'executando':
                    $row[] = '
                              <a class="btn btn-sm btn-primary btn-block" href="pesquisa_lifo/teste/' . $pesquisa->id . '" target="_blank" title="Iniciar teste">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Finalizado</a>
                             ';
                    break;
                case 'espera':
                    $row[] = '
                              <a class="btn btn-sm btn-warning btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Data agendada&nbsp;</a>
                             ';
                    break;
                case 'expirada':
                    $row[] = '
                              <a class="btn btn-sm btn-danger btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Data expirada&nbsp;</a>
                             ';
                    break;
                case 'esgotado':
                    $row[] = '
                              <a class="btn btn-sm btn-danger btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Tempo esgotado&nbsp;</a>
                             ';
                    break;
                case 'concluido':
                    $row[] = '
                              <a class="btn btn-sm btn-primary btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Teste concluído&nbsp;</a>
                             ';
                    break;
                default:
                    $row[] = '
                              <a class="btn btn-sm btn-success btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Iniciar teste</a>
                             ';
            }

            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function teste($id)
    {
        $empresa = $this->session->userdata('empresa');
        $usuario = $this->session->userdata('id');

        $sql = "SELECT a.id,
                       c.id_modelo,
                       c.data_inicio,
                       c.data_termino,
                       d.instrucoes,
                       d.tipo,
                       e.data_avaliacao,
                       IF(a.data_acesso IS NOT NULL, IF(TIME_TO_SEC(TIMEDIFF(NOW(), a.data_acesso)) > 1800, '00:00:00', TIMEDIFF('00:30:00', TIMEDIFF(NOW(), a.data_acesso))), null) AS data_acesso,
                       a.data_finalizacao
                FROM pesquisa_avaliadores a
                INNER JOIN usuarios b ON
                           b.id = a.id_avaliador
                INNER JOIN pesquisa c ON
                           c.id = a.id_pesquisa
                INNER JOIN pesquisa_modelos d ON
                           d.id = c.id_modelo
                LEFT JOIN pesquisa_resultado e ON
                          e.id_avaliador = a.id
                WHERE b.id = {$usuario} AND 
                      d.id_usuario_EMPRESA = {$empresa} AND 
                      a.id = '{$id}'";
        $data['teste'] = $this->db->query($sql)->row();
        if (empty($data['teste'])) {
            redirect(site_url('home'));
        }
        if ($data['teste']->data_finalizacao) {
            redirect(site_url('home'));
        }


        $this->db->trans_begin();

        $this->db->select('id, pergunta');
        $this->db->where('id_modelo', $data['teste']->id_modelo);
        $this->db->order_by('id', 'asc');
        $perguntas = $this->db->get('pesquisa_perguntas')->result();

        $total = 0;
        foreach ($perguntas as $pergunta) {
            $sql = "SELECT a.id,
                           a.alternativa,
                           a.peso,
                           e.peso AS resposta
                    FROM pesquisa_alternativas a
                    INNER JOIN pesquisa_perguntas b ON
                               b.id = a.id_pergunta
                    INNER JOIN pesquisa_modelos c ON
                               c.id = b.id_modelo
                    LEFT JOIN pesquisa_resultado d ON
                              d.id_alternativa = a.id AND
                              d.id_pergunta = b.id AND
                              d.id_avaliador = {$data['teste']->id}
                    LEFT JOIN pesquisa_alternativas e ON
                              e.id = d.id_alternativa
                    WHERE a.id_pergunta = {$pergunta->id}
                          AND a.id_modelo = {$data['teste']->id_modelo}";
            $rows = $this->db->query($sql)->result();

            $pergunta->alternativas = $rows;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            redirect(site_url('home'));
        } else {
            $this->db->trans_commit();
        }

        $data['perguntas'] = $perguntas;

        $data['teste']->total = round($total, 2);
        if ($data['teste']->data_acesso) {
            $data['tempo_restante'] = $data['teste']->data_acesso;
        } else {
            $this->db->set('data_acesso', date('Y-m-d H-i-s'));
            $this->db->where('id', $id);
            $this->db->update('pesquisa_avaliadores');
            $data['tempo_restante'] = '0:30:00';
        }

        $this->load->view('pesquisa_lifo_teste', $data);
    }

    public function finalizar($idAvaliador)
    {
        $valores = $this->input->post('valor');
        $alternativas = array_keys($valores);

        $this->db->select('id, id_pergunta');
        $this->db->where_in('id', $alternativas);
        $rows = $this->db->get('pesquisa_alternativas')->result();
        $perguntas = array_column($rows, 'id_pergunta', 'id');

        $dataAvaliacao = date('Y-m-d H:i:s');

        $data = array();

        $this->db->trans_start();

        $this->db->set('data_finalizacao', date('Y-m-d H-i-s'));
        $this->db->where('id', $idAvaliador);
        $this->db->update('pesquisa_avaliadores');

        $this->db->where('id_avaliador', $idAvaliador);
        $this->db->delete('pesquisa_resultado');

        foreach ($valores as $alternativa => $valor) {
            $data[] = array(
                'id_avaliador' => $idAvaliador,
                'id_pergunta' => $perguntas[$alternativa],
                'id_alternativa' => $alternativa,
                'valor' => $valor,
                'resposta' => null,
                'data_avaliacao' => $dataAvaliacao
            );
        }

        $this->db->insert_batch('pesquisa_resultado', $data);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_listResposta($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }

        $post = $this->input->post();

        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM pesquisa_alternativas a
                INNER JOIN pesquisa_modelos b ON
                           b.id = a.id_modelo
                WHERE a.id_modelo = {$id} AND
                      a.id_pergunta IS NULL 
                GROUP by a.id";

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        $sql .= " 
                LIMIT 0, 4";

        $list = $this->db->query($sql)->result();

        $recordsTotal = count($list);

        $data = array();

        foreach ($list as $pesquisa) {
            $data[] = array($pesquisa->alternativa, $pesquisa->peso);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $data,
        );

        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get('pesquisa_perguntas')->row();

        echo json_encode($data);
    }

    public function ajaxCriarEstilo()
    {
        $this->db->select('IFNULL(MAX(indice_resposta), 0) + 1 AS indice_resposta', false);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $data = $this->db->get('pesquisa_lifo_estilos')->row();

        echo json_encode($data);
    }

    public function ajaxEditEstilo()
    {
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('pesquisa_lifo_estilos')->row();

        echo json_encode($data);
    }

    public function ajaxEditComportamento()
    {
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('pesquisa_lifo_comportamentos')->row();

        echo json_encode($data);
    }

    public function ajax_editResposta($id)
    {
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM pesquisa_alternativas a
                INNER JOIN pesquisa_modelos b ON
                           b.id = a.id_modelo
                WHERE b.id = {$id}
                LIMIT 0, 4";

        $data = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'N',
            'prefixo_resposta' => $this->input->post('prefixo_resposta'),
            'valor_min' => 1,
            'valor_max' => 4
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
        }

        $this->db->trans_begin();

        $this->db->query($this->db->insert_string('pesquisa_perguntas', $data));

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addEstilo()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome do estilo é obrigatório')));
        }
        if (empty($data['indice_resposta'])) {
            exit(json_encode(array('erro' => 'O índice de resposta é obrigatório')));
        }
        $data['id_empresa'] = $this->session->userdata('empresa');
        $this->db->where('id_empresa', $data['id_empresa']);
        $this->db->where('nome', $data['nome']);
        $this->db->where('indice_resposta', $data['indice_resposta']);
        $count = $this->db->get('pesquisa_lifo_estilos')->num_rows();

        if ($count > 0) {
            exit(json_encode(array('erro' => 'O estilo já está cadastrado')));
        }

        $status = $this->db->insert('pesquisa_lifo_estilos', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addComportamento()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome do comportamento é obrigatório')));
        }
        if (empty($data['situacao_comportamental'])) {
            exit(json_encode(array('erro' => 'A situação comportamental é obrigatória')));
        }
        $this->db->where('id_estilo', $data['id_estilo']);
        $this->db->where('situacao_comportamental', $data['situacao_comportamental']);
        $this->db->where('nome', $data['nome']);
        $count = $this->db->get('pesquisa_lifo_comportamentos')->num_rows();

        if ($count > 0) {
            exit(json_encode(array('erro' => 'O comportamento já está cadastrado')));
        }

        $status = $this->db->insert('pesquisa_lifo_comportamentos', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'prefixo_resposta' => $this->input->post('prefixo_resposta')
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
        }

        $this->db->trans_begin();

        $update_string = $this->db->update_string('pesquisa_perguntas', $data, array('id' => $this->input->post('id')));
        $this->db->query($update_string);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateEstilo()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome do estilo é obrigatório')));
        }
        if (empty($data['indice_resposta'])) {
            exit(json_encode(array('erro' => 'O índice de resposta é obrigatório')));
        }
        if (strlen($data['estilo_personalidade_majoritario']) == 0) {
            $data['estilo_personalidade_majoritario'] = null;
        }
        if (strlen($data['estilo_personalidade_secundario']) == 0) {
            $data['estilo_personalidade_secundario'] = null;
        }
        $data['id_empresa'] = $this->session->userdata('empresa');
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id !=', $id);
        $this->db->where('id_empresa', $data['id_empresa']);
        $this->db->where('nome', $data['nome']);
        $this->db->where('indice_resposta', $data['indice_resposta']);
        $count = $this->db->get('pesquisa_lifo_estilos')->num_rows();

        if ($count > 0) {
            exit(json_encode(array('erro' => 'O estilo já está cadastrado')));
        }

        $status = $this->db->update('pesquisa_lifo_estilos', $data, ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateComportamento()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome do comportamento é obrigatório')));
        }
        if (empty($data['situacao_comportamental'])) {
            exit(json_encode(array('erro' => 'A situação comportamental é obrigatória')));
        }
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id !=', $id);
        $this->db->where('id_estilo', $data['id_estilo']);
        $this->db->where('situacao_comportamental', $data['situacao_comportamental']);
        $this->db->where('nome', $data['nome']);
        $count = $this->db->get('pesquisa_lifo_comportamentos')->num_rows();

        if ($count > 0) {
            exit(json_encode(array('erro' => 'O comportamento já está cadastrado')));
        }

        $status = $this->db->update('pesquisa_lifo_comportamentos', $data, ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $this->db->trans_begin();
        $this->db->query("DELETE FROM pesquisa_perguntas WHERE id = ?", $id);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_deleteEstilo()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('pesquisa_lifo_estilos', ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_deleteComportamento()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('pesquisa_lifo_comportamentos', ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateResposta()
    {
        $id_modelo = $this->input->post('id_modelo');

        if (empty($id_modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de avaliação não foi encontrado')));
        }

        $id_alternativas = $this->input->post('id_alternativa');

        $alternativas = $this->input->post('alternativa');

        $peso = $this->input->post('peso');

        $this->db->trans_begin();

        foreach ($alternativas as $k => $alternativa) {

            $data = array(
                'id_modelo' => $id_modelo,
                'alternativa' => $alternativa,
                'peso' => $peso[$k]
            );

            if ($alternativa) {

                if ($id_alternativas[$k]) {

                    $where = array(
                        'id' => $id_alternativas[$k],
                        'id_modelo' => $id_modelo
                    );

                    $update_string = $this->db->update_string('pesquisa_alternativas', $data, $where);
                    $this->db->query($update_string);
                } else {

                    $insert_string = $this->db->insert_string('pesquisa_alternativas', $data);
                    $this->db->query($insert_string);
                }
            } elseif ($id_alternativas[$k]) {

                $this->db->query("DELETE FROM pesquisa_alternativas WHERE id = $id_alternativas[$k]");
            }
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }


    public function relatorio($id, $pdf = false)
    {
        $this->db->select('id, foto, foto_descricao');
        $empresa = $this->db->get_where('usuarios', array('id' => $this->session->userdata('empresa')))->row();
        $data['foto'] = 'imagens/usuarios/' . $empresa->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $empresa->foto_descricao;

        $sql = "SELECT a.id, d.nome AS modelo,
                       DATE_FORMAT(c.data_inicio, '%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(c.data_termino, '%d/%m/%Y') AS data_termino,
                       b.nome AS candidato, 
                       CONCAT_WS('/', b.cargo , b.funcao) AS cargo_funcao,
                       a.estilo_personalidade_majoritario, 
                       a.estilo_personalidade_secundario
                FROM pesquisa_avaliadores a
                INNER JOIN usuarios b ON 
                           b.id = a.id_avaliador
                INNER JOIN pesquisa c ON
                           c.id = a.id_pesquisa
                INNER JOIN pesquisa_modelos d ON
                           d.id = c.id_modelo
                WHERE a.id = {$id}";
        $data['teste'] = $this->db->query($sql)->row();

        $this->db->select('NULL, SUM(IF(c.peso = 1, a.valor, 0)) AS sp_gv', false);
        $this->db->select('SUM(IF(c.peso = 2, a.valor, 0)) AS ct_tk', false);
        $this->db->select('SUM(IF(c.peso = 3, a.valor, 0)) AS cs_hd', false);
        $this->db->select('SUM(IF(c.peso = 4, a.valor, 0)) AS ad_dl', false);
        $this->db->join('pesquisa_perguntas b', 'b.id = a.id_pergunta');
        $this->db->join('pesquisa_alternativas c', 'c.id = a.id_alternativa');
        $this->db->where('id_avaliador', $id);
        $resultado = array_combine([null, 1, 2, 3, 4], $this->db->get('pesquisa_resultado a')->row_array());

        $indiceResposta = array_search(max($resultado), $resultado);

        $this->db->select('a.nome, a.estilo_personalidade_majoritario, a.estilo_personalidade_secundario');
        $this->db->select(["GROUP_CONCAT(DISTINCT b.nome ORDER BY b.nome SEPARATOR ';<br>') AS comportamentos_normais"], false);
        $this->db->select(["GROUP_CONCAT(DISTINCT c.nome ORDER BY c.nome SEPARATOR ';<br>') AS comportamentos_estresse"], false);
        $this->db->join('pesquisa_lifo_comportamentos b', "b.id_estilo = a.id AND b.situacao_comportamental = 'N'", 'left');
        $this->db->join('pesquisa_lifo_comportamentos c', "c.id_estilo = a.id AND c.situacao_comportamental = 'E'", 'left');
        $this->db->where('a.id_empresa', $empresa->id);
        $this->db->where('a.indice_resposta', $indiceResposta);
        $data['laudoPerfil'] = $this->db->get('pesquisa_lifo_estilos a')->row();

        if (!empty($data['teste']->estilo_personalidade_majoritario)) {
            $data['laudoPerfil']->estilo_personalidade_majoritario = $data['teste']->estilo_personalidade_majoritario;
        }
        if (!empty($data['teste']->estilo_personalidade_secundario)) {
            $data['laudoPerfil']->estilo_personalidade_secundario = $data['teste']->estilo_personalidade_secundario;
        }


        if ($pdf) {
            return $this->load->view('pesquisa_pdfLifo', $data, true);
        } else {
            $this->load->view('pesquisa_relatorioLifo', $data);
        }
    }

    public function savePerfilComportamento($idAvaliador)
    {
        $estilo_personalidade_majoritario = $this->input->post('estilo_personalidade_majoritario');
        if (strlen($estilo_personalidade_majoritario) == 0) {
            $estilo_personalidade_majoritario = null;
        }
        $estilo_personalidade_secundario = $this->input->post('estilo_personalidade_secundario');
        if (strlen($estilo_personalidade_secundario) == 0) {
            $estilo_personalidade_secundario = null;
        }

        $this->db->set('estilo_personalidade_majoritario', $estilo_personalidade_majoritario);
        $this->db->set('estilo_personalidade_secundario', $estilo_personalidade_secundario);
        $this->db->where('id', $idAvaliador);
        $status = $this->db->update('pesquisa_avaliadores');

        if ($status !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar o laudo', 'redireciona' => 0, 'pagina' => '')));
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Laudo salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('pesquisa_lifo/relatorio/' . $idAvaliador)));
    }

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.pesquisa thead th { font-size: 14px; padding: 5px; text-align: center; font-weight: bold; } ';
        $stylesheet .= 'table.pesquisa thead tr, table.recrutamento tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.pesquisa tbody tr th { font-size: 13px; padding: 2px; } ';
        $stylesheet .= 'table.pesquisa tbody tr:nth-child(2) td { border-top: 1px solid #ddd; } ';
        $stylesheet .= 'table.pesquisa tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.pesquisa tbody td strong { font-weight: bold; } ';
        $stylesheet .= 'table.pesquisa { border-bottom: 5px solid #ddd; } ';

        $stylesheet .= 'table.laudo tr th, table.resultado tr td { font-size: 14px !important; padding: 5px; } ';
        $stylesheet .= 'table.laudo thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.laudo thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.laudo tbody tr td.success { background-color: #dff0d8; } ';
        $stylesheet .= 'table.laudo tbody tr td:nth-child(1) { vertical-align: top; word-wrap: break-word; width: 45%; } ';
        $stylesheet .= 'table.laudo tbody tr td:nth-child(2) { vertical-align: top; word-wrap: break-word;  width: 45%; word-break: break-all; } ';

        $this->m_pdf->pdf->setTopMargin(60);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));

        $sql = "SELECT c.nome
                FROM pesquisa a
                INNER JOIN pesquisa_avaliadores b ON
                           b.id_pesquisa = a.id
                INNER JOIN usuarios c ON 
                           c.id = b.id_avaliador
                WHERE b.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output("LIFO - {$row->nome}.pdf", 'D');
    }

}
