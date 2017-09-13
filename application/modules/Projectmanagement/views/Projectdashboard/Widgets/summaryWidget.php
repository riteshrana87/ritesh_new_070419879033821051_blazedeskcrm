 <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 connectedSortable" id="summaryWidget">
        <div class="whitebox pad-6 pm-dbbox-height">
            <h4><b><?= lang ('summary') ?></b></h4>

            <div class="grayline-1"></div>
            <ul class="tasklist">
                <?php if (!empty($project_tasks_status)) {
                    foreach ($project_tasks_status as $row) {
                        if (!empty($row['total_task'])) {
                            ?>
                            <li><span class="greenbg"><?= $row['total_task'] ?></span><?=lang('total')?> <?= $row['status_name'] ?>
                                <?=lang('projecttask')?>
                            </li>
                        <?php }
                    }
                } ?>
            </ul>
        </div>
    </div>