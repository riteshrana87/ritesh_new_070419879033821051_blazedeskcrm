<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$path = base_url() . 'Contact/' . $form_action;
?>

<div class="modal-dialog modal-lg">
    <?php
    $attributes = array("name" => "form_schedule_meeting", "id" => "form_schedule_meeting", 'data-parsley-validate' => "");
    echo form_open_multipart($path, $attributes);
    ?>
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="set_label"><?php echo $modal_title; ?>
            </h4>
        </div>
        <div class="modal-body">
            <div class ="form-group row">
                <div class="col-sm-4 col-md-3 font-15em">
                    <?= $this->lang->line('MEETING_SUBJECT') ?> : *
                </div>
                <div class="col-sm-8 col-md-9">
                    <input type="text" class="form-control" required placeholder="<?= $this->lang->line('MEETING_SUBJECT') ?>" id="meeting_subject" name="meeting_subject"  value="<?php
                    if (!empty($editRecord[0]['meet_title'])) {
                        echo htmlentities($editRecord[0]['meet_title']);
                    }
                    ?>">
                </div>
            </div>

            <div class =" row">
                <div class="col-md-12 form-group">
                    <label><?= $this->lang->line('ADD_RECEIPENT') ?> : *</label>
                    <?php
                    if ($display_from == 'dashboard_salesoverview') {
                        $select_require = "required='true'";
                    } else {
                        $select_require = '';
                    }
                    ?>
                    <select name="contact_id[]" multiple="true" <?php echo $select_require; ?> class="chosen-select" data-placeholder="<?php echo lang('SELECT_MEETING_PARTICIPANT'); ?>" id="contact_id" data-parsley-errors-container="#company-errors">
                        <option value=""></option>
                    <?php
                    //1- Employee, 2-contact
                    $selected_contact = '';
                    $selected_contact_disable = '';
                    foreach ($meeting_particiapnts as $particiapnts) {
                        $val_participants = $particiapnts['user_id'] . "/" . $particiapnts['user_type'];
                        if ((in_array($val_participants, $edited_id)) || in_array($val_participants, $edit_participants)) {
                            $selected_contact = "selected='selected'";
                            if (in_array($val_participants, $edited_id)) {
                                $selected_contact_disable = "disabled='true'";
                            }
                        }
                        ?>
                            <option value="<?php echo $val_participants; ?>" <?php echo $selected_contact; ?> <?php echo $selected_contact_disable; ?>><?php echo $particiapnts['user_name']; ?></option>

                            <?php
                            $selected_contact = '';
                            $selected_contact_disable = '';
                        }
                        ?>
                    </select>
                    <div id="company-errors"></div>
                </div>
<div class="clr"></div>
                <div class = "col-xs-12 col-md-4 col-sm-4 form-group">
                    <label><?= $this->lang->line('MEETING_DATE') ?> : *</label>
                    <div class="input-group date" id="meeting_date">

                        <input type="text" data-date-format="yyyy-mm-dd" class="form-control" required placeholder="<?= $this->lang->line('MEETING_DATE') ?>" id="meeting_date" name="meeting_date"  value="<?php
                        if (!empty($editRecord[0]['meeting_date'])) {
                            echo date('Y-m-d', strtotime($editRecord[0]['meeting_date']));
                        }
                        ?>">
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                    </div>
                </div>

                <div class = "col-xs-12 col-md-4 col-sm-4 form-group ">
                    <label><?= $this->lang->line('MEETING_TIME') ?> : *</label>
                    <div class="input-group date" id="meeting_time">

                        <input type="text" class="form-control"  required placeholder="<?= $this->lang->line('MEETING_TIME') ?>" id="meeting_time" name="meeting_time"  value="<?php
                        if (!empty($editRecord[0]['meeting_time'])) {
                            echo date('H:i:s', strtotime($editRecord[0]['meeting_time']));
                        }
                        ?>">
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                    </div>
                </div>

                <div class = "col-xs-12 col-md-4 col-sm-4 form-group">
                    <label><?= $this->lang->line('MEETING_END_TIME') ?> : *</label>
                    <div class="input-group date" id="meeting_end_time">

                        <input type="text" class="form-control"  required placeholder="<?= $this->lang->line('MEETING_TIME') ?>" id="meeting_end_time" name="meeting_end_time"  value="<?php
                        if (!empty($editRecord[0]['meeting_end_time'])) {
                            echo date('H:i:s', strtotime($editRecord[0]['meeting_end_time']));
                        }
                        ?>">
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                    </div>
                </div>
                <div class="clr"></div>

            </div>

            <div class ="form-group row">
                <div class = "col-sm-4">
