<?php require_once 'header.php'; ?>

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-reorder"></i> Cadastro de Gestão de Processo
                        </header>
                        <div class="panel-body">
                            <?php echo form_open_multipart('gestaoProcessos/inserir', 'data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>

                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button type="submit" name="submit" class="btn btn-success"><i
                                                class="fa fa-save"></i> Salvar
                                    </button>
                                    <button type="button" class="btn btn-default" onclick="javascript:history.back()"><i
                                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </button>
                                </div>
                            </div>

                            <div class="form-body">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">URL página</label>
                                    <div class="col-sm-10">
                                        <?php echo form_dropdown('url_pagina', $urlPaginas, '', 'class="form-control"'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">Orientacoes gerais</label>
                                    <div class="col-sm-10">
                                            <textarea name="orientacoes_gerais" class="form-control"
                                                      rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2 text-nowrap">Processo 1</label>
                                    <div class="col-md-10">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <div class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Selecionar arquivo</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="processo_1" accept=".pdf"/>
                                            </div>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">Limpar</a>
                                        </div>
                                        <span id="nome_processo_1" class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2 text-nowrap">Processo 2</label>
                                    <div class="col-md-10">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <div class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Selecionar arquivo</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="processo_2" accept=".pdf"/>
                                            </div>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">Limpar</a>
                                        </div>
                                        <span id="nome_processo_2" class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2 text-nowrap">Documentação 1</label>
                                    <div class="col-md-10">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <div class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Selecionar arquivo</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="documentacao_1" accept=".pdf"/>
                                            </div>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">Limpar</a>
                                        </div>
                                        <span id="nome_documentacao_1" class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-2 text-nowrap">Documentação 2</label>
                                    <div class="col-md-10">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename"></span>
                                            </div>
                                            <div class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Selecionar arquivo</span>
                                                <span class="fileinput-exists">Alterar</span>
                                                <input type="file" name="documentacao_2" accept=".pdf"/>
                                            </div>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                               data-dismiss="fileinput">Limpar</a>
                                        </div>
                                        <span id="nome_documentacao_2" class="help-block"></span>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <button type="submit" name="submit" class="btn btn-success"><i
                                                class="fa fa-save"></i> Salvar
                                    </button>
                                    <button type="button" class="btn btn-default" onclick="javascript:history.back()"><i
                                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                    </button>
                                </div>
                            </div>
                            <?php echo form_close(); ?>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

<?php require_once 'end_js.php'; ?>

    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Cadastro de Gestão de Processo';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>

<?php require_once 'end_html.php'; ?>