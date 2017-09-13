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

<div class="whitebox" id="table_meeting">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr>
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Contact/getContactMeeting/?orderField=meeting_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'meeting_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'meeting_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('MEETING_DATE') ?>
                        </a>

                    </th>
                    <th><?php echo lang('START_TIME');?></th>
                    <th><?php echo lang('END_TIME');?></th>
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Contact/getContactMeeting/?orderField=meet_title&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'meet_title') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'meet_title') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('MEETING_TITLE') ?>
                        </a>

                    </th>
                    <th><?php echo lang('MEETING_REMINDER');?></th>
                    <th></th>
                    <th><?php echo lang('MEETING_CONTACT');?></th>
                    <th><?php echo lang('MEETING_USER');?> </th>
                    
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Contact/getContactMeeting/?orderField=mm.created_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('CREATED_DATE') ?>
                        </a>

                    </th>
                    <th><?php echo lang('action');?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                
                if(isset($meeting_data) && count($meeting_data) > 0)
                { 
                    foreach ($meeting_data as $event) {
                    //$redirect_link = base_url()."Contact/view/".$note['notes_related_id'];
                        if (strpos($_SERVER['HTTP_REFERER'], 'Lead') !== false) {
                                $redirect_link = base_url()."Lead/viewdata/".$event['meet_related_id'];
                            }
                            elseif (strpos($_SERVER['HTTP_REFERER'], 'Opportunity') !== false) {
                                $redirect_link = base_url()."Opportunity/viewdata/".$event['meet_related_id'];
                            }
                            elseif (strpos($_SERVER['HTTP_REFERER'], 'Account') !== false) {
                                $redirect_link = base_url()."Account/viewdata/".$event['meet_related_id'];
                            }
                            elseif(strpos($_SERVER['HTTP_REFERER'], 'Contact') !== false) {
                                $redirect_link = base_url()."Contact/view/".$event['meet_related_id'];
                            }
                        
                    ?>
                    <tr id="event_id_<?php echo $event['meeting_id']; ?>">
                        <td>
                            <?php echo configDateTime($event['meeting_date']);?>
                        </td>
                        <td> <?php echo convertTimeTo12HourFormat($event['meeting_time']);?></td>
                        <td> <?php echo convertTimeTo12HourFormat($event['meeting_end_time']);?></td>
                        <td >
                            <?php
                            $in = $event['meet_title'];
                            echo  strlen($in) > 25 ? substr($in,0,25)."..." : $in; 
                            ?>
                            
                           
                        </td>
                        <td>
                            <?php 
                          
                            if($event['meeting_reminder'] == '0')
                            {
                                echo  lang('off');
                            }else  if($event['meeting_reminder'] == '1')
                            {
                                 echo lang('on');
                            }
                            
                            ?>
<!--                            <input <?php if (!empty($event['meet_reminder'])) { ?>checked="checked"<?php } ?> data-toggle="toggle" disabled="" data-onstyle="success" class="event_reminder_toggle" type="checkbox"  id="event_reminder" name="event_reminder"/>-->
                            
                        </td>
                        <td>
                            <?php 
                            $meet_date_time =  $event['meeting_date']." ".$event['meeting_time'];
                            $event_date = date('Y-m-d',  strtotime($meet_date_time));
                            echo calculate_day_left($event_date);
                            ?>
                        </td>
                        <td><?php echo ucfirst($event['meeting_contact_name']);?></td>
                        <td><?php echo ucfirst($event['login_user_name']);?></td>
                        <td><?php echo configDateTime($event['created_date']);?></td>
                        
                        <td>
                            <?php  $redirectLink=$_SERVER['HTTP_REFERER'] ?>
                            <?php  if (strpos($redirectLink, 'Contact/view') !== false) {
                                            if (checkPermission ("Contact", 'view')) {
                                            ?>
                            <a data-href="<?= base_url() ?>Meeting/view_meeting/<?= $event['meeting_master_id'] ?>" title="<?= $this->lang->line('view') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_MEETING'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                            <?php } } ?>
                            <?php  if (strpos($redirectLink, 'Lead/viewdata') !== false) {
                                            if (checkPermission ("Lead", 'view')) {
                                            ?>
                            <a data-href="<?= base_url() ?>Meeting/view_meeting/<?= $event['meeting_master_id'] ?>" title="<?= $this->lang->line('view') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_MEETING'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                            <?php } } ?>
                            <?php  if (strpos($redirectLink, 'Opportunity/viewdata') !== false) {
                                            if (checkPermission ("Opportunity", 'view')) {
                                            ?>
                            <a data-href="<?= base_url() ?>Meeting/view_meeting/<?= $event['meeting_master_id'] ?>" title="<?= $this->lang->line('view') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_MEETING'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                            <?php } } ?>
<!--                            <a data-href="<?= base_url() ?>Contact/update_meeting/<?= $event['meeting_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_SCHDEDULE_MEETING'); ?>"><i class="fa fa-pencil bluecol"></i></a>&nbsp;&nbsp; 
                            <a onclick="delete_meeting('<?php echo $event['meeting_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a>-->
                        </td>
                    </tr>
                <?php }
                }else
               { ?>
                    <tr><td colspan="9" class="text-center"> <?= lang ('common_no_record_found') ?></td></tr>  
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
//$('.event_reminder_toggle').bootstrapToggle();

function delete_meeting(meeting_id,redirect_link)
{
    var delete_url = "../../Contact/delete_meeting?meeting_id=" + meeting_id +"&link=" + redirect_link;
    var delete_meg ="<?php echo lang('CONFIRM_DELETE_MEETING'); ?>";
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