<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->viewname = $this->uri->segment(1);

if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table table-responsive whitebox">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
    <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
    <table id="CampaignTable" class="table table-striped dataTable" role="grid" >
        <thead>
            <tr>
                
                <th <?php if(isset($sortfield) && $sortfield == 'campaign_name'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}
                                else{echo "class = 'sorting_asc col-md-1'";}}
                           else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="campaignreport" rowspan="1" colspan="1" onclick="apply_sorting('campaign_name','<?php echo $sorttypepass;?>')">
                    
                        <?= $this->lang->line('CR_CAMPAIGN_NAME') ?>
                </th>
                <th <?php if(isset($sortfield) && $sortfield == 'campaign_auto_id'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}
                                else{echo "class = 'sorting_asc col-md-1'";}}
                           else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="campaignreport" rowspan="1" colspan="1" onclick="apply_sorting('campaign_auto_id','<?php echo $sorttypepass;?>')">
                    
                    <?= $this->lang->line('CR_CAMPAIGN_ID') ?>
                    
                </th>
                <th <?php if(isset($sortfield) && $sortfield == 'camp_type_name'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}
                                else{echo "class = 'sorting_asc col-md-1'";}}
                           else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="campaignreport" rowspan="1" colspan="1" onclick="apply_sorting('camp_type_name','<?php echo $sorttypepass;?>')">
                    
                    <?= $this->lang->line('CR_TYPE') ?>
                    
                </th>
                <th <?php if(isset($sortfield) && $sortfield == 'budget_ammount'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}
                                else{echo "class = 'sorting_asc col-md-1'";}}
                           else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="campaignreport" rowspan="1" colspan="1" onclick="apply_sorting('budget_ammount','<?php echo $sorttypepass;?>')">
                    <?= $this->lang->line('CR_BUDGET_AVAILABLE') ?>
                    
                </th>
                <th class="col-md-1">
                    <?= $this->lang->line('CR_BUDGET_SPEND') ?>
                </th>
                <th class="col-md-1">
                    <?= $this->lang->line('CR_GENERATED_REVENUE') ?>
                </th>
                <th class="col-md-1">
                    <?= $this->lang->line('CR_GOAL') ?>
                </th>
                <th <?php if(isset($sortfield) && $sortfield == 'start_date'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}
                                else{echo "class = 'sorting_asc col-md-1'";}}
                           else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="campaignreport" rowspan="1" colspan="1" onclick="apply_sorting('start_date','<?php echo $sorttypepass;?>')">
                    <?= $this->lang->line('CR_START_DATE') ?>
                    
                </th>
                <th <?php if(isset($sortfield) && $sortfield == 'end_date'){
                                if($sortby == 'asc'){echo "class = 'sorting_desc col-md-1'";}
                                else{echo "class = 'sorting_asc col-md-1'";}}
                           else{echo "class = 'sorting col-md-1'";} ?> tabindex="0" aria-controls="campaignreport" rowspan="1" colspan="1" onclick="apply_sorting('end_date','<?php echo $sorttypepass;?>')">
                    <?= $this->lang->line('CR_END_DATE') ?>
                    
                </th>
                <th>
                    <?= $this->lang->line('CR_RESPONSIBLE') ?>
                    
                </th>
                <th class="sortCampaignReport bd-cr-select text-right">
                   <span> <?= $this->lang->line('CR_SELECT_CAMPAIGN') ?></span>
                    <input type="checkbox" class="selecctall" id="selecctall">
                </th>
            </tr>
        </thead>
      
        <tbody>
                    
        <?php
                        
            if (isset($campaign_report_list) && count($campaign_report_list) > 0) {
                foreach ($campaign_report_list as $budget_request_data) {
        ?>
            <tr>
                
                
                <td class="col-md-2"><?php echo $budget_request_data['campaign_name']; ?></td>
                <td  class="col-md-1"><?php echo $budget_request_data['campaign_auto_id']; ?></td>
                <td class="col-md-1"><?php echo $budget_request_data['camp_type_name']; ?></td>
                <td ><?php echo number_format($budget_request_data['budget_ammount'], 2); ?></td>
                <td  class="col-md-1"><?php echo "5000"; ?></td>
                <td  class="col-md-1"><?php echo "5000"; ?></td>
                <td  class="col-md-1"><?php echo "5000"; ?></td>
                <td class="col-md-1"><?php echo date("m/d/Y", strtotime($budget_request_data['start_date'])); ?></td>
                <td class="col-md-1"><?php echo date("m/d/Y", strtotime($budget_request_data['end_date'])); ?></td>
                <td class="col-md-1"><?php echo showResponsibleEmployee($budget_request_data['campaign_id']);?></td>
                <td class="text-right"><input type="checkbox" class="checkbox1" name="check[]" value="<?php echo $budget_request_data['campaign_id']; ?>"/></td>

            </tr>
        <?php
                }
            } else {
        ?>
            <tr>
                <td colspan="11" class="text-center"><?= lang('common_no_record_found') ?></td>
            </tr>
        <?php }
        ?>
                    
        </tbody>
    </table>

</div>
<div class="clr"></div>
    <div id="common_tb" class="no_of_records">
            <?php
                if (isset($pagination)) {
                    echo $pagination;
                }
            ?>
    </div>
