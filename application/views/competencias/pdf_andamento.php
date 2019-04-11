<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajax CRUD with Bootstrap modals and Datatables</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>"
          rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container-fluid">
    <!-- page start-->
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
    <div class="row">
        <div class="col col-md-12">
            <h1 class="text-center">Avaliação de Desempenho - Andamento</h1>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col col-md-9">
            <label>Avaliação: </label> <?= $dadosAvaliacao->nome; ?><br/>
            <label>Período de avaliação: </label>
            <span class="<?= ($dadosAvaliacao->data_valida == 'ok' ? '' : 'text-danger'); ?>">
                        <?= $dadosAvaliacao->data_inicio . ' a ' . $dadosAvaliacao->data_termino; ?>
                    </span><br/>
            <label>Data atual: </label> <?= $dadosAvaliacao->data_atual; ?><br/>
        </div>
        <div class="col col-md-3 text-right">
            <?php if ($is_pdf == false): ?>
                <a id="pdf" class="btn btn-sm btn-danger"
                   href="<?= site_url('competencias/relatorios/pdfAndamento/' . $this->uri->rsegment(3)); ?>"
                   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                            class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                </button>
            <?php endif; ?>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col col-md-12">
            <?php if (empty($dadosAvaliadores)): ?>
                <span class="text-center text-muted"><h3>Nenhuma competência avaliada</h3></span>
            <?php endif; ?>
            <?php foreach ($dadosAvaliadores as $id => $competencia) : ?>
                <div class="panel panel-success">
                    <div class="panel-heading" style="color: #3c763d !important; background-color: #dff0d8 !important;">
                        <label><?= $competencia->nome ?></label></div>
                    <div class="panel-body">
                        <table id="table_<?= $competencia->id ?>" class="table table-striped">
                            <thead>
                            <?php if (array_filter($competencia->dimensao)) : ?>
                                <tr>
                                    <th>Avaliador</th>
                                    <th>Avaliado</th>
                                    <th>Status</th>
                                </tr>
                            <?php endif; ?>
                            </thead>
                            <tbody>
                            <?php if (array_filter($competencia->dimensao)) : ?>
                                <?php foreach ($competencia->dimensao as $dadosAvaliacao) : ?>
                                    <tr>
                                        <td>
                                            <?= $dadosAvaliacao['avaliador']; ?>
                                        </td>
                                        <td>
                                            <?= $dadosAvaliacao['avaliado']; ?>
                                        </td>
                                        <td class="<?= ($dadosAvaliacao['status'] === 'Avaliado' ? 'text-success' : 'text-danger'); ?>">
                                            <strong><?= $dadosAvaliacao['status']; ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="odd">
                                    <td colspan="3" class="dataTables_empty" valign="top">Nenhum registro encontrado
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- page end-->

</body>
</html>