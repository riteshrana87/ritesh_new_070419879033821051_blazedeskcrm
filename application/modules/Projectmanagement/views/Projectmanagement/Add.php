<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath = $project_view . '/' . $formAction;
?>
<div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
        <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>"
              data-parsley-validate>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <div class="modelTaskTitle"><?= $modal_title ?></div>
                </h4>
            </div>

            <div class="modal-body">

                <div class=" row">
                    <div class="col-sm-6 form-group">
                        <input type="text" class="form-control" placeholder="<?= lang('project_name') ?> *"
                               id="project_name" maxlength="100"  name="project_name" value="<?= !empty($edit_record[0]['project_name']) ? $edit_record[0]['project_name'] : '' ?>" required>
                        <span id="task_name_error" class="alert-danger"></span>
                    </div>
                    <div class="col-sm-6 form-group">
                        <input type="text" readonly class="form-control"
                               placeholder="P###(<?= lang('auto_generated_number') ?>)"  id="project_code" name="project_code" value="<?= !empty($edit_record[0]['project_code']) ? $edit_record[0]['project_code'] : $project_code ?>">
                        <span id="task_name_error" class="alert-danger"></span>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="  control-label"><?= lang('description') ?> </label>
                        <textarea class="form-control" rows="4" placeholder="<?= lang('project_desc') ?>"
                                  name="project_desc" id="project_desc"
                                  ><?= !empty($edit_record[0]['project_desc']) ? $edit_record[0]['project_desc'] : '' ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label"><?= lang('assign_user') ?>*</label>

                    <div class="col-lg-12">
                        <select tabindex="-1" required multiple="multiple" id="res_user" name="res_user[]"
                                class="chosen-select" data-placeholder="<?= lang('choose_user') ?> *">
                                    <?php
                                    if (!empty($res_user)) {
                                        foreach ($res_user as $row) {
                                            ?>

                                    <option <?php
                                    if (!empty($user_id) && in_array($row['login_id'], $user_id)) {
                                        echo 'selected="selected"';
                                    }
                                    ?>
                                        value="<?= $row['login_id'] ?>"><?= ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                        <?php
                                    }
                                }
                                ?>
                        </select>
                    </div>

                </div>
                <div class="form-group row">
                    <label class="col-lg-12 control-label"><?= lang('client') ?>*</label>

                    <div class="col-lg-12">
                        <select tabindex="-1" required id="client_id" name="client_id"
                                class="chosen-select" data-placeholder="<?= lang('choose_client') ?> *">
                            <option value=""></option>
                            <optgroup label="Company">
                                <?php foreach ($company_info as $company) { ?>
                                    <option  <?php
                                        if (!empty($edit_record) && ($edit_record[0]['client_type'] . '_' . $edit_record[0]['client_id'] == 'company_' . $company['company_id'])) {
                                            echo "selected";
                                        }
                                        ?> value="company_<?php echo $company['company_id']; ?>"><?php echo $company['company_name']; ?></option>
                                <?php } ?>
                            </optgroup>
                            <optgroup label="Client">
                                    <?php foreach ($client_info as $client) { ?>
                                    <option  <?php
                                    if (!empty($edit_record) && ($edit_record[0]['client_type'] . '_' . $edit_record[0]['client_id'] == 'client_' . $client['prospect_id'])) {
                                        echo "selected";
                                    }
                                    ?> value="client_<?php echo $client['prospect_id']; ?>"><?php echo $client['prospect_name']; ?></option>
                                    <?php } ?>
                            </optgroup>
                            <optgroup label="Contact">
