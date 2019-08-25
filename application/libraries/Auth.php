<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth
{

    private static $salt = '@#d13g0tr1nd4d3!';


    private static $emailSystem = 'sistema@rhsuite.com.br';


    private static $emailAdmin = 'mhffortes@hotmail.com';


    private static $emailContact = 'contato@peoplenetcorp.com.br';


    private static $models = [
        'log' => 'AcessoSistema_model',
        'temp' => 'ArquivosTemp_model',
        'users' => [
            'usuario' => 'Usuarios_model',
            'candidato' => 'RecrutamentoUsuarios_model',
            'cliente' => 'EadClientes_model'
        ]
    ];


    private $logModel;


    private $tempModel;


    private $userModel;


    private $userData;


    private $companyData;


    private $systemData;


    private $_CI;


    public function __construct($config = array())
    {
        $this->_CI = &get_instance();

        $this->_CI->load->model([self::$models['log'], self::$models['temp']]);

        $this->logModel = &$this->_CI->{self::$models['log']};
        $this->tempModel = &$this->_CI->{self::$models['temp']};

        $this->initialize($config, true);

        log_message('info', 'Auth Class Initialized');
    }


    public function initialize($config = array(), $reset = false)
    {
        if ($reset == false and $this->userModel) {
            return;
        }

        if ($this->_CI->session->has_userdata('tipo')) {

            $model = self::$models['users'][$this->_CI->session->userdata('tipo')] ?? self::$models['users']['usuario'];

            if (empty($config)) {
                $config = ['id' => $this->_CI->session->userdata('id')];
            }

        } else {

            if (empty($config)) {
                return;
            }

            $model = null;

            foreach (self::$models['users'] as $alias => $userModel) {

                require_once APPPATH . 'models/' . $userModel . '.php';

                $reflection = new ReflectionClass($userModel);

                $table = $reflection->getProperty('table');

                $table->setAccessible(true);

                $sql = $this->_CI->db->where($config)->get_compiled_select($table->getValue());

                if ($this->_CI->db->simple_query($sql)) {
                    $model = $reflection->getName();
                    break;
                }
            }

            if (empty($model)) {
                $model = self::$models['users']['usuario'];
            }

        }

        $this->_CI->load->model($model);

        $this->userModel = &$this->_CI->$model;

        $this->userData = $this->userModel->find($config, false);

        $this->companyData = $this->userModel->findEmpresa($config);

        $this->systemData = $this->userModel->find(['email' => self::$emailAdmin]);
    }


    public function encryptPassword(string $humanPassword): string
    {
        return md5(self::$salt . $humanPassword);
    }


    public function getEmailSystem(): string
    {
        return self::$emailSystem;
    }


    public function getEmailAdmin(): string
    {
        return self::$emailAdmin;
    }


    public function getEmailContact(): string
    {
        return self::$emailContact;
    }


    public function getUserModel()
    {
        return $this->userModel;
    }


    public function getUserData()
    {
        return $this->userData;
    }


    public function getCompanyData()
    {
        return $this->companyData;
    }


    public function getSystemData()
    {
        return $this->systemData;
    }


    public function setUserData($data, $reset = false)
    {
        $userData = $this->userData;

        if ($reset) {
            $this->userData = new stdClass();
        }

        foreach ($data as $field => $value) {
            if (isset($userData->$field)) {
                $this->userData->$field = $value;
            }
        }
    }


    public function setCompanyData($data, $reset = false)
    {
        $this->companyData = (object)$data;
//        $companyData = $this->companyData ?? $this->userData;
//
//        if ($reset) {
//            $this->companyData = new stdClass();
//        }
//
//        foreach ($data as $field => $value) {
//            if (isset($companyData->$field)) {
//                $this->companyData->$field = $value;
//            }
//        }
    }


    public function updateUser()
    {
        if ($this->userData) {
            $this->userModel->update($this->userData, ['id' => $this->userData->id]);

            if ($this->_CI->session->has_userdata('logado')) {
                $this->setSession();
            }
        }
    }


    public function login()
    {
        $user = $this->userData;
        $company = $this->companyData;

        if (!$user or !$company) {
            return;
        }

        $this->_CI->session->set_userdata(array(
            'id' => $user->id,
            'empresa' => $company->id,
            'cabecalho' => $company->cabecalho,
            'logomarca' => $company->foto,
            'logado' => true
        ));

        $this->setSession();

        $kcfinder = array(
            'disabled' => false,
            'uploadURL' => 'upload/' . $user->id,
            'uploadDir' => ''
        );

        if (CI_VERSION < 3) {
            session_start();
            $_SESSION['KCFINDER'] = $kcfinder;
        } else {
            $this->_CI->session->set_userdata(['KCFINDER' => $kcfinder]);
        }

        $this->_CI->session->sess_regenerate();

        $this->logModel->insert();

        $this->clearTempFiles();
    }


    private function setSession()
    {
        if ($user = $this->userData) {
            $this->_CI->session->set_userdata([
                'nome' => $user->nome,
                'tipo' => $user->tipo,
                'nivel' => $user->nivel_acesso,
                'email' => $user->email,
                'foto' => $user->foto,
                'foto_descricao' => $user->foto_descricao ?? null,
                'hash_acesso' => json_decode($user->hash_acesso, true)
            ]);
        }
    }


    public function logout()
    {
        try {
            $this->clearTempFiles();

            $this->logModel->finalize();

            if (CI_VERSION < 3) {
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_destroy();
                }
            }

            $this->_CI->session->sess_destroy();

            redirect(site_url('login'));

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    private function clearTempFiles()
    {
        if (isset($this->userData->id)) {
            $this->tempModel->delete(['usuario' => $this->userData->id]);
        }
    }


}

