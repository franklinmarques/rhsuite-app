<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contratos extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('empresaDepartamentos_model', 'depto');
        $this->load->model('empresaAreas_model', 'area');
        $this->load->model('empresaSetores_model', 'setor');
    }

    //==========================================================================
    public function index()
    {
        $data = [
            'empresa' => $this->session->userdata('empresa'),
            'depto' => $this->depto->findColumn('nome', 'id', 'Todos'),
            'area' => ['' => 'Todas'],
            'setor' => ['' => 'Todos']
        ];

        $this->load->view('apontamento_contratos', $data);
    }

    //==========================================================================
    public function ajaxList()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select(["a.nome, CONCAT_WS('/', a.depto, a.area) AS estrutura, a.contrato, a.id"], false)
            ->join('usuarios b', 'b.id = a.id_empresa')
            ->join('alocacao_unidades c', 'c.id_contrato = a.id', 'left')
            ->where('a.id_empresa', $this->session->userdata('empresa'));
        if (!empty($busca['depto'])) {
            $this->db->where('a.depto', $busca['depto']);
        }
        if (!empty($busca['area'])) {
            $this->db->where('a.area', $busca['area']);
        }
        if (!empty($busca['setor'])) {
            $this->db->where('c.setor', $busca['setor']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('a.contrato', $busca['contrato']);
        }
        $query = $this->db->get('alocacao_contratos a');


        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->estrutura,
                $row->contrato,
                '<button type="button" class="btn btn-sm btn-info" onclick="edit_contrato(' . $row->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                 <button type="button" class="btn btn-sm btn-info" onclick="edit_unidades(' . $row->id . ')" title="Gerenciar unidades"><i class="glyphicon glyphicon-plus"></i> Unidades</button>
                 <button type="button" class="btn btn-sm btn-info" onclick="edit_servicos(' . $row->id . ')" title="Gerenciar serviços"><i class="glyphicon glyphicon-plus"></i> Serviços</button>
                 <button type="button" class="btn btn-sm btn-info" onclick="edit_reajuste(' . $row->id . ')" title="Gerenciar reajuste"><i class="glyphicon glyphicon-plus"></i> Reajustes</button>
                 <button type="button" class="btn btn-sm btn-danger" onclick="delete_contrato(' . $row->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $data = $this->db
            ->select('id, id_usuario, nome, depto, area, contrato')
            ->select(["DATE_FORMAT(data_assinatura, '%d/%m/%Y') AS data_assinatura"], false)
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->where('id', $this->input->post('id'))
            ->get('alocacao_contratos')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Contrato não encontrado ou excluído recentemente.']));
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
