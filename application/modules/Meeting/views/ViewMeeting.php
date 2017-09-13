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
                        <?=!empty($meeting_data[0]['meeting_date'])?date('Y-m-d',  strtotime($meeting_data[0]['meeting_date'])):''?>
                    </div>
                    <div class="col-sm-4">
                        <label><?= $this->lang->line('MEETING_TIME') ?>:</label><br/>
                        <?=!empty($meeting_data[0]['meeting_time'])? $meeting_data[0]['meeting_time']:''?>
                    </div>
                    <div class="col-sm-4">
                        <label><?= $this->lang->line('MEETING_TITLE') ?> :</label><br/>
                        <?=!empty($meeting_data[0]['meet_title'])?$meeting_data[0]['meet_title']:''?>
                    </div>
                </div>
          
                <div class="form-group row">
                    <div class="col-sm-2">
                        <label><?php echo lang('MEETING_REMINDER');?> :</label><br/>
                         <?php 
                            if($meeting_data[0]['meeting_reminder'] == '1')
                            {
                                echo "YES";
                            }else  if($meeting_data[0]['meeting_reminder'] == '0')
                            {
                                echo "NO";
                            }
                       
                        ?>
                    </div>
                    <?php
                    if($meeting_data[0]['meeting_reminder'] == '1')
                    {
                    
                    ?>
                    <div class="col-sm-2">
                        <label><?php echo lang('BEFORE_AFTER');?> :</label><br/>
                        <?php 
                            if($meeting_data[0]['before_status'] == '1')
                            {
                                echo "AFTER";
                            }else  if($meeting_data[0]['before_status'] == '0')
                            {
                                echo "BEFORE";
                            }
                        ?>
                    </div>
                    <div class="col-sm-2">
                        <label><?php echo lang('TIME_TO_REMIND');?> :</label><br/>
                        <?=!empty($meeting_data[0]['remind_time'])?$meeting_data[0]['remind_time']:''?>
                        
                    </div>
                    <div class="col-sm-2">
                        <label><?php echo lang('REPEAT');?> :</label><br/>
                        <?=!empty($meeting_data[0]['repeat'])?$meeting_data[0]['repeat']:'N/A'?>
                    </div>
                    <div class="col-sm-3">
                        <label><?php echo lang('REMIND_TIME_BEFORE_AFTER');?> :</label><br/>
                        <?=!empty($meeting_data[0]['remind_before_min'])?$meeting_data[0]['remind_before_min']:'N/A'?>
                    </div>
                    <?php } ?>
                </div>
          
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?php echo lang('MEETING_PARTICIPANTS');?> :</label><br/>
                        <?=!empty($meeting_data[0]['meeting_participants'])?$meeting_data[0]['meeting_participants']:''?>
                    </div>
                   
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?php echo lang('METING_ADDITIONAL_PARTICIAPNTS');?> :</label><br/>
                        <?=!empty($meeting_data[0]['additiona_receipent_email'])?$meeting_data[0]['additiona_receipent_email']:'N/A'?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-8">
                        <label><?php echo lang('MEETING_DESCRIPTION');?> :</label><br/>
                        <?=!empty($meeting_data[0]['meeting_description'])?$meeting_data[0]['meeting_description']:'N/A'?>
                    </div>
                    <div class="col-sm-4">
                        <label><?php echo lang('MEETING_USER');?> :</label><br/>
                        <?=!empty($meeting_data[0]['login_user_name'])?$meeting_data[0]['login_user_name']:''?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label><?php echo lang('PUBLIC_VISIBLE');?> :</label><br/>
                        <?php 
                            if($meeting_data[0]['is_public'] == '1')
                            {
                                echo "YES";
                            }else  if($meeting_data[0]['is_public'] == '0')
                            {
                                echo "NO";
                            }
                       
                        ?>
                    </div>
                   
                    <div class="col-sm-4">
                        <label><?php echo lang('PRIVATE_VISIBLE');?> :</label><br/>
                        <?php 
                            if($meeting_data[0]['is_private'] == '1')
                            {
                                echo "YES";
                            }else  if($meeting_data[0]['is_private'] == '0')
                            {
                                echo "NO";
                            }
                       
                        ?>
                    </div>
                    <div class="col-sm-4">
                        <label><?php echo lang('CREATE_AN_EVENT');?> :</label><br/>
                        <?php 
                            if($meeting_data[0]['is_event'] == '1')
                            {
                                echo "YES";
                            }else  if($meeting_data[0]['is_event'] == '0')
                            {
                                echo "NO";
                            }
                       
                        ?>
                    </div>
                     
                    
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label><?php echo lang('RECURRING_MEETING');?> :</label><br/>
                        <?php 
                            if($meeting_data[0]['is_recurring'] == '1')
                            {
                                echo "YES";
                            }else  if($meeting_data[0]['is_recurring'] == '0')
                            {
                                echo "NO";
                            }
                       
                        ?>
                    </div>
                    <?php
                    if($meeting_data[0]['is_recurring'] == '1')
                    {
                    ?>
                    <div class="col-sm-3">
                        <label><?php echo lang('RECURRING_TIME');?> :</label><br/>
                        <?=!empty($meeting_data[0]['recurring_repeat'])?$meeting_data[0]['recurring_repeat']:'N/A'?>
                    </div>
                    <div class="col-sm-3">
                        <label><?php echo lang('RECURRING_END_DATE');?> :</label><br/>
                        <?=!empty($meeting_data[0]['recurring_end_date'])?$meeting_data[0]['recurring_end_date']:'N/A'?>
                    </div>
                    <?php } ?>
                </div>
                <div class="form-group row">
                    <div class="col-sm-8">
                        <label><?php echo lang('MEETING_LOCATION');?> :</label><br/>
                        <?=!empty($meeting_data[0]['meeting_location'])?$meeting_data[0]['meeting_location']:'N/A'?>
                    </div>
                    <div class="col-sm-4">
                        <label><?= $this->lang->line('CREATED_DATE') ?> :</label><br/>
                        <?=!empty($meeting_data[0]['created_date'])?date('Y-m-d',  strtotime($meeting_data[0]['created_date'])):''?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label><?php echo lang('ATTACHMENT_FILE');?> : </label>
                        <ul class="files">
                        <?php
                        if(isset($meeting_attach_data) && count($meeting_attach_data) > 0)
                        {
                        
                            foreach ($meeting_attach_data as $data)
                            {
                                $file_name = $data['file_name'];
                                
                                $file_extension = explode('.',$file_name);
                                
                                $document_logo_file_name = getImgFromFileExtension($file_extension[1]);
                                $document_logo_file_path = base_url()."/uploads/images/icons64/".$document_logo_file_name;
                                
                                $image_path = base_url().$data['file_path']."/".$file_name;
                                ?>
                            <li id="contact_file_<?php echo $data['file_id'];?>" class="bd-contact-rmv">
                                <p class="text-center"><a href="<?php echo $image_path; ?>" download>
                                        <img src="<?php echo $document_logo_file_path; ?>" alt=""/>
                                    </a>
                                </p>
                                <p class="text-center"><a href="<?php echo $image_path; ?>" download><?php echo $file_name;?></a></p>
                                
                            </li>
                        <?php   }
                        }else
                        {
                            echo  "N/A";
                        }
                        ?>
                       			
                    </ul>
                        
                        
                    </div>
                </div>
          
               
           
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  
  </div>

<script>
$('.event_reminder_toggle').bootstrapToggle();
</script>