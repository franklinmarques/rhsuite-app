<?php
//require_once "right-sidebar.php";
?>
</section>
<div id="script_js" style="display: none;"></div>

<script>
    $(document).ready(function () {

//        var latitude = null;
//        var longitude = null;
//document.fullScreen = true;
//if(document.fullscreenElement){
//    launchIntoFullscreen(document.getElementById("main-content"));            
//} else {
//    exitFullscreen();
//}
//$("body").keypress(function(event){
        // stop inadvertant form submission
//     alert(event.which);

//});
        $.ajaxSetup({
            'error': function (jqXHR, textStatus, errorThrown) {
                if (navigator.onLine) {
                    if (jqXHR.status === 401) {
                        $('#session_timeout').modal('show');
                    } else if (jqXHR.status !== 0) {
                        alert(textStatus + ' ' + jqXHR.status + ': ' + errorThrown);
                    }
                } else {
                    if (jqXHR.status === 0) {
                        alert(textStatus + ' ' + jqXHR.status + ': ' + 'Disconnected');
                    }
                }
            }
        });


        $.ajax({
            'url': '<?php echo base_url('email/getNovasMensagens'); ?>',
            'type': 'GET',
            'dataType': 'html',
            'data': '',
            'success': function (html) {
                $('#script_js').append(html);
            }
        });

    });

	<?php
	# Geolocalização
	if (empty($localização)) {
	?>

    //    var x = document.getElementById("demo");
    //
    //    getLocation();
    //
    //    function getLocation() {
    //        if (navigator.geolocation) {
    //            navigator.geolocation.getCurrentPosition(showPosition);
    //        } else {
    //            x.innerHTML = "O seu navegador não suporta Geolocalização.";
    //        }
    //    }
    //
    //    function showPosition(position) {
    //        latitude = position.coords.latitude;
    //        longitude = position.coords.longitude;
    //
    //        $.ajax({
    //            url: '<?php // base_url('sidebar/gerarLocalizacao');  ?>/' + latitude + '/' + longitude,
    //            type: 'GET',
    //            data: '',
    //            success: function (data) {
    //                if (data == 'success') {
    //                    location.reload(true);
    //                }
    //            }
    //        });
    //    }

	<?php
	}
	?>

    //    function modalAjuda(){
    //        $('#modal-ajuda').modal('show');
    //    }

</script>

<!--Core js-->
<script src="<?= base_url("assets/bs3/js/bootstrap.min.js"); ?>"></script>
<script class="include" src="<?= base_url("assets/js/jquery.dcjqaccordion.2.7.js"); ?>"></script>
<script src="<?= base_url("assets/js/jquery.scrollTo.min.js"); ?>"></script>
<script src="<?= base_url("assets/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"); ?>"></script>
<script src="<?= base_url("assets/js/jquery.nicescroll.js"); ?>"></script>
<!--Easy Pie Chart-->
<script src="<?= base_url("assets/js/easypiechart/jquery.easypiechart.js"); ?>"></script>
<!--Sparkline Chart-->
<script src="<?= base_url("assets/js/sparkline/jquery.sparkline.js"); ?>"></script>
<!--jQuery Flot Chart
<script src="<?= base_url("assets/js/flot-chart/jquery.flot.js"); ?>"></script>
<script src="<?= base_url("assets/js/flot-chart/jquery.flot.tooltip.min.js"); ?>"></script>
<script src="<?= base_url("assets/js/flot-chart/jquery.flot.resize.js"); ?>"></script>
<script src="<?= base_url("assets/js/flot-chart/jquery.flot.pie.resize.js"); ?>"></script>
-->


<script src="<?= base_url("assets/js/scripts.js"); ?>"></script>

<!-- Ajax -->
<script src="<?php echo base_url("assets/js/ajax/ajax.form.js"); ?>"></script>
<script src="<?php echo base_url("assets/js/ajax/ajax.upload.js"); ?>"></script>
<script src="<?php echo base_url('assets/js/ajax/ajax.custom.js'); ?>"></script>

<script src="<?php echo base_url("assets/js/jquery-migrate-1.2.1.js"); ?>"></script>

<!--clock init-->
<!--<script src="<?php //base_url('assets/js/css3clock/js/css3clock.js'); ?>"></script>-->

<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js') ?>"></script>

<script>
    //    // Modal in content div
    //    $(document).on('show.bs.modal', '#main-content .modal', function () {
    //        setTimeout(function () {
    //            $('.modal-backdrop').appendTo('.wrapper');
    //        }, 0);
    //    });

</script>
