<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DetalhesEventos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('st_detalhes_eventos_model', 'detalhesEventos');
    }

    //==========================================================================
    public function index()
    {
        $this->load->view('st/detalhes_eventos', ['empresa' => $this->session->userdata('empresa')]);
    }

    //==========================================================================
    public function listar()
    {
        $query = $this->db
            ->select('codigo, nome, id')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->get('st_detalhes_eventos');

        $this->load->library('dataTables', ['search' => ['codigo', 'nome']]);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->codigo,
                $row->nome,
                '<button class="btn btn-sm btn-info" onclick="edit_detalhe_evento(' . $row->id . ')" title="Editar detalhe de evento"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_detalhe_evento(' . $row->id . ')" title="Excluir detalhe de evento"><i class="glyphicon glyphicon-trash"></i></button>'
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->detalhesEventos->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->detalhesEventos->errors()]));
        };

        echo json_encode($data);;
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('stDetalhesEventos', $this->input->post());

        $this->detalhesEventos->setValidationLabel('codigo', 'Código');
        $this->detalhesEventos->setValidationLabel('nome', 'Nome');

        $this->detalhesEventos->save($data) or exit(json_encode(['erro' => $this->detalhesEventos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->detalhesEventos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->detalhesEventos->errors()]));

        echo json_encode(['status' => true]);
    }

}
