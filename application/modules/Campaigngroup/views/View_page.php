<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if($view === true){
    $readonly = 'disabled';
}else{
    $readonly = '';
}
?>
<div class="modal-dialog modal-lg">

    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" title="<?=lang('close')?>" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="group_title"><?=$this->lang->line('VIEW_CAMPAIGN_GROUP')?></h4>
        </div>

        <div class="pad-10">
            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('GROUP_NAME')?>  :</label>
                        <?=!empty($editRecord[0]['group_name'])?$editRecord[0]['group_name']:''?>
                    </div>

                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('GROUP_OWNER')?>  :</label>
                        <?php
                        $group_owner_id = $editRecord[0]['group_owner_id'];
                        foreach($employee_owner as $row){
                            if($group_owner_id == $row['login_id']){?>
                                <?php echo $row['firstname'].' '.$row['lastname'];?>
                            <?php }}?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                    <div class = "col-sm-6">
                        <label>      <?=$this->lang->line('GROUP_DESCRIPTION')?>  :</label>
                        <?=!empty($editRecord[0]['group_description'])?$editRecord[0]['group_description']:''?>
                    </div>
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('BRANCHE')?>  :</label>
                        <?php
                        $branch_id = $editRecord[0]['branch_id'];
                        foreach($branch_info as $row){
                            if($branch_id == $row['branch_id']){ ?>
                                <?php echo $row['branch_name'];?>
                            <?php }} ?>
                    </div>

                </div>
            </div>


            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('products')?> :</label>
                        <?php
                        $product_id = $editRecord[0]['product_id'];
                        foreach($product_info as $row){
                            if($product_id == $row['product_id']){?>
                                <?php echo $row['product_name'];?>
                            <?php }} ?>
                    </div>
                    <div class = "col-sm-6">
                        <label>    <?=$this->lang->line('status')?>  :</label>
                        <?php if(!empty($editRecord[0]['status_id']) && $editRecord[0]['status_id'] == 1 ){?>Prospect<?php } ?>
                        <?php if(!empty($editRecord[0]['status_id']) && $editRecord[0]['status_id'] == 2 ){?> Lead <?php }?>
                        <?php if(!empty($editRecord[0]['status_id']) && $editRecord[0]['status_id'] == 3 ){?>Client <?php }?>
                    </div>


                </div>
            </div>

            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                <div class = "col-sm-6">
                    <label><?=$this->lang->line('VALUE_START')?> :</label>
                    <?=!empty($editRecord[0]['value_start'])?$editRecord[0]['value_start']:''?>
                    </div>
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('VALUE_END')?>  :</label>
                        <?=!empty($editRecord[0]['value_end'])?$editRecord[0]['value_end']:''?>
                    </div>

                </div>
            </div>

            <div class="col-xs-12 col-md-12 no-left-pad">
                <div class = "form-group row">
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('EMPLOYEE_OWNER')?> :</label>
                        <?php
                        $emp_owner_id = $editRecord[0]['emp_owner_id'];
                        foreach($employee_owner as $row){
                            if($emp_owner_id == $row['login_id']){?>
                                <?php echo $row['firstname'].' '.$row['lastname'];?>
                            <?php }} ?>
                    </div>
                    <div class = "col-sm-6">
                        <label><?=$this->lang->line('PREVIOUS_CAMPAIGN')?> :</label>
                        <?php
                        $previous_campaign_id = $editRecord[0]['previous_campaign_id'];
                        foreach($campaign_info as $row){
                            if($previous_campaign_id == $row['campaign_id']){?>
                                <?php echo $row['campaign_name'];?>
                            <?php }}?>
                    </div>
                </div>
            </div>
            <?php if(!empty($prospect_info) || !empty($lead_info)) { ?>
                <div class="col-xs-12 col-md-12">
                    <div class="whitebox" style="max-height: 300px; overflow-x: hidden;">
                        <div class="table table-responsive">
                            <table id="datatable1" class="table table-striped" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?= lang('company_name') ?></th>
                                    <th><?= lang('contacts') ?></th>
                                    <th><?= lang('branche') ?></th>
                                    <th><?= lang('products') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <th><?= lang('Add')?></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                //$finalMSGPrnt = "";
                                if(isset($prospect_info) && count($prospect_info) > 0 ){

                                    ?>

                                    <?php foreach($prospect_info as $data){
                                        if(!empty($group_sales_info)) {
                                            if (in_array($data['prospect_id'], $group_sales_info)) {
                                                $checked = 'checked';

                                        ?>

                                        <tr>
                                            <td><?php echo $data['company_name'];?></td>
                                            <td><?php
                                                $i=0;
                                                foreach($prospect_contact_info as $row) {
                                                    if($row['prospect_id']==$data['prospect_id'])
                                                    {
                                                        $i++;

                                                    }
                                                }
                                                echo $i;
                                                ?></td>
                                            <td>
                                                <?php foreach($branch_info as $branch) {
                                                    if($branch['branch_id']==$data['branch_id'])
                                                        echo $branch['branch_name'];
                                                }
                                                ?>
                                            </td>
                                            <td class="col-lg-6 col-xs-6 col-md-6">
                                                <?php
                                                foreach($prospect_product_info as $product) {
                                                    if($product['prospect_id']==$data['prospect_id'])
                                                    {
                                                        //foreach($product_info as $pro) {
                                                            echo $product['pros_products'];
                                                        //}
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php
                                                if($data['status_type']=='1')
                                                    echo lang('opportunity');
                                                else if($data['status_type']=='2')
                                                    echo lang('lead');
                                                else if($data['status_type']=='3')
                                                    echo lang('client');
                                                ?></td>
                                            <td>

                                                <input <?php echo $readonly;?> type="checkbox" name="add_to_group[]" <?php if(!empty($group_sales_info)) { echo $checked; }?> id="add_group_<?php echo $data['prospect_id'];?>" value='<?php echo $data['prospect_id'];?>'></td>
                                            <input type="hidden" name="prospect_id[]" value="<?php echo $data['prospect_id'];?>">
                                        </tr>
                                    <?php }} else {
                                            //echo $this->lang->line('common_no_record_found');
                                        }
                                    }?>
                                    <?php if(empty($group_sales_info)) {
                                        //$finalMSGPrnt = 'yes';
                                        ?>
                                    <?php }?>
                                <?php }?>

                                <?php
                                //$finalLeadMSDPrint = "";
                                if(isset($lead_info) && count($lead_info) > 0 ){
                                    ?>
                                    <?php foreach($lead_info as $lead_data){
                                        if(!empty($lead_sales_info)) {
                                            if (in_array($lead_data['lead_id'], $lead_sales_info)) {
                                                $checked = 'checked';

                                        ?>
                                        <tr>
                                            <td><?php echo $lead_data['company_name'];?></td>
                                            <td><?php
                                                $i=0;
                                                foreach($lead_contact_info as $row) {
                                                    if($row['lead_id']==$lead_data['lead_id'])
                                                    {
                                                        $i++;

                                                    }
                                                }
                                                echo $i;
                                                ?></td>
                                            <td><?php foreach($branch_info as $branch) {
                                                    if($branch['branch_id']==$lead_data['branch_id'])
                                                        echo $branch['branch_name'];
                                                }
                                                ?></td>
                                            <td class="col-lg-6 col-xs-6 col-md-6"><?php
                                                foreach($lead_product_info as $product) {
                                                    if($product['lead_id']==$lead_data['lead_id'])
                                                    {
                                                        //foreach($product_info as $pro) {
                                                            echo $product['lead_products'];
                                                        //}
                                                    }
                                                }
                                                ?></td>
                                            <td><?php
                                                if($lead_data['status_type']=='1')
                                                    echo lang('opportunity');
                                                else if($lead_data['status_type']=='2')
                                                    echo lang('lead');
                                                else if($lead_data['status_type']=='3')
                                                    echo lang('client');
                                                ?></td>
                                            <?php
                                            if($lead_data['status_type']=='1')
                                                $status_type= '1';
                                            else if($lead_data['status_type']=='2')
                                                $status_type = '2';
                                            else if($lead_data['status_type']=='3')
                                                $status_type = '3';
                                            ?>
                                            <td><input <?php echo $readonly;?> type="checkbox" <?php if(!empty($lead_sales_info)) { echo $checked; }?> class="checkbox1" name="add_to_group[]" value="<?php echo $status_type.'-'.$lead_data['lead_id']; ?>" id="add_group_<?php echo $lead_data['lead_id'];?>" /></td>
                                            <input type="hidden" name="prospect_id[]" value="<?php echo $lead_data['lead_id'];?>">

                                            <input type="hidden" name="status_type[]" value="<?php echo $status_type;?>">
                                        </tr>
                                            <?php }} else {
                                            //$finalLeadMSDPrint = "yes";
                                            //echo $this->lang->line('common_no_record_found');
                                    }?>
                                    <?php }?>
                                <?php }?>
                                <?php //if($finalLeadMSDPrint == 'yes' && $finalMSGPrnt == 'yes')
                                if(count($group_sales_info) == 0 && count($lead_sales_info) == 0)
                                { ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <?php echo $this->lang->line('common_no_record_found');?>
                                        </td>
                                    </tr>

                                <?php }
                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>
                </div>
            <?php } ?>
            <?php if(!empty($editRecord[0]['related_campaign']))
            { $edit_time_id = "related_campaign_show";
            }else{
                $edit_time_id = "related_campaign_hide";
            }?>
            <div class="form-group">
                <div class="col-sm-5">
                    <!-- <input data-toggle="toggle" data-onstyle="success" type="checkbox" id="related_campaign" value="1" name="related_campaign">-->

                    <label>
                        <?=$this->lang->line('RELATED_TO_CAMPAIGN')?>
                    </label>
                </div>
                <div class="col-sm-4">
                        <?php
                        if(!empty($editRecord[0]['related_campaign'])){
                        $campaign_id = $editRecord[0]['campaign_id'];
                        if(count($campaign_id) > 0 && $campaign_id != "0"){
                        foreach($campaign_info as $row){
                            if($campaign_id == $row['campaign_id']){?>
                                <?php echo $row['campaign_name'];?>
                            <?php }} ?>
                        <?php } else { echo $this->lang->line('no_select_supplier'); }?>

                        <?php } else { echo $this->lang->line('no_select_supplier'); }?>
                </div>
            </div>
        </div>

        <div class="clr"> </div><br/>
    </div>

</div>
<script>
    $(document).ready(function () {
        $('#related_campaign').bootstrapToggle();
    });

</script>
<?php $this->load->view($js_content);?>
<!--</div>-->