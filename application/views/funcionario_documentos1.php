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
        <button class="btn btn-info" onclick="add_documento()"><i class="glyphicon glyphicon-plus"></i> Adicionar
            novo documento do colaborador
        </button>
        <br/>
        <br/>
        <div class="table-responsive">
            <table id="table_documento" class="table table-striped table-bordered"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Tipo</th>
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
<div class="modal fade" id="modal_documento" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Formulario de Documento do Colaborador</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_documento" class="form-horizontal">
                    <input type="hidden" value="<?= $id_usuario; ?>" id="usuario" name="usuario"/>
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="row form-group">
                            <label class="control-label col-md-2">Tipo</label>
                            <div class="col-md-10">
                                <?php echo form_dropdown('tipo', $tipo, '', 'class="form-control"'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Descrição</label>
                            <div class="col-md-10">
                                <input name="descricao" placeholder="Descrição" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Arquivo</label>
                            <div class="col-md-10 controls">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecionar arquivo</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" name="arquivo" accept=".pdf"
                                               placeholder="Selecione para substitutir algum arquivo abaixo"/>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                       data-dismiss="fileinput">Remover</a>
                                </div>
                                <span class="help-inline"></span>
                                <p class="help-block">Formato permitido: .pdf (tamanho máximo: 100 Mb)</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveDocumento" onclick="save_documento()" class="btn btn-success">
                    Salvar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
    table_documento = $('#table_documento').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "language": {
            "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('documento/ajax_list1/' . $id_usuario) ?>",
            "type": "POST"
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
        ]
    });

    function add_documento() {
        save_method = 'add';
        $('#form_documento')[0].reset(); // reset form on modals
        $('#form_documento [name="id"]').val(''); // reset hidden id
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#form_documento help-inline').html('');
        $('#modal_documento').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar PDI'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_documento(id) {
        save_method = 'update';
        $('#form_documento')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('documento/ajax_edit'); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (json) {
                $('#form_documento [name="id"]').val(json.id);
                $('#form_documento [name="tipo"]').val(json.tipo);
                $('#form_documento [name="descricao"]').val(json.descricao);
                $('#form_documento .help-inline').html('<i class="fa fa-file"></i> Arquivo atual: ' + json.arquivo);

                $('#modal_documento').modal('show');
                $('#main-content .modal-title').text('Editar PDI'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save_documento() {
        $('#btnSaveDocumento').text('saving...'); //change button text
        $('#btnSaveDocumento').attr('disabled', true); //set button disable
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('documento/documentoColaborador_db'); ?>";
        } else {
            var id = $('#form_documento [name="id"]').val();
            url = "<?php echo site_url('documento/editarDocumentoOrganizacao_db'); ?>/" + id;
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_documento').serialize(),
            dataType: "JSON",
            success: function (data) {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_documento').modal('hide');
                    reolad_documento();
                }

                $('#btnSaveDocumento').text('Salvar'); //change button text
                $('#btnSaveDocumento').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveDocumento').text('save'); //change button text
                $('#btnSaveDocumento').attr('disabled', false); //set button enable
            }
        });
    }

    function delete_documento(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('documento/excluir'); ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    //if success reload ajax table
                    $('#modal_documento').modal('hide');
                    reolad_documento();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    function baixar_documento(id) {
        $.fileDownload('<?= site_url('documento/download') ?>/', {
//            preparingMessageHtml: "Preparando o arquivo solicitado, aguarde...",
//            failMessageHtml: "Erro ao baixar o arquivo, tente novamente.",
            httpMethod: "POST",
            data: {id: id}
        });
    }

    function reolad_documento() {
        table_documento.ajax.reload(null, false);
    }
</script>
