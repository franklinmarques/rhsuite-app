<?php require_once APPPATH . 'views/header.php'; ?>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-12">
                    <div id="alert"></div>
                    <section class="panel">
                        <header class="panel-heading">
                            <i class="fa fa-file-text-o"></i> Páginas do Treinamento - <?php echo $row->nome; ?>
                            <button class="btn btn-default btn-sm" onclick="javascript:history.back()" style="float:right; margin-top: -0.3%;"><i class="fa fa-reply"></i> Voltar</button>
                        </header>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <a class="btn btn-success" href="<?php echo site_url('ead/pagina_curso/novo/' . $this->uri->rsegment(3)); ?>">
                                        <i class="fa fa-plus"></i> <span>Adicionar página</span>
                                    </a>
                                    <?php if ($row->qtde_paginas > 0): ?>
                                        <a class="btn btn-info" target="_blank" href="<?php echo site_url('ead/cursos/preview/' . $this->uri->rsegment(3)); ?>">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                            <span>Visualizar treinamento</span>
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-info disabled">
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                            <span>Visualizar treinamento</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-6 text-danger text-right">
                                    <p>
                                        <em>* Arraste e solte uma linha da coluna em destaque para mudar a ordem.</em>
                                    </p>
                                </div>
                            </div>
                            <br />
                            <table id="table" class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th nowrap>Ordem<span class="text-danger"> *</span></th>
                                    <th>Título</th>
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
    <link rel="stylesheet" href="<?php echo base_url("assets/datatables/extensions/rowReorder.dataTables.min.css"); ?>"/>

<?php require_once APPPATH . 'views/end_js.php'; ?>
    <!-- Js -->
    <script>
        $(document).ready(function () {
            document.title = 'CORPORATE RH - LMS - Gerenciar Páginas do Treinamento - <?php echo $row->nome; ?>';
        });
    </script>

    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/extensions/rowReorder.dataTables.min.js'); ?>"></script>

    <script>
        var table;

        $(document).ready(function () {
            table = $('#table').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 25,
                "lengthMenu": [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, 'Todos']],
                rowReorder: {
                    selector: 'td.reorder',
                    update: false
                },
                language: {
                    url: '<?php echo base_url('assets/datatables/lang_pt-br.json'); ?>'
                },
                ajax: {
                    url: '<?php echo site_url('ead/pagina_curso/ajax_list/') ?>',
                    type: 'POST',
                    data: {
                        id: '<?= $row->id ?>'
                    }
                },
                columnDefs: [
                    {
                        "createdCell": function (td) {
                            $(td).addClass('active');
                        },
                        className: 'reorder text-center',
                        searchable: false,
                        targets: [0]
                    },
                    {
                        width: '100%',
                        targets: [1]
                    },
                    {
                        className: 'text-nowrap',
                        targets: [-1],
                        orderable: false,
                        searchable: false
                    }
                ]
            }).on('row-reorder', function (e, diff) {
                if (diff.length > 1) {
                    var colunas = new Array();
                    for (var i = 0, ien = diff.length; i < ien; i++) {
                        colunas[i] = {oldData: diff[i].oldData, newData: diff[i].newData};
                    }

                    $.ajax({
                        url: '<?php echo site_url('ead/pagina_curso/ordenar/' . $this->uri->rsegment(3)); ?>',
                        type: 'POST',
                        data: {'table-dnd': colunas},
                        success: function () {
                            e.preventDefault();
                            reload_table();
                        }
                    });
                }
            });
        });

        function copiar(id) {
            if (confirm('Deseja copiar essa página?')) {
                $.ajax({
                    url: '<?php echo site_url('ead/pagina_curso/duplicar') ?>',
                    type: "POST",
                    dataType: "JSON",
                    timeout: 9000,
                    data: {
                        id: id
                    },
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

        function reload_table()
        {
            table.ajax.reload(null, false);
        }

        function ajax_delete(id) {
            if (confirm('Tem certeza que deseja excluir essa página do curso?'))
            {
                $.ajax({
                    url: "<?php echo site_url('ead/pagina_curso/ajax_delete') ?>",
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
                        alert('Erro ao excluir a página do curso');
                    }
                });

            }
        }
    </script>
<?php require_once APPPATH . 'views/end_html.php'; ?>