<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_servicos_model extends MY_Model
{
    protected static $table = 'st_servicos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_contrato' => 'required|is_natural_no_zero|max_length[11]',
        'tipo' => 'required|in_list[0,1]',
        'descricao' => 'required|max_length[255]',
        'data_reajuste' => 'valid_date',
        'valor' => 'required|numeric|max_length[11]'
    ];

    protected static $tipo = ['0' => 'Não compartilhado', '1' => 'Compartilhado'];

}
