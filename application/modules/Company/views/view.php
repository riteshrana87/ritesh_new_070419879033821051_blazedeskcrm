<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$readonly = 'disabled';
?>
<!-- Modal New Company-->
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?= lang('view_company') ?></div></h4>
        </div>
        <form id="from_model" name="from_model" method="post"  action="<?php echo base_url($path); ?>" > 
            <div class="modal-body">
                <input name="company_id" type="hidden" value="<?= !empty($editRecord[0]['company_id']) ? $editRecord[0]['company_id'] : '' ?>" />
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('country') ?> :</label>
                        <?php echo!empty($editRecord[0]['country_name']) ? $editRecord[0]['country_name'] : 'N/A' ?>
                    </div>
                    <div class="col-sm-6">
                        <label> <?= $this->lang->line('company_name') ?> :</label>

                        <?php echo!empty($editRecord[0]['company_name']) ? $editRecord[0]['company_name'] : 'N/A' ?>
                    </div>
                  
                </div>       
                <div class="form-group row">
                      <div class="col-sm-6">
                        <label> <?= $this->lang->line('branche') ?> :</label> 
                        <?php echo!empty($branch_data1[0]['branch_name']) ? $branch_data1[0]['branch_name'] : 'N/A' ?>

                    </div>
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('email') ?> :</label>
                        <?php echo!empty($editRecord[0]['email_id']) ? $editRecord[0]['email_id'] : 'N/A' ?>

                    </div>
                    
                </div>

                <div class="form-group row">
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('contact_no') ?> :</label>
                        <?php echo!empty($editRecord[0]['phone_no']) ? $editRecord[0]['phone_no'] : 'N/A' ?>
                    </div>
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('website') ?> :</label>
                        <?php echo!empty($editRecord[0]['website']) ? $editRecord[0]['website'] : '' ?>

                    </div>
                  
                </div>

                <div class="form-group row">
                      <div class="col-sm-6">
                        <label><?= $this->lang->line('address1') ?> :</label>
                        <?php echo!empty($editRecord[0]['address1']) ? $editRecord[0]['address1'] : 'N/A' ?>

                    </div>
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('address2') ?> :</label>
                        <?php echo!empty($editRecord[0]['address2']) ? $editRecord[0]['address2'] : 'N/A' ?>

                    </div>
                   
                </div>
                <div class="form-group row">
                     <div class="col-sm-6">
                        <label><?= $this->lang->line('city') ?> :</label>
                        <?php echo!empty($editRecord[0]['city']) ? $editRecord[0]['city'] : 'N/A' ?>

                    </div>
                    <div class="col-sm-6">
                        <label><?= $this->lang->line('state') ?> :</label>
                        <?php echo!empty($editRecord[0]['state']) ? $editRecord[0]['state'] : 'N/A' ?>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6"> 
                        <label><?= $this->lang->line('postal_code') ?> :</label>
                        <?php echo!empty($editRecord[0]['postal_code']) ? $editRecord[0]['postal_code'] : 'N/A' ?></div>
                    <div class="col-sm-6 form-group">
                        <label><?= $this->lang->line('logo_image') ?> :</label>
                            <?php 
                        $profileURL =FCPATH . PROFILE_PIC_UPLOAD_PATH . "/" . $editRecord[0]['logo_img'];
if ($editRecord[0]['logo_img'] && file_exists($profileURL)) {
                                ?>
                        <img class="img-responsive thumbnail" src="<?php echo base_url('uploads/company').'/'.$editRecord[0]['logo_img']; ?>">
                       
                            <?php } else { ?>
                         <img class="img-responsive thumbnail" src="<?php echo base_url('uploads/company/noimage.jpg');?>">
                            <?php } ?>
                 
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">

            </div>
        </form>
    </div>
</div>
