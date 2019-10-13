<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('auth', ['url' => $this->uri->segment(3)]);
	}

	//==========================================================================
	public function index()
	{
		$data = [
			'logoempresa' => '',
			'logo' => '',
			'cabecalho' => '',
			'imagem_fundo' => '',
			'video_fundo' => '',
			'visualizacao_pilula_conhecimento' => [],
			'area_conhecimento' => [],
			'tema' => '',
		];

		$uri = $this->uri->segment(1);

		if ($uri != 'login') {
			$row = $this->db->where('url', $uri)->get('usuarios')->row();

			if ($row) {
				$data['logoempresa'] = $row->url;
				$data['logo'] = $row->foto;
				$data['cabecalho'] = $row->cabecalho;
				$data['imagem_fundo'] = $row->imagem_fundo;
				$data['video_fundo'] = $row->video_fundo;
				$data['visualizacao_pilula_conhecimento'] = $row->visualizacao_pilula_conhecimento;
			} else {
				show_404();
			}
		}

		if ($data['visualizacao_pilula_conhecimento']) {
			$areasConhecimento = $this->db
				->select('a.id, a.nome')
				->join('cursos_pilulas b', 'b.id_area_conhecimento = a.id AND b.publico = 1')
				->order_by('a.nome', 'asc')
				->get('cursos_pilulas_areas a')
				->result();

			$data['area_conhecimento'] = ['' => 'selecione...'] + array_column($areasConhecimento, 'nome', 'id');

			$tema = $this->db
				->select('a.id, a.nome')
				->join('cursos_pilulas b', 'b.id_curso = a.id AND b.publico = 1')
				->where('b.id_area_conhecimento', null)
				->order_by('a.nome', 'asc')
				->get('cursos a')
				->result();

			$data['tema'] = ['' => 'selecione...'] + array_column($tema, 'nome', 'id');
		}

		$this->load->view('login', $data);
	}

	//==========================================================================
	public function autenticacao_json()
	{
		header('Content-type: text/json');

		$data = $this->input->post();

		if ($this->auth->validate($data) == false) {
			exit(json_encode(['retorno' => 0, 'aviso' => $this->auth->errors()]));
		}

		$data['senha'] = $this->auth->encryptPassword($data['senha']);

		if ($this->auth->login($data) == false) {
			exit(json_encode(['retorno' => 0, 'aviso' => $this->auth->errors()]));
		}

		echo json_encode([
			'retorno' => 1,
			'aviso' => 'Login efetuado com sucesso!',
			'redireciona' => 1,
			'pagina' => site_url('home')
		]);
	}

	//==========================================================================
	public function recuperarSenha()
	{
		header('Content-type: text/json');

		$data = $this->input->post();

		if ($this->auth->validate($data) == false) {
			exit(json_encode(['retorno' => 0, 'aviso' => $this->auth->errors()]));
		}

		$result = $this->auth->find();

		$data['email'] = trim($this->input->post('email'));

		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));
		}

		$result = $this->db->query("SELECT * FROM usuarios WHERE email = ?", $data);

		if ($result->num_rows() == 0) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Não existe nenhum usuário cadastrado com esse endereço de e-mail')));
		}

		$usuario = $result->row();

		$id = $usuario->id;
		$token = uniqid();

		$this->config->set_item('index_page', $usuario->url);

		if (!$this->db->where('id', $id)->update('usuarios', array('token' => $token))) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar token, tente novamente, se o erro persistir entre em contato com o administrador')));
		}

		$this->load->library('email');

		$this->email->from('sistema@rhsuite.com.br', 'RhSuite');
		$this->email->to($usuario->email);
		$this->email->subject('LMS - Redefinição de senha');

		$urlAlterarSenha = site_url('home/alterarsenha/' . $token);
		$mensagem = "<p style='text-align: center;'>
                        <h1>LMS</h1>
                    </p>
                    <hr/>
                    <p>Prezado(a) {$usuario->nome},</p>
                    <p>Para alterar sua senha, acesso o endereço abaixo</p>
                    <p><a href='{$urlAlterarSenha}'>{$urlAlterarSenha}</a></p>
                    <p>Caso não tenha solicitado a alteração de senha, ignore este e-mail.</p>";

		$this->email->message($mensagem);

		if ($this->email->send() == false) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'Erro ao enviar e-mail, tente novamente, se o erro persistir entre em contato com o administrador']));
		}

		echo json_encode(['retorno' => 1, 'aviso' => 'Foi enviado um e-mail com endereço de redefinição de senha']);
	}

	//==========================================================================
	public function filtrarTemas()
	{
		$temas = $this->db
			->select('a.id, a.nome')
			->join('cursos_pilulas b', 'b.id_curso = a.id AND b.publico = 1')
			->where('b.id_area_conhecimento', $this->input->post('area_conhecimento'))
			->order_by('a.nome', 'asc')
			->get('cursos a')
			->result();

		$temas = ['' => 'selecione...'] + array_column($temas, 'nome', 'id');

		$data['tema'] = form_dropdown('', $temas, $this->input->post('tema'));

		echo json_encode($data);
	}

	//==========================================================================
	public function mostrarPilulaConhecimento()
	{
		$data = $this->db
			->select('conteudo')
			->where('id_curso', $this->input->post('tema'))
			->get('ead_paginas')
			->row();

		if (empty($data)) {
			exit(json_encode(['erro' => $this->paginas->errors()]));
		}

		echo json_encode($data);
	}

}
