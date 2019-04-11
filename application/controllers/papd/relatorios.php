<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $this->medicao_mensal();
    }


    public function atividades_deficiencias($pdf = false)
    {
        $data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');

        $this->db->select('nome');
        $this->db->where('id_instituicao', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $data['deficiencias'] = $this->db->get('papd_hipotese_diagnostica')->result();

        $this->db->select("nome, FORMAT(valor, 2, 'de_DE') AS valor", false);
        $this->db->where('id_instituicao', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $data['atividades'] = $this->db->get('papd_atividades')->result();

        $data['is_pdf'] = $pdf;

        if ($pdf) {
            $data['is_valor'] = $this->input->get('valor');
            return $this->load->view('papd/relatorio_atividades_deficiencias', $data, true);
        }

        $data['is_valor'] = '1';

        $this->load->view('papd/relatorio_atividades_deficiencias', $data);
    }


    public function pdfConsolidado_mif_zarit()
    {
        $anoInicial = $this->input->get('ano_inicial');
        $anoFinal = $this->input->get('ano_final');

        $data = array(
            'foto' => 'imagens/usuarios/' . $this->session->userdata('foto'),
            'foto_descricao' => 'imagens/usuarios/' . $this->session->userdata('foto_descricao'),
            'ano1' => $anoInicial,
            'ano2' => $anoInicial + 1,
            'ano3' => $anoInicial + 2,
            'ano4' => $anoInicial + 3,
            'ano5' => $anoFinal,
            'mif' => array(),
            'zarit' => array()
        );

        $this->db->select('p.nome AS paciente');
        for ($i = $anoInicial; $i <= $anoFinal; $i++) {
            $this->db->select("ROUND(AVG(CASE YEAR(m.data_avaliacao) WHEN {$i} THEN m.mif END)) AS mif_{$i}", false);
            $this->db->select("(CASE ROUND(AVG(CASE YEAR(z.data_avaliacao) WHEN {$i} THEN IF(z.zarit > 21, 3, IF(z.zarit > 14, 2, IF(z.zarit >= 0, 1, NULL))) END)) WHEN 1 THEN 'Leve' WHEN 2 THEN 'Moderada' WHEN 3 THEN 'Grave' END) AS zarit_{$i}", false);
            $data['mif'][] = 'mif_' . $i;
            $data['zarit'][] = 'zarit_' . $i;
        }
        $this->db->join('papd_mif m', 'm.id_paciente = p.id', 'left');
        $this->db->join('papd_zarit z', 'z.id_paciente = p.id', 'left');
        $this->db->where('p.id_empresa', $this->session->userdata('empresa'));
        $this->db->group_by('p.id');
        $this->db->order_by('p.nome', 'asc');
        $data['rows'] = $this->db->get('papd_pacientes p')->result();

        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: bold; } ';
        $stylesheet .= '#table thead tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#table tbody td { font-size: 10px; padding: 5px; } ';
        $stylesheet .= '#table_consolidado_mif_zarit thead th, #table2 thead th { font-size: 13px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table_consolidado_mif_zarit tbody td, #table2 tbody td { font-size: 12px; padding: 3px 5px; vertical-align: top; } ';


        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->load->view('papd/pdfConsolidado_mif_zarit', $data, true));
        $this->m_pdf->pdf->Output("PAPD-Consolidado MIF-ZARIT_{$anoInicial}-{$anoFinal}.pdf", 'D');
    }


    public function pdfAtividades_deficiencias()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: bold; } ';
        $stylesheet .= '#table thead tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#table tbody td { font-size: 10px; padding: 5px; } ';
        $stylesheet .= '#table1 thead th, #table2 thead th { font-size: 13px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table1 tbody td, #table2 tbody td { font-size: 12px; padding: 3px 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->atividades_deficiencias(true));
        $this->m_pdf->pdf->Output('PAPD-Atividades_deficiências.pdf', 'D');
    }


    public function atendimentos_realizados($id = '', $pdf = false)
    {
        $data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');

        if (empty($id)) {
            $id = $this->uri->rsegment(3, 0);
        }

        $get = $this->input->get();

        $this->db->select('a.nome, b.tipo AS deficiencia, c.nome AS hipotese_diagnostica', false);
        $this->db->select("DATE_FORMAT(MIN(d.data_atendimento), '%d/%m/%Y') AS data_inicio", false);
        $this->db->select("DATE_FORMAT(MAX(d.data_atendimento), '%d/%m/%Y') AS data_termino", false);
        $this->db->join('deficiencias b', 'b.id = a.id_deficiencia', 'left');
        $this->db->join('papd_hipotese_diagnostica c', 'c.id = a.id_hipotese_diagnostica', 'left');
        $this->db->join('papd_atendimentos d', 'd.id_paciente = a.id', 'left');
        $this->db->where('a.id', $id);
        if ($pdf) {
            if (!empty($get['data_inicio'])) {
                $this->db->where('d.data_atendimento >=', date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $get['data_inicio'] . '00:00:00'))));
            }
            if (!empty($get['data_termino'])) {
                $this->db->where('d.data_atendimento >=', date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $get['data_termino'] . '23:59:59'))));
            }
        }
        $data['paciente'] = $this->db->get('papd_pacientes a')->row();

        if ($pdf) {
            $this->db->select('c.id AS id_atividade, c.nome AS nome_atividade, d.nome AS nome_profissional');
            $this->db->select("DATE_FORMAT(a.data_atendimento, '%d/%m/%Y') AS data_atendimento", false);
            $this->db->select("DATE_FORMAT(a.data_atendimento, '%H:%i') AS hora_atendimento", false);
        } else {
            $this->db->select('c.id, c.nome, a.data_atendimento');
        }
        $this->db->join('papd_pacientes b', 'b.id = a.id_paciente');
        $this->db->join('papd_atividades c', 'c.id = a.id_atividade AND c.id_instituicao = b.id_instituicao');
        $this->db->join('usuarios d', 'd.id = a.id_usuario');
        $this->db->where('a.id_paciente', $id);
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(7, 8, 9, 10))) {
                $this->db->where('a.id_paciente', $this->session->userdata('id'));
            }
        }
        if (!empty($data['paciente']->data_inicio)) {
            $this->db->where('a.data_atendimento >=', date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $data['paciente']->data_inicio . '00:00:00'))));
        }
        if (!empty($data['paciente']->data_termino)) {
            $this->db->where('a.data_atendimento <=', date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $data['paciente']->data_termino . '23:59:59'))));
        }
        if ($pdf) {
            if (!empty($get['atividade'])) {
                $this->db->where('a.id_atividade', $get['atividade']);
            }
            if (!empty($get['order'])) {
                $this->db->_protect_identifiers = false;
                $this->db->order_by("{$get['order'][0]} {$get['order'][1]}");
                $this->db->_protect_identifiers = true;
            }
        } else {
            $this->db->group_by('c.id');
            $this->db->order_by('c.nome ASC, a.data_atendimento DESC');
        }
        $rows = $this->db->get('papd_atendimentos a')->result();

        $data['is_pdf'] = $pdf;

        if ($pdf) {
            $data['atendimentos'] = $rows;

            return $this->load->view('papd/pdfAtendimentos_realizados', $data, true);
        } else {
            $data['atividades'] = array('' => 'Todas');
            foreach ($rows as $row) {
                $data['atividades'][$row->id] = $row->nome;
            }

            $this->load->view('papd/atendimentos_realizados', $data);
        }
    }


    public function ajax_atendimentos_realizados()
    {
        $post = $this->input->post();

        $subquery = "SELECT a.id, 
                             b.nome AS paciente,
                             c.nome AS atividade,
                             d.nome AS profissional,
                             DATE_FORMAT(a.data_atendimento, '%d/%m/%Y') AS data_atendimento,
                             DATE_FORMAT(a.data_atendimento, '%H:%i') AS hora_atendimento
                  FROM papd_atendimentos a
                  INNER JOIN papd_pacientes b ON
                            b.id = a.id_paciente
                  INNER JOIN papd_atividades c ON
                            c.id = a.id_atividade AND 
                            c.id_instituicao = b.id_instituicao
                  INNER JOIN usuarios d ON
                            d.id = a.id_usuario
                  WHERE a.id_paciente = {$post['id_paciente']}";
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(7, 8, 9, 10))) {
                $subquery .= " AND a.id_usuario = {$this->session->userdata('id')}";
            }
        }
        if ($post['data_inicio']) {
            $subquery .= " AND a.data_atendimento >= '" . date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $post['data_inicio'] . '00:00:00'))) . "'";
        }
        if ($post['data_termino']) {
            $subquery .= " AND a.data_atendimento <= '" . date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $post['data_termino'] . '23:59:59'))) . "'";
        }
        if ($post['atividade']) {
            $subquery .= " AND a.id_atividade = '{$post['atividade']}'";
        }

        $sql = "SELECT s.id, 
                       s.paciente,
                       s.atividade,
                       s.profissional,
                       s.data_atendimento,
                       s.hora_atendimento
                FROM ($subquery) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.paciente', 's.atividade', 's.profissional', 's.data_atendimento', 's.valor');
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
        foreach ($list as $relatorio) {
            $row = array();
            $row[] = $relatorio->data_atendimento;
            $row[] = $relatorio->hora_atendimento;
            $row[] = $relatorio->atividade;
            $row[] = $relatorio->profissional;

            $data[] = $row;
        }

        $row = "SELECT IFNULL(DATE_FORMAT(MIN(s.data_atendimento), '%d%m%Y'), '{$post['data_inicio']}') AS data_inicio, 
                       IFNULL(DATE_FORMAT(MAX(s.data_atendimento), '%d%m%Y'), '{$post['data_termino']}') AS data_termino
                FROM ($subquery) s";

        $medicao = $this->db->query($row)->row_array();

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "medicao" => $medicao,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function frequencia($id, $pdf = false)
    {
        if (empty($id)) {
            redirect(site_url('apontamento_pacientes'));
        }

        $data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');


        $sql = "SELECT 'Associação dos Amigos Metroviários dos Excepcionais' AS instituicao_nome, 
                       '64.917.818/0001-56' AS instituicao_cnpj, 
                       a.nome, 
                       CASE a.sexo 
                            WHEN 'M' THEN 'Masculino' 
                            WHEN 'F' THEN 'Feminino' END AS sexo, 
                       DATE_FORMAT(a.data_nascimento, '%d/%m/%Y') AS data_nascimento, 
                       a.cpf, 
                       a.cadastro_municipal, 
                       f.tipo AS deficiencia, 
                       e.nome AS hd, 
                       a.nome_responsavel_1, 
                       a.telefone_fixo_1, 
                       a.telefone_celular_1,
                       a.nome_responsavel_2, 
                       a.telefone_fixo_2, 
                       a.telefone_celular_2, 
                       CONCAT_WS(', ', a.logradouro, a.numero) AS endereco, 
                       a.complemento, 
                       a.bairro, 
                       CASE WHEN a.cidade_nome IS NOT NULL
                            THEN a.cidade_nome 
                            ELSE c.municipio END AS cidade, 
                       d.uf AS estado, 
                       a.cep, 
                       DATE_FORMAT(a.data_ingresso, '%m') AS mes_ingresso, 
                       DATE_FORMAT(a.data_ingresso, '%Y') AS ano_ingresso 
                FROM papd_pacientes a 
                INNER JOIN usuarios b ON 
                           b.id = a.id_instituicao 
                LEFT JOIN municipios c ON 
                          c.cod_mun = a.cidade
                LEFT JOIN estados d ON 
                          d.cod_uf = a.estado 
                LEFT JOIN papd_hipotese_diagnostica e ON 
                          e.id = a.id_hipotese_diagnostica
                LEFT JOIN deficiencias f ON 
                          f.id = a.id_deficiencia
                WHERE a.id = {$id}";
        $row = $this->db->query($sql)->row();

        $mes = $this->input->get('mes');
        if ($mes) {
            $row->mes_ingresso = $mes;
        }
        if (isset($row->mes_ingresso)) {
            $this->load->library('Calendar');
            $row->nome_mes_ingresso = $this->calendar->get_month_name($row->mes_ingresso);
        }
        $ano = $this->input->get('ano');
        if ($ano) {
            $row->ano_ingresso = $ano;
        }

        $data['paciente'] = $row;
        $data['meses'] = array(
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );
        $data['is_pdf'] = $pdf;

        if ($pdf) {
            return $this->load->view('papd/pdfFrequencia', $data, true);
        } else {
            $this->load->view('papd/frequencia', $data);
        }
    }


    public function medicao_mensal($pdf = false)
    {
        $empresa = $this->session->userdata('empresa');

        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $empresa))->row();

        $data['foto'] = 'imagens/usuarios/' . $usuario->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $usuario->foto_descricao;

        $sql = "SELECT a.cod_uf, 
                       a.uf 
                FROM estados a 
                INNER JOIN papd_pacientes b ON 
                           b.estado = a.cod_uf 
                WHERE b.id_instituicao = {$empresa}";
        $estados = $this->db->query($sql)->result();
        $data['estado'] = array('' => 'Todos');
        foreach ($estados as $estado) {
            $data['estado'][$estado->cod_uf] = $estado->uf;
        }

        $sql2 = "SELECT a.cod_mun, 
                        a.municipio 
                 FROM municipios a 
                 INNER JOIN papd_pacientes b ON 
                            b.cidade = a.cod_mun 
                 WHERE b.id_instituicao = {$empresa}";
        $cidades = $this->db->query($sql2)->result();
        $data['cidade'] = array('' => 'Todas');
        foreach ($cidades as $cidade) {
            $data['cidade'][$cidade->cod_mun] = $cidade->municipio;
        }
        $this->db->distinct('cidade_nome');
        $this->db->where('id_instituicao', $empresa);
        $this->db->where('CHAR_LENGTH(cidade_nome) >', 0);
        $cidades_nome = $this->db->get('papd_pacientes')->result();
        foreach ($cidades_nome as $cidade_nome) {
            $data['cidade'][$cidade_nome->cidade_nome] = $cidade_nome->cidade_nome;
        }

        $this->db->distinct('bairro');
        $this->db->where('id_instituicao', $empresa);
        $this->db->where('CHAR_LENGTH(bairro) > ', 0);
        $bairros = $this->db->get('papd_pacientes')->result();
        $data['bairro'] = array('' => 'Todos');
        foreach ($bairros as $bairro) {
            $data['bairro'][$bairro->bairro] = $bairro->bairro;
        }

        $this->db->select('b.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(8, 9, 10))) {
                $this->db->where('a.id_usuario', $this->session->userdata('id'));
            }
        }
        $this->db->group_by('b.id');
        $this->db->order_by('b.nome', 'asc');
        $profissionais = $this->db->get('papd_atendimentos a')->result();
        $data['profissional'] = array('' => 'Todos');
        foreach ($profissionais as $profissional) {
            $data['profissional'][$profissional->id] = $profissional->nome;
        }

        $sql3 = "SELECT id, nome
                 FROM papd_pacientes 
                 WHERE id_instituicao = {$empresa}
                 ORDER BY nome ASC";
        $grupoPacientes = $this->db->query($sql3)->result();
        $data['paciente'] = ['' => 'Todos'] + array_column($grupoPacientes, 'nome', 'id');

