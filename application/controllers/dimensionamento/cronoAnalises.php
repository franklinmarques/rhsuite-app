<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CronoAnalises extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $data = ['empresa' => $this->session->userdata('empresa')];
        $this->db->where('id_empresa', $data['empresa']);
        $processos = $this->db->get('dimensionamento_processos')->result();
        $data['processos'] = ['' => 'selecione...'] + array_column($processos, 'nome', 'id');
        $this->load->view('dimensionamento/crono_analises', $data);
    }

    //==========================================================================
    public function ajaxList()
    {
        $status = $this->input->post('status');

        $this->db
            ->select("a.nome, (CASE a.status WHEN 'A' THEN 'Ativa' WHEN 'I' THEN 'Inativa' END) AS status", false)
            ->select('b.nome AS padrao, a.data_inicio, a.data_termino, a.id, b.id_depto')
            ->select(["DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio_de"], false)
            ->select(["DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino_de"], false)
            ->join('dimensionamento_processos b', 'b.id = a.id_processo', 'left')
            ->where('a.id_empresa', $this->session->userdata('empresa'));
        if ($this->input->post('ativos')) {
            $this->db->where('(NOW() BETWEEN a.data_inicio AND a.data_termino)');
        }
        if ($status) {
            $this->db->where('a.status', $status);
        }
        $query = $this->db->get('dimensionamento_crono_analises a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();
        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->status,
                $row->padrao,
                $row->data_inicio_de,
                $row->data_termino_de,
                '<button class="btn btn-sm btn-info" onclick="edit_crono_analise(' . $row->id . ')" title="Editar crono análise"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_crono_analise(' . $row->id . ')" title="Excluir crono análise"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-info" onclick="edit_executores(' . $row->id . ')" title="Gerenciar executores"><i class="glyphicon glyphicon-list"></i> Analisados</button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('dimensionamento/apontamentos/gerenciar/' . $row->id) . '" target="_blank" title="Gerenciar apontamentos">Apontamentos</a>
                 <a class="btn btn-sm btn-primary" href="' . site_url('dimensionamento/performance/gerenciar/' . $row->id) . '" target="_blank" title="Análise performance">Rel. performance</a>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxEdit()
    {
        $data = $this->db
            ->select('id, id_empresa, id_processo, nome, status, base_tempo, unidade_producao')
            ->select(["DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio"], false)
            ->select(["DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino"], false)
            ->where('id', $this->input->post('id'))
            ->get('dimensionamento_crono_analises')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Análise não encontrada ou excluída recentemente.']));
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $this->validarCronoAnalise();
        $this->db->trans_start();
        $this->db->insert('dimensionamento_crono_analises', $this->input->post());
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar a análise.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $this->validarCronoAnalise();
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_crono_analises', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar a análise.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    private function validarCronoAnalise()
    {
        $data = $this->input->post();
        unset($data['id'], $data['id_empresa']);
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
        $this->form_validation->set_rules('unidade_producao', '"Unidade Produção"', 'max_length[30]');

        if ($this->form_validation->run() == false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }

        $count = $this->db
            ->where('id !=', $this->input->post('id'))
            ->where('id_empresa', $this->input->post('id_empresa'))
            ->where('nome', $this->input->post('nome'))
            ->get('dimensionamento_crono_analises')
            ->num_rows();

        if ($count > 0) {
            exit(json_encode(['erro' => 'O campo "Nome" já existe, ele deve ser único.']));
        }
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_crono_analises', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir a análise.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function editarExecutores()
    {
        $cronoAnalise = $this->db
            ->select('b.id_depto')
            ->join('dimensionamento_processos b', 'b.id = a.id_processo')
            ->where('a.id', $this->input->post('id'))
            ->get('dimensionamento_crono_analises a')
            ->row();

        if (empty($cronoAnalise)) {
            exit(json_encode(['erro' => 'Análise não encontrada ou excluída recentemente.']));
        }


        $equipes = $this->db
            ->select("id, CONCAT(nome, ' (', total_componentes, ')') AS nome", false)
            ->where('id_depto', $cronoAnalise->id_depto)
            ->order_by('nome', 'asc')
            ->get('dimensionamento_equipes')
            ->result();


        $usuarios = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
            ->where('b.id', $cronoAnalise->id_depto)
            ->where('a.tipo', 'funcionario')
            ->where('a.status', 1)
            ->order_by('a.nome', 'asc')
            ->get('usuarios a')
            ->result();


        $executores = $this->db
            ->select('id_equipe, id_usuario')
            ->where('id_crono_analise', $this->input->post('id'))
            ->get('dimensionamento_executores')
            ->result();

        $data = [
            'equipes' => form_multiselect(
                '',
                array_column($equipes, 'nome', 'id'),
                array_column($executores, 'id_equipe')
            ),
            'executores' => form_multiselect(
                '',
                array_column($usuarios, 'nome', 'id'),
                array_column($executores, 'id_usuario')
            )
        ];


        echo json_encode($data);
    }

    //==========================================================================
    public function salvarExecutores()
    {
        $idCronoAnalise = $this->input->post('id_crono_analise');
        $tipo = $this->input->post('tipo');
        $equipes = $this->input->post('id_equipe');
        $executores = $this->input->post('id_usuario');
        if (empty($equipes)) {
            $equipes = [0];
        }
        if (empty($executores)) {
            $executores = [0];
        }

        $this->db->trans_start();

        $this->db
            ->where('id_crono_analise', $idCronoAnalise)
            ->where('tipo', 'E')
            ->where_not_in('id_usuario', $equipes)
            ->delete('dimensionamento_executores');

        $this->db
            ->where('id_crono_analise', $idCronoAnalise)
            ->where('tipo', 'C')
            ->where_not_in('id_usuario', $executores)
            ->delete('dimensionamento_executores');

        if ($tipo === 'E') {
            $data = $this->db
                ->select("'{$idCronoAnalise}' AS id_crono_analise", false)
                ->select("'{$tipo}' AS tipo", false)
                ->select('a.id AS id_equipe')
                ->join('dimensionamento_executores b', "b.id_equipe = a.id AND b.id_crono_analise = '{$idCronoAnalise}'", 'left')
                ->where_in('a.id', $equipes)
                ->where('b.id', null)
                ->order_by('a.nome', 'asc')
                ->get('dimensionamento_equipes a')
                ->result_array();
        } elseif ($tipo === 'C') {
            $data = $this->db
                ->select("'{$idCronoAnalise}' AS id_crono_analise", false)
                ->select("'{$tipo}' AS tipo", false)
                ->select('a.id AS id_usuario')
                ->join('dimensionamento_executores b', "b.id_usuario = a.id AND b.id_crono_analise = '{$idCronoAnalise}'", 'left')
                ->where_in('a.id', $executores)
                ->where('b.id', null)
                ->order_by('a.nome', 'asc')
                ->get('usuarios a')
                ->result_array();
        }

        if ($data) {
            $this->db->insert_batch('dimensionamento_executores', $data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao salvar os executores da análise.']));
        }

        echo json_encode(['status' => true]);
    }


}
