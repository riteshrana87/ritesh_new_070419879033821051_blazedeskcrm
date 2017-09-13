
<div class="mediagalleryPopupData">
    <div class="row">
        <div class="col-sm-12"><a data-href="<?php echo base_url('Projectmanagement/Filemanager/index/?dir=' . rawurlencode($parent) . '&modal=true&module='.$module); ?>" data-toggle="tooltip" title="<?php echo lang('up');?>" id="button-parent" class="btn btn-default"><i class="fa fa-level-up"></i></a> 
            <a data-href="<?php echo base_url('Projectmanagement/Filemanager/index/?dir=' . rawurlencode($refresh) . '&modal=true&module='.$module); ?>" data-toggle="tooltip" title="<?php echo 'referesh'; ?>" id="button-refresh" class="btn btn-default"><i class="fa fa-refresh"></i></a>
<!--                            <button type="button" data-toggle="tooltip"  id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i></button>
            <button type="button" data-toggle="tooltip" id="button-folder"  class="btn btn-default"><i class="fa fa-folder"></i></button>
                    <button type="button" data-toggle="tooltip" id="button-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>-->
            <button type="button" name="selectfiles" class="btn btn-success" id="selectfiles"><?php echo lang('use_selected'); ?></button> 

        </div>
        <div cass="box" id="folder_box" style="display:none">
            <input placeholder="<?php echo lang('folder_name');?>" type="text" name="folder_name" id="folder_name">
            <input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo base_url('Projectmanagement/Filemanager/index/?dir=' . rawurlencode($refresh) . '&modal=true&module='.$module); ?>">
            <input type="hidden" name="path" id="path" value="<?php echo rawurlencode($refresh); ?>">
            <input type="button" name="create_folder" id="create_folder" value="<?php echo lang('create');?>">
        </div>
    </div>
    <hr />
    <ul id="selectable" class="list-unstyled bd-img-upload">
        <?php foreach ($images as $image) { ?>
            <li class="ui-state-default col-sm-3 col-lg-3 col-md-3 ">
                <div class=" text-center">
                    <?php if ($image['type'] == 'directory') { ?>
                        <div class="text-center"><a title="<?php echo $image['name']; ?>" data-href="<?php echo base_url('Projectmanagement/Filemanager/index/?dir=' .rawurlencode($image['path']) . '&modal=true&module='.$module); ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-5x"></i> <label class="mar-tb0">
            <!--                                        <input type="checkbox" name="path[]" value="<?php //echo $image['path'];                   ?>" />-->
                                    <?php echo (strlen($image['name']) > 15) ? substr($image['name'], 0, 15) . '...' : $image['name']; ?></label></a></div>

                    <?php } ?>
                    <?php if ($image['type'] == 'image') { ?>
                        <a title="<?php echo $image['name']; ?>" href="javascript:;" data-value="<?php echo $image['path']; ?>" data-name="<?php echo $image['name']; ?>" class="thumbnail">
                            <?php if (in_array($image['ext'], array('jpg', 'png', 'jpeg'))) { ?>
                                <img src="<?php echo $image['href']; ?>" class="thumbnail-img" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" />
                            <?php } else { ?>
                                <i class="fa fa-file"></i>                   
                            <?php } ?>     <label class="mar-tb0">
                                <input type="hidden" name="path[]" id="selectFiles" value="<?php echo $image['path']; ?>" data-name="<?php echo $image['name']; ?>" />
                                <?php echo (strlen($image['name']) > 15) ? substr($image['name'], 0, 15) . '...' : $image['name']; ?></label>
                        </a>

                    <?php } ?>
                </div>

            </li>
        <?php } ?>
    </ul>
    <br />
</div>

<script>
    $(document).ready(function () {
        $("#selectable").selectable();
        $("#selectable").on("selectablestart", function (event, ui) {
            event.originalEvent.ctrlKey = true;
        });

    });
    var returnUrl = $.trim($('#returnUrl').val());
    var path = $.trim($('#path').val());
    $('a.directory').on('click', function (e) {
        e.preventDefault();

        $('#modbdy').load($(this).attr('data-href'));
    });

    $('.pagination a').on('click', function (e) {
        e.preventDefault();

        $('#modbdy').load($(this).attr('data-href'));
    });

    $('#button-parent').on('click', function (e) {
        e.preventDefault();

        $('#modbdy').load($(this).attr('data-href'));
    });

    $('#button-refresh').on('click', function (e) {
        e.preventDefault();

        $('#modbdy').load($(this).attr('data-href'));
    });
    $('#button-folder').on('click', function (e) {

        $('#folder_box').show();
        e.preventDefault();
    });
    $('#create_folder').click(function ()
    {
        var folderName = $.trim($('#folder_name').val());
        var path = $.trim($('#path').val());
        var returnUrl = $.trim($('#returnUrl').val());
        var re = /^[a-zA-Z].*/;

        if (folderName == '')
        {
             var delete_meg = "<?php echo lang('input_folder'); ?>";
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
        else
        {
            if (re.test(folderName) == false)
            {
                 var delete_meg = "<?php echo $this->lang->line('folder_name_alpha'); ?>";
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
        $.ajax({
            url: "Projectmanagement/Filemanager/makeDir",
            data: {'name': folderName, 'path': path},
            dataType: 'json',
            type: "POST",
            success: function (d)
            {
                if (d.status == '1')
                {
                    $('#modbdy').load(returnUrl);
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

    $('#button-upload').on('click', function () {
        $('#form-upload').remove();

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
                            alert(json['error']);
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $('#button-refresh').trigger('click');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    $('#selectfiles').on('click', function () {
        var checkedlen = $('.ui-selected').length;
        var html = '';
        if (checkedlen > 0)
        {
            $(".ui-selected").each(function () {
                console.log($(this));
                $(this).addClass("foo");
                var name = $(this).find('a').attr('data-name');
                var path = $(this).find('a').attr('data-value');
                if ($(this).find('a').attr('data-value').length>0 && $(this).find('a').attr('data-name').length>0) {
                    html += "<p>" + name + "</p><input type='hidden' name='gallery_files[]' id='gallery_files' value='" + name + "'><input type='hidden' name='gallery_path[]' id='gallery_path' value='" + path + "'>";
                }
            });
            $('.mediaGalleryImg').empty();
            $('.mediaGalleryImg').prepend(html);
            $('#modalGallery').modal('hide');

        }

        return false;
    });
    function getImgUrlToForm(url, name, path)
    {
        var imgpath = path + '/' + name;
        //<img src='" + url + "' height='50px' width='50px'>
        var html = "<p>" + name + "</p><input type='hidden' name='gallery_files[]' id='gallery_files' value='" + name + "'><input type='hidden' name='gallery_path[]' id='gallery_path' value='" + path + "'>";
        $('.mediaGalleryImg').empty();
        $('.mediaGalleryImg').prepend(html);
        $('#modalGallery').modal('hide');
    }
    $(".selectable").click(function (e) {
        $(this).toggleClass("selected");
    });

</script>

