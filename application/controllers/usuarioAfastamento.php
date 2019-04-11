<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe UsuarioAfastamento
 *
 * Trabalha com o gerenciamento de exames periódicos dos colaboradores
 *
 * @author frank
 * @access public
 * @package CI_Controller\MY_Controller
 * @version 1.0
 */
class UsuarioAfastamento extends MY_Controller
{

    /**
     * Construtor da classe
     *
     * Carrega o model de afastamento do usuario
     *
     * @access public
     * @uses ..\models\usuarioafastamento_model.php Model
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuarioafastamento_model', 'afastamento');
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
     * Retorna lista de afastamento criados
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_list($id_usuario)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.data_afastamento,
                       s.motivo_afastamento,
                       s.data_pericia_medica,
                       s.data_limite_beneficio,
                       s.data_retorno,
                       s.historico_afastamento,
                       s.data_afastamento_de,
                       s.data_pericia_medica_de,
                       s.data_limite_beneficio_de,
                       s.data_retorno_de,
                       s.matricula
                FROM (SELECT a.id, 
                             a.data_afastamento,
                             CASE a.motivo_afastamento
                                  WHEN 1 THEN 'Auxílio doença - INSS'
                                  WHEN 2 THEN 'Licença maternidade'
                                  WHEN 3 THEN 'Acidente de trabalho'
                                  WHEN 4 THEN 'Aposentadoria por invalidez'
                                  END AS motivo_afastamento,
                             a.data_pericia_medica,
                             a.data_limite_beneficio,
                             a.data_retorno,
                             a.historico_afastamento,
                             DATE_FORMAT(a.data_afastamento,'%d/%m/%Y') AS data_afastamento_de,
                             DATE_FORMAT(a.data_pericia_medica,'%d/%m/%Y') AS data_pericia_medica_de,
                             DATE_FORMAT(a.data_limite_beneficio,'%d/%m/%Y') AS data_limite_beneficio_de,
                             DATE_FORMAT(a.data_retorno,'%d/%m/%Y') AS data_retorno_de,
                             b.matricula
                      FROM usuarios_afastamento a
                      INNER JOIN usuarios b
                                 ON b.id = a.id_usuario
                                 AND b.empresa = a.id_empresa
                      WHERE b.id = {$id_usuario}";
        if (!empty($post['status'])) {
            $sql .= ' AND b.status IN (6, 7, 8, 9)';
        }
        if (!empty($post['status2'])) {
            $sql .= ' AND CHAR_LENGTH(a.data_retorno) = 0';
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.data_afastamento_de',
            's.motivo_afastamento',
            's.data_pericia_medica_de',
            's.data_limite_beneficio_de',
            's.data_retorno_de',
            's.historico_afastamento',
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
        foreach ($list as $afastamento) {
            $row = array();
            $row[] = $afastamento->data_afastamento_de;
            $row[] = $afastamento->motivo_afastamento;
            $row[] = $afastamento->data_pericia_medica_de;
            $row[] = $afastamento->data_limite_beneficio_de;
            $row[] = $afastamento->data_retorno_de;
            $row[] = $afastamento->historico_afastamento;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-primary" onclick="edit_afastamento(' . $afastamento->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_afastamento(' . $afastamento->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
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
     * Retorna dados para edição de afastamento
     *
     * @access public
     */
    public function ajax_edit($id)
    {
        $data = $this->afastamento->select(array('id' => $id));
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
        if ($data['data_afastamento']) {
            $_POST['data_afastamento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_afastamento'])));
        }
        if ($data['data_pericia_medica']) {
            $_POST['data_pericia_medica'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_pericia_medica'])));
        } else {
            $_POST['data_pericia_medica'] = null;
        }
        if ($data['data_limite_beneficio']) {
            $_POST['data_limite_beneficio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_limite_beneficio'])));
        } else {
            $_POST['data_limite_beneficio'] = null;
        }
        if (!empty($data['data_retorno'])) {
            $_POST['data_retorno'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_retorno'])));
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra um novo afastamento
     *
     * @access public
     */
    public function ajax_add()
    {
        $this->formatarDados();
        if (($msg = $this->validar()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        $status = $this->afastamento->insert();
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de afastamento
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validar()
    {
        return $this->afastamento->validar();
    }

    // -------------------------------------------------------------------------

    /**
     * Altera um afastamento existente
     *
     * @access public
     */
    public function ajax_update()
    {
        $this->formatarDados();
        $id = $this->input->post('id');
        if (($msg = $this->afastamento->update(array('id' => $id))) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de afastamento
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidar()
    {
        return $this->afastamento->revalidar();
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui um afastamento existente
     *
     * @access public
     */
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        if (($msg = $this->afastamento->delete(array('id' => $id))) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Relatório de todos os afastamentos listados
     *
     * @access public
     */
    public function relatorio($pdf = false)
    {
        $empresa = $this->session->userdata('empresa');
        $status = $this->input->get('status');

        $data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');

        $this->db->select('b.id, b.nome, a.motivo_afastamento', false);
        $this->db->select("DATE_FORMAT(a.data_afastamento, '%d/%m/%Y') AS data_afastamento", false);
        $this->db->select("IF(a.data_pericia_medica, DATE_FORMAT(a.data_pericia_medica, '%d/%m/%Y'), NULL) AS data_pericia_medica", false);
        $this->db->select("IF(a.data_limite_beneficio, DATE_FORMAT(a.data_limite_beneficio, '%d/%m/%Y'), NULL) AS data_limite_beneficio", false);
        $this->db->select("IF(a.data_retorno, DATE_FORMAT(a.data_retorno, '%d/%m/%Y'), NULL) AS data_retorno", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $empresa);
        if ($status) {
            $this->db->where('a.data_retorno', null);
        }
        $data['funcionarios'] = $this->db->get('usuarios_afastamento a')->result();
        $data['is_pdf'] = $pdf;

        if ($pdf) {
            return $this->load->view('funcionarios_afastamentoPdf', $data, true);
        }
        $this->load->view('funcionarios_afastamentoRelatorio', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de afastamento criados
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_relatorio()
    {
        $post = $this->input->post();


        $sql = "SELECT s.id,
                       s.nome,
                       s.data_afastamento,
                       s.motivo_afastamento,
                       s.data_pericia_medica,
                       s.data_limite_beneficio,
                       s.data_retorno,
                       s.data_afastamento_de,
                       s.data_pericia_medica_de,
                       s.data_limite_beneficio_de,
                       s.data_retorno_de,
                       s.matricula
                FROM (SELECT b.id,
                             b.nome,
                             b.matricula,
                             a.data_afastamento,
                             CASE a.motivo_afastamento
                                  WHEN 1 THEN 'Auxílio doença - INSS'
                                  WHEN 2 THEN 'Licença maternidade'
                                  WHEN 3 THEN 'Acidente de trabalho'
                                  WHEN 4 THEN 'Aposentadoria por invalidez'
                                  END AS motivo_afastamento,
                             a.data_pericia_medica,
                             a.data_limite_beneficio,
                             a.data_retorno,
                             DATE_FORMAT(a.data_afastamento,'%d/%m/%Y') AS data_afastamento_de,
                             IF(a.data_pericia_medica, DATE_FORMAT(a.data_pericia_medica,'%d/%m/%Y'), NULL) AS data_pericia_medica_de,
                             IF(a.data_limite_beneficio, DATE_FORMAT(a.data_limite_beneficio,'%d/%m/%Y'), NULL) AS data_limite_beneficio_de,
                             IF(a.data_retorno, DATE_FORMAT(a.data_retorno,'%d/%m/%Y'), NULL) AS data_retorno_de
                      FROM usuarios_afastamento a
                      INNER JOIN usuarios b
                                 ON b.id = a.id_usuario
                                 AND b.empresa = a.id_empresa
                      WHERE b.empresa = {$this->session->userdata('empresa')}";
        if (!empty($post['status'])) {
            $sql .= ' AND b.status IN (6, 7, 8, 9)';
        }
        if (!empty($post['status2'])) {
            $sql .= ' AND a.data_retorno IS NULL';
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.nome',
            's.data_afastamento_de',
            's.motivo_afastamento',
            's.data_pericia_medica_de',
            's.data_limite_beneficio_de',
            's.data_retorno_de',
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
        foreach ($list as $afastamento) {
            $row = array();
            $row[] = $afastamento->nome;
            $row[] = $afastamento->data_afastamento_de;
            $row[] = $afastamento->motivo_afastamento;
            $row[] = $afastamento->data_pericia_medica_de;
            $row[] = $afastamento->data_limite_beneficio_de;
            $row[] = $afastamento->data_retorno_de;
            $row[] = '
                      <a class="btn btn-success btn-sm"
                               href="' . site_url('funcionario/editar/' . $afastamento->id) . '"
                               title="Prontuário de colaborador">
                                <i class="glyphicon glyphicon-plus"></i> Prontuário
                            </a>
                            <button class="btn btn-danger btn-sm" 
                            onclick="delete_prontuario(' . $afastamento->id . ')"
                            title="Excluir prontuário">
                                <i class="glyphicon glyphicon-trash"></i>
                            </button>
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
     * Limpa os afastamentos de um usuário
     *
     * @access public
     */
    public function limpar()
    {
        $id_usuario = $this->input->post('id_usuario');
        if (($msg = $this->afastamento->delete(array('id_usuario' => $id_usuario))) !== true) {
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

        $stylesheet = 'table.afastamento tr { border-width: 3px; border-color: #ddd; } ';

        $stylesheet .= 'table.funcionarios tr th, table.funcionarios tr td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= 'table.funcionarios thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.funcionarios thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.funcionarios tbody tr th { background-color: #dff0d8; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio($this->uri->rsegment(3), true));


        $this->m_pdf->pdf->Output("Relatório de Afastamentos.pdf", 'D');
    }

}
