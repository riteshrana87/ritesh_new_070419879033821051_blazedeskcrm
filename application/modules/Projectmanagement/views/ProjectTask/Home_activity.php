<h4 class="text-center"><?= lang ("activities") ?></h4>
<div class="clr"></div>
<?php $scroll = !empty($activities) && count ($activities) > 5 ? 'scrollbar' : ''; ?>
<div class="whitebox <?= $scroll ?> col-xs-12 col-md-12 col-sm-12">
    <?php if (!empty($activities)) {
        $n = 1;
        foreach ($activities as $row) {
            if ($n != 1) {
                $cls = "act-dv";
            } else {
                $cls = "";
            } ?>
            <div class="fl col-xs-12 col-md-12 col-sm-12 <?= $cls ?>">
                <div class="time_rec_db">
                    <div class="fl"><?php if ($row['activity_date'] != '0000-00-00 00:00:00') {
                            echo configDateTimeFormat($row['activity_date']);
                        } ?></div>
                </div>
                <div class="clr"></div>
                <div class="fl act_title_db" title="<?= $row['user_name'] ?>"><?= ucfirst ($row['user_name']) ?></div>
                <div title=" <?= $row['activity'] ?>"> <?= $row['activity'] ?></div>
            </div>
            <?php $n++;
        }
    } else { ?>
        <div class="fl col-xs-12 col-md-12 col-sm-12"><?=lang('no_activities_found')?></div>
    <?php } ?>
</div>
<?php if (!empty($activities)) { ?>
    <div><a class="view_act" href="<?= base_url ('Projectmanagement/Activities') ?>"><?= lang("all") ?> (<?= $activities_total ?>
            )</a></div>
<?php } ?>