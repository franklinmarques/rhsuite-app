<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe Estruturas
 *
 * Trabalha com as estruturas (departamentos, áreas e setores) de uma empresa
 *
 * @author frank
 * @access public
 * @package CI_Controller\MY_Controller
 * @version 1.0
 */
class Estruturas extends MY_Controller
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
     * Abre a tela de estruturas
     *
     * @access public
     * @uses ..\views\estruturas.php View
     */
    public function index()
    {
        $this->departamentos();
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de estruturas na prmeira aba
     *
     * @access public
     * @uses ..\views\estruturas.php View
     */
    public function departamentos()
    {
        $data = $this->input->get();
        $data['empresa'] = $this->session->userdata('empresa');
        $data['indice'] = 0;
        $this->load->view('estruturas', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de estruturas na segunda aba
     *
     * @access public
     * @uses ..\views\estruturas.php View
     */
    public function areas()
    {
        $data = $this->input->get();
        $data['empresa'] = $this->session->userdata('empresa');
        $data['indice'] = 1;
        $this->load->view('estruturas', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de estruturas na terceira aba
     *
     * @access public
     * @uses ..\views\estruturas.php View
     */
    public function setores()
    {
        $data = $this->input->get();
        $data['empresa'] = $this->session->userdata('empresa');
        $data['indice'] = 2;
        $this->load->view('estruturas', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de departamentos existentes
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_departamento()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.depto
                FROM (SELECT a.id, 
                             a.nome AS depto
                      FROM empresa_departamentos a
                      WHERE a.id_empresa = {$this->session->userdata('empresa')}) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.depto');
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
        foreach ($list as $departamento) {
            $row = array();
            $row[] = $departamento->depto;
            $row[] = '
                      <button class="btn btn-sm btn-info" onclick="edit_depto(' . $departamento->id . ')" title="Editar departamento"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_depto(' . $departamento->id . ')" title="Excluir departamento"><i class="glyphicon glyphicon-trash"></i></button>
                      <button class="btn btn-sm btn-primary" onclick="nextArea(' . $departamento->id . ')" title="Áreas"><i class="glyphicon glyphicon-list"></i> Áreas</button>
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
     * Retorna lista de áreas existentes
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_area()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.depto,
                       s.area
                FROM (SELECT a.id, 
                             b.nome AS depto,
                             a.nome AS area
                      FROM empresa_departamentos b
                      LEFT JOIN empresa_areas a
                                 ON b.id = a.id_departamento
                      WHERE b.id_empresa = {$this->session->userdata('empresa')}
                            AND (b.id = '{$post['id_depto']}' OR CHAR_LENGTH('{$post['id_depto']}') = 0)) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.depto', 's.area');
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
        foreach ($list as $area) {
            $row = array();
            $row[] = $area->depto;
            if ($area->id) {
                $row[] = $area->area;
                $row[] = '
                          <button class="btn btn-sm btn-info" onclick="edit_area(' . $area->id . ')" title="Editar área"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger" onclick="delete_area(' . $area->id . ')" title="Excluir área"><i class="glyphicon glyphicon-trash"></i></button>
                          <button class="btn btn-sm btn-primary" onclick="nextSetor(' . $area->id . ')" title="Setores"><i class="glyphicon glyphicon-list"></i> Setores</button>
                         ';
            } else {
                $row[] = '<span class="text-muted">Nenhuma área encontrada</span>';
                $row[] = '
                          <button class="btn btn-sm btn-info disabled" title="Editar área"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger disabled" title="Excluir área"><i class="glyphicon glyphicon-trash"></i></button>
                          <button class="btn btn-sm btn-primary disabled" title="Setores"><i class="glyphicon glyphicon-list"></i> Setores</button>
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
     * Retorna lista de setores existentes
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_setor()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.depto,
                       s.area,
                       s.setor
                FROM (SELECT a.id, 
                             c.nome AS depto,
                             b.nome AS area,
                             a.nome AS setor
                      FROM empresa_departamentos c 
                      INNER JOIN empresa_areas b
                                 ON c.id = b.id_departamento 
                      LEFT JOIN empresa_setores a
                                ON b.id = a.id_area
                      WHERE c.id_empresa = {$this->session->userdata('empresa')}
                            AND (c.id = '{$post['id_depto']}' OR CHAR_LENGTH('{$post['id_depto']}') = 0)
                            AND (b.id = '{$post['id_area']}' OR CHAR_LENGTH('{$post['id_area']}') = 0)) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.depto', 's.area', 's.setor');
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
        foreach ($list as $setor) {
            $row = array();
            $row[] = $setor->depto;
            $row[] = $setor->area;
            if ($setor->id) {
                $row[] = $setor->setor;
                $row[] = '
                          <button class="btn btn-sm btn-info" onclick="edit_setor(' . $setor->id . ')" title="Editar setor"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger" onclick="delete_setor(' . $setor->id . ')" title="Excluir setor"><i class="glyphicon glyphicon-trash"></i></button>
                         ';
            } else {
                $row[] = '<span class="text-muted">Nenhum setor encontrado</span>';
                $row[] = '
                          <button class="btn btn-sm btn-info disabled" title="Editar setor"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger disabled" title="Excluir setor"><i class="glyphicon glyphicon-trash"></i></button>
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
     * Retorna dados para edição de um departamento
     *
     * @access public
     */
    public function ajax_editDepto()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('empresa_departamentos', array('id' => $id))->row();
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de uma área
     *
     * @access public
     */
    public function ajax_editArea()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('empresa_areas', array('id' => $id))->row();
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de um setor
     *
     * @access public
     */
    public function ajax_editSetor()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('empresa_setores', array('id' => $id))->row();
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra um novo departamento
     *
     * @access public
     */
    public function ajax_addDepto()
    {
        $data = $this->input->post();
        $status = $this->db->insert('empresa_departamentos', $data);
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra uma nova área
     *
     * @access public
     */
    public function ajax_addArea()
    {
        $data = $this->input->post();
        $status = $this->db->insert('empresa_areas', $data);
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra um novo setor
     *
     * @access public
     */
    public function ajax_addSetor()
    {
        $data = $this->input->post();
        $status = $this->db->insert('empresa_setores', $data);
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de estruturas
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validar()
    {
        return $this->email->validar();
    }

    // -------------------------------------------------------------------------

    /**
     * Altera um departamento
     *
     * @access public
     */
    public function ajax_updateDepto()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);
        $status = $this->db->update('empresa_departamentos', $data, array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Altera uma área
     *
     * @access public
     */
    public function ajax_updateArea()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);
        $status = $this->db->update('empresa_areas', $data, array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Altera um setor
     *
     * @access public
     */
    public function ajax_updateSetor()
    {
        $data = $this->input->post();
        $id = $this->input->post('id');
        unset($data['id']);
        $status = $this->db->update('empresa_setores', $data, array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de estruturas
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidar()
    {
        return $this->email->revalidar();
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui um departamento
     *
     * @access public
     */
    public function ajax_deleteDepto()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('empresa_departamentos', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui uma área
     *
     * @access public
     */
    public function ajax_deleteArea()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('empresa_areas', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui um setor
     *
     * @access public
     */
    public function ajax_deleteSetor()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('empresa_setores', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

}
