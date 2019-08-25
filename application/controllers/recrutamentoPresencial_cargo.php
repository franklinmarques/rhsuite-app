<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class recrutamentoPresencial_cargo extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('requisicoes_pessoal_model', 'rp');
        $this->load->model('requisicoes_pessoal_candidatos_model', 'candidatos');
    }

    //==========================================================================
    public function index()
    {
        $this->load->view('recrutamentoPresencial_candidatos');
    }

    //==========================================================================
    public function gerenciar($idRequisicao = null)
    {
        if (empty($idRequisicao)) {
            redirect(site_url('requisicaoPessoal'));
        }

        $data = $this->rp->find($idRequisicao);

        if (empty($data)) {
            redirect(site_url('requisicaoPessoal'));
        }

        $this->load->view('recrutamentoPresencial_cargo', $data);
    }

    //==========================================================================
    public function listar()
    {
        $query = $this->rp->find();

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->id,
                $row->nome
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function listarCandidatosInternos()
    {
        $query = $this->rp->find();

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->id,
                $row->nome
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function listarCandidatosExternos()
    {
        $query = $this->rp->find();

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = [
                $row->id,
                $row->nome
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

}