<?php
//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table table-responsive">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>"/>
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>"/>
    <input type="hidden" id="uri_segment" name="uri_segment"
           value="<?php if (isset($uri_segment)) echo $uri_segment; ?>"/>


    <table id="milestonetable1" class="table table-striped dataTable" cellspacing="0" width="100%">
        <thead>
        <tr>

            <th <?php
            if (isset($sortfield) && $sortfield == 'activity') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc col-xs-9'";
                } else {
                    echo "class = 'sorting_asc col-xs-9'";
                }
            } else {
                echo "class = 'sorting col-xs-9'";
            }
            ?> tabindex="0" aria-controls="example1"
               onclick="apply_sorting('activity', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('activities') ?>
            </th>
            <th <?php
            if (isset($sortfield) && $sortfield == 'activity_date') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc col-xs-3'";
                } else {
                    echo "class = 'sorting_asc col-xs-3'";
                }
            } else {
                echo "class = 'sorting col-xs-3'";
            }
            ?> tabindex="0" aria-controls="example1" 
               onclick="apply_sorting('activity_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('cost_placeholder_createddate') ?>
            </th>

        </tr>
        </thead>
        
        <tbody>
        <?php if (isset($activities) && count ($activities) > 0) { ?>
            <?php foreach ($activities as $row) { ?>
                <tr>
                    <?php
                    if (isset($row['profile_photo']) && $row['profile_photo'] != '') {
                        $explode_name   = explode ('.', $row['profile_photo']);
                        $thumbnail_name = $explode_name[0] . '_thumb.' . $explode_name[1];
                        $profile_src    = base_url () . "uploads/profile_photo/" . $thumbnail_name;
                    } else {
                        $profile_src = base_url () . "uploads/images/mark-icon.png";
                    }
                    ?>
                    <td><span class="bd-activ-prof-pic"><img src="<?= $profile_src ?>"
                                                            alt=""/></span><b><?= ucfirst ($row['user_name']) ?></b> <?= !empty($row['activity']) ? $row['activity'] : '' ?><div class="clr"></div>
                    </td>
                    <td><?php echo configDateTimeFormat($row['activity_date']); ?></td>
                
                </tr>

            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6" class="text-center">
                    <?= lang ('common_no_record_found') ?>
                </td>

            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<div class="clr"></div>
<div class="row">
    <div class="col-md-12 text-center">
        <div id="common_tb" class="no_of_records">
            <?php
            if (isset($pagination)) {
                echo $pagination;
            }
            ?>
        </div>
    </div>
</div>