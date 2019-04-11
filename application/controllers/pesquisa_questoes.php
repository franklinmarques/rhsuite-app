<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_questoes extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Avaliacaoexp_model', 'avaliacaoexp');
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar($id = null)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $pesquisa = $this->db->get_where('pesquisa_modelos', array('id' => $id))->row();
        if ($pesquisa->exclusao_bloqueada) {
            redirect(site_url('pesquisa_modelos'));
            exit;
        }
        $data['id_modelo'] = $pesquisa->id;
        $data['modelo'] = $pesquisa->nome;
        switch ($pesquisa->tipo) {
            case 'C':
                $data['tipo'] = 'de clima';
                break;
            case 'E':
                $data['tipo'] = 'de personalidade';
                break;
            case 'P':
                $data['tipo'] = 'de perfil';
                break;
            default:
                $data['tipo'] = 'de clima';
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $sql = "SELECT a.id, a.categoria 
                FROM pesquisa_categorias a 
                INNER JOIN pesquisa_modelos b ON 
                           b.id = a.id_modelo AND
                           b.tipo = 'C'
                WHERE b.id_usuario_EMPRESA = {$data['empresa']}";
        $categorias = $this->db->query($sql)->result();
        $data['categorias'] = array('' => 'selecione...');
        foreach ($categorias as $categoria) {
            $data['categorias'][$categoria->id] = $categoria->categoria;
        }

        if ($pesquisa->tipo == 'E') {
            $this->load->view('pesquisa_personalidade', $data);
        } else {
            $this->load->view('pesquisa_questoes', $data);
        }
    }


    public function ajax_list($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.categoria,
                       s.pergunta,
                       s.id_categoria
                FROM (SELECT b.id AS id_categoria,
                             b.categoria, 
                             c.id,
                             c.pergunta
                      FROM pesquisa_modelos a
                      LEFT JOIN pesquisa_categorias b ON
                                b.id_modelo = a.id
                      LEFT JOIN pesquisa_perguntas c ON
                                c.id_modelo = a.id AND 
                                b.id = c.id_categoria
                      WHERE a.id = {$id} 
                      ORDER BY a.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();
        $columns = array(
            's.id',
            's.categoria',
            's.pergunta'
        );
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
        foreach ($list as $pesquisa) {
            $row = array();
            $row[] = $pesquisa->categoria;
            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar categoria" onclick="edit_categoria(' . "'" . $pesquisa->id_categoria . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir categoria" onclick="delete_categoria(' . "'" . $pesquisa->id_categoria . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                     ';
            $row[] = $pesquisa->pergunta;
            if ($pesquisa->pergunta !== null) {
                $row[] = '
                          <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar pergunta" onclick="edit_pergunta(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                          <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir pergunta" onclick="delete_pergunta(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                         ';
            } else {
                $row[] = '';
            }
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

    public function ajax_listCategoria()
    {
        $sql = "SELECT a.id, a.categoria 
                FROM pesquisa_categorias a 
                INNER JOIN pesquisa_modelos b ON 
                           b.id = a.id_modelo AND
                           b.tipo = 'C'
                WHERE b.id_usuario_EMPRESA = {$this->session->userdata('empresa')}";
        $categorias = $this->db->query($sql)->result();
        $option = array('' => 'selecione...');
        foreach ($categorias as $categoria) {
            $option[$categoria->id] = $categoria->categoria;
        }

        echo form_dropdown('categoria', $option, '', 'class="form-control"');
    }

    public function ajax_listResposta($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }

        $post = $this->input->post();

        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM pesquisa_alternativas a
                INNER JOIN pesquisa_modelos b ON
                           b.id = a.id_modelo
                LEFT JOIN pesquisa_perguntas c ON 
                          c.id = a.id_pergunta
                WHERE a.id_modelo = {$id}
                GROUP by a.id";

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        $sql .= " 
                LIMIT 0, 6";

        $list = $this->db->query($sql)->result();

        $recordsTotal = count($list);

        $data = array();

        foreach ($list as $pesquisa) {
            $data[] = array($pesquisa->alternativa, $pesquisa->peso);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $data,
        );

        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $this->db->where(array('id' => $id));
        $data = $this->db->get('pesquisa_perguntas')->row();

        echo json_encode($data);
    }

    public function ajax_editResposta($id)
    {
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM pesquisa_alternativas a
                INNER JOIN pesquisa_modelos b ON
                           b.id = a.id_modelo
                WHERE b.id = {$id}
                LIMIT 0, 6";

        $data = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_categoria($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get('pesquisa_categorias')->row();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'id_categoria' => $this->input->post('categoria'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'U'
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'O modelo de pesquisa não foi encontrado')));
        }
        if (strlen($data['pergunta']) == 0) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'A pergunta é obrigatória')));
        }

        $status = $this->db->insert('pesquisa_perguntas', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addCategoria()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'categoria' => $this->input->post('categoria')
        );

        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'O modelo de pesquisa não foi encontrado')));
        }
        if (strlen($data['categoria']) == 0) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'O nome da categoria é obrigatório')));
        }

        $status = $this->db->insert('pesquisa_categorias', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'id_categoria' => $this->input->post('categoria'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'U'
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'O modelo de pesquisa não foi encontrado')));
        }
        if (strlen($data['pergunta']) == 0) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'A pergunta é obrigatória')));
        }
        $status = $this->db->update('pesquisa_perguntas', $data, array('id' => $this->input->post('id')));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateCategoria()
    {
        $post = $this->input->post();
        if (empty($post['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'O modelo de pesquisa não foi encontrado')));
        }
        if (strlen($post['categoria']) == 0) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'O nome da categoria é obrigatório')));
        }

        $data = array('categoria' => $post['categoria']);
        $where = array('id' => $post['id'], 'id_modelo' => $post['id_modelo']);

        $status = $this->db->update('pesquisa_categorias', $data, $where);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateResposta()
    {
        $id_modelo = $this->input->post('id_modelo');

        if (empty($id_modelo)) {
            exit(json_encode(array('retorno' => 0, 'erro' => 'O modelo de avaliação não foi encontrado')));
        }

        $id_alternativas = $this->input->post('id_alternativa');

        $alternativas = $this->input->post('alternativa');

        $peso = $this->input->post('peso');

        $this->db->trans_begin();

        foreach ($alternativas as $k => $alternativa) {

            $data = array(
                'id_modelo' => $id_modelo,
                'alternativa' => $alternativa,
                'peso' => $peso[$k]
            );

            if ($alternativa) {

                if ($id_alternativas[$k]) {

                    $where = array(
                        'id' => $id_alternativas[$k],
                        'id_modelo' => $id_modelo
                    );

                    $this->db->update('pesquisa_alternativas', $data, $where);
                } else {

                    $this->db->insert('pesquisa_alternativas', $data);
                }
            } elseif ($id_alternativas[$k]) {

                $this->db->delete('pesquisa_alternativas', array('id' => $id_alternativas[$k]));
            }
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('pesquisa_perguntas', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_deleteCategoria($id)
    {
        $this->db->trans_begin();

        $this->db->delete('pesquisa_perguntas', array('id_categoria' => $id));
        $this->db->delete('pesquisa_categorias', array('id' => $id));

        $this->db->trans_complete();

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

}
