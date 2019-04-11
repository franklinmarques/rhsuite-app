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
                    <li class="active">Gerenciar Cargos/Funções</li>
                </ol>
                <br />
            </div>
        </div>
        <div class="row">
            <div class="col col-sm-6">
                <form action="#" id="form_cargo" class="form-horizontal">
                    <input name="id" id="id_cargo" type="hidden">
                    <label>Cargo</label>
                    <div class="input-group">
                        <input name="nome" id="cargo" type="text" class="form-control disabled" placeholder="Digite o nome do cargo" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default disabled" id="btnCancelCargo" type="button">Cancelar</button>
                            <button class="btn btn-primary disabled" id="btnSaveCargo" type="button">Atualizar</button>
                        </span>
                    </div>
                </form>
                <table id="table_cargo" class="table table-striped table-hover table-condensed table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Lista de cargos</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col col-sm-6">
                <form action="#" id="form_funcao" class="form-horizontal">
                    <input name="id" id="id_funcao" type="hidden">
                    <label>Função</label>
                    <div class="input-group">
                        <input name="nome" id="funcao" type="text" class="form-control disabled" placeholder="Digite o nome da função" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-default disabled" id="btnCancelFuncao" type="button">Cancelar</button>
                            <button class="btn btn-primary disabled" id="btnSaveFuncao" type="button">Atualizar</button>
                        </span>
                    </div>
                </form>
                <table id="table_funcao" class="table table-striped table-hover table-condensed table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Lista de funçãos</th>
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
        document.title = 'CORPORATE RH - LMS - Gerenciar Cargos/Funções';
    });
</script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>
<script>

    var save_method; //for save method string
    var table_cargo;
    var table_funcao;

    $(document).ready(function () {

        //datatables
        table_cargo = $('#table_cargo').DataTable({
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
                "url": "<?php echo site_url('cargo_funcao/ajax_cargo') ?>",
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
                            $('#id_cargo').val($(this).data('id'));
                            $('#cargo').val($(this).text());
                            $('#cargo, #btnSaveCargo, #btnCancelCargo').removeClass('disabled');
                            $('#table_cargo tr').removeClass('active');
                            $(td).parent().addClass('active');
                            reload_table_funcao();
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

        table_funcao = $('#table_funcao').DataTable({
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
                "url": "<?php echo site_url('cargo_funcao/ajax_funcao') ?>",
                "type": "POST",
                data: function (d) {
                    d.cargo = $('#cargo').val();
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
                            $('#id_funcao').val($(this).data('id'));
                            $('#funcao').val($(this).text());
                            $('#funcao, #btnSaveFuncao, #btnCancelFuncao').removeClass('disabled');
                            $('#table_funcao tr').removeClass('active');
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

    $('#cargo').on('keyup', function () {
        if (this.value.length > 0 && $('#table_cargo tr').hasClass('active')) {
            $('#btnSaveCargo, #btnCancelCargo').removeClass('disabled');
        } else {
            $('#btnSaveCargo, #btnCancelCargo').addClass('disabled');
        }
    });

    $('#funcao').on('keyup', function () {
        if (this.value.length > 0 && $('#table_funcao tr').hasClass('active')) {
            $('#btnSaveFuncao, #btnCancelFuncao').removeClass('disabled');
        } else {
            $('#btnSaveFuncao, #btnCancelFuncao').addClass('disabled');
        }
    });

    $('#btnCancelCargo').on('click', function () {
        $('#form_cargo input').val('');
//        $('#btnCancelCargo').hide();
        $('#table_cargo tr').removeClass('active');
        $('#cargo, #btnSaveCargo, #btnCancelCargo').addClass('disabled');
    });

    $('#btnCancelFuncao').on('click', function () {
        $('#form_funcao input').val('');
//        $('#btnCancelFuncao').hide();
        $('#table_funcao tr').removeClass('active');
        $('#funcao, #btnSaveFuncao, #btnCancelCargo').addClass('disabled');
    });

    function reload_table_cargo()
    {
        table_cargo.ajax.reload(null, false); //reload datatable ajax 
    }

    function reload_table_funcao()
    {
        table_funcao.ajax.reload(null, false); //reload datatable ajax 
    }

    $('#btnSaveCargo').on('click', function () {
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('cargo_funcao/save_cargo') ?>",
            type: "POST",
            data: $('#form_cargo').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    reload_table_cargo();
                    $('#btnCancelCargo').trigger('click');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    });

    $('#btnSaveFuncao').on('click', function () {
        // ajax adding data to database
        $.ajax({
            url: "<?php echo site_url('cargo_funcao/save_funcao') ?>",
            type: "POST",
            data: $('#form_funcao').serialize(),
            dataType: "JSON",
            success: function (data)
            {
                if (data.status) //if success close modal and reload ajax table
                {
                    reload_table_funcao();
                    $('#btnCancelFuncao').trigger('click');
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    });

    function delete_cargo(id)
    {
        if (confirm('Deseja remover cargo?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('cargo_funcao/delete_cargo') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data)
                {
                    //if success reload ajax table
                    reload_table_cargo();
                    reload_table_funcao();
                    reload_table_setor();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }

    function delete_funcao(id)
    {
        if (confirm('Deseja remover função?'))
        {
            // ajax delete data to database
            $.ajax({
                url: "<?php echo site_url('cargo_funcao/delete_funcao') ?>",
                type: "POST",
                dataType: "JSON",
                data: {id: id},
                success: function (data)
                {
                    //if success reload ajax table
                    reload_table_funcao();
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