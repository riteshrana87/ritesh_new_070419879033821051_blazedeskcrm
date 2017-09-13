<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if(isset($readonly)){
	
	$disable = $readonly['disabled'];
}else{
	$disable = "";
}

if($project_view!='viewdata'){
$formAction = 'saveTicketData';
}else{
	$formAction='';
	
}
if($project_view=='editdata'){

$formAction = 'updateTicketData';
}else{
	$formAction='';
	
}
if($project_view=='Ticket'){
$formAction = 'saveTicketData';
}else{
	$formAction='';
	
}

$formPath = $project_view . '/' . $formAction;
//echo $project_view;die;

/*pr($ticket_data);
die('aa');*/
?>
<!--modal-->

<div class="modal-dialog modal-lg" role="document">
  <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
    <input type="hidden" name="update_id" id="update_id" <?php if(isset($id)) { ?>value="<?php echo $id;?><?php }?>"/>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><b>View ticket</b></h4>
      </div>
      <div class="modal-body">
        <div class="col-xs-12 col-sm-3 col-md-3"> 
          <!--modal left side box starts-->
          <div class="bd-modalleft-box">
            <h2 class="title-2 text-red">Please note</h2>
            <div class="form-group">
              <label>Support contract:</label>
              <label class="text-red">NO</label>
            </div>
            <div class="form-group">
              <label>Selected product delivered on:</label>
              <label class="text-red">01/20/16</label>
            </div>
            <div class="form-group">
              <label>Warranty date until:</label>
              <label class="text-red">01/20/16</label>
            </div>
            <div>
              <ul class="tasklist">
                <?php foreach($left_ticket_data as $tkdata){ ?>
                <li>
                  <h5> <span >Ticket #<?php echo $tkdata['ticket_subject'];?> </span> </br>
				  <span>Reported: <?php echo $tkdata['created_date'];?></span></br>
                    <b><?php echo $tkdata['ticket_desc'];?></b></h5>
                  <span class="pull-left pad-r6"><img alt="" src="images/mark-icon.png"></span> <b>Responsible agent:</b> </br>
                  <b><?php echo $tkdata['contact_name'];?></b>
                  <div class=" bd-success-status  bd-status"><?php echo $tkdata['status_name'];?></div>
                </li>
                <?php }?>
              </ul>
            </div>
          </div>
          <!--modal left side box ends--> 
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9">
          <div class="form-group">
            <div class="row">
              <div class="col-lg-6 col-sm-6 col-md-6 ">
                <?php if($project_view == 'viewdata'){?>
                <label><?php echo lang('client_name') ?></label>
                <?php } ?>
				<?php if (!empty($client_data) && count($client_data) > 0) { ?>
                <?php
				//pr($ticket_data[0]['client_id']);
				
					 		 //$options = array('1'=>"Super Admin",'2'=>"Admin");
							//$options = array(''=>"Select Client");
					 		$options1 = array();	
					 		$options2 = array();		 		
						 	foreach ($client_data as $key => $value){
								//pr($value);
							   array_push($options1, $value['prospect_id']);
							   array_push($options2, $value['prospect_name']);
							
							}
							if($ticket_data[0]['client_id']=='0')
							{
								$frst_option=("N/A");
							}else{
							$frst_option=("Select Client");
							}
							$options = array_combine($options1, $options2);
							//array_unshift($options , 'Select Client');
					 		 $name = "client_id";
					 		 if($formAction == "saveTicketData"){
					 		 	$selected = 0;
								echo "N/A";
								
					 		 }else{
								
					 		 	$selected = $ticket_data[0]['client_id'];
					 		 	
					 		 }		 		 
							 
					 		 echo dropdown($name,$options,$selected,$disable,$frst_option);
							 
					 	?>
						<?php } else {
							echo "N/A";
							}?>
              </div>
              <div class="col-lg-6 col-sm-6 col-md-6">
                <?php if($project_view == 'viewdata'){?>
                <label><?php echo lang('contact_name') ?>:</label>
                <?php }?>
                  <?php if(!empty($contact_selected_users)){?>
                            <?php if (!empty($contact_data) && count($contact_data) > 0) { ?>

                            <?php
                            $blnkFlag = 0;
                            $tmp = '';
                            ?>
                                <?php foreach ($contact_data as $key => $value) { ?>
                            <?php if(!empty($contact_selected_users) && in_array($value['contact_id'],$contact_selected_users)){$tmp .= $value['contact_name'].',';} else { $blnkFlag++; } ?>
                                <?php if($blnkFlag == count($contact_selected_users)){  }?>
                                <?php }
                            echo rtrim($tmp,',');
                            ?>
                            <?php }  ?>
                    <?php }else{
                        echo "N/A";
                        ?>
                    <?php }?>
                
              </div>
            </div>
          </div>
          <div class="clr"></div>
          <div class="form-group">
            <div class="row"><div class="col-lg-6">
              <?php if($project_view == 'viewdata'){?>
              <label><?php echo lang('ticket_sub') ?></label>
              <?php }?>
              <input type="text"   class="form-control" placeholder="<?php echo lang('ticket_sub') ?>" id="ticket_subject" name="ticket_subject"  required <?php if(isset($ticket_data[0]['ticket_subject'])) { ?>   value="<?php echo $ticket_data[0]['ticket_subject'];?>" <?php } if($project_view=='viewdata'){?>disabled="disabled"<?php }?>>
              <span id="cost_name_error" class="alert-danger"></span></div>
            <div class="col-lg-6 col-sm-6 col-md-6">
              <?php if($project_view == 'viewdata'){?>
              <label><?php echo lang('product_name') ?></label>
              <?php }?>
              <?php
					 		 //$options = array('1'=>"Super Admin",'2'=>"Admin");
					 		$options1 = array();	
					 		$options2 = array();		 		
						 	foreach ($product_data as $key => $value){
							   array_push($options1, $value['product_id']);
							   array_push($options2, $value['product_name']);
							}
							$frst_option=("Select Product");
							$options = array_combine($options1, $options2);	
							//array_unshift($options , 'Select Product');							
					 		 $name = "product_id";
							 if($ticket_data[0]['product_id']=='0')
							{
								$frst_option=("N/A");
							}else{
							$frst_option=("Select Client");
							}
					 		 if($formAction == "saveTicketData"){
					 		 	$selected = 0; 
					 		 }else{
					 		 	$selected = $ticket_data[0]['product_id'];
					 		 	
					 		 }		 		 
					 		 echo dropdown($name,$options,$selected,$disable,$frst_option); 
					 	?>
              <span id="cost_type_error" class="alert-danger"></span> </div>
            <div class="clr"></div></div>
          </div>
          <div class="row">
            <div class="form-group">
              <div class="">
                <div class="col-lg-6 col-sm-6 col-md-6">
                  <?php if($project_view == 'viewdata'){?>
                  <label><?php echo lang('CR_TYPE') ?></label>
                  <?php }?>
                  <?php
				  //pr($ticket_data[0]['type']);
					 		 //$options = array('1'=>"Super Admin",'2'=>"Admin");
					 		$options1 = array();	
					 		$options2 = array();		 		
						 	foreach ($type_data as $key => $value){
							   array_push($options1, $value['support_type_id']);
							   array_push($options2, $value['type']);
							}
							$frst_option=("Select Type");
							$options = array_combine($options1, $options2);	
							//array_unshift($options , 'Select Product');							
					 		 $name = "type";
							 if($ticket_data[0]['type']=='0' || $ticket_data[0]['type']=='')
							{
								$frst_option=("N/A");
							}else{
							$frst_option=("Select Client");
							}
					 		 if($formAction == "saveTicketData"){
					 		 	$selected = 0; 
					 		 }else{
					 		 	$selected = $ticket_data[0]['type'];
					 		 	
					 		 }		 		 
					 		 echo dropdown($name,$options,$selected,$disable,$frst_option); 
					 	?>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6">
                  <?php if($project_view == 'viewdata'){?>
                  <label><?php echo lang('status') ?></label>
                  <?php }?>
                  <?php
					 		 //$options = array('1'=>"Super Admin",'2'=>"Admin");
					 		$options1 = array();	
					 		$options2 = array();		 		
						 	foreach ($status_data as $key => $value){
							   array_push($options1, $value['status_id']);
							   array_push($options2, $value['status_name']);
							}
							$frst_option=("Select Status");
							$options = array_combine($options1, $options2);	
							//array_unshift($options , 'Select Product');							
					 		 $name = "status";
							if($ticket_data[0]['status']=='0' || $ticket_data[0]['status']=='')
							{
								$frst_option=("N/A");
							}else{
							$frst_option=("Select Client");
							}
					 		 if($formAction == "saveTicketData"){
					 		 	$selected = 0; 
					 		 }else{
					 		 	$selected = $ticket_data[0]['status'];
					 		 	
					 		 }		 		 
					 		 echo dropdown($name,$options,$selected,$disable,$frst_option); 
					 	?>
                </div>
                <div class="clr"></div>
              </div>
            </div>
         
          </div>
        
        
          <div class="row">
            <div class="form-group">
              <div class="col-lg-6 col-sm-6 col-md-6">
                <div class="form-group">
                <label>Date:</label>
                <?php if($project_view == 'viewdata'){?>
                <span><?php echo configDateTime($ticket_data[0]['due_date']);?></span>
                 
                <?php } else { ?>
                 <input type="text"  class="form-control" placeholder="<?php echo lang('due_date') ?>" <?php if($project_view!='viewdata'){?>id="due_date"<?php }?> name="due_date" required="" <?php if(isset($ticket_data[0]['ticket_subject'])) { ?>   value="<?php echo configDateTime($ticket_data[0]['due_date']);?>" <?php } ?>>
                <?php } ?>
                </div>
              </div>
             
              <div class="col-lg-6 col-sm-6 col-md-6">
                <?php if($project_view == 'viewdata'){?>
                <label> <?php echo lang('priority') ?></label>
                <?php }?>
                <?php
					 		 //$options = array('1'=>"Super Admin",'2'=>"Admin");
					 		$options1 = array();	
					 		$options2 = array();		 		
						 	foreach ($priority_data as $key => $value){
							   array_push($options1, $value['support_priority_id']);
							   array_push($options2, $value['priority']);
							}
							$frst_option=("Select Priority");
							$options = array_combine($options1, $options2);	
							//array_unshift($options , 'Select Product');							
					 		 $name = "priority";
							 if($ticket_data[0]['priority']=='0' || $ticket_data[0]['priority']=='')
							{
								$frst_option=("N/A");
							}else{
							$frst_option=("Select Client");
							}
					 		 if($formAction == "saveTicketData"){
					 		 	$selected = 0; 
					 		 }else{
					 		 	$selected = $ticket_data[0]['priority'];
					 		 	
					 		 }		 		 
					 		 echo dropdown($name,$options,$selected,$disable,$frst_option); 
					 	?>
              </div>
            </div>
          </div>
        
          <div class="form-group">
            <?php if($project_view == 'viewdata'){?>
              <label><?php echo lang('ticket_placeholder_desc') ?></label>
            <?php }?>
               <?php if($project_view == 'viewdata'){?>
              <?php echo $ticket_data[0]['ticket_desc'];?>
               <?php } else {?>
            <ul class="parsley-errors-list filled" id="termsError" >
              <li class="parsley-required">This value is required.</li>
            </ul>
            <textarea id="ticket_desc"  class="form-control" rows="4" placeholder="<?php echo lang('ticket_placeholder_desc') ?>" name="ticket_desc" ><?php echo!empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?><?php if(isset($ticket_data[0]['ticket_desc'])){?><?php echo $ticket_data[0]['ticket_desc'];?><?php } ?>
</textarea>
              <?php } ?>
          </div>
        <?php if (!empty($img_data)){ ?>
          <div class="form-group">
            <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
              
                  <?php if($project_view=='viewdata'){?>
               <label name="fileUpload[]">
                   <input type="file"  onchange="showimagepreview(this)" name="fileUpload[]" style="display: none" id="upl" multiple readonly/>
                </label>
                  <?php } else{ ?>
                <div class="image_part" style="height: 100px;">
                   <label name="fileUpload[]">
                <h1 style="top: -162px;">
                  <?= lang('DROP_IMAGES_HERE') ?>
                </h1>
                <input type="file"  onchange="showimagepreview(this)" name="fileUpload[]" style="display: none" id="upl" multiple />
                </label>
                        </div>
                  <?php } ?>
          
              <?php
                if (!empty($img_data)){
                        if (count($img_data) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                            $i = 15482564;
                            foreach ($img_data as $image) {
                                $path = $image['file_path'];
                                $name = $image['file_name'];
                                $arr_list = explode('.', $name);
                                $arr = $arr_list[1];
                                if (file_exists($path . '/' . $name)) { ?>
              <div id="img_<?php echo $image['file_id']; ?>" class="eachImage">
                <?php if($project_view!='viewdata'){?>
                <a class="btn delimg remove_drag_img" href="javascript:;" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['file_id']; ?>" data-path="<?php echo $path; ?>">x</a>
                <?php }?>
                <span id="<?php echo $i; ?>" class="preview"> <a   href='<?php echo base_url('Ticket/download/' . $image['file_id']); ?>' target="_blank">
                <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                <img src="<?= base_url($path . '/' . $name); ?>"  width="75"/>
                <?php } else { ?>
                <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/>
                  <p class="img_show"><?php echo $arr; ?></p>
                </div>
                <?php } ?>
                </a>
                <p class="img_name"><?php echo $name; ?></p>
                <span class="overlay" style="display: none;"> <span class="updone">100%</span></span> </span> </div>
              <?php } ?>
              <?php
                                $i++;
                            }
                            ?>
              <div id="deletedImagesDiv"></div>
              <?php }  }?>
            </div>
          </div>
        <?php } else { ?>
           <div class="form-group"></div>
        <?php } ?>
          
          <div class="text-center form-group">
            <h2 class="title-2 ">-Assigned to-</h2>
          </div>
          <div class="row form-group">
            <div class="col-lg-6 col-sm-6 col-md-6">
              <?php if($project_view == 'viewdata'){?>
              <label><?php echo lang('sup_team'); ?></label>
              <?php }?>
              <?php
					 		 //$options = array('1'=>"Super Admin",'2'=>"Admin");
					 		$options1 = array();	
					 		$options2 = array();		 		
						 	foreach ($team_data as $key => $value){
								//pr($team_data);die();
							   array_push($options1, $value['team_id']);
							   array_push($options2, $value['team_name']);
							}
							$frst_option=("Select Team");
							$options = array_combine($options1, $options2);	
							//array_unshift($options , 'Select Team');							
					 		 $name = "team_id";
							  if($ticket_data[0]['suport_team']=='0' || $ticket_data[0]['suport_team']=='')
							{
								$frst_option=("N/A");
							}else{
							$frst_option=("Select Client");
							}
					 		 if($formAction == "saveTicketData"){
					 		 	$selected = 0; 
					 		 }else{
					 		 	$selected = $ticket_data[0]['suport_team'];
					 		 	
					 		 }		 		 
					 		 echo dropdown($name,$options,$selected,$disable,$frst_option); 
					 	?>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6">
              <?php if($project_view == 'viewdata'){?>
              <label><?php echo lang('sup_team_mem'); ?></label></br>
              <?php }?>
              <?php //echo $ticket_data[0]['support_user'];
					//pr($value['team_member_id']);die();
					$numItems = count($ticket_data_team);
					$i = 0;
					?>
					
				<?php
				if(!empty($ticket_data_team) && count($ticket_data_team)>0){
				foreach($ticket_data_team as $key=>$value){?>
					
					<?php echo $value['firstname'].' '.$value['lastname'];if(++$i !== $numItems) {
    echo ",";
  }?>
					
					<?php } } else { echo "N/A";}?>
            </div>
          </div>
         
          <div class="clr"></div>
        </div>
        <div class="clr"></div>
      </div>
      <div class="modal-footer">
        <?php if($project_view != 'viewdata') { ?>
        <div class="text-center">
          <input type="submit" class="btn btn-green" name="submit_btn" id="submit_btn" value="<?php if($project_view=='Ticket'){
				 echo lang('create_ticket');
				}
				elseif($project_view=='Ticket/updatedata'){
					
					echo lang('update_ticket');
				}
				
				 ?>" />
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
		$('#ticket_desc').summernote({
		  height: 150,   //set editable area's height
			codemirror: { // codemirror options
			  theme: 'monokai'
			}
		   });
 $('body').delegate('#from-model', 'submit', function () {

     var code = $('#ticket_desc').code(),
     filteredContent = $(code).text().replace(/\s+/g, '');

   if(filteredContent.length > 0) {

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
                        alert("File \"" + file.name + "\" is too big.Max allowed size is 20 MB.");
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
                    alert("Failed to compute file upload length");
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
        $('.upload_recent').remove();
        var url = '<?php echo base_url();?>';
        $.each(input.files, function(a,b){
            var rand = Math.floor((Math.random()*100000)+3);
            var arr1 = b.name.split('.');
            var arr= arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function(e) {
                var template = '<div class="eachImage upload_recent" id="'+rand+'">';
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
