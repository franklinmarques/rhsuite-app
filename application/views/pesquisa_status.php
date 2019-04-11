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
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="4">
                            <h1 class="text-center"><?= $pesquisa->titulo ?></h1>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td colspan="2">
                            <h5><strong>Avaliação: </strong><?= $pesquisa->nome ?></h5>
                            <h5><strong>Data atual: </strong><?= date('d/m/Y') ?></h5>
                        </td>
                        <td>
                            <h5><strong>Data início pesquisa: </strong><?= $pesquisa->data_inicio ?></h5>
                            <h5><strong>Data término pesquisa: </strong><?= $pesquisa->data_termino ?></h5>
                        </td>
                        <td class="text-right">
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>
                        </td>
                    </tr>

                    <?php if (isset($avaliado)): ?>
                        <tr style='border-top: 5px solid #ddd;'>
                            <th>Colaborador alvo da pesquisa</th>
                            <th>Função</th>
                            <th>Depto/área/setor</th>
                            <th>Data de início de atividades</th>
                        </tr>
                        <tr>
                            <td><?= $avaliado->nome ?></td>
                            <td><?= $avaliado->funcao ?></td>
                            <td><?= $avaliado->depto ?></td>
                            <td><?= $avaliado->data_admissao ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!--<div class="table-responsive">-->
            <table class="table table-bordered table-condensed">
                <thead>
                    <tr class='active'>
                        <th>Colaboradores pesquisados</th>
                        <th>Função</th>
                        <th>Depto/área/setor</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($avaliadores as $avaliador): ?>
                        <tr>
                            <td><?= $avaliador->nome ?></td>
                            <td><?= $avaliador->funcao ?></td>
                            <td><?= $avaliador->depto ?></td>
                            <td class="text-center <?= ($avaliador->status ? 'text-success' : 'text-danger') ?>">
                                <strong><?= ($avaliador->status ? 'Respondida' : 'Responder') ?></strong>
                            </td>
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
        document.title = 'CORPORATE RH - LMS - <?= $pesquisa->titulo ?>';
    });
</script>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>
