<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
       
            <?php echo $this->breadcrumbs->show(); ?>
       
    </div>
    <div class="col-xs-12 col-md-3 col-lg-3 col-sm-6 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <div class="input-group">
                    <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                    <input type="text" name="searchtext" id="searchtext" class="form-control" aria-controls="example1" placeholder="<?= lang('EST_LISTING_SEARCH_FOR')?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                    <span class="input-group-btn">
                        <button onclick="data_search('changesearch')" class="btn btn-default"  title="<?php echo lang('search');?>"> <i class="fa fa-search fa-x"></i></button>
                        <button class="btn btn-default howler flt"  onclick="reset_data()" title="<?php echo lang('reset');?>"><i class="fa fa-refresh fa-x"></i></button>
                    </span> </div>
            </div>

            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
</div>
<div id="main-page">
    <?php echo $this->session->flashdata('msg'); ?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 text-right pull-right">
                <?php if (checkPermission('Supplier', 'add')) { ?>
                    <a class="btn btn-white pull-right add_cost " href="<?php echo base_url('Supplier/add'); ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal"><?php echo lang('add_supplier') ?>                       </a>

                <?php } ?>
            </div>
            <!--            <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2 text-right">
                            <div class="btn-group">
                                <button class="btn btn-white"><i class="fa fa-list"></i></button>
                                <button class="btn btn-white"><i class="fa fa-th"></i></button>
                            </div>
                        </div>-->
            <div class="clr"></div>
            <div class="whitebox" id="common_div">

                <?php $this->load->view('AjaxSupplier'); ?>

                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div>
    </div>

</div>
<?= $this->load->view('/Common/common', '', true); ?>
