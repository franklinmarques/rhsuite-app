<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RecrutamentoPresencial_cargos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Pesquisa_model', 'pesquisa');
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $data = array(
            'requisicao' => '',
            'nome_requisicao' => '',
            'numero_vagas' => '',
            'numero_contratados' => '',
            'data_abertura' => '',
            'nome_requisitante' => '',
            'previsao_inicio' => '',
            'cargo_funcao' => '',
            'data_aprovacao' => '',
            'aprovado_por' => '',
            'tempo_aprovacao' => '',
            'dias_restantes' => '',
            'spa' => '',
            'local_trabalho' => '',
        );

        if ($this->uri->rsegment(3)) {
            $this->db->select('a.id, a.numero, a.spa, a.numero_vagas, a.local_trabalho, a.aprovado_por');
            $this->db->select("CASE a.tipo_vaga WHEN 'I' THEN 'Interna' WHEN 'E' THEN 'Externa' ELSE 'Indefinido' END AS tipo_vaga", false);
            $this->db->select("DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura", false);
            $this->db->select("DATE_FORMAT(a.previsao_inicio, '%d/%m/%Y') AS previsao_inicio", false);
            $this->db->select("DATE_FORMAT(a.data_aprovacao, '%d/%m/%Y') AS data_aprovacao", false);
            $this->db->select("DATEDIFF(a.data_aprovacao, a.data_abertura) AS tempo_aprovacao", false);
            $this->db->select("DATEDIFF(a.previsao_inicio, NOW()) AS dias_restantes", false);
            $this->db->select("IFNULL(CONCAT(b.nome, '/', c.nome), a.cargo_funcao_alternativo) AS cargo_funcao", false);
            $this->db->select("CONCAT(e.nome, '/', f.nome) AS area_setor", false);
            $this->db->select("CONCAT(d.nome, '/', e.nome, ' - ', f.nome) AS estrutura", false);
            $this->db->select("IF(a.tipo_vaga = 'I', g.nome, a.requisitante_externo)  AS requisitante", false);
            $this->db->join('empresa_cargos b', 'b.id = a.id_cargo', 'left');
            $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao', 'left');
            $this->db->join('empresa_departamentos d', 'd.id = a.id_depto', 'left');
            $this->db->join('empresa_areas e', 'e.id = a.id_area', 'left');
            $this->db->join('empresa_setores f', 'f.id = a.id_setor', 'left');
            $this->db->join('usuarios g', 'g.id = a.requisitante_interno', 'left');
            $this->db->where('a.id', $this->uri->rsegment(3));
            $row = $this->db->get('requisicoes_pessoal a')->row();

            if ($row) {
                $this->db->where('id_requisicao', $row->id);
                $this->db->where('aprovado', '1');
                $data['numero_contratados'] = $this->db->get('requisicoes_pessoal_candidatos')->num_rows();

                $data['requisicao'] = $row->id;
                $data['nome_requisicao'] = $row->numero;
                $data['numero_vagas'] = $row->numero_vagas;
                $data['data_abertura'] = $row->data_abertura;
                $data['previsao_inicio'] = $row->previsao_inicio;
                $data['cargo_funcao'] = $row->cargo_funcao;
                $data['data_aprovacao'] = $row->data_aprovacao;
                $data['aprovado_por'] = $row->aprovado_por;
                $data['tempo_aprovacao'] = $row->tempo_aprovacao;
                $data['dias_restantes'] = $row->dias_restantes;
                $data['spa'] = $row->spa;
                $data['local_trabalho'] = $row->local_trabalho;
                if ($row->tipo_vaga == 'Externa') {
                    $data['nome_requisitante'] = $row->requisitante;
                } elseif ($this->session->userdata('tipo') == 'selecionador') {
                    $data['nome_requisitante'] = $row->estrutura . ' - ' . $row->requisitante;
                } else {
                    $data['nome_requisitante'] = $row->area_setor . ' - ' . $row->requisitante;
                }
            }
        }

        $this->db->select('id, nome');
        $this->db->order_by('id', 'asc');
        $escolaridade = $this->db->get('escolaridade')->result();
        $data['escolaridade'] = ['' => 'selecione...'] + array_column($escolaridade, 'nome', 'id');

        /*if ($data['requisicao']) {
            $this->load->view('recrutamentoPresencial_cargo', $data);
        } else {
            $this->load->view('recrutamentoPresencial_cargos', $data);
        }*/
        $this->load->view('recrutamentoPresencial_cargo', $data);
    }

    public function ajax_list($id = '')
    {
        $empresa = $this->session->userdata('empresa');

        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.cargo_funcao, 
                       s.id_candidato,
                       s.candidato,
                       s.id_usuario,
                       (CASE s.tipo_modelo 
                        WHEN 'C' THEN ''
                        WHEN 'D' THEN s.digitacao_resposta 
                        WHEN 'E' THEN s.total_nota 
                        WHEN 'I' THEN s.total_nota 
                        ELSE (s.soma_resposta * 100 / s.soma_peso) END) AS aproveitamento1,
                       s.aproveitamento2,
                       s.aprovado
                FROM (SELECT a.id, 
                             CONCAT(b.nome, '/', c.nome) AS cargo_funcao, 
                             d.id AS id_candidato,
                             d.id_usuario,
                             e.nome AS candidato,
                             d.aprovado,
                             g.tipo AS tipo_modelo,
                             (SELECT SUM(x.peso)
                              FROM (SELECT g1.id_modelo, 
                                           MAX(f1.peso) AS peso
                                    FROM recrutamento_alternativas f1
                                    INNER JOIN recrutamento_perguntas g1 ON
                                               g1.id = f1.id_pergunta
                                    GROUP BY g1.id) x 
                              WHERE x.id_modelo = g.id) AS soma_peso,
                             (SELECT SUM(i.peso)
                              FROM requisicoes_pessoal_resultado h 
                              INNER JOIN recrutamento_alternativas i ON 
                                         i.id = h.id_alternativa 
                              WHERE h.id_teste = f.id) AS soma_resposta,
                             (SELECT ROUND(SUM(nota) / COUNT(id), 1) 
                               FROM requisicoes_pessoal_resultado 
                               WHERE id_teste = f.id) AS total_nota,
                             (CASE g.tipo WHEN 'D' THEN (SELECT j.pergunta FROM recrutamento_perguntas j WHERE j.id_modelo = g.id) ELSE null END) AS digitacao_pergunta,
                             (CASE g.tipo WHEN 'D' THEN (SELECT k.resposta FROM requisicoes_pessoal_resultado k WHERE k.id_teste = f.id) ELSE null END) AS digitacao_resposta,
                             AVG(f.nota_aproveitamento) AS aproveitamento2
                      FROM requisicoes_pessoal a
                      INNER JOIN empresa_cargos b
                                 ON b.id = a.id_cargo
                      INNER JOIN empresa_funcoes c
                                 ON c.id = a.id_funcao
                      LEFT JOIN requisicoes_pessoal_candidatos d
                                ON d.id_requisicao = a.id
                      LEFT JOIN recrutamento_usuarios e
                                ON e.id = d.id_usuario
                      LEFT JOIN requisicoes_pessoal_testes f
                                ON f.id_candidato = d.id
                      LEFT JOIN recrutamento_modelos g
                                ON g.id = f.id_modelo";
        if ($id) {
            $sql .= " WHERE a.id = {$id}";
        }
        $sql .= ' GROUP BY a.id, d.id)s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.cargo_funcao', 's.candidato');
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
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        /*foreach ($list as $li => $query2) {
            $sql2 = "SELECT (CASE s.tipo 
                                  WHEN 'C' THEN ''
                                  WHEN 'D' THEN s.digitacao_resposta 
                                  WHEN 'E' THEN s.total_nota 
                                  WHEN 'I' THEN s.total_nota 
                                  ELSE (s.soma_resposta * 100 / s.soma_peso) END) AS aproveitamento,
                            s.tipo,
                            s.digitacao_pergunta 
                     FROM (SELECT e.tipo,
                                  (SELECT SUM(x.peso) 
                                   FROM (SELECT g.id_modelo, 
                                                MAX(f.peso) AS peso
                                         FROM recrutamento_alternativas f
                                         INNER JOIN recrutamento_perguntas g
                                                    ON g.id = f.id_pergunta
                                         GROUP BY g.id) x 
                                   WHERE x.id_modelo = e.id) AS soma_peso,
                                   (SELECT SUM(i.peso) 
                                    FROM recrutamento_resultado h 
                                    INNER JOIN recrutamento_alternativas i
                                               ON i.id = h.id_alternativa 
                                    WHERE h.id_teste = d.id) AS soma_resposta,
                                   (SELECT ROUND(SUM(nota) / COUNT(id), 1)
                                    FROM recrutamento_resultado 
                                    WHERE id_teste = d.id) AS total_nota,
                                   (CASE e.tipo WHEN 'D' THEN (SELECT j.pergunta FROM recrutamento_perguntas j WHERE j.id_modelo = e.id) ELSE null END) AS digitacao_pergunta,
                                   (CASE e.tipo WHEN 'D' THEN (SELECT k.resposta FROM recrutamento_resultado k WHERE k.id_teste = d.id) ELSE null END) AS digitacao_resposta
                           FROM requisicoes_pessoal a 
                           LEFT JOIN recrutamento_cargos b ON
                                 b.id_recrutamento = a.id
                           LEFT JOIN recrutamento_candidatos c ON
                                c.id_cargo = b.id AND c.id_usuario = {$query2->id_usuario}
                      LEFT JOIN recrutamento_testes d ON 
                                d.id_candidato = c.id
                      LEFT JOIN recrutamento_modelos e ON
                                e.id = d.id_modelo 
                      WHERE c.id = {$query2->id_candidato} AND d.id IS NOT NULL) s";
            $rows2 = $this->db->query($sql2)->result();
            $queryResult = array();
            foreach ($rows2 as $row2) {
                if ($row2->tipo === 'D') {
                    similar_text($row2->digitacao_pergunta, $row2->aproveitamento, $percent);
                    $queryResult[] = $row2->aproveitamento !== null ? number_format($percent, 1, ',', '') : null;
                } elseif($row2->tipo !== 'C') {
                    $queryResult[] = $row2->aproveitamento !== null ? number_format($row2->aproveitamento, 1, ',', '') : null;
                }
            }
            $query2->aproveitamento2 = array_sum($queryResult) / max(count(array_filter($queryResult)), 1);
        }*/

        $data = array();
        foreach ($list as $requisicao) {
            $row = array();
            $row[] = $requisicao->id;
            $row[] = $requisicao->cargo_funcao;
            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Adicionar candidato" onclick="add_candidato(' . "'" . $requisicao->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Candidato</a>
                     ';
            $row[] = $requisicao->candidato;
            if ($requisicao->candidato) {
                if ($requisicao->aprovado === 1) {
                    $row[] = '
                              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir candidato" onclick="delete_candidato(' . "'" . $requisicao->id_candidato . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                              <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Desaprovar candidato" onclick="desaprovar_candidato(' . "'" . $requisicao->id_candidato . "'" . ')"><i class="glyphicon glyphicon-ok"></i> Aprovado</a>
                             ';
                } elseif ($requisicao->aprovado === 0) {
                    $row[] = '
                              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir candidato" onclick="delete_candidato(' . "'" . $requisicao->id_candidato . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                              <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Desaprovar candidato" onclick="desaprovar_candidato(' . "'" . $requisicao->id_candidato . "'" . ')"><i class="glyphicon glyphicon-ok"></i> Reprovado</a>
                             ';
                } else {
                    $row[] = '
                              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir candidato" onclick="delete_candidato(' . "'" . $requisicao->id_candidato . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                              <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Aprovar candidato" onclick="aprovar_candidato(' . "'" . $requisicao->id_candidato . "'" . ')"><i class="glyphicon glyphicon-ok"></i> Aprovar</a>
                             ';;
                }
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-danger disabled"><i class="glyphicon glyphicon-trash"></i></button>
                          <button class="btn btn-sm btn-success disabled"><i class="glyphicon glyphicon-ok"></i> Aprovar</button>
                         ';
            }
            $row[] = number_format($requisicao->aproveitamento1, 1, ',', '');
            if ($requisicao->candidato) {
                $row[] = '
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamentoPresencial_processos/online/' . $requisicao->id_candidato) . '" title="Ver processo"><i class="glyphicon glyphicon-list-alt"></i> Testes</a>
                         ';
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-primary disabled"><i class="glyphicon glyphicon-list-alt"></i> Testes</button>
                         ';
            }

            $row[] = number_format($requisicao->aproveitamento2, 1, ',', '');
            if ($requisicao->candidato) {
                $row[] = '
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamentoPresencial_processos/presencial/' . $requisicao->id_candidato) . '" title="Ver processo"><i class="glyphicon glyphicon-list-alt"></i> Testes</a>
                         ';
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-primary disabled"><i class="glyphicon glyphicon-list-alt"></i> Testes</button>
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

    public function ajax_listCandidatos($id = '')
    {
        $empresa = $this->session->userdata('empresa');

        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.candidato,
                       s.telefone,
                       s.email,
                       s.deficiencia,
                       s.fonte_contratacao,
                       s.status,
                       s.data_selecao,
                       s.resultado_selecao,
                       s.data_requisitante,
                       s.resultado_requisitante,
                       s.antecedentes_criminais,
                       s.restricoes_financeiras,
                       s.data_exame_admissional,
                       s.resultado_exame_admissional,
                       s.nome_status,
                       s.data_admissao,
                       s.id_usuario,
                       IF(s.tipo_modelo NOT IN ('C','D', 'E', 'I'), (s.soma_resposta * 100 / s.soma_peso), s.aproveitamento1) AS aproveitamento1,
                       s.cargo_funcao, 
                       s.aproveitamento2,
                       s.id_candidato,
                       s.observacoes,
                       CASE WHEN s.aproveitamento1 BETWEEN 0 AND 20 THEN 'Insuficiente'
                            WHEN s.aproveitamento1 BETWEEN 21 AND 40 THEN 'Fraco'
                            WHEN s.aproveitamento1 BETWEEN 41 AND 60 THEN 'Médio'
                            WHEN s.aproveitamento1 BETWEEN 61 AND 80 THEN 'Bom'
                            WHEN s.aproveitamento1 BETWEEN 81 AND 100 THEN 'Ótimo'
                            END AS aproveitamento1b,
                       CASE WHEN s.aproveitamento2 BETWEEN 0 AND 20 THEN 'Insuficiente'
                            WHEN s.aproveitamento2 BETWEEN 21 AND 40 THEN 'Fraco'
                            WHEN s.aproveitamento2 BETWEEN 41 AND 60 THEN 'Médio'
                            WHEN s.aproveitamento2 BETWEEN 61 AND 80 THEN 'Bom'
                            WHEN s.aproveitamento2 BETWEEN 81 AND 100 THEN 'Ótimo'
                            END AS aproveitamento2b,
                       s.aprovado
                FROM (SELECT a.id, 
                             CONCAT(b.nome, '/', c.nome) AS cargo_funcao, 
                             d.id AS id_candidato,
                             d.id_usuario,
                             d.observacoes,
                             IF(CHAR_LENGTH(d.observacoes) > 0, 'Obs', '') AS observacoes2,
                             IFNULL(e.nome, i.nome) AS candidato,
                             IFNULL(e.telefone, i.telefone) AS telefone,
                             IFNULL(e.email, i.email) AS email,
                             IFNULL(h.tipo, i.deficiencia) AS deficiencia,
                             IFNULL(e.fonte_contratacao, IFNULL(i.fonte_contratacao, 'Nenhuma')) AS fonte_contratacao,
                             DATE_FORMAT(d.data_selecao, '%d/%m/%Y %H:%i') AS data_selecao,
                             CASE d.resultado_selecao
                                  WHEN 'A' THEN 'Selecionado'
                                  WHEN 'D' THEN 'Desistiu'
                                  WHEN 'N' THEN 'Não compareceu'
                                  WHEN 'X' THEN 'Aprovado'
                                  WHEN 'R' THEN 'Reprovado'
                                  WHEN 'S' THEN 'Stand by'
                                  END AS resultado_selecao,
                             DATE_FORMAT(d.data_requisitante, '%d/%m/%Y %H:%i') AS data_requisitante,
                             CASE d.resultado_requisitante
                                  WHEN 'A' THEN 'Selecionado'
                                  WHEN 'C' THEN 'Aprovado'
                                  WHEN 'D' THEN 'Desistiu'
                                  WHEN 'N' THEN 'Não compareceu'
                                  WHEN 'X' THEN 'Aprovado'
                                  WHEN 'R' THEN 'Reprovado'
                                  WHEN 'S' THEN 'Stand by'
                                  END AS resultado_requisitante,
                             CASE d.antecedentes_criminais
                                  WHEN 1 THEN 'Antecedentes'
                                  WHEN 0 THEN 'Nada consta'
                                  END AS antecedentes_criminais,
                             CASE d.restricoes_financeiras
                                  WHEN 1 THEN 'Com restrições'
                                  WHEN 0 THEN 'Sem restrições'
                                  END AS restricoes_financeiras,
                             DATE_FORMAT(d.data_exame_admissional, '%d/%m/%Y') AS data_exame_admissional,
                             CASE d.resultado_exame_admissional
                                  WHEN 1 THEN 'Apto'
                                  WHEN 0 THEN 'Não apto'
                                  END AS resultado_exame_admissional,
                             DATE_FORMAT(d.data_admissao, '%d/%m/%Y') AS data_admissao,
                             d.status,
                             CASE d.status
                                  WHEN 'A' THEN 'Agendado'
                                  WHEN 'P' THEN 'Em processo'
                                  WHEN 'F' THEN 'Fora do perfil'
                                  WHEN 'N' THEN 'Não atende ou recado'
                                  WHEN 'S' THEN 'Sem interesse'
                                  WHEN 'I' THEN 'Telefone errado'
                                  END AS nome_status,
                             d.aprovado,
                             g.tipo AS tipo_modelo,
                             (SELECT SUM(x.peso)
                              FROM (SELECT g1.id_modelo, 
                                           MAX(f1.peso) AS peso
                                    FROM recrutamento_alternativas f1
                                    INNER JOIN recrutamento_perguntas g1 ON
                                               g1.id = f1.id_pergunta
                                    GROUP BY g1.id) x 
                              WHERE x.id_modelo = g.id) AS soma_peso,
                             (SELECT SUM(i.peso)
                              FROM requisicoes_pessoal_resultado h 
                              INNER JOIN recrutamento_alternativas i ON 
                                         i.id = h.id_alternativa 
                              WHERE h.id_teste = f.id) AS soma_resposta,
                             CASE g.tipo 
                                  WHEN g.tipo = 'C' THEN ''
                                  WHEN g.tipo = 'D' THEN (SELECT k.resposta FROM requisicoes_pessoal_resultado k WHERE k.id_teste = f.id)
                                  WHEN g.tipo IN ('E', 'I') THEN (SELECT ROUND(SUM(nota) / COUNT(id), 1) 
                                                                  FROM requisicoes_pessoal_resultado 
                                                                  WHERE id_teste = f.id)
                                  END AS aproveitamento1,
                             AVG(f.nota_aproveitamento) AS aproveitamento2
                      FROM requisicoes_pessoal a
                      INNER JOIN empresa_cargos b
                                 ON b.id = a.id_cargo
                      INNER JOIN empresa_funcoes c
                                 ON c.id = a.id_funcao
                      INNER JOIN requisicoes_pessoal_candidatos d
                                ON d.id_requisicao = a.id
                      LEFT JOIN recrutamento_usuarios e
                                ON e.id = d.id_usuario
                      LEFT JOIN requisicoes_pessoal_testes f
                                ON f.id_candidato = d.id
                      LEFT JOIN recrutamento_modelos g
                                ON g.id = f.id_modelo
                      LEFT JOIN deficiencias h 
                                ON h.id = e.deficiencia
                      LEFT JOIN recrutamento_google i
                                ON i.id = d.id_usuario_banco
                      WHERE (d.status != 'E' OR d.status IS NULL)";
        if ($id) {
            $sql .= " AND a.id = {$id}";
        }
        if ($post['status']) {
            $sql .= ' AND d.status IN (' . preg_replace('/\w/', '\'$0\'', $post['status']) . ')';
        }
        $sql .= ' GROUP BY a.id, d.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.cargo_funcao', 's.candidato');
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
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $requisicao) {
            $row = array();
            if ($requisicao->candidato) {
                $row[] = '
                          <button class="btn btn-sm btn-info" href="javascript:void(0)" onclick="documentos(' . $requisicao->id_candidato . ');" title="Gerenciar documentos"><i class="glyphicon glyphicon-file"></i></button>
                          <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir candidato" onclick="delete_candidato(' . "'" . $requisicao->id_candidato . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                          <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar observações" onclick="edit_observacoes(\'' . $requisicao->candidato . '\',' . $requisicao->id_candidato . ', \'' . $requisicao->observacoes . '\')">Obs.</a>
                          ';
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-info disabled" title="Gerenciar documentos"><i class="glyphicon glyphicon-file"></i></button>
                          <button class="btn btn-sm btn-danger disabled" title="Excluir candidato"><i class="glyphicon glyphicon-trash"></i></button>
                          <button class="btn btn-sm btn-info disabled" title="Editar observações">Obs.</button>
                         ';
            }
            if ($requisicao->id_usuario) {
                $row[] = '<a href="' . site_url('recrutamento_candidatos/perfil/' . $requisicao->id_usuario) . '">' . $requisicao->candidato . '</a>';
            } else {
                $row[] = $requisicao->candidato;
            }
            $row[] = $requisicao->telefone;
            $row[] = '<a href="mailto:' . $requisicao->email . '">' . $requisicao->email . '</a>';
            $row[] = $requisicao->deficiencia;
            $row[] = $requisicao->fonte_contratacao;
            $row[] = $requisicao->nome_status;
            $row[] = $requisicao->data_selecao;
            $row[] = $requisicao->resultado_selecao;
            $row[] = $requisicao->data_requisitante;
            $row[] = $requisicao->resultado_requisitante;
            $row[] = $requisicao->antecedentes_criminais;
            $row[] = $requisicao->restricoes_financeiras;
            $row[] = $requisicao->data_exame_admissional;
            $row[] = $requisicao->resultado_exame_admissional;
            if ($requisicao->candidato) {
                if ($requisicao->aprovado) {
                    $row[] = '
                              <button class="btn btn-sm btn-success disabled" onclick="desaprovar_candidato(' . $requisicao->id_candidato . ')" title="Candidato contratado"><i class="glyphicon glyphicon-ok"></i></button>
                             ';
                } else {
//                    if (in_array($requisicao->resultado_requisitante, ['Selecionado', 'Aprovado'])) {
                    if ($requisicao->resultado_exame_admissional === 'Não apto' or $requisicao->antecedentes_criminais === 'Antecedentes') {
                        $row[] = '
                                      <button class="btn btn-sm btn-warning" onclick="forcar_aprovacao_candidato(' . $requisicao->id_candidato . ')" title="Contratar candidato"><i class="glyphicon glyphicon-save"></i></button>
                                     ';
                    } else {
                        $row[] = '
                                      <button class="btn btn-sm btn-warning" onclick="aprovar_candidato(' . $requisicao->id_candidato . ')" title="Contratar candidato"><i class="glyphicon glyphicon-save"></i></button>
                                     ';
                    }
//                    } else {
//                        $row[] = '
//                                  <button class="btn btn-sm btn-warning disabled" title="Contratar candidato"><i class="glyphicon glyphicon-save"></i></button>
//                                 ';
//                    }
                }
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-success disabled"><i class="glyphicon glyphicon-ok"></i></button>
                         ';
            }
            $row[] = $requisicao->data_admissao;

            $row[] = str_replace('.', ',', round($requisicao->aproveitamento1, 1)) . '%';
            if ($requisicao->candidato) {
                $row[] = '
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamentoPresencial_processos/online/' . $requisicao->id_candidato) . '" title="Acessar testes online"><i class="glyphicon glyphicon-list-alt"></i></a>
                         ';
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-primary disabled"><i class="glyphicon glyphicon-list-alt"></i></button>
                         ';
            }

            $row[] = str_replace('.', ',', round($requisicao->aproveitamento2, 1)) . '%';
            if ($requisicao->candidato) {
                $row[] = '
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamentoPresencial_processos/presencial/' . $requisicao->id_candidato) . '" title="Acessar testes presenciais"><i class="glyphicon glyphicon-list-alt"></i></a>
                         ';
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-primary disabled"><i class="glyphicon glyphicon-list-alt"></i></button>
                         ';
            }
            $row[] = $requisicao->id_candidato;
            $row[] = $requisicao->status;
            $row[] = $requisicao->id_usuario;
            $row[] = $requisicao->candidato;

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

    public function ajaxListAme()
    {
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca ?? array();


        $this->db->select('a.id, b.id AS id_usuario, a.nome, f.municipio, g.tipo AS deficiencia');
        $this->db->select('a.telefone, a.email, a.fonte_contratacao, a.status, a.observacoes, b.id_requisicao, h.id AS id_usuario_google');
        $this->db->join('requisicoes_pessoal_candidatos b', "b.id_usuario = a.id AND b.id_requisicao = '{$busca['id_requisicao']}'", 'left');
        $this->db->join('requisicoes_pessoal c', "c.id = b.id_requisicao",'left');
        $this->db->join('empresa_cargos d', 'd.id = c.id_cargo', 'left');
        $this->db->join('empresa_funcoes e', 'e.id = c.id_funcao', 'left');
        $this->db->join('municipios f', 'f.cod_mun = a.cidade', 'left');
        $this->db->join('deficiencias g', 'g.id = a.deficiencia', 'left');
        $this->db->join('recrutamento_google h', 'h.nome = a.nome', 'left');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        if (!empty($busca['estado'])) {
            $this->db->where('a.estado', $busca['estado']);
        }
        if (!empty($busca['cidade'])) {
            $this->db->where('a.cidade', $busca['cidade']);
        }
        if (!empty($busca['bairro'])) {
            $this->db->where('a.bairro', $busca['bairro']);
        }
        if (!empty($busca['deficiencia'])) {
            $this->db->where('a.deficiencia', $busca['deficiencia']);
        }
        if (!empty($busca['escolaridade'])) {
            $this->db->where('a.escolaridade', $busca['escolaridade']);
        }
        if (!empty($busca['sexo'])) {
            $this->db->where('a.sexo', $busca['sexo']);
        }
        if (!empty($busca['cargo_funcao'])) {
            $this->db->where("CONCAT(d.nome, '/' , e.nome) = ", "'{$busca['cargo_funcao']}'", false);
        }
        if (!empty($busca['resultado_selecao'])) {
            $this->db->where('b.resultado_selecao', $busca['resultado_selecao']);
        }
        if (!empty($busca['resultado_representante'])) {
            $this->db->where('b.resultado_requisitante', $busca['resultado_representante']);
        }
        $this->db->order_by('a.nome', 'ASC');
        $this->db->group_by('a.id');
        $query = $this->db->get('recrutamento_usuarios a');


        $config = array(
            'search' => ['nome', 'telefone', 'email', 'fonte_contratacao', 'observacoes']
        );

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);


        $data = array();

        foreach ($output->data as $row) {
            if ($row->id_requisicao or $row->id_usuario_google) {
                $btn = '<button type="button" class="btn btn-sm btn-success disabled" title="Incluído"><i class="glyphicon glyphicon-ok"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" title="Detalhes" onclick="detalhes_candidato(' . $row->id . ')"><i class="glyphicon glyphicon-info-sign"></i></button>';
            } else {
                $btn = '<button type="button" class="btn btn-sm btn-success" onclick="salvar_banco_novo(' . $row->id . ');" title="Incluir"><i class="glyphicon glyphicon-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" title="Detalhes" onclick="detalhes_candidato(' . $row->id . ')"><i class="glyphicon glyphicon-info-sign"></i></button>';
            }
            $data[] = array(
                $btn,
                null,
                null,
                $row->municipio,
                $row->nome,
                $row->deficiencia,
                $row->telefone,
                $row->email,
                $row->fonte_contratacao,
                $row->status,
                null,
                null,
                null,
                null,
                $row->observacoes,
                $row->id
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }

    public function ajaxListBanco()
    {
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca ?? array();
        $idRequisicao = $this->uri->rsegment(3, $busca['id_requisicao']);
        unset($busca['id_requisicao'], $busca['table_google_length']);


        $this->db->select('a.*, b.id_requisicao', false);
        $this->db->join('requisicoes_pessoal_candidatos b', "b.id_usuario_banco = a.id AND b.id_requisicao = {$idRequisicao}", 'left');
        $post = $this->input->post();
        if (count(array_filter($busca)) > 0 or strlen($post['search']['value']) > 0) {
            if (!empty($busca['cliente'])) {
                $this->db->where('a.cliente', $busca['cliente']);
            }
            if (!empty($busca['usuario'])) {
                $this->db->where('a.nome', $busca['usuario']);
            }
            if (!empty($busca['cargo'])) {
                $this->db->where('a.cargo', $busca['cargo']);
            }
            if (!empty($busca['cidade'])) {
                $this->db->where('a.cidade', $busca['cidade']);
            }
            if (!empty($busca['deficiencia'])) {
                $this->db->where('a.deficiencia', $busca['deficiencia']);
            }
            if (!empty($busca['resultado_entrevista_rh'])) {
                $this->db->where('a.resultado_entrevista_rh', $busca['resultado_entrevista_rh']);
            }
            if (!empty($busca['resultado_entrevista_cliente'])) {
                $this->db->where('a.resultado_entrevista_cliente', $busca['resultado_entrevista_cliente']);
            }
        } else {
            $this->db->where('a.id', null);
        }
        $recordsTotal = $this->db->get('recrutamento_google a')->num_rows();
        $recordsFiltered = $recordsTotal;

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        if ($post['search']['value']) {
            $sql .= " WHERE s.nome LIKE '%{$post['search']['value']}%'";
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();

        $data = array();

        foreach ($rows as $row) {
            if ($row->id_requisicao) {
                $btn = '<button type="button" class="btn btn-sm btn-success disabled" title="Incluído"><i class="glyphicon glyphicon-ok"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" title="Detalhes" onclick="detalhes_candidato(' . $row->id . ')"><i class="glyphicon glyphicon-info-sign"></i></button>';
            } else {
                $btn = '<button type="button" class="btn btn-sm btn-success" onclick="salvar_banco_google(' . $row->id . ');" title="Incluir"><i class="glyphicon glyphicon-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-primary" title="Detalhes" onclick="detalhes_candidato(' . $row->id . ')"><i class="glyphicon glyphicon-info-sign"></i></button>';
            }
            $data[] = array(
                $btn,
                $row->cliente,
                $row->cargo,
                $row->cidade,
                $row->nome,
                $row->deficiencia,
                $row->telefone,
                $row->email,
                $row->fonte_contratacao,
                $row->status,
                $row->data_entrevista_rh,
                $row->resultado_entrevista_rh,
                $row->data_entrevista_cliente,
                $row->resultado_entrevista_cliente,
                $row->observacoes,
                $row->id
            );
        }

        $output = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        );

        echo json_encode($output);
    }


    public function ajaxListInteressados()
    {
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca ?? array();


        $this->db->select('a.id, b.id AS id_usuario, a.nome, f.municipio, g.tipo AS deficiencia');
        $this->db->select('a.telefone, a.email, a.fonte_contratacao, a.status, a.observacoes, b.id_requisicao');
        $this->db->join('municipios f', 'f.cod_mun = a.cidade', 'left');
        $this->db->join('deficiencias g', 'g.id = a.deficiencia', 'left');
        $this->db->join('requisicoes_pessoal_candidatos b', "b.id_usuario = a.id AND b.id_requisicao = '{$busca['id_requisicao']}'", 'left');
        $this->db->join('requisicoes_pessoal c', "c.id = b.id_requisicao", 'left');
        $this->db->join('empresa_cargos d', 'd.id = c.id_cargo', 'left');
        $this->db->join('empresa_funcoes e', 'e.id = c.id_funcao', 'left');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.nivel_acesso', 'E');
        $this->db->where('b.status', 'E');
        if (!empty($busca['estado'])) {
            $this->db->where('a.estado', $busca['estado']);
        }
        if (!empty($busca['cidade'])) {
            $this->db->where('a.cidade', $busca['cidade']);
        }
        if (!empty($busca['bairro'])) {
            $this->db->where('a.bairro', $busca['bairro']);
        }
        if (!empty($busca['deficiencia'])) {
            $this->db->where('a.deficiencia', $busca['deficiencia']);
        }
        if (!empty($busca['escolaridade'])) {
            $this->db->where('a.escolaridade', $busca['escolaridade']);
        }
        if (!empty($busca['sexo'])) {
            $this->db->where('a.sexo', $busca['sexo']);
        }
        if (!empty($busca['cargo_funcao'])) {
            $this->db->where("CONCAT(d.nome, '/' , e.nome) = ", "'{$busca['cargo_funcao']}'", false);
        }
        if (!empty($busca['resultado_selecao'])) {
            $this->db->where('b.resultado_selecao', $busca['resultado_selecao']);
        }
        if (!empty($busca['resultado_representante'])) {
            $this->db->where('b.resultado_requisitante', $busca['resultado_representante']);
        }
        $query = $this->db->get('recrutamento_usuarios a');


        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();
        foreach ($output->data as $row) {
            $data[] = array(
                '<button type="button" class="btn btn-sm btn-success" onclick="salvar_interessado(' . $row->id_usuario . ');" title="Incluir"><i class="glyphicon glyphicon-plus"></i></button>
                 <button type="button" class="btn btn-sm btn-primary" title="Detalhes" onclick="detalhes_candidato(' . $row->id . ')"><i class="glyphicon glyphicon-info-sign"></i></button>',
                null,
                null,
                $row->municipio,
                $row->nome,
                $row->deficiencia,
                $row->telefone,
                $row->email,
                $row->fonte_contratacao,
                $row->status,
                null,
                null,
                null,
                null,
                $row->observacoes,
                $row->id
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    public function ajax_candidatos()
    {
        $empresa = $this->session->userdata('empresa');
        $where = $this->input->post();
        $options = array();


        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $fontes = $this->db->get('requisicoes_pessoal_fontes')->result();
        $options['fonte_contratacao'] = ['' => 'selecione...'] + array_column($fontes, 'nome', 'nome');


        $sql = "SELECT a.cod_uf, 
                       a.uf 
                FROM estados a 
                INNER JOIN recrutamento_usuarios b ON 
                           b.estado = a.cod_uf 
                WHERE b.empresa = {$empresa}";
        $estados = $this->db->query($sql)->result();
        $options['estado'] = ['' => 'Todos'] + array_column($estados, 'uf', 'cod_uf');


        $this->db->select('a.cod_mun, a.municipio ');
        $this->db->join('recrutamento_usuarios b', 'b.cidade = a.cod_mun');
        $this->db->where('b.empresa', $empresa);
        if ($where['estado']) {
            $this->db->where('a.cod_uf', $where['estado']);
        }
        $this->db->group_by('a.cod_mun');
        $cidades = $this->db->get('municipios a')->result();
        $options['cidade'] = ['' => 'Todas'] + array_column($cidades, 'municipio', 'cod_mun');


        $this->db->select('DISTINCT(bairro) AS bairro', false);
        $this->db->where('empresa', $empresa);
        $this->db->where('CHAR_LENGTH(bairro) >', 0);
        if ($where['estado']) {
            $this->db->where('estado', $where['estado']);
        }
        if ($where['cidade']) {
            $this->db->where('cidade', $where['cidade']);
        }
        $bairros = $this->db->get('recrutamento_usuarios')->result();
        $options['bairro'] = ['' => 'Todos'] + array_column($bairros, 'bairro', 'bairro');


        $sql2 = "SELECT a.id, 
                        a.tipo 
                 FROM deficiencias a 
                 INNER JOIN recrutamento_usuarios b ON 
                            b.deficiencia = a.id 
                 WHERE b.empresa = {$empresa}";
        $deficiencias = $this->db->query($sql2)->result();
        $options['deficiencia'] = ['' => 'Sem filtro'] + array_column($deficiencias, 'tipo', 'id');

        $sql3 = "SELECT DISTINCT(a.escolaridade) AS id, 
                        b.nome
                 FROM recrutamento_usuarios a 
                 INNER JOIN escolaridade b ON 
                            b.id = a.escolaridade
                 WHERE a.empresa = {$empresa}";
        $escolaridade = $this->db->query($sql3)->result();
        $options['escolaridade'] = ['' => 'Todas'] + array_column($escolaridade, 'nome', 'id');


        $sql4 = "SELECT DISTINCT(sexo) AS id, 
                        CASE sexo WHEN 'M' THEN 'Masculino' WHEN 'F' THEN 'Feminino' END AS nome
                 FROM recrutamento_usuarios 
                 WHERE empresa = {$empresa}";
        $sexos = $this->db->query($sql4)->result();
        $options['sexo'] = ['' => 'Todos'] + array_column($sexos, 'nome', 'id');


        $sql5 = "SELECT CONCAT(c.nome, '/', d.nome) AS cargo_funcao
                 FROM requisicoes_pessoal_candidatos a
                 INNER JOIN requisicoes_pessoal b ON 
                            b.id = a.id_requisicao
                 INNER JOIN empresa_cargos c ON 
                            c.id = b.id_cargo
                 INNER JOIN empresa_funcoes d ON 
                            d.id = b.id_funcao
                 WHERE b.id_empresa = {$empresa} AND 
                       b.id = {$where['id_requisicao']}
                 GROUP BY c.id, d.id 
                 ORDER BY c.nome ASC, 
                          d.nome ASC";
        $cargosFuncoes = $this->db->query($sql5)->result();
        $options['cargo_funcao'] = ['' => 'Todos'] + array_column($cargosFuncoes, 'cargo_funcao', 'cargo_funcao');


        $this->db->select('IFNULL(a.requisitante_externo, b.nome) AS requisitante', false);
        $this->db->join('usuarios b', 'b.id = a.requisitante_interno');
        $this->db->where('a.id_empresa', $empresa);
        $clientes = $this->db->get('requisicoes_pessoal a')->result();
        $options['cliente'] = ['' => 'Todos'] + array_column($clientes, 'requisitante', 'requisitante');


        $this->db->select('a.id, a.nome');
//        $this->db->join('recrutamento_candidatos b', 'b.id_usuario = a.id', 'left');
        $this->db->join('requisicoes_pessoal_candidatos b', 'b.id_usuario = a.id', 'left');
        $this->db->join('requisicoes_pessoal c', 'c.id = b.id_requisicao', 'left');
        $this->db->join('empresa_cargos d', 'd.id = c.id_cargo', 'left');
        $this->db->join('empresa_funcoes e', 'e.id = c.id_funcao', 'left');
        $this->db->where('a.empresa', $this->session->userdata('empresa'));
        if ($where['estado']) {
            $this->db->where('a.estado', $where['estado']);
        }
        if ($where['cidade']) {
            $this->db->where('a.cidade', $where['cidade']);
        }
        if ($where['bairro']) {
            $this->db->where('a.bairro', $where['bairro']);
        }
        if ($where['deficiencia']) {
            $this->db->where('a.deficiencia', $where['deficiencia']);
        }
        if ($where['escolaridade']) {
            $this->db->where('a.escolaridade', $where['escolaridade']);
        }
        if ($where['sexo']) {
            $this->db->where('a.sexo', $where['sexo']);
        }
        if ($where['cargo_funcao']) {
            $this->db->where("CONCAT(d.nome, '/' , e.nome) = ", "'{$where['cargo_funcao']}'", false);
        }
        if ($where['resultado_selecao']) {
            $this->db->where('b.resultado_selecao', $where['resultado_selecao']);
        }
        if ($where['resultado_representante']) {
            $this->db->where('b.resultado_requisitante', $where['resultado_representante']);
        }
//        $this->db->where('b . id', null);
        $this->db->order_by('a . nome', 'ASC');
        $rows = $this->db->get('recrutamento_usuarios a')->result();
        $options['usuarios'] = array_column($rows, 'nome', 'id');


        $data['clientes'] = form_dropdown('', $options['cliente'], $where['cliente'], 'id = "estado" class="form-control filtro input-sm"');
        $data['estados'] = form_dropdown('', $options['estado'], $where['estado'], 'id = "estado" class="form-control filtro input-sm"');
        $data['cidades'] = form_dropdown('', $options['cidade'], $where['cidade'], 'id = "cidade" class="form-control filtro input-sm"');
        $data['bairros'] = form_dropdown('', $options['bairro'], $where['bairro'], 'id = "bairro" class="form-control filtro input-sm"');
        $data['deficiencias'] = form_dropdown('', $options['deficiencia'], $where['deficiencia'], 'id = "deficiencia" class="form-control filtro input-sm"');
        $data['fonte_contratacao'] = form_dropdown('', $options['fonte_contratacao'], '', 'id="fonte_contratacao" name="fonte_contratacao" class="form-control"');
        $data['escolaridade'] = form_dropdown('', $options['escolaridade'], $where['escolaridade'], 'id = "escolaridade" class="form-control filtro input-sm"');
        $data['cargo_funcao'] = form_dropdown('', $options['cargo_funcao'], $where['cargo_funcao'], 'id = "cargo_funcao" class="form-control filtro input-sm"');
        $data['candidatos'] = form_multiselect('id_usuario[]', $options['usuarios'], array(), 'id = "id_usuario" class="form-control demo1"');


        echo json_encode($data);
    }

    public function ajax_bancos()
    {
        $empresa = $this->session->userdata('empresa');
        $where = $this->input->post();


        $this->db->select('DISTINCT(cliente) AS cliente');
        $this->db->where('CHAR_LENGTH(cliente) >', 0);
        $this->db->order_by('cliente', 'asc');
        $cliente = $this->db->get('recrutamento_google')->result();
        $options['clientes'] = ['' => 'selecione...'] + array_column($cliente, 'cliente', 'cliente');


        $this->db->select('DISTINCT(cidade) AS cidade');
        if (!empty($where['cliente'])) {
            $this->db->start_cache();
            $this->db->where('cliente', $where['cliente']);
            $this->db->stop_cache();
        }
        $this->db->where('CHAR_LENGTH(cidade) >', 0);
        $this->db->order_by('cidade', 'asc');
        $cidade = $this->db->get('recrutamento_google')->result();
        $options['cidades'] = ['' => 'selecione...'] + array_column($cidade, 'cidade', 'cidade');


        $this->db->select('DISTINCT(cargo) AS cargo');
        if (!empty($where['cidade'])) {
            $this->db->start_cache();
            $this->db->where('cidade', $where['cidade']);
            $this->db->stop_cache();
        }
        $this->db->where('CHAR_LENGTH(cargo) >', 0);
        $this->db->order_by('cargo', 'asc');
        $cargo = $this->db->get('recrutamento_google')->result();
        $options['cargos'] = ['' => 'selecione...'] + array_column($cargo, 'cargo', 'cargo');


        $this->db->select('DISTINCT(deficiencia) AS deficiencia');
        if (!empty($where['cargo'])) {
            $this->db->start_cache();
            $this->db->where('cargo', $where['cargo']);
            $this->db->stop_cache();
        }
        $this->db->where('CHAR_LENGTH(deficiencia) >', 0);
        $this->db->order_by('deficiencia', 'asc');
        $deficiencia = $this->db->get('recrutamento_google')->result();
        $options['deficiencias'] = ['' => 'Todas'] + array_column($deficiencia, 'deficiencia', 'deficiencia');


        $this->db->select('DISTINCT(resultado_entrevista_rh) AS rh');
        $this->db->where('CHAR_LENGTH(resultado_entrevista_rh) >', 0);
        $this->db->order_by('resultado_entrevista_rh', 'asc');
        $rh = $this->db->get('recrutamento_google')->result();
        $options['resultado_rh'] = ['' => 'Todos'] + array_column($rh, 'rh', 'rh');


        $this->db->select('DISTINCT(resultado_entrevista_cliente) AS cli');
        $this->db->where('CHAR_LENGTH(resultado_entrevista_cliente) >', 0);
        $this->db->order_by('resultado_entrevista_cliente', 'asc');
        $cli = $this->db->get('recrutamento_google')->result();
        $options['resultado_cli'] = ['' => 'Todos'] + array_column($cli, 'cli', 'cli');


        $this->db->flush_cache();


        $data['clientes'] = form_dropdown('', $options['clientes'], $where['cliente'], 'id = "estado" class="form-control filtro input-sm"');
        $data['cidades'] = form_dropdown('', $options['cidades'], $where['cidade'], 'id = "estado" class="form-control filtro input-sm"');
        $data['cargos'] = form_dropdown('', $options['cargos'], $where['cargo'], 'id = "estado" class="form-control filtro input-sm"');
        $data['deficiencias'] = form_dropdown('', $options['deficiencias'], $where['deficiencia'], 'id = "estado" class="form-control filtro input-sm"');
        $data['resultados_rh'] = form_dropdown('', $options['resultado_rh'], $where['resultado_entrevista_rh'], 'id = "estado" class="form-control filtro input-sm"');
        $data['resultados_cli'] = form_dropdown('', $options['resultado_cli'], $where['resultado_entrevista_cliente'], 'id = "estado" class="form-control filtro input-sm"');


        echo json_encode($data);
    }

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('recrutamento_cargos', array('id' => $id))->row();
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if (empty($data['cargo'])) {
            exit(json_encode(array('erro' => 'O cargo não deve ficar sem nome')));
        }

        $status = $this->db->insert('recrutamento_cargos', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addCandidatoNovo()
    {
        $data = $this->input->post();
        $idRequisicao = $data['id_requisicao'];
        unset($data['id_requisicao']);

        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'Nenhum candidato selecionado')));
        }
        if (strlen($data['email']) > 0 and !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(array('erro' => 'Endereço de e-mail inválido')));
        }

        $this->db->select('nome, email');
        $this->db->where('nome', $data['nome']);
        if (strlen($data['email'])) {
            $this->db->or_where('email', $data['email']);
        }
        $candidato = $this->db->get('recrutamento_usuarios')->row();
        if (!empty($candidato->nome)) {
            exit(json_encode(array('erro' => 'Já existe um candidato cadastrado com esse nome')));
        }
        if (!empty($candidato->email)) {
            exit(json_encode(array('erro' => 'Já existe um candidato cadastrado com esse e-mail')));
        }
//        if (strlen($data['senha']) == 0) {
//            exit(json_encode(array('retorno' => 0, 'aviso' => 'A senha é obrigatória')));
//        }
//        $this->db->select('nome');
//        $this->db->where('id_empresa', $this->session->userdata('empresa'));
//        $this->db->where('id', $data['fonte_contratacao']);
//        $fonte = $this->db->get('requisicoes_pessoal_fontes')->row();

        $this->db->trans_begin();

        $data2 = array(
            'nome' => $data['nome'],
            'empresa' => $this->session->userdata('empresa'),
            'telefone' => $data['telefone'],
            'deficiencia' => $data['deficiencia'],
            'fonte_contratacao' => $data['fonte_contratacao'],
            'email' => strlen($data['email']) > 0 ? $data['email'] : null,
            'senha' => $data['senha'],
            'token' => uniqid(),
            'data_inscricao' => date('Y-m-d H:i:s'),
            'nivel_acesso' => 'C',
            'status' => 'A'
        );
        $this->db->insert('recrutamento_usuarios', $data2);

        if ($this->db->trans_status()) {
            $data = array(
                'id_requisicao' => $idRequisicao,
                'id_usuario' => $this->db->insert_id(),
                'antecedentes_criminais' => null,
                'restricoes_financeiras' => null,
                'resultado_exame_admissional' => null,
                'observacoes' => null
            );
            $this->db->insert('requisicoes_pessoal_candidatos', $data);
        }

        $status = $this->db->trans_status();

        if ($status == false) {
            exit(json_encode(['erro' => 'Não foi possível cadastrar o candidato.']));
            $this->db->trans_rollback();
        }

        $this->db->trans_commit();

        echo json_encode(['status' => $status]);
    }

    public function ajax_addCandidato()
    {
        $id = $this->input->post('id');
        $idRequisicao = $this->input->post('id_requisicao');

        $usuario = $this->db->select('id')->get_where('recrutamento_usuarios', ['id' => $id])->row();
        if (empty($usuario)) {
            exit(json_encode(array('erro' => 'Candidato não encontrado.')));
        }

        $data = array(
            'id_requisicao' => $idRequisicao,
            'id_usuario' => $usuario->id,
            'antecedentes_criminais' => null,
            'restricoes_financeiras' => null,
            'resultado_exame_admissional' => null,
            'observacoes' => null
        );
        $status = $this->db->insert('requisicoes_pessoal_candidatos', $data);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addBanco()
    {
        $id = $this->input->post('id');
        $idRequisicao = $this->input->post('id_requisicao');

        $usuario = $this->db->select('id')->get_where('recrutamento_google', ['id' => $id])->row();
        if (empty($usuario)) {
            exit(json_encode(array('erro' => 'Candidato não encontrado.')));
        }

        $data = array(
            'id_requisicao' => $idRequisicao,
            'id_usuario' => null,
            'id_usuario_banco' => $usuario->id,
            'antecedentes_criminais' => null,
            'resultado_exame_admissional' => null
        );
        $status = $this->db->insert('requisicoes_pessoal_candidatos', $data);
        if ($status) {
            $cidade = $this->db->select('cod_mun')->get_where('municipios', ['municipio' => $data['cidade']])->row();
            $deficiencia = $this->db->select('id')->get_where('deficiencias', ['tipo' => $data['deficiencia']])->row();
            $data2 = array(
                'nome' => $data['nome'],
                'cidade' => $cidade->cod_mun ?? null,
                'deficiencia' => $deficiencia->id ?? null,
                'telefone' => $data['telefone'],
                'email' => $data['email'],
                'fonte_contratacao' => $data['fonte_contratacao'],
                'status' => $data['status'],
                'observacoes' => $data['observacoes']
            );
            $this->db->insert('recrutamento_usuarios', $data2);
        }

        echo json_encode(array("status" => $status !== false));
    }


    public function ajax_addInteressado()
    {
        $id = $this->input->post('id');
        $data = array(
            'status' => 'A'
        );
        $status = $this->db->update('requisicoes_pessoal_candidatos', $data, ['id' => $id]);

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if (empty($data['cargo'])) {
            exit(json_encode(array('erro' => 'O cargo não deve ficar sem nome')));
        }

        $where = array('id' => $data['id']);
        unset($data['id']);

        $status = $this->db->update('recrutamento_cargos', $data, $where);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateCandidato()
    {
        $data = $this->input->post();

        if (isset($data['status'])) {
            $this->db->set('status', strlen($data['status']) > 0 ? $data['status'] : null);
        }

        if (isset($data['data_selecao'])) {
            if (strlen($data['data_selecao']) > 0) {
                $this->db->set('data_selecao', date('Y-m-d H:i', strtotime(str_replace('/', '-', $data['data_selecao'] . ' ' . $data['hora_selecao']))));
            } else {
                $this->db->set('data_selecao', null);
            }
            unset($data['hora_selecao']);
        }
        if (isset($data['resultado_selecao'])) {
            if (strlen($data['resultado_selecao']) > 0 and strlen($data['data_selecao']) > 0) {
                $this->db->set('resultado_selecao', $data['resultado_selecao']);
            } else {
                $this->db->set('resultado_selecao', null);
            }
        }
        if (isset($data['data_requisitante'])) {
            if (strlen($data['data_requisitante']) > 0) {
                $this->db->set('data_requisitante', date('Y-m-d H:i', strtotime(str_replace('/', '-', $data['data_requisitante'] . ' ' . $data['hora_requisitante']))));
            } else {
                $this->db->set('data_requisitante', null);
            }
            unset($data['hora_requisitante']);
        }
        if (isset($data['resultado_requisitante'])) {
            if (strlen($data['resultado_requisitante']) > 0 and strlen($data['data_requisitante']) > 0) {
                $this->db->set('resultado_requisitante', $data['resultado_requisitante']);
            } else {
                $this->db->set('resultado_requisitante', null);
            }
        }
        if (isset($data['antecedentes_criminais'])) {
            if (strlen($data['antecedentes_criminais']) > 0) {
                $this->db->set('antecedentes_criminais', $data['antecedentes_criminais']);
            } else {
                $this->db->set('antecedentes_criminais', null);
            }
        }
        if (isset($data['restricoes_financeiras'])) {
            if (strlen($data['restricoes_financeiras']) > 0) {
                $this->db->set('restricoes_financeiras', $data['restricoes_financeiras']);
            } else {
                $this->db->set('restricoes_financeiras', null);
            }
        }
        if (isset($data['data_exame_admissional'])) {
            if (strlen($data['data_exame_admissional']) > 0) {
                $this->db->set('data_exame_admissional', date('Y-m-d', strtotime(str_replace('/', '-', $data['data_exame_admissional']))));
            } else {
                $this->db->set('data_exame_admissional', null);
            }
        }
        if (isset($data['resultado_exame_admissional'])) {
            if (strlen($data['resultado_exame_admissional']) > 0 and strlen($data['data_exame_admissional']) > 0) {
                $this->db->set('resultado_exame_admissional', $data['resultado_exame_admissional']);
            } else {
                $this->db->set('resultado_exame_admissional', null);
            }
        }
        if (isset($data['data_admissao'])) {
            if (strlen($data['data_admissao']) > 0) {
                $this->db->set('data_admissao', date('Y-m-d', strtotime(str_replace('/', '-', $data['data_admissao']))));
            } else {
                $this->db->set('data_admissao', null);
            }
        }
        if (isset($data['observacoes'])) {
            if (strlen($data['observacoes']) > 0) {
                $this->db->set('observacoes', $data['observacoes']);
            } else {
                $this->db->set('observacoes', null);
            }
        }

        $this->db->where('id', $this->input->post('id'));
        $status = $this->db->update('requisicoes_pessoal_candidatos');

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('recrutamento_cargos', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_deleteCandidato($id)
    {
        $status = $this->db->delete('requisicoes_pessoal_candidatos', array('id' => $id));
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

    public function aprovarCandidato($id)
    {
        $status = $this->db->update('requisicoes_pessoal_candidatos', array('aprovado' => 1), array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function desaprovarCandidato($id)
    {
        $status = $this->db->update('requisicoes_pessoal_candidatos', array('aprovado' => null), array('id' => $id));
        echo json_encode(array("status" => $status !== false));
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

        $sql = "SELECT a.id, 
                       b.nome, 
                       b.tipo, 
                       '{
                $this->input->get('depto')}' AS depto,
                       '{
                $this->input->get('area')}' AS area,
                       '{
                $this->input->get('setor')}' AS setor,
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
        $this->db->select('a . id');
        $this->db->join('usuarios b', 'b . id = a . id_avaliador');
        $this->db->where('a . id_pesquisa', $id);
        $where = array_filter(array('b . depto' => $depto, 'b . area' => $area, 'b . setor' => $setor));
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

        $stylesheet = 'table . pesquisa thead th {
                font - size: 11px; padding: 5px; text - align: center; font - weight: normal; } ';
        $stylesheet .= 'table . pesquisa tbody tr {
                border - width: 5px; border - color: #ddd; } ';
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
