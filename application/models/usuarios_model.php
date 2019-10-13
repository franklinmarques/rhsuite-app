<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends MY_Model
{
	protected static $table = 'usuarios';

	protected static $createdField = 'datacadastro';

	protected static $updatedField = 'dataeditado';

	protected $validationRules = [
		'id' => 'required|is_natural_no_zero|max_length[11]',
		'empresa' => 'is_natural_no_zero|max_length[11]',
		'tipo' => 'required|max_length[20]',
		'url' => 'required|max_length[255]',
		'nome' => 'required|max_length[255]',
		'data_nascimento' => 'valid_date',
		'sexo' => 'max_length[1]',
		'depto' => 'max_length[255]',
		'area' => 'max_length[255]',
		'setor' => 'max_length[255]',
		'cargo' => 'max_length[255]',
		'funcao' => 'max_length[255]',
		'municipio' => 'max_length[30]',
		'id_depto' => 'is_natural_no_zero|max_length[11]',
		'id_area' => 'is_natural_no_zero|max_length[11]',
		'id_setor' => 'is_natural_no_zero|max_length[11]',
		'id_cargo' => 'is_natural_no_zero|max_length[11]',
		'id_funcao' => 'is_natural_no_zero|max_length[11]',
		'foto' => 'required|max_length[255]',
		'foto_descricao' => 'max_length[255]',
		'cabecalho' => 'max_length[65535]',
		'imagem_inicial' => 'required|max_length[65535]',
		'tipo_tela_inicial' => 'required|numeric|max_length[1]',
		'imagem_fundo' => 'max_length[65535]',
		'video_fundo' => 'max_length[65535]',
		'assinatura_digital' => 'max_length[65535]',
		'tipo_vinculo' => 'integer|max_length[1]',
		'rg' => 'max_length[13]',
		'cpf' => 'max_length[14]',
		'cnpj' => 'max_length[18]',
		'pis' => 'max_length[14]',
		'nome_mae' => 'max_length[255]',
		'nome_pai' => 'max_length[255]',
		'telefone' => 'max_length[255]',
		'email' => 'required|max_length[255]',
		'senha' => 'required|max_length[32]',
		'token' => 'required|max_length[255]',
		'matricula' => 'max_length[255]',
		'contrato' => 'max_length[255]',
		'centro_custo' => 'max_length[255]',
		'nome_banco' => 'max_length[30]',
		'agencia_bancaria' => 'max_length[15]',
		'conta_bancaria' => 'max_length[15]',
		'nome_cartao' => 'max_length[100]',
		'valor_vt' => 'max_length[100]',
		'datacadastro' => 'required|valid_datetime',
		'dataeditado' => 'valid_datetime',
		'data_admissao' => 'valid_datetime',
		'data_demissao' => 'valid_date',
		'tipo_demissao' => 'integer|max_length[11]',
		'observacoes_demissao' => 'max_length[4294967295]',
		'nivel_acesso' => 'required|integer|max_length[11]',
		'hash_acesso' => 'max_length[4294967295]',
		'max_colaboradores' => 'integer|max_length[11]',
		'observacoes_historico' => 'max_length[4294967295]',
		'observacoes_avaliacao_exp' => 'max_length[4294967295]',
		'status' => 'integer|max_length[2]',
		'saldo_apontamentos' => 'valid_time',
		'banco_horas_icom' => 'max_length[10]',
		'visualizacao_pilula_conhecimento' => 'numeric|max_length[1]'
	];

	protected $uploadConfig = [
		'foto' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png'],
		'foto_descricao' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png'],
		'imagem_inicial' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png'],
		'imagem_fundo' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'jpg|png'],
		'video_fundo' => ['upload_path' => './videos/usuarios/', 'allowed_types' => 'mp4'],
		'assinatura_digital' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png']
	];

	protected $beforeInsert = ['encriptarSenha', 'gerarToken'];

	protected $beforeUpdate = ['encriptarSenha'];

	protected static $tipo = [
		'administrador' => 'administrador',
		'empresa' => 'empresa',
		'funcionario' => 'funcionario',
		'selecionador' => 'selecionador'
	];

	protected static $sexo = ['M' => 'Masculino', 'F' => 'Feminino'];

	protected static $tipoTelaInicial = [
		'1' => 'Imagem padrão',
		'2' => 'Vídeo padrão',
		'3' => 'Imagem personalizada',
		'4' => 'Vídeo personalizado'
	];

	protected static $tipoVinculo = [
		'1' => 'CLT',
		'2' => 'MEI',
		'3' => 'PJ',
		'4' => 'Autônomo'
	];

	protected static $tipoDemissao = [
		'1' => 'Demissão sem justa causa',
		'2' => 'Demissão por justa causa',
		'3' => 'Pedido de demissão',
		'4' => 'Término do contrato',
		'5' => 'Rescisão antecipada pelo empregado',
		'6' => 'Rescisão antecipada pelo empregador',
		'7' => 'Desistiu da vaga',
		'8' => 'Rescisão estagiário',
		'9' => 'Rescisão por acordo'
	];

	protected static $nivelAcesso = [
		'1' => 'Administrador',
		'7' => 'Presidente',
		'18' => 'Diretor',
		'8' => 'Gerente',
		'9' => 'Coordenador',
		'15' => 'Representante',
		'10' => 'Supervisor',
		'19' => 'Supervisor requisitante',
		'11' => 'Encarregado',
		'12' => 'Líder',
		'4' => 'Colaborador CLT',
		'16' => 'Colaborador MEI',
		'14' => 'Colaborador PJ',
		'13' => 'Cuidador Comunitário',
		'3' => 'Gestor',
		'2' => 'Multiplicador',
		'6' => 'Selecionador',
		'5' => 'Cliente',
		'17' => 'Vistoriador'
	];

	protected static $status = [
		'1' => 'Ativo',
		'2' => 'Inativo',
		'3' => 'Em experiência',
		'4' => 'Em desligamento',
		'5' => 'Desligado',
		'6' => 'Afastado (maternidade)',
		'7' => 'Afastado (aposentadoria)',
		'8' => 'Afastado (doença)',
		'9' => 'Afastado (acidente)',
		'10' => 'Desistiu da vaga'
	];

	//==========================================================================
	protected function encriptarSenha($data)
	{
		if (array_key_exists('senha', $data['data'] ?? []) === false) {
			return $data;
		}

		if (strlen($data['data']['senha']) > 0) {
			if ($this->load->is_loaded('Auth') == false) {
				$this->load->library('Auth');
			}

			$data['data']['senha'] = $this->auth->encryptPassword($data['data']['senha']);
		} else {
			unset($data['data']['senha']);
		}

		return $data;
	}

	//==========================================================================
	protected function gerarToken($data)
	{
		if (array_key_exists('data', $data) == false) {
			return $data;
		}

		$data['data']['token'] = uniqid();

		return $data;
	}

}
