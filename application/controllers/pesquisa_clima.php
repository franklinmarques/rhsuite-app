<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_clima extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Pesquisa_model', 'pesquisa');
    }

    public function index()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => '',
            'nome' => 'Modelo de pesquisa'
        );

        $this->load->view('pesquisa_clima', $data);
    }

    public function ajax_list()
    {

        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.data_inicio,
                       s.data_termino
                FROM (SELECT a.id, 
                             b.nome, 
                             b.data_inicio,
                             b.data_termino
                      FROM pesquisa_avaliadores a 
                      INNER JOIN pesquisa b ON
                                 b.id = a.id_pesquisa
                      INNER JOIN pesquisa_modelos c ON 
                                 c.id = b.id_modelo AND 
                                 c.tipo = 'C'
                      WHERE a.id_avaliador = {$this->session->userdata('id')}) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.data_inicio', 's.data_termino');
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
        $responderPesquisa = $this->agent->is_mobile() ? ' Resp.' : ' Responder pesquisa';
        foreach ($list as $pesquisa) {
            $row = array();
            $row[] = $pesquisa->nome;
            if ($this->agent->is_mobile()) {
                $row[] = $pesquisa->data_inicio ? date("d/m/y", strtotime(str_replace('-', '/', $pesquisa->data_inicio))) : '';
                $row[] = $pesquisa->data_termino ? date("d/m/y", strtotime(str_replace('-', '/', $pesquisa->data_termino))) : '';
            } else {
                $row[] = $pesquisa->data_inicio ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_inicio))) : '';
                $row[] = $pesquisa->data_termino ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_termino))) : '';
            }

            $row[] = '
                     <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Responder pesquisa de Clima Organizacional" onclick="edit_pesquisa(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i>' . $responderPesquisa . '</a>
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
        $sql = "SELECT a.id_modelo,
                       a.nome,
                       b.instrucoes
                FROM pesquisa a
                INNER JOIN pesquisa_modelos b ON
                           b.id = a.id_modelo
                INNER JOIN pesquisa_avaliadores c ON
                           c.id_pesquisa = a.id
                WHERE c.id = {$id}";
        $avaliador = $this->db->query($sql)->row();

        $query = "SELECT a.id,
                         b.categoria,
                         a.pergunta
                  FROM pesquisa_perguntas a
                  INNER JOIN pesquisa_categorias b ON
                             b.id = a.id_categoria
                  INNER JOIN pesquisa_modelos c ON
                             c.id = a.id_modelo
                  WHERE c.id = {$avaliador->id_modelo} 
                  ORDER BY a.id ASC";
        $perguntas = $this->db->query($query)->result();

        $data = array();

        foreach ($perguntas as $p => $pergunta) {

            $select = "SELECT a.id,
                              a.id_pergunta,
                              a.alternativa,
                              a.peso
                       FROM pesquisa_alternativas a
                       INNER JOIN pesquisa_perguntas b ON
                                  b.id_modelo = a.id_modelo AND
                                  a.id_pergunta IS NULL
                       WHERE b.id = {$pergunta->id} 
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

            $select2 = "SELECT b.id_alternativa AS resposta
                        FROM pesquisa_avaliadores a
                        LEFT JOIN pesquisa_resultado b ON
                                  b.id_avaliador = a.id
                        INNER JOIN pesquisa_perguntas c ON
                                   c.id = b.id_pergunta
                        INNER JOIN pesquisa_alternativas d ON
                                   d.id = b.id_alternativa AND 
                                   d.id in ({$questionarios})
                        WHERE c.id = {$pergunta->id} AND 
                              a.id = {$id} 
                        UNION ALL 
                        SELECT null AS resposta 
                        FROM dual 
                        LIMIT 1";

            $resposta = $this->db->query($select2)->row();

            $respostas = form_hidden("id_pergunta[$p]", $pergunta->id);
            foreach ($arrAlternativas as $k => $arrAlternativa) {
                $respostas .= '<div><label>';
                $respostas .= form_radio("id_alternativa[$p]", $k, $k == $resposta->resposta);
                $respostas .= ' ' . $arrAlternativa . " </label></div>";
            }

            $data[$p] = array($pergunta->categoria, $pergunta->pergunta, $respostas);
        }

        $output = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data,
            'title' => $avaliador->nome,
            'instrucoes' => $avaliador->instrucoes
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_save()
    {
        $id_pergunta = $this->input->post('id_pergunta');
        $id_alternativa = $this->input->post('id_alternativa');
        $avaliador = $this->input->post('id_avaliador');

        $perguntas = implode(',', $id_pergunta);
        $alternativas = implode(',', $id_alternativa);
        $delete = "DELETE FROM pesquisa_resultado 
                   WHERE id_avaliador = {$avaliador} AND NOT
                         (id_pergunta IN ({$perguntas}) AND
                          id_alternativa IN ({$alternativas}))";

        $this->db->trans_begin();
        $this->db->query($delete);

        $select = "SELECT id_pergunta
                   FROM pesquisa_resultado 
                   WHERE id_avaliador = {$avaliador}";
        $rows = $this->db->query($select)->result();

        $arrPergunta = array();
        foreach ($rows as $row) {
            $arrPergunta[] = $row->id_pergunta;
        }

        foreach ($id_pergunta as $k => $pergunta) {

            if (in_array($pergunta, $arrPergunta) == false) {
                $data = array(
                    'id_avaliador' => $avaliador,
                    'id_pergunta' => $pergunta,
                    'id_alternativa' => $id_alternativa[$k],
                    'data_avaliacao' => date('Y-m-d H:i:s')
                );

                $query = $this->db->insert_string('pesquisa_resultado', $data);
                $this->db->query($query);
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

}
