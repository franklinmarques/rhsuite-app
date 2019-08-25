<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Colaboradores extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('empresa_departamentos_model', 'departamentos');
        $this->load->model('empresa_areas_model', 'areas');
        $this->load->model('empresa_setores_model', 'setores');
    }

    //==========================================================================
    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $deptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $empresa)
            ->where('nome', 'ICOM')
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $data = [
            'empresa' => $empresa,
            'deptos' => array_column($deptos, 'nome', 'id'),
            'areas' => ['' => 'Todas'],
            'setores' => ['' => 'Todos'],
            'depto_atual' => '',
            'area_atual' => '',
            'setor_atual' => ''
        ];

        $this->load->view('icom/colaboradores', $data);
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');

        $data = $this->carregarEstrutura($depto, $area, $setor, true);

        echo json_encode($data);
    }

    //==========================================================================
    public function montarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');

        $data = $this->carregarEstrutura($depto, $area, $setor);

        echo json_encode($data);
    }

    //==========================================================================
    private function carregarEstrutura($depto = 0, $area = 0, $setor = 0, $todos = false)
    {
        $rowDeptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $deptos = ['' => ($todos ? 'Todos' : 'selecione...')] + array_column($rowDeptos, 'nome', 'id');

        $rowAreas = $this->db
            ->select('id, nome')
            ->where('id_departamento', $depto)
            ->order_by('nome', 'asc')
            ->get('empresa_areas')
            ->result();

        $areas = ['' => ($todos ? 'Todas' : 'selecione...')] + array_column($rowAreas, 'nome', 'id');

        $rowSetores = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->where('a.id_area', $area)
            ->where('b.id_departamento', $depto)
            ->order_by('a.nome', 'asc')
            ->get('empresa_setores a')
            ->result();

        $setores = ['' => ($todos ? 'Todos' : 'selecione...')] + array_column($rowSetores, 'nome', 'id');

        $data = [
            'deptos' => form_dropdown('id_depto', $deptos, $depto, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'setores' => form_dropdown('id_setor', $setores, $setor, 'class="form-control input-sm"')
        ];

        return $data;
    }

    //==========================================================================
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select(["a.nome, CONCAT_WS('/', b.nome, c.nome, d.nome) AS estrutura"], false)
            ->select('e.nome AS funcao, a.id')
            ->join('empresa_departamentos b', 'b.id = a.id_depto')
            ->join('empresa_areas c', 'c.id = a.id_area')
            ->join('empresa_setores d', 'd.id = a.id_setor')
            ->join('empresa_funcoes e', 'e.id = a.id_funcao')
            ->where('empresa', $this->session->userdata('empresa'));
        if ($busca['id_depto']) {
            $this->db->where('b.id', $busca['id_depto']);
        }
        if ($busca['id_area']) {
            $this->db->where('c.id', $busca['id_area']);
        }
        if ($busca['id_setor']) {
            $this->db->where('d.id', $busca['id_setor']);
        }
        $query = $this->db
            ->group_by('a.id')
            ->get('usuarios a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->nome,
                $row->estrutura,
                $row->funcao,
                '<a class="btn btn-sm btn-primary" href="' . site_url('icom/colaboradores/editar/' . $row->id) . '" title="Edição rápida"><i class="glyphicon glyphicon-pencil"></i> Edição rápida</a>
                 <a class="btn btn-sm btn-primary" href="' . site_url('icom/colaboradores/editar/' . $row->id) . '" title="Treinamentos"><i class="glyphicon glyphicon-list-alt"></i> Treinamentos</a>
                 <a class="btn btn-sm btn-primary" href="' . site_url('icom/colaboradores/editar/' . $row->id) . '" title="PDIs"><i class="glyphicon glyphicon-list-alt"></i> PDIs</a>'
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $funcionario = $this->db
            ->get_where('usuarios', ['id' => $this->uri->rsegment(3)])
            ->row();

        if (empty($funcionario)) {
            redirect(site_url('icom/colaboradores'));
        }

        if ($funcionario->hash_acesso) {
            $this->load->library('encrypt');
            $funcionario->hash_acesso = $this->encrypt->decode($funcionario->hash_acesso, base64_encode($funcionario->id));
        } else {
            $funcionario->hash_acesso = 'null';
        }

        $dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_admissao)));
        $funcionario->data_admissao = $dataFormatada;
        $funcionario->saldo_apontamentos = $this->db->query("SELECT TIME_FORMAT('{$funcionario->saldo_apontamentos}', '%H:%i') AS hora")->row()->hora;
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

        $this->load->view('icom/colaborador_perfil', $data);
    }

    //==========================================================================
    public function salvar()
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
        $data['contrato'] = $this->input->post('contrato');
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['status'] = $this->input->post('status');
        $saldo_apontamentos = $this->input->post('saldo_apontamentos');
        $data['saldo_apontamentos'] = $this->db->query("SELECT TIME('{$saldo_apontamentos}') AS hora")->row()->hora;

        if ($this->db->where('id', $funcionario->id)->update('usuarios', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Funcionário editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('icom/colaboradores')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    //==========================================================================
    public function pdf()
    {
        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $busca = $this->input->get();

        $this->db
            ->select(["a.nome, CONCAT_WS('/', b.nome, c.nome, d.nome) AS estrutura"], false)
            ->select(["DATE_FORMAT(a.data_admissao, '%d/%m/%Y') AS data_admissao"], false)
            ->select('e.nome AS funcao, a.id')
            ->join('empresa_departamentos b', 'b.id = a.id_depto')
            ->join('empresa_areas c', 'c.id = a.id_area')
            ->join('empresa_setores d', 'd.id = a.id_setor')
            ->join('empresa_funcoes e', 'e.id = a.id_funcao')
            ->where('empresa', $this->session->userdata('empresa'));
        if (!empty($busca['id_depto'])) {
            $this->db->where('b.id', $busca['id_depto']);
        }
        if (!empty($busca['id_area'])) {
            $this->db->where('c.id', $busca['id_area']);
        }
        if (!empty($busca['id_setor'])) {
            $this->db->where('d.id', $busca['id_setor']);
        }
        $data['colaboradores'] = $this->db
            ->group_by('a.id')
            ->get('usuarios a')
            ->result();

        $this->load->library('m_pdf');

        $stylesheet = '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->load->view('icom/colaboradores_pdf', $data, true));

        $this->m_pdf->pdf->Output('Colaboradores.pdf', 'D');
    }


}
