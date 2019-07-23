<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Abcbr304Menu extends Entity
{
    protected $id;
    protected $ordem;
    protected $nome;
    protected $url;
    protected $icone;
    protected $paginas_ativas;
    protected $id_pai;

    protected $casts = [
        'id' => 'int',
        'ordem' => 'int',
        'nome' => 'string',
        'url' => '?string',
        'icone' => '?string',
        'paginas_ativas' => '?string',
        'id_pai' => '?int'
    ];

}
