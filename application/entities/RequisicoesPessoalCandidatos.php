<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RequisicoesPessoalCandidatos extends Entity
{
    protected $id;
    protected $id_requisicao;
    protected $id_usuario;
    protected $id_usuario_banco;
    protected $status;
    protected $data_selecao;
    protected $resultado_selecao;
    protected $data_requisitante;
    protected $resultado_requisitante;
    protected $antecedentes_criminais;
    protected $restricoes_financeiras;
    protected $data_exame_admissional;
    protected $resultado_exame_admissional;
    protected $aprovado;
    protected $data_admissao;
    protected $observacoes;

    protected $casts = [
        'id' => 'int',
        'id_requisicao' => 'int',
        'id_usuario' => '?int',
        'id_usuario_banco' => '?int',
        'status' => '?string',
        'data_selecao' => '?datetime',
        'resultado_selecao' => '?string',
        'data_requisitante' => '?datetime',
        'resultado_requisitante' => '?string',
        'antecedentes_criminais' => '?int',
        'restricoes_financeiras' => '?int',
        'data_exame_admissional' => '?datetime',
        'resultado_exame_admissional' => '?int',
        'aprovado' => '?int',
        'data_admissao' => '?datetime',
        'observacoes' => '?string'
    ];

}
