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
            <th class="col-md-6" <?php
            if (isset($sortfield) && $sortfield == 'job_title_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('job_title_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('JOB_TITLE') ?>
            </th>
            <th class="col-xs-3" <?php
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
               onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('cost_placeholder_createddate') ?>
            </th>
            
            <?php if (checkPermission ('Contact', 'edit') || checkPermission ('Contact', 'delete')) { ?>
            <th><?= lang ('action') ?></th>
            <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php if (isset($project_incidenttype_data) && count ($project_incidenttype_data) > 0) { ?>
            <?php foreach ($project_incidenttype_data as $project_incidenttype_data) { ?>
                <tr>
                    <td><?= !empty($project_incidenttype_data['job_title_name']) ? $project_incidenttype_data['job_title_name'] : '' ?></td>
                    <td><?php echo date ('Y-m-d', strtotime ($project_incidenttype_data['created_date'])); ?></td>
                    <?php if (checkPermission ('Contact', 'edit') || checkPermission ('Contact', 'delete')) { ?>
                        <td class="bd-actbn-btn">
                            <?php if (checkPermission ('Contact', 'edit')) { ?><a
                                data-href="<?= base_url () ?>Contact/edit_job_title/<?= $project_incidenttype_data['job_title_id'] ?>"
                                data-toggle="ajaxModal" title="<?= lang ('edit') ?>" class=""><i
                                        class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if(checkPermission('Contact','delete')){ ?><a href="javascript:void(0);" title="<?php echo lang('delete');?>" onclick="deleteItem(<?php echo $project_incidenttype_data['job_title_id']; ?>)"><i class="fa fa-remove redcol"></i></a><?php } ?>
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
        var delete_meg ="<?php echo lang('CONFIRM_DELETE_JOB_TITLE'); ?>";
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
                        window.location.href = "<?php echo base_url('Contact/delete_job_title/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });
    }
</script>