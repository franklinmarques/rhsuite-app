<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vagas extends CI_Controller
{

	public function index()
    {
        if ($this->session->userdata('logado')) {
            $indexPage = $this->config->item('index_page');
            $uri = !empty($indexPage) ? $indexPage : 'ame';
        } else {
            $uri = $this->uri->segment(1);
        }
        $data['logoempresa'] = '';
        $data['logo'] = '';
        $data['cabecalho'] = '';
        $data['imagem_fundo'] = '';

        if ($uri != 'vagas') {
            $row = $this->db->query("SELECT u.* FROM usuarios u
                                   WHERE u.url = ?", $uri);
            if ($row->num_rows() > 0) {
                $data['logoempresa'] = $row->row()->url;
                $data['logo'] = $row->row()->foto;
                $data['cabecalho'] = $row->row()->cabecalho;
                $data['imagem_fundo'] = $row->row()->imagem_fundo;
            } else {
                show_404();
            }
        }

        $this->load->view('vagas', $data);
    }


    public function listar()
    {
        $this->db->select(["a.codigo, a.data_abertura, c.nome AS cargo_funcao"], false);
        $this->db->select('a.quantidade, a.cidade_vaga, a.id_empresa, b.nome AS cargo, c.nome AS funcao');
        $this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%y') AS data_abertura_de, d.vagas_deficiente"], false);
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id');
        $this->db->join('requisicoes_pessoal d', 'd.id = a.id_requisicao_pessoal');
        $this->db->where('a.status', 1);
        $query = $this->db->get('gestao_vagas a');

        $config = array(
            'search' => ['codigo', 'cidade_vaga', 'cargo', 'funcao']
        );

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = array();
        $logado = (bool)$this->session->userdata('logado');
        foreach ($output->data as $row) {
            if ($logado) {
                $acoes = '<button class="btn btn-sm btn-info" title="Detalhes da vaga" onclick="visualizar_vaga(' . $row->codigo . ')">Detalhes da vaga</button>
                          <button class="btn btn-sm btn-primary" title="Candidatar-se!" onclick="candidatar(' . $row->codigo . ')">Candidatar-se!</button>';
            } else {
                $acoes = '<button class="btn btn-sm btn-info" title="Detalhes da vaga" onclick="visualizar_vaga(' . $row->codigo . ')">Detalhes da vaga</button>
                          <button class="btn btn-sm btn-primary" title="Candidatar-se!" onclick="candidatar(' . $row->codigo . ')">Candidatar-se!</button>';
            }
            $iconeAcessibilidade = $row->vagas_deficiente ? '<i class="fa fa-wheelchair text-primary" style="font-size:18px; font-weight:bold;"></i> ' : '';
            $data[] = [
                $row->codigo,
                $row->data_abertura_de,
                $iconeAcessibilidade . $row->cargo_funcao,
                $row->quantidade,
                $row->cidade_vaga,
                $acoes
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function visualizarDetalhes()
    {
        $codigo = $this->input->get('codigo');

        $this->db->select(["a.codigo, c.nome AS cargo_funcao"], false);
        $this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura"], false);
        $this->db->select('a.perfil_profissional_desejado, a.quantidade, a.cidade_vaga, a.bairro_vaga');
        $this->db->select("(CASE a.tipo_vinculo WHEN 1 THEN 'CLT' WHEN 2 THEN 'PJ/MEI' WHEN 3 THEN 'Autônomo' END) AS tipo_vinculo", false);
        $this->db->select(["FORMAT(a.remuneracao, 2, 'de_DE') AS remuneracao"], false);
        $this->db->select('a.beneficios, a.horario_trabalho, d.nome AS formacao_minima, a.contato_selecionador, a2.observacoes_selecionador');
        $this->db->join('requisicoes_pessoal a2', 'a2.id = a.id_requisicao_pessoal');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id');
        $this->db->join('escolaridade d', 'd.id = a.formacao_minima', 'left');
        $this->db->where('a.codigo', $codigo);
        $data = $this->db->get('gestao_vagas a')->row();

        echo json_encode($data);
    }


    public function verificarCPF()
    {
        $candidato = $this->db
            ->select('email')
            ->where('cpf', $this->input->post('cpf'))
            ->get('recrutamento_usuarios')
            ->row();

        if ($candidato) {
            exit(json_encode(['email' => $candidato->email]));
        }

        echo json_encode(['status' => true]);
    }


    public function solicitarAjuda()
    {
        $cpf = $this->input->post('cpf');
        $emailAntigo = $this->input->post('email');
        if (strlen($cpf . $emailAntigo) === 0) {
            exit(json_encode(['erro' => 'O formulário está vazio']));
        } else {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('cpf', 'CPF', 'required|max_length[14]');
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email|max_length[255]');

            if ($this->form_validation->run() == false) {
                exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
            }
        }

        $candidato = $this->db
            ->select('nome, email')
            ->where('cpf', $cpf)
            ->or_where('email', $emailAntigo)
            ->get('recrutamento_usuarios')
            ->row();

        $selecionadores = $this->db
            ->select('email')
            ->where('tipo', 'funcionario')
            ->where('nivel_acesso', 6)
            ->where('status', 1)
            ->order_by('nome', 'asc')
            ->get('usuarios')
            ->row();

        if ($selecionadores) {
            $this->load->library('email');

            $this->email
                ->from($candidato->email, $candidato->nome)
                ->to(array_column($selecionadores, 'email'))
                ->subject('Recuperação de senha de candidato')
                ->message('Necessito de ajuda para recuperar meu acesso ao portal de vagas<br>Meu CPF: ' . $cpf . '<br>Meu e-mail antigo: ' . $emailAntigo)
                ->send();
        }

        echo json_encode(['status' => true]);
    }


    public function novoCandidato($codigo = '')
    {
        $cabecalho = $this->getCabecalho();

        $fields = $this->db->get('recrutamento_usuarios')->list_fields();
        $data = array_combine($fields, array_pad(array(), count($fields), ''));
        $data['codigo'] = $codigo;
        $data['titulo'] = 'Cadastro de novo candidato';
        $data['url'] = 'recrutamento_candidatos/ajax_addPerfil';
        $data['url_empresa'] = $cabecalho['url_empresa'];
        $data['logo'] = $cabecalho['logo'];
        $data['cabecalho'] = $cabecalho['cabecalho'];
        $data['imagem_fundo'] = $cabecalho['imagem_fundo'];

        $data['estado'] = 35; # SP
        $data['cidade'] = 3550308; # São Paulo
        $data['escolaridade'] = 4; # Ensino médio completo
        $data['deficiencia'] = 0; # Nenhuma

        $data['estados'] = array('' => 'selecione ...');
        $data['cidades'] = array('' => 'selecione ...');
        $data['escolaridades'] = array('' => 'selecione ...');
        $data['deficiencias'] = array('' => 'selecione ...');

        $this->db->order_by('uf', 'asc');
        $estados = $this->db->get('estados')->result();
        foreach ($estados as $estado) {
            $data['estados'][$estado->cod_uf] = $estado->uf;
        }
        $cidades = $this->db->get_where('municipios', array('cod_uf' => $data['estado']))->result();
        foreach ($cidades as $cidade) {
            $data['cidades'][$cidade->cod_mun] = $cidade->municipio;
        }
        $escolaridade = $this->db->get('escolaridade')->result();
        foreach ($escolaridade as $nivel) {
            $data['escolaridades'][$nivel->id] = $nivel->nome;
        }
        $deficiencias = $this->db->get('deficiencias')->result();
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencias'][$deficiencia->id] = $deficiencia->tipo;
        }
        $this->db->select('nome');
        $this->db->where('id_empresa', $data['empresa']);
        $this->db->order_by('nome', 'asc');
        $fontesContratacao = $this->db->get('requisicoes_pessoal_fontes')->result();
        $data['fontesContratacao'] = ['' => 'selecione...'] + array_column($fontesContratacao, 'nome', 'nome');


        $this->load->view('vagas_curriculo', $data);
    }


    private function getCabecalho()
    {
        $codigo = $this->uri->rsegment(3);

        if ($codigo) {
            $this->db->select("url, CONCAT(b.url, '/') AS url_empresa", false);
            $this->db->select('b.foto AS logo, b.cabecalho, b.imagem_fundo');
            $this->db->join('usuarios b', 'b.id = a.id_empresa');
            $this->db->where('a.codigo', $codigo);
            $data = $this->db->get('gestao_vagas a')->row_array();

            if (empty($data)) {
                show_404();
            }
        } else {
            $uri = $this->uri->segment(1);
            if ($uri === 'vagas') {
                $uri = 'ame';
            }

            $data = [
                'url' => '',
                'logo' => '',
                'url_empresa' => '',
                'cabecalho' => '',
                'imagem_fundo' => ''
            ];

            $row = $this->db
                ->select("url, CONCAT(url, '/') AS url_empresa, foto, cabecalho, imagem_fundo", false)
                ->where('url', $uri)
                ->get('usuarios')
                ->row();

            if ($row) {
                $data['url'] = $row->url;
                $data['url_empresa'] = $row->url_empresa;
                $data['logo'] = $row->foto;
                $data['cabecalho'] = $row->cabecalho;
                $data['imagem_fundo'] = $row->imagem_fundo;
            } else {
                show_404();
            }
        }

        return $data;
    }


    public function consultarCEP()
    {
        $cep = $this->input->get('cep');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/{$cep}/json/unicode/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultado = json_decode(curl_exec($ch));
        curl_close($ch);

        $data = array();
        if (!isset($resultado->erro) && isset($resultado->cep)) {
            $this->db->select('cod_uf');
            $estado = $this->db->get_where('estados', array('uf' => $resultado->uf))->row();

            $sql = "SELECT a.cod_mun,
                           a.municipio 
                    FROM municipios a 
                    INNER JOIN estados b ON 
                               b.cod_uf = a.cod_uf 
                    WHERE a.cod_uf = {$estado->cod_uf}";
            $rows = $this->db->query($sql)->result();
            $options = array('' => 'selecione ...');
            foreach ($rows as $row) {
                $options[$row->cod_mun] = $row->municipio;
            }

            $data = array(
                'cep' => $resultado->cep,
                'logradouro' => $resultado->logradouro,
                'complemento' => $resultado->complemento,
                'bairro' => $resultado->bairro,
                'estado' => $estado->cod_uf,
                'numero' => $resultado->unidade,
                'cidade' => form_dropdown('cidade', $options, $resultado->ibge, 'id="cidade" class="form-control filtro"')
            );
        } elseif (isset($resultado->erro)) {
            $data['erro'] = $resultado->erro;
        }

        echo json_encode($data);
    }


    public function validarCandidato()
    {
        $data = $this->input->post();
        $id = $data['id'];

        $campos = [
            'nome' => 'Nome candidato',
            'telefone' => 'Telefone',
            'email' => 'E-mail',
            'senha' => 'Senha',
            'confirmar_senha' => 'Confirmar senha',
        ];
        if ($id and strlen($data['senha']) == 0 and strlen($data['confirmar_senha']) == 0) {
            unset($campos['senha'], $campos['confirmar_senha']);
        }
        foreach ($campos as $campo => $label) {
            if (empty($data[$campo])) {
                exit(json_encode(['erro' => 'O campo "' . $label . '" não pode ficar em branco.']));
            }
        }

        if (isset($data['cpf']) and strlen($data['cpf']) > 0) {
            $verifica_cpf = $this->db
                ->where('id !=', $id)
                ->where('cpf', $data['cpf'])
                ->get('recrutamento_usuarios')
                ->num_rows();
            if ($verifica_cpf > 0) {
                exit(json_encode(['erro' => 'Esse CPF já está em uso.']));
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(['erro' => 'Endereço de e-mail inválido.']));
        }

        $verifica_email = $this->db
            ->where('id !=', $id)
            ->where('email', $data['email'])
            ->get('recrutamento_usuarios')
            ->num_rows();
        if ($verifica_email > 0) {
            exit(json_encode(['erro' => 'Esse endereço de e-mail já está em uso.']));
        }

        if ($data['senha'] != $data['confirmar_senha']) {
            exit(json_encode(['erro' => 'O campo "Senha" não confere com o "Confirmar Senha"']));
        }

        if (!empty($_FILES['foto']['tmp_name']) and !empty($_FILES['foto']['error'])) {
            exit(json_encode(['erro' => 'A foto do candidato é inválida', 'redireciona' => 0, 'pagina' => '']));
        }

        echo json_encode(['status' => true]);
    }


    public function validarFormacaoCandidato()
    {
        echo json_encode(['status' => true]);
    }


    public function salvarCandidato()
    {
        $data = $this->input->post();

        $candidato = [];
        $dataFormacao = [];
        $dataExperiencia = [];

        foreach ($data as $key => $value) {
            if (preg_match('/^_formacao_/', $key) === 1) {
                $dataFormacao[preg_replace('/^_formacao_/', '', $key)] = $value;
            } elseif (preg_match('/^_historico_profissional_/', $key) === 1) {
                $dataExperiencia[preg_replace('/^_historico_profissional_/', '', $key)] = $value;
            } else {
                $candidato[$key] = $value;
            }
        }

        $idCandidato = $candidato['id'];

        if (strlen($candidato['codigo']) > 0) {
            $vaga = $this->db
                ->select('id_empresa')
                ->where('codigo', $candidato['codigo'])
                ->get('gestao_vagas')
                ->row();

            $candidato['empresa'] = $vaga->id_empresa;
        } else {
            $url = substr_replace(current_url(), '', strlen(uri_string()) * (-1) - 1);
            $empresa = $this->db
                ->select('id')
                ->where('url', substr(strrchr($url, '/'), 1))
                ->get('usuarios')
                ->row();

            $candidato['empresa'] = $empresa->id ?? 78;
        }

        $candidato['token'] = uniqid();
        $candidato['data_inscricao'] = date('Y-m-d H:i:s');
        if (strlen($candidato['data_nascimento']) > 0) {
            $candidato['data_nascimento'] = date('Y-m-d', strtotime(str_replace('-', '/', $candidato['data_nascimento'])));
        }

        if (strlen($candidato['cpf']) == 0) {
            $candidato['cpf'] = null;
        }

        unset($candidato['codigo'], $candidato['id'], $candidato['confirmar_senha']);

        $this->load->model('usuarios_model', 'usuarios');
        $candidato['senha'] = $this->usuarios->setPassword($candidato['senha']);
        $candidato['foto'] = "avatar.jpg";

        $this->db->trans_begin();

        if ($idCandidato) {
            $this->db->update('recrutamento_usuarios', $candidato, ['id' => $idCandidato]);
        } else {
            $this->db->insert('recrutamento_usuarios', $candidato);
            $idCandidato = $this->db->insert_id();
        }

        $dataFormacao['id_usuario'] = $idCandidato;
        $dataExperiencia['id_usuario'] = $idCandidato;

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            exit(json_encode(['erro' => 'Erro ao efetuar cadastro de candidato, tente novamente.']));
        }

        //==========================================
        $idUsuario = $dataFormacao['id_usuario'];
        $escolaridade = $dataFormacao['escolaridade'];
        unset($dataFormacao['id_usuario'], $dataFormacao['escolaridade']);

        $this->db->set('escolaridade', $escolaridade);
        $this->db->where('id', $idUsuario);
        $this->db->update('recrutamento_usuarios');

        $this->db->where('id_usuario', $idUsuario);
        $this->db->delete('recrutamento_formacao');

        $arrFormacao = [];
        foreach ($dataFormacao as $column => $values) {
            foreach ($values as $row => $value) {
                $arrFormacao[$row][$column] = $value;
            }
        }

        foreach ($arrFormacao as $formacao) {
            if (strlen($formacao['instituicao']) == 0) {
                continue;
            }

            $formacao['id_usuario'] = $idUsuario;
            $formacao['id_escolaridade'] = $escolaridade;
            if (!(isset($formacao['curso']) and strlen($formacao['curso']) > 0)) {
                $formacao['curso'] = null;
            }
            if (!empty($formacao['tipo']) == false) {
                $formacao['tipo'] = null;
            }
            if (strlen($formacao['ano_conclusao']) == 0) {
                $formacao['ano_conclusao'] = null;
            }
            if (!empty($formacao['id'])) {
                $this->db->update('recrutamento_formacao', $formacao, ['id' => $formacao['id']]);
            } else {
                $this->db->insert('recrutamento_formacao', $formacao);
            }

            if ($this->db->trans_status() == false) {
                $this->db->trans_rollback();
                exit(json_encode(['erro' => 'Erro ao efetuar cadastro de formação de candidato, tente novamente.']));
            }
        }

        //=====================================================
        $idUsuario = $dataExperiencia['id_usuario'];
        unset($dataExperiencia['id_usuario']);

        $this->db->where('id_usuario', $idUsuario);
        $this->db->delete('recrutamento_experiencia_profissional');

        $arrExperiencia = [];
        foreach ($dataExperiencia as $column => $values) {
            foreach ($values as $row => $value) {
                $arrExperiencia[$row][$column] = $value;
            }
        }

        foreach ($arrExperiencia as $experiencia) {
            if (strlen($experiencia['instituicao']) == 0) {
                continue;
            }
            if (empty($experiencia['data_entrada'])) {
                $this->db->trans_rollback();
                exit(json_encode(['retorno' => 0, 'aviso' => 'A data de entrada é obrigatória', 'redireciona' => 0, 'pagina' => '']));
            }
            if (empty($experiencia['cargo_entrada'])) {
                $this->db->trans_rollback();
                exit(json_encode(['retorno' => 0, 'aviso' => 'O cargo de entrada é obrigatório', 'redireciona' => 0, 'pagina' => '']));
            }
            if (empty($experiencia['salario_entrada'])) {
                $this->db->trans_rollback();
                exit(json_encode(['retorno' => 0, 'aviso' => 'O salário de entrada é obrigatório', 'redireciona' => 0, 'pagina' => '']));
            }

            $experiencia['id_usuario'] = $idUsuario;
            $experiencia['data_entrada'] = date('Y-m-d', strtotime(str_replace('/', '-', $experiencia['data_entrada'])));
            if ($experiencia['data_saida']) {
                $experiencia['data_saida'] = date('Y-m-d', strtotime(str_replace('/', '-', $experiencia['data_saida'])));
            } else {
                $experiencia['data_saida'] = null;
            }
            $experiencia['salario_entrada'] = str_replace(['.', ','], ['', '.'], $experiencia['salario_entrada']);
            if ($experiencia['salario_saida']) {
                $experiencia['salario_saida'] = str_replace(['.', ','], ['', '.'], $experiencia['salario_saida']);
            } else {
                $experiencia['salario_saida'] = null;
            }
            if ($experiencia['id']) {
                $this->db->update('recrutamento_experiencia_profissional', $experiencia, ['id' => $experiencia['id']]);
            } else {
                $this->db->insert('recrutamento_experiencia_profissional', $experiencia);
            }
        }

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            exit(json_encode(['erro' => 'Erro ao cadastrar histórico profissional do candidato, tente novamente.']));
        }

        if (!empty($_FILES['foto']['tmp_name'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['foto']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data();
                $this->db->update('recrutamento_usuarios', ['foto' => utf8_encode($foto['file_name'])], ['id' => $idCandidato]);
            } else {
                $this->db->trans_rollback();
                exit(json_encode(['erro' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '']));
            }
        }

        $this->db->trans_commit();

        echo json_encode(['status' => true]);
    }


    public function recuperarSenhaCandidato()
    {
        $email = $this->input->post('email');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email|max_length[255]');

        if ($this->form_validation->run() === false) {
            exit(json_encode(['erro' => $this->form_validation->error_string(' ', ' ')]));
        }

        $candidato = $this->db->get_where('recrutamento_usuarios', ['email' => $email])->row();

        if (empty($candidato)) {
            exit(json_encode(['erro' => 'Não existe nenhum candidato cadastrado com esse endereço de e-mail']));
        }

        $token = uniqid();

        $this->db->trans_begin();

        $this->db->update('recrutamento_usuarios', ['token' => $token], ['id' => $candidato->id]);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            exit(json_encode(['erro' => 'Erro ao editar token, tente novamente, se o erro persistir entre em contato com o administrador']));
        }

        $this->load->library('email');

        $urlAlterarSenha = site_url('vagas/editarSenhaCandidato/' . $token);
        $mensagem = "<p style='text-align: center;'>
                        <h1>LMS</h1>
                    </p>
                    <hr/>
                    <p>Prezado(a) {$candidato->nome},</p>
                    <p>Para alterar sua senha, acesso o endereço abaixo</p>
                    <p><a href='{$urlAlterarSenha}'>{$urlAlterarSenha}</a></p>
                    <p>Caso não tenha solicitado a alteração de senha, ignore este e-mail.</p>";

        $this->email
            ->from('sistema@rhsuite.com.br', 'RhSuite')
            ->to($candidato->email)
            ->subject('LMS - Redefinição de senha')
            ->message($mensagem);

        if ($this->email->send() === false) {
            $this->db->trans_rollback();
            exit(json_encode(['erro' => 'Erro ao enviar e-mail, tente novamente, se o erro persistir entre em contato com o administrador']));
        }

        $this->db->trans_commit();

        echo json_encode(['status' => true]);
    }


    public function editarSenhaCandidato()
    {
        $data = $this->getCabecalho();

        $token = $this->uri->rsegment(3);

        if (empty($token)) {
            redirect(site_url('vagas'));
        }

        $candidato = $this->db
            ->select('nome, token')
            ->where('token', $token)
            ->get('recrutamento_usuarios')
            ->row();

        if (empty($candidato)) {
            redirect(site_url('vagas'));
        }

        $data['token'] = $candidato->token;
        $data['nome'] = $candidato->nome;

        $this->load->view('vagas_alterar_senha', $data);
    }


    public function salvarSenhaCandidato()
    {
        header('Content-type: text/json');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('nova_senha', '<strong>Nova Senha</strong>', 'trim|required|max_length[32]');
        $this->form_validation->set_rules('confirmar_nova_senha', '<strong>Confirmar Nova Senha</strong>', 'trim|required|max_length[32]|matches[senha]');

        if ($this->form_validation->run() === false) {
            exit(json_encode(['retorno' => 0, 'aviso' => $this->form_validation->error_string()]));
        }

        $this->load->helper('date');

        $token = $this->input->post('token');
        $data = $this->input->post();
        $data['token'] = $this->uri->rsegment(3);

        if (strlen($token) == 0) {
            exit(json_encode(['retorno' => 0, 'aviso' => 'A alteração de senha não é permitida sem um token.']));
        }

        $candidato = $this->db->select('id')->where('token', $token)->get('recrutamento_usuarios')->row();

        if (empty($candidato)) {
            exit(json_encode(['retorno' => 0, 'aviso' => 'Não existe nenhum candidato cadastrado com esse token']));
        }

        $this->load->model('usuarios_model', 'usuarios');
        $data = [
            'senha' => $this->usuarios->setPassword($this->input->post('nova_senha')),
            'token' => uniqid(),
            'data_edicao' => date('Y-m-d H:i:s')
        ];

        $this->db->trans_start();
        $this->db->update('recrutamento_usuarios', $data, ['id' => $candidato->id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['retorno' => 0, 'aviso' => 'Erro ao alterar senha, tente novamente, se o erro persistir entre em contato com o administrador']));
        }

        echo json_encode(['retorno' => 1, 'aviso' => 'Senha alterada com sucesso', 'redireciona' => 1, 'pagina' => site_url('vagas')]);
    }

}
