<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();
	}


	//==========================================================================


	private function getIdMes($mes, $semestre): int
	{
		return intval($mes) - (intval($semestre) > 1 ? 6 : 0);
	}


	//==========================================================================


	public function index()
	{
		// monta campo de meses
		$data = [
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

		// monta o mes atual
		$data['mes'] = $data['meses'][date('m')];
		// monta o semestre atual
		$data['semestre'] = array_slice(array_values($data['meses']), intval(date('n')) > 6 ? 6 : 0, 7);
		if (!isset($data['semestre'][6])) {
			$data['semestre'][6] = 'Jul';
		}

		// prepara a busca dos campos de filtragem
		$where = [
			'empresa' => $this->session->userdata('empresa'),
			'ano' => date('Y'),
			'semestre' => (date('n') / 6)
		];

		//monta o campo de departamento
		$data['depto'] = $this->getDeptos($where);

		// monta o campo de diretoria
		$data['diretoria'] = ['' => 'Todas'] + $this->getDiretorias($where);

		// monta o campo de supervisor
		$data['supervisor'] = ['' => 'Todos'] + $this->getSupervisores($where);

		// monta o campo de supervisor visitante (desnecessário)
		$data['supervisorVisitante'] = ['' => 'selecione...'] + $this->getVisitantes($where);

		// monta o departamento atual
		$data['depto_atual'] = count($data['depto']) > 0 ? '' : 'Educação Inclusiva';

		// monta a diretoria atual
		$data['diretoria_atual'] = '';

		// monta o supervisor atual
		if (in_array($this->session->userdata('nivel'), [9, 10])) {
			$data['supervisor_atual'] = $data['supervisor'][$this->session->userdata('id')] ?? '';
		} else {
			$data['supervisor_atual'] = '';
		}

		// monta a view
		$this->load->view('ei/apontamento', $data);
	}


	//==========================================================================


	private function getDeptos(array $where = [])
	{
		if ($this->session->userdata('nivel') == 10) {
			$supervisor = $this->session->userdata('id');
		} else {
			$supervisor = '';
		}


		$sql = "SELECT a.depto 
                FROM ei_diretorias a
                LEFT JOIN ei_escolas b ON b.id_diretoria = a.id
                LEFT JOIN ei_supervisores c ON c.id_escola = b.id
                LEFT JOIN ei_coordenacao d ON d.id = c.id_coordenacao
                WHERE a.id_empresa = '{$where['empresa']}'
                      AND a.depto = 'Educação Inclusiva'
                      AND (d.id_usuario = '{$supervisor}' OR '{$supervisor}' = '')
                UNION
                SELECT 'Educação Inclusiva' AS depto
                UNION
                SELECT depto 
                FROM ei_alocacao 
                WHERE id_empresa = '{$where['empresa']}' 
                       AND depto = 'Educação Inclusiva'
                       AND (id_supervisor = '{$supervisor}' OR '{$supervisor}' = '')
                       AND ano = '{$where['ano']}' AND semestre = '{$where['semestre']}'
                ORDER BY depto ASC";


		$rows = $this->db->query($sql)->result();


		return array_column($rows, 'depto', 'depto');
	}


	//==========================================================================


	private function getDiretorias(array $where = [])
	{
		$depto = $where['depto'] ?? 'Educação Inclusiva';


		if ($this->session->userdata('nivel') == 10) {
			$supervisor = $this->session->userdata('id');
		} else {
			$supervisor = '';
		}


		$sql = "SELECT a.id, a.nome AS diretoria 
                FROM ei_diretorias a
                LEFT JOIN ei_escolas b ON b.id_diretoria = a.id
                LEFT JOIN ei_supervisores c ON c.id_escola = b.id
                LEFT JOIN ei_coordenacao d ON d.id = c.id_coordenacao
                WHERE a.id_empresa = '{$where['empresa']}' 
                      AND a.depto = '{$depto}'
                      AND (d.id_usuario = '{$supervisor}' OR '{$supervisor}' = '')
                UNION
                SELECT id_diretoria AS id, diretoria 
                FROM ei_alocacao 
                WHERE id_empresa = '{$where['empresa']}' 
                      AND depto = '{$depto}'
                      AND (id_supervisor = '{$supervisor}' OR '{$supervisor}' = '')
                      AND ano = '{$where['ano']}' 
                      AND semestre = '{$where['semestre']}'
                ORDER BY diretoria ASC";


		$rows = $this->db->query($sql)->result();


		return array_column($rows, 'diretoria', 'id');
	}


	//==========================================================================


	private function getSupervisores(array $where = [])
	{
		$depto = $where['depto'] ?? 'Educação Inclusiva';


		$diretoria = $where['diretoria'] ?? '';


		if ($this->session->userdata('nivel') == 10) {
			$supervisor = $this->session->userdata('id');
		} else {
			$supervisor = '';
		}


		$sql = "SELECT a.id AS id, a.nome AS supervisor 
                FROM usuarios a 
                INNER JOIN ei_coordenacao b ON b.id_usuario = a.id
                INNER JOIN ei_supervisores c ON c.id_coordenacao = b.id
                INNER JOIN ei_escolas d ON d.id = c.id_escola
                INNER JOIN ei_diretorias e ON e.id = d.id_diretoria
                WHERE e.id_empresa = '{$where['empresa']}' 
                      AND e.depto = '{$depto}'
                      AND (e.id = '{$diretoria}' OR '{$diretoria}' = '')
                      AND (a.id = '{$supervisor}' OR '{$supervisor}' = '')
                UNION
                SELECT id_supervisor AS id, supervisor 
                FROM ei_alocacao 
                WHERE id_empresa = '{$where['empresa']}' 
                      AND depto = '{$depto}'
                      AND (id_diretoria = '{$diretoria}' OR '{$diretoria}' = '')
                      AND (id_supervisor = '{$supervisor}' OR '{$supervisor}' = '')
                      AND ano = '{$where['ano']}' 
                      AND semestre = '{$where['semestre']}'
                ORDER BY supervisor ASC";


		$rows = $this->db->query($sql)->result();


		return array_column($rows, 'supervisor', 'id');
	}


	//==========================================================================


	private function getVisitantes(array $where = [])
	{
		$depto = $where['depto'] ?? 'Educação Inclusiva';


		$diretoria = $where['diretoria'] ?? '';


		if ($this->session->userdata('nivel') == 10) {
			$supervisor = $this->session->userdata('id');
		} else {
			$supervisor = $where['supervisor'] ?? '';
		}


		$sql = "SELECT a.id AS id, a.nome AS supervisor_visitante
                FROM usuarios a 
                INNER JOIN ei_coordenacao b ON b.id_usuario = a.id
                INNER JOIN ei_supervisores c ON c.id_coordenacao = b.id
                INNER JOIN ei_escolas d ON d.id = c.id_escola
                INNER JOIN ei_diretorias e ON e.id = d.id_diretoria
                WHERE e.id_empresa = '{$where['empresa']}' 
                      AND e.depto = '{$depto}'
                      AND (e.id = '{$diretoria}' OR '{$diretoria}' = '')
                      AND (a.id = '{$supervisor}' OR '{$supervisor}' = '')
                UNION
                SELECT DISTINCT(c.id_supervisor_visitante) AS id, c.supervisor_visitante
                FROM ei_alocacao a
                INNER JOIN ei_mapa_unidades b ON b.id_alocacao = a.id
                INNER JOIN ei_mapa_visitacao c ON c.id_mapa_unidade = b.id
                WHERE a.id_empresa = '{$where['empresa']}' 
                      AND a.depto = '{$depto}'
                      AND (a.id_diretoria = '{$diretoria}' OR '{$diretoria}' = '')
                      AND (a.id_supervisor = '{$supervisor}' OR '{$supervisor}' = '')
                      AND a.ano = '{$where['ano']}' 
                      AND a.semestre = '{$where['semestre']}'
                ORDER BY supervisor_visitante ASC";


		$rows = $this->db->query($sql)->result();


		return array_column($rows, 'supervisor_visitante', 'id');
	}


	//==========================================================================


	public function atualizarFiltro()
	{
		$diretoria = $this->input->post('diretoria');


		$supervisor = $this->input->post('supervisor');


		$where = [
			'empresa' => $this->session->userdata('empresa'),
			'depto' => $this->input->post('depto'),
			'diretoria' => $diretoria,
			'ano' => $this->input->post('ano'),
			'semestre' => $this->input->post('semestre')
		];


		$filtro['diretoria'] = ['' => 'Todas'] + $this->getDiretorias($where);


		$filtro['supervisor'] = ['' => 'Todos'] + $this->getSupervisores($where);


		if (isset($filtro['supervisor'][$supervisor])) {
			$where['supervisor'] = $supervisor;
			$filtro['supervisor_visitante'] = $this->getVisitantes($where);
		} else {
			$filtro['supervisor_visitante'] = ['' => 'selecione...'] + $this->getVisitantes($where);
		}


		$data['diretoria'] = form_dropdown('diretoria', $filtro['diretoria'], $diretoria, 'onchange="atualizarFiltro()" class="form-control input-sm"');


		$data['supervisor'] = form_dropdown('supervisor', $filtro['supervisor'], $supervisor, 'class="form-control input-sm"');


		$data['supervisor_visitante'] = form_dropdown('supervisor_visitante', $filtro['supervisor_visitante'], $supervisor, 'class="form-control"');


		echo json_encode($data);
	}


	//==========================================================================


	public function atualizarFiltrosVisitas()
	{
		$id = $this->input->post('id');


		$this->db->select('cliente, municipio, unidade_visitada');
		$this->db->where('id', $id);
		$row = $this->db->get('ei_mapa_visitacao')->row();


		if ($row) {
			$cliente = $row->cliente;
			$municipio = $row->municipio;
			$unidadeVisitada = $row->unidade_visitada;
		} else {
			$cliente = $this->input->post('cliente');
			$municipio = $this->input->post('municipio');
			$unidadeVisitada = $this->input->post('unidade_visitada');
		}


		$this->db->where('id', $unidadeVisitada);
		$escola = $this->db->get('ei_escolas')->row();


		$busca = [
			'cliente' => $cliente,
			'municipio' => $municipio,
			'unidade_visitada' => $unidadeVisitada,
			'escola' => ($escola->nome ?? ''),
			'mes' => $this->input->post('mes'),
			'ano' => $this->input->post('ano')
		];


		$filtro = $this->montarFiltrosVisita($busca);


		$data['prestadores_servicos_tratados'] = $filtro['prestadores_servicos_tratados'];


		$data['cliente'] = form_dropdown('cliente', $filtro['clientes'], $cliente, 'onchange="atualizarFiltrosVisitas()" class="form-control"');


		$data['municipio'] = form_dropdown('municipio', $filtro['municipios'], $municipio, 'onchange="atualizarFiltrosVisitas()" class="form-control"');


		$data['unidade_visitada'] = form_dropdown('unidade_visitada', $filtro['unidades_visitadas'], $unidadeVisitada, 'onchange="atualizarFiltrosVisitas()" class="form-control"');


		echo json_encode($data);
	}


	//==========================================================================


	public function prepararOS()
	{
		$where = $this->input->post();
		unset($where['mes']);


		$this->db->select('a.id, a.nome');
		$this->db->join('ei_contratos b', 'b.id = a.id_contrato');
		$this->db->join('ei_diretorias c', 'c.id = b.id_cliente');
		$this->db->where('c.id', $where['diretoria']);
		$this->db->where('c.depto', $where['depto']);
		$this->db->where('a.ano', $where['ano']);
		$this->db->where('a.semestre', $where['semestre']);
		$totalOS = $this->db->get('ei_ordem_servico a')->num_rows();


		if (empty($totalOS)) {
			exit(json_encode(['erro' => 'Nenhuma Ordem de Serviço disponível para alocação.']));
		}


		$alocacao = $this->db->get_where('ei_alocacao', $where)->row();


		$idAlocacao = $alocacao->id ?? null;


		$sql = "SELECT a.id, a.nome
                FROM ei_ordem_servico a
                INNER JOIN ei_contratos b ON 
                           b.id = a.id_contrato
                INNER JOIN ei_diretorias c ON 
                           c.id = b.id_cliente
                INNER JOIN ei_escolas d ON 
                           d.id_diretoria = c.id
                INNER JOIN ei_supervisores e ON 
                           e.id_escola = d.id
                LEFT JOIN ei_coordenacao f ON 
                           f.id = e.id_coordenacao AND 
                           f.ano = a.ano AND 
                           f.semestre = a.semestre
                LEFT JOIN ei_ordem_servico_escolas g ON 
                          g.id_ordem_servico = a.id AND 
                          g.id_escola = d.id
                LEFT JOIN ei_ordem_servico_profissionais h ON 
                          h.id_ordem_servico_escola = g.id
                LEFT JOIN ei_ordem_servico_horarios i ON 
                          i.id_os_profissional = h.id
                LEFT JOIN ei_alocados_horarios j ON 
                          j.id_os_horario = i.id
                LEFT JOIN ei_alocados k ON 
                          k.id = j.id_alocado
                LEFT JOIN ei_alocacao_escolas l ON 
                          l.id = k.id_alocacao_escola
                LEFT JOIN ei_alocacao m ON 
                          m.id = l.id_alocacao AND 
                          m.id = '{$idAlocacao}'
                WHERE c.id = '{$where['diretoria']}'
                      AND c.depto =  '{$where['depto']}'
                      AND a.ano =  '{$where['ano']}'
                      AND a.semestre =  '{$where['semestre']}'
                      AND j.id IS NULL
                GROUP BY a.id
                ORDER BY a.nome asc";


		$os = $this->db->query($sql)->result();


		if (empty($os)) {
			exit(json_encode(['erro' => 'Este semestre já está alocado.']));
		}


		$ordem_servico = ['' => 'Todas'] + array_column($os, 'nome', 'id');


		$escolas = $this->filtrarOSEscola($where);
		$data['ordem_servico'] = form_dropdown('', $ordem_servico, '');


		$data['escolas'] = form_multiselect('', $escolas, '');


		echo json_encode($data);
	}


	//==========================================================================


	private function filtrarOSEscola($where)
	{
		$this->db->select(["c.id, CONCAT_WS(' - ', c.codigo, c.nome) AS nome"], false);
		$this->db->join('ei_supervisores b', 'b.id_coordenacao = a.id');
		$this->db->join('ei_escolas c', 'c.id = b.id_escola');
		$this->db->join('ei_diretorias d', 'd.id = c.id_diretoria');
		$this->db->join('ei_ordem_servico_escolas e', 'e.id_escola = c.id', 'left');
		$this->db->join('ei_ordem_servico f', 'f.id = e.id_ordem_servico and f.ano = a.ano AND f.semestre = a.semestre', 'left');
		$this->db->where('d.depto', $where['depto']);
		$this->db->where('d.id', $where['diretoria']);
		if (!empty($where['ordem_servico'])) {
			$this->db->where('f.id', $where['ordem_servico']);
		} else {
			$this->db->where('a.ano', $where['ano']);
			$this->db->where('a.semestre', $where['semestre']);
		}
		$this->db->group_by(['c.id', 'c.codigo', 'c.nome']);
		$this->db->order_by('IF(CHAR_LENGTH(c.codigo) > 0, c.codigo, CAST(c.nome AS DECIMAL))', 'asc', false);
		$osEscolas = $this->db->get('ei_coordenacao a')->result();


		return array_column($osEscolas, 'nome', 'id');
	}


	//==========================================================================


	public function filtrarOSEscolas()
	{
		$where = $this->input->post();


		$escolas = $this->filtrarOSEscola($where);


		$data['escolas'] = form_multiselect('', $escolas, '');


		echo json_encode($data);
	}


	//==========================================================================


	public function iniciarSemestre()
	{
		$empresa = $this->session->userdata('empresa');


		$departamento = $this->input->post('depto');


		$idDiretoria = $this->input->post('diretoria');


		$idSupervisor = $this->input->post('supervisor');


		$ano = $this->input->post('ano');


		$mes = intval($this->input->post('mes'));


		$semestre = $this->input->post('semestre');
		if (empty($semestre)) {
			$semestre = $mes > 7 ? '2' : '1';
		}

		$idMes = $mes - ($semestre > 1 ? 6 : 0);


		$iniciarMapaVisitacao = $this->input->post('possui_mapa_visitacao');


		$ordemServico = $this->input->post('ordem_servico');


		$escolas = $this->input->post('escolas');


		$this->db->where('id_empresa', $empresa);
		$this->db->where('depto', $departamento);
		$this->db->where('id_diretoria', $idDiretoria);
		$this->db->where('id_supervisor', $idSupervisor);
		$this->db->where('ano', $ano);
		$this->db->where('semestre', $semestre);
		$alocacao = $this->db->get('ei_alocacao')->row();


		$this->db->trans_begin();


		if (isset($alocacao->id)) {
			$idAlocacao = $alocacao->id;
		} else {
			$this->db->select('nome, municipio, id_coordenador');
			$this->db->where('id_empresa', $empresa);
			$this->db->where('id', $idDiretoria);
			$this->db->where('depto', $departamento);
			$diretoria = $this->db->get('ei_diretorias')->row();


			$this->db->select('nome');
			$this->db->where('id', $idSupervisor);
			$supervisor = $this->db->get('usuarios')->row();


			$data = [
				'id_empresa' => $empresa,
				'depto' => $departamento,
				'id_diretoria' => $idDiretoria,
				'diretoria' => $diretoria->nome,
				'id_supervisor' => $idSupervisor,
				'supervisor' => $supervisor->nome,
				'municipio' => $diretoria->municipio,
				'coordenador' => $diretoria->id_coordenador,
				'ano' => $ano,
				'semestre' => $semestre
			];


			$this->db->insert('ei_alocacao', $data);


			$idAlocacao = $this->db->insert_id();
		}


		$this->db->select("'{$idAlocacao}' AS id_alocacao, a.id AS id_os_escola, b.id AS id_escola", false);
		$this->db->select('b.codigo, b.nome AS escola, b.municipio, c.nome AS ordem_servico, d.contrato', false);
		$this->db->join('ei_escolas b', 'b.id = a.id_escola');
		$this->db->join('ei_ordem_servico c', 'c.id = a.id_ordem_servico');
		$this->db->join('ei_contratos d', 'd.id = c.id_contrato');
		$this->db->join('ei_diretorias e', 'e.id = d.id_cliente');
		$this->db->join('ei_supervisores f', 'f.id_escola = b.id');
		$this->db->join('ei_coordenacao g', 'g.id = f.id_coordenacao AND g.ano = c.ano AND g.semestre = c.semestre');
		$this->db->join('ei_funcoes_supervisionadas h', 'h.id_supervisor = g.id');
		$this->db->join('ei_ordem_servico_profissionais i', 'i.id_ordem_servico_escola = a.id');
		$this->db->join('ei_ordem_servico_horarios j', 'j.id_os_profissional = i.id', 'left');
		$this->db->where('e.id_empresa', $empresa);
		$this->db->where('e.depto', $departamento);
		$this->db->where('e.id', $idDiretoria);
		$this->db->where('g.id_usuario', $idSupervisor);
		$this->db->where('c.ano', $ano);
		$this->db->where('c.semestre', $semestre);
		$this->db->where('(j.id_funcao = h.funcao OR j.id_funcao IS NULL)', null, false);
		if ($ordemServico) {
			$this->db->where('c.id', $ordemServico);
		}
		if ($escolas) {
			$this->db->where_in('b.id', $escolas);
		}
		$this->db->group_by('a.id');
		$this->db->order_by('b.nome', 'asc');
		$alocacaoEscolas = $this->db->get('ei_ordem_servico_escolas a')->result_array();


		if (!$alocacaoEscolas) {
			$this->db->trans_rollback();
			exit(json_encode(['erro' => 'Nenhuma escola encontrada.']));
		}


		$this->db->insert_batch('ei_alocacao_escolas', $alocacaoEscolas);


		$this->db->select('d.id AS id_alocacao_escola, a.id AS id_os_profissional, a.id_usuario AS id_cuidador, b.nome AS cuidador', false);
		$this->db->select('a.valor_hora_operacional, a.horas_mensais_custo, a.data_inicio_contrato, a.data_termino_contrato', false);
		$this->db->select(["ROUND((TIME_TO_SEC(a.horas_mensais_custo) / 3600) * a.valor_hora_operacional, 2) AS valor_total"], false);
		$this->db->join('usuarios b', 'b.id = a.id_usuario');
		$this->db->join('ei_ordem_servico_escolas c', 'c.id = a.id_ordem_servico_escola');
		$this->db->join('ei_alocacao_escolas d', 'd.id_os_escola = c.id');
		$this->db->join('ei_alocacao e', 'e.id = d.id_alocacao');
		$this->db->join('ei_supervisores f', 'f.id_escola = d.id_escola');
		$this->db->join('ei_coordenacao g', 'g.id = f.id_coordenacao AND g.id_usuario = e.id_supervisor AND g.ano = e.ano AND g.semestre = e.semestre');
		$this->db->join('ei_funcoes_supervisionadas h', 'h.id_supervisor = g.id');
		$this->db->join('ei_ordem_servico_horarios i', 'i.id_os_profissional = a.id', 'left');
		$this->db->where('d.id_alocacao', $idAlocacao);
		$this->db->where_in('c.id', array_column($alocacaoEscolas, 'id_os_escola'));
		$this->db->where("(a.id_supervisor = {$idSupervisor} OR a.id_supervisor IS NULL)", null, false);
		$this->db->where('(i.id_funcao = h.funcao OR i.id_funcao IS NULL)', null, false);
		$this->db->group_by('a.id');
		$cuidadores = $this->db->get('ei_ordem_servico_profissionais a')->result_array();


		if (!$cuidadores) {
			$this->db->trans_rollback();
			exit(json_encode(['erro' => 'Nenhum cuidador encontrado.']));
		}


		$this->db->insert_batch('ei_alocados', $cuidadores);


		if ($iniciarMapaVisitacao === '1') {
			$this->db->select('a.id_alocacao, a.id_escola, a.escola, a.municipio');
			$this->db->join('ei_alocacao b', 'b.id = a.id_alocacao');
			$this->db->join('ei_mapa_unidades c', 'c.id_alocacao = b.id AND c.id_escola = a.id_escola', 'left');
			$this->db->where('b.id', $idAlocacao);
			$this->db->where('c.id', null);
			$this->db->group_by(['a.id_escola']);
			$mapaVisitacao = $this->db->get('ei_alocacao_escolas a')->result_array();


			if ($mapaVisitacao) {
				$this->db->insert_batch('ei_mapa_unidades', $mapaVisitacao);
			}
		}


		$this->db->select('d.id AS id_alocacao_escola, a.id AS id_os_aluno, a.id_aluno, b.nome AS aluno', false);
		$this->db->select('b.status, b.hipotese_diagnostica, a.modulo, a.data_inicio, a.data_termino', false);
		$this->db->select('a.id_aluno_curso, a2.id_curso, a3.nome AS curso', false);
		$this->db->join('ei_alunos b', 'b.id = a.id_aluno');
		$this->db->join('ei_ordem_servico_escolas c', 'c.id = a.id_ordem_servico_escola');
		$this->db->join('ei_alunos_cursos a2', 'a2.id = a.id_aluno_curso AND a2.id_aluno = b.id');
		$this->db->join('ei_cursos a3', 'a3.id = a2.id_curso');
		$this->db->join('ei_alocacao_escolas d', 'd.id_os_escola = c.id');
		$this->db->join('ei_alocacao e', 'e.id = d.id_alocacao');
		$this->db->join('ei_supervisores f', 'f.id_escola = d.id_escola');
		$this->db->join('ei_coordenacao g', 'g.id = f.id_coordenacao AND g.ano = e.ano AND g.semestre = e.semestre');
		$this->db->join('ei_funcoes_supervisionadas h', 'h.id_supervisor = g.id');
		$this->db->join('ei_ordem_servico_profissionais i', 'i.id_ordem_servico_escola = c.id');
		$this->db->join('ei_ordem_servico_horarios j', 'j.id_os_profissional = i.id', 'left');
		$this->db->where('d.id_alocacao', $idAlocacao);
		$this->db->where_in('c.id', array_column($alocacaoEscolas, 'id_os_escola'));
		$this->db->where('(j.id_funcao = h.funcao OR j.id_funcao IS NULL)', null, false);
		$this->db->group_by('a.id');
		$alunos = $this->db->get('ei_ordem_servico_alunos a')->result_array();


		if ($alunos) {
			$this->db->insert_batch('ei_matriculados', $alunos);
		}


		$mes1 = $semestre > 1 ? '07' : '01';
		$mes2 = $semestre > 1 ? '08' : '02';
		$mes3 = $semestre > 1 ? '09' : '03';
		$mes4 = $semestre > 1 ? '10' : '04';
		$mes5 = $semestre > 1 ? '11' : '05';
		$mes6 = $semestre > 1 ? '12' : '06';
		if ($semestre === '1') {
			$mes7 = '07';
		}


		$diaIniMes1 = date('Y-m-d', strtotime("{$ano}-{$mes1}-01"));
		$diaIniMes2 = date('Y-m-d', strtotime("{$ano}-{$mes2}-01"));
		$diaIniMes3 = date('Y-m-d', strtotime("{$ano}-{$mes3}-01"));
		$diaIniMes4 = date('Y-m-d', strtotime("{$ano}-{$mes4}-01"));
		$diaIniMes5 = date('Y-m-d', strtotime("{$ano}-{$mes5}-01"));
		$diaIniMes6 = date('Y-m-d', strtotime("{$ano}-{$mes6}-01"));
		if ($semestre === '1') {
			$diaIniMes7 = date('Y-m-d', strtotime("{$ano}-{$mes7}-01"));
		}


		$diaFimMes1 = date('Y-m-t', strtotime($diaIniMes1));
		$diaFimMes2 = date('Y-m-t', strtotime($diaIniMes2));
		$diaFimMes3 = date('Y-m-t', strtotime($diaIniMes3));
		$diaFimMes4 = date('Y-m-t', strtotime($diaIniMes4));
		$diaFimMes5 = date('Y-m-t', strtotime($diaIniMes5));
		$diaFimMes6 = date('Y-m-t', strtotime($diaIniMes6));
		if ($semestre === '1') {
			$diaFimMes7 = date('Y-m-t', strtotime($diaIniMes7));
		}


		$this->db->select('c.id AS id_alocado, a.id AS id_os_horario, f.nome AS cargo, e.nome AS funcao');
		$this->db->select('a.dia_semana, a.periodo');
		$this->db->select("(CASE WHEN {$mes1} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_inicio END) AS horario_inicio_mes1", false);
		$this->db->select("(CASE WHEN {$mes2} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_inicio END) AS horario_inicio_mes2", false);
		$this->db->select("(CASE WHEN {$mes3} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_inicio END) AS horario_inicio_mes3", false);
		$this->db->select("(CASE WHEN {$mes4} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_inicio END) AS horario_inicio_mes4", false);
		$this->db->select("(CASE WHEN {$mes5} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_inicio END) AS horario_inicio_mes5", false);
		$this->db->select("(CASE WHEN {$mes6} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_inicio END) AS horario_inicio_mes6", false);
		if ($semestre === '1') {
			$this->db->select("(CASE WHEN {$mes7} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_inicio END) AS horario_inicio_mes7", false);
		}
		$this->db->select("(CASE WHEN {$mes1} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_termino END) AS horario_termino_mes1", false);
		$this->db->select("(CASE WHEN {$mes2} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_termino END) AS horario_termino_mes2", false);
		$this->db->select("(CASE WHEN {$mes3} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_termino END) AS horario_termino_mes3", false);
		$this->db->select("(CASE WHEN {$mes4} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_termino END) AS horario_termino_mes4", false);
		$this->db->select("(CASE WHEN {$mes5} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_termino END) AS horario_termino_mes5", false);
		$this->db->select("(CASE WHEN {$mes6} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_termino END) AS horario_termino_mes6", false);
		if ($semestre === '1') {
			$this->db->select("(CASE WHEN {$mes7} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN a.horario_termino END) AS horario_termino_mes7", false);
		}
		$this->db->select("(CASE WHEN {$mes1} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes1", false);
		$this->db->select("(CASE WHEN {$mes2} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes2", false);
		$this->db->select("(CASE WHEN {$mes3} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes3", false);
		$this->db->select("(CASE WHEN {$mes4} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes4", false);
		$this->db->select("(CASE WHEN {$mes5} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes5", false);
		$this->db->select("(CASE WHEN {$mes6} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes6", false);
		if ($semestre === '1') {
			$this->db->select("(CASE WHEN {$mes7} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes7", false);
		}
		$this->db->select(['a.data_inicio_contrato, a.data_termino_contrato, a.valor_hora_operacional, a.horas_mensais_custo, l.valor AS valor_hora_funcao'], false);
		$this->db->select(['IF(a.valor_hora_operacional > 0, a.valor_hora_operacional, l.valor_pagamento) AS valor_hora_operacional'], false);
		$this->db->select(["IF({$mes1} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes1}, MAX(h.data_termino), '{$diaFimMes1}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes1}, MAX(h.data_termino), '$diaFimMes1'), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes1}, MIN(h.data_inicio), '{$diaIniMes1}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes1}, MIN(h.data_inicio), '{$diaIniMes1}'), '%w')) + dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes1"], false);
		$this->db->select(["IF({$mes2} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes2}, MAX(h.data_termino), '{$diaFimMes2}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes2}, MAX(h.data_termino), '$diaFimMes2'), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes2}, MIN(h.data_inicio), '{$diaIniMes2}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes2}, MIN(h.data_inicio), '{$diaIniMes2}'), '%w')) + dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes2"], false);
		$this->db->select(["IF({$mes3} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes3}, MAX(h.data_termino), '{$diaFimMes3}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes3}, MAX(h.data_termino), '$diaFimMes3'), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes3}, MIN(h.data_inicio), '{$diaIniMes3}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes3}, MIN(h.data_inicio), '{$diaIniMes3}'), '%w')) + dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes3"], false);
		$this->db->select(["IF({$mes4} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes4}, MAX(h.data_termino), '{$diaFimMes4}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes4}, MAX(h.data_termino), '$diaFimMes4'), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes4}, MIN(h.data_inicio), '{$diaIniMes4}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes4}, MIN(h.data_inicio), '{$diaIniMes4}'), '%w')) + dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes4"], false);
		$this->db->select(["IF({$mes5} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes5}, MAX(h.data_termino), '{$diaFimMes5}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes5}, MAX(h.data_termino), '$diaFimMes5'), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes5}, MIN(h.data_inicio), '{$diaIniMes5}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes5}, MIN(h.data_inicio), '{$diaIniMes5}'), '%w')) + dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes5"], false);
		$this->db->select(["IF({$mes6} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes6}, MAX(h.data_termino), '{$diaFimMes6}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes6}, MAX(h.data_termino), '$diaFimMes6'), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes6}, MIN(h.data_inicio), '{$diaIniMes6}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes6}, MIN(h.data_inicio), '{$diaIniMes6}'), '%w')) + dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes6"], false);
		if ($semestre === '1') {
			$this->db->select(["IF({$mes7} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino)), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes7}, MAX(h.data_termino), '{$diaFimMes7}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes7}, MAX(h.data_termino), '$diaFimMes7'), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes7}, MIN(h.data_inicio), '{$diaIniMes7}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes7}, MIN(h.data_inicio), '{$diaIniMes7}'), '%w')) + dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes7"], false);
		}
		$this->db->join('ei_ordem_servico_profissionais b', 'b.id = a.id_os_profissional');
		$this->db->join('ei_alocados c', 'c.id_os_profissional = b.id');
		$this->db->join('ei_alocacao_escolas d', 'd.id = c.id_alocacao_escola');
		$this->db->join('ei_alocacao d2', 'd2.id = d.id_alocacao');
		$this->db->join('ei_supervisores m', 'm.id_escola = d.id_escola');
		$this->db->join('ei_coordenacao n', 'n.id = m.id_coordenacao AND n.id_usuario = d2.id_supervisor AND n.ano = d2.ano AND n.semestre = d2.semestre');
		$this->db->join('ei_funcoes_supervisionadas o', 'o.id_supervisor = n.id', 'left');
		$this->db->join('empresa_funcoes e', 'e.id = a.id_funcao', 'left');
		$this->db->join('empresa_cargos f', 'f.id = e.id_cargo', 'left');
		$this->db->join('ei_ordem_servico_turmas g', 'g.id_os_horario = a.id', 'left');
		$this->db->join('ei_ordem_servico_alunos h', 'h.id = g.id_os_aluno', 'left');
		$this->db->join('ei_ordem_servico_escolas i', 'i.id = b.id_ordem_servico_escola', 'left');
		$this->db->join('ei_ordem_servico j', 'j.id = i.id_ordem_servico', 'left');
		$this->db->join('ei_contratos k', 'k.id = j.id_contrato', 'left');
		$this->db->join('ei_valores_faturamento l', 'l.id_contrato = k.id AND l.ano = j.ano AND l.semestre = j.semestre AND l.id_cargo = f.id AND l.id_funcao = e.id', 'left');
		$this->db->where('d.id_alocacao', $idAlocacao);
		$this->db->where_in('d.id_os_escola', array_column($alocacaoEscolas, 'id_os_escola'));
		$this->db->where_in('b.id', array_column($cuidadores, 'id_os_profissional'));
		$this->db->where('(o.funcao = a.id_funcao OR a.id_funcao IS NULL)', null, false);
		$this->db->group_by('a.id');
		$horarios = $this->db->get('ei_ordem_servico_horarios a')->result_array();


		$this->db->insert_batch('ei_alocados_horarios', $horarios);


		$this->db->select('d.id AS id_matriculado, e.id AS id_alocado_horario');
		$this->db->join('ei_ordem_servico_alunos b', 'b.id = a.id_os_aluno');
		$this->db->join('ei_ordem_servico_horarios c', 'c.id = a.id_os_horario');
		$this->db->join('ei_matriculados d', 'd.id_os_aluno = b.id');
		$this->db->join('ei_alocados_horarios e', 'e.id_os_horario = c.id');
		$this->db->join('ei_alocados f', 'f.id = e.id_alocado');
		$this->db->join('ei_alocacao_escolas g', 'g.id = f.id_alocacao_escola');
		$this->db->where('g.id_alocacao', $idAlocacao);
		$this->db->where_in('g.id_os_escola', array_column($alocacaoEscolas, 'id_os_escola'));
		$this->db->where_in('f.id_os_profissional', array_column($cuidadores, 'id_os_profissional'));
		$this->db->where_in('d.id_os_aluno', array_column($alunos, 'id_os_aluno'));
		$this->db->where_in('e.id_os_horario', array_column($horarios, 'id_os_horario'));
		$turmas = $this->db->get('ei_ordem_servico_turmas a')->result_array();


		if ($turmas) {
			$this->db->insert_batch('ei_matriculados_turmas', $turmas);
		}


		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			exit(json_encode(['erro' => 'Erro ao iniciar semestre.']));
		}


		$this->db->trans_commit();


		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function montarOSRestantes()
	{
		$data = $this->input->post();
		$ano = $data['ano'];
		$mes = $data['mes'];
		$semestre = $data['semestre'] ?? '';

		if (empty($semestre)) {
			$semestre = intval($data['mes']) > 7 ? '2' : '1';
		}
		if (!empty($data['semestre_anterior'])) {
			$timestamp = mktime(0, 0, 0, (int)$mes, 1, (int)$ano);
			$dataSemestreAnterior = new DateTime(date('Y-m-d', $timestamp));
			if ($mes === '07' and $semestre === '1') {
				$dataSemestreAnterior->sub(new DateInterval('P7M'));
			} else {
				$dataSemestreAnterior->sub(new DateInterval('P6M'));
			}
			$mes = $dataSemestreAnterior->format('m');
			$ano = $dataSemestreAnterior->format('Y');
			$semestre = intval($mes) > 7 ? '2' : '1';
		}

		$data['ano_os'] = $ano;
		$data['semestre_os'] = $semestre;

		$ordemServico = $this->db
			->select('a.id, a.nome')
			->join('ei_contratos b', 'b.id = a.id_contrato')
			->join('ei_diretorias c', 'c.id = b.id_cliente')
			->join('ei_escolas d', 'd.id_diretoria = c.id')
			->join('ei_supervisores e', 'e.id_escola = d.id')
			->join('ei_coordenacao f', 'f.id = e.id_coordenacao')
			->where('c.id_empresa', $this->session->userdata('empresa'))
			->where('c.depto', $data['depto'])
			->where('c.id', $data['diretoria'])
			->where('f.id_usuario', $data['supervisor'])
			->where('a.ano', $ano)
			->where('a.semestre', $semestre)
			->group_by(['a.id', 'd.id'])
			->order_by('a.nome', 'ASC')
			->get('ei_ordem_servico a')->result();

		$os = ['' => 'selecione...'] + array_column($ordemServico, 'nome', 'id');

		$data['ordem_servico'] = form_dropdown('ordem_servico', $os, '', 'class="form-control"');
		$data['escola'] = form_dropdown('', ['' => 'selecione...'], '');
		$data['aluno'] = form_dropdown('', ['' => 'selecione...'], '');

		echo json_encode($data);
	}


	//==========================================================================


	public function montarEscolasRestantes()
	{
		$os = $this->input->post('ordem_servico');

		$subquery = $this->db
			->select('a.id')
			->select(["CONCAT_WS(' - ', b.codigo, b.nome) AS nome"], false)
			->select(["IF(CHAR_LENGTH(b.codigo) > 0, b.codigo, CAST(b.nome AS DECIMAL)) AS ordem"], false)
			->join('ei_escolas b', 'b.id = a.id_escola')
			->where('a.id_ordem_servico', $os)
			->group_by('b.id')
			->order_by('b.nome', 'ASC')
			->get_compiled_select('ei_ordem_servico_escolas a');

		$escolasNaoAlocadas = $this->db->from("({$subquery}) t")->order_by('t.ordem', 'asc')->get()->result();

		$escolas = ['' => 'selecione...'] + array_column($escolasNaoAlocadas, 'nome', 'id');

		$data['escola'] = form_dropdown('escola', $escolas, '', 'class="form-control"');
		$data['aluno'] = form_dropdown('', ['' => 'selecione...'], '');

		echo json_encode($data);
	}


	//==========================================================================


	public function montarAlunosRestantes()
	{
		$escola = $this->input->post('escola');

		$alunosNaoAlocados = $this->db
			->select('a.id, b.nome')
			->join('ei_alunos b', 'b.id = a.id_aluno')
			->where('a.id_ordem_servico_escola', $escola)
			->get('ei_ordem_servico_alunos a')
			->result();

		$alunos = ['' => 'selecione...'] + array_column($alunosNaoAlocados, 'nome', 'id');

		$data['aluno'] = form_dropdown('aluno', $alunos, '', 'class="form-control"');

		echo json_encode($data);
	}


	//==========================================================================


	public function prepararOSIndividual()
	{
		$data = $this->input->post();
		if (empty($data['semestre'])) {
			$data['semestre'] = intval($data['mes']) > 7 ? '2' : '1';
		}

		unset($data['mes']);

		$sql = "SELECT a.id,
                       a.nome,
                       d.id AS id_escola,
                       d.nome AS escola,
                       m.id AS id_aluno,
                       m.nome AS aluno
                FROM ei_ordem_servico a
                INNER JOIN ei_contratos b 
                           ON b.id = a.id_contrato
                INNER JOIN ei_diretorias c
                           ON c.id = b.id_cliente
                INNER JOIN ei_escolas d 
                           ON d.id_diretoria = c.id
                INNER JOIN ei_supervisores e 
                           ON e.id_escola = d.id
                INNER JOIN ei_coordenacao f 
                           ON f.id = e.id_coordenacao
                INNER JOIN ei_ordem_servico_escolas g 
                           ON g.id_ordem_servico = a.id
                LEFT JOIN ei_ordem_servico_profissionais h 
                           ON h.id_ordem_servico_escola = g.id
                LEFT JOIN ei_funcoes_supervisionadas i
                           ON i.id_supervisor = f.id 
                           AND i.cargo = h.id_cargo 
                           AND i.funcao = h.id_funcao                          
                LEFT JOIN ei_ordem_servico_horarios j
                           ON j.id_os_profissional = h.id                          
                LEFT JOIN ei_ordem_servico_alunos k
                           ON k.id_ordem_servico_escola = g.id
                LEFT JOIN ei_ordem_servico_turmas l
                           ON l.id_os_horario = j.id 
                           AND l.id_os_aluno = k.id
                INNER JOIN ei_alunos m ON m.id = k.id_aluno
                WHERE c.id_empresa = {$this->session->userdata('empresa')}
                      AND c.depto = '{$data['depto']}'
                      AND c.id = {$data['diretoria']}
                      AND f.id_usuario = {$data['supervisor']}
                      AND a.ano = {$data['ano']}
                      AND a.semestre = {$data['semestre']}
                GROUP BY a.id, d.id, m.id
                ORDER BY a.nome ASC";
		$ordemServico = $this->db->query($sql)->result();


		$options = ['' => 'selecione...'] + array_column($ordemServico, 'nome', 'id');
		$escolas = array_column($ordemServico, 'escola', 'id_escola');
		$alunos = array_column($ordemServico, 'aluno', 'id_aluno');
		asort($escolas);
		asort($alunos);


		$data['ordem_servico'] = form_dropdown('ordem_servico', $options, '', 'class="form-control"');
		$data['escola'] = form_dropdown('escola', ['' => 'selecione...'] + $escolas, '', 'class="form-control"');
		$data['aluno'] = form_dropdown('aluno', ['' => 'selecione...'] + $alunos, '', 'class="form-control"');


		echo json_encode($data);
	}


	//==========================================================================


	public function adicionarOSIndividual()
	{
		// verifica se há ordem de serviço
		$ordemServico = $this->input->post('ordem_servico');
		if (empty($ordemServico)) {
			exit(json_encode(['erro' => 'Selecione uma Ordem de Serviço.']));
		}

		// Gera as variáveis locais necessárias
		$empresa = $this->session->userdata('empresa');
		$departamento = $this->input->post('depto');
		$idDiretoria = $this->input->post('diretoria');
		$idSupervisor = $this->input->post('supervisor');
		$ano = $this->input->post('ano_os');
		$anoReal = $this->input->post('ano');
		$mes = $this->input->post('mes_os');
		$mesReal = $this->input->post('mes');
		$semestre = $this->input->post('semestre_os');
		if (empty($semestre)) {
			$semestre = $mes > 7 ? '2' : '1';
		}
		$semestreReal = $this->input->post('semestre');
		if (empty($semestreReal)) {
			$semestreReal = $mesReal > 7 ? '2' : '1';
		}

		// Verifica se existe o mês alocado
		$alocacao = $this->db
			->where('id_empresa', $empresa)
			->where('depto', $departamento)
			->where('id_diretoria', $idDiretoria)
			->where('id_supervisor', $idSupervisor)
			->where('ano', $anoReal)
			->where('semestre', $semestreReal)
			->get('ei_alocacao')
			->row();

		if (empty($alocacao)) {
			exit(json_encode(['erro' => 'O semestre não foi iniciado.']));
		}

		// Inicia a transação
		$this->db->trans_begin();

		$idAlocacao = $alocacao->id;

		// gera as demais variáveis locais
		$osEscola = $this->input->post('escola');
		$osAluno = $this->input->post('aluno');
		$semestreAnterior = $this->input->post('semestre_anterior');
		$ordemServico = $this->input->post('ordem_servico');


		// Recupera as escolas já alocadoas
		$this->db->select('id, id_os_escola');
		if ($osEscola) {
			$this->db->where('id_os_escola', $osEscola);
		}
		$alocacaoEscolasExistentes = $this->db
			->where('id_alocacao', $idAlocacao)
			->get('ei_alocacao_escolas')
			->result_array();


		// Seleciona as novas escolas
		$this->db->select("'{$idAlocacao}' AS id_alocacao, a.id AS id_os_escola, b.id AS id_escola", false);
		$this->db->select('b.codigo, b.nome AS escola, b.municipio, c.nome AS ordem_servico, d.contrato', false);
		$this->db->join('ei_escolas b', 'b.id = a.id_escola');
		$this->db->join('ei_ordem_servico c', 'c.id = a.id_ordem_servico');
		$this->db->join('ei_contratos d', 'd.id = c.id_contrato');
		$this->db->join('ei_diretorias e', 'e.id = d.id_cliente');
		$this->db->join('ei_supervisores f', 'f.id_escola = b.id');
		$this->db->join('ei_coordenacao g', 'g.id = f.id_coordenacao AND g.ano = c.ano AND g.semestre = c.semestre');
		$this->db->join('ei_funcoes_supervisionadas h', 'h.id_supervisor = g.id');
		$this->db->join('ei_ordem_servico_profissionais i', 'i.id_ordem_servico_escola = a.id');
		$this->db->join('ei_ordem_servico_horarios j', 'j.id_os_profissional = i.id', 'left');
		$this->db->join('ei_alocacao_escolas k', "k.id_alocacao = '{$idAlocacao}' AND k.id_os_escola = a.id", 'left');
		$this->db->where('e.id_empresa', $empresa);
		$this->db->where('e.depto', $departamento);
		$this->db->where('e.id', $idDiretoria);
		$this->db->where('g.id_usuario', $idSupervisor);
		$this->db->where('c.ano', $ano);
		$this->db->where('c.semestre', $semestre);
		$this->db->where('(j.id_funcao = h.funcao OR j.id_funcao IS NULL)', null, false);
		$this->db->where('c.id', $ordemServico);
		$this->db->where('k.id', null);
		if ($osEscola) {
			$this->db->where('a.id', $osEscola);
		}
		$this->db->group_by(['a.id', 'b.nome']);
		$this->db->order_by('b.nome', 'asc');
		$alocacaoEscolas = $this->db->get('ei_ordem_servico_escolas a')->result_array();

		// Aloca as novas escolas
		if ($alocacaoEscolas) {
			$this->db->insert_batch('ei_alocacao_escolas', $alocacaoEscolas);
		}

		// mescla as escolas recém cadastradas à lista de escolas já alocadas
		$escolasExistentes = array_merge(array_column($alocacaoEscolasExistentes, 'id_os_escola'), array_column($alocacaoEscolas, 'id_os_escola'));

		// Verifica se a lista de escolas está vazia
		if (empty($escolasExistentes)) {
			$this->db->trans_rollback();
			exit(json_encode(['erro' => 'Nenhuma escola encontrada.']));
		}

		// recupera os cuidadores já alocados
		$alocadosExistentes = $this->db
			->select('a.id, a.id_os_profissional')
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->where_in('b.id_os_escola', $escolasExistentes)
			->get('ei_alocados a')
			->result_array();

		// seleciona os novos cuidadores
		$this->db->select('d.id AS id_alocacao_escola, a.id AS id_os_profissional, a.id_usuario AS id_cuidador, b.nome AS cuidador', false);
		$this->db->select('a.valor_hora_operacional, a.horas_mensais_custo, a.data_inicio_contrato, a.data_termino_contrato', false);
		$this->db->select(["ROUND((TIME_TO_SEC(a.horas_mensais_custo) / 3600) * a.valor_hora_operacional, 2) AS valor_total"], false);
		$this->db->join('usuarios b', 'b.id = a.id_usuario');
		$this->db->join('ei_ordem_servico_escolas c', 'c.id = a.id_ordem_servico_escola');
		$this->db->join('ei_alocacao_escolas d', 'd.id_os_escola = c.id');
		$this->db->join('ei_alocacao e', 'e.id = d.id_alocacao');
		$this->db->join('ei_supervisores f', 'f.id_escola = d.id_escola');
		$this->db->join('ei_coordenacao g', 'g.id = f.id_coordenacao AND g.id_usuario = e.id_supervisor AND g.ano = e.ano AND g.semestre = e.semestre');
		$this->db->join('ei_funcoes_supervisionadas h', 'h.id_supervisor = g.id');
		$this->db->join('ei_ordem_servico_horarios i', 'i.id_os_profissional = a.id', 'left');
		$this->db->join('ei_alocados j', 'j.id_alocacao_escola = d.id AND j.id_os_profissional = a.id', 'left');
		$this->db->join('ei_ordem_servico_turmas k', 'k.id_os_horario = i.id', 'left');
		$this->db->join('ei_ordem_servico_alunos l', 'l.id = k.id_os_aluno AND l.id_ordem_servico_escola = c.id', 'left');
		$this->db->where('d.id_alocacao', $idAlocacao);
		$this->db->where_in('c.id', $escolasExistentes);
		$this->db->where("(a.id_supervisor = {$idSupervisor} OR a.id_supervisor IS NULL)", null, false);
		$this->db->where('(i.id_funcao = h.funcao OR i.id_funcao IS NULL)', null, false);
		$this->db->where('j.id', null);
		if ($osAluno) {
			$this->db->where('l.id', $osAluno);
		}
		$this->db->group_by('a.id');
		$cuidadores = $this->db->get('ei_ordem_servico_profissionais a')->result_array();


		// aloca os novos cuidadores
		if ($cuidadores) {
			$this->db->insert_batch('ei_alocados', $cuidadores);
		}

		// mescla os novos cuidadores á lista de cuidadores já alocados
		$cuidadoresExistentes = array_merge(array_column($alocadosExistentes, 'id_os_profissional'), array_column($cuidadores, 'id_os_profissional'));

		// verifica se a lista de cuidadores está vazia
		if (!$cuidadoresExistentes) {
			$this->db->trans_rollback();
			exit(json_encode(['erro' => 'Nenhum cuidador encontrado.']));
		}

		// Recupera os alunos já alocados
		$matriculadosExistentes = $this->db
			->select('a.id, a.id_os_aluno')
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->where_in('b.id_os_escola', $escolasExistentes)
			->get('ei_matriculados a')
			->result_array();

		// seleciona os novos alunos
		$this->db->select('d.id AS id_alocacao_escola, a.id AS id_os_aluno, a.id_aluno, b.nome AS aluno', false);
		$this->db->select('b.status, b.hipotese_diagnostica, a.modulo, a.data_inicio, a.data_termino', false);
		$this->db->select('a.id_aluno_curso, a2.id_curso, a3.nome AS curso', false);
		$this->db->join('ei_alunos b', 'b.id = a.id_aluno');
		$this->db->join('ei_ordem_servico_escolas c', 'c.id = a.id_ordem_servico_escola');
		$this->db->join('ei_alunos_cursos a2', 'a2.id = a.id_aluno_curso AND a2.id_aluno = b.id');
		$this->db->join('ei_cursos a3', 'a3.id = a2.id_curso');
		$this->db->join('ei_alocacao_escolas d', 'd.id_os_escola = c.id');
		$this->db->join('ei_alocacao e', 'e.id = d.id_alocacao');
		$this->db->join('ei_supervisores f', 'f.id_escola = d.id_escola');
		$this->db->join('ei_coordenacao g', 'g.id = f.id_coordenacao AND g.ano = e.ano AND g.semestre = e.semestre');
		$this->db->join('ei_funcoes_supervisionadas h', 'h.id_supervisor = g.id');
		$this->db->join('ei_ordem_servico_profissionais i', 'i.id_ordem_servico_escola = c.id');
		$this->db->join('ei_ordem_servico_horarios j', 'j.id_os_profissional = i.id', 'left');
		$this->db->join('ei_matriculados k', 'k.id_alocacao_escola = d.id AND k.id_os_aluno = a.id', 'left');
		$this->db->where('d.id_alocacao', $idAlocacao);
		if ($osEscola) {
			$this->db->where('c.id', $osEscola);
		} else {
			$this->db->where_in('c.id', $escolasExistentes);
		}
		if ($semestreAnterior) {
			$this->db->where('a.id IS NOT NULL');
		}
		if ($osAluno) {
			$this->db->where('a.id', $osAluno);
		} elseif ($semestreAnterior == '0') {
			$this->db->where('(j.id_funcao = h.funcao OR j.id_funcao IS NULL)', null, false);
			$this->db->where('k.id', null);
		}
		$this->db->group_by(['a.id', 'b.id']);
		$alunos = $this->db->get('ei_ordem_servico_alunos a')->result_array();

		// aloca os novos alunos
		if ($alunos) {
			$this->db->insert_batch('ei_matriculados', $alunos);
		}

		// mescla os novos alunos à lista de alunos já alocados
		$alunosExistentes = array_merge(array_column($matriculadosExistentes, 'id_os_aluno'), array_column($alunos, 'id_os_aluno'));


		$mes1 = $semestre > 1 ? '07' : '01';
		$mes2 = $semestre > 1 ? '08' : '02';
		$mes3 = $semestre > 1 ? '09' : '03';
		$mes4 = $semestre > 1 ? '10' : '04';
		$mes5 = $semestre > 1 ? '11' : '05';
		$mes6 = $semestre > 1 ? '12' : '06';
		if ($semestre === '1') {
			$mes7 = '07';
		}


		$diaIniMes1 = date('Y-m-d', strtotime("{$ano}-{$mes1}-01"));
		$diaIniMes2 = date('Y-m-d', strtotime("{$ano}-{$mes2}-01"));
		$diaIniMes3 = date('Y-m-d', strtotime("{$ano}-{$mes3}-01"));
		$diaIniMes4 = date('Y-m-d', strtotime("{$ano}-{$mes4}-01"));
		$diaIniMes5 = date('Y-m-d', strtotime("{$ano}-{$mes5}-01"));
		$diaIniMes6 = date('Y-m-d', strtotime("{$ano}-{$mes6}-01"));
		if ($semestre === '1') {
			$diaIniMes7 = date('Y-m-d', strtotime("{$ano}-{$mes7}-01"));
		}


		$diaFimMes1 = date('Y-m-t', strtotime($diaIniMes1));
		$diaFimMes2 = date('Y-m-t', strtotime($diaIniMes2));
		$diaFimMes3 = date('Y-m-t', strtotime($diaIniMes3));
		$diaFimMes4 = date('Y-m-t', strtotime($diaIniMes4));
		$diaFimMes5 = date('Y-m-t', strtotime($diaIniMes5));
		$diaFimMes6 = date('Y-m-t', strtotime($diaIniMes6));
		if ($semestre === '1') {
			$diaFimMes7 = date('Y-m-t', strtotime($diaIniMes7));
		}


		$this->db->select('c.id AS id_alocado, a.id AS id_os_horario, f.nome AS cargo, e.nome AS funcao');
		$this->db->select('a.dia_semana, a.periodo');
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes1} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_inicio END) AS horario_inicio_mes1", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes2} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_inicio END) AS horario_inicio_mes2", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes3} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_inicio END) AS horario_inicio_mes3", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes4} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_inicio END) AS horario_inicio_mes4", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes5} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_inicio END) AS horario_inicio_mes5", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes6} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_inicio END) AS horario_inicio_mes6", false);
		if ($semestre === '1') {
			$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes7} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_inicio END) AS horario_inicio_mes7", false);
		}
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes1} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_termino END) AS horario_termino_mes1", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes2} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_termino END) AS horario_termino_mes2", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes3} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_termino END) AS horario_termino_mes3", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes4} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_termino END) AS horario_termino_mes4", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes5} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_termino END) AS horario_termino_mes5", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes6} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_termino END) AS horario_termino_mes6", false);
		if ($semestre === '1') {
			$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes7} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN a.horario_termino END) AS horario_termino_mes7", false);
		}
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes1} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes1", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes2} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes2", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes3} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes3", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes4} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes4", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes5} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes5", false);
		$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes6} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes6", false);
		if ($semestre === '1') {
			$this->db->select("(CASE WHEN '{$semestreAnterior}' = 1 OR ({$mes7} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))) THEN TIMEDIFF(a.horario_termino, a.horario_inicio) END) AS total_horas_mes7", false);
		}
		$this->db->select(['a.data_inicio_contrato, a.data_termino_contrato, a.valor_hora_operacional, a.horas_mensais_custo, l.valor AS valor_hora_funcao'], false);
		$this->db->select(['IF(a.valor_hora_operacional > 0, a.valor_hora_operacional, l.valor_pagamento) AS valor_hora_operacional'], false);
		$this->db->select(["IF('{$semestreAnterior}' = 1 OR ({$mes1} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes1}, MAX(h.data_termino), '{$diaFimMes1}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes1}, MAX(h.data_termino), '$diaFimMes1'), '%w') - a.dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes1}, MIN(h.data_inicio), '{$diaIniMes1}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes1}, MIN(h.data_inicio), '{$diaIniMes1}'), '%w')) + a.dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes1"], false);
		$this->db->select(["IF('{$semestreAnterior}' = 1 OR ({$mes2} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes2}, MAX(h.data_termino), '{$diaFimMes2}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes2}, MAX(h.data_termino), '$diaFimMes2'), '%w') - a.dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes2}, MIN(h.data_inicio), '{$diaIniMes2}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes2}, MIN(h.data_inicio), '{$diaIniMes2}'), '%w')) + a.dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes2"], false);
		$this->db->select(["IF('{$semestreAnterior}' = 1 OR ({$mes3} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes3}, MAX(h.data_termino), '{$diaFimMes3}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes3}, MAX(h.data_termino), '$diaFimMes3'), '%w') - a.dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes3}, MIN(h.data_inicio), '{$diaIniMes3}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes3}, MIN(h.data_inicio), '{$diaIniMes3}'), '%w')) + a.dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes3"], false);
		$this->db->select(["IF('{$semestreAnterior}' = 1 OR ({$mes4} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes4}, MAX(h.data_termino), '{$diaFimMes4}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes4}, MAX(h.data_termino), '$diaFimMes4'), '%w') - a.dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes4}, MIN(h.data_inicio), '{$diaIniMes4}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes4}, MIN(h.data_inicio), '{$diaIniMes4}'), '%w')) + a.dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes4"], false);
		$this->db->select(["IF('{$semestreAnterior}' = 1 OR ({$mes5} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes5}, MAX(h.data_termino), '{$diaFimMes5}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes5}, MAX(h.data_termino), '$diaFimMes5'), '%w') - a.dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes5}, MIN(h.data_inicio), '{$diaIniMes5}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes5}, MIN(h.data_inicio), '{$diaIniMes5}'), '%w')) + a.dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes5"], false);
		$this->db->select(["IF('{$semestreAnterior}' = 1 OR ({$mes6} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes6}, MAX(h.data_termino), '{$diaFimMes6}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes6}, MAX(h.data_termino), '$diaFimMes6'), '%w') - a.dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes6}, MIN(h.data_inicio), '{$diaIniMes6}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes6}, MIN(h.data_inicio), '{$diaIniMes6}'), '%w')) + a.dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes6"], false);
		if ($semestre === '1') {
			$this->db->select(["IF('{$semestreAnterior}' = 1 OR ({$mes7} BETWEEN MONTH(MIN(h.data_inicio)) AND MONTH(MAX(h.data_termino))), WEEK(DATE_SUB(IF(MONTH(MAX(h.data_termino)) = {$mes7}, MAX(h.data_termino), '{$diaFimMes7}'), INTERVAL ((7 + DATE_FORMAT(IF(MONTH(MAX(h.data_termino)) = {$mes7}, MAX(h.data_termino), '$diaFimMes7'), '%w') - a.dia_semana) % 7) DAY)) - WEEK(DATE_ADD(IF(MONTH(MIN(h.data_inicio)) = {$mes7}, MIN(h.data_inicio), '{$diaIniMes7}'), INTERVAL (((7 - DATE_FORMAT(IF(MONTH(MIN(h.data_inicio)) = {$mes7}, MIN(h.data_inicio), '{$diaIniMes7}'), '%w')) + a.dia_semana) % 7) DAY)) + 1, 0) AS total_semanas_mes7"], false);
		}
		$this->db->join('ei_ordem_servico_profissionais b', 'b.id = a.id_os_profissional');
		$this->db->join('ei_ordem_servico_escolas i', 'i.id = b.id_ordem_servico_escola');
		$this->db->join('ei_ordem_servico j', 'j.id = i.id_ordem_servico');
		$this->db->join('ei_contratos k', 'k.id = j.id_contrato');
		$this->db->join('ei_alocados c', 'c.id_os_profissional = b.id');
		$this->db->join('ei_alocacao_escolas d', 'd.id = c.id_alocacao_escola');
		$this->db->join('ei_alocacao d2', 'd2.id = d.id_alocacao');
		$this->db->join('ei_supervisores m', 'm.id_escola = d.id_escola');
		$this->db->join('ei_coordenacao n', 'n.id = m.id_coordenacao AND n.id_usuario = d2.id_supervisor AND n.ano = d2.ano AND n.semestre = d2.semestre');
		$this->db->join('ei_funcoes_supervisionadas o', 'o.id_supervisor = n.id', 'left');
		$this->db->join('empresa_funcoes e', 'e.id = a.id_funcao', 'left');
		$this->db->join('empresa_cargos f', 'f.id = e.id_cargo', 'left');
		$this->db->join('ei_ordem_servico_turmas g', 'g.id_os_horario = a.id', 'left');
		$this->db->join('ei_ordem_servico_alunos h', 'h.id = g.id_os_aluno AND h.id_ordem_servico_escola = i.id', 'left');
		$this->db->join('ei_valores_faturamento l', 'l.id_contrato = k.id AND l.ano = j.ano AND l.semestre = j.semestre AND l.id_cargo = f.id AND l.id_funcao = e.id', 'left');
		$this->db->join('ei_alocados_horarios m2', 'm2.id_alocado = c.id AND m2.id_os_horario = a.id', 'left');
		$this->db->where('d.id_alocacao', $idAlocacao);
		$this->db->where_in('d.id_os_escola', $escolasExistentes);
		$this->db->where_in('b.id', $cuidadoresExistentes);
		$this->db->where('(o.funcao = a.id_funcao OR a.id_funcao IS NULL)', null, false);
		$this->db->where('m2.id', null);
		$this->db->group_by('a.id');
		$horarios = $this->db->get('ei_ordem_servico_horarios a')->result_array();
