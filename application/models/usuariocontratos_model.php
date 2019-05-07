<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UsuarioContratos_model extends CI_Model
{

    protected static $table = 'usuarios_contratos';


    public function find($where)
    {
        return $this->db->get_where(self::$table, $where)->row();
    }


    public function insert($data)
    {
        return $this->db->insert(self::$table, $data);
    }


    public function update($data, $where)
    {
        return $this->db->update(self::$table, $data, $where);
    }


    public function delete($where)
    {
        return $this->db->delete(self::$table, $where);
    }

}
