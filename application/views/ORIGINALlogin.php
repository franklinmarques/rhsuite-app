<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">

    <title>PeopleNetCorp</title>

    <!--Core CSS -->
    <link href="<?= base_url('assets/bs3/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap-reset.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="<?= base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/style-responsive.css'); ?>" rel="stylesheet"/>

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="<?= base_url('assets/js/ie8-responsive-file-warning.js');?>"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-page">

<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <?php
            $logo_rhsuite = base_url('assets/img/Llogo-rhsuite.jpg');

            // Verifica logormaca da empresa
            if ($logoempresa) {

                // Caminho da imagem
                $logo = base_url('imagens/usuarios/' . $logo);
                $imagem_inicial = base_url('imagens/usuarios/' . $imagem_inicial);

                echo <<<HTML
                    <div class="login-wrapper">
                        <center>
                            <div class="col-md-12">
                                <img src="$logo" style="max-width: 135px; max-height: 135px; margin-bottom: 3%;">
                            </div>
                            <h3>$cabecalho</h3>
                            <hr style="border-width: 3px; border-color: #5382a5;-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.45);-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.45);box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.45);"/>
                        </center>
                    </div>
HTML;
            } // Se não existir coloca espaços
            else {
                $logo = base_url('assets/img/socialnetwork.png');
                echo <<<HTML
                    <br /><br /><br />
                    <div class="login-wrapper">
                        <center>
                            <p><img src="$logo_rhsuite" alt="RHSuite" style="max-width: 135px; max-height: 135px; margin-bottom: 3%;">
                            <hr style="border-width: 3px; border-color: #5382a5;-webkit-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.45);-moz-box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.45);box-shadow: 0px 0px 7px 0px rgba(50, 50, 50, 0.45);"/>
                        </center>
                    </div>
HTML;
            } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="login-wrapper">
                <!-- BEGIN alert -->
                <div id="alert" style="margin: 10px auto;"></div>
                <!-- END alert -->
                <!-- BEGIN Login Form -->
                <?php echo form_open('home/autenticacao_json', 'data-aviso="alert" id="form-login" class="ajax-simple"'); ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4>Entre na sua conta</h4>
                    </div>
                    <hr/>
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
                                <input type="password" name="senha" placeholder="Senha" class="form-control" autocomplete="new-password"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary form-control">Entrar</button>
                            </div>
                        </div>
                        <!-- END Login Form -->
                        <hr/>
                        <p class="clearfix">
                            <a href="#" class="goto-forgot pull-left">Esqueceu a senha?</a>
                        </p>
                    </div>
                </div>
                <div><p style="text-align: center; margin-top: -2%;">Copyright &copy; PeopleNet In Education | 2014 - <?= date('Y');?></p></div>
                </form>

                <!-- BEGIN Forgot Password Form -->
                <?php echo form_open('home/recuperarsenha_json', 'data-aviso="alert" id="form-forgot" class="ajax-simple" style="display:none"'); ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3>Recupere sua senha</h3>
                    </div>
                    <hr/>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="controls">
                                <input type="text" name="email" placeholder="E-mail" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary form-control">Recuperar</button>
                            </div>
                        </div>
                        <hr/>
                        <p class="clearfix">
                            <a href="#" class="goto-login pull-left">← Voltar</a>
                        </p>
                    </div>
                    </form>

                    <!-- BEGIN Forgot Password Form -->
                    <?php echo form_open('home/recuperarsenha_json', 'data-aviso="alert" id="form-forgot" class="ajax-simple" style="display:none"'); ?>
                    id="form-forgot" class="ajax-simple" method="post" accept-charset="utf-8">
                    <div style="display:none">
                        <input type="hidden" name="edunet_token" value="231cc51ae4d5fa70b830bc0e97dbc5d5">
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3>Recupere sua senha</h3>
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
                                    <button type="submit" class="btn btn-primary form-control">Recuperar</button>
                                </div>
                            </div>
                            <hr>
                            <p class="clearfix">
                                <a href="#" class="goto-login pull-left">← Voltar</a>
                            </p>
                        </div>

                        <!-- END Forgot Password Form -->

                    </div>
                    </form>
                    <!-- END Main Content -->

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="<?= base_url('assets/js/jquery.js'); ?>"></script>
<script src="<?= base_url('assets/bs3/js/bootstrap.min.js'); ?>"></script>

<script src="<?php echo base_url("assets/js/ajax/ajax.simple.js"); ?>"></script>

<script>
    function goToForm(form) {
        $('.login-wrapper > form:visible').fadeOut(500, function () {
            $('#form-' + form).fadeIn(500);
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
