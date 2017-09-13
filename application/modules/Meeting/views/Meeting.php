<?php
defined ('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
           
          
            <li class="active"><?= lang ('OWNER_MEETING') ?></li>
        </ul>
    </div>
    <!-- Search: Start -->
    <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i
                        class="fa fa-gear fa-2x"></i></a></div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <form id="searchForm" class="navbar-form navbar-left pull-right">
                    <div class="input-group">
                        <input type="text" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR');?>" name="search_input" id="search_input"
                               class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" id="submit" name="submit" class="btn btn-default" title="<?php echo lang('search');?>"><i
                                class="fa fa-search fa-x"></i></button>
                        <button onclick="reset_data();" class="btn btn-default" type="reset" title="<?php echo lang('reset');?>"><i
                                class="fa fa-refresh fa-x"></i></button>
                    </span></div>
                    <!-- /input-group -->
                </form>
            </div>

        </div>

    </div>
</div>
<div class="clr"></div>
<?php if ($this->session->flashdata('message')) { ?>
        <div class='alert alert-success text-center' id=""> <?php echo $this->session->flashdata('message'); ?></div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
        <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
    <?php } ?>
<div class="clr"></div>
<div class="row">
    <div class="col-xs-6 col-md-6 no-left-pad">
        <h3 class="white-link"><?= lang ('OWNER_MEETING') ?></h3>
    </div>
   
</div>

<div class="whitebox" id="common_div">

    <?php $this->load->view ('MeetingAjaxList') ?>
</div>
<div class="clr"></div>


</div>

