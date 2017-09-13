<?php
defined ('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?= base_url() ?>"><?= lang('crm') ?></a></li>
            <li class="active"><?= lang ('settings') ?></li>
            <li class="active"><?= lang ('CASES_TYPE_NAME') ?></li>
        </ul>
    </div>
    <!-- Search: Start -->
    <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>">
                    <i class="fa fa-gear fa-2x"></i></a></div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <form id="searchForm" class="navbar-form navbar-left pull-right">
                    <div class="input-group">
                        <input type="text" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR');?>" name="search_input" id="search_input"
                               class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" id="submit" name="submit" title="<?php echo lang('search');?>" class="btn btn-default"><i
                                class="fa fa-search fa-x"></i></button>
                        <button onclick="reset_data();" title="<?php echo lang('reset');?>" class="btn btn-default" type="reset"><i
                                class="fa fa-refresh fa-x"></i></button>
                    </span></div>
                    <!-- /input-group -->
                </form>
            </div>

        </div>

    </div>
</div>
<div class="clr"></div>
<?php echo $this->session->flashdata ('msg'); ?>
<div class="clr"></div>
<div class="row">
    <div class="col-xs-6 col-md-6 no-left-pad">
        <h3 class="white-link"><?= lang ('CASES_TYPE_NAME') ?></h3>
    </div>
    <div class="col-xs-6 col-md-6 pull-right no-right-pad">
        <?php if (checkPermission ('CasesType', 'add')) { ?><a
            data-href="<?= base_url ('CasesType/add_record') ?>" data-toggle="ajaxModal"
            title="<?= lang ('create') ?>"
            class="btn btn-white pull-right" ><?= $this->lang->line ('CREATE_CASE_TYPE') ?></a><?php } ?>
    </div>
</div>

<div class="whitebox" id="common_div">

    <?php $this->load->view ('CasesTypeAjaxList') ?>
</div>
<div class="clr"></div>


</div>

