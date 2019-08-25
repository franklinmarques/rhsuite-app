<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_alocacao_model', 'alocacao');
        $this->load->model('icom_alocados_model', 'alocados');
        $this->load->model('icom_apontamento_model', 'apontamento');
    }

    //==========================================================================
    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $arrDeptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $empresa)
            ->where('nome', 'ICOM')
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $deptos = array_column($arrDeptos, 'nome', 'id');

        if (count($deptos) === 1) {
            $arrAreas = $this->db
                ->select('a.id, a.nome')
                ->join('empresa_departamentos b', 'b.id = a.id_departamento')
                ->where_in('b.id', array_column($arrDeptos, 'id'))
                ->order_by('a.nome', 'asc')
                ->get('empresa_areas a')
                ->result();

            $areas = ['' => 'selecione...'] + array_column($arrAreas, 'nome', 'id');
        } else {
            $areas = ['' => 'selecione...'];
        }

        $data = [
            'empresa' => $empresa,
            'tipo_evento' => ['' => 'selecione...'] + $this->apontamento::tipoEvento(),
            'deptos' => count($deptos) === 1 ? $deptos : ['' => 'selecione...'] + $deptos,
            'areas' => $areas,
            'setores' => ['' => 'selecione...'],
            'depto_atual' => count($deptos) === 1 ? array_keys($deptos)[0] : '',
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

        $data = $this->montarEstrutura($depto, $area, $setor);

        echo json_encode($data);
    }

    //==========================================================================
    private function montarEstrutura($depto = '', $area = '', $setor = '')
    {
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

        return $data;
    }

    //==========================================================================
    public function alocarNovoMes()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomAlocacao', $this->input->post());

        $this->alocacao->save($data) or exit(json_encode(['erro' => $this->alocacao->errors()]));

        $idAlocacao = !empty($data->id) ? $data->id : $this->alocacao::insertID();

        $dataAlocados = $this->db
            ->select("'{$idAlocacao}' AS id_alocacao", false)
            ->select('id AS id_usuario, nome AS nome_usuario')
            ->where('empresa', $this->session->userdata('empresa'))
            ->where('tipo', 'funcionario')
            ->where('id_depto', $data->id_depto)
            ->where('id_area', $data->id_area)
            ->where('id_setor', $data->id_setor)
            ->where('status', 1)
            ->order_by('nome', 'asc')
            ->get('usuarios')
            ->result_array();

        $this->db->insert_batch('icom_alocados', $dataAlocados);

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

        $query = $this->db
            ->select('a.nome_usuario, a.banco_horas, a.id')
            ->join('icom_alocacao b', 'b.id = a.id_alocacao')
            ->where('b.id_empresa', $busca['id_empresa'])
            ->where('b.id_depto', $busca['id_depto'])
            ->where('b.id_area', $busca['id_area'])
            ->where('b.id_setor', $busca['id_setor'])
            ->where('b.mes', $busca['mes'])
            ->where('b.ano', $busca['ano'])
            ->get('icom_alocados a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $apontamentos = $this->apontamento
            ->select("id_alocado, DATE_FORMAT(data, '%e') AS dia, tipo_evento", false)
            ->where_in('id_alocado', array_column($output->data, 'id') + [0])
            ->group_by(['id_alocado', 'data'])
            ->findAll();

        $eventos = [];

        foreach ($apontamentos as $apontamento) {
            $eventos[$apontamento->id_alocado][$apontamento->dia] = $apontamento;
        }

        $data = [];

        $dias = range(1, 31);

        foreach ($output->data as $row) {
            $rows = [$row->nome_usuario, $row->banco_horas];

            foreach ($dias as $dia) {
                $rows[] = $eventos[$row->id][$dia] ?? '';
            }

            $data[] = $rows;
        }

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
        $alocado = $this->alocados->find($this->input->post('id_alocado'));
        $data = $this->input->post('data');

        if (($msgErro = $this->alocados->errors())) {
            exit(json_encode(['erro' => $msgErro]));
        }

        $evento = $this->apontamento
            ->where(['id_alocado' => $alocado->id, 'data' => $data])
            ->find();

        if (($msgErro2 = $this->apontamento->errors())) {
            exit(json_encode(['erro' => $msgErro2]));
        }

        $output = new stdClass();

        if ($evento) {
            $this->load->helper('time');

            $evento->horario_entrada = timeSimpleFormat($evento->horario_entrada);
            $evento->horario_intervalo = timeSimpleFormat($evento->horario_intervalo);
            $evento->horario_retorno = timeSimpleFormat($evento->horario_retorno);
            $evento->horario_saida = timeSimpleFormat($evento->horario_saida);
            $evento->desconto_folha = timeSimpleFormat($evento->desconto_folha);
            $evento->acrescimo_horas = timeSimpleFormat($evento->acrescimo_horas);
            $evento->decrescimo_horas = timeSimpleFormat($evento->decrescimo_horas);
            $output = $evento;
        } else {
            $output->id_alocado = $alocado->id;
            $output->data = $data;
        }

        $output->colaborador_data = $alocado->nome_usuario . '<br>' . date('d/m/Y', strtotime($data));

        echo json_encode($output);
    }

    //==========================================================================
    public function salvarEvento()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomApontamento', $this->input->post());

