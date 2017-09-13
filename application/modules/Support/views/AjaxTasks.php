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

<div id="AjaxTasks" class="sortableDiv">
<div class="whitebox" id="taskTable">
    <div class="table table-responsive">
        <table class="table table-responsive">
            <thead>
                <tr role="row">
                    <th class='sortTask'>
                        <a href="<?php echo base_url(); ?>Support/taskAjaxList/<?php echo $taskPage ?>/?orderField=task_name&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'task_name') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'task_name') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?php echo lang('to_do'); ?>
                        </a>
                    </th>
                    <th class='sortTask'>
                        <a href="<?php echo base_url(); ?>Support/taskAjaxList/<?php echo $taskPage ?>/?orderField=importance&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'importance') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'importance') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?php echo lang('priority'); ?>
                        </a>
                    </th>
                    <th class='sortTask'>
                        <a   href="<?php echo base_url(); ?>Support/taskAjaxList/<?php echo $taskPage ?>/?orderField=end_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'end_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'end_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>                                   
                            <?php echo lang('deadline'); ?>
                        </a>
                    </th>
                    <th><?PHP if (checkPermission('SupportTask', 'add')) { ?><a data-href="<?php echo base_url('SupportTask/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true">+<?php echo lang('create'); ?></a><?php } ?> </th></tr>
            </thead>
            <tbody>
                <?php
                if (count($task_data) > 0) {
                    foreach ($task_data as $tasks) {
                        ?>
                        <tr>
                            <td class="col-lg-4"><?php echo $tasks['task_name']; ?></td>
                            <td><?php
                                if ($tasks['importance'] == 'High') {

                                    echo '<div class="redline"></div>';
                                } elseif ($tasks['importance'] == 'Medium') {
                                    echo '<div class="blueline"></div>';
                                } else
                                    echo '<div class="greenline"></div>';
                                ?><?php //echo $task_data['importance']; ?></td>
                            <td><?php echo date('m/d/Y',strtotime($tasks['end_date'])); ?></td>

                            <td> <?PHP if (checkPermission('SupportTask', 'edit')) { ?><a data-href="<?php echo base_url('SupportTask/edittask/'.$tasks['task_id'].'/Support'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_lead" id="edit_lead" title="<?= $this->lang->line('edit') ?>"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('SupportTask', 'view')) { ?><a data-href="<?php echo base_url('SupportTask/viewtask/'.$tasks['task_id'].'/Support'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="view_lead" id="view_lead" title="<?= $this->lang->line('view') ?>"><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4"><?php echo lang('common_no_record_found');?></td>
                    </tr>
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
</div>
