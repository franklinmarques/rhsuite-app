<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('ei_alocacao_model', 'alocacao');
        $this->load->model('ei_alocados_model', 'alocados');
        $this->load->model('ei_alocados_horarios_model', 'alocados_horarios');
        $this->load->model('ei_alocados_totalizacao_model', 'alocados_totalizacao');
        $this->load->model('ei_apontamonto_model', 'apontamento');
        $this->load->model('ei_matriculados_model', 'matriculados');
        $this->load->model('ei_matriculados_turmas_model', 'matriculados_turmas');
    }

    //==========================================================================
    public function index()
    {
        $where = [
            'empresa' => $this->session->userdata('empresa'),
            'ano' => date('Y'),
            'semestre' => (date('n') / 6)
        ];

        $data = [
            'deptos' => $this->alocacao->getDepartamentos($where),
            'diretorias' => $this->alocacao->getDiretorias(),
            'supervisores' => $this->alocacao->getSupervisores(),
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

        $data['depto_atual'] = count($data['deptos']) > 0 ? '' : 'Educação Inclusiva';

        $data['semestre'] = array_slice(array_values($data['meses']), intval(date('n')) > 6 ? 6 : 0, 7);
        if (!isset($data['semestre'][6])) {
            $data['semestre'][6] = 'Jul';
        }

        $this->load->view('apontamento', $data);
    }

    //==========================================================================
    public function atualizarFiltro()
    {
        $diretorias = ['' => 'Todas'] + $this->alocacao->getDiretorias($this->input->post('depto'));
        $supervisores = ['' => 'Todos'] + $this->alocacao->getSupervisores($this->input->post('diretoria'));

        $data = [
            'diretoria' => form_dropdown('', $diretorias, $this->input->post('diretoria')),
            'supervisor' => form_dropdown('', $supervisores, $this->input->post('supervisor')),
        ];

        return json_encode($data);
    }

    //==========================================================================
    public function prepararAlocacaoSemestre()
    {
        $where = $this->input->post();
        unset($where['mes']);

        $totalOS = $this->db
            ->select('a.id, a.nome')
            ->join('ei_contratos b', 'b.id = a.id_contrato')
            ->join('ei_diretorias c', 'c.id = b.id_cliente')
            ->where('c.id', $where['diretoria'])
            ->where('c.depto', $where['depto'])
            ->where('a.ano', $where['ano'])
            ->where('a.semestre', $where['semestre'])
            ->get('ei_ordem_servico a')
            ->num_rows();

        if (empty($totalOS)) {
            exit(json_encode(['erro' => 'Nenhuma Ordem de Serviço disponível para alocação.']));
        }

        $alocacao = $this->alocacao->where($where)->find();

        $os = $this->db
            ->select('a.id, a.nome')
            ->join('ei_contratos b', 'b.id = a.id_contrato')
            ->join('ei_diretorias c', 'c.id = b.id_cliente')
            ->join('ei_escolas d', 'd.id_diretoria = c.id')
            ->join('ei_supervisores e', 'e.id_escola = d.id')
            ->join('ei_coordenacao f', 'f.id = e.id_coordenacao AND f.ano = a.ano AND f.semestre = a.semestre')
            ->join('ei_ordem_servico_escolas g', 'g.id_ordem_servico = a.id AND g.id_escola = d.id', 'left')
            ->join('ei_ordem_servico_profissionais h', 'h.id_ordem_servico_escola = g.id', 'left')
            ->join('ei_ordem_servico_horarios i', 'i.id_os_profissional = h.id', 'left')
            ->join('ei_alocados_horarios j', 'j.id_os_horario = i.id', 'left')
            ->join('ei_alocados k', 'k.id = j.id_alocado', 'left')
            ->join('ei_alocacao_escolas l', 'l.id = k.id_alocacao_escola', 'left')
            ->join('ei_alocacao m', "m.id = l.id_alocacao AND m.id = '" . ($alocacao->id ?? null) . "'", 'left')
            ->where('c.id', $where['diretoria'])
            ->where('c.depto', $where['depto'])
            ->where('a.ano', $where['ano'])
            ->where('a.semestre', $where['semestre'])
            ->where('j.id', null)
            ->group_by('a.id')
            ->get('ei_ordem_servico a')
            ->num_rows();

        $ordem_servico = ['' => 'Todas'] + array_column($os, 'nome', 'id');

        $escolas = [];

        $data = [
            'ordem_servico' => form_dropdown('', $ordem_servico, ''),
            'escolas' => form_multiselect('', $escolas, '')
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function alocarSemestre()
    {

    }

    //==========================================================================
    public function atualizarAlocacaoSemestre()
    {

    }

    //==========================================================================
    public function desalocarSemestre()
    {
        $data = $this->input->post();

        $alocacao = $this->alocacao
            ->select('id')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->where('depto', $data['depto'])
            ->where('id_diretoria', $data['diretoria'])
            ->where('id_supervisor', $data['supervisor'])
            ->where('ano', $data['ano'])
            ->where('semestre', $data['semestre'])
            ->get('ei_alocacao')
            ->result();

        if (empty($alocacao)) {
            exit(json_encode(['erro' => 'Este semestre já está vazio.']));
        }

        if ($data['possui_mapa_visitacao'] === '2') {
            $this->db->where_in('id_alocacao', array_column($alocacao, 'id'));
            $status = $this->db->delete('ei_mapa_unidades');
        } elseif ($data['possui_mapa_visitacao'] === '1') {
            $this->db->where_in('id', array_column($alocacao, 'id'));
            $status = $this->db->delete('ei_alocacao');
        } else {
            $this->db->where_in('id_alocacao', array_column($alocacao, 'id'));
            $status = $this->db->delete(['ei_alocacao_escolas', 'ei_faturamento', 'ei_faturamento_consolidado', 'ei_pagamento_prestador']);
        }

        echo json_encode(['status' => $status !== false]);
    }

    //==========================================================================
    public function listarEventos()
    {

    }

    //==========================================================================
    public function listarFaturamentos()
    {

    }

    //==========================================================================
    public function listarControleMateriais()
    {

    }

    //==========================================================================
    public function listarMapaVisitacao()
    {

    }

    //==========================================================================
    public function listarDiasLetivos()
    {

    }

    //==========================================================================
    public function listarBancoHoras()
    {

    }

    //==========================================================================
    public function editarEventosDia()
    {

    }

    //==========================================================================
    public function salvarEventosDia()
    {

    }

    //==========================================================================
    public function editarFuncionarioAlocado()
    {

    }

    //==========================================================================
    public function salvarFuncionarioAlocado()
    {

    }

    //==========================================================================
    public function editarAlunoMatriculado()
    {

    }

    //==========================================================================
    public function salvarAlunoMatriculado()
    {

    }

    //==========================================================================
    public function editarEvento()
    {

    }

    //==========================================================================
    public function salvarEvento()
    {

    }

    //==========================================================================
    public function fecharMes()
    {

    }

    //==========================================================================
    public function totalizarMes()
    {

    }

    //==========================================================================
    public function salvarMes()
    {

    }

    //==========================================================================
    public function editarFaturamentoConsolidado()
    {

    }

    //==========================================================================
    public function salvarFaturamentoConsolidado()
    {

    }

    //==========================================================================
    public function recalcularDatasInicio()
    {

    }

    //==========================================================================
    public function recalcularDatasTermino()
    {

    }

    //==========================================================================
    public function desalocarTiposDados()
    {

    }

    //==========================================================================
    public function salvarTiposDados()
    {

    }

    //==========================================================================
    public function editarDataInicioSemestre()
    {

    }

    //==========================================================================
    public function salvarDataInicioSemestre()
    {

    }

    //==========================================================================
    public function editarDataTerminoSemestre()
    {

    }

    //==========================================================================
    public function salvarDataTerminoSemestre()
    {

    }

    //==========================================================================
    public function editarHorarioEntradaSaida()
    {

    }

    //==========================================================================
    public function salvarHorarioEntradaSaida()
    {

    }

    //==========================================================================
    public function editarColaboradorSubstituto()
    {

    }

    //==========================================================================
    public function salvarColaboradorSubstituto()
    {

    }

    //==========================================================================
    public function editarDescontoMensal()
    {

    }

    //==========================================================================
    public function salvarDescontoMensal()
    {

    }

    //==========================================================================
    public function editarFaturamento()
    {

    }

    //==========================================================================
    public function salvarFaturamento()
    {

    }

    //==========================================================================
    public function editarPagamentoPrestador()
    {

    }

    //==========================================================================
    public function salvarPagamentoPrestador()
    {

    }

    //==========================================================================
    public function editarAluno()
    {

    }

    //==========================================================================
    public function salvarAluno()
    {

    }

    //==========================================================================
    public function gerenciarVisitas()
    {

    }

    //==========================================================================
    public function editarVisita()
    {

    }

    //==========================================================================
    public function salvarVisita()
    {

    }

    //==========================================================================
    public function prepararNovoBancoHoras()
    {

    }

    //==========================================================================
    public function editarBancoHoras()
    {

    }

    //==========================================================================
    public function salvarBancoHoras()
    {

    }

    //==========================================================================
    public function relatorioBancoHoras()
    {

    }

}
