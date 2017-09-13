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
                                $redirect_link = base_url()."Account/viewLostClient/".$event['event_related_id'];
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
                            $in = ucfirst($event['event_title']);
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
                                <li> <label><?php echo lang('reminder?');?>  <input <?php if (!empty($event['event_remember'])) { ?>checked="checked"<?php } ?> class="event_reminder_toggle" disabled="true" data-toggle="toggle" data-onstyle="success" type="checkbox"  id="event_reminder" name="event_reminder" /></label></li>
                                <li>
                                    <?php
                                    if(!empty($event['event_remember']) && $event['event_remember'] == '1')
                                    {
                                        $c_event_date = $event['event_date'];
                                        $reminder_date = $event['reminder_date'];
                                        $reminder_str = calculate_days($c_event_date,$reminder_date);
                                        //$reminder_before = con_min_days($event['event_date']);
                                        //$reminder_str = explode('/',$reminder_before);
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
                                <?php $redirectLink =  $_SERVER['HTTP_REFERER']; ?>
                                <?php  if (strpos($redirectLink, 'Contact/view') !== false) {
                                            if (checkPermission ("Contact", 'add')) {
                                            ?>
                                <a data-href="<?= base_url() ?>Contact/event_send_email/<?= $event['event_related_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" class="btn btn-blue width-100"><?php echo lang('SEND_EMAIL');?></a>
                                <?php } } ?>
                                <?php  if (strpos($redirectLink, 'Lead/viewdata') !== false) {
                                            if (checkPermission ("Lead", 'add')) {
                                            ?>
                                <a data-href="<?= base_url() ?>Contact/event_send_email/<?= $event['event_related_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" class="btn btn-blue width-100"><?php echo lang('SEND_EMAIL');?></a>
                                <?php } } ?>
                                <?php  if (strpos($redirectLink, 'Opportunity/viewdata') !== false) {
                                            if (checkPermission ("Opportunity", 'add')) {
                                            ?>
                                <a data-href="<?= base_url() ?>Contact/event_send_email/<?= $event['event_related_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" class="btn btn-blue width-100"><?php echo lang('SEND_EMAIL');?></a>
                                <?php } } ?>
                        </td>
                       
                        <td  class="bd-actbn-btn">
                            <?php 
                           
                            if (strpos($redirectLink, 'Contact/view') !== false) {
                                if (checkPermission ("Contact", 'view')) {
                                ?>
                                    <a data-href="<?= base_url() ?>Contact/viewEvents/<?= $event['event_id'] ?>" title="<?= $this->lang->line('view') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_EVENTS'); ?>"><i class="fa fa-search greencol"></i></a>
                                <?php } 
                            
                            }?>
                            
                            <?php  if (strpos($redirectLink, 'Lead/viewdata') !== false) {
                                if (checkPermission ("Lead", 'view')) {
                                ?>
                                    <a data-href="<?= base_url() ?>Contact/viewEvents/<?= $event['event_id'] ?>" title="<?= $this->lang->line('view') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_EVENTS'); ?>"><i class="fa fa-search greencol"></i></a>
                                <?php }
                            }?>
                            
                            <?php  if (strpos($redirectLink, 'Opportunity/viewdata') !== false) {
                                if (checkPermission ("Opportunity", 'view')) {
                                ?>
                                    <a data-href="<?= base_url() ?>Contact/viewEvents/<?= $event['event_id'] ?>" title="<?= $this->lang->line('view') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_EVENTS'); ?>"><i class="fa fa-search greencol"></i></a>
                                <?php } 
                            
                            }?>
                                    
                            <?php  if (strpos($redirectLink, 'Account/viewLostClient') !== false) {
                                if (checkPermission ("Account", 'view')) {
                                ?>
                                    <a data-href="<?= base_url() ?>Contact/viewEvents/<?= $event['event_id'] ?>" title="<?= $this->lang->line('view') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_EVENTS'); ?>"><i class="fa fa-search greencol"></i></a>
                                <?php } 
                            
                            }?>
                            
                            <?php  if (strpos($redirectLink, 'Contact/view') !== false) {
                                if (checkPermission ("Contact", 'edit')) {
                                ?>
                               <a data-href="<?= base_url() ?>Contact/update_event/<?= $event['event_id'] ?>" title="<?= $this->lang->line('edit') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_EVENTS'); ?>"><i class="fa fa-pencil bluecol"></i></a>
                               <?php } 
                           
                            }?>
                           
                            <?php  if (strpos($redirectLink, 'Lead/viewdata') !== false) {
                                if (checkPermission ("Lead", 'edit')) {
                                ?>
                               <a data-href="<?= base_url() ?>Contact/update_event/<?= $event['event_id'] ?>" title="<?= $this->lang->line('edit') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_EVENTS'); ?>"><i class="fa fa-pencil bluecol"></i></a>
                               <?php } 
                           
                            }?>
                               
                            <?php  if (strpos($redirectLink, 'Opportunity/viewdata') !== false) {
                                if (checkPermission ("Opportunity", 'edit')) {
                                ?>
                               <a data-href="<?= base_url() ?>Contact/update_event/<?= $event['event_id'] ?>" title="<?= $this->lang->line('edit') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_EVENTS'); ?>"><i class="fa fa-pencil bluecol"></i></a>
                               <?php } 
                           
                            }?>
                               
                            <?php  if (strpos($redirectLink, 'Account/viewLostClient') !== false) {
                                if (checkPermission ("Account", 'edit')) {
                                ?>
                               <a data-href="<?= base_url() ?>Contact/update_event/<?= $event['event_id'] ?>" title="<?= $this->lang->line('edit') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_EVENTS'); ?>"><i class="fa fa-pencil bluecol"></i></a>
                               <?php } 
                           
                            }?>
                           
                            <?php  if (strpos($redirectLink, 'Contact/view') !== false) {
                                if (checkPermission ("Contact", 'delete')) {
                                ?>
                                <a title="<?= $this->lang->line('delete') ?>" onclick="delete_event('<?php echo $event['event_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a>
                                <?php } 
                            
                            } ?>
                            
                            <?php  if (strpos($redirectLink, 'Lead/viewdata') !== false) {
                                if (checkPermission ("Lead", 'delete')) {
                                ?>
                                <a title="<?= $this->lang->line('delete') ?>" onclick="delete_event('<?php echo $event['event_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a>
                                <?php } 
                            
                            } ?>
                            
                            <?php  if (strpos($redirectLink, 'Opportunity/viewdata') !== false) {
                                if (checkPermission ("Opportunity", 'delete')) {
                                ?>
                                <a title="<?= $this->lang->line('delete') ?>" onclick="delete_event('<?php echo $event['event_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a>
                                <?php } 
                            
                            } ?>
                                
                            <?php  if (strpos($redirectLink, 'Account/viewLostClient') !== false) {
                                if (checkPermission ("Account", 'delete')) {
                                ?>
                                <a title="<?= $this->lang->line('delete') ?>" onclick="delete_event('<?php echo $event['event_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a>
                                <?php } 
                            
                            } ?>
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
            <?php
                if(checkPermission('Contact','add'))
                { ?>
            <div class="col-lg-6 col-xs-6 col-md-6 col-sm-6 text-left">
                 <p><a class="btn btn-blue" data-href="<?= base_url('Contact/view_event')."/".$contact_id; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('ADD_EVENTS');?>"><?php echo lang('ADD_EVENTS');?></a></p>
            </div>
                <?php }?>
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
    var delete_meg ="<?php echo lang('CONFIRM_DELETE_EVENT'); ?>";
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