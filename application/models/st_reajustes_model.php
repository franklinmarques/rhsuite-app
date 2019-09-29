<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_reajustes_model extends MY_Model
{
    protected static $table = 'st_reajustes';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_cliente' => 'required|is_natural_no_zero|max_length[11]',
        'data_reajuste' => 'required|valid_date',
        'valor_indice' => 'required|numeric|max_length[11]'
    ];

}
