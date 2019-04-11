<?php
require_once APPPATH . 'views/header.php';
?>

<style>

    table tr td:first-child {
        white-space: nowrap;
    }

</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div style="color: #000;">
            <table class="table table-condensed pdi">
                <thead>
                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="2">
                        <div class="row">
                            <div class="col-sm-12">
                                <img src="<?= base_url('imagens/usuarios/' . $empresa->foto) ?>" align="left"
                                     style="height: auto; width: auto; max-height: 92px; max-width: 254px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                <p class="text-left">
                                    <img src="<?= base_url('imagens/usuarios/' . $empresa->foto_descricao) ?>"
                                         align="left"
                                         style="height: auto; width: auto; max-height: 92px; max-width: 508px; vertical-align: middle; padding: 0 10px 5px 5px;">
                                </p>
                            </div>
                        </div>
                    </th>
                    <td class="text-right">
                        <?php if ($is_pdf == false): ?>
                            <a id="pdf" class="btn btn-sm btn-info"
                               href="<?= site_url('facilities/ordensServico/pdf/' . $this->uri->rsegment(3)); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="3">
                        <?php if ($is_pdf == false): ?>
                            <h2 class="text-center">REQUISIÇÃO DE ORDEM DE SERVIÇOS</h2>
                        <?php else: ?>
                            <h3 class="text-center">REQUISIÇÃO DE ORDEM DE SERVIÇOS</h3>
                        <?php endif; ?>
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

            <table id="descricao_problema" class="table campos table-condensed">
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

            <table id="observacoes" class="table campos table-condensed">
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

            <table id="resolucao_satisfatoria" class="table campos table-condensed">
                <thead>
                <tr>
                    <th colspan="2">Pesquisa de satisfação</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-nowrap">A resolução da necessidade/problema reportado na O. S. foi:</td>
                    <td width="100%" class="<?= $classe_resolucao_satisfatoria; ?>">
                        <strong><?= $resolucao_satisfatoria; ?></strong></td>
                </tr>
                </tbody>
            </table>

            <table id="observacoes_positivas" class="table campos table-condensed">
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

            <table id="observacoes_negativas" class="table campos table-condensed">
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
    </section>
</section>
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Requisição de Ordem de Serviços';
    });
</script>

<?php
require_once APPPATH . 'views/end_js.php';
require_once APPPATH . 'views/end_html.php';
?>
