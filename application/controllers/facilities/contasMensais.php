<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ContasMensais extends MY_Controller
{

    public function index()
    {
        $data = $this->getItensDespesas();
        $data['empresa'] = $this->session->userdata('empresa');

        $this->db->select('DISTINCT(tipo)', false);
        $this->db->where('ativo', 1);
        $this->db->where('tipo IS NOT NULL');
        $rows = $this->db->get('facilities_itens')->result();
        $data['tipos'] = ['' => 'digite ou selecione...'] + array_column($rows, 'tipo', 'tipo');


        $this->load->view('facilities/contas_mensais', $data);
    }


    private function getItensDespesas($where = [])
    {
        $this->db->select('id, nome');
        $this->db->order_by('nome', 'asc');
        $empresas = $this->db->get('facilities_contas_empresas')->result();


        $this->db->select('id, nome');
        $this->db->order_by('nome', 'asc');
        if (!empty($where['id_conta_empresa'])) {
            $this->db->where('id_conta_empresa', $where['id_conta_empresa']);
        }
        $unidades = $this->db->get('facilities_contas_unidades')->result();


        $this->db->select('a.id, a.nome');
        $this->db->join('facilities_contas_unidades b', 'b.id = a.id_unidade');
        $this->db->order_by('a.nome', 'asc');
        if (!empty($where['id_conta_empresa'])) {
            $this->db->where('b.id_conta_empresa', $where['id_conta_empresa']);
        }
        if (!empty($where['id_unidade'])) {
            $this->db->where('a.id_unidade', $where['id_unidade']);
        }
        $itens = $this->db->get('facilities_contas_itens a')->result();


        $data['empresas'] = ['' => 'selecione...'] + array_column($empresas, 'nome', 'id');
        $data['unidades'] = ['' => 'selecione...'] + array_column($unidades, 'nome', 'id');
        $data['itens'] = ['' => 'selecione...'] + array_column($itens, 'nome', 'id');


        return $data;
    }


    public function atualizarItensDespesas()
    {
        $where = $this->input->post();


        $itensDespesas = $this->getItensDespesas($where);


        $data['empresas'] = form_dropdown('', $itensDespesas['empresas'], $where['id_conta_empresa']);
        $data['unidades'] = form_dropdown('', $itensDespesas['unidades'], $where['id_unidade']);
        $data['itens'] = form_dropdown('', $itensDespesas['itens'], $where['id_item']);


        echo json_encode($data);
    }


    public function ajaxList()
    {
        $this->db->select('a.nome AS empresa, b.nome AS unidade, c.nome AS item');
        $this->db->select("CONCAT(d.mes, '/', d.ano) AS mes_ano", false);
        $this->db->select('NULL AS consumo, d.valor, d.data_vencimento, a.id', false);
        $this->db->select("FORMAT(d.valor, 2, 'de_DE') AS valor_de", false);
        $this->db->select("DATE_FORMAT(d.data_vencimento, '%d/%m/%Y') AS data_vencimento_de", false);
        $this->db->join('facilities_contas_unidades b', 'b.id_conta_empresa = a.id');
        $this->db->join('facilities_contas_itens c', 'c.id_unidade = b.id');
        $this->db->join('facilities_contas_despesas d', 'd.id_item = c.id');
        $query = $this->db->get('facilities_contas_empresas a');


        $options = array(
            'search' => ['empresa', 'unidade', 'item']
        );

        $this->load->library('dataTables', $options);


        $output = $this->datatables->generate($query);


        $data = array();

        foreach ($output->data as $row) {
            $data[] = array(
                $row->empresa,
                $row->unidade,
                $row->item,
                $row->mes_ano,
                $row->consumo,
                $row->valor_de,
                $row->data_vencimento_de,
                '<button class="btn btn-sm btn-info" onclick="edit_despesa(' . $row->id . ')" title="Editar ativo"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_despesa(' . $row->id . ')" title="Excluir ativo"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }


        $output->data = $data;


        echo json_encode($output);
    }

    public function ajaxEdit()
    {
        $id = $this->input->post('id');


        $this->db->select('a.id, a.id_item, a.nome, a.mes, a.ano');
        $this->db->select("FORMAT(a.valor, 2, 'de_DE') AS valor", false);
        $this->db->select("DATE_FORMAT(a.data_vencimento, '%d/%m/%Y') AS data_vencimento", false);
        $this->db->select('b.id_unidade, c.id_conta_empresa', false);
        $this->db->join('facilities_contas_itens b', 'b.id = a.id_item');
        $this->db->join('facilities_contas_unidades c', 'c.id = b.id_unidade');
        $this->db->where('a.id', $id);
        $data = $this->db->get('facilities_contas_despesas a')->row();


        $where = array(
            'id_conta_empresa' => $data->id_conta_empresa,
            'id_unidade' => $data->id_unidade
        );


        $itensDespesas = $this->getItensDespesas($where);


        $data->empresas = form_dropdown('', $itensDespesas['empresas'], $where['id_conta_empresa']);
        $data->unidades = form_dropdown('', $itensDespesas['unidades'], $where['id_unidade']);
        $data->itens = form_dropdown('', $itensDespesas['itens'], $data->id_item);


        echo json_encode($data);
    }


    public function ajaxAdd()
    {
        $data = $this->setData();

        $status = $this->db->insert('facilities_contas_despesas', $data);


        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxUpdate()
    {
        $id = $this->input->post('id');

        $data = $this->setData();

        $status = $this->db->update('facilities_contas_despesas', $data, array('id' => $id));


        echo json_encode(array("status" => $status !== false));
    }


    public function ajaxDelete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('facilities_contas_despesas', array('id' => $id));

        echo json_encode(array("status" => $status !== false));
    }

    // -------------------------------------------------------------------------

    protected function setData()
    {
        $data = $this->input->post();

        if (empty($data['id_item'])) {
            exit(json_encode(['erro' => 'O item de despesa é obrigatório']));
        }

        if (strlen($data['valor']) == 0) {
            exit(json_encode(['erro' => 'O valor é obrigatório']));
        } else {
            $data['valor'] = str_replace(['.', ','], ['', '.'], $data['valor']);
        }

        if (strlen($data['data_vencimento']) == 0) {
            exit(json_encode(['erro' => 'O vencimento é obrigatório']));
        }

        if (empty($data['mes'])) {
            exit(json_encode(['erro' => 'O mês é obrigatório']));
        }

        if (strlen($data['ano']) == 0) {
            exit(json_encode(['erro' => 'O ano é obrigatório']));
        }

        if (strlen($data['data_vencimento']) > 0) {

            $dataVencimento = date('Y-m-d', strtotime(str_replace('/', '-', $data['data_vencimento'])));
            if ($data['data_vencimento'] != preg_replace('/(\d+)-(\d+)-(\d+)/', '$3/$2/$1', $dataVencimento)) {
                exit(json_encode(['erro' => 'O vencimento é inválido.']));
            }

            $data['data_vencimento'] = $dataVencimento;
        } else {
            exit(json_encode(['erro' => 'O vencimento é obrigatório.']));
        }


        unset($data['id']);

        return $data;
    }

}
