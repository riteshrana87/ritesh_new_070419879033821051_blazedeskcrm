<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertdata';
$formPath = $task_view . '/' . $formAction;
$imgUrl = base_url('uploads/images/mark-johnson.png');
//pr($memberlist);
?>

<div class="modal-dialog .bs-addsubtask-modal modal-lg" id="ViewTaskDialog" >
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><b><?php echo!empty($edit_record[0]['task_name']) ? $edit_record[0]['task_name'] : '' ?> </b> </h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12 col-sm-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12  bottom-gray-border mar_b6">
                            <div class="col-lg-7 col-xs-12 col-sm-6 col-md-7">
                                <div class="row"> <div class="col-md-6"> <h4 ><?= lang('pm_details') ?></h4>
                                        <?php if (count($project_manager) > 0) { ?>
                                            <div class="row">
                                                <div class="col-lg-10 col-xs-12 col-sm-10 col-md-10">

                                                    <div class=" mar_b6">
                                                        <div class="row">
                                                            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
                                                                <div class="text-center"><img src="<?php echo ($project_manager[0]['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $project_manager[0]['profile_photo'])) : $imgUrl; ?>" alt="" class="img-responsive"/></div>
                                                            </div>
                                                            <div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 "><span class="font-15em"><b><?php echo $project_manager[0]['full_name']; ?></b></span> <br>
                                                                <span class="font-1em"><?php echo str_replace('_', ' ', $project_manager[0]['role_name']); ?></span> </div>
                                                            <div class="clr"></div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="pull-right col-lg-4 text-right"> 
                                                    <!--                                            <button type="button" class="btn btn-default mar_b6">Add Team Member</button>
                                                                                                                        <button type="button" class="btn btn-default">Edit Team Member</button>--> 
                                                </div>
                                            </div>
                                        <?php } ?></div>
                                    <div class="col-md-6"> <h4 ><?= lang('team_member') ?></h4>
                                        <?php if (count($memberlist) > 0) {
                                            foreach ($memberlist as $member) {
                                                ?>
                                                <div class="row">
                                                    <div class="col-lg-10 col-xs-12 col-sm-10 col-md-10">

                                                        <div class=" mar_b6">
                                                            <div class="row">
                                                                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
                                                                    <div class="text-center"><img src="<?php echo ($member['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $member['profile_photo'])) : $imgUrl; ?>" alt="" class="img-responsive"/></div>
                                                                </div>
                                                                <div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 "><span class="font-15em"><b><?php echo $member['firstname'] . ' ' . $member['lastname']; ?></b></span> <br>
                                                                    <span class="font-1em"><?php echo str_replace('_', ' ', $member['role_name']); ?></span> </div>
                                                                <div class="clr"></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="pull-right col-lg-4 text-right"> 
                                                        <!--                                            <button type="button" class="btn btn-default mar_b6">Add Team Member</button>
                                                                                                                            <button type="button" class="btn btn-default">Edit Team Member</button>--> 
                                                    </div>
                                                </div>
    <?php }
} ?></div>
                                    <div class="clr"></div></div>
                            </div>
                            <div class="col-lg-5 col-xs-12 col-sm-6 col-md-5 border-left-grey">
                                <div class="form-group row">
                                    <label class="bd-txt-undr col-xs-6 col-lg-6 col-sm-6 "><span><?php echo!empty($edit_record[0]['task_name']) ? $edit_record[0]['task_name'] : '' ?> </span><br/>
                                                                              <?= !empty($edit_record[0]['milestone_name']) ? $edit_record[0]['milestone_name'] : '' ?></label>
                                    <div class="col-xs-6 col-lg-6"> <span class="color_box"
                                                                          style="color:#FFF;background-color:<?= $edit_record[0]['status_color'] ?>">
