<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function index()
    {
        $uri = $this->uri->segment(1);
        $data['logoempresa'] = '';
        $data['logo'] = '';
        $data['cabecalho'] = '';
        $data['imagem_fundo'] = '';
        if ($uri != 'login') {
            $row = $this->db->query("SELECT u.* FROM usuarios u
                                   WHERE u.url = ?", $uri);
            if ($row->num_rows() > 0) {
                $data['logoempresa'] = $row->row()->url;
                $data['logo'] = $row->row()->foto;
                $data['cabecalho'] = $row->row()->cabecalho;
                $data['imagem_fundo'] = $row->row()->imagem_fundo;
            } else {
                show_404();
            }
        }

        $this->load->view('login', $data);
    }

    public function autenticacao_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $data = $this->input->post();

        if (empty($data['email'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O e-mail não pode ficar em branco')));
        }
        if (empty($data['senha'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A senha não pode ficar em branco')));
        }

        $this->load->model('Usuarios_model', 'usuarios');
        $data['senha'] = $this->usuarios->setPassword($data['senha']);

        $login = $this->usuarios->getUsuario($data['email'], $data['senha']);
        if (!$login) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nome de usuário / senha inválidos')));
        }

        $status_usuario = $login->status;
        // Se usuario for do tipo funcionario
        if ($login->empresa) {
            // Substitui o proprio status pelo status da empresa
            $empresa_status = $this->db->query("SELECT u.* FROM usuarios u
                                   WHERE u.id = ?", $login->empresa)->row();
            $login->status = $empresa_status->status;
        } else {
            // Substitui valor zerado de empresa por id do usuario
            $login->empresa = $login->id;
//            $this->config->set_item('index_page', $login->url);
        }

        if ($login->status == 5 || $status_usuario == 5) {
            if ($login->tipo == 'funcionario' || $login->tipo == 'candidato' || $login->tipo == 'cliente') {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Acesso inativo temporariamente. <br /> Favor contatar o gestor da plataforma. <br /> E-mail: <a href="' . $empresa_status->email . '">' . $empresa_status->email . '</a>')));
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Acesso inativo temporariamente. <br /> Favor contatar o administrador da plataforma. <br /> E-mail: <a href="contato@peoplenetcorp.com.br">contato@peoplenetcorp.com.br</a>')));
            }
        }

        // ** => Cabeçalho
        $cabecalho = $login->cabecalho;
        $logomarca = $login->foto;

        if ($login->empresa) {
            $row = $this->db->query("SELECT u.cabecalho, u.foto FROM usuarios u
                                           WHERE u.id = ?", $login->empresa)->row();

            $cabecalho = $row->cabecalho;
            $logomarca = $row->foto;

            // Se não existir, usa a logo do sistema
            if (!$logomarca) {
                $sistema = $this->db->query("SELECT u.cabecalho, u.foto FROM usuarios u
                                           WHERE u.email = ?", 'mhffortes@hotmail.com')->row();
                $logomarca = $sistema->foto;
            }
        }

        # Uso exclusivo para acessar arquivos com o KCFinder
        $dados_old = array('usuario' => $login->id, 'endereco_ip' => null, 'agente_usuario' => null);
        if ($this->session->userdata('logado')) {
            $id_old = $this->session->userdata('id');
            $this->db->select('usuario, endereco_ip, agente_usuario');
            $this->db->where('usuario', $id_old);
            $this->db->where('endereco_ip', $this->input->ip_address());
            $this->db->where('agente_usuario', $this->input->user_agent());
            $this->db->order_by('id', 'desc');
            $this->db->limit(1);
            $dados_old = $this->db->get('acessosistema')->row_array();

            $this->session->sess_destroy();
        } else {
            session_start();
        }


        $_SESSION['KCFINDER'] = array(
            'disabled' => false,
            'uploadURL' => "upload/{$login->id}",
            'uploadDir' => ""
        );
        if ($login->hash_acesso) {
            $this->load->library('encrypt');
            $hash_acesso = (array)json_decode($this->encrypt->decode($login->hash_acesso, base64_encode($login->id)));
        } else {
            $hash_acesso = array();
        }

        $this->session->set_userdata(array(
            'id' => $login->id,
            'empresa' => $login->empresa,
            'nome' => $login->nome,
            'tipo' => $login->tipo,
            'nivel' => $login->nivel_acesso,
            'email' => $login->email,
            'cabecalho' => $cabecalho,
            'logomarca' => $logomarca,
            'foto' => $login->foto,
            'foto_descricao' => $login->foto_descricao ?? null,
            'logado' => true,
            'hash_acesso' => $hash_acesso
        ));

        # Insere a data e hora da login
        $dados['usuario'] = $this->session->userdata('id');
        $dados['data_acesso'] = mdate("%Y-%m-%d %H:%i:%s");
        $dados['endereco_ip'] = $this->input->ip_address();
        $dados['agente_usuario'] = $this->input->user_agent();

        if ($dados['usuario'] == $dados_old['usuario'] and
            $dados['endereco_ip'] == $dados_old['endereco_ip'] and
            $dados['agente_usuario'] == $dados_old['agente_usuario']) {

            $dados_old['data_acesso'] = mdate("%Y-%m-%d %H:%i:%s");
            $dados_old['data_atualizacao'] = null;
            $dados_old['data_saida'] = null;
            $this->db->update('acessosistema', $dados_old, array('id' => $id_old));
        } else {
            $this->db->query($this->db->insert_string('acessosistema', $dados));
        }


        # Apaga arquivos temporários
        if ($login->tipo !== 'candidato') {
            $arquivos_temp = $this->db->query("SELECT * FROM arquivos_temp WHERE usuario = ?", $this->session->userdata('id'));
            foreach ($arquivos_temp->result() as $linha) {
                unlink($linha->arquivo);
                $this->db->where('id', $linha->id)->delete('arquivos_temp');
            }
        }
        echo json_encode(array('retorno' => 1, 'aviso' => 'Login efetuado com sucesso!', 'redireciona' => 1, 'pagina' => site_url('home')));
    }

    public function recuperarsenha_json()
    {
        header('Content-type: text/json');

        $data['email'] = $this->input->post('email');

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));
        }

        $result = $this->db->query("SELECT * FROM usuarios WHERE email = ?", $data);

        if ($result->num_rows() == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Não existe nenhum usuário cadastrado com esse endereço de e-mail')));
        }

        $usuario = $result->row();

        $id = $usuario->id;
        $token = uniqid();

        $this->config->set_item('index_page', $usuario->url);

        if (!$this->db->where('id', $id)->update('usuarios', array('token' => $token))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar token, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        $this->load->library('email');

        $this->email->from('sistema@rhsuite.com.br', 'RhSuite');
        $this->email->to($usuario->email);
        $this->email->subject('LMS - Redefinição de senha');


        $urlAlterarSenha = site_url('home/alterarsenha/' . $token);
        $mensagem = "<p style='text-align: center;'>
                        <h1>LMS</h1>
                    </p>
                    <hr/>
                    <p>Prezado(a) {$usuario->nome},</p>
                    <p>Para alterar sua senha, acesso o endereço abaixo</p>
                    <p><a href='{$urlAlterarSenha}'>{$urlAlterarSenha}</a></p>
                    <p>Caso não tenha solicitado a alteração de senha, ignore este e-mail.</p>";

        $this->email->message($mensagem);

        if ($this->email->send() == false) {
            exit(json_encode(['retorno' => 0, 'aviso' => 'Erro ao enviar e-mail, tente novamente, se o erro persistir entre em contato com o administrador']));
        }

        echo json_encode(['retorno' => 1, 'aviso' => 'Foi enviado um e-mail com endereço de redefinição de senha']);
    }

    public function recuperarsenha_json2()
    {
        header('Content-type: text/json');

        $data['email'] = $this->input->post('email');

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));
        }

        $result = $this->db->query("SELECT * FROM usuarios WHERE email = ?", $data);

        if ($result->num_rows() == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Não existe nenhum usuário cadastrado com esse endereço de e-mail')));
        }

        $usuario = $result->row();

        $id = $usuario->id;
        $nome = $usuario->nome;
        $email = $usuario->email;
        $token = uniqid();

        $this->config->set_item('index_page', $usuario->url);
        $urlAlterarSenha = site_url('home/alterarsenha/' . $token);

        if (!$this->db->where('id', $id)->update('usuarios', array('token' => $token))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar token, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        $this->load->helper('phpmailer');
        $assunto = "LMS - Redefinição de senha";
        $mensagem = "<span style='text-align: center'>
                        <h1>LMS</h1>
                    </span>
                    <hr />
                    <p>Prezado(a) {$nome},</p>
                    <p>Para alterar sua senha, acesso o endereço abaixo</p>
                    <p><a href='{$urlAlterarSenha}'>{$urlAlterarSenha}</a></p>
                    <p>Caso não tenha solicitado a alteração de senha, ignore este e-mail.</p>";
        if (send_email($nome, $email, $assunto, $mensagem)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Foi enviado um e-mail com endereço de redefinição de senha'));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao enviar e-mail, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

}
