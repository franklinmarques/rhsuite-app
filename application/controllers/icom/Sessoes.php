<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sessoes extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model([
			'icom_sessoes_model' => 'sessoes',
			'icom_sessoes_profissionais_model' => 'profissionais',
			'icom_faturamento_model' => 'faturamento',
			'icom_pagamento_model' => 'pagamento'
		]);
	}

	//==========================================================================
	public function index()
	{
		$this->load->model('icom_clientes_model', 'clientes');
		$this->load->model('icom_produtos_model', 'produtos');
		$this->load->model('icom_contratos_model', 'contratos');
		$this->load->model('empresa_departamentos_model', 'deptos');
		$this->load->model('usuarios_model', 'usuarios');

		$this->load->library('calendar');

		$dias = range(1, date('t', mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y'))));
		$diasFormatados = array_map(function ($dia) {
			return str_pad($dia, 2, '0', STR_PAD_LEFT);
		}, $dias);

		$data = [
			'filtro_clientes' => $this->clientes->findColumn('nome', 'id', 'Todos'),
			'filtro_produtos' => $this->produtos->findColumn('nome', 'id', 'Todos'),
			'filtro_profissionais' => $this->atualizarFiltroProfissionais(),
			'clientes' => $this->clientes->findColumn('nome', 'id', 'selecione...'),
			'produtos' => $this->produtos->findColumn('nome', 'id', 'selecione...'),
			'contratos' => $this->contratos->findColumn('codigo', 'codigo', 'selecione...'),
			'deptos' => $this->deptos->findColumn('nome', 'id', 'selecione...'),
			'mes_ano' => $this->calendar->get_month_name(date('m')) . ' de ' . date('Y'),
			'dias' => ['' => 'Todos'] + array_combine($diasFormatados, $diasFormatados),
			'meses' => [
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
			]
		];

		$this->load->view('icom/sessoes', $data);
	}


	private function atualizarFiltroProfissionais()
	{
		$rowProfissionaisAlocados = $this->db->select('b.id, b.nome')
			->join('usuarios b', 'b.id = a.id_profissional_alocado')
			->where('empresa', $this->session->userdata('empresa'))
			->where('tipo', 'funcionario')
			->order_by('b.nome', 'asc')
			->group_by('b.id')
			->get('icom_sessoes a')
			->result();
		return ['' => 'Todos'] + array_column($rowProfissionaisAlocados, 'nome', 'id');
	}

	//==========================================================================
	public function listar()
	{
		parse_str($this->input->post('busca'), $busca);

		$this->db
			->select(["DATE_FORMAT(a.data_evento, '%d/%m/%Y') AS data_evento"], false)
			->select('b.nome AS produto, e.nome AS cliente')
			->select(["TIME_FORMAT(a.horario_inicio, '%H:%i') AS horario_inicio"], false)
			->select(["FORMAT(a.qtde_horas, 2, 'de_DE') AS qtde_horas"], false)
			->select('f.nome AS profissional_alocado')
			->select(["FORMAT(a.valor_faturamento, 2, 'de_DE') AS valor_faturamento"], false)
			->select(["FORMAT(a.valor_pagamento_profissional, 2, 'de_DE') AS valor_pagamento_profissional"], false)
			->select('a.valor_faturamento AS valor_faturado, a.valor_pagamento_profissional AS valor_pago, a.id')
			->select('g.id AS id_faturamento, h.id AS id_pagamento', false)
			->join('icom_produtos b', 'b.id = a.id_produto')
			->join('icom_clientes e', 'e.id = a.id_cliente')
			->join('icom_contratos c', 'c.codigo = a.codigo_contrato', 'left')
			->join('icom_propostas d', 'd.codigo = c.codigo_proposta', 'left')
			->join('usuarios f', 'f.id = a.id_profissional_alocado', 'left')
			->join('icom_faturamento g', 'g.id_cliente = a.id_cliente AND g.mes_referencia = MONTH(a.data_evento) AND g.ano_referencia = YEAR(a.data_evento)', 'left')
			->join('icom_pagamento h', 'h.id_profissional_alocado = a.id_profissional_alocado AND h.mes_referencia = MONTH(a.data_evento) AND h.ano_referencia = YEAR(a.data_evento)', 'left')
			->where('b.id_empresa', $this->session->userdata('empresa'));
		if ($busca['dia']) {
			$this->db->where('DAY(a.data_evento)', $busca['dia']);
		}
		if ($busca['mes']) {
			$this->db->where('MONTH(a.data_evento)', $busca['mes']);
		}
		if ($busca['ano']) {
			$this->db->where('YEAR(a.data_evento)', $busca['ano']);
		}
		if ($busca['produto']) {
			$this->db->where('b.id', $busca['produto']);
		}
		if ($busca['cliente']) {
			$this->db->where('e.id', $busca['cliente']);
		}
		if ($busca['profissional']) {
			$this->db->where('a.id_profissional_alocado', $busca['profissional']);
		}
		$query = $this->db->get($this->sessoes::table() . ' a');

		$config = [
			'search' => ['produto', 'cliente', 'profissional_alocado']
		];

		$this->load->library('dataTables', $config);

		$output = $this->datatables->generate($query);

		$data = [];
		$totalFaturado = 0;
		$totalPago = 0;

		foreach ($output->data as $row) {
			$data[] = [
				$row->data_evento,
				$row->produto,
				$row->cliente,
				$row->horario_inicio,
				$row->qtde_horas,
				$row->profissional_alocado,
				$row->valor_faturamento,
				$row->valor_pagamento_profissional,
				'<button class="btn btn-sm btn-info" onclick="edit_sessao(' . $row->id . ')" title="Editar sessão de atividades"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_sessao(' . $row->id . ')" title="Excluir sessão de atividades"><i class="glyphicon glyphicon-trash"></i></button>',
				$row->id_faturamento,
				$row->id_pagamento
			];
			$totalFaturado += $row->valor_faturado;
			$totalPago += $row->valor_pago;
		}

		$output->data = $data;
		$output->totalFaturamento = number_format($totalFaturado, 2, ',', '.');
		$output->totalPagamento = number_format($totalPago, 2, ',', '.');

		echo json_encode($output);
	}

	//==========================================================================
	public function listarPeriodos()
	{
		parse_str($this->input->post('busca'), $busca);

		$mes = strlen($busca['mes']) > 0 ? $busca['mes'] : date('m');
		$ano = strlen($busca['ano']) > 0 ? $busca['ano'] : date('Y');

		$this->db
			->select('COUNT(a.id) AS id, DAY(a.data_evento) AS dia', false)
			->select('FLOOR(TIME_TO_SEC(a.horario_inicio) / 21600) AS periodo', false)
			->join('icom_produtos b', 'b.id = a.id_produto')
			->join('icom_clientes e', 'e.id = a.id_cliente')
			->join('icom_contratos c', 'c.codigo = a.codigo_contrato', 'left')
			->join('icom_propostas d', 'd.codigo = c.codigo_proposta', 'left')
			->where('b.id_empresa', $this->session->userdata('empresa'))
			->where('MONTH(a.data_evento)', $mes)
			->where('YEAR(a.data_evento)', $ano);
		if ($busca['cliente']) {
			$this->db->where('e.id', $busca['cliente']);
		}
		if ($busca['produto']) {
			$this->db->where('b.id', $busca['produto']);
		}
		$query = $this->db
			->group_by(['a.data_evento', 'FLOOR(TIME_TO_SEC(a.horario_inicio) / 21600)'])
			->get($this->sessoes::table() . ' a');

		$this->load->library('dataTables');

		$output = $this->datatables->generate($query);

		$ultimoDiaMes = date('t', mktime(0, 0, 0, (int)$mes, 1, (int)$ano));
		$diasMes = array_pad([], $ultimoDiaMes, '');
		$totalDiasMes = array_pad($diasMes, 31, null);

		$data = [
			array_merge(['Madrugada'], $totalDiasMes),
			array_merge(['Manhã'], $totalDiasMes),
			array_merge(['Tarde'], $totalDiasMes),
			array_merge(['Noite'], $totalDiasMes)
		];

		foreach ($output->data as $row) {
			$data[$row->periodo][$row->dia] = $row->id;
		}

		$madrugada = $data[0];
		array_shift($madrugada);
		if (empty(array_filter($madrugada))) {
			array_shift($data);
		}

		$this->load->library('calendar');

		$dias = range(1, date('t', mktime(0, 0, 0, (int)$mes, 1, (int)$ano)));
		$diasFormatados = array_map(function ($dia) {
			return str_pad($dia, 2, '0', STR_PAD_LEFT);
		}, $dias);

		$output->data = $data;
		$output->days = form_dropdown('', ['' => 'Todos'] + array_combine($diasFormatados, $diasFormatados), $busca['dia']);
		$output->recordsTotal = count($data);
		$output->recordsFiltered = $output->recordsTotal;
		$output->lastDayOfMonth = $ano . '-' . $mes . '-' . $ultimoDiaMes;

		$this->load->model('icom_clientes_model', 'clientes');

		$clientes = $this->clientes->findColumn('nome', 'id', 'Todos');
		$profissionais = $this->atualizarFiltroProfissionais();
		$output->clientes = form_dropdown('', $clientes, $busca['cliente'] ?? '');
		$output->profissionais = form_dropdown('', $profissionais, $busca['profissional'] ?? '');

		echo json_encode($output);
	}

	//==========================================================================
	public function editar()
	{
		$data = $this->sessoes->find($this->input->post('id'));

		if (empty($data)) {
			exit(json_encode(['erro' => $this->sessoes->errors()]));
		};

		$cliente = $this->db
			->select('c.id')
			->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
			->join('icom_clientes c', 'c.id = b.id_cliente')
			->where('a.codigo', $data->codigo_contrato)
			->get('icom_contratos a')
			->row();

		$rowsContratos = $this->db
			->select('a.codigo')
			->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
			->join('icom_clientes c', 'c.id = b.id_cliente')
			->where('c.id', $data->id_cliente)
			->order_by('a.codigo', 'asc')
			->get('icom_contratos a')
			->result();

		$contratos = ['' => 'selecione...'] + array_column($rowsContratos, 'codigo', 'codigo');

		$data->contratos = form_dropdown('', $contratos, $data->codigo_contrato);
		unset($data->codigo_contrato);

//		$profissionais = $this->profissionais->where('id_sessao', $this->input->post('id'))->findAll();

		$rowUsuarios = $this->db
			->select('a.id, a.nome')
			->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
			->where('b.id', $data->id_depto_prestador_servico)
			->where('a.empresa', $this->session->userdata('empresa'))
			->where('a.tipo', 'funcionario')
			->where('a.status', 1)
			->order_by('a.nome', 'asc')
			->get('usuarios a')
			->result();

		$usuarios = ['' => 'selecione...'] + array_column($rowUsuarios, 'nome', 'id');

		$data->id_profissional_alocado = form_dropdown('', $usuarios, $data->id_profissional_alocado);

//		$data->profissionais = [];
//		foreach ($profissionais as $profissional) {
//			$data->profissionais[] = [
//				'id_sessao_profissional' => $profissional->id,
//				'id_profissional_alocado' => form_dropdown('', $usuarios, $profissional->id_profissional_alocado),
//				'valor_pagamento' => number_format($profissional->valor_pagamento, 2, ',', '.')
//			];
//		}
//
//		unset($data->id_profissional_alocado);

		$this->load->helper('time');

		$data->data_evento = date('d/m/Y', strtotime($data->data_evento));
		$data->horario_inicio = timeSimpleFormat($data->horario_inicio);
		$data->horario_termino = timeSimpleFormat($data->horario_termino);
		if (strlen($data->qtde_horas) > 0) {
			$data->qtde_horas = number_format($data->qtde_horas, 2, ',', '.');
		}
		if (strlen($data->valor_faturamento) > 0) {
			$data->valor_faturamento = number_format($data->valor_faturamento, 2, ',', '.');
		}
		if (strlen($data->valor_desconto) > 0) {
			$data->valor_desconto = number_format($data->valor_desconto, 2, ',', '.');
		}
		if (strlen($data->custo_operacional) > 0) {
			$data->custo_operacional = number_format($data->custo_operacional, 2, ',', '.');
		}
		if (strlen($data->custo_impostos) > 0) {
			$data->custo_impostos = number_format($data->custo_impostos, 2, ',', '.');
		}
		if (strlen($data->valor_pagamento_profissional) > 0) {
			$data->valor_pagamento_profissional = number_format($data->valor_pagamento_profissional, 2, ',', '.');
		}


		echo json_encode($data);
	}

	//==========================================================================
	public function filtrarContratos()
	{
		$rowsContratos = $this->db
			->select('a.codigo')
			->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
			->join('icom_clientes c', 'c.id = b.id_cliente')
			->where('c.id', $this->input->post('id_cliente'))
			->order_by('a.codigo', 'asc')
			->get('icom_contratos a')
			->result();

		$contratos = ['' => 'selecione...'] + array_column($rowsContratos, 'codigo', 'codigo');

		$data = ['contratos' => form_dropdown('', $contratos, $this->input->post('codigo_contrato'))];

		echo json_encode($data);
	}

	//==========================================================================
	public function filtrarProfissionais()
	{
		$rowUsuarios = $this->db
			->select('a.id, a.nome')
			->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
			->where('b.id', $this->input->post('id_depto'))
			->where('a.empresa', $this->session->userdata('empresa'))
			->where('a.tipo', 'funcionario')
			->where('a.status', 1)
			->order_by('a.nome', 'asc')
			->get('usuarios a')
			->result();

		$usuarios = ['' => 'selecione...'] + array_column($rowUsuarios, 'nome', 'id');

		$data = ['usuarios' => form_dropdown('', $usuarios, $this->input->post('id_usuario'))];

		echo json_encode($data);
	}


	//==========================================================================
	public function calcularValorProduto()
	{
		$this->load->model('icom_produtos_model', 'produtos');

		$produto = $this->produtos->select('preco, custo, tipo_cobranca')->find($this->input->post('id_produto'));

		$data = [
			'preco' => $produto->preco ?? '',
			'custo' => $produto->custo ?? '',
			'tipo_cobranca' => $produto->tipo_cobranca ?? ''
		];

		echo json_encode($data);
	}

	//==========================================================================
	public function salvar()
	{
		$this->load->library('entities');

		$rows = $this->entities->create('icomSessoes', $this->input->post())->toArray();

		$data = [];
		foreach ($rows['id_profissional_alocado'] as $k => $idProfissionalAlocado) {
			$data[$k] = $rows;
			$data[$k]['id_profissional_alocado'] = $rows['id_profissional_alocado'][$k];
			$data[$k]['valor_pagamento_profissional'] = $rows['valor_pagamento_profissional'][$k];
		}

		$this->sessoes->setValidationRule('id_cliente', 'required|is_natural_no_zero|max_length[11]');
		$this->sessoes->setValidationRule('id_profissional_alocado[]', 'required|is_natural_no_zero|max_length[11]');
		$this->sessoes->setValidationRule('valor_pagamento_profissional[]', 'numeric|max_length[11]');
		$this->sessoes->setValidationRule('id_profissional_alocado', false);
		$this->sessoes->setValidationRule('valor_pagamento_profissional', false);

		$this->sessoes->setValidationLabel('id_cliente', 'Cliente');
		$this->sessoes->setValidationLabel('id_produto', 'Produto');
		$this->sessoes->setValidationLabel('codigo_contrato', 'Contrato');
		$this->sessoes->setValidationLabel('data_evento', 'Data Evento');
		$this->sessoes->setValidationLabel('horario_inicio', 'Horário Início');
		$this->sessoes->setValidationLabel('horario_termino', 'Horário Término');
		$this->sessoes->setValidationLabel('qtde_horas', 'Qtde. Horas');
		$this->sessoes->setValidationLabel('valor_desconto', 'Desconto');
		$this->sessoes->setValidationLabel('valor_faturamento', 'Valor a ser Faturado');
		$this->sessoes->setValidationLabel('custo_operacional', 'Custo Operacional');
		$this->sessoes->setValidationLabel('custo_impostos', 'Impostos');
		$this->sessoes->setValidationLabel('local_evento', 'Local do Evento');
		$this->sessoes->setValidationLabel('id_depto_prestador_servico', 'Departamento prestador serviço');
		$this->sessoes->setValidationLabel('observacoes', 'Observações Sobre o Evento');
		$this->sessoes->setValidationLabel('id_profissional_alocado[]', 'Nome Profissional Alocado(a)');
		$this->sessoes->setValidationLabel('valor_pagamento_profissional[]', 'Valor a ser Pago ao Profissional');

		$this->sessoes->validate($rows) or exit(json_encode(['erro' => $this->sessoes->errors()]));

		$this->sessoes->skipValidation();

		if (count($data) > 1) {
			$this->sessoes->insertBatch($data) or exit(json_encode(['erro' => $this->sessoes->errors()]));
		} else {
			$this->sessoes->save($data[0]) or exit(json_encode(['erro' => $this->sessoes->errors()]));
		}

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function excluir()
	{
		$this->sessoes->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->sessoes->errors()]));

		echo json_encode(['status' => true]);
	}

	//==========================================================================
	public function pdf()
	{
		$this->load->library('m_pdf');

		$stylesheet = '#sessoes thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
		$stylesheet .= '#sessoes thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
		$stylesheet .= '#sessoes tbody td { font-size: 11px; padding: 5px; } ';
		$stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
		$stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->relatorio(true));

		$this->load->library('Calendar');

		$mes_ano = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

		$this->m_pdf->pdf->Output('Gestão Comercial - Sessões de Libras_' . $mes_ano . '.pdf', 'D');
	}

	//==========================================================================
	public function relatorio($isPdf = false)
	{
		$data = $this->db
			->select('foto, foto_descricao')
			->where('id', $this->session->userdata('empresa'))
			->get('usuarios')
			->row_array();

		$data['rows'] = $this->db
			->select('nome, contato_principal, telefone_contato_principal, email_contato_principal')
			->where('id_empresa', $this->session->userdata('empresa'))
			->order_by('nome', 'asc')
			->get('icom_sessoes')
			->result();

		$data['data'] = date('d/m/Y');

		$data['is_pdf'] = $isPdf === true;

		if ($data['is_pdf']) {
			return $this->load->view('icom/pdf_sessoes', $data, true);
		}

		$this->load->view('icom/relatorio_sessoes', $data);
	}


	public function solicitacaoFaturamento($isPdf = false)
	{
		$get = $this->input->get();

		$empresa = $this->db
			->select('foto, foto_descricao')
			->where('id', $this->session->userdata('empresa'))
			->get('usuarios')
			->row();


		$cliente = $this->db
			->select('a.*, b.id AS id_faturamento, b.mes_referencia, b.ano_referencia', false)
			->select('b.total_sessoes, b.valor_total AS valor_total', false)
			->select(["IFNULL(b.condicoes_pagamento, GROUP_CONCAT(DISTINCT e.condicoes_pagamento SEPARATOR ';')) AS condicoes_pagamento"], false)
			->select(["IFNULL(b.centro_custo, GROUP_CONCAT(DISTINCT e.centro_custo SEPARATOR ';')) AS centro_custo"], false)
			->join('icom_faturamento b', "b.id_cliente = a.id", 'left')
			->join('icom_propostas c', "c.id_cliente = a.id AND DATE_FORMAT(c.data_entrega, '%Y.%m') <= '{$get['ano']}-{$get['mes']}'", 'left')
			->join('icom_sessoes d', 'd.id_cliente = a.id AND MONTH(d.data_evento) = b.mes_referencia AND YEAR(d.data_evento) = b.ano_referencia', 'left')
			->join('icom_contratos e', 'e.codigo = d.codigo_contrato', 'left')
			->where('a.id', $get['cliente'])
			->group_by('a.id')
			->get('icom_clientes a')
			->row();


		$profissionais = $this->db
			->select(["DATE_FORMAT(a.data_evento, '%d/%m/%Y') AS data_evento"], false)
			->select('COUNT(a.id) AS qtde_sessoes, c.nome AS nome_cliente', false)
			->select(["FORMAT(SUM(b.preco * a.qtde_horas), 2, 'de_DE') AS valor_total"], false)
			->select(["SUM(b.preco * a.qtde_horas) AS valor_total_original"], false)
			->join('icom_produtos b', 'b.id = a.id_produto')
			->join('usuarios c', 'c.id = a.id_profissional_alocado')
			->where('a.id_cliente', $get['cliente'])
			->group_by(['a.data_evento', 'c.id'])
			->order_by('a.data_evento', 'asc')
			->order_by('c.nome', 'asc')
			->get('icom_sessoes a')
			->result();


		if (is_null($cliente->id_faturamento)) {
			$cliente->mes_referencia = $get['mes'];
			$cliente->ano_referencia = $get['ano'];
			$cliente->total_sessoes = array_sum(array_column($profissionais, 'qtde_sessoes'));
			$cliente->valor_total = array_sum(array_column($profissionais, 'valor_total_original'));
		}

		$this->load->library('calendar');

		$cliente->nome_mes_referencia = $this->calendar->get_month_name($get['mes']);

		$data = [
			'empresa' => $empresa,
			'cliente' => $cliente,
			'profissionais' => $profissionais,
			'is_pdf' => $isPdf === true,
			'data_atual' => date('d') . ' de ' . $this->calendar->get_month_name(date('m')) . ' de ' . date('Y'),
			'query_string' => http_build_query($get)
		];

		if ($isPdf === true) {
			return $this->load->view('icom/relatorio_solicitacao_faturamento', $data, true);
		}

		$this->load->view('icom/relatorio_solicitacao_faturamento', $data);
	}


	public function salvarFaturamento()
	{
		$this->load->library('entities');

		$data = $this->entities->create('icomFaturamento', $this->input->post());

		$this->faturamento->save($data) or exit(json_encode(['erro' => $this->faturamento->errors()]));

		echo json_encode(['status' => true]);
	}


	public function imprimirSolicitacaoFaturamento()
	{
		$this->load->library('m_pdf');

		$stylesheet = '#faturamento thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: bold; } ';
		$stylesheet .= '#faturamento thead tr { border-width: 5px; border-color: #ddd; } ';
		$stylesheet .= '#faturamento tbody td { font-size: 11px; padding: 5px; } ';
		$stylesheet .= '.table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
		$stylesheet .= '.table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

		$this->m_pdf->pdf->setTopMargin(74);
		$this->m_pdf->pdf->AddPage('P');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->solicitacaoFaturamento(true));

		$this->load->library('Calendar');

		$mes_ano = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

		$this->m_pdf->pdf->Output('Solicitação de Faturamento - Sessões de Libras_' . $mes_ano . '.pdf', 'D');
	}


	public function solicitacaoPagamento($isPdf = false)
	{
		$get = $this->input->get();

		$empresa = $this->db
			->select('foto, foto_descricao')
			->where('id', $this->session->userdata('empresa'))
			->get('usuarios')
			->row();


		$profissional = $this->db
			->select('f.nome AS nome_cliente, c.centro_custo, b.nome AS nome_depto_prestador_servico', false)
			->select('c.nome AS nome_profissional_alocado, c.cnpj, c.id', false)
			->select('c.nome_banco, c.agencia_bancaria, c.conta_bancaria', false)
			->select('e.id AS id_pagamento, e.mes_referencia, e.ano_referencia, e.valor_total, e.nota_fiscal, e.assinatura', false)
			->join('empresa_departamentos b', 'b.id = a.id_depto_prestador_servico')
			->join('usuarios c', 'c.id = a.id_profissional_alocado')
			->join('icom_clientes d', 'd.id = a.id_cliente')
			->join('icom_pagamento e', "e.id_profissional_alocado = c.id AND e.mes_referencia ='{$get['mes']}' AND e.ano_referencia ='{$get['ano']}'", 'left')
			->join('usuarios f', "(f.id_depto = 9 OR f.depto = 'Libras') AND f.tipo = 'funcionario' AND f.nivel_acesso = 9", 'left')
			->where('c.id', $get['profissional'])
			->where('MONTH(a.data_evento)', $get['mes'])
			->where('YEAR(a.data_evento)', $get['ano'])
			->get('icom_sessoes a')
			->row();


		$clientes = $this->db
			->select(["DATE_FORMAT(a.data_evento, '%d/%m/%Y') AS data_evento"], false)
			->select('b.nome AS nome_cliente, COUNT(a.id) AS qtde_sessoes', false)
			->select(["FORMAT(a.qtde_horas, 2, 'de_DE') AS qtde_horas"], false)
			->select(["FORMAT(c.preco, 2, 'de_DE') AS valor_unitario"], false)
			->select(["FORMAT(c.preco * a.qtde_horas, 2, 'de_DE') AS valor_total"], false)
			->select(["SUM(c.preco * a.qtde_horas) AS valor_total_original"], false)
			->join('icom_clientes b', 'b.id = a.id_cliente')
			->join('icom_produtos c', 'c.id = a.id_produto')
			->where('a.id_profissional_alocado', $get['profissional'])
			->where('MONTH(a.data_evento)', $get['mes'])
			->where('YEAR(a.data_evento)', $get['ano'])
			->group_by(['a.data_evento', 'b.id'])
			->order_by('a.data_evento', 'asc')
			->order_by('b.nome', 'asc')
			->get('icom_sessoes a')
			->result();

		if (is_null($profissional->id_pagamento)) {
			$profissional->mes_referencia = $get['mes'];
			$profissional->ano_referencia = $get['ano'];
			$profissional->total_sessoes = array_sum(array_column($clientes, 'qtde_sessoes'));
			$profissional->valor_total = array_sum(array_column($clientes, 'valor_total_original'));
		}

		$this->load->library('calendar');

		$profissional->nome_mes_referencia = $this->calendar->get_month_name($get['mes']);

		$data = [
			'empresa' => $empresa,
			'profissional' => $profissional,
			'clientes' => $clientes,
			'is_pdf' => $isPdf === true,
			'data_atual' => date('d') . ' de ' . $this->calendar->get_month_name(date('m')) . ' de ' . date('Y'),
			'query_string' => http_build_query($get)
		];

		if ($isPdf === true) {
			return $this->load->view('icom/relatorio_solicitacao_pagamento', $data, true);
		}

		$this->load->view('icom/relatorio_solicitacao_pagamento', $data);
	}


	public function salvarPagamento()
	{
		$this->load->library('entities');

		$data = $this->entities->create('icomPagamento', $this->input->post());

		$this->pagamento->setValidationLabel('data_emissao', 'Data Emissão');
		$this->pagamento->setValidationLabel('nota_fiscal', 'N&ordm; Nota Fiscal');
		$this->pagamento->setValidationLabel('tipo_pagamento', 'Tipo Pagamento');
		$this->pagamento->setValidationLabel('valor_total', 'Valor Total');
		$this->pagamento->setValidationLabel('assinatura', 'Assinatura');

		$this->pagamento->save($data) or exit(json_encode(['erro' => $this->pagamento->errors()]));

		echo json_encode(['status' => true]);
	}


	public function imprimirSolicitacaoPagamento()
	{
		$this->load->library('m_pdf');

		$stylesheet = '#pagamento thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: bold; } ';
		$stylesheet .= '#pagamento thead tr { border-width: 5px; border-color: #ddd; } ';
		$stylesheet .= '#pagamento tbody td { font-size: 11px; padding: 5px; } ';
		$stylesheet .= '.table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
		$stylesheet .= '.table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

		$this->m_pdf->pdf->setTopMargin(50);
		$this->m_pdf->pdf->AddPage('P');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->solicitacaoPagamento(true));

		$this->load->library('Calendar');

		$mes_ano = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

		$this->m_pdf->pdf->Output('Solicitação de Pagamento - Sessões de Libras_' . $mes_ano . '.pdf', 'D');
	}

}
