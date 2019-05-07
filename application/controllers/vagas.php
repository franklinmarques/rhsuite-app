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
                show_404();
            }
        }

        $this->load->view('vagas', $data);
    }


    private function getCabecalho()
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
            }
        }
    }


    public function listar()
    {
        $this->db->select(["a.codigo, a.data_abertura, CONCAT(b.nome, '/', c.nome) AS cargo_funcao"], false);
        $this->db->select('a.quantidade, a.cidade_vaga, a.bairro_vaga, b.nome AS cargo, c.nome AS funcao');
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
        $this->db->select('a.beneficios, a.horario_trabalho, a.formacao_minima, a.contato_selecionador');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id');
        $this->db->where('a.codigo', $codigo);
        $data = $this->db->get('gestao_vagas a')->row();

        echo json_encode($data);
    }

    public function cadastrarCurriculo()
    {
        $cabecalho = $this->getCabecalho();

        $fields = $this->db->get('recrutamento_usuarios')->list_fields();
        $data = array_combine($fields, array_pad(array(), count($fields), ''));
        $data['empresa'] = $this->session->userdata('empresa');
        $data['titulo'] = 'Cadastro de novo candidato';
        $data['url'] = 'recrutamento_candidatos/ajax_addPerfil';
        $data['logoempresa'] = $cabecalho['logoempresa'];
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

        $codigo = $this->uri->rsegment(3);

        $row = $this->db->get_where('gestao_vagas', ['codigo' => $codigo])->row();

        $data['codigo'] = $row->codigo;


        $this->load->view('vagas_curriculo', $data);
    }


    public function salvarCandidato()
    {
        parse_str($this->input->post('candidato'), $candidato);

        if (strlen($candidato['nome']) == 0) {
            exit(json_encode(['erro' => 'O nome é obrigatório.']));
        }
        if (strlen($candidato['email']) == 0) {
            exit(json_encode(['erro' => 'O e-mail é obrigatório.']));
        }
        if (!filter_var($candidato['email'], FILTER_VALIDATE_EMAIL)) {
            exit(json_encode(['erro' => 'O e-mail é inválido.']));
        }
        if (strlen($candidato['senha']) == 0) {
            exit(json_encode(['erro' => 'A senha é obrigatória.']));
        }
        if (strlen($candidato['confirmar_senha']) == 0) {
            exit(json_encode(['erro' => 'A confirmação de senha é obrigatória.']));
        }
        if ($candidato['senha'] !== $candidato['confirmar_senha']) {
            exit(json_encode(['erro' => 'A confirmação de senha deve ser igual ao campo senha.']));
        }
        if (strlen($candidato['cpf']) > 0) {
            $qtdeCPF = $this->db->get('candidatos', ['cpf' => $candidato['cpf']])->num_rows();
            if ($qtdeCPF) {
                exit(json_encode(['erro' => 'O CPF requisitado já está cadastrado.']));
            }
        } else {
            unset($candidato['cpf']);
        }


        $this->db->trans_begin();

        $this->load->model('usuarios_model', 'usuarios');

        $candidato['empresa'] = '78';
        $candidato['senha'] = $this->usuarios->setPassword($candidato['senha']);
        $candidato['token'] = uniqid();
        $candidato['data_inscricao'] = date('Y-m-d H:i:s');
        $candidato['data_nascimento'] = date('Y-m-d', strtotime(str_replace('/', '-', $candidato['data_nascimento'])));
        unset($candidato['confirmar_senha']);


        $this->db->insert('candidatos', $candidato);

        $idCandidato = $this->db->insert_id();


        parse_str($this->input->post('formacao'), $arrFormacoes);
        unset($arrFormacoes['escolaridade']);

        $arrFormacoes['id_candidato'] = array_pad([], count($arrFormacoes['instituicao']), $idCandidato);
        $formacoes = array();

        foreach ($arrFormacoes as $column1 => $values1) {
            foreach ($values1 as $row1 => $value1) {
                $formacoes[$row1][$column1] = $value1;
            }
        }

        $camposFormacao = $this->db->list_fields('candidatos_formacoes');
        $camposFormacao = array_combine($camposFormacao, array_pad([], count($camposFormacao), null));

        foreach ($formacoes as $nivel => $formacao) {
            if (strlen($formacao['instituicao']) == 0) {
                continue;
            }

            switch ($nivel) {
                case 0:
                    $formacao['id_escolaridade'] = empty($formacao['ano_conclusao']) ? 2 : 1;
                    break;
                case 1:
                case 2:
                case 3:
                    $formacao['id_escolaridade'] = empty($formacao['ano_conclusao']) ? 4 : 3;
                    break;
                case 4:
                case 5:
                case 6:
                    $formacao['id_escolaridade'] = empty($formacao['ano_conclusao']) ? 6 : 5;
                    break;
                case 7:
                case 8:
                case 9:
                    $formacao['id_escolaridade'] = empty($formacao['ano_conclusao']) ? 8 : 7;
                    break;
                case 10:
                case 11:
                case 12:
                    $formacao['id_escolaridade'] = empty($formacao['ano_conclusao']) ? 10 : 9;
                    break;
            }

            $formacao = array_merge($camposFormacao, $formacao);

            $this->db->insert('candidatos_formacoes', $formacao);
        }


        parse_str($this->input->post('historico_profissional'), $arrHistoricosProfissionais);
        $historicosProfissionais = array();
        foreach ($arrHistoricosProfissionais as $column2 => $values2) {
            foreach ($values2 as $row2 => $value2) {
                $historicosProfissionais[$row2][$column2] = $value2;
            }
        }

        $colunasObrigatorias = ['instituicao', 'data_entrada', 'cargo_entrada', 'salario_entrada'];

        foreach ($historicosProfissionais as $historicoProfissional) {
            if (array_diff($colunasObrigatorias, array_keys(array_filter($historicoProfissional)))) {
                continue;
            }
            $historicoProfissional['id_candidato'] = $idCandidato;
            if (strlen($historicoProfissional['data_entrada'])) {
                $historicoProfissional['data_entrada'] = date('Y-m-d', strtotime(str_replace('/', '-', $historicoProfissional['data_entrada'])));
            }
            if (strlen($historicoProfissional['data_saida'])) {
                $historicoProfissional['data_saida'] = date('Y-m-d', strtotime(str_replace('/', '-', $historicoProfissional['data_saida'])));
            }
            $historicoProfissional['salario_entrada'] = str_replace(['.', ','], ['', '.'], $historicoProfissional['salario_entrada']);
            $historicoProfissional['salario_saida'] = str_replace(['.', ','], ['', '.'], $historicoProfissional['salario_saida']);

            if (strlen($formacao['instituicao'])) {
                $this->db->insert('candidatos_historico_profissional', $historicoProfissional);
            }
        }

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
                    exit(json_encode(['erro' => $this->upload->display_errors()]));
                }

                $foto = $this->upload->data();

                $this->db->set('foto', utf8_encode($foto['file_name']));
                $this->db->where('id', $idCandidato);
                $this->db->update('candidatos');
            }
        }

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            exit(json_encode(['erro' => 'Erro ao salvar o candidato']));
        }

        $this->db->trans_commit();

        echo json_encode(['status' => true]);
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

}