//exit(json_encode(['erro' => $horarios]));

		if ($horarios) {
			$this->db->insert_batch('ei_alocados_horarios', $horarios);
		}

		$horariosExistentes = $this->db
			->select('a.id_os_horario')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->where_in('c.id_os_escola', $escolasExistentes)
			->get('ei_alocados_horarios a')
			->result_array();


		$this->db->select('d.id AS id_matriculado, e.id AS id_alocado_horario');
		$this->db->join('ei_ordem_servico_alunos b', 'b.id = a.id_os_aluno');
		$this->db->join('ei_ordem_servico_horarios c', 'c.id = a.id_os_horario');
		$this->db->join('ei_matriculados d', 'd.id_os_aluno = b.id');
		$this->db->join('ei_alocados_horarios e', 'e.id_os_horario = c.id');
		$this->db->join('ei_alocados f', 'f.id = e.id_alocado');
		$this->db->join('ei_alocacao_escolas g', 'g.id = f.id_alocacao_escola');
		$this->db->join('ei_matriculados_turmas h', 'h.id_matriculado = d.id AND h.id_alocado_horario = e.id', 'left');
		$this->db->where('g.id_alocacao', $idAlocacao);
		$this->db->where_in('g.id_os_escola', $escolasExistentes);
		$this->db->where_in('f.id_os_profissional', $cuidadoresExistentes);
		$this->db->where_in('d.id_os_aluno', $alunosExistentes);
		$this->db->where_in('e.id_os_horario', array_column($horariosExistentes, 'id_os_horario'));
		$this->db->where('(h.id_matriculado IS NULL OR h.id_alocado_horario IS NULL)');
		$turmas = $this->db->get('ei_ordem_servico_turmas a')->result_array();


		if ($turmas) {
			$this->db->insert_batch('ei_matriculados_turmas', $turmas);
		}


		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			exit(json_encode(['erro' => 'Erro ao iniciar semestre.']));
		}

		$this->db->trans_commit();

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function limparSemestre()
	{
		$data = $this->input->post();


		$this->db->select('id');
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$this->db->where('depto', $data['depto']);
		$this->db->where('id_diretoria', $data['diretoria']);
		$this->db->where('id_supervisor', $data['supervisor']);
		$this->db->where('ano', $data['ano']);
		$this->db->where('semestre', $data['semestre']);
		$rows = $this->db->get('ei_alocacao')->result();


		if (!$rows) {
			exit(json_encode(['erro' => 'Este semestre já está vazio.']));
		}


		if ($data['possui_mapa_visitacao'] === '2') {
			$this->db->where_in('id_alocacao', array_column($rows, 'id'));
			$status = $this->db->delete('ei_mapa_unidades');
		} elseif ($data['possui_mapa_visitacao'] === '1') {
			$this->db->where_in('id', array_column($rows, 'id'));
			$status = $this->db->delete('ei_alocacao');
		} else {
			$this->db->where_in('id_alocacao', array_column($rows, 'id'));
			$status = $this->db->delete(['ei_alocacao_escolas', 'ei_faturamento', 'ei_faturamento_consolidado', 'ei_pagamento_prestador']);
		}


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxListEventos()
	{
		parse_str($this->input->post('busca'), $busca);
		$semestre = $busca['semestre'] ?? null;
		if (empty($semestre)) {
			$semestre = intval($busca['mes']) > 7 ? '2' : '1';
		}


		$query = $this->db
			->select('a.id, b.municipio, b.escola, b.ordem_servico, a.id_cuidador, d.periodo')
			->select(["CASE WHEN MONTH(d.data_substituicao1) < '{$busca['mes']}' || MONTH(d.data_substituicao2) < '{$busca['mes']}' THEN NULL ELSE a.cuidador END AS cuidador"], false)
			->select("GROUP_CONCAT(DISTINCT h.aluno ORDER BY h.aluno SEPARATOR ';<br>') AS aluno", false)
			->select("(CASE d.periodo WHEN 0 THEN 'Madrugada' WHEN 1 THEN 'Manhã' WHEN 2 THEN 'Tarde' WHEN 3 THEN 'Noite' END) AS nome_periodo", false)
			->select(["CASE WHEN MONTH(d.data_substituicao1) <= '{$busca['mes']}' THEN e.nome ELSE NULL END AS cuidador_sub1"], false)
			->select(["CASE WHEN MONTH(d.data_substituicao2) <= '{$busca['mes']}' THEN f.nome ELSE NULL END AS cuidador_sub2"], false)
			->select("h.id AS id_aluno_matriculado, COUNT(DISTINCT(h.aluno)) AS total_alunos", false)
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->join('ei_alocacao c', 'c.id = b.id_alocacao')
			->join('ei_alocados_horarios d', "d.id_alocado = a.id", 'left')
			->join('usuarios e', 'e.id = d.id_cuidador_sub1', 'left')
			->join('usuarios f', 'f.id = d.id_cuidador_sub2', 'left')
			->join('ei_matriculados_turmas g', "g.id_alocado_horario = d.id", 'left')
			->join('ei_matriculados h', 'h.id = g.id_matriculado AND h.id_alocacao_escola = b.id', 'left')
			->where('c.id_empresa', $this->session->userdata('empresa'))
			->where('c.depto', $busca['depto'])
			->where('c.id_diretoria', $busca['diretoria'])
			->where('c.id_supervisor', $busca['supervisor'])
			->where('c.ano', $busca['ano'])
			->where('c.semestre', $semestre)
			->group_by(['a.id', 'a.cuidador', 'd.periodo'])
			->get('ei_alocados a');

		$options = [
			'search' => ['municipio', 'escola', 'cuidador'],
			'order' => ['municipio', 'cuidador', 'aluno', 'escola', 'ordem_servico']
		];
		$this->load->library('dataTables', $options);

		$output = $this->datatables->generate($query);


		$alocados = $output->data;
		$output->totalFuncionarios = count(array_filter(array_column($alocados, 'id_cuidador')));
		$output->totalAlunos = array_sum(array_column($alocados, 'total_alunos'));


		$rowsEventos = $this->db
			->select("b.id, DATE_FORMAT(a.data, '%d') AS dia", false)
			->select("IFNULL(a.periodo, '') AS periodo, a.status", false)
			->select("TIME_FORMAT(a.desconto, '%H:%i') AS desconto", false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->where('d.ano', $busca['ano'])
			->where('d.semestre', $semestre)
			->where('YEAR(a.data)', $busca['ano'])
			->where('MONTH(a.data)', $busca['mes'])
			->where_in('b.id', $alocados ? array_unique(array_column($alocados, 'id')) : [0])
			->get('ei_apontamento a')
			->result();

		$apontamento = [];
		$nomeDoStatus = [
			'FA' => 'Falta',
			'PV' => 'Posto vago',
			'AT' => 'Atraso',
			'SA' => 'Saída antecipada',
			'FE' => 'Feriado',
			'EM' => 'Emenda de feriado',
			'RE' => 'Recesso',
			'AF' => 'Aluno ausente',
			'EU' => 'Evento Unidade',
			'AP' => 'Apontamento positivo',
			'AN' => 'Apontamento negativo'
		];
		foreach ($rowsEventos as $rowEvento) {
			$apontamento[$rowEvento->id][intval($rowEvento->dia)][$rowEvento->periodo] = [
				'status' => $rowEvento->status,
				'tipo' => $nomeDoStatus[$rowEvento->status],
				'desconto' => $rowEvento->desconto
			];
		}


		$data = [];
		foreach ($alocados as $alocado) {
			$row = [
				$alocado->id,
				"<strong>Municipio:</strong> {$alocado->municipio}&emsp;
                <strong>Escola:</strong> {$alocado->escola}<br>
                <strong>Ordem de serviço:</strong> {$alocado->ordem_servico}",
				implode(';<br>', array_unique(array_filter([$alocado->cuidador, $alocado->cuidador_sub1, $alocado->cuidador_sub2]))),
				(strlen($alocado->nome_periodo) and $alocado->aluno) ? $alocado->aluno . ' - ' . $alocado->nome_periodo : null
			];
			for ($i = 1; $i <= 31; $i++) {
				$row[] = $apontamento[$alocado->id][$i][$alocado->periodo] ?? $apontamento[$alocado->id][$i][''] ?? [];
			}
			$row[] = $alocado->periodo;
			$row[] = $alocado->id_aluno_matriculado;

			$data[] = $row;
		}

		$output->data = $data;


		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semana = [];
		for ($i = 1; $i <= 7; $i++) {
			$semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
		}
		$output->calendar = [
			'mes' => $busca['mes'],
			'ano' => $busca['ano'],
			'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
			'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
			'semana' => $semana
		];

		echo json_encode($output);
	}


	//==========================================================================


	public function ajaxListFaturamento()
	{
		$post = $this->input->post();

		parse_str($this->input->post('busca'), $busca);
		$semestre = $busca['semestre'] ?? null;
		if (empty($semestre)) {
			$semestre = intval($busca['mes']) > 7 ? '2' : '1';
		}
		$idMes = intval($busca['mes']) - ($semestre === '2' ? 6 : 0);


		$this->db->select('c.municipio, c.escola, c.ordem_servico, b.total_dias_letivos, a.periodo');
		$this->db->select("DATE_FORMAT(a.dia_semana, '%a') AS semana", false);
		$this->db->select("(CASE a.periodo WHEN 0 THEN 'Madrugada' WHEN 1 THEN 'Manhã' WHEN 2 THEN 'Tarde' WHEN 3 THEN 'Noite' END) AS nome_periodo", false);

		$this->db->select('a.dia_semana');
		$this->db->select("TIME_FORMAT(a.horario_inicio_mes{$idMes}, '%H:%i') AS horario_entrada", false);
		$this->db->select("TIME_FORMAT(a.horario_termino_mes{$idMes}, '%H:%i') AS horario_saida", false);
		$this->db->select("TIME_FORMAT(a.total_horas_mes{$idMes}, '%H:%i') AS total_horas", false);
		$this->db->select(["CASE WHEN MONTH(a.data_substituicao1) < '{$busca['mes']}' || MONTH(a.data_substituicao2) < '{$busca['mes']}' THEN NULL ELSE b.cuidador END AS cuidador"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao1) <= '{$busca['mes']}' THEN e.nome END) AS cuidador_sub1"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao2) <= '{$busca['mes']}' THEN f.nome END) AS cuidador_sub2"], false);
		$this->db->select(["CASE WHEN MONTH(a.data_substituicao1) < '{$busca['mes']}' || MONTH(a.data_substituicao2) < '{$busca['mes']}' THEN NULL ELSE a.funcao END AS funcao"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao1) <= '{$busca['mes']}' THEN a.funcao_sub1 END) AS funcao_sub1"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao2) <= '{$busca['mes']}' THEN a.funcao_sub2 END) AS funcao_sub2"], false);

		$this->db->select("a.total_semanas_mes{$idMes} AS total_semanas_mes");
		$this->db->select("a.desconto_mes{$idMes} - IFNULL(a.endosso_mes{$idMes}, 0) AS desconto_mes");
		$this->db->select(["TIME_FORMAT(IFNULL(a.total_endossado_mes{$idMes}, a.total_mes{$idMes}), '%H:%i') AS total_mes"], false);
		$this->db->select(["(SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IFNULL(ax.total_endossado_mes{$idMes}, ax.total_mes{$idMes})))), '%H:%i') FROM ei_alocados_horarios ax WHERE ax.id_alocado = b.id AND ax.periodo = a.periodo) AS total_horas_mes"], false);
		$this->db->select(["TIME_FORMAT(g.dias_descontados_mes{$idMes}, '%H:%i') AS dias_descontados_mes"], false);
		$this->db->select(["TIME_FORMAT(g.horas_descontadas_mes{$idMes}, '%H:%i') AS horas_descontadas_mes"], false);
		$this->db->select("j.data_liberacao_pagto_mes{$idMes} AS data_liberacao_pagto_mes");

		$this->db->select(["IF(MONTH(a.data_substituicao1) = {$busca['mes']}, a.total_semanas_sub1, NULL) AS total_semanas_sub1_mes"], false);
		$this->db->select(["IF(MONTH(a.data_substituicao1) = {$busca['mes']}, a.desconto_sub1 - IFNULL(a.endosso_sub1, 0), NULL) AS desconto_sub1_mes"], false);
		$this->db->select("TIME_FORMAT(IF(MONTH(a.data_substituicao1) = {$busca['mes']}, IFNULL(a.total_endossado_sub1, a.total_sub1), 0), '%H:%i') AS total_sub1_mes", false);
