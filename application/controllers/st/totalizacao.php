<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Totalizacao extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    //==========================================================================
    public function index()
    {
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {

            $this->db->select('depto, area, setor');
            $this->db->where('id', $this->session->userdata('id'));
            $filtro = $this->db->get('usuarios')->row();

            if (in_array($this->session->userdata('nivel'), array(9, 10))) {
                $data = $this->get_filtros_usuarios($filtro->depto);
            } else {
                $data = $this->get_filtros_usuarios($filtro->depto, $filtro->area, $filtro->setor);
                unset($data['area'][''], $data['setor']['']);
            }
            unset($data['depto']['']);
        } else {

            $data = $this->get_filtros_usuarios();
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
            $status = $this->db->get('usuarios')->row();
            $data['depto_atual'] = $status->depto;
            $data['area_atual'] = $status->area;
            $data['setor_atual'] = $status->setor;
        } else {
            $data['depto_atual'] = '';
            $data['area_atual'] = '';
            $data['setor_atual'] = '';
        }

        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('alocacao c', 'c.id = a.id_alocacao');
//        $this->db->where('a.id', $empresa);
//        $this->db->where('c.id', $id);
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('c.data', date('Y-m-d'));
        $this->db->order_by('b.nome', 'asc');
        $rows = $this->db->get('st_alocados a')->result();

        $data['backup'] = array('' => 'selecione...');
        foreach ($rows as $row) {
            $data['backup'][$row->id] = $row->nome;
        }

        $this->db->select('DISTINCT(a.detalhes) AS detalhe', false);
        $this->db->join('st_alocados b', 'b.id = a.id_alocado');
        $this->db->join('alocacao c', 'c.id = b.id_alocacao');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(a.detalhes) >', 0);
        $this->db->order_by('a.detalhes', 'asc');
        $data['detalhes'] = $this->db->get('st_apontamento a')->result();

        $this->load->view('st/totalizacao', $data);
    }

    //==========================================================================
    public function atualizar_filtro()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
                unset($filtro['area'][''], $filtro['setor']['']);
            }
            unset($filtro['depto']['']);
        }

        $data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    //==========================================================================
    public function novo()
    {
        $empresa = $this->session->userdata('empresa');
        $post = $this->input->post();
        $mes = empty($post['mes']) ? date('m') : $post['mes'];
        $ano = empty($post['ano']) ? date('Y') : $post['ano'];

        $this->db->where('id_empresa', $empresa);
        $this->db->where('data', date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano)));
        $this->db->where('depto', $post['depto']);
        $this->db->where('area', $post['area']);
        $this->db->where('setor', $post['setor']);
        $num_rows = $this->db->get('alocacao')->num_rows();
        if ($num_rows) {
            exit;
        }

        $data = array(
            'id_empresa' => $empresa,
            'data' => date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano)),
            'depto' => $post['depto'],
            'area' => $post['area'],
            'setor' => $post['setor']
        );
        $this->db->trans_start();

        $this->db->insert('alocacao', $data);
        $id_alocacao = $this->db->insert_id();

        $this->db->select("'{$id_alocacao}' AS id_alocacao, a.id AS id_usuario", false);
        $this->db->select("'I' AS tipo_horario, 'P' AS nivel", false);
