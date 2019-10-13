<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Requisicoes_pessoal_model extends MY_Model
{
	protected static $table = 'requisicoes_pessoal';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'id_empresa' => 'required|is_natural_no_zero|max_length[11]',
		'numero' => 'required|max_length[255]',
		'data_abertura' => 'required|valid_date',
		'data_fechamento' => 'valid_date',
		'data_solicitacao_exame' => 'valid_date',
		'data_suspensao' => 'valid_date',
		'data_cancelamento' => 'valid_date',
		'data_processo_seletivo' => 'valid_date',
		'requisicao_confidencial' => 'required|numeric|max_length[1]',
		'tipo_vaga' => 'required|exact_length[1]',
		'selecionador' => 'max_length[255]',
		'spa' => 'integer|max_length[6]',
		'requisitante_interno' => 'is_natural_no_zero|max_length[11]',
		'requisitante_externo' => 'max_length[255]',
		'numero_contrato' => 'max_length[255]',
		'centro_custo' => 'max_length[255]',
		'regime_contratacao' => 'required|integer|max_length[1]',
		'id_depto' => 'integer|max_length[11]',
		'id_area' => 'integer|max_length[11]',
		'id_setor' => 'integer|max_length[11]',
		'id_cargo' => 'is_natural_no_zero|max_length[11]',
		'id_funcao' => 'is_natural_no_zero|max_length[11]',
		'cargo_funcao_alternativo' => 'max_length[255]',
		'cargo_externo' => 'max_length[255]',
		'funcao_externa' => 'max_length[255]',
		'numero_vagas' => 'required|integer|max_length[11]',
		'vagas_deficiente' => 'integer|max_length[1]',
		'justificativa_contratacao' => 'required|exact_length[1]',
		'colaborador_substituto' => 'max_length[4294967295]',
		'possui_indicacao' => 'numeric|max_length[1]',
		'colaboradores_indicados' => 'max_length[4294967295]',
		'indicador_responsavel' => 'max_length[255]',
		'aprovado_por' => 'max_length[255]',
		'data_aprovacao' => 'valid_date',
		'remuneracao_mensal' => 'decimal|max_length[11]',
		'horario_trabalho' => 'max_length[4294967295]',
		'previsao_inicio' => 'valid_date',
		'vale_transporte' => 'integer|max_length[1]',
		'valor_vale_transporte' => 'decimal|max_length[8]',
		'vale_alimentacao' => 'integer|max_length[1]',
		'valor_vale_alimentacao' => 'decimal|max_length[8]',
		'vale_refeicao' => 'integer|max_length[1]',
		'valor_vale_refeicao' => 'decimal|max_length[8]',
		'assistencia_medica' => 'integer|max_length[1]',
		'valor_assistencia_medica' => 'decimal|max_length[8]',
		'plano_odontologico' => 'integer|max_length[1]',
		'valor_plano_odontologico' => 'decimal|max_length[8]',
		'cesta_basica' => 'integer|max_length[1]',
		'valor_cesta_basica' => 'decimal|max_length[8]',
		'participacao_resultados' => 'integer|max_length[1]',
		'valor_participacao_resultados' => 'decimal|max_length[8]',
		'local_trabalho' => 'max_length[255]',
		'municipio' => 'max_length[30]',
		'exame_clinico' => 'integer|max_length[1]',
		'audiometria' => 'inte
		ger|max_length[1]',
		'laudo_cotas' => 'integer|max_length[1]',
		'exame_outros' => 'max_length[255]',
		'perfil_geral' => 'max_length[4294967295]',
		'competencias_tecnicas' => 'max_length[4294967295]',
		'competencias_comportamentais' => 'max_length[4294967295]',
		'atividades_associadas' => 'max_length[4294967295]',
		'observacoes' => 'max_length[4294967295]',
		'observacoes_selecionador' => 'max_length[65535]',
		'observacoes_gerais' => 'max_length[4294967295]',
		'estagio' => 'required|integer|max_length[2]',
		'status' => 'required|exact_length[1]',
		'descricao_pendencias' => 'max_length[255]',
		'data_nascimento' => 'valid_date',
		'nome_mae' => 'max_length[255]',
		'nome_pai' => 'max_length[255]',
		'rg' => 'max_length[14]',
		'rg_data_emissao' => 'valid_date',
		'rg_orgao_emissor' => 'max_length[100]',
		'cpf' => 'max_length[14]',
		'pis' => 'max_length[14]',
		'departamento_informacoes' => 'max_length[4294967295]'
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
