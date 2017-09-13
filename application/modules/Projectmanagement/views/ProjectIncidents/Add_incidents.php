<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath = $project_incident_view . '/' . $formAction;
?>    
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <form id="from-model" method="post" action="<?php echo base_url($formPath); ?>" enctype="multipart/form-data" data-parsley-validate>
            <!-- Modal content-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><div class="modelMilestoneTitle"><?= $modal_title ?></div></h4>
            </div>

            <div class="modal-body">
                <div class = " row">
                    <div class = "col-sm-6 form-group">
                        <label class="control-label"><?= lang('title') ?> <span class="viewtimehide">*</span> </label>
                        <input type="text" maxlength="100"  class="form-control" placeholder="<?= lang('title') ?>" id="title" name="title" value="<?= !empty($edit_record[0]['title']) ? $edit_record[0]['title'] : '' ?>" required>
                    </div>

                    <div class="col-lg-6 col-sm-6 form-group">
                        <label class="  control-label"><?= lang('type') ?> <span class="viewtimehide">*</span></label>     
                        <select tabindex="-1" id="type_id" name="type_id" data-parsley-errors-container="#salution-errors"  class="chosen-select" data-placeholder="<?= lang('selecting_type') ?>" required>
                            <option value=""></option>
                            <?php
                            if (!empty($type_data)) {
                                foreach ($type_data as $row) {
                                    ?>
                                    <option <?php
                                    if (!empty($edit_record[0]['type_id']) && ($edit_record[0]['type_id'] == $row['incident_type_id'])) {
                                        echo 'selected="selected"';
                                    }
                                    ?> value="<?= $row['incident_type_id'] ?>"><?= ucfirst($row['incident_type_name']) ?></option>
                                        <?php
                                    }
                                }
                                ?>
                        </select>
                        <div id="salution-errors"></div>
                    </div>
                </div>

                <?php /* <div class = "form-group row">
                  <div class = "col-sm-6">
                  <label class="control-label"><?=lang('BUSINESS_CASES')?> : </label>
                  <input type="text" class="form-control" placeholder="<?=lang('BUSINESS_CASES')?>" id="business_cases" name="business_cases" value="<?= !empty($edit_record[0]['business_cases']) ? $edit_record[0]['business_cases'] : '' ?>" required>
                  </div>

                  <div class = "col-sm-6">
                  <label class="control-label"><?=lang('CASE_SUBJECT')?>  : </label>
                  <input type="text" class="form-control" placeholder="<?=lang('CASE_SUBJECT')?>" id="business_subject" name="business_subject" value="<?= !empty($edit_record[0]['business_subject']) ? $edit_record[0]['business_subject'] : '' ?>" required>
                  </div>

                  </div> */ ?>

                <div class = " row">
                    <div class = "col-sm-6 form-group">
                        <label class="control-label"><?= lang('RESPONSIBLE') ?> <span class="viewtimehide">*</span> </label>
                        <select class="chosen-select" name="responsible" id="responsible" data-parsley-errors-container="#responsible-errors" required>
                            <option value="<?php echo $this->session->userdata('LOGGED_IN')['ID']; ?>"><?= lang('select') ?> <?= lang('RESPONSIBLE') ?></option>
                            <?php
                            if (!empty($res_user)) {
                                foreach ($res_user as $row) {
                                    ?>
                                    <option <?php
                                    if (!empty($edit_record[0]['responsible']) && $edit_record[0]['responsible'] == $row['login_id']) {
                                        echo 'selected="selected"';
                                    }
                                    ?>
                                        value="<?= $row['login_id'] ?>"><?= ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                        <?php
                                    }
                                }
                                ?>
                        </select>

                        <div id="responsible-errors"></div>
                    </div>

                    <div class = "col-sm-6 form-group">
                        <label class="control-label"><?= lang('DEADLINE') ?> <span class="viewtimehide">*</span> </label>

                        <input type="text" class="form-control" placeholder="<?= lang('DEADLINE') ?>" id="deadline" name="deadline" value="<?php
                        if (isset($edit_record[0]['deadline'])) {
                            echo configDateTime($edit_record[0]['deadline']);
                        } else {
                            echo configDateTime();
                        }
                        ?>" required>
                    </div>

                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="control-label"><?= lang('description') ?>  </label>
                        <textarea rows="2" name="description" id="description"  placeholder="<?= lang('description') ?>" class="form-control"><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></textarea>
                    </div>
                </div>

                <div class=" row">
                    <div class = "col-sm-6 form-group">
                        <label class="control-label"><?= lang('INCIDENT_STATUS') ?> <span class="viewtimehide">*</span></label>
                        <select class="chosen-select" name="incident_status" id="incident_status" data-parsley-errors-container="#incodent-errors" required>
                            <option value=""><?= lang('SELECT_INCIDENT_STATUS') ?></option>
                            <option value="1" <?php
                        if (isset($edit_record[0]['incident_status']) && $edit_record[0]['incident_status'] == '1') {
                            echo "selected='selected'";
                        }
                        ?>>In Process</option>
                            <option value="2" <?php
                            if (isset($edit_record[0]['incident_status']) && $edit_record[0]['incident_status'] == '2') {
                                echo "selected='selected'";
                            }
                        ?>>On Hold</option>

                        </select>
                        <div id="incodent-errors"></div>
                    </div>

                </div>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="mediaGalleryDiv">

                                <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"
                                        class="btn btn-primary"><?= lang('cost_placeholder_uploadlib') ?></button>
                                <div class="mediaGalleryImg">

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">

                        <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                            <div class="image_part">
                                <label name="addfile[]">
                                    <h1 style="top: -162px;">
                                        <i class="fa fa-cloud-upload"></i>
                                        <?= lang('DROP_IMAGES_HERE') ?>
                                    </h1>
                                    <input type="file" onchange="showimagepreview(this)" name="addfile[]" style="display: none" id="upl" multiple />
                                </label>
                            </div>
                            <?php
                            if (!empty($incidentds_files)) {
                                $img_data = $incidentds_files;
                                $i = 15482564;
                                foreach ($img_data as $image) {
                                    $arr_list = explode('.', $image['file_name']);
                                    $arr = $arr_list[1];
                                    if (!file_exists($this->config->item('project_incidents_img_base_url') . $image['file_name'])) {
                                        ?>
                                                                                            <!--<a onclick="delete_row(<?php echo $i; ?>)"class="remove_drag_img" id="delete_row">×</a>-->
                                        <div id="img_<?php echo $image['incident_file_id']; ?>" class="eachImage">
                                            <a title="<?php echo lang('delete'); ?>" class="delete_file remove_drag_img" href="javascript:;"
                                               data-id="img_<?php echo $image['incident_file_id']; ?>"
                                               data-href="<?php echo base_url($project_incident_view . '/delete_file/' . $image['incident_file_id']); ?>"
                                               data-name="<?php echo $image['file_name']; ?>" data-path="<?php echo $image['file_path']; ?>"
                                               >x</a>
                                            <span id="<?php echo $i; ?>" class="preview">
                                                <a href='<?php echo base_url($project_incident_view . '/download/' . $image['incident_file_id']); ?>' target="_blank">
                                                    <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>                                <img src="<?= base_url($image['file_path']) . '/' . $image['file_name'] ?>"  width="75"/>        <?php } else { ?>
                                                        <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/><p class="img_show"><?php echo $arr; ?></p></div>
                                                    <?php } ?>
                                                </a>
                                                <p class="img_name"><?php echo $image['file_name']; ?></p>
                                                <span class="overlay" style="display: none;">
                                                    <span class="updone">100%</span></span>
                                                    <!-- <input type="hidden" value="<?php //echo $image['file_name']; ?>" name="fileToUpload[]"> --></span>
                                        </div>
                                    <?php } ?>
                                    <?php
                                    $i++;
                                }
                                ?>
                                <div id="deletedImagesDiv"></div>
                            <?php } ?>
                        </div>
                        <div class="clr"> </div>
                    </div
                    <div class="clr">
                    </div>

                </div>

                <div class="modal-footer">
                    <center> 
                        <input type="hidden" id="incident_id" name="incident_id"  value="<?= !empty($edit_record[0]['incident_id']) ? $edit_record[0]['incident_id'] : '' ?>">
                        <input type="hidden" id="display" name="display"  value="<?= !empty($home) ? $home : '' ?>">
                        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                        <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?= $submit_button_title ?>" />
                    </center>

                </div>
        </form>
    </div>

