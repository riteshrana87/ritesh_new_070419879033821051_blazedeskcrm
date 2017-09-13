<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
  <div class="modal-dialog modal-lg">
    
  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="set_label"><?php echo $modal_title;?>
        </h4>
      </div>
      <div class="modal-body">
            
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label><?= $this->lang->line('MEETING_DATE') ?>:</label><br/>
                        <?=!empty($meeting_data[0]['meet_date_time'])?$meeting_data[0]['meet_date_time']:''?>
                    </div>
                    <div class="col-sm-8">
                        <label><?= $this->lang->line('MEETING_TITLE') ?> :</label><br/>
                        <?=!empty($meeting_data[0]['meet_title'])?$meeting_data[0]['meet_title']:''?>
                    </div>
                </div>
          
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label><?php echo lang('MEETING_REMINDER');?> :</label><br/>
                        <?php  if($meeting_data['meet_reminder'] == '0')
                            {
                                echo "OFF";
                            }else  if($meeting_data['meet_reminder'] == '1')
                            {
                                echo "ON";
                            }
                            
                            ?>
<!--                        <input <?php if (!empty($meeting_data[0]['meet_reminder'])) { ?>checked="checked"<?php } ?> data-toggle="toggle" disabled="" data-onstyle="success" class="event_reminder_toggle" type="checkbox"/>-->
                    </div>
                    <div class="col-sm-4">
                        <label><?php echo lang('MEETING_CONTACT');?> :</label><br/>
                        <?=!empty($meeting_data[0]['meeting_contact_name'])?$meeting_data[0]['meeting_contact_name']:''?>
                    </div>
                     
                      <div class="col-sm-4">
                        <label><?php echo lang('MEETING_USER');?> :</label><br/>
                        <?=!empty($meeting_data[0]['login_user_name'])?$meeting_data[0]['login_user_name']:''?>
                    </div>
                </div>
          
                <div class="form-group row">
                    
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('CREATED_DATE') ?> :</label><br/>
                        <?=!empty($meeting_data[0]['created_date'])?$meeting_data[0]['created_date']:''?>
                    </div>
                </div>
           
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  
  </div>
