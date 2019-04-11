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
        <button class="btn btn-info" onclick="add_treinamento()"><i class="glyphicon glyphicon-plus"></i> Adicionar novo
            treinamento
        </button>
        <button id="email" class="btn btn-warning" onclick="enviar_email()"
                title="Enviar e-mail de convocação"><i
                    class="glyphicon glyphicon-envelope"></i>
            Enviar e-mail de convocação
        </button>
    </div>
    <div class="col-md-12">
        <br>
        <div class="form-group">
            <label class="control-label col-sm-2">Texto de e-mail</label>
            <div class="col-sm-10">
                <textarea name="mensagem" rows="2" class="form-control">Caro colaborador, você está convocado para realizar treinamento na data de: dd/mm/aaaa. Favor verificar com o Departamento de Gestão de Pessoas.</textarea>
            </div>
        </div>
    </div>
</div>
<br/>
<table id="table_treinamento" class="table table-striped table-bordered"
       cellspacing="0" width="100%">
    <thead>
    <tr>
        <th>Nome treinamento</th>
        <th>Tipo</th>
        <th>Local</th>
        <th>Data início</th>
        <th>Data término</th>
        <th>Avaliação final</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<!-- page end-->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_treinamento" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title">Formulario de Treinamento</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_treinamento" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <input type="hidden" value="<?= $id_usuario; ?>" name="usuario"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">Tipo treinamento</label>
                            <div class="col-md-3">
                                <label class="radio-inline">
                                    <input type="radio" name="tipo_treinamento" value="P" checked="">
                                    Presencial
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tipo_treinamento" value="E"> EAD
                                </label>
                            </div>
                            <label class="control-label col-md-2">Local</label>
                            <div class="col-md-3">
                                <label class="radio-inline">
                                    <input type="radio" name="local_treinamento" value="I" class="input_presencial">
                                    Interno
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="local_treinamento" value="E" class="input_presencial">
                                    Externo
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Nome treinamento</label>
                            <div class="col-sm-10 controls">
                                <div class="presencial">
                                    <input name="nome" placeholder="Nome de treinamento presencial" class="form-control"
                                           type="text">
                                </div>
                                <div class="ead" style="display: none;">
                                    <?php echo form_dropdown('id_curso', array('' => 'selecione...'), '', 'class="form-control"'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Carga horária</label>
                            <div class="col-md-2">
                                <input class="hora form-control text-center" placeholder="hh:mm"
                                       name="carga_horaria_presencial" type="text">
                            </div>
                            <label class="control-label col-md-2">Período realização</label>
                            <div class="col-md-5">
                                <div class="form-inline form-group" style="padding-left: 15px;">
                                    <label for="data_inicio" style="font-weight: normal"> De </label>
                                    <input class="data form-control text-center" name="data_inicio"
                                           placeholder="dd/mm/aaaa" value="" style="width: 150px;" type="text">
                                    <label for="data_maxima" style="font-weight: normal"> até </label>
                                    <input class="data form-control text-center" name="data_maxima"
                                           placeholder="dd/mm/aaaa" value="" style="width: 150px;" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Avaliação final</label>
                            <div class="col-md-2">
                                <input class="data form-control text-center input_presencial"
                                       name="avaliacao_presencial"
                                       type="number"
                                       value="" min="0" max="100">
                            </div>
                            <label class="control-label col-md-4">Nota mínima para emitir certificado</label>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <input name="nota_aprovacao" id="nota_aprovacao" value="" size="3" min="0" max="100"
                                           class="form-control text-right" type="number">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Fornecedor/palestrante</label>
                            <div class="col-sm-10 controls">
                                <input name="nome_fornecedor" placeholder="Nome do fornecedor"
                                       class="form-control input_presencial" type="text">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveTreinamento" onclick="save_treinamento()" class="btn btn-success">
                    Salvar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>
    var table_treinamento;
    var save_method; //for save method string

    $('.data').mask('00/00/0000');
    $('.hora').mask('#00:00', {
        translation: {
            '#': {pattern: /\d/, optional: true}
        },
        reverse: true
    });

    //datatables
    table_treinamento = $('#table_treinamento').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "iDisplayLength": 100,
        "lengthMenu": [[5, 10, 25, 50, 100, 250, 500], [5, 10, 25, 50, 100, 250, 500]],
        "language": {
            "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('ead/cursos_funcionario/ajax_list/' . $id_usuario) ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            {
                width: '100%',
                targets: [0]
            },
            {
                className: 'text-center',
                targets: [1, 2, 4, 5]
            },
            {
                className: "text-nowrap",
                "targets": [-1], //last column
                "orderable": false, //set not orderable
                "searchable": false //set not orderable
            }
        ]
    });

    $('[name="tipo_treinamento"]').on('change', function () {
        if (this.value === 'P') {
            $('.ead').hide();
            $('.presencial').show();
            $('.input_presencial').prop('disabled', false);
        } else if (this.value === 'E') {
            $('.presencial').hide();
            $('.ead').show();
            $('.input_presencial').prop('disabled', true);
        }
    });

    function add_treinamento() {
        save_method = 'add';
        $('#form_treinamento')[0].reset(); // reset form on modals
        $('#form_treinamento [name="id"]').val(''); // reset hidden id
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        $.ajax({
            url: "<?php echo site_url('ead/cursos_funcionario/getCursos/' . $id_usuario); ?>/",
            type: "GET",
            dataType: "JSON",
            success: function (json) {
                $('#form_treinamento [name="tipo_treinamento"][value="P"]').prop('checked', true).trigger('change');
                $('#form_treinamento [name="local_treinamento"][value="I"]').prop('checked', true);
                $('#form_treinamento [name="id_curso"]').html($(json.cursos).html());

                $('#modal_treinamento').modal('show');
                $('#main-content .modal-title').text('Adicionar Treinamento'); // Set title to Bootstrap modal title
                $('.combo_nivel1').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_treinamento(id) {
        save_method = 'update';
        $('#form_treinamento')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('ead/cursos_funcionario/getCursos/' . $id_usuario); ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (json) {
                if (json.tipo_treinamento !== null) {
                    if (json.tipo_treinamento.length > 0) {
                        $('#form_treinamento [name="tipo_treinamento"][value="' + json.tipo_treinamento + '"]').prop('checked', true).trigger('change');
                    }
                }

                $('#form_treinamento [name="id"]').val(json.id);
                $('#form_treinamento [name="nome"]').val(json.nome);
                $('#form_treinamento [name="id_curso"]').html($(json.cursos).html());
                $('#form_treinamento [name="data_inicio"]').val(json.data_inicio);
                $('#form_treinamento [name="data_maxima"]').val(json.data_maxima);
                $('#form_treinamento [name="nota_aprovacao"]').val(json.nota_aprovacao);
                $('#form_treinamento [name="carga_horaria_presencial"]').val(json.carga_horaria_presencial);
                $('#form_treinamento [name="avaliacao_presencial"]').val(json.avaliacao_presencial);
                $('#form_treinamento [name="nome_fornecedor"]').val(json.nome_fornecedor);

                $('#modal_treinamento').modal('show');
                $('#main-content .modal-title').text('Editar Treinamento'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function save_treinamento() {
        $('#btnSaveTreinamento').text('saving...'); //change button text
        $('#btnSaveTreinamento').attr('disabled', true); //set button disable
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('ead/cursos_funcionario/ajax_add/' . $id_usuario); ?>";
        } else {
            url = "<?php echo site_url('ead/cursos_funcionario/ajax_update/' . $id_usuario); ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form_treinamento').serialize(),
            dataType: "JSON",
            success: function (json) {
                if (json.retorno) //if success close modal and reload ajax table
                {
                    $('#modal_treinamento').modal('hide');
                    reolad_treinamento();
                }

                $('#btnSaveTreinamento').text('Salvar'); //change button text
                $('#btnSaveTreinamento').attr('disabled', false); //set button enable
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
                $('#btnSaveTreinamento').text('save'); //change button text
                $('#btnSaveTreinamento').attr('disabled', false); //set button enable
            }
        });
    }

    function delete_treinamento(id) {
        if (confirm('Deseja remover?')) {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('ead/cursos_funcionario/ajax_delete'); ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function (data) {
                    //if success reload ajax table
                    $('#modal_treinamento').modal('hide');
                    reolad_treinamento();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        }
    }

    function enviar_email() {
        $.ajax({
            url: "<?php echo site_url('ead/cursos_funcionario/enviarEmail') ?>",
            type: "POST",
            data: {
                id: '<?= $id_usuario ?>',
                mensagem: $('[name="mensagem"]').val()
            },
            dataType: "JSON",
            success: function (data) {
                if (data.status) {
                    alert('E-mail de convocação enviado com sucesso');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Erro ao enviar e-mail de convocação');
            }
        });
    }

    function reolad_treinamento() {
        table_treinamento.ajax.reload(null, false);
    }
</script>
