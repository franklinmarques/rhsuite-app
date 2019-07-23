<?php

namespace App\Entities;

use CodeIgniter\Entity;

class PapdMif extends Entity
{
    protected $id;
    protected $id_paciente;
    protected $avaliador;
    protected $data_avaliacao;
    protected $mif;
    protected $observacoes;
    protected $alimentacao;
    protected $arrumacao;
    protected $banho;
    protected $vestimenta_superior;
    protected $vestimenta_inferior;
    protected $higiene_pessoal;
    protected $controle_vesical;
    protected $controle_intestinal;
    protected $transferencia;
    protected $leito_cadeira;
    protected $sanitario;
    protected $banheiro_chuveiro;
    protected $marcha;
    protected $cadeira_rodas;
    protected $escadas;
    protected $compreensao_ambas;
    protected $compreensao_visual;
    protected $expressao_verbal;
    protected $expressao_nao_verbal;
    protected $interacao_social;
    protected $resolucao_problemas;
    protected $memoria;

    protected $casts = [
        'id' => 'int',
        'id_paciente' => 'int',
        'avaliador' => 'string',
        'data_avaliacao' => 'datetime',
        'mif' => '?int',
        'observacoes' => '?string',
        'alimentacao' => '?int',
        'arrumacao' => '?int',
        'banho' => '?int',
        'vestimenta_superior' => '?int',
        'vestimenta_inferior' => '?int',
        'higiene_pessoal' => '?int',
        'controle_vesical' => '?int',
        'controle_intestinal' => '?int',
        'transferencia' => '?int',
        'leito_cadeira' => '?int',
        'sanitario' => '?int',
        'banheiro_chuveiro' => '?int',
        'marcha' => '?int',
        'cadeira_rodas' => '?int',
        'escadas' => '?int',
        'compreensao_ambas' => '?int',
        'compreensao_visual' => '?int',
        'expressao_verbal' => '?int',
        'expressao_nao_verbal' => '?int',
        'interacao_social' => '?int',
        'resolucao_problemas' => '?int',
        'memoria' => '?int'
    ];

}