<?php
if (!empty($editRecord[0]['meeting_reminder'])) {

    $reminder_id = "first_time_show";
} else {
    $reminder_id = "first_time_hide";
}
?>
                    <div class="pad_top20">
                        <input <?php if (!empty($editRecord[0]['meeting_reminder'])) { ?>checked="checked"<?php } ?>  data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" class="event_reminder_tog" data-toggle="toggle" data-onstyle="success" type="checkbox"  id="event_reminder" name="event_reminder" onChange="toggle_show(<?php echo "'#" . $reminder_id . "'"; ?>, this)"/>
                        <label><?= $this->lang->line('reminder?') ?></label>
                    </div>
                </div>

                <div id="<?php echo $reminder_id; ?>">
                    <div class = "col-sm-4">
                        <label><?= $this->lang->line('REMINDER_DATE') ?> : *</label>
                        <div class="input-group date" id="reminder_date1">

                            <input type="text" data-parsley-errors-container="#reminder_date_errors" class="input-append date form_datetime form-control" required placeholder="<?= $this->lang->line('REMINDER_DATE') ?>" id="reminder_date" name="reminder_date" onkeydown="return false" value="<?php
                    if (!empty($editRecord[0]['reminder_date']) && $editRecord[0]['reminder_date'] != '0000-00-00') {
                        echo date('YYYY-m-d H:i', strtotime($editRecord[0]['reminder_date']));
                    }
                        ?>" >
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                        </div>
                        <span id="reminder_date_errors"></span>
                    </div>

                    <div class = "col-sm-4">
                        <label><?= $this->lang->line('REMINDER_TIME') ?> : *</label>
                        <div class="input-group date" id="reminder_time1">

                            <input type="text" data-parsley-errors-container="#reminder_time_errors" class="input-append date form_datetime form-control" required placeholder="<?= $this->lang->line('REMINDER_TIME') ?>" id="reminder_time" name="reminder_time" onkeydown="return false" value="<?php
                            if (!empty($editRecord[0]['reminder_time']) && $editRecord[0]['reminder_time'] != '0000-00-00') {
                                echo date('YYYY-m-d H:i', strtotime($editRecord[0]['reminder_time']));
                            }
?>" >
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                        </div>
                        <span id="reminder_time_errors"></span>
                    </div>

                </div>

            </div>
            
        <div class="clr"></div>

        <div class="form-group row">
            <div class="col-sm-12">
                <label><?= $this->lang->line('ADD_ADDITIONAL_RECEIPENT') ?> : </label>

                <textarea class="form-control" rows="1" name="additional_receipent" id="additional_receipent" placeholder="<?php echo lang('EX_COMMA_SEPERATED_EMAIL'); ?>"><?= !empty($editRecord[0]['additiona_receipent_email']) ? $editRecord[0]['additiona_receipent_email'] : '' ?></textarea>
                <label id="QtChar"> </label>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-sm-12">
                <label><?= $this->lang->line('MEETING_DESCRIPTION') ?> : *</label>
                <!-- onkeyup="checkTextareaWord()"-->
                <textarea class="form-control" required rows="2" name="meeting_description" id="meeting_description" placeholder="<?php echo lang('MEETING_DESCRIPTION'); ?>"><?= !empty($editRecord[0]['meeting_description']) ? $editRecord[0]['meeting_description'] : '' ?></textarea>
                <label id="QtChar"> </label>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-xs-12 col-md-6">
                <label><?php echo lang('documents');?></label>
                <div class="form-group">
                    <div class="mediaGalleryDiv">

                        <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
                        <div class="mediaGalleryImg">

                        </div> 

                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xs-12 col-md-6 no-right-pad col-lg-5">
                <div id="dragAndDropFiles" class="uploadArea uploadarea-sm bd-dragimage">
                    <div class="image_part">
                        <label name="cost_files[]">
                            <h1 style="top: -162px;">
                                <i class="fa fa-cloud-upload"></i>
<?= lang('DROP_IMAGES_HERE') ?>
                            </h1>
                            <input type="file" onchange="showimagepreview(this)" name="cost_files[]" style="display: none" id="upl" multiple />
                        </label>
                    </div>

