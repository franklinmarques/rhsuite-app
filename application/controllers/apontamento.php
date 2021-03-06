<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{

	private function getAlocacao($busca, $consolidado = '')
	{
		$this->db->select('id, data, mes_bloqueado, NULL as id_anterior, NULL AS data_abertura', false);
		$this->db->select("NULLIF(dia_fechamento, 0) AS dia_fechamento", false);
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$this->db->where('depto', $busca['depto']);
		$this->db->where('area', $busca['area']);
		$this->db->where('setor', $busca['setor']);
		$this->db->where('MONTH(data)', $busca['mes']);
		$this->db->where('YEAR(data)', $busca['ano']);
		$alocacao = $this->db->get('alocacao')->row();

		if (empty($alocacao)) {
			$alocacao = new stdClass();
			$alocacao->id = null;
			$alocacao->mes_bloqueado = null;
			$alocacao->dia_fechamento = null;
			$alocacao->id_anterior = null;
		}

		if ($consolidado and $alocacao->dia_fechamento) {
			$sql = "SELECT DATE_ADD(DATE_SUB(STR_TO_DATE('{$busca['ano']}-{$busca['mes']}-{$alocacao->dia_fechamento}', '%Y-%m-%d'), INTERVAL 1 MONTH), INTERVAL 1 DAY) AS mes_ano";
			$alocacao->mes_ano = $this->db->query($sql)->row()->mes_ano;

			$this->db->select('id');
			$this->db->where("DATE_FORMAT(data, '%Y-%m') = DATE_FORMAT('{$alocacao->mes_ano}', '%Y-%m')", null, false);
			$this->db->where('depto', $busca['depto']);
			$this->db->where('area', $busca['area']);
			$this->db->where('setor', $busca['setor']);
			$alocacaoAnterior = $this->db->get('alocacao')->row();

//            $buscaAnterior = $busca;
//            $mesAnterior = strtotime('-1 month', strtotime($alocacao->data));
//            $buscaAnterior['ano'] = date('Y', $mesAnterior);
//            $buscaAnterior['mes'] = date('m', $mesAnterior);
//            $alocacaoAnterior = $this->getAlocacao($buscaAnterior);
			$alocacao->id_anterior = $alocacaoAnterior->id ?? null;

			$alocacao->data_abertura = date('Y-m-d', strtotime($alocacao->mes_ano));
			$alocacao->data_fechamento = date('Y-m-d', strtotime("{$busca['ano']}-{$busca['mes']}-{$alocacao->dia_fechamento}"));
		} else {
			$alocacao->mes_ano = date('Y-m-d', strtotime("{$busca['ano']}-{$busca['mes']}-01"));
			$alocacao->data_abertura = $alocacao->mes_ano;
			$alocacao->data_fechamento = date('Y-m-t', strtotime($alocacao->data_abertura));
		}

		return $alocacao;
	}

	public function ajax_list()
	{
		$post = $this->input->post();
		parse_str($this->input->post('busca'), $busca);


		$alocacao = $this->getAlocacao($busca, $post['consolidado']);


		$sql = "SELECT s.nome,
                       s.bck_sub, 
                       IFNULL(s.total_saldo, '') AS total_saldo, 
                       SUM(s.total_faltas) AS total_faltas, 
                       TIME_FORMAT(SEC_TO_TIME(SUM(s.total_atrasos)), '%H:%i') AS total_atrasos, 
                       s.id,
                       s.id_alocacao, 
                       s.id_usuario_bck, 
                       s.id_bck, 
                       s.nome_bck, 
                       s.id_usuario_sub, 
                       s.id_sub, 
                       s.nome_sub, 
                       s.data_recesso, 
                       s.data_retorno, 
                       s.data_desligamento, 
                       s.id_usuario, 
                       s.data, 
                       s.tipo_bck 
                FROM (SELECT a.id, 
                             a.id_alocacao, 
                             b1.nome, 
                             IFNULL(a.nome_bck, a.nome_sub) AS bck_sub,
                             TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(e.apontamento_saldo))), '%H:%i') AS total_saldo, 
                             SUM(CASE WHEN f.status IN ('FJ', 'FN', 'PD', 'PI') 
                                      THEN f.qtde_dias 
                                      END) AS total_faltas,
                             SUM(CASE WHEN f.status IN ('AJ', 'AN', 'SJ', 'SN') 
                                      THEN TIME_TO_SEC(f.hora_atraso) 
                                      END) AS total_atrasos,
                             a.id_usuario_bck, 
                             c1.id AS id_bck,
                             a.nome_bck, 
                             a.id_usuario_sub, 
                             d1.id AS id_sub, 
                             a.nome_sub, 
                             DATE_FORMAT(a.data_recesso, '%d/%m/%Y') AS data_recesso, 
                             DATE_FORMAT(a.data_retorno, '%d/%m/%Y') AS data_retorno,
                             DATE_FORMAT(a.data_desligamento, '%d/%m/%Y') AS data_desligamento, 
                             a.id_usuario, 
                             b.data,
                             a.tipo_bck
                      FROM alocacao_usuarios a
                      INNER JOIN alocacao b ON 
                                 b.id = a.id_alocacao
                      INNER JOIN usuarios b1 ON b1.id = a.id_usuario
                      LEFT JOIN usuarios c ON c.id = a.id_usuario_bck
                      LEFT JOIN (SELECT x.id, x.id_usuario, y.depto, y.area, y.setor, y.data 
                                 FROM alocacao_usuarios x 
                                 INNER JOIN alocacao y ON 
                                            y.id = x.id_alocacao) c1 ON 
                                c1.id_usuario = c.id AND
                                c1.depto = b.depto AND 
                                c1.area = b.area AND 
                                c1.setor = 'Backup' AND 
                                DATE_FORMAT(c1.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')
                      LEFT JOIN usuarios d ON d.id = a.id_usuario_sub
                      LEFT JOIN (SELECT x2.id, x2.id_usuario, y2.depto, y2.area, y2.setor, y2.data 
                                 FROM alocacao_usuarios x2 
                                 INNER JOIN alocacao y2 ON 
                                            y2.id = x2.id_alocacao) d1 ON 
                                d1.id_usuario = d.id AND
                                d1.depto = b.depto AND 
                                d1.area = b.area AND 
                                d1.setor = b.setor AND 
                                DATE_FORMAT(d1.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')
                      LEFT JOIN (SELECT e1.id, 
                                        e1.apontamento_saldo, 
                                        e2.id_usuario
                                 FROM alocacao_apontamento e1 
                                 INNER JOIN alocacao_usuarios e2 ON 
                                            e2.id = e1.id_alocado
                                 WHERE e1.data <= '{$alocacao->data_fechamento}') e ON 
                                e.id_usuario = a.id_usuario
                      LEFT JOIN (SELECT f1.id, 
                                        f1.id_alocado,
                                        f1.qtde_dias, 
                                        f1.hora_atraso,
                                        f1.status
                                 FROM alocacao_apontamento f1
                                 INNER JOIN alocacao_usuarios f2 ON 
                                            f2.id = f1.id_alocado
                                 WHERE f2.id_alocacao IN ('{$alocacao->id}', '{$alocacao->id_anterior}') AND 
                                       f1.data BETWEEN '{$alocacao->data_abertura}' AND '{$alocacao->data_fechamento}') f ON 
                                f.id_alocado = a.id AND
                                f.id = e.id    
                      WHERE b.id = '{$alocacao->id}' AND 
                            (a.cargo = '{$busca['cargo']}' OR CHAR_LENGTH('{$busca['cargo']}') = 0 ) AND
                            (a.funcao = '{$busca['funcao']}' OR CHAR_LENGTH('{$busca['funcao']}') = 0 ) AND 
                            a.nivel = 'P'
                      GROUP BY a.id
                      ORDER BY a.nome ASC) s
                GROUP BY s.id_usuario";

//        $alocados = $this->db->query($sql);


		$this->load->library('dataTables');
		$output = $this->datatables->query($sql);


		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semana = array();

		$arrDataAnterior = explode('-', $alocacao->data_abertura);
		$arrDataAtual = explode('-', $alocacao->data_fechamento);
		for ($i = 0; $i <= 6; $i++) {
			$semana[$i + 1] = $dias_semana[date('w', mktime(0, 0, 0, $arrDataAnterior[1], $arrDataAnterior[2] + $i, $arrDataAnterior[0]))];
		}


		$arrayDias = array_pad([], 32, null);
		unset($arrayDias[0]);

		$begin = new DateTime($alocacao->data_abertura);
		$end = new DateTime($alocacao->data_fechamento);
		$end = $end->modify('+1 day');

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval, $end);
		$qtdeDias = 0;
		foreach ($daterange as $k => $date) {
			$qtdeDias++;
			if (strtotime($date->format('Y-m-d')) <= strtotime(date('Y-m-d'))) {
				$arrayDias[$k + 1] = $date->format('d');
			}
		}


		$output->calendar = array(
			'dias' => $arrayDias,
			'mes_anterior' => $arrDataAnterior[1],
			'ano_anterior' => $arrDataAnterior[0],
			'mes_ano_anterior' => $this->calendar->get_month_name($arrDataAnterior[1]) . ' ' . $arrDataAnterior[0],
			'mes' => $arrDataAtual[1],
			'ano' => $arrDataAtual[0],
			'mes_ano' => $this->calendar->get_month_name($arrDataAtual[1]) . ' ' . $arrDataAtual[0],
			'qtde_dias' => $qtdeDias,
			'semana' => $semana,
			'mes_bloqueado' => boolval($alocacao->mes_bloqueado ?? 0)
		);


		$this->db->select(["e.id, a.id_usuario, DATE_FORMAT(e.data, '%d') AS dia"], false);
		$this->db->select('e.qtde_dias, f.nome AS detalhe, e.observacoes, e.status, e.qtde_req, e.qtde_rev');
		$this->db->select('e.id_alocado_bck, e.detalhes AS id_detalhe, g.nome, g.nome');
		$this->db->select(["DATE_FORMAT(e.hora_atraso, '%H:%i') AS hora_atraso"], false);
		$this->db->select(["DATE_FORMAT(e.hora_entrada, '%H:%i') AS hora_entrada"], false);
		$this->db->select(["DATE_FORMAT(e.hora_intervalo, '%H:%i') AS hora_intervalo"], false);
		$this->db->select(["DATE_FORMAT(e.hora_retorno, '%H:%i') AS hora_retorno"], false);
		$this->db->select(["DATE_FORMAT(e.hora_saida, '%H:%i') AS hora_saida"], false);
		$this->db->select(["DATE_FORMAT(e.hora_glosa, '%H:%i') AS hora_glosa"], false);
		$this->db->select(["DATE_FORMAT(e.apontamento_extra, '%H:%i') AS apontamento_extra"], false);
		$this->db->select(["DATE_FORMAT(e.apontamento_desc, '%H:%i') AS apontamento_desc"], false);
		$this->db->select(["TIME_TO_SEC(e.apontamento_saldo) AS apontamento_saldo"], false);

		$this->db->join('alocacao_usuarios a', 'a.id = e.id_alocado');
		$this->db->join('alocacao_eventos f', 'f.id = e.detalhes', 'left');
		$this->db->join('usuarios g', 'g.id = e.id_alocado_bck', 'left');
		if ($alocacao->id_anterior) {
			$this->db->where_in('a.id_alocacao', [$alocacao->id_anterior, $alocacao->id]);
		} else {
			$this->db->where('a.id_alocacao', $alocacao->id);
		}
		$this->db->where_in('a.id_usuario', array_column($output->data, 'id_usuario') + [0]);
		$this->db->where("e.data BETWEEN '{$alocacao->data_abertura}' AND '{$alocacao->data_fechamento}'");
		$eventos = $this->db->get('alocacao_apontamento e')->result();

		$apontamentos = array();

		foreach ($eventos as $evento) {
			$apontamentos[$evento->id_usuario][$evento->dia] = array(
				$evento->id . '',
				$evento->qtde_dias . '',
				$evento->hora_atraso . '',
				$evento->hora_entrada . '',
				$evento->hora_intervalo . '',
				$evento->hora_retorno . '',
				$evento->hora_saida . '',
				$evento->detalhe . '',
				$evento->observacoes . '',
				$evento->status . '',
				$evento->id_alocado_bck . '',
				$evento->id_alocado_bck . '',
				$evento->hora_glosa . '',
				$evento->id_detalhe . '',
				$evento->nome . '',
				$evento->nome . '',
				$evento->apontamento_extra . '',
				$evento->apontamento_desc . '',
				$evento->apontamento_saldo . '',
				$evento->qtde_req . '',
				$evento->qtde_rev . ''
			);
		}


		$data = array();

		$diaSolicitado = strtotime($busca['ano'] . '-' . $busca['mes'] . '-' . date('t'));
		$diaLimite = date(($diaSolicitado < strtotime(date('Y-m-t')) ? 't' : 'd'));

		foreach ($output->data as $k => $row) {
			$rowData = array(
				[
					$row->id,
					$row->nome,
					$row->data_recesso,
					$row->data_retorno,
					$row->id_usuario_bck,
					$row->tipo_bck,
					$row->nome_bck,
					$row->id_bck
				], [
					$row->id,
					$row->nome_sub,
					$row->data_desligamento,
					$row->id_usuario_sub,
					$row->id_sub
				]
			);

			$rowData[] = $row->total_saldo;

			for ($i = 1; $i <= 31; $i++) {
				if (empty($arrayDias[$i])) {
					$rowData[] = [];
					continue;
				}
				$rowData[] = $apontamentos[$row->id_usuario][$arrayDias[$i]] ?? [''];
			}
//                print_r($apontamentos[$row->id_usuario]);exit;

			$rowData[] = $row->total_faltas;
			$rowData[] = $row->total_atrasos;

			$data[] = $rowData;
		}


		$output->data = $data;


		echo json_encode($output);
	}


	public function ajaxTotalizacao()
	{
		$post = $this->input->post();
		parse_str($this->input->post('busca'), $busca);

		$alocacao = $this->getAlocacao($busca, $post['consolidado']);


		$sql = "SELECT s.nome,
                       s.dias_faltas,
                       TRIM(ROUND(s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0), 2)) + 0  AS perc_dias_faltas,
                       s.horas_atraso,
                       TRIM(ROUND(s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0), 2)) + 0 AS perc_horas_atraso,
                       FORMAT(s.valor_posto, 2, 'de_DE') AS valor_posto,
                       FORMAT(s.valor_dia, 2, 'de_DE') AS valor_dia,
                       FORMAT(s.valor_dia * NULLIF(s.dias_faltas, 0), 2, 'de_DE') AS glosa_dia,
                       FORMAT(s.valor_posto * (s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)) / 100, 2, 'de_DE') AS perc_glosa_dia,
                       FORMAT(s.valor_hora, 2, 'de_DE') AS valor_hora,
                       FORMAT(s.valor_hora * NULLIF(s.minutos_atraso, 0), 2, 'de_DE') AS glosa_hora,
                       FORMAT(s.valor_posto * (s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)) / 100, 2, 'de_DE') AS perc_glosa_hora,
                       CASE {$post['calculo_totalizacao']} WHEN 2 THEN
                            FORMAT(s.valor_posto * (1 - (IFNULL(s.dias_faltas * 100.0 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0), 0) + IFNULL(s.minutos_atraso * 100.0 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0), 0)) / 100.0) + IFNULL(s.total_acrescido, 0), 2, 'de_DE')
                            ELSE 
                            FORMAT(s.valor_posto - (IFNULL(s.dias_faltas, 0) * s.valor_dia + IFNULL(s.minutos_atraso, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0), 2, 'de_DE') END AS valor_total,
                       s.dias_acrescidos,
                       s.horas_acrescidas,
                       s.total_acrescido,
                       s.id
                FROM (SELECT a.id,
                             c.nome,
                             d.dias_faltas,
                             TIME_FORMAT(SEC_TO_TIME(d.segundos_atraso), '%k:%i') AS horas_atraso,
                             d.segundos_atraso / 3600 AS minutos_atraso,
                             e.valor_posto, 
                             e.valor_dia, 
                             e.total_dias_mensais,
                             e.valor_hora,
                             e.total_horas_diarias,
                             SUM(a.dias_acrescidos) AS dias_acrescidos,
                             SUM(a.horas_acrescidas) AS horas_acrescidas,
                             SUM(a.total_acrescido) AS total_acrescido
                      FROM alocacao_usuarios a
                      INNER JOIN alocacao b ON 
                                b.id = a.id_alocacao
                      INNER JOIN usuarios c ON 
                                c.id = a.id_usuario 
                      LEFT JOIN (SELECT d2.id_usuario,
                                        SUM(CASE WHEN d1.status IN ('FJ','FN','PD','PI') 
                                                 THEN d1.qtde_dias 
                                                 END) AS dias_faltas,
                                        SUM(CASE WHEN d1.status IN ('AJ','AN','SJ','SN') 
                                                 THEN TIME_TO_SEC(d1.hora_atraso) 
                                                 END) AS segundos_atraso
                                 FROM alocacao_apontamento d1
                                 INNER JOIN alocacao_usuarios d2 ON d2.id = d1.id_alocado
                                 INNER JOIN alocacao d3 ON d3.id = d2.id_alocacao
                                 WHERE d3.id IN ('{$alocacao->id}', '{$alocacao->id_anterior}') AND 
                                       d1.data BETWEEN '{$alocacao->data_abertura}' AND '{$alocacao->data_fechamento}'
                                 GROUP BY d2.id_usuario) d ON 
                                d.id_usuario = a.id_usuario
                      LEFT JOIN alocacao_postos e ON 
                                e.id_usuario = a.id_usuario AND
                                e.data = (SELECT MAX(e1.data) 
                                          FROM alocacao_postos e1
                                          WHERE e1.id_usuario = e.id_usuario AND 
                                                DATE_FORMAT(e1.data,'%Y-%m') <= DATE_FORMAT(b.data,'%Y-%m'))
                      WHERE b.id = '{$alocacao->id}' AND
                            (c.cargo = '{$busca['cargo']}' OR CHAR_LENGTH('{$busca['cargo']}') = 0) AND
                            (c.funcao = '{$busca['funcao']}' OR CHAR_LENGTH('{$busca['funcao']}') = 0)
                            GROUP BY a.id_usuario) s 
                ORDER BY s.nome ASC";

		$this->load->library('dataTables');
		$output = $this->datatables->query($sql);


		$data = array();

		$diaSolicitado = strtotime($busca['ano'] . '-' . $busca['mes'] . '-' . date('t'));
		$diaLimite = date(($diaSolicitado < strtotime(date('Y-m-t')) ? 't' : 'd'));

		$posto = 0;
		$total = 0;

		foreach ($output->data as $k => $row) {
			$data[] = array(
				$row->nome,
				$row->dias_faltas,
				str_replace('.', ',', $row->perc_dias_faltas),
				$row->horas_atraso,
				str_replace('.', ',', $row->perc_horas_atraso),
				$row->valor_posto,
				$row->valor_dia,
				str_replace('.', ',', $post['calculo_totalizacao'] === '2' ? $row->perc_glosa_dia : $row->glosa_dia),
				$row->valor_hora,
				str_replace('.', ',', $post['calculo_totalizacao'] === '2' ? $row->perc_glosa_hora : $row->glosa_hora),
				$row->valor_posto ? $row->valor_total : '',
				$row->dias_acrescidos,
				$row->horas_acrescidas,
				$row->total_acrescido,
				$row->id
			);

			if ($row->valor_posto) {
				$posto += str_replace(array('.', ','), array('', '.'), $row->valor_posto);
			}
			if ($row->valor_total) {
				$total += str_replace(array('.', ','), array('', '.'), $row->valor_total);
			}
		}

		$output->total_posto = number_format($posto, 2, ',', '.');
		$output->total = number_format($total, 2, ',', '.');
		$output->total_percentual = str_replace('.', ',', round($total * 100 / max($posto, 1), 2));

		$output->data = $data;


		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semana = array();
		for ($i = 1; $i <= 7; $i++) {
			$semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
		}
		$output->calendar = array(
			'mes' => $busca['mes'],
			'ano' => $busca['ano'],
			'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
			'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
			'semana' => $semana,
			'mes_bloqueado' => boolval($alocacao->mes_bloqueado ?? 0)
		);


		echo json_encode($output);
	}


	public function index()
	{
		$modo_privilegiado = true;

		$empresa = $this->session->userdata('empresa');
		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
			if (in_array($this->session->userdata('nivel'), array(10, 11))) {
				$modo_privilegiado = false;
			}

			$this->db->select('depto, area, setor');
			$this->db->where('id', $this->session->userdata('id'));
			$this->db->like('depto', 'servicos terceirizados');
			$filtro = $this->db->get('usuarios')->row();

			if (in_array($this->session->userdata('nivel'), array(9, 10))) {
				$data = $this->get_filtros_usuarios($filtro->depto);
				$data['depto'] = array($filtro->depto => $filtro->depto);
			} else {
				$data = $this->get_filtros_usuarios($filtro->depto, $filtro->area, $filtro->setor);
				$data['depto'] = array($filtro->depto => $filtro->depto);
				$data['area'] = array($filtro->area => $filtro->area);
				$data['setor'] = array($filtro->setor => $filtro->setor);
			}
		} else {
			$this->db->select('depto');
			$this->db->where('empresa', $empresa);
			$this->db->like('depto', 'servicos terceirizados');
			$filtro = $this->db->get('usuarios')->row();
			$data = $this->get_filtros_usuarios($filtro->depto ?? 'servicos terceirizados');
		}

		$data['modo_privilegiado'] = $modo_privilegiado;
		$data['area_colaborador'] = $data['area'];
		$data['area_colaborador'][''] = 'selecione...';
		$data['setor_colaborador'] = $data['setor'];
		$data['setor_colaborador'][''] = 'selecione...';

		$this->db->select('DISTINCT(contrato) AS nome', false);
		$this->db->where('empresa', $empresa);
		$this->db->where('CHAR_LENGTH(contrato) >', 0);
		$contratos = $this->db->get('usuarios')->result();
		$data['contrato'] = array('' => 'selecione...');
		foreach ($contratos as $contrato) {
			$data['contrato'][$contrato->nome] = $contrato->nome;
		}

		$data['meses'] = array(
			'01' => 'Janeiro',
			'02' => 'Fevereiro',
			'03' => 'Março',
			'04' => 'Abril',
			'05' => 'Maio',
			'06' => 'Junho',
			'07' => 'Julho',
			'08' => 'Agosto',
			'09' => 'Setembro',
			'10' => 'Outubro',
			'11' => 'Novembro',
			'12' => 'Dezembro'
		);
		$data['mes'] = $data['meses'][date('m')];

		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
			if (in_array($this->session->userdata('nivel'), array(9, 10))) {
				$this->db->select("depto, '' AS area, '' AS setor", false);
			} else {
				$this->db->select('depto, area, setor');
			}
			$this->db->where('id', $this->session->userdata('id'));
		} else {
			$this->db->select("depto, '' AS area, '' AS setor", false);
			$this->db->like('depto', 'serviços terceirizados');
		}
		$status = $this->db->get('usuarios')->row();
		$data['depto_atual'] = $status->depto;
		$data['area_atual'] = $status->area;
		$data['setor_atual'] = $status->setor;

		$this->db->select('id');
		$this->db->where('id_empresa', $empresa);
		$this->db->where("DATE_FORMAT(data, '%m/%Y') =", date('m/Y'));
		$alocacao = $this->db->get('alocacao')->row();
		$data['id_alocacao'] = $alocacao->id ?? '';

		$data['usuarios'] = array('' => 'selecione...');

		$this->db->select('id, codigo, nome');
		$this->db->where('id_empresa', $empresa);
		$this->db->order_by('codigo', 'asc');
		$detalhes = $this->db->get('alocacao_eventos')->result();
		$data['detalhes'] = array('' => 'selecione...');
		foreach ($detalhes as $detalhe) {
			$data['detalhes'][$detalhe->id] = $detalhe->codigo . ' - ' . $detalhe->nome;
		}

		$this->load->view('apontamento', $data);
	}

	public function atualizar_filtro()
	{
		$depto = $this->input->post('depto');
		$area = $this->input->post('area');
		$setor = $this->input->post('setor');
		$cargo = $this->input->post('cargo');
		$funcao = $this->input->post('funcao');

		$filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);

		if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
			if (in_array($this->session->userdata('nivel'), array(9, 10))) {
				$filtro['depto'] = array($depto => $depto);
			} else {
				$filtro['depto'] = array($depto => $depto);
				$filtro['area'] = array($area => $area);
				$filtro['setor'] = array($setor => $setor);
			}
		}

		$data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'onchange="atualizarFiltro()" class="form-control input-sm"');
		$data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'class="form-control input-sm"');

		echo json_encode($data);
	}

	public function novo()
	{
		$empresa = $this->session->userdata('empresa');
		$post = $this->input->post();
		$dia = !empty($post['dia']) ? $post['dia'] : 1;
		$mes = empty($post['mes']) ? date('m') : $post['mes'];
		$ano = empty($post['ano']) ? date('Y') : $post['ano'];

		$subquery = "(SELECT id, id_empresa, data, depto, area, setor
                      FROM alocacao
                      WHERE DATE_FORMAT(data, '%Y-%m') = '{$ano}-{$mes}') b";
		$subquery2 = "(SELECT depto, area, setor, contrato, descricao_servico, valor_servico, dia_fechamento, 
                              qtde_alocados_potenciais, turnover_reposicao, turnover_aumento_quadro, 
                              turnover_desligamento_empresa, turnover_desligamento_colaborador, observacoes
                       FROM alocacao
                       WHERE DATE_FORMAT(data, '%Y-%m') <= '{$ano}-{$mes}' AND 
                             (depto = '{$post['depto']}' OR CHAR_LENGTH('{$post['depto']}') = 0) AND
                             (area = '{$post['area']}' OR CHAR_LENGTH('{$post['area']}') = 0) AND
                             (setor = '{$post['setor']}' OR CHAR_LENGTH('{$post['setor']}') = 0) 
                       ORDER BY data DESC LIMIT 1) c";

		$this->db->select('a.depto, a.area, a.setor, c.contrato, c.descricao_servico, c.valor_servico, c.dia_fechamento, c.qtde_alocados_potenciais, c.observacoes');
		$this->db->select('c.turnover_reposicao, c.turnover_aumento_quadro, c.turnover_desligamento_empresa, c.turnover_desligamento_colaborador');
		$this->db->join($subquery, 'b.depto = a.depto AND b.area = a.area AND b.setor = a.setor', 'left');
		$this->db->join($subquery2, 'c.depto = a.depto AND c.area = a.area AND c.setor = a.setor', 'left');
		$this->db->where('a.empresa', $empresa);
		$this->db->where_in('a.status', array(1, 3));
		$this->db->where('b.id', null);
		if ($post['depto']) {
			$this->db->where('a.depto', $post['depto']);
		} else {
			$this->db->where('CHAR_LENGTH(a.depto) >', 0);
		}
		if ($post['area']) {
			$this->db->where('a.area', $post['area']);
		} else {
			$this->db->where('CHAR_LENGTH(a.area) >', 0);
		}
		if ($post['setor']) {
			$this->db->where('a.setor', $post['setor']);
		} else {
			$this->db->where('CHAR_LENGTH(a.setor) >', 0);
		}
		$this->db->group_by(array('a.depto', 'a.area', 'a.setor'));
		$this->db->order_by('a.depto ASC, a.area ASC, a.setor ASC');
		$rows = $this->db->get('usuarios a')->result_array();
		if (empty($rows)) {
			exit;
		}

		$data = array();
		foreach ($rows as $row) {
			$data[] = array(
				'id_empresa' => $empresa,
				'data' => date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $ano)),
				'depto' => $row['depto'],
				'area' => $row['area'],
				'setor' => $row['setor'],
				'contrato' => $row['contrato'],
				'descricao_servico' => $row['descricao_servico'],
				'valor_servico' => $row['valor_servico'],
				'dia_fechamento' => $row['dia_fechamento'],
				'qtde_alocados_potenciais' => $row['qtde_alocados_potenciais'],
				'turnover_reposicao' => $row['turnover_reposicao'],
				'turnover_aumento_quadro' => $row['turnover_aumento_quadro'],
				'turnover_desligamento_empresa' => $row['turnover_desligamento_empresa'],
				'turnover_desligamento_colaborador' => $row['turnover_desligamento_colaborador'],
				'observacoes' => $row['observacoes']
			);
		}

		$this->db->trans_start();

		$this->db->insert_batch('alocacao', $data);

		$data2 = array();

		foreach ($rows as $row) {
			$this->db->select('b.id AS id_alocacao, a.id AS id_usuario');
			$this->db->select("'I' AS tipo_horario, 'P' AS nivel", false);
			$this->db->join($subquery, 'b.id_empresa = a.empresa AND b.depto = a.depto AND b.area = a.area AND b.setor = a.setor');
			$this->db->where(array(
				'b.id_empresa' => $empresa,
				'b.data' => date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $ano)),
				'b.depto' => $row['depto'],
				'b.area' => $row['area'],
				'b.setor' => $row['setor']
			));
			$this->db->where_in('a.status', array(1, 3));
			$rows2 = $this->db->get('usuarios a')->result_array();

			foreach ($rows2 as $row2) {
				$data2[] = $row2;
			}
		}

		if ($data2) {
			$this->db->insert_batch('alocacao_usuarios', $data2);
		}

		$this->db->trans_complete();
		$status = $this->db->trans_status();

		echo json_encode(array('status' => $status !== false));
	}

	public function listarEMTU()
	{
		parse_str($this->input->post('busca'), $busca);

		$output = $this->montarEMTU($busca, $this->input->post('consolidado'));

		echo json_encode($output);
	}


	private function montarEMTU($busca, $consolidado = false)
	{
		$alocacao = $this->getAlocacao($busca, $consolidado);

		$sql = "SELECT s.*
                FROM (SELECT a.id_usuario, c.nome, 'qtde_req' AS tipo, 'Req.' AS nome_tipo
                      FROM alocacao_usuarios a 
                      INNER JOIN alocacao b ON b.id = a.id_alocacao
                      INNER JOIN usuarios c ON c.id = a.id_usuario
                      WHERE b.id IN ('{$alocacao->id}') AND 
                            b.area = 'EMTU' AND 
                            b.setor LIKE '%Passe Escolar%' AND
                            (a.cargo = '{$busca['cargo']}' OR CHAR_LENGTH('{$busca['cargo']}') = 0) AND 
                            (a.funcao = '{$busca['funcao']}' OR CHAR_LENGTH('{$busca['funcao']}') = 0)
                      GROUP BY a.id_usuario 
                      UNION
                      SELECT a2.id_usuario, c2.nome, 'qtde_rev' AS tipo, 'Rev.' AS nome_tipo
                      FROM alocacao_usuarios a2
                      INNER JOIN alocacao b2 ON b2.id = a2.id_alocacao
                      INNER JOIN usuarios c2 ON c2.id = a2.id_usuario
                      WHERE b2.id IN ('{$alocacao->id}') AND 
                            b2.area = 'EMTU' AND 
                            b2.setor LIKE '%Passe Escolar%' AND
                            (a2.cargo = '{$busca['cargo']}' OR CHAR_LENGTH('{$busca['cargo']}') = 0) AND 
                            (a2.funcao = '{$busca['funcao']}' OR CHAR_LENGTH('{$busca['funcao']}') = 0)
                      GROUP BY a2.id_usuario) s
                GROUP BY s.id_usuario, s.tipo 
                ORDER BY s.id_usuario ASC, s.tipo ASC";

		$this->load->library('dataTables', ['search' => ['nome']]);

		$output = $this->datatables->query($sql);

		$rowsApontamentos = $this->db
			->select("b.id_usuario, DATE_FORMAT(a.data, '%d') AS dia, SUM(a.qtde_req) AS qtde_req, SUM(a.qtde_rev) AS qtde_rev", false)
			->join('alocacao_usuarios b', 'b.id = a.id_alocado')
			->join('alocacao c', 'c.id = b.id_alocacao')
			->where_in('c.id', [$alocacao->id, $alocacao->id_anterior ?? 0])
			->where("a.data BETWEEN '{$alocacao->data_abertura}' AND '{$alocacao->data_fechamento}'")
			->group_by(['b.id', 'a.data'])
			->get('alocacao_apontamento a')
			->result();

		$eventos = [];

		foreach ($rowsApontamentos as $rowApontamento) {
			$eventos[$rowApontamento->id_usuario][$rowApontamento->dia] = [
				'qtde_req' => $rowApontamento->qtde_req,
				'qtde_rev' => $rowApontamento->qtde_rev
			];
		}

		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semana = array();

		$arrDataAnterior = explode('-', $alocacao->data_abertura);
		$arrDataAtual = explode('-', $alocacao->data_fechamento);
		for ($i = 0; $i <= 6; $i++) {
			$semana[$i + 1] = $dias_semana[date('w', mktime(0, 0, 0, $arrDataAnterior[1], $arrDataAnterior[2] + $i, $arrDataAnterior[0]))];
		}


		$arrayDias = array_pad([], 32, null);
		unset($arrayDias[0]);

		$begin = new DateTime($alocacao->data_abertura);
		$end = new DateTime($alocacao->data_fechamento);
		$end = $end->modify('+1 day');

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval, $end);
		$qtdeDias = 0;
		foreach ($daterange as $k => $date) {
			$qtdeDias++;
			if (strtotime($date->format('Y-m-d')) <= strtotime(date('Y-m-d'))) {
				$arrayDias[$k + 1] = $date->format('d');
			}
		}

		$data = [];

		foreach ($output->data as $row) {
			$rows = [
				$row->nome,
				$row->nome_tipo
			];

			$total = null;
			foreach ($arrayDias as $dia) {
				$evento = $eventos[$row->id_usuario][$dia][$row->tipo] ?? null;
				$rows[] = $evento;
				$total += $evento;
			}

			$rows[] = $total;
			$data[] = $rows;
		}

		$output->data = $data;

		$output->calendar = array(
			'dias' => $arrayDias,
			'mes_anterior' => $arrDataAnterior[1],
			'ano_anterior' => $arrDataAnterior[0],
			'mes_ano_anterior' => $this->calendar->get_month_name($arrDataAnterior[1]) . ' ' . $arrDataAnterior[0],
			'mes' => $arrDataAtual[1],
			'ano' => $arrDataAtual[0],
			'mes_ano' => $this->calendar->get_month_name($arrDataAtual[1]) . ' ' . $arrDataAtual[0],
			'qtde_dias' => $qtdeDias,
			'semana' => $semana,
			'mes_bloqueado' => boolval($alocacao->mes_bloqueado ?? 0)
		);

		return $output;
	}


	public function listarProducaoEMTU()
	{
		parse_str($this->input->post('busca'), $busca);

		$output = $this->montarProducaoEMTU($busca, $this->input->post('consolidado'));

		echo json_encode($output);
	}


	private function montarProducaoEMTU($busca, $consolidado = false)
	{
		$alocacao = $this->getAlocacao($busca, $consolidado);

		$fatorDivisor = max((int)$this->input->get_post('fator_divisor'), 1);

		$feriados = [
			'qtde_novos_processos' => ['Qtde. processos novos'],
			'qtde_analistas' => ['Qtde. analistas'],
			'qtde_pagamentos' => ['Pagamentos gerados para a AME'],
			'qtde_processos_analisados' => ['Qtde. processos analisados'],
			'qtde_linhas_analisadas' => ['Qtde. linhas analisadas'],
			'total_analisados' => ['Total analisados'],
			'qtde_colaboradores_estimada' => ['Qtde. estimada de colaboradores']
		];

		$order = $this->input->post('order')[0]['dir'] ?? '';
		if ($order === 'desc') {
			$feriados = array_reverse($feriados);
		}

		$rows = $this->db
			->select("*, DATE_FORMAT(data, '%d') AS dia", false)
			->where_in('id_alocacao', [$alocacao->id, $alocacao->id_anterior ?? 0])
			->where("data BETWEEN '{$alocacao->data_abertura}' AND '{$alocacao->data_fechamento}'")
			->get('alocacao_feriados')
			->result();

		foreach ($rows as $row) {
			$feriados['qtde_novos_processos'][$row->dia] = $row->qtde_novos_processos;
			$feriados['qtde_analistas'][$row->dia] = $row->qtde_analistas;
			$feriados['qtde_pagamentos'][$row->dia] = $row->qtde_pagamentos;
			$feriados['qtde_processos_analisados'][$row->dia] = $row->qtde_processos_analisados;
			$feriados['qtde_linhas_analisadas'][$row->dia] = $row->qtde_linhas_analisadas;
			$feriados['total_analisados'][$row->dia] = $row->qtde_processos_analisados + $row->qtde_linhas_analisadas;
			$feriados['qtde_colaboradores_estimada'][$row->dia] = round(($row->qtde_processos_analisados + $row->qtde_linhas_analisadas) / $fatorDivisor);
		}

		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semana = array();

		$arrDataAnterior = explode('-', $alocacao->data_abertura);
		$arrDataAtual = explode('-', $alocacao->data_fechamento);
		for ($i = 0; $i <= 6; $i++) {
			$semana[$i + 1] = $dias_semana[date('w', mktime(0, 0, 0, $arrDataAnterior[1], $arrDataAnterior[2] + $i, $arrDataAnterior[0]))];
		}

		$arrayDias = array_pad([], 32, null);
		unset($arrayDias[0]);

		$begin = new DateTime($alocacao->data_abertura);
		$end = new DateTime($alocacao->data_fechamento);
		$end = $end->modify('+1 day');

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval, $end);
		$qtdeDias = 0;
		foreach ($daterange as $k => $date) {
			$qtdeDias++;
			if (strtotime($date->format('Y-m-d')) <= strtotime(date('Y-m-d'))) {
				$arrayDias[$k + 1] = $date->format('d');
			}
		}

		$data = [];

		foreach ($feriados as $k => $feriado) {
			$rows = [$feriado[0]];

			$total = null;
			foreach ($arrayDias as $dia) {
				$evento = $feriado[$dia] ?? null;
				$rows[] = $evento;
				$total += $evento;
			}

			$rows[] = $total;
			$data[] = $rows;
		}

		$count = $order === 'desc' ? 0 : count($data) - 1;
		foreach ($data[$count] as &$row2) {
			$row2 = '<strong>' . $row2 . '</strong>';
		}

		$mediaColaboradores = array_filter($feriados['qtde_colaboradores_estimada']);
		unset($mediaColaboradores[0]);
		if (empty($mediaColaboradores)) {
			$mediaColaboradores = [''];
		}
		$calculoFatorDivisor = [
			'base' => $fatorDivisor,
			'min' => min($mediaColaboradores),
			'avg' => round(array_sum($mediaColaboradores) / count($mediaColaboradores)),
			'max' => max($mediaColaboradores)
		];

		$output = [
			'draw' => $this->input->post('draw'),
			'recordsTotal' => count($data),
			'recordsFiltered' => count($data),
			'fator_divisor' => $calculoFatorDivisor,
			'calendar' => [
				'dias' => $arrayDias,
				'mes_anterior' => $arrDataAnterior[1],
				'ano_anterior' => $arrDataAnterior[0],
				'mes_ano_anterior' => $this->calendar->get_month_name($arrDataAnterior[1]) . ' ' . $arrDataAnterior[0],
				'mes' => $arrDataAtual[1],
				'ano' => $arrDataAtual[0],
				'mes_ano' => $this->calendar->get_month_name($arrDataAtual[1]) . ' ' . $arrDataAtual[0],
				'qtde_dias' => $qtdeDias,
				'semana' => $semana,
				'mes_bloqueado' => boolval($alocacao->mes_bloqueado ?? 0)
			],
			'data' => $data,
		];

		return $output;
	}


	public function listarRelatorioDeGestaoEMTU()
	{
		parse_str($this->input->post('busca'), $busca);

		$setores = $this->db
			->select('a.nome')
			->join('empresa_areas b', 'b.id = a.id_area')
			->join('empresa_departamentos c', 'c.id = b.id_departamento')
			->where('c.id_empresa', $this->session->userdata('empresa'))
			->where('c.nome', $busca['depto'])
			->where('b.nome', 'EMTU')
			->like('a.nome', 'Passe Escolar', 'both')
			->group_by('a.id')
			->order_by('a.nome', 'asc')
			->get('empresa_setores a')
			->result_array();

		$alocacoes = $this->db
			->select('id, setor', false)
			->where('id_empresa', $this->session->userdata('empresa'))
			->where('depto', $busca['depto'])
			->where('area', 'EMTU')
			->like('setor', 'Passe Escolar', 'both')
//			->where('MONTH(data)', $busca['mes'])
//			->where('YEAR(data)', $busca['ano'])
			->order_by('setor', 'asc')
			->get('alocacao')
			->result_array();

		$idAlocacoes = array_column($alocacoes, 'id');
		$unidades = array_column($setores, 'nome', 'nome');


		$unidade = $this->input->post('unidade');
		$mesAnoInicio = $this->input->post('mes_ano_inicio');
		$mesAnoTermino = $this->input->post('mes_ano_termino');

		$anoMesInicio = date('Y-m-d', strtotime(preg_replace('/^(\d+)\/(\d+)*/', '$2-$1-01', $mesAnoInicio)));
		$anoMesTermino = date('Y-m-t', strtotime(preg_replace('/^(\d+)\/(\d+)*/', '$2-$1-01', $mesAnoTermino)));
		
		$this->db
			->select('a.*')
			->join('alocacao b', 'b.id = a.id_alocacao')
			->where_in('b.id', $idAlocacoes + [0]);
		if (strlen($unidade) > 0) {
			$this->db->where('a.unidade', $unidade);
		}
		if (strlen($mesAnoInicio) > 0) {
			$this->db
				->where("(b.data >= '{$anoMesInicio}')")
				->where("STR_TO_DATE(CONCAT(a.ano, '-', a.mes, '-01'), '%Y-%m-%d') >= '{$anoMesInicio}'");
		}
//		else {
//			$this->db->where("b.data >= STR_TO_DATE('{$busca['ano']}-{$busca['mes']}-01', '%Y-%m-%d')");
//		}
		if (strlen($mesAnoTermino) > 0) {
			$this->db
				->where("(b.data <= '{$anoMesTermino}')")
				->where("LAST_DAY(STR_TO_DATE(CONCAT(a.ano, '-', a.mes, '-01'), '%Y-%m-%d')) <= '{$anoMesTermino}'");
		}
//		else {
//			$this->db->where("b.data <= LAST_DAY(STR_TO_DATE('{$busca['ano']}-{$busca['mes']}-01', '%Y-%m-%d'))");
//		}
		$query = $this->db
			->get('alocacao_fechamento_mensal_emtu a');

		$this->load->library('dataTables', ['search' => ['nome']]);

		$output = $this->datatables->generate($query);

		$this->load->library('calendar', ['month_type' => 'short']);

		$data = [];
		foreach ($output->data as $row) {
			$data[] = [
				$this->calendar->get_month_name($row->mes) . '/' . $row->ano,
				$row->qtde_dados,
				$row->qtde_dias_uteis,
				$row->qtde_digitadores,
				number_format($row->valor_receita, 2, ',', '.'),
				number_format($row->valor_custo_fixo, 2, ',', '.'),
				number_format($row->valor_custo_variavel, 2, ',', '.'),
				number_format($row->valor_custo_total, 2, ',', '.'),
				number_format($row->valor_resultado, 2, ',', '.'),
				str_replace('.', ',', $row->resultado_percentual)
			];
		}
		$output->data = $data;
		$output->unidades = form_dropdown('', ['' => 'Todas'] + $unidades, $unidade);

		echo json_encode($output);
	}


	public function editarFeriado()
	{
		parse_str($this->input->post('busca'), $busca);

		$alocacao = $this->db
			->select('id')
			->where('id_empresa', $this->session->userdata('empresa'))
			->where('depto', $busca['depto'])
			->where('area', $busca['area'])
			->where('setor', $busca['setor'])
			->where('YEAR(data)', $busca['ano'])
			->where('MONTH(data)', $busca['mes'])
			->get('alocacao')
			->row();

		if (empty($alocacao)) {
			exit(json_encode(['erro' => 'Alocação do mês não encontrada.']));
		}

		$data = $this->db
			->where('id_alocacao', $alocacao->id)
			->where('data', $this->input->post('dia'))
			->get('alocacao_feriados')
			->row();

		if (empty($data)) {
			$data['id_alocacao'] = $alocacao->id;
			$data['data'] = $this->input->post('dia');
		}

		echo json_encode($data);
	}


	public function salvarFeriado()
	{
		$data = $this->input->post();
		if (strlen($data['status']) == 0) {
			$data['status'] = null;
		}

		$id = $data['id'];
		unset($data['id']);

		$this->db->trans_start();

		if ($id) {
			$this->db->update('alocacao_feriados', $data, ['id' => $id]);

			if ($data['status']) {
				$apontamentos = $this->db
					->select(["a.id AS id_alocado, '{$data['data']}' AS data, '{$data['status']}' AS status"], false)
					->join('alocacao b', 'b.id = a.id_alocacao')
					->join('alocacao_feriados c', 'c.id_alocacao = b.id')
					->join('ei_apontamento d', "d.id_alocado = a.id AND d.data = '{$data['data']}'")
					->where('c.id', $id)
					->where('d.status', null)
					->group_by('a.id')
					->get('alocacao_usuarios a')
					->result_array();
			}
		} else {
			$this->db->insert('alocacao_feriados', $data);
			$id = $this->db->insert_id();

			if ($data['status']) {
				$apontamentos = $this->db
					->select(["a.id AS id_alocado, '{$data['data']}' AS data, '{$data['status']}' AS status"], false)
					->join('alocacao b', 'b.id = a.id_alocacao')
					->join('alocacao_feriados c', 'c.id_alocacao = b.id')
					->join('ei_apontamento d', "d.id_alocado = a.id AND d.data = '{$data['data']}'", 'left')
					->where('c.id', $id)
					->where('d.status', null)
					->group_by('a.id')
					->get('alocacao_usuarios a')
					->result_array();
			}
		}

		if (!empty($apontamentos) and $data['status']) {
			$this->db->insert_batch('alocacao_apontamento', $apontamentos);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Não foi possível salvar os eventos do dia.']));
		}

		echo json_encode(['status' => true]);
	}


	public function limparFeriado()
	{
		$this->db->trans_start();

		$apontamento = $this->db
			->select('a.id')
			->join('alocacao_usuarios b', 'b.id = a.id_alocado')
			->join('alocacao c', 'c.id = b.id_alocacao')
			->join('alocacao_feriados d', 'd.id_alocacao = c.id AND d.data = a.data AND d.status = a.status')
			->where('d.id', $this->input->post('id'))
			->get('alocacao_apontamento a')
			->result();

		$apontamentos = array_column($apontamento, 'id');

		$this->db->where_in('id', $apontamentos)->delete('alocacao_apontamento');
		$this->db->delete('alocacao_feriados', ['id' => $this->input->post('id')]);
		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Não foi possível limpar os eventos do dia.']));
		}
		echo json_encode(['status' => true]);
	}


	public function ajax_list2()
	{
		$post = $this->input->post();
		parse_str($this->input->post('busca'), $busca);

		$this->db->select("CASE WHEN dia_fechamento > 0 THEN dia_fechamento END AS dia_fechamento, mes_bloqueado", false);
		$this->db->where("DATE_FORMAT(data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
		$this->db->where('depto', $busca['depto'] ?? null);
		$this->db->where('area', $busca['area'] ?? null);
		$this->db->where('setor', $busca['setor'] ?? null);
		$alocacao = $this->db->get('alocacao')->row();
		if (isset($post['dia_fechamento']) and empty(intval($post['dia_fechamento']))) {
			$post['dia_fechamento'] = $alocacao->dia_fechamento ?? '';
		}

		if (!empty($post['dia_fechamento'])) {
			$sqlMesAno = "SELECT DATE_ADD(DATE_SUB(STR_TO_DATE('{$post['dia_fechamento']}/{$busca['mes']}/{$busca['ano']}', '%d/%m/%Y'), INTERVAL 1 MONTH), INTERVAL 1 DAY) AS mes_ano";
			$mes_ano = $this->db->query($sqlMesAno)->row()->mes_ano;

			$dataAbertura = date('Y-m-d', strtotime(str_replace('/', '-', $mes_ano)));
			$dataFechamento = date('Y-m-d', strtotime("{$busca['ano']}-{$busca['mes']}-{$post['dia_fechamento']}"));
			$view_alocacao = 'view_alocacao_consolidada';
		} else {
			$mes_ano = $busca['ano'] . '-' . $busca['mes'] . '-01';

			$dataAbertura = $mes_ano;
			$dataFechamento = date('Y-m-t', strtotime($mes_ano));
			$view_alocacao = 'view_alocacao';
		}

		//CONCAT(\'"\', IFNULL(REPLACE(REPLACE(e.observacoes, CHAR(10), CONCAT(\'\\\\\', \'a\')), CHAR(13), \'\\ r\'), \'\'), \'",\'),
		$sqlDias = "SELECT GROUP_CONCAT(CONCAT(' dia_', DATE_FORMAT(x.daynum, '%d'))) AS titulo,
                           GROUP_CONCAT(CONCAT(' (SELECT IF(\'', x.daynum, '\' <= CURDATE(), IF(e.id IS NOT NULL AND (e.status = \'FR\' OR e.data <= CURDATE()), CONCAT(\'[\', GROUP_CONCAT(
                                                        CONCAT(\'\"\', e.id, \'\",\'), 
                                                        CONCAT(\'\"\', IFNULL(e.qtde_dias, \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(DATE_FORMAT(e.hora_atraso, \'%H:%i\'), \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(DATE_FORMAT(e.hora_entrada, \'%H:%i\'), \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(DATE_FORMAT(e.hora_intervalo, \'%H:%i\'), \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(DATE_FORMAT(e.hora_retorno, \'%H:%i\'), \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(DATE_FORMAT(e.hora_saida, \'%H:%i\'), \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(f.nome, \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(e.observacoes, \'\'), \'\",\'),
                                                        CONCAT(\'\"\', e.status, \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(e.id_alocado_bck, \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(e.id_alocado_bck, \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(DATE_FORMAT(e.hora_glosa, \'%H:%i\'), \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(f.id, \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(g.nome, \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(h.nome, \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(DATE_FORMAT(e.apontamento_extra, \'%H:%i\'), \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(DATE_FORMAT(e.apontamento_desc, \'%H:%i\'), \'\'), \'\",\'),
                                                        CONCAT(\'\"\', IFNULL(TIME_TO_SEC(e.apontamento_extra), \'0:00\') - IFNULL(TIME_TO_SEC(e.apontamento_desc), \'0:00\'), \'\"\')
                                                    ),\']\'),
                                                \'[\"\"]\'), \'[]\') FROM alocacao_apontamento e
                                                            INNER JOIN alocacao_usuarios i ON i.id = e.id_alocado
                                                            INNER JOIN alocacao j ON j.id = i.id_alocacao
                                                            LEFT JOIN alocacao_eventos f ON
                                                                      f.id = e.detalhes
                                                            LEFT JOIN usuarios g ON 
                                                                      g.id = e.id_alocado_bck
                                                            LEFT JOIN usuarios h ON 
                                                                      h.id = e.id_alocado_bck
                                                            WHERE j.depto = b.depto AND
                                                                  j.area = b.area AND
                                                                  j.setor = b.setor AND
                                                                  DATE_FORMAT(j.data, \'%Y-%m\') = \'', DATE_FORMAT(x.daynum, '%Y-%m'), '\' AND
                                                                  i.id_usuario = a.id_usuario AND
                                                                  DATE_FORMAT(e.data, \'%Y-%m-%d\') = \'', DATE_FORMAT(x.daynum, '%Y-%m-%d'),
                                                  '\') AS dia_', DATE_FORMAT(x.daynum, '%d'))) AS atributos
                    FROM (SELECT C.mes_ano AS mes_ano,
                                 DATE_ADD(C.mes_ano, INTERVAL t+u DAY) AS daynum
                          FROM (SELECT 0 t UNION SELECT 10 UNION SELECT 20 UNION SELECT 30 
                                UNION SELECT 40 UNION SELECT 50 UNION SELECT 60) A,
                               (SELECT 0 u UNION SELECT '01' UNION SELECT 2 UNION SELECT 3
                                UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
                                UNION SELECT 8 UNION SELECT 9) B,
                               (SELECT STR_TO_DATE('{$mes_ano}', '%Y-%m-%d') AS mes_ano) C
                          ORDER BY daynum LIMIT 31) x 
                    WHERE (MONTH(x.daynum) < MONTH(x.mes_ano) AND YEAR(x.daynum) > YEAR(x.mes_ano)) OR 
                          (MONTH(x.daynum) = MONTH(x.mes_ano) AND DAY(x.daynum) >= DAY(x.mes_ano)) OR 
                          (MONTH(x.daynum) = (MONTH(x.mes_ano) + 1) AND DAY(x.daynum) < DAY(x.mes_ano))";
		$this->db->query('SET SESSION group_concat_max_len = 1000000');
		$dias = $this->db->query($sqlDias)->row();


		$rowDias = array_map(function ($dia) {
			$arrayDias[] = $dia;
			return '$row[] = json_decode($apontamento->' . trim($dia) . ');';
		}, explode(',', $dias->titulo));

		$arrayDias = array();
		foreach (explode(',', $dias->titulo) as $n => $dia) {
			$arrayDias[$n + 1] = trim(str_replace('dia_', '', $dia));
		}

		$sql = "SELECT s.id,
                       s.nome,
                       s.nome_sub,
                       s.segundos_saldo,
                       s.tipo_bck,
                       s.data_recesso,
                       s.data_retorno,
                       s.id_usuario_bck,
                       s.id_bck,
                       s.nome_bck,
                       s.data_desligamento,
                       s.id_usuario_sub,
                       s.id_sub,
                       TIME_FORMAT(SEC_TO_TIME(s.segundos_saldo), '%k:%i') AS saldo,
                       {$dias->titulo},
                       IF(s.total_faltas > 0, s.total_faltas, null) AS total_faltas,
                       TIME_FORMAT(SEC_TO_TIME(s.total_atrasos), '%k:%i') AS total_atrasos,
                       s.id_usuario,
                       s.data
                FROM (SELECT a.id,
                             a.id_usuario,
                             c.nome,
                             b.data,
                             a.tipo_bck,
                             DATE_FORMAT(a.data_recesso, '%d/%m/%Y') AS data_recesso,
                             DATE_FORMAT(a.data_retorno, '%d/%m/%Y') AS data_retorno,
                             DATE_FORMAT(a.data_desligamento, '%d/%m/%Y') AS data_desligamento,
                             a.id_usuario_bck,
                             d1.id AS id_bck,
                             d.nome AS nome_bck,
                             a.id_usuario_sub,
                             e1.id AS id_sub,
                             e.nome AS nome_sub,
                             NULL AS segundos_saldo,
                             {$dias->atributos},
                             (CASE WHEN h.data >= '{$dataAbertura}' THEN h.dias_faltas END) AS total_faltas,
                             (CASE WHEN h.data >= '{$dataAbertura}' THEN h.segundos_atraso END) AS total_atrasos
                      FROM alocacao_usuarios a
                      INNER JOIN alocacao b ON b.id = a.id_alocacao
                      INNER JOIN usuarios c ON c.id = a.id_usuario
                      LEFT JOIN usuarios d ON 
                                      d.id = a.id_usuario_bck
                      LEFT JOIN (SELECT x.id, x.id_usuario, y.depto, y.area, y.setor, y.data 
                                 FROM alocacao_usuarios x 
                                 INNER JOIN alocacao y ON 
                                            y.id = x.id_alocacao) d1 ON 
                                d1.id_usuario = d.id AND
                                d1.depto = b.depto AND 
                                d1.area = b.area AND 
                                d1.setor = 'Backup' AND 
                                DATE_FORMAT(d1.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')
                      LEFT JOIN usuarios e ON 
                                      e.id = a.id_usuario_sub
                      LEFT JOIN (SELECT x.id, x.id_usuario, y.depto, y.area, y.setor, y.data 
                                 FROM alocacao_usuarios x 
                                 INNER JOIN alocacao y ON 
                                            y.id = x.id_alocacao) e1 ON 
                                e1.id_usuario = d.id AND
                                e1.depto = b.depto AND 
                                e1.area = b.area AND 
                                e1.setor = b.setor AND 
                                DATE_FORMAT(e1.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')
                      LEFT JOIN {$view_alocacao} h ON 
                                h.id = a.id AND
                                h.data <= '{$dataFechamento}'
                      WHERE b.id_empresa = {$this->session->userdata('empresa')} AND
                            b.depto = '{$busca['depto']}' AND
                            b.area = '{$busca['area']}' AND
                            b.setor = '{$busca['setor']}' AND
                            (c.cargo = '{$busca['cargo']}' OR CHAR_LENGTH('{$busca['cargo']}') = 0) AND
                            (c.funcao = '{$busca['funcao']}' OR CHAR_LENGTH('{$busca['funcao']}') = 0) AND
                            a.nivel = 'P'
                      GROUP BY a.id) s
                WHERE s.data BETWEEN '{$dataAbertura}' AND '{$dataFechamento}'";
		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array('s.id', 's.nome', 's.nome_bck', 's.nome_sub', 's.segundos_saldo');
		if ($post['search']['value']) {
			foreach ($columns as $key => $column) {
				if ($key > 1) {
					$sql .= " OR
                    {$column} LIKE '%{$post['search']['value']}%'";
					if ($key == count($columns) - 1) {
						$sql .= ')';
					}
				} elseif ($key == 1) {
					$sql .= " 
                    AND ({$column} LIKE '%{$post['search']['value']}%'";
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
		$sql .= " 
        LIMIT {$post['start']}, {$post['length']}";
		$list = $this->db->query($sql)->result();

		$data = array();
		$saldo_apontamentos = array();

		foreach ($list as $apontamento) {
			$saldo_apontamentos[] = array(
				'id' => $apontamento->id_usuario,
				'saldo_apontamentos' => $apontamento->saldo
			);

			$row = array();
			$row[] = array(
				$apontamento->id,
				$apontamento->nome,
				$apontamento->data_recesso,
				$apontamento->data_retorno,
				$apontamento->id_usuario_bck,
				$apontamento->tipo_bck,
				$apontamento->nome_bck,
				$apontamento->id_bck,
			);
			$row[] = array(
				$apontamento->id,
				$apontamento->nome_sub,
				$apontamento->data_desligamento,
				$apontamento->id_usuario_sub,
				$apontamento->id_sub
			);
			$row[] = $apontamento->saldo;
			for ($i = 0; $i < 31; $i++) {
				if (isset($rowDias[$i])) {
					eval($rowDias[$i]);
				} else {
					$row[] = array('');
				}
			}
			$row[] = $apontamento->total_faltas;
			$row[] = $apontamento->total_atrasos;

			$data[] = $row;
		}

		if ($saldo_apontamentos) {
			$this->db->update_batch('usuarios', $saldo_apontamentos, 'id');
		}


		$this->load->library('Calendar');
		$dias_semana = $this->calendar->get_day_names('long');
		$semana = array();

		$arrDataAnterior = explode('-', $dataAbertura);
		$arrDataAtual = explode('-', $dataFechamento);
		for ($i = 0; $i <= 6; $i++) {
			$semana[$i + 1] = $dias_semana[date('w', mktime(0, 0, 0, $arrDataAnterior[1], $arrDataAnterior[2] + $i, $arrDataAnterior[0]))];
		}
		$calendario = array(
			'dias' => $arrayDias,
			'mes_anterior' => $arrDataAnterior[1],
			'ano_anterior' => $arrDataAnterior[0],
			'mes_ano_anterior' => $this->calendar->get_month_name($arrDataAnterior[1]) . ' ' . $arrDataAnterior[0],
			'mes' => $arrDataAtual[1],
			'ano' => $arrDataAtual[0],
			'mes_ano' => $this->calendar->get_month_name($arrDataAtual[1]) . ' ' . $arrDataAtual[0],
			'qtde_dias' => count($arrayDias),
			'semana' => $semana,
			'mes_bloqueado' => boolval($alocacao->mes_bloqueado ?? 0)
		);

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"calendar" => $calendario,
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_colaboradores()
	{
		$empresa = $this->session->userdata('empresa');
		parse_str($this->input->post('busca'), $busca);

		$this->db->select("id, dia_fechamento, CASE area WHEN 'Ipesp' THEN area END AS ipesp", false);
		$this->db->select("(CASE WHEN area = 'EMTU' AND setor LIKE '%Passe Escolar%' THEN area END) AS emtu", false);
		$this->db->select("CASE setor WHEN 'Presencial' THEN setor END AS presencial", false);
		$this->db->select("CASE setor WHEN 'Teleatendimento' THEN setor END AS teleatendimento", false);
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		if ($busca['depto']) {
			$this->db->where('depto', $busca['depto']);
		}
		if ($busca['area']) {
			$this->db->where('area', $busca['area']);
		}
		if ($busca['setor']) {
			$this->db->where('setor', $busca['setor']);
		}
		$this->db->where("DATE_FORMAT(data, '%m/%Y') =", date('m/Y', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])));
		$alocacao = $this->db->get('alocacao')->row();
		$data['id'] = $alocacao->id ?? '';
		$data['ipesp'] = $alocacao->ipesp ?? null;
		$data['emtu'] = $alocacao->emtu ?? null;
		$data['dia_fechamento'] = '';
		if (in_array($this->session->userdata('nivel'), array(0, 1, 3, 7, 8, 9))) {
			$data['dia_fechamento'] = $alocacao->dia_fechamento ?? '';
		}

		$sql = "SELECT a.id, a.nome
        FROM usuarios a
        LEFT JOIN alocacao c ON 
        c.data = '" . date('Y-m-d', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])) . "'
        LEFT JOIN alocacao_usuarios b ON 
        b.id_usuario = a.id AND 
        c.id = b.id_alocacao
        WHERE a.empresa = '$empresa' AND 
        a.tipo =  'funcionario' AND 
        a.status IN ('1', '3') AND 
        b.id_usuario IS NULL";
		if ($busca['depto']) {
			$sql .= " AND a.depto = '{$busca['depto']}'";
		}
		if ($busca['area']) {
			$sql .= " AND a.area = '{$busca['area']}'";
		}
		if ($busca['setor']) {
			$sql .= " AND a.setor = '{$busca['setor']}'";
		} else {
			$sql .= " AND a.setor != 'backup'";
		}
		$sql .= ' ORDER BY a.nome asc';
		$rows_novos = $this->db->query($sql)->result();
		$usuarios_novos = array('' => 'selecione...');
		$usuarios_sub = array('' => null);
		if (count($rows_novos) > 0) {
			foreach ($rows_novos as $row_novo) {
				$usuarios_novos[$row_novo->id] = $row_novo->nome;
				$usuarios_sub[$row_novo->id] = $row_novo->nome;
			}
		}

		$data['id_usuario'] = form_dropdown('id_usuario', $usuarios_novos, '', 'class="form-control"');


		$sql2 = "SELECT s.id, s.nome 
                FROM (SELECT a.id, a.nome 
                FROM usuarios a
                WHERE a.empresa =  '$empresa' AND
                a.tipo =  'funcionario' AND 
                a.status IN ('1', '3')";
		if ($busca['depto']) {
			$sql2 .= " AND a.depto = '{$busca['depto']}'";
		}

		$sql2 .= "AND a.setor =  'backup'
                UNION
                SELECT d.id, d.nome
                FROM alocacao_usuarios b
                INNER JOIN alocacao c ON c.id = b.id_alocacao
                INNER JOIN usuarios d ON d.id = b.id_usuario_bck
                WHERE c.id_empresa =  '$empresa' AND
                DATE_FORMAT(c.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
		if ($busca['depto']) {
			$sql2 .= " AND c.depto = '{$busca['depto']}'";
		}
//        if ($busca['area']) {
//            $sql2 .= " AND c.area = '{$busca['area']}'";
//        }
		if ($busca['setor']) {
			$sql2 .= " AND c.setor = '{$busca['setor']}'";
		}
		$sql2 .= ") s 
                ORDER BY s.nome asc";
		$rows_bck = $this->db->query($sql2)->result();
		$usuarios_bck = array('' => 'selecione...');
		if (count($rows_bck) > 0) {
			foreach ($rows_bck as $row_bck) {
				$usuarios_bck[$row_bck->id] = $row_bck->nome;
				$usuarios_sub[$row_bck->id] = $row_bck->nome;
			}
		}

		$data['id_usuario_bck'] = form_dropdown('id_usuario_bck', $usuarios_bck, '', 'class="form-control"');
		asort($usuarios_sub);
		$usuarios_sub[''] = 'selecione...';
		$data['id_usuario_sub'] = form_dropdown('id_usuario_sub', $usuarios_sub, '', 'class="form-control"');
		$data['id_alocado_bck'] = form_dropdown('id_alocado_bck', $usuarios_bck, '', 'class="form-control"');


		$this->db->select('b.id, c.nome');
		$this->db->join('alocacao_usuarios b', 'b.id_alocacao = a.id');
		$this->db->join('usuarios c', 'c.id = b.id_usuario');
		$this->db->where('a.id_empresa', $this->session->userdata('empresa'));
		$this->db->where('c.tipo', 'funcionario');
		$this->db->where_in('c.status', array('1', '3'));
		$this->db->where("DATE_FORMAT(a.data, '%m/%Y') =", date('m/Y', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])));
		if ($busca['depto']) {
			$this->db->where('a.depto', $busca['depto']);
		}
		if ($busca['area']) {
			$this->db->where('a.area', $busca['area']);
		}
		if ($busca['setor']) {
			$this->db->where('a.setor', $busca['setor']);
		}
		$this->db->order_by('c.nome', 'asc');
		$rows_alocados = $this->db->get('alocacao a')->result();
		$usuarios_alocados = array('' => 'selecione...');
		if (count($rows_alocados) > 0) {
			foreach ($rows_alocados as $row_alocado) {
				$usuarios_alocados[$row_alocado->id] = $row_alocado->nome;
			}
		}

		$data['id_usuario_alocado'] = form_dropdown('id', $usuarios_alocados, '', 'class="form-control"');

		echo json_encode($data);
	}

	public function ajax_avaliado($id)
	{
		if (empty($id)) {
			$id = $this->uri->rsegment(3);
		}
		$empresa = $this->session->userdata('empresa');
		$post = $this->input->post();

		$sql = "SELECT s.id,
                s.nome,
                s.data_programada,
                s.avaliador,
                s.data_realizada
                FROM (SELECT b.id, 
                c.nome, 
                DATE_FORMAT(a.data_avaliacao,'%d/%m/%Y') AS data_programada,
                d.nome AS avaliador,
                (SELECT MAX(x.data_avaliacao)
                FROM avaliacaoexp_resultado x
                WHERE x.id_avaliador = a.id) AS data_realizada
                FROM avaliacaoexp_avaliadores a
                INNER JOIN avaliacaoexp_avaliados b ON
                b.id = a.id_avaliado
                LEFT JOIN avaliacaoexp_modelos c ON
                c.id = b.id_modelo and
                c.id_usuario_EMPRESA = {$empresa}
                LEFT JOIN usuarios d ON
                d.id = a.id_avaliador
                WHERE b.id_avaliado = {$id} AND 
                c.tipo = 'P') s";
		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array(
			's.id',
			's.nome',
			's.data_programada',
			's.avaliador',
			's.data_realizada'
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
			$row[] = $avaliacaoExp->nome;
			$row[] = $avaliacaoExp->data_programada;
			$row[] = $avaliacaoExp->avaliador;
			$row[] = $avaliacaoExp->data_realizada ? date("d/m/Y", strtotime(str_replace('-', '/', $avaliacaoExp->data_realizada))) : '';

			$row[] = '
                    <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Gerenciar avaliadores" onclick="edit_avaliado(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Gerenciar avaliadores</a>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_avaliado(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                    <a class="btn btn-sm btn-primary" href="' . site_url('avaliacaoexp_avaliados/relatorio/' . $avaliacaoExp->id) . '" title="Relatório de avaliação"><i class="glyphicon glyphicon-list-alt"> </i> Relatório</a>
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

	public function ajax_edit()
	{
		$this->db->select('id, codigo, nome');
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$this->db->order_by('codigo', 'asc');
		$detalhes = $this->db->get('alocacao_eventos')->result();
		$data = array('' => 'selecione...');
		foreach ($detalhes as $detalhe) {
			$data[$detalhe->id] = $detalhe->codigo . ' - ' . $detalhe->nome;
		}

		echo form_dropdown('detalhes', $data, '', 'class="form-control"');
	}

	public function ajax_config()
	{
		$busca = $this->input->post();

		$this->db->select("DATE_FORMAT(data, '%m') AS mes, DATE_FORMAT(data, '%Y') AS ano", false);
		$this->db->select('contrato, descricao_servico, valor_servico, dia_fechamento, qtde_alocados_potenciais, qtde_alocados_ativos, observacoes, valor_projetado, valor_realizado');
		$this->db->select('turnover_reposicao, turnover_aumento_quadro, turnover_desligamento_empresa, turnover_desligamento_colaborador');
		$this->db->select('total_faltas, total_dias_cobertos, total_dias_descobertos, mes_bloqueado');
		if (!empty($busca['depto'])) {
			$this->db->where('depto', $busca['depto']);
		}
		if (!empty($busca['area'])) {
			$this->db->where('area', $busca['area']);
		}
		if (!empty($busca['setor'])) {
			$this->db->where('setor', $busca['setor']);
		}
		$this->db->where("DATE_FORMAT(data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
		$data = $this->db->get('alocacao')->row();
		if (!empty($data->valor_servico)) {
			$data->valor_servico = number_format($data->valor_servico, 2, ',', '.');
		}

		echo json_encode($data);
	}

	public function ajax_config_ipesp()
	{
		$busca = $this->input->post();

		$this->db->select("a.*, DATE_FORMAT(b.data, '%m') AS mes, DATE_FORMAT(b.data, '%Y') AS ano", false);
		$this->db->join('alocacao b', 'b.id = a.id_alocacao');
		if (!empty($busca['depto'])) {
			$this->db->where('b.depto', $busca['depto']);
		}
		if (!empty($busca['area'])) {
			$this->db->where('b.area', 'ipesp');
		}
		if (!empty($busca['setor'])) {
			$this->db->where_in('b.setor', array('presencial', 'teleatendimento'));
		}
		$this->db->where("DATE_FORMAT(b.data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
		$data = $this->db->get('alocacao_observacoes a')->row();
		if (empty($data)) {
			$data = $busca;
		} else {
			$data->balanco_valor_projetado = number_format($data->balanco_valor_projetado, 2, ',', '.');
			$data->balanco_valor_glosa = number_format($data->balanco_valor_glosa, 2, ',', '.');
			$data->balanco_glosas = number_format($data->balanco_glosas, 2, ',', '.');
			$data->balanco_porcentagem = number_format($data->balanco_porcentagem, 1, ',', '');
		}

		echo json_encode($data);
	}

	public function ajax_saveConfig()
	{
		$busca = $this->input->post();

		if (strlen($busca['contrato']) == 0) {
			$busca['contrato'] = null;
		}
		if (strlen($busca['descricao_servico']) == 0) {
			$busca['descricao_servico'] = null;
		}
		if (strlen($busca['valor_servico']) == 0) {
			$busca['valor_servico'] = null;
		} else {
			$busca['valor_servico'] = str_replace(array('.', ','), array('', '.'), $busca['valor_servico']);
		}
		if (strlen($busca['dia_fechamento']) == 0) {
			$busca['dia_fechamento'] = 0;
		}
		if (strlen($busca['qtde_alocados_potenciais']) == 0) {
			$busca['qtde_alocados_potenciais'] = null;
		}
		if (strlen($busca['qtde_alocados_ativos']) == 0) {
			$busca['qtde_alocados_ativos'] = null;
		}
		if (strlen($busca['turnover_reposicao']) == 0) {
			$busca['turnover_reposicao'] = null;
		}
		if (strlen($busca['turnover_aumento_quadro']) == 0) {
			$busca['turnover_aumento_quadro'] = null;
		}
		if (strlen($busca['turnover_desligamento_empresa']) == 0) {
			$busca['turnover_desligamento_empresa'] = null;
		}
		if (strlen($busca['turnover_desligamento_colaborador']) == 0) {
			$busca['turnover_desligamento_colaborador'] = null;
		}
		if (strlen($busca['observacoes']) == 0) {
			$busca['observacoes'] = null;
		}
		if (strlen($busca['valor_projetado']) == 0) {
			$busca['valor_projetado'] = null;
		}
		if (strlen($busca['valor_realizado']) == 0) {
			$busca['valor_realizado'] = null;
		}
		if (strlen($busca['total_faltas']) == 0) {
			$busca['total_faltas'] = 0;
		}
		if (strlen($busca['total_dias_cobertos']) == 0) {
			$busca['total_dias_cobertos'] = 0;
		}
		if (strlen($busca['total_dias_descobertos']) == 0) {
			$busca['total_dias_descobertos'] = 0;
		}
		$data = array(
			'contrato' => $busca['contrato'],
			'descricao_servico' => $busca['descricao_servico'],
			'valor_servico' => $busca['valor_servico'],
			'dia_fechamento' => $busca['dia_fechamento'],
			'qtde_alocados_potenciais' => $busca['qtde_alocados_potenciais'],
			'qtde_alocados_ativos' => $busca['qtde_alocados_ativos'],
			'turnover_reposicao' => $busca['turnover_reposicao'],
			'turnover_aumento_quadro' => $busca['turnover_aumento_quadro'],
			'turnover_desligamento_empresa' => $busca['turnover_desligamento_empresa'],
			'turnover_desligamento_colaborador' => $busca['turnover_desligamento_colaborador'],
			'observacoes' => $busca['observacoes'],
			'valor_projetado' => $busca['valor_projetado'],
			'valor_realizado' => $busca['valor_realizado'],
			'total_faltas' => $busca['total_faltas'],
			'total_dias_cobertos' => $busca['total_dias_cobertos'],
			'total_dias_descobertos' => $busca['total_dias_descobertos'],
		);

		/* $data = array(
		  'contrato' => $busca['contrato'],
		  'qtde_alocados_potenciais' => $busca['qtde_alocados_potenciais'],
		  'qtde_alocados_ativos' => $busca['qtde_alocados_ativos'],
		  'valor_projetado' => $busca['valor_projetado'],
		  'valor_realizado' => $busca['valor_realizado'],
		  'total_faltas' => $busca['total_faltas'],
		  'total_dias_cobertos' => $busca['total_dias_cobertos'],
		  'total_dias_descobertos' => $busca['total_dias_descobertos'],
		  'observacoes' => $busca['observacoes'],

		  ); */

		if (!empty($busca['depto'])) {
			$this->db->where('depto', $busca['depto']);
		}
		if (!empty($busca['area'])) {
			$this->db->where('area', $busca['area']);
		}
		if (!empty($busca['setor'])) {
			$this->db->where('setor', $busca['setor']);
		}
		$this->db->where("DATE_FORMAT(data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
		$status = $this->db->update('alocacao', $data);

		echo json_encode(array("status" => $status !== false));
	}

	public function ajax_saveConfig_ipesp()
	{
		$data = $this->input->post();
		$id = $data['id'];
		$id_alocacao = $data['id_alocacao'];
		unset($data['id'], $data['id_alocacao']);

		foreach ($data as $k => $row) {
			if (strlen($row) == 0) {
				$data[$k] = null;
			}
		}

		if ($data['balanco_glosas']) {
			$data['balanco_glosas'] = str_replace(array('.', ','), array('', '.'), $data['balanco_glosas']);
		}
		if ($data['balanco_porcentagem']) {
			$data['balanco_porcentagem'] = str_replace(array('.', ','), array('', '.'), $data['balanco_porcentagem']);
		}
		if ($data['balanco_valor_glosa']) {
			$data['balanco_valor_glosa'] = str_replace(array('.', ','), array('', '.'), $data['balanco_valor_glosa']);
		}
		if ($data['balanco_valor_projetado']) {
			$data['balanco_valor_projetado'] = str_replace(array('.', ','), array('', '.'), $data['balanco_valor_projetado']);
		}

		$where = array(
			'depto' => $data['depto'],
			'area' => $data['area'],
			'setor' => $data['setor'],
			"DATE_FORMAT(data, '%Y-%m') =" => $data['ano'] . '-' . $data['mes']
		);

		unset($data['depto'], $data['area'], $data['setor'], $data['cargo'], $data['funcao'], $data['mes'], $data['ano']);

		if ($id and $id_alocacao) {
			$status = $this->db->update('alocacao_observacoes', $data, array('id' => $id));
		} else {
			$this->db->select('id');
			$alocacao = $this->db->get_where('alocacao', $where)->row();

			$data['id_alocacao'] = $alocacao->id;

			$status = $this->db->insert('alocacao_observacoes', $data);
		}


		echo json_encode(array("status" => $status !== false));
	}

	public function bloquearMes()
	{
		$post = $this->input->post();

		$this->db->set('mes_bloqueado', $post['mes_bloqueado'] ? 1 : null);
		$this->db->where('id_empresa', $this->session->userdata('empresa'));
		$this->db->where('depto', $post['depto']);
		$this->db->where('area', $post['area']);
		$this->db->where('setor', $post['setor']);
		$this->db->where("DATE_FORMAT(data, '%Y-%m') =", $post['ano'] . '-' . $post['mes']);
		$status = $this->db->update('alocacao');

		echo json_encode(['status' => $status !== false]);
	}

	public function ajax_ferias()
	{
		$data = $this->input->post();
		if ($data['data_recesso'] xor $data['data_retorno']) {
			exit('O período de férias está incompleto');
		}

		if ($data['data_recesso']) {
			$data['data_recesso'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_recesso'])));

			//            $this->db->select("DATE_FORMAT(b.data, '%Y%m') as data", false);
			//            $this->db->join('alocacao b', 'b.id = a.id_alocacao');
			//            $row = $this->db->get_where('alocacao_usuarios a', array('a.id' => $data['id']))->row();
			//            if ($row->data != date("Ym", strtotime(str_replace('/', '-', $data['data_recesso'])))) {
			//                exit('A data de início de férias deve pertencer ao mês e ano correspondentes');
			//            }
		} else {
			$data['data_recesso'] = null;
		}
		if ($data['data_retorno']) {
			$data['data_retorno'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_retorno'])));

			$data_inicio = date_create($data['data_recesso']);
			$data_retorno = date_create($data['data_retorno']);
			$tempo_ferias = date_diff($data_inicio, $data_retorno);

			if ($data_inicio > $data_retorno) {
				exit('A data de retorno de férias deve ser maior que a data de início de férias');
			} elseif ($tempo_ferias->format('a') > 30) {
				exit('O tempo máximo de férias deve ser de 30 dias');
			}
		} else {
			$data['data_retorno'] = null;
		}
		if (empty($data['id_usuario_bck'])) {
			$data['id_usuario_bck'] = null;
		}

		if (empty($data['tipo_bck']) and empty($data['data_recesso']) and empty($data['data_retorno'])) {
			$data['tipo_bck'] = null;
		}

		$this->db->select('a.*, b.id_empresa, b.data, b.depto, b.area', false);
		$this->db->join('alocacao b', 'b.id = a.id_alocacao');
		$alocado = $this->db->get_where('alocacao_usuarios a', array('a.id' => $data['id']))->row();

		$this->db->update('alocacao_usuarios', $data, array('id' => $data['id']));

		/* $this->db->select('a.*, b.id_empresa, b.data, b.depto, b.area, b.setor', false);
		  $this->db->join('alocacao b', 'b.id = a.id_alocacao');
		  $this->db->where('a.id_usuario', $alocado->id_usuario_bck);
		  $this->db->where('b.setor', 'backup');
		  $this->db->where("DATE_FORMAT(b.data, '%Y-%m') =", date('Y-m', strtotime($alocado->data)));
		  $alocado_bck = $this->db->get('alocacao_usuarios a')->row();

		  if ($alocado_bck) {
		  $qtde_alocados = $this->db->get_where('alocacao_usuarios', array('id_alocacao' => $alocado_bck->id_alocacao))->num_rows();

		  if ($data['id_usuario_bck'] == null) {
		  if ($qtde_alocados === 1) {
		  $this->db->delete('alocacao', array('id' => $alocado_bck->id_alocacao));
		  } else {
		  $this->db->delete('alocacao_usuarios', array('id' => $alocado_bck->id));
		  }

		  } elseif ($alocado_bck->id_usuario != $data['id_usuario_bck']) {
		  $data2 = (array) $alocado_bck;
		  $data2['id_usuario'] = $data['id_usuario_bck'];
		  $data2['tipo_bck'] = $data['tipo_bck'];
		  unset($data2['id_empresa'], $data2['data'], $data2['depto'], $data2['area'], $data2['setor']);

		  if ($alocado_bck->id_alocacao != $alocado->id_alocacao) {
		  if ($qtde_alocados === 1) {
		  $this->db->delete('alocacao', array('id' => $alocado_bck->id_alocacao));
		  }

		  $tem_alocacao = $this->db->get_where('alocacao', array('id' => $alocado_bck->id_alocacao))->num_rows();
		  if ($tem_alocacao == 0) {
		  $data_alocacao = array(
		  'id_empresa' => $alocado->id_empresa,
		  'data' => $alocado->data,
		  'depto' => $alocado->depto,
		  'area' => $alocado->area,
		  'setor' => 'Backup'
		  );
		  $this->db->insert('alocacao', $data_alocacao);
		  $data2['id_alocacao'] = $this->db->insert_id();
		  }
		  }

		  $this->db->delete('alocacao_apontamento', array('id_alocado' => $alocado_bck->id));
		  $this->db->update('alocacao_usuarios', $data2, array('id' => $alocado_bck->id));

		  } else {
		  $this->db->update('alocacao_usuarios', array('tipo_bck' => $data['tipo_bck']), array('id' => $alocado_bck->id));
		  }

		  } else {
		  $data2 = array(
		  'id_usuario' => $data['id_usuario_bck'],
		  'tipo_horario' => 'I',
		  'nivel' => 'B',
		  'tipo_bck' => 'F'
		  );

		  $this->db->select('a.id');
		  $this->db->join('alocacao_usuarios b', 'b.id_alocacao = a.id', 'left');
		  $this->db->join('usuarios c', 'c.id = b.id_usuario_bck', 'left');
		  $this->db->where('a.id_empresa', 'c.empresa');
		  $this->db->where('c.id', $data['id_usuario_bck']);
		  $this->db->where("DATE_FORMAT(a.data, '%Y-%m') =", date('Y-m', strtotime($alocado->data)));
		  $this->db->where('a.depto', 'c.depto');
		  $this->db->where('a.area', 'c.area');
		  $this->db->where('a.setor', 'Backup');
		  $alocacao_bck = $this->db->get('alocacao a')->row();

		  if ($alocacao_bck) {
		  $data2['id_alocacao'] = $alocacao_bck->id;
		  } else {
		  $this->db->select('empresa, depto, area');
		  $this->db->where('id', $data['id_usuario_bck']);
		  $alocacao_bck = $this->db->get('usuarios')->row();

		  $data_alocacao = array(
		  'id_empresa' => $alocacao_bck->empresa,
		  'data' => $alocado->data,
		  'depto' => $alocacao_bck->depto,
		  'area' => $alocacao_bck->area,
		  'setor' => 'Backup'
		  );
		  $this->db->insert('alocacao', $data_alocacao);
		  $data2['id_alocacao'] = $this->db->insert_id();
		  }

		  $this->db->insert('alocacao_usuarios', $data2);
		  }
		 */

		$update = "UPDATE alocacao_usuarios 
                   SET data_recesso = '{$data['data_recesso']}', data_retorno = '{$data['data_retorno'] }' 
                   WHERE id_alocacao != {$alocado->id_alocacao} AND 
                         id_usuario = {$alocado->id_usuario} AND
                        (DATE_FORMAT(data_recesso, '%Y-%m') = DATE_FORMAT('{$data['data_recesso']}', '%Y-%m') OR 
                         DATE_FORMAT(data_retorno, '%Y-%m') = DATE_FORMAT('{$data['data_retorno']}', '%Y-%m'))";
		$this->db->query($update);

		echo json_encode(array("status" => true));
	}

	public function ajax_substituto()
	{
		$data = $this->input->post();

		if ($data['data_desligamento']) {
			$data['data_desligamento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_desligamento'])));

			$this->db->select("DATE_FORMAT(b.data, '%Y%m') as data", false);
			$this->db->join('alocacao b', 'b.id = a.id_alocacao');
			$row = $this->db->get_where('alocacao_usuarios a', array('a.id' => $data['id']))->row();
			if ($row->data != date("Ym", strtotime(str_replace('/', '-', $data['data_desligamento'])))) {
				exit('A data de início deve pertencer ao mês e ano correspondentes');
			}
		} else {
			$data['data_desligamento'] = null;
		}
		if (empty($data['id_usuario_sub'])) {
			$data['id_usuario_sub'] = null;
		}

		$this->db->select('a.*, b.id_empresa, b.data, b.depto, b.area, b.setor', false);
		$this->db->join('alocacao b', 'b.id = a.id_alocacao');
		$alocado = $this->db->get_where('alocacao_usuarios a', array('a.id' => $data['id']))->row();

		$this->db->update('alocacao_usuarios', $data, array('id' => $data['id']));

		/* $this->db->select('a.*, b.id_empresa, b.data, b.depto, b.area, b.setor', false);
		  $this->db->join('alocacao b', 'b.id = a.id_alocacao');
		  $this->db->where('a.id_usuario', $alocado->id_usuario_sub);
		  $this->db->where('b.setor', $alocado->setor);
		  $this->db->where("DATE_FORMAT(b.data, '%Y-%m') =", date('Y-m', strtotime($alocado->data)));
		  $alocado_sub = $this->db->get('alocacao_usuarios a')->row();

		  if ($alocado_sub) {
		  $qtde_alocados = $this->db->get_where('alocacao_usuarios', array('id_alocacao' => $alocado_bck->id_alocacao))->num_rows();

		  if ($data['id_usuario_sub'] == null) {
		  if ($qtde_alocados === 1) {
		  $this->db->delete('alocacao', array('id' => $alocado_sub->id_alocacao));
		  } else {
		  $this->db->delete('alocacao_usuarios', array('id' => $alocado_sub->id));
		  }

		  } elseif ($alocado_sub->id_usuario != $data['id_usuario_sub']) {
		  $data2 = (array) $alocado_sub;
		  $data2['id_usuario'] = $data['id_usuario_sub'];
		  unset($data2['id_empresa'], $data2['data'], $data2['depto'], $data2['area'], $data2['setor']);

		  if ($alocado_sub->id_alocacao != $alocado->id_alocacao) {
		  if ($qtde_alocados === 1) {
		  $this->db->delete('alocacao', array('id' => $alocado_sub->id_alocacao));
		  }

		  $tem_alocacao = $this->db->get_where('alocacao', array('id' => $alocado_sub->id_alocacao))->num_rows();
		  if ($tem_alocacao == 0) {
		  $data_alocacao = array(
		  'id_empresa' => $alocado->id_empresa,
		  'data' => $alocado->data,
		  'depto' => $alocado->depto,
		  'area' => $alocado->area,
		  'setor' => 'Backup'
		  );
		  $this->db->insert('alocacao', $data_alocacao);
		  $data2['id_alocacao'] = $this->db->insert_id();
		  }
		  }

		  $this->db->delete('alocacao_apontamento', array('id_alocado' => $alocado_sub->id));
		  $this->db->update('alocacao_usuarios', $data2, array('id' => $alocado_sub->id));

		  }

		  } else {
		  $data2 = array(
		  'id_usuario' => $data['id_usuario_sub'],
		  'tipo_horario' => 'I',
		  'nivel' => 'S'
		  );

		  $this->db->select('a.id');
		  $this->db->join('usuarios b', "b.id = {$data['id_usuario_sub']}", 'left');
		  $this->db->where('a.id_empresa', 'b.empresa');
		  $this->db->where("DATE_FORMAT(a.data, '%Y-%m') =", date('Y-m', strtotime($alocado->data)));
		  $this->db->where('a.depto', 'b.depto');
		  $this->db->where('a.area', 'b.area');
		  $this->db->where('a.setor', 'b.setor');
		  $alocacao_sub = $this->db->get('alocacao a')->row();

		  if ($alocacao_sub) {
		  $data2['id_alocacao'] = $alocacao_bck->id;
		  } else {
		  $this->db->select('empresa, depto, area');
		  $this->db->where('id', $data['id_usuario_sub']);
		  $alocacao_sub = $this->db->get('alocacao a')->row();

		  $data_alocacao = array(
		  'id_empresa' => $alocacao_sub->id_empresa,
		  'data' => $alocacao_sub->data,
		  'depto' => $alocacao_sub->depto,
		  'area' => $alocacao_sub->area,
		  'setor' => $alocacao_sub->setor
		  );
		  $this->db->insert('alocacao', $data_alocacao);
		  $data2['id_alocacao'] = $this->db->insert_id();
		  }

		  $this->db->insert('alocacao_usuarios', $data2);
		  } */

		$update = "UPDATE alocacao_usuarios 
                   SET data_desligamento = '{$data['data_desligamento']}'
                   WHERE id_alocacao != {$alocado->id_alocacao} AND 
                         id_usuario = {$alocado->id_usuario} AND
                         DATE_FORMAT(data_desligamento, '%Y-%m') = DATE_FORMAT('{$alocado->data_desligamento}', '%Y-%m')";
		$this->db->query($update);

		echo json_encode(array("status" => true));
	}

	public function ajax_save()
	{
		$data = $this->input->post();
		if (empty($data['id_alocado'])) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Colaborador não encontrado')));
		}

		$data['data'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data'])));
		if (!empty($data['hora_entrada'])) {
			$data['hora_entrada'] = date("Y-m-d H:i", strtotime(str_replace('/', '-', $data['data']) . ' ' . $data['hora_entrada']));
		} else {
			$data['hora_entrada'] = null;
		}
		if (!empty($data['hora_intervalo'])) {
			$data['hora_intervalo'] = date("Y-m-d H:i", strtotime(str_replace('/', '-', $data['data']) . ' ' . $data['hora_intervalo']));
		} else {
			$data['hora_intervalo'] = null;
		}
		if (!empty($data['hora_retorno'])) {
			$data['hora_retorno'] = date("Y-m-d H:i", strtotime(str_replace('/', '-', $data['data']) . ' ' . $data['hora_retorno']));
		} else {
			$data['hora_retorno'] = null;
		}
		if (!empty($data['hora_saida'])) {
			$data['hora_saida'] = date("Y-m-d H:i", strtotime(str_replace('/', '-', $data['data']) . ' ' . $data['hora_saida']));
		} else {
			$data['hora_saida'] = null;
		}
		if (!empty($data['hora_atraso'])) {
			$data['hora_atraso'] = date("H:i", strtotime($data['hora_atraso']));
		} else {
			$data['hora_atraso'] = null;
		}
		if (!empty($data['apontamento_extra'])) {
			$data['apontamento_extra'] = date("H:i", strtotime($data['apontamento_extra']));
		} else {
			$data['apontamento_extra'] = null;
		}
		if (!empty($data['apontamento_desc'])) {
			$data['apontamento_desc'] = date("H:i", strtotime($data['apontamento_desc']));
		} else {
			$data['apontamento_desc'] = null;
		}
		if ($data['apontamento_extra'] === null and $data['apontamento_desc'] === null) {
			$data['apontamento_saldo'] = null;
		} else {
			$sqlSaldo = "SELECT TIMEDIFF(IFNULL('{$data['apontamento_extra']}', 0), IFNULL('{$data['apontamento_desc']}', 0)) AS saldo";
			$data['apontamento_saldo'] = $this->db->query($sqlSaldo)->row()->saldo ?? null;
		}
		if (!empty($data['hora_glosa'])) {
			$data['hora_glosa'] = date("H:i", strtotime($data['hora_glosa']));
		} else {
			$data['hora_glosa'] = null;
		}
		if (!empty($data['qtde_dias'])) {
			$data['qtde_dias'] = in_array($data['status'], array('FJ', 'FN', 'PD', 'PI')) ? max($data['qtde_dias'], 0) : null;
		} else {
			$data['qtde_dias'] = null;
		}

		if (empty($data['detalhes'])) {
			$data['detalhes'] = null;
		}
		if (empty($data['id_alocado_bck'])) {
			$data['id_alocado_bck'] = null;
		}
		if (empty($data['observacoes'])) {
			$data['observacoes'] = null;
		} else {
			$data['observacoes'] = str_replace(array(chr(9), chr(10), chr(13)), array('\t', '\n', '\r'), $data['observacoes']);
		}

		if ($data['id']) {
			$this->db->select('IFNULL(apontamento_saldo, 0) AS apontamento_saldo', false);
			$row_saldo = $this->db->get_where('alocacao_apontamento', array('id' => $data['id']))->row();
			$saldo_old = $row_saldo->apontamento_saldo ?? 0;

			$status = $this->db->update('alocacao_apontamento', $data, array('id' => $data['id']));
			$id = $data['id'];
		} else {
			$saldo_old = 0;
			unset($data['id']);
			$status = $this->db->insert('alocacao_apontamento', $data);
			$id = $this->db->insert_id();
		}

		if ($status !== false) {
			$sql = "SELECT c.id,
                           CASE WHEN a.apontamento_saldo IS NOT NULL 
                                     OR c.saldo_apontamentos IS NOT NULL 
                                     OR '{$saldo_old}' > 0
                                THEN ADDTIME(SUBTIME(IFNULL(c.saldo_apontamentos, 0), '{$saldo_old}'), IFNULL(a.apontamento_saldo, 0))
                                ELSE c.saldo_apontamentos END AS saldo
                FROM alocacao_apontamento a
                INNER JOIN alocacao_usuarios b
                           ON b.id = a.id_alocado
                INNER JOIN usuarios c
                           ON c.id = b.id_usuario
                WHERE a.id = '{$id}'";
			$row = $this->db->query($sql)->row();

			$data2 = array('saldo_apontamentos' => $row->saldo);
			$status = $this->db->update('usuarios', $data2, array('id' => $row->id));
		}

		echo json_encode(array("status" => $status !== false));
	}

	public function ajaxSaveEventos()
	{
		parse_str($this->input->post('eventos'), $eventos);
		parse_str($this->input->post('busca'), $busca);

		$this->db->select("a.id AS id_alocado, '{$eventos['data']}' AS data, '{$eventos['status']}' AS status", false);
		$this->db->join('alocacao b', 'b.id = a.id_alocacao');
		$this->db->join('alocacao_apontamento c', "c.id_alocado = a.id AND c.data = '{$eventos['data']}'", 'left');
		$this->db->where($busca);
		$this->db->where('c.data', null);
		$this->db->group_by('a.id');
		$data = $this->db->get('alocacao_usuarios a')->result_array();

		$status = $this->db->insert_batch('alocacao_apontamento', $data);

		echo json_encode(array('status' => $status !== false));
	}

	public function ajaxDeleteEventos()
	{
		parse_str($this->input->post('eventos'), $eventos);
		parse_str($this->input->post('busca'), $busca);

		$this->db->select('a.id');
		$this->db->join('alocacao_usuarios b', 'b.id = a.id_alocado');
		$this->db->join('alocacao c', 'c.id = b.id_alocacao');
		$this->db->where($busca);
		$this->db->where('a.data', $eventos['data']);
		$this->db->where('a.status', $eventos['status']);
		$where = $this->db->get('alocacao_apontamento a')->result();

		$this->db->where_in('id', array_column($where, 'id'));
		$status = $this->db->delete('alocacao_apontamento');

		echo json_encode(array('status' => $status !== false));
	}

	public function ajax_delete()
	{
		$id = $this->input->post('id');

		$sql = "SELECT c.id,
                       CASE WHEN a.apontamento_saldo IS NOT NULL OR c.saldo_apontamentos IS NOT NULL
                            THEN SUBTIME(IFNULL(c.saldo_apontamentos, 0), IFNULL(a.apontamento_saldo, 0))
                            ELSE c.saldo_apontamentos END AS saldo
                FROM alocacao_apontamento a
                INNER JOIN alocacao_usuarios b
                           ON b.id = a.id_alocado
                INNER JOIN usuarios c
                           ON c.id = b.id_usuario
                WHERE a.id = '{$id}'";
		$row = $this->db->query($sql)->row();

		$data = array('saldo_apontamentos' => $row->saldo);
		$status = $this->db->update('usuarios', $data, array('id' => $row->id));

		if ($status !== false) {
			$status = $this->db->delete('alocacao_apontamento', array('id' => $id));
		}

		echo json_encode(array("status" => $status !== false));
	}

	public function ajax_limpar()
	{
		$post = $this->input->post();
		$where = array(
			'id_empresa' => $this->session->userdata('empresa'),
			'data' => date('Y-m-d', mktime(0, 0, 0, $post['mes'], 1, $post['ano']))
		);
		if ($post['depto']) {
			$where['depto'] = $post['depto'];
		}
		if ($post['area']) {
			$where['area'] = $post['area'];
		}
		if ($post['setor']) {
			$where['setor'] = $post['setor'];
		}
		$this->db->trans_start();

		$this->db->delete('alocacao', $where);

		$this->db->trans_complete();
		$status = $this->db->trans_status();

		echo json_encode(array('status' => $status !== false));
	}


	public function imprimirMedicaoConsolidada()
	{
		$busca = $this->input->get();
		unset($busca['consolidado']);

		$output = $this->montarEMTU($busca, $this->input->get('consolidado'));

		$mes_ano = $output->calendar['mes_ano'];

		$data = [
			'data' => $output->data,
			'empresa' => $this->db
				->select('foto, foto_descricao')
				->where('id', $this->session->userdata('empresa'))
				->get('usuarios')
				->row(),
			'mes_ano' => str_replace(' ', ' DE ', $mes_ano),
			'dias' => $output->calendar['dias'],
			'is_pdf' => true
		];

		unset($output);

		$this->load->library('m_pdf');

		$stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
		$stylesheet .= '#medicao_consolidada { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#medicao_consolidada thead th { font-size: 12px; padding: 4px; background-color: #DFF0D8; border: 1px solid #444; } ';
		$stylesheet .= '#medicao_consolidada thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#medicao_consolidada tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$this->m_pdf->pdf->setTopMargin(38);
		$this->m_pdf->pdf->AddPage('L');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->load->view('apontamento_medicao_consolidada', $data, true));

		$this->m_pdf->pdf->Output('Serviços Terceirizados - Relatório de Medição Consolidada - ' . lcfirst($mes_ano) . '.pdf', 'D');
	}

	public function imprimirRelatorioProducao()
	{
		$busca = $this->input->get();
		unset($busca['consolidado']);

		$output = $this->montarProducaoEMTU($busca, $this->input->get('consolidado'));

		$mes_ano = $output['calendar']['mes_ano'];

		$data = [
			'data' => $output['data'],
			'fator_divisor' => $output['fator_divisor'],
			'empresa' => $this->db
				->select('foto, foto_descricao')
				->where('id', $this->session->userdata('empresa'))
				->get('usuarios')
				->row(),
			'mes_ano' => str_replace(' ', ' DE ', $mes_ano),
			'dias' => $output['calendar']['dias'],
			'is_pdf' => true
		];

		unset($output);

		$this->load->library('m_pdf');

		$stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
		$stylesheet .= '#relatorio_producao { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#relatorio_producao thead th { font-size: 12px; padding: 4px; background-color: #DFF0D8; border: 1px solid #444; } ';
		$stylesheet .= '#relatorio_producao thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#relatorio_producao tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$this->m_pdf->pdf->setTopMargin(38);
		$this->m_pdf->pdf->AddPage('L');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->load->view('apontamento_relatorio_producao', $data, true));

		$this->m_pdf->pdf->Output('Serviços Terceirizados - Relatório de Produção - ' . lcfirst($mes_ano) . '.pdf', 'D');
	}


	public function editarFechamentoMensalEMTU()
	{
		$alocacao = $this->db
			->where('id_empresa', $this->session->userdata('empresa'))
			->where('depto', $this->input->post('depto'))
			->where('area', $this->input->post('area'))
			->where('setor', $this->input->post('setor'))
			->where('MONTH(data)', $this->input->post('mes'))
			->where('YEAR(data)', $this->input->post('ano'))
			->get('alocacao')
			->row();

		if (empty($alocacao)) {
			exit(json_encode(['erro' => 'Alocação mensal não encontrada.']));
		}

		$data = $this->db
			->where('id_alocacao', $alocacao->id)
			->where('mes', $this->input->post('mes'))
			->where('ano', $this->input->post('ano'))
			->get('alocacao_fechamento_mensal_emtu')
			->row();

		if (empty($data)) {
			$output = $this->montarEMTU($this->input->post(), true);

			$contrato = $this->db
				->select('(SELECT MAX(c.valor_indice) FROM alocacao_reajuste c WHERE c.id_cliente = a.id ORDER BY c.data_reajuste DESC) AS valor_indice', false)
				->join('alocacao_unidades b', 'b.id_contrato = a.id')
				->join('usuarios c', 'c.id = a.id_usuario', 'left')
				->where('a.id_empresa', $this->session->userdata('empresa'))
				->where('a.depto', $this->input->post('depto'))
				->where('a.area', $this->input->post('area'))
				->where('b.setor', $this->input->post('setor'))
				->order_by('a.data_assinatura', 'desc')
				->limit(1)
				->get('alocacao_contratos a')
				->row();

			$data = new stdClass();
			$dadosEMTU = $output->data;
			$data->qtde_dias_uteis = $output->calendar['qtde_dias'];
			$data->qtde_dados = array_sum(array_filter(array_column($dadosEMTU, $data->qtde_dias_uteis + 2)));
			$data->qtde_digitadores = count(array_unique(array_column($dadosEMTU, 0)));
			$data->valor_receita = isset($contrato->valor_indice) ? ($contrato->valor_indice * 100) * $data->qtde_dados : null;
			$data->id_alocacao = $alocacao->id;
			$data->unidade = $this->input->post('setor');
			$data->mes = $this->input->post('mes');
			$data->ano = $this->input->post('ano');
		}

		$this->load->library('Calendar');
		$data->mes_ano = $this->calendar->get_month_name($data->mes) . '/' . $data->ano;

		if (!empty($data->valor_receita)) {
			$data->valor_receita = number_format($data->valor_receita, 2, ',', '.');
		}
		if (!empty($data->valor_custo_fixo)) {
			$data->valor_custo_fixo = number_format($data->valor_custo_fixo, 2, ',', '.');
		}
		if (!empty($data->valor_custo_variavel)) {
			$data->valor_custo_variavel = number_format($data->valor_custo_variavel, 2, ',', '.');
		}
		if (!empty($data->valor_custo_total)) {
			$data->valor_custo_total = number_format($data->valor_custo_total, 2, ',', '.');
		}
		if (!empty($data->valor_resultado)) {
			$data->valor_resultado = number_format($data->valor_resultado, 2, ',', '.');
		}
		if (!empty($data->resultado_percentual)) {
			$data->resultado_percentual = str_replace('.', ',', $data->resultado_percentual);
		}

		echo json_encode($data);
	}


	public function salvarFechamentoMensalEMTU()
	{
		$data = $this->input->post();

		if (strlen($data['valor_receita']) > 0) {
			$data['valor_receita'] = str_replace(['.', ','], ['', '.'], $data['valor_receita']);
		} else {
			$data['valor_receita'] = null;
		}

		if (strlen($data['valor_custo_fixo']) > 0) {
			$data['valor_custo_fixo'] = str_replace(['.', ','], ['', '.'], $data['valor_custo_fixo']);
		} else {
			$data['valor_custo_fixo'] = null;
		}

		if (strlen($data['valor_custo_variavel']) > 0) {
			$data['valor_custo_variavel'] = str_replace(['.', ','], ['', '.'], $data['valor_custo_variavel']);
		} else {
			$data['valor_custo_variavel'] = null;
		}

		if (strlen($data['valor_custo_total']) > 0) {
			$data['valor_custo_total'] = str_replace(['.', ','], ['', '.'], $data['valor_custo_total']);
		} else {
			$data['valor_custo_total'] = null;
		}

		if (strlen($data['valor_resultado']) > 0) {
			$data['valor_resultado'] = str_replace(['.', ','], ['', '.'], $data['valor_resultado']);
		} else {
			$data['valor_resultado'] = null;
		}

		if (strlen($data['resultado_percentual']) > 0) {
			$data['resultado_percentual'] = str_replace(',', '.', $data['resultado_percentual']);
		} else {
			$data['resultado_percentual'] = null;
		}

		$id = $data['id'];
		unset($data['id']);

		$this->db->trans_start();
		if ($id) {
			$this->db->update('alocacao_fechamento_mensal_emtu', $data, ['id' => $id]);
		} else {
			$this->db->insert('alocacao_fechamento_mensal_emtu', $data);
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Não foi possível salvar o fechamento mensal.']));
		}

		echo json_encode(['status' => true]);
	}


	public function excluirFechamentoMensalEMTU()
	{
		$id = $this->input->post('id');
		$this->db->trans_start();
		$this->db->delete('alocacao_fechamento_mensal_emtu', ['id' => $id]);
		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['erro' => 'Não foi possível excluir o fechamento mensal.']));
		}

		echo json_encode(['status' => true]);
	}


}
