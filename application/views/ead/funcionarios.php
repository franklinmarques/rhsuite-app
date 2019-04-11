<?php require_once APPPATH . 'views/header.php'; ?>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-graduation-cap"></i> Gestão alocacão treinamentos
                        </header>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="well well-sm">
                                        <form action="#" id="busca" class="form-horizontal" autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="control-label">Departamento</label>
                                                    <?php echo form_dropdown('depto', $depto, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="control-label">Área</label>
                                                    <?php echo form_dropdown('area', $area, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="control-label">Setor</label>
                                                    <?php echo form_dropdown('setor', $setor, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label class="control-label">Treinamento</label>
                                                    <?php echo form_dropdown('treinamento', $treinamento, '', 'onchange="atualizarFiltro()" class="form-control input-sm filtro"'); ?>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label">Status</label>
                                                    <select name="status" class="form-control input-sm filtro">
                                                        <option value="">Todos</option>
                                                        <option value="0">Em espera</option>
                                                        <option value="1">Abertos</option>
                                                        <option value="2">Em curso</option>
                                                        <option value="3">Concluídos</option>
                                                        <option value="-1">Expirados</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>&nbsp;</label><br>
                                                    <div class="btn-group" role="group" aria-label="...">
                                                        <button type="button" id="pesquisar"
                                                                class="btn btn-sm btn-default">
                                                            <i class="glyphicon glyphicon-search"></i>
                                                        </button>
                                                        <button type="button" id="limpar_filtro"
                                                                class="btn btn-sm btn-default">
                                                            Limpar
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>&nbsp;</label><br>
                                                    <a id="pdf" class="btn btn-sm btn-danger"
                                                       href="<?= site_url('ead/funcionarios/pdf/' . $query_string); ?>"
                                                       title="Exportar PDF">
                                                        <i class="glyphicon glyphicon-download-alt"></i> Exportar
                                                        PDF</a>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <table id="table" class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Colaborador(a)</th>
                                    <th>Treinamento</th>
                                    <th>Data início</th>
                                    <th>Data término</th>
                                    <th>Avaliação requerida</th>
                                    <th>Avaliação final</th>
                                    <th>Status</th>
                                    <th>Ação</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <!-- Css -->
    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css') ?>" rel="stylesheet">

<?php require_once APPPATH . 'views/end_js.php'; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gestão alocacão treinamentos';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/plugins/dataTables.rowsGroup.js'); ?>"></script>

    <script>
        var table, busca;

        $(document).ready(function () {
            busca = $('#busca').serialize();

            table = $('#table').on('preXhr.dt', function () {
                setPdf_atributes();
            }).DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: -1,
                "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('ead/funcionarios/ajaxList/') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.busca = busca;
                        return d;
                    },
                },
                columnDefs: [
                    {
                        visible: false,
                        targets: [0]
                    },
                    {
                        width: '50%',
                        targets: [1, 2]
                    },
                    {
                        className: 'text-center',
                        searchable: false,
                        targets: [3, 4, 5, 6, 7]
                    },
                    {
                        className: 'text-nowrap',
                        targets: [-1],
                        orderable: false,
                        searchable: false
                    }
                ],
                rowsGroup: [0, 1]
            });
        });

        function atualizarFiltro() {
            $.ajax({
                url: "<?php echo site_url('ead/funcionarios/atualizar_filtro/') ?>",
                type: "POST",
                dataType: "JSON",
                data: $('#busca').serialize(),
                success: function (json) {
                    $('#busca [name="area"]').html($(json.area).html());
                    $('#busca [name="setor"]').html($(json.setor).html());
                    $('#busca [name="treinamento"]').html($(json.treinamento).html());
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        }

        $('#pesquisar').on('click', function () {
            filtrar();
        });

        $('#limpar_filtro').on('click', function () {
            $('#busca select').val('');
            filtrar();
        });

        function filtrar() {
            busca = $('#busca').serialize();
            reload_table();
        }

        function reload_table() {
            table.ajax.reload(null, false);
        }

        function ajax_delete(id) {
            if (confirm('Tem certeza que deseja excluir esse curso do funcionário?')) {
                $.ajax({
                    url: '<?php echo site_url('ead/cursos_funcionario/ajax_delete') ?>',
                    type: 'POST',
                    dataType: 'JSON',
                    timeout: 9000,
                    data: {
                        id: id
                    },
                    success: function (data) {
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Erro ao excluir treinamento');
                    }
                });

            }
        }

        function setPdf_atributes() {
            var search = '';
            var q = new Array();
            $('#busca select').each(function (i, v) {
                if (v.value.length > 0 && (v.value !== 'Todos' || v.value !== 'Todas')) {
                    q[i] = v.name + "=" + v.value;
                }
            });
            if (table.search().length > 0) {
                q.push('busca=' + table.search());
            }

            q = q.filter(function (v) {
                return v.length > 0;
            });
            if (q.length > 0) {
                search = '/q?' + q.join('&');
            }

            $('#pdf').prop('href', "<?= site_url('ead/funcionarios/pdf/'); ?>" + search);
        }

    </script>
<?php require_once APPPATH . 'views/end_html.php'; ?>