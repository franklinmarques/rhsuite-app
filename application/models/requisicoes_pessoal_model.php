<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_model extends MY_Model
{
    protected static $table = 'requisicoes_pessoal';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
        'data_abertura' => 'required|valid_date',
        'data_fechamento' => 'valid_date|after_or_equal_date[data_abertura]',
        'data_solicitacao_exame' => 'valid_date',
        'data_suspensao' => 'valid_date',
        'data_cancelamento' => 'valid_date',
        'requisicao_confidencial' => 'required|is_natural_no_zero|exact_length[1]',
        'tipo_vaga' => 'required|in_list[I,E]',
        'selecionador' => 'max_length[255]',
        'spa' => 'is_natural_no_zero|max_length[6]',
        'requisitante_interno' => 'is_natural_no_zero|max_length[11]',
        'requisitante_externo' => 'max_length[255]',
        'numero_contrato' => 'max_length[255]',
        'centro_custo' => 'max_length[255]',
        'regime_contratacao' => 'is_natural_no_zero|less_than_equal_to[4]',
        'id_depto' => 'is_natural_no_zero|max_length[11]',
        'id_area' => 'is_natural_no_zero|max_length[11]',
        'id_setor' => 'is_natural_no_zero|max_length[11]',
        'id_cargo' => 'is_natural_no_zero|max_length[11]',
        'id_funcao' => 'is_natural_no_zero|max_length[11]',
        'cargo_funcao_alternativo' => 'max_length[255]',
        'cargo_externo' => 'max_length[255]',
        'funcao_externa' => 'max_length[255]',
        'numero_vagas' => 'required|is_natural_no_zero|max_length[11]',
        'vagas_deficiente' => 'is_natural_no_zero|exact_length[1]',
        'justificativa_contratacao' => 'required|in_list[S,T,A,P]',
        'colaborador_substituto' => 'max_length[65535]',
        'possui_indicacao' => 'is_natural|exact_length[1]',
        'colaboradores_indicados' => 'max_length[65535]',
        'indicador_responsavel' => 'max_length[255]',
        'aprovado_por' => 'max_length[255]',
        'data_aprovacao' => 'valid_date',
        'remuneracao_mensal' => 'numeric|greater_than_equal_to[0]|max_length[10]',
        'horario_trabalho' => 'max_length[65535]',
        'previsao_inicio' => 'valid_date|after_or_equal_date[data_aprovacao]',
        'vale_transporte' => 'is_natural|exact_length[1]',
        'valor_vale_transporte' => 'numeric|greater_than_equal_to[0]|max_length[7]',
        'vale_alimentacao' => 'is_natural|exact_length[1]',
        'valor_vale_alimentacao' => 'numeric|greater_than_equal_to[0]|max_length[7]',
        'vale_refeicao' => 'is_natural|exact_length[1]',
        'valor_vale_refeicao' => 'numeric|greater_than_equal_to[0]|max_length[7]',
        'assistencia_medica' => 'is_natural|exact_length[1]',
        'valor_assistencia_medica' => 'numeric|greater_than_equal_to[0]|max_length[7]',
        'plano_odontologico' => 'is_natural|exact_length[1]',
        'valor_plano_odontologico' => 'numeric|greater_than_equal_to[0]|max_length[7]',
        'cesta_basica' => 'is_natural|exact_length[1]',
        'valor_cesta_basica' => 'numeric|greater_than_equal_to[0]|max_length[7]',
        'participacao_resultados' => 'is_natural|exact_length[1]',
        'valor_participacao_resultados' => 'numeric|greater_than_equal_to[0]|max_length[7]',
        'local_trabalho' => 'max_length[255]',
        'municipio' => 'max_length[30]',
        'exame_clinico' => 'is_natural|exact_length[1]',
        'audiometria' => 'is_natural|exact_length[1]',
        'laudo_cotas' => 'is_natural|exact_length[1]',
        'exame_outros' => 'max_length[255]',
        'perfil_geral' => 'max_length[65535]',
        'competencias_tecnicas' => 'max_length[65535]',
        'competencias_comportamentais' => 'max_length[65535]',
        'atividades_associadas' => 'max_length[65535]',
        'observacoes' => 'max_length[65535]',
        'estagio' => 'is_natural_no_zero|less_than_equal_to[12]',
        'status' => 'required|in_list[A,S,C,G,F,P]',
        'data_nascimento' => 'valid_date',
        'nome_mae' => 'max_length[255]',
        'nome_pai' => 'max_length[255]',
        'rg' => 'max_length[14]',
        'rg_data_emissao' => 'valid_date|after_or_equal_date[data_nascimento]',
        'rg_orgao_emissor' => 'max_length[100]',
        'cpf' => 'max_length[14]',
        'pis' => 'max_length[14]',
        'departamento_informacoes' => 'max_length[65535]'
    ];

    protected static $tipoVaga = ['I' => 'Interna', 'E' => 'Externa'];

    protected static $regimeContratacao = ['1' => 'CLT', '2' => 'MEI', '3' => 'PJ', '4' => 'Estágio'];

    protected static $justificativaContratacao = [
        'S' => 'Substituição',
        'T' => 'Transferência',
        'A' => 'Aumento de quadro',
        'P' => 'Temporário'
    ];

    protected static $requisicaoConfidencial = ['1' => 'Confidencial', '0' => 'Não confidencial'];

    protected static $estagio = [
        '1' => '01/10 - Alinhando perfil',
        '2' => '02/10 - Divulgando vagas',
        '3' => '03/10 - Triando currículos',
        '4' => '04/10 - Convocando candidatos',
        '5' => '05/10 - Entrevistando candidatos',
        '6' => '06/10 - Elaborando pareceres',
        '7' => '07/10 - Aguardando gestor',
        '8' => '08/10 - Entrevista solicitante',
        '9' => '09/10 - Exame adissional',
        '10' => '10/10 - Entrega documentos',
        '11' => 'Faturamento',
        '12' => 'Processo finalizado'
    ];

    protected static $status = [
        'A' => 'Ativa',
        'S' => 'Suspensa',
        'C' => 'Cancelada',
        'G' => 'Aguardando aprovação',
        'F' => 'Fechada',
        'P' => 'Fechada parcialmente'
    ];

}
