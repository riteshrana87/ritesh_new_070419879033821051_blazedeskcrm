<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="<?= base_url() ?>uploads/dist/js/bootstrap-datepicker.js"></script>

<link id="bsdp-css" href="<?= base_url() ?>uploads/dist/css/bootstrap-datepicker3.css" rel="stylesheet">

	<div class="row">
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
            <ul class="breadcrumb nobreadcrumb-bg">
                <li><a href="<?php echo base_url();?>"><?= lang('crm') ?></a></li>
                <li><a href="<?php echo base_url().'Marketingcampaign';?>"><?= lang('marketing_campaigns') ?></a></li>
                <li class="active"><?= lang('request_budget') ?></li>
            </ul>
        </div>
        <div class="col-xs-12 col-md-3 col-lg-3 col-sm-6 pull-right text-right col-md-offset-3">
            <div class="row">
                <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
                <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                    <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                <input type="text" name="searchtext" id="searchtext" class="form-control" aria-controls="example1" placeholder="<?= lang('EST_LISTING_SEARCH_FOR') ?>" value="<?=!empty($searchtext)?$searchtext:''?>">
            <span class="input-group-btn">
             <button onclick="data_search('changesearch')" class="btn btn-default"  title="<?=$this->lang->line('search')?>"> <i class="fa fa-search fa-x"></i></button>
             <button class="btn btn-default howler flt" onclick="reset_data()" title="<?=$this->lang->line('reset')?>"><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
        </div>


                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
    <div class="clr"></div>
</div>
 <div class="clr"></div>
        <?php echo $this->session->flashdata('msg'); ?>
	<div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
          <h3 class="white-link"><?= lang('request_budget') ?>
          </h3>
        </div>
         <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-sm-offset-3 col-md-offset-4 text-right">
             <?php if(checkPermission('RequestBudget','add')){ ?>
            <a data-href="<?php echo base_url('RequestBudget/create_campaign'); ?>" class="btn btn-white"  data-refresh="true" aria-hidden="true" data-toggle="ajaxModal"><?= lang('add_request_budget') ?></a>
             <?php }?>
        </div>
    <div class="clr"></div>
</div>
 <div class="clr"></div>
<div class="row">
    <div class="col-xs-12 col-md-12">
       
        <div class="whitebox" id="common_div">
            <?=$this->load->view('RequestBudget/ajax_list.php','',true);?>
            <div class="clr"></div>

        </div>
        <div class="clr"></div>
    </div>
</div>



