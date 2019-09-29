<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emtu_alocacao_model extends MY_Model
{
    protected static $table = 'emtu_alocacao';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'id_depto' => 'required|is_natural_no_zero|max_length[11]',
        'id_area' => 'required|is_natural_no_zero|max_length[11]',
        'id_setor' => 'required|is_natural_no_zero|max_length[11]',
        'mes' => 'required|is_natural_no_zero|less_than_equal_to[12]',
        'ano' => 'required|is_natural_no_zero|max_length[4]',
        'dia_fechamento' => 'is_natural_no_zero|less_than_equal_to[31]'
    ];

}
