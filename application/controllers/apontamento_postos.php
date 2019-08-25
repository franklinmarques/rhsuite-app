<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apontamento_postos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $empresa = $this->session->userdata('empresa');
        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao', 'contrato');

        $data = array_combine($arrSql, array_pad(array(), count($arrSql), array()));

        foreach ($arrSql as $field) {
            $sql = "SELECT DISTINCT(TRIM({$field})) AS {$field} 
                    FROM usuarios 
                    WHERE empresa = {$empresa} AND NOT
                          ({$field} IS NULL OR {$field} = '') 
                    ORDER BY {$field} ASC";
            $rows = $this->db->query($sql)->result_array();
            $data[$field] = array('' => 'Todos');
            foreach ($rows as $row) {
                $data[$field][$row[$field]] = $row[$field];
            }
        }
        $data['cargo'][''] = 'selecione...';
        $data['funcao'][''] = 'selecione...';

        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->where('status', '1');
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();
        $data['usuarios'] = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $data['usuarios'][$usuario->id] = $usuario->nome;
        }

        $this->load->view('apontamento_postos', $data);
    }

    public function atualizar_filtro()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $cargo = $this->input->post('cargo');
        $funcao = $this->input->post('funcao');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
                unset($filtro['area'][''], $filtro['setor']['']);
            }
            unset($filtro['depto']['']);
        }

        $data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'onchange="atualizarFiltro()" class="form-control input-sm"');

        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        if ($depto) {
            $this->db->where('depto', $depto);
        }
        if ($area) {
            $this->db->where('area', $area);
        }
        if ($setor) {
            $this->db->where('setor', $setor);
        }
        if ($cargo) {
            $this->db->where('cargo', $cargo);
        }
        if ($funcao) {
            $this->db->where('funcao', $funcao);
        }
        $this->db->order_by('nome', 'asc');
        $usuarios = $this->db->get('usuarios')->result();
        $options = array('' => 'selecione...');
        foreach ($usuarios as $usuario) {
            $options[$usuario->id] = $usuario->nome;
        }
        $data['id_usuario'] = form_dropdown('id_usuario', $options, '', 'class="form-control"');

        echo json_encode($data);
    }

    public function novo()
    {
        $empresa = $this->session->userdata('empresa');
        $post = $this->input->post();
        $mes = empty($post['mes']) ? date('m') : $post['mes'];
        $ano = empty($post['ano']) ? date('Y') : $post['ano'];

        $this->db->where('id_empresa', $empresa);
        $this->db->where('data', date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano)));
        $this->db->where('depto', $post['depto']);
        $this->db->where('area', $post['area']);
        $this->db->where('setor', $post['setor']);
        $num_rows = $this->db->get('alocacao')->num_rows();
        if ($num_rows) {
            exit;
        }

        $data = array(
            'id_empresa' => $empresa,
            'data' => date('Y-m-t', mktime(0, 0, 0, $mes, 1, $ano)),
            'depto' => $post['depto'],
            'area' => $post['area'],
            'setor' => $post['setor']
        );
        $this->db->trans_start();

        $this->db->insert('alocacao', $data);
        $id_alocacao = $this->db->insert_id();

        $this->db->select("'{$id_alocacao}' AS id_alocacao, a.id AS id_usuario", false);
        $this->db->select("'I' AS tipo_horario, 'P' AS nivel", false);
