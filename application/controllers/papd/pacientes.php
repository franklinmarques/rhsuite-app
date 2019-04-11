<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pacientes extends MY_Controller
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
        $this->db->distinct('cidade_nome');
        $this->db->where('id_instituicao', $empresa);
        $this->db->where('CHAR_LENGTH(cidade_nome) >', 0);
        $cidades_nome = $this->db->get('papd_pacientes')->result();
        foreach ($cidades_nome as $cidade_nome) {
            $data['cidade'][$cidade_nome->cidade_nome] = $cidade_nome->cidade_nome;
        }

        $this->db->distinct('bairro');
        $this->db->where('id_instituicao', $empresa);
        $this->db->where('CHAR_LENGTH(bairro) >', 0);
        $bairros = $this->db->get('papd_pacientes')->result();
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
                 WHERE id_instituicao = {$empresa}";
        $deficiencias = $this->db->query($sql4)->result();
        $data['deficiencia'] = array('' => 'Sem filtro');
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencia'][$deficiencia->id] = $deficiencia->nome;
        }

        $sql5 = "SELECT DISTINCT(contrato) AS contrato
                 FROM papd_pacientes
                 WHERE id_instituicao = {$empresa} AND 
                       CHAR_LENGTH(contrato) > 0";
        $contratos = $this->db->query($sql5)->result();
        $data['contratos'] = array('' => 'selecione...');
        foreach ($contratos as $contrato) {
            $data['contratos'][$contrato->contrato] = $contrato->contrato;
        }

        $data['empresa'] = $empresa;

        $data['meses'] = array(
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        $this->load->view('papd/pacientes', $data);
    }

    public function atualizar_filtro()
    {
        $estado = $this->input->post('estado');
        $cidade = $this->input->post('cidade');
        $bairro = $this->input->post('bairro');

        $filtro = $this->get_filtros_usuarios($depto, $area, $setor, $cargo, $funcao);
        if ($this->session->userdata('tipo') == 'funcionario') {
            if (!in_array($this->session->userdata('nivel'), array(9, 10))) {
                unset($filtro['area'][''], $filtro['setor']['']);
            }
            unset($filtro['depto']['']);
        }

        $data['area'] = form_dropdown('area', $filtro['area'], $area, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['setor'] = form_dropdown('setor', $filtro['setor'], $setor, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['cargo'] = form_dropdown('cargo', $filtro['cargo'], $cargo, 'onchange="atualizarFiltro()" class="form-control input-sm"');
        $data['funcao'] = form_dropdown('funcao', $filtro['funcao'], $funcao, 'onchange="atualizarFiltro()" class="form-control input-sm"');

        echo json_encode($data);
    }

    public function ajax_list()
    {
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome,
                       s.status,
                       s.deficiencia,
                       s.data_ingresso
                FROM (SELECT a.id, 
                             a.nome,
                             CASE a.status
                                  WHEN 'A' THEN 'Ativo'
                                  WHEN 'I' THEN 'Inativo'
                                  WHEN 'M' THEN 'Em monitoramento'
                                  WHEN 'X' THEN 'Afastado'
                                  WHEN 'E' THEN 'Em fila de espera' END AS status,
                             CONCAT_WS('/', CONCAT((CASE WHEN b.id > 0 THEN '' ELSE '_' END), b.tipo) , c.nome) AS deficiencia,
                             DATE_FORMAT(a.data_ingresso, '%d/%m/%Y') AS data_ingresso
                  FROM papd_pacientes a
                  LEFT JOIN deficiencias b ON
                            b.id = a.id_deficiencia
                  LEFT JOIN papd_hipotese_diagnostica c ON
                            c.id = a.id_hipotese_diagnostica
                  WHERE a.id_instituicao= {$this->session->userdata('empresa')}";
        if ($post['deficiencia']) {
            $sql .= " AND a.id_hipotese_diagnostica = {$post['deficiencia']}";
        }
        if ($post['status']) {
            $sql .= " AND a.status = '{$post['status']}'";
        }
        if ($post['contrato']) {
            $sql .= " AND a.contrato = '{$post['contrato']}'";
        }
        if ($post['estado']) {
            $sql .= " AND a.estado = {$post['estado']}";
        }
        if ($post['cidade']) {
            $sql .= " AND (a.cidade = '{$post['cidade']}' OR a.cidade_nome = '{$post['cidade']}')";
        }
        if ($post['bairro']) {
            $sql .= " AND a.bairro = '{$post['bairro']}'";
        }
        $sql .= ')s';

        $recordsTotal = $this->db->query($sql)->num_rows();

        $columns = array('s.nome', 's.status', 's.deficiencia', 's.data_ingresso');
        if ($post['search']['value']) {
            foreach ($columns as $key => $column) {
                if ($key > 0) {
                    $sql .= " OR {$column} LIKE '%{$post['search']['value']}%'";
                } else {
                    $sql .= " WHERE {$column} LIKE '%{$post['search']['value']}%'";
                }
            }
        }
        $recordsFiltered = $this->db->query($sql)->num_rows();

        if (isset($post['order'])) {
            $orderBy = array();
            foreach ($post['order'] as $order) {
                $orderBy[] = $columns[$order['column']] . ' ' . $order['dir'];
            }
            $sql .= ' ORDER BY ' . implode(', ', $orderBy);
        }
        $sql .= " LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $apontamento) {
            $row = array();
            $row[] = $apontamento->nome;
            $row[] = $apontamento->status;
            $row[] = str_replace('_', '', $apontamento->deficiencia);
            $row[] = $apontamento->data_ingresso;
            $row[] = '
                      <a class="btn btn-sm btn-primary" href="' . site_url('papd/pacientes/cadastro/' . $apontamento->id) . '" title="Editar"><i class="glyphicon glyphicon-pencil"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_paciente(' . "'" . $apontamento->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('papd/relatorios/frequencia/' . $apontamento->id) . '" title="Gerar controle de frequência individual"><i class="glyphicon glyphicon-list"></i> Controle de frequência individual</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('papd/relatorios/atendimentos_realizados/' . $apontamento->id) . '" title="Relatório"><i class="glyphicon glyphicon-list-alt"></i> Relatório</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('papd/mif/gerenciar/' . $apontamento->id) . '" title="Medida de Independência Funcional (MIF)">MIF</a>
                      <a class="btn btn-sm btn-primary" href="' . site_url('papd/zarit/gerenciar/' . $apontamento->id) . '" title="Avaliação da Sobrecarga dos Cuidadores (ZARIT)">ZARIT</a>
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


    public function ajax_consolidados_mif_zarit()
    {
        $anoInicial = $this->input->post('ano_inicial');
        $anoFinal = $this->input->post('ano_final');

        if (empty($anoInicial)) {
            $anoInicial = date('Y');
        }
        if (empty($anoFinal) or $anoFinal < $anoInicial) {
            $anoFinal = date('Y', mktime(0, 0, 0, 1, 1, $anoInicial + 5));
        }

        $mif = array();
        $zarit = array();
        $this->db->select('p.nome AS paciente');
        for ($i = $anoInicial; $i <= $anoFinal; $i++) {
            $this->db->select("ROUND(AVG(CASE YEAR(m.data_avaliacao) WHEN {$i} THEN m.mif END)) AS mif_{$i}", false);
            $this->db->select("(CASE ROUND(AVG(CASE YEAR(z.data_avaliacao) WHEN {$i} THEN IF(z.zarit > 21, 3, IF(z.zarit > 14, 2, IF(z.zarit >= 0, 1, NULL))) END)) WHEN 1 THEN 'Leve' WHEN 2 THEN 'Moderada' WHEN 3 THEN 'Grave' END) AS zarit_{$i}", false);
            $mif[] = 'mif_' . $i;
            $zarit[] = 'zarit_' . $i;
        }
        $this->db->join('papd_mif m', 'm.id_paciente = p.id', 'left');
        $this->db->join('papd_zarit z', 'z.id_paciente = p.id', 'left');
        $this->db->where('p.id_empresa', $this->session->userdata('empresa'));
        $this->db->group_by('p.id');
        $query = $this->db->get('papd_pacientes p');

        $this->load->library('dataTables');

        $data = array();

        $output = $this->datatables->generate($query);

        foreach ($output->data as $row) {
            $data[] = array(
                $row->paciente,
                isset($mif[0]) ? $row->{$mif[0]} : null,
                isset($zarit[0]) ? $row->{$zarit[0]} : null,
                isset($mif[1]) ? $row->{$mif[1]} : null,
                isset($zarit[1]) ? $row->{$zarit[1]} : null,
                isset($mif[2]) ? $row->{$mif[2]} : null,
                isset($zarit[2]) ? $row->{$zarit[2]} : null,
                isset($mif[3]) ? $row->{$mif[3]} : null,
                isset($zarit[3]) ? $row->{$zarit[3]} : null,
                isset($mif[4]) ? $row->{$mif[4]} : null,
                isset($zarit[4]) ? $row->{$zarit[4]} : null
            );
        }

        $output->ano_inicial = intval($anoInicial);
        $output->ano_final = $output->ano_inicial + 4;
        $output->data = $data;

        echo json_encode($output);
    }


    public function cadastro($id = null)
    {
        if ($id) {
            $data = $this->db->get_where('papd_pacientes', array('id' => $id))->row_array();
        } else {
            $this->db->select('*, COUNT(*) as count', false);
            $this->db->where('id', null);
            $data = $this->db->get('papd_pacientes')->row_array();

            $data['id_instituicao'] = $this->session->userdata('empresa');
            $data['estado'] = 35;   # Pré-seleção do Estado de São Paulo.
            $data['cidade'] = 3550308;  # Pré-seleção da Cidade de São Paulo.
            unset($data['count']);
        }

        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }

        if ($data['data_nascimento']) {
            $data['data_nascimento'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_nascimento'])));
        }
        if ($data['data_ingresso']) {
            $data['data_ingresso'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_ingresso'])));
        }
        if ($data['data_inativo']) {
            $data['data_inativo'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_inativo'])));
        }
        if ($data['data_afastamento']) {
            $data['data_afastamento'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_afastamento'])));
        }
        if ($data['data_fila_espera']) {
            $data['data_fila_espera'] = date("d/m/Y", strtotime(str_replace('-', '/', $data['data_fila_espera'])));
        }

        $this->db->order_by('uf', 'asc');
        $estados = $this->db->get('estados')->result();
        $data['estados'] = array('' => 'selecione ...');
        foreach ($estados as $estado) {
            $data['estados'][$estado->cod_uf] = $estado->uf;
        }

        $this->db->where('cod_uf', $data['estado']);
        $cidades = $this->db->get('municipios')->result();
        $data['cidades'] = array('' => 'digite ou selecione ...');
        foreach ($cidades as $cidade) {
            $data['cidades'][$cidade->cod_mun] = $cidade->municipio;
        }

        $deficiencias = $this->db->get('deficiencias')->result();
        $data['deficiencias'] = array('' => 'selecione ...');
        foreach ($deficiencias as $deficiencia) {
            $data['deficiencias'][$deficiencia->id] = $deficiencia->tipo;
        }

        $hds = $this->db->get('papd_hipotese_diagnostica')->result();
        $data['hds'] = array('' => 'selecione ...');
        foreach ($hds as $hd) {
            $data['hds'][$hd->id] = $hd->nome;
        }

        $data['grupo_status'] = array('A' => 'Ativo', 'I' => 'Inativo', 'M' => 'Em monitoramento', 'E' => 'Em fila de espera');

        $this->load->view('papd/perfil', $data);
    }

    public function ajax_save()
    {
        $data = $this->input->post();

        if (empty($data['id_empresa'])) {
            $data['id_empresa'] = $this->session->userdata('empresa');
        }
        if (strlen($data['nome']) == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O nome do paciente é obrigatório')));
        }
        if (empty($data['status'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O status é obrigatório')));
        }
        if (strlen($data['data_nascimento']) == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de nascimento é obrigatória')));
        } elseif ($this->formatarData($data['data_nascimento']) === false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de nascimento é inválida')));
        }
        if (strlen($data['data_ingresso']) == 0) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de ingresso é obrigatória')));
        } elseif ($this->formatarData($data['data_ingresso']) === false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de ingresso é inválida')));
        }
        if (strlen($data['data_inativo']) == 0) {
            $data['data_inativo'] = null;
        } elseif ($this->formatarData($data['data_inativo']) === false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de inatividade é inválida')));
        }
        if (strlen($data['data_afastamento']) == 0) {
            $data['data_afastamento'] = null;
        } elseif ($this->formatarData($data['data_afastamento']) === false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de afastamento é inválida')));
        }
        if (strlen($data['data_fila_espera']) == 0) {
            $data['data_fila_espera'] = null;
        } elseif ($this->formatarData($data['data_fila_espera']) === false) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'A data de fila de espera é inválida')));
        }

        $id = $data['id'];
        if (empty($data['id_deficiencia'])) {
            $data['id_deficiencia'] = null;
        }
        if (empty($data['id_hipotese_diagnostica'])) {
            $data['id_hipotese_diagnostica'] = null;
        }
        if (empty($data['cidade'])) {
            $data['cidade'] = null;
        } else {
            $this->db->where('cod_mun', $data['cidade']);
            $this->db->or_where('municipio', $data['cidade']);
            $cidade = $this->db->get('municipios')->row();
            if ($cidade) {
                $data['cidade'] = $cidade->cod_mun;
                $data['cidade_nome'] = null;
            } else {
                $data['cidade_nome'] = $data['cidade'];
                $data['cidade'] = null;
            }
        }
        if (empty($data['estado'])) {
            $data['estado'] = null;
        }
        unset($data['id']);
        if ($id) {
            $this->db->update('papd_pacientes', $data, array('id' => $id));
        } else {
            $this->db->insert('papd_pacientes', $data);
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Cadastro de paciente salvo com sucesso', 'redireciona' => 1, 'pagina' => site_url('papd/pacientes')));
    }

    private function formatarData(&$date)
    {
        if (strlen($date) == 0) {
            return $date;
        }

        $date = date("Y-m-d", strtotime(str_replace('/', '-', $date)));
        $arrDate = explode('-', $date);
        if (checkdate(intval($arrDate[1]), intval($arrDate[2]), intval($arrDate[0])) == false) {
            return false;
        }

        return $date;
    }

    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('papd_pacientes', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

}
