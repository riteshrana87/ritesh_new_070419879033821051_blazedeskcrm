 <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 connectedSortable" id="recentActivitiesWidget">
        <div class="whitebox pad-6 pm-dbbox-height">
            <h4><b><?= lang ('recent_activities') ?></b></h4>

            <div class="grayline-1"></div>
            <ul class="tasklist">
                <?php if (!empty($activities)) {
                    $n = 1;
                    foreach ($activities as $row) { ?>
                        <?php
                        if (isset($row['profile_photo']) && $row['profile_photo'] != '') {
                             if (file_exists('uploads/profile_photo/' . $row['profile_photo'])) {
                            $explode_name   = explode ('.', $row['profile_photo']);
                            $thumbnail_name = $explode_name[0] . '_thumb.' . $explode_name[1];
                            $profile_src    = base_url () . "uploads/profile_photo/" . $thumbnail_name;
                            }
                            else {
                            $profile_src = base_url () . "uploads/images/mark-icon.png";
                            }
                        } else {
                            $profile_src = base_url () . "uploads/images/mark-icon.png";
                        }
                        ?>
                        <li><span class="pull-left pad-r6"><img src="<?= $profile_src ?>" alt=""/></span>
                            <b><?= ucfirst ($row['user_name']) ?></b>
                            <!-- <a href="#">created #83</a>:  --> <?= $row['activity'] ?><br/>
                            <span
                                class="font-1em graycol">Created on <?php if ($row['activity_date'] != '0000-00-00 00:00:00') {
                                    echo configDateTimeFormat($row['activity_date']);
                                } ?></span></li>
                    <?php }
                } ?>
            </ul>
            <?php if (checkPermission ('Activities', 'view')) { ?><p class="text-right"><a href="<?= base_url ('Projectmanagement/Activities') ?>"><?= lang('all') ?>
                    (<?= $activities_total ?>)</a></p><?php } ?>
        </div>
    </div>