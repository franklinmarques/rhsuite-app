<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/favipn.ico'); ?>">
    <title>Rhsuite - Alterar Senha do Candidato</title>
    <!--Core CSS -->
    <link href="<?php echo base_url('assets/bs3/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/bootstrap-reset.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style-responsive.css'); ?>" rel="stylesheet"/>
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <style>

        body {
            background-image: url('<?= base_url($imagem_fundo ? 'imagens/usuarios/' . $imagem_fundo : 'assets/images/fdmrh3.jpg') ?>');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
            background-attachment: fixed;
        }

        body .container {
            padding: 10px !important;
            min-height: calc(100% - 40px);
        }

        .btn-primary {
            background-color: #337ab7 !important;
            border-color: #2e6da4 !important;
            color: #fff;
        }

        .btn-default: {
            background-color: #fff !important;
            border-color: #ccc !important
            color: #333 !important;
        }

        label.control-label {
            font-weight: bold;
        }

        .panel {
            background-color: rgba(255, 255, 255, 0.81);
        }

    </style>
</head>

<body>

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

    <div class="panel">
        <div class="panel-header">
            <br>
            <div align="center">
                <img src="<?php echo $logo; ?>" style="width: auto; max-height: 100px; margin-bottom: 3%;">
                <h4 style="color: #111343; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">
                    <strong><?php echo $cabecalho; ?></strong></h4>
            </div>
            <div align="center">
                <h3>Alteração de senha</h3>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div id="alert" style="margin: 10px auto;"></div>
                        </div>
                    </div>
                    <?php echo form_open('vagas/salvarSenhaCandidato', 'data-aviso="alert" class="ajax-simple"'); ?>
                    <input type="hidden" name="token" value="<?= $token; ?>">
                    <div class="row form-group">
                        <label class="control-label col-md-2 col-md-offset-2">Nova senha</label>
                        <div class="col-md-4">
                            <input name="nova_senha" class="form-control" type="password" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="control-label col-md-2 col-md-offset-2">Confirmar nova senha</label>
                        <div class="col-md-4">
                            <input name="confirmar_nova_senha" class="form-control" type="password" autocomplete="new-password">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn"
                                    style="width: 250px; color: #fff; background-color: #111343;">Alterar
                                e entrar no portal
                            </button>
                        </div>
                    </div>
                    <br>
                    <br>
                    <?php form_close(); ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!--main content end-->

<footer class="footer">
    <p style="text-align: center; color: #151860; text-shadow: 1px 2px 4px rgba(0, 0, 0, .15);">Copyright &copy;
        PeopleNet In Education<br>
        <a href="mailto:contato@rhsuite.com.br" style="color: #151860;">contato@rhsuite.com.br</a> | <a
                href="mailto:contato@multirh.com.br" style="color: #151860;">contato@multirh.com.br</a>
    </p>
</footer>

<!-- Placed js at the end of the document so the pages load faster -->
<!--Core js-->
<script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>
<script src="<?php echo base_url('assets/bs3/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url("assets/js/ajax/ajax.simple.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/scripts.js"); ?>"></script>

</body>
</html>
