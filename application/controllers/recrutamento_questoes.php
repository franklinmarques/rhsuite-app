<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_questoes extends MY_Controller
{

    protected $tipo_usuario = array('empresa', 'selecionador');

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar($id = null)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $recrutamento = $this->db->get_where('recrutamento_modelos', array('id' => $id))->row();
        $data['id_modelo'] = $recrutamento->id;
        $data['tipo'] = $recrutamento->tipo;
        $data['modelo'] = $recrutamento->nome;
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('recrutamento_questoes', $data);
    }

    public function texto($id = null)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $data['id_usuario'] = $this->session->userdata('id');

        $this->db->select('a.id AS id_modelo, a.nome, a.tipo, b.id, b.pergunta');
        $this->db->join('recrutamento_perguntas b', 'b.id_modelo = a.id', 'left');
        $this->db->where('a.id', $id);
        $this->db->where("(a.tipo = 'D' OR a.tipo = 'I')");
        $data = $this->db->get('recrutamento_modelos a')->row();

        $this->load->view('recrutamento_texto', $data);
    }

    public function personalidade($id = null)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $data['id_usuario'] = $this->session->userdata('id');

        $this->db->select('a.id AS id_modelo, a.nome AS modelo, a.tipo, b.id, b.pergunta');
        $this->db->join('recrutamento_perguntas b', 'b.id_modelo = a.id', 'left');
        $this->db->where('a.id', $id);
        $this->db->where('a.tipo', 'C');
        $data = $this->db->get('recrutamento_modelos a')->row();

        $this->load->view('recrutamento_personalidade', $data);
    }

    public function entrevistas($id = null)
    {
        $empresa = $this->session->userdata('empresa');
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $data['id_usuario'] = $this->session->userdata('id');


        $this->db->select('DISTINCT(cargo) AS nome', false);
        $this->db->where('id_usuario_EMPRESA', $empresa);
        $this->db->where('CHAR_LENGTH(funcao) >', 0);
        $this->db->order_by('cargo', 'asc');
        $rowsCargo = $this->db->get('cargos')->result();
        $data['cargo'] = array('' => 'selecione...');
        foreach ($rowsCargo as $rowCargo) {
            $data['cargo'][$rowCargo->nome] = $rowCargo->nome;
        }


        $this->db->select('DISTINCT(funcao) AS nome', false);
        $this->db->where('id_usuario_EMPRESA', $empresa);
        $this->db->where('CHAR_LENGTH(funcao) >', 0);
        $this->db->order_by('funcao', 'asc');
        $rowsFuncao = $this->db->get('cargos')->result();
        $data['funcao'] = array('' => 'selecione...');
        foreach ($rowsFuncao as $rowFuncao) {
            $data['funcao'][$rowFuncao->nome] = $rowFuncao->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('cargos b', 'b.id = a.id_cargo');
        $this->db->where('a.id_usuario_EMPRESA', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $rowsCompetencia = $this->db->get('competencias a')->result();
        $data['id_competencia'] = array('' => 'selecione...');
        foreach ($rowsCompetencia as $rowCompetencia) {
            $data['id_competencia'][$rowCompetencia->id] = $rowCompetencia->nome;
        }


        $this->db->select('a.id AS id_modelo, a.nome AS modelo, a.tipo, b.id, b.pergunta');
        $this->db->join('recrutamento_perguntas b', 'b.id_modelo = a.id', 'left');
        $this->db->where('a.id', $id);
        $this->db->where('a.tipo', 'E');
        $data['row'] = $this->db->get('recrutamento_modelos a')->row();

        $this->load->view('recrutamento_entrevistas', $data);
    }

    public function filtrarCompetencias()
    {
        $empresa = $this->session->userdata('empresa');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');
        $id_competencia = $this->input->post('id_competencia');

        $data = array(
            'funcao' => array('' => 'selecione...'),
            'id_competencia' => array('' => 'selecione...')
        );


        $this->db->select('DISTINCT(funcao) AS nome', false);
        $this->db->where('id_usuario_EMPRESA', $empresa);
        $this->db->where('CHAR_LENGTH(funcao) >', 0);
        if ($cargo) {
            $this->db->where('cargo', $cargo);
        }
        $this->db->order_by('funcao', 'asc');
        $rowsFuncao = $this->db->get('cargos')->result();
        $funcoes = array('' => 'selecione...');
        foreach ($rowsFuncao as $rowFuncao) {
            $funcoes[$rowFuncao->nome] = $rowFuncao->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('cargos b', 'b.id = a.id_cargo');
        $this->db->where('a.id_usuario_EMPRESA', $empresa);
        if ($cargo) {
            $this->db->where('b.cargo', $cargo);
        }
        if ($funcao) {
            $this->db->where('b.funcao', $funcao);
        }
        $this->db->order_by('a.nome', 'asc');
        $rowsCompetencia = $this->db->get('competencias a')->result();
        $competencias = array('' => 'selecione...');
        foreach ($rowsCompetencia as $rowCompetencia) {
            $competencias[$rowCompetencia->id] = $rowCompetencia->nome;
        }


        $data['funcao'] = form_dropdown('funcao', $funcoes, $funcao, 'id="funcao" class="form-control filtro input-sm"');
        $data['id_competencia'] = form_dropdown('id_competencia', $competencias, $id_competencia, 'id="id_competencia" class="form-control filtro input-sm"');

        echo json_encode($data);
    }

    public function ajax_list($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.pergunta, 
                       s.alternativa,
                       s.peso
                FROM (SELECT a.id,
                             a.pergunta,
                             b.alternativa,
                             b.peso
                      FROM recrutamento_perguntas a
                      LEFT JOIN recrutamento_alternativas b ON
                                 b.id_pergunta = a.id
                      WHERE a.id_modelo = {$id} 
                      ORDER BY a.id, 
                               b.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();
        $columns = array(
            's.id',
            's.pergunta',
            's.alternativa',
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
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";

        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $recrutamento) {
            $row = array();
            $row[] = $recrutamento->pergunta;
            $row[] = $recrutamento->alternativa;
            $row[] = $recrutamento->peso;

            $row[] = '
                      <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_pergunta(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pergunta(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
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

    public function ajax_personalidade($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.pergunta
                FROM (SELECT a.id,
                             a.pergunta,
                             a.tipo_eneagrama
                      FROM recrutamento_perguntas a
                      WHERE a.id_modelo = {$id} 
                      ORDER BY a.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();
        $columns = array(
            's.id',
            's.pergunta'
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
        foreach ($list as $recrutamento) {
            $row = array();
            $row[] = $recrutamento->pergunta;

            $row[] = '
                      <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_pergunta(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pergunta(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
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

    public function ajax_entrevistas($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $post = $this->input->post();

        $sql = "SELECT s.id_modelo, 
                       s.pergunta,
                       s.competencia
                FROM (SELECT a.id_modelo,
                             a.pergunta,
                             a.tipo_eneagrama, 
                             a.competencia
                      FROM recrutamento_perguntas a
                      WHERE a.id_modelo = {$id} 
                      ORDER BY a.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();
        $columns = array(
            's.id',
            's.pergunta',
            's.competencia',
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
        foreach ($list as $recrutamento) {
            $row = array();
            $row[] = $recrutamento->pergunta;
            $row[] = $recrutamento->competencia;

            $row[] = '
                      <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_pergunta(' . "'" . $recrutamento->id_modelo . "'" . ', ' . "'" . $recrutamento->competencia . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pergunta(' . "'" . $recrutamento->id_modelo . "'" . ', ' . "'" . $recrutamento->competencia . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
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

    public function ajax_resposta($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }

        $post = $this->input->post();

        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM recrutamento_alternativas a
                INNER JOIN recrutamento_modelos b ON
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
                LIMIT 0, 6";

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
        $data = $this->db->get('recrutamento_perguntas')->row();
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM recrutamento_alternativas a
                INNER JOIN recrutamento_perguntas b ON
                           b.id = a.id_pergunta
                WHERE a.id_pergunta = {$data->id}";
        $data->alternativas = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function edit_personalidade($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get('recrutamento_perguntas')->row();

        echo json_encode($data);
    }

    public function edit_entrevista()
    {
        $id_modelo = $this->input->get('id_modelo');
        $competencia = $this->input->get('competencia');

        $this->db->select('id_modelo, tipo_resposta, id_competencia, competencia');
        $this->db->select('NULL AS perguntas', false);
        $this->db->where('id_modelo', $id_modelo);
        $this->db->where('competencia', $competencia);
        $data = $this->db->get('recrutamento_perguntas')->row();
        $data->perguntas = array();

        $this->db->select('id, pergunta');
        $this->db->where('id_modelo', $id_modelo);
        $this->db->where('competencia', $competencia);
        $data->perguntas = $this->db->get('recrutamento_perguntas')->result();

        echo json_encode($data);
    }

    public function ajax_editResposta($id)
    {
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM recrutamento_alternativas a
                INNER JOIN recrutamento_modelos b ON
                           b.id = a.id_modelo
                WHERE b.id = {$id}
                LIMIT 0, 6";

        $data = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $this->db->where('id', $this->input->post('id_modelo'));
        $modelo = $this->db->get('recrutamento_modelos')->row();
        if (empty($modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de testes de seleção não foi encontrado')));
        }
        $data = array(
            'id_modelo' => $modelo->id,
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => $modelo->tipo === 'D' ? 'A' : 'U',
//            'tipo_resposta' => $this->input->post('tipo_resposta'),
            'justificativa' => $this->input->post('justificativa'),
            'valor_min' => $this->input->post('valor_min'),
            'valor_max' => $this->input->post('valor_max')
        );
        if (!in_array($data['tipo_resposta'], array('N', 'M'))) {
            $data['valor_min'] = null;
            $data['valor_max'] = null;
        }

        $this->db->trans_begin();

        $this->db->query($this->db->insert_string('recrutamento_perguntas', $data));

        $id_pergunta = $this->db->insert_id();
        if (in_array($data['tipo_resposta'], array('U', 'M', 'V'))) {
            $alternativas = array_filter($this->input->post('alternativa'));
        } else {
            $alternativas = array();
        }

        $peso = $this->input->post('peso');

        foreach ($alternativas as $k => $alternativa) {
            $data = array(
                'id_modelo' => $modelo->id,
                'id_pergunta' => $id_pergunta,
                'alternativa' => $alternativa,
                'peso' => $peso[$k]
            );

            $this->db->query($this->db->insert_string('recrutamento_alternativas', $data));
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

    public function ajax_update()
    {
        $this->db->where('id', $this->input->post('id_modelo'));
        $modelo = $this->db->get('recrutamento_modelos')->row();
        if (empty($modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de testes de seleção não foi encontrado')));
        }
        $data = array(
            'id_modelo' => $modelo->id,
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => $modelo->tipo === 'D' ? 'A' : 'U',
//            'tipo_resposta' => $this->input->post('tipo_resposta'),
            'justificativa' => $this->input->post('justificativa'),
            'valor_min' => $this->input->post('valor_min'),
            'valor_max' => $this->input->post('valor_max')
        );
        if (!in_array($data['tipo_resposta'], array('N', 'M'))) {
            $data['valor_min'] = null;
            $data['valor_max'] = null;
        }

        $this->db->trans_begin();

        $update_string = $this->db->update_string('recrutamento_perguntas', $data, array('id' => $this->input->post('id')));
        $this->db->query($update_string);

        $id_pergunta = $this->input->post('id');
        $id_alternativa = $this->input->post('id_alternativa');
        if (in_array($data['tipo_resposta'], array('U', 'M', 'V'))) {
            $alternativas = $this->input->post('alternativa');
        } else {
            $alternativas = array_pad(array(), 6, '');
        }

        $peso = $this->input->post('peso');

        foreach ($alternativas as $k => $alternativa) {
            $data = array(
                'id_modelo' => $modelo->id,
                'id_pergunta' => $id_pergunta,
                'alternativa' => $alternativa,
                'peso' => $peso[$k]
            );
            if ($alternativa) {
                if ($id_alternativa[$k]) {
                    $update_string = $this->db->update_string('recrutamento_alternativas', $data, array('id' => $id_alternativa[$k]));
                    $this->db->query($update_string);
                } else {
                    $insert_string = $this->db->insert_string('recrutamento_alternativas', $data);
                    $this->db->query($insert_string);
                }
            } elseif ($id_alternativa[$k]) {
                $this->db->query("DELETE FROM recrutamento_alternativas WHERE id = $id_alternativa[$k]");
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

                    $update_string = $this->db->update_string('recrutamento_alternativas', $data, $where);
                    $this->db->query($update_string);
                } else {

                    $insert_string = $this->db->insert_string('recrutamento_alternativas', $data);
                    $this->db->query($insert_string);
                }
            } elseif ($id_alternativas[$k]) {

                $this->db->query("DELETE FROM recrutamento_alternativas WHERE id = $id_alternativas[$k]");
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

    public function salvar_texto()
    {
        $data = $this->input->post();
        $this->db->trans_begin();

        if ($data['id']) {
            if ($data['pergunta']) {
                $sql = $this->db->update_string('recrutamento_perguntas', $data, array('id' => $data['id']));
                $erro = 'Erro ao atualizar texto, tente novamente';
            } else {
                $sql = "DELETE FROM recrutamento_perguntas WHERE id = {$data['id']}";
                $erro = 'Erro ao limpar texto, tente novamente';
            }
            $this->db->query($sql);
        } else {
            if ($data['pergunta']) {
                $erro = 'Erro ao salvar texto, tente novamente';
                $this->db->query($this->db->insert_string('recrutamento_perguntas', $data));
            } else {
                $data['pergunta'] = null;
                $erro = 'O texto é obrigatório';
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE or $data['pergunta'] === null) {
            $this->db->trans_rollback();
            exit(json_encode(array('retorno' => 0, 'aviso' => $erro)));
        }
        $this->db->trans_commit();
        echo json_encode(array('retorno' => 1, 'aviso' => 'Texto salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('recrutamento_modelos')));
    }

    public function ajax_delete($id)
    {
        $sql = "DELETE a FROM recrutamento_alternativas a
                INNER JOIN recrutamento_perguntas b ON
                           b.id = a.id_pergunta
                WHERE a.id_pergunta = ?";
        $this->db->trans_begin();
        $this->db->query($sql, $id);
        $this->db->query("DELETE FROM recrutamento_perguntas WHERE id = ?", $id);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function add_personalidade()
    {
        $this->db->where('id', $this->input->post('id_modelo'));
        $modelo = $this->db->get('recrutamento_modelos')->row();
        if (empty($modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de testes de seleção não foi encontrado')));
        }
        $tipoEneagrama = $this->input->post('tipo_eneagrama');
        $data = array(
            'id_modelo' => $modelo->id,
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'U',
            'tipo_eneagrama' => ($tipoEneagrama ? $tipoEneagrama : null)
        );

        $this->db->trans_begin();

        $this->db->query($this->db->insert_string('recrutamento_perguntas', $data));

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo json_encode(array("status" => $status !== false));
    }

    public function update_personalidade()
    {
        $this->db->where('id', $this->input->post('id_modelo'));
        $modelo = $this->db->get('recrutamento_modelos')->row();
        if (empty($modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de testes de seleção não foi encontrado')));
        }
        $tipoEneagrama = $this->input->post('tipo_eneagrama');
        $data = array(
            'id_modelo' => $modelo->id,
            'pergunta' => $this->input->post('pergunta'),
            'tipo_eneagrama' => ($tipoEneagrama ? $tipoEneagrama : null)
        );

        $this->db->trans_begin();

        $update_string = $this->db->update_string('recrutamento_perguntas', $data, array('id' => $this->input->post('id')));
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

    public function delete_personalidade($id)
    {

        $this->db->trans_begin();
        $this->db->query("DELETE FROM recrutamento_perguntas WHERE id = ?", $id);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function add_entrevista()
    {
        $this->db->where('id', $this->input->post('id_modelo'));
        $modelo = $this->db->get('recrutamento_modelos')->row();
        if (empty($modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de testes de seleção não foi encontrado')));
        }
        $perguntas = $this->input->post('pergunta');
        $id_competencia = $this->input->post('id_competencia');
        $competencia = $this->input->post('competencia');

        $data = array();
        foreach ($perguntas as $pergunta) {
            if (strlen($pergunta) > 0) {
                $data[] = array(
                    'id_modelo' => $modelo->id,
                    'pergunta' => $pergunta,
                    'tipo_resposta' => 'A',
                    'id_competencia' => $id_competencia ?? null,
                    'competencia' => $competencia ?? null
                );
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Todas as questões  devem ser preenchidas')));
            }
        }

        $this->db->trans_begin();

        $this->db->insert_batch('recrutamento_perguntas', $data);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        echo json_encode(array("status" => $status !== false));
    }

    public function update_entrevista()
    {
        $this->db->where('id', $this->input->post('id_modelo'));
        $modelo = $this->db->get('recrutamento_modelos')->row();
        if (empty($modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de testes de seleção não foi encontrado')));
        }
        $id = $this->input->post('id');
        $perguntas = $this->input->post('pergunta');
        $id_competencia = $this->input->post('id_competencia');
        $competencia = $this->input->post('competencia');

        $data = array();
        foreach ($perguntas as $k => $pergunta) {
            if (strlen($pergunta) > 0 or empty($id[$k])) {
                $data[] = array(
                    'id' => $id[$k],
                    'pergunta' => $pergunta,
                    'id_competencia' => $id_competencia ?? null,
                    'competencia' => $competencia ?? null
                );
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Todas as questões  devem ser preenchidas')));
            }
        }

        $this->db->trans_start();

        $this->db->update_batch('recrutamento_perguntas', $data, 'id');

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function delete_entrevista()
    {
        $id_modelo = $this->input->post('id_modelo');
        $competencia = $this->input->post('competencia');
        $where = array(
            'id_modelo' => $id_modelo,
            'competencia' => $competencia
        );

        $this->db->trans_start();

        $this->db->delete('recrutamento_perguntas', $where);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

}
