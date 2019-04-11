<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{

    public function autenticar()
    {

    }

    public function desconectar()
    {
        $data = array(
            'data_saida' => date()
        );
        $where = array(
            'id' => $this->session->userdata('id_acesso')
        );

        $this->load->model('acessoSistema_model', 'acesso');
        $this->acesso->update($data, $where);


        $this->load->model('arquivosTemp_model', 'temp');
        $this->temp->limparArquivos($this->session->userdata('id'));


        $this->load->model('usuarios_model', 'usuario');
        $usuario = $this->usuario->find($this->session->userdata('id'));
        if ($usuario->url) {
            $this->config->set_item('index_page', $usuario->url);
        }

        session_start();
        session_destroy();

        $this->session->sess_destroy();

        redirect(site_url('login'));
    }

}

