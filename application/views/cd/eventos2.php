<?php require_once APPPATH . 'views/header.php'; ?>

<section id="main-content">
    <section class="wrapper">

        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                    <li><a href="<?= site_url('cd/apontamento') ?>">Apontamentos diários</a></li>
                    <li class="active">Gerenciar eventos de apontamento</li>
                </ol>
                <button class="btn btn-success" onclick="add_evento()"><i class="glyphicon glyphicon-plus"></i> Adicionar evento</button>
                <button class="btn btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>
                <br />
                <br />
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome do evento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Formulario de evento de apontamento</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                            <input type="hidden" value="" name="id"/> 
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Código evento</label>
                                    <div class="col-md-5">
                                        <input name="codigo" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Nome evento</label>
                                    <div class="col-md-9">
                                        <input name="nome" placeholder="Digite o nome do evento" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

    </section>
</section>

<?php require_once APPPATH . 'views/end_js.php'; ?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar Eventos de Cuidadores';
    });
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

<script>

    var save_method;
    var table;

    $(document).ready(function () {

        table = $('#table').DataTable({
            info: false,
            processing: true,
            serverSide: true,
            order: [],
            language: {
                url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            ajax: {
                url: '<?php echo site_url('cd/eventos/ajax_list/') ?>',
                type: 'POST'
            },
            columnDefs: [
                {
                    width: '80%',
                    targets: [1]
                },
                {
                    className: 'text-nowrap',
                    targets: [-1],
                    orderable: false,
                    searchable: false
                }
            ]
        });

    });

    function add_evento()
    {
        save_method = 'add';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#modal_form').modal('show');
        $('.modal-title').text('Adicionar evento de apontamento');
        $('.combo_nivel1').hide();
    }

    function edit_evento(id)
    {
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            url: '<?php echo site_url('cd/eventos/ajax_edit') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {id: id},
            success: function (json)
            {
                $.each(json, function (key, value) {
                    $('[name="' + key + '"]').val(value);
                });
                $('#modal_form').modal('show');
                $('.modal-title').text('Editar evento de apontamento');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });

    }

    function reload_table()
    {
        table.ajax.reload(null, false);
    }

    function save()
    {
        $('#btnSave').text('Salvando...');
        $('#btnSave').attr('disabled', true);
        var url;

        if (save_method === 'add') {
            url = '<?php echo site_url('cd/eventos/ajax_add') ?>';
        } else {
            url = '<?php echo site_url('cd/eventos/ajax_update') ?>';
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: $('#form').serialize(),
            dataType: 'JSON',
            success: function (data)
            {
                if (data.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
                }

                $('#btnSave').text('Salvar');
                $('#btnSave').attr('disabled', false);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('Salvar');
                $('#btnSave').attr('disabled', false);
            }
        });
    }

    function delete_evento(id)
    {
        if (confirm('Deseja remover?'))
        {
            $.ajax({
                url: '<?php echo site_url('cd/eventos/ajax_delete') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {id: id},
                success: function (data)
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    alert('Error deleting data');
                }
            });

        }
    }

</script>

<?php require_once APPPATH . 'views/end_html.php'; ?>