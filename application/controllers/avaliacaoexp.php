<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa')
        );
        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $data['empresa']);
        $this->db->where('tipo', 'A');
        $data['modelos'] = $this->db->get('avaliacaoexp_modelos')->result();
        $data['titulo'] = 'Avaliação Periódica de Desempenho';

        $this->load->view('avaliacaoexp', $data);
    }

    public function avaliado()
    {
        $sql = "SELECT id,
                       nome 
                FROM usuarios 
                WHERE id = {$this->uri->rsegment(3)}";
        $avaliado = $this->db->query($sql)->row();

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
        $data['titulo'] = 'Avaliação por Período de Experiência';
        $data['id_avaliado'] = '';
        $data['avaliado'] = '';
        if (count($avaliado) > 0) {
            $data['id_avaliado'] = $avaliado->id;
            $data['titulo'] = "Avaliações Colaborador - $avaliado->nome";
        }
        $data['empresa'] = $empresa;
        $data['id_avaliacao'] = '';
        $data['tipo'] = '2';
        $data['data_inicio'] = '';
        $data['data_termino'] = '';
        $modelos = $this->db->get_where('avaliacaoexp_modelos', array('tipo' => 'P'))->result();
        $data['id_modelo'] = array('' => 'selecione...');
        foreach ($modelos as $modelo) {
            $data['id_modelo'][$modelo->id] = $modelo->nome;
        }

        $this->db->select('nome, id');
        $this->db->where('empresa', $empresa);
        $this->db->order_by('nome', 'ASC');
        $avaliadores = $this->db->get('usuarios')->result();

        $data['colaboradores'] = array('' => 'selecione...');
        foreach ($avaliadores as $avaliador) {
            $data['colaboradores'][$avaliador->id] = $avaliador->nome;
        }

        $this->load->view('avaliacaoexp_avaliados', $data);
    }

    public function ajax_list($id)
    {
        if (empty($id)) {
            $id = $this->session->userdata('empresa');
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.modelo,
                       s.data_inicio,
                       s.data_termino
                FROM (SELECT a.id, 
                             a.nome, 
                             b.nome AS modelo,
                             a.data_inicio,
                             a.data_termino
                      FROM avaliacaoexp a
                      INNER JOIN avaliacaoexp_modelos b ON 
                                 b.id = a.id_modelo
                      WHERE b.id_usuario_EMPRESA = {$id}) s";
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
        foreach ($list as $avaliacaoexp) {
            $row = array();
            $row[] = $avaliacaoexp->nome;
            $row[] = $avaliacaoexp->modelo;
            $row[] = $avaliacaoexp->data_inicio ? date("d/m/Y", strtotime(str_replace('-', '/', $avaliacaoexp->data_inicio))) : '';
            $row[] = $avaliacaoexp->data_termino ? date("d/m/Y", strtotime(str_replace('-', '/', $avaliacaoexp->data_termino))) : '';

            $row[] = '
                      <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_avaliacao(' . "'" . $avaliacaoexp->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_avaliacao(' . "'" . $avaliacaoexp->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-success" href="' . site_url('avaliacaoexp_avaliados/gerenciar/' . $avaliacaoexp->id) . '" title="Avaliadores X Avaliados"><i class="glyphicon glyphicon-plus"></i> Avaliadores X Avaliados</a>
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

    public function ajax_avaliado($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $empresa = $this->session->userdata('empresa');
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.nome,
                       s.data_programada,
                       s.avaliador,
                       s.data_realizada,
                       s.id_avaliador
                FROM (SELECT b.id, 
                             c.nome, 
                             DATE_FORMAT(a.data_avaliacao,'%d/%m/%Y') AS data_programada,
                             a.id AS id_avaliador,
                             d.nome AS avaliador,
                             (SELECT MAX(x.data_avaliacao)
                              FROM avaliacaoexp_resultado x
                              WHERE x.id_avaliador = a.id) AS data_realizada
                      FROM avaliacaoexp_avaliadores a
                      INNER JOIN avaliacaoexp_avaliados b ON
                                 b.id = a.id_avaliado
                      LEFT JOIN avaliacaoexp_modelos c ON
                                c.id = b.id_modelo and
                                c.id_usuario_EMPRESA = {$empresa}
                      LEFT JOIN usuarios d ON
                                 d.id = a.id_avaliador
                      WHERE b.id_avaliado = {$id} AND 
                            c.tipo = 'P') s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.nome',
            's.data_programada',
            's.avaliador',
            's.data_realizada'
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
        foreach ($list as $avaliacaoExp) {
            $row = array();
            $row[] = $avaliacaoExp->nome;
            $row[] = $avaliacaoExp->data_programada;
            $row[] = $avaliacaoExp->avaliador;
            $row[] = '
                      <button class="btn btn-warning btn-sm" onclick="notificarAvaliador(' . $avaliacaoExp->id_avaliador . ')" title="Enviar e-mail de notificação"><i class="glyphicon glyphicon-envelope"></i></button>
                     ';
            $row[] = $avaliacaoExp->data_realizada ? date("d/m/Y", strtotime(str_replace('-', '/', $avaliacaoExp->data_realizada))) : '';
            $row[] = '
                      <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Gerenciar avaliadores" onclick="edit_avaliado(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Gerenciar</a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_avaliado(' . "'" . $avaliacaoExp->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-info" href="' . site_url('avaliacaoexp_avaliados/relatorio/' . $avaliacaoExp->id) . '" title="Relatório de avaliação"><i class="glyphicon glyphicon-list-alt"> </i> Relatório</a>
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
        $data = $this->db->get_where('avaliacaoexp', array('id' => $id))->row();

        $data->data_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_termino = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }

        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $status = $this->db->insert('avaliacaoexp', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }
        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $where = array('id' => $data['id']);
        unset($data['id']);

        $status = $this->db->update('avaliacaoexp', $data, $where);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('avaliacaoexp', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function enviarEmail()
    {
        $id_usuario = $this->input->post('id');
        $status = $this->input->post('status');
        $mes = $this->input->post('mes');
        $ano = $this->input->post('ano');
        $mensagem = $this->input->post('mensagem');

        $this->load->helper(array('date'));

        $email['titulo'] = 'E-mail de convocação para Exame Periódico';
        $email['remetente'] = $this->session->userdata('id');
        $email['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        $status = true;

        $this->db->select('a.id_usuario, b.nome, b.email, a.data_programada');
        $this->db->select("DATE_FORMAT(a.data_programada, '%d/%m/%Y') AS data_programada", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        if ($id_usuario) {
            $this->db->where('a.id_usuario', $id_usuario);
        }
        if ($status === '0') {
            $this->db->where('a.data_realizacao', null);
        } elseif ($status === '1') {
            $this->db->where('a.data_realizacao !=', null);
        }
        if ($mes) {
            $this->db->where("DATE_FORMAT(a.data_programada, '%m') =", $mes);
        }
        if ($ano) {
            $this->db->where("DATE_FORMAT(a.data_programada, '%Y') =", $ano);
        }
        $destinatarios = $this->db->get('usuarios_exame_periodico a')->result();

        $this->db->select("a.nome, a.email, IFNULL(b.email, a.email) AS email_empresa", false);
        $this->db->join('usuarios b', 'b.id = a.empresa', 'left');
        $this->db->where('a.id', $this->session->userdata('id'));
        $remetente = $this->db->get('usuarios a')->row();

        $this->load->library('email');

        foreach ($destinatarios as $destinatario) {
            if ($mensagem) {
                $email['mensagem'] = $mensagem;
            } else {
                $email['mensagem'] = "Caro colaborador, você está convocado para realizar exame médico periódico na data de: {$destinatario->data_programada}. Favor verificar com o Departamento de Gestão de Pessoas";
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
    }
}
