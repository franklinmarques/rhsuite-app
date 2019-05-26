<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Recrutamento_candidatos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->tipo_usuario = array('empresa', 'selecionador');
        if (in_array($this->uri->rsegment(2), array('perfil', 'consultar_cep', 'ajax_cidades', 'ajax_updatePerfil'))) {
            $this->tipo_usuario[] = 'candidato';
        }
        if ($this->tipo_usuario && !in_array($this->session->userdata('tipo'), $this->tipo_usuario)) {
            show_error('Acesso não autorizado ' . $this->session->userdata('tipo') . '-' . implode(',', $this->tipo_usuario), 403, 'Erro 403');
        }
    }

    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $data = $this->ajustarFiltros();

        $data['empresa'] = $empresa;
        $this->load->view('recrutamento_candidatos', $data);
    }

    public function novo()
    {
        $fields = $this->db->get('recrutamento_usuarios')->list_fields();
        $data = array_combine($fields, array_pad(array(), count($fields), ''));
        $data['empresa'] = $this->session->userdata('empresa');
        $data['titulo'] = 'Cadastro de novo candidato';
        $data['url'] = 'recrutamento_candidatos/ajax_addPerfil';

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

        $this->load->view('recrutamento_perfil_novo', $data);
    }

    public function perfil()
    {
        $this->db->where('id', $this->uri->rsegment(3));
        $data = $this->db->get('recrutamento_usuarios')->row_array();
        if ($this->session->userdata('tipo') !== 'candidato') {
            $data['titulo'] = 'Cadastro de ' . $data['nome'];
        } else {
            $data['titulo'] = 'Meu perfil';
        }
        if ($data['data_nascimento']) {
            $data['data_nascimento'] = date('d/m/Y', strtotime(str_replace('-', '/', $data['data_nascimento'])));
        }
        $data['sexos'] = array(
            '' => 'selecione...',
            'M' => 'Masculino',
            'F' => 'Feminino'
        );
        $data['estados_civis'] = array(
            '' => 'selecione...',
            '1' => 'Solteiro(a)',
            '2' => 'Casado(a)',
            '3' => 'Desquitado(a)',
            '4' => 'Divorciado(a)',
            '5' => 'Viúvo(a)',
            '6' => 'Outro'
        );

        $data['url'] = 'recrutamento_candidatos/ajax_updatePerfil';
        unset($data['senha']);

        $data['estados'] = array('' => 'selecione ...');
        $data['cidades'] = array('' => 'selecione ...');
        $data['escolaridades'] = array('' => 'selecione ...');
        $data['deficiencias'] = array('' => 'selecione ...');

        $this->db->order_by('uf', 'asc');
        $estados = $this->db->get('estados')->result();
        foreach ($estados as $estado) {
            $data['estados'][$estado->cod_uf] = $estado->uf;
        }
        $this->db->where('cod_uf', $data['estado']);
        $cidades = $this->db->get('municipios')->result();
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


        $this->db->select('a.*, b.nome AS escolaridade', false);
        $this->db->join('escolaridade b', 'b.id = a.id_escolaridade');
        $this->db->where('a.id_usuario', $this->uri->rsegment(3));
        $this->db->order_by('a.id_escolaridade', 'asc');
        $this->db->order_by('a.ano_conclusao', 'asc');
        $formacoes = $this->db->get('recrutamento_formacao a')->result();

        $formacaoCampos = (object)array_fill_keys($this->db->get('recrutamento_formacao')->list_fields(), null);
        $data['formacao'] = array();
        for ($i = 0; $i <= 12; $i++) {
            $data['formacao'][$i] = $formacaoCampos;
        }

        $ensinoFundamental = array();
        $ensinoMedio = array();
        $graduacao = array();
        $posGraduacao = array();
        $mestrado = array();
        foreach ($formacoes as $formacao) {
            switch ($formacao->id_escolaridade) {
                case 1:
                case 2:
                    $ensinoFundamental[] = $formacao;
                    break;
                case 3:
                case 4:
                    $ensinoMedio[] = $formacao;
                    break;
                case 5:
                case 6:
                    $graduacao[] = $formacao;
                    break;
                case 7:
                case 8:
                    $posGraduacao[] = $formacao;
                    break;
                default:
                    $mestrado[] = $formacao;
            }
        }
        array_splice($data['formacao'], 0, count($ensinoFundamental), $ensinoFundamental);
        array_splice($data['formacao'], 1, count($ensinoMedio), $ensinoMedio);
        array_splice($data['formacao'], 4, count($graduacao), $graduacao);
        array_splice($data['formacao'], 7, count($posGraduacao), $posGraduacao);
        array_splice($data['formacao'], 10, count($mestrado), $mestrado);


        $this->db->select('id, instituicao, cargo_entrada, cargo_saida, motivo_saida, realizacoes');
        $this->db->select("DATE_FORMAT(data_entrada, '%d/%m/%Y') AS data_entrada", false);
        $this->db->select("DATE_FORMAT(data_saida, '%d/%m/%Y') AS data_saida", false);
        $this->db->select("FORMAT(salario_entrada, 2, 'de_DE') AS salario_entrada", false);
        $this->db->select("FORMAT(salario_saida, 2, 'de_DE') AS salario_saida", false);
        $this->db->where('id_usuario', $this->uri->rsegment(3));
        $this->db->order_by('data_entrada', 'desc');
        $this->db->order_by('data_saida', 'desc');
        $this->db->limit(7);
        $expProfissional = $this->db->get('recrutamento_experiencia_profissional')->result();
        $expProfissionalCampos = (object)array_fill_keys($this->db->get('recrutamento_experiencia_profissional')->list_fields(), null);
        $data['historicoProfissional'] = array();
        for ($i = 0; $i <= 6; $i++) {
            $data['historicoProfissional'][$i] = $expProfissionalCampos;
        }
        array_splice($data['historicoProfissional'], 0, count($expProfissional), $expProfissional);

        $this->load->view('recrutamento_perfil', $data);
    }

    public function ajustarFiltros($campos = array())
    {
        $empresa = $this->session->userdata('empresa');
        $data = array(
            'estado' => array('' => 'Todos'),
            'cidade' => array('' => 'Todas'),
            'bairro' => array('' => 'Todos'),
            'deficiencia' => array('' => 'Sem filtro'),
            'escolaridade' => array('' => 'Todas')
        );


        $this->db->select('a.cod_uf, a.uf');
        $this->db->join('recrutamento_usuarios b', 'b.estado = a.cod_uf');
        $this->db->where('b.empresa', $empresa);
        $estados = $this->db->get('estados a')->result();
        foreach ($estados as $estado) {
            $data['estado'][$estado->cod_uf] = $estado->uf;
        }


        $this->db->select('a.cod_mun, a.municipio');
        $this->db->join('recrutamento_usuarios b', 'b.cidade = a.cod_mun');
        $this->db->where('b.empresa', $empresa);
        if (!empty($campos['estado'])) {
            $this->db->where('b.estado', $campos['estado']);
        }
        $cidades = $this->db->get('municipios a')->result();
        foreach ($cidades as $cidade) {
            $data['cidade'][$cidade->cod_mun] = $cidade->municipio;
        }


        $this->db->distinct('bairro');
        $this->db->where('empresa', $empresa);
        $this->db->where('CHAR_LENGTH(bairro) >', 0);
        if (!empty($campos['estado'])) {
            $this->db->where('estado', $campos['estado']);
        }
        if (!empty($campos['cidade'])) {
            $this->db->where('cidade', $campos['cidade']);
        }
        $bairros = $this->db->get('recrutamento_usuarios')->result();
        foreach ($bairros as $bairro) {
            $data['bairro'][$bairro->bairro] = $bairro->bairro;
        }


        $this->db->select('a.id, a.tipo');
        $this->db->join('recrutamento_usuarios b', 'b.deficiencia = a.id');
        $this->db->where('b.empresa', $empresa);
        if (!empty($campos['estado'])) {
            $this->db->where('estado', $campos['estado']);
        }
        if (!empty($campos['cidade'])) {
            $this->db->where('cidade', $campos['cidade']);
        }
        if (!empty($campos['bairro'])) {
            $this->db->where('bairro', $campos['bairro']);
        }
        $deficiencias = $this->db->get('deficiencias a')->result();
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencia'][$deficiencia->id] = $deficiencia->tipo;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('recrutamento_usuarios b', 'b.escolaridade = a.id');
        $this->db->where('b.empresa', $empresa);
        if (!empty($campos['estado'])) {
            $this->db->where('estado', $campos['estado']);
        }
        if (!empty($campos['cidade'])) {
            $this->db->where('cidade', $campos['cidade']);
        }
        if (!empty($campos['bairro'])) {
            $this->db->where('bairro', $campos['bairro']);
        }
        $escolaridade = $this->db->get('escolaridade a')->result();
        foreach ($escolaridade as $nivel) {
            $data['escolaridade'][$nivel->id] = $nivel->nome;
        }


        return $data;
    }

    public function ajax_list($id)
    {
        if (empty($id)) {
            $id = $this->session->userdata('empresa');
        }
        $post = $this->input->post();


        $this->db->select('a.nome, a.cidade AS cod_municipal, a.bairro, a.id');
        $this->db->select('a.estado, d.municipio AS cidade, a.deficiencia, a.escolaridade, a.email');
        $this->db->join('recrutamento_candidatos b', 'b.id_usuario = a.id', 'left');
        $this->db->join('recrutamento_testes c', 'c.id_candidato = b.id', 'left');
        $this->db->join('municipios d', 'd.cod_mun = a.cidade', 'left');
        $this->db->where('a.empresa', $id);
        if ($post['estado']) {
            $this->db->where('a.estado', $post['estado']);
        }
        if ($post['cidade']) {
            $this->db->where('a.cidade', $post['cidade']);
        }
        if ($post['bairro']) {
            $this->db->where('a.bairro', $post['bairro']);
        }
        if ($post['deficiencia']) {
            $this->db->where('a.deficiencia', $post['deficiencia']);
        }
        if ($post['escolaridade']) {
            $this->db->where('a.escolaridade', $post['escolaridade']);
        }
        $this->db->group_by('a.id');


        $query = $this->db->get('recrutamento_usuarios a');

        $config = array(
            'search' => ['nome', 'email', 'cidade', 'bairro']
        );

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);


        $filtros = $this->ajustarFiltros($post);
        $campos['estado'] = form_dropdown('estado', $filtros['estado'], $post['estado'], 'id="estado" class="form-control filtro input-sm"');
        $campos['cidade'] = form_dropdown('cidade', $filtros['cidade'], $post['cidade'], 'id="cidade" class="form-control filtro input-sm"');
        $campos['bairro'] = form_dropdown('bairro', $filtros['bairro'], $post['bairro'], 'id="bairro" class="form-control filtro input-sm"');
        $campos['deficiencia'] = form_dropdown('deficiencia', $filtros['deficiencia'], $post['deficiencia'], 'id="deficiencia" class="form-control filtro input-sm"');
        $campos['escolaridade'] = form_dropdown('escolaridade', $filtros['escolaridade'], $post['escolaridade'], 'id="escolaridade" class="form-control filtro input-sm"');

        $output->filtros = $campos;


        $data = array();
        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $row->cidade,
                $row->bairro,
                '<a class="btn btn-sm btn-primary" href="' . site_url('recrutamento_candidatos/perfil/' . $row->id) . '" title="Editar cadastro sumário"><i class="glyphicon glyphicon-pencil"></i></a>
                 <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_candidato(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                 <a class="btn btn-sm btn-primary" href="' . site_url('recrutamento/candidatos/' . $row->id) . '" title="Ver processo"><i class="glyphicon glyphicon-list-alt"></i> Avaliações</a>'
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('recrutamento', array('id' => $id))->row();

        $data->data_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_inicio)));
        $data->data_termino = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_termino)));

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        $data['id_usuario_EMPRESA'] = $this->session->userdata('empresa');
        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $status = $this->db->insert('recrutamento', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_addPerfil()
    {
        $data = $this->input->post();

        $campos = array(
            'nome' => 'Nome candidato',
            'telefone' => 'Telefone',
            'email' => 'E-mail',
            'senha' => 'Senha',
            'confirmarsenha' => 'Confirmar senha',
        );
        foreach ($campos as $campo => $label) {
            if (empty($data[$campo])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "' . $label . '" não pode ficar em branco')));
            }
        }

        $data['empresa'] = $this->session->userdata('empresa');
        $data['token'] = uniqid();
        $data['data_inscricao'] = date('Y-m-d H:i:s');

        if (isset($data['cpf']) and strlen($data['cpf']) > 0) {
            $verifica_cpf = $this->db->get_where('recrutamento_usuarios', array('cpf' => $data['cpf']))->num_rows();
            if ($verifica_cpf > 0) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse CPF já está em uso')));
            }
        } else {
            unset($data['cpf']);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));
        }

        $verifica_email = $this->db->get_where('recrutamento_usuarios', array('email' => $data['email']))->num_rows();
        if ($verifica_email > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse endereço de e-mail já está em uso')));
        }

        if ($data['senha'] != $data['confirmarsenha']) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));
        }
        unset($data['confirmarsenha']);

        $this->load->model('usuarios_model', 'usuarios');
        $data['senha'] = $this->usuarios->setPassword($data['senha']);
        $data['foto'] = "avatar.jpg";

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
        }

        if ($this->db->query($this->db->insert_string('recrutamento_usuarios', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de candidato efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('recrutamento_candidatos')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de candidato, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));

        $where = array('id' => $data['id']);
        unset($data['id']);

        $status = $this->db->update('recrutamento', $data, $where);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_updatePerfil()
    {
        $data = $this->input->post();

        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Nome candidato" não pode ficar em branco')));
        }

        if (empty($data['telefone'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Telefone" não pode ficar em branco')));
        }

        if (empty($data['email'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "E-mail" não pode ficar em branco')));
        }

        $data['empresa'] = $this->session->userdata('empresa');
        $data['data_edicao'] = date('Y-m-d H:i:s');

        if (isset($data['cpf']) and strlen($data['cpf']) > 0) {
            $this->db->where('cpf', $data['cpf']);
            $this->db->where('id !=', $data['id']);
            $verifica_cpf = $this->db->get('recrutamento_usuarios')->num_rows();
            if ($verifica_cpf > 0) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse CPF já esá em uso')));
            }
        } else {
            unset($data['cpf']);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Endereço de e-mail inválido')));
        }

        $this->db->where('email', $data['email']);
        $this->db->where('id !=', $data['id']);
        $verifica_email = $this->db->get('recrutamento_usuarios')->num_rows();
        if ($verifica_email > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse endereço de e-mail já está em uso')));
        }

        if (strlen($data['senha']) > 0) {
            if (empty($data['confirmarsenha'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Confirmar Senha" não pode ficar em branco')));
            }
            if ($data['senha'] != $data['confirmarsenha']) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));
            }
            $this->load->model('usuarios_model', 'usuarios');
            $data['senha'] = $this->usuarios->setPassword($data['senha']);
        }

        unset($data['confirmarsenha']);
        $data['foto'] = "avatar.jpg";

        $this->db->select('foto');
        $this->db->where('id', $data['id']);
        $row = $this->db->get('recrutamento_usuarios')->row();
        $fotoAtual = $row ? utf8_decode($row->foto) : '';

        if (!empty($_FILES['foto'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['foto']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data();
                $data['foto'] = utf8_encode($foto['file_name']);
                if ($fotoAtual != "avatar.jpg" && file_exists('./imagens/usuarios/' . $fotoAtual) && $fotoAtual != $foto['file_name']) {
                    @unlink('./imagens/usuarios/' . $fotoAtual);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        $id = $data['id'];
        unset($data['id']);

        if ($this->db->query($this->db->update_string('recrutamento_usuarios', $data, array('id' => $id)))) {
            $pagina = $this->session->userdata('tipo') == 'candidato' ? 'home' : 'recrutamento_candidatos';
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de candidato efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url($pagina)));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de candidato, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function ajax_updateFormacao()
    {
        $rows = $this->input->post();
        $idUsuario = $this->input->post('id_usuario');
        $escolaridade = $this->input->post('escolaridade');
        unset($rows['id_usuario'], $rows['escolaridade']);

        $arrId = array_filter($rows['id']);

        $this->db->trans_start();

        $this->db->set('escolaridade', $escolaridade);
        $this->db->where('id', $idUsuario);
        $this->db->update('recrutamento_usuarios');

        if ($arrId) {
            $this->db->where('id_usuario', $idUsuario);
            $this->db->where_not_in('id', $arrId);
            $this->db->delete('recrutamento_formacao');
        }

        $arrData = array();
        foreach ($rows as $column => $values) {
            foreach ($values as $row => $value) {
                $arrData[$row][$column] = $value;
            }
        }

        foreach ($arrData as $data) {
            if (strlen($data['instituicao']) == 0) {
                continue;
            }

            $data['id_usuario'] = $idUsuario;
            $data['id_escolaridade'] = $escolaridade;
            if (!(isset($data['curso']) and strlen($data['curso']) > 0)) {
                $data['curso'] = null;
            }
            if (!empty($data['tipo']) == false) {
                $data['tipo'] = null;
            }
            if (strlen($data['ano_conclusao']) == 0) {
                $data['ano_conclusao'] = null;
            }
            if ($data['id']) {
                $this->db->update('recrutamento_formacao', $data, array('id' => $data['id']));
            } else {
                $this->db->insert('recrutamento_formacao', $data);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() == false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao cadastrar formações do candidato, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        $pagina = $this->session->userdata('tipo') == 'candidato' ? 'home' : 'recrutamento_candidatos';
        echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de formação do candidato efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url($pagina)));
    }

    public function ajax_updateHistorico()
    {
        $rows = $this->input->post();
        $idUsuario = $this->input->post('id_usuario');
        unset($rows['id_usuario']);

        $arrId = array_filter($rows['id']);

        $this->db->trans_start();

        if ($arrId) {
            $this->db->where('id_usuario', $idUsuario);
            $this->db->where_not_in('id', $arrId);
            $this->db->delete('recrutamento_experiencia_profissional');
        }

        $arrData = array();
        foreach ($rows as $column => $values) {
            foreach ($values as $row => $value) {
                $arrData[$row][$column] = $value;
            }
        }

        foreach ($arrData as $data) {
            if (strlen($data['instituicao']) == 0) {
                continue;
            }
            if (empty($data['data_entrada'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de entrada é obrigatória', 'redireciona' => 0, 'pagina' => '')));
            }
            if (empty($data['cargo_entrada'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O cargo de entrada é obrigatório', 'redireciona' => 0, 'pagina' => '')));
            }
            if (empty($data['salario_entrada'])) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O salário de entrada é obrigatório', 'redireciona' => 0, 'pagina' => '')));
            }

            $data['id_usuario'] = $idUsuario;
            $data['data_entrada'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_entrada'])));
            if ($data['data_saida']) {
                $data['data_saida'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_saida'])));
            } else {
                $data['data_saida'] = null;
            }
            $data['salario_entrada'] = str_replace(array('.', ','), array('', '.'), $data['salario_entrada']);
            if ($data['salario_saida']) {
                $data['salario_saida'] = str_replace(array('.', ','), array('', '.'), $data['salario_saida']);
            } else {
                $data['salario_saida'] = null;
            }
            if ($data['id']) {
                $this->db->update('recrutamento_experiencia_profissional', $data, array('id' => $data['id']));
            } else {
                $this->db->insert('recrutamento_experiencia_profissional', $data);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() == false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao cadastrar histórico profissional do candidato, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        $pagina = $this->session->userdata('tipo') == 'candidato' ? 'home' : 'recrutamento_candidatos';
        echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de formação do candidato efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url($pagina)));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('recrutamento_usuarios', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }


    public function ajax_updateCurriculo()
    {
        $id = $this->input->post('id');

        $this->db->trans_begin();

        $this->db->select('arquivo_curriculo');
        $this->db->where('id', $id);
        $row = $this->db->get('recrutamento_usuarios')->row();
        $arquivoCurriculoAtual = $row->arquivo_curriculo;

        if (!empty($_FILES['arquivo_curriculo'])) {
            $config['upload_path'] = './arquivos/curriculos/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = utf8_decode($_FILES['arquivo_curriculo']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('arquivo_curriculo')) {
                $arquivoCurriculo = $this->upload->data();
                $data['arquivo_curriculo'] = utf8_encode($arquivoCurriculo['file_name']);

                $this->db->set('arquivo_curriculo', $data['arquivo_curriculo']);
                $this->db->where('id', $id);
                $this->db->update('recrutamento_usuarios');

                if (file_exists('./arquivos/curriculos/' . $arquivoCurriculoAtual) && $arquivoCurriculoAtual != $arquivoCurriculo['file_name']) {
                    @unlink('./arquivos/curriculos/' . $arquivoCurriculoAtual);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        $status = $this->db->trans_status();

        if ($status == false) {
            $this->db->trans_rollback();
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Não foi possível importar o arquivo.', 'redireciona' => 0, 'pagina' => '')));
        }

        $this->db->trans_commit();

        echo json_encode(array('retorno' => 1, 'aviso' => 'Importação de currículo efetuada com sucesso', 'redireciona' => 1, 'pagina' => site_url(lcfirst(get_class($this) . '/perfil/' . $id))));
    }


    public function visualizarPerfil()
    {
        $sql = "SELECT a.nome, a.foto,
                       DATE_FORMAT(a.data_nascimento, '%d/%m/%Y') AS data_nascimento,
                       (CASE a.sexo WHEN 'M' THEN 'Masculino' WHEN 'F' THEN 'Feminino' END) AS sexo,
                       (CASE a.sexo 
                             WHEN 'M' THEN (CASE a.estado_civil 
                                                 WHEN 1 THEN 'Solteiro' 
                                                 WHEN 2 THEN 'Casado' 
                                                 WHEN 3 THEN 'Desquitado' 
                                                 WHEN 4 THEN 'Divorciado' 
                                                 WHEN 5 THEN 'Viúvo' 
                                                 WHEN 6 THEN 'Outro' END)
                             WHEN 'F' THEN (CASE a.estado_civil 
                                                 WHEN 1 THEN 'Solteira' 
                                                 WHEN 2 THEN 'Casada' 
                                                 WHEN 3 THEN 'Desquitada' 
                                                 WHEN 4 THEN 'Divorciada' 
                                                 WHEN 5 THEN 'Viúva' 
                                                 WHEN 6 THEN 'Outro' END)
                             END) AS estado_civil,
                       a.telefone, a.email,
                       a.nome_mae, a.nome_pai,
                       (CASE a.status WHEN 'A' THEN 'Ativo' WHEN 'E' THEN 'Excluído' END) AS status,
                       a.cpf, a.rg, a.pis, a.cep, 
                       a.logradouro, a.numero, a.complemento, a.bairro,
                       b.municipio AS cidade,
                       c.estado,
                       d.nome AS escolaridade,
                       e.tipo AS deficiencia,
                       a.fonte_contratacao,
                       a.arquivo_curriculo
                FROM recrutamento_usuarios a
                LEFT JOIN municipios b ON b.cod_mun = a.cidade
                LEFT JOIN estados c ON c.cod_uf = a.estado
                LEFT JOIN escolaridade d ON d.id = a.escolaridade
                LEFT JOIN deficiencias e ON e.id = a.deficiencia
                WHERE a.id = '{$this->uri->rsegment(3)}'";
        $data = $this->db->query($sql)->row_array();


        $this->db->select('a.*, b.nome AS escolaridade', false);
        $this->db->join('escolaridade b', 'b.id = a.id_escolaridade');
        $this->db->where('a.id_usuario', $this->uri->rsegment(3));
        $this->db->order_by('a.id_escolaridade', 'asc');
        $this->db->order_by('a.ano_conclusao', 'asc');
        $formacoes = $this->db->get('recrutamento_formacao a')->result();

        $formacaoCampos = (object)array_fill_keys($this->db->get('recrutamento_formacao')->list_fields(), null);
        $data['formacao'] = array();
        for ($i = 0; $i <= 12; $i++) {
            $data['formacao'][$i] = $formacaoCampos;
        }

        $ensinoFundamental = array();
        $ensinoMedio = array();
        $graduacao = array();
        $posGraduacao = array();
        $mestrado = array();
        foreach ($formacoes as $formacao) {
            switch ($formacao->id_escolaridade) {
                case 1:
                case 2:
                    $ensinoFundamental[] = $formacao;
                    break;
                case 3:
                case 4:
                    $ensinoMedio[] = $formacao;
                    break;
                case 5:
                case 6:
                    $graduacao[] = $formacao;
                    break;
                case 7:
                case 8:
                    $posGraduacao[] = $formacao;
                    break;
                default:
                    $mestrado[] = $formacao;
            }
        }
        array_splice($data['formacao'], 0, count($ensinoFundamental), $ensinoFundamental);
        array_splice($data['formacao'], 1, count($ensinoMedio), $ensinoMedio);
        array_splice($data['formacao'], 4, count($graduacao), $graduacao);
        array_splice($data['formacao'], 7, count($posGraduacao), $posGraduacao);
        array_splice($data['formacao'], 10, count($mestrado), $mestrado);


        $this->db->select('id, instituicao, cargo_entrada, cargo_saida, motivo_saida, realizacoes');
        $this->db->select("DATE_FORMAT(data_entrada, '%d/%m/%Y') AS data_entrada", false);
        $this->db->select("DATE_FORMAT(data_saida, '%d/%m/%Y') AS data_saida", false);
        $this->db->select("FORMAT(salario_entrada, 2, 'de_DE') AS salario_entrada", false);
        $this->db->select("FORMAT(salario_saida, 2, 'de_DE') AS salario_saida", false);
        $this->db->where('id_usuario', $this->uri->rsegment(3));
        $this->db->order_by('data_entrada', 'desc');
        $this->db->order_by('data_saida', 'desc');
        $this->db->limit(7);
        $expProfissional = $this->db->get('recrutamento_experiencia_profissional')->result();
        $expProfissionalCampos = (object)array_fill_keys($this->db->get('recrutamento_experiencia_profissional')->list_fields(), null);
        $data['historicoProfissional'] = array();
        for ($i = 0; $i <= 6; $i++) {
            $data['historicoProfissional'][$i] = $expProfissionalCampos;
        }
        array_splice($data['historicoProfissional'], 0, count($expProfissional), $expProfissional);

        $this->load->view('recrutamento_perfil_visualizacao', $data);
    }

}
