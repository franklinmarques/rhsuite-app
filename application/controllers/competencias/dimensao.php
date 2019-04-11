<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dimensao extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Dimensao_model', 'dimensao');
    }

    public function index($tipo = null)
    {
        $sql = "SELECT a.id as id_cargo, 
                       CONCAT_WS('/', a.cargo, a.funcao) as nome_cargo, 
                       b.id as cargo_competencia, 
                       b.nome as nome_competencia
		FROM cargos_competencias b
		INNER JOIN cargos a ON a.id = b.id_cargo 
		WHERE b.id = {$this->uri->rsegment(3, 0)}";
        $competencia = $this->db->query($sql)->row();

        $variaveis['options_cargos'] = array(
            'id_cargo' => $competencia->id_cargo,
            'nome_cargo' => $competencia->nome_cargo,
            'cargo_competencia' => $competencia->cargo_competencia,
            'nome_competencia' => $competencia->nome_competencia,
            'tipo' => $tipo
        );

        $this->db->select('a.id, a.nome');
        $this->db->join('competencias_modelos b', 'b.id = a.id_modelo');
        if ($tipo) {
            $this->db->where('b.tipo', $tipo);
        }
        $variaveis['sugestoes'] = $this->db->get('competencias_dimensao a')->result();

        $this->load->view('competencias/dimensao', $variaveis);
    }

    public function tecnica()
    {
        $this->index('T');
    }

    public function comportamental()
    {
        $this->index('C');
    }

    public function ajax_list($id)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.peso, 
                       s.nivel, 
                       s.atitude, 
                       s.indice  
                FROM (SELECT a.id, 
                             a.nome, 
                             a.peso, 
                             a.nivel, 
                             a.atitude, 
                             round((((CAST(a.peso AS DECIMAL) / 100) * a.nivel) * (a.atitude / 100)), 3) AS indice  
                      FROM cargos_dimensao a 
                      WHERE a.cargo_competencia = {$id}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.peso', 's.nivel', 's.atitude', 's.indice');
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
        foreach ($list as $dimensao) {
            $row = array();
            $row[] = $dimensao->nome;
            $row[] = str_replace('.', ',', round($dimensao->peso, 3));
            $row[] = intval($dimensao->nivel);
            $row[] = intval($dimensao->atitude);
            $row[] = str_replace('.', ',', round($dimensao->indice, 3));

            //add html for action
            $row[] = '
                     <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_dimensao(' . "'" . $dimensao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                     <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_dimensao(' . "'" . $dimensao->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                     ';

            $data[] = $row;
        }

        $output = array(
            'draw' => $this->input->post('draw'),
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
        $data = $this->db->get_where('cargos_dimensao', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        $this->db->select('id');
        $this->db->where('nome', $data['nome']);
        $row = $this->db->get_where('competencias_dimensao')->row();
        if ($row) {
            $data['id_dimensao'] = $row->id;
        } else {
            $data['id_dimensao'] = null;
        }
        $this->db->insert('cargos_dimensao', $data);

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $this->db->select('id');
        $this->db->where('nome', $data['nome']);
        $row = $this->db->get_where('competencias_dimensao')->row();
        if ($row) {
            $data['id_dimensao'] = $row->id;
        } else {
            $data['id_dimensao'] = null;
        }
        $this->db->update('cargos_dimensao', $data, array('id' => $data['id']));

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $this->db->delete('cargos_dimensao', array('id' => $id));

        echo json_encode(array("status" => TRUE));
    }

}
