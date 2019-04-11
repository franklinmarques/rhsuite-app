<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EiObservacoes_model extends MY_Model
{

    /**
     * Nome da tabela do banco de dados
     */
    protected $table = 'ei_observacoes';

    //--------------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();
    }

}
