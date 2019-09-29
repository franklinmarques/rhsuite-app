<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Avaliacaoexp_avaliados extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Pesquisa_model', 'pesquisa');
//        $this->load->model('Funcionarios_model', 'funcionarios');
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $sql = "SELECT a.id,
                       b.id as modelo,
                       b.nome,
                       (case b.tipo 
                        when 'A' then '1' 
                        when 'D' then '3' 
                        when 'P' then '2'
                        else '' end) AS tipo,
                        DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio, 
                        DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino
                FROM avaliacaoexp a 
                INNER JOIN avaliacaoexp_modelos b ON 
                           b.id = a.id_modelo 
                WHERE a.id = {$this->uri->rsegment(3, 0)}";
        $avaliacao = $this->db->query($sql)->row();
        if (count($avaliacao) == 0) {
            show_404();
        }

        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa}
                          AND status = 1
                          AND NOT ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }

        $data['empresa'] = $empresa;
        $data['titulo'] = $avaliacao->nome;
        $data['id_modelo'] = $avaliacao->modelo;
        $data['id_avaliacao'] = $avaliacao->id;
        $data['id_avaliado'] = '';
        $data['tipo'] = $avaliacao->tipo;
        $data['data_inicio'] = $avaliacao->data_inicio;
        $data['data_termino'] = $avaliacao->data_termino;

        $this->db->select('nome, id');
        $this->db->where('empresa', $empresa);
        $this->db->where('status', 1);
        $this->db->order_by('nome', 'ASC');
        $avaliadores = $this->db->get('usuarios')->result();
        $data['modelos'] = $this->db->get_where('avaliacaoexp_modelos', array('tipo' => $data['tipo']))->result();

        $data['colaboradores'] = array('' => 'selecione...');
        $data['colaboradoresBatch'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['colaboradores'][$avaliador->id] = $avaliador->nome;
            $data['colaboradoresBatch'][$avaliador->id] = $avaliador->nome;
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
                       s.cargo,
                       s.depto
                FROM (SELECT a.id, 
                             b.nome, 
                             CONCAT_WS('/', trim(b.cargo), trim(b.funcao)) AS cargo,
                             CONCAT_WS('/', trim(b.depto), trim(b.area), trim(b.setor)) AS depto
                      FROM avaliacaoexp_avaliados a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_avaliado
                      INNER JOIN avaliacaoexp c ON 
                                 c.id = a.id_avaliacao
                      INNER JOIN avaliacaoexp_modelos d ON 
                                 d.id = c.id_modelo AND 
                                 (d.tipo = 'A' OR d.tipo = 'D')
                      WHERE a.id_avaliacao = {$id}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.cargo', 's.depto');
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
            $row[] = $avaliacaoexp->cargo;
            $row[] = $avaliacaoexp->depto;
            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Gerenciar avaliadores" onclick="edit_avaliado(' . "'" . $avaliacaoexp->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Gerenciar avaliadores</a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_avaliado(' . "'" . $avaliacaoexp->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('avaliacaoexp_avaliados/relatorio/' . $avaliacaoexp->id) . '" title="Relatório de avaliação"><i class="glyphicon glyphicon-list-alt"></i> Relatório</a>
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
        $row = $this->db->get_where('avaliacaoexp_avaliados', array('id' => $id))->row();
        $data['id'] = $row->id;
        $data['id_modelo'] = $row->id_modelo;
        $data['id_avaliado'] = $row->id_avaliado;
        $data['id_supervisor'] = $row->id_supervisor;
        $data['data_atividades'] = date("d/m/Y", strtotime(str_replace('-', '/', $row->data_atividades)));
        $data['nota_corte'] = $row->nota_corte;
        $data['id_avaliacao'] = $row->id_avaliacao;

        $avaliadores = $this->db->get_where('avaliacaoexp_avaliadores', array('id_avaliado' => $row->id))->result();
        $data['avaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['avaliadores'][] = array(
                'id' => $avaliador->id,
                'id_avaliador' => $avaliador->id_avaliador,
                'data_avaliacao' => date("d/m/Y", strtotime(str_replace('-', '/', $avaliador->data_avaliacao)))
            );
        }

        echo json_encode($data);
    }

    public function ajax_avaliados($id)
    {
        $sql = "SELECT a.id 
                FROM usuarios a
                INNER JOIN avaliacaoexp_avaliados b ON 
                           b.id_avaliado = a.id
                WHERE a.empresa = {$this->session->userdata('empresa')} AND 
                      b.id_avaliacao = {$id}
                ORDER BY nome ASC";
        $avaliados = $this->db->query($sql)->result();

        $data = array();
        foreach ($avaliados as $avaliado) {
            $data[] = $avaliado->id;
        }
        echo json_encode($data);
    }

    public function ajax_avaliadores()
    {
        $where = array_filter($this->input->post());
        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('status', 1);
        $this->db->order_by('nome', 'ASC');
        $rows = $this->db->get_where('usuarios', $where)->result();
        $options = array();
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $data['avaliado'] = form_dropdown('id_avaliado', array('' => 'selecione...') + $options, '', 'id="id_avaliado" class="form-control"');
        $data['supervisor'] = form_dropdown('id_supervisor', array('' => 'selecione...') + $options, '', 'id="id_supervisor" class="form-control"');
        $data['avaliador'] = form_dropdown('avaliador[]', array('' => 'selecione...') + $options, '', 'class="form-control"');
        $data['avaliador1'] = '';
        $data['avaliador2'] = '';
        $data['avaliador3'] = '';

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $modelo = $this->input->post('id_modelo');
        if (empty($modelo)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de avaliação não foi encontrado')));
        }

        $avaliados = $this->input->post('id_avaliado');
        if (is_string($avaliados)) {
            $avaliados = array($avaliados);
        }

        $id_supervisor = $this->input->post('id_supervisor');
        if (strlen($id_supervisor) == 0) {
            $id_supervisor = null;
        }

        $id_avaliacao = $this->input->post('id_avaliacao');
        if (strlen($id_avaliacao) == 0) {
            $id_avaliacao = null;
        }

        $dataAtividades = date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post('data_atividades'))));
        $notaCorte = $this->input->post('nota_corte');

        $id_avaliados = array();

        $this->db->trans_start();

        foreach ($avaliados as $avaliado) {
            $data = array(
                'id_avaliado' => $avaliado,
                'id_modelo' => $modelo,
                'id_supervisor' => $id_supervisor,
                'id_avaliacao' => $id_avaliacao,
                'data_atividades' => $dataAtividades,
                'nota_corte' => $notaCorte
            );

            $this->db->insert('avaliacaoexp_avaliados', $data);

            $id_avaliados[] = $this->db->insert_id();
        }

        $avaliadores = array_filter($this->input->post('avaliador'));
        $data_avaliacao = $this->input->post('data_avaliacao');
        $eventos = array();

        foreach ($id_avaliados as $id_avaliado) {
            foreach ($avaliadores as $k => $avaliador) {
                $data = array(
                    'id_avaliado' => $id_avaliado,
                    'id_avaliador' => $avaliador,
                    'data_avaliacao' => date("Y-m-d", strtotime(str_replace('/', '-', $data_avaliacao[$k])))
                );
                $this->db->insert('avaliacaoexp_avaliadores', $data);
                $id = $this->db->insert_id();
                if ($this->db->affected_rows() > 0) {
                    $eventos[$id_avaliado][$id] = null;
                }
            }
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();
        $result['status'] = $status !== false;

        if ($status !== FALSE) {
            foreach ($eventos as $id_avaliado => $evento) {
                $getAvaliado = $this->getAvaliado($id_avaliado);
                $result = array(
                    'modelo' => $getAvaliado->modelo,
                    'avaliado' => $getAvaliado->avaliado,
                    'avaliadores' => $evento
                );
                $this->ajax_notificar($result);
            }
        }

        echo json_encode(array('status' => $status !== false));

    }

    public function ajax_update()
    {
        $avaliadores = $this->input->post('avaliador');
        if (count(array_filter($avaliadores)) == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A avaliação necessita ao menos de um avaliador')));
        }

        $id = $this->input->post('id');
        $data = array(
            'id_modelo' => $this->input->post('id_modelo'),
            'id_avaliado' => $this->input->post('id_avaliado'),
            'data_atividades' => date("Y-m-d", strtotime(str_replace('/', '-', $this->input->post('data_atividades')))),
            'nota_corte' => $this->input->post('nota_corte')
        );
        if (empty($data['id_modelo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo de avaliação não foi encontrado')));
        }

        $id_supervisor = $this->input->post('id_supervisor');
        if ($id_supervisor) {
            $data['id_supervisor'] = $id_supervisor;
        }
        $id_avaliacao = $this->input->post('id_avaliacao');
        if ($id_avaliacao) {
            $data['id_avaliacao'] = $id_avaliacao;
        }

        $this->db->trans_start();

        $update_string = $this->db->update_string('avaliacaoexp_avaliados', $data, array('id' => $id));
        $this->db->query($update_string);

        $id_avaliador = $this->input->post('id_avaliador');

        $data_avaliacao = $this->input->post('data_avaliacao');
        $eventos = array();

        foreach ($avaliadores as $k => $avaliador) {
            $data = array(
                'id_avaliado' => $id,
                'id_avaliador' => $avaliador,
                'data_avaliacao' => date("Y-m-d", strtotime(str_replace('/', '-', $data_avaliacao[$k])))
            );

            if ($id_avaliador[$k]) {
                $row = $this->db->query("SELECT id_evento FROM avaliacaoexp_avaliadores WHERE id = $id_avaliador[$k]")->row();
                $id_evento = $row ? $row->id_evento : null;

                if ($avaliador) {
                    $this->db->update('avaliacaoexp_avaliadores', $data, array('id' => $id_avaliador[$k]));
                    if ($this->db->affected_rows() > 0 || empty($id_evento)) {
                        $eventos[$id_avaliador[$k]] = $id_evento;
                    }
                } else {
                    $this->db->delete('avaliacaoexp_avaliadores', array('id' => $id_avaliador[$k]));
                    if ($this->db->affected_rows() > 0) {
                        $eventos[$id_avaliador[$k]] = $id_evento;
                    }
                }
            } elseif ($avaliador) {

                $this->db->insert('avaliacaoexp_avaliadores', $data);
                $id = $this->db->insert_id();
                if ($this->db->affected_rows() > 0) {
                    $eventos[$id] = null;
                }
            }
        }
        $this->db->trans_complete();

        $status = $this->db->trans_status();

        $result['status'] = $status !== false;

        if ($status !== FALSE and $eventos) {
            $avaliado = $this->getAvaliado($id);
            $result = array(
                'modelo' => $avaliado->modelo,
                'avaliado' => $avaliado->avaliado,
                'avaliadores' => $eventos
            );
            $this->ajax_notificar($result);
        }

        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_delete($id_avaliado)
    {
        $avaliado = $this->getAvaliado($id_avaliado);
        $this->db->trans_start();

        $sql = "SELECT id, 
                       id_evento
                FROM avaliacaoexp_avaliadores 
                WHERE id_avaliado = {$id_avaliado}";
        $rows = $this->db->query($sql)->result();
        $eventos = array();
        foreach ($rows as $row) {
            $eventos[$row->id] = $row->id_evento;
        }

        $this->db->query("DELETE FROM avaliacaoexp_avaliados WHERE id = {$id_avaliado}");
        $this->db->trans_complete();

        $status = $this->db->trans_status();

        $result['status'] = $status !== false;

        if ($status !== FALSE and $eventos) {
            $result = array(
                'modelo' => $avaliado->modelo,
                'avaliado' => $avaliado->avaliado,
                'avaliadores' => $eventos
            );
            $this->ajax_notificar($result);
        }

        echo json_encode(array('status' => $status !== false));
    }

    public function relatorio($avaliado = null, $pdf = false)
    {
        if (empty($avaliado)) {
            $avaliado = $this->uri->rsegment(3);
        }

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $vars['empresa'] = $this->db->get('usuarios')->row();

        $sql = "SELECT b.nome,
                       b.tipo,
                       (case b.tipo when 'P'
                        then 'Avaliação Período de Experiência' 
                        else 'Avaliação Periódica de Desempenho' end) AS titulo,
                       d.id AS id_colaborador,
                       d.nome AS colaborador,
                       f.cargo,
                       d.funcao,
                       d.depto,
                       d.area,
                       d.setor,
                       (case b.tipo when 'P'
                        then DATE_FORMAT(a.data_atividades,'%d/%m/%Y') 
                        else DATE_FORMAT(c.data_inicio,'%d/%m/%Y') end) AS data_inicio,
                       DATE_FORMAT(MAX(g.data_avaliacao),'%d/%m/%Y') AS data_termino, 
                       DATE_FORMAT(curdate(),'%d/%m/%Y') AS data_atual,
                       a.nota_corte,
                       null AS resultado_final,
                       h.pontos_fortes,
                       h.pontos_fracos,
                       h.feedback1,
                       h.feedback2,
                       h.feedback3,
                       DATE_FORMAT(h.data_feedback1,'%d/%m/%Y') AS data_feedback1,
                       DATE_FORMAT(h.data_feedback2,'%d/%m/%Y') AS data_feedback2,
                       DATE_FORMAT(h.data_feedback3,'%d/%m/%Y') AS data_feedback3,
                       h.parecer_final
                FROM avaliacaoexp_avaliados a
                INNER JOIN avaliacaoexp_modelos b ON
                            b.id = a.id_modelo 
                LEFT JOIN avaliacaoexp c ON
                            c.id_modelo = b.id
                LEFT JOIN usuarios d ON
                            d.id = a.id_avaliado 
                LEFT JOIN funcionarios_cargos e ON
                            e.id_usuario = d.id 
                LEFT JOIN cargos f ON
                            f.id = e.id_cargo
                LEFT JOIN avaliacaoexp_avaliadores g ON
                          g.id_avaliado = a.id
                LEFT JOIN avaliacaoexp_periodo h ON
                          h.id_avaliado = a.id
                WHERE a.id = {$avaliado}";

        $vars['dadosAvaliacao'] = $this->db->query($sql)->row();

        $query = "SELECT a.id,
                         DATE_FORMAT(a.data_avaliacao,'%d/%m/%Y') AS data_avaliacao,
                         b.nome,
                         (SELECT DATE_FORMAT(MAX(x.data_avaliacao),'%d/%m/%Y')
                          FROM avaliacaoexp_resultado x
                          WHERE x.id_avaliador = a.id) AS data_realizacao,
                         '' AS resultado,
                         c.pontos_fortes,
                         c.pontos_fracos,
                         c.observacoes
                 FROM avaliacaoexp_avaliadores a 
                 LEFT JOIN usuarios b ON
                            b.id = a.id_avaliador
                 LEFT JOIN avaliacaoexp_desempenho c ON
                           c.id_avaliador = a.id
                 WHERE a.id_avaliado = {$avaliado} 
                 ORDER BY a.data_avaliacao ASC, 
                          a.id ASC";

        $avaliadores = $this->db->query($query)->result();
        foreach ($avaliadores as $k => $avaliador) {
            $avaliadores[$k]->resultado = array_pad(array(), 6, 0);
        }

        $vars['alternativas'] = array();

        if ($vars['dadosAvaliacao']->tipo == 'P') {

            $select = "SELECT a.alternativa AS nome, 
                              a.peso,
                              null as media
                       FROM avaliacaoexp_alternativas a
                       INNER JOIN avaliacaoexp_modelos b ON
                                  b.id = a.id_modelo
                       INNER JOIN avaliacaoexp_avaliados c ON
                                  c.id_modelo = b.id
                       WHERE c.id = {$avaliado} 
                       ORDER BY a.id ASC";

            $vars['alternativas'] = $this->db->query($select)->result();

            foreach ($vars['alternativas'] as $k => $result) {
                $vars['alternativas'][$k]->media = array_pad(array(), 3, null);
            }
        }

        $select = "SELECT a.id,
                          a.pergunta
                  FROM avaliacaoexp_perguntas a
                  INNER JOIN avaliacaoexp_modelos b ON
                             b.id = a.id_modelo
                  INNER JOIN avaliacaoexp_avaliados c ON
                             c.id_modelo = b.id
                  WHERE c.id = {$avaliado} 
                  ORDER BY a.id ASC";
        $perguntas = $this->db->query($select)->result();

        $data = array();

        $soma_pesos = 0;

        foreach ($perguntas as $p => $pergunta) {

            $data[$p] = array(
                'id' => $pergunta->id,
                'pergunta' => $pergunta->pergunta,
                'alternativas' => array()
            );

            $select = "SELECT a.id,
                              a.alternativa,
                              a.peso
                       FROM avaliacaoexp_alternativas a
                       INNER JOIN avaliacaoexp_perguntas b ON
                                  b.id_modelo = a.id_modelo AND 
                                  (b.id = a.id_pergunta OR a.id_pergunta IS NULL)
                       WHERE b.id = {$pergunta->id} 
                       ORDER BY a.id ASC, 
                                a.peso ASC";

            $alternativas = $this->db->query($select)->result();

            $max_peso = 1;

            foreach ($alternativas as $a => $alternativa) {

                $data[$p]['alternativas'][$a] = array(
                    'id' => $alternativa->id,
                    'alternativa' => $alternativa->alternativa,
                    'peso' => $alternativa->peso,
                    'respostas' => array_pad(array(), 3, null)
                );

                $max_peso = max($max_peso, $alternativa->peso);

                foreach ($avaliadores as $k => $avaliador) {

                    $select2 = "SELECT d.peso as resposta
                                FROM avaliacaoexp_avaliadores a
                                LEFT JOIN avaliacaoexp_resultado b ON
                                          b.id_avaliador = a.id
                                INNER JOIN avaliacaoexp_perguntas c ON
                                          c.id = b.id_pergunta AND
                                          c.id = {$pergunta->id}
                                INNER JOIN avaliacaoexp_alternativas d ON
                                          d.id = b.id_alternativa AND
                                          d.id = {$alternativa->id}
                                WHERE a.id = {$avaliador->id}
                                UNION ALL 
                                SELECT null AS resposta 
                                FROM dual 
                                LIMIT 1";

                    $resposta = $this->db->query($select2)->row();

                    $avaliadores[$k]->resultado[$a] += $resposta->resposta;
                    $data[$p]['alternativas'][$a]['respostas'][$k] = isset($resposta->resposta) ? $resposta->resposta : 'null';

                    if ($vars['dadosAvaliacao']->tipo == 'P') {
                        $vars['alternativas'][$a]->media[$k] += ($resposta->resposta ? 1 : 0);
                    }
                }
            }
            $soma_pesos += $max_peso;
        }

        foreach ($vars['alternativas'] as $a => $alternativa) {
            foreach ($alternativa->media as $r => $respostas) {
                $peso = $vars['alternativas'][$a]->peso;
                if ($respostas > 0 and count($perguntas) > 0) {
                    $vars['alternativas'][$a]->media[$r] = round($peso * $respostas / count($perguntas), 1);
                }
            }
        }

        $avaliador0 = new stdClass();
        $avaliador0->data_avaliacao = '';
        $avaliador0->nome = '';
        $avaliador0->data_realizacao = '';
        $avaliador0->resultado = array_pad(array(), 6, '');
        $avaliador0->pontos_fortes = '';
        $avaliador0->pontos_fracos = '';
        $avaliador0->observacoes = '';
        $vars['dadosAvaliadores'] = array_pad(array(), 3, $avaliador0);

        foreach ($avaliadores as $k => $avaliador) {
            foreach ($avaliador->resultado as $r => $resultado) {
                if ($vars['dadosAvaliacao']->tipo == 'P') {
                    $avaliadores[$k]->resultado[$r] = count($perguntas) > 0 ? $resultado / count($perguntas) : 0;
                } else {

                    $avaliadores[$k]->resultado[$r] = $soma_pesos > 0 ? $resultado * 100 / $soma_pesos : '';
                }
            }
            $vars['dadosAvaliacao']->resultado_final += array_sum($avaliador->resultado);
            $vars['dadosAvaliadores'][$k] = $avaliadores[$k];
        }
        $vars['dadosAvaliacao']->resultado_final = count($avaliadores) > 0 ? round($vars['dadosAvaliacao']->resultado_final / count($avaliadores), 1) : 0;
        $vars['dadosAvaliado'] = $data;

        $vars['is_pdf'] = $pdf;

        $this->load->helper('url');
        if ($pdf) {
//            foreach ($vars['itensAvaliacao'] as $line) {
//                $line->competencia = wordwrap($line->competencia, 18, "<br/>", true);
//                $line->descricao = wordwrap($line->descricao, 18, "<br/>", true);
//                $line->resultado = wordwrap($line->resultado, 18, "<br/>", true);
//            }
            $vars['ocultar_avaliadores'] = $this->input->get('ocultar_avaliadores');
            return $this->load->view('getavaliacaoexp_relatorio', $vars, true);
        } else {
            $this->load->view('avaliacaoexp_relatorio', $vars);
        }
    }

    public function pdfRelatorio()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.avaliado thead th { font-size: 11px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.avaliado tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliado tbody tr th { font-size: 11px; padding: 2px; } ';
        $stylesheet .= 'table.avaliado tbody tr:nth-child(2) td { border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.avaliado tbody td { font-size: 11px; padding: 5px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.avaliado tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.avaliadores thead tr th { padding: 5px; background-color: #f5f5f5; } ';
        $stylesheet .= 'table.avaliadores thead tr:nth-child(0) th { font-size: 11px; } ';
        $stylesheet .= 'table.avaliadores tbody td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= 'table.avaliadores tbody td.text-center + td { text-align: center; }';
        $stylesheet .= 'table.avaliadores tbody td.text-right + td { text-align: right; }';
        $stylesheet .= 'table.avaliadores tfoot td { font-size: 18px; font-weight: bold; }';

        $stylesheet .= 'table.avaliacao tr th, table.avaliacao tr td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= 'table.avaliacao th.active { background-color: #f5f5f5; } ';

        $stylesheet .= 'table.resultado tr th, table.resultado tr td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= 'table.resultado tr th.active { font-size: 11px; padding: 5px; background-color: #f5f5f5; } ';

        $stylesheet .= 'table.parecer_final tr th, table.parecer_final tr td { font-size: 12px; padding: 5px; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));

        $sql = "SELECT (CASE m.tipo WHEN 'P'
                        THEN 'APE' 
                        ELSE 'APR' 
                        END) AS tipo,
                        u.nome AS avaliado
                FROM avaliacaoexp_avaliados a 
                INNER JOIN usuarios u ON 
                           u.id = a.id_avaliado 
                INNER JOIN avaliacaoexp_modelos m ON 
                           m.id = a.id_modelo
                WHERE a.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output($row->tipo . ' - ' . $row->avaliado . '.pdf', 'D');
    }

    private function getAvaliado($id_avaliado)
    {
        $sql = "SELECT b.nome AS modelo,
                       c.nome AS avaliado
                FROM avaliacaoexp_avaliados a
                INNER JOIN avaliacaoexp_modelos b ON 
                           b.id = a.id_modelo
                INNER JOIN usuarios c ON
                           c.id = a.id_avaliado
                WHERE a.id = {$id_avaliado}";

        return $this->db->query($sql)->row();
    }

    public function ajax_notificar($busca)
    {
        $avaliadores = $busca['avaliadores'];
        if (empty($avaliadores)) {
            exit(json_encode(array('erro' => 'Nenhum avaliador notificado')));
        }
//        $this->load->helper(array('date', 'phpmailer'));
        $this->load->helper(array('date'));

        $nome_avaliacao = $busca['modelo'];
        $nome_avaliado = $busca['avaliado'];

        $data['title'] = $nome_avaliacao;
        $data['date_from'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['usuario'] = $this->session->userdata('id');

        $email['remetente'] = $data['usuario'];
        $email['titulo'] = $nome_avaliacao;
        $email['datacadastro'] = $data['date_from'];
        $msgRemetente = '';

        foreach ($avaliadores as $id => $id_evento) {
            $query = "SELECT a.id_avaliador, 
                             d.nome, 
                             c.id_avaliado, 
                             a.data_avaliacao, 
                             e.usuario_referenciado, 
                             f.nome AS nome_referenciado, 
                             e.date_to 
                      FROM avaliacaoexp_avaliadores a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_avaliador
                      INNER JOIN avaliacaoexp_avaliados c ON
                                 c.id = a.id_avaliado
                      INNER JOIN usuarios d ON 
                                 d.id = c.id_avaliado
                      LEFT JOIN eventos e ON 
                                e.id = a.id_evento
                      LEFT JOIN usuarios f ON 
                                 f.id = e.usuario_referenciado
                      WHERE a.id = {$id} AND 
                            a.id_evento " . ($id_evento ? "= {$id_evento}" : 'IS NULL');
            $row = $this->db->query($query)->row();

            if (count($row)) {
                $data_avaliacao = date("d/m/Y", strtotime(str_replace('-', '/', $row->data_avaliacao)));
                $data['date_to'] = $row->data_avaliacao;
                $data['description'] = "Avaliado(a) : {$nome_avaliado}<br>Avaliador(a): {$row->nome}";
//                $data['link'] = "avaliacaoexp/avaliado/{$row->id_avaliado}";
                $data['link'] = "avaliacaoexp_avaliador/periodo";
                $data['usuario_referenciado'] = $row->id_avaliador;

                if ($id_evento) {
                    #update
                    $this->db->update('eventos', $data, array('id' => $id_evento));
                    $date_to = date("d/m/Y", strtotime(str_replace('-', '/', $row->date_to)));

                    if ($row->id_avaliador !== $row->usuario_referenciado) {

                        # mensagem de exclusão para o avaliador antigo     
                        $email['destinatario'] = $row->usuario_referenciado;
                        $email['mensagem'] = "<p>O compromisso do dia {$date_to} foi cancelado.</p>";
                        $this->enviarEmail($email);

                        # mensagem de inclusão para o avaliador novo
                        $email['destinatario'] = $row->id_avaliador;
                        $msg = "<p>Você deve avaliar o(a) colaborador(a) {$row->nome} no lugar de {$row->nome_referenciado}.</p>";
                        $email['mensagem'] = "Você tem uma nova avaliação do período de experiência no dia {$data_avaliacao}. {$msg}";
                        $this->enviarEmail($email);

                        $msgRemetente .= $msg;
                        //$msgRemetente .= "<p>O(a) avaliador(a) {$row->nome_referenciado} foi substituído(a) por {$row->nome} para o dia {$data_avaliacao}.</p>";
                    } elseif ($data_avaliacao !== $date_to) {

                        # mensagem de atualização para o avaliador atual
                        $email['destinatario'] = $row->id_avaliador;
                        $msg = "<p>Você deve avaliar o(a) colaborador(a) {$row->nome}.</p>";
                        $email['mensagem'] = "Você tem uma avaliação do período de experiência alterada para o dia {$data_avaliacao}. {$msg}";

                        $this->enviarEmail($email);
                        $msgRemetente .= $msg;
                        //$msgRemetente .= "<p>A avaliação de {$row->nome} foi alterada para o dia {$data_avaliacao}.</p>";
                    }
                } else {
                    #insert
                    $this->db->insert('eventos', $data);
                    $evento = $this->db->insert_id();
                    $this->db->update('avaliacaoexp_avaliadores', array('id_evento' => $evento), array('id' => $id));

                    $email['destinatario'] = $row->id_avaliador;
                    $msg = "<p>Você deve avaliar o(a) colaborador(a) {$row->nome}.</p>";
                    $email['mensagem'] = "Você tem uma nova avaliação do período de experiência no dia {$data_avaliacao}. {$msg}";
                    $msgRemetente .= $msg;
                    $this->enviarEmail($email);
                }
            } else {
                #delete
                $sql = "SELECT b.nome, 
                               DATE_FORMAT(a.date_to,'%d/%m/%Y') AS date_to, 
                               a.usuario_referenciado 
                        FROM eventos a 
                        INNER JOIN usuarios b ON 
                                   b.id = a.usuario_referenciado 
                        WHERE a.id = '{$id_evento}'";
                $evento = $this->db->query($sql)->row();
                if (count($evento)) {
                    $this->db->delete('eventos', array('id' => $id_evento));

                    $email['destinatario'] = $evento->usuario_referenciado;
                    $msg = "<p>A avaliação de $evento->nome foi removida.</p>";
                    $email['mensagem'] = "<p>O compromisso do dia {$evento->date_to} foi cancelado.</p> {$msg}";
                    $this->enviarEmail($email);
                    $msgRemetente .= $msg;
                }
            }
        }

        $email['destinatario'] = $email['remetente'];
        $email['titulo'] = "$nome_avaliacao - $nome_avaliado";
        $email['mensagem'] = $msgRemetente;
        $this->enviarEmail($email);
    }

    private function enviarEmail($data)
    {
        if ($this->load->is_loaded('email') == false) {
            $this->load->library('email');
        }

        $this->db->select('a.nome, a.email, b.email AS email_empresa', false);
        $this->db->join('usuarios b', 'b.id = a.empresa', 'left');
        $this->db->where('a.id', $data['remetente']);
        $remetente = $this->db->get('usuarios a')->row();

        $this->db->select('email');
        $destinatario = $this->db->get_where('usuarios', array('id' => $data['destinatario']))->row();

        if ($remetente and $destinatario) {
            $this->email->from($remetente->email, $remetente->nome);
            $this->email->to($destinatario->email);

            $this->email->subject($data['titulo']);
            $this->email->message($data['mensagem']);

            if ($this->email->send()) {
                $data['mensagem'] = "<p>Prezado colaborador.</p>{$data['mensagem']}<p>Favor ver a sua agenda.</p>";
                $this->db->insert('mensagensrecebidas', $data);
                $this->db->insert('mensagensenviadas', $data);
            }
        }

        $this->email->clear();
    }

    private function enviarEmail_old($data)
    {
        $sql = "SELECT id,
                       nome,
                       email 
                FROM usuarios 
                WHERE id = '{$data['destinatario']}'";
        $usuario = $this->db->query($sql)->row();

        if (count($usuario)) {
            $funcionario = $usuario->id !== $this->session->userdata('empresa');
            if ($funcionario) {
                $data['mensagem'] = "<p>Prezado colaborador.</p>{$data['mensagem']}<p>Favor ver a sua agenda.</p>";
            }
            if (send_email($usuario->nome, $usuario->email, $data['titulo'], $data['mensagem']) and $funcionario) {
                $this->db->query($this->db->insert_string('mensagensrecebidas', $data));
                $this->db->query($this->db->insert_string('mensagensenviadas', $data));
            }
        }
    }

    public function status($tipo = '')
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));
        $data['nivel'] = $this->session->userdata('nivel');

        $tipo_modelo = $tipo === 2 ? 'P' : ($tipo === 1 ? 'A' : '');

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM(a.{$field})) AS {$field} 
                    FROM usuarios a
                    INNER JOIN avaliacaoexp_avaliados b ON
                               b.id_avaliado = a.id
                    LEFT JOIN avaliacaoexp_modelos c ON
                               c.id = b.id_modelo AND 
                               c.tipo = '{$tipo}'
                    WHERE a.empresa = {$empresa} AND
                          CHAR_LENGTH({$field}) > 0";
            if ($this->session->userdata('tipo') == 'funcionario' and in_array($field, array('depto', 'area', 'setor'))) {
                if (in_array($data['nivel'], array(9, 10))) {
                    $sql .= " AND a.depto = (SELECT depto 
                                             FROM usuarios 
                                             WHERE id = {$this->session->userdata('id')})";
                } elseif ($data['nivel'] == 11) {
                    $sql .= " AND a.nivel_acesso = 4";
                    if ($field == 'setor') {
                        $sql .= " AND (a.setor = (SELECT setor 
                                             FROM usuarios 
                                             WHERE id = {$this->session->userdata('id')}) OR a.setor = 'backup')";
                    } else {
                        $sql .= " AND a.{$field} = (SELECT {$field} 
                                             FROM usuarios 
                                             WHERE id = {$this->session->userdata('id')})";
                    }
                }
            }

            $rows = $this->db->query($sql)->result_array();
            if ($data['nivel'] == 11 and in_array($field, array('depto', 'area'))) {
                $data[$field] = array();
            } else {
                $data[$field] = array('' => 'Todos');
            }

            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
            if ($this->session->userdata('tipo') == 'funcionario' and $field == 'depto' and in_array($data['nivel'], array(9, 10, 11))) {
                unset($data[$field]['']);
            }
        }
        $data['tipo'] = $tipo;

        $this->load->view('avaliacaoexp_status', $data);
    }

    public function atualizar_filtro()
    {
        $nivel = $this->session->userdata('nivel');

        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
                unset($filtro['area'][''], $filtro['setor']['']);
            }
            unset($filtro['depto']['']);
        }

        if ($nivel != 11) {
            $data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
            $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        }
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    public function ajax_status($tipo = '')
    {
        $empresa = $this->session->userdata('empresa');
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $nivel = $this->session->userdata('nivel');

        $sql = "SELECT s.id, 
                       s.nome, 
                       DATE_FORMAT(s.data_programada,'%d/%m/%Y') AS data_programada,
                       s.avaliador,
                       DATE_FORMAT(s.data_realizacao,'%d/%m/%Y') AS data_realizacao,
                       s.nota_aproveitamento,
                       s.observacoes,
                       s.id_avaliado,
                       s.matricula
                FROM (SELECT a.id, 
                             b.nome,
                             b.matricula,
                             c.data_avaliacao AS data_programada, 
                             a.data_atividades, 
                             d.nome AS avaliador, 
                             MAX(h.data_avaliacao) AS data_realizacao, 
                             a.observacoes, 
                             a.id_avaliado,
                             CASE e.tipo WHEN 'P' 
                                  THEN SUM(i.peso) / (SELECT COUNT(p.id) FROM avaliacaoexp_perguntas p WHERE p.id_modelo = e.id)
                                  ELSE SUM(i.peso) * 100 / (SELECT SUM(z.peso) FROM (SELECT MAX(k.peso) AS peso FROM avaliacaoexp_alternativas k INNER JOIN avaliacaoexp_perguntas x ON x.id = k.id_pergunta WHERE k.id_modelo = 10 GROUP BY x.id) z)
                                  END AS nota_aproveitamento
                      FROM avaliacaoexp_avaliados a 
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_avaliado
                      INNER JOIN avaliacaoexp_modelos e ON 
                                 e.id = a.id_modelo
                      INNER JOIN avaliacaoexp_avaliadores c ON 
                                 c.id_avaliado = a.id
                      INNER JOIN usuarios d ON 
                                 d.id = c.id_avaliador
                      LEFT JOIN avaliacaoexp_resultado h ON 
                                h.id_avaliador = c.id
                      LEFT JOIN avaliacaoexp_alternativas i ON 
                                i.id = h.id_alternativa
                      WHERE b.empresa = {$empresa}";
        if ($nivel == 11) {
            $sql .= " AND c.id_avaliador = {$this->session->userdata('id')}";
        }
        if ($busca['depto']) {
            $sql .= " AND b.depto = '{$busca['depto']}'";
        }
        if ($busca['area']) {
            $sql .= " AND b.area = '{$busca['area']}'";
        }
        if ($busca['setor'] == 'backup') {
            $sql .= " AND b.setor = '{$busca['setor']}'";
        } elseif ($busca['setor']) {
            $sql .= " AND (b.setor = '{$busca['setor']}' OR b.setor = 'backup')";
        }
        if ($busca['cargo']) {
            $sql .= " AND b.cargo = '{$busca['cargo']}'";
        }
        if ($busca['funcao']) {
            $sql .= " AND b.funcao = '{$busca['funcao']}'";
        }
        if (strlen($busca['data_avaliacao'])) {
            $sql .= " AND c.data_avaliacao >= '" . date("Y-m-d", strtotime(str_replace('/', '-', $busca['data_avaliacao']))) . "'";
        }
        if (isset($busca['resultado']) and !empty($busca['resultado'])) {
            $sql .= ' AND h.id_avaliador IS NULL';
        }
        if (isset($busca['status']) and !empty($busca['status'])) {
            $sql .= ' AND b.status = 1';
        }
        if (isset($busca['ultimo_semestre']) and !empty($busca['ultimo_semestre'])) {
            $sql .= ' AND c.data_avaliacao >= SUBDATE(NOW(), INTERVAL 6 MONTH)';
        }
        if ($tipo == '1') {
            $sql .= " AND e.tipo = 'A'";
        } elseif ($tipo == '2') {
            $sql .= " AND e.tipo = 'P'";
        }
        $sql .= ' GROUP BY c.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.data_programada', 's.avaliador', 's.data_realizacao', 's.nota_aproveitamento', 's.observacoes', 's.matricula');
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
        foreach ($list as $avaliacaoexp) {
            $row = array();
            $row[] = $avaliacaoexp->nome;
            $row[] = $avaliacaoexp->data_programada;
            $row[] = $avaliacaoexp->avaliador;
            $row[] = $avaliacaoexp->data_realizacao;
            $row[] = $avaliacaoexp->nota_aproveitamento !== null ? round($avaliacaoexp->nota_aproveitamento, 1) . '%' : '';
            $row[] = $avaliacaoexp->observacoes;

            if ($this->session->userdata('tipo') == 'funcionario') {
                $row[] = '
                          <a class="btn btn-sm btn-info" href="' . site_url('avaliacaoexp_avaliados/relatorio/' . $avaliacaoexp->id) . '" target="_blank" title="Relatório de avaliação"><i class="glyphicon glyphicon-list-alt"></i> </a>
                          <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Notificar avaliador(es)" onclick="notificar(' . "'" . $avaliacaoexp->id . "'" . ')"><i class="glyphicon glyphicon-bell"></i> </a>
                         ';
            } else {
                $row[] = '
                          <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar observação" onclick="edit_status(' . "'" . $avaliacaoexp->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> </a>
                          <a class="btn btn-sm btn-success" href="' . site_url('funcionario/editar/' . $avaliacaoexp->id_avaliado) . '" title="Gerenciar avaliado"><i class="glyphicon glyphicon-plus"></i> Gerenciar</a>
                          <a class="btn btn-sm btn-info" href="' . site_url('avaliacaoexp_avaliados/relatorio/' . $avaliacaoexp->id) . '" target="_blank" title="Relatório de avaliação"><i class="glyphicon glyphicon-list-alt"></i> </a>
                          <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Notificar avaliador(es)" onclick="notificar(' . "'" . $avaliacaoexp->id . "'" . ')"><i class="glyphicon glyphicon-bell"></i> </a>
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

    public function edit_status()
    {
        $empresa = $this->session->userdata('empresa');
        $id = $this->input->post('id');

        $this->db->select('a.id, b.nome, a.observacoes');
        $this->db->join('usuarios b', 'b.id = a.id_avaliado');
        $this->db->where('b.empresa', $empresa);
        $this->db->where('a.id', $id);
        $data = $this->db->get_where('avaliacaoexp_avaliados a')->row();

        echo json_encode($data);
    }

    public function update_status()
    {
        $data = array('observacoes' => $this->input->post('observacoes'));
        if (strlen($data['observacoes']) == 0) {
            $data['observacoes'] = null;
        }
        $where = array('id' => $this->input->post('id'));

        $this->db->trans_start();
        $this->db->update('avaliacaoexp_avaliados', $data, $where);
        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    public function status_notificar()
    {
        $tipo = $this->input->post('tipo');
        $id_avaliado = $this->input->post('id_avaliado');
        $id_avaliador = $this->input->post('id_avaliador');

        parse_str($this->input->post('busca'), $busca);

        $this->db->select('a.id AS id_avaliado, b.nome as avaliado, d.nome as avaliacao');
        $this->db->select("CASE c.tipo WHEN 'A' THEN 'periódica de desempenho' WHEN 'P' THEN 'por período de experiência' END AS modelo", false);
        $this->db->join('usuarios b', 'b.id = a.id_avaliado');
        $this->db->join('avaliacaoexp_modelos c', 'c.id = a.id_modelo');
        $this->db->join('avaliacaoexp d', 'd.id = a.id_avaliacao', 'left');
        if ($id_avaliado) {
            $this->db->where('a.id', $id_avaliado);
        }
        if ($tipo) {
            $this->db->where('c.tipo', $tipo == 1 ? 'A' : ($tipo == 2 ? 'P' : null));
        }
        if ($busca['depto']) {
            $this->db->where('b.depto', $busca['depto']);
        }
        if ($busca['area']) {
            $this->db->where('b.area', $busca['area']);
        }
        if ($busca['setor']) {
            $this->db->where('b.setor', $busca['setor']);
        }
        if ($busca['cargo']) {
            $this->db->where('b.cargo', $busca['cargo']);
        }
        if ($busca['funcao']) {
            $this->db->where('b.funcao', $busca['funcao']);
        }
        $rows = $this->db->get('avaliacaoexp_avaliados a')->result();
        if (count($rows) == 0) {
            exit(json_encode(array('msg' => 'Não há colaboradores a serem avaliados.')));
        }

        $this->load->helper(array('date'));

        $email['remetente'] = $this->session->userdata('id');
        $email['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $email['mensagem'] = "<p>Caro colaborador, existem avaliações (Período de Experiência) sob sua responsabilidade para serem realizadas. Por gentileza, utilize o menu lateral para acessar estas avaliações.</p><p>Saudações - Gestão de Pessoas</p>";

        $status = true;

        foreach ($rows as $row) {
            $email['titulo'] = 'Avaliação ' . $row->modelo . ': ' . implode(' - ', array($row->avaliacao, $row->avaliado));

            $this->db->select('a.id AS id_avaliador, b.nome, b.email');
            $this->db->join('usuarios b', 'b.id = a.id_avaliador');
            $this->db->join('avaliacaoexp_resultado c', 'c.id_avaliador = a.id', 'left');
            $this->db->where('a.id_avaliado', $row->id_avaliado);
            if ($id_avaliador) {
                $this->db->where('a.id', $id_avaliador);
            }
            $this->db->group_by('b.id');
            $this->db->having('count(c.id)', 0);
            if ($busca['data_avaliacao']) {
                $this->db->where('a.data_avaliacao >=', date("Y-m-d", strtotime(str_replace('/', '-', $busca['data_avaliacao']))));
            }
            $avaliadores = $this->db->get('avaliacaoexp_avaliadores a')->result();

            $this->db->select("a.nome, a.email, IFNULL(b.email, a.email) AS email_empresa", false);
            $this->db->join('usuarios b', 'b.id = a.empresa', 'left');
            $this->db->where('a.id', $this->session->userdata('id'));
            $remetente = $this->db->get('usuarios a')->row();

            $this->load->library('email');

            foreach ($avaliadores as $avaliador) {

                $this->email->from($remetente->email, $remetente->nome);
//                $this->email->from('mhffortes@hotmail.com', 'mhffortes@hotmail.com');
                $this->email->to($avaliador->email);
//                $this->email->to('mhffortes@hotmail.com');
//                $this->email->cc('contato@rhsuite.com.br');
//                $this->email->bcc($remetente->email_empresa);

                $this->email->subject($email['titulo']);
                $this->email->message($email['mensagem']);

                if ($this->email->send()) {
                    $email['destinatario'] = $avaliador->id_avaliador;
                    $this->db->query($this->db->insert_string('mensagensrecebidas', $email));
                    $this->db->query($this->db->insert_string('mensagensenviadas', $email));
                } else {
                    $status = false;
                    exit(json_encode(array('msg' => 'Erro ao notificar avaliador ' . $avaliador->nome)));
                }

                $this->email->clear();
            }
        }

        echo json_encode(array('status' => $status));
    }

    public function notificarAvaliador()
    {
        $id = $this->input->post('id');

        $this->db->select('id_avaliado, id_evento');
        $row = $this->db->get_where('avaliacaoexp_avaliadores', array('id' => $id))->row();

        $status = !empty($row);
        if ($status) {
            $avaliado = $this->getAvaliado($row->id_avaliado);

            $this->load->helper('date');

            $email = array(
                'remetente' => $this->session->userdata('id'),
                'titulo' => $avaliado->modelo,
                'datacadastro' => mdate("%Y-%m-%d %H:%i:%s")
            );

            $query = "SELECT a.id_avaliador, 
                             b.nome, 
                             c.id_avaliado, 
                             a.data_avaliacao, 
                             d.usuario_referenciado, 
                             e.nome AS nome_referenciado, 
                             d.date_to 
                      FROM avaliacaoexp_avaliadores a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_avaliador
                      INNER JOIN avaliacaoexp_avaliados c ON
                                 c.id = a.id_avaliado
                      LEFT JOIN eventos d ON 
                                d.id = a.id_evento
                      LEFT JOIN usuarios e ON 
                                 e.id = d.usuario_referenciado
                      WHERE a.id = {$id} AND 
                            a.id_evento " . ($row->id_evento ? "= {$row->id_evento}" : 'IS NULL');
            $row2 = $this->db->query($query)->row();

            $msgRemetente = '';

            if ($row->id_evento) {
                $data = array(
                    'title' => $avaliado->modelo,
                    'date_from' => $email['datacadastro'],
                    'usuario' => $this->session->userdata('id'),
                    'date_to' => $row2->data_avaliacao,
                    'description' => "Avaliado(a): {$avaliado->avaliado}<br>Avaliador(a) : {$row2->nome}",
                    'link' => "avaliacaoexp_avaliador/periodo",
                    'usuario_referenciado' => $row2->id_avaliador
                );
                $this->db->update('eventos', $data, array('id' => $row->id_evento));

                $email['destinatario'] = $row2->id_avaliador;
                $msg = "<p>Você deve avaliar o(a) colaborador(a) {$row2->nome}.</p>";
                $data_avaliacao = date("d/m/Y", strtotime(str_replace('-', '/', $row2->data_avaliacao)));
                $email['mensagem'] = "Você tem uma avaliação do período de experiência marcada para o dia {$data_avaliacao}. {$msg}";
                $email['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
                $this->enviarEmail($email);

                $msgRemetente .= $msg;
            }

            $email['destinatario'] = $email['remetente'];
            $email['titulo'] = "{$avaliado->modelo} - {$avaliado->avaliado}";
            $email['mensagem'] = $msgRemetente;
            $this->enviarEmail($email);
        }

        echo json_encode(array('status' => $status));
    }

}
