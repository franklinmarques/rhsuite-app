<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_avaliador extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['id_usuario'] = $this->session->userdata('id');
        $data['titulo'] = 'Avaliações de Experiência';
        $data['tipo_modelo'] = '';
        $this->load->view('avaliacaoexp_avaliador', $data);
    }

    public function avaliacoesPeriodicas()
    {
        $data['id_usuario'] = $this->session->userdata('id');
        $data['titulo'] = 'Avaliações Periódicas';
        $data['tipo_modelo'] = '1';
        $this->load->view('avaliacaoexp_avaliador', $data);
    }

    public function desempenho()
    {
        $data['id_usuario'] = $this->session->userdata('id');
        $data['titulo'] = 'Avaliações de Desempenho';
        $data['tipo_modelo'] = '3';
        $this->load->view('avaliacaoexp_avaliador', $data);
    }

    public function periodo()
    {
        $data['id_usuario'] = $this->session->userdata('id');
        $data['titulo'] = 'Avaliações do Período de Experiência';
        $data['tipo_modelo'] = '2';
        $this->load->view('avaliacaoexp_avaliador', $data);
    }

    public function ajax_list($id, $tipo = '')
    {
        if (empty($id)) {
            $id = $this->session->userdata('id');
        }
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.cargo,
                       s.depto,
                       s.data_programada,
                       s.avaliador,
                       s.data_realizacao,
                       s.resultado,
                       s.desempenho,
                       s.periodo
                FROM (SELECT a.id, 
                             u.nome,
                             c.cargo,
                             b.nome AS avaliacao,
                             CONCAT_WS('/', u.depto, u.area, u.setor) AS depto,
                             d.id AS avaliador,
                             d.data_avaliacao AS data_programada,
                             (SELECT MAX(i.data_avaliacao)
                              FROM avaliacaoexp_resultado i
                              WHERE i.id_avaliador = d.id) AS data_realizacao,
                              IF(COUNT(h.id) > 0, 1, 0) AS resultado,
                              IF((b.tipo = 'A' OR b.tipo = 'D') AND COUNT(k.id_avaliador) > 0, 1, 0) AS desempenho,
                              IF(b.tipo = 'P' AND COUNT(j.id_avaliado) > 0, 0, 0) AS periodo
                      FROM avaliacaoexp_avaliados a
                      INNER JOIN avaliacaoexp_modelos b ON
                                 b.id = a.id_modelo
                      INNER JOIN avaliacaoexp_avaliadores d ON
                                 d.id_avaliado = a.id 
                      INNER JOIN usuarios u ON
                                 u.id = a.id_avaliado
                      LEFT JOIN funcionarios_cargos f ON 
                                 f.id_usuario = u.id
                      LEFT JOIN cargos c ON 
                                 c.id = f.id_cargo
                      LEFT JOIN avaliacaoexp_resultado h ON 
                                h.id_avaliador = d.id
                      LEFT JOIN avaliacaoexp_desempenho k ON 
                                k.id_avaliador = d.id
                      LEFT JOIN avaliacaoexp_periodo j ON 
                                j.id_avaliado = a.id
                      WHERE d.id_avaliador = {$id}";
        if (isset($busca['resultado']) and !empty($busca['resultado'])) {
            $sql .= ' AND h.id_avaliador IS NULL';
        }
        if (!empty($busca['mes'])) {
            $sql .= " AND MONTH(d.data_avaliacao) = '{$busca['mes']}'";
        }
        if (!empty($busca['ano'])) {
            $sql .= " AND YEAR(d.data_avaliacao) = '{$busca['ano']}'";
        }
        if ($tipo) {
            if ($tipo == '1') {
                $tipo = 'A';
            } elseif ($tipo == '2') {
                $tipo = 'P';
            } elseif ($tipo == '3') {
                $tipo = 'D';
            }
            $sql .= " AND b.tipo = '{$tipo}'";
        }
        $sql .= " GROUP BY a.id, d.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.nome',
            's.cargo',
            's.depto',
            's.data_programada',
            's.data_realizacao'
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
        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $avaliacao_parte1 = $this->agent->is_mobile() ? '<sup>1</sup>' : ' Avaliação parte 1';
        $avaliacao_parte2 = $this->agent->is_mobile() ? '<sup>2</sup>' : ' Avaliação parte 2';
        $relatorio = $this->agent->is_mobile() ? '' : 'Relatório';

        $data = array();
        foreach ($list as $avaliacaoExp) {
            $row = array();
            $row[] = $avaliacaoExp->nome;
            $row[] = $avaliacaoExp->cargo;
            $row[] = $avaliacaoExp->depto;
            $row[] = $avaliacaoExp->data_programada ? date("d/m/Y", strtotime(str_replace('-', '/', $avaliacaoExp->data_programada))) : '';
            $row[] = $avaliacaoExp->data_realizacao ? date("d/m/Y", strtotime(str_replace('-', '/', $avaliacaoExp->data_realizacao))) : '';
            $btn = '';
            if ($tipo === 'P') {
                if ($avaliacaoExp->resultado) {
                    $btn .= '<button class="btn btn-sm btn-info disabled" title="Avaliação parte 1"><i class="glyphicon glyphicon-ok"></i>' . $avaliacao_parte1 . '</button>';
                } else {
                    $btn .= '<button class="btn btn-sm btn-info" onclick="edit_avaliacao(' . $avaliacaoExp->avaliador . ')" title="Avaliação parte 1"><i class="glyphicon glyphicon-plus"></i>' . $avaliacao_parte1 . '</button>';
                }
                if ($avaliacaoExp->periodo) {
                    $btn .= '
                            <button class="btn btn-sm btn-info disabled" title="Avaliação parte 2"><i class="glyphicon glyphicon-ok"></i>' . $avaliacao_parte2 . '</button>';
                } else {
                    $btn .= '
                            <button class="btn btn-sm btn-info" onclick="edit_periodo(' . $avaliacaoExp->id . ')" title="Avaliação parte 2"><i class="glyphicon glyphicon-plus"></i>' . $avaliacao_parte2 . '</button>';
                }
                $row[] = $btn . '
                                <a class="btn btn-sm btn-primary" href="' . site_url('avaliacaoexp_avaliados/relatorio/' . $avaliacaoExp->id) . '" title="Relatório de avaliação"><i class="glyphicon glyphicon-list-alt"></i> ' . $relatorio . '</a>';
            } else {
                if ($avaliacaoExp->resultado) {
                    $btn .= '<button class="btn btn-sm btn-info disabled" title="Avaliação parte 1"><i class="glyphicon glyphicon-ok"></i>' . $avaliacao_parte1 . '</button>';
                } else {
                    $btn .= '<button class="btn btn-sm btn-info" onclick="edit_avaliacao(' . $avaliacaoExp->avaliador . ')" title="Avaliação parte 1"><i class="glyphicon glyphicon-plus"></i>' . $avaliacao_parte1 . '</button>';
                }
                if ($avaliacaoExp->desempenho) {
                    $btn .= '
                            <button class="btn btn-sm btn-info disabled" title="Avaliação parte 2"><i class="glyphicon glyphicon-ok"></i>' . $avaliacao_parte2 . '</button>';
                } else {
                    $btn .= '
                            <button class="btn btn-sm btn-info" onclick="edit_desempenho(' . $avaliacaoExp->avaliador . ')" title="Avaliação parte 2"><i class="glyphicon glyphicon-plus"></i>' . $avaliacao_parte2 . '</button>';
                }

                if ($tipo === 'A') {
                    $btn . '
                            <a class="btn btn-sm btn-primary" href="' . site_url('avaliacaoexp_avaliados/relatorio/' . $avaliacaoExp->id) . '" title="Relatório de avaliação"><i class="glyphicon glyphicon-list-alt"></i> ' . $relatorio . '</a>';
                }

                $row[] = $btn;
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
        $sql = "SELECT a.id_modelo
                FROM avaliacaoexp_avaliados a
                INNER JOIN avaliacaoexp_modelos b ON
                           b.id = a.id_modelo
                INNER JOIN avaliacaoexp_avaliadores c ON
                           c.id_avaliado = a.id
                WHERE c.id = {$id}";
        $avaliador = $this->db->query($sql)->row();

        $query = "SELECT a.id,
                         a.pergunta,
                         a.tipo
                  FROM avaliacaoexp_perguntas a
                  INNER JOIN avaliacaoexp_modelos b ON
                             b.id = a.id_modelo
                  WHERE b.id = {$avaliador->id_modelo} 
                  ORDER BY a.id ASC";
        $perguntas = $this->db->query($query)->result();

        $data = array();

        foreach ($perguntas as $p => $pergunta) {

            if ($pergunta->tipo == 'U' or $pergunta->tipo == 'M') {

                $select = "SELECT a.id,
                              a.id_pergunta,
                              a.alternativa,
                              a.peso
                       FROM avaliacaoexp_alternativas a
                       INNER JOIN avaliacaoexp_modelos b ON
                                  b.id = a.id_modelo
                       INNER JOIN avaliacaoexp_perguntas c ON
                                  c.id_modelo = b.id
                       WHERE c.id = {$pergunta->id} AND 
                                  (a.id_pergunta = c.id OR a.id_pergunta IS NULL)
                       ORDER BY a.id ASC, 
                                a.peso ASC";

                $alternativas = $this->db->query($select)->result();

                $arrAlternativas = array();
                $arrQuestionario = array();

                foreach ($alternativas as $alternativa) {
                    $arrAlternativas[$alternativa->id] = $alternativa->alternativa;
                    $arrQuestionario[] = $alternativa->id;
                }

                $questionarios = implode(',', $arrQuestionario);

                $select2 = "SELECT b.id_alternativa,
                                   b.resposta
                            FROM avaliacaoexp_avaliadores a
                            LEFT JOIN avaliacaoexp_resultado b ON
                                      b.id_avaliador = a.id
                            INNER JOIN avaliacaoexp_perguntas c ON
                                       c.id = b.id_pergunta
                            INNER JOIN avaliacaoexp_alternativas d ON
                                       d.id = b.id_alternativa AND 
                                       d.id in ({$questionarios})
                            WHERE c.id = {$pergunta->id} AND 
                                  a.id = {$id} 
                            UNION ALL 
                            SELECT null AS id_alternativa,
                                   null AS resposta
                            FROM dual 
                            LIMIT 1";

                $resposta = $this->db->query($select2)->row();

                $respostas = form_hidden("id_pergunta[$p]", $pergunta->id);
                foreach ($arrAlternativas as $k => $arrAlternativa) {
                    $respostas .= '<div><label>';
                    $respostas .= form_radio("id_alternativa[$p]", $k, $k == $resposta->id_alternativa);
                    $respostas .= ' ' . $arrAlternativa . " </label></div>";
                }
            } else {

                $select2 = "SELECT b.id_alternativa,
                                   b.resposta
                            FROM avaliacaoexp_avaliadores a
                            LEFT JOIN avaliacaoexp_resultado b ON
                                      b.id_avaliador = a.id
                            INNER JOIN avaliacaoexp_perguntas c ON
                                       c.id = b.id_pergunta
                            WHERE c.id = {$pergunta->id} AND 
                                  a.id = {$id} AND 
                                  b.id_alternativa IS NULL
                            UNION ALL 
                            SELECT null AS id_alternativa,
                                   null AS resposta
                            FROM dual 
                            LIMIT 1";
                $resposta = $this->db->query($select2)->row();
                $respostas = form_hidden("id_pergunta[$p]", $pergunta->id);
                $input = array(
                    'name' => "resposta[$p]",
                    'class' => 'form-control',
                    'value' => $resposta->resposta);
                if ($pergunta->tipo == 'N') {
                    $input['type'] = 'number';
                    $respostas .= form_input($input);
                } else {
                    $input['rows'] = 2;
                    $respostas .= form_textarea($input);
                }
            }

            $data[$p] = array($pergunta->pergunta, $respostas);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_editDesempenho($id)
    {
        $query = "SELECT a.*, 
                         b.id
                  FROM avaliacaoexp_desempenho a
                  RIGHT JOIN avaliacaoexp_avaliadores b ON
                             b.id = a.id_avaliador
                  WHERE b.id = {$id}";

        $data = $this->db->query($query)->row();

        if (empty($data->id_avaliador)) {
            $data->id_avaliador = $data->id;
        }

        echo json_encode($data);
    }

    public function ajax_editPeriodo($id)
    {
        $query = "SELECT a.*, 
                         b.id
                  FROM avaliacaoexp_periodo a
                  RIGHT JOIN avaliacaoexp_avaliados b ON
                             b.id = a.id_avaliado
                  WHERE b.id = {$id}";

        $data = $this->db->query($query)->row();
        if ($data->data_feedback1) {
            $data->data_feedback1 = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_feedback1)));
        }
        if ($data->data_feedback2) {
            $data->data_feedback2 = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_feedback2)));
        }
        if ($data->data_feedback3) {
            $data->data_feedback3 = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_feedback3)));
        }


        if (empty($data->id_avaliado)) {
            $data->id_avaliado = $data->id;
        }

        echo json_encode($data);
    }

    public function ajax_save()
    {
        $avaliador = $this->input->post('id_avaliador');
        $id_pergunta = $this->input->post('id_pergunta');
        $id_alternativa = $this->input->post('id_alternativa');
        $resposta = $this->input->post('resposta');

        $select = "SELECT id_pergunta
                   FROM avaliacaoexp_resultado 
                   WHERE id_avaliador = {$avaliador}";

        $rows = $this->db->query($select)->result();

        $arrPergunta = array();
        foreach ($rows as $row) {
            $arrPergunta[] = $row->id_pergunta;
        }

        $this->db->trans_begin();
        foreach ($id_pergunta as $k => $pergunta) {
            if (!isset($id_alternativa[$k]) and !isset($resposta[$k])) {
                continue;
            }

            $data = array(
                'id_avaliador' => $avaliador,
                'id_pergunta' => $pergunta,
                'id_alternativa' => isset($id_alternativa[$k]) ? $id_alternativa[$k] : null,
                'resposta' => isset($resposta[$k]) ? $resposta[$k] : null,
                'data_avaliacao' => date('Y-m-d H:i:s')
            );

            if (in_array($pergunta, $arrPergunta)) {
                $where = array(
                    'id_avaliador' => $avaliador,
                    'id_pergunta' => $pergunta
                );
                $query = $this->db->update_string('avaliacaoexp_resultado', $data, $where);
            } else {
                $query = $this->db->insert_string('avaliacaoexp_resultado', $data);
            }
            $this->db->query($query);
        }

        $status = $this->db->trans_status();
        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_saveDesempenho()
    {
        $data = $this->input->post();

        $where = array('id_avaliador' => $data['id_avaliador']);

        $this->db->select(implode(',', array_keys($data)));
        $select = $this->db->get_where('avaliacaoexp_desempenho', $where)->row();
        if (array_diff($data, (array)$select)) {
            $data['data'] = date("Y-m-d H:i:s");
        }

        $count = $this->db->get_where('avaliacaoexp_desempenho', $where)->num_rows();

        if ($count) {
            $status = $this->db->update('avaliacaoexp_desempenho', $data, $where);
        } else {
            $status = $this->db->insert('avaliacaoexp_desempenho', $data);
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_savePeriodo()
    {
        $data = $this->input->post();
        if (empty($data['feedback1'])) {
            $data['feedback1'] = null;
        }
        if (empty($data['feedback2'])) {
            $data['feedback2'] = null;
        }
        if (empty($data['feedback3'])) {
            $data['feedback3'] = null;
        }

        $where = array('id_avaliado' => $data['id_avaliado']);
        $this->db->select(implode(',', array_keys($data)));

        $select = $this->db->get_where('avaliacaoexp_periodo', $where)->row();
//        if (strcmp($data['feedback1'], $select->feedback1)) {
        if (!(isset($select->feedback1) and $data['feedback1'] === $select->feedback1)) {
            $data['data_feedback1'] = date("Y-m-d H:i:s");
        }
//        if (strcmp($data['feedback2'], $select->feedback2)) {
        if (!(isset($select->feedback2) and $data['feedback1'] === $select->feedback2)) {
            $data['data_feedback2'] = date("Y-m-d H:i:s");
        }
//        if (strcmp($data['feedback3'], $select->feedback3)) {
        if (!(isset($select->feedback3) and $data['feedback1'] === $select->feedback3)) {
            $data['data_feedback3'] = date("Y-m-d H:i:s");
        }
        if (array_diff($data, (array)$select)) {
            $data['data'] = date("Y-m-d H:i:s");
        }

        $count = $this->db->get_where('avaliacaoexp_periodo', $where)->num_rows();

        if ($count) {
            $status = $this->db->update('avaliacaoexp_periodo', $data, $where);
        } else {
            $status = $this->db->insert('avaliacaoexp_periodo', $data);
        }

        echo json_encode(array("status" => $status !== false));
    }

}
