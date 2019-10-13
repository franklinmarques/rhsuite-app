<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_cargos extends MY_Controller
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
            'recrutamento' => '',
            'nome_recrutamento' => '',
        );

        if ($this->uri->rsegment(3)) {
            $this->db->select('id, nome');
            $this->db->where('id', $this->uri->rsegment(3));
            $row = $this->db->get('recrutamento')->row();

            if ($row) {
                $data['recrutamento'] = $row->id;
                $data['nome_recrutamento'] = $row->nome;
            }
        }

        $this->load->view('recrutamento_cargos', $data);
    }

    public function ajax_list($id = '')
    {
        $empresa = $this->session->userdata('empresa');

        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.cargo, 
                       s.id_candidato,
                       s.candidato,
                       s.id_usuario,
                       NULL AS aproveitamento2
                FROM (SELECT a.id, 
                             a.cargo, 
                             c.id AS id_candidato,
                             c.id_usuario,
                             d.nome AS candidato
                      FROM recrutamento_cargos a
                      INNER JOIN recrutamento b ON 
                                 b.id = a.id_recrutamento
                      LEFT JOIN recrutamento_candidatos c ON
                                c.id_cargo = a.id
                      LEFT JOIN recrutamento_usuarios d ON
                                d.id = c.id_usuario
                      WHERE b.id_usuario_EMPRESA = {$empresa}";
        if ($id) {
            $sql .= " AND b.id = '{$id}'";
        }
        $sql .= ") s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.cargo', 's.candidato');
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

        foreach ($list as $li => $query2) {
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
                           FROM recrutamento a 
                           LEFT JOIN recrutamento_cargos b ON
                                 b.id_recrutamento = a.id
                           LEFT JOIN recrutamento_candidatos c ON
                                c.id_cargo = b.id AND c.id_usuario = {$query2->id_usuario}
                      LEFT JOIN recrutamento_testes d ON 
                                d.id_candidato = c.id
                      LEFT JOIN recrutamento_modelos e ON
                                e.id = d.id_modelo 
                      WHERE c.id = '{$query2->id_candidato}' AND d.id IS NOT NULL) s";
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
        }

        $data = array();
        foreach ($list as $recrutamento) {
            $row = array();
            $row[] = $recrutamento->cargo;
            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_cargo(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_cargo(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Adicionar candidato" onclick="add_candidato(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Candidato</a>
                     ';
            $row[] = $recrutamento->candidato;
            $row[] = number_format($recrutamento->aproveitamento2, 1, ',', '');
            if ($recrutamento->candidato) {
                $row[] = '
                          <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir candidato" onclick="delete_candidato(' . "'" . $recrutamento->id_candidato . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                          <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_processos/gerenciar/' . $recrutamento->id_candidato) . '" title="Ver processo"><i class="glyphicon glyphicon-list-alt"></i> Testes seletivos</a>
                         ';
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-danger disabled"><i class="glyphicon glyphicon-trash"></i></button>
                          <button class="btn btn-sm btn-primary disabled"><i class="glyphicon glyphicon-list-alt"></i> Testes seletivos</button>
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

    public function ajax_candidatos()
    {
        $empresa = $this->session->userdata('empresa');

        $options = array(
            'estado' => array('' => '...'),
            'cidade' => array('' => 'Todas'),
            'bairro' => array('' => 'Todos'),
            'deficiencia' => array('' => 'Sem filtro'),
            'usuarios' => array('' => 'selecione ...'),
        );

        $sql = "SELECT a.cod_uf, 
                       a.uf 
                FROM estados a 
                INNER JOIN recrutamento_usuarios b ON 
                           b.estado = a.cod_uf 
                WHERE b.empresa = {$empresa}";
        $estados = $this->db->query($sql)->result();
        foreach ($estados as $estado) {
            $options['estado'][$estado->cod_uf] = $estado->uf;
        }

        $sql2 = "SELECT a.cod_mun, 
                        a.municipio 
                 FROM municipios a 
                 INNER JOIN recrutamento_usuarios b ON 
                            b.cidade = a.cod_mun 
                 WHERE b.empresa = {$empresa}";
        $cidades = $this->db->query($sql2)->result();
        foreach ($cidades as $cidade) {
            $options['cidade'][$cidade->cod_mun] = $cidade->municipio;
        }

        $this->db->distinct('bairro');
        $bairros = $this->db->get_where('recrutamento_usuarios', array('empresa' => $empresa))->result();
        foreach ($bairros as $bairro) {
            $options['bairro'][$bairro->bairro] = $bairro->bairro;
        }

        $sql3 = "SELECT a.id, 
                        a.tipo 
                 FROM deficiencias a 
                 INNER JOIN recrutamento_usuarios b ON 
                            b.deficiencia = a.id 
                 WHERE b.empresa = {$empresa}";
        $deficiencias = $this->db->query($sql3)->result();
        foreach ($deficiencias as $deficiencia) {
            $options['deficiencia'][$deficiencia->id] = $deficiencia->tipo;
        }

        $where = $this->input->post();

        $this->db->select('a.id, a.nome');
        $this->db->join('recrutamento_candidatos b', 'b.id_usuario = a.id', 'left');
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
//        $this->db->where('b.id', null);
        $this->db->order_by('a.nome', 'ASC');
        $rows = $this->db->get('recrutamento_usuarios a')->result();
        foreach ($rows as $row) {
            $options['usuarios'][$row->id] = $row->nome;
        }

        $data['estados'] = form_dropdown('estado', $options['estado'], $where['estado'], 'id="estado" class="form-control filtro input-sm"');
        $data['cidades'] = form_dropdown('cidade', $options['cidade'], $where['cidade'], 'id="cidade" class="form-control filtro input-sm"');
        $data['bairros'] = form_dropdown('bairro', $options['bairro'], $where['bairro'], 'id="bairro" class="form-control filtro input-sm"');
        $data['deficiencias'] = form_dropdown('deficiencia', $options['deficiencia'], $where['deficiencia'], 'id="deficiencia" class="form-control filtro input-sm"');
        $data['candidatos'] = form_dropdown('id_usuario', $options['usuarios'], '', 'id="id_usuario" class="form-control"');

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
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O cargo não deve ficar sem nome')));
        }

        $status = $this->db->insert('recrutamento_cargos', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addCandidato()
    {
        $data = $this->input->post();
        if (empty($data['id_usuario'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum candidato selecionado')));
        }

        $status = $this->db->insert('recrutamento_candidatos', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if (empty($data['cargo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O cargo não deve ficar sem nome')));
        }

        $where = array('id' => $data['id']);
        unset($data['id']);

        $status = $this->db->update('recrutamento_cargos', $data, $where);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('recrutamento_cargos', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_deleteCandidato($id)
    {
        $status = $this->db->delete('recrutamento_candidatos', array('id' => $id));
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
                       '{$this->input->get('depto')}' AS depto,
                       '{$this->input->get('area')}' AS area,
                       '{$this->input->get('setor')}' AS setor,
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
        $this->db->select('a.id');
        $this->db->join('usuarios b', 'b.id = a.id_avaliador');
        $this->db->where('a.id_pesquisa', $id);
        $where = array_filter(array('b.depto' => $depto, 'b.area' => $area, 'b.setor' => $setor));
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

        $stylesheet = 'table.pesquisa thead th { font-size: 11px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.pesquisa tbody tr { border-width: 5px; border-color: #ddd; } ';
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
