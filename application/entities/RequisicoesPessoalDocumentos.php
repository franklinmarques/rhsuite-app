<?php

namespace App\Entities;

use CodeIgniter\Entity;

class RequisicoesPessoalDocumentos extends Entity
{
    protected $id;
    protected $id_candidato;
    protected $nome_arquivo;
    protected $tipo_arquivo;
    protected $data_upload;

    protected $casts = [
        'id' => 'int',
        'id_candidato' => 'int',
        'nome_arquivo' => 'string',
        'tipo_arquivo' => 'string',
        'data_upload' => 'datetime'
    ];

}
