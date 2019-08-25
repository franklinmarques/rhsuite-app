<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal_consolidado extends MY_Controller
{

    public function index()
    {
        $data = $this->getFiltros();
        $this->load->view('requisicaoPessoal_consolidado', $data);
    }

    // -------------------------------------------------------------------------

    public function atualizarFiltro()
    {
        $filtros = $this->getFiltros();

        $data['funcao'] = form_dropdown('', $filtros['cargosFuncoes'], '');

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    public function getFiltros()
    {
        $idDepto = $this->input->post('id_depto');

        $sql = "SELECT a.id_depto, b.nome AS depto, 
                       a.id_funcao, c.nome AS cargo, d.nome AS funcao
                FROM requisicoes_pessoal a
                LEFT JOIN empresa_departamentos b ON b.id = a.id_depto 
                LEFT JOIN empresa_cargos c ON c.id = a.id_cargo 
                LEFT JOIN empresa_funcoes d ON d.id = a.id_funcao AND d.id_cargo = c.id 
                WHERE a.id_empresa = '{$this->session->userdata('empresa')}'";

        $sqlDeptos = "SELECT s.id_depto, s.depto FROM ({$sql}) s ORDER BY s.depto ASC";
        $sqlCargosFuncoes = "SELECT s.id_funcao, CONCAT_WS('/', s.cargo, s.funcao) AS cargo_funcao 
                             FROM ({$sql}) s 
                             WHERE (s.id_depto = '{$idDepto}' OR CHAR_LENGTH('{$idDepto}') = 0)
                             ORDER BY s.cargo ASC, s.funcao ASC";

        $deptos = $this->db->query($sqlDeptos)->result();
        $cargosFuncoes = $this->db->query($sqlCargosFuncoes)->result();

        $data = array(
            'deptos' => ['' => 'Todos'] + array_column($deptos, 'depto', 'id_depto'),
            'cargosFuncoes' => ['' => 'Todos'] + array_column($cargosFuncoes, 'cargo_funcao', 'id_funcao')
        );

        return $data;
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        parse_str($this->input->post('busca'), $busca);
        $ano = !empty($busca['ano']) ? $busca['ano'] : date('Y');

        $sql = "SELECT MONTHNAME(data_abertura) AS mes,
                       COUNT(data_abertura) AS aberto,
                       SUM(CASE WHEN data_abertura IS NOT NULL THEN numero_vagas END) AS vagas_aberto,
                       COUNT(data_fechamento) AS fechado,
                       SUM(CASE WHEN data_fechamento IS NOT NULL THEN numero_vagas END) AS vagas_fechado,
                       COUNT(data_suspensao) AS parcial,
                       SUM(CASE WHEN data_suspensao IS NOT NULL THEN numero_vagas END) AS vagas_parcial,
                       COUNT(data_cancelamento) AS suspenso
                FROM requisicoes_pessoal 
                WHERE id_empresa = '{$this->session->userdata('empresa')}' AND 
                      (id_depto = '{$busca['id_depto']}' OR CHAR_LENGTH('{$busca['id_depto']}') = 0) AND
                      (id_funcao = '{$busca['id_funcao']}' OR CHAR_LENGTH('{$busca['id_funcao']}') = 0) AND 
                      YEAR(data_abertura) = '{$ano}'";

        $total = $this->db->query($sql)->row_array();

        $sql .= ' GROUP BY MONTH(data_abertura) 
                 ORDER BY MONTH(data_abertura) ASC';

        $this->load->library('dataTables');

        $this->db->query("SET lc_time_names = 'pt_BR'");
        $output = $this->datatables->query($sql);

        unset($total['mes']);
        $output->total = array_values($total);

        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                ucfirst($row->mes),
                $row->aberto,
                $row->vagas_aberto,
                $row->fechado,
                $row->vagas_fechado,
                $row->parcial,
                $row->vagas_parcial,
                $row->suspenso
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

}
