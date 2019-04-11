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
            <th>Escola</th>
            <th>Período</th>
            <th>Data</th>
            <th>Evento</th>
            <th>Substituto(a)</th>
            <th>Desconto</th>
            <th>Apontamento</th>
            <th>Observações gerais</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row->cuidador ?></td>
                <td><?= $row->escola ?></td>
                <td style="text-align: center;"><?= $row->turno ?></td>
                <td style="text-align: center;"><?= $row->data ?></td>
                <td style="text-align: center;"><?= $row->status ?></td>
                <td><?= $row->nome_bck ?></td>
                <td style="text-align: center;"><?= $row->apontamento_desc ?></td>
                <td style="text-align: center;"><?= $row->apontamento_extra ?></td>
                <td><?= $row->observacoes ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <pagebreak odd-header-name="myHeader"></pagebreak>

    <table id="legenda" class="table table-bordered table-condensed" width="50%">
        <thead>
        <tr>
            <th colspan="4" align="center">Legenda</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="auto"><strong>DE</strong></td>
            <td>Funcionário demitido</td>
            <td width="auto"><strong>FS</strong></td>
            <td>Falta sem atestado</td>
        </tr>
        <tr>
            <td width="auto"><strong>FA</strong></td>
            <td>Falta com atestado próprio</td>
            <td width="auto"><strong>AF</strong></td>
            <td>Funcionário afastado</td>
        </tr>
        <tr>
            <td width="auto"><strong>AA</strong></td>
            <td>Aluno ausente</td>
            <td width="auto"><strong>RE</strong></td>
            <td>Funcionário remanejado</td>
        </tr>
        <tr>
            <td width="auto"><strong>NA</strong></td>
            <td>Funcionário não-alocado</td>
            <td width="auto"><strong>AP</strong></td>
            <td>Apontamento</td>
        </tr>
        <tr>
            <td width="auto"><strong>AD</strong></td>
            <td>Funcionário admitido</td>
            <td width="auto"><strong>SL</strong></td>
            <td>Sábado letivo</td>
        </tr>
        <tr>
            <td width="auto"><strong>FC</strong></td>
            <td>Feriado escola/cuidador</td>
            <td width="auto"><strong>FE</strong></td>
            <td>Feriado escola</td>
        </tr>
        <tr>
            <td width="auto"><strong>EM</strong></td>
            <td>Emenda de feriado</td>
            <td width="auto"><strong>PC</strong></td>
            <td>Posto coberto</td>
        </tr>
        <tr>
            <td width="auto"><strong>ID</strong></td>
            <td>Intercorrência de diretoria</td>
            <td width="auto"><strong>IC</strong></td>
            <td>Intercorrência de cuidadores</td>
        </tr>
        <tr>
            <td width="auto"><strong>IA</strong></td>
            <td>Intercorrência de alunos</td>
            <td width="auto"><strong>AT</strong></td>
            <td>Acidente de trabalho</td>
        </tr>

        </tbody>
    </table>
    <!--</div>-->
</div>

</body>
</html>