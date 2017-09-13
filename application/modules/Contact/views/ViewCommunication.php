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
                <?php //pr($comm_data);?>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('COMM_DATE') ?> :</label><br/>
                        <?=!empty($comm_data[0]['comm_date'])?date('Y-m-d',  strtotime($comm_data[0]['comm_date'])):''?>
                    </div>
                    
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('COMM_TYPE') ?> :</label><br/>
                        <?php 
                            if($comm_data[0]['comm_type'] == "1")
                            {
                                echo lang('EMAIL_TYPE_EVENT');
                            }else if($comm_data[0]['comm_type'] == "2")
                            {
                                echo lang('EMAIL_TYPE_EMAIL_PROSPECT');
                            }else if($comm_data[0]['comm_type'] == "3")
                            {
                                echo lang('EMAIL_TYPE_NOTE');
                            }else
                            {
                                echo lang('EMAIL_TYPE_PERSONAL');
                            }
                            
                            ?>
                    </div>
                </div>
          
               
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('COMM_CONTACT') ?> :</label><br/>
                       <?php echo $comm_data[0]['sender_name'];?>
                    </div>
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('recipients') ?> :</label><br/>
                         <?php echo $comm_data[0]['receiver_name'];?>
                    </div>
                    
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('COMM_SUBJECT') ?> :</label><br/>
                        <?php echo $comm_data[0]['comm_subject'];?>
                    </div>
                    
                   
                </div>
          
                <div class="form-group row">
                     <div class="col-sm-6 ckeditorContent">
                        <label><?= $this->lang->line('EMAIL_CONTENT') ?> :</label><br/>
                        <?php echo $comm_data[0]['comm_content'];?>
                    </div>
                    
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('ATTACH_FILE') ?> :</label><br/>
                        <ul class="files">
                            
                            
                        
                        <?php 
                       
                        if(count($attach_file_data) > 0)
                        {
                            
                            foreach ($attach_file_data as $data)
                            {
                                 
                                $file_name = $data['file_name'];
                                
                                $file_extension = explode('.',$file_name);
                                
                                $document_logo_file_name = getImgFromFileExtension($file_extension[1]);
                                $document_logo_file_path = base_url()."/uploads/images/icons64/".$document_logo_file_name;
                                
                                $image_path = base_url().$data['file_path']."/".$file_name;
                                ?> 
                            <li>
                                <p class="text-center"><a href="<?php echo $image_path; ?>" download>
                                        <img class="img-responsive" src="<?php echo $document_logo_file_path; ?>" alt=""/>
                                    </a>
                                </p>
                                <p class="text-center"><a href="<?php echo $image_path; ?>" download><?php echo $file_name;?></a></p>
                               
                            </li>      
                    <?php   }
                        }else
                        {
                            echo  lang('NO_MAIL_ATTACHMENT');
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

