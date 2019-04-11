<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends MY_Controller
{

    public function verAgenda()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $this->db->select("id, date_to AS date, 'meeting' AS type, title, description, link AS url, status, color", false);
        $this->db->where('usuario', $this->session->userdata('id'));
        $this->db->or_where('usuario_referenciado', $this->session->userdata('id'));
        $eventos = $this->db->get('eventos')->result();

        $html = null;
        if (count($eventos) > 0) {
            $html = json_encode($eventos);
        }

        echo $html;
    }

    public function inserir()
    {
        header('Content-type: text/json');
        $data = $this->input->post();

        # Validação
        if (empty($data['title']) || empty($data['description']) || empty($data['date_to'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Todos os campos (exceto link) não podem ficar em branco')));
        }

        $this->load->helper(array('date'));
        $data['date_from'] = date('Y-m-d H:i:s');
        $data['date_to'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['date_to'])));
        $data['usuario'] = $this->session->userdata('id');
        if (!($data['usuario_referenciado'] > 0)) {
            $data['usuario_referenciado'] = $data['usuario'];
        }

        if ($this->db->query($this->db->insert_string('eventos', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de evento efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de evento, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function atualizarFiltro()
    {
        $empresa = $this->session->userdata('empresa');
        $get = $this->input->get();


        $this->db->select('DISTINCT(area) AS nome', false);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        if ($this->session->userdata('tipo') != "administrador") {
            $this->db->where('empresa', $empresa);
        }
        if ($get['depto']) {
            $this->db->where('depto', $get['depto']);
        }
        $this->db->order_by('area');
        $areas = $this->db->get('usuarios')->result();

        $options['area'] = array('' => 'selecione...');
        foreach ($areas as $area) {
            $options['area'][$area->nome] = $area->nome;
        }


//        $this->db->select("id, CONCAT(nome, ' &ensp; &ll;', email, '&gg;') AS nome", false);
        $this->db->select('id, nome');
        if ($this->session->userdata('tipo') != "administrador") {
            $this->db->where('empresa', $empresa);
        }
        if ($get['depto']) {
            $this->db->where('depto', $get['depto']);
        }
        if ($get['area']) {
            $this->db->where('area', $get['area']);
        }
        $this->db->order_by('nome');
        $usuarios = $this->db->get('usuarios')->result();

        $options['usuario'] = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $options['usuario'][$usuario->id] = $usuario->nome;
        }


        $data['area'] = form_dropdown('area', $options['area'], $get['area'], 'id="area" class="form-control filtro input-sm"');
        $data['usuario'] = form_dropdown('usuario_referenciado', $options['usuario'], $get['usuario'], 'id="usuario" class="form-control input-sm"');

        echo json_encode($data);
    }

    public function finalizar()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));
        $get = $this->input->post();

        if (empty($get['id'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Evento não localizado, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        $id_evento = (int)$get['id'];
        $complemento_query = null;
        $array_where = $id_evento;

        // Verifica se é administrador
        if ($this->session->userdata('tipo') != 'administrador') {
            $complemento_query = "AND usuario_referenciado = ? OR usuario = ? ";
            $array_where = array($id_evento, $this->session->userdata('id'), $this->session->userdata('id'));
        }

        $eventos = $this->db->query("SELECT * FROM eventos WHERE id = ? AND status = 0 $complemento_query", $array_where);
        $data['status'] = 1;

        # Validação
        if ($eventos->num_rows() > 0) {
            if ($this->db->where('id', $id_evento)->update('eventos', $data)) {
                echo json_encode(array('retorno' => 1, 'aviso' => 'Evento finalizado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home')));
            } else {
                echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar finalização do evento, tente novamente, se o erro persistir entre em contato com o administrador'));
            }
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Evento não localizado ou sem permissão para alteração, tente novamente, se o erro persistir entre em contato com o administrador')));
        }
    }

    public function excluir()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));
        $get = $this->input->post();

        if (empty($get['id'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Evento não localizado, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        $id_evento = (int)$get['id'];
        $complemento_query = null;
        $array_where = $id_evento;

        // Verifica se é administrador
        if ($this->session->userdata('tipo') != 'administrador') {
            $complemento_query = "AND usuario_referenciado = ? OR usuario = ?";
            $array_where = array($id_evento, $this->session->userdata('id'), $this->session->userdata('id'));
        }

        $eventos = $this->db->query("SELECT * FROM eventos WHERE id = ? $complemento_query", $array_where);
        $data['status'] = 1;

        # Validação
        if ($eventos->num_rows() > 0) {
            if ($this->db->where('id', $id_evento)->delete('eventos')) {
                echo json_encode(array('retorno' => 1, 'aviso' => 'Evento excluído com sucesso', 'redireciona' => 1, 'pagina' => site_url('home')));
            } else {
                echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar exclusão do evento, tente novamente, se o erro persistir entre em contato com o administrador'));
            }
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Evento não localizado ou sem permissão para alteração, tente novamente, se o erro persistir entre em contato com o administrador')));
        }
    }

}
