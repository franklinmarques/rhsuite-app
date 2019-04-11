<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
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
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'onchange="atualizarFiltro()" class="form-control input-sm"');

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

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        if (isset($post['dia_fechamento']) and empty(intval($post['dia_fechamento']))) {
            $this->db->select("CASE WHEN dia_fechamento > 0 THEN dia_fechamento END AS dia_fechamento", false);
            $this->db->where("DATE_FORMAT(data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
            $this->db->where('depto', $busca['depto'] ?? null);
            $this->db->where('area', $busca['area'] ?? null);
            $this->db->where('setor', $busca['setor'] ?? null);
            $alocacao = $this->db->get('alocacao')->row();
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
                                                        CONCAT(\'\"\', IFNULL(REPLACE(REPLACE(e.observacoes, CHAR(10), \'\\\\n\'), CHAR(13), \'\\\\r\'), \'\'), \'\",\'),
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
                             IFNULL(h.segundos_saldo, 0) AS segundos_saldo,
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
                            (b.depto = '{$busca['depto']}' OR CHAR_LENGTH('{$busca['depto']}') = 0) AND
                            (b.area = '{$busca['area']}' OR CHAR_LENGTH('{$busca['area']}') = 0) AND
                            (b.setor = '{$busca['setor']}' OR CHAR_LENGTH('{$busca['setor']}') = 0) AND
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
        foreach ($list as $apontamento) {
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
            'semana' => $semana
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

    public function ajax_list3()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        if (isset($post['dia_fechamento']) and empty(intval($post['dia_fechamento']))) {
            $this->db->select("CASE WHEN dia_fechamento > 0 THEN dia_fechamento END AS dia_fechamento", false);
            $this->db->where("DATE_FORMAT(data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
            $this->db->where('depto', $busca['depto'] ?? null);
            $this->db->where('area', $busca['area'] ?? null);
            $this->db->where('setor', $busca['setor'] ?? null);
            $alocacao = $this->db->get('alocacao')->row();
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
                                                        CONCAT(\'\"\', IFNULL(REPLACE(REPLACE(e.observacoes, CHAR(10), \'\\\\n\'), CHAR(13), \'\\\\r\'), \'\'), \'\",\'),
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


        $arrayDias = array();
        $rowDias = array_map(function ($dia) {
            $arrayDias[] = $dia;
            return '$row[] = json_decode($apontamento->' . trim($dia) . ', true);';
        }, explode(',', $dias->titulo));

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
                             IFNULL(h.segundos_saldo, 0) AS segundos_saldo,
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
                            (b.depto = '{$busca['depto']}' OR CHAR_LENGTH('{$busca['depto']}') = 0) AND
                            (b.area = '{$busca['area']}' OR CHAR_LENGTH('{$busca['area']}') = 0) AND
                            (b.setor = '{$busca['setor']}' OR CHAR_LENGTH('{$busca['setor']}') = 0) AND
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
        foreach ($list as $apontamento) {
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
            'semana' => $semana
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

    public function ajax_list2()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $sql = "SELECT t.*,
                       TIME_FORMAT(SEC_TO_TIME(t.saldo_sec), '%k:%i') AS saldo
        	    FROM (SELECT s.id, 
                             s.nome,
                             s.nome_sub,
                             @rownum := IF(@usuario = s.id_usuario, @rownum, 0) + s.saldo_sec AS saldo_sec,
                             @rownum, @usuario := IF(@usuario = s.id_usuario, @usuario, s.id_usuario),
                             s.tipo_bck,
                             s.data_recesso,
                             s.data_retorno,
                             s.id_usuario_bck,
                             s.id_alocado_bck,
                             s.nome_bck,
                             s.data_desligamento,
                             s.id_usuario_sub,
                             s.id_alocado_sub,
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
                             s.total_atrasos,
                             s.data
                      FROM (SELECT a.id, 
                                   a.id_usuario,
                                   c.nome,
                                   b.data,
                                   a.tipo_bck,
                                   DATE_FORMAT(a.data_recesso, '%d/%m/%Y') AS data_recesso,
                                   DATE_FORMAT(a.data_retorno, '%d/%m/%Y') AS data_retorno,
                                   a.id_usuario_bck,
                                   d.nome AS id_alocado_bck,
                                   (SELECT o.nome
                                    FROM usuarios o
                                    WHERE o.id = a.id_usuario_bck) AS nome_bck,
                                    DATE_FORMAT(a.data_desligamento, '%d/%m/%Y') AS data_desligamento,
                                   a.id_usuario_sub,
                                   e.nome AS id_alocado_sub,
                                   (SELECT o.nome
                                    FROM usuarios o
                                    WHERE o.id = a.id_usuario_sub) AS nome_sub,
                                   IFNULL(g.saldo_anterior, 0) AS saldo_anterior,
                                   IFNULL(a.saldo_atual, 0) AS saldo_atual,
                                   IFNULL(a.saldo_atual, 0) - IFNULL(g.saldo_anterior, 0) AS saldo_sec,
                                   ";
        for ($i = 1; $i <= 31; $i++) {
            $dia = str_pad($i, 2, '0', STR_PAD_LEFT);
            if (strtotime("{$busca['ano']}-{$busca['mes']}-$dia") <= strtotime(date('Y-m-d'))) {
                $sql .= "(SELECT CASE WHEN (g.status = 'FR' OR g.data <= CURDATE())
                THEN CONCAT('[', GROUP_CONCAT(
                    CONCAT('\"', g.id, '\",'), 
                    CONCAT('\"', IFNULL(g.qtde_dias, ''), '\",'), 
                    CONCAT('\"', IFNULL(DATE_FORMAT(g.hora_atraso, '%H:%i'), ''), '\",'), 
                    CONCAT('\"', IFNULL(DATE_FORMAT(g.hora_entrada, '%H:%i'), ''), '\",'), 
                    CONCAT('\"', IFNULL(DATE_FORMAT(g.hora_intervalo, '%H:%i'), ''), '\",'), 
                    CONCAT('\"', IFNULL(DATE_FORMAT(g.hora_retorno, '%H:%i'), ''), '\",'), 
                    CONCAT('\"', IFNULL(DATE_FORMAT(g.hora_saida, '%H:%i'), ''), '\",'), 
                    CONCAT('\"', IFNULL(i.nome, ''), '\",'), 
                    CONCAT('\"', IFNULL(REPLACE(REPLACE(observacoes, CHAR(10), '\\\\r'), CHAR(13), '\\\\n'), ''), '\",'), 
                    CONCAT('\"', g.status, '\",'), 
                    CONCAT('\"', IFNULL(g.id_alocado_bck, ''), '\",'), 
                    CONCAT('\"', IFNULL(g.id_alocado_bck, ''), '\",'), 
                    CONCAT('\"', IFNULL(DATE_FORMAT(g.hora_glosa, '%H:%i'), ''), '\",'), 
                    CONCAT('\"', IFNULL(i.id, ''), '\",'), 
                    CONCAT('\"', IFNULL(j.nome, ''), '\",'), 
                    CONCAT('\"', IFNULL(k.nome, ''), '\",'), 
                    CONCAT('\"', IFNULL(DATE_FORMAT(g.apontamento_extra, '%H:%i'), ''), '\",'), 
                    CONCAT('\"', IFNULL(DATE_FORMAT(g.apontamento_desc, '%H:%i'), ''), '\",'), 
                    CONCAT('\"', IFNULL(TIME_TO_SEC(g.apontamento_extra), '0:00') - IFNULL(TIME_TO_SEC(g.apontamento_desc), '0:00'), '\"')
                ),']') 
                ELSE '[\"\"]' END
                FROM alocacao_apontamento g 
                LEFT JOIN usuarios j ON 
                j.id = g.id_alocado_bck
                LEFT JOIN usuarios k ON 
                k.id = g.id_alocado_bck
                LEFT JOIN alocacao_eventos i ON 
                i.id = g.detalhes
                WHERE g.id_alocado = a.id AND 
                DATE_FORMAT(g.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m') AND 
                DATE_FORMAT(g.data, '%d') = '{$dia}') AS dia_{$dia}, ";
            } else {
                $sql .= "'[]' AS dia_{$dia}, ";
            }
        }
        $sql .= "(SELECT SUM(h.qtde_dias)
                                    FROM alocacao_apontamento h
                                    WHERE h.id_alocado = a.id AND 
                                    DATE_FORMAT(h.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')) AS total_faltas,
                                   (SELECT TIME_FORMAT(SEC_TO_TIME(SUM(TIME_TO_SEC(TIME(h.hora_atraso)))), '%H:%i')
                                    FROM alocacao_apontamento h
                                    WHERE h.id_alocado = a.id AND 
                                    DATE_FORMAT(h.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')) AS total_atrasos

                            FROM (SELECT x.*, 
					 SUM(IFNULL(TIME_TO_SEC(y.apontamento_extra), 0) - IFNULL(TIME_TO_SEC(y.apontamento_desc), 0)) AS saldo_atual 
                                  FROM alocacao_usuarios x
                                  LEFT JOIN alocacao_apontamento y ON y.id_alocado = x.id
                                  GROUP BY x.id) a
                            INNER JOIN alocacao b ON 
                                       b.id = a.id_alocacao 
                            INNER JOIN usuarios c ON 
                                       c.id = a.id_usuario
                            LEFT JOIN usuarios d ON 
                                      d.id = a.id_usuario_bck
                            LEFT JOIN usuarios e ON 
                                      e.id = a.id_usuario_sub
                            LEFT JOIN alocacao f ON
                                      f.id_empresa = c.id AND
                                      f.depto = c.depto AND 
                                      f.area = c.area AND
                                      f.setor = c.setor AND
                                      DATE_FORMAT(f.data, '%Y-%m') = DATE_FORMAT(DATE_SUB(b.data, INTERVAL 1 MONTH), '%Y-%m')
                            LEFT JOIN (SELECT x.*, 
                                              SUM(IFNULL(TIME_TO_SEC(y.apontamento_extra), 0) - IFNULL(TIME_TO_SEC(y.apontamento_desc), 0)) AS saldo_anterior
                                       FROM alocacao_usuarios x
                                       LEFT JOIN alocacao_apontamento y ON y.id_alocado = x.id
                                       GROUP BY x.id) g ON 
                                      g.id_alocacao = f.id AND
                                      g.id_usuario = a.id_usuario

                            WHERE b.id_empresa = {$this->session->userdata('empresa')}";
        if ($busca['depto']) {
            $sql .= " AND b.depto = '{$busca['depto']}'";
        }
        if ($busca['area']) {
            $sql .= " AND b.area = '{$busca['area']}'";
        }
        if ($busca['setor']) {
            $sql .= " AND b.setor = '{$busca['setor']}'";
        }
        if ($busca['cargo']) {
            $sql .= " AND c.cargo = '{$busca['cargo']}'";
        }
        if ($busca['funcao']) {
            $sql .= " AND c.funcao = '{$busca['funcao']}'";
        }
        $sql .= " GROUP BY a.id 
                            ORDER BY a.id_usuario ASC, b.data ASC) s,
                            (SELECT @rownum := 0, @usuario := 0) rownum) t 
        	WHERE DATE_FORMAT(t.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('t.id', 't.nome', 't.nome_bck', 't.nome_sub');
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
        foreach ($list as $apontamento) {
            $row = array();
            $row[] = array(
                $apontamento->id,
                $apontamento->nome,
                $apontamento->data_recesso,
                $apontamento->data_retorno,
                $apontamento->id_usuario_bck,
                $apontamento->tipo_bck,
                $apontamento->id_alocado_bck
            );
            $row[] = array(
                $apontamento->id,
                $apontamento->nome_bck,
                $apontamento->data_desligamento,
                $apontamento->id_usuario_sub,
                $apontamento->id_alocado_sub
            );
            $row[] = $apontamento->saldo;
            $row[] = json_decode($apontamento->dia_01);
            $row[] = json_decode($apontamento->dia_02);
            $row[] = json_decode($apontamento->dia_03);
            $row[] = json_decode($apontamento->dia_04);
            $row[] = json_decode($apontamento->dia_05);
            $row[] = json_decode($apontamento->dia_06);
            $row[] = json_decode($apontamento->dia_07);
            $row[] = json_decode($apontamento->dia_08);
            $row[] = json_decode($apontamento->dia_09);
            $row[] = json_decode($apontamento->dia_10);
            $row[] = json_decode($apontamento->dia_11);
            $row[] = json_decode($apontamento->dia_12);
            $row[] = json_decode($apontamento->dia_13);
            $row[] = json_decode($apontamento->dia_14);
            $row[] = json_decode($apontamento->dia_15);
            $row[] = json_decode($apontamento->dia_16);
            $row[] = json_decode($apontamento->dia_17);
            $row[] = json_decode($apontamento->dia_18);
            $row[] = json_decode($apontamento->dia_19);
            $row[] = json_decode($apontamento->dia_20);
            $row[] = json_decode($apontamento->dia_21);
            $row[] = json_decode($apontamento->dia_22);
            $row[] = json_decode($apontamento->dia_23);
            $row[] = json_decode($apontamento->dia_24);
            $row[] = json_decode($apontamento->dia_25);
            $row[] = json_decode($apontamento->dia_26);
            $row[] = json_decode($apontamento->dia_27);
            $row[] = json_decode($apontamento->dia_28);
            $row[] = json_decode($apontamento->dia_29);
            $row[] = json_decode($apontamento->dia_30);
            $row[] = json_decode($apontamento->dia_31);
            $row[] = $apontamento->total_faltas;
            $row[] = $apontamento->total_atrasos;

            $data[] = $row;
        }

        $this->load->library('Calendar');
        $dias_semana = $this->calendar->get_day_names('long');
        $semana = array();
        for ($i = 1; $i <= 7; $i++) {
            $semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
        }
        $calendario = array(
            'mes' => $busca['mes'],
            'ano' => $busca['ano'],
            'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
            'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
            'semana' => $semana
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

        //        $this->db->select('a.id, a.nome');
        //        $this->db->where('a.empresa', $empresa);
        //        $this->db->where('a.tipo', 'funcionario');
        //        $this->db->where_in('a.status', array('1', '3'));
        //        if ($busca['depto']) {
        //            $this->db->where('a.depto', $busca['depto']);
        //        }
        //        if ($busca['area']) {
        //            $this->db->where('a.area', $busca['area']);
        //        }
        //        $this->db->where('a.setor', 'backup');
        //        $this->db->order_by('a.nome', 'asc');
        //
        //        $rows_bck = $this->db->get('usuarios a')->result();

        $sql2 = "SELECT s.id, s.nome 
                FROM (SELECT a.id, a.nome 
                FROM usuarios a
                WHERE a.empresa =  '$empresa' AND
                a.tipo =  'funcionario' AND 
                a.status IN ('1', '3')";
        if ($busca['depto']) {
            $sql2 .= " AND a.depto = '{$busca['depto']}'";
        }
//        if ($busca['area']) {
//            $sql2 .= " AND a.area = '{$busca['area']}'";
//        }
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
                    <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Gerenciar avaliadores" onclick="edit_avaliado(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Gerenciar avaliadores</a>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_avaliado(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                    <a class="btn btn-sm btn-info" href="' . site_url('avaliacaoexp_avaliados/relatorio/' . $avaliacaoExp->id) . '" title="Relatório de avaliação"><i class="glyphicon glyphicon-list-alt"> </i> Relatório</a>
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

        $this->db->select("DATE_FORMAT(data, '%Y') AS mes, DATE_FORMAT(data, '%Y') AS ano", false);
        $this->db->select('contrato, descricao_servico, valor_servico, dia_fechamento, qtde_alocados_potenciais, observacoes, valor_projetado, valor_realizado');
        $this->db->select('turnover_reposicao, turnover_aumento_quadro, turnover_desligamento_empresa, turnover_desligamento_colaborador');
        $this->db->select('total_faltas, total_dias_cobertos, total_dias_descobertos');
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
        }

        if ($data['id']) {
            $status = $this->db->update('alocacao_apontamento', $data, array('id' => $data['id']));
        } else {
            unset($data['id']);
            $status = $this->db->insert('alocacao_apontamento', $data);
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('alocacao_apontamento', array('id' => $id));

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

}
