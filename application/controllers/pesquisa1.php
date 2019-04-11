<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pesquisa_model', 'pesquisa');
    }

    public function index()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => ''
        );
        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $data['empresa']);
        $data['modelos'] = $this->db->get('pesquisa_modelos')->result();

        $this->load->view('pesquisa', $data);
    }

    public function clima()
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }
        $data['empresa'] = $this->session->userdata('empresa');
        $data['tipo'] = 'clima';
        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $data['empresa']);
        $this->db->where('tipo', 'C');
        $data['modelos'] = $this->db->get('pesquisa_modelos')->result();

        $this->db->select('nome, id');
        $this->db->where('empresa', $data['empresa']);
        $this->db->order_by('nome', 'ASC');
        $avaliadores = $this->db->get('usuarios')->result();

        $data['avaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['avaliadores'][$avaliador->id] = $avaliador->nome;
        }

        $data['avaliado'] = array('' => 'selecione...') + $data['avaliadores'];

        $this->load->view('pesquisa', $data);
    }

    public function eneagrama()
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }
        $data['empresa'] = $this->session->userdata('empresa');
        $data['tipo'] = 'personalidade';
        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $data['empresa']);
        $this->db->where('tipo', 'E');
        $data['modelos'] = $this->db->get('pesquisa_modelos')->result();

        $this->db->select('nome, id');
        $this->db->where('empresa', $data['empresa']);
        $this->db->order_by('nome', 'ASC');
        $avaliadores = $this->db->get('usuarios')->result();

        $data['avaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['avaliadores'][$avaliador->id] = $avaliador->nome;
        }

        $data['avaliado'] = array('' => 'selecione...') + $data['avaliadores'];

        $this->load->view('pesquisa', $data);
    }

    public function personalidade()
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }
        $data['empresa'] = $this->session->userdata('empresa');
        $data['tipo'] = 'personalidade';
        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $data['empresa']);
        $this->db->where('tipo', 'E');
        $data['modelos'] = $this->db->get('pesquisa_modelos')->result();

        $this->db->select('nome, id');
        $this->db->where('empresa', $data['empresa']);
        $this->db->order_by('nome', 'ASC');
        $avaliadores = $this->db->get('usuarios')->result();

        $data['avaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['avaliadores'][$avaliador->id] = $avaliador->nome;
        }

        $data['avaliado'] = array('' => 'selecione...') + $data['avaliadores'];

        $this->load->view('pesquisa_eneagrama', $data);
    }

    public function perfil()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => 'perfil'
        );
        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $data['empresa']);
        $this->db->where('tipo', 'P');
        $data['modelos'] = $this->db->get('pesquisa_modelos')->result();

        $this->load->view('pesquisa', $data);
    }

    public function ajax_list($id, $tipo = '')
    {
        if (empty($id)) {
            $id = $this->session->userdata('empresa');
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.modelo,
                       s.tipo,
                       s.data_inicio,
                       s.data_termino
                FROM (SELECT a.id, 
                             a.nome,
                             b.nome AS modelo,
                             (case b.tipo 
                              when 'C' then 'Clima' 
                              when 'E' then 'Personalidade' 
                              when 'P' then 'Perfil'
                              else '' end) AS tipo,
                             a.data_inicio,
                             a.data_termino
                      FROM pesquisa a
                      INNER JOIN pesquisa_modelos b ON 
                                 b.id = a.id_modelo
                      LEFT JOIN pesquisa_avaliadores c
                                ON c.id_pesquisa = a.id
                                AND b.tipo = 'E'
                      LEFT JOIN usuarios d
                                ON d.id = c.id_avaliador
                      WHERE b.id_usuario_EMPRESA = {$id}";
        if ($tipo) {
            if ($tipo == 'clima') {
                $tipo = 'C';
            } elseif ($tipo == 'personalidade') {
                $tipo = 'E';
            } elseif ($tipo == 'perfil') {
                $tipo = 'P';
            }
            $sql .= " AND b.tipo = '{$tipo}'";
        }
        $sql .= ") s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.modelo', 's.tipo', 's.data_inicio', 's.data_termino');
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
            $row[] = $pesquisa->nome;
            $row[] = $pesquisa->modelo;
            $row[] = $pesquisa->tipo;
            $row[] = $pesquisa->data_inicio ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_inicio))) : '';
            $row[] = $pesquisa->data_termino ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_termino))) : '';

            $botoes = '
			<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_pesquisa(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pesquisa(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a> ';
            if ($pesquisa->tipo == 'Clima' || $pesquisa->tipo == 'Personalidade') {
                $botoes .= '
                        <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Participantes" onclick="edit_participantes(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Participantes</a>
			<a class="btn btn-sm btn-info" href="' . site_url('pesquisa/relatorio/' . $pesquisa->id) . '" title="Relatórios"><i class="glyphicon glyphicon-list-alt"></i> Relatórios</a>
			<a class="btn btn-sm btn-warning" href="' . site_url('pesquisa/status/' . $pesquisa->id) . '" title="Status"><i class="glyphicon glyphicon-info-sign"></i> Status</a>
			';
            } elseif ($pesquisa->tipo == 'Perfil') {
                $botoes .= '
                            <a class="btn btn-sm btn-success" href="' . site_url('pesquisa_avaliados/gerenciar/' . $pesquisa->id) . '" title="Avaliadores X Avaliados"><i class="glyphicon glyphicon-plus"></i> Avaliadores X Avaliados</a>
                            ';
            }

            $row[] = $botoes;

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

    public function ajax_personalidade($id)
    {
        if (empty($id)) {
            $id = $this->session->userdata('empresa');
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.avaliador,
                       s.modelo,
                       s.tipo,
                       s.data_inicio,
                       s.data_termino
                FROM (SELECT c.id, 
                             a.nome,
                             d.nome AS avaliador,
                             b.nome AS modelo,
                             (case b.tipo 
                              when 'E' then 'Personalidade' 
                              else '' end) AS tipo,
                             a.data_inicio,
                             a.data_termino
                      FROM pesquisa a
                      INNER JOIN pesquisa_modelos b ON 
                                 b.id = a.id_modelo
                      LEFT JOIN pesquisa_avaliadores c
                                ON c.id_pesquisa = a.id
                                AND b.tipo = 'E'
                      LEFT JOIN usuarios d
                                ON d.id = c.id_avaliador
                      WHERE b.id_usuario_EMPRESA = {$id}
                            AND b.tipo = 'E') s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.modelo', 's.tipo', 's.data_inicio', 's.data_termino');
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
            $row[] = $pesquisa->nome;
            $row[] = $pesquisa->avaliador;
            $row[] = $pesquisa->modelo;
            $row[] = $pesquisa->tipo;
            $row[] = $pesquisa->data_inicio ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_inicio))) : '';
            $row[] = $pesquisa->data_termino ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_termino))) : '';

            $row[] = '
			            <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_pesquisa(' . $pesquisa->id . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			            <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pesquisa(' . $pesquisa->id . ')"><i class="glyphicon glyphicon-trash"></i></a>
                        <a class="btn btn-sm btn-info" href="' . site_url('pesquisa/relatorio/' . $pesquisa->id) . '" title="Relatórios"><i class="glyphicon glyphicon-list-alt"></i> Relatórios</a>
			            <a class="btn btn-sm btn-warning" href="' . site_url('pesquisa/status/' . $pesquisa->id) . '" title="Status"><i class="glyphicon glyphicon-info-sign"></i> Status</a>
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

    public function ajax_avaliadores()
    {
        $where = array_filter($this->input->post());
        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'ASC');
        $rows = $this->db->get_where('usuarios', $where)->result();
        $options = array();
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $data['avaliadores'] = form_multiselect('avaliadores', $options, array(), 'size="10" id="avaliadores" class="demo2"');

        echo json_encode($data);
    }

    public function ajax_edit($id)
    {
        $this->db->select('*, NULL AS avaliador', false);
        $data = $this->db->get_where('pesquisa', array('id' => $id))->row();

        $data->data_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_termino = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));

        $this->db->select('tipo');
        $modelo = $this->db->get_where('pesquisa_modelos', array('id' => $data->id_modelo))->row();
        if ($modelo->tipo == 'E') {
            $this->db->select('id_avaliador AS id', false);
            $avaliador = $this->db->get_where('pesquisa_avaliadores', array('id_pesquisa' => $id))->row();
            $data->avaliador = $avaliador->id;
        }

        echo json_encode($data);
    }

    public function ajax_editEneagrama($id)
    {
        $this->db->select('*, NULL AS avaliadores', false);
        $data = $this->db->get_where('pesquisa', array('id' => $id))->row();
        if (count($data) == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Pesquisa de eneagrama não encontrada')));
        }

        $data->data_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_termino = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));

        $this->db->select('id_avaliador AS id');
        $avaliadores = $this->db->get_where('pesquisa_avaliadores', array('id_pesquisa' => $data->id, 'id_avaliado' => null))->result();
        $data->avaliadores = array();
        foreach ($avaliadores as $avaliador) {
            $data->avaliadores[] = $avaliador->id;
        }

        echo json_encode($data);
    }

    public function ajax_editParticipantes($id)
    {
        $row = $this->db->get_where('pesquisa', array('id' => $id))->row();
        if (count($row) == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Pesquisa de clima não encontrada')));
        }
        $avaliadores = $this->db->get_where('pesquisa_avaliadores', array('id_pesquisa' => $row->id, 'id_avaliado' => null))->result();
        $data['pesquisa'] = $row->id;
        $data['avaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['avaliadores'][] = $avaliador->id_avaliador;
        }

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }

        $avaliadores = $data['avaliador'] ?? null;
        if (is_string($avaliadores)) {
            $avaliadores = array($avaliadores);
        }
        $this->db->select('tipo');
        $modelo = $this->db->get_where('pesquisa_modelos', array('id' => $data['id_modelo']))->row();
        if ($modelo->tipo == 'E' and empty($avaliadores)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum avaliador incluso')));
        }

        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        if (isset($data['avaliador'])) {
            unset($data['avaliador']);
        }

        $this->db->trans_start();
        $this->db->insert('pesquisa', $data);

        if ($modelo->tipo == 'E') {
            $pesquisa = $this->db->insert_id();
            $data2 = array();
            foreach ($avaliadores as $avaliador) {
                $data2[] = array(
                    'id_pesquisa' => $pesquisa,
                    'id_avaliador' => $avaliador
                );
            }
            $this->db->insert_batch('pesquisa_avaliadores', $data2);
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addParticipantes()
    {
        $avaliadores = $this->input->post('avaliadores');
        if (empty($avaliadores)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum avaliador incluso')));
        }
        $pesquisa = $this->input->post('pesquisa');

        $this->db->trans_begin();

        foreach ($avaliadores as $avaliador) {
            $data = array(
                'id_pesquisa' => $pesquisa,
                'id_avaliador' => $avaliador
            );
            $this->db->query($this->db->insert_string('pesquisa_avaliadores', $data));
        }

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }

        $avaliadores = $data['avaliador'] ?? null;
        if (is_string($avaliadores)) {
            $avaliadores = array($avaliadores);
        }
        $this->db->select('tipo');
        $modelo = $this->db->get_where('pesquisa_modelos', array('id' => $data['id_modelo']))->row();
        if ($modelo->tipo == 'E' and empty($avaliadores)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum avaliador incluso')));
        }

        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $id = $data['id'];
        unset($data['id'], $data['avaliador']);
        $where = array('id' => $id);

        $this->db->trans_start();
        $this->db->update('pesquisa', $data, $where);

        if ($modelo->tipo == 'E') {
            $strAvaliadores = implode(',', $avaliadores);
            $delete = "DELETE FROM pesquisa_avaliadores 
                       WHERE id_pesquisa = {$id} AND 
                             id_avaliado IS NULL AND
                             id_avaliador NOT IN ({$strAvaliadores})";
            $this->db->query($delete);

            $select = "SELECT id, id_avaliador 
                       FROM pesquisa_avaliadores 
                       WHERE id_pesquisa = {$id} AND 
                             id_avaliado IS NULL";
            $rows = $this->db->query($select)->result();
            $arrAvaliadores = array();
            foreach ($rows as $row) {
                $arrAvaliadores[$row->id] = $row->id_avaliador;
            }

            $keysAvaliadores = array_flip($arrAvaliadores);

            foreach ($avaliadores as $avaliador) {
                $data = array(
                    'id_pesquisa' => $id,
                    'id_avaliador' => $avaliador
                );

                if (in_array($avaliador, $arrAvaliadores)) {
                    $where = array('id' => $keysAvaliadores[$avaliador]);
                    $this->db->query($this->db->update_string('pesquisa_avaliadores', $data, $where));
                } else {
                    $this->db->query($this->db->insert_string('pesquisa_avaliadores', $data));
                }
            }

        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateParticipantes()
    {
        $postAvaliadores = $this->input->post('avaliadores');
        $avaliadores = is_array($postAvaliadores) ? $postAvaliadores : array($postAvaliadores);
        if (empty($avaliadores)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum avaliador incluso')));
        }
        $pesquisa = $this->input->post('pesquisa');

        $this->db->trans_begin();

        $strAvaliadores = implode(',', $avaliadores);
        $delete = "DELETE FROM pesquisa_avaliadores 
                   WHERE id_pesquisa = {$pesquisa} AND 
                         id_avaliado IS NULL AND
                         id_avaliador NOT IN ({$strAvaliadores})";
        $this->db->query($delete);

        $select = "SELECT id, id_avaliador 
                   FROM pesquisa_avaliadores 
                   WHERE id_pesquisa = {$pesquisa} AND 
                         id_avaliado IS NULL";
        $rows = $this->db->query($select)->result();
        $arrAvaliadores = array();
        foreach ($rows as $row) {
            $arrAvaliadores[$row->id] = $row->id_avaliador;
        }

        $keysAvaliadores = array_flip($arrAvaliadores);

        foreach ($avaliadores as $avaliador) {
            $data = array(
                'id_pesquisa' => $pesquisa,
                'id_avaliador' => $avaliador
            );

            if (in_array($avaliador, $arrAvaliadores)) {
                $where = array('id' => $keysAvaliadores[$avaliador]);
                $this->db->query($this->db->update_string('pesquisa_avaliadores', $data, $where));
            } else {
                $this->db->query($this->db->insert_string('pesquisa_avaliadores', $data));
            }
        }

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
        $status = $this->db->delete('pesquisa', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function get_tipo()
    {
        $id = $this->input->post('id');
        $row = $this->db->get_where('pesquisa_modelos', array('id' => $id))->row();
        $result = '';
        if (count($row) == 1) {
            $result = $row->tipo;
        }
        echo $result;
    }

    public function status($id)
    {
        $sql = "SELECT a.id, 
                       b.nome, 
                       b.tipo, 
                       DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino, 
                       'PESQUISA DE CLIMA - ANDAMENTO' as titulo
                FROM pesquisa a 
                INNER JOIN pesquisa_modelos b ON 
                           b.id = a.id_modelo 
                WHERE a.id = {$id}";
        $row = $this->db->query($sql)->row();

        if ($row->tipo == 'P') {
            $row->titulo = 'PESQUISA DE PERFIL - ANDAMENTO';
            $query = "SELECT c.nome, 
                             c.funcao, 
                             CONCAT_WS('/', c.depto, c.area, c.setor) AS depto, 
                             DATE_FORMAT(c.data_admissao, '%d/%m/%Y') AS data_admissao
                      FROM pesquisa_avaliados a 
                      INNER JOIN pesquisa b ON 
                                 b.id = a.id_pesquisa 
                      INNER JOIN usuarios c ON
                                 c.id = a.id_avaliado
                      WHERE a.id_pesquisa = {$row->id}";
            $data['avaliado'] = $this->db->query($query)->row();
        }
        $data['pesquisa'] = $row;
//s.qtde_perguntas = s.qtde_respostas AND s.qtde_perguntas > 0
        $query = "SELECT s.nome, 
                         s.funcao, 
                         s.depto, 
                         (s.qtde_perguntas > 0 AND s.qtde_respostas > 0) AS status 
                  FROM (SELECT c.nome, 
                               c.funcao, 
                               CONCAT_WS('/', c.depto, c.area, c.setor) AS depto, 
                               (SELECT count(p.id) 
                                FROM pesquisa_perguntas p 
                                INNER JOIN pesquisa_modelos m ON 
                                           m.id = p.id_modelo 
                                WHERE m.id = b.id_modelo) AS qtde_perguntas,
                               (SELECT count(r.id_pergunta) 
                                FROM pesquisa_resultado r 
                                WHERE r.id_avaliador = a.id) AS qtde_respostas
                        FROM pesquisa_avaliadores a 
                        INNER JOIN pesquisa b ON 
                                   b.id = a.id_pesquisa 
                        INNER JOIN usuarios c ON
                                   c.id = a.id_avaliador
                        WHERE a.id_pesquisa = {$row->id}) s";
        $avaliadores = $this->db->query($query)->result();
        $data['avaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['avaliadores'][] = $avaliador;
        }

        $this->load->view('pesquisa_status', $data);
    }

    public function relatorio($pesquisa, $pdf = false)
    {
        if (empty($pesquisa)) {
            $pesquisa = $this->uri->rsegment(3);
        }

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $sql = "SELECT a.id, 
                       b.nome, 
                       b.tipo, 
                       '{$this->input->get('depto')}' AS depto,
                       '{$this->input->get('area')}' AS area,
                       '{$this->input->get('setor')}' AS setor,
                       DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino
                FROM pesquisa a 
                INNER JOIN pesquisa_modelos b ON 
                           b.id = a.id_modelo 
                WHERE a.id = {$pesquisa}";
        $row = $this->db->query($sql)->row();
        $data['pesquisa'] = $row;

        $sql2 = "SELECT a.alternativa
                 FROM pesquisa_alternativas a 
                 INNER JOIN pesquisa_modelos b ON 
                            b.id = a.id_modelo 
                 INNER JOIN pesquisa c ON
                            c.id_modelo = b.id
                 WHERE c.id = {$row->id} AND 
                       a.id_pergunta IS NULL";
        $alternativas = $this->db->query($sql2)->result();
        $data['alternativas'] = $alternativas;

        foreach (array('depto', 'area', 'setor') as $field) {
            $sql3 = "SELECT DISTINCT(c.{$field})  
                     FROM pesquisa_avaliadores a 
                     INNER JOIN pesquisa b ON 
                                b.id = a.id_pesquisa 
                     INNER JOIN usuarios c ON 
                                c.id = a.id_avaliador
                     WHERE a.id_pesquisa = {$row->id} AND 
                           c.{$field} IS NOT NULL";
            $rows = $this->db->query($sql3)->result();
            $data[$field] = array();
            foreach ($rows as $row2) {
                $data[$field][$row2->$field] = $row2->$field;
            }
        }

        $data['is_pdf'] = $pdf;

        if ($pdf) {

            $depto = $this->input->get('depto');
            $area = $this->input->get('area');
            $setor = $this->input->get('setor');

            $data['data'] = $this->get_relatorio($pesquisa, $depto, $area, $setor);

            return $this->load->view('getpesquisa_relatorio', $data, true);
        } else {

            $this->load->view('pesquisa_relatorio', $data);
        }
    }

    public function ajax_relatorio($id)
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');

        $data = $this->get_relatorio($id, $depto, $area, $setor);

        $output = array(
            "draw" => $this->input->post('draw'),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    private function get_relatorio($id, $depto, $area, $setor)
    {
        $this->db->select('a.id');
        $this->db->join('usuarios b', 'b.id = a.id_avaliador');
        $this->db->where('a.id_pesquisa', $id);
        $where = array_filter(array('b.depto' => $depto, 'b.area' => $area, 'b.setor' => $setor));
        if ($where) {
            $this->db->where($where);
        }
        $rows = $this->db->get('pesquisa_avaliadores a')->result();
        $avaliadores = array();
        foreach ($rows as $row) {
            $avaliadores[] = $row->id;
        }

        $strAvaliadores = implode(',', $avaliadores);

        $sql = "SELECT a.id,
                       d.categoria,
                       a.pergunta 
                FROM pesquisa_perguntas a 
                INNER JOIN pesquisa_modelos b ON 
                           b.id = a.id_modelo 
                INNER JOIN pesquisa c ON
                           c.id_modelo = b.id
                LEFT JOIN pesquisa_categorias d ON
                          d.id = a.id_categoria
                WHERE c.id = {$id} 
                ORDER BY d.id, 
                         a.id";

        $perguntas = $this->db->query($sql)->result();

        $sql3 = "SELECT a.id,
                        a.alternativa, 
                        a.peso 
                 FROM pesquisa_alternativas a 
                 INNER JOIN pesquisa_modelos b ON 
                            b.id = a.id_modelo 
                 INNER JOIN pesquisa c ON
                            c.id_modelo = b.id
                 WHERE c.id = {$id} AND 
                       a.id_pergunta IS NULL";
        $alternativas = $this->db->query($sql3)->result();

        $data = array();

        if ($avaliadores) {
            $sql4 = "SELECT distinct(a.id_pergunta), 
                        a.id_alternativa, 
                        COUNT(a.id_alternativa) / (SELECT count(b.id_pergunta) 
                                                   FROM pesquisa_resultado b 
                                                   WHERE b.id_avaliador IN ({$strAvaliadores}) and 
                                                         b.id_pergunta = a.id_pergunta) * 100 AS resposta
                 FROM pesquisa_resultado a
                 WHERE a.id_avaliador IN ({$strAvaliadores}) 
                 GROUP BY a.id_pergunta, 
                          a.id_alternativa";
            $rows2 = $this->db->query($sql4)->result();
            $resultado = array();
            foreach ($rows2 as $row2) {
                $resultado[$row2->id_pergunta][$row2->id_alternativa] = $row2->resposta;
            }
        }

        foreach ($perguntas as $pergunta) {
            $row = array();
            $row[] = $pergunta->categoria;
            $row[] = $pergunta->pergunta;

            $resposta = array();
            if (isset($resultado[$pergunta->id])) {
                $resposta = $resultado[$pergunta->id];
            }
            foreach ($alternativas as $alternativa) {
                if (isset($resposta[$alternativa->id])) {
                    $row[] = round($resposta[$alternativa->id], 2);
                } elseif ($resposta) {
                    $row[] = 0;
                } else {
                    $row[] = null;
                }
            }

            $data[] = $row;
        }

        return $data;
    }

    public function pdfRelatorio()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.pesquisa thead th { font-size: 11px; padding: 5px; font-weight: normal; } ';
        $stylesheet .= 'table.pesquisa tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.pesquisa tbody tr th { font-size: 11px; padding: 2px; } ';
        $stylesheet .= 'table.pesquisa tbody tr:nth-child(2) td { border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.pesquisa tbody td { font-size: 11px; padding: 5px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.pesquisa tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.resultado tr th, table.resultado tr td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= 'table.resultado thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.resultado thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.resultado tbody tr th { background-color: #dff0d8; } ';


        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));

        $sql = "SELECT a.nome
                FROM pesquisa a 
                WHERE a.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output($row->nome . '.pdf', 'D');
    }

}
