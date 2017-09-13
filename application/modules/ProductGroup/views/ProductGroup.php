<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = !empty($editRecord)?'updatedata':'insertdata'; 

$path = $crnt_view.'/'.$formAction;

$this->load->view('ProductGroup/ProductGroupajax.php','',true);
?>

  <!-- Example row of columns -->
<div class="row"> 
    <!-- BreadCrumb: Start -->
    <div class="col-sm-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url();?>Dashboard"><?= lang('crm') ?></a></li>
            <li><a href="<?php echo base_url();?>Product"><?= lang('products') ?></a></li>
            <li class="active"><?= lang('product_group') ?></li>
        </ul>
    </div>
    <!-- BreadCrumb: End -->
    
    <!-- Search: Start -->
  <div class="col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
        <div class="navbar-form navbar-left pull-right" id="searchForm">
          <div class="input-group">
            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
            <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR'); ?>" value="<?=!empty($searchtext)?$searchtext:''?>">
            <span class="input-group-btn">
            <button onclick="data_search('changesearch')" class="btn btn-default" title="<?php echo lang('search') ?>" type="button"><i class="fa fa-search fa-x"></i></button>&nbsp;
             
             <button class="btn btn-default" title="<?php echo lang('reset') ?>" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
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
    <div class="row">
        <div class="col-xs-6 col-md-6">
            <h3 class="white-link"><?= lang('product_group')?></h3> 
        </div>    
        
        <div class="col-xs-6 col-md-2 pull-right">
        <?php if(checkPermission('ProductGroup','add')){?>     
            <a data-href="<?php echo base_url($crnt_view.'/AddEditProductGroup');?>" data-toggle="ajaxModal" class="btn btn-white pull-right"><?= lang('create_product_group')?></a>
	<?php } ?>
        </div>
         <div class="clr"></div>
    </div>  
    <div class="clr"></div>
    <?php echo $this->session->flashdata('msg'); ?>
    
    <!-- Listing of Product Table: Start -->
    <div  class="whitebox" id="common_div">
        <?php $this->load->view('ProductGroupajax');?>
    </div>
    <!-- Listing of Product Table: End -->

</div>

<!-- Script for ajaxModel -->
<script>
    $(document).ready(function () {
        $('body').delegate('[data-toggle="ajaxModal"]', 'click', function (e) {
                $('#ajaxModal').remove();
                e.preventDefault();
                var $this = $(this)
                        , $remote = $this.data('remote') || $this.attr('data-href')
                        , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                $('body').append($modal);
                $modal.modal();
                $modal.load($remote);
        });
    });
</script>

<?=$this->load->view('/Common/common','',true);?>
