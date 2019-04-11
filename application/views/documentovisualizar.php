<?php require_once "header.php"; ?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-file-text-alt"></i> Visualização de documento - <?= $arquivos->descricao ?>
                        <a class="btn btn-default btn-sm" onclick="javascript:history.back()" style="float: right; margin-top: -0.6%;">
                            <i class="fa fa-reply"></i> &nbsp;&nbsp; Voltar 
                        </a>
                    </header>
                    <div class="panel-body" style="height:100%;">
                        <iframe src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/documentos/' . ($arquivos->categoria === '2' ? 'organizacao/' : 'colaborador/') . convert_accented_characters($arquivos->arquivo)); ?>"
                                style="width:100%; height:600px; margin:0;" frameborder="0"></iframe>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<?php require_once "end_js.php"; ?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Visualização de documento - <?php echo $arquivos->descricao; ?>';
    });
</script>

<?php require_once "end_html.php"; ?>
