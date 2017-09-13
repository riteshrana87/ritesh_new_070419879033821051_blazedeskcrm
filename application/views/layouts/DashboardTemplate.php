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
            <div class="menu-whitebg">
                <div class="container">
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
        <?php
        /*
          Author : Maulik Suthar
          Desc   : common code for the task calender
          Input  :
          Output : View Page
          Date   : 23/02/2016
         */
        ?>
		<script src='<?= base_url() ?>uploads/custom/js/enjoy_hint/enjoyhint.min.js'></script>
		<link href='<?= base_url() ?>uploads/custom/js/enjoy_hint/enjoyhint.css' rel='stylesheet'/>
        <link href='<?= base_url() ?>uploads/custom/css/projectmanagement/fullcalendar.css' rel='stylesheet' />
        <script src='<?= base_url() ?>uploads/custom/js/projectmanagement/moment.js'></script>
        <script src='<?= base_url() ?>uploads/custom/js/projectmanagement/fullcalendar.js'></script>
        <script src="<?php echo base_url() . "uploads/dist/js/bootstrap-datetimepicker.js"; ?>"></script>
        
        <link href='<?= base_url() ?>uploads/dist/css/bootstrap-datetimepicker.min.css' rel='stylesheet' />
        <script src='<?= base_url() ?>uploads/custom/js/jquery.blockUI.js'></script>
        <script>
		
		
            $(document).ready(function () {
            	  				
				
					 $('body').delegate('[data-toggle="ajaxModal"]', 'click',
                           function (e) {
                               $('#ajaxModal').remove();
                               e.preventDefault();
                               var $this = $(this)
                                       , $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href')
                                       , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                               $('body').append($modal);
                               $modal.modal();
                               var url=$remote+"?token=<?php echo generateFormToken();?>";
                      		 //  console.log(url);
                               $modal.load(url);
                           }

                   );

                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,basicWeek,basicDay'
                    },
                    defaultDate: '<?= date("Y-m-d") ?>',
                    defaultView: 'month',
                    yearColumns: 2,
                    selectable: false,
                    selectHelper: true,
                    //firstDay: 0,
                    editable: false,
                    eventLimit: true, // allow "more" link when too many events
                    events: {
                        url: '<?= base_url("SalesOverview/grantview") ?>',
                        success: function (data) {

                        },
                        error: function () {
                            $('#script-warning').show();
                        }}
                    ,
                    eventRender: function (event, element) {
                        element.attr("data-toggle", "ajaxModal")
                    },
                });

            });
            
            $('#btnNext').click(function () {
                $('#calendar').fullCalendar('next');
            });
            $('#btnPrev').click(function () {
                $('#calendar').fullCalendar('prev');
            });

        </script>
        <script type="text/javascript" src="https://js.stripe.com/v1/"></script>
        <?php
        if (isset($js_content) && !empty($js_content)) {
            $this->load->view($js_content);
        }
        ?>
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
        <?php
        /*pr($_SERVER);
        die('here');*/
        if(isset($_SERVER['REDIRECT_URL'])){
        $Salesoverview = explode('/',$_SERVER['REDIRECT_URL']);
       ?>
        <script>
        var dashboardWidgetsOrder = '<?php echo base_url('SalesOverview/dashboardWidgetsOrder'); ?>';
        var Information = '<?php echo $this->lang->line('Information');?>';
        var COMMON_LABEL_CANCEL ='<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>';
        var ok ='<?php echo $this->lang->line('ok');?>';
        var getMonthlySalesData = '<?php echo base_url('Dashboard/getMonthlySalesData'); ?>';
        var monthly_sales_comparision = '<?php echo lang("monthly_sales_comparision"); ?>';
        var monthly_comparision = '<?php echo lang("monthly_comparision"); ?>';
        var upload_file = '<?php echo base_url('SalesOverview'); ?>/upload_file';
        var too_big_size = '<?php echo lang('too_big_size'); ?>';
        var acc_upload_file= '<?php echo base_url('/Account/upload_file');?>';
        var failed_file_upload = '<?php echo lang('failed_file_upload'); ?>';
        var url_data = '<?php echo base_url(); ?>';
        var dashboardWidgetsOrder_url = '<?php echo base_url('Dashboard/dashboardWidgetsOrder'); ?>/?resetWidgets=Yes';
        var deletedataSalesOverview = '<?php echo base_url('Lead/deletedataSalesOverview/?'); ?>';
        var confirm_delete_lead = '<?php echo lang('confirm_delete_lead'); ?>';
        var opp_deletedataSalesOverview = '<?php echo base_url('Opportunity/deletedataSalesOverview/?'); ?>';

        var confirm_delete_opportunity = '<?php echo lang('confirm_delete_opportunity'); ?>';
        var acc_deletedataSalesOverview = '<?php echo base_url('Account/deletedataSalesOverview/?'); ?>';
        var confirm_delete_account = '<?php echo lang('confirm_delete_account'); ?>';
        var confirm_convert_opportunity = '<?php echo lang('confirm_convert_opportunity'); ?>';
        var lead_convert_opportunity_success = '<?php echo lang('lead_convert_opportunity_success'); ?>';
        var close = '<?php echo lang('close'); ?>';
        var confirm_convert_win = '<?php echo lang('confirm_convert_win'); ?>';
        var account_win_convert_msg = '<?php echo lang('account_win_convert_msg'); ?>';
        var confirm_convert_lost = '<?php echo lang('confirm_convert_lost'); ?>';
        var account_lose_convert_msg = '<?php echo lang('account_lose_convert_msg'); ?>';
        </script>
            <?php if(isset($Salesoverview[2]) && $Salesoverview[2] == 'SalesOverview'){ ?>
        <script src="<?= base_url() ?>uploads/custom/js/Salesoverview/SalesOverview.js"></script>
    <?php } }?>


    </body>
</html>
