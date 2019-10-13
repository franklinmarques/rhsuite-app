<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliador extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Avaliadordimensao_model', 'avaliadorDimensao');
    }

    public function index()
    {
        $data['id_usuario'] = $this->session->userdata('id');

        $this->load->view('competencias/avaliador_avaliacao', $data);
    }

    public function avaliados()
    {
        $data['id_avaliacao'] = $this->uri->rsegment(3);
        $data['id_usuario'] = $this->session->userdata('id');

        $this->load->view('competencias/avaliador_avaliados', $data);
    }

    public function tipo()
    {
        $data['id_usuario'] = $this->session->userdata('id');
        $data['id_avaliado'] = $this->uri->rsegment(3);
        $data['tipo_competencia'] = $this->uri->rsegment(4);
        $stringCompetencia = ($data['tipo_competencia'] == 1) ? "técnica" : "comportamental";

        $data['stringCompetencia'] = $stringCompetencia;

        $this->load->view('competencias/avaliador_tipo', $data);
    }

    public function dimensao()
    {
        $variaveis["id_avaliado"] = $this->uri->rsegment(3, 0);
        $variaveis["id_competencia"] = $this->uri->rsegment(4, 0);

        $this->db->select('id');
        $this->db->where('id_avaliado', $variaveis["id_avaliado"]);
        $this->db->where('id_usuario', $this->session->userdata('id'));
        $row = $this->db->get('competencias_avaliadores')->row();
        $variaveis["id_avaliador"] = $row->id;

        $this->db->select('a.nome AS competencia, b.cargo', false);
        $this->db->join('cargos b', 'b.id = a.id_cargo');
        $this->db->where('a.id', $variaveis["id_competencia"]);
        $row2 = $this->db->get('cargos_competencias a')->row();

        $variaveis["nome_cargo"] = $row2->cargo;
        $variaveis["nome_competencia"] = $row2->competencia;

        $this->load->view('competencias/avaliador_dimensao', $variaveis);
    }

    public function ajax_list($id)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.cargo_funcao,
                       s.data_inicio,
                       s.data_termino, 
                       s.id_competencia
                FROM (SELECT a.id, 
                             a.nome, 
                             CONCAT_WS('/', b.cargo, b.funcao) AS cargo_funcao, 
                             DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio,
                             DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino, 
                             c.id_competencia
                      FROM competencias a 
                      INNER JOIN cargos b ON 
                                 b.id = a.id_cargo 
                      INNER JOIN competencias_avaliados c ON 
                                 c.id_competencia = a.id 
                      INNER JOIN competencias_avaliadores d ON 
                                 d.id_avaliado = c.id 
                      WHERE d.id_usuario = {$id} AND
                            NOW() BETWEEN a.data_inicio AND a.data_termino
                      GROUP BY a.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.cargo_funcao', 's.data_inicio', 's.data_termino');
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
        }
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();

        $realizarAvaliacao = $this->agent->is_mobile() ? '' : ' Realizar avaliação';

        foreach ($list as $avaliacao) {
            $row = array();
            $row[] = $avaliacao->nome;
            $row[] = $avaliacao->cargo_funcao;
            $row[] = $avaliacao->data_inicio . ' a ' . $avaliacao->data_termino;

            $row[] = '
                      <a class="btn btn-sm btn-success" href="' . site_url('competencias/avaliador/avaliados/' . $avaliacao->id_competencia) . '" title="Realizar avaliacao de competências" ><i class="glyphicon glyphicon-plus"></i>' . $realizarAvaliacao . '</a>
                     ';

            $data[] = $row;
        }
        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_avaliados($id_competencia, $id_usuario)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.cargo_funcao
                FROM (SELECT c.id, 
                             d.nome, 
                             CONCAT_WS('/', d.cargo, d.funcao) AS cargo_funcao 
                      FROM competencias_avaliadores a 
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_usuario 
                      INNER JOIN competencias_avaliados c ON 
                                 c.id = a.id_avaliado 
                      INNER JOIN usuarios d ON 
                                 d.id = c.id_usuario 
                      INNER JOIN competencias e ON 
                                 e.id = c.id_competencia
                      WHERE b.id = {$id_usuario} AND 
                            e.id = {$id_competencia} AND
                            NOW() BETWEEN e.data_inicio AND e.data_termino) s";
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
        }
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();

        $compTecnicas = $this->agent->is_mobile() ? 'Ct' : 'C. Técnicas';
        $compComportamentais = $this->agent->is_mobile() ? 'Cc' : 'C. Comportamentais';

        foreach ($list as $avaliacao) {
            $row = array();
            $row[] = $avaliacao->nome;
            $row[] = $avaliacao->cargo_funcao;
            $row[] = '
                      <a class="btn btn-sm btn-primary" href="' . site_url('competencias/avaliador/tipo/' . $avaliacao->id . "/1") . '" title="Competências técnicas" ><i class="glyphicon glyphicon-check"></i> ' . $compTecnicas . '</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('competencias/avaliador/tipo/' . $avaliacao->id . "/2") . '" title="Competências comportamentais" ><i class="glyphicon glyphicon-check"></i> ' . $compComportamentais . '</a>
                      ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_tipo($id_usuario, $id_avaliado, $tipo_competencia = '')
    {
        $post = $this->input->post();

        $tipo = $tipo_competencia == 1 ? 'T' : ($tipo_competencia == 2 ? 'C' : '');

        $sql = "SELECT s.id, s.nome, s.status 
                FROM (SELECT a.id, 
                             a.nome, 
                             (CASE WHEN g.id IS NOT NULL 
                              THEN 'Avaliado' 
                              ELSE 'Avaliar' END) AS status
                      FROM cargos_competencias a 
                      INNER JOIN cargos_dimensao b ON 
                                 b.cargo_competencia = a.id 
                      INNER JOIN cargos c ON 
                                 c.id = a.id_cargo
                      INNER JOIN competencias d ON 
                                 d.id_cargo = c.id
                      INNER JOIN competencias_avaliados e ON 
                                 e.id_competencia = d.id
                      INNER JOIN competencias_avaliadores f ON
                                 f.id_avaliado = e.id
                      LEFT JOIN competencias_resultado g ON 
                                g.cargo_dimensao = b.id AND 
                                g.id_avaliador = f.id
                      WHERE a.tipo_competencia = '{$tipo}' AND
                            NOW() BETWEEN d.data_inicio AND d.data_termino AND 
                            f.id_avaliado = {$id_avaliado} AND 
                            f.id_usuario = {$id_usuario}
                            GROUP BY a.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.status');
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
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        $avaliarDimensão = $this->agent->is_mobile() ? '' : ' Avaliar dimensão';
        foreach ($list as $avaliadorCompetencia) {
            $row = array();
            $row[] = $avaliadorCompetencia->nome;
            $row[] = $avaliadorCompetencia->status;
            $row[] = '
                     <a class="btn btn-sm btn-primary" href="' . site_url('competencias/avaliador/dimensao/' . $id_avaliado . '/' . $avaliadorCompetencia->id) . '" title="Avaliar Dimensao"><i class="glyphicon glyphicon-check"></i> ' . $avaliarDimensão . '</a>
                     ';

            $data[] = $row;
        }

        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_dimensao($id_avaliador, $id_competencia)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,                        
                       s.nivel, 
                       s.atitude, 
                       s.peso, 
                       s.indice,
                       s.cargo_dimensao
                FROM (SELECT a.id AS cargo_dimensao, 
                             a.nome, 
                             a.peso,
                             g.id, 
                             g.nivel, 
                             g.atitude, 
                             (((CAST(a.peso AS DECIMAL) / 100) * g.nivel) * (g.atitude / 100)) AS indice
                      FROM cargos_dimensao a 
                      INNER JOIN cargos_competencias b ON 
                                 b.id = a.cargo_competencia 
                      INNER JOIN cargos c ON 
                                 c.id = b.id_cargo
                      INNER JOIN competencias d ON 
                                 d.id_cargo = c.id
                      INNER JOIN competencias_avaliados e ON 
                                 e.id_competencia = d.id
                      INNER JOIN competencias_avaliadores f ON
                                 f.id_avaliado = e.id
                      LEFT JOIN competencias_resultado g ON 
                                g.cargo_dimensao = a.id AND 
                                g.id_avaliador = f.id
                      WHERE b.id = {$id_competencia} AND 
                            NOW() BETWEEN d.data_inicio AND d.data_termino AND
                            f.id = {$id_avaliador}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.nivel', 's.atitude', 's.peso', 's.indice');
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
                $orderBy[] = ($order['column'] + 3) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }

        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $dimensao) {
            $row = array();
            $row[] = $dimensao->nome;
            $row[] = $dimensao->peso;
            $row[] = $dimensao->nivel;
            $row[] = $dimensao->atitude;
            $row[] = $dimensao->indice ? round($dimensao->indice, 3) : $dimensao->indice;

            //add html for action
            if ($dimensao->id) {
                $row[] = '
                          <a class="btn btn-sm btn-success btn-block" href="javascript:void(0)" title="Reavaliar" onclick="edit_avaliadorDimensao(' . $dimensao->cargo_dimensao . ', ' . $dimensao->id . ')"><i class="glyphicon glyphicon-check"></i></a>
                         ';
            } else {
                $row[] = '
                          <a class="btn btn-sm btn-primary btn-block" href="javascript:void(0)" title="Realizar avaliação" onclick="edit_avaliadorDimensao(' . $dimensao->cargo_dimensao . ')"><i class="glyphicon glyphicon-edit"></i></a>
                         ';
            }

            $data[] = $row;
        }

        $output = array(
            "draw" => $post['draw'],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $cargo_dimensao = $this->input->post('cargo_dimensao');

        $this->db->select('a.id AS cargo_dimensao, a.nome, b.id, b.nivel, b.atitude');
        if ($id) {
            $this->db->join('competencias_resultado b', "b.cargo_dimensao = a.id and b.id = {$id}", 'left');
        } else {
            $this->db->join('competencias_resultado b', 'b.cargo_dimensao = a.id and b.id is null', 'left');
        }
        $this->db->where('a.id', $cargo_dimensao);
        $data = $this->db->get('cargos_dimensao a')->row();

        echo json_encode($data);
    }

    public function ajax_save()
    {
        $data = $this->input->post();
        if ($data['nivel'] === '') {
            $data['nivel'] = null;
        }
        if ($data['atitude'] === '') {
            $data['atitude'] = null;
        }
        if ($data['id']) {
            $this->db->update('competencias_resultado', $data, array('id' => $data['id']));
        } else {
            $this->db->insert('competencias_resultado', $data);
        }

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete()
    {
//        $id = $this->input->post('id');
//        $this->db->delete('competencias_resultado', array('id' => $id));
//
//        echo json_encode(array("status" => TRUE));
    }

}
