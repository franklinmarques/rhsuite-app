<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">

    <title>CORPORATE RH - LMS</title>

    <!--Core CSS -->
    <link href="<?= base_url("assets/bs3/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <!--<link href="<?php //echo base_url("assets/css/bootstrap-reset.css");       ?>" rel="stylesheet">-->
    <link href="<?= base_url("assets/bs3/fonts/glyphicons-pro.css"); ?>" rel="stylesheet"/>
    <link href="<?= base_url("assets/font-awesome/css/font-awesome.css"); ?>" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="<?= base_url("assets/css/style.css"); ?>" rel="stylesheet">
    <link href="<?= base_url("assets/css/style-responsive.css"); ?>" rel="stylesheet"/>

    <!--clock css-->
    <!--<link href="<?php // base_url("assets/js/css3clock/css/style.css");       ?>" rel="stylesheet">-->

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <?php if ($this->uri->rsegment(1) != 'home'): ?>
        <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <?php else: ?>
        <script src="<?= base_url("assets/js/jquery-1.10.2.js"); ?>"></script>
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-ui/jquery-ui-1.10.1.custom.min.css"); ?>"/>
    <script src="<?php echo base_url("assets/js/jquery-ui/jquery-ui-1.10.1.custom.min.js"); ?>"></script>
    <style>
        :-webkit-full-screen, :fullscreen, :-ms-fullscreen, :-moz-full-screen {
            position: fixed !important;
            width: 100%;
            height: 100%;
            top: 0;
            background-color: #fff;
        }
    </style>
</head>

<body>

<section id="container-fluid">
<?php
require_once "navbar.php";

switch ($this->session->userdata('tipo')) {
    case 'administrador':
        require_once "menu_administrador.php";
        break;
    case 'empresa':
        require_once "menu_empresa.php";
        break;
    case 'funcionario':
        require_once "menu_funcionario.php";
        break;
    case 'cliente':
//        require_once "menu_cliente.php";
        break;
    case 'candidato':
    case 'selecionador':
        require_once "menu_recrutamento.php";
        break;
    default:
        require_once "menu.php";
}

?>