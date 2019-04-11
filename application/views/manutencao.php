<?php
require_once "header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="<?php echo site_url('home'); ?>">Início</a>
                    <span class="divider"><i class="icon-angle-right"></i></span>
                </li>
                <li class="active">Página em manutenção</li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-wrench"></i> Módulo não habilitado
                    </header>
                    <div class="panel-body">
                        <div class="col-md-4"><img src="<?= base_url('assets/img/manutencao.jpg'); ?>" class="img-responsive"></div>
                        <div class="col-md-5">
                            <h4 class="control-label" style="text-align: justify;">
                                Este módulo se encontra desabilitado! <br/>
                                Para maiores informações sobre o mesmo,
                                utilize o fale conosco ou envie uma mensagem
                                para contato@rhsuite.com.br
                            </h4>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<?php
require_once "end_js.php";
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Página em Manutenção';
    });
</script>
<?php
require_once "end_html.php";
?>
