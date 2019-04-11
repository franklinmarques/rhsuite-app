<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EiConsumos_model extends MY_Model
{

    /**
     * Nome da tabela do banco de dados
     */
    protected $table = 'ei_consumos';

    //--------------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();
    }

}
