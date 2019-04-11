<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alunos extends MY_Controller
{

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar($id_escola = null)
    {
        $qtde_escolas = $this->db->get_where('ei_escolas', array('id' => $id_escola))->num_rows();
        if ($id_escola and !$qtde_escolas) {
            redirect(site_url('home'));
        }

        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');

        $data = array();

        $data['status'] = array('' => 'Todos');
        $sqlStatus = "SELECT DISTINCT(a.status) AS id,
                             CASE a.status
                                  WHEN 'A' THEN 'Ativos'
                                  WHEN 'I' THEN 'Inativos'
                                  WHEN 'N' THEN 'Não frequentes'
                                  WHEN 'F' THEN 'Afastados'
                                  END AS nome
                       FROM ei_alunos a
                       INNER JOIN ei_escolas b ON b.id = a.id_escola
                       INNER JOIN ei_diretorias d ON d.id = b.id_diretoria
                       WHERE d.id_empresa = {$empresa}";
        if ($id_escola) {
            $sqlStatus .= " AND a.id_escola = {$id_escola}";
        }
        $statusGroup = $this->db->query($sqlStatus)->result();
        foreach ($statusGroup as $status) {
            $data['status'][$status->id] = $status->nome;
        }
        $data['cursos'] = array('' => 'Todos');

        $this->db->select('DISTINCT(a.depto) AS nome', false);
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('ei_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        $data['depto'] = array();
        if ($id_escola) {
            $this->db->where('b.id', $id_escola);
        } elseif (in_array($this->session->userdata('nivel'), array(4, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
        } else {
            $data['depto'] = array('' => 'Todos');
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.depto', 'asc');
        $deptos = $this->db->get('ei_diretorias a')->result();
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('ei_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        $data['diretoria'] = array();
        $data['id_diretoria'] = array();
        if ($id_escola) {
            $this->db->where('b.id', $id_escola);
        } elseif (in_array($this->session->userdata('nivel'), array(4, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $diretorias_disponiveis = $this->db->get('ei_diretorias a')->result();
        $data['diretoria'] = array('' => 'Todas');
        $data['id_diretoria'] = array('' => 'selecione...');
        foreach ($diretorias_disponiveis as $diretoria_disponivel) {
            $data['diretoria'][$diretoria_disponivel->id] = $diretoria_disponivel->nome;
            $data['id_diretoria'][$diretoria_disponivel->id] = $diretoria_disponivel->nome;
        }

        $this->db->select('a.id, a.nome, a.municipio');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_supervisores c', 'c.id_escola = a.id', 'left');
        $this->db->where('b.id_empresa', $empresa);
        $data['escola'] = array();
        $data['id_escola'] = array();
        if (in_array($this->session->userdata('nivel'), array(4, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
        }
        if ($id_escola) {
            $this->db->where('a.id', $id_escola);
        } else {
            $data['escola'] = array('' => 'Todas');
            $data['id_escola'] = array('' => 'selecione...');
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $escolas_disponiveis = $this->db->get('ei_escolas a')->result();
        foreach ($escolas_disponiveis as $escola_disponivel) {
            $data['escola'][$escola_disponivel->id] = $escola_disponivel->nome;
            $data['id_escola'][$escola_disponivel->id] = $escola_disponivel->nome;
        }
        $data['municipio'] = ['' => 'selecione...'] + array_filter(array_column($escolas_disponiveis, 'municipio', 'municipio'));

        $this->db->select('id, nome');
        $this->db->order_by('nome', 'asc');
        $cursos = array_column($this->db->get('ei_cursos')->result(), 'nome', 'id');
        $data['curso'] = array('' => 'Todos') + $cursos;
        $data['cursos'] = array('' => 'selecione...') + $cursos;

        $this->db->select('nome');
        $this->db->order_by('nome', 'asc');
        $alunos = $this->db->get('ei_alunos')->result();
        $data['alunos'] = ['' => 'Digite ou selecione...'] + array_column($alunos, 'nome', 'nome');

        $this->load->view('ei/alunos', $data);
    }

    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $busca = $this->input->post('busca');
        $filtro = array();


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias c', 'c.id = a.id_diretoria');
        $this->db->join('ei_contratos b', 'b.id_cliente = c.id');
        $this->db->where('c.id_empresa', $empresa);
        if ($busca['diretoria']) {
            $this->db->where('c.id', $busca['diretoria']);
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $escolas = $this->db->get('ei_escolas a')->result();
        $filtro['escola'] = array('' => 'Todas') + array_column($escolas, 'nome', 'id');;

        $sqlStatus = "SELECT a.status,
                             CASE a.status 
                                  WHEN 'A' THEN 'Ativo'
                                  WHEN 'I' THEN 'Inativo'
                                  WHEN 'N' THEN 'Não frequentando'
                                  WHEN 'F' THEN 'Afastado' END AS nome_status
                      FROM ei_alunos a
                      INNER JOIN ei_escolas b
                                 ON b.id = a.id_escola
                      INNER JOIN ei_diretorias c
                                 ON c.id = b.id_diretoria
                      WHERE (b.id = '{$busca['escola']}' OR CHAR_LENGTH('{$busca['escola']}') = 0)
                            AND (c.id = '{$busca['diretoria']}' OR CHAR_LENGTH('{$busca['diretoria']}') = 0)
                      GROUP BY a.status";
        $status = $this->db->query($sqlStatus)->result();
        $filtro['status'] = array('' => 'Todos') + array_column($status, 'nome_status', 'status');


        $this->db->select('a.id, a.nome');
        $this->db->join('ei_alunos_cursos b', 'b.id_curso = a.id');
        $this->db->join('ei_alunos c', 'c.id = b.id_aluno');
        $this->db->join('ei_escolas d', 'd.id = c.id_escola');
        $this->db->join('ei_diretorias e', 'e.id = d.id_diretoria');
        if ($busca['diretoria']) {
            $this->db->where('e.id', $busca['diretoria']);
        }
        if ($busca['escola']) {
            $this->db->where('d.id', $busca['escola']);
        }
        if ($busca['status']) {
            $this->db->where('c.id', $busca['status']);
        }
        $cursos = $this->db->get('ei_cursos a')->result();
        $filtro['curso'] = array('' => 'Todos') + array_column($cursos, 'nome', 'id');

        $data['escola'] = form_dropdown('busca[escola]', $filtro['escola'], $busca['escola'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['status'] = form_dropdown('busca[status]', $filtro['status'], $busca['status'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['curso'] = form_dropdown('busca[curso]', $filtro['curso'], $busca['curso'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');

        echo json_encode($data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();
        $id_escola = $this->input->post('id_escola');

        $sql = "SELECT s.id, 
                       s.nome,
                       s.status,
                       s.id_curso,
                       s.curso,
                       s.status_curso,
                       s.diretoria,
                       s.escola
                FROM (SELECT a.id, 
                             c.nome AS diretoria,
                             b.nome AS escola,
                             a.nome,
                             (CASE a.status 
                                  WHEN 'A' THEN 'Ativo'
                                  WHEN 'I' THEN 'Inativo'
                                  WHEN 'N' THEN 'Não frequentando'
                                  WHEN 'F' THEN 'Afastado' END) AS status,
                             d.id AS id_curso,
                             e.nome AS curso,
                             (CASE d.status_ativo 
                                  WHEN 1 THEN 'Ativo'
                                  WHEN 0 THEN 'Inativo' 
                                  ELSE NULL END) AS status_curso
                      FROM ei_alunos a
                      LEFT JOIN ei_alunos_cursos d ON 
                                d.id_aluno = a.id
                      LEFT JOIN ei_cursos e ON 
                                e.id = d.id_curso
                      LEFT JOIN ei_escolas b ON
                                 b.id = d.id_escola
                      LEFT JOIN ei_diretorias c ON 
                                c.id = b.id_diretoria AND 
                                c.id_empresa = {$this->session->userdata('empresa')}
                      WHERE 1";
        if ($id_escola) {
            $sql .= " AND b.id = {$id_escola}";
        }
        if (!empty($busca['diretoria'])) {
            $sql .= " AND c.id = '{$busca['diretoria']}'";
        }
        if (!empty($busca['escola'])) {
            $sql .= " AND b.id = '{$busca['escola']}'";
        }
        if (!empty($busca['status'])) {
            $sql .= " AND a.status = '{$busca['status']}'";
        }
        if (!empty($busca['curso'])) {
            $sql .= " AND e.id = '{$busca['curso']}'";
        }
        /*if (!empty($busca['curso'])) {
            $sql .= " AND c.id = '{$busca['curso']}'";
        }*/
        $sql .= ' GROUP BY a.id, d.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.escola', 's.nome', 's.curso');
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
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $ei) {
            $row = array();

            $row[] = $ei->nome;
            $row[] = $ei->status;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_aluno(' . $ei->id . ')" title="Editar aluno"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_aluno(' . $ei->id . ')" title="Excluir aluno"><i class="glyphicon glyphicon-trash"></i> </button>
                      <button type="button" class="btn btn-sm btn-info" onclick="add_curso(' . $ei->id . ')" title="Adicionar curso"><i class="glyphicon glyphicon-plus"></i> Curso</button>
                     ';
            $row[] = $ei->curso;
            $row[] = $ei->escola;
            $row[] = $ei->status_curso;
            if ($ei->id_curso) {
                $row[] = '
                          <button type="button" class="btn btn-sm btn-info" onclick="edit_curso(' . $ei->id_curso . ')" title="Editar curso"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button type="button" class="btn btn-sm btn-danger" onclick="delete_curso(' . $ei->id_curso . ')" title="Excluir curso"><i class="glyphicon glyphicon-trash"></i> </button>
                         ';
            } else {
                $row[] = '
                          <button type="button" class="btn btn-sm btn-info disabled" title="Editar curso"><i class="glyphicon glyphicon-pencil"></i></button>
                          <button type="button" class="btn btn-sm btn-danger disabled" title="Excluir curso"><i class="glyphicon glyphicon-trash"></i> </button>
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

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('ei_alunos', array('id' => $id))->row();

        $cursos = $this->db->get_where('ei_alunos_cursos', array('id_aluno' => $data->id))->result();
        /*foreach ($cursos as $curso) {
            $data->{'id_curso[' . $curso->ordem . ']'} = $curso->id;
            $data->{'curso[' . $curso->ordem . ']'} = $curso->nome;
            $data->{'qtde_semestre[' . $curso->ordem . ']'} = $curso->qtde_semestre;
            $data->{'semestre_inicial[' . $curso->ordem . ']'} = $curso->semestre_inicial;
            $data->{'semestre_ativo[' . $curso->ordem . ']'} = $curso->semestre_atual;
            $data->{'status_curso[' . $curso->ordem . ']'} = $curso->status_ativo;
        }*/

        echo json_encode($data);
    }

    public function ajax_editCurso()
    {
        $id = $this->input->post('id');

        $this->db->select('a.*, b.id_diretoria', false);
        $this->db->join('ei_escolas b', 'b.id = a.id_escola');
        $data = $this->db->get_where('ei_alunos_cursos a', array('a.id' => $id))->row();

        echo json_encode($data);
    }

    public function atualizar_escolas()
    {
        $municipio = $this->input->post('municipio');
        $id_diretoria = $this->input->post('id_diretoria');
        $id_escola = $this->input->post('id_escola');


        $this->db->select('municipio');
        if ($id_diretoria) {
            $this->db->where('id_diretoria', $id_diretoria);
        }
        $this->db->where('municipio IS NOT NULL');
        $this->db->order_by('municipio', 'asc');
        $rows1 = $this->db->get('ei_escolas')->result();

        $municipios = ['' => 'selecione...'] + array_column($rows1, 'municipio', 'municipio');


        $this->db->select('id, nome, municipio');
        if ($id_diretoria) {
            $this->db->where('id_diretoria', $id_diretoria);
        }
        if ($municipio) {
            $this->db->where('municipio', $municipio);
        }
        $this->db->order_by('nome', 'asc');
        $rows2 = $this->db->get('ei_escolas')->result();

        $escolas = ['' => 'selecione...'] + array_column($rows2, 'nome', 'id');


        $selected = array_key_exists($id_escola, $escolas) ? $id_escola : '';
        $data['municipios'] = form_dropdown('', $municipios, $municipio, 'id="municipio" class="form-control"');
        $data['escolas'] = form_dropdown('id_escola', $escolas, $selected, 'id="id_escola" class="form-control"');

        echo json_encode($data);
    }

    public function atualizar_periodos()
    {
        $id = $this->input->post('id');
        $this->db->select('b.id, b.nome');
        $this->db->join('ei_cursos b', 'b.id = a.id_curso');
        $this->db->where('a.id_escola', $id);
        $cursos = array('' => 'selecione...') + array_column($this->db->get_where('ei_escolas_cursos a')->result(), 'nome', 'id');

        $data = form_dropdown('id_curso', $cursos, '', 'id="id_curso" class="form-control"');
        echo json_encode(array('cursos' => $data));
    }

    public function ajax_add()
    {
        $data = $this->input->post();

        if (empty($data['nome'])) {
            exit('O nome do(a) aluno(a) é obrigatório');
        }
        /*if (empty($data['id_escola'])) {
            exit('O campo Unidade de Ensino é obrigatório');
        }*/
        /*if (empty($data['hipotese_diagnostica'])) {
            exit('O campo Hipótese Diagnóstica é obrigatório');
        }*/

        $cursos = array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cursos[$key] = $value;
                unset($data[$key]);
            }
            if ($value === '') {
                $data[$key] = null;
            }
        }
        /*if ($data['data_matricula']) {
            $data['data_matricula'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_matricula'])));
        }
        if ($data['data_afastamento']) {
            $data['data_afastamento'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_afastamento'])));
        }
        if ($data['data_desligamento']) {
            $data['data_desligamento'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_desligamento'])));
        }*/

        $status = $this->db->insert('ei_alunos', $data);

        /*if ($status) {
            $id_aluno = $this->db->insert_id();

            $data2 = array();
            foreach ($cursos as $ordem => $curso) {
                $cursos['id_aluno'] = array_fill(1, 5, $id_aluno);
                $cursos['nome'] = $cursos['curso'];
                $cursos['ordem'] = array_combine(array_keys($cursos['id_curso']), array_keys($cursos['id_curso']));
                $cursos['semestre_atual'] = $cursos['semestre_ativo'];
                for ($i = 1; $i <= 5; $i++) {
                    $cursos['status_ativo'][$i] = $cursos['status_curso'][$i] ?? null;
                }
                unset($cursos['id_curso'], $cursos['curso'], $cursos['semestre_ativo'], $cursos['status_curso']);

                $arrCursos = array();
                foreach ($cursos as $key => $values) {
                    foreach ($values as $ordem => $value) {
                        $arrCursos[$ordem][$key] = $value;
                    }
                }

                $data2 = array();
                foreach ($arrCursos as $arrCurso) {
                    if (!empty($arrCurso['nome'])) {
                        $data2[] = $arrCurso;
                    }
                }
            }
            $status = $this->db->insert_batch('ei_alunos_cursos', $data2);
        }*/

        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_addCurso()
    {
        $data = $this->input->post();
        unset($data['id']);

        if (empty($data['id_escola'])) {
            exit('O campo Unidade de Ensino é obrigatório');
        }
        if (empty($data['id_curso'])) {
            exit('O campo Curso é obrigatório');
        }

        $status = $this->db->insert('ei_alunos_cursos', $data);

        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();

        $erro = '';
        if (empty($data['nome'])) {
            $erro .= "O nome do(a) aluno(a) é obrigatório\n";
        }
        /*if (empty($data['hipotese_diagnostica'])) {
            $erro .= "O campo Hipótese Diagnóstica é obrigatório\n";
        }*/
        if ($erro) {
            exit($erro);
        }

        $id = $data['id'];
        unset($data['id']);
        $cursos = array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $cursos[$key] = $value;
                unset($data[$key]);
            }
            if (empty($value)) {
                $data[$key] = null;
            }
        }
        $status = $this->db->update('ei_alunos', $data, array('id' => $id));

        /*if ($status) {
            $rowsCursos = $this->db->get_where('ei_alunos_cursos', array('id_aluno' => $id))->result();
            $cursosAluno = array();
            foreach ($rowsCursos as $rowCurso) {
                $cursosAluno[$rowCurso->ordem] = $rowCurso;
            }


            $cursos['id'] = $cursos['id_curso'];
            $cursos['id_aluno'] = array_fill(1, 5, $id);
            $cursos['nome'] = $cursos['curso'];
            $cursos['ordem'] = array_combine(array_keys($cursos['id_curso']), array_keys($cursos['id_curso']));
            $cursos['semestre_atual'] = $cursos['semestre_ativo'];
            for ($i = 1; $i <= 5; $i++) {
                $cursos['status_ativo'][$i] = $cursos['status_curso'][$i] ?? null;
            }
            unset($cursos['id_curso'], $cursos['curso'], $cursos['semestre_ativo'], $cursos['status_curso']);

            $arrCursos = array();
            foreach ($cursos as $key => $values) {
                foreach ($values as $ordem => $value) {
                    $arrCursos[$ordem][$key] = $value;
                }
            }

            foreach ($arrCursos as $ordem => $data2) {
                if (!($status !== false)) {
                    break;
                }
                if (!empty($data2['nome'])) {
                    if (!empty($cursosAluno[$ordem])) {
                        $status = $this->db->update('ei_alunos_cursos', $data2, array('id' => $cursosAluno[$ordem]->id));
                    } else {
                        $status = $this->db->insert('ei_alunos_cursos', $data2);
                    }
                } elseif (!empty($cursosAluno[$ordem])) {
                    $status = $this->db->delete('ei_alunos_cursos', array('id' => $cursosAluno[$ordem]->id));
                }
            }
        }*/

        /*$this->db->select('a.id, a.id_aluno, a.escola, a.id_alocacao, a.turno');
        $this->db->join('ei_alocacao b', "b.id = a.id_alocacao AND DATE_FORMAT(b.data, '%Y-%m') = '" . date('Y-m') . "'");
        $this->db->where('a.id_aluno', $id);
        $this->db->or_where('a.aluno', $data['nome']);
        $this->db->limit(1);
        $matriculados = $this->db->get('ei_matriculados a')->result();


        foreach ($matriculados as $matriculado) {

            if ($status !== false) {
                $this->db->select('nome');
                $escola = $this->db->get_where('ei_escolas', array('id' => $data['id_escola']))->row();

                $data2 = array(
                    'id_alocacao' => $matriculado->id_alocacao,
                    'id_aluno' => $id ?? $matriculado->id_aluno,
                    'aluno' => $data['nome'],
                    'escola' => $escola->nome ?? $matriculado->escola,
                    'status' => $data['status']
                );

                $status = $this->db->update('ei_matriculados a', $data2, array('a.id' => $matriculado->id));
            }

        }*/


        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_updateCurso()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);

        if ((!empty($data['status_ativo'])) == false) {
            $data['status_ativo'] = null;
        }
        if (empty($data['id_escola'])) {
            exit('O campo Unidade de Ensino é obrigatório');
        }
        if (empty($data['id_curso'])) {
            exit('O campo Curso é obrigatório');
        }

        $status = $this->db->update('ei_alunos_cursos', $data, array('id' => $id));

        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_alunos', array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_deleteCurso()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_alunos_cursos', array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    public function importar()
    {
        $this->load->view('ei/importarAlunos');
    }

    public function importarCsv()
    {
        header('Content-type: text/json; charset=UTF-8');
        $this->load->helper(array('date'));

        $empresa = $this->session->userdata('empresa');

        // Verifica se o arquivo foi enviado
        if (isset($_FILES) && !empty($_FILES)) {
            if ($_FILES['arquivo']['error'] == 0) {
                $config['upload_path'] = './arquivos/csv/';
                $config['file_name'] = utf8_decode($_FILES['arquivo']['name']);
                $config['allowed_types'] = '*';
                $config['overwrite'] = TRUE;

                //Upload do csv
                $html = '';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('arquivo')) {
                    $csv = $this->upload->data();

                    //Importar o arquivo transferido para o banco de dados
                    $handle = fopen($config['upload_path'] . $csv['file_name'], "r");

                    $x = 0;
                    $validacao = true;
                    $label = array('Aluno', 'Endereço', 'Número', 'Complemento', 'Município', 'Telefone',
                        'Contato', 'E-mail', 'CEP', 'Nome responsável', 'Hipótese diagnóstica',
                        'Observações', 'Escola', 'Data matrícula', 'Períodos');
                    $data = array();


                    $this->db->trans_begin();

                    while (($row = fgetcsv($handle, 1850, ";")) !== FALSE) {
                        $x++;

                        if ($x == 1) {
                            if (count(array_filter($row)) == 15) {
                                $label = $row;
                            }
                            continue;
                        }

                        $row = array_pad($row, 15, '');
                        if (count(array_filter($row)) == 0) {
                            $html .= "Linha $x: registro n&atilde;o encontrado.<br>";
                            continue;
                        }

                        $data['nome'] = utf8_encode($row[0]);
                        $data['endereco'] = utf8_encode($row[1]);
                        $data['numero'] = utf8_encode($row[2]);
                        $data['complemento'] = utf8_encode($row[3]);
                        $data['municipio'] = utf8_encode($row[4]);

                        $telefones = explode('/', $row[5]);
                        foreach ($telefones as $k => $telefone) {
                            $telefones[$k] = trim($telefone);
                        }
                        $data['telefone'] = utf8_encode(implode('/', $telefones));
                        $data['contato'] = utf8_encode($row[6]);

                        $data['email'] = utf8_encode($row[7]);
                        $data['cep'] = utf8_encode($row[8]);
                        $data['nome_responsavel'] = utf8_encode($row[9]);
                        $data['hipotese_diagnostica'] = utf8_encode($row[10]);
                        $data['observacoes'] = utf8_encode($row[11]);

                        $this->db->select('a.id');
                        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
                        $this->db->where('b.id_empresa', $empresa);
                        $this->db->where('a.nome', utf8_encode($row[12]));
                        $escola = $this->db->get('ei_escolas a')->row();
                        if (!isset($escola->id)) {
                            $html .= "Linha $x: escola \"" . utf8_encode($row[12]) . "\" n&atilde;o encontrada.<br>";
                            continue;
                        }
                        $data['id_escola'] = $escola->id;

                        $data['data_matricula'] = utf8_encode($row[13]);
                        $data['periodos'] = utf8_encode($row[14]);

                        $_POST = $data;

                        if ($this->validaCsv($label)) {
                            $data['data_matricula'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_matricula'])));
                            $data['periodo_manha'] = preg_match('/(M|I)/i', $row[14]);
                            $data['periodo_tarde'] = preg_match('/(T|I)/i', $row[14]);
                            $data['periodo_noite'] = preg_match('/(N|I)/i', $row[14]);
                            unset($data['periodos']);
                            $data['status'] = 'A';

                            //Inserir informação no banco
                            if ($this->db->get_where('ei_alunos', array('nome' => $data['nome'], 'id_escola' => $data['id_escola']))->num_rows() == 0) {
                                $this->db->query($this->db->insert_string('ei_alunos', $data));
                            }
                        } else {
                            $html .= $this->form_validation->error_string("Linha $x: ");
                            $validacao = false;
                        }
                    }

                    fclose($handle);

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }

                    if ($validacao and empty($html)) {
                        //Mensagem de confirmação
                        exit(json_encode(array('retorno' => 1, 'aviso' => 'Importação de alunos efetuada com sucesso', 'redireciona' => 1, 'pagina' => site_url('ei/alunos/importar'))));
                    } else {
                        //Mensagem de erro
                        exit(json_encode(array('retorno' => 0, 'aviso' => utf8_encode("Erro no registro de alguns arquivos: <br> {$html}"), 'redireciona' => 0, 'pagina' => '')));
                    }
                }
            }
        }

        //Mensagem de erro
        exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro no envio do arquivo. Por favor, tente mais tarde', 'redireciona' => 0, 'pagina' => '')));
    }

    private function validaCsv($label)
    {
        $this->load->library('form_validation');
        $lang = array(
            'required' => "A coluna %s &eacute; obrigat&oacute;ria.",
            'integer' => "A coluna %s deve conter um valor num&eacute;rico.",
            'max_length' => 'A coluna %s n&atilde;o deve conter mais de %s caracteres.',
            'valid_email' => 'A coluna %s deve conter um endereco de e-mail v&aacute;lido.',
            'is_unique' => 'A coluna %s contem dado j&aacute; cadastrado em outro aluno.',
            'is_date' => 'A coluna %s deve conter uma data v&aacue;lida.',
            'regex_match' => 'A coluna %s n&aacute;o est&aacute; no formato correto.'
        );
        $this->form_validation->set_message($lang);

        $config = array(
            array(
                'field' => 'nome',
                'label' => $label[0],
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'endereco',
                'label' => $label[1],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'numero',
                'label' => $label[2],
                'rules' => 'max_length[11]'
            ),
            array(
                'field' => 'complemento',
                'label' => $label[3],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'municipio',
                'label' => $label[4],
                'rules' => 'max_length[100]'
            ),
            array(
                'field' => 'telefone',
                'label' => $label[5],
                'rules' => 'max_length[50]'
            ),
            array(
                'field' => 'contato',
                'label' => $label[6],
                'rules' => 'max_length[255]'
            ),
            // array(
            //     'field' => 'email',
            //     'label' => $label[7],
            //     'rules' => 'valid_email|is_unique[ei_alunos.email]|max_length[255]'
            // ),
            array(
                'field' => 'cep',
                'label' => $label[8],
                'rules' => 'max_length[20]'
            ),
            array(
                'field' => 'nome_responsavel',
                'label' => $label[9],
                'rules' => 'max_length[255]'
            ),
            array(
                'field' => 'hipotese_diagnostica',
                'label' => $label[10],
                'rules' => 'required|max_length[255]'
            ),
            array(
                'field' => 'id_escola',
                'label' => $label[12],
                'rules' => 'required|integer|max_length[11]'
            ),
            // array(
            //     'field' => 'data_matricula',
            //     'label' => $label[13],
            //     'rules' => 'is_date'
            // ),
            array(
                'field' => 'periodos',
                'label' => $label[14],
                'rules' => 'required'
            )
        );


        $this->form_validation->set_rules($config);

        return $this->form_validation->run();
    }


    public function pdf()
    {
        $empresa = $this->session->userdata('empresa');
        $this->load->library('m_pdf');

        $stylesheet = 'table { font-size: 12px; } ';
        $stylesheet .= 'table tr th, table tr td { border: 1px solid #fff; } ';
//        $this->m_pdf->pdf->setTopMargin(60);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);

        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $empresa))->row();

        $sql = "SELECT s.* 
                FROM (SELECT a.nome,
                             (CASE a.status 
                                  WHEN 'A' THEN 'Ativo'
                                  WHEN 'I' THEN 'Inativo'
                                  WHEN 'N' THEN 'Não frequentando'
                                  WHEN 'F' THEN 'Afastado' END) AS status,
                             e.nome AS curso,
                             b.nome AS escola,
                             (CASE d.status_ativo 
                                  WHEN 1 THEN 'Ativo'
                                  WHEN 0 THEN 'Inativo' 
                                  ELSE NULL END) AS status_curso
                      FROM ei_alunos a
                      LEFT JOIN ei_alunos_cursos d ON 
                                d.id_aluno = a.id
                      LEFT JOIN ei_cursos e ON 
                                e.id = d.id_curso
                      LEFT JOIN ei_escolas b ON
                                 b.id = d.id_escola
                      LEFT JOIN ei_diretorias c ON 
                                c.id = b.id_diretoria AND 
                                c.id_empresa = {$empresa}
                      GROUP BY a.id, d.id) s 
                ORDER BY s.nome ASC, s.status ASC, s.curso ASC, s.escola ASC, s.status_curso ASC";
        $data = $this->db->query($sql)->result_array();

        $cabecalho = '<table width="100%">
            <thead>
            <tr>
                <td>
                    <img src="' . base_url('imagens/usuarios/' . $usuario->foto) . '" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="vertical-align: top;" width="100%">
                    <p>
                        <img src="' . base_url('imagens/usuarios/' . $usuario->foto_descricao) . '" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" style="padding-bottom: 12px;  text-align: center; border-top: 5px solid #ddd; border-bottom: 2px solid #ddd; padding:5px;">
                    <h1 style="font-weight: bold;">RELAÇÃO ALUNOS x CURSOS x ESCOLAS</h1>
                </td>
            </tr>
            </tbody>
        </table>
        <br><br>';

        $table = [['Aluno', 'Status', 'Curso', 'Escola', 'Status do curso']];
        foreach ($data as $row) {
            $table[] = $row;
        }
        $this->load->library('table');

        $html = $cabecalho . $this->table->generate($table);

        $this->m_pdf->pdf->writeHTML($html);

        $this->m_pdf->pdf->Output("EI_alunos.pdf", 'D');
    }

}
