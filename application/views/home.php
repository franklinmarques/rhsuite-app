<?php
require_once "header.php";
?>
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <!-- page start-->

            <div class="row">
                <div class="col-sm-12">
                    <section class="panel">
                        <header class="panel-heading" id="titulo">
                            Heading goes here..
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                        </header>
                        <div class="panel-body">
                            <button class="btn btn-success" type="button" id="btnTeste">Testar</button>
                            This is a sample page
                        </div>

                        <table>
                            <thead>
                            </thead>
                            <tbody id="tabela">
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
            <!-- page end-->
        </section>
    </section>
    <!--main content end-->

    <script>
        $(document).ready(function () {
            document.title = 'AE - Home';
            $("#titulo").html("Titulo da página");
        });

        $("#btnTeste").click(function () {
            alert(1);
        });
    </script>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>