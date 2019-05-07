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
        <button class="btn btn-info" onclick="add_contrato()"><i class="glyphicon glyphicon-plus"></i> Adicionar
            novo contrato do colaborador
        </button>
        <br/>
        <br/>
        <div class="table-responsive">
            <table id="table_contrato" class="table table-striped table-bordered"
                   cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Contrato</th>
                    <th nowrap>Data assinatura</th>
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
<div class="modal fade" id="modal_contrato" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Formulario de Contrato do Colaborador</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_contrato" class="form-horizontal">
                    <input type="hidden" value="<?= $id_usuario; ?>" name="id_usuario"/>
                    <div class="form-body">
                        <div class="row form-group">
                            <label class="control-label col-md-2">Contrato</label>
                            <div class="col-md-10">
                                <input name="contrato" placeholder="Nome do contrato" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Data assinatura</label>
                            <div class="col-md-3">
                                <input name="data_assinatura" placeholder="dd/mm/aaaa"
                                       class="form-control text-center data" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveContrato" onclick="save_contrato()" class="btn btn-success">
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
    table_contrato = $('#table_contrato').DataTable({
        'processing': true,
        'serverSide': true,
        'language': {
            'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
        },
        'ajax': {
            'url': '<?php echo site_url('usuarioContratos/ajaxList/') ?>',
            'type': 'POST',
            'data': function (d) {
                d.id_usuario = '<?= $id_usuario ?>';
                return d;
            }
        },
        'columnDefs': [
            {
                'width': '100%',
                'targets': [0]
            },
            {
                'class': 'text-center',
                'targets': [1]
            },
            {
                'className': 'text-center text-nowrap',
                'targets': [-1],
                'orderable': false,
                'searchable': false
            }
        ]
    });

    function add_contrato() {
        save_method = 'add';
        $('#form_contrato')[0].reset(); // reset form on modals
        $('#form_contrato [name="id"]').val(''); // reset hidden id
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#form_contrato help-inline').html('');
        $('#modal_contrato').modal('show'); // show bootstrap modal
        $('.modal-title').text('Adicionar contrato'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_contrato(id) {
        save_method = 'update';
        $('#form_contrato')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('usuarioContratos/ajaxEdit'); ?>',
            'type': 'GET',
            'dataType': 'json',
            'data': {'id': id},
            'success': function (json) {
                $('#form_contrato [name="id"]').val(json.id);
                $('#form_contrato [name="contrato"]').val(json.contrato);
                $('#form_contrato [name="data_assinatura"]').val(json.data_assinatura);

                $('#modal_contrato').modal('show');
                $('#main-content .modal-title').text('Editar contrato'); // Set title to Bootstrap modal title

            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save_contrato() {
        $('#btnSaveContrato').text('Salvando...'); //change button text
        $('#btnSaveContrato').attr('disabled', true); //set button disable
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('usuarioContratos/ajaxAdd'); ?>";
        } else {
            var id = $('#form_contrato [name="id"]').val();
            url = "<?php echo site_url('usuarioContratos/ajaxUpdate'); ?>/" + id;
        }

        // ajax adding data to database
        $.ajax({
            'url': url,
            'type': 'POST',
            'data': $('#form_contrato').serialize(),
            'dataType': 'json',
            'success': function (json) {
                if (json.status) {
                    $('#modal_contrato').modal('hide');
                    reolad_contrato();
                } else if (json.erro) {
                    alert(json.erro);
                }

                $('#btnSaveContrato').text('Salvar').attr('disabled', false);
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveContrato').text('Salavr').attr('disabled', false);
            }
        });
    }

    function delete_contrato(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                'url': '<?php echo site_url('usuarioContratos/ajaxDelete'); ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {'id': id},
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        reolad_contrato();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    function baixar_contrato(id) {
        $.fileDownload('<?= site_url('usuarioContratos/download') ?>/', {
//            preparingMessageHtml: "Preparando o arquivo solicitado, aguarde...",
//            failMessageHtml: "Erro ao baixar o arquivo, tente novamente.",
            httpMethod: "POST",
            data: {id: id}
        });
    }

    function reolad_contrato() {
        table_contrato.ajax.reload(null, false);
    }
</script>
