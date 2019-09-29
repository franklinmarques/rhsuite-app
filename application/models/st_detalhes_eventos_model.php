<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_detalhes_eventos_model extends MY_Model
{
    protected static $table = 'st_detalhes_eventos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'codigo' => 'required|max_length[30]|is_unique[st_detalhes_eventos.codigo]',
        'nome' => 'required|max_length[255]'
    ];

}
