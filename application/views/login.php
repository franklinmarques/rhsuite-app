<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">

    <title>Rhsuite - Ferramentas Para RH</title>

    <!--Core CSS -->
    <link href="<?= base_url('assets/bs3/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap-reset.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/style-responsive.css'); ?>" rel="stylesheet"/>

    <!--Core js-->
    <script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
    <script src="<?= base_url('assets/bs3/js/bootstrap.min.js'); ?>"></script>
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="<?= base_url('assets/js/ie8-responsive-file-warning.js'); ?>"></script>
    <![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <?php if (!empty($imagem_fundo)): ?>
        <style>
            .login-page {
                background-image: url(<?= '../imagens/usuarios/' . $imagem_fundo ?>);
            }
        </style>
    <?php endif; ?>
</head>

<body class="login-page">
<div id="cookie" class="text-danger text-center" style="background-color: #ffe; display: none;">
    Este site usa Cookies! Habilite o uso de cookies em seu navegador para o correto funcionamento do site.
</div>
<div class="container">
    <?php
    if ($logoempresa) {
        $logo = base_url('imagens/usuarios/' . $logo);
        $hr = '<hr style="margin-top:10px; margin-bottom:10px;"/>';
    } else {
        $logo = base_url('assets/img/Llogo-rhsuite.jpg');
        $cabecalho = '';
        $hr = '';
    }
    ?>
    <div style="width: 100%; max-width: 370px; margin: 0 auto;">
        <div align="center">
            <img src="<?php echo $logo; ?>" style="width: auto; max-height: 100px; margin-bottom: 3%;">
            <h4 style="color: #111343; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">
                <strong><?php echo $cabecalho; ?></strong></h4>
        </div>
    </div>
    <div class="login-wrapper">
        <!-- BEGIN alert -->
        <div id="alert" style="margin: 10px auto;"></div>
        <!-- END alert -->
        <!-- BEGIN Login Form -->
        <?php echo form_open('login/autenticacao_json', 'data-aviso="alert" id="form-login" class="ajax-simple"'); ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4> Entre na sua conta</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" name="email" placeholder="E-mail" class="form-control" autofocus=""/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" name="senha" placeholder="Senha" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary form-control">Entrar</button>
                    </div>
                </div>
                <!-- END Login Form -->
                <hr style="margin-top: 10px; margin-bottom: 10px;"/>
                <p class="clearfix">
                    <a href="#" class="goto-forgot pull-left" style="color: #111343;">Esqueceu a senha?</a>
                </p>
            </div>
        </div>
        <?php echo form_close(); ?>

        <!-- BEGIN Forgot Password Form -->
        <?php echo form_open('login/recuperarsenha_json', 'data-aviso="alert" id="form-forgot" class="ajax-simple" style="display:none"'); ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>Recupere sua senha</h4>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input type="text" name="email" placeholder="E-mail" class="form-control"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary form-control">Recuperar</button>
                    </div>
                </div>
                <hr>
                <p class="clearfix">
                    <a href="#" class="goto-login pull-left" style="color: #111343;"><i
                                class="fa fa-long-arrow-left"></i> Voltar</a>
                </p>
            </div>
        </div>
        <?php echo form_close(); ?>

        <!-- BEGIN Forgot Password Form -->
        <?php echo form_open('home/recuperarsenha_json', 'data-aviso="alert" id="form-forgot" class="ajax-simple" style="display:none"'); ?>
        <div style="display:none">
            <input type="hidden" name="edunet_token" value="231cc51ae4d5fa70b830bc0e97dbc5d5">
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>Recupere sua senha</h4>
            </div>
            <hr>
            <div class="panel-body">
                <div class="form-group">
                    <div class="controls">
                        <input type="text" name="email" placeholder="E-mail" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="controls">
                        <button type="button" class="btn btn-default form-control">Voltar</button>
                    </div>
                    <div class="controls">
                        <button type="submit" class="btn btn-primary form-control">Recuperar</button>
                    </div>
                </div>
                <hr>
                <p class="clearfix">
                    <a href="#" class="goto-login pull-left"><i class="fa fa-long-arrow-left"></i> Voltar</a>
                </p>
            </div>

            <!-- END Forgot Password Form -->

        </div>
        <?php echo form_close(); ?>
        <button type="button" class="btn btn-primary form-control" style="box-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">
            Consultar vagas | Cadastrar currículo
        </button>

        <!-- END Main Content -->

    </div>
</div>
<footer class="footer">
    <p style="text-align: center; color: #151860; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">Copyright &copy;
        PeopleNet In
        Education<br>
        <a href="mailto:contato@rhsuite.com.br" style="color: #151860;">contato@rhsuite.com.br</a> | <a
                href="mailto:contato@multirh.com.br" style="color: #151860;">contato@multirh.com.br</a>
    </p>
</footer>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="<?php echo base_url("assets/js/ajax/ajax.simple.js"); ?>"></script>

<script>
    var tmpcookie = new Date();
    chkcookie = (tmpcookie.getTime() + '');
    document.cookie = "chkcookie=" + chkcookie + "; path=/";

    if (document.cookie.indexOf(chkcookie, 0) < 0) {
        $('#cookie').show();
    } else {
        $('#cookie').hide();
    }

    function goToForm(form) {
        $('#alert').slideUp(400, function () {
            $('#alert').html('').hide();
            $('.login-wrapper > form:visible').fadeOut(500, function () {
                $('#form-' + form).fadeIn(500);
            });
        });
    }

    $(function () {
        $('.goto-login').click(function () {
            goToForm('login');
        });
        $('.goto-forgot').click(function () {
            goToForm('forgot');
        });
        $('.goto-register').click(function () {
            goToForm('register');
        });
    });
</script>

</body>
</html>
