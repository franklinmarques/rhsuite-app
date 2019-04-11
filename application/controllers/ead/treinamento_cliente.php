<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Treinamento_cliente extends MY_Controller
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
        $data['categorias'] = $this->db->query("SELECT distinct(a.categoria) FROM cursos a INNER JOIN cursos_clientes_treinamentos b ON b.id_curso = a.id INNER JOIN cursos_clientes c ON c.id = b.id_usuario WHERE c.email = '{$row->email}' AND CHAR_LENGTH(a.categoria) > 0");
        $data['areas_conhecimento'] = $this->db->query("SELECT distinct(a.area_conhecimento) FROM cursos a INNER JOIN cursos_clientes_treinamentos b ON b.id_curso = a.id INNER JOIN cursos_clientes c ON c.id = b.id_usuario WHERE c.email = '{$row->email}' AND CHAR_LENGTH(a.area_conhecimento) > 0");
        $this->load->view('ead/treinamento_cliente', $data);
    }


    public function ajax_list()
    {
        $email = $this->session->userdata('email');
        $limit = $this->uri->rsegment(3, 0);
        $view = 'ead/getmeuscursos_cliente';
        $redirect = site_url('home');

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.categoria, 
                       s.area_conhecimento, 
                       s.data_inicio, 
                       s.data_maxima,                        
                       s.nota_aprovacao, 
                       s.resultado 
                FROM (SELECT a.id,
                             c.nome, 
                             c.categoria, 
                             c.area_conhecimento, 
                             DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio, 
                             DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima, 
                             a.nota_aprovacao, 
                             ROUND(SUM(j.peso) * 100 / SUM(g.peso), 2) AS resultado
                      FROM cursos_clientes_treinamentos a 
                      INNER JOIN cursos_clientes b ON 
                                 b.id = a.id_usuario 
                      INNER JOIN cursos c ON c.id = a.id_curso 
                      INNER JOIN cursos_paginas d ON 
                                 d.id_curso = c.id 
                      LEFT JOIN cursos_questoes e ON
                                e.id_pagina = d.id AND 
                                d.modulo = 'atividades' 
                      LEFT JOIN biblioteca_questoes f ON 
                                f.id = e.id_biblioteca AND 
                                f.tipo = 1
                      LEFT JOIN biblioteca_alternativas g ON 
                                g.id_questao = f.id AND 
                                g.peso > 0 
                      LEFT JOIN cursos_acessos h ON 
                                h.id_curso_usuario = a.id AND 
                                h.id_pagina = e.id_pagina AND 
                                h.data_finalizacao IS NOT NULL
                      LEFT JOIN cursos_resultado i ON 
                                i.id_acesso = h.id AND 
                                i.id_questao = f.id AND 
                                i.id_alternativa = g.id
                      LEFT JOIN biblioteca_alternativas j ON 
                                j.id = i.id_alternativa 
                      WHERE b.email = '{$email}' 
                            GROUP BY a.id 
                            ORDER by a.id desc) s WHERE 1";
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

        $config['base_url'] = site_url('ead/treinamento_cliente/ajax_list');
        $config['total_rows'] = $this->db->query($sql)->num_rows();
        $config['per_page'] = 20;
        $config['uri_segment'] = $this->session->userdata('tipo') == 'empresa' ? 4 : 3;

        $this->pagination->initialize($config);

        $data['busca'] = "busca={$busca}&{$this->security->get_csrf_token_name()}={$this->security->get_csrf_hash()}";
        $sql .= " LIMIT {$limit}, {$config['per_page']}";
        $data['query'] = $this->db->query($sql)->result();

        $this->load->view($view, $data);
    }


    public function acessar()
    {
        $sql = "SELECT s.id, 
                       s.nome, 
                       s.id_curso_usuario, 
                       s.progressao_linear,
                       MIN(s.ordem) AS pagina_inicial,
                       MAX(s.ordem) AS pagina_final,
                       MAX(s.ultima_pagina_acessada) AS ultima_pagina_acessada,
                       MAX(s.ultima_pagina_finalizada) AS ultima_pagina_finalizada,                  
                       MIN(s.acesso_pendente) AS acesso_pendente,
                       MIN(s.finalizacao_pendente) AS finalizacao_pendente
                FROM (SELECT a.id, 
                             a.nome, 
                             c.id AS id_curso_usuario, 
                             a.progressao_linear,
                             b.ordem,
                             CASE WHEN d.data_acesso = (SELECT MAX(e.data_acesso) 
                                                        FROM cursos_acessos e 
                                                        WHERE e.id_curso_usuario = {$this->uri->rsegment(3, 0)}) 
                                  THEN b.ordem 
                                  ELSE NULL END as ultima_pagina_acessada, 
                             CASE WHEN d.data_acesso IS NULL
                                  THEN b.ordem END AS acesso_pendente,
                             CASE WHEN d.data_finalizacao = (SELECT MAX(f.data_finalizacao) 
                                                             FROM cursos_acessos f 
                                                             WHERE f.id_curso_usuario = {$this->uri->rsegment(3, 0)}) 
                                  THEN b.ordem 
                                  ELSE NULL END as ultima_pagina_finalizada,
                             CASE WHEN d.data_finalizacao IS NULL
                                  THEN b.ordem END AS finalizacao_pendente
                      FROM cursos a 
                      INNER JOIN cursos_paginas b ON 
                                 b.id_curso = a.id 
                      INNER JOIN cursos_clientes_treinamentos c ON 
                                 c.id_curso = a.id 
                      INNER JOIN cursos_clientes c2 ON 
                                 c2.id = c.id_usuario
                      LEFT JOIN cursos_clientes_acessos d ON 
                                d.id_curso_usuario = c.id AND 
                                d.id_pagina = b.id 
                      WHERE c.id = {$this->uri->rsegment(3, 0)} AND 
                            c2.email = '{$this->session->userdata('email')}') s";
        $curso = $this->db->query($sql)->row();

        if (empty($curso)) {
            redirect(site_url('ead/treinamento'));
            exit;
        }

        if ($curso->ultima_pagina_finalizada === null and $curso->ultima_pagina_acessada === null) {
            $proxima_pagina = $curso->pagina_inicial;
        } else {
            $this->db->select('ordem');
            $this->db->where('id_curso', $curso->id);
            if ($curso->ultima_pagina_finalizada === null) {
                $this->db->where('ordem >', $curso->ultima_pagina_acessada);
            } else {
                $this->db->where('ordem >', $curso->ultima_pagina_finalizada);
            }
            $this->db->limit(1);
            $ultima_pagina = $this->db->get('cursos_paginas')->row();

            if ($ultima_pagina) {
                $proxima_pagina = $ultima_pagina->ordem;
            } elseif ($curso->acesso_pendente) {
                $proxima_pagina = $curso->acesso_pendente;
            } elseif ($curso->finalizacao_pendente) {
                $proxima_pagina = $curso->finalizacao_pendente;
            } else {
                $proxima_pagina = $curso->pagina_final;
            }
        }

        $this->db->select('a.*, c.id_curso_usuario');
        $this->db->select('(CASE WHEN c.data_acesso IS NOT NULL THEN 1 ELSE 0 END) AS acesso', false);
        $this->db->select('(CASE WHEN c.data_finalizacao IS NOT NULL THEN 1 ELSE 0 END) AS status', false);
        $this->db->select('(SELECT MAX(e.ordem) FROM cursos_paginas e WHERE e.id_curso = a.id_curso AND e.ordem < a.ordem) AS anterior', false);
        $this->db->select('(SELECT MIN(e.ordem) FROM cursos_paginas e WHERE e.id_curso = a.id_curso AND e.ordem > a.ordem) AS proxima', false);
        $this->db->select('COUNT(d.id) AS resultado', false);
        $this->db->join('cursos_clientes_treinamentos b', 'b.id_curso = a.id_curso');
        $this->db->join('cursos_clientes b2', 'b2.id = b.id_usuario');
        $this->db->join('cursos_clientes_acessos c', 'c.id_curso_usuario = b.id AND c.id_pagina = a.id', 'left');
        $this->db->join('cursos_clientes_resultado d', 'd.id_acesso = c.id', 'left');
        $this->db->where('a.id_curso', $curso->id);
        $this->db->where('b2.email', $this->session->userdata('email'));
        $this->db->where('a.ordem', $this->uri->rsegment(4, $proxima_pagina));
        $pagina_atual = $this->db->get('cursos_paginas a')->row();

//        if ($this->input->server('SERVER_PORT') == 443 && preg_match('/<iframe>|<\/iframe>/i', $pagina_atual->conteudo)) {
//            $qtdeIframes = substr_count($pagina_atual->conteudo, '<iframe>');
//            $qtdeHTTPS = substr_count($pagina_atual->conteudo, 'https://');
//            if ($qtdeIframes != $qtdeHTTPS) {
//                header("Location: " . str_replace('https', 'http', current_url()));
//            }
//        }

        switch ($pagina_atual->modulo) {
            case 'quiz':
            case 'atividades':
                $this->db->select("a.*, null AS alternativas, null AS resposta", false);
                $this->db->join('cursos_paginas b', 'b.id = a.id_pagina');
                if ($pagina_atual->aleatorizacao == 'T' || $pagina_atual->aleatorizacao == 'P') {
                    $this->db->order_by('rand()');
                } else {
                    $this->db->order_by('a.id', 'asc');
                }
                $perguntas = $this->db->get_where('cursos_questoes a', array('b.id' => $pagina_atual->id))->result();

                $sql2 = "SELECT c.id_questao, c.id_alternativa, c.resposta 
                         FROM cursos_clientes_acessos a 
                         INNER JOIN cursos_paginas b ON 
                                    b.id = a.id_pagina
                         INNER JOIN cursos_clientes_resultado c ON 
                                    c.id_acesso = a.id
                         WHERE a.id_curso_usuario = '{$pagina_atual->id_curso_usuario}' AND
                               b.ordem = '{$pagina_atual->ordem}'";
                $sqlRespostas = $this->db->query($sql2)->result();
                $resposta = array();
                foreach ($sqlRespostas as $sqlResposta) {
                    $resposta[$sqlResposta->id_questao] = $sqlResposta;
                }

                foreach ($perguntas as $pergunta) {
                    $checked = $resposta[$pergunta->id]->id_alternativa ?? 'NULL';
                    $this->db->select('a.id, a.alternativa, a.peso');
                    $this->db->select("CASE WHEN {$checked} = a.id THEN 1 ELSE 0 END AS checked", false);
                    $this->db->join('cursos_questoes b', 'b.id = a.id_questao');
                    $this->db->where('b.id', $pergunta->id);
                    if ($pagina_atual->aleatorizacao == 'T' || $pagina_atual->aleatorizacao == 'A') {
                        $this->db->order_by('rand()');
                    } else {
                        $this->db->order_by('a.id', 'asc');
                    }

                    $pergunta->alternativas = $this->db->get('cursos_alternativas a')->result();
                    $pergunta->resposta = $resposta[$pergunta->id]->resposta ?? '';
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

        if ($pagina_atual->acesso == 0) {
            $arrAcesso = array(
                'id_curso_usuario' => $curso->id_curso_usuario,
                'id_pagina' => $pagina_atual->id,
                'data_acesso' => date("Y-m-d H:i:s"),
                'data_finalizacao' => $pagina_atual->ordem === '0' ? date("Y-m-d H:i:s") : null
            );
            $this->db->insert('cursos_clientes_acessos', $arrAcesso);
            $curso->id_acesso = $this->db->insert_id();
        } else {
            $arrAcesso = array(
                'data_atualizacao' => date("Y-m-d H:i:s")
            );
            $where = array(
                'id_curso_usuario' => $curso->id_curso_usuario,
                'id_pagina' => $pagina_atual->id
            );
            $this->db->update('cursos_clientes_acessos', $arrAcesso, $where);
        }

        $data['curso'] = $curso;
        $data['paginaatual'] = $pagina_atual;
        $data['andamento'] = 100;

        $this->db->select('a.id, a.ordem, a.titulo');
        $this->db->select('(CASE WHEN c.data_acesso IS NOT NULL THEN 1 ELSE 0 END) AS acesso', false);
        $this->db->select('(CASE WHEN c.data_finalizacao IS NOT NULL THEN 1 ELSE 0 END) AS status', false);
        $this->db->join('cursos_clientes_treinamentos b', 'b.id_curso = a.id_curso');
        $this->db->join('cursos_clientes b2', 'b2.id = b.id_usuario');
        $this->db->join('cursos_clientes_acessos c', 'c.id_curso_usuario = b.id AND c.id_pagina = a.id', 'left');
        $this->db->where('a.id_curso', $curso->id);
        $this->db->where('b2.email', $this->session->userdata('email'));
        $this->db->order_by('a.ordem', 'asc');
        $data['paginas'] = $this->db->get('cursos_paginas a')->result();

        $this->load->view('ead/acessarcurso_cliente', $data);
    }


    public function atualizarTempoEstudo()
    {
        $id_curso = $this->input->post('id_curso');
        $id_pagina = $this->input->post('id_pagina');

        $this->db->select('a.id, a.data_acesso, a.data_atualizacao');
        $this->db->join('cursos_clientes_treinamentos b', 'b.id = a.id_curso_usuario');
        $this->db->join('cursos_clientes b2', 'b2.id = b.id_usuario');
        $this->db->join('cursos_paginas c', 'c.id = a.id_pagina');
        $this->db->where('b.id_curso', $id_curso);
        $this->db->where('a.id_pagina', $id_pagina);
        $this->db->where('b2.email', $this->session->userdata('email'));
        $row = $this->db->get('cursos_clientes_acessos a')->row();

        if ($row) {
            $this->load->helper(array('date'));

            $this->db->set('data_atualizacao', mdate("%Y-%m-%d %H:%i:%s"));
            if ($row->data_atualizacao) {
                $this->db->set('tempo_estudo', "ADDTIME(IFNULL(`tempo_estudo`, 0), TIMEDIFF('" . mdate("%Y-%m-%d %H:%i:%s") . "', '{$row->data_atualizacao}'))", false);
            } else {
                $this->db->set('tempo_estudo', "ADDTIME(IFNULL(`tempo_estudo`, 0), TIMEDIFF('" . mdate("%Y-%m-%d %H:%i:%s") . "', '{$row->data_acesso}'))", false);
            }
            $this->db->where('id', $row->id);
            $status = $this->db->update('cursos_clientes_acessos');
        } else {
            $status = false;
        }

        echo json_encode(array('status' => $status));
    }


    public function avaliar_atividade()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $id_curso = $this->input->post('id_curso');
        $id_pagina = $this->input->post('id_pagina');

        $this->db->select('a.id, c.nota_corte, c.id_pagina_aprovacao, c.id_pagina_reprovacao');
        $this->db->join('cursos_clientes_treinamentos b', 'b.id = a.id_curso_usuario');
        $this->db->join('cursos_clientes b2', 'b2.id = b.id_usuario');
        $this->db->join('cursos_paginas c', 'c.id = a.id_pagina');
        $this->db->where('b.id_curso', $id_curso);
        $this->db->where('a.id_pagina', $id_pagina);
        $this->db->where('b2.email', $this->session->userdata('email'));
        $row = $this->db->get('cursos_clientes_acessos a')->row();
        if (empty($row)) {
            echo json_encode('Erro ao validar o questionário!');
            exit;
        }

        $this->db->where('id_acesso', $row->id);
        $num_rows = $this->db->get('cursos_clientes_resultado')->num_rows();
        if ($num_rows) {
            exit(json_encode('Atividade realizada anteriormente!'));
        }

        $biblioteca = $this->input->post('pergunta');
        $tipo = $this->input->post('tipo');
        $data = array();
        foreach ($biblioteca as $id_questao => $resposta) {
            $this->db->select('a.id as id_questao, b.id AS id_alternativa');
            $this->db->join('cursos_alternativas b', 'b.id_questao = a.id', 'left');
            $this->db->where('a.id', $id_questao);
            $this->db->where('b.id', $tipo[$id_questao] == 2 ? null : $resposta);
            $questoes = $this->db->get('cursos_questoes a')->row();
            if (empty($questoes)) {
                echo json_encode('Erro ao validar o resultado!');
                exit;
            }

            if ($tipo[$id_questao] == 2) {
                $data[] = array(
                    'id_acesso' => $row->id,
                    'id_questao' => $questoes->id_questao,
                    'id_alternativa' => null,
                    'resposta' => $resposta,
                    'data_avaliacao' => mdate("%Y-%m-%d %H:%i:%s")
                );
            } else {
                $data[] = array(
                    'id_acesso' => $row->id,
                    'id_questao' => $questoes->id_questao,
                    'id_alternativa' => $questoes->id_alternativa,
                    'resposta' => null,
                    'data_avaliacao' => mdate("%Y-%m-%d %H:%i:%s")
                );
            }
        }

        $this->db->insert_batch('cursos_clientes_resultado', $data);
        echo json_encode('Atividade finalizada com sucesso!');
    }


    public function finalizar_pagina()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $id_curso = $this->input->post('id_curso');
        $id_pagina = $this->input->post('id_pagina');

        $this->db->select('a.id, a.data_acesso, a.data_atualizacao');
        $this->db->select('c.ordem, c.modulo, c.nota_corte, c.id_pagina_aprovacao, c.id_pagina_reprovacao');
        $this->db->join('cursos_clientes_treinamentos b', 'b.id = a.id_curso_usuario');
        $this->db->join('cursos_clientes b2', 'b2.id = b.id_usuario');
        $this->db->join('cursos_paginas c', 'c.id = a.id_pagina');
        $this->db->where('b.id_curso', $id_curso);
        $this->db->where('a.id_pagina', $id_pagina);
        $this->db->where('b2.email', $this->session->userdata('email'));
        $row = $this->db->get('cursos_clientes_acessos a')->row();

        if (empty($row)) {
            echo json_encode('Erro ao finalizar a página!');
            exit;
        }

        $this->db->set('data_finalizacao', mdate("%Y-%m-%d %H:%i:%s"));
        if ($row->data_atualizacao) {
            $this->db->set('tempo_estudo', "ADDTIME(IFNULL(`tempo_estudo`, 0), TIMEDIFF('" . mdate("%Y-%m-%d %H:%i:%s") . "', '{$row->data_atualizacao}'))", false);
        } else {
            $this->db->set('tempo_estudo', "ADDTIME(IFNULL(`tempo_estudo`, 0), TIMEDIFF('" . mdate("%Y-%m-%d %H:%i:%s") . "', '{$row->data_acesso}'))", false);
        }
        $this->db->where('id', $row->id);
        $this->db->update('cursos_clientes_acessos');

        if ($row->modulo == 'quiz' || $row->modulo == 'atividades') {
            $sql = "SELECT SUM(b.peso) AS total, 
                           SUM(d.peso) AS nota_atividade
                    FROM cursos_questoes a 
                    INNER JOIN cursos_alternativas b ON 
                               b.id_questao = a.id
                    LEFT JOIN cursos_clientes_resultado c ON 
                              c.id_questao = a.id AND 
                              c.id_alternativa = b.id AND 
                              c.id_acesso = {$row->id}
                    LEFT JOIN cursos_alternativas d ON 
                              d.id = c.id_alternativa
                    WHERE a.id_pagina = {$id_pagina}";
            $resultado = $this->db->query($sql)->row();

            $this->db->select('ordem');
            if (($resultado->total / 100 * $resultado->nota_atividade) < $row->nota_corte) {
                $this->db->where('id', $row->id_pagina_reprovacao);
            } else {
                $this->db->where('id', $row->id_pagina_aprovacao);
            }
            $proxima_pagina = $this->db->get('cursos_paginas')->row();
            if ($proxima_pagina) {
                $row->ordem = $proxima_pagina->ordem;
            }
        }

        $this->db->select('ordem');
        $this->db->where('id_curso', $id_curso);
        $this->db->where('ordem >', $row->ordem);
        $this->db->limit(1);
        $ultima_pagina = $this->db->get('cursos_paginas')->row();

        if ($ultima_pagina) {
            $result['proxima'] = $ultima_pagina->ordem;
        } else {
            $result['proxima'] = null;
        }

        echo json_encode($result);
    }


    public function status($id = null)
    {
        $sql = "SELECT a.titulo,
                       a.modulo,
                       d.tipo,
                       DATE_FORMAT(f.data_acesso, '%d/%m/%Y') AS data_acesso,
                       DATE_FORMAT(f.data_finalizacao, '%d/%m/%Y') AS data_finalizacao,
                       IFNULL(f.tempo_estudo, ABCBR304_SEC_TO_TIME(TIMESTAMPDIFF(SECOND, f.data_acesso, f.data_finalizacao))) AS tempo_estudo,
                       SUM(IF(h.peso < 0, 0, h.peso)) * 100 / SUM(IF(e.peso < 0, 0, e.peso)) AS nota_avaliacao
                FROM cursos_paginas a 
                INNER JOIN cursos b ON 
                           b.id = a.id_curso
                INNER JOIN cursos_clientes_treinamentos c ON 
                           c.id_curso = b.id
                INNER JOIN cursos_clientes c2 ON 
                           c2.id = c.id_usuario
                LEFT JOIN cursos_questoes d ON 
                          d.id_pagina = a.id AND 
                          (d.tipo = 1 OR d.tipo = 3) AND
                          (a.modulo = 'quiz' OR a.modulo = 'atividades')
                LEFT JOIN cursos_alternativas e ON 
                          e.id_questao = d.id
                LEFT JOIN cursos_clientes_acessos f ON 
                          f.id_curso_usuario = c.id AND 
                          f.id_pagina = a.id 
                LEFT JOIN cursos_clientes_resultado g ON 
                          g.id_acesso = f.id AND 
                          g.id_questao = d.id AND 
                          g.id_alternativa = e.id
                LEFT JOIN cursos_alternativas h ON 
                          h.id = g.id_alternativa 
                WHERE c.id = {$id} 
                GROUP BY a.id 
                ORDER BY a.ordem ASC";
        $data['rows'] = $this->db->query($sql)->result();

        $total = "SELECT s.id_usuario,
                         100 / count(s.id) * count(s.data_finalizacao) AS finalizado, 
                         CASE WHEN s.tempo_estudo IS NOT NULL 
                         THEN ABCBR304_SEC_TO_TIME(SUM(s.tempo_estudo)) 
                         ELSE '00:00:00' END AS tempo_curso,
                         avg(s.resultado) AS nota_final
                  FROM (SELECT a.id, 
                               c.id_usuario,
                               f.data_finalizacao, 
                               TIMESTAMPDIFF(SECOND, f.data_acesso, f.data_finalizacao) AS tempo_estudo,
                               CASE WHEN a.modulo = 'atividades' 
                                    THEN SUM(IF(h.peso < 0, 0, h.peso)) * 100 / SUM(IF(e.peso < 0, 0, e.peso)) 
                                    ELSE null END AS resultado
                        FROM cursos_paginas a 
                        INNER JOIN cursos b ON 
                                   b.id = a.id_curso
                        INNER JOIN cursos_clientes_treinamentos c ON 
                                   c.id_curso = b.id
                        INNER JOIN cursos_clientes c2 ON 
                                   c2.id = c.id_usuario
                        LEFT JOIN cursos_questoes d ON 
                                  d.id_pagina = a.id AND 
                                  (d.tipo = 1 OR d.tipo = 3) AND
                                  (a.modulo = 'quiz' OR a.modulo = 'atividades')
                        LEFT JOIN cursos_alternativas e ON 
                                  e.id_questao = d.id
                        LEFT JOIN cursos_clientes_acessos f ON 
                                  f.id_curso_usuario = c.id AND 
                                  f.id_pagina = a.id
                        LEFT JOIN cursos_clientes_resultado g ON 
                                  g.id_acesso = f.id AND 
                                  g.id_questao = d.id AND 
                                  g.id_alternativa = e.id
                        LEFT JOIN cursos_alternativas h ON 
                                  h.id = g.id_alternativa 
                        WHERE c.id = {$id} 
                        GROUP BY a.id 
                        ORDER BY a.ordem ASC) s";
        $data['total'] = $this->db->query($total)->row();

        $this->load->view('ead/status_treinamento', $data);
    }


    public function certificado($id)
    {
        $sql = "SELECT b.*, 
                       MIN(f.data_acesso) AS data_acesso, 
                       MAX(f.data_finalizacao) AS data_finalizacao,
                       IFNULL(TIME_FORMAT(ABCBR304_SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, f.data_acesso, f.data_finalizacao))), '%H'), 0) AS duracao,
                       b.horas_duracao,
                       a2.nome AS nome_usuario, 
                       a2.id_empresa, 
                       a2.foto,
                       h.assinatura_digital, 
                       h.nome AS empresa_emissora, 
                       h.foto AS foto_empresa_emissora,
                       h.assinatura_digital AS assinatura_emissora,
                       a.data_inicio,
                       a.data_maxima,
                       a.nota_aprovacao,
                       e.peso,
                       100 / e.peso * SUM(CASE WHEN g.id IS NOT NULL THEN 1 ELSE NULL END) as resultado
                FROM cursos_clientes_treinamentos a 
                INNER JOIN cursos_clientes a2 ON 
                           a2.id = a.id_usuario
                INNER JOIN cursos b ON 
                           b.id = a.id_curso
                INNER JOIN cursos_paginas c ON 
                           c.id_curso = b.id
                LEFT JOIN cursos_questoes d ON 
                          d.id_pagina = a.id AND 
                          c.modulo = 'atividades'
                LEFT JOIN cursos_alternativas e ON 
                          e.id_questao = d.id AND 
                          e.peso > 0
                LEFT JOIN cursos_clientes_acessos f ON 
                          f.id_curso_usuario = a.id AND 
                          f.id_pagina = c.id AND 
                          f.data_finalizacao IS NOT NULL
                LEFT JOIN cursos_clientes_resultado g ON 
                          g.id_acesso = f.id AND 
                          g.id_questao = d.id AND 
                          g.id_alternativa = e.id              
                INNER JOIN usuarios h ON
                           h.id = a2.id_empresa
                WHERE a.id = {$id}";

        $row = $this->db->query($sql)->row();

        if (empty($row->id) or ($row->peso !== null && $row->resultado < $row->nota_aprovacao)) {
            header("location:" . site_url('home'));
            exit;
        } else {
            setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');

            $data_acesso = strtotime($row->data_inicio);
            $data_finalizacao = strtotime($row->data_maxima);

//            $data_acesso = strtotime($row->data_inicio ?? $row->data_acesso);
//            $data_finalizacao = strtotime($row->data_maxima ?? $row->data_finalizacao);
            if (date('Y', $data_acesso) !== date('Y', $data_finalizacao)) {
                $data_inicial = strftime('%d de %B de %Y a ', $data_acesso);
            } elseif (date('m', $data_acesso) !== date('m', $data_finalizacao)) {
                $data_inicial = strftime('%d de %B a ', $data_acesso);
            } elseif (date('d', $data_acesso) !== date('d', $data_finalizacao)) {
                $data_inicial = strftime('%d a ', $data_acesso);
            } else {
                $data_inicial = '';
            }

            $data = array(
                'nome' => $row->nome_usuario,
                'nome_treinamento' => $row->nome,
                'duracao' => $row->horas_duracao,
                'data_inicial' => utf8_encode($data_inicial),
                'data_final' => utf8_encode(strftime('%d de %B de %Y', $data_finalizacao))
            );
            if ($row->empresa) {
                $data['empresa'] = $row->empresa_emissora;
                $data['foto'] = $row->foto_empresa_emissora;
                $data['assinatura'] = $row->assinatura_emissora;
            } else {
                $data['empresa'] = $row->nome_usuario;
                $data['foto'] = $row->foto;
                $data['assinatura'] = $row->assinatura_digital;
            }

            $this->load->library('m_pdf');

            $html = $this->load->view('certificado_view', $data, true);
            $this->m_pdf->pdf->AddPage('L');
            $this->m_pdf->pdf->writeHTML($html);

            $this->m_pdf->pdf->Output('certificado.pdf', 'I');
        }
    }

}
