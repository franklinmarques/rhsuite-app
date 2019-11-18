<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DetalhesEventos extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('st_detalhes_eventos_model', 'detalhes');
	}

	//==========================================================================
	public function index()
	{
		$data['empresa'] = $this->session->userdata('empresa');
		$this->load->view('st/detalhes_eventos', $data);
	}

	//==========================================================================
	public function listar()
	{
		$query = $this->db
			->where('id_empresa', $this->session->userdata('empresa'))
			->get($this->detalhes::table());

		$this->load->library('dataTables', ['search' => ['codigo', 'nome']]);

		$output = $this->datatables->generate($query);

		$data = [];

		foreach ($output->data as $row) {
			$data[] = array(
				$row->codigo,
				$row->nome,
				'<button class="btn btn-sm btn-info" onclick="edit_detalhe(' . $row->id . ')" title="Editar detalhe"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_detalhe(' . $row->id . ')" title="Excluir detalhe"><i class="glyphicon glyphicon-trash"></i></button>'
			);
		}

		$output->data = $data;

		echo json_encode($output);
	}

	//==========================================================================
	public function editar()
	{
		$data = $this->detalhes->find($this->input->post());

		if (empty($data)) {
			exit(json_encode($this->detalhes->errors()));
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function salvar()
	{
		$this->load->library('entities');

		$data = $this->entities->create('stDetalhesEventos', $this->input->post());

		$this->detalhes->setValidationLabel('codigo', 'Código Evento');
		$this->detalhes->setValidationLabel('nome', 'Nome Evento');

		$this->detalhes->save($data) or exit(json_encode(['erro' => $this->detalhes->errors()]));

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function excluir()
	{
		$this->detalhes->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->detalhes->errors()]));

		echo json_encode(['status' => true]);
	}

}
