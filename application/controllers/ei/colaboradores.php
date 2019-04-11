<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Colaboradores extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
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
            $this->db->like('depto', 'Educação Inclusiva');
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
            $this->db->like('depto', 'Educação Inclusiva');
        }
        $status = $this->db->get('usuarios')->row();
        $data['depto_atual'] = $status->depto;
        $data['area_atual'] = $status->area;
        $data['setor_atual'] = $status->setor;

        $this->db->select('DISTINCT(contrato) AS nome', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'selecione...');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->load->view('ei/colaboradores', $data);
    }

    public function atualizar_filtro()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
//        if ($this->session->userdata('tipo') == 'funcionario') {
//        if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
//            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
//                unset($filtro['area'][''], $filtro['setor']['']);
//            }
//            unset($filtro['depto']['']);
//        }

        $data['area'] = form_dropdown('area', $filtro['area'], $area, 'class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'class="form-control input-sm"');
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'class="form-control input-sm"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'class="form-control input-sm"');

        echo json_encode($data);
    }

    public function atualizar_filtro2()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
        if ($this->session->userdata('tipo') == 'funcionario') {
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
        $this->db->insert_batch('alocacao_usuarios', $data2);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    public function getcolaboradores()
    {
        $this->load->library('pagination');

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = site_url('ei/colaboradores/getcolaboradores');

        $post = $this->input->post();
        $post[$this->security->get_csrf_token_name()] = $this->security->get_csrf_hash();
//        $mes_ano = date('Y-m');
//        $sql0 = "SELECT c.id, 
//                       c.nome, 
//                       c.depto, 
//                       c.area, 
//                       c.setor, 
//                       c.funcao 
//                FROM alocacao_usuarios a
//                INNER JOIN alocacao b ON 
//                           b.id = a.id_alocacao 
//                INNER JOIN usuarios c ON 
//                           c.id = a.id_usuario 
//                WHERE b.id_empresa = {$this->session->userdata('empresa')} AND 
//                      c.status = 1 AND 
//                      DATE_FORMAT(b.data, '%Y-%m') = '{$mes_ano}'";

        $sql = "SELECT a.id, 
                       a.nome, 
                       a.depto, 
                       a.area, 
                       a.setor, 
                       a.funcao 
                FROM usuarios a
                WHERE a.empresa = {$this->session->userdata('empresa')}";
        if ($post['busca']) {
            $sql .= " AND (a.nome LIKE '%{$post['busca']}%' OR a.email LIKE '%{$post['busca']}%')";
        }
        if ($post['pdi']) {
            $sql .= " AND (a.id IN (SELECT d.usuario FROM pdi d WHERE d.status = '{$post['pdi']}'))";
        }
        if ($post['status']) {
            $sql .= " AND a.status = '{$post['status']}'";
        }
        if ($post['depto']) {
            $sql .= " AND a.depto = '{$post['depto']}'";
        }
        if ($post['area']) {
            $sql .= " AND a.area = '{$post['area']}'";
        }
        if ($post['setor']) {
            $sql .= " AND a.setor = '{$post['setor']}'";
        }
        if ($post['cargo']) {
            $sql .= " AND a.cargo = '{$post['cargo']}'";
        }
        if ($post['funcao']) {
            $sql .= " AND a.funcao = '{$post['funcao']}'";
        }
        if ($post['contrato']) {
            $sql .= " AND a.contrato = '{$post['contrato']}'";
        }

        $data['total'] = $this->db->query($sql)->num_rows();

        $config['total_rows'] = $data['total'];
        $config['per_page'] = 20;
        $this->pagination->initialize($config);

        $sql .= " ORDER BY a.id DESC 
                  LIMIT {$this->uri->rsegment(3, 0)}, {$config['per_page']}";

        $data['total_encontrados'] = $this->db->query($sql)->num_rows();
        $data['busca'] = http_build_query($post);
        $data['query'] = $this->db->query($sql)->result();
        $this->load->view('ei/getcolaboradores', $data);
    }

    public function ajax_list3()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $sql = "SELECT s.id, 
                       s.nome,
                       s.estrutura,
                       s.cargo_funcao,
                       s.depto,
                       s.area,
                       s.setor,
                       s.cargo, 
                       s.funcao,
                       s.contrato
                FROM (SELECT c.id, 
                             c.nome,
                             CONCAT_WS('/', c.depto, c.area, c.setor) AS estrutura,
                             CONCAT_WS('/', c.cargo, c.funcao) AS cargo_funcao,
                             c.depto,
                             c.area,
                             c.setor,
                             c.cargo, 
                             c.funcao,
                             c.contrato
                      FROM alocacao_usuarios a
                      INNER JOIN alocacao b ON 
                                 b.id = a.id_alocacao 
                      INNER JOIN usuarios c ON 
                                 c.id = a.id_usuario 
                      WHERE b.id_empresa = {$this->session->userdata('empresa')} GROUP BY c.id) s";
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
            $row[] = $apontamento->estrutura;
            $row[] = $apontamento->cargo_funcao;
            $row[] = '
                      <a class="btn btn-sm btn-primary" href="' . site_url('apontamento_colaboradores/editar_perfil/' . $apontamento->id) . '" title="Edição rápida"><i class="glyphicon glyphicon-pencil"></i> </a>
                      <a class="btn btn-sm btn-success" href="' . site_url('ead/cursos_funcionario/index/' . $apontamento->id) . '" target="_blank" title="Treinamentos"><i class="glyphicon glyphicon-plus"></i> Treinamentos</a>
                      <a class="btn btn-sm btn-warning" href="' . site_url('pdi/gerenciar/' . $apontamento->id) . '" target="_blank" title="PDIs"><i class="glyphicon glyphicon-plus"></i> PDIs</a>
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

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $sql = "SELECT s.id_usuario,
                       s.nome,
                       s.estrutura,
                       s.cargo_funcao,
                       s.depto,
                       s.area,
                       s.setor,
                       s.cargo, 
                       s.funcao,
                       s.contrato,
                       s.id
                FROM (SELECT a.id, 
                             c.id AS id_usuario,
                             c.nome,
                             CONCAT_WS('/', c.depto, c.area, c.setor) AS estrutura,
                             CONCAT_WS('/', c.cargo, c.funcao) AS cargo_funcao,
                             c.depto,
                             c.area,
                             c.setor,
                             c.cargo, 
                             c.funcao,
                             c.contrato
                      FROM alocacao_usuarios a
                      INNER JOIN alocacao b ON 
                                 b.id = a.id_alocacao 
                      INNER JOIN usuarios c ON 
                                 c.id = a.id_usuario 
                      WHERE b.id_empresa = {$this->session->userdata('empresa')} AND 
                            DATE_FORMAT(b.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
        if ($busca['depto']) {
            $sql .= " AND c.depto = '{$busca['depto']}'";
        }
        if ($busca['area']) {
            $sql .= " AND c.area = '{$busca['area']}'";
        }
        if ($busca['setor']) {
            $sql .= " AND c.setor = '{$busca['setor']}'";
        }
        if ($busca['cargo']) {
            $sql .= " AND c.cargo = '{$busca['cargo']}'";
        }
        if ($busca['funcao']) {
            $sql .= " AND c.funcao = '{$busca['funcao']}'";
        }
        $sql .= ' GROUP BY a.id) s';
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
            $row[] = $apontamento->depto;
            $row[] = $apontamento->area;
            $row[] = $apontamento->setor;
            $row[] = $apontamento->funcao;
            $row[] = '
                      <a class="btn btn-xs btn-success" href="' . site_url('ead/cursos_funcionario/index/' . $apontamento->id_usuario) . '" target="_blank" title="Treinamentos"><i class="glyphicon glyphicon-plus"></i> Treinamentos</a>
                      <a class="btn btn-xs btn-warning" href="' . site_url('pdi/gerenciar/' . $apontamento->id_usuario) . '" target="_blank" title="PDIs"><i class="glyphicon glyphicon-plus"></i> PDIs</a>
                      <button type="button" class="btn btn-xs btn-danger" onclick="delete_colaborador(' . $apontamento->id . ')" title="Excluir alocação de colaborador"><i class="glyphicon glyphicon-trash"></i> </button>
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

    public function ajax_colaboradores()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('alocacao c', 'c.id = a.id_alocacao');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where("DATE_FORMAT(c.data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
        if ($this->session->userdata('tipo') == 'funcionario') {
            $this->db->where('c.depto', $busca['depto']);
//            if ($this->session->userdata('nivel') == 11) {
//                $this->db->where('c.area', $busca['area']);
//                $this->db->where('c.setor', $busca['setor']);
//            }
        }
        $this->db->order_by('b.nome', 'asc');
        $rows = $this->db->get('alocacao_usuarios a')->result();

        $options = array('' => 'selecione...');
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $data['id_bck'] = form_dropdown('id_bck', $options, '', 'class="form-control"');
        $data['id_usuario_sub1'] = form_dropdown('id_usuario_sub1', $options, '', 'class="form-control"');
        $data['id_usuario_sub2'] = form_dropdown('id_usuario_sub2', $options, '', 'class="form-control"');
        $data['id_alocado_bck'] = form_dropdown('id_alocado_bck', $options, '', 'class="form-control"');

        echo json_encode($data);
    }

    public function ajax_setores()
    {
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');

        $this->db->select('DISTINCT(setor) AS nome', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('area', $area);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $rows = $this->db->get('usuarios')->result();

        $options = array('' => 'selecione...');
        foreach ($rows as $row) {
            $options[$row->nome] = $row->nome;
        }

        echo form_dropdown('setor', $options, $setor, 'id="setor" class="combobox form-control"');
    }

    public function editar_perfil()
    {
        $this->db->where('id', $this->uri->rsegment(3, 0));
//        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        $funcionario = $this->db->get('usuarios')->row();

        if (count($funcionario) == 0) {
            redirect(site_url('ei/colaboradores'));
        }

//        if ($funcionario->empresa != $this->session->userdata('id')) {
//            redirect(site_url('apontamento_colaboradores'));
//        }
        if ($funcionario->hash_acesso) {
            $this->load->library('encrypt');
            $funcionario->hash_acesso = $this->encrypt->decode($funcionario->hash_acesso, base64_encode($funcionario->id));
        } else {
            $funcionario->hash_acesso = 'null';
        }

        $dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_admissao)));
        $funcionario->data_admissao = $dataFormatada;
        $data['row'] = $funcionario;
        $data['status'] = array(
            '1' => 'Ativo',
            '2' => 'Inativo',
            '3' => 'Em experiência',
            '4' => 'Em desligamento',
            '5' => 'Desligado',
            '6' => 'Afastado (maternidade)',
            '7' => 'Afastado (aposentadoria)',
            '8' => 'Afastado (doença)',
            '9' => 'Afastado (acidente)'
        );

        $this->db->select('DISTINCT(area) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        $areas = $this->db->get('usuarios')->result();
        $data['area'] = array('' => 'digite ou selecione...');
        foreach ($areas as $area) {
            $data['area'][$area->nome] = $area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('area', $funcionario->area);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $setores = $this->db->get('usuarios')->result();
        $data['setor'] = array('' => 'digite ou selecione...');
        foreach ($setores as $setor) {
            $data['setor'][$setor->nome] = $setor->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'digite ou selecione...');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->load->view('ei/coladorador', $data);
    }

    public function ajax_save()
    {
        $post = $this->input->post();
        $mes_ano = strtotime($post['ano'] . '-' . $post['mes']);
        $post['data'] = date('Y-m-d', $mes_ano);
        unset($post['id'], $post['mes'], $post['ano']);

        $this->db->select('depto, area, setor, cargo, funcao, contrato');
        $this->db->where('id', $post['id_usuario']);
        $row = $this->db->get('usuarios')->row_array();
        $data = array_merge($post, $row);
        $sql = "SELECT s.* 
                FROM (SELECT a.* 
                      FROM alocacao_postos a 
                      WHERE a.id_usuario = '{$data['id_usuario']}' 
                      ORDER BY a.data DESC 
                      LIMIT 1) s 
                WHERE s.depto = '{$data['depto']}' AND 
                      s.area = '{$data['area']}' AND 
                      s.setor = '{$data['setor']}' AND 
                      s.cargo = '{$data['cargo']}' AND 
                      s.funcao = '{$data['funcao']}' AND 
                      s.contrato = '{$data['contrato']}' AND 
                      s.total_dias_mensais = '{$data['total_dias_mensais']}' AND 
                      s.total_horas_diarias = '{$data['total_horas_diarias']}' AND 
                      s.valor_posto = '{$data['valor_posto']}' AND 
                      s.valor_dia = '{$data['valor_dia']}' AND 
                      s.valor_hora = '{$data['valor_hora']}'";
        $count = $this->db->query($sql)->num_rows();

        $this->db->select('id');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where("DATE_FORMAT(data, '%Y-%m') =", date('Y-m', $mes_ano));
        $row2 = $this->db->get('alocacao')->row();
        $data2 = array(
            'id_alocacao' => $row2->id,
            'id_usuario' => $post['id_usuario'],
            'tipo_horario' => 'I',
            'nivel' => 'P'
        );

        $this->db->trans_start();
        if ($count == 0) {
            $this->db->query($this->db->insert_string('alocacao_postos', $data));
        }
        $this->db->query($this->db->insert_string('alocacao_usuarios', $data2));
        $this->db->trans_complete();

        $status = $this->db->trans_status();
        echo json_encode(array("status" => $status !== false));
    }

    public function save_perfil()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $this->db->where('id', $this->uri->rsegment(3, 0));
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        $funcionario = $this->db->get('usuarios')->row();

        if ($funcionario->empresa != $this->session->userdata('empresa')) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
        }

        $data['area'] = $this->input->post('area');
        $data['setor'] = $this->input->post('setor');
        $data['cnpj'] = $this->input->post('cnpj');
        $data['contrato'] = $this->input->post('contrato');
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['status'] = $this->input->post('status');
        $data['nome_cartao'] = $this->input->post('nome_cartao');
        $data['valor_vt'] = $this->input->post('valor_vt');
        $data['nome_banco'] = $this->input->post('nome_banco');
        $data['agencia_bancaria'] = $this->input->post('agencia_bancaria');
        $data['conta_bancaria'] = $this->input->post('conta_bancaria');

        if ($this->db->where('id', $funcionario->id)->update('usuarios', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Funcionário editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('ei/colaboradores')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $this->db->select('id, nome, area, setor, contrato, status');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('id', $id);
        $data = $this->db->get('usuarios')->row();

        echo json_encode($data);
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('alocacao_usuarios', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function pdf()
    {
        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();


        $get = $this->input->get();

        $this->db->select("a.contrato, a.nome, DATE_FORMAT(a.data_admissao, '%d/%m/%Y') AS data_admissao", false);
        $this->db->select("CONCAT_WS('/', a.depto, a.area, a.setor) AS estrutura", false);
        $this->db->select("CONCAT_WS('/', a.cargo, a.funcao) AS cargo_funcao", false);
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        if (isset($get['busca'])) {
            $this->db->like('a.nome', $get['busca']);
            $this->db->or_like('a.email', $get['busca']);
        }
        if (isset($get['pdi'])) {
            $this->db->where_in('a.id', "(SELECT d.usuario FROM pdi d WHERE d.status = {$get['pdi']})");
        }
        if (isset($get['status'])) {
            $this->db->where('a.status', $get['status']);
        }
        if (isset($get['depto'])) {
            $this->db->where('a.depto', $get['depto']);
        }
        if (isset($get['area'])) {
            $this->db->where('a.area', $get['area']);
        }
        if (isset($get['setor'])) {
            $this->db->where('a.setor', $get['setor']);
        }
        if (isset($get['cargo'])) {
            $this->db->where('a.cargo', $get['cargo']);
        }
        if (isset($get['funcao'])) {
            $this->db->where('a.funcao', $get['funcao']);
        }
        if (isset($get['contrato'])) {
            $this->db->where('a.contrato', $get['contrato']);
        }
        $data['colaboradores'] = $this->db->get('usuarios a')->result();

        $this->load->library('m_pdf');

        $stylesheet = '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->load->view('funcionariosPdf', $data, true));

        $this->m_pdf->pdf->Output('Colaboradores.pdf', 'D');
    }

}
