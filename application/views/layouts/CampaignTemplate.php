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
                  Author : Ritesh Rana
                  Desc   : Call Header area
                  Input  : Bunch of Array
                  Output : Top Side Header(Logo, Menu, Language)
                  Date   : 22/01/2016
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
  Author : Ritesh Rana
  Desc   : Call Left Menu area
  Input  : Bunch of array
  Output : Top Side Header
  Date   : 22/01/2016
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
  Author : Ritesh Rana
  Desc   : Call Mobile Menu and Header
  Input  : Bunch of array
  Output : Top Side Header and Menu
  Date   : 22/01/2016
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
  Author : Ritesh Rana
  Desc   : Call Page Content Area
  Input  : View Page Name and Bunch of array
  Output : View Page
  Date   : 22/01/2016
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

        <?php echo Modules::run('Sidebar/unseterror');?>
        <?=$this->load->view('/Common/common','',true);?>


        <!-- START this JS is for marketing campaign page - /blazedeskcrm/Marketingcampaign -->
        <script>
            var linkedin_api_key = '<?php echo $linkedin_api_key; ?>';
            var linkedin_company_id = '<?php echo $linkedin_company_id; ?>';
            var ajax_loader ='<?php echo base_url()."uploads/images/ajax-loader.gif"; ?>';
            var grantview = '<?= base_url("Marketingcampaign/grantview") ?>';
            var delete_url = "<?php echo $this->config->item('base_url').'/'.$this->viewname.'/deletedata';?>";
            var delete_meg ="<?php echo $this->lang->line('DELETE_MARKETING_CAMPAING_MESSGE');?>";
            var Information = '<?php echo $this->lang->line('Information');?>';
            var COMMON_LABEL_CANCEL ='<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>';
            var ok ='<?php echo $this->lang->line('ok');?>';
            var campign_del_msg = '<?php echo $this->lang->line('campign_del_msg');?>';
            var date_formate='<?= date("Y-m-d") ?>';
            var generateFormToken = '<?php echo generateFormToken();?>';

            var Campaigngroup_delete_msg ="<?php echo $this->lang->line('DELETE_CAMPAING_GROUP_MESSGE')."<br/><br/>".lang('CONFIRM_DELETE_CAMPAIGN');?>";
            var index_url= '<?php echo $this->config->item('base_url').'/'.$this->viewname?>/index';

            <?php
                $Marketingcampaign_url = explode('/',$_SERVER['REDIRECT_URL']);
        ?>
             var brand_engagement = '<?php echo $Marketingcampaign_url[2];?>';

        </script>
        <!-- 75qdwdkxx05h1n -->
        <?php
        if(isset($linkedin_api_key) != '' &&  isset($linkedin_company_id) != '')
        {
            printf('<script src="//platform.linkedin.com/in.js" type="text/javascript">
    api_key: %s
    onLoad: onLinkedInLoad
    authorize: true
    </script>',
                $linkedin_api_key
            );
        }
        ?>
        <script src="<?= base_url() ?>uploads/custom/js/Marketingcampaign/Marketingcampaign.js"></script>

        <!-- END this JS is for marketing campaign page - /blazedeskcrm/Marketingcampaign -->
    </body>
</html>
