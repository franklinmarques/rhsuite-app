<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cargos extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['id_usuario'] = $this->session->userdata('id');
		$data['cargo'] = $this->getCargos();
		$data['funcao'] = $this->getFuncoes();

		$this->load->view('competencias/cargos', $data);
	}

	private function getCargos()
	{
		$this->db->select('DISTINCT(cargo) AS cargo');
		if ($this->session->userdata('tipo') != 'administrador') {
			$this->db->where('empresa', $this->session->userdata('empresa'));
		}
		$rows = $this->db->where('CHAR_LENGTH(cargo) > 0')->order_by('cargo', 'asc')->get('usuarios')->result();

		$data = ['' => 'selecione...'] + array_column($rows, 'cargo', 'cargo');

		return $data;
	}

	private function getFuncoes($cargo = null)
	{
		$this->db->select('DISTINCT(funcao), cargo');
		if ($this->session->userdata('tipo') != 'administrador') {
			$this->db->where('empresa', $this->session->userdata('empresa'));
		}
		if ($cargo) {
			$this->db->where('cargo', $cargo);
		}
		$rows = $this->db->where('CHAR_LENGTH(funcao) > 0')->order_by('funcao', 'asc')->get('usuarios')->result();

		$data = ['' => 'selecione...'] + array_column($rows, 'funcao', 'funcao');
		
		return $data;
	}

	public function ajax_list($id)
	{
		$post = $this->input->post();

		$NCTf = "round(
                    (select sum(round(( CAST(b.peso AS DECIMAL) / 100 ) * 
                        (select sum(( ( CAST(c.peso AS DECIMAL) / 100) * c.nivel ) * ( c.atitude / 100)) as rowdc 
                        from cargos_dimensao c 
                        where c.cargo_competencia = b.id), 3)) as rowSomarCompetencias
                    from cargos_competencias b 
                    where b.tipo_competencia = 'T' and b.id_cargo = a.id), 3)";

		$NCCf = "round(
                    (select sum(round(( CAST(e.peso AS DECIMAL) / 100 ) * 
                        (select sum(( ( CAST(f.peso AS DECIMAL) / 100) * f.nivel ) * ( f.atitude / 100)) as rowdc 
                        from cargos_dimensao f 
                        where f.cargo_competencia = e.id), 3)) as rowSomarCompetencias
                    from cargos_competencias e 
                    where e.tipo_competencia = 'C' and e.id_cargo = a.id), 3)";

		$IDcf = "round((({$NCTf} * (a.peso_competencias_tecnicas / 100)) + ({$NCCf} * (a.peso_competencias_comportamentais / 100))), 3)";

		$sql = "SELECT s.id, 
                       s.nome, 
                       s.NCTf,
                       s.peso_competencias_tecnicas, 
                       s.NCCf,
                       s.peso_competencias_comportamentais,
                       s.IDcf
                FROM (SELECT a.id, 
                             CONCAT_WS('/', trim(a.cargo), trim(a.funcao)) AS nome, 
                             {$NCTf} as NCTf,
                             a.peso_competencias_tecnicas, 
                             {$NCCf} as NCCf,
                             a.peso_competencias_comportamentais,
                             {$IDcf} as IDcf 
                      FROM cargos a
                      WHERE a.id_usuario_EMPRESA = {$id}) s";
		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array(
			's.id',
			's.nome',
			's.NCTf',
			's.peso_competencias_tecnicas',
			's.NCCf',
			's.peso_competencias_comportamentais',
			's.IDcf'
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
		foreach ($list as $cargos) {
			$sql = "SELECT COUNT(b.id) as ct, COUNT(c.id) as cc
                    FROM cargos_competencias a 
                    LEFT JOIN cargos_dimensao b ON 
                              b.cargo_competencia = a.id AND 
                              a.tipo_competencia = 'T'
                    LEFT JOIN cargos_dimensao c ON 
                              c.cargo_competencia = a.id AND 
                              a.tipo_competencia = 'C'
                    WHERE a.id_cargo = " . $cargos->id;
			$btn = $this->db->query($sql)->row();
			$disabled = $btn->ct && $btn->cc ? '' : ' disabled';
			$row = array();
			$row[] = $cargos->nome;
			$row[] = str_replace('.', ',', round($cargos->NCTf, 3));
			$row[] = intval($cargos->peso_competencias_tecnicas);
			$row[] = str_replace('.', ',', round($cargos->NCCf, 3));
			$row[] = intval($cargos->peso_competencias_comportamentais);
			$row[] = str_replace('.', ',', round($cargos->IDcf, 3));

			$row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_cargo(' . "'" . $cargos->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_cargo(' . "'" . $cargos->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('competencias/tipo/tecnica/' . $cargos->id) . '" title="Competências técnicas" >CT</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('competencias/tipo/comportamental/' . $cargos->id) . '" title="Competências comportamentais" >CC</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('competencias/cargos/mapeamento/' . $cargos->id) . '" title="Visualizar mapeamento de competências"><i class="glyphicon glyphicon-list-alt"></i> Visualizar</a>
                      ';

			$data[] = $row;
		}
		$output = array(
			'draw' => $this->input->post('draw'),
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_funcoes()
	{
		$cargo = $this->input->post('cargo');
		$options = $this->getFuncoes($cargo);
		$funcao = form_dropdown('funcao', $options, '', 'id="funcao" class="form-control"');

		echo json_encode(array('funcao' => $funcao));
	}

	public function ajax_edit()
	{
		$id = $this->input->post('id');
		$data = $this->db->get_where('cargos', array('id' => $id))->row();
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$data = $this->input->post();
		if ($data['peso_competencias_tecnicas'] + $data['peso_competencias_comportamentais'] > 100) {
			exit('A soma dos pesos deve ser igual a 100');
		}
		$this->db->insert('cargos', $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$data = $this->input->post();
		if ($data['peso_competencias_tecnicas'] + $data['peso_competencias_comportamentais'] > 100) {
			exit('A soma dos pesos deve ser igual a 100');
		}
		$this->db->update('cargos', $data, array('id' => $data['id']));

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete()
	{
		$id = $this->input->post('id');
		$this->db->delete('cargos', array('id' => $id));

		echo json_encode(array("status" => TRUE));
	}

	public function mapeamento($id, $pdf = false)
	{
		$this->db->select('foto, foto_descricao');
		$this->db->where('id', $this->session->userdata('empresa'));
		$data['empresa'] = $this->db->get('usuarios')->row();

		$NCTf = "round(
                    (select sum(round(( CAST(b.peso AS DECIMAL) / 100 ) * 
                        (select sum(( ( CAST(c.peso AS DECIMAL) / 100) * c.nivel ) * ( c.atitude / 100)) as rowdc 
                        from cargos_dimensao c 
                        where c.cargo_competencia = b.id), 3)) as rowSomarCompetencias
                    from cargos_competencias b 
                    where b.tipo_competencia = 'T' and b.id_cargo = a.id), 3)";

		$NCCf = "round(
                    (select sum(round(( CAST(e.peso AS DECIMAL) / 100 ) * 
                        (select sum(( ( CAST(f.peso AS DECIMAL) / 100) * f.nivel ) * ( f.atitude / 100)) as rowdc 
                        from cargos_dimensao f 
                        where f.cargo_competencia = e.id), 3)) as rowSomarCompetencias
                    from cargos_competencias e 
                    where e.tipo_competencia = 'C' and e.id_cargo = a.id), 3)";

		$IDcf = "round((({$NCTf} * ( a.peso_competencias_tecnicas / 100 )) + ({$NCCf} * (a.peso_competencias_comportamentais / 100 ))), 3)";

		$sql = "SELECT a.id, 
                       CONCAT_WS('/', a.cargo, a.funcao) AS cargo_funcao, 
                       {$NCTf} as NCTf,
                       a.peso_competencias_tecnicas, 
                       {$NCCf} as NCCf,
                       a.peso_competencias_comportamentais,
                       {$IDcf} as IDcf 
                FROM cargos a 
                WHERE a.id = {$id}";
		$cargo = $this->db->query($sql)->row();

		$data['cargo_funcao'] = $cargo->cargo_funcao;
		$data['nctf'] = round($cargo->NCTf, 3);
		$data['peso_ct'] = intval($cargo->peso_competencias_tecnicas);
		$data['nccf'] = round($cargo->NCCf, 3);
		$data['peso_cc'] = intval($cargo->peso_competencias_comportamentais);
		$data['idcf'] = round($cargo->IDcf, 3);

		$data['data_atual'] = date('d/m/Y');
		$data['is_pdf'] = $pdf;

		$sql2 = "SELECT a.id, 
                        a.nome, 
                        a.tipo_competencia 
                 FROM cargos_competencias a 
                 INNER JOIN cargos b ON 
                            b.id = a.id_cargo 
                 WHERE a.id_cargo = {$cargo->id}";
		$rows = $this->db->query($sql2)->result();
		$data['ct'] = array();
		$data['cc'] = array();

		foreach ($rows as $row) {
			$sql = "SELECT a.nome, 
                           a.peso, 
                           a.nivel, 
                           a.atitude, 
                           round((((CAST(a.peso AS DECIMAL) / 100) * a.nivel) * (a.atitude / 100)), 3) AS indice 
                    FROM cargos_dimensao a 
                    INNER JOIN cargos_competencias b ON 
                               b.id = a.cargo_competencia 
                    WHERE a.cargo_competencia = {$row->id}";
			$dimensao = $this->db->query($sql)->result();

			if ($row->tipo_competencia == 'T') {
				$data['ct'][] = array('nome' => $row->nome, 'dimensao' => $dimensao);
			} elseif ($row->tipo_competencia == 'C') {
				$data['cc'][] = array('nome' => $row->nome, 'dimensao' => $dimensao);
			}
		}

		if ($pdf) {
			return $this->load->view('competencias/pdf_mapeamento', $data, true);
		} else {
			$this->load->view('competencias/mapeamento', $data);
		}
	}

	public function pdfMapeamento($id)
	{
		$this->load->library('m_pdf');

		$stylesheet = 'table.cargo thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
		$stylesheet .= 'table.cargo tbody tr { border-width: 5px; border-color: #ddd; } ';
		$stylesheet .= 'table.cargo tbody td { font-size: 12px; padding: 5px; } ';
		$stylesheet .= 'table.cargo tbody td div { background-color: #d9edf7; } ';

		$stylesheet .= 'table.competencias thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5; } ';
		$stylesheet .= 'table.competencias thead tr:nth-child(1) th { background-color: #dff0d8; } ';
		$stylesheet .= 'table.competencias tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
		$stylesheet .= 'table.competencias tbody td:nth-child(1) { text-align: left; }';

		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->mapeamento($id, true));

		$this->db->select("CONCAT_WS('_-_', cargo, funcao) AS cargo_funcao", false);
		$cargo = $this->db->get_where('cargos', array('id' => $id))->row();

		$this->m_pdf->pdf->Output("MC - {$cargo->cargo_funcao}.pdf", 'D');
	}

}
