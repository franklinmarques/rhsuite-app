<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GestaoDeVagas extends MY_Controller
{

	//==========================================================================

	public function index()
	{
		$data['cargos'] = $this->getCargos();
		$data['funcoes'] = $this->getFuncoes();

		$this->db->order_by('estado', 'asc');
		$estados = $this->db->get('estados')->result();
		$data['estados'] = ['' => 'selecione...'] + array_column($estados, 'estado', 'uf');

		$escolaridade = $this->db->get('escolaridade')->result();
		$data['escolaridades'] = ['' => 'selecione...'] + array_column($escolaridade, 'nome', 'id');

		$requisicoesPessoal = $this->db->select('id')->get('requisicoes_pessoal')->result();
		$data['requisicoesPessoal'] = ['' => 'selecione...'] + array_column($requisicoesPessoal, 'id', 'id');

		$this->load->view('gestao_de_vagas', $data);
	}

	//==========================================================================

	public function getCargos()
	{
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$cargos = $this->db->get('empresa_cargos')->result();

		return ['' => 'selecione...'] + array_column($cargos, 'nome', 'id');
	}

	//==========================================================================

	public function getFuncoes($idCargo = '')
	{
		$this->db->select('a.*', false);
		$this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
		$this->db->where('b.id', $idCargo);
		$this->db->where('b.id_empresa', $this->session->userdata('empresa'));
		$funcoes = $this->db->get('empresa_funcoes a')->result();

		return ['' => 'selecione...'] + array_column($funcoes, 'nome', 'id');
	}

	//==========================================================================

	public function ajaxList()
	{
		$this->db->select('a.codigo, a.id_requisicao_pessoal');
		$this->db->select("(CASE WHEN a.status = 1 THEN 'Aberta' WHEN a.status = 0 THEN 'Fechada' END) AS status", false);
		$this->db->select(["a.data_abertura, IFNULL(CONCAT(b.nome, '/', c.nome), a.cargo_funcao_alternativo) AS cargo_funcao"], false);
		$this->db->select('a.quantidade, a.cidade_vaga, a.bairro_vaga, b.nome AS cargo, c.nome AS funcao');
		$this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura_de"], false);
		$this->db->join('empresa_cargos b', 'b.id = a.id_cargo', 'left');
		$this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id', 'left');
		$this->db->where('a.id_empresa', $this->session->userdata('empresa'));
		$query = $this->db->get('gestao_vagas a');

		$config = array(
			'search' => ['codigo', 'cidade_vaga', 'bairro_vaga', 'cargo', 'funcao']
		);

		$this->load->library('dataTables', $config);

		$output = $this->datatables->generate($query);

		$data = array();
		foreach ($output->data as $row) {
			$data[] = array(
				$row->codigo,
				$row->id_requisicao_pessoal,
				$row->status,
				$row->data_abertura_de,
				$row->cargo_funcao,
				$row->quantidade,
				$row->cidade_vaga,
				$row->bairro_vaga,
				'<button class="btn btn-sm btn-info" title="Editar" onclick="edit_vaga(' . $row->codigo . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_vaga(' . $row->codigo . ')"><i class="glyphicon glyphicon-trash"></i></button>'
			);
		}

		$output->data = $data;

		echo json_encode($output);
	}

	//==========================================================================

	public function ajaxNova()
	{
		$this->db->select(['IFNULL(MAX(codigo) + 1, 1) AS codigo'], false);
		$data = $this->db->get('gestao_vagas')->row();

		$data->cargos = form_dropdown('', $this->getCargos(), '');
		$data->funcoes = form_dropdown('', $this->getFuncoes(), '');

		echo json_encode($data);
	}

	//==========================================================================

	public function ajaxEdit()
	{
		$codigo = $this->input->post('codigo');

		$row = $this->db->get_where('gestao_vagas', ['codigo' => $codigo])->row();
		if (empty($row)) {
			exit(json_encode(['erro' => 'Vaga não encontrada.']));
		}

		if ($row->remuneracao) {
			$row->remuneracao = str_replace('.', ',', $row->remuneracao);
		}

		$data['data'] = $row;
		$data['input']['cargos'] = form_dropdown('', $this->getCargos(), $row->id_cargo);
		$data['input']['funcoes'] = form_dropdown('', $this->getFuncoes($row->id_cargo), $row->id_funcao);

		echo json_encode($data);
	}

	//==========================================================================

	public function atualizarFuncoes()
	{
		$idCargo = $this->input->post('id_cargo');

		$data['funcoes'] = form_dropdown('', $this->getFuncoes($idCargo), '');

		echo json_encode($data);
	}

	//==========================================================================

	public function ajaxAdd()
	{
		$data = $this->input->post();

		$data['id_empresa'] = $this->session->userdata('empresa');
		$data['data_abertura'] = date('Y-m-d');
		$data['remuneracao'] = str_replace(['.', ','], ['', '.'], $data['remuneracao']);

		$status = $this->db->insert('gestao_vagas', $data);

		echo json_encode(['status' => $status !== false]);
	}

	//==========================================================================

	public function ajaxUpdate()
	{
		$data = $this->input->post();
		$data['remuneracao'] = str_replace(['.', ','], ['', '.'], $data['remuneracao']);

		$codigo = $data['codigo'];

		unset($data['codigo']);

		$status = $this->db->update('gestao_vagas', $data, ['codigo' => $codigo]);

		echo json_encode(['status' => $status !== false]);
	}

	//==========================================================================

	public function ajaxDelete()
	{
		$codigo = $this->input->post('codigo');

		$status = $this->db->delete('gestao_vagas', ['codigo' => $codigo]);

		echo json_encode(['status' => $status !== false]);
	}


}
