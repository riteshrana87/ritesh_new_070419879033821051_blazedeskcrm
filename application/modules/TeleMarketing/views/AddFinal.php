 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($readonly)) {

    $disable = $readonly['disabled'];
} else {
    $disable = "";
}

if ($project_view != 'viewdata') {
    $formAction = 'saveTelemarketingData';
} else {
    $formAction = '';
}
if ($project_view == 'editdata') {

    $formAction = 'updateTelemarketingData';
} else {
    $formAction = '';
}
if ($project_view == 'TeleMarketing') {
    $formAction = 'saveTelemarketingData';
} else {
    $formAction = '';
}

$formPath = $project_view . '/' . $formAction;
//echo $project_view;

/* pr($ticket_data);
  die('aa'); */
?>
<!--modal-->

 <div class="modal-dialog modal-lg" role="document">
  <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
		<input type="hidden" name="update_id" id="update_id" <?php if(isset($id)) { ?>value="<?php echo $id;?><?php }?>"/>
		<input type="hidden" name="user_id" id="user_id" <?php if(isset($tele_data[0]['user_id'])) { ?>value="<?php echo $tele_data[0]['user_id'];?><?php }?>"/>
        
    <div class="modal-content">
      <div class="modal-header">
        <button title="<?php echo lang('close') ?>" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><b>
			<?php if($project_view=='TeleMarketing'){?>
			<?php echo lang('create_new_telemarketing') ?>
			<?php } else{ ?>
				<?php echo lang('edit_telemarketing') ?>
				
				<?php }?>
			</b></h4>
      </div>
      <div class="modal-body">
          
          <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('company_name') ?>
					<?php }?>
                       <input type="text" maxlength="50"  data-parsley-pattern="/^([^0-9]*)$/"   class="form-control" placeholder="<?php echo lang('company_contact_name') ?> *" id="tele_name" name="tele_name"  required <?php if(isset($tele_data[0]['tele_name'])) { ?>   value="<?php echo $tele_data[0]['tele_name'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        <span id="cost_name_error" class="alert-danger"></span>
              </div>
               <div class="clr"></div>
			    
            
            
          </div>
          <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('company_name') ?>
					<?php }?>
					 <input type="text" maxlength="50" data-parsley-pattern="/^([^0-9]*)$/"   class="form-control" placeholder="<?php echo lang('select_company') ?> *" id="company_name" name="company_name"  required <?php if(isset($tele_data[0]['company_name'])) { ?>   value="<?php echo $tele_data[0]['company_name'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        <span id="cost_name_error" class="alert-danger"></span>
						<!--<select <?php if($project_view=='viewdata'){?>disabled="disabled"<?php }?> name="company_name" class="form-control chosen-select"  id="company_name" required data-parsley-errors-container="#help-errors">
                        <option value="">
                            <?= $this->lang->line('select_company') ?> *
                        </option>
                        
                        <?php if (isset($company_data) && count($company_data) > 0) { ?>
						<?php foreach ($company_data as $company_data) { ?>
                                <option value="<?php echo $company_data['company_id']; ?>" <?php
                                    if ((!empty($tele_data[0]['company_id']) && $tele_data[0]['company_id'] == $company_data['company_id'])) {
                                        echo 'selected';
                                    }
                                    ?>><?php echo $company_data['company_name']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>-->
                    
                     
              </div>
                       
               <div class="clr"></div>
			    
            
            
          </div>
		  <div class="clr"></div>
		  <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('phone_no') ?>
					<?php }?>
                       <input type="text" data-parsley-pattern='^[\d\+\-\.\(\)\/\s]*$' maxlength="25"   class="form-control" placeholder="<?php echo lang('phone_no') ?> *" id="phone_no" name="phone_no"  required <?php if(isset($tele_data[0]['phone_no'])) { ?>   value="<?php echo $tele_data[0]['phone_no'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        <span id="cost_name_error" class="alert-danger"></span>
              </div>
               <div class="clr"></div>
          </div>
		   
		 
		   <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('type') ?>
					<?php }?>
						<select <?php if($project_view=='viewdata'){?>disabled="disabled"<?php }?> name="status" class="form-control chosen-select"  id="status" required data-parsley-errors-container="#help-errors">
                        <option value="">
                            <?= $this->lang->line('select_tele_status') ?> *
                        </option>
                        <option value="0" <?php if(isset($tele_data[0]['status']) && $tele_data[0]['status']=='0'){echo "selected='selected'";}?>><?= lang('not_call');?></option>
                        <option value="1" <?php if(isset($tele_data[0]['status']) && $tele_data[0]['status']=='1'){echo "selected='selected'";}?>><?= lang('pos_req');?></option>
                        <option value="2" <?php if(isset($tele_data[0]['status']) && $tele_data[0]['status']=='2'){echo "selected='selected'";}?>><?= lang('pos_demo');?></option>
                        <option value="3" <?php if(isset($tele_data[0]['status']) && $tele_data[0]['status']=='3'){echo "selected='selected'";}?>><?= lang('pos_became_client');?></option>
                        <option value="4" <?php if(isset($tele_data[0]['status']) && $tele_data[0]['status']=='4'){echo "selected='selected'";}?>><?= lang('neg_not_int');?></option>
						<option value="5" <?php if(isset($tele_data[0]['status']) && $tele_data[0]['status']=='5'){echo "selected='selected'";}?>><?= lang('voice');?></option>
						<option value="6" <?php if(isset($tele_data[0]['status']) && $tele_data[0]['status']=='6'){echo "selected='selected'";}?>><?= lang('call_back');?></option>
                    </select>
                     <div id="help-errors"></div>   
              </div>
                       
               <div class="clr"></div>
			    
            
            
          </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
             
                    <div class="form-group">
                        <?php if ($project_view == 'viewdata') { ?>
                            <?php echo lang('remark') ?>
                        <?php } ?>
                         <textarea id="remark" tabindex="26" <?php if ($project_view == 'viewdata') { ?>disabled="disabled"<?php } ?> class="form-control" rows="4" placeholder="<?php echo lang('remark') ?>" name="remark" ><?php
                                   if (!empty($tele_data[0]['remark'])) {
                                       echo $tele_data[0]['remark'];
                                   }
                                    ?></textarea>
                        <ul class="parsley-errors-list filled" id="termsError" >
                            <li class="parsley-required"><?php echo lang('val_req') ?></li>
                        </ul>
                        <?php //echo $project_view;  ?>
                       
                    </div>
                    <div class="clr"></div>
                    <br/>
			    
            
            
          </div>
	
              <div class="clr"></div>
     
        
      </div>

     <div class="modal-footer">
				<?php if($project_view != 'viewdata') { ?>
                <div class="text-center">
                    <input type="submit" class="btn btn-green" name="submit_btn" id="submit_btn" 
                    <?php if($project_view == 'TeleMarketing'){?>
                    value="<?php echo lang('create_new_telemarketing'); ?>" 
                    <?php } else {?>
						value="<?php echo lang('edit_telemarketing'); ?>" 
						<?php } ?>
                    />
				 
					 </div>
				<?php }?>
                <div class="clr"> </div>
            </div>
    </div>
	</form>
  </div>



<script>

    $('#email_notification').bootstrapToggle();
    $('#agent_notify').bootstrapToggle();
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    $('#start_date').datepicker({
        autoclose: true
    }).on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#due_date').datepicker('setStartDate', startDate);
    });
    $('#due_date')
            .datepicker({autoclose: true,
                startDate: '-0m'
            });

</script> 
<script>
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });

    $("#team_id").change(function () {

        var id = this.value;
      
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('Ticket/getTeam'); ?>',
            data: 'id=' + id,
            dataType:"json",
            success: function (data) {
                //  $('#userList').html(data);
               //console.log(data);
               // var team = $.parseJSON(data);

                $('#team_member_id').html('');
                  if (data.length > 0) {
                for(var i=0;i<data.length;i++)
                {
                                            $('#team_member_id').append('<option value="' + data[i].login_id + '">' + data[i].firstname + data[i].lastname + '</option>')

                }
                }
               
                $('#team_member_id').trigger('chosen:updated');

            }

        });
    });
