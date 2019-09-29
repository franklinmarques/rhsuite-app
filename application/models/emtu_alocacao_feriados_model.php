<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Emtu_alocacao_feriados_model extends MY_Model
{
    protected static $table = 'emtu_alocacao_feriados';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
        'data' => 'required|valid_date',
        'status' => 'required|max_length[255]',
        'qtde_novos_processos' => 'is_natural_no_zero|max_length[11]',
        'qtde_analistas' => 'is_natural_no_zero|max_length[11]',
        'qtde_processos_tratados_dia' => 'is_natural_no_zero|max_length[11]',
        'qtde_pagamentos' => 'is_natural_no_zero|max_length[11]'
    ];

}
