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

		if (!empty($data['possui_apontamento_horas'])) {
			$usuario = $this->auth->getUsuario($data['email'], $data['senha']);

			if (!$usuario) {
				exit(json_encode(['retorno' => 0, 'aviso' => 'Nome de usuário/senha inválidos']));
			} elseif (empty($usuario->possui_apontamento_horas)) {
				exit(json_encode(['retorno' => 0, 'aviso' => 'E-mail não autorizado a apontar entrada/saída.']));
			}

			$query = http_build_query(['token' => $usuario->token]);
			$uri = $this->uri->segment(1) == 'login' ? '' : $this->uri->segment(1) . '/';
			echo json_encode([
				'retorno' => 1,
				'aviso' => 'Preparando apontamento de horas',
				'redireciona' => 1,
				'pagina' => site_url($uri . 'login/solicitarNovoApontamento/?' . $query)
			]);
			return;
		}


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
	public function solicitarNovoApontamento()
	{
		$get = $this->input->get();
		$usuario = $this->db->where('token', $get['token'])->get('usuarios')->row();

		if (empty($usuario) or isset($usuario->possui_apontamento_horas) == false) {
			redirect(site_url('login'));
		}

		$empresa = $this->db->where('id', $usuario->empresa)->get('usuarios')->row();

		if ($empresa) {
			$usuario->logoempresa = $empresa->url;
			$usuario->logo = $empresa->foto;
			$usuario->cabecalho = $empresa->cabecalho;
			$usuario->imagem_fundo = $empresa->imagem_fundo;
			$usuario->video_fundo = $empresa->video_fundo;
			$usuario->visualizacao_pilula_conhecimento = $empresa->visualizacao_pilula_conhecimento;
			$usuario->area_conhecimento = null;
			$usuario->tema = null;
		} else {
			$usuario->logoempresa = $usuario->url;
			$usuario->logo = $usuario->foto;
			$usuario->area_conhecimento = null;
			$usuario->tema = null;
		}


		$uri = $this->uri->segment(1);
		if ($uri != 'login') {
			$row = $this->db->where('url', $uri)->get('usuarios')->row();

			if ($row) {
				$usuario->logoempresa = $row->url;
				$usuario->logo = $row->foto;
				$usuario->cabecalho = $row->cabecalho;
				$usuario->imagem_fundo = $row->imagem_fundo;
				$usuario->video_fundo = $row->video_fundo;
				$usuario->visualizacao_pilula_conhecimento = $row->visualizacao_pilula_conhecimento;
			} else {
				show_404();
			}
			$usuario->login = 'ame/' . $uri . '/login';
		} else {
			$usuario->login = 'ame/' . $uri;
		}

		if ($usuario->visualizacao_pilula_conhecimento) {
			$areasConhecimento = $this->db
				->select('a.id, a.nome')
				->join('cursos_pilulas b', 'b.id_area_conhecimento = a.id AND b.publico = 1')
				->order_by('a.nome', 'asc')
				->get('cursos_pilulas_areas a')
				->result();

			$usuario->area_conhecimento = ['' => 'selecione...'] + array_column($areasConhecimento, 'nome', 'id');

			$tema = $this->db
				->select('a.id, a.nome')
				->join('cursos_pilulas b', 'b.id_curso = a.id AND b.publico = 1')
				->where('b.id_area_conhecimento', null)
				->order_by('a.nome', 'asc')
				->get('cursos a')
				->result();

			$usuario->tema = ['' => 'selecione...'] + array_column($tema, 'nome', 'id');
		}

		$this->load->view('login_apontamento_horas', $usuario);
	}


	//==========================================================================
	public function registrarApontamento()
	{
		header('Content-type: text/json');

		$post = $this->input->post();
		$usuario = $this->db
			->select('a.*', false)
			->select('b.nome AS nome_depto, c.nome AS nome_area, d.nome AS nome_setor')
			->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto', 'left')
			->join('empresa_areas c', 'c.id = a.id_area OR c.nome = a.area', 'left')
			->join('empresa_setores d', 'd.id = a.id_setor OR d.nome = a.setor', 'left')
			->where('a.token', $post['token'])
			->get('usuarios a')
			->row();

		if (!$usuario) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'Usuário não encontrado ou token expirado']));
		} elseif (empty($usuario->possui_apontamento_horas)) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'E-mail não autorizado a apontar entrada/saída.']));
		}

		$post['id_usuario'] = $usuario->id;
		$post['id_depto'] = $usuario->id_depto;
		$post['id_area'] = $usuario->id_area;
		$post['id_setor'] = $usuario->id_setor;


		if (!isset($post['modo_cadastramento'])) {
			$post['modo_cadastramento'] = 'A';
			$post['data_hora'] = date('Y-m-d H:i:s');
			$data = date('d/m/Y');
			$hora = date('H');
			$min = date('i');
		} else {
			$timestampDate = strtotime(str_replace('/', '-', $post['data']));
			$data = date('d/m/Y', $timestampDate);
			$dia = (int)date('d', $timestampDate);
			$mes = (int)date('m', $timestampDate);
			$ano = (int)date('Y', $timestampDate);
			if (checkdate($mes, $dia, $ano) == false) {
				exit(json_encode(['retorno' => 0, 'aviso' => 'O campo Data contém valor inválido.']));
			}

			$horario = explode(':', $post['hora']);
			$hora = $horario[0] ?? null;
			$min = $horario[1] ?? null;
			if ($hora > 23 or $min > 59 or strlen($hora) !== 2 or strlen($min) !== 2) {
				exit(json_encode(['retorno' => 0, 'aviso' => 'O campo Hora contém valor inválido.']));
			}

			$post['data_hora'] = date('Y-m-d H:i:s', mktime((int)$hora, (int)$min, 0, $mes, $dia, $ano));
		}

		if (strlen($post['justificativa']) == 0) {
			$post['justificativa'] = null;
		}

		unset($post['token']);
		unset($post['data']);
		unset($post['hora']);

		$this->db->trans_start();
		$this->db->insert('usuarios_apontamento_horas', $post);
		$this->db->update('usuarios', ['token' => uniqid()], ['id' => $usuario->id]);
		$this->db->trans_complete();

		if ($this->db->trans_status() == false) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'Não foi possível salvar o apontamento de horas.']));
		}

		$this->load->library('email');
		$this->email->set_mailtype('html');

		$this->email->from('sistema@rhsuite.com.br', 'RhSuite');
		$this->email->to($usuario->email);
		$this->email->subject('LMS - Apontamento de horas');

		$email = [
			'usuario' => $usuario->nome,
			'turno' => $post['turno_evento'] == 'S' ? 'término' : 'início',
			'data' => $data,
			'hora' => $hora,
			'minuto' => $min,
			'depto' => $usuario->nome_depto,
			'area' => $usuario->nome_area,
			'setor' => $usuario->nome_setor
		];
		$this->email->message($this->load->view('controle_frequencias_email', $email, true));

		if ($this->email->send() == false) {
			exit(json_encode(['retorno' => 0, 'aviso' => 'Erro ao enviar e-mail, tente novamente, se o erro persistir entre em contato com o administrador']));
		}

		$dados = [
			'usuario' => $usuario->nome,
			'turno' => $post['turno_evento'] == 'E' ? 'Início ' : ($post['turno_evento'] == 'S' ? 'Término ' : ''),
			'data_hora' => date('d/m/Y - H:i', strtotime($post['data_hora']))
		];

		echo json_encode([
			'retorno' => 1,
			'aviso' => 'Apontamento realizado com sucesso!',
			'data' => $dados
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
