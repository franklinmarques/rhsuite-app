<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_aprovadores_model extends MY_Model
{
    protected static $table = 'requisicoes_pessoal_aprovadores';

    protected $validationRules = [
        'id_usuario' => 'required|is_natural_no_zero|max_length[11]'
    ];

}
