<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    //==========================================================================
    public function index()
    {

        $empresa = $this->session->userdata('empresa');


        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $departamentos = $this->db->get('cd_diretorias')->result();

        //$data['depto'] = array('' => 'Todos');
        $data['depto'] = array();
        foreach ($departamentos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        if ($this->session->userdata('nivel') == 10) {
            $this->db->where('c.id_supervisor', $this->session->userdata('id'));
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $diretorias = $this->db->get('cd_diretorias a')->result();
        $data['diretoria'] = array('' => 'Todas');

        foreach ($diretorias as $diretoria) {
            $data['diretoria'][$diretoria->id] = $diretoria->nome;
        }


        $this->db->select('d.id, d.nome');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('cd_supervisores c', 'c.id_escola = a.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->where('b.id_empresa', $empresa);
        if ($this->session->userdata('nivel') == 10) {
            $this->db->where('d.id', $this->session->userdata('id'));
        }
        $this->db->order_by('d.nome', 'asc');
        $supervisores = $this->db->get('cd_escolas a')->result();
        if ($this->session->userdata('nivel') == 10 and count($supervisores) > 0) {
            $data['supervisor'] = array();
        } else {
            $data['supervisor'] = array('' => 'Todos');
        }
        foreach ($supervisores as $supervisor) {
            $data['supervisor'][$supervisor->id] = $supervisor->nome;
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

        $this->db->select('a.id, d.nome');
        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('cd_cuidadores c', 'c.id = a.id_vinculado');
        $this->db->join('usuarios d', 'd.id = c.id_cuidador');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where("DATE_FORMAT(b.data, '%Y-%m') =", date('Y-m'));
        $cuidadores = $this->db->get('cd_alocados a')->result();
        $data['usuarios'] = array('' => 'selecione...');
        foreach ($cuidadores as $cuidador) {
            $data['usuarios'][$cuidador->id] = $cuidador->nome;
        }


        $modo_privilegiado = true;
        $data['modo_privilegiado'] = $modo_privilegiado;
        $data['depto_atual'] = count($departamentos) > 1 ? '' : 'Cuidadores';
        $data['diretoria_atual'] = '';
        if (in_array($this->session->userdata('nivel'), array(9, 10))) {
            $data['supervisor_atual'] = $supervisores[0]->nome ?? '';
        } else {
            $data['supervisor_atual'] = '';
        }

        $data['id_diretoria'] = array('' => 'selecione...');
        $data['id_escola'] = array('' => 'selecione...');
        $data['id_alocado'] = array('' => 'selecione...');

        $this->load->view('cd/apontamento', $data);
    }

    //==========================================================================
    public function ajax_list()
    {
        parse_str($this->input->post('busca'), $busca);


        $this->db->select('b.municipio, a.escola, a.turno');
        $this->db->select("CASE a.remanejado WHEN 0 THEN 'A contratar' WHEN 1 THEN 'Remanejado' WHEN 2 THEN 'Alocar cuidador' ELSE a.cuidador END AS cuidador", false);
        $this->db->select('a.id, a.id_vinculado AS id_cuidador, a.remanejado', false);
        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('cd_diretorias c', 'c.nome = b.diretoria');
        $this->db->join('usuarios d', 'd.nome = b.supervisor');
        $this->db->join('usuarios e', 'e.nome = a.cuidador', 'left');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('b.depto', $busca['depto']);
        $this->db->where('c.id', $busca['diretoria']);
        $this->db->where('d.id', $busca['supervisor']);
        $this->db->where('YEAR(b.data)', $busca['ano']);
        $this->db->where('MONTH(b.data)', $busca['mes']);
        $this->db->group_by(['a.escola', 'a.turno', 'a.id']);
        $this->db->order_by('a.cuidador', 'asc');

        $alocados = $this->db->get('cd_alocados a');


        $this->load->library('dataTables');
        $rows = $this->datatables->generate($alocados);


        $this->load->library('Calendar');
        $dias_semana = $this->calendar->get_day_names('long');
        $semana = array();
        for ($i = 1; $i <= 7; $i++) {
            $semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
        }

        $rows->calendar = array(
            'mes' => $busca['mes'],
            'ano' => $busca['ano'],
            'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
            'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
            'semana' => $semana
        );


        $this->db->select(["a.id, a.id_cuidador_sub, a.qtde_dias"], false);
        $this->db->select(["DATE_FORMAT(a.apontamento_asc, '%H:%i') AS apontamento_asc"], false);
        $this->db->select(["DATE_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_desc"], false);
        $this->db->select('a.saldo, a.observacoes, a.status, b.nome');
        $this->db->select(["a.id_alocado, DATE_FORMAT(a.data, '%d') AS dia"], false);
        $this->db->join('usuarios b', 'b.id = a.id_cuidador_sub', 'left');
        $this->db->where("(a.status = 'FE' OR a.data <= NOW())", null, false);
        $this->db->where_in('a.id_alocado', $alocados->result() ? array_column($alocados->result(), 'id') : [0]);
        $eventos = $this->db->get('cd_apontamento a')->result();

        $apontamento = array();

        foreach ($eventos as $evento) {
            $apontamento[$evento->id_alocado][intval($evento->dia)] = array(
                $evento->id . '',
                $evento->id_cuidador_sub . '',
                $evento->qtde_dias . '',
                $evento->apontamento_asc . '',
                $evento->apontamento_desc . '',
                $evento->saldo . '',
                $evento->observacoes . '',
                $evento->status . '',
                $evento->nome . '',
            );
        }


        $data = array();

        $diaSolicitado = strtotime($busca['ano'] . '-' . $busca['mes'] . '-' . date('t'));
//        $diaLimite = date(($diaSolicitado < strtotime(date('Y-m-t')) ? 't' : 'd'));
        $diaLimite = $diaSolicitado < strtotime(date('Y-m-t')) ? date('t', strtotime($diaSolicitado)) : date('t');

        foreach ($rows->data as $k => $row) {
            $rowData = array(
                $row->id,
                $row->municipio,
                $row->escola,
                $row->turno,
                [$row->cuidador, $row->id_cuidador, $row->remanejado]
            );

            for ($i = 1; $i <= 31; $i++) {
                if ($i > $diaLimite) {
                    $rowData[] = null;
                    continue;
                }
                $rowData[] = $apontamento[$row->id][$i] ?? [''];
            }

            $data[] = $rowData;
        }


        $rows->data = $data;

        echo json_encode($rows);
    }

    //==========================================================================
    public function ajax_funcionarios()
    {
        parse_str($this->input->post('busca'), $busca);


        $this->db->select('IFNULL(a.municipio, b.municipio) AS municipio, a.escola', false);
        $this->db->select("(CASE a.turno WHEN 'M' THEN 1 WHEN 'T' THEN 2 WHEN 'N' THEN 3 ELSE 0 END) AS num_turno", false);
        $this->db->select("CASE a.remanejado WHEN 0 THEN 'A contratar' WHEN 1 THEN 'Remanejado' WHEN 2 THEN 'Alocar cuidador' ELSE a.cuidador END AS cuidador", false);
        $this->db->select('a.id, a.id_vinculado AS id_cuidador, a.remanejado, a.turno', false);
        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('cd_diretorias c', 'c.nome = b.diretoria');
        $this->db->join('usuarios d', 'd.nome = b.supervisor');
        $this->db->join('usuarios e', 'e.nome = a.cuidador', 'left');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('b.depto', $busca['depto']);
        $this->db->where('c.id', $busca['diretoria']);
        $this->db->where('d.id', $busca['supervisor']);
        $this->db->where('YEAR(b.data)', $busca['ano']);
        $this->db->where('MONTH(b.data)', $busca['mes']);
        $this->db->group_by(['a.escola', 'a.turno', 'a.cuidador', 'a.id']);
        $this->db->order_by('a.cuidador', 'asc');

        $alocados = $this->db->get('cd_alocados a');


        $this->load->library('dataTables');
        $rows = $this->datatables->generate($alocados);


        $this->load->library('Calendar');
        $dias_semana = $this->calendar->get_day_names('long');
        $semana = array();
        for ($i = 1; $i <= 7; $i++) {
            $semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
        }

        $rows->calendar = array(
            'mes' => $busca['mes'],
            'ano' => $busca['ano'],
            'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
            'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
            'semana' => $semana
        );


        $this->db->select(["a.id, a.id_cuidador_sub, a.qtde_dias"], false);
        $this->db->select(["DATE_FORMAT(a.apontamento_asc, '%H:%i') AS apontamento_asc"], false);
        $this->db->select(["DATE_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_desc"], false);
        $this->db->select('a.saldo, a.observacoes, a.status, b.nome');
        $this->db->select(["a.id_alocado, DATE_FORMAT(a.data, '%d') AS dia"], false);
        $this->db->join('usuarios b', 'b.id = a.id_cuidador_sub', 'left');
        $this->db->where("(a.status = 'FE' OR a.data <= NOW())", null, false);
        $this->db->where_in('a.id_alocado', $alocados->result() ? array_column($alocados->result(), 'id') : [0]);
        $eventos = $this->db->get('cd_apontamento a')->result();

        $apontamento = array();

        foreach ($eventos as $evento) {
            $apontamento[$evento->id_alocado][intval($evento->dia)] = array(
                $evento->id . '',
                $evento->id_cuidador_sub . '',
                $evento->qtde_dias . '',
                $evento->apontamento_asc . '',
                $evento->apontamento_desc . '',
                $evento->saldo . '',
                $evento->observacoes . '',
                $evento->status . '',
                $evento->nome . '',
            );
        }


        $data = array();

        $diaSolicitado = strtotime($busca['ano'] . '-' . $busca['mes'] . '-' . date('t'));
//        $diaLimite = date(($diaSolicitado < strtotime(date('Y-m-t')) ? 't' : 'd'));
        $diaLimite = $diaSolicitado < strtotime(date('Y-m-t')) ? date('t', strtotime($diaSolicitado)) : date('t');


        foreach ($rows->data as $k => $row) {
            $rowData = array(
                $row->id,
                $row->municipio,
                $row->escola,
                $row->cuidador,
                $row->id_cuidador,
                $row->turno
            );

            for ($i = 1; $i <= 31; $i++) {
                if ($i > $diaLimite) {
                    $rowData[] = null;
                    continue;
                }
                $rowData[] = $apontamento[$row->id][$i] ?? [''];
            }

            $data[] = $rowData;
        }


        $rows->data = $data;

        echo json_encode($rows);
    }

    //==========================================================================
    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $depto = $this->input->post('depto');
        $diretoria = $this->input->post('diretoria');
        $supervisor = $this->input->post('supervisor');


        $this->db->select('a.id, a.nome');
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        if ($this->session->userdata('nivel') == 10) {
            $this->db->where('c.id_supervisor', $this->session->userdata('id'));
        } else {
            if (!empty($depto)) {
                $this->db->where('a.depto', $depto);
            }
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_diretorias = $this->db->get('cd_diretorias a')->result();
        $filtro['diretoria'] = array('' => 'Todas');
        foreach ($rows_diretorias as $row_diretoria) {
            $filtro['diretoria'][$row_diretoria->id] = $row_diretoria->nome;
        }


        $this->db->select('d.id, d.nome');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('cd_supervisores c', 'c.id_escola = a.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->where('b.id_empresa', $empresa);
        if ($this->session->userdata('nivel') == 10) {
            $this->db->where('d.id', $this->session->userdata('id'));
        } else {
            if (!empty($depto)) {
                $this->db->where('b.depto', $depto);
            }
            if (!empty($diretoria)) {
                $this->db->where('b.id', $diretoria);
            }
        }
        $this->db->group_by('c.id_supervisor');
        $this->db->order_by('d.nome', 'asc');
        $rows_supervisores = $this->db->get('cd_escolas a')->result();
        if ($this->session->userdata('nivel') == 10 and count($rows_supervisores) > 0) {
            $filtro['supervisor'] = array();
        } else {
            $filtro['supervisor'] = array('' => 'Todos');
        }
        foreach ($rows_supervisores as $row_supervisor) {
            $filtro['supervisor'][$row_supervisor->id] = $row_supervisor->nome;
        }


        $data['diretoria'] = form_dropdown('diretoria', $filtro['diretoria'], $diretoria, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['supervisor'] = form_dropdown('supervisor', $filtro['supervisor'], $supervisor, 'class="form-control input-sm"');

        echo json_encode($data);
    }

    //==========================================================================
    public function novo()
    {
        // Prepara as variáveis auxiliares
        $empresa = $this->session->userdata('empresa');

        $depto = $this->input->post('depto');

        $id_diretoria = $this->input->post('diretoria');
        $this->db->select('nome');
        $_rowDiretoria = $this->db->get_where('cd_diretorias', array('id' => $id_diretoria))->row();
        $diretoria = $_rowDiretoria->nome ?? '';

        $id_supervisor = $this->input->post('supervisor');
        $this->db->select('nome');
        $_rowSupervisor = $this->db->get_where('usuarios', array('id' => $id_supervisor))->row();
        $supervisor = $_rowSupervisor->nome ?? '';

        $_mes = $this->input->post('mes');
        $_ano = $this->input->post('ano');
        $_dia = $this->input->post('dia');
        if (empty($_dia)) {
            $_dia = '01';
        }
        if (checkdate($_mes, $_dia, $_ano) == false) {
            exit(json_encode(array('erro' => 'A data para alocar o mês é inválida.')));
        }
        $_timestamp = strtotime($_dia . '-' . $_mes . '-' . $_ano);
        $mesAno = date('Y-m', $_timestamp);
        $diaMesAno = date('Y-m-d', $_timestamp);


        // Verifica se existem escolas a serem alocadas
        $this->db->select('a.id');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('cd_supervisores c', 'c.id_escola = a.id');
        $this->db->where('b.id', $id_diretoria);
        if ($depto) {
            $this->db->where('b.depto', $depto);
        }
        if ($id_supervisor) {
            $this->db->where('c.id_supervisor', $id_supervisor);
        }
        $escolas = $this->db->get('cd_escolas a')->num_rows();

        if (empty($escolas)) {
            exit(json_encode(array('erro' => 'Nenhuma escola encontrada para alocação.')));
        }


        // Verifica se alocação já foi realizada
        $this->db->where('id_empresa', $empresa);
        $this->db->where("DATE_FORMAT(data, '%Y-%m') =", $mesAno);
        $this->db->where('diretoria', $diretoria);
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        if ($supervisor) {
            $this->db->where('supervisor', $supervisor);
        }
        $alocacao = $this->db->get('cd_alocacao')->num_rows();
        if ($alocacao) {
            exit(json_encode(array('erro' => 'A alocação de cuidadores da diretoria ou supervisor(a) selecionados já foi realizada para este mês.')));
        }


        // Prepara subquery de exceção e recuperação do mês alocado
        $sqlAlocacao = "SELECT id, id_empresa, data, depto, diretoria, coordenador, supervisor, municipio
                FROM cd_alocacao
                WHERE DATE_FORMAT(data, '%Y-%m') = '{$mesAno}'";


        // Prepara a alocação do mês
        $this->db->select('a.depto, a.nome AS diretoria, d.nome AS supervisor');
        $this->db->select("'{$empresa}' AS id_empresa, a.id_coordenador AS coordenador, a.municipio", false);
        $this->db->select("STR_TO_DATE('{$diaMesAno}', '%Y-%m-%d') AS data", false);
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->join("({$sqlAlocacao}) e", 'e.depto = a.depto AND e.diretoria = a.nome AND e.supervisor = d.nome AND e.municipio = a.municipio', 'left');
        $this->db->where('a.id_empresa', $empresa);
        if ($depto) {
            $this->db->where('a.depto', $depto);
        }
        if ($id_diretoria) {
            $this->db->where('a.id', $id_diretoria);
        }
        if ($id_supervisor) {
            $this->db->where('c.id_supervisor', $id_supervisor);
        } else {
            $this->db->where('e.id', null);
        }
        $this->db->group_by(array('a.depto', 'a.id', 'a.id_coordenador'));
        $this->db->order_by('a.depto ASC, a.id ASC, a.id_coordenador ASC');
        $data = $this->db->get('cd_diretorias a')->result_array();
        if (empty($data)) {
            exit(json_encode(array('erro' => 'Nenhum registro encontrado para alocação.')));
        }


        $this->db->trans_start();
        $this->db->insert_batch('cd_alocacao', $data);


        // Prepara a alocação dos cuidadores e dos alunos
        $data2 = array();
        $data3 = array();


        // Prepara resultado de alunos por turno
        $sqlAlunos = "SELECT t1.*, CAST('M' AS CHAR) AS turno FROM cd_alunos t1 WHERE t1.periodo_manha = 1 AND t1.status IN ('A','N')
              UNION
              SELECT t2.*, CAST('T' AS CHAR) AS turno FROM cd_alunos t2 WHERE t2.periodo_tarde = 1 AND t2.status IN ('A','N')
              UNION
              SELECT t3.*, CAST('N' AS CHAR) AS turno FROM cd_alunos t3 WHERE t3.periodo_noite = 1 AND t3.status IN ('A','N')";


        foreach ($data as $row) {
            // Prepara os dados dos cuidadores por alocacão
            $this->db->select('e.id AS id_alocacao, g.id AS id_vinculado, a.municipio, a.nome AS escola', false);
            $this->db->select("IFNULL(h.nome, 'A contratar/Remanejado') AS cuidador", false);
            $this->db->select('e.supervisor, c.turno');
            $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
            $this->db->join('cd_supervisores c', 'c.id_escola = a.id');
            $this->db->join('usuarios d', 'd.id = c.id_supervisor');
            $this->db->join("({$sqlAlocacao}) e", 'e.id_empresa = b.id_empresa AND e.depto = b.depto AND e.diretoria = b.nome AND e.supervisor = d.nome AND e.municipio = b.municipio');
            $this->db->join("({$sqlAlunos}) f", 'f.id_escola = a.id AND f.turno = c.turno');
            $this->db->join('cd_cuidadores g', 'g.id_escola = a.id AND g.turno = f.turno', 'left');
            $this->db->join('usuarios h', 'h.id = g.id_cuidador', 'left');
            $this->db->where('e.id_empresa', $row['id_empresa']);
            $this->db->where('e.data', $row['data']);
            $this->db->where('e.depto', $row['depto']);
            $this->db->where('e.diretoria', $row['diretoria']);
            $this->db->where('e.municipio', $row['municipio']);
            $this->db->where('e.supervisor', $row['supervisor']);
            $this->db->group_by('e.id, h.nome, a.nome, c.turno');
            $this->db->order_by('a.nome', 'asc');
            $this->db->order_by('h.nome', 'asc');
            $this->db->order_by('f.periodo_manha', 'desc');
            $this->db->order_by('f.periodo_tarde', 'desc');
            $this->db->order_by('f.periodo_noite', 'desc');
            $rows2 = $this->db->get('cd_escolas a')->result_array();

            foreach ($rows2 as $row2) {
                $data2[] = $row2;
            }

            // Prepara os dados dos alunos por alocação
            $this->db->select('e.id AS id_alocacao, f.id AS id_aluno, f.nome AS aluno, a.nome AS escola', false);
            $this->db->select('f.hipotese_diagnostica, f.status, e.supervisor, c.turno');
            $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
            $this->db->join('cd_supervisores c', 'c.id_escola = a.id');
            $this->db->join('usuarios d', 'd.id = c.id_supervisor');
            $this->db->join("({$sqlAlocacao}) e", 'e.id_empresa = b.id_empresa AND e.depto = b.depto AND e.diretoria = b.nome AND e.supervisor = d.nome AND e.municipio = b.municipio');
            $this->db->join("({$sqlAlunos}) f", 'f.id_escola = a.id AND f.turno = c.turno');
            $this->db->where('e.id_empresa', $row['id_empresa']);
            $this->db->where('e.data', $row['data']);
            $this->db->where('e.depto', $row['depto']);
            $this->db->where('e.diretoria', $row['diretoria']);
            $this->db->where('e.municipio', $row['municipio']);
            $this->db->where('e.supervisor', $row['supervisor']);
            $this->db->group_by('e.id, f.id, a.nome, c.turno');
            $this->db->order_by('a.nome', 'asc');
            $this->db->order_by('f.nome', 'asc');
            $this->db->order_by('f.periodo_manha', 'desc');
            $this->db->order_by('f.periodo_tarde', 'desc');
            $this->db->order_by('f.periodo_noite', 'desc');
            $rows3 = $this->db->get('cd_escolas a')->result_array();

            foreach ($rows3 as $row3) {
                $data3[] = $row3;
            }
        }

        if ($data2) {
            $this->db->insert_batch('cd_alocados', $data2);
        }
        if ($data3) {
            $this->db->insert_batch('cd_matriculados', $data3);
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_frequencias()
    {
        $empresa = $this->session->userdata('empresa');
        parse_str($this->input->post('busca'), $busca);
        $post = $this->input->post();


        $sql = "SELECT a.id,
                       b.municipio,
                       x.escola,
                       e.id_supervisor,
                       (CASE x.turno 
                             WHEN 'M' THEN 1
                             WHEN 'T' THEN 2
                             WHEN 'N' THEN 3
                             ELSE 0 END) AS id_turno,
                       a.aluno,
                       a.id_alocacao,
                       x.turno
                 FROM cd_alocacao b
                 INNER JOIN cd_alocados x
                            ON b.id = x.id_alocacao
                 INNER JOIN cd_diretorias c ON 
                            c.nome = b.diretoria AND 
                            c.depto = b.depto AND 
                            c.id_empresa = b.id_empresa AND 
                            c.municipio = b.municipio
                 INNER JOIN cd_escolas d ON 
                            d.id_diretoria = c.id
                 INNER JOIN cd_supervisores e ON 
                            e.id_escola = d.id
                 INNER JOIN usuarios f ON
                            f.id = e.id_supervisor
                            AND f.nome = b.supervisor
                 LEFT JOIN cd_matriculados a 
                           ON a.id_alocacao = b.id
                           AND a.escola = x.escola
                           AND a.turno = x.turno
                           AND a.status IN ('A','N')
                 WHERE c.id_empresa = {$empresa}
                       AND DATE_FORMAT(b.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
        if ($busca['depto']) {
            $sql .= " AND c.depto = '{$busca['depto']}'";
        }
        $sql .= " AND c.id = '{$busca['diretoria']}'";
        if ($busca['supervisor']) {
            $sql .= " AND e.id_supervisor = '{$busca['supervisor']}'";
        }
        $sql .= ' GROUP BY b.municipio, x.escola, x.turno, a.aluno, a.turno 
                  ORDER BY a.escola ASC, a.turno, a.aluno ASC';

        $config = array('search' => ['s.escola', 's.aluno']);
        $this->load->library('dataTables', $config);

        $rows = $this->datatables->query($sql);


        $matriculados = array_filter(array_column($rows->data, 'id'));

        $this->db->select('a.id, a.id_matriculado, a.status');
        $this->db->select(["DATE_FORMAT(a.data, '%e') AS dia"], false);
        $this->db->select('COUNT(c.id) AS qtde_consumo', false);
        $this->db->join('cd_matriculados b', 'b.id = a.id_matriculado AND a.data <= NOW()');
        $this->db->join('cd_consumos c', 'c.id_frequencia = a.id', 'left');
        $this->db->where_in('b.id', count($matriculados) > 0 ? $matriculados : [0]);
        $this->db->group_by(['b.id', 'a.id']);
        $frequencias = $this->db->get('cd_frequencias a')->result();

        $apontamento = array();
        foreach ($frequencias as $frequencia) {
            $apontamento[$frequencia->id_matriculado][$frequencia->dia] = array(
                $frequencia->id,
                $frequencia->status,
                $frequencia->qtde_consumo
            );
        }


        $data = array();

        foreach ($rows->data as $row) {
            $rowData = array(
                $row->id,
                $row->municipio,
                $row->escola,
                $row->id_supervisor,
                $row->turno,
                $row->aluno,
                $row->id_alocacao
            );
            for ($i = 1; $i <= 31; $i++) {
                $rowData[] = $apontamento[$row->id][$i] ?? [];
            }

            $data[] = $rowData;
        }

        $this->load->library('Calendar');
        $dias_semana = $this->calendar->get_day_names('long');
        $semana = array();
        for ($i = 1; $i <= 7; $i++) {
            $semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
        }


        $rows->calendar = array(
            'mes' => $busca['mes'],
            'ano' => $busca['ano'],
            'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
            'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
            'semana' => $semana
        );

        $rows->data = $data;

        echo json_encode($rows);
    }

    //==========================================================================
    public function ajax_cuidadores()
    {
        $empresa = $this->session->userdata('empresa');
        parse_str($this->input->post('busca'), $busca);
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.municipio_escola,
                       s.cuidador,
                       s.data_hora_admissao,
                       s.vale_transporte,
                       s.id_turno,
                       s.aluno,
                       s.hipotese_diagnostica,
                       s.turno,
                       s.data_admissao
                FROM (SELECT a.id,
                             CONCAT(a.municipio, ' &emsp; <strong>Escola:</strong> ', b.escola) AS municipio_escola,
                             (CASE b.turno 
				                   WHEN 'M' THEN 1
				                   WHEN 'T' THEN 2
				                   WHEN 'N' THEN 3
				                   END) AS id_turno,
                             (CASE b.turno 
                                   WHEN 'M' THEN 'Manhã'
                                   WHEN 'T' THEN 'Tarde'
                                   WHEN 'N' THEN 'Noite'
                                   END) AS turno,
				             GROUP_CONCAT(CONCAT('<strong>&#149; </strong><span', CASE WHEN b.id_vinculado IS NULL THEN ' class=\"text-danger\"' ELSE '' END, '>', CASE b.remanejado WHEN 0 THEN 'A contratar' WHEN 1 THEN 'Remanejado' WHEN 2 THEN 'Alocar cuidador' ELSE b.cuidador END) ORDER BY b.cuidador SEPARATOR '</span><br>') AS cuidador,
				             GROUP_CONCAT(CONCAT('<strong>&#149; </strong>', c.data_admissao) ORDER BY b.cuidador SEPARATOR '<br>') AS data_hora_admissao,
				             GROUP_CONCAT(CONCAT('<strong>&#149; </strong>', DATE_FORMAT(c.data_admissao, '%d/%m/%Y')) ORDER BY b.cuidador SEPARATOR '<br>') AS data_admissao,
				             GROUP_CONCAT(CONCAT('<strong>&#149; </strong>', IF(CHAR_LENGTH(c.valor_vt) > 0,CONCAT(c.nome_cartao, ' (', c.valor_vt, ')'), c.nome_cartao)) ORDER BY b.cuidador SEPARATOR '<br>') AS vale_transporte,
                             DATE_FORMAT(c.data_admissao, '%d/%m/%Y') AS data_admissao2,
                             IF(CHAR_LENGTH(c.valor_vt) > 0,CONCAT(c.nome_cartao, ' (', c.valor_vt, ')'), c.nome_cartao)  AS vale_transporte2,
                             d.aluno,
                             d.hipotese_diagnostica
                      FROM cd_alocacao a
                      INNER JOIN cd_alocados b 
                                 ON b.id_alocacao = a.id
                      LEFT JOIN (SELECT c1.*, c2.id AS id_vinculado 
                                  FROM usuarios c1 
                                  INNER JOIN cd_cuidadores c2 
                                             ON c2.id_cuidador = c1.id) c 
                                 ON c.id_vinculado = b.id_vinculado
                      LEFT JOIN cd_matriculados d 
                                ON d.id_alocacao = a.id 
                                AND d.escola = b.escola
                                AND d.turno = b.turno
                                AND d.status IN ('A','N')
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}
                            AND DATE_FORMAT(a.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'
                            AND (a.depto = '{$busca['depto']}' OR CHAR_LENGTH('{$busca['depto']}') = 0)
                            AND (CHAR_LENGTH('{$busca['diretoria']}') > 0
                                 AND a.diretoria = (SELECT nome 
                                                   FROM cd_diretorias 
                                                   WHERE id = '{$busca['diretoria']}'))
                            AND (CHAR_LENGTH('{$busca['supervisor']}') > 0
                                 AND b.supervisor = (SELECT nome 
                                                    FROM usuarios 
                                                    WHERE id = '{$busca['supervisor']}'))
                      GROUP BY b.escola, b.turno, d.aluno, d.turno
                      ORDER BY a.municipio, 
                               b.cuidador, 
                               b.escola, 
                               b.turno, 
                               d.aluno) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.municipio_escola',
            's.cuidador',
            'data_hora_admissao',
            'vale_transporte',
            's.turno',
            's.aluno',
            'hipotese_diagnostica'
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
        foreach ($list as $apontamento) {
            $row = array();
            $row[] = $apontamento->id;
            $row[] = $apontamento->municipio_escola;
            $row[] = $apontamento->cuidador;
            $row[] = $apontamento->data_admissao;
            $row[] = $apontamento->vale_transporte;
            $row[] = $apontamento->turno;
            $row[] = $apontamento->aluno;
            $row[] = $apontamento->hipotese_diagnostica;

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
    public function ajax_cuidadores_sub()
    {
        $empresa = $this->session->userdata('empresa');
        parse_str($this->input->post('busca'), $busca);
        $turno = $this->input->post('turno');
        $id_alocado = $this->input->post('id_alocado');
        $value = $this->input->post('value');


        $this->db->select('a.id, a.nome');
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id');
        $this->db->where('a.id_empresa', $empresa);
        if (!empty($busca['depto'])) {
            $this->db->where('a.depto', $busca['depto']);
        }
        if (!empty($busca['diretoria'])) {
            $this->db->where('a.id', $busca['diretoria']);
            $diretorias = array();
        } else {
            $diretorias = array('' => 'selecione...');
        }
        if (!empty($busca['supervisor'])) {
            $this->db->where('c.id_supervisor', $busca['supervisor']);
        }
        $rows_diretorias = $this->db->get('cd_diretorias a')->result();
        foreach ($rows_diretorias as $row_diretoria) {
            $diretorias[$row_diretoria->id] = $row_diretoria->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('cd_supervisores c', 'c.id_escola = a.id');
        $this->db->where('b.id_empresa', $empresa);
        if (!empty($busca['depto'])) {
            $this->db->where('b.depto', $busca['depto']);
        }
        if (!empty($busca['diretoria'])) {
            $this->db->where('b.id', $busca['diretoria']);
        } else {

        }
        if (!empty($busca['supervisor'])) {
            $this->db->where('c.id_supervisor', $busca['supervisor']);
        }
        $rows_escolas = $this->db->get('cd_escolas a')->result();
        $escolas = array('' => 'selecione...');
        foreach ($rows_escolas as $row_escolas) {
            $escolas[$row_escolas->id] = $row_escolas->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_cuidadores b', 'b.id_cuidador = a.id');
        $this->db->join('cd_escolas c', 'c.id = b.id_escola');
        $this->db->join('cd_diretorias d', 'd.id = c.id_diretoria');
        $this->db->join('cd_supervisores g', 'g.id_escola = c.id', 'left');
        $this->db->join('cd_alocados e', 'e.id_vinculado = b.id', 'left');
        $this->db->join('cd_alocacao f', "f.id = e.id_alocacao AND DATE_FORMAT(f.data, '%Y-%m') = {$busca['ano']}-{$busca['mes']}", 'left');
        if (!empty($busca['depto'])) {
            $this->db->where('d.depto', $busca['depto']);
        }
        if (!empty($busca['diretoria'])) {
            $this->db->where('d.id', $busca['diretoria']);
        }
        if (!empty($busca['supervisor'])) {
            $this->db->where('g.id_supervisor', $busca['supervisor']);
        }
        $this->db->where('e.id', null);
        $rows_alocados = $this->db->get('usuarios a')->result();
        $usuarios = array('' => 'selecione...');
        foreach ($rows_alocados as $row_alocado) {
            $usuarios[$row_alocado->id] = $row_alocado->nome;
        }


        $this->db->select('d.id, d.nome');
        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('cd_cuidadores c', 'c.id = a.id_vinculado');
        $this->db->join('usuarios d', 'd.id = c.id_cuidador');
        $this->db->join('cd_escolas e', 'e.id = c.id_escola');
        $this->db->join('cd_diretorias f', 'f.id = e.id_diretoria');
        $this->db->join('cd_supervisores g', 'g.id_escola = e.id', 'left');
        $this->db->where('b.id_empresa', $empresa);
        if (!empty($busca['depto'])) {
            $this->db->where('f.depto', $busca['depto']);
        }
        if (!empty($busca['diretoria'])) {
            $this->db->where('f.id', $busca['diretoria']);
        }
        if (!empty($busca['supervisor'])) {
            $this->db->where('g.id_supervisor', $busca['supervisor']);
        }
        $this->db->where("DATE_FORMAT(b.data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
        $this->db->group_by('d.id');
        $this->db->order_by('d.nome', 'asc');
        $cuidadores = $this->db->get('cd_alocados a')->result();
        $alocados = array('' => 'selecione...');
        foreach ($cuidadores as $cuidador) {
            $alocados[$cuidador->id] = $cuidador->nome;
        }

        $sql = "SELECT a.id, 
                       a.nome
                FROM usuarios a
                INNER JOIN cd_cuidadores b 
                           ON b.id_cuidador = a.id
                INNER JOIN cd_escolas c
                           ON c.id = b.id_escola
                INNER JOIN cd_diretorias d 
                           ON d.id = c.id_diretoria
                WHERE a.empresa = {$empresa} AND
                      (d.id = (SELECT i.id_diretoria 
                              FROM cd_alocados h 
                              INNER JOIN cd_escolas i ON i.nome = h.escola
                              WHERE h.id = '{$id_alocado}') 
                       OR CHAR_LENGTH('{$id_alocado}') = 0) AND
                      a.tipo =  'funcionario' AND
                      a.depto = 'Cuidadores' AND
                      a.status IN ('1', '3')";
//        $sql = "SELECT b.id, 
//                       b.nome
//                FROM cd_cuidadores a
//                INNER JOIN usuarios b ON 
//                           b.id = a.id_cuidador
//                INNER JOIN cd_escolas c ON
//                           c.id = a.id_escola
//                INNER JOIN cd_diretorias d ON
//                           d.id = c.id_diretoria
//                LEFT JOIN cd_supervisores e ON 
//                           e.id_escola = c.id
//                WHERE d.id_empresa = {$empresa} AND 
//                      a.turno != '{$turno}'";
//        if (!empty($busca['depto'])) {
//            $sql .= " AND d.depto = '{$busca['depto']}'";
//        }
//        if (!empty($busca['diretoria'])) {
//            $sql .= " AND d.id = {$busca['diretoria']}";
//        }
//        if (!empty($busca['supervisor'])) {
//            $sql .= " AND e.id_supervisor = {$busca['supervisor']}";
//        }
        $sql .= ' ORDER BY a.nome asc';
        $cuidadores_sub = $this->db->query($sql)->result();
        $options = array('' => 'selecione...');
        foreach ($cuidadores_sub as $cuidador_sub) {
            $options[$cuidador_sub->id] = $cuidador_sub->nome;
        }

        $data['id_diretoria'] = form_dropdown('', $diretorias, $busca['diretoria'], 'id="id_diretoria" class="form-control" autocomplete="off"');
        $data['id_escola'] = form_dropdown('', $escolas, '', 'id="id_escola" class="form-control" autocomplete="off"');
        $data['id_usuario'] = form_dropdown('id_vinculado', $usuarios, '', 'class="form-control" autocomplete="off"');
        $data['id_usuario_alocado'] = form_dropdown('id_vinculado', $alocados, '', 'class="form-control" autocomplete="off"');
        $data['id_cuidador_sub'] = form_dropdown('id_cuidador_sub', $options, $value, 'class="form-control"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_novo_cuidador()
    {
        $empresa = $this->session->userdata('empresa');
        $diretoria = $this->input->post('diretoria');
        $escola = $this->input->post('escola');
        $vinculado = $this->input->post('vinculado');

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        if (!empty($diretoria)) {
            $this->db->where('b.id', $diretoria);
        }
        $this->db->order_by('a.nome');
        $rows_escolas = $this->db->get('cd_escolas a')->result();
        $escolas = array('' => 'selecione...');
        foreach ($rows_escolas as $row_escola) {
            $escolas[$row_escola->id] = $row_escola->nome;
        }


        $this->db->select('d.id, d.nome');
        $this->db->join('cd_escolas b', 'b.id = a.id_escola');
        $this->db->join('cd_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('usuarios d', 'd.id = a.id_cuidador');
        if (!empty($escola)) {
            $this->db->where('b.id', $escola);
        }
        if (!empty($diretoria)) {
            $this->db->where('c.id', $diretoria);
        }
        $this->db->order_by('d.nome');
        $rows_vinculados = $this->db->get('cd_cuidadores a')->result();
        $vinculados = array('' => 'selecione...');
        foreach ($rows_vinculados as $row_vinculado) {
            $vinculados[$row_vinculado->id] = $row_vinculado->nome;
        }

        $data['id_escola'] = form_dropdown('id_escola', $escolas, $escola, 'id="id_escola" class="form-control" onchange="atualizarCuidadores();" autocomplete="off"');
        $data['id_vinculado'] = form_dropdown('id_vinculado', $vinculados, $vinculado, 'class="form-control" autocomplete="off"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_novo_matriculado()
    {
        $empresa = $this->session->userdata('empresa');
        $diretoria = $this->input->post('diretoria');
        $escola = $this->input->post('escola');

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('id_empresa', $empresa);
        if (!empty($diretoria)) {
            $this->db->where('b.id', $diretoria);
        }
        $this->db->order_by('a.nome');
        $rows_escolas = $this->db->get('cd_escolas a')->result();
        $escolas = array('' => 'selecione...');
        foreach ($rows_escolas as $row_escola) {
            $escolas[$row_escola->id] = $row_escola->nome;
        }

        $data['id_escola'] = form_dropdown('id_escola', $escolas, $escola, 'id="id_escola" class="form-control" onchange="atualizarCuidadores();" autocomplete="off"');

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

    //==========================================================================
    public function ajax_config()
    {
        $busca = $this->input->post();

        $this->db->select("a.id, DATE_FORMAT(a.data, '%Y') AS mes, DATE_FORMAT(a.data, '%Y') AS ano, e.nome AS supervisor", false);
        $this->db->select('a.diretoria, a.id AS id_alocacao, 0 AS total_faltas, 0 AS total_faltas_justificadas', false);
        $this->db->select('0 AS turnover_substituicao, 0 AS turnover_aumento_quadro, 0 AS turnover_desligamento_empresa, 0 AS turnover_desligamento_solicitacao', false);
        $this->db->select('0 AS intercorrencias_diretoria, 0 AS intercorrencias_cuidador, 0 AS intercorrencias_alunos, 0 AS acidentes_trabalho', false);
        $this->db->select('0 AS total_escolas, 0 AS total_alunos, 0 AS dias_letivos', false);
        $this->db->select('0 AS total_cuidadores, 0 AS total_cuidadores_cobrados, 0 AS total_cuidadores_ativos, 0 AS total_cuidadores_afastados', false);
        $this->db->select('0 AS total_supervisores, 0 AS total_supervisores_cobrados, 0 AS total_supervisores_ativos, 0 AS total_supervisores_afastados', false);
        $this->db->select('0 AS faturamento_projetado, 0 AS faturamento_realizado', false);

        /*$this->db->select('NULL AS visitas_projetadas, NULL AS visitas_realizadas, NULL AS visitas_porcentagem, NULL AS visitas_total_horas', false);
        $this->db->select('NULL AS balanco_valor_projetado, NULL AS balanco_glosas, NULL AS balanco_valor_glosa, NULL AS balanco_porcentagem', false);
        $this->db->select('NULL AS atendimentos_total_mes, NULL AS atendimentos_media_diaria', false);
        $this->db->select('NULL AS pendencias_total_informada, NULL AS pendencias_aguardando_tratativa, NULL AS pendencias_parcialmente_resolvidas', false);
        $this->db->select('NULL AS pendencias_resolvidas, NULL AS pendencias_resolvidas_atendimentosa, NULL AS monitoria_media_equipe', false);
        $this->db->select('NULL AS indicadores_operacionais_tma, NULL AS indicadores_operacionais_tme, NULL AS indicadores_operacionais_ociosidade', false);
        $this->db->select('NULL AS avaliacoes_atendimento, NULL AS avaliacoes_atendimento_otimos, NULL AS avaliacoes_atendimento_bons', false);
        $this->db->select('NULL AS avaliacoes_atendimento_regulares, NULL AS avaliacoes_atendimento_ruins', false);
        $this->db->select('NULL AS solicitacoes, NULL AS solicitacoes_atendidas, NULL AS solicitacoes_nao_atendidas, NULL AS observacoes', false);*/

        $this->db->join('cd_diretorias b', 'b.nome = a.diretoria AND b.depto = a.depto');
        $this->db->join('cd_escolas c', 'c.id_diretoria = b.id');
        $this->db->join('cd_supervisores d', 'd.id_escola = c.id');
        $this->db->join('usuarios e', 'e.id = d.id_supervisor');
        if (!empty($busca['depto'])) {
            $this->db->where('a.depto', $busca['depto']);
        }
        if (!empty($busca['diretoria'])) {
            $this->db->where('b.id', $busca['diretoria']);
        }
        if (!empty($busca['supervisor'])) {
            $this->db->where('d.id_supervisor', $busca['supervisor']);
        }
        $this->db->where("DATE_FORMAT(a.data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
        $this->db->group_by('a.id');
        $alocacao = $this->db->get('cd_alocacao a')->row();
        if (empty($alocacao)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhuma alocação encontrada neste mês')));
        }

        $this->db->select("*, '{$alocacao->mes}' AS mes, '{$alocacao->ano}' AS ano", false);
        $this->db->where('id_alocacao', $alocacao->id);
        $this->db->where('supervisor', $alocacao->supervisor);
        $data = $this->db->get('cd_observacoes')->row();
        if (empty($data)) {
            $data = $alocacao;
            unset($data->id);
        }
        if ($data->faturamento_projetado) {
            $data->faturamento_projetado = number_format($data->faturamento_projetado, 2, ',', '.');
        }
        if ($data->faturamento_realizado) {
            $data->faturamento_realizado = number_format($data->faturamento_realizado, 2, ',', '.');
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_editConfig()
    {
        $busca = $this->input->post();

        $this->db->select('a.nome AS diretoria, a.depto, d.nome AS supervisor', false);
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->where('a.id', $busca['diretoria']);
        $this->db->where('c.id_supervisor', $busca['supervisor']);
        $row = $this->db->get('cd_diretorias a')->row();

        $this->db->select('a.id, 0 AS total_escolas, 0 AS total_alunos', false);
        $this->db->select('0 AS total_cuidadores, 0 AS total_cuidadores_cobrados', false);
        $this->db->select('0 AS total_cuidadores_ativos, 0 AS total_cuidadores_afastados', false);
        $this->db->select('0 AS total_supervisores, 0 AS total_supervisores_cobrados', false);
        $this->db->select('0 AS total_supervisores_ativos, 0 AS total_supervisores_afastados', false);
        $this->db->select('0 AS faturamento_projetado, 0 AS faturamento_realizado', false);
        $this->db->select("COUNT(CASE c.status WHEN 'FA' THEN c.status END) AS total_faltas_justificadas", false);
        $this->db->select("COUNT(CASE c.status WHEN 'FS' THEN c.status END) AS total_faltas", false);
        $this->db->select("COUNT(CASE c.status WHEN 'ID' THEN c.status END) AS intercorrencias_diretoria", false);
        $this->db->select("COUNT(CASE c.status WHEN 'IC' THEN c.status END) AS intercorrencias_cuidador", false);
        $this->db->select("COUNT(CASE c.status WHEN 'IA' THEN c.status END) AS intercorrencias_alunos", false);
        $this->db->select("COUNT(CASE c.status WHEN 'AT' THEN c.status END) AS acidentes_trabalho", false);

        $this->db->join('cd_alocados b', 'b.id_alocacao = a.id');
        $this->db->join('cd_apontamento c', 'c.id_alocado = b.id', 'left');
        $this->db->where("DATE_FORMAT(a.data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
        $this->db->where('a.depto', $row->depto);
        $this->db->where('a.diretoria', $row->diretoria);
        $this->db->where('a.supervisor', $row->supervisor);
        $this->db->group_by('a.id');
        $faltas = $this->db->get('cd_alocacao a')->row();


        $this->db->select("DISTINCT(escola)", false);
        $this->db->where('id_alocacao', $faltas->id);
        //$this->db->where('supervisor', $row->supervisor);
        $faltas->total_escolas = $this->db->get('cd_alocados')->num_rows();


        $this->db->select("DISTINCT(aluno)", false);
        $this->db->where('id_alocacao', $faltas->id);
        //$this->db->where('supervisor', $row->supervisor);
        $faltas->total_alunos = $this->db->get('cd_matriculados')->num_rows();


        $this->db->select("DISTINCT(cuidador)", false);
        $this->db->where('id_alocacao', $faltas->id);
        //$this->db->where('supervisor', $row->supervisor);
        $faltas->total_cuidadores_ativos = $this->db->get('cd_alocados')->num_rows();
        $faltas->total_cuidadores_cobrados = $faltas->total_cuidadores_ativos;


        $this->db->select("DISTINCT(a.id_supervisor)", false);
        $this->db->join('cd_escolas b', 'b.id = a.id_escola');
        $this->db->join('cd_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('cd_supervisores d', 'd.id_escola = b.id');
        $this->db->where('c.id', $busca['diretoria']);
        $this->db->where('d.id_supervisor', $busca['supervisor']);
        $faltas->total_supervisores_ativos = $this->db->get('cd_supervisores a')->num_rows();
        $faltas->total_supervisores_cobrados = $faltas->total_supervisores_ativos;

        $sqlData = "SELECT z.dias - FLOOR(z.dias / 6) - z.feriados AS dias_uteis
                    FROM (SELECT DAY(LAST_DAY(x.data)) - FLOOR((DAY(LAST_DAY(x.data)) + WEEKDAY(x.data)) / 7) AS dias, 
                                 IFNULL(v.feriado, 0) AS feriados
                          FROM (SELECT '{$busca['ano']}-{$busca['mes']}-01' as data) x
                          LEFT JOIN (SELECT data, IF(SUM(status = 'FE') > 0, 1, 0) AS feriado 
                                     FROM cd_apontamento 
                                     GROUP BY YEAR(data), MONTH(data), id) v
                                    ON DATE_FORMAT(v.data, '%Y-%m') = DATE_FORMAT(x.data, '%Y-%m')) z";
        $dias_letivos = $this->db->query($sqlData)->row();
        $faltas->dias_letivos = $dias_letivos->dias_uteis;
        $data = $faltas;

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_saveConfig()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);

        $campos_alocacao = array('depto', 'diretoria', 'mes', 'ano');
        foreach ($campos_alocacao as $campo_alocacao) {
            if (isset($data[$campo_alocacao])) {
                unset($data[$campo_alocacao]);
            }
        }

        foreach ($data as $k => $row) {
            if (strlen($row) == 0) {
                $data[$k] = null;
            }
        }


        $this->db->select('nome');
        $this->db->where('id', $data['supervisor']);
        $supervisor = $this->db->get('usuarios')->row();
        $data['id_supervisor'] = $data['supervisor'];
        $data['supervisor'] = $supervisor->nome ?? null;
        if ($data['faturamento_projetado']) {
            $data['faturamento_projetado'] = str_replace(array('.', ','), array('', '.'), $data['faturamento_projetado']);
        }
        if ($data['faturamento_realizado']) {
            $data['faturamento_realizado'] = str_replace(array('.', ','), array('', '.'), $data['faturamento_realizado']);
        }


        if ($id) {
            $status = $this->db->update('cd_observacoes', $data, array('id' => $id));
        } else {
            $status = $this->db->insert('cd_observacoes', $data);
        }


        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_edit_frequencia()
    {
        $id_frequencia = $this->input->post('id_frequencia');

        if ($id_frequencia) {
            $this->db->select('a.id, a.nome, a.tipo, IFNULL(b.qtde, 0) AS qtde', false);
            $this->db->join('cd_consumos b', "b.id_insumo = a.id AND b.id_frequencia = '{$id_frequencia}'", 'left');
        } else {
            $this->db->select('a.id, a.nome, a.tipo, 0 AS qtde', false);
        }
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('a.id', 'asc');
        $rows = $this->db->get('cd_insumos a')->result();

        $this->load->library('table');
        $this->table->set_template(array(
            'table_open' => '<table class="table table-condensed" width="100%">'
        ));

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $this->table->add_row(
                    $row->nome, form_input(array(
                    'name' => "qtde_insumos[{$row->id}]",
                    'value' => $row->qtde,
                    'type' => 'number',
                    'class' => 'form-control qtde_insumos text-right input-sm',
                    'style' => 'width: 100px;'
                )), $row->tipo);
            }
        } else {
            $this->table->add_row('<span class="text-center">Nenhum insumo encontrado.</span>');
        }

        $data['qtde_insumos'] = $this->table->generate();

        echo json_encode($data);
    }

    //==========================================================================
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

        $this->db->update('alocacao_usuarios', $data, array('id' => $data['id']));

        echo json_encode(array("status" => true));
    }

    //==========================================================================
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

        $this->db->update('alocacao_usuarios', $data, array('id' => $data['id']));

        echo json_encode(array("status" => true));
    }

    //==========================================================================
    public function ajax_save()
    {
        $data = $this->input->post();
        if (empty($data['id_alocado'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Colaborador não encontrado')));
        }

        $data['data'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data'])));
        if (!empty($data['data_afastamento'])) {
            $data['data_afastamento'] = date("H:i", strtotime($data['data_afastamento']));
        } else {
            $data['data_afastamento'] = null;
        }
        if (!empty($data['apontamento_asc'])) {
            $data['apontamento_asc'] = date("H:i", strtotime($data['apontamento_asc']));
        } else {
            $data['apontamento_asc'] = null;
        }
        if (!empty($data['apontamento_desc'])) {
            $data['apontamento_desc'] = date("H:i", strtotime($data['apontamento_desc']));
        } else {
            $data['apontamento_desc'] = null;
        }
        if (!empty($data['qtde_dias'])) {
            $data['qtde_dias'] = in_array($data['status'], array('FA', 'FS', 'PD')) ? max($data['qtde_dias'], 0) : null;
        } else {
            $data['qtde_dias'] = null;
        }
        if (empty($data['id_cuidador_sub'])) {
            $data['id_cuidador_sub'] = null;
        }
        if (empty($data['observacoes'])) {
            $data['observacoes'] = null;
        } else {
            $data['observacoes'] = str_replace(array(chr(9), chr(10), chr(13)), array('\t', '\n', '\r'), $data['observacoes']);
        }

        if ($data['id']) {
            $status = $this->db->update('cd_apontamento', $data, array('id' => $data['id']));
        } else {
            unset($data['id']);
            $status = $this->db->insert('cd_apontamento', $data);
        }

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajaxSaveEventos()
    {
        parse_str($this->input->post('eventos'), $eventos);
        parse_str($this->input->post('busca'), $busca);

        $this->db->select("a.id AS id_alocado, '{$eventos['data']}' AS data, '{$eventos['status']}' AS status", false);
        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('cd_diretorias c', 'c.nome = b.diretoria');
        $this->db->join('usuarios d', 'd.nome = b.supervisor');
        $this->db->join('cd_supervisores e', 'e.id_supervisor = d.id');
        $this->db->join('cd_apontamento f', "f.id_alocado = a.id AND f.data = '{$eventos['data']}'", 'left');
        $this->db->where('b.depto', $busca['depto']);
        $this->db->where('c.id', $busca['diretoria']);
        $this->db->where('e.id_supervisor', $busca['supervisor']);
        $this->db->where("DATE_FORMAT(b.data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
        $this->db->where('f.data', null);
        $this->db->group_by('a.id');
        $data = $this->db->get('cd_alocados a')->result_array();

        $status = true;
        if ($data) {
            $status = $this->db->insert_batch('cd_apontamento', $data);
        }

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajaxDeleteEventos()
    {
        parse_str($this->input->post('eventos'), $eventos);
        parse_str($this->input->post('busca'), $busca);

        $this->db->select('a.id');
        $this->db->join('cd_alocados b', 'b.id = a.id_alocado');
        $this->db->join('cd_alocacao c', 'c.id = b.id_alocacao');
        $this->db->join('cd_diretorias d', 'd.nome = c.diretoria');
        $this->db->join('usuarios e', 'e.nome = c.supervisor');
        $this->db->join('cd_supervisores f', 'f.id_supervisor = e.id');
        $this->db->where('c.depto', $busca['depto']);
        $this->db->where('d.id', $busca['diretoria']);
        $this->db->where('f.id_supervisor', $busca['supervisor']);
        $this->db->where("DATE_FORMAT(c.data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
        $this->db->where('a.data', $eventos['data']);
        $this->db->where('a.status', $eventos['status']);
        $where = $this->db->get('cd_apontamento a')->result();

        $status = true;
        if ($where) {
            $this->db->where_in('id', array_column($where, 'id'));
            $status = $this->db->delete('cd_apontamento');
        }

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_saveRemanejado()
    {
        $id = $this->input->post('id');
        $remanejado = $this->input->post('remanejado');
        if (!$id) {
            exit('Escola e turno não encontrado');
        }
        $data = array('remanejado' => $remanejado === '' ? null : $remanejado);
        if ($remanejado === '0') {
            $data['cuidador'] = 'A contratar';
        } elseif ($remanejado === '1') {
            $data['cuidador'] = 'Remanejado';
        } elseif ($remanejado === '2') {
            $data['cuidador'] = 'Alocar cuidador';
        } elseif ($remanejado === '') {
            $data['cuidador'] = 'A contratar/Remanejado';
        }

        $status = $this->db->update('cd_alocados', $data, array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_editAlocado()
    {
        $id = $this->input->post('id');

        $this->db->select('a.id, a.id_alocacao, a.escola, a.turno');
        $this->db->select('b.municipio, d.id AS id_escola', false);
        $this->db->select("CASE a.turno WHEN 'M' THEN 'Manhã' WHEN 'T' THEN 'Tarde' WHEN 'N' THEN 'Noite' END AS periodo", false);
        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('cd_diretorias c', 'c.id_empresa = b.id_empresa AND c.depto = b.depto AND c.nome = b.diretoria AND c.municipio = b.municipio', 'left');
        $this->db->join('cd_escolas d', 'd.id_diretoria = c.id AND d.nome = a.escola', 'left');
        $this->db->where('a.id', $id);
        $alocado = $this->db->get('cd_alocados a')->row();
        if (!$alocado) {
            exit('Alocação não encontrada!');
        }

        $data = array(
            'id' => $alocado->id,
            'municipio' => $alocado->municipio,
            'escola' => $alocado->escola,
            'turno' => $alocado->periodo
        );

        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_cuidador');
        $this->db->join('cd_escolas c', 'c.id = a.id_escola');
        $this->db->join('cd_alocados d', "d.escola = c.nome AND d.turno = a.turno AND d.id_alocacao = {$alocado->id_alocacao}", 'left');
        $this->db->where('c.id', $alocado->id_escola);
        $this->db->where('a.turno', $alocado->turno);
        $this->db->where('d.id_vinculado', null);
        $rows = $this->db->get('cd_cuidadores a')->result();
        $cuidadores = array('' => 'selecione...');
        foreach ($rows as $row) {
            $cuidadores[$row->id] = $row->nome;
        }

        $data['id_vinculado'] = form_dropdown('id_vinculado', $cuidadores, '', 'class="form-control" autocomplete="off"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_saveAlocado()
    {
        $id = $this->input->post('id');
        $id_vinculado = $this->input->post('id_vinculado');
        if (!$id) {
            exit(json_encode(array("erro" => 'Escola e turno não encontrado')));
        } else if (!$id_vinculado) {
            exit(json_encode(array("erro" => 'Nenhum cuidador selecionado')));
        }

        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_cuidador');
        $this->db->where('a.id', $id_vinculado);
        $cuidador = $this->db->get('cd_cuidadores a')->row();
        if (!$cuidador) {
            exit(json_encode(array("erro" => 'Cuidador não encontrado')));
        }

        $data = array(
            'id_vinculado' => $cuidador->id,
            'cuidador' => $cuidador->nome,
            'remanejado' => null,
        );

        $status = $this->db->update('cd_alocados', $data, array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_save_frequencia()
    {
        $data = $this->input->post();
        $id = $data['id'];
        $insumos = $data['qtde_insumos'];
        unset($data['id'], $data['qtde_insumos']);

        $this->db->trans_start();

        if (array_filter($insumos) or !empty($data['status'])) {
            if ($id) {
                $this->db->update('cd_frequencias', $data, array('id' => $id));
            } else {
                $this->db->insert('cd_frequencias', $data);
                $id = $this->db->insert_id();
            }

            $this->db->select('id, id_insumo');
            $this->db->where('id_frequencia', $id);
            $rows = $this->db->get('cd_consumos')->result();
            $consumo = array();
            foreach ($rows as $row) {
                $consumo[$row->id_insumo] = $row->id;
            }
        } else {
            $this->db->delete('cd_frequencias', array('id' => $id));
        }

        foreach ($insumos as $id_insumo => $qtde) {
            $data = array(
                'id_frequencia' => $id,
                'id_insumo' => $id_insumo,
                'qtde' => $qtde
            );

            if (isset($consumo[$id_insumo])) {
                if ($qtde > 0) {
                    $this->db->update('cd_consumos', $data, array('id' => $consumo[$id_insumo]));
                } else {
                    $this->db->delete('cd_consumos', array('id' => $consumo[$id_insumo]));
                }
            } elseif ($qtde > 0) {
                $this->db->insert('cd_consumos', $data);
            }
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_limpar_frequencia()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('cd_frequencias', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('cd_apontamento', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_limpar()
    {
        $post = $this->input->post();

        if ($post['diretoria']) {
            $this->db->select('nome');
            $this->db->where('id', $post['diretoria']);
            $diretoria = $this->db->get('cd_diretorias')->row();
            $post['diretoria'] = $diretoria->nome ?? '';
        }

        if ($post['supervisor']) {
            $this->db->select('nome');
            $this->db->where('id', $post['supervisor']);
            $supervisor = $this->db->get('usuarios')->row();
            $post['supervisor'] = $supervisor->nome ?? '';
        }


        $this->db->trans_start();

        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where("DATE_FORMAT(data, '%Y-%m') =", $post['ano'] . '-' . $post['mes']);
        if ($post['depto']) {
            $this->db->where('depto', $post['depto']);
        }
        $this->db->where('diretoria', $post['diretoria']);
        if ($post['supervisor']) {
            $this->db->where('supervisor', $post['supervisor']);
        }
        $this->db->delete('cd_alocacao');


        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_save_cuidador()
    {
        $empresa = $this->session->userdata('empresa');
        $id_escola = $this->input->post('id_escola');
        $ano = $this->input->post('ano');
        $mes = $this->input->post('mes');


        /*$this->db->select('b.nome, a.turno');
        $this->db->join('usuarios b', 'b.id = a.id_cuidador');
        $this->db->join('cd_escolas c', 'c.id = a.id_escola');
        $this->db->join('diretorias d', 'd.id = a.id_diretoria AND d.id_empresa = b.empresa');
        $this->db->where('c.id', $id_escola);
        $this->db->where('d.id_empresa', $empresa);
        $cuidadores = $this->db->get('cd_cuidadores a')->result();*/


        $this->db->trans_start();

        $sql = "SELECT g.id AS id_alocacao, 
                       a.id AS id_vinculado, 
                       b.nome AS cuidador, 
                       e.nome AS escola, 
                       e.municipio, 
                       d.nome AS supervisor, 
                       a.turno
                FROM cd_cuidadores a
                INNER JOIN usuarios b ON b.id = a.id_cuidador
                INNER JOIN cd_supervisores c ON c.id = a.id_supervisor
                INNER JOIN usuarios d ON d.id = c.id_supervisor
                INNER JOIN cd_escolas e ON e.id = c.id_escola
                INNER JOIN cd_diretorias f ON f.id = e.id_diretoria
                LEFT JOIN cd_alocacao g 
                          ON g.depto = f.depto
                          AND g.diretoria = f.nome
                          AND g.id_empresa = f.id_empresa
                          AND g.municipio = f.municipio
                          AND g.supervisor = d.nome
                          AND DATE_FORMAT(g.data, '%Y-%m') = '{$ano}-{$mes}'
                WHERE e.id = '{$id_escola}' 
                      AND f.id_empresa = {$empresa}
                      AND NOT EXISTS (SELECT h.id 
                                  FROM cd_alocados h
                                  WHERE h.id_alocacao = g.id AND 
                                        h.cuidador = b.nome AND 
                                        h.escola = e.nome AND 
                                        h.supervisor = g.supervisor AND 
                                        h.turno = a.turno)";

        $data = $this->db->query($sql)->result_array();

        if (count($data) > 0) {
            $this->db->insert_batch('cd_alocados', $data);
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_save_matriculados()
    {
        $empresa = $this->session->userdata('empresa');
        $id_escola = $this->input->post('id_escola');
        $ano = $this->input->post('ano');
        $mes = $this->input->post('mes');
        $arrTurno = $this->input->post('turno');
        $turno = $arrTurno ? implode("','", $arrTurno) : "''";

        $this->db->trans_start();

        $sql = "SELECT g.id AS id_alocacao, 
                       a.id AS id_aluno, 
                       a.nome AS aluno, 
                       b.nome AS escola, 
                       d.nome AS supervisor,
                       a.hipotese_diagnostica,
                       a.turno
                FROM (SELECT s.*, CAST('M' AS CHAR) AS turno FROM cd_alunos s WHERE s.periodo_manha = 1 AND s.status IN ('A','N')
                      UNION 
                      SELECT s.*, CAST('T' AS CHAR) AS turno FROM cd_alunos s WHERE s.periodo_tarde = 1 AND s.status IN ('A','N')
                      UNION 
                      SELECT s.*, CAST('N' AS CHAR) AS turno FROM cd_alunos s WHERE s.periodo_noite = 1 AND s.status IN ('A','N')) a
                INNER JOIN cd_escolas b ON b.id = a.id_escola
                INNER JOIN cd_supervisores c ON c.id_escola = b.id
                INNER JOIN usuarios d ON d.id = c.id_supervisor
                INNER JOIN cd_diretorias f ON f.id = b.id_diretoria
                LEFT JOIN cd_alocacao g 
                          ON g.depto = f.depto
                          AND g.diretoria = f.nome
                          AND g.id_empresa = f.id_empresa
                          AND g.municipio = f.municipio
                          AND g.supervisor = d.nome
                          AND DATE_FORMAT(g.data, '%Y-%m') = '{$ano}-{$mes}'
                WHERE f.id_empresa = {$empresa}
                      AND b.id = '{$id_escola}'
                      AND a.turno IN ('{$turno}')
                      AND NOT EXISTS (SELECT h.id 
                                  FROM cd_matriculados h
                                  WHERE h.id_alocacao = g.id AND
                                        h.id_aluno = a.id AND 
                                        h.aluno = a.nome AND 
                                        h.escola = b.nome AND 
                                        h.supervisor = g.supervisor AND 
                                        h.turno = a.turno) 
                GROUP BY a.id, a.turno";

        $data = $this->db->query($sql)->result_array();

        if (count($data) > 0) {
            $this->db->insert_batch('cd_matriculados', $data);
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_delete_cuidador()
    {
        $empresa = $this->session->userdata('empresa');
        parse_str($this->input->post('busca'), $busca);
        $id_vinculado = $this->input->post('id_vinculado');

        $this->db->trans_start();

        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('cd_cuidadores c', 'c.id = a.id_vinculado');
        $this->db->join('usuarios d', 'd.id = c.id_cuidador');
        $this->db->where('b.id_empresa', $empresa);
        if ($busca['depto']) {
            $where['depto'] = $busca['depto'];
        }
        if ($busca['diretoria']) {
            $where['diretoria'] = $busca['diretoria'];
        }
        if ($busca['supervisor']) {
            $where['supervisor'] = $busca['supervisor'];
        }
        $this->db->where("DATE_FORMAT(b.data, '%Y-%m') =", $busca['ano'] . '-' . $busca['mes']);
        $this->db->where('d.id', $id_vinculado);
        $this->db->delete('cd_alocados a');

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

}
