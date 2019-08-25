<?php
require_once "header.php";
?>

    <style>
        #busca_scheduler .btn {
            padding: 5px;
        }


        #busca_scheduler .btn-default.active {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        #busca_scheduler .btn-default.active:hover {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div id="alert"></div>
            <section class="panel">
                <header class="panel-heading">
                    <?php $this->load->view('modal_processos', ['url' => 'home']); ?>
                    <i class="fa fa-home"></i> Início
                </header>
                <div class="panel-body">

                    <!-- <ul class="nav nav-pills">
                    <li role="presentation"><a class="alert-info" href="<?php //echo site_url('atividades'); ?>"><strong>Lista de Atividades</strong></a></li>                    
                    <li role="presentation"><a class="alert-info" href="<?php //echo site_url('manutencao'); ?>"><strong>Gestão de Reuniões</strong></a></li>                    
                    <li role="presentation"><a class="alert-info" href="#manutencao"><strong>Plano de Trabalho</strong></a></li>
                </ul> -->

                    <div id="eventCalendarDefault"></div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="profile-nav alt">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- page end-->

            <!-- Modal -->
            <div class='modal fade' id='modalInserir' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'
                 aria-hidden='true'>
                <div class='modal-dialog modal-lg'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id='myModalLabel' style="font-weight: normal;">Inserir evento</h4>
                        </div>
                        <div class='modal-body'>
                            <?php echo form_open('agenda/inserir', 'id="form" data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                            <div id="alert"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Data/hora</label>

                                <div class="col-md-4">
                                    <div class="input-group date form_datetime-component">
                                        <input type="text" class="form-control text-center" readonly=""
                                               value="<?= date('d/m/Y   H:i'); ?>" size="16" name="date_to">
                                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary date-set"><i
                                                    class="fa fa-calendar"></i></button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Título</label>

                                <div class="col-lg-9 controls">
                                    <input type="text" name="title" placeholder="Título" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Descrição</label>

                                <div class="col-lg-9 controls">
                                    <textarea name="description" class="form-control" rows="1"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Link</label>

                                <div class="col-lg-9 controls">
                                    <input type="text" name="link" placeholder="Link" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Departamento</label>
                                <div class="col-lg-4 controls">
                                    <?php echo form_dropdown('depto', $deptos, $depto, 'id="depto" class="form-control filtro input-sm"'); ?>
                                </div>
                                <label class="col-sm-1 control-label">Área</label>
                                <div class="col-lg-4 controls">
                                    <?php echo form_dropdown('area', $areas, $area, 'id="area" class="form-control filtro input-sm"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Compromisso agendado para</label>

                                <div class="col-lg-9 controls">
                                    <?php echo form_dropdown('usuario_referenciado', $usuarios, '', 'id="usuario" class="form-control input-sm"'); ?>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                        <div class='modal-footer'>
                            <button type="submit" name="button" class="btn btn-primary" onclick="$('#form').submit();">
                                Cadastrar
                            </button>
                            <button type='button' class='btn btn-default' data-dismiss='modal' id="fechaModal">Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <div id="modal_scheduler" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><strong>Você possui tarefas pendentes</strong></h4>
                </div>
                <div class="modal-body">

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#dia" aria-controls="home" role="tab"
                                                                  data-toggle="tab">Hoje</a>
                        </li>
                        <li role="presentation"><a href="#semana" aria-controls="profile" role="tab" data-toggle="tab">2 semana</a></li>
                        <li role="presentation"><a href="#mes" aria-controls="messages" role="tab" data-toggle="tab">Tarefas deste mês</a></li>
                    </ul>

                    <!--<div class="row form-group">
                        <label class="control-label col-md-5 text-primary"><strong>Ocultar as atividades
                                recorrentes</strong></label>
                        <div class="col-md-7">
                            <div id="busca_scheduler" class="btn-group btn-group-sm" data-toggle="buttons">
                                <label class="btn btn-default">
                                    <input type="checkbox" name="mes" value="1"> Deste mês
                                </label>
                                <label class="btn btn-default">
                                    <input type="checkbox" name="semana" value="1"> Do número
                                    desta semana
                                </label>
                                <label class="btn btn-default">
                                    <input type="checkbox" name="dia" value="1"> De hoje
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>-->
                    <form id="form_scheduler" action="#" method="POST" autocomplete="off">
                        <?php foreach ($scheduler['atividades'] as $k => $atividades): ?>
                            <div id="scheduler_<?= $atividades->atividade; ?>"
                                 class="scheduler_item <?= $atividades->class_dia; ?> <?= $atividades->class_semana; ?> <?= $atividades->class_mes; ?>">
                                <h4><strong>Atividade:</strong> <?= nl2br($atividades->atividade); ?></h4>
                                <div><strong>Objetivo(s):</strong> <?= nl2br($atividades->objetivos); ?></div>
                                <div class="text-right">
                                    <button type="button" class="btn btn-danger btn-xs"
                                            onclick="excluir_scheduler('<?= $atividades->atividade; ?>')">Excluir
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs"
                                            onclick="salvar_scheduler('<?= $atividades->atividade; ?>');">Não lembrar
                                        novamente
                                    </button>
                                </div>
                                <?php if ($k < $scheduler['total']): ?>
                                    <hr>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        <br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Grid CSS File (only needed for demo page) -->
    <!--    <link rel="stylesheet" href="--><?php //echo base_url('assets/js/eventCalendar/css/paragridma.css'); ?><!--">-->

    <!-- Core CSS File. The CSS code needed to make eventCalendar works -->
    <link rel="stylesheet" href="<?= base_url('assets/js/eventCalendar/css/eventCalendar.css'); ?>">

    <!-- Theme CSS file: it makes eventCalendar nicer -->
    <link rel="stylesheet" href="<?= base_url('assets/js/eventCalendar/css/eventCalendar_theme_responsive.css'); ?>">

    <!--clock init-->
    <script src="<?= base_url('assets/js/css3clock/js/css3clock.js'); ?>"></script>

    <!-- Date input -->
    <link rel="stylesheet" href="<?= base_url('assets/js/bootstrap-datetimepicker/css/datetimepicker.css'); ?>"/>
    <script src="<?= base_url('assets/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js'); ?>"></script>

    <script src="<?= base_url('assets/js/eventCalendar/js/moment.js'); ?>"></script>
    <script src="<?= base_url('assets/js/eventCalendar/js/jquery.eventCalendar.js'); ?>"></script>
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Home';

            $("#eventCalendarDefault").eventCalendar({
                eventsjson: '<?= base_url('agenda/verAgenda'); ?>',
                startWeekOnMonday: false,
                openEventInNewWindow: true,
                showDescription: true,
                monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
                    "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
                dayNames: ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira',
                    'Quinta-Feira', 'Sexta-Feira', 'Sábado'],
                dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                txt_noEvents: "Não há eventos para este período",
                txt_SpecificEvents_prev: "",
                txt_SpecificEvents_after: " - Eventos:",
                txt_next: "Próximo",
                txt_prev: "Anterior",
                txt_NextEvents: "VEJA ABAIXO SUAS ATIVIDADES PROGRAMADAS PARA SEREM EXECUTADAS!",
                txt_GoToEventUrl: "Ir ao evento",
                txt_NumAbbrevTh: "",
                txt_NumAbbrevSt: "",
                txt_NumAbbrevNd: "",
                txt_NumAbbrevRd: "",
                txt_loading: "Carregando...",
                jsonDateFormat: 'human'// link to events json
            });

            //datetime picker start


            $(".form_datetime-component").datetimepicker({
                format: "dd/mm/yyyy   hh:ii",
                minuteStep: 1,
                autoclose: true,
                todayBtn: true,
                language: 'pt',
                pickerPosition: "bottom-left"
            });

            //datetime picker end

            if (<?= !empty($scheduler['atividades']) ? 'true' : 'false'; ?>) {
                $('#modal_scheduler').modal('show');
            }
        });

        $('#busca_scheduler .btn').on('click', function () {
            setTimeout(function () {
                var dia = $('#busca_scheduler [name="dia"]').is(':checked') === false;
                var semana = $('#busca_scheduler [name="semana"]').is(':checked') === false;
                var mes = $('#busca_scheduler [name="mes"]').is(':checked') === false;
                console.log([dia, semana, mes]);
                $('#form_scheduler .scheduler_item').hide();
                if (dia) {
                    $('#form_scheduler .scheduler_dia').show();
                }
                if (semana) {
                    $('#form_scheduler .scheduler_semana').show();
                }
                if (mes) {
                    $('#form_scheduler .scheduler_mes').show();
                }
            });
        });

        function salvar_scheduler(atividade) {
            $('#btnSaveScheduler').prop('disabled', true);
            $.ajax({
                'url': '<?php echo site_url('home/atualizarScheduler') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'atividade': atividade},
                'beforeSend': function () {
                    $('#modal_scheduler .btn').prop('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#scheduler_' + atividade).remove();
                        if ($('.scheduler_item').size() === 0) {
                            $('#modal_scheduler').modal('hide');
                        }
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                    $('#btnSaveScheduler').prop('disabled', false);
                },
                'beforeSend': function () {
                    $('#modal_scheduler .btn').prop('disabled', false);
                }
            });
        }

        function excluir_scheduler(atividade) {
            if (confirm('Deseja excluir a atividade?')) {
                $('#btnSaveScheduler').prop('disabled', true);
                $.ajax({
                    'url': '<?php echo site_url('home/excluirScheduler') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'atividade': atividade},
                    'beforeSend': function () {
                        $('#modal_scheduler .btn').prop('disabled', true);
                    },
                    'success': function (json) {
                        if (json.status) {
                            $('#scheduler_' + atividade).remove();
                            if ($('.scheduler_item').size() === 0) {
                                $('#modal_scheduler').modal('hide');
                            }
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                        $('#btnSaveScheduler').prop('disabled', false);
                    },
                    'beforeSend': function () {
                        $('#modal_scheduler .btn').prop('disabled', false);
                    }
                });
            }
        }


        function insereEvento() {
            $('#modalInserir').modal('show');
        }

        $('#modalInserir').on('show.bs.modal', function () {
            $('#form')[0].reset();
        });

        $('#modalInserir').on('hide.bs.modal', function () {
            if ($('#alert').html().length > 0) {
                $('#alert').html('');
            }
        });

        $('#form .filtro').on('change', function () {
            $.ajax({
                url: "<?php echo site_url('agenda/atualizarFiltro') ?>",
                type: "GET",
                data: {
                    depto: $('#depto').val(),
                    area: $('#area').val(),
                    usuario: $('#usuario').val()
                },
                dataType: "JSON",
                success: function (json) {
                    $('#area').html($(json.area).html());
                    $('#usuario').html($(json.usuario).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                }
            });
        });


        function finalizaEvento(id) {
            if (confirm('Tem certeza que deseja finalizar esse evento?')) {
                var aviso = '#alert-function';

                $.ajax({
                    url: '<?= base_url('agenda/finalizar'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    beforeSend: function () {
                        $('html, body').animate({scrollTop: 0}, 1500);
                        $(aviso).html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                    },
                    error: function () {
                        $(aviso).html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    },
                    success: function (data) {
                        $('html, body').animate({scrollTop: 0}, 1500);
                        if (parseInt(data['retorno'])) {
                            $(aviso).html('<div class="alert alert-success">' + data['aviso'] + '</div>').hide().fadeIn('slow', function () {
                                if (parseInt(data['redireciona']))
                                    window.location = data['pagina'];
                            });
                        } else {
                            $(aviso).html('<div class="alert alert-danger">' + data['aviso'] + '</div>').hide().fadeIn('slow');
                        }
                    }
                });
            }
        }

        function deletaEvento(id) {
            if (confirm('Tem certeza que deseja excluir esse evento?')) {
                var aviso = '#alert-function';

                $.ajax({
                    url: '<?= base_url('agenda/excluir'); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {id: id},
                    beforeSend: function () {
                        $('html, body').animate({scrollTop: 0}, 1500);
                        $(aviso).html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                    },
                    error: function () {
                        $(aviso).html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    },
                    success: function (data) {
                        $('html, body').animate({scrollTop: 0}, 1500);
                        if (parseInt(data['retorno'])) {
                            $(aviso).html('<div class="alert alert-success">' + data['aviso'] + '</div>').hide().fadeIn('slow', function () {
                                if (parseInt(data['redireciona']))
                                    window.location = data['pagina'];
                            });
                        } else {
                            $(aviso).html('<div class="alert alert-danger">' + data['aviso'] + '</div>').hide().fadeIn('slow');
                        }
                    }
                });
            }
        }

    </script>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>