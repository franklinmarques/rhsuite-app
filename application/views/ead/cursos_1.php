<?php require_once APPPATH . "views/header.php"; ?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div id="alert"></div>
            <div class="col-md-12">
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-graduation-cap"></i> Gerenciar Treinamentos
                    </header>
                    <div class="panel-body">
                        <div class="col-md-10 col-lg-push-1 col-sm-1">
                            <div class="panel-group m-bot20" id="accordion">
                                <div class="well well-sm">
                                    <div class="">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="height: 1px;">
                                            <span style="padding-left: 40%; font-weight: bold;"><i class="fa fa-search"></i>&nbsp;&nbsp;&nbsp;&nbsp;Buscar</span>
                                        </a>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <?php echo form_open('ead/cursos/ajax_list', 'data-html="html-cursos" class="form-horizontal" id="busca-cursos"'); ?>
                                            <div class="form-group">
                                                <div class="col-sm-2 col-lg-3">
                                                    <?php echo form_dropdown('categoria', $categorias, '', 'class="form-control input-sm"'); ?>
                                                </div>
                                                <div class="col-sm-3 col-lg-4">
                                                    <?php echo form_dropdown('area_conhecimento', $areas_conhecimento, '', 'class="form-control input-sm"'); ?>
                                                </div>
                                                <div class="col-sm-3 col-lg-4 controls">
                                                    <input type="text" name="busca" placeholder="Palavra chave ou Tema..." class="form-control input-sm"/>
                                                </div>
                                                <div class="col-sm-3 col-lg-1">
                                                    <button type="submit" class="btn btn-primary busca"><i class="glyphicon glyphicon-search"></i></button>
                                                </div>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-content" id="html-cursos"></div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<?php require_once APPPATH . "views/end_js.php"; ?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'RhSuite - Corporate RH Tools: Gerenciar Treinamentos';
    });

    $('#busca-cursos').submit(function () {
        ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
        return false;
    }).submit();
</script>
<?php require_once APPPATH . "views/end_html.php"; ?>