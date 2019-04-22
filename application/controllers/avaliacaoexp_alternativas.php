<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_alternativas extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Avaliacaoexp_model', 'avaliacaoexp');
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
        $avaliacao = $this->db->get_where('avaliacaoexp_modelos', array('id' => $id))->row();
        $data['id_modelo'] = $avaliacao->id;
        $data['modelo'] = $avaliacao->nome;
        $data['tipo'] = $avaliacao->tipo == 'C' ? 'de clima' : 'de perfil';
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('avaliacaoexp_alternativas', $data);
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
                      FROM avaliacaoexp_perguntas a
                      INNER JOIN avaliacaoexp_alternativas b ON
                                 b.id_pergunta = a.id AND 
                                 b.id_modelo = a.id_modelo
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
        foreach ($list as $avaliacao) {
            $row = array();
            $row[] = $avaliacao->pergunta;
            $row[] = $avaliacao->alternativa;
            $row[] = $avaliacao->peso;

            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_pergunta(' . "'" . $avaliacao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pergunta(' . "'" . $avaliacao->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
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

    public function ajax_edit($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get('avaliacaoexp_perguntas')->row();
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM avaliacaoexp_alternativas a
                INNER JOIN avaliacaoexp_perguntas b ON
                           b.id = a.id_pergunta AND
                           b.id_modelo = a.id_modelo
                WHERE a.id_pergunta = {$data->id}";
        $data->alternativas = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta')
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
        }

        $this->db->trans_begin();

        $this->db->query($this->db->insert_string('avaliacaoexp_perguntas', $data));

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

            $this->db->query($this->db->insert_string('avaliacaoexp_alternativas', $data));
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
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta')
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de avaliacao não foi encontrado')));
        }

        $this->db->trans_begin();

        $update_string = $this->db->update_string('avaliacaoexp_perguntas', $data, array('id' => $this->input->post('id')));
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
                    $update_string = $this->db->update_string('avaliacaoexp_alternativas', $data, array('id' => $id_alternativa[$k]));
                    $this->db->query($update_string);
                } else {
                    $insert_string = $this->db->insert_string('avaliacaoexp_alternativas', $data);
                    $this->db->query($insert_string);
                }
            } elseif ($id_alternativa[$k]) {
                $this->db->query("DELETE FROM avaliacaoexp_alternativas WHERE id = $id_alternativa[$k]");
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
        $sql = "DELETE a FROM avaliacaoexp_alternativas a
                INNER JOIN avaliacaoexp_perguntas b ON
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

}
