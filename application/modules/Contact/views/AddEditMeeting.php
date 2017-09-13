<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$path = base_url().'Contact/addUpdateMeeting'
?>

  <div class="modal-dialog ">
      <?php $attributes = array("name" => "form_schedule_meeting", "id" => "form_schedule_meeting", 'data-parsley-validate' => "");
echo form_open_multipart($path,$attributes);
?>
  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="set_label"><?php echo $modal_title;?>
        </h4>
      </div>
      <div class="modal-body">
               
                    <div class ="form-group row">
                        <div class="">
                            <label><?= $this->lang->line('ADD_RECEIPENT') ?> :&nbsp;* </label>
                            <select name="contact_id[]" multiple="true" <?php if(isset($display_from) && $display_from = 'dashboard_salesoverview'){ echo "required='true'";} ?> data-placeholder="<?= $this->lang->line('ADD_RECEIPENT') ?>" class="chosen-select" id="contact_id" data-parsley-errors-container="#company-errors">
                           
                             <?php 
                            foreach ($contact_data as $contact)
                            {  if($contact_id != $contact['contact_id'])
                                { ?>
                                     <option value="<?php echo $contact['contact_id'];?>"><?php echo $contact['contact_name'];?></option>
                                <?php 
                                }
                            }    
                            ?>
                        </select>
                        <div id="company-errors"></div>
                        </div>
                       
                       
                    </div>
          <div class="form-group row">
           <div class="">
                            <label><?= $this->lang->line('ADD_ADDITIONAL_RECEIPENT') ?> : </label>
                            
                            <textarea class="form-control" rows="2" name="additional_receipent" id="additional_receipent" placeholder="<?php echo lang('EX_COMMA_SEPERATED_EMAIL');?>"><?=!empty($editRecord[0]['additiona_receipent_email'])?$editRecord[0]['additiona_receipent_email']:''?></textarea>
                            <label id="QtChar"> </label>
                        </div>
          </div>
             
                <div class="form-group row">
                   
                    <div class="">
                        <label><?= $this->lang->line('MEETING_TITLE') ?> : *</label>
                        <!-- onkeyup="checkTextareaWord()"-->
                        <textarea class="form-control" required maxlength="150"  rows="2" name="meeting_title" id="meeting_title" placeholder="<?php echo lang('MEETING_TITLE');?>"><?=!empty($editRecord[0]['meet_title'])?$editRecord[0]['meet_title']:''?></textarea>
                        <label id="QtChar"> </label>
                    </div>
                    
                    
                </div>
                <div class="form-group row">
                	<div class = "col-xs-12 col-sm-4">
                        <label><?= $this->lang->line('MEETING_DATE') ?> : *</label>
                            <div class="input-group date" id="meeting_date">
                            
                                <input type="text" class="form-control" required placeholder="<?= $this->lang->line('MEETING_DATE') ?>" id="meeting_date" name="meeting_date"  value="<?php
                                    if (!empty($editRecord[0]['meet_date_time'])) {
                                        echo date('YYYY-m-d H:i', strtotime($editRecord[0]['meet_date_time']));
                                    } 
                                    ?>">
                                <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                            </div>
                    </div>
                    
                    <div class = "col-sm-4 col-xs-12">
                    <label>&nbsp;</label>
                        <div class="">
                            <input <?php if (!empty($editRecord[0]['meet_reminder'])) { ?>checked="checked"<?php } ?> class="event_reminder_tog" data-toggle="toggle" data-onstyle="success" type="checkbox"  id="event_reminder" name="event_reminder"/>
                            <label>
                                <?= $this->lang->line('reminder?') ?>
                            </label>
                        </div>
                    </div> 
                </div>
            <input type="hidden" id="meeting_related_id" name="meeting_related_id" value="<?= !empty($editRecord[0]['meet_related_id']) ? $editRecord[0]['meet_related_id'] : $meeting_related_id ?>">
            <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
            <input type="hidden" name="hdn_contact_id" id="hdn_contact_id" value="<?=!empty($contact_id)?$contact_id:''?>">
            <input type="hidden" name="meeting_id" id="meeting_id" value="<?=!empty($meeting_id)?$meeting_id:''?>" />
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>"> 
      </div>
      <div class="modal-footer">
        <center> <input type="submit" class="btn btn-primary" id="contact_submit_btn" value="<?=$submit_button_title?>"></center>
      </div>
    </div>
  <?php echo form_close(); ?>
  </div>
 
<script>
    $(document).ready(function () {    $('#form_schedule_meeting').parsley();});
</script>
<script>
 $(function()
    {
      // $("#QtChar")[0].innerText = "Allowed Character :"+ parseInt($("#meeting_title")[0].maxLength);
    });
    function checkTextareaWord()
    {
       $("#QtChar")[0].innerText = "Allowed Character : "+ parseInt($("#meeting_title")[0].maxLength - $("#meeting_title").val().length);	
    }

    $('.chosen-select').chosen();
     
    var current_date = new Date();
    $('.event_reminder_tog').bootstrapToggle();
    
    <?php 
    if(!empty($meeting_id))
    { ?>
        $('#meeting_date').datetimepicker({ format: 'YYYY-MM-DD HH:mm'});
   <?php }else{
    ?>
        $('#meeting_date').datetimepicker({minDate: current_date,  format: 'YYYY-MM-DD HH:mm'});
   <?php } ?>
   
    
    
    
    
</script>