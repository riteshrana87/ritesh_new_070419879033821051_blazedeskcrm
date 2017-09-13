<?php
//pr($editRecord[0]['file']);exit;
defined('BASEPATH') OR exit('No direct script access allowed');
if(!empty($editRecord) && count($editRecord[0]) > 1){
    $formAction = 'updatedata';
}else{
    $formAction = 'insertdata';
}
$path = $sales_view.'/'.$formAction;
?>

<div class="modal-dialog modal-lg">
  <form role="form" name="frm_createcampaign" id="frm_createcampaign" enctype="multipart/form-data" action="<?php echo base_url($path);?>" method="post" data-parsley-validate>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" title="<?=lang('close')?>" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <div class="modelTitle"><?php echo $modal_title;?></div>
        </h4>
      </div>
      <div class="pad-10">
        <div class="col-xs-12 col-md-4 col-lg-4 col-sm-4 no-left-pad">
          <div class="form-group">
            <input type="text" name="campaign_name" data-parsley-maxlength="200" class="form-control" id="campaign_name" placeholder="<?=$this->lang->line('CAMPAIGN_NAME')?> *" value="<?=!empty($editRecord[0]['campaign_name'])?htmlentities(stripslashes($editRecord[0]['campaign_name'])):''?>" required=""/>
            <input type="hidden" name="redirect" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
            <!-- <input type="hidden" id="hdn_contact_id" name="hdn_contact_id" value="<?php // if(isset($contact_id) && $contact_id !=''){ echo $contact_id;}else{ echo "";}?>"/>--> 
            <span class="text-danger" id="campaign_name_error"></span> </div>
        </div>
        <div class="col-xs-12 col-md-4 col-lg-4 col-sm-4 no-right-pad">
          <div class="form-group">
            <?php

                           /* $date_array = str_replace(" ","",microtime(FALSE));
                            $micro_number=substr(str_replace(".","",$date_array),1,15);
                           */
                            ?>
            <input type="text" name="campaign_auto_id" class="form-control" id="campaign_auto_id" placeholder="<?php echo $camping_auto_id;?> *" value="<?=!empty($editRecord[0]['campaign_auto_id'])?$editRecord[0]['campaign_auto_id']:$camping_auto_id?>" readonly />
          </div>
        </div>
          <div class="col-xs-12 col-md-4 col-lg-4 col-sm-4 no-left-pad">
          <div class="form-group bd-form-group">
            <div class="col-md-4 col-sm-4"><label>
              <?= lang('START_DATE') ?>
            </label></div>
            <div class="input-group date col-md-8 col-sm-8" id='start_date'>
              <input type='text' data-parsley-errors-container="#start-date-errors" class="form-control" name="start_date"  placeholder="<?=$this->lang->line('START_DATE')?> *" value="<?=!empty($editRecord[0]['start_date'])?date("m/d/Y", strtotime($editRecord[0]['start_date'])):''?>" required />
              <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
            <!-- <span class="text-danger" id="start_date_error"></span>--> 
          </div>
          <div id="start-date-errors"></div>
        </div>
        <div class="clr"></div>
        <div class="col-xs-12 col-md-4 col-lg-4 col-sm-4 no-left-pad">
          <div class="form-group">
            <input type="text" name="campaign_type_id" data-parsley-maxlength="200" class="form-control" id="campaign_type_id" placeholder="<?=$this->lang->line('TYPE_OF_CAMPAIGN')?> *" value="<?=!empty($editRecord[0]['camp_type_name'])?htmlentities(stripslashes($editRecord[0]['camp_type_name'])):''?>" required=""/>
            <div id="salution-errors"></div>
            <!--<span class="text-danger" id="campaign_type_error"></span>--> 
          </div>
        </div>
        <div class="col-xs-12 col-md-4 col-lg-4 col-sm-4 no-right-pad">
          <div class="form-group">
            <select multiple class="selectpicker form-control chosen-select" name="responsible_employee_id[]" id="responsible_employee_id" data-placeholder="<?=$this->lang->line('RESPONSIBLE_EMPLOYEE')?>">
              <?php
                                    foreach($responsible_employee_data as $row){
                                         if (in_array($row['login_id'], $responsible_user_data)){
                                        ?>
              <option selected value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>
              <?php }else{?>
              <option value="<?php echo $row['login_id'];?>"><?php echo $row['firstname'].' '.$row['lastname'];?></option>
              <?php }}?>
            </select>
          </div>
        </div>
         <div class="col-xs-12 col-md-4 col-lg-4 col-sm-4 no-left-pad">
          <div class="form-group bd-form-group">
           <div class="col-md-4 col-sm-4"> <label>
              <?= lang('END_DATE') ?>
            </label></div>
            <div class="input-group date col-md-8 col-sm-8" id='end_date'>
              <input type='text' data-parsley-errors-container="#end-date-errors" data-parsley-gteq="#start_date" class="form-control" name="end_date" placeholder="<?=$this->lang->line('END_DATE')?> *" value="<?=!empty($editRecord[0]['end_date'])?date("m/d/Y", strtotime($editRecord[0]['end_date'])):''?>" required/>
              <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
            <!-- <span class="text-danger" id="end_date_error"></span>--> 
          </div>
          <div id="end-date-errors"></div>
        </div>
        <div class="clr"></div>
      
       
        <div class="clr"></div>
        <div class="col-xs-12 col-md-12 col-lg-12 col-sm-12 no-left-pad">
          <div class="form-group">
            <textarea class="form-control" name="campaign_description" rows="5" id="campaign_description" placeholder="<?=$this->lang->line('CAMPAIGN_DESCRIPTION')?>"><?=!empty($editRecord[0]['campaign_description'])?$editRecord[0]['campaign_description']:''?>
