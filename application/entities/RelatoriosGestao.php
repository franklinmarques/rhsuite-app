<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RelatoriosGestao extends Entity
{
    protected $id;
    protected $id_empresa;
    protected $id_usuario;
    protected $id_depto;
    protected $id_area;
    protected $id_setor;
    protected $mes_referencia;
    protected $ano_referencia;
    protected $data_fechamento;
    protected $indicadores;
    protected $riscos_oportunidades;
    protected $ocorrencias;
    protected $necessidades_investimentos;
    protected $objetivos_imediatos;
    protected $objetivos_futuros;
    protected $parecer_final;
    protected $observacoes;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_empresa' => 'int',
        'id_usuario' => 'int',
        'id_depto' => '?int',
        'id_area' => '?int',
        'id_setor' => '?int',
        'mes_referencia' => 'int',
        'ano_referencia' => 'int',
        'data_fechamento' => 'datetime',
        'indicadores' => '?string',
        'riscos_oportunidades' => '?string',
        'ocorrencias' => '?string',
        'necessidades_investimentos' => '?string',
        'objetivos_imediatos' => '?string',
        'objetivos_futuros' => '?string',
        'parecer_final' => '?string',
        'observacoes' => '?string',
        'status' => 'string'
    ];

}
