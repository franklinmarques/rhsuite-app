<?php
require_once APPPATH . "views/header.php";
?>
<style>
    .btn-success{
        background-color: #5cb85c;
        border-color: #4cae4c;
        color: #fff;
    }
    .btn-primary {
        background-color: #337ab7 !important;
        border-color: #2e6da4 !important;
        color: #fff;
    }
    .btn-info {
        color: #fff;
        background-color: #5bc0de;
        border-color: #46b8da;
    }
    .btn-warning {
        color: #fff;
        background-color: #f0ad4e;
        border-color: #eea236;
    }
    .btn-danger {
        color: #fff;
        background-color: #d9534f;
        border-color: #d43f3a;
    }
    .text-nowrap{
        white-space: nowrap;
    }    
</style>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">

        <table class="table table-condensed cargo">
            <thead>
                <tr>
                    <th colspan="2">
                        <?php if ($is_pdf == false): ?>
                            <h1 class="text-center">MAPEAMENTO DE COMPETÊNCIAS</h1>
                        <?php else: ?>
                            <h2 class="text-center">MAPEAMENTO DE COMPETÊNCIAS</h2>
                        <?php endif; ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td>
                        <?php if ($is_pdf == false): ?>
                            <h5><strong>Cargo/função: </strong><?= $cargo_funcao ?></h5>
                            <h5><strong>Data atual: </strong><?= $data_atual ?></h5>
                        <?php else: ?>
                            <h6><strong>Cargo/função: </strong><?= $cargo_funcao ?></h6>
                            <h6><strong>Data atual: </strong><?= $data_atual ?></h6>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?php if ($is_pdf == false): ?>
                            <a class="btn btn-sm btn-danger" href="<?= site_url('competencias/cargos/pdfMapeamento/' . $this->uri->rsegment(3)); ?>" title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="2">Legenda</th>
                </tr>
                <tr>
                    <td>
                        <div class="bg-info">
                            <ul class="text-info">
                                <li><small>Peso (0 a 100) - Peso ou grau de importância do comportamento;</small></li>
                                <li><small>Atitude (0 a 100) - Grau de Atitude demandada;</small></li>
                                <li><small>Nível (0 a 5) - Nível de Conhecimento e Habilidade Demandados;</small></li>
                                <ol start="0">
                                    <li><small>Mínimo conhecimento;</small></li>
                                    <li><small>Conhecimento básico, habilidade mínima;</small></li>
                                    <li><small>Conhecimento e prática básica;</small></li>
                                    <li><small>Conhecimento e prática intermediários;</small></li>
                                    <li><small>Conhecimento e prática avançados;</small></li>
                                    <li><small>Especialista - Multiplicador.</small></li>
                                </ol>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <div class="bg-info">
                            <ul class="text-info" type="none">
                                <li>&nbsp;</li>
                                <li><small>NCTf - Nível de Competência Técnica Demandado pelo Cargo-Função</small></li>
                                <li><small>NCCf - Nível de Competência Comportamental Demandado pelo Cargo-Função</small></li>
                                <li><small>IDcf - Índice de Desempenho Demandado pelo Cargo-Função</small></li>
                                <li>&nbsp;</li>
                            </ul>
                        </div>
                        <table class="competencias table table-bordered table-condensed">
                            <thead>
                                <tr class="active">
                                    <th>NCTf</th>
                                    <th nowrap>Peso (CT)</th>
                                    <th>NCCf</th>
                                    <th nowrap>Peso (CC)</th>
                                    <th>IDcf</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-right">
                                    <td><?= $nctf ?></td>
                                    <td><?= $peso_ct ?></td>
                                    <td><?= $nccf ?></td>
                                    <td><?= $peso_cc ?></td>
                                    <td><?= $idcf ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php foreach ($ct as $competencia): ?>
            <!--<div class="table-responsive">-->
            <table class="competencias table table-bordered table-condensed">
                <thead>
                    <tr class='success'>
                        <th colspan="5" class='text-success'><?= $competencia['nome'] ?></th>
                    </tr>
                    <tr class='active'>
                        <th rowspan="2" style='vertical-align: middle;'>Comportamento/dimensão</th>
                        <th colspan="4" class="text-center">Cargo/função</th>
                    </tr>
                    <tr class='active'>
                        <th>Peso</th>
                        <th>Nível</th>
                        <th>Atitude</th>
                        <th>IDcf</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($competencia['dimensao'] as $dm): ?>

                        <tr class='text-right'>
                            <td class='text-left'><?= $dm->nome ?></td>
                            <td><?= round($dm->peso, 3) ?></td>
                            <td><?= $dm->nivel ?></td>
                            <td><?= $dm->atitude ?></td>
                            <td><?= round($dm->indice, 3) ?></td>
                        </tr>	
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!--</div>-->
        <?php endforeach; ?>

        <?php foreach ($cc as $competencia): ?>
            <!--<div class="table-responsive">-->
            <table class="competencias table table-bordered table-condensed">
                <thead>
                    <tr class='success'>
                        <th colspan="5" class='text-success'><?= $competencia['nome'] ?></th>
                    </tr>
                    <tr class='active'>
                        <th rowspan="2" style='vertical-align: middle;'>Comportamento/dimensão</th>
                        <th colspan="4" class="text-center">Cargo/função</th>
                    </tr>
                    <tr class='active'>
                        <th>Peso</th>
                        <th>Nível</th>
                        <th>Atitude</th>
                        <th>IDcf</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($competencia['dimensao'] as $dm): ?>

                        <tr class='text-right'>
                            <td class='text-left'><?= $dm->nome ?></td>
                            <td><?= round($dm->peso, 3) ?></td>
                            <td><?= $dm->nivel ?></td>
                            <td><?= $dm->atitude ?></td>
                            <td><?= round($dm->indice, 3) ?></td>
                        </tr>	
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!--</div>-->
        <?php endforeach; ?>

    </section>
</section>
<!--main content end-->

<?php
require_once APPPATH . "views/end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Mapeamento de Competências';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<?php
require_once APPPATH . "views/end_html.php";
?>
