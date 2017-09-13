<?php
//if ($taskPage == 0)
//    $taskPage = 1;
$tasksSortDefault = '<i class="fa fa-sort"></i>';
$taskSortAsc = '<i class="fa fa-sort-desc"></i>';
$taskSortDesc = '<i class="fa fa-sort-asc"></i>';
if ($tasksortOrder == "asc") {
    $tasksortOrder = "desc";
} else {
    $tasksortOrder = "asc";
}

?>   
<div class="whitebox" id="table_campaign">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr role="row">
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>SupportContact/viewCampaignData/?orderField=campaign_contact_id&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('id') ?>
                        </a>
                        
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>SupportContact/viewCampaignData/?orderField=campaign_id&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('CAMPAIGN_NAME') ?>
                        </a>
                    </th>
                    <!--
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Contact/viewCampaignData/?orderField=campaign_type_id&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('CAMPAIGN_TYPE') ?>
                        </a>
                    </th>
                    -->
                    
                    <th><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
               
               <?php
               if(isset($campaign_info) && count($campaign_info) > 0 ){ ?>
            <?php foreach($campaign_info as $data){ 
                $redirect_link = base_url()."SupportContact/view/".$data['campaign_related_id'];
                ?>
                <tr>
                    
                    <td><?php echo $data['campaign_contact_id'];?></td>
                    <td width="30%"><?php echo $data['campaign_name'];?></td>
                   <!--  <td width="30%"><?php echo $data['camp_type_name'];?></td>
                    -->
                    <td>
                        
                        <a onclick="delete_campaign('<?php echo $data['campaign_contact_id'];?>');" title="<?php echo lang('REMOVE_FROM_CONTACT');?>"><i class="fa fa-remove fa-x redcol"></i></a>
                    </td>
                </tr>
            <?php }?>
        <?php }else{?>
            <tr>
                <td colspan="3" class="text-center"><?= lang ('common_no_record_found') ?></td>
            </tr>
        <?php }?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>
<script>
function delete_campaign(campaign_contact_id)
{
    var delete_url = "../../SupportContact/delete_campaign?campaign_contact_id=" + campaign_contact_id ;
    var delete_meg ="<?php echo $this->lang->line('confirm_delete_campaign');?>";
    BootstrapDialog.show(
        {
            title: '<?php echo $this->lang->line('Information');?>',
            message: delete_meg,
            buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '<?php echo $this->lang->line('ok');?>',
                action: function(dialog) {
                    window.location.href = delete_url;
                    dialog.close();
                }
            }]
        });

}
function delete_lead_campaign(lead_id)
{
    var delete_url = "../../SupportContact/deleteLeadCampaign?lead_id=" + lead_id ;
    var delete_meg ="<?php echo $this->lang->line('confirm_delete_campaign');?>";
    BootstrapDialog.show(
        {
            title: '<?php echo $this->lang->line('Information');?>',
            message: delete_meg,
            buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '<?php echo $this->lang->line('ok');?>',
                action: function(dialog) {
                    window.location.href = delete_url;
                    dialog.close();
                }
            }]
        });


}
function delete_prospect_campaign(prospect_id)
{
    var delete_url = "../../SupportContact/deleteProspectCampaign?prospect_id=" + prospect_id ;
    var delete_meg ="<?php echo $this->lang->line('confirm_delete_campaign');?>";
    BootstrapDialog.show(
        {
            title: '<?php echo $this->lang->line('Information');?>',
            message: delete_meg,
            buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '<?php echo $this->lang->line('ok');?>',
                action: function(dialog) {
                    window.location.href = delete_url;
                    dialog.close();
                }
            }]
        });
    }
</script>