</textarea>
          </div>
        </div>
        <div class="clr"> </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-left-pad">
          <?php if(!empty($editRecord[0]['budget_requirement']))
                        {
                            $edit_time_id = "budget_ammount_show";
                        }else{
                            $edit_time_id = "budget_ammount";
                        }
                        ?>
          <div class="form-group row">
           <div class="col-lg-6 col-sm-12 col-md-6  col-xs-12 form-group" id="<?php echo $edit_time_id;?>">
              <input name="budget_ammount" class="form-control"  maxlength="20" value="<?=!empty($editRecord[0]['budget_ammount'])?$editRecord[0]['budget_ammount']:''?>"  placeholder="<?=$this->lang->line('BUDGET_AMOUNT')?>" pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/" />
            </div>
           <div class="col-md-6 col-sm-12 bd-togl-label"> <div class="pull-left no-left-pad form-group">
              <div class="btn-group btn-toggle">
                <input data-toggle="toggle" data-onstyle="success" type="checkbox"  id="budget_requirement" name="budget_requirement"  data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" onChange="toggle_show_requirement(<?php echo "'#".$edit_time_id."'";?>, this)" <?=!empty($editRecord[0]['budget_requirement'])?'checked="checked"':''?>/>
              </div>
            </div>
            <div class="col-xs-8 col-md-6 col-lg-6 col-sm-6 no-right-pad bd-form-group">
            <label>
            <?=$this->lang->line('BUDGET_REQUIRED')?>
            </label>
            </div></div>
           
           
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-right-pad">
          <?php if(!empty($editRecord[0]['revenue_goal']))
                        { $revenue_time_id = "revenue_amount_show";
                        }else{
                            $revenue_time_id = "revenue_amount";
                        }?>
          <div class="form-group row">
           <div class="col-lg-6 col-sm-12 col-xs-12 col-md-6 form-group" id="<?php echo $revenue_time_id;?>">
              <input name="revenue_amount" class="form-control"  maxlength="20" value="<?=!empty($editRecord[0]['revenue_amount'])?$editRecord[0]['revenue_amount']:''?>" placeholder="<?=$this->lang->line('REVENUE_AMOUNT')?>" pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/"/>
            </div>
           <div class="col-md-6 col-sm-12 bd-togl-label"> <div class="pull-left no-left-pad form-group">
              <div class="btn-group btn-toggle">
                <input data-toggle="toggle" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" data-onstyle="success" type="checkbox"  id="revenue_goal" name="revenue_goal" onChange="toggle_show_requirement(<?php echo "'#".$revenue_time_id."'";?>, this)" <?=!empty($editRecord[0]['revenue_goal'])?'checked="checked"':''?>/>
              </div>
            </div>
            <div class="col-xs-8 col-md-6 col-lg-6 col-sm-6 no-right-pad bd-form-group">
              <label>
                <?=$this->lang->line('REVENUE_GOAL')?>
              </label>
            </div>
            <div class="clr"> </div></div>
           
          </div>
        </div>
        <div class="clr"> </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-left-pad">
          <?php if(!empty($editRecord[0]['campaign_supplier']))
                        { $campaign_time_id = "campaign_amount_show";
                        }else{
                            $campaign_time_id = "campaign_amount";
                        }?>
          <div class="form-group row">
          <div class="col-md-6 col-sm-12 col-md-6 form-group" id="<?php echo $campaign_time_id;?>">
             
                <select class="selectpicker form-control chosen-select" name="supplier_id" id="">
                  <option value="" selected="">
                  <?=$this->lang->line('SELECT_SUPPLIER')?>
                  </option>
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
           <div class="col-md-6 col-sm-12 bd-togl-label"> <div class="pull-left no-left-pad form-group" >
              <div class="btn-group btn-toggle ">
                <input data-toggle="toggle" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" data-onstyle="success" type="checkbox"  id="campaign_supplier" name="campaign_supplier" onChange="toggle_show(<?php echo "'#".$campaign_time_id."'";?>, this)" <?=!empty($editRecord[0]['campaign_supplier'])?'checked="checked"':''?> />
              </div>
            </div>
            <div class="col-xs-8 col-md-6 col-lg-6 col-sm-6 padding-rightnone bd-form-group">
            <label>
            <?=$this->lang->line('CAMPAIGN_BY_SUPPLIER')?>
            </label>
            </div>
            <div class="clr"> </div></div>
            
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-right-pad">
          <?php if(!empty($editRecord[0]['related_product']))
                        {
                            $related_time_id = "related_amount_show";
                        }else{
                            $related_time_id = "related_amount";
                        }?>
          <div class="form-group row">
          <div class="col-md-6 col-sm-12 form-group" id="<?php echo $related_time_id;?>">
              
                <select class="chosen-select form-control related_product" data-placeholder="<?=$this->lang->line('select_product')?>" multiple="true"  name="product_id[]" id="">
                  <?php if (!empty($product_info) && count($product_info) > 0) { ?>
                  <?php foreach ($product_info as $row) { ?>
                  <option value="<?php echo $row['product_id']; ?>" <?php if(!empty($product_data) && in_array($row['product_id'],$product_data)){echo 'selected="selected"';}?>><?php echo $row['product_name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
            
            </div>
            <div class="col-md-6 col-sm-12 bd-togl-label"><div class="pull-left no-left-pad form-group">
              <div class="btn-group btn-toggle">
                <input data-toggle="toggle" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" data-onstyle="success" type="checkbox"  id="related_product" name="related_product" onChange="toggle_show(<?php echo "'#".$related_time_id."'";?>, this)" <?=!empty($editRecord[0]['related_product'])?'checked="checked"':''?>/>
              </div>
            </div>
            <div class="col-xs-8 col-md-6 col-lg-6 col-sm-6 no-right-pad bd-form-group">
            <label>
            <?=$this->lang->line('RELATED_TO_PRODUCT')?>
            </label>
            </div>
            <div class="clr"> </div></div>
            
          </div>
        </div>
        <div class="clr"> </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-left-pad">
          <div class="form-group">
            <select class="form-control selectpicker chosen-select" name="campaign_group_id" id="campaign_group_id">
              <option value="" selected="">
              <?=$this->lang->line('ADD_CAMPAIGN_GROUP')?>
              </option>
              <?php

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
            <label class="control-label" for="inputCampaign" placeholder="<?=$this->lang->line('ADD_CAMPAIGN_RECEIPIENTS')?>">
              <?=$this->lang->line('ADD_CAMPAIGN_RECEIPIENTS')?>
            </label>
            <select multiple name="contact_id[]" id="contact_id" class="chosen-select"  data-placeholder="<?=$this->lang->line('EST_LABEL_SELECT_RECIPIENTS')?>">
              <option value=""></option>
              <optgroup label="Contact">
              <?php foreach ($contact_info as $contact) { ?>
              <?php if (in_array('contact_' . $contact['contact_id'], $EstRecipientArray)){?>
              <option selected value="contact_<?php echo $contact['contact_id']; ?>"><?php echo $contact['contact_name']; ?></option>
              <?php }else{?>
              <option value="contact_<?php echo $contact['contact_id']; ?>"><?php echo $contact['contact_name']; ?></option>
              <?php }} ?>
              </optgroup>
            </select>
          </div>
        </div>
        <input type="hidden" name="id" id="id" value="<?=!empty($editRecord[0]['campaign_id'])?$editRecord[0]['campaign_id']:''?>" />
        <!-- new code-->
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 no-right-pad bd-dragimage">
          <div class="mediaGalleryDiv mb15">
            <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
            <div class="mediaGalleryImg"> </div>
          </div>
          <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
            <div class="image_part" style="height: 100px;">
              <label name="fileUpload[]">
              <h1 style="top: -162px;"> <i class="fa fa-cloud-upload"></i>
                <?= lang('DROP_IMAGES_HERE') ?>
              </h1>
              <input type="file" onchange="showimagepreview(this)" name="fileUpload[]" style="display: none" id="upl" multiple />
              </label>
            </div>
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
              <!--     <a class="btn delimg remove_drag_img" href="javascript:;" data-id="img_<?php echo $image['file_id']; ?>" data-href="<?php echo base_url('Marketingcampaign/deleteImage/' . $image['file_id']); ?>">x</a>--> 
              
              <a class="btn delimg remove_drag_img" href="javascript:;" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['file_id']; ?>" data-path="<?php echo $path; ?>">x</a> <span id="<?php echo $i; ?>" class="preview"> <a href='<?php echo base_url('Marketingcampaign/download/' . $image['file_id']); ?>' target="_blank">
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
            <div id="deletedImagesDiv"></div>
            <?php }  }?>
          </div>
          <div class="clr"> </div>
        </div>
        
        <!-- end new code --> 
        
      </div>
      <div class="clr"> </div>
      <br/>
      <div class="modal-footer">
        <div class="text-center">
          <?php if(!empty($editRecord)){?>
          <input type="submit" class="btn btn-primary" id="camp_submit_btn" value="<?=$this->lang->line('UPDATE_CAMPAIGN')?>">
          <?php }else{?>
          <input type="submit" class="btn btn-primary" id="camp_submit_btn" value="<?=$this->lang->line('CREATE_MARKETING_CAMPAING')?>">
          <?php }?>
        </div>
      </div>
      <div class="clr"> </div>
      <br/>
    </div>
  </form>
</div>
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Uploads</h4>
      </div>
      <div class="modal-body" id="modbdy"> </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');">Close</button>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 
<script type="text/javascript">
    $('.related_product').trigger('chosen:updated');

    $('#start_date').datepicker({
        autoclose: true,
        startDate : new Date(),
    }).on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#end_date').datepicker('setStartDate', startDate);
    });
    $('#end_date')
        .datepicker({autoclose: true,startDate : new Date()
        });

    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });

</script>
<script>
    $(document).ready(function () {
        $('#frm_createcampaign').parsley();
    });
    window.Parsley.addValidator('gteq',
        function (value, requirement) {
            return Date.parse($('#end_date input').val()) >= Date.parse($('#start_date input').val());
        }, 32)
        .addMessage('en', 'le', 'This value should be less or equal');



    $(function () {
        var availableBranch = [
            <?php
            if (isset($campaign_type_info) && count($campaign_type_info) > 0) {
                $count = 0;
                foreach ($campaign_type_info as $campaign_type) {
                    $count++;
                    echo '"' . addslashes($campaign_type['camp_type_name']) . '"';
                    if ($count != count($campaign_type_info)) {
                        echo ", ";
                    }
                }
            }
            ?>
        ];
        $("#campaign_type_id").autocomplete({
            source: availableBranch
        });
    });

    $('#campaign_description').summernote({
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
</script>
<?php $this->load->view($js_content);?>
<!--</div>-->