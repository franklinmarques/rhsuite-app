<?php
require_once APPPATH . "views/header.php";
?>
<style>
    .btn-success{
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
    .text-nowrap{
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
                    <li><a href="<?= site_url("competencias/cargos/") ?>">Cargo-função: </a><?= $options_cargos['nome_cargo'] ?></li>
                    <li>
                        <?php if ($options_cargos['tipo'] === 'T'): ?>
                            <a href="<?= site_url("competencias/tipo/tecnica/" . $options_cargos['id_cargo']) ?>">Competência técnica: </a>
                        <?php elseif ($options_cargos['tipo'] === 'C'): ?>
                            <a href="<?= site_url("competencias/tipo/comportamental/" . $options_cargos['id_cargo']) ?>">Competência comportamental: </a>
                        <?php else: ?>
                            <a href="<?= site_url("competencias/tipo/index/" . $options_cargos['id_cargo']) ?>">Competência: </a>
                        <?php endif; ?>
                        <?= $options_cargos['nome_competencia'] ?>
                    </li>
                    <li class="active">Comportamento/dimensão</li>
                </ol>
                <div class="row form-inline">
                    <div class="col-sm-6 col-md-5">
                        <button class="btn btn-success" onclick="add_dimensao()"><i class="glyphicon glyphicon-plus"></i> Adicionar comportamento/dimensão</button>
                        <button class="btn btn-default" onclick="javascript:history.back()"><i class="glyphicon glyphicon-circle-arrow-left"></i> Voltar</button>
                    </div>
                    <div class="col-sm-6 col-md-7 text-danger text-right">
                        <em>* A soma dos pesos deve ser igual a 100 &nbsp;</em>
                        <!--<button class="btn btn-primary" type="button" id="distribuir">Distribuir peso</button>-->
                    </div>
                </div>
                <br />
                <br />
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Comportamento/dimensão</th>
                            <th>Peso <span class="text-danger">*</span></th>
                            <th>Nível</th>
                            <th>Atitude</th>
                            <th>Índice</th>
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
            <div class="modal-dialog" style="width: 990px">
                <div class="modal-content" style="width: 990px">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Funcao Form</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id"/> 
                            <div class="form-body">
                                <div class="form-group">
                                    <input type="hidden" name="cargo_competencia" id="cargo_competencia" value="<?= $options_cargos['cargo_competencia'] ?>">
                                    <label class="control-label col-md-3">Cargo/função:<br>Competência:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static">
                                            <?= $options_cargos['nome_cargo'] ?><br>
                                            <?= $options_cargos['nome_competencia'] ?>
                                        </p>
                                    </div>
                                    <div class="col-md-3 text-right">
                                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>

                                <div class="form-group input_competencia">
                                    <div class="col-md-7">
                                        <div class="row">
                                            <label class="control-label col-md-5">Comportamento observável ou dimensão da competência</label>
                                            <div class="col-md-7">
                                                <textarea class="form-control" rows="4" id="nome" name="nome" placeholder="Digite o comportamento observável" style="max-width: 100%;"></textarea>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-5">Nível mínimo de habilidade e conhecimento demandado pelo cargo/função</label>
                                            <div class="col-md-7">
                                                <select class="form-control" id="nivel" name="nivel">
                                                    <option value="0" selected="selected">0 - Nenhum conhecimento</option>
                                                    <option value="1">1 - Conhecimento básico</option>
                                                    <option value="2">2 - Conhecimento e prática básicos</option>
                                                    <option value="3">3 - Conhecimento e prática intermediário</option>
                                                    <option value="4">4 - Conhecimento e prática avancados</option>
                                                    <option value="5">5 - Especialista e multiplicador</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="control-label col-md-5">Peso do comportamento</label>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control text-right" placeholder="0 - 100" name="peso" id="peso" min="0" max="100"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="row form-group">
                                            <label class="control-label col-md-5">Nível mínimo de atitude exigido pelo cargo/função</label>
                                            <div class="col-md-4">
                                                <div class="input-group">
                                                    <select class="form-control text-right" id="atitude" name="atitude">
                                                        <?php
                                                        $range = range(0, 90, 10);
                                                        foreach ($range as $k => $option):
                                                            ?>
                                                            <option value="<?= $option; ?>"> <?= $option; ?>%</option>
                                                        <?php endforeach; ?>
                                                        <option value="100" selected="selected">100%</option>
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-placement="right" 
                                                                data-content="&nbsp;&nbsp;&nbsp;&nbsp;0% = Nunca; <br>
                                                                &nbsp;&nbsp;20% = Raramente; <br>
                                                                &nbsp;&nbsp;40% = Poucas vezes; <br>
                                                                &nbsp;&nbsp;60% = Com frequência; <br>
                                                                &nbsp;&nbsp;80% = Muitas vezes; <br>
                                                                100% = Todas as vezes." data-trigger="focus" data-html="true">
                                                            <span class="glyphicon glyphicon-question-sign"></span>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">							
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Biblioteca de comportamentos</div>
                                            <div class="panel-body">
                                                <div style="overflow-x: hidden;overflow-y: scroll; height: 210px;">
                                                    <input type="hidden" name="id_dimensao" id="id_dimensao">
                                                    <ul id="sugestao" class="list-group">
                                                        <?php foreach ($sugestoes as $k => $sugestao): ?>
                                                            <li id="<?= $sugestao->id ?>" class="sugestao_competencia list-group-item"><?= $sugestao->nome ?></li>
                                                        <?php endforeach; ?>	
                                                    </ul>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--                    <div class="modal-footer">
                                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Salvar</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                        </div>-->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->

    </section>
</section>
<!--main content end-->

<?php
require_once APPPATH . "views/end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Comportamento/dimensão';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

<script>

    var save_method; //for save method string
    var table;

    $(document).ready(function () {
        $('[data-toggle="popover"]').popover();

        $('.sugestao_competencia').click(function () {
            $("#nome").val($(this).text());
            $("#id_dimensao").val($(this).attr('id'));
        });
        //datatables
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "iDisplayLength": 25,
            "order": [], //Initial no order.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('competencias/dimensao/ajax_list/' . $this->uri->rsegment(3)) ?>",
                "type": "POST",
                timeout: 9000
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    width: '100%',
                    targets: [0]
                },
                {
                    className: "text-right text-nowrap",
                    cellType: 'td',
                    "targets": [1, 2, 3, 4]
                },
                {
                    className: "text-nowrap",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false //set not orderable
                }
            ]

        });

        //datepicker
        $('.datepicker').datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
            orientation: "top auto",
            todayBtn: true
        });

    });

    function add_dimensao()
    {
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('[name="id"], [name="id_dimensao"]').val('');
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.selectbox_nivel2').hide();
        $('.modal-title').text('Adicionar comportamento/dimensão'); // Set Title to Bootstrap modal title
    }

    function edit_dimensao(id)
    {
        save_method = 'update';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('competencias/dimensao/ajax_edit') ?>",
            type: "POST",
            dataType: "JSON",
            timeout: 9000,
            data: {
                id: id
            },
            success: function (data)
            {

                $('[name="id"]').val(data.id);
                $('[name="nome"]').val(data.nome);
                $('[name="nivel"]').val(data.nivel);
                $('[name="peso"]').val(data.peso);
                $('[name="atitude"]').val(data.atitude);
                $('[name="id_dimensao"]').val(data.id_dimensao);

                $('#modal_form').modal('show');
                $('.modal-title').text('Editar comportamento/dimensão'); // Set title to Bootstrap modal title
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });

    }

    function reload_table()
    {
        table.ajax.reload(null, false); //reload datatable ajax 
    }


    function save()
    {
        $('#btnSave').text('Salvando...'); //change button text
        $('#btnSave').attr('disabled', true); //set button disable 
        var url;

        if (save_method === 'add') {
            url = "<?php echo site_url('competencias/dimensao/ajax_add') ?>";
        } else {
            url = "<?php echo site_url('competencias/dimensao/ajax_update') ?>";
        }

        // ajax adding data to database
        $.ajax({
            url: url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            timeout: 9000,
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                }

                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 


            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave').attr('disabled', false); //set button enable 

            }
        });
    }


    function delete_dimensao(id)
    {
        if (confirm('Deseja Remover?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('competencias/dimensao/ajax_delete') ?>",
                type: "POST",
                dataType: "JSON",
                timeout: 9000,
                data: {
                    id: id
                },
                success: function (data)
                {
                    //if success reload ajax table
                    $('#modal_form').modal('hide');
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });

        }
    }

</script>

<?php
require_once APPPATH . "views/end_html.php";
?>
