<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function index()
    {
        $this->funcionarios();
    }

    public function funcionarios($pdf = false)
    {
        $data = $this->input->get();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $supervisor = $this->input->get('supervisor');
        $diretoria = $this->input->get('diretoria');
        $departamento = $this->input->get('departamento');

        $this->db->select('a.nome AS supervisor, d.nome AS diretoria, d.depto');
        $this->db->join('ei_supervisores b', 'b.id_supervisor = a.id', 'left');
        $this->db->join('ei_escolas c', 'c.id = b.id_escola', 'left');
        $this->db->join('ei_diretorias d', 'd.id = c.id_diretoria', 'left');
        if ($supervisor) {
            $this->db->where('a.id', $supervisor);
        }
        if ($diretoria) {
            $this->db->where('d.id', $diretoria);
        }
        if ($departamento) {
            $this->db->where('d.depto', $departamento);
        }
        $row = $this->db->get('usuarios a')->row();
        $data['departamento'] = $departamento ? $row->depto : '';
        $data['diretoria'] = $diretoria ? $row->diretoria : '';
        $data['supervisor'] = $supervisor ? $row->supervisor : '';


        $this->db->select('a.id, a.nome, a.depto, a.area, a.contrato, c.setor');
        $this->db->select('b.nome AS nome_usuario, b.depto AS depto_usuario, b.telefone, b.email');
        $this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
        $this->db->join('alocacao_unidades c', 'c.id_contrato = a.id');
        $this->db->join('alocacao_reajuste d', 'd.id_cliente = a.id');
//        $this->db->where("DATE_FORMAT(a.data, '%m/%Y') =", "{$data['mes']}/{$data['ano']}");
        if (!empty($data['depto'])) {
            $this->db->where('a.depto', $data['depto']);
        }
        $data['postos'] = false;
        if (!empty($data['area'])) {
            $this->db->where('a.area', $data['area']);
            if (strpos($data['area'], 'Ipesp') !== false) {
                $data['postos'] = true;
            }
        }
        if (!empty($data['setor'])) {
            $this->db->where('c.setor', $data['setor']);
        }
        $data['contrato'] = $this->db->get('alocacao_contratos a')->row();

        $data['dias'] = date('t', mktime(0, 0, 0, $data['mes'], 1, $data['ano']));
        $this->load->library('Calendar');
        $data['mes_nome'] = $this->calendar->get_month_name($data['mes']);
        $data['calculo_totalizacao'] = $data['calculo_totalizacao'] ?? '1';
        $data['apontamentos'] = $this->ajax_list();

        $sql = "SELECT h.numero, h.nome 
                FROM (SELECT @rownum:= @rownum + 1 AS numero, s.data, s.id_cuidador_sub, s.nome 
                      FROM (SELECT a.id_cuidador_sub, b.id, a.data, d.nome 
                            FROM ei_apontamento a 
                            INNER JOIN ei_alocados b ON b.id = a.id_alocado 
                            INNER JOIN ei_alocacao c ON c.id = b.id_alocacao 
                            LEFT JOIN usuarios d ON d.id = a.id_cuidador_sub 
                            WHERE a.id_cuidador_sub IS NOT NULL 
                            GROUP BY a.id_cuidador_sub 
                            ORDER BY d.nome) s, 
                           (SELECT @rownum:= 0) x) h
                WHERE DATE_FORMAT(h.data, '%Y-%m') = '{$data['ano']}-{$data['mes']}'";
        $legendas = $this->db->query($sql)->result();

        $data['legendas'] = array();
        foreach ($legendas as $legenda) {
            $data['legendas'][$legenda->numero] = $legenda->nome;
        }
        $data['funcionarios'] = $this->ajax_funcionarios();
        $data['observacoes'] = $this->ajax_observacoes();
        $data['is_pdf'] = $pdf;
        $data['query_string'] = 'q?' . http_build_query($this->input->get());

        if ($pdf) {
            return $this->load->view('ei/relatorio', $data, true);
        } else {
            $this->load->view('ei/relatorio', $data);
        }
    }

    public function escolas($pdf = false)
    {
        if ($pdf !== true) {
            $pdf = false;
        }
        $data = $this->input->get();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $supervisor = $this->input->get('supervisor');
        $diretoria = $this->input->get('diretoria');
        $departamento = $this->input->get('departamento');

        $this->db->select('a.nome AS supervisor, d.nome AS diretoria, d.depto');
        $this->db->join('ei_supervisores b', 'b.id_supervisor = a.id', 'left');
        $this->db->join('ei_escolas c', 'c.id = b.id_escola', 'left');
        $this->db->join('ei_diretorias d', 'd.id = c.id_diretoria', 'left');
        if ($supervisor) {
            $this->db->where('a.id', $supervisor);
        }
        if ($diretoria) {
            $this->db->where('d.id', $diretoria);
        }
        if ($departamento) {
            $this->db->where('d.depto', $departamento);
        }
        $row = $this->db->get('usuarios a')->row();
        $data['departamento'] = $departamento ? $row->depto : '';
        $data['diretoria'] = $diretoria ? $row->diretoria : '';
        $data['supervisor'] = $supervisor ? $row->supervisor : '';


        $this->db->select('a.id, a.nome, a.depto, a.area, a.contrato, c.setor');
        $this->db->select('b.nome AS nome_usuario, b.depto AS depto_usuario, b.telefone, b.email');
        $this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
        $this->db->join('alocacao_unidades c', 'c.id_contrato = a.id');
        $this->db->join('alocacao_reajuste d', 'd.id_cliente = a.id');
//        $this->db->where("DATE_FORMAT(a.data, '%m/%Y') =", "{$data['mes']}/{$data['ano']}");
        if (!empty($data['depto'])) {
            $this->db->where('a.depto', $data['depto']);
        }
        $data['postos'] = false;
        if (!empty($data['area'])) {
            $this->db->where('a.area', $data['area']);
            if (strpos($data['area'], 'Ipesp') !== false) {
                $data['postos'] = true;
            }
        }
        if (!empty($data['setor'])) {
            $this->db->where('c.setor', $data['setor']);
        }
        $data['contrato'] = $this->db->get('alocacao_contratos a')->row();

        $data['dias'] = date('t', mktime(0, 0, 0, $data['mes'], 1, $data['ano']));
        $this->load->library('Calendar');
        $data['mes_nome'] = $this->calendar->get_month_name($data['mes']);
        $data['calculo_totalizacao'] = $data['calculo_totalizacao'] ?? '1';
        $data['apontamentos'] = $this->ajax_list();

        $sql = "SELECT h.numero, h.nome 
                FROM (SELECT @rownum:= @rownum + 1 AS numero, s.data, s.id_cuidador_sub, s.nome 
                      FROM (SELECT a.id_cuidador_sub, b.id, a.data, d.nome 
                            FROM ei_apontamento a 
                            INNER JOIN ei_alocados b ON b.id = a.id_alocado 
                            INNER JOIN ei_alocacao c ON c.id = b.id_alocacao 
                            LEFT JOIN usuarios d ON d.id = a.id_cuidador_sub 
                            WHERE a.id_cuidador_sub IS NOT NULL 
                            GROUP BY a.id_cuidador_sub 
                            ORDER BY d.nome) s, 
                           (SELECT @rownum:= 0) x) h
                WHERE DATE_FORMAT(h.data, '%Y-%m') = '{$data['ano']}-{$data['mes']}'";
        $legendas = $this->db->query($sql)->result();

        $data['legendas'] = array();
        foreach ($legendas as $legenda) {
            $data['legendas'][$legenda->numero] = $legenda->nome;
        }
        $data['funcionarios'] = $this->ajax_funcionarios();
        $data['observacoes'] = $this->ajax_observacoes();
        $data['is_pdf'] = $pdf;
        $data['query_string'] = 'q?' . http_build_query($this->input->get());

        if ($pdf) {
            return $this->load->view('ei/relatorio_escolas', $data, true);
        } else {
            $this->load->view('ei/relatorio_escolas', $data);
        }
    }

    public function insumos($pdf = false)
    {
        if ($pdf !== true) {
            $pdf = false;
        }
        $data = $this->input->get();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $supervisor = $this->input->get('supervisor');
        $diretoria = $this->input->get('diretoria');
        $departamento = $this->input->get('departamento');

        $this->db->select('a.nome AS supervisor, d.nome AS diretoria, d.depto');
        $this->db->join('ei_supervisores b', 'b.id_supervisor = a.id', 'left');
        $this->db->join('ei_escolas c', 'c.id = b.id_escola', 'left');
        $this->db->join('ei_diretorias d', 'd.id = c.id_diretoria', 'left');
        if ($supervisor) {
            $this->db->where('a.id', $supervisor);
        }
        if ($diretoria) {
            $this->db->where('d.id', $diretoria);
        }
        if ($departamento) {
            $this->db->where('d.depto', $departamento);
        }
        $row = $this->db->get('usuarios a')->row();
        $data['departamento'] = $departamento ? $row->depto : '';
        $data['diretoria'] = $diretoria ? $row->diretoria : '';
        $data['supervisor'] = $supervisor ? $row->supervisor : '';


        $this->db->select('a.id, a.nome, a.depto, a.area, a.contrato, c.setor');
        $this->db->select('b.nome AS nome_usuario, b.depto AS depto_usuario, b.telefone, b.email');
        $this->db->join('usuarios b', 'b.id = a.id_usuario', 'left');
        $this->db->join('alocacao_unidades c', 'c.id_contrato = a.id');
        $this->db->join('alocacao_reajuste d', 'd.id_cliente = a.id');
//        $this->db->where("DATE_FORMAT(a.data, '%m/%Y') =", "{$data['mes']}/{$data['ano']}");
        if (!empty($data['depto'])) {
            $this->db->where('a.depto', $data['depto']);
        }
        $data['postos'] = false;
        if (!empty($data['area'])) {
            $this->db->where('a.area', $data['area']);
            if (strpos($data['area'], 'Ipesp') !== false) {
                $data['postos'] = true;
            }
        }
        if (!empty($data['setor'])) {
            $this->db->where('c.setor', $data['setor']);
        }
        $data['contrato'] = $this->db->get('alocacao_contratos a')->row();

        $data['dias'] = date('t', mktime(0, 0, 0, $data['mes'], 1, $data['ano']));
        $this->load->library('Calendar');
        $data['mes_nome'] = $this->calendar->get_month_name($data['mes']);
        $data['calculo_totalizacao'] = $data['calculo_totalizacao'] ?? '1';
        $data['apontamentos'] = $this->ajax_list();

        $sql = "SELECT h.numero, h.nome 
                FROM (SELECT @rownum:= @rownum + 1 AS numero, s.data, s.id_cuidador_sub, s.nome 
                      FROM (SELECT a.id_cuidador_sub, b.id, a.data, d.nome 
                            FROM ei_apontamento a 
                            INNER JOIN ei_alocados b ON b.id = a.id_alocado 
                            INNER JOIN ei_alocacao c ON c.id = b.id_alocacao 
                            LEFT JOIN usuarios d ON d.id = a.id_cuidador_sub 
                            WHERE a.id_cuidador_sub IS NOT NULL 
                            GROUP BY a.id_cuidador_sub 
                            ORDER BY d.nome) s, 
                           (SELECT @rownum:= 0) x) h
                WHERE DATE_FORMAT(h.data, '%Y-%m') = '{$data['ano']}-{$data['mes']}'";
        $legendas = $this->db->query($sql)->result();

        $data['legendas'] = array();
        foreach ($legendas as $legenda) {
            $data['legendas'][$legenda->numero] = $legenda->nome;
        }
        $data['funcionarios'] = $this->ajax_funcionarios();
        $insumos = $this->ajax_insumos();
        $data['titulos'] = $insumos['titulos'];
        $data['insumos'] = $insumos['registros'];
        $data['is_pdf'] = $pdf;
        $data['query_string'] = 'q?' . http_build_query($this->input->get());

        if ($pdf) {
            return $this->load->view('ei/relatorio_insumos', $data, true);
        } else {
            $this->load->view('ei/relatorio_insumos', $data);
        }
    }

    private function ajax_list()
    {
        $busca = $this->input->get();

        $sql = "SELECT s.escola, 
                       s.municipio,
                       s.turno,
                       s.nome,
                       s.remanejado,
                       s.numero,
                       s.dia_01,
                       s.dia_02,
                       s.dia_03,
                       s.dia_04,
                       s.dia_05,
                       s.dia_06,
                       s.dia_07,
                       s.dia_08,
                       s.dia_09,
                       s.dia_10,
                       s.dia_11,
                       s.dia_12,
                       s.dia_13,
                       s.dia_14,
                       s.dia_15,
                       s.dia_16,
                       s.dia_17,
                       s.dia_18,
                       s.dia_19,
                       s.dia_20,
                       s.dia_21,
                       s.dia_22,
                       s.dia_23,
                       s.dia_24,
                       s.dia_25,
                       s.dia_26,
                       s.dia_27,
                       s.dia_28,
                       s.dia_29,
                       s.dia_30,
                       s.dia_31,
                       s.sub_01,
                       s.sub_02,
                       s.sub_03,
                       s.sub_04,
                       s.sub_05,
                       s.sub_06,
                       s.sub_07,
                       s.sub_08,
                       s.sub_09,
                       s.sub_10,
                       s.sub_11,
                       s.sub_12,
                       s.sub_13,
                       s.sub_14,
                       s.sub_15,
                       s.sub_16,
                       s.sub_17,
                       s.sub_18,
                       s.sub_19,
                       s.sub_20,
                       s.sub_21,
                       s.sub_22,
                       s.sub_23,
                       s.sub_24,
                       s.sub_25,
                       s.sub_26,
                       s.sub_27,
                       s.sub_28,
                       s.sub_29,
                       s.sub_30,
                       s.sub_31
                FROM (SELECT e.escola, 
                             c.municipio, 
                             e.turno, 
                             e.cuidador AS nome, 
                             e.remanejado,
                             d.rownum AS numero,                            
                             ";
        for ($i = 1; $i <= 31; $i++) {
            $dia = str_pad($i, 2, '0', STR_PAD_LEFT);
            if (strtotime("{$busca['ano']}-{$busca['mes']}-$dia") <= strtotime(date('Y-m-d'))) {
                $sql .= "(SELECT h.status
                          FROM ei_apontamento h
                          LEFT JOIN usuarios k ON
                                    k.id = h.id_cuidador_sub
                          WHERE h.id_alocado = e.id AND 
                                DATE_FORMAT(h.data, '%Y-%m') = DATE_FORMAT(f.data, '%Y-%m') AND 
                                DATE_FORMAT(h.data, '%d') = '{$dia}') AS dia_{$dia},
                         (CASE WHEN d.id = g.id_cuidador_sub THEN d.rownum END) AS sub_{$dia},
                         (SELECT h.rownum 
                          FROM (SELECT @rownum_{$dia}:= @rownum_{$dia} + 1 AS rownum, s.data, s.id_cuidador_sub, s.id
                               FROM (SELECT a.id_cuidador_sub, b.id, a.data 
                                     FROM ei_apontamento a 
                                     INNER JOIN ei_alocados b ON b.id = a.id_alocado 
                                     INNER JOIN ei_alocacao c ON c.id = b.id_alocacao 
                                     LEFT JOIN usuarios d ON d.id = a.id_cuidador_sub 
                                     WHERE a.id_cuidador_sub IS NOT NULL 
                                     GROUP BY a.id_cuidador_sub 
                                     ORDER BY d.nome) s, 
                                    (SELECT @rownum_{$dia}:= 0) x) h
                          WHERE h.id = e.id AND
                                DATE_FORMAT(h.data, '%Y-%m') = DATE_FORMAT(f.data, '%Y-%m') AND 
                                DATE_FORMAT(h.data, '%d') = '{$dia}') AS ub_{$dia},";
            } else {
                $sql .= "'' AS dia_{$dia}, '' AS sub_{$dia}, ";
            }
        }
        $sql .= "d.nome AS nome_cuidador
                 FROM ei_alocados e
                 INNER JOIN ei_alocacao f ON
                            f.id = e.id_alocacao
                 INNER JOIN ei_diretorias c ON
                            c.nome = f.diretoria
                 INNER JOIN ei_escolas b
                            ON b.id_diretoria = c.id
                 INNER JOIN ei_supervisores h ON
                            h.id_escola = b.id
                 LEFT JOIN ei_cuidadores a 
                           ON a.id_escola = b.id
                 LEFT JOIN (SELECT @rownum:= @rownum + 1 AS rownum, a.* 
                            FROM (SELECT b.*
                                  FROM ei_cuidadores a
                                  INNER JOIN usuarios b ON b.id = a.id_cuidador
                                  GROUP BY a.id_cuidador
                                  ORDER BY b.nome) a, (SELECT @rownum:= 0) s) d ON 
                           d.id = a.id_cuidador
                 LEFT JOIN ei_apontamento g ON 
                           g.id_alocado = e.id AND
                           DATE_FORMAT(g.data, '%Y-%m') = DATE_FORMAT(f.data, '%Y-%m')
                 WHERE c.id_empresa = {$this->session->userdata('empresa')} AND 
                            DATE_FORMAT(f.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
        if (!empty($busca['depto'])) {
            $sql .= " AND c.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['diretoria'])) {
            $sql .= " AND c.id = '{$busca['diretoria']}'";
        }
        if (!empty($busca['supervisor'])) {
            $sql .= " AND h.id_supervisor = '{$busca['supervisor']}'";
        }
        $sql .= " GROUP BY e.escola, e.turno
                 ORDER BY f.municipio ASC, 
                          e.escola ASC,
                          f.municipio ASC,
                          FIELD(e.turno, 'M', 'T', 'N'),
                          e.cuidador ASC) s";
        $data = $this->db->query($sql)->result();

        return $data;
    }

    private function ajax_funcionarios()
    {
        $busca = $this->input->get();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.remanejado,
                       s.municipio,
                       s.turno,
                       s.num_turno,
                       s.dia_01,
                       s.dia_02,
                       s.dia_03,
                       s.dia_04,
                       s.dia_05,
                       s.dia_06,
                       s.dia_07,
                       s.dia_08,
                       s.dia_09,
                       s.dia_10,
                       s.dia_11,
                       s.dia_12,
                       s.dia_13,
                       s.dia_14,
                       s.dia_15,
                       s.dia_16,
                       s.dia_17,
                       s.dia_18,
                       s.dia_19,
                       s.dia_20,
                       s.dia_21,
                       s.dia_22,
                       s.dia_23,
                       s.dia_24,
                       s.dia_25,
                       s.dia_26,
                       s.dia_27,
                       s.dia_28,
                       s.dia_29,
                       s.dia_30,
                       s.dia_31
                FROM (SELECT a.id, 
                             a.escola,
                             a.cuidador AS nome,
                             a.remanejado,
                             b.municipio,
                             a.turno,                         
                             ";
        for ($i = 1; $i <= 31; $i++) {
            $dia = str_pad($i, 2, '0', STR_PAD_LEFT);
            if (strtotime("{$busca['ano']}-{$busca['mes']}-$dia") <= strtotime(date('Y-m-d'))) {
                $sql .= "(SELECT CASE WHEN (h.status = 'FE' OR h.data <= CURDATE()) AND a.id IS NOT NULL 
                                      THEN CONCAT('[', GROUP_CONCAT(
                                                CONCAT('\"', h.id, '\",'), 
                                                CONCAT('\"', IFNULL(h.id_cuidador_sub, ''), '\",'), 
                                                CONCAT('\"', IFNULL(h.qtde_dias, ''), '\",'), 
                                                CONCAT('\"', IFNULL(DATE_FORMAT(h.apontamento_asc, '%H:%i'), ''), '\",'), 
                                                CONCAT('\"', IFNULL(DATE_FORMAT(h.apontamento_desc, '%H:%i'), ''), '\",'), 
                                                CONCAT('\"', IFNULL(h.saldo, ''), '\",'), 
                                                CONCAT('\"', IFNULL(h.observacoes, ''), '\",'), 
                                                CONCAT('\"', IFNULL(h.status, ''), '\",'), 
                                                CONCAT('\"', IFNULL(k.nome, ''), '\"')
                                           ),']')
                                      WHEN a.id IS NOT NULL THEN '[\"\"]' 
                                      ELSE '' END
                          FROM ei_apontamento h
                          INNER JOIN ei_alocados i ON
                                     i.id = h.id_alocado
                          LEFT JOIN ei_cuidadores j ON
                                    j.id = i.id_vinculado
                          LEFT JOIN usuarios k ON
                                    k.id = h.id_cuidador_sub
                          WHERE h.id_alocado = a.id AND
                                DATE_FORMAT(h.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m') AND
                                DATE_FORMAT(h.data, '%d') = '{$dia}') AS dia_{$dia}, ";
            } else {
                $sql .= "'' AS dia_{$dia}, ";
            }
        }
        $sql .= "CASE a.turno WHEN 'M' THEN 1
                              WHEN 'T' THEN 2
                              WHEN 'N' THEN 3 
                              ELSE 0 END AS num_turno
                FROM ei_alocados a
                INNER JOIN ei_alocacao b ON
                           b.id = a.id_alocacao
                INNER JOIN ei_diretorias d ON
                           d.nome = b.diretoria
                INNER JOIN usuarios e ON
                           e.nome = b.supervisor
                LEFT JOIN usuarios f ON
                           f.nome = a.cuidador
                LEFT JOIN ei_apontamento c ON 
                          c.id_alocado = a.id AND
                          DATE_FORMAT(c.data, '%Y-%m') = DATE_FORMAT(b.data, '%Y-%m')
                WHERE b.id_empresa = {$this->session->userdata('empresa')} AND 
                            DATE_FORMAT(b.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
        if ($busca['depto']) {
            $sql .= " AND b.depto = '{$busca['depto']}'";
        }
//        if ($busca['diretoria']) {
        $sql .= " AND d.id = '{$busca['diretoria']}'";
//        }
        if ($busca['supervisor']) {
            $sql .= " AND e.id = '{$busca['supervisor']}'";
        }
        $sql .= ' GROUP BY a.escola, a.turno, a.id ORDER BY a.escola ASC, a.cuidador ASC) s';
        $data = $this->db->query($sql)->result();

        return $data;
    }

    private function ajax_insumos()
    {
        $busca = $this->input->get();

        $depto = $this->input->get('depto');
        $diretoria = $this->input->get('diretoria');
        $supervisor = $this->input->get('supervisor');
        $mes = $this->input->get('mes');
        $ano = $this->input->get('ano');


        $sqlColunas = "SELECT GROUP_CONCAT(CONCAT(' SUM(IF(h.id = ',  s.id, ', g.qtde, NULL)) AS \'', LCASE(REPLACE(REPLACE(s.nome, '-', ''), ' ', '_'))), '\''
                        ) AS insumo
               FROM (SELECT * FROM ei_insumos ORDER BY nome) s";

        $rowColunas = $this->db->query($sqlColunas)->row();
        $colunas = convert_accented_characters($rowColunas->insumo);

        $sql = "SELECT d.id AS id_escola, 
                       x.escola, 
                       (SELECT COUNT(DISTINCT (IFNULL(s.id_aluno, '')))
                        FROM ei_alocados t 
                        LEFT JOIN ei_matriculados s 
                                  ON s.escola = t.escola 
                                  AND s.turno = t.turno
                                  AND s.status IN ('A','N')
                        WHERE t.escola = d.nome
                              AND t.id_alocacao = b.id) AS total_alunos,
                       IFNULL(a.aluno, '&nbsp;') AS aluno,
                       {$colunas}, 
                       SUM(g.qtde) AS total
                FROM ei_alocados x
                INNER JOIN ei_alocacao b ON
                           b.id = x.id_alocacao
                INNER JOIN ei_diretorias c ON
                           c.nome = b.diretoria AND 
                           c.id_empresa = b.id_empresa
                INNER JOIN ei_escolas d ON
                           d.id_diretoria = c.id AND 
                           d.nome = x.escola
                LEFT JOIN ei_matriculados a 
                          ON a.id_alocacao = b.id 
                          AND a.escola = x.escola
                          AND a.turno = x.turno
                          AND a.status IN ('A','N')
                LEFT JOIN ei_frequencias f ON
                          f.id_matriculado = a.id
                LEFT JOIN ei_consumos g ON
                          g.id_frequencia = f.id
                LEFT JOIN ei_insumos h ON
                          h.id = g.id_insumo
                WHERE (b.depto = '{$depto}' OR CHAR_LENGTH('{$depto}') = 0)
                      AND (c.id = '{$diretoria}' OR CHAR_LENGTH('{$diretoria}') = 0)
                      AND (b.supervisor = (SELECT nome 
                                           FROM usuarios 
                                           WHERE id = '{$supervisor}') 
                          OR CHAR_LENGTH('{$supervisor}') = 0)
                      AND DATE_FORMAT(b.data, '%Y-%m') = '{$ano}-{$mes}'
                GROUP BY x.escola, x.turno, a.aluno
                ORDER BY x.escola ASC, FIELD(x.turno, 'M', 'T', 'N'), a.aluno ASC";

        $data['registros'] = $this->db->query($sql)->result_array();


        $sqlNomeColunas = "SELECT LCASE(REPLACE(REPLACE(a.nome, '-', ''), ' ', '_')) AS id, 
                                  IF(SUM(s.qtde) > 0, CONCAT(a.nome, ' (', SUM(s.qtde), ')'), a.nome) AS nome 
                           FROM ei_insumos a 
                           LEFT JOIN (SELECT b.id_insumo, 
                                             b.qtde 
                                      FROM ei_alocados x 
                                      INNER JOIN ei_alocacao e ON e.id = x.id_alocacao
                                                 AND DATE_FORMAT(e.data, '%Y-%m') = '{$ano}-{$mes}'
                                      INNER JOIN ei_diretorias f 
                                                 ON f.nome = e.diretoria 
                                                 AND f.id_empresa = e.id_empresa
                                                 AND (f.depto = '{$depto}' OR CHAR_LENGTH('{$depto}') = 0) 
                                                 AND (f.id = '{$diretoria}' OR CHAR_LENGTH('{$diretoria}') = 0)
                                      INNER JOIN ei_escolas g 
                                                 ON g.id_diretoria = f.id 
                                      INNER JOIN ei_supervisores h
                                                 ON h.id_escola = g.id
                                                 AND (h.id_supervisor = '{$supervisor}' OR CHAR_LENGTH('{$supervisor}') = 0)
                                      LEFT JOIN ei_matriculados d 
                                                ON d.id_alocacao = e.id
                                                AND d.escola = x.escola 
                                                AND d.turno = x.turno
                                      LEFT JOIN ei_frequencias c 
                                                ON c.id_matriculado = d.id
                                                AND DATE_FORMAT(c.data, '%Y-%m') = '{$ano}-{$mes}' 
                                      LEFT JOIN ei_consumos b ON b.id_frequencia = c.id) s 
                                     ON s.id_insumo = a.id
                           GROUP BY a.id ORDER BY a.nome";

        $rows = $this->db->query($sqlNomeColunas)->result();
        $data['titulos'] = array();
        foreach ($rows as $row) {
            $data['titulos'][convert_accented_characters($row->id)] = $row->nome;
        }


        return $data;
    }

    /* private function ajax_insumos()
      {
      $busca = $this->input->get();

      $sqlSemana = "SELECT DAY(CASE WEEKDAY(a.data)
      WHEN 5 THEN DATE_ADD(a.data, INTERVAL 2 DAY)
      WHEN 6 THEN DATE_ADD(a.data, INTERVAL 1 DAY)
      ELSE a.data END) AS data_ini,
      DAY(LAST_DAY(a.data)) AS data_fim
      FROM (SELECT STR_TO_DATE('{$busca['ano']}-{$busca['mes']}-01','%Y-%m-%d') as data) a";
      $dias = $this->db->query($sqlSemana)->row();

      $primeiraSemana = 8 - date('N', strtotime($busca['ano'] . '-' . $busca['mes'] . '-01'));
      $semana = array();
      for ($i = $dias->data_ini; $i <= $dias->data_fim; $i += $primeiraSemana) {
      $semana[] = array(
      'data_ini' => $i,
      'data_fim' => min($i + ($i > $dias->data_ini ? 4 : $primeiraSemana - 3), $dias->data_fim)
      );
      if ($i > $dias->data_ini) {
      $primeiraSemana = 7;
      }
      if ($i > $dias->data_fim) {
      break;
      }
      }

      $data = array('semanas' => $semana);

      $sql = "SELECT b.id AS id_escola,
      b.nome AS escola,
      a.id AS id_aluno,
      a.nome AS aluno,
      CASE (DAY(d.data) + (CASE WHEN WEEKDAY(DATE_SUB(d.data, INTERVAL (DAY(d.data) - 1) DAY)) < 5
      THEN WEEKDAY(DATE_SUB(d.data, INTERVAL (DAY(d.data) - 1) DAY))
      ELSE 0 END) + (6 - WEEKDAY(d.data))) / 7
      WHEN 1 THEN 'semana1'
      WHEN 2 THEN 'semana2'
      WHEN 3 THEN 'semana3'
      WHEN 4 THEN 'semana4'
      WHEN 5 THEN 'semana5'
      END as semana,
      d.status,
      g.nome AS insumo,
      SUM(f.qtde) AS qtde
      FROM ei_alunos a
      INNER JOIN ei_escolas b ON
      b.id = a.id_escola
      INNER JOIN ei_diretorias c ON
      c.id = b.id_diretoria
      LEFT JOIN ei_frequencias d ON
      d.id_aluno = a.id
      LEFT JOIN ei_alocacao e ON
      e.id = d.id_alocacao
      LEFT JOIN ei_consumos f ON
      f.id_frequencia = d.id
      LEFT JOIN ei_insumos g ON
      g.id = f.id_insumo
      WHERE DATE_FORMAT(e.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
      if (isset($busca['depto'])) {
      $sql .= " AND c.depto = '{$busca['depto']}'";
      }
      if (isset($busca['diretoria'])) {
      $sql .= " AND c.id = '{$busca['diretoria']}'";
      }
      if (isset($busca['supervisor'])) {
      $sql .= " AND b.id_supervisor = '{$busca['supervisor']}'";
      }
      $sql .= '
      GROUP BY b.id, a.id, semana, g.id
      ORDER BY b.nome, a.nome, d.data';

      $rows = $this->db->query($sql)->result();


      $arr_insumos = array();

      foreach ($rows as $row) {
      $data[$row->id_escola]['escola'] = $row->escola;
      $data[$row->id_escola]['qtde_alunos'] = 0;
      $data[$row->id_escola]['alunos'][$row->id_aluno] = array(
      'nome' => $row->aluno,
      'semana1' => array(),
      'semana2' => array(),
      'semana3' => array(),
      'semana4' => array(),
      'semana5' => array(),
      'total' => 0
      );
      $arr_insumos[] = $row->insumo;
      }

      $arr_insumos = array_unique($arr_insumos);

      $id_aluno = 0;
      foreach ($rows as $row2) {
      $key_insumos = array_search($row2->insumo, $arr_insumos);
      if ($row2->id_aluno != $id_aluno) {
      $data[$row2->id_escola]['qtde_alunos'] += 1;
      $id_aluno = $row2->id_aluno;
      }
      $data[$row2->id_escola]['alunos'][$row2->id_aluno][$row2->semana]['status'] = $row2->status;
      $data[$row2->id_escola]['alunos'][$row2->id_aluno][$row2->semana]['insumos'][$key_insumos]['nome'] = $row2->insumo;
      $data[$row2->id_escola]['alunos'][$row2->id_aluno][$row2->semana]['insumos'][$key_insumos]['qtde'] = $row2->qtde;
      $data[$row2->id_escola]['alunos'][$row2->id_aluno]['total'] += $row2->qtde;
      }

      return $data;
      } */

    private function ajax_observacoes()
    {
        $busca = $this->input->get();

        $sqlSemana = "SELECT DAY(CASE WEEKDAY(a.data) 
                                      WHEN 5 THEN DATE_ADD(a.data, INTERVAL 2 DAY)
                                      WHEN 6 THEN DATE_ADD(a.data, INTERVAL 1 DAY)
                                      ELSE a.data END) AS data_ini,
           DAY(LAST_DAY(a.data)) AS data_fim
                      FROM (SELECT STR_TO_DATE('{$busca['ano']}-{$busca['mes']}-01','%Y-%m-%d') as data) a";
        $dias = $this->db->query($sqlSemana)->row();

        $primeiraSemana = 8 - date('N', strtotime($busca['ano'] . '-' . $busca['mes'] . '-01'));
        $semana = array();
        for ($i = $dias->data_ini; $i <= $dias->data_fim; $i += $primeiraSemana) {
            $semana[] = array(
                'data_ini' => $i,
                'data_fim' => min($i + ($i > $dias->data_ini ? 4 : $primeiraSemana - 3), $dias->data_fim)
            );
            if ($i > $dias->data_ini) {
                $primeiraSemana = 7;
            }
            if ($i > $dias->data_fim) {
                break;
            }
        }

        $data = array('semanas' => $semana);

        $sql = "SELECT a.status, 
                       CASE a.status
                            WHEN 'FA' THEN 'Falta com atestado'
                            WHEN 'FS' THEN 'Falta sem atestado'
                            WHEN 'FE' THEN 'Feriado escola'
                            WHEN 'EM' THEN 'Emenda Feriado'
                            WHEN 'AA' THEN 'Aluno ausente'
                            WHEN 'AF' THEN 'Afastamento'
                            WHEN 'AP' THEN 'Apontamento'
                            WHEN 'AD' THEN 'Funcionário admitido'
                            WHEN 'AT' THEN 'Acidente de trabalho'
                            WHEN 'DE' THEN 'Funcionário demitido'
                            WHEN 'FC' THEN 'Feriado escola/cuidador'
                            WHEN 'IA' THEN 'Intercorrência de alunos'
                            WHEN 'IC' THEN 'Intercorrência de cuidadores'
                            WHEN 'ID' THEN 'Intercorrência de diretoria'
                            WHEN 'NA' THEN 'Funcionário não-alocado'
                            WHEN 'RE' THEN 'Funcionário remanejado'
                            WHEN 'SL' THEN 'Sábado letivo'
                            ELSE 'Outro' END AS nome_status, 
                       CASE (DAY(a.data) + (CASE WHEN WEEKDAY(DATE_SUB(a.data, INTERVAL (DAY(a.data) - 1) DAY)) < 5 
                                                 THEN WEEKDAY(DATE_SUB(a.data, INTERVAL (DAY(a.data) - 1) DAY)) 
                                                 ELSE 0 END) + (6 - WEEKDAY(a.data))) / 7 
                            WHEN 1 THEN 'semana1' 
                            WHEN 2 THEN 'semana2' 
                            WHEN 3 THEN 'semana3' 
                            WHEN 4 THEN 'semana4'
                            WHEN 5 THEN 'semana5' 
                            END as semana, 
                       e.id, 
                       e.nome, 
                       a.id_alocado, 
                       a.observacoes, 
                       DAY(a.data) AS dia
                FROM ei_apontamento a
                INNER JOIN ei_alocados b ON 
                           b.id = a.id_alocado
                INNER JOIN ei_alocacao c ON 
                           c.id = b.id_alocacao
                INNER JOIN ei_diretorias g ON 
                           g.depto = c.depto AND
                           g.nome = c.diretoria AND 
                           g.municipio = c.municipio
                INNER JOIN ei_escolas f ON 
                           f.id_diretoria = g.id
                INNER JOIN ei_supervisores h ON
                           h.id_escola = f.id
                LEFT JOIN ei_cuidadores d on 
                           d.id = b.id_vinculado
                LEFT JOIN usuarios e ON 
                           e.id = d.id_cuidador                
                WHERE DATE_FORMAT(c.data, '%Y-%m') = '{$busca['ano']}-{$busca['mes']}'";
        if (isset($busca['depto'])) {
            $sql .= " AND g.depto = '{$busca['depto']}'";
        }
        if (isset($busca['diretoria'])) {
            $sql .= " AND g.id = '{$busca['diretoria']}'";
        }
        if (isset($busca['supervisor'])) {
            $sql .= " AND h.id_supervisor = '{$busca['supervisor']}'";
        }
        $sql .= 'GROUP BY b.escola, b.turno ORDER BY a.status, a.data';

        $rows = $this->db->query($sql)->result();


        $arr_observacoes = array();

        foreach ($rows as $row) {
            $data[$row->status] = array(
                'status' => $row->nome_status,
                'semana1' => array(),
                'semana2' => array(),
                'semana3' => array(),
                'semana4' => array(),
                'semana5' => array()
            );
            $arr_observacoes[] = $row->observacoes;
        }

        $arr_observacoes = array_unique($arr_observacoes);

        foreach ($rows as $row2) {
            $data[$row2->status][$row2->semana][$row2->id]['nome'] = $row2->nome;

            $key_obs = array_search($row2->observacoes, $arr_observacoes);

            $data[$row2->status][$row2->semana][$row2->id]['observacoes'][$key_obs]['nome'] = $row2->observacoes;
            $data[$row2->status][$row2->semana][$row2->id]['observacoes'][$key_obs]['dias'][] = $row2->dia;
        }


        return $data;
    }


    public function medicao($isPdf = false)
    {
        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();
        $data['is_pdf'] = $isPdf === true;

        $mes = $this->input->get('mes');
        if (strlen($mes) == 0) {
            $mes = date('m');
        }
        $ano = $this->input->get('ano');
        if (strlen($ano) == 0) {
            $ano = date('Y');
        }

        if (checkdate($mes, 1, $ano) == false or strlen($mes) !== 2 or strlen($ano) !== 4) {
            redirect(site_url('ei/relatorios/medicao/'));
        }

        $this->load->library('Calendar');
        $data['mes_nome'] = $this->calendar->get_month_name($mes);
        $data['mes'] = $mes;
        $data['ano'] = $ano;
        $data['semestre'] = $this->input->get('semestre');
        $idMes = intval($mes) - ($data['semestre'] > 1 ? 6 : 0);
        $data['query_string'] = http_build_query($this->input->get());


        $this->db->select(["GROUP_CONCAT(DISTINCT a.id ORDER BY a.id ASC SEPARATOR ', ') AS id"], false);
        $this->db->select('COUNT(DISTINCT(escola)) AS total_escolas', false);
        $this->db->select('COUNT(DISTINCT(aluno)) AS total_alunos', false);
        $this->db->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id');
        $this->db->join('ei_matriculados c', 'c.id_alocacao_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.ano', $this->input->get('ano'));
        $this->db->where('a.semestre', $this->input->get('semestre'));
        $data['alocacao'] = $this->db->get('ei_alocacao a')->row();


        $idAlocacoes = isset($data['alocacao']->id) ? explode(',', $data['alocacao']->id) : [0];


        $sql = "SELECT t.id, 
                       s.id_alocacao,
                       s.cargo, 
                       s.funcao AS nome, 
                       COUNT(DISTINCT(s.cuidador)) AS total_pessoas,
                       SUM(s.total_mes{$idMes}) AS total_secs_projetados,
                       t.total_horas_mes{$idMes} AS total_horas_mes,
                       IFNULL(SUM(TIME_TO_SEC(s.total_horas_mes{$idMes}) + TIME_TO_SEC(s.horas_descontadas_mes{$idMes})), SUM(s.total_mes{$idMes})) AS total_secs_realizados2,
                       SUM(IFNULL(TIME_TO_SEC(s.total_horas_mes{$idMes}), s.total_mes{$idMes})) AS total_secs_realizados,
                       IFNULL(t.valor_hora_mes{$idMes}, SUM(s.valor_hora_funcao)) AS valor_hora,
                       FORMAT(t.valor_faturado_mes{$idMes}, 2, 'de_DE') AS receita_projetada,
                       FORMAT(IFNULL(t.valor_hora_mes{$idMes}, s.valor_hora_funcao) * (SUM(IFNULL(TIME_TO_SEC(s.total_horas_mes{$idMes}), s.total_mes{$idMes})) / 3600), 2, 'de_DE') AS receita_efetuada,
                       SUM(s.pagamentos_efetuados) AS pagamentos_efetuados,
                       FORMAT((IFNULL(t.valor_hora_mes{$idMes}, s.valor_hora_funcao) * (SUM(IFNULL(TIME_TO_SEC(s.total_horas_mes{$idMes}), s.total_mes{$idMes})) / 3600)) - SUM(s.pagamentos_efetuados), 2, 'de_DE') AS resultado,
                       NULL AS resultado_percentual
                FROM (SELECT c.id_alocacao, 
                             b.cuidador,
                             SUM(TIME_TO_SEC(IFNULL(d.total_mes{$idMes}, 0))) AS total_mes{$idMes},
                             IF(e.data_aprovacao_mes{$idMes}, a.total_horas_mes{$idMes}, NULL) AS total_horas_mes{$idMes}, 
                             IF(e.data_aprovacao_mes{$idMes}, a.horas_descontadas_mes{$idMes}, NULL) AS horas_descontadas_mes{$idMes}, 
                             IFNULL(a.valor_total_mes{$idMes}, d.valor_hora_operacional * ((IFNULL(TIME_TO_SEC(d.horas_mensais_custo), 0) + IFNULL(TIME_TO_SEC(a.horas_descontadas_mes{$idMes}), 0)) / 3600)) AS pagamentos_efetuados,
                             d.cargo, d.funcao, d.valor_hora_funcao
                      FROM ei_alocados_totalizacao a
                      INNER JOIN ei_alocados b ON b.id = a.id_alocado
                      INNER JOIN ei_alocacao_escolas c ON c.id = b.id_alocacao_escola
                      LEFT JOIN ei_alocados_horarios d ON d.id_alocado = b.id AND d.periodo = a.periodo
                      LEFT JOIN ei_faturamento e ON e.id_alocacao = c.id_alocacao AND e.id_escola = c.id_escola
                      WHERE c.id_alocacao IN ({$data['alocacao']->id})
                            AND d.cargo IS NOT NULL 
                            AND d.funcao IS NOT NULL
                      GROUP BY b.id, a.periodo, d.cargo, d.funcao) s
                LEFT JOIN ei_faturamento_consolidado t ON
                          t.id_alocacao = s.id_alocacao AND t.cargo = s.cargo AND t.funcao = s.funcao
                GROUP BY s.cargo, s.funcao";

        $data['funcoes'] = $this->db->query($sql)->result();


        $this->db->select('c.cuidador, d2.funcao, d.cnpj, b.escola');
        $this->db->select(["DATE_FORMAT(e.data_liberacao_pagto_mes{$idMes}, '%d/%m/%Y') AS data_liberacao_pagto"], false);
        $this->db->select(["TIME_FORMAT(IFNULL(c2.total_horas_faturadas_mes{$idMes}, SEC_TO_TIME(TIME_TO_SEC(d2.horas_mensais_custo) + IFNULL(TIME_TO_SEC(c2.horas_descontadas_mes{$idMes}), 0))), '%H:%i') AS total_horas"], false);
        $this->db->select(["FORMAT(IFNULL(c2.valor_total_mes{$idMes}, d2.valor_hora_operacional * ((IFNULL(TIME_TO_SEC(d2.horas_mensais_custo), 0) + IFNULL(TIME_TO_SEC(c2.horas_descontadas_mes{$idMes}), 0)) / 3600)), 2, 'de_DE') AS valor_total"], false);
        $this->db->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id');
        $this->db->join('ei_alocados c', 'c.id_alocacao_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_cuidador');
        $this->db->join('ei_alocados_horarios d2', 'd2.id_alocado = c.id');
        $this->db->join('ei_alocados_totalizacao c2', 'c2.id_alocado = c.id AND c2.periodo = d2.periodo', 'left');
        $this->db->join('ei_pagamento_prestador e', 'e.id_alocacao = a.id AND e.id_cuidador = c.id_cuidador', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.depto', $this->input->get_post('depto'));
        $this->db->where('a.id_diretoria', $this->input->get_post('diretoria'));
        $this->db->where('a.id_supervisor', $this->input->get_post('supervisor'));
        $this->db->where('a.ano', $this->input->get_post('ano'));
        $this->db->where('a.semestre', $this->input->get_post('semestre'));
        $this->db->group_by(['c.id_cuidador', 'b.id_escola']);
        $this->db->order_by('c.cuidador', 'asc');
        $this->db->order_by('b.escola', 'asc');
        $data['rows'] = $this->db->get('ei_alocacao a')->result();


        $this->load->helper('time');

        if ($data['is_pdf']) {
            return $this->load->view('ei/relatorio_medicao', $data, true);
        }

        $this->load->view('ei/relatorio_medicao', $data);
    }


    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#funcionarios { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#funcionarios thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#funcionarios tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#legenda { border: 0px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#legenda thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border-bottom: 2px solid #444; } ';
        $stylesheet .= '#legenda tbody td { font-size: 12px; padding: 4px; vertical-align: top; border-bottom: 1px solid #444; } ';
        $stylesheet .= '#legenda tbody tr:nth-child(8) td { font-size: 13px; padding: 5px; font-weight: bold; background-color: #f5f5f5; } ';

        $stylesheet .= '#observacoes { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#observacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#observacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->funcionarios(true));

        $data = $this->input->get();

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Medição de Funcionários - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }


    public function pdfMedicao()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= 'table.medicao {  border: 1px solid #333; margin-bottom: 0px; } ';
        $stylesheet .= 'table.medicao thead tr th { font-size: 13px; padding: 4px; background-color: #f5f5f5; border: 1px solid #333;  } ';
        $stylesheet .= 'table.medicao tbody tr td { font-size: 12px; padding: 4px; vertical-align: top; border: 1px solid #333;  } ';


        $this->m_pdf->pdf->setTopMargin(52);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->medicao(true));

        $data = $this->input->get();

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Relatório de Medição Mensal de Educação Inclusiva - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

    public function pdfEscolas()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#escolas { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#escolas thead th { font-size: 12px; padding: 4px; background-color: #DFF0D8; border: 1px solid #444; } ';
        $stylesheet .= '#escolas thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#escolas tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#legenda { border: 0px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#legenda thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border-bottom: 2px solid #444; } ';
        $stylesheet .= '#legenda tbody td { font-size: 12px; padding: 4px; vertical-align: top; border-bottom: 1px solid #444; } ';
        $stylesheet .= '#legenda tbody tr:nth-child(8) td { font-size: 13px; padding: 5px; font-weight: bold; background-color: #f5f5f5; } ';

        $stylesheet .= '#observacoes { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#observacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#observacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->escolas(true));

        $data = $this->input->get();

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Medição de Escolas - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

    public function pdfInsumos()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#insumos { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#insumos thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#insumos tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->insumos(true));

        $data = $this->input->get();

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Apontamento de Insumos - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

    public function pdfCuidadores()
    {
        $id_empresa = $this->session->userdata('empresa');
        $diretoria = $this->input->get('diretoria');
        $depto = $this->input->get('depto');
        $supervisor = $this->input->get('supervisor');
        $mes = $this->input->get('mes');
        $ano = $this->input->get('ano');

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
                       s.municipio_escola,
                       s.cuidador,
                       s.data_admissao,
                       s.vale_transporte,
                       s.id_turno,
                       s.aluno,
                       s.hipotese_diagnostica,
                       s.turno
                FROM (SELECT a.id,
                             CONCAT(a.municipio, ' &emsp; <strong>Escola:</strong> ', b.escola) AS municipio_escola,
                             (CASE b.turno 
                           WHEN 'M' THEN 1
                           WHEN 'T' THEN 2
                           WHEN 'N' THEN 3
                           END) AS id_turno,
                             (CASE b.turno 
                                   WHEN 'M' THEN 'Manhã'
                                   WHEN 'T' THEN 'Tarde'
                                   WHEN 'N' THEN 'Noite'
                                   END) AS turno,
                     GROUP_CONCAT(CONCAT('<strong>&#149; </strong>', IFNULL(b.cuidador, CONCAT('<span class=\"text-danger\">', IF(b.remanejado = 2, 'Alocar cuidador', IF(b.remanejado = 1, 'Remanejado', 'A contratar')), '</span>')) ) ORDER BY b.cuidador SEPARATOR '<br>') AS cuidador,
                     CASE WHEN b.cuidador IS NOT NULL 
                          THEN GROUP_CONCAT(CONCAT('<strong>&#149; </strong>', c.data_admissao) ORDER BY b.cuidador SEPARATOR '<br>') 
                          END AS data_hora_admissao,
                     CASE WHEN b.cuidador IS NOT NULL
                          THEN GROUP_CONCAT(CONCAT('<strong>&#149; </strong>', DATE_FORMAT(c.data_admissao, '%d/%m/%Y')) ORDER BY b.cuidador SEPARATOR '<br>') 
                          END AS data_admissao,
                     CASE WHEN b.cuidador IS NOT NULL
                          THEN GROUP_CONCAT(CONCAT('<strong>&#149; </strong>', IF(CHAR_LENGTH(c.valor_vt) > 0, CONCAT(c.nome_cartao, ' (', c.valor_vt, ')'), c.nome_cartao)) ORDER BY b.cuidador SEPARATOR '<br>') 
                          END AS vale_transporte,
                             d.aluno,
                             d.hipotese_diagnostica
                      FROM ei_alocacao a
                      INNER JOIN ei_alocados b 
                                 ON b.id_alocacao = a.id
                      LEFT JOIN usuarios c ON
                                c.nome = b.cuidador
                      LEFT JOIN ei_matriculados d 
                                 ON d.id_alocacao = a.id 
                                 AND d.escola = b.escola
                                 AND d.turno = b.turno
                                 AND d.status IN ('A','N')
                      WHERE a.id_empresa = {$id_empresa}
                            AND DATE_FORMAT(a.data, '%Y-%m') = '{$ano}-{$mes}'
                            AND (a.depto = '{$depto}' OR CHAR_LENGTH('{$depto}') = 0)
                            AND (CHAR_LENGTH('{$diretoria}') = 0
                                 OR a.diretoria = (SELECT nome 
                                                   FROM ei_diretorias 
                                                   WHERE id = '{$diretoria}'))
                            AND (CHAR_LENGTH('{$supervisor}') = 0
                                 OR b.supervisor = (SELECT nome 
                                                    FROM usuarios 
                                                    WHERE id = '{$supervisor}'))
                      GROUP BY b.escola, b.turno, d.aluno, d.turno
                      ORDER BY a.municipio, 
                               b.cuidador, 
                               b.escola, 
                               b.turno, 
                               d.aluno) s";

        $data['rows'] = $this->db->query($sql)->result();

        $this->load->library('m_pdf');

        $stylesheet = '#cuidadores thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#cuidadores thead tr, #cuidadores tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#cuidadores tbody td { font-size: 10px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 10px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->load->view('ei/escolasPdf', $data, true));

        $this->m_pdf->pdf->Output('Relação de Escolas.pdf', 'D');
    }


    public function resultados($pdf = false)
    {
        if ($pdf !== true) {
            $pdf = false;
        }

        $get = $this->input->get();

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $this->db->select("a.*, DATE_FORMAT(b.data, '%m') AS mes", false);
        $this->db->join('ei_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('ei_diretorias c', 'c.nome = b.diretoria AND c.depto = b.depto');
        $this->db->join('ei_escolas d', 'd.id_diretoria = c.id');
        $this->db->join('ei_supervisores e', 'e.id_escola = d.id');
        $this->db->join('usuarios f', 'f.id = e.id_supervisor AND f.nome = a.supervisor');
        $this->db->where('c.id', $get['diretoria']);
        $this->db->where('c.depto', $get['depto']);
        $this->db->where('e.id_supervisor', $get['supervisor']);
        $this->db->where("DATE_FORMAT(b.data, '%Y') =", $get['ano']);
        $this->db->order_by('b.data', 'asc');
        $rows = $this->db->get('ei_observacoes a')->result();
        /*
        $this->db->query("SET lc_time_names = 'pt_BR'");
        $this->db->select("a.*, DATE_FORMAT(a.data, '%m') AS mes", false);
        $this->db->join('ei_diretorias b', 'b.nome = a.diretoria AND b.depto = a.depto');
        $this->db->where('b.id', $get['diretoria']);
        $this->db->where('b.depto', $get['depto']);
        $this->db->where("DATE_FORMAT(a.data, '%Y') =", $get['ano']);
        $this->db->order_by('a.data', 'asc');
        $rows = $this->db->get('ei_alocacao a')->result();*/

        $data = array();
        $data['total_meses'] = 14;

        /*$this->db->select('DISTINCT(a.data)', false);
        $this->db->join('ei_diretorias b', 'b.nome = a.diretoria AND b.depto = a.depto');
        $this->db->where('b.id', $get['diretoria']);
        $this->db->where('b.depto', $get['depto']);
        $this->db->where("DATE_FORMAT(a.data, '%Y') =", $get['ano']);
        $total_meses = $this->db->get('ei_alocacao a')->result();*/

        $this->db->select('nome, depto');
        $diretoria = $this->db->get_where('ei_diretorias', array('id' => $get['diretoria']))->row();
        $data['departamento'] = $diretoria->depto;
        $data['diretoria'] = $diretoria->nome;

        $this->db->select('nome');
        $supervisor = $this->db->get_where('usuarios', array('id' => $get['supervisor']))->row();
        $data['supervisor'] = $supervisor->nome;

        /*$this->db->select('COUNT(DISTINCT(b.cuidador)) AS total', false);
        $this->db->join('ei_alocados b', 'b.id_alocacao = a.id', 'left');
        $this->db->where("DATE_FORMAT(a.data, '%Y') =", $get['ano']);
        $this->db->where('a.depto', $diretoria->depto);
        $this->db->where('a.diretoria', $diretoria->nome);
        $this->db->where('a.supervisor', $supervisor->nome);
        $this->db->where("CHAR_LENGTH(b.cuidador) >", 0);
        $data['total_alocados'] = $this->db->get('ei_alocacao a')->row()->total;

        $this->db->select('COUNT(DISTINCT(b.aluno)) AS total', false);
        $this->db->join('ei_matriculados b', 'b.id_alocacao = a.id', 'left');
        $this->db->where("DATE_FORMAT(a.data, '%Y') =", $get['ano']);
        $this->db->where('a.depto', $diretoria->depto);
        $this->db->where('a.diretoria', $diretoria->nome);
        $this->db->where('a.supervisor', $supervisor->nome);
        $this->db->where("CHAR_LENGTH(b.aluno) >", 0);
        $data['total_matriculados'] = $this->db->get('ei_alocacao a')->row()->total;*/


        $data['meses'] = array(
            '01' => 'Jan',
            '02' => 'Fev',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'Mai',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Set',
            '10' => 'Out',
            '11' => 'Nov',
            '12' => 'Dez'
        );


        $data['ano'] = $get['ano'];
        $data['is_pdf'] = $pdf;
        $data['query_string'] = http_build_query($get);
        $data['modo'] = 'normal';

        $mesesVazios = array(
            '01' => null,
            '02' => null,
            '03' => null,
            '04' => null,
            '05' => null,
            '06' => null,
            '07' => null,
            '08' => null,
            '09' => null,
            '10' => null,
            '11' => null,
            '12' => null
        );

        $data['total_faltas'] = $mesesVazios;
        $data['total_faltas_justificadas'] = $mesesVazios;
        $data['turnover_substituicao'] = $mesesVazios;
        $data['turnover_aumento_quadro'] = $mesesVazios;
        $data['turnover_desligamento_empresa'] = $mesesVazios;
        $data['turnover_desligamento_solicitacao'] = $mesesVazios;
        $data['intercorrencias_diretoria'] = $mesesVazios;
        $data['intercorrencias_cuidador'] = $mesesVazios;
        $data['intercorrencias_alunos'] = $mesesVazios;
        $data['acidentes_trabalho'] = $mesesVazios;
        $data['total_escolas'] = $mesesVazios;
        $data['total_alunos'] = $mesesVazios;
        $data['dias_letivos'] = $mesesVazios;
        $data['total_cuidadores'] = $mesesVazios;
        $data['total_cuidadores_cobrados'] = $mesesVazios;
        $data['total_cuidadores_ativos'] = $mesesVazios;
        $data['total_cuidadores_afastados'] = $mesesVazios;
        $data['total_supervisores'] = $mesesVazios;
        $data['total_supervisores_cobrados'] = $mesesVazios;
        $data['total_supervisores_ativos'] = $mesesVazios;
        $data['total_supervisores_afastados'] = $mesesVazios;
        $data['faturamentos_projetados'] = $mesesVazios;
        $data['faturamentos_realizados'] = $mesesVazios;

        foreach ($rows as $row) {
            $mes = $row->mes;
            $data['total_faltas'][$mes] = $row->total_faltas;
            $data['total_faltas_justificadas'][$mes] = $row->total_faltas_justificadas;
            $data['turnover_substituicao'][$mes] = $row->turnover_substituicao;
            $data['turnover_aumento_quadro'][$mes] = $row->turnover_aumento_quadro;
            $data['turnover_desligamento_empresa'][$mes] = $row->turnover_desligamento_empresa;
            $data['turnover_desligamento_solicitacao'][$mes] = $row->turnover_desligamento_solicitacao;
            $data['intercorrencias_diretoria'][$mes] = $row->intercorrencias_diretoria;
            $data['intercorrencias_cuidador'][$mes] = $row->intercorrencias_cuidador;
            $data['intercorrencias_alunos'][$mes] = $row->intercorrencias_alunos;
            $data['acidentes_trabalho'][$mes] = $row->acidentes_trabalho;
            $data['total_escolas'][$mes] = $row->total_escolas;
            $data['total_alunos'][$mes] = $row->total_alunos;
            $data['dias_letivos'][$mes] = $row->dias_letivos;
            $data['total_cuidadores'][$mes] = $row->total_cuidadores;
            $data['total_cuidadores_cobrados'][$mes] = $row->total_cuidadores_cobrados;
            $data['total_cuidadores_ativos'][$mes] = $row->total_cuidadores_ativos;
            $data['total_cuidadores_afastados'][$mes] = $row->total_cuidadores_afastados;
            $data['total_supervisores'][$mes] = $row->total_supervisores;
            $data['total_supervisores_cobrados'][$mes] = $row->total_supervisores_cobrados;
            $data['total_supervisores_ativos'][$mes] = $row->total_supervisores_ativos;
            $data['total_supervisores_afastados'][$mes] = $row->total_supervisores_afastados;
            $data['faturamentos_projetados'][$mes] = $row->faturamento_projetado;
            $data['faturamentos_realizados'][$mes] = $row->faturamento_realizado;
        }

        if ($pdf) {
            return $this->load->view('ei/relatorio_resultados', $data, true);
        } else {
            $this->load->view('ei/relatorio_resultados', $data);
        }

    }

    public function resultadosDiretorias($pdf = false)
    {
        if ($pdf !== true) {
            $pdf = false;
        }

        $get = $this->input->get();

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $this->db->select("DATE_FORMAT(b.data, '%m') AS mes", false);
        $this->db->select('SUM(a.total_faltas) AS total_faltas', false);
        $this->db->select('SUM(a.total_faltas_justificadas) AS total_faltas_justificadas', false);
        $this->db->select('SUM(a.turnover_substituicao) AS turnover_substituicao', false);
        $this->db->select('SUM(a.turnover_aumento_quadro) AS turnover_aumento_quadro', false);
        $this->db->select('SUM(a.turnover_desligamento_empresa) AS turnover_desligamento_empresa', false);
        $this->db->select('SUM(a.turnover_desligamento_solicitacao) AS turnover_desligamento_solicitacao', false);
        $this->db->select('SUM(a.intercorrencias_diretoria) AS intercorrencias_diretoria', false);
        $this->db->select('SUM(a.intercorrencias_cuidador) AS intercorrencias_cuidador', false);
        $this->db->select('SUM(a.intercorrencias_alunos) AS intercorrencias_alunos', false);
        $this->db->select('SUM(a.acidentes_trabalho) AS acidentes_trabalho', false);
        $this->db->select('SUM(a.total_escolas) AS total_escolas', false);
        $this->db->select('SUM(a.total_alunos) AS total_alunos', false);
        $this->db->select('SUM(a.total_cuidadores) AS total_cuidadores', false);
        $this->db->select('SUM(a.total_cuidadores_cobrados) AS total_cuidadores_cobrados', false);
        $this->db->select('SUM(a.total_cuidadores_ativos) AS total_cuidadores_ativos', false);
        $this->db->select('SUM(a.total_cuidadores_afastados) AS total_cuidadores_afastados', false);
        $this->db->select('SUM(a.total_supervisores) AS total_supervisores', false);
        $this->db->select('SUM(a.total_supervisores_cobrados) AS total_supervisores_cobrados', false);
        $this->db->select('SUM(a.total_supervisores_ativos) AS total_supervisores_ativos', false);
        $this->db->select('SUM(a.total_supervisores_afastados) AS total_supervisores_afastados', false);
        $this->db->select('SUM(a.faturamento_projetado) AS faturamento_projetado', false);
        $this->db->select('SUM(a.faturamento_realizado) AS faturamento_realizado', false);
        $this->db->join('ei_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('ei_diretorias c', 'c.nome = b.diretoria AND c.depto = b.depto AND c.municipio = b.municipio');
        $this->db->where('c.id', $get['diretoria']);
        $this->db->where('c.depto', $get['depto']);
        $this->db->where("DATE_FORMAT(b.data, '%Y') =", $get['ano']);
        $this->db->group_by('b.data');
        $this->db->order_by('b.data', 'asc');
        $rows = $this->db->get('ei_observacoes a')->result();

        $data = array();
        $data['total_meses'] = 14;


        $this->db->select('nome, depto');
        $diretoria = $this->db->get_where('ei_diretorias', array('id' => $get['diretoria']))->row();
        $data['departamento'] = $diretoria->depto;
        $data['diretoria'] = $diretoria->nome;

        $this->db->select('supervisor AS nome', false);
        $this->db->where("DATE_FORMAT(data, '%Y') =", $get['ano']);
        $this->db->where('depto', $diretoria->depto);
        $this->db->where('diretoria', $diretoria->nome);
        $this->db->group_by('supervisor');
        $this->db->order_by('supervisor', 'asc');
        $supervisores = $this->db->get('ei_alocacao')->result();
        foreach ($supervisores as $supervisor) {
            $data['supervisor'][] = $supervisor->nome;
        }

        /*$this->db->select('COUNT(DISTINCT(b.cuidador)) AS total', false);
        $this->db->join('ei_alocados b', 'b.id_alocacao = a.id', 'left');
        $this->db->where("DATE_FORMAT(a.data, '%Y') =", $get['ano']);
        $this->db->where('a.depto', $diretoria->depto);
        $this->db->where('a.diretoria', $diretoria->nome);
        $this->db->where("CHAR_LENGTH(b.cuidador) >", 0);
        $data['total_alocados'] = $this->db->get('ei_alocacao a')->row()->total;

        $this->db->select('COUNT(DISTINCT(b.aluno)) AS total', false);
        $this->db->join('ei_matriculados b', 'b.id_alocacao = a.id', 'left');
        $this->db->where("DATE_FORMAT(a.data, '%Y') =", $get['ano']);
        $this->db->where('a.depto', $diretoria->depto);
        $this->db->where('a.diretoria', $diretoria->nome);
        $this->db->where("CHAR_LENGTH(b.aluno) >", 0);
        $data['total_matriculados'] = $this->db->get('ei_alocacao a')->row()->total;*/


        $data['meses'] = array(
            '01' => 'Jan',
            '02' => 'Fev',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'Mai',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Set',
            '10' => 'Out',
            '11' => 'Nov',
            '12' => 'Dez'
        );


        $data['ano'] = $get['ano'];
        $data['is_pdf'] = $pdf;
        $data['query_string'] = http_build_query($get);
        $data['modo'] = 'diretorias';

        $mesesVazios = array(
            '01' => null,
            '02' => null,
            '03' => null,
            '04' => null,
            '05' => null,
            '06' => null,
            '07' => null,
            '08' => null,
            '09' => null,
            '10' => null,
            '11' => null,
            '12' => null
        );

        $data['total_faltas'] = $mesesVazios;
        $data['total_faltas_justificadas'] = $mesesVazios;
        $data['turnover_substituicao'] = $mesesVazios;
        $data['turnover_aumento_quadro'] = $mesesVazios;
        $data['turnover_desligamento_empresa'] = $mesesVazios;
        $data['turnover_desligamento_solicitacao'] = $mesesVazios;
        $data['intercorrencias_diretoria'] = $mesesVazios;
        $data['intercorrencias_cuidador'] = $mesesVazios;
        $data['intercorrencias_alunos'] = $mesesVazios;
        $data['acidentes_trabalho'] = $mesesVazios;
        $data['total_escolas'] = $mesesVazios;
        $data['total_alunos'] = $mesesVazios;
        $data['dias_letivos'] = null;
        $data['total_cuidadores'] = $mesesVazios;
        $data['total_cuidadores_cobrados'] = $mesesVazios;
        $data['total_cuidadores_ativos'] = $mesesVazios;
        $data['total_cuidadores_afastados'] = $mesesVazios;
        $data['total_supervisores'] = $mesesVazios;
        $data['total_supervisores_cobrados'] = $mesesVazios;
        $data['total_supervisores_ativos'] = $mesesVazios;
        $data['total_supervisores_afastados'] = $mesesVazios;
        $data['faturamentos_projetados'] = $mesesVazios;
        $data['faturamentos_realizados'] = $mesesVazios;

        foreach ($rows as $row) {
            $mes = $row->mes;
            $data['total_faltas'][$mes] = $row->total_faltas;
            $data['total_faltas_justificadas'][$mes] = $row->total_faltas_justificadas;
            $data['turnover_substituicao'][$mes] = $row->turnover_substituicao;
            $data['turnover_aumento_quadro'][$mes] = $row->turnover_aumento_quadro;
            $data['turnover_desligamento_empresa'][$mes] = $row->turnover_desligamento_empresa;
            $data['turnover_desligamento_solicitacao'][$mes] = $row->turnover_desligamento_solicitacao;
            $data['intercorrencias_diretoria'][$mes] = $row->intercorrencias_diretoria;
            $data['intercorrencias_cuidador'][$mes] = $row->intercorrencias_cuidador;
            $data['intercorrencias_alunos'][$mes] = $row->intercorrencias_alunos;
            $data['acidentes_trabalho'][$mes] = $row->acidentes_trabalho;
            $data['total_escolas'][$mes] = $row->total_escolas;
            $data['total_alunos'][$mes] = $row->total_alunos;
            //$data['dias_letivos'][$mes] = $row->dias_letivos;
            $data['total_cuidadores'][$mes] = $row->total_cuidadores;
            $data['total_cuidadores_cobrados'][$mes] = $row->total_cuidadores_cobrados;
            $data['total_cuidadores_ativos'][$mes] = $row->total_cuidadores_ativos;
            $data['total_cuidadores_afastados'][$mes] = $row->total_cuidadores_afastados;
            $data['total_supervisores'][$mes] = $row->total_supervisores;
            $data['total_supervisores_cobrados'][$mes] = $row->total_supervisores_cobrados;
            $data['total_supervisores_ativos'][$mes] = $row->total_supervisores_ativos;
            $data['total_supervisores_afastados'][$mes] = $row->total_supervisores_afastados;
            $data['faturamentos_projetados'][$mes] = $row->faturamento_projetado;
            $data['faturamentos_realizados'][$mes] = $row->faturamento_realizado;
        }

        if ($pdf) {
            return $this->load->view('ei/relatorio_resultados', $data, true);
        } else {
            $this->load->view('ei/relatorio_resultados', $data);
        }

    }

    public function pdfResultados()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#recursos_alocados thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#recursos_alocados { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#recursos_alocados thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#recursos_alocados tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#faltas { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#faltas thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#faltas tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#movimentacoes { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#movimentacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#movimentacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#faturamento { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#faturamento thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#faturamento tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->resultados(true));

        $data = $this->input->get();

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Cuidadores - Acompanhamento individual ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

    public function pdfResultadosDiretorias()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#recursos_alocados thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#recursos_alocados { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#recursos_alocados thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#recursos_alocados tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#faltas { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#faltas thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#faltas tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#movimentacoes { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#movimentacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#movimentacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#faturamento { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#faturamento thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#faturamento tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->resultadosDiretorias(true));

        $data = $this->input->get();

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Cuidadores - Acompanhamento de diretoria ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

    public function resultadosConsolidados($pdf = false)
    {
        if ($pdf !== true) {
            $pdf = false;
        }

        $get = $this->input->get();

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $this->db->select("DATE_FORMAT(b.data, '%m') AS mes", false);
        $this->db->select('SUM(a.total_faltas) AS total_faltas', false);
        $this->db->select('SUM(a.total_faltas_justificadas) AS total_faltas_justificadas', false);
        $this->db->select('SUM(a.turnover_substituicao) AS turnover_substituicao', false);
        $this->db->select('SUM(a.turnover_aumento_quadro) AS turnover_aumento_quadro', false);
        $this->db->select('SUM(a.turnover_desligamento_empresa) AS turnover_desligamento_empresa', false);
        $this->db->select('SUM(a.turnover_desligamento_solicitacao) AS turnover_desligamento_solicitacao', false);
        $this->db->select('SUM(a.intercorrencias_diretoria) AS intercorrencias_diretoria', false);
        $this->db->select('SUM(a.intercorrencias_cuidador) AS intercorrencias_cuidador', false);
        $this->db->select('SUM(a.intercorrencias_alunos) AS intercorrencias_alunos', false);
        $this->db->select('SUM(a.acidentes_trabalho) AS acidentes_trabalho', false);
        $this->db->select('SUM(a.total_escolas) AS total_escolas', false);
        $this->db->select('SUM(a.total_alunos) AS total_alunos', false);
        $this->db->select('SUM(a.total_cuidadores) AS total_cuidadores', false);
        $this->db->select('SUM(a.total_cuidadores_cobrados) AS total_cuidadores_cobrados', false);
        $this->db->select('SUM(a.total_cuidadores_ativos) AS total_cuidadores_ativos', false);
        $this->db->select('SUM(a.total_cuidadores_afastados) AS total_cuidadores_afastados', false);
        $this->db->select('SUM(a.total_supervisores) AS total_supervisores', false);
        $this->db->select('SUM(a.total_supervisores_cobrados) AS total_supervisores_cobrados', false);
        $this->db->select('SUM(a.total_supervisores_ativos) AS total_supervisores_ativos', false);
        $this->db->select('SUM(a.total_supervisores_afastados) AS total_supervisores_afastados', false);
        $this->db->select('SUM(a.faturamento_projetado) AS faturamento_projetado', false);
        $this->db->select('SUM(a.faturamento_realizado) AS faturamento_realizado', false);
        $this->db->join('ei_alocacao b', 'b.id = a.id_alocacao');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
//        $this->db->join('ei_diretorias c', 'c.nome = b.diretoria AND c.depto = b.depto');
//        $this->db->join('ei_escolas d', 'd.id_diretoria = c.id');
//        $this->db->join('ei_supervisores e', 'e.id_escola = d.id');
//        $this->db->join('usuarios f', 'f.id = e.id_supervisor AND f.nome = a.supervisor');
//        $this->db->where('c.id', $get['diretoria']);
//        $this->db->where('c.depto', $get['depto']);
//        $this->db->where('e.id_supervisor', $get['supervisor']);
        $this->db->where("DATE_FORMAT(b.data, '%Y') =", $get['ano']);
        $this->db->group_by('b.data');
        $this->db->order_by('b.data', 'asc');
        $rows = $this->db->get('ei_observacoes a')->result();

        /*$this->db->select('COUNT(DISTINCT(b.cuidador)) AS total', false);
        $this->db->join('ei_alocados b', 'b.id_alocacao = a.id', 'left');
        $this->db->where("DATE_FORMAT(a.data, '%Y') =", $get['ano']);
        $this->db->where("CHAR_LENGTH(b.cuidador) >", 0);
        $data['total_alocados'] = $this->db->get('ei_alocacao a')->row()->total;

        $this->db->select('COUNT(DISTINCT(b.aluno)) AS total', false);
        $this->db->join('ei_matriculados b', 'b.id_alocacao = a.id', 'left');
        $this->db->where("DATE_FORMAT(a.data, '%Y') =", $get['ano']);
        $this->db->where("CHAR_LENGTH(b.aluno) >", 0);
        $data['total_matriculados'] = $this->db->get('ei_alocacao a')->row()->total;*/


        $data = array();
        $data['total_meses'] = 14;
        $data['meses'] = array(
            '01' => 'Jan',
            '02' => 'Fev',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'Mai',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Set',
            '10' => 'Out',
            '11' => 'Nov',
            '12' => 'Dez'
        );


        $data['ano'] = $get['ano'];
        $data['is_pdf'] = $pdf;
        $data['query_string'] = http_build_query($get);
        $data['modo'] = 'consolidado';

        $mesesVazios = array(
            '01' => null,
            '02' => null,
            '03' => null,
            '04' => null,
            '05' => null,
            '06' => null,
            '07' => null,
            '08' => null,
            '09' => null,
            '10' => null,
            '11' => null,
            '12' => null
        );

        $data['total_faltas'] = $mesesVazios;
        $data['total_faltas_justificadas'] = $mesesVazios;
        $data['turnover_substituicao'] = $mesesVazios;
        $data['turnover_aumento_quadro'] = $mesesVazios;
        $data['turnover_desligamento_empresa'] = $mesesVazios;
        $data['turnover_desligamento_solicitacao'] = $mesesVazios;
        $data['intercorrencias_diretoria'] = $mesesVazios;
        $data['intercorrencias_cuidador'] = $mesesVazios;
        $data['intercorrencias_alunos'] = $mesesVazios;
        $data['acidentes_trabalho'] = $mesesVazios;
        $data['total_escolas'] = $mesesVazios;
        $data['total_alunos'] = $mesesVazios;
        $data['dias_letivos'] = null;
        $data['total_cuidadores'] = $mesesVazios;
        $data['total_cuidadores_cobrados'] = $mesesVazios;
        $data['total_cuidadores_ativos'] = $mesesVazios;
        $data['total_cuidadores_afastados'] = $mesesVazios;
        $data['total_supervisores'] = $mesesVazios;
        $data['total_supervisores_cobrados'] = $mesesVazios;
        $data['total_supervisores_ativos'] = $mesesVazios;
        $data['total_supervisores_afastados'] = $mesesVazios;
        $data['faturamentos_projetados'] = $mesesVazios;
        $data['faturamentos_realizados'] = $mesesVazios;

        foreach ($rows as $row) {
            $mes = $row->mes;
            $data['total_faltas'][$mes] = $row->total_faltas;
            $data['total_faltas_justificadas'][$mes] = $row->total_faltas_justificadas;
            $data['turnover_substituicao'][$mes] = $row->turnover_substituicao;
            $data['turnover_aumento_quadro'][$mes] = $row->turnover_aumento_quadro;
            $data['turnover_desligamento_empresa'][$mes] = $row->turnover_desligamento_empresa;
            $data['turnover_desligamento_solicitacao'][$mes] = $row->turnover_desligamento_solicitacao;
            $data['intercorrencias_diretoria'][$mes] = $row->intercorrencias_diretoria;
            $data['intercorrencias_cuidador'][$mes] = $row->intercorrencias_cuidador;
            $data['intercorrencias_alunos'][$mes] = $row->intercorrencias_alunos;
            $data['acidentes_trabalho'][$mes] = $row->acidentes_trabalho;
            $data['total_escolas'][$mes] = $row->total_escolas;
            $data['total_alunos'][$mes] = $row->total_alunos;
            $data['total_cuidadores'][$mes] = $row->total_cuidadores;
            $data['total_cuidadores_cobrados'][$mes] = $row->total_cuidadores_cobrados;
            $data['total_cuidadores_ativos'][$mes] = $row->total_cuidadores_ativos;
            $data['total_cuidadores_afastados'][$mes] = $row->total_cuidadores_afastados;
            $data['total_supervisores'][$mes] = $row->total_supervisores;
            $data['total_supervisores_cobrados'][$mes] = $row->total_supervisores_cobrados;
            $data['total_supervisores_ativos'][$mes] = $row->total_supervisores_ativos;
            $data['total_supervisores_afastados'][$mes] = $row->total_supervisores_afastados;
            $data['faturamentos_projetados'][$mes] = $row->faturamento_projetado;
            $data['faturamentos_realizados'][$mes] = $row->faturamento_realizado;
        }

        if ($pdf) {
            return $this->load->view('ei/relatorio_resultados', $data, true);
        } else {
            $this->load->view('ei/relatorio_resultados', $data);
        }

    }

    public function pdfResultadosConsolidados()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#recursos_alocados thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#recursos_alocados { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#recursos_alocados thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#recursos_alocados tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#faltas { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#faltas thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#faltas tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#movimentacoes { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#movimentacoes thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#movimentacoes tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $stylesheet .= '#faturamento { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#faturamento thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#faturamento tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->resultadosConsolidados(true));

        $data = $this->input->get();

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Cuidadores - Acompanhamento mensal consolidado ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }


    public function pdfMapaCarregamento()
    {
        $get = $this->input->get();
        $idMes = intval($get['mes']) - ($get['semestre'] > 1 ? 7 : 0);


        $empresa = $this->session->userdata('empresa');
        $this->load->library('m_pdf');

        $stylesheet = 'table { font-size: 12px; } ';
        $stylesheet .= 'table tr th, table tr td { border: 1px solid #fff; } ';
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);

        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $empresa))->row();

        $this->db->select('a.id, COUNT(DISTINCT(b.escola)) AS total_escolas', false);
        $this->db->select('COUNT(DISTINCT(f.aluno)) AS total_alunos', false);
        $this->db->select('COUNT(DISTINCT(c.cuidador)) AS total_profissionais', false);
        $this->db->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id', 'left');
        $this->db->join('ei_alocados c', 'c.id_alocacao_escola = b.id');
        $this->db->join('ei_alocados_horarios d', 'd.id_alocado = c.id', 'left');
        $this->db->join('ei_matriculados_turmas e', 'e.id_alocado_horario = d.id', 'left');
        $this->db->join('ei_matriculados f', 'f.id = e.id_matriculado AND f.id_alocacao_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        $this->db->where('a.depto', $get['depto']);
        $this->db->where('a.id_diretoria', $get['diretoria']);
        $this->db->where('a.id_supervisor', $get['supervisor']);
        $this->db->where('a.ano', $get['ano']);
        $this->db->where('a.semestre', $get['semestre']);
        $alocacao = $this->db->get('ei_alocacao a')->row();

        $cabecalho = '<table width="100%">
            <thead>
            <tr>
                <td>
                    <img src="' . base_url('imagens/usuarios/' . $usuario->foto) . '" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="vertical-align: top;" width="100%">
                    <p>
                        <img src="' . base_url('imagens/usuarios/' . $usuario->foto_descricao) . '" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" style="padding-bottom: 12px;  text-align: center; border-top: 5px solid #ddd; border-bottom: 2px solid #ddd; padding:5px;">
                    <h1 style="font-weight: bold;">MAPA DE CARREGAMENTO DE O. S. - ' . $get['ano'] . '/' . ($get['mes'] > 6 ? 2 : 1) . '</h1>
                </td>
            </tr>
                        </tbody>
        </table>
        <table width="100%">
            <tr>
                <td style="text-align: center;">Número de escolas: ' . $alocacao->total_escolas . '</td>
                <td style="text-align: center;">Número de alunos:  ' . $alocacao->total_alunos . '</td>
                <td style="text-align: center;">Número de profissionais:  ' . $alocacao->total_profissionais . '</td>
            </tr>
        </table>
        <br><br>';

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $sql = "SELECT s.ordem_servico,
                       s.funcao,
                       s.cuidador, 
                       s.escola,
                       s.aluno,
                       s.curso,
                       GROUP_CONCAT(CONCAT(s.min_semana, s.max_semana, ', ', s.horario_inicio, ' às ', s.horario_termino) ORDER BY s.min_semana, s.max_semana, s.horario_inicio, s.horario_termino SEPARATOR ';<br>') AS horario,
                       CONCAT(s.data_inicio, ' a ', s.data_termino) AS periodo,
                       s.modulo,
                       s.codigo,
                       s.municipio
                FROM (SELECT REPLACE(a2.ordem_servico, '/{$get['ano']}', '') AS ordem_servico,
                             CONCAT_WS(' - ', a2.codigo, a2.escola) AS escola,
                             GROUP_CONCAT(DISTINCT(c.aluno) ORDER BY c.aluno SEPARATOR '<br>') AS aluno,
                             c.curso,
                             CASE MIN(g.dia_semana) 
                                  WHEN '0' THEN 'Dom'
                                  WHEN '1' THEN '2&ordf;'
                                  WHEN '2' THEN '3&ordf;'
                                  WHEN '3' THEN '4&ordf;'
                                  WHEN '4' THEN '5&ordf;'
                                  WHEN '5' THEN '6&ordf;'
                                  WHEN '6' THEN 'Sáb'
                                  END AS min_semana,
                             CASE MAX(g.dia_semana) 
                                  WHEN MIN(dia_semana) THEN ''
                                  WHEN '0' THEN ' a Dom'
                                  WHEN '1' THEN ' a 2&ordf;'
                                  WHEN '2' THEN ' a 3&ordf;'
                                  WHEN '3' THEN ' a 4&ordf;'
                                  WHEN '4' THEN ' a 5&ordf;'
                                  WHEN '5' THEN ' a 6&ordf;'
                                  WHEN '6' THEN ' a Sáb'
                                  END AS max_semana,
                             TIME_FORMAT(MIN(g.horario_inicio_mes{$idMes}), '%H:%i') AS horario_inicio,
                             TIME_FORMAT(MAX(g.horario_termino_mes{$idMes}), '%H:%i') AS horario_termino,
                             a.cuidador,
                             g.funcao,
                             DATE_FORMAT(c.data_inicio, '%d/%b') AS data_inicio,
                             DATE_FORMAT(c.data_termino, '%d/%b') AS data_termino,
                             c.modulo,
                             a2.codigo,
                             a2.municipio
                      FROM ei_alocacao b
                      INNER JOIN ei_alocacao_escolas a2 ON b.id = a2.id_alocacao
                      INNER JOIN ei_alocados a ON a2.id = a.id_alocacao_escola
                      LEFT JOIN ei_alocados_horarios g ON g.id_alocado = a.id
                      LEFT JOIN ei_matriculados_turmas c2 ON c2.id_alocado_horario = g.id
                      LEFT JOIN ei_matriculados c ON c.id = c2.id_matriculado AND c.id_alocacao_escola = a2.id
                      WHERE b.id = '{$alocacao->id}'
                      GROUP BY a2.escola, a.cuidador, g.cargo, g.funcao, c.curso, g.horario_inicio_mes{$idMes}, g.horario_termino_mes{$idMes}
                      ORDER BY IF(CHAR_LENGTH(a2.codigo) > 0, a2.codigo, CAST(a2.escola AS DECIMAL)) ASC,
                      a2.municipio ASC, a2.escola ASC, COALESCE(g.funcao, 'zzz') ASC, a.cuidador, a2.ordem_servico ASC, a.cuidador ASC, c.aluno ASC) s
                GROUP BY s.ordem_servico, s.escola, s.aluno
                ORDER BY IF(CHAR_LENGTH(s.codigo) > 0, s.codigo, CAST(s.escola AS DECIMAL)) ASC, s.municipio ASC, s.escola ASC, COALESCE(s.funcao, 'zzz') ASC, s.ordem_servico ASC, s.aluno ASC";

        $data = $this->db->query($sql)->result_array();

        $table = [['O.S.', 'Função', 'Profissional', 'Escola', 'Aluno(s)', 'Curso', 'Horário', 'Período', 'Módulo']];
        foreach ($data as $row) {
            unset($row['codigo'], $row['municipio']);
            $table[] = $row;
        }
        $this->load->library('table');

        $html = $cabecalho . $this->table->generate($table);

        $this->m_pdf->pdf->writeHTML($html);

        $this->m_pdf->pdf->Output("EI - Mapa Carregamento de OS.pdf", 'D');
    }


    public function pdfMapaCarregamentoOS()
    {
        $get = $this->input->get('busca');
        $idMes = intval($get['mes']) - ($get['semestre'] > 1 ? 7 : 0);

        $empresa = $this->session->userdata('empresa');
        $this->load->library('m_pdf');

        $stylesheet = 'table { font-size: 12px; } ';
        $stylesheet .= 'table tr th, table tr td { border: 1px solid #fff; } ';
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);

        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $empresa))->row();

        $nomeOS = '';


        $this->db->select('a.id, d.id_escola, f.id_aluno');
        $this->db->join('ei_contratos b', 'b.id = a.id_contrato');
        $this->db->join('ei_diretorias c', 'c.id = b.id_cliente');
        $this->db->join('ei_ordem_servico_escolas d', 'd.id_ordem_servico = a.id', 'left');
        $this->db->join('ei_escolas e', 'e.id = d.id_escola AND e.id_diretoria = c.id', 'left');
        $this->db->join('ei_ordem_servico_alunos f', 'f.id_ordem_servico_escola = d.id', 'left');
        $this->db->where('c.id_empresa', $empresa);
        if ($get['diretoria']) {
            $this->db->where('c.id', $get['diretoria']);
        }
        if ($get['contrato']) {
            $this->db->where('b.id', $get['contrato']);
        }
        if ($get['ano_semestre']) {
            $this->db->where("CONCAT(a.ano, '/', a.semestre) = '{$get['ano_semestre']}'", null, false);
        }
        if ($get['ordem_servico']) {
            $this->db->where('a.id', $get['ordem_servico']);
            $nomeOS = ' - ' . $get['ordem_servico'];
        }
        if ($get['municipio']) {
            $this->db->where('e.municipio', $get['municipio']);
        }
        if ($get['escola']) {
            $this->db->where('e.id', $get['escola']);
        }
        $rowsOS = $this->db->get('ei_ordem_servico a')->result();


        $ordensServico = implode(', ', array_unique(array_column($rowsOS, 'id') + [0]));
        $totalEscolas = count(array_unique(array_column($rowsOS, 'id_escola')));
        $totalAlunos = count(array_unique(array_column($rowsOS, 'id_aluno')));


        $cabecalho = '<table width="100%">
            <thead>
            <tr>
                <td>
                    <img src="' . base_url('imagens/usuarios/' . $usuario->foto) . '" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="vertical-align: top;" width="100%">
                    <p>
                        <img src="' . base_url('imagens/usuarios/' . $usuario->foto_descricao) . '" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" style="padding-bottom: 12px;  text-align: center; border-top: 5px solid #ddd; border-bottom: 2px solid #ddd; padding:5px;">
                    <h1 style="font-weight: bold;">MAPA ESCOLAS X ALUNOS</h1>
                </td>
            </tr>
                        </tbody>
        </table>
        <table width="100%">
            <tr>
                <td style="text-align: center;">Número de escolas: ' . $totalEscolas . '</td>
                <td style="text-align: center;">Número de alunos:  ' . $totalAlunos . '</td>
            </tr>
        </table>
        <br><br>';

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $sql = "SELECT s.ordem_servico,
                       s.funcao,
                       s.escola,
                       s.aluno,
                       s.curso,
                       s.data_inicio,
                       s.data_termino,
                       GROUP_CONCAT(CONCAT(s.min_semana, s.max_semana, ', ', s.horario_inicio, ' às ', s.horario_termino) ORDER BY s.min_semana, s.max_semana, s.horario_inicio, s.horario_termino SEPARATOR ';<br>') AS horario,
                       s.modulo,
                       s.codigo,
                       s.municipio
                FROM (SELECT a.nome AS ordem_servico,
                             CONCAT_WS(' - ', c.codigo, c.nome) AS escola,
                             e.nome AS aluno,
                             g.nome AS curso,
                             k.nome AS funcao,
                             CASE MIN(i.dia_semana) 
                                  WHEN '0' THEN 'Dom'
                                  WHEN '1' THEN '2&ordf;'
                                  WHEN '2' THEN '3&ordf;'
                                  WHEN '3' THEN '4&ordf;'
                                  WHEN '4' THEN '5&ordf;'
                                  WHEN '5' THEN '6&ordf;'
                                  WHEN '6' THEN 'Sáb'
                                  END AS min_semana,
                             CASE MAX(i.dia_semana) 
                                  WHEN MIN(i.dia_semana) THEN ''
                                  WHEN '0' THEN ' a Dom'
                                  WHEN '1' THEN ' a 2&ordf;'
                                  WHEN '2' THEN ' a 3&ordf;'
                                  WHEN '3' THEN ' a 4&ordf;'
                                  WHEN '4' THEN ' a 5&ordf;'
                                  WHEN '5' THEN ' a 6&ordf;'
                                  WHEN '6' THEN ' a Sáb'
                                  END AS max_semana,
                             TIME_FORMAT(MIN(i.horario_inicio_mes{$idMes}), '%H:%i') AS horario_inicio,
                             TIME_FORMAT(MAX(i.horario_termino_mes{$idMes}), '%H:%i') AS horario_termino,
                             DATE_FORMAT(MIN(d.data_inicio), '%d/%m/%Y') AS data_inicio,
                             DATE_FORMAT(MAX(d.data_termino), '%d/%m/%Y') AS data_termino,
                             d.modulo,
                             c.codigo,
                             c.municipio
                      FROM ei_ordem_servico a
                      INNER JOIN ei_ordem_servico_escolas b ON b.id_ordem_servico = a.id
                      INNER JOIN ei_escolas c ON c.id = b.id_escola
                      INNER JOIN ei_ordem_servico_alunos d ON d.id_ordem_servico_escola = b.id
                      INNER JOIN ei_alunos e ON e.id = d.id_aluno
                      LEFT JOIN ei_alunos_cursos f ON f.id = d.id_aluno_curso AND f.id_aluno = e.id
                      LEFT JOIN ei_cursos g ON g.id = f.id_curso
                      LEFT JOIN ei_ordem_servico_turmas j ON j.id_os_aluno = d.id
                      LEFT JOIN ei_ordem_servico_horarios i ON i.id = j.id_os_horario
                      LEFT JOIN ei_ordem_servico_profissionais h ON h.id = i.id_os_profissional AND h.id_ordem_servico_escola = b.id
                      LEFT JOIN empresa_funcoes k ON k.id = i.id_funcao
                      WHERE a.id IN ({$ordensServico})
                      GROUP BY a.id, b.id_escola, e.id, k.id, i.horario_inicio_mes{$idMes}, i.horario_termino_mes{$idMes}
                      ORDER BY IF(CHAR_LENGTH(c.codigo) > 0, c.codigo, CAST(c.nome AS DECIMAL)) ASC, c.municipio ASC, c.nome ASC, COALESCE(funcao, 'zzz') ASC, 
                      a.nome ASC, e.nome ASC) s
                GROUP BY s.ordem_servico, s.escola, s.aluno
                ORDER BY IF(CHAR_LENGTH(s.codigo) > 0, s.codigo, CAST(s.escola AS DECIMAL)) ASC, s.municipio ASC, s.escola ASC, COALESCE(s.funcao, 'zzz') ASC, s.ordem_servico ASC, s.aluno ASC";

        $data = $this->db->query($sql)->result_array();

        $table = [['O.S.', 'Funcao', 'Escola', 'Aluno(s)', 'Curso', 'Data início', 'Data término', 'Horário', 'Módulo']];
        foreach ($data as $row) {
            unset($row['codigo'], $row['municipio']);
            $table[] = $row;
        }
        $this->load->library('table');

        $html = $cabecalho . $this->table->generate($table);

        $this->m_pdf->pdf->writeHTML($html);

        $this->m_pdf->pdf->Output("EI - Mapa Escolas X Alunos{$nomeOS}.pdf", 'D');
    }


    public function pagamentoPrestadores()
    {
        $data = $this->ajaxPagamentoPrestadores(false);

        $this->load->view('ei/pagamento_prestadores', $data);
    }


    public function ajaxPagamentoPrestadores($isPdf = false)
    {
        $where = array(
            'depto' => $this->input->get_post('depto'),
            'diretoria' => $this->input->get_post('diretoria'),
            'supervisor' => $this->input->get_post('supervisor'),
            'ano' => $this->input->get_post('ano'),
            'semestre' => $this->input->get_post('semestre'),
            'mes' => $this->input->get_post('mes')
        );

        $this->load->library('Calendar');

        $data = array(
            'query_string' => http_build_query($where),
            'nomeMes' => $this->calendar->get_month_name($where['mes']),
            'ano' => $where['ano'],
            'is_pdf' => $isPdf
        );

        $idMes = $where['mes'] - ($where['semestre'] > 1 ? 6 : 0);


        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();


        $this->db->select('c.cuidador, d2.funcao, d.cnpj, b.escola');
        $this->db->select(["DATE_FORMAT(e.data_liberacao_pagto_mes{$idMes}, '%d/%m/%Y') AS data_liberacao_pagto"], false);
        $this->db->select(["TIME_FORMAT(IFNULL(c2.total_horas_faturadas_mes{$idMes}, SEC_TO_TIME(TIME_TO_SEC(d2.horas_mensais_custo) + IFNULL(TIME_TO_SEC(c2.horas_descontadas_mes{$idMes}), 0))), '%H:%i') AS total_horas"], false);
        $this->db->select(["FORMAT(IFNULL(c2.valor_total_mes{$idMes}, d2.valor_hora_operacional * ((IFNULL(TIME_TO_SEC(d2.horas_mensais_custo), 0) + IFNULL(TIME_TO_SEC(c2.horas_descontadas_mes{$idMes}), 0)) / 3600)), 2, 'de_DE') AS valor_total"], false);
        $this->db->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id');
        $this->db->join('ei_alocados c', 'c.id_alocacao_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_cuidador');
        $this->db->join('ei_alocados_horarios d2', 'd2.id_alocado = c.id');
        $this->db->join('ei_alocados_totalizacao c2', 'c2.id_alocado = c.id AND c2.periodo = d2.periodo', 'left');
        $this->db->join('ei_pagamento_prestador e', 'e.id_alocacao = a.id AND e.id_cuidador = c.id_cuidador', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.depto', $this->input->get_post('depto'));
        $this->db->where('a.id_diretoria', $this->input->get_post('diretoria'));
        $this->db->where('a.id_supervisor', $this->input->get_post('supervisor'));
        $this->db->where('a.ano', $this->input->get_post('ano'));
        $this->db->where('a.semestre', $this->input->get_post('semestre'));
        $this->db->group_by(['c.id_cuidador', 'b.id_escola']);
        $this->db->order_by('c.cuidador', 'asc');
        $this->db->order_by('b.escola', 'asc');
        $data['rows'] = $this->db->get('ei_alocacao a')->result();


        return $data;
    }


    public function unidadeVisitada($isPdf = false)
    {
        $empresa = $this->session->userdata('empresa');
        $this->db->select('foto, foto_descricao');
        $data['empresa'] = $this->db->get_where('usuarios', ['id' => $empresa])->row();


        $id = $this->input->get('id_mapa_unidade');

        $this->db->select('a.*, b.ano', false);
        $this->db->join('ei_alocacao b', 'b.id = a.id_alocacao');
        $this->db->where('a.id', $id);
        $mapaUnidade = $this->db->get('ei_mapa_unidades a')->row();

        $mes = $this->input->get('mes');
        $ano = $this->input->get('ano');
        if (empty($ano)) {
            $ano = $mapaUnidade->ano;
        }


        $sql = "SELECT DATE_FORMAT(a.data_visita, '%d/%m/%Y') AS data_visita, a.escola, 
                       a.supervisor_visitante, 
                       a.prestadores_servicos_tratados, 
                       (CASE a.motivo_visita
                             WHEN 1 THEN 'Visita de rotina'
                             WHEN 2 THEN 'Visita programada'
                             WHEN 3 THEN 'Solicitação da unidade'
                             WHEN 4 THEN 'Solicitação de materiais'
                             WHEN 5 THEN 'Processo seletivo'
                             WHEN 6 THEN 'Ocorrência com aluno'
                             WHEN 7 THEN 'Ocorrência com funcionário'
                             WHEN 8 THEN 'Ocorrência na escola'
                             END) AS motivo_visita,                             
                       FORMAT(a.gastos_materiais, 2, 'de_DE') AS gastos_materiais, 
                       a.sumario_visita, 
                       a.observacoes
                FROM ei_mapa_visitacao a
                INNER JOIN ei_mapa_unidades b ON b.id = a.id_mapa_unidade
                INNER JOIN ei_alocacao c ON c.id = b.id_alocacao
                WHERE c.id_empresa = '{$empresa}'
                      AND (b.id = '{$mapaUnidade->id}'
                      OR (c.ano = '{$ano}' AND b.escola = '{$mapaUnidade->id_escola}' AND b.escola = '{$mapaUnidade->escola}'))
                ORDER BY a.data_visita ASC, a.escola ASC, a.supervisor_visitante ASC";

        $data['visitas'] = $this->db->query($sql)->result();


        $data['id'] = $id;
        $data['mes'] = $mes;
        $data['ano'] = $ano;
        $data['mes_ano'] = implode('/', array_filter([$mes, $ano]));

        $data['query_string'] = http_build_query(['id_mapa_unidade' => $id, 'ano' => $ano, 'mes' => $mes]);
        $data['is_pdf'] = $isPdf === true;

        if ($data['is_pdf']) {
            return $this->load->view('ei/relatorio_visitas', $data, true);
        }

        $this->load->view('ei/relatorio_visitas', $data);
    }


    public function pdfUnidadeVisitada()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= 'table.unidades_visitadas {  border: 1px solid #333; margin-bottom: 0px; } ';
        $stylesheet .= 'table.unidades_visitadas thead tr th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #333;  } ';
        $stylesheet .= 'table.unidades_visitadas tbody tr td { font-size: 11px; padding: 4px; vertical-align: top; border: 1px solid #333;  } ';


        $this->m_pdf->pdf->setTopMargin(45);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->unidadeVisitada(true));


        $mes = $this->input->get('mes');
        $ano = $this->input->get('ano');

        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Relatório de Visitas - ' . implode('/', array_filter([$mes, $ano]));

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }


    public function ajaxSaveMedicao()
    {
        $data = $this->input->post();

        $totalEscolas = $data['total_escolas'];
        $totalAlunos = $data['total_alunos'];

        unset($data['total_escolas'], $data['total_alunos']);

        $this->db->trans_start();

        foreach ($data as &$row) {
            $row['total_escolas'] = $totalEscolas;
            $row['total_alunos'] = $totalAlunos;
            $row['receita_projetada'] = str_replace(['.', ','], ['', '.'], $row['receita_projetada']);
            $row['receita_efetuada'] = str_replace(['.', ','], ['', '.'], $row['receita_efetuada']);
            $row['pagamentos_efetuados'] = str_replace(['.', ','], ['', '.'], $row['pagamentos_efetuados']);
            $row['resultado'] = str_replace(['.', ','], ['', '.'], $row['resultado']);
            $row['resultado_percentual'] = str_replace(',', '.', $row['resultado_percentual']);

            if ($row['id']) {
                $this->db->update('ei_faturamento_consolidado', $row, ['id' => $row['id']]);
            } else {
                $this->db->insert('ei_faturamento_consolidado', $row);
            }
        }

        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(['status' => $status !== false]);
    }


    public function pdfPagamentoPrestadores()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#pagamento_prestadores thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#pagamento_prestadores { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#pagamento_prestadores thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#pagamento_prestadores tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';


        $data = $this->pagamentoPrestadores(true);


        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->load->view('ei/pagamento_prestadores', $data, true));


        $data = $this->input->get();


        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Pagamentos Consolidados de Prestadores de Serviços - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];


        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }


    public function faturamentoConsolidado()
    {
        $data = $this->ajaxFaturamentoConsolidado(false);

        $this->load->view('ei/faturamento_consolidado', $data);
    }


    public function ajaxFaturamentoConsolidado($isPdf = false)
    {
        $where = array(
            'depto' => $this->input->get_post('depto'),
            'diretoria' => $this->input->get_post('diretoria'),
            'supervisor' => $this->input->get_post('supervisor'),
            'ano' => $this->input->get_post('ano'),
            'semestre' => $this->input->get_post('semestre'),
            'mes' => $this->input->get_post('mes')
        );

        $this->load->library('Calendar');

        $data = array(
            'query_string' => http_build_query($where),
            'nomeMes' => $this->calendar->get_month_name($where['mes']),
            'ano' => $where['ano'],
            'is_pdf' => $isPdf
        );

        $idMes = $where['mes'] - ($where['semestre'] > 1 ? 6 : 0);


        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();


        $this->db->select('c.cuidador, d2.funcao, b.municipio');
        $this->db->select(["CONCAT_WS(' - ', b.codigo, b.escola) AS escola"], false);
        $this->db->select(["DATE_FORMAT(e.data_aprovacao_mes{$idMes}, '%d/%m/%Y') AS data_aprovacao"], false);
        $this->db->select(["ROUND(IFNULL(c2.total_dias_mes{$idMes}, SUM(d2.total_semanas_mes{$idMes} - IFNULL(d2.desconto_mes{$idMes}, 0)))) AS total_dias"], false);
        $this->db->select(["TIME_FORMAT(IFNULL(c2.total_horas_mes{$idMes}, SEC_TO_TIME(SUM(TIME_TO_SEC(d2.total_mes{$idMes})))), '%H:%i') AS total_horas"], false);
        $this->db->join('ei_alocacao_escolas b', 'b.id_alocacao = a.id');
        $this->db->join('ei_alocados c', 'c.id_alocacao_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_cuidador');
        $this->db->join('ei_alocados_horarios d2', 'd2.id_alocado = c.id');
        $this->db->join('ei_alocados_totalizacao c2', 'c2.id_alocado = c.id AND c2.periodo = d2.periodo', 'left');
        $this->db->join('ei_faturamento e', 'e.id_alocacao = a.id AND e.id_escola = b.id_escola AND e.cargo = d2.cargo AND e.funcao = d2.funcao', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.depto', $this->input->get_post('depto'));
        $this->db->where('a.id_diretoria', $this->input->get_post('diretoria'));
        $this->db->where('a.id_supervisor', $this->input->get_post('supervisor'));
        $this->db->where('a.ano', $this->input->get_post('ano'));
        $this->db->where('a.semestre', $this->input->get_post('semestre'));
        $this->db->group_by(['b.id_escola', 'c.id_cuidador', 'd2.cargo', 'd2.funcao', 'c.id', 'd2.periodo']);
        $this->db->order_by('IF(CHAR_LENGTH(b.codigo) > 0, b.codigo, CAST(b.escola AS DECIMAL)) ASC', null, false);
        $this->db->order_by('c.cuidador', 'asc');
        $this->db->order_by('d2.funcao', 'asc');
        $data['rows'] = $this->db->get('ei_alocacao a')->result();


        return $data;
    }


    public function pdfFaturamentoConsolidado()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#faturmaneto_consolidado thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '#faturmaneto_consolidado { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#faturmaneto_consolidado thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#faturmaneto_consolidado tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';


        $data = $this->faturamentoConsolidado(true);


        $this->m_pdf->pdf->setTopMargin(38);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->load->view('ei/faturamento_consolidado', $data, true));


        $data = $this->input->get();


        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Faturamentos Consolidados - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];


        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

}
