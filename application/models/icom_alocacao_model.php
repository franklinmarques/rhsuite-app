<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_alocacao_model extends MY_Model
{
    protected static $table = 'icom_alocacao';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'id_depto' => 'required|is_natural_no_zero|max_length[11]',
        'id_area' => 'required|is_natural_no_zero|max_length[11]',
        'id_setor' => 'required|is_natural_no_zero|max_length[11]',
        'mes' => 'required|is_natural_no_zero|max_length[12]',
        'ano' => 'required|is_natural_no_zero|max_length[4]'
    ];

}
