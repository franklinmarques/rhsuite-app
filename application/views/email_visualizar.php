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
                            <li<?= ($this->uri->rsegment(2) == 'visualizarentrada' ? ' class="active"' : ''); ?>>
                                <a href="<?php echo site_url('email/entrada'); ?>"> <i
                                        class="fa fa-inbox"></i> Caixa de Entrada<span
                                        class="label label-danger pull-right inbox-notification nao-lidas"></span></a>
                            </li>
                            <li<?= ($this->uri->rsegment(2) == 'visualizarsaida' ? ' class="active"' : ''); ?>>
                                <a href="<?php echo site_url('email/saida'); ?>"> <i
                                        class="fa fa-send"></i>
                                    Caixa de Saída</a>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="col-sm-9">
                <section class="panel">
                    <header class="panel-heading wht-bg">
                        <h4 class="gen-case"> Visualizar Mensagem</h4>
                        <?php
                        $destinatario = $busca->row(0)->destinatario;
                        ?>
                        <a class="btn btn-default btn-sm" style="float:right; margin-top: -5%;" href="<?= site_url('email/novo') . "/$destinatario"; ?>">
                            <i class="fa fa-send"></i>
                            <span>Responder</span>
                        </a>
                    </header>
                    <div class="panel-body ">
                        <?php
                        foreach ($busca->result() as $row) {
                            ?>
                            <div class="mail-header row">
                                <div class="col-md-8">
                                    <h4 class="pull-left"><?= $row->titulo ?></h4>
                                </div>
                                <div class="col-md-4" style="display: none;">
                                    <div class="compose-btn pull-right">
                                        <a href="mail_compose.html" class="btn btn-sm btn-primary"><i
                                                class="icon-reply"></i> Reply</a>
                                        <button class="btn  btn-sm tooltips" data-original-title="Print" type="button"
                                                data-toggle="tooltip" data-placement="top" title=""><i
                                                class="icon-print"></i></button>
                                        <button class="btn btn-sm tooltips" data-original-title="Trash"
                                                data-toggle="tooltip" data-placement="top" title=""><i
                                                class="icon-trash"></i></button>
                                    </div>
                                </div>

                            </div>
                            <div class="mail-sender">
                                <div class="row">
                                    <div class="col-md-8">
                                        <img src="<?= base_url('imagens/usuarios/' . $row->foto); ?>" alt="">
                                        <strong>&nbsp;&nbsp;&nbsp;<?= $row->destinatario_mensagem; ?></strong>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="date"><?= $row->datacadastro; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="view-mail">
                                <?= $row->mensagem; ?>
                            </div>
                            <div class="attachment-mail">
                                <p>
                                    <span> <b>Anexo</b> </span>
                                </p>
                                <ul>
                                    <li>
                                        <a class="atch-thumb icon-paper-clip"
                                           href="<?= site_url('email/download/') . "/$row->anexo"; ?>">
                                               <?= $row->anexo; ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="compose-btn pull-left" style="display: none;">
                                <a href="mail_compose.html" class="btn btn-sm btn-primary"><i class="fa fa-reply"></i>
                                    Reply</a>
                                <button class="btn btn-sm "><i class="fa fa-arrow-right"></i> Forward</button>
                                <button class="btn  btn-sm tooltips" data-original-title="Print" type="button"
                                        data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-print"></i>
                                </button>
                                <button class="btn btn-sm tooltips" data-original-title="Trash" data-toggle="tooltip"
                                        data-placement="top" title=""><i class="fa fa-trash-o"></i></button>
                            </div>
                            <?php
                        }
                        ?>
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
        document.title = 'CORPORATE RH - LMS - Visualizar Mensagem';
    });
</script>
<?php
require_once "end_html.php";
?>