//		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao1) = '{$busca['mes']}' THEN TIME_FORMAT(ADDTIME(g2.total_horas_mes{$idMes}, IFNULL(g2.horas_descontadas_mes{$idMes}, 0)), '%H:%i') END) AS total_horas_sub1_mes"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao1) = '{$busca['mes']}' THEN TIME_FORMAT(g2.total_horas_mes{$idMes}, '%H:%i') END) AS total_horas_sub1_mes"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao1) = '{$busca['mes']}' THEN TIME_FORMAT(g2.dias_descontados_mes{$idMes}, '%H:%i') END) AS dias_descontados_sub1_mes"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao1) = '{$busca['mes']}' THEN TIME_FORMAT(g2.horas_descontadas_mes{$idMes}, '%H:%i') END) AS horas_descontadas_sub1_mes"], false);

		$this->db->select(["IF(MONTH(a.data_substituicao2) = {$busca['mes']}, a.total_semanas_sub2, NULL) AS total_semanas_sub2_mes"], false);
		$this->db->select(["IF(MONTH(a.data_substituicao2) = {$busca['mes']}, a.desconto_sub2  - IFNULL(a.endosso_sub2, 0), NULL) AS desconto_sub2_mes"], false);
		$this->db->select("TIME_FORMAT(IF(MONTH(a.data_substituicao2) = {$busca['mes']}, a.total_endossado_sub2, 0), '%H:%i') AS total_sub2_mes", false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao2) = '{$busca['mes']}' THEN TIME_FORMAT(ADDTIME(g3.total_horas_mes{$idMes}, IFNULL(g3.horas_descontadas_mes{$idMes}, 0)), '%H:%i') END) AS total_horas_sub2_mes"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao1) = '{$busca['mes']}' THEN TIME_FORMAT(g3.dias_descontados_mes{$idMes}, '%H:%i') END) AS dias_descontados_sub2_mes"], false);
		$this->db->select(["(CASE WHEN MONTH(a.data_substituicao1) = '{$busca['mes']}' THEN TIME_FORMAT(g3.horas_descontadas_mes{$idMes}, '%H:%i') END) AS horas_descontadas_sub2_mes"], false);

		$this->db->select('a.id, b.id AS id_alocado');
		$this->db->select(["DATE_FORMAT(k.data_aprovacao_mes{$idMes}, '%d/%m/%Y') AS data_aprovacao_mes"], false);
		$this->db->select(['MONTH(a.data_substituicao1)' . ($semestre > 1 ? ' -6' : '') . ' AS mes_sub1'], false);
		$this->db->select(['MONTH(a.data_substituicao2)' . ($semestre > 1 ? ' -6' : '') . ' AS mes_sub2'], false);

		$this->db->select("GROUP_CONCAT(DISTINCT i.aluno ORDER BY i.aluno SEPARATOR ', ') AS alunos", false);
		$this->db->select('i.data_inicio AS data_inicio_aluno_de, i.data_termino AS data_termino_aluno_de');
		$this->db->select("DATE_FORMAT(MIN(i.data_inicio), '%d/%m/%Y') AS data_inicio_aluno", false);
		$this->db->select("DATE_FORMAT(MAX(i.data_termino), '%d/%m/%Y') AS data_termino_aluno", false);
		$this->db->select("IFNULL(DATE_FORMAT(IFNULL(a.data_inicio_real, MIN(i.data_inicio)), '%d/%m/%Y'), '00/00/0000') AS data_inicio_real", false);
		$this->db->select("IFNULL(DATE_FORMAT(a.data_termino_real, '%d/%m/%Y'), '00/00/0000') AS data_termino_real", false);
		$this->db->select("IFNULL(DATE_FORMAT(MAX(i.data_recesso), '%d/%m/%Y'), '00/00/0000') AS data_recesso_aluno", false);

		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_alocacao d', 'd.id = c.id_alocacao');
		$this->db->join('usuarios e', 'e.id = a.id_cuidador_sub1', 'left');
		$this->db->join('usuarios f', 'f.id = a.id_cuidador_sub2', 'left');
		$this->db->join('ei_alocados_totalizacao g', 'g.id_alocado = b.id AND g.periodo = a.periodo AND g.id_cuidador = b.id_cuidador', 'left');
		$this->db->join('ei_alocados_totalizacao g2', "g2.id_alocado = b.id AND g2.periodo = a.periodo AND g2.id_cuidador = a.id_cuidador_sub1", 'left');
		$this->db->join('ei_alocados_totalizacao g3', "g3.id_alocado = b.id AND g3.periodo = a.periodo AND g3.id_cuidador = a.id_cuidador_sub2", 'left');
