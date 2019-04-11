<?php require_once APPPATH . 'views/header.php'; ?>
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

        /*    .wizard > .steps > ul > li {
                width: 33% !important;
            }*/

        .wizard > .steps a, .wizard > .steps a:hover, .wizard > .steps a:active {
            background: #eee !important;
        }

        .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active {
            background: #111343 !important;
            color: #fff;
            cursor: default;
        }

        .wizard > .steps .done a, .wizard > .steps .done a:hover, .wizard > .steps .done a:active {
            background: #758fb0 !important;
            color: #fff;
        }
    </style>

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-sm-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('ei/apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Gerenciar Cursos/Disciplinas</li>
                    </ol>
                    <section class="panel">
                        <div class="panel-body">
                            <div id="wizard">
                                <h6>Cursos</h6>
                                <section style="padding: 0; border-top: 1px solid #ddd; height: auto;">
                                    <br>
                                    <button id="novo_curso" class="btn btn-info" onclick="add_curso()"><i
                                                class="glyphicon glyphicon-plus"></i> Novo curso
                                    </button>
                                    <br>
                                    <div class="table-responsive">
                                        <table id="table_curso" class="table table-striped table-condensed"
                                               cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Área/cliente</th>
                                                <th>Curso</th>
                                                <th>Unidades de ensino</th>
                                                <th>Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>

                                <h6>Disciplinas</h6>
                                <section style="padding: 0; border-top: 1px solid #ddd;">
                                    <br>
                                    <button id="nova_disciplina" class="btn btn-info" onclick="add_disciplina()"><i
                                                class="glyphicon glyphicon-plus"></i> Nova disciplina
                                    </button>
                                    <br>
                                    <div class="table-responsive">
                                        <table id="table_disciplina" class="table table-striped table-condensed"
                                               cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Curso</th>
                                                <th>Disciplina</th>
                                                <th>Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_curso" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar curso</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_curso" class="form-horizontal">
                                <input type="hidden" value="<?php echo $empresa; ?>" name="id_empresa"/>
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-9">
                                            <input name="nome" class="form-control" type="text" maxlength="255"
                                                   autofocus>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Cliente</label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('id_diretoria', array('' => 'selecione...') + $clientes, '', 'id="id_diretoria" class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-sm-12">
                                            <?php echo form_multiselect('id_escola[]', array(), array(), 'id="id_escola" class="demo2" size="8"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveCurso" onclick="save_curso()" class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_disciplina" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Adicionar disciplina</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form_disciplina" class="form-horizontal">
                                <input type="hidden" value="" name="id_curso"/>
                                <input type="hidden" value="" name="id"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Nome</label>
                                        <div class="col-md-9">
                                            <input name="nome" class="form-control" type="text" maxlength="255"
                                                   autofocus>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="btnSaveDisciplina" onclick="save_disciplina()"
                                    class="btn btn-success">
                                Salvar
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <!-- End Bootstrap modal -->

            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/jquery.steps.css?1') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

<?php require_once APPPATH . 'views/end_js.php'; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'RhSuite - Corporate RH Tools: Gerenciar Cursos/Disciplinas';
        });

    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery-steps/jquery.steps.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

    <script>
        var save_method;
        var table_curso, table_disciplina;
        var demo2;
        var id_curso = '';

        var steps = $("#wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
//        transitionEffect: "slideLeft",
            transitionEffect: 0,
            autoFocus: true,
            enableFinishButton: false,
            enablePagination: false,
            enableAllSteps: true,
            titleTemplate: "#title#",
            startIndex: <?php echo $indice; ?>
        });

        $(document).ready(function () {

            //datatables
            table_curso = $('#table_curso').DataTable({
                "info": true,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "iDisplayLength": -1,
                "bLengthChange": false,
                "searching": false,
                "paging": false,
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('ei/cursosDisciplinas/ajax_cursos/') ?>",
                    "type": "POST"
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '33%',
                        targets: [0, 2]
                    },
                    {
                        width: '34%',
                        targets: [1]
                    },
                    {
                        mRender: function (data) {
                            if (data === null) {
                                data = '<span class="text-muted">Nenhuma unidade vinculada</span>';
                            }
                            return data;
                        },
                        targets: [2]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ],
                rowsGroup: [0, -1, 1, 2]
            });

            table_disciplina = $('#table_disciplina').DataTable({
                "info": true,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "iDisplayLength": -1,
                "bLengthChange": false,
                "searching": false,
                "paging": false,
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('ei/cursosDisciplinas/ajax_disciplinas/') ?>",
                    "type": "POST",
                    "data": function (d) {
                        d.id_curso = id_curso;
                        return d;
                    }
                },

                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '50%',
                        targets: [0, 1]
                    },
                    {
                        className: "text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false //set not orderable
                    }
                ],
                "drawCallback": function () {
                    if (id_curso.length === 0) {
                        $('#nova_disciplina').addClass('btn-warning').removeClass('btn-info');
                    } else {
                        $('#nova_disciplina').addClass('btn-info').removeClass('btn-warning');
                    }
                }
            });

            demo2 = $('.demo2').bootstrapDualListbox({
                nonSelectedListLabel: 'Unidades de ensino disponíveis',
                selectedListLabel: 'Unidades de ensino selecionadas',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: false,
                filterPlaceHolder: 'Filtrar',
                helperSelectNamePostfix: false,
                selectorMinimalHeight: 132,
                infoText: false
            });

        });

        $('#modal_disciplina').on('shown.bs.modal', function () {
            $(this).find('[autofocus]').focus().select();
        });

        $('#id_diretoria').on('change', function () {
            var id = this.value;
            $.ajax({
                url: "<?php echo site_url('ei/cursosDisciplinas/atualizarEscolas') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    $('#id_escola').html($(json.escolas).html());
                    demo2.bootstrapDualListbox('refresh', true);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });

        function add_curso() {
            save_method = 'add';
            $('#form_curso')[0].reset(); // reset form on modals
            $('#form_curso [name="id"]').val('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_curso').modal('show'); // show bootstrap modal
            $('#form_curso [name="nome"]').focus();
            $('.modal-title').text('Adicionar novo curso'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function add_disciplina() {
            if (id_curso.length === 0) {
                alert('Selecione o curso onde será adicionada a nova disciplina.');
                return false;
            }
            save_method = 'add';
            $('#form_disciplina')[0].reset(); // reset form on modals
            $('#form_disciplina [name="id"]').val('');
            $('#form_disciplina [name="id_curso"]').val(id_curso);
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_disciplina').modal('show'); // show bootstrap modal
            $('#form_disciplina [name="nome"]').focus();
            $('.modal-title').text('Adicionar nova disciplina'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_curso(id) {
            save_method = 'update';
            $('#form_curso')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('ei/cursosDisciplinas/ajax_editCurso') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="id_empresa"]').val(json.id_empresa);
                    $('[name="nome"]').val(json.nome);

                    $('#id_diretoria').val(json.id_diretoria);
                    $('#id_escola').html($(json.escolas).html());
                    demo2.bootstrapDualListbox('refresh', true);

                    $('#modal_curso').modal('show');
                    $('.modal-title').text('Editar curso - ' + json.nome); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function edit_disciplina(id) {
            save_method = 'update';
            $('#form_disciplina')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('ei/cursosDisciplinas/ajax_editDisciplina') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (json) {
                    $('[name="id"]').val(json.id);
                    $('[name="id_curso"]').val(json.id_curso);
                    $('[name="nome"]').val(json.nome);

                    $('#modal_disciplina').modal('show');
                    $('.modal-title').text('Editar disciplina - ' + json.nome); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function save_curso() {
            $('#btnSaveCurso').text('Salvando...'); //change button text
            $('#btnSaveCurso').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('ei/cursosDisciplinas/ajax_addCurso') ?>";
            } else {
                url = "<?php echo site_url('ei/cursosDisciplinas/ajax_updateCurso') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_curso').serialize(),
                dataType: "JSON",
                success: function (json) {
                    if (json.status) {
                        $('#modal_curso').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveCurso').text('Salvar'); //change button text
                    $('#btnSaveCurso').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveCurso').text('Salvar'); //change button text
                    $('#btnSaveCurso').attr('disabled', false); //set button enable
                }
            });
        }

        function save_disciplina() {
            $('#btnSaveDisciplina').text('Salvando...'); //change button text
            $('#btnSaveDisciplina').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('ei/cursosDisciplinas/ajax_addDisciplina') ?>";
            } else {
                url = "<?php echo site_url('ei/cursosDisciplinas/ajax_updateDisciplina') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form_disciplina').serialize(),
                dataType: "JSON",
                success: function (json) {
                    if (json.status) {
                        $('#modal_disciplina').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSaveDisciplina').text('Salvar'); //change button text
                    $('#btnSaveDisciplina').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSaveDisciplina').text('Salvar'); //change button text
                    $('#btnSaveDisciplina').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_curso(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: "<?php echo site_url('ei/cursosDisciplinas/ajax_deleteCurso') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id},
                    success: function (data) {
                        $('#modal_curso').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                        alert('Error deleting data');
                    }
                });
            }
        }

        function delete_disciplina(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: "<?php echo site_url('ei/cursosDisciplinas/ajax_deleteDisciplina') ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: {id: id},
                    success: function (data) {
                        $('#modal_disciplina').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                        alert('Error deleting data');
                    }
                });
            }
        }

        function reload_table() {
            table_curso.ajax.reload(null, false);
            table_disciplina.ajax.reload(null, false);
        }

        function nextDisciplina(id) {
            id_curso = id;
            //steps.next(id);
            $('#wizard-t-1').trigger('click');
            reload_table();
        }
    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>