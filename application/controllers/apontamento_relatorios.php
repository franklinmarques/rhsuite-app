<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento_relatorios extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
		date_default_timezone_set('America/Sao_Paulo');
	}

	public function index()
	{
		$this->gerenciar();
	}

	public function gerenciar($pdf = false)
	{
		$data = $this->input->get();

		$this->db->select('foto, foto_descricao');
		$this->db->where('id', $this->session->userdata('empresa'));
		$data['empresa'] = $this->db->get('usuarios')->row();

		$this->db->select('contrato AS nome');
		$this->db->where("DATE_FORMAT(data, '%Y-%m') <=", $data['ano'] . '-' . $data['mes']);
		if (isset($data['depto'])) {
			$this->db->where('depto', $data['depto']);
		} else {
			$data['depto'] = null;
		}
		if (isset($data['area'])) {
			$this->db->where('area', $data['area']);
		} else {
			$data['area'] = null;
		}
		if (isset($data['setor'])) {
			$this->db->where('setor', $data['setor']);
		} else {
			$data['setor'] = null;
		}
		$this->db->order_by('data', 'desc');
		$this->db->limit(1);
		$contrato = $this->db->get('alocacao')->row();


		$this->db->select('a.id, a.nome, a.depto, a.area, a.contrato, c.setor');
		if (!empty($contrato->nome)) {
			$this->db->select("'{$contrato->nome}' AS contrato", false);
		} else {
			$this->db->select('a.contrato');
		}
		$this->db->select('b.nome AS nome_usuario, b.depto AS depto_usuario, b.telefone, b.email');
		$this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
		$this->db->join('alocacao_unidades c', 'c.id_contrato = a.id');
		$this->db->join('alocacao_reajuste d', 'd.id_cliente = a.id');
		if ($data['depto']) {
			$this->db->where('a.depto', $data['depto']);
		}
		$data['postos'] = false;
		if ($data['area']) {
			$this->db->where('a.area', $data['area']);
			if (strpos($data['area'], 'Ipesp') !== false) {
				$data['postos'] = true;
			}
		}
		if ($data['setor']) {
			$this->db->where('c.setor', $data['setor']);
		}
		$data['contrato'] = $this->db->get('alocacao_contratos a')->row();


		$this->db->where("DATE_FORMAT(data, '%Y-%m') =", $data['ano'] . '-' . $data['mes']);
		if ($data['depto']) {
			$this->db->where('depto', $data['depto']);
		}
		if ($data['area']) {
			$this->db->where('setor', $data['setor']);
		}
		if ($data['setor']) {
			$this->db->where('setor', $data['setor']);
		}
		$alocacao = $this->db->get('alocacao')->row();

		$data['alocacao_observacoes'] = $alocacao->observacoes ?? '';


		$this->load->library('Calendar');
		$data['mes_nome'] = $this->calendar->get_month_name($data['mes']);
		$data['calculo_totalizacao'] = $data['calculo_totalizacao'] === '2' ? '2' : '1';
		$ajax_list = $this->ajax_list();
		$data['dias'] = $ajax_list['dias'];
		$data['apontamentos'] = $ajax_list['apontamentos'];
		$data['totalizacoes'] = $this->ajax_totalizacao();
		$data['observacoes'] = $this->ajax_observacoes();
		$data['servicos'] = $this->ajax_servicos($data['contrato']->id ?? null);
		$data['reajuste'] = $this->ajax_reajuste($data['contrato']->id ?? null);
		$data['is_pdf'] = $pdf;
		$_GET['valor_projetado'] = $data['reajuste']->valor_contratual;
		$_GET['valor_realizado'] = $data['reajuste']->total_liquido;
		$data['query_string'] = 'q?' . http_build_query($this->input->get());
		if ($pdf) {
			return $this->load->view('apontamento_relatorio', $data, true);
		} else {
			$this->load->view('apontamento_relatorio', $data);
		}
	}

	private function ajax_list()
	{
		$busca = $this->input->get();

		$this->db->select("CASE WHEN dia_fechamento > 0 THEN dia_fechamento END AS dia_fechamento", false);
		$this->db->where("DATE_FORMAT(data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
		$this->db->where('depto', $busca['depto'] ?? null);
		$this->db->where('area', $busca['area'] ?? null);
		$this->db->where('setor', $busca['setor'] ?? null);
		$alocacao = $this->db->get('alocacao')->row();
		$dia_fechamento = $alocacao->dia_fechamento ?? '';

		if (!empty($dia_fechamento)) {
			$sqlMesAno = "SELECT DATE_ADD(DATE_SUB(STR_TO_DATE('{$dia_fechamento}/{$busca['mes']}/{$busca['ano']}', '%d/%m/%Y'), INTERVAL 1 MONTH), INTERVAL 1 DAY) AS mes_ano";
			$mes_ano = $this->db->query($sqlMesAno)->row()->mes_ano;

			$dataAbertura = date('Y-m-d', strtotime(str_replace('/', '-', $mes_ano)));
			$dataFechamento = date('Y-m-d', strtotime("{$busca['ano']}-{$busca['mes']}-{$dia_fechamento}"));
			$view_alocacao = 'view_alocacao_consolidada';
		} else {
			$mes_ano = $busca['ano'] . '-' . $busca['mes'] . '-01';

			$dataAbertura = $mes_ano;
			$dataFechamento = date('Y-m-t', strtotime($mes_ano));
			$view_alocacao = 'view_alocacao';
		}


		$sqlDias = "SELECT GROUP_CONCAT(CONCAT(' dia_', DATE_FORMAT(x.daynum, '%d'))) AS titulo,
                           GROUP_CONCAT(CONCAT(' (SELECT IF(\'', x.daynum, '\' <= CURDATE(), IF(e.id IS NOT NULL AND (e.status = \'FR\' OR e.data <= CURDATE()), CONCAT( GROUP_CONCAT(
                                                        CASE WHEN e.qtde_dias > 0 THEN e.qtde_dias
                                                        WHEN TIME_TO_SEC(e.hora_atraso) > 0 THEN TIME_FORMAT(e.hora_atraso, \'%H:%i\')
                                                        ELSE null END
                                                    )), null), null) FROM alocacao_apontamento e
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

		$data['dias'] = array();
		foreach (explode(',', $dias->titulo) as $n => $dia) {
			$data['dias'][$n + 1] = trim(str_replace('dia_', '', $dia));
		}


		$sql = "SELECT s.id, 
                       s.nome,
                       s.nome_bck,
                       s.matricula,
                       s.login,
                       s.horario_trabalho,
                       s.nome_cargo,
                       {$dias->titulo},
                       IF(s.total_faltas > 0, s.total_faltas, null) AS total_faltas,
                       TIME_FORMAT(SEC_TO_TIME(s.total_atrasos), '%k:%i') AS total_atrasos,
                       s.data
                FROM (SELECT a.id, 
                             c.nome,
                             j.matricula,
                             j.login,
                             CONCAT_WS(' a ', TIME_FORMAT(j.horario_entrada, '%H:%ih'), TIME_FORMAT(j.horario_saida, '%H:%ih')) AS horario_trabalho,
                             c.cargo AS nome_cargo,
                             d.nome AS nome_bck,
                             {$dias->atributos},
                             (CASE WHEN h.data >= '{$dataAbertura}' THEN h.dias_faltas END) AS total_faltas,
                             (CASE WHEN h.data >= '{$dataAbertura}' THEN h.segundos_atraso END) AS total_atrasos,
                             b.data
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
                      LEFT JOIN alocacao_postos j ON 
                                j.id = h.id_posto
                      WHERE b.id_empresa = {$this->session->userdata('empresa')}";
		if (isset($busca['depto'])) {
			$sql .= " AND b.depto = '{$busca['depto']}'";
		}
		if (isset($busca['area'])) {
			$sql .= " AND b.area = '{$busca['area']}'";
		}
		if (isset($busca['setor'])) {
			$sql .= " AND b.setor = '{$busca['setor']}'";
		}
		if (isset($busca['cargo'])) {
			$sql .= " AND c.cargo = '{$busca['cargo']}'";
		}
		if (isset($busca['funcao'])) {
			$sql .= " AND c.funcao = '{$busca['funcao']}'";
		}
		$sql .= " AND a.nivel = 'P'
                  GROUP BY a.id) s
                WHERE s.data BETWEEN '{$dataAbertura}' AND '{$dataFechamento}' ORDER BY s.nome";
		$data['apontamentos'] = $this->db->query($sql)->result();

		return $data;
	}

	private function ajax_totalizacao()
	{
		$busca = $this->input->get();

		$this->db->select("CASE WHEN dia_fechamento > 0 THEN dia_fechamento END AS dia_fechamento", false);
		$this->db->where("DATE_FORMAT(data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
		$this->db->where('depto', $busca['depto'] ?? null);
		$this->db->where('area', $busca['area'] ?? null);
		$this->db->where('setor', $busca['setor'] ?? null);
		$alocacao = $this->db->get('alocacao')->row();
		$dia_fechamento = $alocacao->dia_fechamento ?? '';

		if (!empty($dia_fechamento)) {
			$sqlMesAno = "SELECT DATE_ADD(DATE_SUB(STR_TO_DATE('{$dia_fechamento}/{$busca['mes']}/{$busca['ano']}', '%d/%m/%Y'), INTERVAL 1 MONTH), INTERVAL 1 DAY) AS mes_ano";
			$mes_ano = $this->db->query($sqlMesAno)->row()->mes_ano;

			$dataAbertura = date('Y-m-d', strtotime(str_replace('/', '-', $mes_ano)));
			$dataFechamento = date('Y-m-d', strtotime("{$busca['ano']}-{$busca['mes']}-{$dia_fechamento}"));
			$view_alocacao = 'view_alocacao_consolidada';
		} else {
			$mes_ano = $busca['ano'] . '-' . $busca['mes'] . '-01';

			$dataAbertura = $mes_ano;
			$dataFechamento = date('Y-m-t', strtotime($mes_ano));
			$view_alocacao = 'view_alocacao';
		}


//        $sql = "SELECT s.id, 
//                       s.nome,
//                       s.dias_faltas,
//                       (s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)) AS perc_dias_faltas,
//                       IF(TIME_TO_SEC(s.horas_atraso) > 0, s.horas_atraso, NULL) AS horas_atraso,
//                       (IF(TIME_TO_SEC(s.horas_atraso) > 0, s.horas_atraso, NULL) * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)) AS perc_horas_atraso,
//                       FORMAT(s.valor_posto, 2, 'de_DE') AS valor_posto,
//                       FORMAT(s.valor_dia, 2, 'de_DE') AS valor_dia,
//                       FORMAT(s.valor_dia * NULLIF(s.dias_faltas, 0), 2, 'de_DE') AS glosa_dia,
//                       FORMAT(s.valor_posto * (s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)) / 100, 2, 'de_DE') AS perc_glosa_dia,
//                       FORMAT(s.valor_hora, 2, 'de_DE') AS valor_hora,
//                       FORMAT(s.valor_hora * NULLIF(s.minutos_atraso, 0), 2, 'de_DE') AS glosa_hora,
//                       FORMAT(s.valor_posto * (s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)) / 100, 2, 'de_DE') AS perc_glosa_hora,
//                       FORMAT(s.valor_posto - (IFNULL(s.dias_faltas, 0) * s.valor_dia + IFNULL(s.minutos_atraso, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0), 2, 'de_DE') AS valor_total,
//                       FORMAT(s.valor_posto * (1 - (IFNULL(FLOOR(s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)), 0) + IFNULL(FLOOR(s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)), 0)) / 100), 2, 'de_DE') AS valor_total_2,
//                       s.dias_acrescidos,
//                       s.horas_acrescidas,
//                       s.total_acrescido
//                FROM (SELECT a.id, 
//                             d.nome, 
//                             b.dias_faltas,
//                             TIME_FORMAT(SEC_TO_TIME(b.segundos_atraso), '%k:%i') AS horas_atraso,
//                             b.segundos_atraso / 3600 AS minutos_atraso,
//                             e.valor_posto, 
//                             e.valor_dia, 
//                             e.total_dias_mensais,
//                             e.valor_hora,
//                             e.total_horas_diarias,
//                             SUM(a.dias_acrescidos) AS dias_acrescidos,
//                             SUM(a.horas_acrescidas) AS horas_acrescidas,
//                             SUM(a.total_acrescido) AS total_acrescido
//                      FROM alocacao_usuarios a
//                      INNER JOIN alocacao c ON 
//                                c.id = a.id_alocacao
//                      INNER JOIN usuarios d ON 
//                                d.id = a.id_usuario 
//                      LEFT JOIN {$view_alocacao} b ON 
//                                b.id = a.id AND 
//                                b.data <= '{$dataFechamento}'
//                      LEFT JOIN alocacao_postos e ON 
//                                e.id = b.id_posto
//                      WHERE c.id_empresa = {$this->session->userdata('empresa')} AND 
//                            c.data BETWEEN '{$dataAbertura}' AND '{$dataFechamento}'";
//        if (isset($busca['depto'])) {
//            $sql .= " AND c.depto = '{$busca['depto']}'";
//        }
//        if (isset($busca['area'])) {
//            $sql .= " AND c.area = '{$busca['area']}'";
//        }
//        if (isset($busca['setor'])) {
//            $sql .= " AND c.setor = '{$busca['setor']}'";
//        }
//        if (isset($busca['cargo'])) {
//            $sql .= " AND d.cargo = '{$busca['cargo']}'";
//        }
//        if (isset($busca['funcao'])) {
//            $sql .= " AND d.funcao = '{$busca['funcao']}'";
//        }
//        $sql .= ' GROUP BY a.id_usuario) s ORDER BY s.nome';
		$sql = "SELECT s.id, 
                       s.nome,
                       s.dias_faltas,
                       (s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)) AS perc_dias_faltas,
                       s.horas_atraso,
                       (s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)) AS perc_horas_atraso,
                       FORMAT(s.valor_posto, 2, 'de_DE') AS valor_posto,
                       FORMAT(s.valor_dia, 2, 'de_DE') AS valor_dia,
                       FORMAT(s.valor_dia * NULLIF(s.dias_faltas, 0), 2, 'de_DE') AS glosa_dia,
                       FORMAT(s.valor_posto * (s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)) / 100, 2, 'de_DE') AS perc_glosa_dia,
                       FORMAT(s.valor_hora, 2, 'de_DE') AS valor_hora,
                       FORMAT(s.valor_hora * NULLIF(s.minutos_atraso, 0), 2, 'de_DE') AS glosa_hora,
                       FORMAT(s.valor_posto * (s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)) / 100, 2, 'de_DE') AS perc_glosa_hora,
                       FORMAT(s.valor_posto - (IFNULL(s.dias_faltas, 0) * s.valor_dia + IFNULL(s.minutos_atraso, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0), 2, 'de_DE') AS valor_total,
                       FORMAT(s.valor_posto * (1 - (IFNULL(s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0), 0) + IFNULL(s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0), 0)) / 100) + IFNULL(s.total_acrescido, 0), 2, 'de_DE') AS valor_total_2,
                       s.dias_acrescidos,
                       s.horas_acrescidas,
                       s.total_acrescido
                FROM (SELECT a.id,
                             d.nome,
                             b.dias_faltas,
                             TIME_FORMAT(SEC_TO_TIME(b.segundos_atraso), '%k:%i') AS horas_atraso,
                             b.segundos_atraso / 3600 AS minutos_atraso,
                             e.valor_posto, 
                             e.valor_dia, 
                             e.total_dias_mensais,
                             e.valor_hora,
                             e.total_horas_diarias,
                             SUM(a.dias_acrescidos) AS dias_acrescidos,
                             SUM(a.horas_acrescidas) AS horas_acrescidas,
                             SUM(a.total_acrescido) AS total_acrescido
                      FROM alocacao_usuarios a
                      INNER JOIN alocacao c ON 
                                c.id = a.id_alocacao
                      INNER JOIN usuarios d ON 
                                d.id = a.id_usuario 
                      LEFT JOIN {$view_alocacao} b ON 
                                b.id = a.id AND 
                                b.data <= '{$dataFechamento}'
                      LEFT JOIN alocacao_postos e ON 
                                e.id = b.id_posto
                      WHERE c.id_empresa = {$this->session->userdata('empresa')} AND 
                            c.data BETWEEN '{$dataAbertura}' AND '{$dataFechamento}'";
		if (isset($busca['depto'])) {
			$sql .= " AND c.depto = '{$busca['depto']}'";
		}
		if (isset($busca['area'])) {
			$sql .= " AND c.area = '{$busca['area']}'";
		}
		if (isset($busca['setor'])) {
			$sql .= " AND c.setor = '{$busca['setor']}'";
		}
		if (isset($busca['cargo'])) {
			$sql .= " AND d.cargo = '{$busca['cargo']}'";
		}
		if (isset($busca['funcao'])) {
			$sql .= " AND d.funcao = '{$busca['funcao']}'";
		}
		$sql .= ' GROUP BY a.id_usuario ORDER BY d.nome ASC) s';
		$data = $this->db->query($sql)->result();

		return $data;
	}

	public function ajax_servicos($id_contrato = null)
	{
		$depto = $this->input->get('depto');
		$area = $this->input->get('area');
		$setor = $this->input->get('setor');
		$mes = $this->input->get('mes');
		$ano = $this->input->get('ano');

		$this->db->select('b.contrato AS nome, a.descricao_servico, a.valor_servico');
		$this->db->join('alocacao_contratos b', 'b.contrato = a.contrato', 'left');
		$this->db->where("DATE_FORMAT(a.data, '%Y-%m') <=", $ano . '-' . $mes);
		if ($depto) {
			$this->db->where('a.depto', $depto);
		}
		if ($area) {
			$this->db->where('a.area', $area);
		}
		if ($setor) {
			$this->db->where('a.setor', $setor);
		}
		$this->db->order_by('a.data', 'desc');
		$this->db->limit(1);
		$contrato = $this->db->get('alocacao a')->row();

//var_dump([$contrato->nome ?? 'x', $id_contrato]);exit;
		$this->db->select('id, NULL AS compartilhados, NULL AS nao_compartilhados, NULL AS total', false);
		if (!empty($contrato->nome)) {
			$this->db->where('contrato', $contrato->nome);
//			$this->db->where('id', $id_contrato);
		} else {
			$this->db->where('id', $id_contrato);
		}
		$data = $this->db->get('alocacao_contratos')->row();

		if ($data) {
			$data->compartilhados = array();
			$data->nao_compartilhados = array();

			$this->db->select('a.tipo, a.descricao, a.valor');
			$this->db->where('a.id_contrato', $data->id);
			$this->db->where('a.data_reajuste', "(SELECT MAX(a2.data_reajuste) FROM alocacao_servicos a2 WHERE DATE_FORMAT(a2.data_reajuste, '%Y-%m') <= '{$ano}-{$mes}')", false);
			$rows = $this->db->get('alocacao_servicos a')->result();

			$possuiDescricaoServico = !empty($contrato->descricao_servico) and !empty($contrato->valor_servico);

			if ($possuiDescricaoServico) {
				$row2 = new stdClass();
				$row2->tipo = '0';
				$row2->descricao = $contrato->descricao_servico;
				$row2->valor = $contrato->valor_servico;

				$data->nao_compartilhados[] = $row2;
			}

			foreach ($rows as $row) {
				if ($row->tipo === '1') {
					$data->compartilhados[] = $row;
					$data->total += $row->valor;
//                } elseif ($row->tipo === '0') {
				} elseif (!$possuiDescricaoServico) {
					$data->nao_compartilhados[] = $row;
				}
			}
		} else {
			$data = new stdClass();
			$data->compartilhados = [];
			$data->nao_compartilhados = [];
			$data->total = null;
		}


		return $data;
	}

	public function ajax_reajuste($id_contrato = null)
	{
		$busca = $this->input->get();

		$this->db->select('contrato AS nome');
		$this->db->select("CASE WHEN dia_fechamento > 0 THEN dia_fechamento END AS dia_fechamento", false);
		$this->db->where("DATE_FORMAT(data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
		$this->db->where('depto', $busca['depto'] ?? null);
		$this->db->where('area', $busca['area'] ?? null);
		$this->db->where('setor', $busca['setor'] ?? null);
		$contrato = $this->db->get('alocacao')->row();
		$dia_fechamento = $contrato->dia_fechamento ?? '';

		if (!empty($dia_fechamento)) {
			$sqlMesAno = "SELECT DATE_ADD(DATE_SUB(STR_TO_DATE('{$dia_fechamento}/{$busca['mes']}/{$busca['ano']}', '%d/%m/%Y'), INTERVAL 1 MONTH), INTERVAL 1 DAY) AS mes_ano";
			$mes_ano = $this->db->query($sqlMesAno)->row()->mes_ano;

			$dataAbertura = date('Y-m-d', strtotime(str_replace('/', '-', $mes_ano)));
			$dataFechamento = date('Y-m-d', strtotime("{$busca['ano']}-{$busca['mes']}-{$dia_fechamento}"));
			$view_alocacao = 'view_alocacao_consolidada';
		} else {
			$mes_ano = $busca['ano'] . '-' . $busca['mes'] . '-01';

			$dataAbertura = $mes_ano;
			$dataFechamento = date('Y-m-t', strtotime($mes_ano));
			$view_alocacao = 'view_alocacao';
		}

//        $this->db->select('contrato AS nome');
//        $this->db->where("DATE_FORMAT(data, '%Y-%m') <=", $busca['ano'] . '-' . $busca['mes']);
//        if (isset($busca['depto'])) {
//            $this->db->where('depto', $busca['depto']);
//        }
//        if (isset($busca['area'])) {
//            $this->db->where('area', $busca['area']);
//        }
//        if (isset($busca['setor'])) {
//            $this->db->where('setor', $busca['setor']);
//        }
//        $this->db->order_by('data', 'desc');
//        $this->db->limit(1);
//        $contrato = $this->db->get('alocacao')->row();

		if (!empty($contrato->nome)) {
			$this->db->select('id');
			$this->db->where('contrato', $contrato->nome);
			$row_contrato = $this->db->get('alocacao_contratos')->row();

			$id_contrato = $row_contrato->id ?? NULL;
		}

//        $sql = "SELECT SUM(s.valor_posto) AS valor_contratual,
//                       SUM(s.valor_posto - (IFNULL(s.dias_faltas, 0) * s.valor_dia + IFNULL(s.minutos_atraso, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0)) AS total_liquido,
//                       SUM(s.valor_posto * (1 - (IFNULL(FLOOR(s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)), 0) + IFNULL(FLOOR(s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)), 0)) / 100)) AS total_liquido_2,
//                       NULL AS indices,
//                       NULL AS indices_2,
//                       (SELECT SUM(t.valor) 
//                        FROM alocacao_servicos t 
//                        WHERE t.id_contrato = '$id_contrato' AND 
//                              t.tipo = 1) AS total_servicos
//                FROM (SELECT b.dias_faltas,
//                             b.segundos_atraso / 3600 AS minutos_atraso,
//                             e.valor_posto, 
//                             e.valor_dia,
//                             e.total_dias_mensais,
//                             e.valor_hora,
//                             e.total_horas_diarias,
//                             SUM(a.dias_acrescidos) AS dias_acrescidos,
//                             SUM(a.horas_acrescidas) AS horas_acrescidas,
//                             SUM(a.total_acrescido) AS total_acrescido
//                      FROM alocacao_usuarios a
//                      INNER JOIN alocacao c ON 
//                                c.id = a.id_alocacao
//                      INNER JOIN usuarios d ON 
//                                d.id = a.id_usuario 
//                      LEFT JOIN {$view_alocacao} b ON 
//                                b.id = a.id AND 
//                                b.data <= '{$dataFechamento}'
//                      LEFT JOIN alocacao_postos e ON 
//                                e.id_usuario = d.id AND 
//                                e.data = (SELECT MAX(g.data) 
//                                          FROM alocacao_postos g 
//                                          WHERE g.id_usuario = e.id_usuario AND 
//                                                DATE_FORMAT(g.data, '%Y-%m') <= '{$busca['ano']}-{$busca['mes']}')
//                      WHERE c.id_empresa = {$this->session->userdata('empresa')} AND 
//                            c.data BETWEEN '{$dataAbertura}' AND '{$dataFechamento}'";
//        if (isset($busca['depto'])) {
//            $sql .= " AND d.depto = '{$busca['depto']}'";
//        }
//        if (isset($busca['area'])) {
//            $sql .= " AND d.area = '{$busca['area']}'";
//        }
//        if (isset($busca['setor'])) {
//            $sql .= " AND d.setor = '{$busca['setor']}'";
//        }
//        if (isset($busca['cargo'])) {
//            $sql .= " AND d.cargo = '{$busca['cargo']}'";
//        }
//        if (isset($busca['funcao'])) {
//            $sql .= " AND d.funcao = '{$busca['funcao']}'";
//        }
//        $sql .= ' GROUP BY a.id_usuario) s';

		$sql = "SELECT (CASE s.area WHEN 'Ipesp' 
                             THEN (SELECT IFNULL(SUM(t.valor), 0) 
                                   FROM alocacao_servicos t 
                                   WHERE t.id_contrato = '$id_contrato' AND 
                                         t.data_reajuste = (SELECT MAX(t2.data_reajuste) 
                                                            FROM alocacao_servicos t2
                                                            WHERE t2.id_contrato = '$id_contrato' AND 
                                                                  DATE_FORMAT(t2.data_reajuste, '%Y-%m') <= '{$busca['ano']}-{$busca['mes']}') AND
                                         t.tipo = 1)
                             ELSE 0 END) + SUM(s.valor_posto) AS valor_contratual,
                       SUM(s.valor_posto - (IFNULL(s.dias_faltas, 0) * s.valor_dia + IFNULL(s.minutos_atraso, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0)) AS total_liquido,
                       SUM(s.valor_posto * (1 - (IFNULL(s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0), 0) + IFNULL(s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0), 0)) / 100) + IFNULL(s.total_acrescido, 0)) AS total_liquido_2,
                       SUM(s.valor_posto * (1 - (IFNULL(FLOOR(s.dias_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)), 0) + IFNULL(FLOOR(s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)), 0)) / 100) + IFNULL(s.total_acrescido, 0)) AS total_liquido_3,
                       NULL AS indices,
                       NULL AS indices_2,
                       (SELECT SUM(t.valor) 
                        FROM alocacao_servicos t 
                        WHERE t.id_contrato = '$id_contrato' AND 
                              t.data_reajuste = (SELECT MAX(t2.data_reajuste) 
                                                            FROM alocacao_servicos t2
                                                            WHERE t2.id_contrato = '$id_contrato' AND 
                                                                  DATE_FORMAT(t2.data_reajuste, '%Y-%m') <= '{$busca['ano']}-{$busca['mes']}') AND
                              t.tipo = 1) AS total_servicos
                FROM (SELECT a.id,
                             d.nome,
                             c.area,
                             b.dias_faltas,
                             TIME_FORMAT(SEC_TO_TIME(b.segundos_atraso), '%k:%i') AS horas_atraso,
                             b.segundos_atraso / 3600 AS minutos_atraso,
                             e.valor_posto, 
                             e.valor_dia, 
                             e.total_dias_mensais,
                             e.valor_hora,
                             e.total_horas_diarias,
                             SUM(a.dias_acrescidos) AS dias_acrescidos,
                             SUM(a.horas_acrescidas) AS horas_acrescidas,
                             SUM(a.total_acrescido) AS total_acrescido
                      FROM alocacao_usuarios a
                      INNER JOIN alocacao c ON 
                                c.id = a.id_alocacao
                      INNER JOIN usuarios d ON 
                                d.id = a.id_usuario 
                      LEFT JOIN {$view_alocacao} b ON 
                                b.id = a.id AND 
                                b.data <= '{$dataFechamento}'
                      LEFT JOIN alocacao_postos e ON 
                                e.id = b.id_posto
                      WHERE c.id_empresa = {$this->session->userdata('empresa')} AND 
                            c.data BETWEEN '{$dataAbertura}' AND '{$dataFechamento}'";
		if (isset($busca['depto'])) {
			$sql .= " AND c.depto = '{$busca['depto']}'";
		}
		if (isset($busca['area'])) {
			$sql .= " AND c.area = '{$busca['area']}'";
		}
		if (isset($busca['setor'])) {
			$sql .= " AND c.setor = '{$busca['setor']}'";
		}
		if (isset($busca['cargo'])) {
			$sql .= " AND d.cargo = '{$busca['cargo']}'";
		}
		if (isset($busca['funcao'])) {
			$sql .= " AND d.funcao = '{$busca['funcao']}'";
		}
		$sql .= ' GROUP BY a.id_usuario ORDER BY d.nome ASC) s';
		$data = $this->db->query($sql)->row();

		$this->db->select("DATE_FORMAT(a.data_reajuste, 'DIA %d/%m/%Y') AS data_reajuste, a.valor_indice, NULL AS valor_reajuste, NULL AS valor_reajuste_2", false);
		$this->db->join('alocacao_contratos b', 'b.id = a.id_cliente');
		$this->db->where('b.id', $id_contrato);
//        $this->db->where('a.data_reajuste <', $dataAbertura);
		$this->db->where('a.data_reajuste <=', $dataFechamento);
		$this->db->limit(5);
		$rows = $this->db->get('alocacao_reajuste a')->result();


		$total = $data->total_liquido + $data->total_servicos;
		$total2 = $data->total_liquido_2 + $data->total_servicos;
		foreach ($rows as $row) {
			if ($data->total_liquido && $row->valor_indice) {
				$total += ($row->valor_indice / 100 * $total);
			}
			$row->valor_reajuste = $total;

			if ($data->total_liquido_2 && $row->valor_indice) {
				$total2 += ($row->valor_indice / 100 * $total2);
			}

			$row->valor_indice = round($row->valor_indice, 8) . '%';
			$row->valor_reajuste_2 = $total2;
		}

		$data->indices = $rows;
		$data->valor_total = $total;
		$data->valor_total_2 = $total2;

		return $data;
	}

	private function ajax_observacoes()
	{
		$busca = $this->input->get();

		$sql = "SELECT c.nome,
                       a.evento,
                       DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio,
                       DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino,
                       d.nome AS nome_bck
                FROM (SELECT x.id_alocacao,
                             x.id_usuario,
                             (CASE x.tipo_bck WHEN 'A' 
                                   THEN 'Afastamento'
                                   ELSE 'Férias' END) AS evento,
                             x.data_recesso AS data_inicio,
                             x.data_retorno AS data_termino,
                             x.id_usuario_bck
                      FROM alocacao_usuarios x
                      WHERE x.tipo_bck IN('A', 'F')
                      UNION
                      SELECT y.id_alocacao,
                             y.id_usuario,
                             'Substituição' AS evento,
                             y.data_desligamento AS data_inicio,
                             NULL AS data_termino,
                             y.id_usuario_sub AS id_usuario_bck
                      FROM alocacao_usuarios y
                      WHERE y.data_desligamento IS NOT NULL) a
                INNER JOIN alocacao b ON
                           b.id = a.id_alocacao
                INNER JOIN usuarios c ON
                           c.id = a.id_usuario
                LEFT JOIN usuarios d ON
                          d.id = a.id_usuario_bck
                WHERE b.id_empresa = {$this->session->userdata('empresa')} AND
                      DATE_FORMAT(b.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
		if (isset($busca['depto'])) {
			$sql .= " AND b.depto = '{$busca['depto']}'";
		}
		if (isset($busca['area'])) {
			$sql .= " AND b.area = '{$busca['area']}'";
		}
		if (isset($busca['setor'])) {
			$sql .= " AND b.setor = '{$busca['setor']}'";
		}
		$sql .= ' ORDER BY c.nome ASC';
		$data = $this->db->query($sql)->result();
		$this->db->protect_identifiers = true;

		return $data;
	}

	public function pdf()
	{
		$this->load->library('m_pdf');

		$stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
		$stylesheet .= '#apontamento { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#apontamento thead th { font-size: 12px; padding: 4px; background-color: #DFF0D8; border: 1px solid #444; } ';
		$stylesheet .= '#apontamento thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#apontamento tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#totalizacao { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#servicos { border: 1px solid #444; } ';
		$stylesheet .= '#totalizacao thead th, #servicos thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#totalizacao tbody td, #servicos tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#reajuste { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#reajuste thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#reajuste tbody td { font-size: 12px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
		$stylesheet .= '#reajuste tbody tr:nth-child(8) td { font-size: 13px; padding: 5px; font-weight: bold; background-color: #f5f5f5; } ';

		$stylesheet .= '#observacoes { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#observacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#observacoes thead td, #observacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$this->m_pdf->pdf->setTopMargin(38);
		$this->m_pdf->pdf->AddPage('L');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->gerenciar(true));

		$data = $this->input->get();

		$this->db->select('contrato AS nome');
		$this->db->where("DATE_FORMAT(data, '%Y-%m') <=", $data['ano'] . '-' . $data['mes']);
		if (!empty($data['depto'])) {
			$this->db->where('depto', $data['depto']);
		}
		if (!empty($data['area'])) {
			$this->db->where('area', $data['area']);
		}
		if (!empty($data['setor'])) {
			$this->db->where('setor', $data['setor']);
		}
		$this->db->order_by('data', 'desc');
		$this->db->limit(1);
		$contrato = $this->db->get('alocacao')->row();

		$this->db->select('a.nome, a.contrato, c.setor');
		$this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
		$this->db->join('alocacao_unidades c', 'c.id_contrato = a.id');
		$this->db->join('alocacao_reajuste d', 'd.id_cliente = a.id');
		if (!empty($contrato->nome)) {
			$this->db->where('a.contrato', $contrato->nome);
		} else {
			if (!empty($data['depto'])) {
				$this->db->where('a.depto', $data['depto']);
			}
			if (!empty($data['area'])) {
				$this->db->where('a.area', $data['area']);
			}
			if (!empty($data['setor'])) {
				$this->db->where('c.setor', $data['setor']);
			}
		}
		$row = $this->db->get('alocacao_contratos a')->row_array();
		$nome = 'Apontamento';
		if ($row) {
			$nome = implode('-', $row);
		}
		$nome .= date('_m-Y', mktime(0, 0, 0, $data['mes'], 1, $data['ano']));

		$this->m_pdf->pdf->Output($nome . '.pdf', 'D');
	}


	public function atividades_mensais($pdf = false)
	{
		if ($pdf !== true) {
			$pdf = false;
		}

		$get = $this->input->get();

		$this->db->query("SET lc_time_names = 'pt_BR'");
		$this->db->select("a.*, DATE_FORMAT(b.data, '%m') AS mes", false);
		$this->db->join('alocacao b', 'b.id = a.id_alocacao');
		$this->db->where('b.depto', $get['depto']);
		$this->db->where('b.area', $get['area']);
		$this->db->where('b.setor', $get['setor']);
		$this->db->where("DATE_FORMAT(b.data, '%Y') =", $get['ano']);
		$this->db->order_by('b.data', 'asc');
		$rows = $this->db->get('alocacao_observacoes a')->result();

		$mesesVazios = array(
			'01' => null,
			'02' => null,
			'03' => null,
			'04' => null,
			'05' => null,
			'06' => null,
			'07' => null,
			'08' => null,
			'09' => null,
			'10' => null,
			'11' => null,
			'12' => null
		);

		$alocacao_observacoes = $this->db->list_fields('alocacao_observacoes');
		unset($alocacao_observacoes['id'], $alocacao_observacoes['id_alocacao']);
		$data = array_flip($alocacao_observacoes);

		foreach ($data as $k => $field) {
			$data[$k] = $mesesVazios;
		}

		$this->db->select('foto, foto_descricao');
		$this->db->where('id', $this->session->userdata('empresa'));
		$data['empresa'] = $this->db->get('usuarios')->row();

		$data['total_meses'] = 14;

		$data['departamento'] = $get['depto'];
		$data['area'] = $get['area'];
		$data['setor'] = $get['setor'];

		$data['meses'] = array(
			'01' => 'Jan',
			'02' => 'Fev',
			'03' => 'Mar',
			'04' => 'Abr',
			'05' => 'Mai',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Ago',
			'09' => 'Set',
			'10' => 'Out',
			'11' => 'Nov',
			'12' => 'Dez'
		);


		$data['meses_completo'] = array(
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


		$data['ano'] = $get['ano'];
		$data['is_pdf'] = $pdf;
		$data['query_string'] = http_build_query($get);
		$data['modo'] = 'normal';


		foreach ($rows as $row) {
			$mes = $row->mes;
			$data['total_colaboradores_contratados'][$mes] = $row->total_colaboradores_contratados;
			$data['total_colaboradores_ativos'][$mes] = $row->total_colaboradores_ativos;
			$data['visitas_projetadas'][$mes] = $row->visitas_projetadas;
			$data['visitas_realizadas'][$mes] = $row->visitas_realizadas;
			$data['visitas_porcentagem'][$mes] = $row->visitas_porcentagem;
			$data['visitas_total_horas'][$mes] = $row->visitas_total_horas;
			$data['balanco_valor_projetado'][$mes] = number_format($row->balanco_valor_projetado, 2, ',', '.');
			$data['balanco_glosas'][$mes] = number_format($row->balanco_glosas, 2, ',', '.');
			$data['balanco_valor_glosa'][$mes] = number_format($row->balanco_valor_glosa, 2, ',', '.');
			$data['balanco_porcentagem'][$mes] = number_format($row->balanco_porcentagem, 1, ',', '');
			$data['turnover_admissoes'][$mes] = $row->turnover_admissoes;
			$data['turnover_demissoes'][$mes] = $row->turnover_demissoes;
			$data['turnover_desligamentos'][$mes] = $row->turnover_desligamentos;
			$data['atendimentos_total_mes'][$mes] = $row->atendimentos_total_mes;
			$data['atendimentos_media_diaria'][$mes] = $row->atendimentos_media_diaria;
			$data['pendencias_total_informada'][$mes] = $row->pendencias_total_informada;
			$data['pendencias_aguardando_tratativa'][$mes] = $row->pendencias_aguardando_tratativa;
			$data['pendencias_parcialmente_resolvidas'][$mes] = $row->pendencias_parcialmente_resolvidas;
			$data['pendencias_resolvidas'][$mes] = $row->pendencias_resolvidas;
			$data['pendencias_resolvidas_atendimentos'][$mes] = $row->pendencias_resolvidas_atendimentos;
			$data['monitoria_media_equipe'][$mes] = $row->monitoria_media_equipe;
			$data['indicadores_operacionais_tma'][$mes] = $row->indicadores_operacionais_tma;
			$data['indicadores_operacionais_tme'][$mes] = $row->indicadores_operacionais_tme;
			$data['indicadores_operacionais_ociosidade'][$mes] = $row->indicadores_operacionais_ociosidade;
			$data['avaliacoes_atendimento'][$mes] = $row->avaliacoes_atendimento;
			$data['avaliacoes_atendimento_otimos'][$mes] = $row->avaliacoes_atendimento_otimos;
			$data['avaliacoes_atendimento_bons'][$mes] = $row->avaliacoes_atendimento_bons;
			$data['avaliacoes_atendimento_regulares'][$mes] = $row->avaliacoes_atendimento_regulares;
			$data['avaliacoes_atendimento_ruins'][$mes] = $row->avaliacoes_atendimento_ruins;
			$data['solicitacoes'][$mes] = $row->solicitacoes;
			$data['solicitacoes_atendidas'][$mes] = $row->solicitacoes_atendidas;
			$data['solicitacoes_nao_atendidas'][$mes] = $row->solicitacoes_nao_atendidas;
			$data['observacoes'][$mes] = $row->observacoes;
		}


		$this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(a.indicadores_operacionais_tma))) AS tma', false);
		$this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(a.indicadores_operacionais_tme))) AS tme', false);
		$this->db->select('SEC_TO_TIME(SUM(TIME_TO_SEC(a.indicadores_operacionais_ociosidade))) AS ociosidade', false);
		$this->db->join('alocacao b', 'b.id = a.id_alocacao');
		$this->db->where('b.depto', $get['depto']);
		$this->db->where('b.area', $get['area']);
		$this->db->where('b.setor', $get['setor']);
		$this->db->where("DATE_FORMAT(b.data, '%Y') =", $get['ano']);
		$this->db->order_by('b.data', 'asc');
		$data['total_indicadores_operacionais'] = $this->db->get('alocacao_observacoes a')->row();


		if ($pdf) {
			return $this->load->view('apontamento_atividades_mensais', $data, true);
		} else {
			$this->load->view('apontamento_atividades_mensais', $data);
		}

	}

	public function pdfAtividades_mensais()
	{
		$this->load->library('m_pdf');

		$stylesheet = '#rh thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
		$stylesheet .= '#rh { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#rh thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#rh tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#visitas_periodicas { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#visitas_periodicas thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#visitas_periodicas tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#disponibilidade { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#disponibilidade thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#disponibilidade tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#turnover { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#turnover thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#turnover tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#atendimentos { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#atendimentos thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#atendimentos tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#pendencias { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#pendencias thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#pendencias tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#monitoria { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#monitoria thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#monitoria tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#indicadores_operacionais { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#indicadores_operacionais thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#indicadores_operacionais tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#pesquisa_satisfacao { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#pesquisa_satisfacao thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#pesquisa_satisfacao tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$stylesheet .= '#observacoes { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#observacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#observacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$this->m_pdf->pdf->setTopMargin(38);
		$this->m_pdf->pdf->AddPage('L');
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->atividades_mensais(true));

		$data = $this->input->get();

		$this->load->library('Calendar');
		$this->calendar->month_type = 'short';
		$nome = 'Serviços Terceirizados - Controle de Atividades Mensais ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

		$this->m_pdf->pdf->Output($nome . '.pdf', 'D');
	}


	public function fechamentoMensal()
	{
		$data = $this->montarFechamentoMensal();

		$this->load->view('apontamento_fechamento_mensal', $data);
	}


	private function montarFechamentoMensal()
	{
		$dia = $this->input->get('dia_fechamento');
		$mes = $this->input->get('mes');
		$ano = $this->input->get('ano');
		$mostrarColaboradores = $this->input->get('mostrar_colaborador');
		if ($dia) {
			$dataTermino = date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $ano));
		} else {
			$dataTermino = date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano));
		}
		$sql = "SELECT DATE_ADD(DATE_SUB(STR_TO_DATE('{$dataTermino}', '%Y-%m-%d'), INTERVAL 1 MONTH), INTERVAL 1 DAY) AS data_inicio";
		$dataInicio = $this->db->query($sql)->row()->data_inicio;

		$mesAnoAnterior = date('Y-m', strtotime($dataInicio));
		$mesAnoAtual = date('Y-m', strtotime($dataTermino));

		$rows = $this->db
			->select('c.nome')
			->select('SUM(d.qtde_req) AS qtde_req', false)
			->select('SUM(d.qtde_rev) AS qtde_rev', false)
			->join('alocacao_usuarios b', 'b.id_alocacao = a.id')
			->join('usuarios c', 'c.id = b.id_usuario')
			->join('alocacao_apontamento d', "d.id_alocado = b.id and d.data BETWEEN '{$dataInicio}' AND '{$dataTermino}'", 'left')
			->where('a.id_empresa', $this->session->userdata('empresa'))
			->where('a.depto', $this->input->get('depto'))
			->where('a.area', $this->input->get('area'))
			->where('a.setor', $this->input->get('setor'))
			->where("DATE_FORMAT(a.data, '%Y-%m') IN ('{$mesAnoAnterior}', '{$mesAnoAtual}')")
			->group_by('c.id')
			->order_by('c.nome', 'asc')
			->get('alocacao a')
			->result();

		$alocacaoAtual = $this->db
			->select('contrato, setor')
			->where('id_empresa', $this->session->userdata('empresa'))
			->where('depto', $this->input->get('depto'))
			->where('area', $this->input->get('area'))
			->where('setor', $this->input->get('setor'))
			->where("DATE_FORMAT(data, '%Y-%m') = '{$mesAnoAtual}'")
			->get('alocacao')
			->row();

		$contrato = $this->db
			->select('a.contrato, b.setor')
			->select('(SELECT MAX(c.valor_indice) FROM alocacao_reajuste c WHERE c.id_cliente = a.id ORDER BY c.data_reajuste DESC) AS valor_indice', false)
			->join('alocacao_unidades b', 'b.id_contrato = a.id')
			->where('a.id_empresa', $this->session->userdata('empresa'))
			->where('a.depto', $this->input->get('depto'))
			->where('a.area', $this->input->get('area'))
			->where('b.setor', $this->input->get('setor'))
			->order_by('a.data_assinatura', 'desc')
			->limit(1)
			->get('alocacao_contratos a')
			->row();

		$this->load->library('Calendar');

		$data = [
			'rows' => $rows,
			'subtotal_req' => array_sum(array_column($rows ?? [], 'qtde_req')),
			'subtotal_rev' => array_sum(array_column($rows ?? [], 'qtde_rev')),
			'data_inicio' => date('d/m/Y', strtotime($dataInicio)),
			'data_termino' => date('d/m/Y', strtotime($dataTermino)),
			'empresa' => $this->db
				->select('foto, foto_descricao')
				->where('id', $this->session->userdata('empresa'))
				->get('usuarios')
				->row(),
			'contrato' => $alocacaoAtual->contrato ?? $contrato->contrato,
			'setor' => $alocacaoAtual->setor ?? $contrato->setor,
			'mostrarColaboradores' => $mostrarColaboradores,
			'valor_unitario' => number_format($contrato->valor_indice * 100, 2, ',', '.'),
			'query_string' => http_build_query($this->input->get() + ['mostrar_colaborador' => $mostrarColaboradores]),
			'is_pdf' => false
		];

		$data['total'] = $data['subtotal_req'] + $data['subtotal_rev'];
		$data['valor_faturamento'] = number_format($contrato->valor_indice * 100 * $data['total'], 2, ',', '.');

		return $data;
	}


	public function imprimirFechamentoMensal()
	{
		$data = $this->montarFechamentoMensal();

		$this->load->library('m_pdf');

		$stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
		$stylesheet .= '#fechamento_mensal { border: 1px solid #444; margin-bottom: 0px; } ';
		$stylesheet .= '#fechamento_mensal thead th { font-size: 12px; padding: 4px; background-color: #DFF0D8; border: 1px solid #444; } ';
		$stylesheet .= '#fechamento_mensal thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
		$stylesheet .= '#fechamento_mensal tbody td { font-size: 11px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
		$stylesheet .= '#fechamento_mensal tfoot th { font-size: 11px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

		$this->m_pdf->pdf->setTopMargin(38);
		$this->m_pdf->pdf->writeHTML($stylesheet, 1);
		$this->m_pdf->pdf->writeHTML($this->load->view('apontamento_fechamento_mensalPdf', $data, true));

		$this->load->library('calendar');

		$mes_ano = $this->calendar->get_month_name($this->input->get('mes')) . ' ' . $this->input->get('ano');

		$this->m_pdf->pdf->Output('Serviços Terceirizados - Relatório de Fechamento Mensal - ' . lcfirst($mes_ano) . '.pdf', 'D');
	}

}
