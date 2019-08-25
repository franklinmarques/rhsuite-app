<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sessoes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_sessoes_model', 'sessoes');
    }

    //==========================================================================
    public function index()
    {
        $this->load->model('icom_clientes_model', 'clientes');
        $this->load->model('icom_produtos_model', 'produtos');

        $data = [
            'clientes' => $this->clientes->findColumn('nome', 'id', 'selecione...'),
            'produtos' => $this->produtos->findColumn('nome', 'id', 'selecione...')
        ];

        $this->load->view('icom/sessoes', $data);
    }

    //==========================================================================
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select(["DATE_FORMAT(data_evento, '%d/%m/%Y') AS data_evento"], false)
            ->select('b.nome AS produto, e.nome AS cliente')
            ->select(["TIME_FORMAT(a.horario_inicio, '%H:%i') AS horario_inicio"], false)
            ->select('a.qtde_horas, a.profissional_alocado')
            ->select(["FORMAT(a.valor_faturamento, 2, 'de_DE') AS valor_faturamento, a.id"], false)
            ->join('icom_produtos b', 'b.id = a.id_produto')
            ->join('icom_contratos c', 'c.codigo = a.codigo_contrato')
            ->join('icom_propostas d', 'd.codigo = c.codigo_proposta')
            ->join('icom_clientes e', 'e.id = d.id_cliente')
            ->where('b.id_empresa', $this->session->userdata('empresa'));
        if ($busca['mes']) {
            $this->db->where('MONTH(a.data_evento)', $busca['mes']);
        }
        if ($busca['ano']) {
            $this->db->where('YEAR(a.data_evento)', $busca['ano']);
        }
        if ($busca['cliente']) {
            $this->db->where('e.id', $busca['cliente']);
        }
        if ($busca['produto']) {
            $this->db->where('b.id', $busca['produto']);
        }
        $query = $this->db->get($this->sessoes::table() . ' a');

        $config = [
            'search' => ['produto', 'cliente', 'profissional_alocado']
        ];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->data_evento,
                $row->produto,
                $row->cliente,
                $row->horario_inicio,
                $row->qtde_horas,
                $row->profissional_alocado,
                $row->valor_faturamento,
                '<button class="btn btn-sm btn-info" onclick="edit_sessao(' . $row->id . ')" title="Editar sessão de atividades"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="excluir_sessao(' . $row->id . ')" title="Excluir sessão de atividades"><i class="glyphicon glyphicon-trash"></i></button>'
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->sessoes->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->sessoes->errors()]));
        };

        $cliente = $this->db
            ->select('c.id')
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = b.id_cliente')
            ->where('a.codigo', $data->codigo_contrato)
            ->get('icom_contratos a')
            ->row();

        $data->id_cliente = $cliente->id ?? '';

        $rowsContratos = $this->db
            ->select('a.codigo')
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = b.id_cliente')
            ->where('c.id', $data->id_cliente)
            ->order_by('a.codigo', 'asc')
            ->get('icom_contratos a')
            ->result();

        $contratos = ['' => 'selecione...'] + array_column($rowsContratos, 'codigo', 'codigo');

        $data->contratos = form_dropdown('', $contratos, $data->codigo_contrato);
        unset($data->codigo_contrato);

        $this->load->helper('time');

        $data->data_evento = date('d/m/Y', strtotime($data->data_evento));
        $data->horario_inicio = timeSimpleFormat($data->horario_inicio);
        $data->horario_termino = timeSimpleFormat($data->horario_termino);
        if (strlen($data->valor_faturamento) > 0) {
            $data->valor_faturamento = number_format($data->valor_faturamento, 2, ',', '.');
        }
        if (strlen($data->valor_desconto) > 0) {
            $data->valor_desconto = number_format($data->valor_desconto, 2, ',', '.');
        }
        if (strlen($data->custo_operacional) > 0) {
            $data->custo_operacional = number_format($data->custo_operacional, 2, ',', '.');
        }
        if (strlen($data->custo_impostos) > 0) {
            $data->custo_impostos = number_format($data->custo_impostos, 2, ',', '.');
        }
        if (strlen($data->valor_pagamento_profissional) > 0) {
            $data->valor_pagamento_profissional = number_format($data->valor_pagamento_profissional, 2, ',', '.');
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function filtrarContratos()
    {
        $rowsContratos = $this->db
            ->select('a.codigo')
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = b.id_cliente')
            ->where('c.id', $this->input->post('id_cliente'))
            ->order_by('a.codigo', 'asc')
            ->get('icom_contratos a')
            ->result();

        $contratos = ['' => 'selecione...'] + array_column($rowsContratos, 'codigo', 'codigo');

        $data = ['contratos' => form_dropdown('', $contratos, $this->input->post('codigo_contrato'))];

        echo json_encode($data);
    }

    //==========================================================================
    public function calcularValorProduto()
    {
        $this->load->model('icom_produtos_model', 'produtos');

        $produto = $this->produtos->select('preco, custo, tipo_cobranca')->find($this->input->post('id_produto'));

        $data = [
            'preco' => $produto->preco ?? '',
            'custo' => $produto->custo ?? '',
            'tipo_cobranca' => $produto->tipo_cobranca ?? ''
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomSessoes', $this->input->post());

        $this->sessoes->setValidationRule('id_cliente', 'required|is_natural_no_zero|max_length[11]');

        $this->sessoes->setValidationLabel('id_cliente', 'Cliente');
        $this->sessoes->setValidationLabel('id_produto', 'Produto');
        $this->sessoes->setValidationLabel('codigo_contrato', 'Contrato');
        $this->sessoes->setValidationLabel('data_evento', 'Data Evento');
        $this->sessoes->setValidationLabel('horario_inicio', 'Horário Início');
        $this->sessoes->setValidationLabel('horario_termino', 'Horário Término');
        $this->sessoes->setValidationLabel('qtde_horas', 'Qtde. Horas');
        $this->sessoes->setValidationLabel('valor_desconto', 'Desconto');
        $this->sessoes->setValidationLabel('valor_faturamento', 'Valor a ser Faturado');
        $this->sessoes->setValidationLabel('valor_pagamento_profissional', 'Valor a ser Pago ao Profissional');
        $this->sessoes->setValidationLabel('custo_operacional', 'Custo Operacional');
        $this->sessoes->setValidationLabel('custo_impostos', 'Impostos');
        $this->sessoes->setValidationLabel('local_evento', 'Local do Evento');
        $this->sessoes->setValidationLabel('profissional_alocado', 'Profissional Alocado');
        $this->sessoes->setValidationLabel('observacoes', 'Observações Sobre o Evento');

        $this->sessoes->validate($data) or exit(json_encode(['erro' => $this->sessoes->errors()]));

        unset($data->id_cliente);

        $this->sessoes->skipValidation();

        $this->sessoes->save($data) or exit(json_encode(['erro' => $this->sessoes->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->sessoes->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->sessoes->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#sessoes thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#sessoes thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#sessoes tbody td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));

        $this->load->library('Calendar');

        $mes_ano = $this->calendar->get_month_name(date('m')) . '/' . date('Y');

        $this->m_pdf->pdf->Output('Gestão Comercial - Sessões de Libras_' . $mes_ano . '.pdf', 'D');
    }

    //==========================================================================
    public function relatorio($isPdf = false)
    {
        $data = $this->db
            ->select('foto, foto_descricao')
            ->where('id', $this->session->userdata('empresa'))
            ->get('usuarios')
            ->row_array();

        $data['rows'] = $this->db
            ->select('nome, contato_principal, telefone_contato_principal, email_contato_principal')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('icom_sessoes')
            ->result();

        $data['data'] = date('d/m/Y');

        $data['is_pdf'] = $isPdf === true;

        if ($data['is_pdf']) {
            return $this->load->view('icom/pdf_sessoes', $data, true);
        }

        $this->load->view('icom/relatorio_sessoes', $data);
    }

}
