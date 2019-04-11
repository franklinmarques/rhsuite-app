<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class InspecaoVistorias extends MY_Controller
{

    public function index()
    {
        $data['idEmpresa'] = $this->session->userdata('empresa');
        $data['facilityEmpresas'] = ['' => 'selecione...'] + $this->getFacilitiesEmpresas();

        $this->load->view('facilities/inspecao_vistorias', $data);
    }

    // -------------------------------------------------------------------------

    public function gerenciar()
    {
        $data['idVistoria'] = $this->uri->rsegment(3);
        $data['facilityUnidades'] = ['' => 'selecione...'] + $this->getFacilitiesEmpresas();
        $data['facilityAndares'] = ['' => 'selecione...'] + $this->getFacilitiesEmpresas();
        $data['facilitySalas'] = ['' => 'selecione...'] + $this->getFacilitiesEmpresas();
        $data['facilityEmpresas'] = ['' => 'selecione...'] + $this->getFacilitiesEmpresas();

        $this->load->view('facilities/inspecao_vistorias', $data);
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $idSala = $this->uri->rsegment(3);
        $post = $this->input->post();

        $this->db->select(["nome, versao, IF(status = 1, 'Ativo', 'Inativo') AS status, id"], false);
        if (!empty($post['status'])) {
            $this->db->where('status', $post['status']);
        }
        $recordsTotal = $this->db->get('facilities_vistorias a')->num_rows();

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        $post = $this->input->post();
        if ($post['search']['value']) {
            $sql .= " WHERE s.nome LIKE '%{$post['search']['value']}%' OR 
                            s.versao LIKE '%{$post['search']['value']}%'";
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
                $row->versao,
                $row->status,
                '<button class="btn btn-sm btn-info" onclick="edit_vistoria(' . $row->id . ')" title="Editar ativo"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_vistoria(' . $row->id . ')" title="Excluir ativo"><i class="glyphicon glyphicon-trash"></i></button>
                 <a class="btn btn-sm btn-primary" href="#" title="Gerenciar Itens"><i class="glyphicon glyphicon-list-alt"></i> Itens</a>
                 <a class="btn btn-sm btn-primary" href="#" title="Imprimir plano">Imprimir</a>
                 <a class="btn btn-sm btn-primary" href="#" title="Visualizar execuções">Visualizar execuções</a>
                 <button class="btn btn-sm btn-success" onclick="copiar_vistoria(' . $row->id . ')" title="Copiar plano"><i class="glyphicon glyphicon-plus"></i> Copiar</button>'
            );
        }

        $status = array(
            '' => 'Todos',
            '1' => 'Ativos',
            '0' => 'Inativos'
        );

        $output = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'status' => form_dropdown('', $status, $post['status'], 'id="status" class="form-control input-sm" onchange="reload_table();"'),
            'data' => $data
        );

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function copiar()
    {
        $data = $this->getData($this->input->post('id'));

        $data->versao = null;
        $data->id_copia = $data->id;
        unset($data->id);

        $status = $this->db->insert('facilities_vistorias', $data);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxEdit()
    {
        echo json_encode($this->getData($this->input->post('id')));
    }

    // -------------------------------------------------------------------------

    public function ajaxAdd()
    {
        $data = $this->setData();
        $status = $this->db->insert('facilities_vistorias', $data);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxUpdate()
    {
        $data = $this->setData();
        $this->db->set($data);
        $this->db->where('id', $this->input->post('id'));
        $status = $this->db->update('facilities_vistorias');

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_vistorias', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    /* -------------------------------------------------------------------------
     *
     * -------------------------------------------------------------------------
     */

    protected function getData($id = '')
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        return $this->db->get_where('facilities_vistorias')->row();
    }

    // -------------------------------------------------------------------------

    protected function getFacilitiesEmpresas()
    {
        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('facilities_empresas')->result();

        return array_column($rows, 'nome', 'id');
    }

    // -------------------------------------------------------------------------

    protected function setData()
    {
        $data = $this->input->post();

        if (strlen($data['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome é obrigatório']));
        }
        if (strlen($data['id_facility_empresa']) == 0) {
            exit(json_encode(['erro' => 'A empresa de facilities é obrigatório']));
        }

        if (strlen($data['versao']) == 0) {
            $data['versao'] = null;
        }
        unset($data['id']);

        return $data;
    }

}
