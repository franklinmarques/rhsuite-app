<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estruturas extends MY_Controller
{
    //==========================================================================
    public function index()
    {
        $this->gerenciar();
    }

    //==========================================================================
    public function processos()
    {
        $this->gerenciar(0);
    }

    //==========================================================================
    public function atividades()
    {
        $this->gerenciar(1);
    }

    //==========================================================================
    public function etapas()
    {
        $this->gerenciar(2);
    }

    //==========================================================================
    public function itens()
    {
        $this->gerenciar(3);
    }

    //==========================================================================
    private function gerenciar($indice = 0)
    {
        $data = $this->input->get();
        $data['empresa'] = $this->session->userdata('empresa');
        $data['indice'] = $indice;

        if ($this->session->userdata('tipo') == 'funcionario') {
            $usuario = $this->db
                ->select('b.id AS id_depto, c.id AS id_area, d.id AS id_setor')
                ->select('b.nome AS depto, c.nome AS area, d.nome AS setor')
                ->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto')
                ->join('empresa_areas c', 'c.id = a.id_area OR c.nome = a.area')
                ->join('empresa_setores d', 'd.id = a.id_setor OR d.nome = a.setor')
                ->where('a.id', $this->session->userdata('id'))
                ->get('usuarios a')
                ->row();

            $data['depto'] = [$usuario->id_depto => $usuario->depto];
            $data['area'] = [$usuario->id_area => $usuario->area];
            $data['setor'] = [$usuario->id_setor => $usuario->setor];
        } else {
            $deptos = $this->db
                ->select('id, nome')
                ->where('id_empresa', $this->session->userdata('empresa'))
                ->order_by('nome', 'asc')
                ->get('empresa_departamentos')
                ->result();

            $data['depto'] = ['' => 'Todos'] + array_column($deptos, 'nome', 'id');
            $data['area'] = ['' => 'Todas'];
            $data['setor'] = ['' => 'Todos'];
        }

        $this->load->view('dimensionamento/estruturas', $data);
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');

        $rowAreas = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_departamentos b', 'b.id = a.id_departamento')
            ->where('b.id_empresa', $this->session->userdata('empresa'))
            ->where('b.id', $depto)
            ->order_by('a.nome', 'asc')
            ->get('empresa_areas a')
            ->result();

        $areas = array_column($rowAreas, 'nome', 'id');

        $rowSetores = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->join('empresa_departamentos c', 'c.id = b.id_departamento')
            ->where('c.id_empresa', $this->session->userdata('empresa'))
            ->where('c.id', $depto)
            ->where('b.id', $area)
            ->order_by('a.nome', 'asc')
            ->get('empresa_setores a')
            ->result();

        $setores = array_column($rowSetores, 'nome', 'id');

        $data['area'] = form_dropdown('', ['' => 'Todas'] + $areas, $area);
        $data['setor'] = form_dropdown('', ['' => 'Todos'] + $setores, $setor);

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxListProcessos()
    {
        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');

        $this->db->select('nome, id');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        if ($idDepto) {
            $this->db->where('id_depto', $idDepto);
        }
        if ($idArea) {
            $this->db->where('id_area', $idArea);
        }
        if ($idSetor) {
            $this->db->where('id_setor', $idSetor);
        }
        $query = $this->db->get('dimensionamento_processos');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();
        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                '<button class="btn btn-sm btn-info" onclick="edit_processo(' . $row->id . ')" title="Editar processo"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_processo(' . $row->id . ')" title="Excluir processo"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" onclick="next_atividade(' . $row->id . ')" title="Atividades"><i class="glyphicon glyphicon-list"></i> Atividades</button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxListAtividades()
    {
        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');
        $idProcesso = $this->input->post('id_processo');

        $this->db->select('a.nome AS processo, b.nome, b.id');
        $this->db->join('dimensionamento_atividades b', 'b.id_processo = a.id', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        if ($idDepto) {
            $this->db->where('a.id_depto', $idDepto);
        }
        if ($idArea) {
            $this->db->where('a.id_area', $idArea);
        }
        if ($idSetor) {
            $this->db->where('a.id_setor', $idSetor);
        }
        if ($idProcesso) {
            $this->db->where('a.id', $idProcesso);
        }
        $query = $this->db->get('dimensionamento_processos a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            if ($row->id) {
                $acoes = '<button class="btn btn-sm btn-info" onclick="edit_atividade(' . $row->id . ')" title="Editar atividade"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_atividade(' . $row->id . ')" title="Excluir atividade"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" onclick="next_etapa(' . $row->id . ')" title="Etapas"><i class="glyphicon glyphicon-list"></i> Etapas</button>';
            } else {
                $acoes = '<button class="btn btn-sm btn-info" disabled title="Editar atividade"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" disabled title="Excluir atividade"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" disabled title="Etapas"><i class="glyphicon glyphicon-list"></i> Etapas</button>';
            }

            $data[] = array(
                $row->processo,
                $row->nome,
                $acoes
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxListEtapas()
    {
        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');
        $idProcesso = $this->input->post('id_processo');
        $idAtividade = $this->input->post('id_atividade');

        $this->db->select('a.nome AS processo, b.nome AS atividade, c.nome');
        $this->db->select("(CASE c.grau_complexidade WHEN 1 THEN 'Extremamente baixa'
                                                     WHEN 2 THEN 'Baixa'
                                                     WHEN 3 THEN 'Média'
                                                     WHEN 4 THEN 'Alta'
                                                     WHEN 5 THEN 'Extremamente alta'
                                                     END) AS grau_complexidade, c.id", false);
        $this->db->join('dimensionamento_atividades b', 'b.id_processo = a.id');
        $this->db->join('dimensionamento_etapas c', 'c.id_atividade = b.id', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        if ($idDepto) {
            $this->db->where('a.id_depto', $idDepto);
        }
        if ($idArea) {
            $this->db->where('a.id_area', $idArea);
        }
        if ($idSetor) {
            $this->db->where('a.id_setor', $idSetor);
        }
        if ($idProcesso) {
            $this->db->where('a.id', $idProcesso);
        }
        if ($idAtividade) {
            $this->db->where('b.id', $idAtividade);
        }
        $query = $this->db->get('dimensionamento_processos a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            if ($row->id) {
                $acoes = '<button class="btn btn-sm btn-info" onclick="edit_etapa(' . $row->id . ')" title="Editar etapa"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_etapa(' . $row->id . ')" title="Excluir etapa"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" onclick="next_item(' . $row->id . ')" title="Itens"><i class="glyphicon glyphicon-list"></i> Itens</button>';
            } else {
                $acoes = '<button class="btn btn-sm btn-info" disabled title="Editar etapa"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" disabled title="Excluir etapa"><i class="glyphicon glyphicon-trash"></i></button>
                 <button class="btn btn-sm btn-primary" disabled title="Itens"><i class="glyphicon glyphicon-list"></i> Itens</button>';
            }

            $data[] = array(
                $row->processo,
                $row->atividade,
                $row->nome,
                $row->grau_complexidade,
                $acoes
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxListItens()
    {
        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');
        $idProcesso = $this->input->post('id_processo');
        $idAtividade = $this->input->post('id_atividade');
        $idEtapa = $this->input->post('id_etapa');

        $this->db->select('a.nome AS processo, b.nome AS atividade, c.nome AS etapa, d.nome, d.descricao, d.id');
        $this->db->join('dimensionamento_atividades b', 'b.id_processo = a.id');
        $this->db->join('dimensionamento_etapas c', 'c.id_atividade = b.id');
        $this->db->join('dimensionamento_itens d', 'd.id_etapa = c.id', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        if ($idDepto) {
            $this->db->where('a.id_depto', $idDepto);
        }
        if ($idArea) {
            $this->db->where('a.id_area', $idArea);
        }
        if ($idSetor) {
            $this->db->where('a.id_setor', $idSetor);
        }
        if ($idProcesso) {
            $this->db->where('a.id', $idProcesso);
        }
        if ($idAtividade) {
            $this->db->where('b.id', $idAtividade);
        }
        if ($idEtapa) {
            $this->db->where('c.id', $idEtapa);
        }
        $query = $this->db->get('dimensionamento_processos a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            if ($row->id) {
                $acoes = '<button class="btn btn-sm btn-info" onclick="edit_item(' . $row->id . ')" title="Editar item"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_item(' . $row->id . ')" title="Excluir item"><i class="glyphicon glyphicon-trash"></i></button>';
            } else {
                $acoes = '<button class="btn btn-sm btn-info" disabled title="Editar item"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" disabled title="Excluir item"><i class="glyphicon glyphicon-trash"></i></button>';
            }

            $data[] = array(
                $row->processo,
                $row->atividade,
                $row->etapa,
                $row->nome,
                $row->descricao,
                $acoes
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxEditProcesso()
    {
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('dimensionamento_processos')->row();
        if (empty($data)) {
            exit(json_encode(['erro' => 'Processo não encontrado ou excluído recentemente.']));
        }
        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEditAtividade()
    {
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('dimensionamento_atividades')->row();
        if (empty($data)) {
            exit(json_encode(['erro' => 'Atividade não encontrada ou excluída recentemente.']));
        }
        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEditEtapa()
    {
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('dimensionamento_etapas')->row();
        if (empty($data)) {
            exit(json_encode(['erro' => 'Etapa não encontrada ou excluída recentemente.']));
        }

        $data->peso_item = str_replace('.', ',', $data->peso_item);

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEditItem()
    {
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('dimensionamento_itens')->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Item não encontrado ou excluído recentemente.']));
        }

        $data->valor = str_replace('.', ',', $data->valor);

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAddProcesso()
    {
        $this->validarNome('processos', $this->input->post('nome'));
        $this->db->trans_start();
        $this->db->insert('dimensionamento_processos', $this->input->post());
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar o processo.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxAddAtividade()
    {
        $this->validarNome('atividades', $this->input->post('nome'));
        $this->db->trans_start();
        $this->db->insert('dimensionamento_atividades', $this->input->post());
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar a atividade.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxAddEtapa()
    {
        $this->validarNome('etapas', $this->input->post('nome'));

        $data = $this->input->post();
        if (strlen($data['peso_item'])) {
            $data['peso_item'] = str_replace(',', '.', $data['peso_item']);
        } else {
            $data['peso_item'] = null;
        }

        $this->db->trans_start();
        $this->db->insert('dimensionamento_etapas', $this->input->post());
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar a etapa.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxAddItem()
    {
        $this->validarNome('itens', $this->input->post('nome'));

        $data = $this->input->post();
        if (strlen($data['valor'])) {
            $data['valor'] = str_replace(['.', ','], ['', '.'], $data['valor']);
        } else {
            $data['valor'] = null;
        }

        $this->db->trans_start();
        $this->db->insert('dimensionamento_itens', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar o item.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdateProcesso()
    {
        $data = $this->input->post();
        $this->validarNome('processos', $data['nome']);
        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_processos', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar o processo.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdateAtividade()
    {
        $data = $this->input->post();
        $this->validarNome('atividades', $data['nome']);
        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_atividades', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar a atividade.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdateEtapa()
    {
        $data = $this->input->post();

        $this->validarNome('etapas', $data['nome']);

        if (strlen($data['peso_item'])) {
            $data['peso_item'] = str_replace(',', '.', $data['peso_item']);
        } else {
            $data['peso_item'] = null;
        }

        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_etapas', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar a etapa.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxUpdateItem()
    {
        $data = $this->input->post();
        $this->validarNome('itens', $data['nome']);

        if (strlen($data['valor'])) {
            $data['valor'] = str_replace(['.', ','], ['', '.'], $data['valor']);
        } else {
            $data['valor'] = null;
        }

        $id = $this->input->post('id');
        unset($data['id']);

        $this->db->trans_start();
        $this->db->update('dimensionamento_itens', $data, ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao alterar o item.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    private function validarNome($tabela, $nome = '')
    {
        if (strlen($nome) == 0) {
            exit(json_encode(['erro' => 'O campo "Nome" é obrigatório.']));
        } elseif (strlen($nome) > 255) {
            exit(json_encode(['erro' => 'O campo "Nome" ultrapassou o limite de 255 caracteres.']));
        }

        $this->db->where('id !=', $this->input->post('id'));
        $this->db->where('nome', $this->input->post('nome'));
        switch ($tabela) {
            case 'itens':
                $this->db->where('id_etapa', $this->input->post('id_etapa'));
                break;
            case 'etapas':
                $this->db->where('id_atividade', $this->input->post('id_atividade'));
                break;
            case 'atividades':
                $this->db->where('id_processo', $this->input->post('id_processo'));
                break;
            case 'processos':
                $this->db->where('id_empresa', $this->input->post('id_empresa'));
                $this->db->where('id_depto', $this->input->post('id_depto'));
                $this->db->where('id_area', $this->input->post('id_area'));
                $this->db->where('id_setor', $this->input->post('id_setor'));
                break;
        }
        $count = $this->db->get('dimensionamento_' . $tabela)->num_rows();

        if ($count > 0) {
            exit(json_encode(['erro' => 'O campo "Nome" já existe, ele deve ser único.']));
        }
    }

    //==========================================================================
    public function ajaxDeleteProcesso()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_processos', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir o processo.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxDeleteAtividade()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_atividades', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir a atividade.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxDeleteEtapa()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_etapas', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir a etapa.']));
        }

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function ajaxDeleteItem()
    {
        $this->db->trans_start();
        $this->db->delete('dimensionamento_itens', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Erro ao excluir o item.']));
        }

        echo json_encode(['status' => true]);
    }


}
