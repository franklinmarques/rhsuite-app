<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ControleFrequencias extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuarios_apontamento_horas_model', 'apontamento');
	}


	public function index()
	{
		$this->load->library('calendar');

		$data = $this->input->get();

		$data['empresa'] = $this->session->userdata('empresa');

		$data['mes'] = $this->calendar->get_month_name(date('m'));

		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), [3, 7, 8, 18]) == false) {
			$usuario = $this->db
				->select('b.id AS id_depto')
				->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
				->where('a.id', $this->session->userdata('id'))
				->get('usuarios a')
				->row();

			$data['depto'] = [$usuario->id_depto => $usuario->depto];

			$areas = $this->db
				->select('id, nome')
				->where('id_departamento', $usuario->id_depto)
				->order_by('nome', 'asc')
				->get('empresa_areas')
				->result();

			$data['area'] = ['' => 'selecione...'] + array_column($areas, 'nome', 'id');
		} else {
			$deptos = $this->db
				->select('id, nome')
				->where('id_empresa', $data['empresa'])
				->order_by('nome', 'asc')
				->get('empresa_departamentos')
				->result();

			$data['depto'] = ['' => 'selecione...'] + array_column($deptos, 'nome', 'id');

			$data['area'] = ['' => 'selecione...'];

		}
		$data['setor'] = ['' => 'selecione...'];

		$this->load->view('controle_frequencias', $data);
	}


	public function filtrar()
	{
		$depto = $this->input->post('depto');

		$area = $this->input->post('area');

		$setor = $this->input->post('setor');

		$rowAreas = $this->db
			->select('a.id, a.nome')
			->join('empresa_departamentos b', 'b.id = a.id_departamento')
			->where('b.id_empresa', $this->session->userdata('empresa'))
			->where('b.id', $depto)
			->order_by('a.nome', 'asc')
			->get('empresa_areas a')
			->result();

		$areas = array_column($rowAreas, 'nome', 'id');

		$rowSetores = $this->db
			->select('a.id, a.nome')
			->join('empresa_areas b', 'b.id = a.id_area')
			->join('empresa_departamentos c', 'c.id = b.id_departamento')
			->where('c.id_empresa', $this->session->userdata('empresa'))
			->where('c.id', $depto)
			->where('b.id', $area)
			->order_by('a.nome', 'asc')
			->get('empresa_setores a')
			->result();

		$setores = array_column($rowSetores, 'nome', 'id');

		$data['area'] = form_dropdown('', ['' => 'selecione...'] + $areas, $area);

		$data['setor'] = form_dropdown('', ['' => 'selecione...'] + $setores, $setor);

		echo json_encode($data);
	}


	public function listar()
	{
		$empresa = $this->session->userdata('empresa');

		$idDepto = $this->input->post('depto');

		$idArea = $this->input->post('area');

		$idSetor = $this->input->post('setor');

		$status = $this->input->post('status');

		$mes = $this->input->post('mes');

		$ano = $this->input->post('ano');

		$colaborador = $this->input->post('colaborador');

		$this->db
			->select('b.nome, a.data_hora, TIME(a.data_hora), a.turno_evento')
			->select('a.justificativa, IFNULL(a.aceite_justificativa, a.modo_cadastramento) AS status')
			->select('a.data_aceite, c.nome AS usuario_aceite, a.observacoes_aceite')
			->select('a.aceite_justificativa, a.id, a.id_usuario')
			->select(["DATE_FORMAT(a.data_hora, '%d/%m/%Y') AS data"], false)
			->select(["TIME_FORMAT(a.data_hora, '%H:%i') AS hora"], false)
			->select(["DATE_FORMAT(a.data_aceite, '%d/%m/%Y') AS data_tratamento"], false)
			->join('usuarios b', 'b.id = a.id_usuario')
			->join('usuarios c', 'c.id = a.id_usuario_aceite', 'left')
			->group_start()
			->where('b.id', $empresa)
			->or_where('b.empresa', $empresa)
			->group_end()
			->where('YEAR(a.data_hora)', $ano)
			->where('MONTH(a.data_hora)', $mes);
		if (!empty($idDepto)) {
			$this->db->where('a.id_depto', $idDepto);
		}
		if (!empty($idArea)) {
			$this->db->where('a.id_area', $idArea);
		}
		if (!empty($idSetor)) {
			$this->db->where('a.id_setor', $idSetor);
		}
		if (!empty($status)) {
			$this->db->where('b.status', $status);
		}
		if (!empty($colaborador)) {
			$this->db->where('a.id_usuario', $colaborador);
		}
		$query = $this->db->get('usuarios_apontamento_horas a');

		$config = [
			'search' => ['nome', 'justificativa', 'usuario_aceite', 'observacoes_aceite']
		];

		$this->load->library('dataTables', $config);

		$output = $this->datatables->generate($query);

		$turnoEvento = $this->apontamento::turnoEvento();
		$modoCadastramento = $this->apontamento::modoCadastramento();
		$statusAceite = $this->apontamento::aceiteJustificativa();

		$data = [];

		foreach ($output->data as $row) {
			$data[] = array(
				$row->nome,
				$row->data,
				$row->hora,
				$turnoEvento[$row->turno_evento] ?? null,
				$row->justificativa,
				$row->aceite_justificativa ? ($statusAceite[$row->status] ?? null) : ($modoCadastramento[$row->status] ?? null),
				$row->data_tratamento,
				$row->usuario_aceite,
				$row->observacoes_aceite,
				'<button class="btn btn-sm btn-info" onclick="edit_evento(' . $row->id . ')" title="Editar evento"><i class="glyphicon glyphicon-pencil"></i></button>
				 <button class="btn btn-sm btn-danger" onclick="delete_evento(' . $row->id . ')" title="Excluir evento"><i class="glyphicon glyphicon-trash"></i></button>'
			);
		}

		$colaboradores = array_column($output->data, 'nome', 'id_usuario');
		asort($colaboradores);

		$output->data = $data;

		$output->colaboradores = form_dropdown('', ['' => 'Todos'] + $colaboradores, $colaborador);

		echo json_encode($output);
	}


	public function editar()
	{
		$data = $this->apontamento->find($this->input->post('id'));

		if (empty($data)) {
			exit();
		}

		$usuario = $this->db->select('nome')->where('id', $data->id_usuario)->get('usuarios')->row();
		$data->nome = $usuario->nome;

		echo json_encode($data);
	}


	public function salvar()
	{
		$this->load->library('entities');

		$data = $this->entities->create('usuariosApontamentoHoras', $this->input->post());
		if (!isset($data->aceite_justificativa)) {
			$data->aceite_justificativa = null;
		}

		$this->apontamento->setValidationRule('aceite_justificativa', 'required|is_natural|less_than_equal_to[1]');

		$this->apontamento->setValidationLabel('aceite_justificativa', 'Status');
		$this->apontamento->setValidationLabel('observacoes_aceite', 'Observações');

		$this->apontamento->save($data) or exit(json_encode(['erro' => $this->apontamento->errors()]));

		echo json_encode(['status' => true]);
	}


	public function excluir()
	{
		$this->apontamento->delete($this->input->post('id')) or
		exit(json_encode(['erro' => $this->apontamento->errors()]));

		echo json_encode(['status' => true]);
	}


	public function imprimir()
	{
		$idUsuario = $this->input->get('id_usuario');
		$mes = $this->input->get('mes');
		$ano = $this->input->get('ano');

		$data = $this->db
			->select('id AS id_empresa, foto, foto_descricao')
			->where('id', $this->session->userdata('empresa'))
			->get('usuarios')
			->row_array();

		$usuario = $this->db
			->select('a.id, a.nome')
			->select('b.nome AS depto, c.nome AS area, d.nome AS setor')
			->join('empresa_departamentos b', 'b.id = a.id_depto', 'left')
			->join('empresa_areas c', 'c.id = a.id_area', 'left')
			->join('empresa_setores d', 'd.id = a.id_setor', 'left')
			->where('a.id', $idUsuario)
			->get('usuarios a')
			->row();

		$data['nome'] = $usuario->nome;
		$data['depto'] = $usuario->depto;
		$data['area'] = $usuario->area;
		$data['setor'] = $usuario->setor;

		$this->load->library('Calendar');
		$mes_ano = ucfirst($this->calendar->get_month_name($mes)) . '/' . $ano;
		$data['mes_ano'] = $mes_ano;

		$eventos = $this->db
			->select('id, observacoes_aceite, justificativa')
			->select('DAY(data_hora) AS dia', false)
			->select(["IF(turno_evento = 'E', TIME_FORMAT(data_hora, '%H:%i'), NULL) AS horario_entrada"], false)
			->select(["IF(turno_evento = 'S', TIME_FORMAT(data_hora, '%H:%i'), NULL) AS horario_saida"], false)
			->where('id_usuario', $idUsuario)
			->where('MONTH(data_hora)', $mes, false)
			->where('YEAR(data_hora)', $ano, false)
			->get('usuarios_apontamento_horas')
			->result();

		$data['rows'] = $eventos;

		$this->load->library('m_pdf');

		$stylesheet = '#totalizacao thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
		$stylesheet .= '#totalizacao thead tr, #totalizacao tbody tr { border-width: 5px; border-color: #ddd; } ';
		$stylesheet .= '#totalizacao tbody td { font-size: 11px; padding: 5px; } ';
		$stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
		$stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

//        $this->m_pdf->pdf->setTopMargin(38);
		$this->m_pdf->pdf->AddPage('L');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->load->view('controle_frequencias_pdf', $data, true));
		unset($data);

		$this->m_pdf->pdf->Output('Controle Frequências ' . $mes_ano . '.pdf', 'D');
	}

}
