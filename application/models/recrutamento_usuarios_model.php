<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_usuarios_model extends MY_Model
{
    protected static $table = 'recrutamento_usuarios';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'empresa' => 'required|is_natural_no_zero|max_length[11]',
        'nome' => 'required|max_length[255]',
        'data_nascimento' => 'valid_date',
        'sexo' => 'in_list[M,F]',
        'estado_civil' => 'required|is_natural_no_zero|less_than_equal_to[6]',
        'nome_mae' => 'max_length[255]',
        'nome_pai' => 'max_length[255]',
        'cpf' => 'valid_cpf|is_unique[recrutamento_usuarios.cpf]',
        'rg' => 'valid_rg|is_unique[recrutamento_usuarios.rg]',
        'pis' => 'valid_pis|is_unique[recrutamento_usuarios.pis]',
        'logradouro' => 'max_length[255]',
        'numero' => 'is_natural_no_zero|max_length[11]',
        'complemento' => 'max_length[255]',
        'bairro' => 'max_length[50]',
        'cidade' => 'is_natural_no_zero|max_length[11]',
        'estado' => 'is_natural_no_zero|max_length[2]',
        'cep' => 'valid_cep',
        'escolaridade' => 'is_natural_no_zero|max_length[11]',
        'deficiencia' => 'is_natural_no_zero|max_length[11]',
        'foto' => 'uploaded[foto]|mime_in[foto.gif,jpg,png]|max_length[255]',
        'telefone' => 'required|max_length[255]',
        'email' => 'valid_email|is_unique[recrutamento_usuarios.email]|max_length[255]',
        'senha' => 'max_length[32]',
        'token' => 'required|is_unique[recrutamento_usuarios.token]|max_length[255]',
        'data_inscricao' => 'valid_datetime|after_date[data_nascimento]',
        'fonte_contratacao' => 'max_length[30]',
        'resumo_cv' => 'max_length[4294967295]',
        'data_edicao' => 'valid_datetime|after_date[data_nascimento]|after_date[data_inscricao]',
        'nivel_acesso' => 'required|in_list[C]',
        'observacoes' => 'max_length[4294967295]',
        'arquivo_curriculo' => 'uploaded[arquivo_curriculo]|mime_in[arquivo_curriculo.pdf]|max_length[255]',
        'status' => 'required|in_list[A,E]'
    ];

    protected $uploadConfig = [
        'foto' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png'],
        'arquivo_curriculo' => ['upload_path' => './arquivos/curriculos/', 'allowed_types' => 'pdf']
    ];

    protected static $sexo = ['M' => 'Masculino', 'F' => 'Feminino'];

    protected static $estadoCivil = [
        '1' => 'Solteiro(a)',
        '2' => 'Casado(a)',
        '3' => 'Desquitado(a)',
        '4' => 'Divorciado(a)',
        '5' => 'Viúvo(a)',
        '6' => 'Outro'
    ];

    protected static $nivelAcesso = ['C' => 'Candidato'];

    protected static $status = ['A' => 'Ativo', 'E' => 'Excluído'];

}
