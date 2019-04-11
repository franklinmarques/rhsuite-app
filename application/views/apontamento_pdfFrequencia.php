<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CORPORATE RH - LMS - Controle de Frequência Individual</title>
        <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') ?>" rel="stylesheet">

        <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries--> 
        <!--WARNING: Respond.js doesn't work if you view the page via file://--> 
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
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

            tr.group, tr.group:hover {
                background-color: #ddd !important;
            }
        </style>
    </head> 
    <body style="color: #000;">
        <div class="container-fluid">
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
                                <a class="btn btn-sm btn-danger" href="<?= site_url('apontamento_pacientes/pdfFrequencia/' . $this->uri->rsegment(3)); ?>" title="Exportar PDF"><i class="glyphicon glyphicon-download-alt"></i> Exportar PDF</a>
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
                        <th colspan="3"><h3>Declaração do mês: <?= $paciente->mes_ingresso . ' de ' . $paciente->ano_ingresso ?></h3></th>
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
                        <th colspan="5" class="text-center"><h3><strong>PROGRAMA DE APOIO À PESSOA COM DEFICIÊNCIA</strong></h3></th>                            
                    </tr>
                    <tr class="active">
                        <th class="text-center">Data</th>
                        <th class="text-center">Horário início</th>
                        <th class="text-center">Atividades / procedimentos</th>
                        <th class="text-center">Paciente / responsável</th>
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
            <table class="table table-condensed avaliacao">
                <tbody>
                    <tr>
                        <td style="border: 0; text-align: center;"><br>Data:______/______/____________<br>&nbsp;</td>
                        <td style="border: 0; text-align: center;"><br>_____________________________________________________________<br>Assinatura</td>
                    </tr>
                </tbody>
            </table>
            <!--</div>-->
        </div>
    </body>
</html>