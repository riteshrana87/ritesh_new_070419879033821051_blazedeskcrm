<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(!empty($editRecord)){
    $formAction = 'updatedata';
}else{
    $formAction = 'insertdata';
}
$path = $sales_view.'/'.$formAction;


?>

<div class="modal-dialog modal-lg"> 
  
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" title="<?=lang('close')?>" data-dismiss="modal">&times;</button>
      <h4 class="modal-title" id="group_title"><?php echo $modal_title;?></h4>
    </div>
    <div class="modal-body">
      <form class="row" role="form" name="frm_createcampaigngroup" id="frm_createcampaigngroup" enctype="multipart/form-data" action="<?php echo base_url($path);?>" method="post">
        <div class="col-sm-8 col-md-8">
          <div class="form-group">
              <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
            <input type="text" name="group_name" onchange="chick_group_name();" class="form-control" id="group_name" placeholder="<?=$this->lang->line('GROUP_NAME')?> *" value="<?=!empty($editRecord[0]['group_name'])?htmlentities(stripslashes($editRecord[0]['group_name'])):''?>" required />
            <span class="text-danger" id="group_name_error"></span>
            <ul class='parsley-errors-list filled hidden' id='email_err'><li class='parsley-remote'><?php echo lang('Campign_group_is_already_exists'); ?></li></ul>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <div class="form-group">
            <select class="selectpicker form-control chosen-select" data-parsley-errors-container="#group-errors" name="group_owner_id" id="group_owner_id" required>
              <option value="" selected="">
              <?=$this->lang->line('GROUP_OWNER')?> *
              </option>
              <?php
                                $group_owner_id = $editRecord[0]['group_owner_id'];
                                foreach($employee_owner as $row){
                                    if($group_owner_id == $row['login_id']){?>
              <option selected value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>
              <?php }else{?>
              <option value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>
              <?php }}?>
            </select>
              <div id="group-errors"></div>
          </div>
        </div>
        <div class="clr"></div>
        <div class="col-sm-12 col-md-12">
          <div class="form-group">
            <textarea class="form-control" name="group_description" rows="5" id="group_description" placeholder="<?=$this->lang->line('GROUP_DESCRIPTION')?> *" required><?=!empty($editRecord[0]['group_description'])?$editRecord[0]['group_description']:''?>
