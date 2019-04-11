<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends MY_Controller
{

    public function entrada()
    {
        $this->load->view('email');
    }

    public function saida()
    {
        $this->load->view('email_saida');
    }

    public function getEmail()
    {
        $this->load->library('pagination');
        $this->load->helper(array('date'));

        $query = $this->input->post('busca');
        $qryWHERE = null;

        if (!empty($query)) {
            $qryWHERE = "AND (m.titulo LIKE '%$query%' OR m.mensagem LIKE '%$query%' OR u.nome LIKE '%$query%')";
        }

        $data['busca'] = $this->db->query("SELECT m.*, u.nome AS remetente_mensagem 
                                           FROM mensagensrecebidas m
                                           INNER JOIN usuarios u ON u.id = m.remetente
                                           WHERE m.destinatario = ? $qryWHERE 
                                           ORDER BY m.datacadastro DESC", $this->session->userdata('id'));
        $data['total_rows'] = $data['busca']->num_rows();

        $this->load->view('getemail', $data);
    }

    public function getEmailSaida()
    {
        $this->load->library('pagination');
        $this->load->helper(array('date'));

        $query = $this->input->post('busca');
        $qryWHERE = null;

        if (!empty($query)) {
            $qryWHERE = "AND (m.titulo LIKE '%$query%' OR m.mensagem LIKE '%$query%' OR u.nome LIKE '%$query%')";
        }

        $data['busca'] = $this->db->query("SELECT m.*, u.nome AS remetente_mensagem
                                           FROM mensagensenviadas m
                                           INNER JOIN usuarios u ON u.id = m.destinatario
                                           WHERE m.remetente = ? $qryWHERE 
                                           ORDER BY m.datacadastro DESC", $this->session->userdata('id'));

        # Mensagens não lidas da caixa de entrada
        $resultado = $this->db->query("SELECT COUNT(*) AS naolidas 
                                       FROM mensagensrecebidas mr
                                       WHERE mr.status = 0 AND mr.destinatario = ?", $this->session->userdata('id'));

        $data['busca']->naolidas = $resultado->result();

        $data['total_rows'] = $data['busca']->num_rows();

        $this->load->view('getemailsaida', $data);
    }

    public function novo($destinatario = 0)
    {
        $this->load->library('pagination');
        $this->load->helper(array('date'));

        //Id do usuário para localizar a empresa e os contatos
        $id_usuario = $this->session->userdata('id');

        if ($this->session->userdata('empresa') > 0) {
            $id_usuario = $this->session->userdata('empresa');
        }

        //Variáveis para montagem do select
        $gestor = null;
        $funcionarios = null;
        $administrador = null;
        $option = null;

        if ($this->session->userdata('tipo') == 'administrador') {
            $busca = $this->db->query("SELECT u.*
                                       FROM usuarios u
                                       WHERE u.empresa = 0", array($id_usuario, $id_usuario));
        } elseif ($this->session->userdata('tipo') == 'empresa') {
            //Consulta no banco
            $busca = $this->db->query("SELECT u.*
                                       FROM usuarios u
                                       WHERE u.id = ? OR u.empresa = ? OR u.tipo = ?", array($id_usuario, $id_usuario, 'administrador'));
        } else {
            //Consulta no banco
            $busca = $this->db->query("SELECT u.*
                                       FROM usuarios u
                                       WHERE u.id = ? OR u.empresa = ?", array($id_usuario, $id_usuario));
        }

        foreach ($busca->result() as $row) {
            if ((int) $destinatario == $row->id) {
                $ckecked = 'selected';
            } else {
                $ckecked = null;
            }

            if ($row->tipo == 'administrador') {
                $administrador .= "<option $ckecked value='$row->id'>$row->nome</option>";
            } elseif ($row->tipo == 'empresa') {
                $gestor .= "<option $ckecked value='$row->id'>$row->nome</option>";
            } else {
                $funcionarios .= "<option $ckecked value='$row->id'>$row->nome</option>";
            }
        }

        // Montar select
        if (!empty($administrador)) {
            $option .= "<optgroup label='Gestor da Plataforma'>
                            $administrador
                        </optgroup>
                       ";
        }
        if (!empty($gestor)) {
            $option .= "<optgroup label='Gestor de RH'>
                            $gestor
                        </optgroup>
                       ";
        }
        $option .= "<optgroup label='Colaborador(es)'>
                        $funcionarios
                    </optgroup>
                    ";

        $data['option'] = $option;

        $this->load->helper('form');
        $this->load->view('email_novo', $data);
    }

    public function enviar()
    {
        $this->load->helper(array('date'));

        $data['remetente'] = $this->session->userdata('id');
        $data['destinatario'] = $this->input->post('destinatario');
        $data['titulo'] = $this->input->post('titulo');
        $data['mensagem'] = $this->input->post('mensagem');
        $data['status'] = '0';
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        if (!empty($_FILES['anexo'])) {
            $config['upload_path'] = './imagens/emails/';
            $config['allowed_types'] = 'gif|jpg|png|txt|doc|xls|ppt|jpg|bmp|xlsx|zip';
            $config['max_size'] = '2048';

            $this->load->library('upload', $config);
            $_FILES['anexo']['name'] = $this->removeCaracterEspecial($_FILES['anexo']['name']);

            if ($this->upload->do_upload('anexo')) {
                $foto = $this->upload->data();
                $data['anexo'] = $foto['file_name'];
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if (!empty($data['mensagem']) && !empty($data['remetente'])) {
            //Inserir em mensagens recebidas
            $this->db->query($this->db->insert_string('mensagensrecebidas', $data));
            //Inserir em mensagens enviadas
            $this->db->query($this->db->insert_string('mensagensenviadas', $data));

            echo json_encode(array('retorno' => 1, 'aviso' => 'Mensagem enviada com sucesso', 'redireciona' => 1, 'pagina' => site_url('email/saida')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar o envio da mensagem, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function excluirsaida()
    {
        $this->load->helper(array('date'));

        $data['id_email'] = $_GET['id'];

        if ($data['id_email'] > 0) {
            $id = explode(",", $data['id_email']);
            //Deletar linhas
            foreach ($id as $id_tabela) {
                $this->db->query("DELETE FROM mensagensenviadas WHERE id = ? AND remetente = ?", array($id_tabela, $this->session->userdata('id')));
            }
            echo 'Mensagem(ns) excluída(s) com sucesso';
        } else {
            echo 'Erro na identificação da(s) mensagem(ns), tente novamente';
        }
    }

    public function excluirentrada()
    {
        $this->load->helper(array('date'));

        $data['id_email'] = $_GET['id'];

        if ($data['id_email'] > 0) {
            $id = explode(",", $data['id_email']);
            //Deletar linhas
            foreach ($id as $id_tabela) {
                $this->db->query("DELETE FROM mensagensrecebidas WHERE id = ? AND destinatario = ?", array($id_tabela, $this->session->userdata('id')));
            }
            echo 'Mensagem(ns) excluída(s) com sucesso';
        } else {
            echo 'Erro na identificação da(s) mensagem(ns), tente novamente';
        }
    }

    public function lerMensagem($id = 0)
    {
        $this->load->helper(array('date'));

        $data['id_email'] = $id;

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $data['id_email'] = $_GET['id'];
        }

        if ($data['id_email'] > 0) {
            $id = explode(",", $data['id_email']);
            //Deletar linhas
            foreach ($id as $id_tabela) {
                $this->db->query("UPDATE mensagensrecebidas SET status = 1 WHERE id = ? AND destinatario = ?", array($id_tabela, $this->session->userdata('id')));
            }
            if ($id == 0) {
                echo 'Mensagem(ns) alterada(s) com sucesso';
            }
        } else {
            echo 'Erro na identificação da(s) mensagem(ns), tente novamente';
        }
    }

    public function naoLerMensagem($id = 0)
    {
        $this->load->helper(array('date'));

        $data['id_email'] = $id;

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $data['id_email'] = $_GET['id'];
        }

        if ($data['id_email'] > 0) {
            $id = explode(",", $data['id_email']);
            //Deletar linhas
            foreach ($id as $id_tabela) {
                $this->db->query("UPDATE mensagensrecebidas SET status = 0 WHERE id = ? AND destinatario = ?", array($id_tabela, $this->session->userdata('id')));
            }
            if ($id == 0) {
                echo 'Mensagem(ns) alterada(s) com sucesso';
            }
        } else {
            echo 'Erro na identificação da(s) mensagem(ns), tente novamente';
        }
    }

    public function visualizarsaida($id = 0)
    {
        $this->load->library('pagination');
        $this->load->helper(array('date'));

        //Consulta no banco
        $data['busca'] = $this->db->query("SELECT m.*, u.nome AS destinatario_mensagem, u.foto
                                           FROM mensagensenviadas m
                                           INNER JOIN usuarios u ON u.id = m.destinatario
                                           WHERE m.id = ? AND m.remetente = ? LIMIT 1", array($id, $this->session->userdata('id')));

        if ($data['busca']->num_rows() > 0) {
            $this->load->helper('form');
            $this->load->view('email_visualizar', $data);
        } else {
            redirect(site_url('email/saida'));
        }
    }

    public function visualizarentrada($id = 0)
    {
        $this->load->library('pagination');
        $this->load->helper(array('date'));

        //Consulta no banco
        $data['busca'] = $this->db->query("SELECT m.*, u.nome AS destinatario_mensagem, u.foto
                                           FROM mensagensrecebidas m
                                           INNER JOIN usuarios u ON u.id = m.remetente
                                           WHERE m.id = ? AND m.destinatario = ? LIMIT 1", array($id, $this->session->userdata('id')));

        if ($data['busca']->num_rows() > 0) {
            $this->lerMensagem($id);

            $this->load->helper('form');
            $this->load->view('email_visualizar', $data);
        } else {
            redirect(site_url('email/entrada'));
        }
    }

    public function download($name)
    {
        $path = "./imagens/emails/";
        // make sure it's a file before doing anything!
        if (is_file($path . $name)) {
            $this->load->helper('download');
            $data = file_get_contents($path . $name); // Read the file's contents
            force_download($name, $data);
        }
    }

    public function removeCaracterEspecial($texto)
    {
        //desconvertendo do padrão entitie (tipo á para á)
        $texto = trim(html_entity_decode($texto));
        //tirando os acentos
        $texto = preg_replace('![áàãâä]+!u', 'a', $texto);
        $texto = preg_replace('![éèêë]+!u', 'e', $texto);
        $texto = preg_replace('![íìîï]+!u', 'i', $texto);
        $texto = preg_replace('![óòõôö]+!u', 'o', $texto);
        $texto = preg_replace('![úùûü]+!u', 'u', $texto);
        //parte que tira o cedilha e o ñ
        $texto = preg_replace('![ç]+!u', 'c', $texto);
        $texto = preg_replace('![ñ]+!u', 'n', $texto);
        //tirando outros caracteres invalidos
        $texto = preg_replace('[^a-z0-9\-]', '-', $texto);
        //tirando espaços
        $texto = str_replace(' ', '-', $texto);
        //trocando duplo espaço (hifen) por 1 hifen só
        $texto = str_replace('--', '-', $texto);
        $texto = str_replace('{', '', $texto);
        $texto = str_replace('}', '', $texto);
        $texto = str_replace('[', '', $texto);
        $texto = str_replace(']', '', $texto);
        $texto = str_replace('/', '', $texto);

        return strtolower($texto);
    }

    public function getNovasMensagens()
    {
        $html = null;

        $mensagens = $this->db->query("SELECT m.*, 
                                              u.nome AS remetente_mensagem 
                                       FROM mensagensrecebidas m
                                       INNER JOIN usuarios u ON u.id = m.remetente
                                       WHERE m.destinatario = ? AND 
                                             m.status = 0 
                                       ORDER BY m.datacadastro DESC", $this->session->userdata('id'));

        if ($mensagens->num_rows() > 0) {

            if ($mensagens->num_rows() > 1) {
                $html .= "<li><p class=\"red\">Você possui {$mensagens->num_rows()} mensagens novas</p></li>";
            } else {
                $html .= "<li><p class=\"red\">Você possui {$mensagens->num_rows()} mensagem novas</p></li>";
            }

            foreach ($mensagens->result() as $row) {
                $imagem = null;
                $url_entrada = site_url('email/visualizarentrada') . "/$row->id/";

                if ($row->foto)
                    $imagem = base_url('imagens/usuarios/' . $row->foto);
                $html .= "<li><a href=\"$url_entrada\"><span class=\"photo\"><img alt=\"$imagem\" src=\"\"></span><span class=\"subject\"><span class=\"from\">$row->destinatario_mensagem</span></span><span class=\"message\">$row->titulo</span></a></li>";
            }
        } else {
            $html .= "<li><p class=\"red\">Você não possui nenhuma mensagem nova</p></li>";
        }

        $url = site_url('email/entrada');

        $html .= "<li><a href=\"$url\" style=\"text-align: center;\">Visualizar todas as mensagens</a></li>";

        $html = "<script>$('#total_msg').html('{$mensagens->num_rows()}'); $('#inbox').html('$html');</script>";

        echo($html);
    }

}
