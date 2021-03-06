<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form'));
        if (!$this->session->userdata('logado') && !in_array($this->uri->segment(2), array('login', 'autenticacao_json', 'cadastro_json', 'recuperarsenha_json', 'alterarsenha', 'alterarsenha_json'))) {
            redirect(base_url('home/login'));
        }
        $this->load->model('Usuarios_model', 'usuarios');
    }

    public function login()
    {
        $this->load->view('login');
    }

    public function autenticacao_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $data['email'] = trim($_POST['email']);
        $data['senha'] = trim($_POST['senha']);

        if (empty($data['email']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O e-mail não pode ficar em branco')));
        if (empty($data['senha']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A senha não pode ficar em branco')));

        $data['senha'] = $this->usuarios->setPassword($data['senha']);

        $login = $this->db->query("SELECT u.* FROM usuarios u
                                   WHERE u.email = ? AND u.senha = ?", $data);

        if ($login->num_rows == 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Nome de usuário / senha inválidos')));

        $login = $login->row();
        $status_usuario = $login->status;

        if ($login->empresa > 0) {
            $empresa_status = $this->db->query("SELECT u.* FROM usuarios u
                                   WHERE u.id = ?", $login->empresa)->row();

            $login->status = $empresa_status->status;
        } else {
            $login->empresa = $login->id;
        }

        if ($login->status != 1 || $status_usuario != 1)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Acesso inativo temporariamente. <br /> Favor contatar o administrador da plataforma. <br /> E-mail: <a href="mailto:contato@peoplenetcorp.com.br">contato@peoplenetcorp.com.br</a>')));

        session_start();
        $_SESSION['KCFINDER'] = array();
        $_SESSION['KCFINDER']['disabled'] = false;
        $_SESSION['KCFINDER']['uploadURL'] = "upload/{$login->id}";


        // ** => Cabeçalho
        $cabecalho = $login->cabecalho;
        $logomarca = $login->foto;

        if ($login->empresa > 0) {
            $cabecalho = $this->db->query("SELECT u.cabecalho, u.foto FROM usuarios u
                                           WHERE u.id = ?", $login->empresa);
            $rows = $cabecalho->row();

            $cabecalho = $rows->cabecalho;
            $logomarca = $rows->foto;

            // Se não existir, usa a logo do sistema
            if (!$logomarca) {
                $sistema = $this->db->query("SELECT u.cabecalho, u.foto FROM usuarios u
                                           WHERE u.email = ?", 'mhffortes@hotmail.com');
                $sistema = $sistema->row();
                $logomarca = $sistema->foto;
            }
        }

        $this->session->set_userdata(array('cabecalho' => $cabecalho, 'logomarca' => $logomarca));
        // ** => Cabeçalho

        $this->session->set_userdata(array('logado' => true, 'id' => $login->id, 'tipo' => $login->tipo, 'empresa' => $login->empresa, 'nome' => $login->nome, 'foto' => $login->foto, 'email' => $login->email));

        # Insere a data e hora da login
        $dados['usuario'] = $this->session->userdata('id');
        $dados['data_acesso'] = mdate("%Y-%m-%d %H:%i:%s");

        $this->db->query($this->db->insert_string('acessosistema', $dados));

        echo json_encode(array('retorno' => 1, 'aviso' => 'Login efetuado com sucesso!', 'redireciona' => 1, 'pagina' => base_url('home')));
    }

    public function recuperarsenha_json()
    {
        header('Content-type: text/json');

        $data['email'] = $_POST['email'];

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));

        $usuario = $this->db->query("SELECT * FROM usuarios WHERE email = ?", $data);

        if ($usuario->num_rows == 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Não existe nenhum usuário cadastrado com esse endereço de e-mail')));

        $usuario = $usuario->row();

        $id = $usuario->id;
        $nome = $usuario->nome;
        $email = $usuario->email;
        $token = uniqid();

        $urlAlterarSenha = base_url('home/alterarsenha/' . $token);

        if (!$this->db->where('id', $id)->update('usuarios', array('token' => $token)))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar token, tente novamente, se o erro persistir entre em contato com o administrador')));

        $this->load->helper('phpmailer');
        $assunto = "LMS - Redefinição de senha";
        $mensagem = "<center>
                        <h1>LMS</h1>
                    </center>
                    <hr />
                    <p>Prezado(a) {$nome},</p>
                    <p>Para alterar sua senha, acesso o endereço abaixo</p>
                    <p><a href='{$urlAlterarSenha}'>{$urlAlterarSenha}</a></p>
                    <p>Caso não tenha solicitado a alteração de senha, ignore este e-mail.</p>";
        if (send_email($nome, $email, $assunto, $mensagem))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Foi enviado um e-mail com endereço de redefinição de senha'));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao enviar e-mail, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function alterarsenha()
    {
        $data['token'] = $this->uri->segment(3);

        if (empty($data['token']))
            redirect(base_url('home'));

        $usuario = $this->db->query("SELECT * FROM usuarios WHERE token = ?", $data);
        if ($usuario->num_rows == 0)
            redirect(base_url('home'));

        $usuario = $usuario->row(0);

        $data['nome'] = $usuario->nome;

        $this->load->view('alterarsenha', $data);
    }

    public function alterarsenha_json()
    {
        header('Content-type: text/json');
        $this->load->helper('date');

        $data['token'] = $this->uri->segment(3);
        $data['novotoken'] = uniqid();
        $data['novasenha'] = trim($_POST['novasenha']);
        $data['confirmarsenha'] = trim($_POST['confirmarsenha']);

        if (empty($data['token']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O token não pode ficar em branco')));

        if (empty($data['novasenha']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A nova senha não pode ficar em branco')));
        if (empty($data['confirmarsenha']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo confirmar senha não pode ficar em branco')));
        if ($data['novasenha'] != $data['confirmarsenha'])
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A senha não pode ser diferente da confirmar senha')));

        $data['senha'] = $this->usuarios->setPassword($data['novasenha']);

        $usuario = $this->db->query("SELECT * FROM usuarios WHERE token = ?", $data['token']);
        if ($usuario->num_rows == 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Não existe nenhum usuário cadastrado com esse token')));

        $usuario = $usuario->row();

        if ($this->db->where('id', $usuario->id)->update('usuarios', array('senha' => $data['senha'], 'token' => $data['novotoken'], 'dataeditado' => mdate("%Y-%m-%d %H:%i:%s"))))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Senha alterada com sucesso', 'redireciona' => 1, 'pagina' => base_url('home')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao alterar senha, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function meuperfil()
    {
        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", $this->session->userdata('id'))->row(0);

        $this->load->view('meuperfil', $data);
    }

    public function editarmeuperfil_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $usuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", $this->session->userdata('id'))->row(0);

        $data['cabecalho'] = "";

        $data['nome'] = $_POST['nome'];

        if (isset($_POST['cabecalho'])) {
            $data['cabecalho'] = $_POST['cabecalho'];
        }

        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");

        if (empty($data['nome']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Nome" não pode ficar em branco')));

        if ($_POST['senhaantiga'] != '') {
            if ($usuario->senha != $this->usuarios->setPassword($_POST['senhaantiga']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'A senha antiga não confere!')));

            if (empty($_POST['novasenha']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Nova Senha" não pode ficar em branco')));

            if ($_POST['novasenha'] != $_POST['confirmarnovasenha'])
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Nova Senha" não confere com o "Confirmar Nova Senha"')));

            $data['senha'] = $this->usuarios->setPassword($_POST['novasenha']);
        }

        if (!empty($_FILES['foto'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data();
                $data['foto'] = $foto['file_name'];
                if ($usuario->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $usuario->foto) && $usuario->foto != $data['foto'])
                    @unlink('./imagens/usuarios/' . $usuario->foto);
                $this->session->set_userdata(array('foto' => $data['foto']));
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if (!empty($data['cabecalho'])) {
            $this->session->set_userdata(array('cabecalho' => $data['cabecalho']));
        }

        if ($this->db->where('id', $this->session->userdata('id'))->update('usuarios', $data))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Meu perfil foi editado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar meu perfil, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function sair()
    {
        $this->load->helper(array('date'));

        # Pega o id da empresa
        $empresa = $this->session->userdata('id');

        if ($this->session->userdata('empresa') > 0) {
            $empresa = $this->session->userdata('empresa');
        }

        # Insere a data e hora do logout
        $id_usuario = $this->session->userdata('id');
        $data_saida = mdate("%Y-%m-%d %H:%i:%s");

        $ultimo_acesso = $this->db->query("SELECT * FROM acessosistema WHERE usuario = ?
                                           ORDER BY id DESC LIMIT 1
                                          ", $id_usuario);
        foreach ($ultimo_acesso->result() as $linha) {
            $this->db->where('id', $linha->id)->update('acessosistema', array('data_saida' => $data_saida));
        }

        # Apaga as sessões
        session_start();
        session_destroy();
        $this->session->sess_destroy();

        # Verifica se existe url no banco
        $url_empresa = $this->db->query("SELECT url FROM usuarios WHERE id = ?", $empresa);
        $empresa = $url_empresa->row();

        # Se existir, redireciona
        if ($empresa->url) {
            redirect(base_url($empresa->url . '/login'));
        } else {
            redirect(base_url('home/login'));
        }
    }

    public function index()
    {
        setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
        date_default_timezone_set('America/Sao_Paulo');
        $this->load->helper(array('date'));

        if ($this->session->userdata('tipo') == "administrador") {
            $data['usuarios'] = $this->db->query('SELECT * FROM usuarios');
            $this->load->view('index_administrador', $data);
        } else if ($this->session->userdata('tipo') == "empresa") {
            $data['usuarios'] = $this->db->query('SELECT * FROM usuarios WHERE empresa = ?', $this->session->userdata('id'));
            $this->load->view('index_empresa', $data);
        } else if ($this->session->userdata('tipo') == "funcionario") {
            $data['usuarios'] = $this->db->query('SELECT * FROM usuarios WHERE empresa = ?', $this->session->userdata('empresa'));
            $this->load->view('index_funcionario', $data);
        }
    }

    public function novabiblioteca()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");
        $this->load->view('novabiblioteca', $data);
    }

    public function novabiblioteca_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $data['usuario'] = $this->session->userdata('id');
        $data['titulo'] = $_POST['titulo'];
        $data['tipo'] = $_POST['tipo'];
        $data['categoria'] = $_POST['categoria'];
        $data['descricao'] = $_POST['descricao'];
        $data['link'] = $_POST['link'];
        $data['disciplina'] = $_POST['disciplina'];
        $data['anoserie'] = $_POST['anoserie'];
        $data['temacurricular'] = $_POST['temacurricular'];
        $data['uso'] = $_POST['uso'];
        $data['licenca'] = $_POST['licenca'];
        $data['produzidopor'] = $_POST['produzidopor'];
        $data['tags'] = $_POST['tags'];
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        if (empty($data['titulo']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O título não pode ficar em branco')));

        if (empty($data['tipo']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O tipo não pode ficar em branco')));

        if (empty($data['categoria']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A categoria não pode ficar em branco')));

        if (empty($data['link']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O link não pode ficar em branco')));

        if (!filter_var($data['link'], FILTER_VALIDATE_URL))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O link informado não é válido')));

        if ($this->db->query($this->db->insert_string('biblioteca', $data)))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro da biblioteca efetuada com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/biblioteca')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro da biblioteca, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function biblioteca()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $this->load->view('biblioteca');
    }

    public function biblioteca_html()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $this->load->library('pagination');

        $query = $_POST['busca'];

        $qryWHERE = 'WHERE 1 = ? ';
        $dataWHERE[] = 1;

        if (!empty($query)) {
            $qryWHERE .= 'AND (titulo LIKE ? OR disciplina LIKE ? OR anoserie LIKE ? OR temacurricular LIKE ?)';
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/biblioteca_html');
        $config['total_rows'] = $this->db->query("SELECT * FROM biblioteca {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("3"))) ? 0 : intval($this->uri->segment("3"));
        $dataWHERE[] = $config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM biblioteca WHERE 1 = ?", array(1))->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM biblioteca {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getbiblioteca', $data);
    }

    public function editarbiblioteca()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $data['row'] = $this->db->query("SELECT * FROM biblioteca WHERE id = ?", array($this->uri->segment(3)))->row(0);
        $data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");
        $this->load->view('editarbiblioteca', $data);
    }

    public function editarbiblioteca_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $data['titulo'] = $_POST['titulo'];
        $data['tipo'] = $_POST['tipo'];
        $data['categoria'] = $_POST['categoria'];
        $data['descricao'] = $_POST['descricao'];
        $data['link'] = $_POST['link'];
        $data['disciplina'] = $_POST['disciplina'];
        $data['anoserie'] = $_POST['anoserie'];
        $data['temacurricular'] = $_POST['temacurricular'];
        $data['uso'] = $_POST['uso'];
        $data['licenca'] = $_POST['licenca'];
        $data['produzidopor'] = $_POST['produzidopor'];
        $data['tags'] = $_POST['tags'];
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");

        if (empty($data['titulo']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O título não pode ficar em branco')));

        if (empty($data['tipo']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O tipo não pode ficar em branco')));

        if (empty($data['categoria']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A categoria não pode ficar em branco')));

        if (empty($data['link']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O link não pode ficar em branco')));

        if (!filter_var($data['link'], FILTER_VALIDATE_URL))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O link informado não é válido')));

        if ($this->db->where('id', $this->uri->segment(3))->update('biblioteca', $data))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Biblioteca editada com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/biblioteca')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar biblioteca, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function getempresas()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $this->load->library('pagination');

        $query = $_POST['busca'];

        $qryWHERE = 'WHERE tipo = ? ';
        $dataWHERE[] = "empresa";

        if (!empty($query)) {
            $qryWHERE .= 'AND (nome LIKE ? OR email LIKE ?)';
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getempresas');
        $config['total_rows'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("3"))) ? 0 : intval($this->uri->segment("3"));
        $dataWHERE[] = $config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM usuarios WHERE tipo = ?", array("empresa"))->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE} ORDER BY empresa LIMIT ?,?", $dataWHERE);
        $this->load->view('getempresas', $data);
    }

    public function empresas()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $this->load->view('empresas');
    }

    public function cursosempresa()
    {
        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->segment(3)))->row(0);

        $this->load->view('cursosempresa', $data);
    }

    public function getcursosempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $this->load->library('pagination');

        $query = $_POST['busca'];

        $qryWHERE = 'WHERE (tipo = ? AND publico = ?) ';
        $dataWHERE[] = "administrador";
        $dataWHERE[] = 1;
        $qryWHERE_IN = null;
        $qryWHERE_TOTAL = null;

        $cursospagos = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ?", array($this->uri->segment(3)));

        foreach ($cursospagos->result() as $row) {
            $qryWHERE_IN .= '?,';
            $dataWHERE[] = $row->curso;
        }

        if ($cursospagos->num_rows > 0) {
            $qryWHERE_IN = substr($qryWHERE_IN, 0, -1);
            $qryWHERE .= "OR id IN ({$qryWHERE_IN}) ";
        }

        if (!empty($query)) {
            $qryWHERE .= 'AND (curso LIKE ?)';
            $dataWHERE[] = "%{$query}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getcursosempresa/' . $this->uri->segment(3));
        $config['total_rows'] = $this->db->query("SELECT * FROM cursos {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("4"))) ? 0 : intval($this->uri->segment("4"));
        $dataWHERE[] = $config['per_page'];

        $dataWHERE_TOTAL[] = "administrador";
        $dataWHERE_TOTAL[] = 1;

        foreach ($cursospagos->result() as $row) {
            $qryWHERE_TOTAL .= '?,';
            $dataWHERE_TOTAL[] = $row->curso;
        }

        if ($cursospagos->num_rows > 0) {
            $qryWHERE_TOTAL = substr($qryWHERE_TOTAL, 0, -1);
            $qryWHERE_TOTAL = "OR id IN ({$qryWHERE_TOTAL})";
        }

        $data['total'] = $this->db->query("SELECT * FROM cursos WHERE (tipo = ? AND publico = ?) {$qryWHERE_TOTAL}", $dataWHERE_TOTAL)->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM cursos {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getcursosempresa', $data);
    }

    public function excluircursosempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $this->db->query("DELETE FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($this->uri->segment(3), $this->uri->segment(4)));

        redirect(base_url('home/cursosempresa/' . $this->uri->segment(3)));
    }

    public function novocursoempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->segment(3)))->row(0);
        $data['cursos'] = $this->db->query("SELECT * FROM cursos WHERE usuario = ? AND status = ? OR publico = ? AND status = ? AND gratuito = ? ORDER BY curso ASC", array($this->session->userdata('id'), 1, 0, 1, 1));

        $this->load->view('novocursoempresa', $data);
    }

    public function novocursoempresa_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $data['usuario'] = $this->uri->segment(3);
        $data['curso'] = $_POST['curso'];
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['data_maxima'] = implode("-", array_reverse(explode("/", $_POST['data_maxima'])));
        $data_validacao = explode('/', $_POST['data_maxima']);
        $data['colaboradores_maximo'] = (int) $_POST['colaboradores_maximo'];

        if (empty($data['curso']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));

        if (empty($data['data_maxima']) || !checkdate($data_validacao[1], $data_validacao[0], $data_validacao[2]))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Prencha o campo "Data máxima de acesso" corretamente')));

        $verifcacurso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($data['curso']))->row(0);

        if ($verifcacurso->tipo != "administrador" && $verifcacurso->publico != 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));

        $verificacursoempresa = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", $data);

        if ($verificacursoempresa->num_rows > 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento já está vinculado para essa empresa!')));

        if ($this->db->query($this->db->insert_string('usuarioscursos', $data)))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de treinamento para empresa efetuado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/cursosempresa/' . $data['usuario'])));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de treinamento para empresa, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function editarcursoempresa($empresa, $curso)
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $query = $this->db->query("SELECT * FROM usuarios WHERE id = ? AND tipo = ? ", array($this->uri->segment(3), "empresa"));
        $data['row'] = $query->row(0);
        $data['cursos'] = $this->db->query("SELECT * FROM cursos WHERE usuario = ? AND status = ? OR publico = ? AND status = ? AND gratuito = ? ORDER BY curso ASC", array($this->session->userdata('id'), 1, 0, 1, 1));
        $query_edicao = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($data['row']->id, $this->uri->segment(4)));
        $data['curso_edicao'] = $query_edicao->row(0);

        if ($query->num_rows() < 1 || $query_edicao->num_rows() < 1) {
            redirect(base_url('home/empresas'));
        }

        $this->load->view('editarcursoempresa', $data);
    }

    public function editarcursoempresa_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $data['usuario'] = $this->uri->segment(3);
        $data['curso'] = $_POST['curso'];
        $data['data_maxima'] = implode("-", array_reverse(explode("/", $_POST['data_maxima'])));
        $data_validacao = explode('/', $_POST['data_maxima']);
        $data['colaboradores_maximo'] = (int) $_POST['colaboradores_maximo'];

        if (empty($data['curso']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));

        if (empty($data['data_maxima']) || !checkdate($data_validacao[1], $data_validacao[0], $data_validacao[2]))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Prencha o campo "Data máxima de acesso" corretamente')));

        $verifcacurso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($data['curso']))->row(0);

        if ($verifcacurso->tipo != "administrador" && $verifcacurso->publico != 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));

        $verificacursoempresa = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", $data);

        if ($verificacursoempresa->num_rows() <> 1)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento já está vinculado para essa empresa!')));

        if ($this->db->where('id', $verificacursoempresa->row(0)->id)->update('usuarioscursos', $data))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Edição de treinamento para empresa efetuado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/cursosempresa/' . $data['usuario'])));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de treinamento para empresa, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function novaempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $this->load->view('novaempresa');
    }

    public function novaempresa_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $data['tipo'] = "empresa";
        $data['url'] = $_POST['url'];
        $data['nome'] = $_POST['empresa'];
        $data['foto'] = "avatar.jpg";
        $data['email'] = $_POST['email'];
        $data['senha'] = $_POST['senha'];
        $data['token'] = uniqid();
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['status'] = $_POST['status'];

        if (empty($data['url']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "URL" não pode ficar em branco')));

        $verificaURL = $this->db->query("SELECT * FROM usuarios WHERE url = ?", array($data['url']));
        if ($verificaURL->num_rows > 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Já existe uma empresa com essa URL!')));

        if (empty($data['nome']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Empresa" não pode ficar em branco')));

        if (empty($data['email']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "E-mail" não pode ficar em branco')));

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));

        $verificaemail = $this->db->query("SELECT * FROM usuarios WHERE email = ?", array($data['email']));
        if ($verificaemail->num_rows > 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse endereço de e-mail já está em uso')));

        if (empty($data['senha']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));

        if ($data['senha'] != $_POST['confirmarsenha'])
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));

        $data['senha'] = $this->usuarios->setPassword($data['senha']);

        /* Logomarca */
        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                $data['foto'] = $foto['file_name'];
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Imagem Inicial */
        if (!empty($_FILES['imagem-inicial'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('imagem-inicial')) {
                $img_inicial = $this->upload->data();
                $data['imagem_inicial'] = $img_inicial['file_name'];
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Assinatura Digital */
        if (!empty($_FILES['assinatura-digital'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('assinatura-digital')) {
                $ass_digital = $this->upload->data();
                $data['assinatura_digital'] = $ass_digital['file_name'];
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if ($this->db->query($this->db->insert_string('usuarios', $data)))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de empresa efetuado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/empresas')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de empresa, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function editarempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $empresa = $this->db->query("SELECT * FROM usuarios WHERE tipo = ? AND id = ?", array("empresa", $this->uri->segment(3)));

        if ($empresa->num_rows == 0)
            redirect(base_url('home/empresas'));

        $data['row'] = $empresa->row(0);

        $this->load->view('editarempresa', $data);
    }

    public function editarempresa_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $empresa = $this->db->query("SELECT * FROM usuarios WHERE tipo = ?  AND id = ?", array("empresa", $this->uri->segment(3)))->row(0);

        $data['url'] = $_POST['url'];
        $data['nome'] = $_POST['empresa'];
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['status'] = $_POST['status'];

        if (empty($data['url']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "URL" não pode ficar em branco')));

        $verificaURL = $this->db->query("SELECT * FROM usuarios WHERE id <> ? AND url = ?", array($this->uri->segment(3), $data['url']));
        if ($verificaURL->num_rows > 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Já existe uma empresa com essa URL!')));

        if (empty($data['nome']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Empresa" não pode ficar em branco')));

        if ($_POST['senha'] != '') {
            if (empty($_POST['senha']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));

            if ($_POST['senha'] != $_POST['confirmarsenha'])
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));

            $data['senha'] = $this->usuarios->setPassword($_POST['senha']);
        }

        /* Logomarca */
        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                $data['foto'] = $foto['file_name'];
                if ($empresa->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->foto) && $empresa->foto != $data['foto'])
                    @unlink('./imagens/usuarios/' . $empresa->foto);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Imagem Inicial */
        if (!empty($_FILES['imagem-inicial'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('imagem-inicial')) {
                $img_inicial = $this->upload->data();
                $data['imagem_inicial'] = $img_inicial['file_name'];
                if ($empresa->imagem_inicial != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->imagem_inicial) && $empresa->imagem_inicial != $data['imagem_inicial'])
                    @unlink('./imagens/usuarios/' . $empresa->imagem_inicial);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Assinatura Digital */
        if (!empty($_FILES['assinatura-digital'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('assinatura-digital')) {
                $ass_digital = $this->upload->data();
                $data['assinatura_digital'] = $ass_digital['file_name'];
                if ($empresa->assinatura_digital != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->assinatura_digital) && $empresa->assinatura_digital != $data['assinatura_digital'])
                    @unlink('./imagens/usuarios/' . $empresa->assinatura_digital);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if ($this->db->where('id', $empresa->id)->update('usuarios', $data))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Empresa editada com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/empresas')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar empresa, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function excluirempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador")))
            redirect(base_url('home'));

        $empresa = $this->db->query("SELECT * FROM usuarios WHERE tipo = ? AND id = ?", array("empresa", $this->uri->segment(3)));

        if ($empresa->num_rows == 0)
            redirect(base_url('home/empresas'));

        $empresa = $empresa->row(0);

        if ($empresa->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->foto))
            @unlink('./imagens/usuarios/' . $empresa->foto);

        $this->db->where('id', $empresa->id)->delete('usuarios');

        redirect(base_url('home/empresas'));
    }

    public function cursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $data['categorias'] = $this->db->query("SELECT categoria FROM cursos WHERE CHAR_LENGTH(categoria) > 0 GROUP BY categoria");
        $data['areas_conhecimento'] = $this->db->query("SELECT area_conhecimento FROM cursos WHERE CHAR_LENGTH(area_conhecimento) > 0 GROUP BY area_conhecimento");

        $this->load->view('cursos', $data);
    }

    public function getcursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        header('Content-Type: text/html; charset=utf-8');

        $this->load->library('pagination');

        $query = $_POST['busca'];
        $area_conhecimento = $_POST['area_conhecimento'];
        $categoria = $_POST['categoria'];
        $busca = $_POST['busca'];

        $query_categoria = null;
        $query_areaConhecimento = null;
        $query_busca = null;

        # Verificar preenchimento dos filtros
        if (!empty($categoria)) {
            $query_categoria = " AND categoria = ? ";
        }
        if (!empty($area_conhecimento)) {
            $query_areaConhecimento = " AND area_conhecimento = ? ";
        }
        if (!empty($busca)) {
            $query_busca = " AND curso LIKE '" . $busca . "%' ";
        }

        if ($this->session->userdata('tipo') == "administrador") {
            $qryWHERE = "WHERE usuario = ? $query_categoria $query_areaConhecimento $query_busca";
            $dataWHERE[] = $this->session->userdata('id');

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }
        } else {
            $qryWHERE = "WHERE publico = 1 AND status = 1 $query_categoria $query_areaConhecimento $query_busca OR id IN
                        (SELECT curso FROM usuarioscursos WHERE usuario = ?) AND tipo = ? $query_categoria $query_areaConhecimento $query_busca
                        ";
            $dataWHERE[] = $this->session->userdata('id');

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }

            $dataWHERE[] = $this->session->userdata('id');

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }

            $dataWHERE[] = 'administrador';
            $dataWHERE[] = $this->session->userdata('id');
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getcursos');

        if ($this->session->userdata('tipo') != 'administrador') {
            $config['total_rows'] = $this->db->query("(SELECT * FROM cursos WHERE usuario = ? $query_categoria $query_areaConhecimento $query_busca) UNION (SELECT * FROM cursos {$qryWHERE})", $dataWHERE)->num_rows;
        } else {
            $config['total_rows'] = $this->db->query("SELECT * FROM cursos WHERE usuario = ?", $dataWHERE)->num_rows;
        }

        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("3"))) ? 0 : intval($this->uri->segment("3"));
        $dataWHERE[] = $config['per_page'];
        $data['total'] = $config['total_rows'];
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";

        if ($this->session->userdata('tipo') != 'administrador') {
            $data['query'] = $this->db->query("(SELECT * FROM cursos WHERE usuario = ? $query_categoria $query_areaConhecimento $query_busca) UNION (SELECT * FROM cursos {$qryWHERE}) ORDER BY usuario = ? DESC, curso ASC LIMIT ?,?", $dataWHERE);
        } else {
            $data['query'] = $this->db->query("SELECT * FROM cursos {$qryWHERE} ORDER BY id ASC LIMIT ?,?", $dataWHERE);
        }

        $this->load->view('getcursos', $data);
    }

    public function paginascurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                redirect(base_url('home/cursos'));

        $data['row'] = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        $this->load->view('paginascurso', $data);
    }

    public function getpaginascurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $this->load->library('pagination');

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                exit('Você não tem acesso a essa página!');

        $query = $_POST['busca'];

        $qryWHERE = 'WHERE curso = ? ';
        $dataWHERE[] = $curso->id;

        if (!empty($query)) {
            $qryWHERE .= 'AND (titulo LIKE ?)';
            $dataWHERE[] = "%{$query}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getpaginascurso/' . $curso->id);
        $config['total_rows'] = $this->db->query("SELECT * FROM paginas {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 9999;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("4"))) ? 0 : intval($this->uri->segment("4"));
        $dataWHERE[] = $config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM paginas WHERE curso = ?", array($curso->id))->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM paginas {$qryWHERE} ORDER BY ordem ASC LIMIT ?,?", $dataWHERE);
        $this->load->view('getpaginascurso', $data);
    }

    public function ordempaginascurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                redirect(base_url('home/cursos'));

        $ordem = 0;
        foreach ($_POST['table-dnd'] as $row) {
            $pagina = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($row))->row();
            if ($pagina->curso == $curso->id) {
                $this->db->where('id', $row)->update('paginas', array('ordem' => $ordem));
                $ordem++;
            }
        }
    }

    public function getbiblioteca_html()
    {
        $this->load->library('pagination');

        $categoria = $_POST['categoria'];
        $titulo = $_POST['titulo'];
        $tag = $_POST['tag'];

        $qryWHERE = 'WHERE tipo = ? ';
        $dataWHERE[] = $this->uri->segment(3);

        if (!empty($categoria)) {
            $qryWHERE .= 'AND categoria = ? ';
            $dataWHERE[] = $categoria;
        }

        if (!empty($titulo)) {
            $qryWHERE .= 'AND titulo LIKE ? ';
            $dataWHERE[] = "%{$titulo}%";
        }

        if (!empty($tag)) {
            $qryWHERE .= 'AND tags LIKE ? ';
            $dataWHERE[] = "%{$tag}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getbiblioteca_html/' . $this->uri->segment(3) . '/' . $this->uri->segment(4));
        $config['total_rows'] = $this->db->query("SELECT * FROM biblioteca {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 20;
        $config['uri_segment'] = 5;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("5"))) ? 0 : intval($this->uri->segment("5"));
        $dataWHERE[] = $config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM biblioteca WHERE tipo = ?", array($this->uri->segment(3)))->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM biblioteca {$qryWHERE} ORDER BY titulo LIMIT ?,?", $dataWHERE);
        $this->load->view('getbibliotecapagina', $data);
    }

    public function novapaginacurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));

        $data['row'] = $curso;

        $data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");

        $this->load->view('novapaginacurso', $data);
    }

    public function novapaginacurso_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));

        $pagina = $this->db->query("SELECT * FROM paginas WHERE curso = ? ORDER BY ordem DESC LIMIT 1", array($curso->id));

        if ($pagina->num_rows == 0) {
            $ordem = 0;
        } else {
            $pagina = $pagina->row(0);
            $ordem = $pagina->ordem + 1;
        }

        //Áudio e Vídeo
        $data['audio'] = $_POST['arquivo_audio'];
        $data['video'] = $_POST['arquivo_video'];

        $data['modulo'] = $_POST['modulo'];
        $data['curso'] = $curso->id;
        $data['titulo'] = $_POST['titulo'];
        $data['ordem'] = $ordem;
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        if (empty($data['modulo']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não pode ficar em branco')));

        if (!in_array($data['modulo'], array('ckeditor', 'arquivos-pdf', 'quiz', 'atividades', 'video-youtube', 'aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia')))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não é válido')));

        if (empty($data['titulo']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Título" não pode ficar em branco')));

        if ($data['modulo'] == "ckeditor") {

            $data['conteudo'] = $_POST['conteudo'];
            $data['pdf'] = "";
            $data['youtube'] = "";

            if (empty($data['conteudo']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Conteúdo" não pode ficar em branco')));
        } else if ($data['modulo'] == "arquivos-pdf") {

            if (!empty($_FILES['arquivo'])) {

                $config['upload_path'] = './arquivos/pdf/';
                $config['allowed_types'] = 'pdf|doc|docx|txt|ppt|pptx';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('arquivo')) {
                    $arquivo = $this->upload->data();
                    $data['pdf'] = $arquivo['file_name'];
                    $data['conteudo'] = "";
                    $data['youtube'] = "";

                    if ($arquivo['file_ext'] === '.doc' || $arquivo['file_ext'] === '.docx' || $arquivo['file_ext'] === '.txt' || $arquivo['file_ext'] === '.ppt' || $arquivo['file_ext'] === '.pptx') {
                        shell_exec("unoconv -f pdf " . $config['upload_path'] . $arquivo['file_name']);
                        $data['pdf'] = $arquivo['raw_name'] . ".pdf";
                    }
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
            }
        } else if ($data['modulo'] == "quiz") {

            $pid_ = array();
            $aid_ = array();
            $quiz = array();

            $perguntas = $_POST['perguntas'];

            foreach ($perguntas as $pid => $pergunta) {
                $pid_[] = $pid;
                $quiz[$pid] = array('pid' => $pid, 'pergunta' => $pergunta, 'tipoquestao' => $_POST['tipoquestao'][$pid], 'respostacorreta' => $_POST['respostacorreta'][$pid], 'respostaerrada' => $_POST['respostaerrada'][$pid], 'quantidade' => count($_POST['alternativas'][$pid]));
                if (!empty($pergunta) && $_POST['tipoquestao'][$pid] == 1) {
                    $alternativas = $_POST['alternativas'][$pid];
                    foreach ($alternativas as $aid => $alternativa) {
                        $aid_[] = $aid;
                        if (!empty($alternativa)) {
                            $quiz[$pid]['perguntas'][] = array('aid' => $aid, 'alternativa' => $alternativa, 'correta' => ($_POST['corretas'][$aid]) ? 1 : 0);
                        }
                    }
                }
            }

            foreach ($quiz as $row) {
                if ($row['tipoquestao'] == 1) {

                    if ($row['quantidade'] == 0)
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa!")));

                    $alternativaCorretas = 0;
                    foreach ($row['perguntas'] as $row_) {
                        if ($row_['correta'] == 1)
                            $alternativaCorretas++;
                    }

                    if ($alternativaCorretas == 0)
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa correta!")));
                }
            }

            $idQuiz = array();

            foreach ($quiz as $row) {

                $dataQuizPerguntas['pergunta'] = $row['pergunta'];
                $dataQuizPerguntas['tipo'] = $row['tipoquestao'];
                $dataQuizPerguntas['respostacorreta'] = $row['respostacorreta'];
                $dataQuizPerguntas['respostaerrada'] = $row['respostaerrada'];

                if ($this->db->query($this->db->insert_string('quizperguntas', $dataQuizPerguntas))) {
                    $dataQuizAlternativas['quiz'] = $this->db->insert_id();
                    $idQuiz[] = $dataQuizAlternativas['quiz'];

                    foreach ($row['perguntas'] as $row_) {
                        $dataQuizAlternativas['alternativa'] = $row_['alternativa'];
                        $dataQuizAlternativas['correta'] = $row_['correta'];

                        $this->db->query($this->db->insert_string('quizalternativas', $dataQuizAlternativas));
                    }
                }
            }
        } else if ($data['modulo'] == "atividades") {

            $pid_ = array();
            $aid_ = array();
            $quiz = array();

            $perguntas = $_POST['perguntas'];

            foreach ($perguntas as $pid => $pergunta) {
                $pid_[] = $pid;
                $quiz[$pid] = array('pid' => $pid, 'pergunta' => $pergunta, 'tipoquestao' => $_POST['tipoquestao'][$pid], 'respostacorreta' => $_POST['respostacorreta'][$pid], 'respostaerrada' => $_POST['respostaerrada'][$pid], 'quantidade' => count($_POST['alternativas'][$pid]));
                if (!empty($pergunta) && $_POST['tipoquestao'][$pid] == 1) {
                    $alternativas = $_POST['alternativas'][$pid];
                    foreach ($alternativas as $aid => $alternativa) {
                        $aid_[] = $aid;
                        if (!empty($alternativa)) {
                            $quiz[$pid]['perguntas'][] = array('aid' => $aid, 'alternativa' => $alternativa, 'correta' => ($_POST['corretas'][$aid]) ? 1 : 0);
                        }
                    }
                }
            }

            foreach ($quiz as $row) {
                if ($row['tipoquestao'] == 1) {

                    if ($row['quantidade'] == 0)
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa!")));

                    $alternativaCorretas = 0;
                    foreach ($row['perguntas'] as $row_) {
                        if ($row_['correta'] == 1)
                            $alternativaCorretas++;
                    }

                    if ($alternativaCorretas == 0)
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa correta!")));
                }
            }

            $idQuiz = array();

            foreach ($quiz as $row) {

                $dataQuizPerguntas['pergunta'] = $row['pergunta'];
                $dataQuizPerguntas['tipo'] = $row['tipoquestao'];
                $dataQuizPerguntas['respostacorreta'] = $row['respostacorreta'];
                $dataQuizPerguntas['respostaerrada'] = $row['respostaerrada'];

                if ($this->db->query($this->db->insert_string('atividadesperguntas', $dataQuizPerguntas))) {
                    $dataQuizAlternativas['quiz'] = $this->db->insert_id();
                    $idQuiz[] = $dataQuizAlternativas['quiz'];

                    foreach ($row['perguntas'] as $row_) {
                        $dataQuizAlternativas['alternativa'] = $row_['alternativa'];
                        $dataQuizAlternativas['correta'] = $row_['correta'];

                        $this->db->query($this->db->insert_string('atividadesalternativas', $dataQuizAlternativas));
                    }
                }
            }
        } else if ($data['modulo'] == "video-youtube") {

            $url_video = $_POST['videoyoutube'];
            $url_final = $_POST['videoyoutube'];

            switch ($url_video) {
                # Youtube anterior
                case strpos($url_video, 'youtube') > 0:
                    parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
                    // Verifica se a url está correta
                    if (isset($url['v'])) {
                        $url_final = "http://www.youtube.com/watch?v=" . $url['v'];
                    }
                    break;
                # Youtube novo
                case strpos($url_video, 'youtu.be') > 0:
                    $url_video = explode('/', $url_video);
                    $_POST['videoyoutube'] = "http://www.youtube.com/watch?v=" . $url_video[3];
                    parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
                    // Verifica se a url está correta
                    if (isset($url['v'])) {
                        $url_final = "http://www.youtube.com/watch?v=" . $url['v'];
                    }
                    break;
                # Vimeo
                case strpos($url_video, 'vimeo') > 0:
                    $url_video = explode('/', $url_video);
                    // usando file_get_contents para pegar os dados e unserializando o array
                    $video = unserialize(file_get_contents("http://vimeo.com/api/v2/video/{$url_video[3]}.php"));
                    // Verifica se a url está correta
                    if (isset($video[0]['url'])) {
                        $url_final = $video[0]['url'];
                    }
                    break;
                case strpos($url_video, 'slideshare') > 0:
                    $video = json_decode(file_get_contents("http://pt.slideshare.net/api/oembed/2?url=$url_video&format=json"));
                    if (isset($video->html)) {
                        $url_1 = explode('src="', $video->html);
                        $url_1 = explode('https://www.slideshare.net/slideshow/embed_code/key/', $url_1[1]);
                        $url_1 = explode('"', $url_1[1]);
                        $url_final = "https://pt.slideshare.net/slideshow/embed_code/key/" . $url_1[0];
                    }
                    break;
                case strpos($url_video, 'dailymotion') > 0:
                    $video = json_decode(file_get_contents("http://www.dailymotion.com/services/oembed?url=$url_video"));
                    if (isset($video->html)) {
                        $url_1 = explode('src="', $video->html);
                        $url_1 = explode('http://www.dailymotion.com/embed/video/', $url_1[1]);
                        $url_1 = explode('"', $url_1[1]);
                        $url_final = "http://www.dailymotion.com/embed/video/" . $url_1[0];
                    }
                    break;
            }

            $data['youtube'] = $url_final;
            $data['conteudo'] = $_POST['descricaoyoutube'];
            $data['pdf'] = "";

            if (empty($_POST['videoyoutube']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não pode ficar em branco')));

            if (empty($data['youtube']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não é válido')));
        } else if (in_array($data['modulo'], array('aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {

            $data['categoriabiblioteca'] = $_POST['categoriabiblioteca'];
            $data['titulobiblioteca'] = $_POST['titulobiblioteca'];
            $data['tagsbiblioteca'] = $_POST['tagsbiblioteca'];
            $data['biblioteca'] = $_POST['biblioteca'];

            if (empty($_POST['biblioteca']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione um item da Biblioteca para continuar')));
        }

        if ($this->db->query($this->db->insert_string('paginas', $data))) {
            $idPagina = $this->db->insert_id();
            if ($data['modulo'] == "quiz") {
                foreach ($idQuiz as $idQuizUnico) {
                    $this->db->where('id', $idQuizUnico)->update('quizperguntas', array('pagina' => $idPagina));
                }
            }
            if ($data['modulo'] == "atividades") {
                foreach ($idQuiz as $idQuizUnico) {
                    $this->db->where('id', $idQuizUnico)->update('atividadesperguntas', array('pagina' => $idPagina));
                }
            }
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro da página efetuado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/paginascurso/' . $curso->id)));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro da página, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editarpaginacurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $pagina = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($this->uri->segment(3)))->row(0);
        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($pagina->curso))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                redirect(base_url('home/cursos'));

        $data['curso'] = $curso;
        $data['row'] = $pagina;

        $data['quizperguntas'] = $this->db->query("SELECT * FROM quizperguntas WHERE pagina = ? ORDER BY id ASC", array($pagina->id));
        $data['atividadesperguntas'] = $this->db->query("SELECT * FROM atividadesperguntas WHERE pagina = ? ORDER BY id ASC", array($pagina->id));
        $data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");

        $this->load->view('editarpaginacurso', $data);
    }

    public function editarpaginacurso_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $pagina = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($this->uri->segment(3)))->row(0);
        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($pagina->curso))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));

        //Áudio e Vídeo
        $data['audio'] = $_POST['arquivo_audio'];
        $data['video'] = $_POST['arquivo_video'];

        $data['curso'] = $curso->id;
        $data['modulo'] = $_POST['modulo'];
        $data['titulo'] = $_POST['titulo'];
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");

        if (empty($data['modulo']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não pode ficar em branco')));

        if (!in_array($data['modulo'], array('ckeditor', 'arquivos-pdf', 'quiz', 'atividades', 'video-youtube', 'aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia')))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não é válido')));

        if (empty($data['titulo']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Título" não pode ficar em branco')));

        if ($data['modulo'] == "ckeditor") {

            $data['conteudo'] = $_POST['conteudo'];
            $data['pdf'] = "";
            $data['youtube'] = "";

            if (empty($data['conteudo']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Conteúdo" não pode ficar em branco')));
        } else if ($data['modulo'] == "arquivos-pdf") {
            if (!empty($_FILES['arquivo'])) {
                $config['upload_path'] = './arquivos/pdf/';
                $config['allowed_types'] = 'pdf|doc|docx|txt|ppt|pptx';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('arquivo')) {
                    $arquivo = $this->upload->data();
                    $data['pdf'] = $arquivo['file_name'];
                    $data['conteudo'] = "";
                    $data['youtube'] = "";

                    if ($arquivo['file_ext'] === '.doc' || $arquivo['file_ext'] === '.docx' || $arquivo['file_ext'] === '.txt' || $arquivo['file_ext'] === '.ppt' || $arquivo['file_ext'] === '.pptx') {
                        shell_exec("unoconv -f pdf " . $config['upload_path'] . $arquivo['file_name']);
                        $data['pdf'] = $arquivo['raw_name'] . ".pdf";
                    }

                    if (file_exists('./arquivos/pdf/' . $pagina->pdf) && $pagina->pdf != $data['pdf'])
                        @unlink('./arquivos/pdf/' . $pagina->pdf);
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            } else if ($pagina->pdf == "")
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
        } else if ($data['modulo'] == "quiz") {

            $pid_ = array();
            $aid_ = array();
            $quiz = array();

            $perguntas = $_POST['perguntas'];

            foreach ($perguntas as $pid => $pergunta) {
                $pid_[] = $pid;
                $quiz[$pid] = array('pid' => $pid, 'pergunta' => $pergunta, 'tipoquestao' => $_POST['tipoquestao'][$pid], 'respostacorreta' => $_POST['respostacorreta'][$pid], 'respostaerrada' => $_POST['respostaerrada'][$pid], 'quantidade' => count($_POST['alternativas'][$pid]));
                if (!empty($pergunta) && $_POST['tipoquestao'][$pid] == 1) {
                    $alternativas = $_POST['alternativas'][$pid];
                    foreach ($alternativas as $aid => $alternativa) {
                        $aid_[] = $aid;
                        if (!empty($alternativa)) {
                            $quiz[$pid]['perguntas'][] = array('aid' => $aid, 'alternativa' => $alternativa, 'correta' => ($_POST['corretas'][$aid]) ? 1 : 0);
                        }
                    }
                }
            }

            foreach ($quiz as $row) {
                if ($row['tipoquestao'] == 1) {

                    if ($row['quantidade'] == 0)
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa!")));

                    $alternativaCorretas = 0;
                    foreach ($row['perguntas'] as $row_) {
                        if ($row_['correta'] == 1)
                            $alternativaCorretas++;
                    }

                    if ($alternativaCorretas == 0)
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa correta!")));
                }
            }

            $quizPerguntas = $this->db->query("SELECT * FROM quizperguntas WHERE pagina = ? ORDER BY id ASC", array($pagina->id));

            $quizPid_ = array();
            $quizAid_ = array();

            foreach ($quizPerguntas->result() as $row) {
                $quizPid_[] = $row->id;
                $quizAlternativas = $this->db->query("SELECT * FROM quizalternativas WHERE quiz = ? ORDER BY id ASC", array($row->id));
                foreach ($quizAlternativas->result() as $row_) {
                    $quizAid_[] = $row_->id;
                }
            }

            $pidExcluidos = array_diff($quizPid_, $pid_);
            $aidExcluidos = array_diff($quizAid_, $aid_);

            foreach ($pidExcluidos as $row) {
                $pergunta = $this->db->query("SELECT * FROM quizperguntas WHERE id = ?", array($row))->row(0);
                if ($pergunta->pagina == $pagina->id) {
                    $this->db->delete('quizperguntas', array('id' => $pergunta->id));
                    $this->db->delete('quizalternativas', array('quiz' => $pergunta->id));
                }
            }

            foreach ($aidExcluidos as $row) {
                $alternativa = $this->db->query("SELECT quizperguntas.pagina, quizalternativas.* FROM quizalternativas JOIN quizperguntas ON quizperguntas.id = quizalternativas.quiz WHERE quizalternativas.id = ?", array($row))->row(0);
                if ($alternativa->pagina == $pagina->id) {
                    $this->db->delete('quizalternativas', array('id' => $alternativa->id));
                }
            }

            foreach ($quiz as $row) {

                $verificaPergunta = $this->db->query("SELECT * FROM quizperguntas WHERE id = ? AND pagina = ?", array($row['pid'], $pagina->id))->num_rows;

                if ($verificaPergunta > 0) {

                    $this->db->where('id', $row['pid'])->update('quizperguntas', array('pergunta' => $row['pergunta'], 'tipo' => $row['tipoquestao'], 'respostacorreta' => $row['respostacorreta'], 'respostaerrada' => $row['respostaerrada']));

                    foreach ($row['perguntas'] as $row_) {
                        $verificaAlternativa = $this->db->query("SELECT * FROM quizalternativas WHERE id = ? AND quiz = ?", array($row_['aid'], $row['pid']))->num_rows;

                        if ($verificaAlternativa > 0) {
                            $this->db->where('id', $row_['aid'])->update('quizalternativas', array('alternativa' => $row_['alternativa'], 'correta' => $row_['correta']));
                        } else {
                            $dataQuizAlternativas['quiz'] = $row['pid'];
                            $dataQuizAlternativas['alternativa'] = $row_['alternativa'];
                            $dataQuizAlternativas['correta'] = $row_['correta'];
                            $this->db->query($this->db->insert_string('quizalternativas', $dataQuizAlternativas));
                        }
                    }
                } else {

                    $dataQuizPerguntas['pagina'] = $pagina->id;
                    $dataQuizPerguntas['pergunta'] = $row['pergunta'];
                    $dataQuizPerguntas['tipo'] = $row['tipoquestao'];
                    $dataQuizPerguntas['respostacorreta'] = $row['respostacorreta'];
                    $dataQuizPerguntas['respostaerrada'] = $row['respostaerrada'];

                    if ($this->db->query($this->db->insert_string('quizperguntas', $dataQuizPerguntas))) {
                        $dataQuizAlternativas['quiz'] = $this->db->insert_id();

                        foreach ($row['perguntas'] as $row_) {
                            $dataQuizAlternativas['alternativa'] = $row_['alternativa'];
                            $dataQuizAlternativas['correta'] = $row_['correta'];

                            $this->db->query($this->db->insert_string('quizalternativas', $dataQuizAlternativas));
                        }
                    }
                }
            }
        } else if ($data['modulo'] == "atividades") {

            $pid_ = array();
            $aid_ = array();
            $quiz = array();

            $perguntas = $_POST['perguntas'];

            foreach ($perguntas as $pid => $pergunta) {
                $pid_[] = $pid;
                $quiz[$pid] = array('pid' => $pid, 'pergunta' => $pergunta, 'tipoquestao' => $_POST['tipoquestao'][$pid], 'respostacorreta' => $_POST['respostacorreta'][$pid], 'respostaerrada' => $_POST['respostaerrada'][$pid], 'quantidade' => count($_POST['alternativas'][$pid]));
                if (!empty($pergunta) && $_POST['tipoquestao'][$pid] == 1) {
                    $alternativas = $_POST['alternativas'][$pid];
                    foreach ($alternativas as $aid => $alternativa) {
                        $aid_[] = $aid;
                        if (!empty($alternativa)) {
                            $quiz[$pid]['perguntas'][] = array('aid' => $aid, 'alternativa' => $alternativa, 'correta' => ($_POST['corretas'][$aid]) ? 1 : 0);
                        }
                    }
                }
            }

            foreach ($quiz as $row) {
                if ($row['tipoquestao'] == 1) {

                    if ($row['quantidade'] == 0)
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa!")));

                    $alternativaCorretas = 0;
                    foreach ($row['perguntas'] as $row_) {
                        if ($row_['correta'] == 1)
                            $alternativaCorretas++;
                    }

                    if ($alternativaCorretas == 0)
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa correta!")));
                }
            }

            $quizPerguntas = $this->db->query("SELECT * FROM atividadesperguntas WHERE pagina = ? ORDER BY id ASC", array($pagina->id));

            $quizPid_ = array();
            $quizAid_ = array();

            foreach ($quizPerguntas->result() as $row) {
                $quizPid_[] = $row->id;
                $quizAlternativas = $this->db->query("SELECT * FROM atividadesalternativas WHERE quiz = ? ORDER BY id ASC", array($row->id));
                foreach ($quizAlternativas->result() as $row_) {
                    $quizAid_[] = $row_->id;
                }
            }

            $pidExcluidos = array_diff($quizPid_, $pid_);
            $aidExcluidos = array_diff($quizAid_, $aid_);

            foreach ($pidExcluidos as $row) {
                $pergunta = $this->db->query("SELECT * FROM atividadesperguntas WHERE id = ?", array($row))->row(0);
                if ($pergunta->pagina == $pagina->id) {
                    $this->db->delete('atividadesperguntas', array('id' => $pergunta->id));
                    $this->db->delete('atividadesalternativas', array('quiz' => $pergunta->id));
                }
            }

            foreach ($aidExcluidos as $row) {
                $alternativa = $this->db->query("SELECT atividadesperguntas.pagina, atividadesalternativas.* FROM atividadesalternativas JOIN atividadesperguntas ON atividadesperguntas.id = atividadesalternativas.quiz WHERE atividadesalternativas.id = ?", array($row))->row(0);
                if ($alternativa->pagina == $pagina->id) {
                    $this->db->delete('atividadesalternativas', array('id' => $alternativa->id));
                }
            }

            foreach ($quiz as $row) {
                $verificaPergunta = $this->db->query("SELECT * FROM atividadesperguntas WHERE id = ? AND pagina = ?", array($row['pid'], $pagina->id))->num_rows;

                if ($verificaPergunta > 0) {

                    $this->db->where('id', $row['pid'])->update('atividadesperguntas', array('pergunta' => $row['pergunta'], 'tipo' => $row['tipoquestao'], 'respostacorreta' => $row['respostacorreta'], 'respostaerrada' => $row['respostaerrada']));

                    foreach ($row['perguntas'] as $row_) {
                        $verificaAlternativa = $this->db->query("SELECT * FROM atividadesalternativas WHERE id = ? AND quiz = ?", array($row_['aid'], $row['pid']))->num_rows;

                        if ($verificaAlternativa > 0) {
                            $this->db->where('id', $row_['aid'])->update('atividadesalternativas', array('alternativa' => $row_['alternativa'], 'correta' => $row_['correta']));
                        } else {
                            $dataQuizAlternativas['quiz'] = $row['pid'];
                            $dataQuizAlternativas['alternativa'] = $row_['alternativa'];
                            $dataQuizAlternativas['correta'] = $row_['correta'];
                            $this->db->query($this->db->insert_string('atividadesalternativas', $dataQuizAlternativas));
                        }
                    }
                } else {

                    $dataQuizPerguntas['pagina'] = $pagina->id;
                    $dataQuizPerguntas['pergunta'] = $row['pergunta'];
                    $dataQuizPerguntas['tipo'] = $row['tipoquestao'];
                    $dataQuizPerguntas['respostacorreta'] = $row['respostacorreta'];
                    $dataQuizPerguntas['respostaerrada'] = $row['respostaerrada'];

                    if ($this->db->query($this->db->insert_string('atividadesperguntas', $dataQuizPerguntas))) {
                        $dataQuizAlternativas['quiz'] = $this->db->insert_id();

                        foreach ($row['perguntas'] as $row_) {
                            $dataQuizAlternativas['alternativa'] = $row_['alternativa'];
                            $dataQuizAlternativas['correta'] = $row_['correta'];

                            $this->db->query($this->db->insert_string('atividadesalternativas', $dataQuizAlternativas));
                        }
                    }
                }
            }
        } else if ($data['modulo'] == "video-youtube") {

            $url_video = $_POST['videoyoutube'];
            $url_final = $_POST['videoyoutube'];

            switch ($url_video) {
                # Youtube anterior
                case strpos($url_video, 'youtube') > 0:
                    parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
                    // Verifica se a url está correta
                    if (isset($url['v'])) {
                        $url_final = "http://www.youtube.com/watch?v=" . $url['v'];
                    }
                    break;
                # Youtube novo
                case strpos($url_video, 'youtu.be') > 0:
                    $url_video = explode('/', $url_video);
                    $_POST['videoyoutube'] = "http://www.youtube.com/watch?v=" . $url_video[3];
                    parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
                    // Verifica se a url está correta
                    if (isset($url['v'])) {
                        $url_final = "http://www.youtube.com/watch?v=" . $url['v'];
                    }
                    break;
                # Vimeo
                case strpos($url_video, 'vimeo') > 0:
                    $url_video = explode('/', $url_video);
                    // usando file_get_contents para pegar os dados e unserializando o array
                    $video = unserialize(file_get_contents("http://vimeo.com/api/v2/video/{$url_video[3]}.php"));
                    // Verifica se a url está correta
                    if (isset($video[0]['url'])) {
                        $url_final = $video[0]['url'];
                    }
                    break;
                case strpos($url_video, 'slideshare') > 0:
                    $video = json_decode(file_get_contents("http://pt.slideshare.net/api/oembed/2?url=$url_video&format=json"));
                    if (isset($video->html)) {
                        $url_1 = explode('src="', $video->html);
                        $url_1 = explode('https://www.slideshare.net/slideshow/embed_code/key/', $url_1[1]);
                        $url_1 = explode('"', $url_1[1]);
                        $url_final = "https://pt.slideshare.net/slideshow/embed_code/key/" . $url_1[0];
                    }
                    break;
                case strpos($url_video, 'dailymotion') > 0:
                    $video = json_decode(file_get_contents("http://www.dailymotion.com/services/oembed?url=$url_video"));
                    if (isset($video->html)) {
                        $url_1 = explode('src="', $video->html);
                        $url_1 = explode('http://www.dailymotion.com/embed/video/', $url_1[1]);
                        $url_1 = explode('"', $url_1[1]);
                        $url_final = "http://www.dailymotion.com/embed/video/" . $url_1[0];
                    }
                    break;
            }

            $data['youtube'] = $url_final;
            $data['conteudo'] = $_POST['descricaoyoutube'];
            $data['pdf'] = "";

            if (empty($_POST['videoyoutube']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não pode ficar em branco')));

            if (empty($data['youtube']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não é válido')));
        } else if (in_array($data['modulo'], array('aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {

            $data['categoriabiblioteca'] = $_POST['categoriabiblioteca'];
            $data['titulobiblioteca'] = $_POST['titulobiblioteca'];
            $data['tagsbiblioteca'] = $_POST['tagsbiblioteca'];
            $data['biblioteca'] = $_POST['biblioteca'];

            if (empty($_POST['biblioteca']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione um item da Biblioteca para continuar')));
        }

        if ($pagina->modulo == "upload" && $data['modulo'] != "upload")
            if (file_exists('./arquivos/pdf/' . $pagina->pdf) && $pagina->pdf != $data['pdf'])
                @unlink('./arquivos/pdf/' . $pagina->pdf);

        if ($this->db->where('id', $pagina->id)->update('paginas', $data))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Página do Curso editada com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/paginascurso/' . $curso->id)));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar página do curso, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function excluirpaginacurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $pagina = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($this->uri->segment(3)))->row(0);
        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($pagina->curso))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                redirect(base_url('home/cursos'));

        //Excluir atividades e páginas
        $this->db->where('id', $pagina->id)->delete('paginas');
        $this->db->where('pagina', $pagina->id)->delete('atividadesperguntas');
        $this->db->where('pagina', $pagina->id)->delete('quizperguntas');
        $this->db->query("DELETE FROM atividadesalternativas
                          WHERE quiz NOT IN (SELECT id FROM atividadesperguntas)");
        $this->db->query("DELETE FROM quizalternativas
                          WHERE quiz NOT IN (SELECT id FROM quizperguntas)");

        redirect(base_url('home/paginascurso/' . $curso->id));
    }

    public function novocurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $this->load->view('novocurso');
    }

    public function novocurso_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $data['usuario'] = $this->session->userdata('id');
        $data['tipo'] = $this->session->userdata('tipo');

        $data['publico'] = 0;
        $data['gratuito'] = 0;

        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        # Variaveis do post
        foreach ($_POST as $name => $value) {
            if ($name != 'submit') {
                $data[$name] = $value;
            }
        }

        if (isset($_FILES) && !empty($_FILES)) {
            if ($_FILES['foto_consultor']['error'] == 0) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_consultor')) {
                    $foto = $this->upload->data();
                    $data['foto_consultor'] = $foto['file_name'];
                }
                /*
                  else {
                  exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                  } */
            }

            if ($_FILES['foto_treinamento']['error'] == 0) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_treinamento')) {
                    $foto = $this->upload->data();
                    $data['foto_treinamento'] = $foto['file_name'];
                }
                /*
                  else {
                  exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                  }
                 */
            }
        }

        $data['publico'] = isset($data['publico']) ? $data['publico'] : 0;
        $data['publico'] = ($data['publico'] == 1) ? 1 : 0;
        # Validação
        if ($data['curso'] == '') {
            $name = str_replace('-', ' ', $name);
            $name = ucfirst($name);
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "' . $name . '" não pode ficar em branco')));
        }
        if ($this->db->query($this->db->insert_string('cursos', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de curso efetuado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/cursos')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de curso, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editarcurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                redirect(base_url('home/cursos'));

        $data['row'] = $curso;

        $this->load->view('editarcurso', $data);
    }

    public function editarcurso_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));

        $data['publico'] = 0;
        $data['gratuito'] = 0;

        # Variaveis do post
        foreach ($_POST as $name => $value) {
            if ($name != 'submit') {
                $data[$name] = $value;
            }
        }

        $data['id'] = base64_decode($data['id']);

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($data['id']))->row(0);
        $data['foto_consultor'] = $curso->foto_consultor;
        $data['foto_treinamento'] = $curso->foto_treinamento;

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));

        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");


        if (isset($_FILES)) {
            if ($_FILES['foto_consultor']['name']) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_consultor')) {
                    $foto = $this->upload->data();
                    $data['foto_consultor'] = $foto['file_name'];
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            }

            if ($_FILES['foto_treinamento']['name']) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_treinamento')) {
                    $foto = $this->upload->data();
                    $data['foto_treinamento'] = $foto['file_name'];
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            }
        }

        # Validação
        if ($data['curso'] == '') {
            $name = str_replace('-', ' ', $name);
            $name = ucfirst($name);
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "' . $name . '" não pode ficar em branco')));
        }

        if ($this->db->where('id', $data['id'])->update('cursos', $data))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Curso editado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/cursos')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar curso, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function excluircurso()
    {
        # Listagem da pasta
        $path_pdf = "arquivos/pdf/";
        $path_media = "arquivos/media/";

        # Arquivos
        $arquivos_pdf[] = null;
        $arquivos_media[] = null;
        $localizados_pdf[] = null;
        $localizados_media[] = null;

        # Verificar arquivos no banco
        $paginas = $this->db->query("SELECT pdf, audio, video FROM paginas
                                    WHERE curso = ?
                                    GROUP BY pdf, audio, video", array($this->uri->segment(3)));

        # Excluir arquivos
        foreach ($paginas->result() as $file) {

            if (!empty($file->pdf) || !empty($file->audio) || !empty($file_audio)) {

                if (!empty($file->pdf)) {
                    $arquivos_pdf[] = $file->pdf;
                }
                if (!empty($file->audio)) {
                    $arquivos_media[] = $file->audio;
                }
                if (!empty($file->video)) {
                    $arquivos_media[] = $file->video;
                }

                $arquivos = $this->db->query("SELECT pdf, audio, video FROM paginas WHERE curso != ? AND
                pdf = ? OR audio = ? OR video = ? GROUP BY pdf, audio, video", array($this->uri->segment(3), $file->pdf, $file->audio, $file->video));

                # Verifica se localizou algum arquivo
                if ($arquivos->num_rows > 0) {
                    foreach ($arquivos->result() as $files) {
                        if (!empty($files->pdf)) {
                            $localizados_pdf[] = $files->pdf;
                        }
                        if (!empty($files->audio)) {
                            $localizados_media[] = $files->audio;
                        }
                        if (!empty($files->video)) {
                            $localizados_media[] = $files->audio;
                        }
                    }
                }
            }
        }

        # Deleta os arquivos que não foram localizados
        foreach ($arquivos_pdf as $pdf) {
            if (!in_array($pdf, $localizados_pdf)) {
                unlink($path_pdf . $pdf);
            }
        }
        foreach ($arquivos_media as $media) {
            if (!in_array($media, $localizados_media)) {
                unlink($path_media . $media);
            }
        }

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(base_url('home'));

        # Excluir curso e páginas
        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador")
            if ($curso->usuario != $this->session->userdata('id'))
                redirect(base_url('home/cursos'));

        $this->db->where('id', $curso->id)->delete('cursos');
        $this->db->where('curso', $curso->id)->delete('paginas');

        //Excluir atividades e quiz
        $this->db->query("DELETE FROM atividadesperguntas
                          WHERE pagina NOT IN (SELECT id FROM paginas)");
        $this->db->query("DELETE FROM quizperguntas
                          WHERE pagina NOT IN (SELECT id FROM paginas)");
        $this->db->query("DELETE FROM atividadesalternativas
                          WHERE quiz NOT IN (SELECT id FROM atividadesperguntas)");
        $this->db->query("DELETE FROM quizalternativas
                          WHERE quiz NOT IN (SELECT id FROM quizperguntas)");

        redirect(base_url('home/cursos'));
    }

    public function solicitarcurso_json()
    {
        header('Content-type: text/json');
        $this->load->helper('phpmailer');

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);

        $sucesso = 0;
        if ($this->session->userdata('tipo') == "empresa") {

            if ($curso->tipo != "administrador")
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));

            $administradores = $this->db->query("SELECT * FROM usuarios WHERE tipo = ?", array('administrador'));
            foreach ($administradores->result() as $row) {

                $nome = $row->nome;
                $email = $row->email;

                $assunto = "LMS - Solicitação de curso";
                $mensagem = "<center>
                <h1>LMS</h1>
                </center>
                <hr />
                <p>Prezado(a) {$nome},</p>
                <p>Foi solicitado um curso, segue abaixo os dados da empresa e do curso solicitado.</p>
                <p><strong>Empresa:</strong> {$this->session->userdata('nome')} - {$this->session->userdata('email')}</p>
                <p><strong>Curso:</strong> {$curso->curso}</p>";

                if (send_email($nome, $email, $assunto, $mensagem))
                    $sucesso = 1;
            }
        } else if ($this->session->userdata('tipo') == "funcionario") {
            $usuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->session->userdata('id')))->row(0);
            $empresa = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($usuario->empresa))->row(0);

            if ($curso->tipo != "administrador" && $curso->usuario != $usuario->empresa)
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));

            $nome = $empresa->nome;
            $email = $empresa->email;

            $assunto = "LMS - Solicitação de curso";
            $mensagem = "<center>
                <h1>LMS</h1>
                </center>
                <hr />
                <p>Prezado(a) {$nome},</p>
                <p>Foi solicitado um curso, segue abaixo os dados do funcionário e do curso solicitado.</p>
                <p><strong>Funcionário:</strong> {$this->session->userdata('nome')} - {$this->session->userdata('email')}</p>
                <p><strong>Curso:</strong> {$curso->curso}</p>";

            if (send_email($nome, $email, $assunto, $mensagem))
                $sucesso = 1;
        }

        if ($sucesso)
            echo json_encode(array('retorno' => 1, 'aviso' => 'Treinamento solicitado com sucesso ao administrador da plataforma; em breve entraremos em contato para a liberação do mesmo'));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao solicitar treinamento, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function novofuncionario()
    {
        $this->load->view('novofuncionario');
    }

    public function getfuncionarios()
    {
        $this->load->library('pagination');

        $query = $_POST['busca'];

        $qryWHERE = 'WHERE empresa = ? AND tipo = ? ';
        $dataWHERE[] = $this->session->userdata('id');
        $dataWHERE[] = "funcionario";

        if (!empty($query)) {
            $qryWHERE .= 'AND (nome LIKE ? OR email LIKE ?)';
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getfuncionarios');
        $config['total_rows'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("3"))) ? 0 : intval($this->uri->segment("3"));
        $dataWHERE[] = $config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM usuarios WHERE empresa = ? AND tipo = ?", array($this->session->userdata('id'), "funcionario"))->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getfuncionarios', $data);
    }

    public function funcionarios()
    {
        $this->load->view('funcionarios');
    }

    public function cursosfuncionario()
    {
        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->segment(3)))->row(0);

        $this->load->view('cursosfuncionario', $data);
    }

    public function getcursosfuncionario()
    {
        $this->load->library('pagination');

        $query = $_POST['busca'];

        $qryWHERE = 'WHERE id IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) ';
        $dataWHERE[] = $this->uri->segment(3);

        if (!empty($query)) {
            $qryWHERE .= 'AND (curso LIKE ?)';
            $dataWHERE[] = "%{$query}%";
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getcursosfuncionario/' . $this->uri->segment(3));
        $config['total_rows'] = $this->db->query("SELECT * FROM cursos {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("4"))) ? 0 : intval($this->uri->segment("4"));
        $dataWHERE[] = $config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM cursos WHERE id IN (SELECT curso FROM usuarioscursos WHERE usuario = ?)", array($this->uri->segment(3)))->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT c.*,
                                            (SELECT COUNT(*) FROM paginas p WHERE p.curso = c.id AND p.modulo = 'atividades') AS total_atividades
                                           FROM cursos c {$qryWHERE} ORDER BY c.id DESC LIMIT ?,?", $dataWHERE);

        # Separa a quantidade de alternativas e acertos
        foreach ($data['query']->result() as $row) {
            $data['atividades'][$row->id] = $this->db->query("SELECT status, pagina
                                                              FROM usuariosatividades
                                                              WHERE curso = ? AND usuario = ?
                                                              ", array($row->id, $this->uri->segment(3)))->result();
        }

        $this->load->view('getcursosfuncionario', $data);
    }

    public function novocursofuncionario()
    {
        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->segment(3)))->row(0);
        $data['cursos'] = $this->db->query("SELECT * FROM cursos WHERE (status = ? && usuario = ?)
                                            AND id IN (
                                            SELECT curso FROM usuarioscursos WHERE curso NOT IN
                                             (SELECT curso FROM usuarioscursos WHERE usuario = ?)
                                            AND usuario = ? AND data_maxima IS NULL OR data_maxima >= NOW()
                                            )
                                            || publico = ? && status = ?
                                            ORDER BY curso ASC", array(1, $this->session->userdata('id'), $this->uri->segment(3), $this->session->userdata('id'), 0, 1));

        $this->load->view('novocursofuncionario', $data);
    }

    public function novocursofuncionario_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $data['usuario'] = $this->uri->segment(3);
        $data['curso'] = $_POST['curso'];
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $total = 0;
        $permissoes = 0;

        $verificausuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($verificausuario->empresa != $this->session->userdata('id'))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse usuário não é válido!')));

        if (empty($data['curso']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));

        $verifcacurso = $this->db->query("SELECT * FROM cursos WHERE id = ? AND ((tipo = ? AND publico = ?) OR (usuario = ?))", array($data['curso'], "administrador", 0, $this->session->userdata('id')))->num_rows;
        $verificacursoliberado = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ?  AND curso = ?", array($this->session->userdata('id'), $data['curso']));

        if ($verifcacurso == 0 && $verificacursoliberado->num_rows() == 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));

        //Verificar quantidade de colaboradores
        foreach ($verificacursoliberado->result() as $row) {
            $permissoes = $row->colaboradores_maximo;
            $total += $this->db->query("SELECT * FROM usuarioscursos WHERE usuario IN (SELECT id FROM usuarios WHERE empresa = ? OR id = ?) AND curso = ?", array($this->session->userdata('id'), $this->session->userdata('id'), $data['curso']))->num_rows();
        }

        $total -= 1;

        if ($permissoes <> 0 && $total >= $permissoes)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Número máximo de colaboradores excede ao limite contratado, para aumentar este número contate o gestor da plataforma via "Fale Conosco" ou envie um email para contato@peoplenetcorp.com.br')));

        $verificacursofuncionario = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($data['usuario'], $data['curso']));

        if ($verificacursofuncionario->num_rows > 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento já está vinculado para esse funcionário!')));

        if ($this->db->query($this->db->insert_string('usuarioscursos', $data)))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de treinamento para funcionário efetuado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/cursosfuncionario/' . $data['usuario'])));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de treinamento para funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function excluircursosfuncionario()
    {
        $veririficafuncionario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->segment(3)))->row(0);

        if ($veririficafuncionario->empresa != $this->session->userdata('id'))
            redirect(base_url('home/funcionarios'));

        $this->db->query("DELETE FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($this->uri->segment(3), $this->uri->segment(4)));

        redirect(base_url('home/cursosfuncionario/' . $this->uri->segment(3)));
    }

    public function novofuncionario_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $data['empresa'] = $this->session->userdata('id');
        $data['tipo'] = "funcionario";
        $data['nome'] = $_POST['funcionario'];
        $data['foto'] = "avatar.jpg";
        $data['email'] = $_POST['email'];
        $data['senha'] = $_POST['senha'];
        $data['funcao'] = $_POST['funcao'];
        $data['nivel_acesso'] = $_POST['nivel_acesso'];
        $data['status'] = $_POST['status'];
        $data['token'] = uniqid();
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        if (empty($data['nome']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Funcionário" não pode ficar em branco')));

        if (empty($data['email']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "E-mail" não pode ficar em branco')));

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));

        $verificaemail = $this->db->query("SELECT * FROM usuarios WHERE email = ?", array($data['email']));
        if ($verificaemail->num_rows > 0)
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse endereço de e-mail já está em uso')));

        if (empty($data['senha']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));

        if ($data['senha'] != $_POST['confirmarsenha'])
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));

        $data['senha'] = $this->usuarios->setPassword($data['senha']);

        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                $data['foto'] = $foto['file_name'];
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if ($this->db->query($this->db->insert_string('usuarios', $data)))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de funcionário efetuado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/funcionarios')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function editarfuncionario()
    {
        $funcionario = $this->db->query("SELECT * FROM usuarios WHERE tipo = ? AND id = ?", array("funcionario", $this->uri->segment(3)))->row(0);

        if ($funcionario->empresa != $this->session->userdata('id'))
            redirect(base_url('home/funcionarios'));

        $data['row'] = $funcionario;

        $this->load->view('editarfuncionario', $data);
    }

    public function editarfuncionario_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $funcionario = $this->db->query("SELECT * FROM usuarios WHERE tipo = ?  AND id = ?", array("funcionario", $this->uri->segment(3)))->row(0);

        if ($funcionario->empresa != $this->session->userdata('id'))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));

        $data['nome'] = $_POST['funcionario'];
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['nivel_acesso'] = $_POST['nivel_acesso'];
        $data['status'] = $_POST['status'];

        if (empty($data['nome']))
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Funcionário" não pode ficar em branco')));

        if ($_POST['senha'] != '') {
            if (empty($_POST['senha']))
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));

            if ($_POST['senha'] != $_POST['confirmarsenha'])
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));

            $data['senha'] = $this->usuarios->setPassword($_POST['senha']);
        }

        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                $data['foto'] = $foto['file_name'];
                if ($funcionario->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $funcionario->foto) && $funcionario->foto != $data['foto'])
                    @unlink('./imagens/usuarios/' . $funcionario->foto);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if ($this->db->where('id', $funcionario->id)->update('usuarios', $data))
            echo json_encode(array('retorno' => 1, 'aviso' => 'Funcionário editado com sucesso', 'redireciona' => 1, 'pagina' => base_url('home/funcionarios')));
        else
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
    }

    public function excluirfuncionario()
    {
        $funcionario = $this->db->query("SELECT * FROM usuarios WHERE tipo = ? AND id = ?", array("funcionario", $this->uri->segment(3)))->row(0);

        if ($funcionario->empresa != $this->session->userdata('id'))
            redirect(base_url('home/funcionarios'));

        if ($funcionario->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $funcionario->foto))
            @unlink('./imagens/usuarios/' . $funcionario->foto);

        $this->db->where('id', $funcionario->id)->delete('usuarios');

        redirect(base_url('home/funcionarios'));
    }

    public function meuscursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario")))
            redirect(base_url('home'));

        $data['categorias'] = $this->db->query("SELECT categoria FROM cursos WHERE CHAR_LENGTH(categoria) > 0 GROUP BY categoria");
        $data['areas_conhecimento'] = $this->db->query("SELECT area_conhecimento FROM cursos WHERE CHAR_LENGTH(area_conhecimento) > 0 GROUP BY area_conhecimento");
        $this->load->view('meuscursos', $data);
    }

    public function getmeuscursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario")))
            redirect(base_url('home'));

        $this->load->library('pagination');

        $query = $_POST['busca'];
        $area_conhecimento = $_POST['area_conhecimento'];
        $categoria = $_POST['categoria'];
        $busca = $_POST['busca'];

        $query_categoria = null;
        $query_areaConhecimento = null;
        $query_busca = null;

        # Verificar preenchimento dos filtros
        if (!empty($categoria)) {
            $query_categoria = " AND categoria = ? ";
        }
        if (!empty($area_conhecimento)) {
            $query_areaConhecimento = " AND area_conhecimento = ? ";
        }
        if (!empty($busca)) {
            $query_busca = " AND curso LIKE '" . $busca . "%' ";
        }

        $qryWHERE = " WHERE c.id IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) $query_categoria $query_areaConhecimento $query_busca";
        $dataWHERE[] = $this->session->userdata('empresa');
        $dataWHERE[] = $this->session->userdata('id');

        # Definir os filtros
        if (!empty($query_categoria)) {
            $dataWHERE[] = $categoria;
        }
        if (!empty($query_areaConhecimento)) {
            $dataWHERE[] = $area_conhecimento;
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getmeuscursos');
        $config['total_rows'] = $this->db->query("SELECT c.* FROM cursos c {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("3"))) ? 0 : intval($this->uri->segment("3"));
        $dataWHERE[] = $config['per_page'];

        $qryWHERE_TOTAL = 'WHERE c.id IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) ';
        $dataWHERE_TOTAL[] = $this->session->userdata('id');

        $data['total'] = $this->db->query("SELECT c.* FROM cursos c {$qryWHERE_TOTAL}", $dataWHERE_TOTAL)->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT c.*,
                                            (SELECT uc.data_maxima FROM usuarioscursos uc WHERE uc.curso = c.id AND uc.usuario = ?) AS data_maxima,
                                            (SELECT COUNT(*) FROM paginas p WHERE p.curso = c.id AND p.modulo = 'atividades') AS total_atividades
                                           FROM cursos c {$qryWHERE} GROUP BY c.id ORDER BY c.id DESC LIMIT ?,?", $dataWHERE);

        # Separa a quantidade de alternativas e acertos
        foreach ($data['query']->result() as $row) {
            $data['atividades'][$row->id] = $this->db->query("SELECT status, pagina
                                                              FROM usuariosatividades
                                                              WHERE curso = ? AND usuario = ?
                                                              ", array($row->id, $this->session->userdata('id')))->result();
        }

        $this->load->view('getmeuscursos', $data);
    }

    public function solicitarcursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario")))
            redirect(base_url('home'));

        $data['categorias'] = $this->db->query("SELECT categoria FROM cursos WHERE CHAR_LENGTH(categoria) > 0 GROUP BY categoria");
        $data['areas_conhecimento'] = $this->db->query("SELECT area_conhecimento FROM cursos WHERE CHAR_LENGTH(area_conhecimento) > 0 GROUP BY area_conhecimento");
        $this->load->view('solicitarcursos', $data);
    }

    public function getsolicitarcursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario")))
            redirect(base_url('home'));

        $this->load->library('pagination');

        $query = $_POST['busca'];

        $query = $_POST['busca'];
        $area_conhecimento = $_POST['area_conhecimento'];
        $categoria = $_POST['categoria'];
        $busca = $_POST['busca'];

        $query_categoria = null;
        $query_areaConhecimento = null;
        $query_busca = null;

        # Verificar preenchimento dos filtros
        if (!empty($categoria)) {
            $query_categoria = " AND categoria = ? ";
        }
        if (!empty($area_conhecimento)) {
            $query_areaConhecimento = " AND area_conhecimento = ? ";
        }
        if (!empty($busca)) {
            $query_busca = " AND curso LIKE '" . $busca . "%' ";
        }

        $usuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->session->userdata('id')))->row(0);

        $qryWHERE = "WHERE status = 1 AND publico = 1 AND id NOT IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) $query_categoria $query_areaConhecimento $query_busca || status = 1 AND tipo = ? AND id NOT IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) $query_categoria $query_areaConhecimento $query_busca";
        $dataWHERE[] = $this->session->userdata('id');

        # Definir os filtros
        if (!empty($query_categoria)) {
            $dataWHERE[] = $categoria;
        }
        if (!empty($query_areaConhecimento)) {
            $dataWHERE[] = $area_conhecimento;
        }

        $dataWHERE[] = 'administrador';
        $dataWHERE[] = $this->session->userdata('id');

        # Definir os filtros
        if (!empty($query_categoria)) {
            $dataWHERE[] = $categoria;
        }
        if (!empty($query_areaConhecimento)) {
            $dataWHERE[] = $area_conhecimento;
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url('home/getsolicitarcursos');
        $config['total_rows'] = $this->db->query("SELECT * FROM cursos {$qryWHERE}", $dataWHERE)->num_rows;
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (is_null($this->uri->segment("3"))) ? 0 : intval($this->uri->segment("3"));
        $dataWHERE[] = $config['per_page'];

        $qryWHERE_TOTAL = 'WHERE status = 1 AND id NOT IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) ';
        $dataWHERE_TOTAL[] = $this->session->userdata('id');

        $data['total'] = $this->db->query("SELECT * FROM cursos {$qryWHERE_TOTAL}", $dataWHERE_TOTAL)->num_rows;
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM cursos {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getsolicitarcursos', $data);
    }

    public function acessarcurso()
    {
        $verificacurso = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($this->session->userdata('id'), $this->uri->segment(3)))->num_rows;
        if ($verificacurso == 0)
            redirect(base_url('home/meuscursos'));

        $data['curso'] = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->segment(3)))->row(0);
        $data['paginas'] = $this->db->query("SELECT * FROM paginas WHERE curso = ? ORDER BY ordem ASC", array($this->uri->segment(3)));

        $data['ultimapagina'] = $this->db->query("SELECT * FROM paginas WHERE curso = ?", array($this->uri->segment(3)))->num_rows - 1;
        $data['datacadastro'] = date("%Y-%m-%d %H:%i:%s");

        $pagina = ($this->uri->segment(4) == null) ? 0 : $this->uri->segment(4);

        $data['paginaatual'] = $this->db->query("SELECT p.*,
                                                  (SELECT COUNT(*) FROM usuariospaginas pg
                                                    WHERE pg.curso = p.curso
                                                    AND pg.pagina = p.id
                                                    AND pg.usuario = '{$this->session->userdata['id']}'
                                                  ) AS total,
                                                  (SELECT COUNT(*) FROM usuariospaginas up
                                                    WHERE up.curso = p.curso
                                                    AND up.usuario = '{$this->session->userdata['id']}'
                                                    AND up.status = 1
                                                  ) AS andamento,
                                                  (SELECT COUNT(*) FROM usuariospaginas up
                                                    WHERE up.curso = p.curso
                                                    AND up.pagina = p.id
                                                    AND up.usuario = '{$this->session->userdata['id']}'
                                                    AND up.status = 1
                                                  ) AS conclusao
                                                  FROM paginas p
                                                  WHERE p.curso = ?
                                                  ORDER BY p.ordem ASC LIMIT ?,1", array($this->uri->segment(3), (int) $pagina))->row(0);
        $this->iniciaCurso($data['paginaatual'], $this->session->userdata['id'], $data['datacadastro']);
        $this->load->view('acessarcurso', $data);
    }

    public function iniciaCurso($dados = array(), $usuario = 0, $data_cadastro = null)
    {
        //Dados para inserção e verificação no banco
        $data_insert = array();
        $total = 1;

        //Pegar dados enviados pelo sql
        foreach ($dados as $key => $value) {
            if ($key == 'id') {
                $data_insert['pagina'] = (int) $value;
            } elseif ($key == 'curso' || $key == 'total' || $key == 'ordem') {
                //Separar id do curso e total de linhas
                if ($key == 'total') {
                    $total = (int) $value;
                } else {
                    $data_insert[$key] = (int) $value;
                }
            } else {
                unset($data_insert[$key]);
            }
        }

        $data_insert['datacadastro'] = date('Y-m-d H:i:s');
        $data_insert['dataconclusao'] = date('Y-m-d H:i:s');
        $data_insert['usuario'] = (int) $usuario;

        //Verifica id do usuário, se a página já está cadastrada e não insere as "capas"
        if ($usuario > 0 && $total == 0 && $data_insert['ordem'] >= 0) {
            //Capa do curso
            if ($data_insert['ordem'] > 0) {
                unset($data_insert['dataconclusao']);
            }
            unset($data_insert['ordem']);

            $this->db->query($this->db->insert_string('usuariospaginas', $data_insert));
        }
    }

    public function manutencao()
    {
        $this->load->view('manutencao');
    }

    public function copiaCursos()
    {
        if (isset($_GET) && !empty($_GET['valor'])) {
            $valor = 0;

            foreach ($_GET as $campoGet => $valorGet) {
                $$campoGet = (int) $valorGet;
            }

            $resultado = $this->db->query("SELECT * FROM cursos WHERE id = '$valor'")->num_rows;

            if ($resultado > 0) {

                #Copiar curso
                $this->db->query("INSERT INTO cursos
                                (usuario, tipo, publico, curso, descricao, datacadastro, dataeditado,
                                duracao, objetivos, competencias_genericas, competencias_especificas,
                                competencias_comportamentais, consultor, foto_consultor, curriculo,
                                foto_treinamento, pre_requisitos)
                                SELECT c.usuario, c.tipo, c.publico, CONCAT('Copia ' , c.curso), c.descricao, c.datacadastro, '0000-00-00 00:00:00',
                                c.duracao, c.objetivos, c.competencias_genericas, c.competencias_especificas,
                                c.competencias_comportamentais, c.consultor, c.foto_consultor, c.curriculo,
                                c.foto_treinamento, c.pre_requisitos
                                FROM cursos c
                                WHERE id = '$valor'
                                LIMIT 1
                                ");

                $id = $this->db->query("SELECT LAST_INSERT_ID() as id_final FROM paginas GROUP BY id_final");
                $id_curso = $id->row(0)->id_final;

                #Copiar páginas
                $this->db->query("INSERT INTO paginas
                                (curso, ordem, modulo, titulo, conteudo, pdf, youtube, categoriabiblioteca,
                                titulobiblioteca, tagsbiblioteca, biblioteca, datacadastro, dataeditado, copia_de)
                                SELECT $id_curso, p.ordem, p.modulo, p.titulo, p.conteudo, p.pdf, p.youtube, p.categoriabiblioteca,
                                p.titulobiblioteca, p.tagsbiblioteca, p.biblioteca, p.datacadastro, '0000-00-00 00:00:00', p.id
                                FROM paginas p
                                WHERE p.curso = '$valor'
                                ");

                $paginas_atv = $this->db->query("SELECT copia_de, id FROM paginas WHERE curso = ?", $id_curso);

                if ($paginas_atv->num_rows > 0) {
                    $paginas_atv = $paginas_atv->result_object();

                    #Copiar quiz e atividades
                    foreach ($paginas_atv AS $pagina) {
                        # ---------------------- Quiz ----------------------
                        #Quiz Perguntas
                        $this->db->query("INSERT INTO quizperguntas
                                            (pagina, tipo, pergunta, respostacorreta, respostaerrada, copia_de)
                                            SELECT $pagina->id, q.tipo, q.pergunta, q.respostacorreta, q.respostaerrada, q.id
                                            FROM quizperguntas q
                                            WHERE q.pagina = '$pagina->copia_de'
                                            ");

                        $quizs = $this->db->query("SELECT copia_de, id FROM quizperguntas
                                      WHERE pagina = '$pagina->id'
                                      ");

                        #Quiz Alternativas
                        if ($quizs->num_rows > 0) {
                            $quizs = $quizs->result_object();

                            foreach ($quizs as $quiz) {
                                $this->db->query("INSERT INTO quizalternativas
                                                    (quiz, alternativa, correta)
                                                    SELECT $quiz->id, q.alternativa, q.correta
                                                    FROM quizalternativas q
                                                    WHERE q.quiz = '$quiz->copia_de'
                                                    ");
                            }
                        }

                        # ---------------------- Atividades ----------------------
                        #Atividades Perguntas
                        $this->db->query("INSERT INTO atividadesperguntas
                                            (pagina, tipo, pergunta, respostacorreta, respostaerrada, copia_de)
                                            SELECT $pagina->id, q.tipo, q.pergunta, q.respostacorreta, q.respostaerrada, q.id
                                            FROM atividadesperguntas q
                                            WHERE q.pagina = '$pagina->copia_de'
                                            ");

                        $quizs = $this->db->query("SELECT id, copia_de FROM atividadesperguntas
                                                      WHERE pagina = '$pagina->id'
                                                      ");

                        #Atividades Alternativas
                        if ($quizs->num_rows > 0) {
                            $quizs = $quizs->result_object();

                            foreach ($quizs as $quiz) {
                                $this->db->query("INSERT INTO atividadesalternativas
                                                    (quiz, alternativa, correta)
                                                    SELECT $quiz->id, q.alternativa, q.correta
                                                    FROM atividadesalternativas q
                                                    WHERE q.quiz = '$quiz->copia_de'
                                                    ");
                            }
                        }
                    }
                }

                echo json_encode("sucesso");
            } else {
                echo json_encode("O curso não pode ser copiado");
            }
        }
    }

    public function preview()
    {
        $aula = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($this->uri->segment(3)))->row(0);
        $data['row'] = $aula;
        $this->load->view('previewaula', $data);
    }

    public function copiar_pagina()
    {
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];
            $paginas = $this->db->query("SELECT * FROM paginas WHERE id LIKE '" . $id . "' ");
            if ($paginas->num_rows > 0) {
                $this->db->query("INSERT INTO paginas
                                    (curso, ordem, modulo, titulo, conteudo, pdf, youtube, categoriabiblioteca,
                                    titulobiblioteca, tagsbiblioteca, biblioteca, datacadastro, dataeditado)
                                    SELECT p.curso, o.ordem + 1 AS pagFinal, p.modulo, CONCAT('Copia ' , p.titulo), p.conteudo, p.pdf, p.youtube, p.categoriabiblioteca,
                                    p.titulobiblioteca, p.tagsbiblioteca, p.biblioteca, p.datacadastro, '0000-00-00 00:00:00'
                                    FROM paginas p
                                    INNER JOIN paginas o ON o.curso = p.curso
                                    WHERE p.id = " . $id . "
                                    ORDER BY o.ordem DESC LIMIT 1;");

                $paginas_atv = $this->db->query("SELECT LAST_INSERT_ID() as id_final FROM paginas GROUP BY id_final");

                if ($paginas_atv->num_rows > 0) {
                    $paginas_atv = $paginas_atv->result_object();

                    #Copiar quiz e atividades
                    foreach ($paginas_atv AS $pagina) {
                        # ---------------------- Quiz ----------------------
                        #Quiz Perguntas
                        $this->db->query("INSERT INTO quizperguntas
                                            (pagina, tipo, pergunta, respostacorreta, respostaerrada, copia_de)
                                            SELECT $pagina->id_final, q.tipo, q.pergunta, q.respostacorreta, q.respostaerrada, q.id
                                            FROM quizperguntas q
                                            WHERE q.pagina = '$id'
                                            ");

                        $quizs = $this->db->query("SELECT copia_de, id FROM quizperguntas
                                      WHERE pagina = '$pagina->id_final'
                                      ");

                        #Quiz Alternativas
                        if ($quizs->num_rows > 0) {
                            $quizs = $quizs->result_object();

                            foreach ($quizs as $quiz) {
                                $this->db->query("INSERT INTO quizalternativas
                                                    (quiz, alternativa, correta)
                                                    SELECT $quiz->id, q.alternativa, q.correta
                                                    FROM quizalternativas q
                                                    WHERE q.quiz = '$quiz->copia_de'
                                                    ");
                            }
                        }

                        # ---------------------- Atividades ----------------------
                        #Atividades Perguntas
                        $this->db->query("INSERT INTO atividadesperguntas
                                            (pagina, tipo, pergunta, respostacorreta, respostaerrada, copia_de)
                                            SELECT $pagina->id_final, q.tipo, q.pergunta, q.respostacorreta, q.respostaerrada, q.id
                                            FROM atividadesperguntas q
                                            WHERE q.pagina = '$id'
                                            ");

                        $quizs = $this->db->query("SELECT id, copia_de FROM atividadesperguntas
                                                      WHERE pagina = '$pagina->id_final'
                                                      ");

                        #Atividades Alternativas
                        if ($quizs->num_rows > 0) {
                            $quizs = $quizs->result_object();

                            foreach ($quizs as $quiz) {
                                $this->db->query("INSERT INTO atividadesalternativas
                                                    (quiz, alternativa, correta)
                                                    SELECT $quiz->id, q.alternativa, q.correta
                                                    FROM atividadesalternativas q
                                                    WHERE q.quiz = '$quiz->copia_de'
                                                    ");
                            }
                        }
                    }
                }
                echo json_encode('success');
            } else {
                echo json_encode('ERROR: Nenhuma página solicitada foi encontrada!');
            }
        }
    }

}
