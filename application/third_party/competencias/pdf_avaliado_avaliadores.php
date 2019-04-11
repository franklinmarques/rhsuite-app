<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet" media="all">
        <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet" media="all">
        <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet" media="all">

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
            <table class="table table-condensed avaliacao">
                <thead>
                    <tr>
                        <th colspan="2">
                            <?php if ($is_pdf == false): ?>
                                <h1 class="text-center">AVALIADO x AVALIADORES</h1>
                            <?php else: ?>
                                <h2 class="text-center">AVALIADO x AVALIADORES</h2>
                            <?php endif; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td>
                            <?php if ($is_pdf == false): ?>
                                <h5><strong>Avaliação: </strong><?= $dadosAvaliacao->competencia ?></h5>
                                <h5><strong>Período de avaliação: </strong><span<?= ($dadosAvaliacao->data_valida == 'ok' ? '' : ' class="text-danger"') ?>><?= $dadosAvaliacao->data_inicio . ' a ' . $dadosAvaliacao->data_termino ?></span></h5>
                                <h5><strong>Data atual: </strong><?= $dadosAvaliacao->data_atual ?></h5>
                            <?php else: ?>
                                <h6><strong>Avaliação: </strong><?= $dadosAvaliacao->competencia ?></h6>
                                <h6><strong>Período de avaliação: </strong><span<?= ($dadosAvaliacao->data_valida == 'ok' ? '' : ' class="text-danger"') ?>><?= $dadosAvaliacao->data_inicio . ' a ' . $dadosAvaliacao->data_termino ?></span></h6>
                                <h6><strong>Data atual: </strong><?= $dadosAvaliacao->data_atual ?></h6>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <?php if ($is_pdf == false): ?>
                                <a id="pdf" class="btn btn-sm btn-danger" href="<?= site_url('competencias/relatorios/pdfAvaliado_avaliadores/' . $this->uri->rsegment(3) . "/" . $this->uri->rsegment(4) . '/q?avaliadores=1'); ?>" title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                                <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php if ($is_pdf == false or $exibirAvaliadores == true): ?>
                        <tr style='border-top: 5px solid #ddd;'>
                            <td>
                                <?php if ($is_pdf == false): ?>
                                    <h4>Avaliadores</h4>
                                <?php else: ?>
                                    <h5>Avaliadores</h5>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">                      
                                <?php if ($is_pdf == false): ?>
                                    <div class="checkbox">
                                        <label>
                                            <input id="exibir_avaliadores" type="checkbox" checked onchange="exibirAvaliadores()" style="float: none;"> Exibir avaliadores ao exportar PDF
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php foreach ($dadosAvaliadores as $k => $avaliador): ?>
                            <?php if ($k % 2 == 0): ?><tr class="avaliador"><?php endif; ?>
                                <td width="50%">
                                    <?php if ($avaliador->id && $avaliador->nome): ?>
                                        <strong>Avaliador <?= $k + 1 . ($avaliador->id_usuario === $dadosAvaliacao->id_usuario ? ' (avaliado)' : '') ?>:</strong> <?= $avaliador->nome ?>
                                    <?php endif; ?>
                                </td>
                                <?php if ($k % 2 == 1 || $k == count($dadosAvaliadores)): ?></tr><?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <tr style='border-top: 5px solid #ddd;'>
                        <td colspan="2">
                            <strong>Legenda</strong><br>
                            <small>NCT - Nível de Competência Técnica &nbsp; | &nbsp; NCC - Nível de Competência Comportamental</small><br>
                            <small>IDcf - Índice de Desempenho pelo cargo-função &nbsp; | &nbsp; IDC - Índice de Desempenho apresentado pelo Colaborador</small><br>
                            <small>IDc - Índice de Desempenho Geral do colaborador frente &nbsp; | &nbsp; IDC% - GAP Percentual (IDC / IDcf)</small>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!--<div class="table-responsive">-->
            <table class="table table-bordered table-condensed avaliado">
                <thead>
                    <tr class='active'>
                        <th>Colaborador avaliado</th>
                        <th>NCTf</th>
                        <th style="white-space: nowrap">Peso (CT)</th>
                        <th>NCCf</th>
                        <th style="white-space: nowrap">Peso (CC)</th>
                        <th>IDcf</th>
                        <th>NCTc</th>
                        <th>NCCc</th>
                        <th>IDc</th>
                        <th style="white-space: nowrap">IDC <em>%</em></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class='text-right'>
                        <td class="text-left"><?= $dadosAvaliacao->avaliado ?></td>
                        <td><?= $dadosCargoFuncao->ntctf ?></td>
                        <td><?= $dadosCargoFuncao->peso_competencias_tecnicas ?></td>
                        <td><?= $dadosCargoFuncao->ntccf ?></td>
                        <td><?= $dadosCargoFuncao->peso_competencias_comportamentais ?></td>
                        <td><?= $dadosCargoFuncao->idcf ?></td>
                        <td><?= $dadosCargoFuncao->ntct ?></td>
                        <td><?= $dadosCargoFuncao->ntcc ?></td>
                        <td><?= $dadosCargoFuncao->idc ?></td>
                        <td>
                            <?php if ($dadosCargoFuncao->idcPerc < 0): ?>
                                <span class="text-danger" style="font-weight: bold;"><?= $dadosCargoFuncao->idcPerc; ?></span>
                            <?php elseif ($dadosCargoFuncao->idcPerc > 0): ?>
                                <span class="text-success" style="font-weight: bold;"><?= $dadosCargoFuncao->idcPerc; ?></span>
                            <?php else: ?>
                                <span style="font-weight: bold;"><?= $dadosCargoFuncao->idcPerc; ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--</div>-->

            <?php foreach ($dadosCompetencias as $competencia): ?>
                <!--<div class="table-responsive">-->
                <table class="competencias table table-bordered table-condensed">
                    <thead>
                        <tr class="success">
                            <th colspan="<?= count($dadosAvaliadores) * 2 + 5 ?>" class='text-success'><?= $competencia->nome ?></th>
                        </tr>
                        <tr class='active'>
                            <th rowspan="2" style='vertical-align: middle;'>Comportamento/dimensão</th>
                            <th colspan="4" class="text-center">Cargo/função</th>
                            <?php foreach ($dadosAvaliadores as $k => $avaliador): ?>
                                <?php if ($avaliador->id): ?>
                                    <th colspan="2" class="text-center">Av<?= $k + 1; ?></th>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                        <tr class='active'>
                            <th>Peso</th>
                            <th>Nível</th>
                            <th>Atitude</th>
                            <th>IDcf</th>
                            <?php foreach ($dadosAvaliadores as $avaliador): ?>
                                <?php if ($avaliador->id): ?>
                                    <th>Nível</th>
                                    <th>Atitude</th>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($competencia->dimensao as $dimensao): ?>
                            <tr class='text-right'>
                                <td class='text-left'><?= $dimensao->nome ?></td>
                                <td><?= $dimensao->peso ?></td>
                                <td><?= $dimensao->nivel ?></td>
                                <td><?= $dimensao->atitude ?></td>
                                <td><?= $dimensao->idc ?></td>
                                <?php foreach ($dimensao->colaboradores as $colaborador): ?>
                                    <td><?= $colaborador->nivel ?></td>
                                    <td><?= $colaborador->atitude ?></td>
                                <?php endforeach; ?>
                            </tr>	
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!--</div>-->
            <?php endforeach; ?>
            <!-- page end-->

        </div>

        <script>

            function exibirAvaliadores() {
                var a = document.getElementById('exibir_avaliadores');
                var pdf = document.getElementById('pdf');
                var search = '';
                if (a.checked) {
                    search = '/q?avaliadores=1';
                }
                pdf.setAttribute("href", "<?= site_url('competencias/relatorios/pdfAvaliado_avaliadores/' . $this->uri->rsegment(3) . "/" . $this->uri->rsegment(4)); ?>" + search);
            }

        </script>

    </body>
</html>