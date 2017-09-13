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
<div class="whitebox" id="taskTable">
    <div class="table table-responsive">
        <table class="table table-responsive">
            <thead>
                <tr role="row">
                    <th class='sortTask'>
                        <a href="<?php echo base_url(); ?>SalesOverview/taskAjaxList/<?php echo $taskPage ?>/?orderField=task_name&sortOrder=<?php echo $tasksortOrder ?>">
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
                        <a    href="<?php echo base_url(); ?>SalesOverview/taskAjaxList/<?php echo $taskPage ?>/?orderField=importance&sortOrder=<?php echo $tasksortOrder ?>">
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
                        <a   href="<?php echo base_url(); ?>SalesOverview/taskAjaxList/<?php echo $taskPage ?>/?orderField=end_date&sortOrder=<?php echo $tasksortOrder ?>">
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
                    <th ><?PHP if (checkPermission('Task', 'add')) { ?><a href="<?php echo base_url('Task/add'); ?>" data-toggle="ajaxModal">+<?php echo lang('create'); ?></a><?php } ?> </th></tr>
            </thead>
            <tbody>
                <?php
                if (count($task_data) > 0) {
                    foreach ($task_data as $tasks) {
                        ?>
                        <tr>
                            <td><?php echo $tasks['task_name']; ?></td>
                            <td><?php
                                if ($tasks['importance'] == 'High') {

                                    echo '<div class="redline"></div>';
                                } elseif ($tasks['importance'] == 'Medium') {
                                    echo '<div class="blueline"></div>';
                                } else
                                    echo '<div class="greenline"></div>';
                                ?><?php //echo $task_data['importance']; ?></td>
                            <td><?php echo $tasks['end_date']; ?></td>

                            <td> <?PHP if (checkPermission('Task', 'edit')) { ?><a href="<?php echo base_url('Task/edittask/'.$tasks['task_id'].'/SalesOverview'); ?>" data-toggle="ajaxModal"  class="edit_lead" id="edit_lead"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                                
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">No Records Found!</td>
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