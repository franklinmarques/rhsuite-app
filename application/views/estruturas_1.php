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
                    <li class="active">Gerenciar Estrutura Organizacional</li>
                </ol>
                <br />
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-4">
                <form action="#" id="form_depto" class="form-horizontal">
                    <input name="id" id="id_depto" type="hidden">
                    <label>Departamento</label>
                    <div class="input-group">
                        <input name="nome" id="depto" type="text" class="form-control disabled" placeholder="Digite o nome do departamento" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default disabled" id="btnCancelDepto" type="button">Cancelar</button>
                            <button class="btn btn-primary disabled" id="btnSaveDepto" type="button">Atualizar</button>
                        </span>
                    </div>
                </form>
                <table id="table_depto" class="table table-striped table-hover table-condensed table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Lista de departamentos</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col col-sm-4">
                <form action="#" id="form_area" class="form-horizontal">
                    <input name="id" id="id_area" type="hidden">
                    <label>Área</label>
                    <div class="input-group">
                        <input name="nome" id="area" type="text" class="form-control disabled" placeholder="Digite o nome da área" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default disabled" id="btnCancelArea" type="button">Cancelar</button>
                            <button class="btn btn-primary disabled" id="btnSaveArea" type="button">Atualizar</button>
                        </span>
                    </div>
                </form>
                <table id="table_area" class="table table-striped table-hover table-condensed table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Lista de áreas</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col col-sm-4">
                <form action="#" id="form_setor" class="form-horizontal">
                    <input name="id" id="id_setor" type="hidden">
                    <label>Setor</label>
                    <div class="input-group">
                        <input name="nome" id="setor" type="text" class="form-control disabled" placeholder="Digite o nome do setor" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default disabled" id="btnCancelSetor" type="button">Cancelar</button>
                            <button class="btn btn-primary disabled" id="btnSaveSetor" type="button">Atualizar</button>
                        </span>
                    </div>
                </form>
                <table id="table_setor" class="table table-striped table-hover table-condensed table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Lista de setores</th>
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

