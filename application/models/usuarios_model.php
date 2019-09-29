<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios_model extends CI_Model
{
    protected static $table = 'usuarios';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'empresa' => 'is_natural_no_zero|max_length[11]',
        'tipo' => 'required|in_list[administrador,empresa,funcionario,selecionador]',
        'url' => 'required|max_length[255]',
        'nome' => 'required|max_length[255]',
        'data_nascimento' => 'valid_date',
        'sexo' => 'in_list[M,F]',
        'depto' => 'max_length[255]',
        'area' => 'max_length[255]',
        'setor' => 'max_length[255]',
        'cargo' => 'max_length[255]',
        'funcao' => 'max_length[255]',
        'municipio' => 'max_length[30]',
        'id_depto' => 'is_natural_no_zero|max_length[11]',
        'id_area' => 'is_natural_no_zero|max_length[11]',
        'id_setor' => 'is_natural_no_zero|max_length[11]',
        'id_cargo' => 'is_natural_no_zero|max_length[11]',
        'id_funcao' => 'is_natural_no_zero|max_length[11]',
        'foto' => 'uploaded[foto]|mime_in[foto.gif,jpg,png]|max_length[255]',
        'foto_descricao' => 'uploaded[foto_descricao]|mime_in[foto_descricao.gif,jpg,png]|max_length[255]',
        'cabecalho' => 'max_length[65535]',
        'imagem_inicial' => 'uploaded[imagem_inicial]|mime_in[imagem_inicial.gif,jpg,png]|max_length[65535]',
        'tipo_tela_inicial' => 'required|is_natural|exact_length[1]',
        'imagem_fundo' => 'uploaded[imagem_fundo]|mime_in[imagem_fundo.jpg,png]|max_length[65535]',
        'video_fundo' => 'uploaded[video_fundo]|mime_in[video_fundo.mp4]|max_length[65535]',
        'assinatura_digital' => 'max_length[65535]',
        'tipo_vinculo' => 'is_natural_no_zero|less_than_equal_to[4]',
        'rg' => 'valid_rg|is_unique[usuarios.rg]',
        'cpf' => 'valid_cpf|is_unique[usuarios.cpf]',
        'cnpj' => 'valid_cnpj|is_unique[usuarios.cnpj]',
        'pis' => 'valid_pis|is_unique[usuarios.pis]',
        'nome_mae' => 'max_length[255]',
        'nome_pai' => 'max_length[255]',
        'telefone' => 'max_length[255]',
        'email' => 'required|valid_email|is_unique[recrutamento_usuarios.email]|max_length[255]',
        'senha' => 'required|max_length[32]',
        'token' => 'required|is_unique[recrutamento_usuarios.token]|max_length[255]',
        'matricula' => 'is_unique[recrutamento_usuarios.matricula]|max_length[255]',
        'contrato' => 'is_unique[recrutamento_usuarios.contrato]|max_length[255]',
        'centro_custo' => 'max_length[255]',
        'nome_banco' => 'max_length[30]',
        'agencia_bancaria' => 'max_length[15]',
        'conta_bancaria' => 'max_length[15]',
        'nome_cartao' => 'max_length[100]',
        'valor_vt' => 'max_length[100]',
        'datacadastro' => 'required|valid_datetime|after_date[data_nascimento]',
        'dataeditado' => 'valid_datetime|after_date[data_nascimento]|after_date[datacadastro]',
        'data_admissao' => 'valid_datetime|after_date[data_nascimento]|after_date[datacadastro]',
        'data_demissao' => 'valid_date|after_date[data_nascimento]|after_date[datacadastro]',
        'tipo_demissao' => 'is_natural_no_zero|max_length[11]',
        'observacoes_demissao' => 'max_length[4294967295]',
        'nivel_acesso' => 'required|is_natural_no_zero|max_length[11]',
        'hash_acesso' => 'max_length[4294967295]',
        'max_colaboradores' => 'is_natural_no_zero|max_length[11]',
        'observacoes_historico' => 'max_length[4294967295]',
        'observacoes_avaliacao_exp' => 'max_length[4294967295]',
        'status' => 'is_natural_no_zero|less_than_equal_to[8]',
        'saldo_apontamentos' => 'valid_time',
        'banco_horas_icom' => 'valid_time',
        'visualizacao_pilula_conhecimento' => 'is_natural|exact_length[1]'
    ];

    protected $uploadConfig = [
        'foto' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png'],
        'foto_descricao' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png'],
        'imagem_inicial' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png'],
        'imagem_fundo' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'jpg|png'],
        'video_fundo' => ['upload_path' => './videos/usuarios/', 'allowed_types' => 'mp4'],
        'assinatura_digital' => ['upload_path' => './imagens/usuarios/', 'allowed_types' => 'gif|jpg|png']
    ];

    protected static $tipo = [
        'administrador' => 'administrador',
        'empresa' => 'empresa',
        'funcionario' => 'funcionario',
        'selecionador' => 'selecionador'
    ];

    protected static $sexo = ['M' => 'Masculino', 'F' => 'Feminino'];

    protected static $tipoTelaInicial = [
        '1' => 'Imagem padrão',
        '2' => 'Vídeo padrão',
        '3' => 'Imagem personalizada',
        '4' => 'Vídeo personalizado'
    ];

    protected static $tipoVinculo = [
        '1' => 'CLT',
        '2' => 'MEI',
        '3' => 'PJ',
        '4' => 'Autônomo'
    ];

    protected static $tipoDemissao = [
        '1' => 'Demissão sem justa causa',
        '2' => 'Demissão por justa causa',
        '3' => 'Pedido de demissão',
        '4' => 'Término do contrato',
        '5' => 'Rescisão antecipada pelo empregado',
        '6' => 'Rescisão antecipada pelo empregador',
        '7' => 'Desistiu da vaga',
        '8' => 'Rescisão estagiário',
        '9' => 'Rescisão por acordo'
    ];

    protected static $nivelAcesso = [
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
    ];

    protected static $status = [
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
    ];

    private $salt = '@#d13g0tr1nd4d3!';

    public function setPassword($value)
    {
        return md5($this->salt . $value);
    }

    public function getUsuario($email, $senha)
    {
        $row = $this->db->get_where('usuarios', array('email' => $email, 'senha' => $senha));
        if ($row->num_rows() == 1) {
            return $row->row();
        } else {
            $this->db->select("a.*, b.url, b.cabecalho, IF(a.nivel_acesso = 'E', 'candidato_externo', 'candidato') AS tipo, NULL AS hash_acesso", false);
            $this->db->join('usuarios b', 'b.id = a.empresa');
            $row = $this->db->get_where('recrutamento_usuarios a', array('a.email' => $email, 'a.senha' => $senha));
            if ($row->num_rows() == 1) {
                return $row->row();
            } else {
                $this->db->select("a.*, b.id AS empresa, b.url, b.cabecalho, 'cliente' AS tipo, NULL AS nivel_acesso, NULL AS hash_acesso", false);
                $this->db->join('usuarios b', 'b.id = a.id_empresa');
                $row = $this->db->get_where('cursos_clientes a', array('a.email' => $email, 'a.senha' => $senha));
                if ($row->num_rows() == 1) {
                    return $row->row();
                } else {
                    $this->db->select("a.*, b.id AS empresa, b.url, b.cabecalho, 'candidato_externo' AS tipo, NULL AS nivel_acesso, NULL AS hash_acesso", false);
                    $this->db->join('usuarios b', 'b.id = a.empresa');
                    $row = $this->db->get_where('candidatos a', array('a.email' => $email, 'a.senha' => $senha));
                    if ($row->num_rows() == 1) {
                        return $row->row();
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    public function getCargos()
    {
        $this->db->select('DISTINCT(cargo)');
        if ($this->session->userdata('tipo') != 'administrador') {
            $this->db->where('empresa', $this->session->userdata('empresa'));
        }
        $this->db->where('CHAR_LENGTH(cargo) > 0');
        $rows = $this->db->get(self::$table)->result();

        $data = array('' => 'selecione...');
        foreach ($rows as $row) {
            $data[$row->cargo] = $row->cargo;
        }
        return $data;
    }

    public function getFuncoes($cargo = null)
    {
        $this->db->select('DISTINCT(funcao), cargo');
        if ($this->session->userdata('tipo') != 'administrador') {
            $this->db->where('empresa', $this->session->userdata('empresa'));
        }
        if ($cargo) {
            $this->db->where('cargo', $cargo);
        }
        $this->db->where('CHAR_LENGTH(funcao) > 0');
        $rows = $this->db->get(self::$table)->result();

        $data = array('' => 'selecione...');
        foreach ($rows as $row) {
            $data[$row->funcao] = $row->funcao;
        }
        return $data;
    }

    public function isValid()
    {
        $this->load->form_validation();

        $config = array(
            array('field' => 'id', 'label' => 'ID', 'rules' => 'integer|max_length[11]'),
            array('field' => 'empresa', 'label' => 'Empresa', 'rules' => 'integer|max_length[11]'),
            array('field' => 'tipo', 'label' => 'Tipo', 'rules' => 'required|max_length[20]'),
            array('field' => 'url', 'label' => 'URL', 'rules' => 'required|valid_url|max_length[255]'),
            array('field' => 'nome', 'label' => 'Nome', 'rules' => 'required|max_length[255]'),
            array('field' => 'funcao', 'label' => 'Função', 'rules' => 'max_length[100]'),
            array('field' => 'foto', 'label' => 'Foto', 'rules' => 'required|max_length[255]'),
            array('field' => 'cabecalho', 'label' => 'Cabeçalho', 'rules' => ''),
            array('field' => 'imagem_inicial', 'label' => 'Imagem Inicial', 'rules' => 'required'),
            array('field' => 'assinatura_digital', 'label' => 'Assinatura Digital', 'rules' => ''),
            array('field' => 'email', 'label' => 'E-mail', 'rules' => 'required|valid_email|max_length[255]'),
            array('field' => 'senha', 'label' => 'Senha', 'rules' => 'required|max_length[32]'),
            array('field' => 'token', 'label' => 'Token', 'rules' => 'required|max_length[255]'),
            array('field' => 'datacadastro', 'label' => 'Data Cadastro', 'rules' => 'required|is_date'),
            array('field' => 'dataeditado', 'label' => 'Data Editado', 'rules' => 'required|is_date'),
            array('field' => 'status', 'label' => 'Status', 'rules' => 'required|integer|max_length[2]'),
            array('field' => 'nivel_acesso', 'label' => 'nivel_acesso', 'rules' => 'required|integer|max_length[11]')
        );

        $this->form_validation->set_rules($config);

        return $this->form_validation->run();
    }

}
