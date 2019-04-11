<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Escolas extends MY_Controller
{

    public function index()
    {
        $this->gerenciar();
    }

    public function gerenciar($id_diretoria = null)
    {
        $empresa = $this->session->userdata('empresa');
        $id_usuario = $this->session->userdata('id');

        $data = array();

        $this->db->select('a.id, a.nome');
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id', 'left');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id', 'left');
        $this->db->where('a.id_empresa', $empresa);
        if ($id_diretoria) {
            $this->db->where('a.id', $id_diretoria);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $data['diretoria'] = array();
            $data['id_diretoria'] = array();
        } else {
            $data['diretoria'] = array('' => 'Todas');
            $data['id_diretoria'] = array('' => 'selecione...');
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $diretorias = $this->db->get('cd_diretorias a')->result();
        foreach ($diretorias as $diretoria) {
            $data['diretoria'][$diretoria->id] = $diretoria->nome;
            $data['id_diretoria'][$diretoria->id] = $diretoria->nome;
        }

        $this->db->select('c.id_supervisor AS id, d.nome', false);
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->where('a.id_empresa', $empresa);
        if ($id_diretoria) {
            $this->db->where('a.id', $id_diretoria);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $data['supervisor'] = array();
        } else {
            $data['supervisor'] = array('' => 'Todos');
        }
        $this->db->group_by('c.id_supervisor');
        $this->db->order_by('d.nome', 'asc');
        $supervisores = $this->db->get('cd_diretorias a')->result();

        foreach ($supervisores as $supervisor) {
            $data['supervisor'][$supervisor->id] = $supervisor->nome;
        }

        $this->load->view('cd/escolas', $data);
    }

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
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $filtro['diretoria'] = array();
        } else {
            $filtro['diretoria'] = array('' => 'Todas');
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'asc');
        $diretorias = $this->db->get('cd_diretorias a')->result();
        foreach ($diretorias as $diretoria) {
            $filtro['diretoria'][$diretoria->id] = $diretoria->nome;
        }


        $this->db->select('c.id_supervisor AS id, d.nome', false);
        $this->db->join('cd_escolas b', 'b.id_diretoria = a.id');
        $this->db->join('cd_supervisores c', 'c.id_escola = b.id');
        $this->db->join('usuarios d', 'd.id = c.id_supervisor');
        $this->db->where('a.id_empresa', $empresa);
        if ($busca['diretoria']) {
            $this->db->where('a.id', $busca['diretoria']);
        }
        if (in_array($this->session->userdata('nivel'), array(4, 10, 11))) {
            $this->db->where('c.id_supervisor', $id_usuario);
            $filtro['supervisor'] = array();
        } else {
            $filtro['supervisor'] = array('' => 'Todos');
        }
        $this->db->group_by('c.id_supervisor');
        $this->db->order_by('d.nome', 'asc');
        $supervisores = $this->db->get('cd_diretorias a')->result();

        foreach ($supervisores as $supervisor) {
            $filtro['supervisor'][$supervisor->id] = $supervisor->nome;
        }


        $data['diretoria'] = form_dropdown('diretoria', $filtro['diretoria'], $busca['diretoria'], 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['supervisor'] = form_dropdown('supervisor', $filtro['supervisor'], $busca['supervisor'], 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $arrBusca);
        $busca = $arrBusca['busca'] ?? array();

        $id_diretoria = $this->input->post('id_diretoria');

        $sql = "SELECT s.id, 
                       s.diretoria,
                       s.nome,
                       s.contrato
                FROM (SELECT a.id, 
                             b.alias AS diretoria,
                             a.nome,
                             d.nome AS supervisor,
                             b.contrato
                      FROM cd_escolas a
                      INNER JOIN cd_diretorias b ON
                                b.id = a.id_diretoria
                      LEFT JOIN cd_supervisores c ON 
                                c.id_escola = a.id
                      LEFT JOIN usuarios d ON
                                 d.id = c.id_supervisor
                      WHERE b.id_empresa = {$this->session->userdata('empresa')}";
        if ($id_diretoria) {
            $sql .= " AND b.id = {$id_diretoria}";
        } elseif (!empty($busca['diretoria'])) {
            $sql .= " AND b.id = '{$busca['diretoria']}'";
        }
        if (!empty($busca['depto'])) {
            $sql .= " AND b.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['supervisor'])) {
            $sql .= " AND (c.id_supervisor = '{$busca['supervisor']}' OR c.id_supervisor = '{$busca['supervisor']}')";
        }
        if (!empty($busca['contrato'])) {
            $sql .= " AND b.contrato = '{$busca['contrato']}'";
        }
        $sql .= ' GROUP BY a.id) s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.diretoria', 's.nome', 's.supervisor', 's.contrato');
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
        foreach ($list as $cd) {
            $row = array();
            $row[] = $cd->diretoria;
            $row[] = $cd->nome;
            $row[] = $cd->contrato;
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_escola(' . $cd->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <a class="btn btn-sm btn-primary" href="' . site_url('cd/alunos/gerenciar/' . $cd->id) . '" title="Gerenciar alunos"><i class="glyphicon glyphicon-plus"></i> Alunos</a>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_escola(' . $cd->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                     ';
//            $row[] = '
//                      <button type="button" class="btn btn-sm btn-info" onclick="edit_escola(' . $cd->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
//                      <a class="btn btn-sm btn-primary" href="' . site_url('cd/alunos/gerenciar/' . $cd->id) . '" title="Gerenciar alunos"><i class="glyphicon glyphicon-plus"></i> Alunos</a>
//                      <button type="button" class="btn btn-sm btn-info" onclick="edit_insumos(' . $cd->id . ')" title="Gerenciar insumos"><i class="glyphicon glyphicon-plus"></i> Insumos</button>
//                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_escola(' . $cd->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
//                     ';
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
        $data = $this->db->get_where('cd_escolas', array('id' => $id))->row();
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();
        if (empty($data['id_diretoria'])) {
            exit(json_encode(array('erro' => 'A diretoria de ensino é obrigatória.')));
        }
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome da unidade de ensino é obrigatório.')));
        }
        if (strlen($data['municipio']) == 0) {
            exit(json_encode(array('erro' => 'O município é obrigatório.')));
        }
        if (empty($data['numero'])) {
            $data['numero'] = null;
        }
        $status = $this->db->insert('cd_escolas', $data);
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_update()
    {
        $data = $this->input->post();
        if (empty($data['id_diretoria'])) {
            exit(json_encode(array('erro' => 'A diretoria de ensino é obrigatória.')));
        }
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('erro' => 'O nome da unidade de ensino é obrigatório.')));
        }
        if (strlen($data['municipio']) == 0) {
            exit(json_encode(array('erro' => 'O município é obrigatório.')));
        }
        $id = $data['id'];
        unset($data['id']);
        if (empty($data['numero'])) {
            $data['numero'] = null;
        }
        if (empty($data['periodo_manha'])) {
            $data['periodo_manha'] = null;
        }
        if (empty($data['periodo_tarde'])) {
            $data['periodo_tarde'] = null;
        }
        if (empty($data['periodo_noite'])) {
            $data['periodo_noite'] = null;
        }
        $status = $this->db->update('cd_escolas', $data, array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('cd_escolas', array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    public function importar()
    {
        $this->load->view('cd/importarEscolas');
    }

    public function importarCsv()
    {
        header('Content-type: text/json; charset=UTF-8');
        $this->load->helper(array('date'));

        $empresa = $this->session->userdata('empresa');

        // Verifica se o arquivo foi enviado
        if (!(isset($_FILES) && !empty($_FILES))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro no envio do arquivo. Por favor, tente mais tarde', 'redireciona' => 0, 'pagina' => '')));
        }

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
                $label = array('Escola', 'Endereço', 'Número', 'Complemento', 'Bairro', 'Município', 'Telefone',
                    'Telefone contato', 'E-mail', 'CEP', 'Diretoria', 'Supervisor(a)', 'Períodos');
                $data = array();


                $this->db->trans_begin();

                while (($row = fgetcsv($handle, 1850, ";")) !== FALSE) {
                    $x++;

                    if ($x == 1) {
                        if (count(array_filter($row)) == 13) {
                            $label = $row;
                        }
                        continue;
                    }

                    $row = array_pad($row, 13, '');
                    if (count(array_filter($row)) == 0) {
                        $html .= "Linha $x: registro n&atilde;o encontrado.<br>";
                        continue;
                    }

                    $data['nome'] = utf8_encode($row[0]);
                    $data['endereco'] = utf8_encode($row[1]);
                    $data['numero'] = utf8_encode($row[2]);
                    $data['complemento'] = utf8_encode($row[3]);
                    $data['bairro'] = utf8_encode($row[4]);
                    $data['municipio'] = utf8_encode($row[5]);

                    $telefones = explode('/', $row[6]);
                    foreach ($telefones as $k => $telefone) {
                        $telefones[$k] = trim($telefone);
                    }
                    $data['telefone'] = utf8_encode(implode('/', $telefones));

                    $telefonesContato = explode('/', $row[7]);
                    foreach ($telefonesContato as $k2 => $telefoneContato) {
                        $telefonesContato[$k2] = trim($telefoneContato);
                    }
                    $data['telefone_contato'] = utf8_encode(implode('/', $telefonesContato));

                    $data['email'] = utf8_encode($row[8]);
                    $data['cep'] = utf8_encode($row[9]);

                    $this->db->select('id');
                    $this->db->where('id_empresa', $empresa);
                    $this->db->where('nome', utf8_encode($row[10]));
                    $this->db->or_where('alias', utf8_encode($row[10]));
                    $diretoria = $this->db->get('cd_diretorias')->row();
                    if (!isset($diretoria->id)) {
                        $html .= "Linha $x: diretoria \"" . utf8_encode($row[10]) . "\" n&atilde;o encontrada.<br>";
                        continue;
                    }
                    $data['id_diretoria'] = $diretoria->id;

                    if (utf8_encode($row[11])) {
                        $this->db->select('id');
                        $this->db->where('nome', utf8_encode($row[11]));
                        $supervisor = $this->db->get('usuarios')->row();
                        if (!isset($supervisor->id)) {
                            $html .= "Linha $x: colaborador(a) \"" . utf8_encode($row[11]) . "\" n&atilde;o encontrado(a).<br>";
                            continue;
                        }
                        $data['id_supervisor'] = $supervisor->id;
                    } else {
                        $data['id_supervisor'] = null;
                    }

                    $data['periodos'] = utf8_encode($row[12]);


                    $_POST = $data;

                    if ($this->validaCsv($label)) {
                        $data['periodo_manha'] = preg_match('/(M|I)/i', $row[12]);
                        $data['periodo_tarde'] = preg_match('/(T|I)/i', $row[12]);
                        $data['periodo_noite'] = preg_match('/(N|I)/i', $row[12]);

                        $id_supervisor = $data['id_supervisor'];
                        unset($data['id_supervisor'], $data['periodos']);

                        //Inserir informação no banco
                        $this->db->select('id');
                        $this->db->where('nome', $data['nome']);
                        $escola = $this->db->get('cd_escolas')->row();
                        if (isset($escola->id)) {
                            $id_escola = $escola->id;
                        } else {
                            $this->db->query($this->db->insert_string('cd_escolas', $data));
                            $id_escola = $this->db->insert_id();
                        }

                        if ($id_supervisor) {

                            $this->db->start_cache();
                            //$this->db->where('id_supervisor', $id_supervisor);
                            $this->db->where('id_escola', $id_escola);
                            $this->db->stop_cache();
                            $periodo_manha = $this->db->get_where('cd_supervisores', array('turno' => 'M'))->num_rows() == 0;
                            $periodo_tarde = $this->db->get_where('cd_supervisores', array('turno' => 'T'))->num_rows() == 0;
                            $periodo_noite = $this->db->get_where('cd_supervisores', array('turno' => 'N'))->num_rows() == 0;
                            $this->db->flush_cache();

                            $data2 = array();
                            if ($data['periodo_manha'] and $periodo_manha) {
                                $data2[] = array(
                                    'id_supervisor' => $id_supervisor,
                                    'id_escola' => $id_escola,
                                    'turno' => 'M',
                                );
                            }
                            if ($data['periodo_tarde'] and $periodo_tarde) {
                                $data2[] = array(
                                    'id_supervisor' => $id_supervisor,
                                    'id_escola' => $id_escola,
                                    'turno' => 'T',
                                );
                            }
                            if ($data['periodo_noite'] and $periodo_noite) {
                                $data2[] = array(
                                    'id_supervisor' => $id_supervisor,
                                    'id_escola' => $id_escola,
                                    'turno' => 'N',
                                );
                            }

                            if ($data2) {
                                $this->db->insert_batch('cd_supervisores', $data2);
                            }
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

                if ($validacao == false or $html) {
                    //Mensagem de erro
                    exit(json_encode(array('retorno' => 0, 'aviso' => utf8_encode("Erro no registro de alguns arquivos: <br> {$html}"), 'redireciona' => 0, 'pagina' => '')));
                }
            }
        }

        //Mensagem de confirmação
        exit(json_encode(array('retorno' => 1, 'aviso' => 'Importação de escolas efetuada com sucesso', 'redireciona' => 1, 'pagina' => site_url('cd/escolas/importar'))));
    }

    private function validaCsv($label)
    {
        $this->load->library('form_validation');
        $lang = array(
            'required' => "A coluna %s &eacute; brigat&oacute;ria.",
            'integer' => "A coluna %s deve conter um valor num&eacute;rico.",
            'max_length' => 'A coluna %s n&atilde;o deve conter mais de %s caracteres.',
            'valid_email' => 'A coluna %s deve conter um endereco de e-mail v&aacute;lido.',
            'is_unique' => 'A coluna %s cont&eacute;m dado j&aacute; cadastrado em outro aluno.',
            'is_date' => 'A coluna %s deve conter uma data v&aacute;lida.',
            'regex_match' => 'A coluna %s n&atilde;o est&aacute; no formato correto.'
        );
        $this->form_validation->set_message($lang);

        $config = array(
            array(
                'field' => 'nome',
                'label' => $label[0],
                'rules' => 'required|max_length[100]'
            ),
            array(
                'field' => 'endereco',
                'label' => $label[1],
                'rules' => 'required|max_length[255]'
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
                'field' => 'bairro',
                'label' => $label[4],
                'rules' => 'required|max_length[50]'
            ),
            array(
                'field' => 'municipio',
                'label' => $label[5],
                'rules' => 'required|max_length[50]'
            ),
            array(
                'field' => 'telefone',
                'label' => $label[6],
                'rules' => 'required|max_length[30]'
            ),
            array(
                'field' => 'telefone_contato',
                'label' => $label[7],
                'rules' => 'max_length[30]'
            ),
            // array(
            //     'field' => 'email',
            //     'label' => $label[8],
            //     'rules' => 'valid_email|is_unique[cd_alunos.email]|max_length[255]'
            // ),
            array(
                'field' => 'cep',
                'label' => $label[9],
                'rules' => 'required|max_length[20]'
            ),
            array(
                'field' => 'id_diretoria',
                'label' => $label[10],
                'rules' => 'required|integer|max_length[11]'
            ), array(
                'field' => 'id_supervisor',
                'label' => $label[11],
                'rules' => 'integer|max_length[11]'
            ),
            array(
                'field' => 'periodos',
                'label' => $label[12],
                'rules' => 'max_length[3]'
            )
        );


        $this->form_validation->set_rules($config);

        return $this->form_validation->run();
    }

}
