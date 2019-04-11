<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 13/04/2018
 * Time: 06:34
 */

class MY_Session extends CI_Session
{

    /**
     * Grava a última atividade no banco de dados a cada 5 minutos por padrão
     */
    function sess_update()
    {
        if ($this->CI->input->is_ajax_request() OR ($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now) {
            return;
        }

        if (($this->userdata['last_activity'] + $this->sess_expiration) >= $this->now) {

            $CI = &get_instance();

            $CI->db->select('id');
            $CI->db->where('usuario', $this->userdata('id'));
            $CI->db->order_by('id', 'desc');
            $CI->db->limit(1);
            $log = $CI->db->get('acessosistema')->row();

            $data = array(
                'data_atualizacao' => date('Y-m-d H:i:s')
            );
            $CI->db->update('acessosistema', $data, array('id' => $log->id));
        }

        parent::sess_update();
    }

}