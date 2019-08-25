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
                        <li class="active">ICOM - Gerenciar Colaboradores</li>
                    </ol>
                    <form action="#" id="estrutura" class="form-horizontal" autocomplete="off">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Filtrar por departamento</label>
                                <?php echo form_dropdown('id_depto', $deptos, $depto_atual, 'onchange="filtrar_estrutura()" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label>Filtrar por área</label>
                                <?php echo form_dropdown('id_area', $areas, $area_atual, 'onchange="filtrar_estrutura();" class="form-control input-sm"'); ?>
                            </div>
                            <div class="col-md-4">
                                <label>Filtrar por setor</label>
                                <?php echo form_dropdown('id_setor', $setores, $setor_atual, 'onchange="filtrar_estrutura();" class="form-control input-sm"'); ?>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <button class="btn btn-default" onclick="javascript:history.back()"><i
                                class="glyphicon glyphicon-circle-arrow-left"></i> Voltar
                    </button>
                    <a id="pdf" class="btn btn-info" href="<?= site_url('icom/colaboradores/pdf'); ?>"
                       target="_blank"><i
                                class="glyphicon glyphicon-print"></i> Imprimir
                    </a>
                    <br>
                    <table id="table" class="table table-striped table-condensed" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Funcionário(a)</th>
                            <th>Depto/Área/Setor</th>
                            <th>Função</th>
                            <th>Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
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
            document.title = 'CORPORATE RH - LMS - Gestão Comercial: Gerenciar Clientes';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js') ?>"></script>

    <script>

        var save_method;
        var table;


        $(document).ready(function () {

            table = $('#table').DataTable({
                'processing': true,
                'serverSide': true,
                'order': [['0', 'asc']],
                'language': {
                    'url': '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                'ajax': {
                    'url': '<?php echo site_url('icom/colaboradores/listar') ?>',
                    'type': 'POST',
                    'data': function (d) {
                        d.busca = $('#estrutura').serialize();
                        return d;
                    }
                },
                'columnDefs': [
                    {
                        'width': '30%',
                        'targets': [0, 1, 2]
                    },
                    {
                        'className': 'text-nowrap',
                        'targets': [-1],
                        'orderable': false,
                        'searchable': false
                    }
                ],
                'preDrawCallback': function () {
                    $('#estrutura select').prop('disabled', false);
                    setPdf_atributes();
                }
            });

        });


        function filtrar_estrutura() {
            var data = $('#estrutura').serialize();
            $.ajax({
                'url': '<?php echo site_url('icom/colaboradores/filtrarEstrutura') ?>',
                'type': 'POST',
                'dataType': 'json',
                'data': data,
                'beforeSend': function () {
                    $('#estrutura select').prop('disabled', true);
                },
                'success': function (json) {
                    if (json.erro) {
                        alert(json.erro);
                    } else {
                        $('#estrutura [name="id_area"]').html(json.areas);
                        $('#estrutura [name="id_setor"]').html(json.setores);
                        reload_table();
                    }
                },
                'error': function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                },
                'complete': function () {
                    $('#estrutura select').prop('disabled', false);
                }
            });
        }


        function reload_table() {
            table.ajax.reload(null, false);
        }


        function setPdf_atributes() {
            var search = '';
            var q = new Array();

            $('#estrutura select').each(function (i, v) {
                if (v.value.length > 0) {
                    q[i] = v.name + "=" + v.value;
                }
            });

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('icom/colaboradores/pdf/'); ?>" + search);
        }


    </script>

<?php require_once APPPATH . 'views/end_html.php'; ?>