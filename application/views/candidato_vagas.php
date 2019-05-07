<?php require_once 'header.php'; ?>

<section id="main-content" class="merge-left">
    <section class="wrapper">

        <!-- page start-->
        <div class="row">
            <div class="col-md-12">
                <div id="alert"></div>
                <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                    <li class="active">Banco de vagas em aberto</li>
                </ol>
                <div class="page-header">
                    <h3>Caro candidato, seja bem-vindo ao nosso painel de vagas!</h3>
                </div>
                <h4>Caso algumas das vagas seja de seu interesse, basta acionar o botão "Candidatar-se!" que você será
                    automaticamente incluído no processo seletivo da mesma.</h4>
                <br>
                <br>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th nowrap>Código vaga</th>
                        <th>Abertura</th>
                        <th>Cargo/Função</th>
                        <th>Qtde.</th>
                        <th>Cidade</th>
                        <th>Bairro</th>
                        <th>Status</th>
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
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Formulario de evento de apontamento</h3>
                    </div>
                    <div class="modal-body form">
                        <div id="alert"></div>
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="<?= $empresa; ?>" name="id_empresa"/>
                            <input type="hidden" value="" name="id"/>
                            <div class="form-body">
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Código evento</label>
                                    <div class="col-md-5">
                                        <input name="codigo" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-md-3">Nome evento</label>
                                    <div class="col-md-9">
                                        <input name="nome" placeholder="Digite o nome do evento" class="form-control"
                                               type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_vaga" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Detalhes da vaga</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" class="form-horizontal">
                            <div class="row">
                                <label class="control-label col-md-3">Código da vaga</label>
                                <div class="col-md-8">
                                    <p id="codigo" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Cargo/Função</label>
                                <div class="col-md-8">
                                    <p id="cargo_funcao" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Perfil profissional desejado</label>
                                <div class="col-md-8">
                                    <p id="perfil_profissional_desejado" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Data de abertura</label>
                                <div class="col-md-8">
                                    <p id="data_abertura" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Quantidade de vagas</label>
                                <div class="col-md-8">
                                    <p id="quantidade" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Cidade da vaga</label>
                                <div class="col-md-8">
                                    <p id="cidade_vaga" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Bairro da vaga</label>
                                <div class="col-md-8">
                                    <p id="bairro_vaga" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Tipo de vínculo</label>
                                <div class="col-md-8">
                                    <p id="tipo_vinculo" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Remuneração (R$)</label>
                                <div class="col-md-8">
                                    <p id="remuneracao" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Benefícios</label>
                                <div class="col-md-8">
                                    <p id="beneficios" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Horário de trabalho</label>
                                <div class="col-md-8">
                                    <p id="horario_trabalho" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Formação mínima</label>
                                <div class="col-md-8">
                                    <p id="formacao_minima" class="form-control-static"></p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-md-3">Contato do selecionador</label>
                                <div class="col-md-8">
                                    <p id="contato_selecionador" class="form-control-static"></p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="modal_candidato" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Candidatar-se a uma vaga</h3>
                    </div>
                    <div class="modal-body form">
                        <p>Para candidatar-se a uma vaga, é necessário cadastrar seu currículo e fazer login no
                            sistema.</p>
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <h4>Já sou cadastrado</h4>
                                <a type="button" class="btn btn-primary btn-block" href="login">Entrar no portal</a>
                            </div>
                            <div class="col-md-6 text-center">
                                <h4>Quero me candidatar</h4>
                                <a type="button" class="btn btn-primary btn-block" href="#" id="url_cadastro">Cadastrar
                                    currículo</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </section>
</section>
<!--main content end-->

<?php require_once 'end_js.php'; ?>

<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">
<!--script for this page-->

<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

<script>

    var table;

    $(document).ready(function () {

        table = $('#table').DataTable({
            'processing': true,
            'serverSide': true,
            'lengthChange': false,
            'searching': false,
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            'ajax': {
                'url': '<?php echo site_url('candidatoVagas/ajaxList/') ?>',
                'type': 'POST'
            },
            'columnDefs': [
                {
                    'className': 'text-center',
                    'targets': [1, 3]
                },
                {
                    'width': '34%',
                    'targets': [2]
                },
                {
                    'width': '33%',
                    'targets': [4, 5]
                },
                {
                    'mRender': function (data) {
                        switch (data) {
                            case 'Não admitido':
                                data = '<span class="text-danger">' + data + '</span>';
                                break;
                            case 'Cadastrado':
                                data = '<span class="text-success">' + data + '</span>';
                                break;
                            case 'Admitido':
                                data = '<span class="text-info">' + data + '</span>';
                                break;
                            default:
                                data = '<span class="text-muted">Pendente</span>';
                        }

                        return data;
                    },
                    'className': 'text-center',
                    'orderable': false,
                    'searchable': false,
                    'targets': [6]
                },
                {
                    'className': 'text-center text-nowrap',
                    'orderable': false,
                    'searchable': false,
                    'targets': [-1]
                }
            ]
        });

    });


    function reload_table() {
        table.ajax.reload(null, false);
    }


    function visualizar_vaga(codigo) {
        $.ajax({
            'url': '<?php echo site_url('vagas/visualizarDetalhes/') ?>',
            'type': 'GET',
            'dataType': 'json',
            'data': {'codigo': codigo},
            'success': function (json) {

                $.each(json, function (key, value) {
                    $('#' + key).html(value);
                });

                $('#modal_vaga').modal('show');

            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function candidatar(codigo_vaga) {
        $.ajax({
            'url': '<?php echo site_url('candidatoVagas/candidatar') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {'codigo_vaga': codigo_vaga},
            'success': function () {
                reload_table();
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function descandidatar(id) {
        $.ajax({
            'url': '<?php echo site_url('candidatoVagas/descandidatar') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {'id': id},
            'success': function () {
                reload_table();
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


</script>

<?php require_once 'end_html.php'; ?>
