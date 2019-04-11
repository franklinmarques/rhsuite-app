<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cursos extends MY_Controller
{

    public function index()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $this->db->select('categoria');
        if ($this->session->userdata('tipo') == 'empresa') {
            $this->db->where('id_empresa', $this->session->userdata('id'));
        }
        $this->db->where('CHAR_LENGTH(categoria) >', 0);
        $this->db->group_by('categoria');
        $categorias = $this->db->get('cursos')->result();

        $data['categorias'] = array('' => 'Todas as categorias');
        foreach ($categorias as $categoria) {
            $data['categorias'][$categoria->categoria] = $categoria->categoria;
        }

        $this->db->select('area_conhecimento');
        if ($this->session->userdata('tipo') == 'empresa') {
            $this->db->where('id_empresa', $this->session->userdata('id'));
        }
        $this->db->where('CHAR_LENGTH(area_conhecimento) >', 0);
        $this->db->group_by('area_conhecimento');
        $areas = $this->db->get('cursos')->result();

        $data['areas_conhecimento'] = array('' => 'Todas as áreas de conhecimeto');
        foreach ($areas as $area) {
            $data['areas_conhecimento'][$area->area_conhecimento] = $area->area_conhecimento;
        }

        $this->load->view('ead/cursos', $data);
    }

    public function ajax_list()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        header('Content-Type: text/html; charset=utf-8');

        $this->load->library('pagination');

        $query = $this->input->post('busca');
        $area_conhecimento = $this->input->post('area_conhecimento');
        $categoria = $this->input->post('categoria');
        $busca = $this->input->post('busca');

        $query_categoria = null;
        $query_areaConhecimento = null;
        $query_busca = null;

        # Verificar preenchimento dos filtros
        if (!empty($categoria)) {
            $query_categoria = " AND c.categoria = ? ";
        }
        if (!empty($area_conhecimento)) {
            $query_areaConhecimento = " AND c.area_conhecimento = ? ";
        }
        if (!empty($busca)) {
            $query_busca = " AND c.nome LIKE '" . $busca . "%' ";
        }

        if ($this->session->userdata('tipo') == "administrador") {
            $qryWHERE = "WHERE c.id_empresa = ? $query_categoria $query_areaConhecimento $query_busca";
            $dataWHERE[] = $this->session->userdata('id');

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }
        } else {
            $qryWHERE = "(SELECT c.*, u.tipo FROM cursos c INNER JOIN usuarios u ON u.id = c.id_empresa WHERE c.publico = 1 $query_categoria $query_areaConhecimento $query_busca) UNION (SELECT c.*, u.tipo FROM cursos c INNER JOIN usuarios u ON u.id = c.id_empresa WHERE c.status = 1 AND c.publico = 1 $query_categoria $query_areaConhecimento $query_busca) UNION (SELECT c.*, u.tipo FROM cursos c INNER JOIN usuarios u ON u.id = c.id_empresa WHERE u.tipo = 'administrador'AND c.status = 1 $query_categoria $query_areaConhecimento $query_busca)";
            $dataWHERE[] = $this->session->userdata('id');

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }

            $dataWHERE[] = $this->session->userdata('id');

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }

            //$dataWHERE[] = 'administrador';
            //$dataWHERE[] = $this->session->userdata('id');
        }

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = site_url('ead/cursos/ajax_list');

        if ($this->session->userdata('tipo') != 'administrador') {
            $config['total_rows'] = $this->db->query("(SELECT c.*, '' AS tipo FROM cursos c WHERE c.id_empresa = 1 $query_categoria $query_areaConhecimento $query_busca) UNION {$qryWHERE}", $dataWHERE)->num_rows();
        } else {
            $config['total_rows'] = $this->db->query("SELECT c.*, '' AS tipo FROM cursos c WHERE c.id_empresa = ?", $dataWHERE)->num_rows();
        }

        $config['per_page'] = 5;

        $this->pagination->initialize($config);

        $dataWHERE[] = (int) $this->uri->rsegment(3, 0);
        $dataWHERE[] = (int) $config['per_page'];
        $data['total'] = $config['total_rows'];
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";

        if ($this->session->userdata('tipo') != 'administrador') {
            $data['query'] = $this->db->query("(SELECT c.*, u.tipo FROM cursos c INNER JOIN usuarios u ON u.id = c.id_empresa WHERE c.id_empresa = ? $query_categoria $query_areaConhecimento $query_busca) UNION {$qryWHERE} ORDER BY id_empresa = ? DESC, nome ASC LIMIT ?,?", $dataWHERE)->result();
        } else {
            $data['query'] = $this->db->query("SELECT c.*, u.tipo FROM cursos c INNER JOIN usuarios u ON u.id = c.id_empresa {$qryWHERE} ORDER BY c.id ASC LIMIT ?,?", $dataWHERE)->result();
        }
        foreach ($data['query'] as $row) {
            $row->tipo_curso = $this->db->query("SELECT * FROM cursos_usuarios WHERE id_usuario = ? AND id_curso = ?", array($this->session->userdata('id'), $row->id))->num_rows();
            $row->qtde_paginas = $this->db->query("SELECT * FROM cursos_paginas WHERE id_curso = ?", array($row->id))->num_rows();
        }

        $this->load->view('ead/getcursos', $data);
    }

    public function novo()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $this->load->view('ead/novocurso');
    }

    public function ajax_categorias()
    {
        $a_json = array();
        $categoria = $this->input->get('termo');
        # Verifica se GET está vazio
        if ($categoria) {
            $query = $this->db->query("(SELECT categoria FROM cursos WHERE categoria LIKE '$categoria%' AND categoria IS NOT NULL GROUP BY categoria) UNION (SELECT nome AS categoria FROM cursos_categorias) ORDER BY categoria")->result();
            foreach ($query as $row) {
                array_push($a_json, $row->categoria);
            }
        }

        echo json_encode($a_json);
    }

    public function ajax_areaConhecimento()
    {
        $a_json = array();
        $area_conhecimento = $this->input->get('termo');
        # Verifica se GET está vazio
        if ($area_conhecimento) {
            $query = $this->db->query("(SELECT area_conhecimento FROM cursos WHERE area_conhecimento LIKE '$area_conhecimento%' AND area_conhecimento IS NOT NULL GROUP BY area_conhecimento) UNION (SELECT nome AS area_conhecimento FROM cursos_areas) ORDER BY area_conhecimento")->result();
            foreach ($query as $row) {
                array_push($a_json, $row->area_conhecimento);
            }
        }

        echo json_encode($a_json);
    }

    public function ajax_add()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        # Variaveis do post
        $data = $this->input->post();
        if (isset($data['peoplenet_token'])) {
            unset($data['peoplenet_token']);
        }
        if (isset($data['submit'])) {
            unset($data['submit']);
        }

        $data['id_empresa'] = $this->session->userdata('id');

        $data['data_cadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        if (isset($_FILES) && !empty($_FILES)) {
            if ($_FILES['foto_consultor']['error'] == 0) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['file_name'] = utf8_decode($_FILES['foto_consultor']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_consultor')) {
                    $foto = $this->upload->data();
                    $data['foto_consultor'] = utf8_encode($foto['file_name']);
                }
                /*
                  else {
                  exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                  } */
            }

            if ($_FILES['foto_treinamento']['error'] == 0) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['file_name'] = utf8_decode($_FILES['foto_treinamento']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_treinamento')) {
                    $foto = $this->upload->data();
                    $data['foto_treinamento'] = utf8_encode($foto['file_name']);
                }
                /*
                  else {
                  exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                  }
                 */
            }
        }

        $data['publico'] = isset($data['publico']) ? $data['publico'] : 0;
        # Validação
        if ($data['nome'] == '') {
            $name = ucfirst(str_replace('_', ' ', $data['nome']));
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "' . $name . '" não pode ficar em branco')));
        }
        if ($this->db->query($this->db->insert_string('cursos', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de curso efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('ead/cursos')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de curso, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function editar()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        $usuarios = $this->db->query("SELECT * FROM usuarios WHERE tipo IN ('administrador', 'empresa') ORDER BY tipo ASC");

        if ($this->session->userdata('tipo') != "administrador") {
            if ($curso->id_empresa != $this->session->userdata('id')) {
                redirect(site_url('ead/cursos'));
            }
        }

        $data['row'] = $curso;
        $data['usuarios'] = $usuarios;

        $this->load->view('ead/editarcurso', $data);
    }

    public function ajax_update()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
        }

        # Variaveis do post
        $data = $this->input->post();
        if (isset($data['peoplenet_token'])) {
            unset($data['peoplenet_token']);
        }
        if (isset($data['submit'])) {
            unset($data['submit']);
        }
        $data['publico'] = 0;
        $data['gratuito'] = 0;
        $data['id'] = base64_decode($data['id']);

        $curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($data['id']))->row(0);
        $data['foto_consultor'] = $curso->foto_consultor;
        $data['foto_treinamento'] = $curso->foto_treinamento;

        if ($this->session->userdata('tipo') == "administrador") {
            if ($curso->id_empresa != $this->session->userdata('id')) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }
        }

        $data['data_editado'] = mdate("%Y-%m-%d %H:%i:%s");


        if (isset($_FILES['foto_consultor'])) {
            if (isset($_FILES['foto_consultor']['name'])) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['file_name'] = utf8_decode($_FILES['foto_consultor']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_consultor')) {
                    $foto = $this->upload->data();
                    $data['foto_consultor'] = utf8_encode($foto['file_name']);
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            }

            if (isset($_FILES['foto_treinamento']['name'])) {
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['file_name'] = utf8_decode($_FILES['foto_treinamento']['name']);

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto_treinamento')) {
                    $foto = $this->upload->data();
                    $data['foto_treinamento'] = utf8_encode($foto['file_name']);
                } else {
                    exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
                }
            }
        }

        # Validação
        if ($data['nome'] == '') {
            $name = ucfirst(str_replace('-', ' ', $data['nome']));
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "' . $name . '" não pode ficar em branco')));
        }

        //Caso for administrador, verifica o usuário
        if ($this->session->userdata('tipo') == 'administrador') {
            # Validação usuario
            if ($data['id_empresa'] > 0) {
                $usuario = $this->db->query('SELECT tipo FROM usuarios WHERE id = ?', $data['id_empresa']);

                if ($usuario->num_rows() < 1) {
                    exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo usuário não pode ficar em branco')));
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo usuário não pode ficar em branco')));
            }
        }

        if ($this->db->where('id', $data['id'])->update('cursos', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Curso editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('ead/cursos')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar curso, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function ajax_delete()
    {
        if (!in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))) {
            redirect(site_url('home'));
        }
        $id = $this->input->post('id');

        # Testa a exclusão do curso antes da exclusão dos arquivos nas páginas do mesmo
        $this->db->trans_start(true);
        $delete = "DELETE FROM cursos WHERE id = {$id}";
        if ($this->session->userdata('tipo') == 'empresa') {
            $delete .= " AND id_empresa = {$this->session->userdata('id')}";
        }
        $this->db->query($delete);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Não foi possível excluir o curso')));
        }
        $this->db->trans_off();

        $sql = "(SELECT 'arquivos/pdf/' AS dir, 
                        pdf AS arquivo
                 FROM cursos_paginas
                 WHERE id_curso = {$id} AND 
                       CHAR_LENGTH(pdf) > 0
                 GROUP BY pdf
                 HAVING COUNT(pdf) = 1)
                UNION
                (SELECT 'arquivos/media/' AS dir, 
                        audio AS arquivo
                 FROM cursos_paginas
                 WHERE id_curso = {$id} AND 
                       CHAR_LENGTH(audio) > 0
                 GROUP BY audio
                 HAVING COUNT(audio) = 1)
                UNION
                (SELECT 'arquivos/media/' AS dir, 
                        video AS arquivo
                 FROM cursos_paginas
                 WHERE id_curso = {$id} AND 
                       CHAR_LENGTH(video) > 0
                 GROUP BY video
                 HAVING COUNT(video) = 1)";
        $rows = $this->db->query($sql)->result();
        foreach ($rows as $row) {
            if (file_exists($row->dir . $row->arquivo)) {
                unlink($row->dir . $row->arquivo);
            }
        }

        $this->db->query($delete);

        echo json_encode(array('status' => true));
    }

    public function duplicar()
    {
        $id = $this->input->post('id');

        $data = $this->db->get_where('cursos', array('id' => $id))->row_array();

        if ($data) {

            # Copiar curso
            $data['nome'] .= ' (cópia)';
            $data['id_copia'] = $data['id'];
            unset($data['id']);
            $this->db->insert('cursos', $data);
            $novo_id = $this->db->insert_id();

            # Copiar páginas

            $colunas_paginas = $this->db->list_fields('cursos_paginas');
            $paginas = array_combine($colunas_paginas, $colunas_paginas);
            $paginas['id_curso'] = $novo_id . ' AS id_curso';
            $paginas['id_copia'] = 'id AS id_copia';
            unset($paginas['id']);

            $this->db->select(implode(', ', $paginas), false);
            $this->db->where('id_curso', $id);
            $rows = $this->db->get('cursos_paginas')->result_array();

            if ($rows) {
                $this->db->insert_batch('cursos_paginas', $rows);

                # Copiar registros de questões

                $colunas_questoes = $this->db->list_fields('cursos_questoes');
                $questoes = array_combine($colunas_questoes, $colunas_questoes);
                foreach ($questoes as $questao) {
                    $questao = 'a.' . $questao;
                }
                $questoes['id_pagina'] = 'b.id AS id_pagina';
                $questoes['id_copia'] = 'a.id AS id_copia';
                unset($questoes['id']);

                $this->db->select(implode(', ', $questoes), false);
                $this->db->join('cursos_paginas b', 'b.id_copia = a.id_pagina');
                $this->db->where('b.id_curso', $novo_id);
                $rows2 = $this->db->get('cursos_questoes a')->result_array();

                if ($rows2) {
                    $this->db->insert_batch('cursos_questoes', $rows2);

                    # Copiar registros de alternativas

                    $this->db->select('b.id AS id_questao, a.alternativa, a.peso, a.id AS id_copia');
                    $this->db->join('cursos_questoes b', 'b.id_copia = a.id_questao');
                    $this->db->join('cursos_paginas c', 'c.id_copia = b.id_pagina');
                    $this->db->where('c.id_curso', $novo_id);
                    $rows3 = $this->db->get('cursos_alternativas a')->result_array();
                    if ($rows3) {
                        $this->db->insert_batch('cursos_alternativas', $rows3);
                    }
                }
            }

            echo json_encode("sucesso");
        } else {
            echo json_encode("O curso não pode ser copiado");
        }
    }

    public function status($status = null, $id = null)
    {
        $row = $this->db->get_where('cursos', array('id' => $id))->row();

        if ($row && $status >= 0) {
            # Verifica a permissão do usuário
            if ($row->id_empresa == $this->session->userdata('id') || $this->session->userdata('tipo') == 'administrador') {
                # Alterar o status no banco
                $this->db->query("UPDATE cursos SET status = ? WHERE id = ?", array($status, $id));
            } else {
                exit(json_encode("Você não possui permissão para essa alteração.\nPor favor, entre em contato com o administrador do sistema"));
            }
        } else {
            exit(json_encode("Erro ao localizar o treinamento.\nPor favor, entre em contato com o administrador do sistema"));
        }

        echo json_encode('sucesso');
    }

    public function disponiveis()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario"))) {
            redirect(site_url('home'));
        }

        $sql_categorias = "SELECT DISTINCT(a.categoria)
                           FROM cursos a 
                           INNER JOIN usuarios b ON 
                                      b.id = a.id_empresa
                           LEFT JOIN cursos_usuarios c ON 
                                     c.id_curso = a.id AND 
                                     c.id_usuario = {$this->session->userdata('id')}
                           WHERE a.status = 1 AND 
                                 (a.publico = 1 OR b.tipo = 'administrador') AND 
                                 c.id IS NULL AND 
                                 CHAR_LENGTH(a.categoria) > 0
                           ORDER BY a.categoria";
        $data['categorias'] = $this->db->query($sql_categorias)->result();

        $sql_areas_conhecimento = "SELECT DISTINCT(a.area_conhecimento)
                                   FROM cursos a 
                                   INNER JOIN usuarios b ON 
                                              b.id = a.id_empresa
                                   LEFT JOIN cursos_usuarios c ON 
                                             c.id_curso = a.id AND 
                                             c.id_usuario = {$this->session->userdata('id')}
                                   WHERE a.status = 1 AND 
                                         (a.publico = 1 OR b.tipo = 'administrador') AND 
                                         c.id IS NULL AND 
                                         CHAR_LENGTH(a.area_conhecimento) > 0
                                   ORDER BY a.area_conhecimento";
        $data['areas_conhecimento'] = $this->db->query($sql_areas_conhecimento)->result();

        $this->load->view('ead/solicitarcursos', $data);
    }

    public function ajax_disponiveis()
    {
        if (!in_array($this->session->userdata('tipo'), array("funcionario"))) {
            redirect(site_url('home'));
        }

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.categoria, 
                       s.area_conhecimento
                FROM (SELECT a.id, 
                             a.nome, 
                             a.categoria, 
                             a.area_conhecimento
                      FROM cursos a 
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_empresa
                      LEFT JOIN cursos_usuarios c ON 
                                c.id_curso = a.id AND 
                                c.id_usuario = {$this->session->userdata('id')}
                      WHERE a.status = 1  AND 
                            (a.publico = 1 OR b.tipo = 'administrador') AND 
                            c.id IS NULL 
                      ORDER BY a.id) s WHERE 1";
        $data['total'] = $this->db->query($sql)->num_rows();

        $categoria = $this->input->post('categoria');
        $area_conhecimento = $this->input->post('area_conhecimento');
        $busca = $this->input->post('busca');
        if ($categoria) {
            $sql .= " AND s.categoria = '{$categoria}'";
        }
        if ($area_conhecimento) {
            $sql .= " AND s.area_conhecimento = '{$area_conhecimento}'";
        }
        if ($busca) {
            $sql .= " AND s.nome LIKE '%{$busca}%'";
        }

        $this->load->library('pagination');

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'Primeira';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Última';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = site_url('ead/cursos/ajax_disponiveis');
        $config['total_rows'] = $this->db->query($sql)->num_rows();
        $config['per_page'] = 20;
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $data['busca'] = "busca={$busca}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $sql .= " LIMIT {$this->uri->rsegment(3, 0)}, {$config['per_page']}";
        $data['query'] = $this->db->query($sql)->result();

        $this->load->view('ead/getsolicitarcursos', $data);
    }

    public function detalhes($id = null) #solicitação/detalhes
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $curso = $this->db->get_where("cursos", array('id' => $id))->row();

        if (empty($curso)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O id do curso não foi enviado')));
        } else {

            $genericas = null;
            $especificas = null;
            $comportamentais = null;
            $imagem = "https://www.placehold.it/300x200/EFEFEF/AAAAAA&amp;text=sem+imagem";
            if (cursos::url_exists(base_url('imagens/usuarios/' . $curso->foto_treinamento))) {
                $imagem = base_url('imagens/usuarios/' . $curso->foto_treinamento);
            }
            $imagem_consultor = "https://www.placehold.it/300x200/EFEFEF/AAAAAA&amp;text=sem+imagem";
            if (cursos::url_exists(base_url('imagens/usuarios/' . $curso->foto_consultor))) {
                $imagem_consultor = base_url('imagens/usuarios/' . $curso->foto_consultor);
            }

            $curso->objetivos = nl2br($curso->objetivos);
            $curso->descricao = nl2br($curso->descricao);
            $curso->curriculo = nl2br($curso->curriculo);

            $cp_genericas = explode(',', $curso->competencias_genericas);
            foreach ($cp_genericas as $valor) {
                $genericas .= "<p>$valor</p>";
            }

            $cp_especificas = explode(',', $curso->competencias_especificas);
            foreach ($cp_especificas as $valor) {
                $especificas .= "<p>$valor</p>";
            }

            $cp_comportamentais = explode(',', $curso->competencias_comportamentais);
            foreach ($cp_comportamentais as $valor) {
                $comportamentais .= "<p>$valor</p>";
            }

            $html = "
                <div class='row'>
                    <div class='col-md-9' style='border-right: 1px solid #EEE;'>
                        <div class='row'>
                            <div class='col-md-3'>
                                <img src='{$imagem}' class='img-responsive img-thumbnail'>
                            </div>
                            <div class='col-md-8'>
                                <h3>$curso->nome</h3>
                                <p>
                                    <p style='font-weight: bolder;'>Objetivos</p>
                                $curso->objetivos
                                </p>
                            </div>
                          </div>
                          <hr />
                          <div class='row'>
                            <h4 style='margin-left: 1%;'>Competências</h4>
                            <div class='col-md-4'>
                                <p>
                                    <p style='font-weight: bolder;'>Técnicas Genéricas</p>
                                    $genericas
                                    <br />
                                </p>
                            </div>
                            <div class='col-md-4'>
                                <p>
                                    <p style='font-weight: bolder;'>Técnicas Específicas</p>
                                    $especificas
                                    <br />
                                </p>
                            </div>
                            <div class='col-md-4'>
                                <p>
                                    <p style='font-weight: bolder;'>Comportamentais</p>
                                    $comportamentais
                                    <br />
                                </p>
                            </div>
                          </div>
                          <hr />
                          <div class='row'>
                            <div class='col-md-6' style='border-right: 1px solid #EEE;'>
                                <p>
                                    <p style='font-weight: bolder;'>Pré-Requisitos</p>
                                    $curso->pre_requisitos
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <p>
                                    <p style='font-weight: bolder;'>Carga Horária (Horas)</p>
                                    $curso->horas_duracao
                                </p>
                            </div>
                          </div>
                          <hr style='margin-bottom: 0;'/>
                          <div class='row'>
                            <div class='col-md-9'>
                                <p>
                                    <p style='font-weight: bolder;'>Programa do Treinamento</p>
                                    $curso->descricao
                                </p>
                            </div>
                          </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='row'>
                            <h4 style='margin-left: 4%;'>Dados do Consultor</h4>
                            <div class='col-md-12'>
                                <img src='{$imagem_consultor}' class='img-responsive img-thumbnail'>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-md-12' style='text-align: justify;'>
                                    <p>
                                        <p style='font-weight: bolder;'>Currículo</p>
                                        $curso->curriculo
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
                ";

            echo json_encode($html);
        }
    }

    public function solicitar()
    {
        $id = $this->input->post('id');

        $this->db->select('a.*, b.tipo', false);
        $this->db->join('usuarios b', 'b.id = a.id_empresa');
        $curso = $this->db->get_where('cursos a', array('a.id' => $id))->row();

        $sucesso = 0;
        $this->load->helper('phpmailer');
        if ($this->session->userdata('tipo') == "empresa") {

            if ($curso->tipo != "administrador") {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }

            $administradores = $this->db->query("SELECT * FROM usuarios WHERE tipo = ?", array('administrador'));
            foreach ($administradores->result() as $row) {

                $nome = $row->nome;
                $email = $row->email;

                $assunto = "LMS - Solicitação de curso";
                $mensagem = "<center>
                <h1>LMS</h1>
                </center>
                <hr />
                <p>Prezado(a) {$nome},</p>
                <p>Foi solicitado um curso, segue abaixo os dados da empresa e do curso solicitado.</p>
                <p><strong>Empresa:</strong> {$this->session->userdata('nome')} - {$this->session->userdata('email')}</p>
                <p><strong>Curso:</strong> {$curso->nome}</p>";

                if (send_email($nome, $email, $assunto, $mensagem)) {
                    $sucesso = 1;
                }
            }
        } else if ($this->session->userdata('tipo') == "funcionario") {
            $usuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->session->userdata('id')))->row(0);
            $empresa = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($usuario->empresa))->row(0);

            if ($curso->tipo != "administrador" && $curso->id_empresa != $usuario->empresa) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
            }

            $nome = $empresa->nome;
            $email = $empresa->email;

            $assunto = "LMS - Solicitação de curso";
            $mensagem = "<center>
                <h1>LMS</h1>
                </center>
                <hr />
                <p>Prezado(a) {$nome},</p>
                <p>Foi solicitado um curso, segue abaixo os dados do funcionário e do curso solicitado.</p>
                <p><strong>Funcionário:</strong> {$this->session->userdata('nome')} - {$this->session->userdata('email')}</p>
                <p><strong>Curso:</strong> {$curso->nome}</p>";

            if (send_email($nome, $email, $assunto, $mensagem)) {
                $sucesso = 1;
            }
        }

        if ($sucesso) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Solicitação de <strong>' . $curso->nome . '</strong> realizada com sucesso ao administrador da plataforma. <br>Em breve entraremos em contato para a liberação do treinamento.'));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao solicitar treinamento, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function preview()
    {
        $this->db->select('a.id, a.nome, MIN(b.ordem) AS primeira_pagina, MAX(b.ordem) AS ultima_pagina');
        $this->db->join('cursos_paginas b', 'b.id_curso = a.id');
        $this->db->where('a.id', $this->uri->rsegment(3, 0));
        $this->db->where('a.id_empresa', $this->session->userdata('id'));
        $curso = $this->db->get('cursos a')->row();

        if (empty($curso)) {
            redirect(site_url('ead/treinamento'));
            exit;
        }

        $this->db->where('a.id_curso', $curso->id);
        $this->db->where('a.ordem', $this->uri->rsegment(4, 0));
        $pagina_atual = $this->db->get('cursos_paginas a')->row();

        if ($this->input->server('SERVER_PORT') == 443 && preg_match('/<iframe>|<\/iframe>/i', $pagina_atual->conteudo)) {
            $qtdeIframes = substr_count($pagina_atual->conteudo, '<iframe>');
            $qtdeHTTPS = substr_count($pagina_atual->conteudo, 'https://');
            if ($qtdeIframes != $qtdeHTTPS) {
                header("Location: " . str_replace('https', 'http', current_url()));
            }
        }

        switch ($pagina_atual->modulo) {
            case 'quiz':
            case 'atividades':
                $this->db->select("a.*, null AS alternativas", false);
                $this->db->join('cursos_paginas b', 'b.id = a.id_pagina');
                if ($pagina_atual->aleatorizacao == 'T' || $pagina_atual->aleatorizacao == 'P') {
                    $this->db->order_by('rand()');
                } else {
                    $this->db->order_by('a.id', 'asc');
                }
                $perguntas = $this->db->get_where('cursos_questoes a', array('b.id' => $pagina_atual->id))->result();

                foreach ($perguntas as $pergunta) {
                    $this->db->select('a.*');
                    $this->db->join('cursos_questoes b', 'b.id = a.id_questao');
                    $this->db->where('b.id', $pergunta->id);
                    if ($pagina_atual->aleatorizacao == 'T' || $pagina_atual->aleatorizacao == 'A') {
                        $this->db->order_by('rand()');
                    } else {
                        $this->db->order_by('a.id', 'asc');
                    }
                    $pergunta->alternativas = $this->db->get('cursos_alternativas a')->result();
                }

                $data['perguntas'] = $perguntas;
                break;
            case 'url':
                $data['url_final'] = $pagina_atual->url;

                switch ($pagina_atual->url) {
                    # Youtube novo
                    case strpos($pagina_atual->url, 'youtube') > 0:
                        $url_video = explode('?v=', $pagina_atual->url);
                        $data['url_final'] = "https://www.youtube.com/embed/" . $url_video[1] . "?enablejsapi=1";
                        break;
                    # Vimeo
                    case strpos($pagina_atual->url, 'vimeo') > 0:
                        $url_video = explode('/', $pagina_atual->url);
                        $data['url_final'] = "https://player.vimeo.com/video/" . $url_video[3];
                        break;
                }
                break;
            case 'mapas':
            case 'simuladores':
            case 'aula-digital':
            case 'jogos':
            case 'livros-digitais':
            case 'infograficos':
            case 'experimentos':
            case 'softwares':
            case 'audios':
            case 'multimidia':
            case 'links-externos':
                $data['biblioteca'] = $this->db->get_where('biblioteca', array('id' => $pagina_atual->biblioteca))->row();
        }

        $data['curso'] = $curso;
        $data['paginaatual'] = $pagina_atual;
        $data['andamento'] = 100;

        $this->db->select('id, ordem, titulo');
        $this->db->where('id_curso', $curso->id);
        $data['paginas'] = $this->db->get('cursos_paginas')->result();

        $this->load->view('ead/preview_curso', $data);
    }

}
