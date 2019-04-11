<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| UPLOAD FILE PREFERENCES SETTINGS
| -------------------------------------------------------------------
| Configura as preferências de upload para cada diretório da Plataforma.
|
*/

//$config['upload_path'] = './arquivos/documentos/organizacao/';
//$config['allowed_types'] = 'pdf';
//$config['max_size'] = '102400';

/*$config = array(
    'max_size' => 10,
    'max_width' => 0,
    'max_height' => 0,
    'max_filename' => 0,
    'allowed_types' => '',
    'file_temp' => '',
    'file_name' => utf8_decode($_FILES['arquivo']['name']),
    'orig_name' => '',
    'file_type' => '',
    'file_size' => '',
    'file_ext' => '',
    'upload_path' => '',
    'overwrite' => '',
    'encrypt_name' => '',
    'is_image' => '',
    'image_width' => '',
    'image_height' => '',
    'image_type' => '',
    'image_size_str' => '',
    'error_msg' => Array(
        '0' => 'O diretório de destino não é válido.'
    ),
    'mimes' => Array(),
    'remove_spaces' => 1,
    'xss_clean' => '',
    'temp_prefix' => 'temp_file_',
    'client_name' => '',
    '_file_name_override:protected' => ''
);*/

/*$config = function ($file, $path) {
    if (!isset($_FILES[$file])) {
        return false;
    }

    $option = ['file_name' => utf8_decode($_FILES[$file]['name'])];

    switch ($path) {
        case 'emails':

            break;
        case 'usuarios':
            $option['upload_path'] = './imagens/usuarios/';
            $option['allowed_types'] = 'gif|jpg|png';
            break;
        case 'ftp':
            $option['upload_path'] = '.arquivos/backup/ftp/';
            $option['allowed_types'] = 'sql';
            break;
        case 'sql':
            $option['upload_path'] = '.arquivos/backup/sql/';
            $option['allowed_types'] = 'sql';
            $option['file_name'] = $_FILES[$file]['tmp_name'];
            break;
        case 'csv':
            $option['upload_path'] = './arquivos/csv/';
            $option['allowed_types'] = '*';
            $option['overwrite'] = TRUE;
            break;
        case 'documentos':
            $option['upload_path'] = './arquivos/documentos/organizacao/';
            $option['allowed_types'] = 'pdf';
            $option['max_size'] = '102400';
            break;
        case 'empresa-docs':
            $option['upload_path'] = './arquivos/empresa-docs/';
            $option['allowed_types'] = '*';
            break;
        case 'laudos':
            $option['upload_path'] = './arquivos/laudos/';
            $option['allowed_types'] = 'pdf';
            break;
        case 'media':
            $option['upload_path'] = './arquivos/media/';
            $option['allowed_types'] = '*';
            break;
        case 'pdf':
            $option['upload_path'] = './arquivos/pdf/';
            $option['allowed_types'] = 'pdf|doc|docx|txt|ppt|pptx';
            break;
        case 'temp':

            break;
        case 'videos':
            $option['upload_path'] = './arquivos/videos/';
            $option['allowed_types'] = '*';
            $option['upload_max_filesize'] = '10240';
            break;
        default:
            return null;
    }

    return $option;
};*/
