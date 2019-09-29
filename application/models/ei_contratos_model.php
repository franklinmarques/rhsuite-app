<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ei_contratos_model extends MY_Model
{
    protected static $table = 'ei_contratos';

    protected $validationRules = [
        'id' => 'required|is_natural_no_zero|max_length[11]',
        'id_diretoria' => 'required|is_natural_no_zero|max_length[11]',
        'contrato' => 'required|max_length[30]',
        'data_inicio' => 'required|valid_date',
        'data_termino' => 'required|valid_date|after_date[data_inicio]',
        'data_reajuste1' => 'valid_date|after_date[data_inicio]|before_date[data_termino]|required_with[indice_reajuste1,data_reajuste2]',
        'indice_reajuste1' => 'numeric|max_length[12]|required_with[data_reajuste1,indice_reajuste2]',
        'data_reajuste2' => 'valid_date|after_date[data_reajuste1]|before_date[data_termino]|required_with[indice_reajuste2,data_reajuste3]',
        'indice_reajuste2' => 'numeric|max_length[12]|required_with[data_reajuste2,indice_reajuste3]|differs[indice_reajuste1]',
        'data_reajuste3' => 'valid_date|after_date[data_reajuste2]|before_date[data_termino]|required_with[indice_reajuste3,data_reajuste4]',
        'indice_reajuste3' => 'numeric|max_length[12]|required_with[data_reajuste3,indice_reajuste4]|differs[indice_reajuste2]',
        'data_reajuste4' => 'valid_date|after_date[data_reajuste3]|before_date[data_termino]|required_with[indice_reajuste4,data_reajuste5]',
        'indice_reajuste4' => 'numeric|max_length[12]|required_with[data_reajuste4,indice_reajuste5]|differs[indice_reajuste3]',
        'data_reajuste5' => 'valid_date|after_date[data_reajuste4]|before_date[data_termino]|required_with[indice_reajuste5]',
        'indice_reajuste5' => 'numeric|max_length[12]|differs[indice_reajuste4]|required_with[data_reajuste5]'
    ];

}
