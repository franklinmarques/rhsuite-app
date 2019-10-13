<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class JobDescriptorRespondente extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	//==========================================================================
	public function index()
	{
		$data['empresa'] = $this->session->userdata('empresa');

		$this->db->select('id, nome');
		$this->db->where('empresa', $data['empresa']);
		$this->db->where('tipo', 'funcionario');
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();
		$data['respondentes'] = array();
		foreach ($usuarios as $usuario) {
			$data['respondentes'][$usuario->id] = $usuario->nome;
		}
		$this->load->view('jobDescriptorRespondente', $data);
	}

	//==========================================================================
	public function ajax_list()
	{
		$post = $this->input->post();

		$sql = "SELECT s.id, 
                       s.cargo,
                       s.funcao,
                       s.versao,
                       s.data,
                       s.id_cargo,
                       s.id_funcao
                FROM (SELECT c.id, 
                             a.nome AS cargo,
                             b.nome AS funcao,
                             c.versao,
                             c.data,
                             a.id AS id_cargo,
                             b.id AS id_funcao
                      FROM empresa_cargos a
                      INNER JOIN empresa_funcoes b
                                 ON b.id_cargo = a.id
                      LEFT JOIN job_descriptor c
                                ON c.id_cargo = a.id
                                AND c.id_funcao = b.id
                      LEFT JOIN job_descriptor_respondentes d 
                                ON d.id_descritor = c.id
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}
                            AND d.id_usuario = {$this->session->userdata('id')}
                      ORDER BY a.nome ASC, b.nome ASC) s";

		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array('s.id', 's.cargo', 's.funcao', 's.versao', 's.data');
		if ($post['search']['value']) {
			foreach ($columns as $key => $column) {
				if ($key > 1) {
					$sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
				} elseif ($key == 1) {
					$sql .= " 
                        WHERE {$column} LIKE '%{$post['search']['value']}%'";
				}
			}
		}
		$recordsFiltered = $this->db->query($sql)->num_rows();

		if (isset($post['order'])) {
			$orderBy = array();
			foreach ($post['order'] as $order) {
				$orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
			}
			$sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
		}
		if ($post['length'] > 0) {
			$sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
		}
		$list = $this->db->query($sql)->result();

		$data = array();
		foreach ($list as $apontamento) {
			$row = array();
			$row[] = $apontamento->cargo;
			$row[] = $apontamento->funcao;
			$row[] = $apontamento->versao;
			$row[] = '
                      <button class="btn btn-sm btn-info" onclick="edit_descritivo(' . $apontamento->id . ')" title="Preencher descritivos"><i class="glyphicon glyphicon-pencil"></i> Preencher descritivos</button>
                      <a class="btn btn-sm btn-primary" href="' . site_url('jobDescriptorRespondente/relatorio/' . $apontamento->id) . '" title="Visualizar"><i class="glyphicon glyphicon-list-alt"></i> Visualizar</a>
                     ';

			$data[] = $row;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	//==========================================================================
	public function ajax_edit()
	{
		$idDescritor = $this->input->post('id_descritor');
		$idRespondente = $this->session->userdata('id');

		$this->db->select('c.nome AS nome_cargo, d.nome AS funcao, b.versao');
		$this->db->select("IFNULL(b.campo_livre1, 'Campo livre n&ordm; 1') AS id_campo_livre1", false);
		$this->db->select("IFNULL(b.campo_livre2, 'Campo livre n&ordm; 2') AS id_campo_livre2", false);
		$this->db->select("IFNULL(b.campo_livre3, 'Campo livre n&ordm; 3') AS id_campo_livre3", false);
		$this->db->select("IFNULL(b.campo_livre4, 'Campo livre n&ordm; 4') AS id_campo_livre4", false);
		$this->db->select("IFNULL(b.campo_livre5, 'Campo livre n&ordm; 5') AS id_campo_livre5", false);
		$this->db->select('NULL AS estruturas, NULL AS descritivos', false);
		$this->db->join('job_descriptor b', 'b.id = a.id_descritor');
		$this->db->join('empresa_cargos c', 'c.id = b.id_cargo');
		$this->db->join('empresa_funcoes d', 'd.id = b.id_funcao');
		$this->db->where('a.id_descritor', $idDescritor);
		$this->db->where('a.id_usuario', $idRespondente);
		$data = $this->db->get('job_descriptor_respondentes a')->row_array();

		$this->db->where('id_descritor', $idDescritor);
		$this->db->where('id_usuario', $idRespondente);
		$descritivos = $this->db->get('job_descriptor_respondentes')->row_array();

		$estruturas = $this->db->get_where('job_descriptor', array('id' => $idDescritor))->row_array();

		unset($descritivos['id_descritor'], $descritivos['id_usuario']);
		$data['descritivos'] = $descritivos;
		$data['estruturas'] = array_intersect_key($estruturas, $descritivos);

		foreach ($data['estruturas'] as $estrutura => $valor) {
			if (preg_match('/campo_livre/', $estrutura)) {
				$data['estruturas'][$estrutura] = strlen($valor) > 0 ? '1' : '0';
			}
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_save()
	{
		$data = $this->input->post();
		$id = $data['id'];
		unset($data['id']);

		foreach ($data as $field => $value) {
			if (strlen($value) == 0) {
				$data[$field] = null;
			}
		}

		$data['id_usuario'] = $this->session->userdata('id');
		$status = $this->db->update('job_descriptor_respondentes', $data, array('id' => $id));

		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function relatorio()
	{
		$this->ajax_relatorio();
	}

	//==========================================================================
	public function ajax_relatorio($pdf = false)
	{
		$id = $this->uri->rsegment(3, 0);
		if ($pdf !== true) {
			$pdf = false;
		}

		$get = $this->input->get();

		$data = array('is_pdf' => $pdf);

		$this->db->select('foto, foto_descricao');
		$this->db->where('id', $this->session->userdata('empresa'));
		$data['empresa'] = $this->db->get('usuarios')->row();

		$this->db->select('a.*, c.nome AS cargo, d.nome AS funcao', false);
		$this->db->select(["IFNULL(CONCAT(c.familia_CBO, '-', d.ocupacao_CBO), '--') AS cbo"], false);
		$this->db->join('empresa_cargos c', 'c.id = a.id_cargo');
		$this->db->join('empresa_funcoes d', 'd.id = a.id_funcao');
		$this->db->where('a.id', $id);
		$data['jobDescriptor'] = $this->db->get('job_descriptor a')->row();

		$this->db->select('a.*, b.nome');
		$this->db->join('usuarios b', 'b.id = a.id_usuario');
		$this->db->where('a.id_descritor', $id);
		$this->db->where('a.id_usuario', $this->session->userdata('id'));
		$this->db->order_by('b.nome', 'asc');
		$respondente = $this->db->get('job_descriptor_respondentes a')->row();

		$data['usuarios'] = $respondente->nome;

		$data['estruturas'] = array(
			'sumario' => 'Descrição sumária',
			'formacao_experiencia' => 'Formação e experiência',
			'condicoes_gerais_exercicio' => 'Condições gerais de exercício',
			'codigo_internacional_CIUO88' => 'Código Internacional CIUO88',
			'notas' => 'Notas',
			'recursos_trabalho' => 'Recursos de trabalho',
			'atividades' => 'Atribuições e atividades',
			'responsabilidades' => 'Responsabilidades',
			'conhecimentos_habilidades' => 'Conhecimentos e habilidades',
			'habilidades_basicas' => 'Conhecimentos e habilidades - Básicas',
			'habilidades_intermediarias' => 'Conhecimentos e habilidades - Intermediárias',
			'habilidades_avancadas' => 'Conhecimentos e habilidades - Avançadas',
			'ambiente_trabalho' => 'Especificações gerais - Ambiente de trabalho',
			'condicoes_trabalho' => 'Especificações gerais - Condições de trabalho',
			'esforcos_fisicos' => 'Especificações gerais - Esforços físicos',
			'grau_autonomia' => 'Especificações gerais - Grau de autonomia',
			'grau_complexidade' => 'Especificações gerais - Grau de complexidade',
			'grau_iniciativa' => 'Especificações gerais - Grau de iniciativa',
			'competencias_tecnicas' => 'Competências Técnicas',
			'competencias_comportamentais' => 'Competências Comportamentais',
			'tempo_experiencia' => 'Tempo de experiência no cargo/função',
			'formacao_minima' => 'Formação/escolaridade mínima',
			'formacao_plena' => 'Formação/escolaridade para exercício pleno',
			'esforcos_mentais' => 'Esforços mentais',
			'grau_pressao' => 'Grau de pressão/estresse',
			'campo_livre1' => $data['jobDescriptor']->campo_livre1,
			'campo_livre2' => $data['jobDescriptor']->campo_livre2,
			'campo_livre3' => $data['jobDescriptor']->campo_livre3,
			'campo_livre4' => $data['jobDescriptor']->campo_livre4,
			'campo_livre5' => $data['jobDescriptor']->campo_livre5
		);

		$estruturas = array_intersect_key(array_filter((array)$data['jobDescriptor'], function ($v, $k) {
			$matches = preg_match('/campo_livre/', $k);
			return ($matches and strlen($v) > 0 or !$matches and $v === '1');
		}, ARRAY_FILTER_USE_BOTH), $data['estruturas']);

		$data['estruturas'] = array_intersect_key($data['estruturas'], $estruturas);
		$data['consolidado'] = array();

		$consolidados = $this->db->get_where('job_descriptor_consolidados', ['id_descritor' => $id])->row_array();
		$data['id_consolidado'] = $consolidados['id_descritor'] ?? null;

		foreach (array_keys($estruturas) as $estrutura) {
			$data['consolidado'][$estrutura] = $consolidados[$estrutura] ?? null;
			$data['respondentes'][$estrutura][$respondente->id] = $respondente->$estrutura;
		}

		if ($pdf) {
			return $this->load->view('jobDescriptor_relatorio', $data, true);
		} else {
			$this->load->view('jobDescriptor_relatorio', $data);
		}
	}

	//==========================================================================
	public function ajaxAddConsolidado()
	{
		$campos = array_diff($this->db->list_fields('job_descriptor_respondentes'), ['id', 'id_usuario']);

		foreach ($campos as $campo) {
			$this->db->select("GROUP_CONCAT({$campo} ORDER BY id SEPARATOR '\n') AS {$campo}", false);
		}
		$this->db->where('id_descritor', $this->input->post('id_descritor'));
		$data = $this->db->get('job_descriptor_respondentes')->row();
		if (empty($data)) {
			exit(json_encode(['erro' => 'Não foi possível criar consolidado']));
		}

		$status = $this->db->insert('job_descriptor_consolidados', $data);

		echo json_encode(['status' => $status !== false]);
	}

	//==========================================================================
	public function ajaxEditConsolidado()
	{
		$this->db->where('id_descritor', $this->input->post('id_descritor'));
		$data = $this->db->get('job_descriptor_consolidados')->row();

		echo json_encode($data);
	}

	//==========================================================================
	public function ajaxUpdateConsolidado()
	{
		$data = $this->input->post();
		$idDescritor = $this->input->post('id_descritor');
		unset($data['id_descritor']);
		$status = $this->db->update('job_descriptor_consolidados', $data, ['id_descritor' => $idDescritor]);

		echo json_encode(['status' => $status !== false]);
	}

	//==========================================================================
	public function ajaxDeleteConsolidado()
	{
		$idDescritor = $this->input->post('id_descritor');
		$status = $this->db->delete('job_descriptor_consolidados', ['id_descritor' => $idDescritor]);

		echo json_encode(['status' => $status !== false]);
	}

}
