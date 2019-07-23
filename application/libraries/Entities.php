<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Entities
{

    public function create(string $class, array $data = []): Entity
    {
        require_once APPPATH . 'entities/' . ucfirst($class) . '.php';

        return new $class($data);
    }

}