<?= !empty($edit_record[0]['status_name']) ? $edit_record[0]['status_name'] : '' ?> 
                                        </span> </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="form-group row bd-form-group">
                                    <label class="col-lg-6 col-xs-6"><?= lang('creation_date') ?></label>
                                    <div class="col-md-6 col-xs-6 no-left-pad ">
                                        <div class="bd-sm-control">
                                            <div id="datepicker1" class="input-group date">
                                                <p><?php echo configDateTime($edit_record[0]['created_date']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="form-group bd-form-group row">
                                    <label class="col-lg-6 col-xs-6"><?= lang('start_date') ?></label>
                                    <div class="col-md-6 col-xs-6 no-left-pad ">
                                        <div class="bd-sm-control">
                                            <div id="datepicker2" class="input-group date">

                                                <p><?php
                                                    if (isset($edit_record[0]['start_date']) && $edit_record[0]['start_date'] != '0000-00-00') {
                                                        echo configDateTime($edit_record[0]['start_date']);
                                                    };
                                                    ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="form-group bd-form-group row">
                                    <label class="col-lg-6 col-xs-6"><?= lang('due_date') ?></label>
                                    <div class="col-md-6 col-xs-6 no-left-pad ">
                                        <div class="bd-sm-control">
                                            <div id="datepicker16" class="input-group date ">
                                                <p><?php
                                                    if (isset($edit_record[0]['due_date']) && $edit_record[0]['due_date'] != '0000-00-00') {
                                                        echo configDateTime($edit_record[0]['due_date']);
                                                    };
                                                    ?></p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7 col-xs-12 col-sm-6 col-md-7">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label><?= lang('description') ?></label>
                                    <p class="col-lg-12"><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></p>
                                </div>
                                <div class="form-group row">
                                    <label><?= lang('deal_name') ?></label>
                                    <p><?= !empty($edit_record[0]['prospect_name']) ? $edit_record[0]['prospect_name'] : '' ?></p>
                                </div>
                                <div class="form-group row">
                                    <label class="mb15"><?= lang('attachments') ?></label>
                                    <div class="row">
                                        <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
                                            <div class="row">
                                                <div class="col-lg-12 col-xs-12 col-sm-12 col-md-12">
                                                    <?php
                                                    $cost_files = $task_files;
                                                    if (count($cost_files) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                                        $i = 15482564;
                                                        foreach ($cost_files as $image) {
                                                            $path = $image['file_path'];
                                                            $name = $image['file_name'];
                                                            $arr_list = explode('.', $name);
                                                            $arr = $arr_list[1];
                                                            if (file_exists($path . $name)) {
                                                                ?>
                                                                <div id="img_<?php echo $image['task_file_id']; ?>" class="text-center col-lg-3 col-xs-12 col-sm-6 col-md-6 bd-projtask-viewimg"> 
                                                                    <span id="<?php echo $i; ?>" class="preview">
                                                                            <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                                            <a class="task-previmg" title="<?php echo lang('download'); ?>" href='<?php echo base_url($task_view . '/download/' . $image['task_file_id']); ?>' >
                                                                                <?php } else { ?>
                                                                                <a title="<?php echo lang('download'); ?>" target="_blank" href='<?php echo base_url($task_view . '/download/' . $image['task_file_id']); ?>' >
                                                                                <?php } ?>
                                                                                <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                                                    <img src="<?= base_url($path . $name); ?>" class="img-responsive" />
            <?php } else { ?>
                                                                                    <div class="image_part"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png" class="img-responsive"/>
                                                                                        <p class="img_show"><?php echo $arr; ?></p>
                                                                                    </div>
                                                                                <?php } ?>
                                                                            </a>
                                                                            <p class="img_name"> <span><?php echo (strlen($name) > 15) ? substr($name, 0, 15) . '...' : $name; ?></span>
                                                                                <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                                                    <?php /* <button class="btn btn-default" onclick='showPreview("<?php echo base_url($image['file_path'] . '/' . $image['file_name']); ?>");'><i class="fa fa-search"></i></button> */ ?>
            <?php } ?>
            <?php /* <a href='<?php echo base_url($task_view . '/download/' . $image['task_file_id']); ?>' target="_blank" class="btn btn-default"><i class="fa fa-download "></i></a> <span class="overlay" style="display: none;"> <span class="updone">100%</span></span> */ ?> 
                                                                              <!--                                                <input type="hidden" value="<?php echo $name; ?>" name="fileToUpload[]">--> 
                                                                                </span> 
                                                                                </div>
                                                                            <?php } ?>
                                                                            <?php
                                                                            $i++;
                                                                        }
                                                                        ?>
