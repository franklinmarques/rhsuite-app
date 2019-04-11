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
                        <i class="fa fa-plus"></i> Importar funcionários
                    </header>
                    <div class="panel-body">
                        <?php echo form_open_multipart('funcionario/importarCsv', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Arquivo (*.csv)</label>

                            <div class="col-sm-7 col-lg-7 controls">
                                <input type="file" name="arquivo" accept=".csv" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2">
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-upload"></i>
                                    Importar
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </section>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-comment-o"></i> Instruções para geração e importação do arquivo
                    </div>
                    <div class="panel-body">
                        <p> 1. Abra o aplicativo do excel e delete as planilhas (abas) extras, deixando somente uma
                            planilha
                            ativa.
                        </p>

                        <p>
                            2. Identifique a primeira linha com as seguintes palavras: funcionario, email e senha.
                        </p>

                        <p>
                            3. Preencha as colunas correspondentes com os dados dos funcionários conforme o exemplo ao
                            lado.
                        </p>

                        <p>
                            4. Salve o arquivo com .csv (separado por (,) vírgulas).
                        </p>

                        <p>
                            5. Clique no botão escolher arquivo e selecione o arquivo desejado, em seguida clique no
                            botão
                            importar.
                        </p>

                        <p>
                            6. A plataforma importará os dados e apresentará uma mensagem de sucesso de importação.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <section class="panel">
                    <header class="panel-heading">
                        <i class="fa fa-file-excel-o"></i> Exemplo de arquivo
                    </header>
                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th>Funcionario</th>
                                    <th>Email</th>
                                    <th>Senha</th>
                                    <th>Cargo / Função</th>
                                    <th>Nível de Acesso</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>nomefuncionario1</td>
                                    <td>funcionario1@hotmail.com</td>
                                    <td>senha1</td>
                                    <td>Consultor</td>
                                    <th style="color: red;"> * OPÇÕES ABAIXO</th>
                                </tr>
                                <tr>
                                    <td>nomefuncionario2</td>
                                    <td>funcionario2@hotmail.com</td>
                                    <td>senha2</td>
                                    <td>Assistente Comercial</td>
                                    <th style="color: red;"> * OPÇÕES ABAIXO</th>
                                </tr>
                                <tr>
                                    <td>nomefuncionario3</td>
                                    <td>funcionario3@hotmail.com</td>
                                    <td>senha3</td>
                                    <td>Auxiliar Administrativo</td>
                                    <th style="color: red;"> * OPÇÕES ABAIXO</th>
                                </tr>
                                </tbody>
                            </table>
                            <p style="color: red; font-weight: bold;">
                                * As opções podem ser:
                            </p>
                            <ul style="color: red; font-weight: bold;">
                                <li>Administrador</li>
                                <li>Colaborador</li>
                                <li>Cliente</li>
                                <li>Gestor</li>
                                <li>Multiplicador</li>
                            </ul>
                        </section>
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
<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Cadastrar Treinamento para Funcionário - <?php echo $row->nome; ?>';
    });
</script>
<?php
require_once "end_html.php";
?>
