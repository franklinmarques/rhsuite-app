<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa extends CI_Controller
{

    public function index()
    {
        $empresa = $this->db->query("SELECT * FROM usuarios WHERE url = ?", array($this->uri->segment(1)))->row();

        if (count($empresa) > 0) {
            if ($this->session->userdata('logado')) {
                redirect(substr_replace(current_url(), '', strpos(current_url(), '/' . $empresa->url), strlen($empresa->url) + 1));
            } else {
                $this->config->set_item('index_page', $empresa->url);
                redirect(site_url('login'));
            }
        } else {
            show_404();
        }
    }

    public function isValid()
    {
        return $this->db->get_where('usuario', array('url' => $this->uri->rsegment(1)))->num_rows() > 0;
    }

}
