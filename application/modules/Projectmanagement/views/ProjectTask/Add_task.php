<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath = $task_view . '/' . $formAction;
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="from-model" method="post" action="<?php echo base_url($formPath); ?>" enctype="multipart/form-data"
              data-parsley-validate>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <h4><b><?= $modal_title ?></b></h4>
            </div>
            <div class="modal-body">
             <?php if(empty($edit_record) && $project_detail[0]['due_date'] < date('Y-m-d')) {?>
              <?php if (isset($sub_task)) { ?>
              <div class='alert alert-danger text-center'><?=lang('project_over_subtask_message')?></div>
              <?php } else { ?>
              <div class='alert alert-danger text-center'><?=lang('project_over_task_message')?></div>
            <?php }$dis="disabled";} ?>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 ">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= lang('task_name') ?> *"
                                   id="task_name" maxlength="200" name="task_name"
                                   value="<?= !empty($edit_record[0]['task_name']) ? $edit_record[0]['task_name'] : '' ?>"
                                   required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group">

                            <input type="text" readonly class="form-control"
                                   placeholder="T###<?= lang('auto_generated_number') ?>" id="task_code"
                                   name="task_code"
                                   value="<?= !empty($edit_record[0]['task_code']) ? $edit_record[0]['task_code'] : $task_code ?>">
                        </div>
                    </div>
                    <div class="clearfix visible-xs-block"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group">
                            <?php if (isset($sub_task)) { ?>

                                <?php /* <label><?= lang('affiliate_task') ?></label> */ ?>
                                <span id="subtask-errors"></span>
                                <select tabindex="-1" name="sub_task_id" data-parsley-errors-container="#subtask-errors"
                                        id="sub_task_id" class="chosen-select"
                                        data-placeholder="<?= lang('affiliate_task') ?> *" required>
                                        <?php
                                            $pstart_date = '';
                                            $pdue_date = '';
                                            if ($project_detail[0]["start_date"] != '0000-00-00') {
                                                $pstart_date = configDateTime($project_detail[0]["start_date"]);
                                            }
                                            if ($project_detail[0]["due_date"] != '0000-00-00') {
                                                $pdue_date = configDateTime($project_detail[0]["due_date"]);
                                            }
                                         ?>
                                    <option value="" data-attr="<?= $pstart_date ?>-<?= $pdue_date ?>"><?= lang('select_task') ?> *</option>
                                    <?php
                                    if (!empty($task_data)) {
                                        foreach ($task_data as $row) {
                                            $start_date = '';
                                            $due_date = '';
                                            if ($row['start_date'] != '0000-00-00') {
                                                $start_date = configDateTime($row['start_date']);
                                            }
                                            if ($row['due_date'] != '0000-00-00') {
                                                $due_date = configDateTime($row['due_date']);
                                            }
                                            ?>
                                            <option
                                                data-attr="<?= $start_date ?>-<?= $due_date ?>" <?= (!empty($edit_record[0]['sub_task_id']) && $edit_record[0]['sub_task_id'] == $row['task_id']) ? 'selected="selected"' : '' ?>
                                                value="<?= $row['task_id'] ?>"><?= ucfirst($row['task_name']) ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                </select>

                            <?php } else { ?>
                                <?php /* <label><?= lang('affiliate_milestone') ?></label> */ ?>


                                <select tabindex="-1" name="milestone_id" id="milestone_id"
                                        data-parsley-errors-container="#milestone_err" class="chosen-select"
                                        data-placeholder="<?= lang('affiliate_milestone') ?>">
                                        <?php
                                            $pstart_date = '';
                                            $pdue_date = '';
                                            if ($project_detail[0]["start_date"] != '0000-00-00') {
                                                $pstart_date = configDateTime($project_detail[0]["start_date"]);
                                            }
                                            if ($project_detail[0]["due_date"] != '0000-00-00') {
                                                $pdue_date = configDateTime($project_detail[0]["due_date"]);
                                            }
                                         ?>
                                    <option value="" data-attr="<?= $pstart_date ?>-<?= $pdue_date ?>"><?= lang('select_milestone') ?></option>
                                    <?php
                                    if (!empty($milestone)) {
                                        foreach ($milestone as $row) {
                                            $start_date = '';
                                            $due_date = '';
                                            if ($row['start_date'] != '0000-00-00') {
                                                $start_date = configDateTime($row['start_date']);
                                            }
                                            if ($row['due_date'] != '0000-00-00') {
                                                $due_date = configDateTime($row['due_date']);
                                            }
                                            ?>
                                            <option
                                                data-attr="<?= $start_date ?>-<?= $due_date ?>" <?= (!empty($edit_record[0]['milestone_id']) && $edit_record[0]['milestone_id'] == $row['milestone_id']) ? 'selected="selected"' : '' ?>
                                                value="<?= $row['milestone_id'] ?>"><?= ucfirst($row['milestone_name']) ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                </select>
                                <span id="milestone_err"></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="row form-group">
                            <div class="col-xs-12 col-md-6 pad-t6 col-lg-6 col-sm-6">
                                <label><?= lang('creation_date') ?></label>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                                <div class="input-group date full-width" id="datepicker3">
                                    <input type="text" onkeydown="return false" class="form-control"
                                           placeholder="<?php echo lang('cost_placeholder_createddate') ?>"
                                           id="created_date" name="created_date" value="<?php
                                           if (isset($edit_record[0]['created_date'])) {
                                               echo configDateTime($edit_record[0]['created_date']);
                                           } else {
                                               echo configDateTime();
                                           }
                                           ?>">
                                    <!-- <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span >-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">

                        <div class="form-group">

                            <label><?= lang('team_member') ?> <span class="viewtimehide">*</span></label>

                            <select tabindex="-1" multiple="multiple" data-parsley-errors-container="#team_member_err"
                                    id="team_member1" name="team_member[]"
                                    class="chosen-select" data-placeholder="<?= lang('select_an_option') ?>" required>
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
                            <span id="team_member_err"></span>
                        </div>
                    </div>
					<div class="col-sm-6">
                    	 <div class="row">
                    <div class="col-xs-12 ">
                        <div class="row  bd-form-group">
                            <div class="col-xs-12 col-md-6 pad-t6 col-lg-6 col-sm-6">
                                <label><?= lang('start_date') ?> <span class="viewtimehide">*</span></label>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">

                                <div class="input-group date" id="task_start_date">
                                    <?php if (!isset($sub_task)) { ?>

                                        <input type='text' data-parsley-errors-container="#st_err" onkeydown="return false"
                                               class="form-control" name="start_date" id="task_start_date"
                                               placeholder="<?= lang('start_date') ?>"
                                               value="<?php
                                               if (isset($edit_record[0]['start_date']) && $edit_record[0]['start_date'] != '0000-00-00') {
                                                   echo configDateTime($edit_record[0]['start_date']);
                                               };
                                               ?>" data-parsley-leeqm="#task_start_date" required/>
                                           <?php } else { ?>
                                        <input type='text' onkeydown="return false" data-parsley-errors-container="#st_err"
                                               class="form-control" name="start_date" id="task_start_date"
                                               placeholder="<?= lang('start_date') ?>"
                                               value="<?php
                                               if (isset($edit_record[0]['start_date']) && $edit_record[0]['start_date'] != '0000-00-00') {
                                                   echo configDateTime($edit_record[0]['start_date']);
                                               };
                                               ?>" data-parsley-leeqst="#task_start_date" required/>
                                           <?php } ?>
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                                </div>
                                <span id="st_err"></span>
                            </div>
                            <div class="clr"></div>
                        </div>
                        
                    </div>
                    <div class="col-xs-12 ">
                        <div class="row  bd-form-group">
                            <div class="col-xs-12 col-md-6 pad-t6 col-lg-6 col-sm-6">
                                <label><?= lang('due_date') ?> <span class="viewtimehide">*</span></label>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">

                                <div class="input-group date" id="task_due_date">

                                    <?php if (!isset($sub_task)) { ?>
                                        <input type='text' onkeydown="return false" data-parsley-errors-container="#dt_err"
                                               id="task_due_date" class="form-control" name="due_date"
                                               placeholder="<?= lang('due_date') ?>"
                                               value="<?php
                                               if (isset($edit_record[0]['due_date']) && $edit_record[0]['due_date'] != '0000-00-00') {
                                                   echo configDateTime($edit_record[0]['due_date']);
                                               };
                                               ?>" data-parsley-gteqm="#task_due_date"
                                               data-parsley-gteqt="#task_due_date" required/>
                                           <?php } else { ?>
                                        <input type='text' onkeydown="return false" data-parsley-errors-container="#dt_err"
                                               id="task_due_date" class="form-control" name="due_date"
                                               placeholder="<?= lang('due_date') ?>"
                                               value="<?php
                                               if (isset($edit_record[0]['due_date']) && $edit_record[0]['due_date'] != '0000-00-00') {
                                                   echo configDateTime($edit_record[0]['due_date']);
                                               };
                                               ?>" data-parsley-gteqst="#task_due_date"
                                               data-parsley-gteqt="#task_due_date" required/>
                                           <?php } ?>
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                                </div>
                                <span id="dt_err"></span>
                            </div>
                             <div class="clr"></div>
                        </div>
                       
                    </div>

                    <div class="clr"></div>
                </div>
                    </div>

                    <div class="clr"></div>
                </div>
               
                <div class="clr"></div>
                <br>

                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <label class="  control-label"><?= lang('description') ?> </label>
                            <textarea name="description" placeholder="<?= lang('description') ?>" id="description"
                                      class="form-control"><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></textarea>

                        </div>
                    </div>
                </div>

                <div class="clr"></div>
                <?php if (!empty($edit_record)) { ?>
                    <div class="form-group row">
                        <div class="col-sm-12 date">
                            <label class="  control-label"><?= lang('status') ?> </label>
                            <select tabindex="-1" name="status" class="chosen-select"
                                    data-placeholder="<?= lang('status') ?>">
                                        <?php
                                        if (!empty($project_status)) {
                                            foreach ($project_status as $row) {
                                                ?>
                                        <option <?php
                                        if (!empty($edit_record[0]['status']) && $row['status_id'] == $edit_record[0]['status']) {
                                            echo 'selected="selected"';
                                        }
                                        ?> value="<?= $row['status_id'] ?>"><?= $row['status_name'] ?></option>
                                            <?php
                                        }
                                    }
                                    ?>

                            </select>
                        </div>
                    </div>
                <?php } ?>
                <?php if (!isset($sub_task)) { ?>
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 bd-togl-label">
                            <div class="form-group">
                                <div class="btn-group btn-toggle">
                                    <input checked data-toggle="toggle" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"  data-onstyle="success" type="checkbox"
                                           id="in_project_scope" name="in_project_scope"  />
                                </div>
                                <label><?= $this->lang->line('within_project_scope') ?></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                            <div class="form-group">
                                <select tabindex="-1" name="deal_id" class="chosen-select"
                                        data-placeholder="<?= lang('select_deal') ?>">
                                    <option value=""><?= lang('select_deal') ?></option>
                                    <?php
                                    if (!empty($deal_data)) {
                                        foreach ($deal_data as $row) {
                                            ?>

                                            <option <?php
                                            if (!empty($edit_record[0]['deal_id']) && $edit_record[0]['deal_id'] == $row['prospect_id']) {
                                                echo 'selected="selected"';
                                            }
                                            ?>
                                                value="<?= $row['prospect_id'] ?>"><?= ucfirst($row['prospect_name']) ?></option>
                                                <?php
                                            }
                                        }
                                        ?>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>


                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 bd-togl-label">
                            <div class="form-group">
                                <div class="btn-group btn-toggle">
                                    <input checked data-toggle="toggle" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>" value="1" data-onstyle="success" type="checkbox"
                                           id="notify_team" name="notify_team" />
                                </div>
                                <label><?= lang('notify_team') ?></label>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6 bd-togl-label">
                            <div class="form-group">
                                <div class="btn-group btn-toggle">
                                    <input checked data-toggle="toggle" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"  data-onstyle="success" type="checkbox"
                                           id="notify_project_manager" name="notify_project_manager"/>
                                </div>
                                <label><?= lang('notify_project_manager') ?></label>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                <?php } ?>
                <div class="clr"></div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group">
                            <div class="mediaGalleryDiv">

                                <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"
                                        class="btn btn-primary"><?= lang('cost_placeholder_uploadlib') ?></button>
                                <div class="mediaGalleryImg">

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 no-right-pad col-lg-6 col-sm-6">
                        <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                            <div class="image_part">
                                <label name="addfile[]">
                                    <h1 style="top: -162px;">
                                        <i class="fa fa-cloud-upload"></i>
                                        <?= lang('DROP_IMAGES_HERE') ?>
                                    </h1>
                                    <input type="file" onchange="showimagepreview(this)" name="addfile[]"
                                           style="display: none" id="upl" multiple/>
                                </label>
                            </div>
                            <?php
                            if (!empty($task_files)) {
                                $img_data = $task_files;
                                $i = 15482564;
                                foreach ($img_data as $image) {
                                    $arr_list = explode('.', $image['file_name']);
                                    $arr = $arr_list[1];
                                    if (!file_exists($this->config->item('project_task_img_base_url') . $image['file_name'])) {
                                        ?>
                                                                <!--<a onclick="delete_row(<?php echo $i; ?>)"class="remove_drag_img" id="delete_row">×</a>-->
                                        <div id="img_<?php echo $image['task_file_id']; ?>" class="eachImage">
                                            <a title="<?php echo lang('delete');?>"  class="delete_file remove_drag_img" href="javascript:;"
                                               data-id="img_<?php echo $image['task_file_id']; ?>"
                                               data-href="<?php echo base_url($task_view . '/delete_file/' . $image['task_file_id']); ?>"
                                               data-name="<?php echo $image['file_name']; ?>"
                                               data-path="<?php echo $image['file_path']; ?>"
                                               >x</a> <span
                                               id="<?php echo $i; ?>" class="preview">
                                                <a href='<?php echo base_url($task_view . '/download/' . $image['task_file_id']); ?>'
                                                   target="_blank">
                                                       <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                        <img
                                                            src="<?= base_url($image['file_path']) . '/' . $image['file_name'] ?>"
                                                            width="75"/>        <?php } else { ?>
                                                        <div class="image_ext"><img
                                                                src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"
                                                                width="75"/>

                                                            <p class="img_show"><?php echo $arr; ?></p></div>
                                                    <?php } ?>
                                                </a>
                                                <p class="img_name"><?php echo $image['file_name']; ?></p>
                                                <span class="overlay" style="display: none;">
                                                    <span class="updone">100%</span></span>
                                                    <!-- <input type="hidden" value="<?php //echo $image['file_name'];
                                                    ?>" name="fileToUpload[]"> --></span>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    $i++;
                                }
                                ?>
                                <div id="deletedImagesDiv"></div>
                            <?php } ?>
                        </div>
                        <div class="clr"></div>
                    </div
                    <div class="clr">
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <input type="hidden" id="task_id" name="task_id"
                               value="<?= !empty($edit_record[0]['task_id']) ? $edit_record[0]['task_id'] : '' ?>">
                        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                        <input type="submit" <?=isset($dis)?$dis:'';?> class="btn btn-primary" name="submit_btn_task" id="submit_btn_task"
                               value="<?= $submit_button_title ?>"/>


                    </div>
                    <div class="clr"></div>
                </div>
        </form>
    </div>

</div><!-- /.modal-dialog -->
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= lang('uploads') ?></h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');"><?= lang('close') ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php //echo form_close();     ?>

<script type="text/javascript">

    //upload from library
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('#modalGallery').modal('show');
    });
    $('#description').summernote({
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        },
        focus: true
    });

    $(function () {
<?php if (isset($edit_record) && !empty($edit_record[0]["milestone_id"])) { ?>
            date_validation();//call date validation
<?php } ?>
<?php if (isset($edit_record) && isset($sub_task)) { ?>
            date_validation_sub_task();
<?php } else { ?>
            //Intialize datepicker
            var select_date = '';
    <?php if (isset($edit_record) && (strtotime(date("Y-m-d")) > strtotime($edit_record[0]["start_date"]))) { ?>
                select_date = new Date('<?= date("m/d/Y", strtotime($edit_record[0]["start_date"])) ?>');
    <?php } else { ?>
                select_date = new Date();
    <?php } ?>
            $('#task_start_date').datepicker({
                autoclose: true,
                startDate: select_date,
                endDate: '<?= date("m/d/Y", strtotime($project_detail[0]["due_date"])) ?>',
            }).on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#task_due_date').datepicker('setStartDate', startDate);
            });
            $('#task_due_date')
                    .datepicker({
                        autoclose: true, startDate: select_date,
                        endDate: '<?= date("m/d/Y", strtotime($project_detail[0]["due_date"])) ?>',
                    })
<?php } ?>
        $('.chosen-select').chosen();
        //$('.chosen-select-deselect').chosen({allow_single_deselect: true});
        //check greater than validation
        window.Parsley.addValidator('gteqt',
                function (value, requirement) {
                    return Date.parse($('#task_due_date input').val()) >= Date.parse($('#task_start_date input').val());
                }, 32)
                .addMessage('en', 'gteqt', 'This value should be <br>greater or equal to <br>start date');

        /*.on('changeDate', function(){
         $('#task_start_date').datepicker('setEndDate', new Date($(this).val()));
         });*/

        $('#from-model').parsley();//parsaley validation reload
        $('#in_project_scope').bootstrapToggle('off');
        $('#notify_team').bootstrapToggle('off');
        $('#notify_project_manager').bootstrapToggle('off');

<?php if (isset($edit_record[0]['in_project_scope']) && $edit_record[0]['in_project_scope'] == 1) { ?>
            $('#in_project_scope').bootstrapToggle('on');
<?php } ?>

<?php if (isset($edit_record[0]['notify_team']) && $edit_record[0]['notify_team'] == 1) { ?>
            $('#notify_team').bootstrapToggle('on');
<?php } ?>

<?php if (isset($edit_record[0]['notify_project_manager']) && $edit_record[0]['notify_project_manager'] == 1) { ?>
            $('#notify_project_manager').bootstrapToggle('on');
<?php } ?>

        //disabled after submit
        $('body').delegate('#submit_btn_task', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }

        });
    });
    $('#milestone_id').on('change', function () {
        $('#task_start_date,#task_due_date').datepicker('update', '');
        $('#task_start_date,#task_due_date').datepicker('remove');
        date_validation();//call date validation
    });
    $('#sub_task_id').on('change', function () {
        $('#task_start_date,#task_due_date').datepicker('update', '');
        $('#task_start_date,#task_due_date').datepicker('remove');
        date_validation_sub_task();//call date validation
    });
    //function for date viliadtion
    function date_validation() {
        var da = $('#milestone_id option:selected').attr('data-attr');
        if (da != 'undefined')
        {
            date = da.split('-');

            //Intialize datepicker
            var select_date = '';
<?php if (isset($edit_record)) { ?>
                select_date = new Date('<?= date("m/d/Y", strtotime($edit_record[0]["start_date"])) ?>');
<?php } else { ?>
                //select_date = new Date(date[0]);
                if (Date.parse('<?= date("m/d/Y") ?>') <= Date.parse(date[0]))
                {
                    select_date = new Date(date[0]);
                }
                else
                {
                    select_date = new Date();

                }
<?php } ?>
            $('#task_start_date').datepicker({
                autoclose: true,
                startDate: select_date,
                endDate: date[1],
            }).on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#task_due_date').datepicker('setStartDate', startDate);
            });
            $('#task_due_date')
                    .datepicker({
                        autoclose: true, startDate: select_date,
                        endDate: date[1],
                    })
            /*.on('changeDate', function(){
             $('#task_start_date').datepicker('setEndDate', new Date($(this).val()));
             });*/
            //project due date
            /*window.Parsley.addValidator('gteqm',
                    function (value, requirement) {
                        return Date.parse(value) <= Date.parse(date[1]);
                    }, 32)
                    .addMessage('en', 'gteqm', 'This value should be <br>less or equal to <br>milestone due date.');

            //project due date
            window.Parsley.addValidator('leeqm',
                    function (value, requirement) {
                        return Date.parse(value) >= Date.parse(date[0]);
                    }, 32)
                    .addMessage('en', 'leeqm', 'This value should be <br>greater or equal to <br>milestone start date.');*/
        }
    }
    //function for sub validation
    function date_validation_sub_task() {

        var da = $('#sub_task_id option:selected').attr('data-attr');
        if (da != 'undefined')
        {
            date = da.split('-');
            var select_date = '';
<?php if (isset($edit_record)) { ?>
                select_date = new Date('<?= date("m/d/Y", strtotime($edit_record[0]["start_date"])) ?>');
<?php } else { ?>
                select_date = new Date(date[0]);
                if (Date.parse('<?= date("m/d/Y") ?>') <= Date.parse(date[0]))
                {
                    select_date = new Date(date[0]);
                }
                else
                {
                    select_date = new Date();
                }
<?php } ?>

            $('#task_start_date').datepicker({
                autoclose: true,
                startDate: select_date,
                endDate: date[1],
            }).on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#task_due_date').datepicker('setStartDate', startDate);
            });
            $('#task_due_date')
                    .datepicker({
                        autoclose: true, startDate: select_date,
                        endDate: date[1],
                    })
            /*.on('changeDate', function(){
             $('#task_start_date').datepicker('setEndDate', new Date($(this).val()));
             });*/
            //project due date
            /*window.Parsley.addValidator('gteqst',
                    function (value, requirement) {
                        return Date.parse(value) <= Date.parse(date[1]);
                    }, 32)
                    .addMessage('en', 'gteqst', 'This value should be <br>less or equal to <br>task due date.');

            //project due date
            window.Parsley.addValidator('leeqst',
                    function (value, requirement) {
                        return Date.parse(value) >= Date.parse(date[0]);
                    }, 32)
                    .addMessage('en', 'leeqst', 'This value should be <br>greater or equal to <br>task start date.');*/
        }
    }
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
    //reload modal

</script>
<script type="text/javascript">
    var config = {
        //support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",       // Valid file formats
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo $task_view; ?>/upload_file"              // Server side upload url
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
                    var delete_meg ="<?php echo $this->lang->line('file');?> \"" + file.name + "\" <?php echo $this->lang->line('too_big_size');?>.";
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
            xhr[rand].open("post", "<?php echo base_url('/Projectmanagement/ProjectTask/file_upload') ?>/" + fileext, true);

            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                    $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
                }
                else {
                    var delete_meg ="<?php echo $this->lang->line('fail_file_upload');?>";
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
    function showimagepreview(input) {
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
        });
        var maximum = input.files[0].size / 1024;

    }
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });

</script>