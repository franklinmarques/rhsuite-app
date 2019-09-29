<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class OrdemServico extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    //==========================================================================
    public function index()
    {
        $filtro = $this->montarEstrutura();

        $data = array(
            'id_diretoria' => array('' => 'selecione...') + $filtro['diretoria'],
            'id_contrato' => array('' => 'selecione...') + $filtro['contrato'],
            'id_escola' => array('' => 'selecione...'),
            'id_curso' => array('' => 'selecione...'),
            'diretorias' => array('' => 'Todas') + $filtro['diretoria'],
            'contratos' => array('' => 'Todos') + $filtro['contrato'],
            'anoSemestres' => array('' => 'Todos') + $filtro['ano_semestre'],
            'ordensServico' => array('' => 'Todas') + $filtro['ordem_servico'],
            'municipios' => ['' => 'selecione...'] + $filtro['municipio'],
            'escolas' => ['' => 'selecione...'] + $filtro['escola']
        );

        $this->load->view('ei/ordemServico', $data);
    }

    //==========================================================================
    public function atualizarFiltro($busca = array())
    {
        $retorno = count($busca);
        if (empty($busca)) {
            $busca = $this->input->post('busca');
        }

        $filtro = $this->montarEstrutura();
        $contratos = ['' => 'Todos'] + $filtro['contrato'];
        $anosSemestres = ['' => 'Todos'] + $filtro['ano_semestre'];
        $ordemServicos = ['' => 'Todas'] + $filtro['ordem_servico'];
        $municipios = ['' => 'Todos'] + $filtro['municipio'];

        $data['contrato'] = form_dropdown('busca[contrato]', $contratos, $busca['contrato'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['ano_semestre'] = form_dropdown('busca[ano_semestre]', $anosSemestres, $busca['ano_semestre'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['ordem_servico'] = form_dropdown('busca[ordem_servico]', $ordemServicos, $busca['ordem_servico'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['municipio'] = form_dropdown('busca[unicipio]', $municipios, $busca['municipio'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');

        if ($retorno) {
            return $data;
        }
        echo json_encode($data);
    }

    //==========================================================================
    public function atualizarEscolas()
    {
        $idOrdemServico = $this->input->post('id_ordem_servico');
        $municipio = $this->input->post('municipio');
        $escolasSelecionadas = $this->input->post('escolas');

        $this->db->select(["a.id, CONCAT_WS(' - ', a.codigo, a.nome) AS nome"], false);
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_contratos c', 'c.id_cliente = b.id', 'left');
        $this->db->join('ei_ordem_servico d', "d.id_contrato = c.id AND d.id = {$idOrdemServico}", 'left');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        if ($municipio) {
            $this->db->where('a.municipio', $municipio);
        }
        if ($escolasSelecionadas) {
            $this->db->or_where_in('a.id', $escolasSelecionadas);
        }
        $this->db->order_by('a.codigo', 'asc');
        $this->db->order_by('a.nome', 'asc');
        $escolas = array_column($this->db->get('ei_escolas a')->result(), 'nome', 'id');

        /*$this->db->select('id, nome');
        $this->db->where('municipio', $municipio);
        if ($escolas) {
            $this->db->or_where_in('id', $escolas);
        }
        $escolasSelecionadas = array_column($this->db->get('ei_escolas')->result(), 'id_escola');*/

        $data['escola'] = form_multiselect('id_escola[]', $escolas, $escolasSelecionadas, 'id="escola" class="demo1" size="8"');

        echo json_encode($data);
    }

    //==========================================================================
    public function filtrarEscolasSelecionadas()
    {
        $post = $this->input->post();

        $this->db->select('a.id');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_contratos c', 'c.id_cliente = b.id', 'left');
        $this->db->join('ei_ordem_servico d', "d.id_contrato = c.id AND d.id = {$post['id_ordem_servico']}", 'left');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        if ($post['municipio']) {
            $this->db->where('a.municipio', $post['municipio']);
        }
        $this->db->where_in('a.id', $post['escolas']);
        $rows = $this->db->get('ei_escolas a')->result();

        $data['escolas'] = array_column($rows, 'id');

        echo json_encode($data);
    }

    //==========================================================================
    public function atualizarContratos()
    {
        $busca['diretoria'] = $this->input->post('id_diretoria');
        $contrato = $this->input->post('id_contrato');
        $options = array('' => 'selecione...') + $this->montarEstrutura($busca)['contrato'];

        $data['contrato'] = form_dropdown('id_contrato', $options, $contrato, 'id="contrato" class="form-control"');

        echo json_encode($data);
    }

    //==========================================================================
    private function montarEstrutura($busca = array())
    {
        $empresa = $this->session->userdata('empresa');
        if (empty($busca)) {
            $busca = $this->input->post('busca');
        }

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $diretorias = $this->db->get('ei_diretorias')->result();
        $data['diretoria'] = array_column($diretorias, 'nome', 'id');


        $this->db->select('a.id, a.contrato');
        $this->db->join('ei_diretorias b', 'b.id = a.id_cliente');
        $this->db->where('b.id_empresa', $empresa);
        if (!empty($busca['diretoria'])) {
            $this->db->where('b.id', $busca['diretoria']);
        }
        $this->db->order_by('a.contrato', 'asc');
        $contratos = $this->db->get('ei_contratos a')->result();
        $data['contrato'] = array_column($contratos, 'contrato', 'id');


        $this->db->select('a.id, a.nome');
        $this->db->select("CONCAT(a.ano, '/', a.semestre) AS ano_semestre", false);
        $this->db->join('ei_contratos b', 'b.id = a.id_contrato');
        $this->db->join('ei_diretorias c', 'c.id = b.id_cliente');
        $this->db->where('c.id_empresa', $empresa);
        if (!empty($busca['diretoria'])) {
            $this->db->where('c.id', $busca['diretoria']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('b.id', $busca['contrato']);
        }
        $this->db->group_by(['a.ano', 'a.semestre']);
        $this->db->order_by('a.ano', 'desc');
        $this->db->order_by('a.semestre', 'desc');
        $anoSemestre = $this->db->get('ei_ordem_servico a')->result();
        $data['ano_semestre'] = array_column($anoSemestre, 'ano_semestre', 'ano_semestre');


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_contratos b', 'b.id = a.id_contrato');
        $this->db->join('ei_diretorias c', 'c.id = b.id_cliente');
        $this->db->where('c.id_empresa', $empresa);
        if (!empty($busca['diretoria'])) {
            $this->db->where('c.id', $busca['diretoria']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('b.id', $busca['contrato']);
        }
        if (!empty($busca['anoSemestre'])) {
            $this->db->where("CONCAT(a.ano, '/', a.semestre) =", $busca['anoSemestre']);
        }
        $this->db->order_by('a.nome', 'asc');
        $ordemServico = $this->db->get('ei_ordem_servico a')->result();
        $data['ordem_servico'] = array_column($ordemServico, 'nome', 'id');


        $this->db->select('a.municipio');
        $this->db->join('ei_ordem_servico_escolas b', 'b.id_escola = a.id');
        $this->db->join('ei_ordem_servico c', 'c.id = b.id_ordem_servico');
        $this->db->join('ei_contratos d', 'd.id = c.id_contrato');
        $this->db->join('ei_diretorias e', 'e.id = d.id_cliente AND a.id_diretoria = e.id');
        $this->db->where('e.id_empresa', $empresa);
        if (!empty($busca['diretoria'])) {
            $this->db->where('e.id', $busca['diretoria']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('d.id', $busca['contrato']);
        }
        if (!empty($busca['anoSemestre'])) {
            $this->db->where("CONCAT(c.ano, '/', c.semestre) =", $busca['anoSemestre']);
        }
        $this->db->group_by('a.municipio');
        $this->db->order_by('a.municipio', 'asc');
        $municipios = $this->db->get('ei_escolas a')->result();
        $data['municipio'] = array_column($municipios, 'municipio', 'municipio');


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_ordem_servico_escolas b', 'b.id_escola = a.id');
        $this->db->join('ei_ordem_servico c', 'c.id = b.id_ordem_servico');
        $this->db->join('ei_contratos d', 'd.id = c.id_contrato');
        $this->db->join('ei_diretorias e', 'e.id = d.id_cliente AND a.id_diretoria = e.id');
        $this->db->where('e.id_empresa', $empresa);
        if (!empty($busca['diretoria'])) {
            $this->db->where('e.id', $busca['diretoria']);
        }
        if (!empty($busca['contrato'])) {
            $this->db->where('d.id', $busca['contrato']);
        }
        if (!empty($busca['municipio'])) {
            $this->db->where('a.municipio', $busca['municipio']);
        }
        $this->db->order_by('a.nome', 'asc');
        $escolas = $this->db->get('ei_escolas a')->result();
        $data['escola'] = array_column($escolas, 'nome', 'id');


        return $data;
    }

    //==========================================================================
    public function ajaxList()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();


        $sql = "SELECT s.contrato, 
                       s.ordem_servico, 
                       s.ano_semestre, 
                       s.id_os_escola,
                       s.ordem_escola,
                       s.escola, 
                       s.id_escola,
                       s.id
                FROM (SELECT a.id,
                             b.contrato,
                             a.nome AS ordem_servico,
                             CONCAT(a.ano, '/', a.semestre) AS ano_semestre,
                             d.id AS id_os_escola,
                             d.id_escola,
                             CONCAT_WS(' - ', e.codigo, e.nome) AS escola,
                             IF(CHAR_LENGTH(e.codigo) > 0, e.codigo, CAST(e.nome AS DECIMAL)) AS ordem_escola
                      FROM ei_ordem_servico a 
                      INNER JOIN ei_contratos b 
                                 ON b.id = a.id_contrato
                      INNER JOIN ei_diretorias c
                                 ON c.id = b.id_cliente
                      LEFT JOIN ei_ordem_servico_escolas d 
                                ON d.id_ordem_servico = a.id
                      LEFT JOIN ei_escolas e
                                ON e.id = d.id_escola
                                AND e.id_diretoria = c.id
                      WHERE c.id_empresa = '{$this->session->userdata('empresa')}'";
        if ($busca['diretoria']) {
            $sql .= " AND c.id = '{$busca['diretoria']}'";
        }
        if ($busca['contrato']) {
            $sql .= " AND b.id = '{$busca['contrato']}'";
        }
        if ($busca['ano_semestre']) {
            $sql .= " AND CONCAT(a.ano, '/', a.semestre) = '{$busca['ano_semestre']}'";
        }
        if ($busca['ordem_servico']) {
            $sql .= " AND a.id = '{$busca['ordem_servico']}'";
        }
        if ($busca['municipio']) {
            $sql .= " AND e.municipio = '" . addslashes($busca['municipio']) . "'";
        }
        if ($busca['escola']) {
            $sql .= " AND e.id = '" . addslashes($busca['escola']) . "'";
        }
        $sql .= ') s
        ORDER BY s.contrato ASC, 
                 s.ordem_servico ASC, 
                 s.ano_semestre ASC, 
                 s.ordem_escola ASC';

        $this->load->library('dataTables');
        $output = $this->datatables->query($sql);


        $idEscolas = array_unique(array_column($output->data, 'id_escola'));

        $this->db->select('DISTINCT(id_aluno) AS id_aluno', false);
        $this->db->where_in('id_ordem_servico_escola', array_column($output->data, 'id_os_escola') + [0]);
        $totalAlunos = $this->db->get('ei_ordem_servico_alunos')->num_rows();


        $data = array();
        foreach ($output->data as $ei) {
            $row = array();
            $row[] = $ei->contrato;
            $row[] = $ei->ordem_servico;
            $row[] = $ei->ano_semestre;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_os(' . $ei->id . ')" title="Editar área/cliente"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_os(' . $ei->id . ')" title="Excluir área/cliente"><i class="glyphicon glyphicon-trash"></i> </button>
                      <button type="button" class="btn btn-sm btn-info" onclick="add_escola(' . $ei->id . ')" title="Gerenciar unidade de ensino">Escolas</button>
                     ';
            $row[] = $ei->escola;
            if ($ei->id_os_escola) {
                $row[] = '
                          <!--<button type="button" class="btn btn-sm btn-info" onclick="add_curso(' . ')" title="Adicionar curso"><i class="glyphicon glyphicon-plus"></i> Curso</button>-->
                          <button type="button" class="btn btn-sm btn-primary" onclick="alunos(' . $ei->id_os_escola . ')" title="Gerenciar alunos">Alunos</button>
                          <button type="button" class="btn btn-sm btn-primary" onclick="profissionais(' . $ei->id_os_escola . ')" title="Gerenciar profissionais">Cuidadores/Horários</button>
                          <button type="button" class="btn btn-sm btn-primary" title="Relatorio"><i class="glyphicon glyphicon-print"></i></button>
                         ';
            } else {
                $row[] = '
                         <!--<button type="button" class="btn btn-sm btn-info disabled" title="Adicionar curso"><i class="glyphicon glyphicon-plus"></i> Curso</button>-->
                          <button type="button" class="btn btn-sm btn-primary disabled" title="Gerenciar aluno">Alunos</button>
                          <button type="button" class="btn btn-sm btn-primary disabled" title="Gerenciar profissionais">Cuidadores/Horários</button>
                          <button type="button" class="btn btn-sm btn-primary disabled" title="Relatorio"><i class="glyphicon glyphicon-print"></i></button>
                         ';
            }

            $data[] = $row;
        }

        $output->data = $data;


        $output->total_escolas = count($idEscolas);
        $output->total_alunos = $totalAlunos;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajaxList_old()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();

        $sql = "SELECT s.id, 
                       s.contrato, 
                       s.ordem_servico, 
                       s.ano_semestre, 
                       s.ordem_escola,
                       s.id_escola,
                       s.escola
                FROM (SELECT a.id,
                             b.contrato,
                             a.nome AS ordem_servico,
                             CONCAT(a.ano, '/', a.semestre) AS ano_semestre,
                             d.id AS id_escola,
                             CONCAT_WS(' - ', e.codigo, e.nome) AS escola,
                             IF(CHAR_LENGTH(e.codigo) > 0, e.codigo, CAST(e.nome AS DECIMAL)) AS ordem_escola
                      FROM ei_ordem_servico a 
                      INNER JOIN ei_contratos b 
                                 ON b.id = a.id_contrato
                      INNER JOIN ei_diretorias c
                                 ON c.id = b.id_cliente
                      LEFT JOIN ei_ordem_servico_escolas d 
                                ON d.id_ordem_servico = a.id
                      LEFT JOIN ei_escolas e
                                ON e.id = d.id_escola
                                AND e.id_diretoria = c.id
                      WHERE c.id_empresa = {$this->session->userdata('empresa')}";
        if ($busca['diretoria']) {
            $sql .= " AND c.id = {$busca['diretoria']}";
        }
        if ($busca['contrato']) {
            $sql .= " AND b.id = {$busca['contrato']}";
        }
        if ($busca['ano_semestre']) {
            $sql .= " AND CONCAT(a.ano, '/', a.semestre) = '{$busca['ano_semestre']}'";
        }
        if ($busca['ordem_servico']) {
            $sql .= " AND a.id = {$busca['ordem_servico']}";
        }
        if ($busca['municipio']) {
            $sql .= " AND e.municipio = '{$busca['municipio']}'";
        }
        if ($busca['escola']) {
            $sql .= " AND e.id = '{$busca['escola']}'";
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.ordem_servico', 's.contrato', 's.escola');
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
        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();


        $data = array();
        foreach ($list as $ei) {
            $row = array();
            $row[] = $ei->contrato;
            $row[] = $ei->ordem_servico;
            $row[] = $ei->ano_semestre;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_os(' . $ei->id . ')" title="Editar área/cliente"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_os(' . $ei->id . ')" title="Excluir área/cliente"><i class="glyphicon glyphicon-trash"></i> </button>
                      <button type="button" class="btn btn-sm btn-info" onclick="add_escola(' . $ei->id . ')" title="Gerenciar unidade de ensino">Escolas</button>
                     ';
            $row[] = $ei->escola;
            if ($ei->id_escola) {
                $row[] = '
                          <!--<button type="button" class="btn btn-sm btn-info" onclick="add_curso(' . ')" title="Adicionar curso"><i class="glyphicon glyphicon-plus"></i> Curso</button>-->
                          <button type="button" class="btn btn-sm btn-primary" onclick="alunos(' . $ei->id_escola . ')" title="Gerenciar alunos">Alunos</button>
                          <button type="button" class="btn btn-sm btn-primary" onclick="profissionais(' . $ei->id_escola . ')" title="Gerenciar profissionais">Cuidadores/Horários</button>
                          <button type="button" class="btn btn-sm btn-primary" title="Relatorio"><i class="glyphicon glyphicon-print"></i></button>
                         ';
            } else {
                $row[] = '
                         <!--<button type="button" class="btn btn-sm btn-info disabled" title="Adicionar curso"><i class="glyphicon glyphicon-plus"></i> Curso</button>-->
                          <button type="button" class="btn btn-sm btn-primary disabled" title="Gerenciar aluno">Alunos</button>
                          <button type="button" class="btn btn-sm btn-primary disabled" title="Gerenciar profissionais">Cuidadores/Horários</button>
                          <button type="button" class="btn btn-sm btn-primary disabled" title="Relatorio"><i class="glyphicon glyphicon-print"></i></button>
                         ';
            }

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

    //==========================================================================
    public function ajaxEdit()
    {
        $id = $this->input->post('id');

        $this->db->select('a.*, c.id AS diretoria', false);
        $this->db->join('ei_contratos b', 'b.id = a.id_contrato');
        $this->db->join('ei_diretorias c', 'c.id = b.id_cliente');
        $data = $this->db->get_where('ei_ordem_servico a', array('a.id' => $id))->row();

        $this->db->select('id, contrato');
        $this->db->where('id', $data->diretoria);
        $contratos = array_column($this->db->get('ei_contratos')->result(), 'contrato', 'id');

        $data->contrato = form_dropdown('id_contrato', $contratos, $data->id_contrato, 'id="contrato" class="form-control"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEditEscola()
    {
        $id = $this->input->post('id');

        $this->db->select('a.*, b.contrato, c.id AS id_diretoria, c.nome AS diretoria', false);
        $this->db->select("CONCAT(a.ano, '/', a.semestre) AS ano_semestre", false);
        $this->db->join('ei_contratos b', 'b.id = a.id_contrato');
        $this->db->join('ei_diretorias c', 'c.id = b.id_cliente');
        $this->db->where('a.id', $id);
        $data = $this->db->get('ei_ordem_servico a')->row();

        $this->db->select(["a.id, CONCAT_WS(' - ', a.codigo, a.nome) AS nome"], false);
        $this->db->join('ei_diretorias c', 'c.id = a.id_diretoria');
        $this->db->where('c.id', $data->id_diretoria);
        $this->db->order_by('a.codigo', 'asc');
        $this->db->order_by('a.nome', 'asc');
        $escolas = array_column($this->db->get('ei_escolas a')->result(), 'nome', 'id');

        $this->db->select('id_escola');
        $this->db->where('id_ordem_servico', $id);
        $escolasSelecionadas = array_column($this->db->get('ei_ordem_servico_escolas')->result(), 'id_escola');

        $data->escola = form_multiselect('id_escola[]', $escolas, $escolasSelecionadas, 'id="escola" class="demo1" size="8"');

        $this->db->select('a.municipio');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id', $data->id_diretoria);
        $this->db->where('a.municipio IS NOT NULL');
        $this->db->group_by('a.municipio');
        $this->db->order_by('a.municipio', 'asc');
        $municipios = ['' => 'Todos'] + array_column($this->db->get('ei_escolas a')->result(), 'municipio', 'municipio');

        $data->municipio = form_dropdown('', $municipios, '', 'id="municipio" class="demo1" size="8"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxEditCurso()
    {
        $id = $this->input->post('id');

        $this->db->select('a.*, b.nome AS escola, c.nome AS ordem_servico, d.contrato, e.nome AS diretoria', false);
        $this->db->select("CONCAT(c.ano, '/', c.semestre) AS ano_semestre", false);
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $this->db->join('ei_ordem_servico c', 'c.id = a.id_ordem_servico');
        $this->db->join('ei_contratos d', 'd.id = c.id_contrato');
        $this->db->join('ei_diretorias e', 'e.id = d.id_cliente');
        $this->db->where('a.id', $id);
        $data = $this->db->get('ei_ordem_servico_escolas a')->row();

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_escolas_cursos b', 'b.id_curso = a.id');
        $this->db->join('ei_escolas c', 'c.id = b.id_escola');
        $this->db->join('ei_diretorias c2', 'c2.id = c.id_diretoria');
        $this->db->join('ei_contratos d', 'd.id_cliente = c2.id');
        $this->db->join('ei_ordem_servico e', 'e.id_contrato = d.id');
        $this->db->join('ei_ordem_servico_escolas f', 'f.id_ordem_servico = e.id AND f.id_escola = c.id');
        $this->db->where('f.id', $data->id);
        $cursos = array_column($this->db->get('ei_cursos a')->result(), 'nome', 'id');


        $this->db->select('id_curso');
        $this->db->where('id_ordem_servico_escola ', $id);
        $cursosSelecionados = array_column($this->db->get('ei_ordem_servico_cursos')->result(), 'id_curso');

        $data->curso = form_multiselect('id_curso[]', $cursos, $cursosSelecionados, 'id="curso" class="demo2" size="8"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajaxAdd()
    {
        $data = $this->input->post();
        $status = $this->db->insert('ei_ordem_servico', $data);

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajaxAddEscola()
    {
        $idOrdemServico = $this->input->post('id_ordem_servico');
        $idEscolas = $this->input->post('id_escola');

        $this->db->trans_start();

        $this->db->where('id_ordem_servico', $idOrdemServico);
        $this->db->where_not_in('id_escola', $idEscolas);
        $this->db->delete('ei_ordem_servico_escolas');

        $this->db->select('id_escola');
        $this->db->where('id_ordem_servico', $idOrdemServico);
        $rows = array_column($this->db->get('ei_ordem_servico_escolas')->result(), 'id_escola');
        $idEscolas = array_diff($idEscolas, $rows);

        $data = array();
        foreach ($idEscolas as $idEscola) {
            $data[] = array(
                'id_ordem_servico' => $idOrdemServico,
                'id_escola' => $idEscola
            );
        }
        if ($data) {
            $this->db->insert_batch('ei_ordem_servico_escolas', $data);
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();
        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajaxAddCurso()
    {
        $idOrdemServicoEscola = $this->input->post('id_ordem_servico_escola');
        $idCursos = $this->input->post('id_curso');

        $this->db->trans_start();

        $this->db->where('id_ordem_servico_escola', $idOrdemServicoEscola);
        $this->db->where_not_in('id_curso', $idCursos);
        $this->db->delete('ei_ordem_servico_cursos');

        $this->db->select('id_curso');
        $this->db->where('id_ordem_servico_escola', $idOrdemServicoEscola);
        $rows = array_column($this->db->get('ei_ordem_servico_cursos')->result(), 'id_curso');
        $idCursos = array_diff($idCursos, $rows);

        $data = array();
        foreach ($idCursos as $idCurso) {
            $data[] = array(
                'id_ordem_servico_escola' => $idOrdemServicoEscola,
                'id_curso' => $idCurso
            );
        }
        if ($data) {
            $this->db->insert_batch('ei_ordem_servico_cursos', $data);
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();
        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajaxUpdate()
    {
        $id = $this->input->post('id');
        $data = $this->input->post();
        unset($data['id']);

        $status = $this->db->update('ei_ordem_servico', $data, array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $senhaExclusao = $this->input->post('senha_exclusao');

        $this->db->select('a.id');
        $this->db->join('ei_contratos b', 'b.id = a.id_contrato');
        $this->db->join('ei_diretorias c', 'c.id = b.id_cliente');
        $this->db->where('a.id', $id);
        $this->db->where('c.senha_exclusao', $senhaExclusao);
        $ordemServico = $this->db->get('ei_ordem_servico a')->row();
        if (!$ordemServico) {
            exit(json_encode(['acesso_negado' => 'Senha inválida.']));
        }

        $status = $this->db->delete('ei_ordem_servico', array('id' => $ordemServico->id));

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajaxDeleteCurso()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_ordem_servico_cursos', array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function copiarOS()
    {
        $ano = $this->input->post('ano');
        $semestre = $this->input->post('semestre');
        $ordensServicoSelecionadas = $this->input->post('id');

        $this->db->select('id, nome');
        if ($ano) {
            $this->db->where('ano', $ano);
        }
        if ($semestre) {
            $this->db->where('semestre', $semestre);
        }
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('ei_ordem_servico')->result();

        $ordensServico = array_column($rows, 'nome', 'id');
        $data['ordens_servico'] = form_multiselect('id[]', $ordensServico, $ordensServicoSelecionadas, 'id="ordens_servico" class="demo2" size="8"');

        echo json_encode($data);
    }

    //==========================================================================
    public function salvarCopiaOS()
    {
        $idOS = $this->input->post('id');
        if (!is_array($idOS)) {
            $idOS = array();
        }
        if (empty($idOS)) {
            exit(json_encode(['erro' => 'Nenhuma O.S. selecionada para cópia']));
        }

        $nome = $this->input->post('nome');
        $idContrato = $this->input->post('id_contrato');
        $numeroEmpenho = $this->input->post('numero_empenho');
        $ano = $this->input->post('ano');
        $semestre = $this->input->post('semestre');
        if (strlen($nome) == 0) {
            exit(json_encode(['erro' => 'O nome das novas O.S. é obrigatório']));
        }
        if (strlen($ano) == 0) {
            exit(json_encode(['erro' => 'O ano das novas O.S. é obrigatório']));
        } elseif (date('Y', mktime(0, 0, 0, 1, 1, $ano)) != $ano) {
            exit(json_encode(['erro' => 'O ano das novas O.S. é inválido']));
        }
        if (strlen($semestre) == 0) {
            exit(json_encode(['erro' => 'O semestre das novas O.S. é obrigatório']));
        }

        $this->db->where('nome', $nome);
        $os = $this->db->get('ei_ordem_servico')->num_rows();
        if ($os > 0) {
            exit(json_encode(['erro' => 'O nome da O.S. já existe.']));
        }

        $data = array(
            'nome' => $nome,
            'id_contrato' => $idContrato,
            'numero_empenho' => $numeroEmpenho,
            'ano' => $ano,
            'semestre' => $semestre
        );


        // Busca as escolas antigas
        $this->db->select("DISTINCT(id_escola) AS id_escola", false);
        $this->db->where_in('id_ordem_servico', $idOS);
        $escolas = $this->db->get('ei_ordem_servico_escolas')->result_array();


        // Busca os profissionais antigos
        $this->db->select('a.*, b.id_escola', false);
        $this->db->join('ei_ordem_servico_escolas b', 'b.id = a.id_ordem_servico_escola');
        $this->db->where_in('b.id_ordem_servico', $idOS);
        $this->db->order_by('a.id', 'asc');
        $rowsProfissionais = $this->db->get('ei_ordem_servico_profissionais a')->result_array();
        $profissionais = array();
        foreach ($rowsProfissionais as $rowProfissional) {
            $profissionais[$rowProfissional['id_escola']][$rowProfissional['id_usuario']] = $rowProfissional;
        }


        // Busca os horários antigos
        $this->db->select('a.*, b.id_usuario, c.id_escola', false);
        $this->db->join('ei_ordem_servico_profissionais b', 'b.id = a.id_os_profissional');
        $this->db->join('ei_ordem_servico_escolas c', 'c.id = b.id_ordem_servico_escola');
        $this->db->where_in('c.id_ordem_servico', $idOS);
        $rowsHorarios = $this->db->get('ei_ordem_servico_horarios a')->result_array();
        $horarios = array();
        foreach ($rowsHorarios as $rowHorario) {
            $horarios[$rowHorario['id_escola']][$rowHorario['id_usuario']][] = $rowHorario;
        }


        // Busca os alunos antigos
        $this->db->select('a.*, b.id_escola', false);
        $this->db->join('ei_ordem_servico_escolas b', 'b.id = a.id_ordem_servico_escola');
        $this->db->where_in('b.id_ordem_servico', $idOS);
        $this->db->order_by('a.id', 'asc');
        $rowsAlunos = $this->db->get('ei_ordem_servico_alunos a')->result_array();
        $alunos = array();
        foreach ($rowsAlunos as $rowAluno) {
            $alunos[$rowAluno['id_escola']][$rowAluno['id_aluno']] = $rowAluno;
        }


        // Buscar turmas antigas
        $this->db->select('c.id_usuario, d.id_aluno, e.id_escola, b.id AS id_horario');
        $this->db->join('ei_ordem_servico_horarios b', 'b.id = a.id_os_horario');
        $this->db->join('ei_ordem_servico_profissionais c', 'c.id = b.id_os_profissional');
        $this->db->join('ei_ordem_servico_alunos d', 'd.id = a.id_os_aluno');
        $this->db->join('ei_ordem_servico_escolas e', 'e.id = c.id_ordem_servico_escola AND e.id = d.id_ordem_servico_escola');
        $this->db->where_in('e.id_ordem_servico', $idOS);
        $rowsTurmas = $this->db->get('ei_ordem_servico_turmas a')->result();
        $turmas = array();
        foreach ($rowsTurmas as $rowTurma) {
            $turmas[$rowTurma->id_escola][$rowTurma->id_aluno][$rowTurma->id_usuario][] = $rowTurma->id_horario;
        }


        $this->db->trans_start();


        // Ordem_servico
        $this->db->insert('ei_ordem_servico', $data);
        $id = $this->db->insert_id();


        // Escolas
        foreach ($escolas as $escola) {
            $escola['id_ordem_servico'] = $id;
            $this->db->insert('ei_ordem_servico_escolas', $escola);
            $idOSEscola = $this->db->insert_id();

            $idHorarios = array();


            // Profissionais
            foreach ($profissionais[$escola['id_escola']] as $profissional) {
                unset($profissional['id'], $profissional['id_escola']);
                $profissional['id_ordem_servico_escola'] = $idOSEscola;
                $this->db->insert('ei_ordem_servico_profissionais', $profissional);
                $idOSProfissional = $this->db->insert_id();


                // Horários
                foreach ($horarios[$escola['id_escola']][$profissional['id_usuario']] as $horario) {
                    $idOSHorario = $horario['id'];
                    unset($horario['id'], $horario['id_usuario'], $horario['id_escola']);
                    $horario['id_os_profissional'] = $idOSProfissional;
                    $this->db->insert('ei_ordem_servico_horarios', $horario);

                    $idHorarios[$idOSHorario] = $this->db->insert_id();
                }
            }


            // Alunos
            foreach ($alunos[$escola['id_escola']] as $aluno) {
                unset($aluno['id'], $aluno['id_escola']);
                $aluno['id_ordem_servico_escola'] = $idOSEscola;
                $this->db->insert('ei_ordem_servico_alunos', $aluno);
                $idOSAluno = $this->db->insert_id();


                // Turmas
                if (isset($turmas[$escola['id_escola']][$aluno['id_aluno']])) {
                    foreach ($turmas[$escola['id_escola']][$aluno['id_aluno']] as $idUsuarioOLD) {
                        foreach ($idUsuarioOLD as $idHorariosOLD) {
                            if (isset($idHorarios[$idHorariosOLD])) {
                                $turma = array(
                                    'id_os_aluno' => $idOSAluno,
                                    'id_os_horario' => $idHorarios[$idHorariosOLD],
                                );
                                $this->db->insert('ei_ordem_servico_turmas', $turma);
                            }
                        }
                    }
                }
            }
        }


        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(['status' => $status !== false]);
    }

    //==========================================================================
    public function salvarCopiaOS_old()
    {
        $idsAnteriores = $this->input->post('id');
        if (!is_array($idsAnteriores)) {
            $idsAnteriores = array();
        }
        $nome = $this->input->post('nome');
        $idContrato = $this->input->post('id_contrato');
        $numeroEmpenho = $this->input->post('numero_empenho');
        $ano = $this->input->post('ano');
        $semestre = $this->input->post('semestre');
        if (strlen($nome) == 0) {
            exit(json_encode(['erro' => 'O nome das novas O.S. é obrigatório']));
        }
        if (strlen($ano) == 0) {
            exit(json_encode(['erro' => 'O ano das novas O.S. é obrigatório']));
        } elseif (date('Y', mktime(0, 0, 0, 1, 1, $ano)) != $ano) {
            exit(json_encode(['erro' => 'O ano das novas O.S. é inválido']));
        }
        if (strlen($semestre) == 0) {
            exit(json_encode(['erro' => 'O semestre das novas O.S. é obrigatório']));
        }

        $this->db->trans_start();

        $data = array(
            'nome' => $nome,
            'id_contrato' => $idContrato,
            'numero_empenho' => $numeroEmpenho,
            'ano' => $ano,
            'semestre' => $semestre
        );

        // Ordem_servico
        $this->db->insert('ei_ordem_servico', $data);
        $id = $this->db->insert_id();


        $this->db->where_in('id_ordem_servico', $idsAnteriores);
        $osEscolas = $this->db->get('ei_ordem_servico_escolas')->result();

        // Ordem_servico_escola
        foreach ($osEscolas as $osEscola) {
            $idEscolaAnterior = $osEscola->id;
            unset($osEscola->id);
            $osEscola->id_ordem_servico = $id;

            $this->db->insert('ei_ordem_servico_escolas', $osEscola);
            $idEscola = $this->db->insert_id();


            $this->db->where('id_ordem_servico_escola', $idEscolaAnterior);
            $osProfissionais = $this->db->get('ei_ordem_servico_profissionais')->result();

            $arrIdProfissional = array(0);

            // Ordem_servico_profissionais
            foreach ($osProfissionais as $osProfissional) {
                $idProfissionalAnterior = $osProfissional->id;
                unset($osProfissional->id);
                $osProfissional->id_ordem_servico_escola = $idEscola;

                $this->db->insert('ei_ordem_servico_profissionais', $osProfissional);
                $idProfissional = $this->db->insert_id();
                $arrIdProfissional[$idProfissionalAnterior] = $idProfissional;

                $this->db->where('id_os_profissional', $idProfissionalAnterior);
                $osHorarios = $this->db->get('ei_ordem_servico_horarios')->result();


                foreach ($osHorarios as $osHorario) {
                    unset($osHorario->id);
                    $osHorario->id_os_profissional = $idProfissional;
                    $totalDiasMes = $this->contarSemanasDoMes($idProfissional, $osHorario->dia_semana);
                    $osHorario->total_dias_mes1 = $totalDiasMes[0] ?? null;
                    $osHorario->total_dias_mes2 = $totalDiasMes[0] ?? null;
                    $osHorario->total_dias_mes3 = $totalDiasMes[0] ?? null;
                    $osHorario->total_dias_mes4 = $totalDiasMes[0] ?? null;
                    $osHorario->total_dias_mes5 = $totalDiasMes[0] ?? null;
                    $osHorario->total_dias_mes6 = $totalDiasMes[0] ?? null;

                    $this->db->insert('ei_ordem_servico_horarios', $osHorario);
                }
            }


            $this->db->where('id_ordem_servico_escola', $idEscolaAnterior);
            $osAlunos = $this->db->get('ei_ordem_servico_alunos')->result();

            // Ordem_servico_alunos
            foreach ($osAlunos as $osAluno) {
                $idAlunoAnterior = $osAluno->id;
                unset($osAluno->id);
                $osAluno->id_ordem_servico_escola = $idEscola;

                $this->db->insert('ei_ordem_servico_alunos', $osAluno);
                $idAluno = $this->db->insert_id();


                $this->db->select("id_os_profissional, '{$idAluno}' AS id_os_aluno", false);
                $this->db->where('id_os_aluno', $idAlunoAnterior);
                $this->db->where_in('id_os_profissional', array_keys($arrIdProfissional));
                $osTurmas = $this->db->get('ei_ordem_servico_turmas')->result();

                // Ordem_servico_turmas
                foreach ($osTurmas as $osTurma) {
                    $osTurma->id_os_profissional = $arrIdProfissional[$osTurma->id_os_profissional];
                    $this->db->insert('ei_ordem_servico_turmas', $osTurma);
                }
            }

        }


        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(['status' => $status !== false]);
    }

    //==========================================================================
    private function contarSemanasDoMes($idOSProfissional, $diaDaSemana)
    {
        switch ($diaDaSemana) {
            case '0':
                $semana = 'sun';
                break;
            case '1':
                $semana = 'mon';
                break;
            case '2':
                $semana = 'tue';
                break;
            case '3':
                $semana = 'wed';
                break;
            case '4':
                $semana = 'thu';
                break;
            case '5':
                $semana = 'fri';
                break;
            case '6':
                $semana = 'sat';
                break;
            default:
                return false;
        }

        $this->db->select('c.ano, c.semestre');
        $this->db->select("DATE_FORMAT(MIN(f.data_inicio), '%M %Y') AS mes_inicial", false);
        $this->db->select("DATE_FORMAT(MAX(f.data_termino), '%M %Y') AS mes_final", false);
        $this->db->select('MIN(f.data_inicio) AS data_inicio', false);
        $this->db->select('MAX(f.data_termino) AS data_termino', false);
        $this->db->join('ei_ordem_servico_escolas b', 'b.id = a.id_ordem_servico_escola');
        $this->db->join('ei_ordem_servico c', 'c.id = b.id_ordem_servico');
        $this->db->join('ei_ordem_servico_horarios d', 'd.id_os_profissional = a.id', 'left');
        $this->db->join('ei_ordem_servico_turmas e', 'e.id_os_horario = d.id', 'left');
        $this->db->join('ei_ordem_servico_alunos f', 'f.id = e.id_os_aluno', 'left');
        $this->db->where('a.id', $idOSProfissional);
        $this->db->group_by('a.id');
        $row = $this->db->get('ei_ordem_servico_profissionais a')->row();

        $mesInicial = intval($row->semestre) == 2 ? 7 : 1;
        $mesFinal = $mesInicial + 5;
        $mesAno = array();
        for ($i = $mesInicial; $i <= $mesFinal; $i++) {
            $mesAno[] = date('F Y', strtotime('01-' . str_pad($i, 2, '0', STR_PAD_LEFT) . '-' . $row->ano));
        }

        $data = array();
        foreach ($mesAno as $mes) {
            if ($mes == $row->mes_inicial and $row->data_inicio) {
                $semanaInicial = date('W', strtotime("{$semana} {$row->data_inicio}"));
            } else {
                $semanaInicial = date('W', strtotime("first {$semana} of {$mes}"));
            }
            if ($mes == $row->mes_final and $row->data_termino) {
                $semanaFinal = date('W', strtotime($semana, strtotime("{$row->data_termino} -1 week +1 day"))) + 1;
            } else {
                $semanaFinal = date('W', strtotime("last {$semana} of {$mes} -1 week")) + 1;
            }
            $data[] = $semanaFinal - ($semanaInicial - 1);
        }

        return $data;
    }

}