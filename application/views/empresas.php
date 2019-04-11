<?php
require_once "header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-institution"></i>&nbsp;Empresas
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('home/getempresas', 'data-html="html-empresas" class="form-horizontal" id="busca-empresas"'); ?>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>

                            <div class="col-sm-6 col-lg-7 controls">
                                <input type="text" name="busca" placeholder="Buscar..."
                                       class="form-control input-sm"/>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <button type="submit" class="btn btn-primary busca"><i
                                        class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                        <?php echo form_close('<div class="box-content" id="html-empresas"></div>'); ?>
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
        document.title = 'CORPORATE RH - LMS - Gerenciar Empresas';
    });

    $('#busca-empresas').submit(function () {
        ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
        return false;
    }).submit();
</script>
<?php
require_once "end_html.php";
?>