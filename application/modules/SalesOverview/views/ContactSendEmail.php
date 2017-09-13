<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$path = base_url() . 'SalesOverview/sendMessageEmail';
?>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url() . "uploads/custom/js/SpellChecker/css/jquery.spellchecker.css"; ?>"/>
<script type="text/javascript" src="<?php echo base_url() . "uploads/custom/js/SpellChecker/js/jquery.spellchecker.js"; ?> "></script>
<div class="modal-dialog modal-lg">
    <?php
    $attributes = array("name" => "send_email", "id" => "send_email", 'data-parsley-validate' => "");
    echo form_open_multipart($path, $attributes);
    ?>
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="set_label"><?php echo $modal_title; ?></h4>
        </div>
        <div class="modal-body">

            <div class="row"> 
                <div class="col-xs-12 col-md-5 no-right-pad">
                    <div class="form-group">
                        <select name="receipent_email" data-placeholder="<?php echo lang('SELECT_RECEIPENT_EMAIL'); ?> *" id="receipent_email" class="form-control chosen-select" required data-parsley-errors-container="#prospect_owner_error">
                            <option value=""></option>
                            <?php
                            foreach ($contact_record as $company_data) {
                                ?>
                                <option value="<?php echo $company_data['contact_id'] ?>" > <?php echo $company_data['contact_name'] ?></option>
                            <?php } ?>

                        </select>
                        <div id="prospect_owner_error"></div>
                    </div>
                </div>
                <div class="clr"></div></div>

            <div class="row"> 

                <div class="clr"> </div></div>
            <h4><?php echo lang('EMAIL_CONTENT'); ?></h4>
            <div class="row">
                <div class="col-xs-12 col-md-6 col-lg-5 no-left-pad">
                    <div class="form-group">
                        <input type="text" name="email_subject" id="email_subject" class="form-control" placeholder="<?php echo lang('subject'); ?>*" required=""/>
                    </div> 

                </div>
                <div class="col-lg-2 text-center bd-form-group">
                    <label>or</label>
                </div>
                <div class="col-xs-12 col-md-5 col-lg-5 no-right-pad">
                    <div class="form-group">
                        <select class="form-control chosen-select" name="email_template_id" onchange="autoFillEmailTemplate(this.value);" id="email_template_id" data-parsley-errors-container="#template_errors">
                            <option value=""><?php echo lang('SELECT_EMAIL_TEMPLATE'); ?></option>
