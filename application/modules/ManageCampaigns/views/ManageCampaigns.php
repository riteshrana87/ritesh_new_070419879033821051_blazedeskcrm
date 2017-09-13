<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url();?>"><?= lang('crm') ?></a></li>
            <li><a href="<?php echo base_url().'Marketingcampaign';?>"><?= lang('marketing_campaigns') ?></a></li>
            <li class="active"><?= lang('MANAGE_CAMPAIGN') ?></li>
        </ul>
    </div>
   <!-- Search: Start -->
  <div class="col-xs-12 col-md-3 col-lg-3 col-sm-6 pull-right text-right col-md-offset-3">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang("settings"); ?>" ><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
        <!--<form class="navbar-form navbar-left pull-right" id="searchForm">-->
          <div class="navbar-form navbar-left pull-right" id="searchForm">
          <div class="input-group">
            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                    <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="<?=$this->lang->line('EST_LISTING_SEARCH_FOR')?>" aria-controls="example1" placeholder="Search" value="<?=!empty($searchtext)?$searchtext:''?>">
            <span class="input-group-btn">
             <button onclick="data_search('changesearch')" class="btn btn-default"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i></button>
             <button class="btn btn-default howler flt" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
          </div>

          <!-- /input-group -->
       <!-- </form>-->
      </div>
     
       <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <!-- Search: End -->
    <div class="clr"></div>
    <div class='alert alert-success text-center show-success  hidden'></div>

    <?php if($this->session->flashdata('msg')){ ?>
        <div class='alert alert-success text-center'><?php  echo $this->session->flashdata('msg'); ?></div>
    <?php } ?>
    <?php if($this->session->flashdata('error')){ ?>
        <div class='alert alert-danger text-center'><?php echo $this->session->flashdata('error'); ?></div>
    <?php } ?>
    <div class="clearfix visible-xs-block"></div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-md-6 col-sm-6">
                <h3 class="white-link"><?= lang('ACTIVE_CAMPAIGN_OVERVIEW') ?></h3>
           </div>
            <div class="col-xs-12 col-md-6 col-sm-6 pull-right">
                <a href="<?php echo base_url().'Campaigngroup';?>" class="btn btn-white pull-right" ><?=$this->lang->line('CAMPAIGN_GROUP')?></a>
            </div>
                 <div class="clr"></div>
        </div>
        <div class="whitebox" id="common_div">
            <?=$this->load->view('ManageCampaigns/ajax_list.php','',true);?>
            <div class="clr"></div>
        </div>
          <div class="clr"></div>
          <div class="row">
            <?php  if(checkPermission('Marketingcampaign','add')){ ?>
            <div class="col-md-2 widthauto col-xs-12 col-new-5th">
                <div class="text-center">
                   <a data-href="<?php echo base_url().'Marketingcampaign/add_record';?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-white full-width" ><?=$this->lang->line('CREATE_MARKETING_CAMPAING')?></a>
                </div>
            </div>
            <?php }?>
              
            <?php  if(checkPermission('ManageCampaigns','view')){ ?>
            <div class="col-md-2  col-xs-12 col-new-5th">
                <div class="text-center">
                    <a href="<?php echo base_url().'ManageCampaigns';?>" class="btn btn-white full-width"><?= lang('MANAGE_CAMPAIGN') ?></a>
                </div>
            </div>
            <?php }?>
              
            <?php  if(checkPermission('RequestBudget','view')){ ?>  
            <div class="col-md-2  col-xs-12 col-new-5th">
                <div class="text-center">
                    <a href="<?php echo base_url().'RequestBudget';?>" class="btn btn-white full-width"><?= lang('request_budget') ?></a>
                </div>
            </div>
            <?php }?>
              
            <div class="col-md-2  col-xs-12 col-new-5th">
                <div class="text-center">
                    <?php  if(checkPermission('CampaignReport','view')){ ?>
                    <a href="<?php echo base_url().'CampaignReport';?>" class="btn btn-white full-width"><?= lang('generate_report') ?></a>
                    <?php }?>
                </div>
            </div>
              
            <?php  if(checkPermission('Campaignarchive','view')){ ?>
            <div class="col-md-3  col-xs-12 col-new-5th no-right-pad">
                <div class="text-center">
                   <a href="<?php echo base_url().'Campaignarchive';?>" class="btn btn-white full-width"><?= lang('CAMPAIGN_ACRVHIVE') ?></a>
                </div>
            </div>
            <?php }?>
              
            <?php 
            $newsleeterType = get_newsletter_type();
            if((checkPermission('Newsletter','view')) && ($newsleeterType == '1' || $newsleeterType == '2'  || $newsleeterType == '3'  || $newsleeterType == '4')) 
            {  
                
                $type = '';
                if($newsleeterType == '1')
                {
                    $type = "(".lang('NEWSLETTER_TYPE_MAILCHIMP').")";
                }else if($newsleeterType == '2')
                {
                    $type = "(".lang('NEWSLETTER_TYPE_CAMPAIGN_MONITOR').")";
                }else if($newsleeterType == '3')
                {
                    $type = "(".lang('NEWSLETTER_TYPE_MOOSEND').")";
                }else if($newsleeterType == '4')
                {
                    $type = "(".lang('NEWSLETTER_TYPE_GET_RESPONSE').")";
                }
            ?>
            <div class="col-md-4 col-lg-3 col-new-5th col-xs-12">
                <div class="text-center">
                    <?php  if(checkPermission('Newsletter','view')){ ?>
                    <a href="<?php echo base_url() . 'Newsletter'; ?>" class="btn btn-white full-width"><?= lang ('NEWSLETTER')." ".$type; ?></a>
                    <?php }?>
                </div>
            </div>
              
            <?php } ?>
        </div>
    </div>

</div>

