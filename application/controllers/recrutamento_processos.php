<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_processos extends MY_Controller
{

//    protected $tipo_usuario = array('empresa', 'selecionador');

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $data = array(
            'id_usuario' => '',
            'id_candidato' => '',
            'recrutamento' => '',
            'nome_candidato' => '',
            'nome_cargo' => '',
            'nome_recrutamento' => '',
            'modelos' => array('' => 'selecione ...'),
        );

        if ($this->uri->rsegment(3)) {
            $this->db->select('a.id, a.nome, c.cargo AS nome_cargo, b.id AS candidato');
            $this->db->select('d.id AS id_recrutamento, d.nome AS nome_recrutamento');
            $this->db->join('recrutamento_candidatos b', 'b.id_usuario = a.id');
            $this->db->join('recrutamento_cargos c', 'c.id = b.id_cargo');
            $this->db->join('recrutamento d', 'd.id = c.id_recrutamento');
            $this->db->where('b.id', $this->uri->rsegment(3));
            $row = $this->db->get('recrutamento_usuarios a')->row();

            if ($row) {
                $data['id_usuario'] = $row->id;
                $data['id_candidato'] = $row->candidato;
                $data['recrutamento'] = $row->id_recrutamento;
                $data['nome_candidato'] = $row->nome;
                $data['nome_cargo'] = $row->nome_cargo;
                $data['nome_recrutamento'] = $row->nome_recrutamento;
            }
        }

        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $this->session->userdata('empresa'));
        $rows = $this->db->get('recrutamento_modelos')->result();
        foreach ($rows as $row) {
            $data['modelos'][$row->id] = $row->nome;
        }

        $this->load->view('recrutamento_processos', $data);
    }

    public function ajax_list($id_usuario, $id_candidato = '')
    {
        $post = $this->input->post();

        $sql = "SELECT s.cargo,
                       s.id,
                       s.nome,
                       s.data_inicio, 
                       s.data_termino, 
                       (CASE s.tipo 
                        WHEN 'C' THEN ''
                        WHEN 'D' THEN s.digitacao_resposta 
                        WHEN 'E' THEN s.total_nota 
                        WHEN 'I' THEN s.total_nota 
                        ELSE (s.soma_resposta * 100 / s.soma_peso) END) AS aproveitamento,
                       s.tipo,
                       s.digitacao_pergunta
                FROM (SELECT d.id, 
                             b.cargo,
                             e.nome,
                             e.tipo,
                             DATE_FORMAT(d.data_inicio, '%d/%m/%Y') AS data_inicio, 
                             DATE_FORMAT(d.data_termino, '%d/%m/%Y') AS data_termino,
                             (SELECT SUM(x.peso)
                              FROM (SELECT g.id_modelo, 
                                           MAX(f.peso) AS peso
                                    FROM recrutamento_alternativas f
                                    INNER JOIN recrutamento_perguntas g ON
                                               g.id = f.id_pergunta
                                    GROUP BY g.id) x 
                              WHERE x.id_modelo = e.id) AS soma_peso,
                             (SELECT SUM(i.peso)
                              FROM recrutamento_resultado h 
                              INNER JOIN recrutamento_alternativas i ON 
                                         i.id = h.id_alternativa 
                              WHERE h.id_teste = d.id) AS soma_resposta,
                             (SELECT ROUND(SUM(nota) / COUNT(id), 1) 
                               FROM recrutamento_resultado 
                               WHERE id_teste = d.id) AS total_nota,
                             (CASE e.tipo WHEN 'D' THEN (SELECT j.pergunta FROM recrutamento_perguntas j WHERE j.id_modelo = e.id) ELSE null END) AS digitacao_pergunta,
                             (CASE e.tipo WHEN 'D' THEN (SELECT k.resposta FROM recrutamento_resultado k WHERE k.id_teste = d.id) ELSE null END) AS digitacao_resposta
                      FROM `recrutamento` a 
                      LEFT JOIN recrutamento_cargos b ON
                                 b.id_recrutamento = a.id
                      LEFT JOIN recrutamento_candidatos c ON
                                c.id_cargo = b.id AND c.id_usuario = {$id_usuario}
                      LEFT JOIN recrutamento_testes d ON 
                                d.id_candidato = c.id
                      LEFT JOIN recrutamento_modelos e ON
                                e.id = d.id_modelo";
        if ($id_candidato) {
            $sql .= " WHERE c.id = {$id_candidato} AND d.id IS NOT NULL";
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.cargo', 's.id', 's.nome', 's.data_inicio', 's.data_termino', 's.aproveitamento');
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
        foreach ($list as $recrutamento) {
            $row = array();
            $row[] = $recrutamento->cargo;
            $row[] = '<button class="btn btn-success btn-sm" title="Adicionar teste" onclick="add_teste()"><i class="glyphicon glyphicon-plus"></i> Adicionar teste</button>';
            $row[] = $recrutamento->nome;
            $row[] = $recrutamento->data_inicio;
            $row[] = $recrutamento->data_termino;
            if ($recrutamento->tipo === 'D') {
                similar_text($recrutamento->digitacao_pergunta, $recrutamento->aproveitamento, $percent);
                $row[] = $recrutamento->aproveitamento !== null ? number_format($percent, 1, ',', '') : null;
            } elseif ($recrutamento->tipo === 'C') {
                $row[] = null;
            } else {
                $row[] = $recrutamento->aproveitamento !== null ? number_format($recrutamento->aproveitamento, 1, ',', '') : null;
            }
            if ($recrutamento->nome) {
                $row[] = '
                          <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                          <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                          <a class="btn btn-sm btn-info" href="' . site_url('recrutamento/relatorio/' . $recrutamento->id) . '" title="Relatório"><i class="glyphicon glyphicon-list-alt"></i> Relatório</a>
                         ';
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-primary disabled"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button class="btn btn-sm btn-danger disabled"><i class="glyphicon glyphicon-trash"></i></button>
                          <button class="btn btn-sm btn-info disabled"><i class="glyphicon glyphicon-list-alt"></i> Relatório</button>
                         ';
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

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('recrutamento_testes', array('id' => $id))->row();
        $data->hora_inicio = date("H:i", strtotime($data->data_inicio));
        $data->hora_termino = date("H:i", strtotime($data->data_termino));
        $data->data_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_termino = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));

        $this->db->select('aleatorizacao');
        $this->db->where('id', $data->id_modelo);
        $data->aleatorizacao_ok = $this->db->get_where('recrutamento_modelos')->row()->aleatorizacao;

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum modelo selecionado')));
        }

        $data_inicio = strtotime(str_replace('/', '-', $data['data_inicio'] . ' ' . $data['hora_inicio'] . ':00'));
        $data_termino = strtotime(str_replace('/', '-', $data['data_termino'] . ' ' . $data['hora_termino'] . ':59'));
        if (!($data_inicio && $data_termino)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início ou término não encontrada')));
        }
        if ($data_termino < $data_inicio) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início maior do que a Data de término')));
        }
        if (($data['minutos_duracao'] * 60) > ($data_termino - $data_inicio + 86400)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Tempo máximo maior que minutos entre as datas de início e término')));
        }
        $data['data_inicio'] = date("Y-m-d H:i:s", $data_inicio);
        $data['data_termino'] = date("Y-m-d H:i:s", $data_termino);

        $perguntas = isset($data['perguntas']) ? $data['perguntas'] : '';
        $alternativas = isset($data['alternativas']) ? $data['alternativas'] : '';
        $aleatorizacao = $perguntas && $alternativas ? 'T' : $perguntas . $alternativas;
        $data['aleatorizacao'] = strlen($aleatorizacao) > 0 ? $aleatorizacao : null;

        unset($data['hora_inicio'], $data['hora_termino'], $data['perguntas'], $data['alternativas']);

        $status = $this->db->insert('recrutamento_testes', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addCargo()
    {
        $data = array(
            'id_recrutamento' => $this->input->post('id_recrutamento'),
            'cargo' => $this->input->post('cargo')
        );
        if (empty($data['cargo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O cargo não deve ficar sem nome')));
        }

        $this->db->trans_begin();
        $this->db->insert('recrutamento_cargos', $data);

        $data2 = array(
            'id_cargo' => $this->db->insert_id(),
            'id_usuario' => $this->input->post('id_usuario')
        );
        $this->db->insert('recrutamento_candidatos', $data2);

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
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum modelo selecionado')));
        }

        $data_inicio = strtotime(str_replace('/', '-', $data['data_inicio'] . ' ' . $data['hora_inicio'] . ':00'));
        $data_termino = strtotime(str_replace('/', '-', $data['data_termino'] . ' ' . $data['hora_termino'] . ':59'));
        if (!($data_inicio && $data_termino)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início ou término não encontrada')));
        }
        if ($data_termino < $data_inicio) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início maior do que a Data de término')));
        }
        if (($data['minutos_duracao'] * 60) > ($data_termino - $data_inicio + 86400)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Tempo máximo maior que minutos entre as datas de início e término')));
        }
        $data['data_inicio'] = date("Y-m-d H:i:s", $data_inicio);
        $data['data_termino'] = date("Y-m-d H:i:s", $data_termino);

        $perguntas = isset($data['perguntas']) ? $data['perguntas'] : '';
        $alternativas = isset($data['alternativas']) ? $data['alternativas'] : '';
        $aleatorizacao = $perguntas && $alternativas ? 'T' : $perguntas . $alternativas;
        $data['aleatorizacao'] = strlen($aleatorizacao) > 0 ? $aleatorizacao : null;

        $where = array('id' => $data['id']);
        unset($data['id'], $data['hora_inicio'], $data['hora_termino'], $data['perguntas'], $data['alternativas']);

        $status = $this->db->update('recrutamento_testes', $data, $where);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('recrutamento_testes', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

}
