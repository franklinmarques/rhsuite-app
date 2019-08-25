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
    <!--<link href="<?php //echo base_url("assets/css/bootstrap-reset.css");                                                                                                    ?>" rel="stylesheet">-->
    <link href="<?= base_url("assets/bs3/fonts/glyphicons-pro.css"); ?>" rel="stylesheet"/>
    <link href="<?= base_url("assets/font-awesome/css/font-awesome.css"); ?>" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="<?= base_url("assets/css/style.css"); ?>" rel="stylesheet">
    <link href="<?= base_url("assets/css/style-responsive.css"); ?>" rel="stylesheet"/>

    <!--clock css-->
    <!--<link href="<?php // base_url("assets/js/css3clock/css/style.css");                                                                                                    ?>" rel="stylesheet">-->

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

<body style="height: 100vh;">
<section id="container-fluid">
    <!--main content start-->
    <section id="main-content" class="merge-left">
        <section class="wrapper" style="margin-top: 0px; height: calc(100% - 20px);">

            <!-- page start-->
            <div class="row" style="height: inherit;">
                <div class="col-sm-2 hidden-xs hidden-sm" style="padding-right: 1px;">
                    <div class="panel" style="border-color: #111343">
                        <div class="panel-heading text-center" style="background: #111343 !important;">
                            <h3 class="panel-title">Plano de Aprendizagem</h3>
                        </div>
                        <div class="panel-body" style="overflow-y: auto; max-height: 600px; font-size: 12px;">
                            <?php foreach ($paginas as $k => $pagina): ?>
                                <p style="margin: 0 0 5px; overflow:hidden; text-overflow:ellipsis; white-space: nowrap;<?= $pagina->id === $paginaatual->id ? 'background: #758FB0; color: #fff' : '' ?>">
                                    <span class="glyphicon glyphicon-ok" style="color: rgba(255,255,255,0);"></span>
                                    <a href="<?php echo site_url('ead/cursos/preview/' . $curso->id . '/' . $pagina->ordem); ?>"
                                       title="<?= $pagina->titulo; ?>">
                                        <?php if ($pagina->id === $paginaatual->id): ?>
                                            <strong style="color: #fff;"><?= $pagina->titulo; ?></strong>
                                        <?php else: ?>
                                            <span style="color: #000080;"><?= $pagina->titulo; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10" style="padding-left: 10px; height: inherit;">
                    <section class="panel" style="max-width: 100%; height: inherit;">
                        <header class="panel-heading">
                            <i class="fa fa-file-text-o"></i><span
                                    class="hidden-xs hidden-sm"> <?php echo $paginaatual->titulo; ?></span>
                            <div style="float:right; margin-top: -5px;">
                                <?php if ($paginaatual->audio): ?>
                                    <button id="audio" data-toggle="popover" class="btn btn-primary btn-sm"
                                            style="padding: 1px 6px; font-size: 18px;">
                                        <i class="glyphicons glyphicons-volume_up" style="top: 2px;"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-primary btn-sm disabled"
                                            style="padding: 1px 6px; font-size: 18px;">
                                        <i class="glyphicons glyphicons-mute" style="top: 2px;"></i>
                                    </button>
                                <?php endif; ?>
                                &nbsp;
                                <?php if ($this->uri->rsegment(4) > $curso->primeira_pagina): ?>
                                    <a class="btn btn-primary btn-sm"
                                       href="<?php echo site_url('ead/cursos/preview/' . $this->uri->rsegment(3) . '/' . ((int)$this->uri->rsegment(4) - 1)); ?>">
                                        <i class="glyphicon glyphicon-arrow-left"></i><span class="hidden-xs hidden-sm"> Anterior</span>
                                    </a>
                                <?php else: ?>
                                    <a class="btn btn-primary btn-sm" href="#" disabled="">
                                        <i class="glyphicon glyphicon-arrow-left"></i><span class="hidden-xs hidden-sm"> Anterior</span>
                                    </a>
                                <?php endif; ?>

                                <?php if ($this->uri->rsegment(4) < $curso->ultima_pagina): ?>
                                    <a class="btn btn-primary btn-sm"
                                       href="<?php echo site_url('ead/cursos/preview/' . $this->uri->rsegment(3) . '/' . ((int)$this->uri->rsegment(4) + 1)); ?>">
                                        <span class="hidden-xs hidden-sm">Próximo </span><i
                                                class="glyphicon glyphicon-arrow-right"></i>
                                    </a>
                                <?php else: ?>
                                    <a class="btn btn-primary btn-sm" href="#" disabled="">
                                        <span class="hidden-xs hidden-sm">Próximo </span><i
                                                class="glyphicon glyphicon-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                                <?php if ($this->agent->is_mobile()): ?>
                                    <button class="btn btn-default btn-sm" onclick="window.close();">
                                        <i class="fa fa-times"></i>
                                    </button>
                                <?php endif; ?>
                                <a id="fullscreen" data-toggle="dropdown" class="btn btn-primary btn-sm"
                                   href="javascript:void(0)" title="Tela cheia"
                                   style="padding: 2px 6px; font-size: 16px;">
                                    <span class="glyphicons glyphicons-fullscreen" style="top: 2px;"></span>
                                </a>
                            </div>
                        </header>

                        <div class="panel-body" style="height: inherit;">
                            <div id="alert"></div>
                            <?php switch ($paginaatual->modulo): case 'ckeditor': ?>

                                <?php echo $paginaatual->conteudo; ?>
                                <?php break; ?>

                            <?php case 'pdf': ?>

                                <iframe width="100%" height="100%"
                                        src="https://docs.google.com/gview?embedded=true&url=<?php echo base_url('arquivos/pdf/' . $paginaatual->pdf); ?>"
                                        frameborder="0" allowfullscreen></iframe>
                                <?php break; ?>

                            <?php case 'quiz': ?>

                            <?php case 'atividades': ?>

                                <?php foreach ($perguntas as $pergunta): ?>
                                    <div class="well">
                                        <?= $pergunta->conteudo; ?>
                                        <?php if ($pergunta->tipo == 2): ?>
                                            <div class="form-group">
                                                <textarea class="form-control" rows="3"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <a href="#" class="btn btn-success btn-xs dissertativa"
                                                   data-pergunta="<?= $pergunta->id; ?>">
                                                    <i class="glyphicon glyphfa fa-eye-open"></i> Visualizar Resposta
                                                </a>
                                            </div>
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
                                                                   class="alternativa"
                                                                   value="<?= $alternativa->peso; ?>"
                                                                   data-pergunta="<?= $pergunta->id; ?>">
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
                                <button class="btn btn-success <?= $perguntas ? '' : 'disabled' ?>" id="enviaResposta"
                                        type="button">Enviar respostas
                                </button>

                                <?php break; ?>

                            <?php case 'url': ?>

                                <?php if (!empty($paginaatual->url)): ?>
                                    <div class="col-md-12" style="margin: 0; padding: 0;">
                                        <div class="col-md-8" style="margin: 0; padding: 0;">
                                            <iframe type="text/html" allowfullscreen style="width: 100%; height: 450px;"
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

                                <iframe width="100%" height="580" frameborder="0" allowfullscreen
                                        src="<?php echo $biblioteca->link; ?>"
                                        onload="javascript:resizeIframe(this);"></iframe>

                            <?php endswitch ?>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row hidden-xs hidden-sm">
                <div class="col-md-6">
                    <?php if ($this->uri->rsegment(4) > 0): ?>
                        <div class="box-content pull-left">
                            <p>
                                <a class="btn btn-primary"
                                   href="<?php echo site_url('ead/cursos/preview/' . $this->uri->rsegment(3) . '/' . ((int)$this->uri->rsegment(4) - 1)); ?>">
                                    <i class="glyphicon glyphicon-arrow-left"></i> Anterior
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if ($this->uri->rsegment(4) < $curso->ultima_pagina): ?>
                        <div class="box-content pull-right">
                            <p>
                                <a class="btn btn-primary"
                                   href="<?php echo site_url('ead/cursos/preview/' . $this->uri->rsegment(3) . '/' . ((int)$this->uri->rsegment(4) + 1)); ?>">
                                    Próximo <i class="glyphicon glyphicon-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <!-- Modal -->
    <div class='modal fade' id='modal-informacao' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
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
                        Para finalizar a aula, por favor responda todas as questões e clique em <strong>enviar
                            respostas</strong>
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
    $(document).ready(function () {
//                document.getElementById('#fullscreen').classList.add("disabled");
        document.title = 'CORPORATE RH - LMS - Treinamento - <?php echo $curso->nome; ?> - <?php echo $paginaatual->titulo; ?>';
        //        $('#sidebar').addClass('hide-left-bar');
        if ('<?= $paginaatual->autoplay ?>' === '1') {
//                    var audio = new Audio();
//                    audio.src = '<?php // base_url('arquivos/media/' . $paginaatual->audio);  ?>';
//                    audio.play();
            $('#audio').trigger('click');
            $('.popover').hide();
        }
    });
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
<!--<script src="<?php // base_url('assets/js/css3clock/js/css3clock.js');                                                                                                 ?>"></script>-->

