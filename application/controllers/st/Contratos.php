<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contratos extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('st_contratos_model', 'contratos');
		$this->load->model('st_contratos_reajustes_model', 'reajustes');
		$this->load->model('st_contratos_servicos_model', 'servicos');
		$this->load->model('st_contratos_unidades_model', 'unidades');
	}

	//==========================================================================
	public function index()
	{
		$empresa = $this->session->userdata('empresa');
		$arrSql = array('depto', 'area', 'setor', 'contrato');

		$data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

		foreach ($arrSql as $field) {
			$sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
			$rows = $this->db->query($sql)->result_array();
			$data[$field] = array('' => 'Todos');
			foreach ($rows as $row) {
				$data[$field][$row[$field]] = $row[$field];
			}
		}
		$data['deptos'] = $data['depto'];
		$data['depto'][''] = 'selecione...';
		$data['area_cliente'] = $data['area'];
		$data['area'][''] = 'selecione...';
		$data['setor_unidade'] = $data['setor'];
		$data['setor'][''] = 'selecione...';
		$data['contratos'] = $data['contrato'];
		$data['contrato'][''] = 'selecione...';

		$this->db->select('id, nome');
		$this->db->where('empresa', $empresa);
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();
		$data['usuarios'] = array('' => 'selecione...');
		foreach ($usuarios as $usuario) {
			$data['usuarios'][$usuario->id] = $usuario->nome;
		}

		$this->load->view('st/contratos', $data);
	}

	//==========================================================================
	public function atualizarFiltros()
	{
		$busca = $this->input->post('busca');

		$filtro = $this->get_filtros_usuarios($busca['depto'], $busca['area'], $busca['setor']);
		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
			if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
				unset($filtro['area'][''], $filtro['setor']['']);
			}
			unset($filtro['depto']['']);
		}

		$data['area'] = form_dropdown('area', $filtro['area'], $busca['area'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['setor'] = form_dropdown('setor', $filtro['setor'], $busca['setor'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

		echo json_encode($data);
	}

	//==========================================================================
	public function listar()
	{
		parse_str($this->input->post('busca'), $arrBusca);

		$this->db
			->select(["a.id, a.nome, CONCAT_WS('/', a.depto, a.area) AS estrutura, a.contrato"], false)
			->join('usuarios b', 'b.id = a.id_empresa')
			->join('alocacao_unidades c', 'c.id_contrato = a.id')
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
		$query = $this->db
			->group_by('a.id')
			->get('alocacao_contratos a');

		$this->load->library('dataTables', ['search' => ['codigo', 'nome']]);

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
	public function editar()
	{
		$data = $this->contratos->find($this->input->post());

		if (empty($data)) {
			exit(json_encode($this->contratos->errors()));
		}

		if ($data->data_assinatura) {
			$data->data_assinatura = date('d/m/Y', strtotime($data->data_assinatura));
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function gerenciarReajustes()
	{
		$data = $this->contratos->find($this->input->post());

		if (empty($data)) {
			exit(json_encode($this->contratos->errors()));
		}

		if ($data->data_assinatura) {
			$data->data_assinatura = date('d/m/Y', strtotime($data->data_assinatura));
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function gerenciarServicos()
	{
		$data = $this->contratos->find($this->input->post());

		if (empty($data)) {
			exit(json_encode($this->contratos->errors()));
		}

		if ($data->data_assinatura) {
			$data->data_assinatura = date('d/m/Y', strtotime($data->data_assinatura));
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function gerenciarUnidades()
	{
		$data = $this->contratos->find($this->input->post());

		if (empty($data)) {
			exit(json_encode($this->contratos->errors()));
		}

		if ($data->data_assinatura) {
			$data->data_assinatura = date('d/m/Y', strtotime($data->data_assinatura));
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function atualizarEstrutura()
	{
		$depto = $this->input->post('depto');
		$area = $this->input->post('area');
		$setor = $this->input->post('setor');

		$this->db->select('DISTINCT(area) AS nome', false);
		$this->db->where('empresa', $this->session->userdata('empresa'));
		if ($depto) {
			$this->db->where('depto', $depto);
		}
		$this->db->where('CHAR_LENGTH(area) >', 0);
		$areas = $this->db->get('usuarios')->result();

		$options_area = array('' => 'selecione...');
		foreach ($areas as $row_area) {
			$options_area[$row_area->nome] = $row_area->nome;
		}

		$this->db->select('DISTINCT(setor) AS nome', false);
		$this->db->where('empresa', $this->session->userdata('empresa'));
		if ($depto) {
			$this->db->where('depto', $depto);
		}
		if ($area) {
			$this->db->where('area', $area);
		}
		$this->db->where('CHAR_LENGTH(setor) >', 0);
		$setores = $this->db->get('usuarios')->result();

		$options_setor = array('' => 'selecione...');
		foreach ($setores as $row_setor) {
			$options_setor[$row_setor->nome] = $row_setor->nome;
		}

		$data['area'] = form_dropdown('area', $options_area, $area, 'id="area" class="form-control"');
		$data['setor'] = form_dropdown('setor', $options_setor, $setor, 'id="setor" class="form-control"');

		echo json_encode($data);
	}


	//==========================================================================
	public function atualizarServicos()
	{

	}

	//==========================================================================
	public function salvar()
	{
		$this->load->library('entities');

		$data = $this->entities->create('stContratos', $this->input->post());

		$this->contratos->setValidationLabel('nome', 'Cliente');
		$this->contratos->setValidationLabel('depto', 'Departamento');
		$this->contratos->setValidationLabel('area', 'Área');
		$this->contratos->setValidationLabel('contrato', 'Contrato');
		$this->contratos->setValidationLabel('id_usuario', 'Gestor(a)');
		$this->contratos->setValidationLabel('data_assinatura', 'Data Assinatura');

		$this->contratos->save($data) or exit(json_encode(['erro' => $this->contratos->errors()]));

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function salvarReajustes()
	{
		$this->load->library('entities');

		$data = $this->entities->create('stContratosReajustes', $this->input->post());

		$this->reajustes->setValidationLabel('nome', 'Data 1&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Valor 1&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Data 2&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Valor 2&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Data 3&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Valor 3&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Data 4&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Valor 4&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Data 5&ordm; Índice');
		$this->reajustes->setValidationLabel('nome', 'Valor 5&ordm; Índice');

		$this->reajustes->save($data) or exit(json_encode(['erro' => $this->reajustes->errors()]));

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function salvarServicos()
	{
		$this->load->library('entities');

		$data = $this->entities->create('stContratosServicos', $this->input->post());

		$this->servicos->setValidationLabel('id_reajuste', 'Mês/Ano Reajuste Existentes');
		$this->servicos->setValidationLabel('data_reajuste', 'Mês/Ano Novo Reajuste');
		$this->servicos->setValidationLabel('descricao', 'Serviços Compartilhados');
		$this->servicos->setValidationLabel('valor', 'Valor (R$)');
		$this->servicos->setValidationLabel('descricao', 'Serviços Não Compartilhados');

		$this->servicos->save($data) or exit(json_encode(['erro' => $this->servicos->errors()]));

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function salvarUnidades()
	{
		$this->load->library('entities');

		$data = $this->entities->create('stContratosUnidades', $this->input->post());

		$this->unidades->setValidationLabel('setor', 'Unidades Selecionadas');

		$this->unidades->save($data) or exit(json_encode(['erro' => $this->unidades->errors()]));

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function excluir()
	{
		$this->contratos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->contratos->errors()]));

		echo json_encode(['status' => true]);
	}

}
