<?php

include_once APPPATH . 'entities/Entity.php';

class RequisicoesPessoal extends Entity
{
	protected $id;
	protected $id_empresa;
	protected $numero;
	protected $data_abertura;
	protected $data_fechamento;
	protected $data_solicitacao_exame;
	protected $data_suspensao;
	protected $data_cancelamento;
	protected $data_processo_seletivo;
	protected $requisicao_confidencial;
	protected $tipo_vaga;
	protected $selecionador;
	protected $spa;
	protected $requisitante_interno;
	protected $requisitante_externo;
	protected $numero_contrato;
	protected $centro_custo;
	protected $regime_contratacao;
	protected $id_depto;
	protected $id_area;
	protected $id_setor;
	protected $id_cargo;
	protected $id_funcao;
	protected $cargo_funcao_alternativo;
	protected $cargo_externo;
	protected $funcao_externa;
	protected $numero_vagas;
	protected $vagas_deficiente;
	protected $justificativa_contratacao;
	protected $colaborador_substituto;
	protected $possui_indicacao;
	protected $colaboradores_indicados;
	protected $indicador_responsavel;
	protected $aprovado_por;
	protected $data_aprovacao;
	protected $remuneracao_mensal;
	protected $horario_trabalho;
	protected $previsao_inicio;
	protected $vale_transporte;
	protected $valor_vale_transporte;
	protected $vale_alimentacao;
	protected $valor_vale_alimentacao;
	protected $vale_refeicao;
	protected $valor_vale_refeicao;
	protected $assistencia_medica;
	protected $valor_assistencia_medica;
	protected $plano_odontologico;
	protected $valor_plano_odontologico;
	protected $cesta_basica;
	protected $valor_cesta_basica;
	protected $participacao_resultados;
	protected $valor_participacao_resultados;
	protected $local_trabalho;
	protected $municipio;
	protected $exame_clinico;
	protected $audiometria;
	protected $laudo_cotas;
	protected $exame_outros;
	protected $perfil_geral;
	protected $competencias_tecnicas;
	protected $competencias_comportamentais;
	protected $atividades_associadas;
	protected $observacoes;
	protected $observacoes_selecionador;
	protected $observacoes_gerais;
	protected $estagio;
	protected $status;
	protected $descricao_pendencias;
	protected $data_nascimento;
	protected $nome_mae;
	protected $nome_pai;
	protected $rg;
	protected $rg_data_emissao;
	protected $rg_orgao_emissor;
	protected $cpf;
	protected $pis;
	protected $departamento_informacoes;

	protected $casts = [
		'id' => 'int',
		'id_empresa' => 'int',
		'numero' => 'string',
		'data_abertura' => 'date',
		'data_fechamento' => '?date',
		'data_solicitacao_exame' => '?date',
		'data_suspensao' => '?date',
		'data_cancelamento' => '?date',
		'data_processo_seletivo' => '?date',
		'requisicao_confidencial' => 'int',
		'tipo_vaga' => 'string',
		'selecionador' => '?string',
		'spa' => '?int',
		'requisitante_interno' => '?int',
		'requisitante_externo' => '?string',
		'numero_contrato' => '?string',
		'centro_custo' => '?string',
		'regime_contratacao' => 'int',
		'id_depto' => '?int',
		'id_area' => '?int',
		'id_setor' => '?int',
		'id_cargo' => '?int',
		'id_funcao' => '?int',
		'cargo_funcao_alternativo' => '?string',
		'cargo_externo' => '?string',
		'funcao_externa' => '?string',
		'numero_vagas' => 'int',
		'vagas_deficiente' => '?int',
		'justificativa_contratacao' => 'string',
		'colaborador_substituto' => '?string',
		'possui_indicacao' => '?int',
		'colaboradores_indicados' => '?string',
		'indicador_responsavel' => '?string',
		'aprovado_por' => '?string',
		'data_aprovacao' => '?date',
		'remuneracao_mensal' => '?float',
		'horario_trabalho' => '?string',
		'previsao_inicio' => '?date',
		'vale_transporte' => '?int',
		'valor_vale_transporte' => '?float',
		'vale_alimentacao' => '?int',
		'valor_vale_alimentacao' => '?float',
		'vale_refeicao' => '?int',
		'valor_vale_refeicao' => '?float',
		'assistencia_medica' => '?int',
		'valor_assistencia_medica' => '?float',
		'plano_odontologico' => '?int',
		'valor_plano_odontologico' => '?float',
		'cesta_basica' => '?int',
		'valor_cesta_basica' => '?float',
		'participacao_resultados' => '?int',
		'valor_participacao_resultados' => '?float',
		'local_trabalho' => '?string',
		'municipio' => '?string',
		'exame_clinico' => '?int',
		'audiometria' => '?int',
		'laudo_cotas' => '?int',
		'exame_outros' => '?string',
		'perfil_geral' => '?string',
		'competencias_tecnicas' => '?string',
		'competencias_comportamentais' => '?string',
		'atividades_associadas' => '?string',
		'observacoes' => '?string',
		'observacoes_selecionador' => '?string',
		'observacoes_gerais' => '?string',
		'estagio' => 'int',
		'status' => 'string',
		'descricao_pendencias' => '?string',
		'data_nascimento' => '?date',
		'nome_mae' => '?string',
		'nome_pai' => '?string',
		'rg' => '?string',
		'rg_data_emissao' => '?date',
		'rg_orgao_emissor' => '?string',
		'cpf' => '?string',
		'pis' => '?string',
		'departamento_informacoes' => '?string'
	];

}
