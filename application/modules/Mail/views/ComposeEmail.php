<!-- Example row of columns -->
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url('Mail'); ?>">Mailbox</a></li>
            <li class="active"><?php echo lang('mail_inbox'); ?></li>
        </ul>
    </div>

    <div class="clr"></div>
</div>
<div class="row">
    <div id="leftbar">
        <?php echo $this->load->view('leftbar'); ?>
    </div>
    <div class="col-xs-12 col-sm-12 col-lg-10 col-md-12">
        <div id="main_div">
            <div class="row " >
                <div class="whitebox">
                    <form method="post" action="<?php echo base_url('Mail/sendEmail'); ?>" id="compose-form">
                        <div class="row">
                            <div class="col-lg-9 col-xs-12 col-sm-8 bd-mail-editor-postn">
                                <div class="form-group bd-mail-head bd-inbox">
                                    <ul >
                                        <li ><a href="<?php echo base_url('Mail'); ?>"><i class="bd-back-ico"></i><span><?php echo lang('mail_back'); ?></span></a></li>
                                        <li>
                                            <button type="submit" id="sentmail" name="sentmail" ><i class="bd-send-ico "></i><span><?php echo lang('mail_send_message'); ?></span></button>
                                        </li>
                                        <li>
                                            <button type="button" onclick="$('#upl').trigger('click');" ><i class="bd-attach-ico"></i><span><?php echo lang('mail_attach_file'); ?></span></button>
                                        </li>
                                        <li>
                                            <button type="button" onclick="saveConcept();" ><i class="bd-save-ico"></i><span><?php echo lang('mail_save_concept'); ?></span></button>
                                        </li>
                                        <li>
                                            <a data-href="<?php echo base_url('Mail/signatureBox');?>" data-toggle="ajaxModal" href="javascript:;"><i class="bd-sign-ico"></i><span><?php echo lang('mail_insert_signature'); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 bd-form-group">
                                    <label><?php echo lang('mail_from'); ?>:*</label>
                                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-9 bd-form-control">
    <!--                                    <select class="form-control">
                                            <option>Sharif Hussainali</option>
                                            <option></option>
                                        </select>-->
                                        <input type="hidden" name="mailtype" value="<?php echo isset($mailtype) ? $mailtype : ''; ?>">
                                        <input type="hidden" name="uid" value="<?php echo isset($uid) ? $uid : ''; ?>">
                                        <input type="hidden" name="msg_no" value="<?php echo isset($emailData) ? $emailData[0]['msg_no'] : ''; ?>">
                                        <input type="text" name="from" required="" id="from" class="form-control" readonly value="<?php echo $fromMail; ?>">
                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 bd-form-group">
                                    <label><?php echo lang('mail_cc'); ?>:</label>
                                    <div class="col-lg-10 col-md-11 col-sm-9 col-xs-9 bd-form-control">
                                        <input id="cc" value="<?php echo (isset($emailData) && $mailtype != 'forward') ? $emailData[0]['cc_email'] : ''; ?>"  name="cc" type="text" class="form-control" placeholder="">
                                    </div>
                                    <div class="clr"></div>
                                </div> <div class="clr"></div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 bd-form-group">
                                    <label><?php echo lang('mail_to'); ?>:*</label>
                                    <div class="col-lg-10 col-md-10 col-sm-9 col-xs-9 bd-form-control">
                                        <input name="to" value="<?php echo (isset($to)) ? $to : ''; ?>"  id="to" type="text" class="form-control" required="" placeholder="">
                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="form-group col-lg-6 col-md-6 col-sm-6 bd-form-group">
                                    <label><?php echo lang('mail_bcc'); ?>:</label>
                                    <div class="col-lg-10 col-md-11 col-sm-9 col-xs-9 bd-form-control">
                                        <input id="bcc" value="<?php echo (isset($emailData) && $mailtype != 'forward') ? $emailData[0]['bcc_email'] : ''; ?>" name="bcc" type="text" class="form-control" placeholder="">
                                    </div>
                                    <div class="clr"></div>
                                </div> <div class="clr"></div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 bd-form-group">
                                    <label><?php echo lang('mail_subject'); ?>:*</label>
                                    <div class="col-lg-11 col-md-10 col-sm-10 col-xs-9 bd-form-control">
                                        <input value="<?php echo isset($subject) ? $subject : ''; ?>" id="subject" required="" name="subject" type="text" class="form-control" placeholder="">
                                    </div>
                                    <div class="clr"></div>
                                </div> <div class="clr"></div>
                                <div class="form-group col-lg-12 col-md-12 col-sm-12 bd-form-group bd-mail-editor">
                                    <textarea id="message"  name="message" class="form-control"><?php echo '<br/><br/><br/>' . $email_signature; ?><?php echo isset($defaultBody) ? $defaultBody : ''; ?>  </textarea>
                                    <div class="clr"></div>
                                </div> <div class="clr"></div>
                            </div>
                            <div class="col-lg-3 col-xs-12 col-sm-4">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="bd-mail-detail ">
                                        <div class="bd-sesrch-contact bd-search-head">
                                            <label><?php echo lang('mail_contacts'); ?>:</label>
                                            <div class="search-top">
                                                <div class="navbar-form row">
                                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                                        <input type="text" placeholder="<?php echo lang('mail_search_contacts'); ?>" class="form-control col-lg-11" id='searchContact' name='searchContact' value="">
                                                    </div>
                                                    <!--   <button class="fa fa-search btn btn-default" type="button"></button>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" bd-select-box">
                                            <ul class="nav  " id="searchList">
                                                <?php
                                                if (count($contacts) > 0) {
                                                    foreach ($contacts as $key => $contactValue) {
                                                        $contactEmail = (isset($contactValue['email'])) ? $contactValue['email'] : $contactValue;
                                                        $contactName = (isset($contactValue['contact_name'])) ? '<strong>' . $contactValue['contact_name'] . '</strong><br/>' : '';
                                                        ?>

                                                                                                                                                                                                                                                                                                                    <!--   <li><a href="javascript:;" class="cmail" id="contact-<?php echo $contact['contact_id']; ?>" onclick="addselectedclass('contact-<?php echo $contact['contact_id']; ?>', '<?php echo $contact['email']; ?>')" data-email='<?php echo $contact['email']; ?>'><?php echo $contact['contact_name']; ?></a></li>-->

                                                                                                                                                                                                                                                                                                                                                                    <!--   <li><a href="javascript:;" class="cmail" id="contact-<?php echo $contact['contact_id']; ?>" onclick="addselectedclass('contact-<?php echo $contact['contact_id']; ?>', '<?php echo $contact['email']; ?>')" data-email='<?php echo $contact['email']; ?>'><?php echo $contact['contact_name']; ?></a></li>-->

                                                        <li>
                                                            <a class="cmail" id="contact-<?php echo $key; ?>" onclick="addselectedclass('contact-<?php echo $key; ?>', '<?php echo $contactEmail ?>')" data-email='<?php echo $contactEmail; ?>'>
                                                                <?php echo $contactName . $contactEmail; ?>
                                                            </a>
                                                        </li>

                                                        <?php
                                                    }
                                                }
                                                ?>

                                            </ul>
                                        </div>
                                        <div class="bd-search-ftr">
                                            <button type="button" onclick="addtomail('to');" class="btn col-lg-3 col-md-3 col-sm-3"><?php echo lang('mail_to'); ?> <i class="fa fa-angle-double-right"></i></button>
                                            <button type="button" onclick="addtomail('cc');" class="btn col-lg-3 col-md-3 col-sm-3"><?php echo lang('mail_cc'); ?><i class="fa fa-angle-double-right"></i></button>
                                            <button type="button" onclick="addtomail('bcc');" class="btn col-lg-6 col-md-6 col-sm-6"><?php echo lang('mail_bcc'); ?><i class="fa fa-angle-double-right"></i></button>
                                            <div class="clr"></div>
                                        </div>
                                    </div>

                                    <div class="bd-mail-detail form-group">
                                        <div class=" bd-search-head">
                                            <label><?php echo lang('mail_attachments'); ?>:</label>

                                        </div>
                                        <?php
                                        if (isset($mail_files) && count($mail_files) > 0) {
//                                $file_img = $campaign_data[0]['file'];
//                                $img_data = explode(',', $file_img);
                                            $i = 15482564;

                                            foreach ($mail_files as $image) {
                                                if ($image['file_name_app'] == 0) {
                                                    $path = $image['file_path'];
                                                    $name = $image['file_name'];
                                                    $pathrel = $image['file_path_abs'];
                                                    $arr_list = explode('.', $name);
                                                    $arr = basename($name);
                                                    if (file_exists($path)) {
                                                        ?>
                                                        <div class="pad-10 text-left"> 
                                                            <a href='<?php echo base_url('Mail/download/' . $image['auto_id']); ?>' target="_blank">
                                                                <?php echo $name; ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="pad-10 text-center"> </div>
                                        <?php } ?>

                                        <div class="clr"></div>
                                    </div>

                                    <!--                <div class=" bd-search-head">
                                                      <label>Attachments:</label>
                                                     
                                                    </div>
                                                    
                                                   <div class="pad-10 text-center"> 
                                                   <a class="width-100 btn small-white-btn" href="#">Import Prospect (CSV) </a> </div>
                                                  </div>-->

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



                                </div>
                            </div>
                    </form>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
            </div>
        </div>
    </div>
</div>

<script>

    $('#searchContact').keyup(function () {

        var inputVal = $(this).val();

        if (inputVal.length > 2) {
            searchContactList(inputVal);
        } else {
            searchContactList('');
        }
    });

    function searchContactList(inputVal) {

        $.ajax({
            url: "<?php echo base_url('Mail/searchContacts'); ?>",
            data: {searchValue: inputVal},
            type: "post",
            //dataType: "json",
            beforeSend: function () {
                //$.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> Please wait...'});
            },
            success: function (result)
            {
                $('#searchList').html(result);
            },
        });
    }

    $(document).ready(function () {
        $('#compose-form').parsley();
    });

    $('.delimg').on('click', function () {

        var divId = ($(this).attr('data-id'));
        var imgName = ($(this).attr('data-name'));
        var dataUrl = $(this).attr('data-href');
        var dataPath = $(this).attr('data-path');
        var str1 = divId.replace(/[^\d.]/g, '');

        BootstrapDialog.confirm("<?php echo lang('mail_delete_confirm'); ?>", function (result) {
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
                        //alert("File \"" + file.name + "\" is too big.Max allowed size is 20 MB.");
                        alert("<?= lang('mail_file'); ?> \"" + file.name + "\" <?= lang('mail_file_size'); ?>");
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
                    alert("<?php echo lang('mail_file_compute_lenght'); ?>");
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
                    BootstrapDialog.alert("<?php echo lang('mail_save_concept_alert'); ?>");
                }
            });
        }
        return false;


    }
    $(document).ready(function () {
        //Set Editor For Estimate Content


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


<script>

    $("body").on('click', '#selectall', function () {
        var checkAll = $("#selectall").prop('checked');
        if (checkAll) {
            $("input[name='checkedIds[]']").prop("checked", true);
        } else {
            $("input[name='checkedIds[]']").prop("checked", false);
        }
    });

    $("body").on('click', "input[name='checkedIds[]']", function () {
          if ($("input[name='checkedIds[]']:checked").length > 1)
        {
            $('#replyEmail').hide();
            $('#replyAll').hide();
            $('#forwardEmail').hide();



        }
        else {
            $('#replyEmail').show();
            $('#replyAll').show();
            $('#forwardEmail').show();
        }

        if ($("input[name='checkedIds[]']").length == $("input[name='checkedIds[]']:checked").length) {
            $("#selectall").prop("checked", true);
        } else {
            $("#selectall").prop("checked", false);
        }
    });



    $("body").on('click', '#flagMail', function () {

        var checkedList = [];
        $("input[name='checkedIds[]']:checked").each(function () {
            checkedList.push($(this).attr('id'));
        });

        var finalCheckedFlagList = checkedList.join();

        if (finalCheckedFlagList.length === 0) {
            BootstrapDialog.alert("<?php echo lang('mail_select_atleast_one'); ?>");
            return false;
        }

        BootstrapDialog.confirm("<?php echo lang('mail_select_flag'); ?>", function (result) {

            if (result) {
                $.ajax({
                    url: "<?php echo base_url('Mail/movetoImportant'); ?>",
                    data: {ids: finalCheckedFlagList},
                    type: "post",
                    dataType: "json",
                    beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
                    },
                    success: function (d)
                    {
                        if (d.status) {
                            BootstrapDialog.alert("<?= lang('mail_moved_important') ?>");

                            $("input[name='checkedIds[]']:checked").each(function () {
                                $(this).parent().parent().remove();
                            });
                            $.unblockUI();


                        }
                    }
                });
            }
        });

    });

    $("body").on('click', '#trashMail', function () {

        // $('#trashMail').click(function () {
        // $('#trashMail').click(function () {
        if ($('.Trash').hasClass('active'))
        {
            return false;
        }
        var checkedList = [];
        $("input[name='checkedIds[]']:checked").each(function () {
            checkedList.push($(this).attr('id'));
        });

        var finalCheckedList = checkedList.join();

        if (finalCheckedList.length === 0) {
            BootstrapDialog.alert("<?php echo lang('mail_select_atleast_one'); ?>");
            return false;
        }

        BootstrapDialog.confirm("<?= lang('mail_select_remove') ?>", function (result) {

            if (result) {
                $.ajax({
                    url: "<?php echo base_url('Mail/movetoTrash'); ?>",
                    data: {ids: finalCheckedList},
                    type: "post",
                    dataType: "json",
                    beforeSend: function () {
                        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
                    },
                    success: function (d)
                    {
                        if (d.status) {
                            BootstrapDialog.alert("<?php echo lang('mail_moved_trash'); ?>");

                            $("input[name='checkedIds[]']:checked").each(function () {
                                $(this).parent().parent().remove();
                            });
                            $('.Trash').trigger('click');
                            $.unblockUI();


                        }
                    }
                });
            }
        });

    });

    $("body").on('click', '.starred', function () {

        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/moveMessage'); ?>",
            data: {id: $(this).data('id'), 'path': 'starred'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                // window.location.href = window.location.href;
                if (d.status == 1)
                {
                    BootstrapDialog.alert("<?php echo lang('mail_marked_starred'); ?>");

                    $(el).removeClass('starred');
                    $(el).addClass('unstarred');
//                    window.location.href = window.location.href;
                    $('#star_' + id).addClass('fa-star');
                    $('#star_' + id).removeClass('fa-star-o');
                    $.unblockUI();
                }
            }
        });
    });
    $("body").on('click', '.unstarred', function () {
        var id = $(this).data('id');

        $.ajax({
            url: "<?php echo base_url('Mail/moveMessage'); ?>",
            data: {id: $(this).data('id'), 'path': 'unstarred'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
//                window.location.href = window.location.href;
                if (d.status == 1)
                {
                    BootstrapDialog.alert("<?php echo lang('mail_removed_starred'); ?>");
                    $(el).removeClass('unstarred');
                    $(el).addClass('starred');
                    $('#star_' + id).removeClass('fa-star-0');
                    $('#star_' + id).addClass('fa-star');

////                    alert("done");
//                    $('#' + $(this).data('id')).removeClass('fa-star');
//                    $('#' + $(this).data('id')).addClass('fa-star-0');
                    //  window.location.href = window.location.href;
                }
            }
        });
    });
    $("body").on('click', '.flagged', function () {

        //$('.flagged').click(function () {
        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/markasFlagged'); ?>",
            data: {id: $(this).data('id'), 'path': 'flagged'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                BootstrapDialog.alert("<?php echo lang('mail_marked_flag'); ?>");
                // window.location.href = window.location.href;
                if (d.status == 1)
                {
                    //$.unblockUI();
//                    alert("done");
                    $(el).addClass('unflagged');
                    $(el).removeClass('flagged');
                    $(el).parent('div').addClass('bd-in-mark');
                    $(el).closest('li').addClass('bd-in-mark');
                    $.unblockUI();
                    //  $(elm).parent('div').removeClass('bd-in-mark');

                }
            }
        });
    });
    $("body").on('click', '.unflagged', function () {

        //$('.flagged').click(function () {
        var id = $(this).data('id');
        var el = $(this);
        $.ajax({
            url: "<?php echo base_url('Mail/markasFlagged'); ?>",
            data: {id: $(this).data('id'), 'path': 'INBOX'},
            type: "post",
            dataType: "json",
            beforeSend: function () {
                $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            },
            success: function (d)
            {
                BootstrapDialog.alert("<?php echo lang('mail_marked_unflag'); ?>");
                if (d.status == 1)
                {
                    $(el).removeClass('unflagged');
                    $(el).addClass('flagged');
                    $(el).parent('div').removeClass('bd-in-mark');
                    $(el).closest('li').removeClass('bd-in-mark');
//                    $(elm).parent('div').removeClass('bd-in-mark');
                    $.unblockUI();
//                    alert("done");
//                    $('#' + $(this).data('id')).removeClass('fa-star');
//                    $('#' + $(this).data('id')).addClass('fa-star-0');
                }
            }
        });
    });

    $("body").on('click', '.mail-tr', function () {
        //$('.mail-tr').click(function () {
        $('.mail-tr').removeClass("ActiveTr");
        $(this).addClass("ActiveTr");
    });

