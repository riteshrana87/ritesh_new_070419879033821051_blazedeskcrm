<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 connectedSortable" id="toDoWidget">
        <div class="whitebox pad-6 pm-dbbox-height bd-pm-scroll-height">
            <h4><b><?= lang ("to_dos") ?></b></h4>

            <div class="grayline-1"></div>
            <h5><b><?= lang ("upcoming_tasks") ?></b></h5>
            <ul class="tasklist">
                <?php if (!empty($upcoming_tasks)) {
                    foreach ($upcoming_tasks as $row) { ?>
                        <li>
                            <!-- <a href="#">BD</a> - #80: --> <?= !empty($row['task_name']) ? $row['task_name'] : '' ?>
                            <br/>
                            <span
                                class="font-1em graycol">Created on <?php if ($row['created_date'] != '0000-00-00 00:00:00') {
                                    echo configDateTimeFormat($row['created_date']);
                                } ?></span></li>
                    <?php }
                } ?>
            </ul>
            <p class="text-right"><!-- <a href="#">View All (12)</a> --></p>
        </div>
    </div>