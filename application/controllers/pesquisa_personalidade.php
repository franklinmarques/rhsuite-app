<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_personalidade extends MY_Controller
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
            default:
                $data['tipo'] = 'de clima';
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('pesquisa_personalidade', $data);
    }

    public function ajax_list($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.pergunta, 
                       s.prefixo_resposta
                FROM (SELECT a.id,
                             a.pergunta,
                             a.prefixo_resposta
                      FROM pesquisa_perguntas a
                      WHERE a.id_modelo = {$id} 
                      ORDER BY a.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();
        $columns = array(
            's.id',
            's.pergunta',
            's.prefixo_resposta'
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
        foreach ($list as $pesquisa) {
            $row = array();
            $row[] = $pesquisa->pergunta;
            //$row[] = $pesquisa->prefixo_resposta;
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
        $data = $this->db->get('pesquisa_perguntas')->row();

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
                LIMIT 0, 6";

        $data = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'U',
            'tipo_eneagrama' => $this->input->post('tipo_eneagrama'),
            'valor_min' => 1,
            'valor_max' => 5
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
        }
        if (empty($data['tipo_eneagrama'])) {
            $data['tipo_eneagrama'] = null;
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

    public function ajax_update()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_eneagrama' => $this->input->post('tipo_eneagrama')
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
        }
        if (empty($data['tipo_eneagrama'])) {
            $data['tipo_eneagrama'] = null;
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

}
