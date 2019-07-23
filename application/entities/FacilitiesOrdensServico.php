<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesOrdensServico extends Entity
{
    protected $numero_os;
    protected $id_usuario;
    protected $data_abertura;
    protected $data_fechamento;
    protected $data_resolucao_problema;
    protected $status;
    protected $prioridade;
    protected $id_requisitante;
    protected $id_depto;
    protected $id_area;
    protected $id_setor;
    protected $descricao_problema;
    protected $descricao_solicitacao;
    protected $observacoes;
    protected $resolucao_satisfatoria;
    protected $observacoes_positivas;
    protected $observacoes_negativas;

    protected $casts = [
        'numero_os' => 'int',
        'id_usuario' => '?int',
        'data_abertura' => 'datetime',
        'data_fechamento' => '?datetime',
        'data_resolucao_problema' => '?datetime',
        'status' => 'string',
        'prioridade' => 'int',
        'id_requisitante' => 'int',
        'id_depto' => '?int',
        'id_area' => '?int',
        'id_setor' => '?int',
        'descricao_problema' => '?string',
        'descricao_solicitacao' => '?string',
        'observacoes' => '?string',
        'resolucao_satisfatoria' => '?string',
        'observacoes_positivas' => '?string',
        'observacoes_negativas' => '?string'
    ];

}
