<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_unidades_model extends MY_Model
{
    protected static $table = 'st_unidades';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_contrato' => 'required|is_natural_no_zero|max_length[11]',
        'setor' => 'required|max_length[255]'
    ];

}
