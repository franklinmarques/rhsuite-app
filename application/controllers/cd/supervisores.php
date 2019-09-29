<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supervisores extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->session->userdata('nivel'), array(0, 7, 8, 9, 10))) {
            redirect(site_url('home'));
        }
    }

    //==========================================================================
    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');

        $data = array();

        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('empresa', $empresa);
        $this->db->where('depto', 'cuidadores');
        $depto_selecionado = $this->db->get('usuarios')->row();
        $data['depto_selecionado'] = $depto_selecionado->nome ?? '';


        $this->db->select('DISTINCT(a.depto) AS nome', false);
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id');
        $this->db->where('a.id_empresa', $empresa);
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $data['busca_depto'] = array();
        } else {
            $data['busca_depto'] = array('' => 'Todos');
        }
        $this->db->order_by('a.depto', 'asc');
        $busca_deptos = $this->db->get('cd_diretorias a')->result();
        foreach ($busca_deptos as $busca_depto) {
            $data['busca_depto'][$busca_depto->nome] = $busca_depto->nome;
        }

        $this->db->select('a.id, a.nome', false);
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id');
        $this->db->where('a.id_empresa', $empresa);
        if ($data['depto_selecionado']) {
            $this->db->where('a.depto', $data['depto_selecionado']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $data['busca_diretoria'] = array();
        } else {
            $data['busca_diretoria'] = array('' => 'Todas');
        }
        $this->db->order_by('a.depto', 'asc');
        $busca_diretorias = $this->db->get('cd_diretorias a')->result();
        foreach ($busca_diretorias as $busca_diretoria) {
            $data['busca_diretoria'][$busca_diretoria->id] = $busca_diretoria->nome;
        }

        $this->db->select('c.id_supervisor AS id, d.nome', false);
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('cd_supervisores c', 'c.id_escola = a.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->where('b.id_empresa', $empresa);
        if ($data['depto_selecionado']) {
            $this->db->where('b.depto', $data['depto_selecionado']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $data['busca_supervisor'] = array();
        } else {
            $data['busca_supervisor'] = array('' => 'Todos');
        }
        $this->db->order_by('d.nome', 'asc');
        $this->db->group_by('c.id_supervisor');
        $busca_supervisores = $this->db->get('cd_escolas a')->result();
        foreach ($busca_supervisores as $busca_supervisor) {
            $data['busca_supervisor'][$busca_supervisor->id] = $busca_supervisor->nome;
        }


        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('empresa', $empresa);
        $this->db->where('depto', $data['depto_selecionado']);
        $this->db->order_by('depto', 'asc');
        $deptos = $this->db->get('usuarios')->result();
        if (count($deptos) == 0) {
            $data['depto'] = array('' => 'selecione...');
        }
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->where('nivel_acesso', '10');
        if ($data['depto_selecionado']) {
            $this->db->where('depto', $data['depto_selecionado']);
        }
        $this->db->where('area', 'supervisao');
        $this->db->order_by('nome', 'asc');
        $supervisores = $this->db->get('usuarios')->result();
        $data['id_supervisor'] = array('' => 'selecione...');
        foreach ($supervisores as $supervisor) {
            $data['id_supervisor'][$supervisor->id] = $supervisor->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->where('a.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $diretorias = $this->db->get('cd_diretorias a')->result();
        $data['id_diretoria'] = array('' => 'selecione...');
        foreach ($diretorias as $diretoria) {
            $data['id_diretoria'][$diretoria->id] = $diretoria->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $escolas = $this->db->get('cd_escolas a')->result();
        $data['escolas_manha'] = array();
        $data['escolas_tarde'] = array();
        $data['escolas_noite'] = array();
        foreach ($escolas as $escola) {
            $data['escolas_manha'][$escola->id] = $escola->nome;
            $data['escolas_tarde'][$escola->id] = $escola->nome;
            $data['escolas_noite'][$escola->id] = $escola->nome;
        }

        $this->load->view('cd/supervisores', $data);
    }

    //==========================================================================
    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $busca = $this->input->post('busca');
        $filtro = array();

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id');
        $this->db->where('a.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('a.depto', $busca['depto']);
        }
        $this->db->order_by('a.nome', 'asc');
        $diretorias = $this->db->get('cd_diretorias a')->result();
        $filtro['diretoria'] = array('' => 'Todas');
        foreach ($diretorias as $diretoria) {
            $filtro['diretoria'][$diretoria->id] = $diretoria->nome;
        }

        $this->db->select('d.id_supervisor AS id, b.nome', false);
        $this->db->join('cd_supervisores d', 'd.id_escola = a.id');
        $this->db->join('usuarios b', 'b.id = d.id_supervisor');
        $this->db->join('cd_diretorias c', 'c.id = a.id_diretoria');
        $this->db->where('c.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('c.depto', $busca['depto']);
        }
        if ($busca['diretoria']) {
            $this->db->where('c.id', $busca['diretoria']);
        }
        $this->db->order_by('b.nome', 'asc');
        $this->db->group_by('d.id_supervisor');
        $supervisores = $this->db->get('cd_escolas a')->result();
        $filtro['supervisor'] = array('' => 'Todos');
        foreach ($supervisores as $supervisor) {
            $filtro['supervisor'][$supervisor->id] = $supervisor->nome;
        }

        $data['diretoria'] = form_dropdown('busca[diretoria]', $filtro['diretoria'], $busca['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['supervisor'] = form_dropdown('busca[supervisor]', $filtro['supervisor'], $busca['supervisor'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    //==========================================================================
    public function atualizar_supervisores()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');
        $depto = $this->input->post('depto');


        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->where('tipo', 'funcionario');
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('id', $id_usuario);
        }
        $this->db->where_in('nivel_acesso', [9, 10]);
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('usuarios')->result();
        $supervisores = array('' => 'selecione...');
        foreach ($rows as $row) {
            $supervisores[$row->id] = $row->nome;
        }


        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('depto', 'asc');
        $rows2 = $this->db->get('cd_diretorias')->result();
        $deptos = array();
        if (empty($depto) and count($rows2) == 0) {
            $deptos = array('' => 'selecione...');
        }
        foreach ($rows2 as $row2) {
            $deptos[$row2->nome] = $row2->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        if ($depto) {
            $this->db->where('a.depto', $depto);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
        }
        $this->db->group_by('a.id', 'asc');
        $this->db->order_by('a.nome', 'asc');
        $rows3 = $this->db->get('cd_diretorias a')->result();
        $diretorias = array('' => 'selecione...');
        foreach ($rows3 as $row3) {
            $diretorias[$row3->id] = $row3->nome;
        }


        $data['id_supervisor'] = form_dropdown('id_supervisor', $supervisores, '', 'id="id_supervisor" class="form-control input-sm"');
        $data['depto'] = form_dropdown('', $deptos, $depto, 'id="depto" class="form-control input-sm"');
        $data['id_diretoria'] = form_dropdown('id_diretoria', $diretorias, '', 'id="id_diretoria" class="form-control input-sm"');

        echo json_encode($data);
    }

    //==========================================================================
    public function atualizar_unidades()
    {
        $id_diretoria = $this->input->post('id_diretoria');
        $id_supervisor = $this->input->post('id_supervisor');

        $this->db->join('cd_escolas b', 'b.id = a.id_escola');
        $this->db->where('a.id_supervisor', $id_supervisor);
        $this->db->where('b.id_diretoria', $id_diretoria);
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('a.id_supervisor', $this->session->userdata('id'));
        }
        $row = $this->db->get('cd_supervisores a')->num_rows();
        if ($row > 0) {
            exit(json_encode(array('erro' => 'O vínculo entre Diretoria de Ensino e Supervisor(a) selecionados já existe! Para vincular novas escolas, utilize o botão vincular escolas.')));
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.turno = 'M'", 'left');
        if ($id_diretoria) {
            $this->db->where('a.id_diretoria', $id_diretoria);
        }
        $this->db->where('b.id', null);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_manha = $this->db->get('cd_escolas a')->result();
        $unidades_manha = array();
        foreach ($rows_manha as $row_manha) {
            $unidades_manha[$row_manha->id] = $row_manha->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.turno = 'T'", 'left');
        if ($id_diretoria) {
            $this->db->where('a.id_diretoria', $id_diretoria);
        }
        $this->db->where('b.id', null);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_tarde = $this->db->get('cd_escolas a')->result();
        $unidades_tarde = array();
        foreach ($rows_tarde as $row_tarde) {
            $unidades_tarde[$row_tarde->id] = $row_tarde->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.turno = 'N'", 'left');
        if ($id_diretoria) {
            $this->db->where('a.id_diretoria', $id_diretoria);
        }
        $this->db->where('b.id', null);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_noite = $this->db->get('cd_escolas a')->result();
        $unidades_noite = array();
        foreach ($rows_noite as $row_noite) {
            $unidades_noite[$row_noite->id] = $row_noite->nome;
        }


        $data['id_manha'] = form_multiselect('id[M]', $unidades_manha, array(), 'id="id_manha" class="demo2" size="8"');
        $data['id_tarde'] = form_multiselect('id[T]', $unidades_tarde, array(), 'id="id_tarde" class="demo2" size="8"');
        $data['id_noite'] = form_multiselect('id[N]', $unidades_noite, array(), 'id="id_noite" class="demo2" size="8"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.supervisor,
                       s.turno,
                       s.escola,
                       s.descricao_turno,
                       s.id_supervisor
                FROM (SELECT a.id, 
                             a.nome,
                             c.id_supervisor,
                             d.nome AS supervisor,
                             b.nome AS escola,
                             CASE c.turno WHEN 'M' THEN 1
                                          WHEN 'T' THEN 2
                                          WHEN 'N' THEN 3
                                          ELSE 4 END AS turno,
                             CASE c.turno WHEN 'M' THEN 'Manhã'
                                          WHEN 'T' THEN 'Tarde'
                                          WHEN 'N' THEN 'Noite'
                                          ELSE 'Integral' END AS descricao_turno
                      FROM cd_diretorias a
                      INNER JOIN cd_escolas b ON 
                                 b.id_diretoria = a.id
                      INNER JOIN cd_supervisores c ON 
                                 c.id_escola = b.id
                      INNER JOIN usuarios d ON 
                                 d.id = c.id_supervisor
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}";
        if (!empty($busca['depto'])) {
            $sql .= " AND a.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['diretoria'])) {
            $sql .= " AND a.id = '{$busca['diretoria']}'";
        }
        if (!empty($busca['supervisor'])) {
            $sql .= " AND c.id_supervisor = '{$busca['supervisor']}'";
        }
        $sql .= ' GROUP BY a.id, c.id_supervisor, c.turno, b.id 
                  ORDER BY a.id, c.id_supervisor, c.turno) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.supervisor', 's.turno', 's.escola');
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
        foreach ($list as $cd) {
            $row = array();
            $row[] = $cd->nome;
            $row[] = $cd->supervisor;
            $row[] = $cd->descricao_turno;
            $row[] = $cd->escola;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_supervisores(' . $cd->id . ', ' . $cd->id_supervisor . ')" title="Vincular escolas"><i class="glyphicon glyphicon-plus"></i> Vincular escolas</button>&emsp;&emsp;
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_supervisores(' . $cd->id . ', ' . $cd->id_supervisor . ')" title="Desvincular escolas"><i class="glyphicon glyphicon-minus"></i> </button>
                    ';
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
    public function ajax_edit()

    {
        $empresa = $this->session->userdata('empresa');
        $id_diretoria = $this->input->post('diretoria');
        $id_supervisor = $this->input->post('supervisor');

        $this->db->select('d.depto, c.id_diretoria, d.nome AS diretoria', false);
        $this->db->select('b.id_supervisor, a.nome AS supervisor, a.depto AS id_depto', false);
        $this->db->join('cd_supervisores b', 'b.id_supervisor = a.id');
        $this->db->join('cd_escolas c', 'c.id = b.id_escola');
        $this->db->join('cd_diretorias d', 'd.id = c.id_diretoria');
        $this->db->where('a.empresa', $empresa);
        $this->db->where('a.id', $id_supervisor);
        $this->db->where('d.id', $id_diretoria);
        $row = $this->db->get('usuarios a')->row();

        $filtro = array(
            'depto' => array($row->id_depto => $row->depto),
            'diretoria' => array($row->id_diretoria => $row->diretoria),
            'supervisor' => array($row->id_supervisor => $row->supervisor)
        );

        $data = array();

        $data['depto'] = form_dropdown('', $filtro['depto'], $row->id_depto, 'id="depto" class="form-control input-sm"');
        $data['id_supervisor'] = form_dropdown('id_supervisor', $filtro['supervisor'], $id_supervisor, 'id="id_supervisor" class="form-control input-sm"');
        $data['id_diretoria'] = form_dropdown('id_diretoria', $filtro['diretoria'], $id_diretoria, 'id="id_diretoria" class="form-control input-sm"');


        $this->db->select('a.id, a.nome, b.id_supervisor');
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.turno = 'M'", 'left');
        $this->db->where('a.id_diretoria', $id_diretoria);
        $this->db->where("(b.id IS NULL OR b.id_supervisor = '{$row->id_supervisor}')");
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_manha = $this->db->get('cd_escolas a')->result();
        $unidades_manha = array();
        $selecionados_manha = array();
        foreach ($rows_manha as $row_manha) {
            $unidades_manha[$row_manha->id] = $row_manha->nome;
            if ($row_manha->id_supervisor) {
                $selecionados_manha[] = $row_manha->id;
            }
        }


        $this->db->select('a.id, a.nome, b.id_supervisor');
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.turno = 'T'", 'left');
        $this->db->where('a.id_diretoria', $id_diretoria);
        $this->db->where("(b.id IS NULL OR b.id_supervisor = '{$row->id_supervisor}')");
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_tarde = $this->db->get('cd_escolas a')->result();
        $unidades_tarde = array();
        $selecionados_tarde = array();
        foreach ($rows_tarde as $row_tarde) {
            $unidades_tarde[$row_tarde->id] = $row_tarde->nome;
            if ($row_tarde->id_supervisor) {
                $selecionados_tarde[] = $row_tarde->id;
            }
        }


        $this->db->select('a.id, a.nome, b.id_supervisor');
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.turno = 'N'", 'left');
        $this->db->where('a.id_diretoria', $id_diretoria);
        $this->db->where("(b.id IS NULL OR b.id_supervisor = '{$row->id_supervisor}')");
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_noite = $this->db->get('cd_escolas a')->result();
        $unidades_noite = array();
        $selecionados_noite = array();
        foreach ($rows_noite as $row_noite) {
            $unidades_noite[$row_noite->id] = $row_noite->nome;
            if ($row_noite->id_supervisor) {
                $selecionados_noite[] = $row_noite->id;
            }
        }


        $data['id_manha'] = form_multiselect('id[M]', $unidades_manha, $selecionados_manha, 'id="id_manha" class="demo2" size="8"');
        $data['id_tarde'] = form_multiselect('id[T]', $unidades_tarde, $selecionados_tarde, 'id="id_tarde" class="demo2" size="8"');
        $data['id_noite'] = form_multiselect('id[N]', $unidades_noite, $selecionados_noite, 'id="id_noite" class="demo2" size="8"');


        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_save()
    {
        $id_diretoria = $this->input->post('id_diretoria');
        $id_supervisor = $this->input->post('id_supervisor');

        $id_escolas_manha = $this->input->post('id_manha');
        $id_escolas_tarde = $this->input->post('id_tarde');
        $id_escolas_noite = $this->input->post('id_noite');
        $str_escolas_manha = "'" . implode("','", is_array($id_escolas_manha) ? $id_escolas_manha : array()) . "'";
        $str_escolas_tarde = "'" . implode("','", is_array($id_escolas_tarde) ? $id_escolas_tarde : array()) . "'";
        $str_escolas_noite = "'" . implode("','", is_array($id_escolas_noite) ? $id_escolas_noite : array()) . "'";


        $delete = "DELETE FROM cd_supervisores
                   WHERE id_supervisor = '{$id_supervisor}' 
                         AND id_escola IN (SELECT id FROM cd_escolas WHERE id_diretoria = '{$id_diretoria}')
                         AND ((turno = 'M' AND id_escola NOT IN ({$str_escolas_manha}))
                           OR (turno = 'T' AND id_escola NOT IN ({$str_escolas_tarde}))
                           OR (turno = 'N' AND id_escola NOT IN ({$str_escolas_noite})))";
        $this->db->query($delete);
        $this->db->trans_start();


        $this->db->select("'{$id_supervisor}' AS id_supervisor, a.id AS id_escola, 'M' AS turno", false);
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.id_supervisor = '{$id_supervisor}' AND b.turno = 'M'", 'left');
        $this->db->where_in('a.id', $id_escolas_manha);
        $this->db->where('b.id', null);
        $data_manha = $this->db->get('cd_escolas a')->result_array();
        if ($data_manha) {
            $this->db->insert_batch('cd_supervisores', $data_manha);
        }

        $this->db->select("'{$id_supervisor}' AS id_supervisor, a.id AS id_escola, 'T' AS turno", false);
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.id_supervisor = '{$id_supervisor}' AND b.turno = 'T'", 'left');
        $this->db->where_in('a.id', $id_escolas_tarde);
        $this->db->where('b.id', null);
        $data_tarde = $this->db->get('cd_escolas a')->result_array();
        if ($data_tarde) {
            $this->db->insert_batch('cd_supervisores', $data_tarde);
        }

        $this->db->select("'{$id_supervisor}' AS id_supervisor, a.id AS id_escola, 'N' AS turno", false);
        $this->db->join('cd_supervisores b', "b.id_escola = a.id AND b.id_supervisor = '{$id_supervisor}' AND b.turno = 'N'", 'left');
        $this->db->where_in('a.id', $id_escolas_noite);
        $this->db->where('b.id', null);
        $data_noite = $this->db->get('cd_escolas a')->result_array();
        if ($data_noite) {
            $this->db->insert_batch('cd_supervisores', $data_noite);
        }


        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_delete()
    {
        $id_diretoria = $this->input->post('id_diretoria');
        $id_supervisor = $this->input->post('id_supervisor');

        $this->db->trans_start();

        $delete = "DELETE FROM cd_suoervisores 
                   WHERE id_supervisor = '{$id_supervisor}'
                         AND id_escola IN (SELECT id 
                                           FROM cd_escolas 
                                           WHERE id_diretoria = '{$id_diretoria}')";

//        $delete = "DELETE a.* FROM cd_supervisores a
//                          INNER JOIN cd_escolas b ON
//                                     b.id = a.id_escola
//                          WHERE b.id_diretoria = '{$id_diretoria}' AND
//                                a.id_supervisor = '{$id_supervisor}'";
        $this->db->query($delete);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

}
