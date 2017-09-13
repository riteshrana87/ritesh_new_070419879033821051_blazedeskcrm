<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="modal-dialog">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" title="<?php echo lang('close'); ?>" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <div class="modelTaskTitle"><?= lang('view') ?> <?= lang('project') ?> - <?= $modal_title ?></div>
                </h4>
            </div>

            <div class="modal-body">

                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="control-label"><?= lang('project_name') ?> : </label>
                    </div>
                    <div class="col-sm-9">
                        <span><?= !empty($edit_record[0]['project_name']) ? $edit_record[0]['project_name'] : '' ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="control-label"><?= lang('project_code') ?> : </label>

                    </div>
                    <div class="col-sm-9">

                        <span><?= !empty($edit_record[0]['project_code']) ? $edit_record[0]['project_code'] : '' ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="control-label"><?= lang('project_desc') ?> : </label>
                    </div>
                    <div class="col-sm-9">
                        <span><?= !empty($edit_record[0]['project_desc']) ? $edit_record[0]['project_desc'] : '' ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="control-label"><?= lang('client') ?> : </label>
                    </div>
                    <div class="col-sm-9">
                        <span><?= !empty($edit_record[0]['prospect_name']) ? $edit_record[0]['prospect_name'] : '' ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="  control-label"><?= lang('team_member') ?> :</label>
                    </div>
                    <div class="col-sm-9">
                        <span><?= !empty($edit_record[0]['user_name']) ? $edit_record[0]['user_name'] : '' ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="control-label"><?= lang('start_date') ?> : </label>
                    </div>
                    <div class="col-sm-9 date">


                        <span><?php
if (isset($edit_record[0]['start_date']) && $edit_record[0]['start_date'] != '0000-00-00') {
    echo configDateTime($edit_record[0]['start_date']);
};
?></span>

                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="control-label"><?= lang('due_date') ?> : </label>
                    </div>
                    <div class="col-sm-9 date">

                        <span><?php
                            if (isset($edit_record[0]['due_date']) && $edit_record[0]['due_date'] != '0000-00-00') {
                                echo configDateTime($edit_record[0]['due_date']);
                            };
                            ?></span>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="control-label"><?= lang('project_budget') ?> : </label>
                    </div>
                    <div class="col-sm-9">

                        <span><?= !empty($edit_record[0]['project_budget']) ? $edit_record[0]['project_budget'] : '' ?></span>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-3">
                        <label class="control-label"><?= lang('project_icon') ?> : </label>
                    </div>
                    <div class="col-sm-9">

<?php if (!empty($edit_record[0]['project_icon']) && file_exists($edit_record[0]['icon_path'] . '/' . $edit_record[0]['project_icon'])) { ?>
                            <a class="task-previmg" title="view" href='<?php echo base_url($project_view . '/download/' . $edit_record[0]['project_id']); ?>' >
                                <img alt="No Image"
                                     src="<?= base_url() ?><?= $edit_record[0]['icon_path'] ?>/<?= $edit_record[0]['project_icon'] ?>"
                                     height="80" width="80">
                            </a>
<?php } else { ?><img alt="No Image" src="<?= base_url() ?>uploads/images/noimage.jpg"
                                 height="80" width="80"><?php } ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
</div>


