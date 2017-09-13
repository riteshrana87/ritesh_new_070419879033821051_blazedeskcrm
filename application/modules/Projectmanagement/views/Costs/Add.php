<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'saveCostData';
$formPath = $project_view . '/' . $formAction;
?>    
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><b><?php echo lang('add_cost') ?> </b> </h4>
        </div>
        <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>

            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <input type="text" maxlength="50" class="form-control" placeholder="<?php echo lang('cost_placeholder_name') ?> *" maxlength="30" id="project_name" name="cost_name" data-parsley-pattern="[A-za-z0-9_\-\s]+" data-parsley-whitespace="true"  required>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">
                            <input type="text" readonly class="form-control" placeholder="P###" id="cost_code" name="cost_code" value="<?php echo!empty($edit_record[0]['project_code']) ? $edit_record[0]['project_code'] : rand(); ?>">
                        </div>
                    </div>
                    <div class="clearfix visible-xs-block"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 form-group">
                        <div class="">

                            <select id="task_id" data-parsley-errors-container="#task-errors"  name="task_id" class="chosen-select" data-placeholder="Choose a Task *" required>
                                <option value=""><?php echo lang('cost_placeholder_task'); ?> *</option>
                                <?php
                                if (!empty($tasks)) {
                                    foreach ($tasks as $row) {
                                        ?>
                                        <option  value="<?php echo $row['task_id'] ?>"><?php echo ucfirst($row['task_name']); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <span id="task-errors"></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 form-group">
                        <div class="row">
                            <div class="col-xs-12 col-md-4 bd-form-group">
                                <label><?php echo lang('cost_placeholder_createddate') ?></label>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <div class="input-group date" id="created_date">
                                    <div class="form-control"><?php echo date('Y-m-d') ?></div>
                                    <input type="hidden" onkeydown="return false" onkeyup="return false" class="form-control" placeholder="<?php echo lang('cost_placeholder_createddate') ?>" id="created_date" name="created_date" value="<?php echo date('Y-m-d'); ?>">
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 form-group">
                        <div class="">

                            <select tabindex="-1" id="user_id"  name="user_id" class="chosen-select" data-parsley-errors-container="#user-errors" data-placeholder="Choose a user *" required>
                                <option value=""><?php echo lang('cost_placeholder_member'); ?> *</option>
                                <?php
                                if (!empty($res_user)) {
                                    foreach ($res_user as $row) {
                                        ?>
                                        <option  value="<?php echo $row['login_id'] ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <span id="user-errors"></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 form-group">
                        <div class="row">
                            <div class="col-xs-12 col-md-4 bd-form-group">
                                <label><?php echo lang('start_date'); ?> <span class="viewtimehide">*</span></label>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <div class="input-group date" id="start_date">

                                    <input type="text" class="form-control" placeholder="<?php echo lang('start_date') ?>" data-parsley-errors-container="#st-errors" id="start_date" name="start_date"  data-date-format="yyyy-mm-dd" data-date-startdate="<?php echo date('Y-m-d'); ?>" required>

                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                                </div>
                                <span id="st-errors"></span>

                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="clr"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 form-group">
                        <div class="">

                            <select class="form-control" name="cost_type" id="cost_type"  data-parsley-errors-container="#ctype-errors" required>
                                <option value="" selected=""><?php echo lang('cost_type'); ?> *</option>
                                <option value="Finance"><?php echo lang('finance'); ?></option>
                                <option value="Commission"><?php echo lang('commission'); ?></option>
                                <option value="Tax"><?php echo lang('tax'); ?></option>
                                <option value="Design"><?php echo lang('design'); ?></option>
                            </select>
                            <span id="ctype-errors"></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 form-group">
                        <div class="row">
                            <div class="col-xs-12 col-md-4 bd-form-group">
                                <label><?php echo lang('due_date'); ?> <span class="viewtimehide">*</span></label>
                            </div>
                            <div class="col-xs-12 col-md-8">
                                <div class="input-group date" id="due_date">

                                    <input type="text" required  data-parsley-gteq="#start_date" data-parsley-errors-container="#ed-errors"  data-date-format="yyyy-mm-dd"  class="form-control" placeholder="<?php echo lang('due_date') ?>" id="due_date" name="due_date">
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>

                                </div>
                                <span id="ed-errors"></span>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
                <div class="row">
                    <div class="col-xs-12 col-md-12 bd-togl-label">
                        <div class="form-group">

                            <input  data-toggle="toggle" data-onstyle="success" type="checkbox" id="within_project" name="within_project" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>">
                            <label >
                                <?php echo lang('cost_placeholder_projectbudget'); ?>
                            </label>
                            <!--                        <div class="btn-group btn-toggle">
                                                        <button class="btn btn-xs btn-default">ON</button>
                                                        <button class="btn btn-xs btn-primary active">OFF</button>
                                                    </div>-->
                            <!--                        <label>Within Project Budget?</label>-->
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="col-xs-12 col-md-5">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="<?php echo lang('cost_placeholder_amount') ?> *" id="amount" name="amount" pattern="/^\d{0,8}(\.\d{0,2})?$/" data-parsley-pattern="/^\d{0,8}(\.\d{0,2})?$/">

                        </div>
                    </div>
                    <div class="col-xs-12 col-md-1">
                        <div class="pad-tb6"></div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">

                            <select class="form-control chosen-select" name="product_id" data-parsley-errors-container="#pid-errors" id="product_id"  required>
                                <option value=""><?php echo lang('product_service'); ?> *</option>
                                <?php
                                if (count($products > 0)) {
                                    foreach ($products as $prod) {
                                        ?>
                                        <option value="<?php echo $prod['product_id']; ?>"><?php echo $prod['product_name']; ?></option>
                                    <?php } ?>
                                <?php } ?>

                            </select>
                            <span id="pid-errors"></span>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 bd-togl-label">
                        <div class="form-group">

                            <input  data-toggle="toggle"  data-onstyle="success" type="checkbox"  name="expense_supplier" id="expense_supplier" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>">
                            <label  for="expense_supplier">     <?php echo lang('cost_placeholder_expense'); ?>
                            </label>
                            <!--                        <div class="btn-group btn-toggle">
                                                                                                         <button class="btn btn-xs btn-default">ON</button>
                                                                                                         <button class="btn btn-xs btn-primary active">OFF</button>
                                                                                                     </div>-->
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group">

                            <select tabindex="-1" id="supplier_id"  name="supplier_id[]" class="chosen-select"  data-parsley-errors-container="#sp-errors" data-placeholder="<?php echo lang('cost_placeholder_supplier'); ?>" multiple="">
                                <?php
                                if (!empty($supplier)) {
                                    foreach ($supplier as $row) {
                                        ?>
                                        <option  value="<?php echo $row['supplier_id'] ?>"><?php echo ucfirst($row['supplier_name']); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                            <span id="sp-errors"></span>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"> </div>

                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="form-group">
                            <textarea  class="form-control" rows="4" placeholder="<?php echo lang('cost_placeholder_desc') ?>" name="description" id="description"><?php echo!empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></textarea>

                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
                <div class="clr"> </div>

                <div class="row">


                    <div class="col-xs-12 col-md-6">
                        <label><?php echo lang('CONTACT_VIEW_DOCUMENTS'); ?></label>
                        <div class="form-group">
                            <div class="mediaGalleryDiv">

                                <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
                                <div class="mediaGalleryImg">

                                </div> 

                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-xs-12 col-md-6">
                         <label>&nbsp;</label>
                     <div class="form-group">
                         <input type="file" name="cost_files[]" id="cost_files" class="form-control" multiple>
                     </div>
                     </div>
                    -->

                    <!--upload drag and drop file -->
                    <div class="col-xs-12 col-md-6 no-right-pad">
                        <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                            <div class="image_part">
                                <label name="cost_files[]">
                                    <h1 style="top: -162px;">
                                        <i class="fa fa-cloud-upload"></i>
                                        <?= lang('DROP_IMAGES_HERE') ?>
                                    </h1>
                                    <input type="file" onchange="showimagepreview(this)" name="cost_files[]" style="display: none" id="upl" multiple />
                                </label>
                            </div>

                        </div>
                        <div class="clr"> </div>
                    </div>
                    <!-- end upload drag and drop file -->



                    <div class="clr"></div>
                </div>
                <div class="clr"> </div>
            </div>
            <div class="modal-footer">

                <div class="text-center">
                    <input type="hidden" id="cost_id" name="cost_id"  value="<?php echo!empty($edit_record[0]['cost_id']) ? $edit_record[0]['cost_id'] : '' ?>">
                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">

                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?= lang('cost_placeholder_submit') ?>" />

                    <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="<?= lang('remove') ?>" /></center> 

                </div>
                <div class="clr"> </div>
            </div>
        </form>
    </div>

</div>
<!-- /.modal-dialog -->
<div class="modal fade modal-image" id="modalGallery" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#modalGallery').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= lang('uploads') ?></h4>
            </div>
            <div class="modal-body" id="modbdy">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onClick="$('#modalGallery').modal('hide');"><?= lang('close') ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $(document).ready(function () {
        //Set Editor For Estimate Content
        $('#description').summernote({
            disableDragAndDrop: true,
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
    });
    window.Parsley.addValidator('gteq',
            function (value, requirement) {
                return Date.parse($('#due_date input').val()) >= Date.parse($('#start_date input').val());
            }, 32)
            .addMessage('en', 'le', '<?php echo $this->lang->line('should_be_less_or_equal');?>');

    $('#within_project').bootstrapToggle();
    $('#expense_supplier').bootstrapToggle();
    $('#from-model').parsley();

    //disabled after submit
    $('body').delegate('#submit_btn', 'click', function () {

        if ($('#from-model').parsley().isValid()) {
            $('input[type="submit"]').prop('disabled', true);
            $('#from-model').submit();
        }
    });
    $('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    $('#start_date').datepicker({
        autoclose: true,
        startDate: new Date(),
    }).on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#due_date').datepicker('setStartDate', startDate);
    });
    $('#due_date')
            .datepicker({autoclose: true, startDate: new Date()
            });

</script>
<script>

    $('#gallery-btn').click(function () {
        $('#modbdy').load($(this).attr('data-href'));
        $('costModel').modal('hide');
        $('#modalGallery').modal('show');
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

            xhr[rand].open("post", "<?php echo base_url('/Projectmanagement/Costs/upload_file') ?>/" + fileext, true);

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

//           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size / 1024;
        //alert(maximum);

    }
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
</script>