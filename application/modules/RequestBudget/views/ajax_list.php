<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>

<div class="table table-responsive">

    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
        <thead>
        <tr>

            <th <?php if(isset($sortfield) && $sortfield == 'campaign_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('campaign_name','<?php echo $sorttypepass;?>')"><?= $this->lang->line('CAMPAIGN_NAME') ?></th>

            <th <?php if(isset($sortfield) && $sortfield == 'camp_type_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('camp_type_name','<?php echo $sorttypepass;?>')"><?= $this->lang->line('CAMPAIGN_TYPE') ?></th>

            <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1"><?=$this->lang->line('RESPONSIBLE_EMPLOYEE')?></th>

            <th><?= $this->lang->line('DATE_RANGE') ?></th>
            <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1"><?= $this->lang->line('PRODUCT_NAME') ?></th>

            <th <?php if(isset($sortfield) && $sortfield == 'budget_ammount'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('budget_ammount','<?php echo $sorttypepass;?>')"><?= $this->lang->line('BUDGET_AMOUNT') ?></th>

            <th><?=$this->lang->line('action')?>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            </th>

        </tr>
        </thead>
        <tbody id="postList">
        <?php
        if (isset($budget_request_list) && count($budget_request_list) > 0) {
            foreach ($budget_request_list as $budget_request_data) {
                ?>
                <tr>
                    <td class="col-md-2"><?php echo $budget_request_data['campaign_name']; ?></td>
                    <td class="col-md-2"><?php echo $budget_request_data['camp_type_name']; ?></td>
                    <td class="col-md-2"><?php echo showResponsibleEmployeeRequestBudget($budget_request_data['budget_campaign_id']);?></td>
                    <td><?php echo date("m/d/Y", strtotime($budget_request_data['start_date'])) . "&nbsp;-&nbsp;" . date("m/d/Y", strtotime($budget_request_data['end_date'])); ?></td>
                    <td class="col-md-1"><?php echo showProductNameRequestBudget($budget_request_data['budget_campaign_id']);?></td>
                    <td class="col-md-1"><?php echo CURRENCY_CODE . ' ' . $budget_request_data['budget_ammount']; ?></td>
                    <td class="bd-actbn-btn">
                <?php if(checkPermission('RequestBudget','view')){ ?>
                        <a class="" data-href="<?php echo base_url() . 'RequestBudget/display/' . $budget_request_data['budget_campaign_id']; ?>" data-refresh="true" aria-hidden="true" data-toggle="ajaxModal"  title="<?= lang('view') ?>"><i class="fa fa-search fa-x greencol"></i>
                    <?php }?>
                <?php if(checkPermission('RequestBudget','edit')){ ?>
                        <a class="" data-href="<?php echo base_url() . 'RequestBudget/edit_record/' . $budget_request_data['budget_campaign_id']; ?>" data-refresh="true" aria-hidden="true" data-toggle="ajaxModal"  data-target="#ajaxModal"  title="<?= lang('edit') ?>"><i class="fa fa-pencil fa-x bluecol"></i></a>
                    <?php }?>
                <?php if(checkPermission('RequestBudget','delete')){ ?>
                        <a title="<?=lang('CONTACT_DELETE')?>" class="" onclick="delete_request('<?php echo $budget_request_data['budget_campaign_id']; ?>');"><span class="fa fa-remove fa-x redcol"></span> </a>
                    <?php }?>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="8" class="text-center"><?=lang('NO_RECORD_FOUND')?></td>
            </tr>
        <?php }?>
        <?php //echo $this->ajax_pagination->create_links(); ?>
        </tbody>

    </table>
    <div class="clearfix visible-xs-block"></div>
    <div id="common_tb" class="no_of_records">
        <?php
        if (isset($pagination)) {
            echo $pagination;
        }
        ?>
    </div>
</div>