<?php } ?>
                                                                    </div>
                                                                    <!--                                                    <div class="col-lg-4 text-right">
                                                                                                                                                    <button class="btn btn-primary mar_b6">Select attachment</button>
                                                                                                                                                    <label class="custom-upload btn btn-primary">
                                                                                                                                                        <input type="file" name="upload_file">
                                                                                                                                                        Choose file</label>
                                                                                                                                                </div>-->
                                                                <div class="clr"></div>
                                                                </div>
                                                                </div>
                                                                <div class="clr"></div>
                                                                </div>
                                                                </div>
                                                                </div>
                                                                <div class="clr"></div>
                                                                </div>
                                                                <div class="col-lg-5 col-xs-12 col-sm-6 col-md-5 mar-tb5 border-left-grey">
<?php if (count($sub_tasks) > 0) { ?>
                                                                        <div class="form-group bottom-gray-border">
                                                                            <label><?= lang('subtasks') ?></label>
                                                                            <ul class="bd-task-list">
                                                                                            <?php foreach ($sub_tasks as $task) { ?>
                                                                                    <li><a href="javascript:;" onclick="gotoSubTask('<?php echo base_url('Projectmanagement/ProjectTask/view_record/' . $task['task_id']); ?>')"><?php echo $task['task_name']; ?> <span class="orangecol" style="color:<?= $task['status_color'] ?>">-
                                                                                    <?= $task['status_name'] ?>
                                                                                            </span> </a></li>
                                                                        <?php } ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <?php
                                                                    //  pr($sub_tasks);
                                                                    $ptask=$this->TeamMembers_model->getTaskDetails($edit_record[0]['sub_task_id']);
                                                                    if (count($ptask) > 0) {
                                                                        ?>
                                                                        <div class="form-group bottom-gray-border">
                                                                            <label><?= lang('parenttask') ?></label>
                                                                            <ul class="bd-task-list">
                                                                                <li><a href="javascript:;" onclick="gotoSubTask('<?php echo base_url('Projectmanagement/ProjectTask/view_record/' . $ptask[0]['task_id']); ?>')"><?php echo $ptask[0]['task_name']; ?>
<!--                                                                                        <span class="orangecol" style="color:<?php //$ptask[0]['status_color'] ?>"><?php //$ptask[0]['status_name'] ?></span>-->
                                                                                    
                                                                                    </a></li>                                                                            </ul>
                                                                        </div>
<?php } ?>

                                                                    <!--                            <div class="form-group bottom-gray-border ">
                                                                                                                  <button class="btn btn-default">Start/Stop Timer</button>
                                                                                                                  <span class="col-lg-5 pull-right font-2em"><b>HH : MM </b></span> </div>-->
                                                                    <div class="bottom-gray-border mb15">
                                                                        <div class="form-group bd-form-group row">
                                                                            <label class="col-lg-6 col-xs-6"><?= lang('started_work') ?></label>
                                                                            <div class="col-md-8 col-xs-6 col-lg-6 no-left-pad">
                                                                                <div class="bd-sm-control">
                                                                                    <div class="input-group date" id="datepicker11">
                                                                                        <p><?php
                                                                                            if (isset($edit_record[0]['created_date']) && $edit_record[0]['created_date'] != '0000-00-00') {
                                                                                                echo configDateTime($edit_record[0]['created_date']);
                                                                                            };
                                                                                            ?></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="clr"></div>
                                                                        </div>
<?php if ($edit_record[0]['status'] == 4) { ?>
                                                                            <div class="form-group bd-form-group">
                                                                                <label class="col-lg-6 col-xs-6"><?= lang('finished_work') ?></label>
                                                                                <div class="col-md-8 col-xs-6 col-lg-6 no-left-pad">
                                                                                    <div class="bd-sm-control">
                                                                                        <div class="input-group date" id="datepicker12">
                                                                                            <p><?php
                                                                                                if (isset($edit_record[0]['modified_date']) && $edit_record[0]['modified_date'] != '0000-00-00') {
                                                                                                    echo configDateTime($edit_record[0]['modified_date']);
                                                                                                };
                                                                                                ?></p>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="clr"></div>
                                                                            </div>
<?php } ?>
                                                                    </div>
<?php if (count($time_sheets) > 0) { ?>
                                                                        <div class="form-group bottom-gray-border">
                                                                            <div class="form-group"> 
                                                                                <!--                                        <button class="btn btn-default pull-right">Generate Report</button>-->
                                                                                <label class="col-lg-5"><?= lang('work_diary') ?></label>
                                                                                <div class="clr"></div>
                                                                            </div>
    <?php
    foreach ($time_sheets as $sheet) {
        ?>
                                                                                <div class="col-lg-12">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="row">
                                                                                            <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                                                                                <div class="text-center"><img src="<?php echo ($sheet['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $sheet['profile_photo'])) : $imgUrl; ?>" alt="" class="img-responsive"/></div>
                                                                                            </div>
                                                                                            <div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 "><span class="font-15em"><b><?php echo $sheet['username']; ?></b></span> <br>
                                                                                                <span class="font-1em"><?php echo $sheet['role_name']; ?></span> </div>
                                                                                            <div class="clr"></div>
                                                                                        </div>
                                                                                        <label class="orange-label"><?php echo round($sheet['total_spent_user'], 2); ?> Hours</label>
                                                                                    </div>
                                                                                    <div class="col-lg-6">
                                                                                        <div class="bd-taskduratn"> <span><?= lang('started_work') ?></span>
                                                                                            <p><?php
                                                                                                if (isset($sheet['created_date']) && $sheet['created_date'] != '0000-00-00') {
                                                                                                    echo configDateTime($sheet['created_date']);
                                                                                                };
                                                                                                ?></p>
                                                                                        </div>
                                                                                                <?php if ($edit_record[0]['status'] == 4) { ?>
                                                                                            <div class="bd-taskduratn"> <span><?= lang('finished_work') ?></span>
                                                                                                <p>
                                                                                                    <?php
                                                                                                    if (isset($edit_record[0]['modified_date']) && $edit_record[0]['modified_date'] != '0000-00-00') {
                                                                                                        echo configDateTime($edit_record[0]['modified_date']);
                                                                                                    };
                                                                                                    ?></p>
                                                                                            </div>
                                                                                <?php } ?>
                                                                                    </div>
                                                                                    <div class="clr"></div>
                                                                                </div>
                                                                        <?php } ?>
                                                                            <div class="clr"></div>
                                                                        </div>
<?php } else { ?>
                                                                        <div class="form-group">
                                                                            <label><?= lang('no_timesheet_filled_yet') ?></label>
                                                                            <div class="clr"></div>
                                                                        </div>
<?php } ?>
<?php if (count($time_sheets) > 0) { ?>
                                                                        <div class="form-group ">
                                                                            <div class="col-lg-6 bd-tot-durtn">
                                                                                <label><?= lang('total_time_spent') ?></label>
                                                                                <label class="orange-label"><?php echo round($time_sheets[0]['total_spent'], 2); ?> <?= lang('hours') ?></label>
                                                                            </div>
                                                                        </div>
<?php } ?>
                                                                </div>
                                                                <div class="clr"></div>
                                                                </div>
                                                                </div>
                                                                <div class="clr"></div>
                                                                </div>
                                                                </div>
                                                                <div class="modal-footer"> 
                                                                    <!--                <div class="text-center">
                                                                                              <button class="btn btn-green">Add Task</button>
                                                                                              &nbsp;&nbsp; or &nbsp;&nbsp;
                                                                                              <button class="btn btn-green">+ Add Another Task</button>
                                                                                          </div>-->
                                                                    <div class="clr"> </div>
                                                                </div>
                                                                </div>
                                                                </div>
<?php //echo form_close();        ?>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="imgviewpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" onclick="$('#imgviewpopup').modal('hide');"  aria-hidden="true">&times;</button>
                                                                                <h4 class="modal-title" id="myModalLabel">Preview</h4>
                                                                            </div>
                                                                            <div class="modal-body"> <img id="previewImg" class="img-responsive" alt="no-img"> </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default"  onclick=" $('#imgviewpopup').modal('hide');">Close</button>
                                                                            </div>
                                                                        </div>
                                                                        <!-- /.modal-content --> 
                                                                    </div>
                                                                    <!-- /.modal-dialog --> 
                                                                </div>
                                                                <!-- /.modal --> 
                                                                <script>
                                                                    function showPreview(elurl)
                                                                    {
                                                                        $('#previewImg').attr('src', elurl);
                                                                        $('#imgviewpopup').modal('show');
                                                                    }
                                                                    $('#imgviewpopup').on('hidden.bs.modal', function () {
                                                                        $('body').addClass('modal-open');
                                                                    });
                                                                </script>