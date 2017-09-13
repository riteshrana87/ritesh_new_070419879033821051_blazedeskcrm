<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($readonly)){
	
	$disable = $readonly['disabled'];
}else{
	$disable = "";
}

if($project_view!='viewdata'){
$formAction = 'saveHelpData';
}else{
	$formAction='';
	
}
if($project_view=='Help/updatedata'){

$formAction = 'updateHelpData';
}else{
	$formAction='';
	
}
if($project_view=='Help'){
$formAction = 'saveHelpData';
}else{
	$formAction='';
	
}

$formPath = $project_view . '/' . $formAction;
//echo $project_view;die;

/*pr($setup);
die('aa');*/
?>    
<!--modal-->

  <div class="modal-dialog modal-lg" role="document">
  <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
		<input type="hidden" name="update_id" id="update_id" <?php if(isset($id)) { ?>value="<?php echo $id;?><?php }?>"/>
        
    <div class="modal-content">
      <div class="modal-header">
        <button title="<?php echo lang('close') ?>" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><b><?php echo lang('help') ?></b></h4>
      </div>
      <div class="modal-body">
          
          <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('firstname') ?>
					<?php }?>
                       <input type="text"   class="form-control" placeholder="<?php echo lang('firstname') ?> *" id="firstname" name="firstname"  required <?php if(isset($setup[0]['firstname'])) { ?>   value="<?php echo $setup[0]['firstname'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        <span id="cost_name_error" class="alert-danger"></span>
              </div>
               <div class="clr"></div>
			    
            
            
          </div>
		  
		  <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('lastname') ?>
					<?php }?>
                       <input type="text"   class="form-control" placeholder="<?php echo lang('lastname') ?> *" id="lastname" name="lastname"  required <?php if(isset($setup[0]['lastname'])) { ?>   value="<?php echo $setup[0]['lastname'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        <span id="cost_name_error" class="alert-danger"></span>
              </div>
               <div class="clr"></div>
			    
            
            
          </div>
		   
		   <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('email') ?>
					<?php }?>
                       <input type="email"   class="form-control" data-parsley-trigger="change" placeholder="<?php echo lang('email') ?> *" id="email" name="email"  required <?php if(isset($setup[0]['email'])) { ?>   value="<?php echo $setup[0]['email'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        <span id="cost_name_error" class="alert-danger"></span>
              </div>
               <div class="clr"></div>
			    
            
            
          </div>
		  
		  <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('platform_name') ?>
					<?php }?>
                       <input type="text"   class="form-control" placeholder="<?php echo lang('platform_name') ?> *" id="platform_name" name="platform_name"  required <?php if(isset($setup[0]['platform_name'])) { ?>   value="<?php echo $setup[0]['platform_name'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        <span id="cost_name_error" class="alert-danger"></span>
              </div>
               <div class="clr"></div>
			    
            
            
          </div>
		  
		  <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('subject') ?>
					<?php }?>
                       <input type="text"   class="form-control" placeholder="<?php echo lang('subject') ?> *" id="subject" name="subject"  required <?php if(isset($setup[0]['subject'])) { ?>   value="<?php echo $setup[0]['subject'];?>" <?php } if($project_view=='viewdata'){?>readonly<?php }?>>
                        <span id="cost_name_error" class="alert-danger"></span>
              </div>
               <div class="clr"></div>
			    
            
            
          </div>
		  
		   <div class="col-xs-12 col-sm-6 col-md-6">
              <div class="form-group">
                <?php if($project_view == 'viewdata'){?>
						<?php echo lang('type') ?>
					<?php }?>
						<select <?php if($project_view=='viewdata'){?>disabled="disabled"<?php }?> name="type" class="form-control chosen-select"  id="status" required data-parsley-errors-container="#help-errors">
                        <option value="">
                            <?= $this->lang->line('select_type_help') ?> *
                        </option>
                        
						<option <?php if(isset($setup[0]['type']) && $setup[0]['type']=='Question'){ echo "selected='selected'";}?> value="Question"><?=lang('Question')?></option>
						<option <?php if(isset($setup[0]['type']) && $setup[0]['type']=='Feedback'){ echo "selected='selected'";}?> value="Feedback"><?=lang('Feedback')?></option>
						<option <?php if(isset($setup[0]['type']) && $setup[0]['type']=='bug'){ echo "selected='selected'";}?> value="bug"><?=lang('bug')?></option>
                       
                    </select>
                     <div id="help-errors"></div>   
              </div>
                       
               <div class="clr"></div>
			    
            
            
          </div>
		   <div class="col-xs-12 col-sm-12 col-md-12">
             <div class="form-group">
				<?php if($project_view == 'viewdata'){?>
				<?php echo lang('ticket_placeholder_desc') ?>
				<?php }?>
				<ul class="parsley-errors-list filled" id="termsError" >
				  <li class="parsley-required">This value is required.</li>
				</ul>
				<?php //echo $project_view;?>
				<textarea id="help_desc"  <?php if($project_view=='viewdata'){?>disabled="disabled"<?php }?> class="form-control" rows="4" placeholder="<?php echo lang('ticket_placeholder_desc') ?>" name="help_desc" ><?php echo!empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?><?php if(isset($ticket_data[0]['help_desc'])){?><?php echo $ticket_data[0]['help_desc'];?><?php } ?>
	</textarea>
			  </div>
               <div class="clr"></div>
			    
            
            
          </div>
	
              <div class="clr"></div>
     
        
      </div>
     <div class="modal-footer">
				<?php if($project_view != 'viewdata') { ?>
                <div class="text-center">
                    <input type="submit" class="btn btn-green" name="submit_btn" id="submit_btn" value="<?php echo lang('submit'); ?>" />
				 
					 </div>
				<?php }?>
                <div class="clr"> </div>
            </div>
    </div>
	</form>
  </div>

