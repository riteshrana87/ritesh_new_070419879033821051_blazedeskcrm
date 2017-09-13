<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //pr($param);    ?>
<div class="white-wrapper">
    <div class="container">


        <!-- Example row of columns -->
        <div class="row">
            <div class="col-xs-12 col-md-10 col-sm-10 col-lg-10">
                <h3 class="blue-col">Our all in one solution</h3>
                <p> As an entrepreneur you want to grow your organization. In addition, you need a system that provides you real-time visibility and full control over your business. Blazedesk takes care of your daily activities. Working efficiently saves time and provides value. </p>
            </div>
            <div class="col-xs-12 col-md-2 col-sm-2 col-lg-2 pad-tb12 text-right login-ftr-img">
                <img src="<?= base_url() ?>uploads/images/capterra.png"  alt=""/>
            </div>
            <div class="clr"></div>
            <div class="footer">
                <p class="footerfont"> &copy; 2016  BLAZEDESK | Blazedesk | Kade 30, 3371EP Hardinxveld-Giessendam | KVK 63875853 | BTW: NL852656312B01 | Info@blazedesk.us </p>
            </div>
        </div>
    </div>
    <!-- /container white	 --> 
</div>
<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug --> 
<!--<script src="<?= base_url() ?>uploads/dist/js/datatables.min.js"></script> 
<script src="<?= base_url() ?>uploads/dist/js/bootstrap-datepicker.js"></script> -->

<script src="<?= base_url() ?>uploads/custom/js/projectmanagement/parsley.js"></script>
<link href="<?= base_url() ?>uploads/custom/css/projectmanagement/parsley.css" rel="stylesheet">
<script src="<?php echo base_url('uploads/dist/js/jstz.js'); ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var tz = jstz.determine();
        $('#timezone').val(tz.name);
       // $('#datepicker,  #datepicker1, #datepicker2').datepicker();

       // $('#datatable, #datatable1, #datatable2, #datatable3, #datatable4, #datatable5, #datatable6, #datatable7').DataTable();

    });


    $(document).ready(function () {
        $("div.bhoechie-tab-menu>div.list-group>a").click(function (e) {
            e.preventDefault();
            $(this).siblings('a.active').removeClass("active");
            $(this).addClass("active");
            var index = $(this).index();
            $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
            $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
        });
        setTimeout(function () {
            $('.alert').fadeOut('5000');
        }, 3000);
    });
</script>
