<?php

class MY_Hook
{

    private static $subdomains = [];


    private static function initialize()
    {
        self::$subdomains = [
            'ame',
            'ilitera',
            'rhsuite',
            'pherfil',
            'sigaconsult',
            'saurus'
        ];
    }

    public static function getSubdomain(): string
    {
        $php_self = strstr($_SERVER['PHP_SELF'], 'index.php');

        $domain = str_replace('index.php/', '', $php_self);

        $subdomain = strstr($domain, '/', true);

        self::initialize();

        if (!in_array($subdomain, self::$subdomains)) {
            return '';
        }

        return $subdomain;
    }


    public static function setSubdomain()
    {
        $config = new CI_Config();

        $config->set_item('index_page', self::getSubdomain());

        unset($config);
    }


}