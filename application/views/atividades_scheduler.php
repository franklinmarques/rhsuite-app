<?php
require_once "header.php";
?>
    <style>
        .btn-success {
            background-color: #5cb85c;
            border-color: #4cae4c;
            color: #fff;
        }

        .btn-primary {
            background-color: #337ab7 !important;
            border-color: #2e6da4 !important;
            color: #fff;
        }

        .btn-info {
            color: #fff;
            background-color: #5bc0de;
            border-color: #46b8da;
        }

        .btn-warning {
            color: #fff;
            background-color: #f0ad4e;
            border-color: #eea236;
        }

        .btn-danger {
            color: #fff;
            background-color: #d9534f;
            border-color: #d43f3a;
        }


        #busca .btn {
            padding: 5px;
        }


        #busca .btn-default.active {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        #busca .btn-default.active:hover {
            color: #fff;
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .text-nowrap {
            white-space: nowrap;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>

                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-address-book"></i> Scheduler de atividades recorrentes
                            <div class="tools pull-right">
                                <a class="fa fa-question-circle" href="javascript:;"></a>
                            </div>
                        </header>
                        <div class="panel-body">
                            <form id="busca" action="#" class="form-horizontal" autocomplete="off">
                                <div class="row form-group">
                                    <label class="control-label col-md-3 text-primary">Atividades recorrentes
                                        mensais</label>
                                    <div class="col-md-9">
                                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="01"> Jan
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="02"> Fev
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="03"> Mar
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="04"> Abr
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="05"> Mai
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="06"> Jun
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="07"> Jul
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="08"> Ago
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="09"> Set
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="10"> Out
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="11"> Nov
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="mes[]" value="12"> Dez
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="control-label col-md-3 text-primary">Atividades recorrentes
                                        semanais</label>
                                    <div class="col-md-9">
                                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="semana[]" value="1"> 1&ordf; Sem.
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="semana[]" value="2"> 2&ordf; Sem.
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="semana[]" value="3"> 3&ordf; Sem.
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="semana[]" value="4"> 4&ordf; Sem.
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="semana[]" value="5"> 5&ordf; Sem.
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <label class="control-label col-md-3 text-primary">Atividades recorrentes
                                        diárias</label>
                                    <div class="col-md-9">
                                        <div class="btn-group btn-group-sm" data-toggle="buttons">
                                            <label class="btn btn-default active">
                                                <input type="checkbox" name="dia[]" value="01" checked> 01
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="02"> 02
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="03"> 03
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="04"> 04
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="05"> 05
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="06"> 06
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="07"> 07
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="08"> 08
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="09"> 09
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="10"> 10
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="11"> 11
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="12"> 12
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="13"> 13
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="14"> 14
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="15"> 15
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="16"> 16
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="17"> 17
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="18"> 18
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="19"> 19
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="20"> 20
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="21"> 21
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="22"> 22
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="23"> 23
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="24"> 24
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="25"> 25
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="26"> 26
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="27"> 27
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="28"> 28
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="29"> 29
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="30"> 30
                                            </label>
                                            <label class="btn btn-default">
                                                <input type="checkbox" name="dia[]" value="31"> 31
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>

                    <button class="btn btn-info" id="btnAdd" onclick="add_atividade()"><i
                                class="glyphicon glyphicon-plus"></i> Nova atividade
                    </button>
                    <button class="btn hidden-xs btn-info"><i class="glyphicon glyphicon-print"></i>
                        Imprimir atividade
                    </button>
                    <br/>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Dia</th>
                            <th>Sem.</th>
                            <th>Mês</th>
                            <th>Atividade</th>
                            <th>Objetivos</th>
                            <th>Data limite</th>
                            <th>Envolvidos</th>
                            <th>Observações</th>
                            <th>Processo</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div style="float: right;">
                                <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            </div>
                            <h3 class="modal-title">Cadastro de atividades</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <input type="hidden" value="<?= $usuario; ?>" name="id_usuario"/>
                                <input type="hidden" value="" class="recorrencia" name="id"/>
                                <input type="hidden" value="" class="recorrencia" name="dia"/>
                                <input type="hidden" value="" class="recorrencia" name="semana"/>
                                <input type="hidden" value="" class="recorrencia" name="mes"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-1 text-nowrap">Atividade <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-5">
                                            <textarea name="atividade" class="form-control" rows="3"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Objetivos</label>
                                        <div class="col-md-5">
                                            <textarea name="objetivos" class="form-control" rows="3"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-1 text-nowrap">Data limite</label>
                                        <div class="col-md-11">
                                            <input name="data_limite" class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-1">Envolvidos</label>
                                        <div class="col-md-5">
                                            <textarea name="envolvidos" class="form-control" rows="3"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                        <label class="control-label col-md-1">Obs.</label>
                                        <div class="col-md-5">
                                            <textarea name="observacoes" class="form-control" rows="3"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-1 text-nowrap">Doc. 1</label>
                                        <div class="col-md-11">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <div class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">Selecionar arquivo</span>
                                                    <span class="fileinput-exists">Alterar</span>
                                                    <input type="file" name="documento_1" accept=".pdf"/>
                                                </div>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                                   data-dismiss="fileinput">Remover</a>
                                            </div>
                                            <span id="nome_documento_1" class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-1 text-nowrap">Doc. 2</label>
                                        <div class="col-md-11">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <div class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">Selecionar arquivo</span>
                                                    <span class="fileinput-exists">Alterar</span>
                                                    <input type="file" name="documento_2" accept=".pdf"/>
                                                </div>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                                   data-dismiss="fileinput">Remover</a>
                                            </div>
                                            <span id="nome_documento_2" class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-1 text-nowrap">Doc. 3</label>
                                        <div class="col-md-11">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <div class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new">Selecionar arquivo</span>
                                                    <span class="fileinput-exists">Alterar</span>
                                                    <input type="file" name="documento_3" accept=".pdf"/>
                                                </div>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                                   data-dismiss="fileinput">Remover</a>
                                            </div>
                                            <span id="nome_documento_3" class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave2" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_processo" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div style="float: right;">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                            <h3 class="modal-title">Visualizar processo</h3>
                        </div>
                        <div class="modal-body">
                            <ul class="nav nav-tabs" role="tablist" style="font-size: 15px; font-weight: bolder;">
                                <li role="presentation" class="active">
                                    <a href="#documento_1" aria-controls="documento_1" role="tab" data-toggle="tab">Documento
                                        1</a>
                                </li>
                                <li role="presentation">
                                    <a href="#documento_2" aria-controls="documento_2" role="tab" data-toggle="tab">Documento
                                        2</a>
                                </li>
                                <li role="presentation">
                                    <a href="#documento_3" aria-controls="documento_3" role="tab" data-toggle="tab">Documento
                                        3</a>
                                </li>
                            </ul>
                            <hr>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane" id="documento_1">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <iframe id="iframe_documento_1" src="" width="100%" height="450px"
                                                    frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="documento_2">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <iframe id="iframe_documento_2" src="" width="100%" height="450px"
                                                    frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="documento_3">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <iframe id="iframe_documento_3" src="" width="100%" height="450px"
                                                    frameborder="0" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

            <div class="modal modal_ajuda fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Mapa de atividades</h3>
                        </div>
                        <div class="modal-body">
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer magna metus, facilisis
                                ut magna sit amet, tristique laoreet tortor. Maecenas interdum congue rutrum. Praesent
                                aliquam volutpat orci, ut ornare sem elementum lobortis. Maecenas fringilla pulvinar
                                laoreet. Proin ex augue, finibus sit amet tellus nec, cursus gravida metus. Vivamus leo
                                lectus, rhoncus non purus ut, tincidunt sagittis purus. Nulla pharetra, arcu in auctor
                                eleifend, lectus mi gravida nunc, id mattis lacus turpis maximus turpis. Duis sit amet
                                tempus sapien. Nam sit amet condimentum dolor, quis congue risus. Donec placerat metus
                                id neque tincidunt vulputate. Praesent fringilla luctus sem, nec faucibus ligula
                                elementum sit amet.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </section>
    </section>
    <!--main content end-->

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Scheduler de atividades importantes';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>

    <script>

        var save_method;
        var table;
        var is_mobile = <?php echo $this->agent->is_mobile() ? 'true' : 'false'; ?>;


        $(document).ready(function () {


            table = $('#table').DataTable({
                'dom': "<'row'<'#ocultar_colunas.col-sm-12'>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'info': false,
                'processing': true,
                'serverSide': true,
                'searching': false,
                'lengthChange': false,
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'order': [],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('atividades_scheduler/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    },
                    'dataSrc': function (json) {
                        if (json.draw === 1) {
                            $("#ocultar_colunas").html(
                                '<br>' +
                                '<label class="control-label">Esconder colunas:</label>&nbsp;' +
                                '<label class="checkbox-inline">' +
                                '   <input type="checkbox" autocomplete="off" onchange="ocultar_coluna(0);"> Dias' +
                                '</label>' +
                                '<label class="checkbox-inline">' +
                                '   <input type="checkbox" autocomplete="off" onchange="ocultar_coluna(1);"> Semanas' +
                                '</label>' +
                                '<label class="checkbox-inline">' +
                                '   <input type="checkbox" autocomplete="off" onchange="ocultar_coluna(2);"> Meses' +
                                '</label>' +
                                '<label class="checkbox-inline">' +
                                '   <input type="checkbox" autocomplete="off" onchange="ocultar_coluna(4);"> Objetivos' +
                                '</label>' +
                                '<label class="checkbox-inline">' +
                                '   <input type="checkbox" autocomplete="off" onchange="ocultar_coluna(5);"> Data limite' +
                                '</label>' +
                                '<label class="checkbox-inline">' +
                                '   <input type="checkbox" autocomplete="off" onchange="ocultar_coluna(6);"> Envolvidos' +
                                '</label>' +
                                '<label class="checkbox-inline">' +
                                '   <input type="checkbox" autocomplete="off" onchange="ocultar_coluna(7);"> Observações' +
                                '</label>' +
                                '<label class="checkbox-inline">' +
                                '   <input type="checkbox" autocomplete="off" onchange="ocultar_coluna(8);"> Processo' +
                                '</label>' +
                                '<hr>'
                            );
                        }

                        if (is_mobile) {
                            $('#ocultar_colunas [type="checkbox"]:lt(3)').prop('checked', true);
                            ocultar_coluna(0);
                            ocultar_coluna(1);
                            ocultar_coluna(2);
                        }

                        return json.data;
                    }
                },
                'columnDefs': [
                    {
                        'width': '20%',
                        'targets': [3, 4, 5, 6, 7]
                    },
                    {
                        'width': '1%',
                        'className': 'text-center',
                        'searchable': false,
                        'orderable': false,
                        'targets': [0, 1, 2, 8]
                    },
                    {
                        'className': 'text-center',
                        'searchable': false,
                        'targets': [5]
                    },
                    {
                        'width': '1%',
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ]
            });

        });


        $(document).on('shown.bs.tab', function () {
            $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
        });


        function ocultar_coluna(coluna) {
            var column = table.column(coluna);
            console.log(column);
            column.visible(!column.visible());
        }


        function add_atividade() {
            save_method = 'add';
            $('#form')[0].reset();
            $('.recorrencia').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();
            $('.combo_nivel1').hide();

            $.ajax({
                'url': '<?php echo site_url('atividades_scheduler/ajaxNew') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'busca': $('#busca').serialize()
                },
                'success': function (json) {
                    $.each(json, function (key, value) {
                        $('[name="' + key + '"]').val(value);
                    });

                    $('.modal-title').text('Cadastrar atividade');
                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function edit_atividade(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('.recorrencia').val('');
            $('.form-group').removeClass('has-error');
            $('.help-block').empty();

            $.ajax({
                'url': '<?php echo site_url('atividades_scheduler/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id,
                    'busca': $('#busca').serialize()
                },
                'success': function (json) {
                    if (json.documento_1) {
                        $('#nome_documento_1').html('Nome do arquivo selecionado: <i>' + json.documento_1 + '</i>');
                    }
                    if (json.documento_2) {
                        $('#nome_documento_2').html('Nome do arquivo selecionado: <i>' + json.documento_2 + '</i>');
                    }
                    if (json.documento_3) {
                        $('#nome_documento_3').html('Nome do arquivo selecionado: <i>' + json.documento_3 + '</i>');
                    }

                    $.each(json, function (key, value) {
                        if ($('[name="' + key + '"]').prop('type') !== 'file') {
                            $('[name="' + key + '"]').val(value);
                        }
                    });

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar atividade');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        $('#busca .btn').on('click', function () {
            setTimeout(function () {
                $('#btnAdd').prop('disabled', $('#busca .btn.active').length === 0);
                reload_table();
            });
        });


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function save() {
            $('#btnSave, #btnSave2').text('Salvando...').attr('disabled', true);
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('atividades_scheduler/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('atividades_scheduler/ajaxUpdate') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'data': new FormData($('#form')[0]),
                'enctype': 'multipart/form-data',
                'processData': false,
                'contentType': false,
                'cache': false,
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave, #btnSave2').text('Salvar').attr('disabled', false);
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave, #btnSave2').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_atividade(id) {
            if (confirm('Deseja remover a atividade?')) {
                $.ajax({
                    'url': '<?php echo site_url('atividades_scheduler/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function () {
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    }
                });
            }
        }


        function processo(id) {
            $.ajax({
                'url': '<?php echo site_url('atividades_scheduler/ajaxProcesso') ?>',
                'type': 'GET',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $('#iframe_documento_1').attr('src', json.iframe_documento_1);
                    $('#iframe_documento_2').attr('src', json.iframe_documento_2);
                    $('#iframe_documento_3').attr('src', json.iframe_documento_3);


                    $('#modal_processo').modal('show');

                    // POG para renderização de conteúdo no iframe em abas dentro da modal
                    $('#modal_processo .nav-tabs li:eq(2) a').tab('show');
                    $('#modal_processo .nav-tabs li:eq(1) a').tab('show');
                    $('#modal_processo .nav-tabs li:eq(0) a').tab('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                }
            });
        }

    </script>

<?php
require_once 'end_html.php';
?>