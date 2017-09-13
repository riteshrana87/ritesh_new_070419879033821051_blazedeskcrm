<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!empty($editRecord)){
    $formAction = 'updatedata';
}else{
    $formAction = 'insertdata';
}
$path = $sales_view.'/'.$formAction;

?>
<link href="<?= base_url() ?>uploads/custom/css/bootstrap-toggle.css" rel="stylesheet">
<link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">

<!--<div id="createSalesCampaign" class="modal fade bs-sales-campaign-modal" tabindex="-1" role="dialog">-->
    <div class="modal-dialog ">
        <form role="form" name="frm_createcampaign" id="frm_createcampaign" enctype="multipart/form-data" action="<?php echo base_url($path);?>" method="post">
            <div class="modal-content">
                <div class="pad-10">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4><b id="set_label"><?php echo $modal_title;?></b></h4>

                    <div class="col-xs-12 col-md-6 no-left-pad">
                        <div class="form-group">
                            <input type="text" name="campaign_name" class="form-control" id="campaign_name" placeholder="<?=$this->lang->line('CAMPAIGN_NAME')?>" value="<?=!empty($editRecord[0]['campaign_name'])?$editRecord[0]['campaign_name']:''?>" />
                            <span class="text-danger" id="campaign_name_error"></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 no-right-pad">
                        <div class="form-group">
                            <?php

                            $date_array = str_replace(" ","",microtime(FALSE));
                            $micro_number=substr(str_replace(".","",$date_array),1,15);
                            ?>
                            <input type="text" name="campaign_auto_id" class="form-control" id="campaign_auto_id" placeholder="<?php echo $micro_number;?>" value="<?=!empty($editRecord[0]['campaign_auto_id'])?$editRecord[0]['campaign_auto_id']:$micro_number?>" readonly="" />

                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-md-6 no-left-pad">
                        <div class="form-group">
                            <select class="selectpicker form-control" name="campaign_type_id" id="campaign_type_id" >
                                <option value="" selected=""><?=$this->lang->line('TYPE_OF_CAMPAIGN')?></option>
                                <?php
                                    $campaign_type = $editRecord[0]['campaign_type_id'];?>
                                    <?php foreach($campaign_type_info as $row){
                                        if($campaign_type == $row['camp_type_id']){?>
                                            <option selected value="<?php echo $row['camp_type_id'];?>"><?php echo $row['camp_type_name'];?></option>
                                        <?php }else{?>
                                            <option value="<?php echo $row['camp_type_id'];?>"><?php echo $row['camp_type_name'];?></option>

                                        <?php }}?>
                            </select>
                            <span class="text-danger" id="campaign_type_error"></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 no-right-pad">
                        <div class="form-group">
                            <select class="selectpicker form-control" name="responsible_employee_id" id="responsible_employee_id">
                                <option value="" selected=""><?=$this->lang->line('RESPONSIBLE_EMPLOYEE')?></option>
                                <?php
                                    $responsible_employee = $editRecord[0]['responsible_employee_id'];
                                    foreach($contact_info as $row){
                                        if($responsible_employee == $row['contact_id']){?>
                                            <option selected value="<?php echo $row['contact_id'];?>"><?php echo $row['contact_name'];?></option>
                                        <?php }else{?>
                                            <option value="<?php echo $row['contact_id'];?>"><?php echo $row['contact_name'];?></option>

                                        <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-md-6 no-left-pad">
                        <div class="form-group">
                            <label> Start date</label>
                            <div class='input-group date' id='start_date'>
                                <input type='text' class="form-control" name="start_date"  placeholder="<?=$this->lang->line('START_DATE')?>" value="<?=!empty($editRecord[0]['start_date'])?date("m/d/Y", strtotime($editRecord[0]['start_date'])):''?>" />
                                <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                            <span class="text-danger" id="start_date_error"></span>


                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 no-left-pad">
                        <div class="form-group">
                            <label> End date</label>
                            <div class='input-group date' id='end_date'>
                                <input type='text' class="form-control" name="end_date" placeholder="<?=$this->lang->line('END_DATE')?>" value="<?=!empty($editRecord[0]['end_date'])?date("m/d/Y", strtotime($editRecord[0]['end_date'])):''?>" />
                                <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                            <span class="text-danger" id="end_date_error"></span>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-md-12 no-left-pad">
                        <div class="form-group">
                            <textarea class="form-control" name="campaign_description" rows="5" id="campaign_description" placeholder="<?=$this->lang->line('CAMPAIGN_DESCRIPTION')?>"><?=!empty($editRecord[0]['campaign_description'])?$editRecord[0]['campaign_description']:''?></textarea>
                        </div>
                    </div>
                    <div class="clr"> </div>
                    <div class="col-xs-12 col-md-6 no-left-pad">
                        <?php if(!empty($editRecord[0]['budget_requirement']))
                        { $edit_time_id = "budget_ammount_show";
                        }else{
                            $edit_time_id = "budget_ammount";
                        }?>
                        <div class="form-group">
                            <div class="col-xs-6 col-md-6 no-left-pad">
                                <div class="btn-group btn-toggle">
                                    <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="budget_requirement" name="budget_requirement" onChange="toggle_show(<?php echo "'#".$edit_time_id."'";?>, this)" <?=!empty($editRecord[0]['budget_requirement'])?'checked="checked"':''?>/>

                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6 no-right-pad">
                                <label><?=$this->lang->line('BUDGET_REQUIRED')?><label>
                            </div>
                            <div class="clr"> </div><br/>
                            <input name="budget_ammount" class="form-control" id="<?php echo $edit_time_id;?>" maxlength="20" value="<?=!empty($editRecord[0]['budget_ammount'])?$editRecord[0]['budget_ammount']:''?>"  placeholder="<?=$this->lang->line('BUDGET_AMOUNT')?>" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 no-right-pad">
                        <?php if(!empty($editRecord[0]['budget_requirement']))
                        { $revenue_time_id = "revenue_amount_show";
                        }else{
                            $revenue_time_id = "revenue_amount";
                        }?>
                        <div class="form-group">
                            <div class="col-xs-6 col-md-6 no-left-pad">
                                <div class="btn-group btn-toggle">
                                    <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="revenue_goal" name="revenue_goal" onChange="toggle_show(<?php echo "'#".$revenue_time_id."'";?>, this)" <?=!empty($editRecord[0]['budget_requirement'])?'checked="checked"':''?>/>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6 no-right-pad">
                                <label> <?=$this->lang->line('REVENUE_GOAL')?></label>
                            </div>
                            <div class="clr"> </div><br/>
                            <input name="revenue_amount" class="form-control" id="<?php echo $revenue_time_id;?>" maxlength="20" value="<?=!empty($editRecord[0]['revenue_amount'])?$editRecord[0]['revenue_amount']:''?>" placeholder="<?=$this->lang->line('REVENUE_AMOUNT')?>" />
                    </div>
                    </div>
                    <div class="clr"> </div>
                    <div class="col-xs-12 col-md-6 no-left-pad">

                        <?php if(!empty($editRecord[0]['budget_requirement']))
                        { $campaign_time_id = "campaign_amount_show";
                        }else{
                            $campaign_time_id = "campaign_amount";
                        }?>
                        <div class="form-group">
                            <div class="col-xs-6 col-md-6 no-left-pad">
                                <div class="btn-group btn-toggle">
                                    <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="campaign_supplier" name="campaign_supplier" onChange="toggle_show(<?php echo "'#".$campaign_time_id."'";?>, this)" <?=!empty($editRecord[0]['budget_requirement'])?'checked="checked"':''?>/>

                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6 no-right-pad">
                                <label><?=$this->lang->line('CAMPAIGN_BY_SUPPLIER')?><label>
                            </div>
                            <div class="clr"> </div><br/>

                            <select class="form-control selectpicker" name="supplier_id" id="<?php echo $campaign_time_id;?>">
                                <option value="" selected=""><?=$this->lang->line('SELECT_SUPPLIER')?></option>
                                <?php
                                    $supplier_id = $editRecord[0]['supplier_id'];
                                    foreach($supplier_info as $row){
                                        if($supplier_id == $row['supplier_id']){?>
                                            <option selected value="<?php echo $row['supplier_id'];?>"><?php echo $row['supplier_name'];?></option>
                                        <?php }else{?>
                                            <option value="<?php echo $row['supplier_id'];?>"><?php echo $row['supplier_name'];?></option>
                                        <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 no-right-pad">
                        <?php if(!empty($editRecord[0]['budget_requirement']))
                        {
                            $related_time_id = "related_amount_show";
                        }else{
                            $related_time_id = "related_amount";
                        }?>
                        <div class="form-group">
                            <div class="col-xs-6 col-md-6 no-left-pad">
                                <div class="btn-group btn-toggle">
                                    <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="related_product" name="related_product" onChange="toggle_show(<?php echo "'#".$related_time_id."'";?>, this)" <?=!empty($editRecord[0]['budget_requirement'])?'checked="checked"':''?>/>

                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6 no-right-pad">
                                <label><?=$this->lang->line('RELATED_TO_PRODUCT')?><label>
                            </div>
                            <div class="clr"> </div><br/>
                            <select class="form-control selectpicker" name="product_id" id="<?php echo $related_time_id;?>" >
                                <option value="" selected=""><?=$this->lang->line('SELECT_PRODUCT')?></option>

                                <?php
                                    $product_id = $editRecord[0]['product_id'];

                                    foreach($product_info as $row){
                                        if($product_id == $row['product_id']){?>
                                            <option selected value="<?php echo $row['product_id'];?>"><?php echo $row['product_name'];?></option>
                                        <?php }else{?>
                                            <option value="<?php echo $row['product_id'];?>"><?php echo $row['product_name'];?></option>
                                        <?php }}?>
                            </select>
                        </div>
                    </div>
                    <div class="clr"> </div>
                    <div class="col-xs-12 col-md-6 no-left-pad">
                        <div class="form-group">
                <select class="form-control selectpicker" name="campaign_group_id" id="campaign_group_id">
            <option value="" selected=""><?=$this->lang->line('ADD_CAMPAIGN_GROUP')?></option>                                        <?php

                                    $campaign_group_id = $editRecord[0]['campaign_group_id'];
                                    foreach($campaign_group_info as $row){
                                        if($campaign_group_id == $row['campaign_group_id']){?>
                                            <option selected value="<?php echo $row['campaign_group_id'];?>"><?php echo $row['group_name'];?></option>
                                        <?php }else{?>
                                            <option value="<?php echo $row['campaign_group_id'];?>"><?php echo $row['group_name'];?></option>
                                        <?php }} ?>
                            </select>
                        </div>
                        <div class="clr"> </div>
                        <div class="form-group">
                            <label class="control-label" for="inputCampaign" placeholder="<?=$this->lang->line('ADD_CAMPAIGN_RECEIPIENTS')?>"><?=$this->lang->line('ADD_CAMPAIGN_RECEIPIENTS')?></label>
                            <select multiple class="chosen-select" name="contact_id[]" id="compaign_receipient">

                                <option value=""></option>
                                <?php foreach($contact_info as $row){?>
                                    <?php if (in_array($row['contact_id'], $content_data)){?>
                                    <option selected="selected"  value="<?php echo $row['contact_id'];?>" ><?php echo $row['contact_name'];?></option>
                                        <?php }else{?>
                                        <option value="<?php echo $row['contact_id'];?>" ><?php echo $row['contact_name'];?></option>
                                <?php }} ?>
                            </select>
                        </div>
                    </div>
            <input type="hidden" name="id" id="id" value="<?=!empty($editRecord[0]['campaign_id'])?$editRecord[0]['campaign_id']:''?>" />
                    <div class="col-xs-12 col-md-6 no-right-pad">

                        <!-- <div class="form-group">
                             <input type="file" name="fileToUpload" id="fileToUpload">


                             <div id="display_filename"></div>
                         </div>-->
                        <div id="dragAndDropFiles" class="uploadArea">
                            <h1>Drop Images Here</h1>
                            <!--<input type="file" name="fileUpload[]" id="upl" multiple />-->
                            <?php
                            if(!empty($editRecord)){
                            $file_img = $editRecord[0]['file'];
                            $img_data= explode(',',$file_img);
                            $i = 15482564;
                            foreach($img_data as $image){
                                if(!file_exists($this->config->item('Campaign_img_base_url').$image)){?>
                            <div id="<?php echo $i;?>" class="eachImage"><a onclick="delete_row(<?php echo $i;?>)"                                    class="remove_drag_img" id="delete_row">×</a>
                                        <span id="<?php echo $i;?>" class="preview">
                                        <img src="<?=$this->config->item('Campaign_img_base_url').$image ?>"  width="75"/>
                                        <span class="overlay" style="display: none;">
                                            <span class="updone">100%</span></span>
                                            <input type="hidden" value="<?php echo $image;?>" name="fileToUpload[]">
                                        </span>
                                <div id="<?php echo $i;?>" class="progress"><span style="width: 100%;"></span></div></div>
                    <?php }?>
                            <?php $i++; }?>
                            <?php }?>


                        </div>
                        <div class="clr"> </div>
                    </div>
                </div>
                <div class="clr"> </div><br/>
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" id="camp_submit_btn" value="<?=$this->lang->line('CREATE_CAMPAIGN')?>">
                </div>

                <div class="clr"> </div><br/>
            </div>
        </form>
    </div>
<script src="<?= base_url() ?>uploads/custom/js/bootstrap-toggle.js"></script>
<script src="<?= base_url() ?>uploads/custom/js/bootstrap-toggle.js"></script>
<script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script>

<?php $this->load->view($js_content);?>
<!--</div>-->