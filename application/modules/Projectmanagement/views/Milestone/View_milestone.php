<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath   = $milestone_view . '/' . $formAction;
?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <!-- Modal content-->
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="modelMilestoneTitle"><?= $modal_title ?></div>
            </h4>
        </div>

        <div class="modal-body">

            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label"><?= lang ('milestone_name') ?> : </label>
                </div>
                <div class="col-sm-8">
                    <span><?= !empty($edit_record[0]['milestone_name']) ? $edit_record[0]['milestone_name'] : '' ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label"><?= lang ('milestone_code') ?> : </label>
                </div>
                <div class="col-sm-8">
                    <span><?= !empty($edit_record[0]['milestone_code']) ? $edit_record[0]['milestone_code'] : '' ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label"><?= lang ('RESPONSIBLE_EMPLOYEE') ?> : </label>
                </div>
                <div class="col-lg-8">
                    <span><?= !empty($edit_record[0]['res_user']) ? $edit_record[0]['res_user'] : '' ?></span>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label"><?= lang ('team_member') ?> : </label>
                </div>
                <div class="col-lg-8">
                    <span><?= !empty($edit_record[0]['user_name']) ? $edit_record[0]['user_name'] : '' ?></span>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label"><?= lang ('description') ?> : </label>
                </div>
                <div class="col-lg-8">
                    <span><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label"><?= lang ('start_date') ?> : </label>
                </div>
                <div class="col-sm-8 date">
                   <span> <?php if (isset($edit_record[0]['start_date']) && $edit_record[0]['start_date'] != '0000-00-00') {
                        echo configDateTime($edit_record[0]['start_date']);
                    }; ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label"><?= lang ('due_date') ?> : </label>
                </div>
                <div class="col-sm-6 date">
                    <span><?php if (isset($edit_record[0]['due_date']) && $edit_record[0]['due_date'] != '0000-00-00') {
                        echo configDateTime($edit_record[0]['due_date']);
                    }; ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label"><?= lang ('selecting_task') ?> : </label>
                </div>
                <div class="col-lg-8">
                    <span><?= !empty($edit_record[0]['assign_task']) ? $edit_record[0]['assign_task'] : '' ?></span>
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label"><?= lang ('file') ?> : </label>
                </div>
                
            </div>
            <div class="form-group row">
                <div class="col-lg-12 ">

                        <div class="row">
                            <div class="col-lg-12">
                                <?php

                                if (count($milestone_files) > 0) {
                                    //                                $file_img = $campaign_data[0]['file'];
                                    //                                $img_data = explode(',', $file_img);
                                    $i = 15482564;
                                    foreach ($milestone_files as $image) {
                                        $path = $image['file_path'];
                                        $name = $image['file_name'];
                                        $arr_list = explode('.', $name);
                                        $arr = $arr_list[1];
                                        //echo FCPATH . $path . $name;echo '<br>';
                                        if (file_exists($path . $name)) {
                                            ?>
                                            <div id="img_<?php echo $image['milestone_file_id']; ?>" class="eachImage">

                                                <span id="<?php echo $i; ?>" class="preview">
                                                <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                <a class="task-previmg" title="view" href='<?php echo base_url($milestone_view . '/download/' . $image['milestone_file_id']); ?>' >
                                                <?php } else { ?>
                                                <a title="view" href='javascript:;' >
                                                <?php } ?>
                                                <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                <img src="<?= base_url($path . $name); ?>" class="img-responsive" />
                                                <?php } else { ?>
                                                <div><img src="<?php echo base_url(); ?>/uploads/images/icons64/file-64.png" class="img-responsive"/>
                                                  <p class="img_show"><?php echo $arr; ?></p>
                                                </div>
                                                <?php } ?>
                                                </a>
                                                <p class="img_name"> <span><?php echo (strlen($name) > 15) ? substr($name, 0, 15) . '...' :$name; ?></span>
                                                  <?php if ($arr == 'jpg' || $arr == 'jpeg' || $arr == 'png' || $arr == 'gif') { ?>
                                                  <?php /*<button class="btn btn-default" onclick='showPreview("<?php echo base_url($image['file_path'] . '/' . $image['file_name']); ?>");'><i class="fa fa-search"></i></button>*/ ?>
                                                  <?php } ?>
                                                  <?php /*<a href='<?php echo base_url($milestone_view . '/download/' . $image['milestone_file_id']); ?>' target="_blank" class="btn btn-default"><i class="fa fa-download "></i></a> <span class="overlay" style="display: none;"> <span class="updone">100%</span></span>*/ ?> 
                                                  <!--                                                <input type="hidden" value="<?php echo $name; ?>" name="fileToUpload[]">--> 
                                                </span> 

                                            </div>
                                        <?php } else{}?>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                <?php } ?>

                            </div>
                            <!--                                                    <div class="col-lg-4 text-right">
                                                                                    <button class="btn btn-primary mar_b6">Select attachment</button>
                                                                                    <label class="custom-upload btn btn-primary">
                                                                                        <input type="file" name="upload_file">
                                                                                        Choose file</label>
                                                                                </div>-->
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="clr"></div>
            </div>
        </div>

        <div class="modal-footer">
            <center>
                <input type="hidden" id="milestone_id" name="milestone_id"
                       value="<?= !empty($edit_record[0]['milestone_id']) ? $edit_record[0]['milestone_id'] : '' ?>">
                <input type="hidden" id="display" name="display" value="<?= !empty($home) ? $home : '' ?>">

        </div>

    </div>
</div>
</div><!-- /.modal-dialog -->
<?php //echo form_close(); ?>
<!-- Modal -->
<div class="modal fade" id="imgviewpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#imgviewpopup').modal('hide');"  aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Preview</h4>
            </div>
            <div class="modal-body">
                <img id="previewImg" class="img-responsive">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  onclick=" $('#imgviewpopup').modal('hide');">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    function showPreview(elurl)
    {
        $('#previewImg').attr('src', elurl);
        $('#imgviewpopup').modal('show');
    }
    $('#imgviewpopup').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
    });
</script>

