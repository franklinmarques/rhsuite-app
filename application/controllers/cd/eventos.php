<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    //==========================================================================
    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $data = array();


        $this->db->select('depto AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->group_by('depto');
        $this->db->order_by('depto');
        $deptos = $this->db->get('cd_alocacao')->result();
        if (count($deptos) === 1) {
            $data['deptos'] = array();
        } else {
            $data['deptos'] = array('' => 'Todos');
        }
        foreach ($deptos as $depto) {
            $data['deptos'][$depto->nome] = $depto->nome;
        }

        $this->db->select('diretoria AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->group_by('diretoria');
        $this->db->order_by('diretoria');
        $diretorias = $this->db->get('cd_alocacao')->result();
        $data['diretorias'] = array('' => 'Todas');
        foreach ($diretorias as $diretoria) {
            $data['diretorias'][$diretoria->nome] = $diretoria->nome;
        }

        $this->db->select('a.supervisor AS nome', false);
        $this->db->join('usuarios b', 'a.supervisor = b.nome');
        $this->db->where('a.id_empresa', $empresa);
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            $this->db->where('b.id', $this->session->userdata('id'));
        }
        $this->db->group_by('a.supervisor');
        $this->db->order_by('a.supervisor');
        $supervisores = $this->db->get('cd_alocacao a')->result();
        if (count($supervisores) === 1) {
            $data['supervisores'] = array();
        } else {
            $data['supervisores'] = array('' => 'Todos');
        }
        foreach ($supervisores as $supervisor) {
            $data['supervisores'][$supervisor->nome] = $supervisor->nome;
        }

        $this->db->select('a.escola AS nome', false);
        $this->db->join('cd_alocacao b', 'a.id_alocacao = b.id');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->group_by('a.escola');
        $this->db->order_by('a.escola');
        $escolas = $this->db->get('cd_alocados a')->result();
        $data['escolas'] = array('' => 'Todas');
        foreach ($escolas as $escola) {
            $data['escolas'][$escola->nome] = $escola->nome;
        }

        $sqlStatus = "SELECT a.status,
                             CASE a.status 
                                  WHEN 'AP' THEN 'Apontamento'
                                  WHEN 'DE' THEN 'Funcionário demitido'
                                  WHEN 'FS' THEN 'Falta sem atestado'
                                  WHEN 'FA' THEN 'Falta com atestado'
                                  WHEN 'AF' THEN 'Funcionário afastado'
                                  WHEN 'AA' THEN 'Aluno ausente'
                                  WHEN 'RE' THEN 'Funcionário remanejado'
                                  WHEN 'NA' THEN 'Funcionário não-alocado'
                                  WHEN 'AD' THEN 'Funcionário admitido'
                                  WHEN 'SL' THEN 'Sábado letivo'
                                  WHEN 'FC' THEN 'Feriado escola/cuidador'
                                  WHEN 'FE' THEN 'Feriado escola'
                                  WHEN 'EM' THEN 'Emenda de feriado'
                                  WHEN 'PC' THEN 'Posto coberto'
                                  WHEN 'ID' THEN 'Intercorrência de Diretoria'
                                  WHEN 'IC' THEN 'Intercorrência de Cuidadores'
                                  WHEN 'IA' THEN 'Intercorrência de Alunos'
                                  WHEN 'AT' THEN 'Acidente de trabalho'
                                  END AS nome 
                      FROM cd_apontamento a 
                      INNER JOIN cd_alocados b 
                                 ON a.id_alocado = b.id
                      INNER JOIN cd_alocacao c
                                 ON b.id_alocacao = c.id
                      WHERE c.id_empresa = {$empresa}
                      GROUP BY a.status 
                      ORDER BY a.status ASC";
        $eventos = $this->db->query($sqlStatus)->result();
        $data['status'] = array('' => 'Todos');
        foreach ($eventos as $evento) {
            $data['status'][$evento->status] = $evento->status . ' - ' . $evento->nome;
        }

        $data['depto_atual'] = '';
        $data['supervisor_atual'] = '';

        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            $this->db->select('nome, depto');
            $this->db->where('id', $this->session->userdata('id'));
            $usuario = $this->db->get('usuarios')->row();
            $data['depto_atual'] = $usuario->depto ?? '';
            $data['supervisor_atual'] = $usuario->nome ?? '';
        }


