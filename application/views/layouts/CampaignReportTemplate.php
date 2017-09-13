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
            <div class="menu-whitebg"><div class="container">
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
            <div class="menu-whitebg2"><div class="container">
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
                </div>  </div>
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

        <!-- /container --> 
        <!-- Bootstrap core JavaScript
            ================================================== --> 
        <link id="bsdp-css" href="<?= base_url() ?>uploads/dist/css/bootstrap-datepicker3.css" rel="stylesheet">
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
        <script type="text/javascript" src="<?php echo base_url(); ?>uploads/custom/js/CampaignReport/CampaignReport.js"></script>

        <?php
        /*
          Author : Ritesh Rana
          Desc   : Call Footer Area
          Input  :
          Output : Footer Area( Menu, Content)
          Date   : 28/07/2016
         */
        echo Modules::run('Sidebar/footer');
        ?>
<script>
        var date_formate='<?= date("Y-m-d") ?>';
        var grantview = '<?= base_url("Marketingcampaign/grantview") ?>';
        var generate_pdf_msg = "<?php echo lang('generate_pdf_msg'); ?>";
        var generate_csv_msg = "<?php echo lang('generate_csv_msg'); ?>";
        var close = "<?php echo lang('close'); ?>";
        var campaign_report = "<?php echo lang('CR_CAMPAIGN_REPORT'); ?>";
        var url = "<?php echo base_url() . 'Campaignarchive/campaign_archive'; ?>";
        var boxes = $('input[name="check[]"]:checked');
        var select_both_date = '<?php echo lang('select_both_date'); ?>';
        var close = '<?php echo lang('close'); ?>';
        var url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index';
        var url_data = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>';
        var generateFormToken = '<?php echo generateFormToken();?>';
</script>
    </body>
</html>
