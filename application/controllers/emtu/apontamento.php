<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('emtu_alocacao_model', 'alocacao');
        $this->load->model('emtu_apontamento_model', 'apontamento');
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
            'deptos' => ['' => 'selecione...'] + array_column($deptos, 'nome', 'id'),
            'areas' => ['' => 'selecione...'],
            'setores' => ['' => 'selecione...'],
            'meses' => [
                '01' => 'Janeiro',
                '02' => 'Fevereiro',
                '03' => 'Março',
                '04' => 'Abril',
                '05' => 'Maio',
                '06' => 'Junho',
                '07' => 'Julho',
                '08' => 'Agosto',
                '09' => 'Setembro',
                '10' => 'Outubro',
                '11' => 'Novembro',
                '12' => 'Dezembro'
            ]
        ];

        $this->load->view('emtu/apontamento', $data);
    }

    //==========================================================================
    public function filtrar()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');

        $rowAreas = $this->db
            ->select('id, nome')
            ->where('id_departamento', $depto)
            ->order_by('nome', 'asc')
            ->get('empresa_areas')
            ->result();

        $areas = ['' => 'selecione...'] + array_column($rowAreas, 'nome', 'id');

        $rowSetores = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->where('a.id_area', $area)
            ->where('b.id_departamento', $depto)
            ->order_by('a.nome', 'asc')
            ->get('empresa_setores a')
            ->result();

        $setores = ['' => 'selecione...'] + array_column($rowSetores, 'nome', 'id');

        $data = [
            'areas' => form_dropdown('', $areas, $area),
            'setores' => form_dropdown('', $setores, $setor)
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function alocarMes()
    {
        $data = $this->alocacao->fill($this->input->post());

        $this->alocacao->save($data) or exit(json_encode(['erro' => $this->alocacao->error()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function desalocarMes()
    {
        $this->alocacao->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->alocacao->error()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function listarEventos()
    {
        parse_str($this->input->post('busca'), $busca);

        $alocacao = $this->alocacao->where($busca)->find();

        if ($alocacao) {
            $apontamento = $this->apontamento
                ->select("periodo, COUNT(id) AS total, DATE_FORMAT(data, '%d') AS dia", false)
                ->where('id_alocacao', $alocacao->id)
                ->group_by(['data', 'periodo'])
                ->order_by('periodo', 'asc')
                ->findAll();
        } else {
            $apontamento = null;
        }

        $periodos = [];

        foreach ($apontamento as $dados) {
            $periodos[$dados->periodo][$dados->dia] = $dados->total;
        }

        $data = [];

//        $this->load->library('calendar');
//        $dias = range(1, get_total_days($busca['mes'], $busca['ano']));
        $dias = range(1, 31);

        foreach ($periodos as $periodo => $eventos) {
            $row = [$this->apontamento::periodo($periodo)];

            foreach ($dias as $dia) {
                $row[] = $eventos[$dia] ?? '';
            }

            $data[] = $row;
        }

        $output = stdClass();
        $output->draw = (int)$this->input->post('draw');
        $output->totalRecords = count($periodos);
        $output->totalRecordsFiltered = $output->totalRecords;
        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editarEvento()
    {
        $data = $this->apontamento->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->apontamento->error()]));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function salvarEvento()
    {
        $data = $this->apontamento->fill($this->input->post());

        $this->apontamento->save($data) or exit(json_encode(['erro' => $this->apontamento->error()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluirEvento()
    {
        $this->apontamento->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->apontamento->error()]));

        echo json_encode(['status' => true]);
    }

}
