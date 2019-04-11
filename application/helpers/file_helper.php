<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * uploadConfig
 *
 * Configura arquivos para upload em diretórios específicos
 *
 * @access    public
 * @param    string    file
 * @param    string    path
 * @return    string
 */
if (!function_exists('uploadConfig')) {

    function uploadConfig($file = '', $path = '')
    {
        if (!isset($_FILES[$file])) {
            return false;
        }

        $config = array();

        switch ($path) {
            case 'emails':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'usuarios':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'ftp':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'sql':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'csv':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'documentos':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'empresa-docs':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'laudos':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'media':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'pdf':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'temp':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'videos':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            default:
                return false;
        }

        return $config;
    }

}
