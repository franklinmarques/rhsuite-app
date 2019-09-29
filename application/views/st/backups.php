<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de Alocação de Backups</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <style>
        @page {
            margin: 40px 20px;
        }

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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">

    <htmlpageheader name="myHeader">
        <table id="backup" class="table table-condensed">
            <thead>
            <tr>
                <td style="width: auto;">
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                         style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                </td>
                <td style="width: 100%; vertical-align: top;">
                    <p>
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                             style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                    </p>
                </td>
                <?php if ($is_pdf == false): ?>
                    <td nowrap>
                        <a id="pdf" class="btn btn-sm btn-danger"
                           href="<?= site_url('apontamento_backups/pdf/q?' . $query_string); ?>"
                           title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                        <!--<button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>-->
                    </td>
                <?php endif; ?>
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="<?= $is_pdf == false ? '3' : '2' ?>" style="padding-bottom: 8px; text-align: center;">
                    <?php if ($is_pdf == false): ?>
                        <h3 class="text-center" style="font-weight: bold;">RELATÓRIO DE ALOCAÇÃO DE BACKUPS MÊS
                            DE <?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h3>
                        <?php if ($contrato): ?>
                            <?php if ($setor): ?>
                                <h4 class="text-center" style="font-weight: bold;">CONTRATO
                                    Nº <?= $contrato->contrato ?> ─ <?= $contrato->nome ?>
                                    ─ <?= $contrato->setor ?></h4>
                            <?php else: ?>
                                <h4 class="text-center" style="font-weight: bold;"><?= $contrato->nome ?></h4>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <h4 class="text-center" style="font-weight: bold;">RELATÓRIO DE ALOCAÇÃO DE BACKUPS MÊS
                            DE <?= mb_strtoupper($mes_nome) ?> DE <?= $ano ?></h4>
                        <?php if ($contrato): ?>
                            <?php if ($setor): ?>
                                <h5 class="text-center" style="font-weight: bold;">CONTRATO
                                    Nº <?= $contrato->contrato ?>
                                    ─ <?= $contrato->nome ?> ─ <?= $contrato->setor ?></h5>
                            <?php else: ?>
                                <h5 class="text-center" style="font-weight: bold;"><?= $contrato->nome ?></h5>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </th>
            </tr>
            </thead>
        </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>
    <?php if ($is_pdf): ?>
        <br>
    <?php endif; ?>
    <div>
        <table id="table" class="table table-bordered table-condensed" width="100%">
            <thead>
            <tr class="success">
                <th colspan="6" class="text-center"><h3><strong>Alocação de Backups</strong></h3></th>
            </tr>
            <tr class="active">
                <th class="text-center">Dia</th>
                <th class="text-center text-nowrap">Unidade</th>
                <th class="text-center">Evento</th>
                <th class="text-center">Glosa</th>
                <th class="text-center text-nowrap">Principal</th>
                <th class="text-center text-nowrap">Backup</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

</div>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

<script>
    var table;
    var busca = '<?= $query_string ?>';

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            dom: "<'row'<'#busca.col-sm-4'><'#status.col-sm-4'><'col-sm-4'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            "info": false,
            "ordering": false,
            "paging": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "language": {
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
                sSearch: "Unidade / Principal / Backup",
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
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('apontamento_backups/ajax_list/') ?>",
                "type": "POST",
                "data": function (d) {
                    d.busca = busca;
                    if ($('[name="tipo_bck"]:checked').val() !== undefined) {
                        d.tipo_bck = $('[name="tipo_bck"]:checked').val();
                    } else {
                        d.tipo_bck = '';
                    }
                    if ($('[name="status"]').val() !== undefined) {
                        d.status = $('[name="status"]').val();
                    } else {
                        d.status = '';
                    }
                    return d;
                },
                "dataSrc": function (json) {
                    if (json.draw === '1') {
                        $("#busca").html('<div style="padding: 3px;"><label style="font-weight: normal;">Tipo de evento &emsp;' +
                            '<label class="radio-inline">' +
                            '<input type="radio" name="tipo_bck" value="" onchange="buscar();" checked=""> Todos' +
                            '</label>' +
                            '<label class="radio-inline">' +
                            '<input type="radio" name="tipo_bck" value="0" onchange="buscar();"> Sem backup' +
                            '</label>' +
                            '<label class="radio-inline">' +
                            '<input type="radio" name="tipo_bck" value="1" onchange="buscar();"> Com backup' +
                            '</label>' +
                            '</label></div>');
                        $("#status").html('<div><label style="font-weight: normal;">Status de evento &emsp;' +
                            '<select name="status" class="form-control input-sm" onchange="buscar();">' +
                            '<option value="">Todos</option>' +
                            '<option value="FJ">FJ</option>' +
                            '<option value="FN">FN</option>' +
                            '<option value="A">AJ+AN</option>' +
                            '<option value="S">SJ+SN</option>' +
                            '</select>' +
                            '</label></div>');
                    }
                    return json.data;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    className: 'text-center',
                    searchable: false,
                    targets: [0, 2, 3]
                }
            ]
        });

        setPdfAttributes();
    });

    function buscar() {
        reload_table();
        setPdfAttributes();
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }

    function setPdfAttributes() {
        var search = '';
        var q = busca.split('&');

        if ($('[name="tipo_bck"]').val() !== undefined) {
            if ($('[name="tipo_bck"]:checked').val().length > 0) {
                q.push("tipo_bck=" + $('[name="tipo_bck"]:checked').val());
            }
        }
        if ($('[name="status"]').val() != undefined) {
            if ($('[name="status"]').val().length > 0) {
                q.push("status=" + $('[name="status"]').val());
            }
        }
        if ($('.dataTables_filter input').val().length > 0) {
            q.push("busca=" + $('.dataTables_filter input').val());
        }

        q = q.filter(function (v) {
            return v.length > 0;
        });
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }

        $('#pdf').prop('href', "<?= site_url('apontamento_backups/pdf'); ?>" + search);
    }
</script>

</body>
</html>