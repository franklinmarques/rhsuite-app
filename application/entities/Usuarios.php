<?php

include_once APPPATH . 'entities/Entity.php';

class Usuarios extends Entity
{
	protected $id;
	protected $empresa;
	protected $tipo;
	protected $url;
	protected $nome;
	protected $data_nascimento;
	protected $sexo;
	protected $depto;
	protected $area;
	protected $setor;
	protected $cargo;
	protected $funcao;
	protected $municipio;
	protected $id_depto;
	protected $id_area;
	protected $id_setor;
	protected $id_cargo;
	protected $id_funcao;
	protected $foto;
	protected $foto_descricao;
	protected $cabecalho;
	protected $imagem_inicial;
	protected $tipo_tela_inicial;
	protected $imagem_fundo;
	protected $video_fundo;
	protected $assinatura_digital;
	protected $tipo_vinculo;
	protected $rg;
	protected $cpf;
	protected $cnpj;
	protected $pis;
	protected $nome_mae;
	protected $nome_pai;
	protected $telefone;
	protected $email;
	protected $senha;
	protected $token;
	protected $matricula;
	protected $contrato;
	protected $centro_custo;
	protected $nome_banco;
	protected $agencia_bancaria;
	protected $conta_bancaria;
	protected $nome_cartao;
	protected $valor_vt;
	protected $datacadastro;
	protected $dataeditado;
	protected $data_admissao;
	protected $data_demissao;
	protected $tipo_demissao;
	protected $observacoes_demissao;
	protected $nivel_acesso;
	protected $hash_acesso;
	protected $max_colaboradores;
	protected $observacoes_historico;
	protected $observacoes_avaliacao_exp;
	protected $status;
	protected $saldo_apontamentos;
	protected $banco_horas_icom;
	protected $visualizacao_pilula_conhecimento;

	protected $casts = [
		'id' => 'int',
		'empresa' => '?int',
		'tipo' => 'string',
		'url' => 'string',
		'nome' => 'string',
		'data_nascimento' => '?date',
		'sexo' => '?string',
		'depto' => '?string',
		'area' => '?string',
		'setor' => '?string',
		'cargo' => '?string',
		'funcao' => '?string',
		'municipio' => '?string',
		'id_depto' => '?int',
		'id_area' => '?int',
		'id_setor' => '?int',
		'id_cargo' => '?int',
		'id_funcao' => '?int',
		'foto' => 'string',
		'foto_descricao' => '?string',
		'cabecalho' => '?string',
		'imagem_inicial' => 'string',
		'tipo_tela_inicial' => 'int',
		'imagem_fundo' => '?string',
		'video_fundo' => '?string',
		'assinatura_digital' => '?string',
		'tipo_vinculo' => '?int',
		'rg' => '?string',
		'cpf' => '?string',
		'cnpj' => '?string',
		'pis' => '?string',
		'nome_mae' => '?string',
		'nome_pai' => '?string',
		'telefone' => '?string',
		'email' => 'string',
		'senha' => 'string',
		'token' => 'string',
		'matricula' => '?string',
		'contrato' => '?string',
		'centro_custo' => '?string',
		'nome_banco' => '?string',
		'agencia_bancaria' => '?string',
		'conta_bancaria' => '?string',
		'nome_cartao' => '?string',
		'valor_vt' => '?string',
		'datacadastro' => 'datetime',
		'dataeditado' => '?datetime',
		'data_admissao' => '?datetime',
		'data_demissao' => '?date',
		'tipo_demissao' => '?int',
		'observacoes_demissao' => '?string',
		'nivel_acesso' => 'int',
		'hash_acesso' => '?string',
		'max_colaboradores' => '?int',
		'observacoes_historico' => '?string',
		'observacoes_avaliacao_exp' => '?string',
		'status' => '?int',
		'saldo_apontamentos' => '?time',
		'banco_horas_icom' => '?string',
		'visualizacao_pilula_conhecimento' => '?int'
	];

	//==========================================================================
	public function setToken($value = null)
	{
		if (empty($this->attributes['id'])) {
			$this->attributes['token'] = uniqid();
		}

		return $this;
	}

	//==========================================================================
	public function setHashAcesso($value = null)
	{
		$hashAcesso = json_encode($value);

		if (is_array($value) and $hashAcesso !== false) {
			$this->attributes['hash_acesso'] = $hashAcesso;
		} else {
			$this->attributes['hash_acesso'] = null;
		}

		return $this;
	}

}
