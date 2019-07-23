<?php

namespace App\Entities;

use CodeIgniter\Entity;

class FacilitiesRealizacoesVistorias extends Entity
{
    protected $id;
    protected $id_realizacao;
    protected $id_modelo_vistoria;
    protected $numero_os;
    protected $possui_problema;
    protected $vistoriado;
    protected $nao_aplicavel;
    protected $descricao_problema;
    protected $observacoes;
    protected $data_abertura;
    protected $data_realizacao;
    protected $realizacao_cat;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'id_realizacao' => 'int',
        'id_modelo_vistoria' => 'int',
        'numero_os' => 'string',
        'possui_problema' => '?int',
        'vistoriado' => '?int',
        'nao_aplicavel' => 'int',
        'descricao_problema' => '?string',
        'observacoes' => '?string',
        'data_abertura' => '?datetime',
        'data_realizacao' => '?datetime',
        'realizacao_cat' => '?string',
        'status' => '?string'
    ];

}
