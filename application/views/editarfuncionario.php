<?php
require_once "header.php";
?>
    <style>
        .jstree-defaulto {
            color: #31708f;
        }

        .jstree-warning {
            color: #f0ad4e;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-pencil-square-o"></i> Editar Funcionário - <?php echo $row->nome; ?>
                        </header>
                        <div class="panel-body">
                            <?php echo form_open('home/editarfuncionario_json/' . $row->id, 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                            <div class="text-right">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                    Salvar
                                </button>
                                <button class="btn btn-default" onclick="javascript:history.back()"><i
                                            class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                                </button>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Logo</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">

                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <?php
                                            $nome_foto = $row->foto;
                                            $logo = base_url('imagens/usuarios/' . $nome_foto);

                                            if (!$logo) {
                                                $nome_foto = 'Sem imagem';
                                                $logo = "https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem";
                                            }
                                            ?>
                                            <img src="<?= $logo; ?>" alt="<?= $row->nome; ?>">
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                            <span class="fileinput-exists"><i class="fa fa-undo"></i> Alterar</span>
                                            <input type="file" name="logo" class="default" accept="image/*"/>
                                        </span>
                                            <a href="#" class="btn btn-danger fileinput-exists"
                                               data-dismiss="fileinput"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Nome</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <?php echo form_dropdown('funcionario', $funcionarios, $row->nome, 'class="combobox form-control"'); ?>
                                    <!--<input type="text" name="funcionario" placeholder="Nome" value="<?php //echo $row->nome; ?>" class="form-control" />-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Departamento</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <?php echo form_dropdown('depto', $depto, $row->depto, 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Área</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <?php echo form_dropdown('area', $area, $row->area, 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Setor</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <?php echo form_dropdown('setor', $setor, $row->setor, 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Contrato</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <?php echo form_dropdown('contrato', $contrato, $row->contrato, 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Centro de custo</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <?php echo form_dropdown('centro_custo', $centro_custo, $row->centro_custo, 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Cargo</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <?php echo form_dropdown('cargo', $cargo, $row->cargo, 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Função</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <?php echo form_dropdown('funcao', $funcao, $row->funcao, 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Telefone(s)</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="text" name="telefone" placeholder="Telefone"
                                           value="<?php echo $row->telefone; ?>" class="form-control tags"
                                           data-role="tagsinput"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">E-mail</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="text" name="email" placeholder="E-mail"
                                           value="<?php echo $row->email; ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Obs:</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    (Caso não queira alterar a senha, deixe os campos abaixo em branco)
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Senha</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="senha" placeholder="Senha" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Confirmar Senha</label>
                                <div class="col-sm-9 col-lg-10 controls">
                                    <input type="password" name="confirmarsenha" placeholder="Confirmar Senha" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Nível de acesso</label>
                                <div class="col-sm-4 col-lg-3 controls">
                                    <?php echo form_dropdown('nivel_acesso', $nivel_acesso, $row->nivel_acesso, 'class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Data de admissão</label>
                                <div class="col-sm-3 col-lg-2 controls">
                                    <input type="text" name="data_admissao" id="data_admissao" placeholder="dd/mm/aaaa"
                                           value="<?php echo $row->data_admissao; ?>" class="form-control text-center"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Status</label>

                                <div class="col-sm-4 col-lg-3 controls">
                                    <?php echo form_dropdown('status', $status, $row->status, 'class="form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Nome do cartão Vale Transporte</label>
                                <div class="col-sm-7 col-lg-8 controls">
                                    <input type="text" name="nome_cartao" placeholder="Nome do cartão"
                                           value="<?php echo $row->nome_cartao; ?>" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 col-lg-2 control-label">Valor Vale Transporte</label>

                                <div class="col-sm-4 col-lg-3 controls">
                                    <input type="text" name="valor_vt" placeholder="Valor VT"
                                           value="<?php echo $row->valor_vt; ?>" class="form-control"/>
                                </div>
                            </div>
                            <div id="box-progresso" style="display: none;">
                                <div class="form-group">
                                    <label class="col-sm-3 col-lg-2 control-label">&nbsp;</label>
                                    <div class="col-sm-9 col-lg-10 controls">
                                        <div id="progresso" class="progress progress-mini pbar">
                                            <div class="progress-bar progress-bar-success ui-progressbar-value"
                                                 style="width:0%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                    <button type="submit" name="submit" class="btn btn-primary"><i
                                                class="fa fa-save"></i> Salvar
                                    </button>
                                </div>
                            </div>
                            <?php if ($this->session->userdata('empresa') === '78'): ?>
                                <fieldset>
                                    <legend>
                                        <small>Sistema de gerenciamento de acesso à funcionalidades da Plataforma
                                        </small>
                                    </legend>
                                    <ul id="tree">
                                        <li>
                                            <input type="checkbox">
                                            <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                            Operacional PAPD
                                            <ul>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[PAPD][]" value="501">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Gerenciar
                                                    pacientes
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[PAPD][]" value="502">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Gestão
                                                    Atividades/Deficiências
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[PAPD][]" value="503">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Relatório
                                                    Totalização
                                                    Mensal
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[PAPD][]" value="510">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Gerenciar
                                                    Atendimentos
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <input type="checkbox">
                                            <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                            Operacional ST
                                            <ul>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[ST][]" value="401">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Gestão de
                                                    Contratos
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[ST][]" value="402">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Totalização
                                                    Mensal
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[ST][]" value="403">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Relatórios
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[ST][]" value="410">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Apontamentos
                                                    Diários
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <input type="checkbox">
                                            <i class="glyphicon glyphicon-folder-open jstree-warning"></i>&ensp;Gestão
                                            Operacional CD
                                            <ul>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[CD][]" value="601">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Gestão de
                                                    Contratos
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[CD][]" value="602">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Totalização
                                                    Mensal
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[CD][]" value="603">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Relatórios
                                                </li>
                                                <li>
                                                    <input type="checkbox" name="hash_acesso[CD][]" value="610">
                                                    <i class="glyphicon glyphicon-file text-info"></i> Apontamentos
                                                    Diários
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </fieldset>
                            <?php endif; ?>
                            <?php echo form_close(); ?>
                        </div>
                    </section>
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
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>"/>

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Editar Funcionário - <?php echo $row->nome; ?>';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>

    <script>
        $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
        $('#data_admissao').mask('00/00/0000');

        $(document).ready(function () {
            $('.combobox').combobox();

            var hash_acesso = <?= $row->hash_acesso ?>;
            if (hash_acesso !== null) {
                $.each(hash_acesso, function (i, item) {
                    $.each(item, function (a, value) {
                        $('input[name="hash_acesso[' + i + '][]"][value="' + value + '"]').trigger('click');
                    });
                });
            }
        });

        $('#tree input[type="checkbox"]').change(function (e) {

            var checked = $(this).prop("checked"),
                container = $(this).parent(),
                siblings = container.siblings();

            container.find('input[type="checkbox"]').prop({
                indeterminate: false,
                checked: checked
            });

            function checkSiblings(el) {

                var parent = el.parent().parent(),
                    all = true;

                el.siblings().each(function () {
                    return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
                });

                if (all && checked) {

                    parent.children('input[type="checkbox"]').prop({
                        indeterminate: false,
                        checked: checked
                    });

                    checkSiblings(parent);

                } else if (all && !checked) {

                    parent.children('input[type="checkbox"]').prop("checked", checked);
                    parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
                    checkSiblings(parent);

                } else {

                    el.parents("li").children('input[type="checkbox"]').prop({
                        indeterminate: true,
                        checked: false
                    });

                }

            }

            checkSiblings(container);
        });
    </script>

<?php
require_once "end_html.php";
?>