//        $this->load->helper('time');
//
//        $totalHoras = timeToSec($data->horario_termino) - timeToSec($data->horario_inicio);
//        $data->total_horas = secToTime($totalHoras >= 0 ? $totalHoras : 0);

//        $this->apontamento->setValidationLabel('id', 'Lista de eventos');
        $this->apontamento->setValidationLabel('tipo_evento', 'Tipo de Evento');
        $this->apontamento->setValidationLabel('horario_entrada', 'Horario Entrada');
        $this->apontamento->setValidationLabel('horario_intervalo', 'Horario Intervalo');
        $this->apontamento->setValidationLabel('horario_retorno', 'Horario Retorno');
        $this->apontamento->setValidationLabel('horario_saida', 'Horario Saída');
        $this->apontamento->setValidationLabel('acrescimo_horas', 'Apontamento +');
        $this->apontamento->setValidationLabel('decrescimo_horas', 'Apontamento -');
        $this->apontamento->setValidationLabel('desconto_folha', 'Desconto Folha');
        $this->apontamento->setValidationLabel('observacoes', 'Observações');
//        $this->apontamento->setValidationLabel('id_cliente', 'Cliente');
//        $this->apontamento->setValidationLabel('codigo_contrato', 'Número Contrato');
//        $this->apontamento->setValidationLabel('centro_custo', 'Centro de Custo');
//        $this->apontamento->setValidationLabel('horario_inicio', 'Horário Início');
//        $this->apontamento->setValidationLabel('horario_termino', 'Horário Término');
//        $this->apontamento->setValidationLabel('total_horas', 'Total Horas');
//        $this->apontamento->setValidationLabel('colaboradores_alocados', 'Colaborador(es) Alocado(s)');
//        $this->apontamento->setValidationLabel('telefones_emails', 'Telefone(s)/Email(s)');
//        $this->apontamento->setValidationLabel('custo_colaboradores', 'Custo Colaborador(es)');
//        $this->apontamento->setValidationLabel('custo_operacional', 'Custo Operacional');
//        $this->apontamento->setValidationLabel('impostos', 'Impostos');
//        $this->apontamento->setValidationLabel('valor_cobrado', 'Valor Cobrado');
//        $this->apontamento->setValidationLabel('receita_liquida', 'Receita Líquida');

        $this->apontamento->save($data) or exit(json_encode(['erro' => $this->apontamento->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluirEvento()
    {
        $this->apontamento->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->apontamento->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function editarPosto()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');

        $estrutura = $this->db
            ->select('a.id AS id_setor, a.nome AS nome_setor, b.id AS id_area, c.id AS id_depto')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->join('empresa_departamentos c', 'c.id = b.id_departamento')
            ->where('a.id', $depto)
            ->where('b.id', $area)
            ->where('c.id', $setor)
            ->get('empresa_setores a')
            ->row();

        if ($estrutura) {
            $this->load->model('icom_postos_model', 'postos');

            $data = $this->postos->where('id_setor', $estrutura->id_setor)->find();
            if (empty($data)) {
                exit(json_encode(['erro' => $this->postos->errors()]));
            }
        } else {
            $data = new stdClass();
        }

        $arrDeptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->where('nome', 'ICOM')
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $deptos = array_column($arrDeptos, 'nome', 'id');

        $deptos = count($deptos) === 1 ? $deptos : ['' => 'selecione...'] + $deptos;

        $data->deptos = form_dropdown('', $deptos, $depto);

        $estruturaMontada = $this->montarEstrutura($depto, $area, $setor);

        $data->areas = $estruturaMontada['areas'];
        $data->setores = $estruturaMontada['setores'];

        $nomeSetor = $estrutura->nome_setor ?? '';
        $arrUsuarios = $this->db->select('id, nome')
            ->where('empresa', $this->session->userdata('empresa'))
            ->where("(setor = '{$nomeSetor}' OR id_setor = '{$setor}')")
            ->get('usuarios')
            ->row_array();

        $data->usuarios = form_dropdown('', ['' => 'selecione...'] + array_column($arrUsuarios, 'nome', 'id'), '');

        echo json_encode($data);
    }

}
