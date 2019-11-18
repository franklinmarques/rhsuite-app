<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_questoes extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Avaliacaoexp_modelos_model', 'modelos');
	}

	// -------------------------------------------------------------------------

	public function index()
	{
		$this->gerenciar();
	}

	// -------------------------------------------------------------------------

	public function gerenciar($id = null)
	{
		if (empty($id)) {
			$id = $this->uri->rsegment(3);
		}

		$avaliacao = $this->modelos->find($id);

		$data = array(
			'id_usuario' => $this->session->userdata('id'),
			'empresa' => $this->session->userdata('empresa'),
			'id_modelo' => $avaliacao->id,
			'avaliacao' => $avaliacao->nome,
			'tipo' => $avaliacao->tipo,
			'id_avaliado' => '',
			'avaliado' => ''
		);

//        $this->db->select('id, nome');
//        $this->db->where('id', $this->uri->rsegment(4));
//        $row = $this->db->get('usuarios')->row();
//        if (count($row)) {
//            $data['id_avaliado'] = $row->id;
//            $data['avaliado'] = $row->nome;
//        }

		$this->load->view('avaliacaoexp_questoes', $data);
	}

	// -------------------------------------------------------------------------

	public function ajax_list($id)
	{
		if (empty($id)) {
			$id = $this->uri->rsegment(3);
		}

		$post = $this->input->post();

		$sql = "SELECT s.id, 
                       s.pergunta
                FROM (SELECT a.id,
                             a.pergunta
                      FROM avaliacaoexp_perguntas a
                      INNER JOIN avaliacaoexp_modelos b ON
                                 b.id = a.id_modelo
                      WHERE a.id_modelo = {$id} 
                      ORDER BY a.id) s";

		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array('s.id', 's.pergunta');

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

		foreach ($list as $avaliacaoExp) {
			$row = array();
			$row[] = $avaliacaoExp->pergunta;
			$row[] = '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_pergunta(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pergunta(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>';
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

	public function ajax_edit($id)
	{
		$this->db->where('id', $id);
		$data = $this->db->get('avaliacaoexp_perguntas')->row();

		echo json_encode($data);
	}

	// -------------------------------------------------------------------------

	public function ajax_add()
	{
		$data = array(
			'id_modelo' => $this->input->post('id_modelo'),
			'pergunta' => $this->input->post('pergunta')
		);

		if (empty($data['id_modelo'])) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de avaliação não foi encontrado')));
		}

		$status = $this->db->insert('avaliacaoexp_perguntas', $data);

		echo json_encode(array("status" => $status !== false));
	}

	// -------------------------------------------------------------------------

	public function ajax_update()
	{
		$data = array(
			'id_modelo' => $this->input->post('id_modelo'),
			'pergunta' => $this->input->post('pergunta')
		);

		if (empty($data['id_modelo'])) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de avaliação não foi encontrado')));
		}

		$status = $this->db->update('avaliacaoexp_perguntas', $data, array('id' => $this->input->post('id')));

		echo json_encode(array("status" => $status !== false));
	}

	// -------------------------------------------------------------------------

	public function ajax_delete($id)
	{
		$status = $this->db->delete('avaliacaoexp_perguntas', array('id' => $id));

		echo json_encode(array("status" => $status !== false));
	}

	// -------------------------------------------------------------------------

	public function ajax_respostaList($id)
	{
		if (empty($id)) {
			$id = $this->uri->rsegment(3);
		}

		$post = $this->input->post();

		$sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM avaliacaoexp_alternativas a
                INNER JOIN avaliacaoexp_modelos b ON
                           b.id = a.id_modelo
                WHERE a.id_modelo = {$id} AND 
                      a.id_pergunta IS NULL 
                GROUP by a.id";

		if (isset($post['order'])) {
			$orderBy = array();
			foreach ($post['order'] as $order) {
				$orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
			}
			$sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
		}
		$sql .= " 
                LIMIT 0, 6";

		$list = $this->db->query($sql)->result();

		$recordsTotal = count($list);

		$data = array();

		foreach ($list as $avaliacaoExp) {
			$data[] = array($avaliacaoExp->alternativa, $avaliacaoExp->peso);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsTotal,
			"data" => $data,
		);

		//output to json format
		echo json_encode($output);
	}

	// -------------------------------------------------------------------------

	public function ajax_respostaEdit($id)
	{
		$sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM avaliacaoexp_alternativas a
                INNER JOIN avaliacaoexp_modelos b ON
                           b.id = a.id_modelo
                WHERE b.id = {$id} AND 
                      a.id_pergunta IS NULL
                LIMIT 0, 6";

		$data = $this->db->query($sql)->result();

		echo json_encode($data);
	}

	// -------------------------------------------------------------------------

	public function ajax_respostaUpdate()
	{
		$id_modelo = $this->input->post('id_modelo');

		if (empty($id_modelo)) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de avaliação não foi encontrado')));
		}

		$id_alternativas = $this->input->post('id_alternativa');

		$alternativas = $this->input->post('alternativa');

		$peso = $this->input->post('peso');

		$this->db->trans_begin();

		foreach ($alternativas as $k => $alternativa) {

			$data = array(
				'id_modelo' => $id_modelo,
				'alternativa' => $alternativa,
				'peso' => $peso[$k]
			);

			if ($alternativa) {

				if ($id_alternativas[$k]) {

					$where = array(
						'id' => $id_alternativas[$k],
						'id_modelo' => $id_modelo
					);
					$query = $this->db->update_string('avaliacaoexp_alternativas', $data, $where);
				} else {
					$query = $this->db->insert_string('avaliacaoexp_alternativas', $data);
				}

				$this->db->query($query);
			} elseif ($id_alternativas[$k]) {

				$this->db->query("DELETE FROM avaliacaoexp_alternativas WHERE id = $id_alternativas[$k]");
			}
		}

		$this->db->trans_complete();

		$status = $this->db->trans_status();

		if ($status === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		echo json_encode(array("status" => $status !== false));
	}

}
