<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estruturas extends MY_Controller
{

    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $this->load->view('facilities/estruturas', $data);
    }

    public function ajaxList()
    {
        $this->db->select('a.id, a.nome AS empresa');
        $this->db->select('b.id AS id_unidade, b.nome AS unidade');
        $this->db->select('c.id AS id_andar, c.andar');
        $this->db->select('d.id AS id_sala, d.sala');
        $this->db->join('facilities_unidades b', 'b.id_empresa = a.id', 'left');
        $this->db->join('facilities_andares c', 'c.id_unidade = b.id', 'left');
        $this->db->join('facilities_salas d', 'd.id_andar = c.id', 'left');
        $recordsTotal = $this->db->get('facilities_empresas a')->num_rows();

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        $post = $this->input->post();
        if ($post['search']['value']) {
            $sql .= " WHERE s.empresa LIKE '%{$post['search']['value']}%' OR 
                            s.unidade LIKE '%{$post['search']['value']}%' OR 
                            s.andar LIKE '%{$post['search']['value']}%' OR 
                            s.sala LIKE '%{$post['search']['value']}%'";
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if ($post['order']) {
            $orderBy = [];
            foreach ($post['order'] as $order) {
                $orderBy[] = intval($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();

        $data = array();

        foreach ($rows as $row) {
            if ($row->id_unidade) {
                $btnUnidade = '<button class="btn btn-sm btn-info" onclick="edit_unidade(' . $row->id_unidade . ')" title="Editar empresa"><i class="glyphicon glyphicon-pencil"></i></button>
                               <button class="btn btn-sm btn-danger" onclick="delete_unidade(' . $row->id_unidade . ')" title="Excluir empresa"><i class="glyphicon glyphicon-trash"></i></button>
                               <button class="btn btn-sm btn-info" onclick="add_andar(' . $row->id_unidade . ')" title="Adicionar unidade"><i class="glyphicon glyphicon-plus"></i> Andar</button>';
            } else {
                $btnUnidade = '<button class="btn btn-sm btn-info disabled" title="Editar empresa"><i class="glyphicon glyphicon-pencil"></i></button>
                               <button class="btn btn-sm btn-danger disabled" title="Excluir empresa"><i class="glyphicon glyphicon-trash"></i></button>
                               <button class="btn btn-sm btn-info disabled" title="Adicionar unidade"><i class="glyphicon glyphicon-plus"></i> Andar</button>';
            }
            if ($row->id_andar) {
                $btnAndar = '<button class="btn btn-sm btn-info" onclick="edit_andar(' . $row->id_andar . ')" title="Editar empresa"><i class="glyphicon glyphicon-pencil"></i></button>
                             <button class="btn btn-sm btn-danger" onclick="delete_andar(' . $row->id_andar . ')" title="Excluir empresa"><i class="glyphicon glyphicon-trash"></i></button>
                             <button class="btn btn-sm btn-info" onclick="add_sala(' . $row->id_andar . ')" title="Adicionar unidade"><i class="glyphicon glyphicon-plus"></i> Sala</button>';
            } else {
                $btnAndar = '<button class="btn btn-sm btn-info disabled" title="Editar empresa"><i class="glyphicon glyphicon-pencil"></i></button>
                             <button class="btn btn-sm btn-danger disabled" title="Excluir empresa"><i class="glyphicon glyphicon-trash"></i></button>
                             <button class="btn btn-sm btn-info disabled" title="Adicionar unidade"><i class="glyphicon glyphicon-plus"></i> Sala</button>';
            }
            if ($row->id_sala) {
                $btnSala = '<button class="btn btn-sm btn-info" onclick="edit_sala(' . $row->id_sala . ')" title="Editar empresa"><i class="glyphicon glyphicon-pencil"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="delete_sala(' . $row->id_sala . ')" title="Excluir empresa"><i class="glyphicon glyphicon-trash"></i></button>
                            <a class="btn btn-sm btn-primary" href="' . site_url('facilities/itens/gerenciar/' . $row->id_sala) . '" target="_blank" title="Facilities">Facilities</a>
                            <a class="btn btn-sm btn-primary" href="' . site_url('facilities/ativos/gerenciar/' . $row->id_sala) . '" target="_blank title="Ativos">Ativos</a>';
            } else {
                $btnSala = '<button class="btn btn-sm btn-info disabled" title="Editar empresa"><i class="glyphicon glyphicon-pencil"></i></button>
                            <button class="btn btn-sm btn-danger disabled" title="Excluir empresa"><i class="glyphicon glyphicon-trash"></i></button>
                            <button class="btn btn-sm btn-primary disabled" title="Facilities">Facilities</button>
                            <button class="btn btn-sm btn-primary disabled" title="Ativos">Ativos</button>';
            }
            $data[] = array(
                $row->empresa,
                '<button class="btn btn-sm btn-info" onclick="edit_empresa(' . $row->id . ')" title="Editar empresa"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_empresa(' . $row->id . ')" title="Excluir empresa"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info" onclick="add_unidade(' . $row->id . ')" title="Adicionar unidade"><i class="glyphicon glyphicon-plus"></i> Unidade</button>',
                $row->unidade,
                $btnUnidade,
                $row->andar,
                $btnAndar,
                $row->sala,
                $btnSala
            );
        }

        $output = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        );

        echo json_encode($output);
    }

    public function ajaxEdit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('facilities_empresas', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajaxEditUnidade()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('facilities_unidades', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajaxEditAndar()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('facilities_andares', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajaxEditSala()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('facilities_salas', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajaxAdd()
    {
        $data = $this->input->post();
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }
        unset($data['id']);
        $status = $this->db->insert('facilities_empresas', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxAddUnidade()
    {
        $data = $this->input->post();
        $status = $this->db->insert('facilities_unidades', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxAddAndar()
    {
        $data = $this->input->post();
        $status = $this->db->insert('facilities_andares', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxAddSala()
    {
        $data = $this->input->post();
        $status = $this->db->insert('facilities_salas', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxUpdate()
    {
        $data = $this->input->post();
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }
        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('facilities_empresas', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxUpdateUnidade()
    {
        $data = $this->input->post();
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }
        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('facilities_unidades', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxUpdateAndar()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('facilities_andares', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxUpdateSala()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);
        $status = $this->db->update('facilities_salas', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_empresas', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDeleteUnidade()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_unidades', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDeleteAndar()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_andares', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDeleteSala()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_salas', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
