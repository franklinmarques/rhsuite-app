<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_postos_model extends MY_Model
{
    protected static $table = 'icom_postos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_setor' => 'required|is_natural_no_zero|max_length[11]',
        'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
        'id_funcao' => 'required|is_natural_no_zero|max_length[11]',
        'matricula' => 'required|integer|max_length[11]',
        'categoria' => 'required|in_list[CLT,MEI]',
        'valor_hora_mei' => 'numeric|max_length[10]',
        'valor_mes_clt' => 'numeric|max_length[10]',
        'qtde_horas_mei' => 'is_natural_no_zero|max_length[11]',
        'qtde_meses_clt' => 'is_natural_no_zero|max_length[11]',
        'horario_entrada' => 'valid_time',
        'horario_intervalo' => 'valid_time|after_time[horario_entrada]',
        'horario_retorno' => 'valid_time|after_time[horario_entrada]|after_time[horario_intervalo]',
        'horario_saida' => 'valid_time|after_time[horario_entrada]|after_time[horario_intervalo]|after_time[horario_retorno]'
    ];

}