//		$this->db->join('ei_alocados_totalizacao g', 'g.id_alocado = b.id AND g.periodo = a.periodo AND g.id_cuidador = b.id_cuidador AND g.substituicao_semestral IS NULL', 'left');
//		$this->db->join('ei_alocados_totalizacao g2', "g2.id_alocado = b.id AND g2.periodo = a.periodo AND g2.id_cuidador = a.id_cuidador_sub1 AND MONTH(a.data_substituicao1) = '{$busca['mes']}'", 'left');
//		$this->db->join('ei_alocados_totalizacao g3', "g3.id_alocado = b.id AND g3.periodo = a.periodo AND g3.id_cuidador = a.id_cuidador_sub2 AND MONTH(a.data_substituicao2) = '{$busca['mes']}'", 'left');
		$this->db->join('ei_pagamento_prestador j', 'j.id_alocacao = d.id AND j.id_cuidador = b.id_cuidador', 'left');
		$this->db->join('ei_faturamento k', 'k.id_alocacao = d.id AND k.id_escola = c.id_escola AND k.cargo = a.cargo AND k.funcao = a.funcao', 'left');
		$this->db->join('ei_matriculados_turmas h', 'h.id_alocado_horario = a.id', 'left');
		$this->db->join('ei_matriculados i', 'i.id = h.id_matriculado AND i.id_alocacao_escola = c.id', 'left');
		$this->db->where('d.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('d.depto', $busca['depto']);
		$this->db->where('d.id_diretoria', $busca['diretoria']);
		$this->db->where('d.id_supervisor', $busca['supervisor']);
		$this->db->where('d.ano', $busca['ano']);
		$this->db->where('d.semestre', $semestre);
		$this->db->group_by(['c.ordem_servico', 'c.municipio', 'c.escola', 'a.periodo', 'a.dia_semana', 'a.id', 'e.id', 'f.id']);
		$this->db->order_by('a.id_alocado', 'asc');
		$this->db->order_by("a.horario_inicio_mes{$idMes}", 'asc');
		$query = $this->db->get('ei_alocados_horarios a');


		$options = [
			'search' => ['municipio', 'escola', 'alunos', 'cuidador', 'cuidador_sub1', 'cuidador_sub2']
		];
		$this->load->library('dataTables', $options);

		$output = $this->datatables->generate($query);


		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semestres = [];
		$mesInicial = $semestre === '2' ? 7 : 1;
		$mesFinal = $semestre === '2' ? 12 : 7;
		$mesAno = [];
		for ($i = $mesInicial; $i <= $mesFinal; $i++) {
			$semestres[] = ucfirst($this->calendar->get_month_name($busca['mes']));
			$mesAno[] = date('F Y', strtotime('01-' . $busca['mes'] . '-' . $busca['ano']));
		}

		$output->semestre = $semestres;


		$data = [];

		foreach ($output->data as $row) {
//			$meses = array_intersect(['0' => $busca['mes'], '1' => $row->mes_sub1, '2' => $row->mes_sub2], [min(array_filter([$busca['mes'], $row->mes_sub1, $row->mes_sub2]))]);

			if (strlen($row->data_inicio_aluno) > 0 and strlen($row->data_termino_aluno) > 0) {
				if (strtotime($row->data_termino_aluno_de) < strtotime($row->data_inicio_aluno_de)) {
					$dataInicioAluno = '<span style="background-color: #FF0;">' . $row->data_inicio_aluno . '</span>';
					$dataTerminoAluno = '<span style="background-color: #FF0;">' . $row->data_termino_aluno . '</span>';
				} else {
					$dataInicioAluno = $row->data_inicio_aluno;
					$dataTerminoAluno = $row->data_termino_aluno;
				}
			} else {
				$dataInicioAluno = strlen($row->data_inicio_aluno) > 0 ? $row->data_inicio_aluno : '<span style="background-color: #FF0;">XX:XX:XXXX</span>';
				$dataTerminoAluno = strlen($row->data_termino_aluno) > 0 ? $row->data_termino_aluno : '<span style="background-color: #FF0;">XX:XX:XXXX</span>';
			}

			$data[] = [
				"<strong>Municipio:</strong> {$row->municipio}&emsp;
                <strong>Escola:</strong> {$row->escola}&emsp;
                <strong>Ordem de serviço:</strong> {$row->ordem_servico}<br>
                <strong>Aluno(s):</strong> {$row->alunos} - {$row->nome_periodo}&emsp;
                <strong>Data início (projetada):</strong> {$dataInicioAluno}&emsp;
                <strong>Data início (real):</strong> {$row->data_inicio_real}&emsp;&emsp;&emsp;&emsp;&emsp;
                <strong>Data término (projetada):</strong> {$dataTerminoAluno}&emsp;
                <strong>Data término (real):</strong> {$row->data_termino_real}<br>
                <button type='button' class='btn btn-xs btn-success btnFecharMes' onclick='fechar_mes($row->id_alocado, $row->periodo)'>1 - Fechar mês</button>
                <button type='button' class='btn btn-xs btn-success btnTotalizarMes' onclick='totalizar_mes($row->id_alocado, $row->periodo)'>2 - Totalizar mês</button>
                <button type='button' class='btn btn-xs btn-info btnIngresso' onclick='edit_data_real_totalizacao($row->id_alocado, $row->periodo, 0)'>Editar data início (real)</button>
                <button type='button' class='btn btn-xs btn-info btnRecesso' onclick='edit_data_real_totalizacao($row->id_alocado, $row->periodo, 1)'>Editar data término (real)</button>
                <button type='button' class='btn btn-xs btn-danger' onclick='edit_desalocacao($row->id_alocado, $row->periodo)'>Desalocar...</button>",

				// 1---------------------------------------------------

				$dias_semana[$row->dia_semana],
				strlen($row->total_semanas_mes) > 0 ? implode(' às ', array_filter([$row->horario_entrada, $row->horario_saida])) : null,
				$row->total_horas,
				implode(';<br>', array_filter([$row->cuidador, $row->cuidador_sub1, $row->cuidador_sub2])),
				implode(';<br>', array_filter([$row->funcao, $row->funcao_sub1, $row->funcao_sub2])),

				// 6---------------------------------------------------

				$row->total_semanas_mes,
				$row->desconto_mes ? str_replace('.', ',', round($row->desconto_mes, 2)) : null,
				$row->total_mes,
				$row->total_horas_mes,
				$row->dias_descontados_mes,
				$row->horas_descontadas_mes,#10 > 11
				$row->data_liberacao_pagto_mes,

				$row->total_semanas_sub1_mes,
				$row->desconto_sub1_mes ? str_replace('.', ',', round($row->desconto_sub1_mes, 2)) : null,
				$row->total_sub1_mes,
				$row->total_horas_sub1_mes,
				$row->dias_descontados_sub1_mes,
				$row->horas_descontadas_sub1_mes,#16 > 18
				$row->data_liberacao_pagto_mes,

				$row->total_semanas_sub2_mes,
				$row->desconto_sub2_mes ? str_replace('.', ',', round($row->desconto_sub2_mes, 2)) : null,
				$row->total_sub2_mes,
				$row->total_horas_sub2_mes,
				$row->dias_descontados_sub2_mes,
				$row->horas_descontadas_sub2_mes,#22 > 25
				$row->data_liberacao_pagto_mes,

				// 27---------------------------------------------------

				$row->id,
				$row->id_alocado,
				$row->data_aprovacao_mes,
				$row->cuidador_sub1, #30
				$row->funcao_sub1,
				$row->mes_sub1,
				$row->cuidador_sub2, #33
				$row->funcao_sub2,
				$row->mes_sub2,
				$row->funcao,
				$row->periodo
			];
		}

		$output->data = $data;


		$this->db->select("IF(d.id_cuidador_sub1 IS NOT NULL, MONTH(d.data_substituicao1)" . (intval($busca['mes']) > 6 ? ' - 6' : '') . ", null) AS mes_sub1", false);
		$this->db->select("IF(d.id_cuidador_sub2 IS NOT NULL, MONTH(d.data_substituicao2)" . (intval($busca['mes']) > 6 ? ' - 6' : '') . ", null) AS mes_sub2", false);
		$this->db->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id');
		$this->db->join('ei_alocados c', 'c.id_alocacao_escola = b.id');
		$this->db->join('ei_alocados_horarios d', 'd.id_alocado = c.id');
		$this->db->where('a.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('a.depto', $busca['depto']);
		$this->db->where('a.id_diretoria', $busca['diretoria']);
		$this->db->where('a.id_supervisor', $busca['supervisor']);
		$this->db->where('a.ano', $busca['ano']);
		$this->db->where('a.semestre', $semestre);
		if ($post['search']['value']) {
			$this->db->like('b.municipio', $post['search']['value']);
		}
		if ($post['length'] > 0) {
			$this->db->limit($post['length'], $post['start']);
		}
		$rowSubstituicaoMes = $this->db->get('ei_alocacao a')->result();

		$mes_sub1 = array_filter(array_column($rowSubstituicaoMes, 'mes_sub1'));
		$mes_sub2 = array_filter(array_column($rowSubstituicaoMes, 'mes_sub2'));

		for ($i = 1; $i <= 7; $i++) {
			$substituicaoMes['mes' . $i] = [
				!(isset($mes_sub1[$i]) and isset($mes_sub2[$i])),
				isset($mes_sub1[$i]),
				isset($mes_sub2[$i])
			];
		}

		$output->substituicaoMes = $substituicaoMes;


		$output->mes = $busca['mes'];
		$output->fechamentoMes = boolval(array_filter(array_column($data, 7), function ($v, $k) {
			return strlen($v) > 0;
		}, ARRAY_FILTER_USE_BOTH));
		$output->totalizacaoMes = boolval(array_filter(array_column($data, 8), function ($v, $k) {
			return strlen($v) > 0;
		}, ARRAY_FILTER_USE_BOTH));


		echo json_encode($output);
	}


	//==========================================================================


	public function ajaxListControleMateriais()
	{
		$post = $this->input->post();

		parse_str($this->input->post('busca'), $busca);
		$semestre = $busca['semestre'] ?? null;
		if (empty($semestre)) {
			$semestre = intval($busca['mes']) > 7 ? '2' : '1';
		}


		$this->db->select('b.municipio, a.aluno, b.escola, b.ordem_servico, a.id');
		$this->db->select(["CASE WHEN MONTH(e.data_substituicao1) < '{$busca['mes']}' || MONTH(e.data_substituicao2) < '{$busca['mes']}' THEN NULL ELSE f.cuidador END AS cuidador"], false);
		$this->db->select(["CASE WHEN MONTH(e.data_substituicao1) <= '{$busca['mes']}' THEN g.nome ELSE NULL END AS cuidador_sub1"], false);
		$this->db->select(["CASE WHEN MONTH(e.data_substituicao2) <= '{$busca['mes']}' THEN h.nome ELSE NULL END AS cuidador_sub2"], false);
		$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
		$this->db->join('ei_alocacao c', 'c.id = b.id_alocacao');
		$this->db->join('ei_matriculados_turmas d', 'd.id_matriculado = a.id');
		$this->db->join('ei_alocados_horarios e', 'e.id = d.id_alocado_horario');
		$this->db->join('ei_alocados f', 'f.id = e.id_alocado AND f.id_alocacao_escola = b.id');
		$this->db->join('usuarios g', 'g.id = e.id_cuidador_sub1', 'left');
		$this->db->join('usuarios h', 'h.id = e.id_cuidador_sub2', 'left');
		$this->db->where('c.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('c.depto', $busca['depto']);
		$this->db->where('c.id_diretoria', $busca['diretoria']);
		$this->db->where('c.id_supervisor', $busca['supervisor']);
		$this->db->where('c.ano', $busca['ano']);
		$this->db->where('c.semestre', $semestre);
		$this->db->group_by('a.id');
		$recordsTotal = $this->db->get('ei_matriculados a')->num_rows();


		$sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

		if ($post['search']['value']) {
			$sql .= " WHERE s.municipio LIKE '%{$post['search']['value']}%' OR 
                            s.escola LIKE '%{$post['search']['value']}%' OR 
                            s.ordem_servico LIKE '%{$post['search']['value']}%' OR 
                            s.aluno LIKE '%{$post['search']['value']}%'";
			$recordsFiltered = $this->db->query($sql)->num_rows();
		} else {
			$recordsFiltered = $recordsTotal;
		}


		if ($post['order']) {
			$orderBy = [];
			foreach ($post['order'] as $order) {
				$orderBy[] = intval($order['column'] + 1) . ' ' . $order['dir'];
			}
			$sql .= ' ORDER BY ' . implode(', ', $orderBy);
			if ($post['length'] > 0) {
				$sql .= " LIMIT {$post['start']}, {$post['length']}";
			}
		}
		$matriculados = $this->db->query($sql)->result();

		$this->db->select('a.id_matriculado, a.status');
		$this->db->select("DATE_FORMAT(a.data, '%d') AS dia", false);
		$this->db->select("COUNT(b.id_frequencia) AS total_insumos", false);
		$this->db->join('ei_controle_materiais b', 'b.id_frequencia = a.id', 'left');
		$this->db->where_in('a.id_matriculado', array_column($matriculados, 'id') + [0]);
		$this->db->where("DATE_FORMAT(a.data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
		$this->db->group_by('a.id');
		$rowFrequencias = $this->db->get('ei_frequencias a')->result();

		$frequencias = [];
		$nomeDoStatus = [
			'' => 'Aluno presente',
			'AF' => 'Aluno faltou',
			'AI' => 'Aluno inativo'
		];
		foreach ($rowFrequencias as $rowfrequencia) {
			$frequencias[$rowfrequencia->id_matriculado][intval($rowfrequencia->dia)] = [
				'status' => $rowfrequencia->status,
				'tipo' => $nomeDoStatus[$rowfrequencia->status] ?? null,
				'insumos' => $rowfrequencia->total_insumos
			];
		}


		$data = [];
		foreach ($matriculados as $matriculado) {
			$row = [
				"<strong>Municipio:</strong> {$matriculado->municipio}&emsp;
                <strong>Escola:</strong> {$matriculado->escola}<br>
                <strong>Ordem de serviço:</strong> {$matriculado->ordem_servico} 
                <strong>Cuidador(a):</strong> " . implode('; ', array_filter([$matriculado->cuidador, $matriculado->cuidador_sub1, $matriculado->cuidador_sub2])),
				$matriculado->aluno
			];
			for ($i = 1; $i <= 31; $i++) {
				$row[] = $frequencias[$matriculado->id][$i] ?? [];
			}
			$row[] = $matriculado->id;

			$data[] = $row;
		}


		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semana = [];
		for ($i = 1; $i <= 7; $i++) {
			$semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
		}
		$calendario = [
			'mes' => $busca['mes'],
			'ano' => $busca['ano'],
			'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
			'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
			'semana' => $semana
		];

		$output = [
			'draw' => $this->input->post('draw'),
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'calendar' => $calendario,
			'data' => $data,
		];

		echo json_encode($output);
	}


	//==========================================================================


	public function ajaxListVisitas()
	{
		$post = $this->input->post();

		parse_str($this->input->post('busca'), $busca);

		$semestre = intval($busca['semestre']);

		$this->load->library('Calendar');
		$this->calendar->month_type = 'short';
		$meses = [];
		$nomeMeses = [];
		$mesInicial = $semestre === 2 ? 7 : 1;
		$mesFinal = $semestre === 2 ? 12 : 7;
		for ($i = $mesInicial; $i <= $mesFinal; $i++) {
			$mes = str_pad($i, 2, '0', STR_PAD_LEFT);
			$meses[] = $mes;
			$nomeMeses[] = ucfirst($this->calendar->get_month_name($mes));
		}

		$output = [
			'draw' => intval($this->input->post('draw')),
			'recordsTotal' => 0,
			'recordsFiltered' => 0,
			'meses' => $meses,
			'semestre' => $nomeMeses,
			'data' => [],
		];


		$this->db->select('id');
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$this->db->where('depto', $busca['depto']);
		$this->db->where('id_diretoria', $busca['diretoria']);
		$this->db->where('id_supervisor', $busca['supervisor']);
		$this->db->where('ano', $busca['ano']);
		$this->db->where('semestre', $semestre);
		$alocacao = $this->db->get('ei_alocacao')->row();

		if (empty($alocacao)) {
			echo json_encode($output);
			return;
		}

		$this->db->select('a.id, a.municipio, a.escola');
		$this->db->join('ei_mapa_visitacao b', 'b.id_mapa_unidade = a.id', 'left');
		$this->db->where('a.id_alocacao', $alocacao->id);
		$this->db->group_by('a.id');
		$recordsTotal = $this->db->get('ei_mapa_unidades a')->num_rows();


		$sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

		if ($post['search']['value']) {
			$sql .= " WHERE s.municipio LIKE '%{$post['search']['value']}%' OR 
                            s.escola LIKE '%{$post['search']['value']}%'";
			$recordsFiltered = $this->db->query($sql)->num_rows();
		} else {
			$recordsFiltered = $recordsTotal;
		}


		if ($post['order']) {
			$orderBy = [];
			foreach ($post['order'] as $order) {
				$orderBy[] = intval($order['column'] + 2) . ' ' . $order['dir'];
			}
			$sql .= ' ORDER BY ' . implode(', ', $orderBy);
			if ($post['length'] > 0) {
				$sql .= " LIMIT {$post['start']}, {$post['length']}";
			}
		}
		$visitas = $this->db->query($sql)->result();


		$this->db->select('b.id, a.id_mapa_unidade');
		$this->db->select("COUNT(a.data_visita) AS total_visitas", false);
		$this->db->select("SUM(IF(a.motivo_visita IN (5, 6, 7), 1, 0)) AS total_ocorrencias", false);
		$this->db->select("MONTH(a.data_visita) - IF(c.semestre = 2, 6, 0) AS mes", false);
		$this->db->select("MAX(a.data_visita) AS data_visita", false);
		$this->db->select("SUM(IF(a.motivo_visita = 2, 1, 0)) AS visita_programada", false);
		$this->db->select('a.motivo_visita', false);
		$this->db->join('ei_mapa_unidades b', 'b.id = a.id_mapa_unidade');
		$this->db->join('ei_alocacao c', 'c.id = b.id_alocacao');
		$this->db->where('c.id', $alocacao->id);
		$this->db->group_by(['b.escola', 'MONTH(a.data_visita)']);
		$eventos = $this->db->get('ei_mapa_visitacao a')->result();
		$mesesVisitados = [];
		foreach ($eventos as $evento) {
			$mesesVisitados[$evento->id_mapa_unidade][$evento->mes] = [
				'total_visitas' => $evento->total_visitas,
				'total_ocorrencias' => $evento->total_ocorrencias,
				'data_visita' => $evento->data_visita,
				'motivo_visita' => $evento->motivo_visita
			];
		}

		$data = [];
		foreach ($visitas as $visita) {
			$row = [
				$visita->municipio,
				$visita->escola
			];
			for ($i = 1; $i <= 7; $i++) {
				$row[] = $mesesVisitados[$visita->id][$i]['total_visitas'] ?? null;
			}
			$row[] = $visita->id;
			for ($a = 1; $a <= 7; $a++) {
				$row[] = $mesesVisitados[$visita->id][$a]['total_ocorrencias'] ?? null;
			}
			for ($b = 1; $b <= 7; $b++) {
				$row[] = $mesesVisitados[$visita->id][$b]['data_visita'] ?? null;
			}
			for ($c = 1; $c <= 7; $c++) {
				$row[] = $mesesVisitados[$visita->id][$c]['motivo_visita'] ?? null;
			}

			$data[] = $row;
		}


		$output['recordsTotal'] = intval($recordsTotal);
		$output['recordsFiltered'] = intval($recordsFiltered);
		$output['data'] = $data;

		echo json_encode($output);
	}


	//==========================================================================


	public function ajaxListDiasLetivos()
	{
		parse_str($this->input->post('busca'), $busca);


		$sql = "SELECT s.id,
                       s.id_cuidador,
                       s.cuidador,
                       s.cuidador_sub1,
                       s.cuidador_sub2,
                       s.municipio,
                       s.escola,
                       s.ordem_servico,
                       SUM(total_semanas_mes1) - SUM(faltas_mes1) AS total_dias_mes1,
                       SUM(total_semanas_mes2) - SUM(faltas_mes2) AS total_dias_mes2,
                       SUM(total_semanas_mes3) - SUM(faltas_mes3) AS total_dias_mes3,
                       SUM(total_semanas_mes4) - SUM(faltas_mes4) AS total_dias_mes4,
                       SUM(total_semanas_mes5) - SUM(faltas_mes5) AS total_dias_mes5,
                       SUM(total_semanas_mes6) - SUM(faltas_mes6) AS total_dias_mes6,
                       SUM(total_semanas_mes7) - SUM(faltas_mes7) AS total_dias_mes7
                FROM (SELECT a.id,
                             a.id_cuidador,
                             a.cuidador,
                             f.nome AS cuidador_sub1,
                             g.nome AS cuidador_sub2,
                             b.municipio,
                             b.escola,
                             b.ordem_servico,
                             ROUND(MAX(d.total_semanas_mes1) - IFNULL(MAX(d.desconto_mes1), 0)) AS total_semanas_mes1,
                             ROUND(MAX(d.total_semanas_mes2) - IFNULL(MAX(d.desconto_mes2), 0)) AS total_semanas_mes2,
                             ROUND(MAX(d.total_semanas_mes3) - IFNULL(MAX(d.desconto_mes3), 0)) AS total_semanas_mes3,
                             ROUND(MAX(d.total_semanas_mes4) - IFNULL(MAX(d.desconto_mes4), 0)) AS total_semanas_mes4,
                             ROUND(MAX(d.total_semanas_mes5) - IFNULL(MAX(d.desconto_mes5), 0)) AS total_semanas_mes5,
                             ROUND(MAX(d.total_semanas_mes6) - IFNULL(MAX(d.desconto_mes6), 0)) AS total_semanas_mes6,
                             ROUND(MAX(d.total_semanas_mes7) - IFNULL(MAX(d.desconto_mes7), 0)) AS total_semanas_mes7,
                             SUM(IF((MONTH(e.data) = 1 AND c.semestre = 1) OR (MONTH(e.data) = 7 AND c.semestre = 2), 1, 0)) AS faltas_mes1,
                             SUM(IF((MONTH(e.data) = 2 AND c.semestre = 1) OR (MONTH(e.data) = 8 AND c.semestre = 2), 1, 0)) AS faltas_mes2,
                             SUM(IF((MONTH(e.data) = 3 AND c.semestre = 1) OR (MONTH(e.data) = 9 AND c.semestre = 2), 1, 0)) AS faltas_mes3,
                             SUM(IF((MONTH(e.data) = 4 AND c.semestre = 1) OR (MONTH(e.data) = 10 AND c.semestre = 2), 1, 0)) AS faltas_mes4,
                             SUM(IF((MONTH(e.data) = 5 AND c.semestre = 1) OR (MONTH(e.data) = 11 AND c.semestre = 2), 1, 0)) AS faltas_mes5,
                             SUM(IF((MONTH(e.data) = 6 AND c.semestre = 1) OR (MONTH(e.data) = 12 AND c.semestre = 2), 1, 0)) AS faltas_mes6,
                             SUM(IF(MONTH(e.data) = 7 AND c.semestre = 1, 1, 0)) AS faltas_mes7
                      FROM ei_alocados a
                      INNER JOIN ei_alocacao_escolas b ON b.id = a.id_alocacao_escola
                      INNER JOIN ei_alocacao c ON c.id = b.id_alocacao
                      LEFT JOIN ei_alocados_horarios d ON d.id_alocado = a.id
                      LEFT JOIN ei_apontamento e ON e.id_alocado = a.id AND e.status IN ('FA', 'PV', 'FE', 'EM', 'RE') AND DATE_FORMAT(e.data, '%w') = d.dia_semana
                      LEFT JOIN usuarios f ON f.id = d.id_cuidador_sub1
                      LEFT JOIN usuarios g ON g.id = d.id_cuidador_sub2
                      WHERE c.id_empresa = '{$this->session->userdata('empresa')}'
                            AND c.depto = '{$busca['depto']}'
                            AND c.id_diretoria = '{$busca['diretoria']}'
                            AND c.id_supervisor = '{$busca['supervisor']}'
                            AND c.ano = '{$busca['ano']}'
                            AND c.semestre = '{$busca['semestre']}'
                      GROUP BY a.cuidador, d.dia_semana) s
                GROUP BY s.cuidador";


		$config = [
			'search' => ['cuidador', 'escola', 'municipio', 'ordem_servico'],
			'order' => ['cuidador']
		];
		$this->load->library('dataTables', $config);


		$output = $this->datatables->query($sql);


		$data = [];

		foreach ($output->data as $alocado) {
			$row = [
				"<strong>Municipio:</strong> {$alocado->municipio}&emsp;
                 <strong>Escola:</strong> {$alocado->escola}<br>
                 <strong>Ordem de serviço:</strong> {$alocado->ordem_servico}",
				implode(';<br>', array_filter([$alocado->cuidador, $alocado->cuidador_sub1, $alocado->cuidador_sub2]))
			];
			$total = [
				$alocado->total_dias_mes1,
				$alocado->total_dias_mes2,
				$alocado->total_dias_mes3,
				$alocado->total_dias_mes4,
				$alocado->total_dias_mes5,
				$alocado->total_dias_mes6,
				$alocado->total_dias_mes7
			];
			$row = array_merge($row, $total);
			$row[] = array_sum($total);

			$data[] = $row;
		}

		$output->data = $data;


		$semestre = intval($busca['semestre']);

		$this->load->library('Calendar');
		$this->calendar->month_type = 'short';
		$meses = [];
		$nomeMeses = [];
		$mesInicial = $semestre === 2 ? 7 : 1;
		$mesFinal = $semestre === 2 ? 12 : 7;
		for ($i = $mesInicial; $i <= $mesFinal; $i++) {
			$mes = str_pad($i, 2, '0', STR_PAD_LEFT);
			$meses[] = $mes;
			$nomeMeses[] = ucfirst($this->calendar->get_month_name($mes));
		}

		$output->semestre = $nomeMeses;
		$output->meses = $meses;


		echo json_encode($output);
	}


	//==========================================================================


	public function ajaxListBancoHoras()
	{
		parse_str($this->input->post('busca'), $busca);

		$idMes = intval($busca['mes']) - ($busca['semestre'] === '2' ? 6 : 0);


		$alocacao = $this->db
			->select("id", false)
			->where('id_usuario', $busca['supervisor'])
			->where('ano', $busca['ano'])
			->where('semestre', $busca['semestre'])
			->get('ei_coordenacao')
			->row();

		$bancoHoras = $this->db
			->select("id, saldo_mes{$idMes} AS saldo_mes", false)
			->select('saldo_mes1, saldo_mes2, saldo_mes3, saldo_mes4')
			->select('saldo_mes5, saldo_mes6, saldo_mes7')
			->where('id_supervisao', $alocacao->id ?? '')
			->get('ei_saldo_banco_horas')
			->row();

		$query = $this->db
			->select('a.data')
			->select(["TIME_FORMAT(a.horario_entrada, '%H:%i') AS horario_entrada"], false)
			->select(["TIME_FORMAT(a.horario_saida, '%H:%i') AS horario_saida"], false)
			->select(["TIME_FORMAT(a.horario_entrada_1, '%H:%i') AS horario_entrada_1"], false)
			->select(["TIME_FORMAT(a.horario_saida_1, '%H:%i') AS horario_saida_1"], false)
			->select(["TIME_FORMAT(a.total, '%H:%i') AS total"], false)
			->select(["TIME_FORMAT(a.saldo_dia, '%H:%i') AS saldo_dia"], false)
			->select('a.observacoes, a.id')
			->select(["DATE_FORMAT(a.data, '%d/%m/%Y') AS data_de"], false)
			->join('ei_coordenacao b', 'b.id = a.id_supervisao')
			->where('b.id', $alocacao->id ?? null)
			->where('MONTH(a.data)', $busca['mes'])
			->where('YEAR(a.data)', $busca['ano'])
			->get('ei_carga_horaria a');


		$this->load->helper('time');

		$this->load->library('dataTables');

		$output = $this->datatables->generate($query);


		$data = [];

		foreach ($output->data as $row) {
			$data[] = [
				$row->data_de,
				$row->horario_entrada,
				$row->horario_saida,
				$row->horario_entrada_1,
				$row->horario_saida_1,
				$row->total,
				$row->saldo_dia,
				nl2br($row->observacoes),
				'<button class="btn btn-sm btn-info" onclick="edit_banco_hora(' . $row->id . ');" title="Editar evento"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_banco_hora(' . $row->id . ');" title="Excluir evento"><i class="glyphicon glyphicon-trash"></i></button>'
			];
		}

		$coordenacao = $this->db
			->select(["TIME_FORMAT(carga_horaria, '%H:%i') AS carga_horaria"], false)
			->select('saldo_acumulado_horas')
			->where('id_usuario', $busca['supervisor'])
			->where('ano', $busca['ano'])
			->where('semestre', $busca['semestre'])
			->get('ei_coordenacao')
			->row();

		$saldoAcumulado = timeToSec($coordenacao->saldo_acumulado_horas ?? '');
		$saldoMes = timeToSec($bancoHoras->saldo_mes ?? '');


		$output->saldo_mes = secToTime($saldoMes, false);
		$output->saldo_acumulado = secToTime($saldoAcumulado, false);

		$output->carga_horaria = $coordenacao->carga_horaria ?? '';


		$dias = array_map(function ($d) {
			return str_pad($d, 2, '0', 0);
		}, range(1, date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano']))));

		$output->dias = form_dropdown('', array_combine($dias, $dias), 1);

		$output->data = $data;

		echo json_encode($output);
	}


	//==========================================================================


	public function ajaxEdit()
	{
		$date = $this->input->post('data');
		$idAlocado = $this->input->post('id_alocado');
		$periodo = $this->input->post('periodo');

		$usuario = $this->db
			->select('b.id_cuidador, b.cuidador')
			->select(["IF(a.data_substituicao2 IS NOT NULL AND a.data_substituicao2 <= '{$date}', a.id_cuidador_sub2, IF(a.data_substituicao1 IS NOT NULL AND a.data_substituicao1 <= '{$date}', a.id_cuidador_sub1, b.id_cuidador)) AS id"], false)
			->select(["IF(a.data_substituicao2 IS NOT NULL AND a.data_substituicao2 <= '{$date}', d.nome, IF(a.data_substituicao1 IS NOT NULL AND a.data_substituicao1 <= '{$date}', c.nome, b.cuidador)) AS nome"], false)
			->join('ei_alocados_horarios a', 'b.id = a.id_alocado', 'left')
			->join('usuarios c', 'c.id = a.id_cuidador_sub1', 'left')
			->join('usuarios d', 'd.id = a.id_cuidador_sub2', 'left')
			->where('b.id', $idAlocado)
			->where('a.periodo', $periodo)
//			->where("a.dia_semana = DATE_FORMAT('{$date}', '%w') OR a.dia_semana IS NULL")
			->group_by('b.id')
			->get('ei_alocados b')
			->row();

		$this->db->start_cache();
		$this->db->select('a.id AS id_alocado, b.escola, b.municipio, b.ordem_servico');
		$this->db->select('a.id_cuidador, a.cuidador, c.id_usuario, c.id_alocado_sub1, c.id_alocado_sub2');
		$this->db->select('c.id, c.periodo, c.status, c.ocorrencia_cuidador, c.ocorrencia_aluno, c.ocorrencia_professor');
		$this->db->select("DATE_FORMAT(c.data, '%d/%m/%Y') AS data", false);
		$this->db->select("TIME_FORMAT(c.desconto, '%H:%i') AS desconto", false);
		$this->db->select("TIME_FORMAT(c.desconto_sub1, '%H:%i') AS desconto_sub1", false);
		$this->db->select("TIME_FORMAT(c.desconto_sub2, '%H:%i') AS desconto_sub2", false);
		$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
		$this->db->where('a.id', $idAlocado);
		$this->db->stop_cache();
		$this->db->join('ei_apontamento c', "c.id_alocado = a.id AND c.data = '{$date}' AND c.periodo = '{$periodo}'", 'left');
		$data = $this->db->get('ei_alocados a')->row();

		if (!isset($data->id)) {
			$this->db->join('ei_apontamento c', "c.id_alocado = a.id AND c.data = '{$date}' AND c.periodo IS NULL", 'left');
			$data = $this->db->get('ei_alocados a')->row();
		}

		if (empty($data->data)) {
			$data->data = date('d/m/Y', strtotime(str_replace('-', '/', $date)));
		}


		$this->db->flush_cache();
		$cuidadoresSub = $this->db
			->select('a.id, a.nome')
			->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto', 'left')
			->where('a.empresa', $this->session->userdata('empresa'))
			->where('a.tipo', 'funcionario')
			->where("a.depto = 'Educação Inclusiva'")
			->where('a.status', 1)
			->order_by('a.nome', 'asc')
			->get('usuarios a')
			->result();

		$cuidadores = ['' => $data->cuidador] + array_column($cuidadoresSub, 'nome', 'id');

		$data->id_usuarios = form_dropdown('', [$usuario->id => $usuario->nome], $usuario->id);
		$cuidadores[''] = 'selecione...';
		$data->id_alocado_sub1 = form_dropdown('', $cuidadores, $data->id_alocado_sub1);
		$data->id_alocado_sub2 = form_dropdown('', $cuidadores, $data->id_alocado_sub2);

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditCuidador()
	{
		$idAlocado = $this->input->post('id_alocado');


		$this->db->select('c.id, c.depto, c.id_diretoria, c.ano, c.semestre, a.id_cuidador, a.cuidador');
		$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
		$this->db->join('ei_alocacao c', 'c.id = b.id_alocacao');
		$this->db->where('a.id', $idAlocado);
		$alocacao = $this->db->get('ei_alocados a')->row();


		$sql = "SELECT a.id, a.nome, CONCAT(a.cargo, '/', a.funcao) AS cargo, a.funcao 
                FROM usuarios a
                INNER JOIN ei_ordem_servico_profissionais b ON b.id_usuario = a.id
                INNER JOIN ei_ordem_servico_escolas c ON c.id = b.id_ordem_servico_escola
                INNER JOIN ei_ordem_servico d ON d.id = c.id_ordem_servico
                INNER JOIN ei_contratos e ON e.id = d.id_contrato
                INNER JOIN ei_diretorias f ON f.id = e.id_cliente
                WHERE f.id = '{$alocacao->id_diretoria}' AND 
                      f.depto = '{$alocacao->depto}' AND 
                      d.ano = '{$alocacao->ano}' AND 
                      d.semestre = '{$alocacao->semestre}' AND 
                      a.id NOT IN (SELECT x.id_cuidador
                                   FROM ei_alocados x 
                                   INNER JOIN ei_alocacao_escolas y 
                                              ON y.id = x.id_alocacao_escola
                                   WHERE y.id_alocacao = '{$alocacao->id}')
                ORDER BY a.nome ASC";
		$rows = $this->db->query($sql)->result();
		$idProfissionais = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');
		$cargoFuncao = ['' => 'Todos'] + array_column($rows, 'cargo', 'funcao');
		$municipio = ['' => 'Todos'] + array_column($rows, 'municipio', 'municipio');

		$data = ['cuidador_antigo' => $alocacao->cuidador];

		$data['cargo_funcao'] = form_dropdown('', $cargoFuncao, '');

		$data['municipio'] = form_dropdown('', $municipio, '');

		$data['id_cuidador'] = form_dropdown('', $idProfissionais, $alocacao->id_cuidador);


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditDisciplinaAluno()
	{
		$data = $this->db
			->select('id, id_curso, id_disciplina, media_semestral')
			->select(["CONCAT(aluno, '<br>', modulo) AS dados"], false)
			->where('id', $this->input->post('id_matriculado'))
			->get('ei_matriculados')
			->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'Aluno matriculado não encontrado.']));
		}

		$arrDisciplinas = $this->db
			->select('id, nome')
			->where('id_curso', $data->id_curso)
			->get('ei_disciplinas')
			->result();

		$disciplinas = ['' => 'selecione...'] + array_column($arrDisciplinas, 'nome', 'id');
		$data->disciplinas = form_dropdown('', $disciplinas, $data->id_curso);

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxFiltrarCuidador()
	{
		$idAlocado = $this->input->post('id');
		$cargoFuncao = $this->input->post('cargo_funcao');
		$municipio = $this->input->post('municipio');


		$this->db->select('c.id, c.depto, c.id_diretoria, c.ano, c.semestre, a.id_cuidador, a.cuidador');
		$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
		$this->db->join('ei_alocacao c', 'c.id = b.id_alocacao');
		$this->db->where('a.id', $idAlocado);
		$alocacao = $this->db->get('ei_alocados a')->row();


		$sql = "SELECT a.id, a.nome
                FROM usuarios a
                INNER JOIN ei_ordem_servico_profissionais b ON b.id_usuario = a.id
                INNER JOIN ei_ordem_servico_escolas c ON c.id = b.id_ordem_servico_escola
                INNER JOIN ei_ordem_servico d ON d.id = c.id_ordem_servico
                INNER JOIN ei_contratos e ON e.id = d.id_contrato
                INNER JOIN ei_diretorias f ON f.id = e.id_cliente
                WHERE f.id = '{$alocacao->id_diretoria}' AND 
                      f.depto = '{$alocacao->depto}' AND 
                      d.ano = '{$alocacao->ano}' AND 
                      d.semestre = '{$alocacao->semestre}' AND 
                      a.id NOT IN (SELECT x.id_cuidador 
                                   FROM ei_alocados x 
                                   INNER JOIN ei_alocacao_escolas y 
                                              ON y.id = x.id_alocacao_escola
                                   WHERE y.id_alocacao = '{$alocacao->id}') AND 
                      (a.funcao = '{$cargoFuncao}' OR CHAR_LENGTH('{$cargoFuncao}') = 0) AND
                      (a.municipio = '{$municipio}' OR CHAR_LENGTH('{$municipio}') = 0)
                ORDER BY a.nome ASC";

		$rows = $this->db->query($sql)->result();


		$idProfissionais = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');


		$data['id_cuidador'] = form_dropdown('', $idProfissionais, $alocacao->id_cuidador);


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditFaturamento()
	{
		$data = $this->db
			->select('a.id, a.dia_semana, b.cuidador, c.escola, c.municipio, d.ano, d.semestre')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->where('a.id', $this->input->post('id_horario'))
			->get('ei_alocados_horarios a')
			->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'O horário alocado não existe ou foi desalocado do semestre.']));
		}

		$data->mes = $this->input->post('mes');
		$idMes = $data->mes - ($data->semestre > 1 ? 6 : 0);
		$sub = $this->input->post('substituto');


		$horario = $this->db
			->select(($sub == '2' ? 'desconto_sub2' : ($sub == '1' ? 'desconto_sub2' : "desconto_mes{$idMes}") . ' AS desconto'), false)
			->select(($sub == '2' ? 'endosso_sub2' : ($sub == '1' ? 'endosso_sub2' : "endosso_mes{$idMes}") . ' AS endosso'), false)
			->where('id', $data->id)
			->get('ei_alocados_horarios')
			->row();


		$data->desconto = str_replace('.', ',', $horario->desconto);
		$data->endosso = str_replace('.', ',', $horario->desconto - $horario->endosso);
		$data->endosso_original = $horario->endosso;

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditDataRealTotalizacao()
	{
		$idAlocado = $this->input->post('id_alocado');
		$periodo = $this->input->post('periodo');


		$this->db->select('f.semestre');
		$this->db->select(["DATE_FORMAT(IFNULL(data_inicio_real, MIN(a.data_inicio)), '%d/%m/%Y') AS data_inicio_real"], false);
		$this->db->select(["IFNULL(DATE_FORMAT(data_termino_real, '%d/%m/%Y'), '00/00/0000') AS data_termino_real"], false);
		$this->db->join('ei_matriculados_turmas b', 'b.id_matriculado = a.id');
		$this->db->join('ei_alocados_horarios c', 'c.id = b.id_alocado_horario');
		$this->db->join('ei_alocados d', 'd.id = c.id_alocado AND d.id_alocacao_escola = a.id_alocacao_escola');
		$this->db->join('ei_alocacao_escolas e', 'e.id = d.id_alocacao_escola');
		$this->db->join('ei_alocacao f', 'f.id = e.id_alocacao');
		$this->db->where('d.id', $idAlocado);
		$this->db->where('c.periodo', $periodo);
		$this->db->group_by('d.id');
		$data = $this->db->get('ei_matriculados a')->row();


		if (empty($data)) {
			exit(json_encode(['erro' => 'Nenhum aluno alocado.']));
		}


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditTotalSemanasMes()
	{
		$idAlocado = $this->input->post('id_alocado');
		$periodo = $this->input->post('periodo');


		$this->db->select("DATE_FORMAT(IFNULL(data_inicio_real, MIN(a.data_inicio)), '%d/%m/%Y') AS data_inicio_real", false);
		$this->db->select("IFNULL(DATE_FORMAT(data_termino_real, '%d/%m/%Y'), '00/00/0000') AS data_termino_real", false);
		$this->db->join('ei_matriculados_turmas b', 'b.id_matriculado = a.id');
		$this->db->join('ei_alocados_horarios c', 'c.id = b.id_alocado_horario');
		$this->db->join('ei_alocados d', 'd.id = c.id_alocado AND d.id_alocacao_escola = a.id_alocacao_escola');
		$this->db->where('d.id', $idAlocado);
		$this->db->where('c.periodo', $periodo);
		$this->db->group_by('d.id');
		$data = $this->db->get('ei_matriculados a')->row();


		if (empty($data)) {
			exit(json_encode(['erro' => 'Nenhum aluno alocado.']));
		}


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditDesalocacao()
	{
		$idAlocado = $this->input->post('id_alocado');
		$periodo = $this->input->post('periodo');


		$this->db->select("c.escola, GROUP_CONCAT(DISTINCT e.aluno ORDER BY e.aluno SEPARATOR ', ') AS alunos", false);
		$this->db->select("(CASE a.periodo WHEN 0 THEN 'madrugada' WHEN 1 THEN 'manhã' WHEN 2 THEN 'tarde' WHEN 3 THEN 'noite' END) AS periodo", false);
		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_matriculados_turmas d', 'd.id_alocado_horario = a.id', 'left');
		$this->db->join('ei_matriculados e', 'e.id = d.id_matriculado AND e.id_alocacao_escola = c.id', 'left');
		$this->db->where('b.id', $idAlocado);
		$this->db->where('a.periodo', $periodo);
		$this->db->group_by('c.id');
		$data = $this->db->get('ei_alocados_horarios a')->row();


		if (empty($data)) {
			exit(json_encode(['erro' => 'Nenhum dado alocado encontrado.']));
		}


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditHorario()
	{
		$data = $this->db
			->select('a.id, a.dia_semana, b.cuidador, c.escola, c.municipio, d.ano, d.semestre')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->where('a.id', $this->input->post('id_horario'))
			->get('ei_alocados_horarios a')
			->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'O horário alocado não existe ou foi desalocado do semestre.']));
		}

		$mes = str_pad($this->input->post('mes'), 2, '0', STR_PAD_LEFT);
		$idMes = (int)$mes - ($data->semestre > 1 ? 6 : 0);

		$horario = $this->db
			->select(["TIME_FORMAT(horario_inicio_mes{$idMes}, '%H:%i') AS horario_inicio"], false)
			->select(["TIME_FORMAT(horario_termino_mes{$idMes}, '%H:%i') AS horario_termino"], false)
			->where('id', $data->id)
			->get('ei_alocados_horarios')
			->row();

		$data->horario_inicio = $horario->horario_inicio;
		$data->horario_termino = $horario->horario_termino;

		$this->load->library('calendar');
		$nomeMes = $this->calendar->get_month_name($mes);
		$diasSemana = $this->calendar->get_day_names('long');
		$data->mes_ano = ucfirst($nomeMes) . '/' . $data->ano;
		$data->horario_semana = $diasSemana[$data->dia_semana] . ', ' . $data->horario_inicio . ' às ' . $data->horario_termino;

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditSubstituto()
	{
		$idHorario = $this->input->post('id_horario');

		$this->db->select('a.id, a.dia_semana, c.escola, b.cuidador, a.funcao, c.municipio, d.ano, d.semestre');
		$this->db->select('a.id_cuidador_sub1, a.funcao_sub1', false);
		$this->db->select("DATE_FORMAT(a.data_substituicao1, '%d/%m/%Y') AS data_substituicao1", false);
		$this->db->select('a.id_cuidador_sub2, a.funcao_sub2', false);
		$this->db->select("DATE_FORMAT(a.data_substituicao2, '%d/%m/%Y') AS data_substituicao2", false);
		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_alocacao d', 'd.id = c.id_alocacao');
		$this->db->where('a.id', $idHorario);
		$data = $this->db->get('ei_alocados_horarios a')->row();


		if (empty($data)) {
			exit(json_encode(['erro' => 'O colaborador não existe ou foi desalocado do semestre.']));
		}

		$mes = $this->input->post('mes');
		$idMes = (int)$mes - ($data->semestre > 1 ? 6 : 0);

		$horario = $this->db
			->select(["TIME_FORMAT(horario_inicio_mes{$idMes}, '%H:%i') AS horario_inicio"], false)
			->select(["TIME_FORMAT(horario_termino_mes{$idMes}, '%H:%i') AS horario_termino"], false)
			->where('id', $idHorario)
			->get('ei_alocados_horarios')
			->row();

		$data->horario_inicio = $horario->horario_inicio;
		$data->horario_termino = $horario->horario_termino;


		$this->load->library('calendar');
		$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
		$nomeMes = $this->calendar->get_month_name($mes);
		$diasSemana = $this->calendar->get_day_names('long');
		$data->mes_ano = ucfirst($nomeMes) . '/' . $data->ano;
		$data->horario_semana = $diasSemana[$data->dia_semana] . ', ' . $data->horario_inicio . ' às ' . $data->horario_termino;


		$this->db->select('municipio');
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('tipo', 'funcionario');
		$this->db->where('CHAR_LENGTH(municipio) >', 0);
		$this->db->order_by('municipio', 'asc');
		$municipios = $this->db->get('usuarios')->result();


		$municipioSub = ['' => 'Todos'] + array_column($municipios, 'municipio', 'municipio');


		$this->db->select('id, nome');
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('tipo', 'funcionario');
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();


		$idCuidadorSub = ['' => 'selecione...'] + array_column($usuarios, 'nome', 'id');


		$this->db->select('a.nome');
		$this->db->join('usuarios b', 'b.funcao = a.nome');
		$this->db->where('b.empresa', $this->session->userdata('empresa'));
		$this->db->where('b.depto', 'Educação Inclusiva');
		$this->db->group_by('a.id');
		$this->db->order_by('a.nome', 'asc');
		$funcoes = $this->db->get('empresa_funcoes a')->result();


		$funcoesSub = ['' => 'selecione...'] + array_column($funcoes, 'nome', 'nome');


		$data->id_cuidador_sub1 = form_dropdown('', $idCuidadorSub, $data->id_cuidador_sub1);
		$data->id_cuidador_sub2 = form_dropdown('', $idCuidadorSub, $data->id_cuidador_sub2);
		$data->municipio_sub1 = form_dropdown('', $municipioSub, '');
		$data->municipio_sub2 = form_dropdown('', $municipioSub, '');
		$data->funcao_sub1 = form_dropdown('', $funcoesSub, $data->funcao_sub1);
		$data->funcao_sub2 = form_dropdown('', $funcoesSub, $data->funcao_sub2);


		echo json_encode($data);
	}


	//==========================================================================


	public function atualizarSubstituto()
	{
		$municipio = $this->input->post('municipio');
		$idUsuario = $this->input->post('id_usuario');


		$this->db->select('id, nome');
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('tipo', 'funcionario');
		if ($municipio) {
			$this->db->where('municipio', $municipio);
		}
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();


		$idCuidadorSub = ['' => 'selecione...'] + array_column($usuarios, 'nome', 'id');

		$data['usuario'] = form_dropdown('', $idCuidadorSub, $idUsuario);


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditTotalizacao()
	{
		$alocado = $this->db
			->select('a.id, c.semestre')
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->join('ei_alocacao c', 'c.id = b.id_alocacao')
			->where('a.id', $this->input->post('id_alocado'))
			->get('ei_alocados a')
			->row();

		if (empty($alocado)) {
			exit(json_encode(['erro' => 'O mês alocado não existe ou foi desalocado do semestre.']));
		}


		$mes = $this->input->post('mes');
		$idMes = (int)$mes - ($alocado->semestre > 1 ? 6 : 0);
		$periodo = $this->input->post('periodo');
		$substituto = $this->input->post('substituto');


		$usuario = $this->db
			->select(["(CASE WHEN '{$mes}' > MONTH(a.data_substituicao1) AND a.data_substituicao1 IS NOT NULL THEN a.id_cuidador_sub1 ELSE b.id_cuidador END) AS id"], false)
			->select('b.id_cuidador, a.id_cuidador_sub1, a.id_cuidador_sub2')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->where('b.id', $alocado->id)
			->where('a.periodo', $periodo)
			->get('ei_alocados_horarios a')
			->row();


//		$this->db->select('f.id, a.id_alocado, a.periodo, c.id_alocacao, c.id_escola, c.escola, b.cuidador, e.cargo, e.funcao');
		$this->db->select('f.id, a.id_alocado, a.periodo, c.id_alocacao, c.id_escola, e.cargo, e.funcao');
		$this->db->select("'{$mes}' AS mes", false);
		if ($substituto) {
			$this->db->select('f.observacoes_sub1 AS observacoes', false);
			$this->db->select("DATE_FORMAT(f.data_aprovacao_sub1, '%d/%m/%Y') AS data_aprovacao", false);
			$this->db->select("DATE_FORMAT(IFNULL(f.data_impressao_sub1, NOW()), '%d/%m/%Y') AS data_impressao", false);
		} else {
			$this->db->select("f.observacoes_mes{$idMes} AS observacoes", false);
			$this->db->select("DATE_FORMAT(f.data_aprovacao_mes{$idMes}, '%d/%m/%Y') AS data_aprovacao", false);
			$this->db->select("DATE_FORMAT(IFNULL(f.data_impressao_mes{$idMes}, NOW()), '%d/%m/%Y') AS data_impressao", false);
		}
		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_alocacao d', 'd.id = c.id_alocacao');
		$this->db->join('ei_alocados_horarios e', 'e.id_alocado = b.id AND e.periodo = a.periodo');
		$this->db->join('ei_faturamento f', 'f.id_alocacao = d.id AND f.id_escola = c.id_escola AND f.cargo = e.cargo AND f.funcao = e.funcao', 'left');
		$this->db->where('b.id', $alocado->id);
		$this->db->where('a.periodo', $periodo);
		$this->db->where('a.id_cuidador', $usuario->id);
		$this->db->group_by(['c.id_escola', 'e.cargo', 'e.funcao']);
		$data = $this->db->get('ei_alocados_totalizacao a')->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'O colaborador não existe ou foi desalocado do semestre.']));
		}

		$data->planilha_faturamento = $this->planilhaFaturamento($alocado->id, $mes, $periodo);


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxRecuperarTotalizacao()
	{
		$idAlocado = $this->input->post('id_alocado');
		$alocado = $this->db
			->select('a.id, c.semestre')
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->join('ei_alocacao c', 'c.id = b.id_alocacao')
			->where('a.id', $idAlocado)
			->get('ei_alocados a')
			->row();

		$mes = $this->input->post('mes');
		$idMes = (int)$mes - ($alocado->semestre > 1 ? 6 : 0);
		$periodo = $this->input->post('periodo');

		$this->db->select("observacoes_mes{$idMes} AS observacoes", false);
		$this->db->select("DATE_FORMAT(data_aprovacao_mes{$idMes}, '%d/%m/%Y') AS data_aprovacao", false);
		$this->db->where('id', $this->input->post('id'));
		$row = $this->db->get('ei_faturamento')->row();

		$data['data_aprovacao'] = $row->data_aprovacao ?? '';
		$data['observacoes'] = $row->observacoes ?? '';
		$data['planilha_faturamento'] = $this->planilhaFaturamento($idAlocado, $mes, $periodo, false, true);

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxRecuperarPagamentoPrestador()
	{
		$idHorario = $this->input->post('id_horario');
		$mes = $this->input->post('mes');
		$substituto = $this->input->post('substituto');
		$recuperar = $this->input->post('recuperar');

		$row = $this->db->select('a.id_alocado, d.semestre, d.ano')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->where('a.id', $idHorario)
			->get('ei_alocados_horarios a')
			->row();

		if (empty($row)) {
			exit(json_encode(['erro' => 'O colaborador não existe ou foi desalocado do semestre.']));
		}

		$row->mes = (int)$mes - ($row->semestre > 1 ? 6 : 0);

		$data['planilha_pagamento_prestador'] = $this->planilhaPagamentoPrestador($idHorario, $row->mes, $row->ano, false, $recuperar);


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditAjusteMensal()
	{
		$idAlocado = $this->input->post('id_alocado');

		$alocacao = $this->db
			->select('c.semestre')
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->join('ei_alocacao c', 'c.id = b.id_alocacao')
			->where('a.id', $idAlocado)
			->get('ei_alocados a')
			->row();

		$idMes = intval($this->input->post('mes')) - (($alocacao->semestre ?? 1) > 1 ? 6 : 0);
		$periodo = $this->input->post('periodo');
		$substituto = $this->input->post('substituto');


		$this->db->select("id, '{$idMes}' AS mes", false);
		if ($substituto === '2') {
			$this->db->select("TIME_FORMAT(horas_descontadas_sub2, '%H:%i') AS horas_descontadas", false);
		} elseif ($substituto === '1') {
			$this->db->select("TIME_FORMAT(horas_descontadas_sub1, '%H:%i') AS horas_descontadas", false);
		} else {
			$this->db->select("TIME_FORMAT(horas_descontadas_mes{$idMes}, '%H:%i') AS horas_descontadas", false);
		}
		$this->db->where('id_alocado', $idAlocado);
		$this->db->where('periodo', $periodo);
		$data = $this->db->get('ei_alocados_totalizacao')->row();


		if (empty($data)) {
			exit(json_encode(['erro' => 'O colaborador não existe ou foi desalocado do semestre.']));
		}


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditPagamentoPrestador()
	{
		$horario = $this->db
			->select('a.id, c.id_alocacao, b.id AS id_alocado, b.id_cuidador, b.cuidador, a.periodo, d.ano, d.semestre')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->where('a.id', $this->input->post('id_horario'))
			->get('ei_alocados_horarios a')
			->row();

		if (empty($horario)) {
			exit(json_encode(['erro' => 'O horário alocado não existe ou foi desalocado do semestre.']));
		}

		$mes = (int)$this->input->post('mes');
		$idMes = $mes - ($horario->semestre > 1 ? 6 : 0);
		$substituto = $this->input->post('substituto');


		if ($substituto) {
			$this->db
				->select("d.id, d.nota_fiscal_sub AS numero_nota_fiscal", false)
				->select("FORMAT(d.valor_extra1_sub, 2, 'de_DE') AS valor_extra_1", false)
				->select("FORMAT(d.valor_extra2_sub, 2, 'de_DE') AS valor_extra_2", false)
				->select("d.justificativa1_sub AS justificativa_1", false)
				->select("d.justificativa2_sub AS justificativa_2", false)
				->select("DATE_FORMAT(d.data_liberacao_pagto_sub, '%d/%m/%Y') AS data_liberacao_pagto", false);
		} else {
			$this->db
				->select("d.id, d.nota_fiscal_mes{$idMes} AS numero_nota_fiscal", false)
				->select("FORMAT(d.valor_extra1_mes{$idMes}, 2, 'de_DE') AS valor_extra_1", false)
				->select("FORMAT(d.valor_extra2_mes{$idMes}, 2, 'de_DE') AS valor_extra_2", false)
				->select("d.justificativa1_mes{$idMes} AS justificativa_1", false)
				->select("d.justificativa2_mes{$idMes} AS justificativa_2", false)
				->select("DATE_FORMAT(d.data_liberacao_pagto_mes{$idMes}, '%d/%m/%Y') AS data_liberacao_pagto", false);
		}
		$data = $this->db
			->select("DATE_FORMAT(MIN(h.data_inicio_contrato), '%d/%m/%Y') AS data_inicio_contrato", false)
			->select("DATE_FORMAT(MAX(h.data_termino_contrato), '%d/%m/%Y') AS data_termino_contrato", false)
			->select(["IF({$mes} IN (1, 8), d.pagamento_proporcional_inicio, IF({$mes} IN (7, 12), d.pagamento_proporcional_termino, 0)) AS pagamento_proporcional"], false)
			->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id')
			->join('ei_alocados c', 'c.id_alocacao_escola = b.id')
			->join('ei_pagamento_prestador d', 'd.id_alocacao = a.id AND d.id_cuidador = c.id_cuidador', 'left')
			->join('ei_alocados_horarios e', 'e.id_alocado = c.id', 'left')
			->join('ei_matriculados_turmas f', 'f.id_alocado_horario = e.id', 'left')
			->join('ei_matriculados g', 'g.id = f.id_matriculado AND g.id_alocacao_escola = b.id', 'left')
			->join('ei_ordem_servico_horarios h', 'h.id = e.id_os_horario', 'left')
			->where('a.id', $horario->id_alocacao)
			->where('c.id_cuidador', $horario->id_cuidador)
			->group_by('a.id')
			->get('ei_alocacao a')
			->row();

		$data->planilha_pagamento_prestador = $this->planilhaPagamentoPrestador($horario->id, $idMes, $horario->ano);
		$data->mes = $mes;

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditControleMateriais()
	{
		$id_matriculado = $this->input->post('id_matriculado');
		$date = $this->input->post('date');


		$this->db->select('c.id AS id_frequencia, a.aluno, b.escola, b.municipio, b.ordem_servico, c.status');
		$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
		$this->db->join('ei_frequencias c', "c.id_matriculado = a.id AND c.data = '{$date}'", 'left');
		$this->db->where('a.id', $id_matriculado);
		$data = $this->db->get('ei_matriculados a')->row();


		if ($data->id_frequencia) {
			$this->db->select('a.id, a.nome, a.tipo, IFNULL(b.qtde, 0) AS qtde, b.id_frequencia', false);
			$this->db->join('ei_controle_materiais b', "b.id_insumo = a.id AND b.id_frequencia = '{$data->id_frequencia}'", 'left');
			$this->db->join('ei_frequencias c', 'c.id = b.id_frequencia', 'left');
		} else {
			$this->db->select('a.id, a.nome, a.tipo, 0 AS qtde', false);
		}
		$this->db->where('a.id_empresa', $this->session->userdata('empresa'));
		$this->db->order_by('a.id', 'asc');
		$rows = $this->db->get('ei_insumos a')->result();


		$this->load->library('table');
		$this->table->set_template([
			'table_open' => '<table class="table table-condensed" width="100%">'
		]);


		if (count($rows) > 0) {
			foreach ($rows as $row) {
				$this->table->add_row(
					$row->nome, form_input([
					'name' => "qtde_insumos[{$row->id}]",
					'value' => $row->qtde,
					'type' => 'number',
					'class' => 'form-control qtde_insumos text-right input-sm',
					'style' => 'width: 100px;'
				]), $row->tipo);
			}
		} else {
			$this->table->add_row('<span class="text-center">Nenhum insumo encontrado.</span>');
		}


		$data->qtde_insumos = $this->table->generate();


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxVisitas()
	{
		$idMapaUnidade = $this->input->post('id_mapa_unidade');
		$idMes = $this->input->post('id_mes');


		$this->db->select(["a.id, CONCAT(DATE_FORMAT(a.data_visita, '%d/%m/%Y'), ' - ', b.escola) AS nome"], false);
		$this->db->join('ei_mapa_unidades b', 'b.id = a.id_mapa_unidade');
		$this->db->join('ei_alocacao c', 'c.id = b.id_alocacao');
		$this->db->where('MONTH(a.data_visita)', intval($idMes));
		$this->db->where('b.id', $idMapaUnidade);
		$this->db->order_by('a.data_visita', 'asc');
		$this->db->order_by('a.id', 'asc');
		$rowsId = $this->db->get('ei_mapa_visitacao a')->result();


		$id = ['' => '-- Nova visita --'] + array_column($rowsId, 'nome', 'id');


		$this->db->select("d.*, a.id AS id_mapa_visitacao, DATE_FORMAT(d.data_visita, '%m') AS mes", false);
		$this->db->select('b.id AS id_alocacao, b.ano, b.id_diretoria, b.id_supervisor', false);
		$this->db->select('a.id_escola, a.escola AS unidade, a.municipio AS nome_municipio', false);
		$this->db->join('ei_alocacao b', 'b.id = a.id_alocacao');
		$this->db->join('ei_escolas c', 'c.id = a.id_escola', 'left');
		$this->db->join('ei_mapa_visitacao d', "d.id_mapa_unidade = a.id AND DATE_FORMAT(d.data_visita, '%m') = '{$idMes}'", 'left');
		$this->db->where('a.id', $idMapaUnidade);
		$this->db->order_by('d.data_visita', 'desc');
		$this->db->order_by('d.id', 'desc');
		$data = $this->db->get('ei_mapa_unidades a')->row();


		if (empty($data->id_mapa_unidade)) {
			$data->id_mapa_unidade = $data->id_mapa_visitacao;
		}
		if (empty($data->id_supervisor_visitante)) {
			$data->id_supervisor_visitante = $data->id_supervisor;
		}
		if (empty($data->cliente)) {
			$data->cliente = $data->id_diretoria;
		}
		if (empty($data->municipio)) {
			$data->municipio = $data->nome_municipio;
		}
		if (empty($data->unidade_visitada)) {
			$data->unidade_visitada = $data->id_escola;
		}
		if (empty($data->escola)) {
			$data->escola = $data->unidade;
		}
		if (empty($data->mes)) {
			$data->mes = $idMes;
		}
		if ($data->data_visita) {
			$data->data_visita = date('d/m/Y', strtotime($data->data_visita));
		} else {
			$data->data_visita = date('d/m/Y', mktime(0, 0, 0, $idMes, 1, $data->ano));
		}
		if ($data->data_visita_anterior) {
			$data->data_visita_anterior = date('d/m/Y', strtotime($data->data_visita_anterior));
		} else {
			$data->data_visita_anterior = null;
		}
		$data->gastos_materiais = number_format($data->gastos_materiais, 2, ',', '.');


		$busca = [
			'escola' => $data->escola,
			'cliente' => $data->cliente,
			'municipio' => $data->municipio,
			'unidade_visitada' => $data->id_escola,
			'mes' => $data->mes,
			'ano' => $data->ano
		];


		$filtrosVisita = $this->montarFiltrosVisita($busca);
		$supervisoresVisitantes = $filtrosVisita['supervisores_visitantes'];
		$clientes = $filtrosVisita['clientes'];
		$municipios = $filtrosVisita['municipios'];
		$unidadesVisitadas = $filtrosVisita['unidades_visitadas'];


		if (empty($data->id)) {
			$data->prestadores_servicos_tratados = $filtrosVisita['prestadores_servicos_tratados'];
		}


		$data->id_selecionado = $data->id;
		$data->id = form_dropdown('id', $id, $data->id, 'class="form-control"');
		$data->supervisor_visitante = form_dropdown('supervisor_visitante', $supervisoresVisitantes, $data->id_supervisor_visitante, 'class="form-control"');
		$data->cliente = form_dropdown('cliente', $clientes, $data->cliente, 'onchange="atualizarFiltrosVisitas()" class="form-control"');
		$data->municipio = form_dropdown('municipio', $municipios, $data->municipio, 'onchange="atualizarFiltrosVisitas()" class="form-control"');
		$data->unidade_visitada = form_dropdown('unidade_visitada', $unidadesVisitadas, $data->unidade_visitada, 'onchange="atualizarFiltrosVisitas()" class="form-control"');


		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxUnidadeVisitada()
	{
		$this->db->select('a.id, b.ano, a.escola');
		$this->db->join('ei_alocacao b', 'b.id = a.id_alocacao');
		$this->db->where('a.id', $this->input->post('id'));
		$data = $this->db->get('ei_mapa_unidades a')->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'Unidade não encontrada.']));
		}

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxEditVisita()
	{
		$id = $this->input->post('id');
		$idMapaUnidade = $this->input->post('id_mapa_unidade');
		$idMes = $this->input->post('id_mes');

		$this->db->select("c.*, a.municipio AS nome_municipio, a.id_escola, a.escola AS unidade, b.id_diretoria, b.id_supervisor, b.ano", false);
		$this->db->join('ei_alocacao b', 'b.id = a.id_alocacao');
		$this->db->join('ei_mapa_visitacao c', "c.id_mapa_unidade = a.id AND c.id = '{$id}'", 'left');
		$this->db->where('a.id', $idMapaUnidade);
		$data = $this->db->get('ei_mapa_unidades a')->row();


		if (empty($data->escola)) {
			$data->escola = $data->unidade;
		}
		if (empty($data->mes)) {
			$data->mes = $idMes;
		}
		if ($data->data_visita) {
			$data->data_visita = date('d/m/Y', strtotime($data->data_visita));
		} elseif (empty($data->id)) {
			$data->data_visita = date('d/m/Y', mktime(0, 0, 0, $data->mes, 1, $data->ano));
		}
		if ($data->data_visita_anterior) {
			$data->data_visita_anterior = date('d/m/Y', strtotime($data->data_visita_anterior));
		}
		$data->gastos_materiais = number_format($data->gastos_materiais, 2, ',', '.');


		$busca = [
			'escola' => $data->escola,
			'cliente' => $data->cliente,
			'municipio' => $data->municipio,
			'unidade_visitada' => $data->id_escola,
			'mes' => $data->mes,
			'ano' => $data->ano
		];


		$filtrosVisita = $this->montarFiltrosVisita($busca);
		$supervisoresVisitantes = $filtrosVisita['supervisores_visitantes'];
		$clientes = $filtrosVisita['clientes'];
		$municipios = $filtrosVisita['municipios'];
		$unidadesVisitadas = $filtrosVisita['unidades_visitadas'];


		if ($data->id) {
			$data->prestadores_servicos_tratados = $filtrosVisita['prestadores_servicos_tratados'];
		}


		$data->supervisor_visitante = form_dropdown('supervisor_visitante', $supervisoresVisitantes, $data->id_supervisor_visitante, 'class="form-control"');
		$data->cliente = form_dropdown('cliente', $clientes, $data->cliente, 'onchange="atualizarFiltrosVisitas()" class="form-control"');
		$data->municipio = form_dropdown('municipio', $municipios, $data->municipio, 'onchange="atualizarFiltrosVisitas()" class="form-control"');
		$data->unidade_visitada = form_dropdown('unidade_visitada', $unidadesVisitadas, $data->unidade_visitada, 'onchange="atualizarFiltrosVisitas()" class="form-control"');


		echo json_encode($data);
	}


	//==========================================================================


	private function montarFiltrosVisita($busca = [])
	{
		$this->db->select('d.id, d.nome');
		$this->db->join('ei_escolas b', 'b.id = a.id_escola');
		$this->db->join('ei_coordenacao c', 'c.id = a.id_coordenacao');
		$this->db->join('usuarios d', 'd.id = c.id_usuario');
		$this->db->where('b.id', $busca['unidade_visitada']);
		$this->db->order_by('d.nome', 'asc');
		$supervisores = array_column($this->db->get('ei_supervisores a')->result(), 'nome', 'id');


		$this->db->select('id, nome');
		$this->db->order_by('nome', 'asc');
		$clientes = array_column($this->db->get('ei_diretorias')->result(), 'nome', 'id');


		$this->db->select('a.municipio');
		$this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
		if ($busca['cliente']) {
			$this->db->where('b.id', $busca['cliente']);
		}
		$this->db->group_by('a.municipio');
		$this->db->order_by('a.municipio', 'asc');
		$municipios = array_column($this->db->get('ei_escolas a')->result(), 'municipio', 'municipio');


		$this->db->select('a.id, a.nome');
		$this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
		if ($busca['cliente']) {
			$this->db->where('b.id', $busca['cliente']);
		}
		if ($busca['municipio']) {
			$this->db->where('a.municipio', $busca['municipio']);
		}
		$this->db->order_by('a.nome', 'asc');
		$unidades_visitadas = array_column($this->db->get('ei_escolas a')->result(), 'nome', 'id');


		$data = [
			'supervisores_visitantes' => ['' => 'selecione...'] + $supervisores,
			'clientes' => ['' => 'selecione...'] + $clientes,
			'municipios' => ['' => 'selecione...'] + $municipios,
			'unidades_visitadas' => ['' => 'selecione...'] + $unidades_visitadas
		];


		if (!empty($unidades_visitadas[$busca['unidade_visitada']])) {
			$this->db->select("GROUP_CONCAT(DISTINCT a.cuidador ORDER BY a.cuidador SEPARATOR ', ') AS cuidador", false);
			$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
			$this->db->join('ei_alocacao c', 'c.id = b.id_alocacao');
			$this->db->where('c.ano', $busca['ano']);
			$this->db->where('c.semestre', intval($busca['mes']) > 6 ? 2 : 1);
			$this->db->where('b.escola', $busca['escola']);
			$data['prestadores_servicos_tratados'] = $this->db->get('ei_alocados a')->row()->cuidador ?? null;
		} else {
			$data['prestadores_servicos_tratados'] = null;
		}


		return $data;
	}


	//==========================================================================


	public function faturamentoConsolidado()
	{
		$idDiretoria = $this->input->post('diretoria');
		$mes = $this->input->post('mes');
		$ano = $this->input->post('ano');

		$data['planilha_faturamento_consolidado'] = $this->planilhaFaturamentoConsolidado($idDiretoria, $mes, $ano);

		echo json_encode($data);
	}


	//==========================================================================


	public function recuperarFaturamentoConsolidado()
	{
		$idDiretoria = $this->input->post('diretoria');
		$mes = $this->input->post('mes');
		$ano = $this->input->post('ano');

		$data['planilha_faturamento_consolidado'] = $this->planilhaFaturamentoConsolidado($idDiretoria, $mes, $ano, false, true);

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxSave()
	{
		$data = $this->input->post();
		$id = $this->input->post('id');


		if (!empty($data['status']) == false) {
			exit(json_encode(['erro' => 'O status é obrigatório.']));
		}
		unset($data['id']);

		if (in_array($data['status'], ['FE', 'EM', 'RE'])) {
			$data['periodo'] = null;
		}

		if (strlen($data['id_alocado_sub1']) == 0) {
			$data['id_alocado_sub1'] = null;
		}
		if (strlen($data['id_alocado_sub2']) == 0) {
			$data['id_alocado_sub2'] = null;
		}

		$this->load->helper('time');

		$desconto = timeToSec($data['desconto'] ?? null);
		$descontoSub1 = timeToSec($data['desconto_sub1'] ?? null);
		$descontoSub2 = timeToSec($data['desconto_sub2'] ?? null);

		if (in_array($data['status'], ['FA', 'PV', 'AT', 'SA'])) {
			$data['desconto'] = strlen($desconto) ? secToTime($desconto * ($desconto < 0 ? 1 : -1)) : null;
			$data['desconto_sub1'] = strlen($descontoSub1) ? secToTime($descontoSub1 * ($descontoSub1 < 0 ? -1 : 1)) : null;
			$data['desconto_sub2'] = strlen($descontoSub2) ? secToTime($descontoSub2 * ($descontoSub2 < 0 ? -1 : 1)) : null;
		} else {
			$data['desconto'] = strlen($desconto) ? secToTime($desconto * ($desconto < 0 ? -1 : 1)) : null;
			$data['desconto_sub1'] = strlen($descontoSub1) ? secToTime($descontoSub1 * ($descontoSub1 < 0 ? 1 : -1)) : null;
			$data['desconto_sub2'] = strlen($descontoSub2) ? secToTime($descontoSub2 * ($descontoSub2 < 0 ? 1 : -1)) : null;
		}

		$this->db->trans_begin();

		$desconto_old = null;
		$desconto_sub1_old = null;
		$desconto_sub2_old = null;

		if ($id) {
			$this->db->select('TIME_TO_SEC(desconto) AS desconto');
			$this->db->select('TIME_TO_SEC(desconto_sub1) AS desconto_sub1');
			$this->db->select('TIME_TO_SEC(desconto_sub2) AS desconto_sub2');
			$this->db->where('id', $id);
			$row = $this->db->get('ei_apontamento')->row();

			if ($row) {
				$desconto_old = $row->desconto / 3600;
				$desconto_sub1_old = $row->desconto_sub1 / 3600;
				$desconto_sub2_old = $row->desconto_sub2 / 3600;
			}
			$this->db->update('ei_apontamento', $data, ['id' => $id]);
		} else {
			$this->db->insert('ei_apontamento', $data);
		}


		if ($this->db->trans_status()) {
			$mes = intval(date('m', strtotime($data['data'])));
			$semestre = $mes > 6 ? 2 : 1;
			if ($mes > 6) {
				$mes -= 6;
			}


			$this->db->select("a.id, a.desconto_mensal_{$mes} AS desconto", false);
			$this->db->select("a.desconto_mensal_sub1_{$mes} AS desconto_sub1", false);
			$this->db->select("a.desconto_mensal_sub2_{$mes} AS desconto_sub2", false);
			$this->db->join('ei_ordem_servico_escolas b', 'b.id = a.id_ordem_servico_escola');
			$this->db->join('ei_ordem_servico c', 'c.id = b.id_ordem_servico');
			$this->db->join('ei_alocados d', 'd.id_os_profissional = a.id', 'left');
			$this->db->where('d.id', $data['id_alocado']);
			$this->db->where('c.ano', date('Y', strtotime($data['data'])));
			$this->db->where('c.semestre', $semestre);
			$osProfissional = $this->db->get('ei_ordem_servico_profissionais a')->row();


			if ($osProfissional) {
				if (!isset($data['desconto'])) {
					$data['desconto'] = $this->input->post('desconto');
				}
				if (!isset($data['desconto_sub1'])) {
					$data['desconto_sub1'] = $this->input->post('desconto_sub1');
				}
				if (!isset($data['desconto_sub2'])) {
					$data['desconto_sub2'] = $this->input->post('desconto_sub2');
				}

				$desconto = $this->db->query("SELECT TIME_TO_SEC('{$data['desconto']}') AS desconto")->row_array()['desconto'];
				$desconto_sub1 = $this->db->query("SELECT TIME_TO_SEC('{$data['desconto_sub1']}') AS desconto_sub1")->row_array()['desconto_sub1'];
				$desconto_sub2 = $this->db->query("SELECT TIME_TO_SEC('{$data['desconto_sub2']}') AS desconto_sub2")->row_array()['desconto_sub2'];

				$data2 = [
					'desconto_mensal_' . $mes => $osProfissional->desconto - $desconto_old + ($desconto / 3600),
					'desconto_mensal_sub1_' . $mes => $osProfissional->desconto_sub1 - $desconto_sub1_old + ($desconto_sub1 / 3600),
					'desconto_mensal_sub2_' . $mes => $osProfissional->desconto_sub2 - $desconto_sub2_old + ($desconto_sub2 / 3600),
				];
				$this->db->update('ei_ordem_servico_profissionais', $data2, ['id' => $osProfissional->id]);
			}
		}


		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			exit(json_encode(['erro' => 'Erro ao iniciar semestre.']));
		}


		$this->db->trans_commit();


		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function ajaxSaveEventos()
	{
		parse_str($this->input->post('eventos'), $eventos);
		parse_str($this->input->post('busca'), $busca);

		$this->db->select(["a.id AS id_alocado, '{$eventos['data']}' AS data, '{$eventos['status']}' AS status"], false);
		$this->db->select(['NULL AS desconto, NULL AS desconto_sub1, NULL AS desconto_sub2'], false);
		$this->db->select(["IFNULL((SELECT IF(COUNT(IF(x.data_substituicao1 <= '{$eventos['data']}', 0, 1)) > 0, NULL, x.id_cuidador_sub1)
							   FROM ei_alocados_horarios x 
							   WHERE x.id_alocado = a.id
							   GROUP BY x.id_alocado), a.id_cuidador) AS id_usuario"], false);
		$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
		$this->db->join('ei_alocacao c', 'c.id = b.id_alocacao');
		$this->db->join('ei_apontamento d', "d.id_alocado = a.id AND d.data = '{$eventos['data']}'", 'left');
		$this->db->where('c.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('c.depto', $busca['depto']);
		$this->db->where('c.id_diretoria', $busca['diretoria']);
		$this->db->where('c.id_supervisor', $busca['supervisor']);
		$this->db->where('c.ano', $busca['ano']);
		$this->db->where('c.semestre', $busca['semestre']);
		$this->db->where('d.data', null);
		$this->db->group_by('a.id');
		$data = $this->db->get('ei_alocados a')->result_array();


		$status = true;
		if ($data) {
			$status = $this->db->insert_batch('ei_apontamento', $data);
		}


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveCuidador()
	{
		$id = $this->input->post('id');


		$this->db->select('a.id, a.nome, a.cargo, a.funcao, a.municipio');
		$this->db->select('b.id AS id_depto, c.id AS id_area, d.id AS id_setor');
		$this->db->select('e.id AS id_cargo, f.id AS id_funcao');
		$this->db->join('empresa_departamentos b', 'b.nome = a.depto', 'left');
		$this->db->join('empresa_areas c', 'c.nome = a.area', 'left');
		$this->db->join('empresa_setores d', 'd.nome = a.setor', 'left');
		$this->db->join('empresa_cargos e', 'e.nome = a.cargo', 'left');
		$this->db->join('empresa_funcoes f', 'f.nome = a.funcao', 'left');
		$this->db->where('a.id', $this->input->post('id_cuidador'));
		$usuario = $this->db->get('usuarios a')->row();


		$data = [
			'id_cuidador' => $usuario->id,
			'cuidador' => $usuario->nome
		];


		$this->db->trans_start();


		$this->db->update('ei_alocados', $data, ['id' => $id]);


		$data2 = [
			'id_usuario' => $usuario->id,
			'id_departamento' => $usuario->id_depto,
			'id_area' => $usuario->id_area,
			'id_setor' => $usuario->id_setor,
			'id_cargo' => $usuario->id_cargo,
			'id_funcao' => $usuario->id_funcao,
			'municipio' => $usuario->municipio
		];


		$this->db->select('id_os_profissional');
		$this->db->where('id', $id);
		$alocado = $this->db->get_where('ei_alocados')->row();
		$this->db->update('ei_ordem_servico_profissionais', $data2, ['id' => $alocado->id_os_profissional]);


		$this->db->trans_complete();
		$status = $this->db->trans_status();


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveDisciplinaAluno()
	{
		$data = $this->input->post();

		$matriculado = $this->db
			->select('id, id_os_aluno')
			->where('id', $data['id'])
			->get('ei_matriculados')
			->row();

		if (empty($matriculado)) {
			exit(json_encode(['erro' => 'Aluno matriculado não encontrado.']));
		}

		unset($data['id']);

		$this->db->trans_start();

		$this->db->update('ei_matriculados', $data, ['id' => $matriculado->id]);
		$this->db->update('ei_ordem_servico_alunos', ['nota' => $data['media_semestral']], ['id' => $matriculado->id_os_aluno]);

		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Não foi possível salvar o cadastro de notas.']));
		}

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function ajaxDeleteEventos()
	{
		parse_str($this->input->post('eventos'), $eventos);
		parse_str($this->input->post('busca'), $busca);
		$busca['id_diretoria'] = $busca['diretoria'];
		$busca['id_supervisor'] = $busca['supervisor'];
		unset($busca['diretoria'], $busca['supervisor'], $busca['mes']);


		$this->db->select('a.id');
		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_alocacao d', 'd.id = c.id_alocacao');
		$this->db->where($busca);
		$this->db->where('a.data', $eventos['data']);
		$this->db->where('a.status', $eventos['status']);
		$where = $this->db->get('ei_apontamento a')->result();


		$status = true;
		if ($where) {
			$this->db->where_in('id', array_column($where, 'id'));
			$status = $this->db->delete('ei_apontamento');
		}


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveFaturamento()
	{
		$id = $this->input->post('id');
		$mes = $this->input->post('mes');
		$semestre = $this->input->post('semestre');
		$idMes = $this->getIdMes($mes, $semestre);
		$desconto = $this->input->post('desconto');
		$endosso = $this->input->post('endosso');
		$endossoOriginal = $this->input->post('endosso_original');
		$substituto = $this->input->post('substituto');
		if ($desconto) {
			$desconto = str_replace(',', '.', $desconto);
		}
		if ($endosso) {
			$endosso = str_replace(',', '.', $endosso) + $endossoOriginal;
		}


		$this->db->select('id_alocado, dia_semana');
		$this->db->where('id', $id);
		$horarios = $this->db->get('ei_alocados_horarios')->row();

		if ($substituto === '2') {
			$this->db->set('desconto_sub2', $desconto);
			$this->db->set('endosso_sub2', $endosso);
		} elseif ($substituto === '1') {
			$this->db->set('desconto_sub1', $desconto);
			$this->db->set('endosso_sub1', $endosso);
		} else {
			$this->db->set('desconto_mes' . $idMes, $desconto);
			$this->db->set('endosso_mes' . $idMes, $endosso);
		}
		$this->db->where('id_alocado', $horarios->id_alocado);
		$this->db->where('dia_semana', $horarios->dia_semana);
		$status = $this->db->update('ei_alocados_horarios');


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveDataRealTotalizacao()
	{
		$postDataReal = $this->input->post('data_real_totalizacao');
		$dataReal = date('Y-m-d', strtotime(str_replace('/', '-', $postDataReal)));
		$fechamento = $this->input->post('fechamento');
		if ($dataReal !== preg_replace('/(\d+)\/(\d+)\/(\d+)/', '$3-$2-$1', $dataReal)) {
			if ($fechamento) {
				exit(json_encode(['erro' => 'A data de término real do semestre é inválida']));
			}
			exit(json_encode(['erro' => 'A data de início real do semestre é inválida']));
		}

		$semestre = $this->input->post('semestre');
		$idAlocado = $this->input->post('id_alocado');
		$periodo = $this->input->post('periodo');

		$horarios = $this->db
			->select('d.ano, IFNULL(a.data_inicio_real, MIN(data_inicio)) AS data_inicio', false)
			->select('IFNULL(a.data_termino_real, MAX(data_termino)) AS data_termino', false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->join('ei_matriculados_turmas e', 'e.id_alocado_horario = a.id', 'left')
			->join('ei_matriculados f', 'f.id = e.id_matriculado AND f.id_alocacao_escola = c.id', 'left')
			->where('a.id_alocado', $idAlocado)
			->where('a.periodo', $periodo)
			->group_by('a.id_alocado')
			->get('ei_alocados_horarios a')
			->row();

		$dataInicioReal = $fechamento ? $horarios->data_inicio : $dataReal;
		$mesInicioReal = (int)date('m', strtotime($dataInicioReal));
		$dataTerminoReal = $fechamento ? $dataReal : $horarios->data_termino;
		$mesTerminoReal = (int)date('m', strtotime($dataTerminoReal));
		$mesCorrente = $semestre === '2' ? 7 : 1;

		for ($i = 1; $i <= 7; $i++) {
			$dataInicioMes = date('Y-m-d', mktime(0, 0, 0, $mesCorrente, 1, (int)$horarios->ano));
			$dataFimMes = date('Y-m-t', strtotime($dataInicioMes));

			if ($mesCorrente >= $mesInicioReal and $mesCorrente <= $mesTerminoReal) {
				if ($mesCorrente === $mesInicioReal) {
					$dataInicioMes = $dataInicioReal;
				}
				if ($mesCorrente === $mesTerminoReal) {
					$dataFimMes = $dataTerminoReal;
				}
				$this->db->set("total_semanas_mes{$i}", "WEEK(DATE_SUB('{$dataFimMes}', INTERVAL ((7 + DATE_FORMAT('{$dataFimMes}', '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD('{$dataInicioMes}', INTERVAL ((7 - DATE_FORMAT('{$dataInicioMes}', '%w') + dia_semana) % 7) DAY)) + 1", false);
			} else {
				$this->db->set("total_semanas_mes{$i}", 0);
			}

			$mesCorrente++;
		}
		$this->db->set('data_inicio_real', $dataInicioReal);
		$this->db->set('data_termino_real', $dataTerminoReal);
		$this->db->where('id_alocado', $idAlocado);
		$this->db->where('periodo', $periodo);
		$status = $this->db->update('ei_alocados_horarios');

		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveDataRealTotalizacoes()
	{
		$this->db->select('a.id');
		$this->db->select('MIN(f.data_inicio) AS data_inicio_real', false);
		$this->db->select('MAX(f.data_termino) AS data_termino_real', false);
		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_alocacao d', 'd.id = c.id_alocacao');
		$this->db->join('ei_matriculados_turmas e', 'e.id_alocado_horario = d.id', 'left');
		$this->db->join('ei_matriculados f', 'f.id = e.id_matriculado AND f.id_alocacao_escola = c.id', 'left');
		$this->db->where('c.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('c.depto', $this->input->post('depto'));
		$this->db->where('c.id_diretoria', $this->input->post('diretoria'));
		$this->db->where('c.id_supervisor', $this->input->post('supervisor'));
		$this->db->where('c.ano', $this->input->post('ano'));
		$this->db->where('c.semestre', $this->input->post('semestre'));
		$this->db->where('(a.data_inicio_real IS NULL OR a.data_termino_real IS NULL)', null, false);
		$data = $this->db->get('ei_alocados_horarios a')->result();


		$status = true;
		if ($data) {
			$this->db->set($data);
			$this->db->where_in(array_column($data, 'id'));
			$status = $this->db->update('ei_alocados_horarios');
		}


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function recalcularIngresso()
	{
		$post = $this->input->post();
		$empresa = $this->session->userdata('empresa');
		$ano = $this->input->post('ano');
		$semestre = $this->input->post('semestre');


		$mes1 = $semestre > 1 ? '07' : '01';
		$mes2 = $semestre > 1 ? '08' : '02';
		$mes3 = $semestre > 1 ? '09' : '03';
		$mes4 = $semestre > 1 ? '10' : '04';
		$mes5 = $semestre > 1 ? '11' : '05';
		$mes6 = $semestre > 1 ? '12' : '06';
		$mes7 = $semestre === '1' ? '07' : '';


		$diaIniMes1 = date('Y-m-d', strtotime("{$ano}-{$mes1}-01"));
		$diaIniMes2 = date('Y-m-d', strtotime("{$ano}-{$mes2}-01"));
		$diaIniMes3 = date('Y-m-d', strtotime("{$ano}-{$mes3}-01"));
		$diaIniMes4 = date('Y-m-d', strtotime("{$ano}-{$mes4}-01"));
		$diaIniMes5 = date('Y-m-d', strtotime("{$ano}-{$mes5}-01"));
		$diaIniMes6 = date('Y-m-d', strtotime("{$ano}-{$mes6}-01"));
		if ($semestre === '1') {
			$diaIniMes7 = date('Y-m-d', strtotime("{$ano}-{$mes7}-01"));
		} else {
			$diaIniMes7 = '';
		}


		$diaFimMes1 = date('Y-m-t', strtotime($diaIniMes1));
		$diaFimMes2 = date('Y-m-t', strtotime($diaIniMes2));
		$diaFimMes3 = date('Y-m-t', strtotime($diaIniMes3));
		$diaFimMes4 = date('Y-m-t', strtotime($diaIniMes4));
		$diaFimMes5 = date('Y-m-t', strtotime($diaIniMes5));
		$diaFimMes6 = date('Y-m-t', strtotime($diaIniMes6));
		if ($semestre === '1') {
			$diaFimMes7 = date('Y-m-t', strtotime($diaIniMes7));
		} else {
			$diaFimMes7 = '';
		}


		$this->db->select('a.id');
		$this->db->select(["IF(MONTH(a.data_inicio_real) = {$mes1}, (WEEK(DATE_SUB(LAST_DAY(a.data_inicio_real), INTERVAL ((7 + DATE_FORMAT(LAST_DAY(a.data_inicio_real), '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(a.data_inicio_real, INTERVAL (((7 - DATE_FORMAT(a.data_inicio_real, '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_inicio_real) > {$mes1}, 0, total_semanas_mes1)) AS total_semanas_mes1"], false);
		$this->db->select(["IF(MONTH(a.data_inicio_real) = {$mes2}, (WEEK(DATE_SUB(LAST_DAY(a.data_inicio_real), INTERVAL ((7 + DATE_FORMAT(LAST_DAY(a.data_inicio_real), '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(a.data_inicio_real, INTERVAL (((7 - DATE_FORMAT(a.data_inicio_real, '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_inicio_real) > {$mes2}, 0, total_semanas_mes2)) AS total_semanas_mes2"], false);
		$this->db->select(["IF(MONTH(a.data_inicio_real) = {$mes3}, (WEEK(DATE_SUB(LAST_DAY(a.data_inicio_real), INTERVAL ((7 + DATE_FORMAT(LAST_DAY(a.data_inicio_real), '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(a.data_inicio_real, INTERVAL (((7 - DATE_FORMAT(a.data_inicio_real, '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_inicio_real) > {$mes3}, 0, total_semanas_mes3)) AS total_semanas_mes3"], false);
		$this->db->select(["IF(MONTH(a.data_inicio_real) = {$mes4}, (WEEK(DATE_SUB(LAST_DAY(a.data_inicio_real), INTERVAL ((7 + DATE_FORMAT(LAST_DAY(a.data_inicio_real), '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(a.data_inicio_real, INTERVAL (((7 - DATE_FORMAT(a.data_inicio_real, '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_inicio_real) > {$mes4}, 0, total_semanas_mes4)) AS total_semanas_mes4"], false);
		$this->db->select(["IF(MONTH(a.data_inicio_real) = {$mes5}, (WEEK(DATE_SUB(LAST_DAY(a.data_inicio_real), INTERVAL ((7 + DATE_FORMAT(LAST_DAY(a.data_inicio_real), '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(a.data_inicio_real, INTERVAL (((7 - DATE_FORMAT(a.data_inicio_real, '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_inicio_real) > {$mes5}, 0, total_semanas_mes5)) AS total_semanas_mes5"], false);
		$this->db->select(["IF(MONTH(a.data_inicio_real) = {$mes6}, (WEEK(DATE_SUB(LAST_DAY(a.data_inicio_real), INTERVAL ((7 + DATE_FORMAT(LAST_DAY(a.data_inicio_real), '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(a.data_inicio_real, INTERVAL (((7 - DATE_FORMAT(a.data_inicio_real, '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_inicio_real) > {$mes6}, 0, total_semanas_mes6)) AS total_semanas_mes6"], false);
		if ($semestre === '1') {
			$this->db->select(["IF(MONTH(a.data_inicio_real) = {$mes7}, (WEEK(DATE_SUB(LAST_DAY(a.data_inicio_real), INTERVAL ((7 + DATE_FORMAT(LAST_DAY(a.data_inicio_real), '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(a.data_inicio_real, INTERVAL (((7 - DATE_FORMAT(a.data_inicio_real, '%w')) + a.dia_semana) % 7) DAY) + 1), IF(MONTH(a.data_inicio_real) > {$mes7}, 0, total_semanas_mes7)) AS total_semanas_mes7"], false);
		}
		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_alocacao d', 'd.id = c.id_alocacao');
		$this->db->join('ei_matriculados_turmas e', 'e.id_alocado_horario = a.id', 'left');
		$this->db->join('ei_matriculados f', 'f.id = e.id_matriculado AND f.id_alocacao_escola = c.id', 'left');
		$this->db->where('d.id_empresa', $empresa);
		$this->db->where('d.depto', $post['depto']);
		$this->db->where('d.id_diretoria', $post['diretoria']);
		$this->db->where('d.id_supervisor', $post['supervisor']);
		$this->db->where('d.ano', $post['ano']);
		$this->db->where('d.semestre', $post['semestre']);
		$this->db->group_by('a.id');
		$data = $this->db->get('ei_alocados_horarios a')->result();


		$this->db->trans_start();
		$this->db->update_batch('ei_alocados_horarios', $data, 'id');
		$this->db->trans_complete();


		$status = $this->db->trans_status();
		if ($status === false) {
			exit(json_encode(['erro' => 'Erro ao recalcular quantidade de dias.']));
		}


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function recalcularRecesso()
	{
		$post = $this->input->post();
		$empresa = $this->session->userdata('empresa');
		$ano = $this->input->post('ano');
		$semestre = $this->input->post('semestre');


		$mes1 = $semestre > 1 ? '07' : '01';
		$mes2 = $semestre > 1 ? '08' : '02';
		$mes3 = $semestre > 1 ? '09' : '03';
		$mes4 = $semestre > 1 ? '10' : '04';
		$mes5 = $semestre > 1 ? '11' : '05';
		$mes6 = $semestre > 1 ? '12' : '06';
		$mes7 = $semestre === '1' ? '07' : '';


		$diaIniMes1 = date('Y-m-d', strtotime("{$ano}-{$mes1}-01"));
		$diaIniMes2 = date('Y-m-d', strtotime("{$ano}-{$mes2}-01"));
		$diaIniMes3 = date('Y-m-d', strtotime("{$ano}-{$mes3}-01"));
		$diaIniMes4 = date('Y-m-d', strtotime("{$ano}-{$mes4}-01"));
		$diaIniMes5 = date('Y-m-d', strtotime("{$ano}-{$mes5}-01"));
		$diaIniMes6 = date('Y-m-d', strtotime("{$ano}-{$mes6}-01"));
		if ($semestre === '1') {
			$diaIniMes7 = date('Y-m-d', strtotime("{$ano}-{$mes7}-01"));
		} else {
			$diaIniMes7 = '';
		}


		$diaFimMes1 = date('Y-m-t', strtotime($diaIniMes1));
		$diaFimMes2 = date('Y-m-t', strtotime($diaIniMes2));
		$diaFimMes3 = date('Y-m-t', strtotime($diaIniMes3));
		$diaFimMes4 = date('Y-m-t', strtotime($diaIniMes4));
		$diaFimMes5 = date('Y-m-t', strtotime($diaIniMes5));
		$diaFimMes6 = date('Y-m-t', strtotime($diaIniMes6));
		if ($semestre === '1') {
			$diaFimMes7 = date('Y-m-t', strtotime($diaIniMes7));
		} else {
			$diaFimMes7 = '';
		}

		$this->db->select('a.id');
		$this->db->select(["IF(MONTH(a.data_termino_real) = {$mes1}, (WEEK(DATE_SUB(a.data_termino_real, INTERVAL ((7 + DATE_FORMAT(a.data_termino_real, '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), INTERVAL (((7 - DATE_FORMAT(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_termino_real) < {$mes1}, 0, total_semanas_mes1)) AS total_semanas_mes1"], false);
		$this->db->select(["IF(MONTH(a.data_termino_real) = {$mes2}, (WEEK(DATE_SUB(a.data_termino_real, INTERVAL ((7 + DATE_FORMAT(a.data_termino_real, '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), INTERVAL (((7 - DATE_FORMAT(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_termino_real) < {$mes2}, 0, total_semanas_mes2)) AS total_semanas_mes2"], false);
		$this->db->select(["IF(MONTH(a.data_termino_real) = {$mes3}, (WEEK(DATE_SUB(a.data_termino_real, INTERVAL ((7 + DATE_FORMAT(a.data_termino_real, '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), INTERVAL (((7 - DATE_FORMAT(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_termino_real) < {$mes3}, 0, total_semanas_mes3)) AS total_semanas_mes3"], false);
		$this->db->select(["IF(MONTH(a.data_termino_real) = {$mes4}, (WEEK(DATE_SUB(a.data_termino_real, INTERVAL ((7 + DATE_FORMAT(a.data_termino_real, '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), INTERVAL (((7 - DATE_FORMAT(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_termino_real) < {$mes4}, 0, total_semanas_mes4)) AS total_semanas_mes4"], false);
		$this->db->select(["IF(MONTH(a.data_termino_real) = {$mes5}, (WEEK(DATE_SUB(a.data_termino_real, INTERVAL ((7 + DATE_FORMAT(a.data_termino_real, '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), INTERVAL (((7 - DATE_FORMAT(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_termino_real) < {$mes5}, 0, total_semanas_mes5)) AS total_semanas_mes5"], false);
		$this->db->select(["IF(MONTH(a.data_termino_real) = {$mes6}, (WEEK(DATE_SUB(a.data_termino_real, INTERVAL ((7 + DATE_FORMAT(a.data_termino_real, '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), INTERVAL (((7 - DATE_FORMAT(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_termino_real) < {$mes6}, 0, total_semanas_mes6)) AS total_semanas_mes6"], false);
		if ($semestre === '1') {
			$this->db->select(["IF(MONTH(a.data_termino_real) = {$mes7}, (WEEK(DATE_SUB(a.data_termino_real, INTERVAL ((7 + DATE_FORMAT(a.data_termino_real, '%w') - a.dia_semana) % 7) DAY)) + 1) - WEEK(DATE_ADD(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), INTERVAL (((7 - DATE_FORMAT(DATE_SUB(a.data_termino_real, INTERVAL (DAY(a.data_termino_real) - 1) DAY), '%w')) + a.dia_semana) % 7) DAY)), IF(MONTH(a.data_termino_real) < {$mes7}, 0, total_semanas_mes7)) AS total_semanas_mes7"], false);
		}
		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_alocacao d', 'd.id = c.id_alocacao');
		$this->db->join('ei_matriculados_turmas e', 'e.id_alocado_horario = a.id', 'left');
		$this->db->join('ei_matriculados f', 'f.id = e.id_matriculado AND f.id_alocacao_escola = c.id', 'left');
		$this->db->where('d.id_empresa', $empresa);
		$this->db->where('d.depto', $post['depto']);
		$this->db->where('d.id_diretoria', $post['diretoria']);
		$this->db->where('d.id_supervisor', $post['supervisor']);
		$this->db->where('d.ano', $post['ano']);
		$this->db->where('d.semestre', $post['semestre']);
		$this->db->group_by('a.id');
		$data = $this->db->get('ei_alocados_horarios a')->result();


		$this->db->trans_start();
		$this->db->update_batch('ei_alocados_horarios', $data, 'id');
		$this->db->trans_complete();


		$status = $this->db->trans_status();
		if ($status === false) {
			exit(json_encode(['erro' => 'Erro ao recalcular quantidade de dias.']));
		}


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveFaturamentoConsolidado()
	{
		$mes = $this->input->post('mes');
		$semestre = $this->input->post('semestre');
		$idMes = intval($mes) - ($semestre > 1 ? 6 : 0);

		$rows = array_map(null,
			$this->input->post('id'),
			$this->input->post('id_alocacao'),
			$this->input->post('cargo'),
			$this->input->post('funcao'),
			$this->input->post('valor_hora'),
			$this->input->post('total_horas'),
			$this->input->post('valor_faturado')
		);

		$obs = $this->input->post('observacoes');
		$dataObs = ["observacoes_mes{$idMes}" => strlen($obs) > 0 ? $obs : null];


		$campos = [
			'id',
//			'id_medicao_mensal',
			'id_alocacao',
			'cargo',
			'funcao',
			"valor_hora_mes{$idMes}",
			"total_horas_mes{$idMes}",
			"valor_faturado_mes{$idMes}"
		];


		$this->db->trans_start();


		foreach ($rows as $data) {
			$data[4] = str_replace(['.', ','], ['', '.'], $data[4]);
			$data[6] = str_replace(['.', ','], ['', '.'], $data[6]);
			$data = array_combine($campos, $data);
			if ($data['id']) {
				$this->db->update('ei_faturamento_consolidado', $data, ['id' => $data['id']]);
			} else {
				$this->db->insert('ei_faturamento_consolidado', $data);
			}

			$this->db->update('ei_alocacao', $dataObs, ['id' => $data['id_alocacao']]);
//			$this->db->update('ei_medicao_mensal', $dataObs, ['id' => $data['id_alocacao']]);
		}


		$status = $this->db->trans_status();
		$this->db->trans_complete();


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveHorario()
	{
		$id = $this->input->post('id');
		$semestre = $this->input->post('semestre');
		$mes = intval($this->input->post('mes')) - ($semestre > 1 ? 6 : 0);

		$horarioInicio = $this->input->post('horario_inicio');

		$horarioTermino = $this->input->post('horario_termino');

		if ($horarioInicio xor $horarioTermino) {
			exit(json_encode(['erro' => 'Os horários devem estar ambos vazios ou ambos preenchidos.']));
		}
		if (strtotime($horarioInicio) >= strtotime($horarioTermino)) {
			exit(json_encode(['erro' => 'O horário de saída deve ser maior do que o horário de entrada.']));
		}

		$this->db->set('horario_inicio_mes' . $mes, $horarioInicio);
		$this->db->set('horario_termino_mes' . $mes, $horarioTermino);
		$this->db->set('total_horas_mes' . $mes, "TIMEDIFF('{$horarioTermino}', '{$horarioInicio}')", false);
		$this->db->where('id', $id);
		$status = $this->db->update('ei_alocados_horarios');


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveSubstituto()
	{
		$id = $this->input->post('id');

		$mes = $this->input->post('mes');

		$idCuidadorSub1 = $this->input->post('id_cuidador_sub1');
		if (strlen($idCuidadorSub1) == 0) {
			$idCuidadorSub1 = null;
		}

		$FuncaoSub1 = $this->input->post('funcao_sub1');

		$dataSubstituicao1 = $this->input->post('data_substituicao1');
		if ($dataSubstituicao1) {
			$dataSubstituicao1 = date('Y-m-d', strtotime(str_replace('/', '-', $dataSubstituicao1)));
		} else {
			$dataSubstituicao1 = null;
		}

		$idCuidadorSub2 = $this->input->post('id_cuidador_sub2');
		if (strlen($idCuidadorSub2) == 0) {
			$idCuidadorSub2 = null;
		}

		$FuncaoSub2 = $this->input->post('funcao_sub2');

		$dataSubstituicao2 = $this->input->post('data_substituicao2');
		if ($dataSubstituicao2) {
			$dataSubstituicao2 = date('Y-m-d', strtotime(str_replace('/', '-', $dataSubstituicao2)));
		} else {
			$dataSubstituicao2 = null;
		}


		$this->db->select('d.ano, d.semestre');
		$this->db->select(["IF(MONTH(MIN(f.data_inicio)) = '{$mes}', MIN(f.data_inicio), NULL) AS data_inicio"], false);
		$this->db->select(["IF(MONTH(MAX(f.data_termino)) = '{$mes}', MAX(f.data_termino), NULL) AS data_termino"], false);
		$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
		$this->db->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola');
		$this->db->join('ei_alocacao d', 'd.id = c.id_alocacao');
		$this->db->join('ei_matriculados_turmas e', 'e.id_alocado_horario = a.id', 'left');
		$this->db->join('ei_matriculados f', 'f.id = e.id_matriculado AND f.id_alocacao_escola = c.id', 'left');
		$this->db->where('a.id', $id);
		$this->db->group_by('b.id');
		$row = $this->db->get('ei_alocados_horarios a')->row();

		$idMes = (int)$mes - ($row->semestre > 1 ? 6 : 0);

		if ($row->data_inicio) {
			$diaIni = $row->data_inicio;
		} else {
			$diaIni = $row->ano . '-' . $mes . '-01';
		}
		if ($row->data_termino) {
			$diaFim = $row->data_termino;
		} else {
			$diaFim = date('Y-m-t', strtotime($diaIni));
		}


		if ($dataSubstituicao1) {
			if ($dataSubstituicao2) {
				$this->db->set("total_semanas_mes{$idMes}", "WEEK(DATE_SUB(DATE_SUB('{$dataSubstituicao1}', INTERVAL 1 DAY), INTERVAL ((7 + DATE_FORMAT(DATE_SUB('{$dataSubstituicao1}', INTERVAL 1 DAY), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD('{$diaIni}', INTERVAL (((7 - DATE_FORMAT('{$diaIni}', '%w')) + dia_semana) % 7) DAY)) + 1", false);
				$this->db->set('total_semanas_sub1', "WEEK(DATE_SUB(DATE_SUB('{$dataSubstituicao2}', INTERVAL 1 DAY), INTERVAL ((7 + DATE_FORMAT(DATE_SUB('{$dataSubstituicao2}', INTERVAL 1 DAY), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD('{$dataSubstituicao1}', INTERVAL (((7 - DATE_FORMAT('{$dataSubstituicao1}', '%w')) + dia_semana) % 7) DAY)) + 1", false);
				$this->db->set('total_semanas_sub2', "WEEK(DATE_SUB('{$diaFim}', INTERVAL ((7 + DATE_FORMAT('$diaFim', '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD('{$dataSubstituicao2}', INTERVAL (((7 - DATE_FORMAT('{$dataSubstituicao2}', '%w')) + dia_semana) % 7) DAY)) + 1", false);
			} else {
				$this->db->set("total_semanas_mes{$idMes}", "WEEK(DATE_SUB(DATE_SUB('{$dataSubstituicao1}', INTERVAL 1 DAY), INTERVAL ((7 + DATE_FORMAT(DATE_SUB('{$dataSubstituicao1}', INTERVAL 1 DAY), '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD('{$diaIni}', INTERVAL (((7 - DATE_FORMAT('{$diaIni}', '%w')) + dia_semana) % 7) DAY)) + 1", false);
				$this->db->set('total_semanas_sub1', "WEEK(DATE_SUB('{$diaFim}', INTERVAL ((7 + DATE_FORMAT('$diaFim', '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD('{$dataSubstituicao1}', INTERVAL (((7 - DATE_FORMAT('{$dataSubstituicao1}', '%w')) + dia_semana) % 7) DAY)) + 1", false);
				$this->db->set('total_semanas_sub2', null);
			}
		} elseif ($dataSubstituicao2) {
			$this->db->set("total_semanas_mes{$idMes}", "WEEK(DATE_SUB(DATE_SUB('{$dataSubstituicao2}', INTERVAL 1 DAY), INTERVAL ((7 + DATE_FORMAT(DATE_SUB('{$dataSubstituicao2}', INTERVAL 1 DAY), '%w') - dia_semana) % 7) + 1 DAY)) - WEEK(DATE_ADD('{$diaIni}', INTERVAL (((7 - DATE_FORMAT('{$diaIni}', '%w')) + dia_semana) % 7) DAY)) + 1", false);
			$this->db->set('total_semanas_sub1', null);
			$this->db->set('total_semanas_sub2', "WEEK(DATE_SUB('{$diaFim}', INTERVAL ((7 + DATE_FORMAT('$diaFim', '%w') - dia_semana) % 7) DAY)) - WEEK('DATE_ADD('{$dataSubstituicao2}', INTERVAL (((7 - DATE_FORMAT('{$dataSubstituicao2}', '%w')) + dia_semana) % 7) DAY)') + 1", false);
		} else {
			$this->db->set("total_semanas_mes{$idMes}", "WEEK(DATE_SUB('{$diaFim}', INTERVAL ((7 + DATE_FORMAT('$diaFim', '%w') - dia_semana) % 7) DAY)) - WEEK(DATE_ADD('{$diaIni}', INTERVAL (((7 - DATE_FORMAT('{$diaIni}', '%w')) + dia_semana) % 7) DAY)) + 1", false);
			$this->db->set('total_semanas_sub1', null);
			$this->db->set('total_semanas_sub2', null);
		}

		$this->db->set('id_cuidador_sub1', $idCuidadorSub1);
		$this->db->set('funcao_sub1', $FuncaoSub1);
		$this->db->set('data_substituicao1', $dataSubstituicao1);

		$this->db->set('id_cuidador_sub2', $idCuidadorSub2);
		$this->db->set('funcao_sub2', $FuncaoSub2);
		$this->db->set('data_substituicao2', $dataSubstituicao2);

		$this->db->where('id', $id);
		$status = $this->db->update('ei_alocados_horarios');


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveTotalizacao()
	{
		$id = $this->input->post('id');
		$idAlocacao = $this->input->post('id_alocacao');
		$idEscola = $this->input->post('id_escola');
		$cargo = $this->input->post('cargo');
		$funcao = $this->input->post('funcao');
		$mes = $this->input->post('mes');


		$alocacao = $this->db
			->select('id, semestre')
			->where('id', $idAlocacao)
			->get('ei_alocacao')
			->row();

		$idMes = $this->getIdMes($mes, $alocacao->semestre);

		$dataAprovacao = $this->input->post('data_aprovacao');
		$dataImpressao = $this->input->post('data_impressao');
		if ($dataAprovacao) {
			$dataAprovacao = date('Y-m-d', strtotime(str_replace('/', '-', $dataAprovacao)));
		} else {
			$dataAprovacao = null;
		}
		if ($dataImpressao) {
			$dataImpressao = date('Y-m-d', strtotime(str_replace('/', '-', $dataImpressao)));
		} else {
			$dataImpressao = null;
		}
		$observacoes = $this->input->post('observacoes');
		if (strlen($observacoes) == 0) {
			$observacoes = null;
		}


		$this->db->trans_start();

		$faturamentos = $this->db
			->select('d.id, a.id_escola, a.escola, a.id_alocacao, c.cargo, c.funcao')
			->join('ei_alocados b', 'b.id_alocacao_escola = a.id')
			->join('ei_alocados_horarios c', "c.id_alocado = b.id AND c.cargo = '{$cargo}' AND c.funcao = '{$funcao}'", 'left')
			->join('ei_faturamento d', "d.id_alocacao = a.id_alocacao AND d.id = '{$id}'", 'left')
			->where('a.id_alocacao', $alocacao->id)
			->where('a.id_escola', $idEscola)
			->where('c.cargo IS NOT NULL', null, false)
			->where('c.funcao IS NOT NULL', null, false)
			->group_by(['a.id_escola', 'c.cargo', 'c.funcao'])
			->get('ei_alocacao_escolas a')
			->result();


		foreach ($faturamentos as $faturamento) {
			$faturamento->{'data_aprovacao_mes' . $idMes} = $dataAprovacao;
			$faturamento->{'data_impressao_mes' . $idMes} = $dataImpressao;
			$faturamento->{'observacoes_mes' . $idMes} = $observacoes;

			if ($faturamento->id) {
				$this->db->update('ei_faturamento', $faturamento, ['id' => $faturamento->id]);
			} else {
				$this->db->insert('ei_faturamento', $faturamento);
			}
		}


		$rows = array_map(null,
			$this->input->post('id_totalizacao'),
			$this->input->post('id_alocado'),
			$this->input->post('periodo'),
			$this->input->post('total_dias'),
			$this->input->post('total_horas')
		);

		$campos = ['id', 'id_alocado', 'periodo', "total_dias_mes{$idMes}", "total_horas_mes{$idMes}"];

		/*$substituto = $this->input->post('substituto');

		if ($substituto === '2') {
			$campos[] = 'total_dias_sub2';
			$campos[] = 'total_horas_sub2';
		} elseif ($substituto === '1') {
			$campos[] = 'total_dias_sub1';
			$campos[] = 'total_horas_sub1';
		} else {
			$campos[] = "total_dias_mes{$idMes}";
			$campos[] = "total_horas_mes{$idMes}";
		}*/


		foreach ($rows as $data) {
			$data = array_combine($campos, $data);
			if ($data['id']) {
				$this->db->update('ei_alocados_totalizacao', $data, ['id' => $data['id']]);
			} else {
				$this->db->insert('ei_alocados_totalizacao', $data);
			}
		}


		$status = $this->db->trans_status();
		$this->db->trans_complete();


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSaveAjusteMensal()
	{
		$id = $this->input->post('id');
		$mes = $this->input->post('mes');
//		$substituto = $this->input->post('substituto');
		$horasDescontadas = $this->input->post('horas_descontadas');
		if (strlen($horasDescontadas) == 0) {
			$horasDescontadas = null;
		}


		/*if ($substituto === '2') {
			$this->db->set('horas_descontadas_sub2_mes' . $mes, $horasDescontadas);
		} elseif ($substituto === '1') {
			$this->db->set('horas_descontadas_sub1_mes' . $mes, $horasDescontadas);
		} else {
		}*/
		$this->db->set('horas_descontadas_mes' . $mes, $horasDescontadas);
		$this->db->where('id', $id);
		$status = $this->db->update('ei_alocados_totalizacao');


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxSavePagamentoPrestador()
	{

		$idHorario = $this->input->post('id_horario');
		$alocado = $this->db
			->select('c.id_alocacao, b.id_cuidador, d.semestre, b.id_alocacao_escola, c.escola')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->where('a.id', $idHorario)
			->get('ei_alocados_horarios a')
			->row();

		$mes = (int)$this->input->post('mes');
		$idMes = $this->getIdMes($mes, $alocado->semestre);
		$numeroNotaFiscal = $this->input->post('numero_nota_fiscal');
		$substituto = $this->input->post('substituto');
		$idAlocadoSub = $this->input->post('id_alocado_sub');

		if (strlen($numeroNotaFiscal) == 0) {
			$numeroNotaFiscal = null;
		}
		$dataLiberacaoPagto = $this->input->post('data_liberacao_pagamento');
		if ($dataLiberacaoPagto) {
			$dataLiberacaoPagto = date('Y-m-d', strtotime(str_replace('/', '-', $dataLiberacaoPagto)));
		} else {
			$dataLiberacaoPagto = null;
		}
		$dataInicioContrato = $this->input->post('data_inicio_contrato');
		if ($dataInicioContrato) {
			$dataInicioContrato = date('Y-m-d', strtotime(str_replace('/', '-', $dataInicioContrato)));
		} else {
			$dataInicioContrato = null;
		}
		$dataTerminoContrato = $this->input->post('data_termino_contrato');
		if ($dataTerminoContrato) {
			$dataTerminoContrato = date('Y-m-d', strtotime(str_replace('/', '-', $dataTerminoContrato)));
		} else {
			$dataTerminoContrato = null;
		}
		$valorExtra1 = $this->input->post('valor_extra_1');
		if ($valorExtra1) {
			$valorExtra1 = str_replace(['.', ','], ['', '.'], $valorExtra1);
		} else {
			$valorExtra1 = null;
		}
		$valorExtra2 = $this->input->post('valor_extra_2');
		if ($valorExtra2) {
			$valorExtra2 = str_replace(['.', ','], ['', '.'], $valorExtra2);
		} else {
			$valorExtra2 = null;
		}
		$justificativa1 = $this->input->post('justificativa_1');
		if (strlen($justificativa1) == 0) {
			$justificativa1 = null;
		}
		$justificativa2 = $this->input->post('justificativa_2');
		if (strlen($justificativa2) == 0) {
			$justificativa2 = null;
		}
		$tipoPagamento = $this->input->post('tipo_pagamento');
		if (strlen($tipoPagamento) == 0) {
			$tipoPagamento = null;
		}
		$observacoes = $this->input->post('observacoes');
		if (strlen($observacoes) == 0) {
			$observacoes = null;
		}


		$pagamentoProporcional = $this->input->post('pagamento_proporcional');

		$idCuidador = $alocado->id_cuidador;

		$data = [
			'id_alocacao' => $alocado->id_alocacao,
			'id_cuidador' => $idCuidador,
			'pagamento_proporcional_inicio' => in_array($mes, [1, 7]) ? $pagamentoProporcional : null,
			'pagamento_proporcional_termino' => in_array($mes, [7, 12]) ? $pagamentoProporcional : null
		];

		if ($substituto) {
			$data['data_liberacao_pagto_sub'] = $dataLiberacaoPagto;
			$data['data_inicio_contrato_sub'] = $dataInicioContrato;
			$data['data_termino_contrato_sub'] = $dataTerminoContrato;
			$data['nota_fiscal_sub'] = $numeroNotaFiscal;
			$data['valor_extra1_sub'] = $valorExtra1;
			$data['valor_extra2_sub'] = $valorExtra2;
			$data['justificativa1_sub'] = $justificativa1;
			$data['justificativa2_sub'] = $justificativa2;
			$data['tipo_pagamento_sub'] = $tipoPagamento;
			$data['observacoes_sub'] = $observacoes;
		} else {
			$data['data_liberacao_pagto_mes' . $idMes] = $dataLiberacaoPagto;
			$data['data_inicio_contrato_mes' . $idMes] = $dataInicioContrato;
			$data['data_termino_contrato_mes' . $idMes] = $dataTerminoContrato;
			$data['nota_fiscal_mes' . $idMes] = $numeroNotaFiscal;
			$data['valor_extra1_mes' . $idMes] = $valorExtra1;
			$data['valor_extra2_mes' . $idMes] = $valorExtra2;
			$data['justificativa1_mes' . $idMes] = $justificativa1;
			$data['justificativa2_mes' . $idMes] = $justificativa2;
			$data['tipo_pagamento_mes' . $idMes] = $tipoPagamento;
			$data['observacoes_mes' . $idMes] = $observacoes;
		}

		$this->db->trans_start();


		$id = $this->input->post('id');

		$pagamentoPrestador = $this->db
			->where(['id' => $id, 'id_cuidador' => $idCuidador])
			->get('ei_pagamento_prestador')
			->num_rows();

		if ($pagamentoPrestador) {
			$this->db->update('ei_pagamento_prestador', $data, ['id' => $id]);
		} else {
			$this->db->insert('ei_pagamento_prestador', $data);
			$id = $this->db->insert_id();
		}


		$idTotalizacao = $this->input->post('id_totalizacao');
		$totalHorasFaturadas = $this->input->post('total_horas_faturadas');
		$valorPagamento = $this->input->post('valor_pagamento');
		$valorTotal = $this->input->post('valor_total');


		foreach ($idTotalizacao as $k => $totalizacao) {
			/*if ($substituto === '2') {
				$this->db->set('total_horas_faturadas_sub2', $totalHorasFaturadas[$k]);
				$this->db->set('valor_pagamento_sub2', str_replace(['.', ','], ['', '.'], $valorPagamento[$k]));
				$this->db->set('valor_total_sub2', str_replace(['.', ','], ['', '.'], $valorTotal[$k]));
			} elseif ($substituto === '1') {
				$this->db->set('total_horas_faturadas_sub1', $totalHorasFaturadas[$k]);
				$this->db->set('valor_pagamento_sub1', str_replace(['.', ','], ['', '.'], $valorPagamento[$k]));
				$this->db->set('valor_total_sub1', str_replace(['.', ','], ['', '.'], $valorTotal[$k]));
			} else {

			}*/

			$this->db->set('total_horas_faturadas_mes' . $idMes, $totalHorasFaturadas[$k]);
			$this->db->set('valor_pagamento_mes' . $idMes, str_replace(['.', ','], ['', '.'], $valorPagamento[$k]));
			$this->db->set('valor_total_mes' . $idMes, str_replace(['.', ','], ['', '.'], $valorTotal[$k]));
			if ($totalizacao) {
				$this->db->where('id', $totalizacao)->update('ei_alocados_totalizacao');
			} else {
				$this->db->insert('ei_alocados_totalizacao');
			}
		}


		$this->db->trans_complete();
		$status = $this->db->trans_status();

		/*if ($status) {
			$retornoPP = $this->db
				->select("b.nome, a.observacoes_mes{$idMes} AS observacoes, a.observacoes_sub", false)
				->join('usuarios b', 'b.id = a.id_cuidador')
				->where('a.id', $id)->get('ei_pagamento_prestador a')->row();

			$retornoT = $this->db
				->select("total_horas_faturadas_mes{$idMes} AS total_horas_faturadas_mes, total_horas_faturadas_sub1", false)
				->select("valor_pagamento_mes{$idMes} AS valor_pagamento_mes, valor_pagamento_sub1", false)
				->select("valor_total_mes{$idMes} AS valor_total_mes, valor_total_sub1", false)
				->where_in('id', $idTotalizacao)
				->get('ei_alocados_totalizacao')->result();

			$retornoA = $this->db
				->select('aluno')
				->where('id_alocacao_escola', $alocado->id_alocacao_escola)
				->order_by('aluno', 'asc')
				->get('ei_matriculados')
				->result_array();

			$this->db
				->set('escola', $alocado->escola)
				->set('colaborador', $retornoPP->nome)
				->set('alunos', implode(', ', array_column($retornoA, 'aluno')))
				->set('data', date('Y-m-d H:i:s'))
				->set('mes_faturamento', $mes)
				->set('id_usuario', $this->session->userdata('id'))
				->set('nome_usuario', $this->session->userdata('nome'))
				->set('observacoes', ($substituto ? $retornoPP->observacoes_sub : $retornoPP->observacoes))
				->set('quantidades', implode(', ', array_column($retornoT, $substituto ? 'total_horas_faturadas_sub1' : 'total_horas_faturadas_mes')))
				->set('valores', implode(', ', array_column($retornoT, $substituto ? 'valor_pagamento_sub1' : 'valor_pagamento_mes')))
				->set('valores_totais', implode(', ', array_column($retornoT, $substituto ? 'valor_total_sub1' : 'valor_total_mes')))
				->insert('ei_log_pagamento_prestador');

			$idLog = $this->db->insert_id();
			$log = $this->db->where('id', $idLog)->get('ei_log_pagamento_prestador')->row();

			$status = 'ESCOLA: ' . $log->escola . '<br>' .
				'COLABORADOR: ' . $log->colaborador . '<br>' .
				'ALUNO(S): ' . $log->alunos . '<br>' .
				'DATA/HORA: ' . date('d/m/Y H:i:s', strtotime($log->data)) . '<br>' .
				'MÊS FATURAMENTOA: ' . $log->mes_faturamento . '<br>' .
				'USUÁRIO: ' . $log->nome_usuario . '<br>' .
				'OBSERVAÇÕES: ' . $log->observacoes . '<br>' .
				'QUANTIDADE: ' . $log->quantidades . '<br>' .
				'VALOR: ' . $log->valores . '<br>' .
				'VALOR TOTAL: ' . $log->valores_totais . '<br>';
		}*/


		echo json_encode(['status' => $status]);
	}


	//==========================================================================


	public function ajaxSaveControleMateriais()
	{
		$data = $this->input->post();
		$id = $data['id'];
		$insumos = $data['qtde_insumos'];
		unset($data['id'], $data['qtde_insumos']);
		if (empty($insumos)) {
			$insumos = [];
		}
		if (strlen($data['status']) == 0) {
			$data['status'] = null;
		}


		$this->db->trans_start();


		if (array_filter($insumos) or $data['status']) {
			if ($id) {
				$this->db->update('ei_frequencias', $data, ['id' => $id]);
			} else {
				$this->db->insert('ei_frequencias', $data);
				$id = $this->db->insert_id();
			}


			$this->db->select('id, id_insumo');
			$this->db->where('id_frequencia', $id);
			$rows = $this->db->get('ei_controle_materiais')->result();
			$controleMaterial = [];
			foreach ($rows as $row) {
				$controleMaterial[$row->id_insumo] = $row->id;
			}
		} else {
			$this->db->delete('ei_frequencias', ['id' => $id]);
		}


		foreach ($insumos as $id_insumo => $qtde) {
			$data = [
				'id_frequencia' => $id,
				'id_insumo' => $id_insumo,
				'qtde' => $qtde
			];


			if (isset($controleMaterial[$id_insumo])) {
				if ($qtde > 0) {
					$this->db->update('ei_controle_materiais', $data, ['id' => $controleMaterial[$id_insumo]]);
				} else {
					$this->db->delete('ei_controle_materiais', ['id' => $controleMaterial[$id_insumo]]);
				}
			} elseif ($qtde > 0) {
				$this->db->insert('ei_controle_materiais', $data);
			}
		}


		$this->db->trans_complete();
		$status = $this->db->trans_status();


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxAddVisita()
	{
		$data = $this->input->post();


		$this->db->select('nome');
		$this->db->where('id', $data['unidade_visitada']);
		$escola = $this->db->get('ei_escolas')->row();


		$this->db->select('nome');
		$this->db->where('id', $data['id_supervisor_visitante']);
		$supervisor = $this->db->get('usuarios')->row();


		$data['escola'] = $escola->nome ?? null;
		$data['supervisor_visitante'] = $supervisor->nome ?? null;
		$id = $data['id'];
		unset($data['id']);

		if ($data['data_visita']) {
			$data['data_visita'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_visita'])));
		}
		if ($data['data_visita_anterior']) {
			$data['data_visita_anterior'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_visita_anterior'])));
		} else {
			$data['data_visita_anterior'] = null;
		}
		$data['gastos_materiais'] = str_replace(['.', ','], ['', '.'], $data['gastos_materiais']);


		$status = $this->db->insert('ei_mapa_visitacao', $data);


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxEditCargaHoraria()
	{
		$data = $this->db
			->where('id', $this->input->post('id'))
			->get('ei_carga_horaria')
			->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'Carga horária não encontrada ou excluída recentemente.']));
		}

		$data->dia = date('d', strtotime($data->data));
		if ($data->horario_entrada) {
			$data->horario_entrada = date('H:i', strtotime($data->horario_entrada));
		}
		if ($data->horario_saida) {
			$data->horario_saida = date('H:i', strtotime($data->horario_saida));
		}
		if ($data->horario_entrada_1) {
			$data->horario_entrada_1 = date('H:i', strtotime($data->horario_entrada_1));
		}
		if ($data->horario_saida_1) {
			$data->horario_saida_1 = date('H:i', strtotime($data->horario_saida_1));
		}
		if ($data->carga_horaria) {
			$data->carga_horaria = date('H:i', strtotime($data->carga_horaria));
		}
		if ($data->saldo_dia) {
			$data->saldo_dia = date('H:i', strtotime($data->saldo_dia));
		}

		echo json_encode($data);
	}


	//==========================================================================


	public function ajaxAddCargaHoraria()
	{
		$data = $this->input->post();

		$supervisao = $this->db
			->select('a.id')
			->join('usuarios b', 'b.id = a.id_usuario')
			->join('ei_supervisores c', 'c.id_coordenacao = a.id OR c.id_supervisor = a.id_usuario')
			->join('ei_escolas d', 'd.id = c.id_escola')
			->join('ei_diretorias e', 'e.id = d.id_diretoria')
			->where('e.depto', $data['depto'])
			->where('e.id', $data['diretoria'])
			->where('a.id_usuario', $data['supervisor'])
			->where('a.ano', $data['ano'])
			->where('a.semestre', $data['semestre'])
			->group_by('a.id')
			->get('ei_coordenacao a')
			->row();

		if (empty($supervisao)) {
			exit(json_encode(['erro' => 'O Supervisor não foi encontrado.']));
		}

		$data['data'] = date('Y-m-d', mktime(0, 0, 0, $data['mes'], $data['dia'], $data['ano']));

		$mes = $data['mes'];
		$semestre = $data['semestre'];
		unset($data['depto'], $data['diretoria'], $data['supervisor'], $data['dia'], $data['mes'], $data['ano'], $data['semestre']);

		$data['id_supervisao'] = $supervisao->id;

		foreach ($data as &$row) {
			if (strlen($row) == 0) {
				$row = null;
			}
		}

		$this->load->helper('time');

		$data['total'] = secToTime(
			(timeToSec($data['horario_saida']) - timeToSec($data['horario_entrada'])) +
			(timeToSec($data['horario_saida_1']) - timeToSec($data['horario_entrada_1'])), false);

		$data['saldo_dia'] = secToTime(timeToSec($data['total']) - timeToSec($data['carga_horaria']));

		$this->db->trans_start();
		$this->db->insert('ei_carga_horaria', $data);
		$this->updateSaldoAcumuladoBancoHoras($supervisao->id, $mes, $semestre);
		$this->db->trans_complete();

		if ($this->db->trans_status() === false) {
			exit(json_encode(['erro' => 'Erro ao cadastrar o Banco de Horas.']));
		}

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function ajaxUpdateCargaHoraria()
	{
		$data = $this->input->post();

		$data['data'] = date('Y-m-d', mktime(0, 0, 0, $data['mes'], $data['dia'], $data['ano']));

		if (empty($data['id_supervisao'])) {
			exit(json_encode(['erro' => 'O Supervisor não foi encontrado.']));
		}

		$mes = $data['mes'];
		$semestre = $data['semestre'];
		unset($data['depto'], $data['diretoria'], $data['supervisor'], $data['dia'], $data['mes'], $data['ano'], $data['semestre']);

		foreach ($data as &$row) {
			if (strlen($row) == 0) {
				$row = null;
			}
		}

		$this->load->helper('time');

		$data['total'] = secToTime(
			(timeToSec($data['horario_saida']) - timeToSec($data['horario_entrada'])) +
			(timeToSec($data['horario_saida_1']) - timeToSec($data['horario_entrada_1'])), false);

		$data['saldo_dia'] = secToTime(timeToSec($data['total']) - timeToSec($data['carga_horaria']));

		$this->db->trans_start();
		$this->db->update('ei_carga_horaria', $data, ['id' => $data['id']]);
		$this->updateSaldoAcumuladoBancoHoras($data['id_supervisao'], $mes, $semestre);
		$this->db->trans_complete();

		if ($this->db->trans_status() === false) {
			exit(json_encode(['erro' => 'Erro ao cadastrar o Banco de Horas.']));
		}

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	private function validarCargaHoraria()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('data', '"Data"', 'valid_date');
		$this->form_validation->set_rules('data_1', '"Data"', 'valid_date');
		$this->form_validation->set_rules('horario_entrada', '"Horário entrada"', 'valid_time');
		$this->form_validation->set_rules('horario_saida', '"Horário saída"', 'valid_time');
		$this->form_validation->set_rules('horario_entrada_1', '"Horário entrada"', 'valid_time');
		$this->form_validation->set_rules('horario_saida_1', '"Horário saída"', 'valid_time');

		if ($this->form_validation->run() == false) {
			exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
		}
	}


	//==========================================================================


	public function ajaxDeleteCargaHoraria()
	{
		$cargaHoraria = $this->db
			->select('a.id, a.id_supervisao, MONTH(a.data) AS mes, b.semestre', false)
			->join('ei_coordenacao b', 'b.id = a.id_supervisao')
			->where('a.id', $this->input->post('id'))
			->get('ei_carga_horaria a')
			->row();

		if (empty($cargaHoraria)) {
			exit(json_encode(['erro' => 'O Supervisor não foi encontrado.']));
		}

		$this->db->trans_start();
		$this->db->update('ei_carga_horaria', ['saldo_dia' => 0], ['id' => $cargaHoraria->id]);
		$this->updateSaldoAcumuladoBancoHoras($cargaHoraria->id_supervisao, $cargaHoraria->mes, $cargaHoraria->semestre);
		$this->db->delete('ei_carga_horaria', ['id' => $cargaHoraria->id]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === false) {
			exit(json_encode(['erro' => 'Não foi possível excluir a carga horária.']));
		}

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	private function updateSaldoAcumuladoBancoHoras($idSupervisao, $mes, $semestre)
	{
		$idMes = intval($mes) - ($semestre > 1 ? 6 : 0);

		$cargaHoraria = $this->db
			->select('b.id_usuario, b.ano, b.semestre')
			->select(['SUM(IFNULL(TIME_TO_SEC(a.saldo_dia), 0)) AS novo_saldo_segundos_mes'], false)
			->join('ei_coordenacao b', 'b.id = a.id_supervisao')
			->where('a.id_supervisao', $idSupervisao)
			->where('MONTH(a.data)', $mes)
			->get('ei_carga_horaria a')
			->row();

		$bancoHoras = $this->db
			->select("id, saldo_mes{$idMes} AS antigo_saldo_mes", false)
			->where('id_supervisao', $idSupervisao)->get('ei_saldo_banco_horas')->row();

		if ($this->load->is_loaded('time') === false) {
			$this->load->helper('time');
		}

		if (isset($cargaHoraria->novo_saldo_segundos_mes)) {
			$data = ['saldo_mes' . $idMes => secToTime($cargaHoraria->novo_saldo_segundos_mes)];
			if (isset($bancoHoras->id)) {
				$this->db->update('ei_saldo_banco_horas', $data, ['id_supervisao' => $idSupervisao]);
			} else {
				$data['id_supervisao'] = $idSupervisao;
				$this->db->insert('ei_saldo_banco_horas', $data);
			}
		} else {
			$this->db->delete('ei_saldo_banco_horas', ['id_supervisao' => $idSupervisao]);
		}

		$coordenacao = $this->db->select('id, saldo_acumulado_horas AS saldo_acumulado')
			->where('id_usuario', $cargaHoraria->id_usuario)
			->where("CONCAT(ano, '.', semestre) >= '{$cargaHoraria->ano}.{$cargaHoraria->semestre}'")
			->where('is_supervisor', 1)
			->get('ei_coordenacao')
			->result();

		foreach ($coordenacao as $row) {
			$saldoAcumulado = timeToSec($row->saldo_acumulado);
			$diferenca = 0;
			if (!empty($bancoHoras->antigo_saldo_mes)) {
				$diferenca -= timeToSec($bancoHoras->antigo_saldo_mes);
			}
			if (!empty($cargaHoraria->novo_saldo_segundos_mes)) {
				$diferenca += $cargaHoraria->novo_saldo_segundos_mes;
			}
			$saldoAcumulado += $diferenca;
			$this->db
				->set('saldo_acumulado_horas', secToTime($saldoAcumulado))
				->where('id', $row->id)
				->update('ei_coordenacao');
		}
	}


	//==========================================================================


	public function salvarCargaHorariaAcumulada()
	{
		$alocacao = $this->db
			->select('id, saldo_acumulado')
			->where('empresa', $this->session->userdata('empresa'))
			->get('ei_alocacac')
			->row();

		$saldoMes = $this->input->post('saldo_mes');
		$saldoAcumulado = $this->input->post('saldo_acumulado');
		$data = [
			'saldo_mensal' => $saldoMes,
			'saldo_acumulado' => secToTime(timeToSec($alocacao->saldo_acumulado) + timeToSec($saldoAcumulado))
		];

		$this->db->update('ei_alocacao', $data, ['id' => $alocacao->id]);

		echo json_encode(['status' => true]);
	}


	//==========================================================================


	public function ajaxUpdateVisita()
	{
		$data = $this->input->post();


		$this->db->select('nome');
		$this->db->where('id', $data['unidade_visitada']);
		$escola = $this->db->get('ei_escolas')->row();


		$this->db->select('nome');
		$this->db->where('id', $data['id_supervisor_visitante']);
		$supervisor = $this->db->get('usuarios')->row();


		$data['escola'] = $escola->nome ?? null;
		$data['supervisor_visitante'] = $supervisor->nome ?? null;
		$id = $data['id'];
		unset($data['id']);

		if ($data['data_visita']) {
			$data['data_visita'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_visita'])));
		} else {
			$data['data_visita'] = null;
		}

		if ($data['data_visita_anterior']) {
			$data['data_visita_anterior'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_visita_anterior'])));
		} else {
			$data['data_visita_anterior'] = null;
		}
		$data['gastos_materiais'] = str_replace(['.', ','], ['', '.'], $data['gastos_materiais']);


		$status = $this->db->update('ei_mapa_visitacao', $data, ['id' => $id]);

		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxDelete()
	{
		$id = $this->input->post('id');
		$status = $this->db->delete('ei_apontamento', ['id' => $id]);

		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxDeleteAlocados()
	{
		$tipo = $this->input->post('tipo');
		if (empty($tipo)) {
			exit(json_encode(['erro' => 'O tipo de dado é obrigatório.']));
		}
		$id = $this->input->post('id_alocado');
		$periodo = $this->input->post('periodo');

		$this->db->trans_start();

		$alocado = $this->db
			->select('a.cuidador, b.escola')
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->where('a.id', $id)
			->get('ei_alocados a')
			->row();

		$arrPeriodos = ['Madrugada', 'Manhã', 'Tarde', 'Noite'];

		$dataLog = [
			'data' => date('Y-m-d H:i:s'),
			'id_usuario' => $this->session->userdata('id'),
			'nome_usuario' => $this->session->userdata('nome'),
			'operacao' => 'Desalocação',
			'nome_escola' => $alocado->escola,
			'id_alocado' => $id,
			'nome_cuidador' => $alocado->cuidador,
			'periodo' => $arrPeriodos[$periodo]
		];

		if ($tipo === '2') {
			$dataLog['opcao'] = 'Aluno';

			$this->db->select('b.id, b.id_alocado, d.aluno');
			$this->db->join('ei_alocados_horarios b', 'b.id_alocado = a.id');
			$this->db->join('ei_matriculados_turmas c', 'c.id_alocado_horario = b.id');
			$this->db->join('ei_matriculados d', 'd.id = c.id_matriculado AND d.id_alocacao_escola = a.id_alocacao_escola');
			$this->db->where('b.id_alocado', $id);
			$this->db->where('b.periodo', $periodo);
			$alocado = $this->db->get('ei_alocados a')->result();


			$this->db->where_in('id', array_column($alocado, 'id') + [0]);
			$this->db->delete('ei_alocados_horarios');


			$alocadoExcluido = $this->db
				->select('a.id')
				->join('ei_alocados_horarios b', "b.id_alocado = a.id AND b.periodo != '{$periodo}'", 'left')
				->where('a.id', $id)
				->where('b.id', null)
				->get('ei_alocados a')
				->row();

			$this->db->delete('ei_alocados', ['id' => $alocadoExcluido->id]);


			$this->db->select('a.id');
			$this->db->join('ei_matriculados_turmas b', 'b.id_matriculado = a.id', 'left');
			$this->db->join('ei_alocados_horarios c', "c.id = b.id_alocado_horario AND c.periodo != '{$periodo}'", 'left');
			$this->db->where_in('a.aluno', array_column($alocado, 'aluno') + [0]);
			$this->db->where('c.id IS NULL', null, false);
			$this->db->group_by('a.id');
			$alunos = $this->db->get('ei_matriculados a')->result();


			$this->db->where_in('id', array_column($alunos, 'id') + [0]);
			$this->db->delete('ei_matriculados');

		} elseif ($tipo === '1') {
			$dataLog['opcao'] = 'Escola';

			$this->db->select('b.escola, a.id_cuidador, b.id_alocacao');
			$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
			$this->db->join('ei_alocados_horarios c', 'c.id_alocado = a.id');
			$this->db->where('a.id', $id);
			$this->db->where('c.periodo', $periodo);
			$alocado = $this->db->get('ei_alocados a')->result();

			$escolas = array_column($alocado, 'escola') + [0];

			$this->db->where_in('escola', $escolas);
			$this->db->delete('ei_alocacao_escolas');

			$this->db
				->where_in('id_alocacao', array_column($alocado, 'id_alocacao') + [0])
				->where_in('id_cuidador', array_column($alocado, 'id_cuidador') + [0])
				->delete('ei_pagamento_prestador');
		}

		$this->db->insert('ei_log_desalocacao', $dataLog);

		$this->db->trans_complete();

		$status = $this->db->trans_status();

		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxDeleteControleMateriais()
	{
		$id = $this->input->post('id');
		$status = $this->db->delete('ei_frequencias', ['id' => $id]);

		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function ajaxDeleteVisita()
	{
		$id = $this->input->post('id');
		$status = $this->db->delete('ei_mapa_visitacao', ['id' => $id]);

		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function fecharMes()
	{
		$busca = $this->input->post();
		// verifica se o mês alocado existe
		$alocacao = $this->db
			->select('id')
			->where('id_empresa', $this->session->userdata('empresa'))
			->where('depto', $busca['depto'])
			->where('id_diretoria', $busca['diretoria'])
			->where('id_supervisor', $busca['supervisor'])
			->where('ano', $busca['ano'])
			->where('semestre', $busca['semestre'])
			->get('ei_alocacao')
			->row();

		if (empty($alocacao)) {
			exit(json_encode(['erro' => 'O semestre alocado não foi encontrado.']));
		}

		// prepara as variáveis de tempo
		$anoMes = $busca['ano'] . '-' . $busca['mes'];
		$idMes = $this->getIdMes($busca['mes'], $busca['semestre']);
		$diaPrimeiroDoProximoMes = $this->db->query("SELECT DATE_ADD('{$anoMes}-01', INTERVAL 1 MONTH) AS dia")->row()->dia;


		// retorna dados modificados dos horários do mês selecionado
		$this->db->select('a.id, a.id_alocado')
			->select(["SUM(IF(a.id_cuidador_sub1 IS NOT NULL AND DATE_FORMAT(a.data_substituicao1, '%Y-%m') < '$anoMes', 
								IF(DATE_FORMAT(f.data, '%Y-%m') = '{$anoMes}' AND f.status IN ('FA', 'PV', 'FE', 'EM', 'RE'), 1, 0), 
								IF(e.data < IFNULL(a.data_substituicao1, '{$diaPrimeiroDoProximoMes}') AND e.status IN ('FA', 'PV', 'FE', 'EM', 'RE'), 1, 0))
						) AS desconto_mes{$idMes}"], false)
			->select(["SUM(IF(a.id_cuidador_sub1 IS NOT NULL AND DATE_FORMAT(a.data_substituicao1, '%Y-%m') < '$anoMes', 
								IF(DATE_FORMAT(f.data, '%Y-%m') = '{$anoMes}' AND f.status IN ('FA', 'PV', 'FE', 'EM', 'RE') AND (f.desconto_sub1 OR f.desconto_sub2), 1, 0), 
								IF(e.data < IFNULL(a.data_substituicao1, '{$diaPrimeiroDoProximoMes}') AND e.status IN ('FA', 'PV', 'FE', 'EM', 'RE') AND (e.desconto_sub1 OR e.desconto_sub2), 1, 0))
						) AS endosso_mes{$idMes}"], false)
			->select("NULL AS total_mes{$idMes}", false)
			->select("NULL AS total_endossado_mes{$idMes}", false)
			->select(["(CASE MONTH(a.data_substituicao1) WHEN '{$busca['mes']}' THEN SUM(DISTINCT IF(f.status IN ('FA', 'PV', 'FE', 'EM', 'RE'), 1, 0)) ELSE a.desconto_sub1 END) AS desconto_sub1"], false)
			->select(["(CASE MONTH(a.data_substituicao1) WHEN '{$busca['mes']}' THEN SUM(DISTINCT IF(f.status IN ('FA', 'PV', 'FE', 'EM', 'RE') AND (f.desconto_sub1 OR f.desconto_sub2), 1, 0)) ELSE a.endosso_sub1 END) AS endosso_sub1"], false)
			->select("(CASE MONTH(a.data_substituicao1) WHEN '{$busca['mes']}' THEN NULL ELSE a.total_sub1 END) AS total_sub1", false)
			->select("(CASE MONTH(a.data_substituicao1) WHEN '{$busca['mes']}' THEN NULL ELSE a.total_endossado_sub1 END) AS total_endossado_sub1", false)
			->select(["(CASE MONTH(a.data_substituicao2) WHEN '{$busca['mes']}' THEN SUM(DISTINCT IF(g.status IN ('FA', 'PV', 'FE', 'EM', 'RE'), 1, 0)) ELSE a.desconto_sub2 END) AS desconto_sub2"], false)
			->select(["(CASE MONTH(a.data_substituicao2) WHEN '{$busca['mes']}' THEN SUM(DISTINCT IF(g.status IN ('FA', 'PV', 'FE', 'EM', 'RE') AND (g.desconto_sub1 OR g.desconto_sub2), 1, 0)) ELSE a.endosso_sub2 END) AS endosso_sub2"], false)
			->select("(CASE MONTH(a.data_substituicao2) WHEN '{$busca['mes']}' THEN NULL ELSE a.total_sub2 END) AS total_sub2", false)
			->select("(CASE MONTH(a.data_substituicao2) WHEN '{$busca['mes']}' THEN NULL ELSE a.total_endossado_sub2 END) AS total_endossado_sub2", false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->join('ei_apontamento e', "e.id_alocado = b.id AND (a.periodo = e.periodo OR e.periodo IS NULL) AND DATE_FORMAT(e.data, '%Y-%m') = '{$anoMes}' AND DATE_FORMAT(e.data, '%w') = a.dia_semana AND (e.id_usuario IS NULL OR e.id_usuario = b.id_cuidador)", 'left')
			->join('ei_apontamento f', "f.id_alocado = b.id AND (a.periodo = f.periodo OR f.periodo IS NULL) AND DATE_FORMAT(f.data, '%Y-%m') = '{$anoMes}' AND DATE_FORMAT(f.data, '%w') = a.dia_semana AND (f.id_usuario IS NULL OR f.id_usuario = a.id_cuidador_sub1)", 'left')
			->join('ei_apontamento g', "f.id_alocado = b.id AND (a.periodo = g.periodo OR g.periodo IS NULL) AND DATE_FORMAT(g.data, '%Y-%m') = '{$anoMes}' AND DATE_FORMAT(g.data, '%w') = a.dia_semana AND (g.id_usuario IS NULL OR g.id_usuario = a.id_cuidador_sub2)", 'left')
			->where('d.id', $alocacao->id);
		if (!empty($busca['id_alocado'])) {
			$this->db->where('b.id', $busca['id_alocado']);
		}
		if (!empty($busca['periodo'])) {
			$this->db->where('a.periodo', $busca['periodo']);
		}
		$horariosDoMesAFechar = $this->db
			->group_by('a.id')
			->get('ei_alocados_horarios a')
			->result();


		// prepara a transação de dados
		$this->db->trans_start();

		// busca as totalizacoes atuais
		$totalizacoes = $this->db
			->where(['id_alocado' => $busca['id_alocado'], 'periodo' => $busca['periodo']])
			->get('ei_alocados_totalizacao')
			->result();

		// anula os valores as totalizacoes correspondentes
		foreach ($totalizacoes as $totalizacao) {
			$totalDias = array_filter([
				$idMes === 1 ? null : $totalizacao->total_dias_mes1,
				$idMes === 2 ? null : $totalizacao->total_dias_mes2,
				$idMes === 3 ? null : $totalizacao->total_dias_mes3,
				$idMes === 4 ? null : $totalizacao->total_dias_mes4,
				$idMes === 5 ? null : $totalizacao->total_dias_mes5,
				$idMes === 6 ? null : $totalizacao->total_dias_mes6,
				$idMes === 7 ? null : $totalizacao->total_dias_mes7
			]);

			if (count($totalDias) > 0) {
				$this->db
					->set('total_dias_mes' . $idMes, null)
					->set('dias_descontados_mes' . $idMes, null)
					->set('total_horas_mes' . $idMes, null)
					->set('horas_descontadas_mes' . $idMes, null)
					->set('total_horas_faturadas_mes' . $idMes, null)
					->set('valor_pagamento_mes' . $idMes, null)
					->set('valor_total_mes' . $idMes, null)
					->where('id', $totalizacao->id)
					->update('ei_alocados_totalizacao');
			} else {
				$this->db->delete('ei_alocados_totalizacao', ['id' => $totalizacao->id]);
			}
		}

		// salva as modificacoes nos horarios
		foreach ($horariosDoMesAFechar as $data) {
			$this->db->update('ei_alocados_horarios', $data, ['id' => $data->id]);
		}

		// competa a transacao
		$this->db->trans_complete();

		$status = $this->db->trans_status();


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function totalizarMes()
	{
		// verifica se o mes alocado existe
		$alocacao = $this->db
			->select('id')
			->where('id_empresa', $this->session->userdata('empresa'))
			->where('depto', $this->input->post('depto'))
			->where('id_diretoria', $this->input->post('diretoria'))
			->where('id_supervisor', $this->input->post('supervisor'))
			->where('ano', $this->input->post('ano'))
			->where('semestre', $this->input->post('semestre'))
			->get('ei_alocacao')
			->row();

		if (empty($alocacao)) {
			exit(json_encode(['erro' => 'O semestre alocado não foi encontrado.']));
		}

		// prepara as variaveis locais
		$idAlocado = $this->input->post('id_alocado');
		$periodo = $this->input->post('periodo');
		$mes = $this->input->post('mes');
		$anoMes = $this->input->post('ano') . '.' . $mes;
		$idMes = (int)$mes - ((int)$this->input->post('semestre') > 1 ? 6 : 0);

		// recupera dados modificados dos horarios
		$horarios = $this->db
			->select('a.id')
			->select("SEC_TO_TIME(TIME_TO_SEC(a.total_horas_mes{$idMes}) * (a.total_semanas_mes{$idMes} - a.desconto_mes{$idMes})) AS total_mes{$idMes}", false)
			->select("SEC_TO_TIME(TIME_TO_SEC(a.total_horas_mes{$idMes}) * (a.total_semanas_mes{$idMes} - a.desconto_mes{$idMes} + a.endosso_mes{$idMes})) AS total_endossado_mes{$idMes}", false)
			->select("IF(MONTH(a.data_substituicao1) = '{$mes}', SEC_TO_TIME(TIME_TO_SEC(a.total_horas_mes{$idMes}) * (a.total_semanas_sub1 - a.desconto_sub1)), a.total_sub1) AS total_sub1", false)
			->select("IF(MONTH(a.data_substituicao1) = '{$mes}', SEC_TO_TIME(TIME_TO_SEC(a.total_horas_mes{$idMes}) * (a.total_semanas_sub1 - a.desconto_sub1 + a.endosso_sub1)), a.total_endossado_sub1) AS total_endossado_sub1", false)
			->select("IF(MONTH(a.data_substituicao2) = '{$mes}', SEC_TO_TIME(TIME_TO_SEC(a.total_horas_mes{$idMes}) * (a.total_semanas_sub2 - a.desconto_sub2)), a.total_sub2) AS total_sub2", false)
			->select("IF(MONTH(a.data_substituicao2) = '{$mes}', SEC_TO_TIME(TIME_TO_SEC(a.total_horas_mes{$idMes}) * (a.total_semanas_sub2 - a.desconto_sub2 + a.endosso_sub1)), a.total_endossado_sub2) AS total_endossado_sub2", false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->where(($idAlocado and $periodo) ? ['b.id' => $idAlocado, 'a.periodo' => $periodo] : ['d.id' => $alocacao->id])
			->group_by('a.id')
			->get('ei_alocados_horarios a')
			->result_array();

		if (empty($horarios)) {
			exit(json_encode(['erro' => 'Nenhum horário de cuidador encontrado.']));
		}

		// prepara a transacao de dados
		$this->db->trans_start();

		// atualiza os horarios totalizados
		$this->db->update_batch('ei_alocados_horarios', $horarios, 'id');

		// recupera os dados modificados das totalizacoes do cuidador principal
		$totalizacoesPrincipais = $this->db
			->select('f.id, a.id_alocado, a.periodo, c.id AS id_cuidador, c.nome AS cuidador')
			->select("(CASE c.id WHEN a.id_cuidador_sub2 THEN 2 WHEN a.id_cuidador_sub1 THEN 1 ELSE NULL END) AS substituicao_semestral", false)
			->select('NULL AS substituicao_eventual', false)
			->select("SUM(a.total_semanas_mes{$idMes} - a.desconto_mes{$idMes}) AS total_dias_mes{$idMes}", false)
			->select("(
					   SELECT SEC_TO_TIME(SUM(CASE x.status WHEN 'FA' THEN IFNULL(TIME_TO_SEC(x.desconto), 0) END)) 
					   FROM ei_apontamento x
         			   WHERE x.id_alocado = b.id 
         			   		 AND (x.periodo = a.periodo OR x.periodo IS NULL)
         			   		 AND MONTH(x.data) = '{$mes}'
         					 AND (x.id_usuario = b.id_cuidador OR x.id_usuario IS NULL)
         			  ) AS dias_descontados_mes{$idMes}", false)
			->select("SEC_TO_TIME(SUM(TIME_TO_SEC(a.total_mes{$idMes})) + IFNULL((
						SELECT SUM(CASE WHEN x.status IN('FA', 'AT', 'SA', 'EU') THEN TIME_TO_SEC(x.desconto) END) 
						FROM ei_apontamento x
         				WHERE x.id_alocado = b.id 
         					  AND (x.periodo = a.periodo OR x.periodo IS NULL)
         					  AND MONTH(x.data) = '{$mes}'
         					  AND (x.id_usuario = b.id_cuidador OR x.id_usuario IS NULL)
         			 ), 0)) AS total_horas_mes{$idMes}", false)
			->select("(
					   SELECT SEC_TO_TIME(SUM(CASE WHEN x.status IN('AT', 'SA', 'EU') THEN TIME_TO_SEC(x.desconto) END)) 
					   FROM ei_apontamento x
         			   WHERE x.id_alocado = b.id 
         			   		 AND (x.periodo = a.periodo OR x.periodo IS NULL)
         			   		 AND MONTH(x.data) = '{$mes}'
         					 AND (x.id_usuario = b.id_cuidador OR x.id_usuario IS NULL)
         			  ) AS horas_descontadas_mes{$idMes}", false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('usuarios c', "c.id = (CASE WHEN a.id_cuidador_sub2 IS NOT NULL AND '{$anoMes}' > DATE_FORMAT(a.data_substituicao2, '%Y.%m') THEN a.id_cuidador_sub2 WHEN a.id_cuidador_sub1 IS NOT NULL AND '{$anoMes}' > DATE_FORMAT(a.data_substituicao1, '%Y.%m') THEN a.id_cuidador_sub1 ELSE b.id_cuidador END)")
			->join('ei_alocacao_escolas d', 'd.id = b.id_alocacao_escola')
			->join('ei_alocacao e', 'e.id = d.id_alocacao')
			->join('ei_alocados_totalizacao f', 'f.id_alocado = b.id AND f.periodo = a.periodo AND f.id_cuidador = c.id', 'left')
			->join('ei_apontamento g', "g.id_alocado = b.id AND g.periodo = a.periodo AND (a.dia_semana = DATE_FORMAT(g.data, '%w') OR g.status = 'EU') AND MONTH(g.data) = '{$mes}' AND g.data < (IFNULL(a.data_substituicao2, IFNULL(a.data_substituicao1, DATE_ADD(LAST_DAY(g.data), INTERVAL 1 DAY))))", 'left')
			->where(($idAlocado and $periodo) ? ['b.id' => $idAlocado, 'a.periodo' => $periodo] : ['e.id' => $alocacao->id])
			->group_by(['b.id', 'a.periodo', 'c.id'])
			->get('ei_alocados_horarios a')
			->result();

		// recupera os dados modificados das totalizacoes do cuidador substituto
		$totalizacoesSubstitutasSemestral = $this->db
			->select('f.id, a.id_alocado, a.periodo, c.id AS id_cuidador, c.nome AS cuidador')
			->select("IF(c.id = a.id_cuidador_sub2, 2, 1) AS substituicao_semestral", false)
			->select('NULL AS substituicao_eventual', false)
			->select("SUM(IF(c.id = a.id_cuidador_sub2, a.total_semanas_sub2 - a.desconto_sub2, a.total_semanas_sub1 - a.desconto_sub1)) AS total_dias_mes{$idMes}", false)
			->select("(
					   SELECT SEC_TO_TIME(SUM(CASE x.status WHEN 'FA' THEN IFNULL(TIME_TO_SEC(x.desconto), 0) END)) 
					   FROM ei_apontamento x
         			   WHERE x.id_alocado = b.id 
         			   		 AND x.periodo = a.periodo 
         			   		 AND MONTH(x.data) = '{$mes}'
         			   		 AND (x.id_usuario = a.id_cuidador_sub1 OR x.id_usuario IS NULL)
         			  ) AS dias_descontados_mes{$idMes}", false)
			->select("SEC_TO_TIME(SUM(TIME_TO_SEC(a.total_sub1))) AS total_horas_mes{$idMes}", false)
			->select("(SELECT SEC_TO_TIME(SUM(CASE WHEN x.status IN('AT', 'SA', 'EU') THEN TIME_TO_SEC(x.desconto) END)) 
					   FROM ei_apontamento x
         			   WHERE x.id_alocado = b.id 
         			   		 AND x.periodo = a.periodo 
         			   		 AND MONTH(x.data) = '{$mes}'
         			   		 AND (x.id_usuario = a.id_cuidador_sub1 OR x.id_usuario IS NULL)
         			  ) AS horas_descontadas_mes{$idMes}", false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('usuarios c', "c.id = IFNULL(a.id_cuidador_sub2, a.id_cuidador_sub1) AND '{$mes}' = MONTH(IF(c.id = a.id_cuidador_sub2, a.data_substituicao2, a.data_substituicao1))")
			->join('ei_alocacao_escolas d', 'd.id = b.id_alocacao_escola')
			->join('ei_alocacao e', 'e.id = d.id_alocacao')
			->join('ei_alocados_totalizacao f', 'f.id_alocado = b.id AND f.periodo = a.periodo AND f.id_cuidador = c.id', 'left')
			->join('ei_apontamento g', "g.id_alocado = b.id AND g.periodo = a.periodo AND (a.dia_semana = DATE_FORMAT(g.data, '%w') OR g.status = 'EU') AND MONTH(g.data) = '{$mes}' AND g.data >= (IFNULL(a.data_substituicao2, IFNULL(a.data_substituicao1, DATE_ADD(g.data, INTERVAL 1 DAY))))", 'left')
			->where(($idAlocado and $periodo) ? ['b.id' => $idAlocado, 'a.periodo' => $periodo] : ['e.id' => $alocacao->id])
			->where('c.id IS NOT NULL')
			->group_by(['b.id', 'a.periodo', 'c.id'])
			->get('ei_alocados_horarios a')
			->result();

		// recupera os dados modificados das totalizacoes dos cuidadores substitutos de eventos
		$totalizacoesSubstitutasEventuais = $this->db
			->select('f.id, a.id_alocado, a.periodo, d2.id AS id_cuidador, d2.nome AS cuidador')
			->select('NULL AS substituicao_semestral', false)
			->select(["IF(COUNT(a.id_alocado_sub1) > 0, 1, 0) + IF(COUNT(a.id_alocado_sub2) > 0, 2, 0) AS substituicao_eventual"], false)
			->select("COUNT(DISTINCT DAY(a.data)) AS total_dias_mes{$idMes}", false)
			->select(["SEC_TO_TIME(SUM(CASE WHEN a.status IN('FA', 'AT', 'SA', 'EU') THEN TIME_TO_SEC(CASE WHEN d2.id = a.id_alocado_sub1 THEN a.desconto_sub1 WHEN d2.id = a.id_alocado_sub2 THEN a.desconto_sub2 END) END)) AS total_horas_mes{$idMes}"], false)
			->select("SEC_TO_TIME(0) AS horas_descontadas_mes{$idMes}", false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id  = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id  = c.id_alocacao')
			->join('usuarios d2', 'd2.id  = a.id_alocado_sub1 OR d2.id  = a.id_alocado_sub2', 'left')
			->join('ei_alocados_totalizacao f', 'f.id_alocado = b.id AND f.periodo = a.periodo AND f.id_cuidador = d2.id', 'left')
			->where(($idAlocado and $periodo) ? ['b.id' => $idAlocado, 'a.periodo' => $periodo] : ['d.id' => $alocacao->id])
			->where('MONTH(a.data)', $mes)
			->where('d2.id IS NOT NULL')
			->group_by(['b.id', 'a.periodo', 'd2.id'])
			->get('ei_apontamento a')
			->result();

		// agrupa as totalizacoes recuperadas
		$totalizacoes = [];
		foreach ($totalizacoesPrincipais as $totalizacaoPrincipal) {
			$totalizacoes[] = $totalizacaoPrincipal;
		}
		foreach ($totalizacoesSubstitutasSemestral as $totalizacaoSubstitutaSemestral) {
			$totalizacoes[] = $totalizacaoSubstitutaSemestral;
		}
		foreach ($totalizacoesSubstitutasEventuais as $totalizacaoSubstitutaEventual) {
			$totalizacoes[] = $totalizacaoSubstitutaEventual;
		}
		// salva os dados das totalizacoes
		foreach ($totalizacoes as &$totalizacao) {
			$id = $totalizacao->id;
			unset($totalizacao->id);
			if ($id) {
				$this->db->update('ei_alocados_totalizacao', $totalizacao, ['id' => $id]);
			} else {
				$this->db->insert('ei_alocados_totalizacao', $totalizacao);
			}
		}

		// completa a transacao de dados
		$this->db->trans_complete();

		$status = $this->db->trans_status();


		echo json_encode(['status' => $status !== false]);
	}


	//==========================================================================


	public function planilhaFaturamento($idAlocado, $mes, $periodo, $is_pdf = false, $recuperar = false)
	{
		// prepara o cabecalho da planilha
		$empresa = $this->db
			->select('foto, foto_descricao')
			->where('id', $this->session->userdata('empresa'))
			->get('usuarios')
			->row();

		$usuario = $this->db
			->select('nome, email')
			->where('id', $this->session->userdata('id'))
			->get('usuarios')
			->row();

		// flag de substituto
		$substituto = $this->input->get_post('substituto');

		// recuperar dados da alocacao
		$alocacao = $this->db
			->select('c.id, b.id_escola, b.escola, f.cargo, f.funcao, c.ano, c.semestre')
			->select('d.nome AS nome_supervisor, IFNULL(e.nome, d.funcao) AS funcao_supervisor, d.email AS email_supervisor', false)
//			->select(["GROUP_CONCAT(DISTINCT c2.nome ORDER BY c2.nome SEPARATOR ',') AS ordem_servico"], false)
			->select(["(SELECT GROUP_CONCAT(DISTINCT x.ordem_servico ORDER BY x.ordem_servico ASC SEPARATOR ',') 
						FROM ei_alocacao_escolas x
       					WHERE x.id_escola = b.id_escola and x.id_alocacao = c.id) AS ordem_servico"], false)
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->join('ei_alocacao c', 'c.id = b.id_alocacao')
			->join('ei_ordem_servico c2', 'c2.nome = b.ordem_servico AND c2.ano = c.ano AND c2.semestre = c.semestre')
			->join('usuarios d', 'd.id = c.id_supervisor')
			->join('empresa_funcoes e', 'e.id = d.id_funcao', 'left')
			->join('ei_alocados_horarios f', 'f.id_alocado = a.id', 'left')
			->where('a.id', $idAlocado)
			->where('f.periodo', $periodo)
			->group_by('a.id')
			->get('ei_alocados a')
			->row();

		// dado do mes atual
		$idMes = $this->getIdMes($mes, $alocacao->semestre);
		$ordensServico = explode(',', $alocacao->ordem_servico);

		// recupera os dados do cuidador principal ou substituto
		$cuidadores = $this->db
			->select('b.id AS id_alocado, c.ordem_servico')
			->select(["(CASE WHEN '{$mes}' > MONTH(a.data_substituicao1) AND a.data_substituicao1 IS NOT NULL THEN a.id_cuidador_sub1 ELSE b.id_cuidador END) AS id"], false)
			->select(["(CASE WHEN '{$mes}' = MONTH(a.data_substituicao1) AND a.data_substituicao1 IS NOT NULL THEN a.id_cuidador_sub1 END) AS id_sub"], false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->join('usuarios e', 'e.id = a.id_cuidador_sub1', 'left')
			->where('d.id', $alocacao->id)
			->where('c.id_escola', $alocacao->id_escola)
			->where_in('c.ordem_servico', $ordensServico)
			->where('a.cargo', $alocacao->cargo)
			->where('a.funcao', $alocacao->funcao)
			->get('ei_alocados_horarios a')
			->result();
		$idCuidadores = [];
		$idAlocados = [];
		foreach ($cuidadores as $cuidador) {
			$idCuidador = $substituto ? $cuidador->id_sub : $cuidador->id;
			$idCuidadores[$idCuidador] = $idCuidador;
			$idAlocados[$cuidador->id_alocado] = $cuidador->id_alocado;
		}
		$strCuidadores = implode("', '", array_filter($idCuidadores) + [0]);

		// recupera dados de apresentacao da planilha
		$this->db
			->select('b.id_escola, b.escola, c.funcao, b2.semestre, b2.ano')
			->select(["GROUP_CONCAT(DISTINCT b.contrato ORDER BY b.contrato ASC SEPARATOR ', ') AS contrato"], false)
			->select(["GROUP_CONCAT(DISTINCT b.ordem_servico ORDER BY b.ordem_servico ASC SEPARATOR ', ') AS ordem_servico"], false);
		if ($substituto) {
			$this->db->select('NULL AS cuidador', false);
		} else {
			$this->db->select(["GROUP_CONCAT(DISTINCT f.cuidador ORDER BY a.cuidador ASC SEPARATOR ', ') AS cuidador"], false);
		}
		$this->db
			->select(["GROUP_CONCAT(DISTINCT c2.nome ORDER BY c2.nome ASC SEPARATOR ', ') AS cuidador_sub1"], false)
			->select(["GROUP_CONCAT(DISTINCT c3.nome ORDER BY c3.nome ASC SEPARATOR ', ') AS cuidador_sub2"], false)
			->select(["GROUP_CONCAT(DISTINCT e.aluno ORDER BY e.aluno ASC SEPARATOR ', ') AS alunos"], false)
			->select("g.id AS id_faturamento, g.observacoes_mes{$idMes} AS observacoes, MIN(c.dia_semana) AS dia_semana_inicial", false)
			->select(["IF(COUNT(c.dia_semana) > 1, MAX(c.dia_semana), NULL) AS dia_semana_final"], false)
			->select(["TIME_FORMAT(c.horario_inicio_mes{$idMes}, '%H:%i') AS horario_inicio"], false)
			->select(["TIME_FORMAT(c.horario_termino_mes{$idMes}, '%H:%i') AS horario_termino"], false)
			->select(["DATE_FORMAT(IF(MONTH(MIN(e.data_inicio)) = '{$mes}', MIN(e.data_inicio), CONCAT(b2.ano, '-', {$mes}, '-1')), '%d/%m/%Y') AS periodo_inicial"], false);
		if ($idMes === 1) {
			$this->db->select(["DATE_FORMAT(IFNULL(MAX(e.data_recesso),IFNULL(MAX(e.data_termino), IFNULL(DATE_SUB(MAX(c.data_substituicao1), INTERVAL 1 DAY), LAST_DAY(CONCAT(b2.ano, '-', {$mes}, '-1'))))), '%d/%m/%Y') AS periodo_final"], false);
		} else {
			$this->db->select(["DATE_FORMAT(IF(MONTH(MAX(e.data_termino)) = '{$mes}', MAX(e.data_termino), LAST_DAY(CONCAT(b2.ano, '-', {$mes}, '-1'))), '%d/%m/%Y') AS periodo_final"], false);
		}
		$data = $this->db
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->join('ei_alocacao b2', 'b2.id = b.id_alocacao')
			->join('ei_alocados_horarios c', 'c.id_alocado = a.id', 'left')
			->join('usuarios c2', "c2.id = c.id_cuidador_sub1 AND MONTH(c.data_substituicao1) = '{$mes}'", 'left')
			->join('usuarios c3', "c3.id = c.id_cuidador_sub2 AND MONTH(c.data_substituicao2) = '{$mes}'", 'left')
			->join('ei_matriculados_turmas d', 'd.id_alocado_horario = c.id', 'left')
			->join('ei_matriculados e', 'e.id = d.id_matriculado AND e.id_alocacao_escola = b.id', 'left')
			->join('ei_alocados_totalizacao f', "f.id_alocado = a.id AND f.periodo = c.periodo AND f.id_cuidador IN ('{$strCuidadores}') AND f.substituicao_eventual IS NULL", 'left')
			->join('ei_faturamento g', 'g.id_alocacao = b2.id AND g.id_escola = b.id_escola AND g.cargo = c.cargo AND g.funcao = c.funcao', 'left')
			->where_in('a.id', $idAlocados)
			->where_in('b.ordem_servico', $ordensServico)
			->where('b2.id', $alocacao->id)
			->where('b.id_escola', $alocacao->id_escola)
			->where('c.cargo', $alocacao->cargo)
			->where('c.funcao', $alocacao->funcao)
			->group_by('b.id_escola')
			->get('ei_alocados a')
			->row();

		// recupera dados de totalizacao
		$this->db
			->select('d.id_alocado, d.periodo, e.id AS id_totalizacao, d.cargo, d.funcao')
			->select("(CASE d.periodo WHEN 0 THEN 'Madrugada' WHEN 1 THEN 'Manhã' WHEN 2 THEN 'Tarde' WHEN 3 THEN 'Noite' END) AS nome_periodo", false);
		if ($recuperar) {
			$this->db
				->select(["SUM(d.total_semanas_mes{$idMes} - IFNULL(d.desconto_mes{$idMes}, 0) + IFNULL(d.endosso_mes{$idMes}, 0)) + IF(MONTH(d.data_substituicao1) = {$mes}, SUM(IFNULL(d.total_semanas_sub1, 0) - IFNULL(d.desconto_sub1, 0) + IFNULL(d.endosso_sub1, 0)), 0) AS total_dias"], false)
				->select(["TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IFNULL(d.total_endossado_mes{$idMes}, d.total_mes{$idMes})) + IF(MONTH(d.data_substituicao1) = {$mes}, TIME_TO_SEC(IFNULL(d.total_endossado_sub1, IFNULL(d.total_sub1, 0))), 0) ) + IFNULL(TIME_TO_SEC(e.horas_descontadas_mes{$idMes}), 0)), '%H:%i') AS total_horas"], false);
		} else {
			$this->db
				->select(["e.total_dias_mes{$idMes} AS total_dias"], false)
				->select(["TIME_FORMAT(e.total_horas_mes{$idMes}, '%H:%i') AS total_horas"], false);
		}
		$rows = $this->db
			->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola')
			->join('ei_alocacao c', 'c.id = b.id_alocacao')
			->join('ei_alocados_horarios d', 'd.id_alocado = a.id', 'left')
			->join('ei_alocados_totalizacao e', "e.id_alocado = a.id AND e.periodo = d.periodo AND e.id_cuidador IN ('{$strCuidadores}') AND e.substituicao_eventual IS NULL", 'left')
			->where_in('a.id', $idAlocados)
			->where_in('b.ordem_servico', $ordensServico)
			->where('c.id', $alocacao->id)
			->where('b.id_escola', $alocacao->id_escola)
			->where('d.cargo', $alocacao->cargo)
			->where('d.funcao', $alocacao->funcao)
			->group_by(['a.id', 'b.id_escola', 'd.cargo', 'd.funcao', 'd.periodo'])
			->get('ei_alocados a')
			->result();

		// prepara dados dos inputs de cada totalizacao
		$faturamentos = [];
		foreach ($rows as $row) {
			$faturamentos[] = [
				'id' => $row->id_totalizacao,
				'id_alocado' => $row->id_alocado,
				'periodo' => $row->periodo,
				'cargo' => $row->cargo,
				'funcao' => $row->funcao,
				'nome_periodo' => $row->nome_periodo,
				'dias' => intval($row->total_dias),
				'horas' => $row->total_horas
			];
		}

		// recupera dados dos horarios de trabalho
		$horarioTrabalho = $this->db
			->select('b.id, a.dia_semana, a.periodo')
			->select("TIME_FORMAT(IFNULL(a.horario_inicio_mes{$idMes}, '00:00:00'), '%H:%ih') AS inicio", false)
			->select("TIME_FORMAT(IFNULL(a.horario_termino_mes{$idMes}, '00:00:00'), '%H:%ih') AS termino", false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->where_in('b.id', $idAlocados)
			->where_in('c.ordem_servico', $ordensServico)
			->where('c.id_alocacao', $alocacao->id)
			->where('c.escola', $alocacao->escola)
			->where('a.cargo', $alocacao->cargo)
			->where('a.funcao', $alocacao->funcao)
			->group_by(['a.dia_semana', "a.horario_inicio_mes{$idMes}", "a.horario_termino_mes{$idMes}"])
			->order_by('a.dia_semana', 'asc')
			->order_by("a.horario_inicio_mes{$idMes}", 'asc')
			->order_by("a.horario_termino_mes{$idMes}", 'asc')
			->get('ei_alocados_horarios a')
			->result();

		// formata a data da apresentacao da planilha
		$this->load->library('Calendar');
		$semana = $this->calendar->get_day_names('long');
		$diasSemana = [];
		foreach ($horarioTrabalho as $horario) {
			$strHorario = $horario->inicio . '-' . $horario->termino;
			if (isset($diasSemana[$strHorario])) {
				$diasSemana[$strHorario][0] = str_replace('-feira', '', $diasSemana[$strHorario][0]);
				$diasSemana[$strHorario][1] = ' à ' . str_replace('-feira', '', $semana[$horario->dia_semana]);
			} else {
				$diasSemana[$strHorario][0] = $semana[$horario->dia_semana];
				$diasSemana[$strHorario][1] = '';
			}
			$diasSemana[$strHorario][2] = ', das ' . $horario->inicio . ' às ' . $horario->termino;
		}

		$semestre = $alocacao->semestre ?? 1;
		$dataFaturamento = date('Y-m-d', mktime(0, 0, 0, (int)$mes, 1, $alocacao->ano));
		$dataAtual = $this->input->get('data_atual');
		if ($dataAtual) {
			$dataAtual = date('Y-m-d', strtotime(str_replace('/', '-', $dataAtual)));
		} else {
			$dataAtual = date('Y-m-d');
		}
		$observacoes = $this->input->get('observacoes');
		if (strlen($observacoes) == 0 or $recuperar) {
			$observacoes = $data->observacoes;
		}

		// retorna conjunto de dados para a view da planilha
		$planilha = [
			'empresa' => $empresa,
			'usuario' => $usuario,
			'mesFaturamento' => $this->calendar->get_month_name(date('m', strtotime($dataFaturamento))),
			'anoFaturamento' => date('Y', strtotime($dataFaturamento)),
			'query_string' => "id_alocado={$idAlocado}&mes={$mes}&semestre={$semestre}&periodo={$periodo}&substituto={$substituto}",
			'is_pdf' => $is_pdf,
			'contrato' => $data->contrato,
			'escola' => $data->escola,
			'ordemServico' => $data->ordem_servico,
			'alunos' => $data->alunos,
			'profissional' => implode(', ', array_filter([$data->cuidador, $data->cuidador_sub1, $data->cuidador_sub2])),
			'nomePeriodo' => $data->periodo_inicial . ' a ' . $data->periodo_final,
			'observacoes' => $observacoes,
			'diasSemana' => array_values($diasSemana),
			'mesAno' => ucfirst($this->calendar->get_month_name($mes)) . '/' . $data->ano,
			'faturamentos' => $faturamentos,
			'supervisor' => $alocacao,
			'diaAtual' => date('d', strtotime($dataAtual)),
			'mesAtual' => $this->calendar->get_month_name(date('m', strtotime($dataAtual))),
			'anoAtual' => date('Y', strtotime($dataAtual))
		];


		return $this->load->view('ei/planilha_faturamento', $planilha, true);
	}


	//==========================================================================


	public function planilhaFaturamentoConsolidado($idDiretoria, $mes, $ano, $is_pdf = false, $recuperar = false)
	{
		// prepara o cabecalho da planilha
		$this->db->select('foto, foto_descricao');
		$this->db->where('id', $this->session->userdata('empresa'));
		$empresa = $this->db->get('usuarios')->row();

		$this->db->select('nome, email');
		$this->db->where('id', $this->session->userdata('id'));
		$usuario = $this->db->get('usuarios')->row();

		// prepara as variaveis locais
		$depto = $this->input->get_post('depto');
		$idSupervisor = $this->input->get_post('supervisor_filtrado');
		$semestre = $this->input->get_post('semestre');
		$idMes = intval($mes) - ($semestre > 1 ? 6 : 0);


		// recupera dados de apresentacao da planilha
		$this->db->select("GROUP_CONCAT(DISTINCT a.id ORDER BY a.id SEPARATOR ',') AS id", false);
		$this->db->select('c1.id AS id_medicao_mensal, a.diretoria, null AS valor_hora', false);
		$this->db->select("a.observacoes_mes{$idMes} AS observacoes", false);
		$this->db->select(["GROUP_CONCAT(DISTINCT b3.contrato ORDER BY b3.contrato SEPARATOR ', ') AS contratos"], false);
		$this->db->select(["GROUP_CONCAT(DISTINCT b2.nome ORDER BY b2.nome SEPARATOR ', ') AS ordens_servico"], false);
		$this->db->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id');
		$this->db->join('ei_ordem_servico_escolas b1', 'b1.id = b.id_os_escola');
		$this->db->join('ei_ordem_servico b2', 'b2.id = b1.id_ordem_servico AND b2.ano = a.ano AND b2.semestre = a.semestre');
		$this->db->join('ei_contratos b3', 'b3.id = b2.id_contrato');
		$this->db->join('ei_medicao_mensal c1', "c1.id_empresa = a.id_empresa AND c1.depto = a.depto AND c1.id_diretoria = a.id_diretoria AND c1.ano = a.ano AND c1.semestre = a.semestre AND c1.id_supervisor = '{$idSupervisor}'", 'left');
		$this->db->join('ei_alocados c', 'c.id_alocacao_escola = b.id', 'left');
		$this->db->join('ei_alocados_horarios d', 'd.id_alocado = c.id', 'left');
		$this->db->where('a.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('a.depto', $depto);
		$this->db->where('a.id_diretoria', $idDiretoria);
		if ($idSupervisor) {
			$this->db->where('a.id_supervisor', $idSupervisor);
		}
		$this->db->where('a.ano', $ano);
		$this->db->where('a.semestre', $semestre);
		$this->db->where('d.funcao IS NOT NULL', null, false);
		$this->db->group_by(['a.id_empresa', 'a.depto', 'a.diretoria', 'a.ano', 'a.semestre']);
		$data = $this->db->get('ei_alocacao a')->row();

		// recupera dados de faturamento consolidado
		$subquery = $this->db
			->select('d.id_alocacao, j.cargo, j.funcao, j.valor_hora_funcao')
			->select(["GREATEST(  SUM(IFNULL(TIME_TO_SEC(a.total_horas_mes{$idMes}), 0) - IFNULL(TIME_TO_SEC(i.total_horas_mes{$idMes}), 0))  , 0) AS total_segundos_mes{$idMes}"], false)
			->join('usuarios b', 'b.id = a.id_cuidador')
			->join('ei_alocados c', 'c.id = a.id_alocado')
			->join('ei_alocacao_escolas d', 'd.id = c.id_alocacao_escola')
			->join('ei_alocacao e', 'e.id = d.id_alocacao')
			->join('ei_ordem_servico e2', 'e2.nome = d.ordem_servico AND e2.ano = e.ano AND e2.semestre = e.semestre')
			->join('(SELECT j2.* FROM ei_alocados_horarios j2 GROUP BY j2.id_alocado, j2.periodo) j', 'j.id_alocado = c.id AND j.periodo = a.periodo', 'left', false)
			->join('ei_alocados_totalizacao i', 'i.id_alocado = a.id_alocado AND i.periodo = a.periodo AND i.substituicao_semestral IS NOT NULL AND a.substituicao_semestral IS NULL AND i.substituicao_eventual IS NULL', 'left')
			->join('ei_faturamento h', 'h.id_alocacao = e.id AND h.id_escola = d.id_escola AND h.cargo = j.cargo AND h.funcao = j.funcao', 'left')
			->where_in('e.id', explode(',', $data->id) + [0])
			->where('a.substituicao_eventual IS NULL')
			->group_by(['d.id_escola', 'a.id_cuidador', 'j.cargo', 'j.funcao', 'c.id', 'a.periodo'])
			->order_by('j.funcao', 'asc')
			->get_compiled_select('ei_alocados_totalizacao a');


		if ($recuperar) {
			$sql = "SELECT t.id, 
                       s.id_alocacao,
                       s.cargo, 
                       s.funcao, 
                       FORMAT(s.valor_hora_funcao, 2, 'de_DE') AS valor_hora,
                       NULL AS total_horas_mes,
                       SUM(s.total_segundos_mes{$idMes}) AS total_segundos_mes,
                       FORMAT(s.valor_hora_funcao * (SUM(s.total_segundos_mes{$idMes}) / 3600), 2, 'de_DE') AS valor_faturado,
                       s.valor_hora_funcao * (SUM(s.total_segundos_mes{$idMes}) / 3600) AS valor_total_individual
                FROM ({$subquery}) s
                LEFT JOIN ei_faturamento_consolidado t ON
                          t.id_alocacao = s.id_alocacao AND t.cargo = s.cargo AND t.funcao = s.funcao
                GROUP BY s.cargo, s.funcao";
		} else {
			$sql = "SELECT t.id, 
                       s.id_alocacao,
                       s.cargo, 
                       s.funcao, 
                       FORMAT(IFNULL(t.valor_hora_mes{$idMes}, s.valor_hora_funcao), 2, 'de_DE') AS valor_hora,
                       t.total_horas_mes{$idMes} AS total_horas_mes,
                       SUM(s.total_segundos_mes{$idMes}) AS total_segundos_mes,
                       FORMAT(IFNULL(t.valor_faturado_mes{$idMes}, s.valor_hora_funcao * (SUM(s.total_segundos_mes{$idMes}) / 3600)), 2, 'de_DE') AS valor_faturado,
                       IFNULL(t.valor_faturado_mes{$idMes}, s.valor_hora_funcao * (SUM(s.total_segundos_mes{$idMes}) / 3600)) AS valor_total_individual
                FROM ({$subquery}) s
                LEFT JOIN ei_faturamento_consolidado t ON
                          t.id_alocacao = s.id_alocacao AND t.cargo = s.cargo AND t.funcao = s.funcao
                GROUP BY s.cargo, s.funcao";
		}
		$alocados = $this->db->query($sql)->result();

		// carrega helpers de data e hora
		$this->load->helper('time');
		$this->load->library('Calendar');

		// formata os valores de input
		$totalHoras = null;
		$valorFaturado = null;
		foreach ($alocados as $alocado) {
			$valorFaturado += round($alocado->valor_total_individual, 2);
			$totalHoras += $alocado->total_horas_mes ? timeToSec($alocado->total_horas_mes) : $alocado->total_segundos_mes;
		}

		// retorna conjunto de dados para a view da planilha
		$planilha = [
			'empresa' => $empresa,
			'usuario' => $usuario,
			'mesAtual' => $this->calendar->get_month_name(date('m')),
			'query_string' => "depto={$depto}&diretoria={$idDiretoria}&supervisor={$idSupervisor}&mes={$mes}&ano={$ano}&semestre={$semestre}",
			'is_pdf' => $is_pdf,
			'id_medicao_mensal' => $data->id_medicao_mensal,
			'diretoria' => $data->diretoria,
			'contratos' => $data->contratos,
			'ordensServico' => $data->ordens_servico,
			'mesAno' => ucfirst($this->calendar->get_month_name($mes)) . '/' . $ano,
			'observacoes' => $data->observacoes,
			'alocados' => $alocados,
			'valor_hora' => $data->valor_hora,
			'total_horas' => secToTime($totalHoras, false),
			'valor_faturado' => number_format($valorFaturado, 2, ',', '.'),
			'totalEscolas' => $data->total_escolas ?? null,
			'totalAlunos' => $data->total_alunos ?? null,
			'totalProfissionais' => $data->total_profissionais ?? null
		];


		return $this->load->view('ei/planilha_faturamento_consolidado', $planilha, true);
	}


	//==========================================================================


	public function planilhaPagamentoPrestador($idHorario, $idMes, $ano, $is_pdf = false, $recuperar = false)
	{
		// prepara o cabecalho da planilha
		$empresa = $this->db
			->select('foto, foto_descricao')
			->where('id', $this->session->userdata('empresa'))
			->get('usuarios')
			->row();

		$usuario = $this->db->select('nome, email')
			->where('id', $this->session->userdata('id'))
			->get('usuarios')
			->row();

		// retorna dados da alocacao
		$alocacao = $this->db
			->select('d.id, d.semestre, b.id_cuidador, a.id_alocado, a.periodo')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocacao d', 'd.id = c.id_alocacao')
			->where('a.id', $idHorario)
			->get('ei_alocados_horarios a')
			->row();

		$idAlocado = $alocacao->id_alocado;

		// prepara variaveis locais
		$substituto = $this->input->get_post('substituto');
		$usoHorasFaturadas = $this->input->get_post('uso_horas_faturadas');
		$semestre = $alocacao->semestre ?? 1;

		$mes = str_pad($idMes + (intval($semestre) > 1 ? 6 : 0), 2, '0', STR_PAD_LEFT);

		// recupera dados do cuidador
		$cuidador = $this->db
			->select(["(CASE WHEN '{$mes}' > MONTH(a.data_substituicao1) AND a.data_substituicao1 IS NOT NULL THEN a.id_cuidador_sub1 ELSE b.id_cuidador END) AS id"], false)
			->select(["(CASE WHEN '{$mes}' > MONTH(a.data_substituicao1) AND a.data_substituicao1 IS NOT NULL THEN c.nome ELSE b.cuidador END) AS nome"], false)
			->select(["(CASE WHEN '{$mes}' = MONTH(a.data_substituicao1) AND a.data_substituicao1 IS NOT NULL THEN a.id_cuidador_sub1 END) AS id_sub"], false)
			->select(["(CASE WHEN '{$mes}' = MONTH(a.data_substituicao1) AND a.data_substituicao1 IS NOT NULL THEN c.nome END) AS nome_sub"], false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('usuarios c', 'c.id = a.id_cuidador_sub1', 'left')
			->where('b.id', $alocacao->id_alocado)
			->where('a.periodo', $alocacao->periodo)
			->get('ei_alocados_horarios a')
			->row();
		$idCuidador = $substituto ? $cuidador->id_sub : $cuidador->id;
		$nomeCuidador = $substituto ? $cuidador->nome_sub : $cuidador->nome;

		// recupera dados da apresentacao da planilha
		if ($substituto) {
			$this->db
				->select('g.nome AS cuidador, g.cnpj, g.centro_custo, g.nome_banco, g.agencia_bancaria, g.conta_bancaria')
				->select("e.valor_extra1_sub AS valor_extra1", false)
				->select("e.valor_extra2_sub AS valor_extra2", false)
				->select("e.justificativa1_sub AS justificativa1", false)
				->select("e.justificativa2_sub AS justificativa2", false)
				->select("IFNULL(e.tipo_pagamento_sub, 1) AS tipo_pagamento", false)
				->select("e.observacoes_sub AS observacoes", false);
		} else {
			$this->db
				->select('g.nome AS cuidador, g.cnpj, g.centro_custo, g.nome_banco, g.agencia_bancaria, g.conta_bancaria')
				->select("e.valor_extra1_mes{$idMes} AS valor_extra1", false)
				->select("e.valor_extra2_mes{$idMes} AS valor_extra2", false)
				->select("e.justificativa1_mes{$idMes} AS justificativa1", false)
				->select("e.justificativa2_mes{$idMes} AS justificativa2", false)
				->select("IFNULL(e.tipo_pagamento_mes{$idMes}, 1) AS tipo_pagamento", false)
				->select("e.observacoes_mes{$idMes} AS observacoes", false);
		}
		$pagamentoPrestador = $this->db
			->select('a3.nome AS solicitante, a.depto', false)
			->select("FORMAT(IFNULL(c21.valor_hora_operacional, c2.valor_hora_operacional), 2, 'de_DE') AS valor_hora_operacional", false)
			->select("FORMAT(c6.valor_pagamento, 2, 'de_DE') AS valor_pagamento", false)
			->select("FORMAT(c6.valor_pagamento2, 2, 'de_DE') AS valor_pagamento2", false)
//			->select(["GROUP_CONCAT(DISTINCT g.nome ORDER BY g.nome SEPARATOR ', ') AS cuidador_sub1"], false)
			->select(["GROUP_CONCAT(DISTINCT h.nome ORDER BY h.nome SEPARATOR ', ') AS cuidador_sub1"], false)
			->select(["GROUP_CONCAT(DISTINCT i.nome ORDER BY i.nome SEPARATOR ', ') AS cuidador_sub2"], false)
			->join('ei_diretorias a2', 'a2.id = a.id_diretoria')
			->join('usuarios a3', 'a3.id = a2.id_coordenador')
			->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id')
			->join('ei_alocados c', 'c.id_alocacao_escola = b.id')
			->join('ei_ordem_servico_profissionais c2', 'c2.id = c.id_os_profissional')
			->join('ei_ordem_servico_escolas c3', 'c3.id = c2.id_ordem_servico_escola')
			->join('ei_ordem_servico c4', 'c4.id = c3.id_ordem_servico')
			->join('ei_contratos c5', 'c5.id = c4.id_contrato')
			->join('ei_valores_faturamento c6', 'c6.id_contrato = c5.id AND c6.ano = c4.ano AND c6.semestre = c4.semestre AND c6.id_funcao = c2.id_funcao', 'left')
			->join('usuarios d', 'd.id = c.id_cuidador')
			->join('ei_ordem_servico_horarios c21', 'c21.id_os_profissional = c2.id', 'left')
			->join('ei_pagamento_prestador e', 'e.id_alocacao = a.id AND e.id_cuidador = c.id_cuidador', 'left')
			->join('ei_alocados_horarios f', 'f.id_alocado = c.id', 'left')
			->join('ei_alocados_totalizacao c0', "c0.id_alocado = c.id AND c0.periodo = f.periodo AND c0.id_cuidador = '{$idCuidador}'", 'left')
			->join('usuarios g', 'g.id = c0.id_cuidador', 'left')
			->join('usuarios h', 'h.id = f.id_cuidador_sub1', 'left')
			->join('usuarios i', 'i.id = f.id_cuidador_sub2', 'left')
			->where('a.id', $alocacao->id)
			->where('c.id_cuidador', $alocacao->id_cuidador)
			->group_by('c.id_cuidador')
			->get('ei_alocacao a')
			->row();

		// recupera dados dos substitutos de eventos
		$substitutoEvento = $this->input->get_post('alocado_bck');
		if ($substitutoEvento and $pagamentoPrestador) {
			$rowSubstitutoEvento = $this->db
				->select('nome, cnpj, centro_custo, agencia_bancaria, conta_bancaria, nome_banco')
				->where('id', $substitutoEvento)
				->get('usuarios')
				->row();

			// substitui dados de pagamento de acordo com a condicao de substituicao de evento
			$pagamentoPrestador->cuidador = $rowSubstitutoEvento->nome;
			$pagamentoPrestador->cnpj = $rowSubstitutoEvento->cnpj;
			$pagamentoPrestador->centro_custo = $rowSubstitutoEvento->centro_custo;
			$pagamentoPrestador->nome_banco = $rowSubstitutoEvento->nome_banco;
			$pagamentoPrestador->agencia_bancaria = $rowSubstitutoEvento->agencia_bancaria;
			$pagamentoPrestador->conta_bancaria = $rowSubstitutoEvento->conta_bancaria;
		}

		// prepara a lista de substitutos de eventos
		$rowSubstitutosEventos = $this->db
			->select('b.id, b.nome')
			->join('usuarios b', 'b.id = a.id_alocado_sub1 OR b.id = a.id_alocado_sub2')
			->where('a.id_alocado', $alocacao->id_alocado)
			->group_by(['b.id', 'b.nome'])
			->order_by('b.nome', 'asc')
			->get('ei_apontamento a')
			->result();

		$substitutosEventos = ['' => 'selecione...'] + array_column($rowSubstitutosEventos, 'nome', 'id');

		// prepara os dados de faturamento
		$rowValoresFaturamento = $this->db
			->select(["c.id_escola, a.periodo, IFNULL(g.valor_pagamento, g.valor_pagamento2) AS valor_pagamento"], false)
			->select(["TIME_FORMAT(SEC_TO_TIME(SUM(IFNULL(TIME_TO_SEC(a.desconto_sub1), 0) + IFNULL(TIME_TO_SEC(a.desconto_sub2), 0))), '%H:%i') AS total_horas_mes"], false)
			->select(["TIME_FORMAT(SEC_TO_TIME(IFNULL(g.valor_pagamento, g.valor_pagamento2) * SUM(IFNULL(TIME_TO_SEC(a.desconto_sub1), 0) + IFNULL(TIME_TO_SEC(a.desconto_sub2), 0))), '%H:%i') AS total"], false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_ordem_servico_escolas d', 'd.id = c.id_os_escola')
			->join('ei_ordem_servico e', 'e.id = d.id_ordem_servico')
			->join('ei_contratos f', 'f.id = e.id_contrato')
			->join('usuarios h', 'h.id = a.id_alocado_sub1 OR h.id = a.id_alocado_sub2', 'left')
			->join('empresa_funcoes i', 'i.id = h.id_funcao OR i.nome = h.funcao', 'left')
			->join('ei_valores_faturamento g', 'g.id_contrato = f.id AND g.ano = e.ano AND g.semestre = e.semestre AND g.id_funcao = i.id', 'left')
			->where('b.id', $alocacao->id_alocado)
			->where("(CHAR_LENGTH('{$substitutoEvento}') = 0 OR h.id = '{$substitutoEvento}')")
			->group_by(['c.id_escola', 'a.periodo'])
			->get('ei_apontamento a')
			->result();
		$totalHorasMesSub = [];
		$valorPagamentoSub = [];
		$totalSub = [];
		foreach ($rowValoresFaturamento as $valoresFaturamento) {
			$totalHorasMesSub[$valoresFaturamento->id_escola][$valoresFaturamento->periodo] = $valoresFaturamento->total_horas_mes;
			$valorPagamentoSub[$valoresFaturamento->id_escola][$valoresFaturamento->periodo] = $valoresFaturamento->valor_pagamento;
			$totalSub[$valoresFaturamento->id_escola][$valoresFaturamento->periodo] = $valoresFaturamento->total;
		}

		// recupera os dados das totalizacoes
		$this->db->select('d.id, c.id_escola, c.escola, a.periodo');
		$this->db->select("(CASE a.periodo WHEN 0 THEN 'Madrugada' WHEN 1 THEN 'Manhã' WHEN 2 THEN 'Tarde' WHEN 3 THEN 'Noite' END) AS nome_periodo", false);
		if ($substituto) {
			if ($recuperar) {
				$this->db->select(["TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(e.horas_mensais_custo) + IFNULL(TIME_TO_SEC(d.dias_descontados_mes{$idMes}), 0) + IFNULL(TIME_TO_SEC(d.horas_descontadas_mes{$idMes}), 0)), '%H:%i') AS total_horas_mes"], false);
				$this->db->select(["IF(e.valor_hora_operacional > 0, e.valor_hora_operacional, j.valor_pagamento) AS valor_hora_operacional"], false);
				$this->db->select(["IF(e.valor_hora_operacional > 0, e.valor_hora_operacional, j.valor_pagamento) * ((IFNULL(TIME_TO_SEC(e.horas_mensais_custo), 0) + IFNULL(TIME_TO_SEC(d.dias_descontados_mes{$idMes}), 0) + IFNULL(TIME_TO_SEC(d.horas_descontadas_mes{$idMes}), 0)) / 3600) AS valor_total"], false);
			} elseif ($usoHorasFaturadas) {
				$this->db
					->select(["TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(MONTH(a.data_substituicao1) = '{$mes}', a.total_sub1, 0))) + IFNULL(TIME_TO_SEC(d.dias_descontados_mes{$idMes}), 0) + IFNULL(TIME_TO_SEC(d.horas_descontadas_mes{$idMes}), 0)), '%H:%i') AS total_horas_mes"], false)
					->select(["IFNULL(d.valor_pagamento_mes{$idMes}, IF(e.valor_hora_operacional > 0, e.valor_hora_operacional, j.valor_pagamento)) AS valor_hora_operacional"], false)
					->select(["IFNULL(d.valor_pagamento_mes{$idMes}, IF(e.valor_hora_operacional > 0, e.valor_hora_operacional, j.valor_pagamento)) * IFNULL(SEC_TO_TIME(SUM(TIME_TO_SEC(IF(MONTH(a.data_substituicao1) = '{$mes}', a.total_sub1, 0))) + IFNULL(TIME_TO_SEC(d.dias_descontados_mes{$idMes}), 0) + IFNULL(TIME_TO_SEC(d.horas_descontadas_mes{$idMes}), 0)), 0) AS valor_total"], false);
			} else {
				$this->db
					->select(["TIME_FORMAT(IFNULL(d.total_horas_faturadas_mes{$idMes}, 0), '%H:%i') AS total_horas_mes"], false)
					->select(["IFNULL(d.valor_pagamento_mes{$idMes}, 0) AS valor_hora_operacional"], false)
					->select(["IFNULL(d.valor_total_mes{$idMes}, 0) AS valor_total"], false);
			}
		} else {
			if ($recuperar) {
				$this->db
					->select(["TIME_FORMAT(SEC_TO_TIME(TIME_TO_SEC(e.horas_mensais_custo) + IFNULL(TIME_TO_SEC(d.dias_descontados_mes{$idMes}), 0) + IFNULL(TIME_TO_SEC(d.horas_descontadas_mes{$idMes}), 0)), '%H:%i') AS total_horas_mes"], false)
					->select(["IF(e.valor_hora_operacional > 0, e.valor_hora_operacional, j.valor_pagamento) AS valor_hora_operacional"], false)
					->select(["IF(e.valor_hora_operacional > 0, e.valor_hora_operacional, j.valor_pagamento) * ((IFNULL(TIME_TO_SEC(e.horas_mensais_custo), 0) + IFNULL(TIME_TO_SEC(d.dias_descontados_mes{$idMes}), 0) + IFNULL(TIME_TO_SEC(d.horas_descontadas_mes{$idMes}), 0)) / 3600) AS valor_total"], false);
			} elseif ($usoHorasFaturadas) {
				$this->db
					->select(["TIME_FORMAT(SEC_TO_TIME(  SUM(TIME_TO_SEC(a.total_mes{$idMes})) + IFNULL(TIME_TO_SEC(d.dias_descontados_mes{$idMes}), 0) + IFNULL(TIME_TO_SEC(d.horas_descontadas_mes{$idMes}), 0)), '%H:%i') AS total_horas_mes"], false)
					->select(["IFNULL(d.valor_pagamento_mes{$idMes}, IF(e.valor_hora_operacional > 0, e.valor_hora_operacional, j.valor_pagamento)) AS valor_hora_operacional"], false)
					->select(["IFNULL(d.valor_pagamento_mes{$idMes}, IF(e.valor_hora_operacional > 0, e.valor_hora_operacional, j.valor_pagamento)) * IFNULL(SEC_TO_TIME(SUM(TIME_TO_SEC(a.total_mes{$idMes})) + IFNULL(TIME_TO_SEC(d.dias_descontados_mes{$idMes}), 0) + IFNULL(TIME_TO_SEC(d.horas_descontadas_mes{$idMes}), 0)), 0) AS valor_total"], false);
			} else {
				$this->db
					->select(["TIME_FORMAT(IFNULL(d.total_horas_faturadas_mes{$idMes}, 0), '%H:%i') AS total_horas_mes"], false)
					->select(["IFNULL(d.valor_pagamento_mes{$idMes}, 0) AS valor_hora_operacional"], false)
					->select(["IFNULL(d.valor_total_mes{$idMes}, 0) AS valor_total"], false);
			}
		}
		$this->db
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocados_totalizacao d', "d.id_alocado = b.id AND d.periodo = a.periodo AND d.id_cuidador = '{$idCuidador}'");
		$totalizacoes = $this->db
			->join('ei_ordem_servico_horarios e', 'e.id = a.id_os_horario')
			->join('ei_ordem_servico_profissionais f', 'f.id = e.id_os_profissional')
			->join('ei_ordem_servico_escolas g', 'g.id = f.id_ordem_servico_escola')
			->join('ei_ordem_servico h', 'h.id = g.id_ordem_servico')
			->join('ei_contratos i', 'i.id = h.id_contrato')
			->join('ei_valores_faturamento j', 'j.id_contrato = i.id AND j.ano = h.ano AND j.semestre = h.semestre AND j.id_funcao = e.id_funcao', 'left')
			->join('ei_apontamento k', "k.id_alocado = b.id AND k.periodo = a.periodo AND (k.id_alocado_sub1 = '{$substitutoEvento}' OR k.id_alocado_sub2 = '{$substitutoEvento}')", 'left')
			->where('b.id_cuidador', $alocacao->id_cuidador)
			->where('c.id_alocacao', $alocacao->id)
			->group_by(['b.id_cuidador', 'c.id_escola', 'a.periodo'])
			->get('ei_alocados_horarios a')
			->result();

		// monta os dados para a apresentacao da planilha
		$servicos = [];
		$soma = round($pagamentoPrestador->valor_extra1, 2, PHP_ROUND_HALF_DOWN);
		$soma += round($pagamentoPrestador->valor_extra2, 2, PHP_ROUND_HALF_DOWN);
		foreach ($totalizacoes as $totalizacao) {
			$servicos[] = [
				'id' => $totalizacao->id,
				'escola' => $totalizacao->escola,
				'periodo' => $totalizacao->nome_periodo,
				'qtdeHoras' => $substitutoEvento ? ($totalHorasMesSub[$totalizacao->id_escola][$totalizacao->periodo] ?? null) : $totalizacao->total_horas_mes,
				'valorCustoProfissional' => number_format(round($substitutoEvento ? ($valorPagamentoSub[$totalizacao->id_escola][$totalizacao->periodo] ?? null) : $totalizacao->valor_hora_operacional, 2, PHP_ROUND_HALF_DOWN), 2, ',', '.'),
				'total' => number_format(round($substitutoEvento ? ($totalSub[$totalizacao->id_escola][$totalizacao->periodo] ?? null) : $totalizacao->valor_total, 2, PHP_ROUND_HALF_DOWN), 2, ',', '.')
			];

			$soma += round($substitutoEvento ? ($totalSub[$totalizacao->id_escola][$totalizacao->periodo] ?? null) : $totalizacao->valor_total, 2, PHP_ROUND_HALF_DOWN);
		}

		// formata a data da apresentacao da planilha
		$this->load->library('Calendar');

		// retorna conjunto de dados para a view da planilha
		$planilha = [
			'empresa' => $empresa,
			'usuario' => $usuario,
			'mesAtual' => $this->calendar->get_month_name(date('m')),
			'query_string' => "horario={$idHorario}&mes={$mes}&ano={$ano}&semestre={$semestre}&substituto={$substituto}",
			'is_pdf' => $is_pdf,
			'solicitante' => $pagamentoPrestador->solicitante,
			'id_alocado' => $alocacao->id_alocado,
			'id_horario' => $idHorario,
			'id_mes' => $idMes,
			'prestador' => $pagamentoPrestador->cuidador,
			'prestador_sub1' => $pagamentoPrestador->cuidador_sub1,
			'prestador_sub2' => $pagamentoPrestador->cuidador_sub2,
			'tipo_pagamento' => $pagamentoPrestador->tipo_pagamento,
			'pagamento_inicio_semestre' => $pagamentoPrestador->valor_pagamento,
			'pagamento_ajustado' => $pagamentoPrestador->valor_pagamento2,
			'pagamento_ordem_servico' => $pagamentoPrestador->valor_hora_operacional,
			'substituto' => $substituto,
			'substitutos_eventos' => $substitutosEventos,
			'observacoes' => $pagamentoPrestador->observacoes,
			'cnpj' => $pagamentoPrestador->cnpj,
			'departamento' => $pagamentoPrestador->depto,
			'centroCusto' => $pagamentoPrestador->centro_custo,
			'agencia' => $pagamentoPrestador->agencia_bancaria,
			'conta' => $pagamentoPrestador->conta_bancaria,
			'banco' => $pagamentoPrestador->nome_banco,
			'mesAno' => ucfirst($this->calendar->get_month_name($mes)) . '/' . $ano,
			'justificativa1' => $pagamentoPrestador->justificativa1,
			'valorExtra1' => $pagamentoPrestador->valor_extra1,
			'justificativa2' => $pagamentoPrestador->justificativa2,
			'valorExtra2' => $pagamentoPrestador->valor_extra2,
			'valorTotal' => number_format(round($soma, 2, PHP_ROUND_HALF_DOWN), 2, ',', '.'),
			'servicos' => $servicos,
			'teste' => $totalizacoes
		];

		return $this->load->view('ei/planilha_pagamento_prestador', $planilha, true);
	}


	//==========================================================================


	public function planilhaPagamentoPrestadorSubstitutoEvento()
	{
		$idHorario = $this->input->post('id_horario');
		$idMes = $this->input->post('id_mes');

		$alocacao = $this->db
			->select('e.id, e.semestre, b.id_cuidador, a.id_alocado, a.periodo')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('usuarios c', 'c.id = b.id_cuidador')
			->join('ei_alocacao_escolas d', 'd.id = b.id_alocacao_escola')
			->join('ei_alocacao e', 'e.id = d.id_alocacao')
			->where('a.id', $idHorario)
			->group_by('b.id')
			->get('ei_alocados_horarios a')
			->row();


		$substituto = $this->input->post('substituto');
		$substitutoSemestre = $this->input->post('substituto_semestre');
		if ($substituto) {
			$idUsuario = $substituto;
		} else {
			$idUsuario = $alocacao->id_cuidador;
		}

		$rowTotalizacoes = $this->db
			->select('a.id, a.periodo, c.id_escola')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->where('a.id_alocado', $alocacao->id_alocado)
			->where('a.id_cuidador', $idUsuario)
			->group_by(['c.id_escola', 'a.periodo'])
			->get('ei_alocados_totalizacao a')
			->result();
		$buu1 = [];
		foreach ($rowTotalizacoes as $rowTotalizacao) {
			$buu1[$rowTotalizacao->id_escola][$rowTotalizacao->periodo] = $rowTotalizacao->id;
		}

		$data = $this->db
			->select('nome, cnpj, centro_custo, agencia_bancaria, conta_bancaria, nome_banco')
			->where('id', $idUsuario)
			->get('usuarios')
			->row();

		$buu = $this->db
			->select(["j.id AS id_totalizacao, c.id_escola, a.periodo, IFNULL(j.valor_pagamento_mes{$idMes} ,IFNULL(g.valor_pagamento, g.valor_pagamento2)) AS valor_pagamento"], false)
			->select(["TIME_FORMAT(IFNULL(j.total_horas_faturadas_mes{$idMes} ,SEC_TO_TIME(SUM(IFNULL(TIME_TO_SEC(a.desconto_sub1), 0) + IFNULL(TIME_TO_SEC(a.desconto_sub2), 0)))), '%H:%i') AS total_horas_mes"], false)
			->select(["IFNULL(j.valor_total_mes{$idMes}, IFNULL(g.valor_pagamento, g.valor_pagamento2) * SUM(IFNULL(TIME_TO_SEC(a.desconto_sub1), 0) + IFNULL(TIME_TO_SEC(a.desconto_sub2), 0)) / 3600) AS total"], false)
//			->select(["c.id_escola, a.periodo, IFNULL(g.valor_pagamento, g.valor_pagamento2) AS valor_pagamento"], false)
//			->select(["TIME_FORMAT(SEC_TO_TIME(SUM(IFNULL(TIME_TO_SEC(a.desconto_sub1), 0) + IFNULL(TIME_TO_SEC(a.desconto_sub2), 0))), '%H:%i') AS total_horas_mes"], false)
//			->select(["TIME_FORMAT(SEC_TO_TIME(IFNULL(g.valor_pagamento, g.valor_pagamento2) * SUM(IFNULL(TIME_TO_SEC(a.desconto_sub1), 0) + IFNULL(TIME_TO_SEC(a.desconto_sub2), 0))), '%H:%i') AS total"], false)
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_ordem_servico_escolas d', 'd.id = c.id_os_escola')
			->join('ei_ordem_servico e', 'e.id = d.id_ordem_servico')
			->join('ei_contratos f', 'f.id = e.id_contrato')
			->join('usuarios h', 'h.id = a.id_alocado_sub1 OR h.id = a.id_alocado_sub2', 'left')
			->join('empresa_funcoes i', 'i.id = h.id_funcao OR i.nome = h.funcao', 'left')
			->join('ei_valores_faturamento g', 'g.id_contrato = f.id AND g.ano = e.ano AND g.semestre = e.semestre AND g.id_funcao = i.id', 'left')
			->join('ei_alocados_totalizacao j', "j.id_alocado = b.id AND j.periodo = a.periodo AND j.id_cuidador = '{$idUsuario}'", 'left')
			->where('b.id', $alocacao->id_alocado)
			->where("(CHAR_LENGTH('{$substituto}') = 0 OR h.id = '{$idUsuario}')")
			->group_by(['c.id_escola', 'a.periodo', 'h.id'])
			->get('ei_apontamento a')->result();
		$buu2 = [];
		$buu3 = [];
		$buu4 = [];
		foreach ($buu as $bu) {
			$buu2[$bu->id_escola][$bu->periodo] = $bu->total_horas_mes;
			$buu3[$bu->id_escola][$bu->periodo] = $bu->valor_pagamento;
			$buu4[$bu->id_escola][$bu->periodo] = $bu->total;
		}


		if ($substitutoSemestre) {
			$this->db
				->select("e.valor_extra1_mes{$idMes} AS valor_extra1", false)
				->select("e.valor_extra2_mes{$idMes} AS valor_extra2", false);
		} else {
			$this->db
				->select("e.valor_extra1_sub AS valor_extra1", false)
				->select("e.valor_extra2_sub AS valor_extra2", false);
		}
		$pagamentoPrestador = $this->db
			->join('ei_diretorias a2', 'a2.id = a.id_diretoria')
			->join('usuarios a3', 'a3.id = a2.id_coordenador')
			->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id')
			->join('ei_alocados c', 'c.id_alocacao_escola = b.id')
			->join('ei_ordem_servico_profissionais c2', 'c2.id = c.id_os_profissional')
			->join('ei_ordem_servico_escolas c3', 'c3.id = c2.id_ordem_servico_escola')
			->join('ei_ordem_servico c4', 'c4.id = c3.id_ordem_servico')
			->join('ei_contratos c5', 'c5.id = c4.id_contrato')
			->join('ei_valores_faturamento c6', 'c6.id_contrato = c5.id AND c6.ano = c4.ano AND c6.semestre = c4.semestre AND c6.id_funcao = c2.id_funcao', 'left')
			->join('usuarios d', 'd.id = c.id_cuidador')
			->join('ei_ordem_servico_horarios c21', 'c21.id_os_profissional = c2.id', 'left')
			->join('ei_pagamento_prestador e', 'e.id_alocacao = a.id AND e.id_cuidador = c.id_cuidador', 'left')
			->join('ei_alocados_horarios f', 'f.id_alocado = c.id', 'left')
			->join('usuarios g', 'g.id = f.id_cuidador_sub1', 'left')
			->join('usuarios h', 'h.id = f.id_cuidador_sub2', 'left')
			->where('a.id', $alocacao->id)
			->where('c.id_cuidador', $alocacao->id_cuidador)
			->group_by('c.id_cuidador')
			->get('ei_alocacao a')
			->row();


		/*if ($substitutoSemestre) {
			$this->db
				->select(["TIME_FORMAT(IFNULL(d.total_horas_faturadas_mes{$idMes}, 0), '%H:%i') AS total_horas_mes"], false)
				->select(['IFNULL(d.valor_pagamento_sub1, 0) AS valor_hora_operacional'], false)
				->select(['IFNULL(d.valor_total_sub1, 0) AS valor_total'], false);
		} else {
			$this->db
				->select(["TIME_FORMAT(IFNULL(d.total_horas_faturadas_mes{$idMes}, 0), '%H:%i') AS total_horas_mes"], false)
				->select(["IFNULL(d.valor_pagamento_mes{$idMes}, 0) AS valor_hora_operacional"], false)
				->select(["IFNULL(d.valor_total_mes{$idMes}, 0) AS valor_total"], false);
		}*/
		$totalizacoes = $this->db
			->select(["TIME_FORMAT(IFNULL(d.total_horas_faturadas_mes{$idMes}, 0), '%H:%i') AS total_horas_mes"], false)
			->select(["IFNULL(d.valor_pagamento_mes{$idMes}, 0) AS valor_hora_operacional"], false)
			->select(["IFNULL(d.valor_total_mes{$idMes}, 0) AS valor_total"], false)
			->select('c.id_escola, a.periodo')
			->join('ei_alocados b', 'b.id = a.id_alocado')
			->join('ei_alocacao_escolas c', 'c.id = b.id_alocacao_escola')
			->join('ei_alocados_totalizacao d', 'd.id_alocado = b.id AND d.periodo = a.periodo')
			->join('ei_ordem_servico_horarios e', 'e.id = a.id_os_horario')
			->join('ei_ordem_servico_profissionais f', 'f.id = e.id_os_profissional')
			->join('ei_ordem_servico_escolas g', 'g.id = f.id_ordem_servico_escola')
			->join('ei_ordem_servico h', 'h.id = g.id_ordem_servico')
			->join('ei_contratos i', 'i.id = h.id_contrato')
			->join('ei_valores_faturamento j', 'j.id_contrato = i.id AND j.ano = h.ano AND j.semestre = h.semestre AND j.id_funcao = e.id_funcao', 'left')
			->where('d.id_cuidador', $alocacao->id_cuidador)
			->where('c.id_alocacao', $alocacao->id)
			->group_by(['c.id_escola', 'a.periodo'])
			->get('ei_alocados_horarios a')
			->result();

		$this->load->helper('time');

		$servicos = [];
		$soma = round($pagamentoPrestador->valor_extra1, 2, PHP_ROUND_HALF_DOWN);
		$soma += round($pagamentoPrestador->valor_extra2, 2, PHP_ROUND_HALF_DOWN);
		foreach ($totalizacoes as $totalizacao) {
			$servicos[] = [
				'id_totalizacao' => $buu1[$totalizacao->id_escola][$totalizacao->periodo] ?? null,
				'qtdeHoras' => $substituto ? ($buu2[$totalizacao->id_escola][$totalizacao->periodo] ?? null) : $totalizacao->total_horas_mes,
				'valorCustoProfissional' => number_format(round($substituto ? ($buu3[$totalizacao->id_escola][$totalizacao->periodo] ?? null) : $totalizacao->valor_hora_operacional, 2, PHP_ROUND_HALF_DOWN), 2, ',', '.'),
				'total' => number_format(round($substituto ? ($buu4[$totalizacao->id_escola][$totalizacao->periodo] ?? null) : $totalizacao->valor_total, 2, PHP_ROUND_HALF_DOWN), 2, ',', '.')
			];

			$soma += round($totalizacao->valor_total, 2, PHP_ROUND_HALF_DOWN);
		}

		$data->servicos = $servicos;
		$data->soma = $soma;

		echo json_encode($data);
	}


	//==========================================================================


	public function pdfTotalizacao()
	{
		$this->load->library('m_pdf');

		$stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
		$stylesheet .= '#table { border-bottom: 1px solid #ddd; } ';
		$stylesheet .= '#periodo { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#periodo thead th { padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#periodo tbody td { font-size: 12px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
		$stylesheet .= 'p strong { font-weight: bold; }';


		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$idAlocado = $this->input->get('id_alocado');
		$mes = $this->input->get('mes');
		$periodo = $this->input->get('periodo');
		$this->m_pdf->pdf->writeHTML($this->planilhaFaturamento($idAlocado, $mes, $periodo, true));


		$this->db->select('b.escola, c.ano');
		$this->db->join('ei_alocacao_escolas b', 'b.id = a.id_alocacao_escola');
		$this->db->join('ei_alocacao c', 'c.id = b.id_alocacao');
		$this->db->where('a.id', $idAlocado);
		$alocado = $this->db->get('ei_alocados a')->row();


		$this->load->library('Calendar');
		$nomeMes = ucfirst($this->calendar->get_month_name(str_pad($mes, 2, '0', STR_PAD_LEFT)));


		$this->m_pdf->pdf->Output("FAT {$alocado->escola} - {$nomeMes}_{$alocado->ano}.pdf", 'D');
	}


	//==========================================================================


	public function pdfTotalizacaoConsolidada()
	{
		$this->load->library('m_pdf');

		$stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
		$stylesheet .= '#table { border-bottom: 1px solid #ddd; } ';
		$stylesheet .= '#periodo { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#periodo thead th { padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#periodo tbody td { font-size: 12px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
		$stylesheet .= 'p strong { font-weight: bold; }';


		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$idDiretoria = $this->input->get('diretoria');
		$mes = $this->input->get('mes');
		$idMes = (int)$mes - ($this->input->get('semestre') > 1 ? 6 : 0);
		$ano = $this->input->get('ano');
		$this->m_pdf->pdf->writeHTML($this->planilhaFaturamentoConsolidado($idDiretoria, $mes, $ano, true));


		$this->load->library('Calendar');
		$mes = $this->calendar->get_month_name($mes);


		$this->m_pdf->pdf->Output("PF-Educação Inclusiva - {$mes}/{$ano}.pdf", 'D');
	}


	//==========================================================================


	public function pdfPagamentoPrestador()
	{
		$this->load->library('m_pdf');


		$stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
		$stylesheet .= '#table { border-bottom: 1px solid #ddd; } ';
		$stylesheet .= '#periodo { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#periodo thead th { font-size: 11px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#periodo tbody td { font-size: 11px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
		$stylesheet .= 'p strong { font-weight: bold; }';


		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$idHorario = $this->input->get('horario');
		$substituto = $this->input->get('substituto');
		$alocadoBck = $this->input->get('alocado_bck');


		$mes = $this->input->get('mes');
		$idMes = (int)$mes - ($this->input->get('semestre') > 1 ? 6 : 0);
		$ano = $this->input->get('ano');
		$this->m_pdf->pdf->writeHTML($this->planilhaPagamentoPrestador($idHorario, $idMes, $ano, true));


		if ($alocadoBck) {
			$nomeProfissional = $this->db->select('nome')->where('id', $alocadoBck)->get('usuarios')->row()->nome ?? '';
		} else {
			if ($substituto) {
				$this->db->select('c.nome AS cuidador');
			} else {
				$this->db->select('b.cuidador');
			}
			$this->db->join('ei_alocados b', 'b.id = a.id_alocado');
			if ($substituto === '2') {
				$this->db->join('usuarios c', 'c.id = a.id_cuidador_sub2');
			} elseif ($substituto === '1') {
				$this->db->join('usuarios c', 'c.id = a.id_cuidador_sub1');
			}
			$this->db->where('a.id', $idHorario);
			$nomeProfissional = $this->db->get('ei_alocados_horarios a')->row()->cuidador ?? '';
		}


		$this->load->library('Calendar');
		$mes = $this->calendar->get_month_name($mes);


		$this->m_pdf->pdf->Output("PP-{$nomeProfissional} - {$mes}/{$ano}.pdf", 'D');
	}


}
