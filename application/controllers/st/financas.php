<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Financas extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    }

    //==========================================================================
    public function index()
    {
        $this->gerenciar();
    }

    //==========================================================================
    public function gerenciar($pdf = false)
    {
        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $data['depto'] = $this->input->get('depto');
        $data['area'] = $this->input->get('area');
        $data['setor'] = $this->input->get('setor');
        $data['cargo'] = $this->input->get('cargo');
        $data['funcao'] = $this->input->get('funcao');
        $data['mes'] = $this->input->get('mes');
        $data['ano'] = $this->input->get('ano');
        if (empty($data['mes']) and empty($data['ano'])) {
            $data['mes'] = $this->input->get('mes_final');
            $data['ano'] = $this->input->get('ano_final');
        }

        $this->db->select('b.id, b.nome, a.depto, a.area, b.contrato, c.setor');
        $this->db->select('e.nome AS nome_usuario, e.depto AS depto_usuario, e.telefone, e.email');
        $this->db->join('st_contratos b', 'b.depto = a.depto AND b.area = a.area AND b.contrato = a.contrato');
        $this->db->join('st_unidades c', 'c.id_contrato = b.id AND c.setor = a.setor');
        $this->db->join('st_reajustes d', 'd.id_cliente = b.id AND d.data_reajuste <= a.data');
        $this->db->join('usuarios e', 'e.id = b.id_usuario', 'left');
        $this->db->where("DATE_FORMAT(a.data, '%Y-%m') =", $data['ano'] . '-' . $data['mes']);

        if (!empty($data['depto'])) {
            $this->db->where('a.depto', $data['depto']);
        }
        if (!empty($data['area'])) {
            $this->db->where('a.area', $data['area']);
        }
        if (!empty($data['setor'])) {
            $this->db->where('a.setor', $data['setor']);
        }
        $data['contrato'] = $this->db->get('alocacao a')->row();
        $data['is_pdf'] = $pdf;
        $data['query_string'] = 'q?' . http_build_query($this->input->get());


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

            $data['chart_valores'] = $this->input->post('chart_valores');
            $data['chart_vacancia'] = $this->input->post('chart_vacancia');
            //$data['chart_perdaReceita'] = $this->input->post('chart_perdaReceita');
            //$data['chart_glosaDias'] = $this->input->post('chart_glosaDias');
            //$data['chart_glosaMinutos'] = $this->input->post('chart_glosaMinutos');

            $data['rows'] = $this->ajax_list();

            return $this->load->view('st/financasPdf', $data, true);
        } else {
            $this->load->view('st/financas', $data);
        }
    }

    //==========================================================================
    private function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);
        $mes_ano_inicial = date('Y-m-d', strtotime($busca['ano_inicial'] . '-' . $busca['mes_inicial'] . '-01'));
        $mes_ano_final = date('Y-m-t', strtotime($busca['ano_final'] . '-' . $busca['mes_final'] . '-01'));

        // DAY(LAST_DAY(x.data)) - (FLOOR((DAY(LAST_DAY(x.data)) + WEEKDAY(x.data)) / 7) + FLOOR((DAY(LAST_DAY(x.data)) + MOD(WEEKDAY(x.data) + 1, 7)) / 7))


        $sql = "SELECT t.mes_ano, 
                       t.mes_ano_completo,
                       t.usuarios,
                       t.usuarios_ativos, 
                       t.usuarios_bck,
                       t.usuarios_sub,

                       t.data,
                       t.dias_uteis,
                       t.qtde_alocados_potenciais,
                       t.total_minutos,
                       t.horas,
                       t.minutos,
                       
                       t.qtde_dias,
                       t.qtde_dias_cobertos,
                       t.total_dias,
                       t.horas_atraso,
                       t.qtde_faltas,
                       t.dias_ausentes,
                       t.posto_descoberto,
                       (t.total_dias + t.qtde_dias_cobertos) AS total_faltas,
                       ((t.total_dias + t.qtde_dias_cobertos) / (t.usuarios_ativos * t.dias_uteis)) * 100 AS porcentagem_faltas,
                       (t.total_dias / (t.usuarios_ativos * t.dias_uteis)) * 100 AS indice_vacancia,
                       t.valor_projetado AS valor_projetado,
                       t.valor_realizado AS valor_realizado,
                       FORMAT(t.valor_projetado - t.valor_realizado, 2, 'de_DE') AS valor_glosa,
                       ((t.valor_projetado - t.valor_realizado) * 100 / t.valor_projetado) AS perda_receita,
                       (t.valor_realizado * 100 / t.valor_projetado) AS receita_liquida,

                       t.turnover_reposicao,
                       t.turnover_aumento_quadro,
                       t.turnover_desligamento_empresa,
                       t.turnover_desligamento_colaborador,
                       (t.turnover_desligamento_empresa + t.turnover_desligamento_colaborador) * 100 / usuarios_ativos AS turnover_mensal,
                       t.turnover_desligamento_colaborador * 100 / usuarios_ativos AS turnover_evasao
                FROM (SELECT x.mes_ano,
                             x.mes_ano_completo,
                             x.data,
                             COUNT(s.id) AS usuarios,
                             a.qtde_alocados_ativos AS usuarios_ativos,
                             a.total_faltas AS total_faltas,
                             a.total_dias_cobertos AS qtde_dias_cobertos,
                             a.total_dias_descobertos AS total_dias,
                             a.valor_projetado,
                             a.valor_realizado,
                             
                             (SELECT COUNT(s1.id) 
                              FROM usuarios s1 
                              WHERE s1.depto = s.depto 
                                    AND s1.area = s.area 
                                    AND s1.setor = s.setor 
                                    AND s1.status = 1) AS usuarios_ativos2,
                             COUNT(CASE WHEN (s.id IS NOT NULL AND s.status = 1) THEN s.id ELSE NULL END) AS usuarios_ativos3,
                             COUNT(s.id_usuario_bck) AS usuarios_bck,
                             COUNT(s.id_usuario_sub) AS usuarios_sub,
                             
                             DAY(LAST_DAY(x.data)) - FLOOR((DAY(LAST_DAY(x.data)) + WEEKDAY(x.data)) / 7) - v.feriado AS dias_uteis,
                             s.qtde_alocados_potenciais,
                             IFNULL(SUM((s.horas_atraso * 60) + s.minutos_atraso), 0) AS total_minutos,
                             IFNULL(SUM(s.horas_atraso), 0) AS horas,
                             IFNULL(SUM(s.minutos_atraso), 0) AS minutos,
                             
                             SUM(s.qtde_dias) AS qtde_dias,
                             SUM(s.qtde_dias_cobertos) AS qtde_dias_cobertos0,
                            
                             IFNULL(SUM(s.qtde_faltas) + (SUM((s.horas_atraso * 60) + s.minutos_atraso) / 480), 0) AS total_dias0,
                             CASE s.setor WHEN 'São José dos Campos'     
                                  THEN IFNULL(SUM(s2.segundos_atraso) / 28800, 0) 
                                  ELSE IFNULL(SUM((s.horas_atraso * 60) + s.minutos_atraso) / 480, 0) END AS horas_atraso,
                             CASE s.setor WHEN 'São José dos Campos'     
                                  THEN SUM(IF(s2.dia_coberto = 1, 0, s2.qtde_dias)) 
                                  ELSE SUM(s.qtde_faltas) END AS qtde_faltas,
                             SUM(s.dias_ausentes) AS dias_ausentes,
                             IFNULL(SUM(s.posto_descoberto), 0) AS posto_descoberto,
                             
                             IFNULL(SUM(s.valor_posto), 0) AS valor_projetad,
                             IFNULL(SUM(s.valor_posto - (IFNULL(s.qtde_dias, 0) * s.valor_dia + IFNULL(s.segundos_atraso / 3600, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0)), 0) AS valor_realizad,

                             s.turnover_reposicao,
                             s.turnover_aumento_quadro,
                             s.turnover_desligamento_empresa,
                             s.turnover_desligamento_colaborador 
                             
                             
                       /*SUM(s.posto_descoberto + s.dias_ausentes + s.qtde_faltas) + (SUM((s.horas_atraso * 60) + s.minutos_atraso) / 480) + SUM(s.qtde_dias_cobertos) AS total_faltas0,
                       
                       
                       ((SUM(s.posto_descoberto + s.dias_ausentes + s.qtde_faltas) + (SUM((s.horas_atraso * 60) + s.minutos_atraso) / 480) + SUM(s.qtde_dias_cobertos)) / ((COUNT(CASE WHEN (s.id IS NOT NULL AND s.status = 1) THEN s.id ELSE NULL END)) * (DAY(LAST_DAY(x.data)) - FLOOR((DAY(LAST_DAY(x.data)) + WEEKDAY(x.data)) / 7) - v.feriado))) * 100 AS porcentagem_faltas,
                       
                       ((SUM(s.posto_descoberto + s.dias_ausentes + s.qtde_faltas) + (SUM((s.horas_atraso * 60) + s.minutos_atraso) / 480)) / ((COUNT(CASE WHEN (s.id IS NOT NULL AND s.status = 1) THEN s.id ELSE NULL END)) * (DAY(LAST_DAY(x.data)) - FLOOR((DAY(LAST_DAY(x.data)) + WEEKDAY(x.data)) / 7) - v.feriado))) * 100 AS indice_vacancia,
                       
                                              
                       FORMAT(IFNULL(SUM(s.valor_posto), 0), 2, 'de_DE') AS valor_projetado2,
                       FORMAT(IFNULL(SUM(s.valor_posto - (IFNULL(s.qtde_dias, 0) * s.valor_dia + IFNULL(s.segundos_atraso / 3600, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0)), 0), 2, 'de_DE') AS valor_realizado2,
                       FORMAT(IFNULL(SUM((s.valor_dia * IFNULL(s.qtde_dias, 0)) + (s.valor_hora * IFNULL(s.segundos_atraso / 3600, 0))), 0), 2, 'de_DE') AS valor_glosa,
                       FORMAT(100 * SUM((s.valor_dia * IFNULL(s.qtde_dias, 0)) + (s.valor_hora * IFNULL(s.segundos_atraso / 3600, 0))) / SUM(s.valor_posto), 4) AS perda_receita,
                       FORMAT(100 * (SUM(s.valor_posto) - SUM((s.valor_dia * IFNULL(s.qtde_dias, 0)) + (s.valor_hora * IFNULL(s.segundos_atraso / 3600, 0)))) / SUM(s.valor_posto), 4) AS receita_liquida*/
                                                      
                FROM (
                      SELECT STR_TO_DATE(CONCAT_WS('/', '01', meses.mes, anos.ano), '%d/%m/%Y') AS data, 
                             DATE_FORMAT(STR_TO_DATE(CONCAT_WS('/', '01', meses.mes, anos.ano), '%d/%m/%Y'), '%b/%Y') AS mes_ano,
                             DATE_FORMAT(STR_TO_DATE(CONCAT_WS('/', '01', meses.mes, anos.ano), '%d/%m/%Y'), '%M/%Y') AS mes_ano_completo
                      FROM (SELECT a.n + b.n + c.n + d.n AS ano
                            FROM (SELECT 0 AS n UNION SELECT 1 AS n UNION SELECT 2 AS n UNION SELECT 3 AS n UNION SELECT 4 AS n UNION SELECT 5 AS n UNION SELECT 6 AS n UNION SELECT 7 AS n UNION SELECT 8 AS n UNION SELECT 9) d
                            CROSS JOIN (SELECT 0 AS n UNION SELECT 10 AS n UNION SELECT 20 AS n UNION SELECT 30 AS n UNION SELECT 40 AS n UNION SELECT 50 AS n UNION SELECT 60 AS n UNION SELECT 70 AS n UNION SELECT 80 AS n UNION SELECT 90) c
                            CROSS JOIN (SELECT 0 AS n UNION SELECT 100 AS n UNION SELECT 200 AS n UNION SELECT 300 AS n UNION SELECT 400 AS n UNION SELECT 500 AS n UNION SELECT 600 AS n UNION SELECT 700 AS n UNION SELECT 800 AS n UNION SELECT 900) b
                            CROSS JOIN (SELECT 1000 AS n UNION SELECT 2000 AS n UNION SELECT 3000 AS n UNION SELECT 4000 AS n UNION SELECT 5000 AS n UNION SELECT 6000 AS n UNION SELECT 7000 AS n UNION SELECT 8000 AS n UNION SELECT 9000 AS n) a) anos 
                      CROSS JOIN (SELECT m.n AS mes
                                  FROM (SELECT '01' AS n UNION SELECT '02' AS n UNION SELECT '03' AS n UNION SELECT '04' AS n UNION SELECT '05' AS n UNION SELECT '06' AS n UNION SELECT '07' AS n UNION SELECT '08' AS n UNION SELECT '09' UNION SELECT '10' AS n UNION SELECT '11' AS n UNION SELECT '12' AS n) m) meses 
                      ORDER BY ano ASC, mes ASC
                      ) x 
                LEFT JOIN view_alocacao2 s ON 
                          s.id_empresa = {$this->session->userdata('empresa')} AND 
                          DATE_FORMAT(s.data, '%Y-%m') = DATE_FORMAT(x.data, '%Y-%m') AND
                          (CASE WHEN CHAR_LENGTH('{$busca['depto']}') > 0 
                                THEN s.depto = '{$busca['depto']}' 
                                ELSE 1 END) AND
                          (CASE WHEN CHAR_LENGTH('{$busca['area']}') > 0 
                                THEN s.area = '{$busca['area']}' 
                                ELSE 1 END) AND
                          (CASE WHEN CHAR_LENGTH('{$busca['setor']}') > 0 
                                THEN s.setor = '{$busca['setor']}' 
                                ELSE 1 END) AND
                          (CASE WHEN CHAR_LENGTH('{$busca['cargo']}') > 0 
                                THEN s.cargo = '{$busca['cargo']}' 
                                ELSE 1 END) AND
                          (CASE WHEN CHAR_LENGTH('{$busca['funcao']}') > 0 
                                THEN s.funcao = '{$busca['funcao']}' 
                                ELSE 1 END)
                LEFT JOIN alocacao a ON a.id = s.id_alocacao
                LEFT JOIN (SELECT data, IF(SUM(status = 'FR') > 0, 1, 0) AS feriado 
                           FROM st_apontamento 
                           GROUP BY YEAR(data), MONTH(data)) v ON 
                          DATE_FORMAT(v.data, '%Y-%m') = DATE_FORMAT(s.data, '%Y-%m')
                LEFT JOIN view_alocacao_consolidada s2 ON
                          s2.id = s.id
                WHERE (x.data BETWEEN '{$mes_ano_inicial}' and '{$mes_ano_final}')
                GROUP BY x.data, s.data
                ORDER BY x.data) t";

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $rows = $this->db->query($sql)->result();

        $sqlReajuste = "SELECT d.data_reajuste AS data, d.id_cliente, d.valor_indice
                        FROM alocacao a 
                        INNER JOIN st_contratos b 
                                   ON b.depto = a.depto
                                   AND b.area = a.area
                                   AND b.contrato = a.contrato
                        INNER JOIN st_unidades c
                                   ON c.id_contrato = b.id
                                   AND c.setor = a.setor
                        INNER JOIN st_reajustes d 
                                   ON d.id_cliente = b.id
                                   AND d.data_reajuste <= a.data
                        WHERE (a.depto = '{$busca['depto']}' OR CHAR_LENGTH('{$busca['depto']}') = 0)
                              AND (a.area = '{$busca['area']}' OR CHAR_LENGTH('{$busca['area']}') = 0)
                              AND (a.setor = '{$busca['setor']}' OR CHAR_LENGTH('{$busca['setor']}') = 0)
                              AND (a.data BETWEEN '{$mes_ano_inicial}' and '{$mes_ano_final}')
                        ORDER BY a.data, d.data_reajuste";

//        $sqlReajustes = "SELECT a.data_reajuste AS data, a.id_cliente, a.valor_indice
//                         FROM st_reajustes a
//                         INNER JOIN st_contratos b ON b.id = a.id_cliente
//                         INNER JOIN st_unidades c ON c.id_contrato = b.id
//                         WHERE (b.depto = '{$busca['depto']}' OR CHAR_LENGTH('{$busca['depto']}') = 0) AND 
//                               (b.area = '{$busca['area']}' OR CHAR_LENGTH('{$busca['area']}') = 0) AND
//                               (c.setor = '{$busca['setor']}' OR CHAR_LENGTH('{$busca['setor']}') = 0) 
//                         ORDER BY a.data_reajuste";
        $reajustes = $this->db->query($sqlReajuste)->result();

        foreach ($rows as $row) {
            $reajuste_projetado = $row->valor_projetado;
            $reajuste_realizado = $row->valor_realizado;
//            foreach ($reajustes as $k => $reajuste) {
//                if (strtotime($reajuste->data) <= strtotime($row->data)) {
//                    $reajuste_projetado += ($reajuste->valor_indice / 100 * $reajuste_projetado);
//                    $reajuste_realizado += ($reajuste->valor_indice / 100 * $reajuste_realizado);
//                }
//            }

            $row->valor_projetado = number_format($reajuste_projetado, 2, ',', '.');
            $row->valor_realizado = number_format($reajuste_realizado, 2, ',', '.');
            $row->valor_glosa = number_format($reajuste_projetado - $reajuste_realizado, 2, ',', '.');
            $row->perda_receita = $reajuste_projetado > 0 ? ($reajuste_projetado - $reajuste_realizado) * 100 / $reajuste_projetado : 0;
            $row->receita_liquida = $reajuste_projetado > 0 ? $reajuste_realizado * 100 / $reajuste_projetado : 0;
        }

        return $rows;
    }

    //==========================================================================
    public function ajax_colaboradores()
    {
        $list = $this->ajax_list();

        $data = array();
        foreach ($list as $financa) {
            $row = array();
            $row[] = $financa->mes_ano;
            $row[] = $financa->usuarios;
            //$row[] = $financa->usuarios_ativos;
            $row[] = $financa->usuarios_bck;
            $row[] = $financa->usuarios_sub;

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

    //==========================================================================
    public function ajax_tempo()
    {
        $list = $this->ajax_list();

        $data = array();
        foreach ($list as $financa) {
            $row = array();
            $row[] = $financa->mes_ano;
            $row[] = $financa->total_minutos;
            $row[] = $financa->horas;
            $row[] = $financa->minutos;

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

    //==========================================================================
    public function ajax_faltas()
    {
        $list = $this->ajax_list();

        $data = array();
        //$chart = array('vacancia' => array(), 'glosaDias' => array(), 'glosaMinutos' => array());
        $chart = array('vacancia' => array());
        foreach ($list as $financa) {
            $row = array();
            $row[] = $financa->mes_ano;
            $row[] = $financa->dias_uteis;
            $row[] = $financa->qtde_alocados_potenciais;
            $row[] = $financa->usuarios_ativos;
            $row[] = str_replace('.', ',', round($financa->total_faltas, 2));
            $row[] = str_replace('.', ',', round($financa->qtde_dias_cobertos, 2));
            $row[] = str_replace('.', ',', round($financa->total_dias, 2));
            $row[] = str_replace('.', ',', round($financa->porcentagem_faltas, 2));
            $row[] = str_replace('.', ',', round($financa->indice_vacancia, 2));
            $row[] = str_replace('.', ',', round($financa->qtde_faltas, 2));
            //$row[] = str_replace('.', ',', round($financa->dias_ausentes, 2));
            $row[] = str_replace('.', ',', round($financa->horas_atraso, 2));
            //$row[] = $financa->posto_descoberto;

            $data[] = $row;

            $chart['vacancia'][] = array(
                array('v' => $financa->mes_ano, 'f' => ucfirst($financa->mes_ano_completo)),
                array('v' => round($financa->porcentagem_faltas, 2), 'f' => str_replace('.', ',', round($financa->porcentagem_faltas, 2)) . '%'),
                array('v' => round($financa->indice_vacancia, 2), 'f' => str_replace('.', ',', round($financa->indice_vacancia, 2)) . '%')
            );
            /* $chart['glosaDias'][] = array(
              array('v' => $financa->mes_ano, 'f' => ucfirst($financa->mes_ano_completo)),
              array('v' => round($financa->qtde_faltas, 2), 'f' => str_replace('.', ',', round($financa->qtde_faltas, 2)))
              );
              $chart['glosaMinutos'][] = array(
              array('v' => $financa->mes_ano, 'f' => ucfirst($financa->mes_ano_completo)),
              array('v' => round($financa->horas_atraso, 2), 'f' => str_replace('.', ',', round($financa->horas_atraso, 2)))
              ); */
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => count($list),
            "recordsFiltered" => count($list),
            "chart" => $chart,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    //==========================================================================
    public function ajax_valores()
    {
        $list = $this->ajax_list();

        $data = array();
        //$chart = array('valores' => array(), 'perdaReceita' => array());
        $chart = array('valores' => array());
        foreach ($list as $financa) {
            $row = array();
            $row[] = $financa->mes_ano;
            $row[] = $financa->valor_projetado;
            $row[] = $financa->valor_realizado;
            $row[] = $financa->valor_glosa;
            $row[] = str_replace('.', ',', round($financa->perda_receita, 4));
            $row[] = str_replace('.', ',', round($financa->receita_liquida, 4));

            $data[] = $row;

            $chart['valores'][] = array(
                array('v' => $financa->mes_ano, 'f' => ucfirst($financa->mes_ano_completo)),
                array('v' => floatval(str_replace(array('.', ','), array('', '.'), $financa->valor_projetado)), 'f' => 'R$ ' . $financa->valor_projetado),
                array('v' => floatval(str_replace(array('.', ','), array('', '.'), $financa->valor_realizado)), 'f' => 'R$ ' . $financa->valor_realizado)
                // array('v' => floatval(str_replace(array('.', ','), array('', '.'), $financa->valor_glosa)), 'f' => 'R$ ' . $financa->valor_glosa)
            );
            /* $chart['perdaReceita'][] = array(
              array('v' => $financa->mes_ano, 'f' => ucfirst($financa->mes_ano_completo)),
              array('v' => round($financa->perda_receita, 4), 'f' => str_replace('.', ',', round($financa->perda_receita, 4)) . '%')
              ); */
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => count($list),
            "recordsFiltered" => count($list),
            "chart" => $chart,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    //==========================================================================
    public function ajax_turnover()
    {
        $list = $this->ajax_list();

        $data = array();
        foreach ($list as $financa) {
            $row = array();
            $row[] = $financa->turnover_reposicao;
            $row[] = $financa->turnover_aumento_quadro;
            $row[] = $financa->turnover_desligamento_empresa;
            $row[] = $financa->turnover_desligamento_colaborador;
            $row[] = str_replace('.', ',', round($financa->turnover_mensal, 2));
            $row[] = str_replace('.', ',', round($financa->turnover_evasao, 2));

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

    //==========================================================================
    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '.datatable { border: 1px solid #777; margin-bottom: 0px; } ';
        $stylesheet .= '.datatable thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #777; } ';
        $stylesheet .= '.datatable tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #777; } ';

        $stylesheet .= '#chart tr td { padding-top: 5px; padding-bottom: 5px; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->gerenciar(true)); /* $data = $this->input->get();

          $this->db->select('a.nome, a.contrato, c.setor');
          $this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
          $this->db->join('st_unidades c', 'c.id_contrato = a.id');
          $this->db->join('st_reajustes d', 'd.id_cliente = a.id');
          if (!empty($data['depto'])) {
          $this->db->where('a.depto', $data['depto']);
          }
          if (!empty($data['area'])) {
          $this->db->where('a.area', $data['area']);
          }
          if (!empty($data['setor'])) {
          $this->db->where('c.setor', $data['setor']);
          }
          $row = $this->db->get('st_contratos a')->row_array();
          $nome = 'Relatório de Consolidação Financeira';
          if ($row) {
          $nome = implode('-', $row);
          }
          $nome .= date('_m-Y', mktime(0, 0, 0, $data['mes'], 1, $data['ano'])) */;

        $data['pacote'] = uniqid();
        $this->m_pdf->pdf->Output('arquivos/temp/' . $data['pacote'] . '.pdf', 'F');

        echo json_encode($data);
    }

    //==========================================================================
    public function downloadPdf()
    {
        $pacote = $this->uri->rsegment(3, '');
        $this->load->helper('download');
        $data = file_get_contents("arquivos/temp/$pacote.pdf");
        unlink("arquivos/temp/$pacote.pdf");

        force_download('Relatório de Consolidação Financeira.pdf', $data);
    }

}
