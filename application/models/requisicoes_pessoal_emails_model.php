<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_emails_model extends MY_Model
{
    protected static $table = 'requisicoes_pessoal_emails';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'is_natural_no_zero|max_length[11]',
        'colaborador' => 'required|max_length[255]',
        'email' => 'required|valid_email|max_length[255]',
        'tipo_usuario' => 'required|is_natural_no_zero|less_than_equal_to[5]',
        'tipo_email' => 'is_natural_no_zero|less_than_equal_to[4]'
    ];

    protected static $tipoUsuario = [
        '1' => 'Selecionador',
        '2' => 'Departamento de Pessoal',
        '3' => 'Gestão de Pessoas',
        '4' => 'Administrador',
        '5' => 'Gestor'
    ];

    protected static $tipoEmail = [
        '1' => 'Nova Requisição de Pessoal',
        '2' => 'Solicitação de agendamento Exame Médico',
        '3' => 'Nova requisição + Solicitação de agendamento',
        '4' => 'Administrador'
    ];

}