//        $sql3 = "SELECT status AS id,
//                        CASE status
//                        WHEN 'A' THEN 'Ativo'
//                        WHEN 'I' THEN 'Inativo'
//                        WHEN 'X' THEN 'Afastado'
//                        WHEN 'E' THEN 'Em fila de espera' END AS nome
//                 FROM papd_pacientes 
//                 WHERE id_instituicao = {$empresa}";
//        $grupo_status = $this->db->query($sql3)->result();
//        $data['status'] = array('' => 'Todos');
//        foreach ($grupo_status as $status) {
//            $data['status'][$status->id] = $status->nome;
//        }

        $sql4 = "SELECT id, 
                        nome 
                 FROM papd_hipotese_diagnostica 
                 WHERE id_instituicao = {$empresa}";
        $deficiencias = $this->db->query($sql4)->result();
        $data['deficiencia'] = array('' => 'Sem filtro');
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencia'][$deficiencia->id] = $deficiencia->nome;
        }

        $this->db->distinct('contrato');
        $this->db->where('id_instituicao', $empresa);
        $this->db->where('CHAR_LENGTH(contrato) > ', 0);
        $contratos = $this->db->get('papd_pacientes')->result();
        $data['contrato'] = array('' => 'Todos');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->contrato] = $contrato->contrato;
        }
        $data['is_pdf'] = $pdf;

        if ($pdf) {
            $get = $this->input->get();

            $subquery = "SELECT b.nome AS paciente,
                             c.nome AS atividade,
                             d.nome AS profissional,
                             a.data_atendimento,
                             CASE WHEN a.data_atendimento IS NOT NULL
                                  THEN DATE_FORMAT(a.data_atendimento, '%d/%m/%Y &nbsp; %H:%i')
                                  ELSE NULL END AS data_hora,
                             c.valor
                  FROM papd_atendimentos a
                  INNER JOIN papd_pacientes b ON
                            b.id = a.id_paciente
                  INNER JOIN papd_atividades c ON
                            c.id = a.id_atividade AND 
                            c.id_instituicao = b.id_instituicao
                  INNER JOIN usuarios d ON
                            d.id = a.id_usuario
                  WHERE 1";
            if ($this->session->userdata('tipo') == 'funcionario') {
                if (!in_array($this->session->userdata('nivel'), array(8, 9, 10))) {
                    $subquery .= " AND a.id_usuario = {$this->session->userdata('id')}";
                }
            }
            if (isset($get['data_inicio'])) {
                $subquery .= " AND a.data_atendimento >= '" . date("Y-m-d", strtotime(str_replace('/', '-', $get['data_inicio']))) . " 00:00:00'";
            }
            if (isset($get['data_termino'])) {
                $subquery .= " AND a.data_atendimento <= '" . date("Y-m-d", strtotime(str_replace('/', '-', $get['data_termino']))) . " 23:59:59'";
            }
//            if (isset($get['status'])) {
//                $subquery .= " AND b.status = '{$get['status']}'";
//            }
            if (isset($get['paciente'])) {
                $subquery .= " AND b.id = '{$get['paciente']}'";
            }
            if (isset($get['estado'])) {
                $subquery .= " AND b.estado = {$get['estado']}";
            }
            if (isset($get['cidade'])) {
                $subquery .= " AND (b.cidade = '{$get['cidade']}' OR b.cidade_nome = '{$get['cidade']}')";
            }
            if (isset($get['bairro'])) {
                $subquery .= " AND b.bairro = '{$get['bairro']}'";
            }
            if (isset($get['profissional'])) {
                $subquery .= " AND d.id = '{$get['profissional']}'";
            }
            if (isset($get['deficiencia'])) {
                $subquery .= " AND b.id_hipotese_diagnostica = {$get['deficiencia']}";
            }
            if (isset($get['contrato'])) {
                $subquery .= " AND b.contrato = '{$get['contrato']}'";
            }
            if (isset($get['order'])) {
                $subquery .= " ORDER BY {$get['order'][0]} {$get['order'][1]}";
            }

            $sql = "SELECT s.paciente,
                       s.atividade,
                       s.profissional,
                       s.data_hora,
                       s.valor
                FROM ($subquery) s";

            $data['rows'] = $this->db->query($sql)->result();

            $row = "SELECT CASE WHEN CHAR_LENGTH('{$get['data_inicio']}') > 0 
                                THEN '{$get['data_inicio']}' 
                                ELSE DATE_FORMAT(MIN(s.data_atendimento), '%d/%m/%Y') END AS data_inicio, 
                           CASE WHEN CHAR_LENGTH('{$get['data_termino']}') > 0
                                THEN '{$get['data_termino']}' 
                                ELSE DATE_FORMAT(MAX(s.data_atendimento), '%d/%m/%Y') END AS data_termino, 
                           FORMAT(SUM(s.valor),2,'de_DE') AS total
                    FROM ($subquery) s";
            $data['medicao'] = $this->db->query($row)->row();

            return $this->load->view('papd/pdfMedicao_mensal', $data, true);
        } else {
            $this->load->view('papd/medicao_mensal', $data);
        }
    }


    public function ajax_medicao_mensal()
    {
        $post = $this->input->post();

        $subquery = "SELECT b.nome AS paciente,
                            c.nome AS atividade,
                            d.nome AS profissional,
                            a.data_atendimento,
                            CASE WHEN a.data_atendimento IS NOT NULL
                                 THEN DATE_FORMAT(a.data_atendimento, '%d/%m/%Y &nbsp; %H:%i')
                                 ELSE NULL END AS data_hora,
                            c.valor
                  FROM papd_atendimentos a
                  INNER JOIN papd_pacientes b ON
                            b.id = a.id_paciente
                  INNER JOIN papd_atividades c ON
                            c.id = a.id_atividade AND 
                            c.id_instituicao = b.id_instituicao
                  INNER JOIN usuarios d ON
                            d.id = a.id_usuario
                  WHERE 1";
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(8, 9, 10))) {
                $subquery .= " AND a.id_usuario = {$this->session->userdata('id')}";
            }
        }
        if ($post['data_inicio']) {
            $subquery .= " AND a.data_atendimento >= '" . date("Y-m-d", strtotime(str_replace('/', '-', $post['data_inicio']))) . " 00:00:00'";
        }
        if ($post['data_termino']) {
            $subquery .= " AND a.data_atendimento <= '" . date("Y-m-d", strtotime(str_replace('/', '-', $post['data_termino']))) . " 23:59:59'";
        }
        if ($post['paciente']) {
            $subquery .= " AND b.id = '{$post['paciente']}'";
        }
        if ($post['estado']) {
            $subquery .= " AND b.estado = {$post['estado']}";
        }
        if ($post['cidade']) {
            $subquery .= " AND (b.cidade = '{$post['cidade']}' OR b.cidade_nome = '{$post['cidade']}')";
        }
        /* if ($post['bairro']) {
          $subquery .= " AND b.bairro = '{$post['bairro']}'";
          } */
        if ($post['profissional']) {
            $subquery .= " AND d.id = '{$post['profissional']}'";
        }
        if ($post['deficiencia']) {
            $subquery .= " AND b.id_hipotese_diagnostica = {$post['deficiencia']}";
        }
        if ($post['contrato']) {
            $subquery .= " AND b.contrato = '{$post['contrato']}'";
        }

        $sql = "SELECT s.paciente,
                       s.atividade,
                       s.profissional,
                       s.data_atendimento,
                       s.valor,
                       s.data_hora
                FROM ($subquery) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.paciente', 's.atividade', 's.profissional', "DATE_FORMAT(s.data_hora, '%d/%m/%Y H;i:s')", "FORMAT(s.valor, 2, 'de_DE')");
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 0) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 0) {
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
        foreach ($list as $relatorio) {
            $row = array();
            $row[] = $relatorio->paciente;
            $row[] = $relatorio->atividade;
            $row[] = $relatorio->profissional;
            $row[] = $relatorio->data_hora;
            $row[] = number_format($relatorio->valor, 2, ',', '.');

            $data[] = $row;
        }

        $row = "SELECT CASE WHEN CHAR_LENGTH('{$post['data_inicio']}') > 0 
                            THEN '{$post['data_inicio']}' 
                            ELSE DATE_FORMAT(MIN(s.data_atendimento), '%d/%m/%Y') END AS data_inicio, 
                       CASE WHEN CHAR_LENGTH('{$post['data_termino']}') > 0
                            THEN '{$post['data_termino']}' 
                            ELSE DATE_FORMAT(MAX(s.data_atendimento), '%d/%m/%Y') END AS data_termino, 
                       FORMAT(COUNT(s.data_atendimento),0,'de_DE') AS qtde_atendimentos,
                       FORMAT(SUM(s.valor),2,'de_DE') AS total
                FROM ($subquery) s";

        $medicao = $this->db->query($row)->row_array();

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "medicao" => $medicao,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function pdfMedicao_mensal()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#medicao thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#medicao thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#medicao tbody td { font-size: 10px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 10px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->medicao_mensal(true));

        $this->load->library('Calendar');
        $get = $this->input->get();
        $mes = date('m', strtotime(str_replace('/', '-', $get['data_inicio'])));
        $ano = date('Y', strtotime(str_replace('/', '-', $get['data_inicio'])));
        $mes_ano = $this->calendar->get_month_name($mes) . '-' . $ano;
        $this->m_pdf->pdf->Output('PAPD-Medição-' . $mes_ano . '.pdf', 'D');
    }


    public function medicao_consolidada($pdf = false)
    {
        $empresa = $this->session->userdata('empresa');

        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $empresa))->row();

        $data['foto'] = 'imagens/usuarios/' . $usuario->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $usuario->foto_descricao;

        $sql = "SELECT a.cod_uf, 
                       a.uf 
                FROM estados a 
                INNER JOIN papd_pacientes b ON 
                           b.estado = a.cod_uf 
                WHERE b.id_instituicao = {$empresa}";
        $estados = $this->db->query($sql)->result();
        $data['estado'] = array('' => 'Todos');
        foreach ($estados as $estado) {
            $data['estado'][$estado->cod_uf] = $estado->uf;
        }

        $sql2 = "SELECT a.cod_mun, 
                        a.municipio 
                 FROM municipios a 
                 INNER JOIN papd_pacientes b ON 
                            b.cidade = a.cod_mun 
                 WHERE b.id_instituicao = {$empresa}";
        $cidades = $this->db->query($sql2)->result();
        $data['cidade'] = array('' => 'Todas');
        foreach ($cidades as $cidade) {
            $data['cidade'][$cidade->cod_mun] = $cidade->municipio;
        }
        $this->db->distinct('cidade_nome');
        $this->db->where('id_instituicao', $empresa);
        $this->db->where('CHAR_LENGTH(cidade_nome) >', 0);
        $cidades_nome = $this->db->get('papd_pacientes')->result();
        foreach ($cidades_nome as $cidade_nome) {
            $data['cidade'][$cidade_nome->cidade_nome] = $cidade_nome->cidade_nome;
        }

        $this->db->distinct('bairro');
        $this->db->where('id_instituicao', $empresa);
        $this->db->where('CHAR_LENGTH(bairro) > ', 0);
        $bairros = $this->db->get('papd_pacientes')->result();
        $data['bairro'] = array('' => 'Todos');
        foreach ($bairros as $bairro) {
            $data['bairro'][$bairro->bairro] = $bairro->bairro;
        }

        $this->db->select('b.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(8, 9, 10))) {
                $this->db->where('a.id_usuario', $this->session->userdata('id'));
            }
        }
        $this->db->group_by('b.id');
        $this->db->order_by('b.nome', 'asc');
        $profissionais = $this->db->get('papd_atendimentos a')->result();
        $data['profissional'] = array('' => 'Todos');
        foreach ($profissionais as $profissional) {
            $data['profissional'][$profissional->id] = $profissional->nome;
        }

        $sql3 = "SELECT status AS id,
                        CASE status
                        WHEN 'A' THEN 'Ativo'
                        WHEN 'I' THEN 'Inativo'
                        WHEN 'M' THEN 'Em monitoramento'
                        WHEN 'X' THEN 'Afastado'
                        WHEN 'E' THEN 'Em fila de espera' END AS nome
                 FROM papd_pacientes 
                 WHERE id_instituicao = {$empresa}";
        $grupo_status = $this->db->query($sql3)->result();
        $data['status'] = array('' => 'Todos');
        foreach ($grupo_status as $status) {
            $data['status'][$status->id] = $status->nome;
        }

        $sql4 = "SELECT id, 
                        nome 
                 FROM papd_hipotese_diagnostica 
                 WHERE id_instituicao = {$empresa}";
        $deficiencias = $this->db->query($sql4)->result();
        $data['deficiencia'] = array('' => 'Sem filtro');
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencia'][$deficiencia->id] = $deficiencia->nome;
        }

        $this->db->distinct('contrato');
        $this->db->where('id_instituicao', $empresa);
        $this->db->where('CHAR_LENGTH(contrato) > ', 0);
        $contratos = $this->db->get('papd_pacientes')->result();
        $data['contrato'] = array('' => 'Todos');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->contrato] = $contrato->contrato;
        }
        $data['is_pdf'] = $pdf;

        if ($pdf) {
            $get = $this->input->get();

            $subquery = "SELECT d.nome AS profissional,
                             COUNT(a.id) AS qtde_atendimentos,
                             SUM(c.valor) AS valor,
                             MIN(a.data_atendimento) AS data_inicio,
                             MAX(a.data_atendimento) AS data_termino
                  FROM papd_atendimentos a
                  INNER JOIN papd_pacientes b ON
                            b.id = a.id_paciente
                  INNER JOIN papd_atividades c ON
                            c.id = a.id_atividade AND 
                            c.id_instituicao = b.id_instituicao
                  INNER JOIN usuarios d ON
                            d.id = a.id_usuario
                  WHERE 1";
            if ($this->session->userdata('tipo') == 'funcionario') {
                if (!in_array($this->session->userdata('nivel'), array(8, 9, 10))) {
                    $subquery .= " AND a.id_usuario = {$this->session->userdata('id')}";
                }
            }
            if (isset($get['data_inicio'])) {
                $subquery .= " AND a.data_atendimento >= '" . date("Y-m-d", strtotime(str_replace('/', '-', $get['data_inicio']))) . " 00:00:00'";
            }
            if (isset($get['data_termino'])) {
                $subquery .= " AND a.data_atendimento <= '" . date("Y-m-d", strtotime(str_replace('/', '-', $get['data_termino']))) . " 23:59:59'";
            }
            if (isset($get['status'])) {
                $subquery .= " AND b.status = '{$get['status']}'";
            }
            if (isset($get['estado'])) {
                $subquery .= " AND b.estado = {$get['estado']}";
            }
            if (isset($get['cidade'])) {
                $subquery .= " AND (b.cidade = '{$get['cidade']}' OR b.cidade_nome = '{$get['cidade']}')";
            }
            if (isset($get['bairro'])) {
                $subquery .= " AND b.bairro = '{$get['bairro']}'";
            }
            if (isset($get['profissional'])) {
                $subquery .= " AND d.id = '{$get['profissional']}'";
            }
            if (isset($get['deficiencia'])) {
                $subquery .= " AND b.id_hipotese_diagnostica = {$get['deficiencia']}";
            }
            if (isset($get['contrato'])) {
                $subquery .= " AND b.contrato = '{$get['contrato']}'";
            }
            $subquery .= ' GROUP BY a.id_usuario';
            if (isset($get['order'])) {
                $subquery .= " ORDER BY {$get['order'][0]} {$get['order'][1]}";
            }

            $sql = "SELECT s.profissional,
                       s.qtde_atendimentos,
                       s.valor
                FROM ($subquery) s";

            $data['rows'] = $this->db->query($sql)->result();

            $row = "SELECT CASE WHEN CHAR_LENGTH('{$get['data_inicio']}') > 0 
                                THEN '{$get['data_inicio']}' 
                                ELSE DATE_FORMAT(MIN(s.data_inicio), '%d/%m/%Y') END AS data_inicio, 
                           CASE WHEN CHAR_LENGTH('{$get['data_termino']}') > 0
                                THEN '{$get['data_termino']}' 
                                ELSE DATE_FORMAT(MAX(s.data_termino), '%d/%m/%Y') END AS data_termino, 
                           FORMAT(SUM(s.valor),2,'de_DE') AS total
                    FROM ($subquery) s";
            $data['medicao'] = $this->db->query($row)->row();

            return $this->load->view('papd/pdfMedicao_consolidada', $data, true);
        } else {
            $this->load->view('papd/medicao_consolidada', $data);
        }
    }


    public function medicao_anual($pdf = false)
    {
        $empresa = $this->session->userdata('empresa');

        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $empresa))->row();

        $data['foto'] = 'imagens/usuarios/' . $usuario->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $usuario->foto_descricao;
        $data['is_pdf'] = $pdf;


        if ($pdf) {
            $get = $this->input->get();
            $data['ano'] = strlen($get['ano']) > 0 ? $get['ano'] : date('Y');

            $rowPacientesCadastrados = array_pad(['<span style="font-weight: bold;">Número de pacientes cadastrados</span>'], 15, null);
            $rowPacientesInativos = array_pad(['<span style="font-weight: bold;">Número de pacientes inativos</span>'], 15, null);
            $rowPacientesMonitoramento = array_pad(['<span style="font-weight: bold;">Número de pacientes em monitoramento</span>'], 15, null);

            $this->db->where('id_empresa', $empresa);
            $this->db->where('ano', $get['ano']);
            $this->db->order_by('mes', 'asc');
            $sqlMedicao = $this->db->get('papd_medicao')->result();

            foreach ($sqlMedicao as $medicao) {
                $rowPacientesCadastrados[intval($medicao->mes)] = $medicao->total_pacientes_cadastrados;
                $rowPacientesInativos[intval($medicao->mes)] = $medicao->total_pacientes_inativos;
                $rowPacientesMonitoramento[intval($medicao->mes)] = $medicao->total_pacientes_monitorados;
            }

            $prefixoMeses = array('atividade', 'total_jan', 'total_fev', 'total_mar', 'total_abr', 'total_mai', 'total_jun', 'total_jul', 'total_ago', 'total_set', 'total_out', 'total_nov', 'total_dez', 'total', 'total_percentual');
            $rowPacientesCadastrados = (object)array_combine($prefixoMeses, $rowPacientesCadastrados);
            $rowPacientesInativos = (object)array_combine($prefixoMeses, $rowPacientesInativos);
            $rowPacientesMonitoramento = (object)array_combine($prefixoMeses, $rowPacientesMonitoramento);


            $sqlTotal = "SELECT s.id, s.paciente, s.atendente,
                            IF(SUM(s.total_jan) > 0, 1, 0) AS total_jan,
                            IF(SUM(s.total_fev) > 0, 1, 0) AS total_fev,
                            IF(SUM(s.total_mar) > 0, 1, 0) AS total_mar,
                            IF(SUM(s.total_abr) > 0, 1, 0) AS total_abr,
                            IF(SUM(s.total_mai) > 0, 1, 0) AS total_mai,
                            IF(SUM(s.total_jun) > 0, 1, 0) AS total_jun,
                            IF(SUM(s.total_jul) > 0, 1, 0) AS total_jul,
                            IF(SUM(s.total_ago) > 0, 1, 0) AS total_ago,
                            IF(SUM(s.total_set) > 0, 1, 0) AS total_set,
                            IF(SUM(s.total_out) > 0, 1, 0) AS total_out,
                            IF(SUM(s.total_nov) > 0, 1, 0) AS total_nov,
                            IF(SUM(s.total_dez) > 0, 1, 0) AS total_dez
                     FROM (SELECT a.id, b.nome AS paciente, d.nome AS atendente,
                                  IF(MONTH(a.data_atendimento) = 1, 1, 0) AS total_jan,
                                  IF(MONTH(a.data_atendimento) = 2, 1, 0) AS total_fev,
                                  IF(MONTH(a.data_atendimento) = 3, 1, 0) AS total_mar,
                                  IF(MONTH(a.data_atendimento) = 4, 1, 0) AS total_abr,
                                  IF(MONTH(a.data_atendimento) = 5, 1, 0) AS total_mai,
                                  IF(MONTH(a.data_atendimento) = 6, 1, 0) AS total_jun,
                                  IF(MONTH(a.data_atendimento) = 7, 1, 0) AS total_jul,
                                  IF(MONTH(a.data_atendimento) = 8, 1, 0) AS total_ago,
                                  IF(MONTH(a.data_atendimento) = 9, 1, 0) AS total_set,
                                  IF(MONTH(a.data_atendimento) = 10, 1, 0) AS total_out,
                                  IF(MONTH(a.data_atendimento) = 11, 1, 0) AS total_nov,
                                  IF(MONTH(a.data_atendimento) = 12, 1, 0) AS total_dez
                           FROM papd_atendimentos a
                           INNER JOIN papd_pacientes b ON b.id = a.id_paciente
                           INNER JOIN papd_atividades c ON c.id = a.id_atividade
                           INNER JOIN usuarios d ON d.id = a.id_usuario
                           WHERE YEAR(a.data_atendimento) = '{$get['ano']}') s";


            $sqlPacientes = "SELECT t.paciente AS atividade, 
                                SUM(t.total_jan) AS total_jan,
                                SUM(t.total_fev) AS total_fev,
                                SUM(t.total_mar) AS total_mar,
                                SUM(t.total_abr) AS total_abr,
                                SUM(t.total_mai) AS total_mai,
                                SUM(t.total_jun) AS total_jun,
                                SUM(t.total_jul) AS total_jul,
                                SUM(t.total_ago) AS total_ago,
                                SUM(t.total_set) AS total_set,
                                SUM(t.total_out) AS total_out,
                                SUM(t.total_nov) AS total_nov,
                                SUM(t.total_dez) AS total_dez,
                                COUNT(t.paciente) AS total,
                                '--' AS total_percentual
                         FROM ({$sqlTotal} 
                               GROUP BY s.paciente) t";
            $rowPacientes = $this->db->query($sqlPacientes)->row();
            $rowPacientes->atividade = '<span style="font-weight: bold;">Número de pacientes atendidos</span>';


            $sqlAtendentes = "SELECT t.atendente AS atividade, 
                                 SUM(t.total_jan) AS total_jan,
                                 SUM(t.total_fev) AS total_fev,
                                 SUM(t.total_mar) AS total_mar,
                                 SUM(t.total_abr) AS total_abr,
                                 SUM(t.total_mai) AS total_mai,
                                 SUM(t.total_jun) AS total_jun,
                                 SUM(t.total_jul) AS total_jul,
                                 SUM(t.total_ago) AS total_ago,
                                 SUM(t.total_set) AS total_set,
                                 SUM(t.total_out) AS total_out,
                                 SUM(t.total_nov) AS total_nov,
                                 SUM(t.total_dez) AS total_dez,
                                 COUNT(t.atendente) AS total,
                                 '--' AS total_percentual
                              FROM ({$sqlTotal}
                                    GROUP BY s.atendente) t";
            $rowAtendentes = $this->db->query($sqlAtendentes)->row();
            $rowAtendentes->atividade = '<span style="font-weight: bold;">Número de atendentes</span>';


            $subquery = "SELECT c.nome AS atividade,
                            IF(MONTH(a.data_atendimento) = 1, COUNT(a.id_atividade), 0) AS total_jan,
                            IF(MONTH(a.data_atendimento) = 2, COUNT(a.id_atividade), 0) AS total_fev,
                            IF(MONTH(a.data_atendimento) = 3, COUNT(a.id_atividade), 0) AS total_mar,
                            IF(MONTH(a.data_atendimento) = 4, COUNT(a.id_atividade), 0) AS total_abr,
                            IF(MONTH(a.data_atendimento) = 5, COUNT(a.id_atividade), 0) AS total_mai,
                            IF(MONTH(a.data_atendimento) = 6, COUNT(a.id_atividade), 0) AS total_jun,
                            IF(MONTH(a.data_atendimento) = 7, COUNT(a.id_atividade), 0) AS total_jul,
                            IF(MONTH(a.data_atendimento) = 8, COUNT(a.id_atividade), 0) AS total_ago,
                            IF(MONTH(a.data_atendimento) = 9, COUNT(a.id_atividade), 0) AS total_set,
                            IF(MONTH(a.data_atendimento) = 10, COUNT(a.id_atividade), 0) AS total_out,
                            IF(MONTH(a.data_atendimento) = 11, COUNT(a.id_atividade), 0) AS total_nov,
                            IF(MONTH(a.data_atendimento) = 12, COUNT(a.id_atividade), 0) AS total_dez,
                            COUNT(a.id_atividade) AS total
                     FROM papd_atendimentos a
                     INNER JOIN papd_pacientes b ON
                            b.id = a.id_paciente
                  INNER JOIN papd_atividades c ON
                            c.id = a.id_atividade AND 
                            c.id_instituicao = b.id_instituicao
                  INNER JOIN usuarios d ON
                            d.id = a.id_usuario
                  WHERE 1";
            if ($this->session->userdata('tipo') == 'funcionario') {
                if (!in_array($this->session->userdata('nivel'), array(8, 9, 10))) {
                    $subquery .= " AND a.id_usuario = {$this->session->userdata('id')}";
                }
            }
            if ($get['ano']) {
                $subquery .= " AND YEAR(a.data_atendimento) = '{$get['ano']}'";
            } else {
                $subquery .= " AND YEAR(a.data_atendimento) = YEAR(NOW())";
            }
            $subquery .= ' GROUP BY c.id, MONTH(a.data_atendimento)';
            $sql = "SELECT s.atividade,
                           SUM(s.total_jan) AS total_jan,
                           SUM(s.total_fev) AS total_fev,
                           SUM(s.total_mar) AS total_mar,
                           SUM(s.total_abr) AS total_abr,
                           SUM(s.total_mai) AS total_mai,
                           SUM(s.total_jun) AS total_jun,
                           SUM(s.total_jul) AS total_jul,
                           SUM(s.total_ago) AS total_ago,
                           SUM(s.total_set) AS total_set,
                           SUM(s.total_out) AS total_out,
                           SUM(s.total_nov) AS total_nov,
                           SUM(s.total_dez) AS total_dez,
                           SUM(s.total) AS total, ";

            $rowAtendimentos = $this->db->query($sql . "100 AS total_percentual FROM ($subquery) s")->row();
            $rowAtendimentos->atividade = '<span style="font-weight: bold;">Número de atendimentos</span>';

            $sql .= "ROUND(SUM(s.total) / " . max($rowAtendimentos->total, 1) . " * 100, 1) AS total_percentual 
                      FROM ({$subquery}) s 
                      GROUP BY s.atividade";
            $data['rows'] = $this->db->query($sql)->result();
            array_unshift($data['rows'], $rowPacientesCadastrados, $rowPacientesInativos, $rowPacientesMonitoramento, $rowPacientes, $rowAtendentes, $rowAtendimentos);

            return $this->load->view('papd/pdfMedicao_anual', $data, true);
        }

        $this->load->view('papd/medicao_anual', $data);
    }


    public function ajax_medicao_consolidada()
    {
        $post = $this->input->post();

        $subquery = "SELECT d.nome AS profissional,
                            COUNT(a.id) AS qtde_atendimentos,
                            SUM(c.valor) AS valor,
                            MIN(a.data_atendimento) AS data_inicio,
                            MAX(a.data_atendimento) AS data_termino
                  FROM papd_atendimentos a
                  INNER JOIN papd_pacientes b ON
                            b.id = a.id_paciente
                  INNER JOIN papd_atividades c ON
                            c.id = a.id_atividade AND 
                            c.id_instituicao = b.id_instituicao
                  INNER JOIN usuarios d ON
                            d.id = a.id_usuario
                  WHERE 1";
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(8, 9, 10))) {
                $subquery .= " AND a.id_usuario = {$this->session->userdata('id')}";
            }
        }
        if ($post['data_inicio']) {
            $subquery .= " AND a.data_atendimento >= '" . date("Y-m-d", strtotime(str_replace('/', '-', $post['data_inicio']))) . " 00:00:00'";
        }
        if ($post['data_termino']) {
            $subquery .= " AND a.data_atendimento <= '" . date("Y-m-d", strtotime(str_replace('/', '-', $post['data_termino']))) . " 23:59:59'";
        }
        if ($post['status']) {
            $subquery .= " AND b.status = '{$post['status']}'";
        }
        if ($post['estado']) {
            $subquery .= " AND b.estado = {$post['estado']}";
        }
        if ($post['cidade']) {
            $subquery .= " AND (b.cidade = '{$post['cidade']}' OR b.cidade_nome = '{$post['cidade']}')";
        }
        /* if ($post['bairro']) {
          $subquery .= " AND b.bairro = '{$post['bairro']}'";
          } */
        if ($post['profissional']) {
            $subquery .= " AND d.id = '{$post['profissional']}'";
        }
        if ($post['deficiencia']) {
            $subquery .= " AND b.id_hipotese_diagnostica = {$post['deficiencia']}";
        }
        if ($post['contrato']) {
            $subquery .= " AND b.contrato = '{$post['contrato']}'";
        }
        $subquery .= ' GROUP BY a.id_usuario';

        $sql = "SELECT s.profissional,
                       s.qtde_atendimentos,
                       s.valor
                FROM ($subquery) s";

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.profissional', 'qtde_atendimentos', "FORMAT(s.valor, 2, 'de_DE')");
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 0) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 0) {
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
        foreach ($list as $relatorio) {
            $row = array();
            $row[] = $relatorio->profissional;
            $row[] = $relatorio->qtde_atendimentos;
            $row[] = number_format($relatorio->valor, 2, ',', '.');

            $data[] = $row;
        }

        $row = "SELECT CASE WHEN CHAR_LENGTH('{$post['data_inicio']}') > 0 
                            THEN '{$post['data_inicio']}' 
                            ELSE DATE_FORMAT(MIN(s.data_inicio), '%d/%m/%Y') END AS data_inicio, 
                       CASE WHEN CHAR_LENGTH('{$post['data_termino']}') > 0
                            THEN '{$post['data_termino']}' 
                            ELSE DATE_FORMAT(MAX(s.data_termino), '%d/%m/%Y') END AS data_termino, 
                       FORMAT(SUM(s.qtde_atendimentos),0,'de_DE') AS qtde_atendimentos,
                       FORMAT(SUM(s.valor),2,'de_DE') AS total
                FROM ($subquery) s";

        $medicao = $this->db->query($row)->row_array();

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "medicao" => $medicao,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function ajax_medicao_anual()
    {
        $post = $this->input->post();
        $ano = strlen($post['ano']) > 0 ? $post['ano'] : date('Y');


        $sqlTotal = "SELECT s.id, s.paciente, s.atendente,
                            IF(SUM(s.total_jan) > 0, 1, 0) AS total_jan,
                            IF(SUM(s.total_fev) > 0, 1, 0) AS total_fev,
                            IF(SUM(s.total_mar) > 0, 1, 0) AS total_mar,
                            IF(SUM(s.total_abr) > 0, 1, 0) AS total_abr,
                            IF(SUM(s.total_mai) > 0, 1, 0) AS total_mai,
                            IF(SUM(s.total_jun) > 0, 1, 0) AS total_jun,
                            IF(SUM(s.total_jul) > 0, 1, 0) AS total_jul,
                            IF(SUM(s.total_ago) > 0, 1, 0) AS total_ago,
                            IF(SUM(s.total_set) > 0, 1, 0) AS total_set,
                            IF(SUM(s.total_out) > 0, 1, 0) AS total_out,
                            IF(SUM(s.total_nov) > 0, 1, 0) AS total_nov,
                            IF(SUM(s.total_dez) > 0, 1, 0) AS total_dez
                     FROM (SELECT a.id, b.nome AS paciente, d.nome AS atendente,
                                  IF(MONTH(a.data_atendimento) = 1, 1, 0) AS total_jan,
                                  IF(MONTH(a.data_atendimento) = 2, 1, 0) AS total_fev,
                                  IF(MONTH(a.data_atendimento) = 3, 1, 0) AS total_mar,
                                  IF(MONTH(a.data_atendimento) = 4, 1, 0) AS total_abr,
                                  IF(MONTH(a.data_atendimento) = 5, 1, 0) AS total_mai,
                                  IF(MONTH(a.data_atendimento) = 6, 1, 0) AS total_jun,
                                  IF(MONTH(a.data_atendimento) = 7, 1, 0) AS total_jul,
                                  IF(MONTH(a.data_atendimento) = 8, 1, 0) AS total_ago,
                                  IF(MONTH(a.data_atendimento) = 9, 1, 0) AS total_set,
                                  IF(MONTH(a.data_atendimento) = 10, 1, 0) AS total_out,
                                  IF(MONTH(a.data_atendimento) = 11, 1, 0) AS total_nov,
                                  IF(MONTH(a.data_atendimento) = 12, 1, 0) AS total_dez
                           FROM papd_atendimentos a
                           INNER JOIN papd_pacientes b ON b.id = a.id_paciente
                           INNER JOIN papd_atividades c ON c.id = a.id_atividade
                           INNER JOIN usuarios d ON d.id = a.id_usuario
                           WHERE YEAR(a.data_atendimento) = '{$ano}'";
        if ($post['search']['value']) {
            $sqlTotal .= " AND c.nome LIKE '%{$post['search']['value']}%'";
        }
        $sqlTotal .= ') s';

        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('ano', $ano);
        $this->db->order_by('mes', 'asc');
        $sqlMedicao = $this->db->get('papd_medicao')->result();

        $totalPacientesCadastrados = array_pad(['<span style="font-weight: bold;">Número de pacientes cadastrados</span>'], 15, null);
        $totalPacientesInativos = array_pad(['<span style="font-weight: bold;">Número de pacientes inativos</span>'], 15, null);
        $totalPacientesMonitorados = array_pad(['<span style="font-weight: bold;">Número de pacientes em monitoramento</span>'], 15, null);

        foreach ($sqlMedicao as $medicao) {
            $totalPacientesCadastrados[intval($medicao->mes)] = $medicao->total_pacientes_cadastrados;
            $totalPacientesInativos[intval($medicao->mes)] = $medicao->total_pacientes_inativos;
            $totalPacientesMonitorados[intval($medicao->mes)] = $medicao->total_pacientes_monitorados;
        }


        $sqlPacientes = "SELECT t.paciente, 
                                SUM(t.total_jan) AS total_jan,
                                SUM(t.total_fev) AS total_fev,
                                SUM(t.total_mar) AS total_mar,
                                SUM(t.total_abr) AS total_abr,
                                SUM(t.total_mai) AS total_mai,
                                SUM(t.total_jun) AS total_jun,
                                SUM(t.total_jul) AS total_jul,
                                SUM(t.total_ago) AS total_ago,
                                SUM(t.total_set) AS total_set,
                                SUM(t.total_out) AS total_out,
                                SUM(t.total_nov) AS total_nov,
                                SUM(t.total_dez) AS total_dez,
                                COUNT(t.paciente) AS total,
                                '--'
                         FROM ({$sqlTotal} 
                               GROUP BY s.paciente) t";
        $rowPacientes = $this->db->query($sqlPacientes)->row_array();
        $rowPacientes['paciente'] = '<span style="font-weight: bold;">Número de pacientes atendidos</span>';

        $data = array(
            $totalPacientesCadastrados,
            $totalPacientesInativos,
            $totalPacientesMonitorados
        );
        $data[] = array_values($rowPacientes);


        $sqlAtendentes = "SELECT t.atendente, 
                                 SUM(t.total_jan) AS total_jan,
                                 SUM(t.total_fev) AS total_fev,
                                 SUM(t.total_mar) AS total_mar,
                                 SUM(t.total_abr) AS total_abr,
                                 SUM(t.total_mai) AS total_mai,
                                 SUM(t.total_jun) AS total_jun,
                                 SUM(t.total_jul) AS total_jul,
                                 SUM(t.total_ago) AS total_ago,
                                 SUM(t.total_set) AS total_set,
                                 SUM(t.total_out) AS total_out,
                                 SUM(t.total_nov) AS total_nov,
                                 SUM(t.total_dez) AS total_dez,
                                 COUNT(t.atendente) AS total, 
                                 '--'
                              FROM ({$sqlTotal}
                                    GROUP BY s.atendente) t";
        $rowAtendentes = $this->db->query($sqlAtendentes)->row_array();
        $rowAtendentes['atendente'] = '<span style="font-weight: bold;">Número de atendentes</span>';
        $data[] = array_values($rowAtendentes);

        $subquery = "SELECT c.nome AS atividade,
                            IF(MONTH(a.data_atendimento) = 1, COUNT(a.id_atividade), 0) AS total_jan,
                            IF(MONTH(a.data_atendimento) = 2, COUNT(a.id_atividade), 0) AS total_fev,
                            IF(MONTH(a.data_atendimento) = 3, COUNT(a.id_atividade), 0) AS total_mar,
                            IF(MONTH(a.data_atendimento) = 4, COUNT(a.id_atividade), 0) AS total_abr,
                            IF(MONTH(a.data_atendimento) = 5, COUNT(a.id_atividade), 0) AS total_mai,
                            IF(MONTH(a.data_atendimento) = 6, COUNT(a.id_atividade), 0) AS total_jun,
                            IF(MONTH(a.data_atendimento) = 7, COUNT(a.id_atividade), 0) AS total_jul,
                            IF(MONTH(a.data_atendimento) = 8, COUNT(a.id_atividade), 0) AS total_ago,
                            IF(MONTH(a.data_atendimento) = 9, COUNT(a.id_atividade), 0) AS total_set,
                            IF(MONTH(a.data_atendimento) = 10, COUNT(a.id_atividade), 0) AS total_out,
                            IF(MONTH(a.data_atendimento) = 11, COUNT(a.id_atividade), 0) AS total_nov,
                            IF(MONTH(a.data_atendimento) = 12, COUNT(a.id_atividade), 0) AS total_dez,
                            COUNT(a.id_atividade) AS total
                     FROM papd_atendimentos a
                     INNER JOIN papd_pacientes b ON
                            b.id = a.id_paciente
                  INNER JOIN papd_atividades c ON
                            c.id = a.id_atividade AND 
                            c.id_instituicao = b.id_instituicao
                  INNER JOIN usuarios d ON
                            d.id = a.id_usuario
                  WHERE 1";
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(8, 9, 10))) {
                $subquery .= " AND a.id_usuario = {$this->session->userdata('id')}";
            }
        }
        if ($post['ano']) {
            $subquery .= " AND YEAR(a.data_atendimento) = '{$post['ano']}'";
        } else {
            $subquery .= " AND YEAR(a.data_atendimento) = YEAR(NOW())";
        }
