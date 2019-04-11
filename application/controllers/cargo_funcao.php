<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe Cargo_funcao
 *
 * Trabalha com os cargos e funções de uma empresa
 *
 * @author frank
 * @access public
 * @package CI_Controller\MY_Controller
 * @version 1.0
 */
class Cargo_funcao extends MY_Controller
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
     * Abre a tela de cargo/função
     *
     * @access public
     * @uses ..\views\cargo_funcao.php View
     */
    public function index()
    {
        $this->cargos();
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de cargo/função na prmeira aba
     *
     * @access public
     * @uses ..\views\cargo_funcao.php View
     */
    public function cargos()
    {
        $data = $this->input->get();
        $data['empresa'] = $this->session->userdata('empresa');
        $data['indice'] = 0;
        $this->load->view('cargo_funcao', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de cargo/função na segunda aba
     *
     * @access public
     * @uses ..\views\cargo_funcao.php View
     */
    public function funcoes()
    {
        $data = $this->input->get();
        $data['empresa'] = $this->session->userdata('empresa');
        $data['indice'] = 1;
        $this->load->view('cargo_funcao', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de cargos existentes
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_cargo()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.cargo,
                       s.familia_CBO
                FROM (SELECT a.id, 
                             a.nome AS cargo,
                             a.familia_CBO
                      FROM empresa_cargos a
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}
                ORDER BY a.nome ASC) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.cargo', 's.familia_CBO');
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
        foreach ($list as $cargo) {
            $row = array();
            $row[] = $cargo->cargo;
            $row[] = $cargo->familia_CBO;
            $row[] = '
                      <button class="btn btn-sm btn-info" onclick="edit_cargo(' . $cargo->id . ')" title="Editar cargo"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_cargo(' . $cargo->id . ')" title="Excluir cargo"><i class="glyphicon glyphicon-trash"></i></button>
                      <button class="btn btn-sm btn-primary" onclick="nextFuncao(' . $cargo->id . ')" title="Funções"><i class="glyphicon glyphicon-list"></i> Funções</button>
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
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_funcao()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.cargo,
                       s.familia_CBO,
                       s.funcao,
                       s.ocupacao_CBO
                FROM (SELECT a.id, 
                             b.nome AS cargo,
                             b.familia_CBO,
                             a.nome AS funcao,
                             a.ocupacao_CBO
                      FROM empresa_cargos b
                      LEFT JOIN empresa_funcoes a
                                ON b.id = a.id_cargo
                      WHERE b.id_empresa = {$this->session->userdata('empresa')}
                            AND (b.id = '{$post['id_cargo']}' OR CHAR_LENGTH('{$post['id_cargo']}') = 0)
                      ORDER BY a.nome ASC) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.cargo', 's.familia_CBO', 's.funcao', 's.ocupacao_CBO');
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
        foreach ($list as $funcao) {
            $row = array();
            $row[] = $funcao->cargo;
            $row[] = $funcao->familia_CBO;
            if ($funcao->id) {
                $row[] = $funcao->funcao;
                $row[] = $funcao->ocupacao_CBO;
                $row[] = '
                          <button class="btn btn-sm btn-info" onclick="edit_funcao(' . $funcao->id . ')" title="Editar função"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger" onclick="delete_funcao(' . $funcao->id . ')" title="Excluir função"><i class="glyphicon glyphicon-trash"></i></button>
                         ';
            } else {
                $row[] = '<span class="text-muted">Nenhuma função encontrada</span>';
                $row[] = '';
                $row[] = '
                          <button class="btn btn-sm btn-info disabled" title="Editar função"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger disabled" title="Excluir função"><i class="glyphicon glyphicon-trash"></i></button>
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
     * Retorna dados para edição de um cargo
     *
     * @access public
     */
    public function ajax_editCargo()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('empresa_cargos', array('id' => $id))->row();
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de uma função
     *
     * @access public
     */
    public function ajax_editFuncao()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('empresa_funcoes', array('id' => $id))->row();
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra um novo cargo
     *
     * @access public
     */
    public function ajax_addCargo()
    {
        $data = $this->input->post();
        if (empty($data['familia_CBO'])) {
            $data['familia_CBO'] = null;
        }
        $status = $this->db->insert('empresa_cargos', $data);
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra uma nova função
     *
     * @access public
     */
    public function ajax_addFuncao()
    {
        $data = $this->input->post();
        if (empty($data['ocupacao_CBO'])) {
            $data['ocupacao_CBO'] = null;
        }
        $status = $this->db->insert('empresa_funcoes', $data);
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de cargo
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validarCargo()
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
     * Valida os dados para inserção de função
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validarFuncao()
    {
        $config = array(
            array('field' => 'id_cargo', 'rules' => 'callback_verificaCargo'),
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
     * Altera um cargo
     *
     * @access public
     */
    public function ajax_updateCargo()
    {
        $data = $this->input->post();
        if (empty($data['familia_CBO'])) {
            $data['familia_CBO'] = null;
        }
        $id = $this->input->post('id');
        unset($data['id']);
        $status = $this->db->update('empresa_cargos', $data, array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Altera uma função
     *
     * @access public
     */
    public function ajax_updateFuncao()
    {
        $data = $this->input->post();
        if (empty($data['ocupacao_CBO'])) {
            $data['ocupacao_CBO'] = null;
        }
        $id = $this->input->post('id');
        unset($data['id']);
        $status = $this->db->update('empresa_funcoes', $data, array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de cargo
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidarCargo()
    {
        $config = array(
            array('field' => 'id', 'rules' => 'callback_verificaId'),
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
     * Valida os dados para alteração de função
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidarFuncao()
    {
        $config = array(
            array('field' => 'id', 'rules' => 'callback_verificaId'),
            array('field' => 'id_cargo', 'rules' => 'callback_verificaCargo'),
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
     * Exclui um cargo
     *
     * @access public
     */
    public function ajax_deleteCargo()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('empresa_cargos', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui uma função
     *
     * @access public
     */
    public function ajax_deleteFuncao()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('empresa_funcoes', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

}
