<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends MY_Controller
{

    protected $backup_tb = null;

    public function __construct()
    {
        parent::__construct();
        if (!in_array($this->session->userdata('tipo'), array('administrador', 'empresa'))) {
            redirect(site_url('home'));
        }
    }

    public function index()
    {
        $this->load->helper(array('form'));
        $this->load->view('backup');
    }

    public function ftp()
    {
        $arquivo = "arquivos/backup/ftp/rhsuite_app_" . date('dmY_His') . ".zip";
        $uptime = exec("zip -r $arquivo ../app/");

        if (isset($arquivo) && file_exists($arquivo)) {
            // faz o teste se a variavel não esta vazia e se o arquivo realmente existe
            switch (strtolower(substr(strrchr(basename($arquivo), "."), 1))) {

                // verifica a extensão do arquivo para pegar o tipo
                case "pdf":
                    $tipo = "application/pdf";
                    break;
                case "exe":
                    $tipo = "application/octet-stream";
                    break;
                case "zip":
                    $tipo = "application/zip";
                    break;
                case "doc":
                    $tipo = "application/msword";
                    break;
                case "xls":
                    $tipo = "application/vnd.ms-excel";
                    break;
                case "ppt":
                    $tipo = "application/vnd.ms-powerpoint";
                    break;
                case "gif":
                    $tipo = "image/gif";
                    break;
                case "png":
                    $tipo = "image/png";
                    break;
                case "jpg":
                    $tipo = "image/jpg";
                    break;
                case "mp3":
                    $tipo = "audio/mpeg";
                    break;
                case "php": // deixar vazio por segurança
                    break;
                case "htm": // deixar vazio por segurança
                    break;
                case "html": // deixar vazio por segurança
                    break;
            }
            header("Content-Type: " . $tipo); // informa o tipo do arquivo ao navegador
            header("Content-Length: " . filesize($arquivo)); // informa o tamanho do arquivo ao navegador
            header("Content-Disposition: attachment; filename=" . basename($arquivo));
            // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
            readfile($arquivo); // lê o arquivo
            exit();
            // aborta pós-ações
        }
    }


    public function mysql1()
    {
        $this->load->dbutil();

        $backup =& $this->dbutil->backup(['format' => 'txt']);

        $filename = $this->db->database . ' ' . date('Ymd_His') . '.sql';

        $this->load->helper('file');
        write_file('arquivos/backup/sql/' . $filename, $backup);

        $this->load->helper('download');
        force_download($filename, $backup);
    }


    public function mysql()
    {
        $query = $this->db->query('SHOW TABLES');
        $tabelas = array();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $obj = 'Tables_in_' . $this->db->database;
                $tabelas[] = $row->$obj;
            }
        }

        $this->backup_tb = array();

        foreach ($tabelas as $tabela) {

            $query = $this->db->query('SELECT * FROM ' . $tabela);

            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    # Apago todos os array númericos
                    if (is_array($row)) {
                        foreach (array_keys($row) as $key) {
                            if (gettype($key) == 'integer') {
                                unset($row[$key]);
                            }
                        }
                    }
                    $this->backup_tb[$tabela][] = $row;
                }
            }
        }

        if (isset($_GET['tipo'])) {

            if ($_GET['tipo'] == 'json') {
                $this->mysql_json();
            } elseif ($_GET['tipo'] == 'sql') {
                $this->mysql_sql();
            }
        } else {
            exit();
        }
    }

    public function mysql_json()
    {
        $dbase = null;

        $json = array(
            array(
                $dbase => $this->backup_tb
            ));

        header('Content-disposition: attachment; filename=rhsuite_' . date('dmY_His') . '.json');
        header("Content-Type:text/json");

        echo json_encode($json);
    }

    public function mysql_sql()
    {
        $dbhost = $this->db->hostname;
        $dbuser = $this->db->username;
        $dbpass = $this->db->password;
        $dbname = $this->db->database;
        $arquivo = $dbname . date('dmY_His') . '.sql';

        $backup_file = 'arquivos/backup/sql/' . $arquivo;

        # Comando SQL
        $command = "mysqldump --opt -h $dbhost -u $dbuser -p'$dbpass' " . "$dbname > $backup_file";
        exec($command);


        if (file_exists($backup_file) === true) {
            $this->load->helper('download');
            $data = file_get_contents($backup_file); // Read the file's contents
            force_download($arquivo, $data);

            # essa linha pode ser comentada caso queira fazer copia no servidor
            //unlink($backup_file); # destroi o arquivo temporário
        } else {
            return false;
        }
    }

    public function importar_sql()
    {
        $sql = $this->input->post();
        $this->db->query($sql);
    }

    public function ajax_list()
    {
        $post = $this->input->post();

        $ftp = 'arquivos/backup/ftp/';
        $sql = 'arquivos/backup/sql/';

        $arrFtp = scandir($ftp);
        $arrSql = scandir($sql);

        $list = array();
        $recordsTotal = 0;
        $recordsFiltered = 0;

        if ($this->session->userdata('tipo') == 'administrador') {
            foreach ($arrFtp as $arquivo) {
                if (is_file($ftp . $arquivo)) {
                    $list[] = array(
                        'data' => stat($ftp . $arquivo)['ctime'],
                        'nome' => $arquivo,
                        'tipo' => 'Diretório de arquivos',
                        'formato' => strstr($arquivo, '.'),
                        'protegido' => '<span class="text-muted glyphicon glyphicon-ok"></span>'
                    );
                }
                $recordsTotal++;
            }
        }
        foreach ($arrSql as $arquivo) {
            if (is_file($sql . $arquivo)) {
                $list[] = array(
                    'data' => stat($sql . $arquivo)['ctime'],
                    'nome' => $arquivo,
                    'tipo' => 'Banco de dados',
                    'formato' => strstr($arquivo, '.'),
                    'protegido' => '<span class="text-success glyphicon glyphicon-ok"></span>'
                );
            }
            $recordsTotal++;
        }

        $tmp = array();
        $columns = array('data', 'nome', 'tipo', 'formato', 'protegido');
        foreach ($list as $key => $value) {
            if (!empty($post['search']['value'])) {
                $search = strtolower($post['search']['value']);
                $lwData = date("d/m/Y H:i:s", $value['data']);
                $lwNome = strtolower($value['nome']);
                if (!(strstr($lwData, $search) or strstr($lwNome, $search))) {
                    unset($list[$key]);
                    continue;
                }
            }
            $tmp[] = $value[$columns[$post['order'][0]['column']]];
            $recordsFiltered++;
        }

        array_multisort($tmp, $post['order'][0]['dir'] === 'asc' ? SORT_ASC : ($post['order'][0]['dir'] === 'desc' ? SORT_DESC : SORT_REGULAR), $list);

        $data = array();
        foreach ($list as $arquivo) {
            $row = array();
            $row[] = date("d/m/Y H:i:s", $arquivo['data']);
            if ($arquivo['tipo'] === 'Banco de dados') {
                $row[] = '<span class="text-info fa fa-database"></span> ' . $arquivo['nome'];
            } else {
                $row[] = '<span class="text-warning fa fa-archive"></span> ' . $arquivo['nome'];
            }
            $row[] = $arquivo['tipo'];
            $row[] = $arquivo['formato'];
            $row[] = $arquivo['protegido'];

            if ($arquivo['tipo'] === 'Banco de dados') {
                $row[] = '
                      <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Baixar" onclick="baixar_backup(' . "'" . $arquivo['nome'] . "'" . ')"><i class="glyphicon glyphicon-save"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="backup_delete(' . "'" . $arquivo['nome'] . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Restaurar" onclick="restaura_backup(' . "'" . $arquivo['nome'] . "'" . ')"><i class="glyphicon glyphicon-import"></i> Restaurar</a>
                     ';
            } else {
                $row[] = '
                      <a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Baixar" onclick="baixar_backup(' . "'" . $arquivo['nome'] . "'" . ')"><i class="glyphicon glyphicon-save"></i></a>
                      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Excluir" onclick="backup_delete(' . "'" . $arquivo['nome'] . "'" . ')"><i class="glyphicon glyphicon-trash"></i></a>
                      <button class="btn btn-sm btn-success disabled" title="Restaurar"><i class="glyphicon glyphicon-import"></i> Restaurar</button>
                     ';
            }

            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_delete()
    {
        $filename = $this->input->post('filename');
        $status = true;

        if (is_file('arquivos/backup/ftp/' . $filename)) {
            $filepath = 'arquivos/backup/ftp/' . $filename;
        } elseif (is_file('arquivos/backup/sql/' . $filename)) {
            $filepath = 'arquivos/backup/sql/' . $filename;
        } else {
            $status = false;
        }

        if ($status) {
            $status = unlink($filepath);
        }
        echo json_encode(array("status" => $status));
    }

    public function baixar()
    {
        $filename = $this->input->post('filename');
        $status = true;

        if (is_file('arquivos/backup/ftp/' . $filename)) {
            $filepath = 'arquivos/backup/ftp/' . $filename;
        } elseif (is_file('arquivos/backup/sql/' . $filename)) {
            $filepath = 'arquivos/backup/sql/' . $filename;
        } else {
            $status = false;
        }
        if ($status) {
            $this->load->helper('download');
            $data = file_get_contents($filepath);
            force_download($filepath, $data);
//            $status = true;
//            echo true;
//        } else {
////            return false;
        }
//        echo json_encode(array("status" => $status));
    }

    public function restaurar()
    {
        $filename = $this->input->post('filename');
        if (empty($filename) and isset($_FILES['arquivo'])) {
            $filename = $_FILES['arquivo']['tmp_name'];
        }
        $status = false;

        if (is_file('arquivos/backup/ftp/' . $filename)) {
            $backup_file = 'arquivos/backup/ftp/' . $filename;
        } elseif (is_file('arquivos/backup/sql/' . $filename)) {
            $backup_file = 'arquivos/backup/sql/' . $filename;

            $dbhost = $this->db->hostname;
            $dbuser = $this->db->username;
            $dbpass = $this->db->password;
            $dbname = $this->db->database;

            $command = "mysql -h $dbhost -u $dbuser -p'$dbpass' " . "$dbname < $backup_file";

            try {
                exec($command);
                $status = true;
            } catch (Exception $exc) {
                $status = $exc->getTraceAsString();
            }
        }
        echo json_encode(array('status' => $status));
    }

}