</textarea>
              <ul class="parsley-errors-list filled hidden" id="descriptionError" ><li class="parsley-required"><?= $this->lang->line('EST_ADD_LABEL_REQUIRED_FIELD') ?></li></ul>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <div class="form-group">
            <!--<select data-parsley-errors-container="#branch-errors" class="selectpicker form-control chosen-select" name="branch_id" id="branch_id" required >
              <option value="" selected="">
              <?=$this->lang->line('BRANCHE')?>
              </option>
              <?php
                                $branch_id = $editRecord[0]['branch_id'];
                                foreach($branch_info as $row){
                                    if($branch_id == $row['branch_id']){
                                        ?>
              <option selected value="<?php echo $row['branch_id'];?>"><?php echo $row['branch_name'];?></option>
              <?php }else{?>
              <option value="<?php echo $row['branch_id'];?>"><?php echo $row['branch_name'];?></option>
              <?php }} ?>
            </select>-->
            <input type="text" id="branch_id" name="branch_id" class="form-control" tabindex="11" placeholder="<?= $this->lang->line('branche') ?>" value="<?php if(!empty($branch_info1[0]['branch_name'])){ echo htmlentities(stripslashes($branch_info1[0]['branch_name'])); }else{ echo ''; } ?>">
              <div id="branch-errors"></div>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <div class="form-group">
            <select data-parsley-errors-container="#product-errors" class="selectpicker form-control chosen-select" name="product_id" id="product_id" required >
              <option value="" selected="">
              <?=$this->lang->line('products')?> *
              </option>
              <?php
                                $product_id = $editRecord[0]['product_id'];
                                foreach($product_info as $row){
                                    if($product_id == $row['product_id']){?>
              <option selected value="<?php echo $row['product_id'];?>"><?php echo $row['product_name'];?></option>
              <?php }else{?>
              <option value="<?php echo $row['product_id'];?>"><?php echo $row['product_name'];?></option>
              <?php }} ?>
            </select>
              <div id="product-errors"></div>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <div class="form-group">
            <select data-parsley-errors-container="#status-errors" class="selectpicker form-control chosen-select" name="status_id" id="status_id" required>
              <option value="" selected="">
              <?=$this->lang->line('status')?> *
              </option>
              <option <?php if(!empty($editRecord[0]['status_id']) && $editRecord[0]['status_id'] == 1 ){?>selected<?php }?> value="1"><?php echo lang('opportunity');?></option>
              <option <?php if(!empty($editRecord[0]['status_id']) && $editRecord[0]['status_id'] == 2 ){?>selected<?php }?> value="2"><?php echo lang('lead');?></option>
              <option <?php if(!empty($editRecord[0]['status_id']) && $editRecord[0]['status_id'] == 3 ){?>selected<?php }?> value="3"><?php echo lang('client');?></option>
            </select>
              <div id="status-errors"></div>
          </div>
        </div>
        <div>
        <div class="clr"></div>
        <div class="col-xs-12"><label>
          <?=$this->lang->line('VALUE_BETWEEN')?>
        </label></div>
        <div class="clr"></div>
        <div class="col-sm-2 col-md-2">
          <div class="form-group">
            <input type="text" pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-lt="#value_end" name="value_start"  class="form-control" id="value_start" placeholder="<?=$this->lang->line('START')?> *" value="<?=!empty($editRecord[0]['value_start'])?$editRecord[0]['value_start']:''?>" required />
          </div>
        </div>
        <div class="col-sm-2 col-md-2">
          <div class="form-group">
            <input type="text" pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-gt="#value_start" name="value_end" class="form-control" id="value_end" placeholder="<?=$this->lang->line('END')?> *" value="<?=!empty($editRecord[0]['value_end'])?$editRecord[0]['value_end']:''?>" required/>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <div class="form-group">
            <select data-parsley-errors-container="#emp-owner-errors" class="selectpicker form-control chosen-select" name="emp_owner_id" id="emp_owner_id" required>
              <option value="" selected="">
              <?=$this->lang->line('EMPLOYEE_OWNER')?> *
              </option>
              <?php
                                if(!empty($editRecord)){
                                    $emp_owner_id = $editRecord[0]['emp_owner_id'];
                                }else{
//                                    $emp_owner_id = $this->session->userdata('LOGGED_IN')['ID'];
                                    $emp_owner_id = "";
                                }
                                foreach($employee_owner as $row){
                                    if($emp_owner_id == $row['login_id']){?>
              <option selected value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>
              <?php }else{?>
              <option value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>
              <?php }} ?>
            </select>
              <div id="emp-owner-errors"></div>
          </div>
        </div>
        <div class="col-sm-4 col-md-4">
          <div class="form-group">
            <select data-parsley-errors-container="#previous-errors" class="selectpicker form-control chosen-select" name="previous_campaign_id" id="previous_campaign_id">
              <option value="" selected="">
              <?=$this->lang->line('PREVIOUS_CAMPAIGN')?>
              </option>
              <?php

                                $previous_campaign_id = $editRecord[0]['previous_campaign_id'];
                                foreach($campaign_info as $row){
                                    if($previous_campaign_id == $row['campaign_id']){?>
              <option selected value="<?php echo $row['campaign_id'];?>"><?php echo $row['campaign_name'];?></option>
              <?php }else{?>
              <option value="<?php echo $row['campaign_id'];?>"><?php echo $row['campaign_name'];?></option>
              <?php }}?>
            </select>
              <div id="previous-errors"></div>
          </div>
        </div>
        <div> <span class="text-danger" id="value_start_end_error"></span></div>
        <?php //  if(!empty($prospect_info) || !empty($lead_info)) { ?>
        <div class="row" style="margin-left:-12px;margin-right:-12px">
          <div class="col-sm-12 col-md-12">
            <div style="max-height: 300px; overflow-x: hidden;" class="mb15">
              <div class="table table-responsive">
                <table id="datatable1" class="table table-striped" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th><?= lang('company_name') ?></th>
                      <th><?= lang('contacts') ?></th>
                      <th><?= lang('branche') ?></th>
                      <th><?= lang('products') ?></th>
                      <th><?= lang('status') ?></th>
                      <th><?= lang('Add')?>
                        
                        <!--<input type="checkbox" class="selecctall" id="selecctall"> --></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if(!empty($prospect_info) || !empty($lead_info)) { ?>
                    <?php if(isset($prospect_info) && count($prospect_info) > 0 ){ ?>
                    <?php foreach($prospect_info as $data){
                                                if(!empty($group_sales_info)) {
                                                    if (in_array($data['prospect_id'], $group_sales_info)) {
                                                        $checked = 'checked';
                                                    } else {
                                                        $checked = '';
                                                    }
                                                }
                                                ?>
                    <tr>
                      <td><?php echo $data['company_name'];?></td>
                      <td><?php
//                                                        $i=0;
                                                        $tmp_var = 0;
                                                        foreach($prospect_contact_info as $row) {
                                                            if($row['prospect_id']==$data['prospect_id'])
                                                            {
//                                                                $i++;
                                                               $tmp_var =  $row['pros_contacts'];
                                                            }
                                                            
                                                        }
                                                        echo $tmp_var;
//                                                        echo $i;
                                                        ?></td>
                      <td><?php foreach($branch_info as $branch) {
                                                            if($branch['branch_id']==$data['branch_id'])
                                                                echo $branch['branch_name'];
                                                        }
                                                        ?></td>
                      <td class="col-lg-6 col-xs-6 col-md-6"><?php 
                                                        foreach($prospect_product_info as $product) {
                                                            if($product['prospect_id']==$data['prospect_id'])
                                                            {
//                                                                foreach($product_info as $pro) {
                                                                    echo $product['pros_products'];
//                                                                }
                                                            }
                                                        }
                                                        ?></td>
                      <td><?php
                                                        if($data['status_type']=='1')
                                                            echo lang('opportunity');
//                                                        else if($data['status_type']=='2')
//                                                            echo 'Lead';
                                                        else if($data['status_type']=='3')
                                                            echo lang('client');
                                                        ?></td>

                        <?php
                        if($data['status_type']=='1')
                            $status_type= '1';
                        else if($data['status_type']=='2')
                            $status_type = '2';
                        else if($data['status_type']=='3')
                            $status_type = '3';
                        ?>
                      <td><input type="checkbox" <?php if(!empty($group_sales_info)) { echo $checked; }?> class="checkbox1" name="add_to_group[]" value="<?php echo $status_type.'-'.$data['prospect_id']; ?>" id="add_group_<?php echo $data['prospect_id'];?>" /></td>

                       <input type="hidden" name="status_type[]" value="<?php echo $status_type;?>">
                       <input type="hidden" name="prospect_id[]" value="<?php echo $data['prospect_id'];?>">
                    </tr>
                    <?php }?>
                    <?php }?>

                    <?php if(isset($lead_info) && count($lead_info) > 0 ){ ?>
                        <?php foreach($lead_info as $lead_data){
                            if(!empty($lead_sales_info)) {
                                if (in_array($lead_data['lead_id'], $lead_sales_info)) {
                                    $checked = 'checked';
                                } else {
                                    $checked = '';
                                }
                            }
                            ?>
                            <tr>
                                <td><?php echo $lead_data['company_name'];?></td>
                                <td><?php 
//                                    $i=0;
                                $tmp_var = 0;
                                    foreach($lead_contact_info as $row) {
                                        if($row['lead_id']==$lead_data['lead_id'])
                                        {
//                                            $i++;
                                            $tmp_var =  $row['lead_contacts'];
                                        }

//                                        }
                                        
                                    }
                                    echo $tmp_var;
//                                    echo $i;
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
                                <td><input type="checkbox" <?php if(!empty($lead_sales_info)) { echo $checked; }?> class="checkbox1" name="add_to_group[]" value="<?php echo $status_type.'-'.$lead_data['lead_id']; ?>" id="add_group_<?php echo $lead_data['lead_id'];?>" /></td>
                                <input type="hidden" name="prospect_id[]" value="<?php echo $lead_data['lead_id'];?>">

                <input type="hidden" name="status_type[]" value="<?php echo $status_type;?>">
                            </tr>
                        <?php }?>
                    <?php }?>
                    
                    <?php } else { ?>
                            <tr>
                                <td colspan="6" style="text-align:center;"><?php echo lang("common_no_record_found") ?></td>
                            </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>

              <div class="clr"></div>
            </div>
            <div class="clr"></div>
          </div>
        </div>
        <?php // } ?>
        <?php if(!empty($editRecord[0]['related_campaign'])){
                        $edit_time_id = "related_campaign_show";
                    }else{
                        $edit_time_id = "related_campaign_hide";
                    }?>
        <div class="">
        <div class="col-sm-6 col-md-5 col-xs-12 form-group">
            <input data-toggle="toggle" data-onstyle="success" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" type="checkbox"  id="related_campaign" name="related_campaign" onChange="toggle_show(<?php echo "'#".$edit_time_id."'";?>, this)" <?=!empty($editRecord[0]['related_campaign'])?'checked="checked"':''?>/>
            <label class="checkbox-inline">
              <?=$this->lang->line('RELATED_TO_CAMPAIGN')?>
            </label>
          </div>
          
            <div id="<?php echo $edit_time_id;?>" class="col-sm-3  col-md-4">
          <div class=" form-group">
            <select data-parsley-errors-container="#campaign-data-errors" class="form-control selectpicker chosen-select" name="campaign_id" id="">
              <option value="" selected="">
              <?=$this->lang->line('SELECT_CAMPAIGN')?>
              </option>
              <?php
                                $campaign_id = $editRecord[0]['campaign_id'];
                                foreach($campaign_info as $row){
                                    if($campaign_id == $row['campaign_id']){?>
              <option selected value="<?php echo $row['campaign_id'];?>"><?php echo $row['campaign_name'];?></option>
              <?php }else{?>
              <option value="<?php echo $row['campaign_id'];?>"><?php echo $row['campaign_name'];?></option>
              <?php }} ?>
            </select>
            <div id="campaign-data-errors"></div>
          </div>
            </div>
            <?php if(isset($prospect_info) && count($prospect_info) > 0 ){ ?>
  <div class="form-group pull-right text-right col-sm-3 col-md-3">
  <label class="btn btn-primary" for="selecctall"><?php echo lang('select_all');?></label>
                <input type="checkbox" class="selecctall hidden" id="selecctall" >
            </div>
            <?php }?>
          <?php if(!empty($prospect_info)) { ?>
          <div class="col-sm-2"> </div>
          <?php } ?>
          <div class="clr"></div>
        </div>
            <div class="clr"></div>
        <input type="hidden" name="id" id="id" value="<?=!empty($editRecord[0]['campaign_group_id'])?$editRecord[0]['campaign_group_id']:''?>" />
        <div class="form-group">
          <div class="modal-footer">
            <div class="text-center">
              <?php if(!empty($editRecord)){?>
              <input type="submit" class="btn btn-primary" id="group_submit_btn" value="<?=$this->lang->line('UPDATE_CAMPAIGN_GROUP')?>">
              <?php }else{?>
              <input type="submit" class="btn btn-primary" id="group_submit_btn" value="<?=$this->lang->line('CREATE_CAMPAIGN_GROUP')?>">
              <?php }?>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
    function chick_group_name()
    {
        var group_name = $("input#group_name").val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>" + "Campaigngroup/CheckCampaignGroup",
            dataType: 'json',
            data: {group_name: group_name},
            success: function(d) {
                
                if (d.status == 1)
                    {
                        $('#group_name').val('');
                        $('#group_name').addClass('parsley-error');
                        $('#email_err').removeClass('hidden');
                    }
                    else
                    {
                        $('#email_id').removeClass('parsley-error');
                        $('#email_err').addClass('hidden');
                    }
            }
        });
    }
    <?php if (!empty($editRecord[0]['country_id'])) { ?>
    //chick_group_name();
    <?php } ?>


    $('#group_description').summernote({
        disableDragAndDrop : true,
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        },
        focus: true
    });
    //open modal after image upload etc in description
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });

    $('body').delegate('#group_submit_btn', 'click', function (e) {
        //Validation for Description Wysiwyc editor not allow blank space in description
        var wys = $('.note-editable').html();
        //var value = wys.replace(/(<([^>]+)>)/ig, "");
        var value = wys.replace(/(?:&nbsp;|<br>|<p>|<\/p>)/ig, "");
        var final_value = value.replace(/&nbsp;/g, '');

        final_value = final_value.replace(/^\s+/g, '');
        if (final_value != '') {
            var description = $('#group_description').code();
            if (description !== '' && description !== '<p><br></p>' && description !== '<br>') {
                $("#descriptionError").addClass("hidden");
            }
        } else {
            $("#descriptionError").removeClass("hidden");
            return false;
        }
        if ($('#frm_createcampaigngroup').parsley().isValid()) {
            //disabled submit button after submit
            $('input[type="submit"]').prop('disabled', true);
            $('#frm_createcampaigngroup').submit();
        }
    });

    $(document).ready(function () {
        $("#desciptionError").css("display", "none");
        $('#frm_createcampaigngroup').parsley();

        $('#related_campaign').bootstrapToggle();
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });

    $(function() {
        var availableBranch = [
            <?php if (isset($branch_info) && count($branch_info) > 0) {
                    $count = 0;
                    foreach ($branch_info as $branch) {
                        $count++;
                        echo '"'.addslashes($branch['branch_name']).'"';
                        if($count != count($branch_info)){ echo ", "; }
                    }
               } ?>
        ];
        $( "#branch_id" ).autocomplete({
            source: availableBranch
        });
    });



