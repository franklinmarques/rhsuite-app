<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_modelos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Avaliacaoexp_model', 'avaliacaoexp');
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $data['tipo'] = $this->uri->rsegment(3);
        $data['titulo'] = 'Modelos de avaliação';
        if ($data['tipo'] === '1') {
            $data['titulo'] .= ' periódica';
        } elseif ($data['tipo'] === '2') {
            $data['titulo'] .= ' de período de experiência';
        }
        $this->load->view('avaliacaoexp_modelos', $data);
    }

    public function ajax_list($tipo = '')
    {
        $empresa = $this->session->userdata('empresa');
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.empresa,
                       s.tipo,
                       s.observacao
                FROM (SELECT a.id, 
                             a.nome, 
                             a.id_usuario_EMPRESA AS empresa,
                             (case tipo 
                              when 'A' then 'Avaliação periódica' 
                              when 'P' then 'Período de experiência'
                              else '' end) AS tipo,
                             a.observacao
                      FROM avaliacaoexp_modelos a
                      WHERE a.id_usuario_EMPRESA = {$empresa}) s";
//        if ($tipo) {
//            if ($tipo == '1') {
//                $tipo = 'A';
//            } elseif ($tipo == '2') {
//                $tipo = 'P';
//            }
//            $sql .= " AND a.tipo = '{$tipo}'";
//        }
//        $sql .= ") s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.tipo');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " 
                        WHERE {$column} LIKE '%{$post['search']['value']}%'";
                }
            }
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $avaliacaoExp) {
            $row = array();
            $row[] = $avaliacaoExp->nome;
            $row[] = $avaliacaoExp->tipo;
            $uri = $avaliacaoExp->tipo === 'Período de experiência' ? 'avaliacaoexp_questoes' : 'avaliacaoexp_alternativas';

            $row[] = '
			<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_avaliacao(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_avaliacao(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			<a class="btn btn-sm btn-success" href="' . site_url($uri . '/gerenciar/' . $avaliacaoExp->id) . '" title="Editar questões" ><i class="glyphicon glyphicon-list"></i> Editar questões</a>
			
			';

            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
//        set_status_header(401, utf8_decode('sessão expirada'));
        $data = $this->avaliacaoexp->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'id_usuario_EMPRESA' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $this->input->post('tipo'),
            'observacao' => $this->input->post('observacao')
        );
        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }
        $status = $this->avaliacaoexp->save($data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = array(
            'id_usuario_EMPRESA' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $this->input->post('tipo'),
            'observacao' => $this->input->post('observacao')
        );
        if (empty($data['nome'])) {
            die(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }
        $status = $this->avaliacaoexp->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->avaliacaoexp->delete_by_id($id);
        echo json_encode(array("status" => $status !== false));
    }

    public function get_tipo()
    {
        $id = $this->input->post('id');
        $row = $this->db->get_where('avaliacaoexp_modelos', array('id' => $id))->row();
        $result = '';
        if (count($row) == 1) {
            $result = $row->tipo;
        }
        echo $result;
    }

}
