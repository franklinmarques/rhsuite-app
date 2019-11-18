<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">

    <title>CORPORATE RH - LMS</title>

    <!--Core CSS -->
    <link href="<?= base_url("assets/bs3/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <!--<link href="<?php //echo base_url("assets/css/bootstrap-reset.css");                                                                                                                                                           ?>" rel="stylesheet">-->
    <link href="<?= base_url("assets/bs3/fonts/glyphicons-pro.css"); ?>" rel="stylesheet"/>
    <link href="<?= base_url("assets/font-awesome/css/font-awesome.css"); ?>" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="<?= base_url("assets/css/style.css"); ?>" rel="stylesheet">
    <link href="<?= base_url("assets/css/style-responsive.css"); ?>" rel="stylesheet"/>

    <!--clock css-->
    <!--<link href="<?php // base_url("assets/js/css3clock/css/style.css");                                                                                                                                                           ?>" rel="stylesheet">-->

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>

    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-ui/jquery-ui-1.10.1.custom.min.css"); ?>"/>
    <script src="<?php echo base_url("assets/js/jquery-ui/jquery-ui-1.10.1.custom.min.js"); ?>"></script>
    <script src="<?php echo base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>
</head>

<body>
<section id="container-fluid">
    <!--main content start-->

    <section id="main-content" class="merge-left">
        <section class="wrapper" style="margin-top: 0px;">

            <!-- page start-->
            <div class="row">
                <div class="col-md-2 hidden-xs hidden-sm" style="padding-right: 1px;">
                    <div class="panel" style="border-color: #111343">
                        <div class="panel-heading text-center" style="background: #111343 !important;">
                            <h3 class="panel-title">Plano de Aprendizagem</h3>
                        </div>
                        <div class="panel-body" style="overflow-y: auto; max-height: 600px; font-size: 12px;">
                            <?php foreach ($paginas as $k => $pagina): ?>
                                <p style="margin: 0 0 5px; overflow:hidden; text-overflow:ellipsis; white-space: nowrap;<?= $pagina->id === $paginaatual->id ? 'background: #758FB0; color: #fff' : '' ?>">
                                    <?php if (($curso->progressao_linear and $pagina->acesso == 0) or $pagina->acesso == 0): ?>

                                        <span class="glyphicon glyphicon-ok" style="color: rgba(255,255,255,0);"></span>
                                        <a style="cursor: default;">
                                            <span style="color: #555;"><?= $pagina->titulo; ?></span>
                                        </a>

                                    <?php else: ?>

                                        <?php if ($pagina->status || $pagina->ordem == 0): ?>
                                            <span class="glyphicon glyphicon-ok text-success"></span>
                                        <?php else: ?>
                                            <span class="glyphicon glyphicon-ok"
                                                  style="color: rgba(255,255,255,0);"></span>
                                        <?php endif; ?>
                                        <a href="<?php echo site_url('ead/treinamento_cliente/acessar/' . $curso->id_curso_usuario . '/' . $pagina->ordem); ?>"
                                           title="<?= $pagina->titulo; ?>">
                                            <?php if ($pagina->id === $paginaatual->id): ?>
                                                <strong style="color: #fff;"><?= $pagina->titulo; ?></strong>
                                            <?php else: ?>
                                                <span style="color: #000080;"><?= $pagina->titulo; ?></span>
                                            <?php endif; ?>
                                        </a>

                                    <?php endif; ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-10" style="padding-left: 10px;">
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-file-text-o"></i><span
                                    class="hidden-xs hidden-sm"> <?php echo $paginaatual->titulo; ?></span>
                            <div style="float:right; margin-top: -5px;">

                                <?php if ($paginaatual->audio): ?>
                                    <button id="audio" data-toggle="popover" class="btn btn-primary btn-sm"
                                            style="padding: 1px 6px; font-size: 16px;">
                                        <i class="glyphicons glyphicons-volume_up" style="top: 2px;"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-primary btn-sm disabled"
                                            style="padding: 1px 6px; font-size: 16px;">
                                        <i class="glyphicons glyphicons-mute" style="top: 2px;"></i>
                                    </button>
                                <?php endif; ?>
                                <button id="diminuir_zoom" class="btn btn-primary btn-sm"
                                        style="padding: 1px 6px; font-size: 16px;">
                                    A-
                                </button>
                                <button id="aumentar_zoom" class="btn btn-primary btn-sm"
                                        style="padding: 1px 6px; font-size: 16px;">
                                    A+
                                </button>
                                &nbsp;
                                <?php if ($paginaatual->anterior > $curso->pagina_inicial): ?>
                                    <a class="btn btn-primary btn-sm" style="padding: 1px 6px; font-size: 16px;"
                                       href="<?php echo site_url('ead/treinamento_cliente/acessar/' . $this->uri->rsegment(3) . '/' . $paginaatual->anterior); ?>">
                                        <i class="glyphicon glyphicon-arrow-left"></i><span class="hidden-xs hidden-sm"> Anterior</span>
                                    </a>
                                <?php else: ?>
                                    <a class="btn btn-primary btn-sm" style="padding: 1px 6px; font-size: 16px;"
                                       href="#" disabled="">
                                        <i class="glyphicon glyphicon-arrow-left"></i><span class="hidden-xs hidden-sm"> Anterior</span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($paginaatual->ordem == $curso->pagina_final || ($curso->progressao_linear && $paginaatual->status == 0) or $paginaatual->status == 0): ?>
                                    <a class="btn btn-primary btn-sm" style="padding: 1px 6px; font-size: 16px;"
                                       href="#" disabled="">
                                        <span class="hidden-xs hidden-sm">Próximo </span><i
                                                class="glyphicon glyphicon-arrow-right"></i>
                                    </a>
                                <?php else: ?>
                                    <a class="btn btn-primary btn-sm" style="padding: 1px 6px; font-size: 16px;"
                                       href="<?php echo site_url('ead/treinamento_cliente/acessar/' . $this->uri->rsegment(3) . '/' . $paginaatual->proxima); ?>">
                                        <span class="hidden-xs hidden-sm">Próximo </span><i
                                                class="glyphicon glyphicon-arrow-right"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if ($paginaatual->status == 0): ?>
                                    <?php if (($paginaatual->modulo == 'atividades' || $paginaatual->modulo == 'quiz') && $paginaatual->resultado == 0): ?>
                                        <button id="finalizarAula" style="padding: 1px 6px; font-size: 16px;"
                                                class="btn btn-warning btn-sm"
                                                onclick="modalInformacao();">
                                            <i class="fa fa-check"></i><span
                                                    class="hidden-xs hidden-sm">Finalizar tópico</span>
                                        </button>
                                    <?php else: ?>
                                        <button id="finalizarAula" style="padding: 1px 6px; font-size: 16px;"
                                                class="btn btn-success btn-sm"
                                                onclick="finalizaPagina(<?= $paginaatual->id_curso . "," . $paginaatual->id; ?>)">
                                            <i class="fa fa-check"></i><span
                                                    class="hidden-xs hidden-sm">Finalizar tópico</span>
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button class="btn btn-success btn-sm" style="padding: 1px 6px; font-size: 16px;"
                                            disabled>
                                        <i class="fa fa-check"></i><span
                                                class="hidden-xs hidden-sm"> Finalizar tópico</span>
                                    </button>
                                <?php endif; ?>&nbsp;
                                <?php if ($this->agent->is_mobile()): ?>
                                    <button class="btn btn-default btn-sm" style="padding: 1px 7px; font-size: 16px;"
                                            onclick="window.close();">
                                        <i class="fa fa-times"></i>
                                    </button>
                                <?php endif; ?>
                                <a id="fullscreen" data-toggle="dropdown"
                                   class="btn btn-primary btn-sm"
                                   href="javascript:void(0)" title="Tela cheia"
                                   style="padding: 1px 6px; font-size: 16px;">
                                    <span class="glyphicons glyphicons-fullscreen" style="top: 2px;"></span>
                                </a>
                            </div>
                        </header>

                        <div class="panel-body">
                            <div id="alert"></div>
                            <?php switch ($paginaatual->modulo): case 'ckeditor': ?>

                                <?php echo $paginaatual->conteudo; ?>
                                <?php break; ?>

                            <?php case 'pdf': ?>
                                <?php if ($paginaatual->pdf): ?>
                                    <iframe src="https://docs.google.com/gview?embedded=true&url=<?php echo base_url('arquivos/pdf/' . $paginaatual->pdf); ?>"
                                            style="width: 100%; height: calc(100vh - 86px);" frameborder="0"
                                            allowfullscreen></iframe>
                                <?php else: ?>
                                    <iframe src="" style="width: 100%; height: calc(100vh - 86px);" frameborder="0"
                                            allowfullscreen></iframe>
                                <?php endif; ?>
                                <?php break; ?>

                            <?php case 'quiz': ?>

                            <?php case 'atividades': ?>

                                <?php echo form_open('ead/treinamento_cliente/avaliar_atividade/', 'data-aviso="alert" class="ajax-simple" id="respostaAtividades"'); ?>
                                <input type="hidden" value="<?= $paginaatual->id_curso; ?>" name="id_curso">
                                <input type="hidden" value="<?= $paginaatual->id; ?>" name="id_pagina">

                                <?php if ($paginaatual->status == 0 && $paginaatual->resultado == 0): ?>
                                    <button class="btn btn-success enviaResposta<?= $perguntas ? '' : ' disabled' ?>"
                                            type="button"><?= count($perguntas) > 1 ? 'Enviar respostas' : 'Enviar resposta' ?></button>
                                    <br>
                                    <br>
                                <?php endif; ?>
                                <?php foreach ($perguntas as $pergunta): ?>
                                    <input type="hidden" value="<?= $pergunta->tipo; ?>"
                                           name="tipo[<?= $pergunta->id; ?>]">
                                    <div class="well">
                                        <?= $pergunta->conteudo; ?>
                                        <?php if ($pergunta->tipo == 2): ?>
                                            <div class="form-group">
                                                            <textarea name="pergunta[<?= $pergunta->id; ?>]"
                                                                      class="form-control"
                                                                      rows="3"><?= $pergunta->resposta ?></textarea>
                                            </div>
                                            <?php if (strlen($pergunta->feedback_correta) or strlen($pergunta->feedback_incorreta)): ?>
                                                <div class="form-group">
                                                    <a href="#" class="btn btn-success btn-xs dissertativa"
                                                       data-pergunta="<?= $pergunta->id; ?>">
                                                        <i class="glyphicon glyphfa fa-eye-open"></i> Visualizar
                                                        Resposta
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            <div id="alternativa-dissertativa-<?= $pergunta->id; ?>"
                                                 class="alert alert-success" style="display: none;">
                                                <?= $pergunta->feedback_correta; ?>
                                            </div>
                                        <?php else: ?>
                                            <ul class="list-unstyled">
                                                <?php foreach ($pergunta->alternativas as $alternativa): ?>
                                                    <li>
                                                        <label style="font-weight: normal">
                                                            <input type="radio" name="pergunta[<?= $pergunta->id; ?>]"
                                                                   class="alternativa" value="<?= $alternativa->id; ?>"
                                                                   data-pergunta="<?= $pergunta->id; ?>"
                                                                   data-peso="<?= $alternativa->peso; ?>"<?= $alternativa->checked ? ' checked=""' : ''; ?>>
                                                            <?= $alternativa->alternativa; ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <div id="alternativa-correta-<?= $pergunta->id; ?>"
                                                 class="alert alert-success" style="display: none;">
                                                <?= $pergunta->feedback_correta; ?>
                                            </div>
                                            <div id="alternativa-errada-<?= $pergunta->id; ?>"
                                                 class="alert alert-danger" style="display: none;">
                                                <?= $pergunta->feedback_incorreta; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                                <?php if ($paginaatual->status == 0 && $paginaatual->resultado == 0): ?>
                                    <button class="btn btn-success enviaResposta<?= $perguntas ? '' : ' disabled' ?>"
                                            type="button"><?= count($perguntas) > 1 ? 'Enviar respostas' : 'Enviar resposta' ?></button>
                                <?php endif; ?>
                                <?php echo form_close(); ?>
                                <?php break; ?>

                            <?php case 'url': ?>

                                <?php if (!empty($paginaatual->url)): ?>
                                    <div class="col-md-12" style="margin: 0; padding: 0;">
                                        <div class="col-md-8" style="margin: 0; padding: 0;">
                                            <iframe type="text/html" allowfullscreen
                                                    style="width: 100%; height: calc(100vh - 86px);"
                                                    src="<?php echo $url_final; ?>" frameborder="0"></iframe>
                                        </div>
                                        <div class="col-md-4">
                                            <?php echo $paginaatual->conteudo; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="col-md-12">
                                        <div class="col-md-8">
                                            <source src="<?php echo base_url('arquivos/videos/' . $paginaatual->arquivo_video); ?>"
                                                    type="video/mp4">
                                        </div>
                                        <div class="col-md-4">
                                            <?php echo $paginaatual->conteudo; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php break; ?>

                            <?php case 'mapas': ?>
                            <?php case 'simuladores': ?>
                            <?php case 'aula-digital': ?>
                            <?php case 'jogos': ?>
                            <?php case 'livros-digitais': ?>
                            <?php case 'infograficos': ?>
                            <?php case 'experimentos': ?>
                            <?php case 'softwares': ?>
                            <?php case 'audios': ?>
                            <?php case 'multimidia': ?>
                            <?php case 'links-externos': ?>

                                <iframe style="width: 100%; height: calc(100vh - 86px);" frameborder="0" allowfullscreen
                                        src="<?php echo $biblioteca->link; ?>"
                                        onload="javascript:resizeIframe(this);"></iframe>

                            <?php endswitch ?>
                        </div>
                    </section>
                </div>
            </div>

            <div class="col-xs-12 hidden-md hidden-lg">
                <div class="panel" style="border-color: #111343">
                    <div class="panel-heading text-center" style="background: #111343 !important;">
                        <h3 class="panel-title">Plano de Aprendizagem</h3>
                    </div>
                    <div class="panel-body" style="overflow-y: auto; max-height: 600px; font-size: 12px;">
                        <?php foreach ($paginas as $k => $pagina): ?>
                            <p style="margin: 0 0 5px; overflow:hidden; text-overflow:ellipsis; white-space: nowrap;<?= $pagina->id === $paginaatual->id ? 'background: #758FB0; color: #fff' : '' ?>">
                                <?php if ($curso->progressao_linear && $pagina->acesso == 0): ?>

                                    <span class="glyphicon glyphicon-ok" style="color: rgba(255,255,255,0);"></span>
                                    <a style="cursor: default;">
                                        <span style="color: #555;"><?= $pagina->titulo; ?></span>
                                    </a>

                                <?php else: ?>

                                    <?php if ($pagina->status || $pagina->ordem == 0): ?>
                                        <span class="glyphicon glyphicon-ok text-success"></span>
                                    <?php else: ?>
                                        <span class="glyphicon glyphicon-ok"
                                              style="color: rgba(255,255,255,0);"></span>
                                    <?php endif; ?>
                                    <a href="<?php echo site_url('ead/treinamento_cliente/acessar/' . $curso->id_curso_usuario . '/' . $pagina->ordem); ?>"
                                       title="<?= $pagina->titulo; ?>">
                                        <?php if ($pagina->id === $paginaatual->id): ?>
                                            <strong style="color: #fff;"><?= $pagina->titulo; ?></strong>
                                        <?php else: ?>
                                            <span style="color: #000080;"><?= $pagina->titulo; ?></span>
                                        <?php endif; ?>
                                    </a>

                                <?php endif; ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 hidden-xs hidden-sm">
                <?php if ($paginaatual->anterior > $curso->pagina_inicial): ?>
                    <div class="box-content pull-left" style="display: none;">
                        <p>
                            <a class="btn btn-primary"
                               href="<?php echo site_url('ead/treinamento_cliente/acessar/' . $this->uri->rsegment(3) . '/' . $paginaatual->anterior); ?>">
                                <i class="glyphicon glyphicon-arrow-left"></i> Anterior
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-6 hidden-xs hidden-sm">
                <?php if ($paginaatual->proxima < $curso->pagina_final): ?>
                    <div class="box-content pull-right" style="display: none;">
                        <p>
                            <a class="btn btn-primary"
                               href="<?php echo site_url('ead/treinamento_cliente/acessar/' . $this->uri->rsegment(3) . '/' . $paginaatual->proxima); ?>">
                                Próximo <i class="glyphicon glyphicon-arrow-right"></i>
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <!-- Modal -->
    <div class='modal' id='modal-informacao' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
         aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Informação para finalização da aula</h3>
                </div>
                <div class='modal-body'>
                    <p>
                        Para finalizar o tópico, por favor responda todas as questões e clique em
                        <strong><?= count($perguntas) > 1 ? '"Enviar respostas"' : '"Enviar resposta"' ?></strong>
                    </p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-default' data-dismiss="modal" id='fechaModal'>Fechar</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var atualizandoTempoEstudo = false;

    $(document).ready(function () {
        //                document.getElementById('#fullscreen').classList.add("disabled");
        document.title = 'CORPORATE RH - LMS - Treinamento - <?php echo $curso->nome; ?> - <?php echo $paginaatual->titulo; ?>';
        //        $('#sidebar').addClass('hide-left-bar');
        if ('<?= $paginaatual->autoplay ?>' === '1') {
            //                    var audio = new Audio();
            //                    audio.src = '<?php //base_url('arquivos/media/' . $paginaatual->audio);                        ?>';
            //                    audio.play();
            $('#audio').trigger('click');
            $('.popover').hide();
        }

        // Atualiza o tempo de estudo a cada minuto
        if ('<?= $paginaatual->status ?>' === '0') {
            setInterval(function () {
                if (atualizandoTempoEstudo === false) {
                    atualizarTempoEstudo(<?= $paginaatual->id_curso . "," . $paginaatual->id; ?>);
                }
            }, 60000);
        }
    });

    function atualizarTempoEstudo(id_curso, id_pagina) {
        atualizandoTempoEstudo = true;
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('ead/treinamento_cliente/atualizarTempoEstudo'); ?>',
            dataType: 'JSON',
            data: {
                id_curso: id_curso,
                id_pagina: id_pagina
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus + ' ' + jqXHR.status + ': ' + (jqXHR.status === 0 ? 'Disconnected' : errorThrown));
            },
            complete: function () {
                atualizandoTempoEstudo = false;
            }
        });
    }
