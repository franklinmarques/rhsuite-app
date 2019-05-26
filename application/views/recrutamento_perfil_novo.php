<?php
require_once "header.php";
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
                        <?php if ($this->session->userdata('tipo') !== 'candidato') : ?>
                            <li><a href="<?= site_url('recrutamento_candidatos') ?>">Gerenciamento de candidatos</a>
                            </li>
                        <?php endif; ?>
                        <li class="active"><?= $titulo ?></li>
                    </ol>
                    <br>
                    <ul class="nav nav-tabs" role="tablist" style="font-size: 15px; font-weight: bolder;">
                        <li role="presentation" class="active">
                            <a href="#dados_cadastrais" aria-controls="dados_cadastrais" role="tab" data-toggle="tab">Dados
                                cadastrais</a>
                        </li>
                        <?php if (empty($id)): ?>
                            <li role="presentation" class="disabled">
                                <a href="#">Formação</a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#">Histórico profissional</a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#">Currículo</a>
                            </li>
                        <?php else: ?>
                            <li role="presentation">
                                <a href="#formacao" aria-controls="formacao" role="tab" data-toggle="tab">Formação</a>
                            </li>
                            <li role="presentation">
                                <a href="#historico_profissional" aria-controls="historico_profissional" role="tab"
                                   data-toggle="tab">Histórico profissional</a>
                            </li>
                            <li role="presentation" class="disabled">
                                <a href="#">Currículo</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <br/>
                    <br/>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="dados_cadastrais">
                            <div class="row">
                                <div class="col col-md-12">
                                    <?php echo form_open($url, 'data-aviso="alert" class="form-horizontal ajax-upload autocomplete="off"'); ?>
                                    <input type="hidden" name="id" value=""/>
                                    <fieldset>
                                        <legend>Campos obrigatórios</legend>
                                        <div class="form-group last">
                                            <label class="col-sm-2 control-label">Foto</label>
                                            <div class="col-lg-7 controls">
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail"
                                                         style="width: auto; height: 150px;">
                                                        <img src="<?= base_url('imagens/usuarios/Sem+imagem.png') ?>"
                                                             alt="Sem imagem"/>
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                                         style="width: auto; height: 150px;"></div>
                                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="foto" class="default" accept="image/*"/>
                                        </span>
                                                        <a href="#" class="btn btn-default fileinput-exists"
                                                           data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <button type="submit" name="submit" class="btn btn-success"><i
                                                            class="fa fa-save"></i>
                                                    &nbsp;Salvar
                                                </button>
                                                <button class="btn btn-default" onclick="javascript:history.back()"><i
                                                            class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"><?= $this->session->userdata('tipo') === 'candidato' ? 'Nome' : 'Nome candidato' ?></label>
                                            <div class="col-lg-7 controls">
                                                <input type="text" name="nome"
                                                       placeholder="<?= $this->session->userdata('tipo') === 'candidato' ? 'Nome completo' : 'Nome do candidato' ?>"
                                                       value="" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Data nascimento</label>
                                            <div class="col-sm-2">
                                                <input type="text" name="data_nascimento" placeholder="dd/mm/aaaa"
                                                       value=""
                                                       class="form-control text-center date"/>
                                            </div>
                                            <label class="col-sm-1 control-label">Sexo</label>
                                            <div class="col-sm-2">
                                                <select name="sexo" class="form-control">
                                                    <option value="">selecione...</option>
                                                    <option value="M">Masculino</option>
                                                    <option value="F">Feminino</option>
                                                </select>
                                            </div>
                                            <label class="col-sm-1 control-label text-nowrap">Estado civil</label>
                                            <div class="col-sm-3">
                                                <select name="estado_civil" class="form-control">
                                                    <option value="">selecione...</option>
                                                    <option value="1">Solteiro(a)</option>
                                                    <option value="2">Casado(a)</option>
                                                    <option value="3">Desquitado(a)</option>
                                                    <option value="4">Divorciado(a)</option>
                                                    <option value="5">Viúvo(a)</option>
                                                    <option value="6">Outro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Telefone</label>
                                            <div class="col-lg-4 controls">
                                                <input type="text" name="telefone" placeholder="Telefone"
                                                       value=""
                                                       class="form-control"/>
                                            </div>
                                            <label class="col-sm-1 control-label">E-mail</label>
                                            <div class="col-lg-4 controls">
                                                <input type="email" name="email" placeholder="E-mail"
                                                       value=""
                                                       class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Senha</label>
                                            <div class="col-lg-4 controls">
                                                <input type="password" name="senha" placeholder="Senha" value=""
                                                       max="32"
                                                       class="form-control" autocomplete="new-password"/>
                                            </div>
                                            <label class="col-sm-1 control-label">Confirmar senha</label>
                                            <div class="col-lg-4 controls">
                                                <input type="password" name="confirmarsenha"
                                                       placeholder="Confirmar senha"
                                                       value=""
                                                       max="32" class="form-control" autocomplete="new-password"/>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend>Campos complementares</legend>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nome da mãe</label>
                                            <div class="col-lg-7 controls">
                                                <input type="text" name="nome_mae"
                                                       placeholder="Nome da mãe do(a) candidato(a)"
                                                       value="" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nome do pai</label>
                                            <div class="col-lg-7 controls">
                                                <input type="text" name="nome_pai"
                                                       placeholder="Nome do pai do(a) candidato(a)"
                                                       value="" class="form-control"/>
                                            </div>
                                        </div>
                                        <?php if ($this->session->userdata('tipo') !== 'candidato') : ?>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Status</label>
                                                <div class="col-sm-3 col-lg-2 controls">
                                                    <select name="status" class="form-control">
                                                        <option value="A">Ativo</option>
                                                        <option value="E">Excluído</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label">Nível de acesso</label>
                                                <div class="col-sm-3 col-lg-2 controls">
                                                    <select name="nivel_acesso" class="form-control">
                                                        <option value="C">Candidato</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-1 control-label">CPF</label>
                                                <div class="col-lg-2 controls">
                                                    <input type="text" name="cpf" id="cpf" placeholder="CPF"
                                                           value=""
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">RG</label>
                                            <div class="col-lg-2 controls">
                                                <input type="text" name="rg" id="rg" placeholder="RG" value=""
                                                       class="form-control"/>
                                            </div>
                                            <label class="col-sm-1 control-label">PIS</label>
                                            <div class="col-lg-2 controls">
                                                <input type="text" name="pis" id="pis" placeholder="PIS"
                                                       value=""
                                                       class="form-control"/>
                                            </div>
                                            <label class="col-sm-1 control-label">CEP</label>
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <input type="text" name="cep" id="cep" placeholder="CEP"
                                                           value=""
                                                           class="form-control"/>
                                                    <span class="input-group-btn">
                                            <button class="btn btn-info" id="consultar_cep" type="button"><i
                                                        class="glyphicon glyphicon-search"></i> Consultar CEP</button>
                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Logradouro</label>
                                            <div class="col-lg-6 controls">
                                                <input type="text" name="logradouro" id="logradouro"
                                                       placeholder="Logradouro"
                                                       value="" class="form-control"/>
                                            </div>
                                            <label class="col-sm-1 control-label">Número</label>
                                            <div class="col-lg-2 controls">
                                                <input type="number" name="numero" value=""
                                                       class="form-control text-right"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Complemento</label>
                                            <div class="col-lg-4 controls">
                                                <input type="text" name="complemento" id="complemento"
                                                       placeholder="Complemento"
                                                       value="" class="form-control"/>
                                            </div>
                                            <label class="col-sm-1 control-label">Bairro</label>
                                            <div class="col-lg-4 controls">
                                                <input type="text" name="bairro" id="bairro" placeholder="Bairro"
                                                       value="" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Estado</label>
                                            <div class="col-sm-2 controls">
                                                <?php echo form_dropdown('estado', $estados, '', 'id="estado" class="form-control filtro"'); ?>
                                            </div>
                                            <label class="col-sm-1 control-label">Cidade </label>
                                            <div class="col-lg-6 controls">
                                                <?php echo form_dropdown('cidade', $cidades, '', 'id="cidade" class="form-control filtro"'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Escolaridade</label>
                                            <div class="col-sm-4 col-lg-3 controls">
                                                <?php echo form_dropdown('escolaridade', $escolaridades, '', 'id="escolaridade" class="form-control"'); ?>
                                            </div>
                                            <label class="col-sm-1 control-label">Deficiência</label>
                                            <div class="col-sm-4 col-lg-3 controls">
                                                <?php echo form_dropdown('deficiencia', $deficiencias, '', 'id="deficiencia" class="form-control"'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Fonte contratação</label>
                                            <div class="col-sm-5 col-lg-4 controls">
                                                <?php echo form_dropdown('fonte_contratacao', $fontesContratacao, '', 'class="form-control"'); ?>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <?php echo form_close(); ?>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="formacao">
                        </div>

                        <div role="tabpanel" class="tab-pane" id="historico_profissional">
                        </div>
                    </div>

                </div>
            </div>

            <!-- page end-->

        </section>
    </section>
    <!--main content end-->
<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - <?= $titulo ?>';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

    <script>
        $('#cpf').mask('000.000.000-00', {reverse: true});
        $('#rg').mask('00.000.000-0', {reverse: true});
        $('#pis').mask('00.000.000.000', {reverse: true});
        $('#cep').mask('00000-000');
        $('.date').mask('00/00/0000');
        $('.valor').mask('##.###.##0,00', {reverse: true});

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
require_once "end_html.php";
?>