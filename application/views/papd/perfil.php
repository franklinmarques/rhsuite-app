<?php
require_once APPPATH . "views/header.php";
?>
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
                        <li><a href="<?= site_url('papd/pacientes') ?>">Gerenciar pacientes</a></li>
                        <?php if ($id): ?>
                            <li class="active">Cadastro de paciente: <?= $nome ?></li>
                        <?php else: ?>
                            <li class="active">Cadastro de novo paciente</li>
                        <?php endif; ?>
                    </ol>
                    <!--<br>-->
                    <?php echo form_open('papd/pacientes/ajax_save', 'data-aviso="alert" class="form-horizontal ajax-upload" autocomplete="off"'); ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <input type="hidden" name="id_empresa" value="<?= $id_empresa ?>">
                    <input type="hidden" name="id_instituicao" value="<?= $id_instituicao ?>">
                    <div class="text-right">
                        <button type="submit" name="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar
                        </button>
                        <button class="btn btn-default" onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    </div>
                    <fieldset>
                        <legend>
                            <small>Dados do paciente</small>
                        </legend>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nome <span class="text-danger">*</span></label>
                            <div class="col-lg-7 controls">
                                <input type="text" name="nome" placeholder="Nome" value="<?= $nome ?>"
                                       class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">CPF</label>
                            <div class="col-sm-3 col-lg-2 controls">
                                <input type="text" name="cpf" id="cpf" value="<?= $cpf ?>" class="form-control"/>
                            </div>
                            <label class="col-sm-2 control-label">Data de nascimento <span class="text-danger">*</span></label>
                            <div class="col-sm-3 col-lg-2 controls">
                                <input type="text" name="data_nascimento" placeholder="dd/mm/aaaa"
                                       value="<?= $data_nascimento ?>" class="form-control text-center date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Sexo</label>
                            <div class="col col-md-3 form-group">
                                <label class="checkbox-inline">
                                    <input type="radio" name="sexo" value="M"<?= $sexo == 'M' ? ' checked' : '' ?>>
                                    Masculino
                                </label>
                                <label class="checkbox-inline">
                                    <input type="radio" name="sexo" value="F"<?= $sexo == 'F' ? ' checked' : '' ?>>
                                    Feminino
                                </label>
                            </div>
                            <label class="col-sm-1 control-label">Deficiência</label>
                            <div class="col-sm-4 col-lg-3 controls">
                                <?php echo form_dropdown('id_deficiencia', $deficiencias, $id_deficiencia, 'id="id_deficiencia" class="form-control"'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Cadastro municipal</label>
                            <div class="col-sm-2 col-lg-2 controls">
                                <input type="text" name="cadastro_municipal" placeholder="Cadastro municipal"
                                       value="<?= $cadastro_municipal ?>" class="form-control"/>
                            </div>
                            <label class="col-sm-2 control-label">Hipótese Diagnóstica</label>
                            <div class="col-lg-3 controls">
                                <?php echo form_dropdown('id_hipotese_diagnostica', $hds, $id_hipotese_diagnostica, 'id="id_hipotese_diagnostica" class="form-control"'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Logradouro</label>
                            <div class="col-lg-6 controls">
                                <input name="logradouro" id="logradouro" placeholder="Logradouro"
                                       value="<?= $logradouro ?>" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Número</label>
                            <div class="col-lg-2 controls">
                                <input name="numero" value="<?= $numero ?>" class="form-control text-right"
                                       type="number">
                            </div>
                            <label class="col-sm-1 control-label">CEP</label>
                            <div class="col-lg-2">
                                <!--<div class="input-group">-->
                                <input name="cep" id="cep" placeholder="CEP" value="<?= $cep ?>" class="form-control"
                                       maxlength="9" autocomplete="off" type="text">
                                <!--                                <span class="input-group-btn">
                                                                <button class="btn btn-info" id="consultar_cep" type="button"><i class="glyphicon glyphicon-search"></i> Consultar CEP</button>
                                                            </span>-->
                                <!--</div>-->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Complemento</label>
                            <div class="col-lg-5 controls">
                                <input name="complemento" id="complemento" placeholder="Complemento"
                                       value="<?= $complemento ?>" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Bairro</label>
                            <div class="col-lg-5 controls">
                                <input name="bairro" id="bairro" placeholder="Bairro" value="<?= $bairro ?>"
                                       class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Estado</label>
                            <div class="col-sm-2 controls">
                                <?php echo form_dropdown('estado', $estados, $estado, 'id="estado" class="form-control filtro"'); ?>
                            </div>
                            <label class="col-sm-1 control-label">Cidade </label>
                            <div class="col-lg-4 controls">
                                <?php echo form_dropdown('cidade', $cidades, $cidade, 'id="cidade" class="combobox form-control filtro"'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <small>Dados responsável 1</small>
                        </legend>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nome</label>
                            <div class="col-lg-7 controls">
                                <input type="text" name="nome_responsavel_1" placeholder="Nome"
                                       value="<?= $nome_responsavel_1 ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Telefone fixo</label>
                            <div class="col-sm-2 controls">
                                <input id="telefone" type="text" name="telefone_fixo_1" value="<?= $telefone_fixo_1 ?>"
                                       class="tags" data-role="tagsinput"/>
                            </div>
                            <label class="col-sm-2 control-label">Telefone celular</label>
                            <div class="col-sm-2 controls">
                                <input id="telefone" type="text" name="telefone_celular_1"
                                       value="<?= $telefone_celular_1 ?>" class="tags" data-role="tagsinput"/>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <small>Dados responsável 2</small>
                        </legend>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nome</label>
                            <div class="col-lg-7 controls">
                                <input type="text" name="nome_responsavel_2" placeholder="Nome"
                                       value="<?= $nome_responsavel_2 ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Telefone fixo</label>
                            <div class="col-sm-2 controls">
                                <input id="telefone" type="text" name="telefone_fixo_2" value="<?= $telefone_fixo_2 ?>"
                                       class="tags" data-role="tagsinput"/>
                            </div>
                            <label class="col-sm-2 control-label">Telefone celular</label>
                            <div class="col-sm-2 controls">
                                <input id="telefone" type="text" name="telefone_celular_2"
                                       value="<?= $telefone_celular_2 ?>" class="tags" data-role="tagsinput"/>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <small>Dados do contratante</small>
                        </legend>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nome</label>
                            <div class="col-lg-7 controls">
                                <input type="text" name="contratante" placeholder="Nome do contratante"
                                       value="<?= $contratante ?>" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Contrato</label>
                            <div class="col-lg-7 controls">
                                <input type="text" name="contrato" placeholder="Contrato" value="<?= $contrato ?>"
                                       class="form-control"/>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>
                            <small>Dados de controle</small>
                        </legend>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Data de ingresso <span
                                        class="text-primary">*</span></label>
                            <div class="col-sm-2 controls">
                                <input type="text" name="data_ingresso" placeholder="dd/mm/aaaa"
                                       value="<?= $data_ingresso ?>" class="form-control text-center date"/>
                            </div>
                            <label class="col-sm-3 control-label">Data início inatividade</label>
                            <div class="col-sm-2 controls">
                                <input type="text" name="data_inativo" placeholder="dd/mm/aaaa"
                                       value="<?= $data_inativo ?>" class="form-control text-center date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Data início monitoramento</label>
                            <div class="col-sm-2 controls">
                                <input type="text" name="data_afastamento" placeholder="dd/mm/aaaa"
                                       value="<?= $data_afastamento ?>" class="form-control text-center date"/>
                            </div>
                            <label class="col-sm-3 control-label">Data início fila de espera</label>
                            <div class="col-sm-2 controls">
                                <input type="text" name="data_fila_espera" placeholder="dd/mm/aaaa"
                                       value="<?= $data_fila_espera ?>" class="form-control text-center date"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Status <span class="text-danger">*</span></label>
                            <div class="col-sm-2 controls">
                                <?php echo form_dropdown('status', $grupo_status, $status, 'id="status" class="form-control"'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <!-- page end-->

        </section>
    </section>
    <!--main content end-->

<?php
require_once APPPATH . "views/end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar pacientes';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

    <script>
        $('.date').mask('00/00/0000');
        $('#cpf').mask('000.000.000-00');
        $('#cep').mask('00000-000');
        $('.combobox').combobox();
        var cidade_nome = '<?= $cidade_nome ?>';
        if (cidade_nome.length > 0) {
            $('#cidadeundefined').val('<?= $cidade_nome ?>');
        }

        $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
        $('#consultar_cep').on('click', function () {
            var cep = $('#cep').val();
            if (cep.length > 0) {
                $.ajax({
                    url: "<?php echo site_url('recrutamento_candidatos/consultar_cep/') ?>/",
                    type: "POST",
                    dataType: "json",
                    data: {
                        cep: cep
                    },
                    beforeSend: function () {
                        $('#consultar_cep').html('<i class="glyphicon glyphicon-search"></i> Consultando...');
                    },
                    success: function (json) {
                        if (json.erro === undefined) {
                            $('#logradouro').val(json.logradouro);
                            $('#complemento').val(json.complemento);
                            $('#bairro').val(json.bairro);
                            $('#estado').val(json.estado);
                            $('#numero').val(json.numero);
                            $('#cidade').html(json.cidade);
                        } else {
                            alert("CEP não encontrado.");
                            $('.filtro').val();
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error get data from ajax');
                    }
                }).done(function () {
                    $('#consultar_cep').html('<i class="glyphicon glyphicon-search"></i> Consultar CEP');
                });
            }
        });
        $('#estado').on('change', function () {
            $.ajax({
                url: "<?php echo site_url('recrutamento_candidatos/ajax_cidades/') ?>/",
                type: "POST",
                dataType: "JSON",
                data: {
                    estado: $(this).val()
                },
                success: function (data) {
                    $('#cidade').html(data.cidades);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        });
    </script>

<?php
require_once APPPATH . "views/end_html.php";
?>