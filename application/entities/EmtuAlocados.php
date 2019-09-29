<?php

include_once APPPATH . 'entities/Entity.php';

class EmtuAlocados extends Entity
{
    protected $id;
    protected $id_alocacao;
    protected $id_usuario;
    protected $nome_usuario;

    protected $id_funcao;
    protected $matricula;
    protected $categoria;
    protected $valor_hora_mei;
    protected $qtde_horas_mei;
    protected $qtde_horas_dia_mei;
    protected $valor_mes_clt;
    protected $qtde_meses_clt;
    protected $qtde_horas_dia_clt;
    protected $horario_entrada;
    protected $horario_intervalo;
    protected $horario_retorno;
    protected $horario_saida;
    protected $desconto_folha;
    protected $comprometimento;
    protected $pontualidade;
    protected $script;
    protected $simpatia;
    protected $empatia;
    protected $postura;
    protected $ferramenta;
    protected $tradutorio;
    protected $linguistico;
    protected $neutralidade;
    protected $discricao;
    protected $fidelidade;
    protected $extra_1;
    protected $extra_2;
    protected $extra_3;

    protected $casts = [
        'id' => 'int',
        'id_alocacao' => 'int',
        'id_usuario' => '?int',
        'nome_usuario' => 'string',
        'id_funcao' => '?int',
        'matricula' => '?int',
        'categoria' => 'string',
        'valor_hora_mei' => '?float',
        'qtde_horas_mei' => '?time',
        'qtde_horas_dia_mei' => '?time',
        'valor_mes_clt' => '?float',
        'qtde_meses_clt' => '?time',
        'qtde_horas_dia_clt' => '?time',
        'horario_entrada' => '?time',
        'horario_intervalo' => '?time',
        'horario_retorno' => '?time',
        'horario_saida' => '?time',
        'desconto_folha' => '?time',
        'comprometimento' => '?int',
        'pontualidade' => '?int',
        'script' => '?int',
        'simpatia' => '?int',
        'empatia' => '?int',
        'postura' => '?int',
        'ferramenta' => '?int',
        'tradutorio' => '?int',
        'linguistico' => '?int',
        'neutralidade' => '?int',
        'discricao' => '?int',
        'fidelidade' => '?int',
        'extra_1' => '?int',
        'extra_2' => '?int',
        'extra_3' => '?int'
    ];

}
