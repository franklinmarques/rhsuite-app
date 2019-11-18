<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_treinamentos extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ead_clientes_treinamentos_model', 'treinamento');
	}

	public function index()
	{
		$this->gerenciar();
	}


	public function gerenciar()
	{
		$data['empresa'] = $this->session->userdata('empresa');
		$data['idCliente'] = $this->uri->rsegment(3, '');
		$this->load->view('ead/clientes_treinamentos', $data);
	}


	public function avaliacao($isPdf = false)
	{
		$idCliente = $this->uri->rsegment(3, $this->input->get('id_cliente'));
		$idTreinamento = $this->uri->rsegment(4, $this->input->get('id'));

		$this->db->select('a.id, b.nome AS treinamento, c.id AS id_cliente, c.nome AS usuario, c.cliente', false);
		$this->db->select("DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio", false);
		$this->db->select("DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima", false);
		$this->db->select("DATE_FORMAT(MAX(d.data_finalizacao), '%d/%m/%Y') AS data_finalizacao", false);
		$this->db->select("TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(d.tempo_estudo))), '%H:%i:%s') AS tempo_estudo", false);
		$this->db->select('ROUND(AVG(e.nota)) AS avaliacao_final', false);
		$this->db->join('cursos b', 'b.id = a.id_curso');
		$this->db->join('cursos_clientes c', 'c.id = a.id_usuario');
		$this->db->join('cursos_clientes_acessos d', 'd.id_curso_usuario = a.id', 'left');
		$this->db->join('cursos_clientes_resultado e', 'e.id_acesso = d.id', 'left');
		$this->db->where('a.id', $idTreinamento);
		$this->db->where('c.id_empresa', $this->session->userdata('empresa'));
		$this->db->group_by('a.id');
		$data = $this->db->get('cursos_clientes_treinamentos a')->row();

		if (empty($data)) {
			redirect(site_url('ead/clientes_treinamentos/gerenciar/' . $idCliente));
		}


		$this->db->select('g.id, d.conteudo, g.nota, f.data_finalizacao');
		$this->db->select('IF(g.id_alternativa, e.alternativa, g.resposta) AS resposta', false);
		$this->db->join('cursos b', 'b.id = a.id_curso');
		$this->db->join('cursos_paginas c', 'c.id_curso = b.id');
		$this->db->join('cursos_questoes d', 'd.id_pagina = c.id');
		$this->db->join('cursos_alternativas e', 'e.id_questao = d.id', 'left');
		$this->db->join('cursos_clientes_acessos f', 'f.id_curso_usuario = a.id AND f.id_pagina = c.id', 'left');
		$this->db->join('cursos_clientes_resultado g', 'g.id_acesso = f.id AND g.id_questao = d.id AND (g.id_alternativa = e.id OR g.id_alternativa IS NULL)', 'left');
		$this->db->where('a.id', $idTreinamento);
		$this->db->group_by('d.id, e.id');
		$data->resultados = $this->db->get('cursos_clientes_treinamentos a')->result();

		$data->is_pdf = $isPdf === true;
		$data->query_string = 'id_cliente=' . $idCliente . '&id=' . $idTreinamento;

		if ($data->is_pdf) {
			$this->db->select('foto, foto_descricao');
			$this->db->where('id', $this->session->userdata('empresa'));
			$data->empresa = $this->db->get('usuarios')->row();

			return $this->load->view('ead/clientes_avaliacao_pdf', $data, true);
		}


		$this->load->view('ead/clientes_avaliacao', $data);
	}


	public function ajaxList()
	{
		$idCliente = $this->input->post('id_cliente');


		$this->db->select('IFNULL(c.nome, a.nome) AS nome', false);
		$this->db->select('a.data_inicio, a.data_maxima, ROUND(AVG(e.nota), 1) AS avaliacao_final, a.id', false);
		$this->db->select(["DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio_de"], false);
		$this->db->select(["DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima_de"], false);
		$this->db->join('cursos_clientes b', 'b.id = a.id_usuario');
		$this->db->join('cursos c', 'c.id = a.id_curso', 'left');
		$this->db->join('cursos_clientes_acessos d', 'd.id_curso_usuario = a.id', 'left');
		$this->db->join('cursos_clientes_resultado e', 'e.id_acesso = d.id', 'left');
		$this->db->where('b.id_empresa', $this->session->userdata('empresa'));
		if ($idCliente) {
			$this->db->where('a.id_usuario', $idCliente);
		}
		$this->db->group_by('a.id');
		$query = $this->db->get('cursos_clientes_treinamentos a');


		$this->load->library('dataTables');
		$output = $this->datatables->generate($query);

		$data = array();

		foreach ($output->data as $row) {
			$data[] = array(
				$row->nome,
				$row->data_inicio_de,
				$row->data_maxima_de,
				str_replace('.', ',', $row->avaliacao_final),
				'<button class="btn btn-sm btn-info" onclick="edit_treinamento(' . $row->id . ');" title="Editar treinamento"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_treinamento(' . $row->id . ');" title="Excluir treinamento"><i class="glyphicon glyphicon-trash"></i></button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('ead/clientes_treinamentos/avaliacao/' . $idCliente . '/' . $row->id) . '" title="Gerenciar avaliação do treinamento cliente"><i class="glyphicon glyphicon-list-alt"></i> Ver avaliação</a>'
			);
		}

		$output->data = $data;

		echo json_encode($output);
	}


	public function ajaxEdit()
	{
		$empresa = $this->session->userdata('empresa');
		$id_usuario = $this->input->post('id_usuario');
		$id = $this->input->post('id');

		$this->db->select('a.id, a.id_curso, a.nota_aprovacao, a.tipo_treinamento, a.local_treinamento');
		$this->db->select('a.nome_fornecedor, a.avaliacao_presencial');
		$this->db->select("IF(b.id IS NOT NULL, b.nome, a.nome) AS nome", false);
		$this->db->select("DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio", false);
		$this->db->select("DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima", false);
		$this->db->select("TIME_FORMAT(a.carga_horaria_presencial, '%H:%i') AS carga_horaria_presencial", false);
		$this->db->join('cursos_clientes c', 'c.id = a.id_usuario');
		$this->db->join('cursos b', 'b.id = a.id_curso', 'left');
		$this->db->where('a.id', $id);
		$this->db->where('c.id_empresa', $empresa);
		$this->db->group_by('a.id');
		$curso_usuario = $this->db->get('cursos_clientes_treinamentos a')->row();

		$id_curso = $curso_usuario->id_curso ?? '';
		$data['id'] = $curso_usuario->id ?? null;
		$data['nome'] = $curso_usuario->nome ?? null;
		$data['tipo_treinamento'] = $curso_usuario->tipo_treinamento ?? null;
		$data['local_treinamento'] = $curso_usuario->local_treinamento ?? null;
		$data['data_inicio'] = $curso_usuario->data_inicio ?? null;
		$data['data_maxima'] = $curso_usuario->data_maxima ?? null;
		$data['nota_aprovacao'] = $curso_usuario->nota_aprovacao ?? null;
		$data['carga_horaria_presencial'] = $curso_usuario->carga_horaria_presencial ?? null;
		$data['avaliacao_presencial'] = $curso_usuario->avaliacao_presencial ?? null;
		$data['nome_fornecedor'] = $curso_usuario->nome_fornecedor ?? null;

		$this->db->select('a.id, a.nome');
		$this->db->join('cursos_clientes_treinamentos b', "b.id_curso = a.id AND b.id_usuario = {$id_usuario}", 'left');
		$this->db->where('a.id_empresa =', $empresa);
		if ($id) {
			$this->db->where("(b.id IS NULL OR b.id_curso = {$id})");
		} else {
			$this->db->where('b.id', null);
		}
		if ($id_curso) {
			$this->db->or_where('a.id', $id_curso);
			$data['nome'] = '';
		} else {
			$data['nome'] = $curso_usuario->nome ?? null;
		}
		$this->db->group_by('a.id');
		$this->db->order_by('a.nome', 'ASC');
		$rows = $this->db->get('cursos a')->result();

		$options = array('' => 'selecione...');
		foreach ($rows as $row) {
			$options[$row->id] = $row->nome;
		}

		$data['cursos'] = form_dropdown('id_curso', $options, $id_curso, 'class="form-control"');
		echo json_encode($data);
	}


	public function ajaxAdd()
	{
		$this->load->library('entities');

		$data = $this->entities->create('eadClientesTreinamentos', $this->input->post());

		$this->treinamento->setValidationLabel('data_inicio', 'Data Início');
		$this->treinamento->setValidationLabel('data_termino', 'Data Término');

		$this->treinamento->insert($data) or exit(json_encode(['erro' => $this->treinamento->errors()]));

		echo json_encode(['status' => true]);
	}


	public function ajaxUpdate()
	{
		$this->load->library('entities');

		$data = $this->entities->create('eadClientesTreinamentos', $this->input->post());

		$this->treinamento->setValidationLabel('data_inicio', 'Data Início');
		$this->treinamento->setValidationLabel('data_termino', 'Data Término');

		$this->treinamento->update($data->id, $data) or exit(json_encode(['erro' => $this->treinamento->errors()]));

		echo json_encode(['status' => true]);
	}


	public function ajaxDelete()
	{
		$this->treinamento->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->treinamento->errors()]));

		echo json_encode(['status' => true]);
	}


	public function ajaxSaveAvaliacao()
	{
		$idCliente = $this->uri->rsegment(3);
		$idTreinamento = $this->uri->rsegment(4);
		$data = $this->input->post('nota');

		if (empty($data)) {
			exit(json_encode(['retorno' => 2, 'aviso' => 'Nenhum questionário finalizado encontrado.']));
		}

		$this->db->trans_start();

		foreach ($data as $id => $nota) {
			$this->db->set('nota', strlen($nota) ? $nota : null);
			$this->db->where('id', $id);
			$this->db->update('cursos_clientes_resultado');
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'Erro ao salvar a avaliação']));
		}

		echo json_encode(['retorno' => 1, 'aviso' => 'Avaliação realizada com sucesso', 'redireciona' => 1, 'pagina' => site_url('ead/clientes_treinamentos/avaliacao/' . $idCliente . '/' . $idTreinamento)]);
	}


	public function pdf()
	{
		$this->load->library('m_pdf');

		$stylesheet = 'table.avaliacao thead th { font-size: 14px; padding: 5px; text-align: center; font-weight: normal; } ';
		$stylesheet .= 'table.avaliacao tbody tr { border-width: 5px; border-color: #ddd; } ';
		$stylesheet .= 'table.avaliacao tbody tr.avaliador { border-width: 1px; border-color: #ddd; } ';
		$stylesheet .= 'table.avaliacao tbody td { font-size: 14px; padding: 5px; } ';
		$stylesheet .= 'table.avaliacao tbody tr.avaliador td { width: 50%; border-width: 1px; border-color: #ddd; } ';

		$stylesheet .= '#table thead th { font-size: 13px; padding: 5px; background-color: #f5f5f5; } ';
//        $stylesheet .= '#table thead tr:nth-child(1) th { background-color: #dff0d8; } ';
		$stylesheet .= '#table tbody td { font-size: 13px; padding: 5px; text-align: left; } ';
		$stylesheet .= '#table tbody td:nth-child(3) { text-align: right; }';


		$this->m_pdf->pdf->AddPage('L');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->avaliacao(true));

		$this->db->select("CONCAT(c.nome, ' - ', b.nome) AS nome", false);
		$this->db->join('cursos_clientes b', 'b.id = a.id_usuario');
		$this->db->join('cursos c', 'c.id = a.id_curso');
		$this->db->where('a.id', $this->input->get('id'));
		$row = $this->db->get('cursos_clientes_treinamentos a')->row();

		$this->m_pdf->pdf->Output("Avaliação de Treinamento de Clientes - {$row->nome}.pdf", 'D');
	}


}
