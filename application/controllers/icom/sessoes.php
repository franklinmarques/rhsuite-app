<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_eventos_model', 'eventos');
    }

    //==========================================================================
    public function index()
    {
        $this->load->view('icom/eventos', ['empresa' => $this->session->userdata('empresa')]);
    }

    //==========================================================================
    public function listar()
    {
        $query = $this->db
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->get($this->eventos::table());

        $config = [
            'select' => ['nome', 'contato_principal', 'telefone_contato_principal', 'email_contato_principal', 'id'],
            'search' => ['nome', 'contato_principal', 'telefone_contato_principal', 'email_contato_principal']
        ];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->contato_principal,
                $row->telefone_contato_principal,
                $row->email_contato_principal,
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
        };

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomClientes', $this->input->post());

        $this->clientes->setValidationLabel('nome', 'Nome Cliente');
        $this->clientes->setValidationLabel('observacoes', 'Observações');
        $this->clientes->setValidationLabel('contato_principal', 'Contato Principal');
        $this->clientes->setValidationLabel('telefone_contato_principal', 'Telefone do Contato Principal');
        $this->clientes->setValidationLabel('email_contato_principal', 'E-mail do Contato Principal');
        $this->clientes->setValidationLabel('cargo_contato_principal', 'Cargo do Contato Principal');
        $this->clientes->setValidationLabel('contato_secundario', 'Contato Secundario');
        $this->clientes->setValidationLabel('telefone_contato_secundario', 'Telefone do Contato Secundario');
        $this->clientes->setValidationLabel('email_contato_secundario', 'E-mail do Contato Secundario');
        $this->clientes->setValidationLabel('cargo_contato_secundario', 'Cargo do Contato Secundario');

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
            ->get('icom_clientes')
            ->result();

        $data['data'] = date('d/m/Y');

        $data['is_pdf'] = $isPdf === true;

        if ($data['is_pdf']) {
            return $this->load->view('icom/pdf_clientes', $data, true);
        }

        $this->load->view('icom/relatorio_clientes', $data);
    }

}
