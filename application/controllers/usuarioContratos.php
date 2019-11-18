<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UsuarioContratos extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuarios_contratos_model', 'contratos');
	}

	// -------------------------------------------------------------------------

	public function ajaxList()
	{
		$post = $this->input->post();


		$this->db->select('a.id, a.contrato, a.data_assinatura');
		$this->db->select(["DATE_FORMAT(a.data_assinatura, '%d/%m/%Y') AS data_assinatura_de"], false);
		$this->db->join('usuarios b', 'b.id = a.id_usuario');
		if ($this->session->userdata('tipo') == 'empresa') {
			$this->db->where('b.empresa', $this->session->userdata('empresa'));
			if ($post['id_usuario']) {
				$this->db->where('a.id_usuario', $post['id_usuario']);
			}
		} else {
			$this->db->where('a.id_usuario', $this->session->userdata('id'));
		}
		$query = $this->db->get('usuarios_contratos a');

		$config = ['search' => ['contrato']];

		$this->load->library('dataTables', $config);

		$output = $this->datatables->generate($query);


		$data = array();

		foreach ($output->data as $row) {
			$data[] = array(
				$row->contrato,
				$row->data_assinatura_de,
				'<button class="btn btn-sm btn-info" onclick="edit_contrato(' . $row->id . ');" title="Editar contrato"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_contrato(' . $row->id . ');" title="Excluir contrato"><i class="glyphicon glyphicon-trash"></i></button>'
			);
		}

		$output->data = $data;


		echo json_encode($output);
	}

	// -------------------------------------------------------------------------

	public function ajaxEdit()
	{
		$data = $this->contratos->find($this->input->get_post('id'));

		if (empty($data)) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'Contrato não encontrado']));
		}

		if ($data->data_assinatura) {
			$data->data_assinatura = date('d/m/Y', strtotime($data->data_assinatura));
		}

		echo json_encode($data);
	}

	// -------------------------------------------------------------------------

	public function ajaxAdd()
	{
		$this->load->library('entities');

		$data = $this->entities->create('UsuariosContratos', $this->input->post());

		$this->contratos->insert($data) or exit(json_encode(['retorno' => 0, 'aviso' => $this->contratos->errors()]));

		echo json_encode(['status' => true]);
	}

	// -------------------------------------------------------------------------

	public function ajaxUpdate()
	{
		$this->load->library('entities');

		$data = $this->entities->create('UsuariosContratos', $this->input->post());

		$this->contratos->update($data->id, $data) or exit(json_encode(['retorno' => 0, 'aviso' => $this->contratos->errors()]));

		echo json_encode(['status' => true]);
	}


	// -------------------------------------------------------------------------

	public function ajaxDelete()
	{
		$this->contratos->delete($this->input->post('id')) or exit(json_encode(['retorno' => 0, 'aviso' => $this->contratos->errors()]));

		echo json_encode(['status' => true]);
	}

}
