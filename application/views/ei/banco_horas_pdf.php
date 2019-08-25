<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Banco de Horas de Educação Inclusiva</title>
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

        #mapa_visitacao tbody tr td.success {
            background-color: #5cb85c;
            color: #fff;
        }

        #mapa_visitacao tbody tr td.warning {
            background-color: #f0ad4e;
            color: #fff;
        }

        #mapa_visitacao tbody tr td.danger {
            background-color: #c9302c;
            color: #fff;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">

    <htmlpageheader name="myHeader">
        <table id="table" class="table table-condensed">
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
            </tr>
            <tr style='border-top: 5px solid #ddd;'>
                <th colspan="2" style="padding-bottom: 8px; text-align: center;">
                    <h2 class="text-center" style="font-weight: bold;">RELATÓRIO DE BANCO DE HORAS</h2>
                    <h3 class="text-center" style="font-weight: bold;"><?= strtoupper($mes); ?> DE <?= $ano; ?></h3>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2">
                    <span style="font-weight: bold;">Departamento:</span> <?= $departamento; ?>
                </td>
            </tr>
            <tr>
                <!--<td>
                    <span style="font-weight: bold;">Cliente:</span> <? /*= $diretoria; */ ?>
                </td>-->
                <td colspan="2">
                    <span style="font-weight: bold;">Saldo acumulado na data de <?= date('d/m/Y'); ?>:</span> <?= $saldo_acumulado_horas; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span style="font-weight: bold;">Supervisor(a):</span> <?= $supervisor; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </htmlpageheader>
    <sethtmlpageheader name="myHeader" value="on" show-this-page="1"></sethtmlpageheader>

    <div>
        <table id="banco_horas" class="table table-condensed table-bordered" cellspacing="0" width="100%"
               style="border-radius: 0 !important;">
            <thead>
            <tr class="active">
                <th>Data</th>
                <th>Entrada</th>
                <th>Saída</th>
                <th>Entrada</th>
                <th>Saída</th>
                <th>Total</th>
                <th>Saldo</th>
                <th width="40%">Observações</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="8" class="text-center">Nenhuma carga horária cadastrada</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td class="text-center"><?= $row->data; ?></td>
                    <td class="text-center"><?= $row->horario_entrada; ?></td>
                    <td class="text-center"><?= $row->horario_saida; ?></td>
                    <td class="text-center"><?= $row->horario_entrada_1; ?></td>
                    <td class="text-center"><?= $row->horario_saida_1; ?></td>
                    <td class="text-center"><?= $row->total; ?></td>
                    <td class="text-center"><?= $row->saldo_dia; ?></td>
                    <td><?= nl2br($row->observacoes); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

</body>
</html>