</script> 
<script>
    $(document).ready(function () {
                
        //On change Campaigns-> Show Company, contacts, products, Status, checkbox
        $("#previous_campaign_id").on("change", function(){
          var campaignId = $(this).val(); 
          var ownerId = $("#emp_owner_id").val();
          var campaignGroupId = $("#id").val();
          
          //Ajax Call: Pass CampaignId and get Company, contacts, products, Status
          //if(campaignId != ""){
          $("#datatable1 tbody").empty();
          $.ajax({
              url: "<?php echo base_url('Campaigngroup/getCompanyByCampaign'); ?>",
                type: "POST",
                dataType: "json",
                data: {'campaignId':campaignId, 'campaignGroupId':campaignGroupId, "ownerId": ownerId},
                success: function (data)
                {
                    /*
                    * Show Prospect Data
                    */
                //if(data.length > 0){
                    if(data.prospect_data.length>0)
                    {
                        $.each(data.prospect_data, function(prosKey, prosVal){
                            var prosStatusType      = "";
                            var prosContactCount    = 0;
                            var html                = "";
                            var checked             = "";
                            var prosProducts = prosVal.pros_products;
                            
                            if(prosProducts == null){
                                prosProducts = "";
                            }
                                                          
                            // Status Names: Prospect, Client from status_type_ids
                            if(prosVal.pros_status_type == 1){
                                prosStatusType = "<?php echo lang('opportunity'); ?>";
                            }
                            else if(prosVal.pros_status_type == 3){
                                prosStatusType = "<?php echo lang('client'); ?>";
                            }
                            
                            $.each(data.prospect_contact_info, function(contactKey, contactVal){
                                
                                if(contactVal.prospect_id == prosVal.prospect_id){
                                    prosContactCount = contactVal.pros_contacts;
                                }
                            });
                             
                            if(prosVal.pros_company_name == "" && prosVal.pros_branch_name == ""  && prosVal.pros_products == "" && prosStatusType == ""){
                            }
                            else{
                                
                                html = "<tr>"+
                                         "<td>"+prosVal.pros_company_name+"</td>"+
                                         "<td>"+prosContactCount+"</td>"+
                                         "<td>"+prosVal.pros_branch_name+"</td>"+
                                         "<td class='col-lg-6 col-xs-6 col-md-6'>"+prosProducts+"</td>"+
                                         "<td>"+prosStatusType+"</td>";
                                <?php if(isset($editRecord)) { ?>
                                if(data.group_sales_info == "undefined" || data.group_sales_info == null)
                                {
                                    
                                }
                                else{
                                    
                                    if($.inArray(prosVal.prospect_id, data.group_sales_info)  != -1){
                                        checked = "checked";
                                    }
                                    html += "<td><input type='checkbox' "+checked+" class='checkbox1' name='add_to_group[]' id='add_group_"+prosVal.prospect_id+"' value="+prosVal.pros_status_type+"-"+prosVal.prospect_id+" /></td>";
                                }
                                <?php }else { ?>
                                    html += "<td><input type='checkbox' class='checkbox1' name='add_to_group[]' id='add_group_"+prosVal.prospect_id+"' value="+prosVal.pros_status_type+"-"+prosVal.prospect_id+" /></td>";
                                <?php } ?>
                                html += "<input type='hidden' name='status_type[]' value="+prosVal.pros_status_type+">"+
                                        "<input type='hidden' name='prospect_id[]' value="+prosVal.prospect_id+">";
                                html += "</tr>";
                                $("#datatable1 tbody").append(html);
                            }
                        });
                    }
                    
                    /*
                    * Show Lead Data
                    */
                    if(data.lead_data.length>0)
                    {
                         $.each(data.lead_data, function(leadKey, leadVal){
                            var leadStatusType = "";
                            var html = "";
                            var leadContactCount = 0;
                            var checked             = "";
                            var leadProducts = leadVal.lead_products;
                            
                            if(leadProducts == null){
                                leadProducts = "";
                            }
                            
                            //Status
                             if(leadVal.lead_status_type == 2){
                                leadStatusType = "<?php echo lang('lead'); ?>";
                             }
                             
                            // Total Contacts count
                            $.each(data.lead_contact_info, function(contactKey, contactVal){
                                
                                if(contactVal.lead_id == leadVal.lead_id){
                                    leadContactCount = contactVal.lead_contacts;
                                }
                            });
                            
                            if(leadVal.lead_company_name == '' && leadVal.lead_branch_name == ''  && leadVal.lead_products == '' && leadStatusType == ''){
                            }
                            else{
                                html = "<tr>"+
                                         "<td>"+leadVal.lead_company_name+"</td>"+
                                         "<td>"+leadContactCount+"</td>"+
                                         "<td>"+leadVal.lead_branch_name+"</td>"+
                                         "<td class='col-lg-6 col-xs-6 col-md-6'>"+leadProducts+"</td>"+
                                         "<td>"+leadStatusType+"</td>";
                                <?php if(isset($editRecord)) { ?>
                                if(data.lead_sales_info == "undefined" || data.lead_sales_info == null)
                                {
                                }
                                else{
                                    if($.inArray(leadVal.lead_id, data.lead_sales_info)  != -1){
                                        checked = "checked";
                                        
                                    }
                                    html += "<td><input type='checkbox' "+checked+" class='checkbox1' name='add_to_group[]' id='add_group_"+leadVal.lead_id+"' value="+leadVal.lead_status_type+"-"+leadVal.lead_id+" /></td>";
                                }
                                 <?php }else{ ?>
                                    html += "<td><input type='checkbox' class='checkbox1' name='add_to_group[]' id='add_group_"+leadVal.lead_id+"' value="+leadVal.lead_status_type+"-"+leadVal.lead_id+" /></td>";
                                 <?php } ?>
                                html += "<input type='hidden' name='status_type[]' value="+leadVal.lead_status_type+">"+
                                        "<input type='hidden' name='prospect_id[]' value="+leadVal.lead_id+">";
                                html += "</tr>";
                                $("#datatable1 tbody").append(html);
                            }
                        });
                    }
                    var html_empty = "";
                    if(data.prospect_data.length<=0 && data.lead_data.length<=0)
                    {
                        html_empty = "<tr><td colspan='6' style='text-align:center;'><?php echo lang("common_no_record_found") ?></td></tr>";
                        $("#datatable1 tbody").append(html_empty);
                    }
                }
            });
        });
       
        //On change Campaigns-> Show Company, contacts, products, Status
        $("#emp_owner_id").on("change", function(){
          var ownerId = $(this).val(); 
          var campaignId = $("#previous_campaign_id").val();
          var campaignGroupId = $("#id").val();
          
          //Ajax Call: Pass CampaignId and get Company, contacts, products, Status
          //if(ownerId != ""){
            $("#datatable1 tbody").empty();
          $.ajax({
              url: "<?php echo base_url('Campaigngroup/getCompanyByOwner'); ?>",
                type: "POST",
                dataType: "json",
                data: {'ownerId': ownerId, 'campaignGroupId': campaignGroupId, 'campaignId': campaignId},
                success: function (data)
                {
                    /*
                    * Show Prospect Data
                    */
                   //if(data.length > 0){
                    if(data.prospect_data.length>0)
                    {
                        $.each(data.prospect_data, function(prosKey, prosVal){
                            var statusType = "";
                            var html = "";
                            var checked = "";
                            var prosContactCount = 0;
                            var prosProducts = prosVal.pros_products;
                            
                            if(prosProducts == null){
                                prosProducts = "";
                            }
                            
                             if(prosVal.pros_status_type == 1){
                                statusType = "<?php echo lang('opportunity'); ?>";
                             }
                             else if(prosVal.pros_status_type == 3){
                                statusType = "<?php echo lang('client'); ?>";
                             }
                             
                             $.each(data.prospect_contact_info, function(contactKey, contactVal){
                                
                                if(contactVal.prospect_id == prosVal.prospect_id){
                                    prosContactCount = contactVal.pros_contacts;
                                }
                            });
                            
                            if(prosVal.pros_company_name == "" && prosVal.pros_branch_name == ""  && prosVal.pros_products == "" && statusType == ""){
                            }
                            else{
                                html = "<tr>"+
                                         "<td>"+prosVal.pros_company_name+"</td>"+
                                         "<td>"+prosContactCount+"</td>"+
                                         "<td>"+prosVal.pros_branch_name+"</td>"+
                                         "<td class='col-lg-6 col-xs-6 col-md-6'>"+prosProducts+"</td>"+
                                         "<td>"+statusType+"</td>";
                                        <?php if(isset($editRecord)) { ?>
                                       if(data.group_sales_info == "undefined" || data.group_sales_info == null)
                                       {
                                       }
                                       else{
                                            if($.inArray(prosVal.prospect_id, data.group_sales_info) !== -1){
                                                checked = "checked";
                                           }
                                          html += "<td><input type='checkbox' "+checked+" class='checkbox1' name='add_to_group[]' id='add_group_"+prosVal.prospect_id+"' value="+prosVal.pros_status_type+"-"+prosVal.prospect_id+" /></td>";
                                       }
                                       <?php }else { ?>
                                           html += "<td><input type='checkbox' class='checkbox1' name='add_to_group[]' id='add_group_"+prosVal.prospect_id+"' value="+prosVal.pros_status_type+"-"+prosVal.prospect_id+" /></td>";
                                       <?php } ?>
                                html += "<input type='hidden' name='status_type[]' value="+prosVal.pros_status_type+">"+
                                        "<input type='hidden' name='prospect_id[]' value="+prosVal.prospect_id+">";
                                html += "</tr>";
                                $("#datatable1 tbody").append(html);
                            }
                         });
                    }
                    
                    /*
                    * Show Lead Data
                    */
                    if(data.lead_data.length>0)
                    {
                         $.each(data.lead_data, function(leadKey, leadVal){
                            var leadStatusType      = "";
                            var html                = "";
                            var leadContactCount    = 0;
                            var checked             = "";
                            var leadProducts = leadVal.lead_products;
                            
                            if(leadProducts == null){
                                leadProducts = "";
                            }
                            
                            //Status
                            if(leadVal.lead_status_type == 2){
                                leadStatusType = "<?php echo lang('lead'); ?>";
                            }
                            
                            // Total Contacts count
                            $.each(data.lead_contact_info, function(contactKey, contactVal){
                                
                                if(contactVal.lead_id == leadVal.lead_id){
                                    leadContactCount = contactVal.lead_contacts;
                                }
                            });
                            
                            if(leadVal.lead_company_name == '' && leadVal.lead_branch_name == ''  && leadVal.lead_products == '' && leadStatusType == ''){
                            }
                            else{
                                html = "<tr>"+
                                         "<td>"+leadVal.lead_company_name+"</td>"+
                                         "<td>"+leadContactCount+"</td>"+
                                         "<td>"+leadVal.lead_branch_name+"</td>"+
                                         "<td class='col-lg-6 col-xs-6 col-md-6'>"+leadProducts+"</td>"+
                                         "<td>"+leadStatusType+"</td>";
                                        <?php if(isset($editRecord)) { ?>
                                        if(data.lead_sales_info == "undefined" || data.lead_sales_info == null)
                                        {
                                        }
                                        else{
                                            if($.inArray(leadVal.lead_id, data.lead_sales_info) !== -1){                                                        checked = "checked";
                                           }
                                          html += "<td><input type='checkbox' "+checked+" class='checkbox1' name='add_to_group[]' id='add_group_"+leadVal.lead_id+"' value="+leadVal.lead_status_type+"-"+leadVal.lead_id+" /></td>";
                                        }
                                        <?php }else{ ?>
                                           html += "<td><input type='checkbox' class='checkbox1' name='add_to_group[]' id='add_group_"+leadVal.lead_id+"' value="+leadVal.lead_status_type+"-"+leadVal.lead_id+" /></td>";
                                        <?php } ?>

                                       html += "<input type='hidden' name='status_type[]' value="+leadVal.lead_status_type+">"+
                                        "<input type='hidden' name='prospect_id[]' value="+leadVal.lead_id+">";
                                       html += "</tr>";
                                $("#datatable1 tbody").append(html);
                            }
                         });
                    }  
                    var html_empty = "";
                    if(data.prospect_data.length<=0 && data.lead_data.length<=0)
                    {
                        html_empty = "<tr><td colspan='6' style='text-align:center;'><?php echo lang("common_no_record_found") ?></td></tr>";
                        $("#datatable1 tbody").append(html_empty);
                    }
                }
          });
          //}
       });
    });
</script>