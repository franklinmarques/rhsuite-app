<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estruturas extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->model('empresa_departamentos_model', 'departamentos');
		$this->load->model('empresa_areas_model', 'areas');
		$this->load->model('empresa_setores_model', 'setores');
	}


	//==========================================================================


	public function index()
	{
		$this->departamentos();
	}


	//==========================================================================


	public function departamentos()
	{
		$data = $this->input->get();
		$data['empresa'] = $this->session->userdata('empresa');
		$data['indice'] = 0;
		$this->load->view('estruturas', $data);
	}


	//==========================================================================


	public function areas()
	{
		$data = $this->input->get();
		$data['empresa'] = $this->session->userdata('empresa');
		$data['indice'] = 1;
		$this->load->view('estruturas', $data);
	}


	//==========================================================================


	public function setores()
	{
		$data = $this->input->get();
		$data['empresa'] = $this->session->userdata('empresa');
		$data['indice'] = 2;
		$this->load->view('estruturas', $data);
	}


	//==========================================================================


	public function listarDepartamentos()
	{
		$query = $this->db
			->select('nome, id')
			->where('id_empresa', $this->session->userdata('empresa'))
			->get('empresa_departamentos');

		$this->load->library('dataTables');

		$output = $this->datatables->generate($query);

		$data = [];

		foreach ($output->data as $row) {
			$data[] = [
				$row->nome,
				'<button class="btn btn-sm btn-info" onclick="edit_depto(' . $row->id . ')" title="Editar departamento"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_depto(' . $row->id . ')" title="Excluir departamento"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" onclick="nextArea(' . $row->id . ',\'' . $row->nome . '\')" title="Áreas"><i class="glyphicon glyphicon-list"></i> Áreas</button>'
			];
		}

		$output->data = $data;

		echo json_encode($output);
	}


	//==========================================================================


	public function listarAreas()
	{
		$idDepartamento = $this->input->post('id_depto');

		$this->db
			->select('a.nome AS nome_departamento, b.nome AS nome_area, b.id AS id_area')
			->join('empresa_areas b', 'b.id_departamento = a.id', 'left')
			->where('a.id_empresa', $this->session->userdata('empresa'));
		if ($idDepartamento) {
			$this->db->where('a.id', $idDepartamento);
		}
		$query = $this->db->get('empresa_departamentos a');

		$this->load->library('dataTables');

		$output = $this->datatables->generate($query);

		$data = [];

		foreach ($output->data as $row) {
			if ($row->id_area) {
				$btn = '<button class="btn btn-sm btn-info" onclick="edit_area(' . $row->id_area . ',\'' . $row->nome_departamento . '\')" title="Editar área"><i class="glyphicon glyphicon-pencil"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="delete_area(' . $row->id_area . ')" title="Excluir área"><i class="glyphicon glyphicon-trash"></i></button>
                        <button class="btn btn-sm btn-primary" onclick="nextSetor(' . $row->id_area . ',\'' . $row->nome_departamento . '\',\'' . $row->nome_area . '\')" title="Setores"><i class="glyphicon glyphicon-list"></i> Setores</button>';
			} else {
				$btn = '<button class="btn btn-sm btn-info disabled" title="Editar área"><i class="glyphicon glyphicon-pencil"></i></button>
                        <button class="btn btn-sm btn-danger disabled" title="Excluir área"><i class="glyphicon glyphicon-trash"></i></button>
                        <button class="btn btn-sm btn-primary disabled" title="Setores"><i class="glyphicon glyphicon-list"></i> Setores</button>';
			}
			$data[] = [
				$row->nome_departamento,
				$row->nome_area,
				$btn
			];
		}

		$output->data = $data;

		echo json_encode($output);
	}


	//==========================================================================


	public function listarSetores()
	{
		$idDepartamento = $this->input->post('id_depto');
		$idArea = $this->input->post('id_area');

		$this->db
			->select('a.nome AS nome_departamento, b.nome AS nome_area, c.nome AS nome_setor, c.id AS id_setor')
			->join('empresa_areas b', 'b.id_departamento = a.id')
			->join('empresa_setores c', 'c.id_area = b.id', 'left')
			->where('a.id_empresa', $this->session->userdata('empresa'));
		if ($idDepartamento) {
			$this->db->where('a.id', $idDepartamento);
		}
		if ($idArea) {
			$this->db->where('b.id', $idArea);
		}
		$query = $this->db->get('empresa_departamentos a');

		$this->load->library('dataTables');

		$output = $this->datatables->generate($query);

		$data = [];

		foreach ($output->data as $row) {
			if ($row->id_setor) {
				$btn = '<button class="btn btn-sm btn-info" onclick="edit_setor(' . $row->id_setor . ',\'' . $row->nome_departamento . '\',\'' . $row->nome_area . '\')" title="Editar setor"><i class="glyphicon glyphicon-pencil"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="delete_setor(' . $row->id_setor . ')" title="Excluir setor"><i class="glyphicon glyphicon-trash"></i></button>';
			} else {
				$btn = '<button class="btn btn-sm btn-info disabled" title="Editar setor"><i class="glyphicon glyphicon-pencil"></i></button>
                        <button class="btn btn-sm btn-danger disabled" title="Excluir setor"><i class="glyphicon glyphicon-trash"></i></button>';
			}
			$data[] = [
				$row->nome_departamento,
				$row->nome_area,
				$row->nome_setor,
				$btn
			];
		}

		$output->data = $data;

		echo json_encode($output);
	}


	//==========================================================================


	public function editarDepartamento()
	{
		$data = $this->departamentos->find($this->input->post('id'));

		if (empty($data)) {
			exit(json_encode(['erro' => $this->departamentos->errors()]));
		}

		echo json_encode($data);
	}


	//==========================================================================


	public function editarArea()
	{
		$data = $this->areas->find($this->input->post('id'));

		if (empty($data)) {
			exit(json_encode(['erro' => $this->areas->errors()]));
		}

		echo json_encode($data);
	}


	//==========================================================================


	public function editarSetor()
	{
		$data = $this->setores->find($this->input->post('id'));

		if (empty($data)) {
			exit(json_encode(['erro' => $this->setores->errors()]));
		}

		echo json_encode($data);
	}


	//==========================================================================


	public function salvarDepartamento()
	{
		$this->load->library('entities');

		$data = $this->entities->create('empresaDepartamentos', $this->input->post());

		$this->departamentos->setValidationLabel('nome', 'Depto.');

		$this->departamentos->save($data) or
		exit(json_encode(['erro' => $this->departamentos->errors()]));

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function salvarArea()
	{
		$this->load->library('entities');

		$data = $this->entities->create('empresaAreas', $this->input->post());

		$this->areas->setValidationRule('nome_depto', 'required|max_length[255]');

		$this->areas->setValidationLabel('nome_depto', 'Depto.');
		$this->areas->setValidationLabel('nome', 'Área');

		$this->areas->validate($data) or
		exit(json_encode(['erro' => $this->areas->errors()]));

		unset($data->nome_depto);

		$this->areas->skipValidation();

		$this->areas->save($data) or
		exit(json_encode(['erro' => $this->areas->errors()]));

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function salvarSetor()
	{
		$this->load->library('entities');

		$data = $this->entities->create('empresaSetores', $this->input->post());

		$this->setores->setValidationRule('nome_depto', 'required|max_length[255]');
		$this->setores->setValidationRule('nome_area', 'required|max_length[255]');

		$this->setores->setValidationLabel('nome_depto', 'Depto.');
		$this->setores->setValidationLabel('nome_area', 'Área');
		$this->setores->setValidationLabel('nome', 'Setor');
		$this->setores->setValidationLabel('cnpj', 'CNPJ');

		$this->setores->validate($data) or
		exit(json_encode(['erro' => $this->setores->errors()]));

		unset($data->nome_depto, $data->nome_area);

		$this->setores->skipValidation();

		$this->setores->save($data) or
		exit(json_encode(['erro' => $this->setores->errors()]));

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function excluirDepartamento()
	{
		$this->departamentos->delete($this->input->post('id')) or
		exit(json_encode(['erro' => $this->departamentos->errors()]));

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function excluirArea()
	{
		$this->areas->delete($this->input->post('id')) or
		exit(json_encode(['erro' => $this->areas->errors()]));

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function excluirSetor()
	{
		$this->setores->delete($this->input->post('id')) or
		exit(json_encode(['erro' => $this->setores->errors()]));

		echo json_encode(['status' => true]);
	}


}
