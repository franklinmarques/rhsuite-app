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
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('facilities/estruturas') ?>">Cadastro Estrutural de Facilities</a>
                        </li>
                        <li class="active">Itens de Facilities</li>
                    </ol>
                    <button class="btn btn-info" onclick="add_item()"><i class="glyphicon glyphicon-plus"></i>
                        Adicionar Item de Facilities
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th>Empresa</th>
                            <th>Unidade</th>
                            <th>Andar</th>
                            <th>Sala</th>
                            <th>Nome</th>
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
                            <h3 class="modal-title">Formulario de Item de Facilities</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="0" name="ativo"/>
                                <input type="hidden" value="<?= $idSala; ?>" name="id_sala"/>
                                <input type="hidden" value="<?= $nomeSala; ?>" id="nome_sala"/>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Tipo ativo</label>
                                        <div class="col-md-6 controls">
                                            <?php echo form_dropdown('tipo', $tipoItem, '', 'class="combobox form-control"'); ?>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button type="button" id="btnSave2" onclick="save()"
                                                    class="btn btn-success">Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Nome do item</label>
                                        <div class="col-md-9">
                                            <input name="nome" placeholder="Digite o nome do item"
                                                   class="form-control" type="text">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Código (ativo fixo)</label>
                                        <div class="col-md-5">
                                            <input name="codigo" class="form-control" type="text" maxlength="50">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Data entrada operação</label>
                                        <div class="col-md-4">
                                            <input name="data_entrada_operacao"
                                                   class="form-control data text-center"
                                                   type="text" placeholder="dd/mm/aaaa">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Vida útil (anos)</label>
                                        <div class="col-md-4">
                                            <input name="anos_duracao" class="form-control" type="text"
                                                   maxlength="50">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Periodicidade de vistoria</label>
                                        <div class="col-md-4">
                                            <select name="periodicidade_vistoria" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="D">Diária</option>
                                                <option value="H">Semanal</option>
                                                <option value="Q">Quinzenal</option>
                                                <option value="M">Mensal</option>
                                                <option value="B">Bimestral</option>
                                                <option value="T">Trimestral</option>
                                                <option value="S">Semestral</option>
                                                <option value="A">Anual</option>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Meses de vistoria</label>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_jan" type="checkbox" value="1">
                                                    Jan</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_mai" type="checkbox" value="1">
                                                    Mai</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_set" type="checkbox" value="1">
                                                    Set</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_fev" type="checkbox" value="1">
                                                    Fev</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_jun" type="checkbox" value="1">
                                                    Jun</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_out" type="checkbox" value="1">
                                                    Out</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_mar" type="checkbox" value="1">
                                                    Mar</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_jul" type="checkbox" value="1">
                                                    Jul</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_nov" type="checkbox" value="1">
                                                    Nov</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_abr" type="checkbox" value="1">
                                                    Abr</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_ago" type="checkbox" value="1">
                                                    Ago</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_vistoria_dez" type="checkbox" value="1">
                                                    Dez</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Periodicidade de manutencao</label>
                                        <div class="col-md-4">
                                            <select name="periodicidade_manutencao" class="form-control">
                                                <option value="">selecione...</option>
                                                <option value="D">Diária</option>
                                                <option value="H">Semanal</option>
                                                <option value="Q">Quinzenal</option>
                                                <option value="M">Mensal</option>
                                                <option value="B">Bimestral</option>
                                                <option value="T">Trimestral</option>
                                                <option value="S">Semestral</option>
                                                <option value="A">Anual</option>
                                            </select>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-4">Meses de manutenção</label>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_jan" type="checkbox" value="1">
                                                    Jan</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_mai" type="checkbox" value="1">
                                                    Mai</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_set" type="checkbox" value="1">
                                                    Set</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_fev" type="checkbox" value="1">
                                                    Fev</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_jun" type="checkbox" value="1">
                                                    Jun</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_out" type="checkbox" value="1">
                                                    Out</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_mar" type="checkbox" value="1">
                                                    Mar</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_jul" type="checkbox" value="1">
                                                    Jul</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_nov" type="checkbox" value="1">
                                                    Nov</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_abr" type="checkbox" value="1">
                                                    Abr</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_ago" type="checkbox" value="1">
                                                    Ago</label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input name="mes_manutencao_dez" type="checkbox" value="1">
                                                    Dez</label>
                                            </div>
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
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Itens de Facilities';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {
            $('.data').mask('00/00/0000');
            $('.combobox').combobox();

            //datatables
            table = $('#table').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "iDisplayLength": -1,
                "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('facilities/itens/ajaxList/' . $idSala) ?>",
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '15%',
                        targets: [0, 1, 2, 3]
                    },
                    {
                        width: '40%',
                        targets: [4]
                    },
                    {
                        className: "text-nowrap",
                        "orderable": false,
                        "searchable": false,
                        "targets": [-1]
                    }
                ],
                'rowsGroup': [0, 1, 2, 3, 4]
            });

        });

        $('[name="tipo"]').on('change', function () {
            $('[name="nome"]').val(this.value);
        });


        function add_item() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#form [name="id"]').val('');

            $('#modal_form').modal('show');
            $('.modal-title').text('Adicionar Item de Facilities'); // Set title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }


        function edit_item(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('facilities/itens/ajaxEdit') ?>",
                type: "POST",
                dataType: "json",
                data: {id: id},
                success: function (json) {
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').is(':checkbox') === false) {
                            $('#form [name="' + key + '"]').val(value);
                        } else {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value === '1');
                        }
                    });

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar Item de Facilities'); // Set title to Bootstrap modal title
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $('#btnSave, #btnSave2').text('Salvando...'); //change button text
            $('#btnSave, #btnSave2').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('facilities/itens/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('facilities/itens/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "json",
                success: function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave, #btnSave2').text('Salvar'); //change button text
                    $('#btnSave, #btnSave2').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave, #btnSave2').text('Salvar'); //change button text
                    $('#btnSave, #btnSave2').attr('disabled', false); //set button enable
                }
            });
        }


        function delete_item(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    url: "<?php echo site_url('facilities/itens/ajaxDelete') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {id: id},
                    success: function () {
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }


        function vistorias(id_item) {
            var logado = <?php echo $this->session->userdata('logado') ? 'true' : 'false'; ?>;
            if (logado) {
                window.open("<?php echo site_url('facilities/itensVistorias/gerenciar'); ?>/" + id_item, 'Itens de Vistorias', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=100, LEFT=250, WIDTH=1010, HEIGHT=500');
            } else {
                window.open("<?php echo site_url('home/sair'); ?>");
            }
        }

        function manutencao(id_item) {
            var logado = <?php echo $this->session->userdata('logado') ? 'true' : 'false'; ?>;
            if (logado) {
                window.open("<?php echo site_url('facilities/itensManutencao/gerenciar'); ?>/" + id_item, 'Itens de Manutenção Periódica', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=100, LEFT=250, WIDTH=1010, HEIGHT=500');
            } else {
                window.open("<?php echo site_url('home/sair'); ?>");
            }
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>