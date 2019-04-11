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
                <i class="fa fa-home"></i> Início
            </header>
            <div class="panel-body">

                <!-- <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a aria-controls="agenda-calendario" role="tab" data-toggle="tab" href="#agenda-calendario"><strong>Agenda de Avaliações</strong></a></li>
                    <li role="presentation"><a aria-controls="lista-atividades" role="tab" data-toggle="tab" href="#lista-atividades"><strong>Lista de Atividades</strong></a></li>                    
                    <li role="presentation"><a aria-controls="manutencao" role="tab" data-toggle="tab" href="#manutencao"><strong>Gestão de Reuniões</strong></a></li>                    
                    <li role="presentation"><a aria-controls="manutencao" role="tab" data-toggle="tab" href="#manutencao"><strong>Plano de Trabalho</strong></a></li>
                </ul> -->

                <div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; border-radius: 0 0 5px 5px;">
                    <div role="tabpanel" class="tab-pane active" id="agenda-calendario" >
                        <div id="alert-function"></div>
                        <section class="panel">
                            <div class="panel-body">
                                <div id="eventCalendarDefault"></div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="profile-nav alt">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="lista-atividades">
                        <section class="panel">
                            <div class="panel-body">
                                <button class="btn btn-success" onclick="add_mes()"><i class="glyphicon glyphicon-plus"></i> Cadastrar atividade</button>
                                <button class="btn btn-primary" onclick="excluir_mes()"><i class="glyphicon glyphicon-refresh"></i> Atualizar status</button>
                                <button class="btn btn-info" onclick=""><i class="glyphicon glyphicon-list-alt"></i> Relatório</button>
                                <br>
                                <div class="table-responsive">
                                    <table id="table_atividades" class="table table-hover table-bordered" cellspacing="0" width="calc(100%)" style="border-radius: 0 !important;">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>PR</th>
                                                <th>TP</th>
                                                <th>ST</th>
                                                <th>Atividade mês</th>
                                                <th>Data cadastro</th>
                                                <th>Data limite</th>
                                                <th>Data fechamento</th>
                                                <th>Ações</th>
                                            </tr>                                        
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <table id="table_atividades_filhas" class="table table-hover table-bordered" cellspacing="0" width="calc(100%)" style="border-radius: 0 !important;">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>PR</th>
                                                <th>TP</th>
                                                <th>ST</th>
                                                <th>Atividade mês</th>
                                                <th>Data cadastro</th>
                                                <th>Data limite</th>
                                                <th>Data fechamento</th>
                                                <th>Ações</th>
                                            </tr>                                        
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="manutencao">
                        <br>
                        <section class="panel" style="margin-left: 20px; margin-right: 20px;">
                            <header class="panel-heading">
                                <i class="fa fa-wrench"></i> Módulo não habilitado
                            </header>
                            <div class="panel-body">
                                <div class="col-md-4"><img src="<?= base_url('assets/img/manutencao.jpg'); ?>" class="img-responsive"></div>
                                <div class="col-md-5">
                                    <h4 class="control-label" style="text-align: justify;">
                                        Este módulo se encontra desabilitado! <br/>
                                        Para maiores informações sobre o mesmo,
                                        utilize o fale conosco ou envie uma mensagem
                                        para contato@rhsuite.com.br
                                    </h4>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>
        <!-- page end-->

        <!-- Modal -->
        <div class='modal fade' id='modalInserir' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h4 class='modal-title' id='myModalLabel' style='text-align: center !important;'>
                            Inserir evento
                        </h4>
                    </div>
                    <div class='modal-body'>
                        <?php echo form_open('agenda/inserir', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <div id="alert"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Data/hora</label>

                            <div class="col-md-7">
                                <div class="input-group date form_datetime-component">
                                    <input type="text" class="form-control" readonly=""
                                           value="<?= date('Y-m-d H:i'); ?>" size="16" name="date_to">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary date-set"><i
                                                class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Título</label>

                            <div class="col-lg-7 controls">
                                <input type="text" name="title" placeholder="Título" value=""
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Descrição</label>

                            <div class="col-lg-7 controls">
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Link</label>

                            <div class="col-lg-7 controls">
                                <input type="text" name="link" placeholder="Link" value=""
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Referenciar tarefa</label>

                            <div class="col-lg-7 controls">
                                <select name="usuario_referenciado" class="form-control">
                                    <option value="">Selecione</option>
                                    <?php foreach ($usuarios->result() as $row) { ?>
                                        <option
                                            value="<?php echo $row->id; ?>"><?= "$row->nome [$row->email]"; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3">
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="fa fa-save"></i>
                                    &nbsp;Cadastrar
                                </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'
                                style='display: none !important;' id='fechaModal'>Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- page end-->
    </section>
</section>
<!--main content end-->

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<!-- Grid CSS File (only needed for demo page) -->
<link rel="stylesheet" href="<?= base_url('assets/js/eventCalendar/css/paragridma.css'); ?>">

<!-- Core CSS File. The CSS code needed to make eventCalendar works -->
<link rel="stylesheet" href="<?= base_url('assets/js/eventCalendar/css/eventCalendar.css'); ?>">

<!-- Theme CSS file: it makes eventCalendar nicer -->
<link rel="stylesheet" href="<?= base_url('assets/js/eventCalendar/css/eventCalendar_theme_responsive.css'); ?>">

<!--clock init-->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Home';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?= base_url('assets/js/css3clock/js/css3clock.js'); ?>"></script>

<!-- Date input -->
<link rel="stylesheet" href="<?= base_url('assets/js/bootstrap-datetimepicker/css/datetimepicker.css'); ?>"/>
<script src="<?= base_url('assets/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js'); ?>"></script>

<script src="<?= base_url('assets/js/eventCalendar/js/moment.js'); ?>"></script>
<script src="<?= base_url('assets/js/eventCalendar/js/jquery.eventCalendar.js'); ?>"></script>
<script>
    var table_atividades;
    var table_atividades_filhas;

    $(document).ready(function () {
        table_atividades = $('#table_atividades').DataTable({
            "processing": true,
            "serverSide": true,
            "iDisplayLength": 25,
            "order": [[1, 'desc']],
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            "ajax": {
                "url": "<?php echo site_url('atividades/ajax_list/') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '100%',
                    targets: [4]
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('cursor', 'pointer');
                    },
                    "targets": [0, 2, 4, 5, 6, 7]
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('cursor', 'pointer');
                        switch (rowData[col]) {
                            case '0':
                                $(td).addClass('success').html('BX');
                                break;
                            case '1':
                                $(td).addClass('warning').html('MD');
                                break;
                            case '2':
                                $(td).addClass('danger').html('AL');
                        }
                    },
                    "targets": [1]
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('cursor', 'pointer');
                        switch (rowData[col]) {
                            case '0':
                                $(td).html('NF');
                                break;
                            case '1':
                                $(td).addClass('success').html('F');
                                break;
                            case '2':
                                $(td).addClass('warning').html('DL');
                            case '3':
                                $(td).addClass('danger').html('L');
                        }
                    },
                    "targets": [3]
                },
                {
                    className: 'text-center',
                    targets: [0, 1, 2, 3, 5, 6, 7]
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ]
        });
        $('#table_atividades tbody').on('click', 'td:lt(8)', function () {
            if ($(this).parent('tr').hasClass('active')) {
                $(this).parent('tr').removeClass('active');
            } else {
                table_atividades.$('tr').removeClass('active');
                $(this).parent('tr').addClass('active');
            }
        });
        table_atividades_filhas = $('#table_atividades_filhas').DataTable();


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
            txt_NextEvents: "Próximos eventos:",
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
            format: "yyyy-mm-dd hh:ii",
            minuteStep: 1,
            autoclose: true,
            todayBtn: true,
            language: 'pt'
        });

        //datetime picker end
    });

    function insereEvento() {
        $('#modalInserir').modal('show');
    }

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