<?php foreach ($contact_info as $contact) { ?>
                                    <option <?php if (!empty($edit_record) && ($edit_record[0]['client_type'] . '_' . $edit_record[0]['client_id'] == 'contact_' . $contact['contact_id'])) {
        echo "selected";
    }
    ?> value="contact_<?php echo $contact['contact_id']; ?>"><?php echo $contact['contact_name']; ?></option>
<?php } ?>
                            </optgroup>
                        </select>
                    </div>

                </div>
                <div class="row"><div class="col-xs-12 col-md-6 col-sm-6 col-lg-6 no-left-pad">
                        <div class="form-group">
                            <label> <?= lang('start_date') ?> <span class="viewtimehide">*</span></label>

                            <div class='input-group date' id='task_start_date'>
                                <input type='text' onkeydown="return false" data-parsley-errors-container="#start_date_error" class="form-control" name="start_date"
                                       id="task_start_date" placeholder="<?= lang('start_date') ?>"
                                       value="<?php
if (isset($edit_record[0]['start_date']) && $edit_record[0]['start_date'] != '0000-00-00') {
    echo configDateTime($edit_record[0]['start_date']);
};
?>" data-parsley-gteq="#task_due_date"  required/>
                                <span class="input-group-addon"> <span
                                        class="glyphicon glyphicon-calendar"></span> </span></div>
                            <span class="text-danger" id="start_date_error"></span>


                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6 no-left-pad">
                        <div class="form-group">
                            <label> <?= lang('due_date') ?> <span class="viewtimehide">*</span></label>

                            <div class='input-group date' id='task_due_date'>
                                <input type='text' onkeydown="return false" data-parsley-errors-container="#end_date_error" id="task_due_date" class="form-control" name="due_date"
                                       placeholder="<?= lang('due_date') ?>"
                                       value="<?php
if (isset($edit_record[0]['due_date']) && $edit_record[0]['due_date'] != '0000-00-00') {
    echo configDateTime($edit_record[0]['due_date']);
};
?>"  required/>
                                <span class="input-group-addon"> <span
                                        class="glyphicon glyphicon-calendar"></span> </span></div>
                            <span class="text-danger" id="end_date_error"></span>
                        </div>
                    </div></div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="col-lg-12 control-label"><?= lang('project_budget') ?> <span class="viewtimehide">*</span></label>
                        <input type="text" maxlength="10" class="form-control" 
                               placeholder="<?= lang('project_budget') ?>" id="project_budget"
                               name="project_budget"
                               value="<?= !empty($edit_record[0]['project_budget']) ? $edit_record[0]['project_budget'] : '' ?>"
                               data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/" required>
                    </div>
                </div>
                <div class="form-group row">


                    <div class="col-lg-12">
                        <label class="custom-upload btn btn-blue"><?= lang('project_icon') ?><input type="file" name="addfile" id="files" class="input-group" data-parsley-fileextension='ico|png' data-parsley-max-file-size="200" data-parsley-errors-container="#logo_image_errors" ></label>
                            <!-- <input type="file" class="form-control" name="addfile" id="files"> -->
                        <p id="logo_image_errors"></p>
                    </div>
                </div>
                <div class="form-group row" id="display_img">
                    <div class="col-sm-2">

<?php
if (!empty($edit_record[0]['project_icon'])) {

    $path = $edit_record[0]['icon_path'];
    $name = $edit_record[0]['project_icon'];
    ?>
    <?php if (file_exists($path . '/' . $name)) { ?>

                                <a href='<?php echo base_url($project_view . '/download/' . $edit_record[0]['project_id']); ?>'>
        <?php //echo $edit_record[0]['project_icon'];  ?>
                                    <img
                                        id="image_dis" src="<?= base_url($edit_record[0]['icon_path']) . '/' . $edit_record[0]['project_icon'] ?>"
                                        width="75"/>
                                </a>

                                <!-- <a class="btn delete_file" href="javascript:;"
                                   data-id="img_<?php echo $edit_record[0]['project_id']; ?>"
                                   data-href="<?php echo base_url($project_view . '/delete_file/' . $edit_record[0]['project_id']); ?>"><i
                                        class="fa fa-remove redcol"></i></a> -->
                                <a title="<?php echo lang('delete'); ?>" class="delete_file remove_drag_img" href="javascript:;"
                                   data-id="img_<?php echo $edit_record[0]['project_id']; ?>"
                                   data-href="<?php echo base_url($project_view . '/delete_file/' . $edit_record[0]['project_id']); ?>"
                                   data-name="<?php echo $edit_record[0]['icon_path']; ?>"
                                   data-path="<?php echo $edit_record[0]['project_icon']; ?>"
                                   >x</a>

        <?php
    } else {
        ?> 
                                <img id="image_dis" alt="" src="" width="75"/>
    <?php }
} else {
    ?>
                            <img id="image_dis" alt="" src="" width="75"/>
<?php }
?>

                    </div>

                </div>


            </div>

            <div class="modal-footer">
                <center>
                    <input type="hidden" name="delete_image" id="delete_image" value="">
                    <input type="hidden" id="project_id" name="project_id"
                           value="<?= !empty($edit_record[0]['project_id']) ? $edit_record[0]['project_id'] : '' ?>">
                    <input type="hidden" id="display" name="display" value="<?= !empty($redirect) ? $redirect : '' ?>">
                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn"
                           value="<?= $submit_button_title ?>"/>

            </div>
    </div>
