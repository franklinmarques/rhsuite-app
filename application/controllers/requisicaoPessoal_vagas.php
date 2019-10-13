<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal_vagas extends MY_Controller
{

    public function index()
    {
        $this->load->view('requisicaoPessoal_vagas');
    }

    public function ajaxList()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db->select(["CONCAT(c.nome, '/', d.nome) AS cargo_funcao, COUNT(IF(a.status IN ('A', 'G', 'P'), 1, NULL)) AS qtde_vagas"], false);
        $this->db->select(["IFNULL(e.nome, a.requisitante_interno) AS requisitante"], false);
        $this->db->select('a.data_abertura, a.previsao_inicio, COUNT(b.id) AS total_aprovados', false);
        $this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura_de"], false);
        $this->db->select(["DATE_FORMAT(a.previsao_inicio, '%d/%m/%Y') AS previsao_inicio_de"], false);
        $this->db->join('requisicoes_pessoal_candidatos b', 'b.id_requisicao = a.id AND b.aprovado = 1', 'left');
        $this->db->join('empresa_cargos c', 'c.id = a.id_cargo', 'left');
        $this->db->join('empresa_funcoes d', 'd.id = a.id_funcao', 'left');
        $this->db->join('usuarios e', 'e.id = a.requisitante_interno', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        if ($busca['status']) {
            $this->db->where('a.status', $busca['status']);
        }
        if ($busca['data_inicio']) {
            $this->db->where("DATE_FORMAT(a.data_abertura, '%d/%m/%Y') =", $busca['data_inicio']);
        }
        if ($busca['data_termino']) {
            $this->db->where("DATE_FORMAT(a.data_fechamento, '%d/%m/%Y') =", $busca['data_termino']);
        }
        $this->db->group_by('a.requisitante_interno');
        $query = $this->db->get('requisicoes_pessoal a');

        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->cargo_funcao,
                $row->qtde_vagas,
                $row->requisitante,
                $row->data_abertura_de,
                $row->previsao_inicio_de,
                $row->total_aprovados
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

}
