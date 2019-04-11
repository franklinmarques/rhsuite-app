<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe Setor_model
 *
 * @author Marques
 * @access public
 * @package CI_Model\MY_Model\st
 * @version 1.0
 */
class Setor_model extends MY_Model
{

    /**
     * @access protected
     * @var string $table Nome da tabela principal do model
     */
    protected $table = 'alocacao_unidades';

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
        
    }

    // -------------------------------------------------------------------------

    /**
     * Seleciona um grupo de dados cadastrados da tabela
     *
     * @access public
     * @param mixed[] $where Array com o(s) atributo(s) da cláusula WHERE
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function selectAll($where)
    {
        
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
