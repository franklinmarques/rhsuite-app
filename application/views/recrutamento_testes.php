<?php
require_once "header.php";
?>
    <style>
        <?php if ($this->agent->is_mobile()): ?>

        #table {
            font-size: x-small;
        }

        <?php endif; ?>

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
                        <li class="active"><?= !empty($nome_teste) ? 'Testes de ' . $nome_teste : 'Processo Seletivo' ?></li>
                        <button style="float: right;" class="btn btn-default btn-xs"
                                onclick="javascript:history.back()"><i
                                    class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                        </button>
                    </ol>
                    <h4 class="text-danger"><strong>Para participar do processo seletivo, realize os testes abaixo
                            apresentados utilizando o botão "Realizar teste".</strong></h4>
                    <br/>
                    <br/>
                    <!--<p class="bg-info text-info" style="padding: 5px;">
                        <strong>Instruções:</strong> para reslizar o(s) teste(s) seletivo(s) abaixo, selecione o botão que se encontra no campo "Status/ação".
                    </p>-->
                    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Ação</th>
                            <th class="hidden-xs hidden-sm">Cargo da vaga</th>
                            <th>Função</th>
                            <th class="hidden-xs hidden-sm">Teste</th>
                            <th>Início</th>
                            <th>Término</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- page end-->

            <!-- Bootstrap modal -->
            <div class="modal fade center" id="modal" role="dialog">
                <div class="modal-dialog" style="max-width: 95%;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Atenção</h3>
                        </div>
                        <div class="modal-body">
                            <p id="em_execucao" class="text-danger" style="text-indent: 20px;">
                                ESTE TESTE JÁ ESTÁ EM EXECUÇÃO!
                            </p>
                            <p style="font-size: 15px; text-indent: 20px;">
                                O tempo estimado para este teste é <span id="tempo_duracao"></span>.
                                Você terá apenas <strong>1 (uma) tentativa</strong> para a realização do mesmo.
                                Após concluí-lo, não será possível acessá-lo novamente.
                            </p>
                            <br>
                            <!--<p style="text-indent: 20px;">
                                Em caso de problemas, entre em contato com o administrador da plataforma.
                            </p>-->
                            <form action="#" id="form" class="form-horizontal">
                                <input type="hidden" value="" name="id_teste"/>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" id="btnIniciar_Teste" onclick="iniciar_teste()"
                                    class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i> Iniciar teste
                            </button>
                            <button type="button" id="btnContinuar_Teste" onclick="iniciar_teste()"
                                    class="btn btn-success"><i class="glyphicon glyphicon-pencil"></i> Continuar teste
                            </button>

                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </section>
    </section>
    <!--main content end-->

<?php
require_once "end_js.php";
?>
    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Testes de <?= $nome_teste ?>';
        });</script>
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

    <script>

        var table;
        var is_mobile = <?= $this->agent->is_mobile() ? 'true' : 'false'; ?>;
        var id_teste = '';
        var tipo = 1;
        var modeloTeste = '';

        $(document).ready(function () {

            //datatables
            table = $('#table').DataTable({
                "info": false,
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "lengthChange": false,
                "searching": false,
                "language": {
                    "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
                },
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('recrutamento_testes/ajax_list/' . $teste) ?>/",
                    "type": "POST"
                },
                //Set column definition initialisation properties.
                "columnDefs": [
                    {
                        width: '34%',
                        targets: [1]
                    },
                    {
                        width: '33%',
                        targets: [2, 3]
                    },
                    {
                        className: 'text-center',
                        orderable: (is_mobile === false),
                        targets: [-1, -2]
                    },
                    {
                        visible: (is_mobile === false),
                        "targets": [1, 3]
                    },
                    {
                        orderable: false,
                        searchable: false,
                        targets: [0]
                    }
                ]
            });

        });

        function verificar_teste(id) {
            $.ajax({
                url: "<?php echo site_url('recrutamento_testes/verificar_teste') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    id_teste = id;
                    tipo = 1;
                    if (data.data_acesso === null) {
                        $('#em_execucao, #btnContinuar_Teste').hide();
                        $('#btnIniciar_Teste').show();
                    } else {
                        $('#em_execucao, #btnContinuar_Teste').show();
                        $('#btnIniciar_Teste').hide();
                    }
                    if (data.tempo_duracao.length > 0) {
                        $('#tempo_duracao').html('de <strong>' + data.tempo_duracao + ' minutos</strong>');
                    } else {
                        $('#tempo_duracao').html('até o dia <strong>' + data.data_termino + '</strong>');
                    }
                    $('#modal').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error getting data');
                }
            });
        }

        function verificar_teste2(id) {
            $.ajax({
                url: "<?php echo site_url('recrutamentoPresencial_testes/verificar_teste') ?>/" + id,
                type: "POST",
                dataType: "JSON",
                success: function (data) {
                    id_teste = id;
                    tipo = 2;
                    modeloTeste = data.tipo;
                    switch (data.tipo) {
                        case 'M':
                            modeloTeste = 'matematica';
                            break;
                        case 'R':
                            modeloTeste = 'raciocinio_logico';
                            break;
                        case 'P':
                            modeloTeste = 'portugues';
                            break;
                        case 'L':
                            modeloTeste = 'lideranca';
                            break;
                        case 'C':
                            modeloTeste = 'perfil_personalidade';
                            break;
                        case 'D':
                            modeloTeste = 'digitacao';
                            break;
                        case 'I':
                            modeloTeste = 'interpretacao';
                            break;
                        case 'E':
                            modeloTeste = 'entrevista';
                    }
                    if (data.data_acesso === null) {
                        $('#em_execucao, #btnContinuar_Teste').hide();
                        $('#btnIniciar_Teste').show();
                    } else {
                        $('#em_execucao, #btnContinuar_Teste').show();
                        $('#btnIniciar_Teste').hide();
                    }
                    if (data.tempo_duracao.length > 0) {
                        $('#tempo_duracao').html('de <strong>' + data.tempo_duracao + ' minutos</strong>');
                    } else {
                        $('#tempo_duracao').html('até o dia <strong>' + data.data_termino + '</strong>');
                    }
                    $('#modal').modal('show');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error getting data');
                }
            });
        }

        function iniciar_teste() {
            setTimeout(function () {
                $('#modal').modal('hide');
                table.ajax.reload(null, false);
            }, 1);
            if (tipo === 2) {
                window.open("<?php echo site_url('recrutamentoPresencial_testes'); ?>/" + modeloTeste + '/' + id_teste, '_blank');
            } else {
                window.open("<?php echo site_url('recrutamento_testes'); ?>/" + modeloTeste + '/' + id_teste, '_blank');
            }
        }


    </script>

<?php
require_once "end_html.php";
?>