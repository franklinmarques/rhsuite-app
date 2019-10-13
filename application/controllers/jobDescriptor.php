<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class JobDescriptor extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	//==========================================================================
	public function index()
	{
		$data['empresa'] = $this->session->userdata('empresa');

		$this->db->select('DISTINCT(nome) AS nome', false);
		$this->db->where('id_empresa', $data['empresa']);
		$this->db->order_by('nome', 'asc');
		$deptos = $this->db->get('empresa_departamentos')->result();
		$data['depto'] = array('' => 'Todos');
		foreach ($deptos as $depto) {
			$data['depto'][$depto->nome] = $depto->nome;
		}

		$this->db->select('DISTINCT(a.nome) AS nome', false);
		$this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
		$this->db->where('b.id_empresa', $data['empresa']);
		$this->db->order_by('nome', 'asc');
		$areas = $this->db->get('empresa_areas a')->result();
		$data['area'] = array('' => 'Todas');
		foreach ($areas as $area) {
			$data['area'][$area->nome] = $area->nome;
		}

		$this->db->select('DISTINCT(a.nome) AS nome', false);
		$this->db->join('empresa_areas b', 'b.id = a.id_area');
		$this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
		$this->db->where('c.id_empresa', $data['empresa']);
		$this->db->order_by('nome', 'asc');
		$setores = $this->db->get('empresa_setores a')->result();
		$data['setor'] = array('' => 'Todos');
		foreach ($setores as $setor) {
			$data['setor'][$setor->nome] = $setor->nome;
		}

		$this->db->select('DISTINCT(nome) AS nome', false);
		$this->db->where('id_empresa', $data['empresa']);
		$this->db->order_by('nome', 'asc');
		$cargos = $this->db->get('empresa_cargos')->result();
		$data['cargo'] = array('' => 'Todos');
		foreach ($cargos as $cargo) {
			$data['cargo'][$cargo->nome] = $cargo->nome;
		}

		$this->db->select('DISTINCT(a.nome) AS nome', false);
		$this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
		$this->db->where('b.id_empresa', $data['empresa']);
		$this->db->order_by('nome', 'asc');
		$funcoes = $this->db->get('empresa_funcoes a')->result();
		$data['funcao'] = array('' => 'Todas');
		foreach ($funcoes as $funcao) {
			$data['funcao'][$funcao->nome] = $funcao->nome;
		}

		$this->db->select('id, versao');
		$this->db->where('id_empresa', $data['empresa']);
		$this->db->order_by('data', 'asc');
		$jobDescriptors = $this->db->get('job_descriptor')->result();
		$data['versao'] = array('' => 'Todas');
		foreach ($jobDescriptors as $jobDescriptor) {
			$data['versao'][$jobDescriptor->id] = $jobDescriptor->versao;
		}

		$this->db->select('id, nome');
		$this->db->where('empresa', $data['empresa']);
		$this->db->where('tipo', 'funcionario');
		$this->db->where('status', 1);
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();
		$data['respondentes'] = array();
		foreach ($usuarios as $usuario) {
			$data['respondentes'][$usuario->id] = $usuario->nome;
		}
		$this->load->view('jobDescriptor', $data);
	}

	//==========================================================================
	public function atualizar_filtro()
	{
		$data['empresa'] = $this->session->userdata('empresa');
		$post = $this->input->post();


		$this->db->select('DISTINCT(a.nome) AS nome');
		$this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
		$this->db->where('b.id_empresa', $data['empresa']);
		if (!empty($post['depto'])) {
			$this->db->where('b.nome', $post['depto']);
		}
		$this->db->order_by('a.nome', 'asc');
		$areas = $this->db->get('empresa_areas a')->result();
		$option['area'] = array('' => 'Todas');
		foreach ($areas as $area) {
			$option['area'][$area->nome] = $area->nome;
		}

		$this->db->select('DISTINCT(a.nome) AS nome');
		$this->db->join('empresa_areas b', 'b.id = a.id_area');
		$this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
		$this->db->where('c.id_empresa', $data['empresa']);
		if (!empty($post['depto'])) {
			$this->db->where('c.nome', $post['depto']);
		}
		if (!empty($post['area'])) {
			$this->db->where('b.nome', $post['area']);
		}
		$this->db->order_by('a.nome', 'asc');
		$setores = $this->db->get('empresa_setores a')->result();
		$option['setor'] = array('' => 'Todos');
		foreach ($setores as $setor) {
			$option['setor'][$setor->nome] = $setor->nome;
		}

		$this->db->select('DISTINCT(a.nome) AS nome');
		$this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
		$this->db->where('b.id_empresa', $data['empresa']);
		if ($post['cargo']) {
			$this->db->where('b.nome', $post['cargo']);
		}
		$this->db->order_by('a.nome', 'asc');
		$funcoes = $this->db->get('empresa_funcoes a')->result();
		$option['funcao'] = array('' => 'Todas');
		foreach ($funcoes as $funcao) {
			$option['funcao'][$funcao->nome] = $funcao->nome;
		}

		$this->db->select('a.id, a.versao');
		$this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
		$this->db->join('empresa_funcoes c', 'c.id = a.id_funcao');
		$this->db->where('a.id_empresa', $data['empresa']);
		if ($post['cargo']) {
			$this->db->where('b.nome', $post['cargo']);
		}
		if ($post['funcao']) {
			$this->db->where('c.nome', $post['funcao']);
		}
		$this->db->order_by('a.data', 'asc');
		$jobDescriptors = $this->db->get('job_descriptor a')->result();
		$option['versao'] = array('' => 'Todas');
		foreach ($jobDescriptors as $jobDescriptor) {
			$option['versao'][$jobDescriptor->id] = $jobDescriptor->versao;
		}

//        $data['area'] = form_dropdown('area', $option['area'], $post['area'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
//        $data['setor'] = form_dropdown('setor', $option['setor'], $post['setor'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['funcao'] = form_dropdown('funcao', $option['funcao'], $post['funcao'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['versao'] = form_dropdown('versao', $option['versao'], $post['versao'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_list()
	{
		$post = $this->input->post();
		parse_str($this->input->post('busca'), $busca);


		$this->db->select('c.id, a.nome AS cargo, b.nome AS funcao, c.versao', false);
		$this->db->select('c.data, a.id AS id_cargo, b.id AS id_funcao', false);
		$this->db->select(["CONCAT(a.familia_CBO, '-', b.ocupacao_CBO) AS cbo"], false);
		$this->db->join('empresa_funcoes b', 'b.id_cargo = a.id');
		$this->db->join('job_descriptor c', 'c.id_cargo = a.id AND c.id_funcao = b.id', 'left');
		$this->db->where('a.id_empresa', $this->session->userdata('empresa'));
		if (!empty($busca['cargo'])) {
			$this->db->where('a.nome', $busca['cargo']);
		}
		if (!empty($busca['funcao'])) {
			$this->db->where('b.nome', $busca['funcao']);
		}
		if (!empty($busca['versao'])) {
			$this->db->where('c.id', $busca['versao']);
		}
		$this->db->order_by('a.nome', 'asc');
		$this->db->order_by('b.nome', 'asc');
		$recordsTotal = $this->db->get('empresa_cargos a')->num_rows();

		$sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

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
			$row[] = $apontamento->cbo;
			$row[] = '
                      <button class="btn btn-sm btn-info" onclick="add_versao(' . $apontamento->id_cargo . ',' . $apontamento->id_funcao . ')" title="Adicionar versão de cargo/função"><i class="glyphicon glyphicon-plus"></i> Versão</button>
                     ';
			$row[] = $apontamento->versao;
			if ($apontamento->id) {
				$row[] = '
                          <button class="btn btn-sm btn-info" onclick="edit_versao(' . $apontamento->id . ')" title="Editar versão de cargo/função"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger" onclick="delete_versao(' . $apontamento->id . ')" title="Excluir versão de cargo/função"><i class="glyphicon glyphicon-trash"></i></button>
                          <!--<button class="btn btn-sm btn-info" onclick="edit_estrutura()" title="Estrutura">Estrutura</button>
                          <button class="btn btn-sm btn-info" onclick="edit_evento()" title="Descritivo">Descritivo</button>-->
                          <button class="btn btn-sm btn-info" onclick="edit_respondentes(' . $apontamento->id . ')" title="Respondentes">Respondentes</button>
                          <a class="btn btn-sm btn-primary" href="' . site_url('jobDescriptor/relatorio/' . $apontamento->id) . '" title="Visualizar"><i class="glyphicon glyphicon-list-alt"></i> Visualizar</a>
                         ';
			} else {
				$row[] = '
                          <button class="btn btn-sm btn-info disabled" title="Editar versão de cargo/função"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger disabled" title="Excluir versão de cargo/função"><i class="glyphicon glyphicon-trash"></i></button>
                          <!--<button class="btn btn-sm btn-info disabled" title="Estrutura">Estrutura</button>
                          <button class="btn btn-sm btn-info disabled" title="Descritivo">Descritivo</button>-->
                          <button class="btn btn-sm btn-info disabled" title="Respondentes">Respondentes</button>
                          <button class="btn btn-sm btn-primary disabled" title="Visualizar"><i class="glyphicon glyphicon-list-alt"></i> Visualizar</button>
                         ';
			}
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
	public function ajaxVersaoAnterior()
	{
		$this->db->where('id_cargo', $this->input->post('id_cargo'));
		$this->db->where('id_funcao', $this->input->post('id_funcao'));
		$this->db->where('id !=', $this->input->post('id'));
		$this->db->order_by('data', 'desc');
		$this->db->limit(1);
		$row = $this->db->get('job_descriptor')->row();

		$data = array();
		if ($row) {
			$estruturas = array(
				'sumario' => $row->sumario,
				'formacao_experiencia' => $row->formacao_experiencia,
				'condicoes_gerais_exercicio' => $row->condicoes_gerais_exercicio,
				'codigo_internacional_CIUO88' => $row->codigo_internacional_CIUO88,
				'notas' => $row->notas,
				'recursos_trabalho' => $row->recursos_trabalho,
				'atividades' => $row->atividades,
				'responsabilidades' => $row->responsabilidades,
				'conhecimentos_habilidades' => $row->conhecimentos_habilidades,
				'habilidades_basicas' => $row->habilidades_basicas,
				'habilidades_intermediarias' => $row->habilidades_intermediarias,
				'habilidades_avancadas' => $row->habilidades_avancadas,
				'ambiente_trabalho' => $row->ambiente_trabalho,
				'condicoes_trabalho' => $row->condicoes_trabalho,
				'esforcos_fisicos' => $row->esforcos_fisicos,
				'grau_autonomia' => $row->grau_autonomia,
				'grau_complexidade' => $row->grau_complexidade,
				'grau_iniciativa' => $row->grau_iniciativa,
				'competencias_tecnicas' => $row->competencias_tecnicas,
				'competencias_comportamentais' => $row->competencias_comportamentais,
				'tempo_experiencia' => $row->tempo_experiencia,
				'formacao_minima' => $row->formacao_minima,
				'formacao_plena' => $row->formacao_plena,
				'esforcos_mentais' => $row->esforcos_mentais,
				'grau_pressao' => $row->grau_pressao
			);
			$data = array(
				'id_versao_anterior' => null,
				'estruturas' => array_keys(array_filter($estruturas)),
				'campo_livre1' => $row->campo_livre1,
				'campo_livre2' => $row->campo_livre2,
				'campo_livre3' => $row->campo_livre3,
				'campo_livre4' => $row->campo_livre4,
				'campo_livre5' => $row->campo_livre5
			);
			$copiarEstrutura = $this->input->post('copiar_estrutura');
			if ($copiarEstrutura) {
				$data['id_versao_anterior'] = $row->id;
			}
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_edit()
	{
		$id = $this->input->post('id');
		$row = $this->db->get_where('job_descriptor', array('id' => $id))->row();

		$estruturas = array(
			'sumario' => $row->sumario,
			'formacao_experiencia' => $row->formacao_experiencia,
			'condicoes_gerais_exercicio' => $row->condicoes_gerais_exercicio,
			'codigo_internacional_CIUO88' => $row->codigo_internacional_CIUO88,
			'notas' => $row->notas,
			'recursos_trabalho' => $row->recursos_trabalho,
			'atividades' => $row->atividades,
			'responsabilidades' => $row->responsabilidades,
			'conhecimentos_habilidades' => $row->conhecimentos_habilidades,
			'habilidades_basicas' => $row->habilidades_basicas,
			'habilidades_intermediarias' => $row->habilidades_intermediarias,
			'habilidades_avancadas' => $row->habilidades_avancadas,
			'ambiente_trabalho' => $row->ambiente_trabalho,
			'condicoes_trabalho' => $row->condicoes_trabalho,
			'esforcos_fisicos' => $row->esforcos_fisicos,
			'grau_autonomia' => $row->grau_autonomia,
			'grau_complexidade' => $row->grau_complexidade,
			'grau_iniciativa' => $row->grau_iniciativa,
			'competencias_tecnicas' => $row->competencias_tecnicas,
			'competencias_comportamentais' => $row->competencias_comportamentais,
			'tempo_experiencia' => $row->tempo_experiencia,
			'formacao_minima' => $row->formacao_minima,
			'formacao_plena' => $row->formacao_plena,
			'esforcos_mentais' => $row->esforcos_mentais,
			'grau_pressao' => $row->grau_pressao
		);

		$data = array(
			'id' => $row->id,
			'id_empresa' => $row->id_empresa,
			'id_cargo' => $row->id_cargo,
			'id_funcao' => $row->id_funcao,
			'versao' => $row->versao,
			'estruturas' => array_keys(array_filter($estruturas)),
			'campo_livre1' => $row->campo_livre1,
			'campo_livre2' => $row->campo_livre2,
			'campo_livre3' => $row->campo_livre3,
			'campo_livre4' => $row->campo_livre4,
			'campo_livre5' => $row->campo_livre5
		);

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_respondentes($id)
	{
		$row = $this->db->get_where('job_descriptor', array('id' => $id))->row();
		$data['id_descritor'] = $row->id;

		$respondentes = $this->db->get_where('job_descriptor_respondentes', array('id_descritor' => $row->id))->result();
		$data['id_usuario'] = array();
		foreach ($respondentes as $respondente) {
			$data['id_usuario'][] = $respondente->id_usuario;
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_add()
	{
		$data = $this->input->post();
		if (strlen($data['versao']) == 0) {
			exit(json_encode(['erro' => 'O nome da versão é obrigatório']));
		}
		if (empty($data['id_empresa'])) {
			$data['id_empresa'] = $this->session->userdata('empresa');
		}
		$estruturas = $data['estruturas'] ?? array();
		unset($data['id'], $data['estruturas']);

		foreach ($estruturas as $estrutura) {
			$data[$estrutura] = 1;
		}

		$status = $this->db->insert('job_descriptor', $data);

		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function ajax_update()
	{
		$data = $this->input->post();
		if (strlen($data['versao']) == 0) {
			exit(json_encode(['erro' => 'O nome da versão é obrigatório']));
		}
		if (empty($data['id_empresa'])) {
			$data['id_empresa'] = $this->session->userdata('empresa');
		}

		$id = $data['id'];
		$estrutura = isset($data['estruturas']) ? array_combine($data['estruturas'], $data['estruturas']) : array();
		unset($data['id'], $data['estruturas']);

		$data['sumario'] = !empty($estrutura['sumario']);
		$data['formacao_experiencia'] = !empty($estrutura['formacao_experiencia']);
		$data['condicoes_gerais_exercicio'] = !empty($estrutura['condicoes_gerais_exercicio']);
		$data['codigo_internacional_CIUO88'] = !empty($estrutura['codigo_internacional_CIUO88']);
		$data['notas'] = !empty($estrutura['notas']);
		$data['recursos_trabalho'] = !empty($estrutura['recursos_trabalho']);
		$data['atividades'] = !empty($estrutura['atividades']);
		$data['responsabilidades'] = !empty($estrutura['responsabilidades']);
		$data['conhecimentos_habilidades'] = !empty($estrutura['conhecimentos_habilidades']);
		$data['habilidades_basicas'] = !empty($estrutura['habilidades_basicas']);
		$data['habilidades_intermediarias'] = !empty($estrutura['habilidades_intermediarias']);
		$data['habilidades_avancadas'] = !empty($estrutura['habilidades_avancadas']);
		$data['ambiente_trabalho'] = !empty($estrutura['ambiente_trabalho']);
		$data['condicoes_trabalho'] = !empty($estrutura['condicoes_trabalho']);
		$data['esforcos_fisicos'] = !empty($estrutura['esforcos_fisicos']);
		$data['grau_autonomia'] = !empty($estrutura['grau_autonomia']);
		$data['grau_complexidade'] = !empty($estrutura['grau_complexidade']);
		$data['grau_iniciativa'] = !empty($estrutura['grau_iniciativa']);
		$data['competencias_tecnicas'] = !empty($estrutura['competencias_tecnicas']);
		$data['competencias_comportamentais'] = !empty($estrutura['competencias_comportamentais']);
		$data['tempo_experiencia'] = !empty($estrutura['tempo_experiencia']);
		$data['formacao_minima'] = !empty($estrutura['formacao_minima']);
		$data['formacao_plena'] = !empty($estrutura['formacao_plena']);
		$data['esforcos_mentais'] = !empty($estrutura['esforcos_mentais']);
		$data['grau_pressao'] = !empty($estrutura['grau_pressao']);

		if (strlen($data['campo_livre1']) == 0) {
			$data['campo_livre1'] = null;
		}
		if (strlen($data['campo_livre2']) == 0) {
			$data['campo_livre2'] = null;
		}
		if (strlen($data['campo_livre3']) == 0) {
			$data['campo_livre3'] = null;
		}
		if (strlen($data['campo_livre4']) == 0) {
			$data['campo_livre4'] = null;
		}
		if (strlen($data['campo_livre5']) == 0) {
			$data['campo_livre5'] = null;
		}


		$status = $this->db->update('job_descriptor', $data, array('id' => $id));

		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function ajax_saveRespondentes()
	{
		$id_descritor = $this->input->post('id_descritor');
		$id_usuarios = $this->input->post('id_usuario');
		if (empty($id_usuarios)) {
			$id_usuarios = array();
		}

		$this->db->trans_start();

		$this->db->where('id_descritor', $id_descritor);
		$this->db->where_not_in('id_usuario', $id_usuarios + [0]);
		$this->db->delete('job_descriptor_respondentes');

		$this->db->select('id_usuario');
		$this->db->where('id_descritor', $id_descritor);
		$respondentes = $this->db->get('job_descriptor_respondentes')->result();
		$usuariosExistentes = array_column($respondentes, 'id_usuario');

		$this->db->select('a.id_versao_anterior, c.*', false);
		$this->db->join('job_descriptor b', 'b.id = a.id_versao_anterior');
		$this->db->join('job_descriptor_consolidados c', 'c.id_descritor = b.id');
		$this->db->where('a.id', $id_descritor);
		$consolidados = $this->db->get('job_descriptor a')->row_array();

		if (empty($consolidados)) {
			$consolidados = [];
		}

		if (!empty($consolidados['id_versao_anterior'])) {
			$jobDescriptor = $this->db->get_where('job_descriptor', ['id' => $consolidados['id_versao_anterior']])->row_array();
			unset($consolidados['id_versao_anterior'], $consolidados['id_descritor']);
			$consolidados = array_intersect_key($consolidados, array_filter($jobDescriptor));
		}

		$data = array();
		foreach ($id_usuarios as $id_usuario) {
			if (!in_array($id_usuario, $usuariosExistentes)) {
				$data[] = array_merge(['id_descritor' => $id_descritor, 'id_usuario' => $id_usuario], $consolidados);
			}
		}

		if ($data) {
			$this->db->insert_batch('job_descriptor_respondentes', $data);
		}


		$this->db->trans_complete();
		$status = $this->db->trans_status();

		echo json_encode(array("status" => $status !== false));

	}

	//==========================================================================
	public function ajax_delete()
	{
		$id = $this->input->post('id');
		$status = $this->db->delete('job_descriptor', array('id' => $id));

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
		$this->db->order_by('b.nome', 'asc');
		$respondentes = $this->db->get('job_descriptor_respondentes a')->result();

		$data['usuarios'] = array('Agrupados' => array('' => 'Consolidado (sem edição)', 'consolidado' => 'Consolidado (com edição)'));

		foreach ($respondentes as $respondente) {
			$data['usuarios']['Individuais'][$respondente->id] = $respondente->nome;
		}

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
			foreach ($respondentes as $respondente) {
				$data['respondentes'][$estrutura][$respondente->id] = $respondente->$estrutura;
			}
		}

		if ($pdf) {
			return $this->load->view('jobDescriptor_pdf', $data, true);
		} else {
			$this->load->view('jobDescriptor_relatorio', $data);
		}
	}

}
