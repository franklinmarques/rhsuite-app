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
                        <i class="fa fa-file-text-o"></i> Páginas do Treinamento - <?php echo $row->curso; ?>
                        <div style="float:right; margin-top: -0.5%;">
                            <a class="btn btn-success btn-sm" style="border-radius: 20px !important;" href="<?php echo site_url('home/novapaginacurso/' . $this->uri->rsegment(3)); ?>">
                                <i class="fa fa-plus"></i>
                                <span>Adicionar página</span>
                            </a>
                            <a class="btn btn-info btn-sm" style="border-radius: 20px !important;" target="_blank" href="<?php echo site_url('home/acessarcurso/' . $this->uri->rsegment(3)); ?>">
                                <i class="glyphicon glyphicon-eye-open"></i>
                                <span>Visualizar treinamento</span>
                            </a>
                        </div>
                    </header>
                    <div class="panel-body">
                        <?php echo form_open('home/getpaginascurso/' . $row->id, 'data-html="html-paginas-curso" class="form-horizontal" style="margin-top: 15px;" id="busca-paginas-curso"'); ?>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>

                            <div class="col-sm-6 col-lg-7 controls">
                                <input type="text" name="busca" placeholder="Buscar..."
                                       class="form-control input-sm"/>
                            </div>
                            <div class="col-sm-3 col-lg-3">
                                <button type="submit" class="btn btn-primary"><i
                                        class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                        <?php echo form_close('<div class="box-content" id="html-paginas-curso"></div>'); ?>
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
<script src="<?php echo base_url('assets/js/tablednd/jquery.tablednd.js'); ?>"></script>
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar Páginas do Treinamento - <?php echo $row->curso; ?>';
    });

    $('#busca-paginas-curso').submit(function () {
        ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
        return false;
    }).submit();
</script>

<?php
require_once "end_html.php";
?>