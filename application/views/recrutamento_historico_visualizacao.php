<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Histórico de Candidatos</title>
    <link href="<?php echo base_url('assets/font-awesome/css/font-awesome.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

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
    <br>
    <button class="btn btn-default" onclick="javascript:window.close()"><i
                class="glyphicon glyphicon-remove"></i> Fechar
    </button>
    <br>
    <br>
    <h5 class="text-primary">
        <strong>Candidato(a): <?= $nomeCandidato ?></strong></h5>
    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th rowspan="2">RP</th>
            <th rowspan="2" class="text-center text-nowrap">
                Perfil<br>
                <i class="fa fa-smile-o text-success"></i>
                <i class="fa fa-meh-o text-warning"></i>
                <i class="fa fa-frown-o text-danger"></i>
            </th>
            <th rowspan="2">Observações</th>
            <th rowspan="2">Cargo</th>
            <th rowspan="2">Função</th>
            <th rowspan="2">Status</th>
            <th rowspan="2">Deficiência</th>
            <th colspan="2" class="text-center">Seleção</th>
            <th colspan="2" class="text-center">Requisitante</th>
            <th class="text-center">Antecedentes criminais</th>
            <th class="text-center">Restrições financeiras</th>
            <th colspan="2" class="text-center">Exame médico admissional</th>
            <th rowspan="2">Data admissão</th>
        </tr>
        <tr>
            <th class="text-center">Data</th>
            <th class="text-center">Resultado</th>
            <th class="text-center">Data</th>
            <th class="text-center">Resultado</th>
            <th class="text-center">Resultado</th>
            <th class="text-center">Resultado</th>
            <th class="text-center">Data</th>
            <th class="text-center">Resultado</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


</div>
<div id="script_js" style="display: none;"></div>
<script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>
    var save_method;
    var table;

    $(document).ready(function () {

        table = $('#table').DataTable({
            'info': false,
            'lengthChange': false,
            'searching': false,
            'paging': false,
            'processing': true,
            'serverSide': true,
            'order': [['1', 'asc']],
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('recrutamento_candidatos/ajax_list_processos') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.id_candidato = '<?= $idCandidato; ?>';
                    return d;
                }
            },
            'columnDefs': [
                {
                    'width': '16%',
                    'targets': [1, 2, 5, 10, 11, 12]
                },
                {
                    'className': 'text-center',
                    'targets': [0, 1, 6, 7, 8, 9, 10, 11]
                }
            ]
        });

    });


    function reload_table() {
        table.ajax.reload(null, false);
    }

</script>
</body>
</html>