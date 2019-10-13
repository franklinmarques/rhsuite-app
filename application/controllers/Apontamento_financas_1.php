<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento_financas extends MY_Controller
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
        $data['depto'] = $this->input->get('depto');
        $data['area'] = $this->input->get('area');
        $data['setor'] = $this->input->get('setor');
        $data['cargo'] = $this->input->get('cargo');
        $data['funcao'] = $this->input->get('funcao');
        $data['mes'] = $this->input->get('mes');
        $data['ano'] = $this->input->get('ano');

        $this->db->select('a.id, a.nome, a.depto, a.area, a.contrato, c.setor');
        $this->db->select('b.nome AS nome_usuario, b.depto AS depto_usuario, b.telefone, b.email');
        $this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
        $this->db->join('alocacao_unidades c', 'c.id_contrato = a.id');
        $this->db->join('alocacao_reajuste d', 'd.id_cliente = a.id');
//        $this->db->where("DATE_FORMAT(a.data, '%m/%Y') =", "{$data['mes']}/{$data['ano']}");
        if (!empty($data['depto'])) {
            $this->db->where('a.depto', $data['depto']);
        }
        $data['postos'] = false;
        if (!empty($data['area'])) {
            $this->db->where('a.area', $data['area']);
            if (strpos($data['area'], 'Ipesp') !== false) {
                $data['postos'] = true;
            }
        }
        if (!empty($data['setor'])) {
            $this->db->where('c.setor', $data['setor']);
        }
        $data['contrato'] = $this->db->get('alocacao_contratos a')->row();

        $data['dias'] = date('t', mktime(0, 0, 0, $data['mes'], 1, $data['ano']));
        $this->load->library('Calendar');
        $data['mes_nome'] = $this->calendar->get_month_name($data['mes']);
        $data['calculo_totalizacao'] = $data['calculo_totalizacao'] ?? '1';
        $data['apontamentos'] = $this->ajax_totalizacao();
        $data['observacoes'] = array();//$this->ajax_observacoes();
        $data['servicos'] = $this->ajax_servicos($data['contrato']->id ?? null);
        $data['reajuste'] = $this->ajax_reajuste($data['contrato']->id ?? null);
        $data['is_pdf'] = $pdf;
        $data['query_string'] = 'q?' . http_build_query($this->input->get());

        //print_r($data);exit;
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

        if ($pdf) {
            return $this->load->view('apontamento_financas', $data, true);
        } else {
            $this->load->view('apontamento_financas', $data);
        }
    }

    private function ajax_totalizacao()
    {
        $busca = $this->input->get();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.nome_bck,
                       s.matricula,
                       s.login,
                       s.horario_trabalho,
                       s.nome_cargo,
                       s.dia_01,
                       s.dia_02,
                       s.dia_03,
                       s.dia_04,
                       s.dia_05,
                       s.dia_06,
                       s.dia_07,
                       s.dia_08,
                       s.dia_09,
                       s.dia_10,
                       s.dia_11,
                       s.dia_12,
                       s.dia_13,
                       s.dia_14,
                       s.dia_15,
                       s.dia_16,
                       s.dia_17,
                       s.dia_18,
                       s.dia_19,
                       s.dia_20,
                       s.dia_21,
                       s.dia_22,
                       s.dia_23,
                       s.dia_24,
                       s.dia_25,
                       s.dia_26,
                       s.dia_27,
                       s.dia_28,
                       s.dia_29,
                       s.dia_30,
                       s.dia_31,
                       s.total_faltas,
                       s.total_atrasos
                FROM (SELECT a.id, 
                             c.nome,
                             j.matricula,
                             j.login,
                             CONCAT_WS(' a ', TIME_FORMAT(j.horario_entrada, '%H:%ih'), TIME_FORMAT(j.horario_saida, '%H:%ih')) AS horario_trabalho,
                             c.cargo AS nome_cargo,
                             d.nome AS nome_bck,
                             ";
        for ($i = 1; $i <= 31; $i++) {
            $dia = str_pad($i, 2, '0', STR_PAD_LEFT);
            if (strtotime("{$busca['ano']}-{$busca['mes']}-$dia") <= strtotime(date('Y-m-d'))) {
                $sql .= "(SELECT CASE WHEN g.qtde_dias > 0 THEN g.qtde_dias
                                      WHEN g.hora_atraso IS NOT NULL THEN TIME_FORMAT(g.hora_atraso, '%H:%i')
                                      WHEN g.hora_glosa IS NOT NULL THEN TIME_FORMAT(g.hora_glosa, '%H:%i') 
                                      ELSE '' END
                                 FROM alocacao_apontamento g 
                                 LEFT JOIN alocacao_usuarios i ON 
                                           i.id = g.id_alocado 
                                 WHERE g.id_alocado = a.id AND 
                                       DATE_FORMAT(g.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m') AND 
                                       DATE_FORMAT(g.data, '%d') = '{$dia}'
                                 GROUP BY g.id_alocado, g.data) AS dia_{$dia}, ";
            } else {
                $sql .= "'' AS dia_{$dia}, ";
            }
        }
        $sql .= "(SELECT IFNULL(SUM(h.qtde_dias), 0)
                  FROM alocacao_apontamento h
                  WHERE h.id_alocado = a.id AND 
                        DATE_FORMAT(h.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')) AS total_faltas,
                 (SELECT IFNULL(TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(TIME(h.hora_atraso)))), '%H:%i'), '00:00') 
                  FROM alocacao_apontamento h
                  WHERE h.id_alocado = a.id AND 
                        DATE_FORMAT(h.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')) AS total_atrasos
                      FROM alocacao_usuarios a
                      INNER JOIN alocacao b ON 
                                 b.id = a.id_alocacao 
                      INNER JOIN usuarios c ON 
                                 c.id = a.id_usuario
                      LEFT JOIN usuarios d ON 
                                 d.id = a.id_usuario_sub
                      LEFT JOIN alocacao_apontamento g ON 
                                g.id_alocado = a.id AND 
                                DATE_FORMAT(g.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')
                      LEFT JOIN alocacao_postos j ON 
                                j.id_usuario = a.id_usuario AND 
                                j.data = (SELECT MAX(k.data) 
                                          FROM alocacao_postos k 
                                          WHERE k.id_usuario = j.id_usuario AND 
                                                DATE_FORMAT(k.data, '%Y-%m') <= '{$busca['ano']}-{$busca['mes']}')
                      WHERE b.id_empresa = {$this->session->userdata('empresa')} AND 
                            DATE_FORMAT(b.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
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
            $sql .= " AND c.cargo = '{$busca['cargo']}'";
        }
        if (isset($busca['funcao'])) {
            $sql .= " AND c.funcao = '{$busca['funcao']}'";
        }
        $sql .= ' GROUP BY a.id) s ORDER BY s.nome';
        $data = $this->db->query($sql)->result();

        return $data;
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $sql = "SELECT s.id, 
                       s.data,
                       s.mes_ano,
                       FORMAT(SUM(s.valor_posto), 2, 'de_DE') AS valor_projetado,
                       FORMAT(SUM(s.valor_posto - (IFNULL(s.dias_atraso, 0) * s.valor_dia + IFNULL(s.minutos_atraso, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0)), 2, 'de_DE') AS valor_realizado,
                       FORMAT(SUM((s.valor_dia * IFNULL(s.dias_atraso, 0)) + (s.valor_hora * IFNULL(s.minutos_atraso, 0))), 2, 'de_DE') AS valor_glosa,
                       FORMAT(100 * SUM((s.valor_dia * IFNULL(s.dias_atraso, 0)) + (s.valor_hora * IFNULL(s.minutos_atraso, 0))) / SUM(s.valor_posto), 2) AS perda_receita,
                       FORMAT(100 * (SUM(s.valor_posto) - SUM((s.valor_dia * IFNULL(s.dias_atraso, 0)) + (s.valor_hora * IFNULL(s.minutos_atraso, 0)))) / SUM(s.valor_posto), 2) AS receita_liquida
                FROM (SELECT b.id, 
                             c.data,
                             DATE_FORMAT(c.data, '%b/%Y') AS mes_ano,
                             SUM(CASE a.status 
                                      WHEN 'FJ' THEN a.qtde_dias 
                                      WHEN 'FN' THEN a.qtde_dias
                                      WHEN 'PD' THEN a.qtde_dias
                                      WHEN 'PI' THEN a.qtde_dias
                                      ELSE NULL END) AS dias_atraso,
                             TIME_FORMAT(SEC_TO_TIME(SUM(CASE a.status 
                                                         WHEN 'AJ' THEN TIME_TO_SEC(a.hora_atraso) 
                                                         WHEN 'AN' THEN TIME_TO_SEC(a.hora_atraso) 
                                                         WHEN 'SJ' THEN TIME_TO_SEC(a.hora_atraso) 
                                                         WHEN 'SN' THEN TIME_TO_SEC(a.hora_atraso) 
                                                         ELSE NULL END)), '%H:%i') AS horas_atraso, 
                             SUM(CASE a.status 
                                      WHEN 'AJ' THEN TIME_TO_SEC(a.hora_atraso) 
                                      WHEN 'AN' THEN TIME_TO_SEC(a.hora_atraso) 
                                      WHEN 'SJ' THEN TIME_TO_SEC(a.hora_atraso) 
                                      WHEN 'SN' THEN TIME_TO_SEC(a.hora_atraso) 
                                      ELSE NULL END) / 3600 AS minutos_atraso, 
                             e.valor_posto, 
                             e.valor_dia, 
                             e.total_dias_mensais,
                             e.valor_hora,
                             e.total_horas_diarias,
                             b.dias_acrescidos,
                             b.horas_acrescidas,
                             b.total_acrescido
                      FROM alocacao_usuarios b
                      LEFT JOIN alocacao_apontamento a ON 
                                b.id = a.id_alocado 
                      LEFT JOIN alocacao c ON 
                                c.id = b.id_alocacao
                      LEFT JOIN usuarios d ON 
                                d.id = b.id_usuario 
                      LEFT JOIN alocacao_postos e ON 
                                e.id_usuario = d.id AND 
                                e.data = (SELECT MAX(g.data) 
                                          FROM alocacao_postos g 
                                          WHERE g.id_usuario = e.id_usuario AND 
                                                DATE_FORMAT(g.data, '%Y-%m') <= '{$busca['ano']}'-'{$busca['mes']}')
                      WHERE c.id_empresa = {$this->session->userdata('empresa')}";

        if (!empty($busca['mes_inicial']) and !empty($busca['ano_inicial']) and !empty($busca['mes_final']) and !empty($busca['ano_final'])) {
            $sql .= " AND (DATE_FORMAT(c.data, '%Y-%m') BETWEEN '{$busca['ano_inicial']}'-'{$busca['mes_inicial']}' AND '{$busca['ano_final']}'-'{$busca['mes_final']}')";
        }
        if (!empty($busca['depto'])) {
            $sql .= " AND d.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['area'])) {
            $sql .= " AND d.area = '{$busca['area']}'";
        }
        if (!empty($busca['setor'])) {
            $sql .= " AND d.setor = '{$busca['setor']}'";
        }
        if (!empty($busca['cargo'])) {
            $sql .= " AND d.cargo = '{$busca['cargo']}'";
        }
        if (!empty($busca['funcao'])) {
            $sql .= " AND d.funcao = '{$busca['funcao']}'";
        }
        $sql .= ' GROUP BY b.id) s GROUP BY s.mes_ano ORDER BY s.data';
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $financa) {
            $row = array();
            $row[] = $financa->mes_ano;
            $row[] = $financa->valor_projetado;
            $row[] = $financa->valor_realizado;
            $row[] = $financa->valor_glosa;
            $row[] = str_replace('.', ',', round($financa->perda_receita, 2));
            $row[] = str_replace('.', ',', round($financa->receita_liquida, 2));

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => count($list),
            "recordsFiltered" => count($list),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_servicos($id_contrato = null)
    {
        $this->db->select('id, NULL AS compartilhados, NULL AS nao_compartilhados,NULL AS total', false);
        $this->db->where('id', $id_contrato);
        $data = $this->db->get('alocacao_contratos')->row();

        if ($data) {
            $data->compartilhados = array();
            $data->nao_compartilhados = array();

            $this->db->select('tipo, descricao, valor');
            $this->db->where('id_contrato', $data->id);
            $rows = $this->db->get('alocacao_servicos')->result();

            foreach ($rows as $row) {
                if ($row->tipo === '1') {
                    $data->compartilhados[] = $row;
                    $data->total += $row->valor;
                } else {
                    $data->nao_compartilhados[] = $row;
                }
            }
        }

        return $data;
    }

    public function ajax_reajuste($id_contrato = null)
    {
        $busca = $this->input->get();

        $sql = "SELECT SUM(s.valor_posto) AS valor_contratual,
                       SUM(s.valor_posto - (IFNULL(s.dias_atraso, 0) * s.valor_dia + IFNULL(s.minutos_atraso, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0)) AS total_liquido,
                       SUM(s.valor_posto * (1 - (IFNULL(FLOOR(s.dias_atraso * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)), 0) + IFNULL(FLOOR(s.minutos_atraso * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)), 0)) / 100)) AS total_liquido_2,
                       NULL AS indices,
                       NULL AS indices_2,
                       (SELECT SUM(t.valor) 
                        FROM alocacao_servicos t 
                        WHERE t.id_contrato = '$id_contrato' AND 
                              t.tipo = 1) AS total_servicos
                FROM (SELECT SUM(CASE a.status 
                                      WHEN 'FJ' THEN a.qtde_dias 
                                      WHEN 'FN' THEN a.qtde_dias
                                      WHEN 'PD' THEN a.qtde_dias
                                      WHEN 'PI' THEN a.qtde_dias
                                      ELSE NULL END) AS dias_atraso,
                             SUM(CASE a.status 
                                      WHEN 'AJ' THEN TIME_TO_SEC(a.hora_atraso) 
                                      WHEN 'AN' THEN TIME_TO_SEC(a.hora_atraso) 
                                      WHEN 'SJ' THEN TIME_TO_SEC(a.hora_atraso) 
                                      WHEN 'SN' THEN TIME_TO_SEC(a.hora_atraso) 
                                      ELSE NULL END) / 3600 AS minutos_atraso, 
                             e.valor_posto, 
                             e.valor_dia,
                             e.total_dias_mensais,
                             e.valor_hora,
                             e.total_horas_diarias,
                             b.dias_acrescidos,
                             b.horas_acrescidas,
                             b.total_acrescido
                      FROM alocacao_usuarios b
                      LEFT JOIN alocacao_apontamento a ON 
                                b.id = a.id_alocado 
                      LEFT JOIN alocacao c ON 
                                c.id = b.id_alocacao
                      LEFT JOIN usuarios d ON 
                                d.id = b.id_usuario 
                      LEFT JOIN alocacao_postos e ON 
                                e.id_usuario = d.id AND 
                                e.data = (SELECT MAX(g.data) 
                                          FROM alocacao_postos g 
                                          WHERE g.id_usuario = e.id_usuario AND 
                                                DATE_FORMAT(g.data, '%Y-%m') <= '{$busca['ano']}-{$busca['mes']}')
                      WHERE c.id_empresa = {$this->session->userdata('empresa')} AND 
                            DATE_FORMAT(c.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
        if (isset($busca['depto'])) {
            $sql .= " AND d.depto = '{$busca['depto']}'";
        }
        if (isset($busca['area'])) {
            $sql .= " AND d.area = '{$busca['area']}'";
        }
        if (isset($busca['setor'])) {
            $sql .= " AND d.setor = '{$busca['setor']}'";
        }
        if (isset($busca['cargo'])) {
            $sql .= " AND d.cargo = '{$busca['cargo']}'";
        }
        if (isset($busca['funcao'])) {
            $sql .= " AND d.funcao = '{$busca['funcao']}'";
        }
        $sql .= ' GROUP BY b.id) s';
        $data = $this->db->query($sql)->row();

        $this->db->select("DATE_FORMAT(a.data_reajuste, 'DIA %d/%m/%Y') AS data_reajuste, a.valor_indice, NULL AS valor_reajuste, NULL AS valor_reajuste_2", false);
        $this->db->join('alocacao_contratos b', 'b.id = a.id_cliente');
        $this->db->where('b.id', $id_contrato);
        $this->db->limit(5);
        $rows = $this->db->get('alocacao_reajuste a')->result();

        $total = $data->total_liquido;
        $total2 = $data->total_liquido_2;
        foreach ($rows as $row) {
            $row->valor_indice = round($row->valor_indice, 8) . '%';

            if ($data->total_liquido && $row->valor_indice) {
                $total += ($row->valor_indice / 100 * $total);
            }
            $row->valor_reajuste = $total;

            if ($data->total_liquido_2 && $row->valor_indice) {
                $total2 += ($row->valor_indice / 100 * $total2);
            }
            $row->valor_reajuste_2 = $total2;
        }

        $data->indices = $rows;
        $data->valor_total = $total + $data->total_servicos;
        $data->valor_total_2 = $total2 + $data->total_servicos;

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
        $this->db->_protect_identifiers = true;

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
        $stylesheet .= '#totalizacao thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#totalizacao tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#reajuste { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#reajuste thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#reajuste tbody td { font-size: 12px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
        $stylesheet .= '#reajuste tbody tr:nth-child(8) td { font-size: 13px; padding: 5px; font-weight: bold; background-color: #f5f5f5; } ';

        $stylesheet .= '#observacoes { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#observacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#observacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->gerenciar(true));

        $data = $this->input->get();

        $this->db->select('a.nome, a.contrato, c.setor');
        $this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
        $this->db->join('alocacao_unidades c', 'c.id_contrato = a.id');
        $this->db->join('alocacao_reajuste d', 'd.id_cliente = a.id');
        if (!empty($data['depto'])) {
            $this->db->where('a.depto', $data['depto']);
        }
        if (!empty($data['area'])) {
            $this->db->where('a.area', $data['area']);
        }
        if (!empty($data['setor'])) {
            $this->db->where('c.setor', $data['setor']);
        }
        $row = $this->db->get('alocacao_contratos a')->row_array();
        $nome = 'Apontamento';
        if ($row) {
            $nome = implode('-', $row);
        }
        $nome .= date('_m-Y', mktime(0, 0, 0, $data['mes'], 1, $data['ano']));

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

}
