<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Assessments_modelos extends MY_Controller
{

    public function index()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => '',
            'nome' => 'Modelo de recrutamento'
        );
        $this->load->view('assessments_modelos', $data);
    }

    public function ajaxList()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, s.nome, s.nome_tipo, s.tipo 
                FROM (SELECT id, nome,
                             tipo,
                             (case tipo 
                              when 'C' then 'Perfil Comportamental'
                              when 'N' then 'Potencial - NineBox'
                              else '' end) AS nome_tipo 
                      FROM assessments_modelos 
                      WHERE id_empresa = {$this->session->userdata('empresa')}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.nome_tipo');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                $sql .= ($key > 1 ? ' OR' : ' WHERE') . " {$column} LIKE '%{$post['search']['value']}%'";
            }
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();

        $data = array();
        foreach ($rows as $row) {
            if ($row->tipo == 'C') {
                $acoes = '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			              <a class="btn btn-sm btn-primary" href="' . site_url('assessments_modelos/instrucoes/' . $row->id) . '" title="Editar instruções" ><i class="glyphicon glyphicon-pencil"></i> Instruções</a>
                          <a class="btn btn-sm btn-primary" href="' . site_url('assessments_questoes/gerenciar/' . $row->id) . '" title="Editar questões" ><i class="glyphicon glyphicon-list-alt"></i> Questões</a>';
            } else {
                $acoes = '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			              <a class="btn btn-sm btn-primary" href="' . site_url('assessments_modelos/instrucoes/' . $row->id) . '" title="Editar instruções" ><i class="glyphicon glyphicon-pencil"></i> Instruções</a>
                          <button class="btn btn-sm btn-primary" disabled title="Editar questões" ><i class="glyphicon glyphicon-list-alt"></i> Questões</button>';
            }
            $data[] = array(
                $row->nome,
                $row->nome_tipo,
                $acoes
            );
        }

        echo json_encode(array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        ));
    }

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('assessments_modelos', array('id' => $id))->row();
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $perguntas = $this->input->post('perguntas');
        $alternativas = $this->input->post('alternativas');
        $aleatorizacao = $perguntas && $alternativas ? 'T' : $perguntas . $alternativas;
        $data = array(
            'id_empresa' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $this->input->post('tipo'),
            'observacoes' => $this->input->post('observacoes'),
            'aleatorizacao' => strlen($aleatorizacao) > 0 ? $aleatorizacao : null
        );
        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }
        $status = $this->db->insert('assessments_modelos', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $perguntas = $this->input->post('perguntas');
        $alternativas = $this->input->post('alternativas');
        $aleatorizacao = $perguntas && $alternativas ? 'T' : $perguntas . $alternativas;
        $data = array(
            'id_empresa' => $this->input->post('empresa'),
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

        $update = $this->db->update_string('assessments_modelos', $data, array('id' => $this->input->post('id')));
        $this->db->query($update);

        /*if (empty($perguntas)) {
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
            $update2 = $this->db->update_string('assessments_modelos', array('aleatorizacao' => null), $where);
        }
        $this->db->query($update2);*/

        $this->db->trans_complete();

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('assessments_modelos', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function instrucoes($id)
    {
        $this->db->where('id', $id);
        $modelo = $this->db->get('assessments_modelos')->row();
        $data = array(
            'modelo' => $modelo->id,
            'nome' => $modelo->nome,
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => '',
            'instrucoes' => $modelo->instrucoes
        );

        $this->load->view('assessments_instrucoes', $data);
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

        if (!$this->db->update('assessments_modelos', $data, array('id' => $this->input->post('id')))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar instruções, tente novamente')));
        }
        echo json_encode(array('retorno' => 1, 'aviso' => 'Instruções editadas com sucesso', 'redireciona' => 1, 'pagina' => site_url('recrutamento_modelos')));
    }

}
