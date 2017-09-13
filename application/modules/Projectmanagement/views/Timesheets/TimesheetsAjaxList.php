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


    <table id="Timesheetstable1" class="table table-striped dataTable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th <?php
                if (isset($sortfield) && $sortfield == 'username') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                    onclick="apply_sorting('username', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('username') ?>
                </th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'task_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                    onclick="apply_sorting('task_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('task_name') ?>
                </th>

                <th <?php
                if (isset($sortfield) && $sortfield == 'estimate_time') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                    onclick="apply_sorting('estimate_time', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('estimate_time') ?>
                </th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'spent_time') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                    onclick="apply_sorting('spent_time', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('spent_time') ?>
                </th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'description') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                    onclick="apply_sorting('description', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('description') ?>
                </th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'created_date') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                    onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('cost_placeholder_createddate') ?>
                </th>
                <?php if (checkPermission('Timesheets', 'edit') || checkPermission('Timesheets', 'delete') || checkPermission ("Timesheets", 'view')) { ?>
                    <th><?= lang('actions') ?></th>
                <?php } ?>
            </tr>
        </thead>

        <tbody>
            <?php if (isset($timesheet_data) && count($timesheet_data) > 0) { ?>
                <?php foreach ($timesheet_data as $timesheet_data) { ?>
                    <tr>
                        <td class="col-md-2"><?= !empty($timesheet_data['username']) ? $timesheet_data['username'] : '' ?></td>
                        <td class="col-md-2"><?= !empty($timesheet_data['task_name']) ? $timesheet_data['task_name'] : '' ?></td>
                        <td><?= !empty($timesheet_data['estimate_time']) ? $timesheet_data['estimate_time'] . ' Hr' : '' ?></td>
                        <td> <?php echo ($timesheet_data['spent_time'] != '') ? $timesheet_data['spent_time'].'Hr' : '' ?></td>
                        <td class="col-md-3"><?= !empty($timesheet_data['description']) ? html_substr($timesheet_data['description'],0, 50) : '' ?></td>
                        <td><?php echo configDateTime($timesheet_data['created_date']); ?></td>
                        <?php if (checkPermission ('Timesheets', 'edit') || checkPermission ('Timesheets', 'delete') || checkPermission ("Timesheets", 'view')) { ?>
                        <td class="bd-actbn-btn">
                            <?php if (checkPermission ("Timesheets", 'view')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/Timesheets/view_record/<?= $timesheet_data['timesheet_id'] ?>"
                                data-toggle="ajaxModal" aria-hidden="true"title="<?= lang ('view') ?>"
                                class=""><i class="fa fa-search greencol"></i></a><?php } ?>
                            <?php if (checkPermission ('Timesheets', 'edit')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/Timesheets/edit_record/<?= $timesheet_data['timesheet_id'] ?>"
                                data-toggle="ajaxModal" title="<?= lang ('edit') ?>" class=""><i
                                        class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if (checkPermission ('Timesheets', 'delete')) { ?><a href="javascript:void(0);" title="<?php echo lang('delete');?>"
                                                                                      onclick="deleteItem(<?php echo $timesheet_data['timesheet_id']; ?>)">
                                    <i class="fa fa-remove redcol"></i></a><?php } ?>
                        </td>
                    <?php } ?>
                        
                    </tr>

                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6" class="text-center">
                        <?= lang('common_no_record_found') ?>
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
<script>
    function deleteItem(id) {
        var delete_meg ="<?php echo lang('timesheet_delete_message'); ?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        window.location.href = "<?php echo base_url('Projectmanagement/Timesheets/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });
        }
</script>