<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AtivosManutencao extends MY_Controller
{

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $this->db->select('a.id AS idItem, a.nome AS nomeAtivo');
        $this->db->select('b.sala, c.andar, d.nome AS unidade');
        $this->db->join('facilities_salas b', 'b.id = a.id_sala');
        $this->db->join('facilities_andares c', 'c.id = b.id_andar');
        $this->db->join('facilities_unidades d', 'd.id = c.id_unidade');
        $this->db->where('a.id', $this->uri->rsegment(3));
        $this->db->where('a.ativo', 1);
        $data = $this->db->get('facilities_itens a')->row();

        $this->load->view('facilities/ativos_manutencao', $data);
    }

    public function ajaxList()
    {
        $idItem = $this->uri->rsegment(3);

        $this->db->select('a.id, a.nome');
        $this->db->join('facilities_itens b', 'b.id = a.id_item');
        $this->db->where('b.ativo', 1);
        if ($idItem) {
            $this->db->where('a.id_item', $idItem);
        }
        $recordsTotal = $this->db->get('facilities_manutencoes a')->num_rows();

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        $post = $this->input->post();
        if ($post['search']['value']) {
            $sql .= " WHERE s.nome LIKE '%{$post['search']['value']}%'";
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if ($post['order']) {
            $orderBy = [];
            foreach ($post['order'] as $order) {
                $orderBy[] = intval($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();

        $data = array();

        foreach ($rows as $row) {
            $data[] = array(
                $row->nome,
                '<button class="btn btn-sm btn-info" onclick="edit_manutencao(' . $row->id . ')" title="Editar item de manutenção periódica de ativos"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_manutencao(' . $row->id . ')" title="Excluir item de manutenção periódica de ativos"><i class="glyphicon glyphicon-trash"></i></button>'
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

        $this->db->join('facilities_itens b', 'b.id = a.id_item');
        $this->db->where('b.ativo', 1);
        $this->db->where('a.id', $id);
        $data = $this->db->get('facilities_manutencoes a')->row();

        echo json_encode($data);
    }

    public function ajaxAdd()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        unset($data['id']);

        $status = $this->db->insert('facilities_manutencoes', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxUpdate()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        $id = $data['id'];
        unset($data['id']);

        $status = $this->db->update('facilities_manutencoes', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_manutencoes', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
