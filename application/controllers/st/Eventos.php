<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('st_apontamento_model', 'apontamento');
	}

	//==========================================================================
	public function index()
	{
		$empresa = $this->session->userdata('empresa');
		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {

			$this->db->select('depto, area, setor');
			$this->db->where('id', $this->session->userdata('id'));
			$filtro = $this->db->get('usuarios')->row();

			if (in_array($this->session->userdata('nivel'), array(9, 10))) {
				$data = $this->get_filtros_usuarios($filtro->depto);
				$data['depto'] = array($filtro->depto => $filtro->depto);
			} else {
				$data = $this->get_filtros_usuarios($filtro->depto, $filtro->area, $filtro->setor);
				$data['depto'] = array($filtro->depto => $filtro->depto);
				$data['area'] = array($filtro->area => $filtro->area);
				$data['setor'] = array($filtro->setor => $filtro->setor);
			}
		} else {
			$this->db->select('depto');
			$this->db->like('depto', 'servicos terceirizados');
			$filtro = $this->db->get('usuarios')->row();
			$data = $this->get_filtros_usuarios($filtro->depto);
		}

		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
			if (in_array($this->session->userdata('nivel'), array(9, 10))) {
				$this->db->select("depto, '' AS area, '' AS setor", false);
			} else {
				$this->db->select('depto, area, setor');
			}
			$this->db->where('id', $this->session->userdata('id'));
		} else {
			$this->db->select("depto, '' AS area, '' AS setor", false);
			$this->db->like('depto', 'serviços terceirizados');
		}
		$status = $this->db->get('usuarios')->row();
		$data['depto_atual'] = $status->depto;
		$data['area_atual'] = $status->area;
		$data['setor_atual'] = $status->setor;

		$this->db->select('DISTINCT(contrato) AS nome', false);
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('CHAR_LENGTH(contrato) >', 0);
		$contratos = $this->db->get('usuarios')->result();
		$data['contrato'] = array('' => 'Todos');
		foreach ($contratos as $contrato) {
			$data['contrato'][$contrato->nome] = $contrato->nome;
		}

		$this->db->select('id, nome');
		$this->db->where('empresa', $empresa);
		$this->db->where('status', '1');
		$usuarios = $this->db->get('usuarios')->result();
		$data['usuarios'] = array('' => 'selecione...');
		foreach ($usuarios as $usuario) {
			$data['usuarios'][$usuario->id] = $usuario->nome;
		}

		$data['meses'] = array(
			'' => 'Todos',
			'01' => 'Janeiro',
			'02' => 'Fevereiro',
			'03' => 'Março',
			'04' => 'Abril',
			'05' => 'Maio',
			'06' => 'Junho',
			'07' => 'Julho',
			'08' => 'Agosto',
			'09' => 'Setembro',
			'10' => 'Outubro',
			'11' => 'Novembro',
			'12' => 'Dezembro'
		);
		$data['mes'] = $this->input->get('mes');
		$data['ano'] = $this->input->get('ano');

		$this->load->view('st/eventos', $data);
	}

	//==========================================================================
	public function atualizar_filtro()
	{
		$empresa = $this->session->userdata('empresa');
		$get = $this->input->get();
		$filtro = array(
			'area' => array('' => 'Todas'),
			'setor' => array('' => 'Todos'),
			'cargo' => array('' => 'Todos'),
			'funcao' => array('' => 'Todas'),
			'contrato' => array('' => 'Todos')
		);


		$this->db->select('area AS nome', false);
		$this->db->where('id_empresa', $empresa);
		$this->db->where('CHAR_LENGTH(area) >', 0);
		if ($get['depto']) {
			$this->db->where('depto', $get['depto']);
		}
		$this->db->group_by('area');
		$areas = $this->db->get('alocacao')->result();
		foreach ($areas as $area) {
			$filtro['area'][$area->nome] = $area->nome;
		}


		$this->db->select('setor AS nome', false);
		$this->db->where('id_empresa', $empresa);
		$this->db->where('CHAR_LENGTH(setor) >', 0);
		if ($get['depto']) {
			$this->db->where('depto', $get['depto']);
		}
		if ($get['area']) {
			$this->db->where('area', $get['area']);
		}
		$this->db->group_by('setor');
		$setores = $this->db->get('alocacao')->result();
		foreach ($setores as $setor) {
			$filtro['setor'][$setor->nome] = $setor->nome;
		}


		$this->db->select('a.cargo AS nome', false);
		$this->db->join('alocacao_usuarios b', 'b.id_usuario = a.id');
		$this->db->join('alocacao c', 'c.id = b.id_alocacao');
		$this->db->where('c.id_empresa', $empresa);
		$this->db->where('CHAR_LENGTH(a.cargo) >', 0);
		if ($get['depto']) {
			$this->db->where('c.depto', $get['depto']);
		}
		if ($get['area']) {
			$this->db->where('c.area', $get['area']);
		}
		if ($get['setor']) {
			$this->db->where('c.setor', $get['setor']);
		}
		$this->db->group_by('a.cargo');
		$cargos = $this->db->get('usuarios a')->result();
		foreach ($cargos as $cargo) {
			$filtro['cargo'][$cargo->nome] = $cargo->nome;
		}


		$this->db->select('a.funcao AS nome', false);
		$this->db->join('alocacao_usuarios b', 'b.id_usuario = a.id');
		$this->db->join('alocacao c', 'c.id = b.id_alocacao');
		$this->db->where('c.id_empresa', $empresa);
		$this->db->where('CHAR_LENGTH(a.funcao) >', 0);
		if ($get['depto']) {
			$this->db->where('c.depto', $get['depto']);
		}
		if ($get['area']) {
			$this->db->where('c.area', $get['area']);
		}
		if ($get['setor']) {
			$this->db->where('c.setor', $get['setor']);
		}
		if ($get['cargo']) {
			$this->db->where('a.cargo', $get['cargo']);
		}
		$this->db->group_by('a.funcao');
		$funcoes = $this->db->get('usuarios a')->result();
		foreach ($funcoes as $funcao) {
			$filtro['funcao'][$funcao->nome] = $funcao->nome;
		}


		$this->db->select('a.contrato AS nome', false);
		$this->db->join('alocacao_usuarios b', 'b.id_usuario = a.id');
		$this->db->join('alocacao c', 'c.id = b.id_alocacao');
		$this->db->where('c.id_empresa', $empresa);
		$this->db->where('CHAR_LENGTH(a.contrato) >', 0);
		if ($get['depto']) {
			$this->db->where('c.depto', $get['depto']);
		}
		if ($get['area']) {
			$this->db->where('c.area', $get['area']);
		}
		if ($get['setor']) {
			$this->db->where('c.setor', $get['setor']);
		}
		if ($get['cargo']) {
			$this->db->where('a.cargo', $get['cargo']);
		}
		if ($get['funcao']) {
			$this->db->where('a.funcao', $get['funcao']);
		}
		$this->db->group_by('a.contrato');
		$contratos = $this->db->get('usuarios a')->result();
		foreach ($contratos as $contrato) {
			$filtro['contrato'][$contrato->nome] = $contrato->nome;
		}


		$data['area'] = form_dropdown('area', $filtro['area'], $get['area'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
		$data['setor'] = form_dropdown('setor', $filtro['setor'], $get['setor'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
		$data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $get['cargo'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
		$data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $get['funcao'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
		$data['contrato'] = form_dropdown('contrato', $filtro['contrato'], $get['contrato'], 'class="form-control input-sm filtro"');


		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_list()
	{
		parse_str($this->input->post('busca'), $busca);

		$output = $this->montarEventos($busca);

		echo json_encode($output);
	}

	//==========================================================================
	private function montarEventos($busca = [])
	{
		$this->db
			->select('b.nome, a.data AS original_data, a.status')
			->select(["(CASE WHEN a.status IN ('FJ', 'FN', 'FR') THEN CONCAT(a.qtde_dias, 'd') ELSE TIME_FORMAT(a.hora_glosa, '%H:%i') END) AS glosa"], false)
			->select('c.nome AS nome_bck')
			->select(["TIME_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_desc"], false)
			->select(["TIME_FORMAT(a.apontamento_extra, '%H:%i') AS apontamento_extra"], false)
			->select('f.nome AS detalhes, a.observacoes')
			->select(["DATE_FORMAT(a.data, '%d/%m/%Y') AS data"], false)
			->join('alocacao_usuarios d', 'd.id = a.id_alocado')
			->join('alocacao e', 'e.id = d.id_alocacao')
			->join('usuarios b', 'b.id = d.id_usuario')
			->join('usuarios c', 'c.id = a.id_alocado_bck', 'left')
			->join('alocacao_eventos f', 'f.id = a.detalhes', 'left')
			->where('e.id_empresa', $this->session->userdata('empresa'));
		if (!empty($busca['depto'])) {
			$this->db->where('e.depto', $busca['depto']);
		}
		if (!empty($busca['area'])) {
			$this->db->where('e.area', $busca['area']);
		}
		if (!empty($busca['setor'])) {
			$this->db->where('e.setor', $busca['setor']);
		}
		if (!empty($busca['cargo'])) {
			$this->db->where('b.cargo', $busca['cargo']);
		}
		if (!empty($busca['funcao'])) {
			$this->db->where('b.funcao', $busca['funcao']);
		}
		if (!empty($busca['contrato'])) {
			$this->db->where('b.contrato', $busca['contrato']);
		}
		if (!empty($busca['mes'])) {
			$this->db->where('MONTH(e.data)', $busca['mes']);
		}
		if (!empty($busca['ano'])) {
			$this->db->where('YEAR(e.data)', $busca['ano']);
		}
		$query = $this->db->get('alocacao_apontamento a');

		$this->load->library('dataTables', ['search' => ['nome', 'nome_bck', 'detalhes', 'observacoes']]);

		$output = $this->datatables->generate($query);

		$data = [];

		foreach ($output->data as $row) {
			$data[] = [
				$row->nome,
				$row->data,
				$row->status,
				$row->glosa,
				$row->nome_bck,
				$row->apontamento_desc,
				$row->apontamento_extra,
				$row->detalhes,
				$row->observacoes
			];
		}

		$output->data = $data;

		return $output;
	}

	//==========================================================================
	public function pdf()
	{
		$this->load->library('m_pdf');

		$stylesheet = '#titulo thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
		$stylesheet .= '#titulo thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
		$stylesheet .= '#titulo tbody td { font-size: 10px; padding: 5px; } ';
		$stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
		$stylesheet .= '#table tbody td { font-size: 10px; padding: 5px; vertical-align: top; } ';
		$stylesheet .= '#legenda thead th { font-size: 13px; padding: 5px; background-color: #f5f5f5;} ';
		$stylesheet .= '#legenda tbody td { font-size: 11px; padding: 5px; vertical-align: top; } ';

		$this->m_pdf->pdf->setTopMargin(12);
		$this->m_pdf->pdf->AddPage('L');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);

		$this->db->select('foto, foto_descricao');
		$this->db->where('id', $this->session->userdata('empresa'));
		$data['empresa'] = $this->db->get('usuarios')->row();

		$output = $this->montarEventos($this->input->get());

		$data['rows'] = $output->data ?? null;

		$this->m_pdf->pdf->writeHTML($this->load->view('st/pdf_eventos', $data, true));

		$this->m_pdf->pdf->Output('Relatório de eventos.pdf', 'D');
	}

}
