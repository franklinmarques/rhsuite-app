<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe ExamePeriodico
 *
 * Trabalha com o gerenciamento de exames periódicos dos colaboradores
 *
 * @author frank
 * @access public
 * @package CI_Controller\MY_Controller
 * @version 1.0
 */
class ExamePeriodico extends MY_Controller
{

    /**
     * Construtor da classe
     *
     * Carrega o model de exame periódico
     *
     * @access public
     * @uses ..\models\examePeriodico_model.php Model
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('exameperiodico_model', 'exame');
    }

    /**
     * Função padrão
     *
     * @access public
     */
    public function index()
    {
        $this->relatorio();
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de exames criados
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_list($id_usuario)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.data_programada,
                       s.data_realizacao,
                       s.data_entrega,
                       s.local_exame,
                       s.observacoes,
                       s.data_programada_de,
                       s.data_realizacao_de,
                       s.data_entrega_copia_de,
                       s.data_entrega_de,
                       s.matricula
                FROM (SELECT a.id, 
                             a.data_programada,
                             a.data_realizacao,
                             a.data_entrega,
                             a.local_exame,
                             a.observacoes,
                             DATE_FORMAT(a.data_programada,'%d/%m/%Y') AS data_programada_de,
                             DATE_FORMAT(a.data_realizacao,'%d/%m/%Y') AS data_realizacao_de,
                             DATE_FORMAT(a.data_entrega_copia,'%d/%m/%Y') AS data_entrega_copia_de,
                             DATE_FORMAT(a.data_entrega,'%d/%m/%Y') AS data_entrega_de,
                             b.matricula
                      FROM usuarios_exame_periodico a
                      INNER JOIN usuarios b
                                 ON b.id = a.id_usuario
                      WHERE b.id = {$id_usuario}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.data_programada_de', 's.data_realizacao_de', 's.data_entrega_de', 's.observacoes', 's.local_exame', 's.matricula');
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
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $exame) {
            $row = array();
            $row[] = $exame->data_programada_de;
            $row[] = $exame->data_realizacao_de;
            $row[] = $exame->data_entrega_copia_de;
            $row[] = $exame->data_entrega_de;
            $row[] = $exame->local_exame;
            $row[] = $exame->observacoes;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-primary" onclick="edit_exame(' . $exame->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_exame(' . $exame->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                      <button class="btn btn-warning btn-sm" onclick="enviar_email_exame(' . $exame->id . ')" title="Enviar e-mail de convocação"><i class="glyphicon glyphicon-envelope"></i></button>
                     ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de exame periódico
     *
     * @access public
     */
    public function ajax_edit($id)
    {
        $data = $this->exame->select(array('id' => $id));
        echo json_encode($data);
    }


    // -------------------------------------------------------------------------

    /**
     * Formata os dados para inserção ou alteração
     *
     * @access private
     */
    private function formatarDados()
    {
        $data = $this->input->post();
        if ($data['data_programada']) {
            $_POST['data_programada'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_programada'])));
        }
        if ($data['data_realizacao']) {
            $_POST['data_realizacao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_realizacao'])));
        } else {
            $_POST['data_realizacao'] = null;
        }
        if ($data['data_entrega_copia']) {
            $_POST['data_entrega_copia'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_entrega_copia'])));
        } else {
            $_POST['data_entrega_copia'] = null;
        }
        if ($data['data_entrega']) {
            $_POST['data_entrega'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_entrega'])));
        } else {
            $_POST['data_entrega'] = null;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra um novo exame periódico
     *
     * @access public
     */
    public function ajax_add()
    {
        $this->formatarDados();
        if (($msg = $this->validar()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        $status = $this->exame->insert();
        /*if ($status) {
            $this->enviarEmail(false);
        }*/
        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de exame periódico
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validar()
    {
        return $this->exame->validar();
    }

    // -------------------------------------------------------------------------

    /**
     * Altera um exame periódico existente
     *
     * @access public
     */
    public function ajax_update()
    {
        $this->formatarDados();
        $id = $this->input->post('id');
        if (($msg = $this->exame->update(array('id' => $id))) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }

//        $this->enviarEmail(false);

        echo json_encode(array('status' => true));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de exame periódico
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidar()
    {
        return $this->exame->revalidar();
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui um exame periódico existente
     *
     * @access public
     */
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        if (($msg = $this->exame->delete(array('id' => $id))) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }


    // -------------------------------------------------------------------------

    /**
     * Limpa os exames periódicos de um usuários
     *
     * @access public
     */
    public function limpar()
    {
        $id_usuario = $this->input->post('id_usuario');
        if (($msg = $this->exame->delete(array('id_usuario' => $id_usuario))) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }


    // -------------------------------------------------------------------------

    /**
     * Relatório de todos os afastamentos listados
     *
     * @access public
     */
    public function relatorio($pdf = false)
    {
        $empresa = $this->session->userdata('empresa');
        $realizados = $this->input->get('realizados');
        $idDepto = $this->input->get('id_depto');
        $idArea = $this->input->get('id_area');
        $mes = $this->input->get('mes');
        $ano = $this->input->get('ano');
        $tipoVinculo = $this->input->get('tipo_vinculo');
        $status = $this->input->get('status');

        $data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');

        $this->db->select('b.id, b.nome, b.cpf, b.funcao, a.local_exame, b.municipio, b.matricula', false);
        $this->db->select("CASE b.status 
                                  WHEN 1 THEN 'Ativo'
                                  WHEN 2 THEN 'Inativo'
                                  WHEN 3 THEN 'Em experiência'
                                  WHEN 4 THEN 'Em desligamento'
                                  WHEN 5 THEN 'Desligado'
                                  WHEN 6 THEN 'Afastado (maternidade)'
                                  WHEN 7 THEN 'Afastado (aposentadoria)'
                                  WHEN 8 THEN 'Afastado (doença)'
                                  WHEN 9 THEN 'Afastado (acidente)'
                                  ELSE 'Indefinido' END AS status", false);
        $this->db->select("CONCAT_WS('/', b.depto, b.area, b.setor) AS estrutura", false);
        $this->db->select("DATE_FORMAT(a.data_programada, '%d/%m/%Y') AS data_programada", false);
        $this->db->select("DATE_FORMAT(a.data_realizacao, '%d/%m/%Y') AS data_realizacao", false);
        $this->db->select("DATE_FORMAT(a.data_entrega_copia, '%d/%m/%Y') AS data_entrega_copia", false);
        $this->db->select("DATE_FORMAT(a.data_entrega, '%d/%m/%Y') AS data_entrega", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_depto OR c.nome = b.depto', 'left');
        $this->db->join('empresa_areas d', 'd.id = b.id_area OR d.nome = b.area', 'left');
        $this->db->where('b.empresa', $empresa);
        if ($realizados === '0') {
            $this->db->where('a.data_realizacao IS NULL');
        } elseif ($realizados === '1') {
            $this->db->where('a.data_realizacao IS NOT NULL');
        } elseif ($realizados === '2') {
            $this->db->where('a.data_programada IS NULL');
        }
        if ($idDepto) {
            $this->db->where('c.id', $idDepto);
        }
        if ($idArea) {
            $this->db->where('d.id', $idArea);
        }
        if ($mes) {
            $this->db->where("DATE_FORMAT(a.data_programada, '%m') =", $mes);
        }
        if ($ano) {
            $this->db->where("DATE_FORMAT(a.data_programada, '%Y') =", $ano);
        }
        if ($tipoVinculo) {
            $this->db->where('b.tipo_vinculo', $tipoVinculo);
        }
        if (strlen($status) > 0) {
            if ($status < 0) {
                $this->db->where('(b.status < 1 OR b.status > 9)', null, false);
            } else {
                $this->db->where('b.status', $status);
            }
        }
        $data['funcionarios'] = $this->db
            ->group_by('a.id')
            ->order_by('b.nome', 'asc')
            ->get('usuarios_exame_periodico a')->result();
        $data['is_pdf'] = $pdf;

        $deptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $empresa)
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $data['deptos'] = ['' => 'Todos'] + array_column($deptos, 'nome', 'id');
        $data['areas'] = ['' => 'Todas'];

        if ($pdf) {
            return $this->load->view('funcionarios_examePdf', $data, true);
        }
        $this->load->view('funcionarios_exameRelatorio', $data);
    }


    public function filtrarEstrutura()
    {
        $rowAreas = $this->db
            ->select('id, nome')
            ->where('id_departamento', $this->input->post('id_depto'))
            ->order_by('nome', 'asc')
            ->get('empresa_areas')
            ->result();

        $areas = ['' => 'Todas'] + array_column($rowAreas, 'nome', 'id');

        $data['areas'] = form_dropdown('', $areas, $this->input->post('id_area'));

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de afastamento criados
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_relatorio()
    {
        $post = $this->input->post();

        if ($post['draw'] === '1') {
            $post['tipo_vinculo'] = '01';
            $post['status'] = 1;
            $post['mes'] = '';
            $post['ano'] = date('Y');
        }

        $this->db
            ->select('b.id, b.nome, b.cpf, b.funcao, a.local_exame, b.municipio, b.matricula')
            ->select(["IF(b.status > 0 AND b.status < 10, b.status, '-1') AS status"], false)
            ->select(["CONCAT_WS('/', b.depto, b.area, b.setor) AS estrutura"], false)
            ->select(["DATE_FORMAT(a.data_programada,'%d/%m/%Y') AS data_programada_de"], false)
            ->select(["DATE_FORMAT(a.data_realizacao,'%d/%m/%Y') AS data_realizacao_de"], false)
            ->select(["DATE_FORMAT(a.data_entrega_copia,'%d/%m/%Y') AS data_entrega_copia_de"], false)
            ->select(["DATE_FORMAT(a.data_entrega,'%d/%m/%Y') AS data_entrega_de"], false)
            ->join('usuarios b', 'b.id = a.id_usuario')
            ->join('empresa_departamentos c', 'c.id = b.id_depto OR c.nome = b.depto', 'left')
            ->join('empresa_areas d', 'd.id = b.id_area OR d.nome = b.area', 'left')
            ->where('b.empresa', $this->session->userdata('empresa'));
        if (!empty($post['id_depto'])) {
            $this->db->where('c.id', $post['id_depto']);
        }
        if (!empty($post['id_area'])) {
            $this->db->where('d.id', $post['id_area']);
        }
        if ($post['realizados'] === '0') {
            $this->db->where('a.data_realizacao', null);
        } elseif ($post['realizados'] === '1') {
            $this->db->where('a.data_realizacao IS NOT NULL');
        } elseif ($post['realizados'] === '2') {
            $this->db->where('a.data_programada', null);
        }
        if (!empty($post['mes'])) {
            $this->db->where('MONTH(a.data_programada)', $post['mes']);
        }
        if (!empty($post['ano'])) {
            $this->db->where('YEAR(a.data_programada)', $post['ano']);
        }
        if (!empty($post['tipo_vinculo'])) {
            $this->db->where('b.tipo_vinculo', $post['tipo_vinculo']);
        }
        if (!empty($post['status'])) {
            if ($post['status'] < 0) {
                $this->db->where('(b.status < 1 OR b.status > 9)');
            } else {
                $this->db->where('b.status', $post['status']);
            }
        }
        $query = $this->db
            ->group_by('a.id')
            ->order_by('b.nome', 'asc')
            ->get('usuarios_exame_periodico a');

        $config = ['search' => ['nome']];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $nomeStatus = [
            '1' => 'Ativo',
            '2' => 'Inativo',
            '3' => 'Em experiência',
            '4' => 'Em desligamento',
            '5' => 'Desligado',
            '6' => 'Afastado (maternidade)',
            '7' => 'Afastado (aposentadoria)',
            '8' => 'Afastado (doença)',
            '9' => 'Afastado (acidente)'
        ];

        if ($output->draw === 1) {
            $meses = array(
                '' => 'Todos',
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
            );
            $output->mes = form_dropdown('mes', $meses, '', 'class="form-control input-sm" onchange="buscar()"');

            $this->db->select("DATE_FORMAT(data_programada, '%Y') AS ano", false);
            $this->db->order_by('data_programada', 'asc');
            $examePeriodico = $this->db->get('usuarios_exame_periodico')->result();
            $anos = array('' => 'Todos');
            foreach ($examePeriodico as $data_programada) {
                $anos[$data_programada->ano] = $data_programada->ano;
            }
            $output->ano = form_dropdown('ano', $anos, $post['ano'], 'class="form-control input-sm" onchange="buscar()"');
            $tipo_vinculo = array(
                '' => 'Todos',
                '01' => 'CLT',
                '02' => 'MEI',
                '03' => 'PJ'
            );
            $output->tipo_vinculo = form_dropdown('tipo_vinculo', $tipo_vinculo, $post['tipo_vinculo'] ?? '', 'class="form-control input-sm" onchange="buscar()"');

            $arrStatus = [
                '' => 'Todos',
                '1' => 'Ativos',
                '2' => 'Inativos',
                '3' => 'Em experiência',
                '4' => 'Em desligamento',
                '5' => 'Desligados',
                '6' => 'Afastados (maternidade)',
                '7' => 'Afastados (aposentadoria)',
                '8' => 'Afastados (doença)',
                '9' => 'Afastados (acidente)',
                '-1' => 'Indefinidos'
            ];

            $status = array_intersect_key($arrStatus, ['' => 'Todos'] + array_column($output->data, 'status', 'status'));

            $output->status = form_dropdown('status', $status, $post['status'] ?? '', 'class="form-control input-sm" onchange="buscar()"');
        } else {
            $output->mes = '';
            $output->ano = '';
            $output->tipo_vinculo = '';
            $output->status = '';
        }

        $data = [];


        foreach ($output->data as $row) {
            $data[] = [
                $row->nome,
                $row->cpf,
                $row->funcao,
                $row->local_exame,
                $row->municipio,
                $row->matricula,
                $nomeStatus[$row->status] ?? 'Indefinido',
                $row->estrutura,
                $row->data_programada_de,
                $row->data_realizacao_de,
                $row->data_entrega_copia_de,
                $row->data_entrega_de,
                '<a class="btn btn-success btn-sm" href="' . site_url('funcionario/editar/' . $row->id) . '" title="Prontuário de colaborador">
                      <i class="glyphicon glyphicon-plus"></i> Prontuário
                 </a>
                 <button class="btn btn-warning btn-sm" onclick="enviar_email(' . $row->id . ', ' . $row->nome . ')" title="Enviar e-mail de convocação">
                      <i class="glyphicon glyphicon-envelope"></i>
                 </button>
                 <button class="btn btn-danger btn-sm" onclick="delete_prontuario(' . $row->id . ')" title="Excluir prontuário">
                      <i class="glyphicon glyphicon-trash"></i>
                 </button>'
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    public function ajax_relatorio1()
    {
        $post = $this->input->post();

        if ($post['draw'] === '1') {
            $post['tipo_vinculo'] = '01';
            $post['status'] = 1;
            $post['ano'] = date('Y');
        }

        $sql = "SELECT s.id,
                       s.nome,
                       s.cpf,
                       s.funcao,
                       s.local_exame,
                       s.municipio,
                       s.matricula,
                       s.nome_status,
                       s.estrutura,
                       s.data_programada,
                       s.data_realizacao,
                       s.data_entrega,
                       s.data_programada_de,
                       s.data_realizacao_de,
                       s.data_entrega_copia_de,
                       s.data_entrega_de,
                       s.status
                FROM (SELECT b.id,
                             b.nome,
                             b.cpf,
                             b.funcao,
                             a.local_exame,
                             b.municipio,
                             b.matricula,
                             IF(b.status > 0 AND b.status < 10, b.status, '-1') AS status,
                             CASE b.status 
                                  WHEN 1 THEN 'Ativo'
                                  WHEN 2 THEN 'Inativo'
                                  WHEN 3 THEN 'Em experiência'
                                  WHEN 4 THEN 'Em desligamento'
                                  WHEN 5 THEN 'Desligado'
                                  WHEN 6 THEN 'Afastado (maternidade)'
                                  WHEN 7 THEN 'Afastado (aposentadoria)'
                                  WHEN 8 THEN 'Afastado (doença)'
                                  WHEN 9 THEN 'Afastado (acidente)'
                                  ELSE 'Indefinido' END AS nome_status,
                             CONCAT_WS('/', b.depto, b.area, b.setor) AS estrutura,
                             a.data_programada,
                             a.data_realizacao,
                             a.data_entrega,
                             DATE_FORMAT(a.data_programada,'%d/%m/%Y') AS data_programada_de,
                             DATE_FORMAT(a.data_realizacao,'%d/%m/%Y') AS data_realizacao_de,
                             DATE_FORMAT(a.data_entrega_copia,'%d/%m/%Y') AS data_entrega_copia_de,
                             DATE_FORMAT(a.data_entrega,'%d/%m/%Y') AS data_entrega_de
                      FROM usuarios_exame_periodico a
                      INNER JOIN usuarios b
                                 ON b.id = a.id_usuario
                      LEFT JOIN empresa_departamentos c ON c.id = b.id_depto OR c.nome = b.depto
                      LEFT JOIN empresa_areas d ON d.id = b.id_area OR d.nome = b.area
                      WHERE b.empresa = {$this->session->userdata('empresa')}";
        if (!empty($post['id_depto'])) {
            $sql .= " AND c.id = '{$post['id_depto']}'";
        }
        if (!empty($post['id_area'])) {
            $sql .= " AND d.id = '{$post['id_area']}'";
        }
        if ($post['realizados'] === '0') {
            $sql .= ' AND a.data_realizacao IS NULL';
        } elseif ($post['realizados'] === '1') {
            $sql .= ' AND a.data_realizacao IS NOT NULL';
        } elseif ($post['realizados'] === '2') {
            $sql .= ' AND a.data_programada IS NULL';
        }
        if (!empty($post['mes'])) {
            $sql .= " AND DATE_FORMAT(a.data_programada, '%m') = '{$post['mes']}'";
        }
        if (!empty($post['ano'])) {
            $sql .= " AND DATE_FORMAT(a.data_programada, '%Y') = '{$post['ano']}'";
        }
        if (!empty($post['tipo_vinculo'])) {
            $sql .= " AND b.tipo_vinculo = '{$post['tipo_vinculo']}'";
        }
        if (!empty($post['status'])) {
            if ($post['status'] < 0) {
                $sql .= " AND (b.status < 1 OR b.status > 9)";
            } else {
                $sql .= " AND b.status = '{$post['status']}'";
            }
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.nome',
            's.cpf',
            's.funcao',
            's.local_exame',
            's.municipio',
            's.matricula',
            's.status',
            's.estrutura',
            's.data_programada'
        );
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

//        if (isset($post['order'])) {
//            $orderBy = array();
//            foreach ($post['order'] as $order) {
//                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
//            }
//            $sql .= '
//                    ORDER BY ' . implode(', ', $orderBy);
//        }
        $sql .= ' 
                    ORDER BY s.nome ASC';
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $exame) {
            $row = array();
            $row[] = $exame->nome;
            $row[] = $exame->cpf;
            $row[] = $exame->funcao;
            $row[] = $exame->local_exame;
            $row[] = $exame->municipio;
            $row[] = $exame->matricula;
            $row[] = $exame->nome_status;
            $row[] = $exame->estrutura;
            $row[] = $exame->data_programada_de;
            $row[] = $exame->data_realizacao_de;
            $row[] = $exame->data_entrega_copia_de;
            $row[] = $exame->data_entrega_de;
            $row[] = '
                      <a class="btn btn-success btn-sm"
                               href="' . site_url('funcionario/editar/' . $exame->id) . '"
                               title="Prontuário de colaborador">
                                <i class="glyphicon glyphicon-plus"></i> Prontuário
                            </a>
                      <button class="btn btn-warning btn-sm" onclick="enviar_email(' . $exame->id . ', \'' . $exame->nome . '\')"
                               title="Enviar e-mail de convocação">
                                <i class="glyphicon glyphicon-envelope"></i>
                            </button>
                      <button class="btn btn-danger btn-sm" 
                            onclick="delete_prontuario(' . $exame->id . ')"
                            title="Excluir prontuário">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
                     ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        if ($output['draw'] === '1') {
            $meses = array(
                '' => 'Todos',
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
            );
            $output['mes'] = form_dropdown('mes', $meses, '', 'class="form-control input-sm" onchange="buscar()"');

            $this->db->select("DATE_FORMAT(data_programada, '%Y') AS ano", false);
            $this->db->order_by('data_programada', 'asc');
            $examePeriodico = $this->db->get('usuarios_exame_periodico')->result();
            $anos = array('' => 'Todos');
            foreach ($examePeriodico as $data_programada) {
                $anos[$data_programada->ano] = $data_programada->ano;
            }
            $output['ano'] = form_dropdown('ano', $anos, $post['ano'], 'class="form-control input-sm" onchange="buscar()"');
            $tipo_vinculo = array(
                '' => 'Todos',
                '01' => 'CLT',
                '02' => 'MEI',
                '03' => 'PJ'
            );
            $output['tipo_vinculo'] = form_dropdown('tipo_vinculo', $tipo_vinculo, $post['tipo_vinculo'] ?? '', 'class="form-control input-sm" onchange="buscar()"');
            $arrStatus = array(
                '' => 'Todos',
                '1' => 'Ativos',
                '2' => 'Inativos',
                '3' => 'Em experiência',
                '4' => 'Em desligamento',
                '5' => 'Desligados',
                '6' => 'Afastados (maternidade)',
                '7' => 'Afastados (aposentadoria)',
                '8' => 'Afastados (doença)',
                '9' => 'Afastados (acidente)',
                '-1' => 'Indefinidos'
            );
            $status = array_intersect_key($arrStatus, ['' => 'Todos'] + array_column($list, 'nome_status', 'status'));

            $output['status'] = form_dropdown('status', $status, $post['status'] ?? '', 'class="form-control input-sm" onchange="buscar()"');
        }
        //output to json format
        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    /**
     * Gera o pdf do relatório
     *
     * @access public
     * @uses ..\libraries\mpdf.php
     */
    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.exame tr { border-width: 3px; border-color: #ddd; } ';

        $stylesheet .= 'table.funcionarios tr th, table.funcionarios tr td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= 'table.funcionarios thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.funcionarios thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.funcionarios tbody tr th { background-color: #dff0d8; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));


        $this->m_pdf->pdf->Output("Relatório de Exames Periódicos.pdf", 'D');
    }

    // -------------------------------------------------------------------------

    /**
     * Envia e-mail de convocação a um ou a todos os colaboradores
     *
     * @access public
     * @uses ..\libraries\email.php
     */
    public function enviarEmail($retorno = true)
    {
        $id = $this->input->post('id');
        $id_usuario = $this->input->post('id_usuario');
        $realizados = $this->input->post('realizados');
        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');
        $tipoVinculo = $this->input->post('tipo_vinculo');
        $status = $this->input->post('status');
        $mensagem = $this->input->post('mensagem');

        $this->load->helper(array('date'));

        $email['titulo'] = 'E-mail de convocação para Exame Periódico';
        $email['remetente'] = $this->session->userdata('id');
        $email['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        $status = true;

        $this->db->select('a.id_usuario, b.nome, b.email, a.data_programada');
        $this->db->select("DATE_FORMAT(a.data_programada, '%d/%m/%Y') AS data_programada", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        if ($id) {
            $this->db->where('a.id', $id);
        }
        if ($id_usuario) {
            $this->db->where('a.id_usuario', $id_usuario);
        }
        if ($realizados === '0') {
            $this->db->where('a.data_realizacao', null);
        } elseif ($realizados === '1') {
            $this->db->where('a.data_realizacao !=', null);
        }
        if ($mes) {
            $this->db->where("DATE_FORMAT(a.data_programada, '%m') =", $mes);
        }
        if ($ano) {
            $this->db->where("DATE_FORMAT(a.data_programada, '%Y') =", $ano);
        }
        if ($tipoVinculo) {
            $this->db->where('b.tipo_vinculo', $tipoVinculo);
        }
        if (strlen($status) > 0) {
            if ($status < 0) {
                $this->db->where('(b.status < 1 OR b.status > 9)', null, false);
            } else {
                $this->db->where('b.status', $status);
            }
        }
        $destinatarios = $this->db->get('usuarios_exame_periodico a')->result();

        $this->db->select("a.nome, a.email, IFNULL(b.email, a.email) AS email_empresa", false);
        $this->db->join('usuarios b', 'b.id = a.empresa', 'left');
        $this->db->where('a.id', $this->session->userdata('id'));
        $remetente = $this->db->get('usuarios a')->row();

        $this->load->library('email');

        foreach ($destinatarios as $destinatario) {
            if ($mensagem) {
                $email['mensagem'] = $mensagem;
            } else {
                $email['mensagem'] = "Caro colaborador, você está convocado para realizar exame médico periódico na data de: {$destinatario->data_programada}. Favor verificar com o Departamento de Gestão de Pessoas";
            }

            $this->email->from($remetente->email, $remetente->nome);
            $this->email->to($destinatario->email);

            $this->email->subject($email['titulo']);
            $this->email->message($email['mensagem']);

            if ($this->email->send()) {
                $email['destinatario'] = $destinatario->id_usuario;
                $this->db->query($this->db->insert_string('mensagensrecebidas', $email));
                $this->db->query($this->db->insert_string('mensagensenviadas', $email));
            } else {
                $status = false;
            }

            $this->email->clear();
        }

        if ($retorno) {
            echo json_encode(array('status' => $status));
        }
    }

}
