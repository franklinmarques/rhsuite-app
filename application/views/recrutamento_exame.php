<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="<?= base_url("assets/images/favipn.ico"); ?>">

    <title>CORPORATE RH - LMS - <?php echo $teste->titulo; ?></title>

    <!--Core CSS -->
    <link href="<?= base_url("assets/bs3/css/bootstrap.min.css"); ?>" rel="stylesheet">
    <link href="<?= base_url("assets/bs3/fonts/glyphicons-pro.css"); ?>" rel="stylesheet"/>
    <link href="<?= base_url("assets/font-awesome/css/font-awesome.css"); ?>" rel="stylesheet"/>

    <!-- Custom styles for this template -->
    <link href="<?= base_url("assets/css/style.css"); ?>" rel="stylesheet">
    <link href="<?= base_url("assets/css/style-responsive.css"); ?>" rel="stylesheet"/>

    <!--clock css-->

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <script src="<?php echo base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>
</head>

<body>
<section id="container-fluid">
    <!--main content start-->
    <section id="main-content" class="merge-left">
        <section class="wrapper" style="margin-top: 0px;">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-file-text-o"></i><span
                                    class="hidden-xs hoidden-sm"> <?php echo $teste->titulo; ?></span>
                            <div style="float:right; margin-top: -0.5%;">
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal">
                                    <i class="fa fa-check"></i> Enviar e finalizar
                                </button>
                                &nbsp;
                                <a id="fullscreen" data-toggle="dropdown" class="btn btn-primary btn-sm"
                                   href="javascript:void(0)" title="Tela cheia"
                                   style="padding: 2px 6px; font-size: 16px;">
                                    <span class="glyphicons glyphicons-fullscreen" style="top: 2px;"></span>
                                </a>
                            </div>
                        </header>

                        <div class="panel-body">
                            <?php if ($teste->data_envio): ?>
                                <div class="row" id="fechar">
                                    <div class="col-sm-12 text-center">
                                        <div id="alert" class="alert alert-info text-center">Este teste foi
                                            finalizado!
                                        </div>
                                        <br>
                                        <button type="button" class="btn btn-default">Fechar janela</button>
                                        <br>
                                        <br>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php if ($teste->tipo === 'D' || $teste->tipo === 'I'): ?>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="panel panel-default" style="border: 1px solid #999">
                                                <div class="panel-heading text-center"
                                                     style="background: #eee !important;">
                                                    <?php if ($teste->tipo === 'D'): ?>
                                                        <h3 class="panel-title" style="color: #333;">Digite o texto
                                                            abaixo na caixa ao lado</h3>
                                                    <?php elseif ($teste->tipo === 'I'): ?>
                                                        <h3 class="panel-title" style="color: #333;">Interprete o texto
                                                            abaixo na caixa ao lado</h3>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="panel-body">
                                                    <object id="panel-manual" type="image/png"
                                                            data="<?= site_url('recrutamento_testes/texto_exemplo/' . $this->uri->rsegment(3)) ?>"
                                                            width="100%" height="500px"></object>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <?php if ($teste->instrucoes): ?>
                                                <div class="panel panel-default"
                                                     style="border-color: #999; border-radius: 1px; box-shadow: 0 1px 1px rgba(0, 0, 0, .01);">
                                                    <div class="panel-body">
                                                        <?php echo $teste->instrucoes ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <form id="form" class="form-horizontal">
                                                <?php foreach ($perguntas as $k => $pergunta): ?>
                                                    <input type="hidden" value="<?= $pergunta->id ?>" name="pergunta"/>
                                                    <input type="hidden" value="" name="valor"/>
                                                    <?php if ($teste->tipo === 'D'): ?>
                                                        <textarea name="resposta" class="form-control" rows="16"
                                                                  style="border: 1px solid #999"
                                                                  placeholder="Digite neste espaço o texto proposto ao lado"></textarea>
                                                    <?php elseif ($teste->tipo === 'I'): ?>
                                                        <textarea name="resposta" class="form-control" rows="16"
                                                                  style="border: 1px solid #999"
                                                                  placeholder="Digite neste espaço o seu entendimento sobre o texto proposto"></textarea>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </form>
                                        </div>
                                        <div class="col-sm-2 text-center">
                                            <h4>Tempo restante:</h4>
                                            <h1 id="tempo_restante"><?= $tempo_restante ?></h1>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="row">
                                        <div class="col-sm-2 hidden-xs hidden-sm">
                                            <div class="panel panel-default">
                                                <div class="panel-heading text-center"
                                                     style="background: #eee !important;">
                                                    <h3 class="panel-title" style="color: #333;">Mapa de questões</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <?php foreach ($perguntas as $k => $pergunta): ?>
                                                        <p style="overflow:hidden; text-overflow:ellipsis; white-space: nowrap;">
                                                            <span class="glyphicon glyphicon-ok text-muted"></span>
                                                            <a href="#pergunta_<?= $k + 1 ?>"
                                                               title="<?= $pergunta->pergunta; ?>"><?= $pergunta->pergunta; ?></a>
                                                        </p>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <?php if ($teste->instrucoes): ?>
                                                <div class="panel panel-default"
                                                     style="border-radius: 1px; box-shadow: 0 1px 1px rgba(0, 0, 0, .01);">
                                                    <div class="panel-body">
                                                        <?php echo $teste->instrucoes ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="text-center hidden-md hidden-lg">
                                                <h4>Tempo restante:</h4>
                                                <h1 id="tempo_restante_m"><?= $tempo_restante ?></h1>
                                                <br>
                                            </div>
                                            <form id="form" class="form-horizontal">
                                                <?php if ($teste->tipo === 'E'): ?>
                                                    <?php foreach ($competencias as $competencia): ?>
                                                        <div class="well well-sm">
                                                            <?php foreach ($competencia as $k => $pergunta): ?>
                                                                <p id="pergunta_<?= $k + 1 ?>">
                                                                    <strong><?php echo $pergunta->pergunta; ?></strong>
                                                                </p>
                                                                <textarea name="resposta[<?= $pergunta->id ?>]"
                                                                          class="form-control" rows="4"></textarea>
                                                                <br>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <?php foreach ($perguntas as $k => $pergunta): ?>
                                                        <div class="well well-sm">
                                                            <p id="pergunta_<?= $k + 1 ?>">
                                                                <strong><?php echo $pergunta->pergunta; ?></strong></p>
                                                            <div class="well well-sm">
                                                                <ul class="list-unstyled">
                                                                    <?php echo $pergunta->alternativas ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </form>
                                        </div>
                                        <div class="col-sm-2 text-center hidden-xs hidden-sm">
                                            <h4>Tempo restante:</h4>
                                            <h1 id="tempo_restante"><?= $tempo_restante ?></h1>
                                        </div>
                                        <div class="col-sm-2 hidden-md hidden-lg">
                                            <div class="panel panel-default">
                                                <div class="panel-heading text-center"
                                                     style="background: #eee !important;">
                                                    <h3 class="panel-title" style="color: #333;">Mapa de questões</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <?php foreach ($perguntas as $k => $pergunta): ?>
                                                        <p style="overflow:hidden; text-overflow:ellipsis; white-space: nowrap;">
                                                            <span class="glyphicon glyphicon-ok text-muted"></span>
                                                            <a href="#pergunta_<?= $k + 1 ?>"
                                                               title="<?= $pergunta->pergunta; ?>"><?= $pergunta->pergunta; ?></a>
                                                        </p>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>
            </div>

            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <!-- Modal -->
    <div class='modal fade' id='modal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Atenção</h3>
                </div>
                <?php if ($teste->data_envio): ?>
                    <div class='modal-body'>
                        <p>
                            Este teste já foi enviado e finalizado
                        </p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss="modal">Fechar
                        </button>
                    </div>
                <?php else : ?>
                    <div class='modal-body'>
                        <p>
                            Atenção! Depois de finalizar, não será possível refazer o teste
                        </p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' data-dismiss="modal" id='enviar'>Enviar tudo e
                            finalizar
                        </button>
                        <button type='button' class='btn btn-default' data-dismiss="modal">Cancelar
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class='modal fade' id='modal_exit' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
         aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Atenção</h3>
                </div>
                <div class='modal-body'>
                    <p>
                        O teste foi enviado e finalizado com sucesso. Esta página será fechada.
                    </p>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-default' data-dismiss="modal" id="fechaPagina">Ok</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!--Core js-->

