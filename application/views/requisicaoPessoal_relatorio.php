<?php
require_once 'header.php';
?>
<style>
    table tr td:first-child {
        white-space: nowrap;
    }

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
                    <th colspan="2">
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
                    <td class="text-right">
                        <?php if ($is_pdf == false): ?>
                            <a id="pdf" class="btn btn-sm btn-danger"
                               href="<?= site_url('requisicaoPessoal/pdf/' . $this->uri->rsegment(3) . '/q?aprovados=1'); ?>"
                               title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                            <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="mostrar_aprovados" checked autocomplete="off"> Mostrar
                                    candidatos aprovados
                                </label>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="3">
                        <?php if ($is_pdf == false): ?>
                            <h2 class="text-center">REQUISIÇÃO DE PESSOAL</h2>
                        <?php else: ?>
                            <h3 class="text-center">REQUISIÇÃO DE PESSOAL</h3>
                        <?php endif; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr style='border-top: 5px solid #ddd;'>
                    <td><strong>N&ordm; requisição:</strong> <?= $row->id ?></td>
                    <td><strong>Tipo de vaga:</strong> <?= $row->tipo_vaga ?></td>
                    <td><strong>Data da requisição:</strong> <?= $row->data_abertura ?></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Nome da requisição:</strong> <?= $row->numero ?></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Depto/área/setor:</strong> <?= $row->estrutura ?></td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Requisitante:</strong> <?= $row->requisitante ?></td>
                </tr>
                </tbody>
            </table>

            <br/>

            <table id="contrato" class="table table-condensed">
                <thead>
                <tr>
                    <th colspan="2">Dados do contrato e centro de custo</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="active">N&ordm; do contrato:</td>
                    <td width="100%"><?= $row->numero_contrato; ?></td>
                </tr>
                <tr>
                    <td class="active">Regime de contratação:</td>
                    <td><?= $row->regime_contratacao; ?></td>
                </tr>
                <tr>
                    <td class="active">Centro de custo:</td>
                    <td><?= $row->centro_custo; ?></td>
                </tr>
                </tbody>
            </table>

            <table id="vagas" class="table table-condensed">
                <thead>
                <tr>
                    <th colspan="2">Dados da vaga</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="active">Tipo de vaga:</td>
                    <td><?= $row->requisicao_confidencial; ?></td>
                </tr>
                <?php if ($row->cargo or $row->funcao): ?>
                    <tr>
                        <td class="active">Cargo:</td>
                        <td width="100%"><?= $row->cargo; ?></td>
                    </tr>
                    <tr>
                        <td class="active">Função:</td>
                        <td><?= $row->funcao; ?></td>
                    </tr>
                <?php elseif ($row->cargo_funcao_alternativo): ?>
                    <tr>
                        <td class="active">Cargo/Função:</td>
                        <td><?= $row->cargo_funcao_alternativo; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="active">Quantidade de vagas:</td>
                    <td><?= $row->numero_vagas; ?></td>
                </tr>
                <?php if ($row->vagas_deficiente): ?>
                    <tr>
                        <td class="active">Vagas para deficientes:</td>
                        <td>Sim</td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="active">Justificativa da contratação:</td>
                    <td><?= $row->justificativa_contratacao; ?></td>
                </tr>
                <?php if ($row->justificativa_contratacao == 'Substituição'): ?>
                    <tr>
                        <td class="active">Colaborador substituto:</td>
                        <td><?= nl2br($row->colaborador_substituto); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($row->possui_indicacao): ?>
                    <tr>
                        <td class="active">Colaboradores indicados:</td>
                        <td><?= nl2br($row->colaboradores_indicados); ?></td>
                    </tr>
                    <tr>
                        <td class="active">Responsável pela indicação:</td>
                        <td><?= nl2br($row->indicador_responsavel); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($row->possui_indicacao and $row->id_depto === '5'): ?>
                    <tr>
                        <td class="active">Nome do pai:</td>
                        <td><?= $row->nome_pai; ?></td>
                    </tr>
                    <tr>
                        <td class="active">Nome da mãe:</td>
                        <td><?= $row->nome_mae; ?></td>
                    </tr>
                    <tr>
                        <td class="active">Data de nascimento:</td>
                        <td><?= $row->data_nascimento; ?></td>
                    </tr>
                    <tr>
                        <td class="active">RG:</td>
                        <td><?= $row->rg; ?></td>
                    </tr>
                    <tr>
                        <td class="active">Data de emissão RG:</td>
                        <td><?= $row->rg_data_emissao; ?></td>
                    </tr>
                    <tr>
                        <td class="active">Órgão Emissor RG:</td>
                        <td><?= $row->rg_orgao_emissor; ?></td>
                    </tr>
                    <tr>
                        <td class="active">CPF:</td>
                        <td><?= $row->cpf; ?></td>
                    </tr>
                    <tr>
                        <td class="active">PIS:</td>
                        <td><?= $row->pis; ?></td>
                    </tr>
                    <tr>
                        <td class="active">Informações de departamento:</td>
                        <td><?= nl2br($row->departamento_informacoes); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="active">Benefícios:</td>
                    <td><?= $row->beneficios; ?></td>
                </tr>
                <tr>
                    <td class="active">Remuneração mensal:</td>
                    <td><?= $row->remuneracao_mensal; ?></td>
                </tr>
                <tr>
                    <td class="active">Horário de trabalho:</td>
                    <td><?= nl2br($row->horario_trabalho); ?></td>
                </tr>
                <tr>
                    <td class="active">Previsão de início:</td>
                    <td><?= $row->previsao_inicio; ?></td>
                </tr>
                <tr>
                    <td class="active">Local de trabalho:</td>
                    <td><?= $row->local_trabalho; ?></td>
                </tr>
                <tr>
                    <td class="active">Exames necessários:</td>
                    <td><?= $row->exames_necessarios; ?></td>
                </tr>
                <tr>
                    <td class="active">Perfil geral:</td>
                    <td><?= nl2br($row->perfil_geral); ?></td>
                </tr>
                <tr>
                    <td class="active">Competências técnicas necessárias:</td>
                    <td><?= nl2br($row->competencias_tecnicas); ?></td>
                </tr>
                <tr>
                    <td class="active">Competências comportamentais necessárias:</td>
                    <td><?= nl2br($row->competencias_comportamentais); ?></td>
                </tr>
                <tr>
                    <td class="active">Atividaes e responsabilidades associadas ao cargo-função:</td>
                    <td><?= nl2br($row->atividades_associadas); ?></td>
                </tr>
                <tr>
                    <td class="active">Observações:</td>
                    <td><?= nl2br($row->observacoes); ?></td>
                </tr>
                </tbody>
            </table>

            <table id="candidatos_aprovados" class="table table-condensed">
                <thead>
                <tr>
                    <th>Candidatos aprovados</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($row->candidatos_aprovados): ?>
                    <?php foreach ($row->candidatos_aprovados as $candidato_aprovado): ?>
                        <tr>
                            <td><?= $candidato_aprovado; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center">Nenhum candidato aprovado</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </section>
</section>
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - REQUISIÇÃO DE PESSOAL';
    });

    $('#mostrar_aprovados').on('change', function () {
        if ($(this).is(':checked')) {
            $('#candidatos_aprovados').show();
        } else {
            $('#candidatos_aprovados').hide();
        }
        setPdf_atributes();
    });

    function setPdf_atributes() {
        $('#pdf').prop('href', '<?= site_url('requisicaoPessoal/pdf/' . $this->uri->rsegment(3) . '/q?aprovados='); ?>' + ($('#mostrar_aprovados').is(':checked') ? 1 : 0));
    }
</script>
<?php
require_once 'end_js.php';
require_once 'end_html.php';
?>
