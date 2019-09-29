<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Insumos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('cd_insumos_model', 'insumos');
    }

    //==========================================================================
    public function index()
    {
        $this->load->view('cd/insumos', ['empresa' => $this->session->userdata('empresa')]);
    }

    //==========================================================================
    public function listar()
    {
        $query = $this->db
            ->select('nome, tipo, id')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->get('cd_insumos');

        $this->load->library('dataTables', ['search' => ['nome', 'tipo']]);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->nome,
                $row->tipo,
                '<button class="btn btn-sm btn-info" onclick="edit_insumo(' . $row->id . ')" title="Editar insumo"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="edit_insumo(' . $row->id . ')" title="Excluir insumo"><i class="glyphicon glyphicon-trash"></i></button>'
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->insumos->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->insumos->errors()]));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('cdInsumos', $this->input->post());

        $this->insumos->setValidationLabel('nome', 'Nome');
        $this->insumos->setValidationLabel('tipo', 'Tipo');

        $this->insumos->save($data) or exit(json_encode(['erro' => $this->insumos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->insumos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->insumos->errors()]));

        echo json_encode(['status' => true]);
    }

}
