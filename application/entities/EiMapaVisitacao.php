<?php

namespace App\Entities;

use CodeIgniter\Entity;

class EiMapaVisitacao extends Entity
{
    protected $id;
    protected $id_mapa_unidade;
    protected $data_visita;
    protected $data_visita_anterior;
    protected $id_supervisor_visitante;
    protected $supervisor_visitante;
    protected $cliente;
    protected $municipio;
    protected $escola;
    protected $unidade_visitada;
    protected $prestadores_servicos_tratados;
    protected $coordenador_responsavel;
    protected $motivo_visita;
    protected $gastos_materiais;
    protected $sumario_visita;
    protected $observacoes;

    protected $casts = [
        'id' => 'int',
        'id_mapa_unidade' => 'int',
        'data_visita' => 'datetime',
        'data_visita_anterior' => '?datetime',
        'id_supervisor_visitante' => '?int',
        'supervisor_visitante' => 'string',
        'cliente' => 'int',
        'municipio' => 'string',
        'escola' => 'string',
        'unidade_visitada' => 'int',
        'prestadores_servicos_tratados' => '?string',
        'coordenador_responsavel' => '?int',
        'motivo_visita' => '?int',
        'gastos_materiais' => 'float',
        'sumario_visita' => '?string',
        'observacoes' => '?string'
    ];

}