//        $this->db->join('(SELECT @rownum:=0) b', 'a.id = a.id');
        $this->db->where('a.depto', $post['depto']);
        $this->db->where('a.area', $post['area']);
        $this->db->where('a.setor', $post['setor']);
        $data2 = $this->db->get('usuarios a, (SELECT @rownum:=0) b')->result_array();
        $this->db->insert_batch('alocacao_usuarios', $data2);

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array('status' => $status !== false));
    }

    public function ajax_posto()
    {
        $id_usuario = $this->input->post('id_usuario');

        $this->db->select('matricula, login, horario_entrada, horario_saida');
        $this->db->select('total_dias_mensais, total_horas_diarias, valor_posto, valor_dia, valor_hora');
        $this->db->where('id_usuario', $id_usuario);
        $this->db->order_by('data', 'desc');
        $this->db->limit(1);
        $row = $this->db->get('alocacao_postos')->row();

        echo json_encode($row);
    }

    public function ajax_list()
    {
        $post = $this->input->post();
        parse_str($this->input->post('busca'), $busca);

        $sql = "SELECT s.id, 
                       s.nome,
                       s.data,
                       s.valor_posto,
                       s.total_dias_mensais,
                       s.total_horas_diarias,
                       s.valor_dia,
                       s.valor_hora,
                       s.mes,
                       s.ano
                FROM (SELECT a.id, 
                             c.nome,
                             a.data,
                             a.valor_posto, 
                             a.total_dias_mensais,
                             a.total_horas_diarias,
                             a.valor_dia,
                             a.valor_hora,
                             DATE_FORMAT(a.data, '%m') AS mes,
                             DATE_FORMAT(a.data, '/%Y') AS ano
                      FROM alocacao_postos a
                      INNER JOIN usuarios c ON 
                                 c.id = a.id_usuario 
                      WHERE c.empresa = {$this->session->userdata('empresa')}";
        if (!empty($busca['depto'])) {
            $sql .= " AND a.depto = '{$busca['depto']}'";
        }
        if (!empty($busca['area'])) {
            $sql .= " AND a.area = '{$busca['area']}'";
        }
        if (!empty($busca['setor'])) {
            $sql .= " AND c.setor = '{$busca['setor']}'";
        }
        if (!empty($busca['cargo'])) {
            $sql .= " AND c.cargo = '{$busca['cargo']}'";
        }
        if (!empty($busca['funcao'])) {
            $sql .= " AND c.funcao = '{$busca['funcao']}'";
        }
        if (!empty($busca['contrato'])) {
            $sql .= " AND a.contrato = '{$busca['contrato']}'";
        }
        if (!empty($busca['busca_mes'])) {
            $sql .= " AND DATE_FORMAT(a.data, '%m') = '{$busca['busca_mes']}'";
        }
        if (!empty($busca['busca_ano'])) {
            $sql .= " AND DATE_FORMAT(a.data, '%Y') = '{$busca['busca_ano']}'";
        }
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.data', 's.valor_posto', 's.total_dias_mensais', 's.total_horas_diarias', 's.valor_dia', 's.valor_hora');
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
        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        foreach ($list as $apontamento) {
            $row = array();
            $row[] = $apontamento->nome;
            $row[] = $this->calendar->get_month_name($apontamento->mes) . $apontamento->ano;
            $row[] = number_format($apontamento->valor_posto, 2, ',', '.');
            $row[] = $apontamento->total_dias_mensais;
            $row[] = $apontamento->total_horas_diarias;
            $row[] = number_format($apontamento->valor_dia, 2, ',', '.');
            $row[] = number_format($apontamento->valor_hora, 2, ',', '.');
            $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_posto(' . $apontamento->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_posto(' . $apontamento->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
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

    public function ajax_colaboradores()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db->select('a.id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('alocacao c', 'c.id = a.id_alocacao');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where("DATE_FORMAT(c.data, '%Y-%m') =", "{$busca['ano']}-{$busca['mes']}");
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), array(9, 10, 11))) {
            $this->db->where('c.depto', $busca['depto']);
//            if ($this->session->userdata('nivel') == 11) {
//                $this->db->where('c.area', $busca['area']);
//                $this->db->where('c.setor', $busca['setor']);
//            }
        }
        $this->db->order_by('b.nome', 'asc');
        $rows = $this->db->get('alocacao_usuarios a')->result();

        $options = array('' => 'selecione...');
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $data['id_bck'] = form_dropdown('id_bck', $options, '', 'class="form-control"');
        $data['id_usuario_sub1'] = form_dropdown('id_usuario_sub1', $options, '', 'class="form-control"');
        $data['id_usuario_sub2'] = form_dropdown('id_usuario_sub2', $options, '', 'class="form-control"');
        $data['id_alocado_bck'] = form_dropdown('id_alocado_bck', $options, '', 'class="form-control"');
        $data['id_alocado_bck2'] = form_dropdown('id_alocado_bck2', $options, '', 'class="form-control"');

        echo json_encode($data);
    }

    public function editar_perfil()
    {
        $this->db->where('id', $this->uri->rsegment(3, 0));
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        $this->db->order_by('nome', 'asc');
        $funcionario = $this->db->get('usuarios')->row();

        if (count($funcionario) == 0) {
            redirect(site_url('home/funcinarios'));
        }

        if ($funcionario->empresa != $this->session->userdata('id')) {
            redirect(site_url('home/funcinarios'));
        }
        if ($funcionario->hash_acesso) {
//            $this->load->library('encrypt');
//            $funcionario->hash_acesso = $this->encrypt->decode($funcionario->hash_acesso, base64_encode($funcionario->id));
//            $funcionario->hash_acesso = json_decode($funcionario->hash_acesso, true);
        } else {
            $funcionario->hash_acesso = 'null';
        }

        $dataFormatada = date("d/m/Y", strtotime(str_replace('-', '/', $funcionario->data_admissao)));
        $funcionario->data_admissao = $dataFormatada;
        $data['row'] = $funcionario;
        $data['nivel_acesso'] = array(
            '1' => 'Administrador',
            '7' => 'Presidente',
            '8' => 'Gerente',
            '9' => 'Coordenador',
            '10' => 'Supervisor',
            '11' => 'Encarregado',
            '12' => 'Líder',
            '4' => 'Colaborador',
            '13' => 'Cuidador Comunitário',
            '3' => 'Gestor',
            '2' => 'Multiplicador',
            '6' => 'Selecionador',
            '5' => 'Cliente'
        );
        $data['status'] = array(
            '1' => 'Ativo',
            '2' => 'Inativo',
            '3' => 'Em experiência',
            '4' => 'Em desligamento',
            '5' => 'Desligado',
            '6' => 'Afastado (maternidade)',
            '7' => 'Afastado (aposentadoria)',
            '8' => 'Afastado (doença)',
            '9' => 'Afastado (acidente)'
        );

        $this->db->select('DISTINCT(depto) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(depto) >', 0);
        $this->db->order_by('depto', 'asc');
        $deptos = $this->db->get('usuarios')->result();
        $data['depto'] = array('' => 'digite ou selecione...');
        foreach ($deptos as $depto) {
            $data['depto'][$depto->nome] = $depto->nome;
        }

        $this->db->select('DISTINCT(area) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('CHAR_LENGTH(area) >', 0);
        $this->db->order_by('area', 'asc');
        $areas = $this->db->get('usuarios')->result();
        $data['area'] = array('' => 'digite ou selecione...');
        foreach ($areas as $area) {
            $data['area'][$area->nome] = $area->nome;
        }

        $this->db->select('DISTINCT(setor) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('depto', $funcionario->depto);
        $this->db->where('area', $funcionario->area);
        $this->db->where('CHAR_LENGTH(setor) >', 0);
        $this->db->order_by('setor', 'asc');
        $setores = $this->db->get('usuarios')->result();
        $data['setor'] = array('' => 'digite ou selecione...');
        foreach ($setores as $setor) {
            $data['setor'][$setor->nome] = $setor->nome;
        }

        $this->db->select('DISTINCT(contrato) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(contrato) >', 0);
        $this->db->order_by('contrato', 'asc');
        $contratos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'digite ou selecione...');
        foreach ($contratos as $contrato) {
            $data['contrato'][$contrato->nome] = $contrato->nome;
        }

        $this->db->select('DISTINCT(centro_custo) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(centro_custo) >', 0);
        $this->db->order_by('centro_custo', 'asc');
        $centro_custos = $this->db->get('usuarios')->result();
        $data['centro_custo'] = array('' => 'digite ou selecione...');
        foreach ($centro_custos as $centro_custo) {
            $data['centro_custo'][$centro_custo->nome] = $centro_custo->nome;
        }

        $this->db->select('DISTINCT(cargo) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('CHAR_LENGTH(cargo) >', 0);
        $this->db->order_by('cargo', 'asc');
        $cargos = $this->db->get('usuarios')->result();
        $data['contrato'] = array('' => 'digite ou selecione...');
        foreach ($cargos as $cargo) {
            $data['cargo'][$cargo->nome] = $cargo->nome;
        }

        $this->db->select('DISTINCT(funcao) AS nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('cargo', $funcionario->cargo);
        $this->db->where('CHAR_LENGTH(funcao) >', 0);
        $this->db->order_by('funcao', 'asc');
        $funcoes = $this->db->get('usuarios')->result();
        $data['funcao'] = array('' => 'digite ou selecione...');
        foreach ($funcoes as $funcao) {
            $data['funcao'][$funcao->nome] = $funcao->nome;
        }

        $this->load->view('apontamento_coladorador', $data);
    }

    public function save_perfil()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $this->db->where('id', $this->uri->rsegment(3, 0));
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        $this->db->order_by('nome', 'asc');
        $funcionario = $this->db->get('usuarios')->row();

        if ($funcionario->empresa != $this->session->userdata('id')) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Você não tem acesso a essa página!')));
        }
        if (isset($funcionario->foto)) {
            $funcionario->foto = utf8_decode($funcionario->foto);
        }

        $data['area'] = $this->input->post('area');
        $data['setor'] = $this->input->post('setor');
        $data['contrato'] = $this->input->post('contrato');
        $data['telefone'] = $this->input->post('telefone');
        $data['email'] = $this->input->post('email');
        $data['dataeditado'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['status'] = $this->input->post('status');

        $senha = $this->input->post('senha');
        $confirmar_senha = $this->input->post('confirmar_senha');
        if ($senha != '') {
            if (empty($senha)) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não pode ficar em branco')));
            }

            if ($senha != $confirmar_senha) {
                exit(json_encode(array('retorno' => 0, 'aviso' => 'O campo "Senha" não confere com o "Confirmar Senha"')));
            }

            $data['senha'] = $this->usuarios->setPassword($senha);
        }

        if (!empty($_FILES['logo'])) {
            $config['upload_path'] = './imagens/usuarios/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = utf8_decode($_FILES['logo']['name']);

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('logo')) {
                $foto = $this->upload->data();
                $data['foto'] = utf8_encode($foto['file_name']);
                if ($funcionario->foto != "avatar.jpg" && file_exists('./imagens/usuarios/' . $funcionario->foto) && $funcionario->foto != $foto['file_name']) {
                    @unlink('./imagens/usuarios/' . $funcionario->foto);
                }
            } else {
                exit(json_encode(array('retorno' => 0, 'aviso' => $this->upload->display_errors(), 'redireciona' => 0, 'pagina' => '')));
            }
        }

        if ($this->db->where('id', $funcionario->id)->update('usuarios', $data)) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Funcionário editado com sucesso', 'redireciona' => 1, 'pagina' => site_url('apontamento')));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $this->db->select('a.id, a.id_usuario, a.matricula, a.login, a.total_dias_mensais, a.total_horas_diarias, a.valor_posto, a.valor_dia, a.valor_hora');
        $this->db->select("IFNULL(DATE_FORMAT(a.horario_entrada, '%H:%i'), '') AS horario_entrada", false);
        $this->db->select("IFNULL(DATE_FORMAT(a.horario_saida, '%H:%i'), '') AS horario_saida", false);
        $this->db->select("DATE_FORMAT(a.data, '%m') AS mes", false);
        $this->db->select("DATE_FORMAT(a.data, '%Y') AS ano", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $this->session->userdata('empresa'));
        $this->db->where('a.id', $id);
        $data = $this->db->get('alocacao_postos a')->row();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $post = $this->input->post();
        $post['data'] = date('Y-m-d', strtotime($post['ano'] . '-' . $post['mes']));
        if (!empty($post['horario_entrada'])) {
            $post['horario_entrada'] = date('H:i', strtotime($post['horario_entrada']));
        } else {
            $post['horario_entrada'] = null;
        }
        if (!empty($post['horario_saida'])) {
            $post['horario_saida'] = date('H:i', strtotime($post['horario_saida']));
        } else {
            $post['horario_saida'] = null;
        }
        if (strlen($post['matricula']) == 0) {
            $post['matricula'] = null;
        }
        if (strlen($post['login']) == 0) {
            $post['login'] = null;
        }
        unset($post['id'], $post['mes'], $post['ano']);

        $this->db->select('depto, area, setor, cargo, funcao, contrato');
        $this->db->where('id', $post['id_usuario']);
        $row = $this->db->get('usuarios')->row_array();
        $data = array_merge($post, $row);
        $sql = "SELECT s.* 
                FROM (SELECT a.* 
                      FROM alocacao_postos a 
                      WHERE a.id_usuario = '{$data['id_usuario']}' 
                      ORDER BY a.data DESC 
                      LIMIT 1) s 
                WHERE s.data = '{$data['data']}' OR 
                      (s.depto = '{$data['depto']}' AND 
                       s.area = '{$data['area']}' AND 
                       s.setor = '{$data['setor']}' AND 
                       s.cargo = '{$data['cargo']}' AND 
                       s.funcao = '{$data['funcao']}' AND 
                       s.contrato = '{$data['contrato']}' AND 
                       s.matricula = '{$data['matricula']}' AND 
                       s.login = '{$data['login']}' AND 
                       s.horario_entrada = '{$data['horario_entrada']}' AND 
                       s.horario_saida = '{$data['horario_saida']}' AND 
                       s.total_dias_mensais = '{$data['total_dias_mensais']}' AND 
                       s.total_horas_diarias = '{$data['total_horas_diarias']}' AND 
                       s.valor_posto = '{$data['valor_posto']}' AND 
                       s.valor_dia = '{$data['valor_dia']}' AND 
                       s.valor_hora = '{$data['valor_hora']}')";
        $count = $this->db->query($sql)->num_rows();

        if ($count == 0) {
            $status = $this->db->insert('alocacao_postos', $data);
        } else {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Os dados salvos são idênticos aos do posto anterior.')));
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $post = $this->input->post();
        if (strlen($post['matricula']) == 0) {
            $post['matricula'] = null;
        }
        if (strlen($post['login']) == 0) {
            $post['login'] = null;
        }
        if (!empty($post['horario_entrada'])) {
            $post['horario_entrada'] = date('H:i', strtotime($post['horario_entrada']));
        } else {
            $post['horario_entrada'] = null;
        }
        if (!empty($post['horario_saida'])) {
            $post['horario_saida'] = date('H:i', strtotime($post['horario_saida']));
        } else {
            $post['horario_saida'] = null;
        }
        $id = $post['id'];
        $post['data'] = date('Y-m-d', strtotime($post['ano'] . '-' . $post['mes']));
        unset($post['id'], $post['mes'], $post['ano']);

        $this->db->select('depto, area, setor, cargo, funcao, contrato');
        $this->db->where('id', $post['id_usuario']);
        $row = $this->db->get('usuarios')->row_array();
        $data = array_merge($post, $row);

        $status = $this->db->update('alocacao_postos', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('alocacao_postos', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
