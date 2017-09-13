 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($readonly)) {

    $disable = $readonly['disabled'];
} else {
    $disable = "";
}

if ($project_view != 'viewdata') {
    $formAction = 'saveTicketData';
} else {
    $formAction = '';
}
if ($project_view == 'editdata') {

    $formAction = 'updateTicketData';
} else {
    $formAction = '';
}
if ($project_view == 'Ticket') {
    $formAction = 'saveTicketData';
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
        <input type="hidden" name="update_id" id="update_id" <?php if (isset($id)) { ?>value="<?php echo $id; ?><?php } ?>"/>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>
                        <?php if ($project_view == 'viewdata') { ?>
                            <?php echo lang('create_new_ticket') ?>
                        <?php } elseif ($project_view == 'Ticket/updatedata') { ?>
                            <?php echo lang('edit_ticket') ?>
                        <?php } elseif ($project_view == 'Ticket') { ?>
                            <?php echo lang('create_new_ticket') ?>
                        <?php } ?>
                    </b></h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 col-sm-3 col-md-3 mb15"> 
                    <!--modal left side box starts-->
                    <div class="bd-modalleft-box">
                        <h2 class="title-2 text-red"><?php echo lang('please_note') ?></h2>
                        <div class="form-group">
                            <label><?php echo lang('sup_con') ?></label>
                            <label class="text-red"><?php echo lang('no') ?></label>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('no') ?></label>
                            <label class="text-red">01/20/16</label>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('war_date_until') ?></label>
                            <label class="text-red">01/20/16</label>
                        </div>
                        <div>
                            <ul class="tasklist">
                                <?php foreach ($left_ticket_data as $tkdata) { ?>
                                    <li>
                                        <h5> <span ><?php echo lang('ticket') ?> #<?php echo $tkdata['ticket_subject']; ?> </span> </br>
                                            <span><?php echo lang('reported') ?> <?php echo $tkdata['created_date']; ?></span> </br>
                                            <b><?php echo $tkdata['ticket_desc']; ?></b></h5>
                                        <span class="pull-left pad-r6"><img alt="" src="images/mark-icon.png"></span> <b><?php echo lang('res_agent') ?></b> </br>
                                        <b><?php echo $tkdata['contact_name']; ?></b>
                                        <div class=" bd-success-status  bd-status"><?php echo $tkdata['status_name']; ?></div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!--modal left side box ends--> 
                </div>
                <div class="col-xs-12 col-sm-9 col-md-9">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <?php if ($project_view == 'viewdata') { ?>
                                    <?php echo lang('client_name') ?>
                                <?php } ?>
                                <?php
                                //$options = array('1'=>"Super Admin",'2'=>"Admin");
                                //$options = array(''=>"Select Client");
                                $options1 = array();
                                $options2 = array();
                                foreach ($client_data as $key => $value) {
                                    array_push($options1, $value['prospect_id']);
                                    array_push($options2, $value['prospect_name']);
                                }
                                $frst_option = lang('select_client');
                                $options = array_combine($options1, $options2);
                                //array_unshift($options , 'Select Client');
                                $name = "client_id";
                                if ($formAction == "saveTicketData") {
                                    $selected = 0;
                                } else {

                                    $selected = $ticket_data[0]['client_id'];
                                }

                                echo dropdown($name, $options, $selected, $disable, $frst_option);
                                ?>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <?php if ($project_view == 'viewdata') { ?>
                                    <?php echo lang('contact_name') ?>
                                <?php } ?>
                                <select data-placeholder="<?php echo lang('contact_name') ?>" <?php if ($project_view == 'viewdata') { ?>disabled='disabled'<?php } ?> multiple="true" name="contact_id[]" id="contact_id" class="chosen-select" tabindex="20">
                                    <?php if (isset($contact_data) && count($contact_data) > 0) { ?>

                                        <?php
                                        foreach ($contact_data as $key => $value) {
                                            $final_contact_data = array();
                                            //$final_contact_data=explode(',',$contact_selected_users[0]);
                                            ?>
                                            <option 
                                            <?php
                                            if (!empty($contact_selected_users) && in_array($value['contact_id'], $contact_selected_users)) {
                                                echo 'selected="selected"';
                                            }
                                            ?>
                                                value="<?php echo $value['contact_id']; ?>"><?php echo $value['contact_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="row">
                        <div class="form-group col-xs-6 col-lg-6 col-sm-6 col-md-6">
                            <?php if ($project_view == 'viewdata') { ?>
                                <?php echo lang('ticket_sub') ?>
                            <?php } ?>
                            <input type="text"   class="form-control" placeholder="<?php echo lang('ticket_sub') ?> *" id="ticket_subject" name="ticket_subject"  required <?php if (isset($ticket_data[0]['ticket_subject'])) { ?>   value="<?php echo $ticket_data[0]['ticket_subject']; ?>" <?php } if ($project_view == 'viewdata') { ?>disabled="disabled"<?php } ?>>
                            <span id="cost_name_error" class="alert-danger"></span> </div>
                        <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                            <?php if ($project_view == 'viewdata') { ?>
                                <?php echo lang('CR_TYPE') ?>
                            <?php } ?>
                            <?php
                            //$options = array('1'=>"Super Admin",'2'=>"Admin");
                            $options1 = array();
                            $options2 = array();
                            foreach ($type_data as $key => $value) {
                                array_push($options1, $value['support_type_id']);
                                array_push($options2, $value['type']);
                            }
                            $frst_option = lang("type");
                            $options = array_combine($options1, $options2);
                            //array_unshift($options , 'Select Product');							
                            $name = "type";
                            if ($formAction == "saveTicketData") {
                                $selected = 0;
                            } else {
                                $selected = $ticket_data[0]['type'];
                            }
                            echo dropdown($name, $options, $selected, $disable, $frst_option);
                            ?>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-xs-6 col-lg-6 col-sm-6 col-md-6">
                                <?php if ($project_view == 'viewdata') { ?>
                                    <?php echo lang('product_name') ?>
                                <?php } ?>
                                <?php
//$options = array('1'=>"Super Admin",'2'=>"Admin");
                                $options1 = array();
                                $options2 = array();
                                foreach ($product_data as $key => $value) {
                                    array_push($options1, $value['product_id']);
                                    array_push($options2, $value['product_name']);
                                }
                                $frst_option = lang("select_product");
                                $options = array_combine($options1, $options2);
//array_unshift($options , 'Select Product');							
                                $name = "product_id";
                                if ($formAction == "saveTicketData") {
                                    $selected = 0;
                                } else {
                                    $selected = $ticket_data[0]['product_id'];
                                }
                                echo dropdown($name, $options, $selected, $disable, $frst_option);
                                ?>
                                <span id="cost_type_error" class="alert-danger"></span> </div>
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <?php if ($project_view == 'viewdata') { ?>
                                    <?php echo lang('status') ?>
                                <?php } ?>
                                <?php
                                //$options = array('1'=>"Super Admin",'2'=>"Admin");
                                $options1 = array();
                                $options2 = array();
                                foreach ($status_data as $key => $value) {
                                    array_push($options1, $value['status_id']);
                                    array_push($options2, $value['status_name']);
                                }
                                $frst_option = lang("select_status");
                                $options = array_combine($options1, $options2);
                                //array_unshift($options , 'Select Product');							
                                $name = "status";
                                if ($formAction == "saveTicketData") {
                                    $selected = 0;
                                } else {
                                    $selected = $ticket_data[0]['status'];
                                }
                                echo dropdown($name, $options, $selected, $disable, $frst_option);
                                ?>
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <input type="text"  class="form-control" placeholder="<?php echo lang('due_date') ?> *" <?php if ($project_view != 'viewdata') { ?>id="due_date"<?php } ?> name="due_date" required="" <?php if (isset($ticket_data[0]['ticket_subject'])) { ?>   value="<?php echo configDateTime($ticket_data[0]['due_date']); ?>" <?php } if ($project_view == 'viewdata') { ?>readonly<?php } ?>>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                                <?php if ($project_view == 'viewdata') { ?>
                                    <?php echo lang('priority') ?>
                                <?php } ?>
                                <?php
//$options = array('1'=>"Super Admin",'2'=>"Admin");
                                $options1 = array();
                                $options2 = array();
                                foreach ($priority_data as $key => $value) {
                                    array_push($options1, $value['support_priority_id']);
                                    array_push($options2, $value['priority']);
                                }
                                $frst_option = lang("select_priority");
                                $options = array_combine($options1, $options2);
//array_unshift($options , 'Select Product');							
                                $name = "priority";
                                if ($formAction == "saveTicketData") {
                                    $selected = 0;
                                } else {
                                    $selected = $ticket_data[0]['priority'];
                                }
                                echo dropdown($name, $options, $selected, $disable, $frst_option);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <br/>
                    <div class="form-group">
                        <?php if ($project_view == 'viewdata') { ?>
                            <?php echo lang('ticket_placeholder_desc') ?>
                        <?php } ?>
                        <ul class="parsley-errors-list filled" id="termsError" >
                            <li class="parsley-required"><?php echo lang('val_req') ?></li>
                        </ul>
                        <?php //echo $project_view;  ?>
                        <textarea id="ticket_desc"  <?php if ($project_view == 'viewdata') { ?>disabled="disabled"<?php } ?> class="form-control" rows="4" placeholder="<?php echo lang('ticket_placeholder_desc') ?>" name="ticket_desc" ><?php echo!empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?><?php if (isset($ticket_data[0]['ticket_desc'])) { ?><?php echo $ticket_data[0]['ticket_desc']; ?><?php } ?>
                        </textarea>
                    </div>
                    <div class="clr"></div>
                    <br/>
                    <div class="">
                        <div class="col-sm-12 col-md-6 form-group">
                            <div class="mediaGalleryDiv">
                                <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
                                <div class="mediaGalleryImg">

                                </div>

                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div id="dragAndDropFiles" class="uploadArea bd-dragimage">

                                <div class="image_part" style="height: 100px;">
                                    <label name="fileUpload[]">
                                        <h1 style="top: -162px;">
                                            <i class="fa fa-cloud-upload"></i>
                                            <?= lang('DROP_IMAGES_HERE') ?>
                                        </h1>
                                        <input type="file" <?php if ($project_view == 'viewdata') { ?>disabled="disabled"<?php } ?>  onchange="showimagepreview(this)" name="fileUpload[]" style="display: none" id="upl" multiple />
                                    </label>
                                </div>
                                <?php
                                if (!empty($img_data)) {
                                    if (count($img_data) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                        $i = 15482564;
                                        foreach ($img_data as $image) {
                                            $path = $image['file_path'];
                                            $name = $image['file_name'];
                                            $arr_list = explode('.', $name);
                                            $arr = $arr_list[1];
                                            if (file_exists($path . '/' . $name)) {
                                                ?>
                                                <div id="img_<?php echo $image['file_id']; ?>" class="eachImage">
                                                    <?php if ($project_view != 'viewdata') { ?>
                                                        <a class="btn delimg remove_drag_img" href="javascript:;" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['file_id']; ?>" data-path="<?php echo $path; ?>">x</a>
                                                    <?php } ?>
                                                    <span id="<?php echo $i; ?>" class="preview"> <a href='<?php echo base_url('Ticket/download/' . $image['file_id']); ?>' target="_blank">
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
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="text-center form-group">
                        <h2 class="title-2 ">-<?php echo lang('ass_to');?>-</h2>
                    </div>
                    <div class="row ">
						 <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-group">
							 <select class="form-control" id="team_id" name="team_id">
							 <option value=""><?php echo lang('select_team');?></option>
							 <option value="0" <?php if(isset($ticket_data[0]['suport_team'])&& $ticket_data[0]['suport_team']=='0'){echo "selected='selected'";}?>><?php echo lang('Unclassified');?></option>
							 <?php foreach ($team_data as $key => $value) {
								  if ($formAction == "saveTicketData") {
                                $selected = 0;
                                }
								 elseif($value['team_id']==$ticket_data[0]['suport_team']){
									 
									 $selected='selected="selected"';
									 
									 }
								 
									 
								 
								 ?>
								 
								 <option <?php echo $selected;?> value="<?php echo $value['team_id'];?>"><?php echo $value['team_name'];?></option>
								 
							 <?php }?>
							 </select>
							 </div>
                       <!-- <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-group">
                            <?php if ($project_view == 'viewdata') { ?>
                                <?php echo lang('sup_team'); ?>
                            <?php } ?>
                            <?php //pr($team_member_id);die('aa');?>
                            <?php
//$options = array('1'=>"Super Admin",'2'=>"Admin");
							
                            $options1 = array();
                            $options2 = array();

                            foreach ($team_data as $key => $value) {
                                //pr($team_data);die();
                                array_push($options1, $value['team_id']);
                                array_push($options2, $value['team_name']);
                            }
                            
                            if($project_view == 'Ticket'){
								$select_first='sel_team';
							}else{
								$select_first='uncl_team';
								}
                            $first_option = ("Select Team");
                            $second_option = ("Unclassified");
                              

                            $options = array_combine($options1, $options2);
//array_unshift($options , 'Select Team');							
                            $name = "team_id";

                            if ($formAction == "saveTicketData") {
                                $selected = 0;
                            } else {
                                $selected = $ticket_data[0]['suport_team'];
                            }
                           
                            echo dropdown($name, $options, $selected, $disable, $first_option,$second_option,$select_first);
                               
                              
                            ?>
                        </div>-->
<?php //$numItems = count($team_member_data[0]);
									//echo $numItems;?>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-group" id="userList">

                            <select data-placeholder="<?php echo lang('sup_team_mem') ?>" multiple="true" name="team_member_id[]" id="team_member_id" class="chosen-select" tabindex="20">

                                <?php if ($project_view == 'Ticket/updatedata') {
                                    ?> 
                                    <?php foreach ($team_member_data1 as $row) { ?>     

                                        <option value="<?php echo $row['login_id']; ?>" <?php
                                foreach ($team_member_data[0] as $row1) {
									
									
									//pr($row1);
									
                                    if ($row1['login_id'] == $row['login_id']) {
                                                ?> selected <?php
                                            }
                                        }
                                        ?> ><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></option>

                                            <?php }
                                        }
                                        ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12 col-sm-12 col-md-6 col-xs-6 form-group">
                            <div class="btn-group btn-toggle">
                                <div class=" pull-left">
                                    <input  data-toggle="toggle" <?php
                                        if ($project_view == 'viewdata') {
                                            echo "disabled='disabled'";
                                        }
                                        ?> <?php if (isset($ticket_data[0]['email_notification'])) { ?><?php
                                        if ($ticket_data[0]['email_notification'] == '1') {
                                            echo "checked='checked'";
                                        }
                                        ?><?php } ?> data-onstyle="success" type="checkbox" id="email_notification" name="email_notification" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"
>
                                   
                                </div>
                                 <div class="col-sm-6"><label for="client_notification"> <?php echo lang('client_notification'); ?> </label></div>
                                <div class="clr"> </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xs-12 col-sm-12 col-md-6 col-xs-6 form-group">
                            <div class="btn-group btn-toggle">
                                <div class=" pull-left">
                                    <input  data-toggle="toggle" <?php
                                            if ($project_view == 'viewdata') {
                                                echo "disabled='disabled'";
                                            }
                                    ?> <?php if (isset($ticket_data[0]['agent_notify'])) { ?><?php
                                        if ($ticket_data[0]['agent_notify'] == '1') {
                                            echo "checked='checked'";
                                        }
                                        ?><?php } ?>  data-onstyle="success" type="checkbox" id="agent_notify" name="agent_notify" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"
>
                                    <label for="notify_agent"> <?php echo lang('notify_agent'); ?> </label>
                                </div>
                                <div class="clr"> </div>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="modal-footer">
                <?php if ($project_view != 'viewdata') { ?>
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?php
                    if ($project_view == 'Ticket') {
                        echo lang('create_ticket');
                    } elseif ($project_view == 'Ticket/updatedata') {

                        echo lang('update_ticket');
                    }
                    ?>" />
                    </div>
                <?php } ?>
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
		//alert('aa');
		var enjoyhint_instance = new EnjoyHint({});
		var enjoyhint_script_steps = [{
			'next .bd-modalleft-box' : 'You can see here NOTE'
			
		}];
enjoyhint_instance.set(enjoyhint_script_steps);
enjoyhint_instance.run();
        $("#termsError").css("display", "none");
        $('#from-model').parsley();
        $('#ticket_desc').summernote({
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            }
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
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
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
        var url = '<?php echo base_url(); ?>';
        $.each(input.files, function (a, b) {
            var rand = Math.floor((Math.random() * 100000) + 3);
            var arr1 = b.name.split('.');
            var arr = arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function (e) {
                var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                var randtest = 'delete_row("' + rand + '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                    template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                } else {
                    template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

//           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size / 20480;
        //alert(maximum);
    }
    function delete_row(rand) {
        jQuery('#' + rand).remove();

    }
</script>
