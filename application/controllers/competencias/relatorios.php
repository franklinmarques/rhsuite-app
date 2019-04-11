<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Relatorios_model', 'cargos');
        $this->load->model('gerenciarAvaliacao_model', 'gerenciarAvaliacao');
        $this->load->model('competencias_model', 'competencias');
        $this->load->model('Dimensao_model', 'dimensao');
    }

    public function index()
    {
        $data['id_competencia'] = $this->uri->rsegment(3);
        $data['id_empresa'] = $this->session->userdata('id');

        $this->load->view('competencias/relatorios', $data);
    }

    public function ajax_list($id, $empresa)
    {
        $post = $this->input->post();
        $sql = "SELECT s.id, 
                       s.nome, 
                       s.cargo_funcao,
                       s.id_usuario
                FROM (SELECT a.id, 
                             b.id AS id_usuario, 
                             b.nome, 
                             CONCAT_WS('/', b.cargo, b.funcao) as cargo_funcao 
                      FROM competencias_avaliados a 
                      INNER JOIN usuarios b ON 
                                 a.id_usuario = b.id
                      INNER JOIN competencias c ON 
                                 c.id = a.id_competencia 
                      WHERE a.id_competencia = {$id} AND 
                            c.id_usuario_EMPRESA = {$empresa}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.cargo_funcao');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " AND 
                        ({$column} LIKE '%{$post['search']['value']}%'";
                }
            }
            $sql .= ')';
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        } else {
            $sql .= ' 
                    ORDER BY 2';
        }
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $avaliacao) {
            $row = array();
            $row[] = $avaliacao->nome;
            $row[] = $avaliacao->cargo_funcao;
            $row[] = '
                     <a class="btn btn-sm btn-info" href="' . site_url('competencias/relatorios/analise_gap/' . $avaliacao->id) . '" title="Analise de GAPs"><i class="glyphicon glyphicon-list-alt"> </i> Analise de GAPs</a>
                     <a class="btn btn-sm btn-success" href="' . site_url('competencias/relatorios/avaliado_avaliadores/' . $avaliacao->id) . '" title="Avaliado x Avaliadores"><i class="glyphicon glyphicon-list-alt"> </i> Avaliado x Avaliadores</a>
                     <a class="btn btn-sm btn-warning" href="' . site_url('pdi/gerenciar/' . $avaliacao->id_usuario) . '" title="PDIs - Planos de Desenvolvimento Individuais" target="_blank">PDIs</a>
                     ';

            $data[] = $row;
        }

        $output = array(
            'draw' => $post['draw'],
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function analise_gap($id_avaliado, $pdf = false)
    {
        if (empty($id_avaliado)) {
            $id_avaliado = $this->uri->rsegment(3, 0);
        }

        $sql = "SELECT c.id,
                       c.nome AS competencia, 
                       c.id_cargo,
                       a.id AS id_avaliado,
                       b.id AS id_usuario, 
                       b.nome AS avaliado,                        
                       DATE_FORMAT(c.data_inicio,'%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(c.data_termino,'%d/%m/%Y') AS data_termino, 
                       DATE_FORMAT(curdate(),'%d/%m/%Y') AS data_atual, 
                       (CASE WHEN curdate() between data_inicio and data_termino 
                        THEN 'ok' 
                        ELSE 'expirado' END) AS data_valida
                FROM competencias_avaliados a
                INNER JOIN usuarios b ON
                            b.id = a.id_usuario
                INNER JOIN competencias c ON
                           c.id = a.id_competencia
                WHERE a.id = {$id_avaliado}";
        $avaliacao = $this->db->query($sql)->row();
        if (empty($avaliacao)) {
            die();
        }

        $this->db->select('a.id, b.id AS id_usuario, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id_avaliado', $id_avaliado);
        $avaliadores = $this->db->get('competencias_avaliadores a')->result();
        $arrAvaliadores = array();
        foreach ($avaliadores as $avaliador) {
            $arrAvaliadores[$avaliador->id] = null;
        }
        if ($avaliadores % 2 == 1) {
            $avaliadores[] = (object) array('id' => null, 'nome' => null, 'is_avaliado' => '');
        }

        $this->db->where('id', $avaliacao->id_cargo);
        $cargo = $this->db->get('cargos')->row();
        if (empty($cargo)) {
            print_r('Cargo n&atilde;o encontrado!');
            exit;
        }
        $cargo->ntctf = 0;
        $cargo->ntccf = 0;
        $cargo->ntct = null;
        $cargo->ntcc = null;
        $cargo->idc = null;
        $cargo->idcPerc = null;

        $this->db->where('id_cargo', $cargo->id);
        $competencias = $this->db->get('cargos_competencias')->result();

        $cargo_ntct = $arrAvaliadores;
        $cargo_ntcc = $arrAvaliadores;
        foreach ($competencias as $competencia) {

            $idc = $arrAvaliadores;
            $idcf = null;

            $this->db->where('cargo_competencia', $competencia->id);
            $dimensoes = $this->db->get('cargos_dimensao')->result();

            foreach ($dimensoes as $dimensao) {

                $dimensao->idc = $dimensao->peso / 100 * $dimensao->atitude / 100 * $dimensao->nivel;
                $idcf += $dimensao->idc;

                $this->db->select('a.id, b.nivel, b.atitude');
                $this->db->select("{$dimensao->peso} / 100 * atitude / 100 * nivel AS idc", false);
                $this->db->join('competencias_resultado b', "b.id_avaliador = a.id AND b.cargo_dimensao = {$dimensao->id}", 'left');
                $this->db->where_in('a.id', array_keys($arrAvaliadores));
                $colaboradores = $this->db->get('competencias_avaliadores a')->result();

                $nivel = null;
                $atitude = null;
                $idco = null;
                $media = $arrAvaliadores;
                foreach ($colaboradores as $colaborador) {
                    if ($colaborador->atitude !== null || $colaborador->nivel !== null) {
                        $nivel += $colaborador->nivel;
                        $atitude += $colaborador->atitude;
                        $idco += $colaborador->idc;
                        $media[$colaborador->id] = true;
                        $idc[$colaborador->id] += $colaborador->idc;
                    }
                }

                $qtde_avaliadores = count(array_filter($media));

                $gap = new stdClass();
                if ($qtde_avaliadores) {
                    $gap->nivel = round($nivel / $qtde_avaliadores, 4);
                    $gap->atitude = round($atitude / $qtde_avaliadores, 4);
                    $gap->idc = round($idco / $qtde_avaliadores, 4);
                    $gap->idcPerc = round(($idco / $qtde_avaliadores) / $dimensao->idc * 100 - 100, 2);
                } else {
                    $gap->nivel = null;
                    $gap->atitude = null;
                    $gap->idc = null;
                    $gap->idcPerc = null;
                }

                $dimensao->colaborador = $gap;

                $competencia->dimensao[$dimensao->id] = $dimensao;
            }

            if ($competencia->tipo_competencia === 'T') {
                $cargo->ntctf += ($competencia->peso / 100 * $idcf);
                foreach ($idc as $k => $row) {
                    if ($row !== null) {
                        $cargo_ntct[$k] += ($competencia->peso / 100 * $idc[$k]);
                    }
                }
            } elseif ($competencia->tipo_competencia === 'C') {
                $cargo->ntccf += ($competencia->peso / 100 * $idcf);
                foreach ($idc as $k => $row) {
                    if ($row !== null) {
                        $cargo_ntcc[$k] += ($competencia->peso / 100 * $idc[$k]);
                    }
                }
            }
        }

        $cargo_idcf = ($cargo->ntctf / 100 * $cargo->peso_competencias_tecnicas) + ($cargo->ntccf / 100 * $cargo->peso_competencias_comportamentais);
        $cargo->idcf = round($cargo_idcf, 4);

        foreach ($cargo_ntct as $k => $row) {
            if ($row === null) {
                unset($cargo_ntct[$k]);
            }
        }
        foreach ($cargo_ntcc as $k => $row) {
            if ($row === null) {
                unset($cargo_ntcc[$k]);
            }
        }
        if ($cargo_ntct && $cargo_ntcc) {
            $media_ntct = array_sum($cargo_ntct) / count($cargo_ntct);
            $media_ntcc = array_sum($cargo_ntcc) / count($cargo_ntcc);
            $cargo->ntct = round($media_ntct, 4);
            $cargo->ntcc = round($media_ntcc, 4);

            $idc = ($media_ntct / 100 * $cargo->peso_competencias_tecnicas) + ($media_ntcc / 100 * $cargo->peso_competencias_comportamentais);
            $cargo->idc = round($idc, 4);
            $cargo->idcPerc = round($idc / $cargo_idcf * 100, 4) - 100;
        }

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $vars['empresa'] = $this->db->get('usuarios')->row();

        $vars['is_pdf'] = $pdf;
        $vars['dadosAvaliacao'] = $avaliacao;
        $vars['dadosAvaliadores'] = $avaliadores;
        $vars['dadosCargoFuncao'] = $cargo;
        $vars['dadosCompetencias'] = $competencias;

        if ($pdf) {
            $vars['exibirAvaliadores'] = $this->input->get('avaliadores');
            return $this->load->view('competencias/pdf_analise_gap', $vars, true);
        } else {
            $vars['exibirAvaliadores'] = true;
            $this->load->view('competencias/analise_gap', $vars);
        }
    }

    public function avaliado_avaliadores($id_avaliado, $pdf = false)
    {
        if (empty($id_avaliado)) {
            $id_avaliado = $this->uri->rsegment(3, 0);
        }

        $sql = "SELECT c.id,
                       c.nome AS competencia, 
                       c.id_cargo,
                       a.id AS id_avaliado,
                       b.id AS id_usuario, 
                       b.nome AS avaliado,                        
                       DATE_FORMAT(c.data_inicio,'%d/%m/%Y') AS data_inicio, 
                       DATE_FORMAT(c.data_termino,'%d/%m/%Y') AS data_termino, 
                       DATE_FORMAT(curdate(),'%d/%m/%Y') AS data_atual, 
                       (CASE WHEN curdate() between data_inicio and data_termino 
                        THEN 'ok' 
                        ELSE 'expirado' END) AS data_valida
                FROM competencias_avaliados a
                INNER JOIN usuarios b ON
                            b.id = a.id_usuario
                INNER JOIN competencias c ON
                           c.id = a.id_competencia
                WHERE a.id = {$id_avaliado}";
        $avaliacao = $this->db->query($sql)->row();
        if (empty($avaliacao)) {
            die();
        }

        $this->db->select('a.id, b.id AS id_usuario, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id_avaliado', $id_avaliado);
        $avaliadores = $this->db->get('competencias_avaliadores a')->result();
        $arrAvaliadores = array();
        foreach ($avaliadores as $avaliador) {
            $arrAvaliadores[$avaliador->id] = null;
        }
        if ($avaliadores % 2 == 1) {
            $avaliadores[] = (object) array('id' => null, 'nome' => null, 'is_avaliado' => '');
        }

        $this->db->where('id', $avaliacao->id_cargo);
        $cargo = $this->db->get('cargos')->row();
        if (empty($cargo)) {
            print_r('Cargo n&atilde;o encontrado!');
            exit;
        }
        $cargo->ntctf = 0;
        $cargo->ntccf = 0;
        $cargo->ntct = null;
        $cargo->ntcc = null;
        $cargo->idc = null;
        $cargo->idcPerc = null;

        $this->db->where('id_cargo', $cargo->id);
        $competencias = $this->db->get('cargos_competencias')->result();

        $cargo_ntct = $arrAvaliadores;
        $cargo_ntcc = $arrAvaliadores;
        foreach ($competencias as $competencia) {

            $idc = $arrAvaliadores;
            $idcf = null;

            $this->db->where('cargo_competencia', $competencia->id);
            $dimensoes = $this->db->get('cargos_dimensao')->result();

            foreach ($dimensoes as $dimensao) {

                $dimensao->idc = $dimensao->peso / 100 * $dimensao->atitude / 100 * $dimensao->nivel;
                $idcf += $dimensao->idc;

                $this->db->select('a.id, b.nivel, b.atitude');
                $this->db->select("{$dimensao->peso} / 100 * atitude / 100 * nivel AS idc", false);
                $this->db->join('competencias_resultado b', "b.id_avaliador = a.id AND b.cargo_dimensao = {$dimensao->id}", 'left');
                $this->db->where_in('a.id', array_keys($arrAvaliadores));
                $colaboradores = $this->db->get('competencias_avaliadores a')->result();

                foreach ($colaboradores as $colaborador) {
                    if ($colaborador->atitude !== null || $colaborador->nivel !== null) {
                        $idc[$colaborador->id] += $colaborador->idc;
                    }
                }

                $dimensao->colaboradores = $colaboradores;

                $competencia->dimensao[$dimensao->id] = $dimensao;
            }

            if ($competencia->tipo_competencia === 'T') {
                $cargo->ntctf += ($competencia->peso / 100 * $idcf);
                foreach ($idc as $k => $row) {
                    if ($row !== null) {
                        $cargo_ntct[$k] += ($competencia->peso / 100 * $idc[$k]);
                    }
                }
            } elseif ($competencia->tipo_competencia === 'C') {
                $cargo->ntccf += ($competencia->peso / 100 * $idcf);
                foreach ($idc as $k => $row) {
                    if ($row !== null) {
                        $cargo_ntcc[$k] += ($competencia->peso / 100 * $idc[$k]);
                    }
                }
            }
        }

        $cargo_idcf = ($cargo->ntctf / 100 * $cargo->peso_competencias_tecnicas) + ($cargo->ntccf / 100 * $cargo->peso_competencias_comportamentais);
        $cargo->idcf = round($cargo_idcf, 4);

        foreach ($cargo_ntct as $k => $row) {
            if ($row === null) {
                unset($cargo_ntct[$k]);
            }
        }
        foreach ($cargo_ntcc as $k => $row) {
            if ($row === null) {
                unset($cargo_ntcc[$k]);
            }
        }
        if ($cargo_ntct && $cargo_ntcc) {
            $media_ntct = array_sum($cargo_ntct) / count($cargo_ntct);
            $media_ntcc = array_sum($cargo_ntcc) / count($cargo_ntcc);
            $cargo->ntct = round($media_ntct, 4);
            $cargo->ntcc = round($media_ntcc, 4);

            $idc = ($media_ntct / 100 * $cargo->peso_competencias_tecnicas) + ($media_ntcc / 100 * $cargo->peso_competencias_comportamentais);
            $cargo->idc = round($idc, 4);
            $cargo->idcPerc = round($idc / $cargo_idcf * 100, 4) - 100;
        }

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $vars['empresa'] = $this->db->get('usuarios')->row();

        $vars['is_pdf'] = $pdf;
        $vars['dadosAvaliacao'] = $avaliacao;
        $vars['dadosAvaliadores'] = $avaliadores;
        $vars['dadosCargoFuncao'] = $cargo;
        $vars['dadosCompetencias'] = $competencias;

        if ($pdf) {
            $vars['exibirAvaliadores'] = $this->input->get('avaliadores');
            return $this->load->view('competencias/pdf_avaliado_avaliadores', $vars, true);
        } else {
            $vars['exibirAvaliadores'] = true;
            $this->load->view('competencias/avaliado_avaliadores', $vars);
        }
    }

    public function analise_comparativa($id_avaliacao, $pdf = false)
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor');
        $vars = array_combine($arrSql, array_pad(array(), count($arrSql), array()));
        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $vars[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $vars[$field][$row[$field]] = $row[$field];
            }
        }

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $vars['empresa'] = $this->db->get('usuarios')->row();

        $this->db->select("id, nome, DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio", false);
        $this->db->select("DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino", false);
        $this->db->where('id', $id_avaliacao);
        $avaliacao = $this->db->get('competencias')->row();
        $vars['avaliacao'] = $avaliacao;
        $vars['data_atual'] = date('d/m/Y');

        $this->db->select('a.id_usuario AS avaliado, d.id AS id_cargo, b.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('competencias c', 'c.id = a.id_competencia');
        $this->db->join('cargos d', 'd.id = c.id_cargo');
        $this->db->where('c.id', $id_avaliacao);
        $avaliados = $this->input->get('avaliados');
        if ($avaliados) {
            $this->db->where_in('a.id_usuario', $this->input->get('avaliados'));
        }
        $vars['avaliados'] = $this->db->get('competencias_avaliados a')->result();

        $cargos = array();
        $arrAvaliados = array();
        $vars['avaliadores'] = array();
        foreach ($vars['avaliados'] as $avaliado) {
            $vars['avaliadores'][$avaliado->id] = $avaliado->nome;
            $arrAvaliados[] = $avaliado->avaliado;
            $cargos[] = $avaliado->id_cargo;
        }
        $cargos = array_unique($cargos);
        $strAvaliados = implode(',', $arrAvaliados);

        foreach ($cargos as $k => $cargo) {
            $this->db->where('id_cargo', $cargo);
            $competencias = $this->db->get('cargos_competencias')->result();

            foreach ($competencias as $competencia) {
                $this->db->select('id, nome, nivel, atitude');
                $this->db->select('(((CAST(peso AS DECIMAL) / 100) * nivel) * (atitude / 100)) AS IDc', false);
                $this->db->where('cargo_competencia', $competencia->id);
                $competencia->dimensao = $this->db->get('cargos_dimensao')->result();

                foreach ($competencia->dimensao as $dimensao) {
                    if ($avaliados) {
                        $sql = "SELECT s.id, 
                                   s.nome, 
                                   SUM(s.nivel) / COUNT(s.avaliador) as nivel, 
                                   SUM(s.atitude) / COUNT(s.avaliador) as atitude,
                                   (((CAST(s.peso AS DECIMAL) / 100) * (SUM(s.nivel) / COUNT(s.avaliador))) * ((SUM(s.atitude) / COUNT(s.avaliador)) / 100)) AS IDc
                            FROM (SELECT a.id, 
                                         a.nome, 
                                         e.nivel,
                                         e.atitude, 
                                         d.id_usuario AS avaliador,
                                         f.peso
                                  FROM usuarios a
                                  INNER JOIN competencias_avaliados b ON
                                             b.id_usuario = a.id
                                  INNER JOIN competencias c ON 
                                             c.id = b.id_competencia
                                  INNER JOIN competencias_avaliadores d ON 
                                             d.id_avaliado = b.id
                                  LEFT JOIN competencias_resultado e ON 
                                            e.id_avaliador = d.id AND
                                            e.cargo_dimensao = {$dimensao->id} 
                                  LEFT JOIN cargos_dimensao f ON 
                                            f.id = e.cargo_dimensao 
                                  WHERE c.id = {$id_avaliacao} AND a.id IN({$strAvaliados})) s 
                            GROUP BY s.id";
                        $dimensao->colaboradores = $this->db->query($sql)->result();

                        foreach ($dimensao->colaboradores as $colaborador) {
                            $colaborador->nivel = $colaborador->nivel !== null ? round($colaborador->nivel, 4) : null;
                            $colaborador->atitude = $colaborador->atitude !== null ? round($colaborador->atitude, 4) : null;
                            $colaborador->IDc = $colaborador->IDc !== null ? round($colaborador->IDc, 4) : null;
                        }
                    }
                    $dimensao->nivel = $dimensao->nivel !== null ? round($dimensao->nivel, 4) : null;
                    $dimensao->atitude = $dimensao->atitude !== null ? round($dimensao->atitude, 4) : null;
                    $dimensao->IDc = $dimensao->IDc !== null ? round($dimensao->IDc, 4) : null;
                }
            }
            $vars['cargos'][$k] = $competencias;
        }

        $vars['is_pdf'] = $pdf;
        if ($pdf) {
            $vars['exibirAvaliadores'] = $this->input->get('avaliadores');
            return $this->load->view('competencias/pdf_analise_comparativa', $vars, true);
        } else {
            $vars['exibirAvaliadores'] = true;
            $this->load->view('competencias/analise_comparativa', $vars);
        }
    }

    public function ajax_analiseComparativa()
    {
        $id_avaliacao = $this->input->post('id_avaliacao');
        $id_avaliados = $this->input->post('avaliados');
        $vars = array();
        if ($id_avaliados) {
            $avaliados = implode(',', $id_avaliados);
        } else {
            echo json_encode($vars);
            exit();
        }

        $this->db->select('distinct(c.id)');
        $this->db->join('competencias_avaliados b', 'b.id_competencia = a.id');
        $this->db->join('cargos c', 'c.id = a.id_cargo');
        $this->db->where('a.id', $id_avaliacao);
        $this->db->where_in('b.id_usuario', $id_avaliados);
        $rows = $this->db->get('competencias a')->result();

        $cargos = array();
        foreach ($rows as $row) {
            $cargos[] = $row->id;
        }

        foreach ($cargos as $k => $cargo) {
            $this->db->select('id, nome');
            $this->db->where('id_cargo', $cargo);
            $competencias = $this->db->get('cargos_competencias')->result();

            foreach ($competencias as $competencia) {
                $this->db->select('id, nome, nivel, atitude', true);
                $this->db->select('(((CAST(peso AS DECIMAL) / 100) * nivel) * (atitude / 100)) AS IDc', false);
                $this->db->where('cargo_competencia', $competencia->id);
                $competencia->dimensao = $this->db->get('cargos_dimensao')->result();

                foreach ($competencia->dimensao as $dimensao) {
                    $sql = "SELECT s.id, 
                                   s.nome, 
                                   SUM(s.nivel) / COUNT(s.nivel) as nivel, 
                                   SUM(s.atitude) / COUNT(s.atitude) as atitude,
                                   SUM(s.IDc) / COUNT(s.IDc) as IDc
                            FROM (SELECT a.id, 
                                         a.nome, 
                                         e.nivel,
                                         e.atitude, 
                                         f.peso / 100 * e.atitude / 100 * e.nivel AS IDc
                                  FROM usuarios a
                                  INNER JOIN competencias_avaliados b ON
                                             b.id_usuario = a.id
                                  INNER JOIN competencias c ON 
                                             c.id = b.id_competencia
                                  INNER JOIN competencias_avaliadores d ON 
                                             d.id_avaliado = b.id
                                  LEFT JOIN competencias_resultado e ON 
                                            e.id_avaliador = d.id AND
                                            e.cargo_dimensao = {$dimensao->id} 
                                  LEFT JOIN cargos_dimensao f ON 
                                            f.id = e.cargo_dimensao 
                                  WHERE c.id = {$id_avaliacao} AND a.id IN({$avaliados})) s 
                            GROUP BY s.id";
                    $dimensao->colaboradores = $this->db->query($sql)->result();

                    $dados = array();
                    foreach ($dimensao->colaboradores as $colaborador) {
                        if ($colaborador->nivel !== null) {
                            $colaborador->nivel = round($colaborador->nivel, 4);
                            $colaborador->gapNivel = $dimensao->nivel ? round(($colaborador->nivel / $dimensao->nivel - 1) * 100, 2) : 0;
                        } else {
                            $colaborador->nivel = null;
                            $colaborador->gapNivel = null;
                        }

                        if ($colaborador->atitude !== null) {
                            $colaborador->atitude = round($colaborador->atitude, 4);
                            $colaborador->gapAtitude = $dimensao->atitude ? round(($colaborador->atitude / $dimensao->atitude - 1) * 100, 2) : 0;
                        } else {
                            $colaborador->atitude = null;
                            $colaborador->gapAtitude = null;
                        }

                        if ($colaborador->IDc !== null) {
                            $colaborador->IDc = round($colaborador->IDc, 4);
                            $colaborador->gapIDc = $dimensao->IDc ? round(($colaborador->IDc / $dimensao->IDc - 1) * 100, 2) : 0;
                        } else {
                            $colaborador->IDc = null;
                            $colaborador->gapIDc = null;
                        }

                        $dados[] = array(
                            $colaborador->nome,
                            $colaborador->nivel,
                            $colaborador->gapNivel,
                            $colaborador->atitude,
                            $colaborador->gapAtitude,
                            $colaborador->IDc,
                            $colaborador->gapIDc
                        );
                    }

                    $dimensao->nivel = $dimensao->nivel !== null ? round($dimensao->nivel, 4) : null;
                    $dimensao->atitude = $dimensao->atitude !== null ? round($dimensao->atitude, 4) : null;
                    $dimensao->IDc = $dimensao->IDc !== null ? round($dimensao->IDc, 4) : null;

                    $vars[] = array(
                        'id' => $dimensao->id,
                        'dados' => $dados
                    );
                }
            }
        }

        echo json_encode($vars);
    }

    public function pdfAvaliado_avaliadores()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.avaliacao thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.avaliacao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador { border-width: 1px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody td { font-size: 12px; padding: 5px; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador td { width: 50%; border-width: 1px; border-color: #ddd; } ';

        $stylesheet .= 'table.avaliado thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= 'table.avaliado tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.avaliado tbody td:nth-child(1) { text-align: left; } ';

        $stylesheet .= 'table.competencias thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5; } ';
        $stylesheet .= 'table.competencias thead tr:nth-child(1) th { background-color: #dff0d8; } ';
        $stylesheet .= 'table.competencias tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.competencias tbody td:nth-child(1) { text-align: left; }';


        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->avaliado_avaliadores($this->uri->rsegment(3), true));

        $this->db->select('b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id', $this->uri->rsegment(3));
        $row = $this->db->get('competencias_avaliados a')->row();

        $this->m_pdf->pdf->Output("Aval-{$row->nome}.pdf", 'D');
    }

    public function pdfAnalise_comparativa()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.avaliacao thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.avaliacao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador { border-width: 1px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody td { font-size: 12px; padding: 5px; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador td { width: 50%; border-width: 1px; border-color: #ddd; } ';

        $stylesheet .= 'table.avaliado thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= 'table.avaliado tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.avaliado tbody td:nth-child(1) { text-align: left; } ';

        $stylesheet .= 'section.panel { border-color: #ddd; } ';
        $stylesheet .= 'section.panel panel-heading { background: #000; font-size: 14px; font-weight: bold; } ';
        $stylesheet .= 'table.competencias thead td { font-size: 12px; padding: 5px; background: #dff0d8; color: #3c763d; font-weight: bold; } ';
        $stylesheet .= 'table.competencias thead tr th { font-size: 12px; font-weight: bold; } ';
        $stylesheet .= 'table.competencias tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.competencias tbody td:nth-child(1) { text-align: left; }';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->analise_comparativa($this->uri->rsegment(3), true));

        $this->db->select('nome');
        $this->db->where('id', $this->uri->rsegment(3));
        $row = $this->db->get('competencias')->row();

        $this->m_pdf->pdf->Output('AComp_-_' . $row->nome . '.pdf', 'D');
    }

    public function pdfAndamento()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.avaliacao thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.avaliacao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador { border-width: 1px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody td { font-size: 12px; padding: 5px; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador td { width: 50%; border-width: 1px; border-color: #ddd; } ';

        $stylesheet .= 'table.avaliado thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= 'table.avaliado tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.avaliado tbody td:nth-child(1) { text-align: left; } ';

        $stylesheet .= 'table.competencias thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5; } ';
        $stylesheet .= 'table.competencias thead tr:nth-child(1) th { background-color: #dff0d8; } ';
        $stylesheet .= 'table.competencias tbody td { font-size: 11px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.competencias tbody td:nth-child(1) { text-align: left; }';


        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->andamento($this->uri->rsegment(3), true));

        $this->db->select('nome');
        $this->db->where('id', $this->uri->rsegment(3));
        $row = $this->db->get('competencias')->row();

        $this->m_pdf->pdf->Output('andamento_-_' . $row->nome . '.pdf', 'D');
    }

    public function pdfAnaliseGap()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.avaliacao thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.avaliacao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador { border-width: 1px; border-color: #ddd; } ';
        $stylesheet .= 'table.avaliacao tbody td { font-size: 12px; padding: 5px; } ';
        $stylesheet .= 'table.avaliacao tbody tr.avaliador td { width: 50%; border-width: 1px; border-color: #ddd; } ';

        $stylesheet .= 'table.avaliado thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= 'table.avaliado tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.avaliado tbody td:nth-child(1) { text-align: left; } ';

        $stylesheet .= 'table.competencias thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5; } ';
        $stylesheet .= 'table.competencias thead tr:nth-child(1) th { background-color: #dff0d8; } ';
        $stylesheet .= 'table.competencias tbody td { font-size: 12px; padding: 5px; text-align: right; } ';
        $stylesheet .= 'table.competencias tbody td:nth-child(1) { text-align: left; }';


        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->analise_gap($this->uri->rsegment(3), true));

        $this->db->select('b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id', $this->uri->rsegment(3));
        $row = $this->db->get('competencias_avaliados a')->row();

        $this->m_pdf->pdf->Output("GAP-{$row->nome}.pdf", 'D');
    }

    public function andamento($id_competencia, $pdf = false)
    {
        $vars = array();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $vars['empresa'] = $this->db->get('usuarios')->row();

        $sql_cabecalho = "SELECT nome, 
                                 DATE_FORMAT(data_inicio,'%d/%m/%Y') AS data_inicio, 
                                 DATE_FORMAT(data_termino,'%d/%m/%Y') AS data_termino, 
                                 DATE_FORMAT(curdate(),'%d/%m/%Y') AS data_atual, 
                                 case when curdate() between data_inicio and data_termino 
                                      then 'ok' 
                                      else 'expirado' end AS data_valida 
                          FROM competencias 
                          WHERE id = {$id_competencia}";
        $vars['dadosAvaliacao'] = $this->db->query($sql_cabecalho)->row();

        $this->db->select("b.id, b.nome, '' AS dimensao", false);
        $this->db->join('cargos_competencias b', 'b.id_cargo = a.id_cargo');
        $this->db->where('a.id', $id_competencia);
        $competencias = $this->db->get('competencias a')->result();
        $arrayRel = array();
        foreach ($competencias as $competencia) {
            $competencia->dimensao = array();
            $arrayRel[$competencia->id] = $competencia;
        }

        $status = $this->input->get('status');

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.avaliador, 
                       s.avaliado,
                       s.status
                FROM (SELECT a.id, 
                             a.nome,
                             h.nome AS avaliador, 
                             f.nome AS avaliado,
                             (CASE WHEN count(b.id) = count(i.id) 
                              THEN 'Avaliado'
                              ELSE 'Avaliar' END) AS status
                      FROM cargos_competencias a
                      INNER JOIN cargos_dimensao b ON 
                                 b.cargo_competencia = a.id 
                      INNER JOIN cargos c ON 
                                 c.id = a.id_cargo
                      INNER JOIN competencias d ON 
                                 d.id_cargo = c.id
                      INNER JOIN competencias_avaliados e ON 
                                 e.id_competencia = d.id
                      INNER JOIN usuarios f ON 
                                 f.id = e.id_usuario
                      INNER JOIN competencias_avaliadores g ON 
                                 g.id_avaliado = e.id
                      INNER JOIN usuarios h ON 
                                 h.id = g.id_usuario
                      LEFT JOIN competencias_resultado i ON 
                                i.id_avaliador = g.id AND 
                                i.cargo_dimensao = b.id
                      WHERE d.id = {$id_competencia}
                      GROUP BY a.id, 
                               g.id) s ";
        if ($status == 'avaliado') {
            $sql .= "WHERE s.status = 'avaliado'";
        } elseif ($status == 'avaliar') {
            $sql .= "WHERE s.status = 'avaliar'";
        }

        $rows = $this->db->query($sql)->result();
        foreach ($rows as $row) {
            $arrayRel[$row->id]->dimensao[] = array(
                "avaliador" => $row->avaliador,
                "avaliado" => $row->avaliado,
                "status" => $row->status
            );
        }

        $vars['dadosAvaliadores'] = $arrayRel;
        $vars['is_pdf'] = $pdf;

        if ($pdf) {
            return $this->load->view('competencias/pdf_andamento', $vars, true);
        } else {
            $this->load->view('competencias/andamento', $vars);
        }
    }

    public function ajax_andamento()
    {
        $id_competencia = $this->input->post('id');
        $status = $this->input->post('status');

        $this->db->select("b.id, b.nome, '' AS dimensao", false);
        $this->db->join('cargos_competencias b', 'b.id_cargo = a.id_cargo');
        $this->db->where('a.id', $id_competencia);
        $competencias = $this->db->get('competencias a')->result();
        $arrayRel = array();
        foreach ($competencias as $competencia) {
            $competencia->dimensao = array();
            $arrayRel[$competencia->id] = $competencia;
        }

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.avaliador, 
                       s.avaliado,
                       s.status
                FROM (SELECT a.id, 
                             a.nome,
                             h.nome AS avaliador, 
                             f.nome AS avaliado,
                             (CASE WHEN count(b.id) = count(i.id) 
                              THEN 'Avaliado'
                              ELSE 'Avaliar' END) AS status
                      FROM cargos_competencias a
                      INNER JOIN cargos_dimensao b ON 
                                 b.cargo_competencia = a.id 
                      INNER JOIN cargos c ON 
                                 c.id = a.id_cargo
                      INNER JOIN competencias d ON 
                                 d.id_cargo = c.id
                      INNER JOIN competencias_avaliados e ON 
                                 e.id_competencia = d.id
                      INNER JOIN usuarios f ON 
                                 f.id = e.id_usuario
                      INNER JOIN competencias_avaliadores g ON 
                                 g.id_avaliado = e.id
                      INNER JOIN usuarios h ON 
                                 h.id = g.id_usuario
                      LEFT JOIN competencias_resultado i ON 
                                i.id_avaliador = g.id AND 
                                i.cargo_dimensao = b.id
                      WHERE d.id = {$id_competencia}
                      GROUP BY a.id, 
                               g.id) s ";
        if ($status == 'avaliado') {
            $sql .= "WHERE s.status = 'avaliado'";
        } elseif ($status == 'avaliar') {
            $sql .= "WHERE s.status = 'avaliar'";
        }
        $rows = $this->db->query($sql)->result();
        foreach ($rows as $row) {
            $arrayRel[$row->id]->dimensao[] = array(
                $row->avaliador,
                $row->avaliado,
                $row->status
            );
        }

        $vars = array();
        foreach ($arrayRel as $rel) {
            $vars[] = array(
                'id' => $rel->id,
                'dados' => $rel->dimensao
            );
        }

        echo json_encode($vars);
    }

}
