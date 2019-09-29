<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Controle de Frequência Individual</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        @page {
            margin: 40px 20px;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">
    <table>
        <tr>
            <td>
                <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                     style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
            </td>
            <td style="vertical-align: top;">
                <p>
                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                </p>
            </td>
        </tr>
    </table>
    <table id="titulo" class="table table-condensed table-condensed">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="2" style="padding-bottom: 12px;">
                <h3 class="text-center" style="font-weight: bold;">RELATORIO DE EVENTOS DE APONTAMENTO</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <table id="table" class="table table-bordered table-condensed">
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
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row->nome ?></td>
                <td style="text-align: center;"><?= $row->data ?></td>
                <td style="text-align: center;"><?= $row->status ?></td>
                <td><?= $row->glosa ?></td>
                <td><?= $row->nome_bck ?></td>
                <td style="text-align: center;"><?= $row->apontamento_desc ?></td>
                <td style="text-align: center;"><?= $row->apontamento_extra ?></td>
                <td><?= $row->detalhes ?></td>
                <td><?= $row->observacoes ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <pagebreak odd-header-name="myHeader"></pagebreak>

    <table id="legenda" class="table table-bordered table-condensed" width="50%">
        <thead>
        <tr>
            <th colspan="2" align="center">Legenda</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="auto"><strong>FJ</strong></td>
            <td>Falta com atestado próprio</td>
        </tr>
        <tr>
            <td width="auto"><strong>FN</strong></td>
            <td>Falta sem atestado</td>
        </tr>
        <tr>
            <td width="auto"><strong>PD</strong></td>
            <td>Posto descoberto</td>
        </tr>
        <tr>
            <td width="auto"><strong>PI</strong></td>
            <td>Posto descontinuado</td>
        </tr>
        <tr>
            <td width="auto"><strong>SJ</strong></td>
            <td>Saída antecipada com atestado próprio</td>
        </tr>
        <tr>
            <td width="auto"><strong>SN</strong></td>
            <td>Saída antecipada sem atestado</td>
        </tr>
        <tr>
            <td width="auto"><strong>AJ</strong></td>
            <td>Atraso com atestado próprio</td>
        </tr>
        <tr>
            <td width="auto"><strong>AN</strong></td>
            <td>Atraso sem atestado</td>
        </tr>
        <tr>
            <td width="auto"><strong>FR</strong></td>
            <td>Feriado</td>
        </tr>
        </tbody>
    </table>
    <!--</div>-->
</div>

</body>
</html>