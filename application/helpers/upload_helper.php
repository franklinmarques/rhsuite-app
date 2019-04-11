<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * get_upload_config
 *
 * Retorna as configurações padrão de upload de arquivos para um diretório
 *
 * @access    public
 * @param string file Nome do campo do arquivo
 * @param string path Caminho para o arquivo
 * @return    string
 */
if (!function_exists('get_upload_config')) {

    function get_upload_config(string $file = '', string $path = ''): array
    {
        if (!isset($_FILES[$file])) {
            return array();
        }


        $config = ['file_name' => utf8_decode($_FILES[$file]['name'])];


        switch ($path) {
            case 'emails':

                break;
            case 'usuarios':
                $config['upload_path'] = './imagens/usuarios/';
                $config['allowed_types'] = 'gif|jpg|png';
                break;
            case 'ftp':
                $config['upload_path'] = '.arquivos/backup/ftp/';
                $config['allowed_types'] = 'sql';
                break;
            case 'sql':
                $config['upload_path'] = '.arquivos/backup/sql/';
                $config['allowed_types'] = 'sql';
                $config['file_name'] = $_FILES[$file]['tmp_name'];
                break;
            case 'csv':
                $config['upload_path'] = './arquivos/csv/';
                $config['allowed_types'] = '*';
                $config['overwrite'] = true;
                break;
            case 'documentos':
                $config['upload_path'] = './arquivos/documentos/organizacao/';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = '102400';
                break;
            case 'empresa-docs':
                $config['upload_path'] = './arquivos/empresa-docs/';
                $config['allowed_types'] = '*';
                break;
            case 'laudos':
                $config['upload_path'] = './arquivos/laudos/';
                $config['allowed_types'] = 'pdf';
                break;
            case 'media':
                $config['upload_path'] = './arquivos/media/';
                $config['allowed_types'] = '*';
                break;
            case 'pdf':
                $config['upload_path'] = './arquivos/pdf/';
                $config['allowed_types'] = 'pdf|doc|docx|txt|ppt|pptx';
                break;
            case 'temp':

                break;
            case 'videos':
                $config['upload_path'] = './arquivos/videos/';
                $config['allowed_types'] = '*';
                $config['upload_max_filesize'] = '10240';
                break;
            default:
                return array();
        }


        return $config;
    }

}
