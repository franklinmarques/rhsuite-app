<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Log_usuarios
 *
 * Trabalha com os logs de entrada e saída de usuários
 *
 * @package model
 */
class Log_usuarios_model extends CI_Model
{

    /**
     * Nome da tabela usada pelo model
     *
     * @var string
     */
    protected $table = 'acessosistema';

    // -------------------------------------------------------------------------

    /**
     * Construtor.
     *
     * Carrega o model
     */
    public function __construct()
    {
        parent::__construct();
    }

    // -------------------------------------------------------------------------

    /**
     * Retorna um registro da tabela principal do model
     *
     * @param array $where
     * @return mixed
     */
    public function selecionar($where = array())
    {
        $this->db->select('id');
        $this->db->where('usuario', $this->session->userdata('id'));
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $log = $this->db->get($this->table)->row();
        $id = $log->id ?? null;

        $tempoLimite = $this->session->sess_expiration;

        $case = "CASE WHEN data_saida IS NOT NULL THEN 'finalizado'
                      WHEN DATE_ADD(IFNULL(data_atualizacao, data_acesso), INTERVAL {$tempoLimite} SECOND)  >= NOW() THEN 'logado'
                      ELSE 'expirado' END";

        $this->db->select('*');
        $this->db->select("DATE_FORMAT(data_acesso, '%d/%m/%Y &ensp; %H:%i:%s') AS data_hora_acesso", false);
        $this->db->select("DATE_FORMAT(data_atualizacao, '%d/%m/%Y &ensp; %H:%i:%s') AS data_hora_atualizacao", false);
        $this->db->select("DATE_FORMAT(data_saida, '%d/%m/%Y &ensp; %H:%i:%s') AS data_hora_saida", false);
        $this->db->select("({$case}) AS status", false);
        if ($where) {
            $this->db->where($where);
        }
        $row = $this->db->get($this->table)->row();

        $usuario = $this->db->select('nome')->get_where('usuarios', array('id' => $row->usuario))->row();
        $row->nome = $usuario->nome ?? '';

        return $row;
    }

    // -------------------------------------------------------------------------

    /**
     * Cria o registro de log do usuário
     *
     * @return mixed
     */
    public function salvar()
    {
        $data = array(
            'usuario' => $this->session->userdata('id'),
            'data_acesso' => date('Y-m-d H:i:s'),
            'endereco_ip' => $this->input->ip_address(),
            'agente_usuario' => $this->input->user_agent()
        );
        return $this->db->insert($this->table, $data);
    }

    // -------------------------------------------------------------------------

    /**
     * Finaliza o log com a data e hora de logout e o status da sessão de usuário
     *
     * @param bool $status
     * @return mixed
     */
    public function finalizar($status = true)
    {
        $this->db->select('id');
        $this->db->where('usuario', $this->session->userdata('id'));
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $where = $this->db->get($this->table)->row_array();

        $data = array(
            'data_saida' => date('Y-m-d H:i:s')
        );
        return $this->db->update($this->table, $data, $where);
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui um registro da tabela principal do model
     *
     * @param array $where
     * @return mixed
     */
    public function excluir($where = array())
    {
        return $this->db->delete($this->table, $where);
    }

    // -------------------------------------------------------------------------

    /**
     * Exclui grupo de registro da tabela principal do model
     *
     * @param array $where
     * @return mixed
     */
    public function limpar($dataInicial = null, $dataFinal = null)
    {
        $empresa = $this->session->userdata('empresa');
        $sql = "DELETE FROM {$this->table} 
                WHERE usuario IN (SELECT id 
                                  FROM usuarios 
                                  WHERE empresa = {$empresa} 
                                        OR id = {$empresa})";
        if ($dataInicial) {
            $sql .= " AND (data_acesso >= '{$dataInicial}' OR (data_saida >= '{$dataInicial}' OR data_saida IS NULL))";
        }
        if ($dataFinal) {
            $sql .= " AND (data_acesso <= '{$dataFinal}' OR (data_saida <= '{$dataFinal}' OR data_saida IS NULL))";
        }
        return $this->db->query($sql);
    }

}
