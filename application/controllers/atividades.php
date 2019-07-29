<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atividades extends MY_Controller
{

    public function index()
    {
        $this->db->select('nome, id');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'ASC');
        $avaliadores = $this->db->get('usuarios')->result();

        $data['id_usuario'] = array();
        foreach ($avaliadores as $avaliador) {
            $data['id_usuario'][$avaliador->id] = $avaliador->nome;
        }

        $sql = "SELECT a.prioridade, 
                       a.status, 
                       a.id_usuario, 
                       a.data_cadastro, 
                       a.data_lembrete, 
                       a.data_limite, 
                       1 AS nivel
                FROM atividades a 
                WHERE a.id_usuario = {$this->session->userdata('empresa')} 
                UNION
                SELECT b.prioridade, 
                       b.status, 
                       b.id_usuario, 
                       b.data_cadastro, 
                       b.data_lembrete,
                       b.data_limite,
                       2 AS nivel
                FROM atividades b 
                INNER JOIN atividades c on c.id = b.id_mae 
                WHERE c.id_usuario = {$this->session->userdata('empresa')}";

        $sql_prioridade = "SELECT DISTINCT(s.prioridade) AS id,
                                  CASE s.prioridade 
                                       WHEN 0 THEN 'Baixa'
                                       WHEN 1 THEN 'Média'
                                       WHEN 2 THEN 'Alta' END AS nome 
                           FROM($sql) s 
                           ORDER BY s.prioridade DESC";
        $prioridades = $this->db->query($sql_prioridade)->result();
        $data['prioridades'] = array('' => 'Todas');
        foreach ($prioridades as $prioridade) {
            $data['prioridades'][$prioridade->id] = $prioridade->nome;
        }

        $sql_status = "SELECT DISTINCT(CASE WHEN s.status = 1 THEN '1' 
                                            WHEN CURDATE() BETWEEN s.data_lembrete AND s.data_limite THEN '2' 
                                            WHEN CURDATE() > s.data_limite THEN '3' 
                                            ELSE s.status END) AS id,
                              CASE WHEN s.status = 1 THEN 'Finalizados' 
                                   WHEN CURDATE() BETWEEN s.data_lembrete AND s.data_limite THEN 'Próximos à data limite' 
                                   WHEN CURDATE() > s.data_limite THEN 'Limite expirado' 
                                   ELSE 'Não-finalizados' END AS nome 
                           FROM($sql) s 
                           ORDER BY s.status ASC";
        $status = $this->db->query($sql_status)->result();
        $data['status'] = array('' => 'Todos');
        foreach ($status as $row) {
            $data['status'][$row->id] = $row->nome;
        }

        $sql_usuario = "SELECT DISTINCT(s.id_usuario) AS id,
                               CONCAT(CASE WHEN s.nivel = 1 THEN '' ELSE '&squarf; ' END, u.nome) AS nome
                           FROM($sql) s 
                           INNER JOIN usuarios u ON
                                      u.id = s.id_usuario";
        $usuarios = $this->db->query($sql_usuario)->result();
        $data['usuarios'] = array('' => 'Todos');
        foreach ($usuarios as $usuario) {
            $data['usuarios'][$usuario->id] = $usuario->nome;
        }

        $sql_data_atividade = "SELECT DATE_FORMAT(MIN(s.data_cadastro), '%d/%m/%Y') AS data_inicio,
                                      DATE_FORMAT(MAX(s.data_limite), '%d/%m/%Y') AS data_termino
                               FROM($sql) s";
        $data_atividade = $this->db->query($sql_data_atividade)->row();
        $data['data_inicio'] = $data_atividade->data_inicio;
        $data['data_termino'] = $data_atividade->data_termino;

        $data['id'] = $this->session->userdata('id');
        $data['tipo'] = $this->session->userdata('tipo');
        $this->load->view('atividades', $data);
    }


    public function ajax_list()
    {
        $id = $this->session->userdata('id');


        $post = $this->input->post();


        $sql = "SELECT a.id, 
                       a.atividade,
                       a.prioridade,
                       CASE WHEN a.status = 1 THEN '1' 
                            WHEN CURDATE() BETWEEN a.data_lembrete AND a.data_limite THEN '2' 
                            WHEN CURDATE() > a.data_limite THEN '3' 
                            ELSE a.status END AS status,
                       b.nome, 
                       a.data_cadastro,
                       a.data_limite,
                       a.data_fechamento,
                       a.tipo,
                       DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS data_cadastro_de,
                       DATE_FORMAT(a.data_limite, '%d/%m/%Y') AS data_limite_de,
                       DATE_FORMAT(a.data_fechamento, '%d/%m/%Y') AS data_fechamento_de,
                       a.id_mae,
                       a.id_usuario,
                       a.observacoes,
                       d.nome AS nome_mae,
                       CASE WHEN c.id_usuario IS NULL 
                            THEN a.id 
                            ELSE a.id_mae END AS ordem_mae,
                       CASE WHEN c.id IS NULL 
                            THEN 0 
                            ELSE a.id END AS ordem_filha,
                       (SELECT CASE WHEN COUNT(f.id_mae) > 0 
                                    THEN 1 
                                    ELSE 0 END
                        FROM atividades m 
                        LEFT JOIN atividades f ON 
                                  f.id_mae = m.id 
                        WHERE m.id = a.id AND 
                              f.id_mae IS NOT NULL) AS possui_filha,
                       (SELECT CASE WHEN COUNT(f.id) > 0 
                                    THEN 0 
                                    ELSE 1 END
                        FROM atividades m 
                        LEFT JOIN atividades f ON 
                                  f.id_mae = m.id 
                        WHERE m.id = a.id AND 
                              f.status = 0) AS filhas_finalizadas
                FROM atividades a
                INNER JOIN usuarios b ON 
                           b.id = a.id_usuario
                LEFT JOIN atividades c ON
                          c.id = a.id_mae
                LEFT JOIN usuarios d ON 
                          d.id = c.id_usuario
                WHERE 1";
        if ($post['prioridade']) {
            $sql .= " AND (a.prioridade = {$post['prioridade']} OR c.prioridade = {$post['prioridade']})";
        }
        if ($post['status'] == 1) {
            $sql .= " AND (a.status = {$post['status']} OR c.status = {$post['status']})";
        } elseif ($post['status'] == 2) {
            $sql .= " AND (CURDATE() BETWEEN a.data_lembrete AND a.data_limite OR CURDATE() BETWEEN c.data_lembrete AND c.data_limite)";
        } elseif ($post['status'] == 3) {
            $sql .= " AND (CURDATE() > a.data_limite OR CURDATE() > c.data_limite)";
        }
        if ($post['data_inicio']) {
            $data_inicio = date('Y-m-d', strtotime('-1 days', strtotime(str_replace('/', '-', $post['data_inicio']))));
            $sql .= " AND (a.data_cadastro > '{$data_inicio}' OR c.data_cadastro > '{$data_inicio}')";
        }
        if ($post['data_termino']) {
            $data_termino = date('Y-m-d', strtotime('+1 days', strtotime(str_replace('/', '-', $post['data_termino']))));
            $sql .= " AND (a.data_limite < '{$data_termino}' OR c.data_limite < '{$data_termino}')";
        }
        if ($post['usuario'] == $id) {
            $sql .= " AND a.id_usuario = {$id}";
        } elseif ($post['usuario']) {
            $sql .= " AND c.id_usuario = {$post['usuario']}";
        } else {
            $sql .= " AND (a.id_usuario = {$id} OR c.id_usuario = {$id})";
        }


        $this->load->library('dataTables');

        $output = $this->datatables->query($sql);

        $data = array();

        foreach ($output->data as $row) {
            $acao = '';

            if ($this->agent->is_mobile == false) {
                if ($row->status === '1') {
                    $acao .= '<button class="btn btn-sm btn-info disabled"><i class="glyphicon glyphicon-pencil"></i></button> ';
                } else {
                    if ($row->id_mae) {
                        $acao .= '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_atividade_filha(' . "'" . $row->id . "'" . ',)"><i class="glyphicon glyphicon-pencil"></i></a> ';
                    } else {
                        $acao .= '<a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_atividade_mae(' . "'" . $row->id . "'" . ',)"><i class="glyphicon glyphicon-pencil"></i></a> ';
                    }
                }
                if ($row->status === '1' or $row->id_mae) {
                    $acao .= ' <button class="btn btn-sm btn-info disabled"><i class="glyphicon glyphicon-plus"></i></button>';
                } else {
                    $acao .= ' <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Cadastrar atividade(s) filha(s)" onclick="add_atividade_filha(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i></a>';
                }
                if ($row->status === '1') {
                    $acao .= ' <a class="btn btn-sm btn-danger disabled" href="javascript:void(0)" title="Excluir"><i class="glyphicon glyphicon-trash"></i></a>';
                } else {
                    $acao .= ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_atividade(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>';
                }
            }
//            if ($row->status === '0' and $row->filhas_finalizadas === '1') {
            if ($row->status === '1') {
                $acao .= ' <button class="btn btn-sm btn-success disabled"><i class="glyphicon glyphicon-ok"></i></button>';
            } else {
                $acao .= ' <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Finalizar" onclick="finaliza_atividade(' . "'" . $row->id . "'" . ')"><i class="glyphicon glyphicon-ok"></i></a>';
            }

            $idMae = $row->id_mae ? '' : $row->id;
            $idFilha = $row->id_mae ? '<strong>(' . $row->id_mae . ' - ' . $row->nome_mae . ')</strong><br>' . $row->id . ' - ' : '';

            $data[] = array(
                $idMae,
                $idFilha . $row->atividade . (strlen($row->observacoes) ? '<p style="margin: 8px 0 0 15px;">Obs.: ' . $row->observacoes . '</p>' : ''),
                $row->prioridade,
                $row->status,
                $row->nome,
                $row->data_cadastro_de,
                $row->data_limite_de,
                $row->data_fechamento_de,
                $acao,
                $row->id_mae,
                $row->id_usuario,
                $row->possui_filha
            );
        }

        $output->data = $data;


        echo json_encode($output);
    }


    public function ajax_edit()
    {
        $data = $this->db
            ->select('id, id_usuario, atividade, prioridade, tipo, observacoes, id_mae')
            ->select(["DATE_FORMAT(data_limite, '%d/%m/%Y') AS data_limite"], false)
            ->select(["DATEDIFF(data_limite, data_lembrete) AS data_lembrete"], false)
            ->where('id', $this->input->post('id'))
            ->get('atividades')
            ->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Atividade não encontrada ou excluída recentemente.']));
        }

        echo json_encode($data);
    }


    public function ajax_add_mae()
    {
        $data = $this->input->post();

        if (empty($data['id_usuario'])) {
            $data['id_usuario'] = $this->session->userdata('id');
        }

        $data['id_mae'] = null;

        $data['data_cadastro'] = date('Y-m-d H:i:s');
        if ($data['data_limite'] and $data['data_lembrete']) {
            $data_limite = strtotime(str_replace('/', '-', $data['data_limite']));
            $data['data_limite'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['data_limite'] . ' 23:59:59')));
            $data['data_lembrete'] = date('Y-m-d', strtotime("-{$data['data_lembrete']} days", $data_limite));
        }

        if (strlen($data['observacoes']) == 0) {
            $data['observacoes'] = null;
        }

        $this->db->trans_start();
        $this->db->insert('atividades', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível cadastrar a atividade mãe.']));
        }

        echo json_encode(['status' => true]);
    }


    public function ajax_add_filha()
    {
        $data = $this->input->post();

        if (empty($data['id_mae'])) {
            exit(json_encode(['erro' => 'Atividade mãe inválida ou excluída recentemente.']));
        }

        if (!isset($data['id_usuario'])) {
            exit(json_encode(['erro' => 'Nenhum(a) colaborador(a) selecionado(a).']));
        }


        $data['data_cadastro'] = date('Y-m-d H:i:s');
        if ($data['data_limite'] and $data['data_lembrete']) {
            $data_limite = strtotime(str_replace('/', '-', $data['data_limite']));
            $data['data_limite'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['data_limite'] . ' 23:59:59')));
            $data['data_lembrete'] = date('Y-m-d', strtotime("-{$data['data_lembrete']} days", $data_limite));
        }

        if (!empty($data['observacoes']) == false) {
            $data['observacoes'] = null;
        }

        $arr_usuario = $data['id_usuario'];

        $arr_data = [];
        foreach ($arr_usuario as $id_usuario) {
            $data['id_usuario'] = $id_usuario;
            $arr_data[] = $data;
        }

        $this->db->trans_start();
        $this->db->insert_batch('atividades', $arr_data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível cadastrar a atividade filha.']));
        }

        $this->notificar($arr_data);

        echo json_encode(['status' => true]);
    }


    private function notificar(array $rows)
    {
        if (empty($rows)) {
            return;
        }

        $usuarios = $this->db->select('id, email')
            ->where_in('id', array_column($rows, 'id_usuario'))
            ->get('usuarios')
            ->result();

        $emails = [];

        foreach ($usuarios as $usuario) {
            $emails[$usuario->id] = $usuario->email;
        }

        $data = [
            'nome' => $this->session->userdata('nome'),
            'email' => $this->session->userdata('email')
        ];

        $this->load->library('email');

        foreach ($rows as $row) {
            $data['atividade'] = $row['atividade'];
            $data['dataLimite'] = date('d/m/Y', strtotime($row['data_limite']));

            $this->email
                ->from('contato@rhsuite.com.br', 'RhSuite')
                ->to($emails[$row['id_usuario']])
                ->subject('LMS - Atribuição de atividade')
                ->message($this->load->view('atividades_email', $data, true))
                ->send();
        }
    }


    public function ajax_update()
    {
        $data = $this->input->post();

        if (empty($data['id_mae'])) {
            $data['id_mae'] = null;
        }

        if ($data['data_limite'] and $data['data_lembrete']) {
            $data_limite = strtotime(str_replace('/', '-', $data['data_limite']));
            $data['data_limite'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['data_limite'] . ' 23:59:59')));
            $data['data_lembrete'] = date('Y-m-d', strtotime("-{$data['data_lembrete']} days", $data_limite));
        }

        if (strlen($data['observacoes']) == 0) {
            $data['observacoes'] = null;
        }

        $this->db->trans_start();
        $this->db->update('atividades', $data, ['id' => $data['id']]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível alterar a atividade.']));
        }

        echo json_encode(['status' => true]);
    }


    public function ajax_delete()
    {
        $this->db->trans_start();
        $this->db->delete('atividades', ['id' => $this->input->post('id')]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível excluir a atividade.']));
        }

        echo json_encode(['status' => true]);
    }


    public function ajax_finalizar()
    {
        $id = $this->input->post('id');

        $this->db->trans_start();
        $this->db->update('atividades', ['data_fechamento' => date('Y-m-d H:i:s'), 'status' => 1], ['id' => $id]);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            exit(json_encode(['erro' => 'Não foi possível finalizar a atividade.']));
        }
        echo json_encode(['status' => true]);
    }


    public function avaliaAtividade()
    {
        header('Content-type: text/json');
        $this->load->helper(array('date'));

        //Verifica o POST
        if (isset($_POST) && !empty($_POST)) {
            //Define variáveis
            $data = array();
            $pagina = 0;
            $curso = 0;
            $atividade['total'] = 1;

            //Pega os dados do POST
            foreach ($_POST as $indice => $valor) {
                if ($indice == 'pergunta') {
                    foreach ($valor as $key => $value) {
                        $data[$key] = $value;
                    }
                } else {
                    $$indice = $valor;
                }
            }

            //Seta valores do POST
            $dados['usuario'] = $this->session->userdata['id'];
            $dados['curso'] = $curso;
            $dados['pagina'] = $pagina;
            $dados['datacadastro'] = mdate("%Y-%m-%d %H:%i:%s");

            //Verifica página e curso
            if ($pagina > 0 && $curso > 0) {

                $cursos = $this->db->query("SELECT p.curso, p.id AS pagina, c.usuario,
                                            (SELECT COUNT(*) FROM usuariosatividades pg
                                              WHERE pg.curso = c.curso
                                              AND pg.pagina = p.id
                                              AND pg.usuario = pg.usuario
                                            ) AS total
                                            FROM paginas p
                                            INNER JOIN usuarioscursos c ON c.curso = p.curso AND c.usuario = ?
                                            WHERE p.curso = ? AND p.id = ?", array($this->session->userdata['id'], $curso, $pagina));

                //Pega total do banco
                foreach ($cursos->result() as $row) {
                    foreach ($row as $key => $value) {
                        $atividade[$key] = $value;
                    }
                }

                //Verifica a permissão do usuário
                if ($cursos->num_rows() > 0 && $atividade['total'] < 1) {
                    foreach ($data as $key => $value) {
                        $dados['atividade'] = $key;
                        $dados['status'] = $value;

                        //Insere no banco os dados
                        $this->db->query($this->db->insert_string('usuariosatividades', $dados));

                        //Informa o resultado
                        echo json_encode('Atividade finalizada com sucesso!');
                    }
                } else {
                    echo json_encode('Atividade realizada anteriormente!');
                }
            }
        }
    }


    public function relatorio($isPdf = false)
    {
        $id = $this->session->userdata('id');
        $get = $this->input->get();
        $observacoes = $get['observacoes'] ?? '';

        $sql = "SELECT (CASE WHEN a.id_mae IS NULL 
                             THEN a.id END) AS id,
                       (CASE WHEN a.id_mae IS NOT NULL 
                             THEN CONCAT(a.id, ' - ', b.nome)
                             ELSE b.nome END) AS nome,
                       CONCAT_WS('/', b.depto, b.area, b.setor) AS estrutura,
                       CASE a.prioridade 
                            WHEN 2 THEN 'AL'
                            WHEN 1 THEN 'MD'
                            ELSE 'BX' END AS prioridade,
                       a.tipo,
                       CASE WHEN a.status = 1 THEN 'F' 
                            WHEN CURDATE() BETWEEN a.data_lembrete AND a.data_limite THEN 'DL' 
                            WHEN CURDATE() > a.data_limite THEN 'L' 
                            ELSE 'NF' END AS status,
                       a.atividade,
                       a.id_mae,
                       IF(CHAR_LENGTH('{$observacoes}'), a.observacoes, NULL) AS observacoes,
                       DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS data_cadastro,
                       DATE_FORMAT(a.data_limite, '%d/%m/%Y') AS data_limite,
                       DATE_FORMAT(a.data_fechamento, '%d/%m/%Y') AS data_fechamento,
                       CASE WHEN c.id_usuario IS NULL 
                            THEN a.id 
                            ELSE a.id_mae END AS ordem_mae,
                       CASE WHEN c.id IS NULL 
                            THEN 0 
                            ELSE a.id END AS ordem_filha
                      FROM atividades a
                      INNER JOIN usuarios b ON 
                                 b.id = a.id_usuario
                      LEFT JOIN atividades c ON
                                c.id = a.id_mae
                      WHERE 1";
        if (!empty($get['prioridade'])) {
            $sql .= " AND (a.prioridade = {$get['prioridade']} OR c.prioridade = {$get['prioridade']})";
        }
        if (isset($get['status'])) {
            if ($get['status'] == 1) {
                $sql .= " AND (a.status = {$get['status']} OR c.status = {$get['status']})";
            } elseif ($get['status'] == 2) {
                $sql .= " AND (CURDATE() BETWEEN a.data_lembrete AND a.data_limite OR CURDATE() BETWEEN c.data_lembrete AND c.data_limite)";
            } elseif ($get['status'] == 3) {
                $sql .= " AND (CURDATE() > a.data_limite OR CURDATE() > c.data_limite)";
            }
        }
        if (!empty($get['data_inicio'])) {
            $data_inicio = date('Y-m-d', strtotime('-1 days', strtotime(str_replace('/', '-', $get['data_inicio']))));
            $sql .= " AND (a.data_cadastro > '{$data_inicio}' OR c.data_cadastro > '{$data_inicio}')";
        }
        if (!empty($get['data_termino'])) {
            $data_termino = date('Y-m-d', strtotime('+1 days', strtotime(str_replace('/', '-', $get['data_termino']))));
            $sql .= " AND (a.data_limite < '{$data_termino}' OR c.data_limite < '{$data_termino}')";
        }
        if (isset($get['status'])) {
            if ($get['status'] == 1) {
                $sql .= " AND (a.status = {$get['status']} OR c.status = {$get['status']})";
            } elseif ($get['status'] == 2) {
                $sql .= " AND (CURDATE() BETWEEN a.data_lembrete AND a.data_limite OR CURDATE() BETWEEN c.data_lembrete AND c.data_limite)";
            } elseif ($get['status'] == 3) {
                $sql .= " AND (CURDATE() > a.data_limite OR CURDATE() > c.data_limite)";
            }
        }
        if (!empty($get['usuario'])) {
            if ($get['usuario'] == $id) {
                $sql .= " AND a.id_usuario = {$id}";
            } else {
                $sql .= " AND c.id_usuario = {$get['usuario']}";
            }
        } else {
            $sql .= " AND (a.id_usuario = {$id} OR c.id_usuario = {$id})";
        }

        $data['rows'] = $this->db->query($sql)->result();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $this->db->select("nome, CONCAT_WS('/', depto, area, setor) AS estrutura", false);
        $this->db->where('id', $this->session->userdata('id'));
        $data['usuario'] = $this->db->get('usuarios')->row();
        if (empty($data['usuario']->estrutura)) {
            $data['usuario']->estrutura = 'Todos';
        }

        $data['is_pdf'] = $isPdf === true;

        if ($data['is_pdf']) {
            return $this->load->view('atividadesPdf', $data, true);
        }


        $sql = "SELECT a.prioridade, 
                       a.status, 
                       a.id_usuario, 
                       a.data_cadastro, 
                       a.data_lembrete, 
                       a.data_limite, 
                       1 AS nivel
                FROM atividades a 
                WHERE a.id_usuario = {$this->session->userdata('empresa')} 
                UNION
                SELECT b.prioridade, 
                       b.status, 
                       b.id_usuario, 
                       b.data_cadastro, 
                       b.data_lembrete,
                       b.data_limite,
                       2 AS nivel
                FROM atividades b 
                INNER JOIN atividades c on c.id = b.id_mae 
                WHERE c.id_usuario = {$this->session->userdata('empresa')}";

        $sql_prioridade = "SELECT DISTINCT(s.prioridade) AS id,
                                  CASE s.prioridade 
                                       WHEN 0 THEN 'Baixa'
                                       WHEN 1 THEN 'Média'
                                       WHEN 2 THEN 'Alta' END AS nome 
                           FROM($sql) s 
                           ORDER BY s.prioridade DESC";
        $prioridades = $this->db->query($sql_prioridade)->result();
        $data['prioridades'] = array('' => 'Todas');
        foreach ($prioridades as $prioridade) {
            $data['prioridades'][$prioridade->id] = $prioridade->nome;
        }

        $sql_status = "SELECT DISTINCT(CASE WHEN s.status = 1 THEN '1' 
                                            WHEN CURDATE() BETWEEN s.data_lembrete AND s.data_limite THEN '2' 
                                            WHEN CURDATE() > s.data_limite THEN '3' 
                                            ELSE s.status END) AS id,
                              CASE WHEN s.status = 1 THEN 'Finalizados' 
                                   WHEN CURDATE() BETWEEN s.data_lembrete AND s.data_limite THEN 'Próximos à data limite' 
                                   WHEN CURDATE() > s.data_limite THEN 'Limite expirado' 
                                   ELSE 'Não-finalizados' END AS nome 
                           FROM($sql) s 
                           ORDER BY s.status ASC";
        $status = $this->db->query($sql_status)->result();
        $data['status'] = array('' => 'Todos');
        foreach ($status as $row) {
            $data['status'][$row->id] = $row->nome;
        }

        $sql_usuario = "SELECT DISTINCT(s.id_usuario) AS id,
                               CONCAT(CASE WHEN s.nivel = 1 THEN '' ELSE '&squarf; ' END, u.nome) AS nome
                           FROM($sql) s 
                           INNER JOIN usuarios u ON
                                      u.id = s.id_usuario";
        $usuarios = $this->db->query($sql_usuario)->result();
        $data['usuarios'] = array('' => 'Todos');
        foreach ($usuarios as $usuario) {
            $data['usuarios'][$usuario->id] = $usuario->nome;
        }


        $this->load->view('atividades_relatorio', $data);
    }


    public function ajaxRelatorio()
    {
        $id = $this->session->userdata('id');
        $get = $this->input->post();


        $this->db->select('a.id, b.nome, a.atividade, a.id_mae');
        if (!empty($get['observacoes'])) {
            $this->db->select('a.observacoes');
        } else {
            $this->db->select('NULL AS observacoes', false);
        }
        $this->db->select("(CASE a.prioridade WHEN 2 THEN 'AL'WHEN 1 THEN 'MD' ELSE 'BX' END) AS prioridade", false);
        $this->db->select("(CASE WHEN a.status = 1 THEN 'F' 
                            WHEN CURDATE() BETWEEN a.data_lembrete AND a.data_limite THEN 'DL' 
                            WHEN CURDATE() > a.data_limite THEN 'L' 
                            ELSE 'NF' END) AS status", false);
        $this->db->select(["DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS data_cadastro"], false);
        $this->db->select(["DATE_FORMAT(a.data_limite, '%d/%m/%Y') AS data_limite"], false);
        $this->db->select(["DATE_FORMAT(a.data_fechamento, '%d/%m/%Y') AS data_fechamento"], false);
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->join('atividades c', 'c.id = a.id_mae', 'left');
        if (!empty($get['prioridade'])) {
            $this->db->where("(a.prioridade = {$get['prioridade']} OR c.prioridade = {$get['prioridade']})", null, false);
        }
        if (!empty($get['status'])) {
            if ($get['status'] == 1) {
                $this->db->where("(a.status = {$get['status']} OR c.status = {$get['status']})", null, false);
            } elseif ($get['status'] == 2) {
                $this->db->where("(CURDATE() BETWEEN a.data_lembrete AND a.data_limite OR CURDATE() BETWEEN c.data_lembrete AND c.data_limite)", null, false);
            } elseif ($get['status'] == 3) {
                $this->db->where("(CURDATE() > a.data_limite OR CURDATE() > c.data_limite)", null, false);
            }
        }
        if (!empty($get['data_inicio'])) {
            $data_inicio = date('Y-m-d', strtotime('-1 days', strtotime(str_replace('/', '-', $get['data_inicio']))));
            $this->db->where("(a.data_cadastro > '{$data_inicio}' OR c.data_cadastro > '{$data_inicio}')", null, false);
        }
        if (!empty($get['data_termino'])) {
            $data_termino = date('Y-m-d', strtotime('+1 days', strtotime(str_replace('/', '-', $get['data_termino']))));
            $this->db->where("(a.data_limite < '{$data_termino}' OR c.data_limite < '{$data_termino}')", null, false);
        }
        if (!empty($get['usuario'])) {
            if ($get['usuario'] == $id) {
                $this->db->where('a.id_usuario', $id);
            } else {
                $this->db->where('c.id_usuario', $get['usuario']);
            }
        } else {
            $this->db->where("(a.id_usuario = {$id} OR c.id_usuario = {$id})", null, false);
        }
        $query = $this->db->get('atividades a');


        $this->load->library('dataTables');

        $output = $this->datatables->generate($query);

        $data = array();

        foreach ($output->data as $row) {
            $idMae = $row->id_mae ? '' : $row->id;
            $idFilha = $row->id_mae ? '<strong>(' . $row->id_mae . ')</strong> ' . $row->id . ' - ' : '';
            $data[] = array(
                $idMae,
                $idFilha . $row->nome,
                $row->prioridade,
                $row->status,
                $row->atividade . (strlen($row->observacoes) ? '<p style="margin: 8px 0 0 15px;">Obs.: ' . $row->observacoes . '</p>' : ''),
                $row->data_cadastro,
                $row->data_limite,
                $row->data_fechamento,
                $row->id_mae
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#atividades thead th { font-size: 12px; padding: 4px 0px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#atividades thead tr, #frequencia tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#atividades tbody tr td { font-size: 12px; padding: 4px; 0px; } ';
        $stylesheet .= '#atividades tbody tr.dados_paciente td { padding: 0px; 0px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; } ';
        $stylesheet .= '#table tbody td:eq(0), #table tbody tr.active td { background-color: #f5f5f5; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->relatorio(true));

        $this->m_pdf->pdf->Output('Relatório de Pendências.pdf', 'D');
    }

}
