<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pagina_curso extends MY_Controller
{

	public function index()
	{
		$this->gerenciar();
	}

	public function gerenciar()
	{
		if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
			redirect(site_url('home'));
		}

		$curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

		if ($this->session->userdata('tipo') != "administrador") {
			if ($curso->id_empresa != $this->session->userdata('id')) {
				redirect(site_url('ead/cursos'));
			}
		}

		$data['row'] = $this->db->query("SELECT a.*, COUNT(b.id) AS qtde_paginas FROM cursos a INNER JOIN cursos_paginas b ON b.id_curso = a.id WHERE a.id = ?", array($this->uri->rsegment(3)))->row();

		$this->load->view('ead/paginascurso', $data);
	}

	public function ajax_list()
	{
		$post = $this->input->post();
		$id_curso = $this->input->post('id');

		$sql = "SELECT s.id, 
                       s.ordem,
                       s.titulo
                FROM (SELECT a.id, 
                             a.ordem,
                             a.titulo
                      FROM cursos_paginas a
                      INNER JOIN cursos b ON
                                 b.id = a.id_curso
                      WHERE b.id_empresa = {$this->session->userdata('empresa')} AND
                            a.id_curso = {$id_curso}) s";

		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array('s.ordem', 's.titulo');
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
				$orderBy[] = ($order['column'] + 2) . ' ' . $order['dir'];
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
		foreach ($list as $pagina_curso) {
			$row = array();
			$row[] = $pagina_curso->ordem;
			$row[] = $pagina_curso->titulo;
			$row[] = '
                      <a class="btn btn-primary btn-sm" href="' . site_url('ead/pagina_curso/editar/' . $pagina_curso->id) . '"><i class="glyphicon glyphicon-pencil"></i> </a>
                      <a class="btn btn-success btn-sm" href="' . site_url('ead/pagina_curso/preview/' . $pagina_curso->id) . '"><i class="glyphicon glyphicon-eye-open"></i> Preview</a>
                      <button class="btn btn-info btn-sm" onclick="copiar(' . $pagina_curso->id . ')"><i class="fa fa-copy"></i> Copiar</button>
                      <button class="btn btn-danger btn-sm excluir" onclick="ajax_delete(' . $pagina_curso->id . ')"><i class="glyphicon glyphicon-trash"></i> </button>
                     ';

			$data[] = $row;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_questoes($id_pagina = '')
	{
		$post = $this->input->post();
		$questoes_add = json_decode($post['questoes_add'] ?? '[]', true);
		$questoes_remove = json_decode($post['questoes_remove'] ?? '[]', true);
		$arr_add = array();

		if ($questoes_add) {
			foreach ($questoes_add as $questao_add) {
				$arr_add[] = $questao_add['id'];
			}
		}
		if ($questoes_remove) {
			$arr_add = array_merge($arr_add, $questoes_remove);
		}
		$id_questoes = implode(', ', array_filter($arr_add));

		$sql = "SELECT s.id, 
                       s.nome, 
                       s.modelo,
                       s.row
                FROM (SELECT a.id, 
                             a.nome,
                             b.nome AS modelo,
                             NULL AS row
                      FROM cursos_questoes a 
                      LEFT JOIN biblioteca_questoes b ON
                                b.id = a.id_biblioteca
                      WHERE a.id_pagina = '{$id_pagina}'";
		if ($id_questoes) {
			$sql .= " AND a.id NOT IN ({$id_questoes})";
		}

		foreach ($questoes_add as $questao_add) {
			$where = $questao_add['id_biblioteca'] ? "= '{$questao_add['id_biblioteca']}'" : 'IS NULL';
			$sql .= " UNION
                      SELECT 0 AS id,
                             '{$questao_add['nome']}' AS nome,
                             (SELECT nome
                              FROM biblioteca_questoes
                              WHERE id {$where}) AS modelo,
                             '{$questao_add['row']}' AS row";
		}
		$sql .= ') s';

		$recordsTotal = $this->db->query($sql)->num_rows();

		$columns = array('s.id', 's.nome', 's.modelo');
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
		foreach ($list as $questao) {
			$row = array();
			$row[] = $questao->nome;
			$row[] = $questao->modelo;
			if ($questao->row !== null) {
				$row[] = '
                          <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_questao_row(' . "'" . $questao->row . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                          <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_questao_row(' . "'" . $questao->row . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                          <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar questão" onclick="edit_conteudo_row(' . "'" . $questao->row . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar Questão</a>
                          <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar respostas" onclick="edit_respostas_row(' . "'" . $questao->row . "'" . ')"><i class="glyphicon glyphicon-list"></i> Editar respostas</a>
                          ';
			} else {
				$row[] = '
                          <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_questao(' . "'" . $questao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                          <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_questao(' . "'" . $questao->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                          <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar questão" onclick="edit_conteudo(' . "'" . $questao->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar Questão</a>
                          <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Editar respostas" onclick="edit_respostas(' . "'" . $questao->id . "'" . ')"><i class="glyphicon glyphicon-list"></i> Editar respostas</a>
                         ';
			}

			$data[] = $row;
		}

		$this->db->select('a.id, a.nome');
		$this->db->join('cursos_questoes b', 'b.id_biblioteca = a.id', 'left');
		$this->db->where('a.id_empresa', $this->session->userdata('id'));
		$this->db->where('b.id_pagina', $id_pagina);
		$this->db->where('b.id_biblioteca', null);
		$questoes = $this->db->get('biblioteca_questoes a')->result();
		$options = array('' => 'selecione...');
		foreach ($questoes as $questao) {
			if (strlen($questao->nome) > 100) {
				$questao->nome = substr($questao_add->nome, 0, 100) . '...';
			}
			$options[$questao->id] = $questao->nome;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $recordsTotal,
			"recordsFiltered" => $recordsFiltered,
			"bibliotecas" => $options,
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ordenar()
	{
		if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
			redirect(site_url('home'));
		}

		$curso = $this->db->get_where('cursos', array('id' => $this->uri->rsegment(3)))->row();

		if ($this->session->userdata('tipo') != "administrador") {
			if ($curso->id_empresa != $this->session->userdata('id')) {
				redirect(site_url('ead/cursos'));
			}
		}

		$datas = $this->input->post('table-dnd');
		$oldData = array();
		$newData = array();
		foreach ($datas as $k => $data) {
			$oldData[$k] = $data['oldData'];
			$newData[$data['oldData'] * (-1) - 1] = $data['newData'];
		}
//
//
//        $this->db->select('id, ordem');
//        $this->db->order_by('ordem', 'asc');
//        $paginas = $this->db->get_where('cursos_paginas', "id_curso = {$curso->id}")->result();
//        $table = array();
//        foreach ($paginas as $pagina) {
//            $table[$pagina->ordem] = $pagina->id;
//        }
//
//        $rows = array_diff_assoc($this->input->post('table-dnd'), $table);

		$this->db->set('ordem', 'ordem * (-1) - 1', false);
		$this->db->where('id_curso', $curso->id);
		$this->db->where_in('ordem', $oldData);
		$this->db->update('cursos_paginas');

		foreach ($newData as $ordem_old => $ordem) {
			$this->db->update('cursos_paginas', array('ordem' => $ordem), array('id_curso' => $curso->id, 'ordem' => $ordem_old));
		}
	}

	public function preview()
	{
		$aula = $this->db->get_where('cursos_paginas', array('id' => $this->uri->rsegment(3)))->row();

//        if ($this->input->server('SERVER_PORT') == 443 && preg_match('/<iframe>|<\/iframe>/i', $aula->conteudo)) {
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, 'http://www.rhsuite.com.br/ame3/login');
//            curl_exec($ch);
//            $ssl = curl_getinfo($ch);
//            curl_close($ch);
//print_r($ssl);exit;
//            $qtdeIframes = substr_count($aula->conteudo, '<iframe>');
//            $qtdeHTTPS = substr_count($aula->conteudo, 'https://');
//            if ($qtdeIframes != $qtdeHTTPS) {
//            header("Location: " . str_replace('https', 'http', current_url()));
//            }
//        }

		if ($this->input->server('SERVER_PORT') == 443 && preg_match('/<iframe>|<\/iframe>/i', $aula->conteudo)) {
			$qtdeIframes = substr_count($aula->conteudo, '<iframe>');
			$qtdeHTTP = substr_count($aula->conteudo, 'http://');
			if ($qtdeIframes === $qtdeHTTP) {
//                header("Location: " . str_replace('https', 'http', current_url()));
			}
		}

		if ($aula->modulo === 'quiz' || $aula->modulo === 'atividades') {
			$this->db->select("a.*, null AS alternativas", false);
			$this->db->join('cursos_paginas b', 'b.id = a.id_pagina');
			if ($aula->aleatorizacao == 'T' || $aula->aleatorizacao == 'P') {
				$this->db->order_by('rand()');
			} else {
				$this->db->order_by('a.id', 'asc');
			}
			$perguntas = $this->db->get_where('cursos_questoes a', array('b.id' => $aula->id))->result();

			foreach ($perguntas as $pergunta) {
				$this->db->select('a.*');
				$this->db->join('cursos_questoes b', 'b.id = a.id_questao');
				$this->db->where('b.id', $pergunta->id);
				if ($aula->aleatorizacao == 'T' || $aula->aleatorizacao == 'A') {
					$this->db->order_by('rand()');
				} else {
					$this->db->order_by('a.id', 'asc');
				}
				$pergunta->alternativas = $this->db->get('cursos_alternativas a')->result();
			}

			$aula->perguntas = $perguntas;
		}

		switch ($aula->url) {
			case '':
				break;
			case strpos($aula->url, 'youtube') > 0:
				$url_video = explode('?v=', $aula->url);
				$aula->url = "https://www.youtube.com/embed/" . $url_video[1] . "?enablejsapi=1";
				break;
			case strpos($aula->url, 'vimeo') > 0:
				$url_video = explode('/', $aula->url);
				$aula->url = "https://player.vimeo.com/video/" . $url_video[3];
				break;
		}

		$data['row'] = $aula;
		$data['biblioteca'] = $this->db->get_where('biblioteca', array('id' => $aula->biblioteca))->row();

		$this->load->view('ead/previewaula', $data);
	}

	public function novo()
	{
		$curso = $this->db->get_where("cursos", array('id' => $this->uri->rsegment(3, 0)))->row();
		if (empty($curso) or !in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
			redirect(site_url('home'));
		}

		if ($this->session->userdata('tipo') != "administrador") {
			if ($curso->id_empresa != $this->session->userdata('id')) {
				exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
			}
		}

		$data['row'] = $curso;

		$this->db->select('id, ordem, titulo');
		$this->db->where('id_curso', $curso->id);
		$id_pagina_aprovacao = $this->db->get('cursos_paginas')->result();
		$proxima_pagina = array(null => 'seguinte...');
		foreach ($id_pagina_aprovacao as $pagina) {
			$proxima_pagina[$pagina->id] = $pagina->ordem . ' - ' . $pagina->titulo;
		}

		$data['row']->proxima_pagina = $proxima_pagina;

		$data['categoria'] = $this->db->query("SELECT * FROM categoria ORDER BY id ASC");

		$this->db->select('id, nome');
		if ($this->session->userdata('tipo') == 'empresa') {
			$this->db->where('id_empresa', $this->session->userdata('id'));
		}
		$questoes = $this->db->get('biblioteca_questoes')->result();
		$data['questao'] = array('' => 'selecione...');
		foreach ($questoes as $questao) {
			if (strlen($questao->nome) > 100) {
				$questao->nome = substr($questao->nome, 0, 100) . '...';
			}
			$data['questao'][$questao->id] = $questao->nome;
		}

		$this->load->view('ead/novapaginacurso', $data);
	}

	public function ajax_add()
	{
		@ini_set('upload_max_filesize', '100M');
		@ini_set('post_max_size', '100M');
		@ini_set('max_execution_time', '300');

		header('Content-type: text/json');
		$this->load->helper(array('date'));

		if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
		}

		$this->db->select('a.id AS id_curso');
		$this->db->select('CASE WHEN max(b.ordem) IS NULL THEN 0 ELSE max(b.ordem) + 1 END AS ordem', false);
		$this->db->join('cursos_paginas b', 'b.id_curso = a.id', 'left');
		$this->db->join('usuarios c', 'c.id = a.id_empresa');
		$this->db->where('a.id', $this->input->post('id_curso'));
		if ($this->session->userdata('tipo') != "administrador") {
			$this->db->where('c.id', $this->session->userdata('id'));
		}
		$pagina = $this->db->get('cursos a')->row();
		if (empty($pagina)) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao acessar a página!')));
		}

		if (!empty($_FILES['arquivo_audio'])) {
			$config['upload_path'] = './arquivos/media/';
			$config['allowed_types'] = '*';
			$config['file_name'] = utf8_decode($_FILES['arquivo_audio']['name']);

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('arquivo_audio')) {
				$arquivo = $this->upload->data();
				$audio = utf8_encode($arquivo['file_name']);
			} else {
				exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
			}
		} elseif ($this->input->post('gravacao_audio')) {
			$audio = $this->input->post('gravacao_audio');
		} else {
			$audio = null;
		}

		$data = array(
			'id_curso' => $pagina->id_curso,
			'ordem' => $pagina->ordem,
			'modulo' => $this->input->post('modulo'),
			'titulo' => $this->input->post('titulo'),
			'conteudo' => null,
			'pdf' => null,
			'url' => null,
			'arquivo_video' => null,
			'categoriabiblioteca' => null,
			'titulobiblioteca' => null,
			'tagsbiblioteca' => null,
			'biblioteca' => null,
			'audio' => $audio,
			'video' => $this->input->post('arquivo_video'),
			'autoplay' => (bool)$this->input->post('autoplay'),
			'nota_corte' => null,
			'id_pagina_aprovacao' => null,
			'id_pagina_reprovacao' => null,
			'aleatorizacao' => null,
			'data_cadastro' => mdate("%Y-%m-%d %H:%i:%s")
		);
		if (empty($data['titulo'])) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Título" não pode ficar em branco')));
		}
		$questoes_add = null;

		switch ($data['modulo']) {
			case '':
				exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não pode ficar em branco')));
				break;

			case 'ckeditor':
				$data['conteudo'] = $this->input->post('conteudo');
				if (empty($data['conteudo'])) {
					exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Conteúdo" não pode ficar em branco')));
				}
				break;

			case 'pdf':
				if (!empty($_FILES['pdf'])) {
					$config['upload_path'] = './arquivos/pdf/';
					$config['allowed_types'] = 'pdf|doc|docx|txt|ppt|pptx';
					$config['file_name'] = utf8_decode($_FILES['pdf']['name']);

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('pdf')) {
						$arquivo = $this->upload->data();
						$data['pdf'] = utf8_encode($arquivo['file_name']);

						if ($arquivo['file_ext'] === '.doc' || $arquivo['file_ext'] === '.docx' || $arquivo['file_ext'] === '.txt' || $arquivo['file_ext'] === '.ppt' || $arquivo['file_ext'] === '.pptx') {
							shell_exec("unoconv -f pdf " . $config['upload_path'] . $arquivo['file_name']);
							$data['pdf'] = utf8_encode($arquivo['raw_name']) . ".pdf";
						}
					} else {
						exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
					}
				} else {
					exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
				}
				break;

			case 'quiz':

			case 'atividades':
				$data['nota_corte'] = $this->input->post('nota_corte');
				$questao_aleatoria = $this->input->post('questao_aleatoria');
				$alternativa_aleatoria = $this->input->post('alternativa_aleatoria');
				if ($questao_aleatoria && $alternativa_aleatoria) {
					$data['aleatorizacao'] = 'T';
				} elseif ($questao_aleatoria || $alternativa_aleatoria) {
					$data['aleatorizacao'] = max($questao_aleatoria, $alternativa_aleatoria);
				}
				if ($this->input->post('id_pagina_aprovacao')) {
					$data['id_pagina_aprovacao'] = $this->input->post('id_pagina_aprovacao');
				}
				if ($this->input->post('id_pagina_reprovacao')) {
					$data['id_pagina_reprovacao'] = $this->input->post('id_pagina_reprovacao');
				}

				$questoes_add = json_decode($this->input->post('questoes_add'), true);
				break;

			case 'url':
				$data['conteudo'] = $this->input->post('conteudo');

				$data['url'] = $this->input->post('url');
				if (empty($data['url'])) {
					if (!empty($_FILES['arquivo_video'])) {

						$config['upload_path'] = './arquivos/videos/';
						$config['allowed_types'] = '*';
						$config['upload_max_filesize'] = '10240';
						$config['file_name'] = utf8_decode($_FILES['arquivo_video']['name']);

						$this->load->library('upload', $config);

						if ($this->upload->do_upload('arquivo_video')) {
							$arquivo = $this->upload->data();
							$data['arquivo_video'] = utf8_encode($arquivo['file_name']);

							if ($arquivo['file_ext'] != '.mp4') {
								$aviso = "Apenas vídeos .mp4 são suportados!";
								exit(json_encode(array('retorno' => 0, 'aviso' => "Arquivo " + $arquivo['file_ext'] + "." + $aviso, 'redireciona' => 0, 'pagina' => '')));
							}
						} else {
							exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
						}
					} else {
						exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
					}
				} else {

					$url_video = $data['url'];
					$url_final = $url_video;

					switch ($url_video) {
						# Youtube anterior
						case strpos($url_video, 'youtube') > 0:
							parse_str(parse_url($data['url'], PHP_URL_QUERY), $url);
							// Verifica se a url está correta
							if (isset($url['v'])) {
								$url_final = "https://www.youtube.com/watch?v=" . $url['v'];
							}
							break;
						# Youtube novo
						case strpos($url_video, 'youtu.be') > 0:
							$url_video = explode('/', $url_video);
							$data['url'] = "https://www.youtube.com/watch?v=" . $url_video[3];
							parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
							// Verifica se a url está correta
							if (isset($url['v'])) {
								$url_final = "https://www.youtube.com/watch?v=" . $url['v'];
							}
							break;
						# Vimeo
						case strpos($url_video, 'vimeo') > 0:
							$url_video = explode('/', $url_video);
							// usando file_get_contents para pegar os dados e unserializando o array
							$video = unserialize(file_get_contents("https://vimeo.com/api/v2/video/{$url_video[3]}.php"));
							// Verifica se a url está correta
							if (isset($video[0]['url'])) {
								$url_final = $video[0]['url'];
							}
							break;
						case strpos($url_video, 'slideshare') > 0:
							$video = json_decode(file_get_contents("https://pt.slideshare.net/api/oembed/2?url=$url_video&format=json"));
							if (isset($video->html)) {
								$url_1 = explode('src="', $video->html);
								$url_1 = explode('https://www.slideshare.net/slideshow/embed_code/key/', $url_1[1]);
								$url_1 = explode('"', $url_1[1]);
								$url_final = "https://pt.slideshare.net/slideshow/embed_code/key/" . $url_1[0];
							}
							break;
						case strpos($url_video, 'dailymotion') > 0:
							$video = json_decode(file_get_contents("https://www.dailymotion.com/services/oembed?url=$url_video"));
							if (isset($video->html)) {
								$url_1 = explode('src="', $video->html);
								$url_1 = explode('https://www.dailymotion.com/embed/video/', $url_1[1]);
								$url_1 = explode('"', $url_1[1]);
								$url_final = "https://www.dailymotion.com/embed/video/" . $url_1[0];
							}
							break;
					}

					$data['url'] = $url_final;

					if (empty($data['url'])) {
						exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não pode ficar em branco')));
					}

//                    if (empty($data['youtube'])) {
//                        exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não é válido')));
//                    }
				}
				break;

			case 'biblioteca':

			case 'aula-digital':

			case 'jogos':

			case 'livros-digitais':

			case 'experimentos':

			case 'softwares':

			case 'audios':

			case 'links-externos':

			case 'multimidia':
				$data['biblioteca'] = $this->input->post('biblioteca');
				if (empty($data['biblioteca'])) {
					exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione um item da Biblioteca para continuar')));
				}
				$data['categoriabiblioteca'] = $this->input->post('categoriabiblioteca');
				$data['titulobiblioteca'] = $this->input->post('titulobiblioteca');
				$data['tagsbiblioteca'] = $this->input->post('tagsbiblioteca');

				break;

			default:
				exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não é válido')));
		}

		if ($this->db->insert('cursos_paginas', $data)) {
			if ($questoes_add) {

				$id_pagina = $this->db->insert_id();
				$alternativas = array();
				$alternativas2 = array();

				foreach ($questoes_add as $questao_add) {

					$questao = array(
						'nome' => $questao_add['nome'],
						'tipo' => $questao_add['tipo'] === '' ? null : $questao_add['tipo'],
						'conteudo' => $questao_add['conteudo'] === '' ? null : $questao_add['conteudo'],
						'feedback_correta' => $questao_add['feedback_correta'] === '' ? null : $questao_add['feedback_correta'],
						'feedback_incorreta' => $questao_add['feedback_incorreta'] === '' ? null : $questao_add['feedback_incorreta'],
						'observacoes' => $questao_add['observacoes'] === '' ? null : $questao_add['observacoes'],
						'aleatorizacao' => $questao_add['aleatorizacao'] === '' ? null : $questao_add['aleatorizacao'],
					);

					$id_questao2 = null;
					if ($questao_add['criar_modelo'] and empty($questao_add['id_biblioteca'])) {
						$questao['id_empresa'] = $this->session->userdata('empresa');
						$this->db->insert('biblioteca_questoes', $questao);
						$id_questao2 = $this->db->insert_id();

						foreach ($questao_add['alternativas'] as $row2) {
							$alternativas2[] = array(
								'id_questao' => $id_questao2,
								'alternativa' => $row2['alternativa'],
								'peso' => $row2['peso']
							);
						}
					}

					$questao['id_pagina'] = $id_pagina;
					$questao['id_biblioteca'] = $questao_add['id_biblioteca'] === '' ? $id_questao2 : $questao_add['id_biblioteca'];

					$this->db->insert('cursos_questoes', $questao);
					$id_questao = $this->db->insert_id();

					foreach ($questao_add['alternativas'] as $row) {
						$alternativas[] = array(
							'id_questao' => $id_questao,
							'alternativa' => $row['alternativa'],
							'peso' => $row['peso']
						);
					}
				}

				foreach ($alternativas as $alternativa) {
					$this->db->insert('cursos_alternativas', $alternativa);
				}
				foreach ($alternativas2 as $alternativa2) {
					$this->db->insert('biblioteca_alternativas', $alternativa2);
				}
			}

			if (count(@$data['audio']) > 0) {
				# Apaga arquivos temporários
				$arquivos_temp = $this->db->query("
                SELECT * FROM arquivos_temp
                WHERE usuario = ? AND arquivo = ?", array($this->session->userdata('id'), './arquivos/media/' . $data['audio']));

				foreach ($arquivos_temp->result() as $linha) {
					$this->db->where('id', $linha->id)->delete('arquivos_temp');
				}
			}
			echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro da página efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('ead/pagina_curso/index/' . $pagina->id_curso)));
		} else {
			echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro da página, tente novamente, se o erro persistir entre em contato com o administrador'));
		}
	}

	public function editar()
	{
		if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
			redirect(site_url('home'));
		}

		$pagina = $this->db->query("SELECT * FROM cursos_paginas WHERE id = ?", array($this->uri->rsegment(3)))->row(0);
		$curso = $this->db->query("SELECT * FROM cursos WHERE id = ?", array($pagina->id_curso))->row(0);

		if ($this->session->userdata('tipo') != "administrador") {
			if ($curso->id_empresa != $this->session->userdata('id')) {
				redirect(site_url('ead/cursos'));
			}
		}

		$data['curso'] = $curso;
		$data['row'] = $pagina;

		$this->db->select('id, ordem, titulo');
		$this->db->where('id_curso', $pagina->id_curso);
		$this->db->where('id !=', $pagina->id);
		$id_pagina_aprovacao = $this->db->get('cursos_paginas')->result();
		$proxima_pagina = array(null => 'seguinte...');
		foreach ($id_pagina_aprovacao as $pagina) {
			$proxima_pagina[$pagina->id] = $pagina->ordem . ' - ' . $pagina->titulo;
		}

		$data['row']->proxima_pagina = $proxima_pagina;

		$data['categoria'] = $this->db->query("SELECT * FROM cursos_categorias ORDER BY id ASC");

		$this->db->select('id, nome');
		$this->db->where('id_empresa', $this->session->userdata('id'));
		$questoes = $this->db->get('biblioteca_questoes')->result();
		$data['questao'] = array('' => 'selecione...');
		foreach ($questoes as $questao) {
			if (strlen($questao->nome) > 100) {
				$questao->nome = substr($questao->nome, 0, 100) . '...';
			}
			$data['questao'][$questao->id] = $questao->nome;
		}

		$this->load->view('ead/editarpaginacurso', $data);
