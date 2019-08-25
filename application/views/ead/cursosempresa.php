<?php
require_once APPPATH . 'views/header.php';
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-graduation-cap"></i> Treinamentos - <?php echo $row->nome; ?>
                        <a class="btn btn-success btn-sm" style="float:right;border-radius: 20px !important; margin-top: -0.5%;" href="<?php echo site_url('ead/cursos/novo/' . $this->uri->rsegment(3)); ?>"><i class="fa fa-plus"></i> Adicionar</a>
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('ead/cursos/ajax_list/' . $row->id, 'data-html="html-cursos-empresa" class="form-horizontal" style="margin-top: 15px;" id="busca-cursos-empresa"'); ?>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Busca</label>
                            <div class="col-sm-6 col-lg-7 controls">
                                <input type="text" name="busca" placeholder="Buscar..." class="form-control input-sm" />
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                        <?php echo form_close('<div class="box-content" id="html-cursos-empresa"></div>'); ?>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<?php
require_once APPPATH . 'views/end_js.php';
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'LMS - Gwdscfvgbhnjmk,erenciar Treinamentos da Empresa - <?php echo $row->nome; ?>';
    });

    $('#busca-cursos-empresa').submit(function () {
        ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
        return false;
    }).submit();
</script>
<?php
require_once APPPATH . 'views/end_html.php';
?>