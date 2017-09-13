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
<div class="whitebox" id="table_events">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead style="display:none">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
            </thead>

            <tbody>
                <?php
                
                if(isset($event_data) && count($event_data) > 0)
                { 
                    foreach ($event_data as $event) {
                    //$redirect_link = base_url()."Contact/view/".$note['notes_related_id'];
                        if (strpos($_SERVER['HTTP_REFERER'], 'Lead') !== false) {
                                $redirect_link = base_url()."Lead/viewdata/".$event['event_related_id'];
                            }
                            elseif (strpos($_SERVER['HTTP_REFERER'], 'Opportunity') !== false) {
                                $redirect_link = base_url()."Opportunity/viewdata/".$event['event_related_id'];
                            }
                            elseif (strpos($_SERVER['HTTP_REFERER'], 'Account') !== false) {
                                $redirect_link = base_url()."Account/viewdata/".$event['event_related_id'];
                            }
                            elseif(strpos($_SERVER['HTTP_REFERER'], 'Contact') !== false) {
                                $redirect_link = base_url()."Contact/view/".$event['event_related_id'];
                            }
                        
                    ?>
                    <tr id="event_id_<?php echo $event['event_id']; ?>">
                        <td class="text-center">
                            <?php
                                if($event['event_image'] != '')
                                {
                                    $img_path = base_url()."uploads/events/".$event['event_image'];
                                }else
                                {
                                    $img_path = base_url()."uploads/images/noimage.jpg";
                                }
                            
                            ?>
                            <img src="<?php echo $img_path; ?>" width="48"/><br/>
                            <div class="clr"></div>
                                 <?php echo $event['event_date']." ".date('H:m',  strtotime($event['event_time']));?>
                        </td>
                        <td class="col-lg-4">
                            <h4><?php
                            $in = $event['event_title'];
                            echo  strlen($in) > 50 ? substr($in,0,50)."..." : $in; 
                            ?></h4>
                            
                            <?php
                            if($event['event_note'] != '')
                            {
                                echo lang('EVENT_NOTE')." : ".$event['event_note'];
                            }
                            
                            ?>
                        </td>
                       <td class="remindr-format">
                           
                            <ul class="nav">
                                <li> <label><?php echo lang('reminder?');?>  <input <?php if (!empty($event['event_remember'])) { ?>checked="checked"<?php } ?> class="event_reminder_toggle" disabled="true" data-toggle="toggle" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" data-onstyle="success" type="checkbox"  /></label></li>
                                <li>
                                    <?php 
                                    $c_event_date = $event['event_date'];
                                    $reminder_date = $event['reminder_date'];
                                    $reminder_str = calculate_days($c_event_date,$reminder_date);
                                    //$reminder_before = con_min_days($event['event_date']);
                                    //$reminder_str = explode('/',$reminder_before);
                                    ?>
                                    <?php
                                    if(!empty($event['event_remember']) && $event['event_remember'] == '1')
                                    {
                                    ?>
                                    <label><?php echo lang('REMIND_BEFORE_DEADLINE');?><span class="remind-count"><?php echo $reminder_str;?></span>Days</label>
                                    <?php } ?>
                                </li>
                            </ul>

                            
                        </td>
                        <td>
                               <div> <?php 
                                $event_date = date('Y-m-d',  strtotime($event['event_date']));
                                echo calculate_day_left($event_date);?></div>
                                
                                <?PHP if (checkPermission('Contact', 'add')) { ?><a data-href="<?= base_url() ?>Contact/event_send_email/<?= $event['event_related_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" class="btn btn-blue width-100"><?php echo lang('SEND_EMAIL');?></a><?php } ?>
                        </td>
                        
                        <td>
                            <a data-href="<?= base_url() ?>Contact/viewEvents/<?= $event['event_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_EVENTS'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                           
                            <!--
                            <a data-href="<?= base_url() ?>Contact/update_event/<?= $event['event_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_EVENTS'); ?>"><i class="fa fa-pencil greencol"></i></a>&nbsp;&nbsp; 
                            <a onclick="delete_event('<?php echo $event['event_id']; ?>','<?php //echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a>
                            -->
                            </td>
                    </tr>
                <?php }
                }else
               { ?>
                    <tr><td colspan="3" class="text-center"> <?= lang ('common_no_record_found') ?></td></tr>  
              <?php }
                ?>
            </tbody>
        </table>
        <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12">
           
            <div class="col-lg-6 col-xs-6 col-md-6 col-sm-6 text-center">
                <?php echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>
<script>
$('.event_reminder_toggle').bootstrapToggle();

function delete_event(campaign_contact_id,redirect_link)
{
    var delete_url = "../../Contact/delete_event?event_id=" + campaign_contact_id +"&link=" + redirect_link;
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