<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Produtos extends MY_Controller
{
    //==========================================================================
    public function __construct()
    {
        parent::__construct();

        $this->load->model('icom_produtos_model', 'produtos');
    }

    //==========================================================================
    public function index()
    {
        $empresa = $this->session->userdata('empresa');

        $deptos = $this->db
            ->select('id, nome')
            ->where('id_empresa', $empresa)
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $data = [
            'empresa' => $empresa,
            'tipos' => ['' => 'selecione...'] + $this->produtos::tipo(),
            'tiposPreco' => ['' => 'selecione...'] + $this->produtos::tipoPreco(),
            'deptos' => ['' => 'Todos'] + array_column($deptos, 'nome', 'id'),
            'areas' => ['' => 'Todas'],
            'setores' => ['' => 'Todos'],
            'depto_atual' => '',
            'area_atual' => '',
            'setor_atual' => ''
        ];

        $this->load->view('icom/produtos', $data);
    }

    //==========================================================================
    public function filtrarEstrutura()
    {
        $depto = $this->input->post('id_depto');
        $area = $this->input->post('id_area');
        $setor = $this->input->post('id_setor');

        $rowAreas = $this->db
            ->select('id, nome')
            ->where('id_departamento', $depto)
            ->order_by('nome', 'asc')
            ->get('empresa_areas')
            ->result();

        $areas = ['' => 'Todas'] + array_column($rowAreas, 'nome', 'id');

        $rowSetores = $this->db
            ->select('a.id, a.nome')
            ->join('empresa_areas b', 'b.id = a.id_area')
            ->where('a.id_area', $area)
            ->where('b.id_departamento', $depto)
            ->order_by('a.nome', 'asc')
            ->get('empresa_setores a')
            ->result();

        $setores = ['' => 'Todos'] + array_column($rowSetores, 'nome', 'id');

        $data = [
            'areas' => form_dropdown('id_area', $areas, $area, 'onchange="filtrar_alocacao();" class="form-control input-sm"'),
            'setores' => form_dropdown('id_setor', $setores, $setor, 'class="form-control input-sm"')
        ];

        echo json_encode($data);
    }

    //==========================================================================
    public function listar()
    {
        parse_str($this->input->post('busca'), $busca);

        $this->db
            ->select('a.*', false)
            ->join('empresa_setores b', 'b.id = a.id_setor', 'left')
            ->join('empresa_areas c', 'c.id = b.id_area', 'left')
            ->join('empresa_departamentos d', 'd.id = c.id_departamento', 'left');
        if ($busca['id_depto']) {
            $this->db->where('d.id', $busca['id_depto']);
        }
        if ($busca['id_area']) {
            $this->db->where('c.id', $busca['id_area']);
        }
        if ($busca['id_setor']) {
            $this->db->where('b.id', $busca['id_setor']);
        }
        $query = $this->db
            ->where('a.id_empresa', $this->session->userdata('empresa'))
            ->get($this->produtos::table() . ' a');

        $config = [
            'select' => ['nome', 'tipo', 'id'],
            'search' => ['codigo', 'nome']
        ];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $data = [];

        foreach ($output->data as $row) {
            $data[] = array(
                $row->nome,
                $this->produtos::tipo($row->tipo),
                '<button class="btn btn-sm btn-info" onclick="edit_produto(' . $row->id . ')" title="Editar produto"><i class="glyphicon glyphicon-pencil"></i></button>
                 <button class="btn btn-sm btn-danger" onclick="delete_produto(' . $row->id . ')" title="Excluir produto"><i class="glyphicon glyphicon-trash"></i></button>'
            );
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function editar()
    {
        $data = $this->produtos->find($this->input->post('id'));

        if (empty($data)) {
            exit(json_encode(['erro' => $this->produtos->errors()]));
        }

        $data->preco = number_format($data->preco, 2, ',', '.');

        echo json_encode($data);
    }

    //==========================================================================
    public function salvar()
    {
        $this->load->library('entities');

        $data = $this->entities->create('icomProdutos', $this->input->post());

        $this->produtos->setValidationLabel('codigo', 'Código');
        $this->produtos->setValidationLabel('nome', 'Nome');
        $this->produtos->setValidationLabel('tipo', 'Tipo');
        $this->produtos->setValidationLabel('preco', 'Preço de Locação');
        $this->produtos->setValidationLabel('tipo_preco', 'Tipo Preço');

        $this->produtos->save($data) or exit(json_encode(['erro' => $this->produtos->errors()]));

        echo json_encode(['status' => true]);
    }

    //==========================================================================
    public function excluir()
    {
        $this->produtos->delete($this->input->post('id')) or exit(json_encode(['erro' => $this->produtos->errors()]));

        echo json_encode(['status' => true]);
    }

}
