<?php
require_once "header.php";
?>
<style>
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
            <table class="table table-condensed pdi">
                <thead>
                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="3">
                        <div class="row">
                            <div class="col-sm-12">
                                <img src="<?= base_url($foto) ?>" align="left"
                                     style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                <p class="text-left">
                                    <img src="<?= base_url($foto_descricao) ?>" align="left"
                                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                </p>
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="3">
                        <?php if ($is_pdf == false): ?>
                            <h2 class="text-center">PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL</h2>
                        <?php else: ?>
                            <h3 class="text-center">PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL</h3>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td colspan="2">
                        <?php if ($is_pdf == false): ?>
                            <h5><strong>PDI: </strong><?= $dadosPDI->nome ?></h5>
                            <h5><strong>Período de
                                    desenvolvimento: </strong><span<?= ($dadosPDI->data_valida == 'ok' ? '' : ' class="text-danger"') ?>><?= $dadosPDI->data_inicio . ' a ' . $dadosPDI->data_termino ?></span>
                            </h5>
                            <h5><strong>Data atual: </strong><?= $dadosPDI->data_atual ?></h5>
                        <?php else: ?>
                            <h6><strong>PDI: </strong><?= $dadosPDI->nome ?></h6>
                            <h6><strong>Período de
                                    desenvolvimento: </strong><span<?= ($dadosPDI->data_valida == 'ok' ? '' : ' class="text-danger"') ?>><?= $dadosPDI->data_inicio . ' a ' . $dadosPDI->data_termino ?></span>
                            </h6>
                            <h6><strong>Data atual: </strong><?= $dadosPDI->data_atual ?></h6>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?php if ($is_pdf == false): ?>
                            <a class="btn btn-sm btn-danger"
                               href="<?= site_url('pdi/pdfRelatorio/' . $this->uri->rsegment(3)); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style='border-top: 5px solid #ddd;'>
                    <th>Colaborador</th>
                    <th>Função</th>
                    <th>Depto/área/setor</th>
                </tr>
                <tr style='border-bottom: 5px solid #ddd;'>
                    <td><?= $dadosPDI->colaborador ?></td>
                    <td><?= $dadosPDI->funcao ?></td>
                    <td><?= $dadosPDI->depto ?></td>
                </tr>
                </tbody>
            </table>

            <br/>
            <!--<div class="table-responsive">-->
            <table class="desenvolvimento table table-condensed">
                <thead>
                <tr>
                    <th>Competência/item a desenvolver</th>
                    <th>Ações para desenvolvimento</th>
                    <th>Resultados esperados</th>
                    <th>Resultados alcançados</th>
                    <th class="text-center">Data início</th>
                    <th class="text-center">Data término</th>
                    <th class="text-center">Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($itensPDI as $itemPDI): ?>
                    <tr>
                        <td style="max-width: 60px; word-wrap: break-word;"><?= $itemPDI->competencia ?></td>
                        <td style="max-width: 80px; word-wrap: break-word;"><?= $itemPDI->descricao; ?></td>
                        <td style="max-width: 80px; word-wrap: break-word;"><?= $itemPDI->expectativa ?></td>
                        <td style="max-width: 80px; word-wrap: break-word;"><?= $itemPDI->resultado ?></td>
                        <td class="text-center"><?= $itemPDI->data_inicio ?></td>
                        <td class="text-center"><?= $itemPDI->data_termino ?></td>
                        <?php
                        $status = '';
                        switch ($itemPDI->status) {
                            case 'A':
                                $itemPDI->status = '<strong>Atrasado</strong>';
                                $status = 'warning text-warning';
                                break;
                            case 'E':
                                $itemPDI->status = '<strong>Em andamento</strong>';
                                $status = 'info text-primary';
                                break;
                            case 'F':
                                $itemPDI->status = '<strong>Finalizado</strong>';
                                $status = 'success text-success';
                                break;
                            case 'C':
                                $itemPDI->status = '<strong>Cancelado</strong>';
                                $status = 'danger text-danger';
                                break;
                            default:
                                $itemPDI->status = 'Não iniciado';
                        }
                        ?>
                        <td class="text-center <?= $status ?>"><?= $itemPDI->status ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <!--</div>-->
        </div>
    </section>
</section>
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL';
    });
</script>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>
