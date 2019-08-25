<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contratos extends MY_Controller
{

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

        $data = [
            'empresa' => $empresa,
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

        $data = $this->carregarEstrutura($depto, $area, $setor, $cliente, $proposta, true);

        echo json_encode($data);
    }

    //==========================================================================
    public function montarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');
        $cliente = $this->input->post('id_cliente');
        $proposta = $this->input->post('codigo_proposta');

        $data = $this->carregarEstrutura($depto, $area, $setor, $cliente, $proposta);

        echo json_encode($data);
    }

    //==========================================================================
    private function carregarEstrutura($depto = 0, $area = 0, $setor = 0, $cliente = 0, $proposta = 0, $todos = false)
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

        $rowPropostas = $this->db
            ->select('a.codigo')
            ->join('icom_clientes b', 'b.id = a.id_cliente')
            ->join('empresa_setores c', 'c.id = a.id_setor')
            ->join('empresa_areas d', 'd.id = c.id_area')
            ->where('a.id_cliente', $cliente)
            ->where('a.id_setor', $setor)
            ->where('c.id_area', $area)
            ->where('d.id_departamento', $depto)
            ->order_by('a.codigo', 'asc')
            ->get('icom_propostas a')
            ->result();

        $propostas = ['' => ($todos ? 'Todas' : 'selecione...')] + array_column($rowPropostas, 'codigo', 'codigo');

        $data = [
            'deptos' => form_dropdown('id_depto', $deptos, $depto, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'setores' => form_dropdown('id_setor', $setores, $setor, 'class="form-control input-sm"'),
            'clientes' => form_dropdown('id_cliente', $clientes, $cliente, 'class="form-control input-sm"'),
            'propostas' => form_dropdown('codigo_proposta', $propostas, $proposta, 'class="form-control input-sm"')
        ];

        return $data;
    }

    //==========================================================================
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select('a.codigo, a.codigo_proposta, c.nome AS nome_cliente, centro_custo')
            ->select(["DATE_FORMAT(a.data_vencimento, '%d/%m/%Y') AS data_vencimento"], false)
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = b.id_cliente')
            ->join('empresa_setores d', 'd.id = b.id_setor')
            ->join('empresa_areas e', 'e.id = d.id_area')
            ->join('empresa_departamentos f', 'f.id = e.id_departamento')
            ->where('a.id_empresa', $this->session->userdata('empresa'));
        if ($busca['id_depto']) {
            $this->db->where('f.id', $busca['id_depto']);
        }
        if ($busca['id_area']) {
            $this->db->where('e.id', $busca['id_area']);
        }
        if ($busca['id_setor']) {
            $this->db->where('d.id', $busca['id_setor']);
        }
        if ($busca['id_cliente']) {
            $this->db->where('c.id', $busca['id_cliente']);
        }
        if ($busca['codigo_proposta']) {
            $this->db->where('b.codigo', $busca['codigo_proposta']);
        }
        $query = $this->db->get('icom_contratos a');

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

        $idEstrutura = $this->db
            ->select('a.codigo, b.id_cliente, b.id_setor, d.id_area, e.id_departamento', false)
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = b.id_cliente')
            ->join('empresa_setores d', 'd.id = b.id_setor', 'left')
            ->join('empresa_areas e', 'e.id = d.id_area', 'left')
            ->where('a.codigo', $data->codigo)
            ->get('icom_contratos a')
            ->row();

        $estrutura = $this->carregarEstrutura($idEstrutura->id_departamento, $idEstrutura->id_area, $idEstrutura->id_setor, $idEstrutura->id_cliente, $data->codigo_proposta);

        $data->deptos = $estrutura['deptos'];
        $data->areas = $estrutura['areas'];
        $data->setores = $estrutura['setores'];
        $data->clientes = $estrutura['clientes'];
        $data->propostas = $estrutura['propostas'];

        $contratos = $this->contratos->findColumn('arquivo', 'arquivo', 'selecione...');

        $data->contratos = form_dropdown('', $contratos, $data->arquivo);

        $url_arquivo = $this->contratos->getUploadConfig()['arquivo']['upload_path'];

        $data->url_arquivo = base_url(str_replace('./', '', $url_arquivo) . $data->arquivo);

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomContratos', $this->input->post());

        $this->contratos->setValidationRule('id_depto', 'required|is_natural_no_zero|max_length[11]');
        $this->contratos->setValidationRule('id_area', 'required|is_natural_no_zero|max_length[11]');
        $this->contratos->setValidationRule('id_setor', 'required|is_natural_no_zero|max_length[11]');
        $this->contratos->setValidationRule('id_cliente', 'required|is_natural_no_zero|max_length[11]');

        $this->contratos->setValidationLabel('id_depto', 'Departamento');
        $this->contratos->setValidationLabel('id_area', 'Área');
        $this->contratos->setValidationLabel('id_setor', 'Setor');
        $this->contratos->setValidationLabel('id_cliente', 'Cliente');
        $this->contratos->setValidationLabel('codigo', 'Cód. Contrato');
        $this->contratos->setValidationLabel('codigo_proposta', 'Proposta');
        $this->contratos->setValidationLabel('id_cliente', 'Cliente');
        $this->contratos->setValidationLabel('centro_custo', 'Centro de Custo');
        $this->contratos->setValidationLabel('data_vencimento', 'Vencimento Contrato');
        $this->contratos->setValidationLabel('arquivo', 'Anexar Contrato');

        $this->contratos->validate($data) or exit(json_encode(['erro' => $this->contratos->errors()]));

        unset($data->id_depto, $data->id_area, $data->id_setor, $data->id_cliente);

        $this->contratos->skipValidation();

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
    public function excluirArquivo()
    {
        $arquivo = $this->input->post('arquivo');
        $urlArquivo = $this->contratos->getUploadConfig()['arquivo']['upload_path'] . $arquivo;

        if (!is_file($urlArquivo)) {
            exit(json_encode(['erro' => 'Arquivo não encontrado.']));
        }

        $this->db->trans_begin();

        $this->db->update('icom_contratos', ['arquivo' => null], ['arquivo' => $arquivo]);

        if (!unlink($urlArquivo) or !$this->db->trans_status()) {
            $this->db->trans_rollback();
            exit(json_encode(['erro' => 'Não foi possível excluir o arquivo.']));
        }

        $this->db->trans_commit();

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
            ->select('c.nome AS nome_cliente, c.contato_principal, c.telefone_contato_principal')
            ->select(["DATE_FORMAT(a.data_vencimento, '%d/%m/%Y') AS data_vencimento"], false)
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = b.id_cliente')
            ->where('a.id_empresa', $this->session->userdata('empresa'))
            ->where('b.id_setor', $this->input->get('setor'))
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
