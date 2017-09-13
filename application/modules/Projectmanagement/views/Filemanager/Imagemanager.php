<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <?php echo $this->breadcrumbs->show(); ?>
        </ul>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-3  text-right pull-right">

        <div class="pad-tb6 ">
            <div class="pull-right settings"> <a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="pull-right search-top">
                <form id="searchForm" class="navbar-form navbar-left pull-right">
                    <div class="input-group">
                        <input type="text" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR'); ?>" name="search_input" id="search_input" class="form-control">
                        <span class="input-group-btn">
                            <button title="<?php echo lang('search'); ?>" onclick="javascript:;"  type="button" id="submit" name="submit" class="btn btn-default"><i class="fa fa-search fa-x"></i></button>
                            <button title="<?php echo lang('reset'); ?>" onclick="javascript:;" onsclick="reset_data();" class="btn btn-default" type="reset"><i class="fa fa-refresh fa-x"></i></button>
                        </span> </div>
                    <!-- /input-group -->
                </form>
            </div>
        </div>
    </div>
</div>
<div class="box mediagalleryPopupData">

    <div class="">

        <?php $this->load->view('Ajaxview'); ?>
    </div>
</div>
<div class="modal fade modal-image" id="folderCreation" tabindex="-1" role="dialog" data-refresh="true" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#folder_name').val('');
                        $('#folderCreation').modal('hide');" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo lang('create_folder'); ?></h4>
            </div>
            <form method="post" id="fldrfrm">
                <div class="modal-body" id="modbdy">
                    <div class="form-group">
                        <input placeholder="<?php echo lang('folder_name'); ?>" type="text" name="folder_name" class="form-control folder_name" id="folder_name">
                        <ul class="parsley-errors-list filled hidden" id="flderErr" ><li class="parsley-required"><?php echo lang('folder_name_alpha'); ?></li></ul>
                        <input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo base_url('Projectmanagement/Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&is_crm=' . $is_crm); ?>">
                        <input type="hidden" name="path" id="path" value="<?php echo $refresh; ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-success" name="create_folder" id="create_folder" value="<?php echo lang('create_folder'); ?>">
                    <button type="button" class="btn btn-default" onClick="$('#folder_name').val('');
                            $('#folderCreation').modal('hide');"><?php echo lang('close'); ?></button>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<style>

</style>

