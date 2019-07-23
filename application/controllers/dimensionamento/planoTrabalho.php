<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PlanoTrabalho extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $deptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();


        $processos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->order_by('nome', 'asc')
            ->get('dimensionamento_processos')
            ->result();


        $data = [
            'depto' => ['' => 'Todos'] + array_column($deptos, 'nome', 'id'),
            'processos' => ['' => 'selecione...'] + array_column($processos, 'nome', 'id')
        ];


        $this->load->view('dimensionamento/plano_trabalho', $data);
    }

    //==========================================================================
    public function filtrarMedicao()
    {
        $empresa = $this->session->userdata('empresa');

        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');
        $idProcesso = $this->input->post('processo');
        $idAtividade = $this->input->post('atividade');
        $complexidade = $this->input->post('complexidade');
        $tipoItem = $this->input->post('tipo_item');
        $idEtapa = $this->input->post('etapa');
        $idCronoAnalise = $this->input->post('crono_analise');
        $idEquipe = $this->input->post('equipe');
        $idColaborador = $this->input->post('colaborador');


        $rowAreas = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_departamentos b', 'b.id = a.id_departamento')
            ->where('b.id_empresa', $empresa)
            ->where('b.id', $idDepto)
            ->order_by('a.nome', 'asc')
            ->get('empresa_areas a')
            ->result();

        $areas = array_column($rowAreas, 'nome', 'id');

        $rowSetores = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->join('empresa_departamentos c', 'c.id = b.id_departamento')
            ->where('c.id_empresa', $empresa)
            ->where('c.id', $idDepto)
            ->where('b.id', $idArea)
            ->order_by('a.nome', 'asc')
            ->get('empresa_setores a')
            ->result();

        $setores = array_column($rowSetores, 'nome', 'id');

        $data['area'] = form_dropdown('', ['' => 'Todas'] + $areas, $idArea);
        $data['setor'] = form_dropdown('', ['' => 'Todos'] + $setores, $idSetor);


        $this->db
            ->select('id, nome')
            ->where('id_empresa', $empresa);
        if ($idDepto) {
            $this->db->where('id_depto', $idDepto);
        }
        if ($idArea) {
            $this->db->where('id_area', $idArea);
        }
        if ($idSetor) {
            $this->db->where('id_setor', $idSetor);
        }
        $rowProcessos = $this->db
            ->order_by('nome', 'asc')
            ->get('dimensionamento_processos')
            ->result();


        $processos = array_column($rowProcessos, 'nome', 'id');


        $rowAtividades = $this->db
            ->select('a.id, a.nome')
            ->join('dimensionamento_processos b', 'b.id = a.id_processo')
            ->where('b.id_empresa', $empresa)
            ->where('b.id', $idProcesso)
            ->order_by('a.nome', 'asc')
            ->get('dimensionamento_atividades a')
            ->result();


        $atividades = array_column($rowAtividades, 'nome', 'id');


        $rowDadosEtapas = $this->db
            ->select('a.grau_complexidade, a.tamanho_item')
            ->join('dimensionamento_atividades b', 'b.id = a.id_atividade')
            ->join('dimensionamento_processos c', 'c.id = b.id_processo')
            ->where('c.id_empresa', $empresa)
            ->where('c.id', $idProcesso)
            ->where('b.id', $idAtividade)
            ->order_by('a.nome', 'asc')
            ->get('dimensionamento_etapas a')
            ->result();

        $grauComplexidade = array_column($rowDadosEtapas, 'grau_complexidade', 'grau_complexidade');
        $tamanhoItem = array_column($rowDadosEtapas, 'tamanho_item', 'tamanho_item');


        $rowGrauComplexidade = [
            '1' => 'Extremamente baixa',
            '2' => 'Baixa',
            '3' => 'Média',
            '4' => 'Alta',
            '5' => 'Extremamente alta'
        ];

        $rowTamanhoItem = [
            '1' => 'Extremamente pequeno',
            '2' => 'Pequeno',
            '3' => 'Médio',
            '4' => 'Grande',
            '5' => 'Extremamente grande'
        ];

        $grausComplexidade = array_intersect_key($rowGrauComplexidade, $grauComplexidade);
        $tamanhoItens = array_intersect_key($rowTamanhoItem, $tamanhoItem);


        $this->db
            ->select('a.id, a.nome')
            ->join('dimensionamento_atividades b', 'b.id = a.id_atividade')
            ->join('dimensionamento_processos c', 'c.id = b.id_processo')
            ->where('c.id_empresa', $empresa)
            ->where('c.id', $idProcesso)
            ->where('b.id', $idAtividade);
        if ($complexidade) {
            $this->db->where('a.grau_complexidade', $complexidade);
        }
        if ($tipoItem) {
            $this->db->where('a.tamanho_item', $tipoItem);
        }
        $rowEtapas = $this->db
            ->order_by('a.nome', 'asc')
            ->get('dimensionamento_etapas a')
            ->result();

        $etapas = array_column($rowEtapas, 'nome', 'id');


        $data['processo'] = form_dropdown('', ['' => 'selecione...'] + $processos, $idProcesso);
        $data['atividade'] = form_dropdown('', ['' => 'selecione...'] + $atividades, $idAtividade);
        $data['complexidade'] = form_dropdown('', ['' => 'Todas'] + $grausComplexidade, $complexidade);
        $data['tipo_item'] = form_dropdown('', ['' => 'Todos'] + $tamanhoItens, $tipoItem);
        $data['etapa'] = form_dropdown('', ['' => 'selecione...'] + $etapas, $idEtapa);


        $rowCronoAnalises = $this->db
            ->select('id, nome')
            ->where('id_empresa', $empresa)
            ->where('id_processo', $idProcesso)
            ->order_by('nome', 'asc')
            ->get('dimensionamento_crono_analises')
            ->result();

        $cronoAnalises = array_column($rowCronoAnalises, 'nome', 'id');


        $rowEquipes = $this->db
            ->select('a.id, c.nome')
            ->join('dimensionamento_crono_analises b', 'b.id = a.id_crono_analise')
            ->join('dimensionamento_equipes c', 'c.id = a.id_equipe')
            ->join('dimensionamento_medicoes d', 'd.id_executor = a.id', 'left')
            ->where('c.id_empresa', $empresa)
            ->where('b.id_processo', $idProcesso)
            ->where('b.id', $idCronoAnalise)
            ->where('a.tipo', 'E')
            ->where('d.medicao_calculada', 1)
            ->order_by('c.nome', 'asc')
            ->group_by('d.id_executor')
            ->get('dimensionamento_executores a')
            ->result();

        $equipes = array_column($rowEquipes, 'nome', 'id');


        $rowColaboradores = $this->db
            ->select('a.id, c.nome')
            ->join('dimensionamento_crono_analises b', 'b.id = a.id_crono_analise')
            ->join('usuarios c', 'c.id = a.id_usuario')
            ->join('dimensionamento_medicoes d', 'd.id_executor = a.id', 'left')
            ->where('c.empresa', $empresa)
            ->where('b.id_processo', $idProcesso)
            ->where('b.id', $idCronoAnalise)
            ->where('a.tipo', 'C')
            ->where('d.medicao_calculada', 1)
            ->order_by('c.nome', 'asc')
            ->group_by('d.id_executor')
            ->get('dimensionamento_executores a')
            ->result();

        $colaboradores = array_column($rowColaboradores, 'nome', 'id');


        $data['crono_analise'] = form_dropdown('', ['' => 'selecione...'] + $cronoAnalises, $idCronoAnalise);
        $data['equipe'] = form_dropdown('', ['' => 'selecione...'] + $equipes, $idEquipe);
        $data['colaborador'] = form_dropdown('', ['' => 'selecione...'] + $colaboradores, $idColaborador);


        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxList()
    {
        $dia = $this->input->post('dia');
        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');
        $status = $this->input->post('status');


        $this->db
            ->select('a.nome, a.status, a.data_inicio, a.data_termino, a.id')
            ->select('b.nome AS job, b.id AS id_job, c.id AS id_programa')
            ->select("(CASE d.tipo WHEN 'E' THEN e.nome WHEN 'C' THEN f.nome END) AS executor", false)
            ->select(["DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio_de"], false)
            ->select(["DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino_de"], false)
            ->select(["TIME_FORMAT(c.horario_inicio_projetado, '%H:%i') AS horario_inicio_projetado"], false)
            ->select(["TIME_FORMAT(c.horario_termino_projetado, '%H:%i') AS horario_termino_projetado"], false)
            ->select(["TIME_FORMAT(c.horario_inicio_real, '%H:%i') AS horario_inicio_real"], false)
            ->select(["TIME_FORMAT(c.horario_termino_real, '%H:%i') AS horario_termino_real"], false)
            ->join('dimensionamento_jobs b', 'b.id_plano_trabalho = a.id', 'left')
            ->join('dimensionamento_programas c', 'c.id_job = b.id', 'left')
            ->join('dimensionamento_executores d', 'd.id = c.id_executor', 'left')
            ->join('dimensionamento_equipes e', 'e.id = d.id_equipe', 'left')
            ->join('usuarios f', 'f.id = d.id_usuario', 'left')
            ->where('a.id_empresa', $this->session->userdata('empresa'));
        if ($dia) {
            $this->db->where("'{$dia}' IN (DAY(a.data_inicio), DAY(a.data_termino), DAY(b.data_inicio), DAY(b.data_termino))", null, false);
        }
        if ($mes) {
            $this->db->where("'{$mes}' IN (MONTH(a.data_inicio), MONTH(a.data_termino), MONTH(b.data_inicio), MONTH(b.data_termino))", null, false);
        }
        if ($ano) {
            $this->db->where("'{$ano}' IN (YEAR(a.data_inicio), YEAR(a.data_termino), YEAR(b.data_inicio), YEAR(b.data_termino))", null, false);
        }
        if ($status) {
            $this->db->where("(a.status = '{$status}' OR b.status = '{$status}')", null, false);
        }
        $query = $this->db
            ->group_by(['a.id', 'b.id', 'c.id'])
            ->get('dimensionamento_planos_trabalho a');


        $config = ['search' => ['nome', 'job']];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);


        $status = [
            'A' => 'Aberto',
            'E' => 'Encerrado'
        ];


        $data = array();

        foreach ($output->data as $row) {
            if ($row->id_job) {
                $acoesJob = '<button class="btn btn-sm btn-info" onclick="edit_job(' . $row->id_job . ')" title="Editar job"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_job(' . $row->id_job . ')" title="Excluir job"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info" onclick="add_programa(' . $row->id_job . ')" title="Adicionar Programa"><i class="glyphicon glyphicon-plus"></i> Programa</button>';
            } else {
                $acoesJob = '<button class="btn btn-sm btn-info disabled" title="Editar job"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger disabled" title="Excluir job"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info disabed" title="Adicionar Programa"><i class="glyphicon glyphicon-plus"></i> Programa</button>';
            }

            if ($row->id_programa) {
                $acoesProgramacao = '<button class="btn btn-sm btn-info" onclick="edit_programa(' . $row->id_programa . ')" title="Editar job"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_programa(' . $row->id_programa . ')" title="Excluir job"><i class="glyphicon glyphicon-trash"></i></button>';
            } else {
                $acoesProgramacao = '<button class="btn btn-sm btn-info disabled" title="Editar programação de trabalho"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger disabled" title="Excluir programação de trabalho"><i class="glyphicon glyphicon-trash"></i></button>';
            }

            $data[] = array(
                $row->nome,
                $status[$row->status] ?? '',
                $row->data_inicio_de,
                $row->data_termino_de,
                '<button class="btn btn-sm btn-info" onclick="edit_plano_trabalho(' . $row->id . ')" title="Editar plano de trabalho"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_plano_trabalho(' . $row->id . ')" title="Excluir plano de trabalho"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info" onclick="add_job(' . $row->id . ')" title="Adicionar Job"><i class="glyphicon glyphicon-plus"></i> Job</button>',
                $row->job,
                $acoesJob,
                $row->executor,
                $row->horario_inicio_projetado,
                $row->horario_termino_projetado,
                $row->horario_inicio_real,
                $row->horario_termino_real,
                $acoesProgramacao
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxListMedicoes()
    {
        parse_str($this->input->post('busca'), $busca);

        $idExecutor = ['E' => $busca['equipe'], 'C' => $busca['colaborador']];

        $this->db
            ->select("b.id, (CASE b.tipo WHEN 'E' THEN g.nome WHEN 'C' THEN h.nome END) AS nome", false)
            ->select(["a.tempo_unidade, CONCAT(e.nome, '/', d.nome) AS atividade_etapa"], false)
            ->select("IF(a.medicao_calculada, 'Cálculo', 'Medição') AS medicao_calculada", false)
            ->select('a.valor_min_calculado, a.valor_medio_calculado, a.valor_max_calculado')
            ->join('dimensionamento_executores b', 'b.id = a.id_executor')
            ->join('dimensionamento_crono_analises c', 'c.id = b.id_crono_analise')
            ->join('dimensionamento_etapas d', 'd.id = a.id_etapa')
            ->join('dimensionamento_atividades e', 'e.id = d.id_atividade')
            ->join('dimensionamento_processos f', 'f.id = e.id_processo AND f.id = c.id_processo')
            ->join('dimensionamento_equipes g', 'g.id = b.id_equipe', 'left')
            ->join('usuarios h', 'h.id = b.id_usuario', 'left')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->where('f.id', $busca['processo'])
            ->where('e.id', $busca['atividade'])
            ->where('d.id', $busca['etapa'])
            ->where('c.id', $busca['crono_analise'])
            ->where('b.tipo', $busca['tipo'])
            ->where('b.id', $idExecutor[$busca['tipo']] ?? null)
            ->where('a.medicao_calculada', 1);
        if ($busca['depto']) {
            $this->db->where('f.id_depto', $busca['depto']);
        }
        if ($busca['area']) {
            $this->db->where('f.id_area', $busca['area']);
        }
        if ($busca['setor']) {
            $this->db->where('f.id_setor', $busca['setor']);
        }
        if ($busca['complexidade']) {
            $this->db->where('d.grau_complexidade', $busca['complexidade']);
        }
        if ($busca['tipo_item']) {
            $this->db->where('d.tamanho_item', $busca['tipo_item']);
        }
        $query = $this->db
            ->group_by('a.id')
            ->get('dimensionamento_medicoes a');


        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);


        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
//                '<div class="radio"><label><input type="radio" onchange="preencher_dados_programa(' . $row->id . ')"></label></div>',
                $row->id,
                $row->nome,
                $row->atividade_etapa,
                $row->medicao_calculada,
                str_replace('.', ',', round($row->valor_min_calculado, 3)),
                str_replace('.', ',', round($row->valor_medio_calculado, 3)),
                str_replace('.', ',', round($row->valor_max_calculado, 3))
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $data = $this->db
            ->select('id, nome, status, plano_diario')
            ->select(["DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio"], false)
            ->select(["DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino"], false)
            ->where('id', $this->input->post('id'))
            ->get('dimensionamento_planos_trabalho')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Plano de trabalho não encontrado ou excluído recentemente.']));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEditJob()
    {
        $data = $this->db
            ->select('id, nome, status, plano_diario')
            ->select(["DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio"], false)
            ->select(["DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino"], false)
            ->select(["TIME_FORMAT(horario_inicio, '%H:%i') AS horario_inicio"], false)
            ->select(["TIME_FORMAT(horario_termino, '%H:%i') AS horario_termino"], false)
            ->where('id', $this->input->post('id'))
            ->get('dimensionamento_jobs')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Job não encontrado ou excluído recentemente.']));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEditPrograma()
    {
        $data = $this->db
            ->select('a.id, a.status, a.id_job, a.id_executor')
            ->select('b.tipo, IFNULL(c.nome, d.nome) AS nome_executor', false)
            ->join('dimensionamento_executores b', 'b.id = a.id_executor')
            ->join('dimensionamento_equipes c', 'c.id = b.id_equipe', 'left')
            ->join('usuarios d', 'd.id = b.id_usuario', 'left')
            ->select(["TIME_FORMAT(a.horario_inicio_projetado, '%H:%i') AS horario_inicio_projetado"], false)
            ->select(["TIME_FORMAT(a.horario_termino_projetado, '%H:%i') AS horario_termino_projetado"], false)
            ->select(["TIME_FORMAT(a.horario_inicio_real, '%H:%i') AS horario_inicio_real"], false)
            ->select(["TIME_FORMAT(a.horario_termino_real, '%H:%i') AS horario_termino_real"], false)
            ->where('a.id', $this->input->post('id'))
            ->group_by('a.id')
            ->get('dimensionamento_programas a')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Programa não encontrado ou excluído recentemente.']));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $this->validarPlanoTrabalho();
        $this->db->trans_start();
        $this->db->insert('dimensionamento_planos_trabalho', $this->input->post());
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar o plano de trabalho.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxAddJob()
    {
        $this->validarJob();
        $this->db->trans_start();
        $this->db->insert('dimensionamento_jobs', $this->input->post());
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar o job.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxAddPrograma()
    {
        $this->validarPrograma();
        $this->db->trans_start();
        $this->db->insert('dimensionamento_programas', $this->input->post());
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar o programa.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $this->validarPlanoTrabalho();
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_planos_trabalho', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar o plano de trabalho.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdateJob()
    {
        $this->validarJob();
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_jobs', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar o job.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdatePrograma()
    {
        $this->validarPrograma();
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_programas', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar o programa.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    private function validarPlanoTrabalho()
    {
        $data = $this->input->post();
        unset($data['id'], $data['status']);
        if (empty(array_filter($data))) {
            exit(json_encode(['erro' => 'O formulário está vazio.']));
        }
        $_POST['id_empresa'] = $this->session->userdata('empresa');
        if ($data['data_inicio']) {
            $_POST['data_inicio'] = preg_replace('/(\d+)\/(\d+)\/(\d+)/', '$3-$2-$1', $data['data_inicio']);
        }
        if ($data['data_termino']) {
            $_POST['data_termino'] = preg_replace('/(\d+)\/(\d+)\/(\d+)/', '$3-$2-$1', $data['data_termino']);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nome', '"Nome"', 'required|max_length[255]');
        $this->form_validation->set_rules('data_inicio', '"Data Início"', 'required|valid_date');
        $this->form_validation->set_rules('data_termino', '"Data Término"', 'required|valid_date|after_or_equal_date[data_inicio]');

        if ($this->form_validation->run() == false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }

        $count = $this->db
            ->where('id !=', $this->input->post('id'))
            ->where('id_empresa', $this->input->post('id_empresa'))
            ->where('nome', $this->input->post('nome'))
            ->get('dimensionamento_planos_trabalho')
            ->num_rows();

        if ($count > 0) {
            exit(json_encode(['erro' => 'O campo "Nome Plano" já existe, ele deve ser único.']));
        }
    }

    //==========================================================================
    private function validarJob()
    {
        $data = $this->input->post();
        unset($data['id'], $data['id_plano_trabalho'], $data['status']);
        if (empty(array_filter($data))) {
            exit(json_encode(['erro' => 'O formulário está vazio.']));
        }
        if ($data['data_inicio']) {
            $_POST['data_inicio'] = preg_replace('/(\d+)\/(\d+)\/(\d+)/', '$3-$2-$1', $data['data_inicio']);
        }
        if ($data['data_termino']) {
            $_POST['data_termino'] = preg_replace('/(\d+)\/(\d+)\/(\d+)/', '$3-$2-$1', $data['data_termino']);
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nome', '"Nome"', 'required|max_length[255]');
        $this->form_validation->set_rules('data_inicio', '"Data Início"', 'required|valid_date');
        $this->form_validation->set_rules('data_termino', '"Data Término"', 'required|valid_date|after_or_equal_date[data_inicio]');
        $this->form_validation->set_rules('horario_inicio', '"Horário Início"', 'valid_time');
        $this->form_validation->set_rules('horario_termino', '"Horário Término"', 'valid_time|after_or_equal_time[horario_inicio]');

        if ($this->form_validation->run() == false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }

        $count = $this->db
            ->where('id !=', $this->input->post('id'))
            ->where('id_plano_trabalho', $this->input->post('id_plano_trabalho'))
            ->where('nome', $this->input->post('nome'))
            ->get('dimensionamento_jobs')
            ->num_rows();

        if ($count > 0) {
            exit(json_encode(['erro' => 'O campo "Nome Job" já existe, ele deve ser único.']));
        }
    }

    //==========================================================================
    private function validarPrograma()
    {
        $data = $this->input->post();
        unset($data['id'], $data['id_job'], $data['status']);
        if (empty(array_filter($data))) {
            exit(json_encode(['erro' => 'O formulário está vazio.']));
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('id_executor', '"Equipe/Colaborador(a) alocado para o job"', 'required');
        $this->form_validation->set_rules('horario_inicio_projetado', '"Horário Início Projetado"', 'valid_time');
        $this->form_validation->set_rules('horario_termino_projetado', '"Horário Término Projetado"', 'valid_time|after_or_equal_time[horario_inicio_projetado]');
        $this->form_validation->set_rules('horario_inicio_real', '"Horário Início Real"', 'valid_time');
        $this->form_validation->set_rules('horario_termino_real', '"Horário Término Real"', 'valid_time|after_or_equal_time[horario_inicio_real]');

        if ($this->form_validation->run() == false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_planos_trabalho', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir o plano de trabalho.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxDeleteJob()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_jobs', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir o job.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxDeletePrograma()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_programas', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir o programa.']));
        }

        echo json_encode(['status' => true]);
    }


}
