<?php
require_once "header.php";
?>
<style>
    <?php if ($this->agent->is_mobile()): ?>
    .table-bordered {
        font-size: xx-small;
    }

    <?php endif; ?>
    /*    .modal, .modal-backdrop {
            overflow: auto;
            height: 100%;
        }    
        #main-content .modal, .modal-backdrop {
            position: absolute;
        }    
        #main-content .modal-backdrop {
            z-index: 1001;
        }    
        .wrapper {
            overflow: auto;
            position:relative;
            height: 90%;
            min-height: 600px;
        }
        #main-content {
            height: 100%;
        }*/
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div style="color: #000;">
            <table class="table table-condensed avaliado">
                <thead>
                <tr class="hidden-xs hidden-sm">
                    <th style="width: auto;">
                        <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                             style="height: auto; width: auto; max-height: 60px; max-width:94px; vertical-align: middle; padding: 0 10px 5px 0;">
                    </th>
                    <th style="width: 100%; vertical-align: top;" colspan="2">
                        <p>
                            <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>" align="left"
                                 style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                        </p>
                    </th>
                </tr>
                <tr>
                    <th colspan="3">
                        <?php if ($this->agent->is_mobile()): ?>
                            <h4 class="text-center"><?= mb_strtoupper($dadosAvaliacao->titulo, 'UTF-8') ?></h4>
                        <?php elseif ($is_pdf == false): ?>
                            <h1 class="text-center"><?= mb_strtoupper($dadosAvaliacao->titulo, 'UTF-8') ?></h1>
                        <?php else: ?>
                            <h2 class="text-center"><?= mb_strtoupper($dadosAvaliacao->titulo, 'UTF-8') ?></h2>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <?php if ($this->agent->is_mobile()): ?>
                        <td colspan="3">
                            <button class="btn btn-default btn-sm" style="float: right;"
                                    onclick="javascript:history.back();">
                                <i class="fa fa-reply"></i> Voltar
                            </button>
                            <h6><strong>Data de início de atividades: </strong><?= $dadosAvaliacao->data_inicio ?></h6>
                            <h6><strong>Data atual: </strong><?= $dadosAvaliacao->data_atual ?></h6>

                        </td>
                    <?php elseif ($is_pdf == false): ?>
                        <td colspan="2">
                            <h5><strong>Avaliação: </strong><?= $dadosAvaliacao->nome ?></h5>
                            <h5><strong>Data de início de atividades: </strong><?= $dadosAvaliacao->data_inicio ?></h5>
                            <h5><strong>Data atual: </strong><?= $dadosAvaliacao->data_atual ?></h5>
                        </td>
                    <?php else: ?>
                        <td colspan="2">
                            <h6><strong>Avaliação: </strong><?= $dadosAvaliacao->nome ?></h6>
                            <h6><strong>Data de início de atividades: </strong><?= $dadosAvaliacao->data_inicio ?></h6>
                            <h6><strong>Data atual: </strong><?= $dadosAvaliacao->data_atual ?></h6>
                        </td>
                    <?php endif; ?>
                    <td class="text-right">
                        <?php if ($this->agent->is_mobile() == false): ?>
                            <?php if ($dadosAvaliacao->tipo == 'P'): ?>
                                <a class="btn btn-sm btn-primary"
                                   href="<?= site_url('pdi/gerenciar/' . $dadosAvaliacao->id_colaborador); ?>"><i
                                            class="fa fa-briefcase"></i> Ir para o PDI - Plano de Desenvolvimento
                                    Individual</a>
                            <?php endif; ?>
                            <?php if ($is_pdf == false): ?>
                                <a id="pdf" class="btn btn-sm btn-info"
                                   href="<?= site_url('avaliacaoexp_avaliados/pdfRelatorio/' . $this->uri->rsegment(3)); ?>"
                                   title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar
                                    PDF</a>
                                <button class="btn btn-sm btn-default" onclick="history.back()"><i
                                            class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                </button>
                                <div class="checkbox">
                                    <label>
                                        <input id="ocultar_avaliadores" type="checkbox" autocomplete="off"> Não
                                        apresentar nome dos avaliadores
                                    </label>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if ($this->agent->is_mobile()): ?>
                    <tr style='border-top: 5px solid #ddd; font-size: x-small;'>
                        <th>Colaborador avaliado</th>
                        <th>Função</th>
                        <th>Depto/área/setor</th>
                    </tr>
                    <tr style='border-bottom: 5px solid #ddd; font-size: x-small;'>
                        <td><?= $dadosAvaliacao->colaborador ?></td>
                        <td><?= $dadosAvaliacao->funcao ?></td>
                        <td><?= implode('/', array_filter(array($dadosAvaliacao->depto, $dadosAvaliacao->area, $dadosAvaliacao->setor))) ?></td>
                    </tr>
                <?php else: ?>
                    <tr style='border-top: 5px solid #ddd;'>
                        <th>Colaborador avaliado</th>
                        <th>Função</th>
                        <th>Depto/área/setor</th>
                    </tr>
                    <tr style='border-bottom: 5px solid #ddd;'>
                        <td><?= $dadosAvaliacao->colaborador ?></td>
                        <td><?= $dadosAvaliacao->funcao ?></td>
                        <td><?= implode('/', array_filter(array($dadosAvaliacao->depto, $dadosAvaliacao->area, $dadosAvaliacao->setor))) ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <!--<div class="table-responsive">-->
            <table class="table table-bordered table-condensed avaliadores">
                <thead>
                <tr class='active'>
                    <?php if ($this->agent->is_mobile()): ?>
                        <?php if ($dadosAvaliacao->tipo === 'P'): ?>
                            <th colspan="2" class="text-center">1&ordf; Avaliador (Av1)</th>
                            <th colspan="2" class="text-center">2&ordf; Avaliador (Av2)</th>
                            <th colspan="2" class="text-center">3&ordf; Avaliador (Av3)</th>
                        <?php else: ?>
                            <th colspan="2" class="text-center">1&ordf; Avaliador</th>
                            <th colspan="2" class="text-center">2&ordf; Avaliador</th>
                            <th colspan="2" class="text-center">3&ordf; Avaliador</th>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($dadosAvaliacao->tipo === 'P'): ?>
                            <th colspan="4" class="text-center">1&ordf; Avaliador (Av1)</th>
                            <th colspan="4" class="text-center">2&ordf; Avaliador (Av2)</th>
                            <th colspan="4" class="text-center">3&ordf; Avaliador (Av3)</th>
                        <?php else: ?>
                            <th colspan="4" class="text-center">1&ordf; Avaliador</th>
                            <th colspan="4" class="text-center">2&ordf; Avaliador</th>
                            <th colspan="4" class="text-center">3&ordf; Avaliador</th>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
                <tr class='active'>
                    <th class="hidden-xs hidden-sm">Data programada</th>
                    <th class="avaliador">Avaliador</th>
                    <th class="hidden-xs hidden-sm">Data de realização</th>
                    <th>Resultado</th>
                    <th class="hidden-xs hidden-sm">Data programada</th>
                    <th class="avaliador">Avaliador</th>
                    <th class="hidden-xs hidden-sm">Data de realização</th>
                    <th>Resultado</th>
                    <th class="hidden-xs hidden-sm">Data programada</th>
                    <th class="avaliador">Avaliador</th>
                    <th class="hidden-xs hidden-sm">Data de realização</th>
                    <th>Resultado</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php foreach ($dadosAvaliadores as $dadosAvaliador): ?>
                        <td class="text-center hidden-xs hidden-sm"><?= $dadosAvaliador->data_avaliacao ?></td>
                        <td class="avaliador"><?= $dadosAvaliador->nome ?></td>
                        <td class="text-center hidden-xs hidden-sm"><?= $dadosAvaliador->data_realizacao ?></td>
                        <td class="text-right"><?= round(array_sum($dadosAvaliador->resultado), 1) ?>%</td>
                    <?php endforeach; ?>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <?php if ($dadosAvaliacao->tipo == 'P'): ?>
                        <?php if ($this->agent->is_mobile()): ?>
                            <td colspan="6"
                                style="border-left: 0; border-right: 0; vertical-align: bottom;">
                                <strong>Nota de corte: </strong><?= $dadosAvaliacao->nota_corte ?>%
                                <br>
                                <?php
                                $statusResultado = '';
                                if ($dadosAvaliacao->resultado_final > $dadosAvaliacao->nota_corte) {
                                    $statusResultado = ' class="text-success"';
                                } elseif ($dadosAvaliacao->resultado_final < $dadosAvaliacao->nota_corte) {
                                    $statusResultado = ' class="text-danger"';
                                }
                                ?>
                                <strong>Resultado final: <span
                                            style="font-size: small;"<?= $statusResultado ?>><?= $dadosAvaliacao->resultado_final ?>
                                        %</span></strong>
                                <br>
                                <strong>Parecer final:
                                    <?php if ($dadosAvaliacao->parecer_final == 'E'): ?>
                                        <span style="font-size: 16px;" class="text-success">Efetivado</span>
                                    <?php elseif ($dadosAvaliacao->parecer_final == 'D'): ?>
                                        <span style="font-size: 16px;" class="text-danger">Dispensado</span>
                                    <?php elseif ($dadosAvaliacao->parecer_final == 'A'): ?>
                                        <span style="font-size: 16px;">Em avaliação</span>
                                    <?php else: ?>
                                        <span class="text-muted">Não avaliado</span>
                                    <?php endif; ?>
                                </strong>
                            </td>
                        <?php else: ?>
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
                                        <span style="font-size: 16px;" class="text-success">Efetivado</span>
                                    <?php elseif ($dadosAvaliacao->parecer_final == 'D'): ?>
                                        <span style="font-size: 16px;" class="text-danger">Dispensado</span>
                                    <?php elseif ($dadosAvaliacao->parecer_final == 'A'): ?>
                                        <span style="font-size: 16px;">Em avaliação</span>
                                    <?php else: ?>
                                        <span class="text-muted">Não avaliado</span>
                                    <?php endif; ?>
                                </strong>
                            </td>
                        <?php endif; ?>
                    <?php elseif ($dadosAvaliacao->tipo == 'A'): ?>
                        <?php if ($this->agent->is_mobile()): ?>
                            <td colspan="6"
                                style="border-left: 0; border-right: 0; vertical-align: bottom;">
                                <strong>Nota de corte: </strong><?= $dadosAvaliacao->nota_corte ?>%
                                <br>
                                <?php
                                $statusResultado = '';
                                if ($dadosAvaliacao->resultado_final > $dadosAvaliacao->nota_corte) {
                                    $statusResultado = ' class="text-success"';
                                } elseif ($dadosAvaliacao->resultado_final < $dadosAvaliacao->nota_corte) {
                                    $statusResultado = ' class="text-danger"';
                                }
                                ?>
                                <strong>Resultado final: <span
                                            style="font-size: small;"<?= $statusResultado ?>><?= $dadosAvaliacao->resultado_final ?>
                                        %</span></strong>
                                <br>
                            </td>
                        <?php else: ?>
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
                    <?php endif; ?>
                </tr>
                </tfoot>
            </table>
            <!--</div>-->

            <br/>
            <!--<div class="table-responsive">-->
            <table class="table table-bordered table-condensed avaliacao" width="100%">
                <thead>
                <?php if ($dadosAvaliacao->tipo === 'P'): ?>
                    <tr class="active">
                        <th rowspan="2">Critérios de avaliação</th>
                        <?php if ($this->agent->is_mobile()): ?>
                            <?php foreach ($alternativas as $alternativa): ?>
                                <th colspan="3" class="text-center"
                                    style="font-size: 8.5px;"><?= $alternativa->nome; ?></th>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach ($alternativas as $alternativa): ?>
                                <th colspan="3" class="text-center"><?= $alternativa->nome; ?></th>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tr>
                    <tr class="active">
                        <?php if ($this->agent->is_mobile()): ?>
                            <?php foreach ($alternativas as $alternativa): ?>
                                <th class="text-center">1</th>
                                <th class="text-center">2</th>
                                <th class="text-center">3</th>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach ($alternativas as $alternativa): ?>
                                <th class="text-center">Av1</th>
                                <th class="text-center">Av2</th>
                                <th class="text-center">Av3</th>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <th>Média percentual</th>
                        <?php if ($this->agent->is_mobile()): ?>
                            <?php foreach ($alternativas as $alternativa): ?>
                                <?php foreach ($alternativa->media as $media): ?>
                                    <th class="text-right"><?= (strlen($media) ? $media : '') ?></th>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach ($alternativas as $alternativa): ?>
                                <?php foreach ($alternativa->media as $media): ?>
                                    <th class="text-right"><?= (strlen($media) ? $media . '%' : '') ?></th>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th>Critérios de avaliação</th>
                        <?php if ($this->session->userdata('tipo') != 'funcionario'): ?>
                            <th class="text-center">Peso</th>
                        <?php endif; ?>
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
                                <?php if ($this->session->userdata('tipo') != 'funcionario'): ?>
                                    <td class="text-right"><?= $alternativa['peso']; ?></td>
                                <?php endif; ?>
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
                <table class="table table-bordered table-condensed resultado">
                    <tr>
                        <th colspan="2" class="active">Aspectos positivos</th>
                    </tr>
                    <tr>
                        <td colspan="2" style="word-wrap: break-word;"><?= $dadosAvaliacao->pontos_fortes ?>&nbsp;</td>
                    </tr>
                    <tr>
                        <th colspan="2" class="active">Aspectos negativos</th>
                    </tr>
                    <tr>
                        <td colspan="2" style="word-wrap: break-word;"><?= $dadosAvaliacao->pontos_fracos ?>&nbsp;</td>
                    </tr>
                    <tr class="active">
                        <th>Feedback 1&ordf; avaliação | Plano Ações de Melhorias</th>
                        <th class="text-center">Data</th>
                    </tr>
                    <tr>
                        <td style="word-break: break-all;"><?= $dadosAvaliacao->feedback1 ?>&nbsp;</td>
                        <td style="white-space: nowrap;"><?= $dadosAvaliacao->data_feedback1 ?>&nbsp;</td>
                    </tr>
                    <tr class="active">
                        <th>Feedback 2&ordf; avaliação | Plano Ações de Melhorias</th>
                        <th class="text-center">Data</th>
                    </tr>
                    <tr>
                        <td style="word-break: break-all;"><?= $dadosAvaliacao->feedback2 ?>&nbsp;</td>
                        <td style="white-space: nowrap;"><?= $dadosAvaliacao->data_feedback2 ?>&nbsp;</td>
                    </tr>
                    <tr class="active">
                        <th>Feedback 3&ordf; avaliação | Plano Ações de Melhorias</th>
                        <th class="text-center">Data</th>
                    </tr>
                    <tr>
                        <td style="word-break: break-all;"><?= $dadosAvaliacao->feedback3 ?>&nbsp;</td>
                        <td style="white-space: nowrap;"><?= $dadosAvaliacao->data_feedback3 ?>&nbsp;</td>
                    </tr>
                </table>
            <?php else: ?>
                <?php foreach ($dadosAvaliadores as $k => $dadosAvaliador): ?>
                    <table class="table table-bordered table-condensed resultado">
                        <thead>
                        <tr>
                            <th class="text-center">Resultado <?= $k + 1 ?>&ordf; avaliação</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="active">
                            <th>Aspectos positivos</th>
                        </tr>
                        <tr>
                            <td style="word-break: break-all;"><?= $dadosAvaliador->pontos_fortes ?>&nbsp;</td>
                        </tr>
                        <tr class="active">
                            <th>Aspectos negativos</th>
                        </tr>
                        <tr>
                            <td style="word-break: break-all;"><?= $dadosAvaliador->pontos_fracos ?>&nbsp;</td>
                        </tr>
                        <tr class="active">
                            <th>Feedback</th>
                        </tr>
                        <tr>
                            <td style="word-break: break-all;"><?= $dadosAvaliador->observacoes ?>&nbsp;</td>
                        </tr>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php endif; ?>

            <!--</div>-->
        </div>
    </section>
</section>
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - <?= $dadosAvaliacao->titulo ?>';
    });
</script>

<?php require_once "end_js.php"; ?>

<script>
    $('#ocultar_avaliadores').on('change', function () {
        var search = '';
        if ($(this).is(':checked')) {
            search = '/q?ocultar_avaliadores=1';
            $('.avaliador').hide();
        } else {
            $('.avaliador').show();
        }
        $('#pdf').prop('href', '<?= site_url('avaliacaoexp_avaliados/pdfRelatorio/' . $this->uri->rsegment(3)); ?>' + search);
    });
</script>

<?php require_once "end_html.php"; ?>
