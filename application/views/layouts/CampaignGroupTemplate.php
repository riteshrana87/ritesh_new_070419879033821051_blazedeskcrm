<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        /*
          Author : Rupesh Jorkar(RJ)
          Desc   : Call Head area
          Input  : Bunch of Array
          Output : All CSS and JS
          Date   : 04/02/2016
         */
        if (empty($head)) {
            $head = array();
        }
        echo Modules::run('Sidebar/head', $head);
       $system_lang = $this->common_model->get_lang();
        ?>
    </head>	
   <body id=<?php echo $system_lang;?> <?php if($system_lang!='english'){?>class="bd-lang-format"<?php }?>>
        <nav class="navbar navbar-default navbar-web">
            <div class="menu-whitebg">  <div class="container">
                <?php
                /*
                  Author : Rupesh Jorkar(RJ)
                  Desc   : Call Header area
                  Input  : Bunch of Array
                  Output : Top Side Header(Logo, Menu, Language)
                  Date   : 13/01/2016
                 */
                if (empty($header)) {
                    $header = array();
                }
                echo Modules::run('Sidebar/header', $header);
                ?>
            </div>
            <div class="navbar-inverse side-collapse in" id="side-collapse">
<?php
/*
  Author : Rupesh Jorkar(RJ)
  Desc   : Call Left Menu area
  Input  : Bunch of array
  Output : Top Side Header
  Date   : 13/01/2016
 */
if (empty($leftmenu)) {
    $leftmenu = array();
}
echo Modules::run('Sidebar/leftmenu', $leftmenu);
?>
            </div>

            <!--/.nav-collapse -->
            <div class="clr"></div>
            <!--</div> RJ This div not started at top any reason need to discuss-->
        </nav>
        <!------------------->
        <nav class="navbar navbar-default navbar-mobile">
           <div class="menu-whitebg2"> <div class="container">
<?php
/*
  Author : Rupesh Jorkar(RJ)
  Desc   : Call Mobile Menu and Header
  Input  : Bunch of array
  Output : Top Side Header and Menu
  Date   : 18/01/2016
 */
if (empty($mobileheader)) {
    $mobileheader = array();
}
echo Modules::run('Sidebar/mobileheader', $mobileheader);
?>
            </div> </div>
        </nav>
        <!-- /.navbar-collapse -->
         <div class="clr pad-tb6"></div>
        <div class="container">
            <!-- Example row of columns -->
<?php
/*
  Author : Rupesh Jorkar(RJ)
  Desc   : Call Page Content Area
  Input  : View Page Name and Bunch of array
  Output : View Page
  Date   : 11/01/2016
 */
$this->load->view($main_content);
?>
        </div>
            <?php
            /*
              Author : Maulik Suthar
              Desc   : Call Footer Area
              Input  :
              Output : Footer Area( Menu, Content)
              Date   : 06/02/2016
             */
            echo Modules::run('Sidebar/footer');
            ?>


<!-- /container -->
<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?=base_url()?>uploads/assets/js/ie10-viewport-bug-workaround.js"></script>
<script src="<?=base_url()?>uploads/dist/js/datatables.min.js"></script>
<script src="<?=base_url()?>uploads/dist/js/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>uploads/custom/js/bootstrap-toggle.js"></script>
<link href="<?=base_url()?>uploads/custom/css/bootstrap-toggle.css" rel="stylesheet">
<script type="text/javascript">

    $(document).ready(function() {
	/*$('#datepicker').datepicker();
		$('#datepicker, #datepicker1,#datepicker2, #datepicker3,#datepicker4, #datepicker5').datepicker();

	$('#datatable, #datatable1, #datatable2, #datatable3, #datatable4, #datatable5').DataTable();

	var sideslider = $('[data-toggle=collapse-side]');
            var sel = sideslider.attr('data-target');
            var sel2 = sideslider.attr('data-target-2');
            sideslider.click(function(event){
                $(sel).toggleClass('in');
                $(sel2).toggleClass('out');
            });
*/
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});

   </script>


<?php
	/*
	  Author : Rupesh Jorkar(RJ)
	  Desc   : Unset Error Message Variable for all Form
	  Input  :
	  Output : Unset Error Session
	  Date   : 18/01/2016
	 */
	echo Modules::run('Sidebar/unseterror');
	?>
</body>

</html>
