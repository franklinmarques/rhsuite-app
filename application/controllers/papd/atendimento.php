<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Atendimento extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $sql = "SELECT a.cod_uf, 
                       a.uf 
                FROM estados a 
                INNER JOIN papd_pacientes b ON 
                           b.estado = a.cod_uf 
                WHERE b.id_instituicao = {$empresa}";
        $estados = $this->db->query($sql)->result();
        $data['estado'] = array('' => 'Todos');
        foreach ($estados as $estado) {
            $data['estado'][$estado->cod_uf] = $estado->uf;
        }

        $sql2 = "SELECT a.cod_mun, 
                        a.municipio 
                 FROM municipios a 
                 INNER JOIN papd_pacientes b ON 
                            b.cidade = a.cod_mun 
                 WHERE b.id_instituicao = {$empresa}";
        $cidades = $this->db->query($sql2)->result();
        $data['cidade'] = array('' => 'Todas');
        foreach ($cidades as $cidade) {
            $data['cidade'][$cidade->cod_mun] = $cidade->municipio;
        }

        $this->db->distinct('bairro');
        $bairros = $this->db->get_where('papd_pacientes', array('id_instituicao' => $empresa))->result();
        $data['bairro'] = array('' => 'Todos');
        foreach ($bairros as $bairro) {
            $data['bairro'][$bairro->bairro] = $bairro->bairro;
        }

        $sql3 = "SELECT status AS id,
                        CASE status
                        WHEN 'A' THEN 'Ativo'
                        WHEN 'I' THEN 'Inativo'
                        WHEN 'M' THEN 'Em monitoramento'
                        WHEN 'X' THEN 'Afastado'
                        WHEN 'E' THEN 'Em fila de espera' END AS nome
                 FROM papd_pacientes 
                 WHERE id_instituicao = {$empresa}";
        $grupo_status = $this->db->query($sql3)->result();
        $data['status'] = array('' => 'Todos');
        foreach ($grupo_status as $status) {
            $data['status'][$status->id] = $status->nome;
        }

        $sql4 = "SELECT id, 
                        nome 
                 FROM papd_hipotese_diagnostica 
                 WHERE id_instituicao = {$empresa}
                 ORDER BY nome ASC";
        $deficiencias = $this->db->query($sql4)->result();
        $data['deficiencia'] = array('' => 'Sem filtro');
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencia'][$deficiencia->id] = $deficiencia->nome;
        }

        $sql5 = "SELECT id,
                        nome
                 FROM papd_pacientes 
                 WHERE id_instituicao = {$empresa}
                 ORDER BY nome ASC";
        $pacientes = $this->db->query($sql5)->result();
        $data['pacientes'] = array('' => 'selecione...');
        foreach ($pacientes as $paciente) {
            $data['pacientes'][$paciente->id] = $paciente->nome;
        }

        $sql6 = "SELECT id, 
                        nome 
                 FROM papd_atividades
                 WHERE id_instituicao = {$empresa}
                 ORDER BY nome ASC";
        $atividades = $this->db->query($sql6)->result();
        $data['atividades'] = array('' => 'selecione...');
        foreach ($atividades as $atividade) {
            $data['atividades'][$atividade->id] = $atividade->nome;
        }

        $data['empresa'] = $empresa;

        $this->load->view('papd/atendimento', $data);
    }

    public function index2()
    {
        $empresa = $this->session->userdata('empresa');

        $sql = "SELECT a.cod_uf, 
                       a.uf 
                FROM estados a 
                INNER JOIN papd_pacientes b ON 
                           b.estado = a.cod_uf 
                WHERE b.id_instituicao = {$empresa}";
        $estados = $this->db->query($sql)->result();
        $data['estado'] = array('' => 'Todos');
        foreach ($estados as $estado) {
            $data['estado'][$estado->cod_uf] = $estado->uf;
        }

        $sql2 = "SELECT a.cod_mun, 
                        a.municipio 
                 FROM municipios a 
                 INNER JOIN papd_pacientes b ON 
                            b.cidade = a.cod_mun 
                 WHERE b.id_instituicao = {$empresa}";
        $cidades = $this->db->query($sql2)->result();
        $data['cidade'] = array('' => 'Todas');
        foreach ($cidades as $cidade) {
            $data['cidade'][$cidade->cod_mun] = $cidade->municipio;
        }

        $this->db->distinct('bairro');
        $bairros = $this->db->get_where('papd_pacientes', array('id_instituicao' => $empresa))->result();
        $data['bairro'] = array('' => 'Todos');
        foreach ($bairros as $bairro) {
            $data['bairro'][$bairro->bairro] = $bairro->bairro;
        }

        $sql3 = "SELECT status AS id,
                        CASE status
                        WHEN 'A' THEN 'Ativo'
                        WHEN 'I' THEN 'Inativo'
                        WHEN 'M' THEN 'Em monitoramento'
                        WHEN 'X' THEN 'Afastado'
                        WHEN 'E' THEN 'Em fila de espera' END AS nome
                 FROM papd_pacientes 
                 WHERE id_instituicao = {$empresa}";
        $grupo_status = $this->db->query($sql3)->result();
        $data['status'] = array('' => 'Todos');
        foreach ($grupo_status as $status) {
            $data['status'][$status->id] = $status->nome;
        }

        $sql4 = "SELECT id, 
                        nome 
                 FROM papd_hipotese_diagnostica 
                 WHERE id_instituicao = {$empresa}
                 ORDER BY nome ASC";
        $deficiencias = $this->db->query($sql4)->result();
        $data['deficiencia'] = array('' => 'Sem filtro');
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencia'][$deficiencia->id] = $deficiencia->nome;
        }

        $sql5 = "SELECT id,
                        nome
                 FROM papd_pacientes 
                 WHERE id_instituicao = {$empresa}
                 ORDER BY nome ASC";
        $pacientes = $this->db->query($sql5)->result();
        $data['pacientes'] = array('' => 'selecione...');
        foreach ($pacientes as $paciente) {
            $data['pacientes'][$paciente->id] = $paciente->nome;
        }

        $sql6 = "SELECT id, 
                        nome 
                 FROM papd_atividades
                 WHERE id_instituicao = {$empresa}
                 ORDER BY nome ASC";
        $atividades = $this->db->query($sql6)->result();
        $data['atividades'] = array('' => 'selecione...');
        foreach ($atividades as $atividade) {
            $data['atividades'][$atividade->id] = $atividade->nome;
        }

        $data['empresa'] = $empresa;

        $this->load->view('papd/atendimento [2018-10-08]', $data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.data_atendimento,
                       s.atividade,
                       s.deficiencia
                FROM (SELECT a.id, 
                             b.nome,
                             DATE_FORMAT(a.data_atendimento, '%d/%m/%Y &nbsp; %H:%i') AS data_atendimento,
                             c.nome AS atividade,
                             d.nome AS deficiencia
                  FROM papd_atendimentos a
                  INNER JOIN papd_pacientes b ON
                            b.id = a.id_paciente
                  LEFT JOIN papd_atividades c ON
                            c.id = a.id_atividade AND 
                            c.id_instituicao = b.id_instituicao
                  LEFT JOIN papd_hipotese_diagnostica d ON
                            d.id = b.id_deficiencia AND 
                            d.id_instituicao = b.id_instituicao
                  WHERE a.id_usuario = {$this->session->userdata('id')} ";

        if ($post['data_inicio']) {
            $sql .= " AND a.data_atendimento >= '" . date("Y-m-d", strtotime(str_replace('/', '-', $post['data_inicio']))) . " 00:00:00'";
        }
        if ($post['data_termino']) {
            $sql .= " AND a.data_atendimento <= '" . date("Y-m-d", strtotime(str_replace('/', '-', $post['data_termino']))) . " 23:59:59'";
        }
        if ($post['paciente']) {
            $sql .= " AND b.id = {$post['paciente']}";
        }
        if ($post['atividade']) {
            $sql .= " AND a.id_atividade = '{$post['atividade']}'";
        }
        if ($post['deficiencia']) {
            $sql .= " AND b.id_hipotese_diagnostica = {$post['deficiencia']}";
        }
        $sql .= ')s';

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.id', 's.nome');
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
        foreach ($list as $atendimento) {
            $row = array();
            $row[] = $atendimento->nome;
            $row[] = $atendimento->data_atendimento;
            $row[] = $atendimento->atividade;
            $row[] = $atendimento->deficiencia;
            $row[] = '
                      <button class="btn btn-sm btn-info" title="Editar" onclick="edit_atendimento(' . "'" . $atendimento->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></button>
                      <button class="btn btn-sm btn-danger" title="Excluir" onclick="delete_atendimento(' . "'" . $atendimento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></button>
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
        $this->db->select('id, id_paciente, id_atividade');
        $this->db->select("DATE_FORMAT(data_atendimento, '%d/%m/%Y') AS data_atendimento", false);
        $this->db->select("DATE_FORMAT(data_atendimento, '%H:%i') AS hora_atendimento", false);
        $data = $this->db->get_where('papd_atendimentos', array('id' => $id))->row();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $idUsuario = $this->session->userdata('id');
        $idPaciente = $this->input->post('id_paciente');
        $atividades = $this->input->post('id_atividade');
        $dataAtendimentos = $this->input->post('data_atendimento');
        $horaAtendimentos = $this->input->post('hora_atendimento');

        if (empty($idPaciente)) {
            exit(json_encode(['erro' => 'O paciente é obrigratório']));
        }
        $idAtividades = array_filter($atividades);
        if (empty($idAtividades)) {
            exit(json_encode(['erro' => 'Um campo de atividade é obrigratório']));
        }


        $data = array();
        foreach ($idAtividades as $k => $idAtividade) {
            if ((!empty($dataAtendimentos[$k]) and !empty($horaAtendimentos[$k])) == false) {
                exit(json_encode(['erro' => 'A data e hora do Atendimento ' . ($k + 1) . ' são obrigratórias']));
            }
            $dataHoraAtendimento = $dataAtendimentos[$k] . ' ' . $horaAtendimentos[$k] . ':00';
            $datetime = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dataHoraAtendimento)));
            $dt = date_create($datetime, timezone_open('America/Sao_Paulo'));
            if (date_format($dt, 'd/m/Y H:i:s') !== $dataHoraAtendimento) {
                exit(json_encode(['erro' => 'A data e hora do Atendimento ' . ($k + 1) . ' são inválidas']));
            }
            $data[] = array(
                'id_usuario' => $idUsuario,
                'id_paciente' => $idPaciente,
                'id_atividade' => $idAtividades[$k],
                'data_atendimento' => $datetime
            );
        }

        $this->db->trans_start();
        $this->db->insert_batch('papd_atendimentos', $data);
        $this->db->trans_complete();
        $status = $this->db->trans_status();

        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $id = $this->input->post('id')[0];
        $idPaciente = $this->input->post('id_paciente');
        $idAtividade = $this->input->post('id_atividade')[0] ?? '';
        $dataAtendimento = $this->input->post('data_atendimento')[0] ?? '';
        $horaAtendimento = $this->input->post('hora_atendimento')[0] ?? '';

        if (empty($id)) {
            exit(json_encode(['erro' => 'O atendimento não foi encontrado']));
        }
        if (empty($idPaciente)) {
            exit(json_encode(['erro' => 'O paciente é obrigratório']));
        }
        if (empty($idAtividade)) {
            exit(json_encode(['erro' => 'Um campo de atividade é obrigratório']));
        }
        if ((!empty($dataAtendimento) and !empty($horaAtendimento)) == false) {
            exit(json_encode(['erro' => 'A data e hora de Atendimento 1 são obrigratórias']));
        }
        $dataHoraAtendimento = $dataAtendimento . ' ' . $horaAtendimento . ':00';
        $datetime = date("Y-m-d H:i:s", strtotime(str_replace('/', '-', $dataHoraAtendimento)));
        $dt = date_create($datetime, timezone_open('America/Sao_Paulo'));
        if (date_format($dt, 'd/m/Y H:i:s') !== $dataHoraAtendimento) {
            exit(json_encode(['erro' => 'A data e hora de Atendimento 1 são inválidas']));
        }

        $data = array(
            'id_paciente' => $idPaciente,
            'id_atividade' => $idAtividade,
            'data_atendimento' => $datetime
        );

        $status = $this->db->update('papd_atendimentos', $data, array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $this->db->delete('papd_atendimentos', array('id' => $id));

        echo json_encode(array("status" => TRUE));
    }

}
