<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Diretorias extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		if (!in_array($this->session->userdata('nivel'), array(0, 4, 7, 8, 9))) {
			redirect(site_url('home'));
		}
	}

	//==========================================================================
	public function index()
	{
		$empresa = $this->session->userdata('empresa');

		$data = array();

		$this->db->select('DISTINCT(depto) AS nome', false);
		$this->db->where('empresa', $empresa);
		$this->db->where('CHAR_LENGTH(depto) >', 0);
		$this->db->order_by('depto', 'asc');
		$deptos_disponiveis = $this->db->get('usuarios')->result();
		$data['deptos_disponiveis'] = array('' => 'selecione...');
		foreach ($deptos_disponiveis as $depto_disponivel) {
			$data['deptos_disponiveis'][$depto_disponivel->nome] = $depto_disponivel->nome;
		}

		$data['cuidadores'] = '';
		$data['coordenadores'] = array('' => 'selecione...');

		$this->db->select('DISTINCT(depto) AS nome', false);
		$this->db->where('empresa', $empresa);
		$this->db->where('depto', 'educação inclusiva');
		$cuidadores = $this->db->get('usuarios')->row();

		if (count($cuidadores) > 0) {
			$data['cuidadores'] = $cuidadores->nome;

			$this->db->select('id, nome');
			$this->db->where('empresa', $empresa);
			$this->db->where('depto', $cuidadores->nome);
			$this->db->order_by('nome', 'asc');
			$usuarios = $this->db->get('usuarios')->result();
			foreach ($usuarios as $usuario) {
				$data['coordenadores'][$usuario->id] = $usuario->nome;
			}
		}

		$this->db->select('DISTINCT(depto) AS nome', false);
		$this->db->where('id_empresa', $empresa);
		$this->db->order_by('depto', 'asc');
		$deptos = $this->db->get('ei_diretorias')->result();
		$data['depto'] = array('' => 'Todos');
		foreach ($deptos as $depto) {
			$data['depto'][$depto->nome] = $depto->nome;
		}

		$this->db->select('DISTINCT(nome) AS nome', false);
		$this->db->where('id_empresa', $empresa);
		$this->db->order_by('nome', 'asc');
		$diretorias = $this->db->get('ei_diretorias')->result();
		$data['diretoria'] = array('' => 'Todas');
		foreach ($diretorias as $diretoria) {
			$data['diretoria'][$diretoria->nome] = $diretoria->nome;
		}

		$this->db->select('a.id_coordenador AS id, b.nome', false);
		$this->db->join('usuarios b', 'b.id = a.id_coordenador');
		$this->db->where('a.id_empresa', $empresa);
		$this->db->order_by('b.nome', 'asc');
		$this->db->group_by('a.id_coordenador');
		$coordenadores = $this->db->get('ei_diretorias a')->result();
		$data['coordenador'] = array('' => 'Todos');
		foreach ($coordenadores as $coordenador) {
			$data['coordenador'][$coordenador->id] = $coordenador->nome;
		}

		$this->db->select('DISTINCT(a.contrato) AS nome', false);
		$this->db->join('ei_diretorias b', 'b.id = a.id_cliente');
		$this->db->where('b.id_empresa', $empresa);
		$this->db->order_by('a.contrato', 'asc');
		$contratos = $this->db->get('ei_contratos a')->result();
		$data['contrato'] = array('' => 'Todos');
		foreach ($contratos as $contrato) {
			$data['contrato'][$contrato->nome] = $contrato->nome;
		}

		$this->load->view('ei/diretorias', $data);
	}

	//==========================================================================
	public function atualizar_filtro()
	{
		$empresa = $this->session->userdata('empresa');
		$busca = $this->input->post('busca');
		$filtro = array();

		$this->db->select('DISTINCT(nome) AS nome', false);
		$this->db->where('id_empresa', $empresa);
		if ($busca['depto']) {
			$this->db->where('depto', $busca['depto']);
		}
		$this->db->order_by('nome', 'asc');
		$diretorias = $this->db->get('ei_diretorias')->result();
		$filtro['diretoria'] = array('' => 'Todas');
		foreach ($diretorias as $diretoria) {
			$filtro['diretoria'][$diretoria->nome] = $diretoria->nome;
		}

		$this->db->select('a.id_coordenador AS id, b.nome', false);
		$this->db->join('usuarios b', 'b.id = a.id_coordenador');
		$this->db->where('a.id_empresa', $empresa);
		if ($busca['depto']) {
			$this->db->where('a.depto', $busca['depto']);
		}
		if ($busca['diretoria']) {
			$this->db->where('a.nome', $busca['diretoria']);
		}
		$this->db->order_by('b.nome', 'asc');
		$this->db->group_by('a.id_coordenador');
		$coordenadores = $this->db->get('ei_diretorias a')->result();
		$filtro['coordenador'] = array('' => 'Todos');
		foreach ($coordenadores as $coordenador) {
			$filtro['coordenador'][$coordenador->id] = $coordenador->nome;
		}

		$this->db->select('DISTINCT(a.contrato) AS nome', false);
		$this->db->join('ei_diretorias b', 'b.id = a.id_cliente');
		$this->db->where('b.id_empresa', $empresa);
		if ($busca['depto']) {
			$this->db->where('b.depto', $busca['depto']);
		}
		if ($busca['diretoria']) {
			$this->db->where('b.nome', $busca['diretoria']);
		}
		if ($busca['coordenador']) {
			$this->db->where('b.id_coordenador', $busca['coordenador']);
		}
		$this->db->order_by('a.contrato', 'asc');
		$contratos = $this->db->get('ei_contratos a')->result();
		$filtro['contrato'] = array('' => 'Todos');
		foreach ($contratos as $contrato) {
			$filtro['contrato'][$contrato->nome] = $contrato->nome;
		}


		$data['diretoria'] = form_dropdown('diretoria', $filtro['diretoria'], $busca['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['coordenador'] = form_dropdown('coordenador', $filtro['coordenador'], $busca['coordenador'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['contrato'] = form_dropdown('contrato', $filtro['contrato'], $busca['contrato'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_list()
	{
		$post = $this->input->post();
		parse_str($this->input->post('busca'), $arrBusca);
		$busca = $arrBusca['busca'] ?? array();

		$sql = "SELECT s.id,
                       s.nome,
                       s.contrato,
                       s.id_contrato,
                       s.id_valor_faturamento,
                       s.ano_semestre,
                       s.funcao,
                       s.qtde_horas,
                       s.valor,
                       s.valor_pagamento,
                       s.valor2,
                       s.valor_pagamento2
                FROM (SELECT a.id,
                             a.nome,
                             d.contrato,
                             d.id AS id_contrato,
                             e.id AS id_valor_faturamento,
                             CONCAT(e.ano, '/', e.semestre) AS ano_semestre,
                             f.nome AS funcao,
                             FORMAT(e.qtde_horas, 2, 'de_DE') AS qtde_horas,
                             FORMAT(e.valor, 2, 'de_DE') AS valor,
                             FORMAT(e.valor_pagamento, 2, 'de_DE') AS valor_pagamento,
                             FORMAT(e.valor2, 2, 'de_DE') AS valor2,
                             FORMAT(e.valor_pagamento2, 2, 'de_DE') AS valor_pagamento2
                      FROM ei_diretorias a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_empresa 
                      LEFT JOIN usuarios c ON
                                c.id = a.id_coordenador
                      LEFT JOIN ei_contratos d ON 
                                d.id_cliente = a.id
                      LEFT JOIN ei_valores_faturamento e ON 
                                e.id_contrato = d.id
                      LEFT JOIN empresa_funcoes f ON 
                                f.id = e.id_funcao
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}";
		if (!empty($busca['depto'])) {
			$sql .= " AND a.depto = '{$busca['depto']}'";
		}
		if (!empty($busca['diretoria'])) {
			$sql .= " AND a.nome = '{$busca['diretoria']}'";
		}
		if (!empty($busca['coordenador'])) {
			$sql .= " AND a.id_coordenador = '{$busca['coordenador']}'";
		}
		if (!empty($busca['contrato'])) {
			$sql .= " AND d.contrato = '{$busca['contrato']}'";
		}
		$sql .= ' GROUP BY a.id, d.id, e.id) s';
		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array('s.id', 's.nome', 's.contrato');
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
				$orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
			}
			$sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
		}
		$sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
		$list = $this->db->query($sql)->result();

		$data = array();
		foreach ($list as $ei) {
			$row = array();
			$row[] = $ei->nome;
			$row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_cliente(' . $ei->id . ')" title="Editar área/cliente"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_cliente(' . $ei->id . ')" title="Excluir área/cliente"><i class="glyphicon glyphicon-trash"></i> </button>
                      <button type="button" class="btn btn-sm btn-info" onclick="add_contrato(' . $ei->id . ')" title="Adicionar contrato"><i class="glyphicon glyphicon-plus"></i> Contrato</button>
                     ';
			$row[] = $ei->contrato;
			if ($ei->contrato) {
				$row[] = '
                          <button type="button" class="btn btn-sm btn-info" onclick="edit_contrato(' . $ei->id_contrato . ')" title="Editar contrato"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button type="button" class="btn btn-sm btn-danger" onclick="delete_contrato(' . $ei->id_contrato . ')" title="Excluir contrato"><i class="glyphicon glyphicon-trash"></i> </button>
                          <button type="button" class="btn btn-sm btn-info" onclick="add_valor_faturamento(' . $ei->id_contrato . ')" title="Adicionar valor faturamento"><i class="glyphicon glyphicon-plus"></i> Valores</button>
                         ';
			} else {
				$row[] = '
                          <button type="button" class="btn btn-sm btn-info disabled" title="Editar contrato"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button type="button" class="btn btn-sm btn-danger disabled" title="Excluir contrato"><i class="glyphicon glyphicon-trash"></i> </button>
                          <button type="button" class="btn btn-sm btn-info disabled" title="Adicionar valor faturamento"><i class="glyphicon glyphicon-plus"></i> Valores</button>
                         ';
			}
			$row[] = $ei->ano_semestre;
			$row[] = $ei->funcao;
			$row[] = $ei->qtde_horas;
			$row[] = $ei->valor;
			$row[] = $ei->valor_pagamento;
			$row[] = $ei->valor2;
			$row[] = $ei->valor_pagamento2;
			if ($ei->id_valor_faturamento) {
				$row[] = '
                          <button type="button" class="btn btn-sm btn-info" onclick="edit_valor_faturamento(' . $ei->id_valor_faturamento . ')" title="Editar valor faturamento"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button type="button" class="btn btn-sm btn-danger" onclick="delete_valor_faturamento(' . $ei->id_valor_faturamento . ')" title="Excluir valor faturamento"><i class="glyphicon glyphicon-trash"></i> </button>
                         ';
			} else {
				$row[] = '
                          <button type="button" class="btn btn-sm btn-info disabled" title="Editar valor faturamento"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button type="button" class="btn btn-sm btn-danger disabled" title="Excluir valor faturamento"><i class="glyphicon glyphicon-trash"></i> </button>
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
	public function ajax_edit()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('ei_diretorias', array('id' => $id))->row();

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_editContrato()
	{
		$id = $this->input->post('id');
		$this->db->select('id, id_cliente, contrato, indice_reajuste1, indice_reajuste2');
		$this->db->select('indice_reajuste3, indice_reajuste4, indice_reajuste5');
		$this->db->select("DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio", false);
		$this->db->select("DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino", false);
		$this->db->select("DATE_FORMAT(data_reajuste1, '%d/%m/%Y') AS data_reajuste1", false);
		$this->db->select("DATE_FORMAT(data_reajuste1, '%d/%m/%Y') AS data_reajuste1", false);
		$this->db->select("DATE_FORMAT(data_reajuste2, '%d/%m/%Y') AS data_reajuste2", false);
		$this->db->select("DATE_FORMAT(data_reajuste3, '%d/%m/%Y') AS data_reajuste3", false);
		$this->db->select("DATE_FORMAT(data_reajuste4, '%d/%m/%Y') AS data_reajuste4", false);
		$this->db->select("DATE_FORMAT(data_reajuste5, '%d/%m/%Y') AS data_reajuste5", false);
		$data = $this->db->get_where('ei_contratos', array('id' => $id))->row();
		if ($data->indice_reajuste1) {
			$data->indice_reajuste1 = number_format($data->indice_reajuste1, 8, ',', '');
		}
		if ($data->indice_reajuste2) {
			$data->indice_reajuste2 = number_format($data->indice_reajuste2, 8, ',', '');
		}
		if ($data->indice_reajuste3) {
			$data->indice_reajuste3 = number_format($data->indice_reajuste3, 8, ',', '');
		}
		if ($data->indice_reajuste4) {
			$data->indice_reajuste4 = number_format($data->indice_reajuste4, 8, ',', '');
		}
		if ($data->indice_reajuste5) {
			$data->indice_reajuste5 = number_format($data->indice_reajuste5, 8, ',', '');
		}

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_valores()
	{
		$id = $this->input->post('id');

		$this->db->select('id, contrato');
		$this->db->where('id', $id);
		$data = $this->db->get('ei_contratos')->row();

		$this->db->select('a.id, a.nome');
		$this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
		$this->db->where('b.id_empresa', $this->session->userdata('empresa'));
		$this->db->order_by('a.nome', 'asc');
		$rows = $this->db->get('empresa_funcoes a')->result();
		$funcoes = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');

		$data->funcoes = form_dropdown('id_funcao', $funcoes, '', 'class="form-control"');

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_editValores()
	{
		$id = $this->input->post('id');

		$this->db->select('a.*, b.contrato', false);
		$this->db->join('ei_contratos b', 'b.id = a.id_contrato');
		$this->db->where('a.id', $id);
		$data = $this->db->get('ei_valores_faturamento a')->row();

		if ($data->qtde_horas) {
			$data->qtde_horas = number_format($data->qtde_horas, 2, ',', '.');
		}
		if ($data->valor) {
			$data->valor = number_format($data->valor, 2, ',', '.');
		}
		if ($data->valor_pagamento) {
			$data->valor_pagamento = number_format($data->valor_pagamento, 2, ',', '.');
		}

		if ($data->valor2) {
			$data->valor2 = number_format($data->valor2, 2, ',', '.');
		}
		if ($data->valor_pagamento2) {
			$data->valor_pagamento2 = number_format($data->valor_pagamento2, 2, ',', '.');
		}

		$this->db->select('a.id, a.nome');
		$this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
		$this->db->where('b.id_empresa', $this->session->userdata('empresa'));
		$this->db->order_by('a.nome', 'asc');
		$rows = $this->db->get('empresa_funcoes a')->result();
		$funcoes = ['' => 'selecione...'] + array_column($rows, 'nome', 'id');

		$data->funcoes = form_dropdown('id_funcao', $funcoes, $data->id_funcao, 'class="form-control"');

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_estrutura()
	{
		$depto = $this->input->post('depto');
		$id = $this->input->post('id_coordenador');

		$this->db->select('id, nome');
		$this->db->where('empresa', $this->session->userdata('empresa'));
		$this->db->where('depto', $depto);
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();

		$coordenadores = array('' => 'selecione...');
		foreach ($usuarios as $usuario) {
			$coordenadores[$usuario->id] = $usuario->nome;
		}

		$data['id_coordenador'] = form_dropdown('id_coordenador', $coordenadores, $id, 'id="id_coordenador" class="form-control"');

		echo json_encode($data);
	}

	//==========================================================================
	public function ajax_add()
	{
		$data = $this->input->post();
		$data['id_empresa'] = $this->session->userdata('empresa');
		unset($data['id']);
		if (empty($data['alias'])) {
			$data['alias'] = null;
		}
		if (strlen($data['senha_exclusao']) == 0) {
			$data['senha_exclusao'] = null;
		}

		$status = $this->db->insert('ei_diretorias', $data);
		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function ajax_addContrato()
	{
		$data = $this->input->post();
		if (strlen($data['contrato']) == 0) {
			exit(json_encode(array('erro' => 'Onome do contrato é obrigatório.')));
		}
		if (empty($data['data_inicio'])) {
			exit(json_encode(array('erro' => 'A data de início é obrigatória.')));
		}
		if (empty($data['data_termino'])) {
			exit(json_encode(array('erro' => 'A data de término é obrigatória.')));
		}
		$data['data_inicio'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_inicio'])));
		$data['data_termino'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_termino'])));
		if ($data['data_reajuste1'] and $data['indice_reajuste1']) {
			$data['data_reajuste1'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste1'])));
			$data['indice_reajuste1'] = str_replace(',', '.', $data['data_reajuste1']);
		} else {
			$data['data_reajuste1'] = null;
			$data['indice_reajuste1'] = null;
		}
		if ($data['data_reajuste2'] and $data['indice_reajuste2']) {
			$data['data_reajuste2'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste2'])));
			$data['indice_reajuste2'] = str_replace(',', '.', $data['data_reajuste2']);
		} else {
			$data['data_reajuste2'] = null;
			$data['indice_reajuste2'] = null;
		}
		if ($data['data_reajuste3'] and $data['indice_reajuste3']) {
			$data['data_reajuste3'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste3'])));
			$data['indice_reajuste3'] = str_replace(',', '.', $data['data_reajuste3']);
		} else {
			$data['data_reajuste3'] = null;
			$data['indice_reajuste3'] = null;
		}
		if ($data['data_reajuste4'] and $data['indice_reajuste4']) {
			$data['data_reajuste4'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste4'])));
			$data['indice_reajuste4'] = str_replace(',', '.', $data['data_reajuste4']);
		} else {
			$data['data_reajuste4'] = null;
			$data['indice_reajuste4'] = null;
		}
		if ($data['data_reajuste5'] and $data['indice_reajuste5']) {
			$data['data_reajuste5'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste5'])));
			$data['indice_reajuste5'] = str_replace(',', '.', $data['data_reajuste5']);
		} else {
			$data['data_reajuste5'] = null;
			$data['indice_reajuste5'] = null;
		}
		unset($data['id']);

		$status = $this->db->insert('ei_contratos', $data);
		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function ajax_addValores()
	{
		$data = $this->input->post();
		if (!empty($data['qtde_horas'])) {
			$data['qtde_horas'] = str_replace(array('.', ','), array('', '.'), $data['qtde_horas']);
		} else {
			$data['qtde_horas'] = null;
		}
		if (!empty($data['valor'])) {
			$data['valor'] = str_replace(array('.', ','), array('', '.'), $data['valor']);
		} else {
			$data['valor'] = null;
		}
		if (!empty($data['valor_pagamento'])) {
			$data['valor_pagamento'] = str_replace(array('.', ','), array('', '.'), $data['valor_pagamento']);
		} else {
			$data['valor_pagamento'] = null;
		}
		if (!empty($data['valor2'])) {
			$data['valor2'] = str_replace(array('.', ','), array('', '.'), $data['valor2']);
		} else {
			$data['valor2'] = null;
		}
		if (!empty($data['valor_pagamento2'])) {
			$data['valor_pagamento2'] = str_replace(array('.', ','), array('', '.'), $data['valor_pagamento2']);
		} else {
			$data['valor_pagamento2'] = null;
		}

		$this->db->select('id_cargo');
		$funcao = $this->db->get_where('empresa_funcoes', ['id' => $data['id_funcao']])->row();
		$data['id_cargo'] = $funcao->id_cargo ?? null;

		$status = $this->db->insert('ei_valores_faturamento', $data);
		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function ajax_update()
	{
		$data = $this->input->post();
		$data['id_empresa'] = $this->session->userdata('empresa');
		$id = $data['id'];
		unset($data['id']);
		if (empty($data['alias'])) {
			$data['alias'] = null;
		}
		if (strlen($data['senha_exclusao']) == 0) {
			$data['senha_exclusao'] = null;
		}

		$status = $this->db->update('ei_diretorias', $data, array('id' => $id));
		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function ajax_updateContrato()
	{
		$data = $this->input->post();
		if (strlen($data['contrato']) == 0) {
			exit(json_encode(array('erro' => 'Onome do contrato é obrigatório.')));
		}
		if (empty($data['data_inicio'])) {
			exit(json_encode(array('erro' => 'A data de início é obrigatória.')));
		}
		if (empty($data['data_termino'])) {
			exit(json_encode(array('erro' => 'A data de término é obrigatória.')));
		}
		$data['data_inicio'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_inicio'])));
		$data['data_termino'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_termino'])));
		if ($data['data_reajuste1'] and $data['indice_reajuste1']) {
			$data['data_reajuste1'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste1'])));
			$data['indice_reajuste1'] = str_replace(',', '.', $data['data_reajuste1']);
		} else {
			$data['data_reajuste1'] = null;
			$data['indice_reajuste1'] = null;
		}
		if ($data['data_reajuste2'] and $data['indice_reajuste2']) {
			$data['data_reajuste2'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste2'])));
			$data['indice_reajuste2'] = str_replace(',', '.', $data['data_reajuste2']);
		} else {
			$data['data_reajuste2'] = null;
			$data['indice_reajuste2'] = null;
		}
		if ($data['data_reajuste3'] and $data['indice_reajuste3']) {
			$data['data_reajuste3'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste3'])));
			$data['indice_reajuste3'] = str_replace(',', '.', $data['data_reajuste3']);
		} else {
			$data['data_reajuste3'] = null;
			$data['indice_reajuste3'] = null;
		}
		if ($data['data_reajuste4'] and $data['indice_reajuste4']) {
			$data['data_reajuste4'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste4'])));
			$data['indice_reajuste4'] = str_replace(',', '.', $data['data_reajuste4']);
		} else {
			$data['data_reajuste4'] = null;
			$data['indice_reajuste4'] = null;
		}
		if ($data['data_reajuste5'] and $data['indice_reajuste5']) {
			$data['data_reajuste5'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_reajuste5'])));
			$data['indice_reajuste5'] = str_replace(',', '.', $data['data_reajuste5']);
		} else {
			$data['data_reajuste5'] = null;
			$data['indice_reajuste5'] = null;
		}
		$id = $data['id'];
		unset($data['id']);

		$status = $this->db->update('ei_contratos', $data, array('id' => $id));
		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function ajax_updateValores()
	{
		$data = $this->input->post();
		$id = $data['id'];
		unset($data['id']);
		if (!empty($data['qtde_horas'])) {
			$data['qtde_horas'] = str_replace(array('.', ','), array('', '.'), $data['qtde_horas']);
		} else {
			$data['qtde_horas'] = null;
		}
		if (!empty($data['valor'])) {
			$data['valor'] = str_replace(array('.', ','), array('', '.'), $data['valor']);
		} else {
			$data['valor'] = null;
		}
		if (!empty($data['valor_pagamento'])) {
			$data['valor_pagamento'] = str_replace(array('.', ','), array('', '.'), $data['valor_pagamento']);
		} else {
			$data['valor_pagamento'] = null;
		}
		if (!empty($data['valor2'])) {
			$data['valor2'] = str_replace(array('.', ','), array('', '.'), $data['valor2']);
		} else {
			$data['valor2'] = null;
		}
		if (!empty($data['valor_pagamento2'])) {
			$data['valor_pagamento2'] = str_replace(array('.', ','), array('', '.'), $data['valor_pagamento2']);
		} else {
			$data['valor_pagamento2'] = null;
		}

		$this->db->select('id_cargo');
		$funcao = $this->db->get_where('empresa_funcoes', ['id' => $data['id_funcao']])->row();
		$data['id_cargo'] = $funcao->id_cargo ?? null;

		$status = $this->db->update('ei_valores_faturamento', $data, ['id' => $id]);
		echo json_encode(array("status" => $status !== false));
	}

	//==========================================================================
	public function ajax_delete()
	{
		$id = $this->input->post('id');
		$status = $this->db->delete('ei_diretorias', array('id' => $id));

		echo json_encode(array('status' => $status !== false));
	}

	//==========================================================================
	public function ajax_deleteContrato()
	{
		$id = $this->input->post('id');
		$status = $this->db->delete('ei_contratos', array('id' => $id));

		echo json_encode(array('status' => $status !== false));
	}

	//==========================================================================
	public function ajax_deleteValores()
	{
		$id = $this->input->post('id');
		$status = $this->db->delete('ei_valores_faturamento', array('id' => $id));

		echo json_encode(array('status' => $status !== false));
	}

}
