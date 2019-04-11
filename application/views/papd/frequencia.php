<?php
require_once APPPATH . "views/header.php";
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
            <table class="table table-condensed avaliado">
                <thead>
                    <tr style='border-top: 5px solid #ddd;'>
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
                                <a id="pdf" class="btn btn-sm btn-danger" href="<?= site_url('papd/relatorios/pdfFrequencia/' . $this->uri->rsegment(3)); ?>" title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
                                <button class="btn btn-sm btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>
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
                        <td>Deficiência: <?= $paciente->deficiencia ?></td>
                    </tr>
                    <tr>
                        <td>HD: <?= $paciente->hd ?></td>
                        <td>Responsável: <?= $paciente->nome_responsavel_1 ?></td>
                        <td>Telefone: <?= $paciente->telefone_fixo_1 ?></td>
                    </tr>
                    <tr>
                        <td>Endereço: <?= $paciente->endereco ?></td>
                        <td>Complemento: <?= $paciente->complemento ?></td>
                        <td>Bairro: <?= $paciente->bairro ?></td>
                    </tr>
                    <tr style='border-bottom: 5px solid #ddd;'>
                        <td>Cidade: <?= $paciente->cidade ?></td>
                        <td>Estado: <?= $paciente->estado ?></td>
                        <td>CEP: <?= $paciente->cep ?></td>
                    </tr>
                    <tr style='border-top: 5px solid #ddd;'>
                        <td colspan="2"><h3>Declaração do mês: <span id="mes_ano"><?= $paciente->nome_mes_ingresso . ' de ' . $paciente->ano_ingresso ?></span></h3></td>
                        <td>
                            <div class="form-inline text-right" style="margin-top: 18px; margin-bottom: 0px;">
                                <div class="form-group">
                                    <label for="exampleInputName2" style="font-weight: normal;">Alterar mês e ano da declaração</label>&nbsp;
                                    <?php echo form_dropdown('mes', $meses, $paciente->mes_ingresso, 'class="form-control input-sm filtro" autocomplete="off"'); ?>
                                    <input name="ano" type="number" value="<?= $paciente->ano_ingresso ?>" size="4" min='1' step='1' class="form-control input-sm filtro" placeholder="aaaa" autocomplete="off" style="width: 80px;">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr style='border-bottom: 5px solid #ddd;'>
                        <td colspan="3" style="text-indent: 2em;">Declaramos que neste mês, o paciente acima identificado, foi submetido às atividades/procedimentos abaixo relacionadas, conforme assinaturas do paciente/responsável e do profissional realizador do atendimento.</td>
                    </tr>
                </tbody>
            </table>

            <br/>
            <!--<div class="table-responsive">-->
            <table class="table table-bordered table-condensed avaliacao">
                <thead>
                    <tr class="active">
                        <th colspan="3" class="text-center"><h3><strong>PROGRAMA DE APOIO À PESSOA COM DEFICIÊNCIA</strong></h3></th>
                    </tr>
                    <tr class="active">
                        <th class="text-center" width="15%">Data</th>
                        <th class="text-center" width="15%" nowrap>Horário início</th>
                        <th class="text-center" width="70%">Atividades/procedimentos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                    </tr>                    
                    <tr>
                        <td>&nbsp;</td>
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
        setPdf_atributes();
    });

    $('.filtro').on('change', function () {
        var mes = $('[name="mes"] option:selected').text().toLowerCase();
        var ano = $('[name="ano"]').val();
        if (mes.length > 0 && ano.length > 0) {
            $('#mes_ano').html(mes + ' de ' + ano);
            setPdf_atributes();
        }
    });

    function setPdf_atributes() {
        var search = '';
        var q = new Array();

        $('.filtro').each(function (i, v) {
            if (v.value.length > 0) {
                q[i] = v.name + "=" + v.value;
            }
        });

        q = q.filter(function (v) {
            return v.length > 0;
        });
        if (q.length > 0) {
            search = '/q?' + q.join('&');
        }

        $('#pdf').prop('href', "<?= site_url('papd/relatorios/pdfFrequencia/' . $this->uri->rsegment(3)); ?>" + search);
    }
</script>
<?php
require_once APPPATH . "views/end_js.php";
require_once APPPATH . "views/end_html.php";
?>
