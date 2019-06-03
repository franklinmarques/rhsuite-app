<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atividades_scheduler extends MY_Controller
{

    public function index()
    {
        $data['usuario'] = $this->session->userdata('id');
        $data['empresa'] = $this->session->userdata('empresa');

        $this->load->view('atividades_scheduler', $data);
    }


    public function ajaxList()
    {
        parse_str($this->input->post('busca'), $busca);

        $dias = implode(',', ($busca['dia'] ?? []) + [0]);
        $semanas = implode(',', ($busca['semana'] ?? []) + [0]);
        $meses = implode(',', ($busca['mes'] ?? []) + [0]);

        $sql = "SELECT dia, semana, mes, atividade, objetivos, data_limite,
                       envolvidos, observacoes, processo_roteiro,
                       documento_1, documento_2, documento_3, id
                FROM atividades_scheduler 
                WHERE id_empresa = {$this->session->userdata('empresa')} AND 
                      id_usuario = {$this->session->userdata('id')} AND 
                      (dia IN ($dias) OR semana IN ($semanas) OR mes IN ($meses))";


        $this->load->library('dataTables');

        $output = $this->datatables->query($sql);

        $data = array();

        foreach ($output->data as $row) {
            if ($row->documento_1 or $row->documento_2 or $row->documento_3) {
                $processo = '<button class="btn btn-sm btn-info" onclick="processo(' . $row->id . ');" title="Processo"><i class="glyphicon glyphicon-print"></i></button>';
            } else {
                $processo = '<button class="btn btn-sm btn-info disabled" title="Processo"><i class="glyphicon glyphicon-print"></i></button>';
            }
            $data[] = array(
                $row->dia,
                $row->semana,
                $row->mes,
                $row->atividade,
                $row->objetivos,
                $row->data_limite,
                $row->envolvidos,
                $row->observacoes,
                $processo,
                '<button class="btn btn-sm btn-info" onclick="edit_atividade(' . $row->id . ');" title="Editar"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_atividade(' . $row->id . ');" title="Excluir"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }


    public function ajaxNew()
    {
        parse_str($this->input->post('busca'), $busca);

        $data = array(
            'dia' => $busca['dia'] ?? '',
            'semana' => $busca['semana'] ?? '',
            'mes' => $busca['mes'] ?? ''
        );

        echo json_encode($data);
    }


    public function ajaxEdit()
    {
        parse_str($this->input->post('busca'), $busca);


        $this->db->select('id, id_empresa, atividade, objetivos envolvidos, observacoes');
        $this->db->select('data_limite, dia, semana, mes, documento_1, documento_2, documento_3');
        $this->db->where('id', $this->input->post('id'));
        $data = $this->db->get('atividades_scheduler')->row();

        if (empty($data)) {
            exit(json_encode(['erro' => 'Erro ao editar a atividade.']));
        }

        echo json_encode($data);
    }


    public function ajaxAdd()
    {
        $data = $this->input->post();

        if (strlen($data['atividade']) == 0) {
            exit(json_encode(['erro' => 'O nome da atividade é obrigatório.']));
        }

        $data['data_cadastro'] = date('Y-m-d');

        $dias = explode(',', $data['dia']);
        $semanas = explode(',', $data['semana']);
        $meses = explode(',', $data['mes']);

        if (count($dias) == 0) {
            $dias = [''];
        }
        if (count($semanas) == 0) {
            $semanas = [''];
        }
        if (count($meses) == 0) {
            $meses = [''];
        }


        $this->db->trans_begin();


        $documento = $this->uploadDocumento();

        $data['documento_1'] = $documento['documento_1'] ?? null;
        $data['documento_2'] = $documento['documento_2'] ?? null;
        $data['documento_3'] = $documento['documento_3'] ?? null;

        $data['dia'] = null;
        $data['semana'] = null;
        $data['mes'] = null;

        foreach ($meses as $mes) {
            foreach ($semanas as $semana) {
                foreach ($dias as $dia) {
                    if ($dia) {
                        $data['dia'] = $dia;
                    }
                    if ($semana) {
                        $data['semana'] = $semana;
                    }
                    if ($mes) {
                        $data['mes'] = $mes;
                    }
                    $this->db->insert('atividades_scheduler', $data);
                }
            }
        }

        $status = $this->db->trans_status();

        if ($status == false) {
            $this->db->trans_rollback();
            $this->excluirDocumento($data['documento_1']);
            $this->excluirDocumento($data['documento_2']);
            $this->excluirDocumento($data['documento_3']);

            exit(json_encode(['erro' => 'Não foi possível salvar a atividade.']));
        }

        $this->db->trans_commit();

        echo json_encode(['status' => $status]);
    }


    public function ajaxUpdate()
    {
        $data = $this->input->post();

        if (strlen($data['atividade']) == 0) {
            exit(json_encode(['erro' => 'O nome da atividade é obrigatório.']));
        }

        $id = $data['id'];
        unset($data['id']);
        unset($data['dia']);
        unset($data['semana']);
        unset($data['mes']);


        $this->db->trans_begin();


        $documento = $this->uploadDocumento();


        if (isset($documento['documento_1'])) {
            $data['documento_1'] = $documento['documento_1'];
        }
        if (isset($documento['documento_2'])) {
            $data['documento_2'] = $documento['documento_2'];
        }
        if (isset($documento['documento_3'])) {
            $data['documento_3'] = $documento['documento_3'];
        }


        $this->db->update('atividades_scheduler', $data, ['id' => $id]);

        $status = $this->db->trans_status();

        if ($status == false) {
            $this->db->trans_rollback();
            $this->excluirDocumento($data['documento_1']);
            $this->excluirDocumento($data['documento_2']);
            $this->excluirDocumento($data['documento_3']);

            exit(json_encode(['erro' => 'Não foi possível salvar a atividade.']));
        }

        $this->db->trans_commit();

        echo json_encode(['status' => $status]);
    }


    public function ajaxDelete()
    {
        $id = $this->input->post('id');

        $this->db->trans_start();

        $documento = $this->db->get_where('atividades_scheduler', ['id' => $id])->row();

        if (empty($documento)) {
            exit(json_encode(['erro' => 'A atividade não foi encontrada ou já foi excluída.']));
        }

        $this->db->delete('atividades_scheduler', ['id' => $id]);

        $this->db->trans_complete();

        if ($this->db->trans_status() == false) {
            $this->db->trans_rollback();
            exit(json_encode(['erro' => 'Não foi possível excluir a atividade.']));
        }

        $this->excluirDocumento($documento->documento_1);
        $this->excluirDocumento($documento->documento_2);
        $this->excluirDocumento($documento->documento_3);


        echo json_encode(['status' => true]);
    }


    private function uploadDocumento()
    {
        $data = [];

        $status = true;

        $documentos = ['documento_1', 'documento_2', 'documento_3'];

        $config = array(
            'upload_path' => './arquivos/pdf/',
            'allowed_types' => 'pdf'
        );


        foreach ($documentos as $documento) {
            if (!empty($_FILES[$documento]['tmp_name'])) {
                $config['file_name'] = utf8_decode($_FILES[$documento]['name']);

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($documento)) {
                    $status = false;
                    break;
                }

                $arquivo = $this->upload->data();
                $data[$documento] = utf8_encode($arquivo['file_name']);
            }
        }

        if ($status == false) {
            foreach ($data as $nomeDocumento) {
                $this->excluirDocumento($nomeDocumento);
            }

            exit(json_encode(['erro' => $this->upload->display_errors() . ' - ' . $nomeDocumento]));
        }

        return $data;
    }


    public function ajaxProcesso()
    {
        $this->db->select('documento_1, documento_2, documento_3');
        $this->db->where('id', $this->input->get('id'));
        $row = $this->db->get('atividades_scheduler')->row();

        if (empty($row)) {
            exit(json_encode(['erro' => 'Erro ao carregar o processo.']));
        }

        $data = array(
            'iframe_documento_1' => '',
            'iframe_documento_2' => '',
            'iframe_documento_3' => ''
        );

        $gview = 'https://docs.google.com/gview?embedded=true&url=';

        if ($row->documento_1) {
            $data['iframe_documento_1'] = $gview . base_url('arquivos/pdf/' . convert_accented_characters($row->documento_1));
        } else {
            $data['iframe_documento_1'] = $gview . 'undefined';
        }
        if ($row->documento_2) {
            $data['iframe_documento_2'] = $gview . base_url('arquivos/pdf/' . convert_accented_characters($row->documento_2));
        } else {
            $data['iframe_documento_2'] = $gview . 'undefined';
        }
        if ($row->documento_3) {
            $data['iframe_documento_3'] = $gview . base_url('arquivos/pdf/' . convert_accented_characters($row->documento_3));
        } else {
            $data['iframe_documento_3'] = $gview . 'undefined';
        }

        echo json_encode($data);
    }


    private function excluirDocumento($documento)
    {
        @unlink('./arquivos/pdf/' . $documento);
    }


    public function pdf()
    {

    }


}
