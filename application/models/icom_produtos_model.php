<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Icom_produtos_model extends MY_Model
{
    protected static $table = 'icom_produtos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'codigo' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[255]',
        'tipo' => 'required|in_list[P,S]',
        'preco' => 'required|numeric|greater_than_equal_to[0]|max_length[10]',
        'tipo_preco' => 'required|in_list[H,M,C]'
    ];

    protected static $tipo = ['P' => 'Produto', 'S' => 'Serviço'];

    protected static $tipoPreco = ['H' => 'Por hora', 'M' => 'Por mês', 'C' => 'Por colaborador/mês'];

}
