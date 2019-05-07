<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Relatório PDI</title>
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
    <table class="table table-condensed avaliado">
        <thead>
        <tr>
            <th colspan="3">
                <?php if ($is_pdf == false): ?>
                    <h1 class="text-center"><?= $dadosAvaliacao->titulo ?></h1>
                <?php else: ?>
                    <h2 class="text-center"><?= $dadosAvaliacao->titulo ?></h2>
                <?php endif; ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style='border-top: 5px solid #ddd;'>
            <td colspan="3">
                <?php if ($is_pdf == false): ?>
                    <h5><strong>Avaliação: </strong><?= $dadosAvaliacao->nome ?></h5>
                    <h5><strong>Data de início de atividades: </strong><?= $dadosAvaliacao->data_inicio ?></h5>
                    <h5><strong>Data atual: </strong><?= $dadosAvaliacao->data_atual ?></h5>
                <?php else: ?>
                    <h6><strong>Avaliação: </strong><?= $dadosAvaliacao->nome ?></h6>
                    <h6><strong>Data de início de atividades: </strong><?= $dadosAvaliacao->data_inicio ?></h6>
                    <h6><strong>Data atual: </strong><?= $dadosAvaliacao->data_atual ?></h6>
                <?php endif; ?>
            </td>
            <?php if ($is_pdf == false): ?>
                <td class="text-right">
                    <a class="btn btn-sm btn-danger"
                       href="<?= site_url('avaliacaoexp/pdfRelatorio/' . $this->uri->rsegment(3)); ?>"
                       title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                    <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                </td>
            <?php endif; ?>
        </tr>
        <tr style='border-top: 5px solid #ddd;'>
            <th>Colaborador</th>
            <th>Função</th>
            <th>Depto/área/setor</th>
        </tr>
        <tr style='border-bottom: 5px solid #ddd;'>
            <td style="width: 34%;"><?= $dadosAvaliacao->colaborador ?></td>
            <td style="width: 33%;"><?= $dadosAvaliacao->funcao ?></td>
            <td style="width: 33%;"><?= implode('/', array_filter(array($dadosAvaliacao->depto, $dadosAvaliacao->area, $dadosAvaliacao->setor))) ?></td>
        </tr>
        </tbody>
    </table>

    <!--<div class="table-responsive">-->
    <table class="table table-bordered table-condensed avaliadores">
        <thead>
        <tr class='active'>
            <?php if ($dadosAvaliacao->tipo === 'P'): ?>
                <th colspan="<?= $ocultar_avaliadores ? '3' : '4' ?>" class="text-center">1&ordf; Avaliador (Av1)</th>
                <th colspan="<?= $ocultar_avaliadores ? '3' : '4' ?>" class="text-center">2&ordf; Avaliador (Av2)</th>
                <th colspan="<?= $ocultar_avaliadores ? '3' : '4' ?>" class="text-center">3&ordf; Avaliador (Av3)</th>
            <?php else: ?>
                <th colspan="<?= $ocultar_avaliadores ? '3' : '4' ?>" class="text-center">1&ordf; Avaliador</th>
                <th colspan="<?= $ocultar_avaliadores ? '3' : '4' ?>" class="text-center">2&ordf; Avaliador</th>
                <th colspan="<?= $ocultar_avaliadores ? '3' : '4' ?>" class="text-center">3&ordf; Avaliador</th>
            <?php endif; ?>
        </tr>
        <tr class='active'>
            <th>Data programada</th>
            <?php if (!$ocultar_avaliadores): ?>
                <th>Avaliador</th>
            <?php endif; ?>
            <th>Data de realização</th>
            <th>Resultado</th>
            <th>Data programada</th>
            <?php if (!$ocultar_avaliadores): ?>
                <th>Avaliador</th>
            <?php endif; ?>
            <th>Data de realização</th>
            <th>Resultado</th>
            <th>Data programada</th>
            <?php if (!$ocultar_avaliadores): ?>
                <th>Avaliador</th>
            <?php endif; ?>
            <th>Data de realização</th>
            <th>Resultado</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <?php foreach ($dadosAvaliadores as $dadosAvaliador): ?>
                <td class="text-center"><?= $dadosAvaliador->data_avaliacao ?></td>
                <?php if (!$ocultar_avaliadores): ?>
                    <td><?= $dadosAvaliador->nome ?></td>
                <?php endif; ?>
                <td class="text-center"><?= $dadosAvaliador->data_realizacao ?></td>
                <td class="text-right"><?= round(array_sum($dadosAvaliador->resultado), 1) ?></td>
            <?php endforeach; ?>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <?php if ($dadosAvaliacao->tipo == 'P'): ?>
                <td colspan="4" style="border-left: 0; border-right: 0; vertical-align: bottom;">
                    <strong>Nota de corte: </strong><?= $dadosAvaliacao->nota_corte ?>%
                </td>
                <td colspan="4" style="border-left: 0; border-right: 0;">
                    <?php
                    $statusResultado = '';
                    if ($dadosAvaliacao->resultado_final > $dadosAvaliacao->nota_corte) {
                        $statusResultado = ' class="text-success"';
                    } elseif ($dadosAvaliacao->resultado_final < $dadosAvaliacao->nota_corte) {
                        $statusResultado = ' class="text-danger"';
                    }
                    ?>
                    <strong>Resultado final: <span
                                style="font-size: 16px;"<?= $statusResultado ?>><?= $dadosAvaliacao->resultado_final ?>
                            %</span></strong>
                </td>
                <td colspan="4" style="border-left: 0;">
                    <strong>Parecer final:
                        <?php if ($dadosAvaliacao->parecer_final == 'E'): ?>
                            <span style="font-size: 16px;" class="text-success">Efetivar</span>
                        <?php elseif ($dadosAvaliacao->parecer_final == 'D'): ?>
                            <span style="font-size: 16px;" class="text-danger">Dispensar</span>
                        <?php elseif ($dadosAvaliacao->parecer_final == 'A'): ?>
                            <span style="font-size: 16px;">Em avaliação</span>
                        <?php else: ?>
                            <span class="text-muted">Não avaliado</span>
                        <?php endif; ?>
                    </strong>
                </td>
            <?php elseif ($dadosAvaliacao->tipo == 'A'): ?>
                <td colspan="6" style="border-left: 0; border-right: 0; vertical-align: bottom;">
                    <strong>Nota de corte: </strong><?= $dadosAvaliacao->nota_corte ?>%
                </td>
                <td colspan="6" style="border-left: 0; border-right: 0;">
                    <?php
                    $statusResultado = '';
                    if ($dadosAvaliacao->resultado_final > $dadosAvaliacao->nota_corte) {
                        $statusResultado = ' class="text-success"';
                    } elseif ($dadosAvaliacao->resultado_final < $dadosAvaliacao->nota_corte) {
                        $statusResultado = ' class="text-danger"';
                    }
                    ?>
                    <strong>Resultado final: <span
                                style="font-size: 16px;"<?= $statusResultado ?>><?= $dadosAvaliacao->resultado_final ?>
                            %</span></strong>
                </td>
            <?php endif; ?>
        </tr>
        </tfoot>
    </table>
    <!--</div>-->

    <br/>
    <!--<div class="table-responsive">-->
    <table class="table table-bordered table-condensed avaliacao">
        <thead>
        <?php if ($dadosAvaliacao->tipo === 'P'): ?>
            <tr>
                <th rowspan="2" class="active">Critérios de avaliação</th>
                <?php foreach ($alternativas as $alternativa): ?>
                    <th colspan="3" class="text-center active"><?= $alternativa->nome; ?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($alternativas as $alternativa): ?>
                    <th class="text-center active">Av1</th>
                    <th class="text-center active">Av2</th>
                    <th class="text-center active">Av3</th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <th>Média percentual</th>
                <?php foreach ($alternativas as $alternativa): ?>
                    <?php foreach ($alternativa->media as $media): ?>
                        <th class="text-right"><?= (strlen($media) ? $media . '%' : '') ?></th>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tr>
        <?php else: ?>
            <tr>
                <th>Critérios de avaliação</th>
                <th class="text-center">Peso</th>
                <th class="text-center">1&ordf; Avaliação</th>
                <th class="text-center">2&ordf; Avaliação</th>
                <th class="text-center">3&ordf; Avaliação</th>
            </tr>
        <?php endif; ?>
        </thead>
        <tbody>
        <?php if ($dadosAvaliacao->tipo === 'P'): ?>

            <?php foreach ($dadosAvaliado as $pergunta): ?>
                <tr>
                    <td><?= $pergunta['pergunta']; ?></td>
                    <?php foreach ($pergunta['alternativas'] as $alternativa): ?>
                        <?php foreach ($alternativa['respostas'] as $resposta): ?>
                            <td class="text-right <?= $resposta == 'null' ? 'text-muted' : ''; ?>"><?= $resposta == 'null' ? '' : $resposta; ?></td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>

            <?php foreach ($dadosAvaliado as $pergunta): ?>
                <tr>
                    <th colspan="5" class="active"><?= $pergunta['pergunta']; ?></th>
                </tr>
                <?php foreach ($pergunta['alternativas'] as $alternativa): ?>
                    <tr>
                        <td><?= $alternativa['alternativa']; ?></td>
                        <td class="text-right"><?= $alternativa['peso']; ?></td>
                        <?php foreach ($alternativa['respostas'] as $resposta): ?>
                            <td class="text-right <?= $resposta == 'null' ? 'text-muted' : ''; ?>"><?= $resposta == 'null' ? '' : $resposta; ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>

        <?php endif; ?>
        </tbody>
    </table>

    <br/>
    <!--<div class="table-responsive">-->
    <?php if ($dadosAvaliacao->tipo === 'P'): ?>
        <table class="table table-bordered table-condensed resultado" style="overflow: wrap;">
            <tr>
                <th colspan="2" class="active">Aspectos positivos</th>
            </tr>
            <tr>
                <td colspan="2"><?= $dadosAvaliacao->pontos_fortes ?>&nbsp;</td>
            </tr>
            <tr>
                <th colspan="2" class="active">Aspectos negativos</th>
            </tr>
            <tr>
                <td colspan="2"><?= $dadosAvaliacao->pontos_fracos ?>&nbsp;</td>
            </tr>
            <tr class="active">
                <th>Feedback 1&ordf; avaliação | Plano Ações de Melhorias</th>
                <th class="text-center">Data</th>
            </tr>
            <tr>
                <td><?= $dadosAvaliacao->feedback1 ?>&nbsp;</td>
                <td style="white-space: nowrap;"><?= $dadosAvaliacao->data_feedback1 ?>&nbsp;</td>
            </tr>
        </table>
        <table style="width:100%; text-align: center; page-break-inside: avoid;" class="parecer_final">
            <tr>
                <td style="border-bottom: 1px solid #000;"></td>
                <td></td>
                <td style="border-bottom: 1px solid #000;"></td>
            </tr>
            <tr>
                <td>Assinatura do gestor</td>
                <td></td>
                <td>Assinatura do colaborador</td>
            </tr>
            <tr>
                <td colspan="3"><br></td>
            </tr>
        </table>
        <table class="table table-bordered table-condensed resultado" style="overflow: wrap;">
            <tr class="active">
                <th>Feedback 2&ordf; avaliação | Plano Ações de Melhorias</th>
                <th class="text-center">Data</th>
            </tr>
            <tr>
                <td><?= $dadosAvaliacao->feedback2 ?>&nbsp;</td>
                <td style="white-space: nowrap;"><?= $dadosAvaliacao->data_feedback2 ?>&nbsp;</td>
            </tr>
        </table>
        <table style="width:100%; text-align: center; page-break-inside: avoid;" class="parecer_final">
            <tr>
                <td style="border-bottom: 1px solid #000;"></td>
                <td></td>
                <td style="border-bottom: 1px solid #000;"></td>
            </tr>
            <tr>
                <td>Assinatura do gestor</td>
                <td></td>
                <td>Assinatura do colaborador</td>
            </tr>
            <tr>
                <td colspan="3"><br></td>
            </tr>
        </table>
        <table class="table table-bordered table-condensed resultado" style="overflow: wrap;">
            <tr class="active">
                <th>Feedback 3&ordf; avaliação | Plano Ações de Melhorias</th>
                <th class="text-center">Data</th>
            </tr>
            <tr>
                <td><?= $dadosAvaliacao->feedback3 ?>&nbsp;</td>
                <td style="white-space: nowrap;"><?= $dadosAvaliacao->data_feedback3 ?>&nbsp;</td>
            </tr>
        </table>
        <table style="width:100%; text-align: center; page-break-inside: avoid;" class="parecer_final">
            <tr>
                <td style="border-bottom: 1px solid #000;"></td>
                <td></td>
                <td style="border-bottom: 1px solid #000;"></td>
            </tr>
            <tr>
                <td>Assinatura do gestor</td>
                <td></td>
                <td>Assinatura do colaborador</td>
            </tr>
            <tr>
                <td colspan="3"><br></td>
            </tr>
        </table>
        <table style="width:100%; text-align: center; page-break-inside: avoid;" class="parecer_final">
            <tr>
                <td colspan="3" style="text-align: left;">
                    <strong>Parecer final:</strong>
                    (&nbsp;&nbsp;&nbsp;) Efetivar &nbsp; (&nbsp;&nbsp;&nbsp;) Dispensar
                </td>
            </tr>
            <tr>
                <td colspan="3"><br><br><br></td>
            </tr>
        </table>
    <?php else: ?>
        <?php foreach ($dadosAvaliadores as $k => $dadosAvaliador): ?>
            <table class="table table-bordered table-condensed resultado" style="overflow: wrap;">
                <tr>
                    <th colspan="3" class="text-center">Resultado <?= $k + 1 ?>&ordf; avaliação</th>
                </tr>
                <tr>
                    <th colspan="3" class="active">Aspectos positivos</th>
                </tr>
                <tr>
                    <td colspan="3"><?= $dadosAvaliador->pontos_fortes ?>&nbsp;</td>
                </tr>
                <tr>
                    <th colspan="3" class="active">Aspectos negativos</th>
                </tr>
                <tr>
                    <td colspan="3"><?= $dadosAvaliador->pontos_fracos ?>&nbsp;</td>
                </tr>
                <tr>
                    <th colspan="3" class="active">Feedback</th>
                </tr>
                <tr>
                    <td colspan="3"><?= $dadosAvaliador->observacoes ?>&nbsp;</td>
                </tr>
                <!--                    </table>
                                    <table style="width:100%; text-align: center; page-break-inside: avoid;" class="parecer_final">
                <tr>
                    <th colspan="3" class="active">Observações</th>
                </tr>-->
                <tr>
                    <td colspan="3" style="border: 0;"><br>Observações:
                        __________________________________________________________________________________________________________________<br>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border: 0;">
                        ________________________________________________________________________________________________________________________________<br>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border: 0;">
                        ________________________________________________________________________________________________________________________________<br>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border: 0;">
                        ________________________________________________________________________________________________________________________________<br>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border: 0;">
                        ________________________________________________________________________________________________________________________________<br>
                    </td>
                </tr>
                <tr>
                    <td style="border: 0; text-align: center;"><br>Data: _____/_____/__________<br>&nbsp;</td>
                    <td style="border: 0; text-align: center;"><br>________________________________________<br>Assinatura
                        Gestor
                    </td>
                    <td style="border: 0; text-align: center;"><br>________________________________________<br>Assinatura
                        Colaborador
                    </td>
                </tr>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
    <div style="border-top: 5px solid #ddd;"></div>
    <!--</div>-->
</div>
</body>
</html>