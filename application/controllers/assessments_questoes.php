<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Assessments_questoes extends MY_Controller
{

    protected $tipo_usuario = array('empresa', 'selecionador');

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
        $assessment = $this->db->get_where('assessments_modelos', array('id' => $id))->row();
        $data['id_modelo'] = $assessment->id;
        $data['tipo'] = $assessment->tipo;
        $data['modelo'] = $assessment->nome;
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('assessments_questoes', $data);
    }


    public function ajax_list($id)
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
                      FROM assessments_perguntas a
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
        foreach ($list as $assessment) {
            $row = array();
            $row[] = $assessment->pergunta;

            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_pergunta(' . "'" . $assessment->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pergunta(' . "'" . $assessment->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
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

    public function ajax_listResposta($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }

        $post = $this->input->post();

        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM assessments_alternativas a
                INNER JOIN assessments_modelos b ON
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
        $data = $this->db->get('assessments_perguntas')->row();
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM assessments_alternativas a
                INNER JOIN assessments_perguntas b ON
                           b.id = a.id_pergunta
                WHERE a.id_pergunta = {$data->id}";
        $data->alternativas = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_editResposta($id)
    {
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM assessments_alternativas a
                INNER JOIN assessments_modelos b ON
                           b.id = a.id_modelo
                WHERE b.id = {$id}
                LIMIT 0, 6";

        $data = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $this->db->where('id', $this->input->post('id_modelo'));
        $modelo = $this->db->get('assessments_modelos')->row();
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

        $this->db->query($this->db->insert_string('assessments_perguntas', $data));

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
        $modelo = $this->db->get('assessments_modelos')->row();
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

        $update_string = $this->db->update_string('assessments_perguntas', $data, array('id' => $this->input->post('id')));
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

                    $update_string = $this->db->update_string('assessments_alternativas', $data, $where);
                    $this->db->query($update_string);
                } else {

                    $insert_string = $this->db->insert_string('assessments_alternativas', $data);
                    $this->db->query($insert_string);
                }
            } elseif ($id_alternativas[$k]) {

                $this->db->query("DELETE FROM assessments_alternativas WHERE id = $id_alternativas[$k]");
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

    public function ajax_delete($id)
    {
        $this->db->trans_begin();
        $this->db->query("DELETE FROM assessments_perguntas WHERE id = ?", $id);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

}