</script>

<!--Core js-->
<script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>
<script class="include" src="<?= base_url("assets/js/jquery.dcjqaccordion.2.7.js"); ?>"></script>
<script src="<?= base_url("assets/js/jquery.scrollTo.min.js"); ?>"></script>
<script src="<?= base_url("assets/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"); ?>"></script>
<script src="<?= base_url("assets/js/jquery.nicescroll.js"); ?>"></script>
<!--Easy Pie Chart-->
<script src="<?= base_url("assets/js/easypiechart/jquery.easypiechart.js"); ?>"></script>
<!--Sparkline Chart-->
<script src="<?= base_url("assets/js/sparkline/jquery.sparkline.js"); ?>"></script>
<!--jQuery Flot Chart
                <script src="<?= base_url("assets/js/flot-chart/jquery.flot.js"); ?>"></script>
                <script src="<?= base_url("assets/js/flot-chart/jquery.flot.tooltip.min.js"); ?>"></script>
                <script src="<?= base_url("assets/js/flot-chart/jquery.flot.resize.js"); ?>"></script>
                <script src="<?= base_url("assets/js/flot-chart/jquery.flot.pie.resize.js"); ?>"></script>
        -->


<script src="<?= base_url("assets/js/scripts.js"); ?>"></script>

<!-- Ajax -->
<script src="<?php echo base_url("assets/js/ajax/ajax.form.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/ajax/ajax.upload.js"); ?>"></script>
<script src="<?php echo base_url('assets/js/ajax/ajax.custom.js'); ?>"></script>

