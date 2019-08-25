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
                        <i class="fa fa-file-text-alt"></i> <span
                                class="hidden-xs hidden-sm"><?php echo $row->titulo; ?></span>
                        <a class="btn btn-default btn-sm"
                           href="<?php echo site_url('ead/pagina_curso/index/' . $row->id_curso); ?>"
                           style="float: right; margin-top: -6px;">
                            <i class="fa fa-reply"></i> Voltar
                        </a>
                        <?php if ($row->audio): ?>
                            <button id="audio" data-toggle="popover" class="btn btn-primary btn-sm"
                                    style="padding: 1px 6px; font-size: 16px; float: right; margin-right: 10px; margin-top: -5px;">
                                <i class="glyphicons glyphicons-volume_up" style="top: 2px;"></i>
                            </button>
                        <?php else: ?>
                            <button class="btn btn-primary btn-sm disabled"
                                    style="padding: 1px 6px; font-size: 16px; float: right; margin-right: 10px; margin-top: -5px;">
                                <i class="glyphicons glyphicons-mute" style="top: 2px;"></i>
                            </button>
                        <?php endif; ?>
                        <button id="diminuir_zoom" class="btn btn-primary btn-sm"
                                style="padding: 1px 6px; font-size: 16px; float: right; margin-right: 10px; margin-top: -5px;">
                            A-
                        </button>
                        <button id="aumentar_zoom" class="btn btn-primary btn-sm"
                                style="padding: 1px 6px; font-size: 16px; float: right; margin-right: 10px; margin-top: -5px;">
                            A+
                        </button>
                    </header>
                    <div class="panel-body" style="height:100%;">
                        <?php switch ($row->modulo): case 'ckeditor': ?>
                            <?= $row->conteudo; ?>
                            <?php break; ?>
                        <?php case 'pdf': ?>
                            <iframe src="https://docs.google.com/gview?embedded=true&url=<?= base_url('arquivos/pdf/' . convert_accented_characters($row->pdf)); ?>"
                                    style="width:100%; height:600px; margin:0;" frameborder="0"></iframe>
                            <?php break; ?>

                        <?php case 'quiz': ?>

                        <?php case 'atividades': ?>
                            <?php foreach ($row->perguntas as $pergunta): ?>
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
                                            <i class="glyphicon glyphicon-ok"></i> <?= $pergunta->feedback_correta; ?>
                                        </div>
                                    <?php else: ?>
                                        <ul class="list-unstyled">
                                            <?php foreach ($pergunta->alternativas as $alternativa): ?>
                                                <li>
                                                    <label style="font-weight: normal">
                                                        <input type="radio" name="pergunta[<?= $pergunta->id; ?>]"
                                                               class="alternativa" value="<?= $alternativa->peso; ?>"
                                                               data-pergunta="<?= $pergunta->id; ?>">
                                                        <?= $alternativa->alternativa; ?>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <div id="alternativa-correta-<?= $pergunta->id; ?>" class="alert alert-success"
                                             style="display: none;">
                                            <i class="glyphicon glyphicon-ok"></i> <?= $pergunta->feedback_correta; ?>
                                        </div>
                                        <div id="alternativa-errada-<?= $pergunta->id; ?>" class="alert alert-danger"
                                             style="display: none;">
                                            <i class="glyphicon glyphicon-remove"></i> <?= $pergunta->feedback_incorreta; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            <button class="btn btn-success <?= $row->perguntas ? '' : 'disabled' ?>" id="enviaResposta"
                                    type="button">Enviar respostas
                            </button>

                            <?php break; ?>

                        <?php case 'url': ?>
                            <div class="col-md-12" style="margin: 0; padding: 0;">
                                <div class="col-md-8" style="margin: 0; padding: 0;">
                                    <?php if ($row->url): ?>
                                        <iframe type="text/html" allowfullscreen style="width: 100%; height: 450px;"
                                                src="<?php echo $row->url; ?>" frameborder="0"></iframe>
                                    <?php else: ?>
                                        <source src="<?php echo base_url('arquivos/videos/' . $row->arquivo_video); ?>"
                                                type="video/mp4">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $row->conteudo; ?>
                                </div>
                            </div>
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
                        <?php case 'links-externos': ?>
                        <?php case 'multimidia': ?>
                            <iframe style="width: 100%; height: 450px;" frameborder="0"
                                    src="<?php echo $biblioteca->link; ?>"
                                    onload="javascript:resizeIframe(this);"></iframe>
                        <?php endswitch; ?>

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
        document.title = 'CORPORATE RH - LMS - Preview - <?php echo $row->titulo; ?>';

        if ('<?= $row->autoplay ?>' === '1') {
//            var audio = new Audio();
//            audio.src = '<?php // base_url('arquivos/media/' . $row->audio);      ?>';
            $('#audio').trigger('click');
            $('.popover').hide();
        }
    });
</script>
<script>
    var zoom_percent = 100;
    var audio = <?= !empty($row->audio ? 'true' : 'false') ?>;
    var enviarResposta = 0;
    if (audio) {
        $('#audio').popover({
            'title': 'Player',
            'html': true,
            'placement': 'bottom',
            'template': '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title text-primary"></h3><div class="popover-content"></div></div>',
            'content': '<audio id="player" controls<?= $row->autoplay ? ' autoplay' : '' ?> src="<?= base_url('arquivos/media/' . $row->audio); ?>"></audio>'
        });
    }

    function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

    $('#aumentar_zoom').on('click', function () {
        if (zoom_percent === 200) {
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

    // Audio
    <?php if (!empty($row->gravacao_audio)): ?>
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
        '<audio id="player" controls="" src="<?= base_url('arquivos/media/' . $row->gravacao_audio); ?>"></audio>' +
        '</div>' +
        '</li>' +
        '</ul>' +
        '</li>');
    <?php endif; ?>




    /*var ctrl,
        minus,
        zero,
        plus;

    document.addEventListener('keydown', function (e) {
        e = window.event ? event : e;
        switch (e.keyCode) {
            case 17: // Control
                ctrl = true;
                break;
            case 109: // -
                minus = true;
                break;
            case 96: // 0
                zero = true;
                break;
            case 107: // +
                plus = true;
                break;
        }
    });

    document.addEventListener('keyup', function (e) {
        if (ctrl && minus) {
            alert("ctrl + minus + x Pressed!");
        } else if (ctrl && zero) {
            alert("ctrl + zero + x Pressed!");
        } else if (ctrl && plus) {
            alert("ctrl + plus + x Pressed!");
        }

        ctrl = minus = zero = plus = false;
    })

    function triggerEvent(eventName, keyCode) {
        var event; // The custom event that will be created

        if (document.createEvent) {
            event = document.createEvent('HTMLEvents');
            event.initEvent(eventName, true, true);
        } else {
            event = document.createEventObject();
            event.eventType = eventName;
        }

        event.eventName = eventName;
        event.keyCode = keyCode || null;

        if (document.createEvent) {
            document.dispatchEvent(event);
        } else {
            document.fireEvent('on' + event.eventType, event);
        }
    }*/

</script>
<?php
require_once APPPATH . 'views/end_html.php';
?>