<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>
<!-- Js -->
<script>

    var audio = <?= !empty($paginaatual->audio ? 'true' : 'false') ?>;
    var enviarResposta = 0;
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

    $('.dissertativa').click(function () {
        $('#alternativa-dissertativa-' + $(this).data('pergunta')).fadeIn('slow');
    });

    $('#enviaResposta').click(function () {
        $('#enviaResposta').text('Enviando...');

        if (enviarResposta === 0) {
            $("#alert").addClass("alert alert-success").html('Atividade finalizada com sucesso!');
            enviarResposta += 1;
        } else {
            $("#alert").addClass("alert alert-warning").html('Atividade realizada anteriormente!');
        }

        //Executa Loop entre todas as Radio buttons
        $('.alternativa').each(function () {
            var pergunta = $(this).data('pergunta');
            if ($(this).is(':checked')) {
                if ($(this).val() === '1') {
                    $('#alternativa-errada-' + pergunta).slideUp();
                    setTimeout(function () {
                        $('#alternativa-correta-' + pergunta).slideDown();
                    }, 500);
                } else {
                    $('#alternativa-correta-' + pergunta).slideUp();
                    setTimeout(function () {
                        $('#alternativa-errada-' + pergunta).slideDown();
                    }, 500);
                }
            }
        });

        setTimeout(function () {
            $('#enviaResposta').text('Enviar respostas');
        }, 1000);
    });

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