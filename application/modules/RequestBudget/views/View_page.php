<?php
if (isset($display) && $display == true) {
    $action_title = "View";
    $disable = 'disabled';
} else {
    $disable = '';
}
?>

<link href="<?= base_url() ?>uploads/custom/css/projectmanagement/parsley.css" rel="stylesheet">
<link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" title="<?=lang('close')?>" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><span id="type_action"></span><?= $this->lang->line('VIEW_BUDGET_REQUEST_SALES_CAMPAIGN') ?></h4>
        </div>
        <div class="modal-body">
            <div class="pad-10">
                    <div class="col-xs-12 col-md-12 no-left-pad">
                        <div class = "form-group row">
                            <div class = "col-sm-6">
                                <label><?= $this->lang->line('SELECT_COMPAIGN') ?>  :</label>
                                <?php foreach ($compaign_master as $compaign_master_array) { ?>
                                    <?php if (isset($campaign_data[0]['campaign_id']) && $campaign_data[0]['campaign_id'] == $compaign_master_array['campaign_id']) { ?>
                                        <?php echo $compaign_master_array['campaign_name'] ?>
                                    <?php }
                                }
                                ?>
                            </div>

                            <div class = "col-sm-6">
                                <label><?=$this->lang->line('campaign_number')?> :</label>
                                <?php
                                if (isset($campaign_data[0]['campaign_auto_id']) && $campaign_data[0]['campaign_auto_id'] != '') {
                                    echo $campaign_data[0]['campaign_auto_id'];
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12 no-left-pad">
                        <div class = "form-group row">
                            <div class = "col-sm-6">
                                <label><?= $this->lang->line('TYPE_OF_CAMPAIGN') ?> :</label>
                                <?php echo $campaign_data[0]['camp_type_name'];?>
                            </div>
                            <div class = "col-sm-6">
                                <label><?= $this->lang->line('RESPONSIBLE_EMPLOYEE') ?>  :</label>
                                    <?php
                                    if(count($responsible_user_data) > 0 && $responsible_user_data != "0"){
                                    $tmp_res = '';
                                    foreach($responsible_employee_data as $row){
                                       if (in_array($row['login_id'], $responsible_user_data)){ ?>
                                            <?php $tmp_res .= $row['firstname'].' '.$row['lastname'].',';?>
                                        <?php }}
                                    echo rtrim($tmp_res,',');

                                    ?>
                                    <?php }else{
                                        echo "N/A";
                                        ?>

                                    <?php }?>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-md-12 no-left-pad">
                        <div class = "form-group row">
                            <div class = "col-sm-6">
                                <label><?= $this->lang->line('START_DATE') ?> :</label>
                                <?= !empty($campaign_data[0]['start_date']) ? date("m/d/Y", strtotime($campaign_data[0]['start_date'])) : '' ?>
                            </div>

                            <div class = "col-sm-6">
                                <label><?= $this->lang->line('END_DATE') ?>  :</label>
<?= !empty($campaign_data[0]['end_date']) ? date("m/d/Y", strtotime($campaign_data[0]['end_date'])) : '' ?>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-md-12 no-left-pad">
                        <div class = "form-group row">
                            <div class = "col-sm-12">
                                <?php
                                if (isset($campaign_data[0]['campaign_description']) && $campaign_data[0]['campaign_description'] != '') {
                                    $camp_description = $campaign_data[0]['campaign_description'];
                                } else {
                                    $camp_description = "N/A";
                                }
                                ?>
                                <label class="mar-tb0"><?= $this->lang->line('CAMPAIGN_DESCRIPTION') ?> :</label>
<?php echo $camp_description; ?>
                            </div>

                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-md-12 no-left-pad">
                        <div class = "form-group row">
                            <div class = "col-sm-6">
                                <label> <?= $this->lang->line('BUDGET_AMOUNT') ?> :</label>
                                <?php
                                if (isset($campaign_data[0]['budget_ammount']) && $campaign_data[0]['budget_ammount'] != '') {
                                    echo $campaign_data[0]['budget_ammount'];
                                }
                                ?>
                            </div>

                            <div class = "col-sm-6">
                                <label><?= $this->lang->line('REVENUE_GOAL') ?>  :</label>
                                <?php
                                if (isset($campaign_data[0]['revenue_goal']) && $campaign_data[0]['revenue_goal'] != '') {
                                    echo $campaign_data[0]['revenue_goal'];
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>

                    <div class="col-xs-12 col-md-12 no-left-pad">
                        <div class = "form-group row">
                            <div class = "col-sm-6">
                                <label><?= $this->lang->line('BUDGET_REQUEST_FOR_SUPPLIER') ?> :</label>
                                <div class="clr"></div>
                                <?php
                                foreach ($supplier_list as $supplier) {
                                    ?>
                                    <?php if (isset($campaign_data[0]['supplier_id']) && $campaign_data[0]['supplier_id'] == $supplier['supplier_id']) { ?>
        <?php echo $supplier['supplier_name']; ?>
    <?php }
}
?>
                            </div>

                            <div class = "col-sm-6">
                                <label><?= $this->lang->line('BUDGET_REQUEST_FOR_PRODUCT') ?>  :</label>
                                <div class="clr"></div>
                                <?php if(count($campaign_product_data) > 0 && $campaign_product_data != "0"){?>
        <?php if (!empty($product_list) && count($product_list) > 0) {
            $tmp_pro = '';
            ?>
           <?php foreach ($product_list as $product) { ?>
                    <?php if (!empty($campaign_product_data) && in_array($product['product_id'], $campaign_product_data)) {
                    $tmp_pro .= $product['product_name'].',';
                } ?>
    <?php }
            echo rtrim($tmp_pro,',');
            ?>
<?php } ?>
                                <?php }else{
                                    echo "N/A";
                                    ?>

                                <?php }?>
                           </div>
                        </div>
                    </div>
                    <div class="clr"></div>

                    <div class="col-xs-12 col-md-6  no-left-pad">
                        <label><?= $this->lang->line('ADDITIONAL_NOTES') ?> :</label>
                        <div class="form-group">
                            <?php
                            if (isset($campaign_data[0]['aditional_notes']) && $campaign_data[0]['aditional_notes'] != '') {
                                $additional_notes = $campaign_data[0]['aditional_notes'];
                            } else {
                                $additional_notes = "N/A";
                            }
                            ?>
                            <?php echo $additional_notes; ?>
                        </div>
                    </div>

                <!-- new code-->
                <div class="col-xs-12 col-md-6 no-right-pad">
                    <div id="" class="">
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
  <a href='<?php echo base_url('RequestBudget/download/' . $image['file_id']); ?>' target="_blank">
               <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                   <img src="<?= base_url($path . '/' . $name); ?>"  width="75"/>
               <?php } else { ?>
                   <div><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/>
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

                <!-- end new code -->

                    <div class="clr"> </div><br/>

                    <div class="clr"> </div><br/>

                    <input type="hidden" name="hdn_budget_campaign_id" id="hdn_budget_campaign_id" value="<?php
                    if (isset($campaign_data[0]['budget_campaign_id']) && $campaign_data[0]['budget_campaign_id'] != '') {
                        echo $campaign_data[0]['budget_campaign_id'];
                    }
                    ?>"/>

                    <input type="hidden" name="hdn_campaign_id" id="hdn_campaign_id" value="<?php
                    if (isset($campaign_data[0]['campaign_id']) && $campaign_data[0]['campaign_id'] != '') {
                        echo $campaign_data[0]['campaign_id'];
                    }
                    ?>"/>

                    <input type="hidden" name="hdn_auto_gen_id" id="hdn_auto_gen_id" value="<?php
                    if (isset($campaign_data[0]['campaign_auto_id']) && $campaign_data[0]['campaign_auto_id'] != '') {
                        echo $campaign_data[0]['campaign_auto_id'];
                    }
                    ?>"/>

            </div>
        </div>
        <div class="modal-footer">

        </div>
    </div>
</div>



<script src="<?= base_url() ?>uploads/custom/js/projectmanagement/parsley.js"></script>
<script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    $('#budget_for_product').trigger('chosen:updated');

</script>


