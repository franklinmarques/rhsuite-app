<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Produtos extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('icom_produtos_model', 'produtos');
	}

	//==========================================================================
	public function index()
	{
		$empresa = $this->session->userdata('empresa');

		$deptos = $this->db
			->select('id, nome')
			->where('id_empresa', $empresa)
			->order_by('nome', 'asc')
			->get('empresa_departamentos')
			->result();

		$data = [
			'empresa' => $empresa,
			'tipos' => ['' => 'selecione...'] + $this->produtos::tipo(),
			'tiposCobranca' => ['' => 'selecione...'] + $this->produtos::tipoCobranca(),
			'deptos' => ['' => 'Todos'] + array_column($deptos, 'nome', 'id'),
			'areas' => ['' => 'Todas'],
			'setores' => ['' => 'Todos'],
			'depto_atual' => '',
			'area_atual' => '',
			'setor_atual' => ''
		];

		$this->load->view('icom/produtos', $data);
	}

	//==========================================================================
	public function filtrarEstrutura()
	{
		$depto = $this->input->post('id_depto');
		$area = $this->input->post('id_area');
		$setor = $this->input->post('id_setor');

		$data = $this->carregarEstrutura($depto, $area, $setor, true);

		echo json_encode($data);
	}

	//==========================================================================
	public function montarEstrutura()
	{
		$depto = $this->input->post('id_depto');
		$area = $this->input->post('id_area');
		$setor = $this->input->post('id_setor');

		$data = $this->carregarEstrutura($depto, $area, $setor);

		echo json_encode($data);
	}

	//==========================================================================
	private function carregarEstrutura($depto = 0, $area = 0, $setor = 0, $todos = false)
	{
		$rowDeptos = $this->db
			->select('id, nome')
			->where('id_empresa', $this->session->userdata('empresa'))
			->order_by('nome', 'asc')
			->get('empresa_departamentos')
			->result();

		$deptos = ['' => ($todos ? 'Todos' : 'selecione...')] + array_column($rowDeptos, 'nome', 'id');

		$rowAreas = $this->db
			->select('id, nome')
			->where('id_departamento', $depto)
			->order_by('nome', 'asc')
			->get('empresa_areas')
			->result();

		$areas = ['' => ($todos ? 'Todas' : 'selecione...')] + array_column($rowAreas, 'nome', 'id');

		$rowSetores = $this->db
			->select('a.id, a.nome')
			->join('empresa_areas b', 'b.id = a.id_area')
			->where('a.id_area', $area)
			->where('b.id_departamento', $depto)
			->order_by('a.nome', 'asc')
			->get('empresa_setores a')
			->result();

		$setores = ['' => ($todos ? 'Todos' : 'selecione...')] + array_column($rowSetores, 'nome', 'id');

		$data = [
			'deptos' => form_dropdown('id_depto', $deptos, $depto, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
			'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
			'setores' => form_dropdown('id_setor', $setores, $setor, 'class="form-control input-sm"')
		];

		return $data;
	}

	//==========================================================================
	public function listar()
	{
		parse_str($this->input->post('busca'), $busca);

		$this->db
			->select('a.*', false)
			->select('d.nome AS depto, c.nome AS area, b.nome AS setor')
			->join('empresa_setores b', 'b.id = a.id_setor')
			->join('empresa_areas c', 'c.id = b.id_area')
			->join('empresa_departamentos d', 'd.id = c.id_departamento');
		if ($busca['id_depto']) {
			$this->db->where('d.id', $busca['id_depto']);
		}
		if ($busca['id_area']) {
			$this->db->where('c.id', $busca['id_area']);
		}
		if ($busca['id_setor']) {
			$this->db->where('b.id', $busca['id_setor']);
		}
		$query = $this->db
			->where('a.id_empresa', $this->session->userdata('empresa'))
			->get($this->produtos::table() . ' a');

		$config = [
			'select' => ['nome', 'depto', 'area', 'setor', 'preco', 'tipo_cobranca', 'tipo', 'id'],
			'search' => ['codigo', 'nome']
		];

		$this->load->library('dataTables', $config);

		$output = $this->datatables->generate($query);

		$data = [];

		foreach ($output->data as $row) {
			$data[] = array(
				$row->nome,
				$row->depto,
				$row->area,
				$row->setor,
				str_replace('.', ',', $row->preco),
				$this->produtos::tipoCobranca($row->tipo_cobranca),
				$this->produtos::tipo($row->tipo),
				'<button class="btn btn-sm btn-info" onclick="edit_produto(' . $row->id . ')" title="Editar produto"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_produto(' . $row->id . ')" title="Excluir produto"><i class="glyphicon glyphicon-trash"></i></button>'
			);
		}

		$output->data = $data;

		echo json_encode($output);
	}

	//==========================================================================
	public function editar()
	{
		$data = $this->produtos->find($this->input->post('id'));

		if (empty($data)) {
			exit(json_encode(['erro' => $this->produtos->errors()]));
		}

		$data = $this->produtos->formatData($data);

		$idEstrutura = $this->db
			->select('a.id, b.id_area, c.id_departamento', false)
			->join('empresa_setores b', 'b.id = a.id_setor')
			->join('empresa_areas c', 'c.id = b.id_area')
			->where('a.id', $data->id)
			->get('icom_produtos a')
			->row();

		$estrutura = $this->carregarEstrutura($idEstrutura->id_departamento, $idEstrutura->id_area, $data->id_setor);

		$data->deptos = $estrutura['deptos'];
		$data->areas = $estrutura['areas'];
		$data->setores = $estrutura['setores'];

		echo json_encode($data);
	}

	//==========================================================================
	public function salvar()
	{
		$this->load->library('entities');

		$data = $this->entities->create('icomProdutos', $this->input->post());

		$this->produtos->setValidationRule('id_empresa', '');
		$this->produtos->setValidationRule('id_depto', 'required|is_natural_no_zero|max_length[11]');
		$this->produtos->setValidationRule('id_area', 'required|is_natural_no_zero|max_length[11]');

		$this->produtos->setValidationLabel('codigo', 'Código Produto');
		$this->produtos->setValidationLabel('nome', 'Nome Produto');
		$this->produtos->setValidationLabel('tipo', 'Tipo Produto');
		$this->produtos->setValidationLabel('preco', 'Preço de Venda');
		$this->produtos->setValidationLabel('tipo_cobranca', 'Tipo de Cobrança');
		$this->produtos->setValidationLabel('id_depto', 'Departamento');
		$this->produtos->setValidationLabel('id_area', 'Área');
		$this->produtos->setValidationLabel('id_setor', 'Setor');
		$this->produtos->setValidationLabel('centro_custo', 'Centro de Custo');
		$this->produtos->setValidationLabel('complementos', 'Complementos do produto');

		$this->produtos->validate($data) or exit(json_encode(['erro' => $this->produtos->errors()]));

		unset($data->id_depto, $data->id_area);

		$this->produtos->skipValidation();

		$this->produtos->save($data) or exit(json_encode(['erro' => $this->produtos->errors()]));

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function excluir()
	{
		$this->produtos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->produtos->errors()]));

		echo json_encode(['status' => true]);
	}

}
