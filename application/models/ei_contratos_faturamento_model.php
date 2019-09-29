<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_contratos_faturamento_model extends MY_Model
{
    protected static $table = 'ei_valores_faturamento';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_contrato' => 'required|is_natural_no_zero|max_length[11]',
        'ano' => 'required|is_natural_no_zero|max_length[4]', // entre data de inicio e termino do contrato
        'semestre' => 'required|in_list[1,2]', // entre data de inicio e termino do contrato
        'id_funcao' => 'required|is_natural_no_zero|max_length[11]',
        'valor_faturamento' => 'is_natural_no_zero|max_length[11]',
        'valor_pagamento' => 'is_natural_no_zero|max_length[11]',
        'valor_faturamento2' => 'is_natural_no_zero|max_length[11]',
        'valor_pagamento2' => 'is_natural_no_zero|max_length[11]'
    ];

}
