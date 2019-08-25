<?php require_once APPPATH . 'views/header.php'; ?>

    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li class="active">Gerenciar Pílulas de Conhecimento</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_pilula();"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar pílula
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Área de conhecimento</th>
                            <th>Treinamento</th>
                            <th>Tipo</th>
                            <th>Colaboradores</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->

            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Cadastrar cliente/usuário</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert_form"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $empresa ?>" name="id_empresa"/>
                                <div class="form-body">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Área conhecimento</label>
                                        <div class="col-md-4">
                                            <?php echo form_dropdown('id_area_conhecimento', $areas, '', 'class="form-control"'); ?>
                                        </div>
                                        <label class="control-label col-md-1">Tipo</label>
                                        <div class="col-md-2">
                                            <select name="publico" class="form-control">
                                                <option value="1">Aberto</option>
                                                <option value="0">Fechado</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <button type="button" class="btn btn-success" id="btnSave" onclick="save()">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Treinamento <span
                                                    class="text-danger">*</span></label>
                                        <div class="col-md-10">
                                            <?php echo form_dropdown('id_curso', $cursos, '', 'class="form-control"'); ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Departamento</label>
                                        <div class="col-md-10">
                                            <?php echo form_dropdown('', $deptos, '', 'id="id_depto" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Área</label>
                                        <div class="col-md-10">
                                            <?php echo form_dropdown('', ['' => 'Todas'], '', 'id="id_area" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Setor</label>
                                        <div class="col-md-10">
                                            <?php echo form_dropdown('', ['' => 'Todos'], '', 'id="id_setor" class="form-control estrutura"'); ?>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <?php echo form_multiselect('id_usuario[]', $usuarios, array(), 'id="usuarios" class="form-control demo1"'); ?>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnSave2" onclick="save()">Salvar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </section>
    <!--main content end-->

    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>">

<?php require_once APPPATH . 'views/end_js.php'; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'RhSuite - Corporate RH Tools: Gerenciar Treinamentos de Clientes';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>

    <script>
        var table;
        var save_method;

        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('ead/pilulasConhecimento/ajaxList') ?>',
                    'type': 'POST'
                },
                'columnDefs': [
                    {
                        'width': '24%',
                        'targets': [0]
                    },
                    {
                        'className': 'text-center',
                        'targets': [2]
                    },
                    {
                        'width': '38%',
                        'targets': [1]
                    },
                    {
                        'createdCell': function (td, cellData, rowData, row, col) {
                            if (cellData === null) {
                                $(td).addClass('text-muted');
                                if (rowData[2] === 'Aberto') {
                                    $(td).text('Alocação desabilitada');
                                } else {
                                    $(td).text('Nenhum colaborador alocado');
                                }
                            }
                        },
                        'width': '38%',
                        'targets': [3]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ]
            });

            demo1 = $('.demo1').bootstrapDualListbox({
                'nonSelectedListLabel': 'Colaboradores disponíveis',
                'selectedListLabel': 'Colaboradores alocados para o treinamento',
                'preserveSelectionOnMove': 'moved',
                'moveOnSelect': false,
                'filterPlaceHolder': 'Filtrar',
                'helperSelectNamePostfix': false,
                'selectorMinimalHeight': 132,
                'infoText': false
            });

        });


        $('[name="publico"]').on('change', function () {
            var is_publico = this.value === '1';
            $('.estrutura').prop('disabled', is_publico);
            $('.bootstrap-duallistbox-container').find('*').prop('disabled', is_publico);
        });


        $('.estrutura').on('change', function () {
            $.ajax({
                'url': '<?php echo site_url('ead/pilulasConhecimento/montarEstrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id_depto': $('#id_depto').val(),
                    'id_area': $('#id_area').val(),
                    'id_setor': $('#id_setor').val(),
                    'usuarios_selecionados': $('#usuarios').val()
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#id_area').html($(json.area).html());
                        $('#id_setor').html($(json.setor).html());
                        $('#usuarios').html($(json.usuarios).html());
                        demo1.bootstrapDualListbox('refresh', true);
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Erro ao montar a estrutura');
                }
            });
        });


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function add_pilula() {
            save_method = 'add';
            $('#form')[0].reset();
            $('#alert_form').html('');
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');
            $('[name="publico"]').trigger('change');
            $('.modal-title').text('Adicionar pílula de conhecimento');
            demo1.bootstrapDualListbox('refresh', true);
            $('#modal_form').modal('show');
        }


        function edit_pilula(id) {
            save_method = 'update';
            $('#form')[0].reset();
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');

            $.ajax({
                'url': '<?php echo site_url('ead/pilulasConhecimento/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                        return false;
                    }

                    $('#form [name="id"]').val(json.id);
                    $('#form [name="id_empresa"]').val(json.id_empresa);

                    $.each(json, function (key, value) {
                        $('#form select[name="' + key + '"]').val(value);
                    });
                    $('#usuarios').val(json.usuarios);
                    $('[name="publico"], .estrutura').trigger('change');

                    demo1.bootstrapDualListbox('refresh', true);

                    $('#alert_form').html('');
                    $('.modal-title').text('Editar pílula de conhecimento');
                    $('#modal_form').modal('show');
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Erro ao excluir o cliente/usuário');
                }
            });
        }


        function save() {
            $('#form .form-group').removeClass('has-error');
            $('#form span.help-block').html('');
            var url = '<?php echo site_url('ead/pilulasConhecimento/ajaxUpdate') ?>';
            if (save_method === 'add') {
                url = '<?php echo site_url('ead/pilulasConhecimento/ajaxAdd') ?>';
            }

            $.ajax({
                'url': url,
                'type': 'POST',
                'dataType': 'json',
                'data': $('#form').serialize(),
                'beforeSend': function () {
                    $('#btnSave, #btnSave2').text('Salvando...').attr('disabled', true);
                },
                'success': function (json) {
                    if (json.status) {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else {
                        $('#modal_form').animate({scrollTop: 0});
                        if (json.msg) {
                            $.each(json.msg, function (key, value) {
                                $('#form input[name="' + key + '"]').parents('div.form-group').addClass('has-error');
                                $('#form input[name="' + key + '"] + span.help-block').html(value);
                            });
                        }
                        if (json.erro) {
                            $('#alert_form').html('<div class="alert alert-danger">' + json.erro + '</div>').hide().fadeIn('slow');
                        }
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    $('#alert_form').html('<div class="alert alert-warning">Erro ao salvar cliente/usuário</div>').hide().fadeIn('slow');
                },
                'complete': function () {
                    $('#btnSave, #btnSave2').text('Salvar').attr('disabled', false);
                }
            });
        }


        function delete_pilula(id) {
            if (confirm('Tem certeza que deseja excluir?')) {
                $.ajax({
                    'url': '<?php echo site_url('ead/pilulasConhecimento/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {
                        'id': id
                    },
                    'success': function (json) {
                        if (json.status) {
                            reload_table();
                        } else if (json.erro) {
                            alert(json.erro);
                        }
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Erro ao excluir o cliente/usuário');
                    }
                });
            }
        }

    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>