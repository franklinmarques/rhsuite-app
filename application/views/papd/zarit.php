<?php require_once APPPATH . 'views/header.php'; ?>

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
                        <li><a href="<?= site_url('papd/pacientes'); ?>">Gerenciar pacientes</a></li>
                        <li class="active">Avaliação da Sobrecarga dos Cuidadores (ZARIT)<?= $nomePaciente; ?></li>
                    </ol>
                    <div class="row form-inline">
                        <div class="col-sm-5 col-md-9">
                            <button class="btn btn-info" onclick="add_zarit()"><i class="glyphicon glyphicon-plus"></i>
                                Cadastrar nova avaliação
                            </button>
                            <button class="btn btn-default" onclick="javascript:history.back()"><i
                                        class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                            </button>
                        </div>
                        <div class="col-sm-7 col-md-3 right">
                            <label class="visible-xs"></label>
                            <p class="bg-info text-info" id="alerta" style="padding: 5px;">
                                <small><strong>* Leve:</strong> de 0 a 14 pontos;</small>
                                <br>
                                <small><strong>* Moderada:</strong> de 15 a 21 pontos;</small>
                                <br>
                                <small><strong>* Grave:</strong> a partir de 22 pontos.</small>
                            </p>
                        </div>
                    </div>
                    <table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th nowrap>Data avaliação</th>
                            <th>Avaliador</th>
                            <th>ZARIT</th>
                            <th nowrap>Sobrecarga <span class="text-info">*</span></th>
                            <th>Observações</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade" id="modal_form" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Gerenciar avaliação ZARIT</h3>
                        </div>
                        <div class="modal-body form">
                            <div id="alert"></div>
                            <form action="#" id="form" class="form-horizontal" autocomplete="off">
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="zarit" value="">
                                <?php if ($idPaciente): ?>
                                    <input type="hidden" name="id_paciente" value="<?= $idPaciente; ?>">
                                <?php endif; ?>
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Data de avaliação</label>
                                        <div class="col-md-2">
                                            <input name="data_avaliacao" type="text"
                                                   class="form-control text-center date" placeholder="dd/mm/aaaa">
                                        </div>
                                        <div class="col-md-8 text-right">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-success">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                    <?php if (empty($idPaciente)): ?>
                                        <div class="form-group">
                                            <label class="control-label col-md-2">Paciente</label>
                                            <div class="col-md-10">
                                                <?php echo form_dropdown('id_paciente', $pacientes, '', 'class="form-control"'); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Avaliador</label>
                                        <div class="col-md-10">
                                            <input name="avaliador" type="text" class="form-control"
                                                   placeholder="Digite o nome do avaliador">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Pessoa pesquisada</label>
                                        <div class="col-md-10">
                                            <input name="pessoa_pesquisada" type="text" class="form-control"
                                                   placeholder="Digite o nome da pessoa pesquisada">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-2">Observações</label>
                                        <div class="col-md-10">
                                            <textarea name="observacoes" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-bordered table-condensed" width="100%">
                                    <thead>
                                    <tr class="active">
                                        <th colspan="2" class="text-center">Esta pesquisa visa levantar o nível de
                                            estresse nos
                                            cuidadores.
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Providências com achados/resultados:</strong> altos
                                            escores indicam estresse dos cuidadores e, nesses casos, a equipe deve
                                            discutir o planejamento da intervenção mais adequado, incluindo tais
                                            propostas no Projeto Terapêutico Singular. Após intervenção, reavaliar,
                                            utilizando o mesmo instrumento e considerar as modificações encontradas.
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <th>Perguntas</th>
                                        <th class="text-center">Respostas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="active">
                                        <td colspan="2">
                                            <strong>Legenda: 0 - </strong> Nunca;
                                            <strong>1 - </strong> Raramente;
                                            <strong>2 - </strong> Algumas vezes;
                                            <strong>3 - </strong> Frequentemente;
                                            <strong>4 - </strong> Sempre.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O (a) Sr(a) sente que a pessoa cuidada pede mais ajuda do que
                                            necessita?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="assistencia_excessiva" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="assistencia_excessiva" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="assistencia_excessiva" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="assistencia_excessiva" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="assistencia_excessiva" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que por causa do tempo que gasta com a pessoa,
                                            não tem tempo suficiente para si mesmo?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="tempo_desperdicado" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="tempo_desperdicado" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="tempo_desperdicado" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="tempo_desperdicado" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="tempo_desperdicado" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O (a) Sr(a) se sente estressado (a) entre cuidar da pessoa e
                                            suas outras responsabilidades com a família e o trabalho?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="estresse_cotidiano" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="estresse_cotidiano" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="estresse_cotidiano" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="estresse_cotidiano" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="estresse_cotidiano" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente envergonhado(a) com o comportamento da
                                            pessoa?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="constrangimento_alheio" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="constrangimento_alheio" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="constrangimento_alheio" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="constrangimento_alheio" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="constrangimento_alheio" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que a pessoa afeta negativamente seus
                                            relacionamentos com outros membros da família ou amigos?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="influencia_negativa" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="influencia_negativa" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="influencia_negativa" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="influencia_negativa" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="influencia_negativa" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente receio pelo futuro da pessoa?</td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="futuro_receoso" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="futuro_receoso" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="futuro_receoso" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="futuro_receoso" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="futuro_receoso" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que a pessoa depende do(a) Sr(a)?</td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="dependencia" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="dependencia" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="dependencia" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="dependencia" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="dependencia" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que sua saúde foi afetada por causa do seu
                                            envolvimento com a pessoa?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="impacto_saude" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="impacto_saude" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="impacto_saude" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="impacto_saude" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="impacto_saude" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que NÃO tem tanta privacidade como gostaria
                                            por causa da pessoa?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="perda_privacidade" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="perda_privacidade" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="perda_privacidade" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="perda_privacidade" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="perda_privacidade" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que sua vida social tem sido prejudicada em
                                            razão de ter de cuidar da pessoa?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="perda_vida_social" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="perda_vida_social" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="perda_vida_social" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="perda_vida_social" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="perda_vida_social" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que a pessoa espera que o(a) Sr(a) cuide
                                            dele(a) como se fosse a única pessoa de quem ele(a) pode depender?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="dependencia_exclusiva" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="dependencia_exclusiva" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="dependencia_exclusiva" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="dependencia_exclusiva" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="dependencia_exclusiva" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que será incapaz de cuidar da pessoa por muito
                                            mais tempo?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="tempo_desgaste" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="tempo_desgaste" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="tempo_desgaste" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="tempo_desgaste" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="tempo_desgaste" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que perdeu o controle de sua vida desde o
                                            nascimento/deficiência da pessoa?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="perda_controle" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="perda_controle" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="perda_controle" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="perda_controle" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="perda_controle" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) se sente em dúvida sobre o que fazer pela pessoa?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="duvida_prestatividade" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="duvida_prestatividade" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="duvida_prestatividade" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="duvida_prestatividade" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="duvida_prestatividade" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">O(a) Sr(a) sente que poderia cuidar melhor da pessoa?</td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="expectativa_qualidade" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="expectativa_qualidade" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="expectativa_qualidade" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="expectativa_qualidade" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="expectativa_qualidade" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="100%">De uma maneira geral, quanto o (a) Sr(a) se sente
                                            sobrecarregado por cuidar da pessoa?
                                        </td>
                                        <td nowrap="">
                                            <label class="radio-inline" title="Nunca">
                                                <input type="radio" name="sobrecarga" value="0"> 0
                                            </label>
                                            <label class="radio-inline" title="Raramente">
                                                <input type="radio" name="sobrecarga" value="1"> 1
                                            </label>
                                            <label class="radio-inline" title="Algumas vezes">
                                                <input type="radio" name="sobrecarga" value="2"> 2
                                            </label>
                                            <label class="radio-inline" title="Frequentemente">
                                                <input type="radio" name="sobrecarga" value="3"> 3
                                            </label>
                                            <label class="radio-inline" title="Sempre">
                                                <input type="radio" name="sobrecarga" value="4"> 4
                                            </label>
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td><strong>Escore total</strong></td>
                                        <td><span id="zarit"></span></td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-center">
                                            <small><i>Retirado de Brasil. Ministério da Saúde. Secretaria de Atenção
                                                    Básica. Envelhecimento e saúde da pessoa idosa - Brasília, 2006.<br>
                                                    Adaptação de Zarit SH, Reever KE, Bach-Peterson J. Relatives of the
                                                    impaired elderly correlates of feelings of burden. Gerontologist
                                                    1980;20:649-55</i>
                                            </small>
                                        </td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <!-- End Bootstrap modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once APPPATH . 'views/end_js.php';
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar avaliação ZARIT';
        });
    </script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

    <script>

        var save_method; //for save method string
        var table;

        $(document).ready(function () {

            $('.date').mask('00/00/0000');

            //datatables
            table = $('#table').DataTable({
                'dom': "<'row'<'.col-sm-3'l><'#status.col-sm-2'><'#tipo.col-sm-4'><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                'processing': true, //Feature control the processing indicator.
                'serverSide': true, //Feature control DataTables' server-side processing mode.
                'iDisplayLength': -1,
                'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                // Load data for the table's content from an Ajax source
                'ajax': {
                    'url': '<?php echo site_url('papd/zarit/ajaxList/') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.id_paciente = '<?= $idPaciente; ?>';
                        return d;
                    }
                },
                //Set column definition initialisation properties.
                'columnDefs': [
                    {
                        'width': '50%',
                        'targets': [1, 4]
                    },
                    {
                        'className': 'text-center',
                        'targets': [0, 2, 3]
                    },
                    {
                        'className': 'text-nowrap',
                        'orderable': false,
                        'searchable': false,
                        'targets': [-1]
                    }
                ]
            });

        });

        $('#form table input[type="radio"]').on('change', function () {
            var zarit = null;

            $.each($('#form table input[type="radio"]:checked'), function (i, elem) {
                zarit = zarit + parseInt(elem.value);
            });

            $('#form input[name="zarit"]').val(zarit);
            $('#zarit').text(zarit);
        });


        function add_zarit() {
            save_method = 'add';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#zarit').text('');

            $('#modal_form').modal('show');
            $('.modal-title').text('Cadastrar nova avaliação'); // Set title to Bootstrap modal title
            $('.combo_nivel1').hide();
        }

        function edit_zarit(id) {
            save_method = 'update';
            $('#form')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string

            //Ajax Load data from ajax
            $.ajax({
                'url': '<?php echo site_url('papd/zarit/ajaxEdit') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': {
                    'id': id
                },
                'success': function (json) {
                    $.each(json, function (key, value) {
                        if ($('#form [name="' + key + '"]').prop('type') === 'radio') {
                            $('#form [name="' + key + '"][value="' + value + '"]').prop('checked', value !== null);
                        } else {
                            $('#form [name="' + key + '"]').val(value);
                        }
                    });
                    $('#zarit').text(json.zarit);

                    $('#modal_form').modal('show');
                    $('.modal-title').text('Editar avaliação'); // Set title to Bootstrap modal title
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false); //reload datatable ajax
        }


        function save() {
            $('#btnSave').text('Salvando...'); //change button text
            $('#btnSave').attr('disabled', true); //set button disable
            var url;

            if (save_method === 'add') {
                url = "<?php echo site_url('papd/zarit/ajaxAdd') ?>";
            } else {
                url = "<?php echo site_url('papd/zarit/ajaxUpdate') ?>";
            }

            // ajax adding data to database
            $.ajax({
                'url': url,
                'type': 'POST',
                'data': $('#form').serialize(),
                'dataType': 'json',
                'success': function (json) {
                    if (json.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    } else if (json.erro) {
                        alert(json.erro);
                    }
                },
                'complete': function () {
                    $('#btnSave').text('Salvar'); //change button text
                    $('#btnSave').attr('disabled', false); //set button enable
                }
            });
        }


        function delete_zarit(id) {
            if (confirm('Deseja remover?')) {
                $.ajax({
                    'url': '<?php echo site_url('papd/zarit/ajaxDelete') ?>',
                    'type': 'POST',
                    'dataType': 'json',
                    'data': {'id': id},
                    'success': function () {
                        reload_table();
                    }
                });
            }
        }

    </script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
