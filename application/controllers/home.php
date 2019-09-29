<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuarios_model', 'usuarios');
    }

    public function index()
    {
        setlocale(LC_ALL, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
        date_default_timezone_set('America/Sao_Paulo');
        $this->load->helper(array('date'));

        $data['depto'] = $this->input->get('depto');
        $data['area'] = $this->input->get('area');


        $this->db->select('DISTINCT(depto) AS nome', false);
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        if ($this->session->userdata('tipo') != "administrador") {
            $this->db->where('empresa', $this->session->userdata('empresa'));
        }
        $this->db->order_by('depto');
        $deptos = $this->db->get('usuarios')->result();

        $data['deptos'] = array('' => 'selecione...');
        foreach ($deptos as $depto) {
            $data['deptos'][$depto->nome] = $depto->nome;
        }


        $this->db->select('DISTINCT(area) AS nome', false);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        if ($this->session->userdata('tipo') != "administrador") {
            $this->db->where('empresa', $this->session->userdata('empresa'));
        }
        if ($data['depto']) {
            $this->db->where('depto', $data['depto']);
        }
        $this->db->order_by('area');
        $areas = $this->db->get('usuarios')->result();

        $data['areas'] = array('' => 'selecione...');
        foreach ($areas as $area) {
            $data['areas'][$area->nome] = $area->nome;
        }

        if ($this->session->userdata('tipo') == "administrador") {
            $usuarios = $this->db->query('SELECT * FROM usuarios ORDER BY nome ASC')->result();
            $view = 'index_administrador';
        } else if ($this->session->userdata('tipo') == "empresa") {
            $usuarios = $this->db->query('SELECT * FROM usuarios WHERE empresa = ? ORDER BY nome ASC', $this->session->userdata('id'))->result();
            $view = 'index_empresa';
        } else if ($this->session->userdata('tipo') == "funcionario") {
            $usuarios = $this->db->query('SELECT * FROM usuarios WHERE empresa = ? ORDER BY nome ASC', $this->session->userdata('empresa'))->result();
            $view = 'index_funcionario';
        } else if ($this->session->userdata('tipo') == "cliente") {
            $usuarios = $this->db->query('SELECT * FROM cursos_clientes WHERE id_empresa = ? ORDER BY nome ASC', $this->session->userdata('empresa'))->result();
            redirect(site_url('ead/treinamento_cliente'));
        } else if ($this->session->userdata('tipo') == "selecionador") {
            $usuarios = $this->db->query('SELECT * FROM usuarios WHERE empresa = ? ORDER BY nome ASC', $this->session->userdata('empresa'))->result();
            $view = 'index_empresa';
        } else if ($this->session->userdata('tipo') == "candidato") {
            $usuarios = $this->db->query('SELECT * FROM recrutamento_usuarios WHERE empresa = ? ORDER BY nome ASC', $this->session->userdata('empresa'))->result();
            //$view = 'index_candidato';
            redirect(site_url('recrutamento/testes'));
        } else if ($this->session->userdata('tipo') == "candidato_externo") {
            $usuarios = $this->db->query('SELECT * FROM candidatos WHERE empresa = ? ORDER BY nome ASC', $this->session->userdata('empresa'))->result();
            //$view = 'index_candidato';
            redirect(site_url('candidatoVagas'));
        }

        $data['usuarios'] = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $data['usuarios'][$usuario->id] = $usuario->nome;
        }

        $data['scheduler'] = $this->notificar();

        $this->load->view($view, $data);
    }

    public function notificar()
    {
        $data = $this->db
            ->select('DAY(NOW()) AS dia', false)
            ->select(['(WEEK(NOW()) - WEEK(DATE_SUB(NOW(), INTERVAL DAY(NOW()) DAY)) + 1) AS semana'], false)
            ->select('MONTH(NOW()) AS mes', false)
            ->get()
            ->row_array();

        if (!$this->session->flashdata('scheduler')) {
            $data['atividades'] = null;
            $data['total'] = 0;
            return $data;
        }

        $data['atividades'] = $this->db
            ->select("GROUP_CONCAT(id SEPARATOR '-') AS id, atividade, dia, mes", false)
            ->select("(CASE dia WHEN '{$data['dia']}' THEN 'scheduler_dia' END) AS class_dia", false)
            ->select("(CASE semana WHEN '{$data['semana']}' THEN 'scheduler_semana' END) AS class_semana", false)
            ->select("(CASE mes WHEN '{$data['mes']}' THEN 'scheduler_mes' END) AS class_mes", false)
            ->select("GROUP_CONCAT(DISTINCT objetivos ORDER BY objetivos ASC SEPARATOR '<br>') AS objetivos", false)
            ->where('id_usuario', $this->session->userdata('id'))
            ->where('lembrar', 1)
//            ->where("((dia = '{$data['dia']} AND mes = '{$data['mes']}') OR (semana = '{$data['semana']}' OR semana IS NULL) OR (mes = '{$data['mes']}' OR mes IS NULL))")
//            ->where("(dia = '{$data['dia']}' OR (semana = '{$data['semana']}' OR semana IS NULL) OR (mes = '{$data['mes']}' OR mes IS NULL))")
            ->group_by('atividade')
            ->order_by('atividade', 'asc')
            ->get('atividades_scheduler')
            ->result();

        return $data;
    }

    public function atualizarScheduler()
    {
        $dia = $this->input->post('dia');
        $semana = $this->input->post('semana');
        $mes = $this->input->post('mes');

        $this->db->trans_start();

        $this->db->set('lembrar', 0);
        $this->db->where('id_usuario', $this->session->userdata('id'));
//        $this->db->where("(dia = '{$dia}' OR semana = '{$semana}' OR mes = '{$mes}')");
        $this->db->where("(dia = '{$dia}' OR mes = '{$mes}')");
        $this->db->where('lembrar', 1);
        $this->db->update('atividades_scheduler');

        $this->db->trans_complete();

        if ($this->db->trans_status() == false) {
            exit(['erro' => 'Erro ao atualizar notificação.']);
        }
        echo json_encode(['status' => true]);
    }

    public function excluirScheduler()
    {
        $idAtividades = explode('-', $this->input->post('id'));

        $documentos = $this->db->where_in('id', $idAtividades)->get('atividades_scheduler')->result();

        if (empty($documentos)) {
            exit(json_encode(['erro' => 'A atividade não foi encontrada ou já foi excluída.']));
        }

        $this->db->trans_start();

        $this->db->where_in('id', $idAtividades)->delete('atividades_scheduler');

        $this->db->trans_complete();

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            exit(json_encode(['erro' => 'Não foi possível excluir a atividade.']));
        }

        foreach ($documentos as $documento) {
            @unlink('./arquivos/pdf/' . $documento->documento_1);
            @unlink('./arquivos/pdf/' . $documento->documento_2);
            @unlink('./arquivos/pdf/' . $documento->documento_3);
        }

        echo json_encode(['status' => true]);
    }

    public function alterarsenha()
    {
        $data['token'] = $this->uri->rsegment(3);

        if (empty($data['token'])) {
            redirect(site_url('home'));
        }

        $usuario = $this->db->query("SELECT * FROM usuarios WHERE token = ?", $data);
        if ($usuario->num_rows() == 0) {
            redirect(site_url('home'));
        }

        $data['nome'] = $usuario->row()->nome;

        $this->load->view('alterarsenha', $data);
    }

    public function alterarsenha_json()
    {
        header('Content-type: text/json');
        $this->load->helper('date');

        $data = $this->input->post();
        $data['token'] = $this->uri->rsegment(3);
        $data['novotoken'] = uniqid();
//        $data['novasenha'] = trim($_POST['novasenha']);
//        $data['confirmarsenha'] = trim($_POST['confirmarsenha']);

        if (empty($data['token'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O token não pode ficar em branco')));
        }
        if (empty($data['novasenha'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A nova senha não pode ficar em branco')));
        }
        if (empty($data['confirmarsenha'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo confirmar senha não pode ficar em branco')));
        }
        if ($data['novasenha'] !== $data['confirmarsenha']) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A senha não pode ser diferente da confirmar senha')));
        }

        $data['senha'] = $this->usuarios->setPassword($data['novasenha']);

        $usuario = $this->db->query("SELECT * FROM usuarios WHERE token = ?", $data['token']);
        if ($usuario->num_rows() == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Não existe nenhum usuário cadastrado com esse token')));
        }

        if ($this->db->where('id', $usuario->row()->id)->update('usuarios', array('senha' => $data['senha'], 'token' => $data['novotoken'], 'dataeditado' => mdate("%Y-%m-%d %H:%i:%s")))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Senha alterada com sucesso', 'redireciona' => 1, 'pagina' => site_url('home')));
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao alterar senha, tente novamente, se o erro persistir entre em contato com o administrador')));
        }
    }

    public function meuperfil()
    {
        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", $this->session->userdata('id'))->row();
        $data['fundo_tela_inicial'] = array(
            '1' => 'Imagem padrão',
//            '2' => 'Vídeo padrão',
            '3' => 'Imagem personalizada',
            '4' => 'Vídeo personalizado'
        );
        $this->load->view('meuperfil', $data);
    }

    public function editarmeuperfil_json()
    {
        header('Content-type: text/json');

        $query = $this->db->query("SELECT * FROM usuarios WHERE id = ?", $this->session->userdata('id'));
        if ($query->num_rows()) {
            $usuario = $query->row();
            $usuario->foto = utf8_decode($usuario->foto);
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Usuário não encontrado')));
        }

        $data['nome'] = $this->input->post('nome');
        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Nome" não pode ficar em branco')));
        }

        $senhaAntiga = $this->input->post('senhaantiga');
        $novaSenha = $this->input->post('novasenha');
        $confirmarNovaSenha = $this->input->post('confirmarnovasenha');

        if ($novaSenha or $confirmarNovaSenha) {
            if ($usuario->senha !== $this->usuarios->setPassword($senhaAntiga)) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'A senha antiga não confere!')));
            }

            if (empty($novaSenha) and $confirmarNovaSenha) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Nova Senha" não pode ficar em branco')));
            }

            if ($novaSenha and empty($confirmarNovaSenha)) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Confirmar Nova Senha" não pode ficar em branco')));
            }

            if ($novaSenha !== $confirmarNovaSenha) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Nova Senha" não confere com o "Confirmar Nova Senha"')));
            }

            $data['senha'] = $this->usuarios->setPassword($novaSenha);
        }

        if (!empty($_FILES['foto'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['foto']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data();
                $data['foto'] = utf8_encode($foto['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }

            if ($usuario->foto != 'avatar.jpg' && is_file('./imagens/usuarios/' . $usuario->foto) && $usuario->foto != $foto['file_name']) {
                unlink('./imagens/usuarios/' . $usuario->foto);
            }
        }

        if (!empty($_FILES['foto_descricao']) and $this->session->userdata('tipo') !== 'funcionario') {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['foto_descricao']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto_descricao')) {
                $foto_descricao = $this->upload->data();
                $data['foto_descricao'] = utf8_encode($foto_descricao['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }


            if ($usuario->foto_descricao != 'avatar.jpg' && is_file('./imagens/usuarios/' . $usuario->foto_descricao) && $usuario->foto_descricao != $foto_descricao['file_name']) {
                unlink('./imagens/usuarios/' . $usuario->foto_descricao);
            }
        }

        $data['imagem_fundo'] = null;
        $data['video_fundo'] = null;
        $data['tipo_tela_inicial'] = $this->input->post('tipo_tela_inicial');
        switch ($data['tipo_tela_inicial']) {
            case '1':
                $data['imagem_fundo'] = 'fdmrh3.jpg';
                break;
            case '2':
                $data['video_fundo'] = 'fdmrh3.jpg';
                break;
            case '3':
                if (!empty($_FILES['imagem_fundo'])) {
                    $config['upload_path'] = './imagens/usuarios/';
                    $config['allowed_types'] = 'jpg|png';
                    $config['file_name'] = utf8_decode($_FILES['imagem_fundo']['name']);

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('imagem_fundo')) {
                        $imagem_fundo = $this->upload->data();
                        $data['imagem_fundo'] = utf8_encode($imagem_fundo['file_name']);
                    } else {
                        exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                    }

                    if ($usuario->imagem_fundo != 'avatar.jpg' && is_file('./imagens/usuarios/' . $usuario->imagem_fundo) && $usuario->imagem_fundo != $imagem_fundo['file_name']) {
                        unlink('./imagens/usuarios/' . $usuario->imagem_fundo);
                    }
                }
                break;
            case '4':
                if (!empty($_FILES['video_fundo'])) {
                    $config['upload_path'] = './videos/usuarios/';
                    $config['allowed_types'] = 'mp4';
                    $config['file_name'] = utf8_decode($_FILES['video_fundo']['name']);

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('video_fundo')) {
                        $video_fundo = $this->upload->data();
                        $data['video_fundo'] = utf8_encode($video_fundo['file_name']);
                    } else {
                        exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors() . 'aa', 'redireciona' => 0, 'pagina' => '')));
                    }

                    if ($usuario->video_fundo != 'avatar.jpg' && is_file('./videos/usuarios/' . $usuario->video_fundo) && $usuario->video_fundo != $video_fundo['file_name']) {
                        unlink('./videos/usuarios/' . $usuario->video_fundo);
                    }
                }
                break;
        }

        if (!empty($_FILES['assinatura-digital'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['assinatura-digital']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('assinatura-digital')) {
                $ass_digital = $this->upload->data();
                $data['assinatura_digital'] = utf8_encode($ass_digital['file_name']);
                if ($usuario->assinatura_digital != "avatar.jpg" && file_exists('./imagens/usuarios/' . $usuario->assinatura_digital) && $usuario->assinatura_digital != $data['assinatura_digital']) {
                    @unlink('./imagens/usuarios/' . $usuario->assinatura_digital);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        $this->load->helper(array('date'));
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");

        $data['cabecalho'] = $this->input->post('cabecalho');

        if (!$this->db->where('id', $this->session->userdata('id'))->update('usuarios', $data)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar meu perfil, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        $this->session->set_userdata('nome', $data['nome']);
        if (!empty($data['cabecalho'])) {
            $this->session->set_userdata('cabecalho', $data['cabecalho']);
        }
        if (isset($data['foto'])) {
            $this->session->set_userdata('foto', $data['foto']);
            if ($this->session->userdata('tipo') !== 'funcionario') {
                $this->session->set_userdata('logomarca', $data['foto']);
            }
        }
        if (isset($data['foto_descricao'])) {
            if ($this->session->userdata('tipo') !== 'funcionario') {
                $this->session->set_userdata('foto_descricao', $data['foto_descricao']);
            }
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Meu perfil foi editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home')));
    }

    public function atualizar_filtro()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
                unset($filtro['area'][''], $filtro['setor']['']);
            }
            unset($filtro['depto']['']);
        }

        $data['area'] = form_dropdown('area', $filtro['area'], $area, 'class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'class="form-control input-sm"');
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'class="form-control input-sm"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'class="form-control input-sm"');

        echo json_encode($data);
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

        # Apaga arquivos temporários
        $arquivos_temp = $this->db->query("SELECT * FROM arquivos_temp WHERE usuario = ?", $id_usuario);
        foreach ($arquivos_temp->result() as $linha) {
            unlink($linha->arquivo);
            $this->db->where('id', $linha->id)->delete('arquivos_temp');
        }

        $url = $this->db->query("SELECT url FROM usuarios WHERE id = ?", $empresa)->row();
        if ($url->url) {
            $this->config->set_item('index_page', $url->url);
        }

        # Apaga as configurações nas sessoes nativas
        session_start();
        session_destroy();

        # Apaga as sessões do usuario
        $this->session->sess_destroy();

        # Redireciona a tela de login        
        redirect(site_url('login'));
    }

    public function novabiblioteca()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");
        $this->load->view('novabiblioteca', $data);
    }

    public function novabiblioteca_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

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

        if (empty($data['titulo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O título não pode ficar em branco')));
        }

        if (empty($data['tipo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O tipo não pode ficar em branco')));
        }

        if (empty($data['categoria'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A categoria não pode ficar em branco')));
        }

        if (empty($data['link'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O link não pode ficar em branco')));
        }

        if (!filter_var($data['link'], FILTER_VALIDATE_URL)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O link informado não é válido')));
        }

        if ($this->db->query($this->db->insert_string('biblioteca', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro da biblioteca efetuada com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/biblioteca')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro da biblioteca, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function biblioteca()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $this->load->view('biblioteca');
    }

    public function biblioteca_html()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

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

        $config['base_url'] = site_url('home/biblioteca_html');
        $config['total_rows'] = $this->db->query("SELECT * FROM biblioteca {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(3, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM biblioteca WHERE 1 = ?", array(1))->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM biblioteca {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getbiblioteca', $data);
    }

    public function editarbiblioteca()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $data['row'] = $this->db->query("SELECT * FROM biblioteca WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
        $data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");
        $this->load->view('editarbiblioteca', $data);
    }

    public function editarbiblioteca_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

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

        if (empty($data['titulo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O título não pode ficar em branco')));
        }

        if (empty($data['tipo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O tipo não pode ficar em branco')));
        }

        if (empty($data['categoria'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A categoria não pode ficar em branco')));
        }

        if (empty($data['link'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O link não pode ficar em branco')));
        }

        if (!filter_var($data['link'], FILTER_VALIDATE_URL)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O link informado não é válido')));
        }

        if ($this->db->where('id', $this->uri->rsegment(3))->update('biblioteca', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Biblioteca editada com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/biblioteca')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar biblioteca, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function getempresas()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

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

        $config['base_url'] = site_url('home/getempresas');
        $config['total_rows'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(3, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM usuarios WHERE tipo = ?", array("empresa"))->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE} ORDER BY empresa LIMIT ?,?", $dataWHERE);
        $this->load->view('getempresas', $data);
    }

    public function empresas()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $this->load->view('empresas');
    }

    public function cursosempresa()
    {
        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        $this->load->view('cursosempresa', $data);
    }

    public function getcursosempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $this->load->library('pagination');

        $query = $_POST['busca'];

        $qryWHERE = 'WHERE (tipo = ? AND publico = ?) ';
        $dataWHERE[] = "administrador";
        $dataWHERE[] = 1;
        $qryWHERE_IN = null;
        $qryWHERE_TOTAL = null;

        $cursospagos = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ?", array($this->uri->rsegment(3)));

        foreach ($cursospagos->result() as $row) {
            $qryWHERE_IN .= '?,';
            $dataWHERE[] = $row->curso;
        }

        if ($cursospagos->num_rows() > 0) {
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

        $config['base_url'] = site_url('home/getcursosempresa/' . $this->uri->rsegment(3));
        $config['total_rows'] = $this->db->query("SELECT * FROM cursos {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(4, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $dataWHERE_TOTAL[] = "administrador";
        $dataWHERE_TOTAL[] = 1;

        foreach ($cursospagos->result() as $row) {
            $qryWHERE_TOTAL .= '?,';
            $dataWHERE_TOTAL[] = $row->curso;
        }

        if ($cursospagos->num_rows() > 0) {
            $qryWHERE_TOTAL = substr($qryWHERE_TOTAL, 0, -1);
            $qryWHERE_TOTAL = "OR id IN ({$qryWHERE_TOTAL})";
        }

        $data['total'] = $this->db->query("SELECT * FROM cursos WHERE (tipo = ? AND publico = ?) {$qryWHERE_TOTAL}", $dataWHERE_TOTAL)->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM cursos {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getcursosempresa', $data);
    }

    public function excluircursosempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $this->db->query("DELETE FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($this->uri->rsegment(3), $this->uri->rsegment(4)));

        redirect(site_url('home/cursosempresa/' . $this->uri->rsegment(3)));
    }

    public function novocursoempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
        $data['cursos'] = $this->db->query("SELECT * FROM cursos WHERE usuario = ? AND status = ? OR publico = ? AND status = ? AND gratuito = ? ORDER BY curso ASC", array($this->session->userdata('id'), 1, 0, 1, 1));

        $this->load->view('novocursoempresa', $data);
    }

    public function novocursoempresa_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        $data['usuario'] = $this->uri->rsegment(3);
        $data['curso'] = $_POST['curso'];
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['data_maxima'] = implode("-", array_reverse(explode("/", $_POST['data_maxima'])));
        $data_validacao = explode('/', $_POST['data_maxima']);
        $data['colaboradores_maximo'] = (int)$_POST['colaboradores_maximo'];

        if (empty($data['curso'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));
        }

        if (empty($data['data_maxima']) || !checkdate($data_validacao[1], $data_validacao[0], $data_validacao[2])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Prencha o campo "Data máxima de acesso" corretamente')));
        }

        $verifcacurso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($data['curso']))->row(0);

        if ($verifcacurso->tipo != "administrador" && $verifcacurso->publico != 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));
        }

        $verificacursoempresa = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", $data);

        if ($verificacursoempresa->num_rows() > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento já está vinculado para essa empresa!')));
        }

        if ($this->db->query($this->db->insert_string('usuarioscursos', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de treinamento para empresa efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/cursosempresa/' . $data['usuario'])));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de treinamento para empresa, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editarcursoempresa($empresa, $curso)
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $query = $this->db->query("SELECT * FROM usuarios WHERE id = ? AND tipo = ? ", array($this->uri->rsegment(3), "empresa"));
        $data['row'] = $query->row(0);
        $data['cursos'] = $this->db->query("SELECT * FROM cursos WHERE usuario = ? AND status = ? OR publico = ? AND status = ? AND gratuito = ? ORDER BY curso ASC", array($this->session->userdata('id'), 1, 0, 1, 1));
        $query_edicao = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($data['row']->id, $this->uri->rsegment(4)));
        $data['curso_edicao'] = $query_edicao->row(0);

        if ($query->num_rows() < 1 || $query_edicao->num_rows() < 1) {
            redirect(site_url('home/empresas'));
        }

        $this->load->view('editarcursoempresa', $data);
    }

    public function editarcursoempresa_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        $data['usuario'] = $this->uri->rsegment(3);
        $data['curso'] = $_POST['curso'];
        $data['data_maxima'] = implode("-", array_reverse(explode("/", $_POST['data_maxima'])));
        $data_validacao = explode('/', $_POST['data_maxima']);
        $data['colaboradores_maximo'] = (int)$_POST['colaboradores_maximo'];

        if (empty($data['curso'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));
        }

        if (empty($data['data_maxima']) || !checkdate($data_validacao[1], $data_validacao[0], $data_validacao[2])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Prencha o campo "Data máxima de acesso" corretamente')));
        }

        $verifcacurso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($data['curso']))->row(0);

        if ($verifcacurso->tipo != "administrador" && $verifcacurso->publico != 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));
        }

        $verificacursoempresa = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", $data);

        if ($verificacursoempresa->num_rows() <> 1) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento já está vinculado para essa empresa!')));
        }

        if ($this->db->where('id', $verificacursoempresa->row(0)->id)->update('usuarioscursos', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Edição de treinamento para empresa efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/cursosempresa/' . $data['usuario'])));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de treinamento para empresa, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function novaempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $this->load->view('novaempresa');
    }

    public function novaempresa_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        $data['tipo'] = "empresa";
        $data['url'] = $_POST['url'];
        $data['nome'] = $_POST['empresa'];
        $data['foto'] = "avatar.jpg";
        $data['email'] = $_POST['email'];
        $data['senha'] = $_POST['senha'];
        $data['token'] = uniqid();
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['status'] = $_POST['status'];
        $data['max_colaboradores'] = $_POST['max_colaboradores'];
        if (empty($data['max_colaboradores'])) {
            $data['max_colaboradores'] = null;
        }

        if (empty($data['url'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "URL" não pode ficar em branco')));
        }

        $verificaURL = $this->db->query("SELECT * FROM usuarios WHERE url = ?", array($data['url']));
        if ($verificaURL->num_rows() > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Já existe uma empresa com essa URL!')));
        }

        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Empresa" não pode ficar em branco')));
        }

        if (empty($data['email'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "E-mail" não pode ficar em branco')));
        }

        if (strlen($data['max_colaboradores']) > 0 and $data['max_colaboradores'] < 1) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Qtde. máxima colaboradores" não pode ter valor inferior a 1')));
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));
        }

        $verificaemail = $this->db->query("SELECT * FROM usuarios WHERE email = ?", array($data['email']));
        if ($verificaemail->num_rows() > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse endereço de e-mail já está em uso')));
        }

        if (empty($data['senha'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));
        }

        if ($data['senha'] != $_POST['confirmarsenha']) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));
        }

        $data['senha'] = $this->usuarios->setPassword($data['senha']);

        /* Logomarca */
        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                $data['foto'] = utf8_encode($foto['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }
        /* Foto da descrição da empresa */
        if (!empty($_FILES['logo_descricao'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo_descricao']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo_descricao')) {
                $foto_descricao = $this->upload->data();
                $data['foto_descricao'] = utf8_encode($foto_descricao['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Imagem Inicial */
        if (!empty($_FILES['imagem-inicial'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['imagem-inicial']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('imagem-inicial')) {
                $img_inicial = $this->upload->data();
                $data['imagem_inicial'] = utf8_encode($img_inicial['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Imagem de Fundo */
        $imagemFundoPadrao = $this->input->post('imagem_fundo_padrao');
        if ($imagemFundoPadrao) {
            $data['imagem_fundo'] = 'fdmrh3.jpg';
        } elseif (!empty($_FILES['imagem_fundo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'jpg|png';
            $config['file_name'] = utf8_decode($_FILES['imagem_fundo']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('imagem_fundo')) {
                $img_fundo = $this->upload->data();
                $data['imagem_fundo'] = utf8_encode($img_fundo['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Assinatura Digital */
        if (!empty($_FILES['assinatura-digital'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['assinatura-digital']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('assinatura-digital')) {
                $ass_digital = $this->upload->data();
                $data['assinatura_digital'] = utf8_encode($ass_digital['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        $visualizacao_pilula_conhecimento = $this->input->post('visualizacao_pilula_conhecimento');
        if ($visualizacao_pilula_conhecimento) {
            $data['visualizacao_pilula_conhecimento'] = $visualizacao_pilula_conhecimento;
        }

        if ($this->db->query($this->db->insert_string('usuarios', $data))) {
            $hash_acesso = $this->input->post('hash_acesso');
            if ($hash_acesso) {
                $id = $this->db->insert_id();
//                $this->load->library('encrypt');
//                $data['hash_acesso'] = $this->encrypt->encode(json_encode($hash_acesso), base64_encode($id));
                $data['hash_acesso'] = json_encode($hash_acesso);
                $this->db->update('usuarios', array('hash_acesso' => $data['hash_acesso']), array('id' => $id));
            }
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de empresa efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/empresas')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de empresa, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editarempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $empresa = $this->db->query("SELECT * FROM usuarios WHERE tipo = ? AND id = ?", array("empresa", $this->uri->rsegment(3)));

        if ($empresa->num_rows() == 0) {
            redirect(site_url('home/empresas'));
        }

        $data['row'] = $empresa->row(0);
        if ($data['row']->hash_acesso) {
//            $this->load->library('encrypt');
//            $data['row']->hash_acesso = $this->encrypt->decode($data['row']->hash_acesso, base64_encode($data['row']->id));
//            $data['row']->hash_acesso = json_decode($data['row']->hash_acesso, true);
        } else {
            $data['row']->hash_acesso = 'null';
        }

        $this->db->where('empresa', $data['row']->id);
        $this->db->where('tipo', 'funcionario');
        $data['total_colaboradores'] = $this->db->get('usuarios')->num_rows();

        $this->load->view('editarempresa', $data);
    }

    public function editarempresa_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        $empresa = $this->db->query("SELECT * FROM usuarios WHERE tipo = ?  AND id = ?", array("empresa", $this->uri->rsegment(3)))->row(0);
        if (isset($empresa->foto)) {
            $empresa->foto = utf8_decode($empresa->foto);
        }

        $data['url'] = $_POST['url'];
        $data['nome'] = $_POST['empresa'];
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['status'] = $_POST['status'];
        $data['email'] = $_POST['email'];
        $data['max_colaboradores'] = $_POST['max_colaboradores'];
        if (empty($data['max_colaboradores'])) {
            $data['max_colaboradores'] = null;
        }

        if (empty($data['url'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "URL" não pode ficar em branco')));
        }

        $verificaURL = $this->db->query("SELECT * FROM usuarios WHERE id <> ? AND url = ?", array($this->uri->rsegment(3), $data['url']));
        if ($verificaURL->num_rows() > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Já existe uma empresa com essa URL!')));
        }

        $verificaEmail = $this->db->query("SELECT * FROM usuarios WHERE email = ? AND id <> ?", array($data['email'], $this->uri->rsegment(3)));
        if ($verificaEmail->num_rows() > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Já existe um usuário com esse email!')));
        }

        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Empresa" não pode ficar em branco')));
        }

        if (strlen($data['max_colaboradores']) > 0 and $data['max_colaboradores'] < 1) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Qtde. máxima colaboradores" não pode ter valor inferior a 1')));
        }

        $this->db->where('empresa', $empresa->id);
        $this->db->where('tipo', 'funcionario');
        $max_colaboradores = $this->db->get('usuarios')->num_rows();
        if (strlen($data['max_colaboradores']) > 0 and $data['max_colaboradores'] < $max_colaboradores) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Qtde. máxima colaboradores" não pode ter valor inferior a ' . $max_colaboradores)));
        }

        if ($_POST['senha'] != '') {
            if (empty($_POST['senha'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));
            }

            if ($_POST['senha'] != $_POST['confirmarsenha']) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));
            }

            $data['senha'] = $this->usuarios->setPassword($_POST['senha']);
        }

        /* Logomarca */
        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                $data['foto'] = utf8_encode($foto['file_name']);
                if ($empresa->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->foto) && $empresa->foto != $foto['file_name']) {
                    @unlink('./imagens/usuarios/' . $empresa->foto);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Foto da descrição da empresa */
        if (!empty($_FILES['logo_descricao'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo_descricao']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo_descricao')) {
                $foto_descricao = $this->upload->data();
                $data['foto_descricao'] = utf8_encode($foto_descricao['file_name']);
                if ($empresa->foto_descricao != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->foto_descricao) && $empresa->foto_descricao != $data['foto_descricao']) {
                    @unlink('./imagens/usuarios/' . $empresa->foto_descricao);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Imagem Inicial */
        if (!empty($_FILES['imagem-inicial'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['imagem-inicial']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('imagem-inicial')) {
                $img_inicial = $this->upload->data();
                $data['imagem_inicial'] = utf8_encode($img_inicial['file_name']);
                if ($empresa->imagem_inicial != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->imagem_inicial) && $empresa->imagem_inicial != $data['imagem_inicial']) {
                    @unlink('./imagens/usuarios/' . $empresa->imagem_inicial);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Imagem de Fundo */
        $imagemFundoPadrao = $this->input->post('imagem_fundo_padrao');
        if ($imagemFundoPadrao) {
            $data['imagem_fundo'] = 'fdmrh3.jpg';
        } elseif (!empty($_FILES['imagem_fundo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'jpg|png';
            $config['file_name'] = utf8_decode($_FILES['imagem_fundo']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('imagem-inicial')) {
                $img_fundo = $this->upload->data();
                $data['imagem_fundo'] = utf8_encode($img_fundo['file_name']);
                if ($empresa->imagem_fundo != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->imagem_fundo) && $empresa->imagem_fundo != $data['imagem_fundo']) {
                    @unlink('./imagens/usuarios/' . $empresa->imagem_fundo);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        /* Assinatura Digital */
        if (!empty($_FILES['assinatura-digital'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['assinatura-digital']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('assinatura-digital')) {
                $ass_digital = $this->upload->data();
                $data['assinatura_digital'] = utf8_encode($ass_digital['file_name']);
                if ($empresa->assinatura_digital != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->assinatura_digital) && $empresa->assinatura_digital != $data['assinatura_digital']) {
                    @unlink('./imagens/usuarios/' . $empresa->assinatura_digital);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        $visualizacao_pilula_conhecimento = $this->input->post('visualizacao_pilula_conhecimento');
        if ($visualizacao_pilula_conhecimento) {
            $data['visualizacao_pilula_conhecimento'] = $visualizacao_pilula_conhecimento;
        } else {
            $data['visualizacao_pilula_conhecimento'] = null;
        }

        $hash_acesso = $this->input->post('hash_acesso');
        if ($hash_acesso) {
//            $this->load->library('encrypt');
//            $data['hash_acesso'] = $this->encrypt->encode(json_encode($hash_acesso), base64_encode($empresa->id));
            $data['hash_acesso'] = json_encode($hash_acesso);
        } else {
            $data['hash_acesso'] = null;
        }

        if ($this->db->where('id', $empresa->id)->update('usuarios', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Empresa editada com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/empresas')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar empresa, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function excluirempresa()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador"))) {
            redirect(site_url('home'));
        }

        $empresa = $this->db->query("SELECT * FROM usuarios WHERE tipo = ? AND id = ?", array("empresa", $this->uri->rsegment(3)));

        if ($empresa->num_rows() == 0) {
            redirect(site_url('home/empresas'));
        }

        $empresa = $empresa->row(0);

        if ($empresa->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->foto)) {
            @unlink('./imagens/usuarios/' . $empresa->foto);
        }
        if ($empresa->foto_descricao != "avatar.jpg" && file_exists('./imagens/usuarios/' . $empresa->foto_descricao)) {
            @unlink('./imagens/usuarios/' . $empresa->foto_descricao);
        }

        $this->db->where('id', $empresa->id)->delete('usuarios');

        redirect(site_url('home/empresas'));
    }

    public function cursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $data['categorias'] = $this->db->query("SELECT categoria FROM cursos WHERE CHAR_LENGTH(categoria) > 0 GROUP BY categoria");
        $data['areas_conhecimento'] = $this->db->query("SELECT area_conhecimento FROM cursos WHERE CHAR_LENGTH(area_conhecimento) > 0 GROUP BY area_conhecimento");

        $this->load->view('cursos', $data);
    }

    public function getcursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        header('Content-Type: text/html; charset=utf-8');

        $this->load->library('pagination');

        $query = $this->input->post('busca');
        $area_conhecimento = $this->input->post('area_conhecimento');
        $categoria = $this->input->post('categoria');
        $busca = $this->input->post('busca');

        $query_categoria = null;
        $query_areaConhecimento = null;
        $query_busca = null;

        # Verificar preenchimento dos filtros
        if (!empty($categoria)) {
            $query_categoria = " AND c.categoria = ? ";
        }
        if (!empty($area_conhecimento)) {
            $query_areaConhecimento = " AND c.area_conhecimento = ? ";
        }
        if (!empty($busca)) {
            $query_busca = " AND c.curso LIKE '" . $busca . "%' ";
        }

        if ($this->session->userdata('tipo') == "administrador") {
            $qryWHERE = "WHERE c.usuario = ? $query_categoria $query_areaConhecimento $query_busca";
            $dataWHERE[] = $this->session->userdata('id');

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }
        } else {
            $qryWHERE = "(SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario WHERE c.publico = 1 $query_categoria $query_areaConhecimento $query_busca) UNION (SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario WHERE c.status = 1 AND c.publico = 1 $query_categoria $query_areaConhecimento $query_busca) UNION (SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario WHERE c.tipo = 'administrador'AND c.status = 1 $query_categoria $query_areaConhecimento $query_busca)";
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

            //$dataWHERE[] = 'administrador';
            //$dataWHERE[] = $this->session->userdata('id');
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

        $config['base_url'] = site_url('home/getcursos');

        if ($this->session->userdata('tipo') != 'administrador') {
            $config['total_rows'] = $this->db->query("(SELECT c.* FROM cursos c WHERE c.usuario = 1 $query_categoria $query_areaConhecimento $query_busca) UNION {$qryWHERE}", $dataWHERE)->num_rows();
        } else {
            $config['total_rows'] = $this->db->query("SELECT c.* FROM cursos c WHERE c.usuario = ?", $dataWHERE)->num_rows();
        }

        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(3, 0);
        $dataWHERE[] = (int)$config['per_page'];
        $data['total'] = $config['total_rows'];
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";

        if ($this->session->userdata('tipo') != 'administrador') {
            $data['query'] = $this->db->query("(SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario WHERE c.usuario = ? $query_categoria $query_areaConhecimento $query_busca) UNION {$qryWHERE} ORDER BY usuario = ? DESC, curso ASC LIMIT ?,?", $dataWHERE);
        } else {
            $data['query'] = $this->db->query("SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario {$qryWHERE} ORDER BY c.id ASC LIMIT ?,?", $dataWHERE);
        }

        $this->load->view('getcursos', $data);
    }

    public function paginascurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                redirect(site_url('home/cursos'));
            }
        }

        $data['row'] = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        $this->load->view('paginascurso', $data);
    }

    public function getpaginascurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $this->load->library('pagination');

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                exit('Você não tem acesso a essa página!');
            }
        }

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

        $config['base_url'] = site_url('home/getpaginascurso/' . $curso->id);
        $config['total_rows'] = $this->db->query("SELECT * FROM paginas {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 9999;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(4, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM paginas WHERE curso = ?", array($curso->id))->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM paginas {$qryWHERE} ORDER BY ordem ASC LIMIT ?,?", $dataWHERE);
        $this->load->view('getpaginascurso', $data);
    }

    public function ordempaginascurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                redirect(site_url('home/cursos'));
            }
        }

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
        $dataWHERE[] = $this->uri->rsegment(3);

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

        $config['base_url'] = site_url('home/getbiblioteca_html/' . $this->uri->rsegment(3) . '/' . $this->uri->rsegment(4));
        $config['total_rows'] = $this->db->query("SELECT * FROM biblioteca {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;
        $config['uri_segment'] = 5;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(5, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM biblioteca WHERE tipo = ?", array($this->uri->rsegment(3)))->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM biblioteca {$qryWHERE} ORDER BY titulo LIMIT ?,?", $dataWHERE);
        $this->load->view('getbibliotecapagina', $data);
    }

    public function novapaginacurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }
        }

        $data['row'] = $curso;

        $data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");

        $this->load->view('novapaginacurso', $data);
//        $this->load->view('teste', $data);
    }

    public function novapaginacurso_json()
    {
        @ini_set('upload_max_filesize', '100M');
        @ini_set('post_max_size', '100M');
        @ini_set('max_execution_time', '300');

        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        $queryCurso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)));
        if ($queryCurso->num_rows()) {
            $curso = $queryCurso->row();
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Curso não encontrado')));
        }

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }
        }

        $pagina = $this->db->query("SELECT * FROM paginas WHERE curso = ? ORDER BY ordem DESC LIMIT 1", array($curso->id));

        if ($pagina->num_rows() == 0) {
            $ordem = 0;
        } else {
            $pagina = $pagina->row(0);
            $ordem = $pagina->ordem + 1;
        }

        //Áudio e Vídeo
        $data['audio'] = $this->input->post('arquivo_audio');
        $data['video'] = $this->input->post('arquivo_video');

        $data['modulo'] = $this->input->post('modulo');
        $data['curso'] = $curso->id;
        $data['titulo'] = $this->input->post('titulo');
        $data['ordem'] = $ordem;
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        if (empty($data['modulo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não pode ficar em branco')));
        }

        if (!in_array($data['modulo'], array('ckeditor', 'arquivos-pdf', 'quiz', 'atividades', 'video-youtube', 'aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não é válido')));
        }

        if (empty($data['titulo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Título" não pode ficar em branco')));
        }

        if ($data['modulo'] == "ckeditor") {

            $data['conteudo'] = $this->input->post('conteudo');
            $data['pdf'] = "";
            $data['youtube'] = "";

            if (empty($data['conteudo'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Conteúdo" não pode ficar em branco')));
            }
        } else if ($data['modulo'] == "arquivos-pdf") {

            if (!empty($_FILES['arquivo'])) {

                $config['upload_path'] = './arquivos/pdf/';
                $config['allowed_types'] = 'pdf|doc|docx|txt|ppt|pptx';
                $config['file_name'] = utf8_decode($_FILES['arquivo']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('arquivo')) {
                    $arquivo = $this->upload->data();
                    $data['pdf'] = utf8_encode($arquivo['file_name']);
                    $data['conteudo'] = "";
                    $data['youtube'] = "";

                    if ($arquivo['file_ext'] === '.doc' || $arquivo['file_ext'] === '.docx' || $arquivo['file_ext'] === '.txt' || $arquivo['file_ext'] === '.ppt' || $arquivo['file_ext'] === '.pptx') {
                        shell_exec("unoconv -f pdf " . $config['upload_path'] . $arquivo['file_name']);
                        $data['pdf'] = utf8_encode($arquivo['raw_name']) . ".pdf";
                    }
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
            }
        } else if ($data['modulo'] == "video-youtube") {
            $data['conteudo'] = $this->input->post('descricaoyoutube');
            $data['youtube'] = "";

            $url_video = $this->input->post('videoyoutube');
            if (empty($url_video)) {
                if (!empty($_FILES['arquivoVideo'])) {

                    $config['upload_path'] = './arquivos/videos/';
                    $config['allowed_types'] = '*';
                    $config['upload_max_filesize'] = '10240';
                    $config['file_name'] = utf8_decode($_FILES['arquivoVideo']['name']);

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('arquivoVideo')) {
                        $arquivo = $this->upload->data();
                        $data['arquivoVideo'] = utf8_encode($arquivo['file_name']);

                        if ($arquivo['file_ext'] != '.mp4') {
                            $aviso = "Apenas vídeos .mp4 são suportados!";
                            exit(json_encode(array('retorno' => 0, 'aviso' => "Arquivo " + $arquivo['file_ext'] + "." + $aviso, 'redireciona' => 0, 'pagina' => '')));
                        }
                    } else {
                        exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                    }
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
                }
            } else {

                $url_final = $url_video;
                switch ($url_video) {
                    # Youtube anterior
                    case strpos($url_video, 'youtube') > 0:
                        parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
                        // Verifica se a url está correta
                        if (isset($url['v'])) {
                            $url_final = "https://www.youtube.com/watch?v=" . $url['v'];
                        }
                        break;
                    # Youtube novo
                    case strpos($url_video, 'youtu.be') > 0:
                        $url_video = explode('/', $url_video);
                        $_POST['videoyoutube'] = "https://www.youtube.com/watch?v=" . $url_video[3];
                        parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
                        // Verifica se a url está correta
                        if (isset($url['v'])) {
                            $url_final = "https://www.youtube.com/watch?v=" . $url['v'];
                        }
                        break;
                    # Vimeo
                    case strpos($url_video, 'vimeo') > 0:
                        $url_video = explode('/', $url_video);
                        // usando file_get_contents para pegar os dados e unserializando o array
                        $video = unserialize(file_get_contents("https://vimeo.com/api/v2/video/{$url_video[3]}.php"));
                        // Verifica se a url está correta
                        if (isset($video[0]['url'])) {
                            $url_final = $video[0]['url'];
                        }
                        break;
                    case strpos($url_video, 'slideshare') > 0:
                        $video = json_decode(file_get_contents("https://pt.slideshare.net/api/oembed/2?url=$url_video&format=json"));
                        if (isset($video->html)) {
                            $url_1 = explode('src="', $video->html);
                            $url_1 = explode('https://www.slideshare.net/slideshow/embed_code/key/', $url_1[1]);
                            $url_1 = explode('"', $url_1[1]);
                            $url_final = "https://pt.slideshare.net/slideshow/embed_code/key/" . $url_1[0];
                        }
                        break;
                    case strpos($url_video, 'dailymotion') > 0:
                        $video = json_decode(file_get_contents("https://www.dailymotion.com/services/oembed?url=$url_video"));
                        if (isset($video->html)) {
                            $url_1 = explode('src="', $video->html);
                            $url_1 = explode('https://www.dailymotion.com/embed/video/', $url_1[1]);
                            $url_1 = explode('"', $url_1[1]);
                            $url_final = "https://www.dailymotion.com/embed/video/" . $url_1[0];
                        }
                        break;
                }

                $data['youtube'] = $url_final;
                $data['conteudo'] = $this->input->post('descricaoyoutube');
                $data['pdf'] = "";
                $data['arquivoVideo'] = "";

                if (empty($_POST['videoyoutube'])) {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não pode ficar em branco')));
                }

                if (empty($data['youtube'])) {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não é válido')));
                }
            }
        } else if ($data['modulo'] == "quiz") {

            $pid_ = array();
            $aid_ = array();
            $quiz = array();

            $perguntas = $this->input->post('perguntas');

            foreach ($perguntas as $pid => $pergunta) {
                $pid_[] = $pid;
                $quiz[$pid] = array('pid' => $pid, 'pergunta' => $pergunta, 'tipoquestao' => $_POST['tipoquestao'][$pid], 'respostacorreta' => $_POST['respostacorreta'][$pid], 'respostaerrada' => $_POST['respostaerrada'][$pid], 'quantidade' => count($_POST['alternativas'][$pid]));
                if (!empty($pergunta) && $_POST['tipoquestao'][$pid] == 1) {
                    $alternativas = $_POST['alternativas'][$pid];
                    foreach ($alternativas as $aid => $alternativa) {
                        $aid_[] = $aid;
                        if (!empty($alternativa)) {
                            $quiz[$pid]['perguntas'][] = array(
                                'aid' => $aid,
                                'alternativa' => $alternativa,
                                'correta' => isset($_POST['corretas'][$aid]) ? 1 : 0
                            );
                        }
                    }
                }
            }

            foreach ($quiz as $row) {
                if ($row['tipoquestao'] == 1) {

                    if ($row['quantidade'] == 0) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa!")));
                    }

                    $alternativaCorretas = 0;
                    foreach ($row['perguntas'] as $row_) {
                        if ($row_['correta'] == 1) {
                            $alternativaCorretas++;
                        }
                    }

                    if ($alternativaCorretas == 0) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa correta!")));
                    }
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
                            $quiz[$pid]['perguntas'][] = array(
                                'aid' => $aid,
                                'alternativa' => $alternativa,
                                'correta' => isset($_POST['corretas'][$aid]) ? 1 : 0
                            );
                        }
                    }
                }
            }

            foreach ($quiz as $row) {
                if ($row['tipoquestao'] == 1) {

                    if ($row['quantidade'] == 0) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa!")));
                    }

                    $alternativaCorretas = 0;
                    foreach ($row['perguntas'] as $row_) {
                        if ($row_['correta'] == 1) {
                            $alternativaCorretas++;
                        }
                    }

                    if ($alternativaCorretas == 0) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa correta!")));
                    }
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
        } else if (in_array($data['modulo'], array('aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {

            $data['categoriabiblioteca'] = $_POST['categoriabiblioteca'];
            $data['titulobiblioteca'] = $_POST['titulobiblioteca'];
            $data['tagsbiblioteca'] = $_POST['tagsbiblioteca'];
            $data['biblioteca'] = $_POST['biblioteca'];

            if (empty($_POST['biblioteca'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione um item da Biblioteca para continuar')));
            }
        }

        if ($this->db->query($this->db->insert_string('paginas', $data))) {
            if (count(@$data['audio']) > 0) {
                # Apaga arquivos temporários
                $arquivos_temp = $this->db->query("
                SELECT * FROM arquivos_temp
                WHERE usuario = ? AND arquivo = ?", array($this->session->userdata('id'), './arquivos/media/' . $data['audio']));

                foreach ($arquivos_temp->result() as $linha) {
                    $this->db->where('id', $linha->id)->delete('arquivos_temp');
                }
            }

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
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro da página efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/paginascurso/' . $curso->id)));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro da página, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editarpaginacurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $pagina = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($pagina->curso))->row(0);

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                redirect(site_url('home/cursos'));
            }
        }

        $data['curso'] = $curso;
        $data['row'] = $pagina;

        $data['quizperguntas'] = $this->db->query("SELECT * FROM quizperguntas WHERE pagina = ? ORDER BY id ASC", array($pagina->id));
        foreach ($data['quizperguntas']->result() as $quiz) {
            $data['quizalternativas'][$quiz->id] = $this->db->query("SELECT * FROM quizalternativas WHERE quiz = ? ORDER BY id ASC", array($quiz->id));
        }
        $data['atividadesperguntas'] = $this->db->query("SELECT * FROM atividadesperguntas WHERE pagina = ? ORDER BY id ASC", array($pagina->id));
        foreach ($data['atividadesperguntas']->result() as $alternativa) {
            $data['atividadesalternativas'][$alternativa->id] = $this->db->query("SELECT * FROM atividadesalternativas WHERE quiz = ? ORDER BY id ASC", array($alternativa->id));
        }
        $data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");

        $this->load->view('editarpaginacurso', $data);
    }

    public function editarpaginacurso_json()
    {
        @ini_set('upload_max_filesize', '100M');
        @ini_set('post_max_size', '100M');
        @ini_set('max_execution_time', '300');

        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        $queryPagina = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($this->uri->rsegment(3)));
        if ($queryPagina->num_rows()) {
            $pagina = $queryPagina->row();
            $pagina->pdf = utf8_decode($pagina->pdf);
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Usuário não encontrado')));
        }
        $queryCurso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($pagina->curso));
        if ($queryCurso->num_rows()) {
            $curso = $queryCurso->row();
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Curso não encontrado')));
        }

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }
        }

        //Áudio e Vídeo
        if (isset($_POST['arquivo_audio'])) {
            $data['audio'] = $_POST['arquivo_audio'];
        }
        if (isset($_POST['arquivo_video'])) {
            $data['video'] = $_POST['arquivo_video'];
        }

        $data['curso'] = $curso->id;
        $data['modulo'] = $_POST['modulo'];
        $data['titulo'] = $_POST['titulo'];
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");

        if (empty($data['modulo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não pode ficar em branco')));
        }

        if (!in_array($data['modulo'], array('ckeditor', 'arquivos-pdf', 'quiz', 'atividades', 'video-youtube', 'aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não é válido')));
        }

        if (empty($data['titulo'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Título" não pode ficar em branco')));
        }

        if ($data['modulo'] == "ckeditor") {

            $data['conteudo'] = $_POST['conteudo'];
            $data['pdf'] = "";
            $data['youtube'] = "";

            if (empty($data['conteudo'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Conteúdo" não pode ficar em branco')));
            }
        } else if ($data['modulo'] == "arquivos-pdf") {
            if (!empty($_FILES['arquivo'])) {
                $config['upload_path'] = './arquivos/pdf/';
                $config['allowed_types'] = 'pdf|doc|docx|txt|ppt|pptx';
                $config['file_name'] = utf8_decode($_FILES['arquivo']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('arquivo')) {
                    $arquivo = $this->upload->data();
                    $data['pdf'] = utf8_encode($arquivo['file_name']);
                    $data['conteudo'] = "";
                    $data['youtube'] = "";

                    if (in_array($arquivo['file_ext'], array('.pdf', '.doc', '.docx', '.txt', '.ppt', '.pptx'))) {
                        shell_exec("unoconv -f pdf " . $config['upload_path'] . $arquivo['file_name']);
                        $data['pdf'] = utf8_encode($arquivo['raw_name']) . ".pdf";
                    }

                    if (is_file('./arquivos/pdf/' . $pagina->pdf) && $pagina->pdf != $data['pdf']) {
                        @unlink('./arquivos/pdf/' . $pagina->pdf);
                    }
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            } else if ($pagina->pdf == "") {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
            }
        } else if ($data['modulo'] == "video-youtube") {
            $data['conteudo'] = $_POST['descricaoyoutube'];
            $data['youtube'] = "";

            if (empty($_POST['videoyoutube'])) {
                if (!empty($_FILES['arquivoVideo'])) {

                    $config['upload_path'] = './arquivos/videos/';
                    $config['allowed_types'] = '*';
                    $config['upload_max_filesize'] = '10240';
                    $config['file_name'] = utf8_decode($_FILES['arquivoVideo']['name']);

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('arquivoVideo')) {
                        $arquivo = $this->upload->data();
                        $data['arquivoVideo'] = utf8_encode($arquivo['file_name']);

                        if ($arquivo['file_ext'] != '.mp4') {
                            $aviso = "Apenas vídeos .mp4 são suportados!";
                            exit(json_encode(array('retorno' => 0, 'aviso' => "Arquivo " + $arquivo['file_ext'] + "." + $aviso, 'redireciona' => 0, 'pagina' => '')));
                        }
                    } else {
                        exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                    }
                } else {
                    if (empty($pagina->arquivoVideo)) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
                    }
                }
            } else {

                $url_video = $_POST['videoyoutube'];
                $url_final = $_POST['videoyoutube'];

                switch ($url_video) {
                    # Youtube anterior
                    case strpos($url_video, 'youtube') > 0:
                        parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
                        // Verifica se a url está correta
                        if (isset($url['v'])) {
                            $url_final = "https://www.youtube.com/watch?v=" . $url['v'];
                        }
                        break;
                    # Youtube novo
                    case strpos($url_video, 'youtu.be') > 0:
                        $url_video = explode('/', $url_video);
                        $_POST['videoyoutube'] = "https://www.youtube.com/watch?v=" . $url_video[3];
                        parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
                        // Verifica se a url está correta
                        if (isset($url['v'])) {
                            $url_final = "https://www.youtube.com/watch?v=" . $url['v'];
                        }
                        break;
                    # Vimeo
                    case strpos($url_video, 'vimeo') > 0:
                        $url_video = explode('/', $url_video);
                        // usando file_get_contents para pegar os dados e unserializando o array
                        $video = unserialize(file_get_contents("https://vimeo.com/api/v2/video/{$url_video[3]}.php"));
                        // Verifica se a url está correta
                        if (isset($video[0]['url'])) {
                            $url_final = $video[0]['url'];
                        }
                        break;
                    case strpos($url_video, 'slideshare') > 0:
                        $video = json_decode(file_get_contents("https://pt.slideshare.net/api/oembed/2?url=$url_video&format=json"));
                        if (isset($video->html)) {
                            $url_1 = explode('src="', $video->html);
                            $url_1 = explode('https://www.slideshare.net/slideshow/embed_code/key/', $url_1[1]);
                            $url_1 = explode('"', $url_1[1]);
                            $url_final = "https://pt.slideshare.net/slideshow/embed_code/key/" . $url_1[0];
                        }
                        break;
                    case strpos($url_video, 'dailymotion') > 0:
                        $video = json_decode(file_get_contents("https://www.dailymotion.com/services/oembed?url=$url_video"));
                        if (isset($video->html)) {
                            $url_1 = explode('src="', $video->html);
                            $url_1 = explode('https://www.dailymotion.com/embed/video/', $url_1[1]);
                            $url_1 = explode('"', $url_1[1]);
                            $url_final = "https://www.dailymotion.com/embed/video/" . $url_1[0];
                        }
                        break;
                }

                $data['youtube'] = $url_final;
                $data['conteudo'] = $_POST['descricaoyoutube'];
                $data['pdf'] = "";
                $data['arquivoVideo'] = "";

                if (empty($_POST['videoyoutube'])) {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não pode ficar em branco')));
                }

                if (empty($data['youtube'])) {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não é válido')));
                }
            }
        } else if ($data['modulo'] == "quiz") {

            $pid_ = array();
            $aid_ = array();
            $quiz = array();

            $perguntas = $_POST['perguntas'];

            foreach ($perguntas as $pid => $pergunta) {
                $pid_[] = $pid;
                $quiz[$pid] = array('pid' => $pid, 'pergunta' => $pergunta, 'tipoquestao' => $_POST['tipoquestao'][$pid], 'respostacorreta' => $_POST['respostacorreta'][$pid], 'respostaerrada' => $_POST['respostaerrada'][$pid], 'quantidade' => isset($_POST['alternativas'][$pid]) ? count($_POST['alternativas'][$pid]) : 0);
                if (!empty($pergunta) && $_POST['tipoquestao'][$pid] == 1) {
                    $alternativas = $_POST['alternativas'][$pid];
                    foreach ($alternativas as $aid => $alternativa) {
                        $aid_[] = $aid;
                        if (!empty($alternativa)) {
                            $quiz[$pid]['perguntas'][] = array(
                                'aid' => $aid,
                                'alternativa' => $alternativa,
                                'correta' => isset($_POST['corretas'][$aid]) ? 1 : 0
                            );
                        }
                    }
                }
            }

            foreach ($quiz as $row) {
                if ($row['tipoquestao'] == 1) {

                    if ($row['quantidade'] == 0) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa!")));
                    }

                    $alternativaCorretas = 0;
                    foreach ($row['perguntas'] as $row_) {
                        if ($row_['correta'] == 1) {
                            $alternativaCorretas++;
                        }
                    }

                    if ($alternativaCorretas == 0) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa correta!")));
                    }
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

                $verificaPergunta = $this->db->query("SELECT * FROM quizperguntas WHERE id = ? AND pagina = ?", array($row['pid'], $pagina->id))->num_rows();

                if ($verificaPergunta > 0) {

                    $this->db->where('id', $row['pid'])->update('quizperguntas', array('pergunta' => $row['pergunta'], 'tipo' => $row['tipoquestao'], 'respostacorreta' => $row['respostacorreta'], 'respostaerrada' => $row['respostaerrada']));

                    if (isset($row['perguntas'])) {
                        foreach ($row['perguntas'] as $row_) {
                            $verificaAlternativa = $this->db->query("SELECT * FROM quizalternativas WHERE id = ? AND quiz = ?", array($row_['aid'], $row['pid']))->num_rows();

                            if ($verificaAlternativa > 0) {
                                $this->db->where('id', $row_['aid'])->update('quizalternativas', array('alternativa' => $row_['alternativa'], 'correta' => $row_['correta']));
                            } else {
                                $dataQuizAlternativas['quiz'] = $row['pid'];
                                $dataQuizAlternativas['alternativa'] = $row_['alternativa'];
                                $dataQuizAlternativas['correta'] = $row_['correta'];
                                $this->db->query($this->db->insert_string('quizalternativas', $dataQuizAlternativas));
                            }
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
                $quiz[$pid] = array('pid' => $pid, 'pergunta' => $pergunta, 'tipoquestao' => $_POST['tipoquestao'][$pid], 'respostacorreta' => $_POST['respostacorreta'][$pid], 'respostaerrada' => $_POST['respostaerrada'][$pid], 'quantidade' => (isset($_POST['alternativas'][$pid]) ? count($_POST['alternativas'][$pid]) : 0));
                if (!empty($pergunta) && $_POST['tipoquestao'][$pid] == 1) {
                    $alternativas = $_POST['alternativas'][$pid];
                    foreach ($alternativas as $aid => $alternativa) {
                        $aid_[] = $aid;
                        if (!empty($alternativa)) {
                            $quiz[$pid]['perguntas'][] = array(
                                'aid' => $aid,
                                'alternativa' => $alternativa,
                                'correta' => isset($_POST['corretas'][$aid]) ? 1 : 0
                            );
                        }
                    }
                }
            }

            foreach ($quiz as $row) {
                if ($row['tipoquestao'] == 1) {

                    if ($row['quantidade'] == 0) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa!")));
                    }

                    $alternativaCorretas = 0;
                    foreach ($row['perguntas'] as $row_) {
                        if ($row_['correta'] == 1) {
                            $alternativaCorretas++;
                        }
                    }

                    if ($alternativaCorretas == 0) {
                        exit(json_encode(array('retorno' => 0, 'aviso' => "A pergunta \"{$row['pergunta']}\" não tem nenhuma alternativa correta!")));
                    }
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
                $verificaPergunta = $this->db->query("SELECT * FROM atividadesperguntas WHERE id = ? AND pagina = ?", array($row['pid'], $pagina->id))->num_rows();

                if ($verificaPergunta > 0) {

                    $this->db->where('id', $row['pid'])->update('atividadesperguntas', array('pergunta' => $row['pergunta'], 'tipo' => $row['tipoquestao'], 'respostacorreta' => $row['respostacorreta'], 'respostaerrada' => $row['respostaerrada']));

                    if (isset($row['perguntas'])) {
                        foreach ($row['perguntas'] as $row_) {
                            $verificaAlternativa = $this->db->query("SELECT * FROM atividadesalternativas WHERE id = ? AND quiz = ?", array($row_['aid'], $row['pid']))->num_rows();

                            if ($verificaAlternativa > 0) {
                                $this->db->where('id', $row_['aid'])->update('atividadesalternativas', array('alternativa' => $row_['alternativa'], 'correta' => $row_['correta']));
                            } else {
                                $dataQuizAlternativas['quiz'] = $row['pid'];
                                $dataQuizAlternativas['alternativa'] = $row_['alternativa'];
                                $dataQuizAlternativas['correta'] = $row_['correta'];
                                $this->db->query($this->db->insert_string('atividadesalternativas', $dataQuizAlternativas));
                            }
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
        } else if (in_array($data['modulo'], array('aula-digital', 'jogos', 'livros-digitais', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {

            $data['categoriabiblioteca'] = $_POST['categoriabiblioteca'];
            $data['titulobiblioteca'] = $_POST['titulobiblioteca'];
            $data['tagsbiblioteca'] = $_POST['tagsbiblioteca'];
            $data['biblioteca'] = $_POST['biblioteca'];

            if (empty($_POST['biblioteca'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione um item da Biblioteca para continuar')));
            }
        }

        if ($pagina->modulo == "upload" && $data['modulo'] != "upload") {
            if (is_file('./arquivos/pdf/' . $pagina->pdf) && $pagina->pdf != $data['pdf']) {
                @unlink('./arquivos/pdf/' . $pagina->pdf);
            }
        }

        if ($this->db->where('id', $pagina->id)->update('paginas', $data)) {
            if (count(@$data['audio']) > 0) {
                # Apaga arquivos temporários
                $arquivos_temp = $this->db->query("
                SELECT * FROM arquivos_temp
                WHERE usuario = ? AND arquivo = ?", array($this->session->userdata('id'), './arquivos/media/' . $data['audio']));

                foreach ($arquivos_temp->result() as $linha) {
                    $this->db->where('id', $linha->id)->delete('arquivos_temp');
                }

                // Verifica se houve mudança no arquivo
                if ($data['audio'] !== $pagina->audio) {
                    $uploadDirectory = './arquivos/media/' . $pagina->audio;
                    @unlink($uploadDirectory);
                }
            }
            echo json_encode(array('retorno' => 1, 'aviso' => 'Página do curso editada com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/paginascurso/' . $curso->id)));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar página do curso, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function limparArquivosTemp()
    {
        $arquivos_temp = $this->db->query("SELECT * FROM arquivos_temp WHERE usuario = ?", $this->session->userdata('id'));
        foreach ($arquivos_temp->result() as $linha) {
            unlink($linha->arquivo);
            $this->db->delete('arquivos_temp', array('id' => $linha->id));
        }
    }

    public function excluirpaginacurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $pagina = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($pagina->curso))->row(0);

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                redirect(site_url('home/cursos'));
            }
        }

        //Excluir atividades e páginas
        $this->db->where('id', $pagina->id)->delete('paginas');
        $this->db->where('pagina', $pagina->id)->delete('atividadesperguntas');
        $this->db->where('pagina', $pagina->id)->delete('quizperguntas');
        $this->db->query("DELETE FROM atividadesalternativas
                          WHERE quiz NOT IN (SELECT id FROM atividadesperguntas)");
        $this->db->query("DELETE FROM quizalternativas
                          WHERE quiz NOT IN (SELECT id FROM quizperguntas)");

        redirect(site_url('home/paginascurso/' . $curso->id));
    }

    public function novocurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $this->load->view('novocurso');
    }

    public function novocurso_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        # Variaveis do post
        $data = $this->input->post();
        if (isset($data['peoplenet_token'])) {
            unset($data['peoplenet_token']);
        }
        if (isset($data['submit'])) {
            unset($data['submit']);
        }

        $data['usuario'] = $this->session->userdata('id');
        $data['tipo'] = $this->session->userdata('tipo');

        $data['publico'] = 0;
        $data['gratuito'] = 0;

        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        if (isset($_FILES) && !empty($_FILES)) {
            if ($_FILES['foto_consultor']['error'] == 0) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['file_name'] = utf8_decode($_FILES['foto_consultor']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_consultor')) {
                    $foto = $this->upload->data();
                    $data['foto_consultor'] = utf8_encode($foto['file_name']);
                }
                /*
                  else {
                  exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                  } */
            }

            if ($_FILES['foto_treinamento']['error'] == 0) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['file_name'] = utf8_decode($_FILES['foto_treinamento']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_treinamento')) {
                    $foto = $this->upload->data();
                    $data['foto_treinamento'] = utf8_encode($foto['file_name']);
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
            $name = str_replace('_', ' ', $name);
            $name = ucfirst($name);
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "' . $name . '" não pode ficar em branco')));
        }
        if ($this->db->query($this->db->insert_string('cursos', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de curso efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/cursos')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de curso, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editarcurso()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        $usuarios = $this->db->query("SELECT * FROM usuarios WHERE tipo IN ('administrador', 'empresa') ORDER BY tipo ASC");

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                redirect(site_url('home/cursos'));
            }
        }

        $data['row'] = $curso;
        $data['usuarios'] = $usuarios;

        $this->load->view('editarcurso', $data);
    }

    public function editarcurso_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        # Variaveis do post
        $data = $this->input->post();
        if (isset($data['peoplenet_token'])) {
            unset($data['peoplenet_token']);
        }
        if (isset($data['submit'])) {
            unset($data['submit']);
        }
        $data['publico'] = 0;
        $data['gratuito'] = 0;
        $data['id'] = base64_decode($data['id']);

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($data['id']))->row(0);
        $data['foto_consultor'] = $curso->foto_consultor;
        $data['foto_treinamento'] = $curso->foto_treinamento;

        if ($this->session->userdata('tipo') == "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }
        }

        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");


        if (isset($_FILES['foto_consultor'])) {
            if (isset($_FILES['foto_consultor']['name'])) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['file_name'] = utf8_decode($_FILES['foto_consultor']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_consultor')) {
                    $foto = $this->upload->data();
                    $data['foto_consultor'] = utf8_encode($foto['file_name']);
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            }

            if (isset($_FILES['foto_treinamento']['name'])) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['file_name'] = utf8_decode($_FILES['foto_treinamento']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_treinamento')) {
                    $foto = $this->upload->data();
                    $data['foto_treinamento'] = utf8_encode($foto['file_name']);
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

        //Caso for administrador, verifica o usuário
        if ($this->session->userdata('tipo') == 'administrador') {
            # Validação usuario
            if ($data['usuario'] > 0) {
                $usuario = $this->db->query('SELECT tipo FROM usuarios WHERE id = ?', $data['usuario']);

                if ($usuario->num_rows() < 1) {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo usuário não pode ficar em branco')));
                } else {
                    $tipo_usuario = $usuario->row(0);
                    $data['tipo'] = $tipo_usuario->tipo;
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo usuário não pode ficar em branco')));
            }
        }

        if ($this->db->where('id', $data['id'])->update('cursos', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Curso editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/cursos')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar curso, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
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
                                    GROUP BY pdf, audio, video", array($this->uri->rsegment(3)));

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
                pdf = ? OR audio = ? OR video = ? GROUP BY pdf, audio, video", array($this->uri->rsegment(3), $file->pdf, $file->audio, $file->video));

                # Verifica se localizou algum arquivo
                if ($arquivos->num_rows() > 0) {
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

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        # Excluir curso e páginas
        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->usuario != $this->session->userdata('id')) {
                redirect(site_url('home/cursos'));
            }
        }

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

        redirect(site_url('home/cursos'));
    }

    public function solicitarcurso_json()
    {
        header('Content-type: text/json');
        $this->load->helper('phpmailer');

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        $sucesso = 0;
        if ($this->session->userdata('tipo') == "empresa") {

            if ($curso->tipo != "administrador") {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }

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

                if (send_email($nome, $email, $assunto, $mensagem)) {
                    $sucesso = 1;
                }
            }
        } else if ($this->session->userdata('tipo') == "funcionario") {
            $usuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->session->userdata('id')))->row(0);
            $empresa = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($usuario->empresa))->row(0);

            if ($curso->tipo != "administrador" && $curso->usuario != $usuario->empresa) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }

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

            if (send_email($nome, $email, $assunto, $mensagem)) {
                $sucesso = 1;
            }
        }

        if ($sucesso) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Treinamento solicitado com sucesso ao administrador da plataforma; em breve entraremos em contato para a liberação do mesmo'));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao solicitar treinamento, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function estruturas()
    {
        $this->load->view('estruturas');
    }

    public function cargo_funcao()
    {
        $this->load->view('cargo_funcao');
    }

    public function novofuncionario()
    {

        $this->db->select("id, IFNULL(max_colaboradores, 'sem limite') AS max_colaboradores", false);
        $this->db->where('id', $this->session->userdata('id'));
        $empresa = $this->db->get('usuarios')->row();
        $data['qtde_max_colaboradores'] = $empresa->max_colaboradores;

        $this->db->where('empresa', $empresa->id);
        $this->db->where('tipo', 'funcionario');
        $data['qtde_colaboradores'] = $this->db->get('usuarios')->num_rows();


        $this->db->select('nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->group_by('id');
        $usuarios = $this->db->get('usuarios')->result();
        $data['funcionarios'] = array('' => 'digite ou selecione...');
        foreach ($usuarios as $usuario) {
            $data['funcionarios'][$usuario->nome] = $usuario->nome;
        }

        $this->db->select('DISTINCT(depto) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $deptos = $this->db->get('usuarios')->result();
        $data['depto'] = array('' => 'digite ou selecione...');
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('DISTINCT(area) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        $areas = $this->db->get('usuarios')->result();
        $data['area'] = array('' => 'digite ou selecione...');
        foreach ($areas as $area) {
            $data['area'][$area->nome] = $area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $setores = $this->db->get('usuarios')->result();
        $data['setor'] = array('' => 'digite ou selecione...');
        foreach ($setores as $setor) {
            $data['setor'][$setor->nome] = $setor->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'digite ou selecione...');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->db->select('DISTINCT(centro_custo) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(centro_custo) >', 0);
        $centro_custos = $this->db->get('usuarios')->result();
        $data['centro_custo'] = array('' => 'digite ou selecione...');
        foreach ($centro_custos as $centro_custo) {
            $data['centro_custo'][$centro_custo->nome] = $centro_custo->nome;
        }

        $this->db->select('DISTINCT(cargo) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(cargo) >', 0);
        $cargos = $this->db->get('usuarios')->result();
        $data['cargo'] = array('' => 'digite ou selecione...');
        foreach ($cargos as $cargo) {
            $data['cargo'][$cargo->nome] = $cargo->nome;
        }

        $this->db->select('DISTINCT(funcao) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(funcao) >', 0);
        $funcoes = $this->db->get('usuarios')->result();
        $data['funcao'] = array('' => 'digite ou selecione...');
        foreach ($funcoes as $funcao) {
            $data['funcao'][$funcao->nome] = $funcao->nome;
        }

        $this->load->view('novofuncionario', $data);
    }

    public function getfuncionarios()
    {
        $this->load->library('pagination');

        $query = $this->input->post('busca');
        $pdi = $this->input->post('pdi');
        $status = $this->input->post('status');

        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');
        $contrato = $this->input->post('contrato');

        $qryWHERE = ' WHERE empresa = ? AND tipo in (?, ?)';
        $dataWHERE[] = $this->session->userdata('id');
        $dataWHERE[] = "funcionario";
        $dataWHERE[] = "selecionador";

        if (!empty($query)) {
            $qryWHERE .= ' AND (nome LIKE ? OR email LIKE ?)';
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
        }

        if (!empty($pdi)) {
            $qryWHERE .= ' AND (id in (select usuario from pdi where status = ?))';
            $dataWHERE[] = $pdi;
        }

        if ($status !== '') {
            $qryWHERE .= ' AND status = ?';
            $dataWHERE[] = $status;
        }

        if (!empty($depto)) {
            $qryWHERE .= ' AND depto = ?';
            $dataWHERE[] = $depto;
        }

        if (!empty($area)) {
            $qryWHERE .= ' AND area = ?';
            $dataWHERE[] = $area;
        }

        if (!empty($setor)) {
            $qryWHERE .= ' AND setor = ?';
            $dataWHERE[] = $setor;
        }

        if (!empty($cargo)) {
            $qryWHERE .= ' AND cargo = ?';
            $dataWHERE[] = $cargo;
        }

        if (!empty($funcao)) {
            $qryWHERE .= 'AND funcao = ?';
            $dataWHERE[] = $funcao;
        }

        if (!empty($contrato)) {
            $qryWHERE .= ' AND contrato = ?';
            $dataWHERE[] = $contrato;
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

        $config['base_url'] = site_url('home/getfuncionarios');
        $config['total_rows'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 1000;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(3, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM usuarios WHERE empresa = ? AND tipo in (?, ?)", array($this->session->userdata('id'), 'funcionario', 'selecionador'))->num_rows();
        $data['busca'] = "busca={$query}&pdi={$pdi}&status={$status}";
        $data['busca'] .= "&depto={$depto}&area={$area}&setor={$setor}&cargo={$cargo}&funcao={$funcao}&contrato={$contrato}";
        $data['busca'] .= "&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE} ORDER BY nome ASC LIMIT ?, ?", $dataWHERE);
        $this->load->view('getfuncionarios', $data);
    }

    public function getfuncionarios1()
    {
        $this->load->library('pagination');

        $query = $this->input->post('busca');
        $pdi = $this->input->post('pdi');
        $status = $this->input->post('status');

        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');
        $contrato = $this->input->post('contrato');
        $tipo_vinculo = $this->input->post('tipo_vinculo');
        $matricula = $this->input->post('matricula');
        $nivelAcesso = $this->input->post('nivel_acesso');

        $qryWHERE = ' WHERE empresa = ? AND tipo in (?, ?)';
        $dataWHERE[] = $this->session->userdata('id');
        $dataWHERE[] = "funcionario";
        $dataWHERE[] = "selecionador";

        if (!empty($query)) {
            $qryWHERE .= ' AND (nome LIKE ? OR email LIKE ? OR matricula LIKE ? OR municipio LIKE ?)';
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
            $dataWHERE[] = "%{$query}%";
        }

        if (!empty($pdi)) {
            $qryWHERE .= ' AND (id in (select usuario from pdi where status = ?))';
            $dataWHERE[] = $pdi;
        }

        if ($status !== '') {
            $qryWHERE .= ' AND status = ?';
            $dataWHERE[] = $status;
        }

        if (!empty($depto)) {
            $qryWHERE .= ' AND depto = ?';
            $dataWHERE[] = $depto;
        }

        if (!empty($area)) {
            $qryWHERE .= ' AND area = ?';
            $dataWHERE[] = $area;
        }

        if (!empty($setor)) {
            $qryWHERE .= ' AND setor = ?';
            $dataWHERE[] = $setor;
        }

        if (!empty($cargo)) {
            $qryWHERE .= ' AND cargo = ?';
            $dataWHERE[] = $cargo;
        }

        if (!empty($funcao)) {
            $qryWHERE .= 'AND funcao = ?';
            $dataWHERE[] = $funcao;
        }

        if (!empty($contrato)) {
            $qryWHERE .= ' AND contrato = ?';
            $dataWHERE[] = $contrato;
        }

        if (!empty($tipo_vinculo)) {
            $qryWHERE .= ' AND tipo_vinculo = ?';
            $dataWHERE[] = $tipo_vinculo;
        }

        if (!empty($matricula)) {
            $qryWHERE .= ' AND matricula = ?';
            $dataWHERE[] = $matricula;
        }

        if (!empty($nivelAcesso)) {
            $qryWHERE .= ' AND nivel_acesso = ?';
            $dataWHERE[] = $nivelAcesso;
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

        $config['base_url'] = site_url('home/getfuncionarios');
        $config['total_rows'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 1000;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(3, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM usuarios WHERE empresa = ? AND tipo in (?, ?)", array($this->session->userdata('id'), 'funcionario', 'selecionador'))->num_rows();
        $data['busca'] = "busca={$query}&pdi={$pdi}&status={$status}";
        $data['busca'] .= "&depto={$depto}&area={$area}&setor={$setor}&cargo={$cargo}&funcao={$funcao}&contrato={$contrato}&tipo_vinculo={$tipo_vinculo}&matricula={$matricula}&nivel_acesso={$nivelAcesso}";
        $data['busca'] .= "&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM usuarios {$qryWHERE} ORDER BY nome ASC LIMIT ?, ?", $dataWHERE);

        $this->load->view('getfuncionarios1', $data);
    }

    public function getavaliado1()
    {
        $sql = "SELECT id,
                       nome 
                FROM usuarios 
                WHERE id = {$this->uri->rsegment(3)}";
        $avaliado = $this->db->query($sql)->row();

        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }
        $data['titulo'] = 'Avaliação por Período de Experiência';
        $data['id_avaliado'] = '';
        $data['avaliado'] = '';
        if (count($avaliado) > 0) {
            $data['id_avaliado'] = $avaliado->id;
            $data['titulo'] = "Avaliações Colaborador - $avaliado->nome";
        }
        $data['empresa'] = $empresa;
        $data['id_avaliacao'] = '';
        $data['tipo'] = '2';
        $data['data_inicio'] = '';
        $data['data_termino'] = '';
        $modelos = $this->db->get_where('avaliacaoexp_modelos', array('tipo' => 'P'))->result();
        $data['id_modelo'] = array('' => 'selecione...');
        foreach ($modelos as $modelo) {
            $data['id_modelo'][$modelo->id] = $modelo->nome;
        }

        $this->db->select('nome, id');
        $this->db->where('empresa', $empresa);
        $this->db->order_by('nome', 'ASC');
        $avaliadores = $this->db->get('usuarios')->result();

        $data['colaboradores'] = array('' => 'selecione...');
        foreach ($avaliadores as $avaliador) {
            $data['colaboradores'][$avaliador->id] = $avaliador->nome;
        }

        return $data;
    }

    public function getpdi1()
    {
        $row = $this->db->get_where('usuarios', array('id' => $this->uri->rsegment(3)))->row();
        $data['id_usuario'] = $row->id;
        $data['nome_usuario'] = $row->nome;
        $data['funcao_usuario'] = $row->funcao;
        return $data;
    }

    public function funcionarios()
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao', 'contrato');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '')";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }
        $this->load->view('funcionarios', $data);
    }

    public function funcionarios1()
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'matricula', 'cargo', 'funcao', 'contrato', 'matricula', 'nivel_acesso');
        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $this->db->select("DISTINCT(TRIM({$field})) AS {$field}", false);
            $this->db->where('empresa', $empresa);
            $this->db->where("CHAR_LENGTH({$field}) >", 0);
            $this->db->order_by($field, 'asc');
            $rows = $this->db->get('usuarios')->result();

            if (in_array($field, ['area', 'funcao', 'matricula'])) {
                $data[$field] = ['' => 'Todas'] + array_column($rows, $field, $field);
            } else {
                $data[$field] = ['' => 'Todos'] + array_column($rows, $field, $field);
            }
        }

        $nivelAcesso = [
            '' => 'Todos',
            '1' => 'Administradores',
            '7' => 'Presidentes',
            '8' => 'Gerentes',
            '9' => 'Coordenadores',
            '15' => 'Representantes',
            '10' => 'Supervisores',
            '11' => 'Encarregados',
            '12' => 'Líderes',
            '4' => 'Colaboradores CLT',
            '16' => 'Colaboradores MEI',
            '14' => 'Colaboradores PJ',
            '13' => 'Cuidadores Comunitários',
            '3' => 'Gestores',
            '2' => 'Multiplicadores',
            '6' => 'Selecionadores',
            '5' => 'Clientes',
            '17' => 'Vistoriadores'
        ];
        $data['nivel_acesso'] = array_intersect_key($nivelAcesso, $data['nivel_acesso']);

        $this->load->view('funcionarios1', $data);
    }

    public function cursosfuncionario()
    {
        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        $this->load->view('cursosfuncionario', $data);
    }

    public function getcursosfuncionario()
    {
        $this->load->library('pagination');

        $query = $_POST['busca'];

        $qryWHERE = 'WHERE id IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) ';
        $dataWHERE[] = $this->uri->rsegment(3);

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

        $config['base_url'] = site_url('home/getcursosfuncionario/' . $this->uri->rsegment(3));
        $config['total_rows'] = $this->db->query("SELECT * FROM cursos {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;
        $config['uri_segment'] = 4;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(4, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $data['total'] = $this->db->query("SELECT * FROM cursos WHERE id IN (SELECT curso FROM usuarioscursos WHERE usuario = ?)", array($this->uri->rsegment(3)))->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT c.*,
                                            (SELECT COUNT(*) FROM paginas p WHERE p.curso = c.id AND p.modulo = 'atividades') AS total_atividades
                                           FROM cursos c {$qryWHERE} ORDER BY c.id DESC LIMIT ?,?", $dataWHERE);

        # Separa a quantidade de alternativas e acertos
        foreach ($data['query']->result() as $row) {
            $data['atividades'][$row->id] = $this->db->query("SELECT status, pagina
                                                              FROM usuariosatividades
                                                              WHERE curso = ? AND usuario = ?
                                                              ", array($row->id, $this->uri->rsegment(3)))->result();
        }

        $this->load->view('getcursosfuncionario', $data);
    }

    public function novocursofuncionario()
    {
        $data['row'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
        $data['cursos'] = $this->db->query("SELECT * FROM cursos WHERE usuario = ? AND id NOT IN
                                            (SELECT curso FROM usuarioscursos WHERE usuario = ?) ||
                                            id IN (SELECT curso FROM usuarioscursos WHERE usuario = ? AND usuario <> ?)", array($this->session->userdata('empresa'), $this->uri->rsegment(3),
            $this->session->userdata('empresa'), $this->uri->rsegment(3)));

        $this->load->view('novocursofuncionario', $data);
    }

    public function novocursofuncionario_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $data['usuario'] = $this->uri->rsegment(3);
        $data['curso'] = $this->input->post('curso');
        $data['data_inicio'] = $this->input->post('data_inicio');
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        $data['data_maxima'] = $this->input->post('data_maxima');
        if ($data['data_maxima']) {
            $data['data_maxima'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_maxima'])));
        }
        if (($data['data_inicio'] && $data['data_maxima']) && $data['data_inicio'] > $data['data_maxima']) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de início deve ser igual ou menor do que a data de término!')));
        }
        $data['nota_aprovacao'] = $this->input->post('nota_aprovacao');
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $total = 0;
        $permissoes = 0;

        $verificausuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($verificausuario->empresa != $this->session->userdata('id')) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse usuário não é válido!')));
        }

        if (empty($data['curso'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));
        }

        $verifcacurso = $this->db->query("SELECT * FROM cursos WHERE id = ? AND ((tipo = ? AND publico = ?) OR (usuario = ?))", array($data['curso'], "administrador", 0, $this->session->userdata('id')))->num_rows();
        $verificacursoliberado = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ?  AND curso = ?", array($this->session->userdata('id'), $data['curso']));

        if ($verifcacurso == 0 && $verificacursoliberado->num_rows() == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));
        }

        //Verificar quantidade de colaboradores
        foreach ($verificacursoliberado->result() as $row) {
            $permissoes = $row->colaboradores_maximo;
            $total += $this->db->query("SELECT * FROM usuarioscursos WHERE usuario IN (SELECT id FROM usuarios WHERE empresa = ? OR id = ?) AND curso = ?", array($this->session->userdata('id'), $this->session->userdata('id'), $data['curso']))->num_rows();
        }

        $total -= 1;

        if ($permissoes <> 0 && $total >= $permissoes) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Número máximo de colaboradores excede ao limite contratado, para aumentar este número contate o gestor da plataforma via "Fale Conosco" ou envie um email para contato@peoplenetcorp.com.br')));
        }

        $verificacursofuncionario = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($data['usuario'], $data['curso']));

        if ($verificacursofuncionario->num_rows() > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento já está vinculado para esse funcionário!')));
        }

        if ($this->db->query($this->db->insert_string('usuarioscursos', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de treinamento para funcionário efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/cursosfuncionario/' . $data['usuario'])));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de treinamento para funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editarcursofuncionario()
    {
        $data['usuario'] = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
        $data['row'] = $this->db->query("SELECT a.id,
                                                a.curso, 
                                                b.curso AS nome_curso,
                                                DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio, 
                                                DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima,
                                                a.nota_aprovacao
                                         FROM usuarioscursos a 
                                         INNER JOIN cursos b ON b.id = a.curso
                                         WHERE a.usuario = ? AND a.curso = ?", array($this->uri->rsegment(3), $this->uri->rsegment(4)))->row(0);

        $data['cursos'] = $this->db->query("SELECT * FROM cursos WHERE usuario = ? AND id NOT IN
                                            (SELECT curso FROM usuarioscursos WHERE usuario = ?) ||
                                            id IN (SELECT curso FROM usuarioscursos WHERE usuario = ? AND usuario <> ?)", array($this->session->userdata('empresa'), $this->uri->rsegment(3),
            $this->session->userdata('empresa'), $this->uri->rsegment(3)));

        $this->load->view('editarcursofuncionario', $data);
    }

    public function editarcursofuncionario_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $id = $this->input->post('id');
        $data['usuario'] = $this->uri->rsegment(3);
        $data['curso'] = $this->input->post('curso');
        $data['data_inicio'] = $this->input->post('data_inicio');
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        $data['data_maxima'] = $this->input->post('data_maxima');
        if ($data['data_maxima']) {
            $data['data_maxima'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_maxima'])));
        }
        if (($data['data_inicio'] && $data['data_maxima']) && $data['data_inicio'] > $data['data_maxima']) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de início deve ser igual ou menor do que a data de término!')));
        }
        $data['nota_aprovacao'] = $this->input->post('nota_aprovacao');

        $total = 0;
        $permissoes = 0;

        $verificausuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($verificausuario->empresa != $this->session->userdata('id')) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse usuário não é válido!')));
        }

        if (empty($data['curso'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));
        }

        $verifcacurso = $this->db->query("SELECT * FROM cursos WHERE id = ? AND ((tipo = ? AND publico = ?) OR (usuario = ?))", array($data['curso'], "administrador", 0, $this->session->userdata('id')))->num_rows();
        $verificacursoliberado = $this->db->query("SELECT * FROM usuarioscursos WHERE id = ?", array($id));

        if ($verifcacurso == 0 && $verificacursoliberado->num_rows() == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));
        }

        //Verificar quantidade de colaboradores
        foreach ($verificacursoliberado->result() as $row) {
            $permissoes += $row->colaboradores_maximo;
            $total += $this->db->query("SELECT * FROM usuarioscursos WHERE usuario IN (SELECT id FROM usuarios WHERE empresa = ? OR id = ?) AND curso = ?", array($this->session->userdata('id'), $this->session->userdata('id'), $data['curso']))->num_rows();
        }

        $total -= 1;

        if ($permissoes <> 0 && $total >= $permissoes) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Número máximo de colaboradores excede ao limite contratado, para aumentar este número contate o gestor da plataforma via "Fale Conosco" ou envie um email para contato@peoplenetcorp.com.br')));
        }

        $verificacursofuncionario = $this->db->query("SELECT curso FROM usuarioscursos WHERE id = ?", array($id))->row(0);

        if ($verificacursofuncionario->curso != $data['curso']) {
            $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        }

        if ($this->db->query($this->db->update_string('usuarioscursos', $data, array('id' => $id)))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Edição de treinamento para funcionário efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/cursosfuncionario/' . $data['usuario'])));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar edição de treinamento para funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function excluircursosfuncionario()
    {
        $veririficafuncionario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($veririficafuncionario->empresa != $this->session->userdata('id')) {
            redirect(site_url('home/funcionarios'));
        }

        $this->db->query("DELETE FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($this->uri->rsegment(3), $this->uri->rsegment(4)));

        redirect(site_url('home/cursosfuncionario/' . $this->uri->rsegment(3)));
    }

    public function novofuncionario_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $this->db->where('empresa', $this->session->userdata('id'));
        $this->db->where('tipo', 'funcionario');
        $qtde_colaboradores = $this->db->get('usuarios')->num_rows();

        $this->db->select('max_colaboradores');
        $this->db->where('id', $this->session->userdata('id'));
        $max_colaboradores = $this->db->get('usuarios')->row()->max_colaboradores ?? null;

        if (!empty($max_colaboradores) and $qtde_colaboradores >= $max_colaboradores) {
            exit(json_encode(array('retorno' => 0, 'aviso' => "O limite máximo de colaboradores é de <strong>{$max_colaboradores}</strong>.<br>Para aumentar esse número, você deve solicitar ao administrador da plataforma.")));
        }

        $data['empresa'] = $this->session->userdata('id');
        $data['tipo'] = "funcionario";
        $data['nome'] = $_POST['funcionario'];
        $data['foto'] = "avatar.jpg";
        $data['telefone'] = $_POST['telefone'];
        $data['email'] = $_POST['email'];
        $data['senha'] = $_POST['senha'];
        $data['depto'] = $_POST['depto'];
        $data['area'] = $_POST['area'];
        $data['setor'] = $_POST['setor'];
        $data['contrato'] = $_POST['contrato'];
        $data['centro_custo'] = $_POST['centro_custo'];
        $data['cargo'] = $_POST['cargo'];
        $data['funcao'] = $_POST['funcao'];
        $data['nome_cartao'] = $this->input->post('nome_cartao');
        $data['valor_vt'] = $this->input->post('valor_vt');
        $data['nivel_acesso'] = $_POST['nivel_acesso'];
        $data['tipo_demissao'] = $_POST['tipo_demissao'];
        $data['tipo'] = $data['nivel_acesso'] === '6' ? 'selecionador' : 'funcionario';
        $data['status'] = $_POST['status'];
        $data['token'] = uniqid();
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['data_admissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_admissao'])));
        if ($_POST['data_demissao']) {
            $data['data_demissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_demissao'])));
        } else {
            $data['data_demissao'] = null;
        }
        if (empty($_POST['tipo_demissao'])) {
            $data['tipo_demissao'] = null;
        }

        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Funcionário" não pode ficar em branco')));
        }

        if (empty($data['email'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "E-mail" não pode ficar em branco')));
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));
        }

        $verificaemail = $this->db->query("SELECT * FROM usuarios WHERE email = ?", array($data['email']));
        if ($verificaemail->num_rows() > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse endereço de e-mail já está em uso')));
        }

        if (empty($data['senha'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));
        }

        if ($data['senha'] != $_POST['confirmarsenha']) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));
        }

        $data['senha'] = $this->usuarios->setPassword($data['senha']);

        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                $data['foto'] = utf8_encode($foto['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }
        /* Foto da descrição da empresa */
        if (!empty($_FILES['logo_descricao'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo_descricao']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo_descricao')) {
                $foto_descricao = $this->upload->data();
                $data['foto_descricao'] = utf8_encode($foto_descricao['file_name']);
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if ($this->db->query($this->db->insert_string('usuarios', $data))) {
            $hash_acesso = $this->input->post('hash_acesso');
            if ($hash_acesso) {
                $id = $this->db->insert_id();
//                $this->load->library('encrypt');
//                $data['hash_acesso'] = $this->encrypt->encode(json_encode($hash_acesso), base64_encode($id));
                $data['hash_acesso'] = json_encode($hash_acesso);
                $this->db->update('usuarios', array('hash_acesso' => $data['hash_acesso']), array('id' => $id));
            }
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de funcionário efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/funcionarios')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editarfuncionario()
    {
        $this->db->where('id', $this->uri->rsegment(3, 0));
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        $funcionario = $this->db->get('usuarios')->row();

        if (count($funcionario) == 0) {
            redirect(site_url('home/funcinarios'));
        }

        if ($funcionario->empresa != $this->session->userdata('id')) {
            redirect(site_url('home/funcinarios'));
        }
        if ($funcionario->hash_acesso) {
//            $this->load->library('encrypt');
//            $funcionario->hash_acesso = $this->encrypt->decode($funcionario->hash_acesso, base64_encode($funcionario->id));
//            $funcionario->hash_acesso = json_decode($funcionario->hash_acesso, true);
        } else {
            $funcionario->hash_acesso = 'null';
        }

        $dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_admissao)));
        $dataFormatada2 = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->datacadastro)));
        $funcionario->data_admissao = $dataFormatada;
        $funcionario->datacadastro = $dataFormatada2;
        $data['row'] = $funcionario;

        $this->db->select('nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->group_by('id');
        $usuarios = $this->db->get('usuarios')->result();
        $data['funcionarios'] = array('' => 'digite ou selecione...');
        foreach ($usuarios as $usuario) {
            $data['funcionarios'][$usuario->nome] = $usuario->nome;
        }

        $data['nivel_acesso'] = array(
            '1' => 'Administrador',
            '7' => 'Presidente',
            '8' => 'Gerente',
            '9' => 'Coordenador',
            '10' => 'Supervisor',
            '11' => 'Encarregado',
            '12' => 'Líder',
            '4' => 'Colaborador',
            '13' => 'Cuidador Comunitário',
            '3' => 'Gestor',
            '2' => 'Multiplicador',
            '6' => 'Selecionador',
            '5' => 'Cliente'
        );
        $data['status'] = array(
            '1' => 'Ativo',
            '2' => 'Inativo',
            '3' => 'Em experiência',
            '4' => 'Em desligamento',
            '5' => 'Desligado',
            '6' => 'Afastado (maternidade)',
            '7' => 'Afastado (aposentadoria)',
            '8' => 'Afastado (doença)',
            '9' => 'Afastado (acidente)'
        );

        $this->db->select('DISTINCT(depto) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $deptos = $this->db->get('usuarios')->result();
        $data['depto'] = array('' => 'digite ou selecione...');
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('DISTINCT(area) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        $areas = $this->db->get('usuarios')->result();
        $data['area'] = array('' => 'digite ou selecione...');
        foreach ($areas as $area) {
            $data['area'][$area->nome] = $area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('area', $funcionario->area);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $setores = $this->db->get('usuarios')->result();
        $data['setor'] = array('' => 'digite ou selecione...');
        foreach ($setores as $setor) {
            $data['setor'][$setor->nome] = $setor->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'digite ou selecione...');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->db->select('DISTINCT(centro_custo) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(centro_custo) >', 0);
        $centro_custos = $this->db->get('usuarios')->result();
        $data['centro_custo'] = array('' => 'digite ou selecione...');
        foreach ($centro_custos as $centro_custo) {
            $data['centro_custo'][$centro_custo->nome] = $centro_custo->nome;
        }

        $this->db->select('DISTINCT(cargo) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(cargo) >', 0);
        $cargos = $this->db->get('usuarios')->result();
        $data['cargo'] = array('' => 'digite ou selecione...');
        foreach ($cargos as $cargo) {
            $data['cargo'][$cargo->nome] = $cargo->nome;
        }

        $this->db->select('DISTINCT(funcao) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('cargo', $funcionario->cargo);
        $this->db->where('CHAR_LENGTH(funcao) >', 0);
        $funcoes = $this->db->get('usuarios')->result();
        $data['funcao'] = array('' => 'digite ou selecione...');
        foreach ($funcoes as $funcao) {
            $data['funcao'][$funcao->nome] = $funcao->nome;
        }

        $this->load->view('editarfuncionario', $data);
    }

    public function editarfuncionario1()
    {
        $this->db->where('id', $this->uri->rsegment(3, 0));
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        $funcionario = $this->db->get('usuarios')->row();

        if (count($funcionario) == 0) {
            redirect(site_url('home/funcinarios'));
        }

        if ($funcionario->empresa != $this->session->userdata('id')) {
            redirect(site_url('home/funcinarios'));
        }
        if ($funcionario->hash_acesso) {
//            $this->load->library('encrypt');
//            $funcionario->hash_acesso = $this->encrypt->decode($funcionario->hash_acesso, base64_encode($funcionario->id));
//            $funcionario->hash_acesso = json_decode($funcionario->hash_acesso, true);
        } else {
            $funcionario->hash_acesso = 'null';
        }

        $dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_admissao)));
        $dataFormatada2 = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->datacadastro)));
        $funcionario->data_admissao = $dataFormatada;
        $funcionario->datacadastro = $dataFormatada2;
        $data['row'] = $funcionario;

        $this->db->select('nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->group_by('id');
        $usuarios = $this->db->get('usuarios')->result();
        $data['funcionarios'] = array('' => 'digite ou selecione...');
        foreach ($usuarios as $usuario) {
            $data['funcionarios'][$usuario->nome] = $usuario->nome;
        }

        $data['nivel_acesso'] = array(
            '1' => 'Administrador',
            '7' => 'Presidente',
            '8' => 'Gerente',
            '9' => 'Coordenador',
            '10' => 'Supervisor',
            '11' => 'Encarregado',
            '12' => 'Líder',
            '4' => 'Colaborador',
            '13' => 'Cuidador Comunitário',
            '3' => 'Gestor',
            '2' => 'Multiplicador',
            '6' => 'Selecionador',
            '5' => 'Cliente'
        );
        $data['status'] = array(
            '1' => 'Ativo',
            '2' => 'Inativo',
            '3' => 'Em experiência',
            '4' => 'Em desligamento',
            '5' => 'Desligado',
            '6' => 'Afastado (maternidade)',
            '7' => 'Afastado (aposentadoria)',
            '8' => 'Afastado (doença)',
            '9' => 'Afastado (acidente)'
        );
        $data['tipo_demissao'] = array(
            '1' => 'Demissão sem justa causa',
            '2' => 'Demissão por justa causa',
            '3' => 'Pedido de demissão',
            '4' => 'Término do contrato',
            '5' => 'Rescisão antecipada pelo empregado',
            '6' => 'Rescisão antecipada pelo empregador'
        );

        $this->db->select('DISTINCT(depto) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $deptos = $this->db->get('usuarios')->result();
        $data['depto'] = array('' => 'digite ou selecione...');
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('DISTINCT(area) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        $areas = $this->db->get('usuarios')->result();
        $data['area'] = array('' => 'digite ou selecione...');
        foreach ($areas as $area) {
            $data['area'][$area->nome] = $area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('area', $funcionario->area);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $setores = $this->db->get('usuarios')->result();
        $data['setor'] = array('' => 'digite ou selecione...');
        foreach ($setores as $setor) {
            $data['setor'][$setor->nome] = $setor->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'digite ou selecione...');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->db->select('DISTINCT(centro_custo) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(centro_custo) >', 0);
        $centro_custos = $this->db->get('usuarios')->result();
        $data['centro_custo'] = array('' => 'digite ou selecione...');
        foreach ($centro_custos as $centro_custo) {
            $data['centro_custo'][$centro_custo->nome] = $centro_custo->nome;
        }

        $this->db->select('DISTINCT(cargo) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(cargo) >', 0);
        $cargos = $this->db->get('usuarios')->result();
        $data['cargo'] = array('' => 'digite ou selecione...');
        foreach ($cargos as $cargo) {
            $data['cargo'][$cargo->nome] = $cargo->nome;
        }

        $this->db->select('DISTINCT(funcao) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('cargo', $funcionario->cargo);
        $this->db->where('CHAR_LENGTH(funcao) >', 0);
        $funcoes = $this->db->get('usuarios')->result();
        $data['funcao'] = array('' => 'digite ou selecione...');
        foreach ($funcoes as $funcao) {
            $data['funcao'][$funcao->nome] = $funcao->nome;
        }

        $data['data_avaliado1'] = $this->getavaliado1();
        $data['data_pdi1'] = $this->getpdi1();

        $this->load->view('editarfuncionario1', $data);
    }

    public function editarfuncionario_json()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $this->db->where('id', $this->uri->rsegment(3, 0));
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        $funcionario = $this->db->get('usuarios')->row();

        if ($funcionario->empresa != $this->session->userdata('id')) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
        }
        if (isset($funcionario->foto)) {
            $funcionario->foto = utf8_decode($funcionario->foto);
        }

        $data['nome'] = $_POST['funcionario'];
        $data['depto'] = $_POST['depto'];
        $data['area'] = $_POST['area'];
        $data['setor'] = $_POST['setor'];
        $data['contrato'] = $_POST['contrato'];
        $data['centro_custo'] = $_POST['centro_custo'];
        $data['cargo'] = $_POST['cargo'];
        $data['funcao'] = $_POST['funcao'];
        $data['telefone'] = $_POST['telefone'];
        $data['email'] = $_POST['email'];
        $data['nome_cartao'] = $this->input->post('nome_cartao');
        $data['valor_vt'] = $this->input->post('valor_vt');
        $data['datacadastro'] = $_POST['datacadastro'];
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['data_admissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_admissao'])));
        if ($_POST['data_demissao']) {
            $data['data_demissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_demissao'])));
        } else {
            $data['data_demissao'] = null;
        }
        if (empty($_POST['tipo_demissao'])) {
            $data['tipo_demissao'] = null;
        }
        $data['nivel_acesso'] = $_POST['nivel_acesso'];
        $data['tipo'] = $data['nivel_acesso'] === '6' ? 'selecionador' : 'funcionario';
        $data['status'] = $_POST['status'];

        $hash_acesso = $this->input->post('hash_acesso');
        if ($hash_acesso) {
//            $this->load->library('encrypt');
//            $data['hash_acesso'] = $this->encrypt->encode(json_encode($hash_acesso), base64_encode($funcionario->id));
            $data['hash_acesso'] = json_encode($hash_acesso);
        } else {
            $data['hash_acesso'] = null;
        }

        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Funcionário" não pode ficar em branco')));
        }

        if ($_POST['senha'] != '') {
            if (empty($_POST['senha'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));
            }

            if ($_POST['senha'] != $_POST['confirmarsenha']) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));
            }

            $data['senha'] = $this->usuarios->setPassword($_POST['senha']);
        }

        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                //print_r(imagesx($_FILES["logo"]["tmp_name"]));exit;
                $data['foto'] = utf8_encode($foto['file_name']);


                $image = new Imagick($foto['full_path']); // default 72 dpi image
                $image->setImageResolution(150, 150);
                $image->writeImage($data['foto']);

                /*$target_file = $foto['full_path'];
                $imageFileType = $foto['image_type'];
                $imageWidth = $foto['image_width'];
                $imageHeight = $foto['image_height'];
                if($imageFileType === 'png'){
                                            $img = imagecreatefrompng($target_file);
                                        } else {
                                            $img = imagecreatefromjpeg($target_file);
                                        }
                                        # Converte para paleta true color
                                        //if(!imageistruecolor($img)){
                                            imagepalettetotruecolor($img);
                                        //}
                                            //print_r($img);exit;
                                        # Configura resolucao (DPI)
                                        //imageresolution($img, 150);
                                        # Redimensiona a imagem
                                        if ($imageWidth > 400 or $imageHeight > 480) {
                                            imagescale($img, ($imageWidth > $imageHeight ? 400 : 480), -1);
                                        }
                                        # Salva a imagem com 80% de qualidade
                                        if($imageFileType === 'png'){
                                            imagepng($img, $target_file, 80);
                                        } else {
                                            imagejpeg($img, $target_file, 80);
                                        }
                                        # Libera espaco na memoria
                                        imagedestroy($img);
*/


                if ($funcionario->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $funcionario->foto) && $funcionario->foto != $foto['file_name']) {
                    @unlink('./imagens/usuarios/' . $funcionario->foto);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }


        /* Foto da descrição da empresa */
        if (!empty($_FILES['logo_descricao'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo_descricao']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo_descricao')) {
                $foto_descricao = $this->upload->data();
                $data['foto_descricao'] = utf8_encode($foto_descricao['file_name']);
                if ($funcionario->foto_descricao != "avatar.jpg" && file_exists('./imagens/usuarios/' . $funcionario->foto_descricao) && $funcionario->foto_descricao != $data['foto_descricao']) {
                    @unlink('./imagens/usuarios/' . $funcionario->foto_descricao);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if ($this->db->where('id', $funcionario->id)->update('usuarios', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Funcionário editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/funcionarios')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function excluirfuncionario()
    {
        $funcionario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($funcionario->empresa != $this->session->userdata('id')) {
            redirect(site_url('home/funcionarios1'));
        }

        if ($funcionario->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $funcionario->foto)) {
            @unlink('./imagens/usuarios/' . $funcionario->foto);
        }

        $this->db->where('id', $funcionario->id)->delete('usuarios');

        redirect(site_url('home/funcionarios1'));
    }

    public function meuscursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario"))) {
            redirect(site_url('home'));
        }

        $data['categorias'] = $this->db->query("SELECT categoria FROM cursos WHERE CHAR_LENGTH(categoria) > 0 GROUP BY categoria");
        $data['areas_conhecimento'] = $this->db->query("SELECT area_conhecimento FROM cursos WHERE CHAR_LENGTH(area_conhecimento) > 0 GROUP BY area_conhecimento");
        $this->load->view('meuscursos', $data);
    }

    public function getmeuscursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario"))) {
            redirect(site_url('home'));
        }

        $this->load->library('pagination');

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
//        $dataWHERE[] = $this->session->userdata('empresa');
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

        $config['base_url'] = site_url('home/getmeuscursos');
        $config['total_rows'] = $this->db->query("SELECT c.* FROM cursos c {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        array_unshift($dataWHERE, $this->session->userdata('id'));
        $dataWHERE[] = (int)$this->uri->rsegment(3, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $qryWHERE_TOTAL = 'WHERE c.id IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) ';
        $dataWHERE_TOTAL[] = $this->session->userdata('id');

        $data['total'] = $this->db->query("SELECT c.* FROM cursos c {$qryWHERE_TOTAL}", $dataWHERE_TOTAL)->num_rows();
        $data['busca'] = "busca={$busca}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
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
        if (!in_array($this->session->userdata('tipo'), array("funcionario"))) {
            redirect(site_url('home'));
        }

        $data['categorias'] = $this->db->query("SELECT categoria FROM cursos WHERE CHAR_LENGTH(categoria) > 0 GROUP BY categoria");
        $data['areas_conhecimento'] = $this->db->query("SELECT area_conhecimento FROM cursos WHERE CHAR_LENGTH(area_conhecimento) > 0 GROUP BY area_conhecimento");
        $this->load->view('solicitarcursos', $data);
    }

    public function getsolicitarcursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario"))) {
            redirect(site_url('home'));
        }

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

        $config['base_url'] = site_url('home/getsolicitarcursos');
        $config['total_rows'] = $this->db->query("SELECT * FROM cursos {$qryWHERE}", $dataWHERE)->num_rows();
        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int)$this->uri->rsegment(3, 0);
        $dataWHERE[] = (int)$config['per_page'];

        $qryWHERE_TOTAL = 'WHERE status = 1 AND id NOT IN (SELECT curso FROM usuarioscursos WHERE usuario = ?) ';
        $dataWHERE_TOTAL[] = $this->session->userdata('id');

        $data['total'] = $this->db->query("SELECT * FROM cursos {$qryWHERE_TOTAL}", $dataWHERE_TOTAL)->num_rows();
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $data['query'] = $this->db->query("SELECT * FROM cursos {$qryWHERE} ORDER BY id DESC LIMIT ?,?", $dataWHERE);
        $this->load->view('getsolicitarcursos', $data);
    }

    public function acessarcurso()
    {
        if ($this->session->userdata('tipo') === 'funcionario') {
            $verificacurso = $this->db->query("SELECT * FROM usuarioscursos WHERE usuario = ? AND curso = ?", array($this->session->userdata('id'), $this->uri->rsegment(3)))->num_rows();
            if ($verificacurso == 0) {
                redirect(site_url('home/meuscursos'));
            }
        }

        $data['curso'] = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
        $data['paginas'] = $this->db->query("SELECT * FROM paginas WHERE curso = ? ORDER BY ordem ASC", array($this->uri->rsegment(3)));

        $data['ultimapagina'] = $this->db->query("SELECT * FROM paginas WHERE curso = ?", array($this->uri->rsegment(3)))->num_rows() - 1;
        $data['datacadastro'] = date("%Y-%m-%d %H:%i:%s");

        $pagina = $this->uri->rsegment(4, 0);

        if ($this->session->userdata('tipo') === 'funcionario') {
            $sql = "SELECT p.*,
                           (SELECT COUNT(*) 
                            FROM usuariospaginas pg
                            WHERE pg.curso = p.curso AND 
                                  pg.pagina = p.id AND 
                                  pg.usuario = '{$this->session->userdata['id']}') AS total,
                           (SELECT COUNT(*) 
                            FROM usuariospaginas up
                            WHERE up.curso = p.curso AND
                                  up.usuario = '{$this->session->userdata['id']}' AND
                                  up.status = 1) AS andamento,
                           (SELECT COUNT(*) 
                            FROM usuariospaginas up
                            WHERE up.curso = p.curso AND 
                                  up.pagina = p.id AND 
                                  up.usuario = '{$this->session->userdata['id']}' AND 
                                  up.status = 1) AS conclusao
                    FROM paginas p
                    WHERE p.curso = ?
                    ORDER BY p.ordem ASC LIMIT ?,1";
        } else {
            $sql = "SELECT p.*,
                           '0' AS total,
                           '0' AS andamento,
                           '0' AS conclusao
                    FROM paginas p
                    WHERE p.curso = ?
                    ORDER BY p.ordem ASC LIMIT ?,1";
        }

        $data['paginaatual'] = $this->db->query($sql, array($this->uri->rsegment(3), (int)$pagina))->row(0);

        if ($_SERVER['SERVER_PORT'] == 443 && preg_match('/<iframe>|<\/iframe>/i', $data['paginaatual']->conteudo)) {
            $qtdeIframes = substr_count($data['paginaatual']->conteudo, '<iframe>');
            $qtdeHTTPS = substr_count($data['paginaatual']->conteudo, 'https://');
            if ($qtdeIframes != $qtdeHTTPS) {
                header("Location: " . str_replace('https', 'http', current_url()));
            }
        }

        $data['andamento'] = 0;
        if ($pagina > 0 && $data['paginaatual']->ordem > 0) {
            $data['andamento'] = (int)$data['paginaatual']->andamento / (int)($data['paginas']->num_rows() - 1) * 100;
        }

        if ($data['paginaatual']->modulo == 'quiz') {

            $perguntas = $this->db->query("SELECT * FROM quizperguntas WHERE pagina = ? ORDER BY id ASC", array($data['paginaatual']->id))->result();
            foreach ($perguntas as $k => $row) {
                $perguntas[$k]->alternativas = $this->db->query("SELECT * FROM quizalternativas WHERE quiz = ? ORDER BY id ASC", array($row->id))->result();
            }

            $data['perguntas'] = $perguntas;
        } elseif ($data['paginaatual']->modulo == 'atividades') {

            $perguntas = $this->db->query("SELECT * FROM atividadesperguntas WHERE pagina = ? ORDER BY id ASC", array($data['paginaatual']->id))->result();
            foreach ($perguntas as $k => $row) {
                $perguntas[$k]->alternativas = $this->db->query("SELECT * FROM atividadesalternativas WHERE quiz = ? ORDER BY id ASC", array($row->id))->result();
            }

            $data['perguntas'] = $perguntas;
        }

        if ($data['paginaatual']->modulo == 'video-youtube') {
            $data['url_final'] = $data['paginaatual']->youtube;

            switch ($data['paginaatual']->youtube) {
                # Youtube novo
                case strpos($data['paginaatual']->youtube, 'youtube') > 0:
                    $url_video = explode('?v=', $data['paginaatual']->youtube);
                    $data['url_final'] = "https://www.youtube.com/embed/" . $url_video[1] . "?enablejsapi=1";
                    break;
                # Vimeo
                case strpos($data['paginaatual']->youtube, 'vimeo') > 0:
                    $url_video = explode('/', $data['paginaatual']->youtube);
                    $data['url_final'] = "https://player.vimeo.com/video/" . $url_video[3];
                    break;
            }
        }
        if (in_array($data['paginaatual']->modulo, array('mapas', 'simuladores', 'aula-digital', 'jogos', 'livros-digitais', 'infograficos', 'experimentos', 'softwares', 'audios', 'links-externos', 'multimidia'))) {
            $data['biblioteca'] = $this->db->query("SELECT * FROM biblioteca WHERE id = ?", array($data['paginaatual']->biblioteca))->row(0);
        }

        $arrStatus = array();
        if ($this->session->userdata('tipo') === 'funcionario') {
            $this->iniciaCurso($data['paginaatual'], $this->session->userdata['id'], $data['datacadastro']);

            $this->db->where('curso', $data['curso']->id);
            $status = $this->db->get('usuariospaginas')->result();
            foreach ($status as $row) {
                $arrStatus[$row->pagina] = $row;
            }
        }
        foreach ($data['paginas']->result() as $pagina) {
            $pagina->status = isset($arrStatus[$pagina->id]) ? $arrStatus[$pagina->id]->status : 0;
        }

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
                $data_insert['pagina'] = (int)$value;
            } elseif ($key == 'curso' || $key == 'ordem') {
                //Separar id do curso e total de linhas
                $data_insert[$key] = (int)$value;
            } elseif ($key == 'total') {
                //Separar id do curso e total de linhas
                $total = (int)$value;
            } else {
                unset($data_insert[$key]);
            }
        }

        $data_insert['datacadastro'] = date('Y-m-d H:i:s');
        $data_insert['dataconclusao'] = date('Y-m-d H:i:s');
        $data_insert['usuario'] = (int)$usuario;

        //Verifica id do usuário, se a página já está cadastrada e não insere as "capas"
        if ($usuario > 0 && $total == 0 && $data_insert['ordem'] >= 0) {
            //Capa do curso
            if ($data_insert['ordem'] > 0) {
                unset($data_insert['dataconclusao']);
            }
            unset($data_insert['ordem']);

            $this->db->insert('usuariospaginas', $data_insert);
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
                $$campoGet = (int)$valorGet;
            }

            $resultado = $this->db->query("SELECT * FROM cursos WHERE id = '$valor'")->num_rows();

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

                if ($paginas_atv->num_rows() > 0) {
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
                        if ($quizs->num_rows() > 0) {
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
                        if ($quizs->num_rows() > 0) {
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
        $aula = $this->db->query("SELECT * FROM paginas WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
        $data['row'] = $aula;
        $this->load->view('previewaula', $data);
    }

    public function copiar_pagina()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $paginas = $this->db->query("SELECT * FROM paginas WHERE id LIKE '" . $id . "' ");
            if ($paginas->num_rows() > 0) {
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

                if ($paginas_atv->num_rows() > 0) {
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
                        if ($quizs->num_rows() > 0) {
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
                        if ($quizs->num_rows() > 0) {
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

    public function paginaCargosFuncionarios()
    {
        $data['id'] = $this->session->userdata('id');
        $this->load->view("cargosFuncionarios", $data);
    }

    public function paginaCargos()
    {
        $data['id'] = $this->session->userdata('id');
        $this->load->view("cargos", $data);
    }

    public function paginaAvaliacao()
    {
        $data['id'] = $this->session->userdata('id');
        $this->load->view("avaliacao", $data);
    }

    public function paginaAvaliador()
    {
        $data['id'] = $this->session->userdata('id');
        $this->load->view("avaliadoravaliacao", $data);
    }

}
