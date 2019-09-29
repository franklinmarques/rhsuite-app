<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Backups extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    }

    //==========================================================================
    public function index()
    {
        $this->gerenciar();
    }

    //==========================================================================
    public function gerenciar($pdf = false)
    {
        $data = $this->input->get();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $this->db->select('contrato AS nome');
        $this->db->where("DATE_FORMAT(data, '%Y-%m') <=", $data['ano'] . '-' . $data['mes']);
        if (isset($data['depto'])) {
            $this->db->where('depto', $data['depto']);
        } else {
            $data['depto'] = null;
        }
        if (isset($data['area'])) {
            $this->db->where('area', $data['area']);
        } else {
            $data['area'] = null;
        }
        if (isset($data['setor'])) {
            $this->db->where('setor', $data['setor']);
        } else {
            $data['setor'] = null;
        }
        $this->db->order_by('data', 'desc');
        $this->db->limit(1);
        $contrato = $this->db->get('alocacao')->row();


        $this->db->select('a.id, a.nome, a.depto, a.area, a.contrato, c.setor');
        if (!empty($contrato->nome)) {
            $this->db->select("'{$contrato->nome}' AS contrato", false);
        } else {
            $this->db->select('a.contrato');
        }
        $this->db->select('b.nome AS nome_usuario, b.depto AS depto_usuario, b.telefone, b.email');
        $this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
        $this->db->join('st_unidades c', 'c.id_contrato = a.id');
        $this->db->join('st_reajustes d', 'd.id_cliente = a.id');
        if ($data['depto']) {
            $this->db->where('a.depto', $data['depto']);
        }
        $data['postos'] = false;
        if ($data['area']) {
            $this->db->where('a.area', $data['area']);
            if (strpos($data['area'], 'Ipesp') !== false) {
                $data['postos'] = true;
            }
        }
        if ($data['setor']) {
            $this->db->where('c.setor', $data['setor']);
        }
        $data['contrato'] = $this->db->get('st_contratos a')->row();


        $this->db->where("DATE_FORMAT(data, '%Y-%m') =", $data['ano'] . '-' . $data['mes']);
        if ($data['depto']) {
            $this->db->where('depto', $data['depto']);
        }
        if ($data['area']) {
            $this->db->where('area', $data['area']);
        }
        if ($data['setor']) {
            $this->db->where('setor', $data['setor']);
        }
        $alocacao = $this->db->get('alocacao')->row();

        $data['st_observacoes'] = $alocacao->observacoes ?? '';


        $this->load->library('Calendar');
        $data['mes_nome'] = $this->calendar->get_month_name($data['mes']);
        $data['calculo_totalizacao'] = $data['calculo_totalizacao'] ?? '1';

        /*$ajax_list = $this->ajax_list();

        $data['apontamentos'] = $ajax_list['apontamentos'];
        $data['totalizacoes'] = $this->ajax_totalizacao();
        $data['observacoes'] = $this->ajax_observacoes();
        $data['servicos'] = $this->ajax_servicos($data['contrato']->id ?? null);
        $data['reajuste'] = $this->ajax_reajuste($data['contrato']->id ?? null);*/
        $data['dias'] = 1;
        $data['is_pdf'] = $pdf;

        $get = $this->input->get();
        if (!empty($get['calculo_totalizacao'])) {
            unset($get['calculo_totalizacao']);
        }
        $data['query_string'] = http_build_query($get);

        if ($pdf) {


            $this->db->select('a.status, d.setor, c.nome, e.nome AS nome_bck', false);
            $this->db->select("DATE_FORMAT(a.data, '%d') AS dia", false);
            $this->db->select('IFNULL(a.qtde_dias, 1) AS qtde_dias', false);
            $this->db->select("TIME_FORMAT(a.hora_atraso, '%h:%i') AS hora_atraso", false);
            $this->db->select("SUBTIME(IFNULL(a.apontamento_extra, 0), IFNULL(a.apontamento_desc, 0)) AS apontamento", false);
            $this->db->join('st_alocados b', 'a.id_alocado = b.id');
            $this->db->join('usuarios c', 'b.id_usuario = c.id');
            $this->db->join('alocacao d', 'b.id_alocacao = d.id');
            $this->db->join('usuarios e', 'a.id_alocado_bck = e.id', 'left');
            $this->db->where('d.id_empresa', $this->session->userdata('empresa'));
            $this->db->where("DATE_FORMAT(a.data, '%Y-%m') =", $data['ano'] . '-' . $data['mes']);
            switch ($get['status']) {
                case 'FJ':
                    $this->db->where('a.status', 'FJ');
                    break;
                case 'FN':
                    $this->db->where('a.status', 'FN');
                    break;
                case 'A':
                    $this->db->where_in('a.status', array('AJ', 'AN'));
                    break;
                case 'S':
                    $this->db->where_in('a.status', array('SJ', 'SN'));
                    break;
                default:
                    $this->db->where_in('a.status', array('AJ', 'AN', 'FJ', 'FN', 'SJ', 'SN'));
            }
            if (!empty($get['depto'])) {
                $this->db->where('d.depto', $get['depto']);
            }
            if (!empty($get['area'])) {
                $this->db->where('d.area', $get['area']);
            }
            if (!empty($get['setor'])) {
                $this->db->where('d.setor', $get['setor']);
            }
            if (!empty($get['tipo_bck'])) {
                if ($get['tipo_bck'] === '1') {
                    $this->db->where("CHAR_LENGTH(e.nome) >", 0);
                } elseif ($get['tipo_bck'] === '0') {
                    $this->db->where('e.nome', null);
                }
            }
            if (!empty($get['busca'])) {
                $this->db->where("d.setor LIKE '%{$get['busca']}%' OR c.nome LIKE '%{$get['busca']}%' OR e.nome LIKE '%{$get['busca']}%'", null, false);
            }
            $this->db->order_by('a.data', 'asc');
            $this->db->order_by('d.setor', 'asc');
            $this->db->order_by('c.nome', 'asc');
            $this->db->order_by('e.nome', 'asc');
            $data['rows'] = $this->db->get('st_apontamento a')->result();

            return $this->load->view('st/backupsPdf', $data, true);
        } else {
            $this->load->view('st/backups', $data);
        }
    }

    //==========================================================================
    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);
        $depto = $busca['depto'] ?? '';
        $area = $busca['area'] ?? '';
        $setor = $busca['setor'] ?? '';

        $tipo_bck = $post['tipo_bck'];
        if ($post['tipo_bck'] === '1') {
            $tipo_bck = ' AND e.nome IS NOT NULL ';
        } elseif ($post['tipo_bck'] === '0') {
            $tipo_bck = ' AND e.nome IS NULL ';
        }

        if ($post['status'] === 'A') {
            $status = "'AJ', 'AN'";
        } elseif ($post['status'] === 'S') {
            $status = "'SJ', 'SN'";
        } elseif (!empty($post['status'])) {
            $status = "'{$post['status']}'";
        } else {
            $status = "'AJ', 'AN', 'FJ', 'FN', 'SJ', 'SN'";
        }

        $sql = "SELECT s.dia, 
                       s.setor,
                       s.status,
                       s.glosa,
                       s.nome,
                       s.nome_bck
                FROM (SELECT a.status, 
                             d.setor,
                             c.nome,
                             e.nome AS nome_bck,
                             DATE_FORMAT(a.data, '%d') AS dia,
                             CASE WHEN a.status IN ('FJ', 'FN', 'PD', 'PI', 'FR') THEN IFNULL(a.qtde_dias, 1)
                                  WHEN a.status IN ('AJ', 'AN', 'SJ', 'SN') THEN TIME_FORMAT(a.hora_atraso, '%h:%i')
                                  WHEN a.status = 'AE' THEN SUBTIME(IFNULL(a.apontamento_extra, 0), IFNULL(a.apontamento_desc, 0))
                                  END AS glosa
                      FROM st_apontamento a
                      INNER JOIN st_alocados b
                                 ON a.id_alocado = b.id
                      INNER JOIN usuarios c
                                 ON b.id_usuario = c.id
                      INNER JOIN alocacao d
                                 ON b.id_alocacao = d.id
                      LEFT JOIN usuarios e
                                ON a.id_alocado_bck = e.id
                      WHERE d.id_empresa = {$this->session->userdata('empresa')}
                            AND DATE_FORMAT(a.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'
                            AND a.status IN ({$status})
                            AND (d.depto = '{$depto}' OR CHAR_LENGTH('{$depto}') = 0)
                            AND (d.area = '{$area}' OR CHAR_LENGTH('{$area}') = 0)
                            AND (d.setor = '{$setor}' OR CHAR_LENGTH('{$setor}') = 0)
                            {$tipo_bck}
                      ORDER BY a.data ASC, 
                               d.setor ASC, 
                               c.nome ASC, 
                               e.nome ASC) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.dia', 's.setor', 's.status', 's.glosa', 's.nome', 's.nome_bck');
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
        foreach ($list as $apontamento) {
            $data[] = array(
                $apontamento->dia,
                $apontamento->setor,
                $apontamento->status,
                $apontamento->glosa,
                $apontamento->nome,
                $apontamento->nome_bck
            );
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        echo json_encode($output);
    }

    //==========================================================================
    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#backup thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#table { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 4px; background-color: #DFF0D8; border: 1px solid #444; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#table tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#totalizacao { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#servicos { border: 1px solid #444; } ';
        $stylesheet .= '#totalizacao thead th, #servicos thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#totalizacao tbody td, #servicos tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#reajuste { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#reajuste thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#reajuste tbody td { font-size: 12px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
        $stylesheet .= '#reajuste tbody tr:nth-child(8) td { font-size: 13px; padding: 5px; font-weight: bold; background-color: #f5f5f5; } ';

        $stylesheet .= '#observacoes { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#observacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#observacoes thead td, #observacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->gerenciar(true));

        $data = $this->input->get();

        $this->db->select('contrato AS nome');
        $this->db->where("DATE_FORMAT(data, '%Y-%m') <=", $data['ano'] . '-' . $data['mes']);
        if (!empty($data['depto'])) {
            $this->db->where('depto', $data['depto']);
        }
        if (!empty($data['area'])) {
            $this->db->where('area', $data['area']);
        }
        if (!empty($data['setor'])) {
            $this->db->where('setor', $data['setor']);
        }
        $this->db->order_by('data', 'desc');
        $this->db->limit(1);
        $contrato = $this->db->get('alocacao')->row();

        $this->db->select('a.area');
        $this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
        $this->db->join('st_unidades c', 'c.id_contrato = a.id');
        $this->db->join('st_reajustes d', 'd.id_cliente = a.id');
        if (!empty($contrato->nome)) {
            $this->db->where('a.contrato', $contrato->nome);
        } else {
            if (!empty($data['depto'])) {
                $this->db->where('a.depto', $data['depto']);
            }
            if (!empty($data['area'])) {
                $this->db->where('a.area', $data['area']);
            }
            if (!empty($data['setor'])) {
                $this->db->where('c.setor', $data['setor']);
            }
        }
        $row = $this->db->get('st_contratos a')->row();
        $nome = 'Alocação de backups - ';
        if (isset($row->area)) {
            $nome .= $row->area;
        }
        $nome .= date('_m-Y', mktime(0, 0, 0, $data['mes'], 1, $data['ano']));

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

}
