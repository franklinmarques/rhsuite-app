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
                        <p> 2. Identifique a primeira linha conforme o exemplo ao lado: funcionario, email, data de
                            admissão...</p>
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
                                    <tr>
                                        <th>Funcionário</th>
                                        <th>Email</th>
                                        <th nowrap>Data de admissão</th>
                                        <th>Senha</th>
                                        <th>Departamento</th>
                                        <th>Área</th>
                                        <th>Setor</th>
                                        <th>Cargo</th>
                                        <th>Função</th>
                                        <th nowrap>Telefone <span style="color: red;">**</span></th>
                                        <th nowrap>Nível de acesso</th>
                                        <th nowrap>Tipo de vínculo</th>
                                        <th>CNPJ</th>
                                        <th>Município</th>
                                        <th>Contrato</th>
                                        <th nowrap>Centro de custo</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>nomefuncionario1</td>
                                        <td>funcionario1@suaempresa.com.br</td>
                                        <td class='text-center'>DD/MM/AAAA</td>
                                        <td>senha1</td>
                                        <td>Produção</td>
                                        <td>Estoque</td>
                                        <td>1</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <th style="color: red;" nowrap> * OPÇÕES ABAIXO</th>
                                        <th style="color: red;" nowrap> * OPÇÕES ABAIXO</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>nomefuncionario2</td>
                                        <td>funcionario2@suaempresa.com.br</td>
                                        <td class='text-center'>DD/MM/AAAA</td>
                                        <td>senha2</td>
                                        <td>Comercial</td>
                                        <td nowrap>Vendas internas</td>
                                        <td>2</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <th style="color: red;"> * OPÇÕES ABAIXO</th>
                                        <th style="color: red;"> * OPÇÕES ABAIXO</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>nomefuncionario3</td>
                                        <td>funcionario3@suaempresa.com.br</td>
                                        <td class='text-center'>DD/MM/AAAA</td>
                                        <td>senha3</td>
                                        <td>Administrativo</td>
                                        <td>Finanças</td>
                                        <td>3</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <th style="color: red;"> * OPÇÕES ABAIXO</th>
                                        <th style="color: red;"> * OPÇÕES ABAIXO</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class='row'>
                                <div class="col col-sm-6">
                                    <p style="color: red; font-weight: bold;"> * Níveis de acesso:</p>
                                    <ul style="color: red; font-weight: bold;">
                                        <li>Administrador</li>
                                        <li>Cliente</li>
                                        <li>Colaborador CLT</li>
                                        <li>Colaborador MEI</li>
                                        <li>Colaborador PJ</li>
                                        <li>Coordenador</li>
                                        <li>Cuidador Comunitário</li>
                                        <li>Encarregado</li>
                                        <li>Gerente</li>
                                        <li>Gestor</li>
                                        <li>Líder</li>
                                        <li>Multiplicador</li>
                                        <li>Presidente</li>
                                        <li>Representante</li>
                                        <li>Selecionador</li>
                                        <li>Supervisor</li>
                                    </ul>
                                </div>
                                <div class="col col-sm-6">
                                    <p style="color: red; font-weight: bold;"> * Tipos de vínculo:</p>
                                    <ul style="color: red; font-weight: bold;">
                                        <li>CLT</li>
                                        <li>MEI</li>
                                        <li>PJ</li>
                                    </ul>
                                    <br>
                                    <br>
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
        document.title = 'CORPORATE RH - LMS - Importar Funcionários';
    });
</script>

<script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>
