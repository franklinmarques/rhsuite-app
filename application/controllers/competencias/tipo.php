<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tipo extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('competencias_model', 'competencias');
//        $this->load->model('Dimensao_model', 'dimensao');
    }

    public function index($id, $tipo = null)
    {
        $this->db->select("id AS id_cargo, CONCAT_WS('/', cargo, funcao) AS cargo_funcao", false);
        $cargo = $this->db->get_where('cargos', array('id' => $id))->row_array();
        $variaveis['options_cargos'] = $cargo;
        $variaveis['id_cargo'] = $cargo['id_cargo'];
        $variaveis['tipo'] = $tipo;
        $variaveis['nome_tipo'] = $tipo === 'T' ? 'técnica' : ($tipo === 'C' ? 'comportamental' : '');
        $variaveis['ncf'] = $tipo === 'T' ? 'NCTf' : ($tipo === 'C' ? 'NCCf' : 'NCf');

        if ($tipo) {
            $this->db->where('tipo', $tipo);
        }
        $variaveis['competencias_sugestao'] = $this->db->get('competencias_modelos')->result();

        $this->load->view('competencias/tipo', $variaveis);
    }

    public function tecnica($id)
    {
        $this->index($id, 'T');
    }

    public function comportamental($id)
    {
        $this->index($id, 'C');
    }

    public function ajax_list($id, $tipo = null)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.tipo_competencia,
                       s.peso, 
                       s.rowdt,
                       s.rowdt2
                FROM (SELECT a.id, 
                             a.nome, 
                             a.tipo_competencia,
                             a.peso,                              
                             SUM(b.peso / 100 * b.atitude / 100 * b.nivel) AS rowdt,
                             SUM(b.peso / 100 * b.atitude / 100 * b.nivel) * a.peso / 100 AS rowdt2  
                      FROM cargos_competencias a 
                      LEFT JOIN cargos_dimensao b ON 
                                 b.cargo_competencia = a.id
                      WHERE a.id_cargo = {$id}";
        if ($tipo) {
            $sql .= " AND a.tipo_competencia = '$tipo'";
        }
        $sql .= ' GROUP BY a.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.tipo_competencia', 's.peso', 's.rowdt', 's.rowdt2');
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
        foreach ($list as $cargos) {
            $row = array();
            $row[] = $cargos->nome;
            if ($cargos->tipo_competencia === 'T') {
                $url = 'competencias/dimensao/tecnica/' . $cargos->id;
                $row[] = 'Técnica';
            } elseif ($cargos->tipo_competencia === 'C') {
                $url = 'competencias/dimensao/comportamental/' . $cargos->id;
                $row[] = 'Comportamental';
            } else {
                $url = 'competencias/dimensao/index/' . $cargos->id;
                $row[] = '';
            }
            $row[] = str_replace('.', ',', round($cargos->peso, 3));
            $row[] = str_replace('.', ',', round($cargos->rowdt, 3));
            $row[] = str_replace('.', ',', round($cargos->rowdt2, 3));

            //add html for action
            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_competencias(' . "'" . $cargos->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_competencias(' . "'" . $cargos->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-primary" href="' . site_url($url) . '" title="Comportamento/dimensão">Comportamentos/dimensão</a>
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
        echo json_encode($output, JSON_PRETTY_PRINT);
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('cargos_competencias', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        $this->db->select('id');
        $this->db->where('nome', $data['nome']);
        $row = $this->db->get_where('competencias_modelos')->row();
        if ($row) {
            $data['id_modelo'] = $row->id;
        } else {
            $data['id_modelo'] = null;
        }
        $this->db->insert('cargos_competencias', $data);

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $this->db->select('id');
        $this->db->where('nome', $data['nome']);
        $row = $this->db->get_where('competencias_modelos')->row();
        if ($row) {
            $data['id_modelo'] = $row->id;
        } else {
            $data['id_modelo'] = null;
        }
        $this->db->update('cargos_competencias', $data, array('id' => $data['id']));

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $this->db->delete('cargos_competencias', array('id' => $id));

        echo json_encode(array("status" => TRUE));
    }

}
