<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacao extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Avaliacao_model', 'avaliacao');
    }

    public function index()
    {
        $data['id_usuario'] = $this->session->userdata('id');

        $sql = "SELECT id, CONCAT_WS('/', cargo, funcao) AS cargo_funcao 
                FROM cargos 
                WHERE id_usuario_EMPRESA = {$this->session->userdata('empresa')}";
        $cargos = $this->db->query($sql)->result();

        $data['id_cargo'] = array('' => 'selecione...');
        foreach ($cargos as $cargo) {
            $data['id_cargo'][$cargo->id] = $cargo->cargo_funcao;
        }

        $this->load->view('competencias/avaliacao', $data);
    }

    public function ajax_list($id)
    {
        $post = $this->input->post();
        $sql = "SELECT a.id, 
                       a.nome,
                       a.data_inicio, 
                       a.data_termino
                FROM competencias a 
                WHERE a.id_usuario_EMPRESA = {$id}";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('a.id', 'a.nome', 'a.data_inicio', 'a.data_termino');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " AND 
                        ({$column} LIKE '%{$post['search']['value']}%'";
                }
            }
            $sql .= ')';
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        } else {
            $sql .= ' 
                    ORDER BY 2';
        }
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $avaliacao) {
            $row = array();
            $row[] = $avaliacao->nome;
            $row[] = date("d/m/Y", strtotime(str_replace('-', '/', $avaliacao->data_inicio)));
            $row[] = date("d/m/Y", strtotime(str_replace('-', '/', $avaliacao->data_termino)));

            //add html for action
            $row[] = '
			<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_avaliacao(' . "'" . $avaliacao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_avaliacao(' . "'" . $avaliacao->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			<a class="btn btn-sm btn-primary" href="' . site_url('competencias/avaliados/index/' . $avaliacao->id) . '" title="Gerenciar Avaliacao" ><i class="glyphicon glyphicon-plus"></i> Avaliador X Avaliados</a>
			<a class="btn btn-sm btn-primary" href="' . site_url('competencias/relatorios/index/' . $avaliacao->id) . '" title="Relatórios"><i class="glyphicon glyphicon-list-alt"></i> Relatórios</a>
			<a class="btn btn-sm btn-primary" href="' . site_url('competencias/relatorios/analise_comparativa/' . $avaliacao->id) . '" title="Análise Comparativa"><i class="glyphicon glyphicon-list-alt"> </i> Análise Comparativa</a>
			<a class="btn btn-sm btn-primary" href="' . site_url('competencias/relatorios/andamento/' . $avaliacao->id) . '" title="Status"><i class="glyphicon glyphicon-info-sign"></i> Andamento</a>
			';

            $data[] = $row;
        }

        $output = array(
            'draw' => $post['draw'],
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('competencias', array('id' => $id))->row();
        if ($data->data_inicio) {
            $data->data_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        }
        if ($data->data_termino) {
            $data->data_termino = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));
        }

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date("Y-m-d 23:59:59", strtotime(str_replace('/', '-', $data['data_termino'])));
        }
        $this->db->insert('competencias', $data);

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date("Y-m-d 23:59:59", strtotime(str_replace('/', '-', $data['data_termino'])));
        }
        $this->db->update('competencias', $data, array('id' => $data['id']));

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $this->db->delete('competencias', array('id' => $id));

        echo json_encode(array("status" => TRUE));
    }

}