<!-- Ajax -->
<script src="<?php echo base_url("assets/js/jquery-migrate-1.2.1.js"); ?>"></script>

<!-- Js -->
<script>

    //            $(document).ready(function () {
    //                $.ajax({
    //                    url: "<?php echo site_url('recrutamento_testes/texto/') ?>/",
    //                    type: "POST",
    //                    dataType: "JSON",
    //                    data: {
    //                        estado: $(this).val()
    //                    },
    //                    success: function (data)
    //                    {
    //                        $('#cidade').html(data.cidades);
    //                    },
    //                    error: function (jqXHR, textStatus, errorThrown)
    //                    {
    //                        alert('Error get data from ajax');
    //                    }
    //                });
    //            });

    //            Flag de confirmação de envio de formulario
    var enviado = false;
    //            Funções de controle do timer
    var str = '<?= $tempo_restante ?>';

    if (str === '' || str === undefined) {
        $('#tempo_restante, #tempo_restante_m').addClass('text-muted').text('Não definido');
    } else {
        var arrTime = str.split(':');

        var hora = parseInt(arrTime[0]);
        var min = parseInt(arrTime[1]);
        var sec = parseInt(arrTime[2]);

        if (hora === 0 && min === 0 && sec === 0) {
            $('#tempo_restante, #tempo_restante_m').addClass('text-danger');
            $('[type="radio"]:not(:checked)').prop('disabled', true);
        }

        var timerID = setInterval(function () {
            if (sec === 0) {
                sec = 59;
                if (min === 0) {
                    min = 59;
                    if (hora === 0) {
                        sec = 0;
                        min = 0;
                        $('#tempo_restante, #tempo_restante_m').addClass('text-danger');
                        $('[type="radio"]:not(:checked)').prop('disabled', true);
                        $('#modal_exit .modal-body p').html('O tempo limite para a realização do teste encerrou, o teste foi enviado e finalizado. Esta página será fechada.');
                        enviar();
                        clearInterval(timerID);
                    } else {
                        hora--;
                    }
                } else {
                    min--;
                }
            } else {
                sec--;
            }
            $('#tempo_restante, #tempo_restante_m').text(('0' + hora).slice(-2) + ':' + ('0' + min).slice(-2) + ':' + ('0' + sec).slice(-2));
        }, 1000);
    }

    //            Funções de controle do teste
    $("#alert").addClass("alert alert-success text-center").html('Teste realizado com sucesso!');

    $('[type="radio"]').on('change', function () {
        var id = $(this).parents('ul').prev('h5').prop('id');
        $('a[href="#' + id + '"]').prev('span').addClass('text-success').removeClass('text-muted');
    });

    $('#enviar').on('click', function () {
        enviar();
    });

    function enviar() {
        $('#enviar').attr('disabled', true).text('Enviando...')
        $('#modal').modal('hide');
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('recrutamento_testes/finalizar/' . $this->uri->rsegment(3)); ?>',
            data: $('#form').serialize(),
            dataType: 'json',
            error: function () {
                $("#alert").addClass("alert alert-danger").html('Erro ao finalizar teste!');
            },
            success: function (json) {
                if (json.status === true) {
                    $('#fechar').show();
                    $('#form').hide();
                    $('#modal_exit').modal('show');
                } else {
                    $("#alert").addClass("alert alert-danger").html('Erro ao finalizar teste!');
                }
            }
        }).done(function () {
            $('#enviar').attr('disabled', false).text('Enviar tudo e finalizar');
        });
    }

    //            Funções de controle da tela
    //            $(window).bind('beforeunload', function () {
    //                return 'Ao sair dessa página você irá descartar todas as respostas.';
    //            });

    $('#fechar, #fechaPagina').on('click', function () {
        window.close();
    });

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