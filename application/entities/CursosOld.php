<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CursosOld extends Entity
{
    protected $id;
    protected $usuario;
    protected $tipo;
    protected $publico;
    protected $gratuito;
    protected $curso;
    protected $descricao;
    protected $datacadastro;
    protected $dataeditado;
    protected $duracao;
    protected $objetivos;
    protected $competencias_genericas;
    protected $competencias_especificas;
    protected $competencias_comportamentais;
    protected $area_conhecimento;
    protected $categoria;
    protected $consultor;
    protected $foto_consultor;
    protected $curriculo;
    protected $foto_treinamento;
    protected $pre_requisitos;
    protected $progressao_linear;
    protected $versao;
    protected $status;

    protected $casts = [
        'id' => 'int',
        'usuario' => 'int',
        'tipo' => 'string',
        'publico' => 'int',
        'gratuito' => 'int',
        'curso' => 'string',
        'descricao' => 'string',
        'datacadastro' => 'datetime',
        'dataeditado' => 'datetime',
        'duracao' => 'string',
        'objetivos' => '?string',
        'competencias_genericas' => '?string',
        'competencias_especificas' => '?string',
        'competencias_comportamentais' => '?string',
        'area_conhecimento' => '?string',
        'categoria' => '?string',
        'consultor' => 'string',
        'foto_consultor' => '?string',
        'curriculo' => '?string',
        'foto_treinamento' => '?string',
        'pre_requisitos' => '?string',
        'progressao_linear' => 'int',
        'versao' => '?int',
        'status' => '?int'
    ];

}
