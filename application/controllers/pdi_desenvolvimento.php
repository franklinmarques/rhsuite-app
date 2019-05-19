<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//require_once APPPATH . 'controllers/Funcionarios.php';
//class PDI_desenvolvimento extends Funcionarios
class PDI_desenvolvimento extends MY_Controller
{

    public function index()
    {
        $data['id_usuario'] = $this->uri->rsegment(3);
        $data['id_pdi'] = $this->uri->rsegment(4, 0);
        $this->load->view('pdi_desenvolvimento', $data);
//        parent::index();
    }

    public function gerenciar()
    {
        $row = $this->db->get_where('usuarios', array('id' => $this->uri->rsegment(3)))->row();
        $data['id_usuario'] = $row->id;
        $data['nome_usuario'] = $row->nome;
        $data['funcao_usuario'] = $row->funcao;
        $data['id_pdi'] = $this->uri->rsegment(4, 0);
        $this->load->view('pdi_desenvolvimento', $data);
    }

    public function ajax_list()
    {
        $this->load->library('pagination');

        $query = $this->input->post('busca');

        $qryWHERE = 'WHERE id_pdi = ? ';
        $dataWHERE[] = $this->uri->rsegment(3);

        if (!empty($query)) {
            $qryWHERE .= 'AND (id_pdi LIKE ? OR data_inicio LIKE ? OR data_fim LIKE ?)';
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = site_url('pdi_desenvolvimento/ajax_list');
        $config['total_rows'] = $this->db->query("SELECT * FROM pdi_desenvolvimento {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dataWHERE[] = $this->uri->rsegment(4, 0);
        $dataWHERE[] = $config['per_page'];

        $data['total'] = $this->db->get('pdi')->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT id, 
                                                  competencia, 
                                                  descricao, 
                                                  expectativa, 
                                                  resultado, 
                                                  DATE_FORMAT(data_inicio,'%d/%m/%Y') AS data_inicio, 
                                                  DATE_FORMAT(data_termino,'%d/%m/%Y') AS data_termino, 
                                                  status 
                                           FROM pdi_desenvolvimento 
                                           {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getpdi_desenvolvimento', $data);


//        $id_empresa = $this->uri->rsegment(4) ? $this->uri->rsegment(4) : $this->session->userdata('id');
//        $post = $this->input->post();
//
//        $sql = "SELECT a.id, 
//                       a.avaliado, 
//                       b.nome, 
//                       d.nome AS cargo 
//                FROM avaliador_avaliados a 
//                INNER JOIN usuarios b ON 
//                     	   a.avaliado = b.id
//                INNER JOIN funcionarios_cargos c ON 
//                           c.id_usuario = b.id 
//                INNER JOIN cargos d ON 
//                           d.id = c.id_cargo 
//                WHERE a.id_avaliacao = {$id_avaliacao}";
//
//        $columns = array('a.avaliado', 'b.nome', 'd.nome');
//
//        if ($post['search']['value']) {
//            foreach ($columns as $key => $column) {
//                if ($key > 1) {
//                    $sql .= " OR
//                         {$column} LIKE '%{$post['search']['value']}%'";
//                } elseif ($key == 1) {
//                    $sql .= " AND 
//                        ({$column} LIKE '%{$post['search']['value']}%'";
//                }
//            }
//            $sql .= ')';
//        }
//        if (isset($post['order'])) {
//            $orderBy = array();
//            foreach ($post['order'] as $order) {
//                $orderBy[] = ($order['column'] + 3) . ' ' . $order['dir'];
//            }
//            $sql .= ' 
//                    ORDER BY ' . implode(', ', $orderBy);
//        } else {
//            $sql .= ' 
//                    ORDER BY 2';
//        }
//        $sql .= " 
//                LIMIT {$post['start']}, {$post['length']}";
//
//        $query = $this->db->query($sql);
//        $list = $query->result();
//
//        $data = array();
//        foreach ($list as $avaliacao) {
//            $row = array();
//            $row[] = $avaliacao->nome;
//            $row[] = $avaliacao->cargo;
//            $row[] = '
//                      <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_pdi(' . "'" . $avaliacao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
//                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pdi(' . "'" . $avaliacao->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
//                     ';
//
//            $data[] = $row;
//        }
//
//        $output = array(
//            "draw" => $post['draw'],
//            "recordsTotal" => $this->gerenciarAvaliacao->count_all(),
//            "recordsFiltered" => $query->num_rows(),
//            "data" => $data,
//        );
        //output to json format
//        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('pdi_desenvolvimento', array('id' => $id))->row();
        $dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_inicio = $dataFormatada;

        $dataFimFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));
        $data->data_termino = $dataFimFormatada;

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $this->db->select('data_inicio, data_termino');
        $pdi = $this->db->get_where('pdi', array('id' => $data['id_pdi']))->row();
        if ($data['data_inicio'] < $pdi->data_inicio) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de início deve ser igual ou maior que a data de início do PDI')));
        }
        if ($data['data_termino'] > $pdi->data_termino) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de término deve ser igual ou menor que a data de término do PDI')));
        }

        $status = (bool)$this->db->insert('pdi_desenvolvimento', $data);
        echo json_encode(array("status" => $status));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $this->db->select('data_inicio, data_termino');
        $pdi = $this->db->get_where('pdi', array('id' => $data['id_pdi']))->row();
        if ($data['data_inicio'] < $pdi->data_inicio) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de início deve ser igual ou maior do que a data de início do PDI')));
        }
        if ($data['data_termino'] > $pdi->data_termino) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de término deve ser igual ou menor do que a data de término do PDI')));
        }

        $status = (bool)$this->db->update('pdi_desenvolvimento', $data, array('id' => $data['id']));
        echo json_encode(array("status" => $status));
    }

    public function ajax_delete($id)
    {
        $status = (bool)$this->db->delete('pdi_desenvolvimento', array('id' => $id));
        echo json_encode(array("status" => $status));
    }

}