<!--modal-->
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Uploads</h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

	$('#crm_notify').bootstrapToggle();
	$('#pm_notify').bootstrapToggle();
	$('#support_notify').bootstrapToggle();
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
            .datepicker({autoclose: true
            });

</script>
<script>
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
		
		$("#termsError").css("display", "none");
		$('#from-model').parsley();
		$('#help_desc').summernote({
		  height: 150,   //set editable area's height
			codemirror: { // codemirror options
			  theme: 'monokai'
			}
		   });
		   
		    $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
				 $(".close").trigger("click");
				BootstrapDialog.show({ message:'<?php echo lang('thankyou');?>' });
                $('input[type="submit"]').prop('disabled', true);
                //$('#from-model').submit();
				
				$.ajax({
                url: $('#from-model').attr('action'),
                type: 'POST',
                data: $('#from-model').serialize(),
                success: function(result) {
					//alert(result);
                    // ... Process the result ...
                }
				
            });
			return false;
            }
        });
		   
		   $('body').delegate('#from-model', 'submit', function () {

				 var code = $('#help_desc').code();
				
			   if(code.length > 0) {

				$("#termsError").css("display", "none");
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
 $('#due_date').datepicker({autoclose: true,startDate:new Date()});

</script>
<script>
    /* image upload */

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg ="<?php echo lang('delete_request_item');?>";

        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                        $('#' + divId).remove();
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
                    var delete_meg ="<?php echo lang('fail_file_upload');?>";
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

            xhr[rand].onreadystatechange = function (oEvent) {
                var img = xhr[rand].response;
                var url = '<?php echo base_url(); ?>';
                if (xhr[rand].readyState === 4) {
                    var filetype = img.split(".")[1];
                    if (xhr[rand].status === 200) {
                        var template = '<div class="eachImage" id="' + rand + '">';
                        var randtest = 'delete_row("' + rand + '")';
                        template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                        if (filetype == 'jpg' || filetype == 'jpeg' || filetype == 'png' || filetype == 'gif') {
                            template += '<span class="preview" id="' + rand + '"><img src="' + src + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                        } else {
                            template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + filetype + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                        }
                        template += '<input type="hidden" name="fileToUpload[]" value="' + img + '">';
                        template += '</span>';
                        $("#dragAndDropFiles").append(template);
                    }
                }
            };

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
    function showimagepreview(input)
    {
        console.log(input);
        var url = '<?php echo base_url();?>';
        $.each(input.files, function(a,b){
            var rand = Math.floor((Math.random()*100000)+3);
            var arr1 = b.name.split('.');
            var arr= arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function(e) {
                var template = '<div class="eachImage" id="'+rand+'">';
                var randtest = 'delete_row("' +rand+ '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick='+randtest+'>×</a>';
                if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif'){
                    template += '<span class="preview" id="'+rand+'"><img src="'+e.target.result+'"><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                }else{
                    template += '<span class="preview" id="'+rand+'"><div><img src="'+url+'/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="'+b.name+'">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

//           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size/20480;
        //alert(maximum);
    }
    function delete_row(rand) {
        jQuery('#' + rand).remove();

    }
</script>