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

                                </div>
                            </div>
                            <br/>
                            <table id="table" class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Colaborador(a)</th>
                                    <th>Treinamento</th>
                                    <th>Data início</th>
                                    <th>Data término</th>
                                    <th>Avaliação requerida</th>
                                    <th>Avaliação final</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?= $row->nome; ?></td>
                                        <td><?= $row->curso; ?></td>
                                        <td><?= $row->data_inicio; ?></td>
                                        <td><?= $row->data_maxima; ?></td>
                                        <td><?= $row->nota_avaliacao; ?></td>
                                        <td><?= $row->resultado; ?></td>
                                        <td><?= $row->status; ?></td>
                                    </tr>
                                <?php endforeach; ?>
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

<?php require_once APPPATH . 'views/end_html.php'; ?>