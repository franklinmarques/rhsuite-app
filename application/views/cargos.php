<?php
require_once "header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div> 
            <object type="text/html" data="<?= site_url('avaliacao/cargos/index/' . $id) ?>" width="100%" height="750px" style="overflow:auto;margin:0px;padding:0px;">
            </object>
        </div>
    </section>
</section>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>