//        $this->db->join('(SELECT @rownum:=0) b', 'a.id = a.id');
        $this->db->where('a.depto', $post['depto']);
        $this->db->where('a.area', $post['area']);
        $this->db->where('a.setor', $post['setor']);
        $data2 = $this->db->get('usuarios a, (SELECT @rownum:=0) b')->result_array();
        $this->db->insert_batch('st_alocados', $data2);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_list()
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
                       CASE {$post['calculo_totalizacao']} WHEN 2 THEN
                            FORMAT(s.valor_posto * (1 - (IFNULL(s.dias_faltas * 100.0 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0), 0) + IFNULL(s.minutos_atraso * 100.0 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0), 0)) / 100.0) + IFNULL(s.total_acrescido, 0), 2, 'de_DE')
                            ELSE 
                            FORMAT(s.valor_posto - (IFNULL(s.dias_faltas, 0) * s.valor_dia + IFNULL(s.minutos_atraso, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0), 2, 'de_DE') END AS valor_total,
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
                      FROM st_alocados a
                      INNER JOIN alocacao c ON 
                                c.id = a.id_alocacao
                      INNER JOIN usuarios d ON 
                                d.id = a.id_usuario 
                      LEFT JOIN {$view_alocacao} b ON 
                                b.id = a.id AND 
                                b.data <= '{$dataFechamento}'
                      LEFT JOIN st_postos e ON 
                                e.id = b.id_posto
                      WHERE c.id_empresa = {$this->session->userdata('empresa')} AND 
                            c.depto = '{$busca['depto']}' AND
                            c.area = '{$busca['area']}' AND
                            c.setor = '{$busca['setor']}' AND
                            (d.cargo = '{$busca['cargo']}' OR CHAR_LENGTH('{$busca['cargo']}') = 0) AND
                            (d.funcao = '{$busca['funcao']}' OR CHAR_LENGTH('{$busca['funcao']}') = 0) AND
                            c.data BETWEEN '{$dataAbertura}' AND '{$dataFechamento}'
                      GROUP BY a.id_usuario ORDER BY d.nome ASC) s";
