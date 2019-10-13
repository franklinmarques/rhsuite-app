<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Diretorias extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->session->userdata('nivel'), array(0, 7, 8, 9))) {
            redirect(site_url('home'));
        }
    }

    //==========================================================================
    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $data = array();

        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('empresa', $empresa);
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $this->db->order_by('depto', 'asc');
        $deptos_disponiveis = $this->db->get('usuarios')->result();
        $data['deptos_disponiveis'] = array('' => 'selecione...');
        foreach ($deptos_disponiveis as $depto_disponivel) {
            $data['deptos_disponiveis'][$depto_disponivel->nome] = $depto_disponivel->nome;
        }

        $data['cuidadores'] = '';
        $data['coordenadores'] = array('' => 'selecione...');

        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('empresa', $empresa);
        $this->db->where('depto', 'cuidadores');
        $cuidadores = $this->db->get('usuarios')->row();

        if (count($cuidadores) > 0) {
            $data['cuidadores'] = $cuidadores->nome;

            $this->db->select('id, nome');
            $this->db->where('empresa', $empresa);
            $this->db->where('depto', $cuidadores->nome);
            $this->db->order_by('nome', 'asc');
            $usuarios = $this->db->get('usuarios')->result();
            foreach ($usuarios as $usuario) {
                $data['coordenadores'][$usuario->id] = $usuario->nome;
            }
        }

        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('depto', 'asc');
        $deptos = $this->db->get('cd_diretorias')->result();
        $data['depto'] = array('' => 'Todos');
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('DISTINCT(nome) AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $diretorias = $this->db->get('cd_diretorias')->result();
        $data['diretoria'] = array('' => 'Todas');
        foreach ($diretorias as $diretoria) {
            $data['diretoria'][$diretoria->nome] = $diretoria->nome;
        }

        $this->db->select('a.id_coordenador AS id, b.nome', false);
        $this->db->join('usuarios b', 'b.id = a.id_coordenador');
        $this->db->where('a.id_empresa', $empresa);
        $this->db->order_by('b.nome', 'asc');
        $this->db->group_by('a.id_coordenador');
        $coordenadores = $this->db->get('cd_diretorias a')->result();
        $data['coordenador'] = array('' => 'Todos');
        foreach ($coordenadores as $coordenador) {
            $data['coordenador'][$coordenador->id] = $coordenador->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('contrato', 'asc');
        $contratos = $this->db->get('cd_diretorias')->result();
        $data['contrato'] = array('' => 'Todos');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->load->view('cd/diretorias', $data);
    }

    //==========================================================================
    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $busca = $this->input->post('busca');
        $filtro = array();

        $this->db->select('DISTINCT(nome) AS nome', false);
        $this->db->where('id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('depto', $busca['depto']);
        }
        $this->db->order_by('nome', 'asc');
        $diretorias = $this->db->get('cd_diretorias')->result();
        $filtro['diretoria'] = array('' => 'Todas');
        foreach ($diretorias as $diretoria) {
            $filtro['diretoria'][$diretoria->nome] = $diretoria->nome;
        }

        $this->db->select('a.id_coordenador AS id, b.nome', false);
        $this->db->join('usuarios b', 'b.id = a.id_coordenador');
        $this->db->where('a.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('a.depto', $busca['depto']);
        }
        if ($busca['diretoria']) {
            $this->db->where('a.nome', $busca['diretoria']);
        }
        $this->db->order_by('b.nome', 'asc');
        $this->db->group_by('a.id_coordenador');
        $coordenadores = $this->db->get('cd_diretorias a')->result();
        $filtro['coordenador'] = array('' => 'Todos');
        foreach ($coordenadores as $coordenador) {
            $filtro['coordenador'][$coordenador->id] = $coordenador->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome', false);
        $this->db->where('id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('depto', $busca['depto']);
        }
        if ($busca['diretoria']) {
            $this->db->where('nome', $busca['diretoria']);
        }
        if ($busca['coordenador']) {
            $this->db->where('id_coordenador', $busca['coordenador']);
        }
        $this->db->order_by('contrato', 'asc');
        $contratos = $this->db->get('cd_diretorias')->result();
        $filtro['contrato'] = array('' => 'Todos');
        foreach ($contratos as $contrato) {
            $filtro['contrato'][$contrato->nome] = $contrato->nome;
        }


        $data['diretoria'] = form_dropdown('diretoria', $filtro['diretoria'], $busca['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['coordenador'] = form_dropdown('coordenador', $filtro['coordenador'], $busca['coordenador'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['contrato'] = form_dropdown('contrato', $filtro['contrato'], $busca['contrato'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

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
                       s.contrato
                FROM (SELECT a.id,
                             a.nome,
                             a.contrato
                      FROM cd_diretorias a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_empresa 
                      LEFT JOIN usuarios c ON
                                c.id = a.id_coordenador
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}";
        if (!empty($busca['depto'])) {
            $sql .= " AND a.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['diretoria'])) {
            $sql .= " AND a.nome = '{$busca['diretoria']}'";
        }
        if (!empty($busca['coordenador'])) {
            $sql .= " AND a.id_coordenador = '{$busca['coordenador']}'";
        }
        if (!empty($busca['contrato'])) {
            $sql .= " AND a.contrato = '{$busca['contrato']}'";
        }
        $sql .= ' GROUP BY a.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.contrato');
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
            $row[] = $cd->contrato;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_diretoria(' . $cd->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <a class="btn btn-sm btn-primary" href="' . site_url('cd/escolas/gerenciar/' . $cd->id) . '" title="Gerenciar unidades de ensino"><i class="glyphicon glyphicon-plus"></i> Unidade Ensino</a>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_diretoria(' . $cd->id . ')" title="excluir"><i class="glyphicon glyphicon-trash"></i> </button>
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
        $id = $this->input->post('id');
        $data = $this->db->get_where('cd_diretorias', array('id' => $id))->row();

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_estrutura()
    {
        $depto = $this->input->post('depto');
        $id = $this->input->post('id_coordenador');

        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $depto);
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();

        $coordenadores = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $coordenadores[$usuario->id] = $usuario->nome;
        }

        $data['id_coordenador'] = form_dropdown('id_coordenador', $coordenadores, $id, 'id="id_coordenador" class="form-control"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_add()
    {
        $data = $this->input->post();
        $data['id_empresa'] = $this->session->userdata('empresa');
        unset($data['id']);
        if (empty($data['alias'])) {
            $data['alias'] = null;
        }

        $status = $this->db->insert('cd_diretorias', $data);
        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_update()
    {
        $data = $this->input->post();
        $data['id_empresa'] = $this->session->userdata('empresa');
        $id = $data['id'];
        unset($data['id']);
        if (empty($data['alias'])) {
            $data['alias'] = null;
        }

        $status = $this->db->update('cd_diretorias', $data, array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    //==========================================================================
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('cd_diretorias', array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }

}
