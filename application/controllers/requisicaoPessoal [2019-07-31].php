<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class RequisicaoPessoal extends MY_Controller
{
    public function index()
    {
        $this->initialize();
    }

    //==========================================================================
    public function admFin()
    {
        $this->initialize('ADM-FIN');
    }

    //==========================================================================
    public function cd()
    {
        $this->initialize('CD');
    }

    //==========================================================================
    public function cdh()
    {
        $this->initialize('CDH');
    }

    //==========================================================================
    public function ei()
    {
        $this->initialize('EI');
    }

    //==========================================================================
    public function gexec()
    {
        $this->initialize('GExec');
    }

    //==========================================================================
    public function icom()
    {
        $this->initialize('ICOM');
    }

    //==========================================================================
    public function papd()
    {
        $this->initialize('PAPD');
    }

    //==========================================================================
    public function st()
    {
        $this->initialize('ST');
    }

    //==========================================================================
    private function initialize($modulo = null)
    {
        $id = $this->session->userdata('id');
        $empresa = $this->session->userdata('empresa');
        $tipo = $this->session->userdata('tipo');
        $nivelAcesso = $this->session->userdata('nivel');

        $data = [
            'tipo' => $tipo,
            'nivel' => $nivelAcesso,
            'depto' => '',
            'id_depto' => '',
            'usuario' => $this->db->select('depto')->where('id', $id)->get('usuarios')->row_array(),
            'deptos' => [],
            'areas' => [],
            'setores' => [],
            'cargos' => ['' => 'selecione...'],
            'funcoes' => ['' => 'selecione...'],
            'modulo' => ''
        ];


        $usuario = $this->db
            ->select('depto, id_depto, area, setor')
            ->where('id', $id)
            ->get('usuarios')
            ->row();

        if ($tipo != 'empresa') {
            $data['depto'] = $usuario->depto;
            $data['id_depto'] = $usuario->id_depto;
        }


        $data['deptos'] = ['' => 'Todos'];
        if ($tipo != 'funcionario') {
            $data['areas'] = ['' => 'Todas'];
            $data['setores'] = ['' => 'Todos'];
        }

        switch ($modulo) {
            case 'ADM-FIN':
                $data['modulo'] = '';
                break;
            case 'CD':
                $data['modulo'] = 'Cuidadores';
                break;
            case 'CDH':
                $data['modulo'] = '';
                break;
            case 'EI':
                $data['modulo'] = 'Educação Inclusiva';
                break;
            case 'GExec':
                $data['modulo'] = '';
                break;
            case 'ICOM':
                $data['modulo'] = '';
                break;
            case 'PAPD':
                $data['modulo'] = 'Programa de Apoio a Pessoa com Deficiência';
                break;
            case 'ST':
                $data['modulo'] = 'Serviços Terceirizados';
        }

        $this->db->select('id, nome');
        if ($tipo == 'funcionario' and !in_array($nivelAcesso, [7, 8, 18])) {
            $this->db->where('nome', $usuario->depto);
        }
        $deptos = $this->db
            ->where('id_empresa', $empresa)
            ->order_by('nome', 'asc')
            ->get('empresa_departamentos')
            ->result();

        $data['deptos'] += array_column($deptos, 'nome', 'id');


        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        if ($tipo == 'funcionario' and !in_array($nivelAcesso, [7, 8, 18])) {
            $this->db->where('b.nome', $usuario->depto);
            $this->db->where('a.nome', $usuario->area);
        }
        $areas = $this->db
            ->where('b.id_empresa', $empresa)
            ->order_by('a.nome', 'asc')
            ->get('empresa_areas a')
            ->result();

        $data['areas'] += array_column($areas, 'nome', 'id');


        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        if ($tipo == 'funcionario' and !in_array($nivelAcesso, [7, 8, 18])) {
            $this->db->where('c.nome', $usuario->depto);
            $this->db->where('b.nome', $usuario->area);
            $this->db->where('a.nome', $usuario->setor);
        }
        $setores = $this->db
            ->where('c.id_empresa', $empresa)
            ->order_by('a.nome', 'asc')
            ->get('empresa_setores a')
            ->result();

        $data['setores'] += array_column($setores, 'nome', 'id');


        $this->db->select('id, nome');
        $this->db->where('empresa', $empresa);
        $this->db->where_in('tipo', array('funcionario', 'selecionador'));
        if ($tipo == 'funcionario' and !in_array($nivelAcesso, [7, 8, 18])) {
            $this->db->where('id', $id);
        } else {
            $data['requisitantes'] = ['' => 'selecione...'];
        }
        $this->db->order_by('nome', 'asc');
        $requisitantes = $this->db->get('usuarios')->result();
        foreach ($requisitantes as $requisitante) {
            $data['requisitantes'][$requisitante->id] = $requisitante->nome;
        }


        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $cargos = $this->db->get('empresa_cargos')->result();
        foreach ($cargos as $cargo) {
            $data['cargos'][$cargo->id] = $cargo->nome;
        }


        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->order_by('a.nome', 'asc');
        $funcoes = $this->db->get('empresa_funcoes a')->result();
        foreach ($funcoes as $funcao) {
            $data['funcoes'][$funcao->id] = $funcao->nome;
        }


        $this->db->select('DISTINCT(municipio) AS municipio', false);
        $this->db->where('id_empresa', $empresa);
        $this->db->where('CHAR_LENGTH(municipio) > 0', null, false);
        $this->db->order_by('municipio', 'asc');
        $municipios = $this->db->get('requisicoes_pessoal')->result();
        $data['municipios'] = ['' => 'Todos'] + array_column($municipios, 'municipio', 'municipio');


        $this->db->select('a.id_usuario AS id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $this->session->userdata('empresa'));
        $this->db->order_by('b.nome', 'asc');
        $sqlAprovadores = $this->db->get('requisicoes_pessoal_aprovadores a')->result();
        $aprovadores = ['' => 'selecione...'] + array_column($sqlAprovadores, 'nome', 'id');
        $data['aprovado_por'] = form_dropdown('aprovado_por', $aprovadores, '');


        $data['idUsuario'] = $tipo == 'funcionario' ? $id : '';
        $data['aprovadores'] = true;


        $this->load->view('requisicaoPessoal', $data);
    }

    //==========================================================================
    public function atualizarEstrutura($retorno = false)
    {
        $id = $this->session->userdata('id');
        $empresa = $this->session->userdata('empresa');
        $tipo = $this->session->userdata('tipo');


        $idDepto = $this->input->post('id_depto');
        $idArea = $this->input->post('id_area');
        $idSetor = $this->input->post('id_setor');
        $tipoVaga = $this->input->post('tipo_vaga');
        $requisitanteInterno = $this->input->post('requisitante_interno');
        $requisitanteExterno = $this->input->post('requisitante_externo');


        $isPost = is_array($this->input->post());
        if (($tipo == 'funcionario' and !in_array($this->session->userdata('nivel'), [7, 8, 18])) and $isPost == false) {
            $this->db->select('b.id AS id_depto, c.id AS id_area, d.id AS id_setor, a.id AS id_requisitante');
            $this->db->join('empresa_departamentos b', 'b.nome = a.depto');
            $this->db->join('empresa_areas c', 'c.nome = a.area');
            $this->db->join('empresa_setores d', 'd.nome = a.setor');
            $this->db->where('a.id', $this->session->userdata('id'));
            $usuario = $this->db->get('usuarios a')->row();

            $idDepto = $usuario->id_depto;
            $idArea = $usuario->id_area;
            $idSetor = $usuario->id_setor;
            $requisitanteInterno = $usuario->id_requisitante;
            $requisitanteExterno = $usuario->id_requisitante;
        }


        $filtro = array(
            'deptos' => ['' => 'Todos'],
            'areas' => ['' => 'Todas'],
            'setores' => ['' => 'Todos'],
            'requisitantes' => ['' => 'selecione...']
        );

        $this->db->select('id, nome');
        $this->db->where('id_empresa', $empresa);
        $this->db->order_by('nome', 'asc');
        $deptos = $this->db->get('empresa_departamentos')->result();
        foreach ($deptos as $depto) {
            $filtro['deptos'][$depto->id] = $depto->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('b.id', $idDepto);
        $this->db->order_by('a.nome', 'asc');
        $areas = $this->db->get('empresa_areas a')->result();
        foreach ($areas as $area) {
            $filtro['areas'][$area->id] = $area->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $empresa);
        $this->db->where('b.id', $idArea);
        $this->db->where('c.id', $idDepto);
        $this->db->order_by('a.nome', 'asc');
        $setores = $this->db->get('empresa_setores a')->result();
        foreach ($setores as $setor) {
            $filtro['setores'][$setor->id] = $setor->nome;
        }

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.nome = a.depto', 'left');
        $this->db->join('empresa_areas c', 'c.nome = a.area', 'left');
        $this->db->join('empresa_setores d', 'd.nome = a.setor', 'left');
        $this->db->where('a.empresa', $empresa);
        $this->db->where_in('a.tipo', ['funcionario', 'selecionador']);
        $this->db->where_in('a.nivel_acesso', [7, 8, 9, 10, 18, 19]); #Presidente, Gerente, Coordenador, Diretor e supervisor
        if ($tipo == 'funcionari') {
            $this->db->where('a.id', $id);
        } else {
            if ($idDepto) {
                $this->db->where('b.id', $idDepto);
            }
            if ($idArea) {
//                $this->db->where('c.id', $idArea);
            }
            if ($idSetor) {
//                $this->db->where('d.id', $idSetor);
            }
            $filtro['requisitantes'] = ['' => 'selecione...'];
        }
        $this->db->order_by('a.nome', 'asc');
        $requisitantes = $this->db->get('usuarios a')->result();
        foreach ($requisitantes as $requisitante) {
            $filtro['requisitantes'][$requisitante->id] = $requisitante->nome;
        }

        $data['depto'] = form_dropdown('id_depto', $filtro['deptos'], $idDepto, 'id="depto" class="form-control estrutura"');
        $data['area'] = form_dropdown('id_area', $filtro['areas'], $idArea, 'id="area" class="form-control estrutura"');
        $data['setor'] = form_dropdown('id_setor', $filtro['setores'], $idSetor, 'id="setor" class="form-control estrutura"');
        $data['requisitante_interno'] = form_dropdown('requisitante_interno', $filtro['requisitantes'], $requisitanteInterno, 'class="form-control"');
        $data['requisitante_externo'] = form_dropdown('requisitante_externo', $filtro['requisitantes'], $requisitanteExterno, 'class="form-control"');

        if ($retorno) {
            return $data;
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function atualizarFuncao($retorno = false)
    {
        $empresa = $this->session->userdata('empresa');

        $idCargo = $this->input->post('id_cargo');
        $idFuncao = $this->input->post('id_funcao');

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_cargos b', 'b.id = a.id_cargo');
        $this->db->where('b.id_empresa', $empresa);
        $this->db->where('b.id', $idCargo);
        $this->db->order_by('a.nome', 'asc');
        $funcoes = $this->db->get('empresa_funcoes a')->result();
        $options = array('' => 'selecione...');
        foreach ($funcoes as $funcao) {
            $options[$funcao->id] = $funcao->nome;
        }

        $data['funcao'] = form_dropdown('id_funcao', $options, $idFuncao, 'class="form-control"');

        if ($retorno) {
            return $data;
        }

        echo json_encode($data);
    }

    //==========================================================================
    public function atualizarMunicipio()
    {
        $rows = $this->db
            ->select('DISTINCT(municipio) AS municipio', false)
            ->where('id_empresa', $this->session->userdata('empresa'))
            ->where('CHAR_LENGTH(municipio) > 0', null, false)
            ->order_by('municipio', 'asc')
            ->get('requisicoes_pessoal')
            ->result();

        $municipios = ['' => 'Todos'] + array_column($rows, 'municipio', 'municipio');

        $data['municipios'] = form_dropdown('', $municipios, $this->input->post('municipio'));

        echo json_encode($data);
    }

    //==========================================================================
    public function ajax_list()
    {
        $post = $this->input->post();
        $tipoUsuario = $this->session->userdata('tipo');
        $nivelUsuario = $this->session->userdata('nivel');
        $representante = $this->session->userdata('id');

        $usuario = $this->db->select('nome, depto')->where('id', $representante)->get('usuarios')->row_array();

        $this->db
            ->select('a.id, a.numero, a.data_abertura, a.status, a.estagio, a.selecionador')
            ->select(["IFNULL(b.nome, a.cargo_funcao_alternativo) AS cargo"], false)
            ->select(["CONCAT(b.nome, '/', c.nome) AS cargo_funcao"], false)
            ->select(["CONCAT(d.nome, '/', e.nome) AS depto_area"], false)
            ->select('a.numero_vagas, a.previsao_inicio, a.tipo_vaga')
            ->select('a.numero_vagas AS numero_vagas_fechadas, NULL AS numero_vagas_abertas', false)
            ->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura_de"], false)
            ->select(["DATE_FORMAT(a.previsao_inicio, '%d/%m/%Y') AS previsao_inicio_de"], false)
            ->select(["CONCAT(d.nome, '/', e.nome, ' - ', f.nome) AS estrutura"], false)
            ->select(["IF(a.tipo_vaga = 'I', g.nome, h.nome) AS requisitante"], false)
            ->select('a.requisitante_interno, a.justificativa_contratacao, a.aprovado_por, a.data_aprovacao')
            ->join('empresa_cargos b', 'b.id = a.id_cargo', 'left')
            ->join('empresa_funcoes c', 'c.id = a.id_funcao', 'left')
            ->join('empresa_departamentos d', 'd.id = a.id_depto', 'left')
            ->join('empresa_areas e', 'e.id = a.id_area', 'left')
            ->join('empresa_setores f', 'f.id = a.id_setor', 'left')
            ->join('usuarios g', 'g.id = a.requisitante_interno', 'left')
            ->join('usuarios h', 'h.id = a.requisitante_externo', 'left')
            ->where('a.id_empresa', $this->session->userdata('empresa'))
            ->where("(CASE WHEN '{$nivelUsuario}' = 9 
                           THEN (a.requisitante_interno = '{$representante}' OR a.requisitante_externo = '{$representante}') 
                           WHEN '{$nivelUsuario}' IN (0, 3, 6, 7, 8, 9, 10, 19) THEN  1 END)");
        if ($post['status']) {
            $this->db->where_in('a.status', explode(',', $post['status']));
        }
        if ($post['estagio']) {
            $this->db->where('a.estagio', $post['estagio']);
        }
        if ($post['municipio']) {
            $this->db->where('a.municipio', $post['municipio']);
        }
        if ($post['depto']) {
            $this->db->where('a.id_depto', $post['depto']);
        }
        if ($post['cargo']) {
            $this->db->where('a.id_cargo', $post['cargo']);
        }
        if ($this->session->userdata('tipo') == 'funcionario' and in_array($this->session->userdata('nivel'), [6, 9, 10, 19])) {
            $this->db->where('a.id_cargo', $post['cargo']);
        }
        if ($post['data_inicio']) {
            $post['data_inicio'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['data_inicio'])));
            $this->db->where("(a.data_abertura >= '{$post['data_inicio']}' OR 
                               a.data_fechamento >= '{$post['data_inicio']}' OR 
                               a.data_solicitacao_exame >= '{$post['data_inicio']}' OR 
                               a.data_aprovacao >= '{$post['data_inicio']}' OR 
                               a.previsao_inicio >= '{$post['data_inicio']}')");
        }
        if ($post['data_termino']) {
            $post['data_termino'] = date('Y-m-d', strtotime(str_replace('/', '-', $post['data_termino'])));
            $this->db->where("(a.data_abertura <= '{$post['data_termino']}' OR 
                               a.data_fechamento <= '{$post['data_termino']}' OR 
                               a.data_solicitacao_exame <= '{$post['data_termino']}' OR 
                               a.data_aprovacao <= '{$post['data_termino']}' OR 
                               a.previsao_inicio <= '{$post['data_termino']}')");
        }
        $query = $this->db->get('requisicoes_pessoal a');

        $config = ['search' => ['id', 'selecionador', 'cargo', 'estrutura']];

        $this->load->library('dataTables', $config);

        $output = $this->datatables->generate($query);

        $status = [
            'A' => 'Ativa',
            'S' => 'Suspensa',
            'C' => 'Cancelada',
            'G' => 'Aguardando aprovação',
            'F' => 'Fechada',
            'P' => 'Fechada parcialmente'
        ];

        $estagio = [
            '1' => '01/10 - Alinhando perfil',
            '2' => '02/10 - Divulgando vagas',
            '3' => '03/10 - Triando currículos',
            '4' => '04/10 - Convocando candidatos',
            '5' => '05/10 - Entrevistando candidatos',
            '6' => '06/10 - Elaborando pareceres',
            '7' => '07/10 - Aguardando gestor',
            '8' => '08/10 - Entrevista solicitante',
            '9' => '09/10 - Exame adissional',
            '10' => '10/10 - Entrega documentos',
            '11' => 'Faturamento',
            '12' => 'Processo finalizado'
        ];

        $data = [];

        foreach ($output->data as $row) {
            $btn = '';
            if ($row->justificativa_contratacao == 'A' and $row->status != 'A' and $tipoUsuario != 'selecionador') {
                if ($row->aprovado_por == $representante or $row->requisitante_interno == $representante or $tipoUsuario == 'empresa') {
                    $btn = '<button type="button" class="btn btn-sm btn-info" onclick="edit_requisicao(' . $row->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_requisicao(' . $row->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                            <button type="button" class="btn btn-sm btn-success" onclick="publicar_vaga(' . $row->id . ')" title="Publicar vaga">Publicar vaga</button>
                            <a class="btn btn-sm btn-primary" href="' . site_url('/recrutamentoPresencial_cargos/gerenciar/' . $row->id) . '" title="Processo Seletivo">Processo</a>
                            <button type="button" class="btn btn-sm btn-info" onclick="mostrar_aprovados(' . $row->id . ')" title="Mostrar aprovados">Aprovados</button>
                            <a class="btn btn-sm btn-primary" href="' . site_url('requisicaoPessoal/relatorio/' . $row->id) . '" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></a>
                            <button type="button" class="btn btn-sm btn-info" onclick="edit_email(' . $row->id . ')" title="Ativar contratação">Ativar contratação</button>';
                } else {
                    $btn = '<button type="button" class="btn btn-sm btn-info disabled" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                            <button type="button" class="btn btn-sm btn-danger disabled" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                            <button type="button" class="btn btn-sm btn-success disabled" title="Publicar vaga">Publicar vaga</button>
                            <button type="button" class="btn btn-sm btn-primary disabled" title="Processo Seletivo">Processo</button>
                            <button type="button" class="btn btn-sm btn-info disabled" title="Mostrar aprovados">Aprovados</button>
                            <button type="button" class="btn btn-sm btn-primary disabled" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></button>
                            <button type="button" class="btn btn-sm btn-info disabled" title="Ativar contratação">Ativar contratação</button>';
                }
            } else {
                if (($usuario['depto'] == 'Gestão de Pessoas' or $tipoUsuario == 'empresa' or ($tipoUsuario == 'selecionador' or $nivelUsuario = 'selecionador requisitante')) and $row->status == 'G') {
                    $btn = '<button type="button" class="btn btn-sm btn-info disabled" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                            <button type="button" class="btn btn-sm btn-danger disabled" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                            <button type="button" class="btn btn-sm btn-success disabled" title="Publicar vaga">Publicar vaga</button>
                            <button type="button" class="btn btn-sm btn-primary disabled" title="Processo Seletivo">Processo</button>
                            <button type="button" class="btn btn-sm btn-info disabled" title="Mostrar aprovados">Aprovados</button>
                            <button type="button" class="btn btn-sm btn-primary disabled" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></button>
                            <button type="button" class="btn btn-sm btn-info disabled" title="Ativar contratação">Ativar contratação</button>';
                } elseif (($usuario['depto'] == 'Gestão de Pessoas' or $tipoUsuario == 'empresa' or ($tipoUsuario == 'selecionador' or $nivelUsuario = 'selecionador requisitante')) and $row->status != 'G') {
                    $btn = '<button type="button" class="btn btn-sm btn-info" onclick="edit_requisicao(' . $row->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_requisicao(' . $row->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                            <button type="button" class="btn btn-sm btn-success" onclick="publicar_vaga(' . $row->id . ')" title="Publicar vaga">Publicar vaga</button>
                            <a class="btn btn-sm btn-primary" href="' . site_url('/recrutamentoPresencial_cargos/gerenciar/' . $row->id) . '" title="Processo Seletivo">Processo</a>
                            <button type="button" class="btn btn-sm btn-info" onclick="mostrar_aprovados(' . $row->id . ')" title="Mostrar aprovados">Aprovados</button>
                            <a class="btn btn-sm btn-primary" href="' . site_url('requisicaoPessoal/relatorio/' . $row->id) . '" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></a>
                            <button type="button" class="btn btn-sm btn-info" onclick="edit_email(' . $row->id . ')" title="Ativar contratação">Ativar contratação</button>';
                } else {
                    $btn = '<button type="button" class="btn btn-sm btn-info" onclick="edit_requisicao(' . $row->id . ')" title="Editar"><i class="glyphicon glyphicon-pencil"></i> </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="delete_requisicao(' . $row->id . ')" title="Excluir"><i class="glyphicon glyphicon-trash"></i> </button>
                            <button type="button" class="btn btn-sm btn-success" onclick="publicar_vaga(' . $row->id . ')" title="Publicar vaga">Publicar vaga</button>
                            <button type="button" class="btn btn-sm btn-primary disabled" title="Processo Seletivo">Processo</button>
                            <button type="button" class="btn btn-sm btn-info" onclick="mostrar_aprovados(' . $row->id . ')" title="Mostrar aprovados">Aprovados</button>
                            <a class="btn btn-sm btn-primary" href="' . site_url('requisicaoPessoal/relatorio/' . $row->id) . '" title="Imprimir requisição de pessoal"><i class="glyphicon glyphicon-print"></i></a>
                            <button type="button" class="btn btn-sm btn-info" onclick="edit_email(' . $row->id . ')" title="Ativar contratação">Ativar contratação</button>';
                }
            }

            $data[] = [
                $row->id,
                $row->data_abertura_de,
                $status[$row->status] ?? '',
                $estagio[$row->estagio] ?? '',
                $row->selecionador,
                $row->cargo,
                $row->numero_vagas,
                $row->cargo_funcao,
                $row->depto_area . ' - ' . $row->requisitante,
                $row->numero_vagas,
                $row->previsao_inicio_de,
                $row->cargo_funcao,
                $row->numero_vagas_fechadas,
                $row->numero_vagas_abertas,
                $btn,
                $row->justificativa_contratacao,
                $row->data_aprovacao
            ];
        }

        $output->data = $data;

        echo json_encode($output);
    }

    //==========================================================================
    public function ajax_listAprovados()
    {
        $this->db->start_cache();

        $this->db
            ->from('requisicoes_pessoal a')
            ->join('requisicoes_pessoal_candidatos b', 'b.id_requisicao = a.id', 'left')
            ->join('recrutamento_usuarios c', 'c.id = b.id_usuario', 'left')
            ->join('deficiencias d', 'd.id = c.deficiencia', 'left')
            ->where('a.id', $this->input->post('id'))
            ->where('b.aprovado', 1);

        $this->db->stop_cache();

        $query = $this->db
            ->select('c.nome')
            ->select(["DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura"], false)
            ->select(["DATE_FORMAT(a.data_aprovacao, '%d/%m/%Y') AS data_aprovacao"], false)
            ->select(["DATE_FORMAT(b.data_selecao, '%d/%m/%Y') AS data_selecao"], false)
            ->select(["DATE_FORMAT(b.data_requisitante, '%d/%m/%Y') AS data_requisitante"], false)
            ->select(["DATE_FORMAT(a.data_fechamento, '%d/%m/%Y') AS data_fechamento"], false)
            ->select(["DATE_FORMAT(b.data_admissao, '%d/%m/%Y') AS data_admissao"], false)
            ->get()
            ->result_array();

        $totalDias = $this->db
            ->select(["DATEDIFF(a.data_aprovacao, a.data_abertura) AS total_data_aprovacao"], false)
            ->select(["DATEDIFF(b.data_selecao, a.data_abertura) AS total_data_selecao"], false)
            ->select(["DATEDIFF(b.data_requisitante, a.data_abertura) AS total_data_requisitante"], false)
            ->select(["DATEDIFF(a.data_fechamento, a.data_abertura) AS total_data_fechamento"], false)
            ->select(["DATEDIFF(b.data_admissao, a.data_abertura) AS total_data_admissao"], false)
            ->get()
            ->row_array();

        $this->db->flush_cache();

        $data = array_map(function ($row) {
            return array_values($row);
        }, $query);

        echo json_encode([
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'total_dias' => array_values($totalDias),
            'data' => $data
        ]);
    }

    //==========================================================================
    public function ajax_nextId()
    {
        $empresa = $this->session->userdata('empresa');

        $this->db->select('IFNULL(MAX(id) + 1, 1) AS id', false);
        $this->db->select("DATE_FORMAT(NOW(), '%d/%m/%Y') AS data_abertura", false);
        $data = $this->db->get_where('requisicoes_pessoal')->row();


        $this->db->select('a.id_usuario AS id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $empresa);
        $this->db->order_by('b.nome', 'asc');
        $sqlAprovadores = $this->db->get('requisicoes_pessoal_aprovadores a')->result();
        $aprovadores = ['' => 'selecione...'] + array_column($sqlAprovadores, 'nome', 'id');
        $data->aprovado_por = form_dropdown('aprovado_por', $aprovadores, '');

        $data->departamento_informacoes = 'Nome do colaborador (1)
Nome do pai (1)
Nome da mãe (1)
Data de nascimento (1)
RG (1)
Data de emissão RG (1)
Órgão expedidor RG (1)
CPF (1)
PIS (1)
--------------------------
Nome do colaborador (2)
Nome do pai (2)
Nome da mãe (2)
Data de nascimento (2)
RG (2)
Data de emissão RG (2)
Órgão expedidor RG (2)
CPF (2)
PIS (2)';

        echo json_encode($data);
    }

    public function ajax_edit()
    {
        $id = $this->input->post('id');
        $data = $this->db->get_where('requisicoes_pessoal', array('id' => $id))->row();
        $data->data_abertura = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_abertura)));
        if ($data->data_fechamento) {
            $data->data_fechamento = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_fechamento)));
        }
        if ($data->data_solicitacao_exame) {
            $data->data_solicitacao_exame = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_solicitacao_exame)));
        }
        if ($data->data_suspensao) {
            $data->data_suspensao = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_suspensao)));
        }
        if ($data->data_cancelamento) {
            $data->data_cancelamento = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_cancelamento)));
        }
        if ($data->previsao_inicio) {
            $data->previsao_inicio = date("d/m/Y", strtotime(str_replace('-', '/', $data->previsao_inicio)));
        }
        if ($data->data_aprovacao) {
            $data->data_aprovacao = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_aprovacao)));
        }
        if ($data->data_nascimento) {
            $data->data_nascimento = date("d/m/Y", strtotime(str_replace('-', '/', $data->data_nascimento)));
        }
        if ($data->rg_data_emissao) {
            $data->rg_data_emissao = date("d/m/Y", strtotime(str_replace('-', '/', $data->rg_data_emissao)));
        }
        if (strlen($data->remuneracao_mensal) > 0) {
            $data->remuneracao_mensal = number_format($data->remuneracao_mensal, 2, ',', '.');
        }
        if (strlen($data->valor_vale_transporte) > 0) {
            $data->valor_vale_transporte = number_format($data->valor_vale_transporte, 2, ',', '.');
        }
        if (strlen($data->valor_vale_alimentacao) > 0) {
            $data->valor_vale_alimentacao = number_format($data->valor_vale_alimentacao, 2, ',', '.');
        }
        if (strlen($data->valor_vale_refeicao) > 0) {
            $data->valor_vale_refeicao = number_format($data->valor_vale_refeicao, 2, ',', '.');
        }
        if (strlen($data->valor_assistencia_medica) > 0) {
            $data->valor_assistencia_medica = number_format($data->valor_assistencia_medica, 2, ',', '.');
        }
        if (strlen($data->valor_plano_odontologico) > 0) {
            $data->valor_plano_odontologico = number_format($data->valor_plano_odontologico, 2, ',', '.');
        }
        if (strlen($data->valor_cesta_basica) > 0) {
            $data->valor_cesta_basica = number_format($data->valor_cesta_basica, 2, ',', '.');
        }
        if (strlen($data->valor_participacao_resultados) > 0) {
            $data->valor_participacao_resultados = number_format($data->valor_participacao_resultados, 2, ',', '.');
        }

        $_POST['id_depto'] = $data->id_depto;
        $_POST['id_area'] = $data->id_area;
        $_POST['id_setor'] = $data->id_setor;
        $_POST['id_cargo'] = $data->id_cargo;
        $_POST['id_funcao'] = $data->id_funcao;
        $_POST['tipo_vaga'] = $data->tipo_vaga;
        $_POST['requisitante_interno'] = $data->requisitante_interno;
        $_POST['requisitante_externo'] = $data->requisitante_externo;

        $estrutura = $this->atualizarEstrutura(true);
        $funcao = $this->atualizarFuncao(true);

        $this->db->select('a.id_usuario AS id, b.nome');
        $this->db->join('usuarios b', 'b.id = a.id_usuario');
        $this->db->where('b.empresa', $this->session->userdata('empresa'));
        $this->db->order_by('b.nome', 'asc');
        $sqlAprovadores = $this->db->get('requisicoes_pessoal_aprovadores a')->result();
        $aprovadores = ['' => 'selecione...'] + array_column($sqlAprovadores, 'nome', 'id');
        $aprovadoPor = ['aprovado_por' => form_dropdown('aprovado_por', $aprovadores, $data->aprovado_por)];


        $input = array_merge($estrutura, $funcao, $aprovadoPor);

        echo json_encode(array('input' => $input, 'data' => $data));
    }

    //==========================================================================
    public function ajax_add()
    {
        $data = $this->input->post();
        $data1 = array_filter($data);
        unset($data1['numero'], $data1['regime_contratacao'], $data1['departamento_informacoes'], $data1['id'], $data1['tipo_vaga'], $data1['data_abertura'], $data1['status'], $data1['estagio']);
        if (empty($data1)) {
            exit(json_encode(['erro' => 'Alguns campos não estão preenchidos. Favor revisar o preenchimento.']));
        }
        unset($data1);

        $data['id_empresa'] = $this->session->userdata('empresa');
        if (strlen($data['data_abertura']) == 0) {
            exit(json_encode(array('erro' => 'A data de abertura é obrigatória.')));
        }
        if (!empty($data['possui_indicacao'])) {
            if (strlen($data['colaboradores_indicados']) == 0) {
                exit(json_encode(array('erro' => 'O campo Colaboradores Indicados é obrigatório.')));
            }
            if (strlen($data['indicador_responsavel']) == 0) {
                exit(json_encode(array('erro' => 'O campo Responsável pela indicação é obrigatório.')));
            }
        } else {
            $data['possui_indicacao'] = null;
            $data['colaboradores_indicados'] = null;
            $data['indicador_responsavel'] = null;
        }

        if (empty($data['requisitante_interno'])) {
            if ($data['tipo_vaga'] == 'I') {
                exit(json_encode(array('erro' => 'O requisitante interno é obrigatório.')));
            }
            $data['requisitante_interno'] = null;
        }
        if (strlen($data['requisitante_externo']) == 0) {
            if ($data['tipo_vaga'] == 'E') {
                exit(json_encode(array('erro' => 'O requisitante externo é obrigatório.')));
            }
            $data['requisitante_externo'] = null;
        }

        if (empty($data['municipio'])) {
            $data['municipio'] = null;
        }

        if (empty($data['id_cargo'])) {
            $data['id_cargo'] = null;
        }
        if (strlen($data['cargo_externo']) == 0) {
            $data['cargo_externo'] = null;
        }
        if (empty($data['id_funcao'])) {
            $data['id_funcao'] = null;
        }
        if (strlen($data['funcao_externa']) == 0) {
            $data['funcao_externa'] = null;
        }
        if (isset($data['vagas_deficiente'])) {
            $data['vagas_deficiente'] = 1;
        } else {
            $data['vagas_deficiente'] = null;
        }
        if (isset($data['colaborador_substituto'])) {
            if (strlen($data['colaborador_substituto']) == 0) {
                $data['colaborador_substituto'] = null;
            }
        } else {
            $data['colaborador_substituto'] = null;
        }
        if (!empty($data['aprovado_por']) == false) {
            $data['aprovado_por'] = null;
        }
        if (isset($data['selecionador']) and strlen($data['selecionador']) == 0) {
            $data['selecionador'] = null;
        }

        $data['data_abertura'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_abertura'])));
        if ($data['previsao_inicio']) {
            $data['previsao_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['previsao_inicio'])));
        } else {
            $data['previsao_inicio'] = null;
        }
        if (!empty($data['data_solicitacao_exame'])) {
            $data['data_solicitacao_exame'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_solicitacao_exame'])));
        } else {
            $data['data_solicitacao_exame'] = null;
        }
        if (!empty($data['data_fechamento'])) {
            $data['data_fechamento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_fechamento'])));
        } else {
            $data['data_fechamento'] = null;
        }
        if (!empty($data['data_suspensao'])) {
            $data['data_suspensao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_suspensao'])));
        } else {
            $data['data_suspensao'] = null;
        }
        if (!empty($data['data_cancelamento'])) {
            $data['data_cancelamento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_cancelamento'])));
        } else {
            $data['data_cancelamento'] = null;
        }
        if (!empty($data['data_aprovacao'])) {
            $data['data_aprovacao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_aprovacao'])));
        } else {
            $data['data_aprovacao'] = null;
        }
        if ($data['remuneracao_mensal']) {
            $data['remuneracao_mensal'] = str_replace(array('.', ','), array('', '.'), $data['remuneracao_mensal']);
        } else {
            $data['remuneracao_mensal'] = null;
        }
        if (strlen($data['valor_vale_transporte']) > 0) {
            $data['valor_vale_transporte'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_transporte']);
        } else {
            $data['valor_vale_transporte'] = null;
        }
        if (strlen($data['valor_vale_alimentacao']) > 0) {
            $data['valor_vale_alimentacao'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_alimentacao']);
        } else {
            $data['valor_vale_alimentacao'] = null;
        }
        if (strlen($data['valor_vale_refeicao']) > 0) {
            $data['valor_vale_refeicao'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_refeicao']);
        } else {
            $data['valor_vale_refeicao'] = null;
        }
        if (strlen($data['valor_cesta_basica']) > 0) {
            $data['valor_cesta_basica'] = str_replace(array('.', ','), array('', '.'), $data['valor_cesta_basica']);
        } else {
            $data['valor_cesta_basica'] = null;
        }
        if (strlen($data['valor_assistencia_medica']) > 0) {
            $data['valor_assistencia_medica'] = str_replace(array('.', ','), array('', '.'), $data['valor_assistencia_medica']);
        } else {
            $data['valor_assistencia_medica'] = null;
        }
        if (strlen($data['valor_plano_odontologico']) > 0) {
            $data['valor_plano_odontologico'] = str_replace(array('.', ','), array('', '.'), $data['valor_plano_odontologico']);
        } else {
            $data['valor_plano_odontologico'] = null;
        }
        if (strlen($data['valor_participacao_resultados']) > 0) {
            $data['valor_participacao_resultados'] = str_replace(array('.', ','), array('', '.'), $data['valor_participacao_resultados']);
        } else {
            $data['valor_participacao_resultados'] = null;
        }


        if ($data['id_depto'] == '5' and $data['possui_indicacao']) { // Cuidadores
            if (!empty($data['data_nascimento'])) {
                $data['data_nascimento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_nascimento'])));
            } else {
                exit(json_encode(['erro' => 'A data de nascimento é obrigatória.']));
            }
            if (!empty($data['rg_data_emissao'])) {
                $data['rg_data_emissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['rg_data_emissao'])));
            } else {
                exit(json_encode(['erro' => 'A data de emissão do RG é obrigatória.']));
            }
            if (!empty($data['nome_mae']) == false) {
                exit(json_encode(['erro' => 'O nome da mãe é obrigatório.']));
            }
            if (!empty($data['nome_pai']) == false) {
                exit(json_encode(['erro' => 'O nome do pai é obrigatório.']));
            }
            if (!empty($data['rg']) == false) {
                exit(json_encode(['erro' => 'O RG é obrigatório.']));
            }
            if (!empty($data['rg_orgao_emissor']) == false) {
                exit(json_encode(['erro' => 'O órgão emissor do RG é obrigatório.']));
            }
            if (!empty($data['cpf']) == false) {
                exit(json_encode(['erro' => 'O CPF é obrigatório.']));
            }
            if (!empty($data['pis']) == false) {
                exit(json_encode(['erro' => 'O PIS é obrigatório.']));
            }
            if (strlen($data['departamento_informacoes']) == 0) {
                $data['departamento_informacoes'] = null;
            }
        } else {
            $data['data_nascimento'] = null;
            $data['nome_mae'] = null;
            $data['nome_pai'] = null;
            $data['rg'] = null;
            $data['rg_data_emissao'] = null;
            $data['rg_orgao_emissor'] = null;
            $data['cpf'] = null;
            $data['pis'] = null;
            $data['departamento_informacoes'] = null;
        }

        if ($this->db->get_where('requisicoes_pessoal', ['id' => $data['id']])->num_rows()) {
            unset($data['id']);
        }

        $status = $this->db->insert('requisicoes_pessoal', $data);
        $id = $this->db->insert_id();

        if ($status and $data['aprovado_por']) {
            $row = array(
                'id_requisicao' => $data['id'] ?? $id,
                'id_usuario' => $data['aprovado_por'],
                'data_aprovacao' => $data['data_aprovacao']
            );
            $this->notificarAprovador($row);
        }

        if ($id) {
            $this->notificarApoio($id);
        }

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_update()
    {
        $data = $this->input->post();
        $data1 = array_filter($data);
        unset($data1['numero'], $data1['regime_contratacao'], $data1['departamento_informacoes'], $data1['id'], $data1['tipo_vaga'], $data1['data_abertura'], $data1['status'], $data1['estagio']);
        if (empty($data1)) {
            exit(json_encode(['erro' => 'Alguns campos não estão preenchidos. Favor revisar o preenchimento.']));
        }
        unset($data1);

        $id = $data['id'];
        unset($data['id']);
        if (strlen($data['data_abertura']) == 0) {
            exit(json_encode(array('erro' => 'A data de abertura é obrigatória.')));
        }
        if (!empty($data['possui_indicacao'])) {
            if (strlen($data['colaboradores_indicados']) == 0) {
                exit(json_encode(array('erro' => 'O campo Colaboradores Indicados é obrigatório.')));
            }
            if (strlen($data['indicador_responsavel']) == 0) {
                exit(json_encode(array('erro' => 'O campo Responsável pela indicação é obrigatório.')));
            }
        } else {
            $data['possui_indicacao'] = null;
            $data['colaboradores_indicados'] = null;
            $data['indicador_responsavel'] = null;
        }

        if (empty($data['requisitante_interno'])) {
            if ($data['tipo_vaga'] == 'I') {
                exit(json_encode(array('erro' => 'O requisitante interno é obrigatório.')));
            }
            $data['requisitante_interno'] = null;
        }
        if (strlen($data['requisitante_externo']) == 0) {
            if ($data['tipo_vaga'] == 'E') {
                exit(json_encode(array('erro' => 'O requisitante externo é obrigatório.')));
            }
            $data['requisitante_externo'] = null;
        }

        if (empty($data['municipio'])) {
            $data['municipio'] = null;
        }

        if (empty($data['id_cargo'])) {
            $data['id_cargo'] = null;
        }
        if (strlen($data['cargo_externo']) == 0) {
            $data['cargo_externo'] = null;
        }
        if (empty($data['id_funcao'])) {
            $data['id_funcao'] = null;
        }
        if (strlen($data['funcao_externa']) == 0) {
            $data['funcao_externa'] = null;
        }
        if (strlen($data['cargo_funcao_alternativo']) == 0) {
            $data['cargo_funcao_alternativo'] = null;
        }
        if (isset($data['vagas_deficiente'])) {
            $data['vagas_deficiente'] = 1;
        } else {
            $data['vagas_deficiente'] = null;
        }
        if (isset($data['colaborador_substituto'])) {
            if (strlen($data['colaborador_substituto']) == 0) {
                $data['colaborador_substituto'] = null;
            }
        } else {
            $data['colaborador_substituto'] = null;
        }
        if (!empty($data['aprovado_por']) == false) {
            $data['aprovado_por'] = null;
        }
        if (isset($data['selecionador']) and strlen($data['selecionador']) == 0) {
            $data['selecionador'] = null;
        }

        $data['data_abertura'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_abertura'])));
        if ($data['previsao_inicio']) {
            $data['previsao_inicio'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['previsao_inicio'])));
        } else {
            $data['previsao_inicio'] = null;
        }
        if (!empty($data['data_solicitacao_exame'])) {
            $data['data_solicitacao_exame'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_solicitacao_exame'])));
        } else {
            $data['data_solicitacao_exame'] = null;
        }
        if (!empty($data['data_fechamento'])) {
            $data['data_fechamento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_fechamento'])));
        } else {
            $data['data_fechamento'] = null;
        }
        if (!empty($data['data_suspensao'])) {
            $data['data_suspensao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_suspensao'])));
        } else {
            $data['data_suspensao'] = null;
        }
        if (!empty($data['data_cancelamento'])) {
            $data['data_cancelamento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_cancelamento'])));
        } else {
            $data['data_cancelamento'] = null;
        }
        if (!empty($data['data_aprovacao'])) {
            $data['data_aprovacao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_aprovacao'])));
        } else {
            $data['data_aprovacao'] = null;
        }
        if ($data['remuneracao_mensal']) {
            $data['remuneracao_mensal'] = str_replace(array('.', ','), array('', '.'), $data['remuneracao_mensal']);
        } else {
            $data['remuneracao_mensal'] = null;
        }
        if (strlen($data['valor_vale_transporte']) > 0) {
            $data['valor_vale_transporte'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_transporte']);
        } else {
            $data['valor_vale_transporte'] = null;
        }
        if (strlen($data['valor_vale_alimentacao']) > 0) {
            $data['valor_vale_alimentacao'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_alimentacao']);
        } else {
            $data['valor_vale_alimentacao'] = null;
        }
        if (strlen($data['valor_vale_refeicao']) > 0) {
            $data['valor_vale_refeicao'] = str_replace(array('.', ','), array('', '.'), $data['valor_vale_refeicao']);
        } else {
            $data['valor_vale_refeicao'] = null;
        }
        if (strlen($data['valor_cesta_basica']) > 0) {
            $data['valor_cesta_basica'] = str_replace(array('.', ','), array('', '.'), $data['valor_cesta_basica']);
        } else {
            $data['valor_cesta_basica'] = null;
        }
        if (strlen($data['valor_assistencia_medica']) > 0) {
            $data['valor_assistencia_medica'] = str_replace(array('.', ','), array('', '.'), $data['valor_assistencia_medica']);
        } else {
            $data['valor_assistencia_medica'] = null;
        }
        if (strlen($data['valor_plano_odontologico']) > 0) {
            $data['valor_plano_odontologico'] = str_replace(array('.', ','), array('', '.'), $data['valor_plano_odontologico']);
        } else {
            $data['valor_plano_odontologico'] = null;
        }
        if (strlen($data['valor_participacao_resultados']) > 0) {
            $data['valor_participacao_resultados'] = str_replace(array('.', ','), array('', '.'), $data['valor_participacao_resultados']);
        } else {
            $data['valor_participacao_resultados'] = null;
        }


        if ($data['id_depto'] == '5' and $data['possui_indicacao']) { // Cuidadores
            if (!empty($data['data_nascimento'])) {
                $data['data_nascimento'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['data_nascimento'])));
            } else {
                exit(json_encode(['erro' => 'A data de nascimento é obrigatória.']));
            }
            if (!empty($data['rg_data_emissao'])) {
                $data['rg_data_emissao'] = date("Y-m-d", strtotime(str_replace('/', '-', $data['rg_data_emissao'])));
            } else {
                exit(json_encode(['erro' => 'A data de emissão do RG é obrigatória.']));
            }
            if (!empty($data['nome_mae']) == false) {
                exit(json_encode(['erro' => 'O nome da mãe é obrigatório.']));
            }
            if (!empty($data['nome_pai']) == false) {
                exit(json_encode(['erro' => 'O nome do pai é obrigatório.']));
            }
            if (!empty($data['rg']) == false) {
                exit(json_encode(['erro' => 'O RG é obrigatório.']));
            }
            if (!empty($data['rg_orgao_emissor']) == false) {
                exit(json_encode(['erro' => 'O órgão emissor do RG é obrigatório.']));
            }
            if (!empty($data['cpf']) == false) {
                exit(json_encode(['erro' => 'O CPF é obrigatório.']));
            }
            if (!empty($data['pis']) == false) {
                exit(json_encode(['erro' => 'O PIS é obrigatório.']));
            }
            if (strlen($data['departamento_informacoes']) == 0) {
                $data['departamento_informacoes'] = null;
            }
        } else {
            $data['data_nascimento'] = null;
            $data['nome_mae'] = null;
            $data['nome_pai'] = null;
            $data['rg'] = null;
            $data['rg_data_emissao'] = null;
            $data['rg_orgao_emissor'] = null;
            $data['cpf'] = null;
            $data['pis'] = null;
            $data['departamento_informacoes'] = null;
        }


        $checkboxes = array(
            'vale_alimentacao',
            'vale_transporte',
            'vale_refeicao',
            'assistencia_medica',
            'plano_odontologico',
            'cesta_basica',
            'participacao_resultados',
            'exame_clinico',
            'audiometria',
            'laudo_cotas'
        );
        foreach ($checkboxes as $checkbox) {
            if (!isset($data[$checkbox])) {
                $data[$checkbox] = null;
            }
        }

        $requisicao = $this->db->select('aprovado_por')->get_where('requisicoes_pessoal', ['id' => $id])->row();

        $status = $this->db->update('requisicoes_pessoal', $data, array('id' => $id));

        if ($status and $requisicao->aprovado_por !== $data['aprovado_por']) {
            $row = array(
                'id_requisicao' => $id,
                'id_usuario' => $data['aprovado_por'],
                'data_aprovacao' => $data['data_aprovacao']
            );
            $this->notificarAprovador($row);
        }

        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    public function ajax_delete()
    {
        $id = $this->input->post('id');
        $status = $this->db->delete('requisicoes_pessoal', array('id' => $id));
        echo json_encode(array('status' => $status !== false));
    }

    //==========================================================================
    private function notificarAprovador($data)
    {
        $this->db->select('id, nome, email');
        $this->db->where('id', $this->session->userdata('id'));
        $remetente = $this->db->get('usuarios')->row();

        $this->db->select('id, nome, email');
        $this->db->where('id', $data['id_usuario']);
        $this->db->where('status', 1);
        $destinatario = $this->db->get('usuarios')->row();


        $this->load->library('email');

        $email['remetente'] = $remetente->id;
        $email['datacadastro'] = date('Y-m-d H:i:s');
        $email['titulo'] = 'Requisição de Pessoal - Aumento de Quadro';
        $email['mensagem'] = '<p>Caro colaborador, você tem uma requisição de pessoal solicitando "Aumento de quadro", aguardando sua aprovação.</p><p>Para maiores detalhes e aprovação da mesma, faça por gentileza o login na Plataforma de Gestão Operacional AME, selecionando a opção Gestão de Processos Seletivos / Gerenciar Requisições de Pessoal. Nesta opção você terá acesso à requisição de dados.</p><p>Requisição n&ordm; ' . $data['id_requisicao'] . '</p><p>Grato pela atenção.</p>';


        $this->email->from($remetente->email, $remetente->nome);
        $this->email->to($destinatario->email);
        $this->email->subject($email['titulo']);
        $this->email->message($email['mensagem']);

        if ($this->email->send()) {
            $email['destinatario'] = $destinatario->id;
            $this->db->query($this->db->insert_string('mensagensrecebidas', $email));
            $this->db->query($this->db->insert_string('mensagensenviadas', $email));
        }

        $this->email->clear();
    }

    //==========================================================================
    private function notificarApoio($id)
    {
        $this->db->select('a.id, a.nome, a.email, b.id AS id_depto');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_depto OR b.nome = a.depto', 'left');
        $this->db->where('a.id', $this->session->userdata('id'));
        $remetente = $this->db->get('usuarios a')->row();


        $this->db->select('b.id, a.email');
        $this->db->join('usuarios b', 'b.email = a.email', 'left');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_depto OR c.nome = b.depto', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('b.status', 1);
//        $this->db->where("(a.tipo_email IN (1, 3, 4) OR (a.tipo_email = 1 AND a.tipo_usuario = 5 AND c.id = '{$remetente->id_depto}'))");
        $this->db->where("(a.tipo_email = 1 AND ((a.tipo_usuario = 5 AND c.id = '{$remetente->id_depto}') OR a.tipo_usuario != 5) OR a.tipo_email IN (3, 4))");
        $destinatarios = $this->db->get('requisicoes_pessoal_emails a')->result();


        $email['titulo'] = 'Requisição de Pessoal - Ativação de contratação';
        $email['mensagem'] = 'Uma nova solicitação foi criada, veja anexo.';
        $filename = 'arquivos/temp/Requisição - ' . $id . '.pdf';
        $this->pdf();


        $this->db->select('b.nome AS depto');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_depto', 'left');
        $this->db->where('a.id', $id);
        $requisicaoPessoal = $this->db->get('requisicoes_pessoal a')->row();


        $this->load->library('email');

        foreach ($destinatarios as $destinatario_apoio) {
            $this->email->from($remetente->email, $remetente->nome);
            $this->email->to($destinatario_apoio->email);
            if ($requisicaoPessoal->depto === 'Cuidadores') {
                $this->email->cc('wendell@ame-sp.org.br');
            }
            $this->email->subject($email['titulo']);
            $this->email->message($email['mensagem']);
            $this->email->attach($filename);

            if ($this->email->send() and $destinatario_apoio->id) {
                $email['destinatario'] = $destinatario_apoio->id;
                $this->db->query($this->db->insert_string('mensagensrecebidas', $email));
                $this->db->query($this->db->insert_string('mensagensenviadas', $email));
            }

            $this->email->clear();
        }

        unlink($filename);
    }

    //==========================================================================
    public function ativarContratacao()
    {
        $this->db->select('id, nome');
        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->order_by('nome', 'asc');
        $rowDeptos = $this->db->get('empresa_departamentos')->result();
        $deptos = array_column($rowDeptos, 'nome', 'id');
        $data['depto'] = form_dropdown('', ['' => 'Todos'] + $deptos, '');

        $data['area'] = form_dropdown('', ['' => 'Todas'], '');
        $data['setor'] = form_dropdown('', ['' => 'Todos'], '');

        $this->db->where('id_empresa', $this->session->userdata('empresa'));
        $this->db->where_in('tipo_email', [2, 3]);
        $this->db->order_by('email', 'asc');
        $rowEmails = $this->db->get('requisicoes_pessoal_emails')->result();
        $emails = array_column($rowEmails, 'email', 'id');


        $data['emails'] = form_dropdown('', ['' => 'selecione...'] + $emails, '');
        $data['mensagem'] = 'Favor promover o agendamento de exame médico admissional para os colaboradores abaixo!';

        $this->db->select('c.nome, c.rg, c.pis, c.cpf, e.nome AS cargo, f.nome AS funcao', false);
        $this->db->select("DATE_FORMAT(c.data_nascimento, '%d/%m/%Y') AS data_nascimento", false);
        $this->db->select('a.centro_custo, a.local_trabalho', false);
        $this->db->join('requisicoes_pessoal_candidatos b', 'b.id_requisicao = a.id', 'left');
        $this->db->join('recrutamento_usuarios c', 'c.id = b.id_usuario', 'left');
        $this->db->join('deficiencias d', 'd.id = c.deficiencia', 'left');
        $this->db->join('empresa_cargos e', 'e.id = a.id_cargo', 'left');
        $this->db->join('empresa_funcoes f', 'f.id = a.id_funcao', 'left');
        $this->db->where('a.id', $this->input->post('id'));
        $this->db->where('b.aprovado', 1);
        $dadosCandidatos = $this->db->get('requisicoes_pessoal a')->result();

        $dados = '';
        foreach ($dadosCandidatos as $k => $dadosCandidato) {
            $dados .= "Candidato " . ($k + 1) . "
            Nome: {$dadosCandidato->nome}
            RG: {$dadosCandidato->rg}
            Data de nascimento: {$dadosCandidato->data_nascimento}
            PIS: {$dadosCandidato->pis}
            CPF: {$dadosCandidato->cpf}
            Cargo: {$dadosCandidato->cargo}
            Função: {$dadosCandidato->funcao}
            Centro de custo: {$dadosCandidato->centro_custo}
            Local de trabalho: {$dadosCandidato->local_trabalho}
            ";
        }
        $data['dados'] = $dados;

        echo json_encode($data);
    }

    //==========================================================================
    public function filtrarEmailContratacao()
    {
        $depto = $this->input->post('depto');
        $area = $this->input->post('area');
        $setor = $this->input->post('setor');
        $email = $this->input->post('email');

        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_departamentos b', 'b.id = a.id_departamento');
        $this->db->where('b.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('a.id_departamento', $depto);
        $this->db->order_by('a.nome', 'asc');
        $rowsAreas = $this->db->get('empresa_areas a')->result();
        $areas = array_column($rowsAreas, 'nome', 'id');


        $this->db->select('a.id, a.nome');
        $this->db->join('empresa_areas b', 'b.id = a.id_area');
        $this->db->join('empresa_departamentos c', 'c.id = b.id_departamento');
        $this->db->where('c.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('c.id', $depto);
        $this->db->where('b.id', $area);
        $this->db->order_by('a.nome', 'asc');
        $rowsSetores = $this->db->get('empresa_setores a')->result();
        $setores = array_column($rowsSetores, 'nome', 'id');


        $this->db->select('a.id, a.email');
        $this->db->join('usuarios b', 'b.email = a.email');
        $this->db->join('empresa_areas c', 'c.id = b.id_area OR c.nome = b.area', 'left');
        $this->db->join('empresa_setores d', 'd.id = b.id_setor OR d.nome = b.setor', 'left');
        $this->db->where('a.id_empresa', $this->session->userdata('empresa'));
        $this->db->where('b.status', 1);
        $this->db->where_in('a.tipo_email', [2, 3]);
        if ($depto) {
            $this->db->where('c.id_departamento', $depto);
        }
        if ($area) {
            $this->db->where('c.id', $area);
        }
        if ($setor) {
            $this->db->where('d.id', $setor);
        }
        $this->db->order_by('a.email', 'asc');
        $rowEmails = $this->db->get('requisicoes_pessoal_emails a')->result();
        $emails = array_column($rowEmails, 'email', 'id');

        $data['area'] = form_dropdown('', ['' => 'Todas'] + $areas, $area);
        $data['setor'] = form_dropdown('', ['' => 'Todos'] + $setores, $setor);
        $data['emails'] = form_dropdown('', ['' => 'selecione...'] + $emails, $email);

        echo json_encode($data);
    }

    //==========================================================================
    public function enviarEmail()
    {
        $remetente = $this->db
            ->select('id, nome, email')
            ->where('id', $this->session->userdata('id'))
            ->get('usuarios')
            ->row();

        $destinatarios = $this->db
            ->select('b.id, a.email, a.colaborador, a.tipo_email')
            ->join('usuarios b', 'b.email = a.email', 'left')
            ->where('a.id', $this->input->post('emails'))
            ->where('b.status', 1)
            ->where_in('a.tipo_email', [2, 3, 4])
            ->get('requisicoes_pessoal_email a')
            ->result();

        $filename = 'arquivos/temp/Requisição - ' . $this->input->post('id') . '.pdf';
        $this->pdf();

        $this->load->library('email');

        $data = array(
            'remetente' => $remetente->email,
            'titulo' => 'Requisição de Pessoal - Ativação de contratação',
            'datacadastro' => date('Y-m-d H:i:s')
        );

        $mensagem = $this->input->post('mensagem');
        $mensagem .= '<hr>' . nl2br($this->input->post('dados_candidatos'));

        foreach ($destinatarios as $destinatario) {
            $data['destinatario'] = $destinatario->id;
//            if ($destinatario->tipo_email === '1') {
//                $data['mensagem'] = '<p>Uma nova requisição de pessoal foi criada; uma cópia da mesma encontra-se anexo a esse email</p>';
//            } else {
//
//            }
            $data['mensagem'] = $mensagem;

            $this->email->from($remetente->email, $remetente->nome);
            $this->email->to($destinatario->email);
            $this->email->subject($data['titulo']);
            $this->email->message($data['mensagem']);
            $this->email->attach($filename);

            if (!$this->email->send()) {
                unlink($filename);
                exit(json_encode(['erro' => 'Erro ao enviar e-mail.']));
            }

            $this->email->clear();

            if (empty($destinatario->id)) {
                continue;
            }

            $this->db->trans_start();

            $this->db->insert('mensagensrecebidas', $data);
            $this->db->insert('mensagensenviadas', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() == false) {
                unlink($filename);
                exit(json_encode(['erro' => 'Erro ao notificar o destinatário.']));
            }
        }

        unlink($filename);

        $this->db->set('data_solicitacao_exame', date('Y-m-d H:i:s'));
        $this->db->where('id', $this->input->post('id'));
        $status = $this->db->update('requisicoes_pessoal');

        echo json_encode(['status' => $status !== false]);
    }

    //==========================================================================
    public function relatorio($id)
    {
        $this->ajax_relatorio();
    }

    //==========================================================================
    public function ajax_relatorio($pdf = false)
    {
        $this->db->select('foto, foto_descricao');
        $usuario = $this->db->get_where('usuarios', array('id' => $this->session->userdata('empresa')))->row();
        $data['foto'] = 'imagens/usuarios/' . $usuario->foto;
        $data['foto_descricao'] = 'imagens/usuarios/' . $usuario->foto_descricao;

        $sql = "SELECT a.id, 
                       a.numero,
                       DATE_FORMAT(a.data_abertura, '%d/%m/%Y') AS data_abertura,
                       IF(a.tipo_vaga = 'I', b.nome, b2.nome) AS requisitante,
                       CASE a.requisicao_confidencial
                            WHEN 0 THEN 'Não confidencial'
                            WHEN 1 THEN 'Confidencial'
                            ELSE 'Indefinida' END AS requisicao_confidencial,
                       CASE a.tipo_vaga
                            WHEN 'I' THEN 'Interno'
                            WHEN 'E' THEN 'Externo'
                            ELSE 'Indefinida' END AS tipo_vaga,
                       CONCAT_WS('/', c.nome, d.nome, e.nome) AS estrutura,
                       a.numero_contrato,
                       CASE a.regime_contratacao 
                            WHEN 1 THEN 'CLT'
                            WHEN 2 THEN 'MEI'
                            WHEN 3 THEN 'PJ'
                            WHEN 4 THEN 'Estágio'
                            END AS regime_contratacao,
                       a.centro_custo,
                       f.nome AS cargo,
                       g.nome AS funcao,
                       a.cargo_funcao_alternativo,
                       a.numero_vagas,
                       a.vagas_deficiente,
                       CASE a.justificativa_contratacao 
                            WHEN 'A' THEN 'Aumento de quadro'
                            WHEN 'S' THEN 'Substituição'
                            WHEN 'T' THEN 'Transferência'
                            END AS justificativa_contratacao,
                       a.colaborador_substituto,
                       a.possui_indicacao,
                       a.colaboradores_indicados,
                       a.indicador_responsavel,
                       CASE WHEN a.vale_alimentacao > 0 THEN 'Vale alimentacao' END AS vale_alimentacao,
                       CASE WHEN a.vale_transporte > 0 THEN 'Vale transporte' END AS vale_transporte,
                       CASE WHEN a.vale_refeicao > 0 THEN 'Vale refeição' END AS vale_refeicao,
                       CASE WHEN a.assistencia_medica > 0 THEN 'Assistência médica' END AS assistencia_medica,
                       CASE WHEN a.plano_odontologico > 0 THEN 'Plano odontológico' END AS plano_odontologico,
                       CASE WHEN a.cesta_basica > 0 THEN 'Cesta básica' END AS cesta_basica,
                       CASE WHEN a.participacao_resultados > 0 THEN 'Ajuda de custo' END AS participacao_resultados,
                       NULL AS beneficios,
                       CONCAT('R$ ', FORMAT(a.remuneracao_mensal, 2, 'de_DE')) AS remuneracao_mensal,
                       a.horario_trabalho,
                       DATE_FORMAT(a.previsao_inicio, '%d/%m/%Y') AS previsao_inicio,
                       a.local_trabalho,
                       a.municipio,
                       CASE WHEN a.exame_clinico > 0 THEN 'Exame clínico' END AS exame_clinico,
                       CASE WHEN a.audiometria > 0 THEN 'Audiometria' END AS audiometria,
                       CASE WHEN a.laudo_cotas > 0 THEN 'Laudo_cotas' END AS laudo_cotas,
                       a.exame_outros,
                       NULL AS exames_necessarios,
                       a.perfil_geral,
                       a.competencias_tecnicas,
                       a.competencias_comportamentais,
                       a.atividades_associadas,
                       a.observacoes,
                       a.id_depto,
                       a.nome_pai,
                       a.nome_mae,
                       DATE_FORMAT(a.data_nascimento, '%d/%m/%Y') AS data_nascimento,
                       a.rg,
                       DATE_FORMAT(a.rg_data_emissao, '%d/%m/%Y') AS rg_data_emissao,
                       a.rg_orgao_emissor,
                       a.cpf,
                       a.pis,
                       a.departamento_informacoes,
                       NULL AS candidatos_aprovados
                FROM requisicoes_pessoal a
                LEFT JOIN empresa_cargos f ON
                           f.id = a.id_cargo
                LEFT JOIN empresa_funcoes g ON
                           g.id = a.id_funcao
                LEFT JOIN empresa_departamentos c 
                           ON c.id = a.id_depto
                LEFT JOIN empresa_areas d 
                           ON d.id = a.id_area
                LEFT JOIN empresa_setores e
                           ON e.id = a.id_setor
                LEFT JOIN usuarios b
                           ON b.id = a.requisitante_interno
                LEFT JOIN usuarios b2
                           ON b2.id = a.requisitante_externo
                WHERE a.id_empresa = {$this->session->userdata('empresa')} 
                      AND a.id = {$this->uri->rsegment(3, $this->input->post('id'))}";

        $row = $this->db->query($sql)->row();
        $beneficios = array(
            $row->vale_alimentacao,
            $row->vale_transporte,
            $row->vale_refeicao,
            $row->assistencia_medica,
            $row->plano_odontologico,
            $row->cesta_basica,
            $row->participacao_resultados
        );
        $row->beneficios = implode(';<br>', array_filter($beneficios));
        $examesNecessarios = array(
            $row->exame_clinico,
            $row->audiometria,
            $row->laudo_cotas,
            $row->exame_outros
        );
        $row->exames_necessarios = implode(';<br>', array_filter($examesNecessarios));

        $this->db->select('b.nome');
        $this->db->join('recrutamento_usuarios b', 'b.id = a.id_usuario');
        $this->db->where('a.id_requisicao', $row->id);
        $this->db->where('a.aprovado', 1);
        $candidatosAprovados = $this->db->get('requisicoes_pessoal_candidatos a')->result();
        $row->candidatos_aprovados = array_column($candidatosAprovados, 'nome');

        $data['row'] = $row;

        $data['is_pdf'] = $pdf;
        if ($pdf) {
            $data['mostrar_aprovados'] = $this->input->get_post('aprovados');

            return $this->load->view('requisicaoPessoal_pdf', $data, true);
        }

        $this->load->view('requisicaoPessoal_relatorio', $data);
    }

    //==========================================================================
    public function pdf()
    {
        $this->load->library('m_pdf');

        $stylesheet = 'table.requisicao thead th { font-size: 12px; padding: 5px; text-align: center; font-weight: normal; } ';
        $stylesheet .= 'table.requisicao tbody tr { border-width: 5px; border-color: #ddd; } ';
        $stylesheet .= 'table.requisicao tbody tr th { font-size: 11px; padding: 2px; } ';
        $stylesheet .= 'table.requisicao tbody td { font-size: 12px; padding: 1px; border-top: 1px solid #ddd;} ';
        $stylesheet .= 'table.requisicao tbody td strong { font-weight: bold; } ';

        $stylesheet .= 'table.dados thead th { font-size: 12px; padding: 5px; border-bottom: 2px solid #ddd; } ';
        $stylesheet .= 'table.dados thead tr.active td { background-color: #e5e5e5; }';
        $stylesheet .= 'table.dados tbody td { font-size: 12px; padding: 5px; border-top: 1px solid #ddd; word-wrap: break-word;} ';

        $this->m_pdf->pdf->writeHTML($stylesheet, 1);
        $this->m_pdf->pdf->writeHTML($this->ajax_relatorio(true));

        if ($this->input->post('id')) {
            $this->m_pdf->pdf->Output('arquivos/temp/Requisição - ' . $this->input->post('id') . '.pdf', 'F');
        } else {
            $this->m_pdf->pdf->Output('Requisição - ' . $this->uri->rsegment(3) . '.pdf', 'D');
        }
    }

    //==========================================================================
    public function publicarVaga()
    {
        $this->db->select('a.id_empresa');
        $this->db->select('a.data_abertura');
        $this->db->select(["IF(a.status = 'A', 1, 0) AS status"], false);
        $this->db->select('a.id AS id_requisicao_pessoal');
        $this->db->select('a.id_cargo');
        $this->db->select('a.id_funcao');
//        $this->db->select('formacao_minima');
//        $this->db->select('formacao_especifica_minima');
//        $this->db->select('perfil_profissional_desejado');
        $this->db->select('a.numero_vagas AS quantidade');
        $this->db->select('d.estado AS estado_vaga');
        $this->db->select('a.municipio AS cidade_vaga');
        $this->db->select('a.local_trabalho AS bairro_vaga');
        $this->db->select('a.regime_contratacao AS tipo_vinculo');
        $this->db->select(['IFNULL(a.remuneracao_mensal, 0) AS remuneracao'], false);
        $this->db->select("(CASE a.vale_transporte WHEN 1 THEN 'vale transporte' END) AS vale_transporte", false);
        $this->db->select("(CASE a.vale_alimentacao WHEN 1 THEN 'vale alimentação' END) AS vale_alimentacao", false);
        $this->db->select("(CASE a.vale_refeicao WHEN 1 THEN 'vale refeição' END) AS vale_refeicao", false);
        $this->db->select("(CASE a.cesta_basica WHEN 1 THEN 'cesta básica' END) AS cesta_basica", false);
        $this->db->select("(CASE a.assistencia_medica WHEN 1 THEN 'assistência médica' END) AS assistencia_medica", false);
        $this->db->select("(CASE a.plano_odontologico WHEN 1 THEN 'plano odontológico' END) AS plano_odontologico", false);
        $this->db->select("(CASE a.participacao_resultados WHEN 1 THEN 'ajuda de custo' END) AS participacao_resultados", false);
        $this->db->select('NULL AS beneficios', false);
        $this->db->select('a.horario_trabalho');
        $this->db->select(["CONCAT(b.nome, ' ', b.telefone) AS contato_selecionador"], false);
        $this->db->join('usuarios b', 'b.nome = a.selecionador', 'left');
        $this->db->join('municipios c', 'c.municipio = a.municipio', 'left');
        $this->db->join('estados d', 'd.cod_uf = c.cod_uf', 'left');
        $this->db->where('a.id', $this->input->post('id'));
        $data = $this->db->get('requisicoes_pessoal a')->row_array();

        $camposBeneficios = array(
            'vale_transporte' => $data['vale_transporte'],
            'vale_alimentacao' => $data['vale_alimentacao'],
            'vale_refeicao' => $data['vale_refeicao'],
            'cesta_basica' => $data['cesta_basica'],
            'assistencia_medica' => $data['assistencia_medica'],
            'plano_odontologico' => $data['plano_odontologico'],
            'participacao_resultados' => $data['participacao_resultados']
        );

        $beneficios = array_filter($camposBeneficios);

        if (count($beneficios)) {
            $data['beneficios'] = ucfirst(implode(', ', $beneficios));
        }

        $data = array_diff_key($data, $camposBeneficios);

        $this->db->trans_start();
        $this->db->insert('gestao_vagas', $data);
        $this->db->trans_complete();

        $status = $this->db->trans_status();

        echo json_encode(['status' => $status]);
    }


}
