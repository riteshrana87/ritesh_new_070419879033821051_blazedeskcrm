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
<div class="whitebox" id="table_estimates">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead >
                <tr>
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewEstimateData/?orderField=estimate_auto_id&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'estimate_auto_id') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'estimate_auto_id') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('estimate_id') ?>
                        </a>

                    </th>

                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewEstimateData/?orderField=value&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'value') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'value') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('estimated_ammount') ?>
                        </a>

                    </th>
                    <!--<th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewEstimateData/?orderField=comm_sender&sortOrder=<?php echo $tasksortOrder ?>">
                    <?php
                    if ($tasksortOrder == 'asc' && $tasksortField == 'comm_sender') {
                        echo $taskSortAsc;
                    } else if ($tasksortOrder == 'asc' && $tasksortField == 'comm_sender') {
                        echo $taskSortDesc;
                    } else {
                        echo $tasksSortDefault;
                    }
                    ?>
                    <?= $this->lang->line('related_to') ?>
                        </a>
        
                    </th>-->

                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewEstimateData/?orderField=comm_subject&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'comm_subject') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'comm_subject') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('status') ?>
                        </a>

                    </th>

                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewEstimateData/?orderField=created_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('creation_date') ?>
                        </a>

                    </th>
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewEstimateData/?orderField=send_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'send_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'send_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('MESSAGE_SEND_DATE') ?>
                        </a>

                    </th>
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewEstimateData/?orderField=due_date&sortOrder=<?php echo $tasksortOrder ?>">
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
                        </a>

                    </th>
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewEstimateData/?orderField=login_id&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'login_id') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'login_id') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('RESPONSIBLE') ?>
                        </a>

                    </th>
                    <th><?php echo lang('action'); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                if (isset($estimate_data) && count($estimate_data) > 0) {
                    foreach ($estimate_data as $estimate) {
                        ?>
                        <tr id="estimate_id_<?php echo $estimate['estimate_id']; ?>">
                            <td > <?php echo $estimate['estimate_auto_id']; ?></td>

                            <td >      <?php echo $estimate['value']; ?> </td>
                          <!-- <td>related to</td>-->
                            <td><div class="yellow-bg"><?= $this->lang->line('estimate_sent') ?></div></td>
                            <td>  <?php echo configDateTime($estimate['created_date']); ?></td>
                            <td>  <?php echo configDateTime($estimate['send_date']); ?></td>
                            <td>  <?php echo configDateTime($estimate['due_date']); ?></td>
                            <td><?php echo $estimate['firstname'] . " " . $estimate['lastname']; ?></td>
                            <td>
        <?php if (checkPermission('Estimates', 'view')) { ?>
                                    <a href="<?php echo base_url() . 'Estimates/view/' . $estimate['estimate_id']; ?>" title="<?php echo lang('view'); ?>"><i class="fa fa-search fa-x greencol"></i></a>&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                                <?php if (checkPermission('Estimates', 'edit')) { ?>
                                    <a href="<?php echo base_url() . 'Estimates/edit/' . $estimate['estimate_id']; ?>" title="<?php echo lang('edit'); ?>"><i class="fa fa-pencil fa-x bluecol"></i></a>&nbsp;&nbsp;&nbsp;
                                <?php } ?>
                                <?php if (checkPermission('Estimates', 'delete')) { ?>
                                    <a href="javascript:;" onclick="deleteRecord(<?php echo $estimate['estimate_id']; ?>);" title="<?php echo lang('delete'); ?>"><i class="fa fa-remove fa-x redcol"></i></a>
                                <?php } ?>
                            </td>

                        </tr>
    <?php
    }
} else {
    ?>
                    <tr><td colspan="9" class="text-center"> <?= lang('common_no_record_found') ?></td></tr>  
                <?php }
                ?>
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
    function deleteRecord(id)
    {
        var delete_meg = "<?php echo lang('confirm_delete_estimate'); ?>";
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
                                window.location.href = "<?php echo base_url('Estimates/deletedata'); ?>/" + id;
                                dialog.close();
                            }
                        }]
                });


    }
</script>