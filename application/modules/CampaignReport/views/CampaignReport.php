<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$redirect_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REDIRECT_URL'];
$this->load->view('CampaignReport/CampaignReportAjax.php', '', true);
?>

<div class="row">
  <div class="col-md-6 col-sm-6">
    <ul class="breadcrumb nobreadcrumb-bg">
      <li><a href="<?php echo base_url(); ?>Dashboard">
        <?= lang('crm') ?>
        </a></li>
      <li><a href="<?php echo base_url(); ?>Marketingcampaign">
        <?= lang('marketing_campaigns') ?>
        </a></li>
      <li class="active">
        <?= lang('CR_ACTIVE_CAMPAIGN_OVERVIEW') ?>
      </li>
    </ul>
  </div>
  <!-- Search: Start -->
  <div class="col-xs-12 col-lg-3 col-sm-6 col-md-6  pull-right text-right ">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
        <div class="navbar-form navbar-left pull-right" id="searchForm">
          <div class="input-group">
            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
            <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR'); ?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
            <span class="input-group-btn">
            <button onclick="data_search('changesearch')" class="btn btn-default" title="<?php echo lang('search'); ?>" type="button"><i class="fa fa-search fa-x"></i></button>
            &nbsp;
            <button class="btn btn-default" title="<?php echo lang('reset'); ?>" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
          <!-- /input-group --> 
        </div>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <!-- Search: End -->
  <div class="clr"></div>
  <div class='alert alert-success text-center show-success  hidden'></div>
  <?php if ($this->session->flashdata('msg')) { ?>
  <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('msg'); ?>
    <?php } ?>
  </div>
  <?php if ($this->session->flashdata('error')) { ?>
  <div class='alert alert-danger text-center'><?php echo $this->session->flashdata('error'); ?>
    <?php } ?>
  </div>
  <div class="clr"></div>
</div>
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-4">
          <h3 class="white-link">
            <?= lang('SELECT_ONE_OR_MULTIPLE_REPORT') ?>
          </h3>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-8">
          <div class="row">
            <div class="col-md-4 col-lg-2  pull-right text-right filtr-btn">
              <button class="btn btn-white" type="button" name="btn_type_of_rep" id="btn_type_of_rep" onclick="getDateByFilter();">
              <?= lang('CR_FILTER_BTN') ?>
              </button>
              <button class="btn btn-white" onclick="reset_date_data()" title="<?php echo lang('reset') ?>"> <i class="fa fa-refresh fa-x"></i> </button>
            </div>
            <div class="col-xs-7 form-group col-sm-4 col-md-8 col-lg-5 font-white  text-right txt-left-resp togl-btn">
             <div class="col-sm-6 bd-form-group togl-auto"><label> <?= lang('use_archive_camp') ?></label></div>
             <div class="pull-left"> <input data-toggle="toggle"  data-onstyle="success" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" onchange="getArchiveCampaignData(this);" type="checkbox" class="archive_camp" name="archive_camp" id="archive_camp"></div>
            </div>
            <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 visible-lg font-white">
              <div class="form-group pad-top10 text-right">
                <label>
                  <?= lang('CR_DATE') ?>
                </label>
              </div>
            </div>
            <div class="col-xs-6 col-sm-2 col-md-6 col-lg-2">
              <div class="form-group">
                <div class='input-group date search_creation_date' >
                  <input type="text" class="form-control searchAjax search_creation_date" placeholder="<?= lang('CR_START_DATE') ?>" id="search_creation_date" name="search_creation_date" onkeydown="return false">
                  <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
              </div>
            </div>
            <div class="col-xs-6 col-sm-2 col-md-6 col-lg-2">
              <div class="form-group">
                <div class='input-group date creation_end_date' >
                  <input type="text" class="form-control searchAjax creation_end_date" placeholder="<?= lang('CR_END_DATE') ?>" id="creation_end_date" name="creation_end_date" onkeydown="return false">
                  <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
              </div>
            </div>
            <div class="clr"></div>
          </div>
          <div class="clr"></div>
        </div>
        <div class="clr"></div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
      
      <!-- Listing of Product Table: Start -->
      <div  class="whitebox" id="common_div">
        <?php $this->load->view('CampaignReportAjax'); ?>
      </div>
      <!-- Listing of Product Table: End -->
      
      <div class="clr"></div>
    </div>
    <div class="col-xs-12 col-md-12 col-sm-12 col-lg-12">
      <div class="row">
        <div class="text-center">
         
             <a data-href="javascript:;" onclick="generate_pdf();" class="btn btn-white " ><span class="glyphicon glyphicon-cloud-download"></span>
              <?= lang('GENERATE_PDF') ?>
              </a> 
         
         
            <a data-href="javascript:;" onclick="generate_csv();" class="btn btn-white " ><span class="glyphicon glyphicon-cloud-download"></span>
              <?= lang('GENERATE_CSV') ?>
              </a>
         
          <div class="clr"></div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 pad-b6">
          <div class="row">
            <div class="col-xs-12 col-md-4 col-sm-4 widthauto">
              <div class="text-center">
                <?php if (checkPermission('Marketingcampaign', 'add')) { ?>
                <a data-href="<?php echo base_url() . 'Marketingcampaign/add_record'; ?>" data-toggle="ajaxModal" title="<?= lang('create') ?>" class="btn btn-white full-width" >
                <?= $this->lang->line('CREATE_MARKETING_CAMPAING') ?>
                </a>
                <?php } ?>
              </div>
            </div>
            <div class="col-md-4  col-xs-12 col-sm-4 btn-20">
              <div class="text-center"> <a href="<?php echo base_url() . 'ManageCampaigns'; ?>" class="btn btn-white full-width">
                <?= lang('MANAGE_CAMPAIGN') ?>
                </a> </div>
            </div>
            <div class="col-md-4  col-xs-12 col-sm-4 btn-20">
              <div class="text-center"> <a href="<?php echo base_url() . 'RequestBudget'; ?>" class="btn btn-white full-width">
                <?= lang('request_budget') ?>
                </a> </div>
            </div>
            <div class="col-md-4  col-xs-12 col-sm-4 btn-20">
              <div class="text-center"> <a href="" class="btn btn-white full-width">
                <?= lang('generate_report') ?>
                </a> </div>
            </div>
            <!-- by brt-->
            <div class="col-md-4  col-xs-12 col-sm-4 btn-20">
              <div class="text-center">
                <?php  if(checkPermission('Campaignarchive','view')){ ?>
                <a href="<?php echo base_url().'Campaignarchive';?>" class="btn btn-white full-width"><?=lang('campaign_archive') ?></a>
                <?php }?>
              </div>
            </div>
            <!-- brt ends-->
           <!--  <div class="col-md-3  col-xs-12 col-new-5th no-right-pad"> 
              
              <div class="text-center">
                                <button class="btn btn-white full-width" data-type="danger" title="Campaign archive" onclick="activat_campaign_archive('<?php echo $redirect_link; ?>')">
                                    <?= lang('CAMPAIGN_ACRVHIVE') ?>
                                </button>
								
            </div>--> 
             <div class="clr"></div>
          </div>
         
        </div>
        <div class="col-md-3 col-xs-12"><?php echo Modules::run('Sidebar/taskCalendar'); ?> </div>
        <div class="clr"></div>
      </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
<div class="clr"></div>
</div>

<!-- Script for ajaxModel --> 


