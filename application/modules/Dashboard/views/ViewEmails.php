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
                    <div class="col-sm-8">
                        <label><?= $this->lang->line('EMAIL_DATE') ?> :</label><br/>
                        <?=!empty($email_content[0]['created_date'])?date('Y-m-d',  strtotime($email_content[0]['created_date'])):''?>
                    </div>
                </div>
          
                <div class="form-group row">
                    <div class="col-sm-8">
                        <label><?= $this->lang->line('CREATED_DATE') ?> :</label><br/>
                        <?=!empty($note_data[0]['created_date'])? date('Y-m-d',  strtotime($note_data[0]['created_date'])):''?>
                    </div>
                </div>
                 
           
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  
  </div>

