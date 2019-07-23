<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supervisores extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->session->userdata('nivel'), array(0, 4, 7, 8, 9, 10))) {
            redirect(site_url('home'));
        }
    }

    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');

        $data = array();

        $this->db->select("CONCAT(ano, '/', semestre) AS ano_semestre", false);
        $this->db->order_by('ano', 'desc');
        $this->db->order_by('semestre', 'desc');
        $semestre = array_column($this->db->get('ei_coordenacao')->result(), 'ano_semestre', 'ano_semestre');
        $data['busca_anoSemestre'] = array('' => 'Todos') + $semestre;


        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $empresa);
        $this->db->group_by('b.id');
        $this->db->order_by('b.nome', 'asc');
        $supervisores = array_column($this->db->get('ei_coordenacao a')->result(), 'nome', 'id');
        $data['busca_supervisor'] = array('' => 'Todos') + $supervisores;


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('ei_supervisores c', 'c.id_escola = a.id');
        $this->db->join('ei_coordenacao d', 'd.id = c.id_coordenacao');
        $this->db->where('a.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $diretorias = array_column($this->db->get('ei_diretorias a')->result(), 'nome', 'id');
        $data['busca_diretoria'] = array('' => 'Todos') + $diretorias;


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_supervisores c', 'c.id_escola = a.id');
        $this->db->join('ei_coordenacao d', 'd.id = c.id_coordenacao');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $escolas = array_column($this->db->get('ei_escolas a')->result(), 'nome', 'id');
        $data['busca_escola'] = array('' => 'Todas') + $escolas;


        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $deptos = $this->db->get('empresa_departamentos')->result();
        $data['deptos'] = array('' => 'selecione...') + array_column($deptos, 'nome', 'id');

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $areas = $this->db->get('empresa_areas a')->result();
        $data['areas'] = array('' => 'selecione...') + array_column($areas, 'nome', 'id');

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $setores = $this->db->get('empresa_setores a')->result();
        $data['setores'] = array('' => 'selecione...') + array_column($setores, 'nome', 'id');

        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->where('tipo', 'funcionario');
        $this->db->where('status', 1);
        $this->db->order_by('nome', 'asc');
        $supervisores = $this->db->get('usuarios')->result();
        $data['supervisores'] = array('' => 'selecione...') + array_column($supervisores, 'nome', 'id');

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('usuarios c', 'c.funcao = a.nome AND c.empresa = b.id_empresa', 'left');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('c.depto', 'Educação Inclusiva');
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $funcoes = $this->db->get('empresa_funcoes a')->result();
        $data['funcoes'] = array_column($funcoes, 'nome', 'id');

        $data['diretorias'] = array('' => 'selecione...') + $diretorias;
        $data['escolas'] = array();

        $this->load->view('ei/supervisores', $data);
    }


    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $busca = $this->input->post('busca');
        $filtro = array();


        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $empresa);
        if ($busca['ano_semestre']) {
            $this->db->where("CONCAT(a.ano, '/', a.semestre) =", $busca['ano_semestre']);
        }
        $this->db->group_by('b.id');
        $this->db->order_by('b.nome', 'asc');
        $supervisores = array_column($this->db->get('ei_coordenacao a')->result(), 'nome', 'id');
        $filtro['supervisor'] = array('' => 'Todos') + $supervisores;


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('ei_supervisores c', 'c.id_escola = b.id');
        $this->db->join('ei_coordenacao d', 'd.id = c.id_coordenacao');
        $this->db->where('a.id_empresa', $empresa);
        if ($busca['ano_semestre']) {
            $this->db->where("CONCAT(d.ano, '/', d.semestre) =", $busca['ano_semestre']);
        }
        if ($busca['supervisor']) {
            $this->db->where('d.id', $busca['supervisor']);
        }
        $this->db->order_by('a.nome', 'asc');
        $diretorias = array_column($this->db->get('ei_diretorias a')->result(), 'nome', 'id');
        $filtro['diretoria'] = array('' => 'Todas') + $diretorias;


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_supervisores c', 'c.id_escola = a.id');
        $this->db->join('ei_coordenacao d', 'd.id = c.id_coordenacao');
        $this->db->where('b.id_empresa', $empresa);
        if ($busca['ano_semestre']) {
            $this->db->where("CONCAT(d.ano, '/', d.semestre) =", $busca['ano_semestre']);
        }
        if ($busca['supervisor']) {
            $this->db->where('d.id', $busca['supervisor']);
        }
        if ($busca['diretoria']) {
            $this->db->where('b.id', $busca['diretoria']);
        }
        $this->db->order_by('a.nome', 'asc');
        $escolas = array_column($this->db->get('ei_escolas a')->result(), 'nome', 'id');
        $filtro['escola'] = array('' => 'Todas') + $escolas;


        $data['supervisor'] = form_dropdown('busca[supervisor]', $filtro['supervisor'], $busca['supervisor'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['diretoria'] = form_dropdown('busca[diretoria]', $filtro['diretoria'], $busca['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['escola'] = form_dropdown('busca[escola]', $filtro['escola'], $busca['escola'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }


    public function atualizar_supervisores($busca = array())
    {
        $empresa = $this->session->userdata('empresa');
        $retorno = count($busca);
        if (empty($busca)) {
            $busca['depto'] = $this->input->post('depto');
            $busca['area'] = $this->input->post('area');
            $busca['setor'] = $this->input->post('setor');
            $busca['supervisor'] = $this->input->post('supervisor');
        }
        $filtro = array();


        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('b.id', $busca['depto']);
        }
        $this->db->order_by('a.nome', 'asc');
        $areas = $this->db->get('empresa_areas a')->result();
        $filtro['areas'] = array('' => 'selecione...') + array_column($areas, 'nome', 'id');

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('c.id', $busca['depto']);
        }
        if ($busca['area']) {
            $this->db->where('b.id', $busca['area']);
        }
        $this->db->order_by('a.nome', 'asc');
        $setores = $this->db->get('empresa_setores a')->result();
        $filtro['setores'] = array('' => 'selecione...') + array_column($setores, 'nome', 'id');

        $this->db->select('a.id, a.nome');
        $this->db->where('a.empresa', $empresa);
        $this->db->where('a.tipo', 'funcionario');
        $this->db->where('a.status', 1);
        if ($busca['depto']) {
            $this->db->join('empresa_departamentos b', 'b.nome = a.depto');
            $this->db->where('b.id', $busca['depto']);
        }
        if ($busca['area']) {
            $this->db->join('empresa_areas c', 'c.nome = a.area');
            $this->db->where('c.id', $busca['area']);
        }
        if ($busca['setor']) {
            $this->db->join('empresa_setores d', 'd.nome = a.setor');
            $this->db->where('d.id', $busca['setor']);
        }
        $this->db->order_by('a.nome', 'asc');
        $supervisores = $this->db->get('usuarios a')->result();
        $filtro['supervisores'] = array('' => 'selecione...') + array_column($supervisores, 'nome', 'id');


        $data['area'] = form_dropdown('area', $filtro['areas'], $busca['area'], 'id="area" class="form-control"');
        $data['setor'] = form_dropdown('setor', $filtro['setores'], $busca['setor'], 'id="setor" class="form-control"');
        $data['supervisor'] = form_dropdown('id_usuario', $filtro['supervisores'], $busca['supervisor'], 'id="supervisor" class="form-control"');

        if ($retorno) {
            return $data;
        }
        echo json_encode($data);
    }


    public function atualizar_unidades()
    {
        $empresa = $this->session->userdata('empresa');
        $id_diretoria = $this->input->post('id_diretoria');
        $escolasSelecionadas = $this->input->post('id_escolas');

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id_empresa', $empresa);
        if ($id_diretoria) {
            $this->db->where('b.id', $id_diretoria);
        }
        if ($escolasSelecionadas) {
            $this->db->or_where_in('a.id', $escolasSelecionadas);
        }
        $this->db->order_by('a.nome', 'asc');
        $escolas = array_column($this->db->get('ei_escolas a')->result(), 'nome', 'id');

        $data['escolas'] = form_multiselect('id_escola[]', $escolas, $escolasSelecionadas, 'id="id_escolas" class="form-control demo2"');

        echo json_encode($data);
    }


    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.ano_semestre,
                       s.id_supervisor,
                       s.ordem_escola,
                       s.escola
                FROM (SELECT a.id, 
                             b.nome,
                             CONCAT(a.ano, '/', a.semestre) AS ano_semestre,
                             CONCAT_WS(' - ', d.codigo, d.nome) AS escola,
                             IF(CHAR_LENGTH(d.codigo) > 0, d.codigo, CAST(d.nome AS DECIMAL)) AS ordem_escola,
                             c.id AS id_supervisor
                      FROM ei_coordenacao a 
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_usuario
                      LEFT JOIN ei_supervisores c ON 
                                c.id_coordenacao = a.id
                      LEFT JOIN ei_escolas d ON 
                                d.id = c.id_escola
                      LEFT JOIN ei_diretorias e ON 
                                e.id = d.id_diretoria
                      WHERE a.is_supervisor = 1 AND 
                            b.empresa = {$this->session->userdata('empresa')}";
        if (!empty($busca['ano_semestre'])) {
            $sql .= " AND CONCAT(a.ano, '/', a.semestre) = '{$busca['ano_semestre']}'";
        }
        if (!empty($busca['supervisor'])) {
            $sql .= " AND a.id = '{$busca['supervisor']}'";
        }
        if (!empty($busca['diretoria'])) {
            $sql .= " AND e.depto = '{$busca['diretoria']}'";
        }
        if (!empty($busca['escola'])) {
            $sql .= " AND d.id = '{$busca['escola']}'";
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.id_supervisor', 's.escola');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " 
                        WHERE {$column} LIKE '%{$post['search']['value']}%'";
                }
            }
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $ei) {
            $row = array();
            $row[] = $ei->nome;
            $row[] = $ei->ano_semestre;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_supervisor(' . $ei->id . ')" title="Editar supervisor"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_supervisor(' . $ei->id . ')" title="Excluir supervisor"><i class="glyphicon glyphicon-trash"></i></button>
                      <button type="button" class="btn btn-sm btn-info" onclick="vincular_unidades(' . $ei->id . ')" title="Vincular unidades">Unidades</button>
                    ';
            $row[] = $ei->escola;

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');

        $data = $this->db
            ->select('*', false)
            ->select("TIME_FORMAT(carga_horaria, '%H:%i') AS carga_horaria_1", false)
            ->where('id', $id)
            ->get('ei_coordenacao')
            ->row();

        $busca = array(
            'depto' => $data->depto,
            'area' => $data->area,
            'setor' => $data->setor,
            'supervisor' => $data->id_usuario,
        );
        $campos = $this->atualizar_supervisores($busca);

        $data->area = $campos['area'];
        $data->setor = $campos['setor'];
        $data->id_usuario = $campos['supervisor'];
        $data->carga_horaria = $data->carga_horaria_1;
        unset($data->carga_horaria_1);

        $this->db->select('a.id, a.nome, d.funcao');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('usuarios c', 'c.funcao = a.nome AND c.empresa = b.id_empresa', 'left');
        $this->db->join('ei_funcoes_supervisionadas d', "d.funcao = a.id AND d.id_supervisor = {$id}", 'left');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('c.depto', 'Educação Inclusiva');
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $rowFuncoes = $this->db->get('empresa_funcoes a')->result();
        $funcoes = array_column($rowFuncoes, 'nome', 'id');
        $fucoesSelecionadas = array_filter(array_column($rowFuncoes, 'funcao')) + [];

        $data->cargos = form_multiselect('funcoes[]', $funcoes, $fucoesSelecionadas, 'id="funcoes" class="form-control demo1"');

        echo json_encode($data);
    }


    public function ajax_editUnidades()
    {
        $empresa = $this->session->userdata('empresa');
        $id = $this->input->post('id');

        $this->db->select('a.id, b.nome AS nome_supervisor');
        $this->db->select("CONCAT(a.ano, '/', a.semestre) AS ano_semestre", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id', $id);
        $data = $this->db->get('ei_coordenacao a')->row_array();


        $this->db->select('a.id, c.id AS id_supervisor');
        $this->db->select(["CONCAT_WS(' - ', a.codigo, a.nome) AS nome"], false);
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_supervisores c', "c.id_escola = a.id AND c.id_coordenacao = '{$data['id']}'", 'left');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->order_by('IF(CHAR_LENGTH(a.codigo) > 0, a.codigo, CAST(a.nome AS DECIMAL)) ASC', null, false);
        $rowsEscolas = $this->db->get('ei_escolas a')->result();

        $escolas = array_column($rowsEscolas, 'nome', 'id');
        $escolasSelecionadas = array_keys(array_filter(array_column($rowsEscolas, 'id_supervisor', 'id')));


        $data['escolas'] = form_multiselect('id_escola[]', $escolas, $escolasSelecionadas, 'id="id_escolas" class="form-control demo2"');

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        unset($data['id']);
        if (empty($data['id_usuario'])) {
            exit(json_encode(array('erro' => 'O supervisor não pode ficar em branco.')));
        }
        if (empty($data['depto'])) {
            exit(json_encode(array('erro' => 'O departamento não pode ficar em branco.')));
        }
        if (empty($data['area'])) {
            exit(json_encode(array('erro' => 'A área não pode ficar em branco.')));
        }
        if (empty($data['setor'])) {
            exit(json_encode(array('erro' => 'O setor não pode ficar em branco.')));
        }
        if (strlen($data['ano']) == 0) {
            exit(json_encode(array('erro' => 'O ano não pode ficar em branco.')));
        } elseif (!checkdate(1, 1, $data['ano'])) {
            exit(json_encode(array('erro' => 'O ano possui formato inválido.')));
        }

        $this->db->where('id_usuario', $data['id_usuario']);
        $this->db->where('ano', $data['ano']);
        $this->db->where('semestre', $data['semestre']);
        $count = $this->db->get('ei_coordenacao')->num_rows();
        if ($count) {
            exit(json_encode(array('erro' => 'O supervisor já possui cadastro no ano e semestres selecionados.')));
        }

        $funcoes = is_array($data['funcoes']) ? $data['funcoes'] : [0];
        $this->db->select('id, id_cargo');
        $this->db->where_in('id', $funcoes);
        $empresaFuncoes = $this->db->get('empresa_funcoes')->result();
        unset($data['funcoes']);

        $this->db->trans_start();
        $this->db->insert('ei_coordenacao', $data);
        $idSupervisor = $this->db->insert_id();

        $data2 = [];
        foreach ($empresaFuncoes as $funcao) {
            $data2[] = array(
                'id_supervisor' => $idSupervisor,
                'cargo' => $funcao->id_cargo,
                'funcao' => $funcao->id
            );
        }

        if ($data2) {
            $this->db->insert_batch('ei_funcoes_supervisionadas', $data2);
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if (empty($data['id_usuario'])) {
            exit(json_encode(array('erro' => 'O supervisor não pode ficar em branco.')));
        }
        if (empty($data['depto'])) {
            exit(json_encode(array('erro' => 'O departamento não pode ficar em branco.')));
        }
        if (empty($data['area'])) {
            exit(json_encode(array('erro' => 'A área não pode ficar em branco.')));
        }
        if (empty($data['setor'])) {
            exit(json_encode(array('erro' => 'O setor não pode ficar em branco.')));
        }
        if (strlen($data['ano']) == 0) {
            exit(json_encode(array('erro' => 'O ano não pode ficar em branco.')));
        } elseif (!checkdate(1, 1, $data['ano'])) {
            exit(json_encode(array('erro' => 'O ano possui formato inválido.')));
        }

        $this->db->where('id !=', $data['id']);
        $this->db->where('id_usuario', $data['id_usuario']);
        $this->db->where('ano', $data['ano']);
        $this->db->where('semestre', $data['semestre']);
        $count = $this->db->get('ei_coordenacao')->num_rows();
        if ($count) {
            exit(json_encode(array('erro' => 'O supervisor já possui cadastro no ano e semestres selecionados.')));
        }


        $id = $this->input->post('id');
        $this->db->select('funcao');
        $this->db->where('id_supervisor', $id);
        $funcoesSupervisionadas = $this->db->get('ei_funcoes_supervisionadas')->result();
        $funcoesExistentes = array_column($funcoesSupervisionadas, 'funcao');

        $funcoesNovas = is_array($data['funcoes']) ? array_diff($data['funcoes'], $funcoesExistentes) + [0] : [0];
        unset($data['id'], $data['funcoes']);

        $this->db->select('id, id_cargo');
        $this->db->where_in('id', $funcoesNovas);
        $empresaFuncoes = $this->db->get('empresa_funcoes')->result();


        $this->db->trans_start();
        $this->db->update('ei_coordenacao', $data, array('id' => $id));

        $this->db->where('id_supervisor', $id);
        $this->db->where_not_in('funcao', $funcoesExistentes);
        $this->db->delete('ei_funcoes_supervisionadas');

        $data2 = [];
        foreach ($empresaFuncoes as $funcao) {
            $data2[] = array(
                'id_supervisor' => $id,
                'cargo' => $funcao->id_cargo,
                'funcao' => $funcao->id
            );
        }

        if ($data2) {
            $this->db->insert_batch('ei_funcoes_supervisionadas', $data2);
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function salvarEscolas()
    {
        $id_coordenacao = $this->input->post('id_coordenacao');
        $id_escolas = $this->input->post('id_escolas');

        $this->db->select('b.id');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id', $id_coordenacao);
        $usuario = $this->db->get('ei_coordenacao a')->row();

        $data = array(
            'id_coordenacao' => $id_coordenacao,
            'id_supervisor' => $usuario->id
        );

        $this->db->trans_start();

        $this->db->where('id_coordenacao', $id_coordenacao);
        $this->db->where_not_in('id_escola', $id_escolas);
        $this->db->delete('ei_supervisores');


        $this->db->select('id, id_escola');
        $this->db->where('id_coordenacao', $id_coordenacao);
        $escolas = array_column($this->db->get('ei_supervisores')->result(), 'id_escola', 'id');

        foreach ($id_escolas as $id => $id_escola) {
            $data['id_escola'] = $id_escola;
            if (in_array($id_escola, $escolas)) {
                $this->db->update('ei_supervisores', $data, array('id' => $id));
            } else {
                $this->db->insert('ei_supervisores', $data);
            }
        }


        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_coordenacao', array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }


    public function ajax_deleteEscola()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_supervisores', array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }


    public function pdf()
    {
        $empresa = $this->session->userdata('empresa');
        $this->load->library('m_pdf');

        $stylesheet = 'table { font-size: 12px; } ';
        $stylesheet .= 'table tr th, table tr td { border: 1px solid #fff; } ';
//        $this->m_pdf->pdf->setTopMargin(60);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);

        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $empresa))->row();

        $sql = "SELECT b.nome,
                             CONCAT(a.ano, '/', a.semestre) AS ano_semestre,
                             d.nome AS escola,
                             GROUP_CONCAT(h.nome ORDER BY h.nome SEPARATOR ', ') AS funcao
                      FROM ei_coordenacao a 
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_usuario
                      LEFT JOIN ei_supervisores c ON 
                                c.id_coordenacao = a.id
                      LEFT JOIN ei_escolas d ON 
                                d.id = c.id_escola
                      LEFT JOIN ei_diretorias e ON 
                                e.id = d.id_diretoria
                      LEFT JOIN ei_funcoes_supervisionadas f ON 
                                f.id_supervisor = a.id
                      LEFT JOIN empresa_funcoes h ON h.id = f.funcao
                      WHERE a.is_supervisor = 1 AND 
                            b.empresa = {$empresa}
                      GROUP BY a.id, d.id 
                      ORDER BY b.nome ASC, a.ano ASC, a.semestre ASC";
        $data = $this->db->query($sql)->result_array();

        $cabecalho = '<table width="100%">
            <thead>
            <tr>
                <td>
                    <img src="' . base_url('imagens/usuarios/' . $usuario->foto) . '" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="vertical-align: top;" width="100%">
                    <p>
                        <img src="' . base_url('imagens/usuarios/' . $usuario->foto_descricao) . '" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" style="padding-bottom: 12px;  text-align: center; border-top: 5px solid #ddd; border-bottom: 2px solid #ddd; padding:5px;">
                    <h1 style="font-weight: bold;">VÍNCULO - SUPERVISORES x UNIDADES DE ENSINO</h1>
                </td>
            </tr>
            </tbody>
        </table>
        <br><br>';

        $table = [['Supervisor', 'Ano/semestre', 'Unidade de Ensino', 'Funções associadas ao supervisor']];
        foreach ($data as $row) {
            $table[] = $row;
        }
        $this->load->library('table');

        $html = $cabecalho . $this->table->generate($table);

        $this->m_pdf->pdf->writeHTML($html);

        $this->m_pdf->pdf->Output("EI_supervisores.pdf", 'D');
    }

}
