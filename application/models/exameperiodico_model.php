<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model ExamePeriodico
 *
 * Trabalha com os exames periódicos dos usuários
 *
 * @package model
 */
class Exameperiodico_model extends CI_Model
{

    /**
     * Nome da tabela usada pelo model
     *
     * @var stringExamePeriodico
     */
    protected $table = 'usuarios_exame_periodico';

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
     * @todo Acrescentar regra de validação (data_programada <= data_realizacao <= data_entrega)
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validar()
    {
        $rules = array(
            array('field' => 'id', 'rules' => 'integer|max_length[11]'),
            array('field' => 'id_usuario', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'data_programada', 'rules' => 'required|max_length[10]'),
            array('field' => 'data_realizacao', 'rules' => 'max_length[10]'),
            array('field' => 'data_entrega', 'rules' => 'max_length[10]')
        );

        $this->load->library('form_validation');

        $this->form_validation->set_rules($rules);

        return $this->form_validation->run();
    }

    // -------------------------------------------------------------------------

    /**
     * Faz a validação dos dados a serem alterados no banco
     *
     * @todo Acrescentar regra de validação (data_programada <= data_realizacao <= data_entrega)
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidar()
    {
        $rules = array(
            array('field' => 'id', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'id_usuario', 'rules' => 'required|integer|max_length[11]'),
            array('field' => 'data_programada', 'rules' => 'required|max_length[10]'),
            array('field' => 'data_realizacao', 'rules' => 'max_length[10]'),
            array('field' => 'data_entrega', 'rules' => 'max_length[10]')
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
        $this->db->select('id, id_usuario, observacoes, local_exame');
        $this->db->select("DATE_FORMAT(data_programada, '%d/%m/%Y') AS data_programada", false);
        $this->db->select("DATE_FORMAT(data_realizacao, '%d/%m/%Y') AS data_realizacao", false);
        $this->db->select("DATE_FORMAT(data_entrega, '%d/%m/%Y') AS data_entrega", false);
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
        if (!$data['data_realizacao']) {
            $data['data_realizacao'] = null;
        }
        if (!$data['data_entrega']) {
            $data['data_entrega'] = null;
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
        if (!$data['data_realizacao']) {
            $data['data_realizacao'] = null;
        }
        if (!$data['data_entrega']) {
            $data['data_entrega'] = null;
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
