<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contratos extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_contratos_model', 'contratos');
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

        $this->load->model('icom_propostas_model', 'propostas');
        $this->load->model('icom_clientes_model', 'clientes');

        $data = [
            'empresa' => $empresa,
            'codigoProposta' => $this->propostas->findColumn('codigo', 'codigo', 'selecione...'),
            'idCliente' => $this->clientes->findColumn('nome', 'id', 'selecione...'),
            'statusAtivo' => $this->contratos::status(),
            'deptos' => ['' => 'Todos'] + array_column($deptos, 'nome', 'id'),
            'areas' => ['' => 'Todas'],
            'setores' => ['' => 'Todos'],
            'depto_atual' => '',
            'area_atual' => '',
            'setor_atual' => ''
        ];

        $this->load->view('icom/contratos', $data);
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');
        $cliente = $this->input->post('id_cliente');
        $proposta = $this->input->post('codigo_proposta');

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

        $rowClientes = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_setores b', 'b.id = a.id_setor')
            ->join('empresa_areas c', 'c.id = b.id_area')
            ->where('a.id_setor', $setor)
            ->where('b.id_area', $area)
            ->where('c.id_departamento', $depto)
            ->order_by('a.nome', 'asc')
            ->get('icom_clientes a')
            ->result();

        $clientes = ['' => 'Todos'] + array_column($rowClientes, 'nome', 'id');

        $rowPropostas = $this->db
            ->select('a.codigo')
            ->join('icom_clientes b', 'b.id = a.id_cliente')
            ->join('empresa_setores c', 'c.id = b.id_setor')
            ->join('empresa_areas d', 'd.id = c.id_area')
            ->where('a.id_cliente', $cliente)
            ->where('b.id_setor', $setor)
            ->where('c.id_area', $area)
            ->where('d.id_departamento', $depto)
            ->order_by('a.codigo', 'asc')
            ->get('icom_propostas a')
            ->result();

        $propostas = ['' => 'Todas'] + array_column($rowPropostas, 'codigo', 'codigo');

        $data = [
            'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_estrutura();" class="form-control input-sm"'),
            'setores' => form_dropdown('id_setor', $setores, $setor, 'onchange="filtrar_estrutura();" class="form-control input-sm"'),
            'clientes' => form_dropdown('id_cliente', $clientes, $cliente, 'class="form-control input-sm"'),
            'propostas' => form_dropdown('codigo_proposta', $propostas, $proposta, 'class="form-control input-sm"')
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function filtrarPropostas()
    {
        $rowPropostas = $this->db
            ->select('codigo')
            ->where('id_cliente', $this->input->post('id_cliente'))
            ->order_by('codigo', 'asc')
            ->get('icom_propostas')
            ->result();

        $propostas = ['' => 'selecione...'] +
            array_column($rowPropostas, 'codigo', 'codigo');

        $data = [
            'propostas' => form_dropdown('', $propostas, $this->input->post('codigo_proposta'))
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function listar()
    {
        $query = $this->db
            ->select('a.codigo, a.codigo_proposta, c.nome AS nome_cliente')
            ->select(["FORMAT(a.centro_custo, 2, 'de_DE') AS centro_custo"], false)
            ->select(["DATE_FORMAT(a.data_vencimento, '%d/%m/%Y') AS data_vencimento"], false)
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = a.id_cliente')
            ->where('a.id_empresa', $this->session->userdata('empresa'))
            ->get('icom_contratos a');

        $config = ['search' => ['codigo', 'codigo_proposta', 'nome_cliente']];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = array(
                $row->codigo,
                $row->codigo_proposta,
                $row->nome_cliente,
                $row->centro_custo,
                $row->data_vencimento,
                '<button class="btn btn-sm btn-info" onclick="edit_contrato(' . $row->codigo . ')" title="Editar contrato"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_contrato(' . $row->codigo . ')" title="Excluir contrato"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->contratos->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->contratos->errors()]));
        }

        if ($data->data_vencimento) {
            $data->data_vencimento = date('d/m/Y', strtotime($data->data_vencimento));
        }

        $rowClientes = $this->db
            ->select('a.id, a.nome')
            ->join('icom_clientes b', 'b.id_setor = a.id_setor')
            ->where('b.id', $data->id_cliente)
            ->order_by('a.nome', 'asc')
            ->get('icom_clientes a')
            ->result();

        $clientes = ['' => 'selecione...'] +
            array_column($rowClientes, 'nome', 'id');

        $data->clientes = form_dropdown('', $clientes, $data->id_cliente);

        $rowPropostas = $this->db
            ->select('codigo')
            ->where('id_cliente', $data->id_cliente)
            ->order_by('codigo', 'asc')
            ->get('icom_propostas')
            ->result();

        $propostas = ['' => 'selecione...'] +
            array_column($rowPropostas, 'codigo', 'codigo');

        $data->propostas = form_dropdown('', $propostas, $data->codigo_proposta);

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomContratos', $this->input->post());

        $this->contratos->setValidationLabel('codigo', 'Cód. Contrato');
        $this->contratos->setValidationLabel('codigo_proposta', 'Proposta');
        $this->contratos->setValidationLabel('id_cliente', 'Cliente');
        $this->contratos->setValidationLabel('centro_custo', 'Centro de Custo');
        $this->contratos->setValidationLabel('data_vencimento', 'Vencimento Contrato');
        $this->contratos->setValidationLabel('arquivo', 'Anexar Contrato');

        $this->contratos->save($data) or exit(json_encode(['erro' => $this->contratos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->contratos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->contratos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#contratos thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#contratos thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#contratos tbody td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));

        $this->load->library('Calendar');

        $mes_ano = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

        $this->m_pdf->pdf->Output('Mapa de Contratos_' . $mes_ano . '.pdf', 'D');
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
            ->select("a.codigo, (CASE a.status_ativo WHEN 1 THEN 'Ativo' WHEN 0 THEN 'Inativo' END) AS status", false)
            ->select('c.nome AS nome_cliente, c.contato_principal, c.telefone_principal')
            ->select(["DATE_FORMAT(a.data_vencimento, '%d/%m/%Y') AS data_vencimento"], false)
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = b.id_cliente')
            ->where('a.id_empresa', $this->session->userdata('empresa'))
            ->where('c.id_setor', $this->input->get('setor'))
            ->order_by('a.codigo', 'asc')
            ->get('icom_contratos a')
            ->result();

        $this->load->library('Calendar');

        $data['mes_ano'] = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

        $data['is_pdf'] = $isPdf === true;

        if ($data['is_pdf']) {
            return $this->load->view('icom/pdf_contratos', $data, true);
        }

        $this->load->view('icom/relatorio_contratos', $data);
    }

}
