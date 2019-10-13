<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Biblioteca extends MY_Controller
{

    public function index()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => '',
            'nome' => 'Modelo de recrutamento'
        );

        $this->load->view('ead/biblioteca_questoes', $data);
    }

    public function ajax_list($id = '')
    {
        if (empty($id)) {
            $id = $this->session->userdata('id');
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.tipo
                FROM (SELECT a.id, 
                             a.nome, 
                             (case tipo 
                              when 1 then 'Múltiplas alternativas'                               
                              when 2 then 'Dissertativa' 
                              when 3 then 'Múltiplas alternativas (quick quiz)' 
                              else '' end) AS tipo
                      FROM biblioteca_questoes a 
                      WHERE a.id_empresa = {$id}) s";
        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome', 's.tipo');
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
        foreach ($list as $recrutamento) {
            $row = array();
            $row[] = $recrutamento->nome;
            $row[] = $recrutamento->tipo;

            $row[] = '
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_questao(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_questao(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar questão" onclick="edit_conteudo(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i> Editar Questão</a>
                      <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar respostas" onclick="edit_respostas(' . "'" . $recrutamento->id . "'" . ')"><i class="glyphicon glyphicon-list"></i> Editar respostas</a>
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

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('biblioteca_questoes', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajax_conteudo()
    {
        $id = $this->input->post('id');
        $this->db->select('id, conteudo');
        $data = $this->db->get_where('biblioteca_questoes', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajax_respostas()
    {
        $id = $this->input->post('id');
        $this->db->select('id AS id_questao, nome, tipo, feedback_correta, feedback_incorreta');
        $this->db->where('id', $id);
        $data = $this->db->get('biblioteca_questoes')->row();
        $sql = "SELECT b.id,
                       b.alternativa,
                       b.peso
                FROM biblioteca_questoes a
                LEFT JOIN biblioteca_alternativas b ON
                          b.id_questao = a.id
                WHERE a.id = {$id}";
        $data->alternativas = $this->db->query($sql)->result();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $perguntas = $this->input->post('perguntas');
        $alternativas = $this->input->post('alternativas');
        $aleatorizacao = '';
        if ($perguntas && $alternativas) {
            $aleatorizacao = 'T';
        } elseif ($perguntas) {
            $aleatorizacao = 'P';
        } elseif ($alternativas) {
            $aleatorizacao = 'A';
        }

        $data = array(
            'id_empresa' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $this->input->post('tipo'),
            'observacoes' => $this->input->post('observacoes'),
            'aleatorizacao' => $aleatorizacao
        );

        $this->db->trans_start();

        $this->db->insert('biblioteca_questoes', $data);
        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $id = $this->input->post('id');
        $tipo = $this->input->post('tipo');
        $perguntas = $this->input->post('perguntas');
        $alternativas = $this->input->post('alternativas');
        $aleatorizacao = '';
        if ($perguntas && $alternativas) {
            $aleatorizacao = 'T';
        } elseif ($perguntas) {
            $aleatorizacao = 'P';
        } elseif ($alternativas) {
            $aleatorizacao = 'A';
        }

        $data = array(
            'id_empresa' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $tipo,
            'observacoes' => $this->input->post('observacoes'),
            'aleatorizacao' => $aleatorizacao
        );

        $this->db->trans_start();

        $this->db->update('biblioteca_questoes', $data, array('id' => $id));
        if ($tipo == '2') {
            $this->db->delete('biblioteca_alternativas', $data, array('id_questao' => $id));
        }
        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function salvar_conteudo()
    {
        $id = $this->input->post('id');
        $data = array('conteudo' => $this->input->post('conteudo'));

        $this->db->trans_start();
        $this->db->update('biblioteca_questoes', $data, array('id' => $id));
        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

    public function salvar_respostas()
    {
        $this->db->select('id, tipo');
        $this->db->where('id', $this->input->post('id_questao'));
        $questao = $this->db->get('biblioteca_questoes')->row();
        if (empty($questao)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A questão não foi encontrada')));
        }

        $data = array(
            'feedback_correta' => $this->input->post('feedback_correta'),
            'feedback_incorreta' => $this->input->post('feedback_incorreta')
        );

        $this->db->trans_begin();

        $update_string = $this->db->update_string('biblioteca_questoes', $data, array('id' => $questao->id));
        $this->db->query($update_string);

        $id_alternativa = $this->input->post('id_alternativa');
        if (in_array($questao->tipo, array('1', '3'))) {
            $alternativas = $this->input->post('alternativa');
        } else {
            $alternativas = array_pad(array(), 6, '');
        }

        $peso = $this->input->post('peso');

        foreach ($alternativas as $k => $alternativa) {
            $data = array(
                'id_questao' => $questao->id,
                'alternativa' => $alternativa,
                'peso' => $peso[$k]
            );
            if ($alternativa) {
                if ($id_alternativa[$k]) {
                    $update_string = $this->db->update_string('biblioteca_alternativas', $data, array('id' => $id_alternativa[$k]));
                    $this->db->query($update_string);
                } else {
                    $insert_string = $this->db->insert_string('biblioteca_alternativas', $data);
                    $this->db->query($insert_string);
                }
            } elseif ($id_alternativa[$k]) {
                $this->db->query("DELETE FROM biblioteca_alternativas WHERE id = $id_alternativa[$k]");
            }
        }

        $this->db->trans_complete();
        $status = $this->db->trans_status();

        if ($status === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');

        $this->db->trans_start();
        $this->db->delete('biblioteca_questoes', array('id' => $id));
        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }

}
