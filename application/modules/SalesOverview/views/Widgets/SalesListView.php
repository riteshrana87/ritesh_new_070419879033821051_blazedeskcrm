<div  class="connectedSortable" style="min-height:300px">
    <div class="sortableDiv" id="SalesListView">
        <div class="col-xs-12 col-md-12">
            <?php if ($this->session->flashdata('msg')) { ?>
                <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('msg'); ?></div>
            <?php } ?>
            <?php if ($this->session->flashdata('error')) { ?>
                <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
            <?php } ?>
            <?php $this->load->view('AjaxSales'); ?>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
        <div class="">
            <?php if (checkPermission('Lead', 'add')) { ?>
                <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width leadadd" data-href="<?php echo base_url('Lead/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><?php echo $this->lang->line('create_new_lead') ?></a> </div>
            <?php } ?>
            <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" href="<?php echo base_url('Lead'); ?>"><?php echo $this->lang->line('manage_leads') ?></a> </div>
            <?php if (checkPermission('Opportunity', 'add')) { ?>
                <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" data-href="<?php echo base_url('Opportunity/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><?php echo $this->lang->line('create_new_opportunity') ?></a> </div>
            <?php } ?>
            <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" href="<?php echo base_url('Opportunity'); ?>"><?php echo $this->lang->line('manage_opportunities') ?></a> </div>
            <?php if (checkPermission('Account', 'add')) { ?>
                <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" data-href="<?php echo base_url('Account/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><?php echo $this->lang->line('create_new_account') ?></a> </div>
            <?php } ?>
            <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" href="<?php echo base_url('Account'); ?>"><?php echo $this->lang->line('manage_account') ?></a> </div>
            <!--<div class="col-md-2 col-new-71 col-xs-12"> <a class="btn btn-white full-width" href="<?php echo base_url('Client/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><?php echo $this->lang->line('create_new_account') ?></a> </div>
            <div class="col-md-2 col-new-71 col-xs-12"> <a class="btn btn-white full-width" href="<?php echo base_url('Client'); ?>"><?php echo $this->lang->line('manage_account') ?></a> </div>-->
            <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a href="#" class="btn btn-white full-width"><?php echo $this->lang->line('manage_reports') ?></a> </div>
        </div>
    </div>
</div>
