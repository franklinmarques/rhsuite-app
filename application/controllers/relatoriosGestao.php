<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RelatoriosGestao extends MY_Controller
{

	public function index()
	{
		$this->db->select('id AS usuario, id_depto, id_area, id_setor');
		$this->db->where('id', $this->session->userdata('id'));
		$data = $this->db->get('usuarios')->row();
		$data->empresa = $this->session->userdata('empresa');


		if ($this->session->userdata('tipo') == 'funcionario') {
			$estrutura = $this->getEstrutura($data->id_depto, $data->id_area, $data->id_setor, $data->usuario);
		} else {
			$estrutura = $this->getEstrutura($data->id_depto, $data->id_area, $data->id_setor);
		}

		$data->deptos = $estrutura['depto'];
		$data->areas = $estrutura['area'];
		$data->setores = $estrutura['setor'];
		$data->usuarios = $estrutura['usuario'];


		$this->load->view('relatorios_gestao', $data);
	}


	public function atualizarFiltro()
	{
		$idDepto = $this->input->get('id_depto');
		$idArea = $this->input->get('id_area');
		$idSetor = $this->input->get('id_setor');
		$idUsuario = $this->input->get('id_usuario');

		$estrutura = $this->getEstrutura($idDepto, $idArea, $idSetor, $idUsuario);

		$data['area'] = form_dropdown('', $estrutura['area'], $idArea);
		$data['setor'] = form_dropdown('', $estrutura['setor'], $idSetor);
		$data['usuario'] = form_dropdown('', $estrutura['usuario'], $idUsuario);

		echo json_encode($data);
	}


	private function getEstrutura($idDepto = '', $idArea = '', $idSetor = '', $idUsuario = null)
	{
		if ($idUsuario) {
			$this->db->where('id', $idDepto);
		} else {
			$this->db->where('id_empresa', $this->session->userdata('empresa'));
		}
		$this->db->order_by('nome', 'asc');
		$deptos = $this->db->get('empresa_departamentos')->result();


		if ($idUsuario) {
			$this->db->where('id', $idArea);
		} else {
			$this->db->where('id_departamento', $idDepto);
		}
		$this->db->order_by('nome', 'asc');
		$areas = $this->db->get('empresa_areas')->result();


		if ($idUsuario) {
			$this->db->where('id', $idSetor);
		} else {
			$this->db->where('id_area', $idArea);
		}
		$this->db->order_by('nome', 'asc');
		$setores = $this->db->get('empresa_setores')->result();


		$this->db->where('tipo', 'funcionario');
		$this->db->where('status', 1);
		if ($idUsuario) {
			$this->db->where('id', $idUsuario);
		} else {
			$this->db->where('empresa', $this->session->userdata('empresa'));
			if ($idDepto) {
				$this->db->where('id_depto', $idDepto);
			}
			if ($idArea) {
				$this->db->where('id_area', $idArea);
			}
			if ($idSetor) {
				$this->db->where('id_setor', $idSetor);
			}
		}
		$this->db->order_by('nome', 'asc');
		$usuarios = $this->db->get('usuarios')->result();


		if ($idUsuario) {
			$data = array(
				'depto' => array_column($deptos, 'nome', 'id'),
				'area' => array_column($areas, 'nome', 'id'),
				'setor' => array_column($setores, 'nome', 'id'),
				'usuario' => array_column($usuarios, 'nome', 'id')
			);
		} else {
			$data = array(
				'depto' => ['' => 'Todos'] + array_column($deptos, 'nome', 'id'),
				'area' => ['' => 'Todas'] + array_column($areas, 'nome', 'id'),
				'setor' => ['' => 'Todos'] + array_column($setores, 'nome', 'id'),
				'usuario' => ['' => 'Todos'] + array_column($usuarios, 'nome', 'id')
			);
		}


		return $data;
	}


	public function ajaxList()
	{
		parse_str($this->input->post('busca'), $busca);


		$this->db->select('a.mes_referencia, a.ano_referencia, b.nome AS depto');
		$this->db->select("(CASE a.status WHEN 'M' THEN 'Em elaboração' WHEN 'E' THEN 'Elaborado'  WHEN 'A' THEN 'Em análise'  WHEN 'P' THEN 'Pendências'  WHEN 'C' THEN 'Aceito' END) AS descricao_status");
		$this->db->select('a.parecer_final, a.status, a.id');
		$this->db->join('empresa_departamentos b', 'b.id = a.id_depto', 'left');
		$this->db->where('a.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('a.id_usuario', $this->session->userdata('id'));
		foreach ($busca as $field => $value) {
			if (!empty($value)) {
				$this->db->where('a.' . $field, $value);
			}
		}
		$query = $this->db->get('relatorios_gestao a');


		$this->load->library('dataTables');

		$output = $this->datatables->generate($query);

		$data = array();
		foreach ($output->data as $row) {
			$data[] = array(
				$row->mes_referencia,
				$row->ano_referencia,
				$row->depto,
				$row->descricao_status,
				$row->parecer_final,
				'<button class="btn btn-sm btn-info" title="Editar" onclick="edit_relatorio(' . $row->id . ')"><i class="fa fa-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_relatorio(' . $row->id . ')"><i class="fa fa-trash"></i></button>
                 <button class="btn btn-sm btn-info" title="Imprimir" onclick="visualizar(' . $row->id . ')"><i class="fa fa-print"></i> Imprimir</button>',
				$row->status
			);
		}

		$output->data = $data;

		echo json_encode($output);
	}


	public function ajaxEdit()
	{
		$this->db->where('id', $this->input->get('id'));
		$data = $this->db->get('relatorios_gestao')->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'Relatório não encontrado ou excluído recentemente.']));
		}

		if ($data->data_fechamento) {
			$data->data_fechamento = date('d/m/Y', strtotime($data->data_fechamento));
		}

		echo json_encode($data);
	}


	private function validar($data)
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('mes_referencia', '"Mês de referência"', 'required|is_natural_no_zero|less_than_equal_to[12]');
		$this->form_validation->set_rules('ano_referencia', '"Ano de referência"', 'required|numeric|max_length[4]');
		$this->form_validation->set_rules('data_fechamento', '"Data de fechamento"', 'required|valid_date');
		$this->form_validation->set_rules('status', '"Status"', 'required|exact_length[1]');

		if ($this->form_validation->run() == false) {
			exit(json_encode(['erro' => str_replace(['<p>', '</p>'], '', $this->form_validation->error_string())]));
		}


		if (strlen($data['data_fechamento']) > 0) {
			$data['data_fechamento'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_fechamento'])));
		}

		foreach ($data as &$field) {
			if (strlen($field) == 0) {
				$field = null;
			}
		}

		return $data;
	}


	public function ajaxAdd()
	{
		$data = $this->validar($this->input->post());

		if (!$this->db->insert('relatorios_gestao', $data)) {
			exit(json_encode(['erro' => 'Não foi possível cadastrar o relatório.']));
		}

		echo json_encode(['status' => true]);
	}


	public function ajaxUpdate()
	{
		$data = $this->validar($this->input->post());

		$id = $data['id'];
		unset($data['id']);

		if (!$this->db->update('relatorios_gestao', $data, ['id' => $id])) {
			exit(json_encode(['erro' => 'Não foi possível cadastrar o relatório.']));
		}

		echo json_encode(['status' => true]);
	}


	public function ajaxDelete()
	{
		$this->db->delete('relatorios_gestao', ['id' > $this->input->post('id')]);

		echo json_encode(['status' => true]);
	}


	public function visualizar()
	{
		$data = $this->gerarPDF();

		echo $data;
	}


	private function gerarPDF($isPdf = false)
	{

		$this->db->query("SET lc_time_names = 'pt_BR'");
		$this->db->select('a.id, b.nome AS usuario, c.nome AS depto, d.nome AS area, e.nome AS setor');
		$this->db->select('a.mes_referencia, a.ano_referencia');
		$this->db->select('a.indicadores, a.riscos_oportunidades');
		$this->db->select('a.ocorrencias, a.necessidades_investimentos');
		$this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
		$this->db->join('empresa_departamentos c', 'c.id = a.id_depto', 'left');
		$this->db->join('empresa_areas d', 'b.id = a.id_area', 'left');
		$this->db->join('empresa_setores e', 'b.id = a.id_setor', 'left');
		$this->db->where('a.id', $this->input->get('id'));
		$data = $this->db->get('relatorios_gestao a')->row();

		if (empty($data)) {
			exit(json_encode(['erro' => 'Relatório não encontrado ou excluído recentemente.']));
		}

		$this->db->select('foto, foto_descricao');
		$this->db->where('id', $this->session->userdata('empresa'));
		$empresa = $this->db->get('usuarios')->row();

		$data->foto = 'imagens/usuarios/' . $empresa->foto;
		$data->foto_descricao = 'imagens/usuarios/' . $empresa->foto_descricao;
		$data->is_pdf = $isPdf;

		$this->load->library('calendar');
		$data->mes_referencia = ucfirst($this->calendar->get_month_name($data->mes_referencia));

		return $this->load->view('relatorios_gestao_pdf', $data, true);
	}


	public function pdf()
	{
		$this->load->library('m_pdf');

		$stylesheet = 'table.gestao thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
		$stylesheet .= 'table.gestao tbody tr { border-width: 5px; border-color: #ddd; } ';
		$stylesheet .= 'table.gestao tbody tr th { font-size: 11px; padding: 2px; } ';
		$stylesheet .= 'table.gestao tbody td { font-size: 12px; padding: 1px; border-top: 1px solid #ddd;} ';
		$stylesheet .= 'table.gestao tbody td strong { font-weight: bold; } ';

		$stylesheet .= 'table.dados thead th { font-size: 12px; padding: 5px; border-bottom: 2px solid #ddd; } ';
		$stylesheet .= 'table.dados thead tr.active td, table.dados tbody tr th.active { background-color: #e5e5e5; }';
		$stylesheet .= 'table.dados tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd; word-wrap: break-word;} ';

		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->gerarPDF(true));

		$this->db->select('parecer_final');
		$this->db->where('id', $this->input->get('id'));
		$row = $this->db->get('relatorios_gestao')->row();

		$parecer = !empty($row->parecer_final) ? ' - ' . $row->parecer_final : '';

		$this->m_pdf->pdf->Output('Relatório de Gestão' . $parecer . '.pdf', 'D');
	}


}
