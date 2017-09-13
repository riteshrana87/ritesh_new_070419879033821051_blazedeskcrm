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
      <?php $attributes = array("name" => "add_contact", "id" => "add_contact", 'data-parsley-validate' => "");
echo form_open_multipart($path,$attributes);
?>
  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="set_label"><?php echo $modal_title;?>
        </h4>
      </div>
      <div class="modal-body">
           
                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input type="text" name="contact_name" class="form-control" id="contact_name" placeholder="<?=$this->lang->line('contact_name')?>*" value="<?=!empty($editRecord[0]['contact_name'])? htmlentities(stripslashes($editRecord[0]['contact_name'])):''?>" required/>
                    </div>
                    <div class="col-sm-6 form-group">

                        <input type="email" name="email" class="form-control" id="email" placeholder="<?=$this->lang->line('email')?>*" value="<?=!empty($editRecord[0]['email'])?$editRecord[0]['email']:''?>" data-parsley-trigger="change" required/>
                        
                    </div>
                </div>

                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <!--
                        <select name="job_title" class="form-control chosen-select"  id="job_title" data-parsley-errors-container="#job_title-errors">
                            <option value=""><?= $this->lang->line('SELECT_JOB_TITLE') ?></option>
                            <?php if (isset($job_title_data) && count($job_title_data) > 0) { ?>
                            <?php foreach ($job_title_data as $job_title) { ?>
                            <option value="<?php echo $job_title['job_title_id']; ?>" 
                                <?php if(!empty($editRecord[0]['job_title']) && $editRecord[0]['job_title'] == $job_title['job_title_id']) 
                                    { echo "selected='selected'"; }  ?>>
                                <?php echo htmlentities(stripslashes($job_title['job_title_name'])); ?>
                            </option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                         <div id="job_title-errors"></div>
                        -->
                        <select class="chosen-select form-control" onchange="api_call();" data-placeholder="<?=$this->lang->line('select_country')?>*"  name="country_id" id="country_id" required data-parsley-errors-container="#country-errors">
                            <option value=""></option>
                            <?php
                            $country_id = $editRecord[0]['country_id'];?>
                            <?php foreach($country_data as $row){
                                if($country_id == $row['country_id']){?>
                                    <option selected data-taxincluded-amount="<?php echo $row['country_code']; ?>" value="<?php echo $row['country_id'];?>"><?php echo $row['country_name'];?></option>
                                <?php }else{?>
                                    <option data-taxincluded-amount="<?php echo $row['country_code']; ?>" value="<?php echo $row['country_id'];?>"><?php echo $row['country_name'];?></option>

                                <?php }}?>
                        </select>
                        <div id="country-errors"></div>
                    </div>
                    <div class="col-sm-6 form-group">
                        <select name="company_id" data-placeholder="<?= $this->lang->line('select_company') ?>*" class="form-control chosen-select" onchange="company_show('#second_time_hide', this.value);autofillCompanyAddress(this.value);" id="company_id" required required data-parsley-errors-container="#company-errors">
                            <option value=""></option>
                            <option value="add_another"><?=lang('add_new_company')?></option>
                            <?php if (isset($company_data) && count($company_data) > 0) { ?>
                                <?php foreach ($company_data as $company_data) { ?>
                                    <option value="<?php echo $company_data['company_id']; ?>"
                                        <?php if(!empty($editRecord[0]['company_id']) && $editRecord[0]['company_id'] == $company_data['company_id'])
                                        { echo 'selected'; }  ?>>
                                        <?php echo $company_data['company_name']; ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                        <div id="company-errors"></div>
                        <input type="hidden" name="com_reg_number" id="com_reg_number" value="">
                        <input type="hidden" name="company_id_data" id="company_id_data" value="">
                    </div>
                </div>
          <div class = " row" id="second_time_hide">
              <div class="row">
                  <div class = "col-sm-3 form-group">
                      <input type="text" class="form-control" placeholder="<?=$this->lang->line('company_name')?>*" id="company_name" name="company_name"><span></span>
                  </div>
                  <div class = "col-sm-3 form-group">
                      <input type="email" class="form-control" placeholder="<?=$this->lang->line('email_id_company')?>*" id="email_id_company" name="email_id_company" data-parsley-trigger="change">
                  </div>
                  <div class = "col-sm-3 form-group">
                      <input type="text" class="form-control" placeholder="<?=$this->lang->line('website')?>" id="website" name="website">
                  </div>
                  <div class = "col-sm-3">
                      <input type="text" class="form-control" placeholder="<?=$this->lang->line('phone_no_company')?>*" id="phone_no_company" name="phone_no_company"  min="0">
                  </div>
              </div>
              <div class="row">
                  <div class = "col-sm-3 form-group">
                      <input type="text" id="branch_id" name="branch_id" placeholder="<?php echo lang('branche'); ?> *" class="form-control">
                  </div>
              </div>
          </div>
                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input type="text" name="contact_for" class="form-control" id="contact_for" placeholder="<?=$this->lang->line('contact_for')?>" value="<?=!empty($editRecord[0]['contact_for'])?htmlentities(stripslashes($editRecord[0]['contact_for'])):''?>" />
                    </div>
                    <div class="col-sm-6 form-group">
                        <select name="language_id" class="form-control chosen-select" id='language_id'>
                        <option value=""><?= $this->lang->line('language_not_filled') ?></option>
                        <?php if (isset($language_data) && count($language_data) > 0) { ?>
                            <?php foreach ($language_data as $language) { ?>
                                <option value="<?php echo $language['language_id']; ?>" <?php
                                if (!empty($editRecord[0]['language_id']) && $editRecord[0]['language_id'] == $language['language_id']) {
                                    echo 'selected';
                                }
                                ?>><?php echo $language['language_name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                    </select>
                    </div>
                </div>

                <div class=" row">
                     <div class="col-sm-6 form-group">
                         <input type="text" name="phone_number" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" class="form-control" id="phone_number" placeholder="<?=$this->lang->line('phone_no')?>" value="<?=!empty($editRecord[0]['phone_number'])?$editRecord[0]['phone_number']:''?>" />
                    </div>
                    <div class="col-sm-6 form-group">
                        <input type="text" name="mobile_number" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" class="form-control" id="mobile_number" placeholder="<?=$this->lang->line('mobile_number')?>*" value="<?=!empty($editRecord[0]['mobile_number'])?$editRecord[0]['mobile_number']:''?>" required/>
                    </div>
                </div>



                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input type="text" name="address1" class="form-control" id="address1" placeholder="<?=$this->lang->line('address1')?>" value="<?=!empty($editRecord[0]['address1'])?htmlentities(stripslashes($editRecord[0]['address1'])):''?>" />
                    </div>
                    <div class="col-sm-6 form-group">
                        <input type="text" name="address2" class="form-control" id="address2" placeholder="<?=$this->lang->line('address2')?>" value="<?=!empty($editRecord[0]['address2'])?htmlentities(stripslashes($editRecord[0]['address2'])):''?>" />
                    </div>
                </div>

                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input type="text" name="city" class="form-control" id="city" placeholder="<?=$this->lang->line('city')?>" value="<?=!empty($editRecord[0]['city'])?htmlentities(stripslashes($editRecord[0]['city'])):''?>" />
                    </div>
                    <div class="col-sm-6 form-group">
                        <input type="text" name="state" class="form-control" id="state" placeholder="<?=$this->lang->line('state')?>" value="<?=!empty($editRecord[0]['state'])?htmlentities(stripslashes($editRecord[0]['state'])):''?>" />
                    </div>
                </div>
                <div class=" row">
                     <div class="col-sm-6 form-group">
                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('postal_code') ?>" id="postal_code" name="postal_code" value="<?php if(!empty($editRecord[0]['postal_code'])) { echo $editRecord[0]['postal_code']; }?>">
                    </div>
                    <div class="col-sm-6 form-group">
                        <input type="text" name="job_title" class="form-control" id="job_title" placeholder="<?=$this->lang->line('job_title')?>" value="<?=!empty($editRecord[0]['job_title'])?htmlentities(stripslashes($editRecord[0]['job_title'])):''?>" />
                        
                        </div>
                   
                </div>
          
                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input type="url" name="url_fb" class="form-control" id="url_fb" placeholder="<?= $this->lang->line('URL_FACEBOOK') ?>" value="<?=!empty($editRecord[0]['fb'])?$editRecord[0]['fb']:''?>" data-parsley-trigger="change"/>
                    </div>
                    <div class="col-sm-6 form-group">
                        <input type="url" name="url_linkedin" class="form-control" id="url_linkedin" placeholder="<?= $this->lang->line('URL_LINKEDIN') ?>" value="<?=!empty($editRecord[0]['linkdin'])?$editRecord[0]['linkdin']:''?>" data-parsley-trigger="change"/>
                    </div>
                </div>
          
                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input type="url" name="url_twitter" class="form-control" id="url_twitter" placeholder="<?= $this->lang->line('URL_TWITTER') ?>" value="<?=!empty($editRecord[0]['twitter'])?$editRecord[0]['twitter']:''?>" data-parsley-trigger="change"/>
                    </div>
                     <div class="col-sm-6 form-group">
                     <select name="status" class="form-control chosen-select" id="status" tabindex="9">
<!--                        <option value="">
                            <?= $this->lang->line('select_status') ?>
                        </option>-->
                        <option value="1" <?php if (isset($editRecord[0]['status']) && $editRecord[0]['status']==1 ) { echo 'selected=selected'; } ?>><?=  lang('active'); ?></option>
                        <option value="0" <?php if (isset($editRecord[0]['status']) && $editRecord[0]['status']==0) { echo 'selected=selected'; } ?>><?=  lang('inactive'); ?></option>
                     </select>
                     </div>
                </div>
                 <div class=" row">
                    <div class="col-sm-6 form-group">
                        <label class="custom-upload btn btn-blue"><?= $this->lang->line('profile_image') ?>
                        <input type="file" class="form-control input-group" name="profile_image"  id="profile_image" onchange="$('#profile_image_txt').html($('#profile_image').val().split('\\').pop());" placeholder="<?=$this->lang->line('profile_image')?>" value="<?=!empty($editRecord[0]['image'])?$editRecord[0]['image']:''?>" data-parsley-fileextension='png|jpeg|jpg|JPG|PNG|JPEG' data-parsley-max-file-size="2000" data-parsley-errors-container="#profile_image_errors"/>
                        </label>
                        <p id="profile_image_txt"></p>
                        <p id="profile_image_errors"></p>
                    </div>
                     <div class="col-sm-6 form-group">
                         <label class="custom-upload btn btn-blue"><?= $this->lang->line('logo_image') ?>
                        <input type="file" class="form-control" name="logo_image"  id="logo_image" onchange="$('#logo_image_txt').html($('#logo_image').val().split('\\').pop());" placeholder="<?=$this->lang->line('logo_image')?>" value="<?=!empty($editRecord[0]['logo_image'])?$editRecord[0]['logo_image']:''?>" data-parsley-fileextension='png|jpeg|jpg|JPG|PNG|JPEG' data-parsley-max-file-size="2000" data-parsley-errors-container="#logo_image_errors"/>
                         </label>
                          <p id="logo_image_txt"></p>
                          <p id="logo_image_errors"></p>
                    </div>
                    
                </div>
                <div class=" row">
                    <div class="col-sm-6 form-group">
                            <?php if(!empty($editRecord[0]['image'])) {
                                $path= FCPATH.('uploads/contact').'/'.$editRecord[0]['image'];
                               
                               if (file_exists($path)) { ?>
                        <div class="col-lg-6"><img class="img-responsive thumbnail" src="<?php echo base_url('uploads/contact').'/'.$editRecord[0]['image']; ?>" class="img-responsive">
                            </div><?php } } ?>
                    </div>
                    <div class="col-sm-6 form-group" id="logo_img_dv">
                            <?php if(!empty($editRecord[0]['company_logo'])) {
                                $path= FCPATH.('uploads/company').'/'.$editRecord[0]['company_logo'];
                               
                               if (file_exists($path)) { ?>
                        <div class="col-lg-6"><img class="img-responsive thumbnail" src="<?php echo base_url('uploads/company').'/'.$editRecord[0]['company_logo']; ?>">
                        </div>
                            <?php } } ?>
                    </div>
                </div>
          
            <?php if((count($newsletterLists) > 0))
            {?>
            <div class=" row">
                <div class="col-sm-6 form-group">
                    <input <?php if (!empty($editRecord[0]['is_newsletter'])) { ?>checked="checked"<?php } ?> class="event_reminder_tog" data-toggle="toggle" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" data-onstyle="success" type="checkbox"  id="is_newsletter" name="is_newsletter"/>
                    <label><?= $this->lang->line('ADDED_TO_NEWSLETTER') ?></label>
                </div>
                
                <?php //pr($newsletterSeleectedLists);?>
                <div class="col-sm-6 form-group hidden" id="listsDropDown">
                    <select name="newsletterLists[]" multiple="true" data-placeholder="<?php echo lang('SELECT_NEWSLLETTER_LISTS');?>" class="form-control chosen-select" data-parsley-errors-container="#lists-errors" id="newsletterLists">
                        <option value=""></option>
                        <?php
                            if(count($newsletterLists) > 0)
                            {
                                foreach ($newsletterLists as $listsKey => $listsVal)
                                { ?>
                                    <option value="<?php echo $listsKey; ?>" <?php if(in_array($listsKey,$newsletterSeleectedLists)){ echo "selected='selected'";}?>><?php echo $listsVal;?></option>
                               <?php  }
                            }
                        ?>
                    </select>
                    <div id="lists-errors"></div>
                </div>
            </div>
          
            <?php }?>
            <input type="hidden" id="redirect_link" name="redirect_link" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
            <input type="hidden" name="redirect" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
            <input type="hidden" name="id" id="id" value="<?=!empty($editRecord[0]['contact_id'])?$editRecord[0]['contact_id']:''?>  " />
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>"> 
      </div>
      <div class="modal-footer">
        <center> <input type="submit" class="btn btn-primary" id="contact_submit_btn" value="<?=$submit_button_title?>"></center>
      </div>
    </div>
  <?php echo form_close(); ?>
  </div>
  
<script>
    //disabled after submit
        $('body').delegate('#contact_submit_btn', 'click', function () {
            if ($('#add_contact').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                $('#add_contact').submit();
            }
        });    
    $(document).ready(function () {
        $('#add_contact').parsley();
         $('.event_reminder_tog').bootstrapToggle();
       
     $('.chosen-select').chosen();   
      window.ParsleyValidator
                .addValidator('fileextension', function (value, requirement) {
                    // the value contains the file path, so we can pop the extension
                    var fileExtension = value.split('.').pop();
                    var multipleFileType = requirement.split('|');
                   
                    if ($.inArray(fileExtension, multipleFileType) != -1)
                    {
                        return true;
                    }else
                    {
                        return false;
                    }
                    
                }, 32)
                .addMessage('en', 'fileextension', '<?php echo lang('MSG_UPLOAD_PROFILE_PIC');?>');
        
         window.Parsley.addValidator('maxFileSize', {
            validateString: function (_value, maxSize, parsleyInstance) {
               if (!window.FormData) {
                    var delete_meg ="<?php echo lang('upgrade_your_browser');?>";
                    BootstrapDialog.show(
                        {
                            title: '<?php echo $this->lang->line('Information');?>',
                            message: delete_meg,
                            buttons: [{
                                label: '<?php echo $this->lang->line('ok');?>',
                                action: function(dialog) {
                                    dialog.close();
                                }
                            }]
                        });

                    return true;
                }
                var files = parsleyInstance.$element[0].files;
                return files.length != 1 || files[0].size <= maxSize * 1024;
            },
            requirementType: 'integer',
            messages: {
                en: 'This file should not be larger than %s Kb',
                fr: "Ce fichier est plus grand que %s Kb."
            }
        });
    });
    function company_show(className, val) {
        if(val == 'add_another') {  
            $("#company_name, #email_id_company, #phone_no_company,#branch_id").attr("required", "required");
            $(className).show(); 
        }
        else { 
            $("#company_name, #email_id_company, #phone_no_company,#branch_id").removeAttr("required");
            $(className).hide(); 
        }
    }
    
    function autofillCompanyAddress(company_id)
    {
        
        if(company_id != 'add_another')
        {
            var url_contact = '<?php echo base_url() . "Contact/getCompanyAddressById/" ?>';
            $.ajax({
                type: "POST",
                url: url_contact,
                data: {'company_id': company_id},
                success: function (data)
                {
                    var dataObj = jQuery.parseJSON(data);
                    $('#phone_number').val(dataObj.phone_no);
                    $('#address1').val(dataObj.address1);
                    $('#address2').val(dataObj.address2);
                    $('#city').val(dataObj.city);
                    $('#state').val(dataObj.state);
                    $('#postal_code').val(dataObj.postal_code);
                    $('#country_id').val(dataObj.country_id).trigger("chosen:updated");
                    $('#logo_img_dv').html('');
                    if(dataObj.logo_img != '')
                    {
                        $('#logo_img_dv').html("<div class='col-lg-6'><img class='img-responsive thumbnail' src="+dataObj.logo_img+"></div>");
                    }
                    
                }
            });
        }
      
        
    }
    $(function () {
        var availableBranch = [
<?php
if (isset($branch_data) && count($branch_data) > 0) {
    $count = 0;
    foreach ($branch_data as $branch) {
        $count++;
        echo '"' . addslashes($branch['branch_name']) . '"';
        if ($count != count($branch_data)) {
            echo ", ";
        }
    }
}
?>
        ];
        $("#branch_id").autocomplete({
            source: availableBranch
        });
    });
    function api_call()
    {
        var country = $('#country_id').find(':selected').attr("data-taxincluded-amount");
        $("#company_name").autocomplete( {
            width: 260,
            matchContains: true,
            selectFirst: false,
            max:15,
            minLength: 3,
            source:'<?php echo base_url();?>GetApiData?country_id='+country,
            search: function(){
                $("#company_name").siblings().addClass("bd-input-load");
            },
            response: function (event, ui) {
                if (ui.content.length === 0) {
                    $(".bd-input-load").removeClass("bd-input-load");
                }
            },
            select: function( event , ui ) {
                $(".bd-input-load").removeClass("bd-input-load");
                $("#company_id_data").val(ui.item.id);
                $("#address1").val(ui.item.address);
                $("#postal_code").val(ui.item.zipcode);
                $("#city").val(ui.item.city);
                $("#com_reg_number").val(ui.item.reg_number);
                $("#com_est_number").val(ui.item.est_number);
                
            }
        });
    }
    <?php if (!empty($editRecord[0]['country_id'])) { ?>
    api_call();
    <?php } ?>
        
    //Added by sanket for displaying newsletter Lists Dropdown    
    $('#is_newsletter').change(function () {
        var abv = $('#is_newsletter').parents('div').attr('class');

        if (abv.indexOf("btn-success") == -1)
        {
            // $('#listsDropDown').css('display', 'none');
            $('#listsDropDown').addClass('hidden');
            $('#newsletterLists').attr('data-parsley-required', 'false').trigger("chosen:updated");
        } else
        {
            //$('#listsDropDown').css('display', 'block');
            $('#listsDropDown').removeClass('hidden');
            $('#newsletterLists').attr('data-parsley-required', 'true').trigger("chosen:updated");
        }
    });
    
    <?php 
    if(!empty($editRecord[0]['is_newsletter']))
    { ?>
        // $('#listsDropDown').css('display', 'block');
         $('#listsDropDown').removeClass('hidden');
   <?php }
    ?>
</script>
