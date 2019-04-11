<?php require_once APPPATH . "views/header.php"; ?>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!-- page start-->
            <div class="row">
                <div id="alert"></div>
                <div class="col-md-12">
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-graduation-cap"></i> Gerenciar Treinamentos
                        </header>
                        <div class="panel-body">
                            <div class="col-md-10 col-lg-push-1 col-sm-1">
                                <div class="panel-group m-bot20" id="accordion">
                                    <div class="well well-sm">
                                        <div class="">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="height: 1px;">
                                                <span style="padding-left: 40%; font-weight: bold;"><i class="fa fa-search"></i>&ensp;Pesquisa avançada</span>
                                            </a>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <?php echo form_open('ead/cursos/ajax_list', 'data-html="html-cursos" class="form-horizontal" id="busca-cursos"'); ?>
                                                <div class="form-group">
                                                    <div class="col-sm-2 col-lg-3 controls">
                                                        <?php if ($this->session->userdata('tipo') == "administrador"): ?>
                                                            <?php echo form_dropdown('publico', $publico, '', 'class="form-control input-sm"'); ?>
                                                        <?php elseif ($this->session->userdata('tipo') == "empresa"): ?>
                                                            <?php echo form_dropdown('tipo', $tipo, '', 'class="form-control input-sm"'); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-sm-3 col-lg-4">
                                                        <?php echo form_dropdown('categoria', $categorias, '', 'class="form-control input-sm"'); ?>
                                                    </div>
                                                    <div class="col-sm-3 col-lg-4">
                                                        <?php echo form_dropdown('area_conhecimento', $areas_conhecimento, '', 'class="form-control input-sm"'); ?>
                                                    </div>
                                                    <div class="col-sm-3 col-lg-1">
                                                        <button type="button" id="pesquisar" class="btn btn-primary busca"><i class="glyphicon glyphicon-search"></i></button>
                                                    </div>
                                                </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <table id="table" class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Acesso</th>
                                    <th>Tipo</th>
                                    <th>Nome</th>
                                    <th>Ações</th>
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

<?php require_once APPPATH . "views/end_js.php"; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'RhSuite - Corporate RH Tools: Gerenciar Treinamentos';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>

    <script>
        var table;

        $(document).ready(function () {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: -1,
                lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                order: [[2, 'asc']],
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('ead/cursos/ajax_list/') ?>',
                    type: 'POST',
                    data: function (d) {
                        d.categoria = $('[name="categoria"]').val();
                        d.area_conhecimento = $('[name="area_conhecimento"]').val();
                        d.tipo = $('[name="tipo"]').val();
                        d.publico = $('[name="publico"]').val();

                        return d;
                    }
                },
                columnDefs: [
                    {
                        className: 'text-center',
                        visible: <?= $this->session->userdata('tipo') == 'administrador' ? 'true' : 'false' ?>,
                        searchable: false,
                        targets: [0]
                    },
                    {
                        className: 'text-center',
                        visible: <?= $this->session->userdata('tipo') == 'empresa' ? 'true' : 'false' ?>,
                        searchable: false,
                        targets: [1]
                    },
                    {
                        width: '100%',
                        targets: [2]
                    },
                    {
                        className: 'text-nowrap',
                        targets: [-1],
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#pesquisar').on('click', function () {
                reload_table();
            });
        });

        function copiaCursos(id) {
            if (confirm('Deseja copiar esse curso?')) {
                $.ajax({
                    url: '<?php echo site_url('ead/cursos/duplicar') ?>',
                    type: "POST",
                    dataType: "JSON",
                    timeout: 9000,
                    data: {
                        id: id
                    },
                    success: function (json) {
                        if (json.status === 'sucesso') {
                            reload_table();
                        } else {
                            alert(json.status);
                        }
                    }
                });
            }
        }

        function statusCursos(status, id) {
            if (confirm('Deseja alterar a situação desse curso?')) {
                $.ajax({
                    url: '<?php echo site_url('ead/cursos/status') ?>/' + status + '/' + id,
                    dataType: 'json',
                    success: function (data) {
                        if (data === 'sucesso') {
                            reload_table();
                        } else {
                            alert(data);
                        }
                    }
                });
            }
        }

        function detalhesCursos(id) {
            if (id > 0) {
                var url = '<?php echo site_url('ead/cursos/detalhes'); ?>/' + id;
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function (data) {
                        $('#getDetalhes').html(data);
                        $('#myModal').modal('show');
                    }
                });
            }
        }

        function solicitaCursos(id) {
            if (confirm('Tem certeza que deseja solicitar esse curso para o administrador?')) {
                var aviso = $('#alert-solicitar');

                $.ajax({
                    url: "<?php echo site_url('ead/cursos/solicitar') ?>",
                    type: "POST",
                    dataType: "JSON",
                    timeout: 9000,
                    data: {
                        id: id
                    },
                    beforeSend: function () {
                        $('html, body').animate({scrollTop: 0}, 1500);
                        aviso.html('<div class="alert alert-info">Carregando...</div>').hide().fadeIn('slow');
                    },
                    error: function () {
                        aviso.html('<div class="alert alert-danger">Erro, tente novamente!</div>').hide().fadeIn('slow');
                    },
                    success: function (data) {
                        $('html, body').animate({scrollTop: 0}, 1500);
                        if (parseInt(data['retorno'])) {
                            aviso.html('<div class="alert alert-success">' + data['aviso'] + '</div>').hide().fadeIn('slow', function () {
                                if (parseInt(data['redireciona']))
                                    window.location = data['pagina'];
                            });
                        } else {
                            aviso.html('<div class="alert alert-danger">' + data['aviso'] + '</div>').hide().fadeIn('slow');
                        }
                    }
                });
            }
        }

        function reload_table()
        {
            table.ajax.reload(null, false);
        }

        function ajax_delete(id) {
            if (confirm('Tem certeza que deseja excluir esse curso?'))
            {
                $.ajax({
                    url: "<?php echo site_url('ead/cursos/ajax_delete') ?>",
                    type: "POST",
                    dataType: "JSON",
                    timeout: 9000,
                    data: {
                        id: id
                    },
                    success: function (data)
                    {
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Erro ao excluir o curso');
                    }
                });

            }
        }
    </script>
<?php require_once APPPATH . "views/end_html.php"; ?>