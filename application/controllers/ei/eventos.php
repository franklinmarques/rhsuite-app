<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends MY_Controller
{

    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $data = array();


        $this->db->select('depto AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->group_by('depto');
        $this->db->order_by('depto');
        $deptos = $this->db->get('ei_alocacao')->result();
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
        $diretorias = $this->db->get('ei_alocacao')->result();
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
        $supervisores = $this->db->get('ei_alocacao a')->result();
        if (count($supervisores) === 1) {
            $data['supervisores'] = array();
        } else {
            $data['supervisores'] = array('' => 'Todos');
        }
        foreach ($supervisores as $supervisor) {
            $data['supervisores'][$supervisor->nome] = $supervisor->nome;
        }

        $this->db->select('a.escola AS nome', false);
        $this->db->join('ei_alocacao b', 'a.id_alocacao = b.id');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->group_by('a.escola');
        $this->db->order_by('a.escola');
        $escolas = $this->db->get('ei_alocados a')->result();
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
                                  WHEN 'ID' THEN 'Intercorrência de Diretoria'
                                  WHEN 'IC' THEN 'Intercorrência de Cuidadores'
                                  WHEN 'IA' THEN 'Intercorrência de Alunos'
                                  WHEN 'AT' THEN 'Acidente de trabalho'
                                  END AS nome 
                      FROM ei_apontamento a 
                      INNER JOIN ei_alocados b 
                                 ON a.id_alocado = b.id
                      INNER JOIN ei_alocacao c
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
//        $this->db->join('ei_alocacao b', 'a.id_alocacao = b.id');
//        $this->db->where('b.id_empresa', $empresa);
//        if ($data['depto_atual']) {
//            $this->db->where('b.depto', $data['depto_atual']);
//        }
//        if ($data['supervisor_atual']) {
//            $this->db->where('b.supervisor', $data['supervisor_atual']);
//        }
//        $this->db->group_by('a.escola');
//        $this->db->order_by('a.escola', 'asc');
//        $escolas = $this->db->get('ei_alocados a')->result();
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

        $this->load->view('ei/eventos', $data);
    }

    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $get = $this->input->get();
        $filtro = array(
            'diretoria' => array('' => 'Todas'),
            'supervisor' => array('' => 'Todos'),
            'escola' => array('' => 'Todas'),
            'status' => array('' => 'Todos'),
            'turno' => array()
        );


        $this->db->select('area AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        if ($get['depto']) {
            $this->db->where('depto', $get['depto']);
        }
        $this->db->group_by('area');
        $areas = $this->db->get('alocacao')->result();
        foreach ($areas as $area) {
            $filtro['area'][$area->nome] = $area->nome;
        }


        $this->db->select('setor AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        if ($get['depto']) {
            $this->db->where('depto', $get['depto']);
        }
        if ($get['area']) {
            $this->db->where('area', $get['area']);
        }
        $this->db->group_by('setor');
        $setores = $this->db->get('alocacao')->result();
        foreach ($setores as $setor) {
            $filtro['setor'][$setor->nome] = $setor->nome;
        }


        $this->db->select('a.cargo AS nome', false);
        $this->db->join('alocacao_usuarios b', 'b.id_usuario = a.id');
        $this->db->join('alocacao c', 'c.id = b.id_alocacao');
        $this->db->where('c.id_empresa', $empresa);
        $this->db->where('CHAR_LENGTH(a.cargo) >', 0);
        if ($get['depto']) {
            $this->db->where('c.depto', $get['depto']);
        }
        if ($get['area']) {
            $this->db->where('c.area', $get['area']);
        }
        if ($get['setor']) {
            $this->db->where('c.setor', $get['setor']);
        }
        $this->db->group_by('a.cargo');
        $cargos = $this->db->get('usuarios a')->result();
        foreach ($cargos as $cargo) {
            $filtro['cargo'][$cargo->nome] = $cargo->nome;
        }


        $this->db->select('a.funcao AS nome', false);
        $this->db->join('alocacao_usuarios b', 'b.id_usuario = a.id');
        $this->db->join('alocacao c', 'c.id = b.id_alocacao');
        $this->db->where('c.id_empresa', $empresa);
        $this->db->where('CHAR_LENGTH(a.funcao) >', 0);
        if ($get['depto']) {
            $this->db->where('c.depto', $get['depto']);
        }
        if ($get['area']) {
            $this->db->where('c.area', $get['area']);
        }
        if ($get['setor']) {
            $this->db->where('c.setor', $get['setor']);
        }
        if ($get['cargo']) {
            $this->db->where('a.cargo', $get['cargo']);
        }
        $this->db->group_by('a.funcao');
        $funcoes = $this->db->get('usuarios a')->result();
        foreach ($funcoes as $funcao) {
            $filtro['funcao'][$funcao->nome] = $funcao->nome;
        }


        $this->db->select('a.contrato AS nome', false);
        $this->db->join('alocacao_usuarios b', 'b.id_usuario = a.id');
        $this->db->join('alocacao c', 'c.id = b.id_alocacao');
        $this->db->where('c.id_empresa', $empresa);
        $this->db->where('CHAR_LENGTH(a.contrato) >', 0);
        if ($get['depto']) {
            $this->db->where('c.depto', $get['depto']);
        }
        if ($get['area']) {
            $this->db->where('c.area', $get['area']);
        }
        if ($get['setor']) {
            $this->db->where('c.setor', $get['setor']);
        }
        if ($get['cargo']) {
            $this->db->where('a.cargo', $get['cargo']);
        }
        if ($get['funcao']) {
            $this->db->where('a.funcao', $get['funcao']);
        }
        $this->db->group_by('a.contrato');
        $contratos = $this->db->get('usuarios a')->result();
        foreach ($contratos as $contrato) {
            $filtro['contrato'][$contrato->nome] = $contrato->nome;
        }


        $data['area'] = form_dropdown('area', $filtro['area'], $get['area'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $get['setor'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $get['cargo'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $get['funcao'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['contrato'] = form_dropdown('contrato', $filtro['contrato'], $get['contrato'], 'class="form-control input-sm filtro"');


        echo json_encode($data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $sql = "SELECT s.cuidador,
                       s.escola,
                       s.data,
                       s.status,
                       s.nome_bck,
                       s.desconto
                FROM (SELECT db.cuidador,
                             db.escola,
                             DATE_FORMAT(a.data, '%d/%m/%Y') AS data,
                             a.status,
                             c.nome AS nome_bck,
                             TIME_FORMAT(a.desconto, '%H:%i') AS desconto
                      FROM ei_apontamento a
                      INNER JOIN ei_alocados d ON 
                                 d.id = a.id_alocado 
                      INNER JOIN ei_alocacao e
                                 ON e.id = d.id_alocacao
                      LEFT JOIN usuarios c ON 
                                c.id = a.id_alocado_sub1 
                      WHERE e.id_empresa = {$this->session->userdata('empresa')}";
        if (!empty($busca['depto'])) {
            $sql .= " AND ec.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['diretoria'])) {
            $sql .= " AND ec.diretoria = '{$busca['diretoria']}'";
        }
        if (!empty($busca['supervisor'])) {
            $sql .= " AND ec.supervisor = '{$busca['supervisor']}'";
        }
        if (!empty($busca['escola'])) {
            $sql .= " AND db.escola = '{$busca['escola']}'";
        }
        if (!empty($busca['status'])) {
            $sql .= " AND a.status = '{$busca['status']}'";
        }
        if (!empty($busca['mes'])) {
            $sql .= " AND DATE_FORMAT(ec.data, '%m') = '{$busca['mes']}'";
        }
        if (!empty($busca['ano'])) {
            $sql .= " AND DATE_FORMAT(ec.data, '%Y') = '{$busca['ano']}'";
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.cuidador', 's.escola', 's.data', 's.status', 's.nome_bck', 's.desconto');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 0) {
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
            $row[] = $apontamento->cuidador;
            $row[] = $apontamento->escola;
            $row[] = $apontamento->data;
            $row[] = $apontamento->status;
            $row[] = $apontamento->nome_bck;
            $row[] = $apontamento->desconto;

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        echo json_encode($output);
    }

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

        $sql = "SELECT b.nome,
                       DATE_FORMAT(a.data, '%d/%m/%Y') AS data,
                       a.status,
                       (CASE a.status 
                             WHEN 'FJ' THEN CONCAT(a.qtde_dias, 'd')
                             WHEN 'FN' THEN CONCAT(a.qtde_dias, 'd')
                             WHEN 'FR' THEN CONCAT(a.qtde_dias, 'd')
                             ELSE TIME_FORMAT(a.hora_glosa, '%H:%i') END) AS glosa,
                       c.nome AS nome_bck,
                       TIME_FORMAT(a.desconto, '%H:%i') AS desconto,
                       a.detalhes
                FROM alocacao_apontamento a
                INNER JOIN alocacao_usuarios d ON 
                           d.id = a.id_alocado 
                INNER JOIN alocacao e ON 
                           e.id = d.id_alocacao 
                INNER JOIN usuarios b ON 
                           b.id = d.id_usuario 
                LEFT JOIN usuarios c ON 
                          c.id = a.id_alocado_bck 
                WHERE e.id_empresa = {$this->session->userdata('empresa')}";
        if (!empty($get['depto'])) {
            $sql .= " AND e.depto = '{$get['depto']}'";
        }
        if (!empty($get['area'])) {
            $sql .= " AND e.area = '{$get['area']}'";
        }
        if (!empty($get['setor'])) {
            $sql .= " AND e.setor = '{$get['setor']}'";
        }
        if (!empty($get['cargo'])) {
            $sql .= " AND b.cargo = '{$get['cargo']}'";
        }
        if (!empty($get['funcao'])) {
            $sql .= " AND b.funcao = '{$get['funcao']}'";
        }
        if (!empty($get['contrato'])) {
            $sql .= " AND b.contrato = '{$get['contrato']}'";
        }
        if (!empty($get['mes'])) {
            $sql .= " AND DATE_FORMAT(e.data, '%m') = '{$get['mes']}'";
        }
        if (!empty($get['ano'])) {
            $sql .= " AND DATE_FORMAT(e.data, '%Y') = '{$get['ano']}'";
        }
        if (!empty($get['search'])) {
            $sql .= " AND (b.nome LIKE '%{$get['search']}%' OR c.nome LIKE '%{$get['search']}%')";
        }
        if (isset($get['order'])) {
            $orderBy = array();
            foreach ($get['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        $data['rows'] = $this->db->query($sql)->result();


        $this->m_pdf->pdf->writeHTML($this->load->view('ei/eventosPdf', $data, true));

        $this->m_pdf->pdf->Output('Relatório de eventos.pdf', 'D');
    }

}
