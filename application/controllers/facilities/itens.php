<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Itens extends MY_Controller
{

    public function index()
    {
        $data['idSala'] = null;
        $data['empresa'] = $this->session->userdata('empresa');

        $this->db->select('sala AS nome');
        $this->db->where('id', $data['idSala']);
        $sala = $this->db->get('facilities_salas')->row();
        $data['nomeSala'] = $sala->nome ?? '';

        $this->db->select('DISTINCT(tipo)', false);
        $this->db->where('ativo', 0);
        $this->db->where('tipo IS NOT NULL');
        $rows = $this->db->get('facilities_itens')->result();
        $data['tipos'] = ['' => 'digite ou selecione...'] + array_column($rows, 'tipo', 'tipo');

        $this->db->select('a.id, a.nome');
        $this->db->join('facilities_empresas b', 'b.id = a.id_facility_empresa');
        $this->db->where('b.id_empresa', $data['empresa']);
        $this->db->where('a.ativo', '0');
        $this->db->order_by('a.nome', 'asc');
        $tipos = $this->db->get('facilities_empresas_itens a')->result();
        $data['tipoItem'] = ['' => 'selecione...'] + array_column($tipos, 'nome', 'nome');

        $this->load->view('facilities/itens', $data);
    }

    public function gerenciar()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $data['idSala'] = $this->uri->rsegment(3);

        $this->db->select('sala AS nome');
        $this->db->where('id', $data['idSala']);
        $sala = $this->db->get('facilities_salas')->row();
        $data['nomeSala'] = $sala->nome ?? '';
        $this->db->select('DISTINCT(tipo)', false);
        $this->db->where('ativo', 0);
        $this->db->where('tipo IS NOT NULL');
        $rows = $this->db->get('facilities_itens')->result();
        $data['tipos'] = ['' => 'digite ou selecione...'] + array_column($rows, 'tipo', 'tipo');

        $this->db->select('a.id, a.nome');
        $this->db->join('facilities_empresas b', 'b.id = a.id_facility_empresa');
        $this->db->where('b.id_empresa', $data['empresa']);
        $this->db->where('a.ativo', '0');
        $this->db->order_by('a.nome', 'asc');
        $tipos = $this->db->get('facilities_empresas_itens a')->result();
        $data['tipoItem'] = ['' => 'selecione...'] + array_column($tipos, 'nome', 'nome');

        $this->load->view('facilities/itens', $data);
    }

    public function ajaxList()
    {
        $idSala = $this->uri->rsegment(3);

        $this->db->select('e.nome AS empresa, d.nome AS unidade');
        $this->db->select('c.andar, b.sala, a.nome, a.id');
        $this->db->join('facilities_salas b', 'b.id = a.id_sala');
        $this->db->join('facilities_andares c', 'c.id = b.id_andar');
        $this->db->join('facilities_unidades d', 'd.id = c.id_unidade');
        $this->db->join('facilities_empresas e', 'e.id = d.id_empresa');
        $this->db->where('a.ativo', 0);
        if ($idSala) {
            $this->db->where('a.id_sala', $idSala);
        }
        $recordsTotal = $this->db->get('facilities_itens a')->num_rows();

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        $post = $this->input->post();
        if ($post['search']['value']) {
            $sql .= " WHERE s.empresa LIKE '%{$post['search']['value']}%' OR 
                            s.unidade LIKE '%{$post['search']['value']}%' OR 
                            s.andar LIKE '%{$post['search']['value']}%' OR 
                            s.sala LIKE '%{$post['search']['value']}%' OR 
                            s.nome LIKE '%{$post['search']['value']}%'";
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
                $row->empresa,
                $row->unidade,
                $row->andar,
                $row->sala,
                $row->nome,
                '<button class="btn btn-sm btn-info" onclick="edit_item(' . $row->id . ')" title="Editar item"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_item(' . $row->id . ')" title="Excluir item"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" onclick="vistorias(' . $row->id . ')" title="Gerenciar vistorias do item">Vistorias</button>
                 <button class="btn btn-sm btn-primary" onclick="manutencao(' . $row->id . ')" title="Gerenciar manutenção periódica do item">Manutenção</button>'
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
        $data = $this->db->get_where('facilities_itens', array('id' => $id))->row();
        if ($data->data_entrada_operacao) {
            $data->data_entrada_operacao = date('d/m/Y', strtotime($data->data_entrada_operacao));
        }

        echo json_encode($data);
    }

    public function ajaxAdd()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        if ($data['data_entrada_operacao']) {
            $dataEntregaOperacao = explode('/', $data['data_entrada_operacao']);
            $dia = $dataEntregaOperacao[0] ?? 0;
            $mes = $dataEntregaOperacao[1] ?? 0;
            $ano = $dataEntregaOperacao[2] ?? 0;
            if (checkdate($mes, $dia, $ano) == false) {
                exit(json_encode(['erro' => 'A data de entrada de operação é inválida']));
            }
            $data['data_entrada_operacao'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_entrada_operacao'])));
        }

        unset($data['id']);
        foreach ($data as $k => $row) {
            if (strlen($row) == 0) {
                $data[$k] = null;
            }
        }
        $status = $this->db->insert('facilities_itens', $data);

        $id = $this->db->insert_id();
        $this->addVistoriasManutencoes($id, $data['tipo']);


        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxUpdate()
    {
        $data = $this->input->post();
        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome do item é obrigatório']));
        }
        if ($data['data_entrada_operacao']) {
            $dataEntregaOperacao = explode('/', $data['data_entrada_operacao']);
            $dia = $dataEntregaOperacao[0] ?? 0;
            $mes = $dataEntregaOperacao[1] ?? 0;
            $ano = $dataEntregaOperacao[2] ?? 0;
            if (checkdate($mes, $dia, $ano) == false) {
                exit(json_encode(['erro' => 'A data de entrada de operação é inválida']));
            }
            $data['data_entrada_operacao'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_entrada_operacao'])));
        }
        if (!empty($data['mes_vistoria_jan']) == false) {
            $data['mes_vistoria_jan'] = null;
        }
        if (!empty($data['mes_vistoria_fev']) == false) {
            $data['mes_vistoria_fev'] = null;
        }
        if (!empty($data['mes_vistoria_mar']) == false) {
            $data['mes_vistoria_mar'] = null;
        }
        if (!empty($data['mes_vistoria_abr']) == false) {
            $data['mes_vistoria_abr'] = null;
        }
        if (!empty($data['mes_vistoria_mai']) == false) {
            $data['mes_vistoria_mai'] = null;
        }
        if (!empty($data['mes_vistoria_jun']) == false) {
            $data['mes_vistoria_jun'] = null;
        }
        if (!empty($data['mes_vistoria_jul']) == false) {
            $data['mes_vistoria_jul'] = null;
        }
        if (!empty($data['mes_vistoria_ago']) == false) {
            $data['mes_vistoria_ago'] = null;
        }
        if (!empty($data['mes_vistoria_set']) == false) {
            $data['mes_vistoria_set'] = null;
        }
        if (!empty($data['mes_vistoria_out']) == false) {
            $data['mes_vistoria_out'] = null;
        }
        if (!empty($data['mes_vistoria_nov']) == false) {
            $data['mes_vistoria_nov'] = null;
        }
        if (!empty($data['mes_vistoria_dez']) == false) {
            $data['mes_vistoria_dez'] = null;
        }

        if (!empty($data['mes_manutencao_jan']) == false) {
            $data['mes_manutencao_jan'] = null;
        }
        if (!empty($data['mes_manutencao_fev']) == false) {
            $data['mes_manutencao_fev'] = null;
        }
        if (!empty($data['mes_manutencao_mar']) == false) {
            $data['mes_manutencao_mar'] = null;
        }
        if (!empty($data['mes_manutencao_abr']) == false) {
            $data['mes_manutencao_abr'] = null;
        }
        if (!empty($data['mes_manutencao_mai']) == false) {
            $data['mes_manutencao_mai'] = null;
        }
        if (!empty($data['mes_manutencao_jun']) == false) {
            $data['mes_manutencao_jun'] = null;
        }
        if (!empty($data['mes_manutencao_jul']) == false) {
            $data['mes_manutencao_jul'] = null;
        }
        if (!empty($data['mes_manutencao_ago']) == false) {
            $data['mes_manutencao_ago'] = null;
        }
        if (!empty($data['mes_manutencao_set']) == false) {
            $data['mes_manutencao_set'] = null;
        }
        if (!empty($data['mes_manutencao_out']) == false) {
            $data['mes_manutencao_out'] = null;
        }
        if (!empty($data['mes_manutencao_nov']) == false) {
            $data['mes_manutencao_nov'] = null;
        }
        if (!empty($data['mes_manutencao_dez']) == false) {
            $data['mes_manutencao_dez'] = null;
        }

        $id = $data['id'];
        unset($data['id']);
        foreach ($data as $k => $row) {
            if (strlen($row) == 0) {
                $data[$k] = null;
            }
        }
        $status = $this->db->update('facilities_itens', $data, array('id' => $id));

        $this->addVistoriasManutencoes($id, $data['tipo']);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_itens', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    private function addVistoriasManutencoes($id, $tipo)
    {
        $this->db->select("'{$id}' AS id_item, a.nome", false);
        $this->db->join('facilities_empresas_itens b', 'b.id = a.id_item');
        $this->db->where('b.nome', $tipo);
        $this->db->where('a.tipo', 'V');
        $this->db->where("a.nome NOT IN (SELECT x.nome FROM facilities_vistorias x WHERE x.id_item = '{$id}')", null, false);
        $this->db->order_by('a.nome', 'asc');
        $vistorias = $this->db->get('facilities_empresas_revisoes a')->result_array();

        if ($vistorias) {
            $this->db->insert_batch('facilities_vistorias', $vistorias);
        }

        $this->db->select("'{$id}' AS id_item, a.nome", false);
        $this->db->join('facilities_empresas_itens b', 'b.id = a.id_item');
        $this->db->where('b.nome', $tipo);
        $this->db->where('a.tipo', 'M');
        $this->db->where("a.nome NOT IN (SELECT x.nome FROM facilities_manutencoes x WHERE x.id_item = '{$id}')", null, false);
        $this->db->order_by('a.nome', 'asc');
        $manutencao = $this->db->get('facilities_empresas_revisoes a')->result_array();

        if ($manutencao) {
            $this->db->insert_batch('facilities_manutencoes', $manutencao);
        }
    }

}
