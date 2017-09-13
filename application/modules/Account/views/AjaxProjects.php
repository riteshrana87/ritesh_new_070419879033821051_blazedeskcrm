<?php
//if ($taskPage == 0)
//    $taskPage = 1;
$tasksSortDefault = '<i class="fa fa-sort"></i>';
$taskSortAsc = '<i class="fa fa-sort-desc"></i>';
$taskSortDesc = '<i class="fa fa-sort-asc"></i>';
if ($tasksortOrder == "asc") {
    $tasksortOrder = "desc";
} else {
    $tasksortOrder = "asc";
}
?>
<div class="whitebox" id="table_projects">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr role="row">
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewProjectsData/?orderField=project_code&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'project_code') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'project_code') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('project_id') ?>
                        </a>

                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Account/viewProjectsData/?orderField=project_name&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'project_name') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'project_name') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('project_name') ?>
                        </a>
                    </th>
                    <!--<th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Account/viewProjectsData/?orderField=related_to&sortOrder=<?php echo $tasksortOrder ?>">
                    <?php
                    if ($tasksortOrder == 'asc' && $tasksortField == 'related_to') {
                        echo $taskSortAsc;
                    } else if ($tasksortOrder == 'asc' && $tasksortField == 'related_to') {
                        echo $taskSortDesc;
                    } else {
                        echo $tasksSortDefault;
                    }
                    ?>
                    <?= $this->lang->line('related_to') ?>
                        </a></th>-->
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Account/viewProjectsData/?orderField=project_budget&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'project_budget') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'project_budget') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('value') ?>
                        </a>

                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Account/viewProjectsData/?orderField=type&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'type') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'type') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('type') ?>
                        </a>

                    </th>
                    <th>

                        <?= $this->lang->line('status') ?>
                    </th>
                 <!-- <th><a  href="<?php echo base_url(); ?>Account/viewProjectsData/?orderField=progress&sortOrder=<?php echo $tasksortOrder ?>">
                    <?php
                    if ($tasksortOrder == 'asc' && $tasksortField == 'progress') {
                        echo $taskSortAsc;
                    } else if ($tasksortOrder == 'asc' && $tasksortField == 'progress') {
                        echo $taskSortDesc;
                    } else {
                        echo $tasksSortDefault;
                    }
                    ?>
                    <?= $this->lang->line('progress') ?>
                    </a></th>-->
                    <th class='sortTask'><a  href="<?php echo base_url(); ?>Account/viewProjectsData/?orderField=start_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'start_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'start_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('start_date') ?>
                        </a></th>
                    <th class='sortTask'><a  href="<?php echo base_url(); ?>Account/viewProjectsData/?orderField=due_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'due_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'due_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('due_date') ?>
                        </a></th>

                    <th><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $redirect_link = $_SERVER['HTTP_REFERER'];

                if (isset($project_data) && count($project_data) > 0) {
                    ?>
                    <?php
                    foreach ($project_data as $data) {

                        //$redirect_link = base_url()."Contact/view/".$data['prospect_related_id'];
                        ?>
                        <tr id="project_<?php echo $data['project_id']; ?>">
                            <td><?php echo $data['project_code']; ?></td>
                            <td><?php echo $data['project_name']; ?></td>
                           <!-- <td><?php echo 'related to'; ?></td>-->
                            <td><?php echo $data['project_budget']; ?></td>
                            <td>Development</td>
                            <td><?= lang('in_progress_status') ?></td>
                           <!-- <td>progress</td>-->
                            <td><?php echo configDateTime($data['start_date']); ?></td>
                            <td><?php echo configDateTime($data['due_date']); ?></td>
                            <td>
                            <?php if (checkPermission("Projectmanagement", 'view')) { ?><a
                                                            data-href="<?= base_url() ?>Projectmanagement/view_record/<?= $data['project_id'] ?>"
                                                            data-toggle="ajaxModal" aria-hidden="true"title="<?= lang('view') ?>"
                                                            class="btn btn-sm btn-dark"><i class="fa fa-search greencol"></i></a><?php } ?>
                            <?php if (checkPermission('Projectmanagement', 'edit')) { ?><a
                                                            data-href="<?= base_url() ?>Projectmanagement/edit_record/<?= $data['project_id'] ?>"
                                                            data-toggle="ajaxModal" title="<?= lang('edit') ?>" class="btn btn-sm btn-dark"><i
                                                                class="fa fa-pencil bluecol"></i></a>&nbsp;&nbsp;<?php } ?>
                            <?php if (checkPermission('Projectmanagement', 'delete')) { ?><a href="javascript:void(0);" title="<?php echo lang('delete'); ?>"
                                                           onclick="deleteItem(<?php echo $data['project_id']; ?>)">
                                                            <i class="fa fa-remove redcol"></i></a><?php } ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr><td colspan="10" class="text-center"> <?= lang('common_no_record_found') ?></td></tr>
<?php } ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>
<script>
    function deleteItem(id) {
        var delete_meg = "<?php echo lang('project_delete_message'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                window.location.href = "<?php echo base_url('Projectmanagement/delete_record/'); ?>/" + id;
                                dialog.close();
                            }
                        }]
                });
    }
</script>