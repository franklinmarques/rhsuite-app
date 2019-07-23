<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Postos extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $this->load->view('apontamento_postos', $data);
    }

    //==========================================================================
    public function ajaxList()
    {
        parse_str($this->input->post('busca'), $busca);


        $this->db
            ->select('b.nome, a.data, a.valor_posto, a.total_dias_mensais')
            ->select('a.total_horas_diarias, a.valor_dia, a.valor_hora, a.id')
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->where('b.empresa', $this->session->userdata('empresa'));
        if (!empty($busca['depto'])) {
            $this->db->where('a.depto', $busca['depto']);
        }
        if (!empty($busca['area'])) {
            $this->db->where('a.area', $busca['area']);
        }
        if (!empty($busca['setor'])) {
            $this->db->where('b.setor', $busca['setor']);
        }
        if (!empty($busca['cargo'])) {
            $this->db->where('b.cargo', $busca['cargo']);
        }
        if (!empty($busca['funcao'])) {
            $this->db->where('b.funcao', $busca['funcao']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('a.contrato', $busca['contrato']);
        }
        if (!empty($busca['busca_mes'])) {
            $this->db->where('MONTH(a.data)', $busca['busca_mes']);
        }
        if (!empty($busca['busca_ano'])) {
            $this->db->where('YEAR(a.data)', $busca['busca_ano']);
        }
        $query = $this->db->get('alocacao_postos a');


        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = array(
                $row->codigo,
                $row->nome,
                '<button class="btn btn-sm btn-info" onclick="edit_evento(' . $row->id . ');" title="Editar evento"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_evento(' . $row->id . ');" title="Excluir evento"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $data = $this->db
            ->where('id', $this->input->post('id'))
            ->get('alocacao_eventos')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Detalhe de evento não encontrado ou excluído recentemente.']));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $this->validarDados();

        $data = $this->input->post();
        unset($data['id']);

        $this->db->trans_start();
        $this->db->insert('alocacao_eventos', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível cadastrar o detalhe de evento.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $this->validarDados();

        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('alocacao_eventos', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível alterar o detalhe de evento.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    private function validarDados()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('codigo', '"Código evento"', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('nome', '"Nome evento"', 'trim|required|max_length[255]');

        if ($this->form_validatio->run() == false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $this->db->trans_start();
        $this->db->delete('alocacao_eventos', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível excluir o detalhe de evento.']));
        }

        echo json_encode(['status' => true]);
    }

}
