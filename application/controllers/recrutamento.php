<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        if ($this->uri->rsegment(2) === 'testes') {
            $this->tipo_usuario = array('candidato');
        } else {
            $this->tipo_usuario = array('empresa', 'selecionador');
        }
        if ($this->tipo_usuario && !in_array($this->session->userdata('tipo'), $this->tipo_usuario)) {
            show_error('Acesso não autorizado ' . $this->session->userdata('tipo') . '-' . implode(',', $this->tipo_usuario), 403, 'Erro 403');
        }
    }

    public function index()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $data['candidato'] = '';
        $data['nome_candidato'] = '';

        $this->load->view('recrutamento', $data);
    }

    public function candidatos()
    {
        $data['empresa'] = $this->session->userdata('empresa');
        $data['candidato'] = '';
        $data['nome_candidato'] = '';


        $this->db->select('id, nome');
        $this->db->where('id', $this->uri->rsegment(3, 0));
        $row = $this->db->get('recrutamento_usuarios')->row();
        if ($row) {
            $data['candidato'] = $row->id;
            $data['nome_candidato'] = $row->nome;
        }

        $this->load->view('recrutamento', $data);
    }

    public function processos()
    {
        $data = array(
            'id_usuario' => $this->uri->rsegment(3),
            'id_candidato' => '',
            'recrutamento' => '',
            'nome_candidato' => '',
            'nome_cargo' => '',
            'nome_recrutamento' => '',
            'modelos' => array('' => 'selecione ...'),
        );

        if ($this->uri->rsegment(3)) {
            $this->db->select('a.id, a.nome, b.id AS candidato');
            $this->db->select('d.id AS id_recrutamento, d.nome AS nome_recrutamento');
            $this->db->join('recrutamento_candidatos b', 'b.id_usuario = a.id', 'left');
            $this->db->join('recrutamento_cargos c', 'c.id = b.id_cargo', 'left');
            $this->db->join('recrutamento d', 'd.id = c.id_recrutamento', 'left');
            $this->db->where('d.id', $this->uri->rsegment(3));
            $this->db->where('a.id', $this->uri->rsegment(4));
            $row = $this->db->get('recrutamento_usuarios a')->row();

            if ($row) {
                $data['id_usuario'] = $row->id;
                $data['id_candidato'] = $row->candidato;
                $data['recrutamento'] = $row->id_recrutamento;
                $data['nome_candidato'] = $row->nome;
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

    public function testes()
    {
        $data['candidato'] = $this->session->userdata('id');
        $data['teste'] = $this->uri->rsegment(3);
        switch ($data['teste']) {
            case 'matematica':
                $data['nome_teste'] = 'Matemática';
                break;
            case 'raciocinio-logico':
                $data['nome_teste'] = 'Raciocínio Lógico';
                break;
            case 'portugues':
                $data['nome_teste'] = 'Português';
                break;
            case 'lideranca':
                $data['nome_teste'] = 'Liderança';
                break;
            case 'perfil-personalidade':
                $data['nome_teste'] = 'Perfil-Personalidade';
                break;
            case 'digitacao':
                $data['nome_teste'] = 'Digitação';
                break;
            case 'interpretacao':
                $data['nome_teste'] = 'Interpretação';
                break;
            case 'entrevista':
                $data['nome_teste'] = 'Entrevista por Competências';
                break;
            default:
                $data['nome_teste'] = '';
        }
        $this->load->view('recrutamento_testes', $data);
    }

    public function ajax_list($candidato = '')
    {
        $empresa = $this->session->userdata('empresa');

        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.requisitante,
                       s.data_inicio,
                       s.data_termino,
                       s.status
                FROM (SELECT DISTINCT(a.id), 
                             a.nome, 
                             a.requisitante,
                             a.data_inicio,
                             a.data_termino,
                             (case a.status 
                              when 'N' then 'Não iniciado' 
                              when 'A' then 'Ativo'
                              when 'C' then 'Cancelado'
                              when 'F' then 'Fechado'
                              else '' end) AS status
                      FROM recrutamento a ";
        if ($candidato) {
            $sql .= "INNER JOIN recrutamento_cargos b ON 
                                b.id_recrutamento = a.id 
                     INNER JOIN recrutamento_candidatos c ON 
                                c.id_cargo = b.id
                     INNER JOIN recrutamento_usuarios d ON 
                                d.id = c.id_usuario ";
        }
        $sql .= "WHERE a.id_usuario_EMPRESA = {$empresa}";
        if ($candidato) {
            $sql .= " AND c.id_usuario = {$candidato}";
        }
        if ($post['busca']) {
            $sql .= " AND a.status = '{$post['busca']}'";
        }

        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.requisitante', 's.data_inicio', 's.data_termino', 's.status');
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
        foreach ($list as $recrutamento) {
            $row = array();
            $row[] = $recrutamento->nome;
            $row[] = $recrutamento->requisitante;
            $row[] = $recrutamento->data_inicio ? date("d/m/Y", strtotime(str_replace('-', '/', $recrutamento->data_inicio))) : '';
            $row[] = $recrutamento->data_termino ? date("d/m/Y", strtotime(str_replace('-', '/', $recrutamento->data_termino))) : '';
            $row[] = $recrutamento->status;

            if ($candidato) {
                $row[] = '
                          <a class="btn btn-sm btn-success" href="' . site_url('recrutamento/processos/' . $recrutamento->id . '/' . $candidato) . '" title="Ver processo"><i class="glyphicon glyphicon-list"></i> Ver processo</a>
                         ';
            } else {
                $row[] = '
                          <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                          <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_teste(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                          <a class="btn btn-sm btn-success" href="' . site_url('recrutamento_cargos/gerenciar/' . $recrutamento->id) . '" title="Ver processo"><i class="glyphicon glyphicon-list"></i> Ver processo</a>
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
        $data = $this->db->get_where('recrutamento', array('id' => $id))->row();

        $data->data_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_termino = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        $data['id_usuario_EMPRESA'] = $this->session->userdata('empresa');
        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $status = $this->db->insert('recrutamento', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $where = array('id' => $data['id']);
        unset($data['id']);

        $status = $this->db->update('recrutamento', $data, $where);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('recrutamento', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function relatorio($id_teste, $pdf = false)
    {
        $this->db->select('id, foto, foto_descricao');
        $empresa = $this->db->get_where('usuarios', array('id' => $this->session->userdata('empresa')))->row();
        $data['foto'] = 'imagens/usuarios/' . $empresa->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $empresa->foto_descricao;

        $sql = "SELECT a.nome, 
                       e.id AS id_teste,
                       f.id AS id_modelo, 
                       f.nome AS modelo, 
                       f.tipo,
                       DATE_FORMAT(e.data_inicio, '%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(e.data_termino, '%d/%m/%Y') AS data_termino,
                       a.requisitante, 
                       b.cargo, 
                       d.nome AS candidato,
                       g.nota
                FROM recrutamento a
                INNER JOIN recrutamento_cargos b ON
                           b.id_recrutamento = a.id
                INNER JOIN recrutamento_candidatos c ON
                           c.id_cargo = b.id
                INNER JOIN recrutamento_usuarios d ON
                           d.id = c.id_usuario
                INNER JOIN recrutamento_testes e ON 
                           e.id_candidato = c.id
                INNER JOIN recrutamento_modelos f ON 
                           f.id = e.id_modelo
                LEFT JOIN recrutamento_resultado g ON
                          g.id_teste = f.id
                WHERE e.id = {$id_teste}";
        $data['teste'] = $this->db->query($sql)->row();

        if ($data['teste']->tipo === 'D' || $data['teste']->tipo === 'I') {
            $this->db->select('c.id, c.pergunta, d.resposta, d.nota');
            $this->db->select('TIMESTAMPDIFF(MINUTE, a.data_acesso, a.data_envio) AS minutos', false);
            $this->db->join('recrutamento_modelos b', 'b.id = a.id_modelo');
            $this->db->join('recrutamento_perguntas c', 'c.id_modelo = b.id', 'left');
            $this->db->join('recrutamento_resultado d', 'd.id_teste = a.id', 'left');
            $this->db->where('a.id', $data['teste']->id_teste);
            $pergunta = $this->db->get('recrutamento_testes a')->row();

            $data['teste']->caracteres = strlen(preg_replace('/[^a-záàâãéèêíïóôõöúçñ0-9]/i', '', $pergunta->resposta));
            if ($data['teste']->tipo === 'D') {
                similar_text($pergunta->pergunta, $pergunta->resposta, $percent);
                $data['teste']->total = round($percent, 1);
            } else {
                $data['teste']->total = $pergunta->nota + 0;
            }
            $data['teste']->minutos = (int)$pergunta->minutos;
            $data['teste']->id_pergunta = $pergunta->id;
            $data['resposta'] = nl2br($pergunta->resposta);
        } elseif ($data['teste']->tipo === 'C') {
            $this->db->select('a.tipo_eneagrama, b.peso');
            $this->db->join('recrutamento_resultado c', "c.id_pergunta = a.id AND c.id_teste = '{$data['teste']->id_teste}'", 'left');
            $this->db->join('recrutamento_alternativas b', 'b.id_modelo = a.id_modelo AND b.id = c.id_alternativa', 'left');
            $this->db->where('a.id_modelo', $data['teste']->id_modelo);
            $this->db->group_by('a.id');
            $perguntas = $this->db->get('recrutamento_perguntas a')->result();

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
                $eneagramas[$pergunta->tipo_eneagrama] += $pergunta->peso;
            }


            /*$total = 0;
            foreach ($perguntas as $pergunta) {

                $sql = "SELECT a.alternativa,
                           a.peso,
                           d.peso AS resposta
                    FROM recrutamento_alternativas a
                    INNER JOIN recrutamento_perguntas b ON
                               b.id_modelo = a.id_modelo
                    LEFT JOIN recrutamento_resultado c ON
                              c.id_alternativa = a.id AND
                              c.id_teste = {$data['teste']->id_teste}
                    LEFT JOIN recrutamento_alternativas d ON
                              d.id = c.id_alternativa
                    WHERE b.id = {$pergunta->id}";
                $alternativas = $this->db->query($sql)->result();

                $pergunta->alternativas = $alternativas;

                $count = 0;
                foreach ($alternativas as $alternativa) {
                    $count += $alternativa->resposta;
                }
                $total += ($pergunta->peso ? $count * 100 / $pergunta->peso : 0);
            }*/
//           print_r(array_search(max($eneagramas), $eneagramas));
//           exit;
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


        } elseif ($data['teste']->tipo === 'E') {
            $this->db->select('c.id, c.pergunta, c.competencia, d.resposta, d.nota');
            $this->db->join('recrutamento_modelos b', 'b.id = a.id_modelo');
            $this->db->join('recrutamento_perguntas c', 'b.id = c.id_modelo', 'left');
            $this->db->join('recrutamento_resultado d', 'd.id_pergunta = c.id AND d.id_teste = a.id', 'left');
            $this->db->where('a.id', $data['teste']->id_teste);
            $this->db->where('a.id_modelo', $data['teste']->id_modelo);
            $perguntas = $this->db->get('recrutamento_testes a')->result();

            $competencia = null;
            $k = -1;
            $data['competencias'] = $arrNota = array();
            foreach ($perguntas as $pergunta) {
                if ($pergunta->competencia != $competencia) {
                    $competencia = $pergunta->competencia;
                    $k++;
                }
                $arrNota[] = $pergunta->nota;
                $data['competencias'][$k][] = $pergunta;
            }

            $data['teste']->total = number_format(array_sum($arrNota) / max(count($arrNota), 1), 1, ',', '');

        } else {
            $this->db->select('a.id, a.pergunta, a.competencia, MAX(b.peso) AS peso');
            $this->db->join('recrutamento_alternativas b', 'b.id_pergunta = a.id');
            $this->db->where('a.id_modelo', $data['teste']->id_modelo);
            $this->db->group_by('a.id');
            $perguntas = $this->db->get('recrutamento_perguntas a')->result();

            $total = 0;
            foreach ($perguntas as $pergunta) {

                $sql = "SELECT a.alternativa,
                           a.peso,
                           d.peso AS resposta
                    FROM recrutamento_alternativas a
                    INNER JOIN recrutamento_perguntas b ON
                               b.id = a.id_pergunta
                    LEFT JOIN recrutamento_resultado c ON
                              c.id_alternativa = a.id AND
                              c.id_teste = {$data['teste']->id_teste}
                    LEFT JOIN recrutamento_alternativas d ON
                              d.id = c.id_alternativa
                    WHERE a.id_pergunta = {$pergunta->id}";
                $alternativas = $this->db->query($sql)->result();

                $pergunta->alternativas = $alternativas;

                $count = 0;
                foreach ($alternativas as $alternativa) {
                    $count += $alternativa->resposta;
                }
                $total += ($pergunta->peso ? $count * 100 / $pergunta->peso : 0);
            }

            $data['perguntas'] = $perguntas;
            $data['teste']->total = count($perguntas) ? round($total / count($perguntas), 2) : 0;
        }

        if ($pdf) {
            if ($data['teste']->tipo == 'C') {
                $data['chart_valores'] = $this->input->post('chart_valores');
                return $this->load->view('getrecrutamento_relatorioEneagrama', $data, true);
            } elseif ($data['teste']->tipo == 'E') {
                return $this->load->view('getrecrutamento_relatorioEntrevista', $data, true);
            } else {
                return $this->load->view('getrecrutamento_relatorio', $data, true);
            }
        } else {
            if ($data['teste']->tipo == 'C') {
                $this->load->view('recrutamento_relatorioEneagrama', $data);
            } elseif ($data['teste']->tipo == 'E') {
                $this->load->view('recrutamento_relatorioEntrevista', $data);
            } else {
                $this->load->view('recrutamento_relatorio', $data);
            }
        }
    }

    public function pdfRelatorio()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.recrutamento thead th { font-size: 14px; padding: 5px; text-align: center; font-weight: bold; } ';
        $stylesheet .= 'table.recrutamento thead tr, table.recrutamento tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.recrutamento tbody tr th { font-size: 13px; padding: 2px; } ';
        $stylesheet .= 'table.recrutamento tbody tr:nth-child(2) td { border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td { font-size: 13px; padding: 5px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.resultado tr th, table.resultado tr td { font-size: 13px; padding: 5px; } ';
        $stylesheet .= 'table.resultado thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.resultado thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.resultado tbody tr th { background-color: #dff0d8; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));

        $sql = "SELECT a.nome AS processo,
                       d.nome
                FROM recrutamento a
                INNER JOIN recrutamento_cargos b ON
                           b.id_recrutamento = a.id
                INNER JOIN recrutamento_candidatos c ON
                           c.id_cargo = b.id
                INNER JOIN recrutamento_usuarios d ON
                           d.id = c.id_usuario
                INNER JOIN recrutamento_testes e ON
                           e.id_candidato = c.id
                WHERE e.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output("PS - {$row->processo} - {$row->nome}.pdf", 'D');
    }

    public function pdfEneagrama()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.recrutamento thead th { font-size: 14px; padding: 5px; text-align: center; font-weight: bold; } ';
        $stylesheet .= 'table.recrutamento thead tr, table.recrutamento tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.recrutamento tbody tr th { font-size: 13px; padding: 2px; } ';
        $stylesheet .= 'table.recrutamento tbody tr:nth-child(2) td { border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.resultado tr th, table.resultado tr td { font-size: 13px; padding: 5px; } ';
        $stylesheet .= 'table.resultado thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.resultado thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.resultado tbody tr td.success { background-color: #dff0d8; } ';
        $stylesheet .= 'table.resultado tbody tr td:nth-child(1) { vertical-align: top; word-wrap: break-word; width: 45%; } ';
        $stylesheet .= 'table.resultado tbody tr td:nth-child(2) { vertical-align: top; word-wrap: break-word;  width: 45%; word-break: break-all; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));

        $sql = "SELECT a.nome AS processo,
                       d.nome
                FROM recrutamento a
                INNER JOIN recrutamento_cargos b ON
                           b.id_recrutamento = a.id
                INNER JOIN recrutamento_candidatos c ON
                           c.id_cargo = b.id
                INNER JOIN recrutamento_usuarios d ON
                           d.id = c.id_usuario
                INNER JOIN recrutamento_testes e ON
                           e.id_candidato = c.id
                WHERE e.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output("{$row->nome} - Relatório Eneagrama.pdf", 'D');
    }

    public function pdfLifo()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.recrutamento thead th { font-size: 14px; padding: 5px; text-align: center; font-weight: bold; } ';
        $stylesheet .= 'table.recrutamento thead tr, table.recrutamento tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.recrutamento tbody tr th { font-size: 13px; padding: 2px; } ';
        $stylesheet .= 'table.recrutamento tbody tr:nth-child(2) td { border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.resultado tr th, table.resultado tr td { font-size: 13px; padding: 5px; } ';
        $stylesheet .= 'table.resultado thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.resultado thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.resultado tbody tr td.success { background-color: #dff0d8; } ';
        $stylesheet .= 'table.resultado tbody tr td:nth-child(1) { vertical-align: top; word-wrap: break-word; width: 45%; } ';
        $stylesheet .= 'table.resultado tbody tr td:nth-child(2) { vertical-align: top; word-wrap: break-word;  width: 45%; word-break: break-all; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));

        $sql = "SELECT a.nome AS processo,
                       d.nome
                FROM recrutamento a
                INNER JOIN recrutamento_cargos b ON
                           b.id_recrutamento = a.id
                INNER JOIN recrutamento_candidatos c ON
                           c.id_cargo = b.id
                INNER JOIN recrutamento_usuarios d ON
                           d.id = c.id_usuario
                INNER JOIN recrutamento_testes e ON
                           e.id_candidato = c.id
                WHERE e.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output("{$row->nome} - Relatório LIFO.pdf", 'D');
    }

    public function pdfEntrevista()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.recrutamento thead th { font-size: 14px; padding: 5px; text-align: center; font-weight: bold; } ';
        $stylesheet .= 'table.recrutamento thead tr, table.recrutamento tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.recrutamento tbody tr th { font-size: 13px; padding: 2px; } ';
        $stylesheet .= 'table.recrutamento tbody tr:nth-child(2) td { border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.recrutamento tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.resultado tr th, table.resultado tr td { font-size: 13px; padding: 5px; } ';
        $stylesheet .= 'table.resultado thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.resultado thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.resultado tbody tr td.success { background-color: #dff0d8; } ';
        $stylesheet .= 'table.resultado tbody tr td:nth-child(1) { vertical-align: top; word-wrap: break-word; width: 45%; } ';
        $stylesheet .= 'table.resultado tbody tr td:nth-child(2) { vertical-align: top; word-wrap: break-word;  width: 45%; word-break: break-all; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));

        $sql = "SELECT a.nome AS processo,
                       d.nome
                FROM recrutamento a
                INNER JOIN recrutamento_cargos b ON
                           b.id_recrutamento = a.id
                INNER JOIN recrutamento_candidatos c ON
                           c.id_cargo = b.id
                INNER JOIN recrutamento_usuarios d ON
                           d.id = c.id_usuario
                INNER JOIN recrutamento_testes e ON
                           e.id_candidato = c.id
                WHERE e.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output("{$row->nome} - Relatório Entrevista.pdf", 'D');
    }


}
