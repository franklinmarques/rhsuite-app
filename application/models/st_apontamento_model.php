<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_apontamento_model extends MY_Model
{
    protected static $table = 'st_apontamento';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_alocado' => 'required|is_natural_no_zero|max_length[11]',
        'data' => 'required|valid_date',
        'hora_entrada' => 'valid_time',
        'hora_intervalo' => 'valid_time',
        'hora_retorno' => 'valid_time',
        'hora_saida' => 'valid_time',
        'qtde_dias' => 'is_natural_no_zero|max_length[2]',
        'hora_atraso' => 'valid_time',
        'apontamento_extra' => 'valid_time',
        'apontamento_desc' => 'valid_time',
        'apontamento_saldo' => 'valid_time',
        'hora_glosa' => 'valid_time',
        'detalhes' => 'is_natural_no_zero|max_length[11]',
        'observacoes' => 'max_length[4294967295]',
        'status' => 'required|exact_length[2]',
        'id_alocado_bck' => 'is_natural_no_zero|max_length[11]'
    ];

}
