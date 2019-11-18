<?php
require_once APPPATH . "views/header.php";
?>
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
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div id="alert"></div>
            <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                <li class="active">Relatório de Medição Mensal Consolidado</li>
            </ol>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="well well-sm">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Data início</label>
                                <input name="data_inicio" type="text" id="data_inicio" placeholder="dd/mm/aaaa"
                                       class="form-control filtro input-sm text-center">
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Data término</label>
                                <input name="data_termino" type="text" id="data_termino" placeholder="dd/mm/aaaa"
                                       class="form-control filtro input-sm text-center">
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Filtrar por deficiência (HD)</label>
                                <?php echo form_dropdown('deficiencia', $deficiencia, '', 'id="deficiencia" class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-2">
                                <label class="control-label">Filtrar por status</label>
                                <?php echo form_dropdown('status', $status, '', 'id="status" class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Filtrar por contrato</label>
                                <?php echo form_dropdown('contrato', $contrato, '', 'id="contrato" class="form-control filtro input-sm"'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Filtrar por estado</label>
                                <?php echo form_dropdown('estado', $estado, '', 'id="estado" class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label">Filtrar por cidade</label>
                                <?php echo form_dropdown('cidade', $cidade, '', 'id="cidade" class="form-control filtro input-sm"'); ?>
                            </div>
                            <!--<div class="col-md-4">
                                <label class="control-label">Filtrar por bairro</label>
                                <?php /*echo form_dropdown('bairro', $bairro, '', 'id="bairro" class="form-control filtro input-sm"'); */ ?>
                            </div>-->
                            <div class="col-md-4">
                                <label class="control-label">Filtrar por profissional</label>
                                <?php echo form_dropdown('profissional', $profissional, '', 'id="profissional" class="form-control filtro input-sm"'); ?>
                            </div>
                            <div class="col-md-3 text-center">
                                <label>&nbsp;</label><br>
                                <button type="button" id="pesquisar" class="btn btn-sm btn-default"><i
                                            class="glyphicon glyphicon-search"></i> Pesquisar
                                </button>
                                <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">Limpar filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <img src="<?= base_url($foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    <p class="text-left">
                        <img src="<?= base_url($foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </div>
            </div>
            <table class="table table-condensed table-condensed avaliacao">
                <thead>
                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="3">
                        <h3 class="text-center">PROGRAMA DE APOIO À PESSOA COM DEFICIÊNCIA<br>RELATORIO DE MEDIÇÃO
                            MENSAL CONSOLIDADO</h3>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
                    <td colspan="2">
                        <h5><strong>Data atual: </strong><?= date('d/m/Y') ?></h5>
                    </td>
                    <td class="text-right">
                        <a id="pdf" class="btn btn-sm btn-danger"
                           href="<?= site_url('papd/relatorios/pdfMedicao_mensal/'); ?>" title="Exportar PDF"><i
                                    class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    </td>
                </tr>
                <tr style='border-bottom: 5px solid #ddd;'>
                    <td style="padding: 0px;">
                        <h5><strong>Período de medição: </strong><span id="medicao_inicio"></span> a <span
                                    id="medicao_termino"></span></h5>
                    </td>
                    <td style="padding: 0px;">
                        <h5><strong>Número de atendimentos no período: </strong><span
                                    id="medicao_qtde_atendimentos"></span>
                        </h5>
                    </td>
                    <td style="padding: 0px;" class="text-right">
                        <h5><strong>VALOR TOTAL (R$): </strong><span id="medicao_total"></span></h5>
                    </td>
                </tr>
                </tbody>
            </table>
            <table id="table" class="table table-bordered table-condensed">
                <thead>
                <tr class='active'>
                    <th>Funcionário(a)</th>
                    <th class="text-center">Número de atendimentos</th>
                    <th class="text-center">Valor receita gerada (R$)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
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
                "processing": true,
                "serverSide": true,
                "iDisplayLength": -1,
                "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                "order": [[0, 'asc']],
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('papd/relatorios/ajax_medicao_consolidada/') ?>",
                    "type": "POST",
                    data: function (d) {
                        d.data_inicio = $('#data_inicio').val();
                        d.data_termino = $('#data_termino').val();
                        d.estado = $('#estado').val();
                        d.cidade = $('#cidade').val();
                        // d.bairro = $('#bairro').val();
                        d.profissional = $('#profissional').val();
                        d.deficiencia = $('#deficiencia').val();
                        d.status = $('#status').val();
                        d.contrato = $('#contrato').val();

                        return d;
                    },
                    "dataSrc": function (json) {
                        $('#medicao_inicio').html(json.medicao.data_inicio);
                        $('#medicao_termino').html(json.medicao.data_termino);
                        $('#medicao_qtde_atendimentos').html(json.medicao.qtde_atendimentos);
                        $('#medicao_total').html(json.medicao.total);

                        setPdf_atributes();

                        return json.data;
                    }
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: "60%",
                        targets: [0]
                    },
                    {
                        className: "text-center",
                        searchable: false,
                        targets: [1, 2]
                    }
                ]
            });
        });

        $('#pesquisar').on('click', function () {
            reload_table();
            setPdf_atributes();
        });

        $('#limpa_filtro').on('click', function () {
            $(".filtro").val('');
            reload_table();
        });

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function setPdf_atributes() {
            var search = '';
            var q = new Array();

            $('.filtro').each(function (i, v) {
//            if (i === 0){
//                q[i] = v.name + "=" + $('#medicao_inicio').html();
//            } else if (i === 1){
//                q[i] = v.name + "=" + $('#medicao_termino').html();
//            } else 
                if (v.value.length > 0 && (v.value !== 'Todos' || v.value !== 'Todas')) {
                    q[i] = v.name + "=" + v.value;
                }
            });
            q[q.length] = 'order[0]=' + (table.order()[0][0] + 1) + '&order[1]=' + table.order()[0][1];

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('papd/relatorios/pdfMedicao_consolidada/'); ?>" + search);
        }

    </script>

<?php
require_once APPPATH . "views/end_html.php";
?>
