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
                    <li class="active">Gerenciar pacientes</li>
                </ol>
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-success" href="<?= site_url('apontamento_pacientes/cadastro') ?>"><i class="glyphicon glyphicon-plus"></i> Cadastrar novo</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="well well-sm">
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="control-label">Filtrar por deficiência</label>
                                    <?php echo form_dropdown('deficiencia', $deficiencia, '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Filtrar por status</label>
                                    <?php echo form_dropdown('status', $status, '', 'id="status" class="form-control filtro input-sm"'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Filtrar por estado</label>
                                    <?php echo form_dropdown('estado', $estado, '', 'id="estado" class="form-control filtro input-sm"'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label><br>
                                    <div class="btn-group" role="group" aria-label="...">
                                        <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">Limpar filtros</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <label class="control-label">Filtrar por cidade</label>
                                    <?php echo form_dropdown('cidade', $cidade, '', 'id="cidade" class="form-control filtro input-sm"'); ?>
                                </div>
                                <div class="col-md-5">
                                    <label class="control-label">Filtrar por bairro</label>
                                    <?php echo form_dropdown('bairro', $bairro, '', 'id="bairro" class="form-control filtro input-sm"'); ?>
                                </div>                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table" class="table table-hover" cellspacing="0" width="calc(100%)" style="border-radius: 0 !important;">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Status</th>
                                <th>Deficiência</th>
                                <th class="text-center">Data ingresso</th>
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

    </section>
</section>
<!--main content end-->

<?php
require_once "end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">


<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar pacientes';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var table;

    $(document).ready(function () {
        //datatables        
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "iDisplayLength": 25,
            scrollY: '100%',
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('apontamento_pacientes/ajax_list') ?>",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.estado = $('#estado').val();
                    d.cidade = $('#cidade').val();
                    d.bairro = $('#bairro').val();
                    d.deficiencia = $('#deficiencia').val();
                    return d;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '40%',
                    "targets": [0, 2] //last column
                },
                {
                    width: '20%',
                    "targets": [1] //last column
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false
                }
            ]
        });

        $('.filtro').on('change', function () {
            reload_table();
        });

        $('#limpa_filtro').on('click', function () {
            $('.filtro').val('');
            reload_table();
        });
    });

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function delete_paciente(id) {
        if (confirm('Deseja remover?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('apontamento_pacientes/ajax_delete') ?>",
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
                    alert('Error deleting data');
                }
            });
        }
    }

</script>

<?php
require_once "end_html.php";
?>