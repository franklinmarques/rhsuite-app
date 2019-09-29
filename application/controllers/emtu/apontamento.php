<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('emtu_alocacao_model', 'alocacao');
        $this->load->model('emtu_alocados_model', 'alocados');
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

        if (in_array($this->session->userdata('nivel'), [])) {
        }


        $areas = $this->db
            ->select('id, nome')
            ->where('id_empresa', $empresa)
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $data = [
            'empresa' => $empresa,
            'depto_atual' => '',
            'area_atual' => '',
            'setor_atual' => '',
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

        $query = $this->db
            ->where('id_alocacao', $alocacao->id ?? null)
            ->get('emtu_alocados');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        if ($alocacao) {
            $apontamento = $this->apontamento
                ->select('id_alocado, DAY(data) AS dia, status', false)
                ->where_in('id_alocacao', array_column($output->data, 'id'))
                ->findAll();
        } else {
            $apontamento = [];
        }

        $eventos = [];

        foreach ($apontamento as $dados) {
            $eventos[$dados->id_alocado][$dados->dia] = $dados->status;
        }

        $data = [];

//        $this->load->library('calendar');
//        $dias = range(1, get_total_days($busca['mes'], $busca['ano']));
        $dias = range(1, 31);

        foreach ($output->data as $row) {
            $rows = [
                $row->nome_usuario,
                null
            ];

            for ($i = 1; $i <= 31; $i++) {
                $rows[] = $evento[$row->id][$i] ?? '';
            }

            $rows[] = $row->id;

            $data[] = $rows;
        }

        $output->data = $data;

        $this->load->library('Calendar');
        $dias_semana = $this->calendar->get_day_names('long');
        $semana = array();
        for ($i = 1; $i <= 7; $i++) {
            $semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
        }
        $output->calendar = [
            'mes' => $busca['mes'],
            'ano' => $busca['ano'],
            'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
            'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
            'semana' => $semana
        ];

        echo json_encode($output);
    }

    //==========================================================================
    public function listarEventosConsolidados()
    {
        parse_str($this->input->post('busca'), $busca);

        $alocacao = $this->alocacao->where($busca)->find();

        $query = $this->db
            ->where('id_alocacao', $alocacao->id ?? null)
            ->get('emtu_alocados');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        if ($alocacao) {
            $apontamento = $this->apontamento
                ->select('id_alocado, DAY(data) AS dia, status', false)
                ->where_in('id_alocacao', array_column($output->data, 'id'))
                ->findAll();
        } else {
            $apontamento = [];
        }

        $eventos = [];

        foreach ($apontamento as $dados) {
            $eventos[$dados->id_alocado][$dados->dia] = $dados->status;
        }

        $data = [];

//        $this->load->library('calendar');
//        $dias = range(1, get_total_days($busca['mes'], $busca['ano']));
        $dias = range(1, 31);

        foreach ($output->data as $row) {
            $rows = [
                $row->nome_usuario,
                null
            ];

            for ($i = 1; $i <= 31; $i++) {
                $rows[] = $evento[$row->id][$i] ?? '';
            }

            $rows[] = $row->id;

            $data[] = $rows;
        }

        $output->data = $data;

        $this->load->library('Calendar');
        $dias_semana = $this->calendar->get_day_names('long');
        $semana = array();
        for ($i = 1; $i <= 7; $i++) {
            $semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
        }
        $output->calendar = [
            'mes' => $busca['mes'],
            'ano' => $busca['ano'],
            'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
            'qtde_dias' => date('t', mktime(0, 0, 0, $busca['mes'], 1, $busca['ano'])),
            'semana' => $semana
        ];

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
