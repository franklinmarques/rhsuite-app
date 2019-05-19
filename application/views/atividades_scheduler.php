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
                            <i class="fa fa-address-book"></i> Scheduler de atividades importantes
                        </header>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" autocomplete="off"> Atividades recorrentes mensais
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                        <button type="button" class="btn btn-default">Jan</button>
                                        <button type="button" class="btn btn-default">Fev</button>
                                        <button type="button" class="btn btn-default">Mar</button>
                                        <button type="button" class="btn btn-default">Abr</button>
                                        <button type="button" class="btn btn-default">Mai</button>
                                        <button type="button" class="btn btn-default">Jun</button>
                                        <button type="button" class="btn btn-default">Jul</button>
                                        <button type="button" class="btn btn-default">Ago</button>
                                        <button type="button" class="btn btn-default">Set</button>
                                        <button type="button" class="btn btn-default">Out</button>
                                        <button type="button" class="btn btn-default">Nov</button>
                                        <button type="button" class="btn btn-default">Dez</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" autocomplete="off"> Atividades recorrentes semanais
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                        <button type="button" class="btn btn-default">Semana 1</button>
                                        <button type="button" class="btn btn-default">Semana 2</button>
                                        <button type="button" class="btn btn-default">Semana 3</button>
                                        <button type="button" class="btn btn-default">Semana 4</button>
                                        <button type="button" class="btn btn-default">Semana 5</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" autocomplete="off"> Atividades recorrentes diárias
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="btn-group btn-group-sm" role="group" aria-label="...">
                                        <button type="button" class="btn btn-default">01</button>
                                        <button type="button" class="btn btn-default">02</button>
                                        <button type="button" class="btn btn-default">03</button>
                                        <button type="button" class="btn btn-default">04</button>
                                        <button type="button" class="btn btn-default">05</button>
                                        <button type="button" class="btn btn-default">06</button>
                                        <button type="button" class="btn btn-default">07</button>
                                        <button type="button" class="btn btn-default">08</button>
                                        <button type="button" class="btn btn-default">09</button>
                                        <button type="button" class="btn btn-default">10</button>
                                        <button type="button" class="btn btn-default">11</button>
                                        <button type="button" class="btn btn-default">12</button>
                                        <button type="button" class="btn btn-default">13</button>
                                        <button type="button" class="btn btn-default">14</button>
                                        <button type="button" class="btn btn-default">15</button>
                                        <button type="button" class="btn btn-default">16</button>
                                        <button type="button" class="btn btn-default">17</button>
                                        <button type="button" class="btn btn-default">18</button>
                                        <button type="button" class="btn btn-default">19</button>
                                        <button type="button" class="btn btn-default">20</button>
                                        <button type="button" class="btn btn-default">21</button>
                                        <button type="button" class="btn btn-default">22</button>
                                        <button type="button" class="btn btn-default">23</button>
                                        <button type="button" class="btn btn-default">24</button>
                                        <button type="button" class="btn btn-default">25</button>
                                        <button type="button" class="btn btn-default">26</button>
                                        <button type="button" class="btn btn-default">27</button>
                                        <button type="button" class="btn btn-default">28</button>
                                        <button type="button" class="btn btn-default">29</button>
                                        <button type="button" class="btn btn-default">30</button>
                                        <button type="button" class="btn btn-default">31</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <button class="btn btn-info" onclick="add_atividade()"><i class="glyphicon glyphicon-plus"></i>
                        Nova atividade
                    </button>
                    <button class="btn btn-info"><i class="glyphicon glyphicon-print"></i>
                        Imprimir atividade
                    </button>
                    <br/>
                    <table id="table" class="table table-striped" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Atividade</th>
                            <th>Objetivos</th>
                            <th>Data limite</th>
                            <th>Envolvidos</th>
                            <th>Observações</th>
                            <th>Processo/roteiro</th>
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
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Cadastro de atividades</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data">
                                <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Atividade</label>
                                        <div class="col-md-10">
                                            <input name="atividade" class="form-control" type="text"
                                                   placeholder="Digite o nome da atividade">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Objetivos</label>
                                        <div class="col-md-10">
                                            <input name="objetivos" placeholder="Digite o nome dos objetivos"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2 text-nowrap">Data limite</label>
                                        <div class="col-md-3">
                                            <input name="data_limite" placeholder="dd/mm/aaaa"
                                                   class="form-control text-center date" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Envolvidos</label>
                                        <div class="col-md-10">
                                            <input name="envolvidos" placeholder="Digite o nome dos envolvidos"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Observações</label>
                                        <div class="col-md-10">
                                            <textarea name="observacoes" class="form-control" rows="3"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2 text-nowrap">Documento 1</label>
                                        <div class="col-md-10">
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
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2 text-nowrap">Documento 2</label>
                                        <div class="col-md-10">
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
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2 text-nowrap">Documento 3</label>
                                        <div class="col-md-10">
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
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

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
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method;
        var table;

        $(document).ready(function () {

            $('.date').mask('00/00/0000');


            table = $('#table').DataTable({
                'info': false,
                'processing': true,
                'serverSide': true,
                'order': [],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('atividades_scheduler/ajaxList/') ?>',
                    'type': 'POST'
                },
                'columnDefs': [
                    {
                        'width': '20%',
                        'targets': [0, 1, 3, 4, 5]
                    },
                    {
                        'width': 'text-center',
                        'searchable': false,
                        'targets': [2]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ]
            });

        });

        function add_atividade() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form').modal('show'); // show bootstrap modal
            $('.modal-title').text('Cadastro de atividade'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_atividade(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('apontamento_detalhes/ajax_edit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="id_empresa"]').val(json.id_empresa);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar atividade');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });

        }

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = '<?php echo site_url('atividades_scheduler/ajaxAdd') ?>';
            } else {
                url = '<?php echo site_url('atividades_scheduler/ajaxUpdate') ?>';
            }

            // ajax adding data to database
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
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
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
                    'success': function (json) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    }
                });
            }
        }

    </script>

<?php
require_once "end_html.php";
?>