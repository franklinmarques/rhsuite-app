<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Postos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_postos_model', 'postos');
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');
        $usuario = $this->input->post('id_usuario');

        $data = $this->carregarEstrutura($depto, $area, $setor, $usuario, true);

        echo json_encode($data);
    }

    //==========================================================================
    public function montarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');
        $usuario = $this->input->post('id_usuario');

        $data = $this->carregarEstrutura($depto, $area, $setor, $usuario);

        echo json_encode($data);
    }

    //==========================================================================
    public function editarColaboradorAlocado()
    {
        $data = $this->db
            ->select('a.*', false)
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->where('b.empresa', $this->session->userdata('empresa'))
            ->where('b.id_setor', $this->input->post('id_setor'))
            ->where('a.id_usuario', $this->input->post('id_usuario'))
            ->get('icom_postos a')->row();

        if (empty($data)) {
            $data = $this->db
                ->select('id_funcao, matricula', false)
                ->select("(CASE tipo_vinculo WHEN 1 THEN 'CLT' WHEN 2 THEN 'MEI' END) AS categoria", false)
                ->where('empresa', $this->session->userdata('empresa'))
                ->where('id_setor', $this->input->post('id_setor'))
                ->where('id', $this->input->post('id_usuario'))
                ->get('usuarios')->row();
        }

        if (empty($data)) {
            exit(json_encode(['erro' => 'Colaborador alocado não encontrado']));
        }

        if (!empty($data->valor_hora_mei)) {
            $data->valor_hora_mei = number_format($data->valor_hora_mei, 2, ',', '.');
        }
        if (!empty($data->valor_mes_clt)) {
            $data->valor_mes_clt = number_format($data->valor_mes_clt, 2, ',', '.');
        }

        $this->load->helper('time');

        if (!empty($data->qtde_horas_mei)) {
            $data->qtde_horas_mei = timeSimpleFormat($data->qtde_horas_mei);
        }
        if (!empty($data->qtde_horas_dia_mei)) {
            $data->qtde_horas_dia_mei = timeSimpleFormat($data->qtde_horas_dia_mei);
        }
        if (!empty($data->qtde_meses_clt)) {
            $data->qtde_meses_clt = timeSimpleFormat($data->qtde_meses_clt);
        }
        if (!empty($data->qtde_horas_dia_clt)) {
            $data->qtde_horas_dia_clt = timeSimpleFormat($data->qtde_horas_dia_clt);
        }

        if (!empty($data->horario_entrada)) {
            $data->horario_entrada = timeSimpleFormat($data->horario_entrada);
        }
        if (!empty($data->horario_intervalo)) {
            $data->horario_intervalo = timeSimpleFormat($data->horario_intervalo);
        }
        if (!empty($data->horario_retorno)) {
            $data->horario_retorno = timeSimpleFormat($data->horario_retorno);
        }
        if (!empty($data->horario_saida)) {
            $data->horario_saida = timeSimpleFormat($data->horario_saida);
        }

        echo json_encode($data);
    }

    //==========================================================================
    private function carregarEstrutura($depto = 0, $area = 0, $setor = 0, $usuario = 0, $todos = false)
    {
        $rowDeptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->where('nome', 'ICOM')
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $arrDeptos = array_column($rowDeptos, 'nome', 'id');

        $deptos = count($arrDeptos) === 1 ? $arrDeptos : ['' => ($todos ? 'Todos' : 'selecione...')] + $arrDeptos;

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

        $rowUsuarios = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_setores b', 'b.id = a.id_setor OR b.nome = a.setor')
            ->where('a.empresa', $this->session->userdata('empresa'))
            ->where('b.id', $setor)
            ->group_by('a.id')
            ->order_by('a.nome', 'asc')
            ->get('usuarios a')
            ->result();

        $usuarios = ['' => ($todos ? 'Todos' : 'selecione...')] + array_column($rowUsuarios, 'nome', 'id');

        $rowFuncoes = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_cargos b', 'b.id = a.id_cargo')
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->order_by('a.nome', 'asc')
            ->get('empresa_funcoes a')
            ->result();

        $funcoes = ['' => ($todos ? 'Todas' : 'selecione...')] + array_column($rowFuncoes, 'nome', 'id');

        $data = [
            'deptos' => form_dropdown('id_depto', $deptos, $depto, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'setores' => form_dropdown('id_setor', $setores, $setor, 'class="form-control input-sm"'),
            'usuarios' => form_dropdown('id_cliente', $usuarios, $usuario, 'class="form-control input-sm"'),
            'funcoes' => form_dropdown('id_funcao', $funcoes, '', 'class="form-control input-sm"')
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
        $data = $this->postos->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->postos->errors()]));
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

        $data = $this->entities->create('icomPostos', $this->input->post());

        $this->postos->setValidationRule('id_depto', 'required|is_natural_no_zero|max_length[11]');
        $this->postos->setValidationRule('id_area', 'required|is_natural_no_zero|max_length[11]');

        $this->postos->setValidationLabel('id_depto', 'Departamento');
        $this->postos->setValidationLabel('id_area', 'Área');
        $this->postos->setValidationLabel('id_setor', 'Setor');
        $this->postos->setValidationLabel('id_usuario', 'Colaborador(a)');
        $this->postos->setValidationLabel('id_funcao', 'Função');
        $this->postos->setValidationLabel('matricula', 'Matrícula');
        $this->postos->setValidationLabel('categoria', 'CLT/MEI');
        $this->postos->setValidationLabel('valor_hora_mei', 'Valor Hora Colaborador');
        $this->postos->setValidationLabel('valor_mes_clt', 'Valor Remuneração Mensal');
        $this->postos->setValidationLabel('qtde_horas_mei', 'Qtde. Horas/Mês MEI');
        $this->postos->setValidationLabel('qtde_horas_dia_mei', 'Qtde. Horas/Dia MEI');
        $this->postos->setValidationLabel('qtde_meses_clt', 'Qtde. Horas/Mês CLT');
        $this->postos->setValidationLabel('qtde_horas_dia_clt', 'Qtde. Horas/Dia CLT');
        $this->postos->setValidationLabel('horario_entrada', 'Horário Entrada');
        $this->postos->setValidationLabel('horario_intervalo', 'Horário Saída Intervalo');
        $this->postos->setValidationLabel('horario_retorno', 'Horário Entrada Intervalo');
        $this->postos->setValidationLabel('horario_saida', 'Horário Saída');

        $this->postos->validate($data) or exit(json_encode(['erro' => $this->postos->errors()]));

        unset($data->id_depto, $data->id_area);

        $this->postos->skipValidation();

        $this->postos->save($data) or exit(json_encode(['erro' => $this->postos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->postos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->postos->errors()]));

        echo json_encode(['status' => true]);
    }

}
