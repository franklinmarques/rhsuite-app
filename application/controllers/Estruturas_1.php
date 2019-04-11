<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe Email
 *
 * Trabalha com o gerenciamento de e-mails
 *
 * @author frank
 * @access public
 * @package CI_Controller\MY_Controller
 * @version 1.0
 */
class Email extends MY_Controller
{

    /**
     * Construtor da classe
     *
     * Carrega o model de Email
     *
     * @access public
     * @uses ..\models\Email_model.php Model
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Email_model', 'email');
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de e-mails criados
     *
     * @access public
     * @uses ..\views\email.php View
     */
    public function index()
    {
        $data = $this->input->get();
        $this->load->view('email', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de e-mails criados a partir de um elememnto referencial
     *
     * @access public
     * @uses ..\views\email.php View
     */
    public function gerenciar()
    {
        $data = $this->input->get();
        $this->load->view('email', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de criar novo e-mail
     *
     * @access public
     * @uses ..\views\email_novo.php View
     */
    public function novo()
    {
        $data = $this->input->get();
        $this->load->view('email', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna opções para filtragem dos e-mails existentes
     *
     * @access public
     */
    public function atualizar_filtro()
    {
        $data = $this->input->post();
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de e-mails criados
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_list()
    {
        
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de e-mail
     *
     * @access public
     */
    public function ajax_edit()
    {
        $where = $this->input->post();
        $data = $this->email->select($where);
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra um novo e-mail
     *
     * @access public
     */
    public function ajax_add()
    {
        if (($msg = $this->validar()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        $status = $this->email->insert();
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de e-mail
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
     * Altera um e-mail existente
     *
     * @access public
     */
    public function ajax_update()
    {
        if (($msg = $this->email->update()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de e-mail
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
     * Exclui um e-mail existente
     *
     * @access public
     */
    public function ajax_delete()
    {
        if (($msg = $this->email->delete()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Exporta a lista de e-mails para um arquivo .pdf
     *
     * @access public
     * @uses ..\libraries\mpdf.php Library para montagem de arquivos .pdf
     */
    public function pdf()
    {
        
    }

}
