<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zarit extends MY_Controller
{

    public function index()
    {
        $rows = $this->db->get('papd_pacientes')->result();
        $pacientes = array_column($rows, 'nome', 'id');

        $data = array(
            'idPaciente' => null,
            'nomePaciente' => null,
            'pacientes' => ['' => 'selecione'] + $pacientes
        );
        $this->load->view('papd/zarit', $data);
    }

    // -------------------------------------------------------------------------

    public function gerenciar()
    {
        $this->db->where('id', $this->uri->rsegment(3));
        $paciente = $this->db->get('papd_pacientes')->row();
        if (empty($paciente)) {
            redirect(site_url('papd/pacientes'));
        }

        $data = array(
            'idPaciente' => $paciente->id,
            'nomePaciente' => ' - ' . $paciente->nome,
            'pacientes' => null
        );
        $this->load->view('papd/zarit', $data);
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $idPaciente = $this->input->post('id_paciente');

        $this->db->select('a.data_avaliacao, a.avaliador, a.zarit');
        $this->db->select("(CASE WHEN zarit > 21 THEN 3 WHEN zarit > 14 THEN 2 WHEN zarit >= 0 THEN 1 ELSE 0 END) AS sobrecarga", false);
        $this->db->select('a.observacoes, a.id');
        $this->db->select(["DATE_FORMAT(a.data_avaliacao, '%d/%m/%Y') AS data_avaliacao_de"], false);
        $this->db->join('papd_pacientes b', 'b.id = a.id_paciente');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        if ($idPaciente) {
            $this->db->where('a.id_paciente', $idPaciente);
        }
        $query = $this->db->get('papd_zarit a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();

        $sobrecarga = array(null, 'Leve', 'Moderada', 'Grave');

        foreach ($output->data as $row) {
            $data[] = array(
                $row->data_avaliacao_de,
                $row->avaliador,
                $row->zarit,
                $sobrecarga[$row->sobrecarga],
                $row->observacoes,
                '<button class="btn btn-sm btn-info" onclick="edit_zarit(' . $row->id . ');" title="Editar ZARIT"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_zarit(' . $row->id . ');" title="Excluir ZARIT"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info disabled" onclick="gerenciar_avaliacao(' . $row->id . ');" title="Gerenciar avaliação ZARIT"><i class="glyphicon glyphicon-print"></i> Imprimir</button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function ajaxEdit()
    {
        $data = $this->getData($this->input->post('id'));

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    public function ajaxAdd()
    {
        $data = $this->setData();
        $status = $this->db->insert('papd_zarit', $data);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxUpdate()
    {
        $id = $this->input->post('id');

        $data = $this->setData();
        $this->db->set($data);
        $this->db->where('id', $id);
        $status = $this->db->update('papd_zarit');

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('papd_zarit', ['numero_os' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    /* -------------------------------------------------------------------------
     *
     * -------------------------------------------------------------------------
     */

    protected function getData($id = '')
    {
        $this->db->where('id', $id);
        $zarit = $this->db->get_where('papd_zarit')->row();

        if (empty($zarit)) {
            $keys = $this->db->list_fields('papd_zarit');
            $values = array_pad([], count($keys), null);

            return array_combine($keys, $values);
        }

        if ($zarit->data_avaliacao) {
            $zarit->data_avaliacao = date('d/m/Y', strtotime($zarit->data_avaliacao));
        }

        return $zarit;
    }

    // -------------------------------------------------------------------------

    protected function setData()
    {
        $data = $this->input->post();

        if (strlen($data['data_avaliacao']) > 0) {

            $dataAvaliacao = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_avaliacao'])));
            if ($data['data_avaliacao'] != preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $dataAvaliacao)) {
                exit(json_encode(['erro' => 'A data de avaliação é inválida.']));
            }

            $data['data_avaliacao'] = $dataAvaliacao;
        } else {
            exit(json_encode(['erro' => 'A data de avaliação é obrigatória.']));
        }

        if (strlen($data['observacoes']) == 0) {
            $data['observacoes'] = null;
        }

        unset($data['id']);

        return $data;
    }

}
