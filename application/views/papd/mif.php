<?php require_once APPPATH . 'views/header.php'; ?>

    <style>
        .btn-success {
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

        .text-nowrap {
            white-space: nowrap;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                        <li><a href="<?= site_url('papd/pacientes'); ?>">Gerenciar pacientes</a></li>
                        <li class="active">Medida de Independência Funcional (MIF)<?= $nomePaciente; ?></li>
                    </ol>
                    <button class="btn btn-info" onclick="add_mif()"><i class="glyphicon glyphicon-plus"></i> Cadastrar
                        nova avaliação
                    </button>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <br/>
                    <br/>
                    <table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th nowrap>Data avaliação</th>
                            <th>Avaliador</th>
                            <th>MIF</th>
                            <th>Observações</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog" style="width: 700px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Gerenciar avaliação MIF</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="mif" value="">
                                <?php if ($idPaciente): ?>
                                    <input type="hidden" name="id_paciente" value="<?= $idPaciente; ?>">
                                <?php endif; ?>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-3">Data de avaliação</label>
                                        <div class="col-md-3">
                                            <input name="data_avaliacao" type="text"
                                                   class="form-control text-center date" placeholder="dd/mm/aaaa">
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <?php if (empty($idPaciente)): ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-2">Paciente</label>
                                            <div class="col-md-10">
                                                <?php echo form_dropdown('id_paciente', $pacientes, '', 'class="form-control"'); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Avaliador</label>
                                        <div class="col-md-10">
                                            <input name="avaliador" type="text" class="form-control"
                                                   placeholder="Digite o nome do avaliador">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Observações</label>
                                        <div class="col-md-10">
                                            <textarea name="observacoes" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <table id="atividades" class="table table-bordered table-condensed" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Itens</th>
                                        <th class="text-center" colspan="2">Atividades</th>
                                        <th class="text-center">Níveis de independência</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="active">
                                        <td class="text-center" colspan="4"><strong>Cuidados pessoais</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">A</td>
                                        <td colspan="2" width="100%">Alimentar-se</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="alimentacao" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="alimentacao" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="alimentacao" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="alimentacao" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="alimentacao" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="alimentacao" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="alimentacao" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="alimentacao" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">B</td>
                                        <td colspan="2" width="100%">Arrumar-se</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="arrumacao" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="arrumacao" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="arrumacao" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="arrumacao" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="arrumacao" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="arrumacao" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="arrumacao" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="arrumacao" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">C</td>
                                        <td colspan="2" width="100%">Banhar-se</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="banho" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banho" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banho" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banho" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banho" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banho" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banho" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banho" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">D</td>
                                        <td colspan="2" width="100%">Vestir-se (parte superior)</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_superior" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_superior" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_superior" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_superior" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_superior" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_superior" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_superior" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_superior" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">E</td>
                                        <td colspan="2" width="100%">Vestir-se (parte inferior)</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_inferior" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_inferior" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_inferior" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_inferior" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_inferior" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_inferior" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_inferior" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="vestimenta_inferior" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">F</td>
                                        <td colspan="2" width="100%">Higiene pessoal</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="higiene_pessoal" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="higiene_pessoal" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="higiene_pessoal" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="higiene_pessoal" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="higiene_pessoal" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="higiene_pessoal" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="higiene_pessoal" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="higiene_pessoal" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td class="text-center" colspan="4"><strong>Controle de esfíncteres</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">G</td>
                                        <td colspan="2" width="100%">Controle vesical</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_vesical" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_vesical" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_vesical" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_vesical" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_vesical" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_vesical" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_vesical" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_vesical" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">H</td>
                                        <td colspan="2" width="100%">Controle intestinal</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_intestinal" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_intestinal" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_intestinal" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_intestinal" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_intestinal" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_intestinal" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_intestinal" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="controle_intestinal" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td class="text-center" colspan="4"><strong>Mobilidade (transfrência)</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">I</td>
                                        <td colspan="2" width="100%">Leito/cadeira/cadeira de rodas</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="leito_cadeira" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="leito_cadeira" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="leito_cadeira" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="leito_cadeira" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="leito_cadeira" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="leito_cadeira" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="leito_cadeira" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="leito_cadeira" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">J</td>
                                        <td colspan="2" width="100%">Sanitário</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="sanitario" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sanitario" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sanitario" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sanitario" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sanitario" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sanitario" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sanitario" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="sanitario" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">K</td>
                                        <td colspan="2" width="100%">Banheiro/chuveiro</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="banheiro_chuveiro" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banheiro_chuveiro" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banheiro_chuveiro" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banheiro_chuveiro" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banheiro_chuveiro" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banheiro_chuveiro" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banheiro_chuveiro" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="banheiro_chuveiro" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td class="text-center" colspan="4"><strong>Locomoção</strong></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" class="text-center">L</td>
                                        <td rowspan="2" width="50%">Marcha/cadeira de rodas</td>
                                        <td width="50%">Marcha</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="marcha" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="marcha" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="marcha" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="marcha" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="marcha" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="marcha" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="marcha" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="marcha" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">Cadeira de rodas</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="cadeira_rodas" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="cadeira_rodas" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="cadeira_rodas" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="cadeira_rodas" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="cadeira_rodas" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="cadeira_rodas" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="cadeira_rodas" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="cadeira_rodas" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">M</td>
                                        <td colspan="2" width="100%">Escadas</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="escadas" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="escadas" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="escadas" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="escadas" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="escadas" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="escadas" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="escadas" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="escadas" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td class="text-center" colspan="4"><strong>Comunicação</strong></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" class="text-center">N</td>
                                        <td rowspan="2" width="50%">Compreensão</td>
                                        <td width="50%">Ambas</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_ambas" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_ambas" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_ambas" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_ambas" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_ambas" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_ambas" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_ambas" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_ambas" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">Visual</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_visual" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_visual" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_visual" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_visual" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_visual" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_visual" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_visual" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="compreensao_visual" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" class="text-center">O</td>
                                        <td rowspan="2" width="50%">Expressão</td>
                                        <td width="50%">Verbal</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_verbal" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_verbal" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_verbal" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_verbal" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_verbal" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_verbal" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_verbal" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_verbal" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">Não verbal</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_nao_verbal" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_nao_verbal" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_nao_verbal" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_nao_verbal" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_nao_verbal" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_nao_verbal" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_nao_verbal" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="expressao_nao_verbal" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td class="text-center" colspan="4"><strong>Conhecimento social</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">P</td>
                                        <td colspan="2" width="100%">Interação social</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="interacao_social" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="interacao_social" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="interacao_social" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="interacao_social" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="interacao_social" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="interacao_social" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="interacao_social" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="interacao_social" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">Q</td>
                                        <td colspan="2" width="100%">Resolução de problemas</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="resolucao_problemas" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="resolucao_problemas" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="resolucao_problemas" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="resolucao_problemas" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="resolucao_problemas" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="resolucao_problemas" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="resolucao_problemas" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="resolucao_problemas" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">R</td>
                                        <td colspan="2" width="100%">Memória</td>
                                        <td nowrap="">
                                            <label class="radio-inline">
                                                <input type="radio" name="memoria" value="0"> 0
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="memoria" value="1"> 1
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="memoria" value="2"> 2
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="memoria" value="3"> 3
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="memoria" value="4"> 4
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="memoria" value="5"> 5
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="memoria" value="6"> 6
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="memoria" value="7"> 7
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td colspan="3"><strong>Total</strong></td>
                                        <td><span id="mif"></span></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <h4>Legenda</h4>
                                <table class="table table-bordered table-condensed" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Níveis de independência</th>
                                        <th>Tipo de assistência</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1 - Assistência total (sujeito = 0%)</td>
                                        <td rowspan="2">Completa dependência</td>
                                    </tr>
                                    <tr>
                                        <td>2 - Assistência máxima (sujeito = 25%)</td>
                                    </tr>
                                    <tr>
                                        <td>3 - Assistência moderada (sujeito = 50%)</td>
                                        <td rowspan="3">Dependência modificada</td>
                                    </tr>
                                    <tr>
                                        <td>4 - Assistência mínima (sujeito = 75%)</td>
                                    </tr>
                                    <tr>
                                        <td>5 - Supervisão</td>
                                    </tr>
                                    <tr>
                                        <td>6 - Independência modificada (aparelho)</td>
                                        <td rowspan="2">Sem assistente</td>
                                    </tr>
                                    <tr>
                                        <td>7 - Independência completa (tempo, segurança)</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar avaliação MIF';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {

            $('.date').mask('00/00/0000');

            //datatables
            table = $('#table').DataTable({
                'dom': "<'row'<'.col-sm-3'l><'#status.col-sm-2'><'#tipo.col-sm-4'><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('papd/mif/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id_paciente = '<?= $idPaciente; ?>';
                        return d;
                    }
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [1, 3]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ]
            });

        });

        $('#atividades input[type="radio"]').on('change', function () {
            var mif = 0;
            var vazio = true;
            $.each($('#atividades input[type="radio"]:checked'), function (i, elem) {
                mif = mif + parseInt(elem.value);
                vazio = false;
            });

            if (vazio) {
                mif = '';
            }

            $('#form input[name="mif"]').val(mif);
            $('#mif').text(mif);
        });


        function add_mif() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#atividades input[type="radio"][value="0"]').prop('checked', true);
            $('#mif').text(0);

            $('#modal_form').modal('show');
            $('.modal-title').text('Cadastrar nova avaliação'); // Set title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_mif(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('papd/mif/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id
                },
                'success': function (json) {
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').prop('type') === 'radio') {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
                        } else {
                            $('#form [name="' + key + '"]').val(value);
                        }
                    });
                    $('#mif').text(json.mif);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar avaliação'); // Set title to Bootstrap modal title
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('papd/mif/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('papd/mif/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }

                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error adding / update data');
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }


        function delete_mif(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('papd/mif/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function () {
                        reload_table();
                    },
                    'error': function (jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>