<?php require_once APPPATH . 'views/header.php'; ?>

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
            <table class="table table-condensed avaliado">
                <thead>
                <tr>
                    <th colspan="3">
                        <?php if ($is_pdf == false): ?>
                            <h1 class="text-center"><strong>CONTROLE DE FREQUÊNCIA INDIVIDUAL</strong></h1>
                        <?php else: ?>
                            <h2 class="text-center"><strong>CONTROLE DE FREQUÊNCIA INDIVIDUAL</strong></h2>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td colspan="2">
                        <?php if ($is_pdf == false): ?>
                            <h3>Identificação do prestador de serviços</h3>
                            <h5><strong>Nome: </strong><?= $paciente->instituicao_nome ?></h5>
                            <h5><strong>CNPJ: </strong><?= $paciente->instituicao_cnpj ?></h5>
                        <?php else: ?>
                            <h4>Identificação do prestador de serviços</h4>
                            <h6><strong>Nome: </strong><?= $paciente->instituicao_nome ?></h6>
                            <h6><strong>CNPJ: </strong><?= $paciente->instituicao_cnpj ?></h6>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?php if ($is_pdf == false): ?>
                            <a class="btn btn-sm btn-danger"
                               href="<?= site_url('apontamento_pacientes/pdfFrequencia/' . $this->uri->rsegment(3)); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="3"><h3>Dados do paciente</h3></th>
                </tr>
                <tr>
                    <td>Nome: <?= $paciente->nome ?></td>
                    <td>Sexo: <?= $paciente->sexo ?></td>
                    <td>Data de nascimento: <?= $paciente->data_nascimento ?></td>
                </tr>
                <tr>
                    <td>CPF: <?= $paciente->cpf ?></td>
                    <td>Cadastro Municipal: <?= $paciente->cadastro_municipal ?></td>
                    <td>HD: <?= $paciente->hd ?></td>
                </tr>
                <tr>
                    <td>Mãe ou responsável: <?= $paciente->nome_responsavel_1 ?></td>
                    <td>Telefone: <?= $paciente->telefone_fixo_1 ?></td>
                    <td>Endereço: <?= $paciente->logradouro ?></td>
                </tr>
                <tr>
                    <td>Número: <?= $paciente->numero ?></td>
                    <td>Complemento: <?= $paciente->complemento ?></td>
                    <td>Bairro: <?= $paciente->bairro ?></td>
                </tr>
                <tr style='border-bottom: 5px solid #ddd;'>
                    <td>Cidade: <?= $paciente->cidade ?></td>
                    <td>Estado: <?= $paciente->estado ?></td>
                    <td>CEP: <?= $paciente->cep ?></td>
                </tr>
                <tr style='border-top: 5px solid #ddd;'>
                    <th colspan="3"><h3>Declaração do
                            mês: <?= $paciente->mes_ingresso . ' de ' . $paciente->ano_ingresso ?></h3></th>
                </tr>
                <tr style='border-bottom: 5px solid #ddd;'>
                    <td colspan="3" style="text-indent: 2em;">Declaramos que neste mês, o paciente acima identificado,
                        foi submetido às atividades/procedimentos abaixo relacionadas, conforme assinaturas do
                        paciente/responsável e do profissional realizador do atendimento.
                    </td>
                </tr>
                </tbody>
            </table>

            <br/>
            <!--<div class="table-responsive">-->
            <table class="table table-bordered table-condensed avaliacao">
                <thead>
                <tr class="active">
                    <th colspan="5" class="text-center"><h3><strong>PROGRAMA DE APOIO À PESSOA COM DEFICIÊNCIA</strong>
                        </h3></th>
                </tr>
                <tr class="active">
                    <th class="text-center">Data</th>
                    <th class="text-center">Horário início</th>
                    <th class="text-center">Atividades/procedimentos</th>
                    <th class="text-center">Paciente/responsável</th>
                    <th class="text-center">Profissional</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
            <!--</div>-->
        </div>
    </section>
</section>
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Controle de Frequência Individual';
    });
</script>

<?php
require_once APPPATH . 'views/end_js.php';
require_once APPPATH . 'views/end_html.php';
?>
