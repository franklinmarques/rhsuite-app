<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends MY_Controller
{

    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $this->load->view('ead/clientes', $data);
    }


    public function editarPerfil()
    {
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('id', $this->session->userdata('id'));
        $data = $this->db->get('cursos_clientes')->row();
        unset($data->senha);

        echo json_encode($data);
    }


    public function salvarPerfil()
    {
        $this->load->model('ead_clientes_model', 'cliente');
        $this->load->model('usuarios_model', 'usuario');

        if (($msg = $this->cliente->revalidate()) !== true) {
            exit(json_encode(['msg' => $msg]));
        }

        $data = $this->input->post();
        if (strlen($data['senha'])) {
            $data['senha'] = $this->usuario->setPassword($data['senha']);
        } else {
            unset($data['senha']);
        }
        $data['data_edicao'] = date('Y-m-d H:i:s');
        $id = $this->input->post('id');
        unset($data['id'], $data['token'], $data['confirmar_senha']);

        if ($this->cliente->update($data, ['id' => $id]) == false) {
            exit(json_encode(['erro' => 'Erro ao alterar dados']));
        }


        $this->session->set_userdata('nome', $data['nome']);
        $this->session->set_userdata('email', $data['email']);
        if (isset($data['foto'])) {
            $this->session->set_userdata('foto', $data['foto']);
        }


        echo json_encode([
            'status' => 1,
            'aviso' => 'Meu perfil foi editado com sucesso!',
            'pagina' => site_url('ead/treinamento_cliente')
        ]);
    }


    public function ajaxList()
    {
        $clienteSelecionado = $this->input->post('cliente');


        $this->db->select('nome, cliente, id');
        $this->db->select("(CASE STATUS WHEN 1 THEN 'Ativo' WHEN 0 THEN 'Inativo' END) AS status", false);
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        if ($clienteSelecionado) {
            $this->db->where('cliente', $clienteSelecionado);
        }
        $query = $this->db->get('cursos_clientes');


        $config = array(
            'search' => ['nome', 'cliente'],
            'order' => ['nome', 'cliente', 'status']
        );

        $this->load->library('dataTables', $config);
        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->cliente,
                $row->status,
                '<button class="btn btn-sm btn-info" onclick="edit_cliente(' . $row->id . ');" title="Editar cliente"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_cliente(' . $row->id . ');" title="Excluir cliente"><i class="glyphicon glyphicon-trash"></i></button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('ead/clientes_treinamentos/gerenciar/' . $row->id) . '" title="Gerenciar treinamentos do cliente ">Treinamentos</a>',
            );
        }

        $output->data = $data;


        $this->db->distinct('cliente');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('cliente', 'asc');
        $rowsClientes = $this->db->get('cursos_clientes')->result();
        $clientes = ['' => 'Todos'] + array_column($rowsClientes, 'cliente', 'cliente');

        $output->clientes = form_dropdown('busca_cliente', $clientes, $clienteSelecionado, 'class="form-control input-sm" aria-controls="table" onchange="reload_table();"');


        echo json_encode($output);
    }


    public function ajaxEdit()
    {
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('cursos_clientes')->row();
        unset($data->senha);

        echo json_encode($data);
    }


    public function ajaxAdd()
    {
        $this->load->model('ead_clientes_model', 'cliente');
        $this->load->model('usuarios_model', 'usuario');

        if (($msg = $this->cliente->validate()) !== true) {
            exit(json_encode(['msg' => $msg]));
        }

        $data = $this->input->post();
        $data['senha'] = $this->usuario->setPassword($data['senha']);
        $data['data_cadastro'] = date('Y-m-d H:i:s');
        $data['token'] = uniqid();
        unset($data['id'], $data['confirmar_senha']);

        if ($this->cliente->insert($data) == false) {
            exit(json_encode(['erro' => 'Erro ao cadastrar dados']));
        }

        echo json_encode(['status' => true]);
    }


    public function ajaxUpdate()
    {
        $this->load->model('ead_clientes_model', 'cliente');
        $this->load->model('usuarios_model', 'usuario');

        if (($msg = $this->cliente->revalidate()) !== true) {
            exit(json_encode(['msg' => $msg]));
        }

        $data = $this->input->post();
        if (strlen($data['senha'])) {
            $data['senha'] = $this->usuario->setPassword($data['senha']);
        } else {
            unset($data['senha']);
        }
        $data['data_edicao'] = date('Y-m-d H:i:s');
        $id = $this->input->post('id');
        unset($data['id'], $data['token'], $data['confirmar_senha']);

        if ($this->cliente->update($data, ['id' => $id]) == false) {
            exit(json_encode(['erro' => 'Erro ao alterar dados']));
        }

        echo json_encode(['status' => true]);
    }


    public function ajaxDelete()
    {
        $id = $this->input->post('id');

        $this->load->model('ead_clientes_model', 'cliente');
        if ($this->cliente->delete(['id' => $id]) == false) {
            exit(json_encode(['erro' => 'Erro ao excluir dados']));
        }

        echo json_encode(['status' => true]);
    }


}
