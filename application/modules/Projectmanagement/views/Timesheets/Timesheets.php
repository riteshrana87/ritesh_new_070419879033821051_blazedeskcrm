<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="main-page">
    <div class="row">
        <div class="col-xs-6 col-md-6 no-left-pad">
            <h3 class="white-link"><?= lang('timesheets') ?></h3> 
        </div>
        <div class="col-xs-6 col-md-6 pull-right no-right-pad">
            <?php if (checkPermission('Timesheets', 'add')) { ?><a data-href="<?php echo base_url() . 'Projectmanagement/Timesheets/add_record'; ?>" data-toggle="ajaxModal" title="<?= lang('create') ?> <?=lang('timesheet')?>" class="btn btn-white pull-right add_record" ><?=lang('create_timesheet')?></a><?php } ?>
        </div>
    </div>
    <div class="clr"></div>
    <?php echo $this->session->flashdata('msg'); ?>
    <div class="clr"></div>
    <div class="whitebox" id="common_div">

        <?php $this->load->view('TimesheetsAjaxList') ?>
    </div>
    <div class="clr"></div>

</div>
</div>  

<div class="clr"></div>
