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
            if (isset($sortfield) && $sortfield == 'milestone_code') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('milestone_code', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('milestone_code') ?>
            </th>
            <th class="col-md-2" <?php
            if (isset($sortfield) && $sortfield == 'milestone_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" 
               onclick="apply_sorting('milestone_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('milestone_name') ?>
            </th>
            <?php /* <th <?php
            if (isset($sortfield) && $sortfield == 'description') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc '";
                }
            } else {
                echo "class = 'sorting '";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('description', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('description') ?>
            </th> */ ?>
            <th <?php
            if (isset($sortfield) && $sortfield == 'start_date') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('start_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('start_date') ?>
            </th>
            <th <?php
            if (isset($sortfield) && $sortfield == 'due_date') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('due_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('due_date') ?>
            </th>
            <?php if (checkPermission ('Milestone', 'edit') || checkPermission ('Milestone', 'delete') || checkPermission ("Milestone", 'view')) { ?>
                <th><?= lang ('actions') ?></th>
            <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php if (isset($milestone_data) && count ($milestone_data) > 0) { ?>
            <?php foreach ($milestone_data as $milestone_data) { ?>
                <tr>
                    <td class="col-md-2"><?= !empty($milestone_data['milestone_code']) ? $milestone_data['milestone_code'] : '' ?></td>
                    <td class="col-md-2"><?= !empty($milestone_data['milestone_name']) ? $milestone_data['milestone_name'] : '' ?></td>
                    <?php /* <td class="col-md-3"><?= !empty($milestone_data['description']) ? $milestone_data['description'] : '' ?></td> */?>
                    <td><?php echo configDateTime($milestone_data['start_date']); ?></td>
                    <td><?php echo configDateTime($milestone_data['due_date']); ?></td>
                    <?php if (checkPermission ('Milestone', 'edit') || checkPermission ('Milestone', 'delete') || checkPermission ("Milestone", 'view')) { ?>
                        <td class="bd-actbn-btn"> 
                            <?php if (checkPermission ("Milestone", 'view')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/Milestone/view_record/<?= $milestone_data['milestone_id'] ?>"
                                data-toggle="ajaxModal" aria-hidden="true"title="<?= lang ('view') ?>"
                                class=""><i class="fa fa-search greencol"></i></a><?php } ?>
                            <?php if (checkPermission ('Milestone', 'edit')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/Milestone/edit_record/<?= $milestone_data['milestone_id'] ?>"
                                data-toggle="ajaxModal" title="<?= lang ('edit') ?>" class=""><i
                                        class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if (checkPermission ('Milestone', 'delete')) { ?><a href="javascript:void(0);" title="<?php echo lang('delete');?>"
                                                                                      onclick="deleteItem(<?php echo $milestone_data['milestone_id']; ?>)">
                                    <i class="fa fa-remove redcol"></i></a><?php } ?>
                        </td>
                    <?php } ?>
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
<script>
    function deleteItem(id) {
        var delete_meg ="<?php echo lang('milestone_delete_message'); ?>";
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
                        window.location.href = "<?php echo base_url('Projectmanagement/Milestone/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });

    }
</script>