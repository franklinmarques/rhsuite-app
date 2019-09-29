<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emtu_alocados_model extends MY_Model
{
    protected static $table = 'emtu_alocados';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
        'id_usuario' => 'is_natural_no_zero|max_length[11]',
        'nome_usuario' => 'required|max_length[255]',
        'id_funcao' => 'is_natural_no_zero|max_length[11]'
    ];

}
