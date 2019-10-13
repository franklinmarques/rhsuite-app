<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_modelos extends MY_Controller
{

    protected $tipo_usuario = array('empresa', 'selecionador');

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => '',
            'nome' => 'Modelo de recrutamento'
        );

        $this->load->view('recrutamento_modelos', $data);
    }

    public function instrucoes()
    {
        $this->db->where('id', $this->uri->rsegment(3));
        $modelo = $this->db->get('recrutamento_modelos')->row();
        $data = array(
            'modelo' => $modelo->id,
            'nome' => $modelo->nome,
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => '',
            'instrucoes' => $modelo->instrucoes
        );

        $this->load->view('recrutamento_instrucoes', $data);
    }

    public function ajax_list($id)
    {
        if (empty($id)) {
            $id = $this->session->userdata('empresa');
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.empresa,
                       s.tipo,
                       s.observacoes
                FROM (SELECT a.id, 
                             a.nome, 
                             a.id_usuario_EMPRESA AS empresa,
                             (case tipo 
                              when 'M' then 'Matemática' 
                              when 'R' then 'Raciocínio Lógico'
                              when 'P' then 'Português'
                              when 'C' then 'Personalidade-Eneagrama'
                              when 'L' then 'Liderança'
                              when 'D' then 'Digitação'
                              when 'I' then 'Interpretação'
                              when 'T' then 'Conhecimento técnico'
                              when 'A' then 'Conhecimento comportamental'
                              when 'E' then 'Entrevista por competência'
                              else '' end) AS tipo,
                             a.observacoes
                      FROM recrutamento_modelos a
                      WHERE a.id_usuario_EMPRESA = {$id}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.tipo');
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
            $row[] = $recrutamento->nome;
            $row[] = $recrutamento->tipo;

            if ($recrutamento->tipo === 'Digitação' || $recrutamento->tipo === 'Interpretação') {
                $row[] = '
			              <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			              <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_modelos/instrucoes/' . $recrutamento->id) . '" title="Editar instruções" ><i class="glyphicon glyphicon-pencil"></i> Editar instruções</a>
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_questoes/texto/' . $recrutamento->id) . '" title="Editar texto" ><i class="glyphicon glyphicon-list"></i> Editar texto&emsp;&emsp;</a>
                         ';
            } elseif ($recrutamento->tipo === 'Personalidade-Eneagrama') {
                $row[] = '
			              <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			              <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_modelos/instrucoes/' . $recrutamento->id) . '" title="Editar instruções" ><i class="glyphicon glyphicon-pencil"></i> Editar instruções</a>
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_questoes/personalidade/' . $recrutamento->id) . '" title="Editar questões" ><i class="glyphicon glyphicon-list"></i> Editar questões</a>
                         ';
            } elseif ($recrutamento->tipo === 'Entrevista por competência') {
                $row[] = '
			              <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			              <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_modelos/instrucoes/' . $recrutamento->id) . '" title="Editar instruções" ><i class="glyphicon glyphicon-pencil"></i> Editar instruções</a>
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_questoes/entrevistas/' . $recrutamento->id) . '" title="Editar questões" ><i class="glyphicon glyphicon-list"></i> Editar questões</a>
                         ';
            } else {
                $row[] = '
			              <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			              <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_modelos/instrucoes/' . $recrutamento->id) . '" title="Editar instruções" ><i class="glyphicon glyphicon-pencil"></i> Editar instruções</a>
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_questoes/gerenciar/' . $recrutamento->id) . '" title="Editar questões" ><i class="glyphicon glyphicon-list"></i> Editar questões</a>
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

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('recrutamento_modelos', array('id' => $id))->row();
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $perguntas = $this->input->post('perguntas');
        $alternativas = $this->input->post('alternativas');
        $aleatorizacao = $perguntas && $alternativas ? 'T' : $perguntas . $alternativas;
        $data = array(
            'id_usuario_EMPRESA' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $this->input->post('tipo'),
            'observacoes' => $this->input->post('observacoes'),
            'aleatorizacao' => strlen($aleatorizacao) > 0 ? $aleatorizacao : null
        );
        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }
        $status = $this->db->insert('recrutamento_modelos', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $perguntas = $this->input->post('perguntas');
        $alternativas = $this->input->post('alternativas');
        $aleatorizacao = $perguntas && $alternativas ? 'T' : $perguntas . $alternativas;
        $data = array(
            'id_usuario_EMPRESA' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $this->input->post('tipo'),
            'observacoes' => $this->input->post('observacoes'),
            'aleatorizacao' => strlen($aleatorizacao) > 0 ? $aleatorizacao : null
        );
        if (empty($data['nome'])) {
//            set_status_header(401, utf8_decode('sessão expirada'));
            die(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }

        $this->db->trans_begin();

        $update = $this->db->update_string('recrutamento_modelos', $data, array('id' => $this->input->post('id')));
        $this->db->query($update);

        if (empty($perguntas)) {
            $where = array(
                'id_modelo' => $this->input->post('id'),
                'aleatorizacao !=' => 'A'
            );
            $update2 = $this->db->update_string('recrutamento_testes', array('aleatorizacao' => null), $where);
        }
        if (empty($alternativas)) {
            $where = array(
                'id_modelo' => $this->input->post('id'),
                'aleatorizacao !=' => 'P'
            );
            $update2 = $this->db->update_string('recrutamento_testes', array('aleatorizacao' => null), $where);
        }
        $this->db->query($update2);

        $this->db->trans_complete();

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function salvar_instrucoes()
    {
        if (empty($this->input->post('id'))) {
            die(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não foi encontrado')));
        }

        if (strlen($this->input->post('instrucoes')) > 0) {
            $data = array('instrucoes' => $this->input->post('instrucoes'));
        } else {
            $data = array('instrucoes' => null);
        }

        if (!$this->db->update('recrutamento_modelos', $data, array('id' => $this->input->post('id')))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar instruções, tente novamente')));
        }
        echo json_encode(array('retorno' => 1, 'aviso' => 'Instruções editadas com sucesso', 'redireciona' => 1, 'pagina' => site_url('recrutamento_modelos')));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('recrutamento_modelos', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

}
