<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($view === true){
    $readonly = 'disabled';
}else{
    $readonly = '';
}
?>

<div class="modal-dialog ">

    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" title="<?=lang('close')?>" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><div class="modelTitle"><?=$this->lang->line('view_archive_campaign')?></div></h4>
        </div>

        <div class="pad-10">
            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('CAMPAIGN_NAME')?>  :</label>
                        <?=!empty($editRecord[0]['campaign_name'])?$editRecord[0]['campaign_name']:''?>
                    </div>

                    <div class = "col-sm-6">
                        <?php

                        $date_array = str_replace(" ","",microtime(FALSE));
                        $micro_number=substr(str_replace(".","",$date_array),1,15);
                        ?>
                        <label><?=$this->lang->line('campaign_number')?>:</label>
                        <?=!empty($editRecord[0]['campaign_auto_id'])?$editRecord[0]['campaign_auto_id']:$micro_number?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('TYPE_OF_CAMPAIGN')?>  :</label>
                        <?php echo $editRecord[0]['camp_type_name'];?>

                    </div>

                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('RESPONSIBLE_EMPLOYEE')?> :</label>
                        <?php
                        if(count($responsible_user_data) > 0 && $responsible_user_data != "0") {
                        $trm_res = '';
                        foreach($responsible_employee_data as $row){
                            if (in_array($row['login_id'], $responsible_user_data)){
                                ?>
                                <?php $trm_res .= $row['firstname'].' '.$row['lastname'].',';?>
                            <?php }}
                        echo rtrim($trm_res,',');
                        }else{
                            echo "N/A";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('START_DATE')?>  :</label>
                        <?=!empty($editRecord[0]['start_date'])?date("m/d/Y", strtotime($editRecord[0]['start_date'])):''?>
                    </div>

                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('END_DATE')?> :</label>
                        <?=!empty($editRecord[0]['end_date'])?date("m/d/Y", strtotime($editRecord[0]['end_date'])):''?>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <?php
                        if (isset($editRecord[0]['campaign_description']) && $editRecord[0]['campaign_description'] != '') {
                            $camp_description = $editRecord[0]['campaign_description'];
                        } else {
                            $camp_description = 'N/A';
                        }
                        ?>
                        <label><?=$this->lang->line('CAMPAIGN_DESCRIPTION')?>  :</label>
                        <?php echo $camp_description; ?>

                    </div>

                </div>
            </div>
            <div class="clr"></div>

            <div class="col-xs-12 col-md-6 no-left-pad col-sm-6">
                <?php if(!empty($editRecord[0]['budget_requirement']))
                { $edit_time_id = "budget_ammount_show";
                }else{
                    $edit_time_id = "budget_ammount";
                }?>
                <div class="form-group row">

                    <div class="col-xs-8 col-md-9 col-lg-9 no-right-pad bd-form-group">
                       <label> <?=$this->lang->line('BUDGET_REQUIRED')?></label>
                    </div>
                    <div class="clr"> </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
                        <?php if(!empty($editRecord[0]['budget_requirement'])){?>
                        <input name="budget_ammount" class="form-control" id="<?php echo $edit_time_id;?>" maxlength="20" value="<?=!empty($editRecord[0]['budget_ammount'])?$editRecord[0]['budget_ammount']:'0'?>"  placeholder="<?=$this->lang->line('BUDGET_AMOUNT')?>" <?php echo $readonly?> />
                        <?php }else{
                            echo $editRecord[0]['budget_requirement'];
                            ?>
                    <?php }?>
                    </div>
                </div> 
            </div>
            <div class="col-xs-12 col-md-6 col-lg-6 no-right-pad col-sm-6">
                <div class="form-group row">

                    <div class="col-xs-8 col-md-9 col-lg-9 no-right-pad bd-form-group">
                        <label><?=$this->lang->line('REVENUE_GOAL')?></label>
                    </div>
                    <div class="clr"> </div>
                    <div class="col-xs-12 col-lg-12 col-sm-12 col-md-12">
                        <?php if(!empty($editRecord[0]['revenue_goal'])){?>
                        <input name="revenue_amount" class="form-control" id="" maxlength="20" value="<?=!empty($editRecord[0]['revenue_amount'])?$editRecord[0]['revenue_amount']:'0'?>" placeholder="<?=$this->lang->line('REVENUE_AMOUNT')?>" <?php echo $readonly?>/>
                    <?php }else{
                            echo $editRecord[0]['budget_requirement'];
                            ?>
                    <?php }?>
                    </div>
                </div>
            </div>
            <div class="clr"> </div>
            <div class="col-xs-12 col-md-6 no-left-pad col-sm-6">
                <div class="form-group row">
                    <div class="col-xs-8 col-md-9 col-lg-9 no-right-pad bd-form-group">
                        <label><?=$this->lang->line('CAMPAIGN_BY_SUPPLIER')?></label>
                    </div>
                    <div class="clr"> </div>
                    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
                        <?php if(!empty($editRecord[0]['campaign_supplier'])){?>
                        <?php
                        $supplier_id = $editRecord[0]['supplier_id'];
                        if(count($supplier_id) > 0 && $supplier_id != "0"){
                        foreach($supplier_info as $row){
                            if($supplier_id == $row['supplier_id']){?>
                                <?php echo $row['supplier_name'];?>
                            <?php }}?>
                        <?php } else { echo "N/A"; }?>
                        <?php }else{
                            echo "N/A";
                            ?>
                    <?php }?>
                </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 no-right-pad col-sm-6">
                <div class="form-group row">

                    <div class="col-xs-8 col-md-9 col-lg-9 no-right-pad bd-form-group">
                        <label><?=$this->lang->line('RELATED_TO_PRODUCT')?></label>
                    </div>
                    <div class="clr"> </div>
                    <div class="col-xs-12 col-lg-12 col-sm-12 col-md-12">
                    <?php if(!empty($editRecord[0]['related_product'])){?>
                        <?php if (!empty($product_info) && count($product_info) > 0) {
                            $tem_pro = '';
                            ?>
                            <?php $blnkFlag = 0;?>
                            <?php foreach ($product_info as $row) { ?>
                                <?php if(!empty($product_data) && in_array($row['product_id'],$product_data)){$tem_pro .= $row['product_name'].',';} else { $blnkFlag++; } ?>
                                <?php if($blnkFlag == count($product_info)){ echo "N/A"; }?>
                            <?php }
                            echo rtrim($tem_pro,',');
                            ?>
                        <?php }  ?>
                    <?php }else{
                        echo "N/A";
                        ?>
                    <?php }?>

                    </div>
                </div>
            </div>
            <div class="clr"> </div>
            <div class="col-xs-12 col-md-6 no-left-pad">
                <div class="form-group">
                    <label> <?=$this->lang->line('ADD_CAMPAIGN_GROUP')?>  :</label>
                    <?php
                        $campaign_group_id = $editRecord[0]['campaign_group_id'];
                    if(!empty($campaign_group_id)){
                        foreach($campaign_group_info as $row){
                            if($campaign_group_id == $row['campaign_group_id']){?>
                                <?php echo $row['group_name'];?>
                            <?php }} ?>
                    <?php }else{
                        echo "N/A";
                        ?>
                    <?php }?>

                </div>
                <div class="clr"> </div>
                <div class="form-group">
                    <label><?=$this->lang->line('ADD_CAMPAIGN_RECEIPIENTS')?>:</label>
                    <div class="clr"> </div>
                            <?php
                            if(count($EstRecipientArray) > 0 && $EstRecipientArray != "0") {
                                $tem_rec = '';
                                foreach ($contact_info as $contact) { ?>
                                    <?php if (in_array('contact_' . $contact['contact_id'], $EstRecipientArray)) { ?>
                                        <?php $tem_rec .= $contact['contact_name'] . ','; ?>
                                    <?php }
                                }
                                echo rtrim($tem_rec, ',');
                            }else{
                                echo "N/A";
                            }
                            ?>
                </div>
            </div>
            <input type="hidden" name="id" id="id" value="<?=!empty($editRecord[0]['campaign_id'])?$editRecord[0]['campaign_id']:''?>" <?php echo $readonly?>/>
            <div class="col-xs-12 col-md-6 no-right-pad">
                <div id="" class="bd-dragimage row">
                    <?php

                    if (!empty($image_data)){
                        if (count($image_data) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                            $i = 15482564;
                            foreach ($image_data as $image) {
                                $path = $image['file_path'];
                                $name = $image['file_name'];
                                $arr_list = explode('.', $name);
                                $arr = $arr_list[1];
                                if (file_exists($path . '/' . $name)) { ?>
                                    <div id="img_<?php echo $image['file_id']; ?>" class="eachImage">
                                         <span id="<?php echo $i; ?>" class="preview">
           <a href='<?php echo base_url('Marketingcampaign/download/' . $image['file_id']); ?>' target="_blank">
               <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                   <img src="<?= base_url($path . '/' . $name); ?>"  width="75"/>
               <?php } else { ?>
                   <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/>
                       <p class="img_show"><?php echo $arr; ?></p>
                   </div>
               <?php } ?>
           </a>
                <p class="img_name"><?php echo $name; ?></p>
                <span class="overlay" style="display: none;"> <span class="updone">100%</span></span>
                <!--<input type="hidden" value="<?php echo $name; ?>" name="fileToUpload[]">-->
                </span> </div>
                                <?php } ?>
                                <?php
                                $i++;
                            }
                            ?>
                        <?php }  }?>
                </div>
                <div class="clr"> </div>
            </div>

        </div>
        <div class="clr"> </div><br/>
    </div>

</div>

<?php $this->load->view($js_content);?>
<!--</div>-->