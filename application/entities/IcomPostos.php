<?php

include_once APPPATH . 'entities/Entity.php';

class IcomPostos extends Entity
{
    protected $id;
    protected $id_setor;
    protected $id_usuario;
    protected $id_funcao;
    protected $matricula;
    protected $categoria;
    protected $valor_hora_mei;
    protected $valor_mes_clt;
    protected $qtde_horas_mei;
    protected $qtde_meses_clt;
    protected $horario_entrada;
    protected $horario_intervalo;
    protected $horario_retorno;
    protected $horario_saida;

    protected $casts = [
        'id' => 'int',
        'id_setor' => 'int',
        'id_usuario' => 'int',
        'id_funcao' => 'int',
        'matricula' => 'int',
        'categoria' => 'string',
        'valor_hora_mei' => '?float',
        'valor_mes_clt' => '?float',
        'qtde_horas_mei' => '?int',
        'qtde_meses_clt' => '?int',
        'horario_entrada' => '?string',
        'horario_intervalo' => '?string',
        'horario_retorno' => '?string',
        'horario_saida' => '?string'
    ];

}
