<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>CORPORATE RH - LMS - Alterar Senha</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!--base css styles-->
        <link rel="stylesheet" href="<?php echo base_url('assets/assets/bootstrap/css/bootstrap.min.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/assets/font-awesome/css/font-awesome.min.css'); ?>">

        <!--page specific css styles-->

        <!--flaty css styles-->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/flaty.css'); ?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/flaty-responsive.css'); ?>">

        <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico'); ?>">
        <style>
            .login-page:before, .error-page:before, #main-content { background: none repeat scroll 0% 0% #fff; }
        </style>
    </head>
    <body class="login-page">

        <!-- BEGIN Main Content -->
        <div class="login-wrapper">
            <!-- BEGIN alert -->
            <div id="alert" style="width: 340px; margin: 10px auto;"></div>
            <!-- END alert -->

            <!-- BEGIN Login Form -->
            <?php echo form_open('home/alterarsenha_json/' . $token, 'data-aviso="alert" class="ajax-simple"'); ?>
            <h3>Alterar senha</h3>
            <hr/>
            <center>
                <h4><?php echo $nome; ?></h4>
            </center>
            <hr/>
            <div class="form-group">
                <div class="controls">
                    <input type="password" name="novasenha" placeholder="Nova Senha" class="form-control" autocomplete="new-password"/>
                </div>
            </div>
            <div class="form-group">
                <div class="controls">
                    <input type="password" name="confirmarsenha" placeholder="Confirmar Senha" class="form-control" autocomplete="new-password"/>
                </div>
            </div>
            <div class="form-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary form-control">Alterar</button>
                </div>
            </div>
            <hr/>
            <?php echo form_close(); ?>
            <!-- END Login Form -->

        </div>
        <!-- END Main Content -->

        <!--basic scripts-->
        <script>window.jQuery || document.write('<script src="<?php echo base_url('assets/assets/jquery/jquery-2.0.3.min.js'); ?>"><\/script>')</script>
        <script src="<?php echo base_url('assets/assets/bootstrap/js/bootstrap.min.js'); ?>"></script>
        <script src="<?php echo base_url("assets/js/ajax.simple.js"); ?>"></script>
    </body>
</html>
