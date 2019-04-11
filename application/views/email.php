<?php
require_once "header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <div class="row">
            <div class="col-sm-3" style="margin: 0; padding-right: 1%;">
                <section class="panel">
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <a href="<?= site_url('email/novo/'); ?>" class="btn btn-compose">
                            Nova Mensagem
                        </a>
                        <ul class="nav nav-pills nav-stacked mail-nav">
                            <li class="active"><a href="<?php echo site_url('email/entrada'); ?>"> <i
                                        class="fa fa-inbox"></i> Caixa de Entrada<span
                                        class="label label-danger pull-right inbox-notification nao-lidas"></span></a>
                            </li>
                            <li><a href="<?php echo site_url('email/saida'); ?>"> <i
                                        class="fa fa-send"></i>
                                    Caixa de Saída</a></li>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="col-sm-9" style="margin: 0; padding-left: 0;">
                <section class="panel">
                    <div id="alert"></div>
                    <header class="panel-heading wht-bg">
                        <h4 class="gen-case">Caixa de Entrada (<span class="total">0</span>)
                            <?php echo form_open('email/getEmail/', 'data-html="script-emails" class="pull-right mail-src-position" id="busca-emails"'); ?>
                            <div class="input-append">
                                <input type="text" class="form-control " placeholder="Buscar" name="busca">
                            </div>
                            <?php echo form_close(); ?>
                        </h4>
                    </header>
                    <div class="panel-body minimal" style="padding-bottom: 0px;">
                        <div class="mail-option">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default" style="padding-bottom: 8px;">
                                    <div class="checkbox" style="margin: 0;">
                                        <label>
                                            <input type="checkbox" id="selecionaTodos">
                                        </label>
                                    </div>
                                </button>
                                <button type="button" class="btn"
                                        style="background-color: #FFF; border: 1px solid #EBEBEB;">
                                    Todos
                                </button>
                            </div>

                            <div class="btn-group">
                                <a data-original-title="Refresh" data-placement="top" data-toggle="dropdown"
                                   href="#"
                                   class="btn mini tooltips" onclick="window.location.reload();">
                                    <i class="fa fa-refresh"></i>
                                </a>
                            </div>
                            <div class="btn-group hidden-phone">
                                <a data-toggle="dropdown" href="#" class="btn mini blue">
                                    Mais &nbsp;
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu" style="padding-top: 5px; padding-bottom: 5px;">
                                    <li><a href="#" id="lerEmail"><i class="fa fa-pencil"></i> Marcar como
                                            lida</a></li>
                                    <li><a href="#" id="naoLerEmail"><i class="fa fa-flag"></i> Marcar como não
                                            lida</a>
                                    </li>
                                    <li class="divider"></li>
                                    <li><a href="#" id="excluirEmail"><i class="fa fa-trash"></i> Excluir</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="pull-right" style="display: none;">
                                <span class="text-muted"><b id="inicial">0</b>–<b id="final">0</b> de <b
                                        class="total">0</b></span>

                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-default">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                    </button>
                                    <button type="button" class="btn btn-default">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="table-inbox-wrap ">
                            <table class="table table-inbox table-hover">
                                <tbody id="html-emails">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->

        <div id="script-emails" style="display: none !important;"></div>
    </section>
</section>
<!--main content end-->
<?php
require_once "end_js.php";
?>
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Caixa de Entrada';
    });
</script>
<script>
    $('#busca-emails').submit(function () {
        ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
        return false;
    }).submit();

    $('#selecionaTodos').click(function () {
        var val = this.checked;
        $("input[name='idEmail[]']").each(function () {
            $(this).prop('checked', val);
        });
    });

    $('#excluirEmail').click(function () {
        //Variáveis
        var val = [];
        var linha = 0;

        //Percorrer Checkbox
        $("input[name='idEmail[]']:checked").each(function () {
            val[linha] = $(this).val();
            linha++;
        });

        $.ajax({
            type: "GET",
            url: "<?= base_url('email/excluirentrada'); ?>",
            data: "id=" + val, // serializes the form's elements.
            success: function (data) {
                if (data.match('Erro')) {
                    $("#alert").addClass("alert alert-danger");
                    $('#alert').html(data);
                } else {
                    $("#alert").addClass("alert alert-success");
                    $('#alert').html(data);
                    window.location.reload();
                }
            }
        });
    });

    $('#lerEmail').click(function () {
        //Variáveis
        var val = [];
        var linha = 0;

        //Percorrer Checkbox
        $("input[name='idEmail[]']:checked").each(function () {
            val[linha] = $(this).val();
            linha++;
        });

        $.ajax({
            type: "GET",
            url: "<?= base_url('email/lerMensagem'); ?>",
            data: "id=" + val, // serializes the form's elements.
            success: function (data) {
                if (data.match('Erro')) {
                    $("#alert").addClass("alert alert-danger");
                    $('#alert').html(data);
                } else {
                    $("#alert").addClass("alert alert-success");
                    $('#alert').html(data);
                    window.location.reload();
                }
            }
        });
    });

    $('#naoLerEmail').click(function () {
        //Variáveis
        var val = [];
        var linha = 0;

        //Percorrer Checkbox
        $("input[name='idEmail[]']:checked").each(function () {
            val[linha] = $(this).val();
            linha++;
        });

        $.ajax({
            type: "GET",
            url: "<?= base_url('email/naoLerMensagem'); ?>",
            data: "id=" + val, // serializes the form's elements.
            success: function (data) {
                if (data.match('Erro')) {
                    $("#alert").addClass("alert alert-danger");
                    $('#alert').html(data);
                } else {
                    $("#alert").addClass("alert alert-success");
                    $('#alert').html(data);
                    window.location.reload();
                }
            }
        });
    });
</script>
<?php
require_once "end_html.php";
?>