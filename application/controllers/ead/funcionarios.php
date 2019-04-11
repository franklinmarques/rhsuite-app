<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionarios extends MY_Controller
{

    public function index()
    {
        $data = array(
            'depto' => array('' => 'Todos'),
            'area' => array('' => 'Todas'),
            'setor' => array('' => 'Todos'),
            'treinamento' => array('' => 'Todos'),
            'query_string' => ''
        );

        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if (in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            $this->db->where('id', $this->session->userdata('id'));
        }
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $this->db->order_by('depto', 'asc');
        $rows_depto = $this->db->get('usuarios')->result();
        if (in_array($this->session->userdata('nivel'), array(9, 10, 11)) and count($rows_depto) == 1) {
            $data['depto'] = array();
        }
        foreach ($rows_depto as $row_depto) {
            $data['depto'][$row_depto->nome] = $row_depto->nome;
        }

        $this->db->select('DISTINCT(area) AS nome', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if (count($rows_depto) == 1 and isset($rows_depto[0]->nome)) {
            $this->db->where('depto', $rows_depto[0]->nome);
        }
        $this->db->where('CHAR_LENGTH(area) >', 0);
        $this->db->order_by('area', 'asc');
        $rows_area = $this->db->get('usuarios')->result();
        foreach ($rows_area as $row_area) {
            $data['area'][$row_area->nome] = $row_area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if (count($rows_depto) == 1 and isset($rows_depto[0]->nome)) {
            $this->db->where('depto', $rows_depto[0]->nome);
        }
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $this->db->order_by('setor', 'asc');
        $rows_setor = $this->db->get('usuarios')->result();
        foreach ($rows_setor as $row_setor) {
            $data['setor'][$row_setor->nome] = $row_setor->nome;
        }

        $this->db->select('DISTINCT(IFNULL(a.nome, c.nome)) AS nome', false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('cursos c', 'c.id = a.id_curso', 'left');
        $this->db->where('b.empresa', $this->session->userdata('empresa'));
        if (count($rows_depto) == 1 and isset($rows_depto[0]->nome)) {
            $this->db->where('b.depto', $rows_depto[0]->nome);
        }
        $this->db->where('CHAR_LENGTH(b.setor) >', 0);
        $this->db->order_by('nome', 'asc');
        $rows_treinamentos = $this->db->get('cursos_usuarios a')->result();
        foreach ($rows_treinamentos as $row_treinamento) {
            $data['treinamento'][$row_treinamento->nome] = $row_treinamento->nome;
        }


        $this->load->view('ead/funcionarios', $data);
    }

    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $treinamento = $this->input->post('treinamento');

        $filtro = array(
            'area' => array('' => 'Todas'),
            'setor' => array('' => 'Todos'),
            'treinamento' => array('' => 'Todos')
        );


        $this->db->select('DISTINCT(area) AS nome', false);
        $this->db->where('empresa', $empresa);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        $this->db->order_by('area', 'asc');
        $rows_area = $this->db->get('usuarios')->result();
        foreach ($rows_area as $row_area) {
            $filtro['area'][$row_area->nome] = $row_area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome', false);
        $this->db->where('empresa', $empresa);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        if ($area) {
            $this->db->where('area', $area);
        }
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        $this->db->order_by('setor', 'asc');
        $rows_setor = $this->db->get('usuarios')->result();
        foreach ($rows_setor as $row_setor) {
            $filtro['setor'][$row_setor->nome] = $row_setor->nome;
        }

        $this->db->select('DISTINCT(IFNULL(a.nome, c.nome)) AS nome', false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('cursos c', 'c.id = a.id_curso', 'left');
        $this->db->where('b.empresa', $empresa);
        $this->db->where('CHAR_LENGTH(b.setor) >', 0);
        if ($area) {
            $this->db->where('b.area', $area);
        }
        if ($depto) {
            $this->db->where('b.depto', $depto);
        }
        $this->db->order_by('nome', 'asc');
        $rows_treinamentos = $this->db->get('cursos_usuarios a')->result();
        foreach ($rows_treinamentos as $row_treinamento) {
            $filtro['treinamento'][$row_treinamento->nome] = $row_treinamento->nome;
        }


        $data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['treinamento'] = form_dropdown('treinamento', $filtro['treinamento'], $treinamento, 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    public function ajaxList()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $sql = "SELECT s.id, 
                       s.nome,
                       s.curso,
                       DATE_FORMAT(s.data_inicio, '%d/%m/%Y') AS data_inicio,
                       DATE_FORMAT(s.data_maxima, '%d/%m/%Y') AS data_maxima,
                       s.nota_aprovacao,
                       s.resultado,
                       s.status,
                       s.id_usuario,
                       s.tipo_treinamento
                FROM (SELECT a.id, 
                             a.id_usuario,
                             b.nome,
                             CASE a.tipo_treinamento 
                                  WHEN 'P' THEN a.nome
                                  ELSE c.nome
                                  END AS curso,
                             a.data_inicio,
                             a.data_maxima,
                             a.nota_aprovacao,
                             CASE a.tipo_treinamento 
                                  WHEN 'P' THEN a.avaliacao_presencial
                                  ELSE ROUND(SUM(j.peso) * 100 / SUM(g.peso), 2)
                                  END AS resultado,
                             (CASE WHEN COUNT(d.id) = COUNT(h.id) OR a.avaliacao_presencial IS NOT NULL THEN 'Concluído'
                                   WHEN COUNT(h.id) = 0 AND CURDATE() BETWEEN a.data_inicio AND a.data_maxima THEN 'Aberto'
                                   WHEN COUNT(h.id) > 0 AND CURDATE() BETWEEN a.data_inicio AND a.data_maxima THEN 'Em curso'
                                   WHEN a.data_maxima < CURDATE() THEN 'Expirado'
                                   WHEN a.data_inicio < CURDATE() THEN 'Em espera'
                                   END) AS status,
                             (CASE WHEN COUNT(d.id) = COUNT(h.id) OR a.avaliacao_presencial IS NOT NULL THEN '3'
                                   WHEN COUNT(h.id) = 0 AND CURDATE() BETWEEN a.data_inicio AND a.data_maxima THEN '1'
                                   WHEN COUNT(h.id) > 0 AND CURDATE() BETWEEN a.data_inicio AND a.data_maxima THEN '2'
                                   WHEN a.data_maxima < CURDATE() THEN '-1' 
                                   WHEN a.data_inicio < CURDATE() THEN '0'
                                   END) AS id_status,
                             a.tipo_treinamento
                      FROM cursos_usuarios a 
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_usuario 
                      LEFT JOIN cursos c ON c.id = a.id_curso 
                      LEFT JOIN cursos_paginas d ON 
                                 d.id_curso = c.id 
                      LEFT JOIN cursos_questoes e ON
                                e.id_pagina = d.id AND 
                                d.modulo = 'atividades' 
                      LEFT JOIN biblioteca_questoes f ON 
                                f.id = e.id_biblioteca AND 
                                f.tipo = 1
                      LEFT JOIN biblioteca_alternativas g ON 
                                g.id_questao = f.id AND 
                                g.peso > 0 
                      LEFT JOIN cursos_acessos h ON 
                                h.id_curso_usuario = a.id AND 
                                h.id_pagina = e.id_pagina AND 
                                h.data_finalizacao IS NOT NULL
                      LEFT JOIN cursos_resultado i ON 
                                i.id_acesso = h.id AND 
                                i.id_questao = f.id AND 
                                i.id_alternativa = g.id
                      LEFT JOIN biblioteca_alternativas j ON 
                                j.id = i.id_alternativa 
                      WHERE b.empresa = {$this->session->userdata('empresa')} 
                            AND (b.depto = '{$busca['depto']}' OR CHAR_LENGTH('{$busca['depto']}') = 0)
                            AND (b.area = '{$busca['area']}' OR CHAR_LENGTH('{$busca['area']}') = 0)
                            AND (b.setor = '{$busca['setor']}' OR CHAR_LENGTH('{$busca['setor']}') = 0)
                      GROUP BY a.id) s 
                WHERE (s.curso = '{$busca['treinamento']}' OR CHAR_LENGTH('{$busca['treinamento']}') = 0)
                      AND (s.id_status = '{$busca['status']}' OR CHAR_LENGTH('{$busca['status']}') = 0)";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.nome', 's.curso', 's.data_inicio', 's.data_maxima', 's.nota_aprovacao', 's.resultado', 's.status');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 0) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } else {
                    $sql .= " 
                        AND ({$column} LIKE '%{$post['search']['value']}%'";
                }
            }
            $sql .= ')';
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
        foreach ($list as $funcionario) {
            $row = array();
            $row[] = $funcionario->id_usuario;
            $row[] = $funcionario->nome;
            $row[] = $funcionario->curso;
            $row[] = $funcionario->data_inicio;
            $row[] = $funcionario->data_maxima;
            $row[] = $funcionario->nota_aprovacao ? round($funcionario->nota_aprovacao, 2) . '%' : $funcionario->nota_aprovacao;
            $row[] = $funcionario->resultado ? round($funcionario->resultado, 2) . '%' : $funcionario->resultado;
            $row[] = $funcionario->status;

            if ($funcionario->resultado < $funcionario->nota_aprovacao or $funcionario->tipo_treinamento == 'P') {
                $row[] = '
                          <a class="btn btn-primary btn-sm" target="_blank" href="' . site_url('ead/cursos_funcionario/editar/' . $funcionario->id) . '" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </a>
                          <button class="btn btn-danger btn-sm" onclick="ajax_delete(' . $funcionario->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                          <a class="btn btn-info btn-sm" target="_blank" href="' . site_url('ead/treinamento/status/' . $funcionario->id) . '" title="Andamento"><i class="glyphicon glyphicon-align-center"></i> </a>
                          <button class="btn btn-default text-warning btn-sm" title="Certificado"><i class="glyphicon glyphicon-certificate"></i> </button>
                         ';
            } else {
                $row[] = '
                          <a class="btn btn-primary btn-sm" target="_blank" href="' . site_url('ead/cursos_funcionario/editar/' . $funcionario->id) . '" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </a>
                          <button class="btn btn-danger btn-sm" onclick="ajax_delete(' . $funcionario->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                          <a class="btn btn-info btn-sm" target="_blank" href="' . site_url('ead/treinamento/status/' . $funcionario->id) . '" title="Andamento"><i class="glyphicon glyphicon-align-center"></i> </a>
                          <a class="btn btn-warning btn-sm" target="_blank" href="' . site_url('ead/treinamento/certificado/' . $funcionario->id) . '" title="Certificado"><i class="glyphicon glyphicon-certificate"></i> </a>
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

        echo json_encode($output);
    }

    public function relatorio()
    {
        $id_empresa = $this->session->userdata('empresa');
        $depto = $this->input->get('depto');
        $area = $this->input->get('area');
        $setor = $this->input->get('setor');
        $status = $this->input->get('status');
        $treinamento = $this->input->get('treinamento');
        $busca = $this->input->get('busca');

        $this->db->select('foto, foto_descricao');
        $empresa = $this->db->get_where('usuarios', array('id' => $id_empresa))->row();
        if (is_file('imagens/usuarios/' . $empresa->foto)) {
            $empresa->foto = base_url('imagens/usuarios/' . $empresa->foto);
        }
        if (is_file('imagens/usuarios/' . $empresa->foto_descricao)) {
            $empresa->foto_descricao = base_url('imagens/usuarios/' . $empresa->foto_descricao);
        }
        $data['empresa'] = $empresa;

        $sql = "SELECT s.id, 
                       s.total_usuarios,
                       s.nome,
                       s.curso,
                       DATE_FORMAT(s.data_inicio, '%d/%m/%Y') AS data_inicio,
                       DATE_FORMAT(s.data_maxima, '%d/%m/%Y') AS data_maxima,
                       s.nota_aprovacao,
                       s.resultado,
                       s.status,
                       s.id_usuario
                FROM (SELECT a.id, 
                             a.id_usuario,
                             (SELECT COUNT(t.id) 
                              FROM cursos_usuarios t 
                              WHERE t.id_usuario = b.id) AS total_usuarios,
                             b.nome,
                             CASE a.tipo_treinamento 
                                  WHEN 'P' THEN a.nome
                                  ELSE c.nome
                                  END AS curso,
                             a.data_inicio,
                             a.data_maxima,
                             FORMAT(a.nota_aprovacao, 'de_DE') AS nota_aprovacao,
                             CASE a.tipo_treinamento 
                                  WHEN 'P' THEN FORMAT(a.avaliacao_presencial, 'de_DE')
                                  ELSE FORMAT(ROUND(SUM(j.peso) * 100 / SUM(g.peso), 2), 'de_DE')
                                  END AS resultado,
                             (CASE WHEN COUNT(d.id) = COUNT(h.id) OR a.avaliacao_presencial IS NOT NULL THEN 'Concluído'
                                   WHEN COUNT(h.id) = 0 AND CURDATE() BETWEEN a.data_inicio AND a.data_maxima THEN 'Aberto'
                                   WHEN COUNT(h.id) > 0 AND CURDATE() BETWEEN a.data_inicio AND a.data_maxima THEN 'Em curso'
                                   WHEN a.data_maxima < CURDATE() THEN 'Expirado'
                                   WHEN a.data_inicio < CURDATE() THEN 'Em espera'
                                   END) AS status,
                             (CASE WHEN COUNT(d.id) = COUNT(h.id) OR a.avaliacao_presencial IS NOT NULL THEN '3'
                                   WHEN COUNT(h.id) = 0 AND CURDATE() BETWEEN a.data_inicio AND a.data_maxima THEN '1'
                                   WHEN COUNT(h.id) > 0 AND CURDATE() BETWEEN a.data_inicio AND a.data_maxima THEN '2'
                                   WHEN a.data_maxima < CURDATE() THEN '-1' 
                                   WHEN a.data_inicio < CURDATE() THEN '0'
                                   END) AS id_status
                      FROM cursos_usuarios a 
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_usuario 
                      LEFT JOIN cursos c ON c.id = a.id_curso 
                      LEFT JOIN cursos_paginas d ON 
                                 d.id_curso = c.id 
                      LEFT JOIN cursos_questoes e ON
                                e.id_pagina = d.id AND 
                                d.modulo = 'atividades' 
                      LEFT JOIN biblioteca_questoes f ON 
                                f.id = e.id_biblioteca AND 
                                f.tipo = 1
                      LEFT JOIN biblioteca_alternativas g ON 
                                g.id_questao = f.id AND 
                                g.peso > 0 
                      LEFT JOIN cursos_acessos h ON 
                                h.id_curso_usuario = a.id AND 
                                h.id_pagina = e.id_pagina AND 
                                h.data_finalizacao IS NOT NULL
                      LEFT JOIN cursos_resultado i ON 
                                i.id_acesso = h.id AND 
                                i.id_questao = f.id AND 
                                i.id_alternativa = g.id
                      LEFT JOIN biblioteca_alternativas j ON 
                                j.id = i.id_alternativa 
                      WHERE b.empresa = '{$id_empresa}' 
                            AND (b.depto = '{$depto}' OR CHAR_LENGTH('{$depto}') = 0)
                            AND (b.area = '{$area}' OR CHAR_LENGTH('{$area}') = 0)
                            AND (b.setor = '{$setor}' OR CHAR_LENGTH('{$setor}') = 0)
                      GROUP BY a.id) s 
                WHERE (s.id_status = '{$status}' OR CHAR_LENGTH('{$status}') = 0)
                      AND (s.curso = '{$treinamento}' OR CHAR_LENGTH('{$treinamento}') = 0)
                      AND (CASE WHEN CHAR_LENGTH('{$busca}') > 0 THEN (s.nome LIKE '%{$busca}%' OR s.curso LIKE '%{$busca}%') ELSE 1 = 1 END)
                ORDER BY s.nome, 
                         s.curso, 
                         s.data_inicio, 
                         s.data_maxima";

        $data['rows'] = $this->db->query($sql)->result();

        return $this->load->view('ead/funcionariosPdf', $data, true);
    }

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#treinamentos thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#treinamentos thead tr, #treinamentos tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#treinamentos tbody td { font-size: 10px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 10px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio());

        $this->m_pdf->pdf->Output('Alocação de treinamentos.pdf', 'D');
    }

}
