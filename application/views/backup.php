<?php
require_once "header.php";
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
    .text-warning {
        color: #f0ad4e;
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
                <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                    <li class="active">Backup do Sistema</li>
                </ol>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-10">
                <?php if ($this->session->userdata('tipo') == 'administrador'): ?>
                    <button class="btn btn-success" data-toggle="modal" data-target="#novo"><i class="glyphicon glyphicon-plus"></i> Criar novo backup</button>
                <?php else: ?>
                    <button class="btn btn-success" id="salvar" onclick="novo()"><i class="glyphicon glyphicon-plus"></i> Criar novo backup</button>
                <?php endif; ?>
                <button class="btn btn-success disabled" data-toggle="modal" data-target="#restaurar"><i class="glyphicon glyphicon-open"></i> Enviar backup local</button>
            </div>
            <!--            <div class="col-md-2 text-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#acoes">Ações especiais</button>
                        </div>-->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <br />
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Data/hora</th>
                            <th>Arquivo</th>
                            <th>Tipo de backup</th> 
                            <th>Extensão</th>                                                               
                            <th>Protegido</th>
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
        <?php if ($this->session->userdata('tipo') == 'administrador'): ?>
            <div class="modal fade" id="novo" role="dialog">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Criar novo backup</h3>
                        </div>
                        <div class="modal-body form">
                            <div class="row form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="tipo" value="ftp" checked>
                                            Arquivos e diretórios
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="tipo" value="sql">
                                            Banco de dados (.sql)
                                        </label>
                                    </div>
                                    <div class="radio disabled">
                                        <label>
                                            <input type="radio" name="tipo" value="json">
                                            Banco de dados (.json)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!--                        <div class="row form-group">
                                                        <div class="col-sm-10 col-sm-offset-2">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="password" type="checkbox" value="">
                                                                    Compactar e proteger o arquivo com senha
                                                                </label>
                                                            </div>
                                                            <div class="checkbox disabled">
                                                                <label>
                                                                    <input name="baixar" type="checkbox" value="" checked="">
                                                                    Baixar arquivo ao salvar
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>-->
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="salvar" onclick="save()" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        <?php endif; ?>
        <!-- /.modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="restaurar" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Restaurar backup</h3>
                    </div>
                    <div class="modal-body">
                        <?php echo form_open_multipart('backup/restaurar/', 'id="form_restaurar" class="form-horizontal ajax-upload"'); ?>
                        <div class="row">
                            <label class="control-label col-md-3">Arquivo de restauração</label>
                            <div class="col-md-9">                                
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecionar arquivo</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" name="arquivo" accept=".sql"/>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>
                                </div>                                
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <!--                        <div class="row form-group">
                                                    <label class="control-label col-md-3">Senha para descompactação *</label>
                                                    <div class="col-md-6">
                                                        <input name="nome" placeholder="Digite a senha do arquivo aqui" class="form-control" type="password">
                                                        <span class="help-block"><i>* Somente para arquivos compactados e protegidos com senha</i></span>
                                                    </div>
                                                </div>-->
                        <div class="modal-footer">
                            <button type="button" id="btnSave" onclick="restaura_do_arquivo()" class="btn btn-primary">Restaurar</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="acoes" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Ações especiais</h3>
                    </div>
                    <div class="modal-body form">
                        <div class="row">
                            <div class="col-sm-3 col-sm-offset-1">
                                <div class="form-group">
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                            Baixar tudo
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                            Limpar tudo
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input name="password" type="checkbox" value="" checked="">
                                            Backup de arquivos e diretórios
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="baixar" type="checkbox" value="" checked="">
                                            Backup de banco de dados
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-11 col-sm-offset-1">
                                <div class="form-inline form-group">
                                    <label for="valor_min">Criados a partir de </label>
                                    <input type="text" class="form-control text-center" name="data_inicio" id="data_inicio" placeholder="dd/mm/aaaa" style="width: 120px;">
                                    <label for="valor_max"> até </label>
                                    <input type="text" class="form-control text-center" name="data_termino" id="data_termino" placeholder="dd/mm/aaaa" style="width: 120px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave2" onclick="acoes()" class="btn btn-primary">Ok</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </section>
</section>
<!--main content end-->

<?php
require_once "end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Backup do Sistema';
    });</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.fileDownload-master/src/Scripts/jquery.fileDownload.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;

    $('#data_inicio, #data_termino').mask('00/00/0000');

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            "info": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [[0, "desc"]],
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('backup/ajax_list') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    className: "text-nowrap",
                    targets: [0, 2, 5]
                },
                {
                    className: "text-center",
                    targets: [0, 4]
                },
                {
                    width: '100%',
                    targets: [1]
                },
                {
                    visible: false,
                    targets: [4]
                },
                {
                    "targets": [2, 3, 4, 5], //last column
                    "searchable": false //set not orderable
                },
                {
                    "targets": [-1], //last column
                    "orderable": false //set not orderable
                }
            ]
        });

        $("#tipo").change(function () {
            var id = $("#tipo option:selected").attr('id');

            if (id === 'codigo_fonte') {
                $('#salvar').removeClass('disabled');
                $('#salvar').attr('href', '<?= site_url('backup/ftp'); ?>');

                $('#div_formato').attr('style', 'display:none');
            } else if (id === 'banco_dados') {
                $('#salvar').addClass('disabled');
                $('#div_formato').attr('style', 'display:block');
            }
        });

        $('#formato').change(function () {
            var formato = $("#formato option:selected").attr('id');

            $('#salvar').removeClass('disabled');
            $('#salvar').attr('href', '<?= site_url('backup/mysql'); ?>?tipo=' + formato);
        });

        $(document).on("click", "#salvar", function () {
            $.fileDownload($(this).prop('href'))
                    .done(function () {
                        alert('Download efetuado com sucesso!');
                    })
                    .fail(function () {
                        alert('Erro no download do arquivo!');
                    });

            return false; //this is critical to stop the click event which will trigger a normal file download
        });

    });

    function baixar_backup(filename) {
        $.fileDownload('<?= site_url('backup/baixar') ?>/', {
//            preparingMessageHtml: "Preparando o arquivo solicitado, aguarde...",
//            failMessageHtml: "Erro ao baixar o arquivo, tente novamente.",
            httpMethod: "POST",
            data: {filename: filename}
        });
    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function novo() {
        $.fileDownload('<?= site_url('backup/mysql'); ?>?tipo=sql').success(function () {
            reload_table();
            alert('Download efetuado com sucesso!');
        }).fail(function () {
            alert('Erro no download do arquivo!');
        });

        return false;
    }

    function save() {
        var tipo = $('[name="tipo"]:checked').val();
        var url = '';

        if (tipo === 'sql' || tipo === 'json') {
            url = '<?= site_url('backup/mysql'); ?>?tipo=' + tipo;
        } else if (tipo === 'ftp') {
            url = '<?= site_url('backup/ftp'); ?>';
        }

        $('#novo').modal('hide');

        $.fileDownload(url).done(function () {
            reload_table();
            alert('Download efetuado com sucesso!');
        }).fail(function () {
            alert('Erro no download do arquivo!');
        });

        return false;
    }

    function backup_delete(filename) {
        if (confirm('Deseja remover?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('backup/ajax_delete') ?>/",
                type: "POST",
                dataType: "JSON",
                data: {filename: filename},
                success: function (data)
                {
                    //if success reload ajax table
                    if (data.status) {
                        reload_table();
                    } else {
                        alert('Não foi possível excluir o arquivo');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }

    function restaura_backup(filename) {
        if (confirm('Deseja restaurar a base de dados atual?'))
        {
            $.ajax({
                url: "<?php echo site_url('backup/restaurar') ?>/",
                type: "POST",
                dataType: "JSON",
                data: {filename: filename},
                success: function (data)
                {
                    //if success reload ajax table
                    if (data.status === true) {
                        reload_table();
                    } else if (data.status === false) {
                        alert('Não foi possível restaurar o backup');
                    } else {
                        alert(data.status);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }

    function restaura_do_arquivo() {
        $('#restaurar').modal('hide');
        if (confirm('Deseja restaurar a base de dados atual?'))
        {
            $("#form_restaurar").submit(function () {
                $.ajax({
                    url: "<?php echo site_url('backup/restaurar') ?>/",
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData(this),
                    success: function (data)
                    {
                        //if success reload ajax table
                        if (data.status === true) {
                            reload_table();
                        } else if (data.status === false) {
                            alert('Não foi possível restaurar o backup');
                        } else {
                            alert(data.status);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error deleting data');
                    }
                });
            });
        }
    }

</script>

<?php
require_once "end_html.php";
?>