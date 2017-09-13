<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath = $milestone_view . '/' . $formAction;
?>
<div class="modal-dialog">
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
            <div class='alert alert-danger text-center'><?=lang('project_over_milestone_message')?></div>
            <?php $dis="disabled";} ?>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="<?= lang('milestone_name') ?> *"
                                   id="milestone_name" maxlength="100" name="milestone_name"
                                   value="<?= !empty($edit_record[0]['milestone_name']) ? $edit_record[0]['milestone_name'] : '' ?>"
                                   required>
                        </div>

                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group">
                            <input type="text" readonly class="form-control"
                                   placeholder="M###(<?= lang('auto_generated_number') ?>)" id="milestone_code"
                                   name="milestone_code"
                                   value="<?= !empty($edit_record[0]['milestone_code']) ? $edit_record[0]['milestone_code'] : $milestone_code ?>">
                        </div>
                    </div>
                    <div class="clearfix visible-xs-block"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="form-group">
                            <select tabindex="-1" name="res_user" data-parsley-errors-container="#res_err"
                                    class="chosen-select"
                                    data-placeholder="<?= lang('responsible_employee') ?> *" required>
                                <option value=""></option>
                                <?php
                                if (!empty($res_user)) {
                                    foreach ($res_user as $row) {
                                        ?>
                                        <option <?php
                                        if (!empty($edit_record[0]['res_user']) && $edit_record[0]['res_user'] == $row['login_id']) {
                                            echo 'selected="selected"';
                                        }
                                        ?>
                                            value="<?= $row['login_id'] ?>"><?= ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                            </select>
                            <span id="res_err"></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="row form-group">
                            <div class="col-xs-12 col-md-6 pad-t6 col-lg-6 col-sm-6">
                                <label><?= lang('creation_date') ?></label>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                                <div class="input-group date full-width" id="datepicker6">
                                    <input type="text" onkeydown="return false" class="form-control"
                                           placeholder="<?php echo lang('cost_placeholder_createddate') ?>"
                                           id="created_date" name="created_date" value="<?php
                                           if (isset($edit_record[0]['created_date'])) {
                                               echo configDateTime($edit_record[0]['created_date']);
                                           } else {
                                               echo configDateTime();
                                           }
                                           ?>">
                                    <!-- <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <label class="  control-label"><?= lang('team_member') ?> <span class="viewtimehide">*</span></label>

                        <div class="form-group">
                            <select tabindex="-1" multiple="multiple" id="team_member" name="team_member[]"
                                    class="chosen-select" data-parsley-errors-container="#team_err"
                                    data-placeholder="<?= lang('choose_team') ?>" required>
                                <option value=""></option>
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
                            <span id="team_err"></span>
                        </div>
                    </div>


                    <div class="clr"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 pad-top10 col-lg-4 col-sm-6">
                                <label> <?= lang('start_date') ?> <span class="viewtimehide">*</span></label>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-8 col-sm-6">


                                <div class="input-group date" id="start_date">
                                    <input type='text' onkeydown="return false" data-parsley-errors-container="#st_err"
                                           class="form-control" name="start_date" id="start_date"
                                           placeholder="<?= lang('start_date') ?>"
                                           value="<?php
                                           if (isset($edit_record[0]['start_date']) && $edit_record[0]['start_date'] != '0000-00-00') {
                                               echo configDateTime($edit_record[0]['start_date']);
                                           };
                                           ?>" required/>
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                                </div>
                                <span id="st_err"></span>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
                        <div class="row">
                            <div class="col-xs-12 col-md-6 pad-top10 col-lg-4 col-sm-6">
                                <label> <?= lang('due_date') ?> <span class="viewtimehide">*</span></label>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-8 col-sm-6">
                                <div class="input-group date" id="due_date">
                                    <input type='text' onkeydown="return false" data-parsley-errors-container="#ed_err" id="due_date"
                                           class="form-control" name="due_date"
                                           placeholder="<?= lang('due_date') ?>"
                                           value="<?php
                                           if (isset($edit_record[0]['due_date']) && $edit_record[0]['due_date'] != '0000-00-00') {
                                               echo configDateTime($edit_record[0]['due_date']);
                                           };
                                           ?>" data-parsley-gteqp="#due_date" data-parsley-gteq="#start_date" required/>
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                                </div>
                                <span id="ed_err"></span>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
                <br>

                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <textarea name="description" placeholder="<?= lang('description') ?>" id="description"
                                      class="form-control"><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="  control-label"><?= lang('selecting_task') ?> </label>
                        <select tabindex="-1" name="task_id[]" multiple="multiple" class="chosen-select"
                                data-placeholder="<?= lang('selecting_task') ?>">
                            <option value=""></option>
                            <?php
                            if (!empty($task_data)) {
                                foreach ($task_data as $row) {
                                    ?>
                                    <option <?php
                                    if (!empty($task_id) && in_array($row['task_id'], $task_id)) {
                                        echo 'selected="selected"';
                                    }
                                    ?> value="<?= $row['task_id'] ?>"><?= ucfirst($row['task_name']) ?></option>
                                        <?php
                                    }
                                }
                                ?>
                        </select>
                    </div>
                </div>
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
                            if (!empty($milestone_files)) {
                                $img_data = $milestone_files;
                                $i = 15482564;
                                foreach ($img_data as $image) {
                                    $arr_list = explode('.', $image['file_name']);
                                    $arr = $arr_list[1];
                                    if (!file_exists($this->config->item('milestone_img_base_url') . $image['file_name'])) {
                                        ?>
                                                                <!--<a onclick="delete_row(<?php echo $i; ?>)"class="remove_drag_img" id="delete_row">×</a>-->
                                        <div id="img_<?php echo $image['milestone_file_id']; ?>" class="eachImage">
                                            <a title="<?php echo lang('delete');?>"  class="delete_file remove_drag_img" href="javascript:;"
                                               data-id="img_<?php echo $image['milestone_file_id']; ?>"
                                               data-href="<?php echo base_url($milestone_view . '/delete_file/' . $image['milestone_file_id']); ?>"
                                               data-id="img_<?php echo $image['milestone_file_id']; ?>"
                                               data-name="<?php echo $image['file_name']; ?>"
                                               data-path="<?php echo $image['file_path']; ?>"
                                               >x</a> <span
                                               id="<?php echo $i; ?>" class="preview">
                                                <a href='<?php echo base_url($milestone_view . '/download/' . $image['milestone_file_id']); ?>'
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
                        <input type="hidden" id="milestone_id" name="milestone_id"
                               value="<?= !empty($edit_record[0]['milestone_id']) ? $edit_record[0]['milestone_id'] : '' ?>">
                        <input type="hidden" id="display" name="display" value="<?= !empty($home) ? $home : '' ?>">

                        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                        <input type="submit" <?=isset($dis)?$dis:'';?> class="btn btn-primary" name="submit_btn" id="submit_btn"
                               value="<?= $submit_button_title ?>"/>


                    </div>
                    <div class="clr"></div>
                </div>
        </form>
    </div>

</div><!-- /.modal-dialog -->
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Uploads</h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php //echo form_close();   ?>
<script type="text/javascript">
    //upload from library
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('#modalGallery').modal('show');
    });
    //editor
    $('#description').summernote({
        height: 150, //set editable area's height
        codemirror: {// codemirror options
            theme: 'monokai'
        },
        focus: true
    });

    $(function () {

        $('.chosen-select').chosen();
        window.Parsley.addValidator('gteq',
                function (value, requirement) {
                    return Date.parse($('#due_date input').val()) >= Date.parse($('#start_date input').val());
                }, 32)
                .addMessage('en', 'gteq', '<?php echo $this->lang->line('should_be_less_or_equal');?>');
        //project due date
        window.Parsley.addValidator('gteqp',
                function (value, requirement) {
                    return Date.parse(value) <= Date.parse('<?= date("m/d/Y", strtotime($project_detail[0]["due_date"])) ?>');
                }, 32)
                .addMessage('en', 'gteqp', '<?php echo $this->lang->line('should_be_less_or_equal');?>.');
        //Intialize datepicker
        var select_date = '';
<?php if (isset($edit_record) && (strtotime(date("Y-m-d")) > strtotime($edit_record[0]["start_date"]))) { ?>
            select_date = new Date('<?= date("m/d/Y", strtotime($edit_record[0]["start_date"])) ?>');
<?php } else { ?>
            if (Date.parse('<?= date("m/d/Y") ?>') <= Date.parse('<?= date("m/d/Y", strtotime($project_detail[0]["start_date"])) ?>')) {
                select_date = new Date('<?= date("m/d/Y", strtotime($project_detail[0]["start_date"])) ?>');
            }
            else {
                select_date = new Date();
            }

<?php } ?>

        $('#start_date').datepicker({
            autoclose: true,
            startDate: select_date,
            endDate: '<?= date("m/d/Y", strtotime($project_detail[0]["due_date"])) ?>',
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('#due_date').datepicker('setStartDate', startDate);
        });
        $('#due_date')
                .datepicker({
                    autoclose: true, startDate: select_date,
                    endDate: '<?= date("m/d/Y", strtotime($project_detail[0]["due_date"])) ?>'
                })
        /*.on('changeDate', function(){
         $('#start_date').datepicker('setEndDate', new Date($(this).val()));
         });*/
        $('#from-model').parsley();//parsaley validation reload

        //disabled after submit
        $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }
        });
    });
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

</script>

<script type="text/javascript">
    var config = {
        //support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",		// Valid file formats
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo $milestone_view; ?>/upload_file"				// Server side upload url
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
            xhr[rand].open("post", "<?php echo base_url('/Projectmanagement/Milestone/file_upload') ?>/" + fileext, true);

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