<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Semestres extends MY_Controller
{

    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $diretorias = $this->db->get('ei_diretorias')->result();

        $data['diretorias'] = array('' => 'Todos') + array_column($diretorias, 'nome', 'id');

        $this->db->select('a.id, a.contrato');
        $this->db->join('ei_diretorias b', 'b.id = a.id_cliente');
        $this->db->where('b.id_empresa', $empresa);
        $contratos = $this->db->get('ei_contratos a')->result();

        $data['contratos'] = array('' => 'Todos') + array_column($contratos, 'contrato', 'id');

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id_empresa', $empresa);
        $escolas = $this->db->get('ei_escolas a')->result();

        $data['escolas'] = array('' => 'Todos') + array_column($escolas, 'nome', 'id');

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $cursos = $this->db->get('ei_cursos')->result();

        $data['cursos'] = array('' => 'Todos') + array_column($cursos, 'nome', 'id');

        $this->load->view('ei/semestres', $data);
    }

    public function index2()
    {
        $this->gerenciar();
    }

    public function gerenciar($id_escola = null)
    {
        /*$qtde_escolas = $this->db->get_where('ei_escolas', array('id' => $id_escola))->num_rows();
        if ($id_escola and !$qtde_escolas) {
            redirect(site_url('home'));
        }*/

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
                       INNER JOIN ei_contratos c ON c.id = b.id_contrato
                       INNER JOIN ei_diretorias d ON d.id = c.id_cliente
                       WHERE d.id_empresa = {$empresa} AND a.status = 'A'";
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
        } elseif (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
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
        } elseif (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
        } else {
            $data['diretoria'] = array('' => 'Todas');
            $data['id_diretoria'] = array('' => 'selecione...');
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $diretorias_disponiveis = $this->db->get('ei_diretorias a')->result();
        foreach ($diretorias_disponiveis as $diretoria_disponivel) {
            $data['diretoria'][$diretoria_disponivel->id] = $diretoria_disponivel->nome;
            $data['id_diretoria'][$diretoria_disponivel->id] = $diretoria_disponivel->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('ei_supervisores c', 'c.id_escola = a.id', 'left');
        $this->db->where('b.id_empresa', $empresa);
        $data['escola'] = array();
        $data['id_escola'] = array();
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
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

        /*$this->db->select('b.nome, c.nome AS aluno, d.nome AS escola, a.status_ativo', false);
        $this->db->join('ei_cursos b', 'b.id = a.id_curso');
        $this->db->join('ei_alunos c', 'c.id = a.id_aluno');
        $this->db->join('ei_escolas d', 'd.id = a.id_escola');
        $this->db->where('a.id', $id_escola);
        $curso = $this->db->get_where('ei_alunos_cursos a')->row();
        $data['nomeCurso'] = $curso->nome ?? null;
        $data['nomeAluno'] = $curso->aluno ?? null;
        $data['nomeEscola'] = $curso->escola ?? null;
        $data['cursoAtivo'] = $curso->status_ativo ?? null;*/

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_cursos b', 'b.id = a.id_curso');
        $this->db->join('ei_alunos_cursos c', 'c.id_curso = b.id');
        $this->db->order_by('a.nome', 'asc');
        $disciplinas = $this->db->get('ei_disciplinas a')->result();
        $data['disciplinas'] = array('' => 'selecione...') + array_column($disciplinas, 'nome', 'id');

        $this->db->select('id, nome');
        $this->db->order_by('nome', 'asc');
        $cuidadores = $this->db->get('usuarios')->result();
        $data['cuidadores'] = array('' => 'selecione...') + array_column($cuidadores, 'nome', 'id');

        $this->load->view('ei/semestres', $data);
    }

    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $busca = $this->input->post('busca');
        $filtro = array();


        $this->db->select('a.id, a.contrato');
        $this->db->join('ei_diretorias b', 'b.id = a.id_cliente');
        $this->db->where('b.id_empresa', $empresa);
        if ($busca['diretoria']) {
            $this->db->where('b.id', $busca['diretoria']);
        }
        $contratos = $this->db->get('ei_contratos a')->result();

        $filtro['contrato'] = array('' => 'Todos') + array_column($contratos, 'contrato', 'id');

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_diretorias b', 'b.id = a.id_diretoria');
        $this->db->where('b.id_empresa', $empresa);
        if ($busca['diretoria']) {
            $this->db->where('b.id', $busca['diretoria']);
        }
        if ($busca['contrato']) {
            $this->db->where('a.id_contrato', $busca['contrato']);
        }
        $escolas = $this->db->get('ei_escolas a')->result();

        $filtro['escola'] = array('' => 'Todos') + array_column($escolas, 'nome', 'id');

        $this->db->select('a.id, a.nome');
        $this->db->join('ei_alunos_cursos b', 'b.id_curso = a.id');
        $this->db->join('ei_escolas c', 'c.id = b.id_escola');
        $this->db->where('a.id_empresa', $empresa);
        if ($busca['diretoria']) {
            $this->db->where('c.id_diretoria', $busca['diretoria']);
        }
        if ($busca['contrato']) {
            $this->db->where('c.id_contrato', $busca['contrato']);
        }
        if ($busca['escola']) {
            $this->db->where('c.id', $busca['escola']);
        }
        $cursos = $this->db->get('ei_cursos a')->result();

        $filtro['curso'] = array('' => 'Todos') + array_column($cursos, 'nome', 'id');

        $data['contrato'] = form_dropdown('busca[contrato]', $filtro['contrato'], $busca['contrato'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['escola'] = form_dropdown('busca[escola]', $filtro['escola'], $busca['escola'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');
        $data['curso'] = form_dropdown('busca[curso]', $filtro['curso'], $busca['curso'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');

        echo json_encode($data);
    }

    public function ajax_list()
    {
        $idEmpresa = $this->session->userdata('empresa');
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();
        $idAlunoCurso = $this->input->post('id_aluno_curso');

        $sql = "SELECT s.id, 
                       s.diretoria,
                       s.escola,
                       s.curso,
                       s.status
                FROM (SELECT a.id,
                             d.nome AS diretoria,
                             c.nome AS escola,
                             b.nome AS curso,
                             NULL AS status
                       FROM ei_alunos_cursos a 
                       INNER JOIN ei_cursos b ON b.id = a.id_curso
                       INNER JOIN ei_escolas c ON c.id = a.id_escola
                       INNER JOIN ei_diretorias d ON d.id = c.id_diretoria
                       LEFT JOIN ei_alunos e ON e.id = a.id_aluno
                       WHERE d.id_empresa = {$idEmpresa} AND e.status = 'A'";
        if (!empty($busca['diretoria'])) {
            $sql .= " AND d.id = '{$busca['diretoria']}'";
        }
        if (!empty($busca['escola'])) {
            $sql .= " AND c.id = '{$busca['escola']}'";
        }
        if (!empty($busca['contrato'])) {
            $sql .= " AND c.id_contrato = '{$busca['contrato']}'";
        }
        if (!empty($busca['curso'])) {
            $sql .= " AND b.id = '{$busca['curso']}'";
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array(
            's.id',
            's.diretoria',
            's.escola',
            's.curso',
            's.status'
        );
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
        foreach ($list as $ei) {
            $row = array();
            $row[] = $ei->diretoria;
            $row[] = $ei->escola;
            $row[] = $ei->curso;
            $row[] = $ei->status;
            $row[] = '
                      <a class="btn btn-sm btn-primary" href="' . site_url('ei/turmas/gerenciar/' . $ei->id) . '" title="Parametrizar semestres">Parametrizar semestres</button>
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

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $this->db->select('id, ano, semestre, modulo');
        $this->db->select("DATE_FORMAT(data_inicio, '%d/%m/%Y') AS data_inicio", false);
        $this->db->select("DATE_FORMAT(data_termino, '%d/%m/%Y') AS data_termino", false);
        $data = $this->db->get_where('ei_semestres', array('id' => $id))->row();
        echo json_encode($data);
    }

    public function ajax_editTurma()
    {
        $id = $this->input->post('id');
        $this->db->select('id, id_semestre, id_disciplina, id_cuidador, dia_semana, nota');
        $this->db->select("TIME_FORMAT(hora_inicio, '%H:%i') AS hora_inicio", false);
        $this->db->select("TIME_FORMAT(hora_termino, '%H:%i') AS hora_termino", false);
        $data = $this->db->get_where('ei_turmas', array('id' => $id))->row();

        if (!empty($data->nota)) {
            $data->nota = number_format($data->nota, 1, ',', '');
        }
        echo json_encode($data);
    }

    public function atualizar_escolas()
    {
        $id_diretoria = $this->input->post('id_diretoria');
        $id_escola = $this->input->post('id_escola');
        $this->db->select('id, nome');
        $rows = $this->db->get_where('ei_escolas', array('id_diretoria' => $id_diretoria))->result();

        $escolas = array('' => 'selecione...');
        foreach ($rows as $row) {
            $escolas[$row->id] = $row->nome;
        }

        $selected = array_key_exists($id_escola, $escolas) ? $id_escola : '';
        $data = form_dropdown('id_escola', $escolas, $selected, 'id="id_escola" class="form-control"');

        echo $data;
    }

    public function atualizar_periodos()
    {
        $id = $this->input->post('id');
        $this->db->select('periodo_manha, periodo_tarde, periodo_noite');
        $data = $this->db->get_where('ei_escolas', array('id' => $id))->row();
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();

        if (empty($data['ano'])) {
            exit('O campo Ano é obrigatório');
        }
        if (empty($data['semestre'])) {
            exit('O campo Semestre é obrigatório');
        }

        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));
        }

        $status = $this->db->insert('ei_semestres', $data);
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_addTurma()
    {
        $data = $this->input->post();

        if (empty($data['id_disciplina'])) {
            exit('O campo Disciplina é obrigatório');
        }

        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }
        if ($data['nota']) {
            $data['nota'] = str_replace(',', '.', $data['nota']);
        }

        $status = $this->db->insert('ei_turmas', $data);
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);

        if (empty($data['ano'])) {
            exit('O campo Ano é obrigatório');
        }
        if (empty($data['semestre'])) {
            exit('O campo Semestre é obrigatório');
        }

        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        if ($data['data_termino']) {
            $data['data_termino'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_termino'])));
        }

        $status = $this->db->update('ei_semestres', $data, array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_updateTurma()
    {
        $data = $this->input->post();
        $id = $data['id'];
        unset($data['id']);

        if (empty($data['id_disciplina'])) {
            exit('O campo Disciplina é obrigatório');
        }

        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }
        if ($data['nota']) {
            $data['nota'] = str_replace(',', '.', $data['nota']);
        }

        $status = $this->db->update('ei_turmas', $data, array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_semestres', array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_deleteTurma()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('ei_turmas', array('id' => $id));
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

}
