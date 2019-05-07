<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CandidatoVagas extends MY_Controller
{

    public function index()
    {
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
        $this->db->select('a.quantidade, a.cidade_vaga, a.bairro_vaga, b.nome AS cargo, c.nome AS funcao');
        $this->db->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura_de, d.id"], false);
        $this->db->select(["(CASE WHEN d.status = 'A' THEN 'Admitido' WHEN d.status = 'N' THEN 'Não admitido' WHEN d.id_candidato > 0 THEN 'Cadastrado' END) AS status"], false);
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->join('empresa_funcoes c', 'c.id = a.id_funcao AND c.id_cargo = b.id');
        $this->db->join('candidatos_vagas d', "d.codigo_vaga = a.codigo AND d.id_candidato = '{$id}'", 'left');
        $this->db->where('a.status', 1);
        $query = $this->db->get('gestao_vagas a');

        $config = array(
            'search' => ['codigo', 'cidade_vaga', 'bairro_vaga', 'cargo', 'funcao']
        );

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = array();
        foreach ($output->data as $row) {
            if ($row->id) {
                $btn = '<button class="btn btn-sm btn-info" title="Detalhes da vaga" onclick="visualizar_vaga(' . $row->codigo . ')">+ Detalhes</button>
                        <button class="btn btn-sm btn-warning" title="Descandidatar" onclick="descandidatar(' . $row->id . ')">Descandidatar</button>';
            } else {
                $btn = '<button class="btn btn-sm btn-info" title="Detalhes da vaga" onclick="visualizar_vaga(' . $row->codigo . ')">+ Detalhes</button>
                        <button class="btn btn-sm btn-success" title="Candidatar" onclick="candidatar(' . $row->codigo . ')">Candidatar</button>';
            }
            $data[] = array(
                $row->codigo,
                $row->data_abertura_de,
                $row->cargo_funcao,
                $row->quantidade,
                $row->cidade_vaga,
                $row->bairro_vaga,
                $row->status,
                $btn
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function candidatar()
    {
        $data = array(
            'id_candidato' => $this->session->userdata('id'),
            'codigo_vaga' => $this->input->post('codigo_vaga'),
            'data_cadastro' => date('Y-m-d H:i:s')
        );

        $status = $this->db->insert('candidatos_vagas', $data);

        echo json_encode(['status' => $status !== false]);
    }


    public function descandidatar()
    {
        $id = $this->input->post('id');

        $status = $this->db->delete('candidatos_vagas', ['id' => $id]);

        echo json_encode(['status' => $status !== false]);
    }


    public function editarPerfil()
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

        $this->load->view('candidato_perfil', $data);
    }


    public function salvarPerfil()
    {

    }

}
