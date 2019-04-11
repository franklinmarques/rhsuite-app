<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Documento extends MY_Controller
{

    public function removeCaracterEspecial($texto)
    {
        //desconvertendo do padrão entitie (tipo á para á)
        $texto = trim(html_entity_decode($texto));
        //tirando os acentos
        $texto = preg_replace('![áàãâä]+!u', 'a', $texto);
        $texto = preg_replace('![éèêë]+!u', 'e', $texto);
        $texto = preg_replace('![íìîï]+!u', 'i', $texto);
        $texto = preg_replace('![óòõôö]+!u', 'o', $texto);
        $texto = preg_replace('![úùûü]+!u', 'u', $texto);
        //parte que tira o cedilha e o ñ
        $texto = preg_replace('![ç]+!u', 'c', $texto);
        $texto = preg_replace('![ñ]+!u', 'n', $texto);
        //tirando outros caracteres invalidos
        $texto = preg_replace('[^a-z0-9\-]', '-', $texto);
        //tirando espaços
        $texto = str_replace(' ', '-', $texto);
        //trocando duplo espaço (hifen) por 1 hifen só
        $texto = str_replace('--', '-', $texto);
        $texto = str_replace('{', '', $texto);
        $texto = str_replace('}', '', $texto);
        $texto = str_replace('[', '', $texto);
        $texto = str_replace(']', '', $texto);
        $texto = str_replace('/', '', $texto);

        return strtolower($texto);
    }

    public function excluir()
    {
        if ($this->session->userdata('tipo') != 'empresa') {
            redirect(site_url('home'));
        }

        header('Content-type: text/json');

        $id = $this->uri->rsegment(3, 0);
        $this->load->helper('form');
        $dados['resultado'] = $this->db->query('SELECT d.id, d.arquivo, td.categoria
                                                FROM documentos d
                                                INNER JOIN tipodocumento td ON td.id = d.tipo
                                                WHERE d.id = ? AND d.usuario = ?
                                                ORDER BY d.descricao ASC', array($id, $this->session->userdata('id')));

        if ($dados['resultado']->num_rows() > 0) {

            $arquivo = $dados['resultado']->row(0);

            if ($arquivo->categoria == 1) {
                $pasta = 'colaborador';
            } else {
                $pasta = 'organizacao';
            }

            unlink("./arquivos/documentos/$pasta/$arquivo->arquivo");
            $this->db->where('id', $id)->delete('documentos');
            exit(json_encode('success'));
        } else {
            exit(json_encode('Erro ao excluir o arquivo, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function download()
    {
        $id = $this->input->post('id');;
        $dados['resultado'] = $this->db->query('SELECT d.arquivo, td.categoria
                                                FROM documentos d
                                                INNER JOIN tipodocumento td ON td.id = d.tipo
                                                WHERE d.id = ?', array($id));

        if ($dados['resultado']->num_rows() > 0) {
            # Nome do arquivo
            $name = $dados['resultado']->row(0)->arquivo;

            # Subpasta do arquivo
            if ($dados['resultado']->row(0)->categoria == 1) {
                $pasta = 'colaborador';
            } else {
                $pasta = 'organizacao';
            }

            # Download
            $path = "./arquivos/documentos/$pasta/";
            // make sure it's a file before doing anything!
            if (is_file($path . $name)) {
                $this->load->helper('download');
                $data = file_get_contents($path . $name); // Read the file's contents
                force_download($name, $data);
            }
        }
    }

    #Organização

    public function organizacao()
    {
        $this->load->helper('form');
        switch ($this->uri->rsegment(3)) {
            case 'gerenciar':
                $this->organizacao_gerenciar();
                break;
            case 'editar':
                $this->organizacao_editar((int)$this->uri->rsegment(4));
                break;
            default:
                $dados['tipos'] = $this->db->query('SELECT id, descricao FROM tipodocumento WHERE categoria = 2 ORDER BY descricao ASC')->result();
                $this->load->view('novodocumentoorganizacao', $dados);
        }
    }

    public function organizacao_gerenciar()
    {
        $this->load->view('documentoorganizacao');
    }

    public function organizacao_editar()
    {
        if ($this->session->userdata('tipo') != 'empresa') {
            redirect(site_url('home'));
        }

        $dados['tipos'] = $this->db->query('SELECT id, descricao FROM tipodocumento WHERE categoria = 2 ORDER BY descricao ASC')->result();
        $dados['documentos'] = $this->db->query('SELECT * FROM documentos
                                                 WHERE id = ? AND usuario = ? LIMIT 1', array($this->uri->rsegment(3, 0), $this->session->userdata('id')));

        if ($dados['documentos']->num_rows() > 0) {
            $dados['documentos'] = $dados['documentos']->result();
            $this->load->view('editardocumentoorganizacao', $dados);
        } else {
            redirect('documento/organizacao_gerenciar');
        }
    }

    public function getDocumentoOrganizacao()
    {
        $this->load->library('pagination');
        $this->load->helper(array('date'));

        //Consulta no banco
        $data['busca'] = '';
        $data['query'] = $this->db->query("SELECT d.*, td.descricao AS tipo_descricao FROM documentos d
                                           INNER JOIN tipodocumento td ON td.id = d.tipo AND td.categoria = ?
                                           WHERE d.usuario = ?
                                           ORDER BY d.descricao ASC", array(2, $this->session->userdata('empresa')));
        $data['total'] = $this->db->query('SELECT d.*, td.descricao AS tipo_descricao FROM documentos d
                                           INNER JOIN tipodocumento td ON td.id = d.tipo AND td.categoria = 2')->num_rows();
        $this->load->helper('form');

        if ($this->session->userdata('tipo') == 'empresa') {
            $this->load->view('getdocumentoorganizacao', $data);
        } else {
            $this->load->view('getdocumentoorganizacao_nivelcolaborador', $data);
        }
    }

    public function documentoOrganizacao_db()
    {
        if ($this->session->userdata('tipo') != 'empresa') {
            redirect(site_url('home'));
        }

        if (!empty($_POST)) {
            $data['datacadastro'] = date('Y-m-d H:i:s');
            $data['descricao'] = $_POST['descricao'];
            $data['tipo'] = (int)$_POST['tipo'];
            $data['usuario'] = $this->session->userdata('id');

            // Verifica os campos
            if (!empty($_FILES['arquivo']) && $data['descricao'] && $data['tipo']) {
                $config['upload_path'] = './arquivos/documentos/organizacao/';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '102400';

                $this->load->library('upload', $config);
                $_FILES['arquivo']['name'] = $this->removeCaracterEspecial($_FILES['arquivo']['name']);

                // Verifica o upload
                if ($this->upload->do_upload('arquivo')) {
                    $foto = $this->upload->data();
                    $data['arquivo'] = $foto['file_name'];

                    // Verifica se é pdf
                    if ($foto['file_ext'] === '.doc' || $foto['file_ext'] === '.docx') {
                        shell_exec("unoconv -f pdf " . $config['upload_path'] . $foto['file_name']);
                        $data['arquivo'] = $foto['raw_name'] . ".pdf";
                        unlink($config['upload_path'] . $foto['file_name']);
                    }

                    if ($this->db->query($this->db->insert_string('documentos', $data))) {
                        exit(json_encode(array('retorno' => 1, 'aviso' => 'Arquivo salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('documento/organizacao_gerenciar/' . $data['usuario']))));
                    } else {
                        exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar o arquivo, tente novamente, se o erro persistir entre em contato com o administrador', 'redireciona' => 0)));
                    }
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro no upload do arquivo: ' . $this->upload->display_errors(), 'redireciona' => 0)));
                }
            }

            // Erro no preenchimento
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Todos os campos devem ser preenchidos', 'redireciona' => 0)));
        } else {
            redirect('documento/organizacao');
        }
    }

    public function editarDocumentoOrganizacao_db()
    {
        if ($this->session->userdata('tipo') != 'empresa') {
            redirect(site_url('home'));
        }

        if (!empty($_POST)) {
            $data['usuario'] = $this->session->userdata('id');
            $data['descricao'] = $_POST['descricao'];
            $data['tipo'] = (int)$_POST['tipo'];
            $id = $this->uri->rsegment(3, 0);

            // Verifica os campos
            if (!empty($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
                $config['upload_path'] = './arquivos/documentos/organizacao/';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '102400';

                $this->load->library('upload', $config);
                $_FILES['arquivo']['name'] = $this->removeCaracterEspecial($_FILES['arquivo']['name']);

                // Verifica o upload
                if ($this->upload->do_upload('arquivo')) {
                    $foto = $this->upload->data();
                    $data['arquivo'] = $foto['file_name'];

                    // Verifica se é pdf
                    if ($foto['file_ext'] === '.doc' || $foto['file_ext'] === '.docx') {
                        shell_exec("unoconv -f pdf " . $config['upload_path'] . $foto['file_name']);
                        $data['arquivo'] = $foto['raw_name'] . ".pdf";
                        unlink($config['upload_path'] . $foto['file_name']);
                    }
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro no upload do arquivo: ' . $this->upload->display_errors(), 'redireciona' => 0)));
                }
            }

            if ($data['descricao'] && $data['tipo'] && $id > 0) {
                if ($this->db->where('id', $id)->update('documentos', $data)) {
                    exit(json_encode(array('retorno' => 1, 'aviso' => 'Arquivo salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('documento/organizacao_gerenciar/' . $data['usuario']))));
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar o arquivo, tente novamente, se o erro persistir entre em contato com o administrador', 'redireciona' => 0)));
                }
            }

            // Erro no preenchimento
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Todos os campos devem ser preenchidos', 'redireciona' => 0)));
        } else {
            redirect('documento/colaborador');
        }
    }

    # Colaborador

    public function documentoColaborador_db()
    {
        if ($this->session->userdata('tipo') != 'empresa') {
            redirect(site_url('home'));
        }

        if (!empty($_POST)) {
            $data['datacadastro'] = date('Y-m-d H:i:s');
            $data['descricao'] = $_POST['descricao'];
            $data['tipo'] = (int)$_POST['tipo'];
            $data['colaborador'] = (int)$_POST['colaborador'];
            $data['usuario'] = $this->session->userdata('id');

            if ($data['colaborador'] < 1) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar arquivo, id do colaborador não identificado ', 'redireciona' => 0)));
            }

            // Verifica os campos
            if (!empty($_FILES['arquivo']) && $data['descricao'] && $data['tipo']) {
                $config['upload_path'] = './arquivos/documentos/colaborador/';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '102400';

                $this->load->library('upload', $config);
                $_FILES['arquivo']['name'] = $this->removeCaracterEspecial($_FILES['arquivo']['name']);

                // Verifica o upload
                if ($this->upload->do_upload('arquivo')) {
                    $foto = $this->upload->data();
                    $data['arquivo'] = $foto['file_name'];

                    // Verifica se é pdf
                    if ($foto['file_ext'] === '.doc' || $foto['file_ext'] === '.docx') {
                        shell_exec("unoconv -f pdf " . $config['upload_path'] . $foto['file_name']);
                        $data['arquivo'] = $foto['raw_name'] . ".pdf";
                        unlink($config['upload_path'] . $foto['file_name']);
                    }

                    if ($this->db->query($this->db->insert_string('documentos', $data))) {
                        exit(json_encode(array('retorno' => 1, 'aviso' => 'Arquivo salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('documento/colaborador_gerenciar/' . $data['colaborador']))));
                    } else {
                        exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar o arquivo, tente novamente, se o erro persistir entre em contato com o administrador', 'redireciona' => 0)));
                    }
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro no upload do arquivo: ' . $this->upload->display_errors(), 'redireciona' => 0)));
                }
            }

            // Erro no preenchimento
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Todos os campos devem ser preenchidos', 'redireciona' => 0)));
        } else {
            redirect('documento/colaborador');
        }
    }

    public function colaborador_editar()
    {
        if ($this->session->userdata('tipo') != 'empresa') {
            redirect(site_url('home'));
        }

        $dados['tipos'] = $this->db->query('SELECT id, descricao FROM tipodocumento WHERE categoria = 1 ORDER BY descricao ASC')->result();
        $dados['documentos'] = $this->db->query('SELECT * FROM documentos
                                                 WHERE id = ? AND usuario = ? LIMIT 1', array($this->uri->rsegment(4, 0), $this->session->userdata('id')));

        if ($dados['documentos']->num_rows() > 0) {
            $dados['documentos'] = $dados['documentos']->result();
            $this->load->view('editardocumentocolaborador', $dados);
        } else {
            redirect('documento/colaborador/gerenciar');
        }
    }

    public function editarDocumentoColaborador_db()
    {
        if ($this->session->userdata('tipo') != 'empresa') {
            redirect(site_url('home'));
        }

        if (!empty($_POST)) {
            $data['descricao'] = $_POST['descricao'];
            $data['tipo'] = (int)$_POST['tipo'];
            $id = $this->uri->rsegment(3, 0);

            // Verifica os campos
            if (!empty($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
                $config['upload_path'] = './arquivos/documentos/colaborador/';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '102400';

                $this->load->library('upload', $config);
                $_FILES['arquivo']['name'] = $this->removeCaracterEspecial($_FILES['arquivo']['name']);

                // Verifica o upload
                if ($this->upload->do_upload('arquivo')) {
                    $foto = $this->upload->data();
                    $data['arquivo'] = $foto['file_name'];

                    // Verifica se é pdf
                    if ($foto['file_ext'] === '.doc' || $foto['file_ext'] === '.docx') {
                        shell_exec("unoconv -f pdf " . $config['upload_path'] . $foto['file_name']);
                        $data['arquivo'] = $foto['raw_name'] . ".pdf";
                        unlink($config['upload_path'] . $foto['file_name']);
                    }
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro no upload do arquivo: ' . $this->upload->display_errors(), 'redireciona' => 0)));
                }
            }

            if ($data['descricao'] && $data['tipo'] && $id > 0) {
                if ($this->db->where('id', $id)->update('documentos', $data)) {
                    exit(json_encode(array('retorno' => 1, 'aviso' => 'Arquivo salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('documento/colaborador_gerenciar/' . $data['colaborador']))));
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar o arquivo, tente novamente, se o erro persistir entre em contato com o administrador', 'redireciona' => 0)));
                }
            }

            // Erro no preenchimento
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Todos os campos devem ser preenchidos', 'redireciona' => 0)));
        } else {
            redirect('documento/colaborador');
        }
    }

    public function colaborador()
    {
        $this->load->helper('form');
        switch ($this->uri->rsegment(3)) {
            case 'gerenciar':
                $this->colaborador_gerenciar();
                break;
            case 'editar':
                $this->colaborador_editar($this->uri->rsegment(4, 0));
                break;
            default:
                $dados['tipos'] = $this->db->query('SELECT id, descricao
                                                    FROM tipodocumento
                                                    WHERE categoria = 1
                                                    ORDER BY descricao ASC')->result();
                $this->load->view('novodocumentocolaborador', $dados);
        }
    }

    public function colaborador_gerenciar()
    {
        if ($this->uri->rsegment(4) < 1) {
            redirect('home');
        }

        $this->load->view('documentocolaborador');
    }

    public function getDocumentoColaborador()
    {
        $this->load->library('pagination');
        $this->load->helper(array('date'));

        //Consulta no banco
        $data['busca'] = $this->input->post('busca');
        $data['total'] = $this->db->query("SELECT d.*, td.descricao AS tipo_descricao FROM documentos d
                                           INNER JOIN tipodocumento td ON td.id = d.tipo 
                                           WHERE td.categoria = 1")->num_rows();
        $data['query'] = $this->db->query("SELECT d.*, td.descricao AS tipo_descricao FROM documentos d
                                           INNER JOIN tipodocumento td ON td.id = d.tipo 
                                           WHERE td.categoria = ? AND d.colaborador = ?
                                           ORDER BY d.descricao ASC", array(1, $this->uri->rsegment(3)));

        $this->load->helper('form');

        if ($this->session->userdata('tipo') == 'empresa') {
            $this->load->view('getdocumentocolaborador', $data);
        } else {
            $this->load->view('getdocumentocolaborador_nivelcolaborador', $data);
        }
    }

    public function ajax_list1($id_usuario)
    {
        if (empty($id_usuario)) {
            $id_usuario = $this->uri->rsegment(3, 0);
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.descricao, 
                       s.tipo_descricao 
                FROM (SELECT a.id, 
                             a.descricao, 
                             b.descricao AS tipo_descricao
                      FROM documentos a
                      INNER JOIN tipodocumento b
                                 ON b.id = a.tipo
                      WHERE b.categoria = 1
                            AND a.colaborador = {$id_usuario}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.descricao', 's.tipo_descricao');
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
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $documento) {
            $row = array();
            $row[] = $documento->descricao;
            $row[] = $documento->tipo_descricao;

            $row[] = '
                      <button class="btn btn-sm btn-primary" onclick="edit_documento(' . $documento->id . ');"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_documento(' . $documento->id . ');"><i class="glyphicon glyphicon-trash"></i></button>
                      <a class="btn btn-sm btn-info" href="' . site_url('pdi/pdfRelatorio/' . $documento->id) . '"><i class="fa fa-eye"></i> Visualizar</a>
                      <button class="btn btn-sm btn-info" onclick="baixar_documento(' . $documento->id . ');"><i class="fa fa-download"></i> Download</button>
                     ';

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

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('documentos', array('id' => $id))->row();
        if (!empty($data->arquivo)) {
            $data->arquivo = anchor(base_url('arquivos/pdf/' . $data->arquivo), $data->arquivo, 'target="_blank"');
        }
        echo json_encode($data);
    }

    public function visualizar()
    {
        $this->db->select('d.*, td.descricao AS tipo_descricao, td.categoria', false);
        $this->db->join('tipodocumento td', 'td.id = d.tipo');
        $this->db->where('d.id', $this->uri->rsegment(3, 0));
        $data['arquivos'] = $this->db->get('documentos d')->row();

        $this->load->view('documentovisualizar', $data);
    }

}
