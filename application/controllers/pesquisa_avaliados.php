<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_avaliados extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pesquisa_model', 'pesquisa');
        $this->load->model('Funcionarios_model', 'funcionarios');
    }

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar()
    {
        $sql = "SELECT a.id
                FROM pesquisa a 
                INNER JOIN pesquisa_modelos b ON 
                           b.id = a.id_modelo 
                WHERE a.id = {$this->uri->rsegment(3)}";
        $pesquisa = $this->db->query($sql)->row();
        if (count($pesquisa) == 0) {
            show_404();
        }

        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }

        $data['empresa'] = $empresa;
        $data['pesquisa'] = $pesquisa->id;

        $this->db->select('nome, id');
        $this->db->where('empresa', $data['empresa']);
        $this->db->order_by('nome', 'ASC');
        $avaliadores = $this->db->get('usuarios')->result();

        $data['avaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['avaliadores'][$avaliador->id] = $avaliador->nome;
        }

        $data['avaliado'] = array('' => 'selecione...') + $data['avaliadores'];

        $this->load->view('pesquisa_avaliados', $data);
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
                      FROM pesquisa_avaliados a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_avaliado
                      INNER JOIN pesquisa c ON 
                                 c.id = a.id_pesquisa
                      INNER JOIN pesquisa_modelos d ON 
                                 d.id = c.id_modelo AND 
                                 d.tipo = 'P'
                      WHERE a.id_pesquisa = {$id}) s";
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
        foreach ($list as $pesquisa) {
            $row = array();
            $row[] = $pesquisa->nome;
            $row[] = $pesquisa->cargo;
            $row[] = $pesquisa->depto;
            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_participante(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i> Entrevistados</a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_participante(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('pesquisa_avaliados/relatorio/' . $pesquisa->id) . '/1" title="Relatório consolidado"><i class="glyphicon glyphicon-list-alt"></i> R. Cons.</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('pesquisa_avaliados/relatorio/' . $pesquisa->id) . '/2" title="Relatório individual" ><i class="glyphicon glyphicon-list-alt"></i> R. Ind.</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('pesquisa/status/' . $id) . '" title="Status"><i class="glyphicon glyphicon-info-sign"></i> Status</a>
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
        $row = $this->db->get_where('pesquisa_avaliados', array('id' => $id))->row();
        $data['id'] = $row->id;
        $data['avaliado'] = $row->id_avaliado;

        $avaliadores = $this->db->get_where('pesquisa_avaliadores', array('id_avaliado' => $row->id))->result();
        $data['avaliadores'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['avaliadores'][] = $avaliador->id_avaliador;
        }

        echo json_encode($data);
    }

    public function ajax_avaliados($id)
    {
        $sql = "SELECT a.id 
                FROM usuarios a
                INNER JOIN pesquisa_avaliados b ON 
                           b.id_avaliado = a.id
                WHERE a.empresa = {$this->session->userdata('empresa')} AND 
                      b.id_pesquisa = {$id}
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
        $selecionados = '';
        if (isset($where['selecionados'])) {
            $selecionados = explode(',', $where['selecionados']);
            unset($where['selecionados']);
        }
        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        foreach ($where as $k => $value) {
            $this->db->where($k, $value);
        }
        if ($selecionados) {
            $this->db->or_where_in('id', $selecionados);
        }
        $this->db->order_by('nome', 'ASC');
        $rows = $this->db->get('usuarios')->result();
        $options = array();
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $data['avaliado'] = form_dropdown('avaliado', array('' => 'selecione...') + $options, '', 'class="form-control filtro input-sm"');
        $data['avaliadores'] = form_multiselect('avaliadores', $options, array(), 'size="10" id="avaliadores" class="demo2"');

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $avaliadores = $this->input->post('avaliadores');
        if (empty($avaliadores)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum avaliador incluso')));
        }

        $data['id_pesquisa'] = $this->input->post('pesquisa');
        $data['id_avaliado'] = $this->input->post('avaliado');
        if (empty($data['id_avaliado'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum avaliado incluso')));
        }

        $this->db->trans_begin();
        $this->db->query($this->db->insert_string('pesquisa_avaliados', $data));

        $pesquisa = $data['id_pesquisa'];
        $avaliado = $this->db->insert_id();
        if ($this->db->trans_status()) {
            foreach ($avaliadores as $avaliador) {
                $data = array(
                    'id_pesquisa' => $pesquisa,
                    'id_avaliado' => $avaliado,
                    'id_avaliador' => $avaliador
                );
                $this->db->query($this->db->insert_string('pesquisa_avaliadores', $data));
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

    public function ajax_update()
    {
        $avaliadores = $this->input->post('avaliadores');
        if (empty($avaliadores)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nenhum avaliador incluso')));
        }

        $data['id'] = $this->input->post('id');
        $data['id_pesquisa'] = $this->input->post('pesquisa');
        $data['id_avaliado'] = $this->input->post('avaliado');

        $this->db->trans_begin();
        $this->db->query($this->db->update_string('pesquisa_avaliados', $data, array('id' => $data['id'])));

        if ($this->db->trans_status()) {

            $strAvaliadores = implode(',', $avaliadores);
            $delete = "DELETE FROM pesquisa_avaliadores 
                       WHERE id_avaliado = {$data['id']} AND 
                             id_avaliador NOT IN ({$strAvaliadores})";
            $this->db->query($delete);

            $select = "SELECT id, id_avaliador 
                       FROM pesquisa_avaliadores 
                       WHERE id_avaliado = {$data['id']}";
            $rows = $this->db->query($select)->result();
            $arrAvaliadores = array();
            foreach ($rows as $row) {
                $arrAvaliadores[$row->id] = $row->id_avaliador;
            }

            $keysAvaliadores = array_flip($arrAvaliadores);
            $avaliado = $data['id'];
            $pesquisa = $data['id_pesquisa'];

            foreach ($avaliadores as $avaliador) {
                $data = array(
                    'id_avaliado' => $avaliado,
                    'id_pesquisa' => $pesquisa,
                    'id_avaliador' => $avaliador
                );

                if (in_array($avaliador, $arrAvaliadores)) {
                    $where = array('id' => $keysAvaliadores[$avaliador]);
                    $this->db->query($this->db->update_string('pesquisa_avaliadores', $data, $where));
                } else {
                    $this->db->query($this->db->insert_string('pesquisa_avaliadores', $data));
                }
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

    public function ajax_delete($id)
    {
        $status = $this->db->delete('pesquisa_avaliados', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function relatorio($perfil, $tipo = 1, $pdf = false)
    {
        if (empty($perfil)) {
            $perfil = $this->uri->rsegment(3);
        }

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $sql = "SELECT a.id, 
                       b.nome, 
                       b.tipo, 
                       DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino
                FROM pesquisa a 
                INNER JOIN pesquisa_modelos b ON 
                           b.id = a.id_modelo 
                INNER JOIN pesquisa_avaliados c ON 
                           c.id_pesquisa = a.id
                INNER JOIN usuarios d ON
                           d.id = c.id_avaliado
                WHERE c.id = {$perfil}";
        $row = $this->db->query($sql)->row();
        $data['pesquisa'] = $row;

        $sql2 = "SELECT c.id,
                        d.nome,
                        d.funcao,
                        CONCAT_WS('/', d.depto, d.area, d.setor) AS depto,
                        DATE_FORMAT(d.data_admissao, '%d/%m/%Y') AS data_admissao
                 FROM pesquisa a 
                 INNER JOIN pesquisa_modelos b ON 
                            b.id = a.id_modelo 
                 INNER JOIN pesquisa_avaliados c ON 
                            c.id_pesquisa = a.id
                 INNER JOIN usuarios d ON
                            d.id = c.id_avaliado
                 WHERE c.id = {$perfil}";
        $avaliado = $this->db->query($sql2)->row();
        $data['avaliado'] = $avaliado;

        $sql3 = "SELECT a.id,
                        c.nome
                 FROM pesquisa_avaliadores a 
                 INNER JOIN pesquisa_avaliados b ON 
                            b.id = a.id_avaliado 
                 INNER JOIN usuarios c ON
                           c.id = a.id_avaliador
                 WHERE b.id = {$perfil}";
        $rows2 = $this->db->query($sql3)->result();
        $avaliadores = array();
        foreach ($rows2 as $row2) {
            $avaliadores[$row2->id] = $row2->nome;
        }
        $data['avaliadores'] = $avaliadores;

        if ($tipo == 2) {
            $sql4 = "SELECT a.id,
                            b.nome,
                            b.funcao,
                            CONCAT_WS('/', b.depto, b.area, b.setor) AS depto,
                            DATE_FORMAT(b.data_admissao, '%d/%m/%Y') AS data_admissao
                     FROM pesquisa_avaliadores a 
                     INNER JOIN usuarios b ON
                                b.id = a.id_avaliador
                     WHERE a.id = {$rows2[0]->id}";
            $selcionado = $this->db->query($sql4)->row();
            $data['selecionado'] = $selcionado;
        }

        $data['is_pdf'] = $pdf;

        if ($pdf) {

            $avaliador = $this->input->get('avaliador');

            $data['data'] = $this->get_relatorio($perfil, $avaliador);
            $data['omitirAvaliadores'] = $this->input->get('omitirAvaliadores');

            return $this->load->view('getpesquisa_relatorio', $data, true);
        } else {

            $this->load->view('pesquisa_relatorio', $data);
        }
    }

    public function ajax_relatorio($id)
    {
        $id_avaliador = $this->input->post('avaliador');

        $data = $this->get_relatorio($id, $id_avaliador);

        $output = array(
            "draw" => $this->input->post('draw'),
            "data" => $data
        );

        if ($id_avaliador) {
            $sql = "SELECT b.funcao,
                           CONCAT_WS('/', b.depto, b.area, b.setor) AS depto,
                           DATE_FORMAT(b.data_admissao, '%d/%m/%Y') AS data_admissao
                    FROM pesquisa_avaliadores a 
                    INNER JOIN usuarios b ON
                               b.id = a.id_avaliador
                    WHERE a.id = {$id_avaliador}";
            $row = $this->db->query($sql)->row();
            $output['selecionado'] = $row;
        }
        //output to json format
        echo json_encode($output);
    }

    private function get_relatorio($id, $id_avaliador)
    {
        if ($id_avaliador) {
            $avaliadores = 1;
            $strAvaliadores = $id_avaliador;
        } else {
            $sql2 = "SELECT a.id
                     FROM pesquisa_avaliadores a 
                     INNER JOIN pesquisa_avaliados b ON 
                                b.id = a.id_avaliado 
                     WHERE b.id = {$id}";
            $rows = $this->db->query($sql2)->result();
            $arr_avaliadores = array();
            foreach ($rows as $row) {
                $arr_avaliadores[] = $row->id;
            }
            $avaliadores = count($arr_avaliadores);
            $strAvaliadores = implode(',', $arr_avaliadores);
        }

        $sql3 = "SELECT a.id,
                        a.pergunta 
                 FROM pesquisa_perguntas a 
                 INNER JOIN pesquisa_modelos b ON 
                            b.id = a.id_modelo 
                 INNER JOIN pesquisa c ON
                            c.id_modelo = b.id
                 INNER JOIN pesquisa_avaliados d ON 
                            d.id_pesquisa = c.id
                 WHERE d.id = {$id} AND 
                       (a.id_categoria IS NULL OR a.id_categoria = 0)";
        $perguntas = $this->db->query($sql3)->result();

        $data = array();

        foreach ($perguntas as $pergunta) {

            $sql = "SELECT a.id, 
                           a.alternativa, 
                           a.peso, 
                           (CASE (SELECT count(d.id_alternativa) 
                                  FROM pesquisa_resultado d 
                                  WHERE d.id_avaliador IN ({$strAvaliadores}) AND 
                                        d.id_pergunta = b.id) 
                                WHEN 0 THEN null 
                                ELSE COUNT(c.id_avaliador) END) AS consolidado
                    FROM pesquisa_alternativas a
                    INNER JOIN pesquisa_modelos a2 ON 
                              a2.id = a.id_modelo
                    LEFT JOIN pesquisa_perguntas b ON 
                               b.id = a.id_pergunta OR 
                               (a.id_pergunta IS NULL AND b.id_modelo = a2.id)
                    LEFT JOIN pesquisa_resultado c ON 
                              c.id_pergunta = b.id AND c.id_alternativa = a.id AND 
                              c.id_avaliador IN ({$strAvaliadores}) 
                    WHERE b.id = {$pergunta->id} 
                    GROUP BY a.id";
            $alternativas = $this->db->query($sql)->result();

            foreach ($alternativas as $alternativa) {
                $row = array();

                $row[] = $pergunta->pergunta;
                $row[] = $alternativa->alternativa;
                $row[] = $alternativa->peso;
                $row[] = $alternativa->consolidado !== null && $alternativa->consolidado > 0 ? $alternativa->peso : null;
                $row[] = $alternativa->consolidado;
                $row[] = $alternativa->consolidado !== null ? round($alternativa->consolidado / $avaliadores * 100, 2) : null;

                $data[] = $row;
            }
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
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), $this->uri->rsegment(4), true));

        $sql = "SELECT d.nome, 
                       CASE b.tipo
                       WHEN 'C' THEN 'Clima Organizacional'
                       WHEN 'P' THEN 'Perfil Profissional'
                       WHEN 'M' THEN 'Perfil Profissional'
                       WHEN 'E' THEN 'Personalidade-Eneagrama'
                       WHEN 'D' THEN 'Personalidade-DISC'
                       WHEN 'N' THEN 'Potencial-NineBox'
                       ELSE 'Pesquisa' END AS tipo
                FROM pesquisa a 
                INNER JOIN pesquisa_modelos b ON 
                           b.id = a.id_modelo
                INNER JOIN pesquisa_avaliados c ON 
                           c.id_pesquisa = a.id
                INNER JOIN usuarios d ON 
                           d.id = c.id_avaliado
                WHERE c.id = {$this->uri->rsegment(3)}";
        $row = $this->db->query($sql)->row();

        $this->m_pdf->pdf->Output($row->tipo . ' - ' . $row->nome . '.pdf', 'D');
    }

}
