<?php
require_once "header.php";
?>
    <style>
        .jstree-defaulto {
            color: #31708f;
        }

        .jstree-warning {
            color: #f0ad4e;
        }

        .nav > li > a {
            position: relative;
            display: block;
            padding: 10px 8px;
        }

        .btn-primary {
            background-color: #337ab7 !important;
            border-color: #2e6da4 !important;
            color: #fff;
        }
    </style>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-pencil-square-o"></i> Gerenciar Cargos/Funções
                        </header>
                        <div class="panel-body">

                            <ul class="nav nav-tabs nav-justified" role="tablist"
                                style="font-size: small; font-weight: bolder;">
                                <li role="presentation" class="active">
                                    <a href="#cargo" aria-controls="cargo" role="tab" data-toggle="tab">Cargos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#funcao" aria-controls="funcao" role="tab" data-toggle="tab">Funções</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="cargo">
                                    <br>
                                    <button class="btn btn-info" onclick="add_cargo()"><i
                                                class="glyphicon glyphicon-plus"></i>
                                        Adicionar cargo
                                    </button>
                                    <button class="btn btn-primary" onclick="ver_funcoes()"><i
                                                class="glyphicon glyphicon-list-alt"></i>
                                        Ver funções
                                    </button>
                                    <br/>
                                    <div class="table-responsive">
                                        <table id="table_cargo"
                                               class="table table-striped table-condensed table-bordered"
                                               cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="funcao">
                                    <br>
                                    <button class="btn btn-info" onclick="add_funcao()"><i
                                                class="glyphicon glyphicon-plus"></i>
                                        Adicionar função
                                    </button>
                                    <br/>
                                    <div class="table-responsive">
                                        <table id="table_funcao"
                                               class="table table-striped table-condensed table-bordered"
                                               cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Cargo</th>
                                                <th>Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>
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
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>"
          rel="stylesheet">


    <!-- Css -->
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-combobox/css/bootstrap-combobox.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.css"); ?>">
    <link rel="stylesheet" href="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.css"); ?>">

    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Cargos/Funções';
        });
    </script>


    <!-- Js -->
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>
    <script src="<?php echo base_url('assets/JQuery-Mask/jquery.mask.js'); ?>"></script>


    <script src="<?php echo base_url("assets/js/bootstrap-combobox/js/bootstrap-combobox.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/bootstrap-fileinput/bootstrap-fileinput.js"); ?>"></script>
    <script src="<?php echo base_url("assets/js/jquery-tags-input/jquery.tagsinput.js"); ?>"></script>
    <!--    <script src="--><?php //echo base_url('assets/JQuery-Mask/jquery.mask.js') ?><!--"></script>-->

    <script>
        var save_method; //for save method string
        var table_cargo;
        var table_funcao;

        $(document).ready(function () {

            //datatables
            table_cargo = $('#table_cargo').DataTable({
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
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
                        width: '100%',
                        targets: [0]
                    },
                    {
                        className: "text-center text-nowrap",
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false
                    }
                ]
            });

            table_funcao = $('#table_funcao').DataTable({
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
                        width: '50%',
                        targets: [0, 1]
                    },
                    {
                        className: "text-center text-nowrap",
                        width: '0%',
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "searchable": false
                    }
                ]
            });

        });
    </script>

<?php
require_once "end_html.php";
?>