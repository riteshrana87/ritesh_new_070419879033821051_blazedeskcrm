<?php
defined ('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?= base_url ('Projectmanagement/Projectdashboard') ?>"><?= lang ('project_management') ?></a>
            </li>
            <li><?= lang ('manage_projects') ?></li>
        </ul>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 pad-tb6text-right puul-right">
        
        <div class="pull-right settings"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a></div>
        <div class="pull-right search-top">

            <form id="searchForm" class="navbar-form navbar-left pull-right">
                <div class="input-group">
                    <input type="text" placeholder="<?= lang('EST_LISTING_SEARCH_FOR') ?>" name="search_input" id="search_input"
                           class="form-control">
                        <span class="input-group-btn">
                            <button type="submit" id="submit" name="submit" class="btn btn-default" title="<?= $this->lang->line('search') ?>"><i
                                    class="fa fa-search fa-x"></i></button>
                            <button onclick="reset_data();" class="btn btn-default" type="reset" title="<?= $this->lang->line('reset') ?>"><i
                                    class="fa fa-refresh fa-x"></i></button>
                        </span></div>
                <!-- /input-group -->
            </form>

        </div>

    </div>
</div>
<div class="clr"></div>
<?php echo $this->session->flashdata ('msg'); ?>
<div class="row">
    <div class="col-xs-6 col-md-6 no-left-pad">
        <h3 class="white-link"><?= lang ('projects') ?></h3>
    </div>
    <div class="col-xs-6 col-md-6 pull-right no-right-pad">
        <?php if (checkPermission ('Projectmanagement', 'add')) { ?><a
            data-href="<?php echo base_url () . 'Projectmanagement/add_record'; ?>" data-toggle="ajaxModal"
            title="<?= lang ('create') ?>"
            class="btn btn-white pull-right" ><?= $this->lang->line ('create_project') ?></a><?php } ?>
    </div>
</div>


<div class="whitebox" id="common_div">
    <?php $this->load->view ('ProjectsAjaxList') ?>
</div>


<div class="clr"></div>