<?php
if (!empty($image_data)) {
    if (count($image_data) > 0) {
        $i = 15482564;
        foreach ($image_data as $image) {
            $path = $image['file_path'];
            $name = $image['file_name'];
            $arr_list = explode('.', $name);
            $arr = $arr_list[1];
            if (file_exists($path . '/' . $name)) {
                ?>
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
    <?php }
} ?>

                </div>
                <div class="clr"> </div>
            </div>
            <!-- end upload drag and drop file -->
            <div class="clr"></div>
        </div>

        <div class ="form-group row">
            <div class="col-md-3 col-xs-12">
                <label><?php echo lang('USE_COMPANY_LOCATION'); ?> :* </label>
                <select name="company_id_location" class="chosen-select" onchange="getCompany_location(this.value);" data-placeholder="<?php echo lang('SELECT_COMPANY_LOCATION'); ?>" id="company_id_location" data-parsley-errors-container="#location-errors">
                    <option value=""></option>
<?php
foreach ($company_data as $company) {
    ?>
                        <option value="<?php echo $company['company_id'] ?>" <?php if (isset($editRecord[0]['company_id_location']) && $company['company_id'] == $editRecord[0]['company_id_location']) {
        echo "selected='selected'";
    } ?>><?php echo $company['company_name'] ?></option>
                    <?php }
                    ?>

                </select>
                <div id="location-errors"></div>
            </div>
            <div class = "col-md-3 col-xs-12">
                <div class="pad_top20">
                   <div class="pull-left"> <input <?php if (!empty($editRecord[0]['is_another_location']) && $editRecord[0]['is_another_location'] == '1') { ?>checked="checked"<?php } ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"  class="event_reminder_tog" data-toggle="toggle" data-onstyle="success" type="checkbox" id="is_another_location" name="is_another_location"/></div>
                    <div class="col-sm-6"><label> <?= $this->lang->line('USE_ANOTHER_LOCATION_ADDRESS') ?></label></div>
                </div>
            </div> 
        </div>
        <div class ="form-group row">
            <div class="col-sm-6">
                <!-- onkeyup="checkTextareaWord()"-->
                <textarea class="form-control" required rows="2" name="meeting_location" id="meeting_location" placeholder="<?php echo lang('SELECT_LOCATION_OF_MEETING'); ?> *"><?= !empty($editRecord[0]['meeting_location']) ? $editRecord[0]['meeting_location'] : '' ?></textarea>
                <label id="QtChar"> </label>
            </div>

            <div class = "col-sm-6 col-md-3">
                <div class="pad_top20">
                    <input <?php if (!empty($editRecord[0]['is_public'])) { ?>checked="checked"<?php } ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"  class="event_reminder_tog" data-toggle="toggle" data-onstyle="success" type="checkbox"  id="public_visible" name="public_visible"/>
                    <label><?= $this->lang->line('PUBLIC_VISIBLE') ?></label>
                </div>
            </div> 
            <div class = "col-sm-6 col-md-3">
                <div class="pad_top20">
                    <input <?php if (!empty($editRecord[0]['is_private'])) { ?>checked="checked"<?php } ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"  class="event_reminder_tog" data-toggle="toggle" data-onstyle="success" type="checkbox" id="private_visible" name="private_visible"/>
                    <label> <?= $this->lang->line('PRIVATE_VISIBLE') ?></label>
                </div>
            </div> 

        </div>

        <div class ="form-group row">

            <div class = "col-sm-6">
                <div class="pad_top20">
                    <input <?php if (!empty($editRecord[0]['is_event'])) { ?>checked="checked"<?php } ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"  class="event_reminder_tog" data-toggle="toggle" data-onstyle="success" type="checkbox"  id="create_event" name="create_event"/>
                    <label><?= $this->lang->line('CREATE_AN_EVENT') ?></label>
                </div>
            </div> 
            <div class = "col-sm-6">
<?php
if (!empty($editRecord[0]['is_recurring'])) {

    $recurring_id = "first_time_show_recurring";
} else {

    $recurring_id = "first_time_hide_recurring";
}
?>
                <div class="pad_top20">
                    <input <?php if (!empty($editRecord[0]['is_recurring'])) { ?>checked="checked"<?php } ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"  class="event_reminder_tog" data-toggle="toggle" data-onstyle="success" type="checkbox"  id="recurring_meeting" name="recurring_meeting" onChange="toggle_show_recurring(<?php echo "'#" . $recurring_id . "'"; ?>, this)"/>
                    <label><?= $this->lang->line('RECURRING_MEETING') ?></label>
                </div>
            </div> 

        </div>

        <div id="<?php echo $recurring_id; ?>">
            <div class ="row">
                <div class = "col-xs-12 col-sm-6"></div>
                <div class = "col-xs-12 col-sm-6">
                    <div class="row">
                        <div class = "col-xs-12 col-sm-6 form-group">
                            <label><?php echo lang('RECURRING_TIME'); ?> : *</label>
                            <select name="recurring_repeat" data-placeholder="<?= $this->lang->line('SELECT_RECURRING_TIME') ?>" class="form-control chosen-select" id="recurring_repeat" data-parsley-errors-container="#errors_recurring">
                                <option value=""></option>
                                <option value="daily" <?php
                if (!empty($editRecord[0]['recurring_repeat']) && ($editRecord[0]['recurring_repeat'] == 'daily')) {
                    echo 'selected';
                }
?>><?= $this->lang->line('daily') ?></option>
                                <option value="weekly" <?php
                                if (!empty($editRecord[0]['recurring_repeat']) && ($editRecord[0]['recurring_repeat'] == 'weekly')) {
                                    echo 'selected';
                                }
                                ?>><?= $this->lang->line('weekly') ?></option>
                                <option value="monthly" <?php
                                if (!empty($editRecord[0]['recurring_repeat']) && ($editRecord[0]['recurring_repeat'] == 'monthly')) {
                                    echo 'selected';
                                }
                                ?>><?= $this->lang->line('monthly') ?></option>
                                <option value="yearly" <?php
                                if (!empty($editRecord[0]['recurring_repeat']) && ($editRecord[0]['recurring_repeat'] == 'yearly')) {
                                    echo 'selected';
                                }
                                ?>><?= $this->lang->line('yearly') ?></option>
                            </select>
                            <div id="errors_recurring"></div>
                        </div>
                        <div class = "col-xs-12 col-sm-6 form-group">
                            <label><?php echo lang('RECURRING_END_DATE'); ?>: *</label>
                            <div class="input-group date">
                                <input type="text" name="recurring_end_date" id="recurring_end_date" class="form-control" value="<?php
                                        if (!empty($editRecord[0]['recurring_end_date'])) {
                                            echo date('Y-m-d', strtotime($editRecord[0]['recurring_end_date']));
                                        }
                                ?>"/> <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                            </div>
                        </div>

                        <div class="clr"></div>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <div class="clr"></div>

        <input type="hidden" id="meeting_related_id" name="meeting_related_id" value="<?= !empty($editRecord[0]['meet_related_id']) ? $editRecord[0]['meet_related_id'] : $meeting_related_id ?>">
        <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
        <input type="hidden" name="hdn_contact_id" id="hdn_contact_id" value="<?= !empty($contact_id) ? $contact_id : '' ?>">
        <input type="hidden" name="meeting_id" id="meeting_id" value="<?= !empty($meeting_id) ? $meeting_id : '' ?>" />
        <input type="hidden" name="hdn_company_id" id="hdn_company_id" value="<?= !empty($company_id) ? $company_id . "/3" : '' ?>" />
        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>"> 
    </div>
    <div class="modal-footer">
        <center> <input type="submit" class="btn btn-primary" id="contact_submit_btn" value="<?= $submit_button_title ?>"></center>
    </div>
</div>
<?php echo form_close(); ?>
</div>
<!-- /.modal-dialog -->
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
    $(document).ready(function () {
        $('#form_schedule_meeting').parsley();
    });
</script>
<script>

    function checkTextareaWord()
    {
        $("#QtChar")[0].innerText = "Allowed Character : " + parseInt($("#meeting_title")[0].maxLength - $("#meeting_title").val().length);
    }

    $('.chosen-select').chosen();

    var current_date = new Date();
    $('.event_reminder_tog').bootstrapToggle();

<?php
if (!empty($meeting_id)) {
    ?>
        $('#meeting_date').datepicker({format: "yyyy-mm-dd", startDate: current_date, autoclose: true}).on('changeDate', function (selected) {
            updateAb(selected);
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('#recurring_end_date').datepicker('setStartDate', startDate);
            $('#recurring_end_date').datepicker('setDate', startDate);

            var picker2 = $('#reminder_date').data("DateTimePicker");
            if (picker2) {
                picker2.destroy();
            }
            $('#reminder_date').datetimepicker({format: 'YYYY-MM-DD', maxDate: startDate, minDate: moment()});

            var picker1 = $('#reminder_time').data("DateTimePicker");
            if (picker1) {
                picker1.destroy();
            }
            $('#reminder_time').datetimepicker({format: 'HH:mm', maxDate: startDate, minDate: null});
        });
<?php } else {
    ?>

        $('#meeting_date').datepicker({format: "yyyy-mm-dd", startDate: current_date, autoclose: true});
        $('#meeting_date').datepicker("setDate", new Date()).on('changeDate', function (selected) {
            updateAb(selected);

            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('#recurring_end_date').datepicker('setStartDate', startDate);
            $('#recurring_end_date').datepicker('setDate', startDate);

            var today = new Date();
            today.setHours(0);
            today.setMinutes(0);
            today.setSeconds(0);
        
            var picker2 = $('#reminder_date').data("DateTimePicker");
            if (picker2) {
                picker2.destroy();
            }
            var picker1 = $('#reminder_time').data("DateTimePicker");
            if (picker1) {
                picker1.destroy();
            }
           

            if (Date.parse(today) == Date.parse(startDate))
            {
                $('#reminder_date').datetimepicker({format: 'YYYY-MM-DD', maxDate: moment(), minDate: moment()});
                $('#reminder_time').datetimepicker({format: 'HH:mm',minDate: moment()});
            }else
            {
                $('#reminder_date').datetimepicker({format: 'YYYY-MM-DD', maxDate: startDate, minDate:moment()});
                $('#reminder_time').datetimepicker({format: 'HH:mm'});
            }
            
            
            
        });


<?php } ?>

<?php
if (!empty($meeting_id)) {
    ?>
        $('#meeting_time').datetimepicker({format: 'HH:mm'});
<?php } else {
    ?>
        $('#meeting_time').datetimepicker({format: 'HH:mm', minDate: moment()});

<?php } ?>

<?php
if (!empty($meeting_id)) {
    ?>
        $('#meeting_end_time').datetimepicker({format: 'HH:mm'});
<?php } else {
    ?>

        $('#meeting_end_time').datetimepicker({format: 'HH:mm', minDate: moment()});

<?php } ?>

<?php
if (!empty($meeting_id)) {
    ?>
        $('#recurring_end_date').datepicker({format: "yyyy-mm-dd", startDate: current_date, autoclose: true});

<?php } else {
    ?>
        $('#recurring_end_date').datepicker({format: "yyyy-mm-dd", autoclose: true}).datepicker("setDate", new Date());
<?php } ?>

<?php
if (empty($meeting_id)) {
    ?>
        $('#private_visible').bootstrapToggle('on');
<?php }
?>
<?php
if (!empty($meeting_id)) {
    ?>
        $('#reminder_date').datetimepicker({format: 'YYYY-MM-DD'});
<?php } else {
    ?>
        $('#reminder_date').datetimepicker({minDate: current_date, format: 'YYYY-MM-DD'});
<?php } ?>

<?php
if (!empty($meeting_id)) {
    ?>
        $('#reminder_time').datetimepicker({format: 'HH:mm'});
<?php } else {
    ?>
        $('#reminder_time').datetimepicker({format: 'HH:mm', minDate: moment()});

<?php } ?>

</script>
<script>

    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
    });
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
    var config = {
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo base_url(); ?>/Contact/upload_file"				// Server side upload url
    }
    $(document).ready(function () {
        //initMultiUploader(config);
        var dropbox;
        var oprand = {
            dragClass: "active",
            on: {
                load: function (e, file) {
                    // check file size
                    if (parseInt(file.size / 1024) > 20480) {
                        var delete_meg ='File \"" + file.name + "\" <?php echo lang('too_big_size'); ?>';
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
            xhr[rand].open("post", "<?php echo base_url('/Contact/upload_file') ?>/" + fileext, true);

            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                    $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                }
                else {
                    var delete_meg ="<?php echo lang('failed_file_upload');?>";
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
    function delete_row(rand) {
        jQuery('#' + rand).remove();
    }


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
                    template += '<span class="preview" id="' + rand + '"><div><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);


        });

        var maximum = input.files[0].size / 1024;


    }

    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
        {

            $(className).show();
            $('#form_schedule_meeting').parsley().reset();
            $('#reminder_date').attr('data-parsley-required', 'true');
            $('#reminder_time').attr('data-parsley-required', 'true');
            $('#form_schedule_meeting').parsley();
        }
        else
        {

            $(className).hide();
            $('#form_schedule_meeting').parsley().reset();
            $('#reminder_date').attr('data-parsley-required', 'false');
            $('#reminder_time').attr('data-parsley-required', 'false');
            $('#form_schedule_meeting').parsley();
        }
    }
    function toggle_show_recurring(className, obj) {

        var $input = $(obj);
        if ($input.prop('checked'))
        {
            $(className).show();
            $('#form_schedule_meeting').parsley().reset();
            $('#recurring_repeat').attr('data-parsley-required', 'true');
            $('#recurring_end_date').attr('data-parsley-required', 'true');
            $('#form_schedule_meeting').parsley();
        }
        else
        {
            $(className).hide();
            $('#form_schedule_meeting').parsley().reset();
            $('#recurring_repeat').attr('data-parsley-required', 'false');
            $('#recurring_end_date').attr('data-parsley-required', 'false');
            $('#form_schedule_meeting').parsley();
        }
    }

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg ="<?php echo lang('delete_item'); ?>";
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


    $(function () {
        $('#public_visible').change(function () {
            var abv = $('#public_visible').parents('div').attr('class');

            if (abv.indexOf("btn-success") == -1)
            {
                $('#private_visible').bootstrapToggle('on');
                $('#public_visible').bootstrapToggle('off');
            } else
            {
                $('#private_visible').bootstrapToggle('off');
                $('#public_visible').bootstrapToggle('on');
            }
        });
        $('#private_visible').change(function () {
            var abv = $('#private_visible').parents('div').attr('class');

            if (abv.indexOf("btn-success") == -1)
            {
                $('#public_visible').bootstrapToggle('on');
                $('#private_visible').bootstrapToggle('off');
            } else
            {
                $('#public_visible').bootstrapToggle('off');
                $('#private_visible').bootstrapToggle('on');
            }
        });

        $('#is_another_location').change(function () {
            var abv = $('#is_another_location').parents('div').attr('class');

            if (abv.indexOf("btn-success") == -1)
            {
                $('#company_id_location').prop('disabled', false).trigger("chosen:updated");
                $('#company_id_location').attr('data-parsley-required', 'true').trigger("chosen:updated");
            } else
            {
                $('#company_id_location').prop('disabled', true).trigger("chosen:updated");
                $('#company_id_location').attr('data-parsley-required', 'false').trigger("chosen:updated");
                $('#meeting_location').val('');
            }
        });
        $('#company_id_location').attr('data-parsley-required', 'true').trigger("chosen:updated");
    });


    function getCompany_location(company_id)
    {
        $("#meeting_location").val('');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('Contact/getCompany_location'); ?>",
            data: {
                company_id: company_id
            },
            success: function (html) {

                $("#meeting_location").val(html);
            }
        });
    }

    function updateAb()
    {
        var selectedDate = $('#meeting_date').datepicker('getDate');

        var today = new Date();
        today.setHours(0);
        today.setMinutes(0);
        today.setSeconds(0);
        if (Date.parse(today) == Date.parse(selectedDate))
        {
            
            var picker = $('#meeting_time').data("DateTimePicker");
            if (picker) {
                picker.destroy();
            }
            var picker1 = $('#meeting_end_time').data("DateTimePicker");
            if (picker1) {
                picker1.destroy();
            }

            $('#meeting_time').datetimepicker({format: 'HH:mm', showClose: true, minDate: moment()});
            $('#meeting_end_time').datetimepicker({format: 'HH:mm', showClose: true, minDate: moment()});

        } else {

            var picker = $('#meeting_time').data("DateTimePicker");
            if (picker) {
                picker.destroy();
            }

            var picker2 = $('#meeting_end_time').data("DateTimePicker");
            if (picker2) {
                picker2.destroy();
            }
            $('#meeting_time').datetimepicker({format: 'HH:mm', minDate: null});
            $('#meeting_end_time').datetimepicker({format: 'HH:mm', minDate: null});

        }
    }

    $('#meeting_time').on('change dp.change', function (e) {
        var start_date = e.date._d;

        var picker2 = $('#meeting_end_time').data("DateTimePicker");
        if (picker2) {
            picker2.destroy();
        }
        $('#meeting_end_time').datetimepicker({format: 'HH:mm', minDate: start_date});
    });
    
    
<?php
if (isset($editRecord[0]['is_another_location']) && $editRecord[0]['is_another_location'] == "1") {
    ?>

        $('#company_id_location').prop('disabled', true).trigger("chosen:updated");
        $('#company_id_location').attr('data-parsley-required', 'false').trigger("chosen:updated");
<?php } ?>

</script>
