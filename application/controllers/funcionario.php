<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Funcionario extends MY_Controller
{

    public function atualizarEstrutura()
    {
        $empresa = $this->session->userdata('empresa');
        $filtro = array(
            'depto' => $this->input->post('depto'),
            'area' => $this->input->post('area'),
            'setor' => $this->input->post('setor')
        );
        $option = array(
            'area' => array('' => 'selecione...'),
            'setor' => array('' => 'selecione...')
        );

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $empresa);
        if ($filtro['depto']) {
            $this->db->where('b.id', $filtro['depto']);
        }
        $this->db->order_by('a.nome', 'asc');
        $areas = $this->db->get('empresa_areas a')->result();
        foreach ($areas as $area) {
            $option['area'][$area->id] = $area->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $empresa);
        if ($filtro['depto']) {
            $this->db->where('c.id', $filtro['depto']);
        }
        if ($filtro['area']) {
            $this->db->where('b.id', $filtro['area']);
        }
        $this->db->order_by('a.nome', 'asc');
        $setores = $this->db->get('empresa_setores a')->result();
        foreach ($setores as $setor) {
            $option['setor'][$setor->id] = $setor->nome;
        }

        $data['area'] = form_dropdown('area', $option['area'], $filtro['area'], 'class="combobox form-control estrutura"');
        $data['setor'] = form_dropdown('setor', $option['setor'], $filtro['setor'], 'class="combobox form-control estrutura"');

        echo json_encode($data);
    }

    public function atualizarCargoFuncao()
    {
        $empresa = $this->session->userdata('empresa');
        $filtro = array(
            'cargo' => $this->input->post('cargo'),
            'funcao' => $this->input->post('funcao')
        );

        $this->db->select('a.id, a.nome', false);
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->where('b.id_empresa', $empresa);
        if ($filtro['cargo']) {
            $this->db->where('b.id', $filtro['cargo']);
        }
        $this->db->order_by('a.nome', 'asc');
        $funcoes = $this->db->get('empresa_funcoes a')->result();
        $option = array('' => 'selecione...');
        foreach ($funcoes as $funcao) {
            $option[$funcao->id] = $funcao->nome;
        }

        $data['funcao'] = form_dropdown('funcao', $option, $filtro['funcao'], 'class="combobox form-control cargo_funcao"');

        echo json_encode($data);
    }

    public function novo()
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
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();
        $data['funcionarios'] = array('' => 'digite ou selecione...');
        foreach ($usuarios as $usuario) {
            $data['funcionarios'][$usuario->nome] = $usuario->nome;
        }

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa->id);
        $this->db->order_by('nome', 'asc');
        $deptos = $this->db->get('empresa_departamentos')->result();
        $data['depto'] = array('' => 'selecione...');
        foreach ($deptos as $depto) {
            $data['depto'][$depto->id] = $depto->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $empresa->id);
        $this->db->order_by('a.nome', 'asc');
        $areas = $this->db->get('empresa_areas a')->result();
        $data['area'] = array('' => 'selecione...');
        foreach ($areas as $area) {
            $data['area'][$area->id] = $area->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $empresa->id);
        $this->db->order_by('a.nome', 'asc');
        $setores = $this->db->get('empresa_setores a')->result();
        $data['setor'] = array('' => 'selecione...');
        foreach ($setores as $setor) {
            $data['setor'][$setor->id] = $setor->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $this->db->order_by('contrato', 'asc');
        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'digite ou selecione...');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->db->select('DISTINCT(centro_custo) AS nome');
        $this->db->where('empresa', $empresa->id);
        $this->db->where('CHAR_LENGTH(centro_custo) >', 0);
        $this->db->order_by('centro_custo', 'asc');
        $centro_custos = $this->db->get('usuarios')->result();
        $data['centro_custo'] = array('' => 'digite ou selecione...');
        foreach ($centro_custos as $centro_custo) {
            $data['centro_custo'][$centro_custo->nome] = $centro_custo->nome;
        }

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa->id);
        $this->db->order_by('nome', 'asc');
        $cargos = $this->db->get('empresa_cargos')->result();
        $data['cargo'] = array('' => 'selecione...');
        foreach ($cargos as $cargo) {
            $data['cargo'][$cargo->id] = $cargo->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->where('b.id_empresa', $empresa->id);
        $this->db->order_by('a.nome', 'asc');
        $funcoes = $this->db->get('empresa_funcoes a')->result();
        $data['funcao'] = array('' => 'selecione...');
        foreach ($funcoes as $funcao) {
            $data['funcao'][$funcao->id] = $funcao->nome;
        }

        $this->load->view('novofuncionario1', $data);
    }

    public function cadastrar()
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
        $data['nome_mae'] = $_POST['nome_mae'];
        $data['nome_pai'] = $_POST['nome_pai'];
        $data['sexo'] = $_POST['sexo'];
        $data['foto'] = "avatar.jpg";
        $data['tipo_vinculo'] = $_POST['tipo_vinculo'];
        $data['rg'] = $this->input->post('rg');
        if (strlen($data['rg']) == 0) {
            $data['rg'] = null;
        }
        $data['cpf'] = $this->input->post('cpf');
        if (strlen($data['cpf']) == 0) {
            $data['cpf'] = null;
        }
        $data['cnpj'] = $this->input->post('cnpj');
        if (strlen($data['cnpj']) == 0) {
            $data['cnpj'] = null;
        }
        $data['pis'] = $this->input->post('pis');
        if (strlen($data['pis']) == 0) {
            $data['pis'] = null;
        }
        $data['telefone'] = $_POST['telefone'];
        $data['email'] = $_POST['email'];
        $data['senha'] = $_POST['senha'];

        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');
        $idCargo = $this->input->post('cargo');
        $idFuncao = $this->input->post('funcao');

        $data['id_depto'] = strlen($idDepto) ? $idDepto : null;
        $data['id_area'] = strlen($idArea) ? $idArea : null;
        $data['id_setor'] = strlen($idSetor) ? $idSetor : null;
        $data['id_cargo'] = strlen($idCargo) ? $idCargo : null;
        $data['id_funcao'] = strlen($idFuncao) ? $idFuncao : null;


        $data['depto'] = $this->db->select('nome')->where('id', $idDepto)->get('empresa_departamentos')->row()->nome ?? null;
        $data['area'] = $this->db->select('nome')->where('id', $idArea)->get('empresa_areas')->row()->nome ?? null;
        $data['setor'] = $this->db->select('nome')->where('id', $idSetor)->get('empresa_setores')->row()->nome ?? null;
        $data['municipio'] = $_POST['municipio'];
        $data['contrato'] = $_POST['contrato'];
        $data['centro_custo'] = $_POST['centro_custo'];
        $data['cargo'] = $this->db->select('nome')->where('id', $idCargo)->get('empresa_cargos')->row()->nome ?? null;
        $data['funcao'] = $this->db->select('nome')->where('id', $idFuncao)->get('empresa_funcoes')->row()->nome ?? null;
        $data['nome_banco'] = $this->input->post('nome_banco');
        $data['agencia_bancaria'] = $this->input->post('agencia_bancaria');
        $data['conta_bancaria'] = $this->input->post('conta_bancaria');
        $data['nome_cartao'] = $this->input->post('nome_cartao');
        $data['valor_vt'] = $this->input->post('valor_vt');
        $data['nivel_acesso'] = $_POST['nivel_acesso'];
        $data['tipo'] = $data['nivel_acesso'] === '6' ? 'selecionador' : 'funcionario';
        $data['status'] = $_POST['status'];
        $data['token'] = uniqid();
        $data['observacoes_historico'] = $this->input->post('observacoes_historico');
        if (strlen($data['observacoes_historico']) == 0) {
            $data['observacoes_historico'] = null;
        }
        if (strlen($_POST['matricula']) > 0) {
            $data['matricula'] = $_POST['matricula'];
        } else {
            $data['matricula'] = null;
        }
        $data['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['data_admissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_admissao'])));
        $data['data_nascimento'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_nascimento'])));
        /* if ($_POST['data_demissao']) {
          $data['data_demissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_demissao'])));
          } else {
          $data['data_demissao'] = null;
          }
          if ($_POST['tipo_demissao']) {
          $data['tipo_demissao'] = $_POST['tipo_demissao'];
          } else {
          $data['tipo_demissao'] = null;
          } */

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

        $this->load->model('Usuarios_model', 'usuarios');

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
            $id = $this->db->insert_id();
            if ($hash_acesso) {
                $this->load->library('encrypt');
                $data['hash_acesso'] = $this->encrypt->encode(json_encode($hash_acesso), base64_encode($id));
                $this->db->update('usuarios', array('hash_acesso' => $data['hash_acesso']), array('id' => $id));
            }
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de funcionário efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('funcionario/editar/' . $id)));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editar($id_usuario = null)
    {
        if (empty($id_usuario)) {
            $id_usuario = $this->uri->rsegment(3, 0);
        }

        $this->db->select('a.*, e.id AS id_cargo1, f.id AS id_funcao1', false);
        $this->db->select('b.id AS id_depto1, c.id AS id_area1, d.id AS id_setor1', false);
        $this->db->join('empresa_departamentos b', 'b.nome = a.depto', 'left');
        $this->db->join('empresa_areas c', 'c.id_departamento = b.id AND c.nome = a.area', 'left');
        $this->db->join('empresa_setores d', 'd.id_area = c.id AND d.nome = a.setor', 'left');
        $this->db->join('empresa_cargos e', 'e.nome = a.cargo', 'left');
        $this->db->join('empresa_funcoes f', 'f.id_cargo = e.id AND f.nome = a.funcao', 'left');
        $this->db->where('a.id', $id_usuario);
        $this->db->where_in('a.tipo', array('funcionario', 'selecionador'));
        $funcionario = $this->db->get('usuarios a')->row();

        if (count($funcionario) == 0) {
            redirect(site_url('home/funcionarios'));
        }

        if ($funcionario->empresa != $this->session->userdata('id')) {
            redirect(site_url('home/funcionarios'));
        }
        if ($funcionario->hash_acesso) {
            $this->load->library('encrypt');
            $funcionario->hash_acesso = $this->encrypt->decode($funcionario->hash_acesso, base64_encode($funcionario->id));
        } else {
            $funcionario->hash_acesso = 'null';
        }

        if ($funcionario->data_nascimento) {
            $dataNascimento = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_nascimento)));
            $funcionario->data_nascimento = $dataNascimento;
        }

        $dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_admissao)));
        $dataFormatada2 = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->datacadastro)));
        if ($funcionario->data_demissao) {
            $dataFormatada3 = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_demissao)));
            $funcionario->data_demissao = $dataFormatada3;
        }
        $funcionario->data_admissao = $dataFormatada;
        $funcionario->datacadastro = $dataFormatada2;

        $data['row'] = $funcionario;


        $this->db->select('id, nome, matricula', false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'ASC');
        $this->db->group_by('id');
        $usuarios = $this->db->get('usuarios')->result();
        $data['funcionarios'] = array('' => 'selecione ou digite o nome...');
        $data['matriculas'] = array('' => 'selecione ou digite a matrícula...');
        $data['colaboradores'] = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $data['funcionarios'][$usuario->id] = $usuario->nome;
            $data['colaboradores'][$usuario->id] = $usuario->nome;
            if ($usuario->matricula) {
                $data['matriculas'][$usuario->id] = $usuario->matricula;
            }
        }

        $data['nivel_acesso'] = array(
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
        );
        $data['tipo_vinculo'] = array('1' => 'CLT', '2' => 'MEI', '3' => 'PJ', '4' => 'Autônomo');
        $data['status'] = array(
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
        );
        $data['tipo_demissao'] = array(
            '' => 'selecione...',
            '1' => 'Demissão sem justa causa',
            '2' => 'Demissão por justa causa',
            '3' => 'Pedido de demissão',
            '4' => 'Término do contrato',
            '5' => 'Rescisão antecipada pelo empregado',
            '6' => 'Rescisão antecipada pelo empregador',
            '7' => 'Desistiu da vaga',
            '8' => 'Rescisão estagiário',
            '9' => 'Rescisão por acordo'
        );

        $data['sexo'] = [
            '' => 'selecione...',
            'M' => 'Masculino',
            'F' => 'Feminino'
        ];

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $deptos = $this->db->get('empresa_departamentos')->result();
        $data['depto'] = array('' => 'digite ou selecione...');
        foreach ($deptos as $depto) {
            $data['depto'][$depto->id] = $depto->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('b.nome', $funcionario->depto);
        $this->db->order_by('a.nome', 'asc');
        $areas = $this->db->get('empresa_areas a')->result();
        $data['area'] = array('' => 'digite ou selecione...');
        foreach ($areas as $area) {
            $data['area'][$area->id] = $area->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('c.nome', $funcionario->depto);
        $this->db->where('b.nome', $funcionario->area);
        $this->db->order_by('a.nome', 'asc');
        $setores = $this->db->get('empresa_setores a')->result();
        $data['setor'] = array('' => 'digite ou selecione...');
        foreach ($setores as $setor) {
            $data['setor'][$setor->id] = $setor->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $this->db->order_by('contrato', 'asc');

        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'digite ou selecione...');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->db->select('DISTINCT(centro_custo) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(centro_custo) >', 0);
        $this->db->order_by('centro_custo', 'asc');
        $centro_custos = $this->db->get('usuarios')->result();
        $data['centro_custo'] = array('' => 'digite ou selecione...');
        foreach ($centro_custos as $centro_custo) {
            $data['centro_custo'][$centro_custo->nome] = $centro_custo->nome;
        }

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $cargos = $this->db->get('empresa_cargos')->result();
        $data['cargo'] = array('' => 'digite ou selecione...');
        foreach ($cargos as $cargo) {
            $data['cargo'][$cargo->id] = $cargo->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('b.nome', $funcionario->cargo);
        $this->db->order_by('a.nome', 'asc');
        $funcoes = $this->db->get('empresa_funcoes a')->result();
        $data['funcao'] = array('' => 'digite ou selecione...');
        foreach ($funcoes as $funcao) {
            $data['funcao'][$funcao->id] = $funcao->nome;
        }

        $data['integracao'] = $this->programaIntegracao($id_usuario); // Pendente
        $data['data_avaliado1'] = $this->periodoExperiencia($id_usuario); // Recebe parte dos dados do usuario
//        $data['data_avaliado1']['depto'] = $data['depto'];
//        $data['data_avaliado1']['area'] = $data['area'];
//        $data['data_avaliado1']['setor'] = $data['setor'];
//        $data['data_avaliado1']['cargo'] = $data['cargo'];
//        $data['data_avaliado1']['funcao'] = $data['funcao'];
        $data['data_avaliado1']['colaboradores'] = $data['colaboradores'];
        $data['data_exame1'] = $this->examesPeriodicos($id_usuario); // Pendente
        $data['data_treinamento1'] = $this->treinamentos($id_usuario); // Recebe somente dados do usuario
        $data['data_afastamento1'] = $this->afastamentos($id_usuario); // Pendente
        $data['data_avaliacao1'] = $this->avaliacaoDesempenho($id_usuario); // Recebe parte dos dados do usuario
        $data['data_faltasAtrasos1'] = $this->faltasAtrasos($id_usuario); // Pendente
        $data['data_pdi1'] = $this->pdi($id_usuario); // Recebe somente dados do usuario
        $data['data_documentos1'] = $this->documentos($id_usuario); // Recebe somente dados do usuario
        $data['data_contratos1'] = ['id_usuario' => $id_usuario]; // Recebe somente dados do usuario

        $this->load->view('editarfuncionario1', $data);
    }

    public function alterar($id = '')
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $this->db->where('id', $id);
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        $funcionario = $this->db->get('usuarios')->row();
        if (!$funcionario) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Funcionário não encontrado ou excluído recentemente!')));
        }
        if ($funcionario->empresa != $this->session->userdata('id')) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
        }
        if (isset($funcionario->foto)) {
            $funcionario->foto = utf8_decode($funcionario->foto);
        }

        $idDepto = $this->input->post('depto');
        $idArea = $this->input->post('area');
        $idSetor = $this->input->post('setor');
        $idCargo = $this->input->post('cargo');
        $idFuncao = $this->input->post('funcao');

        $data['id_depto'] = strlen($idDepto) ? $idDepto : null;
        $data['id_area'] = strlen($idArea) ? $idArea : null;
        $data['id_setor'] = strlen($idSetor) ? $idSetor : null;
        $data['id_cargo'] = strlen($idCargo) ? $idCargo : null;
        $data['id_funcao'] = strlen($idFuncao) ? $idFuncao : null;

        $data['nome'] = $_POST['funcionario'];
        $data['sexo'] = $_POST['sexo'];
        $data['nome_mae'] = $_POST['nome_mae'];
        $data['nome_pai'] = $_POST['nome_pai'];
        $data['depto'] = $this->db->select('nome')->where('id', $idDepto)->get('empresa_departamentos')->row()->nome ?? null;
        $data['area'] = $this->db->select('nome')->where('id', $idArea)->get('empresa_areas')->row()->nome ?? null;
        $data['setor'] = $this->db->select('nome')->where('id', $idSetor)->get('empresa_setores')->row()->nome ?? null;
        $data['municipio'] = $_POST['municipio'];
        $data['contrato'] = $_POST['contrato'];
        $data['centro_custo'] = $_POST['centro_custo'];
        $data['cargo'] = $this->db->select('nome')->where('id', $idCargo)->get('empresa_cargos')->row()->nome ?? null;
        $data['funcao'] = $this->db->select('nome')->where('id', $idFuncao)->get('empresa_funcoes')->row()->nome ?? null;
        $data['tipo_vinculo'] = $_POST['tipo_vinculo'];
        $data['rg'] = $this->input->post('rg');
        if (strlen($data['rg']) == 0) {
            $data['rg'] = null;
        }
        $data['cpf'] = $this->input->post('cpf');
        if (strlen($data['cpf']) == 0) {
            $data['cpf'] = null;
        }
        $data['cnpj'] = $this->input->post('cnpj');
        if (strlen($data['cnpj']) == 0) {
            $data['cnpj'] = null;
        }
        $data['pis'] = $this->input->post('pis');
        if (strlen($data['pis']) == 0) {
            $data['pis'] = null;
        }
        $data['telefone'] = $_POST['telefone'];
        $data['email'] = $_POST['email'];
        $data['nome_banco'] = $this->input->post('nome_banco');
        $data['agencia_bancaria'] = $this->input->post('agencia_bancaria');
        $data['conta_bancaria'] = $this->input->post('conta_bancaria');
        $data['nome_cartao'] = $this->input->post('nome_cartao');
        $data['valor_vt'] = $this->input->post('valor_vt');
        $data['observacoes_demissao'] = $this->input->post('observacoes_demissao');
        if (strlen($data['observacoes_demissao']) == 0) {
            $data['observacoes_demissao'] = null;
        }
        $data['observacoes_historico'] = $this->input->post('observacoes_historico');
        if (strlen($data['observacoes_historico']) == 0) {
            $data['observacoes_historico'] = null;
        }
        $dataCadastro = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $_POST['datacadastro'])));
        if ($dataCadastro != $funcionario->datacadastro) {
            $data['datacadastro'] = $dataCadastro;
        }
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['data_admissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_admissao'])));
        if ($_POST['data_nascimento']) {
            $data['data_nascimento'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_nascimento'])));
        } else {
            $data['data_nascimento'] = null;
        }
        if ($_POST['data_demissao']) {
            $data['data_demissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['data_demissao'])));
        } else {
            $data['data_demissao'] = null;
        }
        if ($_POST['tipo_demissao']) {
            $data['tipo_demissao'] = $_POST['tipo_demissao'];
        } else {
            $data['tipo_demissao'] = null;
        }
        $data['nivel_acesso'] = $_POST['nivel_acesso'];
        $data['tipo'] = $data['nivel_acesso'] === '6' ? 'selecionador' : 'funcionario';
        $data['status'] = $_POST['status'];
        if (strlen($_POST['matricula']) > 0) {
            $data['matricula'] = $_POST['matricula'];
        } else {
            $data['matricula'] = null;
        }

        $hash_acesso = $this->input->post('hash_acesso');
        if ($hash_acesso) {
            $this->load->library('encrypt');
            $data['hash_acesso'] = $this->encrypt->encode(json_encode($hash_acesso), base64_encode($funcionario->id));
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

            $this->load->model('Usuarios_model', 'usuarios');
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

                /* $target_file = $foto['full_path'];
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
            echo json_encode(array('retorno' => 1, 'aviso' => 'Funcionário editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('funcionario/editar/' . $id)));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function verificar()
    {
        $idNome = $this->input->post('busca_nome');
        $idMatricula = $this->input->post('busca_matricula');

        $this->db->select('id');
        if ($idNome) {
            $this->db->where('id', $idNome);
        } elseif ($idMatricula) {
            $this->db->where('id', $idMatricula);
        }
        $row = $this->db->get('usuarios')->row();

        echo json_encode(array('id' => $row->id ?? null));
    }

    public function aniversariantes($pdf = false)
    {
        $data['foto'] = 'imagens/usuarios/' . $this->session->userdata('foto');
        $data['foto_descricao'] = 'imagens/usuarios/' . $this->session->userdata('foto_descricao');
        $data['is_pdf'] = $pdf;

        if ($pdf) {
            $mes = $this->input->get('mes');
            $orderBy = $this->input->get('order');

            $this->db->select("nome, DATE_FORMAT(data_nascimento, '%d/%m/%Y') AS data_nascimento", false);
            $this->db->where('tipo', 'funcionario');
            if ($mes) {
                $this->db->where("MONTH(data_nascimento) =", $mes);
            }
            if (!empty($orderBy[0])) {
                $this->db->order_by('nome', $orderBy[0][1]);
            }
            if (!empty($orderBy[1])) {
                $this->db->order_by('data_nascimento', $orderBy[1][1]);
            }
            $data['funcionarios'] = $this->db->get('usuarios')->result();

            return $this->load->view('funcionarios_aniversariantesPdf', $data, true);
        }
        $this->load->view('funcionarios_aniversariantesRelatorio', $data);
    }

    public function ajaxListAniversariantes()
    {
        $mes = $this->input->post('mes');

        $this->db->select('id, nome, data_nascimento, matricula');
        $this->db->select("DATE_FORMAT(data_nascimento, '%d/%m/%Y') AS data_nascimento_de", false);
        $this->db->where('tipo', 'funcionario');
        if ($mes) {
            $this->db->where("MONTH(data_nascimento) =", $mes);
        }
        $recordsTotal = $this->db->get('usuarios')->num_rows();

        $sql = "SELECT s.* FROM ({$this->db->last_query()}) s";

        $post = $this->input->post();
        if ($post['search']['value']) {
            $sql .= " WHERE s.nome LIKE '%{$post['search']['value']}%' OR 
                            s.matricula LIKE '%{$post['search']['value']}%'";
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if ($post['order']) {
            $orderBy = [];
            foreach ($post['order'] as $order) {
                $orderBy[] = intval($order['column'] + 2) . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }

        if ($post['length'] > 0) {
            $sql .= " LIMIT {$post['start']}, {$post['length']}";
        }
        $rows = $this->db->query($sql)->result();

        $data = array();

        foreach ($rows as $row) {
            $data[] = array(
                $row->nome,
                $row->data_nascimento_de,
                '<a class="btn btn-primary btn-sm" href="' . site_url('funcionario/editar/' . $row->id) . '" title="Prontuário de colaborador">
                    <i class="glyphicon glyphicon-list"></i> Prontuário
                 </a>',
                $row->id
            );
        }

        $meses = array(
            '' => 'Todos',
            '1' => 'Janeiro',
            '2' => 'Fevereiro',
            '3' => 'Março',
            '4' => 'Abril',
            '5' => 'Maio',
            '6' => 'Junho',
            '7' => 'Julho',
            '8' => 'Agosto',
            '9' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        $output = array(
            'draw' => $this->input->post('draw'),
            'meses' => form_dropdown('', $meses, $mes, 'id="mes" class="form-control input-sm" onchange="reload_table();"'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        );

        echo json_encode($output);
    }

    public function ajaxSaveAniversariante()
    {
        $id = $this->input->post('id');
        $dataNascimento = $this->input->post('data_nascimento');
        if ($dataNascimento) {
            $dataNascimento_de = $dataNascimento;
            $dataNascimento = date('Y-m-d', strtotime(str_replace('/', '-', $dataNascimento)));
            if ($dataNascimento != preg_replace('/(\d+)\/(\d+)\/(\d+)/', '$3-$2-$1', $dataNascimento_de)) {
                exit(json_encode(['erro' => 'A data de nascimento é inválida']));
            }
        }

        $status = $this->db->update('usuarios', ['data_nascimento' => $dataNascimento], ['id' => $id]);
        echo json_encode(array('status' => $status !== false));
    }

    public function pdfAniversariantes()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.aniversariante tr { border-width: 3px; border-color: #ddd; } ';

        $stylesheet .= 'table.funcionarios tr th, table.funcionarios tr td { font-size: 11px; padding: 5px; } ';
        $stylesheet .= 'table.funcionarios thead tr th { background-color: #f5f5f5; } ';
        $stylesheet .= 'table.funcionarios thead tr th.text-center { width: auto; } ';
        $stylesheet .= 'table.funcionarios tbody tr th { background-color: #dff0d8; } ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->aniversariantes(true));


        $this->m_pdf->pdf->Output("Relatório de Aniversariantes.pdf", 'D');
    }

    private function programaIntegracao($id_usuario)
    {
        $row = $this->db->get_where('usuarios_integracao', array('id_usuario' => $id_usuario))->row();
        $data = new stdClass();
        if (!empty($row->data_inicio)) {
            $data->data_inicio = date('d/m/Y', strtotime($row->data_inicio));
        } else {
            $data->data_inicio = null;
        }
        if (!empty($row->data_termino)) {
            $data->data_termino = date('d/m/Y', strtotime($row->data_termino));
        } else {
            $data->data_termino = null;
        }
        $data->atividades_desenvolvidas = $row->atividades_desenvolvidas ?? null;
        $data->realizadores = $row->realizadores ?? null;
        $data->observacoes = $row->observacoes ?? null;

        return $data;
    }

    private function periodoExperiencia($id_usuario)
    {
        $this->db->select('depto, area, cargo, observacoes_avaliacao_exp');
        $this->db->where('id', $id_usuario);
        $funcionario = $this->db->get('usuarios')->row();

        $this->db->select('DISTINCT(depto) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $this->db->order_by('depto', 'asc');
        $deptos = $this->db->get('usuarios')->result();
        $data['depto'] = array('' => 'selecione...');
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('DISTINCT(area) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        $this->db->order_by('area', 'asc');
        $areas = $this->db->get('usuarios')->result();
        $data['area'] = array('' => 'selecione...');
        foreach ($areas as $area) {
            $data['area'][$area->nome] = $area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('area', $funcionario->area);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $this->db->order_by('setor', 'asc');
        $setores = $this->db->get('usuarios')->result();
        $data['setor'] = array('' => 'selecione...');
        foreach ($setores as $setor) {
            $data['setor'][$setor->nome] = $setor->nome;
        }

        $this->db->select('DISTINCT(cargo) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(cargo) >', 0);
        $this->db->order_by('cargo', 'asc');
        $cargos = $this->db->get('usuarios')->result();
        $data['cargo'] = array('' => 'selecione...');
        foreach ($cargos as $cargo) {
            $data['cargo'][$cargo->nome] = $cargo->nome;
        }

        $this->db->select('DISTINCT(funcao) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('cargo', $funcionario->cargo);
        $this->db->where('CHAR_LENGTH(funcao) >', 0);
        $this->db->order_by('funcao', 'asc');
        $funcoes = $this->db->get('usuarios')->result();
        $data['funcao'] = array('' => 'selecione...');
        foreach ($funcoes as $funcao) {
            $data['funcao'][$funcao->nome] = $funcao->nome;
        }

        $data['id_usuario'] = $id_usuario;
        $data['id_avaliado'] = $id_usuario;
        $data['id_avaliacao'] = '';
        $data['tipo'] = '2';
        $data['data_inicio'] = '';
        $data['data_termino'] = '';
        $data['observacoes_avaliacao_exp'] = $funcionario->observacoes_avaliacao_exp;
        $modelos = $this->db->get_where('avaliacaoexp_modelos', array('tipo' => 'P'))->result();
        $data['id_modelo'] = array('' => 'selecione...');
        foreach ($modelos as $modelo) {
            $data['id_modelo'][$modelo->id] = $modelo->nome;
        }

        return $data;
    }

    public function salvarObservacoesAvaliacaoExp()
    {
        $observacoes_avaliacao_exp = trim($this->input->post('observacoes_avaliacao_exp'));
        if (strlen($observacoes_avaliacao_exp) == 0) {
            $observacoes_avaliacao_exp = null;
        }
        $this->db->set('observacoes_avaliacao_exp', $observacoes_avaliacao_exp);
        $this->db->where('id', $this->input->post('id'));
        $status = $this->db->update('usuarios');

        echo json_encode(['status' => $status !== false]);
    }

    private function examesPeriodicos($id_usuario)
    {
        $data = array('id_usuario' => $id_usuario);
        return $data;
    }

    private function treinamentos($id_usuario)
    {
        $data = array('id_usuario' => $id_usuario);
        return $data;
    }

    private function afastamentos($id_usuario)
    {
        $this->db->select('id, empresa');
        $row = $this->db->get_where('usuarios', array('id' => $id_usuario))->row();
        $data = array('id_usuario' => $row->id, 'id_empresa' => $row->empresa);
        return $data;
    }

    private function avaliacaoDesempenho($id_usuario)
    {
        $sql = "SELECT a.id AS id_avaliacao,
                       b.nome AS titulo,
                       b.id AS id_modelo,
                       (CASE b.tipo 
                        WHEN 'A' THEN '1' 
                        WHEN 'P' THEN '2'
                        ELSE '' END) AS tipo,
                        DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio, 
                        DATE_FORMAT(a.data_termino, '%d/%m/%Y') AS data_termino
                FROM avaliacaoexp a 
                INNER JOIN avaliacaoexp_modelos b
                           ON b.id = a.id_modelo 
                WHERE a.id = {$this->uri->rsegment(3)}";
        $data = $this->db->query($sql)->row_array();

        $data['empresa'] = $this->session->userdata('empresa');
        $data['id_usuario'] = $id_usuario;
        $data['id_avaliado'] = '';
        $data['modelos'] = $this->db->get_where('avaliacaoexp_modelos', array('tipo' => 'A'))->result();

        return $data;
    }

    private function faltasAtrasos($id_usuario)
    {
        $data = array('id_usuario' => $id_usuario);
        return $data;
    }

    private function pdi($id_usuario)
    {
        $data = array('id_usuario' => $id_usuario);
        return $data;
    }

    private function documentos($id_usuario)
    {
        $data = array('id_usuario' => $id_usuario);

        $data['tipo'] = array('' => 'selecione...');
        $this->db->select('id, descricao');
        $this->db->order_by('descricao', 'asc');
        $tipos = $this->db->get_where('tipodocumento', array('categoria' => 1))->result();
        foreach ($tipos as $tipo) {
            $data['tipo'][$tipo->id] = $tipo->descricao;
        }

        return $data;
    }

    public function ajax_integracao()
    {
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => array(),
        );
        //output to json format
        echo json_encode($output);
    }

    public function salvarIntegracao($id_usuario)
    {
        $data = $this->input->post();
        $data['id_usuario'] = $id_usuario;

        if ($data['data_inicio']) {
            $data['data_inicio'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_inicio'])));
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo Data de Início não pode ficar vazio!')));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_termino'])));
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo Data de Término não pode ficar vazio!')));
        }
        if (empty($data['atividades_desenvolvidas'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo Atividaddes Desenvolvidas não pode ficar vazio!')));
        }
        if (empty($data['realizadores'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo Realizadores do Programa não pode ficar vazio!')));
        }

        $this->db->select('id');
        $row = $this->db->get_where('usuarios_integracao', array('id' => $id_usuario))->row();

        if (isset($row->id)) {
            $retorno = $this->db->update('usuarios_integracao', $data, array('id' => $row->id));
        } else {
            $retorno = $this->db->insert('usuarios_integracao', $data);
        }

        if (!$retorno) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar Programa de Integração, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Programa de Integração editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('funcionario/editar/' . $id_usuario)));
    }

    public function importarFuncionario()
    {
        $this->load->helper('form');
        $this->load->view('importarFuncionario');
    }

    public function importarCsv()
    {
        header('Content-type: text/json; charset=UTF-8');
        // Verifica se o arquivo foi enviado
        if (!(isset($_FILES) && !empty($_FILES))) {
            //Mensagem de erro
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro no envio do arquivo. Por favor, tente mais tarde', 'redireciona' => 0, 'pagina' => '')));
        }

        $this->load->helper(array('date'));

        if ($_FILES['arquivo']['error'] == 0) {
            $config['upload_path'] = './arquivos/csv/';
            $config['file_name'] = utf8_decode($_FILES['arquivo']['name']);
            $config['allowed_types'] = '*';
            $config['overwrite'] = TRUE;

            //Upload do csv
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('arquivo')) {
                $csv = $this->upload->data();

                //Importar o arquivo transferido para o banco de dados
                $handle = fopen($config['upload_path'] . $csv['file_name'], "r");

                $x = 0;
                $validacao = true;
                $html = '';
                $label = array(
                    'Funcionário',
                    'Email',
                    'Data de admissão',
                    'Senha',
                    'Departamento',
                    'Área',
                    'Setor',
                    'Cargo',
                    'Função',
                    'Telefone',
                    'Nível de acesso',
                    'Tipo de vínculo',
                    'CNPJ',
                    'Município',
                    'Contrato',
                    'Centro de custo'
                );
                $data = array();

                $this->load->library('form_validation');
                $this->load->model('Usuarios_model', 'usuarios');

                $this->db->trans_begin();

                while (($row = fgetcsv($handle, 1850, ";")) !== FALSE) {
                    $x++;

                    if ($x == 1) {
                        if (count(array_filter($row)) == 16) {
                            $label = $row;
                        }
                        continue;
                    }

                    $row = array_pad($row, 16, '');
                    if (count(array_filter($row)) == 0) {
                        $html .= "Linha $x: registro não encontrado.<br>";
                        continue;
                    }

                    $data['nome'] = utf8_encode($row[0]);
                    $data['email'] = utf8_encode($row[1]);
                    $data['data_admissao'] = utf8_encode($row[2]);
                    $data['senha'] = trim(utf8_encode($row[3]));
                    $data['depto'] = utf8_encode($row[4]);
                    $data['area'] = utf8_encode($row[5]);
                    $data['setor'] = utf8_encode($row[6]);
                    $data['cargo'] = utf8_encode($row[7]);
                    $data['funcao'] = utf8_encode($row[8]);
                    $telefones = explode('/', $row[9]);
                    foreach ($telefones as $k => $telefone) {
                        $telefones[$k] = trim($telefone);
                    }
                    $data['telefone'] = utf8_encode(implode('/', $telefones));
                    $data['nivel_acesso'] = utf8_encode(strtolower($row[10]));
                    $data['tipo_vinculo'] = utf8_encode(strtolower($row[11]));
                    $cnpj = preg_replace('/\D/', '', $row[12]);
                    $data['cnpj'] = preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
                    $data['municipio'] = trim(utf8_encode($row[13]));
                    $data['contrato'] = trim(utf8_encode($row[14]));
                    $data['centro_custo'] = trim(utf8_encode($row[15]));

                    $_POST = $data;
                    if ($this->validaCsv($label)) {
                        $data['data_admissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $row[2])));
                        $data['datacadastro'] = date('Y-m-d H:i:s');
                        $data['senha'] = $this->usuarios->setPassword($data['senha']);
                        $data['foto'] = 'avatar.jpg';
                        $data['token'] = uniqid();

                        $data['tipo'] = 'funcionario';
                        $data['empresa'] = $this->session->userdata('empresa');
                        switch ($data['nivel_acesso']) {
                            case 'administrador':
                                $data['nivel_acesso'] = 1;
                                break;
                            case 'multiplicador':
                                $data['nivel_acesso'] = 2;
                                break;
                            case 'gestor':
                                $data['nivel_acesso'] = 3;
                                break;
                            case 'colaborador clt':
                                $data['nivel_acesso'] = 4;
                                break;
                            case 'cliente':
                                $data['nivel_acesso'] = 5;
                                break;
                            case 'selecionador':
                                $data['nivel_acesso'] = 6;
                                break;
                            case 'presidente':
                                $data['nivel_acesso'] = 7;
                                break;
                            case 'gerente':
                                $data['nivel_acesso'] = 8;
                                break;
                            case 'coordenador':
                                $data['nivel_acesso'] = 9;
                                break;
                            case 'supervisor':
                                $data['nivel_acesso'] = 10;
                                break;
                            case 'encarregado':
                                $data['nivel_acesso'] = 11;
                                break;
                            case 'líder':
                                $data['nivel_acesso'] = 12;
                                break;
                            case 'cuidador comunitário':
                                $data['nivel_acesso'] = 13;
                                break;
                            case 'colaborador pj':
                                $data['nivel_acesso'] = 14;
                                break;
                            case 'representante':
                                $data['nivel_acesso'] = 15;
                                break;
                            case 'colaborador mei':
                                $data['nivel_acesso'] = 16;
                                break;
                        }
                        switch ($data['tipo_vinculo']) {
                            case 'pj':
                                $data['tipo_vinculo'] = 3;
                                break;
                            case 'mei':
                                $data['tipo_vinculo'] = 2;
                                break;
                            case 'clt':
                                $data['tipo_vinculo'] = 1;
                                $data['cnpj'] = null;
                            default:
                                $data['tipo_vinculo'] = null;
                                $data['cnpj'] = null;
                        }

                        //Inserir informação no banco
                        $this->db->query($this->db->insert_string('usuarios', $data));
                    } else {
                        $html .= $this->form_validation->error_string("Linha $x: ");
                        $validacao = false;
                    }
                }

                fclose($handle);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }

                if (!$validacao) {
                    //Mensagem de erro
                    exit(json_encode(array('retorno' => 0, 'aviso' => "Erro no registro de alguns arquivos: <br> $html", 'redireciona' => 0, 'pagina' => '')));
                }
                //Mensagem de confirmação
                echo json_encode(array('retorno' => 1, 'aviso' => 'Importação de funcionários efetuada com sucesso', 'redireciona' => 1, 'pagina' => site_url('home/funcionarios')));
            }
        }
    }

    function validaCsv($label)
    {
        $this->form_validation = new CI_Form_validation();
        $lang = array(
            'required' => 'A coluna %s é obrigatória.',
            'max_length' => 'A coluna %s não deve conter mais de %s caracteres.',
            'valid_email' => 'A coluna %s deve conter um endereço de e-mail válido.',
            'is_unique' => 'A coluna %s contém dado já cadastrado em outro usuário.',
            'is_date' => 'A coluna %s deve conter uma data válida.',
            'regex_match' => 'A coluna %s não está no formato correto.'
        );
        $this->form_validation->set_message($lang);

        $niveis = [
            'administrador',
            'presidente',
            'gerente',
            'coordenador',
            'representante',
            'supervisor',
            'encarregado',
            'líder',
            'colaborador clt',
            'colaborador mei',
            'colaborador pj',
            'cuidador comunitário',
            'gestor',
            'multiplicador',
            'selecionador',
            'cliente'
        ];

        $config = array(
            array(
                'field' => 'nome',
                'label' => $label[0],
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'email',
                'label' => $label[1],
                'rules' => 'required|valid_email|is_unique[usuarios.email]|max_length[255]'
            ),
            array(
                'field' => 'data_admissao',
                'label' => $label[2],
                'rules' => 'is_date'
            ),
            array(
                'field' => 'senha',
                'label' => $label[3],
                'rules' => 'required|max_length[32]'
            ),
            array(
                'field' => 'depto',
                'label' => $label[4],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'area',
                'label' => $label[5],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'setor',
                'label' => $label[6],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'cargo',
                'label' => $label[7],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'funcao',
                'label' => $label[8],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'telefone',
                'label' => $label[9],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'nivel_acesso',
                'label' => $label[10],
                'rules' => 'regex_match[/^(' . implode('|', $niveis) . ')$/i]'
            ),
            array(
                'field' => 'tipo_vinculo',
                'label' => $label[11],
                'rules' => 'regex_match[/^(clt|mei|pj)$/i]'
            ),
            array(
                'field' => 'cnpj',
                'label' => $label[12],
                'rules' => 'regex_match[/^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}\-?[0-9]{2}$/i]'
            ),
            array(
                'field' => 'municipio',
                'label' => $label[13],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'contrato',
                'label' => $label[14],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'centro_custo',
                'label' => $label[15],
                'rules' => 'max_length[255]'
            )
        );

        $this->form_validation->set_rules($config);

        return $this->form_validation->run();
    }

    public function pdf()
    {
        $get = $this->input->get();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $this->db->select("contrato, nome, matricula, DATE_FORMAT(data_admissao, '%d/%m/%Y') AS data_admissao", false);
        $this->db->select("CONCAT_WS('/', depto, area, setor) AS estrutura", false);
        $this->db->select("CONCAT_WS('/', cargo, funcao) AS cargo_funcao", false);
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if (isset($get['busca'])) {
            $this->db->like('nome', $get['busca']);
            $this->db->or_like('email', $get['busca']);
        }
        if (isset($get['pdi'])) {
            $this->db->where_in('id', "(select usuario from pdi where status = {$get['pdi']})");
        }
        if (isset($get['status'])) {
            $this->db->where('status', $get['status']);
        }
        if (isset($get['depto'])) {
            $this->db->where('depto', $get['depto']);
        }
        if (isset($get['area'])) {
            $this->db->where('area', $get['area']);
        }
        if (isset($get['setor'])) {
            $this->db->where('setor', $get['setor']);
        }
        if (isset($get['cargo'])) {
            $this->db->where('cargo', $get['cargo']);
        }
        if (isset($get['funcao'])) {
            $this->db->where('funcao', $get['funcao']);
        }
        if (isset($get['contrato'])) {
            $this->db->where('contrato', $get['contrato']);
        }
        if (isset($get['tipo_vinculo'])) {
            $this->db->where('tipo_vinculo', $get['tipo_vinculo']);
        }
        $data['colaboradores'] = $this->db->get('usuarios')->result();

        $this->load->library('m_pdf');

        $stylesheet = '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->load->view('funcionariosPdf', $data, true));

        $this->m_pdf->pdf->Output('Colaboradores.pdf', 'D');
    }

}