<?php
foreach ($email_template_data as $email_template) {
    ?>
                                <option value="<?php echo $email_template['template_id']; ?>"><?php echo $email_template['subject']; ?></option>
                            <?php }
                            ?>
                        </select>
                        <div id="template_errors"></div>
                    </div>
                </div>
                <div class="clr"> </div></div>

            <div class="row"><div class="col-xs-12 col-md-12 no-left-pad">
                    <div class="form-group">
                        <textarea class="form-control" name="email_content" id="email_content"></textarea>
                    </div>

                    <div id="incorrect-word-list"></div>
                </div></div>
            <div class="clr"> </div>

            <div class="row">

                <div class="col-xs-6 col-sm-6 col-md-3 no-right-pad col-lg-3">
                    <div class="text-center">
                        <a class="btn btn-primary col-xs-12 col-md-12 btn-whitespace" id="check-textarea"><?php echo lang('CHECK_SPELLING'); ?></a>
                    </div>
                    <div class="clr"> </div><br/>
                    <div class="text-center">
                        <a class="btn btn-primary col-xs-12 col-md-12 btn-whitespace" onclick="markAsImportant();" id="mark_important"><?php echo lang('FLAG_AS_IMPORTANT'); ?></a>
                    </div>
                    <div class="clr"> </div><br/>
                    <div class="text-center">
                        <a class="btn btn-primary col-xs-12 col-md-12 btn-whitespace"><?php echo lang('REQUEST_REAR_NOTIFICATION'); ?></a>
                    </div>
                    <div class="clr"> </div><br/>
                    <div class="text-center">
                        <button class="btn btn-primary col-xs-12 col-md-12 btn-whitespace"><?php echo lang('SEND_MEETING_DATE'); ?></button>
                    </div>
                    <div class="clr"> </div>


                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
                    <label><?php echo lang('documents'); ?></label>
                    <div class="form-group">
                        <div class="mediaGalleryDiv">

                            <button type="button" name="gallery" id="gallery-btn" data-href="<?php echo $url; ?>"  class="btn btn-primary"><?php echo lang('cost_placeholder_uploadlib') ?></button>
                            <div class="mediaGalleryImg">

                            </div> 

                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6 no-right-pad col-lg-5">
                    <div id="dragAndDropFiles" class="uploadArea bd-dragimage">
                        <div class="image_part">
                            <label name="cost_files[]">

                                <h1 style="top: -162px;"> <i class="fa fa-cloud-upload"></i>&nbsp;<?= lang('DROP_IMAGES_HERE') ?></h1>
                                <input type="file" onchange="showimagepreview(this)" name="cost_files[]" style="display: none" id="upl" multiple />
                            </label>
                        </div>

                    </div>
                    <div class="clr"> </div>
                </div>
                <!-- end upload drag and drop file -->



                <div class="clr"></div>
            </div>

            <div class="col-xs-12 col-md-4 no-right-pad">
                <div class="form-group">

                </div> 
            </div>
            <div class="clr"> </div>
            <input type="hidden" name="hdn_mark_as_important" id="hdn_mark_as_important" value="0"/>
            <input type="hidden" name="hdn_contact_id" id="hdn_contact_id" value="<?php if ($contact_id) {
                                echo $contact_id;
                            } ?>"/>
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>"> 
            <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
        </div>
        <div class="modal-footer">

            <center> <input type="submit" class="btn btn-primary" id="send_email_btn" value="<?= $modal_title ?>"></center>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('#send_email').parsley();
        $('.chosen-select').chosen();


        $('#email_content').summernote({
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            },
            focus: true,
            onImageUpload: function (files, editor, $editable) {
                sendFile(files[0], editor, $editable);
            }

        });
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("file", file);
            $.ajax({
                url: "<?php echo base_url('Contact/uploadFromEditor'); ?>",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: function (data) {
                    editor.insertImage(welEditable, data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + " " + errorThrown);
                }
            });
        }
    });



    function autoFillEmailTemplate(template_id)
    {

        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "Contact/getEmailTemplateDataById"; ?>',
            data: {template_id: template_id},
            success: function (data)
            {

                if (data != null)
                {
                    var response_data = data.split('||');
                    $('#email_subject').val(response_data[0]);
                    $('#email_content').code(response_data[1]);
                } else
                {

                    $('#email_subject').val('');
                    $('#email_content').code('');
                }

            }
        });
    }



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
                        var delete_meg = 'File \"" + file.name + "\" <?php echo lang('too_big_size'); ?>';
                        BootstrapDialog.show(
                                {
                                    title: '<?php echo $this->lang->line('Information'); ?>',
                                    message: delete_meg,
                                    buttons: [{
                                            label: '<?php echo $this->lang->line('ok'); ?>',
                                            action: function (dialog) {
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
                    var delete_meg = "<?php echo lang('failed_file_upload'); ?>";
                    BootstrapDialog.show(
                            {
                                title: '<?php echo $this->lang->line('Information'); ?>',
                                message: delete_meg,
                                buttons: [{
                                        label: '<?php echo $this->lang->line('ok'); ?>',
                                        action: function (dialog) {
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


    function markAsImportant()
    {
        var hdn_mark_as_important = $('#hdn_mark_as_important').val();

        if (hdn_mark_as_important == 0)
        {
            $('#hdn_mark_as_important').val(1);
            $('#mark_important').removeClass('btn-primary');
            $('#mark_important').addClass('btn-success');
        } else
        {
            $('#hdn_mark_as_important').val(0);
            $('#mark_important').addClass('btn-primary');
            $('#mark_important').removeClass('btn-success');
        }

    }


</script>
<script type="text/javascript">
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
    (function () {
        var wys = $('.note-editable').html();
        var email_cont = wys.replace(/(<([^>]+)>)/ig, "");
        //        var email_cont =   $('#email_content').code();
        // Init the html spellchecker
        var spellchecker = new $.SpellChecker('#email_content', {
            lang: 'en',
            parser: 'text',
            webservice: {
                path: '<?php echo base_url() . "uploads/custom/js/SpellChecker/webservices/php/SpellChecker.php"; ?>',
                driver: 'pspell'
            },
            suggestBox: {
                position: 'above'
            },
            incorrectWords: {
                container: '#incorrect-word-list'
            },
            local: {
                requestError: '<?php echo lang('SPELLCHECKER_ERROR'); ?>',
                ignoreWord: '<?php echo lang('SPELLCHECKER_IGNOR'); ?>',
                ignoreAll: '<?php echo lang('SPELLCHECKER_IGNOR_ALL'); ?>',
                loading: '<?php echo lang('SPELLCHECKER_LOADING'); ?>',
                noSuggestions: '<?php echo lang('SPELLCHECKER_NO_SUGGESTION'); ?>'
            },
        });

        // Bind spellchecker handler functions
        spellchecker.on('check.success', function (result) {
            var delete_meg = "<?php echo lang('SPELLCHECKER_NO_INCORRECT_SPELL'); ?>";
            BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information'); ?>',
                        message: delete_meg,
                        buttons: [{
                                label: '<?php echo $this->lang->line('ok'); ?>',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                    });

        });

        // Check the spelling
        $("#check-textarea").click(function (e) {
            var content = $('textarea[name="email_content"]').html($('#email_content').code());

            // console.log(content);
            spellchecker.check();
        });

    })();
</script>