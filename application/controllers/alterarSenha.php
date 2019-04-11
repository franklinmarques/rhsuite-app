<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AlterarSenha extends CI_Controller
{

    public function index()
    {
        if (empty($this->uri->rsegment(3))) {
            redirect(site_url('home'));
        }

        $this->db->select('nome, token');
        $this->db->where('token', $this->uri->rsegment(3));
        $data = $this->db->get('usuarios')->row();

        if (empty($data)) {
            redirect(site_url('home'));
        }

        $this->load->view('alterar_senha', $data);
    }

    //--------------------------------------------------------------------------

    public function salvar()
    {
        $post = $this->input->post();
        $post['token'] = $this->uri->rsegment(3);
        $retorno = [
            'retorno' => 0,
            'aviso' => 'Senha alterada com sucesso'
        ];

        if (empty($post['token'])) {
            $retorno['aviso'] = 'O token não pode ficar em branco';
            exit(json_encode($retorno));
        }

        if (strlen($post['novasenha']) == 0 and strlen($post['confirmarsenha']) == 0) {
            $retorno['aviso'] = 'O formulário está vazio';
            exit(json_encode($retorno));
        } else {
            if (strlen($post['novasenha']) == 0) {
                $retorno['aviso'] = 'A nova senha não pode ficar em branco';
                exit(json_encode($retorno));
            }

            if (strlen($post['confirmarsenha']) == 0) {
                $retorno['aviso'] = 'O campo confirmar senha não pode ficar em branco';
                exit(json_encode($retorno));
            }
        }

        if ($post['novasenha'] !== $post['confirmarsenha']) {
            $retorno['aviso'] = 'A senha não pode ser diferente da confirmar senha';
            exit(json_encode($retorno));
        }

        $usuario = $this->db->get_where('usuarios', ['token' => $post['token']])->row();

        if (empty($usuario)) {
            $retorno['aviso'] = 'Não existe usuário cadastrado com esse token';
            exit(json_encode($retorno));
        }


        $this->load->model('Usuarios_model', 'usuario');

        $data = array(
            'senha' => $this->usuarios->setPassword($post['novasenha']),
            'token' => uniqid(),
            'dataeditado' => date()
        );

        if (($msgErro = $this->db->update('usuarios', $data, ['id' => $usuario->id])) !== true) {
            $retorno['aviso'] = $msgErro;
            exit(json_encode($retorno));
        }

        if (($msg = $this->auth->restoreAccount($post['id'], $post['novasenha'])) !== true) {
            exit(json_encode(['retorno' => 0, 'aviso' => $msg]));
        }

        $retorno['redireciona'] = 1;
        $retorno['pagina'] = site_url('home');

        echo json_encode($retorno);
    }

}
