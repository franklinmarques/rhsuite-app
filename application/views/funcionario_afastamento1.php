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
        <button class="btn btn-info" onclick="add_afastamento()"><i class="glyphicon glyphicon-plus"></i> Adicionar novo
            afastamento
        </button>
        <br/>
        <br/>
        <div class="table-responsive">
            <table id="table_afastamento" class="table table-striped table-bordered"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Data afastamento</th>
                    <th>Motivo do afastamento</th>
                    <th>Data perícia médica</th>
                    <th>Data limite do benefício</th>
                    <th>Data do retorno ao trabalho</th>
                    <th>Histórico afastamento</th>
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
<div class="modal fade" id="modal_afastamento" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Formulario de Afastamento</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_afastamento" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <input type="hidden" value="<?= $id_usuario; ?>" name="id_usuario"/>
                    <input type="hidden" value="<?= $id_empresa; ?>" name="id_empresa"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">Data afastamento</label>
                            <div class="col-md-2">
                                <input name="data_afastamento" placeholder="dd/mm/aaaa"
                                       class="data form-control text-center" type="text">
                                <span class="help-block"></span>
                            </div>
                            <label class="control-label col-md-3">Data do retorno ao trabalho</label>
                            <div class="col-md-2">
                                <input name="data_retorno" placeholder="dd/mm/aaaa"
                                       class="data form-control text-center" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Motivo do afastamento</label>
                            <div class="col-md-7">
                                <select name="motivo_afastamento" class="form-control">
                                    <option value="">selecione...</option>
                                    <option value="1">Auxílio doença - atestado</option>
                                    <option value="2">Licença maternidade</option>
                                    <option value="3">Acidente de trabalho</option>
                                    <option value="4">Aposentadoria por invalidez</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Data perícia médica</label>
                            <div class="col-md-2">
                                <input name="data_pericia_medica" placeholder="dd/mm/aaaa"
                                       class="data form-control text-center" type="text">
                                <span class="help-block"></span>
                            </div>
                            <label class="control-label col-md-3">Data limite do benefício</label>
                            <div class="col-md-2">
                                <input name="data_limite_beneficio" placeholder="dd/mm/aaaa"
                                       class="data form-control text-center" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Histórico afastamento</label>
                            <div class="col-md-9">
                                <textarea name="historico_afastamento" class="form-control" rows="2"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveAfastamento" onclick="save_afastamento()" class="btn btn-success">
                    Salvar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>
    var table_afastamento;
    var save_method; //for save method string

    $('.data').mask('00/00/0000');

    //datatables
    table_afastamento = $('#table_afastamento').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "language": {
            "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('usuarioAfastamento/ajax_list/' . $id_usuario) ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            {
                className: 'text-center',
                targets: [0, 2, 3, 4],
            },
            {
                className: "text-nowrap",
                "targets": [-1], //last column
                "orderable": false, //set not orderable
                "searchable": false //set not orderable
            }
        ]
    });

    function add_afastamento() {
        save_method = 'add';
        $('#form_afastamento')[0].reset(); // reset form on modals
        $('#form_afastamento [name="id"]').val(''); // reset hidden id
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_afastamento').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar período de afastamento'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_afastamento(id) {
        save_method = 'update';
        $('#form_afastamento')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('usuarioAfastamento/ajax_edit'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (json) {
                $('#form_afastamento [name="id"]').val(json.id);
                $('#form_afastamento [name="data_afastamento"]').val(json.data_afastamento);
                $('#form_afastamento [name="motivo_afastamento"]').val(json.motivo_afastamento);
                $('#form_afastamento [name="data_pericia_medica"]').val(json.data_pericia_medica);
                $('#form_afastamento [name="data_limite_beneficio"]').val(json.data_limite_beneficio);
                $('#form_afastamento [name="data_retorno"]').val(json.data_retorno);
                $('#form_afastamento [name="historico_afastamento"]').val(json.historico_afastamento);

                $('#modal_afastamento').modal('show');
                $('#main-content .modal-title').text('Editar período de afastamento'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save_afastamento() {
        $('#btnSaveAfastamento').text('saving...'); //change button text
        $('#btnSaveAfastamento').attr('disabled', true); //set button disable
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('usuarioAfastamento/ajax_add'); ?>";
        } else {
            url = "<?php echo site_url('usuarioAfastamento/ajax_update'); ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_afastamento').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_afastamento').modal('hide');
                    reolad_afastamento();
                }

                $('#btnSaveAfastamento').text('Salvar'); //change button text
                $('#btnSaveAfastamento').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveAfastamento').text('save'); //change button text
                $('#btnSaveAfastamento').attr('disabled', false); //set button enable
            }
        });
    }

    function delete_afastamento(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('usuarioAfastamento/ajax_delete'); ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data) {
                    //if success reload ajax table
                    $('#modal_afastamento').modal('hide');
                    reolad_afastamento();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    function reolad_afastamento() {
        table_afastamento.ajax.reload(null, false);
    }
</script>
