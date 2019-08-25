<?php
require_once APPPATH . 'views/header.php';
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
                        <i class="fa fa-plus"></i> Importar alunos
                        <button class="btn btn-default btn-sm" style="float: right; margin-top: -0.6%;" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    </header>
                    <div class="panel-body">
                        <?php echo form_open_multipart('ei/alunos/importarCsv', 'data-aviso="alert" class="form-horizontal ajax-upload"'); ?>
                        <div class="form-group">
                            <label class="col-sm-3 col-lg-2 control-label">Arquivo (*.csv)</label>
                            <div class="col-sm-7 col-lg-7 controls">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Selecionar arquivo</span>
                                        <span class="fileinput-exists">Alterar</span>
                                        <input type="file" name="arquivo" accept=".csv"/>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists"
                                       data-dismiss="fileinput">Remover</a>
                                </div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fa fa-upload"></i> Importar
                            </button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </section>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-comment-o"></i> Instruções para geração e importação do arquivo
                    </div>
                    <div class="panel-body">
                        <p> 1. Abra o aplicativo do excel e delete as planilhas (abas) extras, deixando somente uma
                            planilha ativa.</p>
                        <p> 2. Identifique a primeira linha com as seguintes palavras: funcionario, email e senha.</p>
                        <p> 3. Preencha as colunas correspondentes com os dados dos funcionários conforme o exemplo ao
                            lado.</p>
                        <p> 4. Salve o arquivo com .csv (separado por (,) vírgulas).</p>
                        <p> 5. Clique no botão escolher arquivo e selecione o arquivo desejado, em seguida clique no
                            botão importar.</p>
                        <p> 6. A plataforma importará os dados e apresentará uma mensagem de sucesso de importação.</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <section class="panel panel-default">
                    <header class="panel-heading">
                        <i class="fa fa-file-excel-o"></i> Exemplo de arquivo
                    </header>
                    <div class="panel-body">
                        <section id="unseen">
                            <div class='table-responsive'>
                                <table class="table table-bordered table-striped table-condensed"
                                       style='margin-bottom: 0px'>
                                    <thead>
                                    <tr style="vertical-align: middle;">
                                        <th style="vertical-align: middle;">Aluno</th>
                                        <th style="vertical-align: middle;">Enderço<br>(opcional)</th>
                                        <th style="vertical-align: middle;">Número<br>(opcional)</th>
                                        <th style="vertical-align: middle;">Complemento<br>(opcional)</th>
                                        <th style="vertical-align: middle;">Município<br>(opcional)</th>
                                        <th style="vertical-align: middle;" nowrap>Tel. <span style="color: red;">**</span><br>(opcional)</th>
                                        <th style="vertical-align: middle;">Contato<br>(opcional)</th>
                                        <th style="vertical-align: middle;">E-mail<br>(opcional)</th>
                                        <th style="vertical-align: middle;">CEP<br>(opcional)</th>
                                        <th style="vertical-align: middle;">Nome responsável<br>(opcional)</th>
                                        <th style="vertical-align: middle;">Hipótese diagnóstica</th>
                                        <th style="vertical-align: middle;">Observações<br>(opcional)</th>
                                        <th style="vertical-align: middle;">Escola</th>
                                        <th style="vertical-align: middle;"><span class="text-nowrap">Data matrícula</span><br>(opcional)</th>
                                        <th style="vertical-align: middle;">Períodos</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>nomealuno1</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-muted"><i>aluno1@provedor.com.br</i></td>
                                        <td></td>
                                        <td></td>
                                        <td>nomehipótese</td>
                                        <td></td>
                                        <td>nomeescola</td>
                                        <td class='text-center text-muted'><i>dd/mm/aaaa</i></td>
                                        <th style="color: red;" nowrap> * ver opções</th>
                                    </tr>
                                    <tr>
                                        <td>nomealuno2</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-muted"><i>aluno2@provedor.com.br</i></td>
                                        <td></td>
                                        <td></td>
                                        <td>nomehipótese</td>
                                        <td></td>
                                        <td>nomeescola</td>
                                        <td class='text-center text-muted'><i>dd/mm/aaaa</i></td>
                                        <th style="color: red;" nowrap> * ver opções</th>
                                    </tr>
                                    <tr>
                                        <td>nomealuno3</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-muted"><i>aluno3@provedor.com.br</i></td>
                                        <td></td>
                                        <td></td>
                                        <td>nomehipótese</td>
                                        <td></td>
                                        <td>nomeescola</td>
                                        <td class='text-center text-muted'><i>dd/mm/aaaa</i></td>
                                        <th style="color: red;" nowrap> * ver opções</th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class='row'>
                                <div class="col col-sm-6">
                                    <p style="color: red; font-weight: bold;"> * Selecione de um a três períodos abaixo,
                                        em qualquer ordem
                                        (ex: NM). Ou marque como I (integral), que equivale às três opções
                                        selecionadas:</p>
                                    <ul style="color: red; font-weight: bold;">
                                        <li>M (manhã)</li>
                                        <li>T (tarde)</li>
                                        <li>N (noite)</li>
                                    </ul>
                                </div>
                                <div class="col col-sm-6">
                                    <p style="color: red; font-weight: bold;"> ** Quando houver mais de um telefone por
                                        registro, é aconselhável separá-los por barra invertida (/).</p>
                                </div>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </div>

        <!-- page end-->

    </section>
</section>
<!--main content end-->

<!-- Css -->
<link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Importar alunos';
    });
</script>

<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<?php
require_once APPPATH . 'views/end_js.php';
require_once APPPATH . 'views/end_html.php';
?>
