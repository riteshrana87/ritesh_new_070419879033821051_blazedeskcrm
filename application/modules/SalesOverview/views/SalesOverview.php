<?php
$tasksSortDefault = '<i class="fa fa-sort"></i>';
$taskSortAsc = '<i class="fa fa-sort-desc"></i>';
$taskSortDesc = '<i class="fa fa-sort-asc"></i>';

if ($tasksortOrder == "asc") {
    $tasksortOrder = "desc";
} else {
    $tasksortOrder = "asc";
}
if ($salessortOrder == "asc")
    $salessortOrder = "desc";
else
    $salessortOrder = "asc";


$sales_by_user = "";
$courrency_symbol = "$";
//pr($salesTargetProgressGraph);   
//echo "TEST : ".$salesTargetProgressGraph['sales_target'][0]['currency_symbol']." ".$salesTargetProgressGraph['sales_target'][0]['target'] ; 
?>
<style>
    .current a {
        color: #006600 !important
    }
    ;
</style>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url(); ?>">
                    <?= lang('crm') ?>
                </a></li>
            <li class="active">
                <?= lang('sales_overview') ?>
            </li>
        </ul>
    </div>
    <!-- Search: Start -->
    <div class="col-xs-12 col-md-3 col-sm-6 pull-right text-right col-md-offset-3">
        <div class="">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10  text-right search-top pull-right">
                <div class="pull-right search-top">
                    <form id="searchForm" class="navbar-form navbar-left pull-right">
                        <div class="input-group">
                            <input type="text"  placeholder="<?= $this->lang->line('EST_LISTING_SEARCH_FOR') ?>" name="search_input" id="search_input" class="form-control">
                            <span class="input-group-btn">
                                <button  type="submit" id="submit" title="<?= $this->lang->line('search') ?>" name="submit" class="btn btn-default"><i class="fa fa-search fa-x"></i></button>
                                <button onclick="reset_data();" title="<?= $this->lang->line('reset'); ?>" class="btn btn-default" type="reset"><i class="fa fa-refresh fa-x"></i></button>
                            </span> </div>
                        <!-- /input-group -->
                    </form>
                </div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <!-- Search: End -->

    <div class="clr"></div>
    <?php if ($this->session->flashdata('message')) { ?>
        <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('message'); ?></div>
    <?php } ?>
    <?php if ($this->session->flashdata('error_task')) { ?>
        <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error_task'); ?></div>
    <?php } ?>
    <div id="SalesOverviewDrag">
        <?php
        if (count($widgets) > 0) {
            $widgets = array_unique($widgets);
            foreach ($widgets as $views) {
                if ($views == 'AjaxTasks') {
                    echo "<div id='taskDataId'>";
                    echo $this->load->view($views);
                    echo "</div>";
                } else if ($views == 'SalesListView') {
                    echo "<div id='salesDataId'>";
                    echo $this->load->view('Widgets/' . $views);
                    echo "</div>";
                } else {
                    echo $this->load->view('Widgets/' . $views);
                }
            }
        }
        ?>
    </div>
</div>
