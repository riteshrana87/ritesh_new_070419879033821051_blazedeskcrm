<?php
defined ('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath   = $project_status_view . '/' . $formAction;
?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <!-- Modal content-->
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3 class="modal-title">
                <div class="modelMilestoneTitle"><?= $modal_title ?></div>
            </h3>
        </div>

        <div class="modal-body">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang ('status_name') ?> : </label>
                </div>
                <div class="col-sm-9">
                    <span><?= !empty($edit_record[0]['status_name']) ? $edit_record[0]['status_name'] : '' ?></span>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang ('status_color') ?> : </label>
                </div>
                <div class="col-sm-3">
                    <span class="color_box"
                          style="background-color:<?= !empty($edit_record[0]['status_color']) ? $edit_record[0]['status_color'] : '' ?>">&nbsp;</span>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang ('status_font_icon') ?> : </label>
                </div>
                <div class="col-sm-9">
                    <?php $icon = !empty($edit_record[0]['status_font_icon']) ? $edit_record[0]['status_font_icon'] : '' ?>
                    <i class="fa fa-<?= $icon ?> blackcol"></i>
                </div>

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


