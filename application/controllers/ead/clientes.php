<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ead_clientes_model', 'cliente');
	}


	public function index()
	{
		$data['empresa'] = $this->session->userdata('empresa');
		$this->load->view('ead/clientes', $data);
	}

	//==========================================================================
	public function editarPerfil()
	{
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$this->db->where('id', $this->session->userdata('id'));
		$data = $this->db->get('cursos_clientes')->row();
		unset($data->senha);

		echo json_encode($data);
	}

	//==========================================================================
	public function ajaxList()
	{
		$clienteSelecionado = $this->input->post('cliente');


		$this->db->select('nome, cliente, id');
		$this->db->select("(CASE STATUS WHEN 1 THEN 'Ativo' WHEN 0 THEN 'Inativo' END) AS status", false);
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		if ($clienteSelecionado) {
			$this->db->where('cliente', $clienteSelecionado);
		}
		$query = $this->db->get('cursos_clientes');


		$config = array(
			'search' => ['nome', 'cliente'],
			'order' => ['nome', 'cliente', 'status']
		);

		$this->load->library('dataTables', $config);
		$output = $this->datatables->generate($query);

		$data = array();

		foreach ($output->data as $row) {
			$data[] = array(
				$row->nome,
				$row->cliente,
				$row->status,
				'<button class="btn btn-sm btn-info" onclick="edit_cliente(' . $row->id . ');" title="Editar cliente"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_cliente(' . $row->id . ');" title="Excluir cliente"><i class="glyphicon glyphicon-trash"></i></button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('ead/clientes_treinamentos/gerenciar/' . $row->id) . '" title="Gerenciar treinamentos do cliente ">Treinamentos</a>',
			);
		}

		$output->data = $data;


		$this->db->distinct('cliente');
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$this->db->order_by('cliente', 'asc');
		$rowsClientes = $this->db->get('cursos_clientes')->result();
		$clientes = ['' => 'Todos'] + array_column($rowsClientes, 'cliente', 'cliente');

		$output->clientes = form_dropdown('busca_cliente', $clientes, $clienteSelecionado, 'class="form-control input-sm" aria-controls="table" onchange="reload_table();"');


		echo json_encode($output);
	}

	//==========================================================================
	public function ajaxEdit()
	{
		$data = $this->clientes
			->where('id_empresa', $this->session->userdata('empresa'))
			->find($this->input->post('id'));

		if (empty($data)) {
			exit(json_encode(['erro' => $this->clientes->errors()]));
		};

		unset($data->senha);

		echo json_encode($data);
	}

	//==========================================================================
	public function ajaxAdd()
	{
		$this->salvar();
	}

	//==========================================================================
	public function ajaxUpdate()
	{
		$this->salvar();
	}

	//==========================================================================
	public function salvarPerfil()
	{
		$this->load->library('entities');

		$data = $this->entities->create('eadClientes', $this->input->post());

		if (is_null($data->senha) and is_null($data->confirmar_senha)) {
			unset($data->senha);
		} else {
			$this->clientes->setValidationRule('confirmar_senha', 'required|matches[senha]');
		}

		$this->clientes->setValidationLabel('cliente', 'Cliente');
		$this->clientes->setValidationLabel('nome', 'Usuário');
		$this->clientes->setValidationLabel('email', 'E-mail');
		$this->clientes->setValidationLabel('senha', 'Senha');
		$this->clientes->setValidationLabel('confirmar_senha', 'Confirmar Senha');
		$this->clientes->setValidationLabel('foto', 'Foto');

		$this->clientes->validate($data) or exit(json_encode(['erro' => $this->clientes->errors()]));

		unset($data->confirmar_senha);

		$this->clientes->skipValidation();

		$this->clientes->save($data) or exit(json_encode(['erro' => $this->clientes->errors()]));

		echo json_encode([
			'status' => 1,
			'aviso' => 'Meu perfil foi editado com sucesso!',
			'pagina' => site_url('ead/treinamento_cliente')
		]);
	}

	//==========================================================================
	public function salvar()
	{
		$this->load->library('entities');

		$data = $this->entities->create('eadClientes', $this->input->post());

		if (is_null($data->senha) and is_null($data->confirmar_senha)) {
			unset($data->senha);
		} else {
			$this->clientes->setValidationRule('confirmar_senha', 'required|matches[senha]');
		}

		$this->clientes->setValidationLabel('status', 'Status');
		$this->clientes->setValidationLabel('cliente', 'Cliente');
		$this->clientes->setValidationLabel('nome', 'Usuário');
		$this->clientes->setValidationLabel('email', 'E-mail');
		$this->clientes->setValidationLabel('senha', 'Senha');
		$this->clientes->setValidationLabel('confirmar_senha', 'Confirmar Senha');
		$this->clientes->setValidationLabel('foto', 'Foto');

		$this->clientes->validate($data) or exit(json_encode(['erro' => $this->clientes->errors()]));

		unset($data->confirmar_senha);

		$this->clientes->skipValidation();

		$this->clientes->save($data) or exit(json_encode(['erro' => $this->clientes->errors()]));

		$this->session->set_userdata('nome', $data->nome);
		$this->session->set_userdata('email', $data->email);
		if (isset($data->foto)) {
			$this->session->set_userdata('foto', $data->foto);
		}

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function ajaxDelete()
	{
		$this->clientes->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->clientes->errors()]));

		echo json_encode(['status' => true]);
	}

}