//        $this->db->select('a.escola');
//        $this->db->join('cd_alocacao b', 'a.id_alocacao = b.id');
//        $this->db->where('b.id_empresa', $empresa);
//        if ($data['depto_atual']) {
//            $this->db->where('b.depto', $data['depto_atual']);
//        }
//        if ($data['supervisor_atual']) {
//            $this->db->where('b.supervisor', $data['supervisor_atual']);
//        }
//        $this->db->group_by('a.escola');
//        $this->db->order_by('a.escola', 'asc');
//        $escolas = $this->db->get('cd_alocados a')->result();
//        $data['escolas'] = array('' => 'Todas');
//        foreach ($escolas as $escola) {
//            $data['escolas'][$escola->id] = $escola->nome;
//        }

        $data['meses'] = array(
            '' => 'Todos',
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
        $data['mes'] = $this->input->get('mes');
        $data['ano'] = $this->input->get('ano');

        $this->load->view('cd/eventos', $data);
    }

    //==========================================================================
    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $get = $this->input->get();
        $filtro = array(
            'diretoria' => array('' => 'Todas'),
            'supervisor' => array('' => 'Todos'),
            'escola' => array('' => 'Todas'),
            'status' => array('' => 'Todos')
        );


        $this->db->select('diretoria AS nome');
        $this->db->where('id_empresa', $empresa);
        if ($get['depto']) {
            $this->db->where('depto', $get['depto']);
        }
        $this->db->group_by('diretoria');
        $this->db->order_by('diretoria', 'asc');
        $diretorias = $this->db->get('cd_alocacao')->result();
        foreach ($diretorias as $diretoria) {
            $filtro['diretoria'][$diretoria->nome] = $diretoria->nome;
        }


        $this->db->select('supervisor AS nome');
        $this->db->where('id_empresa', $empresa);
        if ($get['depto']) {
            $this->db->where('depto', $get['depto']);
        }
        if ($get['diretoria']) {
            $this->db->where('diretoria', $get['diretoria']);
        }
        $this->db->group_by('supervisor');
        $this->db->order_by('supervisor', 'asc');
        $supervisores = $this->db->get('cd_alocacao')->result();
        foreach ($supervisores as $supervisor) {
            $filtro['supervisor'][$supervisor->nome] = $supervisor->nome;
        }


        $this->db->select('a.escola AS nome');
        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->where('b.id_empresa', $empresa);
        if ($get['depto']) {
            $this->db->where('b.depto', $get['depto']);
        }
        if ($get['diretoria']) {
            $this->db->where('b.diretoria', $get['diretoria']);
        }
        if ($get['supervisor']) {
            $this->db->where('b.supervisor', $get['supervisor']);
        }
        $this->db->group_by('a.escola');
        $this->db->order_by('a.escola', 'asc');
        $escolas = $this->db->get('cd_alocados a')->result();
        foreach ($escolas as $escola) {
            $filtro['escola'][$escola->nome] = $escola->nome;
        }


        $sqlStatus = "SELECT a.status AS legenda,
                             CASE a.status 
                                  WHEN 'AP' THEN 'Apontamento'
                                  WHEN 'DE' THEN 'Funcionário demitido'
                                  WHEN 'FS' THEN 'Falta sem atestado próprio'
                                  WHEN 'FA' THEN 'Falta com atestado próprio'
                                  WHEN 'AF' THEN 'Funcionário afastado'
                                  WHEN 'AA' THEN 'Aluno ausente'
                                  WHEN 'RE' THEN 'Funcionário remanejado'
                                  WHEN 'NA' THEN 'Funcionário não-alocado'
                                  WHEN 'AD' THEN 'Funcionário admitido'
                                  WHEN 'SL' THEN 'Sábado letivo'
                                  WHEN 'FC' THEN 'Feriado escola/cuidador'
                                  WHEN 'FE' THEN 'Feriado escola'
                                  WHEN 'EM' THEN 'Emenda de feriado'
                                  WHEN 'PC' THEN 'Posto coberto'
                                  WHEN 'ID' THEN 'Intercorrência de Diretoria'
                                  WHEN 'IC' THEN 'Intercorrência de Cuidadores'
                                  WHEN 'IA' THEN 'Intercorrência de Alunos'
                                  WHEN 'AT' THEN 'Acidente de trabalho'
                                  END AS descricao
                      FROM cd_apontamento a
                      INNER JOIN cd_alocados b ON b.id = a.id_alocado
                      INNER JOIN cd_alocacao c ON c.id = b.id_alocacao
                      WHERE c.id_empresa = {$empresa}";
        if ($get['depto']) {
            $sqlStatus .= " AND c.depto = '{$get['depto']}'";
            $this->db->where('c.depto', $get['depto']);
        }
        if ($get['diretoria']) {
            $sqlStatus .= " AND c.diretoria = '{$get['diretoria']}'";
        }
        if ($get['supervisor']) {
            $sqlStatus .= " AND c.supervisor = '{$get['supervisor']}'";
        }
        if ($get['escola']) {
            $sqlStatus .= " AND b.escola = '{$get['escola']}'";
        }
        $sqlStatus .= " GROUP BY a.status ORDER BY a.status ASC";
        $grupoStatus = $this->db->query($sqlStatus)->result();
        foreach ($grupoStatus as $status) {
            $filtro['status'][$status->legenda] = $status->descricao;
        }


        $data['diretoria'] = form_dropdown('diretoria', $filtro['diretoria'], $get['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['supervisor'] = form_dropdown('supervisor', $filtro['supervisor'], $get['supervisor'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['escola'] = form_dropdown('escola', $filtro['escola'], $get['escola'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['status'] = form_dropdown('status', $filtro['status'], $get['status'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');


        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);


        $this->db->select('b.cuidador, b.escola, a.status, d.nome AS nome_bck, a.observacoes');
        $this->db->select("CASE b.turno WHEN 'M' THEN 1 WHEN 'T' THEN 2 WHEN 'N' THEN 3 END AS id_turno", false);
        $this->db->select("CASE b.turno WHEN 'M' THEN 'Manhã' WHEN 'T' THEN 'Tarde' WHEN 'N' THEN 'Noite' END AS turno", false);
        $this->db->select("DATE_FORMAT(a.data, '%d/%m/%Y') AS data", false);
        $this->db->select("TIME_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_desc", false);
        $this->db->select("TIME_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_asc", false);
        $this->db->join('cd_alocados b', 'b.id = a.id_alocado');
        $this->db->join('cd_alocacao c', 'c.id = b.id_alocacao');
        $this->db->join('usuarios d', 'd.id = a.id_cuidador_sub', 'left');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        if (!empty($busca['depto'])) {
            $this->db->where('c.depto', $busca['depto']);
        }
        if (!empty($busca['diretoria'])) {
            $this->db->where('c.diretoria', $busca['diretoria']);
        }
        if (!empty($busca['supervisor'])) {
            $this->db->where('c.supervisor', $busca['supervisor']);
        }
        if (!empty($busca['escola'])) {
            $this->db->where('b.escola', $busca['escola']);
        }
        if (!empty($busca['status'])) {
            $this->db->where('a.status', $busca['status']);
        }
        if (!empty($busca['periodo_manha']) or !empty($busca['periodo_tarde']) or !empty($busca['periodo_noite'])) {
            $periodos = array();
            if (!empty($busca['periodo_manha'])) {
                $periodos[] = "b.turno = 'M'";
            }
            if (!empty($busca['periodo_tarde'])) {
                $periodos[] = "b.turno = 'T'";
            }
            if (!empty($busca['periodo_noite'])) {
                $periodos[] = "b.turno = 'N'";
            }
            $this->db->where('(' . implode(' OR ', $periodos) . ')', null, false);
        }
        if (!empty($busca['mes'])) {
            $this->db->where('MONTH(c.data) =', $busca['mes']);
        }
        if (!empty($busca['ano'])) {
            $this->db->where('YEAR(c.data) =', $busca['ano']);
        }
        $output = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $this->db->get('cd_apontamento a')->num_rows()
        );

        $sql = "SELECT s.cuidador,
                       s.escola,                       
                       s.id_turno,
                       s.data,
                       s.status,
                       s.nome_bck,
                       s.apontamento_desc,
                       s.apontamento_asc,
                       s.observacoes,
                       s.turno 
                FROM ({$this->db->last_query()}) s";

        if ($post['search']['value']) {
            $sql .= " WHERE (s.cuidador LIKE '%{$post['search']['value']}%' OR 
                             s.escola LIKE '%{$post['search']['value']}%' OR
                             s.nome_bck LIKE '%{$post['search']['value']}%')";
            $output['recordsFiltered'] = $this->db->query($sql)->num_rows();
        } else {
            $output['recordsFiltered'] = $output['recordsTotal'];
        }

        if (!empty($post['order'])) {
            $orderBy = [];
            foreach ($post['order'] as $order) {
                $orderBy[] = intval($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();


        $data = array();
        foreach ($rows as $row) {
            $data[] = array(
                $row->cuidador,
                $row->escola,
                $row->turno,
                $row->data,
                $row->status,
                $row->nome_bck,
                $row->apontamento_desc,
                $row->apontamento_asc,
                $row->observacoes
            );
        }

        $output['data'] = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function pdf()
    {
        $this->load->library('m_pdf');


        $stylesheet = '#titulo thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#titulo thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#titulo tbody td { font-size: 10px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 10px; padding: 5px; vertical-align: top; } ';
        $stylesheet .= '#legenda thead th { font-size: 13px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#legenda tbody td { font-size: 11px; padding: 5px; vertical-align: top; } ';


        $this->m_pdf->pdf->setTopMargin(12);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);


        $get = $this->input->get();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();


        $sql = "SELECT b.cuidador, 
                       b.escola, 
                       a.status, 
                       d.nome AS nome_bck, 
                       a.observacoes, 
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
                       DATE_FORMAT(a.data, '%d/%m/%Y') AS data, 
                       TIME_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_desc, 
                       TIME_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_asc
                FROM cd_apontamento a
                INNER JOIN cd_alocados b ON 
                           b.id = a.id_alocado
                INNER JOIN cd_alocacao c ON 
                           c.id = b.id_alocacao
                LEFT JOIN usuarios d ON 
                          d.id = a.id_cuidador_sub
                WHERE c.id_empresa = '{$this->session->userdata('empresa')}'";
        if (!empty($get['depto'])) {
            $sql .= " AND c.depto = '{$get['depto']}'";
        }
        if (!empty($get['diretoria'])) {
            $sql .= " AND c.diretoria = '{$get['diretoria']}'";
        }
        if (!empty($get['supervisor'])) {
            $sql .= " AND c.supervisor = '{$get['supervisor']}'";
        }
        if (!empty($get['escola'])) {
            $sql .= " AND b.escola = '{$get['escola']}'";
        }
        if (!empty($get['status'])) {
            $sql .= " AND a.status = '{$get['status']}'";
        }
        if (!empty($get['periodo_manha']) or !empty($get['periodo_tarde']) or !empty($get['periodo_noite'])) {
            $periodos = array();
            if (!empty($get['periodo_manha'])) {
                $periodos[] = "b.turno = 'M'";
            }
            if (!empty($get['periodo_tarde'])) {
                $periodos[] = "b.turno = 'T'";
            }
            if (!empty($get['periodo_noite'])) {
                $periodos[] = "b.turno = 'N'";
            }
            $sql .= ' AND (' . implode(' OR ', $periodos) . ')';
        }
        if (!empty($get['mes'])) {
            $sql .= " AND MONTH(c.data) = '{$get['mes']}'";
        }
        if (!empty($get['ano'])) {
            $sql .= " AND YEAR(c.data) = '{$get['ano']}'";
        }
        if ($get['search']) {
            $sql .= " AND (b.cuidador LIKE '%{$get['search']}%' OR 
                           b.escola LIKE '%{$get['search']}%' OR
                           d.nome LIKE '%{$get['search']}%')";
        }
        if (!empty($get['order'])) {
            $orderBy = [];
            foreach ($get['order'] as $order) {
                $orderBy[] = intval($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }
        $data['rows'] = $this->db->query($sql)->result();


        $this->m_pdf->pdf->writeHTML($this->load->view('cd/eventosPdf', $data, true));

        $this->m_pdf->pdf->Output('Relatório de eventos.pdf', 'D');
    }

}
