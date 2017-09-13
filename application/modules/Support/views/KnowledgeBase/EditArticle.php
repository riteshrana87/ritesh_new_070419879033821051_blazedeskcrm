<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'KnowledgeBase/updatearticle';
$formPath = $project_view . '/' . $formAction;
?>    
<div class="modal-dialog modal-lg">
    <div class="modal-content costmodaldiv">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?php echo lang('edit_article') ?></div></h4>
        </div>
        <form name="knowledge_data" id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>
            <input type="hidden" value="<?php echo $edit_record[0]['article_id']; ?>" name="article_id">
            <div class="modal-body">
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <input type="text" class="form-control" placeholder="<?php echo lang('article_name') ?> *" id="article_name" name="article_name" value="<?php echo!empty($edit_record[0]['article_name']) ? htmlentities($edit_record[0]['article_name']) : '' ?>" required>
                        <span id="cost_name_error" class="alert-danger"></span>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <select class="form-control" name="main_category_id" id="main_category_id" required>
                            <option value=""><?php echo lang('sel_cat'); ?> *</option>
                            <?php
                            if (!empty($main_category)) {
                                foreach ($main_category as $row) {
                                    ?>
                                    <option  value="<?php echo $row['main_category_id'] ?>" <?php echo ($edit_record[0]['main_category_id'] == $row['main_category_id']) ? 'selected' : ''; ?>><?php echo ucfirst($row['category_name']) ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <span id="cost_type_error" class="alert-danger"></span>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class = "col-sm-12">
                        <select class="form-control" name="sub_category_id" id="sub_category_id">
                            
                        </select>
                        <span id="cost_type_error" class="alert-danger"></span>

                    </div>
                </div>
                <div class="form-group">
                    <ul class="parsley-errors-list filled" id="termsError" >
                        <li class="parsley-required">This value is required.</li>
                    </ul>
                    <label><?php echo lang('article_description') ?> *</label>
                    <textarea class="form-control" name="article_description" rows="2" id="article_description" placeholder="<?= $this->lang->line('article_description') ?>"><?php echo!empty($edit_record[0]['article_description']) ? $edit_record[0]['article_description'] : '' ?></textarea>
                </div>
                <div class = "form-group row">
                    <div class="col-xs-12 col-lg-6 col-sm-6 col-sm-6">
                        <div class="mediaGalleryDiv">
                            <?PHP if (checkPermission('KnowledgeBase', 'add')) { ?>
                                <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary" tabindex="21"><?php echo lang('select_attachment') ?></button>
                            <?php } else {
                                redirect(base_url());
                            } ?>
                            <div class="mediaGalleryImg"> </div>
                        </div>
                    </div>

                    <!-- new code-->

                    <!-- new code-->
                    <div class="col-xs-12 col-md-6 no-right-pad">
                        <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                            <div class="image_part" style="height: 100px;">
                                <label name="article_files[]">
                                    <h1 style="top: -162px;">
                                         <i class="fa fa-cloud-upload"></i>
<?= lang('DROP_IMAGES_HERE') ?>
                                    </h1>
                                    <input type="file" onchange="showimagepreview(this)" name="article_files[]" style="display: none" id="upl" multiple />
                                </label>
                            </div>
                            <?php
                            if (!empty($image_data)) {
                                if (count($image_data) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                    $i = 15482564;
                                    foreach ($image_data as $image) {
                                        $path = $image['file_path'];
                                        $name = $image['file_name'];
                                        $arr_list = explode('.', $name);
                                        $arr = $arr_list[1];
                                        if (file_exists($path . '/' . $name)) {
                                            ?>
                                            <div id="img_<?php echo $image['file_id']; ?>" class="eachImage">
                                                <a class="btn delimg remove_drag_img" href="javascript:;" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['file_id']; ?>" data-path="<?php echo $path; ?>">x</a>

                                                <span id="<?php echo $i; ?>" class="preview">
                                                    <a href='<?php echo base_url('/Support/KnowledgeBase/download/' . $image['file_id']); ?>' target="_blank">
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

                    <!-- end new code -->
                    <div class="clr"> </div>
                </div>
                <!-- end new code -->

                <div class = "form-group row">
                    <div class="col-sm-6">
                        <?php
                        if (!empty($editRecord[0]['client_visible'])) {
                            $client_time_id = "client_amount_show";
                        } else {
                            $client_time_id = "client_amount_hide";
                        }
                        ?>
                        <div class="form-group">
                            <input <?php echo ($edit_record[0]['client_visible'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-onstyle="success" type="checkbox"  id="client_visible" name="client_visible" onChange="toggle_show(<?php echo "'#" . $client_time_id . "'"; ?>, this)" <?= !empty($editRecord[0]['client_visible']) ? 'checked="checked"' : '' ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>

                            <label for="client_visible">
<?php echo lang('client_visible'); ?>
                            </label>
                        </div>
                    </div>
                    <div class = "col-sm-6">
                       
                        <select class="form-control" name="article_owner" id="article_owner" >
                            <option value="">Article Owner</option>
<?php foreach ($user as $user_data) { ?>
                                <option value="<?php echo $user_data['login_id']; ?>" <?php echo ($edit_record[0]['article_owner'] == $user_data['login_id']) ? 'selected' : ''; ?>><?php echo $user_data['firstname'] . ' ' . $user_data['lastname']; ?></option>
<?php } ?>
                        </select>
                           
                        <span id="cost_type_error" class="alert-danger"></span>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class="col-sm-6">
                        <?php
                        if (!empty($editRecord[0]['related_product'])) {
                            $related_time_id = "related_amount_show";
                        } else {
                            $related_time_id = "related_amount";
                        }
                        ?>
                        <div class="form-group">
                            <input <?php echo ($edit_record[0]['product_related'] == 1) ? 'checked' : ''; ?> data-toggle="toggle" data-onstyle="success" type="checkbox"  id="product_related" name="product_related" onChange="toggle_show(<?php echo "'#" . $related_time_id . "'"; ?>, this)" <?= !empty($editRecord[0]['product_related']) ? 'checked="checked"' : '' ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"/>

                            <label for="product_related">
                        <?php echo lang('product_related'); ?>
                            </label>
                        </div>
                    </div>
                    <div class = "col-sm-6">
                            <div id="<?php echo $related_time_id; ?>" class="related_time_id">
                                <select class="form-control chosen-select product_related" multiple="true"  name="product_id[]" id="product_id" data-placeholder="<?= $this->lang->line('select_option') ?>">
                            <?php if (!empty($product_info) && count($product_info) > 0) { ?>
        <?php foreach ($product_info as $row) { ?>
                                            <option value="<?php echo $row['product_id']; ?>" <?php if (!empty($product_data) && in_array($row['product_id'], $product_data)) {
                echo 'selected="selected"';
            } ?>><?php echo $row['product_name']; ?></option>
        <?php } ?>
    <?php } ?>
                                </select>
                            </div>
                        <span id="cost_type_error" class="alert-danger"></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?php echo lang('edit_article') ?>" />
                </div>
                <div class="clr"> </div>
            </div>
        </form>

    </div><!-- /.modal-dialog -->
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
        <?php if($edit_record[0]['client_visible'] == 1){ ?>
        $(".client_time_id").css("display", "block");
        <?php } ?>
            <?php if($edit_record[0]['product_related'] == 1){ ?>
        $(".related_time_id").css("display", "block");
        <?php } ?>
            var id = $("#main_category_id>option:selected").attr("Value");
             $('#sub_category_id').append('<option value=""><?php echo lang('sel_sub_cat') ?></option>');
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('Support/KnowledgeBase/getSubCategories'); ?>",
                data: 'id=' + id,
                success: function (data) {
                    var subcategories = $.parseJSON(data);
                    console.log(subcategories);
                    $.each(subcategories, function (key, val) {
                        console.log(val);
                       
                        $('#sub_category_id').append('<option value="' + val.sub_category_id + '" >' + val.sub_category_name + '</option>')
                       $("#sub_category_id option[value='<?php echo $edit_record[0]['sub_category_id']; ?>']").attr('selected', 'selected');
                    });

                }

            });
        });
        $("#main_category_id").change(function () {
            $("#sub_category_id option").remove();
            $('#sub_category_id').append('<option value=""><?php echo lang('sel_sub_cat') ?></option>');
            var id = this.value;
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('Support/KnowledgeBase/getSubCategories'); ?>',
                data: 'id=' + id,
                success: function (data) {
                    var subcategories = $.parseJSON(data);
                    console.log(subcategories);
                    $.each(subcategories, function (key, val) {
                        console.log(val);

                        $('#sub_category_id').append('<option value="' + val.sub_category_id + '">' + val.sub_category_name + '</option>')
                    });

                }

            });
        });
        $('#from-model').parsley();
        $('.product_related').trigger('chosen:updated');
        $('.client_visible').trigger('chosen:updated');
        $('#client_visible').bootstrapToggle();
        $('#product_related').bootstrapToggle();

        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    </script>
    <script>
        $('#gallery-btn').click(function () {
            $('#modbdy').load($(this).attr('data-href'));
            $('costModel').modal('hide');
            $('#modalGallery').modal('show');
        });

    </script>
    <script>

        $('.delimg').on('click', function () {
            var divId = ($(this).attr('data-id'));
            var imgName = ($(this).attr('data-name'));
            var dataUrl = $(this).attr('data-href');
            var dataPath = $(this).attr('data-path');
            var str1 = divId.replace(/[^\d.]/g, '');
            var delete_meg ="<?php echo $this->lang->line('delete_item');?>";
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
            uploadUrl: "/Support/KnowledgeBase/upload_file"	// Server side upload url
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
                xhr[rand].open("post", "<?php echo base_url('/Support/KnowledgeBase/upload_file') ?>/" + fileext, true);
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

        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#termsError").css("display", "none");
            $('#from-model').parsley();
            $('#article_description').summernote({
                height: 150, //set editable area's height
                codemirror: {// codemirror options
                    theme: 'monokai'
                }
            });
            $('body').delegate('#from-model', 'submit', function () {

                var code = $('#article_description').code(),
                        filteredContent = $(code).text().replace(/\s+/g, '');

                if (filteredContent.length > 0) {

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
        $('#due_date').datepicker({autoclose: true, startDate: new Date()});
        $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

            $('body').addClass('modal-open');
        });
    </script> 
