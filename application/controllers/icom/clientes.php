<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_clientes_model', 'clientes');
    }

    //==========================================================================
    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $deptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $empresa)
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $data = [
            'empresa' => $empresa,
            'deptos' => ['' => 'Todos'] + array_column($deptos, 'nome', 'id'),
            'areas' => ['' => 'Todas'],
            'setores' => ['' => 'Todos'],
            'depto_atual' => '',
            'area_atual' => '',
            'setor_atual' => ''
        ];

        $this->load->view('icom/clientes', $data);
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');

        $rowAreas = $this->db
            ->select('id, nome')
            ->where('id_departamento', $depto)
            ->order_by('nome', 'asc')
            ->get('empresa_areas')
            ->result();

        $areas = ['' => 'Todas'] + array_column($rowAreas, 'nome', 'id');

        $rowSetores = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->where('a.id_area', $area)
            ->where('b.id_departamento', $depto)
            ->order_by('a.nome', 'asc')
            ->get('empresa_setores a')
            ->result();

        $setores = ['' => 'Todos'] + array_column($rowSetores, 'nome', 'id');

        $data = [
            'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'setores' => form_dropdown('id_setor', $setores, $setor, 'class="form-control input-sm"')
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select('a.*', false)
            ->join('empresa_setores b', 'b.id = a.id_setor', 'left')
            ->join('empresa_areas c', 'c.id = b.id_area', 'left')
            ->join('empresa_departamentos d', 'd.id = c.id_departamento', 'left');
        if ($busca['id_depto']) {
            $this->db->where('d.id', $busca['id_depto']);
        }
        if ($busca['id_area']) {
            $this->db->where('c.id', $busca['id_area']);
        }
        if ($busca['id_setor']) {
            $this->db->where('b.id', $busca['id_setor']);
        }
        $query = $this->db
            ->where('a.id_empresa', $this->session->userdata('empresa'))
            ->get($this->clientes::table() . ' a');

        $config = [
            'select' => ['nome', 'contato_principal', 'telefone_principal', 'email_principal', 'id'],
            'search' => ['nome', 'contato_principal', 'contato_secundario', 'email_principal', 'email_secundario']
        ];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->contato_principal,
                $row->telefone_principal,
                $row->email_principal,
                '<button class="btn btn-sm btn-info" onclick="edit_cliente(' . $row->id . ')" title="Editar cliente"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="excluir_cliente(' . $row->id . ')" title="Excluir cliente"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->clientes->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->clientes->errors()]));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomClientes', $this->input->post());

        $this->clientes->setValidationLabel('nome', 'Nome Cliente');
        $this->clientes->setValidationLabel('contato_principal', 'Contato Principal');
        $this->clientes->setValidationLabel('telefone_principal', 'Telefone Principal');
        $this->clientes->setValidationLabel('email_principal', 'E-mail Principal');
        $this->clientes->setValidationLabel('contato_secundario', 'Contato Secundario');
        $this->clientes->setValidationLabel('telefone_secundario', 'Telefone Secundario');
        $this->clientes->setValidationLabel('email_secundario', 'E-mail Secundario');

        $this->clientes->save($data) or exit(json_encode(['erro' => $this->clientes->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->clientes->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->clientes->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function relatorio($isPdf = false)
    {
        $data = $this->db
            ->select('foto, foto_descricao')
            ->where('id', $this->session->userdata('empresa'))
            ->get('usuarios')
            ->row_array();

        $estrutura = $this->db
            ->select('c.nome AS depto, b.nome AS area, a.nome AS setor')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->join('empresa_departamentos c', 'c.id = b.id_departamento')
            ->where('a.id', $this->input->get('setor'))
            ->get('empresa_setores a')
            ->row();

        $data['depto'] = $estrutura->depto;
        $data['area'] = $estrutura->area;
        $data['setor'] = $estrutura->setor;

        $data['rows'] = $this->db
            ->select('a.nome, a.contato_principal, a.telefone_principal, a.email_principal')
            ->where('a.id_empresa', $this->session->userdata('empresa'))
            ->where('a.id_setor', $this->input->get('setor'))
            ->order_by('a.nome', 'asc')
            ->get('icom_clientes a')
            ->result();

        $data['data'] = date('d/m/Y');

        $data['is_pdf'] = $isPdf === true;

        if ($data['is_pdf']) {
            return $this->load->view('icom/pdf_clientes', $data, true);
        }

        $this->load->view('icom/relatorio_clientes', $data);
    }

    //==========================================================================
    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#clientes thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#clientes thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#clientes tbody td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));

        $this->load->library('Calendar');

        $mes_ano = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

        $this->m_pdf->pdf->Output('Mapa de Clientes/Prospects_' . $mes_ano . '.pdf', 'D');
    }

}
