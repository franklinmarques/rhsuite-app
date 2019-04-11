<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliados extends MY_Controller {

    public function __construct() {
        parent::__construct();
//        $this->load->model('Avaliacao_model', 'avaliacao');
    }

    public function index() {
        $data['id_competencia'] = $this->uri->rsegment(3);
        $data['id_empresa'] = $this->session->userdata('id');

        $this->load->view('competencias/avaliados', $data);
    }

    public function novo() {
        $this->db->select('id');
        $this->db->where('id', $this->uri->rsegment(3, 0));
        $this->db->where('id_usuario_EMPRESA', $this->session->userdata('id'));
        $competencia = $this->db->get('competencias')->row();
        if (empty($competencia)) {
            die();
        }
        $data['id_competencia'] = $competencia->id;

        $sql = "SELECT a.id, 
                       a.nome, 
                       a.cargo, 
                       a.funcao, 
                       a.depto, 
                       a.area, 
                       a.setor 
                FROM usuarios a 
                INNER JOIN cargos b ON 
                           b.cargo = a.cargo AND 
                           b.funcao = a.funcao 
                INNER JOIN competencias c ON 
                           c.id_cargo = b.id 
                WHERE c.id = {$competencia->id} AND 
                      a.id NOT IN (SELECT d.id_usuario 
                                   FROM competencias_avaliados d 
                                   WHERE d.id_usuario = a.id AND 
                                         d.id_competencia = c.id) 
                ORDER BY a.nome ASC";
        $avaliados = $this->db->query($sql)->result();

        foreach ($avaliados as $avaliado) {
            $data['comboAvaliado'][] = $avaliado;
        }

        $data['id'] = '';
        $data['avaliado'] = '';

        $this->db->select('distinct(depto)');
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $this->db->order_by('depto', 'asc');
        $arrDepto = $this->db->get('usuarios')->result();
        $depto = array();
        foreach ($arrDepto as $row) {
            $depto[$row->depto] = $row->depto;
        }

        $data['depto'] = array('' => 'Todos') + $depto;
        $data['area'] = array('' => 'Todos');
        $data['setor'] = array('' => 'Todos');

        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('id'));
        $this->db->where_in('depto', $depto + array(''));
        $avaliadores = $this->db->get('usuarios')->result();

        $data['duallistAvaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['duallistAvaliadores'][$avaliador->id] = $avaliador->nome;
        }
        $data['avaliadores'] = array();

        $this->load->view('competencias/avaliadores', $data);
    }

    public function gerenciar_avaliadores() {
        $this->db->select('a.id, a.id_competencia, a.id_usuario');
        $this->db->join('competencias b', 'b.id = a.id_competencia');
        $this->db->where('a.id', $this->uri->rsegment(3, 0));
        $this->db->where('b.id_usuario_EMPRESA', $this->session->userdata('id'));
        $avaliado = $this->db->get('competencias_avaliados a')->row();
        if (empty($avaliado)) {
            die();
        }
        $data['id'] = $avaliado->id;
        $data['id_competencia'] = $avaliado->id_competencia;
        $data['avaliado'] = $avaliado->id_usuario;

        $sql = "SELECT a.id, 
                       a.nome, 
                       a.cargo, 
                       a.funcao, 
                       a.depto, 
                       a.area, 
                       a.setor 
                FROM usuarios a 
                INNER JOIN cargos b ON 
                           b.cargo = a.cargo AND 
                           b.funcao = a.funcao 
                INNER JOIN competencias c ON 
                           c.id_cargo = b.id 
                WHERE c.id = {$avaliado->id_competencia} AND 
                      a.id NOT IN (SELECT d.id_usuario 
                                   FROM competencias_avaliados d 
                                   WHERE d.id != {$avaliado->id} AND 
                                         d.id_usuario = a.id AND 
                                         d.id_competencia = c.id) 
                ORDER BY a.nome ASC";
        $avaliados = $this->db->query($sql)->result();

        foreach ($avaliados as $row) {
            $data['comboAvaliado'][] = $row;
        }

        $this->db->select('distinct(depto)');
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $this->db->order_by('depto', 'asc');
        $arrDepto = $this->db->get('usuarios')->result();
        $depto = array();
        foreach ($arrDepto as $row) {
            $depto[$row->depto] = $row->depto;
        }

        $data['depto'] = array('' => 'Todos') + $depto;
        $data['area'] = array('' => 'Todos');
        $data['setor'] = array('' => 'Todos');

        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('id'));
        $this->db->where_in('depto', $depto + array(''));
        $avaliadores = $this->db->get('usuarios')->result();

        $data['duallistAvaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['duallistAvaliadores'][$avaliador->id] = $avaliador->nome;
        }

        $this->db->select('a.id_usuario');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $this->session->userdata('id'));
        $this->db->where('a.id_avaliado', $avaliado->id);
        $selecionados = $this->db->get('competencias_avaliadores a')->result();
        $data['avaliadores'] = array();
        foreach ($selecionados as $selecionado) {
            $data['avaliadores'][] = $selecionado->id_usuario;
        }

        $this->load->view('competencias/avaliadores', $data);
    }

    public function ajax_list($id, $empresa) {
        $post = $this->input->post();
        $sql = "SELECT s.id, 
                       s.nome, 
                       s.cargo_funcao 
                FROM (SELECT a.id, 
                             b.nome, 
                             CONCAT_WS('/', b.cargo, b.funcao) as cargo_funcao 
                      FROM competencias_avaliados a 
                      INNER JOIN usuarios b ON 
                                 a.id_usuario = b.id
                      INNER JOIN competencias c ON 
                                 c.id = a.id_competencia 
                      WHERE c.id = {$id} AND 
                            c.id_usuario_EMPRESA = {$empresa}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.cargo_funcao');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " AND 
                        ({$column} LIKE '%{$post['search']['value']}%'";
                }
            }
            $sql .= ')';
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        } else {
            $sql .= ' 
                    ORDER BY 2';
        }
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $avaliacao) {
            $row = array();
            $row[] = $avaliacao->nome;
            $row[] = $avaliacao->cargo_funcao;
            $row[] = '
                      <a class="btn btn-sm btn-success" href="' . site_url('competencias/avaliados/gerenciar_avaliadores/' . $avaliacao->id) . '" title="Editar Avaliadores" ><i class="glyphicon glyphicon-plus"></i>Gerenciar Avaliadores</a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir Avaliado" onclick="delete_avaliado(' . $avaliacao->id . ')"><i class="glyphicon glyphicon-trash"></i></a>
                     ';

            $data[] = $row;
        }

        $output = array(
            'draw' => $post['draw'],
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit() {
        $selecionados = $this->input->post('selecionados');
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');

        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        if ($area) {
            $this->db->where('area', $area);
        }
        if ($setor) {
            $this->db->where('setor', $setor);
        }
        if ($selecionados) {
            $this->db->or_where_in('id', $selecionados);
        }
        $this->db->order_by('nome', 'ASC');
        $rows = $this->db->get('usuarios')->result();
        $options = array();
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $optionsArea = array('' => 'Todos');

        if ($depto) {
            $this->db->distinct('area');
            $this->db->where('depto', $depto);
            $this->db->where('CHAR_LENGTH(area) >', 0);
            $areas = $this->db->get('usuarios')->result();

            foreach ($areas as $row) {
                $optionsArea[$row->area] = $row->area;
            }
        } else {
            $area = '';
            $setor = '';
        }

        $optionsSetor = array('' => 'Todos');

        if ($area) {
            $this->db->distinct('setor');
            $this->db->where('depto', $depto);
            $this->db->where('area', $area);
            $this->db->where('CHAR_LENGTH(setor) >', 0);
            $setores = $this->db->get('usuarios')->result();

            foreach ($setores as $row) {
                $optionsSetor[$row->setor] = $row->setor;
            }
        } else {
            $setor = '';
        }

        $data['area'] = form_dropdown('area', $optionsArea, "{$area}", 'class="form-control filtro input-sm"');
        $data['setor'] = form_dropdown('setor', $optionsSetor, "{$setor}", 'class="form-control filtro input-sm"');
        $data['avaliadores'] = form_multiselect('id_usuario_avaliadores[]', $options, array(), 'size="10" id="id_usuario_avaliadores" class="avaliadores"');

        echo json_encode($data);
    }

    public function ajax_save() {
        $avaliadores = $this->input->post('id_usuario_avaliadores');
        if (count(array_filter($avaliadores)) == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A avaliação necessita ao menos de um avaliador')));
        }

        $data['id'] = $this->input->post('id');
        $avaliado = $data['id'];
        $data['id_usuario'] = $this->input->post('avaliado');
        $data['id_competencia'] = $this->input->post('id_competencia');
        $num_rows = $this->db->get_where('competencias_avaliados', array('id' => $data['id']))->num_rows();
        if ($num_rows) {
            $this->db->update('competencias_avaliados', $data, array('id' => $data['id']));
        } else {
            $this->db->insert('competencias_avaliados', $data);
            $avaliado = $this->db->insert_id();
        }

        $this->db->where('id_avaliado', $avaliado);
        $this->db->where_not_in('id_usuario', $avaliadores);
        $this->db->delete('competencias_avaliadores');

        foreach ($avaliadores as $avaliador) {
            $this->db->where('id_avaliado', $avaliado);
            $this->db->where('id_usuario', $avaliador);
            $row = $this->db->get('competencias_avaliadores')->row();

            $data = array(
                'id_usuario' => $avaliador,
                'id_avaliado' => $avaliado
            );
            if (count($row)) {
                $data['id'] = $row->id;
                $this->db->update('competencias_avaliadores', $data, array('id' => $row->id));
            } else {
                $this->db->insert('competencias_avaliadores', $data);
            }
        }

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_add() {
        $data = $this->input->post();
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));
        }
        $this->db->insert('competencias', $data);

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update() {
        $data = $this->input->post();
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));
        }
        $this->db->update('competencias', $data, array('id' => $data['id']));

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete() {
        $data = $this->input->post();
        $this->db->delete('competencias_avaliados', array('id' => $data['id']));

        echo json_encode(array("status" => TRUE));
    }

}
