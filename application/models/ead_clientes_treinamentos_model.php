<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ead_clientes_treinamentos_model extends CI_Model
{

    private static $table = 'cursos_clientes_treinamentos';


    public function validate()
    {
        $post = $this->input->post();

        $config = array(
            array(
                'field' => 'id',
                'label' => 'ID',
                'rules' => 'integer|max_length[11]'
            ),
            array(
                'field' => 'id_usuario',
                'label' => 'ID usuário',
                'rules' => 'required|integer|max_length[11]'
            ),
            array(
                'field' => 'id_curso',
                'label' => 'Nome treinamento',
                'rules' => ($post['tipo_treinamento'] == 'E' ? 'required|' : '') . 'integer|max_length[11]'
            ),
            array(
                'field' => 'nome',
                'label' => 'Nome treinamento',
                'rules' => ($post['tipo_treinamento'] == 'P' ? 'required|' : '') . 'max_length[255]'
            ),
            array(
                'field' => 'tipo_treinamento',
                'label' => 'Tipo treinamento',
                'rules' => 'exact_length[1]'
            ),
            array(
                'field' => 'local_treinamento',
                'label' => 'Local',
                'rules' => 'exact_length[1]'
            ),
            array(
                'field' => 'carga_horaria_presencial',
                'label' => 'Carga horária',
                'rules' => 'valid_time'
            ),
            array(
                'field' => 'data_inicio',
                'label' => 'Data início',
                'rules' => 'valid_date'
            ),
            array(
                'field' => 'data_maxima',
                'label' => 'Data máxima',
                'rules' => 'valid_date|after_or_equal_date[data_inicio]'
            ),
            array(
                'field' => 'avaliacao_presencial',
                'label' => 'Avaliação final',
                'rules' => 'integer|max_length[3]'
            ),
            array(
                'field' => 'nota_aprovacao',
                'label' => 'Nota mínima para emitir certificado',
                'rules' => 'integer|max_length[3]'
            ),
            array(
                'field' => 'nome_fornecedor',
                'label' => 'Fornecedor/palestrante',
                'rules' => 'max_length[255]'
            )
        );

        $this->load->library('form_validation');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_array();
        }

        return true;
    }


    public function revalidate()
    {
        $post = $this->input->post();

        $config = array(
            array(
                'field' => 'id',
                'label' => 'ID',
                'rules' => 'required|integer|max_length[11]'
            ),
            array(
                'field' => 'id_usuario',
                'label' => 'ID usuário',
                'rules' => 'required|integer|max_length[11]'
            ),
            array(
                'field' => 'id_curso',
                'label' => 'Nome treinamento',
                'rules' => ($post['tipo_treinamento'] == 'E' ? 'required|' : '') . 'integer|max_length[11]'
            ),
            array(
                'field' => 'nome',
                'label' => 'Nome treinamento',
                'rules' => ($post['tipo_treinamento'] == 'P' ? 'required|' : '') . 'max_length[255]'
            ),
            array(
                'field' => 'tipo_treinamento',
                'label' => 'Tipo treinamento',
                'rules' => 'exact_length[1]'
            ),
            array(
                'field' => 'local_treinamento',
                'label' => 'Local',
                'rules' => 'exact_length[1]'
            ),
            array(
                'field' => 'carga_horaria_presencial',
                'label' => 'Carga horária',
                'rules' => 'valid_time'
            ),
            array(
                'field' => 'data_inicio',
                'label' => 'Data início',
                'rules' => 'valid_date'
            ),
            array(
                'field' => 'data_maxima',
                'label' => 'Data máxima',
                'rules' => 'valid_date|after_or_equal_date[data_inicio]'
            ),
            array(
                'field' => 'avaliacao_presencial',
                'label' => 'Avaliação final',
                'rules' => 'integer|max_length[3]'
            ),
            array(
                'field' => 'nota_aprovacao',
                'label' => 'Nota mínima para emitir certificado',
                'rules' => 'integer|max_length[3]'
            ),
            array(
                'field' => 'nome_fornecedor',
                'label' => 'Fornecedor/palestrante',
                'rules' => 'max_length[255]'
            )
        );

        $this->load->library('form_validation');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return $this->form_validation->error_array();
        }

        return true;
    }


    public function insert($data)
    {
        $this->db->trans_start();
        $this->db->insert(self::$table, $data);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }


    public function update($data, $where)
    {
        $this->db->trans_start();
        $this->db->update(self::$table, $data, $where);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }


    public function delete($where)
    {
        $this->db->trans_start();
        $this->db->delete(self::$table, $where);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

}
