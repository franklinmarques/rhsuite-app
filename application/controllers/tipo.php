<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tipo extends MY_Controller
{

    public function novo()
    {
        $this->load->helper('form');
        $this->load->view('novotipo');
    }

    public function editar($id = 0)
    {
        $this->load->helper('form');
        $dados['tipos'] = $this->db->query('SELECT id, descricao FROM tipodocumento ORDER BY descricao ASC')->result();
        $dados['row'] = $this->db->query('SELECT * FROM tipodocumento WHERE id = ?', (int) $id)->row(0);
        $dados['id'] = $id;
        if ((int) $id > 0) {
            $this->load->view('editartipo', $dados);
        } else {
            redirect('tipo/gerenciar');
        }
    }

    public function novo_db()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $data['datacadastro'] = date('Y-m-d H:i:s');
        $data['descricao'] = $_POST['descricao'];
        $data['categoria'] = (int) $_POST['categoria'];
        $data['usuario'] = $this->session->userdata('id');

        # Validação
        if (empty($data['descricao']) || empty($data['categoria'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo Descrição não pode ficar em branco')));
        }

        if ($this->db->query($this->db->insert_string('tipodocumento', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de tipo efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('tipo/gerenciar')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de tipo, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editar_db()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $data['descricao'] = $_POST['descricao'];
        $data['categoria'] = (int) $_POST['categoria'];
        $id = $this->uri->rsegment(3, 0);

        # Validação
        if (empty($data['descricao']) || empty($data['categoria'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo Descrição não pode ficar em branco')));
        }

        $resultado = $this->db->query('SELECT id FROM tipodocumento WHERE id = ?', $id);

        if ($resultado->num_rows() < 1) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'ID não localizado, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        # /Validação

        if ($this->db->where('id', $id)->update('tipodocumento', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de tipo efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('tipo/gerenciar')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de tipo, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function excluir()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $id = $this->uri->rsegment(3, 0);

        $resultado = $this->db->query('SELECT id FROM tipodocumento WHERE id = ?', $id);

        if ($resultado->num_rows() < 1) {
            $this->db->query("DELETE FROM tipodocumento WHERE id = ?", $id);
            echo json_encode(array('retorno' => 1, 'aviso' => 'Exclusão de tipo efetuada com sucesso', 'redireciona' => 1, 'pagina' => site_url('tipo/gerenciar')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar exclusão de tipo, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function gerenciar()
    {
        $this->load->helper('form');
        $this->load->view('tipo');
    }

    public function getTipo()
    {
        $this->load->library('pagination');
        $this->load->helper(array('date'));

        //Consulta no banco
        $data['query'] = $this->db->query("SELECT * FROM tipodocumento ORDER BY categoria, descricao ASC");
        $data['total'] = $data['query']->num_rows();
        $data['busca'] = '';

        $this->load->helper('form');
        $this->load->view('gettipo', $data);
    }

}
