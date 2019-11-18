<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe UsuarioAfastamento
 *
 * Trabalha com o gerenciamento de exames periódicos dos colaboradores
 *
 * @author frank
 * @access public
 * @package CI_Controller\MY_Controller
 * @version 1.0
 */
class UsuarioAfastamento extends MY_Controller
{

	/**
	 * Construtor da classe
	 *
	 * Carrega o model de afastamento do usuario
	 *
	 * @access public
	 * @uses ..\models\usuarioafastamento_model.php Model
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuarios_afastamento_model', 'afastamento');
	}

	/**
	 * Função padrão
	 *
	 * @access public
	 */
	public function index()
	{
		$this->relatorio();
	}

	// -------------------------------------------------------------------------

	/**
	 * Retorna lista de afastamento criados
	 *
	 * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
	 *
	 * @access public
	 */
	public function ajax_list($id_usuario)
	{
		$post = $this->input->post();

		$sql = "SELECT s.id,
                       s.data_afastamento,
                       s.motivo_afastamento,
                       s.data_pericia_medica,
                       s.data_limite_beneficio,
                       s.data_retorno,
                       s.historico_afastamento,
                       s.data_afastamento_de,
                       s.data_pericia_medica_de,
                       s.data_limite_beneficio_de,
                       s.data_retorno_de,
                       s.matricula
                FROM (SELECT a.id, 
                             a.data_afastamento,
                             CASE a.motivo_afastamento
                                  WHEN 1 THEN 'Auxílio doença - INSS'
                                  WHEN 2 THEN 'Licença maternidade'
                                  WHEN 3 THEN 'Acidente de trabalho'
                                  WHEN 4 THEN 'Aposentadoria por invalidez'
                                  END AS motivo_afastamento,
                             a.data_pericia_medica,
                             a.data_limite_beneficio,
                             a.data_retorno,
                             a.historico_afastamento,
                             DATE_FORMAT(a.data_afastamento,'%d/%m/%Y') AS data_afastamento_de,
                             DATE_FORMAT(a.data_pericia_medica,'%d/%m/%Y') AS data_pericia_medica_de,
                             DATE_FORMAT(a.data_limite_beneficio,'%d/%m/%Y') AS data_limite_beneficio_de,
                             DATE_FORMAT(a.data_retorno,'%d/%m/%Y') AS data_retorno_de,
                             b.matricula
                      FROM usuarios_afastamento a
                      INNER JOIN usuarios b
                                 ON b.id = a.id_usuario
                                 AND b.empresa = a.id_empresa
                      WHERE b.id = {$id_usuario}";
		if (!empty($post['status'])) {
			$sql .= ' AND b.status IN (6, 7, 8, 9)';
		}
		if (!empty($post['status2'])) {
			$sql .= ' AND CHAR_LENGTH(a.data_retorno) = 0';
		}
		$sql .= ') s';
		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array(
			's.id',
			's.data_afastamento_de',
			's.motivo_afastamento',
			's.data_pericia_medica_de',
			's.data_limite_beneficio_de',
			's.data_retorno_de',
			's.historico_afastamento',
			's.matricula'
		);
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
		foreach ($list as $afastamento) {
			$row = array();
			$row[] = $afastamento->data_afastamento_de;
			$row[] = $afastamento->motivo_afastamento;
			$row[] = $afastamento->data_pericia_medica_de;
			$row[] = $afastamento->data_limite_beneficio_de;
			$row[] = $afastamento->data_retorno_de;
			$row[] = $afastamento->historico_afastamento;
			$row[] = '
                      <button type="button" class="btn btn-sm btn-primary" onclick="edit_afastamento(' . $afastamento->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_afastamento(' . $afastamento->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
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

	// -------------------------------------------------------------------------

	/**
	 * Retorna dados para edição de afastamento
	 *
	 * @access public
	 */
	public function ajax_edit($id)
	{
		$data = $this->afastamento->find($id);

		if (!empty($data->data_afastamento)) {
			$data->data_afastamento = date('d/m/Y', strtotime($data->data_afastamento));
		}
		if (!empty($data->data_pericia_medica)) {
			$data->data_pericia_medica = date('d/m/Y', strtotime($data->data_pericia_medica));
		}
		if (!empty($data->data_limite_beneficio)) {
			$data->data_limite_beneficio = date('d/m/Y', strtotime($data->data_limite_beneficio));
		}
		if (!empty($data->data_retorno)) {
			$data->data_retorno = date('d/m/Y', strtotime($data->data_retorno));
		}

		echo json_encode($data);
	}

	// -------------------------------------------------------------------------

	/**
	 * Cadastra um novo afastamento
	 *
	 * @access public
	 */
	public function ajax_add()
	{
		$this->load->library('entities');

		$data = $this->entities->create('UsuariosAfastamento', $this->input->post());

		$this->afastamento->insert($data) or exit(json_encode(['retorno' => 0, 'aviso' => $this->afastamento->errors()]));

		echo json_encode(['status' => true]);
	}

	// -------------------------------------------------------------------------

	/**
	 * Altera um afastamento existente
	 *
	 * @access public
	 */
	public function ajax_update()
	{
		$this->load->library('entities');

		$data = $this->entities->create('UsuariosAfastamento', $this->input->post());

		$this->afastamento->update($data->id, $data) or exit(json_encode(['retorno' => 0, 'aviso' => $this->afastamento->errors()]));

		echo json_encode(['status' => true]);
	}

	// -------------------------------------------------------------------------

	/**
	 * Exclui um afastamento existente
	 *
	 * @access public
	 */
	public function ajax_delete()
	{
		$this->afastamento->delete($this->input->post('id')) or exit(json_encode(['retorno' => 0, 'aviso' => $this->afastamento->errors()]));

		echo json_encode(['status' => true]);
	}

	// -------------------------------------------------------------------------

	/**
	 * Relatório de todos os afastamentos listados
	 *
	 * @access public
	 */
	public function relatorio($pdf = false)
	{
		$empresa = $this->session->userdata('empresa');

		$data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
		$data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');
		$data['is_pdf'] = $pdf;

		if ($pdf) {
			$motivoAfastamento = $this->input->get('motivo_afastamento');
			$depto = $this->input->get('depto');
			$area = $this->input->get('area');
			$setor = $this->input->get('setor');
			$status = $this->input->get('status');
			$status2 = $this->input->get('status2');

			$this->db->select('b.id, b.nome', false);
			$this->db->select("CASE a.motivo_afastamento
                                WHEN 1 THEN 'Auxílio doença - INSS'
                                WHEN 2 THEN 'Licença maternidade'
                                WHEN 3 THEN 'Acidente de trabalho'
                                WHEN 4 THEN 'Aposentadoria por invalidez'
                                END AS motivo_afastamento", false);
			$this->db->select("DATE_FORMAT(a.data_afastamento, '%d/%m/%Y') AS data_afastamento", false);
			$this->db->select("IF(a.data_pericia_medica, DATE_FORMAT(a.data_pericia_medica, '%d/%m/%Y'), NULL) AS data_pericia_medica", false);
			$this->db->select("IF(a.data_limite_beneficio, DATE_FORMAT(a.data_limite_beneficio, '%d/%m/%Y'), NULL) AS data_limite_beneficio", false);
			$this->db->select("IF(a.data_retorno, DATE_FORMAT(a.data_retorno, '%d/%m/%Y'), NULL) AS data_retorno", false);
			$this->db->join('usuarios b', 'b.id = a.id_usuario');
			$this->db->join('empresa_departamentos c', 'c.id = b.id_depto OR c.nome = b.depto', 'left');
			$this->db->join('empresa_areas d', 'd.id = b.id_area OR d.nome = b.area', 'left');
			$this->db->join('empresa_setores e', 'e.id = b.id_setor OR e.nome = b.setor', 'left');
			$this->db->where('b.empresa', $empresa);
			if ($motivoAfastamento) {
				$this->db->where('a.motivo_afastamento', $motivoAfastamento);
			}
			if ($depto) {
				$this->db->where('c.id', $depto);
			}
			if ($area) {
				$this->db->where('d.id', $area);
			}
			if ($setor) {
				$this->db->where('e.id', $setor);
			}
			if ($status) {
				$this->db->where_in('b.status', [6, 7, 8, 9]);
			}
			if ($status2) {
				$this->db->where('a.data_retorno', null);
			}
			$this->db->group_by(['a.id', 'b.id']);
			$this->db->order_by('b.nome', 'asc');
			$data['funcionarios'] = $this->db->get('usuarios_afastamento a')->result();

			return $this->load->view('funcionarios_afastamentoPdf', $data, true);
		}

		$this->db->select('id, nome');
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$this->db->order_by('nome', 'asc');
		$deptos = $this->db->get('empresa_departamentos')->result();
		$data['depto'] = ['' => 'Todos'] + array_column($deptos, 'nome', 'id');
		$data['area'] = ['' => 'Todas'];
		$data['setor'] = ['' => 'Todos'];

		$this->load->view('funcionarios_afastamentoRelatorio', $data);
	}


	public function filtrarEstrutura()
	{
		$depto = $this->input->post('depto');
		$area = $this->input->post('area');
		$setor = $this->input->post('setor');

		$this->db->select('a.id, a.nome');
		$this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
		$this->db->where('b.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('b.id', $depto);
		$this->db->order_by('a.nome', 'asc');
		$rowAreas = $this->db->get('empresa_areas a')->result();
		$areas = array_column($rowAreas, 'nome', 'id');

		$this->db->select('a.id, a.nome');
		$this->db->join('empresa_areas b', 'b.id = a.id_area');
		$this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
		$this->db->where('c.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('c.id', $depto);
		$this->db->where('b.id', $area);
		$this->db->order_by('a.nome', 'asc');
		$rowSetores = $this->db->get('empresa_setores a')->result();
		$setores = array_column($rowSetores, 'nome', 'id');

		$data['area'] = form_dropdown('', ['' => 'Todas'] + $areas, $area);
		$data['setor'] = form_dropdown('', ['' => 'Todos'] + $setores, $setor);

		echo json_encode($data);
	}

	// -------------------------------------------------------------------------

	/**
	 * Retorna lista de afastamento criados
	 *
	 * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
	 *
	 * @access public
	 */
	public function ajax_relatorio()
	{
		$post = $this->input->post();


		$sql = "SELECT s.id,
                       s.nome,
                       s.data_afastamento,
                       s.motivo_afastamento,
                       s.data_pericia_medica,
                       s.data_limite_beneficio,
                       s.data_retorno,
                       s.data_afastamento_de,
                       s.data_pericia_medica_de,
                       s.data_limite_beneficio_de,
                       s.data_retorno_de,
                       s.matricula
                FROM (SELECT b.id,
                             b.nome,
                             b.matricula,
                             a.data_afastamento,
                             CASE a.motivo_afastamento
                                  WHEN 1 THEN 'Auxílio doença - INSS'
                                  WHEN 2 THEN 'Licença maternidade'
                                  WHEN 3 THEN 'Acidente de trabalho'
                                  WHEN 4 THEN 'Aposentadoria por invalidez'
                                  END AS motivo_afastamento,
                             a.data_pericia_medica,
                             a.data_limite_beneficio,
                             a.data_retorno,
                             DATE_FORMAT(a.data_afastamento,'%d/%m/%Y') AS data_afastamento_de,
                             IF(a.data_pericia_medica, DATE_FORMAT(a.data_pericia_medica,'%d/%m/%Y'), NULL) AS data_pericia_medica_de,
                             IF(a.data_limite_beneficio, DATE_FORMAT(a.data_limite_beneficio,'%d/%m/%Y'), NULL) AS data_limite_beneficio_de,
                             IF(a.data_retorno, DATE_FORMAT(a.data_retorno,'%d/%m/%Y'), NULL) AS data_retorno_de
                      FROM usuarios_afastamento a
                      INNER JOIN usuarios b
                                 ON b.id = a.id_usuario
                                 AND b.empresa = a.id_empresa
                      LEFT JOIN empresa_departamentos c ON c.id = b.id_depto OR c.nome = b.depto
                      LEFT JOIN empresa_areas d ON d.id = b.id_area OR d.nome = b.area
                      LEFT JOIN empresa_setores e ON e.id = b.id_setor OR e.nome = b.setor
                      WHERE b.empresa = {$this->session->userdata('empresa')}";
		if (!empty($post['status'])) {
			$sql .= ' AND b.status IN (6, 7, 8, 9)';
		}
		if (!empty($post['status2'])) {
			$sql .= ' AND a.data_retorno IS NULL';
		}
		if (!empty($post['depto'])) {
			$sql .= " AND c.id = '{$post['depto']}'";
		}
		if (!empty($post['area'])) {
			$sql .= " AND d.id = '{$post['area']}'";
		}
		if (!empty($post['setor'])) {
			$sql .= " AND e.id = '{$post['setor']}'";
		}
		if (!empty($post['motivo_afastamento'])) {
			$sql .= " AND a.motivo_afastamento = '{$post['motivo_afastamento']}'";
		}
		$sql .= ' ORDER BY b.nome ASC) s';
		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array(
			's.id',
			's.nome',
			's.data_afastamento_de',
			's.motivo_afastamento',
			's.data_pericia_medica_de',
			's.data_limite_beneficio_de',
			's.data_retorno_de',
			's.matricula'
		);
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
		foreach ($list as $afastamento) {
			$row = array();
			$row[] = $afastamento->nome;
			$row[] = $afastamento->data_afastamento_de;
			$row[] = $afastamento->motivo_afastamento;
			$row[] = $afastamento->data_pericia_medica_de;
			$row[] = $afastamento->data_limite_beneficio_de;
			$row[] = $afastamento->data_retorno_de;
			$row[] = '
                      <a class="btn btn-success btn-sm"
                               href="' . site_url('funcionario/editar/' . $afastamento->id) . '"
                               title="Prontuário de colaborador">
                                <i class="glyphicon glyphicon-plus"></i> Prontuário
                            </a>
                            <button class="btn btn-danger btn-sm" 
                            onclick="delete_prontuario(' . $afastamento->id . ')"
                            title="Excluir prontuário">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
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

	// -------------------------------------------------------------------------

	/**
	 * Limpa os afastamentos de um usuário
	 *
	 * @access public
	 */
	public function limpar()
	{
		$id_usuario = $this->input->post('id_usuario');
		$this->afastamento->where('id_usuario', $id_usuario)->delete() or exit(json_encode(['retorno' => 0, 'aviso' => $this->afastamento->errors()]));

		echo json_encode(['status' => true]);
	}

	// -------------------------------------------------------------------------

	/**
	 * Gera o pdf do relatório
	 *
	 * @access public
	 * @uses ..\libraries\mpdf.php
	 */
	public function pdf()
	{
		$this->load->library('m_pdf');

		$stylesheet = 'table.afastamento tr { border-width: 3px; border-color: #ddd; } ';

		$stylesheet .= 'table.funcionarios tr th, table.funcionarios tr td { font-size: 11px; padding: 5px; } ';
		$stylesheet .= 'table.funcionarios thead tr th { background-color: #f5f5f5; } ';
		$stylesheet .= 'table.funcionarios thead tr th.text-center { width: auto; } ';
		$stylesheet .= 'table.funcionarios tbody tr th { background-color: #dff0d8; } ';

		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->relatorio(true));


		$this->m_pdf->pdf->Output("Relatório de Afastamentos.pdf", 'D');
	}

}