</script>

<script>
    $(document).ready(function () {

        //serch by enter
        $('#searchtext').keyup(function (event)
        {
            if (event.keyCode == 13) {
                data_search('changesearch');
            }

        });

    });
    function markasUnread(uid)
    {

        $.ajax({
            url: "<?php echo base_url('Mail/markasRead'); ?>",
            type: "POST",
            data: {'uid': uid},
            success: function (d)
            {
                $('span#' + uid).removeClass('font-bold');
            }

        });

    }

    //Search data
    function data_search(allflag)
    {
        var uri_segment = $("#uri_segment").val();
        var request_url = '';
        if (uri_segment == 0)
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index/' + uri_segment;
        } else
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/' + uri_segment;
        }
        var boxtype = $('#refreshBn').attr('data-boxtype');

        $.ajax({
            type: "POST",
            url: request_url,
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: allflag, 'boxtype': boxtype
            },
            success: function (html) {
                $("#common_div").html(html);
            }
        });
        return false;
    }
    function reset_data()
    {
        $("#searchtext").val("");
        apply_sorting('', '');
        data_search('all');
    }

    /*function reset_data_list(data)
     {
     $("#searchtext").val(data);
     apply_sorting('', '');
     data_search('all');
     }*/

    function changepages()
    {
        data_search('');
    }

    function apply_sorting(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);

        if (sortfilter != '' || sorttype != '') {
            data_search('changesorting');
        }
    }
    //pagination
    $('body').on('click', '#common_tb ul.bd-inbox-pagin a.ajax_paging', function (e) {
        var boxtype = $('#refreshBn').attr('boxtype');
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), 'boxtype': boxtype
            },
            /*
             beforeSend: function () {
             $('#common_div').block({message: 'Loading...'});
             },
             */
            success: function (html) {
                $("#common_div").html(html);
                //    $.unblockUI();
            }
        });
        return false;

    });
    function forwardEmail()
    {

        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('forward');
                window.location.href = url;
            });
        }
         else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function replyEmail()
    {

        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('reply');
                window.location.href = url;
            });
        }
         else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function replyAll()
    {
        if ($('.ActiveTr').length > 0) {
            $('.ActiveTr').each(function () {
                var url = $(this).data('replyall');
                window.location.href = url;
            });
        }
         else
        {
            BootstrapDialog.alert("<?= lang('mail_select_one') ?>");
            return false;
        }
    }
    function updateEmails(type)
    {
        if (type != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            $.ajax({
                url: "<?php echo base_url('Mail/getEmails'); ?>?type=full",
                type: "GET",
                success: function (d)
                {
                    BootstrapDialog.alert("<?php echo lang('mail_inbox_update'); ?>");
                    window.location.href = window.location.href;
                    if (d == 'done')
                    {
                        $.ajax({
                            url: "<?php echo base_url('Mail/leftBarCount'); ?>",
                            type: "GET",
                            success: function (d)
                            {
                                $('#leftbar').html(d);
                                $.unblockUI();
                            }
                        });
                    }
                }

            });
        }

    }
    $("body").on('click', '#refreshBn', function () {
//    $('#refreshBn').click(function () {

        var type = $(this).data('boxtype');
        //console.log(type);
        if (type != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            $.ajax({
                url: "<?php echo base_url('Mail/getEmails'); ?>?type=full",
                type: "POST",
                data: {'manualSync': 'yes', 'boxtype': type, 'folderName': type},
                success: function (d)
                {

                    if (d == 'done')
                    {
                        $.ajax({
                            url: "<?php echo base_url('Mail/Index'); ?>?type=full",
                            type: "POST",
                            data: {'result_type': 'ajax', 'boxtype': type, 'folderName': type},
                            success: function (d)
                            {
                                $('#main_div').html(d);
                                $(this).data('boxtype', type);
                                BootstrapDialog.alert(type + " Updated!");

                                $('#refereshLeftbox').trigger('click');
                            }

                        });
                    }
                }

            });
        }


    });
    function getMailBoxData(boxtype, id)
    {
        if (boxtype != '')
        {
            $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait'); ?>'});
            $.ajax({
                url: "<?php echo base_url('Mail/getEmails'); ?>",
                type: "POST",
                data: {'boxtype': boxtype, 'folderName': boxtype},
                success: function (d)
                {
                    $('#refreshBn').data('boxtype', boxtype);
                    //BootstrapDialog.alert(boxtype+" Updated!");
                    // window.location.href = window.location.href;
                    if (d == 'done')
                    {
                        $.ajax({
                            url: "<?php echo base_url('Mail/Index'); ?>?type=full",
                            type: "POST",
                            data: {'result_type': 'ajax', 'boxtype': boxtype, 'folderName': boxtype},
                            success: function (d)
                            {
                                $('#refreshBn').data('boxtype', boxtype);
                                $('.leftbx').removeClass('active');
                                $('#' + id).addClass('active');
//                                $('#refereshCode li button').attr('onClick', "updateEmails(" + boxtype + ")");
                                $('#main_div').html(d);
                                var currBoxType = boxtype.charAt(0).toUpperCase() + boxtype.slice(1).toLowerCase();
                                $('#currentBoxType').html(currBoxType);
                                $.unblockUI();
                                //  $('#refereshLeftbox').trigger('click');

                            }

                        });

                    }
                }

            });
        }

    }

</script>
<script>
    $(document).ready(function () {

        $('body').delegate('[data-toggle="ajaxModal"]', 'click',
                function (e) {
                    $('#ajaxModal').remove();
                    e.preventDefault();
                    var $this = $(this)
                            , $remote = $this.data('remote') || $this.attr('data-href')
                            , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                    $('body').append($modal);
                    $modal.modal();
                    $modal.load($remote);
                    //$("body").removeClass("modal-open");
                    //$("body").css("padding-right", "0 !important");

                }
        // $('#ajaxModal').css({height:"350px", overflow:"auto"});
        );

    });
    $('body').on('click', '#refereshLeftbox', function () {
        var id = $('.leftbx.active').attr('id');
        $.blockUI({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> <?php echo lang('please_wait_leftbar'); ?>'});
        $.ajax({
            url: "<?php echo base_url('Mail/leftBarCount'); ?>",
            type: "GET",
            success: function (d)
            {
                $('#leftbar').html(d);
                $('.leftbx').removeClass('active');
                $('#' + id).addClass('active');
                $.unblockUI();
            }
        });

    });
</script>
