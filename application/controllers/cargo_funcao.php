<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargo_funcao extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('empresa_cargos_model', 'cargos');
        $this->load->model('empresa_funcoes_model', 'funcoes');
    }

    //==========================================================================
    public function index()
    {

        $this->load->view('cargo_funcao');
    }

    //==========================================================================
    public function listar()
    {
        $query = $this->db
            ->select('a.nome AS nome_cargo, a.familia_CBO, a.id AS id_cargo')
            ->select('b.nome AS nome_funcao, b.ocupacao_CBO, b.id AS id_funcao')
            ->join('empresa_funcoes b', 'b.id_cargo = a.id', 'left')
            ->where('a.id_empresa', $this->session->userdata('empresa'))
            ->get('empresa_cargos a');

        $config = [
            'search' => ['nome_cargo', 'nome_funcao']
        ];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            if ($row->id_funcao) {
                $btnFuncao = '<button class="btn btn-sm btn-info" onclick="edit_funcao(' . $row->id_funcao . ',\'' . $row->nome_cargo . '\')" title="Editar função"><i class="glyphicon glyphicon-pencil"></i></button>
                              <button class="btn btn-sm btn-danger" onclick="delete_funcao(' . $row->id_funcao . ')" title="Excluir função"><i class="glyphicon glyphicon-trash"></i></button>';
            } else {
                $btnFuncao = '<button class="btn btn-sm btn-info disabled" title="Editar função"><i class="glyphicon glyphicon-pencil"></i></button>
                              <button class="btn btn-sm btn-danger disabled" title="Excluir função"><i class="glyphicon glyphicon-trash"></i></button>';
            }

            $data[] = [
                $row->nome_cargo,
                $row->familia_CBO,
                '<button class="btn btn-sm btn-info" onclick="edit_cargo(' . $row->id_cargo . ')" title="Editar cargo"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_cargo(' . $row->id_cargo . ')" title="Excluir cargo"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info" onclick="add_funcao(' . $row->id_cargo . ',\'' . $row->nome_cargo . '\')" title="Adicionar Função"><i class="glyphicon glyphicon-plus"></i> Função</button>',
                $row->nome_funcao,
                $row->ocupacao_CBO,
                $btnFuncao
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editarCargo()
    {
        $data = $this->cargos->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->cargos->errors()]));
        };

        echo json_encode($data);
    }

    //==========================================================================
    public function editarFuncao()
    {
        $data = $this->funcoes->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->funcoes->errors()]));
        };

        echo json_encode($data);
    }

    //==========================================================================
    public function salvarCargo()
    {
        $this->load->library('entities');

        $data = $this->entities->create('empresaCargos', $this->input->post());

        $this->cargos->setValidationLabel('nome', 'Cargo');
        $this->cargos->setValidationLabel('familia_CBO', 'Família CBO');

        $this->cargos->save($data) or exit(json_encode(['erro' => $this->cargos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function salvarFuncao()
    {
        $this->load->library('entities');

        $data = $this->entities->create('empresaFuncoes', $this->input->post());

        $this->funcoes->setValidationRule('nome_cargo', 'required|max_length[255]');

        $this->funcoes->setValidationLabel('nome_cargo', 'Cargo');
        $this->funcoes->setValidationLabel('nome', 'Função');
        $this->funcoes->setValidationLabel('ocupacao_CBO', 'CBO - Ocupação');

        $this->funcoes->validate($data) or exit(json_encode(['erro' => $this->funcoes->errors()]));

        unset($data->nome_cargo);

        $this->funcoes->skipValidation();

        $this->funcoes->save($data) or exit(json_encode(['erro' => $this->funcoes->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluirCargo()
    {
        $this->cargos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->cargos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluirFuncao()
    {
        $this->funcoes->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->funcoes->errors()]));

        echo json_encode(['status' => true]);
    }

}
