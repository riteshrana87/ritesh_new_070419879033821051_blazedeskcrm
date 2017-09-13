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
                <div class="col-sm-6">
                    <label><?= $this->lang->line('EVENT_TITLE') ?> :</label>
                    <?=!empty($event_data[0]['event_title'])?$event_data[0]['event_title']:''?>
                </div>
                
                <div class="col-sm-6">
                    <label><?= $this->lang->line('EVENT_DATE') ?> :</label>
                  <?php 
                                $event_date = date('Y-m-d',  strtotime($event_data[0]['event_date']));
                                echo calculate_day_left($event_date);?>
                </div>
            </div>
          
           
          
            <div class="form-group row">
                <div class="col-sm-12">
                    <label><?= $this->lang->line('EVENT_NOTE') ?> :</label>
                      <?=!empty($event_data[0]['event_note'])?$event_data[0]['event_note']:''?>
                </div>
            </div>
          
            <div class="form-group row">
                <div class="col-sm-6">
                    <label><?= $this->lang->line('reminder?') ?> :</label>
                     
                   <?php 
                            
                            if($event_data[0]['event_remember'] == '0')
                            {
                                echo "OFF";
                            }else  if($event_data[0]['event_remember'] == '1')
                            {
                                echo "ON";
                            }?>
                            
                
                </div>
                <div class="col-sm-6">
                    <label>Event Image :</label><br/>
                      <?php
                                if($event_data[0]['event_image'] != '')
                                {
                                    $img_path = base_url()."uploads/events/".$event_data[0]['event_image'];
                                }else
                                {
                                    $img_path = base_url()."uploads/images/noimage.jpg";
                                }
                            
                    ?>
                            <img src="<?php echo $img_path; ?>" width="48"/>
                </div>
            </div>
           
      </div>
      <div class="modal-footer">
       
      </div>
    </div>
  
  </div>

