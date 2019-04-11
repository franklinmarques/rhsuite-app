<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EiMatriculados_model extends MY_Model
{

    /**
     * Nome da tabela do banco de dados
     */
    protected $table = 'ei_matriculados';

    //--------------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();
    }

}
