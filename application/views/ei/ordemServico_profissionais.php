<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CORPORATE RH - LMS - Gerenciar Ordem de Serviço de Cuidadores</title>
    <link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!--HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond.js doesn't work if you view the page via file://-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?= base_url("assets/js/jquery.js"); ?>"></script>
    <style>
        @page {
            margin: 40px 20px;
        }

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

        tr.group, tr.group:hover {
            background-color: #ddd !important;
        }
    </style>
</head>
<body style="color: #000;">
<div class="container-fluid">
    <br>
    <button class="btn btn-info" onclick="gerenciar_profissionais()">Gerenciar profissionais</button>
    <button class="btn btn-info" onclick="add_profissional(null)"><i class="glyphicon glyphicon-plus"></i>
        Programação semanal
    </button>
    <button class="btn btn-default" onclick="javascript:window.close()"><i
                class="glyphicon glyphicon-remove"></i> Fechar
    </button>
    <br>
    <br>
    <h5 class="text-primary">
        <strong>Cliente/diretoria: <?= $nomeCliente ?></strong></h5>
    <h5 class="text-primary">
        <strong>Unidade de ensino: <?= $nomeEscola ?></strong></h5>
    <h5 class="text-primary">
        <strong>Contrato: <?= $nomeContrato ?></strong></h5>
    <h5 class="text-primary">
        <strong>Ordem de Serviço: <?= $ordemServico ?></strong>&emsp;<i style="float:right;"><strong>Obs: Para cadastrar
                demais dados dos funcionários basta um clique sobre o nome dos mesmos.</strong></i>
    </h5>
    <h5 class="text-primary">
        <strong>Ano/semestre: <?= $anoSemestre ?></strong></h5>
    <table id="table" class="table table-striped table-bordered table-condensed" cellspacing="0"
           width="100%">
        <thead>
        <tr>
            <th>Dia semana</th>
            <th>Profissional original</th>
            <th>Aluno(s)</th>
            <th>Função</th>
            <th>Valor horas</th>
            <th>Horas semanais</th>
            <th>Horário</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="modal fade" id="modal_profissionais" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Gerenciar profissionais</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form_profissionais" class="form-horizontal">
                        <input type="hidden" value="<?= $this->uri->rsegment(3) ?>" name="id_ordem_servico_escola"/>
                        <div class="form-body">
                            <div class="row form-group">
                                <label class="control-label col-md-2">Departamento</label>
                                <div class="col-md-4">
                                    <?php echo form_dropdown('id_departamento', array('' => 'Todos'), '', 'id="depto" class="form-control filtro"'); ?>
                                </div>
                                <label class="control-label col-md-1">Área</label>
                                <div class="col-md-4">
                                    <?php echo form_dropdown('id_area', array('' => 'Todas'), '', 'id="area" class="form-control filtro"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Setor</label>
                                <div class="col-md-4">
                                    <?php echo form_dropdown('id_setor', array('' => 'Todos'), '', 'id="setor" class="form-control filtro"'); ?>
                                </div>
                                <label class="control-label col-md-1">Cargo</label>
                                <div class="col-md-4">
                                    <?php echo form_dropdown('id_cargo', array('' => 'Todos'), '', 'id="cargo" class="form-control filtro"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Função</label>
                                <div class="col-md-4">
                                    <?php echo form_dropdown('id_funcao', array('' => 'Todas'), '', 'id="funcao" class="form-control filtro"'); ?>
                                </div>
                                <label class="control-label col-md-1">Município</label>
                                <div class="col-md-4"><?php echo form_dropdown('municipio', array('' => 'Todos'), '', 'id="municipio" class="form-control filtro"'); ?></div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Supervisor(a)</label>
                                <div class="col-md-6">
                                    <?php echo form_dropdown('id_supervisor', $supervisor, '', 'id="id_supervisores" class="form-control"'); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <?php echo form_multiselect('id_usuario[]', array(), array(), 'id="id_usuarios" class="demo1" size="8"'); ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSaveProfissionais" onclick="saveProfissionais()"
                            class="btn btn-success">Salvar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_form" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Alocar profissional</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form" class="form-horizontal">
                        <input type="hidden" value="" name="id"/>
                        <div class="form-body">
                            <div class="row form-group">
                                <label class="control-label col-md-3">Profissional alocado<span
                                            class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('id_os_profissional', array('' => 'selecione...'), '', 'class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-4">Tipo de profissional requerido<span
                                            class="text-danger"> *</span></label>
                                <div class="col-md-7">
                                    <?php echo form_dropdown('id_funcao', $funcoes, '', 'class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <?php echo form_multiselect('alunos[]', array(), array(), 'id="alunos" class="demo2" size="8"'); ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group horario">
                                <label class="control-label col-md-2">Dia da semana</label>
                                <div class="col-md-3">
                                    <select name="dia_semana[]" class="form-control">
                                        <option value="">selecione...</option>
                                        <option value="0">Domingo</option>
                                        <option value="1">Segunda-feira</option>
                                        <option value="2">Terça-feira</option>
                                        <option value="3">Quarta-feira</option>
                                        <option value="4">Quinta-feira</option>
                                        <option value="5">Sexta-feira</option>
                                        <option value="6">Sábado</option>
                                    </select>
                                </div>
                                <label class="control-label col-md-1">Horário</label>
                                <div class="col-md-2">
                                    <input name="horario_inicio[]" class="form-control text-center hora"
                                           placeholder="hh:mm">
                                </div>
                                <label class="control-label col-md-1"
                                       style="width: auto; padding-left: 0px; padding-right: 0px;">até</label>
                                <div class="col-md-2">
                                    <input name="horario_termino[]" class="form-control text-center hora"
                                           placeholder="hh:mm">
                                </div>
                                <div class="col-sm-1 remover_horario" style="display: none;">
                                    <button type="button" style="border-radius: 14px;" class="btn btn-warning btn-sm"
                                            onclick="remove_horario(this);">Remover
                                    </button>
                                </div>
                            </div>
                            <div class="row" id="adicionar_horario">
                                <br>
                                <div class="col-sm-2 col-sm-offset-2">
                                    <button type="button" style="border-radius: 14px;" class="btn btn-info btn-sm"
                                            onclick="add_horario();">
                                        <i class="glyphicon glyphicon-plus"></i> Adicionar horário
                                    </button>
                                </div>
                                <div class="col-sm-8">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="1" id="manter_horarios">
                                            Manter horários de entrada e saída do último registro
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-success">Salvar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_dados" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 1040px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Alocar dados dos profissionais</h3>
                </div>
                <div class="modal-body form">
                    <form action="#" id="form_dados" class="form-horizontal">
                        <input type="hidden" value="" name="id"/>
                        <input type="hidden" value="" name="id_os_profissional"/>
                        <input type="hidden" value="<?= $this->uri->rsegment(3); ?>" name="id_ordem_servico_escola"/>
                        <input type="hidden" value="" name="id_usuario"/>
                        <div class="form-body">
                            <div class="row form-group">
                                <label class="control-label col-md-3">Profissional:<br>Dia semana:<br>Período:</label>
                                <div class="col-md-5">
                                    <p class="form-control-static">
                                        <span id="profissional"></span><br>
                                        <span id="semana"></span><br>
                                        <span id="periodo"></span>
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button type="button" id="btnSaveDados" onclick="save_dados()"
                                            class="btn btn-success">Salvar
                                    </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Supervisor(a)</label>
                                <div class="col-md-6">
                                    <?php echo form_dropdown('id_supervisor', $supervisor, '', 'id="supervisor" class="form-control"'); ?>
                                </div>
                            </div>
                            <hr style="margin: 0px;">
                            <div class="row">
                                <div class="col-md-5">
                                    <h4 class="text-primary"><strong>Valores para faturamento</strong></h4>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Valor hora</label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_hora" value=""
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Horas diárias</label>
                                        <div class="col-md-3">
                                            <input type="text" name="horas_diarias" value=""
                                                   class="form-control text-right"/>
                                        </div>
                                        <label class="control-label col-md-3">Qtde. dias</label>
                                        <div class="col-md-3">
                                            <input type="text" name="qtde_dias" value=""
                                                   class="form-control text-right"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Horas semanais</label>
                                        <div class="col-md-3">
                                            <input type="text" name="horas_semanais" value=""
                                                   class="form-control text-right"/>
                                        </div>
                                        <label class="control-label col-md-3 text-nowrap">Qtde. semanas</label>
                                        <div class="col-md-3">
                                            <input type="text" name="qtde_semanas" value=""
                                                   class="form-control text-right"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-3">Horas mensais</label>
                                        <div class="col-md-3">
                                            <input type="text" name="horas_mensais" value="" readonly
                                                   class="form-control text-right"/>
                                        </div>
                                        <label class="control-label col-md-3">Horas por semestre</label>
                                        <div class="col-md-3">
                                            <input type="text" name="horas_semestre" value=""
                                                   class="form-control text-right"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-7">Valor de faturamento mensal</label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_hora_mensal" value=""
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7" style="border-left: 1px solid #ddd;">
                                    <h4 class="text-primary"><strong>Valores de custo com o profissional</strong></h4>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Valor hora</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_hora_operacional" value=""
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                        <label class="control-label col-md-3">Horas mensais</label>
                                        <div class="col-md-2">
                                            <input type="text" name="horas_mensais_custo" value=""
                                                   class="form-control text-center time"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Data início contrato</label>
                                        <div class="col-md-3">
                                            <input type="text" name="data_inicio_contrato" value=""
                                                   class="form-control text-center date"/>
                                        </div>
                                        <label class="control-label col-md-3">Data término contrato</label>
                                        <div class="col-md-3">
                                            <input type="text" name="data_termino_contrato" value=""
                                                   class="form-control text-center date"/>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="control-label col-md-2">Pagamento início</label>
                                        <div class="col-md-3">
                                            <input type="text" name="pagamento_inicio" value=""
                                                   class="form-control valor"/>
                                        </div>
                                        <label class="control-label col-md-3">Pagamento reajuste</label>
                                        <div class="col-md-3">
                                            <input type="text" name="pagamento_reajuste" value=""
                                                   class="form-control valor"/>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row form-group" style="margin-bottom: 5px;">
                                        <label class="control-label col-md-2"><?= $nomeMes1; ?></label>
                                        <label class="control-label col-md-2 text-nowrap" style="font-weight: normal;">Horas
                                            desc.</label>
                                        <div class="col-md-2">
                                            <input type="text" name="desconto_mensal_1" value=""
                                                   class="form-control text-right desconto"/>
                                        </div>
                                        <label class="control-label col-md-1" style="font-weight: normal;">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_mensal_1" value="" readonly
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0px; margin-bottom: 5px;">
                                    <div class="row form-group" style="margin-bottom: 5px;">
                                        <label class="control-label col-md-2"><?= $nomeMes2; ?></label>
                                        <label class="control-label col-md-2 text-nowrap" style="font-weight: normal;">Horas
                                            desc.</label>
                                        <div class="col-md-2">
                                            <input type="text" name="desconto_mensal_2" value=""
                                                   class="form-control text-right desconto"/>
                                        </div>
                                        <label class="control-label col-md-1" style="font-weight: normal;">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_mensal_2" value="" readonly
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0px; margin-bottom: 5px;">
                                    <div class="row form-group" style="margin-bottom: 5px;">
                                        <label class="control-label col-md-2"><?= $nomeMes3; ?></label>
                                        <label class="control-label col-md-2 text-nowrap" style="font-weight: normal;">Horas
                                            desc.</label>
                                        <div class="col-md-2">
                                            <input type="text" name="desconto_mensal_3" value=""
                                                   class="form-control text-right desconto"/>
                                        </div>
                                        <label class="control-label col-md-1" style="font-weight: normal;">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_mensal_3" value="" readonly
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0px; margin-bottom: 5px;">
                                    <div class="row form-group" style="margin-bottom: 5px;">
                                        <label class="control-label col-md-2"><?= $nomeMes4; ?></label>
                                        <label class="control-label col-md-2 text-nowrap" style="font-weight: normal;">Horas
                                            desc.</label>
                                        <div class="col-md-2">
                                            <input type="text" name="desconto_mensal_4" value=""
                                                   class="form-control text-right desconto"/>
                                        </div>
                                        <label class="control-label col-md-1" style="font-weight: normal;">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_mensal_4" value="" readonly
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0px; margin-bottom: 5px;">
                                    <div class="row form-group" style="margin-bottom: 5px;">
                                        <label class="control-label col-md-2"><?= $nomeMes5; ?></label>
                                        <label class="control-label col-md-2 text-nowrap" style="font-weight: normal;">Horas
                                            desc.</label>
                                        <div class="col-md-2">
                                            <input type="text" name="desconto_mensal_5" value=""
                                                   class="form-control text-right desconto"/>
                                        </div>
                                        <label class="control-label col-md-1" style="font-weight: normal;">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_mensal_5" value="" readonly
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0px; margin-bottom: 5px;">
                                    <div class="row form-group">
                                        <label class="control-label col-md-2"><?= $nomeMes6; ?></label>
                                        <label class="control-label col-md-2 text-nowrap" style="font-weight: normal;">Horas
                                            desc.</label>
                                        <div class="col-md-2">
                                            <input type="text" name="desconto_mensal_6" value=""
                                                   class="form-control text-right desconto"/>
                                        </div>
                                        <label class="control-label col-md-1" style="font-weight: normal;">Valor</label>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" name="valor_mensal_6" value="" readonly
                                                       class="form-control text-right valor"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" tabindex="-1" id="modal_substituto1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Substituir profissional 1</h4>
                </div>
                <div class="modal-body">
                    <form action="#" id="form_substituto1" class="form-horizontal">
                        <input type="hidden" value="" name="id"/>
                        <div class="form-body">
                            <div class="row form-group">
                                <label class="control-label col-md-3">Município</label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('', array('' => 'selecione...'), '', 'id="municipio_sub1" class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Profissional<span
                                            class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('id_usuario_sub1', array('' => 'selecione...'), '', 'class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Data início<span
                                            class="text-danger"> *</span></label>
                                <div class="col-md-3">
                                    <input name="data_substituicao1" type="text" class="form-control text-center date"
                                           placeholder="dd/mm/aaaa">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSaveSubstituto1" onclick="save_substituto1()" class="btn btn-success">
                        Salvar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" tabindex="-1" id="modal_substituto2" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Substituir profissional 2</h4>
                </div>
                <div class="modal-body">
                    <form action="#" id="form_substituto2" class="form-horizontal">
                        <input type="hidden" value="" name="id"/>
                        <div class="form-body">
                            <div class="row form-group">
                                <label class="control-label col-md-3">Município</label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('', array('' => 'selecione...'), '', 'id="municipio_sub2" class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Profissional<span
                                            class="text-danger"> *</span></label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('id_usuario_sub2', array('' => 'selecione...'), '', 'class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Data início<span
                                            class="text-danger"> *</span></label>
                                <div class="col-md-3">
                                    <input name="data_substituicao2" type="text" class="form-control text-center date"
                                           placeholder="dd/mm/aaaa">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSaveSubstituto2" onclick="save_substituto2()" class="btn btn-success">
                        Salvar
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</div>

<div id="script_js" style="display: none;"></div>
<script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-duallistbox/bootstrap-duallistbox.css') ?>" rel="stylesheet">

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/bootstrap-duallistbox/jquery.bootstrap-duallistbox.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>
    var save_method;
    var table, demo1, demo2;

    $(document).ready(function () {

        $.ajaxSetup({
            'type': 'POST',
            'dataType': 'json',
            'error': function (jqXHR, textStatus, errorThrown) {
                alert(textStatus + ' ' + jqXHR.status + ': ' + (jqXHR.status === 0 ? 'Disconnected' : errorThrown));
                if (jqXHR.status === 401) {
                    window.close();
                }
            }
        });

        $('.date').mask('00/00/0000');
        $('.hora').mask('00:00');
        $('.time').mask('#00:00', {'reverse': true});
        $('.desconto').mask('###0,00', {'reverse': true});
        $('.valor').mask('##.###.##0,00', {'reverse': true});
        $('[name="qtde_dias"]').mask('#0,00', {'reverse': true});
        $('[name="horas_diarias"], [name="horas_semanais"], [name="horas_mensais"]').mask('##0,00', {'reverse': true});
        $('[name="horas_semestre"]').mask('###0,00', {'reverse': true});

        table = $('#table').DataTable({
            'info': false,
            'processing': true,
            'serverSide': true,
            'lengthChange': false,
            'searching': false,
            'paging': false,
            'order': [[1, 'asc'], [0, 'desc']],
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxList') ?>',
                'type': 'POST',
                'data': function (d) {
                    d.id_escola = '<?= $this->uri->rsegment(3) ?>';
                    return d;
                }
            },
            'columnDefs': [
                {
                    'mRender': function (data) {
                        if (data === null) {
                            data = '<span class="text-muted">Sem cadastro</span>';
                        }
                        return data;
                    },
                    'className': 'text-nowrap',
                    'width': '10%',
                    'targets': [0]
                },
                {
                    'createdCell': function (td, cellData, rowData, row, col) {
                        $(td).css('cursor', 'pointer').on('click', function () {
                            edit_dados(rowData[8], rowData[9]);
                        });
                    },
                    'width': '30%',
                    'targets': [1, 2]
                },
                {
                    'width': '20%',
                    'targets': [3]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [4, 5, 6]
                },
                {
                    'className': 'text-center text-nowrap',
                    'targets': [-1],
                    'orderable': false
                }
            ],
            'rowsGroup': [0, 2, 3]
        });

        demo1 = $('.demo1').bootstrapDualListbox({
            'nonSelectedListLabel': 'Profissionais disponíveis',
            'selectedListLabel': 'Profissionais alocados',
            'preserveSelectionOnMove': 'moved',
            'moveOnSelect': false,
            'filterPlaceHolder': 'Filtrar',
            'helperSelectNamePostfix': false,
            'selectorMinimalHeight': 132,
            'infoText': false
        });

        demo2 = $('.demo2').bootstrapDualListbox({
            'nonSelectedListLabel': 'Alunos alocados à unidade de ensino',
            'selectedListLabel': 'Alunos vinculados ao profissional',
            'preserveSelectionOnMove': 'moved',
            'moveOnSelect': false,
            'filterPlaceHolder': 'Filtrar',
            'helperSelectNamePostfix': false,
            'selectorMinimalHeight': 100,
            'infoText': false
        });

    });

    $('.filtro').on('change', function () {
        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/atualizarFiltros') ?>',
            'data': {
                'busca': $('.filtro').serialize(),
                'id_usuarios': $('#id_usuarios').val()
            },
            'success': function (json) {
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                $('#cargo').html($(json.cargo).html());
                $('#funcao').html($(json.funcao).html());
                $('#municipio').html($(json.municipio).html());
                $('#id_usuarios').html($(json.id_usuarios).html());
                demo1.bootstrapDualListbox('refresh', true);
            }
        });
    });


    $('#form_dados [name="valor_hora"], #form_dados [name="horas_semanais"], #form_dados [name="qtde_semanas"]').on('change', function () {
        calcularValorMensal();
    });

    $('.desconto, #form_dados [name="valor_hora_operacional"]').on('change', function () {
        calcularDescontoMensal();
    });

    function calcularValorMensal() {
        var horas_semanais = parseFloat($('#form_dados [name="horas_semanais"]').val().replace(',', '.'));
        var qtde_semanas = parseInt($('#form_dados [name="qtde_semanas"]').val());
        var horas_mensais = (horas_semanais * qtde_semanas).toFixed(2).toString().replace('.', ',');
        var valor_hora = parseFloat($('#form_dados [name="valor_hora"]').val().replace(',', '.'));

        if (horas_mensais !== 'NaN' && valor_hora !== 'NaN') {
            var valor_hora_mensal = ((horas_semanais * qtde_semanas) * valor_hora).toFixed(2).toString().replace('.', ',');
            $('#form_dados [name="horas_mensais"]').val(horas_mensais);
            $('#form_dados [name="valor_hora_mensal"]').val(valor_hora_mensal);
        } else {
            $('#form_dados [name="horas_mensais"], #form_dados [name="valor_hora_mensal"]').val('');
        }

        calcularDescontoMensal();
    }

    function calcularDescontoMensal() {
        var desconto_1 = parseFloat($('#form_dados [name="desconto_mensal_1"]').val().replace(',', '.'));
        var desconto_2 = parseFloat($('#form_dados [name="desconto_mensal_2"]').val().replace(',', '.'));
        var desconto_3 = parseFloat($('#form_dados [name="desconto_mensal_3"]').val().replace(',', '.'));
        var desconto_4 = parseFloat($('#form_dados [name="desconto_mensal_4"]').val().replace(',', '.'));
        var desconto_5 = parseFloat($('#form_dados [name="desconto_mensal_5"]').val().replace(',', '.'));
        var desconto_6 = parseFloat($('#form_dados [name="desconto_mensal_6"]').val().replace(',', '.'));

        var horas_mensais = parseFloat($('#form_dados [name="horas_mensais"]').val().replace(',', '.'));
        var valor_hora_operacional = parseFloat($('#form_dados [name="valor_hora_operacional"]').val().replace(',', '.'));

        var valor_mensal_1 = ((horas_mensais - desconto_1) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_2 = ((horas_mensais - desconto_2) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_3 = ((horas_mensais - desconto_3) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_4 = ((horas_mensais - desconto_4) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_5 = ((horas_mensais - desconto_5) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');
        var valor_mensal_6 = ((horas_mensais - desconto_6) * valor_hora_operacional).toFixed(2).toString().replace('.', ',');

        $('#form_dados [name="valor_mensal_1"]').val(valor_mensal_1 !== 'NaN' ? valor_mensal_1 : '');
        $('#form_dados [name="valor_mensal_2"]').val(valor_mensal_2 !== 'NaN' ? valor_mensal_2 : '');
        $('#form_dados [name="valor_mensal_3"]').val(valor_mensal_3 !== 'NaN' ? valor_mensal_3 : '');
        $('#form_dados [name="valor_mensal_4"]').val(valor_mensal_4 !== 'NaN' ? valor_mensal_4 : '');
        $('#form_dados [name="valor_mensal_5"]').val(valor_mensal_5 !== 'NaN' ? valor_mensal_5 : '');
        $('#form_dados [name="valor_mensal_6"]').val(valor_mensal_6 !== 'NaN' ? valor_mensal_6 : '');
    }

    $('#municipio_sub1').on('change', function () {
        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/atualizarSubstituto') ?>',
            'data': {
                'municipio': this.value,
                'id_usuario': $('#form_substituto1 [name="id_usuario"]').val()
            },
            'success': function (json) {
                $('#form_substituto1 [name="id_usuario_sub1"]').html($(json.usuario).html());
            }
        });
    });

    $('#municipio_sub2').on('change', function () {
        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/atualizarSubstituto') ?>',
            'data': {
                'municipio': this.value,
                'id_usuario': $('#form_substituto2 [name="id_usuario"]').val()
            },
            'success': function (json) {
                $('#form_substituto2 [name="id_usuario_sub2"]').html($(json.usuario).html());
            }
        });
    });


    function gerenciar_profissionais() {
        $('#form_profissionais')[0].reset();
        $('#form_profissionais [name="id"]').val('');
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxEdit') ?>',
            'data': {
                'id_escola': '<?= $this->uri->rsegment(3); ?>'
            },
            'success': function (json) {
                $('#form_profissionais [name="id_ordem_servico_escola"]').val(json.id_ordem_servico_escola);
                $('#depto').html($(json.depto).html());
                $('#area').html($(json.area).html());
                $('#setor').html($(json.setor).html());
                $('#cargo').html($(json.cargo).html());
                $('#funcao').html($(json.funcao).html());
                $('#municipio').html($(json.municipio).html());
                $('#id_usuarios').html($(json.id_usuarios).html());
                $('#id_supervisores').val(json.supervisores);
                console.log(json.id_usuarios);
                demo1.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Gerenciar profissionais');
                $('#modal_profissionais').modal('show');
                $('.combo_nivel1').hide();
            }
        });
    }


    function add_profissional(id_os_profissional) {
        save_method = 'add';
        console.log(id_os_profissional);
        $('#form')[0].reset();
        $('#form [name="id"]').val('');
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditHorario') ?>',
            'data': {
                'id_profissional': id_os_profissional,
                'id_escola': '<?= $this->uri->rsegment(3); ?>'
            },
            'success': function (json) {
                $('#form [name="id_os_profissional"]').html($(json.id_os_profissional).html());
                $('#alunos').html($(json.alunos).html());
                demo2.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Cadastrar programação semanal');
                $('#adicionar_horario').show();
                $('.horario:gt(0)').remove();
                $('#modal_form').modal('show');
                $('.combo_nivel1').hide();
            }
        });
    }

    function add_horario() {
        var horario = $('.horario:last').html();
        var manterHorarios = $('#manter_horarios').is(':checked');
        var ultimoHorarioInicio = $('.horario:last [name="horario_inicio[]"]').val();
        var ultimoHorariotermino = $('.horario:last [name="horario_termino[]"]').val();

        $('<div class="row form-group horario">' + horario + '</div>').insertAfter('.horario:last');
        $('.remover_horario:last').show();
        $('.hora').mask('00:00');

        if (manterHorarios) {
            $('.horario:last [name="horario_inicio[]"]').val(ultimoHorarioInicio);
            $('.horario:last [name="horario_termino[]"]').val(ultimoHorariotermino);
        }
    }

    function remove_horario(event) {
        $(event).parents('.horario').remove();
    }

    function add_dados() {
        save_method = 'add';
        $('#form_dados')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $('.modal-title').text('Editar dados do profissional');
        $('#modal_dados').modal('show');
        $('.combo_nivel1').hide();
    }

    function edit_profissional(id) {
        save_method = 'update';
        $('#form')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditHorario') ?>',
            'data': {
                'id': id,
                'id_escola': '<?= $this->uri->rsegment(3); ?>'
            },
            'success': function (json) {
                $('#form [name="id"]').val(json.id);
                $('#form [name="dia_semana[]"]').val(json.dia_semana);
                $('#form [name="horario_inicio[]"]').val(json.horario_inicio);
                $('#form [name="horario_termino[]"]').val(json.horario_termino);
                $('#form [name="id_os_profissional"]').html($(json.id_os_profissional).html());
                $('#form [name="id_funcao"]').html($(json.id_funcao).html());
                $('#alunos').html($(json.alunos).html());
                demo2.bootstrapDualListbox('refresh', true);

                $('.modal-title').text('Editar programação semanal');
                $('#adicionar_horario').hide();
                $('.horario:gt(0)').remove();
                $('#modal_form').modal('show');
            }
        });
    }

    function edit_dados(id_os_profissional, id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditDados') ?>',
            'data': {
                'id_os_profissional': id_os_profissional,
                'id': id,
            },
            'success': function (json) {
                $('#profissional').text(json.input.nome_usuario);
                $('#semana').text(json.input.nome_semana);
                $('#periodo').text(json.input.nome_periodo);
                $('#supervisor').html(json.input.supervisores);

                $.each(json.data, function (key, value) {
                    $('#form_dados [name="' + key + '"]').val(value);
                });

                $('.modal-title').text('Editar dados do profissional');
                $('#modal_dados').modal('show');
            }
        });
    }

    function edit_substituto1(id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditSubstituto1') ?>',
            'data': {'id': id},
            'success': function (json) {
                $('#form_substituto1 [name="id"]').val(json.id);
                $('#municipio_sub1').html($(json.municipio).html());
                $('#form_substituto1 [name="id_usuario_sub1"]').html($(json.id_usuario_sub1).html());
                $('#form_substituto1 [name="data_substituicao1"]').val(json.data_substituicao1);

                $('#modal_substituto1').modal('show');
            }
        });
    }

    function edit_substituto2(id) {
        save_method = 'update';
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('.combo_nivel1').hide();

        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxEditSubstituto2') ?>',
            'data': {'id': id},
            'success': function (json) {
                $('#form_substituto2 [name="id"]').val(json.id);
                $('#municipio_sub2').html($(json.municipio).html());
                $('#form_substituto2 [name="id_usuario_sub2"]').html($(json.id_usuario_sub2).html());
                $('#form_substituto2 [name="data_substituicao2"]').val(json.data_substituicao2);

                $('#modal_substituto2').modal('show');
            }
        });
    }


    function saveProfissionais() {
        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxSave') ?>',
            'data': $('#form_profissionais').serialize(),
            'beforeSend': function () {
                $('#btnSaveProfissionais').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_profissionais').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'complete': function () {
                $('#btnSaveProfissionais').text('Salvar').attr('disabled', false);
            }
        });
    }


    function save() {
        var url;
        if (save_method === 'add') {
            url = '<?php echo site_url('ei/ordemServico_profissionais/ajaxAddHorarios') ?>';
        } else {
            url = '<?php echo site_url('ei/ordemServico_profissionais/ajaxUpdateHorario') ?>';
        }

        $.ajax({
            'url': url,
            'data': $('#form').serialize(),
            'beforeSend': function () {
                $('#btnSave, #btnSave2').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'complete': function () {
                $('#btnSave, #btnSave2').text('Salvar').attr('disabled', false);
            }
        });
    }

    function save_dados() {
        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxSaveDados') ?>',
            'data': $('#form_dados').serialize(),
            'beforeSend': function () {
                $('#btnSaveDados').text('Salvando').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_dados').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'complete': function () {
                $('#btnSaveDados').text('Salvar').attr('disabled', false);
            }
        });
    }

    function save_substituto1() {
        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxSaveSubstituto1') ?>',
            'data': $('#form_substituto1').serialize(),
            'beforeSend': function () {
                $('#btnSaveSubstituto1').text('Salvando').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_substituto1').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'complete': function () {
                $('#btnSaveSubstituto1').text('Salvar').attr('disabled', false);
            }
        });
    }

    function save_substituto2() {
        $.ajax({
            'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxSaveSubstituto2') ?>',
            'data': $('#form_substituto2').serialize(),
            'beforeSend': function () {
                $('#btnSaveSubstituto2').text('Salvando').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_substituto2').modal('hide');
                    reload_table();
                } else if (json.erro) {
                    alert(json.erro);
                }
            },
            'complete': function () {
                $('#btnSaveSubstituto2').text('Salvar').attr('disabled', false);
            }
        });
    }

    function delete_profissional(id) {
        if (confirm('Deseja remover a programação semanal?')) {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxDeleteHorario') ?>',
                'data': {'id': id},
                'success': function (json) {
                    reload_table();
                }
            });

        }
    }

    function limpar_profissional(id) {
        if (confirm('Deseja remover este profissional?')) {
            $.ajax({
                'url': '<?php echo site_url('ei/ordemServico_profissionais/ajaxDelete') ?>',
                'data': {'id': id},
                'success': function (json) {
                    reload_table();
                }
            });

        }
    }

    function reload_table() {
        table.ajax.reload(null, false);
    }
</script>
</body>
</html>
