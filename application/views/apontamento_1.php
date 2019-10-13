<?php
require_once "header.php";
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
    .date-width {
        padding-right: 3px !important;
        padding-left: 3px !important;
        text-align: center;
        white-space: normal;
    }
    .DTFC_RightWrapper{
        display: none;
    }
    .table-hover > tbody > tr > td {
        background-color: #fff;        
    }
    .table-hover > tbody > tr > td:hover {
        background-color: #e8e8e8;
    }
    .table-hover > tbody > tr > td.colaborador-success, 
    .table-hover > tbody > tr > td.date-width-success {
        color: #fff;
        background-color: #5cb85c;
    }
    .table-hover > tbody > tr > td.colaborador-success:hover, 
    .table-hover > tbody > tr > td.date-width-success:hover {
        background-color: #47a447;
    }
    .table-hover > tbody > tr > td.date-width-warning {
        color: #fff;
        background-color: #f0ad4e;
    }
    .table-hover > tbody > tr > td.date-width-warning:hover {
        background-color: #ed9c28;
    }
    .table-hover > tbody > tr > td.date-width-danger {
        color: #fff;
        background-color: #d9534f;
    }
    .table-hover > tbody > tr > td.date-width-danger:hover {
        background-color: #d2322d;
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
                    <li class="active">Gestão Operacional</li>
                </ol>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-success" onclick="add_mes()"><i class="glyphicon glyphicon-plus"></i> Novo mês</button>
                        <button class="btn btn-danger" onclick="excluir_mes()"><i class="glyphicon glyphicon-trash"></i> Excluir mês</button>
                        <?php if ($this->session->userdata('tipo') == 'funcionario'): ?>
                            <a class="btn btn-success" href="apontamento_eventos"><i class="glyphicon glyphicon-list-alt"></i> Gerenciar eventos</a>
                        <?php else: ?>
                            <a class="btn btn-success" href="apontamento_eventos"><i class="glyphicon glyphicon-list-alt"></i> Gerenciar eventos</a>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 right">
                        <label class="visible-xs"></label>
                        <p class="bg-info text-info" style="padding: 5px;">
                            <small>&emsp;<strong>Departamento:</strong> <span id="alerta_depto"><?= empty($depto_atual) ? 'Todos' : $depto_atual ?></span></small><br>
                            <small>&emsp;<strong>Área:</strong> <span id="alerta_area"><?= empty($area_atual) ? 'Todas' : $area_atual ?></span></small><br>
                            <small>&emsp;<strong>Setor:</strong> <span id="alerta_setor"><?= empty($setor_atual) ? 'Todos' : $setor_atual ?></span></small>
                        </p>
                    </div>
                </div>
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">
                        <span id="mes_ano"><?= ucfirst($mes) . ' ' . date('Y') ?></span>                            
                        <div style="float:right; margin-top: -0.5%;">
                            <button class="btn btn-primary btn-sm" onclick="proximo_mes(-1)">
                                <i class="glyphicon glyphicon-arrow-left"></i> Mês anterior
                            </button>
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal_filtro">
                                <i class="glyphicon glyphicon-search"></i> Pesquisa avançada
                            </button>
                            <button id="mes_seguinte" class="btn btn-primary btn-sm" onclick="proximo_mes(1)">
                                Mês seguinte <i class="glyphicon glyphicon-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="panel-body">

                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#apontamento" aria-controls="apontamento" role="tab" data-toggle="tab">Apontamento</a></li>
                            <li role="presentation"><a href="#totalizacao" aria-controls="totalizacao" role="tab" data-toggle="tab">Totalização</a></li>
                        </ul>

                        <div class="tab-content" style="border: 1px solid #ddd; border-top-width: 0;">
                            <div role="tabpanel" class="tab-pane active" id="apontamento">
                                <table id="table" class="table table-hover table-bordered" cellspacing="0" width="calc(100%)" style="border-radius: 0 !important;">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Colaborador(a)</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Bck1</th>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Bck2</th>
                                            <th colspan="31" class="date-width">Dias</th>
                                            <th colspan="2" class="warning text-center" style="padding-left: 4px; padding-right: 4px;">Faltas/atrasos</th>
                                        </tr>
                                        <tr>
                                            <?php for ($i = 1; $i <= 31; $i++): ?>
                                                <?php if (date('N', mktime(0, 0, 0, date('m'), $i, date('Y'))) < 6): ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php else: ?>
                                                    <th class="date-width"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></th>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <th class="warning text-center" style="padding-left: 4px; padding-right: 4px;">Dias</th>
                                            <th class="warning text-center" style="padding-left: 4px; padding-right: 4px;">Horas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <div role="tabpanel" class="tab-pane" id="totalizacao">
                                <table id="table_totalizacao" class="table table-hover table-bordered" cellspacing="0" width="100%" style="border-radius: 0 !important;">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="warning" style="vertical-align: middle;">Colaborador(a)</th>
                                            <th colspan="2" class="warning text-center" style="padding-left: 4px; padding-right: 4px;">Faltas/atrasos</th>
                                            <th colspan="4" class="warning text-center" style="padding-left: 4px; padding-right: 4px;">Valores (R$)</th>
                                        </tr>
                                        <tr>
                                            <th class="warning text-center">Dias</th>
                                            <th class="warning text-center">Horas</th>
                                            <th class="warning text-center">Posto</th>
                                            <th class="warning text-center">Conversor dia</th>
                                            <th class="warning text-center">Conversor hora</th>
                                            <th class="warning text-center">Total devido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- page end-->

        <!-- Bootstrap modal -->        
        <div class="modal fade" id="modal_filtro" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Pesquisa avançada</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por departamento</label>
                                    <?php echo form_dropdown('depto', $depto, $depto_atual, 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por área</label>
                                    <?php echo form_dropdown('area', $area, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por setor</label>
                                    <?php echo form_dropdown('setor', $setor, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por cargo</label>
                                    <?php echo form_dropdown('cargo', $cargo, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por função</label>
                                    <?php echo form_dropdown('funcao', $funcao, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Mês</label>
                                    <?php echo form_dropdown('mes', $meses, date('m'), 'class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Ano</label>
                                    <input name="ano" type="number" value="<?= date('Y') ?>" size="4" class="form-control input-sm" placeholder="aaaa">
                                </div>
                            </div> 
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveFiltro" onclick="filtrar()" class="btn btn-primary" data-dismiss="modal">OK</button>
                        <button type="button" id="limpar" class="btn btn-default">Limpar filtro</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Bootstrap modal -->        
        <div class="modal fade" id="modal_backup" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Editar backup de férias</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form_backup" class="form-horizontal" autocomplete="off">
                            <input type="hidden" value="" name="id"/>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador(a):</label>
                                <div class="col-md-9">
                                    <label class="sr-only" style="margin-top: 7px;"></label>
                                    <p class="form-control-static">
                                        <span id="nome_alocado"></span>
                                    </p>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Período de férias</label>
                                <div class="col-md-9 form-inline">
                                    De <input name="data_ferias" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                    até <input name="data_retorno" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador backup</label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('id_bck', $backup, '', 'class="form-control"'); ?>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveBackup" onclick="salvar_ferias()" class="btn btn-primary">Salvar</button>
                        <button type="button" id="btnLimparBackup" onclick="limpar_ferias()" class="btn btn-danger">Limpar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Bootstrap modal -->        
        <div class="modal fade" id="modal_backup1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Editar backup 1</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form_backup1" class="form-horizontal" autocomplete="off">
                            <input type="hidden" value="" name="id"/>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador(a):</label>
                                <div class="col-md-9">
                                    <label class="sr-only" style="margin-top: 7px;"></label>
                                    <p class="form-control-static">
                                        <span id="nome_alocado1"></span>
                                    </p>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Período de atuação</label>
                                <div class="col-md-9 form-inline">
                                    De <input name="data_inicio1" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                    até <input name="data_termino1" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador backup</label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('id_bck1', $backup, '', 'class="form-control"'); ?>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveBackup1" onclick="salvar_backup1()" class="btn btn-primary">Salvar</button>
                        <button type="button" id="btnLimparBackup1" onclick="limpar_backup1()" class="btn btn-danger">Limpar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div class="modal fade" id="modal_backup2" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Editar backup 2</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form_backup2" class="form-horizontal" autocomplete="off">
                            <input type="hidden" value="" name="id"/>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador(a):</label>
                                <div class="col-md-9">
                                    <label class="sr-only" style="margin-top: 7px;"></label>
                                    <p class="form-control-static">
                                        <span id="nome_alocado1"></span>
                                    </p>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Período de atuação</label>
                                <div class="col-md-9 form-inline">
                                    De <input name="data_inicio2" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                    até <input name="data_termino2" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador backup</label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('id_bck2', $backup, '', 'class="form-control"'); ?>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveBackup2" onclick="salvar_backup2()" class="btn btn-primary">Salvar</button>
                        <button type="button" id="btnLimparBackup2" onclick="limpar_backup2()" class="btn btn-danger">Limpar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <!-- Bootstrap modal -->        
        <div class="modal fade" id="modal_form" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Editar apontamento</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="" name="id_alocado"/>
                            <input type="hidden" value="" name="data"/>
                            <div class="row form-group">
                                <label class="control-label col-md-2">Colaborador(a):<br>Data:</label>
                                <div class="col-md-3">
                                    <label class="sr-only" style="margin-top: 7px;"></label>
                                    <p class="form-control-static">
                                        <span id="nome"></span><br>
                                        <span id="data"></span>
                                    </p>
                                </div>
                                <label class="control-label col-md-2" style="padding-right: 0;">Glosa colaborador(a)</label>
                                <div class="col-md-2">
                                    <input name="hora_glosa" class="hora form-control text-center" type="text" value="" placeholder="hh:mm">
                                </div>
                                <div class="col-md-3 text-right">
                                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>                                    
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                            <hr>
                            <div class="form-body" style="padding-top: 0;">
                                <div class="row">
                                    <h5 style="margin-top: 0;">Tipos de status</h5>
                                    <div class="col col-md-3">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="FJ" checked>
                                                Falta justificada
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="FN">
                                                Falta não-justificada
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="FR">
                                                Feriado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col col-md-3">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="AJ">
                                                Atraso justificado
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="AN">
                                                Atraso não-justificado
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col col-md-4">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="SA">
                                                Saída antecipada justificada
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="status" value="SN">
                                                Saída antecipada não-justificada
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col col-md-1 text-right">
                                        <button type="button" id="btnApagar" onclick="apagar()" class="btn btn-danger">Limpar status</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Glosa cliente</label>
                                    <div class="col-md-2">
                                        <input name="hora_atraso" class="hora form-control text-center" type="text" value="" placeholder="hh:mm">
                                    </div>
                                    <label class="control-label col-md-2">Horário entrada</label>
                                    <div class="col-md-2">
                                        <input name="hora_entrada" class="hora form-control text-center" value="" placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
                                    </div>
                                    <label class="control-label col-md-2">Horário intervalo</label>
                                    <div class="col-md-2">
                                        <input name="hora_intervalo" class="hora form-control text-center" value="" placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2">Qtde de dias</label>
                                    <div class="col-md-2">
                                        <input name="qtde_dias" class="form-control text-right" type="number" min="0" max="31" value="">
                                    </div>
                                    <label class="control-label col-md-2">Horário retorno</label>
                                    <div class="col-md-2">
                                        <input name="hora_retorno" class="hora form-control text-center" value="" placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
                                    </div>
                                    <label class="control-label col-md-2">Horário saída</label>
                                    <div class="col-md-2">
                                        <input name="hora_saida" class="hora form-control text-center" value="" placeholder="hh:mm" maxlength="5" autocomplete="off" type="text">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Backup 1</label>
                                            <div class="col-md-9">
                                                <?php echo form_dropdown('id_alocado_bck', $backup, '', 'class="form-control"'); ?>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Backup 2</label>
                                            <div class="col-md-9">
                                                <?php echo form_dropdown('id_alocado_bck2', $backup, '', 'class="form-control"'); ?>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Detalhes</label>
                                            <div class="col-md-9">
                                                <?php echo form_dropdown('detalhes', $detalhes, '', 'class="form-control"'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row form-group">
                                            <label class="control-label col-md-3">Observações gerais</label>
                                            <div class="col-md-9">
                                                <textarea name="observacoes" class="form-control" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>              
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </section>
</section>
<!--main content end-->

<?php
require_once "end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">


<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Status - Gestão Operacional';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/extensions/dataTables.fixedColumns.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>

    var table, table_totalizacao;
    var busca;

    $('[name="data_ferias"], [name="data_retorno"]').mask('00/00/0000');
    $('[name="data_inicio1"], [name="data_termino1"]').mask('00/00/0000');
    $('[name="data_inicio2"], [name="data_termino2"]').mask('00/00/0000');
    $('.hora').mask('00:00');

    $(function () {
        $('[data-tooltip="tooltip"]').tooltip();
    });

    $(document).ready(function () {
        busca = $('#busca').serialize();

        //datatables        
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "iDisplayLength": 25,
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            "order": [[0, 'asc']],
            scrollY: '100%',
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 3
            },
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('apontamento/ajax_list') ?>",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.busca = busca;
                    return d;
                },
                "dataSrc": function (json) {
                    $('[name="mes"]').val(json.calendar.mes);
                    $('[name="ano"]').val(json.calendar.ano);
                    $('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));

                    var dt1 = new Date();
                    var dt2 = new Date();
                    dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                    if (dt1.getTime() < dt2.getTime()) {
                        $('#mes_seguinte').addClass('disabled').parent().css('cursor', 'not-allowed');
                    } else {
                        $('#mes_seguinte').removeClass('disabled').parent().css('cursor', '');
                    }

                    var semana = 1;
                    for (i = 1; i <= 31; i++) {
                        if (i > 28) {
                            if (i > json.calendar.qtde_dias) {
                                table.column(i + 2).visible(false, false);
                                continue;
                            } else {
                                table.column(i + 2).visible(true, false);
                            }
                        }
                        var coluna = $(table.columns(i + 2).header());
                        coluna.removeClass('text-danger').css('background-color', '');
                        coluna.attr({
                            'data-dia': json.calendar.ano + '-' + json.calendar.mes + '-' + coluna.text(),
                            'data-mes_ano': json.calendar.semana[semana] + ', ' + coluna.text() + '/' + json.calendar.mes + '/' + json.calendar.ano,
                            'title': json.calendar.semana[semana] + ', ' + coluna.text() + ' de ' + json.calendar.mes_ano.replace(' ', ' de ')
                        });
                        if (json.calendar.semana[semana] === 'Sábado' || json.calendar.semana[semana] === 'Domingo') {
                            coluna.addClass('text-danger').css('background-color', '#dbdbdb');
                        }
                        if ((dt1.getTime() === dt2.getTime()) && dt1.getDate() === i) {
                            coluna.css('background-color', '#0f0');
                        }
                        if (i % 7 === 0) {
                            semana = 1;
                        } else {
                            semana++;
                        }
                    }

                    return json.data;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('cursor', 'pointer');
                        if (rowData[col][4]) {
                            $(td).addClass('colaborador-success');
                            $(td).removeClass('warning');
                            $(td).attr({
                                'title': (rowData[col][2] !== null ? 'Início das férias: ' + rowData[col][2] + '\n' : '') +
                                        (rowData[col][3] !== null ? 'Retorno das férias: ' + rowData[col][3] + '\n' : '') +
                                        (rowData[col][4] !== '' && rowData[col][4] !== undefined ? 'Colaborador backup: ' + $('[name="id_bck"] option[value="' + rowData[col][4] + '"]').text() : '')
                            });
                        } else {
                            $(td).addClass('warning');
                            $(td).removeClass('colaborador-success');
                        }
                        $(td).attr({
                            'data-toggle': 'modal',
                            'data-target': '#modal_backup',
                            'data-id': rowData[col][0],
                            'data-nome': rowData[col][1],
                            'data-ferias': rowData[col][2],
                            'data-retorno': rowData[col][3],
                            'data-id_bck': rowData[col][4]
                        });
                        $(td).on('click', function () {
                            $('#form_backup [name="id"]').val($(this).data('id'));
                            $('#form_backup [name="data_ferias"]').val($(this).data('ferias'));
                            $('#form_backup [name="data_retorno"]').val($(this).data('retorno'));
                            $('#form_backup [name="id_bck"]').val($(this).data('id_bck'));
                            $('#nome_alocado').html(rowData[0][1]);
                            $('#data').html($(table.column(col).header()).attr('title'));
                        });
                        $(td).html(rowData[col][1]);
                    },
                    width: '100%',
                    "targets": [0]
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('cursor', 'pointer');
                        if (rowData[col][4]) {
                            $(td).addClass('colaborador-success');
                            $(td).removeClass('warning');
                            $(td).attr({
                                'title': (rowData[col][2] !== null ? 'Data de início: ' + rowData[col][2] + '\n' : '') +
                                        (rowData[col][3] !== null ? 'Data de término: ' + rowData[col][3] + '\n' : '') +
                                        (rowData[col][4] !== '' && rowData[col][4] !== undefined ? 'Colaborador backup: ' + $('[name="id_bck"] option[value="' + rowData[col][4] + '"]').text() : '')
                            });
                        } else {
                            $(td).addClass('warning');
                            $(td).removeClass('colaborador-success');
                        }
                        $(td).attr({
                            'data-toggle': 'modal',
                            'data-target': '#modal_backup1',
                            'data-id': rowData[col][0],
                            'data-nome': rowData[col][1],
                            'data-inicio1': rowData[col][2],
                            'data-termino1': rowData[col][3],
                            'data-id_bck1': rowData[col][4]
                        });
                        $(td).on('click', function () {
                            $('#form_backup1 [name="id"]').val($(this).data('id'));
                            $('#form_backup1 [name="data_inicio1"]').val($(this).data('inicio1'));
                            $('#form_backup1 [name="data_termino1"]').val($(this).data('termino1'));
                            $('#form_backup1 [name="id_bck1"]').val($(this).data('id_bck1'));
                            $('#nome_alocado1').html(rowData[0][1]);
                        });
                        $(td).html(rowData[col][1]);
                    },
                    "targets": [1]
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('cursor', 'pointer');
                        if (rowData[col][4]) {
                            $(td).addClass('colaborador-success');
                            $(td).removeClass('warning');
                            $(td).attr({
                                'title': (rowData[col][2] !== null ? 'Data de início: ' + rowData[col][2] + '\n' : '') +
                                        (rowData[col][3] !== null ? 'Data de término: ' + rowData[col][3] + '\n' : '') +
                                        (rowData[col][4] !== '' && rowData[col][4] !== undefined ? 'Colaborador backup: ' + $('[name="id_bck"] option[value="' + rowData[col][4] + '"]').text() : '')
                            });
                        } else {
                            $(td).addClass('warning');
                            $(td).removeClass('colaborador-success');
                        }
                        $(td).attr({
                            'data-toggle': 'modal',
                            'data-target': '#modal_backup2',
                            'data-id': rowData[col][0],
                            'data-nome': rowData[col][1],
                            'data-inicio2': rowData[col][2],
                            'data-termino2': rowData[col][3],
                            'data-id_bck2': rowData[col][4]
                        });
                        $(td).on('click', function () {
                            $('#form_backup2 [name="id"]').val($(this).data('id'));
                            $('#form_backup2 [name="data_inicio2"]').val($(this).data('inicio2'));
                            $('#form_backup2 [name="data_termino2"]').val($(this).data('termino2'));
                            $('#form_backup2 [name="id_bck2"]').val($(this).data('id_bck2'));
                            $('#nome_alocado2').html(rowData[0][1]);
                        });
                        $(td).html(rowData[col][1]);
                    },
                    "targets": [2]
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '8px 1px');
                        if (rowData[col][0] === 'disabled') {
                            $(td).css('background-color', '#e8e8e8').html('');
                        } else {
                            if ($(table.column(col).header()).hasClass('text-danger')) {
                                $(td).addClass('active');
                            }
                            $(td).css('cursor', 'pointer');
                            if (rowData[col][9] === 'AJ' || rowData[col][9] === 'FJ' || rowData[col][9] === 'SJ') {
                                $(td).addClass('date-width-warning');
                            } else if (rowData[col][9] === 'AN' || rowData[col][9] === 'FN' || rowData[col][9] === 'SN') {
                                $(td).addClass('date-width-danger');
                            } else if (rowData[col][9] === 'FR') {
                                $(td).addClass('date-width-success');
                            }
                            $(td).attr({
                                'data-toggle': 'modal',
                                'data-target': '#modal_form',
                                'data-tooltip': 'tooltip',
                                'data-placement': 'top',
                                'title': rowData[0][1] + '\n' + $(table.column(col).header()).attr('title') +
                                        '\nStatus: ' + (rowData[col][9] === 'AJ' ? 'Atraso justificado' :
                                                rowData[col][9] === 'AN' ? 'Atraso não-justificado' :
                                                rowData[col][9] === 'FJ' ? 'Falta justificada' :
                                                rowData[col][9] === 'FN' ? 'Falta não-justificada' :
                                                rowData[col][9] === 'SJ' ? 'Saída antecipada justificada' :
                                                rowData[col][9] === 'SN' ? 'Saída antecipada não-justificada' :
                                                rowData[col][9] === 'FR' ? 'Feriado' : 'Ok') +
                                        (rowData[col][2] !== '' && rowData[col][2] !== undefined ? '\nHoras devedoras: ' + rowData[col][2] : '') +
                                        (rowData[col][7] !== '' && rowData[col][7] !== undefined ? '\nDetalhes: ' + rowData[col][7] : '') +
                                        (rowData[col][14] !== '' && rowData[col][14] !== undefined ? '\nBackup nº 1: ' + rowData[col][14] : '') +
                                        (rowData[col][15] !== '' && rowData[col][15] !== undefined ? '\nBackup nº 2: ' + rowData[col][15] : '') +
                                        (rowData[col][8] !== '' && rowData[col][8] !== undefined ? '\nObservações: ' + rowData[col][8] : ''),

                                'data-id_alocado': rowData[col][1],
                                'data-text': rowData[col][5],
                                'data-calendar': rowData[col][2],

                                'data-id': rowData[col][0],
                                'data-qtde_dias': rowData[col][1],
                                'data-hora_atraso': rowData[col][2],
                                'data-hora_entrada': rowData[col][3],
                                'data-hora_intervalo': rowData[col][4],
                                'data-hora_retorno': rowData[col][5],
                                'data-hora_saida': rowData[col][6],
                                'data-detalhes': rowData[col][7],
                                'data-observacoes': rowData[col][8],
                                'data-status': rowData[col][9],
                                'data-id_alocado_bck': rowData[col][10],
                                'data-id_alocado_bck2': rowData[col][11],
                                'data-hora_glosa': rowData[col][12],
                                'data-id_detalhes': rowData[col][13]
                            });
                            $(td).on('click', function () {
                                atualizarDetalhes();
                                $('[name="id_alocado"]').val(rowData[0][0]);
                                $('[name="data"]').val($(table.column(col).header()).attr('data-dia'));
                                $('[name="id"]').val($(this).data('id'));
                                $('[name="qtde_dias"]').val($(this).data('qtde_dias'));
                                $('[name="hora_atraso"]').val($(this).data('hora_atraso'));
                                $('[name="hora_glosa"]').val($(this).data('hora_glosa'));
                                $('[name="hora_entrada"]').val($(this).data('hora_entrada'));
                                $('[name="hora_intervalo"]').val($(this).data('hora_intervalo'));
                                $('[name="hora_retorno"]').val($(this).data('hora_retorno'));
                                $('[name="hora_saida"]').val($(this).data('hora_saida'));
                                $('[name="detalhes"]').val($(this).data('id_detalhes'));
                                $('[name="observacoes"]').val($(this).data('observacoes'));
                                if ($(this).data('status') !== undefined) {
                                    $('[name="status"][value="' + $(this).data('status') + '"]').prop('checked', 'checked');
                                    selecionar_status($(this).data('status'));
                                } else {
                                    $('[name="status"][value="FJ"]').prop('checked', 'checked');
                                    selecionar_status('FJ');
                                }
                                $('[name="id_alocado_bck"]').val($(this).data('id_alocado_bck'));
                                $('[name="id_alocado_bck2"]').val($(this).data('id_alocado_bck2'));
                                $('#nome').html(rowData[0][1]);
                                $('#data').html($(table.column(col).header()).attr('data-mes_ano'));
                            });
                            $(td).html(rowData[col][9]);
                        }
                    },
                    "className": 'text-center',
                    "targets": 'date-width', //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).removeClass('text-success text-danger');
                        if (rowData[col] !== null) {
                            $(td).addClass('text-danger');
//                            if (rowData[col].indexOf('-') > -1) {
//                                $(td).addClass('text-danger');
//                            } else if (rowData[col] !== '00:00') {
//                                $(td).addClass('text-success');
//                            }
                            $(td).html('<strong>' + rowData[col] + '</strong>');
                        }
                    },
                    className: "warning text-right",
                    "targets": [-1, -2], //last column
                    "orderable": false, //set not orderable
                    "searchable": false
                }
            ]
        });

        table_totalizacao = $('#table_totalizacao').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "iDisplayLength": 25,
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            "order": [[0, 'asc']],
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('apontamento_totalizacao/ajax_list') ?>",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.busca = busca;
                    return d;
                },
                "dataSrc": function (json) {
                    $('[name="mes"]').val(json.calendar.mes);
                    $('[name="ano"]').val(json.calendar.ano);
                    $('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));

                    var dt1 = new Date();
                    var dt2 = new Date();
                    dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                    if (dt1.getTime() < dt2.getTime()) {
                        $('#mes_seguinte').addClass('disabled').parent().css('cursor', 'not-allowed');
                    } else {
                        $('#mes_seguinte').removeClass('disabled').parent().css('cursor', '');
                    }

                    return json.data;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    className: "warning",
                    "targets": [0]
                },
                {
                    className: "text-center",
                    "searchable": false,
                    "targets": [2] //last column
                },
                {
                    className: "text-right",
                    "searchable": false,
                    "targets": [1, 3, 4, 5] //last column
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).html('<strong>' + rowData[col] + '</strong>');
                    },
                    className: "text-right",
                    "targets": [-1], //last column
                    "searchable": false
                }
            ]
        });

        atualizarColaboradores();
    });

    function atualizarFiltro() {
        $.ajax({
            url: "<?php echo site_url('apontamento/atualizar_filtro/') ?>",
            type: "POST",
            dataType: "JSON",
            data: $('#busca').serialize(),
            success: function (data)
            {
                $('[name="area"]').replaceWith(data.area);
                $('[name="setor"]').replaceWith(data.setor);
                $('[name="cargo"]').replaceWith(data.cargo);
                $('[name="funcao"]').replaceWith(data.funcao);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function atualizarColaboradores()
    {
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_colaboradores/') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                busca: busca
            },
            success: function (data)
            {
                $('[name="id_bck"]').replaceWith(data.id_bck);
                $('[name="id_bck1"]').replaceWith(data.id_bck1);
                $('[name="id_bck2"]').replaceWith(data.id_bck2);
                $('[name="id_alocado_bck"]').replaceWith(data.id_alocado_bck);
                $('[name="id_alocado_bck2"]').replaceWith(data.id_alocado_bck2);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function atualizarDetalhes()
    {
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_edit/') ?>",
            type: "POST",
            dataType: "html",
            success: function (data)
            {
                $('#detalhes').replaceWith(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    $('#limpar').on('click', function () {
        $("#busca")[0].reset();
        //atualizarFiltro();
    });

    $('#modal_form').on('show.bs.modal', function (event) {
        var event = $(event.relatedTarget);
        $('[name="dado1"]').val(event.data('status'));
        $('[name="dado2"]').val(event.data('text'));
    });

    $('[name="status"]').on('change', function () {
        selecionar_status($(this).val());
    });

    function sugestao_detalhe(event) {
        $('[name="detalhes"]').val($(event).text());
    }

    function selecionar_status(value) {
        if (value === 'FJ' || value === 'FN' || value === 'FR') {
            $('[name="qtde_dias"]').prop('disabled', false);
            $('.hora').prop('disabled', true);
        } else if (value === 'AJ' || value === 'AN') {
            $('[name="qtde_dias"]').prop('disabled', true);
            $('.hora').prop('disabled', false);
        } else {
            $('[name="qtde_dias"], .hora').prop('disabled', false);
            $('[name="qtde_dias"], .hora').prop('disabled', false);
        }
    }

    function proximo_mes(value = 1) {
        if ($('#mes_seguinte').hasClass('disabled') && value === 1) {
            return false;
        }
        var queryStr_busca = busca.split('&');
        var arr_busca = {};
        $(queryStr_busca).each(function (i) {
            var param = queryStr_busca[i].split('=');
            arr_busca[param[0]] = param[1];
        });

        var dt = new Date(arr_busca.ano, arr_busca.mes - 1);
        dt.setMonth(dt.getMonth() + (value));
        arr_busca.mes = (dt.getMonth() < 9 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1);
        arr_busca.ano = dt.getFullYear();

        busca = $.param(arr_busca);
        reload_table();
        atualizarColaboradores();
    }

    function filtrar() {
        var data_proximo_mes = new Date();
        var data_busca = new Date();

        data_proximo_mes.setDate(1);
        data_proximo_mes.setMonth(data_proximo_mes.getMonth() + 1);
        data_busca.setFullYear($('[name="ano"]').val(), ($('[name="mes"]').val() - 1), 1);
        if (data_proximo_mes.getTime() < data_busca.getTime()) {
            $('[name="mes"]').val(data_proximo_mes.getMonth() + 1);
            $('[name="ano"]').val(data_proximo_mes.getFullYear());
        }

        busca = $('#busca').serialize();
        reload_table();
        $('#alerta_depto').text($('[name="depto"] option:selected').text());
        $('#alerta_area').text($('[name="area"] option:selected').text());
        $('#alerta_setor').text($('[name="setor"] option:selected').text());
    }

    function add_mes() {
        $.ajax({
            url: "<?php echo site_url('apontamento/novo/') ?>",
            type: "POST",
            dataType: "JSON",
            data: busca,
            success: function (data)
            {
                reload_table();
                atualizarColaboradores();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function excluir_mes() {
        if (confirm('Deseja limpar o mês selecionado?')) {

            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_limpar/') ?>",
                type: "POST",
                dataType: "JSON",
                data: busca,
                success: function (data)
                {
                    reload_table();
                    atualizarColaboradores();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
    }

    function edit_backup(id) {
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/ajax_colaboradores/') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (data)
            {
                $('#backup_1').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_status(id)
    {
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/edit_status/') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (data)
            {
                $('[name="id"]').val(data.id);
                $('#nome').text(data.nome);
                $('[name="observacoes"]').val(data.observacoes);

                $('#modal_form').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax 
        table_totalizacao.ajax.reload(null, false); //reload datatable ajax 
    }

    function salvar_ferias()
    {
        $('#btnSaveBackup').text('Salvando...'); //change button text
        $('#btnSaveBackup, #btnLimparBackup').attr('disabled', true); //set button disable 

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_ferias') ?>",
            type: "POST",
            data: $('#form_backup').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_backup').modal('hide');
                    reload_table();
                }

                $('#btnSaveBackup').text('Salvar'); //change button text
                $('#btnSaveBackup, #btnLimparBackup').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                if (jqXHR.statusText === 'OK') {
                    alert(jqXHR.responseText);
                } else {
                    alert('Erro ao enviar formulário');
                }

                $('#btnSaveBackup').text('Salvar'); //change button text
                $('#btnSaveBackup, #btnLimparBackup').attr('disabled', false); //set button enable 
            }
        });
    }

    function salvar_backup1()
    {
        $('#btnSaveBackup1').text('Salvando...'); //change button text
        $('#btnSaveBackup1, #btnLimparBackup1').attr('disabled', true); //set button disable 

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_backup1') ?>",
            type: "POST",
            data: $('#form_backup1').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_backup1').modal('hide');
                    reload_table();
                }

                $('#btnSaveBackup1').text('Salvar'); //change button text
                $('#btnSaveBackup1, #btnLimparBackup1').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                if (jqXHR.statusText === 'OK') {
                    alert(jqXHR.responseText);
                } else {
                    alert('Erro ao enviar formulário');
                }

                $('#btnSaveBackup1').text('Salvar'); //change button text
                $('#btnSaveBackup1, #btnLimparBackup1').attr('disabled', false); //set button enable 
            }
        });
    }

    function salvar_backup2()
    {
        $('#btnSaveBackup2').text('Salvando...'); //change button text
        $('#btnSaveBackup2, #btnLimparBackup2').attr('disabled', true); //set button disable 

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_backup2') ?>",
            type: "POST",
            data: $('#form_backup2').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_backup2').modal('hide');
                    reload_table();
                }

                $('#btnSaveBackup2').text('Salvar'); //change button text
                $('#btnSaveBackup2, #btnLimparBackup2').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                if (jqXHR.statusText === 'OK') {
                    alert(jqXHR.responseText);
                } else {
                    alert('Erro ao enviar formulário');
                }

                $('#btnSaveBackup2').text('Salvar'); //change button text
                $('#btnSaveBackup2, #btnLimparBackup2').attr('disabled', false); //set button enable 
            }
        });
    }

    function limpar_ferias()
    {
        if (confirm('Deseja limpar o conteúdo?')) {
            $('#form_backup')[0].reset();
            salvar_ferias();
        }
    }

    function limpar_backup1()
    {
        if (confirm('Deseja limpar o conteúdo?')) {
            $('#form_backup1')[0].reset();
            salvar_backup1();
        }
    }

    function limpar_backup2()
    {
        if (confirm('Deseja limpar o conteúdo?')) {
            $('#form_backup2')[0].reset();
            salvar_backup2();
        }
    }

    function save()
    {
        $('#btnSave').text('Salvando...'); //change button text
        $('#btnSave, #btnApagar').attr('disabled', true); //set button disable 

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_save') ?>",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                }

                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave, #btnApagar').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave, #btnApagar').attr('disabled', false); //set button enable 
            }
        });
    }

    function apagar()
    {
        if (confirm('Deseja limpar o status da data selecionada?'))
        {

            $('#btnApagar').text('Apagando...'); //change button text
            $('#btnApagar').attr('disabled', true); //set button disable 
            $('#btnSave').attr('disabled', true); //set button disable 

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_delete') ?>",
                type: "POST",
                data: {
                    id: $('[name="id"]').val()
                },
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnApagar').text('Apagar'); //change button text
                    $('#btnApagar').attr('disabled', false); //set button enable 
                    $('#btnSave').attr('disabled', false); //set button enable 
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus);
                    alert('Error adding / update data');
                    $('#btnApagar').text('Apagar'); //change button text
                    $('#btnApagar').attr('disabled', false); //set button enable 
                    $('#btnSave').attr('disabled', false); //set button enable 
                }
            });
        }
    }

</script>

<?php
require_once "end_html.php";
?>