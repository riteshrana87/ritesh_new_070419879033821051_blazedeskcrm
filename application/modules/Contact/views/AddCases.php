<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertCases';
$formPath =   $contact_view.'/'.$formAction;
?>    
<div class="modal-dialog modal-lg">
    <div class="modal-content costmodaldiv">
        <form id="from-model" method="post" action="<?php echo base_url($formPath); ?>" enctype="multipart/form-data" data-parsley-validate>
            <!-- Modal content-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><div class="modelMilestoneTitle"><?= $modal_title ?></div></h4>
            </div>

            <div class="modal-body">
                <div class = " row">
                    <div class = "col-lg-6 col-sm-6 col-md-6 col-sm-6 col-xs-12 form-group">
                        <label class="control-label"><?= lang('title') ?>*</label>
                        <input type="text" maxlength="100"  class="form-control" placeholder="<?= lang('title') ?>" id="title" name="title" value="<?= !empty($edit_record[0]['title']) ? htmlentities($edit_record[0]['title']) : '' ?>" required>
                    </div>
                    
                    <div class="col-lg-6 col-sm-6 col-md-6 col-sm-6 col-xs-12 form-group">
                        <label class="control-label"><?= lang('case_type') ?>*</label>     
                        <select tabindex="-1" id="type_id" name="type_id" data-parsley-errors-container="#salution-errors"  class="chosen-select" data-placeholder="<?= lang('select_case_type') ?>" required>
                            <option value=""></option>
                            <?php
                            if (!empty($cases_type_data)) {
                                foreach ($cases_type_data as $row) {
                                    ?>
                                    <option <?php if (!empty($edit_record[0]['cases_type_id']) && ($edit_record[0]['cases_type_id'] == $row['cases_type_id'])) {
                                echo 'selected="selected"';
                            } ?> value="<?= $row['cases_type_id'] ?>"><?= ucfirst($row['cases_type_name']) ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <div id="salution-errors"></div>
                    </div>
                </div>
                
                <div class = " row">
                    <div class = "col-lg-6 col-sm-6 col-md-6 col-sm-6 col-xs-12 form-group">
                        <label class="control-label"><?= lang('BUSINESS_CASES') ?>*</label>
                        <input type="text" class="form-control" placeholder="<?= lang('BUSINESS_CASES') ?>" id="business_cases" name="business_cases" value="<?= !empty($edit_record[0]['business_cases']) ? htmlentities($edit_record[0]['business_cases']) : '' ?>" required>
                    </div>
                    
                    <div class = " col-lg-6 col-sm-6 col-md-6 col-sm-6 col-xs-12 form-group">
                        <label class="control-label"><?= lang('CASE_SUBJECT') ?>*</label>
                        <input type="text" class="form-control" placeholder="<?= lang('CASE_SUBJECT') ?>" id="business_subject" name="business_subject" value="<?= !empty($edit_record[0]['business_subject']) ? htmlentities($edit_record[0]['business_subject']) : '' ?>" required>
                    </div>
                    
                </div>
                
                <div class = " row">
                    <div class = "col-sm-6 form-group">
                        <label class="control-label"><?= lang('RESPONSIBLE') ?>*</label>
                        <select class="chosen-select" name="responsible" id="responsible" data-parsley-errors-container="#responsible-errors" required>
                            <option value=""><?= lang('SELECT_EMPLOYEE') ?></option>
                        <?php
                        foreach ($contact_data as $contact)
                        { ?>
                            <option value="<?php echo $contact['login_id']; ?>" <?php if((isset($edit_record[0]['responsible'])) && ($contact['login_id'] == $edit_record[0]['responsible'])){echo "selected='selected'";}?>><?php echo ucfirst($contact['firstname'])." ".ucfirst($contact['lastname']); ?></option>
                       <?php  }
                        ?>    
                        </select>
                        
                        <div id="responsible-errors"></div>
                    </div>
                    
                    <div class = "col-sm-6 form-group">
                        <label class="control-label"><?= lang('deadline') ?>*</label>
                        <input type="text" class="form-control" placeholder="<?= lang('deadline') ?>" id="deadline" name="deadline" onkeydown="return false" value="<?= !empty($edit_record[0]['deadline']) ? $edit_record[0]['deadline'] : '' ?>" required>
                    </div>
                    
                </div>
                
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="control-label"><?= lang('description') ?></label>
                        <textarea rows="2" name="description" id="description"  placeholder="<?= lang('description') ?>" class="form-control"><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></textarea>
                    </div>
                </div>
                
                <div class=" row">
                    <div class = "col-sm-6 form-group">
                        <label class="control-label"><?= lang('incident_status') ?>*</label>
                        <select class="chosen-select" name="incident_status" id="incident_status" data-parsley-errors-container="#incident-errors" required>
                            <option value=""><?= lang('SELECT_INCIDENTS_STATUS') ?></option>
                            <option value="1" <?php if(isset($edit_record[0]['incident_status']) && $edit_record[0]['incident_status'] == '1'){echo "selected='selected'";}?>><?= lang('in_process') ?></option>
                            <option value="2" <?php if(isset($edit_record[0]['incident_status']) && $edit_record[0]['incident_status'] == '2'){echo "selected='selected'";}?>><?= lang('on_hold') ?></option>
                            
                        </select>
                         <div id="incident-errors"></div>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label"><?= lang('uploads') ?></label>
                        <div class="mediaGalleryDiv form-group">
                        <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
                        <div class="mediaGalleryImg"></div> 
                    </div>
                        <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                            <div class="image_part">
                                <label name="addfile[]">
                                   <h1 style="top: -162px;">
                                <i class="fa fa-cloud-upload"></i><?= lang('DROP_IMAGES_HERE') ?> </h1>
                                    <input type="file" onchange="showimagepreview(this)" name="prospect_files[]" style="display: none" id="upl" multiple />
                                </label>
                            </div>
                            
                          <?php
                                if (!empty($cases_files[0]['file_id'])) {
                                    if (count($cases_files) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                        $i = 15482564;
                                        foreach ($cases_files as $image) {
                                            $path = $image['file_path'];
                                            $name = $image['file_name'];
                                            
                                            $arr_list = explode('.', $name);
                                            
                                            $arr = $arr_list[1];
                                            if (file_exists($path . '/' . $name)) {
                                                ?>
                                                <div id="img_<?php echo $image['file_id']; ?>" class="eachImage"> 
                                                    <a class="btn delete_file remove_drag_img" href="javascript:;" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['file_id']; ?>" data-path="<?php echo $path; ?>">x</a>
                                                    <span id="<?php echo $i; ?>" class="preview">    
                                                    <a href='<?php echo base_url($lead_view . '/download/' . $image['file_id']); ?>' target="_blank">
                                                            <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                                <img src="<?= base_url($path . '/' . $name); ?>"  width="75"/>
                                                            <?php } else { ?>
                                                                <div><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/>
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
                                        <?php
                                    }
                                }
                                ?>
                        </div>
                        <div class="clr"> </div>
                    </div
                    <div class="clr">
                    </div>

                </div>

                <div class="modal-footer">
                    <center> 
                       <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>"> 
                        <input type="hidden" id="cases_related_id" name="cases_related_id" value="<?= !empty($edit_record[0]['cases_related_id']) ? $edit_record[0]['cases_related_id'] : $cases_related_id ?>">
                        <input type="hidden" id="incident_id" name="cases_id"  value="<?= !empty($edit_record[0]['cases_id']) ? $edit_record[0]['cases_id'] : '' ?>">
                        <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
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
                <h4 class="modal-title"><?php echo lang('uploads');?></h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');"><?php echo lang('CLOSE');?></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php //echo form_close();  ?>
<script type="text/javascript">
     //upload from library
    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('#modalGallery').modal('show');
    });  
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
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
            autoclose: true,format: 'yyyy-mm-dd',
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
<!-- Upload image script -->
<script type="text/javascript">
    var config = {
        //support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",       // Valid file formats
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "<?php echo base_url(); ?>/Contact/upload_file"              // Server side upload url
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
            xhr[rand].open("post", "<?php echo base_url('/Projectmanagement/ProjectIncidents/file_upload') ?>/" + fileext, true);

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
    //multiple popup
    $('.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
</script>
