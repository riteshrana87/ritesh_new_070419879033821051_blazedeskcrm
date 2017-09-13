<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url();?>"><?= lang('crm') ?></a></li>
            <li>
                <?php  if(checkPermission('Marketingcampaign','view')){ ?>
                <a href="<?php echo base_url().'Marketingcampaign';?>"><?= lang('marketing_campaigns') ?></a>
                <?php }?>
            </li>
            <li>
                <?php  if(checkPermission('Campaignarchive','view')){ ?>
            <a href="<?php echo base_url().'Campaignarchive';?>"><?= lang('CAMPAIGN_ACRVHIVE') ?></a>
                <?php }?>
            </li>
            <li class="active"><?= lang('archive_campaign')?></li>
        </ul>
    </div>
   <!-- Search: Start -->
  <div class="col-xs-12 col-md-3 col-lg-3 col-sm-6 pull-right text-right col-md-offset-3">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
        <!--<form class="navbar-form navbar-left pull-right" id="searchForm" method="post">-->
          <div class="navbar-form navbar-left pull-right" id="searchForm">
          <div class="input-group">
            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                    <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="<?=$this->lang->line('EST_LISTING_SEARCH_FOR')?>" aria-controls="example1" placeholder="Search" value="<?=!empty($searchtext)?$searchtext:''?>">
            <span class="input-group-btn">
             <button onclick="data_search('changesearch')" class="btn btn-default"  title="<?=$this->lang->line('search')?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i></button>
             <button class="btn btn-default howler flt" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
          </div>
      <!--  </form>-->
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
            <div class="col-xs-12 col-md-6">
                <h3 class="white-link"><?= lang('archive_campaign')?></h3>
           </div>
                 <div class="clr"></div>
        </div>

        <div class="whitebox" id="common_div">
            <?=$this->load->view('ArchiveCampaign/ajax_list.php','',true);?>
            <div class="clr"></div>
        </div>
          <div class="clr"></div>

    </div>

</div>
