<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe Detalhe_model
 *
 * @author Marques
 * @access public
 * @package CI_Model\MY_Model\st
 * @version 1.0
 */
class Detalhe_model extends MY_Model
{

    /**
     * @access protected
     * @var string $table Nome da tabela principal do model
     */
    protected $table = 'st_detalhes';

    // -------------------------------------------------------------------------

    /**
     * Construtor da classe
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    // -------------------------------------------------------------------------

    /**
     * Faz a validação dos dados a serem gravados no banco
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validar()
    {
        $rules = array(
            array('field' => 'id', 'rules' => 'integer|max_length[11]'),
            array('field' => 'codigo', 'rules' => 'required|max_length[30]|is_unique[$this->table.CODIGO]'),
            array('field' => 'nome', 'rules' => 'required|max_length[255]'),
            array('field' => 'id_empresa', 'rules' => 'required|integer|max_length[11]')
        );

        $this->load->library('form_validation');

        $this->form->validation->set_rules($rules);

        return $this->form_validation->run();
    }

    // -------------------------------------------------------------------------

    /**
     * Faz a validação dos dados a serem alterados no banco
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidar()
    {
        $rules = array(
            array('field' => 'id', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'codigo', 'rules' => "required|max_length[30]"),
            array('field' => 'nome', 'rules' => 'required|max_length[255]'),
            array('field' => 'id_empresa', 'rules' => 'required|integer|max_length[11]')
        );

        $this->load->library('form_validation');

        $this->form->validation->set_rules($rules);

        return $this->form_validation->run();
    }

    // -------------------------------------------------------------------------

    /**
     * Seleciona os dados cadastrados da tabela
     *
     * @access public
     * @param mixed[] $where Array com o(s) atributo(s) da cláusula WHERE
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function select($where)
    {
        return $this->db->get_where($this->table, $where)->row();
    }

    // -------------------------------------------------------------------------

    /**
     * Seleciona um grupo de dados cadastrados da tabela
     *
     * @access public
     * @param mixed[] $where Array com o(s) atributo(s) da cláusula WHERE
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function find($where)
    {
        return $this->db->get_where($this->table, $where)->result();
    }

    // -------------------------------------------------------------------------

    /**
     * Insere os dados na tabela principal
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function insert()
    {
        $data = $this->input->post();
        return $this->db->insert($this->table, $data) !== false;
    }

    // -------------------------------------------------------------------------

    /**
     * Insere um grupo de dados na tabela principal
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function insertBatch()
    {
        
    }

    // -------------------------------------------------------------------------

    /**
     * Atualiza os dados na tabela principal
     *
     * @access public
     * @param mixed[] $where Array com o(s) atributo(s) da cláusula WHERE
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function update($where)
    {
        $data = $this->input->post();
        return $this->db->update($this->table, $data, $where) !== false;
    }

    // -------------------------------------------------------------------------

    /**
     * Atualiza um grupo de dados na tabela principal
     *
     * @access public
     * @param mixed[] $where Array com o(s) atributo(s) da cláusula WHERE
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function updateBatch($where)
    {
        
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui os dados da tabela principal
     *
     * @access public
     * @param mixed[] $where Array com o(s) atributo(s) da cláusula WHERE
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function delete($where)
    {
        return $this->db->delete($this->table, $where) !== false;
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui um grupo de dados da tabela principal
     *
     * @access public
     * @param mixed[] $where Array com o(s) atributo(s) da cláusula WHERE
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function deleteBatch($where)
    {
        
    }

}
