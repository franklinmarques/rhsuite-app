<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cursos_funcionario extends MY_Controller
{

    public function index()
    {
        if ($this->session->userdata('tipo') == 'empresa') {
            $usuario = $this->uri->rsegment(3, 0);
        } else {
            $usuario = null;
        }
        $row = $this->db->get_where('usuarios', array('id' => $usuario))->row();
        if ($row) {
            $data['row'] = $row;
        } else {
            redirect(site_url('home/funcionarios'));
        }

        $this->load->view('ead/cursosfuncionario', $data);
    }

    public function novo()
    {
        $this->db->select('id, nome');
        $this->db->where('id', $this->uri->rsegment(3, 0));
        $data['row'] = $this->db->get('usuarios')->row();

        $data['cursos'] = array('' => 'Selecione...');
        $sql = "SELECT a.id, a.nome 
                FROM cursos a
                WHERE a.id_empresa = {$this->session->userdata('empresa')} AND 
                      a.id NOT IN (SELECT b.id_curso 
                                   FROM cursos_usuarios b 
                                   WHERE b.id_usuario = {$data['row']->id})";
        $rows = $this->db->query($sql)->result();
        foreach ($rows as $row) {
            $data['cursos'][$row->id] = $row->nome;
        }

        $this->load->view('ead/novocursofuncionario', $data);
    }

    public function ajax_add()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $data['id_usuario'] = $this->uri->rsegment(3);
        $data['id_curso'] = $this->input->post('id_curso');
        if (empty($data['id_curso'])) {
            $data['id_curso'] = null;
        }
        $data['data_inicio'] = $this->input->post('data_inicio');
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        $data['data_maxima'] = $this->input->post('data_maxima');
        if ($data['data_maxima']) {
            $data['data_maxima'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_maxima'])));
        }
        if (($data['data_inicio'] && $data['data_maxima']) && $data['data_inicio'] > $data['data_maxima']) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de início deve ser igual ou menor do que a data de término!')));
        }
        $data['nota_aprovacao'] = $this->input->post('nota_aprovacao');
        $data['data_cadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        $data['nome'] = $this->input->post('nome');
        if (empty($data['nome'])) {
            $data['nome'] = null;
        }
        $data['tipo_treinamento'] = $this->input->post('tipo_treinamento');
        if (empty($data['tipo_treinamento'])) {
            $data['tipo_treinamento'] = null;
        }
        $data['local_treinamento'] = $this->input->post('local_treinamento');
        if (empty($data['local_treinamento'])) {
            $data['local_treinamento'] = null;
        }
        $data['carga_horaria_presencial'] = $this->input->post('carga_horaria_presencial');
        if ($data['carga_horaria_presencial']) {
            $data['carga_horaria_presencial'] = date("H:i", strtotime($data['carga_horaria_presencial']));
        } else {
            $data['carga_horaria_presencial'] = null;
        }
        $data['avaliacao_presencial'] = $this->input->post('avaliacao_presencial');
        if (empty($data['avaliacao_presencial'])) {
            $data['avaliacao_presencial'] = null;
        }
        $data['nome_fornecedor'] = $this->input->post('nome_fornecedor');
        if (empty($data['nome_fornecedor'])) {
            $data['nome_fornecedor'] = null;
        }
        $total = 0;
        $permissoes = 0;

        $verificausuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($verificausuario->empresa != $this->session->userdata('id')) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse usuário não é válido!')));
        }

        if (empty($data['id_curso']) and empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));
        }

        $verifcacurso = $this->db->query("SELECT a.* FROM cursos a INNER JOIN usuarios b ON b.id = a.id_empresa WHERE a.id = ? AND ((b.tipo = ? AND a.publico = ?) OR (b.id = ?))", array($data['id_curso'], "administrador", 0, $this->session->userdata('id')))->num_rows();
        $verificacursoliberado = $this->db->query("SELECT * FROM cursos_usuarios WHERE id_usuario = ?  AND id_curso = ?", array($this->session->userdata('id'), $data['id_curso']));

        if ($data['id_curso'] && $verifcacurso == 0 && $verificacursoliberado->num_rows() == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));
        }

        //Verificar quantidade de colaboradores
        foreach ($verificacursoliberado->result() as $row) {
            $permissoes = $row->colaboradores_maximo;
            $total += $this->db->query("SELECT * FROM cursos_usuarios WHERE id_usuario IN (SELECT id FROM usuarios WHERE empresa = ? OR id = ?) AND id_curso = ?", array($this->session->userdata('id'), $this->session->userdata('id'), $data['id_urso']))->num_rows();
        }

        $total -= 1;

        if ($data['id_curso'] && $permissoes <> 0 && $total >= $permissoes) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Número máximo de colaboradores excede ao limite contratado, para aumentar este número contate o gestor da plataforma via "Fale Conosco" ou envie um email para contato@peoplenetcorp.com.br')));
        }

        $verificacursofuncionario = $this->db->query("SELECT * FROM cursos_usuarios WHERE id_usuario = ? AND id_curso = ?", array($data['id_usuario'], $data['id_curso']));

        if ($data['id_curso'] && $verificacursofuncionario->num_rows() > 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento já está vinculado para esse funcionário!')));
        }

        if ($this->db->query($this->db->insert_string('cursos_usuarios', $data))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de treinamento para funcionário efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('ead/cursos_funcionario/index/' . $data['id_usuario'])));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar cadastro de treinamento para funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function ajax_list($id_usuario)
    {
        $post = $this->input->post();

        $sql = "SELECT s.id,
                       s.nome,
                       s.tipo,
                       s.local,
                       s.data_inicio,
                       s.data_maxima,
                       AVG(s.avaliacao_final) AS avaliacao_final,
                       s.data_inicio_de,
                       s.data_maxima_de,
                       s.nota_aprovacao,
                       s.id_curso
                FROM (SELECT a.id, 
                             CASE WHEN b.id IS NOT NULL 
                                  THEN b.nome ELSE a.nome END AS nome,
                             CASE a.tipo_treinamento 
                                  WHEN 'P' THEN 'Presencial' 
                                  WHEN 'E' THEN 'EAD' 
                                  ELSE 'EAD' END AS tipo,
                             CASE a.local_treinamento 
                                  WHEN 'I' THEN 'Interno' 
                                  WHEN 'E' THEN 'Externo' 
                                  ELSE 'Online' END AS local,
                             a.data_inicio,
                             a.data_maxima,
                             CASE WHEN b.id IS NULL 
                                  THEN a.avaliacao_presencial
                                  ELSE ROUND(SUM(j.peso) * 100 / SUM(k.peso), 2) 
                                  END AS avaliacao_final,
                             DATE_FORMAT(a.data_inicio,'%d/%m/%Y') AS data_inicio_de,
                             DATE_FORMAT(a.data_maxima,'%d/%m/%Y') AS data_maxima_de,
                             a.nota_aprovacao,
                             a.id_curso
                      FROM cursos_usuarios a
                      LEFT JOIN cursos b
                                ON b.id = a.id_curso
                      LEFT JOIN cursos_paginas d ON 
                                 d.id_curso = b.id 
                      LEFT JOIN cursos_questoes e ON
                                e.id_pagina = d.id AND 
                                (e.tipo = 1 OR e.tipo = 3) AND
                                (d.modulo = 'quiz' OR d.modulo = 'atividades') 
                      LEFT JOIN cursos_alternativas g ON 
                                  g.id_questao = e.id
                      LEFT JOIN cursos_acessos h ON 
                                h.id_curso_usuario = a.id AND 
                                h.id_pagina = e.id_pagina AND 
                                h.data_finalizacao IS NOT NULL
                      LEFT JOIN cursos_resultado i ON 
                                i.id_acesso = h.id AND 
                                i.id_questao = e.id AND 
                                i.id_alternativa = g.id
                      LEFT JOIN cursos_alternativas j ON
                                j.id = i.id_alternativa 
                      LEFT JOIN (SELECT id, MAX(peso) AS peso FROM cursos_alternativas GROUP BY id_questao) k ON
                                k.id = g.id
                      WHERE a.id_usuario = {$id_usuario}
                      GROUP BY a.id
                      ORDER BY b.nome ASC) s 
                  GROUP BY s.id";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.tipo', 's.local', 's.data_inicio_de', 's.data_maxima_de', 's.avaliacao_final');
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
        foreach ($list as $treinamento) {
            $row = array();
            $row[] = $treinamento->nome;
            $row[] = $treinamento->tipo;
            $row[] = $treinamento->local;
            $row[] = $treinamento->data_inicio_de;
            $row[] = $treinamento->data_maxima_de;
            $row[] = number_format($treinamento->avaliacao_final, 2, ',', '');

            if ($treinamento->avaliacao_final < $treinamento->nota_aprovacao or empty($treinamento->id_curso)) {
                $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_treinamento(' . $treinamento->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_treinamento(' . $treinamento->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                      <a class="btn btn-sm btn-primary" href="' . site_url('ead/treinamento/status/' . $treinamento->id) . '" title="Excluir"><i class="glyphicon glyphicon-align-center"></i> Andamento</a>
                      <button class="btn btn-sm btn-primary disabled"><i class="fa fa-lock"></i><span class="hidden-xs hidden-sm"> Certificado</span></button>
                     ';
            } else {
                $row[] = '
                      <button type="button" class="btn btn-sm btn-info" onclick="edit_treinamento(' . $treinamento->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                      <button type="button" class="btn btn-sm btn-danger" onclick="delete_treinamento(' . $treinamento->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                      <a class="btn btn-sm btn-primary" href="' . site_url('ead/treinamento/status/' . $treinamento->id) . '" title="Excluir"><i class="glyphicon glyphicon-align-center"></i> Andamento</a>
                      <a class="btn btn-sm btn-primary"  href="' . site_url('ead/treinamento/certificado/' . $treinamento->id) . '"target="_blank"><i class="fa fa-print"></i><span class="hidden-xs hidden-sm"> Certificado</span></a>
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
        //output to json format
        echo json_encode($output);
    }

    public function getCursos($id_usuario, $id_curso_usuario = '')
    {
        $empresa = $this->session->userdata('empresa');

        $this->db->select('a.id, a.id_curso, a.nota_aprovacao, a.tipo_treinamento, a.local_treinamento');
        $this->db->select('a.nome_fornecedor, a.avaliacao_presencial');
        $this->db->select("IF(b.id IS NOT NULL, b.nome, a.nome) AS nome", false);
        $this->db->select("DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio", false);
        $this->db->select("DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima", false);
        $this->db->select("TIME_FORMAT(a.carga_horaria_presencial, '%H:%i') AS carga_horaria_presencial", false);
        $this->db->join('usuarios c', 'c.id = a.id_usuario');
        $this->db->join('cursos b', 'b.id = a.id_curso', 'left');
        $this->db->where('a.id', $id_curso_usuario);
        $this->db->where('c.empresa', $empresa);
        $this->db->group_by('a.id');
        $curso_usuario = $this->db->get('cursos_usuarios a')->row();

        $id_curso = $curso_usuario->id_curso ?? '';
        $data['id'] = $curso_usuario->id ?? null;
        $data['nome'] = $curso_usuario->nome ?? null;
        $data['tipo_treinamento'] = $curso_usuario->tipo_treinamento ?? null;
        $data['local_treinamento'] = $curso_usuario->local_treinamento ?? null;
        $data['data_inicio'] = $curso_usuario->data_inicio ?? null;
        $data['data_maxima'] = $curso_usuario->data_maxima ?? null;
        $data['nota_aprovacao'] = $curso_usuario->nota_aprovacao ?? null;
        $data['carga_horaria_presencial'] = $curso_usuario->carga_horaria_presencial ?? null;
        $data['avaliacao_presencial'] = $curso_usuario->avaliacao_presencial ?? null;
        $data['nome_fornecedor'] = $curso_usuario->nome_fornecedor ?? null;

        $this->db->select('a.id, a.nome');
        $this->db->join('cursos_usuarios b', "b.id_curso = a.id AND b.id_usuario = {$id_usuario}", 'left');
        $this->db->where('a.id_empresa =', $empresa);
        if ($id_curso_usuario) {
            $this->db->where("(b.id IS NULL OR b.id_curso = {$id_curso_usuario})");
        } else {
            $this->db->where('b.id', null);
        }
        if ($id_curso) {
            $this->db->or_where('a.id', $id_curso);
            $data['nome'] = '';
        } else {
            $data['nome'] = $curso_usuario->nome ?? null;
        }
        $this->db->group_by('a.id');
        $this->db->order_by('a.nome', 'ASC');
        $rows = $this->db->get('cursos a')->result();

        $options = array('' => 'selecione...');
        foreach ($rows as $row) {
            $options[$row->id] = $row->nome;
        }

        $data['cursos'] = form_dropdown('id_curso', $options, $id_curso, 'class="form-control"');
        echo json_encode($data);
    }

    public function editar()
    {
        $this->db->select('a.id, a.id_usuario, b.nome, a.id_curso, a.tipo_treinamento, a.local_treinamento, a.nota_aprovacao');
        $this->db->select("CASE a.tipo_treinamento WHEN 'P' THEN a.nome ELSE c.nome END AS nome_curso", false);
        $this->db->select('a.carga_horaria_presencial, a.avaliacao_presencial, a.nome_fornecedor');
        $this->db->select("DATE_FORMAT(a.data_inicio, '%d/%m/%Y') AS data_inicio", false);
        $this->db->select("DATE_FORMAT(a.data_maxima, '%d/%m/%Y') AS data_maxima", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('cursos c', 'c.id = a.id_curso', 'left');
        $this->db->where('a.id', $this->uri->rsegment(3, 0));
        $data['row'] = $this->db->get('cursos_usuarios a')->row();
        $data['cursos'] = array('' => 'Selecione...');

        $sql = "SELECT a.id, a.nome 
                FROM cursos a
                WHERE a.id_empresa = {$this->session->userdata('empresa')} AND 
                      a.id NOT IN (SELECT b.id_curso 
                                   FROM cursos_usuarios b 
                                   WHERE b.id_usuario = {$data['row']->id_usuario} AND 
                                         b.id != {$data['row']->id})";
        $rows = $this->db->query($sql)->result();
        foreach ($rows as $row) {
            $data['cursos'][$row->id] = $row->nome;
        }

        $this->load->view('ead/editarcursofuncionario', $data);
    }

    public function ajax_update()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        $id = $this->input->post('id');
        $data['id_usuario'] = $this->uri->rsegment(3);
        $data['id_curso'] = $this->input->post('id_curso');
        if (empty($data['id_curso'])) {
            $data['id_curso'] = null;
        }
        $data['data_inicio'] = $this->input->post('data_inicio');
        if ($data['data_inicio']) {
            $data['data_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_inicio'])));
        }
        $data['data_maxima'] = $this->input->post('data_maxima');
        if ($data['data_maxima']) {
            $data['data_maxima'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_maxima'])));
        }
        if (($data['data_inicio'] && $data['data_maxima']) && $data['data_inicio'] > $data['data_maxima']) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de início deve ser igual ou menor do que a data de término!')));
        }
        $data['nota_aprovacao'] = $this->input->post('nota_aprovacao');
        $data['nome'] = $this->input->post('nome');
        if (empty($data['nome'])) {
            $data['nome'] = null;
        }
        $data['tipo_treinamento'] = $this->input->post('tipo_treinamento');
        if (empty($data['tipo_treinamento'])) {
            $data['tipo_treinamento'] = null;
        }
        $data['local_treinamento'] = $this->input->post('local_treinamento');
        if (empty($data['local_treinamento'])) {
            $data['local_treinamento'] = null;
        }
        $data['carga_horaria_presencial'] = $this->input->post('carga_horaria_presencial');
        if ($data['carga_horaria_presencial']) {
            $data['carga_horaria_presencial'] = date('H:i', strtotime($data['carga_horaria_presencial']));
        } else {
            $data['carga_horaria_presencial'] = null;
        }
        $data['avaliacao_presencial'] = $this->input->post('avaliacao_presencial');
        if (empty($data['avaliacao_presencial'])) {
            $data['avaliacao_presencial'] = null;
        }
        $data['nome_fornecedor'] = $this->input->post('nome_fornecedor');
        if (empty($data['nome_fornecedor'])) {
            $data['nome_fornecedor'] = null;
        }

        $total = 0;
        $permissoes = 0;

        $verificausuario = $this->db->query("SELECT * FROM usuarios WHERE id = ?", array($this->uri->rsegment(3)))->row(0);

        if ($verificausuario->empresa != $this->session->userdata('id')) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse usuário não é válido!')));
        }

        if (empty($data['id_curso']) and empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Selecione o campo "Treinamento"')));
        }

        $verifcacurso = $this->db->query("SELECT a.* FROM cursos a INNER JOIN usuarios b ON b.id = a.id_empresa WHERE a.id = ? AND ((b.tipo = ? AND a.publico = ?) OR (b.id = ?))", array($data['id_curso'], "administrador", 0, $this->session->userdata('id')))->num_rows();
        $verificacursoliberado = $this->db->query("SELECT * FROM cursos_usuarios WHERE id = ?", array($id));

        if ($data['id_curso'] && $verifcacurso == 0 && $verificacursoliberado->num_rows() == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Esse treinamento não é válido!')));
        }

        //Verificar quantidade de colaboradores
        foreach ($verificacursoliberado->result() as $row) {
            $permissoes += $row->colaboradores_maximo;
            $total += $this->db->query("SELECT * FROM cursos_usuarios WHERE id_usuario IN (SELECT id FROM usuarios WHERE empresa = ? OR id = ?) AND id_curso = ?", array($this->session->userdata('id'), $this->session->userdata('id'), $data['id_curso']))->num_rows();
        }

        $total -= 1;

        if ($data['id_curso'] && $permissoes <> 0 && $total >= $permissoes) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Número máximo de colaboradores excede ao limite contratado, para aumentar este número contate o gestor da plataforma via "Fale Conosco" ou envie um email para contato@peoplenetcorp.com.br')));
        }

        $verificacursofuncionario = $this->db->query("SELECT id_curso FROM cursos_usuarios WHERE id = ?", array($id))->row(0);

        if ($verificacursofuncionario->id_curso != $data['id_curso']) {
            $data['data_cadastro'] = mdate("%Y-%m-%d %H:%i:%s");
        }

        if ($this->db->query($this->db->update_string('cursos_usuarios', $data, array('id' => $id)))) {
            echo json_encode(array('retorno' => 1, 'aviso' => 'Edição de treinamento para funcionário efetuado com sucesso', 'redireciona' => 1, 'pagina' => site_url('ead/cursos_funcionario/index/' . $data['id_usuario'])));
        } else {
            echo json_encode(array('retorno' => 0, 'aviso' => 'Erro ao efetuar edição de treinamento para funcionário, tente novamente, se o erro persistir entre em contato com o administrador'));
        }
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete("cursos_usuarios", array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function enviarEmail()
    {
        $id_usuario = $this->input->post('id');
        $mensagem = $this->input->post('mensagem');

        $this->load->helper(array('date'));

        $email['titulo'] = 'E-mail de convocação para Treinamentoo';
        $email['remetente'] = $this->session->userdata('id');
        $email['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

        $status = true;

        $this->db->select('a.id_usuario, b.nome, b.email');
        $this->db->select("DATE_FORMAT(MIN(a.data_inicio), '%d/%m/%Y') AS data_inicio", false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        if ($id_usuario) {
            $this->db->where('a.id_usuario', $id_usuario);
        }
        $this->db->where('a.data_maxima <= NOW()');
        $this->db->group_by('a.id_usuario');
        $destinatarios = $this->db->get('cursos_usuarios a')->result();

        $this->db->select("a.nome, a.email, IFNULL(b.email, a.email) AS email_empresa", false);
        $this->db->join('usuarios b', 'b.id = a.empresa', 'left');
        $this->db->where('a.id', $this->session->userdata('id'));
        $remetente = $this->db->get('usuarios a')->row();

        $this->load->library('email');

        foreach ($destinatarios as $destinatario) {
            if ($mensagem) {
                $email['mensagem'] = $mensagem;
            } else {
                $email['mensagem'] = "Caro colaborador, você está convocado para realizar treinamento na data de: {$destinatario->data_programada}. Favor verificar com o Departamento de Gestão de Pessoas";
            }

            $this->email->from($remetente->email, $remetente->nome);
            $this->email->to($destinatario->email);
            $this->email->cc($remetente->email_empresa);
            $this->email->bcc('contato@rhsuite.com.br');

            $this->email->subject($email['titulo']);
            $this->email->message($email['mensagem']);

            if ($this->email->send()) {
                $email['destinatario'] = $destinatario->id_usuario;
                $this->db->query($this->db->insert_string('mensagensrecebidas', $email));
                $this->db->query($this->db->insert_string('mensagensenviadas', $email));
            } else {
                $status = false;
            }

            $this->email->clear();
        }

        echo json_encode(array('status' => $status));
    }

}