<?php
require_once "end_js.php";
?>
<!-- Css -->
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<!-- Js -->
<script>
    $(document).ready(function () {
        document.title = 'CORPORATE RH - LMS - Gerenciar Estrutura Organizacional';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script>

    var save_method; //for save method string
    var table_depto;
    var table_area;
    var table_setor;

    $(document).ready(function () {

        //datatables
        table_depto = $('#table_depto').DataTable({
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
                "url": "<?php echo site_url('estruturas/ajax_depto') ?>",
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css({'cursor': 'pointer', 'width': '100%'});
                        $(td).attr({
                            'data-id': rowData[0]
                        });
                        $(td).on('click', function () {
                            $('#id_depto').val($(this).data('id'));
                            $('#depto').val($(this).text());
                            $('#depto, #btnSaveDepto, #btnCancelDepto').removeClass('disabled');
                            $('#table_depto tr').removeClass('active');
                            $(td).parent().addClass('active');
                            reload_table_area();
                            reload_table_setor();
                        });
                        $(td).html(rowData[col]);
//                        $(td).html(rowData[col]).after('<td>' + rowData[2] + '</td>');
                    },
                    targets: [0]
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

        table_area = $('#table_area').DataTable({
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
                "url": "<?php echo site_url('estruturas/ajax_area/') ?>",
                "type": "POST",
                data: function (d) {
                    d.depto = $('#depto').val();
                    return d;
                }
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css({'cursor': 'pointer', 'width': '100%'});
                        $(td).attr({
                            'data-id': rowData[0]
                        });
                        $(td).on('click', function () {
                            $('#id_area').val($(this).data('id'));
                            $('#area').val($(this).text());
                            $('#area, #btnSaveArea, #btnCancelArea').removeClass('disabled');
                            $('#table_area tr').removeClass('active');
                            $(td).parent().addClass('active');
                            reload_table_setor();
                        });
                        $(td).html(rowData[col]);
                    },
                    targets: [0]
                },
                {
                    className: "text-center",
                    "targets": [-1], //last column
                    "orderable": false, //set not orderable
                    "searchable": false
                }
            ]
        });

        table_setor = $('#table_setor').DataTable({
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
                "url": "<?php echo site_url('estruturas/ajax_setor') ?>",
                "type": "POST",
                data: function (d) {
                    d.depto = $('#depto').val();
                    d.area = $('#area').val();
                    return d;
                }
            },
            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css({'cursor': 'pointer', 'width': '100%'});
                        $(td).attr({
                            'data-id': rowData[0]
                        });
                        $(td).on('click', function () {
                            $('#id_setor').val($(this).data('id'));
                            $('#setor').val($(this).text());
                            $('#setor, #btnSaveSetor, #btnCancelSetor').removeClass('disabled');
                            $('#table_setor tr').removeClass('active');
                            $(td).parent().addClass('active');
                        });
                        $(td).html(rowData[col]);
                    },
                    targets: [0]
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

    $('#depto').on('keyup', function () {
        if (this.value.length > 0 && $('#table_depto tr').hasClass('active')) {
            $('#btnSaveDepto, #btnCancelDepto').removeClass('disabled');
        } else {
            $('#btnSaveDepto, #btnCancelDepto').addClass('disabled');
        }
    });

    $('#area').on('keyup', function () {
        if (this.value.length > 0 && $('#table_area tr').hasClass('active')) {
            $('#btnSaveArea, #btnCancelArea').removeClass('disabled');
        } else {
            $('#btnSaveArea, #btnCancelArea').addClass('disabled');
        }
    });

    $('#setor').on('keyup', function () {
        if (this.value.length > 0 && $('#table_setor tr').hasClass('active')) {
            $('#btnSaveSetor, #btnCancelSetor').removeClass('disabled');
        } else {
            $('#btnSaveSetor, #btnCancelSetor').addClass('disabled');
        }
    });

    $('#btnCancelDepto').on('click', function () {
        $('#form_depto input').val('');
        $('#table_depto tr').removeClass('active');
//        $('#btnCancelDepto').hide();
        $('#depto, #btnSaveDepto, #btnCancelDepto').addClass('disabled');
    });

    $('#btnCancelArea').on('click', function () {
        $('#form_area input').val('');
        $('#table_area tr').removeClass('active');
//        $('#btnCancelArea').hide();
        $('#area, #btnSaveArea, #btnCancelDepto').addClass('disabled');
    });

    $('#btnCancelSetor').on('click', function () {
        $('#form_setor input').val('');
        $('#table_setor tr').removeClass('active');
//        $('#btnCancelSetor').hide();
        $('#setor, #btnSaveSetor, #btnCancelDepto').addClass('disabled');
    });

    function reload_table_depto()
    {
        table_depto.ajax.reload(null, false); //reload datatable ajax 
    }

    function reload_table_area()
    {
        table_area.ajax.reload(null, false); //reload datatable ajax 
    }

    function reload_table_setor()
    {
        table_setor.ajax.reload(null, false); //reload datatable ajax 
    }

    $('#btnSaveDepto').on('click', function () {
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('estruturas/save_depto') ?>",
            type: "POST",
            data: $('#form_depto').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    reload_table_depto();
                    $('#btnCancelDepto').trigger('click');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    });

    $('#btnSaveArea').on('click', function () {
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('estruturas/save_area') ?>",
            type: "POST",
            data: $('#form_area').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    reload_table_area();
                    $('#btnCancelArea').trigger('click');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    });

    $('#btnSaveSetor').on('click', function () {
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('estruturas/save_setor') ?>",
            type: "POST",
            data: $('#form_setor').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    reload_table_setor();
                    $('#btnCancelSetor').trigger('click');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    });

    function delete_depto(id)
    {
        if (confirm('Deseja remover departamento?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('estruturas/delete_depto') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data)
                {
                    //if success reload ajax table
                    reload_table_depto();
                    reload_table_area();
                    reload_table_setor();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }

    function delete_area(id)
    {
        if (confirm('Deseja remover área?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('estruturas/delete_area') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data)
                {
                    //if success reload ajax table
                    reload_table_area();
                    reload_table_setor();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }

    function delete_setor(id)
    {
        if (confirm('Deseja remover setor?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('estruturas/delete_setor') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data)
                {
                    //if success reload ajax table
                    reload_table_setor();
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
require_once "end_html.php";
?>