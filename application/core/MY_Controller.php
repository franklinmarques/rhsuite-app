<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logado') and !in_array($this->uri->rsegment(2), ['alterarsenha', 'alterarsenha_json'])) {

            if ($this->input->is_ajax_request()) {
                set_status_header(401, 'expirado');
                exit;
            } else {
                redirect(site_url('login'));
            }
        }
    }

    public function manutencao()
    {
        $this->load->view('manutencao');
    }


    static function url_exists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($code == 200);
    }

    public function get_filtros_usuarios($depto = '', $area = '', $setor = '', $cargo = '', $funcao = '')
    {
        $empresa = $this->session->userdata('empresa');

        $arrSql = array('depto', 'area', 'setor', 'cargo', 'funcao');

        $args = func_get_args();
        $arrWhere = array();

        foreach ($arrSql as $k => $field) {

            $data[$field] = array('' => in_array($field, array('area', 'funcao')) ? 'Todas' : 'Todos');

            $this->db->select("DISTINCT({$field}) AS field", false);
            $this->db->where('empresa', $empresa);
            foreach ($arrWhere as $where => $value) {
                if ($value) {
                    $this->db->where($where, $value);
                }
            }
            $this->db->where("CHAR_LENGTH({$field}) >", 0);
            $this->db->order_by($field, 'asc');
            $rows = $this->db->get('usuarios')->result();

            foreach ($rows as $row) {

                $data[$field][$row->field] = $row->field;
            }

            if (isset($args[$k])) {

                $arrWhere[$field] = $args[$k];
            }
        }

        return $data;
    }

    public function consultar_cep()
    {
        $cep = $this->input->post('cep');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://viacep.com.br/ws/{$cep}/json/unicode/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resultado = json_decode(curl_exec($ch));
        curl_close($ch);

        $data = array();
        if (!isset($resultado->erro) && isset($resultado->cep)) {
            $this->db->select('cod_uf');
            $estado = $this->db->get_where('estados', array('uf' => $resultado->uf))->row();

            $sql = "SELECT a.cod_mun,
                           a.municipio 
                    FROM municipios a 
                    INNER JOIN estados b ON 
                               b.cod_uf = a.cod_uf 
                    WHERE a.cod_uf = {$estado->cod_uf}";
            $rows = $this->db->query($sql)->result();
            $options = array('' => 'selecione ...');
            foreach ($rows as $row) {
                $options[$row->cod_mun] = $row->municipio;
            }

            $data = array(
                'cep' => $resultado->cep,
                'logradouro' => $resultado->logradouro,
                'complemento' => $resultado->complemento,
                'bairro' => $resultado->bairro,
                'estado' => $estado->cod_uf,
                'numero' => $resultado->unidade,
                'cidade' => form_dropdown('cidade', $options, $resultado->ibge, 'id="cidade" class="form-control filtro"')
            );
        } elseif (isset($resultado->erro)) {
            $data['erro'] = $resultado->erro;
        }

        echo json_encode($data);
    }

    public function ajax_cidades()
    {
        $estado = $this->input->post('estado');

        $this->db->order_by('municipio', 'asc');
        $rows = $this->db->get_where('municipios', array('cod_uf' => $estado))->result();
        $options = array('' => 'selecione ...');
        foreach ($rows as $row) {
            $options[$row->cod_mun] = $row->municipio;
        }

        $data['cidades'] = form_dropdown('cidade', $options, array(), 'id="cidade" class="form-control"');

        echo json_encode($data);
    }

//    protected function get_datatables_query($columns, $subquery)
//    {
//        $sql = 'SELECT';
//        
//        if ($post['search']['value']) {
//            foreach ($columns as $key => $column) {
//                if ($key > 0) {
//                    $sql .= " OR
//                         {$column} LIKE '%{$post['search']['value']}%'";
//                } elseif ($key == 1) {
//                    $sql .= " 
//                        WHERE {$column} LIKE '%{$post['search']['value']}%'";
//                }
//            }
//        }
//        $recordsFiltered = $this->db->query($sql)->num_rows();
//
//        if (isset($post['order'])) {
//            $orderBy = array();
//            foreach ($post['order'] as $order) {
//                $orderBy[] = $columns[$order['column']] . ' ' . $order['dir'];
//            }
//            $sql .= ' 
//                    ORDER BY ' . implode(', ', $orderBy);
//        }
//        $sql .= " 
//                LIMIT {$post['start']}, {$post['length']}";
//        $list = $this->db->query($sql)->result();
//    }
}
