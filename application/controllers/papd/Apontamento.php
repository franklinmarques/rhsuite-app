<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe Apontamento
 *
 * Trabalha com alocações de apontamentos feitos por Cuidadores
 *
 * @author Franklin Marques
 * @access public
 * @package CI_Controller\MY_Controller\cd
 * @version 1.0
 */
class Apontamento extends MY_Controller
{

    /**
     * Construtor da classe
     *
     * Verifica a permissão de acesso do usuário ao módulo
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

        $tipo = $this->session->userdata('tipo');
        $hash_acesso = $this->session->userdata('hash_acesso');
        if (!($tipo == 'empresa' or $hash_acesso['CD'] == 610)) {
            redirect(site_url('home'));
        }
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de apontamentos de cuidadores no mês corrente
     * Se o usuário for do tipo funcionário, os filtros serão pré-selecionados
     *
     * @access public
     * @uses ..\views\apontamento.php View
     */
    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = '';
        if ($this->session->userdata('nivel') == 10) {
            $id_usuario = $this->session->userdata('id');
        }


        // Carrega as opções de departamentos alocados e não-alocados
        $sqlDepto = "SELECT a.depto AS nome
                     FROM cd_diretorias a
                     INNER JOIN cd_escolas b
                                ON a.id = b.id_diretoria
                     INNER JOIN cd_supervisores c
                                ON b.id = c.id_escola
                     WHERE a.id_empresa = {$empresa}
                           AND (c.id_supervisor = '{$id_usuario}' OR '{$id_usuario}' = '')
                     UNION
                     SELECT depto AS nome
                     FROM cd_alocacao
                     WHERE id_empresa = {$empresa}
                     ORDER BY nome ASC";
        $departamentos = $this->db->query($sqlDepto)->result();
        $data['depto'] = array();
        foreach ($departamentos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }


        // Carrega as opções de diretorias alocadas e não-alocadas
        $sqlDiretoria = "SELECT a.nome 
                         FROM cd_diretorias a
                         INNER JOIN cd_escolas b
                                    ON a.id = b.id_diretoria
                         INNER JOIN cd_supervisores c
                                    ON b.id = c.id_escola
                         WHERE a.id_empresa = {$empresa}
                               AND (c.id_supervisor = '{$id_usuario}' OR '{$id_usuario}' = '')
                         UNION
                         SELECT diretoria AS nome
                         FROM cd_alocacao
                         WHERE id_empresa = {$empresa}
                         ORDER BY nome ASC";
        $diretorias = $this->db->query($sqlDiretoria)->result();
        $data['diretoria'] = array('' => 'Todas');
        foreach ($diretorias as $diretoria) {
            $data['diretoria'][$diretoria->nome] = $diretoria->nome;
        }


        // Carrega as opções de supervisores alocados e não-alocados
        $sqlSupervisor = "SELECT b.nome 
                          FROM cd_supervisores a
                          INNER JOIN usuarios b
                                     ON a.id_supervisor = b.id
                          WHERE b.empresa = {$empresa}
                                AND (a.id_supervisor = '{$id_usuario}' OR '{$id_usuario}' = '')
                          UNION
                          SELECT supervisor AS nome
                          FROM cd_alocacao
                          WHERE id_empresa = {$empresa}
                          ORDER BY nome ASC";
        $supervisores = $this->db->query($sqlSupervisor)->result();
        if ($this->session->userdata('nivel') == 10 and count($supervisores) > 0) {
            $data['supervisor'] = array();
        } else {
            $data['supervisor'] = array('' => 'Todos');
        }
        foreach ($supervisores as $supervisor) {
            $data['supervisor'][$supervisor->nome] = $supervisor->nome;
        }


        // Carrega as opções de mês e ano
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
        $data['mes'] = $data['meses'][date('m')];


        // Variáveis para a configuração da view
        $this->db->select('a.id, d.nome');
        $this->db->join('cd_alocacao b', 'b.id = a.id_alocacao');
        $this->db->join('cd_cuidadores c', 'c.id = a.id_vinculado');
        $this->db->join('usuarios d', 'd.id = c.id_cuidador');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where("DATE_FORMAT(b.data, '%Y-%m') =", date('Y-m'));
        $cuidadores = $this->db->get('cd_alocados a')->result();
        $data['usuarios'] = array('' => 'selecione...');
        foreach ($cuidadores as $cuidador) {
            $data['usuarios'][$cuidador->id] = $cuidador->nome;
        }

        $modo_privilegiado = true;
        $data['modo_privilegiado'] = $modo_privilegiado;
        $data['depto_atual'] = count($departamentos) > 1 ? '' : 'Cuidadores';
        $data['diretoria_atual'] = '';
        if (in_array($this->session->userdata('nivel'), array(9, 10))) {
            $data['supervisor_atual'] = $supervisores[0]->nome ?? '';
        } else {
            $data['supervisor_atual'] = '';
        }

        $data['id_diretoria'] = array('' => 'selecione...');
        $data['id_escola'] = array('' => 'selecione...');
        $data['id_alocado'] = array('' => 'selecione...');


        $this->load->view('cd/apontamento', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de apontamentos criados a partir de um elememnto referencial
     *
     * @access public
     * @uses ..\views\apontamento.php View
     */
    public function gerenciar()
    {
        $data = $this->input->get();
        $this->load->view('apontamento', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Abre a tela de criar novo apontamento
     *
     * @access public
     * @uses ..\views\apontamento_novo.php View
     */
    public function novo()
    {
        $data = $this->input->get();
        $this->load->view('apontamento', $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna opções para filtragem dos apontamentos existentes
     *
     * @access public
     */
    public function atualizar_filtro()
    {
        $data = $this->input->post();
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna lista de apontamentos criados
     *
     * Se o usuário for do tipo gestor, lista somente os registros da sua empresa
     *
     * @access public
     */
    public function ajax_list()
    {

    }

    // -------------------------------------------------------------------------

    /**
     * Retorna dados para edição de apontamento
     *
     * @access public
     */
    public function ajax_edit()
    {
        $where = $this->input->post();
        $data = $this->apontamento->select($where);
        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Cadastra um novo apontamento
     *
     * @access public
     */
    public function ajax_add()
    {
        if (($msg = $this->validar()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        $status = $this->apontamento->insert();
        echo json_encode(array("status" => $status));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para inserção de apontamento
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function validar()
    {
        return $this->apontamento->validar();
    }

    // -------------------------------------------------------------------------

    /**
     * Altera um apontamento existente
     *
     * @access public
     */
    public function ajax_update()
    {
        if (($msg = $this->apontamento->update()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Valida os dados para alteração de apontamento
     *
     * @access public
     * @return  bool|string    TRUE para sucesso, FALSE ou string para falha
     */
    public function revalidar()
    {
        return $this->apontamento->revalidar();
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui um apontamento existente
     *
     * @access public
     */
    public function ajax_delete()
    {
        if (($msg = $this->apontamento->delete()) !== true) {
            exit(json_encode(array('retorno' => 0, 'aviso' => $msg)));
        }
        echo json_encode(array("status" => $msg));
    }

    // -------------------------------------------------------------------------

    /**
     * Exporta a lista de apontamentos para um arquivo .pdf
     *
     * @access public
     * @uses ..\libraries\mpdf.php Library para montagem de arquivos .pdf
     */
    public function pdf()
    {

    }

}