//        $this->load->view('ead/teste_gravacao', $data);
	}

	public function editar_questao()
	{
		$id = $this->input->post('id');
		$this->db->select('a.id, a.nome, a.tipo, a.conteudo, a.observacoes, a.aleatorizacao, a.id_biblioteca');
		$data = $this->db->get_where('cursos_questoes a', array('a.id' => $id))->row();

		echo json_encode($data);
	}

	public function editar_conteudo()
	{
		$id = $this->input->post('id');
		$this->db->select('id, conteudo');
		$data = $this->db->get_where('cursos_questoes', array('id' => $id))->row();

		echo json_encode($data);
	}

	public function editar_respostas()
	{
		$id = $this->input->post('id');
		$this->db->select('id AS id_questao, nome, tipo, feedback_correta, feedback_incorreta');
		$this->db->where('id', $id);
		$data = $this->db->get('cursos_questoes')->row();
		$sql = "SELECT b.id,
                       b.alternativa,
                       b.peso
                FROM cursos_questoes a
                LEFT JOIN cursos_alternativas b ON
                          b.id_questao = a.id
                WHERE a.id = {$id}";
		$data->alternativas = $this->db->query($sql)->result();

		echo json_encode($data);
	}

	public function ajax_update()
	{
		@ini_set('upload_max_filesize', '100M');
		@ini_set('post_max_size', '100M');
		@ini_set('max_execution_time', '300');

		header('Content-type: text/json');
		$this->load->helper(array('date'));

		if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!"')));
		}
		$id = $this->input->post('id');

		$this->db->select('a.id, a.id_curso, a.pdf, a.modulo, a.audio');
		$this->db->join('cursos b', 'b.id = a.id_curso');
		$this->db->join('usuarios c', 'c.id = b.id_empresa');
		$this->db->where('a.id', $id);
		if ($this->session->userdata('tipo') != "administrador") {
			$this->db->where('c.id', $this->session->userdata('id'));
		}
		$pagina = $this->db->get('cursos_paginas a')->row();
		if (empty($pagina)) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao acessar a página!')));
		}

		if (!empty($_FILES['arquivo_audio'])) {
			$config['upload_path'] = './arquivos/media/';
			$config['allowed_types'] = '*';
			$config['file_name'] = utf8_decode($_FILES['arquivo_audio']['name']);

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('arquivo_audio')) {
				$arquivo = $this->upload->data();
				$audio = utf8_encode($arquivo['file_name']);

				if (is_file('./arquivos/media/' . $pagina->audio) && $pagina->audio != $audio) {
					@unlink('./arquivos/media/' . $pagina->audio);
				}
			} else {
				exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
			}
		} elseif ($this->input->post('gravacao_audio')) {
			$audio = $this->input->post('gravacao_audio');
		} else {
			$audio = $pagina->audio;
		}

		$data = array(
			'modulo' => $this->input->post('modulo'),
			'titulo' => $this->input->post('titulo'),
			'conteudo' => null,
			'pdf' => null,
			'url' => null,
			'arquivo_video' => null,
			'categoriabiblioteca' => null,
			'titulobiblioteca' => null,
			'tagsbiblioteca' => null,
			'biblioteca' => null,
			'audio' => $audio,
			'video' => $this->input->post('arquivo_video'),
			'autoplay' => (bool)$this->input->post('autoplay'),
			'nota_corte' => null,
			'id_pagina_aprovacao' => null,
			'id_pagina_reprovacao' => null,
			'aleatorizacao' => null,
			'data_editado' => mdate("%Y-%m-%d %H:%i:%s")
		);
		if (empty($data['titulo'])) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Título" não pode ficar em branco')));
		}

		switch ($data['modulo']) {
			case '':
				exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não pode ficar em branco')));
				break;

			case 'ckeditor':
				$data['conteudo'] = $this->input->post('conteudo');
				if (empty($data['conteudo'])) {
					exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Conteúdo" não pode ficar em branco')));
				}
				break;

			case 'pdf':
				if (!empty($_FILES['pdf'])) {
					$config['upload_path'] = './arquivos/pdf/';
					$config['allowed_types'] = 'pdf|doc|docx|txt|ppt|pptx';
					$config['file_name'] = utf8_decode($_FILES['pdf']['name']);

					$this->load->library('upload', $config);

					if ($this->upload->do_upload('pdf')) {
						$arquivo = $this->upload->data();
						$data['pdf'] = utf8_encode($arquivo['file_name']);

						if (in_array($arquivo['file_ext'], array('.pdf', '.doc', '.docx', '.txt', '.ppt', '.pptx'))) {
							shell_exec("unoconv -f pdf " . $config['upload_path'] . $arquivo['file_name']);
							$data['pdf'] = utf8_encode($arquivo['raw_name']) . ".pdf";
						}

						if (is_file('./arquivos/pdf/' . $pagina->pdf) && $pagina->pdf != $data['pdf']) {
							@unlink('./arquivos/pdf/' . $pagina->pdf);
						}
					} else {
						exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
					}
				} elseif ($pagina->pdf) {
					$data['pdf'] = $pagina->pdf;
				} else {
					exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
				}
				break;

			case 'quiz':

			case 'atividades':
				$data['nota_corte'] = $this->input->post('nota_corte');
				$questao_aleatoria = $this->input->post('questao_aleatoria');
				$alternativa_aleatoria = $this->input->post('alternativa_aleatoria');
				if ($questao_aleatoria && $alternativa_aleatoria) {
					$data['aleatorizacao'] = 'T';
				} elseif ($questao_aleatoria || $alternativa_aleatoria) {
					$data['aleatorizacao'] = max($questao_aleatoria, $alternativa_aleatoria);
				}
				if ($this->input->post('id_pagina_aprovacao')) {
					$data['id_pagina_aprovacao'] = $this->input->post('id_pagina_aprovacao');
				}
				if ($this->input->post('id_pagina_reprovacao')) {
					$data['id_pagina_reprovacao'] = $this->input->post('id_pagina_reprovacao');
				}

				$questoes_add = json_decode($this->input->post('questoes_add'), true);
				$questoes_remove = json_decode($this->input->post('questoes_remove'), true);

				if ($questoes_add) {

					$alternativas = array();
					$alternativas2 = array();

					foreach ($questoes_add as $questao_add) {

						$questao = array(
							'nome' => $questao_add['nome'],
							'tipo' => $questao_add['tipo'] === '' ? null : $questao_add['tipo'],
							'conteudo' => $questao_add['conteudo'] === '' ? null : $questao_add['conteudo'],
							'feedback_correta' => $questao_add['feedback_correta'] === '' ? null : $questao_add['feedback_correta'],
							'feedback_incorreta' => $questao_add['feedback_incorreta'] === '' ? null : $questao_add['feedback_incorreta'],
							'observacoes' => $questao_add['observacoes'] === '' ? null : $questao_add['observacoes'],
							'aleatorizacao' => $questao_add['aleatorizacao'] === '' ? null : $questao_add['aleatorizacao'],
						);

						$id_questao2 = null;
						if ($questao_add['criar_modelo'] and empty($questao_add['id_biblioteca'])) {
							$questao['id_empresa'] = $this->session->userdata('empresa');
							$this->db->insert('biblioteca_questoes', $questao);
							$id_questao2 = $this->db->insert_id();

							foreach ($questao_add['alternativas'] as $row2) {
								$alternativas2[] = array(
									'id_questao' => $id_questao2,
									'alternativa' => $row2['alternativa'],
									'peso' => $row2['peso']
								);
							}
						}

						$questao['id_pagina'] = $id;
						$questao['id_biblioteca'] = $questao_add['id_biblioteca'] === '' ? $id_questao2 : $questao_add['id_biblioteca'];

						if ($questao_add['id']) {
							$id_questao = $questao_add['id'];
							$this->db->update('cursos_questoes', $questao, array('id' => $id_questao));
						} else {
							$this->db->insert('cursos_questoes', $questao);
							$id_questao = $this->db->insert_id();
						}

						foreach ($questao_add['alternativas'] as $row) {
							$alternativas[] = array(
								'id' => $row['id'],
								'id_questao' => $id_questao,
								'alternativa' => $row['alternativa'],
								'peso' => $row['peso']
							);
						}
					}

					foreach ($alternativas as $alternativa) {
						$where_alternativa = array('id' => $alternativa['id']);
						unset($alternativa['id']);
						if ($where_alternativa['id']) {
							if ($alternativa['alternativa']) {
								$this->db->update('cursos_alternativas', $alternativa, $where_alternativa);
							} else {
								$this->db->delete('cursos_alternativas', $where_alternativa);
							}
						} else {
							$this->db->insert('cursos_alternativas', $alternativa);
						}
					}
					foreach ($alternativas2 as $alternativa2) {
						$this->db->insert('biblioteca_alternativas', $alternativa2);
					}
				}

				if ($questoes_remove) {
					$this->db->where('id_pagina', $id);
					$this->db->where_in('id', implode(', ', $questoes_remove));
					$this->db->delete('cursos_questoes');
				}

				break;

			case 'url':
				$data['url'] = $this->input->post('url');
				$data['conteudo'] = $this->input->post('conteudo');

				if (empty($data['url'])) {
					if (!empty($_FILES['arquivo_video'])) {

						$config['upload_path'] = './arquivos/videos/';
						$config['allowed_types'] = '*';
						$config['upload_max_filesize'] = '10240';
						$config['file_name'] = utf8_decode($_FILES['arquivo_video']['name']);

						$this->load->library('upload', $config);

						if ($this->upload->do_upload('arquivo_video')) {
							$arquivo = $this->upload->data();
							$data['arquivo_video'] = utf8_encode($arquivo['file_name']);

							if ($arquivo['file_ext'] != '.mp4') {
								$aviso = "Apenas vídeos .mp4 são suportados!";
								exit(json_encode(array('retorno' => 0, 'aviso' => "Arquivo " + $arquivo['file_ext'] + "." + $aviso, 'redireciona' => 0, 'pagina' => '')));
							}
						} else {
							exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
						}
					} elseif ($pagina->arquivo_video) {
						$data['arquivo_video'] = $pagina->arquivo_video;
					} else {
						exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Arquivo" não pode ficar em branco')));
					}
				} else {

					$url_video = $data['url'];
					$url_final = $data['url'];

					switch ($url_video) {
						# Youtube anterior
						case strpos($url_video, 'youtube') > 0:
							parse_str(parse_url($data['url'], PHP_URL_QUERY), $url);
							// Verifica se a url está correta
							if (isset($url['v'])) {
								$url_final = "https://www.youtube.com/watch?v=" . $url['v'];
							}
							break;
						# Youtube novo
						case strpos($url_video, 'youtu.be') > 0:
							$url_video = explode('/', $url_video);
							$data['url'] = "https://www.youtube.com/watch?v=" . $url_video[3];
							parse_str(parse_url($_POST['videoyoutube'], PHP_URL_QUERY), $url);
							// Verifica se a url está correta
							if (isset($url['v'])) {
								$url_final = "https://www.youtube.com/watch?v=" . $url['v'];
							}
							break;
						# Vimeo
						case strpos($url_video, 'vimeo') > 0:
							$url_video = explode('/', $url_video);
							// usando file_get_contents para pegar os dados e unserializando o array
							$video = unserialize(file_get_contents("https://vimeo.com/api/v2/video/{$url_video[3]}.php"));
							// Verifica se a url está correta
							if (isset($video[0]['url'])) {
								$url_final = $video[0]['url'];
							}
							break;
						case strpos($url_video, 'slideshare') > 0:
							$video = json_decode(file_get_contents("https://pt.slideshare.net/api/oembed/2?url=$url_video&format=json"));
							if (isset($video->html)) {
								$url_1 = explode('src="', $video->html);
								$url_1 = explode('https://www.slideshare.net/slideshow/embed_code/key/', $url_1[1]);
								$url_1 = explode('"', $url_1[1]);
								$url_final = "https://pt.slideshare.net/slideshow/embed_code/key/" . $url_1[0];
							}
							break;
						case strpos($url_video, 'dailymotion') > 0:
							$video = json_decode(file_get_contents("https://www.dailymotion.com/services/oembed?url=$url_video"));
							if (isset($video->html)) {
								$url_1 = explode('src="', $video->html);
								$url_1 = explode('https://www.dailymotion.com/embed/video/', $url_1[1]);
								$url_1 = explode('"', $url_1[1]);
								$url_final = "https://www.dailymotion.com/embed/video/" . $url_1[0];
							}
							break;
					}

					$data['url'] = $url_final;

					if (empty($data['url'])) {
						exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não pode ficar em branco')));
					}
//                    if (empty($data['youtube'])) {
//                        exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Endereço da URL" não é válido')));
//                    }
				}
				break;

			case 'biblioteca':

			case 'aula-digital':

			case 'jogos':

			case 'livros-digitais':

			case 'experimentos':

			case 'softwares':

			case 'audios':

			case 'links-externos':

			case 'multimidia':
				$data['biblioteca'] = $this->input->post('biblioteca');
				if (empty($data['biblioteca'])) {
					exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione um item da Biblioteca para continuar')));
				}

				$data['categoriabiblioteca'] = $this->input->post('categoriabiblioteca');
				$data['titulobiblioteca'] = $this->input->post('titulobiblioteca');
				$data['tagsbiblioteca'] = $this->input->post('tagsbiblioteca');

				break;

			default:
				exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Módulo" não é válido')));
		}

		if ($pagina->modulo == "upload" && $data['modulo'] != "upload") {
			if (is_file('./arquivos/pdf/' . $pagina->pdf) && $pagina->pdf != $data['pdf']) {
				@unlink('./arquivos/pdf/' . $pagina->pdf);
			}
		}

		if ($this->db->update('cursos_paginas', $data, array('id' => $pagina->id))) {
			if ($data['modulo'] != 'quiz') {
				$this->db->query("DELETE a.* FROM cursos_resultado a INNER JOIN cursos_acessos b ON b.id = a.id_acesso WHERE b.id_pagina = {$id}");
			}

			if (count(@$data['audio']) > 0) {
				# Apaga arquivos temporários
				$arquivos_temp = $this->db->query("
                SELECT * FROM arquivos_temp
                WHERE usuario = ? AND arquivo = ?", array($this->session->userdata('id'), './arquivos/media/' . $data['audio']));

				foreach ($arquivos_temp->result() as $linha) {
					$this->db->where('id', $linha->id)->delete('arquivos_temp');
				}

				// Verifica se houve mudança no arquivo
				if ($data['audio'] !== $pagina->audio) {
					$uploadDirectory = './arquivos/media/' . $pagina->audio;
					@unlink($uploadDirectory);
				}
			}
			echo json_encode(array('retorno' => 1, 'aviso' => 'Página do curso editada com sucesso', 'redireciona' => 1, 'pagina' => site_url('ead/pagina_curso/index/' . $pagina->id_curso)));
		} else {
			echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar página do curso, tente novamente, se o erro persistir entre em contato com o administrador'));
		}
	}

	public function ajax_delete()
	{
		if (!in_array($this->session->userdata('tipo'), array("administrador", "empresa"))) {
			redirect(site_url('home'));
		}
		$id = $this->input->post('id');
		$this->db->select('id_curso, ordem');
		$pagina = $this->db->get_where('cursos_paginas', array('id' => $id))->row();
		if (empty($pagina)) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao excluir página do curso, tente novamente, se o erro persistir entre em contato com o administrador')));
		}

		# Testa a exclusão da página antes da exclusão dos arquivos da mesma
		$this->db->select('id_curso AS id');
		$curso = $this->db->get_where('cursos_paginas', array('id' => $id))->row();

		$this->db->trans_start(true);

		$delete = "DELETE a.* FROM cursos_paginas a 
                   INNER JOIN cursos b ON 
                              b.id = a.id_curso 
                   WHERE a.id = {$id}";
		if ($this->session->userdata('tipo') == 'empresa') {
			$delete .= " AND b.id_empresa = {$this->session->userdata('id')}";
		}
		$this->db->query($delete);

		$this->db->trans_complete();
		if ($this->db->trans_status() === false) {
			exit(json_encode(array('retorno' => 0, 'aviso' => 'Não foi possível excluir a página')));
		}
		$this->db->trans_off();

		$sql = "(SELECT 'arquivos/pdf/' AS dir, 
                        pdf AS arquivo
                 FROM cursos_paginas
                 WHERE id_curso = {$curso->id} AND 
                       CHAR_LENGTH(pdf) > 0
                 GROUP BY pdf
                 HAVING COUNT(pdf) = 1)
                UNION
                (SELECT 'arquivos/media/' AS dir, 
                        audio AS arquivo
                 FROM cursos_paginas
                 WHERE id_curso = {$curso->id} AND 
                       CHAR_LENGTH(audio) > 0
                 GROUP BY audio
                 HAVING COUNT(audio) = 1)
                UNION
                (SELECT 'arquivos/media/' AS dir, 
                        video AS arquivo
                 FROM cursos_paginas
                 WHERE id_curso = {$curso->id} AND 
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
		$this->db->query("UPDATE cursos_paginas SET ordem = ordem * (-1) WHERE id_curso = {$pagina->id_curso} AND ordem > {$pagina->ordem}");
		$this->db->query("UPDATE cursos_paginas SET ordem = (ordem + 1) * (-1) WHERE id_curso = {$pagina->id_curso} AND ordem < 0");

		echo json_encode(array('status' => true));
	}

	public function duplicar()
	{
		$id = $this->input->post('id');

		$data = $this->db->get_where('cursos_paginas', array('id' => $id))->row_array();

		if (isset($data['id'])) {

			$count = $this->db->get_where('cursos_paginas', array('id_copia' => $data['id']))->num_rows();

			$data['titulo'] .= ' (cópia' . ($count > 0 ? " $count" : '') . ')';
			$data['data_cadastro'] = date('Y-m-d H:i:s');
			$data['id_copia'] = $id;
			unset($data['id'], $data['data_editado']);
			$this->db->query("UPDATE cursos_paginas SET ordem = ordem * (-1) -1 WHERE id_curso = {$data['id_curso']} AND ordem > {$data['ordem']}");
			$data['ordem'] += 1;
			$this->db->insert('cursos_paginas', $data);
			$id_copia = $this->db->insert_id();
			$this->db->query("UPDATE cursos_paginas SET ordem = ordem * (-1) WHERE id_curso = {$data['id_curso']} AND ordem < {$data['ordem']} * (-1)");

			$colunas_questoes = $this->db->list_fields('cursos_questoes');
			$questoes = array_combine($colunas_questoes, $colunas_questoes);
			foreach ($questoes as $k => $questao) {
				$questoes[$k] = 'a.' . $questao;
			}
			$questoes['id_pagina'] = "{$id_copia} AS id_pagina";
			$questoes['id_copia'] = 'a.id AS id_copia';
			unset($questoes['id']);

			$this->db->select(implode(', ', $questoes), false);
			$this->db->join('cursos_paginas b', 'b.id = a.id_pagina');
			$this->db->where('b.id', $id);
			$rows = $this->db->get('cursos_questoes a')->result_array();

			foreach ($rows as $row) {
				$this->db->insert('cursos_questoes', $row);
				$id_questao_copia = $this->db->insert_id();

				$this->db->select('a.id AS id_questao, c.alternativa, c.peso, c.id AS id_copia');
				$this->db->join('cursos_questoes b', 'b.id = a.id_copia');
				$this->db->join('cursos_alternativas c', 'c.id_questao = b.id');
				$this->db->where('a.id', $id_questao_copia);
				$rows2 = $this->db->get('cursos_questoes a')->result_array();
				if ($rows2) {
					$this->db->insert_batch('cursos_alternativas', $rows2);
				}
			}

			echo json_encode("sucesso");
		} else {
			echo json_encode("A página não pode ser copiada");
		}
	}

}
