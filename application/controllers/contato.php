<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contato extends MY_Controller
{

    public function novaMensagem()
    {
        $this->load->helper('form');
        $this->load->view('fale-conosco');
    }

    public function enviarMensagem()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $post = $this->input->post();

        //Verifica o POST
        if ($post['mensagem']) {
            # Variáveis
            $nome = $this->session->userdata('nome');
            $remetente = $this->session->userdata('email');
            $assunto = $post['assunto'];
            $mensagem = $post['mensagem'];

            $this->load->helper("phpmailer");

            if (send_email_faleConosco($nome, $remetente, $assunto, $mensagem)) {
                echo json_encode(array('retorno' => 1, 'aviso' => 'Mensagem enviada com sucesso', 'redireciona' => 1, 'pagina' => site_url('contato/novaMensagem/')));
            } else {
                echo json_encode(array('retorno' => 0, 'aviso' => 'Erro no envio da mensagem. Por favor, tente mais tarde', 'redireciona' => 0, 'pagina' => ''));
            }
        }
    }

}