<!-- Modal -->
<div class="modal fade" id="imgviewpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#imgviewpopup').modal('hide');"  aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo lang('EST_LBL_VIEW_PREVIEW'); ?></h4>
            </div>
            <div class="modal-body">
                <img id="previewImg" class="img-responsive" alt="no-img">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  onclick=" $('#imgviewpopup').modal('hide');"><?php echo lang('close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script>
    function updateThumb()
    {
        //  alert("x");
        $("#boximggrid").selectable();
        //  alert("x1");
        $("#boximggrid").on("selectablestart", function (event, ui) {
            event.originalEvent.ctrlKey = true;
            //    alert("x3");
        });
    }

    var returnUrl = $.trim($('#returnUrl').val());
    var path = $.trim($('#path').val());

    $("body").delegate(".directory", "click", function (e) {

        e.preventDefault();

        $('#image-filemanger').load($(this).attr('data-href'));
    });

    $("body").delegate("#button-parent", "click", function (e) {

        e.preventDefault();

        $('#image-filemanger').load($(this).attr('data-href'));
    });

    $("body").delegate("#button-refresh", "click", function (e) {
        e.preventDefault();

        $('#image-filemanger').load($(this).attr('data-href'));
    });
    $("body").delegate("#button-folder", "click", function (e) {

        $('.folder_name').val('');
        $('#folderCreation').modal('show');
        e.preventDefault();
    });

    $("body").delegate("#create_folder", "click", function (e) {
        var returnUrl = $.trim($('#returnUrl').val());
        var path = $.trim($('#path').val());
        var folderName = $.trim($('.folder_name').val());
        var re = /^\w+( \w+)*$/;
        if (folderName == null || folderName == "")
        {
            $('.parsley-required').html('<?php echo lang('input_folder'); ?>');
            $('#flderErr').removeClass('hidden');
            //$('.errorfolder').html("please input folder name");
            return false;
        }
        else
        {
            if (re.test(folderName) == false)
            {
                $('.parsley-required').html('<?php echo lang('folder_name_alpha'); ?>');

                $('#flderErr').removeClass('hidden');
                return false;
            }
            else
            {
                $('#flderErr').addClass('hidden');
            }
        }
        $.ajax({
            url: "<?php echo base_url('Projectmanagement/Filemanager/makeDir'); ?>",
            data: {'name': folderName, 'path': path},
            dataType: 'json',
            type: "POST",
            success: function (d)
            {
                if (d.status == '1')
                {
                    $('#image-filemanger').load(returnUrl);
                    $('.folder_name').val();
                    $('#folderCreation').modal('hide');
                    $('#image-filemanger').load(returnUrl);

                }
                else
                {
                    var delete_meg = "<?php echo $this->lang->line('problem_creating_folder'); ?>";
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
            }

        });
    });
    $("body").delegate("#button-upload", "click", function (e) {
        $('#form-upload').remove();
        var returnUrl = $.trim($('#returnUrl').val());
        var path = $.trim($('#path').val());
        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" id="uploadFileId" name="file[]" multiple="true" value="" /></form>');

        $('#form-upload #uploadFileId').trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function () {
            if ($('#form-upload #uploadFileId').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: "<?php echo base_url('Projectmanagement/Filemanager/upload/?path='); ?>" + path,
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#button-upload i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                        $('#button-upload').prop('disabled', true);
                    },
                    complete: function () {
                        $('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
                        $('#button-upload').prop('disabled', false);
                    },
                    success: function (json) {
                        if (json['error']) {
                            var delete_meg = json['error'];
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

                        if (json['success']) {
                            var delete_meg = json['success'];
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

                            $('#image-filemanger').load(returnUrl);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    $("body").delegate("#button-delete", "click", function (e) {
        var fileArr = [];
        //var checkedlen = $('#selectedItems:checked').length;
        var checkedlen = $('.ui-selected').length;
        if (checkedlen > 0)
        {
            $(".ui-selected a").each(function () {
                var name = $(this).attr('data-name');
                var path = $(this).attr('data-value');
                var url = path + name;
                fileArr.push("" + url + "");
            });
            //console.log(fileArr);return false;
            var delete_meg = "<?php echo lang('common_delete_file'); ?>";
            BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information'); ?>',
                        message: delete_meg,
                        buttons: [{
                                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }, {
                                label: '<?php echo $this->lang->line('ok'); ?>',
                                action: function (dialog) {
                                    $.ajax({
                                        url: "<?php echo base_url('Projectmanagement/Filemanager/deleteImage'); ?>",
                                        data: {name: fileArr},
                                        type: "POST",
                                        dataType: "json",
                                        success: function (data)
                                        {
                                            if (data.status == 1)
                                            {
                                                $('.webui-popover-content .checkedids').prop('checked', false);
                                                $('#image-filemanger').load($('#button-refresh').attr('data-href'));
                                                var delete_meg = "<?php echo lang('file_delete_message'); ?>";
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
                                            else
                                            {
                                                var delete_meg = data.error;
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
                                        },
                                        error: function ()
                                        {
                                            console.log('Error in call');
                                        }
                                    });
                                    dialog.close();
                                }
                            }]
                    });

        }
        else
        {
            var delete_meg = "<?php echo lang('no_file_error'); ?>";
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
//common_delete_file
    });
    $("body").delegate("#list", "click", function (e) {
        $('.boximg').removeClass('hidden');
        $('#boximglist').removeClass('hidden');
        $('#boximggrid').addClass('hidden');

    });
    $("body").delegate("#grid", "click", function (e) {
        $('.boximg').removeClass('hidden');
        $('#boximglist').addClass('hidden');
        $('#boximggrid').removeClass('hidden');

    });



</script>

<script>
    function showPreview(elurl)
    {
        //directory
        $('#previewImg').attr('src', elurl);
        $('#imgviewpopup').modal('show');
    }
//    $('#imgviewpopup').on('hidden.bs.modal', function () {
//        $('body').addClass('modal-open');
//    });
</script>

