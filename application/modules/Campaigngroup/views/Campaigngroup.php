<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
  <div class="col-md-6 col-lg-6 col-sm-7">
    <ul class="breadcrumb nobreadcrumb-bg">
        <li><a href="<?php echo base_url();?>"><?= lang('crm') ?></a></li>
        <li><a href="<?php echo base_url().'Marketingcampaign';?>"><?= lang('marketing_campaigns') ?></a></li>
        <li><a href="<?php echo base_url().'ManageCampaigns';?>"><?= lang('MANAGE_CAMPAIGN') ?></a></li>
        <li class="active"><?=$this->lang->line('CAMPAIGN_GROUP')?></li>

    </ul>
  </div>
    <!-- Search: Start -->
<div class="col-xs-12 col-md-3 col-sm-5 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>""><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <!--<form class="navbar-form navbar-left pull-right" id="searchForm" method="post">-->
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                        <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="<?=$this->lang->line('EST_LISTING_SEARCH_FOR')?>" aria-controls="example1" placeholder="Search" value="<?=!empty($searchtext)?$searchtext:''?>">
            <span class="input-group-btn">
             <button onclick="data_search('changesearch')" class="btn btn-default"  title="Search"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i></button>
             <button class="btn btn-default howler flt" title="Reset" onclick="reset_data()" title="Reset"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
                </div>
                <!--</form>-->
            </div>

            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <!-- Search: End -->
    <div class="clr"></div>
    <div class='alert alert-success text-center hidden'></div>
    <?php if($this->session->flashdata('msg')){ ?>
        <div class='alert alert-success text-center'><?php  echo $this->session->flashdata('msg'); ?></div>
    <?php } ?>
    <?php if($this->session->flashdata('error')){ ?>
        <div class='alert alert-danger text-center'><?php echo $this->session->flashdata('error'); ?></div>
    <?php } ?>

  <div class="clr"></div>
    <div class="clearfix visible-xs-block"></div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-6 col-md-6 col-sm-6 col-lg-6">
                <h3 class="white-link"><?=$this->lang->line('CAMPAIGN_GROUP')?></h3>
            </div>
            <div class="col-xs-6 col-md-6 col-sm-6 col-lg-6 pull-right">
                <?php  if(checkPermission('Campaigngroup','add')){ ?><a data-href="<?php echo base_url().'Campaigngroup/add_record';?>"  aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?=lang('create')?>" class="btn btn-white pull-right" ><?=$this->lang->line('CREATE_CAMPAIGN_GROUP')?></a><?php }?>
            </div>
            <div class="clr"></div>
        </div>




    <div class="whitebox" id="common_div">
      <?=$this->load->view('Campaigngroup/ajax_list.php','',true);?>
  </div>





