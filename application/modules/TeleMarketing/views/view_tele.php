<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
?>   
<div class="modal-dialog">
    <div class="modal-content prodmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="title">
                    <?= lang('view_tele'); ?>
                </div>
            </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <!-- name -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="product name">
                            <?= lang('company_contact_name')?>
                        </label>
                        <p><?=!empty($tele_data[0]['tele_name'])?$tele_data[0]['tele_name']:''?></p>
                    </div>    
                </div>
                
                <!-- type -->
                <div class="col-xs-12 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label for="product type">
                            <?= lang('company_name')?>
                        </label>
                        <p><?=!empty($tele_data[0]['company_name'])?$tele_data[0]['company_name']:''?></p>
                    </div>
                </div>
            </div>
            
            <!-- group -->
            <div class="form-group">
                <label for="product group">
                    <?= lang('phone_no')?>
                </label>
                <?php if(empty($tele_data[0]['phone_no'])){?>
                <p>N/A</p>
                <?php } else {?>
                <p>
                <?php echo $tele_data[0]['phone_no'];?>
                <?php }?>
                </p>
            </div>    
            <!-- status -->
               <div class="form-group">
                <label for="product group">
                    <?= lang('status')?>
                </label>
                <?php if(empty($tele_data[0]['status'])){?>
                <p>N/A</p>
                <?php } else {?>
                <p>
                <?php if($tele_data[0]['status']=='1'){
					echo lang('pos_req');
					}elseif($tele_data[0]['status']=='2'){echo lang('pos_demo');}
					elseif($tele_data[0]['status']=='3'){echo lang('pos_became_client');}
					elseif($tele_data[0]['status']=='4'){echo lang('neg_not_int');}
					elseif($tele_data[0]['status']=='5'){echo lang('voice');}
					elseif($tele_data[0]['status']=='6'){echo lang('call_back');}?>
                <?php }?>
                </p>
            </div>    
            
            <!-- Description -->
            <div class="form-group">
                <label for="product description">
                    <?= lang('remark')?>
                </label>
                <?php if(empty($tele_data[0]['remark'])){?>
                    <p>N/A</p>
                <?php } else {?>
                    <p><?php echo $tele_data[0]['remark']; ?></p>
                <?php } ?>
            </div>
            
         
            
         
            
          
        </div>    
    </div>   
</div>
