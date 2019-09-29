<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_apontamento_model extends MY_Model
{
    protected static $table = 'ei_apontamento';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_alocado' => 'required|is_natural_no_zero|max_length[11]',
        'data' => 'required|valid_date',
        'periodo' => 'is_natural|less_than_equal_to[4]',
        'horario_inicio' => 'valid_time',
        'status' => 'required|in_list[FA,PV,AT,SA,FE,EM,RE,AF,EU]',
        'id_usuario' => 'is_natural_no_zero|max_length[11]',
        'id_alocado_sub1' => 'is_natural_no_zero|max_length[11]',
        'id_alocado_sub2' => 'is_natural_no_zero|max_length[11]',
        'desconto' => 'valid_time',
        'desconto_sub1' => 'valid_time',
        'desconto_sub2' => 'valid_time',
        'ocorrencia_cuidador' => 'max_length[4294967295]',
        'ocorrencia_aluno' => 'max_length[4294967295]',
        'ocorrencia_professor' => 'max_length[4294967295]'
    ];

    protected static $status = [
        'FA' => 'Falta',
        'PV' => 'Posto vago',
        'AT' => 'Atraso',
        'SA' => 'Saída antecipada',
        'FE' => 'Feriado',
        'EM' => 'Emenda de feriado',
        'RE' => 'Recesso',
        'AF' => 'Aluno ausente',
        'EU' => 'Evento unidade'
    ];

    protected static $statusNegativos = [
        'FA' => 'Falta',
        'PV' => 'Posto vago',
        'AT' => 'Atraso',
        'SA' => 'Saída antecipada'
    ];

    protected static $periodo = [
        '0' => 'Madrugada',
        '1' => 'Manhã',
        '2' => 'Tarde',
        '3' => 'Noite'
    ];

}
