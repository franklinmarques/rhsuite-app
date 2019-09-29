<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cuidadores extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->session->userdata('nivel'), array(0, 4, 7, 8, 9, 10))) {
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


        $this->db->select('DISTINCT(c.depto) AS nome', false);
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $this->db->join('ei_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('ei_supervisores d', 'd.id_escola = b.id', 'left');
        $this->db->where('c.id_empresa', $empresa);
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('d.id_supervisor', $id_usuario);
            $data['busca_depto'] = array();
        } else {
            $data['busca_depto'] = array('' => 'Todos');
        }
        $this->db->group_by('c.depto');
        $this->db->order_by('c.depto', 'asc');
        $busca_deptos = $this->db->get('ei_cuidadores a')->result();
        foreach ($busca_deptos as $busca_depto) {
            $data['busca_depto'][$busca_depto->nome] = $busca_depto->nome;
        }


        $this->db->select('c.id, c.nome', false);
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $this->db->join('ei_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('ei_supervisores d', 'd.id_escola = b.id', 'left');
        $this->db->where('c.id_empresa', $empresa);
        if ($data['depto_selecionado']) {
            $this->db->where('c.depto', $data['depto_selecionado']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('d.id_supervisor', $id_usuario);
            $data['busca_diretoria'] = array();
        } else {
            $data['busca_diretoria'] = array('' => 'Todas');
        }
        $this->db->group_by('c.id');
        $this->db->order_by('c.depto', 'asc');
        $busca_diretorias = $this->db->get('ei_cuidadores a')->result();
        foreach ($busca_diretorias as $busca_diretoria) {
            $data['busca_diretoria'][$busca_diretoria->id] = $busca_diretoria->nome;
        }

        $this->db->select('a.id_escola AS id, b.nome', false);
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $this->db->join('ei_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('ei_supervisores d', 'd.id_escola = b.id', 'left');
        $this->db->where('c.id_empresa', $empresa);
        if ($data['depto_selecionado']) {
            $this->db->where('c.depto', $data['depto_selecionado']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('d.id_supervisor', $id_usuario);
            $data['busca_escola'] = array();
        } else {
            $data['busca_escola'] = array('' => 'Todas');
        }
        $this->db->group_by('b.id');
        $this->db->order_by('b.nome', 'asc');
        $busca_escolas = $this->db->get('ei_cuidadores a')->result();
        foreach ($busca_escolas as $busca_escola) {
            $data['busca_escola'][$busca_escola->id] = $busca_escola->nome;
        }


        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $diretorias = $this->db->get('ei_diretorias')->result();
        $data['id_diretoria'] = array('' => 'selecione...');
        foreach ($diretorias as $diretoria) {
            $data['id_diretoria'][$diretoria->id] = $diretoria->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $escolas = $this->db->get('ei_escolas a')->result();
        $data['id_escola'] = array('' => 'selecione...');
        foreach ($escolas as $escola) {
            $data['id_escola'][$escola->id] = $escola->nome;
        }

        $data['supervisores_manha'] = array('' => 'selecione...');
        $data['supervisores_tarde'] = array('' => 'selecione...');
        $data['supervisores_noite'] = array('' => 'selecione...');


        $data['cuidadores_manha'] = array();
        $data['cuidadores_tarde'] = array();
        $data['cuidadores_noite'] = array();

        $this->load->view('ei/cuidadores', $data);
    }

    //==========================================================================
    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');
        $busca = $this->input->post('busca');
        $filtro = array();


        $this->db->select('c.id, c.nome');
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $this->db->join('ei_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('ei_supervisores d', 'd.id_escola = b.id', 'left');
        $this->db->where('c.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('c.depto', $busca['depto']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('d.id_supervisor', $id_usuario);
            $filtro['diretoria'] = array();
        } else {
            $filtro['diretoria'] = array('' => 'Todas');
        }
        $this->db->order_by('c.nome', 'asc');
        $diretorias = $this->db->get('ei_cuidadores a')->result();
        foreach ($diretorias as $diretoria) {
            $filtro['diretoria'][$diretoria->id] = $diretoria->nome;
        }


        $this->db->select('b.id, b.nome', false);
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $this->db->join('ei_diretorias c', 'c.id = b.id_diretoria');
        $this->db->join('ei_supervisores d', 'd.id_escola = b.id', 'left');
        $this->db->where('c.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('c.depto', $busca['depto']);
        }
        if ($busca['diretoria']) {
            $this->db->where('c.id', $busca['diretoria']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('d.id_supervisor', $id_usuario);
            $filtro['escola'] = array('' => 'Todas');
        } else {
            $filtro['escola'] = array('' => 'Todas');
        }
        $this->db->order_by('b.nome', 'asc');
        $escolas = $this->db->get('ei_cuidadores a')->result();

        foreach ($escolas as $escola) {
            $filtro['escola'][$escola->id] = $escola->nome;
        }


        $data['diretoria'] = form_dropdown('busca[diretoria]', $filtro['diretoria'], $busca['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['escola'] = form_dropdown('busca[escola]', $filtro['escola'], $busca['escola'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    //==========================================================================
    public function atualizar_unidades()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('ei_cuidadores c', 'c.id_escola = b.id', 'left');
        $this->db->join('ei_supervisores d', 'd.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        $this->db->where('c.id', null);
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('d.id_supervisor', $id_usuario);
        }
        $this->db->group_by('a.id', 'asc');
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('ei_diretorias a')->result();
        $diretorias = array('' => 'selecione...');
        foreach ($rows as $row) {
            $diretorias[$row->id] = $row->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_cuidadores c', 'c.id_escola = a.id', 'left');
        $this->db->join('ei_supervisores d', 'd.id_escola = a.id', 'left');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('c.id', null);
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('d.id_supervisor', $id_usuario);
        }
        $this->db->order_by('a.nome', 'asc');
        $rows2 = $this->db->get('ei_escolas a')->result();
        $escolas = array('' => 'selecione...');
        foreach ($rows2 as $row2) {
            $escolas[$row2->id] = $row2->nome;
        }


        $data['id_diretoria'] = form_dropdown('id_diretoria', $diretorias, '', 'id="id_diretoria" class="form-control input-sm"');
        $data['id_escola'] = form_dropdown('id_escola', $escolas, '', 'id="id_escola" class="form-control input-sm"');

        $data['supervisores_manha'] = form_dropdown('id_supervisor[M]', array('' => 'selecione...'), '', 'id="supervisores_manha" class="form-control input-sm"');
        $data['supervisores_tarde'] = form_dropdown('id_supervisor[T]', array('' => 'selecione...'), '', 'id="supervisores_tarde" class="form-control input-sm"');
        $data['supervisores_noite'] = form_dropdown('id_supervisor[N]', array('' => 'selecione...'), '', 'id="supervisores_noite" class="form-control input-sm"');

        echo json_encode($data);
    }

    //==========================================================================
    public function atualizar_cuidadores()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');
        $id_diretoria = $this->input->post('id_diretoria');
        $id_escola = $this->input->post('id_escola');

        $this->db->select('a.id, a.nome, b.depto');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_cuidadores c', 'c.id_escola = a.id', 'left');
        $this->db->join('ei_supervisores d', 'd.id_escola = a.id', 'left');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('b.id', $id_diretoria);
        $this->db->where('c.id', null);
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('d.id_supervisor', $id_usuario);
        }
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('ei_escolas a')->result();
        $escolas = array('' => 'selecione...');
        $depto = $rows[0]->depto ?? '';
        foreach ($rows as $row) {
            $escolas[$row->id] = $row->nome;
        }

        $data['id_escola'] = form_dropdown('id_escola', $escolas, $id_escola, 'id="id_escola" class="form-control input-sm"');


        $supervisores = $this->getSupervisores($depto, $id_escola);

        $supervisor_manha = array_keys($supervisores['M'])[0] ?? '';
        $supervisor_tarde = array_keys($supervisores['T'])[0] ?? '';
        $supervisor_noite = array_keys($supervisores['N'])[0] ?? '';


        $data['supervisores_manha'] = form_dropdown('id_supervisor[M]', $supervisores['M'], $supervisor_manha, 'id="supervisores_manha" class="form-control input-sm"');
        $data['supervisores_tarde'] = form_dropdown('id_supervisor[T]', $supervisores['T'], $supervisor_tarde, 'id="supervisores_tarde" class="form-control input-sm"');
        $data['supervisores_noite'] = form_dropdown('id_supervisor[N]', $supervisores['N'], $supervisor_noite, 'id="supervisores_noite" class="form-control input-sm"');


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.alias = a.area');
        $this->db->join('ei_escolas c', 'c.id_diretoria = b.id');
        $this->db->join('ei_cuidadores d', "d.id_cuidador = a.id AND d.turno = 'M'", 'left');
        $this->db->where('a.empresa', $empresa);
        $this->db->where('b.id', $id_diretoria);
        $this->db->where('c.id', $id_escola);
        $this->db->where('d.id', null);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_manha = $this->db->get('usuarios a')->result();
        $cuidadores_manha = array();
        foreach ($rows_manha as $row_manha) {
            $cuidadores_manha[$row_manha->id] = $row_manha->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.alias = a.area');
        $this->db->join('ei_escolas c', 'c.id_diretoria = b.id');
        $this->db->join('ei_cuidadores d', "d.id_cuidador = a.id AND d.turno = 'T'", 'left');
        $this->db->where('a.empresa', $empresa);
        $this->db->where('b.id', $id_diretoria);
        $this->db->where('c.id', $id_escola);
        $this->db->where('d.id', null);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_tarde = $this->db->get('usuarios a')->result();
        $cuidadores_tarde = array();
        foreach ($rows_tarde as $row_tarde) {
            $cuidadores_tarde[$row_tarde->id] = $row_tarde->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.alias = a.area');
        $this->db->join('ei_escolas c', 'c.id_diretoria = b.id');
        $this->db->join('ei_cuidadores d', "d.id_cuidador = a.id AND d.turno = 'N'", 'left');
        $this->db->where('a.empresa', $empresa);
        $this->db->where('b.id', $id_diretoria);
        $this->db->where('c.id', $id_escola);
        $this->db->where('d.id', null);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_noite = $this->db->get('usuarios a')->result();
        $cuidadores_noite = array();
        foreach ($rows_noite as $row_noite) {
            $cuidadores_noite[$row_noite->id] = $row_noite->nome;
        }


        $data['id_manha'] = form_multiselect('id_manha[]', $cuidadores_manha, array(), 'id="id_manha" class="demo2" size="8"');
        $data['id_tarde'] = form_multiselect('id_tarde[]', $cuidadores_tarde, array(), 'id="id_tarde" class="demo2" size="8"');
        $data['id_noite'] = form_multiselect('id_noite[]', $cuidadores_noite, array(), 'id="id_noite" class="demo2" size="8"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();

        $sql = "SELECT s.id,
                       s.diretoria,
                       s.escola,
                       s.turno,
                       s.supervisor,
                       s.nome,
                       s.descricao_turno,
                       s.id_escola
                FROM (SELECT a.id,
                             a.id_escola,
                             CASE a.turno WHEN 'M' THEN 1
                                          WHEN 'T' THEN 2
                                          WHEN 'N' THEN 3
                                          ELSE 4 END AS turno,
                             CASE a.turno WHEN 'M' THEN 'Manhã'
                                          WHEN 'T' THEN 'Tarde'
                                          WHEN 'N' THEN 'Noite'
                                          ELSE 'Integral' END AS descricao_turno,
                             b.nome,
                             c.nome AS escola,
                             d.nome AS diretoria,
                             f.nome AS supervisor
                      FROM ei_cuidadores a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_cuidador
                      INNER JOIN ei_escolas c ON 
                                 c.id = a.id_escola
                      INNER JOIN ei_diretorias d ON 
                                 d.id = c.id_diretoria
                      LEFT JOIN ei_supervisores e ON e.id_escola = c.id AND e.turno = a.turno
                      LEFT JOIN usuarios f ON f.id = e.id_supervisor
                      WHERE b.empresa = {$this->session->userdata('empresa')} 
                      ";
        if (!empty($busca['depto'])) {
            $sql .= " AND d.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['diretoria'])) {
            $sql .= " AND d.id = '{$busca['diretoria']}'";
        }
        if (!empty($busca['escola'])) {
            $sql .= " AND c.id = '{$busca['escola']}'";
        }
        $sql .= ' GROUP BY a.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.diretoria', 's.escola', 's.turno', 's.supervisor', 's.nome');
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

        $orderBy = array_map(function ($key) {
            return ($key + 2) . ' asc';
        }, array_keys($columns));

        if (isset($post['order'])) {
            foreach ($post['order'] as $order) {
                $orderBy[$order['column']] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
        }
        $sql .= " 
                ORDER BY " . implode(', ', $orderBy) . "
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $ei) {
            $row = array();
            $row[] = $ei->diretoria;
            $row[] = $ei->escola;
            $row[] = $ei->descricao_turno;
            $row[] = $ei->supervisor;
            $row[] = $ei->nome;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_cuidadores(' . $ei->id_escola . ')" title="Vincular cuidadores"><i class="glyphicon glyphicon-plus"></i> Vincular cuidadores</button>&emsp;&emsp;
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_cuidadores(' . $ei->id_escola . ')" title="Desvincular cuidadores"><i class="glyphicon glyphicon-minus"></i> </button>
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
    private function getSupervisores($depto, $id_escola)
    {
        $empresa = $this->session->userdata('empresa');
        $turnos = array('M', 'T', 'N');
        $data = array();

        foreach ($turnos as $turno) {

            $sql = "SELECT id, nome 
                     FROM usuarios 
                     WHERE empresa = '{$empresa}' 
                           AND depto = '{$depto}' 
                           AND area = 'Supervisao' 
                           AND nivel_acesso = 10 
                           AND NOT EXISTS 
                               (SELECT a.id, b.nome 
                                FROM ei_supervisores a 
                                INNER JOIN usuarios b 
                                           ON b.id = a.id_supervisor
                                WHERE b.empresa = '{$empresa}'
                                      AND a.id_escola = '{$id_escola}' 
                                      AND a.turno = '{$turno}')
                     UNION
                     SELECT a.id, b.nome 
                     FROM ei_supervisores a 
                     INNER JOIN usuarios b 
                                ON b.id = a.id_supervisor
                     WHERE b.empresa = '{$empresa}'
                           AND a.id_escola = '{$id_escola}'
                           AND a.turno = '{$turno}'";
            $rows = $this->db->query($sql)->result();

            $data[$turno] = count($rows) === 1 ? array() : array('' => 'selecione...');
            foreach ($rows as $row) {
                $data[$turno][$row->id] = $row->nome;
            }
        }

        return $data;
    }

    //==========================================================================
    public function ajax_edit()
    {
        $empresa = $this->session->userdata('empresa');
        $id_escola = $this->input->post('id_escola');


        $this->db->select('b.id AS id_diretoria, b.nome AS diretoria', false);
        $this->db->select('a.id AS id_escola, a.nome AS escola, b.depto', false);
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('a.id', $id_escola);
        $row = $this->db->get('ei_escolas a')->row();

        $diretorias = array($row->id_diretoria => $row->diretoria);
        $escolas = array($row->id_escola => $row->escola);
        $id_diretoria = $row->id_diretoria ?? '';

        $data['id_diretoria'] = form_dropdown('id_diretoria', $diretorias, $row->id_diretoria, 'id="id_diretoria" class="form-control input-sm"');
        $data['id_escola'] = form_dropdown('id_escola', $escolas, $row->id_escola, 'id="id_escola" class="form-control input-sm"');

        $supervisores = $this->getSupervisores($row->depto, $id_escola);

        $supervisor_manha = array_keys($supervisores['M'])[0] ?? '';
        $supervisor_tarde = array_keys($supervisores['T'])[0] ?? '';
        $supervisor_noite = array_keys($supervisores['N'])[0] ?? '';


        $data['supervisores_manha'] = form_dropdown('id_supervisor[M]', $supervisores['M'], $supervisor_manha, 'id="supervisores_manha" class="form-control input-sm"');
        $data['supervisores_tarde'] = form_dropdown('id_supervisor[T]', $supervisores['T'], $supervisor_tarde, 'id="supervisores_tarde" class="form-control input-sm"');
        $data['supervisores_noite'] = form_dropdown('id_supervisor[N]', $supervisores['N'], $supervisor_noite, 'id="supervisores_noite" class="form-control input-sm"');


        $this->db->select('a.id, a.nome, d.id_cuidador');
        $this->db->join('ei_diretorias b', 'b.alias = a.area');
        $this->db->join('ei_escolas c', 'c.id_diretoria = b.id');
        $this->db->join('ei_cuidadores d', "d.id_cuidador = a.id AND d.id_escola = c.id AND d.turno = 'M'", 'left');
        $this->db->where('b.id', $id_diretoria);
        $this->db->where('c.id', $id_escola);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_manha = $this->db->get('usuarios a')->result();
        $cuidadores_manha = array();
        $selecionados_manha = array();
        foreach ($rows_manha as $row_manha) {
            $cuidadores_manha[$row_manha->id] = $row_manha->nome;
            if ($row_manha->id_cuidador) {
                $selecionados_manha[] = $row_manha->id;
            }
        }

        $this->db->select('a.id, a.nome, d.id_cuidador');
        $this->db->join('ei_diretorias b', 'b.alias = a.area');
        $this->db->join('ei_escolas c', 'c.id_diretoria = b.id');
        $this->db->join('ei_cuidadores d', "d.id_cuidador = a.id AND d.id_escola = c.id AND d.turno = 'T'", 'left');
        $this->db->where('b.id', $id_diretoria);
        $this->db->where('c.id', $id_escola);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_tarde = $this->db->get('usuarios a')->result();
        $cuidadores_tarde = array();
        $selecionados_tarde = array();
        foreach ($rows_tarde as $row_tarde) {
            $cuidadores_tarde[$row_tarde->id] = $row_tarde->nome;
            if ($row_tarde->id_cuidador) {
                $selecionados_tarde[] = $row_tarde->id;
            }
        }

        $this->db->select('a.id, a.nome, d.id_cuidador');
        $this->db->join('ei_diretorias b', 'b.alias = a.area');
        $this->db->join('ei_escolas c', 'c.id_diretoria = b.id');
        $this->db->join('ei_cuidadores d', "d.id_cuidador = a.id AND d.id_escola = c.id AND d.turno = 'N'", 'left');
        $this->db->where('b.id', $id_diretoria);
        $this->db->where('c.id', $id_escola);
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rows_noite = $this->db->get('usuarios a')->result();
        $cuidadores_noite = array();
        $selecionados_noite = array();
        foreach ($rows_noite as $row_noite) {
            $cuidadores_noite[$row_noite->id] = $row_noite->nome;
            if ($row_noite->id_cuidador) {
                $selecionados_noite[] = $row_noite->id;
            }
        }


        $data['id_manha'] = form_multiselect('id_manha[]', $cuidadores_manha, $selecionados_manha, 'id="id_manha" class="demo2" size="8"');
        $data['id_tarde'] = form_multiselect('id_tarde[]', $cuidadores_tarde, $selecionados_tarde, 'id="id_tarde" class="demo2" size="8"');
        $data['id_noite'] = form_multiselect('id_noite[]', $cuidadores_noite, $selecionados_noite, 'id="id_noite" class="demo2" size="8"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_save()
    {
        $id_escola = $this->input->post('id_escola');

        $id_supervisor = $this->input->post('id_supervisor');

        $id_manha = $this->input->post('id_manha');
        $id_tarde = $this->input->post('id_tarde');
        $id_noite = $this->input->post('id_noite');
        $str_id_manha = "'" . implode("','", is_array($id_manha) ? $id_manha : array()) . "'";
        $str_id_tarde = "'" . implode("','", is_array($id_tarde) ? $id_tarde : array()) . "'";
        $str_id_noite = "'" . implode("','", is_array($id_noite) ? $id_noite : array()) . "'";

        $this->db->trans_start();


        $delete = "DELETE s.* FROM ei_cuidadores s
                   LEFT JOIN (SELECT *
                              FROM ei_cuidadores 
                              WHERE id_escola = {$id_escola}
                                    AND ((id_cuidador IN ({$str_id_manha}) AND turno = 'M')
                                      OR (id_cuidador IN ({$str_id_tarde}) AND turno = 'T')
                                      OR (id_cuidador IN ({$str_id_noite}) AND turno = 'N'))) a ON a.id = s.id
                   WHERE s.id_escola = {$id_escola} AND 
                         a.id IS NULL";
        $this->db->query($delete);

        $this->db->set('id_supervisor', $id_supervisor['M']);
        $this->db->where('id_escola', $id_escola);
        $this->db->where('turno', 'M');
        $this->db->update('ei_cuidadores');

        $this->db->set('id_supervisor', $id_supervisor['T']);
        $this->db->where('id_escola', $id_escola);
        $this->db->where('turno', 'T');
        $this->db->update('ei_cuidadores');

        $this->db->set('id_supervisor', $id_supervisor['N']);
        $this->db->where('id_escola', $id_escola);
        $this->db->where('turno', 'N');
        $this->db->update('ei_cuidadores');


        $this->db->select("a.id AS id_cuidador, '{$id_escola}' AS id_escola, 'M' AS turno", false);
        if ($id_supervisor['M']) {
            $this->db->select("'{$id_supervisor['M']}' AS id_supervisor", false);
        }
        $this->db->join('ei_cuidadores b', "b.id_cuidador = a.id AND b.id_escola = {$id_escola} AND b.turno = 'M'", 'left');
        $this->db->where_in('a.id', $id_manha);
        $this->db->where('b.id', null);
        $data_manha = $this->db->get('usuarios a')->result_array();
        if ($data_manha) {
            $this->db->insert_batch('ei_cuidadores', $data_manha);
        }

        $this->db->select("a.id AS id_cuidador, '{$id_escola}' AS id_escola, 'T' AS turno", false);
        if ($id_supervisor['T']) {
            $this->db->select("'{$id_supervisor['T']}' AS id_supervisor", false);
        }
        $this->db->join('ei_cuidadores b', "b.id_cuidador = a.id AND b.id_escola = {$id_escola} AND b.turno = 'T'", 'left');
        $this->db->where_in('a.id', $id_tarde);
        $this->db->where('b.id', null);
        $data_tarde = $this->db->get('usuarios a')->result_array();
        if ($data_tarde) {
            $this->db->insert_batch('ei_cuidadores', $data_tarde);
        }

        $this->db->select("a.id AS id_cuidador, '{$id_escola}' AS id_escola, 'N' AS turno", false);
        if ($id_supervisor['N']) {
            $this->db->select("'{$id_supervisor['N']}' AS id_supervisor", false);
        }
        $this->db->join('ei_cuidadores b', "b.id_cuidador = a.id AND b.id_escola = {$id_escola} AND b.turno = 'N'", 'left');
        $this->db->where_in('a.id', $id_noite);
        $this->db->where('b.id', null);
        $data_noite = $this->db->get('usuarios a')->result_array();
        if ($data_noite) {
            $this->db->insert_batch('ei_cuidadores', $data_noite);
        }


        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_delete()
    {
        $empresa = $this->session->userdata('empresa');
        $id_escola = $this->input->post('id_escola');

        $this->db->trans_start();

        $this->db->delete('ei_cuidadores', array('id_escola' => $id_escola));

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

}