</script> 
<script type="text/javascript">
    $(document).ready(function () {
        $("#termsError").css("display", "none");
        $('#from-model').parsley();
        $('#remark').summernote({
			disableDragAndDrop: true,
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
          $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });

        $('body').delegate('#from-model', 'submit', function () {
            

         var wys = $('.note-editable').html();
        var value = wys.replace(/(?:&nbsp;|<br>|<p>|<\/p>)/ig, "");
        var final_value = value.replace(/&nbsp;/g, '');
        final_value = final_value.replace(/^\s+/g, '');
       if (final_value != '') {
                $("#termsError").css("display", "none");
                $('input[type="submit"]').prop('disabled', true);
                response = true;
                return true;
            } else {
                $("#termsError").css("display", "block");
                response = false;
                return false;
                // content is empty
            }
            //return false;


        });
        
    });
    $('#due_date').datepicker({autoclose: true, startDate: new Date()});

</script> 
<script>
    /* image upload */
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
  
    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg ="<?php echo $this->lang->line('delete_request_item');?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                        $('#' + divId).remove();
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
                        dialog.close();
                    }
                }]
            });

    });

    var config = {
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo $sales_view; ?>/upload_file"				// Server side upload url
    }
    $(document).ready(function () {
        //initMultiUploader(config);
        var dropbox;
        var oprand = {
            dragClass: "active",
            on: {
                load: function (e, file) {
                    // check file size
                    if (parseInt(file.size / 256) > 20480) {
                        var delete_meg ="<?php echo lang("file"); ?> \"" + file.name + "\" <?php echo lang('too_big_size'); ?>";
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


                        return false;
                    }

                    create_box(e, file);
                },
            }
        };
        FileReaderJS.setupDrop(document.getElementById('dragAndDropFiles'), oprand);
        var fileArr = [];

        create_box = function (e, file) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var imgName = file.name; // not used, Irand just in case if user wanrand to print it.
            var src = e.target.result;
            var xhr = new Array();
            xhr[rand] = new XMLHttpRequest();
//            console.log(xhr[rand]);

            var filename = file.name;
            var fileext = filename.split('.').pop();
//            console.log(fileext);
            xhr[rand].open("post", "<?php echo base_url('/Ticket/upload_file') ?>/" + fileext, true);

            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                    $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                }
                else {
                    var delete_meg ="<?php echo lang("fail_file_upload"); ?>";
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
                }
            }, false);

            
            xhr[rand].setRequestHeader("Content-Type", "multipart/form-data");
            xhr[rand].setRequestHeader("X-File-Name", file.fileName);
            xhr[rand].setRequestHeader("X-File-Size", file.fileSize);
            xhr[rand].setRequestHeader("X-File-Type", file.type);

            // Send the file (doh)
            xhr[rand].send(file);

        }
        upload = function (file, rand) {
        }

    });


    //image upload
  
</script>
