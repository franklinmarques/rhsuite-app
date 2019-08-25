<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_candidatos_model extends MY_Model
{
    protected static $table = 'requisicoes_pessoal_candidatos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_requisicao' => 'required|is_natural_no_zero|max_length[11]',
        'id_usuario' => 'required_without[id_usuario_banco]|is_natural_no_zero|max_length[11]',
        'id_usuario_banco' => 'required_without[id_usuario]|is_natural_no_zero|max_length[11]',
        'status' => 'in_list[A,P,F,N,S,I]',
        'data_selecao' => 'valid_datetime',
        'resultado_selecao' => 'exact_length[1]',
        'data_requisitante' => 'valid_datetime',
        'resultado_requisitante' => 'exact_length[1]',
        'antecedentes_criminais' => 'is_natural|exact_length[1]',
        'restricoes_financeiras' => 'is_natural|exact_length[1]',
        'data_exame_admissional' => 'valid_datetime',
        'endereco_exame_admissional' => 'max_length[65535]',
        'resultado_exame_admissional' => 'is_natural|exact_length[1]',
        'aprovado' => 'is_natural|exact_length[1]',
        'data_admissao' => 'valid_date',
        'observacoes' => 'max_length[4294967295]',
        'desempenho_perfil' => 'in_list[A,M,B]'
    ];

    protected static $status = [
        'A' => 'Agendado',
        'P' => 'Em processo',
        'F' => 'Fora do perfil',
        'N' => 'Não atende ou recado',
        'S' => 'Sem interesse',
        'I' => 'Telefone errado ou inexistente'
    ];

    protected static $resultadoSelecao = [
        'A' => 'Selecionado',
        'D' => 'Desistiu',
        'N' => 'Não compareceu',
        'X' => 'Aprovado',
        'R' => 'Reprovado',
        'S' => 'Stand by'
    ];

    protected static $resultadoRequisitante = [
        'A' => 'Selecionado',
        'C' => 'Aprovado',
        'D' => 'Desistiu',
        'N' => 'Não compareceu',
        'R' => 'Reprovado',
        'S' => 'Stand by'
    ];

    protected static $antecedentesCriminais = ['0' => 'Nada consta', '1' => 'Antecedentes'];

    protected static $restricoesFinanceiras = ['0' => 'Sem restrições', '1' => 'Com restrições'];

    protected static $resultadoExameAdmissional = ['1' => 'Apto', '0' => 'Não apto'];

    protected static $desempenhoPerfil = ['A' => 'Bom', 'M' => 'Regular', 'B' => 'Ruim'];

}
