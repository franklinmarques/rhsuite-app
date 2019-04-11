<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manutencoes extends MY_Controller
{

    public function index()
    {
        $data['idEmpresa'] = $this->session->userdata('empresa');
        $data['modelos'] = ['' => 'selecione...'] + $this->getModelosManutencoes();
        $data['responsaveis'] = ['' => 'selecione...'] + $this->getResponsaveis();
        $data['meses'] = $this->getMeses();
        $data['itens'] = $this->getItens();

        $this->load->view('facilities/manutencoes', $data);
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $post = $this->input->post();

        $this->db->select(["b.nome AS modelo, CONCAT(a.ano, '-', a.mes) AS data, a.status, a.pendencias, c.nome AS responsavel"], false);
        $this->db->select(["(CASE a.status WHEN 'P' THEN 'Programada' WHEN 'N' THEN 'Não realizada' WHEN 'R' THEN 'Realizada' END) AS nome_status"], false);
        $this->db->select(["CONCAT(a.mes, '/', a.ano) AS mes_ano, IF(a.pendencias = 1, 'Com pendências', 'Sem pendências') AS nome_pendencia, a.id"], false);
        $this->db->select(["(CASE a.tipo_executor WHEN 'I' THEN 'Interna' WHEN 'E' THEN 'Externa' END) AS execucao"], false);
        $this->db->join('facilities_modelos b', "b.id = a.id_modelo AND b.tipo = 'M'");
        $this->db->join('usuarios c', 'c.id = a.id_usuario_vistoriador', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        if (!empty($post['status'])) {
            $this->db->where('a.status', $post['status']);
        }
        if (!empty($post['mes'])) {
            $this->db->where('a.mes', $post['mes']);
        }
        if (!empty($post['ano'])) {
            $this->db->where('a.ano', $post['ano']);
        }
        $query = $this->db->get('facilities_realizacoes a');


        $config = array(
            'search' => ['modelo', 'responsavel']
        );
        $this->load->library('dataTables', $config);

        $rows = $this->datatables->generate($query);


        $data = array();

        foreach ($rows->data as $row) {
            $data[] = array(
                $row->modelo,
                $row->mes_ano,
                $row->nome_status,
                $row->nome_pendencia,
                $row->responsavel,
                $row->execucao,
                '<button class="btn btn-sm btn-info" onclick="edit_manutencao(' . $row->id . ');" title="Editar manutenção"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_manutencao(' . $row->id . ');" title="Excluir manutenção"><i class="glyphicon glyphicon-trash"></i></button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('facilities/manutencoes/realizacao/' . $row->id) . '" target="_blank" title="Cadastrar realização de manutenção"><i class="glyphicon glyphicon-list-alt"></i> Realização</a>
                 <button class="btn btn-sm btn-info" onclick="laudos(' . $row->id . ');" title="Gerenciar laudos de terceiros"><i class="glyphicon glyphicon-import"></i> Laudos</button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('facilities/manutencoes/relatorio/' . $row->id) . '" target="_blank" title="Imprimir manutenção"><i class="glyphicon glyphicon-list-alt"></i> Imprimir</a>',
                $row->status,
                $row->pendencias
            );
        }

        $status = array(
            '' => 'Todas',
            'N' => 'Não realizadas',
            'P' => 'Programadas',
            'R' => 'Realizadas'
        );

        $rows->status = form_dropdown('busca_status', $status, $post['status'], 'class="form-control input-sm" aria-controls="table" onchange="reload_table();"');
        $rows->ano = form_input('busca_ano', $post['ano'], 'class="form-control text-center input-sm ano" style="width: 60px;" aria-controls="table" onblur="reload_table();"');

        $rows->data = $data;

        echo json_encode($rows);
    }

    // -------------------------------------------------------------------------

    public function ajaxLaudos()
    {
        $id = $this->input->post('id_realizacao');


        $this->db->select('b.nome, a.arquivo, a.id');
        $this->db->join('facilities_itens b', 'b.id = a.id_item');
        $this->db->where('a.id_realizacao', $id);
        $query = $this->db->get('facilities_realizacoes_laudos a');

        $this->load->library('dataTables');
        $rows = $this->datatables->generate($query);

        $data = array();
        foreach ($rows->data as $row) {
            $data[] = array(
                $row->nome,
                $row->arquivo,
                '<button class="btn btn-sm btn-danger" onclick="delete_laudo(' . $row->id . ');" title="Excluir laudo"><i class="glyphicon glyphicon-trash"></i></button>
                 <a class="btn btn-sm btn-primary" href="' . base_url('arquivos/laudos/' . convert_accented_characters($row->arquivo)) . '" target="_blank" title="Visualizar laudo"><i class="glyphicon glyphicon-eye-open"></i> Visualizar</button>'
            );
        }

        $rows->data = $data;

        echo json_encode($rows);
    }

    // -------------------------------------------------------------------------

    public function ajaxEdit()
    {
        echo json_encode($this->getData($this->input->post('id')));
    }

    // -------------------------------------------------------------------------

    public function editLaudos()
    {
        $this->db->select('id_modelo');
        $this->db->where('id', $this->input->post('id'));
        $realizacao = $this->db->get('facilities_realizacoes')->row();

        $itens = $this->getItens($realizacao->id_modelo);

        $data['itens'] = form_dropdown('id_item', $itens, '');
        $data['codigo_localizador'] = md5(uniqid($this->config->item('encryption_key')));

        echo json_encode($data);
    }

    // -------------------------------------------------------------------------

    public function ajaxAdd()
    {
        $data = $this->setData();
        $status = $this->db->insert('facilities_realizacoes', $data);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function salvarLaudo()
    {
        $data = $this->input->post();

        if (empty($data['id_item'])) {
            exit(json_encode(['erro' => 'O campo de ativo/facility é obrigatório']));
        }

        $this->db->select('id');
        $this->db->where('id_realizacao', $data['id_realizacao']);
        $this->db->where('id_item', $data['id_item']);
        $numRows = $this->db->get('facilities_realizacoes_laudos')->num_rows();

        if ($numRows > 0) {
            exit(json_encode(['erro' => 'O ativo/facility selecionado já foi cadastrado']));
        }

        if (!empty($_FILES['arquivo'])) {
            $config['upload_path'] = './arquivos/laudos/';
            $config['allowed_types'] = 'pdf';
            $config['file_name'] = utf8_decode($_FILES['arquivo']['name']);

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('arquivo')) {
                exit(json_encode(['erro' => $this->upload->display_errors('', '')]));
            }

            $arquivo = $this->upload->data();
            $data['arquivo'] = utf8_encode($arquivo['file_name']);
            $data['tipo_mime'] = $arquivo['file_type'];
        } else {
            exit(json_encode(['erro' => 'O campo arquivo é obrigatório']));
        }

        $data['data_cadastro'] = date('Y-m-d H:i:s');

        if (strlen($data['local_armazem']) == 0) {
            $data['local_armazem'] = null;
        }
        if (strlen($data['sala_box']) == 0) {
            $data['sala_box'] = null;
        }
        if (strlen($data['arquivo_fisico']) == 0) {
            $data['arquivo_fisico'] = null;
        }
        if (strlen($data['pasta_caixa']) == 0) {
            $data['pasta_caixa'] = null;
        }

        $status = $this->db->insert('facilities_realizacoes_laudos', $data);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxUpdate()
    {
        $id = $this->input->post('id');

        $data = $this->setData();
        $this->db->set($data);
        $this->db->where('id', $id);
        $status = $this->db->update('facilities_realizacoes');

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_realizacoes', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function ajaxDeleteLaudo()
    {
        $id = $this->input->post('id');
        $laudo = $this->db->get_where('facilities_realizacoes_laudos', ['id' => $id])->row();

        $status = $this->db->delete('facilities_realizacoes_laudos', array('id' => $laudo->id));

        unlink('../../arquivos/laudos/' . $laudo->arquivo);

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    public function realizacao()
    {
        $idManutencao = $this->uri->rsegment(3);

        $this->db->select('id_empresa');
        $this->db->where('id', $this->uri->rsegment(3));
        $row = $this->db->get('facilities_realizacoes')->row();

        $data = $this->dadosRelatorio($idManutencao);


        $this->db->select('MAX(numero_os) + 1 AS numero_os', false);
        $novaOs = $this->db->get('facilities_ordens_servico')->row();
        $data['numeroOS'] = array($novaOs->numero_os => $novaOs->numero_os . ' (nova O. S.)');
        $data['novaOS'] = $novaOs->numero_os;

        $this->db->select('numero_os');
        $this->db->order_by('numero_os', 'desc');
        $os = $this->db->get('facilities_ordens_servico')->result();
        $data['numeroOS'] += array_column($os, 'numero_os', 'numero_os');

        include_once('ordensServico.php');

        $os = new ordensServico();

        $data['os'] = $os->getEstruturas($row->id_empresa);

        $this->load->view('facilities/realizacao_manutencoes', $data);
    }

    // -------------------------------------------------------------------------

    public function relatorio($idManutencao, $pdf = false)
    {
        $data = $this->dadosRelatorio($idManutencao, $pdf);

        if ($pdf) {
            return $this->load->view('facilities/relatorio_manutencoes', $data, true);
        }

        $this->load->view('facilities/relatorio_manutencoes', $data);
    }

    // -------------------------------------------------------------------------

    private function dadosRelatorio($idManutencao, $pdf = false)
    {
        $data = array(
            'idManutencao' => $idManutencao,
            'is_pdf' => $pdf
        );


        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();
        $data['query_string'] = '?id=' . $idManutencao;

        $this->db->select(["b.nome, a.id_modelo, c.nome AS empresa, CONCAT(a.mes, '/', a.ano) AS mes_ano"], false);
        $this->db->join('facilities_modelos b', 'b.id = a.id_modelo');
        $this->db->join('facilities_empresas c', 'c.id = b.id_facility_empresa');
        $this->db->where('a.id', $idManutencao);
        $this->db->where('b.tipo', 'M');
        $manutencao = $this->db->get('facilities_realizacoes a')->row();
        $data['nomeManutencao'] = $manutencao->nome;
        $data['empresaFacilities'] = $manutencao->empresa;
        $data['mesAno'] = $manutencao->mes_ano;


        $sql = "SELECT e.id AS id_item,
                       c.id AS id_subitem,
                       f.id AS id_sala,
                       d.nome AS subitem,
                       'manutencao' AS tipo,
                       e.nome AS item,
                       f.sala,
                       g.andar,
                       h.nome AS unidade,
                       i.id,
                       i.descricao_problema,
                       i.observacoes,
                       i.numero_os,
                       i.vistoriado,
                       i.possui_problema,
                       DATE_FORMAT(i.data_realizacao, '%d/%m/%Y') AS data_realizacao,
                       i.realizacao_cat
                FROM facilities_realizacoes a
                INNER JOIN facilities_modelos b ON b.id = a.id_modelo
                INNER JOIN facilities_modelos_manutencoes c ON c.id_modelo = b.id
                INNER JOIN facilities_manutencoes d ON d.id = c.id_manutencao
                INNER JOIN facilities_itens e ON e.id = d.id_item
                INNER JOIN facilities_salas f ON f.id = e.id_sala
                INNER JOIN facilities_andares g ON g.id = f.id_andar
                INNER JOIN facilities_unidades h ON h.id = g.id_unidade
                LEFT JOIN facilities_realizacoes_manutencoes i ON i.id_realizacao AND i.id_modelo_manutencao = c.id
                WHERE a.id = '{$idManutencao}'";


        $manutencaoItens = $this->db->query($sql)->result();

        $data['manutencoes'] = array();
        $data['totalManutencoes'] = 0;
        $data['totalManutencoes'] = 0;

        foreach ($manutencaoItens as $k => $manutencaoItem) {
            if (!isset($data['manutencoes'][$manutencaoItem->id_sala])) {
                $data['manutencoes'][$manutencaoItem->id_sala]['nome'] = $manutencaoItem;
            }
            $data['manutencoes'][$manutencaoItem->id_sala]['itens'][] = $manutencaoItem;
            $data['manutencoes'][$manutencaoItem->id_sala]['subitens'][] = $manutencaoItem;

            if ($manutencaoItem->tipo === 'V') {
                $data['totalManutencoes']++;
            } elseif ($manutencaoItem->tipo === 'M') {
                $data['totalManutencoes']++;
            }
        }

        return $data;
    }


    public function salvarOS()
    {
        $post = $this->input->post();

        $data = array(
            'id_realizacao' => $post['id_realizacao'],
            'id_modelo_manutencao' => $post['id_modelo_manutencao'],
            'numero_os' => $post['numero_os']
        );

        $this->db->select('numero_os');
        $this->db->where('numero_os', $post['numero_os']);
        $os = $this->db->get('facilities_ordens_servico')->row();

        if (empty($os)) {
            unset($post['id_realizacao'], $post['id_modelo_manutencao']);

            $this->db->insert('facilities_ordens_servico', $post);
        }

        $this->db->where('id_realizacao', $data['id_realizacao']);
        $this->db->where('id_modelo_manutencao', $data['id_modelo_manutencao']);
        $item = $this->db->get('facilities_realizacoes_manutencoes')->row();

        if ($item) {
            $status = $this->db->update('facilities_realizacoes_manutencoes', $data, ['id' => $item->id]);
        } else {
            $status = $this->db->insert('facilities_realizacoes_manutencoes', $data);
        }


        echo json_encode(array("status" => $status !== false));
    }


    public function salvarItens()
    {
        $posts = $this->input->post();
        $data = array();
        foreach ($posts as $field => $post) {
            foreach ($post as $idItem => $value) {
                $data[$idItem]['id_realizacao'] = $this->uri->rsegment(3);
                $data[$idItem]['id_modelo_manutencao'] = $idItem;
                $data[$idItem][$field] = $value;
            }
        }

        $dataInsert = array();
        $dataUpdate = array();
        foreach ($data as $row) {
            if (strlen($row['data_realizacao']) > 0) {
                $row['data_realizacao'] = date('Y-m-d', strtotime(str_replace('/', '-', $row['data_realizacao'])));
            } else {
                $row['data_realizacao'] = null;
            }
            if ($row['id']) {
                $dataUpdate[] = $row;
            } else {
                $dataInsert[] = $row;
            }
        }

        $status = true;

        if ($dataInsert) {
            $status = $this->db->insert_batch('facilities_realizacoes_manutencoes', $dataInsert);
        }
        if ($dataUpdate and $status) {
            $status = $this->db->update_batch('facilities_realizacoes_manutencoes', $dataUpdate, 'id');
        }

        if (!($status !== false)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar itens de manutenção, tente novamente')));
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Itens de manutenção salvos com sucesso', 'redireciona' => 1, 'pagina' => site_url('facilities/manutencoes/realizacao/' . $this->uri->rsegment(3))));

    }

    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = '#table thead tr th { border-top: 4px solid #ddd; padding-top: 8px; } ';
        $stylesheet .= '.itens { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '.itens thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '.itens tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';
        $stylesheet .= '#no_itens { border: 1px solid #444; margin-bottom: 0px; } ';
        $stylesheet .= '#no_itens thead th { font-size: 12px; padding: 4px; background-color: #f5f5f5; border: 1px solid #444; } ';
        $stylesheet .= '#no_itens tbody td { font-size: 10px; padding: 4px; vertical-align: top; border: 1px solid #444; } ';

        $this->m_pdf->pdf->setTopMargin(54);
        $this->m_pdf->pdf->AddPage('L');
        $this->m_pdf->pdf->writeHTML($stylesheet, 1);

        $data = $this->input->get();
        $this->m_pdf->pdf->writeHTML($this->relatorio($data['id'], true));


        $this->load->library('Calendar');
        $this->calendar->month_type = 'short';
        $nome = 'Apontamento de Insumos - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

        $this->m_pdf->pdf->Output($nome . '.pdf', 'D');
    }

    /* -------------------------------------------------------------------------
     *
     * -------------------------------------------------------------------------
     */

    protected function getData($id = '')
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        return $this->db->get_where('facilities_realizacoes')->row();
    }

    // -------------------------------------------------------------------------

    private function getMeses()
    {
        return array(
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
    }

    // -------------------------------------------------------------------------

    private function getItens($idModelo = '')
    {

        $this->db->select('a.id, a.nome');
        $this->db->join('facilities_manutencoes b', 'b.id_item = a.id');
        $this->db->join('facilities_modelos_manutencoes c', 'c.id_manutencao = b.id');
        $this->db->join('facilities_modelos d', 'd.id = c.id_modelo');
        $this->db->where('d.id', $idModelo);
        $this->db->order_by('a.nome', 'asc');
        $rows = $this->db->get('facilities_itens a')->result();

        $data = ['' => 'selecione...'];
        if ($rows) {
            $data += array_column($rows, 'nome', 'id');
        }

        return $data;
    }

    // -------------------------------------------------------------------------

    protected function getModelosManutencoes()
    {
        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('tipo', 'M');
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('facilities_modelos')->result();

        return array_column($rows, 'nome', 'id');
    }

    // -------------------------------------------------------------------------

    protected function getResponsaveis()
    {
        $this->db->select('id, nome');
        $this->db->where('empresa', $this->session->userdata('empresa'));
        $this->db->where('tipo', 'funcionario');
        $this->db->where('nivel_acesso', 17);
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('usuarios')->result();

        return array_column($rows, 'nome', 'id');
    }

    // -------------------------------------------------------------------------

    protected function setData()
    {
        $data = $this->input->post();

        if (strlen($data['id_modelo']) == 0) {
            exit(json_encode(['erro' => 'O modelo de manutenção é obrigatório']));
        }

        if (strlen($data['ano']) == 0) {
            exit(json_encode(['erro' => 'O ano é obrigatório']));
        }

        if (strlen($data['id_usuario_vistoriador']) == 0) {
            $data['id_usuario_vistoriador'] = null;
        }

        if (strlen($data['tipo_executor']) == 0) {
            $data['tipo_executor'] = null;
        }

        if (!isset($data['pendencias'])) {
            $data['pendencias'] = 0;
        }

        unset($data['id']);

        return $data;
    }

}