<script src="<?php echo base_url("assets/js/jquery-migrate-1.2.1.js"); ?>"></script>

<!--clock init-->
<!--<script src="<?php // base_url('assets/js/css3clock/js/css3clock.js');                                                                                                                                                        ?>"></script>-->

<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>
<!-- Js -->
<script>

    var zoom_percent = 100;
    var audio = <?= !empty($paginaatual->audio ? 'true' : 'false') ?>;
    if (audio) {
        $('#audio').popover({
            'title': 'Player',
            'html': true,
            'placement': 'bottom',
            'template': '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title text-primary"></h3><div class="popover-content"></div></div>',
            'content': '<audio id="player" controls<?= $paginaatual->autoplay ? ' autoplay' : '' ?> src="<?= base_url('arquivos/media/' . $paginaatual->audio); ?>"></audio>'
        });
    }

    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

    $('#aumentar_zoom').on('click', function () {
        if (zoom_percent === 170) {
            return false;
        }
        zoom_percent += 10;
        $(document.body).css('zoom', (zoom_percent).toString() + '%');
    });

    $('#diminuir_zoom').on('click', function () {
        if (zoom_percent === 50) {
            return false;
        }
        zoom_percent -= 10;
        $(document.body).css('zoom', (zoom_percent).toString() + '%');
    });

    $(function () {
        $('.enviaResposta').click(function () {
            var modulo = '<?= $paginaatual->modulo; ?>';
            //Verifica se é atividade ou quiz
            if (modulo === 'quiz' || modulo === 'atividades') {
                $.ajax({
                    'type': 'POST',
                    'url': '<?= site_url('ead/treinamento_cliente/avaliar_atividade'); ?>',
                    'data': $('#respostaAtividades').serialize(),
                    'dataType': 'json',
                    'success': function (json) {
                        if (json === 'Atividade finalizada com sucesso!') {
                            $('html, body').animate({scrollTop: 0}, 1500);
                            $("#alert").addClass("alert alert-success");
                            $('#finalizarAula').attr('onclick', "javascript:finalizaPagina('<?= $paginaatual->id_curso . "','" . $paginaatual->id; ?>');");
                            $('#finalizarAula').removeClass('btn-warning').addClass('btn-success');
                        } else {
                            $("#alert").addClass("alert alert-danger");
                        }
                        $('#alert').html(data);
                    }
                });
            }

            //Executa Loop entre todas as Radio buttons
            $("input:radio").each(function () {
                if ($(this).is(':checked')) {
                    if ($(this).data('peso') > 0) {
                        $('#alternativa-correta-' + $(this).data('pergunta')).fadeIn('slow');
                        $('#alternativa-errada-' + $(this).data('pergunta')).fadeOut('slow');
                    } else {
                        $('#alternativa-errada-' + $(this).data('pergunta')).fadeIn('slow');
                        $('#alternativa-correta-' + $(this).data('pergunta')).fadeOut('slow');
                    }
                }
            });
        });
    });

    $(function () {
        $('.dissertativa').click(function () {
            $('#alternativa-dissertativa-' + $(this).data('pergunta')).fadeIn('slow');

        });
    });

    function finalizaPagina(id_curso, id_pagina) {
        if (id_curso > 0 && id_pagina > 0) {
            $.ajax({
                'type': 'POST',
                'url': '<?php echo site_url('ead/treinamento_cliente/finalizar_pagina'); ?>',
                'dataType': 'json',
                'data': {
                    'id_curso': id_curso,
                    'id_pagina': id_pagina
                },
                'success': function (json) {
                    if (json.proxima !== null) {
                        window.location.href = '<?= site_url('ead/treinamento_cliente/acessar/' . $this->uri->rsegment(3)); ?>/' + json.proxima;
                    } else {
                        window.location.href = '<?= site_url('ead/treinamento_cliente'); ?>';
                    }
                }
            });
        }
    }

    function modalInformacao() {
        $('#modal-informacao').modal('show');
    }

    //Audio

    if (audio === true) {
        $('.nav .top-menu').append('' +
            '<li id="header_notification_bar" class="dropdown"> ' +
            '<a data-toggle="dropdown" class="dropdown-toggle" href="#">' +
            '<i class="fa fa-play"></i>' +
            '<span class="badge bg-warning">1</span>' +
            '</a>' +
            '<ul class="dropdown-menu extended notification">' +
            '<li>' +
            '<p>Player</p>' +
            '</li>' +
            '<li>' +
            '<div class="alert alert-info clearfix">' +
            '<audio id="player" controls="" src="<?= base_url('arquivos/media/' . $paginaatual->audio); ?>"></audio>' +
            '</div>' +
            '</li>' +
            '</ul>' +
            '</li>');
    }

    $('#informacoes-curso').prepend('' +
        '<li class="widget-collapsible"> ' +
        '<a href="#" class="head widget-head red-bg active clearfix"> ' +
        '<span class="pull-left">Estatísticas</span> ' +
        '<span class="pull-right widget-collapse"><i class="ico-minus"></i></span> ' +
        '</a> ' +
        '<ul class="widget-container"> ' +
        '<li> ' +
        '<div class="prog-row side-mini-stat"> ' +
        '<div class="side-graph-info payment-info"> ' +
        '<h4>Percentual realizado</h4><p><?= ($andamento >= 100 ? 100 : round($andamento, 2)); ?>% realizado</p> ' +
        '</div> ' +
        '<div class="side-mini-graph"> ' +
        '<div class="p-collection"> ' +
        '<span class="pc-epie-chart" data-percent="<?= round($andamento, 2); ?>"> ' +
        '<span class="percent"></span> ' +
        '</span> ' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</li>');

    $('#fullscreen').on('click', function () {
        if (!(document.fullscreenEnabled || document.oFullscreenEnabled || document.msFullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled || document.webkitCurrentFullScreenEnabled)) {
            return false;
        }
        if (document.fullscreenElement || document.mozFullScreen || document.webkitIsFullScreen || document.msFullscreenElement) {
            exitFullscreen();
        } else {
            launchIntoFullscreen(document.documentElement);
        }
    });

    function launchIntoFullscreen(elem) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullScreen) {
            elem.webkitRequestFullScreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
        sessionStorage.setItem('fullscreen', true);
    }

    function exitFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
        sessionStorage.setItem('fullscreen', false);
    }

</script>

</body>
</html>
