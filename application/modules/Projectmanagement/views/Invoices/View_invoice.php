<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath   = $invoice_view . '/' . $formAction;
?>
<div class="modal-dialog modal-lg" >
    <div class="modal-content">
    <form id="from-model" method="post" action="<?php echo base_url ($formPath); ?>" enctype="multipart/form-data"
              data-parsley-validate>
      <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><b><?=!empty($modal_title)?$modal_title:''?></b> </h4>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-xs-12 col-md-9 col-lg-9">
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="  control-label"><?= lang ('description') ?> </label>
                                <?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="  control-label"><?=lang('line_items')?> </label>
                        <div class = "form-group row" id="add_items">
                                <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-md-2"><label><?=lang('line_items')?></label></div>
                                        <div class="col-xs-12 col-md-1"><label><?=lang('qty_hrs')?></label></div>
                                        <div class="col-xs-12 col-md-2"><label><?=lang('rate')?></label></div>
                                        <!-- <div class="col-xs-12 col-md-2">Type</div> -->
                                        <div class="col-xs-12 col-md-2"><label><?=lang('discount')?>(%)</label></div>
                                        <div class="col-xs-12 col-md-2"><label><?=lang('tax_rate')?>(%)</label></div>
                                        <div class="col-xs-12 col-md-2"><label><?=lang('cost')?></label></div>
                                        
                                </div>
                        <?php if(!empty($item_details)){
                            foreach ($item_details as $row) { ?>
                                 <div class="col-xs-12 col-md-12 form-group newrow" id="item_edit_<?=$row['invoice_item_id']?>">
                                    <div class="col-xs-12 col-md-2">
                                    <span><?=!empty($row['item_name'])?$row['item_name']:''?></span>
                                    </div>
                                    <div class="col-xs-12 col-md-1">
                                    <span><?=!empty($row['qty_hours'])?$row['qty_hours']:''?></span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                    <span><?=!empty($row['rate'])?$row['rate']:''?></span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                    <span><?=!empty($row['discount'])?$row['discount']:''?></span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                    <?php if (count($taxes) > 0) { ?>
                                                <?php foreach ($taxes as $tax) { ?>
                                                <?php if(!empty($row['tax_rate']) && $row['tax_rate'] == $tax["tax_id"]){ ?>
                                                    <p><?php echo $tax["tax_percentage"]; ?></p>
                                                <?php
                                                }
                                            } }
                                            ?>
                                   
                                    </div>
                                  
                                    <div class="col-xs-12 col-md-2">
                                    <span><?=!empty($row['cost'])?$row['cost']:''?></span>
                                    </div>
                                    
                                </div>
                            <?php } }?>
                       
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="  control-label"><?=lang('total_amount')?> : <span id="total_item"><?= !empty($edit_record[0]['amount']) ? $edit_record[0]['amount'] : '0.00' ?></span></label>
                    </div>
                  
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <label class="control-label"><?=lang('files')?>  : </label>
                                <div id="dragAndDropFiles" class="">
                                           
                                            <?php
                                            if(!empty($invoice_files)){
                                                $img_data = $invoice_files;
                                                
                                                $i = 15482564;
                                                foreach($img_data as $image){
                                                    $path = $image['file_path'];
                                                    $name = $image['file_name'];
                                                    $arr_list = explode('.',$image['file_name']);
                                                    $arr = $arr_list[1];
                                                   if (file_exists($path .$name)) { ?>
                                                    
                                                            <!--<a onclick="delete_row(<?php echo $i;?>)"class="remove_drag_img" id="delete_row">×</a>-->
                                                       <div id="img_<?php echo $image['invoice_file_id']; ?>" class="eachImage">
                                                            
                                                            
                                                            <span id="<?php echo $i; ?>" class="preview">
                                                            <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                            <a class="task-previmg" title="view" href='<?php echo base_url($invoice_view . '/download/' . $image['invoice_file_id']); ?>' >
                                                            <?php } else { ?>
                                                            <a title="view" href='javascript:;' >
                                                            <?php } ?>
                                                            <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                            <img src="<?= base_url($path . $name); ?>" class="img-responsive" />
                                                            <?php } else { ?>
                                                            <div><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png" class="img-responsive"/>
                                                              <p class="img_show"><?php echo $arr; ?></p>
                                                            </div>
                                                            <?php } ?>
                                                            </a>
                                                            <p class="img_name"> <span><?php echo (strlen($name) > 15) ? substr($name, 0, 15) . '...' :$name; ?></span>
                                                              <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                              <?php /*<button class="btn btn-default" onclick='showPreview("<?php echo base_url($image['file_path'] . '/' . $image['file_name']); ?>");'><i class="fa fa-search"></i></button>*/ ?>
                                                              <?php } ?>
                                                              <?php /*<a href='<?php echo base_url($invoice_view . '/download/' . $image['invoice_file_id']); ?>' target="_blank" class="btn btn-default"><i class="fa fa-download "></i></a> <span class="overlay" style="display: none;"> <span class="updone">100%</span></span>*/ ?> 
                                                              <!--                                                <input type="hidden" value="<?php echo $name; ?>" name="fileToUpload[]">--> 
                                                            </span> 
                                                        </div>
                                                    <?php }?>
                                                    <?php $i++; }?>
                                                    <div id="deletedImagesDiv"></div>
                                            <?php }?>
                                </div>
                                <div class="clr"> </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <label class="  control-label"><?=lang('CONTACT_VIEW_NOTES')?> </label>
                                <span><?= !empty($edit_record[0]['notes']) ? $edit_record[0]['notes'] : '' ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="  control-label"><?=lang('payment_schedule')?> </label>
                        <div class = "form-group row" id="add_payment">
                                <div class="col-xs-12 col-md-12">
                                        <div class="col-xs-12 col-md-2"><label><?=lang('cost_amount')?></label></div>
                                        <div class="col-xs-12 col-md-2"></div>
                                        <div class="col-xs-12 col-md-3"><label><?=lang('due_on')?></label></div>
                                        <div class="col-xs-12 col-md-4"><label><?=lang('CONTACT_VIEW_NOTES')?></label></div>
                                        
                                </div>
                                 <!-- edit payment --> 
                                 <?php if(!empty($payment_details)){
                                    foreach ($payment_details as $row) { ?>
                                    <div class="col-xs-12 col-md-12 form-group newrow" id="payment_edit_<?=$row['invoice_payment_id']?>">
                                    <div class="col-xs-12 col-md-2">
                                    <span><?=!empty($row['amount'])?$row['amount']:''?></span>
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                   <?php if (count($country_info) > 0) { ?>
                                                <?php foreach ($country_info as $country) { ?>
                                                <?php if(!empty($row['amount_per']) && $row['amount_per'] == $country["country_id"]){?>
                                                    <span><?php echo $country["currency_symbol"]; ?></span>
                                                <?php
                                                } }
                                            }
                                            ?>
                                        
                                    </div>
                                    <div class="col-xs-12 col-md-3 date">
                                        <div class="input-group date due_on">
                                            <span><?php
                                               if (isset($row['due_on']) && $row['due_on'] != '0000-00-00') {
                                                   echo configDateTime($row['due_on']);
                                               };
                                               ?></span>
                                               
                                        </div>
                                        <span id="dt_err_edit_<?=$row['invoice_payment_id']?>"></span>
                                    </div>
                                    <div class="col-xs-12 col-md-4">
                                    <span><?=!empty($row['notes'])?$row['notes']:''?></span>
                                    </div>

                                   
                                    </div>
                                <?php } }?>       
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="  control-label"><?=lang('total_amount_pay_to_client')?>: <span id="total_payment"><?= !empty($edit_record[0]['total_payment']) ? $edit_record[0]['total_payment'] : '0.00' ?></span></label>
                    </div>
                   
                    <div class="form-group">
                    
                          
                       <?=lang('send_invoice')?> : <label><?php if(!empty($edit_record[0]['send_invoice_client'])){echo 'Yes';}else{echo 'No';}?></label>   
                    </div>
                </div>
              <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="form-group row">
                            <label class="col-lg-12 control-label"><?=lang('client')?> </label>

                            <div class="col-lg-12">
                                <?php foreach ($company_info as $company) { ?>
                                <?php if ($EstClntArray == 'company_' . $company['company_id']) {?>
                                    <span class="col-lg-12"><?php echo $company['company_name']; ?></span>
                                <?php } } ?>
                                <?php foreach ($client_info as $client) { ?>
                                <?php if ($EstClntArray == 'client_' . $client['prospect_id']) { ?>
                                   <span class="col-lg-12"><?php echo $client['prospect_name']; ?></span>
                                <?php } } ?>
                                <?php foreach ($contact_info as $contact) { ?>
                                <?php if ($EstClntArray == 'contact_' . $contact['contact_id']) { ?>
                                  <span class="col-lg-12"><?php echo $contact['contact_name']; ?></span>
                                <?php } } ?>
                                
                            </div>

                    </div>
                    <div class="form-group" id="ShowRecipientAsPerComapny">
                    <label class="col-lg-12 control-label"><?=lang('recipients')?> </label>
                       <?php foreach ($client_info as $client) { ?>
                            <?php if (in_array("client_" . $client['prospect_id'], $EstRecipientArray)) {
                                    ?>
                              <span class="col-lg-12"> <?php echo $client['prospect_name']; ?></span>
                            <?php } }?>
                       
                            <?php foreach ($contact_info as $contact) { ?>
                            <?php if (in_array("contact_" . $contact['contact_id'], $EstRecipientArray)) {?>
                                <span class="col-lg-12"><?php echo $contact['contact_name']; ?></span>
                            <?php } } ?>
                        
                   
                </div>
                    <div class="form-group row">
                            <label class="col-lg-12 control-label"><?=lang('not_associat_project_id')?> </label>

                            <div class="col-lg-12">
                                
                                    <?php
                                    if (!empty($projects_data) && !empty($edit_record[0]['not_associat_project_id'])) {
                                        foreach ($projects_data as $row) {
                                            ?>
                                            <?php if (!empty($edit_record[0]['not_associat_project_id']) && $edit_record[0]['not_associat_project_id'] == $row['project_id']) {?>
                                           <span><?= ucfirst ($row['project_name']) ?></span>
                                            <?php
                                        } }
                                    }else{
                                        echo "<span>".lang('no_option_selected')."</span>";
                                    }
                                    ?>
                                
                            </div>

                    </div>
                    <div class="form-group row">
                            <label class="col-lg-12 control-label"><?=lang('invoice')?> #</label>

                            <div class="col-lg-12">
                                <span><?= !empty($edit_record[0]['invoice_code']) ? $edit_record[0]['invoice_code'] : $invoice_code ?></span>
                            </div>

                    </div>
                    <div class="form-group row">
                            <label class="col-lg-12 control-label"><?=lang('date_of_creation')?></label>

                            <div class="col-lg-12">
                               <span><?php if(isset($edit_record[0]['created_date'])){echo configDateTime($edit_record[0]['created_date']); }else{echo configDateTime();}?></span>
                            </div>

                    </div>
                    <div class="form-group row">
                            <label class="col-lg-12 control-label"><?=lang('show_in_client_area')?></label>
                            <div class="col-lg-12">
                            <label><?php if(!empty($edit_record[0]['show_in_client_area'])){echo 'Yes';}else{echo 'No';}?></label>
                               
                            </div>

                    </div>
                    <?php /*<div class="form-group row">
                            <label class="col-lg-12 control-label">Auto Send?</label>
                            <div class="col-lg-12">
                               <select name="auto_send" class="form-control" placeholder="">
                                    <option  <?php if (empty($edit_record[0]['auto_send'])) { echo 'selected="selected"';  } ?> value="0">No</option>
                                    <option <?php if (!empty($edit_record[0]['auto_send'])) { echo 'selected="selected"';  } ?> value="1">Yes</option>
                                </select>
                            </div>

                    </div>
                    */?>
                    <div class="form-group row">
                            <label class="col-lg-12 control-label"><?=lang('recurring')?></label>
                            <div class="col-lg-12">
                            <span><?php if(!empty($edit_record[0]['recurring'])){echo ($edit_record[0]['recurring'] == 1)?'Weekly':'Monthly';}else{echo lang('no_recurring');}?></span>
                              
                            </div>
                    </div>
                    <div class="form-group row">
                            <?php $req=''; if (!empty($edit_record[0]['recurring']) && $edit_record[0]['recurring'] == 1) { $sty='style="display:block;"';$req='required'; }else{$sty='style="display:none;"';}?>
                            <?php $req1=''; if (!empty($edit_record[0]['recurring']) && $edit_record[0]['recurring'] == 2) {$sty1='style="display:block;"'; $req1='required';}else{$sty1='style="display:none;"';}?>
                            <div class="col-lg-12" id="weekly" <?=$sty?>>
                            <?php $weekday = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"); ?>
                            
                                <?php foreach ($weekday as $day) { ?>
                                 <?php if (!empty($edit_record[0]['recurring_time']) && $edit_record[0]['recurring_time'] == $day) {?>
                                   <span><?=$day?></span>
                                <?php } }?>
                        
                            
                            </div>
                            
                            <div class="col-lg-12" id="monthly" <?=$sty1?>>
                                <div class="input-group date recurring_time">
                                    <span><?php
                                       if (!empty($edit_record[0]['recurring_time']) && $edit_record[0]['recurring_time'] != '0000-00-00') {
                                           echo configDateTime($edit_record[0]['recurring_time']);
                                       };
                                       ?></span>
                                       
                                </div>
                            <span id="dt_err"></span>
                            </div>

                    </div>
                    <div class="form-group row">
                            <label class="col-lg-12 control-label"><?=lang('currency')?></label>
                            <div class="col-lg-12">
                                    <?php if (count($country_info) > 0) { ?>
                                                <?php foreach ($country_info as $country) { ?>
                                                <?php if(!empty($edit_record[0]['currency']) && $edit_record[0]['currency'] == $country["country_id"]){?>
                                                    <?php echo $country["currency_symbol"]; ?>
                                                <?php
                                                } } } ?>
                               
                            </div>

                    </div>
              </div>
              <div class="clr"></div>
            </div>
      </div>
      <div class="modal-footer">
            <div class="text-center">
                <input type="hidden" id="invoice_id" name="invoice_id"
                       value="<?= !empty($edit_record[0]['invoice_id']) ? $edit_record[0]['invoice_id'] : '' ?>">
                 <input type="hidden" id="delete_item_id" name="delete_item_id"
                       value="">
                   <input type="hidden" id="delete_payment_id" name="delete_payment_id"
                       value="">
                   <input type="hidden" name="client_name" id="client_name" value="<?=!empty($client_name)?$client_name:''?>">



            </div>
            <div class="clr"></div>
      </div>
      </form>
    </div>
  </div>
<style type="text/css">
    .parsley-required {
        word-break: normal;
        word-wrap: normal;
    }
</style>
<?php //echo form_close(); ?>