</form>
</div>

</div>
<?php //echo form_close();  ?>
<script type="text/javascript">
    //editor
    $('#project_desc').summernote({
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        },
        focus: true
    });
    $(function () {

        $('.chosen-select').chosen();
        //$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
        //datepicker
        window.Parsley.addValidator('gteq',
                function (value, requirement) {
                    return Date.parse($('#task_due_date input').val()) >= Date.parse($('#task_start_date input').val());
                }, 32)
                .addMessage('en', 'le', 'This value should be less or equal');
        //Intialize datepicker
        var select_date = '';
<?php if (isset($edit_record)) { ?>
            if (Date.parse('<?= date("m/d/Y") ?>') >= Date.parse('<?= date("m/d/Y", strtotime($edit_record[0]["start_date"])) ?>'))
            {
                select_date = new Date('<?= date("m/d/Y", strtotime($edit_record[0]["start_date"])) ?>');
            }
            else {
                select_date = new Date();
            }
<?php } else { ?>
            select_date = new Date();
<?php } ?>
        $('#task_start_date').datepicker({
            autoclose: true,
            startDate: select_date,
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('#task_due_date').datepicker('setStartDate', startDate);
        });
        $('#task_due_date')
                .datepicker({
                    autoclose: true, startDate: new Date()
                })/*.on('changeDate', function(){
                 $('#task_start_date').datepicker('setEndDate', new Date($(this).val()));
                 });*/

        $('#from-model').parsley();
        //disabled after submit
        $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }
        });
        //popup scroll
        $('.note-image-dialog,.note-link-dialog,.note-video-dialog,.note-help-dialog').on('hidden.bs.modal', function () {

            $('body').addClass('modal-open');
        });
    });
    //Remove files
    //Remove files
    $('.delete_file').on('click', function () {
        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');
        var delete_meg ="<?= lang('common_delete_file') ?>";
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
                        $('#delete_image').val(imgName + dataPath);
                        $('#display_img').remove();
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
                        dialog.close();
                    }

                }]
            });

    });
    //numeric decimal number
    function numericDecimal(e) {
        var unicode = e.charCode ? e.charCode : e.keyCode;
        //alert(unicode);
        if (unicode != 8) {
            if (unicode < 9 || unicode > 9 && unicode < 46 || unicode > 57 || unicode == 47) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return true;
        }
    }
    document.getElementById("files").onchange = function () {
        var reader = new FileReader();

        reader.onload = function (e) {
            // get loaded data and render thumbnail.
            var filename = document.getElementById("files").value;
            var arr1 = filename.split('.');
            var arr = arr1[1].toLowerCase();

            if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                document.getElementById("image_dis").src = e.target.result;
            }
            else
            {
                document.getElementById("image_dis").src = '';
                document.getElementById("image_dis").alt = filename;
            }
        };

        // read the image file as a data URL.
        reader.readAsDataURL(this.files[0]);
    };
</script>

