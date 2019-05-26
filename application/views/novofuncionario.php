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

            <!-- page start-->

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-user-plus"></i> Cadastrar funcionário
                        </header>
                        <div class="panel-body">
                            <?php echo form_open('home/novofuncionario_json', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Total de colaboradores</label>
                                <div class="col-lg-7">
                                    <label class="visible-xs"></label>
                                    <?php if ($qtde_max_colaboradores === 'sem limite' or $qtde_colaboradores < $qtde_max_colaboradores): ?>
                                    <p class="bg-info text-info" style="padding: 5px;">
                                        <?php else: ?>
                                    <p class="bg-danger text-danger" style="padding: 5px;">
                                        <?php endif; ?>
                                        <small>Já cadastrados: <strong><?= $qtde_colaboradores; ?></strong></small>
                                        <br>
                                        <small>Permitidos para esta licença: <strong><?= $qtde_max_colaboradores; ?></strong></small>
                                    </p>
                                </div>
                                <div class="col-lg-2 text-right">
                                    <button type="submit" name="submit" class="btn btn-primary"><i
                                                class="fa fa-save"></i>
                                        Cadastrar
                                    </button>
                                </div>
                            </div>
                            <div class="form-group last">
                                <label class="col-sm-3 control-label">Foto</label>
                                <div class="col-lg-7 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: auto; height: 150px;">
                                            <img src="https://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem"
                                                 alt=""/>
                                        </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail"
                                             style="width: auto; height: 150px;"></div>
                                        <div>
                                        <span class="btn btn-white btn-file">
                                            <span class="fileinput-new"><i class="fa fa-paper-clip"></i> Selecionar imagem</span>
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
                                <label class="col-sm-3 control-label">Nome</label>
                                <div class="col-lg-7 controls">
                                    <?php echo form_dropdown('funcionario', $funcionarios, '', 'class="combobox form-control"'); ?>
                                    <!--<input type="text" name="funcionario" placeholder="Nome" value="" class="form-control"/>-->
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Departamento</label>
                                <div class="col-lg-7 controls">
                                    <?php echo form_dropdown('depto', $depto, '', 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Área</label>
                                <div class="col-lg-7 controls">
                                    <?php echo form_dropdown('area', $area, '', 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Setor</label>
                                <div class="col-lg-7 controls">
                                    <?php echo form_dropdown('setor', $setor, '', 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Contrato</label>
                                <div class="col-lg-7 controls">
                                    <?php echo form_dropdown('contrato', $contrato, '', 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Centro de custo</label>
                                <div class="col-lg-7 controls">
                                    <?php echo form_dropdown('centro_custo', $centro_custo, '', 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo</label>
                                <div class="col-lg-7 controls">
                                    <?php echo form_dropdown('cargo', $cargo, '', 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Função</label>
                                <div class="col-lg-7 controls">
                                    <?php echo form_dropdown('funcao', $funcao, '', 'class="combobox form-control"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Telefone(s)</label>
                                <div class="col-sm-7 controls">
                                    <input id="telefone" type="text" name="telefone" placeholder="Telefone" value=""
                                           class="tags" data-role="tagsinput"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">E-mail</label>
                                <div class="col-lg-7 controls">
                                    <input type="text" name="email" placeholder="E-mail" value="" class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Senha</label>
                                <div class="col-lg-7 controls">
                                    <input type="password" name="senha" placeholder="Senha" value=""
                                           class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Confirmar senha</label>
                                <div class="col-lg-7 controls">
                                    <input type="password" name="confirmarsenha" placeholder="Confirmar senha" value=""
                                           class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nível de acesso</label>
                                <div class="col-sm-4 col-lg-3 controls">
                                    <select name="nivel_acesso" class="form-control">
                                        <option value="1">Administrador</option>
                                        <option value="7">Presidente</option>
                                        <option value="8">Gerente</option>
                                        <option value="9">Coordenador</option>
                                        <option value="10">Supervisor</option>
                                        <option value="11">Encarregado</option>
                                        <option value="12">Líder</option>
                                        <option value="4">Colaborador</option>
                                        <option value="13">Cuidador Comunitário</option>
                                        <option value="3">Gestor</option>
                                        <option value="2">Multiplicador</option>
                                        <option value="6">Selecionador</option>
                                        <option value="5">Cliente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Data de admissão</label>
                                <div class="col-sm-3 col-lg-2 controls">
                                    <input type="text" name="data_admissao" id="data_admissao" placeholder="dd/mm/aaaa"
                                           value="" class="form-control text-center"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>
                                <div class="col-sm-4 col-lg-3 controls">
                                    <select name="status" class="form-control">
                                        <option value="1">Ativo</option>
                                        <option value="2">Inativo</option>
                                        <option value="3">Em experiência</option>
                                        <option value="4">Em desligamento</option>
                                        <option value="5">Desligado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nome do cartão Vale Transporte</label>
                                <div class="col-lg-7 controls">
                                    <input type="text" name="nome_cartao" placeholder="Nome do cartão" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Valor Vale Transporte</label>

                                <div class="col-sm-4 col-lg-3 controls">
                                    <input type="text" name="valor_vt" placeholder="Valor VT" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><i
                                                class="fa fa-save"></i> Cadastrar
                                    </button>
                                </div>
                            </div>
                            <?php if ($this->session->userdata('empresa') === '78'): ?>
                                <fieldset>
                                    <legend>
                                        <small>Sistema de gerenciamento de acesso à funcionalidades da Plataforma
                                        </small>
                                    </legend>

                                    <!--                            <ul id="firstTree" class="tree">
                                                                    <li><input name="nome" type="checkbox"> <a href="#"></a>Gestão Operacional PAPD
                                                                        <ul>
                                                                            <li><input name="nome" type="checkbox"> <a href="#"></a>Gerenciar pacientes</li>
                                                                            <li><input name="nome" type="checkbox"> <a href="#"></a>Gestão Atividades/Deficiências</li>
                                                                            <li><input name="nome" type="checkbox"> <a href="#"></a>Relatório Totalização Mensal</li>
                                                                            <li><input name="nome" type="checkbox"> <a href="#"></a>Gerenciar Atendimentos</li>
                                                                        </ul>
                                                                    </li>
                                                                    <li><input name="nome" type="checkbox"> <a href="#"></a>Gestão Operacional ST
                                                                        <ul>
                                                                            <li><input name="nome" type="checkbox"> <a href="#"></a>Gestão de Contratos</li>
                                                                            <li><input name="nome" type="checkbox"> <a href="#"></a>Totalização Mensal</li>
                                                                            <li><input name="nome" type="checkbox"> <a href="#"></a>Relatórios</li>
                                                                            <li><input name="nome" type="checkbox"> <a href="#"></a>Apontamentos Diários</li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>-->


                                    <!--                            <div id="tree">
                                                                    <ul>
                                                                        <li data-jstree='{"icon": "glyphicon glyphicon-folder-open jstree-warning"}'>Gestão Operacional PAPD
                                                                            <ul>
                                                                                <li data-jstree='{"icon": false}'><i class="glyphicon glyphicon-folder-open"></i> Gerenciar pacientes</li>
                                                                                <li data-jstree='{"icon": false}'>Gestão Atividades/Deficiências</li>
                                                                                <li data-jstree='{"icon": false}'>Relatório Totalização Mensal</li>
                                                                                <li data-jstree='{"icon": false}'>Gerenciar Atendimentos</li>
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                    <ul>
                                                                        <li data-jstree='{"icon": "fa fa-history jstree-defaulto"}'>Gestão Operacional ST
                                                                            <ul>
                                                                                <li data-jstree='{"icon": false}'>Gestão de Contratos</li>
                                                                                <li data-jstree='{"icon": false}'>Totalização Mensal</li>
                                                                                <li data-jstree='{"icon": false}'>Relatórios</li>
                                                                                <li data-jstree='{"icon": false, "disabled": true, "selected": true}'>Apontamentos Diários</li>
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                </div>-->


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
                            </form>
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
    <!--<link rel="stylesheet" href="<?php // echo base_url("assets/js/jstree/dist/themes/default-bootstrap/style.min.css");       ?>"/>-->
    <!--<link rel="stylesheet" href="<?php //echo base_url("assets/js/simpleTree/dist/css/simpletree.css");                     ?>"/>-->

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Adicionar Funcionário';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js') ?>"></script>
    <!--<script src="<?php // echo base_url('assets/js/jstree/dist/jstree.min.js')       ?>"></script>-->
    <!--<script src="<?php //echo base_url('assets/js/simpleTree/dist/js/jquery.simpletree.js')                     ?>"></script>-->
    <!--<script src="<?php // echo base_url('assets/js/checkTree/jquery.checktree.js')                    ?>"></script>-->

    <script>
        $('.tags').tagsInput({width: 'auto', defaultText: 'Telefone', placeholderColor: '#999', delimiter: '/'});
        $('#data_admissao').mask('00/00/0000');

        //    $('[name="contrato"]').typeahead({scrollHeight: 5, source:data });
        $(document).ready(function () {
            $('.combobox').combobox();

//        $("#tree").jstree({
//            "plugins": ['checkbox', 'theme', "html_data", "types"]
//        });
//        $('#firstTree').simpletree();
//        
//        $("ul.cktree").checkTree();
        });


        //    $('#firstTree').simpletree({
        //        classChanged: 'st-treed',
        //        classOpen: 'st-open',
        //        classCollapsed: 'st-collapsed',
        //        classLeaf: 'st-file',
        //        classLast: 'st-last'
        //    });
        //
        //    $('#firstTree').simpletree({
        //	  startCollapsed: true
        //	});


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