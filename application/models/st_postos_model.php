<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class St_postos_model extends MY_Model
{
    protected static $table = 'st_postos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_usuario' => 'required|is_natural_no_zero|max_length[11]',
        'data' => 'required|valid_date',
        'depto' => 'max_length[255]',
        'area' => 'max_length[255]',
        'setor' => 'max_length[255]',
        'cargo' => 'max_length[255]',
        'funcao' => 'max_length[255]',
        'contrato' => 'max_length[255]',
        'total_dias_mensais' => 'required|is_natural|max_length[11]',
        'total_horas_diarias' => 'required|is_natural|max_length[11]',
        'matricula' => 'max_length[255]',
        'login' => 'max_length[255]',
        'horario_entrada' => 'valid_time',
        'horario_saida' => 'valid_time|after_time[horario_entrada]',
        'valor_posto' => 'required|numeric|max_length[11]',
        'valor_dia' => 'required|numeric|max_length[11]',
        'valor_hora' => 'required|numeric|max_length[11]'
    ];

}