//        if ($post['status']) {
//            $subquery .= " AND b.status = '{$post['status']}'";
//        }
//        if ($post['estado']) {
//            $subquery .= " AND b.estado = {$post['estado']}";
//        }
//        if ($post['cidade']) {
//            $subquery .= " AND (b.cidade = '{$post['cidade']}' OR b.cidade_nome = '{$post['cidade']}')";
//        }
//        if ($post['profissional']) {
//            $subquery .= " AND d.id = '{$post['profissional']}'";
//        }
//        if ($post['deficiencia']) {
//            $subquery .= " AND b.id_hipotese_diagnostica = {$post['deficiencia']}";
//        }
//        if ($post['contrato']) {
//            $subquery .= " AND b.contrato = '{$post['contrato']}'";
//        }
        $subquery .= ' GROUP BY c.id, MONTH(a.data_atendimento)';

        $sql = "SELECT s.atividade,
                       SUM(s.total_jan) AS total_jan,
                       SUM(s.total_fev) AS total_fev,
                       SUM(s.total_mar) AS total_mar,
                       SUM(s.total_abr) AS total_abr,
                       SUM(s.total_mai) AS total_mai,
                       SUM(s.total_jun) AS total_jun,
                       SUM(s.total_jul) AS total_jul,
                       SUM(s.total_ago) AS total_ago,
                       SUM(s.total_set) AS total_set,
                       SUM(s.total_out) AS total_out,
                       SUM(s.total_nov) AS total_nov,
                       SUM(s.total_dez) AS total_dez,
                       SUM(s.total) AS total,
                       100
                FROM ($subquery) s";

        $recordsTotal = $this->db->query($sql . ' GROUP BY s.atividade')->num_rows();

        if ($post['search']['value']) {
            $sql .= " WHERE s.atividade LIKE '%{$post['search']['value']}%'";
        }

        $listTotal = $this->db->query($sql)->row_array();
        $listTotal['atividade'] = '<strong>Número de atendimentos</strong>';
        $data[] = array_values($listTotal);

        $sql .= ' GROUP BY s.atividade';

        $recordsFiltered = $this->db->query($sql)->num_rows();
        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        foreach ($list as $relatorio) {
            $row = array();
            $row[] = $relatorio->atividade;
            $row[] = $relatorio->total_jan;
            $row[] = $relatorio->total_fev;
            $row[] = $relatorio->total_mar;
            $row[] = $relatorio->total_abr;
            $row[] = $relatorio->total_mai;
            $row[] = $relatorio->total_jun;
            $row[] = $relatorio->total_jul;
            $row[] = $relatorio->total_ago;
            $row[] = $relatorio->total_set;
            $row[] = $relatorio->total_out;
            $row[] = $relatorio->total_nov;
            $row[] = $relatorio->total_dez;
            $row[] = $relatorio->total;
            $row[] = str_replace('.', ',', round($relatorio->total / $listTotal['total'] * 100, 1));

            $data[] = $row;
        }

