<!-- Count task -->
<?php /*<div class="row mar_b6">
    <?php if (!empty($project_tasks_status)) {
        foreach ($project_tasks_status as $row) { ?>
            <a class="black-link" href="javascript:void(0);" onclick="displayStatus(<?= $row['status_id'] ?>);">
                <div class="col-xs-12 col-sm-3 col-md-3">
                    <div class="text-center white-label font-15em"><i
                            class="fa fa-<?= $row['status_font_icon'] ?>"></i> <?= !empty($row['total_task']) ? $row['total_task'] : 0 ?> <?= $row['status_name'] ?>
                    </div>
                </div>
            </a>
        <?php }
    } ?>
    <div class="clr"></div>
</div> */?>
<div class="row">
     <?php if (checkPermission ("Milestone", 'view')) { ?>
        <a class="black-link" href="<?= base_url ('Projectmanagement/Milestone') ?>">
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="text-center white-label font-15em"><i
                        class="fa fa-flag"></i> <?= lang ('milestone') ?></div>
            </div>
        </a>
    <?php } ?>
     <?php if (checkPermission ("Timesheets", 'view')) { ?>
        <a class="black-link" href="<?= base_url ('Projectmanagement/Timesheets') ?>">
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="text-center white-label font-15em"><i class="fa fa-clock-o"></i> <?= lang ('timesheets') ?>
                </div>
            </div>
        </a>
    <?php } ?>
    <?php if (checkPermission ("Costs", 'view')) { ?>
    <a class="black-link" href="<?= base_url ('Projectmanagement/Costs') ?>" >
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="text-center white-label font-15em"><i class="fa fa-money"></i> <?= lang ('project_cost') ?>
                </div>
            </div>
        </a>
    <?php } ?>
    
    <?php if (checkPermission ("Filemanager", 'view')) { ?>
        <a class="black-link" href="<?= base_url ('Projectmanagement/Filemanager') ?>">
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="text-center white-label font-15em"><i
                        class="fa fa-file-o"></i> <?= lang ('project_files') ?></div>
            </div>
        </a>
    <?php } ?>
    <?php /* if (checkPermission ("ProjectIncidents", 'view')) { ?>
        <a class="black-link" href="<?= base_url ('Projectmanagement/ProjectIncidents') ?>">
            <div class="col-xs-12 col-sm-3 col-md-3">
                <div class="text-center white-label font-15em"><i class="fa fa-flag-checkered"></i> <?= lang ('project_incidents') ?>
                </div>
            </div>
        </a>
    <?php } */?>
   
    
</div>