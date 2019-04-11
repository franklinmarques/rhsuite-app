<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Usuariodemissao
 *
 * Trabalha com os dados de demissão do usuário
 *
 * @package model
 */
class Usuariodemissao_model extends CI_Model
{

    /**
     * Nome da tabela usada pelo model
     *
     * @var string Afastamento
     */
    protected $table = 'usuarios_demissao';

    // -------------------------------------------------------------------------

    /**
     * Construtor.
     *
     * Carrega o model
     */
    public function __construct()
    {
        parent::__construct();
    }


    // -------------------------------------------------------------------------

    /**
     * Faz a validação dos dados a serem inseridos no banco
     *
     * @todo Acrescentar regra de validação (data_demissao < data_cadastro)
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validar()
    {
        $rules = array(
            array('field' => 'id', 'rules' => 'integer|max_length[11]'),
            array('field' => 'id_usuario', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'id_empresa', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'data_demissao', 'rules' => 'required|max_length[10]')
        );

        $this->load->library('form_validation');

        $this->form_validation->set_rules($rules);

        return $this->form_validation->run();
    }

    // -------------------------------------------------------------------------

    /**
     * Faz a validação dos dados a serem alterados no banco
     *
     * @todo Acrescentar regra de validação (data_afastamento < data_retorno)
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidar()
    {
        $rules = array(
            array('field' => 'id', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'id_usuario', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'id_empresa', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'data_demissao', 'rules' => 'required|max_length[10]')
        );

        $this->load->library('form_validation');

        $this->form_validation->set_rules($rules);

        return $this->form_validation->run();
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna um registro da tabela principal do model
     *
     * @param array $where
     * @return mixed
     */
    public function select($where = array())
    {
        $this->db->select('id, id_usuario, id_empresa, motivo_demissao, observacoes');
        $this->db->select("DATE_FORMAT(data_demissao, '%d/%m/%Y') AS data_demissao", false);
        if ($where) {
            $this->db->where($where);
        }

        return $this->db->get($this->table)->row();
    }

    // -------------------------------------------------------------------------

    /**
     * Insere os dados na tabela principal
     *
     * @todo Retirar verificadores para datas vazias no Codeigniter 3.18
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function insert()
    {
        $data = $this->input->post();
        if (!$data['observacoes']) {
            $data['observacoes'] = null;
        }
        return $this->db->insert($this->table, $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Atualiza os dados na tabela principal
     *
     * @todo Retirar verificadores para datas vazias no Codeigniter 3.18
     *
     * @access public
     * @param mixed[] $where Array com o(s) atributo(s) da cláusula WHERE
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function update($where)
    {
        $data = $this->input->post();
        if (!$data['observacoes']) {
            $data['observacoes'] = null;
        }
        return $this->db->update($this->table, $data, $where);
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
        return $this->db->delete($this->table, $where);
    }

}
