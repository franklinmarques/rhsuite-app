<?php
require_once "header.php";
?>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div id="alert"></div>
            <section class="panel">
                <header class="panel-heading">
                    <?php echo $this->load->view('modal_processos', ['url' => 'home']); ?>
                    <i class="fa fa-home"></i> Início
                </header>
                <div class="panel-body">

                    <!-- <ul class="nav nav-tabs">
                        <li role="presentation" class="active"><a aria-controls="agenda-calendario" role="tab" data-toggle="tab" href="#agenda-calendario"><strong>Agenda de Avaliações</strong></a></li>
                        <li role="presentation"><a aria-controls="manutencao" role="tab" data-toggle="tab" href="#manutencao"><strong>Lista de Atividades</strong></a></li>
                        <li role="presentation"><a aria-controls="manutencao" role="tab" data-toggle="tab" href="#manutencao"><strong>Gestão de Reuniões</strong></a></li>
                        <li role="presentation"><a aria-controls="manutencao" role="tab" data-toggle="tab" href="#manutencao"><strong>Minha Agenda</strong></a></li>
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
                            <?php echo form_open('agenda/inserir', 'id="form" data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                            <div id="alert"></div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Data/hora</label>

                                <div class="col-md-9">
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
                            <button type='button' class='btn btn-default' data-dismiss='modal' id='fechaModal'>Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

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
        });

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