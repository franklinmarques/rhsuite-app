<?php

use function foo\func;

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{

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

        $nivelPerformance = [];
        foreach ($this->alocados::nivelPerformance() as $k => $nivel) {
            $nivelPerformance[] = '<strong>' . $k . '</strong> - ' . $nivel;
        }

        $data = [
            'empresa' => $empresa,
            'tipo_evento' => ['' => 'selecione...'] + $this->apontamento::tipoEvento(),
            'nivel_performance' => implode('; ', $nivelPerformance),
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
            ->select('a.id AS id_usuario, a.nome AS nome_usuario, a.id_funcao, a.matricula')
            ->select("(CASE a.tipo_vinculo WHEN 1 THEN 'CLT' WHEN 2 THEN 'MEI' END) AS categoria", false)
            ->select('b.valor_hora_mei, b.valor_mes_clt')
            ->select('b.qtde_horas_mei, b.qtde_horas_dia_mei, b.qtde_meses_clt, b.qtde_horas_dia_clt')
            ->select('b.horario_entrada, b.horario_intervalo, b.horario_retorno, b.horario_saida')
            ->join('icom_postos b', 'b.id_usuario = a.id AND b.id_setor = a.id_setor', 'left')
            ->where('a.empresa', $this->session->userdata('empresa'))
            ->where('a.tipo', 'funcionario')
            ->where('a.id_depto', $data->id_depto)
            ->where('a.id_area', $data->id_area)
            ->where('a.id_setor', $data->id_setor)
            ->where('a.status', 1)
            ->where_in('a.tipo_vinculo', [1, 2])
            ->order_by('a.nome', 'asc')
            ->get('usuarios a')
            ->result_array();

        $this->db->insert_batch('icom_alocados', $dataAlocados);

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function desalocarMes()
    {
        $alocacao = $this->alocacao->where($this->input->post())->find();

        if (empty($alocacao)) {
            exit(json_encode(['erro' => 'Mês não encontrado ou desalocado recentemente.']));
        }

        $this->alocacao->delete($alocacao->id) or exit(json_encode(['erro' => $this->alocacao->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function prepararNovoAlocado()
    {
        $alocacao = $this->alocacao->where($this->input->post())->find();
        if (empty($alocacao)) {
            exit(json_encode(['erro' => 'Mês não alocado']));
        }

        $usuarios = $this->db
            ->select('a.id, a.nome')
            ->join('icom_alocados b', "b.id_usuario = a.id AND b.id_alocacao = '{$alocacao->id}'", 'left')
            ->where('a.empresa', $this->session->userdata('empresa'))
            ->where('a.tipo', 'funcionario')
            ->where('a.id_depto', $alocacao->id_depto)
            ->where('a.id_area', $alocacao->id_area)
            ->where('a.id_setor', $alocacao->id_setor)
            ->where('a.status', 1)
            ->where_in('a.tipo_vinculo', [1, 2])
            ->where('b.id', null)
            ->group_by('a.id')
            ->order_by('a.nome', 'asc')
            ->get('usuarios a')
            ->result();

        if (empty($usuarios)) {
            exit(json_encode(['erro' => 'Nenhum colaborador encontrado.']));
        }

        $data = [
            'id_alocacao' => $alocacao->id,
            'id_usuario' => form_multiselect('', array_column($usuarios, 'nome', 'id'))
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function salvarNovoAlocado()
    {
        $idAlocacao = $this->input->post('id_alocacao');
        $idUsuario = $this->input->post('id_usuario');
        if (is_array($idUsuario) == false) {
            $idUsuario = [$idUsuario];
        }

        $data = $this->db
            ->select("'{$idAlocacao}' AS id_alocacao", false)
            ->select('a.id AS id_usuario, a.nome AS nome_usuario, a.id_funcao, a.matricula')
            ->select("(CASE a.tipo_vinculo WHEN 1 THEN 'CLT' WHEN 2 THEN 'MEI' END) AS categoria", false)
            ->select('b.valor_hora_mei, b.valor_mes_clt')
            ->select('b.qtde_horas_mei, b.qtde_horas_dia_mei, b.qtde_meses_clt, b.qtde_horas_dia_clt')
            ->select('b.horario_entrada, b.horario_intervalo, b.horario_retorno, b.horario_saida')
            ->join('icom_postos b', 'b.id_usuario = a.id AND b.id_setor = a.id_setor', 'left')
            ->join('icom_alocados c', "c.id_usuario = a.id AND c.id_alocacao = '{$idAlocacao}'", 'left')
            ->where('a.empresa', $this->session->userdata('empresa'))
            ->where('a.tipo', 'funcionario')
            ->where_in('a.id', $idUsuario)
            ->where('a.status', 1)
            ->where_in('a.tipo_vinculo', [1, 2])
            ->group_by('a.id')
            ->order_by('a.nome', 'asc')
            ->get('usuarios a')
            ->result_array();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Nenhum colaborador selecionado.']));
        }

        $this->alocados->skipValidation();

        $this->alocados->insertBatch($data) or exit(json_encode(['erro' => $this->alocados->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluirAlocado()
    {
        $this->alocados->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->alocados->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function listarEventos()
    {
        parse_str($this->input->post('busca'), $busca);

        $query = $this->db
            ->select('a.id, a.nome_usuario')
            ->select("CONCAT(TIME_FORMAT(a.horario_entrada, '%H:%i'), ' - ', TIME_FORMAT(a.horario_saida, '%H:%i')) AS horario", false)
            ->select('a.categoria')
            ->select('c.banco_horas_icom AS banco_horas', false)
            ->join('icom_alocacao b', 'b.id = a.id_alocacao')
            ->join('usuarios c', 'c.id = a.id_usuario')
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
            ->where('MONTH(data)', $busca['mes'])
            ->where('YEAR(data)', $busca['ano'])
            ->group_by(['id_alocado', 'data'])
            ->findAll();

        $eventos = [];

        foreach ($apontamentos as $apontamento) {
            $eventos[$apontamento->id_alocado][$apontamento->dia] = $apontamento;
        }

        $data = [];

        $dias = range(1, 31);

        $this->load->helper('time');

        foreach ($output->data as $row) {
            $rows = [
                '<button type="button" class="btn btn-danger btn-xs" onclick="delete_alocado(' . $row->id . ');" title="Desalocar colaborador(a)"><i class="glyphicon glyphicon-trash"></i></button>',
                $row->nome_usuario,
                $row->horario,
                $row->categoria,
                timeSimpleFormat($row->banco_horas)
            ];

            foreach ($dias as $dia) {
                $rows[] = $eventos[$row->id][$dia] ?? '';
            }
            $rows[] = $row->id;

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
    public function listarTotalizacoes()
    {
        parse_str($this->input->post('busca'), $busca);

        $output = $this->montarTotalizacao($busca);

        echo json_encode($output);
    }

    //==========================================================================
    private function montarTotalizacao($busca)
    {
        $query = $this->db
            ->select('a.nome_usuario')
            ->select(["TIME_FORMAT(a.horario_entrada, '%H:%i') AS horario_entrada"], false)
            ->select(["TIME_FORMAT(a.horario_saida, '%H:%i') AS horario_saida"], false)
            ->select(['c.banco_horas_icom AS banco_horas, a.id'], false)
            ->select(['SUM(TIME_TO_SEC(IF(d.saldo_banco_horas > 0, d.saldo_banco_horas, NULL))) AS saldo_positivo'], false)
            ->select(['SUM(TIME_TO_SEC(IF(d.saldo_banco_horas < 0, d.saldo_banco_horas, NULL))) AS saldo_negativo'], false)
            ->select(['SUM(TIME_TO_SEC(d.saldo_banco_horas)) AS saldo_banco_horas'], false)
            ->select(['SUM(TIME_TO_SEC(d.desconto_folha)) AS desconto_folha'], false)
            ->select(['SUM(TIME_TO_SEC(d.hora_extra)) AS hora_extra'], false)
            ->join('icom_alocacao b', 'b.id = a.id_alocacao')
            ->join('usuarios c', 'c.id = a.id_usuario')
            ->join('icom_apontamento d', 'd.id_alocado = a.id', 'left')
            ->where('b.id_empresa', $busca['id_empresa'])
            ->where('b.id_depto', $busca['id_depto'])
            ->where('b.id_area', $busca['id_area'])
            ->where('b.id_setor', $busca['id_setor'])
            ->where('b.mes', $busca['mes'])
            ->where('b.ano', $busca['ano'])
            ->group_by('a.id_usuario')
            ->order_by('a.nome_usuario', 'asc')
            ->get('icom_alocados a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = [];

        $this->load->helper('time');

        foreach ($output->data as $row) {
            $data[] = [
                $row->nome_usuario,
                $row->horario_entrada,
                $row->horario_saida,
                timeSimpleFormat($row->banco_horas),
                secToTime($row->saldo_positivo, false),
                secToTime($row->saldo_negativo, false),
                secToTime($row->saldo_banco_horas, false),
                secToTime($row->desconto_folha, false),
                secToTime($row->hora_extra, false)
            ];
        }

        $output->data = $data;

        return $output;
    }

    //==========================================================================
    public function listarAvaliacoesPerformance()
    {
        parse_str($this->input->post('busca'), $busca);

        $output = $this->montarAvaliacaoPerformance($busca);

        echo json_encode($output);
    }

    //==========================================================================
    private function montarAvaliacaoPerformance($busca)
    {
        $query = $this->db
            ->select('a.nome_usuario, a.comprometimento, a.pontualidade')
            ->select('a.script, a.simpatia, a.empatia, a.postura, a.ferramenta')
            ->select('a.tradutorio, a.linguistico, a.neutralidade, a.discricao, a.fidelidade')
            ->join('icom_alocacao b', 'b.id = a.id_alocacao')
            ->join('usuarios c', 'c.id = a.id_usuario')
            ->where('b.id_empresa', $busca['id_empresa'])
            ->where('b.id_depto', $busca['id_depto'])
            ->where('b.id_area', $busca['id_area'])
            ->where('b.id_setor', $busca['id_setor'])
            ->where('b.mes', $busca['mes'])
            ->where('b.ano', $busca['ano'])
            ->order_by('a.nome_usuario', 'asc')
            ->get('icom_alocados a');

        $this->load->library('dataTables');
        $output = $this->datatables->generate($query);

        $data = [];
        $mediaPerformance = [];

        foreach ($output->data as $row) {
            $avaliacoesPerformance = [
                $row->comprometimento,
                $row->pontualidade,
                $row->script,
                $row->simpatia,
                $row->empatia,
                $row->postura,
                $row->ferramenta,
                $row->tradutorio,
                $row->linguistico,
                $row->neutralidade,
                $row->discricao,
                $row->fidelidade
            ];

            $mediaPerformance[0][] = $row->comprometimento;
            $mediaPerformance[1][] = $row->pontualidade;
            $mediaPerformance[2][] = $row->script;
            $mediaPerformance[3][] = $row->simpatia;
            $mediaPerformance[4][] = $row->empatia;
            $mediaPerformance[5][] = $row->postura;
            $mediaPerformance[6][] = $row->ferramenta;
            $mediaPerformance[7][] = $row->tradutorio;
            $mediaPerformance[8][] = $row->linguistico;
            $mediaPerformance[9][] = $row->neutralidade;
            $mediaPerformance[10][] = $row->discricao;
            $mediaPerformance[11][] = $row->fidelidade;

            $media = round(array_sum($avaliacoesPerformance) / max(count(array_filter($avaliacoesPerformance)), 1), 2);
            $mediaPerformance[12][] = $media;

            $data[] = array_merge([$row->nome_usuario], $avaliacoesPerformance, [$media > 0 ? str_replace('.', ',', $media) : null]);
        }

        $output->data = $data;

        $output->media = array_map(function ($value) {
            return str_replace('.', ',', round(array_sum($value) / max(count(array_filter($value)), 1), 2));
        }, $mediaPerformance);


        $output->abaixo_media = array_map(function ($value2) {
            $value2 = array_filter($value2);
            $total2 = max(count($value2), 1);
            $media2 = array_sum($value2) / $total2;

            $abaixoMedia = array_filter($value2, function ($v2) use ($media2) {
                return $v2 <= $media2;
            });

            return str_replace('.', ',', round(count($abaixoMedia) / $total2 * 100, 2));
        }, $mediaPerformance);


        $output->acima_media = array_map(function ($value3) {
            $value3 = array_filter($value3);
            $total3 = max(count($value3), 1);
            $media3 = array_sum($value3) / $total3;

            $acimaMedia = array_filter($value3, function ($v3) use ($media3) {
                return $v3 > $media3;
            });

            return str_replace('.', ',', round(count($acimaMedia) / $total3 * 100, 2));
        }, $mediaPerformance);

        return $output;
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

        if (empty($alocado)) {
            exit(json_encode(['erro' => $this->alocados->errors()]));
        }

        $data = $this->input->post('data');

        $evento = $this->apontamento
            ->where('id_alocado', $alocado->id)
            ->where('data', $data)
            ->find();

        if (($msgErro2 = $this->apontamento->errors())) {
            exit(json_encode(['erro' => $msgErro2]));
        }

        if ($evento) {
            $this->load->helper('time');

            $evento->horario_entrada = timeSimpleFormat($evento->horario_entrada);
            $evento->horario_intervalo = timeSimpleFormat($evento->horario_intervalo);
            $evento->horario_retorno = timeSimpleFormat($evento->horario_retorno);
            $evento->horario_saida = timeSimpleFormat($evento->horario_saida);
            $evento->hora_extra = timeSimpleFormat($evento->hora_extra);
        } else {
            $keys = $this->db->list_fields($this->apontamento::table());
            $evento = (object)array_combine($keys, array_pad([], count($keys), null));

            $evento->id_alocado = $alocado->id;
            $evento->data = $data;
        }

        $evento->colaborador_data = $alocado->nome_usuario . '<br>' . date('d/m/Y', strtotime($data));

        echo json_encode($evento);
    }

    //==========================================================================
    public function salvarEvento()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomApontamento', $this->input->post());

        $this->apontamento->setValidationLabel('tipo_evento', 'Tipo de Evento');
        $this->apontamento->setValidationLabel('horario_entrada', 'Horario Entrada');
        $this->apontamento->setValidationLabel('horario_intervalo', 'Horario Intervalo');
        $this->apontamento->setValidationLabel('horario_retorno', 'Horario Retorno');
        $this->apontamento->setValidationLabel('horario_saida', 'Horario Saída');
        $this->apontamento->setValidationLabel('hora_extra', 'Banco de Horas');
        $this->apontamento->setValidationLabel('observacoes', 'Observações');

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

        $data = new stdClass();

        $arrDeptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->where('nome', 'ICOM')
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $deptos = array_column($arrDeptos, 'nome', 'id');

        if (count($deptos) === 1) {
            $data->deptos = form_dropdown('', $deptos, $depto);
            $arrAreas = $this->db
                ->where('id_departamento', $depto)
                ->order_by('nome', 'asc')
                ->get('empresa_areas')
                ->result();
            $areas = array_column($arrAreas, 'nome', 'id');
            $data->areas = form_dropdown('', ['' => 'selecione...'] + $areas);
        } else {
            $data->deptos = form_dropdown('', ['' => 'selecione...'] + $deptos, $depto);
            $data->areas = form_dropdown('', ['' => 'selecione...']);
        }

        $data->setores = form_dropdown('', ['' => 'selecione...']);
        $data->usuarios = form_dropdown('', ['' => 'selecione...']);

        echo json_encode($data);
    }

    //==========================================================================
    public function editarAvaliacaoPerformance()
    {
        $data = $this->db
            ->select('a.*, b.mes, b.ano', false)
            ->join('icom_alocacao b', 'b.id = a.id_alocacao')
            ->where('a.id', $this->input->post('id_alocado'))
            ->get('icom_alocados a')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Nenhum colaborador alocado encontrado.']));
        }

        $this->load->library('calendar');

        $nomeMes = $this->calendar->get_month_name($data->mes);

        $data->dados = $data->nome_usuario . '<br>' . ucfirst($nomeMes) . '/' . $data->ano;

        echo json_encode($data);
    }

    //==========================================================================
    public function salvarAvaliacaoPerformance()
    {
        $data = $this->input->post();

        $id = $data['id'];
        unset($data['id']);

        foreach ($data as &$row) {
            if (strlen($row) == 0) {
                $row = null;
            }
        }

        $this->db->trans_start();
        $this->db->update('icom_alocados', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() == false) {
            exit(json_encode(['erro' => 'Não foi possível salvar a Avaliação de Performance.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function editarBancoHoras()
    {
        $data = $this->db
            ->select('a.id_usuario, b.banco_horas_icom AS banco_horas, a.nome_usuario')
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->where('a.id', $this->input->post('id_alocado'))
            ->get('icom_alocados a')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Nenhum colaborador alocado encontrado.']));
        }

        $this->load->helper('time');

        if ($data->banco_horas) {
            $data->banco_horas = timeSimpleFormat($data->banco_horas);
        }

        $this->load->library('calendar');

        $nomeMes = $this->calendar->get_month_name(date('m'));

        $data->dados = $data->nome_usuario . '<br>' . date('d') . ' de ' . $nomeMes . ' de ' . date('Y');

        echo json_encode($data);
    }

    //==========================================================================
    public function salvarBancoHoras()
    {
        $idUsuario = $this->input->post('id_usuario');
        if (empty($idUsuario)) {
            exit(json_encode(['erro' => 'Nenhum colaborador alocado encontrado.']));
        }

        $bancoHoras = $this->input->post('banco_horas');
        if (strlen($bancoHoras) > 0) {
            $bancoHoras .= ':00';
        } else {
            $bancoHoras = null;
        }

        $this->db->trans_start();
        $this->db->update('usuarios', ['banco_horas_icom' => $bancoHoras], ['id' => $idUsuario]);
        $this->db->trans_complete();

        if ($this->db->trans_status() == false) {
            exit(json_encode(['erro' => 'Não foi possível salvar o Banco de Horas.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function pdfTotalizacao()
    {
        $data = $this->db
            ->select('id AS id_empresa, foto, foto_descricao')
            ->where('id', $this->session->userdata('empresa'))
            ->get('usuarios')
            ->row_array();

        $busca = $this->input->get();
        $busca['id_empresa'] = $data['id_empresa'];

        $this->load->library('Calendar');
        $mes_ano = ucfirst($this->calendar->get_month_name($busca['mes'])) . '/' . $busca['ano'];
        $data['mes_ano'] = $mes_ano;

        $estruturas = $this->db
            ->select('a.nome AS depto, b.nome AS area, c.nome AS setor')
            ->join('empresa_areas b', 'b.id_departamento = a.id')
            ->join('empresa_setores c', 'c.id_area = b.id')
            ->where('a.id', $busca['id_depto'])
            ->where('b.id', $busca['id_area'])
            ->where('c.id', $busca['id_setor'])
            ->get('empresa_departamentos a')
            ->row();

        $data['depto'] = $estruturas->depto ?? '';
        $data['area'] = $estruturas->area ?? '';
        $data['setor'] = $estruturas->setor ?? '';

        $this->load->library('m_pdf');

        $stylesheet = '#totalizacao thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#totalizacao thead tr, #totalizacao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#totalizacao tbody td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

//        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $data['rows'] = $this->montarTotalizacao($busca);
        $this->m_pdf->pdf->writeHTML($this->load->view('icom/pdf_totalizacao', $data, true));
        unset($data);

        $this->m_pdf->pdf->Output('Totalização_' . $mes_ano . '.pdf', 'D');
    }

    //==========================================================================
    public function pdfAvaliacaoPerformance()
    {
        $data = $this->db
            ->select('id AS id_empresa, foto, foto_descricao')
            ->where('id', $this->session->userdata('empresa'))
            ->get('usuarios')
            ->row_array();

        $busca = $this->input->get();
        $busca['id_empresa'] = $data['id_empresa'];

        $this->load->library('Calendar');
        $mes_ano = ucfirst($this->calendar->get_month_name($busca['mes'])) . '/' . $busca['ano'];
        $data['mes_ano'] = $mes_ano;

        $estruturas = $this->db
            ->select('a.nome AS depto, b.nome AS area, c.nome AS setor')
            ->join('empresa_areas b', 'b.id_departamento = a.id')
            ->join('empresa_setores c', 'c.id_area = b.id')
            ->where('a.id', $busca['id_depto'])
            ->where('b.id', $busca['id_area'])
            ->where('c.id', $busca['id_setor'])
            ->get('empresa_departamentos a')
            ->row();

        $data['depto'] = $estruturas->depto ?? '';
        $data['area'] = $estruturas->area ?? '';
        $data['setor'] = $estruturas->setor ?? '';

        $this->load->library('m_pdf');

        $stylesheet = '#avaliacao_performance thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#avaliacao_performance thead tr, #avaliacao_performance tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#avaliacao_performance tbody td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

//        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $data['rows'] = $this->montarAvaliacaoPerformance($busca);
        $this->m_pdf->pdf->writeHTML($this->load->view('icom/pdf_avaliacao_performance', $data, true));
        unset($data);

        $this->m_pdf->pdf->Output('Avaliação de Performance_' . $mes_ano . '.pdf', 'D');
    }

}