//                            DATE_FORMAT(c.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
//        if ($busca['depto']) {
//            $sql .= " AND c.depto = '{$busca['depto']}'";
//        }
//        if ($busca['area']) {
//            $sql .= " AND c.area = '{$busca['area']}'";
//        }
//        if ($busca['setor']) {
//            $sql .= " AND c.setor = '{$busca['setor']}'";
//        }
//        if ($busca['cargo']) {
//            $sql .= " AND d.cargo = '{$busca['cargo']}'";
//        }
//        if ($busca['funcao']) {
//            $sql .= " AND d.funcao = '{$busca['funcao']}'";
//        }
//        $sql .= ' GROUP BY a.id ORDER BY d.nome ASC) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome');
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
        $soma_total = $this->db->query($sql)->result();
        $posto = 0;
        $total = 0;
        foreach ($soma_total as $soma) {
            $posto += str_replace(array('.', ','), array('', '.'), $soma->valor_posto);
            $total += str_replace(array('.', ','), array('', '.'), $soma->valor_total);
        }

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
            $row[] = $apontamento->nome;
            $row[] = $apontamento->dias_faltas;
            if ($post['calculo_totalizacao'] === '2') {
//                $row[] = str_replace('.', ',', $apontamento->perc_dias_faltas ? floor($apontamento->perc_dias_faltas) : $apontamento->perc_dias_faltas);
                $row[] = str_replace('.', ',', $apontamento->perc_dias_faltas ? round($apontamento->perc_dias_faltas, 2) : $apontamento->perc_dias_faltas);
            } else {
                $row[] = str_replace('.', ',', $apontamento->perc_dias_faltas ? round($apontamento->perc_dias_faltas, 2) : $apontamento->perc_dias_faltas);
            }
            $row[] = $apontamento->horas_atraso;
            if ($post['calculo_totalizacao'] === '2') {
//                $row[] = str_replace('.', ',', $apontamento->perc_horas_atraso ? floor($apontamento->perc_horas_atraso) : $apontamento->perc_horas_atraso);
                $row[] = str_replace('.', ',', $apontamento->perc_horas_atraso ? round($apontamento->perc_horas_atraso, 2) : $apontamento->perc_horas_atraso);
            } else {
                $row[] = str_replace('.', ',', $apontamento->perc_horas_atraso ? round($apontamento->perc_horas_atraso, 2) : $apontamento->perc_horas_atraso);
            }
            $row[] = $apontamento->valor_posto;
            $row[] = $apontamento->valor_dia;
            $row[] = str_replace('.', ',', $post['calculo_totalizacao'] === '2' ? $apontamento->perc_glosa_dia : $apontamento->glosa_dia);
            $row[] = $apontamento->valor_hora;
            $row[] = str_replace('.', ',', $post['calculo_totalizacao'] === '2' ? $apontamento->perc_glosa_hora : $apontamento->glosa_hora);
            $row[] = $apontamento->valor_posto ? $apontamento->valor_total : '';
            $row[] = $apontamento->dias_acrescidos;
            $row[] = $apontamento->horas_acrescidas;
            $row[] = $apontamento->total_acrescido;
            $row[] = $apontamento->id;

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
            'semana' => $semana,
            'mes_bloqueado' => boolval($alocacao->mes_bloqueado ?? 0)
        );

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "calendar" => $calendario,
            "total_posto" => number_format($posto, 2, ',', '.'),
            "total" => number_format($total, 2, ',', '.'),
            "total_percentual" => str_replace('.', ',', round($total * 100 / max($posto, 1), 2)),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    //==========================================================================
    public function ajax_colaboradores()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('alocacao c', 'c.id = a.id_alocacao');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where("DATE_FORMAT(c.data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            $this->db->where('c.depto', $busca['depto']);
//            if ($this->session->userdata('nivel') == 11) {
//                $this->db->where('c.area', $busca['area']);
//                $this->db->where('c.setor', $busca['setor']);
//            }
        }
        $this->db->order_by('b.nome', 'asc');
        $rows = $this->db->get('st_alocados a')->result();

        $options = array('' => 'selecione...');
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $data['id_bck'] = form_dropdown('id_bck', $options, '', 'class="form-control"');
        $data['id_alocado_bck'] = form_dropdown('id_alocado_bck', $options, '', 'class="form-control"');

        echo json_encode($data);
    }

    //==========================================================================
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

    //==========================================================================
    public function fecharMes()
    {
        $this->load->library('form_validation');

        $lang = array(
            'required' => "O valor para %s é obrigatório.",
            'max_length' => 'O valor para %s nao deve exceder %s caracteres.',
            'is_natural_no_zero' => 'O valor para %s deve ser um número inteiro maior do sque zero.',
            'less_than' => 'O valor para %s deve ser um número menor do que %s.'
        );
        $this->form_validation->set_message($lang);

        $config = array(
            array(
                'field' => 'depto',
                'label' => 'Departamento',
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'area',
                'label' => 'Área',
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'setor',
                'label' => 'Setor',
                'rules' => array('required', 'max_length[255]')
            ),
            array(
                'field' => 'mes',
                'label' => 'Mês',
                'rules' => 'required|is_natural_no_zero|less_than[13]'
            ),
            array(
                'field' => 'ano',
                'label' => 'Ano',
                'rules' => 'required|is_natural_no_zero|max_length[4]'
            )
        );

        $this->form_validation->set_rules($config);

        if (!$this->form_validation->run()) {
            exit(json_encode(array('status' => $this->form_validation->error_string(' ', ' '))));
        }

        $post = $this->input->post();

        $this->db->where("DATE_FORMAT(data, '%Y-%m') =", $post['ano'] . '-' . $post['mes']);
        $this->db->where('depto', $post['depto']);
        $this->db->where('area', $post['area']);
        $this->db->where('setor', $post['setor']);
        $alocacao = $this->db->get('alocacao')->row_array();
        if (!$alocacao) {
            exit(json_encode(array('status' => 'Não foi possível encontrar a alocação do mês.')));
        }

        if (intval($alocacao['dia_fechamento']) > 0) {
            $sqlMesAno = "SELECT DATE_ADD(DATE_SUB(STR_TO_DATE('{$alocacao['dia_fechamento']}/{$post['mes']}/{$post['ano']}', '%d/%m/%Y'), INTERVAL 1 MONTH), INTERVAL 1 DAY) AS mes_ano";
            $mes_ano = $this->db->query($sqlMesAno)->row()->mes_ano;

            $dataAbertura = date('Y-m-d', strtotime(str_replace('/', '-', $mes_ano)));
            $dataFechamento = date('Y-m-d', strtotime("{$post['ano']}-{$post['mes']}-{$alocacao['dia_fechamento']}"));
            $view_alocacao = 'view_alocacao_consolidada';
        } else {
            $mes_ano = $post['ano'] . '-' . $post['mes'] . '-01';

            $dataAbertura = $mes_ano;
            $dataFechamento = date('Y-m-t', strtotime($mes_ano));
            $view_alocacao = 'view_alocacao';
        }

        $this->db->select('id');
        $this->db->where('contrato', $alocacao['contrato']);
        $row_contrato = $this->db->get('st_contratos')->row();

        $id_contrato = $row_contrato->id ?? NULL;


        $sql = "SELECT t.data,
        			   t.qtde_alocados_ativos,
                       t.valor_projetado,
                       if(t.setor = 'Itaquaquecetuba', t.valor_realizado_2, t.valor_realizado) AS valor_realizado,
                       (t.total_dias + t.qtde_dias_cobertos) AS total_faltas,
                       t.qtde_dias_cobertos AS total_dias_cobertos,
                       t.total_dias AS total_dias_descobertos
                FROM (SELECT x.data,
                             s.setor,
                			 SUM(IF(s.status > 0, 1, 0)) AS qtde_alocados_ativos,
                			 (CASE s.area WHEN 'Ipesp' 
                             THEN (SELECT IFNULL(SUM(t.valor), 0) 
                                   FROM st_servicos t 
                                   WHERE t.id_contrato = '{$id_contrato}' AND 
                                         t.data_reajuste = (SELECT MAX(t2.data_reajuste) 
                                                            FROM st_servicos t2
                                                            WHERE t2.id_contrato = '{$id_contrato}' AND 
                                                                  DATE_FORMAT(t2.data_reajuste, '%Y-%m') <= '{$post['ano']}-{$post['mes']}') AND
                                         t.tipo = 1)
                             ELSE 0 END) + SUM(s.valor_posto) AS valor_projetado,
                             IFNULL(SUM(s.valor_posto), 0) AS valor_projetado2,
                             (CASE s.area WHEN 'Ipesp' 
                             THEN (SELECT IFNULL(SUM(t.valor), 0) 
                                   FROM st_servicos t 
                                   WHERE t.id_contrato = '{$id_contrato}' AND 
                                         t.data_reajuste = (SELECT MAX(t2.data_reajuste) 
                                                            FROM st_servicos t2
                                                            WHERE t2.id_contrato = '{$id_contrato}' AND 
                                                                  DATE_FORMAT(t2.data_reajuste, '%Y-%m') <= '{$post['ano']}-{$post['mes']}') AND
                                         t.tipo = 1)
                             ELSE 0 END) + IFNULL(SUM(s.valor_posto - (IFNULL(s.qtde_dias, 0) * s.valor_dia + IFNULL(s.segundos_atraso / 3600, 0) * s.valor_hora) + IFNULL(s.total_acrescido, 0)), 0) AS valor_realizado,
                             SUM(s.valor_posto * (1 - (IFNULL(FLOOR(s.qtde_faltas * 100 / s.total_dias_mensais + IFNULL(s.dias_acrescidos, 0)), 0) + IFNULL(FLOOR((s.segundos_atraso / 3600) * 100 / s.total_horas_diarias + IFNULL(s.horas_acrescidas, 0)), 0)) / 100) + IFNULL(s.total_acrescido, 0)) AS valor_realizado_2,
                             CASE s.setor WHEN 'São José dos Campos' 
                                  THEN (SELECT SUM(IFNULL(s2.dias_faltas, 0) + IFNULL(s2.segundos_atraso / 28800, 0))
                                        FROM view_alocacao_consolidada s2
                                        INNER JOIN st_alocados s3 ON s3.id = s2.id
                                        INNER JOIN alocacao s4 ON s4.id = s3.id_alocacao
                                        WHERE s4.id = s.id_alocacao AND s2.data <= '{$dataFechamento}')
                                  ELSE SUM(IFNULL(s.qtde_faltas, 0)) + (SUM((IFNULL(s.horas_atraso, 0) * 60) + IFNULL(s.minutos_atraso, 0)) / 480) 
                                  END AS total_dias,
                             SUM(s.qtde_dias_cobertos) AS qtde_dias_cobertos
                      FROM (
                            SELECT STR_TO_DATE(CONCAT_WS('/', '01', meses.mes, anos.ano), '%d/%m/%Y') AS data, 
                                   DATE_FORMAT(STR_TO_DATE(CONCAT_WS('/', '01', meses.mes, anos.ano), '%d/%m/%Y'), '%b/%Y') AS mes_ano,
                                   DATE_FORMAT(STR_TO_DATE(CONCAT_WS('/', '01', meses.mes, anos.ano), '%d/%m/%Y'), '%M/%Y') AS mes_ano_completo
                            FROM (SELECT a.n + b.n + c.n + d.n AS ano
                                  FROM (SELECT 0 AS n UNION SELECT 1 AS n UNION SELECT 2 AS n UNION SELECT 3 AS n UNION SELECT 4 AS n UNION SELECT 5 AS n UNION SELECT 6 AS n UNION SELECT 7 AS n UNION SELECT 8 AS n UNION SELECT 9) d
                                  CROSS JOIN 
                                  (SELECT 0 AS n UNION SELECT 10 AS n UNION SELECT 20 AS n UNION SELECT 30 AS n UNION SELECT 40 AS n UNION SELECT 50 AS n UNION SELECT 60 AS n UNION SELECT 70 AS n UNION SELECT 80 AS n UNION SELECT 90) c
                                  CROSS JOIN 
                                  (SELECT 0 AS n UNION SELECT 100 AS n UNION SELECT 200 AS n UNION SELECT 300 AS n UNION SELECT 400 AS n UNION SELECT 500 AS n UNION SELECT 600 AS n UNION SELECT 700 AS n UNION SELECT 800 AS n UNION SELECT 900) b
                                  CROSS JOIN 
                                  (SELECT 1000 AS n UNION SELECT 2000 AS n UNION SELECT 3000 AS n UNION SELECT 4000 AS n UNION SELECT 5000 AS n UNION SELECT 6000 AS n UNION SELECT 7000 AS n UNION SELECT 8000 AS n UNION SELECT 9000 AS n) a
                                  ) anos 
                            CROSS JOIN (SELECT m.n AS mes
                                  FROM (SELECT '01' AS n UNION SELECT '02' AS n UNION SELECT '03' AS n UNION SELECT '04' AS n UNION SELECT '05' AS n UNION SELECT '06' AS n UNION SELECT '07' AS n UNION SELECT '08' AS n UNION SELECT '09' UNION SELECT '10' AS n UNION SELECT '11' AS n UNION SELECT '12' AS n) m) meses 
                            ORDER BY ano ASC, mes ASC) x 
                      LEFT JOIN view_alocacao2 s ON 
                                s.id_empresa = {$this->session->userdata('empresa')} AND 
                                DATE_FORMAT(s.data, '%Y-%m') = DATE_FORMAT(x.data, '%Y-%m') AND
                                s.depto = '{$post['depto']}' AND
                                s.area = '{$post['area']}' AND
                                s.setor = '{$post['setor']}'
                      LEFT JOIN (SELECT data, IF(SUM(status = 'FR') > 0, 1, 0) AS feriado 
                                 FROM st_apontamento 
                                 GROUP BY YEAR(data), MONTH(data)) v
                                ON DATE_FORMAT(v.data, '%Y-%m') = DATE_FORMAT(s.data, '%Y-%m')
                      WHERE (x.data BETWEEN '{$dataAbertura}' and '{$dataFechamento}')
                      GROUP BY x.data, s.data
                      ORDER BY x.data) t";

        $data = $this->db->query($sql)->row();


        $sqlReajuste = "SELECT a.data_reajuste AS data, a.id_cliente, a.valor_indice
                            FROM st_reajustes a
                            INNER JOIN st_contratos b ON b.id = a.id_cliente
                            INNER JOIN st_unidades c ON c.id_contrato = b.id
                            WHERE b.contrato = '{$alocacao['contrato']}' AND 
                                  b.depto = '{$post['depto']}' AND 
                                  (b.area = '{$post['area']}' AND b.area != 'Ipesp') AND
                                  c.setor = '{$post['setor']}'
                            ORDER BY a.data_reajuste";
        $reajustes = $this->db->query($sqlReajuste)->result();

        /*$sqlIpesp = "SELECT SUM(b.valor) AS valor
                            FROM st_contratos a
                            LEFT JOIN st_servicos b ON b.id_contrato = a.id AND b.tipo = 1
                            LEFT JOIN st_unidades c ON c.id_contrato = a.id
                            WHERE a.contrato = '{$alocacao['contrato']}' AND 
                                  a.depto = '{$post['depto']}' AND 
                                  (a.area = '{$post['area']}' AND a.area = 'Ipesp') AND
                                  c.setor = '{$post['setor']}'";
        $ipesp = $this->db->query($sqlIpesp)->row();

        if ($post['area'] == 'Ipesp') {
            $reajuste_projetado = $data->valor_projetado + $ipesp->valor;
            $reajuste_realizado = $data->valor_realizado + $ipesp->valor;
        } else*/
        if ($post['setor'] == 'São José dos Campos') {
            $reajuste_projetado = round(str_replace(',', '.', $post['valor_projetado']), 2);
            $reajuste_realizado = round(str_replace(',', '.', $post['valor_realizado']), 2);
        } else {
            $reajuste_projetado = $data->valor_projetado;
            $reajuste_realizado = $data->valor_realizado;
        }

        foreach ($reajustes as $k => $reajuste) {
            if (strtotime($reajuste->data) <= strtotime($data->data)) {
                $reajuste_projetado += round($reajuste->valor_indice / 100 * $reajuste_projetado, 2);
                $reajuste_realizado += round($reajuste->valor_indice / 100 * $reajuste_realizado, 2);
            }
        }

        $data->valor_projetado = $reajuste_projetado;
        $data->valor_realizado = $reajuste_realizado;

        if ($this->db->update('alocacao', $data, $alocacao) == false) {
            exit(json_encode(array('status' => 'Não foi possível fechar o mês de acordo com o Registro de Ocorrências especificado.')));
        }


        echo json_encode(array('status' => true));
    }

    //==========================================================================
    public function ajax_edit()
    {
        $this->db->select('DISTINCT(a.detalhes)', FALSE);
        $this->db->join('st_alocados b', 'b.id = a.id_alocado');
        $this->db->join('alocacao c', 'c.id = b.id_alocacao');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(a.detalhes) >', 0);
        $this->db->order_by('a.detalhes', 'asc');
        $detalhes = $this->db->get('st_apontamento a')->result();

        $data = '<ul id="detalhes" class="list-group">';
        if ($detalhes) {
            foreach ($detalhes as $detalhe) {
                $data .= '<li class="list-group-item" style="cursor:pointer;" onclick="sugestao_detalhe(this)">' . $detalhe->detalhes . '</li>';
            }
        } else {
            $data .= '<li class="list-group-item" style="color:#777; cursor:not-allowed; background-color:#eee;">Nenhum detalhe encontrado</li>';
        }
        $data .= '</ul>';

        echo $data;
    }

    //==========================================================================
    public function ajax_ferias()
    {
        $data = $this->input->post();
        if ($data['data_ferias'] xor $data['data_retorno']) {
            exit('O período de férias está incompleto');
        }

        if ($data['data_ferias']) {
            $data['data_ferias'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_ferias'])));

            $this->db->select("DATE_FORMAT(b.data, '%Y%m') as data", false);
            $this->db->join('alocacao b', 'b.id = a.id_alocacao');
            $row = $this->db->get_where('st_alocados a', array('a.id' => $data['id']))->row();
            if ($row->data != date("Ym", strtotime(str_replace('/', '-', $data['data_ferias'])))) {
                exit('A data de início de férias deve pertencer ao mês e ano correspondentes');
            }
        } else {
            $data['data_ferias'] = null;
        }
        if ($data['data_retorno']) {
            $data['data_retorno'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_retorno'])));

            $data_inicio = date_create($data['data_ferias']);
            $data_retorno = date_create($data['data_retorno']);
            $tempo_ferias = date_diff($data_inicio, $data_retorno);

            if ($data_inicio > $data_retorno) {
                exit('A data de retorno de férias deve ser maior que a data de início de férias');
            } elseif ($tempo_ferias->format('a') > 30) {
                exit('A tempo máximo de férias deve ser de 30 dias');
            }
        } else {
            $data['data_retorno'] = null;
        }
        if (empty($data['id_bck'])) {
            $data['id_bck'] = null;
        }

        $this->db->update('st_alocados', $data, array('id' => $data['id']));

        echo json_encode(array("status" => true));
    }

    //==========================================================================
    public function ajax_save()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);
        if (empty($data['dias_acrescidos'])) {
            $data['dias_acrescidos'] = null;
        }
        if (empty($data['horas_acrescidas'])) {
            $data['horas_acrescidas'] = null;
        }
        if (empty($data['total_acrescido'])) {
            $data['total_acrescido'] = null;
        }
        $status = $this->db->update('st_alocados', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('st_apontamento', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_limpar()
    {
        $post = $this->input->post();
        $where = array(
            'id_empresa' => $this->session->userdata('empresa'),
            'data' => date('Y-m-t', mktime(0, 0, 0, $post['mes'], 1, $post['ano']))
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
