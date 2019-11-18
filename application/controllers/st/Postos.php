<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Postos extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('st_postos_model', 'postos');
	}

	//==========================================================================
	public function index()
	{
		$empresa = $this->session->userdata('empresa');
		$arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao', 'contrato');

		$data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

		foreach ($arrSql as $field) {
			$sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '') 
                    ORDER BY {$field} ASC";
			$rows = $this->db->query($sql)->result_array();
			$data[$field] = array('' => 'Todos');
			foreach ($rows as $row) {
				$data[$field][$row[$field]] = $row[$field];
			}
		}
		$data['cargo'][''] = 'selecione...';
		$data['funcao'][''] = 'selecione...';

		$this->db->select('id, nome');
		$this->db->where('empresa', $empresa);
		$this->db->where('status', '1');
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();
		$data['usuarios'] = array('' => 'selecione...');
		foreach ($usuarios as $usuario) {
			$data['usuarios'][$usuario->id] = $usuario->nome;
		}

		$this->load->view('st/postos', $data);
	}

	//==========================================================================
	public function atualizar_filtro()
	{
		$depto = $this->input->post('depto');
		$area = $this->input->post('area');
		$setor = $this->input->post('setor');
		$cargo = $this->input->post('cargo');
		$funcao = $this->input->post('funcao');

		$filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
			if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
				unset($filtro['area'][''], $filtro['setor']['']);
			}
			unset($filtro['depto']['']);
		}

		$data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'onchange="atualizarFiltro()" class="form-control input-sm"');

		$this->db->select('id, nome');
		$this->db->where('empresa', $this->session->userdata('empresa'));
		if ($depto) {
			$this->db->where('depto', $depto);
		}
		if ($area) {
			$this->db->where('area', $area);
		}
		if ($setor) {
			$this->db->where('setor', $setor);
		}
		if ($cargo) {
			$this->db->where('cargo', $cargo);
		}
		if ($funcao) {
			$this->db->where('funcao', $funcao);
		}
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();
		$options = array('' => 'selecione...');
		foreach ($usuarios as $usuario) {
			$options[$usuario->id] = $usuario->nome;
		}
		$data['id_usuario'] = form_dropdown('id_usuario', $options, '', 'class="form-control"');

		echo json_encode($data);
	}

	//==========================================================================
	public function listar()
	{
		$this->db
			->select('b.nome, a.data')
			->select("FORMAT(a.valor_posto, 2, 'de_DE') AS valor_posto", false)
			->select('a.total_dias_mensais, a.total_horas_diarias')
			->select("FORMAT(a.valor_dia, 2, 'de_DE') AS valor_dia", false)
			->select("FORMAT(a.valor_hora, 2, 'de_DE') AS valor_hora", false)
			->select("DATE_FORMAT(a.data, '%m') AS mes, YEAR(a.data) AS ano, a.id", false)
			->join('usuarios b', 'b.id = a.id_usuario')
			->where('b.empresa', $this->session->userdata('empresa'));
		if (!empty($busca['depto'])) {
			$this->db->where('a.depto', $busca['depto']);
		}
		if (!empty($busca['area'])) {
			$this->db->where('a.area', $busca['area']);
		}
		if (!empty($busca['setor'])) {
			$this->db->where('b.setor', $busca['setor']);
		}
		if (!empty($busca['cargo'])) {
			$this->db->where('b.cargo', $busca['cargo']);
		}
		if (!empty($busca['funcao'])) {
			$this->db->where('b.funcao', $busca['funcao']);
		}
		if (!empty($busca['contrato'])) {
			$this->db->where('a.contrato', $busca['contrato']);
		}
		if (!empty($busca['busca_mes'])) {
			$this->db->where('MONTH(a.data)', $busca['busca_mes']);
		}
		if (!empty($busca['busca_ano'])) {
			$this->db->where('YEAR(a.data)', $busca['busca_ano']);
		}
		$query = $this->db->get('alocacao_postos a');

		$config = ['s.nome', 's.data', 's.valor_posto', 's.total_dias_mensais', 's.total_horas_diarias', 's.valor_dia', 's.valor_hora'];

		$this->load->library('dataTables', $config);

		$output = $this->datatables->generate($query);

		$data = [];

		$this->load->library('Calendar');

		foreach ($output->data as $row) {
			$data[] = array(
				$row->nome,
				$this->calendar->get_month_name($row->mes) . '/' . $row->ano,
				$row->valor_posto,
				$row->total_dias_mensais,
				$row->total_horas_diarias,
				$row->valor_dia,
				$row->valor_hora,
				'<button class="btn btn-sm btn-info" onclick="edit_posto(' . $row->id . ')" title="Editar posto"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_posto(' . $row->id . ')" title="Excluir posto"><i class="glyphicon glyphicon-trash"></i></button>'
			);
		}

		$output->data = $data;

		echo json_encode($output);
	}

	//==========================================================================
	public function editar()
	{
		$data = $this->postos->find($this->input->post());

		$this->formatarEdicao($data);
	}

	//==========================================================================
	public function copiarUltimoPosto()
	{
		$data = $this->postos
			->where('id_usuario', $this->input->post('id_usuario'))
			->order_by('data', 'desc')
			->limit(1)
			->find();

		$this->formatarEdicao($data);
	}

	//==========================================================================
	private function formatarEdicao($data = null)
	{
		if (empty($data)) {
			exit(json_encode($this->postos->errors()));
		}

		if ($data->valor_posto) {
			$data->valor_posto = number_format($data->valor_posto, 2, ',', '.');
		}

		if ($data->valor_dia) {
			$data->valor_dia = number_format($data->valor_dia, 2, ',', '.');
		}

		if ($data->valor_hora) {
			$data->valor_hora = number_format($data->valor_hora, 2, ',', '.');
		}

		if ($data->horario_entrada) {
			$data->horario_entrada = date('H:i', strtotime($data->horario_entrada));
		}

		if ($data->horario_saida) {
			$data->horario_saida = date('H:i', strtotime($data->horario_saida));
		}
		if ($data->data) {
			$data->mes = date('m', strtotime($data->data));
			$data->ano = date('Y', strtotime($data->data));
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function salvar()
	{
		$this->load->library('entities');

		$data = $this->entities->create('StPostos', $this->input->post());

		$this->postos->setValidationRule('mes', 'required|is_natural_no_zero|less_than_equal_to[12]');
		$this->postos->setValidationRule('ano', 'required|is_natural_no_zero|max_length[4]');

		$this->postos->setValidationLabel('id_usuario', 'Colaborador');
		$this->postos->setValidationLabel('mes', 'Mês');
		$this->postos->setValidationLabel('ano', 'Ano');
		$this->postos->setValidationLabel('matricula', 'Matrícila');
		$this->postos->setValidationLabel('login', 'Login');
		$this->postos->setValidationLabel('valor_posto', 'Valor Posto');
		$this->postos->setValidationLabel('total_dias_mensais', 'Qtde. Dias');
		$this->postos->setValidationLabel('valor_dia', 'Valor (Dia)');
		$this->postos->setValidationLabel('total_horas_diarias', 'Qtde. Horas');
		$this->postos->setValidationLabel('valor_hora', 'Valor (Hora)');
		$this->postos->setValidationLabel('horario_entrada', 'Horário Entrada');
		$this->postos->setValidationLabel('horario_saida', 'Horário Saída');

		$this->postos->validate($data) or exit(json_encode(['erro' => $this->postos->errors()]));

		$data->data = date('Y-m-d', mktime(0, 0, 0, (int)$data->mes, 1, (int)$data->ano));
		unset($data->mes, $data->ano);

		$this->postos->skipValidation();

		$this->postos->save($data) or exit(json_encode(['erro' => $this->postos->errors()]));

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function excluir()
	{
		$this->postos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->postos->errors()]));

		echo json_encode(['status' => true]);
	}

}
