<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller
{

    private static $itens = [];
    private static $names = [];
    private static $url = [];
    private static $icons = [];
    private static $pages = [];
    private static $separators = [];


    public function __construct()
    {
        echo self::${$this->session->userdata('tipo') . '()'};
    }


    public function index()
    {
        show_404();
    }


    public static function getItens($item)
    {
        echo self::$itens[$item] ?? self::$itens;
    }


    public static function getName($item)
    {
        echo self::$names[$item] ?? '';
    }


    public static function getUrl($item)
    {
        echo isset(self::$url[$item]) ? site_url(self::$url[$item]) : 'javascript:;';
    }


    public static function getIcon($item)
    {
        echo self::$icons[$item] ?? '';
    }


    public static function hasActive($item)
    {
        $class = '';
        $pages = self::$pages[$item] ?? [];
        $CI = &get_instance();
        foreach ($pages as $page) {
            if ($page == $CI->uri->rsegment()) {
                $class = 'active';
                break;
            }
        }
        echo $class;
    }


    public static function hasItens($item)
    {
        echo isset(self::$itens[$item]);
    }


    public static function hasSeparator($item)
    {
        echo isset(self::$separators[$item]) ? 'style="border-bottom: solid 1px rgba(255,255,255,0.2);"' : '';
    }


    private static function administrador()
    {
        return array(
            'inicio' => [
                'name' => 'Início',
                'url' => 'home',
                'icon' => 'fa fa-home',
                'pages' => ['home']
            ],
            'pendencias' => [
                'name' => 'Lista de Pendências',
                'url' => 'atividades',
                'icon' => 'fa fa-calendar',
                'pages' => ['atividades/*']
            ],
            'biblioteca' => [
                'name' => 'Biblioteca',
                'icon' => 'fa fa-calendar',
                'submenu' => [
                    'biblioteca1' => [
                        'name' => 'Adicionar',
                        'url' => 'novabiblioteca',
                        'separator' => false,
                        'pages' => ['novabiblioteca']
                    ],
                    'biblioteca2' => [
                        'name' => 'Gerenciar',
                        'url' => 'biblioteca',
                        'separator' => false,
                        'pages' => ['biblioteca', 'editarbiblioteca/*']
                    ]
                ]
            ],
            'empresas' => [
                'name' => 'Empresas',
                'icon' => 'fa fa-instituition',
                'submenu' => [
                    'empresas1' => [
                        'name' => 'Adicionar',
                        'url' => 'novaempresa',
                        'separator' => false,
                        'pages' => ['novaempresa']
                    ],
                    'empresas2' => [
                        'name' => 'Gerenciar',
                        'url' => 'empresas',
                        'separator' => false,
                        'pages' => ['empresas', 'editarempresa/*', 'home/cursosempresa/*']
                    ]
                ]
            ],
            'treinamentos' => [
                'name' => 'treinamentos',
                'icon' => 'fa fa-graduation-cap',
                'submenu' => [
                    'treinamentos1' => [
                        'name' => 'Adicionar treinamento',
                        'url' => 'ead/cursos/novo',
                        'separator' => false,
                        'pages' => ['novatreinamento']
                    ],
                    'treinamentos2' => [
                        'name' => 'Gerenciar treinamentos',
                        'url' => 'ead/cursos',
                        'separator' => false,
                        'pages' => ['ead/cursos/*', 'ead/pagina_curso/*']
                    ],
                    'treinamentos3' => [
                        'name' => 'Gerenciar alocação treinamentos',
                        'url' => 'ead/funcionarios',
                        'separator' => false,
                        'pages' => ['ead/funcionarios']
                    ]
                ]
            ],
            'documentos' => [
                'name' => 'Documentos',
                'icon' => 'fa fa-files-o',
                'submenu' => [
                    'documentos1' => [
                        'name' => 'Adicionar tipo',
                        'url' => 'tipo/novo',
                        'separator' => false,
                        'pages' => ['tipo/novo']
                    ],
                    'documentos2' => [
                        'name' => 'Gerenciar tipos',
                        'url' => 'tipo/gerenciar',
                        'separator' => false,
                        'pages' => ['tipo/gerenciar', 'tipo/editar/*']
                    ]
                ]
            ],
            'plataforma' => [
                'name' => 'Gestão da Plataforma',
                'icon' => 'fa fa-server',
                'submenu' => [
                    'plataforma1' => [
                        'name' => 'Backup/Restore de DBASE',
                        'url' => 'backup',
                        'separator' => false,
                        'pages' => ['backup']
                    ],
                    'plataforma2' => [
                        'name' => 'Log de usuários',
                        'url' => 'log_usuarios',
                        'separator' => false,
                        'pages' => ['log_usuarios']
                    ]
                ]
            ]
        );

    }


    private static function empresa()
    {
        $opcoes = array(
            'inicio' => array(
                'name' => 'Início',
                'url' => 'home',
                'icon' => 'fa fa-home',
                'pages' => array('home')
            ),
            'atividades' => array(
                'name' => 'Lista de Pendências',
                'url' => 'atividades',
                'icon' => 'fa fa-calendar',
                'pages' => array('atividades/*')
            ),
            'gp' => array(
                'name' => 'Gestão Operacional GP',
                'icon' => 'fa fa-users',
                'submenu' => array(
                    'gp1' => array(
                        'name' => 'Adicionar Colaborador (CLT/PJ)',
                        'url' => 'funcionario/novo',
                        'separator' => false,
                        'pages' => array('funcionario/novo')
                    ),
                    'gp2' => array(
                        'name' => 'Gerenciar Colaboradores (CLT/PJ)',
                        'url' => 'home/funcionarios1',
                        'separator' => false,
                        'pages' => array('funcionario', 'funcionario/editar')
                    )
                )
            ),
        );

        return $opcoes;
    }

}
