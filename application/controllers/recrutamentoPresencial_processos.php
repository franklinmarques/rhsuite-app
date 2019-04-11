<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RecrutamentoPresencial_processos extends MY_Controller
{

//    protected $tipo_usuario = array('empresa', 'selecionador');

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->online();
    }

    public function online()
    {
        $data = array(
            'id_usuario' => '',
            'id_candidato' => '',
            'requisicao' => '',
            'nome_candidato' => '',
            'nome_cargo' => '',
            'nome_requisicao' => '',
            'modelos' => array('' => 'selecione ...'),
        );

        if ($this->uri->rsegment(3)) {
            $this->db->select('IFNULL(d.id, e.id) AS id', false);
            $this->db->select('IFNULL(d.nome, e.nome) AS nome', false);
            $this->db->select('c.nome AS nome_cargo, a.id AS candidato, b.id AS id_requisicao, b.numero AS nome_requisicao', false);
            $this->db->join('requisicoes_pessoal b', 'b.id = a.id_requisicao');
            $this->db->join('empresa_cargos c', 'c.id = b.id_cargo', 'left');
            $this->db->join('recrutamento_usuarios d', 'd.id = a.id_usuario', 'left');
            $this->db->join('recrutamento_google e', 'e.id = a.id_usuario_banco', 'left');
            $this->db->where('a.id', $this->uri->rsegment(3));
            $row = $this->db->get('requisicoes_pessoal_candidatos a')->row();

            if ($row) {
                $data['id_usuario'] = $row->id;
                $data['id_candidato'] = $row->candidato;
                $data['requisicao'] = $row->id_requisicao;
                $data['nome_candidato'] = $row->nome;
                $data['nome_cargo'] = $row->nome_cargo;
                $data['nome_requisicao'] = $row->nome_requisicao;
            }
        }


        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $this->session->userdata('empresa'));
        $rows = $this->db->get('recrutamento_modelos')->result();
        foreach ($rows as $row) {
            $data['modelos'][$row->id] = $row->nome;
        }

        $this->load->view('recrutamentoPresencial_processos', $data);
    }

    public function presencial()
    {
        $data = array(
            'id_usuario' => '',
            'id_candidato' => '',
            'requisicao' => '',
            'nome_candidato' => '',
            'nome_cargo' => '',
            'nome_requisicao' => '',
            'modelos' => array('' => 'selecione ...'),
        );

        if ($this->uri->rsegment(3)) {
            $this->db->select('IFNULL(d.id, e.id) AS id', false);
            $this->db->select('IFNULL(d.nome, e.nome) AS nome', false);
            $this->db->select('c.nome AS nome_cargo, a.id AS candidato, b.id AS id_requisicao, b.numero AS nome_requisicao', false);
            $this->db->join('requisicoes_pessoal b', 'b.id = a.id_requisicao');
            $this->db->join('empresa_cargos c', 'c.id = b.id_cargo', 'left');
            $this->db->join('recrutamento_usuarios d', 'd.id = a.id_usuario', 'left');
            $this->db->join('recrutamento_google e', 'e.id = a.id_usuario_banco', 'left');
            $this->db->where('a.id', $this->uri->rsegment(3));
            $row = $this->db->get('requisicoes_pessoal_candidatos a')->row();

            if ($row) {
                $data['id_usuario'] = $row->id;
                $data['id_candidato'] = $row->candidato;
                $data['requisicao'] = $row->id_requisicao;
                $data['nome_candidato'] = $row->nome;
                $data['nome_cargo'] = $row->nome_cargo;
                $data['nome_requisicao'] = $row->nome_requisicao;
            }
        }

        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $this->session->userdata('empresa'));
        $rows = $this->db->get('recrutamento_modelos')->result();
        foreach ($rows as $row) {
            $data['modelos'][$row->id] = $row->nome;
        }

        $this->load->view('recrutamentoPresencial_processos2', $data);
    }

    public function ajax_list($id_usuario, $id_candidato = '', $tipo_teste = '')
    {
        $post = $this->input->post();

        $sql = "SELECT s.cargo,
                       s.id,
                       s.nome,
                       s.data_inicio, 
                       s.data_termino, 
                       (CASE s.tipo_modelo 
                        WHEN 'C' THEN ''
                        WHEN 'D' THEN s.digitacao_resposta 
                        WHEN 'E' THEN s.total_nota 
                        WHEN 'I' THEN s.total_nota 
                        ELSE (s.soma_resposta * 100 / s.soma_peso) END) AS aproveitamento,
                       s.tipo_modelo,
                       s.tipo_teste,
                       s.nota_aproveitamento,
                       s.digitacao_pergunta
                FROM (SELECT d.id, 
                             b.nome AS cargo,
                             d.tipo_teste,
                             d.nota_aproveitamento,
                             IFNULL(e.nome, d.nome) AS nome,
                             e.tipo AS tipo_modelo,
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
                              FROM requisicoes_pessoal_resultado h 
                              INNER JOIN recrutamento_alternativas i ON 
                                         i.id = h.id_alternativa 
                              WHERE h.id_teste = d.id) AS soma_resposta,
                             (SELECT ROUND(SUM(nota) / COUNT(id), 1) 
                               FROM requisicoes_pessoal_resultado 
                               WHERE id_teste = d.id) AS total_nota,
                             (CASE e.tipo WHEN 'D' THEN (SELECT j.pergunta FROM recrutamento_perguntas j WHERE j.id_modelo = e.id) ELSE null END) AS digitacao_pergunta,
                             (CASE e.tipo WHEN 'D' THEN (SELECT k.resposta FROM recrutamento_resultado k WHERE k.id_teste = d.id) ELSE null END) AS digitacao_resposta
                      FROM requisicoes_pessoal a 
                      LEFT JOIN empresa_cargos b ON
                                 b.id = a.id_cargo
                      LEFT JOIN requisicoes_pessoal_candidatos c ON
                                c.id_requisicao = a.id
                      LEFT JOIN recrutamento_candidatos c2 ON
                                c2.id = c.id_usuario AND c.id_usuario = {$id_usuario}
                      LEFT JOIN requisicoes_pessoal_testes d ON 
                                d.id_candidato = c.id
                      LEFT JOIN recrutamento_modelos e ON
                                e.id = d.id_modelo
                      WHERE 1";
        if ($id_candidato) {
            $sql .= " AND c.id = {$id_candidato} AND d.id IS NOT NULL";
        }
        if ($tipo_teste) {
            $sql .= " AND d.tipo_teste = '" . ($tipo_teste === '2' ? 'P' : 'O') . "'";
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
        foreach ($list as $requisicao) {
            $row = array();
            $row[] = $requisicao->cargo;
            $row[] = '<button class="btn btn-success btn-sm" title="Adicionar teste" onclick="add_teste()"><i class="glyphicon glyphicon-plus"></i> Adicionar teste</button>';
            $row[] = $requisicao->nome;
            $row[] = $requisicao->data_inicio;
            $row[] = $requisicao->data_termino;
            if ($requisicao->tipo_teste === 'P') {
                $row[] = str_replace('.', ',', $requisicao->nota_aproveitamento);
            } elseif ($requisicao->tipo_modelo === 'D') {
                similar_text($requisicao->digitacao_pergunta, $requisicao->aproveitamento, $percent);
                $row[] = $requisicao->aproveitamento !== null ? number_format($percent, 1, ',', '') : null;
            } elseif ($requisicao->tipo_modelo === 'C') {
                $row[] = null;
            } else {
                $row[] = $requisicao->aproveitamento !== null ? number_format($requisicao->aproveitamento, 1, ',', '') : null;
            }
            if ($requisicao->nome) {
                if ($requisicao->tipo_teste === 'P') {
                    $row[] = '
                              <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $requisicao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $requisicao->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                              <button class="btn btn-sm btn-info disabled" title="Relatório"><i class="glyphicon glyphicon-list-alt"></i> Relatório</button>
                             ';
                } else {
                    $row[] = '
                              <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $requisicao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $requisicao->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                              <a class="btn btn-sm btn-info" href="' . site_url('recrutamentoPresencial/relatorio/' . $requisicao->id) . '" title="Relatório"><i class="glyphicon glyphicon-list-alt"></i> Relatório</a>
                             ';
                }
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
        $data = $this->db->get_where('requisicoes_pessoal_testes', array('id' => $id))->row();
        $data->hora_inicio = date("H:i", strtotime($data->data_inicio));
        $data->hora_termino = date("H:i", strtotime($data->data_termino));
        $data->data_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_termino = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));
        $data->nota_aproveitamento = number_format($data->nota_aproveitamento, 1, ',', '');

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if ($data['tipo_teste'] != 'P' and empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum modelo selecionado')));
        }

        $data_inicio = strtotime(str_replace('/', '-', $data['data_inicio'] . ' ' . ($data['hora_inicio'] ?? '00:00') . ':00'));
        if ($data['tipo_teste'] != 'P') {
            $data_termino = strtotime(str_replace('/', '-', $data['data_termino'] . ' ' . ($data['hora_termino'] ?? '23:59') . ':59'));
        } else {
            $data_termino = null;
        }
        if ($data['tipo_teste'] == 'P' and empty($data_inicio)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início não encontrada')));
        } elseif ($data['tipo_teste'] != 'P' and !($data_inicio && $data_termino)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início ou término não encontrada')));
        }
        if ($data['tipo_teste'] != 'P' and $data_termino < $data_inicio) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início maior do que a Data de término')));
        }
        if ($data['tipo_teste'] != 'P' and ($data['minutos_duracao'] * 60) > ($data_termino - $data_inicio + 86400)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Tempo máximo maior que minutos entre as datas de início e término')));
        }
        $data['data_inicio'] = date("Y-m-d H:i:s", $data_inicio);
        $data['data_termino'] = $data_termino ? date("Y-m-d H:i:s", $data_termino) : null;
        if (isset($data['nota_aproveitamento'])) {
            if (strlen($data['nota_aproveitamento']) > 0) {
                $data['nota_aproveitamento'] = str_replace(',', '.', $data['nota_aproveitamento']);
            } else {
                $data['nota_aproveitamento'] = null;
            }
        }
        if (!empty($data['observacoes']) == false) {
            $data['observacoes'] = null;
        }

        $perguntas = isset($data['perguntas']) ? $data['perguntas'] : '';
        $alternativas = isset($data['alternativas']) ? $data['alternativas'] : '';
        $aleatorizacao = $perguntas && $alternativas ? 'T' : $perguntas . $alternativas;
        $data['aleatorizacao'] = strlen($aleatorizacao) > 0 ? $aleatorizacao : null;

        unset($data['hora_inicio'], $data['hora_termino'], $data['perguntas'], $data['alternativas']);

        $status = $this->db->insert('requisicoes_pessoal_testes', $data);
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
        if ($data['tipo_teste'] != 'P' and empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum modelo selecionado')));
        }

        $data_inicio = strtotime(str_replace('/', '-', $data['data_inicio'] . ' ' . ($data['hora_inicio'] ?? '00:00') . ':00'));
        if ($data['tipo_teste'] != 'P') {
            $data_termino = strtotime(str_replace('/', '-', $data['data_termino'] . ' ' . ($data['hora_termino'] ?? '23:59') . ':59'));
        } else {
            $data_termino = null;
        }
        if ($data['tipo_teste'] == 'P' and empty($data_inicio)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início ou término não encontrada')));
        } elseif ($data['tipo_teste'] != 'P' and !($data_inicio && $data_termino)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início ou término não encontrada')));
        }
        if ($data['tipo_teste'] != 'P' and $data_termino < $data_inicio) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Data de início maior do que a Data de término')));
        }
        if ($data['tipo_teste'] != 'P' and ($data['minutos_duracao'] * 60) > ($data_termino - $data_inicio + 86400)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Tempo máximo maior que minutos entre as datas de início e término')));
        }
        $data['data_inicio'] = date("Y-m-d H:i:s", $data_inicio);
        $data['data_termino'] = $data_termino ? date("Y-m-d H:i:s", $data_termino) : null;
        if (isset($data['nota_aproveitamento'])) {
            if (strlen($data['nota_aproveitamento']) > 0) {
                $data['nota_aproveitamento'] = str_replace(',', '.', $data['nota_aproveitamento']);
            } else {
                $data['nota_aproveitamento'] = null;
            }
        }
        if (!empty($data['observacoes']) == false) {
            $data['observacoes'] = null;
        }

        $perguntas = isset($data['perguntas']) ? $data['perguntas'] : '';
        $alternativas = isset($data['alternativas']) ? $data['alternativas'] : '';
        $aleatorizacao = $perguntas && $alternativas ? 'T' : $perguntas . $alternativas;
        $data['aleatorizacao'] = strlen($aleatorizacao) > 0 ? $aleatorizacao : null;

        $where = array('id' => $data['id']);
        unset($data['id'], $data['hora_inicio'], $data['hora_termino'], $data['perguntas'], $data['alternativas']);

        $status = $this->db->update('requisicoes_pessoal_testes', $data, $where);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('requisicoes_pessoal_testes', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

}
