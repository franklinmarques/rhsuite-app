<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PesquisaQuati extends MY_Controller
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

        $this->load->view('pesquisa_quati2', $data);
    }

    public function gerenciar($id = null)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $pesquisa = $this->db->get_where('pesquisa_modelos', array('id' => $id))->row();
        if ($pesquisa->exclusao_bloqueada) {
            redirect(site_url('pesquisa_modelos'));
            exit;
        }
        $data['id_modelo'] = $pesquisa->id;
        $data['modelo'] = $pesquisa->nome;
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('pesquisa_quati', $data);
    }

    public function ajaxList($id)
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
                            AND b.tipo = 'Q'
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
                              <a class="btn btn-sm btn-primary btn-block" href="pesquisaQuati/teste/' . $pesquisa->id . '" target="_blank" title="Iniciar teste">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Iniciar teste</a>
                             ';
                    break;
                case 'executando':
                    $row[] = '
                              <a class="btn btn-sm btn-primary btn-block" href="pesquisaQuati/teste/' . $pesquisa->id . '" target="_blank" title="Iniciar teste">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Finalizado</a>
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
                              <a class="btn btn-sm btn-success btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Teste concluído&nbsp;</a>
                             ';
                    break;
                default:
                    $row[] = '
                              <a class="btn btn-sm btn-primary btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Iniciar teste</a>
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

    public function ajaxList2($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.pergunta,
                       s.alternativa,
                       s.escala,
                       s.peso
                FROM (SELECT b.id,
                             b.pergunta,
                             c.alternativa,
                             NULL AS escala,
                             c.peso
                      FROM pesquisa_modelos a
                      INNER JOIN pesquisa_perguntas b ON
                                 b.id_modelo = a.id
                      INNER JOIN pesquisa_alternativas c ON
                                 c.id_pergunta = b.id
                      WHERE a.id = {$id} 
                      ORDER BY b.id, 
                               c.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();
        $columns = array(
            's.id',
            's.pergunta',
            's.alternativa',
            's.escala',
            's.peso'
        );
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
            $row[] = $pesquisa->pergunta;
            $row[] = $pesquisa->alternativa;
            $row[] = $pesquisa->peso;

            $row[] = '
                      <button class="btn btn-sm btn-info" title="Editar" onclick="edit_pergunta(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_pergunta(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
                     ';

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

    public function ajaxEdit($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get('pesquisa_perguntas')->row();
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM pesquisa_alternativas a
                INNER JOIN pesquisa_perguntas b ON
                           b.id = a.id_pergunta AND
                           b.id_modelo = a.id_modelo
                WHERE a.id_pergunta = {$data->id}";
        $data->alternativas = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajaxEditEstilo()
    {
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('pesquisa_quati_estilos')->row();

        echo json_encode($data);
    }

    public function ajaxEditCaracteristica()
    {
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('pesquisa_quati_caracteristicas')->row();

        echo json_encode($data);
    }

    public function ajaxAdd()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'U'
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('erro' => 'O modelo de pesquisa não foi encontrado')));
        }

        $this->db->trans_begin();

        $this->db->query($this->db->insert_string('pesquisa_perguntas', $data));

        $id_modelo = $data['id_modelo'];
        $id_pergunta = $this->db->insert_id();
        $alternativas = array_filter($this->input->post('alternativa'));
        $peso = $this->input->post('peso');

        foreach ($alternativas as $k => $alternativa) {
            $data = array(
                'id_modelo' => $id_modelo,
                'id_pergunta' => $id_pergunta,
                'alternativa' => $alternativa,
                'peso' => $peso[$k]
            );

            $this->db->query($this->db->insert_string('pesquisa_alternativas', $data));
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

    public function ajax_addEstilo()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome do estilo é obrigatório')));
        }
        if (!empty($data['perfil_preponderante']) == false) {
            exit(json_encode(array('erro' => 'O campo Perfil preponderante é obrigatório')));
        }
        if (!empty($data['atitude_primaria']) == false) {
            exit(json_encode(array('erro' => 'O campo Atitude primária é obrigatório')));
        }
        if (!empty($data['atitude_secundaria']) == false) {
            exit(json_encode(array('erro' => 'O campo Atitude secundária é obrigatório')));
        }

        if (strlen($data['laudo_comportamental_padrao']) == 0) {
            $data['laudo_comportamental_padrao'] = null;
        }
        $data['id_empresa'] = $this->session->userdata('empresa');
