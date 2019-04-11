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
                <li class="active">Relatório de Medição Consolidado Anual</li>
            </ol>
            <br>
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
                    <th colspan="2">
                        <h3 class="text-center">PROGRAMA DE APOIO À PESSOA COM DEFICIÊNCIA<br>RELATORIO DE MEDIÇÃO
                            CONSOLIDADO ANUAL</h3>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd; border-bottom: 1px solid #ddd;'>
                    <td>
                        <h5><strong>Data atual: </strong><?= date('d/m/Y') ?></h5>
                    </td>
                    <td class="text-right">
                        <button class="btn btn-sm btn-success" onclick="calcular_valores()" title="Calcular valores">
                            Calcular valores
                        </button>
                        <a id="pdf" class="btn btn-sm btn-danger"
                           href="<?= site_url('papd/relatorios/pdfMedicao_anual/'); ?>" title="Exportar PDF"><i
                                    class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    </td>
                </tr>
                <tr style='border-bottom: 5px solid #ddd;'>
                    <td colspan="2">
                        <div class="row">
                            <div class="col-sm-5 form-inline">
                                <label class="control-label">Período de medição:&nbsp;</label>
                                <div class="form-control-static">Janeiro a Dezembro de <span
                                            id="medicao_ano"><?= date('Y'); ?></span></div>
                            </div>
                            <div class="col-sm-2 form-inline">
                                <label class="control-label">Ano:&nbsp;</label>
                                <input name="ano" type="text" id="ano" placeholder="aaaa" value="<?= date('Y'); ?>"
                                       class="form-control filtro input-sm text-center" style="width:70px;"
                                       autocomplete="off">
                            </div>
                            <div class="col-sm-5 form-inline">
                                <button type="button" id="pesquisar" class="btn btn-sm btn-default"><i
                                            class="glyphicon glyphicon-search"></i> Pesquisar
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <table id="table" class="table table-bordered table-condensed" width="100%">
                <thead>
                <tr class='active'>
                    <th>Atividades desenvolvidas</th>
                    <th class="text-center">Jan</th>
                    <th class="text-center">Fev</th>
                    <th class="text-center">Mar</th>
                    <th class="text-center">Abr</th>
                    <th class="text-center">Mai</th>
                    <th class="text-center">Jun</th>
                    <th class="text-center">Jul</th>
                    <th class="text-center">Ago</th>
                    <th class="text-center">Set</th>
                    <th class="text-center">Out</th>
                    <th class="text-center">Nov</th>
                    <th class="text-center">Dez</th>
                    <th class="text-center">Total</th>
                    <th class="text-center text-nowrap">Total %</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
            document.title = 'CORPORATE RH - LMS - Relatório de Medição Consolidado Anual';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>
    <script src="<?php echo base_url('assets/js/moment.js'); ?>"></script>

    <script>
        $('#ano').mask('0000');

        var table;
        var ano = <?= date('Y'); ?>

            $(document).ready(function () {

                //datatables
                table = $('#table').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "iDisplayLength": -1,
                    "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                    "order": [[0, 'asc']],
                    "language": {
                        "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                    },
                    // Load data for the table's content from an Ajax source
                    "ajax": {
                        "url": "<?php echo site_url('papd/relatorios/ajax_medicao_anual/') ?>",
                        "type": "POST",
                        data: function (d) {
                            d.ano = $('#ano').val();
                            return d;
                        },
                        "dataSrc": function (json) {
                            $('#medicao_ano').html(json.ano);
                            // $('#medicao_termino').html(json.medicao.data_termino);
                            // $('#medicao_qtde_atendimentos').html(json.medicao.qtde_atendimentos);
                            // $('#medicao_total').html(json.medicao.total);

                            setPdf_atributes();

                            return json.data;
                        }
                    },
                    //Set column definition initialisation properties.
                    "columnDefs": [
                        {
                            width: "58%",
                            targets: [0]
                        },
                        {
                            width: '3%',
                            className: "text-center",
                            searchable: false,
                            orderable: false,
                            targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                        }
                    ]
                });
            });

        $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd() > oSettings.fnRecordsTotal() ? oSettings.fnRecordsTotal() : oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": oSettings._iDisplayLength === -1 ?
                    0 : Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": oSettings._iDisplayLength === -1 ?
                    0 : Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        //$('#ano').on('blur', function () {
        //    if (this.value.length < 4 || moment(this.value, 'YYYY').isValid() === false) {
        //        this.value = <?//= date('Y'); ?>//;
        //    }
        //    if (this.value !== ano) {
        //        ano = this.value;
        //    }
        //});

        $('#pesquisar').on('click', function () {
            reload_table();
        });

        function calcular_valores() {
            $.ajax({
                'url': '<?php echo site_url('papd/relatorios/calcularMedicaoAnual/') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'ano': $('#ano').val()
                },
                'success': function (json) {
                    reload_table();
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function setPdf_atributes() {
            var search = '';
            var q = new Array();

            $('.filtro').each(function (i, v) {
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

            $('#pdf').prop('href', "<?= site_url('papd/relatorios/pdfMedicao_anual/'); ?>" + search);
        }

    </script>

<?php
require_once APPPATH . "views/end_html.php";
?>