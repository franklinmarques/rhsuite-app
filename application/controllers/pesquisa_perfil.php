<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_perfil extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
//        $this->load->model('Pesquisa_model', 'pesquisa');
	}

	public function index()
	{
		$data = array(
			'empresa' => $this->session->userdata('empresa'),
			'tipo' => '',
			'nome' => 'Modelo de pesquisa'
		);

		$this->load->view('pesquisa_perfil', $data);
	}

	public function ajax_list()
	{
		$post = $this->input->post();

		$sql = "SELECT s.id, 
                       s.nome, 
                       s.cargo,
                       s.depto,
                       s.data_programada,
                       s.data_realizacao,
                       s.data_ok
                FROM (SELECT a.id, 
                             e.nome, 
                             CONCAT_WS('/', trim(e.cargo), trim(e.funcao)) AS cargo,
                             CONCAT_WS('/', trim(e.depto), trim(e.area), trim(e.setor)) AS depto,
                             c.data_inicio AS data_programada,
                             c.data_termino AS data_realizacao,
                             CASE WHEN NOW() BETWEEN c.data_inicio AND c.data_termino THEN 1 END AS data_ok,
                             (SELECT COUNT(p.id) 
                                FROM pesquisa_perguntas p 
                                INNER JOIN pesquisa_modelos m ON 
                                           m.id = p.id_modelo 
                                WHERE m.id = c.id_modelo) AS qtde_perguntas,
                             (SELECT COUNT(r.id_pergunta) 
                                FROM pesquisa_resultado r 
                                WHERE r.id_avaliador = a.id) AS qtde_respostas
                      FROM pesquisa_avaliadores a 
                      INNER JOIN pesquisa_avaliados b ON
                                 b.id = a.id_avaliado
                      INNER JOIN pesquisa c ON
                                 c.id = a.id_pesquisa
                      INNER JOIN pesquisa_modelos d ON 
                                 d.id = c.id_modelo AND 
                                 d.tipo = 'P'
                      INNER JOIN usuarios e ON 
                                 e.id = b.id_avaliado
                      WHERE a.id_avaliador = {$this->session->userdata('id')}) s 
                WHERE s.qtde_perguntas > 0 AND
                      s.qtde_respostas = 0";

		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array('s.id', 's.nome', 's.cargo', 's.depto', 's.data_programada', 's.data_realizacao');
		if ($post['search']['value']) {
			foreach ($columns as $key => $column) {
				if ($key > 1) {
					$sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
				} elseif ($key == 1) {
					$sql .= " 
                        AND ({$column} LIKE '%{$post['search']['value']}%'";
				}
			}
			$sql .= ')';
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
		if ($post['length'] > 0) {
			$sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
		}

		$list = $this->db->query($sql)->result();

		$data = array();
		$responderPesquisa = $this->agent->is_mobile() ? ' Resp.' : ' Responder pesquisa';
		foreach ($list as $pesquisa) {
			$row = array();
			$row[] = $pesquisa->nome;
			$row[] = $pesquisa->cargo;
			$row[] = $pesquisa->depto;
			if ($this->agent->is_mobile()) {
				$row[] = $pesquisa->data_programada ? date("d/m/y", strtotime(str_replace('-', '/', $pesquisa->data_programada))) : '';
				$row[] = $pesquisa->data_realizacao ? date("d/m/y", strtotime(str_replace('-', '/', $pesquisa->data_realizacao))) : '';
			} else {
				$row[] = $pesquisa->data_programada ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_programada))) : '';
				$row[] = $pesquisa->data_realizacao ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_realizacao))) : '';
			}

			if ($pesquisa->data_ok) {
				$row[] = '
                     	  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Responder pesquisa de Perfil Profissional" onclick="edit_pesquisa(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i>' . $responderPesquisa . '</a>
                     	 ';
			} else {
				$row[] = '
                     	  <button class="btn btn-sm btn-info disabled" title="Responder pesquisa de Perfil Profissional"><i class="glyphicon glyphicon-plus"></i>' . $responderPesquisa . '</button>
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

	public function ajax_edit($id)
	{
		$sql = "SELECT a.id_modelo,
                       a.nome,
                       b.instrucoes
                FROM pesquisa a
                INNER JOIN pesquisa_modelos b ON
                           b.id = a.id_modelo
                INNER JOIN pesquisa_avaliadores c ON
                           c.id_pesquisa = a.id
                WHERE c.id = {$id}";
		$avaliador = $this->db->query($sql)->row();

		$query = "SELECT a.id,
                         a.pergunta,
                         a.tipo_resposta,
                         a.valor_min,
                         a.valor_max
                  FROM pesquisa_perguntas a
                  INNER JOIN pesquisa_modelos b ON
                             b.id = a.id_modelo
                  WHERE b.id = {$avaliador->id_modelo} 
                  ORDER BY a.id ASC";
		$perguntas = $this->db->query($query)->result();

		$data = array();

		foreach ($perguntas as $p => $pergunta) {
			$select = "SELECT a.id,
                              a.id_pergunta,
                              a.alternativa,
                              a.peso
                       FROM pesquisa_alternativas a
                       LEFT JOIN pesquisa_perguntas b ON
                                 b.id_modelo = a.id_modelo AND
                                 (b.id = a.id_pergunta OR a.id_pergunta IS NULL)
                       WHERE b.id = {$pergunta->id} 
                       ORDER BY a.id ASC, 
                                a.peso ASC";

			$alternativas = $this->db->query($select)->result();

			$arrAlternativas = array();
			$arrQuestionario = array();

			foreach ($alternativas as $alternativa) {
				$arrAlternativas[$alternativa->id] = $alternativa;
				$arrQuestionario[] = "$alternativa->id";
			}

			$questionarios = $arrQuestionario ? implode(',', $arrQuestionario) : "''";

			$select2 = "SELECT b.id_alternativa, 
                               b.valor,
                               b.resposta
                        FROM pesquisa_avaliadores a
                        LEFT JOIN pesquisa_resultado b ON
                                  b.id_avaliador = a.id
                        INNER JOIN pesquisa_perguntas c ON
                                   c.id = b.id_pergunta
                        LEFT JOIN pesquisa_alternativas d ON
                                  d.id = b.id_alternativa AND 
                                  d.id in ({$questionarios})
                        WHERE c.id = {$pergunta->id} AND 
                              a.id = {$id}";

			if ($this->db->query($select2)->num_rows() == 0) {
				$select2 = "SELECT null AS id_alternativa, 
                                   null AS valor,
                                   null AS resposta
                            FROM dual";
			}
			$rows = $this->db->query($select2)->result();

			$resultado = array();
			foreach ($rows as $row) {
				$resultado[$row->id_alternativa + 0] = $row;
			}

//            $respostas = '';
//            if ($pergunta->tipo_resposta != 'M') {
			$respostas = form_hidden("id_pergunta[$p]", $pergunta->id);
//            }

			switch ($pergunta->tipo_resposta) {
				case 'A':
					$respostas .= form_textarea(array(
						'name' => "resposta[$p]",
						'class' => 'form-control',
						'value' => $resultado[0]->resposta,
						'rows' => '1'
					));
					break;
				case 'N':
					$respostas .= form_input(array(
						'name' => "valor[$p]",
						'type' => 'number',
						'class' => 'form-control text-right',
						'value' => $resultado[0]->valor,
						'min' => $pergunta->valor_min,
						'max' => $pergunta->valor_max,
						'style' => 'width: 30%;'
					));
					break;
				case 'U':
					foreach ($arrAlternativas as $k => $arrAlternativa) {
						$respostas .= '<div><label>';
						$respostas .= form_radio("id_alternativa[$p]", $k, isset($resultado[$k]->id_alternativa));
						$respostas .= ' ' . $arrAlternativa->alternativa . " </label></div>";
					}
					break;
				case 'M':
					foreach ($arrAlternativas as $k => $arrAlternativa) {
						$respostas .= '<div><label>';
						$respostas .= form_checkbox("id_alternativa[$p][]", $k, isset($resultado[$k]->id_alternativa));
						$respostas .= ' ' . $arrAlternativa->alternativa . " </label></div>";
					}
					break;
				case 'V':
					foreach ($arrAlternativas as $k => $arrAlternativa) {
						$respostas .= form_hidden("id_alternativa[$p][]", $k);
						$respostas .= '<p><div>' . $arrAlternativa->alternativa . " </div>";
						$respostas .= '<div class="col-sm-offset-1">';
						if (strlen($arrAlternativa->peso)) {
							$respostas .= '<label class="radio-inline">';
							$respostas .= form_radio("valor[$p][$k]", 1, isset($resultado[$k]->valor) ? $resultado[$k]->valor == 1 : '');
							$respostas .= 'Verdadeiro</label><label class="radio-inline">';
							$respostas .= form_radio("valor[$p][$k]", 0, isset($resultado[$k]->valor) ? $resultado[$k]->valor == 0 : '');
							$respostas .= 'Falso</label>';
						} else {
							$respostas .= form_input(array(
								'name' => "resposta[$p][$k]",
								'class' => 'form-control input-sm',
								'value' => isset($resultado[$k]->resposta) ? $resultado[$k]->resposta : '',
								'rows' => '1',
								'style' => 'width: 100%;'
							));
						}
						$respostas .= '</div></p>';
					}
			}

			$data[] = array($pergunta->pergunta, $respostas);
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => count($data),
			"recordsFiltered" => count($data),
			"data" => $data,
			'title' => $avaliador->nome,
			'instrucoes' => $avaliador->instrucoes
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_save()
	{
		$avaliador = $this->input->post('id_avaliador');
		$id_perguntas = $this->input->post('id_pergunta');
		$id_alternativa = $this->input->post('id_alternativa');
		$arrValor = $this->input->post('valor');
		$arrResposta = $this->input->post('resposta');

		$this->db->trans_begin();

		foreach ($id_perguntas as $k => $pergunta) {

			$alternativas = isset($id_alternativa[$k]) ? $id_alternativa[$k] : null;
			$valor = isset($arrValor[$k]) ? $arrValor[$k] : null;
			$resposta = isset($arrResposta[$k]) ? $arrResposta[$k] : null;

			$rows = array();
			$where = array();

			if ($alternativas) {
				if (is_string($alternativas)) {
					$alternativas = array($alternativas);
				}
				$strAlternativas = implode(',', $alternativas);

				$delete = "DELETE FROM pesquisa_resultado 
                           WHERE id_avaliador = {$avaliador} AND 
                                 id_pergunta = {$pergunta} AND NOT
                                 (id_alternativa IN ({$strAlternativas}))";
				$this->db->query($delete);

				$sql = "SELECT id_alternativa
                        FROM pesquisa_resultado 
                        WHERE id_avaliador = {$avaliador} AND 
                              id_pergunta = {$pergunta} AND
                              id_alternativa IN ({$strAlternativas})";
				$select = $this->db->query($sql)->result();
				$updateWhere = array();
				foreach ($select as $column) {
					$updateWhere[] = $column->id_alternativa;
				}

				foreach ($alternativas as $a => $alternativa) {
					$rows[$a] = array(
						'id_alternativa' => $alternativa,
						'valor' => isset($valor[$alternativa]) and is_array($valor) ? $valor[$alternativa] : $valor,
						'resposta' => isset($resposta[$alternativa]) and is_array($resposta) ? $resposta[$alternativa] : $resposta
					);
					if (in_array($alternativa, $updateWhere)) {
						$where[$a] = array(
							'id_avaliador' => $avaliador,
							'id_pergunta' => $pergunta,
							'id_alternativa' => $alternativa
						);
					}
				}
			} else {
				if (strlen($valor) > 0 or strlen($resposta) > 0) {
					$rows[] = array(
						'id_alternativa' => null,
						'valor' => $valor,
						'resposta' => $resposta
					);

					$sql = "SELECT id_alternativa
                            FROM pesquisa_resultado 
                            WHERE id_avaliador = {$avaliador} AND 
                                  id_pergunta = {$pergunta}";
					if ($this->db->query($sql)->num_rows()) {
						$where[] = array(
							'id_avaliador' => $avaliador,
							'id_pergunta' => $pergunta
						);
					}
				} else {
					$delete = "DELETE FROM pesquisa_resultado 
                               WHERE id_avaliador = {$avaliador} AND 
                                     id_pergunta = {$pergunta}";
					$this->db->query($delete);
				}
			}

			foreach ($rows as $k => $row) {
				$data = $row;
				$data['data_avaliacao'] = date('Y-m-d H:i:s');
				if (isset($where[$k])) {
					$query = $this->db->update_string('pesquisa_resultado', $data, $where[$k]);
				} else {
					$data['id_avaliador'] = $avaliador;
					$data['id_pergunta'] = $pergunta;
					$query = $this->db->insert_string('pesquisa_resultado', $data);
				}
				$this->db->query($query);

				if ($this->db->trans_status() == false) {
					break;
				}
			}
		}

		$status = $this->db->trans_status();

		if ($status === FALSE) {
			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();
		}

		echo json_encode(array("status" => $status !== false));
	}

}
