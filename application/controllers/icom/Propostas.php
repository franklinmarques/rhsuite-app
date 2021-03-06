<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Propostas extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_propostas_model', 'propostas');
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
            'status' => $this->propostas::status(),
            'deptos' => ['' => 'Todos'] + array_column($deptos, 'nome', 'id'),
            'areas' => ['' => 'Todas'],
            'setores' => ['' => 'Todos'],
            'depto_atual' => '',
            'area_atual' => '',
            'setor_atual' => ''
        ];

        $this->load->view('icom/propostas', $data);
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');
        $cliente = $this->input->post('id_cliente');

        $data = $this->carregarEstrutura($depto, $area, $setor, $cliente, true);

        echo json_encode($data);
    }

    //==========================================================================
    public function montarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');
        $cliente = $this->input->post('id_cliente');

        $data = $this->carregarEstrutura($depto, $area, $setor, $cliente);

        echo json_encode($data);
    }

    //==========================================================================
    private function carregarEstrutura($depto = 0, $area = 0, $setor = 0, $cliente = 0, $todos = false)
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

        $rowClientes = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('icom_clientes')
            ->result();

        $clientes = ['' => ($todos ? 'Todos' : 'selecione...')] + array_column($rowClientes, 'nome', 'id');

        $data = [
            'deptos' => form_dropdown('id_depto', $deptos, $depto, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'setores' => form_dropdown('id_setor', $setores, $setor, 'class="form-control input-sm"'),
            'clientes' => form_dropdown('id_cliente', $clientes, $cliente, 'class="form-control input-sm"')
        ];

        return $data;
    }

    //==========================================================================
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select('a.codigo, a.status, a.descricao, b.nome AS nome_cliente')
            ->select(["DATE_FORMAT(a.data_entrega, '%d/%m/%Y') AS data_entrega"], false)
            ->select(["FORMAT(a.valor, 2, 'de_DE') AS valor"], false)
            ->select(["FORMAT(a.margem_liquida, 2, 'de_DE') AS margem_liquida"], false)
            ->join('icom_clientes b', 'b.id = a.id_cliente')
            ->join('empresa_setores c', 'c.id = a.id_setor')
            ->join('empresa_areas d', 'd.id = c.id_area')
            ->join('empresa_departamentos e', 'e.id = d.id_departamento')
            ->where('b.id_empresa', $this->session->userdata('empresa'));
        if ($busca['id_depto']) {
            $this->db->where('e.id', $busca['id_depto']);
        }
        if ($busca['id_area']) {
            $this->db->where('d.id', $busca['id_area']);
        }
        if ($busca['id_setor']) {
            $this->db->where('c.id', $busca['id_setor']);
        }
        if ($busca['id_cliente']) {
            $this->db->where('b.id', $busca['id_cliente']);
        }
        $query = $this->db->get('icom_propostas a');

        $config = ['search' => ['codigo', 'descricao', 'nome_cliente']];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = array(
                $row->codigo,
                $this->propostas::status($row->status),
                $row->descricao,
                $row->nome_cliente,
                $row->data_entrega,
                $row->valor,
                $row->margem_liquida,
                '<button class="btn btn-sm btn-info" onclick="edit_proposta(' . $row->codigo . ')" title="Editar proposta"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_proposta(' . $row->codigo . ')" title="Excluir proposta"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->propostas->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->propostas->errors()]));
        }

        $data->valor = number_format($data->valor, 2, ',', '.');

        $data->data_entrega = date('d/m/Y', strtotime($data->data_entrega));

        if ($data->custo_produto_servico) {
            $data->custo_produto_servico = number_format($data->custo_produto_servico, 2, ',', '.');
        }

        if ($data->custo_administrativo) {
            $data->custo_administrativo = number_format($data->custo_administrativo, 2, ',', '.');
        }

        if ($data->impostos) {
            $data->impostos = number_format($data->impostos, 2, ',', '.');
        }

        if ($data->margem_liquida) {
            $data->margem_liquida = number_format($data->margem_liquida, 2, ',', '.');
        }

        $idEstrutura = $this->db
            ->select('a.codigo, a.id_setor, c.id_area, d.id_departamento', false)
            ->join('icom_clientes b', 'b.id = a.id_cliente')
            ->join('empresa_setores c', 'c.id = a.id_setor', 'left')
            ->join('empresa_areas d', 'd.id = c.id_area', 'left')
            ->where('a.codigo', $data->codigo)
            ->get('icom_propostas a')
            ->row();

        $estrutura = $this->carregarEstrutura($idEstrutura->id_departamento, $idEstrutura->id_area, $idEstrutura->id_setor, $data->id_cliente);

        $data->deptos = $estrutura['deptos'];
        $data->areas = $estrutura['areas'];
        $data->setores = $estrutura['setores'];
        $data->clientes = $estrutura['clientes'];

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomPropostas', $this->input->post());

        $this->propostas->setValidationRule('id_depto', 'required|is_natural_no_zero|max_length[11]');
        $this->propostas->setValidationRule('id_area', 'required|is_natural_no_zero|max_length[11]');
        $this->propostas->setValidationRule('id_setor', 'required|is_natural_no_zero|max_length[11]');

        $this->propostas->setValidationLabel('codigo', 'Cód. Proposta');
        $this->propostas->setValidationLabel('descricao', 'Descrição Proposta');
        $this->propostas->setValidationLabel('id_depto', 'Departamento');
        $this->propostas->setValidationLabel('id_area', 'Área');
        $this->propostas->setValidationLabel('id_setor', 'Setor');
        $this->propostas->setValidationLabel('id_cliente', 'Cliente');
        $this->propostas->setValidationLabel('tipo', 'Tipo Proposta');
        $this->propostas->setValidationLabel('data_entrega', 'Data Entrega');
        $this->propostas->setValidationLabel('probabilidade_fechamento', 'Probabilidade Fechamento');
        $this->propostas->setValidationLabel('valor', 'Valor');
        $this->propostas->setValidationLabel('detalhes', 'Detalhes Proposta');
        $this->propostas->setValidationLabel('status', 'Status');
        $this->propostas->setValidationLabel('custo_produto_servico', 'Custo Produto/Serviço');
        $this->propostas->setValidationLabel('custo_administrativo', 'Custo Administrativo');
        $this->propostas->setValidationLabel('impostos', 'Impostos');
        $this->propostas->setValidationLabel('margem_liquida', 'Margem Líquida');
        $this->propostas->setValidationLabel('margem_liquida_percentual', 'Margem Líquida Percentual');
        $this->propostas->setValidationLabel('arquivo', 'Anexar Proposta');

        $this->propostas->validate($data) or exit(json_encode(['erro' => $this->propostas->errors()]));

        unset($data->id_depto, $data->id_area);

        $this->propostas->skipValidation();

        $this->propostas->save($data) or exit(json_encode(['erro' => $this->propostas->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->propostas->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->propostas->errors()]));

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
            ->select("a.codigo, (CASE a.status WHEN 'A' THEN 'Aberta' WHEN 'G' THEN 'Ganha' WHEN 'P' THEN 'Perdida' END) AS status", false)
            ->select('b.nome AS nome_cliente, a.descricao')
            ->select('b.contato_principal, b.telefone_contato_principal, b.email_contato_principal')
            ->join('icom_clientes b', 'b.id = a.id_cliente')
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->where('a.id_setor', $this->input->get('setor'))
            ->order_by('a.codigo', 'asc')
            ->get('icom_propostas a')
            ->result();

        $this->load->library('Calendar');

        $data['mes_ano'] = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

        $data['is_pdf'] = $isPdf === true;

        if ($data['is_pdf']) {
            return $this->load->view('icom/pdf_propostas', $data, true);
        }

        $this->load->view('icom/relatorio_propostas', $data);
    }

    //==========================================================================
    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#propostas thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#propostas thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#propostas tbody td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));

        $this->load->library('Calendar');

        $mes_ano = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

        $this->m_pdf->pdf->Output('Relatório de Propostas_' . $mes_ano . '.pdf', 'D');
    }

    //==========================================================================
    public function downloadArquivo()
    {

    }

}
