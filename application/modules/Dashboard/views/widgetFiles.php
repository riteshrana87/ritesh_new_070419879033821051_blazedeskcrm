<div class="whitebox" >
    <div class="mediagalleryPopupData">
        <div class="row">
            <div class="col-sm-5"><a href="<?php echo base_url('Dashboard/getFilesList/?dir=' . rawurlencode($parent) . '&modal=true'); ?>" data-toggle="tooltip" title="" id="button-parent" class="btn btn-default"><i class="fa fa-level-up"></i></a> 
                <a href="<?php echo base_url('Dashboard/getFilesList/?dir=' . rawurlencode($refresh) . '&modal=true'); ?>" data-toggle="tooltip" title="<?php echo 'referesh'; ?>" id="button-refresh" class="btn btn-default"><i class="fa fa-refresh"></i></a>
    <!--       <button type="button" data-toggle="tooltip"  id="button-upload" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                <button type="button" data-toggle="tooltip" id="button-folder"  class="btn btn-default"><i class="fa fa-folder"></i></button>
                <button type="button" data-toggle="tooltip" id="button-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>-->
                <input type="hidden" name="returnUrl" id="returnUrl" value="<?php echo base_url('Dashboard/getFilesList/?dir=' .rawurlencode($refresh) . '&modal=true'); ?>">
                <input type="hidden" name="cnt" id="cnt" value="<?php echo count($images);?>">
            </div>
        </div>
        <hr />
        <ul id="selectable" class="list-unstyled bd-img-upload">
            <?php foreach ($images as $image) { ?>
                <li class="ui-state-default col-sm-2 col-lg-2 col-md-2">
                    <div class=" text-center">
                        <?php if ($image['type'] == 'directory') { ?>
                            <div class="text-center"><a title="<?php echo $image['name']; ?>" href="<?php echo base_url('Dashboard/getFilesList/?dir=' . rawurlencode($image['path']) . '&modal=true'); ?>" class="directory" style="vertical-align: middle;"><i class="fa fa-folder fa-5x"></i> <label class="mar-tb0">
                <!--                                        <input type="checkbox" name="path[]" value="<?php //echo $image['path'];                                      ?>" />-->
                                        <?php echo (strlen($image['name']) > 15) ? substr($image['name'], 0, 15) . '...' : $image['name']; ?></label></a></div>

                        <?php } ?>
                        <?php if ($image['type'] == 'image') { ?>
                            <a title="<?php echo $image['name']; ?>" href="javascript:;" data-value="<?php echo $image['path']; ?>" data-name="<?php echo $image['name']; ?>" class="thumbnail" onclick='showPreview("<?php echo base_url($image['path'] . '/' . $image['name']); ?>");'>
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
    <div class="clr"></div>
</div>
