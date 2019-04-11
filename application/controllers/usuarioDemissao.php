<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe UsuarioDemissao
 *
 * Trabalha com o gerenciamento de demissões dos colaboradores
 *
 * @author frank
 * @access public
 * @package CI_Controller\MY_Controller
 * @version 1.0
 */
class UsuarioDemissao extends MY_Controller
{

    /**
     * Construtor da classe
     *
     * Carrega o model de demissão do usuario
     *
     * @access public
     * @uses ..\models\usuariodemissao_model.php Model
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuariodemissao_model', 'demissao');
    }

    /**
     * Função padrão
     *
     * @access public
     */
    public function index()
    {
        $this->relatorio();
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de demissões criados
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_list($id_usuario)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.nome,
                       s.data_demissao,
                       s.tipo_demissao,
                       s.data_demissao_de,
                       s.matricula
                FROM (SELECT a.id, 
                             a.nome,
                             a.data_demissao,
                             CASE a.tipo_demissao
                                  WHEN 1 THEN 'Demissão sem justa causa'
                                  WHEN 2 THEN 'Demissão por justa causa'
                                  WHEN 3 THEN 'Pedido de demissão'
                                  WHEN 4 THEN 'Término do contrato'
                                  WHEN 5 THEN 'Rescisão antecipada pelo empregado'
                                  WHEN 6 THEN 'Rescisão antecipada pelo empregador'
                                  WHEN 7 THEN 'Desistiu da vaga'
                                  END AS tipo_demissao,
                             DATE_FORMAT(a.data_demissao,'%d/%m/%Y') AS data_demissao_de,
                             a.matricula
                      FROM usuarios a
                      WHERE a.empresa = {$id_usuario}
                            AND (a.data_demissao IS NOT NULL OR a.status IN (4, 5))) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.data_demissao_de',
            's.tipo_demissao',
            's.observacoes',
            's.matricula'
        );
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
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
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
        foreach ($list as $demissao) {
            $row = array();
            $row[] = $demissao->nome;
            $row[] = $demissao->data_demissao_de;
            $row[] = $demissao->tipo_demissao;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_demissao(' . $demissao->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_demissao(' . $demissao->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
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

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de demissão
     *
     * @access public
     */
    public function ajax_edit($id)
    {
        $data = $this->demissao->select(array('id' => $id));
        echo json_encode($data);
    }


    // -------------------------------------------------------------------------

    /**
     * Formata os dados para inserção ou alteração
     *
     * @access private
     */
    private function formatarDados()
    {
        $data = $this->input->post();
        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }
        if ($data['data_demissao']) {
            $_POST['data_demissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_demissao'])));
        }
        if (strlen($data['observacoes']) == 0) {
            $_POST['observacoes'] = null;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra uma nova demissão
     *
     * @access public
     */
    public function ajax_add()
    {
        $this->formatarDados();
        if (($msg = $this->validar()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        $status = $this->demissao->insert();
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de demissão
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validar()
    {
        return $this->demissao->validar();
    }

    // -------------------------------------------------------------------------

    /**
     * Altera uma demissão existente
     *
     * @access public
     */
    public function ajax_update()
    {
        $this->formatarDados();
        $id = $this->input->post('id');
        if (($msg = $this->demissao->update(array('id' => $id))) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de demissão
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidar()
    {
        return $this->demissao->revalidar();
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui uma demissão existente
     *
     * @access public
     */
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        if (($msg = $this->demissao->delete(array('id' => $id))) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Relatório de todos as demissões listados
     *
     * @access public
     */
    public function relatorio($pdf = false)
    {
        $empresa = $this->session->userdata('empresa');

        $data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');


        $dataInicial = $this->input->get('data_inicial');
        if ($dataInicial) {
            $dataInicial = date('Y-m-d', strtotime(str_replace('/', '-', $dataInicial)));
        }
        $dataFinal = $this->input->get('data_final');
        if ($dataFinal) {
            $dataFinal = date('Y-m-d', strtotime(str_replace('/', '-', $dataFinal)));
        }
        $tipoDemissao = $this->input->get('tipo_demissao');


        $sql = "SELECT s.id,
                       s.nome,
                       s.data_demissao,
                       s.tipo_demissao
                FROM (SELECT a.id,
                             a.nome,
                             CASE a.tipo_demissao
                                  WHEN 1 THEN 'Demissão sem justa causa'
                                  WHEN 2 THEN 'Demissão por justa causa'
                                  WHEN 3 THEN 'Pedido de demissão'
                                  WHEN 4 THEN 'Término do contrato'
                                  WHEN 5 THEN 'Rescisão antecipada pelo empregado'
                                  WHEN 6 THEN 'Rescisão antecipada pelo empregador'
                                  WHEN 7 THEN 'Desistiu da vaga'
                                  END AS tipo_demissao,
                             DATE_FORMAT(a.data_demissao,'%d/%m/%Y') AS data_demissao
                      FROM usuarios a
                      WHERE a.empresa = {$empresa}
                            AND (a.data_demissao IS NOT NULL OR a.status IN (4, 5))";
        if ($tipoDemissao) {
            $sql .= " AND a.tipo_demissao = '{$tipoDemissao}'";
        }
        if ($dataInicial and $dataFinal) {
            $sql .= " AND a.data_demissao BETWEEN '{$dataInicial}' AND '{$dataFinal}'";
        } else {
            if ($dataInicial) {
                $sql .= " AND a.data_demissao <='{$dataInicial}'";
            } elseif ($dataFinal) {
                $sql .= " AND a.data_demissao >='{$dataFinal}'";
            }
        }
        $sql .= ') s';
        $data['funcionarios'] = $this->db->query($sql)->result();

        $data['is_pdf'] = $pdf;

        if ($pdf) {
            return $this->load->view('funcionarios_demissaoPdf', $data, true);
        }
        $this->load->view('funcionarios_demissaoRelatorio', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de demissões criadas
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_relatorio()
    {
        $post = $this->input->post();
        $dataInicial = $this->input->post('data_inicial');
        if ($dataInicial) {
            $dataInicial = date('Y-m-d', strtotime(str_replace('/', '-', $dataInicial)));
        }
        $dataFinal = $this->input->post('data_final');
        if ($dataFinal) {
            $dataFinal = date('Y-m-d', strtotime(str_replace('/', '-', $dataFinal)));
        }
        $tipoDemissao = $this->input->post('tipo_demissao');


        $sql = "SELECT s.id,
                       s.nome,
                       s.data_demissao,
                       s.tipo_demissao,
                       s.data_demissao_de
                FROM (SELECT a.id,
                             a.nome,
                             a.data_demissao,
                             CASE a.tipo_demissao
                                  WHEN 1 THEN 'Demissão sem justa causa'
                                  WHEN 2 THEN 'Demissão por justa causa'
                                  WHEN 3 THEN 'Pedido de demissão'
                                  WHEN 4 THEN 'Término do contrato'
                                  WHEN 5 THEN 'Rescisão antecipada pelo empregado'
                                  WHEN 6 THEN 'Rescisão antecipada pelo empregador'
                                  WHEN 7 THEN 'Desistiu da vaga'
                                  END AS tipo_demissao,
                             DATE_FORMAT(a.data_demissao,'%d/%m/%Y') AS data_demissao_de
                      FROM usuarios a
                      WHERE a.empresa = {$this->session->userdata('empresa')}
                            AND (a.data_demissao IS NOT NULL OR a.status IN (4, 5))";
        if ($tipoDemissao) {
            $sql .= " AND a.tipo_demissao = '{$tipoDemissao}'";
        }
        if ($dataInicial and $dataFinal) {
            $sql .= " AND a.data_demissao BETWEEN '{$dataInicial}' AND '{$dataFinal}'";
        } else {
            if ($dataInicial) {
                $sql .= " AND a.data_demissao <='{$dataInicial}'";
            } elseif ($dataFinal) {
                $sql .= " AND a.data_demissao >='{$dataFinal}'";
            }
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.nome',
            's.data_demissao_de',
            's.tipo_demissao'
        );
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
                $orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
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
        foreach ($list as $demissao) {
            $row = array();
            $row[] = $demissao->nome;
            $row[] = $demissao->data_demissao_de;
            $row[] = $demissao->tipo_demissao;
            $row[] = '
                      <a class="btn btn-primary btn-sm"
                               href="' . site_url('funcionario/editar/' . $demissao->id) . '"
                               title="Prontuário de colaborador">
                                <i class="glyphicon glyphicon-plus"></i> Prontuário
                            </a>
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

    // -------------------------------------------------------------------------

    /**
     * Limpa as demissões de um usuário
     *
     * @access public
     */
    public function limpar()
    {
        $id_usuario = $this->input->post('id_usuario');
        if (($msg = $this->demissao->delete(array('id_usuario' => $id_usuario))) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Gera o pdf do relatório
     *
     * @access public
     * @uses ..\libraries\mpdf.php
     */
    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.demissao tr { border-width: 3px; border-color: #ddd; } ';

        $stylesheet .= 'table.funcionarios tr th, table.funcionarios tr td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= 'table.funcionarios thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.funcionarios thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.funcionarios tbody tr th { background-color: #dff0d8; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));


        $this->m_pdf->pdf->Output("Relatório de Demissões.pdf", 'D');
    }

}
