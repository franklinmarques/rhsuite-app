<?php
require_once "header.php";
?>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-user-plus"></i> Cadastrar Funcionário
                        </header>
                        <div class="panel-body">
                            <?php echo form_open('home/novofuncionario_json', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                            <div class="form-group last">
                                <label class="col-sm-3 control-label">Foto</label>

                                <div class="col-lg-7 controls">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=Sem+imagem"
                                                 alt=""/>
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail"
                                             style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                        <div>
                                                   <span class="btn btn-white btn-file">
                                                   <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Selecionar Imagem</span>
                                                   <span class="fileupload-exists"><i
                                                           class="fa fa-undo"></i> Alterar</span>
                                                   <input type="file" name="logo" class="default"/>
                                                   </span>
                                            <a href="#" class="btn btn-danger fileupload-exists"
                                               data-dismiss="fileupload"><i class="fa fa-trash"></i> Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nome</label>

                                <div class="col-lg-7 controls">
                                    <input type="text" name="funcionario" placeholder="Nome" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Cargo - Função</label>

                                <div class="col-lg-7 controls">
                                    <input type="text" name="funcao" placeholder="Cargo - Função" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">E-mail</label>

                                <div class="col-lg-7 controls">
                                    <input type="text" name="email" placeholder="E-mail" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Senha</label>

                                <div class="col-lg-7 controls">
                                    <input type="password" name="senha" placeholder="Senha" value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Confirmar Senha</label>

                                <div class="col-lg-7 controls">
                                    <input type="password" name="confirmarsenha" placeholder="Confirmar Senha"
                                           value=""
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nível de acesso</label>

                                <div class="col-lg-7 controls">
                                    <select name="nivel_acesso" class="form-control">
                                        <option value="1">Administrador</option>
                                        <option value="2">Multiplicador</option>
                                        <option value="3">Gestor</option>
                                        <option value="4">Colaborador</option>
                                        <option value="5">Cliente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>

                                <div class="col-lg-7 controls">
                                    <select name="status" class="form-control">
                                        <option value="1">Ativo</option>
                                        <option value="0">Inativo</option>
                                        <option value="3">Em Experiência</option>
                                        <option value="4">Em desligamento</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-3">
                                    <button type="submit" name="submit" class="btn btn-primary"><i
                                            class="fa fa-save"></i>
                                        &nbsp;Cadastrar
                                    </button>
                                </div>
                            </div>
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
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileupload/bootstrap-fileupload.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Adicionar Funcionário';
        });
    </script>

    <script src="<?php echo base_url("assets/js/bootstrap-fileupload/bootstrap-fileupload.js"); ?>"></script>
<?php
require_once "end_html.php";
?>