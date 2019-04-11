<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_treinamentos extends MY_Controller
{

    public function index()
    {
        $this->gerenciar();
    }


    public function gerenciar()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $data['idCliente'] = $this->uri->rsegment(3, '');
        $this->load->view('ead/clientes_treinamentos', $data);
    }


    public function ajaxList()
    {
        $idCliente = $this->input->post('id_cliente');


        $this->db->select('IFNULL(c.nome, a.nome) AS nome', false);
        $this->db->select('a.data_inicio, a.data_maxima, NULL AS avaliacao_final, a.id', false);
        $this->db->select(["DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio_de"], false);
        $this->db->select(["DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima_de"], false);
        $this->db->join('cursos_clientes b', 'b.id = a.id_usuario');
        $this->db->join('cursos c', 'c.id = a.id_curso', 'left');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        if ($idCliente) {
            $this->db->where('a.id_usuario', $idCliente);
        }
        $query = $this->db->get('cursos_clientes_treinamentos a');


        $this->load->library('dataTables');
        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->data_inicio_de,
                $row->data_maxima_de,
                $row->avaliacao_final,
                '<button class="btn btn-sm btn-info" onclick="edit_treinamento(' . $row->id . ');" title="Editar cliente"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_treinamento(' . $row->id . ');" title="Excluir cliente"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function ajaxEdit()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->input->post('id_usuario');
        $id = $this->input->post('id');

        $this->db->select('a.id, a.id_curso, a.nota_aprovacao, a.tipo_treinamento, a.local_treinamento');
        $this->db->select('a.nome_fornecedor, a.avaliacao_presencial');
        $this->db->select("IF(b.id IS NOT NULL, b.nome, a.nome) AS nome", false);
        $this->db->select("DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio", false);
        $this->db->select("DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima", false);
        $this->db->select("TIME_FORMAT(a.carga_horaria_presencial, '%H:%i') AS carga_horaria_presencial", false);
        $this->db->join('cursos_clientes c', 'c.id = a.id_usuario');
        $this->db->join('cursos b', 'b.id = a.id_curso', 'left');
        $this->db->where('a.id', $id);
        $this->db->where('c.id_empresa', $empresa);
        $this->db->group_by('a.id');
        $curso_usuario = $this->db->get('cursos_clientes_treinamentos a')->row();

        $id_curso = $curso_usuario->id_curso ?? '';
        $data['id'] = $curso_usuario->id ?? null;
        $data['nome'] = $curso_usuario->nome ?? null;
        $data['tipo_treinamento'] = $curso_usuario->tipo_treinamento ?? null;
        $data['local_treinamento'] = $curso_usuario->local_treinamento ?? null;
        $data['data_inicio'] = $curso_usuario->data_inicio ?? null;
        $data['data_maxima'] = $curso_usuario->data_maxima ?? null;
        $data['nota_aprovacao'] = $curso_usuario->nota_aprovacao ?? null;
        $data['carga_horaria_presencial'] = $curso_usuario->carga_horaria_presencial ?? null;
        $data['avaliacao_presencial'] = $curso_usuario->avaliacao_presencial ?? null;
        $data['nome_fornecedor'] = $curso_usuario->nome_fornecedor ?? null;

        $this->db->select('a.id, a.nome');
        $this->db->join('cursos_clientes_treinamentos b', "b.id_curso = a.id AND b.id_usuario = {$id_usuario}", 'left');
        $this->db->where('a.id_empresa =', $empresa);
        if ($id) {
            $this->db->where("(b.id IS NULL OR b.id_curso = {$id})");
        } else {
            $this->db->where('b.id', null);
        }
        if ($id_curso) {
            $this->db->or_where('a.id', $id_curso);
            $data['nome'] = '';
        } else {
            $data['nome'] = $curso_usuario->nome ?? null;
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'ASC');
        $rows = $this->db->get('cursos a')->result();

        $options = array('' => 'selecione...');
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $data['cursos'] = form_dropdown('id_curso', $options, $id_curso, 'class="form-control"');
        echo json_encode($data);
    }


    public function ajaxAdd()
    {
        $this->load->model('ead_clientes_treinamentos_model', 'treinamento');

        if (($msg = $this->treinamento->validate()) !== true) {
            exit(json_encode(['msg' => $msg]));
        }

        $data = $this->setData($this->input->post());
        $data['data_cadastro'] = date('Y-m-d H:i:s');


        if ($this->treinamento->insert($data) == false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar dados']));
        }

        echo json_encode(['status' => true]);
    }


    public function ajaxUpdate()
    {
        $this->load->model('ead_clientes_treinamentos_model', 'treinamento');

        if (($msg = $this->treinamento->revalidate()) !== true) {
            exit(json_encode(['msg' => $msg]));
        }

        $data = $this->setData($this->input->post());
        $id = $this->input->post('id');

        if ($this->treinamento->update($data, ['id' => $id]) == false) {
            exit(json_encode(['erro' => 'Erro ao alterar dados']));
        }

        echo json_encode(['status' => true]);
    }


    public function ajaxDelete()
    {
        $id = $this->input->post('id');

        $this->load->model('ead_clientes_treinamentos_model', 'treinamento');
        if ($this->treinamento->delete(['id' => $id]) == false) {
            exit(json_encode(['erro' => 'Erro ao excluir dados']));
        }

        echo json_encode(['status' => true]);
    }


    /*public function enviarEmail()
    {
        $id_usuario = $this->input->post('id');
        $mensagem = $this->input->post('mensagem');

        $this->load->helper(array('date'));

        $email['titulo'] = 'E-mail de convocação para Treinamento';
        $email['remetente'] = $this->session->userdata('id');
        $email['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        $status = true;

        $this->db->select('a.id_usuario, b.nome, b.email');
        $this->db->select("DATE_FORMAT(MIN(a.data_inicio), '%d/%m/%Y') AS data_inicio", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        if ($id_usuario) {
            $this->db->where('a.id_usuario', $id_usuario);
        }
        $this->db->where('a.data_maxima <= NOW()');
        $this->db->group_by('a.id_usuario');
        $destinatarios = $this->db->get('cursos_clientes a')->result();

        $this->db->select("a.nome, a.email, IFNULL(b.email, a.email) AS email_empresa", false);
        $this->db->join('usuarios b', 'b.id = a.empresa', 'left');
        $this->db->where('a.id', $this->session->userdata('id'));
        $remetente = $this->db->get('usuarios a')->row();

        $this->load->library('email');

        foreach ($destinatarios as $destinatario) {
            if ($mensagem) {
                $email['mensagem'] = $mensagem;
            } else {
                $email['mensagem'] = "Caro colaborador, você está convocado para realizar treinamento na data de: {$destinatario->data_programada}. Favor verificar com o Departamento de Gestão de Pessoas";
            }

            $this->email->from($remetente->email, $remetente->nome);
            $this->email->to($destinatario->email);
            $this->email->cc($remetente->email_empresa);
            $this->email->bcc('contato@rhsuite.com.br');

            $this->email->subject($email['titulo']);
            $this->email->message($email['mensagem']);

            if ($this->email->send()) {
                $email['destinatario'] = $destinatario->id_usuario;
                $this->db->query($this->db->insert_string('mensagensrecebidas', $email));
                $this->db->query($this->db->insert_string('mensagensenviadas', $email));
            } else {
                $status = false;
            }

            $this->email->clear();
        }

        echo json_encode(array('status' => $status));
    }*/


    private function setData($data = [])
    {
        if ($data['data_inicio']) {
            $data['data_inicio'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_maxima']) {
            $data['data_maxima'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['data_maxima'])));
        }
        unset($data['id']);

        return $data;
    }

}
