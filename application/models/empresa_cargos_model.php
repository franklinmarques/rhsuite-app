<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa_cargos_model extends MY_Model
{
    protected static $table = 'empresa_cargos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[255]',
        'familia_CBO' => 'is_natural|max_length[4]'
    ];

}
