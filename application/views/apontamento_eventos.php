<?php
require_once "header.php";
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

        .table > tbody > tr > td.colaborador-success,
        .table > tbody > tr > td.date-width-success {
            color: #fff;
            background-color: #5cb85c !important;
        }

        .table > tbody > tr > td.colaborador-success:hover,
        .table > tbody > tr > td.date-width-success:hover {
            background-color: #47a447 !important;
        }

        .table > tbody > tr > td.colaborador-primary,
        .table > tbody > tr > td.date-width-primary {
            color: #fff;
            background-color: #027EEA !important;
        }

        .table > tbody > tr > td.colaborador-primary:hover,
        .table > tbody > tr > td.date-width-primary:hover {
            background-color: #007EEB;
        }

        .table > tbody > tr > td.colaborador-disabled,
        .table > tbody > tr > td.date-width-disabled {
            color: #fff;
            background-color: #5C679A !important;
        }

        .table > tbody > tr > td.colaborador-disabled:hover,
        .table > tbody > tr > td.date-width-disabled:hover {
            background-color: #576192;
        }

        .table > tbody > tr > td.date-width-warning {
            /*color: #fff;*/
            background-color: #f0ad4e !important;
        }

        .table > tbody > tr > td.date-width-warning:hover {
            background-color: #ed9c28 !important;
        }

        .table > tbody > tr > td.date-width-danger {
            color: #fff;
            background-color: #d9534f !important;
        }

        .table > tbody > tr > td.date-width-danger:hover {
            background-color: #d2322d !important;
        }

        .table > tbody > tr > td.date-width-disabled {
            color: #fff;
            background-color: #8866bb !important;
        }

        .table > tbody > tr > td.date-width-disabled:hover {
            background-color: #7253b0 !important;
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
                        <li><a href="<?= site_url('apontamento') ?>">Apontamentos diários</a></li>
                        <li class="active">Relatório de eventos</li>
                    </ol>
                    <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <a id="pdf" style="float: right;" class="btn btn-sm btn-danger"
                       href="<?= site_url('apontamento_eventos/pdf/'); ?>" title="Exportar PDF"><i
                                class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="well well-sm">
                                <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="control-label">Filtrar por departamento</label>
                                            <?php echo form_dropdown('depto', $depto, $depto_atual, 'onchange="atualizarFiltro()" class="form-control input-sm busca"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Filtrar por área/cliente</label>
                                            <?php echo form_dropdown('area', $area, $area_atual, 'onchange="atualizarFiltro()" class="form-control input-sm busca"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Filtrar por setor/unidade</label>
                                            <?php echo form_dropdown('setor', $setor, $setor_atual, 'onchange="atualizarFiltro()" class="form-control input-sm busca"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Filtrar por cargo</label>
                                            <?php echo form_dropdown('cargo', $cargo, '', 'onchange="atualizarFiltro()" class="form-control input-sm busca"'); ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="control-label">Filtrar por função</label>
                                            <?php echo form_dropdown('funcao', $funcao, '', 'onchange="atualizarFiltro()" class="form-control input-sm busca"'); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label">Filtrar por contrato</label>
                                            <?php echo form_dropdown('contrato', $contrato, '', 'class="form-control input-sm busca"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Filtrar por mês</label>
                                            <?php echo form_dropdown('mes', $meses, $mes, 'class="form-control input-sm busca"'); ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label">Filtrar por ano</label>
                                            <input name="ano" placeholder="aaaa"
                                                   class="form-control input-sm text-right busca"
                                                   maxlength="4" type="number" value="<?= $ano ?>">
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <label>&nbsp;</label><br>
                                            <button type="button" id="pesquisar" class="btn btn-sm btn-default"><i
                                                        class="glyphicon glyphicon-search"></i></button>
                                            <button type="button" id="limpa_filtro" class="btn btn-sm btn-default">
                                                Limpar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Colaborador(a)</th>
                                <th>Data</th>
                                <th style="padding: 5px;">Evento</th>
                                <th>Glosa</th>
                                <th>Backup/Substituto(a)</th>
                                <th>Desconto</th>
                                <th>Apontamento</th>
                                <th>Detalhes</th>
                                <th>Observações gerais</th>
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
            document.title = 'CORPORATE RH - LMS - Relatório de eventos';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script>

        var table;

        $(document).ready(function () {


            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 1000,
                lengthMenu: [[5, 10, 25, 50, 100, 500, 1000, 1500, 2000], [5, 10, 25, 50, 100, 500, 1000, 1500, 2000]],
                order: [],
                language: {
                    sEmptyTable: "Nenhum registro encontrado",
                    sInfo: "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando 0 até 0 de 0 registros",
                    sInfoFiltered: "(Filtrados de _MAX_ registros)",
                    sInfoPostFix: "",
                    sInfoThousands: ".",
                    sLengthMenu: "Mostrar _MENU_ resultados",
                    sLoadingRecords: "Carregando...",
                    sProcessing: "Processando...",
                    sZeroRecords: "Nenhum registro encontrado",
                    sSearch: "Colaborador(a) / backup / substituto(a)",
                    oPaginate: {
                        sNext: "Próximo",
                        sPrevious: "Anterior",
                        sFirst: "Primeiro",
                        sLast: "Último"
                    },
                    oAria: {
                        sSortAscending: ": Ordenar colunas de forma ascendente",
                        sSortDescending: ": Ordenar colunas de forma descendente"
                    }
                },
                ajax: {
                    url: "<?php echo site_url('apontamento_eventos/ajax_list/') ?>",
                    type: "POST",
                    data: function (d) {
                        d.busca = $('#busca').serialize();
                        return d;
                    }
                },
                drawCallback: function () {
                    setPdf_atributes();
                },
                columnDefs: [
                    {
                        className: "text-center",
                        searchable: false,
                        targets: [1, 3, 5, 6]
                    },
                    {
                        createdCell: function (td, cellData, rowData, row, col) {
                            $(td).css({'font-weight': 'normal', 'padding': '8px 1px'});
                            if (rowData[col] === 'AJ' || (rowData[col] === 'FJ' && rowData[4] === null) || rowData[col] === 'SJ') {
                                $(td).addClass('date-width-warning');
                            } else if (rowData[col] === 'AN' || (rowData[col] === 'FN' && rowData[4] === null) || rowData[col] === 'SN') {
                                $(td).addClass('date-width-danger');
                            } else if (rowData[col] !== undefined && rowData[4] !== null) {
                                $(td).addClass('date-width-success');
                            } else if (rowData[col] === 'FR') {
                                $(td).addClass('date-width-primary');
                            } else if (rowData[col] === 'PD' || rowData[col][9] === 'PI') {
                                $(td).addClass('date-width-disabled');
                            } else if (rowData[col].length > 0) {
                                $(td).css('background-color', '#ff0');
                            }
                            $(td).html(rowData[col] !== 'AE' ? rowData[col] : '');
                        },
                        className: 'text-center',
                        searchable: false,
                        targets: [2]
                    },
                    {
                        searchable: false,
                        targets: [7, 8]
                    }
                ]
            });


        });

        function atualizarFiltro() {
            $.ajax({
                url: "<?php echo site_url('apontamento_eventos/atualizar_filtro/') ?>",
                type: "GET",
                dataType: "JSON",
                data: $('#busca').serialize(),
                success: function (json) {
                    $('[name="area"]').html($(json.area).html());
                    $('[name="setor"]').html($(json.setor).html());
                    $('[name="cargo"]').html($(json.cargo).html());
                    $('[name="funcao"]').html($(json.funcao).html());
                    $('[name="contrato"]').html($(json.contrato).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        $('#pesquisar').on('click', function () {
            reload_table();
        });

        $('#limpa_filtro').on('click', function () {
            $('.busca:not([name="depto"])').val('');
            table.search('');
            atualizarFiltro();
            reload_table();
        });

        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }

        function setPdf_atributes() {
            var search = '';
            var q = new Array();

            $('.busca').each(function (i, v) {
                if (v.value.length > 0 && (v.value !== 'Todos' || v.value !== 'Todas')) {
                    q[i] = v.name + "=" + v.value;
                }
            });
            if (table.order().length > 0) {
                q[q.length] = 'order[0]=' + (table.order()[0][0] + 1) + '&order[1]=' + table.order()[0][1];
            }
            if (table.search().length > 0) {
                q[q.length] = 'search=' + table.search();
            }

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('apontamento_eventos/pdf/'); ?>" + search);
        }

    </script>

<?php
require_once "end_html.php";
?>