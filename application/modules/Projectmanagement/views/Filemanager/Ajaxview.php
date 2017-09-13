<?php $i = 0; ?>
<div id="image-filemanger">
    <div class="filemanager-body  " >
         <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h3 class="white-link"><?php echo lang('browse');?></h3>
                    </div>
                    <div class="col-xs-12 col-sm-6 text-right">
                 <a data-href="<?php echo base_url('Projectmanagement/Filemanager/loadAjaxView/?dir=' . rawurlencode($parent) . '&is_crm=' . $is_crm); ?>" data-toggle="tooltip" title="" id="button-parent" class="btn btn-white" title="<?php echo lang('up'); ?>"><i class="fa fa-level-up"></i></a> 
                        <a data-href="<?php echo base_url('Projectmanagement/Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&is_crm=' . $is_crm); ?>" data-toggle="tooltip" title="<?php echo lang('refresh');?>" id="button-refresh" class="btn btn-white"><i class="fa fa-refresh"></i></a>
                        <?php if (checkPermission('Filemanager', 'add')) { ?>
                            <button type="button" data-toggle="tooltip"  id="button-folder"  class="btn btn-white"><?php echo lang('create_folder');?></button>
                            <button type="button" data-toggle="tooltip"   id="button-upload" class="btn btn-white"><?php echo lang('upload_file');?></button>
                        <?php } ?>
                       <?php if (checkPermission('Filemanager', 'delete')) { ?>
                        <button type="button" data-toggle="tooltip" title="<?php echo lang('delete');?>"  id="button-delete" class="btn btn-white"><i class="fa fa-trash-o"></i></button>
                        <?php } ?>

                        <a class="btn btn-white " id="list" href="javascript:;" title="<?php echo lang('list_view');?>"><span class="glyphicon glyphicon-th-list">
                            </span></a> <a class="btn btn-white " id="grid" href="javascript:;" title="<?php echo lang('grid_view');?>"><span class="glyphicon glyphicon-th"></span></a>

                    </div>
                    <div class="clr"></div>
                           <div cass="box" id="folder_box" style="display:none">
                <input placeholder="<?php echo lang('folder_name');?>" type="text" name="folder_name" id="folder_name">
                <input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo base_url('Projectmanagement/Filemanager/loadAjaxView/?dir=' . rawurlencode($refresh) . '&is_crm=' . $is_crm); ?>">
                <input type="hidden" name="path" id="path" value="<?php echo $refresh; ?>">
                <input type="button" name="create_folder" id="create_folder" value="<?php echo lang('create');?>">
            </div>
                </div>
        <?php if(count($images)>0){?>
        <ul id="boximggrid" class="list-unstyled bd-img-upload whitebox">
            <?php 
             foreach ($images as $image) { ?>
                <li class="ui-state-default  bd-dragimage" >
                    <div class=" text-center eachImage">
                        <?php if ($image['type'] == 'directory') { 
                             $folderPth = $image['path'];
                            ?>
                              <div class="text-center eachImage">
                                  <a title="<?php echo $image['name']; ?>" data-href="<?php echo base_url('Projectmanagement/Filemanager/loadAjaxView/?dir=' . rawurlencode($folderPth) . '&modal=true&is_crm=' . $is_crm); ?>" class="directory" style="vertical-align: middle;"><div class="bd-file-minheight"><i class="fa fa-folder-ico"></i></div>
                                            <label class="mar-tb0">
                                                <?php echo (strlen($image['name']) > 15) ? substr($image['name'], 0, 15) . '...' : $image['name']; ?></label></a></div>
                    <?php } ?>
                        <?php if ($image['type'] == 'image') { ?>


                            <a onclick="updateThumb();" id="li_<?php echo $i; ?>" title="<?php echo $image['name']; ?>" href="javascript:;" data-value="<?php echo $image['path']; ?>" data-name="<?php echo $image['name']; ?>"  class="">
                               <div class="preview"> <?php if (in_array($image['ext'], array('jpg', 'png', 'jpeg'))) { ?>
                                    <div class="bd-file-minheight"><img src="<?php echo $image['href']; ?>" class=" img-responsive thumbnail"   alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" /></div>
                                <?php } else { ?>
                                 <div class="bd-file-minheight"> <div class="image_ext"><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png"  width="75"/><p class="img_show"><?php echo $image['ext']; ?></p></div></div>
                                <?php } ?>    <label class="mar-tb0">
                                    <input type="hidden" name="path[]" id="selectFiles" value="<?php echo $image['path']; ?>" data-name="<?php echo $image['name']; ?>" />

                                    <?php echo (strlen($image['name']) > 15) ? substr($image['name'], 0, 15) . '...' : $image['name']; ?></label></div>
                            </a>
                            <div class="webui-popover-content">

                                <button class="btn btn-default" onclick='showPreview("<?php echo base_url($image['path'] . '/' . $image['name']); ?>");'><i class="fa fa-search"></i></button>
                                <?php /* <input type="checkbox" name="path[]" data-id="li_<?php echo $i; ?>" id="selectedItems" class="checkedids " data-name="<?php echo $image['name']; ?>" value="<?php echo $image['path']; ?>" /> */ ?>
        <!--                                <a href='<?php echo base_url($task_view . '/download/' . $image['task_file_id']); ?>' target="_blank" class="btn btn-default"><i class="fa fa-download "></i></a>-->

                            </div>

                        <?php } ?>
                    </div>

                </li>
                <?php
                $i++;
            } 
            ?>
               
          
        </ul>
         <?php }else {?>
         <div class="whitebox text-center bd-min-height"><?php echo lang('NO_RECORD_FOUND');?></div> 
         <?php }?>

        <div class="row whitebox boximg hidden" id='boximglist'>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-left"> <?php echo lang('file_name');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($images as $image) { ?>
                        <tr>
                            <td>
                                <?php if ($image['type'] == 'directory') { ?>

                                    <a data-href="<?php echo base_url('Projectmanagement/Filemanager/loadAjaxView/?dir=' . rawurlencode($image['path']) . '&is_crm=' . $is_crm); ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-2x"></i> <?php echo $image['name']; ?></a>
                                <?php } ?>
                                <?php if ($image['type'] == 'image') { ?>
                                    <!-- <input type="checkbox" name="path[]" id="selectedItems" class="checkedids" data-name="<?php echo $image['name']; ?>" value="<?php echo $image['path']; ?>" /> -->

                                    <a data-href="<?php echo $image['href']; ?>" class="thumbnail" onclick="updateThumb();" target="_blank">
                                        <label>
                                            <?php echo $image['name']; ?></label>
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table> 
        </div>

    </div>
</div>

<script>
    
   
   // $('.imgpopover').webuiPopover({trigger: 'hover'});
    function showPreview(elurl)
    {
        $('#previewImg').attr('src', elurl);
        $('.webui-popover').css('z-index', '1');
        $('#imgviewpopup').modal('show');
    }
    $('.webui-popover-content .checkedids').click(function () {
        var id = $(this).attr('data-id');
        if ($(this).prop('checked')) {
            $('#' + id).addClass('active');
        } else {
            $('#' + id).removeClass('active');
        }

    });
//    $('#imgviewpopup').on('hidden.bs.modal', function () {
//        $('body').addClass('modal-open');
//    });
</script>