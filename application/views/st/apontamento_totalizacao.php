<?php
require_once "header.php";
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
                <ol class="breadcrumb" style="margin-bottom: 5px; background-color: #eee;">
                    <li class="active">Gestão Operacional ST - Totalização Mensal</li>
                </ol>
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-success" onclick="add_mes()"><i class="glyphicon glyphicon-plus"></i> Novo mês</button>
                        <!--<button class="btn btn-danger" onclick="excluir_mes()"><i class="glyphicon glyphicon-trash"></i> Excluir mês</button>-->
                        <button class="btn btn-success" onclick="add_contrato()"><i class="glyphicon glyphicon-plus"></i> Cadastrar contrato</button>
                        <button class="btn btn-success" onclick="add_posto()"><i class="glyphicon glyphicon-plus"></i> Cadastrar posto</button>
                    </div>
                    <div class="col-md-6 right">
                        <label class="visible-xs"></label>
                        <p class="bg-info text-info" style="padding: 5px;">
                            <small>&emsp;<strong>Departamento:</strong> <span id="alerta_depto"><?= empty($depto_atual) ? 'Todos' : $depto_atual ?></span></small><br>
                            <small>&emsp;<strong>Área:</strong> <span id="alerta_area"><?= empty($area_atual) ? 'Todas' : $area_atual ?></span></small><br>
                            <small>&emsp;<strong>Setor:</strong> <span id="alerta_setor"><?= empty($setor_atual) ? 'Todos' : $setor_atual ?></span></small>
                        </p>
                    </div>
                </div>
                <div class="panel panel-default">
                    <!-- Default panel contents -->
                    <div class="panel-heading">
                        <span id="mes_ano"><?= $mes . ' ' . date('Y') ?></span>                            
                        <div style="float:right; margin-top: -0.5%;">
                            <button class="btn btn-primary btn-sm" onclick="proximo_mes(-1)">
                                <i class="glyphicon glyphicon-arrow-left"></i> Mês anterior
                            </button>
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal_filtro">
                                <i class="glyphicon glyphicon-search"></i> Pesquisa avançada
                            </button>
                            <button id="mes_seguinte" class="btn btn-primary btn-sm" onclick="proximo_mes(1)">
                                Mês seguinte <i class="glyphicon glyphicon-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                    <table id="table" class="table table-hover table-bordered" cellspacing="0" width="100%" style="border-radius: 0 !important;">
                        <thead>
                            <tr>
                                <th rowspan="2" class="warning" style="vertical-align: middle;">Colaborador(a)</th>
                                <th colspan="2" class="warning text-center" style="padding-left: 4px; padding-right: 4px;">Faltas/atrasos</th>
                                <th colspan="4" class="warning text-center" style="padding-left: 4px; padding-right: 4px;">Valores (R$)</th>
                            </tr>
                            <tr>
                                <th class="warning text-center">Dias</th>
                                <th class="warning text-center">Horas</th>
                                <th class="warning text-center">Posto</th>
                                <th class="warning text-center">Conversor dia</th>
                                <th class="warning text-center">Conversor hora</th>
                                <th class="warning text-center">Total devido</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- page end-->

        <!-- Bootstrap modal -->        
        <div class="modal fade" id="modal_filtro" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Pesquisa avançada</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por departamento</label>
                                    <?php echo form_dropdown('depto', $depto, $depto_atual, 'onchange="atualizarFiltro()" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por área</label>
                                    <?php echo form_dropdown('area', $area, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por setor</label>
                                    <?php echo form_dropdown('setor', $setor, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por cargo</label>
                                    <?php echo form_dropdown('cargo', $cargo, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Filtrar por função</label>
                                    <?php echo form_dropdown('funcao', $funcao, '', 'onchange="atualizarFiltro();" class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Mês</label>
                                    <?php echo form_dropdown('mes', $meses, date('m'), 'class="form-control input-sm"'); ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">Ano</label>
                                    <input name="ano" type="number" value="<?= date('Y') ?>" size="4" class="form-control input-sm" placeholder="aaaa">
                                </div>
                            </div> 
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveFiltro" onclick="filtrar()" class="btn btn-primary" data-dismiss="modal">OK</button>
                        <button type="button" id="limpar" class="btn btn-default">Limpar filtro</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Bootstrap modal -->        
        <div class="modal fade" id="modal_contrato" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Cadastrar contrato</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form_contrato" class="form-horizontal" autocomplete="off">
                            <input type="hidden" value="" name="id"/>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador(a):</label>
                                <div class="col-md-9">
                                    <label class="sr-only" style="margin-top: 7px;"></label>
                                    <p class="form-control-static">
                                        <span id="nome_alocado"></span>
                                    </p>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Período de férias</label>
                                <div class="col-md-9 form-inline">
                                    De <input name="data_ferias" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                    até <input name="data_retorno" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador contrato</label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('id_bck', $contrato, '', 'class="form-control"'); ?>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSaveContrato" onclick="salvar_ferias()" class="btn btn-primary">Salvar</button>
                        <button type="button" id="btnLimparContrato" onclick="limpar_ferias()" class="btn btn-danger">Limpar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Bootstrap modal -->        
        <div class="modal fade" id="modal_posto" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title">Cadastrar posto</h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form_posto" class="form-horizontal" autocomplete="off">
                            <input type="hidden" value="" name="id"/>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador(a):</label>
                                <div class="col-md-9">
                                    <label class="sr-only" style="margin-top: 7px;"></label>
                                    <p class="form-control-static">
                                        <span id="nome_alocado"></span>
                                    </p>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Período de férias</label>
                                <div class="col-md-9 form-inline">
                                    De <input name="data_ferias" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                    até <input name="data_retorno" placeholder="dd/mm/aaaa" class="form-control text-center" style="width: 150px;" maxlength="10" autocomplete="off" type="text">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="control-label col-md-3">Colaborador posto</label>
                                <div class="col-md-8">
                                    <?php echo form_dropdown('id_bck', $posto, '', 'class="form-control"'); ?>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSavePosto" onclick="salvar_ferias()" class="btn btn-primary">Salvar</button>
                        <button type="button" id="btnLimparPosto" onclick="limpar_ferias()" class="btn btn-danger">Limpar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
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
        document.title = 'CORPORATE RH - LMS - Status - Gestão Operacional';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
<script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>

<script>

    var table;
    var busca;

    $('[name="data_ferias"], [name="data_retorno"]').mask('00/00/0000');
    $('.hora').mask('00:00');

    $(function () {
        $('[data-tooltip="tooltip"]').tooltip();
    });

    $(document).ready(function () {
        busca = $('#busca').serialize();

        //datatables        
        table = $('#table').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "iDisplayLength": 25,
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            "order": [[0, 'asc']],
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('apontamento_totalizacao/ajax_list') ?>",
                "type": "POST",
                timeout: 90000,
                data: function (d) {
                    d.busca = busca;
                    return d;
                },
                "dataSrc": function (json) {
                    $('[name="mes"]').val(json.calendar.mes);
                    $('[name="ano"]').val(json.calendar.ano);
                    $('#mes_ano').html(json.calendar.mes_ano[0].toUpperCase() + json.calendar.mes_ano.slice(1));

                    var dt1 = new Date();
                    var dt2 = new Date();
                    dt2.setFullYear(json.calendar.ano, (json.calendar.mes - 1));

                    if (dt1.getTime() < dt2.getTime()) {
                        $('#mes_seguinte').addClass('disabled').parent().css('cursor', 'not-allowed');
                    } else {
                        $('#mes_seguinte').removeClass('disabled').parent().css('cursor', '');
                    }

                    return json.data;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    className: "warning",
                    "targets": [0]
                },
                {
                    className: "text-center",
                    "searchable": false,
                    "targets": [2] //last column
                },
                {
                    className: "text-right",
                    "searchable": false,
                    "targets": [1, 3, 4, 5] //last column
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).html('<strong>' + rowData[col] + '</strong>');
                    },
                    className: "text-right",
                    "targets": [-1], //last column
                    "searchable": false
                }
            ]
        });

        atualizarColaboradores();
    });

    function atualizarFiltro() {
        $.ajax({
            url: "<?php echo site_url('apontamento/atualizar_filtro/') ?>",
            type: "POST",
            dataType: "JSON",
            data: $('#busca').serialize(),
            success: function (data)
            {
                $('[name="area"]').replaceWith(data.area);
                $('[name="setor"]').replaceWith(data.setor);
                $('[name="cargo"]').replaceWith(data.cargo);
                $('[name="funcao"]').replaceWith(data.funcao);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function atualizarColaboradores()
    {
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_colaboradores/') ?>",
            type: "POST",
            dataType: "JSON",
            data: {
                busca: busca
            },
            success: function (data)
            {
                $('[name="id_bck"]').replaceWith(data.id_bck);
                $('[name="id_alocado_bck"]').replaceWith(data.id_alocado_bck);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function atualizarDetalhes()
    {
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_edit/') ?>",
            type: "POST",
            dataType: "html",
            success: function (data)
            {
                $('#detalhes').replaceWith(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    $('#limpar').on('click', function () {
        $("#busca")[0].reset();
        //atualizarFiltro();
    });

    $('#modal_form').on('show.bs.modal', function (event) {
        var event = $(event.relatedTarget);
        $('[name="dado1"]').val(event.data('status'));
        $('[name="dado2"]').val(event.data('text'));
    });

    $('[name="status"]').on('change', function () {
        selecionar_status($(this).val());
    });

    function sugestao_detalhe(event) {
        $('[name="detalhes"]').val($(event).text());
    }

    function selecionar_status(value) {
        if (value === 'FJ' || value === 'FN' || value === 'FR') {
            $('[name="qtde_dias"]').prop('disabled', false);
            $('.hora').prop('disabled', true);
        } else if (value === 'AJ' || value === 'AN') {
            $('[name="qtde_dias"]').prop('disabled', true);
            $('.hora').prop('disabled', false);
        } else {
            $('[name="qtde_dias"], .hora').prop('disabled', false);
            $('[name="qtde_dias"], .hora').prop('disabled', false);
        }
    }

    function proximo_mes(value = 1) {
        var queryStr_busca = busca.split('&');
        var arr_busca = {};
        $(queryStr_busca).each(function (i) {
            var param = queryStr_busca[i].split('=');
            arr_busca[param[0]] = param[1];
        });

        var dt = new Date(arr_busca.ano, arr_busca.mes - 1);
        dt.setMonth(dt.getMonth() + (value));
        arr_busca.mes = (dt.getMonth() < 9 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1);
        arr_busca.ano = dt.getFullYear();

        busca = $.param(arr_busca);
        reload_table();
        atualizarColaboradores();
    }

    function filtrar() {
        var data_proximo_mes = new Date();
        var data_busca = new Date();

        data_proximo_mes.setDate(1);
        data_proximo_mes.setMonth(data_proximo_mes.getMonth() + 1);
        data_busca.setFullYear($('[name="ano"]').val(), ($('[name="mes"]').val() - 1), 1);
        if (data_proximo_mes.getTime() < data_busca.getTime()) {
            $('[name="mes"]').val(data_proximo_mes.getMonth() + 1);
            $('[name="ano"]').val(data_proximo_mes.getFullYear());
        }

        busca = $('#busca').serialize();
        reload_table();
        $('#alerta_depto').text($('[name="depto"] option:selected').text());
        $('#alerta_area').text($('[name="area"] option:selected').text());
        $('#alerta_setor').text($('[name="setor"] option:selected').text());
    }

    function add_mes() {
        $.ajax({
            url: "<?php echo site_url('apontamento/novo/') ?>",
            type: "POST",
            dataType: "JSON",
            data: busca,
            success: function (data)
            {
                reload_table();
                atualizarColaboradores();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function excluir_mes() {
        if (confirm('Deseja limpar o mês selecionado?')) {

            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_limpar/') ?>",
                type: "POST",
                dataType: "JSON",
                data: busca,
                success: function (data)
                {
                    reload_table();
                    atualizarColaboradores();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
    }

    function edit_backup(id) {
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/ajax_colaboradores/') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (data)
            {
                $('#backup_1').html(data);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }

    function edit_status(id)
    {
        $('#form')[0].reset(); // reset form on modals
        $('#form input[type="hidden"]:not([name="id_avaliado"])').val(''); // reset hidden input form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string

        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('avaliacaoexp_avaliados/edit_status/') ?>",
            type: "POST",
            dataType: "JSON",
            data: {id: id},
            success: function (data)
            {
                $('[name="id"]').val(data.id);
                $('#nome').text(data.nome);
                $('[name="observacoes"]').val(data.observacoes);

                $('#modal_form').modal('show');
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

    function salvar_ferias()
    {
        $('#btnSaveBackup').text('Salvando...'); //change button text
        $('#btnSaveBackup, #btnLimparBackup').attr('disabled', true); //set button disable 

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_ferias') ?>",
            type: "POST",
            data: $('#form_backup').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_backup').modal('hide');
                    reload_table();
                }

                $('#btnSaveBackup').text('Salvar'); //change button text
                $('#btnSaveBackup, #btnLimparBackup').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                if (jqXHR.statusText === 'OK') {
                    alert(jqXHR.responseText);
                } else {
                    alert('Erro ao enviar formulário');
                }

                $('#btnSaveBackup').text('Salvar'); //change button text
                $('#btnSaveBackup, #btnLimparBackup').attr('disabled', false); //set button enable 
            }
        });
    }

    function limpar_ferias()
    {
        if (confirm('Deseja limpar o conteúdo?')) {
            $('#form_backup')[0].reset();
            salvar_ferias();
        }
    }

    function save()
    {
        $('#btnSave').text('Salvando...'); //change button text
        $('#btnSave, #btnApagar').attr('disabled', true); //set button disable 

        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('apontamento/ajax_save') ?>",
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    $('#modal_form').modal('hide');
                    reload_table();
                }

                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave, #btnApagar').attr('disabled', false); //set button enable 
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
                $('#btnSave').text('Salvar'); //change button text
                $('#btnSave, #btnApagar').attr('disabled', false); //set button enable 
            }
        });
    }

    function apagar()
    {
        if (confirm('Deseja limpar o status da data selecionada?'))
        {

            $('#btnApagar').text('Apagando...'); //change button text
            $('#btnApagar').attr('disabled', true); //set button disable 
            $('#btnSave').attr('disabled', true); //set button disable 

            // ajax adding data to database
            $.ajax({
                url: "<?php echo site_url('apontamento/ajax_delete') ?>",
                type: "POST",
                data: {
                    id: $('[name="id"]').val()
                },
                dataType: "JSON",
                success: function (data)
                {
                    if (data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                    }

                    $('#btnApagar').text('Apagar'); //change button text
                    $('#btnApagar').attr('disabled', false); //set button enable 
                    $('#btnSave').attr('disabled', false); //set button enable 
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert(textStatus);
                    alert('Error adding / update data');
                    $('#btnApagar').text('Apagar'); //change button text
                    $('#btnApagar').attr('disabled', false); //set button enable 
                    $('#btnSave').attr('disabled', false); //set button enable 
                }
            });
        }
    }

</script>

<?php
require_once "end_html.php";
?>