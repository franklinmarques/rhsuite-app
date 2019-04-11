<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_eneagrama extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Avaliacaoexp_model', 'avaliacaoexp');
    }

    public function index()
    {
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('pesquisa_eneagrama1', $data);
    }

    public function gerenciar($id = null)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $pesquisa = $this->db->get_where('pesquisa_modelos', array('id' => $id))->row();
        $data['id_modelo'] = $pesquisa->id;
        $data['modelo'] = $pesquisa->nome;
        switch ($pesquisa->tipo) {
            case 'C':
                $data['tipo'] = 'de clima';
                break;
            case 'E':
                $data['tipo'] = 'de personalidade';
                break;
            case 'P':
                $data['tipo'] = 'de perfil';
                break;
            default:
                $data['tipo'] = 'de clima';
        }
        $data['id_usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('pesquisa_eneagrama1', $data);
    }

    public function ajax_list($id)
    {
        if (empty($id)) {
            $id = $this->session->userdata('empresa');
        }

        if ($this->session->userdata('tipo') != 'empresa') {
            $id_usuario = $this->session->userdata('id');
        } else {
            $id_usuario = '';
        }

        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.avaliador,
                       s.modelo,
                       s.tipo,
                       s.data_inicio,
                       s.data_termino,
                       s.data_valida
                FROM (SELECT c.id, 
                             a.nome,
                             d.nome AS avaliador,
                             b.nome AS modelo,
                             (case b.tipo 
                              when 'E' then 'Personalidade' 
                              else '' end) AS tipo,
                             a.data_inicio,
                             a.data_termino,
                             (CASE WHEN (now() BETWEEN a.data_inicio AND a.data_termino) AND COUNT(e.id_avaliador) = 0
                              THEN 'ok'  
                              WHEN now() < a.data_inicio
                              THEN 'espera' 
                              WHEN now() > a.data_termino
                              THEN 'expirada' 
                              WHEN COUNT(e.id_avaliador) > 0
                              THEN 'concluido' 
                              ELSE '' END) AS data_valida
                      FROM pesquisa a
                      INNER JOIN pesquisa_modelos b ON 
                                 b.id = a.id_modelo
                      LEFT JOIN pesquisa_avaliadores c
                                ON c.id_pesquisa = a.id
                                AND b.tipo = 'E'
                      LEFT JOIN usuarios d
                                ON d.id = c.id_avaliador
                      LEFT JOIN pesquisa_resultado e 
                                ON e.id_avaliador = c.id
                      WHERE b.id_usuario_EMPRESA = {$id}
                            AND ('{$id_usuario}' = c.id_avaliador OR CHAR_LENGTH('{$id_usuario}') = 0)
                            AND b.tipo = 'E'
                      GROUP BY c.id) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.modelo', 's.data_inicio', 's.data_termino');
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
        foreach ($list as $pesquisa) {
            $row = array();
            $row[] = $pesquisa->nome;
            $row[] = $pesquisa->modelo;
            $row[] = $pesquisa->data_inicio ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_inicio))) : '';
            $row[] = $pesquisa->data_termino ? date("d/m/Y", strtotime(str_replace('-', '/', $pesquisa->data_termino))) : '';

            switch ($pesquisa->data_valida) {
                case 'ok':
                    $row[] = '
                              <a class="btn btn-sm btn-success btn-block" href="pesquisa_eneagrama/teste/' . $pesquisa->id . '" target="_blank" title="Iniciar teste">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Iniciar</a>
                             ';
                    break;
                case 'executando':
                    $row[] = '
                              <a class="btn btn-sm btn-success btn-block" href="pesquisa_eneagrama/teste/' . $pesquisa->id . '" target="_blank" title="Iniciar teste">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Finalizado</a>
                             ';
                    break;
                case 'espera':
                    $row[] = '
                              <a class="btn btn-sm btn-warning btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Data agendada&nbsp;</a>
                             ';
                    break;
                case 'expirada':
                    $row[] = '
                              <a class="btn btn-sm btn-danger btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Data expirada&nbsp;</a>
                             ';
                    break;
                case 'esgotado':
                    $row[] = '
                              <a class="btn btn-sm btn-danger btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Tempo esgotaddo&nbsp;</a>
                             ';
                    break;
                case 'concluido':
                    $row[] = '
                              <a class="btn btn-sm btn-success btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Teste concluído&nbsp;</a>
                             ';
                    break;
                default:
                    $row[] = '
                              <a class="btn btn-sm btn-success btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Iniciar</a>
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

    public function teste($id)
    {
        $empresa = $this->session->userdata('empresa');
        $usuario = $this->session->userdata('id');

        $sql = "SELECT a.id,
                       c.id_modelo,
                       c.data_inicio,
                       c.data_termino,
                       d.instrucoes,
                       d.tipo,
                       e.data_avaliacao
                FROM pesquisa_avaliadores a
                INNER JOIN usuarios b ON
                           b.id = a.id_avaliador
                INNER JOIN pesquisa c ON
                           c.id = a.id_pesquisa
                INNER JOIN pesquisa_modelos d ON
                           d.id = c.id_modelo
                LEFT JOIN pesquisa_resultado e ON
                          e.id_avaliador = a.id
                WHERE b.id = {$usuario} AND 
                      d.id_usuario_EMPRESA = {$empresa} AND 
                      a.id = '{$id}'";
        $data['teste'] = $this->db->query($sql)->row();
        if (empty($data['teste'])) {
            redirect(site_url('home'));
        }

        $this->db->trans_begin();

        $this->db->select('id, pergunta');
        $this->db->where('id_modelo', $data['teste']->id_modelo);
        $this->db->order_by('id', 'asc');
        $perguntas = $this->db->get('pesquisa_perguntas')->result();

        $total = 0;
        foreach ($perguntas as $pergunta) {
            $sql = "SELECT a.id,
                           a.alternativa,
                           a.peso,
                           d.peso AS resposta
                    FROM pesquisa_alternativas a
                    INNER JOIN pesquisa_modelos b ON
                               b.id = a.id_modelo
                    LEFT JOIN pesquisa_resultado c ON
                              c.id_alternativa = a.id AND
                              c.id_avaliador = {$data['teste']->id}
                    LEFT JOIN pesquisa_alternativas d ON
                              d.id = c.id_alternativa
                    WHERE a.id_pergunta IS NULL 
                          AND a.id_modelo = {$data['teste']->id_modelo}";
            $rows = $this->db->query($sql)->result();

            $respostas = '';
            foreach ($rows as $row) {
                $respostas .= '<li><label style="font-weight: normal">';
                $respostas .= form_radio("alternativa[$pergunta->id]", $row->id, false);
                $respostas .= ' ' . $row->alternativa . "</label></li>";
            }
            $pergunta->alternativas = $respostas;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            redirect(site_url('home'));
        } else {
            $this->db->trans_commit();
        }

        $data['perguntas'] = $perguntas;

        $data['teste']->total = round($total, 2);
        $data['tempo_restante'] = null;

        $this->load->view('pesquisa_eneagrama_teste', $data);
    }

    public function finalizar($id_avaliador)
    {
        $arrPergunta = $this->input->post('pergunta');
        $alternativas = $this->input->post('alternativa');
        $valor = $this->input->post('valor');
        $arrRespostas = $this->input->post('resposta');
        $respostas = array();
        if (is_array($arrRespostas)) {
            foreach ($arrRespostas as $pergunta1 => $resposta1) {
                if (strlen($resposta1) > 0) {
                    $respostas[$pergunta1] = $resposta1;
                }
            }
        } elseif ($arrRespostas) {
            $respostas[$arrPergunta] = $arrRespostas;
        }

        $this->db->select('id, peso');
        $this->db->where_in('id', $alternativas);
        $rowsPeso = $this->db->get('pesquisa_alternativas')->result();
        $peso = array();
        foreach ($rowsPeso as $rowPeso) {
            $peso[$rowPeso->id] = $rowPeso->peso;
        }

        $data_envio = date('Y-m-d H:i:s');

        $this->db->trans_start();

        $this->db->where('id_avaliador', $id_avaliador);
        $this->db->delete('pesquisa_resultado');

        $data = array();
        if ($alternativas) {
            foreach ($alternativas as $pergunta => $id_alternativa) {
                $data[] = array(
                    'id_avaliador' => $id_avaliador,
                    'id_pergunta' => $pergunta,
                    'id_alternativa' => $id_alternativa,
                    'valor' => ($valor[$pergunta] ?? null),
                    'resposta' => $peso[$id_alternativa],
                    'data_avaliacao' => $data_envio
                );
            }
        } elseif ($respostas) {
            foreach ($respostas as $pergunta => $resposta) {
                $data[] = array(
                    'id_avaliador' => $id_avaliador,
                    'id_pergunta' => $pergunta,
                    'valor' => ($valor[$pergunta] ?? null),
                    'resposta' => $resposta,
                    'data_avaliacao' => $data_envio
                );
            }
        }

        $this->db->insert_batch('pesquisa_resultado', $data);

        /* if ($this->db->trans_status() !== false) {
             $this->db->update('pesquisa_avaliadores', array('data_envio' => $data_envio), array('id' => $id_avaliador));
         }*/

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_listResposta($id)
    {
        if (empty($id)) {
            $id = $this->uri->rsegment(3);
        }

        $post = $this->input->post();

        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM pesquisa_alternativas a
                INNER JOIN pesquisa_modelos b ON
                           b.id = a.id_modelo
                WHERE a.id_modelo = {$id} AND
                      a.id_pergunta IS NULL 
                GROUP by a.id";

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        $sql .= " 
                LIMIT 0, 6";

        $list = $this->db->query($sql)->result();

        $recordsTotal = count($list);

        $data = array();

        foreach ($list as $pesquisa) {
            $data[] = array($pesquisa->alternativa, $pesquisa->peso);
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $data,
        );

        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $this->db->where('id', $id);
        $data = $this->db->get('pesquisa_perguntas')->row();

        echo json_encode($data);
    }

    public function ajax_editResposta($id)
    {
        $sql = "SELECT a.id,
                       a.alternativa,
                       a.peso
                FROM pesquisa_alternativas a
                INNER JOIN pesquisa_modelos b ON
                           b.id = a.id_modelo
                WHERE b.id = {$id}
                LIMIT 0, 6";

        $data = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'tipo_resposta' => 'N',
            'prefixo_resposta' => $this->input->post('prefixo_resposta'),
            'valor_min' => 1,
            'valor_max' => 5
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
        }

        $this->db->trans_begin();

        $this->db->query($this->db->insert_string('pesquisa_perguntas', $data));

        $this->db->trans_complete();
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
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'pergunta' => $this->input->post('pergunta'),
            'prefixo_resposta' => $this->input->post('prefixo_resposta')
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de pesquisa não foi encontrado')));
        }

        $this->db->trans_begin();

        $update_string = $this->db->update_string('pesquisa_perguntas', $data, array('id' => $this->input->post('id')));
        $this->db->query($update_string);

        $this->db->trans_complete();
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
        $this->db->trans_begin();
        $this->db->query("DELETE FROM pesquisa_perguntas WHERE id = ?", $id);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updateResposta()
    {
        $id_modelo = $this->input->post('id_modelo');

        if (empty($id_modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de avaliação não foi encontrado')));
        }

        $id_alternativas = $this->input->post('id_alternativa');

        $alternativas = $this->input->post('alternativa');

        $peso = $this->input->post('peso');

        $this->db->trans_begin();

        foreach ($alternativas as $k => $alternativa) {

            $data = array(
                'id_modelo' => $id_modelo,
                'alternativa' => $alternativa,
                'peso' => $peso[$k]
            );

            if ($alternativa) {

                if ($id_alternativas[$k]) {

                    $where = array(
                        'id' => $id_alternativas[$k],
                        'id_modelo' => $id_modelo
                    );

                    $update_string = $this->db->update_string('pesquisa_alternativas', $data, $where);
                    $this->db->query($update_string);
                } else {

                    $insert_string = $this->db->insert_string('pesquisa_alternativas', $data);
                    $this->db->query($insert_string);
                }
            } elseif ($id_alternativas[$k]) {

                $this->db->query("DELETE FROM pesquisa_alternativas WHERE id = $id_alternativas[$k]");
            }
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }


    public function relatorio($id, $pdf = false)
    {
        $this->db->select('id, foto, foto_descricao');
        $empresa = $this->db->get_where('usuarios', array('id' => $this->session->userdata('empresa')))->row();
        $data['foto'] = 'imagens/usuarios/' . $empresa->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $empresa->foto_descricao;

        $sql = "SELECT c.nome, 
                       c.id AS id_teste,
                       d.id AS id_modelo, 
                       d.nome AS modelo,
                       DATE_FORMAT(c.data_inicio, '%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(c.data_termino, '%d/%m/%Y') AS data_termino,
                       b.nome AS candidato, 
                       b.cargo, 
                       b.funcao
                FROM pesquisa_avaliadores a
                INNER JOIN usuarios b ON 
                           b.id = a.id_avaliador
                INNER JOIN pesquisa c ON
                           c.id = a.id_pesquisa
                INNER JOIN pesquisa_modelos d ON
                           d.id = c.id_modelo
                WHERE a.id = {$id}";
        $data['teste'] = $this->db->query($sql)->row();


        $this->db->select('a.tipo_eneagrama, b.peso');
        $this->db->join('pesquisa_resultado c', "c.id_pergunta = a.id AND c.id_avaliador = '{$id}'", 'left');
        $this->db->join('pesquisa_alternativas b', 'b.id_modelo = a.id_modelo AND b.id = c.id_alternativa', 'left');
        $this->db->where('a.id_modelo', $data['teste']->id_modelo);
        $this->db->group_by('a.id');
        $perguntas = $this->db->get('pesquisa_perguntas a')->result();

        $eneagramas = array(
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0
        );
        foreach ($perguntas as $pergunta) {
            if (isset($eneagramas[$pergunta->tipo_eneagrama])) {
                $eneagramas[$pergunta->tipo_eneagrama] += $pergunta->peso;
            }
        }

        $data['eneagrama'] = $eneagramas;
        $tipos = array(
            '1' => 'Perfeccionista',
            '2' => 'Prestativo',
            '3' => 'Competitivo',
            '4' => 'Romântico',
            '5' => 'Observador',
            '6' => 'Questionador',
            '7' => 'Sonhador',
            '8' => 'Confrontador',
            '9' => 'Preservacionista'
        );
        $data['tipos'] = array_intersect_key($tipos, array_filter($eneagramas));

        $notas_maximas = array();
        $notas_maximas2 = array();
        $notas_maximas3 = array();
        arsort($eneagramas);
        foreach ($eneagramas as $id_comp => $eneagrama) {
            if ($eneagrama >= max($eneagramas)) {
                $notas_maximas[$id_comp] = $eneagrama;
            } elseif ($eneagrama >= max(array_diff($eneagramas, $notas_maximas))) {
                $notas_maximas2[$id_comp] = $eneagrama;
            } elseif ($eneagrama >= max(array_diff($eneagramas, $notas_maximas, $notas_maximas2))) {
                $notas_maximas3[$id_comp] = $eneagrama;
            } else {
                break;
            }
        }

        $data['notas_maximas'] = array_keys($notas_maximas);
        $data['notas_maximas2'] = array_keys($notas_maximas2);
        $data['notas_maximas3'] = array_keys($notas_maximas3);
        $descritivos = array_merge($data['notas_maximas'], $data['notas_maximas2'], $data['notas_maximas3']);
        //$data['descritivos'] = array_intersect($descritivos, array_keys(array_filter($eneagramas)));
        $data['descritivos'] = array_intersect($data['notas_maximas'], array_keys(array_filter($eneagramas)));


        if ($pdf) {
            return $this->load->view('pesquisa_pdfEneagrama', $data, true);
        } else {
            $this->load->view('pesquisa_relatorioEneagrama', $data);
        }
    }

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.recrutamento thead th { font-size: 14px; padding: 5px; text-align: center; font-weight: bold; } ';
        $stylesheet .= 'table.recrutamento thead tr, table.recrutamento tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.recrutamento tbody tr th { font-size: 13px; padding: 2px; } ';
        $stylesheet .= 'table.recrutamento tbody tr:nth-child(2) td { border-top: 1px solid #ddd; } ';
        $stylesheet .= 'table.recrutamento tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td strong { font-weight: bold; } ';
        $stylesheet .= 'table.recrutamento { border-bottom: 5px solid #ddd; } ';

        $stylesheet .= 'table.resultado tr th, table.resultado tr td { font-size: 13px; padding: 5px; } ';
        $stylesheet .= 'table.resultado thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.resultado thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.resultado tbody tr td.success { background-color: #dff0d8; } ';
        $stylesheet .= 'table.resultado tbody tr td:nth-child(1) { vertical-align: top; word-wrap: break-word; width: 45%; } ';
        $stylesheet .= 'table.resultado tbody tr td:nth-child(2) { vertical-align: top; word-wrap: break-word;  width: 45%; word-break: break-all; } ';

        $this->m_pdf->pdf->setTopMargin(60);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));

        $sql = "SELECT c.nome
                FROM pesquisa a
                INNER JOIN pesquisa_avaliadores b ON
                           b.id_pesquisa = a.id
                INNER JOIN usuarios c ON 
                           c.id = b.id_avaliador
                WHERE b.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output("Eneagrama - {$row->nome}.pdf", 'D');
    }

}
