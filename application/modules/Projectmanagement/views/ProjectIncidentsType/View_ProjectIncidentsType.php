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
            <h4 class="modal-title">
                <div class="modelMilestoneTitle"><?= $modal_title ?></div>
            </h4>
        </div>

        <div class="modal-body">
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label"><?= lang ('status_name') ?> : </label>
                    <span><?= !empty($edit_record[0]['status_name']) ? $edit_record[0]['status_name'] : '' ?></span>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label"><?= lang ('status_color') ?> : </label>&nbsp;
                    <span class="color_box"
                          style="background-color:<?= !empty($edit_record[0]['status_color']) ? $edit_record[0]['status_color'] : '' ?>">&nbsp;</span>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-12">
                    <label class="control-label"><?= lang ('status_font_icon') ?> : </label>
                    <?php $icon = !empty($edit_record[0]['status_font_icon']) ? $edit_record[0]['status_font_icon'] : '' ?>
                    <i class="fa fa-<?= $icon ?> blackcol"></i>
                </div>

            </div>
        </div>

        <div class="modal-footer">
            

        </div>

    </div>
</div>
</div><!-- /.modal-dialog -->


