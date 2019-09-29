<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_cursos_model extends MY_Model
{
    protected static $table = 'ei_cursos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_diretoria' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[255]',
        'qtde_semestres' => 'is_natural|max_length[2]'
    ];

}
