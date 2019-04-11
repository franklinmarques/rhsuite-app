<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Requisição de Ordem de Serviços</title>
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
    <table class="table table-condensed ordem_servico">
        <thead>
        <tr style='border-top: 5px solid #ddd;'>
            <th colspan="3">
                <h3 class="text-center">REQUISIÇÃO DE ORDEM DE SERVIÇOS</h3>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd;'>
            <td><strong>N&ordm; requisição:</strong> <?= $numero_os ?></td>
            <td><strong>Prioridade:</strong> <?= $prioridade ?></td>
            <td><strong>Data de abertura da requisição:</strong> <?= $data_abertura ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Data estimada para resolução da
                    O.S.:</strong> <?= $data_resolucao_problema ?></td>
            <td><strong>Data de fechamrno da O.S.:</strong> <?= $data_resolucao_problema ?></td>
        </tr>
        <tr>
            <td colspan="3"><strong>Depto/área/setor:</strong> <?= $estrutura ?></td>
        </tr>
        <tr>
            <td colspan="3"><strong>Requisitante:</strong> <?= $requisitante ?></td>
        </tr>
        </tbody>
    </table>

    <br/>

    <table id="descricao_problema" class="table dados table-condensed">
        <thead>
        <tr>
            <th>Necessidade/Problema objeto da requisição</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="100%">
                <?php if ($is_pdf == false): ?>
                    <textarea name="descricao_problema"
                              class="form-control" rows="5"><?= $descricao_problema; ?></textarea>
                <?php else: ?>
                    <?= nl2br($descricao_problema); ?>
                <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>

    <table id="observacoes" class="table dados table-condensed">
        <thead>
        <tr>
            <th>Observações/Andamento da requisição</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="100%">
                <?php if ($is_pdf == false): ?>
                    <textarea name="observacoes" class="form-control" rows="5"><?= $observacoes; ?></textarea>
                <?php else: ?>
                    <?= nl2br($observacoes); ?>
                <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>

    <table id="resolucao_satisfatoria" class="table dados table-condensed">
        <thead>
        <tr>
            <th colspan="2">Pesquisa de satisfação</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="text-nowrap" width="auto">A resolução da necessidade/problema reportado na O. S. foi:</td>
            <td style="font-weight: bold"><?= $resolucao_satisfatoria; ?></td>
        </tr>
        </tbody>
    </table>

    <table id="observacoes_positivas" class="table dados table-condensed">
        <thead>
        <tr>
            <th>Observações positivas quanto a todo o processo de tratamento da O. S.</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="100%">
                <?php if ($is_pdf == false): ?>
                    <textarea name="observacoes_positivas"
                              class="form-control" rows="5"><?= $observacoes_positivas; ?></textarea>
                <?php else: ?>
                    <?= nl2br($observacoes_positivas); ?>
                <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>

    <table id="observacoes_negativas" class="table dados table-condensed">
        <thead>
        <tr>
            <th>Observações negativas quanto a todo o processo de tratamento da O. S.</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="100%">
                <?php if ($is_pdf == false): ?>
                    <textarea name="observacoes_negativas"
                              class="form-control" rows="5"><?= $observacoes_negativas; ?></textarea>
                <?php else: ?>
                    <?= nl2br($observacoes_negativas); ?>
                <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>

</div>
</body>
</html>
