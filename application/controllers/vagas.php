<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vagas extends CI_Controller
{

    public function index()
    {
        $uri = $this->uri->segment(1);
        $data['logoempresa'] = '';
        $data['logo'] = '';
        $data['cabecalho'] = '';
        $data['imagem_fundo'] = '';
        if ($uri != 'login') {
            $row = $this->db->query("SELECT u.* FROM usuarios u
                                   WHERE u.url = ?", $uri);
            if ($row->num_rows() > 0) {
                $data['logoempresa'] = $row->row()->url;
                $data['logo'] = $row->row()->foto;
                $data['cabecalho'] = $row->row()->cabecalho;
                $data['imagem_fundo'] = $row->row()->imagem_fundo;
            } else {
//                show_404();
            }
        }

        $this->load->view('vagas', $data);
    }


    public function listar()
    {
        $this->db->select(["a.codigo, a.data_abertura, CONCAT(b.nome, '/', c.nome) AS cargo_funcao"], false);
        $this->db->select('a.quantidade, a.cidade_vaga, a.bairro_vaga, a.id_empresa, b.nome AS cargo, c.nome AS funcao');
        $this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura_de"], false);
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id');
        $this->db->where('a.status', 1);
        $query = $this->db->get('gestao_vagas a');

        $config = array(
            'search' => ['codigo', 'cidade_vaga', 'bairro_vaga', 'cargo', 'funcao']
        );

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = array();
        foreach ($output->data as $row) {
            $data[] = array(
                $row->codigo,
                $row->data_abertura_de,
                $row->cargo_funcao,
                $row->quantidade,
                $row->cidade_vaga,
                $row->bairro_vaga,
                '<button class="btn btn-sm btn-info" title="Detalhes da vaga" onclick="visualizar_vaga(' . $row->codigo . ')">Detalhes da vaga</button>
                 <button class="btn btn-sm btn-primary" title="Candidatar-se!" onclick="candidatar(' . $row->codigo . ')">Candidatar-se!</button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function visualizarDetalhes()
    {
        $codigo = $this->input->get('codigo');

        $this->db->select(["a.codigo, CONCAT(b.nome, '/', c.nome) AS cargo_funcao"], false);
        $this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura"], false);
        $this->db->select('a.perfil_profissional_desejado, a.quantidade, a.cidade_vaga, a.bairro_vaga');
        $this->db->select("(CASE a.tipo_vinculo WHEN 1 THEN 'CLT' WHEN 2 THEN 'PJ/MEI' WHEN 3 THEN 'Autônomo' END) AS tipo_vinculo", false);
        $this->db->select(["FORMAT(a.remuneracao, 2, 'de_DE') AS remuneracao"], false);
        $this->db->select('a.beneficios, a.horario_trabalho, d.nome AS formacao_minima, a.contato_selecionador');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id');
        $this->db->join('escolaridade d', 'd.id = a.formacao_minima', 'left');
        $this->db->where('a.codigo', $codigo);
        $data = $this->db->get('gestao_vagas a')->row();

        echo json_encode($data);
    }


    public function novoCandidato($codigo)
    {
        $cabecalho = $this->getCabecalho();

        $fields = $this->db->get('recrutamento_usuarios')->list_fields();
        $data = array_combine($fields, array_pad(array(), count($fields), ''));
        $data['codigo'] = $codigo;
        $data['titulo'] = 'Cadastro de novo candidato';
        $data['url'] = 'recrutamento_candidatos/ajax_addPerfil';
        $data['url_empresa'] = $cabecalho['url'];
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

        if (strlen($codigo)) {
            $this->db->select("CONCAT(b.url, '/') AS url", false);
            $this->db->select('b.foto AS logo, b.cabecalho, b.imagem_fundo');
            $this->db->join('usuarios b', 'b.id = a.id_empresa');
            $this->db->where('a.codigo', $codigo);
            $data = $this->db->get('gestao_vagas a')->row_array();

            if (empty($data)) {
                show_404();
            }
        } else {
            $data = array(
                'url' => '',
                'logo' => '',
                'cabecalho' => '',
                'imagem_fundo' => ''
            );
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


    public function salvarCandidato()
    {
        $data = $this->input->post();
        $id = $data['id'];

        $campos = array(
            'nome' => 'Nome candidato',
            'telefone' => 'Telefone',
            'email' => 'E-mail',
            'senha' => 'Senha',
            'confirmar_senha' => 'Confirmar senha',
        );
        if ($id and strlen($data['senha']) == 0 and strlen($data['confirmar_senha']) == 0) {
            unset($campos['senha'], $campos['confirmar_senha']);
        }
        foreach ($campos as $campo => $label) {
            if (empty($data[$campo])) {
                exit(json_encode(['erro' => 'O campo "' . $label . '" não pode ficar em branco.']));
            }
        }

        $this->db->select('id_empresa');
        $this->db->where('codigo', $data['codigo']);
        $vaga = $this->db->get('gestao_vagas')->row();

        $data['empresa'] = $vaga->id_empresa;
        $data['token'] = uniqid();
        $data['data_inscricao'] = date('Y-m-d H:i:s');
        if (strlen($data['data_nascimento']) > 0) {
            $data['data_nascimento'] = date('Y-m-d', strtotime(str_replace('-', '/', $data['data_nascimento'])));
        }

        if (isset($data['cpf']) and strlen($data['cpf']) > 0) {
            $this->db->where('id !=', $id);
            $this->db->where('cpf', $data['cpf']);
            $verifica_cpf = $this->db->get('recrutamento_usuarios')->num_rows();
            if ($verifica_cpf > 0) {
                exit(json_encode(['erro' => 'Esse CPF já está em uso.']));
            }
        } else {
            unset($data['cpf']);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(['erro' => 'Endereço de e-mail inválido.']));
        }

        $this->db->where('id !=', $id);
        $this->db->where('email', $data['email']);
        $verifica_email = $this->db->get('recrutamento_usuarios')->num_rows();
        if ($verifica_email > 0) {
            exit(json_encode(['erro' => 'Esse endereço de e-mail já está em uso.']));
        }

        if ($data['senha'] != $data['confirmar_senha']) {
            exit(json_encode(['erro' => 'O campo "Senha" não confere com o "Confirmar Senha"']));
        }
        unset($data['codigo'], $data['id'], $data['confirmar_senha']);

        $this->load->model('usuarios_model', 'usuarios');
        $data['senha'] = $this->usuarios->setPassword($data['senha']);
        $data['foto'] = "avatar.jpg";


        $this->db->trans_begin();

        if ($id) {
            $this->db->update('recrutamento_usuarios', $data, ['id' => $id]);
        } else {
            $this->db->insert('recrutamento_usuarios', $data);
            $id = $this->db->insert_id();
        }


        if (!empty($_FILES['foto']['tmp_name'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['foto']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                $foto = $this->upload->data();
                $data['foto'] = utf8_encode($foto['file_name']);
            } else {
                $this->db->trans_rollback();
                exit(json_encode(['erro' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '']));
            }
        }


        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            echo json_encode(['erro' => 'Erro ao efetuar cadastro de candidato, tente novamente.']);
        }

        $this->db->trans_commit();


        echo json_encode(['status' => true, 'id_usuario' => $id]);
    }


    public function salvarFormacaoCandidato()
    {
        $rows = $this->input->post();
        $idUsuario = $this->input->post('id_usuario');
        $escolaridade = $this->input->post('escolaridade');
        unset($rows['id_usuario'], $rows['escolaridade']);


        $this->db->trans_start();


        $this->db->set('escolaridade', $escolaridade);
        $this->db->where('id', $idUsuario);
        $this->db->update('recrutamento_usuarios');


        $this->db->where('id_usuario', $idUsuario);
        $this->db->delete('recrutamento_formacao');


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
            exit(json_encode(['erro' => 'Erro ao cadastrar formações do candidato, tente novamente.']));
        }

        echo json_encode(['status' => true]);
    }


    public function salvarHistoricoProfissional()
    {
        $rows = $this->input->post();
        $idUsuario = $this->input->post('id_usuario');
        unset($rows['id_usuario']);


        $this->db->trans_start();


        $this->db->where('id_usuario', $idUsuario);
        $this->db->delete('recrutamento_experiencia_profissional');


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
            exit(json_encode(['erro' => 'Erro ao cadastrar histórico profissional do candidato, tente novamente.']));
        }

        echo json_encode(['status' => true]);
    }


}