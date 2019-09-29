<?php
/**
 * Created by PhpStorm.
 * User: frank
 * Date: 13/04/2018
 * Time: 06:34
 */

class MY_Upload extends CI_Upload
{

    public function set_filename($path, $filename)
    {
        if (strlen($filename) > 0 and $this->encrypt_name == false) {
            $filename = convert_accented_characters(utf8_encode($filename)) . '_' . substr(md5(uniqid()), 0, rand(4, 6));
        }

        return parent::set_filename($path, $filename);
    }

}