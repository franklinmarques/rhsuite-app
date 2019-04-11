<?php
require_once APPPATH . "views/header.php";
?>
<style>
    .btn-success{
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
    .text-nowrap{
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
                    <li class="active">Biblioteca de questões</li>
                </ol>
                <button class="btn btn-success" onclick="add_questao()"><i class="glyphicon glyphicon-plus"></i> Adicionar modelo de questão</button>
                <button class="btn btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>
                <br />
                <br />
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Descritivo do modelo de questão</th>
                            <th>Tipo de questão</th>
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Formulario de modelo de questão</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="<?= $empresa; ?>" id="empresa" name="empresa"/>
                            <input type="hidden" value="" name="id"/> 
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Descritivo do modelo da questão</label>
                                    <div class="col-md-9">
                                        <input name="nome" placeholder="Digite o nome do modelo de questão" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Tipo de questão</label>
                                    <div class="col-md-5">
                                        <select name="tipo" class="form-control">
                                            <option value="1">Múltiplas alternativas</option>
                                            <option value="3">Múltiplas alternativas (quick quiz)</option>
                                            <option value="2">Dissertativa</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Observações</label>
                                    <div class="col-md-9">
                                        <textarea name="observacoes" class="form-control" rows="2"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-9 col-md-offset-3">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="perguntas" value="P" class="aleatorizacao">
                                                Permitir a ordenação aleatória da questão
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="alternativas" value="A" class="aleatorizacao">
                                                Permitir a exibição de alternativas em ordem aleatória
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_conteudo" role="dialog">
            <div class="modal-dialog" style="width: 98%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Editar conteúdo da questão</h3>
                    </div>
                    <div class="modal-body">
                        <div id="alert"></div>
                        <form action="#" id="form_conteudo" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>                                
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label"><strong>Instruções:</strong> digite ou cole o texto a ser interpretado na janela abaixo</label>
                                    <textarea name="conteudo" id="conteudo" class="form-control" rows="16" placeholder="Insira o texto descritivo da questão aqui"></textarea>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveConteudo" onclick="save_conteudo()" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_respostas" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Editar respostas</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form_respostas" class="form-horizontal">
                            <input type="hidden" value="" name="id_questao"/> 
                            <div class="form-body">
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <label class="control-label col-md-2">Questão</label>
                                    <div class="col-md-10">
                                        <h5 id="nome" style="overflow:hidden; text-overflow:ellipsis; white-space: nowrap;"></h5>
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <hr>
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 1</label>
                                    <div class="col-md-7">
                                        <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-1">Peso</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 2</label>
                                    <div class="col-md-7">
                                        <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-1">Peso</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 3</label>
                                    <div class="col-md-7">
                                        <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-1">Peso</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 4</label>
                                    <div class="col-md-7">
                                        <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-1">Peso</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 5</label>
                                    <div class="col-md-7">
                                        <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-1">Peso</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <div class="form-group alternativa">
                                    <input type="hidden" value="" name="id_alternativa[]"/> 
                                    <label class="control-label col-md-2">Resposta 6</label>
                                    <div class="col-md-7">
                                        <textarea name="alternativa[]" class="form-control" rows="1"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                    <label class="control-label col-md-1">Peso</label>
                                    <div class="col-md-2">
                                        <input name="peso[]" class="form-control" type="number" value="">
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Feedback resposta correta</label>
                                    <div class="col-md-10">
                                        <textarea name="feedback_correta" class="form-control" rows="1"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Feedback resposta incorreta</label>
                                    <div class="col-md-10">
                                        <textarea name="feedback_incorreta" class="form-control" rows="1"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveRespostas" onclick="save_respostas()" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

    </section>
</section>
<!--main content end-->

<?php
require_once APPPATH . "views/end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Biblioteca de questões';
    });
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/adapters/jquery.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/gravar/RecordRTC.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;
    $(document).ready(function () {
        $("#conteudo").ckeditor({
            height: '600',
            filebrowserBrowseUrl: '<?= base_url('browser/browse.php'); ?>'
        });

        //datatables
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "iDisplayLength": 25,
            "order": [], //Initial no order.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('ead/biblioteca/ajax_list/' . $empresa) ?>",
                "type": "POST",
                timeout: 9000
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '80%',
                    targets: [0]
                },
                {
                    width: '20%',
                    targets: [1],
                    visible: '<?= empty($tipo) ?>'
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ]

        });

        $('[name="tipo"]').on('change', function () {
            $('.aleatorizacao').prop('disabled', this.value === '2');
        });
    });
    
    function add_questao()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('[name="tipo"] option').prop('disabled', false);
        $('.aleatorizacao').prop('disabled', $('[name="tipo"]').val() === '2');
        $('#modal_form').modal('show'); // show bootstrap modal
        $('#modal_form .modal-title').text('Adicionar modelo de questão'); // Set Title to Bootstrap modal title
        $('.combo_nivel1').hide();
    }

    function edit_questao(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('ead/biblioteca/ajax_edit') ?>",
            type: "POST",
            dataType: "JSON",
            timeout: 9000,
            data: {
                id: id
            },
            success: function (data)
            {
                $('[name="id"]').val(data.id);
                $('[name="empresa"]').val(data.id_empresa);
                $('[name="nome"]').val(data.nome);
                $('[name="tipo"]').val(data.tipo);
                $('[name="tipo"] option').prop('disabled', true);
                $('[name="tipo"] option:selected').prop('disabled', false);
                $('[name="observacoes"]').val(data.observacoes);
                if (data.aleatorizacao === 'P' || data.aleatorizacao === 'T') {
                    $('[name="perguntas"]').prop('checked', true);
                }
                if (data.aleatorizacao === 'A' || data.aleatorizacao === 'T') {
                    $('[name="alternativas"]').prop('checked', true);
                }
                $('.aleatorizacao').prop('disabled', data.tipo === '2');
                $('#modal_form').modal('show');
                $('#modal_form .modal-title').text('Editar modelo de questão'); // Set title to Bootstrap modal title

            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_conteudo(id)
    {
        save_method = 'update';
        $('#form_conteudo')[0].reset(); // reset form on modals
        $('#form_conteudo input[type="hidden"]').val(''); // reset hidden input form on modals
        $('#form_conteudo .form-group').removeClass('has-error'); // clear error class
        $('#form_conteudo .help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('ead/biblioteca/ajax_conteudo') ?>",
            type: "POST",
            dataType: "JSON",
            timeout: 9000,
            data: {
                id: id
            },
            success: function (data)
            {
                $('[name="id"]').val(data.id);
                $('[name="conteudo"]').val(data.conteudo);
                $('#modal_conteudo').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_respostas(id)
    {
        save_method = 'update';
        $('#form_respostas')[0].reset(); // reset form on modals
        $('#form_respostas input[type="hidden"]').val(''); // reset hidden input form on modals
        $('#form_respostas .form-group').removeClass('has-error'); // clear error class
        $('#form_respostas .help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('ead/biblioteca/ajax_respostas') ?>",
            type: "POST",
            dataType: "JSON",
            timeout: 9000,
            data: {
                id: id
            },
            success: function (data)
            {
                $('#nome').text(data.nome);
                $('[name="id_questao"]').val(data.id_questao);
                $('[name="feedback_correta"]').val(data.feedback_correta);
                $('[name="feedback_incorreta"]').val(data.feedback_incorreta);
                if (data.tipo === '2') {
                    $('.alternativa').hide().find('input').val('');
                } else {
                    $('.alternativa').show();
                }

                $(data.alternativas).each(function (index, field) {
                    $('.alternativa:eq(' + index + ') input[name="id_alternativa[]"]').val(field.id);
                    $('.alternativa:eq(' + index + ') textarea[name="alternativa[]"]').val(field.alternativa);
                    $('.alternativa:eq(' + index + ') input[name="peso[]"]').val(field.peso);
                });
                $('#modal_respostas').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function save()
    {
        $('#btnSave').text('Salvando...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable 
        var url;
        if (save_method === 'add') {
            url = "<?php echo site_url('ead/biblioteca/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('ead/biblioteca/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                }

                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 
            }
        });
    }

    function save_conteudo()
    {
        $('#btnSaveConteudo').text('Salvando...'); //change button text
        $('#btnSaveConteudo').attr('disabled', true); //set button disable 

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('ead/biblioteca/salvar_conteudo') ?>",
            type: "POST",
            data: $('#form_conteudo').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_conteudo').modal('hide');
                    reload_table();
                }

                $('#btnSaveConteudo').text('Salvar'); //change button text
                $('#btnSaveConteudo').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSaveConteudo').text('Salvar'); //change button text
                $('#btnSaveConteudo').attr('disabled', false); //set button enable 
            }
        });
    }

    function save_respostas()
    {
        $('#btnSaveRespostas').text('Salvando...'); //change button text
        $('#btnSaveRespostas').attr('disabled', true); //set button disable 
        //
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('ead/biblioteca/salvar_respostas') ?>",
            type: "POST",
            data: $('#form_respostas').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_respostas').modal('hide');
                    reload_table();
                }

                $('#btnSaveRespostas').text('Salvar'); //change button text
                $('#btnSaveRespostas').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSaveRespostas').text('Salvar'); //change button text
                $('#btnSaveRespostas').attr('disabled', false); //set button enable 
            }
        });
    }

    function delete_questao(id)
    {
        if (confirm('Deseja remover?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('ead/biblioteca/ajax_delete') ?>",
                type: "POST",
                dataType: "JSON",
                data: {
                    id: id
                },
                success: function (data)
                {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    $('#alert').html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
//                    alert('Error deleting data');
                }
            });
        }
    }

</script>

<?php
require_once APPPATH . "views/end_html.php";
?>