<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class FaltasAtrasos extends CI_Controller
{

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

		$this->load->view('faltas_atrasos', $data);
	}

	//==========================================================================
	public function filtrarEstrutura()
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

	//==========================================================================
	public function listar()
	{
		$empresa = $this->session->userdata('empresa');

		$idDepto = $this->input->post('depto');

		$idArea = $this->input->post('area');

		$idSetor = $this->input->post('setor');

		$status = $this->input->post('status');

		$mes = $this->input->post('mes');

		$ano = $this->input->post('ano');

		$query = $this->db
			->select('a.nome, f.nome AS nome_sub, a.banco_horas_icom, a.id AS id_colaborador, e.id')
			->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
			->join('empresa_areas c', 'c.id = a.id_area OR c.nome = a.area')
			->join('empresa_setores d', 'd.id = a.id_setor OR d.nome = a.setor')
			->join('usuarios_faltas_atrasos e',
				"e.id_colaborador = a.id AND MONTH(e.data) = '{$mes}' AND YEAR(e.data) = '{$ano}'",
				'left')
			->join('usuarios f', 'f.id = e.id_colaborador_sub', 'left')
			->where('a.empresa', $empresa)
			->where('b.id', $idDepto)
			->where('c.id', $idArea)
			->where('d.id', $idSetor)
			->where('a.status', $status)
			->get('usuarios a');

		$this->load->library('dataTables', ['search' => ['nome', 'nome_sub']]);

		$output = $this->datatables->generate($query);

		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semana = [];
		for ($i = 1; $i <= 7; $i++) {
			$semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, (int)$mes, $i, (int)$ano))];
		}

		$output->calendar = [
			'mes' => $mes,
			'ano' => $ano,
			'mes_ano' => $this->calendar->get_month_name($mes) . ' ' . $ano,
			'qtde_dias' => date('t', mktime(0, 0, 0, (int)$mes, 1, (int)$ano)),
			'semana' => $semana
		];

		$eventos = $this->db
			->select('a.id, a.id_colaborador, b.nome, a.data, a.status', false)
			->select(["DATE_FORMAT(a.data, '%d/%m/%Y') AS data, DAY(a.data) AS dia"], false)
			->join('usuarios b', 'b.id = a.id_colaborador')
			->where_in('a.id', array_column($output->data, 'id') + [0])
			->get('usuarios_faltas_atrasos a')
			->result();

		$falta_atraso = [];

		$status = [
			'FJ' => 'Falta com atestado próprio',
			'FN' => 'Falta sem atestado',
			'FR' => 'Feriado',
			'AJ' => 'Atraso com atestado próprio',
			'AN' => 'Atraso sem atestado',
			'AE' => 'Apontamento extra',
			'SJ' => 'Saída antec. com atestado próprio',
			'SN' => 'Saída antecipada sem atestado',
			'PD' => 'Posto descoberto',
			'PI' => 'Posto desativado'
		];

		foreach ($eventos as $evento) {
			$falta_atraso[$evento->id_colaborador][$evento->dia] = [
				'text' => $evento->status,
				'status' => $status[$evento->status] ?? null,
				'nome' => $evento->nome,
				'data' => $evento->data
			];
		}

		$arrayDias = range(1, date('t', mktime(0, 0, 0, $mes, 1, $ano)));

		$data = array();

		foreach ($output->data as $row) {
			$rowData = [
				$row->nome,
				$row->nome_sub,
				$row->banco_horas_icom
			];

			for ($i = 0; $i < 31; $i++) {
				if (empty($arrayDias[$i])) {
					$rowData[] = [];
					continue;
				}

				$rowData[] = $falta_atraso[$row->id_colaborador][$arrayDias[$i]] ?? [''];
			}

			$rowData[] = $row->id_colaborador;

			$data[] = $rowData;
		}

		$output->data = $data;

		echo json_encode($output);
	}

	//==========================================================================
	public function editarEvento()
	{
		$data = $this->db
			->where('id_empresa', $this->session->userdata('empresa'))
			->where('id_colaborador', $this->input->post('id_colaborador'))
			->where('data', $this->input->post('data'))
			->get('usuarios_faltas_atrasos')
			->row();

		echo json_encode($data);
	}

	//==========================================================================
	public function salvarEvento()
	{
		$data = $this->input->post();

		$data['id_empresa'] = $this->session->userdata('empresa');
		$data['id_usuario'] = $this->session->userdata('id');

		foreach ($data as &$row) {
			if (strlen($row) == 0) {
				$row = null;
			}
		}

		$this->db->trans_start();

		if ($data['id']) {
			$this->db->update('usuarios_faltas_atrasos', $data, ['id' => $data['id']]);
		} else {
			$this->db->insert('usuarios_faltas_atrasos', $data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Erro ao salvar o evento.']));
		}

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function excluirEvento()
	{
		$this->db->trans_start();

		$this->db->delete('usuarios_faltas_atrasos', ['id' => $this->input->post('id')]);

		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Erro ao excluir o evento.']));
		}

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function salvarEventos()
	{
		parse_str($this->input->post('eventos'), $eventos);

		$this->db->trans_start();

		$this->db
			->select("'{$this->session->userdata('empresa')}' AS id_empresa", false)
			->select("'{$this->session->userdata('id')}' AS id_usuario", false)
			->select('a.id AS id_colaborador, b.id AS id_depto, c.id AS id_area, d.id AS id_setor')
			->select("'{$eventos['data']}' AS data, '{$eventos['status']}' AS status", false)
			->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
			->join('empresa_areas c', 'c.id = a.id_area OR c.nome = a.area')
			->join('empresa_setores d', 'd.id = a.id_setor OR d.nome = a.setor')
			->join('usuarios_faltas_atrasos e', "e.id_usuario = a.id AND e.id_depto = b.id AND e.id_area = c.id AND e.id_setor = d.id AND e.data = '{$eventos['data']}'", 'left')
			->where('b.id', $this->input->post('id_depto'))
			->where('c.id', $this->input->post('id_area'))
			->where('d.id', $this->input->post('id_setor'))
			->where('e.id', null);
		if ($this->input->post('status')) {
			$this->db->where('a.status', $this->input->post('status'));
		}
		$data = $this->db
			->group_by('a.id')
			->get('usuarios a')
			->result_array();

		if ($data) {
			$this->db->insert_batch('usuarios_faltas_atrasos', $data);
		}

		$this->db->trans_complete();

		if ($this->db->trans_start() == false) {
			exit(json_encode(['erro' => 'Não foi possível replicar os eventos.']));
		}

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function excluirEventos()
	{
		parse_str($this->input->post('eventos'), $eventos);

		$this->db->trans_start();

		$this->db
			->select('a.id')
			->join('usuarios b', 'b.id = a.id_colaborador')
			->where('a.id_depto', $this->input->post('id_depto'))
			->where('a.id_area', $this->input->post('id_area'))
			->where('a.id_setor', $this->input->post('id_setor'))
			->where('a.data', $eventos['data'])
			->where('a.status', $eventos['status']);
		if ($this->input->post('status')) {
			$this->db->where('b.status', $this->input->post('status'));
		}
		$usuarios = $this->db
			->get('usuarios_faltas_atrasos a')
			->result();

		$this->db
			->where_in('id', array_column($usuarios ?? [], 'id') + [0])
			->delete('usuarios_faltas_atrasos');

		$this->db->trans_complete();

		if ($this->db->trans_start() == false) {
			exit(json_encode(['erro' => 'Não foi possível limpar os eventos.']));
		}

		echo json_encode(['status' => false]);
	}

}
