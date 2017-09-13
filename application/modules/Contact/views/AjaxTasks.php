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
<div class="whitebox" id="table_tasks">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr role="row">
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Contact/viewTaskData/?orderField=task_name&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('name') ?>
                        </a>
                        
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Contact/viewTaskData/?orderField=importance&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('importance') ?>
                        </a>
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Contact/viewTaskData/?orderField=start_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('START') ?>
                        </a></th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Contact/viewTaskData/?orderField=end_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('finish') ?>
                        </a>
                        
                    </th>
                    <th><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
               <?php 
              
               if (isset($task_data) && count($task_data) > 0) { ?>
                                    <?php foreach ($task_data as $task_data) {
                                        $redirect_link = $_SERVER['HTTP_REFERER'];
                                        ?>
                                        <tr id="task_id_<?php echo $task_data['task_id']; ?>">
                                            <td class="col-xs-4"><?php echo $task_data['task_name']; ?></td>
                                            <td><?php
                                                
                                                if ($task_data['importance'] == 'High') { 
                                                    echo '<div class="bd-prior-high bd-prior-cust"></div>';
                                                } elseif ($task_data['importance'] == 'Medium') {
                                                   
                                                    echo '<div class="bd-prior-med bd-prior-cust"></div>';
                                                } else
                                                   
                                                    echo '<div class="bd-prior-low bd-prior-cust"></div>';
                                                ?><?php //echo $task_data['importance']; ?></td>
                                            <td><?php echo configDateTime($task_data['start_date']); ?></td>
                                            <td><?php echo configDateTime($task_data['end_date']); ?></td>
                                            <td class="bd-actbn-btn"> 
                                                <?PHP if (checkPermission('Task', 'edit')) { ?><a data-href="<?php echo base_url('Task/edittask/'.$task_data['task_id'].''); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><i class="fa fa-pencil bluecol"></i></a><?php } ?>
                                                 <?PHP if (checkPermission('Task', 'delete')) { ?><a title="<?= $this->lang->line('delete') ?>" onclick="delete_task('<?php echo $task_data['task_id']; ?>','<?php echo $redirect_link;?>');"><i class="fa fa-remove redcol"></i></a><?php } ?>      
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php }else{ ?>
                                        <tr><td colspan="5" class="text-center"> <?= lang ('common_no_record_found') ?></td></tr> 
                                <?php }?>
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