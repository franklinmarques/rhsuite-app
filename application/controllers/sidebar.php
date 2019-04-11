<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sidebar extends MY_Controller
{

    public function gerarLocalizacao($latitude = null, $longitude = null)
    {
        $localizacao = $this->session->userdata('localizacao');
        if (empty($localizacao) && !empty($latitude) && !empty($longitude)) {
            $this->session->set_userdata(array('localizacao' => array('latitude' => $latitude, 'longitude' => $longitude)));
            exit('success');
        }

        exit('error');
    }

}
