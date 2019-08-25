<?php
require_once APPPATH . 'views/header.php';
?>
<style>
    <?php if ($this->agent->is_mobile()): ?>

    #table, .modal-header, #form {
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
                <ol class="breadcrumb" style="margin-bottom: 5px;">
                    <li class="active">Comportamento/dimensão</li>
                    <button style="float: right;" class="btn btn-default btn-xs" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                </ol>
                <br/>
                <div class="form-group hidden-md hidden-lg">
                    <label class="form-label">Legenda:</label>
                    <p>
                        <button class="btn btn-primary btn-xs" type="button">
                            <i class="glyphicon glyphicon-edit"></i>
                        </button>
                        <small> Avaliar comportamento</small>
                        <button class="btn btn-success btn-xs" type="button">
                            <i class="glyphicon glyphicon-check"></i>
                        </button>
                        <small> Reavaliar comportamento</small>
                    </p>
                    <hr>
                </div>
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Comportamento / dimensao</th>
                        <th class="hidden-xs hidden-sm">Peso</th>
                        <th>Nível</th>
                        <th>Atitude</th>
                        <th class="hidden-xs hidden-sm">Índice</th>
                        <th>Ação</th>
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
                        <h3 class="modal-title">Realizar avaliação</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <input type="hidden" value="<?= $id_avaliador ?>" name="id_avaliador"/>
                            <input type="hidden" value="" name="cargo_dimensao"/>
                            <div class="form-body" style="padding-top: 0;">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 hidden-xs hidden-sm">
                                            <p>Cargo:</p>
                                            <p>Competência:</p>
                                            <p>Comportamento a ser avaliado:</p>
                                        </label>
                                        <div class="col-md-6 hidden-xs hidden-sm">
                                            <p class="form-control-static"><?= $nome_cargo ?></p>
                                            <p class="form-control-static"><?= $nome_competencia ?></p>
                                            <p class="form-control-static" id="nome_dimensao"></p>
                                        </div>
                                        <div class="col-md-3 right" style="float: right;">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">
                                                Salvar
                                            </button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar
                                            </button>
                                        </div>

                                        <div class="col-md-9 hidden-md hidden-lg">
                                            <br>
                                            <p class="form-control-static"><strong>Cargo: </strong><?= $nome_cargo ?>
                                            </p>
                                            <p class="form-control-static">
                                                <strong>Competência: </strong><?= $nome_competencia ?></p>
                                            <p class="form-control-static"><strong>Comportamento a ser
                                                    avaliado: </strong>
                                                <span id="nome_dimensao_m"></span></p>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-primary"><strong>Leia atentamente o comportamento acima. Com base na sua
                                        convivência e conhecimento sobre o seu colega avaliado, selecione o nível de
                                        conhecimento, habilidades e atitudes apresentados pelo mesmo no dia a
                                        dia</strong></p>
                                <hr>
                                <div class="form-group">
                                    <label class="control-label col-md-3">Nível de conhecimento e habilidade
                                        apresentados no dia a dia</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="nivel" name="nivel">
                                            <option value="" selected="selected">selecione...</option>
                                            <option value="0">0 - Nenhum conhecimento</option>
                                            <option value="1">1 - Conhecimento básico</option>
                                            <option value="2">2 - Conhecimento e prática básicos</option>
                                            <option value="3">3 - Conhecimento e prática intermediário</option>
                                            <option value="4">4 - Conhecimento e prática avancados</option>
                                            <option value="5">5 - Especialista e multiplicador</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-3">Nível de atitude, ação e proatividade
                                        apresentados no dia a dia</label>
                                    <div class="col-md-3">
                                        <select class="form-control" id="atitude" name="atitude">
                                            <option value="" selected="selected">selecione...</option>
                                            <?php
                                            $range = range(0, 100, 5);
                                            foreach ($range as $k => $option):
                                                ?>
                                                <option value="<?= $option; ?>"><?= $option; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <br>
                                        <div class="alert alert-info">
                                            <p>
                                                0% = Nunca; <br>
                                                20% = Raramente; <br>
                                                40% = Poucas vezes; <br>
                                                60% = Com frequência; <br>
                                                80% = Muitas vezes; <br>
                                                100% = Todas as vezes.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        document.title = 'CORPORATE RH - LMS - Avaliações - Comportamento/dimensão';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var table;
    var is_mobile = <?= $this->agent->is_mobile() ? 'true' : 'false'; ?>;

    $(document).ready(function () {

        //datatables
        table = $('#table').DataTable({
            'info': false,
            'processing': true, //Feature control the processing indicator.
            'serverSide': true, //Feature control DataTables' server-side processing mode.
            'order': [], //Initial no order.
            'iDisplayLength': -1,
            'lengthMenu': [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
            'lengthChange': (is_mobile === false),
            'searching': (is_mobile === false),
            'language': {
                'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
            },
            // Load data for the table's content from an Ajax source
            'ajax': {
                'url': '<?php echo site_url('competencias/avaliador/ajax_dimensao/' . $id_avaliador . '/' . $id_competencia) ?>',
                'type': 'POST'
            },
            //Set column definition initialisation properties.
            'columnDefs': [
                {
                    'width': (is_mobile === false ? '100%' : 'auto'),
                    'targets': [0]
                },
                {
                    'visible': (is_mobile === false),
                    'targets': [1, 4]
                },
                {
                    'className': 'text-right',
                    'orderable': (is_mobile === false),
                    'cellType': 'td',
                    'targets': [1, 2, 3, 4]
                },
                {
                    'mRender': function (data) {
                        if (data === null) {
                            data = '<span class='
                            text - muted
                            '>0</span>';
                        }
                        return data;
                    },
                    'targets': [2, 3, 4]
                },
                {
                    'className': 'text-nowrap',
                    'targets': [-1], //last column
                    'orderable': false, //set not orderable
                    'searchable': false //set not orderable
                }
            ]

        });

        //datepicker
        $('.datepicker').datepicker({
            'autoclose': true,
            'format': 'yyyy-mm-dd',
            'todayHighlight': true,
            'orientation': 'top auto',
            'todayBtn': true
        });

    });

    function edit_avaliadorDimensao(cargo_dimensao, id = null) {
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            'url': "<?php echo site_url('competencias/avaliador/ajax_edit') ?>",
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'cargo_dimensao': cargo_dimensao,
                'id': id
            },
            'success': function (json) {
                $('[name="id"]').val(json.id);
                $('[name="cargo_dimensao"]').val(json.cargo_dimensao);
                $('[name="nivel"]').val(json.nivel);
                $('[name="atitude"]').val(json.atitude);
                $('#nome_dimensao, #nome_dimensao_m').html(data.nome);
                $('#modal_form').modal('show');
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }


    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }


    function save() {
        $.ajax({
            'url': '<?php echo site_url('competencias/avaliador/ajax_save') ?>',
            'type': 'POST',
            'data': $('#form').serialize(),
            'dataType': 'json',
            'beforeSend': function () {
                $('#btnSave').text('Salvando...').attr('disabled', true);
            },
            'success': function (json) {
                if (json.status) {
                    $('#modal_form').modal('hide');
                    reload_table();
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error adding / update data');
            },
            'complete': function () {
                $('#btnSave').text('Salvar').attr('disabled', false);
            }
        });
    }

    function delete_avaliadorDimensao(id) {
        //Ajax Load data from ajax
        $.ajax({
            'url': '<?php echo site_url('competencias/avaliador/ajax_delete') ?>',
            'type': 'POST',
            'dataType': 'json',
            'data': {
                'id': id
            },
            'success': function (json) {
                if (json.status) {
                    reload_table();
                } else {
                    alert('Não foi possível excluir o arquivo');
                }
            },
            'error': function (jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

</script>

<?php
require_once APPPATH . 'views/end_html.php';
?>
