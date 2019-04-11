<?php
require_once "header.php";
?>
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        <div> 
            <object type="text/html" data="<?= site_url('avaliacao/avaliadoravaliacao/index/' . $id) ?>" width="100%" height="780px" style="overflow:auto;">
            </object>
        </div>
    </section>
</section>
<?php
require_once "end_js.php";
require_once "end_html.php";
?>