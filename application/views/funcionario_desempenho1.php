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
        <button class="btn btn-info" onclick="add_desempenho()"><i class="glyphicon glyphicon-plus"></i> Adicionar nova
            avaliação periódica de desempenho
        </button>
        <br/>
        <br/>
        <table id="table_desempenho" class="table table-striped table-bordered"
               cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Nome avaliação</th>
                <th>Modelo avaliação</th>
                <th>Data início</th>
                <th>Data término</th>
                <th>Cargo/função</th>
                <th>Depto/área/setor</th>
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
<div class="modal fade" id="modal_desempenho" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Formulario de Avaliação Periódica de Desempenho</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_desempenho" class="form-horizontal">
                    <input type="hidden" value="<?= $id_usuario; ?>" id="usuario" name="usuario"/>
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">PDI</label>
                            <div class="col-md-10">
                                <input name="nome" placeholder="Digite o nome do PDI" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Descrição</label>
                            <div class="col-md-10">
                                <textarea name="descricao" class="form-control" rows="1"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Data de início</label>
                                    <div class="col-md-6">
                                        <input name="data_inicio" placeholder="dd/mm/aaaa" size="7"
                                               class="data form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Data de término</label>
                                    <div class="col-md-6">
                                        <input name="data_termino" placeholder="dd/mm/aaaa" size="7"
                                               class="data form-control" type="text">
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
                        <div class="form-group">
                            <label class="control-label col-md-2">Obs.:</label>
                            <div class="col-md-10">
                                <textarea name="observacao" class="form-control" rows="1"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveDesempenho" onclick="save_desempenho()" class="btn btn-success">
                    Salvar
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>
    var table;
    var save_method; //for save method string

    $('.data').mask('00/00/0000');

    //datatables
    table = $('#table_desempenho').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "language": {
            "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('funcionario/ajax_integracao/' . $id_usuario) ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            {
                className: "text-nowrap",
                "targets": [-1], //last column
                "orderable": false, //set not orderable
                "searchable": false //set not orderable
            }
        ]
    });

    function add_desempenho() {
        save_method = 'add';
        $('#form_desempenho')[0].reset(); // reset form on modals
        $('#form_desempenho [name="id"]').val(''); // reset hidden id
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_desempenho').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar PDI'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_desempenho(id) {
        save_method = 'update';
        $('#form_desempenho')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('pdi/ajax_edit'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                $('[name="id"]').val(data.id);
                $('[name="nome"]').val(data.nome);
                $('[name="data_inicio"]').val(data.data_inicio);
                $('[name="data_termino"]').val(data.data_termino);
                $('[name="descricao"]').val(data.descricao);
                $('[name="observacao"]').val(data.observacao);
                $('[name="status"]').val(data.status);

                $('#modal_form').modal('show');
                $('#main-content .modal-title').text('Editar PDI'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save_desempenho() {
        $('#btnSave').text('saving...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('pdi/ajax_add'); ?>";
        } else {
            url = "<?php echo site_url('pdi/ajax_update'); ?>";
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
                    reolad_desempenho();
                }

                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable
            }
        });
    }

    function delete_desempenho(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('pdi/ajax_delete'); ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reolad_desempenho();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    function reolad_desempenho() {
        $('.glyphicon-search').trigger('click');
    }
</script>
