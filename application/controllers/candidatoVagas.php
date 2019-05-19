<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CandidatoVagas extends MY_Controller
{

    public function index()
    {
        $fields = $this->db->get('recrutamento_usuarios')->list_fields();
        $data = array_combine($fields, array_pad(array(), count($fields), ''));
        $data['empresa'] = $this->session->userdata('empresa');

        $data['estado'] = 35; # SP
        $data['cidade'] = 3550308; # São Paulo
        $data['escolaridade'] = 4; # Ensino médio completo
        $data['deficiencia'] = 0; # Nenhuma

        $data['estados'] = array('' => 'selecione ...');
        $data['cidades'] = array('' => 'selecione ...');
        $data['escolaridades'] = array('' => 'selecione ...');
        $data['deficiencias'] = array('' => 'selecione ...');

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


        $this->db->select(["a.*, DATE_FORMAT(a.data_nascimento, '%d/%m/%Y') AS data_nascimento_de"], false);
        $this->db->where('a.id', $this->session->userdata('id'));
        $data['candidato'] = $this->db->get('recrutamento_usuarios a')->row();


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


        if ($this->session->userdata('tipo') == 'cliente') {
            $usuario = $this->session->userdata('id');
        } else {
            $usuario = null;
        }
        $row = $this->db->get_where('cursos_clientes', array('id' => $usuario))->row();

        $data['empresa'] = $this->session->userdata('empresa');
//        $data['categorias'] = $this->db->query("SELECT distinct(a.categoria) FROM cursos a INNER JOIN cursos_clientes_treinamentos b ON b.id_curso = a.id INNER JOIN cursos_clientes c ON c.id = b.id_usuario WHERE c.email = '{$row->email}' AND CHAR_LENGTH(a.categoria) > 0");
//        $data['areas_conhecimento'] = $this->db->query("SELECT distinct(a.area_conhecimento) FROM cursos a INNER JOIN cursos_clientes_treinamentos b ON b.id_curso = a.id INNER JOIN cursos_clientes c ON c.id = b.id_usuario WHERE c.email = '{$row->email}' AND CHAR_LENGTH(a.area_conhecimento) > 0");
        $this->load->view('candidato_vagas', $data);
    }


    public function ajaxList()
    {
        $id = $this->session->userdata('id');

        $this->db->select(["a.codigo, a.data_abertura, CONCAT(b.nome, '/', c.nome) AS cargo_funcao"], false);
        $this->db->select('a.quantidade, a.cidade_vaga, a.bairro_vaga, e.status');
        $this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura_de, e.id AS id_candidatura"], false);
        $this->db->select(["FORMAT(a.remuneracao, 2, 'de_DE') AS remuneracao"], false);
        $this->db->select(["(CASE a.tipo_vinculo WHEN 1 THEN 'CLT' WHEN 2 THEN 'PJ/MEI' WHEN 3 THEN 'Autônomo' END) AS tipo_vinculo"], false);
        $this->db->select('b.nome AS cargo, c.nome AS funcao');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id');
        $this->db->join('requisicoes_pessoal d', "d.id = a.id_requisicao_pessoal", 'left');
        $this->db->join('requisicoes_pessoal_candidatos e', "e.id_requisicao = d.id AND e.id_usuario = '{$id}'", 'left');
        $this->db->where('a.status', 1);
        $query = $this->db->get('gestao_vagas a');

        $config = array(
            'search' => ['codigo', 'cidade_vaga', 'bairro_vaga', 'cargo', 'funcao']
        );

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = array();
        foreach ($output->data as $row) {
            if ($row->status == 'A') {
                $btnTeste = ' <a class="btn btn-sm btn-primary" title="Teste" href="' . site_url('candidatoTestes/gerenciar/') . '" target="_blank">Testes seletivos</a>';
            } else {
                $btnTeste = ' <a class="btn btn-sm btn-primary disabled" title="Teste">Testes seletivos</button>';
            }
            if ($row->id_candidatura) {
                $btn = '<button class="btn btn-sm btn-info" title="Detalhes da vaga" onclick="visualizar_vaga(' . $row->codigo . ')">+ Detalhes</button>
                        <button class="btn btn-sm btn-warning" title="Descandidatar" onclick="descandidatar(' . $row->id_candidatura . ')">Descandidatar-se</button>';
            } else {
                $btn = '<button class="btn btn-sm btn-info" title="Detalhes da vaga" onclick="visualizar_vaga(' . $row->codigo . ')">+ Detalhes</button>
                        <button class="btn btn-sm btn-success" title="Candidatar" onclick="candidatar(' . $row->codigo . ')">Candidatar-se</button>';
            }
            $data[] = array(
                $row->codigo,
                $btn,
                $row->data_abertura_de,
                $row->funcao,
                $row->quantidade,
                $row->cidade_vaga,
                $row->bairro_vaga,
                $row->remuneracao,
                $row->tipo_vinculo
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function ajaxListTestes()
    {
        $sql = "SELECT e.id, a.codigo AS codigo_vaga, d.id_requisicao, b.nome AS funcao,
                       IFNULL(f.nome, e.nome) AS nome, e.data_inicio, e.data_termino,
                       DATE_FORMAT(e.data_inicio, '%d/%m/%Y') AS data_inicio_de,
                       DATE_FORMAT(e.data_termino, '%d/%m/%Y') AS data_termino_de,
                       (CASE WHEN (now() BETWEEN e.data_inicio AND e.data_termino) AND e.data_acesso is null AND e.data_envio is null
                             THEN 'ok' 
                             WHEN (now() BETWEEN e.data_inicio AND e.data_termino) AND (e.minutos_duracao * 60 > TIMESTAMPDIFF(SECOND, e.data_acesso, now())) AND e.data_envio is null
                             THEN 'executando'  
                             WHEN now() < e.data_inicio
                             THEN 'espera' 
                             WHEN now() > e.data_termino
                             THEN 'expirada' 
                             WHEN (e.minutos_duracao * 60 < TIMESTAMPDIFF(SECOND, e.data_acesso, now()))
                             THEN 'esgotado' 
                             WHEN e.data_envio is not null
                             THEN 'concluido' 
                             ELSE '' END) AS data_valida
                FROM gestao_vagas a
                INNER JOIN empresa_funcoes b ON b.id = a.id_funcao
                INNER JOIN requisicoes_pessoal c ON c.id = a.id_requisicao_pessoal
                INNER JOIN requisicoes_pessoal_candidatos d ON d.id_requisicao = c.id
                LEFT JOIN requisicoes_pessoal_testes e ON e.id_candidato = d.id
                LEFT JOIN recrutamento_modelos f ON f.id = e.id_modelo
                WHERE d.id_usuario = '{$this->session->userdata('id')}' AND e.tipo_teste = 'O'";


        $this->load->library('dataTables');

        $output = $this->datatables->query($sql);

        $data = array();
        foreach ($output->data as $row) {
            switch ($row->data_valida) {
                case 'ok':
                    $btn = '<a class="btn btn-sm btn-success btn-block" href="javascript:void(0)" title="Iniciar teste" onclick="verificar_teste(' . "'" . $row->id . "'" . ')">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Realizar teste</a>';
                    break;
                case 'executando':
                    $btn = '<a class="btn btn-sm btn-success btn-block" href="javascript:void(0)" title="Iniciar teste" onclick="verificar_teste(' . "'" . $row->id . "'" . ')">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Finalizado</a>';
                    break;
                case 'espera':
                    $btn = '<a class="btn btn-sm btn-warning btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Data agendada&nbsp;</a>';
                    break;
                case 'expirada':
                    $btn = '<a class="btn btn-sm btn-danger btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Data expirada&nbsp;</a>';
                    break;
                case 'esgotado':
                    $btn = '<a class="btn btn-sm btn-danger btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Tempo esgotaddo&nbsp;</a>';
                    break;
                case 'concluido':
                    $btn = '<a class="btn btn-sm btn-success btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Teste concluído&nbsp;</a>';
                    break;
                default:
                    $btn = '<a class="btn btn-sm btn-success btn-block disabled">&nbsp;<i class="glyphicon glyphicon-pencil"></i> Iniciar</a>';
            }

            $data[] = array(
                $btn,
                $row->codigo_vaga,
                $row->id_requisicao,
                $row->funcao,
                $row->nome,
                $row->data_inicio_de,
                $row->data_termino_de
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function candidatar()
    {
        $this->db->select('id_requisicao_pessoal');
        $this->db->where('codigo', $this->input->post('codigo_vaga'));
        $vaga = $this->db->get('gestao_vagas')->row();

        $data = array(
            'id_requisicao' => $vaga->id_requisicao_pessoal,
            'id_usuario' => $this->session->userdata('id'),
            'status' => 'E'
        );

        $status = $this->db->insert('requisicoes_pessoal_candidatos', $data);

        echo json_encode(['status' => $status !== false]);
    }


    public function descandidatar()
    {
        $id = $this->input->post('id_candidatura');

        $status = $this->db->delete('requisicoes_pessoal_candidatos', ['id' => $id]);

        echo json_encode(['status' => $status !== false]);
    }


    public function editarPerfil()
    {
        $fields = $this->db->get('candidatos')->list_fields();
        $data = array_combine($fields, array_pad(array(), count($fields), ''));
        $data['empresa'] = $this->session->userdata('empresa');

        $data['estado'] = 35; # SP
        $data['cidade'] = 3550308; # São Paulo
        $data['escolaridade'] = 4; # Ensino médio completo
        $data['deficiencia'] = 0; # Nenhuma

        $data['estados'] = array('' => 'selecione ...');
        $data['cidades'] = array('' => 'selecione ...');
        $data['escolaridades'] = array('' => 'selecione ...');
        $data['deficiencias'] = array('' => 'selecione ...');

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


        $this->db->select(["a.*, DATE_FORMAT(a.data_nascimento, '%d/%m/%Y') AS data_nascimento_de"], false);
        $data['candidato'] = $this->db->get_where('candidatos a', ['a.id' => $this->session->userdata('id')])->row();


        $this->db->select('a.*, b.nome AS escolaridade', false);
        $this->db->join('escolaridade b', 'b.id = a.id_escolaridade');
        $this->db->where('a.id_candidato', $this->uri->rsegment(3));
        $this->db->order_by('a.id_escolaridade', 'asc');
        $this->db->order_by('a.ano_conclusao', 'asc');
        $formacoes = $this->db->get('candidatos_formacoes a')->result();

        $formacaoCampos = (object)array_fill_keys($this->db->get('candidatos_formacoes')->list_fields(), null);
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
        $this->db->where('id_candidato', $this->uri->rsegment(3));
        $this->db->order_by('data_entrada', 'desc');
        $this->db->order_by('data_saida', 'desc');
        $this->db->limit(7);
        $expProfissional = $this->db->get('candidatos_historico_profissional')->result();
        $expProfissionalCampos = (object)array_fill_keys($this->db->get('candidatos_historico_profissional')->list_fields(), null);
        $data['historicoProfissional'] = array();
        for ($i = 0; $i <= 6; $i++) {
            $data['historicoProfissional'][$i] = $expProfissionalCampos;
        }
        array_splice($data['historicoProfissional'], 0, count($expProfissional), $expProfissional);

        $this->load->view('candidato_perfil', $data);
    }


    public function salvarDadosCadastrais()
    {
        $data = $this->input->post();


        if (strlen($data['nome']) == 0) {
            exit(json_encode(['retorno' => 0, 'aviso' => 'O nome é obrigatório.']));
        }
        if (strlen($data['email']) == 0) {
            exit(json_encode(['retorno' => 0, 'aviso' => 'O e-mail é obrigatório.']));
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(['retorno' => 0, 'aviso' => 'O e-mail é inválido.']));
        }
        if (strlen($data['senha']) or strlen($data['confirmar_senha'])) {
            if (strlen($data['senha']) == 0) {
                exit(json_encode(['retorno' => 0, 'aviso' => 'A senha é obrigatória.']));
            }
            if (strlen($data['confirmar_senha']) == 0) {
                exit(json_encode(['retorno' => 0, 'aviso' => 'A confirmação de senha é obrigatória.']));
            }
            if ($data['senha'] !== $data['confirmar_senha']) {
                exit(json_encode(['retorno' => 0, 'aviso' => 'A confirmação de senha deve ser igual ao campo senha.']));
            }
        } else {
            unset($data['senha']);
        }

        if (strlen($data['cpf']) > 0) {
            $this->db->where('cpf', $data['cpf']);
            $this->db->where('id !=', $data['id']);
            $qtdeCPF = $this->db->get('candidatos')->num_rows();
            if ($qtdeCPF) {
                exit(json_encode(['retorno' => 0, 'aviso' => 'Esse CPF já esá em uso.']));
            }
        } else {
            unset($data['cpf']);
        }
        if (strlen($data['data_nascimento']) > 0) {
            $data['data_nascimento'] = date('Y-m-d', strtotime(str_replace('-', '/', $data['data_nascimento'])));
        }

        $this->db->trans_begin();

        $this->load->model('usuarios_model', 'usuarios');

        $id = $this->session->userdata('id');


        $data['empresa'] = $this->session->userdata('empresa');
        if (isset($data['senha'])) {
            $data['senha'] = $this->usuarios->setPassword($data['senha']);
        }
        $data['data_edicao'] = date('Y-m-d H:i:s');
        if (strlen($data['data_nascimento'])) {
            $data['data_nascimento'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_nascimento'])));
        }
        unset($data['confirmar_senha']);

        $row = $this->db->get_where('candidatos', ['id' => $id])->row();
        $fotoAtual = $row ? utf8_decode($row->foto) : '';


        $this->db->update('recrutamento_usuarios', $data, ['id' => $id]);


        if ($this->db->trans_status()) {
            if (isset($_FILES['foto'])) {
                $config = array(
                    'upload_path' => './imagens/usuarios/',
                    'allowed_types' => 'gif|jpg|png',
                    'file_name' => utf8_decode($_FILES['foto']['name']),
                );

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto') == false) {
                    $this->db->trans_rollback();
                    exit(json_encode(['retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '']));
                }

                $foto = $this->upload->data();
                if ($fotoAtual != "avatar.jpg" && file_exists('./imagens/usuarios/' . $fotoAtual) && $fotoAtual != $foto['file_name']) {
                    @unlink('./imagens/usuarios/' . $fotoAtual);
                }

                $this->db->set('foto', utf8_encode($foto['file_name']));
                $this->db->where('id', $id);
                $this->db->update('recrutamento_usuarios');
            }
        }

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            exit(json_encode(['retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de candidato, tente novamente. Se o erro persistir entre em contato com o administrador.']));
        }

        $this->db->trans_commit();

        echo json_encode(['retorno' => 1, 'aviso' => 'Cadastro de candidato efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url(lcfirst(get_class($this)))]);
    }


    public function salvarFormacoes()
    {
        $rows = $this->input->post();
        $idUsuario = $this->session->userdata('id');
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
            exit(json_encode(['retorno' => 0, 'aviso' => 'Erro ao cadastrar formações do candidato, tente novamente, se o erro persistir entre em contato com o administrador']));
        }

        echo json_encode(['retorno' => 1, 'aviso' => 'Cadastro de formação do candidato efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url(lcfirst(get_class($this)))]);
    }


    public function salvarHistoricoProfissional()
    {
        $rows = $this->input->post();
        $idUsuario = $this->session->userdata('id');
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
            exit(json_encode(['retorno' => 0, 'aviso' => 'Erro ao cadastrar histórico profissional do candidato, tente novamente, se o erro persistir entre em contato com o administrador']));
        }

        echo json_encode(['retorno' => 1, 'aviso' => 'Cadastro de formação do candidato efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url(lcfirst(get_class($this)))]);
    }


    public function salvarCurriculo()
    {
        $id = $this->session->userdata('id');

        $this->db->trans_begin();

        $this->db->select('arquivo_curriculo');
        $this->db->where('id', $id);
        $row = $this->db->get('recrutamento_usuarios')->row();
        $arquivoCurriculoAtual = $row->arquivo_curriculo;


        if (!empty($_FILES['arquivo_curriculo'])) {
            $config['upload_path'] = './arquivos/curriculos/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = utf8_decode($_FILES['arquivo_curriculo']['name']);

            $this->db->set('arquivo_curriculo', $config['file_name']);
            $this->db->where('id', $id);
            $this->db->update('recrutamento_usuarios');

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('arquivo_curriculo')) {
                $arquivoCurriculo = $this->upload->data();
                $data['foto'] = utf8_encode($arquivoCurriculo['file_name']);
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

        echo json_encode(array('retorno' => 1, 'aviso' => 'Importação de currículo efetuada com sucesso', 'redireciona' => 1, 'pagina' => site_url(lcfirst(get_class($this)))));
    }

}
