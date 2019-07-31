<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Medicoes extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $processos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('dimensionamento_processos')
            ->result();


        $equipes = $this->db
            ->select('a.id, b.nome')
            ->join('dimensionamento_equipes b', 'b.id = a.id_equipe')
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->order_by('b.nome', 'asc')
            ->get('dimensionamento_executores a')
            ->result();


        $colaboradores = $this->db
            ->select('a.id, b.nome')
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->where('b.empresa', $this->session->userdata('empresa'))
            ->order_by('b.nome', 'asc')
            ->get('dimensionamento_executores a')
            ->result();


        $cronoAnalises = $this->db
            ->select('id, nome, base_tempo, unidade_producao')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('dimensionamento_crono_analises')
            ->result();

        $baseTempo = [
            'S' => 'Segundo',
            'I' => 'Minuto',
            'H' => 'Hora',
            'D' => 'Dia',
            'W' => 'Semana',
            'Q' => 'Quinzena',
            'M' => 'Mês',
            'B' => 'Bimestre',
            'T' => 'Trimestre',
            'E' => 'Semestre',
            'Y' => 'Ano'
        ];
        $baseTempo = array_intersect_key($baseTempo, array_column($cronoAnalises, 'base_tempo', 'base_tempo'));

        $pesoItem = $this->db
            ->select('a.peso_item')
            ->select("FORMAT(a.peso_item, 2, 'de_DE') AS peso_item_de", false)
            ->join('dimensionamento_atividades b', 'b.id = a.id_atividade')
            ->join('dimensionamento_processos c', 'c.id = b.id_processo')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->order_by('a.peso_item', 'asc')
            ->get('dimensionamento_etapas a')
            ->result();

        $data = [
            'processos' => ['' => 'Todos'] + array_column($processos, 'nome', 'id'),
            'atividades' => ['' => 'Todas'],
            'equipes' => ['' => 'Todas'] + array_column($equipes, 'nome', 'id'),
            'colaboradores' => ['' => 'Todos'] + array_column($colaboradores, 'nome', 'id'),
            'cronoAnalises' => ['' => 'Todas'] + array_column($cronoAnalises, 'nome', 'id'),
            'baseTempo' => ['' => 'Todas'] + $baseTempo,
            'unidadeProducao' => ['' => 'Todas'] + array_column($cronoAnalises, 'unidade_producao', 'unidade_producao'),
            'status' => ['' => 'Todas', '1' => 'Ativas', '0' => 'Inativas'],
            'pesoItem' => ['' => 'Todos'] + array_column($pesoItem, 'peso_item_de', 'peso_item')
        ];


        $this->load->view('dimensionamento/medicoes', $data);
    }

    //==========================================================================
    public function gerenciar()
    {
        $cronoAnalises = $this->db
            ->select('id, nome, base_tempo, unidade_producao')
            ->where('id', $this->uri->rsegment(3))
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('dimensionamento_crono_analises')
            ->row();


        if (empty($cronoAnalises)) {
            redirect(site_url('dimensionamento/cronoAnalises'));
        }


        $processo = $this->db
            ->select('b.id, b.nome')
            ->join('dimensionamento_processos b', 'b.id = a.id_processo')
            ->where('a.id', $this->uri->rsegment(3))
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->get('dimensionamento_crono_analises a')
            ->row();


        $atividades = $this->db
            ->select('a.id, a.nome, c.base_tempo, c.unidade_producao')
            ->join('dimensionamento_processos b', 'b.id = a.id_processo')
            ->join('dimensionamento_crono_analises c', 'c.id_processo = b.id')
            ->where('c.id_processo', $this->uri->rsegment(3))
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->order_by('a.nome', 'asc')
            ->get('dimensionamento_atividades a')
            ->result();


        $equipes = $this->db
            ->select('a.id, b.nome')
            ->join('dimensionamento_equipes b', 'b.id = a.id_equipe')
            ->where('a.id_crono_analise', $this->uri->rsegment(3))
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->order_by('b.nome', 'asc')
            ->get('dimensionamento_executores a')
            ->result();


        $colaboradores = $this->db
            ->select('a.id, b.nome')
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->where('a.id_crono_analise', $this->uri->rsegment(3))
            ->where('b.empresa', $this->session->userdata('empresa'))
            ->order_by('b.nome', 'asc')
            ->get('dimensionamento_executores a')
            ->result();

        $baseTempo = [
            'S' => 'Segundo',
            'I' => 'Minuto',
            'H' => 'Hora',
            'D' => 'Dia',
            'W' => 'Semana',
            'Q' => 'Quinzena',
            'M' => 'Mês',
            'B' => 'Bimestre',
            'T' => 'Trimestre',
            'E' => 'Semestre',
            'Y' => 'Ano'
        ];

        $pesoItem = $this->db
            ->select('a.peso_item')
            ->select("FORMAT(a.peso_item, 2, 'de_DE') AS peso_item_de", false)
            ->join('dimensionamento_atividades b', 'b.id = a.id_atividade')
            ->join('dimensionamento_processos c', 'c.id = b.id_processo')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->order_by('a.peso_item', 'asc')
            ->get('dimensionamento_etapas a')
            ->result();

        $data = [
            'processos' => [$processo->id => $processo->nome],
            'atividades' => ['' => 'Todas'] + array_column($atividades, 'nome', 'id'),
            'equipes' => ['' => 'Todas'] + array_column($equipes, 'nome', 'id'),
            'colaboradores' => ['' => 'Todos'] + array_column($colaboradores, 'nome', 'id'),
            'cronoAnalises' => [$cronoAnalises->id => $cronoAnalises->nome],
            'baseTempo' => $baseTempo[$cronoAnalises->base_tempo] ?? '',
            'unidadeProducao' => $cronoAnalises->unidade_producao,
            'status' => ['' => 'Todas', '1' => 'Ativas', '0' => 'Inativas'],
            'pesoItem' => ['' => 'Todos'] + array_column($pesoItem, 'peso_item_de', 'peso_item')
        ];


        $this->load->view('dimensionamento/medicoes', $data);
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $processo = $this->input->post('processo');
        $atividade = $this->input->post('atividade');
        $etapa = $this->input->post('etapa');


        $rowAtividades = $this->db
            ->select('a.id, a.nome')
            ->join('dimensionamento_processos b', 'b.id = a.id_processo')
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->where('b.id', $processo)
            ->order_by('a.nome', 'asc')
            ->get('dimensionamento_atividades a')
            ->result();


        $atividades = array_column($rowAtividades, 'nome', 'id');


        $rowEtapas = $this->db
            ->select('a.id, a.nome')
            ->join('dimensionamento_atividades b', 'b.id = a.id_atividade')
            ->join('dimensionamento_processos c', 'c.id = b.id_processo')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->where('c.id', $processo)
            ->where('b.id', $atividade)
            ->order_by('a.nome', 'asc')
            ->get('dimensionamento_etapas a')
            ->result();


        $etapas = array_column($rowEtapas, 'nome', 'id');


        $data['atividade'] = form_dropdown('', ['' => 'Todas'] + $atividades, $atividade);
        $data['etapa'] = form_dropdown('', ['' => 'Todas'] + $etapas, $etapa);


        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxList()
    {
        $idProcesso = $this->input->post('id_processo');
        $idAtividade = $this->input->post('id_atividade');
        $idEtapa = $this->input->post('id_etapa');
        $tipo = $this->input->post('tipo');
        $idExecutor = $this->input->post('id_executor');
        $idCronoAnalise = $this->input->post('id_crono_analise');
        $complexidade = $this->input->post('complexidade');
        $tipoItiem = $this->input->post('tipo_item');
        $pesoItiem = $this->input->post('peso_item');
        $status = $this->input->post('status');


        $this->db
            ->select("(CASE b.tipo WHEN 'E' THEN g.nome WHEN 'C' THEN h.nome END) AS nome", false)
            ->select("(CASE b.tipo WHEN 'E' THEN g.total_componentes ELSE 1 END) AS total_componentes", false)
            ->select('f.nome AS processo, e.nome AS atividade, d.nome AS etapa')
            ->select('a.tempo_inicio, a.tempo_termino, a.tempo_gasto, a.quantidade')
            ->select('a.tempo_unidade, a.indice_mao_obra, d.grau_complexidade, d.tamanho_item, d.peso_item, a.id')
            ->join('dimensionamento_executores b', 'b.id = a.id_executor')
            ->join('dimensionamento_crono_analises c', 'c.id = b.id_crono_analise')
            ->join('dimensionamento_etapas d', 'd.id = a.id_etapa')
            ->join('dimensionamento_atividades e', 'e.id = d.id_atividade')
            ->join('dimensionamento_processos f', 'f.id = e.id_processo AND f.id = c.id_processo')
            ->join('dimensionamento_equipes g', 'g.id = b.id_equipe', 'left')
            ->join('usuarios h', 'h.id = b.id_usuario', 'left')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->where('b.tipo', $tipo);
        if ($idProcesso) {
            $this->db->where('f.id', $idProcesso);
        }
        if ($idAtividade) {
            $this->db->where('e.id', $idAtividade);
        }
        if ($idEtapa) {
            $this->db->where('d.id', $idEtapa);
        }
        if ($idExecutor) {
            $this->db->where('b.id', $idExecutor);
        }
        if ($idCronoAnalise) {
            $this->db->where('c.id', $idCronoAnalise);
        }
        if ($complexidade) {
            $this->db->where('d.grau_complexidade', $complexidade);
        }
        if ($tipoItiem) {
            $this->db->where('d.tamanho_item', $tipoItiem);
        }
        if ($pesoItiem) {
            $this->db->where('d.peso_item', $pesoItiem);
        }
        if (strlen($status)) {
            $this->db->where('a.status', $status);
        }
        $query = $this->db
            ->group_by('a.id')
            ->get('dimensionamento_medicoes a');


        $config = ['search' => [
            'nome',
            'total_componentes',
            'processo',
            'atividade',
            'etapa'
        ]];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);


        $complexidade = [
            '1' => 'Extremamente baixa',
            '2' => 'Baixa',
            '3' => 'Média',
            '4' => 'Alta',
            '5' => 'Extremamente alta'
        ];

        $tipoItem = [
            '1' => 'Extremamente pequeno',
            '2' => 'Pequeno',
            '3' => 'Médio',
            '4' => 'Grande',
            '5' => 'Extremamente grande'
        ];


        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->total_componentes,
                $row->processo,
                $row->atividade,
                $row->etapa,
                str_replace('.', ',', round($row->tempo_inicio, 3)),
                str_replace('.', ',', round($row->tempo_termino, 3)),
                str_replace('.', ',', round($row->tempo_gasto, 3)),
                str_replace('.', ',', round($row->quantidade, 3)),
                str_replace('.', ',', round($row->tempo_unidade, 3)),
                str_replace('.', ',', round($row->indice_mao_obra, 3)),
                $complexidade[$row->grau_complexidade] ?? null,
                $tipoItem[$row->tamanho_item] ?? null,
                str_replace('.', ',', round($row->peso_item, 3)),
                '<button class="btn btn-sm btn-info" onclick="edit_medicao(' . $row->id . ')" title="Editar medição"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_medicao(' . $row->id . ')" title="Excluir medição"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $data = $this->db->select('a.*', false)
            ->select('g.nome AS equipe, h.nome AS colaborador, c.nome AS crono_analise')
            ->select('f.nome AS processo, e.nome AS atividade, d.nome AS etapa, a.status')
            ->join('dimensionamento_executores b', 'b.id = a.id_executor')
            ->join('dimensionamento_crono_analises c', 'c.id = b.id_crono_analise')
            ->join('dimensionamento_etapas d', 'd.id = a.id_etapa')
            ->join('dimensionamento_atividades e', 'e.id = d.id_atividade')
            ->join('dimensionamento_processos f', 'f.id = e.id_processo AND f.id = c.id_processo')
            ->join('dimensionamento_equipes g', 'g.id = b.id_equipe', 'left')
            ->join('usuarios h', 'h.id = b.id_usuario', 'left')
            ->where('a.id', $this->input->post('id'))
            ->group_by('a.id')
            ->get('dimensionamento_medicoes a')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Medição não encontrada ou excluída recentemente.']));
        }

        if ($data->tempo_inicio) {
            $data->tempo_inicio = str_replace('.', ',', round($data->tempo_inicio, 3));
        }
        if ($data->tempo_termino) {
            $data->tempo_termino = str_replace('.', ',', round($data->tempo_termino, 3));
        }
        if ($data->tempo_gasto) {
            $data->tempo_gasto = str_replace('.', ',', round($data->tempo_gasto, 3));
        }
        if ($data->quantidade) {
            $data->quantidade = str_replace('.', ',', round($data->quantidade, 3));
        }


        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $data = $this->input->post();

        $equipe = $this->db
            ->select('IFNULL(b.total_componentes, 1) AS total_componentes', false)
            ->join('dimensionamento_equipes b', 'b.id = a.id_equipe', 'left')
            ->where('a.id', $data['id_executor'])
            ->get('dimensionamento_executores a')
            ->row();
        $totalComponentes = $equipe->total_componentes;

        if ($data['tempo_inicio']) {
            $data['tempo_inicio'] = str_replace(',', '.', $data['tempo_inicio']);
        }
        if ($data['tempo_termino']) {
            $data['tempo_termino'] = str_replace(',', '.', $data['tempo_termino']);
        }
        if ($data['tempo_gasto']) {
            $data['tempo_gasto'] = str_replace(',', '.', $data['tempo_gasto']);
        }
        if ($data['quantidade']) {
            $data['quantidade'] = str_replace(',', '.', $data['quantidade']);
        }
        $data['tempo_unidade'] = round($data['quantidade'] / ($data['tempo_gasto'] * $totalComponentes), 3);
        $data['indice_mao_obra'] = round(($data['tempo_gasto'] * $totalComponentes) / $data['quantidade'], 3);


        $this->db->trans_start();
        $this->db->insert('dimensionamento_medicoes', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar a medição.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $data = $this->input->post();

        $equipe = $this->db
            ->select('IFNULL(b.total_componentes, 1) AS total_componentes', false)
            ->join('dimensionamento_equipes b', 'b.id = a.id_equipe', 'left')
            ->where('a.id', $data['id_executor'])
            ->get('dimensionamento_executores a')
            ->row();
        $totalComponentes = $equipe->total_componentes;

        if ($data['tempo_inicio']) {
            $data['tempo_inicio'] = str_replace(',', '.', $data['tempo_inicio']);
        }
        if ($data['tempo_termino']) {
            $data['tempo_termino'] = str_replace(',', '.', $data['tempo_termino']);
        }
        if ($data['tempo_gasto']) {
            $data['tempo_gasto'] = str_replace(',', '.', $data['tempo_gasto']);
        }
        if ($data['quantidade']) {
            $data['quantidade'] = str_replace(',', '.', $data['quantidade']);
        }
        $data['tempo_unidade'] = round($data['quantidade'] / ($data['tempo_gasto'] * $totalComponentes), 3);
        $data['indice_mao_obra'] = round(($data['tempo_gasto'] * $totalComponentes) / $data['quantidade'], 3);

        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_medicoes', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar a medição.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_medicoes', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir a medição.']));
        }

        echo json_encode(['status' => true]);
    }


}
