<?php

namespace App\Entities;

use CodeIgniter\Entity;

class CursosUsuarios extends Entity
{
    protected $id;
    protected $id_usuario;
    protected $id_curso;
    protected $data_cadastro;
    protected $data_inicio;
    protected $data_maxima;
    protected $colaboradores_maximo;
    protected $nota_aprovacao;
    protected $tipo_treinamento;
    protected $local_treinamento;
    protected $nome;
    protected $carga_horaria_presencial;
    protected $avaliacao_presencial;
    protected $nome_fornecedor;

    protected $casts = [
        'id' => 'int',
        'id_usuario' => 'int',
        'id_curso' => '?int',
        'data_cadastro' => 'datetime',
        'data_inicio' => '?datetime',
        'data_maxima' => '?datetime',
        'colaboradores_maximo' => '?int',
        'nota_aprovacao' => '?int',
        'tipo_treinamento' => '?string',
        'local_treinamento' => '?string',
        'nome' => '?string',
        'carga_horaria_presencial' => '?string',
        'avaliacao_presencial' => '?int',
        'nome_fornecedor' => '?string'
    ];

}
