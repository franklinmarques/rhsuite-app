<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Erro extends MY_Controller
{

    public function index()
    {
        $uri = $this->uri->rsegment(1);
        $empresa = $this->session->userdata('url');

        // Exibe mensagem de erro de requisicao (erro 400)
        if (!$uri and $empresa) {
            show_404();
        }

        if ($uri) {
            // Exibe mensagem de erro em caso de falha (erro 404)
            if ($this->db->get_where('usuarios', array('tipo' => 'empresa', 'url' => $uri))->num_rows() == 0) {
                show_404();
            }

            // Redireciona para a tela de login
            if (!$this->session->userdata('logado')) {
                $this->load->helper(array('form'));
//                $this->session->set_userdata(array(
//                    'tipo' => 'empresa',
//                    'url' => $uri,
//                ));
//                $this->config->set_item('base_url', base_url($uri));
                redirect('login');
            }

            // Exibe mensagem de erro de permissao (erro 401)
            if ($uri !== $empresa) {
                show_404();
            }
        }
    }

}
