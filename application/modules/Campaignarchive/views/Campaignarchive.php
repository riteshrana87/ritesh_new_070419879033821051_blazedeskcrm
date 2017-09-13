<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url();?>"><?= lang('crm') ?></a></li>

            <li>
                <?php  if(checkPermission('Marketingcampaign','view')){ ?>
                <a href="<?php echo base_url().'Marketingcampaign';?>"><?= lang('marketing_campaigns') ?></a>
                <?php }?>
            </li>
            <li class="active"><?= lang('CAMPAIGN_ACRVHIVE') ?></li>
        </ul>
    </div>
   <!-- Search: Start -->
  <div class="col-xs-12 col-md-3 col-lg-3 col-sm-6 pull-right text-right col-md-offset-3">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
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
            <div class="col-xs-12 col-md-6 col-sm-5">

                <h3 class="white-link"><?= lang('CAMPAIGN_ACRVHIVE') ?></h3>
           </div>
            <div class="col-xs-12 col-md-6 col-sm-7 text-right">
                <?php  if(checkPermission('Campaignarchive','add')){ ?>
                <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>

                <button class="btn btn-white" data-type="danger" title="Campaign archive" onclick="activat_campaign_archive('<?php echo $redirect_link;?>')"><?= lang('CAMPAIGN_ACRVHIVE') ?></button>
                <?php }?>
                &nbsp;&nbsp;
                <?php  if(checkPermission('ArchiveCampaign','view')){ ?>
                <a href="<?php echo base_url().'ArchiveCampaign';?>" class="btn btn-white" ><?=$this->lang->line('list_of_archive_campaign')?>
                </a>
                <?php }?>

            </div>
                 <div class="clr"></div>
        </div>
        <div class="whitebox" id="common_div">
            <?=$this->load->view('Campaignarchive/ajax_list.php','',true);?>
            <div class="clr"></div>
        </div>
          <div class="clr"></div>
          <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 resp-widthauto">
                <div class="text-center">
                    <?php  if(checkPermission('Marketingcampaign','add')){ ?><a data-href="<?php echo base_url().'Marketingcampaign/add_record';?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="" class="btn btn-white full-width" ><?=$this->lang->line('CREATE_MARKETING_CAMPAING')?></a><?php }?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 resp-widthauto">
                <div class="text-center">
                    <?php  if(checkPermission('ManageCampaigns','view')){ ?>
                    <a href="<?php echo base_url().'ManageCampaigns';?>" class="btn btn-white full-width"><?= lang('MANAGE_CAMPAIGN') ?></a>
                    <?php }?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 resp-widthauto">
                <div class="text-center">
                    <?php  if(checkPermission('RequestBudget','view')){ ?>
                    <a href="<?php echo base_url().'RequestBudget';?>" class="btn btn-white full-width"><?= lang('request_budget') ?></a>
                    <?php }?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 resp-widthauto">
                <div class="text-center">
                    <?php  if(checkPermission('CampaignReport','view')){ ?>
                    <a href="<?php echo base_url().'CampaignReport';?>" class="btn btn-white full-width"><?= lang('generate_report') ?></a>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>

</div>