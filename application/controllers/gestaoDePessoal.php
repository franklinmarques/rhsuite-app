<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class GestaoDePessoal extends MY_Controller
{

    public function index()
    {
        $data = $this->getFiltros();

        $data['funcao'] = form_dropdown('', $data['cargosFuncoes'], '');

        $this->load->view('gestao_de_pessoal', $data);
    }

    // -------------------------------------------------------------------------

    public function getFiltros()
    {
        $idDepto = $this->input->post('id_depto');

        $sql = "SELECT a.id_depto, b.nome AS depto, 
                       a.id_funcao, c.nome AS cargo, d.nome AS funcao
                FROM requisicoes_pessoal a
                LEFT JOIN empresa_departamentos b ON b.id = a.id_depto 
                LEFT JOIN empresa_cargos c ON c.id = a.id_cargo 
                LEFT JOIN empresa_funcoes d ON d.id = a.id_funcao AND d.id_cargo = c.id 
                WHERE a.id_empresa = '{$this->session->userdata('empresa')}'";

        $sqlDeptos = "SELECT s.id_depto, s.depto FROM ({$sql}) s ORDER BY s.depto ASC";
        $sqlCargosFuncoes = "SELECT s.id_funcao, CONCAT_WS('/', s.cargo, s.funcao) AS cargo_funcao 
                             FROM ({$sql}) s 
                             WHERE (s.id_depto = '{$idDepto}' OR CHAR_LENGTH('{$idDepto}') = 0)
                             ORDER BY s.cargo ASC, s.funcao ASC";

        $deptos = $this->db->query($sqlDeptos)->result();
        $cargosFuncoes = $this->db->query($sqlCargosFuncoes)->result();

        $data = array(
            'deptos' => ['' => 'Todos'] + array_column($deptos, 'depto', 'id_depto'),
            'cargosFuncoes' => ['' => 'Todos'] + array_column($cargosFuncoes, 'cargo_funcao', 'id_funcao')
        );

        return $data;
    }

    // -------------------------------------------------------------------------

    public function ajaxListColaboradores($return = false)
    {
        parse_str($this->input->post('busca'), $busca);
        $ano = !empty($busca['ano']) ? $busca['ano'] : date('Y');
        $quadroAtual = $this->input->post('quadro_atual');


        $this->db->select('a.id, a.nome, b.mes, b.total_colaboradores');
        $this->db->join('requisicoes_pessoal_estruturas b', "b.id_depto = a.id AND b.ano = '{$ano}'", 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));

        $query = $this->db->get('empresa_departamentos a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $rows = array();
        $totalMes = array();
        foreach ($output->data as $estrutura) {
            $rows[$estrutura->id] = $estrutura->nome;
            if ($estrutura->total_colaboradores) {
                $totalMes[$estrutura->id][$estrutura->mes] = $estrutura->total_colaboradores;
            }
        }

        if ($quadroAtual) {
            $dataLimite = date('Y-m-t', mktime(0, 0, 0, intval($busca['mes']), 1, $ano));
            $dataInicial = date('Y-m-d', mktime(0, 0, 0, intval($busca['mes']), 1, $ano));
            $sql = "SELECT s.id, COUNT(s.id_usuario) AS total_colaboradores
                    FROM (SELECT a.id, b.id AS id_usuario
                          FROM empresa_departamentos a
                          LEFT JOIN usuarios b ON b.id_depto = a.id or b.depto = a.nome
                          WHERE b.tipo = 'funcionario' AND b.status IN (1, 6, 7, 8, 9) AND 
                                b.datacadastro <= '{$dataLimite}'
                          UNION
                          SELECT a.id, c.id_usuario
                          FROM empresa_departamentos a
                          INNER JOIN usuarios b ON b.id_depto = a.id or b.depto = a.nome
                          INNER JOIN usuarios_afastamento c on c.id_usuario = b.id
                          WHERE c.data_afastamento <= '{$dataLimite}' AND 
                                (c.data_retorno >= '{$dataInicial}' OR c.data_retorno IS NULL)) s 
                    GROUP BY s.id";
            $quadros = $this->db->query($sql)->result();
            foreach ($quadros as $quadro) {
                $totalMes[$quadro->id][$busca['mes']] = $quadro->total_colaboradores;
            }
        }

        $data = array();
        $total = array_pad([], 12, null);
        foreach ($rows as $id => $nome) {
            if (isset($totalMes[$id][$busca['mes']]) == false) {
                continue;
            }
            $row = array(
                $nome,
                $totalMes[$id]['01'] ?? null,
                $totalMes[$id]['02'] ?? null,
                $totalMes[$id]['03'] ?? null,
                $totalMes[$id]['04'] ?? null,
                $totalMes[$id]['05'] ?? null,
                $totalMes[$id]['06'] ?? null,
                $totalMes[$id]['07'] ?? null,
                $totalMes[$id]['08'] ?? null,
                $totalMes[$id]['09'] ?? null,
                $totalMes[$id]['10'] ?? null,
                $totalMes[$id]['11'] ?? null,
                $totalMes[$id]['12'] ?? null,
                isset($totalMes[$id]) ? round(array_sum($totalMes[$id]) / max(count(array_filter($totalMes[$id], function ($v) {
                        return strlen($v) > 0;
                    })), 1)) : null,
                $id
            );

            $data[] = $row;

            for ($i = 0; $i < 12; $i++) {
                if (strlen($row[$i + 1]) > 0) {
                    $total[$i] += $row[$i + 1];
                }
            }
        }

        $total[] = round(array_sum($total) / max(count(array_filter($total, function ($v) {
                return strlen($v) > 0;
            })), 1));

        if ($return) {
            return $data;
        }

        $output->recordsTotal = count($data);
        $output->recordsFiltered = $output->recordsTotal;
        $output->ano = $ano;
        $output->total = $total;
        $output->data = $data;

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function ajaxListRequisicoes($return = false)
    {
        parse_str($this->input->post('busca'), $busca);
        $ano = !empty($busca['ano']) ? $busca['ano'] : date('Y');
        $idDepto = $busca['depto'] ?? '';
        $idFuncao = $busca['funcao'] ?? '';

        $sql = "SELECT MONTHNAME(data_abertura) AS mes,
                       COUNT(data_abertura) AS aberto,
                       SUM(CASE WHEN data_abertura IS NOT NULL THEN numero_vagas END) AS vagas_aberto,
                       COUNT(data_fechamento) AS fechado,
                       SUM(CASE WHEN data_fechamento IS NOT NULL THEN numero_vagas END) AS vagas_fechado,
                       COUNT(data_suspensao) AS parcial,
                       SUM(CASE WHEN data_suspensao IS NOT NULL THEN numero_vagas END) AS vagas_parcial,
                       COUNT(data_cancelamento) AS suspenso
                FROM requisicoes_pessoal 
                WHERE id_empresa = '{$this->session->userdata('empresa')}' AND
                      (id_depto = '{$idDepto}' OR CHAR_LENGTH('{$idDepto}') = 0) AND
                      (id_funcao = '{$idFuncao}' OR CHAR_LENGTH('{$idFuncao}') = 0) AND 
                      YEAR(data_abertura) = '{$ano}'";

        $total = $this->db->query($sql)->row_array();

        $sql .= ' GROUP BY MONTH(data_abertura) 
                 ORDER BY MONTH(data_abertura) ASC';

        $this->load->library('dataTables');

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $output = $this->datatables->query($sql);

        unset($total['mes']);
        $output->total = array_values($total);


        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                ucfirst($row->mes),
                $row->aberto,
                $row->vagas_aberto,
                $row->fechado,
                $row->vagas_fechado,
                $row->parcial,
                $row->vagas_parcial,
                $row->suspenso
            );
        }

        if ($return) {
            return $data;
        }

        $output->data = $data;

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function ajaxListTurnover($return = false)
    {
        parse_str($this->input->post('busca'), $busca);
        $ano = !empty($busca['ano']) ? $busca['ano'] : date('Y');
        $turnoverAtual = $this->input->post('turnover_atual');


        $this->db->select(['mes, (total_colaboradores_admitidos + total_colaboradores_demitidos + total_colaboradores_desligados + total_demissoes_desligamentos) * 100 / total_colaboradores_ativos AS turnover_geral'], false);
        $this->db->select('total_colaboradores_admitidos * 100 / total_colaboradores_ativos AS turnover_colaboradores_admitidos', false);
        $this->db->select('total_colaboradores_demitidos * 100 / total_colaboradores_ativos AS turnover_colaboradores_demitidos', false);
        $this->db->select('total_colaboradores_justa_causa * 100 / total_colaboradores_ativos AS turnover_colaboradores_justa_causa', false);
        $this->db->select('total_colaboradores_desligados * 100 / total_colaboradores_ativos AS turnover_colaboradores_desligados', false);
        $this->db->select('total_demissoes_desligamentos * 100 / total_colaboradores_ativos AS turnover_demissoes_desligamentos', false);

        $this->db->select('total_colaboradores_ativos', false);
        $this->db->select('total_colaboradores_admitidos', false);
        $this->db->select('total_colaboradores_demitidos', false);
        $this->db->select('total_colaboradores_justa_causa', false);
        $this->db->select('total_colaboradores_desligados', false);
        $this->db->select('total_demissoes_desligamentos', false);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('ano', $ano);

        $query = $this->db->get('requisicoes_pessoal_relatorios');
        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        if ($turnoverAtual) {
            $dataAnterior = date('Y-m-d', strtotime('-5 month', mktime(0, 0, 0, intval($busca['mes']), 1, $ano)));

            $sql = "SELECT '{$busca['mes']}' AS mes, 
                           s.total_colaboradores_ativos,
                           (s.total_colaboradores_admitidos + s.total_demissoes) * 100 / s.total_colaboradores_ativos AS turnover_geral,
                           s.total_colaboradores_admitidos * 100 / s.total_colaboradores_ativos AS turnover_colaboradores_admitidos,
                           s.total_colaboradores_demitidos * 100 / s.total_colaboradores_ativos AS turnover_colaboradores_demitidos,
                           s.total_colaboradores_justa_causa * 100 / s.total_colaboradores_ativos AS turnover_colaboradores_justa_causa,
                           s.total_colaboradores_desligados * 100 / s.total_colaboradores_ativos AS turnover_colaboradores_desligados,
                           s.total_demissoes_desligamentos * 100 / s.total_colaboradores_ativos AS turnover_demissoes_desligamentos,
                           s.total_colaboradores_admitidos,
                           s.total_colaboradores_demitidos,
                           s.total_colaboradores_justa_causa,
                           s.total_colaboradores_desligados,
                           s.total_demissoes_desligamentos
                    FROM (SELECT COUNT(CASE WHEN a.status = 1 THEN 1 END) AS total_colaboradores_ativos,
                                 COUNT(CASE WHEN YEAR(a.data_admissao) = '{$ano}' AND MONTH(a.data_admissao) = '{$busca['mes']}' THEN 1 END) AS total_colaboradores_admitidos,
                                 COUNT(CASE WHEN YEAR(a.data_demissao) = '{$ano}' AND MONTH(a.data_demissao) = '{$busca['mes']}' AND a.tipo_demissao IN (1, 4, 6, 8, 9) THEN 1 END) AS total_colaboradores_demitidos,
                                 COUNT(CASE WHEN YEAR(a.data_demissao) = '{$ano}' AND MONTH(a.data_demissao) = '{$busca['mes']}' AND a.tipo_demissao = 2 THEN 1 END) AS total_colaboradores_justa_causa,
                                 COUNT(CASE WHEN YEAR(a.data_demissao) = '{$ano}' AND MONTH(a.data_demissao) = '{$busca['mes']}' AND a.tipo_demissao IN (3, 5, 7)  THEN 1 END) AS total_colaboradores_desligados,
                                 COUNT(CASE WHEN YEAR(a.data_demissao) = '{$ano}' AND MONTH(a.data_demissao) = '{$busca['mes']}' THEN 1 END) AS total_demissoes,
                                 COUNT(CASE WHEN a.data_demissao < '{$dataAnterior}' THEN 1 END) AS total_demissoes_desligamentos
                    FROM usuarios a
                    WHERE a.empresa = '{$this->session->userdata('empresa')}' AND
                          a.tipo = 'funcionario') s";
            $turnovers = $this->db->query($sql)->row();

            $mesesSalvos = array_column($output->data, 'mes');
            if (in_array($busca['mes'], $mesesSalvos)) {
                $output->data[array_search($busca['mes'], $mesesSalvos)] = $turnovers;
            } else {
                $output->data[] = $turnovers;
            }
        }

        $coluna = [
            'Qtd Colaboradores Ativos',
            'Turnover Geral Mês (Admissões+Desligamentos+Demissões)',
            'Turnover de Admissão',
            'Turnover Demissões (sem justa causa)',
            'Turnover Demissões (por justa causa)',
            'Turnover Desligamentos',
            'Turnover  Demissões+Desligamentos < 6 meses',
            'Qtd Colaboradores Admitidos',
            'Qtd Colaboradores Demitidos (sem justa causa)',
            'Qtd Colaboradores Demitidos (por justa causa)',
            'Qtd Colaboradores Desligados',
            'Qtd Demissões+Desligamentos < 6 meses'
        ];

        $meses = array();
        foreach ($output->data as $row) {
            $meses[0][$row->mes] = $row->total_colaboradores_ativos;
            $meses[1][$row->mes] = $row->turnover_geral;
            $meses[2][$row->mes] = $row->turnover_colaboradores_admitidos;
            $meses[3][$row->mes] = $row->turnover_colaboradores_demitidos;
            $meses[4][$row->mes] = $row->turnover_colaboradores_justa_causa;
            $meses[5][$row->mes] = $row->turnover_colaboradores_desligados;
            $meses[6][$row->mes] = $row->turnover_demissoes_desligamentos;
            $meses[7][$row->mes] = $row->total_colaboradores_admitidos;
            $meses[8][$row->mes] = $row->total_colaboradores_demitidos;
            $meses[9][$row->mes] = $row->total_colaboradores_justa_causa;
            $meses[10][$row->mes] = $row->total_colaboradores_desligados;
            $meses[11][$row->mes] = $row->total_demissoes_desligamentos;
        }

        $data = array();
        for ($i = 0; $i < count($meses); $i++) {
            if (empty($output->data)) {
                break;
            }
            $data[] = array(
                $coluna[$i],
                isset($meses[$i]['01']) ? str_replace('.', ',', round($meses[$i]['01'], 2)) : null,
                isset($meses[$i]['02']) ? str_replace('.', ',', round($meses[$i]['02'], 2)) : null,
                isset($meses[$i]['03']) ? str_replace('.', ',', round($meses[$i]['03'], 2)) : null,
                isset($meses[$i]['04']) ? str_replace('.', ',', round($meses[$i]['04'], 2)) : null,
                isset($meses[$i]['05']) ? str_replace('.', ',', round($meses[$i]['05'], 2)) : null,
                isset($meses[$i]['06']) ? str_replace('.', ',', round($meses[$i]['06'], 2)) : null,
                isset($meses[$i]['07']) ? str_replace('.', ',', round($meses[$i]['07'], 2)) : null,
                isset($meses[$i]['08']) ? str_replace('.', ',', round($meses[$i]['08'], 2)) : null,
                isset($meses[$i]['09']) ? str_replace('.', ',', round($meses[$i]['09'], 2)) : null,
                isset($meses[$i]['10']) ? str_replace('.', ',', round($meses[$i]['10'], 2)) : null,
                isset($meses[$i]['11']) ? str_replace('.', ',', round($meses[$i]['11'], 2)) : null,
                isset($meses[$i]['12']) ? str_replace('.', ',', round($meses[$i]['12'], 2)) : null,
                str_replace('.', ',', round(array_sum($meses[$i]) / max(count(array_filter($meses[$i], function ($v) {
                        return strlen($v) > 0;
                    })), 1), in_array($i, [1, 2, 3, 4, 5]) ? 2 : 0))
            );
        }

        if ($return) {
            return $data;
        }

        $output->recordsTotal = count($meses);
        $output->recordsFiltered = count($meses);
        $output->ano = $ano;
        $output->data = $data;

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function ajaxListAfastamentos($return = false)
    {
        parse_str($this->input->post('busca'), $busca);
        $ano = !empty($busca['ano']) ? $busca['ano'] : date('Y');
        $afastamentosAtual = $this->input->post('afastamentos_atual');


        $this->db->select('mes, total_colaboradores_ativos');
        $this->db->select('(total_acidentes + total_maternidade + total_aposentadoria + total_doenca) * 100 / total_colaboradores_ativos AS turnover_afastados', false);
        $this->db->select('total_acidentes * 100 / total_colaboradores_ativos AS turnover_acidentes', false);
        $this->db->select('total_maternidade * 100 / total_colaboradores_ativos AS turnover_maternidade', false);
        $this->db->select('total_aposentadoria * 100 / total_colaboradores_ativos AS turnover_aposentadoria', false);
        $this->db->select('total_doenca * 100 / total_colaboradores_ativos AS turnover_doenca', false);
        $this->db->select(['(total_acidentes + total_maternidade + total_aposentadoria + total_doenca) AS total_afastados'], false);
        $this->db->select('total_acidentes, total_maternidade, total_aposentadoria, total_doenca');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('ano', $ano);

        $query = $this->db->get('requisicoes_pessoal_relatorios');
        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        if ($afastamentosAtual) {
            $dataLimite = date('Y-m-t', mktime(0, 0, 0, intval($busca['mes']), 1, $ano));
            $dataInicial = date('Y-m-d', mktime(0, 0, 0, intval($busca['mes']), 1, $ano));
            $sql = "SELECT '{$busca['mes']}' AS mes, 
                           s.total_colaboradores_ativos,
                           s.total_afastados * 100 / s.total_colaboradores_ativos AS turnover_afastados, 
                           s.total_acidentes * 100 / s.total_colaboradores_ativos AS turnover_acidentes, 
                           s.total_maternidade * 100 / s.total_colaboradores_ativos AS turnover_maternidade, 
                           s.total_aposentadoria * 100 / s.total_colaboradores_ativos AS turnover_aposentadoria, 
                           s.total_doenca * 100 / s.total_colaboradores_ativos AS turnover_doenca,
                           s.total_afastados, 
                           s.total_acidentes, 
                           s.total_maternidade, 
                           s.total_aposentadoria, 
                           s.total_doenca
                    FROM (SELECT COUNT(DISTINCT(a.id_usuario)) AS total_afastados,
                                 COUNT(CASE a.motivo_afastamento WHEN 1 THEN 1 END) AS total_doenca,
                                 COUNT(CASE a.motivo_afastamento WHEN 2 THEN 1 END) AS total_maternidade,
                                 COUNT(CASE a.motivo_afastamento WHEN 3 THEN 1 END) AS total_acidentes,
                                 COUNT(CASE a.motivo_afastamento WHEN 4 THEN 1 END) AS total_aposentadoria,
                                 (SELECT COUNT(b.id) 
                                  FROM usuarios b
                                  WHERE b.empresa = a.id_empresa AND 
                                        b.tipo = 'funcionario' AND 
                                        b.status = 1) AS total_colaboradores_ativos
                          FROM usuarios_afastamento a
                          WHERE a.id_empresa = 78 AND 
                                a.data_afastamento <= '{$dataLimite}' AND
                                (a.data_retorno > '{$dataInicial}' OR a.data_retorno IS NULL)) s";
            $afastamentos = $this->db->query($sql)->row();

            $mesesSalvos = array_column($output->data, 'mes');
            if (in_array($busca['mes'], $mesesSalvos)) {
                $output->data[array_search($busca['mes'], $mesesSalvos)] = $afastamentos;
            } else {
                $output->data[] = $afastamentos;
            }
        }

        $coluna = [
            'Qtd Colaboradores Ativos',
            'Total de afastados (%)',
            'Acidentes (%)',
            'Maternidade (%)',
            'Aposentadoria (%)',
            'Doença (%)',
            'Total de afastados',
            'Acidentes',
            'Maternidade',
            'Aposentadoria',
            'Doença'
        ];

        $meses = array();
        foreach ($output->data as $row) {
            $meses[0][$row->mes] = $row->total_colaboradores_ativos;
            $meses[1][$row->mes] = $row->turnover_afastados;
            $meses[2][$row->mes] = $row->turnover_acidentes;
            $meses[3][$row->mes] = $row->turnover_maternidade;
            $meses[4][$row->mes] = $row->turnover_aposentadoria;
            $meses[5][$row->mes] = $row->turnover_doenca;
            $meses[6][$row->mes] = $row->total_afastados;
            $meses[7][$row->mes] = $row->total_acidentes;
            $meses[8][$row->mes] = $row->total_maternidade;
            $meses[9][$row->mes] = $row->total_aposentadoria;
            $meses[10][$row->mes] = $row->total_doenca;
        }

        $data = array();
        for ($i = 0; $i < 11; $i++) {
            if (empty($output->data)) {
                break;
            }
            $data[] = array(
                $coluna[$i],
                isset($meses[$i]['01']) ? str_replace('.', ',', round($meses[$i]['01'], 2)) : null,
                isset($meses[$i]['02']) ? str_replace('.', ',', round($meses[$i]['02'], 2)) : null,
                isset($meses[$i]['03']) ? str_replace('.', ',', round($meses[$i]['03'], 2)) : null,
                isset($meses[$i]['04']) ? str_replace('.', ',', round($meses[$i]['04'], 2)) : null,
                isset($meses[$i]['05']) ? str_replace('.', ',', round($meses[$i]['05'], 2)) : null,
                isset($meses[$i]['06']) ? str_replace('.', ',', round($meses[$i]['06'], 2)) : null,
                isset($meses[$i]['07']) ? str_replace('.', ',', round($meses[$i]['07'], 2)) : null,
                isset($meses[$i]['08']) ? str_replace('.', ',', round($meses[$i]['08'], 2)) : null,
                isset($meses[$i]['09']) ? str_replace('.', ',', round($meses[$i]['09'], 2)) : null,
                isset($meses[$i]['10']) ? str_replace('.', ',', round($meses[$i]['10'], 2)) : null,
                isset($meses[$i]['11']) ? str_replace('.', ',', round($meses[$i]['11'], 2)) : null,
                isset($meses[$i]['12']) ? str_replace('.', ',', round($meses[$i]['12'], 2)) : null,
                str_replace('.', ',', round(array_sum($meses[$i]) / max(count(array_filter($meses[$i], function ($v) {
                        return strlen($v) > 0;
                    })), 1), in_array($i, [1, 2, 3, 4, 5]) ? 2 : 0))
            );
        }

        if ($return) {
            return $data;
        }

        $output->recordsTotal = count($data);
        $output->recordsFiltered = count($data);
        $output->ano = $ano;
        $output->data = $data;

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function ajaxListFaltasAtrasos($return = false)
    {
        parse_str($this->input->post('busca'), $busca);
        $ano = !empty($busca['ano']) ? $busca['ano'] : date('Y');
        $quadroAtual = $this->input->post('quadro_atual');


        $this->db->select('a.id, a.nome, b.mes, b.total_faltas, b.total_atrasos');
        $this->db->join('requisicoes_pessoal_faltas_atrasos b', "b.id_depto = a.id AND b.ano = '{$ano}'", 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));

        $query = $this->db->get('empresa_departamentos a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $rows = array();
        $totalFaltas = array();
        $totalAtrasos = array();
        foreach ($output->data as $estrutura) {
            $rows[$estrutura->id] = $estrutura->nome;
            if ($estrutura->total_faltas) {
                $totalFaltas[$estrutura->id][$estrutura->mes] = $estrutura->total_faltas;
            }
            if ($estrutura->total_atrasos) {
                $totalAtrasos[$estrutura->id][$estrutura->mes] = $estrutura->total_atrasos;
            }
        }

        if ($quadroAtual) {
            $dataLimite = date('Y-m-t', mktime(0, 0, 0, intval($busca['mes']) - 1, 1, $ano));
            $dataInicial = date('Y-m-d', mktime(0, 0, 0, intval($busca['mes']) - 1, 1, $ano));
            $sql = "SELECT s.id, 
                           COUNT(s.id_usuario) AS total_faltas, 
                           COUNT(s.id_usuario) AS total_atrasos
                    FROM (SELECT a.id, b.id AS id_usuario
                          FROM empresa_departamentos a
                          LEFT JOIN usuarios b ON b.id_depto = a.id or b.depto = a.nome
                          WHERE b.tipo = 'funcionario' AND b.status IN (1, 6, 7, 8, 9) AND 
                                b.datacadastro <= '{$dataLimite}'
                          UNION
                          SELECT a.id, c.id_usuario
                          FROM empresa_departamentos a
                          INNER JOIN usuarios b ON b.id_depto = a.id or b.depto = a.nome
                          INNER JOIN usuarios_afastamento c on c.id_usuario = b.id
                          WHERE c.data_afastamento <= '{$dataLimite}' AND 
                                (c.data_retorno >= '{$dataInicial}' OR c.data_retorno IS NULL)) s 
                    GROUP BY s.id";
            $quadros = $this->db->query($sql)->result();
            foreach ($quadros as $quadro) {
                $totalFaltas[$quadro->id][$busca['mes']] = $quadro->total_faltas;
                $totalAtrasos[$quadro->id][$busca['mes']] = $quadro->total_atrasos;
            }
        }

        $data = array();
        foreach ($rows as $id => $nome) {
            $data[] = array(
                $nome,
                $totalFaltas[$id]['01'] ?? null,
                $totalAtrasos[$id]['01'] ?? null,
                $totalFaltas[$id]['02'] ?? null,
                $totalAtrasos[$id]['02'] ?? null,
                $totalFaltas[$id]['03'] ?? null,
                $totalAtrasos[$id]['03'] ?? null,
                $totalFaltas[$id]['04'] ?? null,
                $totalAtrasos[$id]['04'] ?? null,
                $totalFaltas[$id]['05'] ?? null,
                $totalAtrasos[$id]['05'] ?? null,
                $totalFaltas[$id]['06'] ?? null,
                $totalAtrasos[$id]['06'] ?? null,
                $totalFaltas[$id]['07'] ?? null,
                $totalAtrasos[$id]['07'] ?? null,
                $totalFaltas[$id]['08'] ?? null,
                $totalAtrasos[$id]['08'] ?? null,
                $totalFaltas[$id]['09'] ?? null,
                $totalAtrasos[$id]['09'] ?? null,
                $totalFaltas[$id]['10'] ?? null,
                $totalAtrasos[$id]['10'] ?? null,
                $totalFaltas[$id]['11'] ?? null,
                $totalAtrasos[$id]['11'] ?? null,
                $totalFaltas[$id]['12'] ?? null,
                $totalAtrasos[$id]['12'] ?? null,
                isset($totalFaltas[$id]) ? round(array_sum($totalFaltas[$id]) / max(count(array_filter($totalFaltas[$id])), 1)) : null,
                isset($totalAtrasos[$id]) ? round(array_sum($totalAtrasos[$id]) / max(count(array_filter($totalAtrasos[$id])), 1)) : null,
                $id
            );
        }

        if ($return) {
            return $data;
        }

        $output->recordsTotal = count($data);
        $output->recordsFiltered = $output->recordsTotal;
        $output->ano = $ano;
        $output->data = $data;

        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    public function ajaxEdit()
    {
        $where = array(
            'id_empresa' => $this->session->userdata('empresa'),
            'mes' => $this->input->post('mes'),
            'ano' => $this->input->post('ano')
        );

        $data = $this->db->get_where('requisicoes_pessoal_relatorios', $where)->row();
        if (empty($data)) {
            $data = $where;
        }

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    public function ajaxEditEstruturas()
    {
        $where = array(
            'id_empresa' => $this->session->userdata('empresa'),
            'id_depto' => $this->input->post('id_depto'),
            'mes' => $this->input->post('mes'),
            'ano' => $this->input->post('ano')
        );

        $data = $this->db->get_where('requisicoes_pessoal_estruturas', $where)->row_array();
        if (empty($data)) {
            $data = $where;
            $data['id'] = null;
        }

        $this->db->select('nome');
        $this->db->where('id', $where['id_depto']);
        $depto = $this->db->get('empresa_departamentos')->row();
        $data['depto'] = $depto->nome;

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    public function ajaxImportarAfastamentos()
    {
        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');
        $dataAfastamento = date('Y-m-t', strtotime("{$ano}-{$mes}-01"));
        $dataRetorno = date('Y-m-d', strtotime("{$ano}-{$mes}-01"));

        $this->db->select(['SUM(IF(motivo_afastamento = 3, 1, 0)) AS total_acidentes'], false);
        $this->db->select(['SUM(IF(motivo_afastamento = 2, 1, 0)) AS total_maternidade'], false);
        $this->db->select(['SUM(IF(motivo_afastamento = 4, 1, 0)) AS total_aposentadoria'], false);
        $this->db->select(['SUM(IF(motivo_afastamento = 1, 1, 0)) AS total_doenca'], false);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('data_afastamento <=', $dataAfastamento);
        $this->db->where("(data_retorno > '{$dataRetorno}' OR data_retorno IS NULL)", null, false);
        $data = $this->db->get('usuarios_afastamento')->row();

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    public function ajaxSave()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }

        if ($id) {
            $status = $this->db->update('requisicoes_pessoal_relatorios', $data, ['id' => $id]);
        } else {
            $status = $this->db->insert('requisicoes_pessoal_relatorios', $data);
        }

        echo json_encode(['status' => $status !== false]);
    }

    // -------------------------------------------------------------------------

    public function ajaxAddEstruturas()
    {
        $data = $this->input->post();
        if (!empty($data['id_empresa']) == false) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }

        $status = $this->db->insert('requisicoes_pessoal_estruturas', $data);

        echo json_encode(['status' => $status !== false]);
    }

    // -------------------------------------------------------------------------

    public function ajaxSaveEstruturas()
    {
        $data = $this->input->post();
        $id = $data['id'];

        if (strlen(trim($data['total_colaboradores'])) > 0) {
            unset($data['id']);
            if (!empty($data['id_empresa']) == false) {
                $data['id_empresa'] = $this->session->userdata('empresa');
            }

            $status = $this->db->update('requisicoes_pessoal_estruturas', $data, ['id' => $id]);
        } else {
            $status = $this->db->delete('requisicoes_pessoal_estruturas', ['id' => $id]);
        }

        echo json_encode(['status' => $status !== false]);
    }

    // -------------------------------------------------------------------------

    public function salvarEstruturas()
    {
        $empresa = $this->session->userdata('empresa');
        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');

        $dataLimite = date('Y-m-t', mktime(0, 0, 0, intval($mes) - 1, 1, $ano));
        $dataInicial = date('Y-m-d', mktime(0, 0, 0, intval($mes) - 1, 1, $ano));

        $sql = "SELECT s.id_depto, 
                       s.id_empresa,
                       COUNT(s.id_usuario) AS total_colaboradores,
                       '{$mes}' AS mes,
                       '{$ano}' AS ano
                FROM (SELECT a.id AS id_depto, 
                             a.id_empresa,
                             b.id AS id_usuario
                      FROM empresa_departamentos a
                      LEFT JOIN usuarios b ON (b.id_depto = a.id OR b.depto = a.nome)  AND 
                                b.tipo = 'funcionario' AND 
                                b.status IN (1, 6, 7, 8, 9) AND 
                                b.datacadastro <= '{$dataLimite}'
                      WHERE a.id_empresa = '{$empresa}'
                      UNION
                      SELECT a.id, 
                             a.id_empresa,
                             c.id_usuario
                      FROM empresa_departamentos a
                      INNER JOIN usuarios b ON b.id_depto = a.id OR b.depto = a.nome
                      INNER JOIN usuarios_afastamento c on c.id_usuario = b.id
                      WHERE a.id_empresa = '{$empresa}' AND 
                            c.data_afastamento <= '{$dataLimite}' AND 
                            (c.data_retorno >= '{$dataInicial}' OR c.data_retorno IS NULL)) s 
                GROUP BY s.id_depto";
        $rows = $this->db->query($sql)->result();
        $data = array();
        foreach ($rows as $row) {
            $data[$row->id_depto] = $row;
        }

        $this->db->select('a.id AS id_depto, b.id');
        $this->db->join('requisicoes_pessoal_estruturas b', "b.id_depto = a.id AND b.ano = '{$ano}' AND b.mes = '{$mes}'", 'left');
        $this->db->where('a.id_empresa', $empresa);

        $estruturas = $this->db->get('empresa_departamentos a')->result();

        $this->db->trans_start();

        foreach ($estruturas as $estrutura) {
            if ($estrutura->id) {
                $this->db->update('requisicoes_pessoal_estruturas', $data[$estrutura->id_depto], ['id' => $estrutura->id]);
            } else {
                $this->db->insert('requisicoes_pessoal_estruturas', $data[$estrutura->id_depto]);
            }
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(['status' => $status !== false]);
    }

    // -------------------------------------------------------------------------

    public function limparEstruturas()
    {
        $where = array(
            'id_empresa' => $this->session->userdata('empresa'),
            'mes' => $this->input->post('mes'),
            'ano' => $this->input->post('ano')
        );

        $status = $this->db->delete('requisicoes_pessoal_estruturas', $where);

        echo json_encode(['status' => $status !== false]);
    }

    // -------------------------------------------------------------------------

    public function limparTurnover()
    {
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('mes', $this->input->post('mes'));
        $this->db->where('ano', $this->input->post('ano'));
        $turnover = $this->db->get('requisicoes_pessoal_relatorios')->row_array();

        $id = $turnover['id'];
        $data = array(
            'id' => $turnover['id'],
            'id_empresa' => $turnover['id_empresa'],
            'mes' => $turnover['mes'],
            'ano' => $turnover['ano'],
            'total_colaboradores_admitidos' => null,
            'total_colaboradores_demitidos' => null,
            'total_colaboradores_justa_causa' => null,
            'total_colaboradores_desligados' => null,
            'total_demissoes_desligamentos' => null
        );

        $turnover = array_filter(array_diff_key($turnover, $data));

        if ($turnover) {
            $status = $this->db->update('requisicoes_pessoal_relatorios', $data, ['id' => $id]);
        } else {
            $status = $this->db->delete('requisicoes_pessoal_relatorios', ['id' => $id]);
        }


        echo json_encode(['status' => $status !== false]);
    }

    // -------------------------------------------------------------------------

    public function limparAfastamentos()
    {
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('mes', $this->input->post('mes'));
        $this->db->where('ano', $this->input->post('ano'));
        $afastamentos = $this->db->get('requisicoes_pessoal_relatorios')->row_array();

        $id = $afastamentos['id'];
        $data = array(
            'id' => $afastamentos['id'],
            'id_empresa' => $afastamentos['id_empresa'],
            'mes' => $afastamentos['mes'],
            'ano' => $afastamentos['ano'],
            'total_afastados' => null,
            'total_acidentes' => null,
            'total_maternidade' => null,
            'total_aposentadoria' => null,
            'total_doenca' => null
        );

        $afastamentos = array_filter(array_diff_key($afastamentos, $data));

        if ($afastamentos) {
            $status = $this->db->update('requisicoes_pessoal_relatorios', $data, ['id' => $id]);
        } else {
            $status = $this->db->delete('requisicoes_pessoal_relatorios', ['id' => $id]);
        }


        echo json_encode(['status' => $status !== false]);
    }

    // -------------------------------------------------------------------------

    public function salvarTurnover()
    {
        $empresa = $this->session->userdata('empresa');
        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');

        $dataAnterior = date('Y-m-d', strtotime('-5 month', mktime(0, 0, 0, intval($mes), 1, $ano)));

        $sql = "SELECT '{$mes}' AS mes, 
                       '{$ano}' AS ano,
                       empresa AS id_empresa,
                       COUNT(CASE WHEN status = 1 THEN 1 END) AS total_colaboradores_ativos,
                       COUNT(CASE WHEN YEAR(data_admissao) = '{$ano}' AND MONTH(data_admissao) = '{$mes}' THEN 1 END) AS total_colaboradores_admitidos,
                       COUNT(CASE WHEN YEAR(data_demissao) = '{$ano}' AND MONTH(data_demissao) = '{$mes}' AND tipo_demissao IN (1, 4, 6, 8, 9) THEN 1 END) AS total_colaboradores_demitidos,
                       COUNT(CASE WHEN YEAR(data_demissao) = '{$ano}' AND MONTH(data_demissao) = '{$mes}' AND tipo_demissao = 2 THEN 1 END) AS total_colaboradores_justa_causa,
                       COUNT(CASE WHEN YEAR(data_demissao) = '{$ano}' AND MONTH(data_demissao) = '{$mes}' AND tipo_demissao IN (3, 5, 7)  THEN 1 END) AS total_colaboradores_desligados,
                       COUNT(CASE WHEN data_demissao < '{$dataAnterior}' THEN 1 END) AS total_demissoes_desligamentos
                FROM usuarios
                WHERE empresa = '{$empresa}' AND
                      tipo = 'funcionario'";
        $data = $this->db->query($sql)->row();

        $where = array(
            'id_empresa' => $empresa,
            'ano' => $ano,
            'mes' => $mes
        );
        $this->db->where($where);
        $afastamento = $this->db->get('requisicoes_pessoal_relatorios')->num_rows();

        $this->db->trans_start();

        if ($afastamento) {
            $this->db->update('requisicoes_pessoal_relatorios', $data, $where);
        } else {
            $this->db->insert('requisicoes_pessoal_relatorios', $data);
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(['status' => $status !== false]);
    }

    // -------------------------------------------------------------------------

    public function salvarAfastamentos()
    {
        $empresa = $this->session->userdata('empresa');
        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');

        $dataLimite = date('Y-m-t', mktime(0, 0, 0, intval($mes), 1, $ano));
        $dataInicial = date('Y-m-d', mktime(0, 0, 0, intval($mes), 1, $ano));

        $sql = "SELECT '{$mes}' AS mes, 
                       '{$ano}' AS ano,
                       a.id_empresa,
                       COUNT(CASE a.motivo_afastamento WHEN 1 THEN 1 END) AS total_doenca,
                       COUNT(CASE a.motivo_afastamento WHEN 2 THEN 1 END) AS total_maternidade,
                       COUNT(CASE a.motivo_afastamento WHEN 3 THEN 1 END) AS total_acidentes,
                       COUNT(CASE a.motivo_afastamento WHEN 4 THEN 1 END) AS total_aposentadoria,
                       (SELECT COUNT(b.id) 
                        FROM usuarios b
                        WHERE b.empresa = a.id_empresa AND 
                              b.tipo = 'funcionario' AND 
                              b.status = 1) AS total_colaboradores_ativos
                FROM usuarios_afastamento a
                WHERE a.id_empresa = '{$empresa}' AND 
                      a.data_afastamento <= '{$dataLimite}' AND
                      (a.data_retorno > '{$dataInicial}' OR a.data_retorno IS NULL)";
        $data = $this->db->query($sql)->row();

        $where = array(
            'id_empresa' => $empresa,
            'ano' => $ano,
            'mes' => $mes
        );
        $this->db->where($where);
        $afastamento = $this->db->get('requisicoes_pessoal_relatorios')->num_rows();

        $this->db->trans_start();

        if ($afastamento) {
            $this->db->update('requisicoes_pessoal_relatorios', $data, $where);
        } else {
            $this->db->insert('requisicoes_pessoal_relatorios', $data);
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(['status' => $status !== false]);
    }

    public function relatorio()
    {
        $this->ajaxRelatorio();
    }

    public function ajaxRelatorio($pdf = false)
    {
        $data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');

        $_POST['busca'] = http_build_query($this->input->get());
        $_POST['length'] = -1;
        $_POST['draw'] = 0;

        $data['quadroColaboradores'] = $this->ajaxListColaboradores(true);
        $data['requisicoesPessoal'] = $this->ajaxListRequisicoes(true);
        $data['turnover'] = $this->ajaxListTurnover(true);
        $data['afastamentos'] = $this->ajaxListAfastamentos(true);
        $data['faltasAtrasos'] = $this->ajaxListFaltasAtrasos(true);

        $data['ano'] = $this->input->get('ano');
        $data['is_pdf'] = $pdf;

        if ($pdf) {
            return $this->load->view('gestao_de_pessoal_relatorio', $data, true);
        }

        $this->load->view('gestao_de_pessoal_relatorio', $data);
    }

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#table tbody tr td { font-weight: bold; text-align: center; } ';
        $stylesheet .= '.table_gestao { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '.table_gestao thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '.table_gestao tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
        $stylesheet .= '.table_gestao tfoot th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->ajaxRelatorio(true));

        $ano = $this->input->get('ano');

        $this->m_pdf->pdf->Output("Consolidado de Gestão de Pessoas - {$ano}.pdf", 'D');
    }

}
