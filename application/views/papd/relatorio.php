<?php
require_once APPPATH . "views/header.php";
?>
<style>
    /*    .modal, .modal-backdrop {
            overflow: auto;
            height: 100%;
        }    
        #main-content .modal, .modal-backdrop {
            position: absolute;
        }    
        #main-content .modal-backdrop {
            z-index: 1001;
        }    
        .wrapper {
            overflow: auto;
            position:relative;
            height: 90%;
            min-height: 600px;
        }
        #main-content {
            height: 100%;
        }*/
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div style="color: #000;">
            <div id="alert"></div>
            <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                <li class="active">Relatório do Paciente</li>
            </ol>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a id="pdf" class="btn btn-sm btn-danger" href="<?= site_url('papd/relatorios/pdfPaciente/'); ?>" title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Data início</label>
                                <input name="data_inicio" type="text" id="data_inicio" placeholder="dd/mm/aaaa" class="form-control filtro input-sm text-center">
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Data término</label>
                                <input name="data_término" type="text" id="data_termino" placeholder="dd/mm/aaaa" class="form-control filtro input-sm text-center">
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Filtrar por deficiência (HD)</label>
                                <?php echo form_dropdown('deficiencia', $deficiencia, '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Filtrar por status</label>
                                <?php echo form_dropdown('status', $status, '', 'id="status" class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label><br>
                                <div class="btn-group" role="group" aria-label="...">
                                    <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">Limpar filtros</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Filtrar por estado</label>
                                <?php echo form_dropdown('estado', $estado, '', 'id="estado" class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Filtrar por cidade</label>
                                <?php echo form_dropdown('cidade', $cidade, '', 'id="cidade" class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">Filtrar por bairro</label>
                                <?php echo form_dropdown('bairro', $bairro, '', 'id="bairro" class="form-control filtro input-sm"'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="table-responsive">-->
            <table id="table" class="table table-bordered table-condensed avaliacao">
                <thead>
                    <tr class="active">
                        <th colspan="5" class="text-center"><h4><strong>PROGRAMA DE APOIO À PESSOA COM DEFICIÊNCIA<br>RELATORIO DE MEDIÇÃO MENSAL</strong></h4></th>                            
                    </tr>
                    <tr class="active">
                        <th colspan="3" style="border-right-width: 0;"><h5><strong>Período de medição: </strong><span id="medicao_data"></span></h5></th>                            
                        <th colspan="2" class="text-right" style="border-left-width: 0;"><h5><strong>TOTAL (R$): </strong><span id="medicao_total"></span></h5></th>                            
                    </tr>
                    <tr class="active">
                        <th class="text-center">Paciente</th>
                        <th class="text-center">Atividades/Procedimentos</th>
                        <th class="text-center">Profissional</th>
                        <th class="text-center">Data/hora</th>
                        <th class="text-center">Valor (R$)</th>
                    </tr>
                </thead>
                <tbody>                
                </tbody>
            </table>
            <!--</div>-->
        </div>
    </section>
</section>

<?php
require_once APPPATH . "views/end_js.php";
?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Relatório de Medição Mensal';
    });
</script>

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

<script>
    $('#data_inicio, #data_termino').mask('00/00/0000');

    var table;

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            "info": false,
            "searching": false,
            "bLengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
//            deferRender: true,
//            scrollY: 200,
//            scrollCollapse: true,
//            scroller: true,
//            "bPaginate": true,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('papd/relatorios/ajax_medicao_mensal/') ?>",
                "type": "POST",
                data: function (d) {
                    d.data_inicio = $('#data_inicio').val();
                    d.data_termino = $('#data_termino').val();
                    d.estado = $('#estado').val();
                    d.cidade = $('#cidade').val();
                    d.bairro = $('#bairro').val();
                    d.deficiencia = $('#deficiencia').val();
                    d.status = $('#status').val();

                    return d;
                },
                "dataSrc": function (json) {
                    $('#medicao_data').html(json.medicao.data_inicio + ' a ' + json.medicao.data_termino);
                    $('#medicao_total').html(json.medicao.total);

                    return json.data;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: "20%",
                    targets: [0, 2]
                },
                {
                    width: "35%",
                    targets: [1]
                },
                {
                    className: "text-center",
                    targets: [3]
                },
                {
                    className: "text-right",
                    targets: [4]
                }
            ]
        });

        $('.filtro').on('change', function () {
            reload_table();
        });

        $('#limpa_filtro').on('click', function () {
            $(".filtro").val('');
            reload_table();
        });

    });

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax 
    }
</script>

<?php
require_once APPPATH . "views/end_html.php";
?>
