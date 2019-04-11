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
        $this->load->view('atividades', $data);
    }

    public function ajax_list($id = '')
    {
        if (empty($id)) {
            $id = $this->session->userdata('id');
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.prioridade,
                       s.tipo,
                       s.status,
                       s.atividade,
                       s.data_cadastro,
                       s.data_limite,
                       s.data_fechamento,
                       s.id_usuario,
                       s.id_mae,
                       s.ordem_mae,
                       s.ordem_filha,
                       s.possui_filha,
                       s.filhas_finalizadas
                FROM (SELECT a.id, 
                             b.nome, 
                             a.prioridade,
                             a.tipo,
                             CASE WHEN a.status = 1 THEN '1' 
                                  WHEN CURDATE() BETWEEN a.data_lembrete AND a.data_limite THEN '2' 
                                  WHEN CURDATE() > a.data_limite THEN '3' 
                                  ELSE a.status END AS status,
                             a.atividade,
                             DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS data_cadastro,
                             DATE_FORMAT(a.data_limite, '%d/%m/%Y') AS data_limite,
                             DATE_FORMAT(a.data_fechamento, '%d/%m/%Y') AS data_fechamento,
                             a.id_mae,
                             a.id_usuario,
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
        $sql .= ') s';
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.atividade');
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

        $sql .= ' ORDER BY s.ordem_mae, s.ordem_filha';
        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' ,' . implode(', ', $orderBy);
        }

        if ($post['length'] > 0) {
            $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        }
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $atividade) {
            $row = array();
            $row[] = $atividade->id;
            $row[] = $atividade->id_mae;
            $row[] = $atividade->prioridade;
            $row[] = $atividade->tipo;
            $row[] = $atividade->status;
            $row[] = $atividade->atividade;
            $row[] = $atividade->id_usuario == $this->session->userdata('id') ? 'Mãe' : 'Filha';
            $row[] = $atividade->nome;
            $row[] = $atividade->data_cadastro;
            $row[] = $atividade->data_limite;
            $row[] = $atividade->data_fechamento;

            $acao = null;
            if ($this->agent->is_mobile == false) {
                if ($atividade->status === '1') {
                    $acao .= '<button class="btn btn-sm btn-primary disabled"><i class="glyphicon glyphicon-pencil"></i></button> ';
                } else {
                    $acao .= '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_atividade(' . "'" . $atividade->id . "'" . ',)"><i class="glyphicon glyphicon-pencil"></i></a> ';
                }
                if ($atividade->id_usuario == $id) {
                    $acao .= ' <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Cadastrar atividade(s) filha(s)" onclick="add_atividade_filha(' . "'" . $atividade->id . "'" . ')"><i class="glyphicon glyphicon-plus"></i></a>';
                } else {
                    $acao .= ' <button class="btn btn-sm btn-success disabled"><i class="glyphicon glyphicon-plus"></i></button>';
                }
                $acao .= ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_atividade(' . "'" . $atividade->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>';
            }
            if ($atividade->status === '0' and $atividade->filhas_finalizadas === '1') {
                $acao .= ' <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Finalizar" onclick="finaliza_atividade(' . "'" . $atividade->id . "'" . ')"><i class="glyphicon glyphicon-ok"></i></a>';
            } else {
                $acao .= ' <button class="btn btn-sm btn-success disabled"><i class="glyphicon glyphicon-ok"></i></button>';
            }

            $row[] = $acao;
            $row[] = $atividade->id_usuario;
            $row[] = $atividade->possui_filha;

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

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $this->db->select('id, id_usuario, atividade, prioridade, tipo, id_mae');
        $this->db->select("DATE_FORMAT(data_limite, '%d/%m/%Y') AS data_limite", false);
        $this->db->select("DATEDIFF(data_limite, data_lembrete) AS data_lembrete", false);
        $data = $this->db->get_where('atividades', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = $this->input->post();

        $data['data_cadastro'] = date('Y-m-d H:i:s');
        if ($data['data_limite'] and $data['data_lembrete']) {
            $data_limite = strtotime(str_replace('/', '-', $data['data_limite']));
            $data['data_limite'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['data_limite'] . ' 23:59:59')));
            $data['data_lembrete'] = date('Y-m-d', strtotime("-{$data['data_lembrete']} days", $data_limite));
        }

        if (empty($data['id_mae'])) {
            $data['id_mae'] = null;
        }

        if (is_array($data['id_usuario'])) {
            $arr_usuario = $data['id_usuario'];

            $arr_data = array();
            foreach ($arr_usuario as $id_usuario) {
                $data['id_usuario'] = $id_usuario;
                $arr_data[] = $data;
            }

            $this->db->insert_batch('atividades', $arr_data);
        } else {
            if (empty($data['id_usuario'])) {
                $data['id_usuario'] = $this->session->userdata('id');
            }

            $this->db->insert('atividades', $data);
        }


        echo json_encode(array("status" => true));
    }

    public function ajax_update()
    {
        $data = $this->input->post();

        if ($data['data_limite'] and $data['data_lembrete']) {
            $data_limite = strtotime(str_replace('/', '-', $data['data_limite']));
            $data['data_limite'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $data['data_limite'] . ' 23:59:59')));
            $data['data_lembrete'] = date('Y-m-d', strtotime("-{$data['data_lembrete']} days", $data_limite));
        }
        if (empty($data['id_mae'])) {
            $data['id_mae'] = null;
        }

        $this->db->update('atividades', $data, array('id' => $data['id']));

        echo json_encode(array("status" => true));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $this->db->delete('atividades', array('id' => $id));

        echo json_encode(array("status" => true));
    }

    public function ajax_finalizar()
    {
        $id = $this->input->post('id');
        $this->db->update('atividades', array('data_fechamento' => date('Y-m-d H:i:s'), 'status' => 1), array('id' => $id));

        echo json_encode(array("status" => true));
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

    public function pdf()
    {
        $id = $this->session->userdata('id');
        $get = $this->input->get();

        $sql = "SELECT a.id, 
                       b.nome, 
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
        if (isset($get['prioridade'])) {
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
        if (isset($get['data_inicio'])) {
            $data_inicio = date('Y-m-d', strtotime('-1 days', strtotime(str_replace('/', '-', $get['data_inicio']))));
            $sql .= " AND (a.data_cadastro > '{$data_inicio}' OR c.data_cadastro > '{$data_inicio}')";
        }
        if (isset($get['data_termino'])) {
            $data_termino = date('Y-m-d', strtotime('+1 days', strtotime(str_replace('/', '-', $get['data_termino']))));
            $sql .= " AND (a.data_limite < '{$data_termino}' OR c.data_limite < '{$data_termino}')";
        }
        if (isset($get['usuario'])) {
            if ($get['usuario'] == $id) {
                $sql .= " AND a.id_usuario = {$id}";
            } elseif ($get['usuario']) {
                $sql .= " AND c.id_usuario = {$get['usuario']}";
            } else {
                $sql .= " AND (a.id_usuario = {$id} OR c.id_usuario = {$id})";
            }
        }
        $data['rows'] = $this->db->query($sql)->result();

        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $this->db->select("nome, CONCAT_WS('/', depto, area, setor) AS estrutura", false);
        $this->db->where('id', $this->session->userdata('id'));
        $data['usuario'] = $this->db->get('usuarios')->row();
        if(empty($data['usuario']->estrutura)) {
            $data['usuario']->estrutura = 'Todos';
        }
        $this->load->library('m_pdf');

        $stylesheet = '#atividades thead th { font-size: 12px; padding: 4px 0px; text-align: center; font-weight: normal; } ';
        $stylesheet .= '#atividades thead tr, #frequencia tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= '#atividades tbody tr td { font-size: 12px; padding: 4px; 0px; } ';
        $stylesheet .= '#atividades tbody tr.dados_paciente td { padding: 0px; 0px; } ';
        $stylesheet .= '#table thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5;} ';
        $stylesheet .= '#table tbody td { font-size: 12px; padding: 4px; vertical-align: top; } ';

        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->load->view('atividadesPdf', $data, true));

        $this->m_pdf->pdf->Output('Relatório de Pendências.pdf', 'D');
    }

}
