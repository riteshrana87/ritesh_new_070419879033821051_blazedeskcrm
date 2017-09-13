    <div id="home_activity" >
        <div class="whitebox sortableDiv" id="activities">
            <h2 class="title-2"><?= lang("activities") ?></h2>
            <ul class="activities-list">
                <?php
              //  pr($activities);
                if (!empty($activities)) {
                    $n = 1;
                    foreach ($activities as $row) {
                        if ($n != 1) {
                            $cls = "act-dv";
                        } else {
                            $cls = "";
                        }
                        ?>
                        <li><a href="#"> <span class="blue-col"><?php
                            if ($row['activity_date'] != '0000-00-00 00:00:00') {
                                echo configDateTimeFormat($row['activity_date']);
                            }
                            ?></span> </a> <br>
                        <?= ucfirst($row['user_name']) ?>  <?= $row['activity'] ?> </li>
                        <?php
                        $n++;
                    }
                } else {
                    ?>
                    <li>No activities</li>
            <?php } ?>

            </ul>
    <?php if (!empty($activities)) { ?>
                <div><a class="view_act" href="<?= base_url('Projectmanagement/Activities') ?>"><?= lang("all") ?>(<?= $activities_total ?>
                        )</a></div>
    <?php } ?>
        </div>
    </div>