//        $this->db->where('id_empresa', $data['id_empresa']);
//        $this->db->where('nome', $data['nome']);
//        $this->db->where('indice_resposta', $data['indice_resposta']);
//        $count = $this->db->get('pesquisa_quati_estilos')->num_rows();
//
//        if ($count > 0) {
//            exit(json_encode(array('erro' => 'O estilo já está cadastrado')));
//        }

        $status = $this->db->insert('pesquisa_quati_estilos', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addCaracteristica()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome da característica comportamental é obrigatório')));
        }
        if (empty($data['tipo_comportamental'])) {
            exit(json_encode(array('erro' => 'O tipo comportamental é obrigatório')));
        }
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('tipo_comportamental', $data['tipo_comportamental']);
        $this->db->where('nome', $data['nome']);
        $count = $this->db->get('pesquisa_quati_caracteristicas')->num_rows();

        if ($count > 0) {
            exit(json_encode(array('erro' => 'A característica comportamental já está cadastrada')));
        }

        $status = $this->db->insert('pesquisa_quati_caracteristicas', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxUpdate()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'U'
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('erro' => 'O modelo de pesquisa não foi encontrado')));
        }

        $this->db->trans_begin();

        $update_string = $this->db->update_string('pesquisa_perguntas', $data, array('id' => $this->input->post('id')));
        $this->db->query($update_string);

        $id_modelo = $data['id_modelo'];
        $id_pergunta = $this->input->post('id');
        $id_alternativa = $this->input->post('id_alternativa');
        $alternativas = $this->input->post('alternativa');
        $peso = $this->input->post('peso');

        foreach ($alternativas as $k => $alternativa) {
            $data = array(
                'id_modelo' => $id_modelo,
                'id_pergunta' => $id_pergunta,
                'alternativa' => $alternativa,
                'peso' => $peso[$k]
            );
            if ($alternativa) {
                if ($id_alternativa[$k]) {
                    $update_string = $this->db->update_string('pesquisa_alternativas', $data, array('id' => $id_alternativa[$k]));
                    $this->db->query($update_string);
                } else {
                    $insert_string = $this->db->insert_string('pesquisa_alternativas', $data);
                    $this->db->query($insert_string);
                }
            } elseif ($id_alternativa[$k]) {
                $this->db->query("DELETE FROM pesquisa_alternativas WHERE id = $id_alternativa[$k]");
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

    public function ajax_updateEstilo()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome do estilo é obrigatório')));
        }
        if (!empty($data['perfil_preponderante']) == false) {
            exit(json_encode(array('erro' => 'O campo Perfil preponderante é obrigatório')));
        }
        if (!empty($data['atitude_primaria']) == false) {
            exit(json_encode(array('erro' => 'O campo Atitude primária é obrigatório')));
        }
        if (!empty($data['atitude_secundaria']) == false) {
            exit(json_encode(array('erro' => 'O campo Atitude secundária é obrigatório')));
        }

        if (strlen($data['laudo_comportamental_padrao']) == 0) {
            $data['laudo_comportamental_padrao'] = null;
        }
        $data['id_empresa'] = $this->session->userdata('empresa');
        $id = $data['id'];
        unset($data['id']);
//        $this->db->where('id !=', $id);
//        $this->db->where('id_empresa', $data['id_empresa']);
//        $this->db->where('nome', $data['nome']);
//        $this->db->where('indice_resposta', $data['tipo_atitude']);
//        $count = $this->db->get('pesquisa_quati_estilos')->num_rows();
//
//        if ($count > 0) {
//            exit(json_encode(array('erro' => 'O estilo já está cadastrado')));
//        }

        $status = $this->db->update('pesquisa_quati_estilos', $data, ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateCaracteristica()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome da característica comportamental é obrigatória')));
        }
        if (empty($data['tipo_comportamental'])) {
            exit(json_encode(array('erro' => 'O tipo comportamental é obrigatório')));
        }
        $id = $data['id'];
        unset($data['id']);
        $this->db->where('id !=', $id);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('tipo_comportamental', $data['tipo_comportamental']);
        $this->db->where('nome', $data['nome']);
        $count = $this->db->get('pesquisa_quati_caracteristicas')->num_rows();

        if ($count > 0) {
            exit(json_encode(array('erro' => 'A característica comportamental já está cadastrada')));
        }

        $status = $this->db->update('pesquisa_quati_caracteristicas', $data, ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDelete($id)
    {
        $sql = "DELETE a FROM pesquisa_alternativas a
                INNER JOIN pesquisa_perguntas b ON
                           b.id = a.id_pergunta AND
                           b.id_modelo = a.id_modelo
                WHERE a.id_pergunta = ?";
        $this->db->trans_begin();
        $this->db->query($sql, $id);
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
        $status = $this->db->delete('pesquisa_quati_estilos', ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_deleteCaracteristica()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('pesquisa_quati_caracteristicas', ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function estilos()
    {
        $this->load->view('pesquisa_quati_estilos');
    }

    public function caracteristicas()
    {
        $data['idEmpresa'] = $this->session->userdata('empresa');

        $this->load->view('pesquisa_quati_caracteristicas', $data);
    }

    public function ajaxEstilos()
    {
        $post = $this->input->post();

        $this->db->select('id, nome');
        $this->db->select("(CASE perfil_preponderante WHEN 'X' THEN 'Introvertido' WHEN 'Y' THEN 'Extrovertido' END) AS perfil_preponderante");
        $this->db->select("(CASE atitude_primaria WHEN 'I' THEN 'Intuitivo' WHEN 'S' THEN 'Sensitivo' WHEN 'R' THEN 'Racional' WHEN 'E' THEN 'Emocional' END) AS atitude_primaria");
        $this->db->select("(CASE atitude_secundaria WHEN 'I' THEN 'Intuitivo' WHEN 'S' THEN 'Sensitivo' WHEN 'R' THEN 'Racional' WHEN 'E' THEN 'Emocional' END) AS atitude_secundaria");
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $rows = $this->db->get('pesquisa_quati_estilos')->result();

        $data = array();
        foreach ($rows as $row) {
            $data[] = array(
                $row->nome,
                $row->perfil_preponderante,
                $row->atitude_primaria,
                $row->atitude_secundaria,
                '<button class="btn btn-sm btn-info" title="Editar" onclick="edit_estilo(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_estilo(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>'
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

    public function ajaxCaracteristicas()
    {
        $post = $this->input->post();
        $output = array('draw' => $this->input->post('draw'));

        $this->db->select('id, nome');
        $this->db->select("CASE tipo_comportamental WHEN 'X' THEN 'Introvertido' WHEN 'Y' THEN 'Extrovertido' WHEN 'I' THEN 'Intuitivo' WHEN 'S' THEN 'Sensitivo' WHEN 'R' THEN 'Racional' WHEN 'E' THEN 'Emocional' END AS tipo_comportamental", false);
        $this->db->where('id_empresa', $post['id_empresa']);
        if ($post['tipo_comportamental']) {
            $this->db->where('tipo_comportamental', $post['tipo_comportamental']);
        }
        $output['recordsTotal'] = $this->db->get('pesquisa_quati_caracteristicas')->num_rows();

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
                $row->tipo_comportamental,
                '<button class="btn btn-sm btn-info" title="Editar" onclick="edit_caracteristica(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_caracteristica(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }
        $output['data'] = $data;

        echo json_encode($output);
    }

    public function relatorio($id, $pdf = false)
    {
        $this->db->select('id, foto, foto_descricao');
        $empresa = $this->db->get_where('usuarios', array('id' => $this->session->userdata('empresa')))->row();
        $data['foto'] = 'imagens/usuarios/' . $empresa->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $empresa->foto_descricao;

        $sql = "SELECT a.id, 'Personalidade - Tipologia de JUNG' AS modelo,
                       DATE_FORMAT(c.data_inicio, '%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(c.data_termino, '%d/%m/%Y') AS data_termino,
                       b.nome AS candidato, 
                       CONCAT_WS('/', b.cargo , b.funcao) AS cargo_funcao,
                       a.laudo_comportamental_padrao,
                       d.id AS id_modelo
                FROM pesquisa_avaliadores a
                INNER JOIN usuarios b ON 
                           b.id = a.id_avaliador
                INNER JOIN pesquisa c ON
                           c.id = a.id_pesquisa
                INNER JOIN pesquisa_modelos d ON
                           d.id = c.id_modelo
                WHERE a.id = {$id}";
        $data['teste'] = $this->db->query($sql)->row();


        $sql2 = "SELECT SUM(IF(s.tipo = 'X', 1, 0)) AS X,
                        SUM(IF(s.tipo = 'Y', 1, 0)) AS Y,
                        SUM(IF(s.tipo = 'I', 1, 0)) AS I,
                        SUM(IF(s.tipo = 'S', 1, 0)) AS S,
                        SUM(IF(s.tipo = 'R', 1, 0)) AS R,
                        SUM(IF(s.tipo = 'E', 1, 0)) AS E
                 FROM (SELECT CASE WHEN (b.rownum % 3) = 1 and c.rownum = 1 THEN 'X'
                                   WHEN (b.rownum % 3) = 1 and c.rownum = 2 THEN 'Y'
                                   WHEN (b.rownum % 3) = 2 and c.rownum = 1 THEN 'I'
                                   WHEN (b.rownum % 3) = 2 and c.rownum = 2 THEN 'S'
                                   WHEN (b.rownum % 3) = 0 and c.rownum = 1 THEN 'R'
                                   WHEN (b.rownum % 3) = 0 and c.rownum = 2 THEN 'E'
                                   END AS tipo
                       FROM pesquisa_resultado a
                       INNER JOIN (SELECT @rownum:= IF(@id_pergunta = b2.id, @rownum, @rownum + 1) as rownum,
                                          @id_pergunta:= b2.id, b2.*
                                   FROM pesquisa_perguntas b2,
                                   (SELECT @rownum:= 0, @id_pergunta:= 0) x
                                   WHERE b2.id_modelo = '{$data['teste']->id_modelo}') b ON 
                                  b.id = a.id_pergunta
                       INNER JOIN (SELECT @rownum2:= IF(@id_pergunta2 = c2.id_pergunta, @rownum2 + 1, 1) as rownum,
                                          @id_pergunta2:= c2.id_pergunta, c2.*
                                   FROM pesquisa_alternativas c2,
                                   (SELECT @rownum2:= 0, @id_pergunta2:= 0) y
                                   WHERE c2.id_modelo = '{$data['teste']->id_modelo}') c ON 
                                  c.id = a.id_alternativa
                       WHERE a.id_avaliador = '{$id}') s";
        $estilo = $this->db->query($sql2)->row_array();
        $data['totalTipos'] = $estilo;

        $interacao = array_intersect_key($estilo, array_flip(['X', 'Y']));
        $percepcao = array_intersect_key($estilo, array_flip(['I', 'S']));
        $decisao = array_intersect_key($estilo, array_flip(['R', 'E']));

        $perfilPreponderante = array_search(max($interacao), $interacao);
        $maxPercepcao = array_search(max($percepcao), $percepcao);
        $maxDecisao = array_search(max($decisao), $decisao);

        if ($decisao[$maxDecisao] < $percepcao[$maxPercepcao]) {
            $atitudePrimaria = $maxPercepcao;
            $atitudeSecundaria = $maxDecisao;
        } else {
            $atitudePrimaria = $maxDecisao;
            $atitudeSecundaria = $maxPercepcao;
        }


        $this->db->select('a.nome, a.laudo_comportamental_padrao');
        $this->db->select(["GROUP_CONCAT(DISTINCT b.nome ORDER BY b.nome SEPARATOR ';<br>') AS perfil_preponderante"], false);
        $this->db->select(["GROUP_CONCAT(DISTINCT c.nome ORDER BY c.nome SEPARATOR ';<br>') AS atitude_primaria"], false);
        $this->db->select(["GROUP_CONCAT(DISTINCT d.nome ORDER BY d.nome SEPARATOR ';<br>') AS atitude_secundaria"], false);
        $this->db->join('pesquisa_quati_caracteristicas b', 'b.tipo_comportamental = a.perfil_preponderante', 'left');
        $this->db->join('pesquisa_quati_caracteristicas c', 'c.tipo_comportamental = a.atitude_primaria', 'left');
        $this->db->join('pesquisa_quati_caracteristicas d', 'd.tipo_comportamental = a.atitude_secundaria', 'left');
        $this->db->where('a.id_empresa', $empresa->id);
        $this->db->where('a.perfil_preponderante', $perfilPreponderante);
        $this->db->where('a.atitude_primaria', $atitudePrimaria);
        $this->db->where('a.atitude_secundaria', $atitudeSecundaria);
        $data['laudoPerfil'] = $this->db->get('pesquisa_quati_estilos a')->row();

        if (!empty($data['teste']->laudo_comportamental_padrao)) {
            $data['laudoPerfil']->laudo_comportamental_padrao = $data['teste']->laudo_comportamental_padrao;
        }


        if ($pdf) {
            return $this->load->view('pesquisa_pdfQuati', $data, true);
        } else {
            $this->load->view('pesquisa_relatorioQuati', $data);
        }
    }


    public function savePerfilComportamento($idAvaliador)
    {
        $laudo_comportamental_padrao = $this->input->post('laudo_comportamental_padrao');
        if (strlen($laudo_comportamental_padrao) == 0) {
            $laudo_comportamental_padrao = null;
        }

        $this->db->set('laudo_comportamental_padrao', $laudo_comportamental_padrao);
        $this->db->where('id', $idAvaliador);
        $status = $this->db->update('pesquisa_avaliadores');

        if ($status !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar o laudo', 'redireciona' => 0, 'pagina' => '')));
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Laudo salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('pesquisa_lifo/relatorio/' . $idAvaliador)));
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
                       IF(a.data_acesso IS NOT NULL, IF(TIME_TO_SEC(TIMEDIFF(NOW(), a.data_acesso)) > 5400, '00:00:00', TIMEDIFF('01:30:00', TIMEDIFF(NOW(), a.data_acesso))), null) AS data_acesso,
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
            $data['tempo_restante'] = '01:30:00';
        }

        $this->load->view('pesquisa_quati_teste', $data);
    }

    public function finalizar($idAvaliador)
    {
        $respostas = $this->input->post('resposta');

        $this->db->select('id, peso');
        $this->db->where_in('id', array_values($respostas));
        $rows = $this->db->get('pesquisa_alternativas')->result();
        $pesos = array_column($rows, 'peso', 'id');

        $dataAvaliacao = date('Y-m-d H:i:s');

        $data = array();

        $this->db->trans_start();

        $this->db->set('data_finalizacao', date('Y-m-d H-i-s'));
        $this->db->where('id', $idAvaliador);
        $this->db->update('pesquisa_avaliadores');

        $this->db->where('id_avaliador', $idAvaliador);
        $this->db->delete('pesquisa_resultado');

        foreach ($respostas as $idPergunta => $idAlternativa) {
            $data[] = array(
                'id_avaliador' => $idAvaliador,
                'id_pergunta' => $idPergunta,
                'id_alternativa' => $idAlternativa,
                'valor' => $pesos[$idAlternativa],
                'resposta' => null,
                'data_avaliacao' => $dataAvaliacao
            );
        }

        $this->db->insert_batch('pesquisa_resultado', $data);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
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

        $this->m_pdf->pdf->Output("QUATI - {$row->nome}.pdf", 'D');
    }

}
