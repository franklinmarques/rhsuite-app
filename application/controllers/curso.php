<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Curso extends MY_Controller
{

    public function index()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
            redirect(site_url('home'));
        }

        $data['categorias'] = $this->db->query("SELECT distinct(categoria) FROM cursos WHERE CHAR_LENGTH(categoria) > 0 GROUP BY categoria");
        $data['areas_conhecimento'] = $this->db->query("SELECT area_conhecimento FROM cursos WHERE CHAR_LENGTH(area_conhecimento) > 0 GROUP BY area_conhecimento");

        $this->load->view('cursos', $data);
    }

    public function novo()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(site_url('home'));

        $this->load->view('novocurso');
    }

    public function getcursos()
    {
        if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa")))
            redirect(site_url('home'));

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
            $query_busca = " AND c.curso LIKE '" . $busca . "%' ";
        }

        if ($this->session->userdata('tipo') == "administrador") {
            $qryWHERE = "WHERE c.usuario = ? $query_categoria $query_areaConhecimento $query_busca";
            $dataWHERE[] = $this->session->userdata('id');

            # Definir os filtros
            if (!empty($query_categoria)) {
                $dataWHERE[] = $categoria;
            }
            if (!empty($query_areaConhecimento)) {
                $dataWHERE[] = $area_conhecimento;
            }
        } else {
            $qryWHERE = "(SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario WHERE c.publico = 1 $query_categoria $query_areaConhecimento $query_busca) UNION (SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario WHERE c.status = 1 AND c.publico = 1 $query_categoria $query_areaConhecimento $query_busca) UNION (SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario WHERE c.tipo = 'administrador'AND c.status = 1 $query_categoria $query_areaConhecimento $query_busca)";
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

        $config['base_url'] = site_url('home/getcursos');

        if ($this->session->userdata('tipo') != 'administrador') {
            $config['total_rows'] = $this->db->query("(SELECT c.* FROM cursos c WHERE c.usuario = ? $query_categoria $query_areaConhecimento $query_busca) UNION {$qryWHERE}", $dataWHERE)->num_rows();
        } else {
            $config['total_rows'] = $this->db->query("SELECT c.* FROM cursos c WHERE c.usuario = ?", $dataWHERE)->num_rows();
        }

        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $dataWHERE[] = $this->uri->rsegment(3, 0);
        $dataWHERE[] = $config['per_page'];
        $data['total'] = $config['total_rows'];
        $data['busca'] = "busca={$query}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";

        if ($this->session->userdata('tipo') != 'administrador') {
            $data['query'] = $this->db->query("(SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario WHERE c.usuario = ? $query_categoria $query_areaConhecimento $query_busca) UNION {$qryWHERE} ORDER BY usuario = ? DESC, curso ASC LIMIT ?,?", $dataWHERE);
        } else {
            $data['query'] = $this->db->query("SELECT c.* FROM cursos c INNER JOIN usuarios u ON u.id = c.usuario {$qryWHERE} ORDER BY c.id ASC LIMIT ?,?", $dataWHERE);
        }

        $this->load->view('getcursos', $data);
    }

    static function url_exists($url)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($code == 200); // verifica se recebe "status OK"
    }

    public function detalhesCurso_json($id = null)
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        //Variáveis
        $html = null;
        $genericas = null;
        $especificas = null;
        $comportamentais = null;
        $data['id'] = (int) $id;
        $imagem = "http://www.placehold.it/300x200/EFEFEF/AAAAAA&amp;text=sem+imagem";
        $imagem_consultor = $imagem;

        $cursos = $this->db->query("SELECT * FROM cursos WHERE id = ?", $data['id']);

        # Validação
        if ($data['id'] < 1 || $cursos->num_rows() < 1) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O id do curso não foi enviado')));
        } else {
            foreach ($cursos->result() as $row) {

                $row->objetivos = nl2br($row->objetivos);
                $row->descricao = nl2br($row->descricao);
                $row->curriculo = nl2br($row->curriculo);

                // Verifica imagem
                if (curso::url_exists(base_url('imagens/usuarios/' . $row->foto_treinamento))) {
                    $imagem = base_url('imagens/usuarios/' . $row->foto_treinamento);
                }

                // Verifica imagem
                if (curso::url_exists(base_url('imagens/usuarios/' . $row->foto_consultor))) {
                    $imagem_consultor = base_url('imagens/usuarios/' . $row->foto_consultor);
                }

                // Separa as competências por vígula
                #Genéricas
                $cp_genericas = explode(',', $row->competencias_genericas);

                foreach ($cp_genericas as $valor) {
                    $genericas .= "<p>$valor</p>";
                }
                #Específicas
                $cp_especificas = explode(',', $row->competencias_especificas);

                foreach ($cp_especificas as $valor) {
                    $especificas .= "<p>$valor</p>";
                }
                #Comportamentais
                $cp_comportamentais = explode(',', $row->competencias_comportamentais);

                foreach ($cp_comportamentais as $valor) {
                    $comportamentais .= "<p>$valor</p>";
                }

                //Adiciona a variável de visualização
                $html .= "
                <div class='row'>
                    <div class='col-md-9' style='border-right: 1px solid #EEE;'>
                        <div class='row'>
                            <div class='col-md-3'>
                                <img src='{$imagem}' class='img-responsive img-thumbnail'>
                            </div>
                            <div class='col-md-8'>
                                <h3>$row->curso</h3>
                                <p>
                                    <p style='font-weight: bolder;'>Objetivos</p>
                                    $row->objetivos
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
                                    $row->pre_requisitos
                                </p>
                            </div>
                            <div class='col-md-3'>
                                <p>
                                    <p style='font-weight: bolder;'>Carga Horária (Horas)</p>
                                    $row->duracao
                                </p>
                            </div>
                          </div>
                          <hr style='margin-bottom: 0;'/>
                          <div class='row'>
                            <div class='col-md-9'>
                                <p>
                                    <p style='font-weight: bolder;'>Programa do Treinamento</p>
                                    $row->descricao
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
                                        $row->curriculo
                                    </p>
                            </div>
                        </div>
                    </div>
                </div>
                ";
            }

            //Visualiza os dados
            echo json_encode($html);
        }
    }

    public function finalizaPagina_json($curso = null, $pagina = null)
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        //Variáveis
        $data['status'] = (int) 1;
        $data['curso'] = (int) $curso;
        $data['pagina'] = (int) $pagina;
        $data['dataconclusao'] = mdate("%Y-%m-%d %H:%i:%s");

        //Variável de retorno
        $total = 0;
        $proxima = '';

        $cursos = $this->db->query("SELECT p.curso, p.id AS pagina, c.usuario,
                                    (SELECT COUNT(*) FROM usuariospaginas pg
                                      WHERE pg.curso = c.curso
                                      AND pg.pagina = p.id
                                      AND pg.usuario = pg.usuario
                                      AND status = 0
                                    ) AS total,
                                    (SELECT COUNT(*) FROM usuariospaginas pg
                                      WHERE pg.curso = c.curso
                                      AND pg.pagina = p.id
                                      AND pg.usuario = pg.usuario
                                    ) AS total_paginas_usuarios,
                                    (SELECT COUNT(*) FROM usuariospaginas pg
                                      WHERE pg.curso = p.curso
                                      AND pg.usuario = pg.usuario
                                    ) AS andamento,
                                    (SELECT COUNT(*) FROM paginas pgs
                                      WHERE pgs.curso = p.curso
                                    ) AS total_paginas
                                    FROM paginas p
                                    INNER JOIN usuarioscursos c ON c.curso = p.curso AND c.usuario = ?
                                    WHERE p.curso = ? AND p.id = ?", array($this->session->userdata['id'], $data['curso'], $data['pagina']));

        # Validação
        if ($data['curso'] < 1 || $data['pagina'] < 1 || $cursos->num_rows() < 1) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O id não foi enviado')));
        } else {
            foreach ($cursos->result() as $row) {
                foreach ($row as $key => $value) {
                    $data[$key] = $value;
                }
            }

            $total = $data['pagina'];
            
            if ($data['total_paginas_usuarios'] > 0) {
                unset($data['total']);
                unset($data['total_paginas']);
                unset($data['andamento']);
                unset($data['total_paginas_usuarios']);

                //Altera os dados
                $this->db->update('usuariospaginas', $data, array('pagina' => $data['pagina'], 'curso' => $data['curso'], 'usuario' => $this->session->userdata['id']));

                //Verificar porcentagem
                $cursos = $this->db->query("SELECT COUNT(*) AS total_paginas,
                                            (SELECT COUNT(*) FROM usuariospaginas pg
                                              WHERE pg.curso = p.curso
                                              AND pg.usuario = c.usuario
                                              AND pg.status = 1
                                            ) AS andamento,
                                            (SELECT min(pg.status) FROM usuariospaginas pg
                                              WHERE pg.curso = p.curso
                                              AND pg.usuario = c.usuario
                                              AND pg.status = 0 
                                              AND p.ordem > 0
                                            ) AS pendente
                                            FROM paginas p
                                            INNER JOIN usuarioscursos c ON c.curso = p.curso AND c.usuario = ?
                                            WHERE p.curso = ?", array($this->session->userdata['id'], $data['curso']));

                if ($cursos->num_rows() > 0) {
                    foreach ($cursos->result() as $row) {
                        foreach ($row as $key => $value) {
                            $data[$key] = $value;
                        }
                    }

                    $total = ((int) $data['andamento'] / (int) ($data['total_paginas'] - 1)) * 100;
                    $proxima = $data['pendente'] ? $data['pendente'] : $data['andamento'] + 1;
                }
            }
            $result['total'] = $total;
            $result['proxima'] = $proxima;
        }

        echo json_encode($result);
    }

    public function status_treinamento($funcionario = 0, $id = null)
    {
        $data['row'] = $this->db->query("SELECT p.*, upg.datacadastro AS cadastro, upg.dataconclusao AS finalizacao,
                                          (SELECT COUNT(*) FROM usuariospaginas pg
                                            WHERE pg.curso = c.id
                                            AND pg.pagina = p.id
                                            AND pg.usuario = '$funcionario'
                                          ) AS total,
                                          (SELECT COUNT(*) FROM usuariospaginas up
                                            WHERE up.curso = c.id
                                            AND up.usuario = '$funcionario'
                                            AND up.status = 1
                                          ) AS andamento,
                                          (SELECT COUNT(*) FROM usuariospaginas up
                                            WHERE up.curso = c.id
                                            AND up.pagina = p.id
                                            AND up.usuario = '$funcionario'
                                            AND up.status = 1
                                          ) AS conclusao
                                         FROM paginas p
                                         INNER JOIN cursos c ON 
                                                    c.id = p.curso
                                         LEFT JOIN usuarioscursos uc ON 
                                                    uc.curso = c.id 
                                         LEFT JOIN usuariospaginas upg  ON 
                                                    upg.pagina = p.id AND upg.usuario = uc.usuario AND upg.curso = uc.curso
                                         WHERE p.curso = ? AND
                                               uc.usuario = ?
                                         ORDER BY p.ordem ASC", array($id, $funcionario));

        $data['atividades'] = $this->db->query("SELECT ua.status, ua.pagina
                                                FROM usuariosatividades ua
                                                WHERE ua.curso = ? AND ua.usuario = ?
                                               ", array($this->uri->rsegment(4), $this->uri->rsegment(3)));

        $this->load->view('statustreinamento', $data);
    }

    public function statusCurso($status = null, $id = null)
    {
        $data['row'] = $this->db->query("SELECT c.*
                                         FROM cursos c
                                         WHERE c.id = ? LIMIT 1
                                         ", $id);

        if ($data['row']->num_rows() > 0 && $status >= 0) {
            foreach ($data['row']->result() as $row) {
                # Verifica a permissão do usuário
                if ($row->usuario == $this->session->userdata('id') || $this->session->userdata('tipo') == 'administrador') {
                    # Alterar o status no banco
                    $this->db->query("UPDATE cursos SET status = ? WHERE id = ?
                                 ", array($status, $id));
                } else {
                    exit(json_encode("Você não possui permissão para essa alteração.\nPor favor, entre em contato com o administrador do sistema"));
                }
            }
        } else {
            exit(json_encode("Erro ao localizar o treinamento.\nPor favor, entre em contato com o administrador do sistema"));
        }

        exit(json_encode('sucesso'));
    }

    public function getCategorias()
    {
        $a_json = array();

        # Verifica se GET está vazio
        if (!empty($_GET['termo'])) {

            $categoria = null;
            @$categoria = ($_GET['termo']);

            $query = $this->db->query("(SELECT categoria FROM cursos WHERE categoria LIKE '$categoria%' AND categoria IS NOT NULL GROUP BY categoria) UNION (SELECT categoria FROM categoriascurso) ORDER BY categoria");
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    array_push($a_json, $row['categoria']);
                }
            }
        }

        echo json_encode($a_json);
    }

    public function getAreaConhecimento()
    {
        $a_json = array();

        # Verifica se GET está vazio
        if (!empty($_GET['termo'])) {

            $area_conhecimento = null;
            @$area_conhecimento = ($_GET['termo']);

            $query = $this->db->query("(SELECT area_conhecimento FROM cursos WHERE area_conhecimento LIKE '$area_conhecimento%' AND area_conhecimento IS NOT NULL GROUP BY area_conhecimento) UNION (SELECT area_conhecimento FROM areaconhecimento) ORDER BY area_conhecimento");
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    array_push($a_json, $row['area_conhecimento']);
                }
            }
        }

        echo json_encode($a_json);
    }

}
