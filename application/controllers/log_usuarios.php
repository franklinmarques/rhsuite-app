<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe log_usuarios
 *
 * Trabalha com os logs de entrada e saída de usuários
 *
 * @package controllers
 */
class Log_usuarios extends MY_Controller
{

	/**
	 * Construtor da classe
	 *
	 * Carrega o model de log de usuários
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('log_usuarios_model', 'logUsuarios');
	}

	// -------------------------------------------------------------------------

	/**
	 * Abre a tela com a listagem de logs registrados
	 */
	public function index()
	{
		$this->db->select("DISTINCT(depto) AS nome", false);
		$this->db->where("CHAR_LENGTH(depto) >", 0);
		$this->db->order_by('depto', 'asc');
		$deptos = $this->db->get('usuarios')->result();
		$data['depto'] = array('' => 'Todos');
		foreach ($deptos as $depto) {
			$data['depto'][$depto->nome] = $depto->nome;
		}

		$this->db->select("DISTINCT(area) AS nome", false);
		$this->db->where("CHAR_LENGTH(area) >", 0);
		$this->db->order_by('area', 'asc');
		$areas = $this->db->get('usuarios')->result();
		$data['area'] = array('' => 'Todas');
		foreach ($areas as $area) {
			$data['area'][$area->nome] = $area->nome;
		}

		$this->db->select("DISTINCT(setor) AS nome", false);
		$this->db->where("CHAR_LENGTH(setor) >", 0);
		$this->db->order_by('setor', 'asc');
		$setores = $this->db->get('usuarios')->result();
		$data['setor'] = array('' => 'Todos');
		foreach ($setores as $setor) {
			$data['setor'][$setor->nome] = $setor->nome;
		}

		$this->db->select("DISTINCT(cargo) AS nome", false);
		$this->db->where("CHAR_LENGTH(cargo) >", 0);
		$this->db->order_by('cargo', 'asc');
		$cargos = $this->db->get('usuarios')->result();
		$data['cargo'] = array('' => 'Todos');
		foreach ($cargos as $cargo) {
			$data['cargo'][$cargo->nome] = $cargo->nome;
		}

		$this->db->select("DISTINCT(funcao) AS nome", false);
		$this->db->where("CHAR_LENGTH(funcao) >", 0);
		$this->db->order_by('funcao', 'asc');
		$funcoes = $this->db->get('usuarios')->result();
		$data['funcao'] = array('' => 'Todas');
		foreach ($funcoes as $funcao) {
			$data['funcao'][$funcao->nome] = $funcao->nome;
		}

		$data['depto_atual'] = '';
		$data['area_atual'] = '';
		$data['setor_atual'] = '';

		$this->load->view('log_usuarios', $data);
	}

	// -------------------------------------------------------------------------


