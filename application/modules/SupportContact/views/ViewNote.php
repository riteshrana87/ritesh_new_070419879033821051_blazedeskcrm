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
                        <label><?= $this->lang->line('NOTE_SUBJECT') ?> :</label><br/>
                        <?=!empty($note_data[0]['note_subject'])?$note_data[0]['note_subject']:''?>
                    </div>
                    <div class="col-sm-8">
                        <label><?= $this->lang->line('NOTE_DESCRIPTION') ?> :</label><br/>
                        <?=!empty($note_data[0]['note_description'])?$note_data[0]['note_description']:''?>
                    </div>
                </div>
          
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label><?= $this->lang->line('NOTE_CREATED_BY') ?> :</label><br/>
                        <?=!empty($note_data[0]['login_user_name'])?$note_data[0]['login_user_name']:''?>
                    </div>
                    <div class="col-sm-3">
                        <label>Contact :</label><br/>
                        <?=!empty($note_data[0]['contact_name'])?$note_data[0]['contact_name']:''?>
                    </div>
                    <div class="col-sm-3">
                        <label><?= $this->lang->line('ADD_TO_COMMUNICATION') ?> :</label><br/>
                       <?php
                       if(!empty($note_data[0]['add_to_communication']))
                       {
                           echo  "YES";
                       }else
                       {
                           echo  "NO";
                       }
                       
                       ?>
                    </div>
                    <div class="col-sm-3">
                        <label><?= $this->lang->line('CREATED_DATE') ?> :</label><br/>
                        <?=!empty($note_data[0]['created_date'])? date('Y-m-d',  strtotime($note_data[0]['created_date'])):''?>
                    </div>
                   
                </div>
           
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  
  </div>

