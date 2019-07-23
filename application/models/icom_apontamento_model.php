<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_apontamento_model extends MY_Model
{
    protected static $table = 'icom_apontamento';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_alocacao' => 'required|is_natural_no_zero|max_length[11]',
        'data' => 'required|valid_date',
        'periodo' => 'required|is_natural|less_than_equal_to[3]',
        'id_cliente' => 'is_natural_no_zero|max_length[11]',
        'tipo_evento' => 'exact_length[1]',
        'nome_cliente' => 'max_length[255]',
        'codigo_contrato' => 'required|is_natural_no_zero|max_length[11]',
        'centro_custo' => 'max_length[255]',
        'horario_inicio' => 'required|valid_time',
        'horario_termino' => 'required|valid_time|after_time[horario_inicio]',
        'total_horas' => 'required|valid_time',
        'custo_colaboradores' => 'numeric|max_length[11]',
        'custo_operacional' => 'numeric|max_length[11]',
        'impostos' => 'numeric|max_length[11]',
        'valor_cobrado' => 'numeric|max_length[11]',
        'receita_liquida' => 'numeric|max_length[11]'
    ];

    protected static $periodo = ['0' => 'Madrugada', '1' => 'Manhã', '2' => 'Tarde', '3' => 'Noite'];

    protected static $tipoEvento = ['E' => 'Esporádico', 'C' => 'Contrato'];

}
