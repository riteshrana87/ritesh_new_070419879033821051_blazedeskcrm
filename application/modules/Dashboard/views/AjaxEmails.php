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
<div class="whitebox sadasd" id="table_emails">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr>
                    <th ><?= $this->lang->line('COMM_DATE') ?></th>
                    <th class="col-md-2"><?= $this->lang->line('COMM_TYPE') ?></th>
                    <th class="col-md-2"> <?= $this->lang->line('COMM_CONTACT') ?></th>
                    <th class="col-md-3"><?= $this->lang->line('COMM_SUBJECT') ?></th>
                    <th class="col-md-2"><?= $this->lang->line('COMM_EMPLOYEE') ?></th>
                    <th class="col-md-1"><?php echo lang('action');?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                
                if(isset($communication_data) && count($communication_data) > 0)
                { 
                    foreach ($communication_data as $communication) {
                    //$redirect_link = base_url()."Contact/view/".$note['notes_related_id'];
                        if (strpos($_SERVER['HTTP_REFERER'], 'Lead') !== false) {
                                $redirect_link = base_url()."Lead/viewdata/".$communication['comm_related_id'];
                            }
                            elseif (strpos($_SERVER['HTTP_REFERER'], 'Opportunity') !== false) {
                                $redirect_link = base_url()."Opportunity/viewdata/".$communication['comm_related_id'];
                            }
                            elseif (strpos($_SERVER['HTTP_REFERER'], 'Account') !== false) {
                                $redirect_link = base_url()."Account/viewdata/".$communication['comm_related_id'];
                            }
                            elseif(strpos($_SERVER['HTTP_REFERER'], 'Contact') !== false) {
                                $redirect_link = base_url()."Contact/view/".$communication['comm_related_id'];
                            }
                        
                    ?>
                    <tr id="event_id_<?php echo $communication['comm_id']; ?>">
                        <td > <?php echo configDateTime($communication['comm_date']);?></td>
                        
                        <td >
                            <?php 
                            if($communication['comm_type'] == "1")
                            {
                                echo lang('EMAIL_TYPE_EVENT');
                            }else if($communication['comm_type'] == "2")
                            {
                                echo lang('EMAIL_TYPE_EMAIL_PROSPECT');
                            }else if($communication['comm_type'] == "3")
                            {
                                echo lang('EMAIL_TYPE_NOTE');
                            }else
                            {
                                echo lang('EMAIL_TYPE_PERSONAL');
                            }
                            ?>
                        </td>
                        
                        <td >  <?php echo $communication['receiver_name'];?></td>
                        
                        <td >
                           <?php
                            $in = $communication['comm_subject'];
                            echo  strlen($in) > 50 ? substr($in,0,50)."..." : $in; 
                            ?>
                        </td>
                       
                        <td>       <?php echo $communication['sender_name'];?></td>
                        
                        <td>
                        <?php if(checkPermission('Contact','view')){ ?>
                            <a data-href="<?= base_url() ?>Contact/viewCommunication/<?= $communication['comm_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_EMAIL_CONTENT'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                          <?php }?>
                          </td>  
                    </tr>
                <?php }
                }else
               { ?>
                    <tr><td colspan="6" class="text-center"> <?= lang ('common_no_record_found') ?></td></tr>  
              <?php }
                ?>
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
    $('.event_reminder_toggle').bootstrapToggle();

    function delete_event(campaign_contact_id, redirect_link)
    {
        var delete_url = "../../Contact/delete_event?event_id=" + campaign_contact_id + "&link=" + redirect_link;
        var delete_meg ="<?php echo lang('CONFIRM_DELETE_EVENT');?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                        return false;
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