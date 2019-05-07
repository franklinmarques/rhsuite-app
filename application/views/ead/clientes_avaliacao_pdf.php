<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório de Avaliação de Treinamento</title>
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
</head>
<style>
    table tr td:first-child {
        white-space: nowrap;
    }
</style>
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
    <table class="table table-condensed avaliacao">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3">
                <h3 class="text-center">RELATÓRIO DE AVALIAÇÃO DE TREINAENTO</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd;'>
            <td><span style="font-weight: bold;">Cliente:</span> <?= $cliente ?></td>
            <td><span style="font-weight: bold;">Usuário:</span> <?= $usuario ?></td>
        </tr>
        <tr>
            <td colspan="2"><span style="font-weight: bold;">Treinamento:</span> <?= $treinamento ?></td>
        </tr>
        <tr>
            <td><span style="font-weight: bold;">Período:</span> <?= implode(' às ', [$data_inicio, $data_maxima]) ?>
            </td>
            <td><span style="font-weight: bold;">Data de realização:</span> <?= $data_finalizacao ?></td>
        </tr>
        <tr>
            <td><span style="font-weight: bold;">Tempo total de estudo:</span> <?= $tempo_estudo ?></td>
            <?php if (strlen($avaliacao_final)): ?>
                <td>
                    <span style="font-weight: bold;">Avaliação final:</span> <?= str_replace('.', ',', $avaliacao_final . '%') ?>
                </td>
            <?php else: ?>
                <td></td>
            <?php endif; ?>
        </tr>
        </tbody>
    </table>

    <br/>

    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th width="43%">Questões</th>
            <th width="43%">Respostas</th>
            <th nowrap>Notas (0% a 100%)</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultados as $resultado): ?>
            <tr>
                <td><?= strip_tags($resultado->conteudo, '<br>') ?></td>
                <td><?= $resultado->resposta ?></td>
                <td><?= $resultado->nota ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
</body>
</html>
