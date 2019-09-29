<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe CursosDisciplinas
 *
 * Trabalha com os cursos e disciplinas do módulo de Educação Inclusiva
 *
 * @author Franklin Marques
 * @access public
 * @package CI_Controller\MY_Controller
 * @version 1.0
 */
class CursosDisciplinas extends MY_Controller
{

    /**
     * Construtor da classe
     *
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de curso/disciplina
     *
     * @access public
     * @uses ..\views\ei\cursosDisciplinas.php View
     */
    public function index()
    {
        $this->cursos();
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de curso/disciplina na prmeira aba
     *
     * @access public
     * @uses ..\views\ei\cursosDisciplinas.php View
     */
    public function cursos()
    {
        $data = $this->input->get();
        $data['empresa'] = $this->session->userdata('empresa');
        $data['indice'] = 0;

        $this->db->select('a.id, a.nome');
        $this->db->order_by('a.nome', 'asc');
        $data['clientes'] = array_column($this->db->get('ei_diretorias a')->result(), 'nome', 'id');

        $this->load->view('ei/cursosDisciplinas', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de curso/disciplina na segunda aba
     *
     * @access public
     * @uses ..\views\ei\cursosDisciplinas.php View
     */
    public function disciplinas()
    {
        $data = $this->input->get();
        $data['empresa'] = $this->session->userdata('empresa');
        $data['indice'] = 1;
        $this->load->view('ei/cursosDisciplinas', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de cursos existentes
     *
     * @access public
     */
    public function ajax_cursos()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.diretoria,
                       s.nome,
                       s.escola
                FROM (SELECT a.id, 
                             a.nome,
                             a2.nome AS diretoria,
                             CONCAT(c.codigo, ' - ', c.nome) AS escola
                      FROM ei_cursos a
                      INNER JOIN ei_diretorias a2 ON 
                                 a2.id = a.id_diretoria
                      LEFT JOIN ei_escolas_cursos b ON 
                                b.id_curso = a.id
                      LEFT JOIN ei_escolas c ON 
                                c.id = b.id_escola
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}
                ORDER BY a.nome ASC) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.diretoria', 's.nome', 's.escola');
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
        foreach ($list as $curso) {
            $row = array();
            $row[] = $curso->diretoria;
            $row[] = $curso->nome;
            $row[] = $curso->escola;
            $row[] = '
                      <button class="btn btn-sm btn-info" onclick="edit_curso(' . $curso->id . ')" title="Editar curso"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_curso(' . $curso->id . ')" title="Excluir curso"><i class="glyphicon glyphicon-trash"></i></button>
                      <button class="btn btn-sm btn-primary" onclick="nextDisciplina(' . $curso->id . ')" title="Disciplinas"><i class="glyphicon glyphicon-list"></i> Disciplinas</button>
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
     * Retorna lista de funções existentes
     *
     * @access public
     */
    public function ajax_disciplinas()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome_curso,
                       s.nome,
                       s.qtde_semestres
                FROM (SELECT a.id, 
                             b.nome AS nome_curso,
                             a.nome,
                             a.qtde_semestres
                      FROM ei_cursos b
                      LEFT JOIN ei_disciplinas a
                                ON b.id = a.id_curso
                      WHERE b.id_empresa = {$this->session->userdata('empresa')}
                            AND (b.id = '{$post['id_curso']}' OR CHAR_LENGTH('{$post['id_curso']}') = 0)
                      ORDER BY a.nome ASC) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome_curso', 's.nome');
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
        foreach ($list as $disciplina) {
            $row = array();
            $row[] = $disciplina->nome_curso;
            if ($disciplina->id) {
                $row[] = $disciplina->nome;
                $row[] = $disciplina->qtde_semestres;
                $row[] = '
                          <button class="btn btn-sm btn-info" onclick="edit_disciplina(' . $disciplina->id . ')" title="Editar disciplina"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger" onclick="delete_disciplina(' . $disciplina->id . ')" title="Excluir disciplina"><i class="glyphicon glyphicon-trash"></i></button>
                         ';
            } else {
                $row[] = '<span class="text-muted">Nenhuma disciplina encontrada</span>';
                $row[] = null;
                $row[] = '
                          <button class="btn btn-sm btn-info disabled" title="Editar disciplina"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger disabled" title="Excluir disciplina"><i class="glyphicon glyphicon-trash"></i></button>
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
        //output to json format
        echo json_encode($output);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de um curso
     *
     * @access public
     */
    public function atualizarEscolas()
    {
        $id = $this->input->post('id');

        $this->db->select("a.id, CONCAT(a.codigo, ' - ', a.nome) AS nome", false);
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria', 'left');
        $this->db->where('b.id', $id);
        $this->db->order_by('a.codigo', 'asc');
        $this->db->order_by('a.nome', 'asc');
        $escolas = array_column($this->db->get('ei_escolas a')->result(), 'nome', 'id');

        $data['escolas'] = form_multiselect('id_escola[]', $escolas, array(), 'id="id_escola" class="demo2" size="8"');

        echo json_encode($data);
    }
    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de um curso
     *
     * @access public
     */
    public function ajax_editCurso()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('ei_cursos', array('id' => $id))->row();

        $this->db->select('id, nome');
        $this->db->where('id_diretoria', $data->id_diretoria);
        $escolas = array_column($this->db->get('ei_escolas')->result(), 'nome', 'id');

        $this->db->select('id_escola');
        $this->db->where('id_curso', $id);
        $idEscolas = $this->db->get('ei_escolas_cursos')->result_array();
        $escolasVinculadas = array_column($idEscolas, 'id_escola');

        $data->escolas = form_multiselect('id_escola[]', $escolas, $escolasVinculadas, 'id="id_escola" class="demo2" size="8"');

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de uma disciplina
     *
     * @access public
     */
    public function ajax_editDisciplina()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('ei_disciplinas', array('id' => $id))->row();
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra um novo curso
     *
     * @access public
     */
    public function ajax_addCurso()
    {
        $data = $this->input->post();

        $this->db->where('id_diretoria', $data['id_diretoria']);
        $this->db->where('nome', $data['nome']);
        $qtdeCursos = $this->db->get('ei_cursos')->num_rows();
        if ($qtdeCursos) {
            exit(json_encode(array('erro' => "Este curso já existe e se encontra cadastrado em outras unidades. \nPara cadastrá-lo em uma nova unidade, basta editar o curso e relacionar a nova unidade desejada.")));
        }

        $idEscolas = $this->input->post('id_escola');
        if (empty($idEscolas)) {
            $idEscolas = array();
        }
        unset($data['id_escola']);
        $status = $this->db->insert('ei_cursos', $data);

        if ($status) {
            $idCurso = $this->db->insert_id();
            $data2 = array();
            foreach ($idEscolas as $idEscola) {
                $data2[] = array('id_escola' => $idEscola, 'id_curso' => $idCurso);
            }
            if ($data2) {
                $this->db->insert_batch('ei_escolas_cursos', $data2);
            }
        }

        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra uma nova disciplina
     *
     * @access public
     */
    public function ajax_addDisciplina()
    {
        $data = $this->input->post();
        if (strlen($data['qtde_semestres']) == 0) {
            $data['qtde_semestres'] = null;
        }
        $status = $this->db->insert('ei_disciplinas', $data);
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de curso
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validarCurso()
    {
        $config = array(
            array('field' => 'id_empresa', 'rules' => 'callback_verificaEmpresa'),
            array('field' => 'nome', 'rules' => 'required|max_length[255]'),
        );

        $this->load->form_validation();
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_string();
        }
        return true;
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de disciplina
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validarDisciplina()
    {
        $config = array(
            array('field' => 'id_curso', 'rules' => 'callback_verificaCurso'),
            array('field' => 'nome', 'rules' => 'required|max_length[255]'),
            array('field' => 'qtde_semestres', 'rules' => 'is_natural_no_zero|max_length[2]')
        );

        $this->load->form_validation();
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_string();
        }
        return true;
    }

    // -------------------------------------------------------------------------

    /**
     * Altera um curso
     *
     * @access public
     */
    public function ajax_updateCurso()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        $id_escolas = $this->input->post('id_escola');
        if (empty($id_escolas)) {
            $id_escolas = array();
        }

        unset($data['id'], $data['id_escola']);
        $this->db->select('id, id_escola');
        $this->db->where('id_curso', $id);
        $escolasCursos = array_column($this->db->get('ei_escolas_cursos')->result(), 'id_escola', 'id');

        $this->db->trans_start();
        $this->db->update('ei_cursos', $data, array('id' => $id));

        foreach ($escolasCursos as $idEscolaCurso => $escolaCurso) {
            if (!in_array($escolaCurso, $id_escolas)) {
                $this->db->delete('ei_escolas_cursos', array('id' => $idEscolaCurso));
            }
        }
        foreach ($id_escolas as $id_escola) {
            $data2 = array('id_curso' => $id, 'id_escola' => $id_escola);
            $where = array_search($id_escola, $escolasCursos);

            if ($where !== false) {
                $this->db->update('ei_escolas_cursos', $data2, array('id' => $where));
            } else {
                $this->db->insert('ei_escolas_cursos', $data2);
            }
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Altera uma disciplina
     *
     * @access public
     */
    public function ajax_updateDisciplina()
    {
        $data = $this->input->post();
        if (strlen($data['qtde_semestres']) == 0) {
            $data['qtde_semestres'] = null;
        }
        $id = $this->input->post('id');
        unset($data['id']);
        $status = $this->db->update('ei_disciplinas', $data, array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de curso
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidarCurso()
    {
        $config = array(
            array('field' => 'id', 'rules' => 'callback_verificaCurso'),
            array('field' => 'id', 'rules' => 'required|numeric|max_length[11]'),
            array('field' => 'nome', 'rules' => 'required|max_length[255]'),
        );

        $this->load->form_validation();
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_string();
        }
        return true;
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de disciplina
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidarDisciplina()
    {
        $config = array(
            array('field' => 'id', 'rules' => 'callback_verificaDisciplina'),
            array('field' => 'id_curso', 'rules' => 'callback_verificaCurso'),
            array('field' => 'nome', 'rules' => 'required|max_length[255]'),
            array('field' => 'qtde_semestres', 'rules' => 'is_natural_no_zero|max_length[2]')
        );

        $this->load->form_validation();
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_string();
        }
        return true;
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui um curso
     *
     * @access public
     */
    public function ajax_deleteCurso()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_cursos', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui uma disciplina
     *
     * @access public
     */
    public function ajax_deleteDisciplina()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_disciplinas', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    /*
    * --------------------------------------------------------------------------
    * Callbacks
    * --------------------------------------------------------------------------
    */
    private function verificaEmpresa($id)
    {
        if (!$this->db->get_where('usuarios', array('id' => $id))->num_rows()) {
            $this->form_validation->set_message('verificaEmpresa', 'A empresa não foi encontrada');
            return false;
        }
        return true;
    }

    private function verificaCurso($id)
    {
        if (!$this->db->get_where('ei_cursos', array('id' => $id))->num_rows()) {
            $this->form_validation->set_message('verificaCurso', 'O campo %s não foi encontrado');
            return false;
        }
        return true;
    }

    private function verificaDisciplina($id)
    {
        if (!$this->db->get_where('ei_disciplinas', array('id' => $id))->num_rows()) {
            $this->form_validation->set_message('verificaDisciplina', 'O campo %s não foi encontrado');
            return false;
        }
        return true;
    }

}
