<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal_documentos extends MY_Controller
{

//    protected $tipo_usuario = array('empresa', 'selecionador');

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->candidato();
    }

    public function candidato($idCandidato = null)
    {
        $data = array(
            'id_usuario' => '',
            'id_candidato' => '',
            'requisicao' => '',
            'nome_candidato' => '',
            'nome_cargo' => '',
            'nome_requisicao' => '',
            'modelos' => array('' => 'selecione ...'),
            'modulo' => ''
        );

        if ($this->uri->rsegment(3)) {
            $this->db->select('a.id, a.nome, c.nome AS nome_cargo, b.id AS candidato');
            $this->db->select('d.id AS id_requisicao, d.numero AS nome_requisicao');
            $this->db->join('requisicoes_pessoal_candidatos b', 'b.id_usuario = a.id');
            $this->db->join('requisicoes_pessoal d', 'd.id = b.id_requisicao');
            $this->db->join('empresa_cargos c', 'c.id = d.id_cargo');
            $this->db->where('b.id', $this->uri->rsegment(3));
            $row = $this->db->get('recrutamento_usuarios a')->row();

            if ($row) {
                $data['id_usuario'] = $row->id;
                $data['id_candidato'] = $row->candidato;
                $data['requisicao'] = $row->id_requisicao;
                $data['nome_candidato'] = $row->nome;
                $data['nome_cargo'] = $row->nome_cargo;
                $data['nome_requisicao'] = $row->nome_requisicao;
            }
        }

        $this->db->select('id, nome');
        $this->db->where('id_usuario_EMPRESA', $this->session->userdata('empresa'));
        $rows = $this->db->get('recrutamento_modelos')->result();
        foreach ($rows as $row) {
            $data['modelos'][$row->id] = $row->nome;
        }

        $data['idCandidato'] = $idCandidato;

        $this->load->view('requisicaoPessoal_documentos', $data);
    }

    public function ajaxList($idCandidato = '')
    {
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.candidato,
                       s.nome_arquivo
                FROM (SELECT d.id,
                             c.nome AS candidato,
                             d.nome_arquivo
                      FROM requisicoes_pessoal_candidatos a
                      INNER JOIN requisicoes_pessoal b ON 
                                 b.id = a.id_requisicao
                      INNER JOIN recrutamento_usuarios c ON 
                                 c.id = a.id_usuario
                      LEFT JOIN requisicoes_pessoal_documentos d ON 
                                d.id_candidato = a.id
                      WHERE c.empresa = {$this->session->userdata('empresa')}";
        if ($idCandidato) {
            $sql .= " AND a.id = {$idCandidato}";
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.candidato', 's.nome_arquivo');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 1) {
                    $sql .= " OR
                         {$column} LIKE '%{$post['search']['value']}%'";
                } elseif ($key == 1) {
                    $sql .= " 
                        WHERE {$column} LIKE '%{$post['search']['value']}%'";
                }
            }
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        if ($post['length'] > 0) {
            $sql .= " 
                     LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $requisicao) {
            $row = array();
            $row[] = $requisicao->candidato;
            $row[] = $requisicao->nome_arquivo;
            if (strlen($requisicao->nome_arquivo) > 0) {
                $row[] = '
                          <button class="btn btn-sm btn-info" onclick="visualizar(' . $requisicao->id . ');" title="Visualizar documento"><i class="glyphicon glyphicon-eye-open"></i></button>
                          <button class="btn btn-sm btn-danger" onclick="delete_documento(' . $requisicao->id . ');" title="Excluir documento"><i class="glyphicon glyphicon-trash"></i></button>
                          <button class="btn btn-sm btn-primary" onclick="baixar(' . $requisicao->id . ');" title="Baixar documento"><i class="glyphicon glyphicon-download-alt"></i></button>
                          ';
            } else {
                $row[] = '
                          <button class="btn btn-sm btn-info disabled" title="Visualizar documento"><i class="glyphicon glyphicon-eye-open"></i></button>
                          <button class="btn btn-sm btn-danger disabled" title="Excluir documento"><i class="glyphicon glyphicon-trash"></i></button>
                          <button class="btn btn-sm btn-primary disabled" title="Baixar documento"><i class="glyphicon glyphicon-download-alt"></i></button>
                          ';
            }

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function salvar()
    {
        $idCandidato = $this->input->post('id_candidato');

        if (!empty($_FILES['nome_arquivo']) == false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Arquivo não encontrado', 'redireciona' => 0, 'pagina' => '')));
        }

        $config['upload_path'] = './arquivos/empresa-docs/';
        $config['allowed_types'] = '*';
        $config['file_name'] = utf8_decode($_FILES['nome_arquivo']['name']);

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('nome_arquivo')) {
            $nomeArquivo = $this->upload->data();
            $data['nome_arquivo'] = utf8_encode($nomeArquivo['file_name']);
            $data['tipo_arquivo'] = utf8_encode($nomeArquivo['file_type']);
            /*if ($funcionario->foto_descricao != "avatar.jpg" && file_exists('./arquivos/empresa-docs/' . $funcionario->foto_descricao) && $funcionario->foto_descricao != $data['foto_descricao']) {
                @unlink('./arquivos/empresa-docs/' . $funcionario->foto_descricao);
            }*/
            $data['id_candidato'] = $idCandidato;
            $data['data_upload'] = date('Y-m-d H:i:s');
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
        }

        if (!$this->db->insert('requisicoes_pessoal_documentos', $data)) {
            exit (json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar documento, tente novamente, se o erro persistir entre em contato com o administrador')));
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Documento salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('requisicaoPessoal_documentos/candidato/' . $idCandidato)));
    }


    public function visualizar()
    {
        $id = $this->input->post('id');
        $arquivo = $this->localizarArquivo($id);
        if (!$arquivo) {
            exit(array('erro' => 'Arquivo não encontrado'));
        }

        echo json_encode(['file' => base_url($arquivo)]);
    }

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $arquivo = $this->localizarArquivo($id);
        if ($arquivo) {
            unlink($arquivo);
        }

        $status = $this->db->delete('requisicoes_pessoal_documentos', ['id' => $id]);

        echo json_encode(['status' => $status !== false]);
    }

    public function baixar()
    {
        $id = $this->input->post('id');
        $arquivo = $this->localizarArquivo($id);

        if ($arquivo) {
            $this->load->helper('download');
            $data = file_get_contents($arquivo);
            force_download($arquivo, $data);
        }
    }

    private function localizarArquivo($id)
    {
        $this->db->select('nome_arquivo');
        $this->db->where('id', $id);
        $documento = $this->db->get('requisicoes_pessoal_documentos')->row();

        $arquivo = './arquivos/empresa-docs/' . $documento->nome_arquivo;

        if (!file_exists($arquivo)) {
            return false;
        }

        return $arquivo;
    }

}
