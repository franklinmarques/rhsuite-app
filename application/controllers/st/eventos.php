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
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {

            $this->db->select('depto, area, setor');
            $this->db->where('id', $this->session->userdata('id'));
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
            $this->db->like('depto', 'servicos terceirizados');
            $filtro = $this->db->get('usuarios')->row();
            $data = $this->get_filtros_usuarios($filtro->depto);
        }

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

        $this->db->select('DISTINCT(contrato) AS nome', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'Todos');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->where('status', '1');
        $usuarios = $this->db->get('usuarios')->result();
        $data['usuarios'] = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $data['usuarios'][$usuario->id] = $usuario->nome;
        }

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

        $this->load->view('st/eventos', $data);
    }

    //==========================================================================
    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $get = $this->input->get();
        $filtro = array(
            'area' => array('' => 'Todas'),
            'setor' => array('' => 'Todos'),
            'cargo' => array('' => 'Todos'),
            'funcao' => array('' => 'Todas'),
            'contrato' => array('' => 'Todos')
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
        $this->db->join('st_alocados b', 'b.id_usuario = a.id');
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
        $this->db->join('st_alocados b', 'b.id_usuario = a.id');
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
        $this->db->join('st_alocados b', 'b.id_usuario = a.id');
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

    //==========================================================================
    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $sql = "SELECT s.nome,
                       s.data,
                       s.status,
                       s.glosa,
                       s.nome_bck,
                       s.apontamento_desc,
                       s.apontamento_extra,
                       s.detalhes,
                       s.observacoes
                FROM (SELECT b.nome,
                             DATE_FORMAT(a.data, '%d/%m/%Y') AS data,
                             a.status,
                             CASE a.status 
                                  WHEN 'FJ' THEN CONCAT(a.qtde_dias, 'd')
                                  WHEN 'FN' THEN CONCAT(a.qtde_dias, 'd')
                                  WHEN 'FR' THEN CONCAT(a.qtde_dias, 'd')
                                  ELSE TIME_FORMAT(a.hora_glosa, '%H:%i') END AS glosa, 
                             c.nome AS nome_bck,
                             TIME_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_desc,
                             TIME_FORMAT(a.apontamento_extra, '%H:%i') AS apontamento_extra,
                             f.nome AS detalhes,
                             a.observacoes
                      FROM st_apontamento a
                      INNER JOIN st_alocados d ON 
                                 d.id = a.id_alocado 
                      INNER JOIN alocacao e ON 
                                 e.id = d.id_alocacao 
                      INNER JOIN usuarios b ON 
                                 b.id = d.id_usuario 
                      LEFT JOIN usuarios c ON 
                                 c.id = a.id_alocado_bck 
                      LEFT JOIN st_detalhes_eventos f ON
                                f.id = a.detalhes
                      WHERE e.id_empresa = {$this->session->userdata('empresa')}";
        if (!empty($busca['depto'])) {
            $sql .= " AND e.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['area'])) {
            $sql .= " AND e.area = '{$busca['area']}'";
        }
        if (!empty($busca['setor'])) {
            $sql .= " AND e.setor = '{$busca['setor']}'";
        }
        if (!empty($busca['cargo'])) {
            $sql .= " AND b.cargo = '{$busca['cargo']}'";
        }
        if (!empty($busca['funcao'])) {
            $sql .= " AND b.funcao = '{$busca['funcao']}'";
        }
        if (!empty($busca['contrato'])) {
            $sql .= " AND b.contrato = '{$busca['contrato']}'";
        }
        if (!empty($busca['mes'])) {
            $sql .= " AND DATE_FORMAT(e.data, '%m') = '{$busca['mes']}'";
        }
        if (!empty($busca['ano'])) {
            $sql .= " AND DATE_FORMAT(e.data, '%Y') = '{$busca['ano']}'";
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.nome', 's.status', 's.glosa', 's.nome_bck', 's.apontamento_desc', 's.apontamento_extra', 's.detalhes', 's.observacoes');
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
            $row[] = $apontamento->nome;
            $row[] = $apontamento->data;
            $row[] = $apontamento->status;
            $row[] = $apontamento->glosa;
            $row[] = $apontamento->nome_bck;
            $row[] = $apontamento->apontamento_desc;
            $row[] = $apontamento->apontamento_extra;
            $row[] = $apontamento->detalhes;
            $row[] = $apontamento->observacoes;

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

        $sql = "SELECT b.nome,
                       DATE_FORMAT(a.data, '%d/%m/%Y') AS data,
                       a.status,
                       (CASE a.status 
                             WHEN 'FJ' THEN CONCAT(a.qtde_dias, 'd')
                             WHEN 'FN' THEN CONCAT(a.qtde_dias, 'd')
                             WHEN 'FR' THEN CONCAT(a.qtde_dias, 'd')
                             ELSE TIME_FORMAT(a.hora_glosa, '%H:%i') END) AS glosa,
                       c.nome AS nome_bck,
                       TIME_FORMAT(a.apontamento_desc, '%H:%i') AS apontamento_desc,
                       TIME_FORMAT(a.apontamento_extra, '%H:%i') AS apontamento_extra,
                       a.detalhes,
                       a.observacoes
                FROM st_apontamento a
                INNER JOIN st_alocados d ON 
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


        $this->m_pdf->pdf->writeHTML($this->load->view('st/eventosPdf', $data, true));

        $this->m_pdf->pdf->Output('Relatório de eventos.pdf', 'D');

    }

}
