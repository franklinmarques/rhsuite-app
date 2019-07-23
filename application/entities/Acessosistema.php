<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Acessosistema extends Entity
{
    protected $id;
    protected $usuario;
    protected $tipo;
    protected $data_acesso;
    protected $data_atualizacao;
    protected $data_saida;
    protected $endereco_ip;
    protected $agente_usuario;
    protected $id_sessao;

    protected $casts = [
        'id' => 'int',
        'usuario' => 'int',
        'tipo' => '?string',
        'data_acesso' => 'datetime',
        'data_atualizacao' => '?datetime',
        'data_saida' => '?datetime',
        'endereco_ip' => '?string',
        'agente_usuario' => '?string',
        'id_sessao' => 'string'
    ];

}
