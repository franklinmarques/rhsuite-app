<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Diretorias extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->session->userdata('nivel'), [0, 7, 8, 9])) {
            redirect(site_url('home'));
        }

        $this->load->model('cd_diretorias_model', 'diretorias');
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

        $data['empresa'] = $empresa;
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
    public function atualizarFiltro()
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
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select('nome, contrato, id')
            ->where('id_empresa', $this->session->userdata('empresa'));
        if (!empty($busca['depto'])) {
            $this->db->where('depto', $busca['depto']);
        }
        if (!empty($busca['diretoria'])) {
            $this->db->where('nome', $busca['diretoria']);
        }
        if (!empty($busca['coordenador'])) {
            $this->db->where('id_coordenador', $busca['coordenador']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('contrato', $busca['contrato']);
        }
        $query = $this->db->get('cd_diretorias');

        $this->load->library('dataTables', ['search' => ['nome', 'contrato']]);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->nome,
                $row->contrato,
                '<button type="button" class="btn btn-sm btn-info" onclick="edit_diretoria(' . $row->id . ')" title="Editar diretoria"><i class="glyphicon glyphicon-pencil"></i> </button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('cd/escolas/gerenciar/' . $row->id) . '" title="Gerenciar unidades de ensino">Unidade Ensino</a>
                 <button type="button" class="btn btn-sm btn-danger" onclick="delete_diretoria(' . $row->id . ')" title="Excluir diretoria"><i class="glyphicon glyphicon-trash"></i> </button>'
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->diretorias->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->diretorias->errors()]));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function atualizarEstrutura()
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
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('cdDiretorias', $this->input->post());

        $this->diretorias->setValidationLabel('nome', 'Diretoria de Ensino');
        $this->diretorias->setValidationLabel('alias', 'Diretoria de Ensino (Alias)');
        $this->diretorias->setValidationLabel('depto', 'Departamento');
        $this->diretorias->setValidationLabel('municipio', 'Município');
        $this->diretorias->setValidationLabel('contrato', 'Contrato');
        $this->diretorias->setValidationLabel('id_coordenador', 'Coordenador(a)');

        $this->diretorias->save($data) or exit(json_encode(['erro' => $this->diretorias->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->diretorias->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->diretorias->errors()]));

        echo json_encode(['status' => true]);
    }

}
