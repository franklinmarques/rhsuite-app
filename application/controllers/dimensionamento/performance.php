<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Performance extends MY_Controller
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
            'equipes' => ['' => 'Todos'] + array_column($equipes, 'nome', 'id'),
            'colaboradores' => ['' => 'Todos'] + array_column($colaboradores, 'nome', 'id'),
            'cronoAnalises' => ['' => 'Todas'] + array_column($cronoAnalises, 'nome', 'id'),
            'baseTempo' => ['' => 'Todas'] + $baseTempo,
            'unidadeProducao' => ['' => 'Todas'] + array_column($cronoAnalises, 'unidade_producao', 'unidade_producao'),
            'pesoItem' => ['' => 'Todos'] + array_column($pesoItem, 'peso_item_de', 'peso_item')
        ];


        $this->load->view('dimensionamento/performance', $data);
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
            ->select('a.id, a.nome')
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
            'equipes' => ['' => 'Todos'] + array_column($equipes, 'nome', 'id'),
            'colaboradores' => ['' => 'Todos'] + array_column($colaboradores, 'nome', 'id'),
            'cronoAnalises' => [$cronoAnalises->id => $cronoAnalises->nome],
            'baseTempo' => $baseTempo[$cronoAnalises->base_tempo] ?? '',
            'unidadeProducao' => $cronoAnalises->unidade_producao,
            'pesoItem' => ['' => 'Todos'] + array_column($pesoItem, 'peso_item_de', 'peso_item')
        ];


        $this->load->view('dimensionamento/performance', $data);
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
        $idCronoAnalise = $this->input->post('id_crono_analise');
        $idProcesso = $this->input->post('id_processo');
        $idAtividade = $this->input->post('id_atividade');
        $idEtapa = $this->input->post('id_etapa');
        $tipo = $this->input->post('tipo');
        $idExecutor = $this->input->post('id_executor');
        $complexidade = $this->input->post('complexidade');
        $tipoItiem = $this->input->post('tipo_item');
        $pesoItiem = $this->input->post('peso_item');
        $medicaoCalculada = $this->input->post('medicao_calculada');


        $this->db
            ->select("(CASE b.tipo WHEN 'E' THEN CONCAT(g.nome, ' (', COUNT(i.id), ')') WHEN 'C' THEN h.nome END) AS nome", false)
            ->select('a.tempo_unidade, a.indice_mao_obra')
            ->select(["CONCAT(e.nome, '/', d.nome) AS atividade_etapa"], false)
            ->select("IF(a.medicao_calculada, 'Cálculo', 'Medição') AS medicao_calculada", false)
            ->select('a.valor_min_calculado, a.valor_medio_calculado, a.valor_max_calculado')
            ->select('a.mao_obra_min_calculada, a.mao_obra_media_calculada, a.mao_obra_max_calculada, a.id')
            ->join('dimensionamento_executores b', 'b.id = a.id_executor')
            ->join('dimensionamento_crono_analises c', 'c.id = b.id_crono_analise')
            ->join('dimensionamento_etapas d', 'd.id = a.id_etapa')
            ->join('dimensionamento_atividades e', 'e.id = d.id_atividade')
            ->join('dimensionamento_processos f', 'f.id = e.id_processo AND f.id = c.id_processo')
            ->join('dimensionamento_equipes g', 'g.id = b.id_equipe', 'left')
            ->join('usuarios h', 'h.id = b.id_usuario', 'left')
            ->join('dimensionamento_equipes_membros i', 'i.id_equipe = g.id', 'left')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->where('a.medicao_calculada', $medicaoCalculada)
            ->where('b.tipo', $tipo);
        if ($idCronoAnalise) {
            $this->db->where('c.id', $idCronoAnalise);
        }
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
        if ($complexidade) {
            $this->db->where('d.grau_complexidade', $complexidade);
        }
        if ($tipoItiem) {
            $this->db->where('d.tamanho_item', $tipoItiem);
        }
        if ($pesoItiem) {
            $this->db->where('d.peso_item', $pesoItiem);
        }
        $query = $this->db
            ->group_by('a.id')
            ->get('dimensionamento_medicoes a');


        $config = ['search' => ['nome']];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);


        $data = array();

        foreach ($output->data as $row) {
            if ($medicaoCalculada) {
                $acao = '<button class="btn btn-sm btn-danger" onclick="excluir_calculo(' . $row->id . ')" title="Excluir medição"><i class="glyphicon glyphicon-trash"></i></button>';
            } else {
                $acao = '';
            }

            $data[] = array(
                $row->nome,
                $row->atividade_etapa,
                $row->medicao_calculada,
                str_replace('.', ',', round($row->tempo_unidade, 3)),
                str_replace('.', ',', round($row->indice_mao_obra, 3)),
                str_replace('.', ',', round($row->valor_min_calculado, 3)),
                str_replace('.', ',', round($row->valor_medio_calculado, 3)),
                str_replace('.', ',', round($row->valor_max_calculado, 3)),
                str_replace('.', ',', round($row->mao_obra_max_calculada, 3)),
                str_replace('.', ',', round($row->mao_obra_media_calculada, 3)),
                str_replace('.', ',', round($row->mao_obra_min_calculada, 3)),
                $acao
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }

    //==========================================================================
    public function editarCalculos()
    {
        $idCronoAnalise = $this->input->post('id_crono_analise');
        $idProcesso = $this->input->post('id_processo');
        $idAtividade = $this->input->post('id_atividade');
        $idEtapa = $this->input->post('id_etapa');
        $tipo = $this->input->post('tipo');
        $idExecutor = $this->input->post('id_executor');
        $complexidade = $this->input->post('complexidade');
        $tipoItiem = $this->input->post('tipo_item');
        $medicaoCalculada = $this->input->post('medicao_calculada');


        $this->db
            ->select(['IF(a.medicao_calculada, NULL, MIN(a.tempo_unidade)) AS valor_min_calculado'], false)
            ->select(['IF(a.medicao_calculada, NULL, AVG(a.tempo_unidade)) AS valor_medio_calculado'], false)
            ->select(['IF(a.medicao_calculada, NULL, MAX(a.tempo_unidade)) AS valor_max_calculado'], false)
            ->select(['IF(a.medicao_calculada, NULL, MIN(a.indice_mao_obra)) AS mao_obra_min_calculada'], false)
            ->select(['IF(a.medicao_calculada, NULL, AVG(a.indice_mao_obra)) AS mao_obra_media_calculada'], false)
            ->select(['IF(a.medicao_calculada, NULL, MAX(a.indice_mao_obra)) AS mao_obra_max_calculada'], false)
            ->select(['IF(a.medicao_calculada, SUM(a.valor_min_calculado), NULL) AS soma_menor'], false)
            ->select(['IF(a.medicao_calculada, SUM(a.valor_medio_calculado), NULL) AS soma_media'], false)
            ->select(['IF(a.medicao_calculada, SUM(a.valor_max_calculado), NULL) AS soma_maior'], false)
            ->select(['IF(a.medicao_calculada, SUM(a.mao_obra_min_calculada), NULL) AS mao_obra_menor'], false)
            ->select(['IF(a.medicao_calculada, SUM(a.mao_obra_media_calculada), NULL) AS mao_obra_media'], false)
            ->select(['IF(a.medicao_calculada, SUM(a.mao_obra_max_calculada), NULL) AS mao_obra_maior'], false)
            ->join('dimensionamento_executores b', 'b.id = a.id_executor')
            ->join('dimensionamento_crono_analises c', 'c.id = b.id_crono_analise')
            ->join('dimensionamento_etapas d', 'd.id = a.id_etapa')
            ->join('dimensionamento_atividades e', 'e.id = d.id_atividade')
            ->join('dimensionamento_processos f', 'f.id = e.id_processo AND f.id = c.id_processo')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->where('a.medicao_calculada', $medicaoCalculada)
            ->where('b.tipo', $tipo);
        if ($idCronoAnalise) {
            $this->db->where('c.id', $idCronoAnalise);
        }
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
        if ($complexidade) {
            $this->db->where('d.grau_complexidade', $complexidade);
        }
        if ($tipoItiem) {
            $this->db->where('d.tamanho_item', $tipoItiem);
        }
        $data = $this->db->get('dimensionamento_medicoes a')->row();


        if ($data->valor_min_calculado) {
            $data->valor_min_calculado = str_replace('.', ',', round($data->valor_min_calculado, 3));
        }
        if ($data->valor_medio_calculado) {
            $data->valor_medio_calculado = str_replace('.', ',', round($data->valor_medio_calculado, 3));
        }
        if ($data->valor_max_calculado) {
            $data->valor_max_calculado = str_replace('.', ',', round($data->valor_max_calculado, 3));
        }

        if ($data->mao_obra_min_calculada) {
            $data->mao_obra_min_calculada = str_replace('.', ',', round($data->mao_obra_min_calculada, 3));
        }
        if ($data->mao_obra_media_calculada) {
            $data->mao_obra_media_calculada = str_replace('.', ',', round($data->mao_obra_media_calculada, 3));
        }
        if ($data->mao_obra_max_calculada) {
            $data->mao_obra_max_calculada = str_replace('.', ',', round($data->mao_obra_max_calculada, 3));
        }

        if ($data->soma_menor) {
            $data->soma_menor = str_replace('.', ',', round($data->soma_menor, 3));
        }
        if ($data->soma_media) {
            $data->soma_media = str_replace('.', ',', round($data->soma_media, 3));
        }
        if ($data->soma_maior) {
            $data->soma_maior = str_replace('.', ',', round($data->soma_maior, 3));
        }

        if ($data->mao_obra_menor) {
            $data->mao_obra_menor = str_replace('.', ',', round($data->mao_obra_menor, 3));
        }
        if ($data->mao_obra_media) {
            $data->mao_obra_media = str_replace('.', ',', round($data->mao_obra_media, 3));
        }
        if ($data->mao_obra_maior) {
            $data->mao_obra_maior = str_replace('.', ',', round($data->mao_obra_maior, 3));
        }


        echo json_encode($data);
    }

    //==========================================================================
    public function salvarCalculos()
    {
        $idCronoAnalise = $this->input->post('id_crono_analise');
        $idProcesso = $this->input->post('id_processo');
        $idAtividade = $this->input->post('id_atividade');
        $idEtapa = $this->input->post('id_etapa');
        $tipo = $this->input->post('tipo');
        $idExecutor = $this->input->post('id_executor');
        $complexidade = $this->input->post('complexidade');
        $tipoItiem = $this->input->post('tipo_item');


        $this->db
            ->select('a.*', false)
            ->join('dimensionamento_executores b', 'b.id = a.id_executor')
            ->join('dimensionamento_crono_analises c', 'c.id = b.id_crono_analise')
            ->join('dimensionamento_etapas d', 'd.id = a.id_etapa')
            ->join('dimensionamento_atividades e', 'e.id = d.id_atividade')
            ->join('dimensionamento_processos f', 'f.id = e.id_processo AND f.id = c.id_processo')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->where('b.tipo', $tipo)
            ->where('a.medicao_calculada = (SELECT IF(SUM(x.medicao_calculada) > 0, 1, 0) 
                                            FROM dimensionamento_medicoes x 
                                            WHERE x.id_executor = a.id_executor 
                                            AND x.id_etapa = a.id_etapa)', null, false);
        if ($idCronoAnalise) {
            $this->db->where('c.id', $idCronoAnalise);
        }
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
        if ($complexidade) {
            $this->db->where('d.grau_complexidade', $complexidade);
        }
        if ($tipoItiem) {
            $this->db->where('d.tamanho_item', $tipoItiem);
        }
        $rows = $this->db
            ->group_by('a.id_executor')
            ->get('dimensionamento_medicoes a')
            ->result_array();


        $valorMinCalculado = $this->input->post('valor_min_calculado');
        $valorMedioCalculado = $this->input->post('valor_medio_calculado');
        $valorMaxCalculado = $this->input->post('valor_max_calculado');

        $maoObraMinCalculada = $this->input->post('mao_obra_min_calculada');
        $maoObraMediaCalculada = $this->input->post('mao_obra_media_calculada');
        $maoObraMaxCalculada = $this->input->post('mao_obra_max_calculada');

        $valorMinCalculado = $valorMinCalculado ? str_replace(',', '.', $valorMinCalculado) : null;
        $valorMedioCalculado = $valorMedioCalculado ? str_replace(',', '.', $valorMedioCalculado) : null;
        $valorMaxCalculado = $valorMaxCalculado ? str_replace(',', '.', $valorMaxCalculado) : null;

        $maoObraMinCalculada = $maoObraMinCalculada ? str_replace(',', '.', $maoObraMinCalculada) : null;
        $maoObraMediaCalculada = $maoObraMediaCalculada ? str_replace(',', '.', $maoObraMediaCalculada) : null;
        $maoObraMaxCalculada = $maoObraMaxCalculada ? str_replace(',', '.', $maoObraMaxCalculada) : null;


        $this->db->trans_start();


        foreach ($rows as $row) {
            $row['valor_min_calculado'] = $valorMinCalculado;
            $row['valor_medio_calculado'] = $valorMedioCalculado;
            $row['valor_max_calculado'] = $valorMaxCalculado;
            $row['mao_obra_min_calculada'] = $maoObraMinCalculada;
            $row['mao_obra_media_calculada'] = $maoObraMediaCalculada;
            $row['mao_obra_max_calculada'] = $maoObraMaxCalculada;

            if ($row['medicao_calculada']) {
                $this->db->update('dimensionamento_medicoes', $row, ['id' => $row['id']]);
            } else {
                $row['medicao_calculada'] = 1;
                unset($row['id']);
                $this->db->insert('dimensionamento_medicoes', $row);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível salvar os valores.']));
        }


        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function salvarCronoAnalise()
    {
        $idCronoAnalise = $this->input->post('id_crono_analise');
        if (empty($idCronoAnalise)) {
            exit(json_encode(['erro' => 'Selecione uma cronoAnálise.']));
        }

        $idExecutor = $this->input->post('id_executor');
        if (empty($idExecutor)) {
            exit(json_encode(['erro' => 'Selecione um colaborador.']));
        }

        $idProcesso = $this->input->post('id_processo');
        $idAtividade = $this->input->post('id_atividade');
        $idEtapa = $this->input->post('id_etapa');
        $complexidade = $this->input->post('complexidade');
        $tipoItiem = $this->input->post('tipo_item');

        $data = [
            'id_empresa' => $this->session->userdata('empresa'),
            'id_usuario' => $this->session->userdata('id'),
            'id_executor' => $this->session->userdata('empresa'),
            'id_crono_analise' => $idCronoAnalise,
            'id_executor' => $idExecutor,
            'id_processo' => strlen($idProcesso) > 0 ? $idProcesso : null,
            'id_atividade' => strlen($idAtividade) > 0 ? $idAtividade : null,
            'id_etapa' => strlen($idEtapa) > 0 ? $idEtapa : null,
            'grau_complexidade' => strlen($tipoItiem) > 0 ? $complexidade : null,
            'tamanho_item' => strlen($tipoItiem) > 0 ? $tipoItiem : null
        ];


        $row = $this->db
            ->select('id')
            ->where($data)
            ->get('dimensionamento_medicoes_resultado')
            ->row();

        $data['soma_menor'] = str_replace(',', '.', $this->input->post('soma_menor'));
        $data['soma_media'] = str_replace(',', '.', $this->input->post('soma_media'));
        $data['soma_maior'] = str_replace(',', '.', $this->input->post('soma_maior'));

        $data['mao_obra_menor'] = str_replace(',', '.', $this->input->post('mao_obra_menor'));
        $data['mao_obra_media'] = str_replace(',', '.', $this->input->post('mao_obra_media'));
        $data['mao_obra_maior'] = str_replace(',', '.', $this->input->post('mao_obra_maior'));


        $this->db->trans_start();

        if ($row) {
            $this->db->update('dimensionamento_medicoes_resultado', $data, ['id', $row->id]);
        } else {
            $data['data_cadastro'] = date('Y-m-d H:i:s');
            $this->db->insert('dimensionamento_medicoes_resultado', $data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir a medição.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluirCalculo()
    {
        $this->db->trans_start();
        $this->db
            ->where('id', $this->input->post('id'))
            ->where('medicao_calculada', 1)
            ->delete('dimensionamento_medicoes');
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir a medição.']));
        }

        echo json_encode(['status' => true]);
    }


}
