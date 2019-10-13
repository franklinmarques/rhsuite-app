<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_alternativas extends MY_Controller
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
        $pesquisa = $this->db->get_where('pesquisa_modelos', array('id' => $id))->row();
        if ($pesquisa->exclusao_bloqueada) {
            redirect(site_url('pesquisa_modelos'));
            exit;
        }
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
            case 'M':
                $data['tipo'] = 'de perfil';
                break;
            default:
                $data['tipo'] = 'de clima';
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        if ($pesquisa->tipo == 'M') {
            $this->load->view('pesquisa_personalidade', $data);
        } else {
            $this->load->view('pesquisa_alternativas', $data);
        }

//        $this->load->view('pesquisa_personalidade', $data);
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
                FROM (SELECT b.id,
                             b.pergunta,
                             c.alternativa,
                             c.peso
                      FROM pesquisa_modelos a
                      INNER JOIN pesquisa_perguntas b ON
                                 b.id_modelo = a.id
                      INNER JOIN pesquisa_alternativas c ON
                                 (c.id_pergunta = b.id OR 
                                  c.id_modelo = a.id)
                      WHERE a.id = {$id} 
                      ORDER BY b.id, 
                               c.id) s";
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
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_pergunta(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pergunta(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
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

    public function ajax_add()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'U'
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
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

    public function ajax_update()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'U'
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
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

    public function ajax_delete($id)
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

}
