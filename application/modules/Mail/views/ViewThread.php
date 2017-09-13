<!-- Example row of columns -->
<!-- <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url('Mail'); ?>">Mailbox</a></li>
            <li class="active">Inbox</li>
        </ul>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 text-right">
        <div class="pull-right settings"> <a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
        <div class="pull-right search-top">
            <form class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="fa fa-search btn btn-default"></button>
            </form>
        </div>
    </div>
    <div class="clr"></div>
</div> -->
<div class="modal-dialog modal-lg" >
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?php echo isset($subject) ? $subject : ''; ?></div></h4>
        </div>
       <!-- <form id="viewEmailTemplate" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>  --> 
        <div class="modal-body modal-noborder">	
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="form-group bd-mail-head bd-inbox">
                    <ul>

                        <li>
                            <a  href="<?php echo base_url('Mail/replyEmail/' . $uid); ?>" ><i class="bd-reply-ico"></i><span><?= lang('mail_reply') ?></span></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('Mail/replyEmailAll/' . $uid); ?>"  ><i class="bd-replyall-ico"></i><span><?= lang('mail_reply_all') ?></span></a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('Mail/forwardEmail/' . $uid); ?>" ><i class="bd-forward-ico"></i><span><?= lang('mail_forward') ?></span></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clr"></div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="row">
                    <div class="whitebox">
                        <form method="post" action="<?php echo base_url('Mail/sendEmail'); ?>" id="compose-form">
                            <div class="row">
                                <div class="col-lg-12 col-xs-12 col-sm-12">
                                    <!--  <div class="form-group bd-mail-head">
                                        <ul>
                                            <li><a href="<?php echo base_url('Mail'); ?>"><i class="bd-back-ico"></i><span>Back</span></a></li>
                                            <li>
                                                <button type="submit" id="sentmail" name="sentmail" ><i class="bd-send-ico"></i><span>Send Message</span></button>
                                            </li>
                                            <li>
                                                <button type="button" onclick="$('#upl').trigger('click');" ><i class="bd-attach-ico"></i><span>Attach File</span></button>
                                            </li>
                                            <li>
                                                <button type="button" onclick="saveConcept();" ><i class="bd-save-ico"></i><span>Save Concept</span></button>
                                            </li>
                                            <li>
                                                <button type="button" onclick="signatureBox();" ><i class="bd-sign-ico"></i><span>Insert Signature</span></button>
                                            </li>
                                        </ul>
                                    </div> -->
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><?= lang('mail_from') ?>:</label>                                
                                            <div class="col-lg-10 col-md-10 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo (isset($emailData)) ? $emailData[0]['from_mail'] : ''; ?></span>
                <!--                                    <select class="form-control">
                                                        <option>Sharif Hussainali</option>
                                                        <option></option>
                                                    </select>-->
                                                   <!--  <input type="hidden" name="mailtype" value="<?php echo isset($mailtype) ? $mailtype : ''; ?>">
                                                    <input type="hidden" name="uid" value="<?php echo isset($uid) ? $uid : ''; ?>">
                                                    <input type="hidden" name="msg_no" value="<?php echo isset($emailData) ? $emailData[0]['msg_no'] : ''; ?>">
                                                    <input type="text" name="from" required="" id="from" class="form-control" readonly="" value="<?php echo $fromMail; ?>"> -->
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><?= lang('mail_cc') ?>:</label>
                                            <div class="col-lg-10 col-md-10 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo isset($emailData) ? $emailData[0]['cc_email'] : ''; ?>                                	</span>
                                           <!-- <input id="cc" value="<?php echo isset($emailData) ? $emailData[0]['cc_email'] : ''; ?>"  name="cc" type="text" class="form-control" placeholder=""> --> 
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><?= lang('mail_to') ?>:</label>
                                            <div class="col-lg-10 col-md-10 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo (isset($to)) ? $to : ''; ?></span>
                                            <!-- <input name="to" value="<?php echo (isset($to)) ? $to : ''; ?>"  id="to" type="text" class="form-control" placeholder=""> -->
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                        <div class="form-group col-lg-6 col-md-6 col-sm-6 ">
                                            <label  class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><?= lang('mail_bcc') ?>:</label>
                                            <div class="col-lg-10 col-md-10 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo isset($emailData) ? $emailData[0]['bcc_email'] : ''; ?></span>
                                                <!-- <input id="bcc" value="<?php echo isset($emailData) ? $emailData[0]['bcc_email'] : ''; ?>" name="bcc" type="text" class="form-control" placeholder=""> --> 
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-6 col-sm-12 ">
                                            <label class="col-lg-2 col-md-2 col-sm-2 col-xs-12"><?= lang('mail_subject') ?>:</label>
                                            <div class="col-lg-10 col-md-10 col-sm-10 bd-form-control col-xs-12">
                                                <span><?php echo isset($subject) ? $subject : ''; ?></span>
                                            <!-- <input value="<?php //echo isset($subject) ? $subject : '';                ?>" id="subject" required="" name="subject" type="text" class="form-control" placeholder=""> -->
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clr"></div>
                                <div class="">
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12  bd-rsp-image
                                         <?php echo (strlen($defaultBody) > 50) ? 'bd-desc-minheight' : '' ?>">
                                        <div class="col-xs-12">  <p ><?php echo isset($defaultBody) ? $defaultBody : ''; ?></p></div>
                                        <div class="clr"></div>
                                    </div>
                                </div>

                                <div class="clr"></div>

                            </div>
                            <?php
                            if (count($mail_files) > 0) {
                                ?>


                                <div class="col-lg-12 col-md-12 col-sm-12">

                                    <div class="bd-mail-detail form-group uploadArea bd-dragimage ">
                                        <!--                <div class=" bd-search-head">
                                                          <label>Attachments:</label>
                                                         
                                                        </div>
                                                        
                                                       <div class="pad-10 text-center"> 
                                                       <a class="width-100 btn small-white-btn" href="#">Import Prospect (CSV) </a> </div>
                                                      </div>-->


                                        <!-- <div class="image_part">
                                             <label name="cost_files[]">
                                                 <h1 style="top: -162px;">
                                                     <i class="fa fa-cloud-upload"></i>
                                        <?= lang('DROP_IMAGES_HERE') ?>
                                                 </h1>
                                                 <input type="file" onchange="showimagepreview(this)" name="cost_files[]" style="display: none" id="upl" multiple />
                                             </label>
                                         </div>  --> 
                                        <?php
                                        //  pr($mail_files);
                                        if (isset($mail_files) && count($mail_files) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                            $i = 15482564;
                                            foreach ($mail_files as $image) {
                                                if ($image['file_name_app'] == 0) {
                                                    $path = htmlentities($image['file_path']);
                                                    $name = $image['file_name'];
                                                    $pathrel = htmlentities($image['file_path_abs']);
                                                    $arr_list = explode('.', $name);
                                                    if (isset($arr_list[1])) {
                                                        $arr = $arr_list[1];
                                                    }

                                                    if (file_exists($path)) {
                                                        ?>
                                                        <div id="img_<?php echo $image['auto_id']; ?>" class="eachImage">
                    <!--                                                        <a class="btn delimg remove_drag_img" title="<?php echo lang('delete'); ?>" href="javascript:;" data-name="<?php echo $name; ?>" data-id="img_<?php echo $image['auto_id']; ?>" data-path="<?php echo $path; ?>" data-href="<?php echo base_url($project_view . '/deleteImage/' . $image['auto_id']); ?>">x</a>-->
                                                            <span id="<?php echo $i; ?>" class="preview">
                                                                <a href='<?php echo base_url('Mail/download/' . $image['auto_id']); ?>' target="_blank">


                                                                    <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>                  
                                                                        <img src="<?= $pathrel; ?>"  width="75"/>        <?php } else { ?>
                                                                        <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/><p class="img_show"><?php echo $arr; ?></p></div>
                                                                    <?php } ?>
                                                                </a>
                                                                <p class="img_name"><?php echo $name; ?></p>
                                                                <span class="overlay" style="display: none;">
                                                                    <span class="updone">100%</span></span>
                                                                <input type="hidden" value="<?php echo $path; ?>" name="fileToUploadext[]">
                                                            </span>
                                                        </div>


                                                    <?php } ?>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </div>

                                </div>
                            <?php } ?>
                        </form>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
            <div class="clr"></div>
        </div>
        <!--  <div class="modal-footer">
             <center> 
              <input name="template_id" type="hidden" value="<?= !empty($viewEmailTemplate[0]['template_id']) ? $viewEmailTemplate[0]['template_id'] : '' ?>" />--> 
              <!-- <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Test" /> -->              	
        <!-- </center>					
</div> -->

        <!-- </form>  -->  
    </div>
    <div class="clr"></div>
</div>

<script>
    $(document).ready(function () {
        $('#compose-form').parsley();
    });

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');

        BootstrapDialog.confirm("Are your Sure to delete this item?", function (result) {
            if (result) {
                $('#deletedImagesDiv').append("<input type='hidden' name='softDeletedImages[]' value='" + str1 + "'> <input type='hidden' name='softDeletedImagesUrls[]' value='" + dataPath + '/' + imgName + "'>");
                $('#' + divId).remove();
//                $.ajax({
//                    url: dataUrl,
//                    type: 'GET',
//                    dataType: "json",
//                    success: function (data)
//                    {
//                        if (data.status == 1)
//                        {
//                            $('#' + divId).remove();
//                        }
//                        else
//                        {
//                            alert(data.error);
//                        }
//                    },
//                    error: function ()
//                    {
//                        console.log('Error in call');
//                    }
//
//                });
            } else {

            }
            $('#confirm-id').on('hidden.bs.modal', function () {
                $('body').addClass('modal-open');
            });
        });
    });
    var config = {
        support: "*", // Valid file formats
        form: "demoFiler", // Form ID
        dragArea: "dragAndDropFiles", // Upload Area ID
        uploadUrl: "/upload_file"				// Server side upload url
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
                        alert("File \"" + file.name + "\" is too big.Max allowed size is 20 MB.");
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
            xhr[rand].open("post", "<?php echo base_url('Mail/dragDropImgSave') ?>/" + fileext, true);
            xhr[rand].upload.addEventListener("progress", function (event) {
                //console.log(event);
                if (event.lengthComputable) {
                    $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                }
                else {
                    alert("Failed to compute file upload length");
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
                template += '<input type="hidden" id="img_' + rand + '" name="fileToUpload[]" value="' + b.name + '">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);

            var file_data = $("#upl").prop("files")[0];   // Getting the properties of file from file field
            var form_data = new FormData();                  // Creating object of FormData class
            form_data.append("file", file_data)
            $.ajax({
                url: '<?php echo base_url('Mail/dragDropImgSave'); ?>/' + arr,
                type: 'POST',
                processData: false, // important
                contentType: false, // important
                data: file_data,
                success: function (d)
                {
                    $('#img_' + rand + '').val(d);
                }
            });
//           console.log(b.name);
        });
        //console.log(input.files[0]['name']);
        var maximum = input.files[0].size / 1024;
        //alert(maximum);

    }



    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });
    function addselectedclass(id, emailaddress)
    {
        if ($('#' + id).hasClass('active'))
        {
            $('#' + id).removeClass('active');
        } else
        {
            $('#' + id).addClass('active');
        }
    }
    function addtomail(typemail)
    {
        var emails = '';
        if ($('#' + typemail).val() != '')
        {
            emails += $('#' + typemail).val() + ',';

        }
        $('.cmail.active').each(function () {
            emails += $(this).attr('data-email') + ',';
        });


        emails = emails.slice(',', -1);
        $('#' + typemail).val(emails);
        $('.cmail').removeClass('active');
    }
    function saveConcept()
    {
        $('#compose-form').parsley().validate();
        if ($('#compose-form').parsley().isValid()) {
            $.ajax({
                url: "<?php echo base_url('Mail/saveConcept'); ?>",
                data: $('#compose-form').serialize(),
                type: "POST",
                dataType: 'json',
                success: function (d)
                {
                    BootstrapDialog.alert("Email Saved as Concept!");
                }
            });
        }
        return false;


    }
    $(document).ready(function () {
        //Set Editor For Estimate Content
        var countImg =<?php echo count($mail_files); ?>;

        if (parseInt(countImg) > 10)
        {
            $('.bd-mail-detail').addClass('scrollDiv');
        }
        else
        {
            $('.bd-mail-detail').removeClass('scrollDiv');
        }

        $('#message').summernote({
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
                url: "<?php echo base_url('Mail/uploadFromEditor'); ?>",
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
    function signatureBox()
    {
        $('#signaturemodal').modal('show');
        $('#signarea').summernote({
            disableDragAndDrop: true,
            height: 150, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            }
        });
    }
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });
</script>
