<?php
require_once "header.php";
?>

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-search"></i> Itens de PDI <?= $id_pdi ?> do funcionário
                            - <?= $nome_usuario . ' - ' . $funcao_usuario ?>
                            <a class="btn btn-success btn-sm"
                               style="float:right;border-radius: 20px !important; margin-top: -0.5%;"
                               href="javascript:void(0);" onclick="add_pdi()">
                                <i class="fa fa-plus"></i>
                                <span>Adicionar</span>
                            </a>
                        </header>
                        <div class="panel-body">
                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>

                            <?php echo form_open('pdi_desenvolvimento/ajax_list/' . $id_pdi, 'data-html="html-funcionarios" class="form-horizontal" style="margin-top: 15px;" id="busca-funcionarios"'); ?>
                            <div class="form-group">
                                <!--                            <div class="col-sm-4 col-lg-3 control-label">
                                                                <button class="btn btn-sm btn-success" onclick="add_pdi()"><i class="glyphicon glyphicon-plus"></i> Adicionar competência/item</button>
                                                            </div>-->
                                <div class="col-sm-6 col-lg-7  col-sm-offset-4 col-lg-offset-3 controls">
                                    <input type="text" name="busca" placeholder="Buscar..."
                                           class="form-control input-sm"/>
                                </div>
                                <div class="col-sm-2 col-lg-2">
                                    <button type="submit" class="btn btn-primary"><i
                                                class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                            <?php echo form_close('<div class="box-content" id="html-funcionarios"></div>'); ?>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Formulario de itens do PDI</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id"/>
                                <input type="hidden" value="<?= $id_pdi; ?>" name="id_pdi"/>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Competência/item a desenvolver</label>
                                        <div class="col-md-7">
                                            <input type="text" name="competencia" placeholder="Competencia" value=""
                                                   class="form-control"/>
                                        </div>
                                        <div class="text-right">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Ações para desenvolvimento</label>
                                        <div class="col-md-10">
                                            <textarea name="descricao" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Resultados esperados</label>
                                        <div class="col-md-10">
                                            <textarea name="expectativa" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Resultados alcançados</label>
                                        <div class="col-md-10">
                                            <textarea name="resultado" class="form-control" rows="1"></textarea>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label col-md-6">Data de início</label>
                                                <div class="col-md-6">
                                                    <input name="data_inicio" id="data_inicio" placeholder="dd/mm/aaaa"
                                                           size="7" class="form-control" type="text">
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label col-md-6">Data de término</label>
                                                <div class="col-md-6">
                                                    <input name="data_termino" id="data_termino"
                                                           placeholder="dd/mm/aaaa" size="7" class="form-control"
                                                           type="text">
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Status</label>
                                                <div class="col-md-9">
                                                    <select name="status" class="form-control">
                                                        <option value="N">Não iniciado</option>
                                                        <option value="A">Atrasado</option>
                                                        <option value="E">Em andamento</option>
                                                        <option value="F">Finalizado</option>
                                                        <option value="C">Cancelado</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--                    <div class="modal-footer">
                                                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                            </div>-->
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
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Itens de PDI <?= $id_pdi ?> do funcionário - <?= $nome_usuario . ' - ' . $funcao_usuario ?>';
        });
    </script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

    <script>
        var save_method; //for save method string

        $('#data_inicio, #data_termino').mask('00/00/0000');

        $('#busca-funcionarios').submit(function () {
            ajax_post($(this).attr('action'), $(this).serialize(), $('#' + $(this).data('html')));
            return false;
        }).submit();

        function add_pdi() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#alert').html('');
            $('#modal_form').modal('show'); // show bootstrap modal
            $('#main-content .modal-title').text('Adicionar competência/item'); // Set Title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_pdi(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('#alert').html('');
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                url: "<?php echo site_url('pdi_desenvolvimento/ajax_edit'); ?>/" + id,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    $('[name="id"]').val(data.id);
                    $('[name="id_pdi"]').val(data.id_pdi);
                    $('[name="competencia"]').val(data.competencia);
                    $('[name="descricao"]').val(data.descricao);
                    $('[name="expectativa"]').val(data.expectativa);
                    $('[name="resultado"]').val(data.resultado);
                    $('[name="data_inicio"]').val(data.data_inicio);
                    $('[name="data_termino"]').val(data.data_termino);
                    $('[name="status"]').val(data.status);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar competência/item'); // Set title to Bootstrap modal title

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function save() {
            $('#alert').html('');
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('pdi_desenvolvimento/ajax_add'); ?>";
            } else {
                url = "<?php echo site_url('pdi_desenvolvimento/ajax_update'); ?>";
            }

            // ajax adding data to database
            $.ajax({
                url: url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                success: function (data) {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else {
                        $('#alert').html('<div class="alert alert-danger">' + data['aviso'] + '</div>').hide().fadeIn('slow');
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }

        function delete_pdi(id) {
            if (confirm('Deseja remover?')) {
                // ajax delete data to database
                $.ajax({
                    url: "<?php echo site_url('pdi_desenvolvimento/ajax_delete'); ?>/" + id,
                    type: "POST",
                    dataType: "JSON",
                    success: function (data) {
                        //if success reload ajax table
                        $('#modal_form').modal('hide');
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });

            }
        }

        function reload_table() {
            $('.glyphicon-search').trigger('click');
        }
    </script>

<?php
require_once "end_html.php";
?>