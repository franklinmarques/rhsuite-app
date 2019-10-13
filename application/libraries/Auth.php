<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{

	private static $salt = '@#d13g0tr1nd4d3!';

	private static $emailSystem = 'sistema@rhsuite.com.br';

	private static $emailAdmin = 'mhffortes@hotmail.com';

	private static $emailContact = 'contato@peoplenetcorp.com.br';

	private $_CI;

	private $erro;

	//==========================================================================
	public function __construct()
	{
		$this->_CI = &get_instance();

		log_message('info', 'Auth Class Initialized');
	}

	//==========================================================================
	public function validate($data = [])
	{
		if ($this->_CI->load->is_loaded('form_validation') == false) {
			$this->_CI->load->library('form_validation');
		}

		$this->_CI->form_validation->set_data($data);

		$this->_CI->form_validation->set_rules('email', 'E-mail', 'required|valid_email|max_length[255]');

		if (array_key_exists('senha', $data)) {
			$this->_CI->form_validation->set_rules('senha', 'Senha', 'required|max_length[255]');
		}

		$retorno = $this->_CI->form_validation->run();

		if ($retorno == false) {
			$this->erro = $this->_CI->form_validation->error_string();
		}

		return $retorno;
	}

	//==========================================================================
	public function getUsuario($email, $senha = null)
	{
		$row = $this->_CI->db
			->where(['email' => $email, 'senha' => $senha])
			->get('usuarios')
			->row();

		if (empty($row)) {
			$row = $this->_CI->db
				->select('a.*, b.url, b.cabecalho', false)
				->select("IF(a.nivel_acesso = 'E', 'candidato_externo', 'candidato') AS tipo", false)
				->select('NULL AS hash_acesso', false)
				->join('usuarios b', 'b.id = a.empresa')
				->where(['a.email' => $email, 'a.senha' => $senha])
				->get('recrutamento_usuarios a')
				->row();
		}

		if (empty($row)) {
			$row = $this->_CI->db
				->select('a.*, b.id AS empresa, b.url, b.cabecalho', false)
				->select("'cliente' AS tipo, NULL AS nivel_acesso, NULL AS hash_acesso", false)
				->join('usuarios b', 'b.id = a.id_empresa')
				->where(['a.email' => $email, 'a.senha' => $senha])
				->get('cursos_clientes a')
				->row();
		}

		if (empty($row)) {
			$row = $this->_CI->db
				->select('a.*, b.id AS empresa, b.url, b.cabecalho', false)
				->select("'candidato_externo' AS tipo, NULL AS nivel_acesso, NULL AS hash_acesso", false)
				->join('usuarios b', 'b.id = a.empresa')
				->where(['a.email' => $email, 'a.senha' => $senha])
				->get('candidatos a')
				->row();
		}

		if ($row) {
			return $row;
		}

		return false;
	}

	//==========================================================================
	public function encryptPassword($humanPassword = '')
	{
		return strlen($humanPassword) > 0 ? md5(self::$salt . $humanPassword) : '';
	}

	//==========================================================================
	public function login($data = [])
	{
		$email = $data['email'] ?? '';
		$senha = $data['senha'] ?? '';

		$usuario = $this->getUsuario($email, $senha);

		if (!$usuario) {
			$this->erro = 'Nome de usuário/senha inválidos';
			return false;
		}

		$empresa = $usuario->empresa ? $this->_CI->db->where('id', $usuario->empresa)->get('usuarios')->row() : $usuario;

		if (!$empresa) {
			$this->erro = 'Empresa não encontrada';
			return false;
		}

		if ($usuario->status == 5 || $empresa->status == 5) {
			if ($usuario->empresa) {
				$this->erro = 'Acesso inativo temporariamente.<br/>Favor contatar o gestor da plataforma.<br/>E-mail: <a href="' . $empresa->email . '">' . $empresa->email . '</a>';
			} else {
				$this->erro = 'Acesso inativo temporariamente.<br/>Favor contatar o administrador da plataforma.<br/>E-mail: <a href="contato@peoplenetcorp.com.br">contato@peoplenetcorp.com.br</a>';
			}
			return false;
		}

		if ($usuario->empresa) {
			$usuario->cabecalho = $empresa->cabecalho;
			$usuario->logomarca = $empresa->foto;

			if (strlen($usuario->foto) == 0) {
				$sistema = $this->_CI->db->where('email', self::$emailAdmin)->get('usuarios')->row();
				$usuario->cabecalho = $sistema->cabecalho;
				$usuario->logomarca = $sistema->foto;
			}
		} else {
			if ($usuario->tipo === 'administrador') {
				$sistema = $this->_CI->db->where('email', self::$emailAdmin)->get('usuarios')->row();
				$usuario->empresa = $sistema->id;
			} else {
				$usuario->empresa = $empresa->id;
			}
			$usuario->logomarca = $usuario->foto;
		}

		$ultimoLog = $this->getLastLog($usuario);
		$this->createSession($usuario);
		$this->createLog($ultimoLog);
		$this->clearTempFiles();

		return true;
	}

	//==========================================================================
	public function createSession($data)
	{
		$this->destroySession($data);

		$this->_CI->session->set_userdata([
			'id' => $data->id,
			'empresa' => $data->empresa,
			'nome' => $data->nome,
			'tipo' => $data->tipo,
			'nivel' => $data->nivel_acesso,
			'email' => $data->email,
			'cabecalho' => $data->cabecalho,
			'logomarca' => $data->logomarca,
			'foto' => $data->foto,
			'foto_descricao' => $data->foto_descricao ?? null,
			'logado' => true,
			'hash_acesso' => $data->hash_acesso ? json_decode($data->hash_acesso, true) : [],
			'KCFINDER' => [
				'disabled' => false,
				'uploadURL' => "upload/{$data->id}",
				'uploadDir' => ''
			]
		]);

		$this->_CI->session->set_flashdata('scheduler', true);
	}

	//==========================================================================
	public function updateSession($data)
	{
		if ($this->_CI->session->userdata('logado')) {
			$this->_CI->session->set_userdata([
				'nome' => $data->nome,
				'tipo' => $data->tipo,
				'nivel' => $data->nivel_acesso,
				'email' => $data->email,
				'cabecalho' => $data->cabecalho,
				'logomarca' => $data->logomarca,
				'foto' => $data->foto,
				'foto_descricao' => $data->foto_descricao ?? null,
				'hash_acesso' => $data->hash_acesso ? json_decode($data->hash_acesso, true) : []
			]);
		}
	}

	//==========================================================================
	public function destroySession($data = null)
	{
//		if ($this->_CI->session->userdata('id') === ($data->id ?? 0)) {
//			$this->_CI->session->sess_regenerate($this->_CI->session->userdata('logado') !== true);
//		} else {
		if ($this->_CI->session->userdata('logado'))
			$this->_CI->session->sess_destroy();
//		}
	}

	//==========================================================================
	public function getLastLog($oldData)
	{
		$usuario = $oldData->id ?? null;

		if ($this->_CI->session->userdata('logado')) {
			return $this->_CI->db
				->select('usuario, endereco_ip, agente_usuario')
				->where('usuario', $usuario)
				->where('endereco_ip', $this->_CI->input->ip_address())
				->where('agente_usuario', $this->_CI->input->user_agent())
				->order_by('id', 'desc')
				->limit(1)
				->get('acessosistema')
				->row_array();
		}

		return [
			'usuario' => $usuario,
			'endereco_ip' => null,
			'agente_usuario' => null
		];
	}

	//==========================================================================
	public function createLog($oldData = [])
	{
		$data = [
			'usuario' => $this->_CI->session->userdata('id'),
			'tipo' => $this->_CI->session->userdata('tipo'),
			'data_acesso' => date('Y-m-d H:i:s'),
			'endereco_ip' => $this->_CI->input->ip_address(),
			'agente_usuario' => $this->_CI->input->user_agent(),
			'id_sessao' => session_id()
		];

		if ($data['usuario'] == $oldData['usuario'] and
			$data['endereco_ip'] == $oldData['endereco_ip'] and
			$data['agente_usuario'] == $oldData['agente_usuario']) {

			$oldData['data_acesso'] = $data['data_acesso'];
			$oldData['data_atualizacao'] = null;
			$oldData['data_saida'] = null;
			$oldData['id_sessao'] = $data['id_sessao'];
			$this->_CI->db->update('acessosistema', $oldData, ['id' => $data['usuario']]);
		} else {
			$this->_CI->db->insert('acessosistema', $data);
		}
	}

	//==========================================================================
	public function finalizeLog()
	{
		$log = $this->_CI->db
			->select('id')
			->where('usuario', $this->_CI->session->userdata('id'))
			->order_by('id', 'desc')
			->get('acessosistema')
			->row();

		$this->_CI->db->update('acessosistema', ['data_saida' => date('Y-m-d H:i:s')], ['id' => $log->id]);
	}

	//==========================================================================
	public function clearTempFiles()
	{
		if ($this->_CI->session->userdata('tipo') !== 'candidato') {
			$rows = $this->_CI->db
				->where('usuario', $this->_CI->session->userdata('id'))
				->get('arquivos_temp')
				->result();

			foreach ($rows as $row) {
				unlink($row->arquivo);
				$this->_CI->db->where('id', $row->id)->delete('arquivos_temp');
			}
		}
	}

	//==========================================================================
	public function errors()
	{
		return $this->erro;
	}

	//==========================================================================
	public function logout()
	{
		$empresa = $this->_CI->db
			->select('url')
			->where('id', $this->_CI->session->userdata('empresa'))
			->get('usuarios')
			->row();

		$this->finalizeLog();
		$this->clearTempFiles();
		$this->destroySession();

		if (!empty($empresa->url)) {
			$this->_CI->config->set_item('index_page', $empresa->url);
		}

		redirect(site_url('login'));
	}

}

