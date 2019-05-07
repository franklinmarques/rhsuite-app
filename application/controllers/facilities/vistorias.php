<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vistorias extends MY_Controller
{

    public function index()
    {
        $data['idEmpresa'] = $this->session->userdata('empresa');
        $data['modelos'] = ['' => 'selecione...'] + $this->getModelosVistorias();
        $data['vistoriadores'] = ['' => 'selecione...'] + $this->getVistoriadores();
        $data['meses'] = $this->getMeses();

        $this->load->view('facilities/vistorias', $data);
    }

    // -------------------------------------------------------------------------

    public function ajaxList()
    {
        $post = $this->input->post();

        $this->db->select(["b.nome AS modelo, CONCAT(a.ano, '-', a.mes) AS data, a.status, a.pendencias, c.nome AS vistoriador"], false);
        $this->db->select(["(CASE a.status WHEN 'P' THEN 'Programada' WHEN 'N' THEN 'Não realizada' WHEN 'R' THEN 'Realizada' END) AS nome_status"], false);
        $this->db->select(["CONCAT(a.mes, '/', a.ano) AS mes_ano, IF(a.pendencias = 1, 'Com pendências', 'Sem pendências') AS nome_pendencia, a.id"], false);
        $this->db->join('facilities_modelos b', "b.id = a.id_modelo AND b.tipo = 'V'");
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
            'search' => ['modelo', 'vistoriador']
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
                $row->vistoriador,
                '<button class="btn btn-sm btn-info" onclick="edit_vistoria(' . $row->id . ');" title="Editar vistoria"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_vistoria(' . $row->id . ');" title="Excluir vistoria"><i class="glyphicon glyphicon-trash"></i></button>
                 <a class="btn btn-sm btn-primary" href="' . site_url('facilities/vistorias/realizacao/' . $row->id) . '" target="_blank" title="Cadastrar realização de vistoria"><i class="glyphicon glyphicon-list-alt"></i> Cadastrar realização</a>
                 <a class="btn btn-sm btn-primary" href="' . site_url('facilities/vistorias/relatorio/' . $row->id) . '" target="_blank" title="Imprimir vistoria"><i class="glyphicon glyphicon-list-alt"></i> Imprimir</a>',
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

    public function ajaxEdit()
    {
        echo json_encode($this->getData($this->input->post('id')));
    }

    // -------------------------------------------------------------------------

    public function ajaxAdd()
    {
        $data = $this->setData();
        $status = $this->db->insert('facilities_realizacoes', $data);

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

    public function realizacao()
    {
        $idVistoria = $this->uri->rsegment(3);

        $this->db->select('id_empresa, mes, ano');
        $this->db->where('id', $this->uri->rsegment(3));
        $row = $this->db->get('facilities_realizacoes')->row();

        $data = $this->dadosRelatorio($idVistoria);

        $data['query_string'] = '?id=' . $idVistoria . '&mes=' . $row->mes . '&ano=' . $row->ano;
        $data['idUsuario'] = $this->session->userdata('id');
        $data['is_pdf'] = false;


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

        $this->load->view('facilities/realizacao_vistorias', $data);
    }

    // -------------------------------------------------------------------------

    public function relatorio($idVistoria, $pdf = false)
    {
        $data = $this->dadosRelatorio($idVistoria, $pdf);

        if ($pdf) {
            return $this->load->view('facilities/relatorio_vistorias', $data, true);
        }

        $this->load->view('facilities/relatorio_vistorias', $data);
    }

    // -------------------------------------------------------------------------

    private function dadosRelatorio($idVistoria, $pdf = false)
    {
        $data = array(
            'idVistoria' => $idVistoria,
            'is_pdf' => $pdf
        );


        $this->db->select('foto, foto_descricao');
        $this->db->where('id', $this->session->userdata('empresa'));
        $data['empresa'] = $this->db->get('usuarios')->row();

        $this->db->select(["b.nome, a.id_modelo, c.nome AS empresa, a.mes, a.ano, CONCAT(a.mes, '/', a.ano) AS mes_ano"], false);
        $this->db->join('facilities_modelos b', 'b.id = a.id_modelo');
        $this->db->join('facilities_empresas c', 'c.id = b.id_facility_empresa');
        $this->db->where('a.id', $idVistoria);
        $this->db->where('b.tipo', 'V');
        $vistoria = $this->db->get('facilities_realizacoes a')->row();
        $data['nomeVistoria'] = $vistoria->nome;
        $data['empresaFacilities'] = $vistoria->empresa;
        $data['mesAno'] = $vistoria->mes_ano;
        $data['query_string'] = '?id=' . $idVistoria . '&mes=' . $vistoria->mes . '&ano=' . $vistoria->ano;


        $sql = "SELECT e.id AS id_item,
                       c.id AS id_subitem,
                       f.id AS id_sala,
                       d.nome AS subitem,
                       'vistoria' AS tipo,
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
                INNER JOIN facilities_modelos_vistorias c ON c.id_modelo = b.id
                INNER JOIN facilities_vistorias d ON d.id = c.id_vistoria
                INNER JOIN facilities_itens e ON e.id = d.id_item
                INNER JOIN facilities_salas f ON f.id = e.id_sala
                INNER JOIN facilities_andares g ON g.id = f.id_andar
                INNER JOIN facilities_unidades h ON h.id = g.id_unidade
                LEFT JOIN facilities_realizacoes_vistorias i ON i.id_realizacao AND i.id_modelo_vistoria = c.id
                WHERE a.id = '{$idVistoria}'";


        $vistoriaItens = $this->db->query($sql)->result();

        $data['vistorias'] = array();
        $data['totalVistorias'] = 0;
        $data['totalManutencoes'] = 0;

        foreach ($vistoriaItens as $k => $vistoriaItem) {
            if (!isset($data['vistorias'][$vistoriaItem->id_sala])) {
                $data['vistorias'][$vistoriaItem->id_sala]['nome'] = $vistoriaItem;
            }
            $data['vistorias'][$vistoriaItem->id_sala]['itens'][] = $vistoriaItem;
            $data['vistorias'][$vistoriaItem->id_sala]['subitens'][] = $vistoriaItem;

            if ($vistoriaItem->tipo === 'V') {
                $data['totalVistorias']++;
            } elseif ($vistoriaItem->tipo === 'M') {
                $data['totalManutencoes']++;
            }
        }

        return $data;
    }


    public function salvarOS()
    {
        $numeroOS = $this->input->post('numero_os');

        $dataOS = $this->setDataOS();


        $this->db->trans_start();


        $this->db->select('numero_os');
        $this->db->where('numero_os', $numeroOS);
        $os = $this->db->get('facilities_ordens_servico')->row();

        if ($os) {
            $this->db->update('facilities_ordens_servico', $dataOS, ['numero_os' => $numeroOS]);
        } else {
            $this->db->insert('facilities_ordens_servico', $dataOS);
        }


        $data = array(
            'id_realizacao' => $this->input->post('id_realizacao'),
            'id_modelo_vistoria' => $this->input->post('id_modelo_vistoria'),
            'numero_os' => $numeroOS
        );

        $this->db->where('id_realizacao', $data['id_realizacao']);
        $this->db->where('id_modelo_vistoria', $data['id_modelo_vistoria']);
        $item = $this->db->get('facilities_realizacoes_vistorias')->row();

        if ($item) {
            $this->db->update('facilities_realizacoes_vistorias', $data, ['id' => $item->id]);
        } else {
            $this->db->insert('facilities_realizacoes_vistorias', $data);
        }


        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(array("status" => $status !== false));
    }


    public function salvarItens()
    {
        $posts = $this->input->post();
        $data = array();
        foreach ($posts as $field => $post) {
            foreach ($post as $idItem => $value) {
                $data[$idItem]['id_realizacao'] = $this->uri->rsegment(3);
                $data[$idItem]['id_modelo_vistoria'] = $idItem;
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
            $status = $this->db->insert_batch('facilities_realizacoes_vistorias', $dataInsert);
        }
        if ($dataUpdate and $status) {
            $status = $this->db->update_batch('facilities_realizacoes_vistorias', $dataUpdate, 'id');
        }

        if (!($status !== false)) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao salvar itens de vistoria, tente novamente')));
        }

        echo json_encode(array('retorno' => 1, 'aviso' => 'Itens de vistoria salvos com sucesso', 'redireciona' => 1, 'pagina' => site_url('facilities/vistorias/realizacao/' . $this->uri->rsegment(3))));

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
        $nome = 'Programa de Vistoria Periódica - ' . $this->calendar->get_month_name($data['mes']) . '_' . $data['ano'];

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

    protected function getModelosVistorias()
    {
        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where('tipo', 'V');
        $this->db->order_by('nome', 'asc');
        $rows = $this->db->get('facilities_modelos')->result();

        return array_column($rows, 'nome', 'id');
    }

    // -------------------------------------------------------------------------

    protected function getVistoriadores()
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
            exit(json_encode(['erro' => 'O modelo de vistoria é obrigatório']));
        }

        if (strlen($data['ano']) == 0) {
            exit(json_encode(['erro' => 'O ano é obrigatório']));
        }

        if (strlen($data['id_usuario_vistoriador']) == 0) {
            $data['id_usuario_vistoriador'] = null;
        }

        if (!isset($data['pendencias'])) {
            $data['pendencias'] = 0;
        }

        unset($data['id']);

        return $data;
    }

    // -------------------------------------------------------------------------

    protected function setDataOS()
    {
        $data = $this->input->post();

        if (strlen($data['data_abertura']) > 0) {

            $dataAbertura = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_abertura'])));
            if ($data['data_abertura'] != preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $dataAbertura)) {
                exit(json_encode(['erro' => 'A data de abertura é inválida.']));
            }

            $data['data_abertura'] = $dataAbertura;
        } else {
            exit(json_encode(['erro' => 'A data de abertura é obrigatória.']));
        }

        if (strlen($data['data_fechamento']) > 0) {

            $dataFechamento = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_fechamento'])));
            if ($data['data_fechamento'] != preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $dataFechamento)) {
                exit(json_encode(['erro' => 'A data de fechamento é inválida.']));
            }

            if (strtotime($dataFechamento) < strtotime($data['data_abertura'])) {
                exit(json_encode(['erro' => 'A data de fechamento deve ser maior ou igual à data de abertura.']));
            }

            $data['data_fechamento'] = $dataFechamento;
        } else {
            $data['data_fechamento'] = null;
        }

        if (strlen($data['id_depto']) == 0) {
            $data['id_depto'] = null;
        }
        if (strlen($data['id_area']) == 0) {
            $data['id_area'] = null;
        }
        if (strlen($data['id_setor']) == 0) {
            $data['id_setor'] = null;
        }

        if (strlen($data['descricao_problema']) == 0) {
            $data['descricao_problema'] = null;
        }

        if (strlen($data['observacoes']) == 0) {
            $data['observacoes'] = null;
        }


        unset($data['numero_os'], $data['id_realizacao'], $data['id_modelo_vistoria']);

        return $data;
    }

}
