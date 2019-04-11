<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Imagem extends MY_Controller
{

    public function avatar()
    {
        include('m2brimagem.class.php');

        $arquivo = './imagens/usuarios/' . $this->uri->rsegment(3);
        $largura = 100;
        $altura = 100;

        $oImg = new m2brimagem($arquivo);
        $oImg->valida();
        $oImg->redimensiona($largura, $altura, 'crop');
        $oImg->grava();
    }

}
