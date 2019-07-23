<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_alocacao_model', 'alocacao');
        $this->load->model('icom_apontamento_model', 'apontamento');
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
            'tipo_evento' => ['' => 'selecione...'] + $this->apontamento::tipoEvento(),
            'deptos' => ['' => 'selecione...'] + array_column($deptos, 'nome', 'id'),
            'areas' => ['' => 'selecione...'],
            'setores' => ['' => 'selecione...'],
            'depto_atual' => '',
            'area_atual' => '',
            'setor_atual' => '',
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

        $this->load->view('icom/apontamento', $data);
    }

    //==========================================================================
    public function filtrarAlocacao()
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
            'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'setores' => form_dropdown('id_setor', $setores, $setor, 'class="form-control input-sm"')
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function alocarNovoMes()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomAlocacao', $this->input->post());

        $this->alocacao->save($data) or exit(json_encode(['erro' => $this->alocacao->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function desalocarMes()
    {
        $this->alocacao->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->alocacao->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function listarEventos()
    {
        parse_str($this->input->post('busca'), $busca);

        $alocacao = $this->alocacao->where($busca)->find();

        $dir = $this->input->post('order')[0]['dir'];

        if ($alocacao) {
            $periodos = array_keys($this->apontamento::periodo());
            array_shift($periodos);
            if ($dir === 'desc') {
                $periodos = array_reverse($periodos);
            }
            $periodos = array_combine($periodos, array([], [], []));

            $apontamento = $this->apontamento
                ->select("periodo, COUNT(id) AS total, DATE_FORMAT(data, '%d') AS dia", false)
                ->where('id_alocacao', $alocacao->id)
                ->group_by(['data', 'periodo'])
                ->order_by('periodo', $dir)
                ->findAll();
        } else {
            $periodos = [];
            $apontamento = [];
        }

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

        $output = new stdClass();
        $output->draw = (int)$this->input->post('draw');
        $output->recordsTotal = count($periodos);
        $output->recordsFiltered = $output->recordsTotal;

        $this->load->library('Calendar');
        $dias_semana = $this->calendar->get_day_names('long');
        $semana = array();
        for ($i = 1; $i <= 7; $i++) {
            $semana[$i] = $dias_semana[date('w', mktime(0, 0, 0, $busca['mes'], $i, $busca['ano']))];
        }
        $output->calendar = array(
            'mes' => $busca['mes'],
            'ano' => $busca['ano'],
            'mes_ano' => $this->calendar->get_month_name($busca['mes']) . ' ' . $busca['ano'],
            'qtde_dias' => $this->calendar->get_total_days($busca['mes'], $busca['ano']),
            'semana' => $semana
        );

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function gerenciarEventos()
    {
        parse_str($this->input->post('busca'), $busca);
        $periodo = $this->input->post('periodo');
        $date = date('Y-m-d', mktime(0, 0, 0, $busca['mes'], $this->input->post('dia'), $busca['ano']));
        $dia = date('d/m/Y', strtotime($date));

        $alocacao = $this->alocacao->where($busca)->find();

        if (empty($alocacao)) {
            exit(json_encode(['erro' => 'Nenhuma alocação encontrada ou excluída recentemente.']));
        }

        $apontamentos = $this->apontamento
            ->where('id_alocacao', $alocacao->id)
            ->where('periodo', $periodo)
            ->where('data', $date)
            ->findAll();

        $eventos = ['' => '-- Novo evento --'];

        $this->load->helper('time');

        foreach ($apontamentos as $apontamento) {
            $eventos[$apontamento->id] = 'Das ' . timeSimpleFormat($apontamento->horario_inicio) . 'h às ' . timeSimpleFormat($apontamento->horario_termino) . 'h';
        }

        if ($apontamentos) {
            $data = (array)array_pop($apontamentos);
            $data['data'] = $date;
            $data['data_periodo'] = $dia . ' - ' . $this->apontamento::periodo($data['periodo']);

            $this->load->helper('time');
            $data['horario_inicio'] = timeSimpleFormat($data['horario_inicio']);
            $data['horario_termino'] = timeSimpleFormat($data['horario_termino']);
            $data['total_horas'] = timeSimpleFormat($data['total_horas']);
        } else {
            $listFields = $this->db->list_fields($this->apontamento::table());
            $data = array_combine($listFields, array_pad([], count($listFields), null));
            $data['id_alocacao'] = $alocacao->id;
            $data['data'] = $date;
            $data['periodo'] = $periodo;
            $data['data_periodo'] = $dia . ' - ' . $this->apontamento::periodo($periodo);
        }

        $data['id'] = form_dropdown('', $eventos, $data['id']);

        $rowClientes = $this->db
            ->select('id, nome')
            ->where('id_setor', $busca['id_setor'])
            ->order_by('nome', 'asc')
            ->get('icom_clientes')
            ->result();

        $clientes = ['' => 'selecione...'] + array_column($rowClientes, 'nome', 'id');

        $data['id_cliente'] = form_dropdown('', $clientes, $data['id_cliente']);

        $rowContratos = $this->db
            ->select('a.codigo')
            ->join('icom_propostas b', 'b.codigo = a.codigo_proposta')
            ->join('icom_clientes c', 'c.id = b.id_cliente')
            ->where('c.id_setor', $busca['id_setor'])
            ->order_by('a.codigo', 'asc')
            ->get('icom_contratos a')
            ->result();

        $contratos = ['' => 'selecione...'] + array_column($rowContratos, 'codigo', 'codigo');

        $data['codigo_contrato'] = form_dropdown('', $contratos, $data['codigo_contrato']);

        echo json_encode($data);
    }

    //==========================================================================
    public function editarEvento()
    {
        $data = $this->apontamento->find($this->input->post('id'));

        if (($msgErro = $this->apontamento->errors())) {
            exit(json_encode(['erro' => $msgErro]));
        }

        if ($data) {
            $this->load->helper('time');

            $data->horario_inicio = timeSimpleFormat($data->horario_inicio);
            $data->horario_termino = timeSimpleFormat($data->horario_termino);
            $data->total_horas = timeSimpleFormat($data->total_horas);
        } else {
            $listFields = $this->db->list_fields($this->apontamento::table());
            $data = array_combine($listFields, array_pad([], count($listFields), null));
            unset($data['id_alocacao'], $data['data'], $data['periodo']);
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function salvarEvento()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomApontamento', $this->input->post());

        $this->load->helper('time');

        $totalHoras = timeToSec($data->horario_termino) - timeToSec($data->horario_inicio);
        $data->total_horas = secToTime($totalHoras >= 0 ? $totalHoras : 0);

        $this->apontamento->setValidationLabel('id', 'Lista de eventos');
        $this->apontamento->setValidationLabel('tipo', 'Tipo de Evento');
        $this->apontamento->setValidationLabel('id_cliente', 'Cliente');
        $this->apontamento->setValidationLabel('codigo_contrato', 'Número Contrato');
        $this->apontamento->setValidationLabel('centro_custo', 'Centro de Custo');
        $this->apontamento->setValidationLabel('horario_inicio', 'Horário Início');
        $this->apontamento->setValidationLabel('horario_termino', 'Horário Término');
        $this->apontamento->setValidationLabel('total_horas', 'Total Horas');
        $this->apontamento->setValidationLabel('colaboradores_alocados', 'Colaborador(es) Alocado(s)');
        $this->apontamento->setValidationLabel('telefones_emails', 'Telefone(s)/Email(s)');
        $this->apontamento->setValidationLabel('custo_colaboradores', 'Custo Colaborador(es)');
        $this->apontamento->setValidationLabel('custo_operacional', 'Custo Operacional');
        $this->apontamento->setValidationLabel('impostos', 'Impostos');
        $this->apontamento->setValidationLabel('valor_cobrado', 'Valor Cobrado');
        $this->apontamento->setValidationLabel('receita_liquida', 'Receita Líquida');

        $this->apontamento->save($data) or exit(json_encode(['erro' => $this->apontamento->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluirEvento()
    {
        $this->apontamento->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->apontamento->errors()]));

        echo json_encode(['status' => true]);
    }

}
