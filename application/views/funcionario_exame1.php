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


<br>
<div class="row">
    <div class="col-md-12">
        <button class="btn btn-info" onclick="add_exame()"><i class="glyphicon glyphicon-plus"></i> Adicionar
            novo exame periódico
        </button>
        <button class="btn btn-info" data-toggle="modal" data-target="#modal_exame_mensagem"><i
                    class="glyphicon glyphicon-pencil"></i> Texto de e-mail
        </button>
        <br/>
        <br/>
        <div class="table-responsive">
            <table id="table_exame" class="table table-striped table-bordered"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Data programada</th>
                    <th>Data realização</th>
                    <th>Data da entrega exame ao RH</th>
                    <th>Local exame</th>
                    <th>Observações sobre o exame</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- page end-->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_exame" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Formulario de Exame Periódico</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_exame" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <input type="hidden" value="<?= $id_usuario; ?>" name="id_usuario"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">Data programada</label>
                            <div class="col-md-2">
                                <input name="data_programada" placeholder="dd/mm/aaaa" size="7"
                                       class="data form-control text-center" type="text">
                                <span class="help-block"></span>
                            </div>
                            <label class="control-label col-md-2">Data realização</label>
                            <div class="col-md-2">
                                <input name="data_realizacao" placeholder="dd/mm/aaaa" size="7"
                                       class="data form-control text-center" type="text">
                                <span class="help-block"></span>
                            </div>
                            <label class="control-label col-md-2">Data entrega</label>
                            <div class="col-md-2">
                                <input name="data_entrega" placeholder="dd/mm/aaaa" size="7"
                                       class="data form-control text-center" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Local do exame</label>
                            <div class="col-md-10">
                                <input name="local_exame" placeholder="Local de realização do exame" maxlength="255"
                                       class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Observações sobre o exame</label>
                            <div class="col-md-10">
                                <textarea name="observacoes" class="form-control" rows="4"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveExame" onclick="save_exame()" class="btn btn-success">
                    Salvar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_exame_mensagem" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Texto de e-mail</h3>
            </div>
            <div class="modal-body form">
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-1">
                            <textarea id="mensagem_exame" rows="3" class="form-control">Caro colaborador, você está convocado para realizar exame médico periódico na data de: dd/mm/aaaa. Favor verificar com o Departamento de Gestão de Pessoas.</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveExameMensagem" onclick="save_exame_mensagem()" class="btn btn-info">
                    Ok
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>
    var table_exame;
    var save_method; //for save method string
    var mensagem_exame = '';

    $('.data').mask('00/00/0000');

    //datatables
    table_exame = $('#table_exame').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "lengthChange": false,
        iDisplayLength: 50,
        "language": {
            "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('examePeriodico/ajax_list/' . $id_usuario) ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            {
                className: 'text-center',
                targets: [0, 1, 2],
            },
            {
                className: "text-nowrap",
                "targets": [-1], //last column
                "orderable": false, //set not orderable
                "searchable": false //set not orderable
            }
        ]
    });

    $('#modal_exame_mensagem').on('shown.bs.modal', function () {
        save_exame_mensagem();
    });

    $('#modal_exame_mensagem').on('hidden.bs.modal', function () {
        $('#mensagem_exame').val(mensagem_exame);
    })

    function save_exame_mensagem() {
        mensagem_exame = $('#mensagem_exame').val();
    }

    function add_exame() {
        save_method = 'add';
        $('#form_exame')[0].reset(); // reset form on modals
        $('#form_exame [name="id"]').val(''); // reset hidden id
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_exame').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar Exame Periódico'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_exame(id) {
        save_method = 'update';
        $('#form_exame')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('examePeriodico/ajax_edit'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('#form_exame [name="id"]').val(data.id);
                $('#form_exame [name="data_programada"]').val(data.data_programada);
                $('#form_exame [name="data_realizacao"]').val(data.data_realizacao);
                $('#form_exame [name="data_entrega"]').val(data.data_entrega);
                $('#form_exame [name="local_exame"]').val(data.local_exame);
                $('#form_exame [name="observacoes"]').val(data.observacoes);

                $('#modal_exame').modal('show');
                $('#main-content .modal-title').text('Editar Exame Periódico'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save_exame() {
        $('#btnSaveExame').text('saving...'); //change button text
        $('#btnSaveExame').attr('disabled', true); //set button disable
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('examePeriodico/ajax_add'); ?>";
        } else {
            url = "<?php echo site_url('examePeriodico/ajax_update'); ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_exame').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_exame').modal('hide');
                    reolad_exame();
                }

                $('#btnSaveExame').text('Salvar'); //change button text
                $('#btnSaveExame').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveExame').text('save'); //change button text
                $('#btnSaveExame').attr('disabled', false); //set button enable
            }
        });
    }

    function delete_exame(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('examePeriodico/ajax_delete'); ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    //if success reload ajax table
                    $('#modal_exame').modal('hide');
                    reolad_exame();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    function enviar_email_exame(id = null) {
        if (confirm('Deseja enviar o e-mail?')) {
            $.ajax({
                url: "<?php echo site_url('examePeriodico/enviarEmail') ?>",
                type: "POST",
                data: {
                    id: id,
                    mensagem: $('#mensagem_exame').val()
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.status) {
                        if (id === null) {
                            alert('E-mails de convocação enviados com sucesso');
                        } else {
                            alert('E-mail de convocação enviado com sucesso');
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (id === null) {
                        alert('Erro ao enviar e-mails de convocação');
                    } else {
                        alert('Erro ao enviar e-mail de convocação');
                    }
                }
            });
        }
    }

    function reolad_exame() {
        table_exame.ajax.reload(null, false);
    }
</script>
