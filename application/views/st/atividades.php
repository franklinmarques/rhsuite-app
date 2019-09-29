<?php require_once APPPATH . 'views/header.php'; ?>

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
                    <li class="active">Gerenciar Atividades & Deficiências</li>
                </ol>
                <br />
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-6">
                <form action="#" id="form_deficiencia" class="form-horizontal">
                    <input name="id" id="id_deficiencia" type="hidden">
                    <label>Deficiência</label>
                    <div class="input-group">
                        <input name="nome" id="deficiencia" type="text" class="form-control" placeholder="Digite o nome da deficiência" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="btnCancelDeficiencia" type="button" style="display: none;">Cancelar</button>
                            <button class="btn btn-primary disabled" id="btnSaveDeficiencia" type="button">Cadastrar</button>
                        </span>
                    </div>
                </form>
                <table id="table_deficiencias" class="table table-striped table-hover table-condensed table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Lista de deficiências</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col col-sm-6">
                <form action="#" id="form_atividade" class="form-horizontal">
                    <input name="id" id="id_atividade" type="hidden">
                    <label>Atividade</label>
                    <div class="input-group">
                        <input name="nome" id="atividade" type="text" class="form-control" placeholder="Digite o nome da atividade" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="btnCancelAtividade" type="button" style="display: none;">Cancelar</button>
                            <button class="btn btn-primary disabled" id="btnSaveAtividade" type="button">Cadastrar</button>
                        </span>
                    </div>
                </form>
                <table id="table_atividades" class="table table-striped table-hover table-condensed table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Lista de atividades</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- page end-->

    </section>
</section>
<!--main content end-->

<?php require_once APPPATH . 'views/end_js.php'; ?>

<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Atividades & Deficiências';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script>

    var save_method; //for save method string
    var table_deficiencias;
    var table_atividades;

    $(document).ready(function () {

        //datatables
        table_deficiencias = $('#table_deficiencias').DataTable({
            "info": false,
            "searching": false,
            "bLengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
//            deferRender: true,
//            scrollY: 200,
//            scrollCollapse: true,
//            scroller: true,
//            "bPaginate": true,
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('apontamento_pacientes/ajax_deficiencias/') ?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    visible: false,
                    targets: [0]
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css({'cursor': 'pointer', 'width': '100%'});
                        $(td).attr({
                            'data-id': rowData[0]
                        });
                        $(td).on('click', function () {
                            $('#id_deficiencia').val($(this).data('id'));
                            $('#deficiencia').val($(this).text());
                            $('#btnCancelDeficiencia').show();
                            $('#btnSaveDeficiencia').removeClass('disabled').text('Atualizar');
                        });
                        $(td).html(rowData[col]);
//                        $(td).html(rowData[col]).after('<td>' + rowData[2] + '</td>');
                    },
                    targets: [1]
                },
                {
                    className: "text-center",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false
                }
            ]
//            "initComplete": function () {
//                $(table_deficiencias.columns(1).header()).attr('colspan', '2');
//            }
        });

        table_atividades = $('#table_atividades').DataTable({
            "info": false,
            "searching": false,
            "bLengthChange": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "language": {
                "url": "<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>"
            },
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('apontamento_pacientes/ajax_atividades/') ?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    visible: false,
                    targets: [0]
                },
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css({'cursor': 'pointer', 'width': '100%'});
                        $(td).attr({
                            'data-id': rowData[0]
                        });
                        $(td).on('click', function () {
                            $('#id_atividade').val($(this).data('id'));
                            $('#atividade').val($(this).text());
                            $('#btnCancelAtividade').show();
                            $('#btnSaveAtividade').removeClass('disabled').text('Atualizar');
                        });
                        $(td).html(rowData[col]);
                    },
                    targets: [1]
                },
                {
                    className: "text-center",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false
                }
            ]
        });

    });

    $('#deficiencia').on('keyup', function () {
        if (this.value.length > 0) {
            $('#btnSaveDeficiencia').removeClass('disabled');
        } else {
            $('#btnSaveDeficiencia').addClass('disabled');
        }
    });

    $('#atividade').on('keyup', function () {
        if (this.value.length > 0) {
            $('#btnSaveAtividade').removeClass('disabled');
        } else {
            $('#btnSaveAtividade').addClass('disabled');
        }
    });

    $('#btnCancelDeficiencia').on('click', function () {
        $('#form_deficiencia input').val('');
        $('#btnCancelDeficiencia').hide();
        $('#btnSaveDeficiencia').addClass('disabled').text('Cadastrar');
    });

    $('#btnCancelAtividade').on('click', function () {
        $('#form_atividade input').val('');
        $('#btnCancelAtividade').hide();
        $('#btnSaveAtividade').addClass('disabled').text('Cadastrar');
    });

    function reload_table_deficiencias()
    {
        table_deficiencias.ajax.reload(null, false); //reload datatable ajax 
    }

    function reload_table_atividades()
    {
        table_atividades.ajax.reload(null, false); //reload datatable ajax 
    }

    $('#btnSaveDeficiencia').on('click', function () {
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('apontamento_pacientes/save_deficiencia') ?>",
            type: "POST",
            data: $('#form_deficiencia').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    reload_table_deficiencias();
                    $('#btnCancelDeficiencia').trigger('click');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    });

    $('#btnSaveAtividade').on('click', function () {
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('apontamento_pacientes/save_atividade') ?>",
            type: "POST",
            data: $('#form_atividade').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    reload_table_atividades();
                    $('#btnCancelAtividade').trigger('click');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    });

    function delete_deficiencia(id)
    {
        if (confirm('Deseja remover deficiência?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('apontamento_pacientes/delete_deficiencia') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data)
                {
                    //if success reload ajax table
                    reload_table_deficiencias();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }

    function delete_atividade(id)
    {
        if (confirm('Deseja remover atividade?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('apontamento_pacientes/delete_atividade') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data)
                {
                    //if success reload ajax table
                    reload_table_atividades();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }

</script>

<?php require_once APPPATH . 'views/end_html.php'; ?>