//        $row = "SELECT DATE_FORMAT(MIN(s.data_inicio), '%d/%m/%Y') AS data_inicio,
//                       DATE_FORMAT(MAX(s.data_termino), '%d/%m/%Y') AS data_termino,
//                       FORMAT(SUM(s.qtde_atendimentos),0,'de_DE') AS qtde_atendimentos,
//                       FORMAT(SUM(s.valor),2,'de_DE') AS total
//                FROM ($subquery) s
//                WHERE (YEAR(s.data_inicio) = '{$post['ano']}' AND
//                       YEAR(s.data_termino) = '{$post['ano']}') OR
//                      CHAR_LENGTH('{$post['ano']}') = 0";
//
//        $medicao = $this->db->query($row)->row_array();

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "ano" => strlen($post['ano']) > 0 ? $post['ano'] : date('Y'),
//            "medicao" => $medicao,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }


    public function calcularMedicaoAnual()
    {
        $empresa = $this->session->userdata('empresa');
        $ano = $this->input->post('ano');
        $mes = date('m');

        $this->db->select(["{$empresa} AS id_empresa, {$ano} AS ano, {$mes} AS mes"], false);
        $this->db->select('COUNT(id) AS total_pacientes_cadastrados', false);
        $this->db->select("COUNT(CASE status WHEN 'I' THEN id END) AS total_pacientes_inativos", false);
        $this->db->select("COUNT(CASE status WHEN 'M' THEN id END) AS total_pacientes_monitorados", false);
        $data = $this->db->get('papd_pacientes')->row();

        $where = array('id_empresa' => $empresa, 'ano' => $ano, 'mes' => $mes);
        $this->db->where($where);
        $temMedicao = $this->db->get('papd_medicao')->num_rows();

        if ($temMedicao) {
            $status = $this->db->update('papd_medicao', $data, $where);
        } else {
            $status = $this->db->insert('papd_medicao', $data);
        }

        echo json_encode(array("status" => $status !== false));
    }


    public function pdfMedicao_anual()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#medicao thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#medicao thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#medicao tbody td { font-size: 10px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 14px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 13px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->setTopMargin(70);
        $this->m_pdf->pdf->AddPage();
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->medicao_anual(true));

        $this->load->library('Calendar');
        $get = $this->input->get();
        $this->m_pdf->pdf->Output('PAPD-Medição Anual-' . $get['ano'] . '.pdf', 'D');
    }


    public function pdfMedicao_consolidada()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#medicao thead tr th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#medicao thead tr, #medicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#medicao tbody td { font-size: 10px; padding: 5px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 10px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->medicao_consolidada(true));

        $this->load->library('Calendar');
        $get = $this->input->get();
        $mes = date('m', strtotime(str_replace('/', '-', $get['data_inicio'])));
        $ano = date('Y', strtotime(str_replace('/', '-', $get['data_inicio'])));
        $mes_ano = $this->calendar->get_month_name($mes) . '-' . $ano;
        $this->m_pdf->pdf->Output('PAPD-Medição_consolidada-' . $mes_ano . '.pdf', 'D');
    }


    public function pdfAtendimentos_realizados()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#atendimentos thead th { font-size: 12px; padding: 4px 0px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#atendimentos thead tr, #atendimentos tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#atendimentos tbody tr td { font-size: 12px; padding: 4px; 0px; } ';
        $stylesheet .= '#atendimentos tbody tr.dados_paciente td { padding: 0px; 0px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->atendimentos_realizados($this->uri->rsegment(3), true));

        $this->db->select('nome');
        $this->db->where('id', $this->uri->rsegment(3));
        $row = $this->db->get('papd_pacientes')->row();

        $this->m_pdf->pdf->Output('PAPD_atendimentos-' . $row->nome . '.pdf', 'D');
    }


    public function pdfFrequencia()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#frequencia { margin-bottom: 10px; } ';
        $stylesheet .= '#frequencia thead th { font-size: 12px; padding: 4px 0px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#frequencia thead tr, #frequencia tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#frequencia tbody tr td { font-size: 12px; padding: 4px; 0px; } ';
        $stylesheet .= '#frequencia tbody tr.dados_paciente td { padding: 0px; 0px; vertical-align: top; } ';
        $stylesheet .= '#table { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->frequencia($this->uri->rsegment(3), true));

        $this->db->select('nome');
        $this->db->where('id', $this->uri->rsegment(3));
        $row = $this->db->get('papd_pacientes')->row();

        $this->m_pdf->pdf->Output('PAPD-' . $row->nome . '.pdf', 'D');
    }


    public function pdfFrequencia_coletiva()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#frequencia thead th { font-size: 12px; padding: 5px 0px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#frequencia thead tr, #frequencia tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#frequencia tbody tr td { font-size: 12px; padding: 5px; 0px; } ';
        $stylesheet .= '#frequencia tbody tr.dados_paciente td { padding: 1px; 0px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 5px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 5px; vertical-align: top; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);

        $get = $this->input->get();
        $sql = "SELECT id
                FROM papd_pacientes 
                WHERE id_instituicao = {$this->session->userdata('empresa')} AND 
                      status = 'A'";
        if ($get['estado']) {
            $sql .= " AND estado = {$get['estado']}";
        }
        if ($get['cidade']) {
            $sql .= " AND cidade = {$get['cidade']}";
        }
        if ($get['bairro']) {
            $sql .= " AND bairro = '{$get['bairro']}'";
        }
        if ($get['deficiencia']) {
            $sql .= " AND id_hipotese_diagnostica = {$get['deficiencia']}";
        }
        if ($get['contrato']) {
            $sql .= " AND contrato = '{$get['contrato']}'";
        }
        $rows = $this->db->query($sql)->result();
        foreach ($rows as $k => $row) {
            $this->m_pdf->pdf->writeHTML($this->frequencia($row->id, true));
            echo json_encode(array('msg' => 'Gerando relatório ' . ($k + 1) . ' de ' . count($rows)));
        }

        $this->m_pdf->pdf->Output('PAPD-Controle_de_Frequência.pdf', 'D');
    }


    public function pdfFrequencia_coletiva2()
    {
        $post = $this->input->post();
        $sql = "SELECT s.id,
                       s.paciente
                FROM (SELECT a.id,
                             a.nome AS paciente,
                             CASE a.status
                                  WHEN 'A' THEN 'Ativo'
                                  WHEN 'I' THEN 'Inativo'
                                  WHEN 'M' THEN 'Em monitoramento'
                                  WHEN 'X' THEN 'Afastado'
                                  WHEN 'E' THEN 'Em fila de espera' END AS status,
                             CONCAT_WS('/', CONCAT((CASE WHEN b.id > 0 THEN '' ELSE '_' END), b.tipo) , c.nome) AS deficiencia,
                             DATE_FORMAT(a.data_ingresso, '%d/%m/%Y') AS data_ingresso
                  FROM papd_pacientes a
                  LEFT JOIN deficiencias b ON
                            b.id = a.id_deficiencia
                  LEFT JOIN papd_hipotese_diagnostica c ON
                            c.id = a.id_hipotese_diagnostica
                  WHERE a.id_instituicao= {$this->session->userdata('empresa')}";
        if (!empty($post['estado'])) {
            $sql .= " AND a.estado = {$post['estado']}";
        }
        if (!empty($post['cidade'])) {
            $sql .= " AND (a.cidade = '{$post['cidade']}' OR a.cidade_nome = '{$post['cidade']}')";
        }
        if (!empty($post['bairro'])) {
            $sql .= " AND a.bairro = '{$post['bairro']}'";
        }
        if (!empty($post['deficiencia'])) {
            $sql .= " AND a.id_hipotese_diagnostica = {$post['deficiencia']}";
        }
        if (!empty($post['contrato'])) {
            $sql .= " AND a.contrato = '{$post['contrato']}'";
        }
        $sql .= ')s';
        $rows = $this->db->query($sql)->result();

        $data['rows'] = $rows;
        $data['max'] = count($rows);
        $data['pacote'] = uniqid();

        echo json_encode($data);
    }


    public function frequencia2()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#frequencia { margin-bottom: 10px; } ';
        $stylesheet .= '#frequencia thead th { font-size: 12px; padding: 4px 0px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#frequencia thead tr, #frequencia tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#frequencia tbody tr td { font-size: 12px; padding: 4px; 0px; } ';
        $stylesheet .= '#frequencia tbody tr.dados_paciente td { padding: 0px; 0px; vertical-align: top; } ';
        $stylesheet .= '#table { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $id = $this->input->get('id');
        $pacote = $this->input->get('pacote');

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->frequencia($id, true));

        $paciente = $this->input->get('paciente');
        $filename = 'PAPD-Controle_de_Frequencia-' . urldecode($paciente) . '.pdf';

        $this->m_pdf->pdf->Output('arquivos/temp/' . $filename, 'F');
        $filepath = is_file('arquivos/temp/' . $filename) ? 'arquivos/temp/' . $filename : '';

        $zip = new ZipArchive;
        if (is_file("arquivos/temp/$pacote.zip")) {
            $result = $zip->open("arquivos/temp/$pacote.zip");
        } else {
            $result = $zip->open("arquivos/temp/$pacote.zip", ZIPARCHIVE::CREATE);
        }
        if ($result === true) {
            $zip->addFile($filepath, $filename);
            $zip->close();
        }
        if (is_file($filepath)) {
            unlink($filepath);
        }

        echo json_encode(array('status' => true));
    }


    public function frequencia3()
    {
        $pacote = $this->uri->rsegment(3, '');
        $this->load->helper('download');
        $data = file_get_contents("arquivos/temp/$pacote.zip");
        unlink("arquivos/temp/$pacote.zip");

        force_download('arquivos/temp/PAPD-Controle_de_Frequencia.zip', $data);
    }


    private function calculoAnual()
    {
        $sql = "SELECT b.nome AS paciente, c.nome AS atividade,
                       IF(MONTH(a.data_atendimento) = 1, 1, 0) AS total_jan,
                       IF(MONTH(a.data_atendimento) = 2, 1, 0) AS total_fev,
                       IF(MONTH(a.data_atendimento) = 3, 1, 0) AS total_mar,
                       IF(MONTH(a.data_atendimento) = 4, 1, 0) AS total_abr,
                       IF(MONTH(a.data_atendimento) = 5, 1, 0) AS total_mai,
                       IF(MONTH(a.data_atendimento) = 6, 1, 0) AS total_jun,
                       IF(MONTH(a.data_atendimento) = 7, 1, 0) AS total_jul,
                       IF(MONTH(a.data_atendimento) = 8, 1, 0) AS total_ago,
                       IF(MONTH(a.data_atendimento) = 9, 1, 0) AS total_set,
                       IF(MONTH(a.data_atendimento) = 10, 1, 0) AS total_out,
                       IF(MONTH(a.data_atendimento) = 11, 1, 0) AS total_nov,
                       IF(MONTH(a.data_atendimento) = 12, 1, 0) AS total_dez
FROM papd_atendimentos a
INNER JOIN papd_pacientes b ON b.id = a.id_paciente
INNER JOIN papd_atividades c ON c.id = a.id_atividade
INNER JOIN usuarios d ON d.id = a.id_usuario
WHERE YEAR(a.data_atendimento) = 2018";
    }


}
