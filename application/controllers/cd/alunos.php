<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Alunos extends MY_Controller
{

    public function index()
    {
        $this->gerenciar();
    }

    //==========================================================================
    public function gerenciar($id_escola = null)
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');

        $data = array();

        $this->db->select('DISTINCT(a.depto) AS nome', false);
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id', 'left');
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
        $deptos = $this->db->get('cd_diretorias a')->result();
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id', 'left');
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
        $diretorias_disponiveis = $this->db->get('cd_diretorias a')->result();
        foreach ($diretorias_disponiveis as $diretoria_disponivel) {
            $data['diretoria'][$diretoria_disponivel->id] = $diretoria_disponivel->nome;
            $data['id_diretoria'][$diretoria_disponivel->id] = $diretoria_disponivel->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('cd_supervisores c', 'c.id_escola = a.id', 'left');
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
        $escolas_disponiveis = $this->db->get('cd_escolas a')->result();
        foreach ($escolas_disponiveis as $escola_disponivel) {
            $data['escola'][$escola_disponivel->id] = $escola_disponivel->nome;
            $data['id_escola'][$escola_disponivel->id] = $escola_disponivel->nome;
        }

        $this->db->select('b.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_supervisor');
        $this->db->join('cd_escolas c', 'c.id = a.id_escola');
        $this->db->join('cd_diretorias d', 'd.id = c.id_diretoria');
        $this->db->where('b.empresa', $empresa);
        $data['supervisor'] = array();
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('b.id', $id_usuario);
        }
        if ($id_escola) {
            $this->db->where('c.id', $id_escola);
        } else {
            $data['supervisor'] = array('' => 'Todos');
        }
        $this->db->group_by('b.id');
        $this->db->order_by('b.nome', 'asc');
        $supervisores_disponiveis = $this->db->get('cd_supervisores a')->result();
        foreach ($supervisores_disponiveis as $supervisor_disponivel) {
            $data['supervisor'][$supervisor_disponivel->id] = $supervisor_disponivel->nome;
        }

        $this->load->view('cd/alunos', $data);
    }

    //==========================================================================
    public function atualizar_filtro()
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');
        $busca = $this->input->post('busca');
        $filtro = array();

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('a.depto', $busca['depto']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $diretorias_disponiveis = $this->db->get('cd_diretorias a')->result();
        $filtro['diretoria'] = array('' => 'Todas');
        foreach ($diretorias_disponiveis as $diretoria_disponivel) {
            $filtro['diretoria'][$diretoria_disponivel->id] = $diretoria_disponivel->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
        $this->db->join('cd_supervisores c', 'c.id_escola = a.id', 'left');
        $this->db->where('b.id_empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('b.depto', $busca['depto']);
        }
        if ($busca['diretoria']) {
            $this->db->where('b.id', $busca['diretoria']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $escolas_disponiveis = $this->db->get('cd_escolas a')->result();
        $filtro['escola'] = array('' => 'Todas');
        foreach ($escolas_disponiveis as $escola_disponivel) {
            $filtro['escola'][$escola_disponivel->id] = $escola_disponivel->nome;
        }

        $this->db->select('b.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_supervisor');
        $this->db->join('cd_escolas c', 'c.id = a.id_escola');
        $this->db->join('cd_diretorias d', 'd.id = c.id_diretoria');
        $this->db->where('b.empresa', $empresa);
        if ($busca['depto']) {
            $this->db->where('d.depto', $busca['depto']);
        }
        if ($busca['diretoria']) {
            $this->db->where('d.id', $busca['diretoria']);
        }
        if ($busca['escola']) {
            $this->db->where('c.id', $busca['escola']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('b.id', $id_usuario);
        }
        $this->db->group_by('b.id');
        $this->db->order_by('b.nome', 'asc');
        $filtro['supervisor'] = array('' => 'Todos');
        $supervisores_disponiveis = $this->db->get('cd_supervisores a')->result();
        foreach ($supervisores_disponiveis as $supervisor_disponivel) {
            $filtro['supervisor'][$supervisor_disponivel->id] = $supervisor_disponivel->nome;
        }

        $data['diretoria'] = form_dropdown('diretoria', $filtro['diretoria'], $busca['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['escola'] = form_dropdown('escola', $filtro['escola'], $busca['escola'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['supervisor'] = form_dropdown('supervisor', $filtro['supervisor'], $busca['supervisor'], 'onchange="atualizarFiltro()" class="form-control input-sm filtro"');

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();
        $id_escola = $this->input->post('id_escola');

        $sql = "SELECT s.id, 
                       s.diretoria,
                       s.escola,
                       s.nome,
                       CONCAT(ord_manha, ord_tarde, ord_noite) AS periodos,
                       s.status,
                       s.hipotese_diagnostica,
                       s.periodo_manha,
                       s.periodo_tarde,
                       s.periodo_noite
                FROM (SELECT a.id, 
                             c.nome AS diretoria,
                             b.nome AS escola,
                             a.nome,
                             IF(a.periodo_manha = 1, 'Manhã', '') AS periodo_manha,
                             IF(a.periodo_tarde = 1, 'Tarde', '') AS periodo_tarde,
                             IF(a.periodo_noite = 1, 'Noite', '') AS periodo_noite,
                             IF(a.periodo_manha = 1, '1', '0') AS ord_manha,
                             IF(a.periodo_tarde = 1, '2', '0') AS ord_tarde,
                             IF(a.periodo_noite = 1, '3', '0') AS ord_noite,
                             (CASE a.status 
                                  WHEN 'A' THEN 'Ativo'
                                  WHEN 'I' THEN 'Inativo'
                                  WHEN 'N' THEN 'Não frequentando'
                                  WHEN 'F' THEN 'Afastado' END) AS status,
                             a.hipotese_diagnostica
                      FROM cd_alunos a
                      INNER JOIN cd_escolas b ON
                                 b.id = a.id_escola
                      INNER JOIN cd_diretorias c ON 
                                 c.id = b.id_diretoria
                      LEFT JOIN cd_supervisores d ON 
                                d.id_escola = b.id AND
                                (d.turno = IF(a.periodo_manha = 1, 'M', NULL) OR
                                 d.turno = IF(a.periodo_tarde = 1, 'T', NULL) OR
                                 d.turno = IF(a.periodo_noite = 1, 'N', NULL))
                      WHERE c.id_empresa = {$this->session->userdata('empresa')}";
        if ($id_escola) {
            $sql .= " AND b.id = {$id_escola}";
        }
        if (!empty($busca['depto'])) {
            $sql .= " AND c.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['diretoria'])) {
            $sql .= " AND c.id = '{$busca['diretoria']}'";
        }
        if (!empty($busca['escola'])) {
            $sql .= " AND b.id = '{$busca['escola']}'";
        }
        if (!empty($busca['supervisor'])) {
            $sql .= " AND d.id_supervisor = '{$busca['supervisor']}'";
        }
        if (array_key_exists('periodo_manha', $busca) or array_key_exists('periodo_tarde', $busca) or array_key_exists('periodo_noite', $busca)) {
            $periodos = array();
            if (!empty($busca['periodo_manha'])) {
                $periodos[] = "a.periodo_manha = '{$busca['periodo_manha']}'";
            }
            if (!empty($busca['periodo_tarde'])) {
                $periodos[] = "a.periodo_tarde = '{$busca['periodo_tarde']}'";
            }
            if (!empty($busca['periodo_noite'])) {
                $periodos[] = "a.periodo_noite = '{$busca['periodo_noite']}'";
            }
            $sql .= ' AND (' . implode(' OR ', $periodos) . ')';
        }
        $sql .= ' GROUP BY a.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.diretoria', 's.escola', 's.nome', 's.hipotese_diagnostica');
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
        foreach ($list as $cd) {
            $row = array();
            $row[] = $cd->diretoria;
            $row[] = $cd->escola;
            $row[] = $cd->nome;
            $row[] = implode(' / ', array_filter(array($cd->periodo_manha, $cd->periodo_tarde, $cd->periodo_noite)));
            $row[] = $cd->status;
            $row[] = $cd->hipotese_diagnostica;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_aluno(' . $cd->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_aluno(' . $cd->id . ')" title="excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                     ';
            /* $row[] = '
              <button type="button" class="btn btn-sm btn-info" onclick="edit_aluno(' . $cd->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
              <a class="btn btn-sm btn-primary" href="' . site_url('cd/insumos/gerenciar/' . $cd->id) . '" title="Gerenciar insumos"><i class="glyphicon glyphicon-plus"></i> Insumos</a>
              <button type="button" class="btn btn-sm btn-danger" onclick="delete_aluno(' . $cd->id . ')" title="excluir"><i class="glyphicon glyphicon-trash"></i> </button>
              '; */
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

    //==========================================================================
    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $this->db->select('a.*, b.id_diretoria');
        $this->db->select('b.periodo_manha AS escola_manha');
        $this->db->select('b.periodo_tarde AS escola_tarde');
        $this->db->select('b.periodo_noite AS escola_noite');
        $this->db->join('cd_escolas b', 'b.id = a.id_escola');
        $data = $this->db->get_where('cd_alunos a', array('a.id' => $id))->row();
        echo json_encode($data);
    }

    //==========================================================================
    public function atualizar_escolas()
    {
        $id_diretoria = $this->input->post('id_diretoria');
        $id_escola = $this->input->post('id_escola');
        $this->db->select('id, nome');
        $rows = $this->db->get_where('cd_escolas', array('id_diretoria' => $id_diretoria))->result();

        $escolas = array('' => 'selecione...');
        foreach ($rows as $row) {
            $escolas[$row->id] = $row->nome;
        }

        $selected = array_key_exists($id_escola, $escolas) ? $id_escola : '';
        $data = form_dropdown('id_escola', $escolas, $selected, 'id="id_escola" class="form-control"');

        echo $data;
    }

    //==========================================================================
    public function atualizar_periodos()
    {
        $id = $this->input->post('id');
        $this->db->select('periodo_manha, periodo_tarde, periodo_noite');
        $data = $this->db->get_where('cd_escolas', array('id' => $id))->row();
        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_add()
    {
        $data = $this->input->post();

        if (empty($data['nome'])) {
            exit('O nome do(a) aluno(a) é obrigatório');
        }
        if (empty($data['id_escola'])) {
            exit('O campo Unidade de Ensino é obrigatório');
        }
        if (empty($data['hipotese_diagnostica'])) {
            exit('O campo Hipótese Diagnóstica é obrigatório');
        }
        if (!isset($data['periodo_manha'])) {
            $data['periodo_manha'] = 0;
        }
        if (!isset($data['periodo_tarde'])) {
            $data['periodo_tarde'] = 0;
        }
        if (!isset($data['periodo_noite'])) {
            $data['periodo_noite'] = 0;
        }
        if (!($data['periodo_manha'] or $data['periodo_tarde'] or $data['periodo_noite'])) {
            exit('Nenhum período selecionado');
        }

        foreach ($data as $key => $value) {
            if ($value === '') {
                $data[$key] = null;
            }
        }
        if ($data['data_matricula']) {
            $data['data_matricula'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_matricula'])));
        }
        if ($data['data_afastamento']) {
            $data['data_afastamento'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_afastamento'])));
        }
        if ($data['data_desligamento']) {
            $data['data_desligamento'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_desligamento'])));
        }

        $status = $this->db->insert('cd_alunos', $data);
        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_update()
    {
        $data = $this->input->post();

        $erro = '';
        if (empty($data['nome'])) {
            $erro .= "O nome do(a) aluno(a) é obrigatório\n";
        }
        if (empty($data['hipotese_diagnostica'])) {
            $erro .= "O campo Hipótese Diagnóstica é obrigatório\n";
        }
        if (!(isset($data['periodo_manha']) or isset($data['periodo_tarde']) or isset($data['periodo_noite']))) {
            $erro .= "Nenhum período selecionado\n";
        }
        if ($erro) {
            exit($erro);
        }

        $periodos = array();
        if (!empty($data['periodo_manha'])) {
            $periodos[] = 'M';
        } else {
            $data['periodo_manha'] = null;
        }
        if (!empty($data['periodo_tarde'])) {
            $periodos[] = 'T';
        } else {
            $data['periodo_tarde'] = null;
        }
        if (!empty($data['periodo_noite'])) {
            $periodos[] = 'N';
        } else {
            $data['periodo_noite'] = null;
        }

        $id = $data['id'];
        unset($data['id']);
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $data[$key] = null;
            }
        }
        $status = $this->db->update('cd_alunos', $data, array('id' => $id));

        $this->db->select('a.id, a.id_aluno, a.escola, a.id_alocacao, a.turno');
        $this->db->join('cd_alocacao b', "b.id = a.id_alocacao AND DATE_FORMAT(b.data, '%Y-%m') = '" . date('Y-m') . "'");
        $this->db->where('a.id_aluno', $id);
        $this->db->or_where('a.aluno', $data['nome']);
        $this->db->limit(1);
        $matriculados = $this->db->get('cd_matriculados a')->result();


        foreach ($matriculados as $matriculado) {

            if (in_array($matriculado->turno, $periodos) and $status !== false) {
                $this->db->select('nome');
                $escola = $this->db->get_where('cd_escolas', array('id' => $data['id_escola']))->row();

                $data2 = array(
                    'id_alocacao' => $matriculado->id_alocacao,
                    'id_aluno' => $id ?? $matriculado->id_aluno,
                    'aluno' => $data['nome'],
                    'escola' => $escola->nome ?? $matriculado->escola,
                    'status' => $data['status']
                );

                $status = $this->db->update('cd_matriculados a', $data2, array('a.id' => $matriculado->id));
            }

        }


        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('cd_alunos', array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function importar()
    {
        $this->load->view('cd/importarAlunos');
    }

    //==========================================================================
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
                        $this->db->join('cd_diretorias b', 'b.id = a.id_diretoria');
                        $this->db->where('b.id_empresa', $empresa);
                        $this->db->where('a.nome', utf8_encode($row[12]));
                        $escola = $this->db->get('cd_escolas a')->row();
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
                            if ($this->db->get_where('cd_alunos', array('nome' => $data['nome'], 'id_escola' => $data['id_escola']))->num_rows() == 0) {
                                $this->db->query($this->db->insert_string('cd_alunos', $data));
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
                        exit(json_encode(array('retorno' => 1, 'aviso' => 'Importação de alunos efetuada com sucesso', 'redireciona' => 1, 'pagina' => site_url('cd/alunos/importar'))));
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

    //==========================================================================
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
            //     'rules' => 'valid_email|is_unique[cd_alunos.email]|max_length[255]'
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
