<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RecrutamentoPresencial_testes extends MY_Controller
{

//    protected $tipo_usuario = array('candidato');

    public function __construct()
    {
        parent::__construct();
    }

    public function matematica($id)
    {
        $this->iniciar('M', $id);
    }

    public function raciocinio_logico($id)
    {
        $this->iniciar('R', $id);
    }

    public function portugues($id)
    {
        $this->iniciar('P', $id);
    }

    public function lideranca($id)
    {
        $this->iniciar('L', $id);
    }

    public function perfil_personalidade($id)
    {
        $this->iniciar('C', $id);
    }

    public function digitacao($id)
    {
        $this->iniciar('D', $id);
    }

    public function interpretacao($id)
    {
        $this->iniciar('I', $id);
    }

    public function entrevista($id)
    {
        $this->iniciar('E', $id);
    }

    public function verificar_teste($id)
    {
        $sql = "SELECT (CASE WHEN (a.minutos_duracao is null or a.data_acesso is null)
                        THEN a.minutos_duracao 
                        ELSE a.minutos_duracao - TIMESTAMPDIFF(MINUTE, a.data_acesso, now())
                        END) AS tempo_duracao, 
                       DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino, 
                       a.data_acesso,
                       b.tipo
                FROM requisicoes_pessoal_testes a
                INNER JOIN recrutamento_modelos b 
                           ON b.id = a.id_modelo
                WHERE a.id = {$id}";
        $result = $this->db->query($sql)->row();

        echo json_encode($result);
    }

    private function iniciar($tipo, $id)
    {
        $empresa = $this->session->userdata('empresa');
        $candidato = $this->session->userdata('id');

        $sql = "SELECT f.id,
                       f.id_modelo,
                       f.data_inicio,
                       f.data_termino,
                       f.aleatorizacao,
                       (CASE WHEN f.data_envio is not null 
                        THEN DATE_FORMAT(f.data_envio, '%d/%m/%Y')
                        ELSE null END) AS data_envio,
                       g.tipo,
                       g.instrucoes,
                       (CASE WHEN f.data_acesso is null
                        THEN sec_to_time(f.minutos_duracao * 60 - 1) 
                        WHEN f.minutos_duracao > 0
                        THEN (CASE WHEN ((f.minutos_duracao * 60) - TIMESTAMPDIFF(SECOND, f.data_acesso, now()) - 1) > 0 
                              THEN sec_to_time((f.minutos_duracao * 60) - TIMESTAMPDIFF(SECOND, f.data_acesso, now()) - 1)
                              ELSE sec_to_time(0)
                              END)
                        ELSE NULL END) AS minutos_duracao,
                        CONCAT(a.numero, ' - ', c.nome, ': ', g.nome) AS titulo
                FROM requisicoes_pessoal a
                INNER JOIN usuarios b ON
                           b.id = a.id_empresa
                INNER JOIN empresa_cargos c ON
                           c.id = a.id_cargo
                INNER JOIN requisicoes_pessoal_candidatos d ON
                           d.id_requisicao = a.id
                INNER JOIN recrutamento_usuarios e ON
                           e.id = d.id_usuario
                INNER JOIN requisicoes_pessoal_testes f ON 
                           f.id_candidato = d.id
                INNER JOIN recrutamento_modelos g ON 
                           g.id = f.id_modelo
                LEFT JOIN requisicoes_pessoal_resultado h ON
                          h.id_teste = f.id
                WHERE e.id = {$candidato} AND 
                      b.id = {$empresa} AND 
                      f.id = '{$id}' AND 
                      g.tipo = '{$tipo}'";
        $data['teste'] = $this->db->query($sql)->row();
        if (empty($data['teste'])) {
            redirect(site_url('home'));
        }

        $this->db->trans_begin();

        $this->db->select('id, pergunta, competencia');
        $this->db->where('id_modelo', $data['teste']->id_modelo);
        if ($data['teste']->aleatorizacao === 'P' or $data['teste']->aleatorizacao === 'T') {
            $this->db->order_by('rand()');
        } else {
            $this->db->order_by('id', 'asc');
            $this->db->order_by('competencia', 'asc');
        }
        $perguntas = $this->db->get('recrutamento_perguntas')->result();

        $total = 0;
        foreach ($perguntas as $pergunta) {

            if ($tipo != 'C') {
                $sql = "SELECT a.id,
                           a.alternativa,
                           a.peso,
                           d.peso AS resposta
                    FROM recrutamento_alternativas a
                    INNER JOIN recrutamento_perguntas b ON
                               b.id = a.id_pergunta
                    LEFT JOIN requisicoes_pessoal_resultado c ON
                              c.id_alternativa = a.id AND
                              c.id_teste = {$data['teste']->id}
                    LEFT JOIN recrutamento_alternativas d ON
                              d.id = c.id_alternativa
                    WHERE a.id_pergunta = {$pergunta->id}";
            } else {
                $sql = "SELECT a.id,
                           a.alternativa,
                           a.peso,
                           d.peso AS resposta
                    FROM recrutamento_alternativas a
                    INNER JOIN recrutamento_perguntas b ON
                               b.id_modelo = a.id_modelo
                    LEFT JOIN requisicoes_pessoal_resultado c ON
                              c.id_alternativa = a.id AND
                              c.id_teste = {$data['teste']->id}
                    LEFT JOIN recrutamento_alternativas d ON
                              d.id = c.id_alternativa
                    WHERE b.id = {$pergunta->id}";
            }
            if ($data['teste']->aleatorizacao === 'A' or $data['teste']->aleatorizacao === 'T') {
                $sql .= " ORDER BY RAND()";
            }
            $rows = $this->db->query($sql)->result();
            $respostas = '';
            foreach ($rows as $row) {
                $respostas .= '<li><label style="font-weight: normal">';
                $respostas .= form_radio("alternativa[$pergunta->id]", $row->id, false);
                $respostas .= ' ' . $row->alternativa . "</label></li>";
            }
            $pergunta->alternativas = $respostas;
        }

        $setData = array('data_acesso' => date('Y-m-d H:i:s'));
        $where = "id = {$data['teste']->id} AND 
                  now() between (data_inicio AND data_termino) AND 
                  data_acesso IS NULL";

        $update = $this->db->update_string('requisicoes_pessoal_testes', $setData, $where);
        $this->db->query($update);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            redirect(site_url('home'));
        } else {
            $this->db->trans_commit();
        }

        if ($tipo == 'E') {
            $competencia = null;
            $k = -1;
            $data['competencias'] = array();
            foreach ($perguntas as $pergunta) {
                if ($pergunta->competencia != $competencia) {
                    $competencia = $pergunta->competencia;
                    $k++;
                }
                $data['competencias'][$k][] = $pergunta;
            }
        }
        $data['perguntas'] = $perguntas;

        $data['teste']->total = round($total, 2);
        $data['tempo_restante'] = $data['teste']->minutos_duracao;

        $this->load->view('recrutamentoPresencial_exame', $data);
    }

    public function texto_exemplo($id)
    {
        $sql = "SELECT a.pergunta, b.tipo 
                FROM recrutamento_perguntas a 
                INNER JOIN recrutamento_modelos b ON 
                           b.id = a.id_modelo 
                INNER JOIN requisicoes_pessoal_testes c ON 
                           c.id_modelo = b.id 
                WHERE c.id = {$id} AND 
                      (b.tipo = 'D' OR b.tipo = 'I')";
        $row = $this->db->query($sql)->row();
        $strTexto = $row ? nl2br($row->pergunta) : '';
        $arrTexto = explode('<br />', $strTexto);

        $html = "<!DOCTYPE html>
                <html>
                    <head> 
                        <link href=\"<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>\" rel='stylesheet'>
                    </head>                     
                    <body oncontextmenu='return false' onselectstart='return false' ondragstart='return false'>
                        <div style='font-family: open-sans; text-align: justify;'>";
        foreach ($arrTexto as $texto) {
            $html .= empty(trim($texto)) ? '<br/>' : "<div style='text-indent: 23px;'>" . $texto . "</div>";
        }
        $html .= "</div>
                    </body>
                </html>";
        echo $html;

//        $font = getenv('windir').'/Fonts/arial.ttf';
//        $im = imagecreate(300, 300);
//
//        // fundo branco e texto azul
//        $bg = imagecolorallocate($im, 255, 255, 255);
//        $textcolor = imagecolorallocate($im, 0, 0, 0);
//
//        // escreve a string em cima na esquerda
//        imagestring($im, 4, 1, 1, $texto, $textcolor);
//
//        // envia a imagem
//        header("Content-type: image/png");
//        echo imagepng($im);
    }

    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $data['candidato'] = $this->session->userdata('id');
        $data['candidato'] = '';
        $data['nome_candidato'] = '';
        $this->load->view('recrutamento', $data);
    }

    public function ajax_list($teste = null)
    {
        $tipo = '';
        switch ($teste) {
            case 'matematica':
                $tipo = 'M';
                break;
            case 'raciocinio-logico':
                $tipo = 'R';
                break;
            case 'portugues':
                $tipo = 'P';
                break;
            case 'lideranca':
                $tipo = 'L';
                break;
            case 'perfil-personalidade':
                $tipo = 'C';
                break;
            case 'digitacao':
                $tipo = 'D';
                break;
            case 'interpretacao':
                $tipo = 'I';
                break;
            case 'entrevista':
                $tipo = 'E';
                break;
        }
        $empresa = $this->session->userdata('empresa');
        $candidato = $this->session->userdata('id');
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.processo,
                       s.cargo,
                       s.teste,
                       s.data_inicio, 
                       s.data_termino, 
                       s.data_valida,
                       s.data_inicio_abrev, 
                       s.data_termino_abrev,
                       s.tipo
                FROM (SELECT f.id, 
                             a.nome AS processo, 
                             c.cargo, 
                             g.nome AS teste, 
                             DATE_FORMAT(f.data_inicio, '%d/%m/%Y') AS data_inicio, 
                             DATE_FORMAT(f.data_termino, '%d/%m/%Y') AS data_termino,
                             DATE_FORMAT(f.data_inicio, '%d/%m/%y') AS data_inicio_abrev, 
                             DATE_FORMAT(f.data_termino, '%d/%m/%y') AS data_termino_abrev,
                             (CASE WHEN (now() BETWEEN f.data_inicio AND f.data_termino) AND f.data_acesso is null AND f.data_envio is null
                              THEN 'ok' 
                              WHEN (now() BETWEEN f.data_inicio AND f.data_termino) AND (f.minutos_duracao * 60 > TIMESTAMPDIFF(SECOND, f.data_acesso, now())) AND f.data_envio is null
                              THEN 'executando'  
                              WHEN now() < f.data_inicio
                              THEN 'espera' 
                              WHEN now() > f.data_termino
                              THEN 'expirada' 
                              WHEN (f.minutos_duracao * 60 < TIMESTAMPDIFF(SECOND, f.data_acesso, now()))
                              THEN 'esgotado' 
                              WHEN f.data_envio is not null
                              THEN 'concluido' 
                              ELSE '' END) AS data_valida,
                              '1' as tipo
                      FROM recrutamento a
                      INNER JOIN usuarios b ON
                                 b.id = a.id_usuario_EMPRESA
                      INNER JOIN recrutamento_cargos c ON
                                 c.id_recrutamento = a.id
                      INNER JOIN recrutamento_candidatos d ON
                                 d.id_cargo = c.id
                      INNER JOIN recrutamento_usuarios e ON
                                 e.id = d.id_usuario
                      INNER JOIN recrutamento_testes f ON 
                                f.id_candidato = d.id
                      INNER JOIN recrutamento_modelos g ON
                                g.id = f.id_modelo
                      WHERE e.id = {$candidato} AND 
                            b.id = {$empresa} AND 
                            (g.tipo = '{$tipo}' OR CHAR_LENGTH('{$tipo}') = 0)
                            
                      UNION
                      
                      SELECT f.id, 
                             a.numero AS processo, 
                             c.nome AS cargo, 
                             g.nome AS teste, 
                             DATE_FORMAT(f.data_inicio, '%d/%m/%Y') AS data_inicio, 
                             DATE_FORMAT(f.data_termino, '%d/%m/%Y') AS data_termino,
                             DATE_FORMAT(f.data_inicio, '%d/%m/%y') AS data_inicio_abrev, 
                             DATE_FORMAT(f.data_termino, '%d/%m/%y') AS data_termino_abrev,
                             (CASE WHEN (now() BETWEEN f.data_inicio AND f.data_termino) AND f.data_acesso is null AND f.data_envio is null
                              THEN 'ok' 
                              WHEN (now() BETWEEN f.data_inicio AND f.data_termino) AND (f.minutos_duracao * 60 > TIMESTAMPDIFF(SECOND, f.data_acesso, now())) AND f.data_envio is null
                              THEN 'executando'  
                              WHEN now() < f.data_inicio
                              THEN 'espera' 
                              WHEN now() > f.data_termino
                              THEN 'expirada' 
                              WHEN (f.minutos_duracao * 60 < TIMESTAMPDIFF(SECOND, f.data_acesso, now()))
                              THEN 'esgotado' 
                              WHEN f.data_envio is not null
                              THEN 'concluido' 
                              ELSE '' END) AS data_valida,
                              '2' as tipo
                      FROM requisicoes_pessoal a
                      INNER JOIN usuarios b ON
                                 b.id = a.id_empresa
                      INNER JOIN empresa_cargos c ON
                                 c.id = a.id_cargo
                      INNER JOIN requisicoes_pessoal_candidatos d ON
                                 d.id_requisicao = a.id
                      INNER JOIN recrutamento_usuarios e ON
                                 e.id = d.id_usuario
                      INNER JOIN requisicoes_pessoal_testes f ON 
                                f.id_candidato = d.id
                      INNER JOIN recrutamento_modelos g ON
                                g.id = f.id_modelo
                      WHERE e.id = {$candidato} AND 
                            b.id = {$empresa} AND 
                            (g.tipo = '{$tipo}' OR CHAR_LENGTH('{$tipo}') = 0)) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.processo', 's.cargo', 's.teste', 's.data_inicio', 's.data_termino');
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
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        $is_mobile = $this->agent->is_mobile();
        foreach ($list as $recrutamento) {
            $row = array();

            switch ($recrutamento->data_valida) {
                case 'ok':
                    if ($recrutamento->tipo === '2') {
                        $row[] = '
                                  <a class="btn btn-sm btn-success btn-block" href="javascript:void(0)" title="Iniciar teste" onclick="verificar_teste2(' . "'" . $recrutamento->id . "'" . ')">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Realizar teste</a>
                                 ';
                    } else {
                        $row[] = '
                                  <a class="btn btn-sm btn-success btn-block" href="javascript:void(0)" title="Iniciar teste" onclick="verificar_teste(' . "'" . $recrutamento->id . "'" . ')">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Realizar teste</a>
                                 ';
                    }
                    break;
                case 'executando':
                    if ($recrutamento->tipo === '2') {
                        $row[] = '
                                  <a class="btn btn-sm btn-success btn-block" href="javascript:void(0)" title="Iniciar teste" onclick="verificar_teste2(' . "'" . $recrutamento->id . "'" . ')">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Finalizado</a>
                                 ';
                    } else {
                        $row[] = '
                                  <a class="btn btn-sm btn-success btn-block" href="javascript:void(0)" title="Iniciar teste" onclick="verificar_teste(' . "'" . $recrutamento->id . "'" . ')">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Finalizado</a>
                                 ';
                    }
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

            $row[] = $recrutamento->processo;
            $row[] = $recrutamento->cargo;
            $row[] = $recrutamento->teste;
            if ($is_mobile) {
                $row[] = $recrutamento->data_inicio_abrev;
                $row[] = $recrutamento->data_termino_abrev;
            } else {
                $row[] = $recrutamento->data_inicio;
                $row[] = $recrutamento->data_termino;
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

    public function finalizar($id_teste)
    {
        $this->db->where('id', $this->session->userdata('id'));
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $row = $this->db->get('recrutamento_usuarios')->row();
        if (!($row && in_array($this->session->userdata('tipo'), ['candidato', 'candidato_externo']))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Acesso negado!')));
        }

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
        $rowsPeso = $this->db->get('recrutamento_alternativas')->result();
        $peso = array();
        foreach ($rowsPeso as $rowPeso) {
            $peso[$rowPeso->id] = $rowPeso->peso;
        }

        $data_envio = date('Y-m-d H:i:s');

        $this->db->trans_start();

        $this->db->where('id_teste', $id_teste);
        $this->db->delete('requisicoes_pessoal_resultado');

        $data = array();
        if ($alternativas) {
            foreach ($alternativas as $pergunta => $id_alternativa) {
                $this->db->select_max('peso');
                $this->db->where('id_pergunta', $pergunta);
                $peso_max = $this->db->get('recrutamento_alternativas')->row();

                $this->db->select('peso');
                $this->db->where('id', $id_alternativa);
                $nota = $this->db->get('recrutamento_alternativas')->row();

                $data[] = array(
                    'id_teste' => $id_teste,
                    'id_pergunta' => $pergunta,
                    'peso_max' => $peso_max->peso ?? null,
                    'id_alternativa' => $id_alternativa,
                    'valor' => ($valor[$pergunta] ?? null),
                    'resposta' => $peso[$id_alternativa],
                    'nota' => $nota->peso ?? null,
                    'data_avaliacao' => $data_envio
                );
            }
        } elseif ($respostas) {
            foreach ($respostas as $pergunta => $resposta) {

                $this->db->select('a.pergunta, b.tipo');
                $this->db->join('recrutamento_modelos b', 'b.id = a.id_modelo');
                $this->db->where('a.id', $pergunta);
                $nota = $this->db->get('recrutamento_perguntas a')->row();
                if ($nota->tipo == 'D') {
                    similar_text($nota->pergunta, $resposta, $percent);
                    $percent = $resposta !== null ? number_format($percent, 1, ',', '') : null;
                } else {
                    $percent = null;
                }

                $data[] = array(
                    'id_teste' => $id_teste,
                    'id_pergunta' => $pergunta,
                    'peso_max' => 100,
                    'valor' => ($valor[$pergunta] ?? null),
                    'resposta' => $resposta,
                    'nota' => $percent,
                    'data_avaliacao' => $data_envio
                );
            }
        }

        if (count($data)) {
            $this->db->insert_batch('requisicoes_pessoal_resultado', $data);
        }


        if ($this->db->trans_status() !== false) {
            $this->db->update('requisicoes_pessoal_testes', array('data_envio' => $data_envio), array('id' => $id_teste));
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function avaliar()
    {
        $id_teste = $this->input->post('id_teste');
        $post = $this->input->post('nota');

        $this->db->select('d.id, c.id AS id_pergunta');
        $this->db->join('recrutamento_modelos b', 'b.id = a.id_modelo');
        $this->db->join('recrutamento_perguntas c', 'b.id = c.id_modelo');
        $this->db->join('requisicoes_pessoal_resultado d', 'a.id = d.id_teste AND d.id_pergunta = c.id', 'left');
        $this->db->where('a.id', $id_teste);
        $this->db->where_in('c.id', array_keys($post));
        $rows = $this->db->get('requisicoes_pessoal_testes a')->result();

        $resultados = array();
        foreach ($rows as $row) {
            $resultados[$row->id_pergunta] = $row->id;
        }

        $data = $data2 = $arrNota = array();

        $this->db->trans_start();

        foreach ($post as $id_pergunta => $nota) {
            if ($resultados[$id_pergunta]) {
                $data[] = array(
                    'id' => $resultados[$id_pergunta],
                    'nota' => (strlen($nota) > 0 ? $nota : null),
                    'data_avaliacao' => date('Y-m-d H:i:s'));
            } else {
                $data2[] = array(
                    'id_teste' => $id_teste,
                    'id_pergunta' => $id_pergunta,
                    'nota' => (strlen($nota) > 0 ? $nota : null),
                    'data_avaliacao' => date('Y-m-d H:i:s'));
            }

            $arrNota[] = $nota;
        }


        if ($data) {
            $this->db->update_batch('requisicoes_pessoal_resultado', $data, 'id');
        }
        if ($data2) {
            $this->db->insert_batch('requisicoes_pessoal_resultado', $data2);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao atualizar nota.')));
        }

        $total = number_format(array_sum($arrNota) / max(count($arrNota), 1), 1, ',', '');
        echo json_encode(array("total" => $total));
    }

}