</div>
<!-- /.modal-dialog -->

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
<?php //echo form_close();     ?>
<script type="text/javascript">
    //image
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
        //$('.chosen-select-deselect').chosen({ allow_single_deselect: true });

        $('#from-model').parsley();//parsaley validation reload
        $('#deadline').datepicker({
            autoclose: true,
            startDate: '-0m'
        })
        //disabled after submit
        $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }
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
        var delete_meg = "<?= lang('common_delete_file') ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                                $('#' + divId).remove();
                                /*$.ajax({
                                 url: dataUrl,
                                 type: 'GET',
                                 dataType: "json",
                                 success: function (data)
                                 {
                                 if (data.status == 1)
                                 {
                                 $('#' + divId).remove();
                                 }
                                 else
                                 {
                                 alert(data.error);
                                 }
                                 },
                                 error: function ()
                                 {
                                 console.log('Error in call');
                                 }
                                 
                                 });*/
                                $('#confirm-id').on('hidden.bs.modal', function () {
                                    $('body').addClass('modal-open');
                                });
                                dialog.close();
                            }

                        }]
                });
    });
</script>
<!-- Upload image script -->
<script type="text/javascript">
    var config = {
        //support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",       // Valid file formats
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo $project_incident_view; ?>/upload_file"              // Server side upload url
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
            xhr[rand].open("post", "<?php echo base_url('/Projectmanagement/ProjectIncidents/file_upload') ?>/" + fileext, true);

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
    function showimagepreview(input)
    {
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
    //multiple popup
    $('#modalGallery,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
</script>
