<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pesquisa_modelos extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
//        $this->load->model('Pesquisa_model', 'pesquisa');
    }

    public function index()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => '',
            'nome' => 'Modelo de pesquisa'
        );

        $this->load->view('pesquisa_modelos', $data);
    }

    public function instrucoes()
    {
        $this->db->where('id', $this->uri->rsegment(3));
        $modelo = $this->db->get('pesquisa_modelos')->row();
        if ($modelo->exclusao_bloqueada) {
            redirect(site_url('pesquisa_modelos'));
            exit;
        }
        $data = array(
            'modelo' => $modelo->id,
            'nome' => $modelo->nome,
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => '',
            'instrucoes' => $modelo->instrucoes
        );

        $this->load->view('pesquisa_instrucoes', $data);
    }

    public function clima()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => 'clima',
            'nome' => 'Modelo de pesquisa de Clima Organizacional'
        );

        $this->load->view('pesquisa_modelos', $data);
    }

    public function personalidade()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => 'eneagrama',
            'nome' => 'Modelo de pesquisa de Avaliação de Personalidade'
        );

        $this->load->view('pesquisa_modelos', $data);
    }

    public function perfil()
    {
        $data = array(
            'empresa' => $this->session->userdata('empresa'),
            'tipo' => 'perfil',
            'nome' => 'Modelo de pesquisa de Perfil Profissional'
        );

        $this->load->view('pesquisa_modelos', $data);
    }

    public function ajax_list($id, $tipo = '')
    {
        if (empty($id)) {
            $id = $this->session->userdata('empresa');
        }
        $post = $this->input->post();

        $sql = "SELECT s.id, 
                       s.nome, 
                       s.empresa,
                       s.nome_tipo,
                       s.observacoes,
                       s.exclusao_bloqueada,
                       s.tipo
                FROM (SELECT a.id, 
                             a.nome, 
                             a.id_usuario_EMPRESA AS empresa,
                             (CASE a.tipo 
                                   WHEN 'C' THEN 'Pesquisa de Clima Organizacional' 
                                   WHEN 'P' THEN 'Pesquisa de Perfil Profissional (uma única resposta)'
                                   WHEN 'M' THEN 'Pesquisa de Perfil Profissional (múltiplas respostas)'
                                   WHEN 'E' THEN 'Avaliação de Personalidade (Eneagrama)' 
                                   WHEN 'Q' THEN 'Avaliação de Personalidade (Tipologia Junguiana)' 
                                   WHEN 'O' THEN 'Avaliação de Personalidade (Orientações de Vida)' 
                                   WHEN 'N' THEN 'Avaliação de Potencial (NineBox)'
                                   ELSE '' END) AS nome_tipo,
                             a.observacoes,
                             a.exclusao_bloqueada,
                             a.tipo
                      FROM pesquisa_modelos a
                      WHERE a.id_usuario_EMPRESA = {$id}";
        if ($tipo) {
            $sql .= " AND a.tipo = '{$tipo}'";
        }
        $sql .= ") s";
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
                $orderBy[] = ($order['column'] + 1) . ' ' . $order['dir'];
            }
            $sql .= ' 
                    ORDER BY ' . implode(', ', $orderBy);
        }
        $sql .= " 
                LIMIT {$post['start']}, {$post['length']}";
        $list = $this->db->query($sql)->result();

        $data = array();
        foreach ($list as $pesquisa) {
            $urlEstilo = '';
            $row = array();
            $row[] = $pesquisa->nome;
            $row[] = $pesquisa->nome_tipo;
            switch ($pesquisa->tipo) {
                case 'C': // clima
                    $uri = 'pesquisa_questoes';
                    break;
                case 'E': // personalidade
                    $uri = 'pesquisa_personalidade';
                    break;
                case 'Q': // personalidade (jung)
                    $uri = 'pesquisaQuati';
                    $urlEstilo = 'pesquisaQuati/estilos';
                    break;
                case 'P': // perfil
                case 'M': // perfil múltiplo
                    $uri = 'pesquisa_alternativas';
                    break;
                case 'O': // orientações de vida
                    $uri = 'pesquisa_orientacoes';
                    $urlEstilo = 'pesquisa_lifo/estilos';
                    break;
                default:
                    $uri = 'pesquisa_questoes';
            }

            if (empty($pesquisa->exclusao_bloqueada)) {
                $row[] = '
			              <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_pesquisa(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			              <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="delete_pesquisa(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
			              <a class="btn btn-sm btn-primary" href="' . site_url('pesquisa_modelos/instrucoes/' . $pesquisa->id) . '" title="Editar instruções" ><i class="glyphicon glyphicon-pencil"></i> Editar instruções</a>
			              <a class="btn btn-sm btn-primary" href="' . site_url($uri . '/gerenciar/' . $pesquisa->id) . '" title="Editar questões" ><i class="glyphicon glyphicon-list"></i> Editar questões</a>
			              <a class="btn btn-sm btn-primary ' . ($urlEstilo ? '' : 'disabled') . '" href="' . site_url($urlEstilo) . '" title="Editar questões" >Configurar estilos</a>
			         	 ';
            } else {
                $row[] = '
			              <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Editar" onclick="edit_pesquisa(' . "'" . $pesquisa->id . "'" . ')"><i class="glyphicon glyphicon-pencil"></i></a>
			              <button class="btn btn-sm btn-danger disabled" title="Excluir"><i class="glyphicon glyphicon-trash"></i></button>
			              <button class="btn btn-sm btn-primary disabled" title="Editar instruções" ><i class="glyphicon glyphicon-pencil"></i> Editar instruções</button>
			              <button class="btn btn-sm btn-primary disabled" title="Editar questões" ><i class="glyphicon glyphicon-list"></i> Editar questões</button>
			              <a class="btn btn-sm btn-primary ' . ($urlEstilo ? '' : 'disabled') . '" href="' . site_url($urlEstilo) . '" title="Editar questões" >Configurar estilos</a>
			         	 ';
            }

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

    public function ajax_edit($id)
    {
        $data = $this->db->get_where('pesquisa_modelos', array('id' => $id))->row();
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $data = array(
            'id_usuario_EMPRESA' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $this->input->post('tipo'),
            'observacoes' => $this->input->post('observacoes'),
            'exclusao_bloqueada' => $this->input->post('exclusao_bloqueada')
        );
        if (empty($data['nome'])) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }
        $status = $this->db->insert('pesquisa_modelos', $data);
        echo json_encode(array("status" => $status !== false));
    }

    public function ajax_update()
    {
        $data = array(
            'id_usuario_EMPRESA' => $this->input->post('empresa'),
            'nome' => $this->input->post('nome'),
            'tipo' => $this->input->post('tipo'),
            'observacoes' => $this->input->post('observacoes'),
            'exclusao_bloqueada' => $this->input->post('exclusao_bloqueada')
        );
        if (empty($data['nome'])) {
            die(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não deve ficar sem nome')));
        }
        $status = $this->db->update('pesquisa_modelos', $data, array('id' => $this->input->post('id')));
        echo json_encode(array("status" => $status !== false));
    }

    public function salvar_instrucoes()
    {
        if (empty($this->input->post('id'))) {
            die(json_encode(array('retorno' => 0, 'aviso' => 'O modelo não foi encontrado')));
        }

        if (strlen($this->input->post('instrucoes')) > 0) {
            $data = array('instrucoes' => $this->input->post('instrucoes'));
        } else {
            $data = array('instrucoes' => null);
        }

        if (!$this->db->update('pesquisa_modelos', $data, array('id' => $this->input->post('id')))) {
            exit(json_encode(array('retorno' => 0, 'aviso' => 'Erro ao editar instruções, tente novamente')));
        }
        echo json_encode(array('retorno' => 1, 'aviso' => 'Instruções editadas com sucesso', 'redireciona' => 1, 'pagina' => site_url('pesquisa_modelos')));
    }

    public function ajax_delete($id)
    {
        $status = $this->db->delete('pesquisa_modelos', array('id' => $id));
        echo json_encode(array("status" => $status !== false));
    }

    public function get_tipo()
    {
        $id = $this->input->post('id');
        $row = $this->db->get_where('pesquisa_modelos', array('id' => $id))->row();
        $result = '';
        if (count($row) == 1) {
            $result = $row->tipo;
        }
        echo $result;
    }

}
