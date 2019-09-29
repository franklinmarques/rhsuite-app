<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_alocados_model extends MY_Model
{
    protected static $table = 'st_alocados';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
        'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[255]',
        'cargo' => 'max_length[255]',
        'funcao' => 'max_length[255]',
        'id_posto' => 'is_natural_no_zero|max_length[11]',
        'tipo_horario' => 'required|in_list[I]',
        'nivel' => 'required|in_list[P]',
        'tipo_bck' => 'in_list[1]',
        'data_recesso' => 'valid_date|required_with[data_retorno]',
        'data_retorno' => 'valid_date|after_date[data_recesso]',
        'id_usuario_bck' => 'is_natural_no_zero|max_length[11]',
        'nome_bck' => 'max_length[255]',
        'data_desligamento' => 'valid_date',
        'id_usuario_sub' => 'is_natural_no_zero|max_length[11]',
        'nome_sub' => 'max_length[255]',
        'dias_acrescidos' => 'numeric|max_length[11]',
        'horas_acrescidas' => 'numeric|max_length[11]',
        'total_acrescido' => 'numeric|max_length[11]',
        'total_faltas' => 'valid_time',
        'total_atrasos' => 'valid_time',
        'horas_saldo' => 'valid_time',
        'horas_saldo_acumulado' => 'valid_time'
    ];

}