	public function atualizarFiltro()
	{
		$post = $this->input->post();


		$this->db->select("DISTINCT(area) AS nome", false);
		$this->db->where("CHAR_LENGTH(area) >", 0);
		if ($post['depto']) {
			$this->db->where('depto', $post['depto']);
		}
		$this->db->group_by('depto, area');
		$this->db->order_by('area', 'asc');
		$areas = $this->db->get('usuarios')->result();
		$options['area'] = array('' => 'Todas');
		foreach ($areas as $area) {
			$options['area'][$area->nome] = $area->nome;
		}


		$this->db->select("DISTINCT(setor) AS nome", false);
		$this->db->where("CHAR_LENGTH(setor) >", 0);
		if ($post['depto']) {
			$this->db->where('depto', $post['depto']);
		}
		if ($post['area']) {
			$this->db->where('area', $post['area']);
		}
		$this->db->group_by('depto, area, setor');
		$this->db->order_by('setor', 'asc');
		$setores = $this->db->get('usuarios')->result();
		$options['setor'] = array('' => 'Todos');
		foreach ($setores as $setor) {
			$options['setor'][$setor->nome] = $setor->nome;
		}


		$this->db->select("DISTINCT(cargo) AS nome", false);
		$this->db->where("CHAR_LENGTH(cargo) >", 0);
		if ($post['depto']) {
			$this->db->where('depto', $post['depto']);
		}
		if ($post['area']) {
			$this->db->where('area', $post['area']);
		}
		if ($post['setor']) {
			$this->db->where('setor', $post['setor']);
		}
		$this->db->group_by('depto, area, setor, cargo');
		$this->db->order_by('cargo', 'asc');
		$cargos = $this->db->get('usuarios')->result();
		$options['cargo'] = array('' => 'Todos');
		foreach ($cargos as $cargo) {
			$options['cargo'][$cargo->nome] = $cargo->nome;
		}


		$this->db->select("DISTINCT(funcao) AS nome", false);
		$this->db->where("CHAR_LENGTH(funcao) >", 0);
		if ($post['depto']) {
			$this->db->where('depto', $post['depto']);
		}
		if ($post['area']) {
			$this->db->where('area', $post['area']);
		}
		if ($post['setor']) {
			$this->db->where('setor', $post['setor']);
		}
		if ($post['cargo']) {
			$this->db->where('cargo', $post['cargo']);
		}
		$this->db->group_by('depto, area, setor, cargo, funcao');
		$this->db->order_by('funcao', 'asc');
		$funcoes = $this->db->get('usuarios')->result();
		$options['funcao'] = array('' => 'Todas');
		foreach ($funcoes as $funcao) {
			$options['funcao'][$funcao->nome] = $funcao->nome;
		}


		$data['area'] = form_dropdown('area', $options['area'], $post['area'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['setor'] = form_dropdown('setor', $options['setor'], $post['setor'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['cargo'] = form_dropdown('cargo', $options['cargo'], $post['cargo'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['funcao'] = form_dropdown('funcao', $options['funcao'], $post['funcao'], 'onchange="atualizarFiltro()" class="form-control input-sm"');


		echo json_encode($data);
	}

	// -------------------------------------------------------------------------

	/**
	 * Gera a lista de logs dos usuários
	 */
	public function listar()
	{
		parse_str($this->input->post('busca'), $busca);
		$id = $this->session->userdata('id');
		$empresa = $this->session->userdata('empresa');

		$sql = "SELECT s.id,
                       s.nome, 
                       s.data_acesso,
                       s.data_saida,
                       DATE_FORMAT(s.data_acesso, '%d/%m/%Y &emsp; %H:%i:%s') AS data_hora_acesso,
                       DATE_FORMAT(s.data_saida, '%d/%m/%Y &emsp; %H:%i:%s') AS data_hora_saida
                FROM (SELECT a.id,
                             b.nome, 
                             a.data_acesso,
                             a.data_saida
                             FROM acessosistema a
                      INNER JOIN usuarios b
                                 ON b.id = a.usuario
                      WHERE (b.id = $id OR b.empresa = {$empresa})
                            AND (b.depto = '{$busca['depto']}' OR CHAR_LENGTH('{$busca['depto']}') = 0)
                            AND (b.area = '{$busca['area']}' OR CHAR_LENGTH('{$busca['area']}') = 0)
                            AND (b.setor = '{$busca['setor']}' OR CHAR_LENGTH('{$busca['setor']}') = 0)
                            AND (b.cargo = '{$busca['cargo']}' OR CHAR_LENGTH('{$busca['cargo']}') = 0)
                            AND (b.funcao = '{$busca['funcao']}' OR CHAR_LENGTH('{$busca['funcao']}') = 0)
                      ORDER BY a.data_acesso DESC, 
                               a.data_saida DESC) s";

		$recordsTotal = $this->db->query($sql)->num_rows();

		$post = $this->input->post();
		$columns = array('s.id', 's.nome', 's.data_acesso', 's.data_saida');
		if ($post['search']['value']) {
			foreach ($columns as $key => $column) {
				if ($key > 1) {
					$sql .= "
                         OR {$column} LIKE '%{$post['search']['value']}%'";
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
		foreach ($list as $log) {
			$row = array();
			$row[] = $log->nome;
			$row[] = $log->data_hora_acesso;
			$row[] = $log->data_hora_saida;
			$row[] = '
                      <button class="btn btn-sm btn-info" onclick="detalhes(' . $log->id . ')" title="Detalhes"><i class="glyphicon glyphicon-info-sign"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_log(' . $log->id . ')" title="Excluir registro de log"><i class="glyphicon glyphicon-trash"></i></button>
                     ';

			$data[] = $row;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data,
		);

		echo json_encode($output);
	}

	// -------------------------------------------------------------------------

	/**
	 * Retorna detalhes de um log selecionado para consulta
	 */
	public function detalhes()
	{
		$id = $this->input->post('id');
		$data = $this->logUsuarios->find($id);

		echo json_encode($data);
	}

	// -------------------------------------------------------------------------

	/**
	 * Exclui um registro de log
	 */
	public function excluir()
	{
		$id = $this->input->post('id');
		$status = $this->logUsuarios->delete($id);

		echo json_encode($status !== false);
	}

	// -------------------------------------------------------------------------

	/**
	 * Limpa os registros de log de uma empresa
	 *
	 * Pode ser de acordo com um intervalo de datas selecionadas
	 */
	public function limpar()
	{
		$dataInicio = $this->input->post('data_inicio');
		$dataTermino = $this->input->post('data_termino');

		if ($dataInicio) {
			$dataInicio = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $dataInicio)));
		}
		if ($dataTermino) {
			$dataTermino = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $dataTermino)));
		}

		$empresa = $this->session->userdata('empresa');
		$sql = "DELETE FROM {$this->table} 
                WHERE usuario IN (SELECT id 
                                  FROM usuarios 
                                  WHERE empresa = {$empresa} 
                                        OR id = {$empresa})";
		if ($dataInicio) {
			$sql .= " AND (data_acesso >= '{$dataInicio}' OR (data_saida >= '{$dataInicio}' OR data_saida IS NULL))";
		}
		if ($dataTermino) {
			$sql .= " AND (data_acesso <= '{$dataTermino}' OR (data_saida <= '{$dataTermino}' OR data_saida IS NULL))";
		}

		$status = $this->db->query($sql);

		echo json_encode($status !== false);
	}

	// -------------------------------------------------------------------------

	/**
	 * Gera um relatório de registros de logs
	 */
	public function relatorio()
	{

	}

	// -------------------------------------------------------------------------

	/**
	 * Baixa o relatório de logs no formato pdf
	 */
	public function pdf()
	{

	}

}
