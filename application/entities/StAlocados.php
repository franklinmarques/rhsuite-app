<?php

include_once APPPATH . 'entities/Entity.php';

class StAlocados extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_usuario;
    protected $nome;
    protected $cargo;
    protected $funcao;
    protected $id_posto;
    protected $tipo_horario;
    protected $nivel;
    protected $tipo_bck;
    protected $data_recesso;
    protected $data_retorno;
    protected $id_usuario_bck;
    protected $nome_bck;
    protected $data_desligamento;
    protected $id_usuario_sub;
    protected $nome_sub;
    protected $dias_acrescidos;
    protected $horas_acrescidas;
    protected $total_acrescido;
    protected $total_faltas;
    protected $total_atrasos;
    protected $horas_saldo;
    protected $horas_saldo_acumulado;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_usuario' => 'int',
        'nome' => 'string',
        'cargo' => '?string',
        'funcao' => '?string',
        'id_posto' => '?int',
        'tipo_horario' => 'string',
        'nivel' => 'string',
        'tipo_bck' => '?string',
        'data_recesso' => '?datetime',
        'data_retorno' => '?datetime',
        'id_usuario_bck' => '?int',
        'nome_bck' => '?string',
        'data_desligamento' => '?datetime',
        'id_usuario_sub' => '?int',
        'nome_sub' => '?string',
        'dias_acrescidos' => '?float',
        'horas_acrescidas' => '?float',
        'total_acrescido' => '?float',
        'total_faltas' => '?string',
        'total_atrasos' => '?string',
        'horas_saldo' => '?string',
        'horas_saldo_acumulado' => '?string'
    ];

}
