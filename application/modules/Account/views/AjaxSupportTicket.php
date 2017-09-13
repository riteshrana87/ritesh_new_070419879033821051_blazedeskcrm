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
<div class="whitebox" id="table_support_ticket">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr role="row">
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/viewSupportData/?orderField=ticket_id&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'ticket_id') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'ticket_id') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('ticket_id') ?>
                        </a>

                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Account/viewSupportData/?orderField=related_to&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('related_to_support') ?>
                        </a>
                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Account/viewAllDealsData/?orderField=ticket_subject&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'ticket_subject') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'ticket_subject') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('subject') ?>
                        </a></th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Account/viewSupportData/?orderField=status&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'status') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'status') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('status') ?>
                        </a>

                    </th>
                    <th class='sortTask'>
                        <a  href="<?php echo base_url(); ?>Account/viewSupportData/?orderField=created_date&sortOrder=<?php echo $tasksortOrder ?>">
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
                    <th class='sortTask'><a  href="<?php echo base_url(); ?>Account/viewSupportData/?orderField=due_date&sortOrder=<?php echo $tasksortOrder ?>">
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
                    <th class='sortTask'><a  href="<?php echo base_url(); ?>Account/viewSupportData/?orderField=&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'days_open') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'days_open') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('days_open') ?>
                        </a></th>
                    <th><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $redirect_link = $_SERVER['HTTP_REFERER'];

                if (isset($support_data) && count($support_data) > 0) {
                    ?>
                    <?php
                    foreach ($support_data as $data) {

                        //$redirect_link = base_url()."Contact/view/".$data['prospect_related_id'];
                        ?>
                        <tr id="ticket_<?php echo $data['ticket_id']; ?>">
                            <td><?php echo $data['ticket_id']; ?></td>
                            <td><?php echo 'related_to'; ?></td>
                            <td><?php echo $data['ticket_subject']; ?></td>
                            <td><?php echo $data['status']; ?></td>
                            <td><?php echo configDateTime($data['created_date']); ?></td>
                            <td><?php echo configDateTime($data['due_date']); ?></td>
                            <td><?php echo lang('days_open'); ?></td>
                            <td>
                                <?php if (checkPermission("Ticket", 'view')) { ?><a  href="<?= base_url() ?>Ticket/view_record/<?= $data['ticket_id'] ?>" data-toggle="ajaxModal" aria-hidden="true" title="<?= lang('view') ?>" ><i class="fa fa-search greencol"></i></a><?php } ?>
                                <?php if (checkPermission("Ticket", 'edit')) { ?>&nbsp;&nbsp;<a href="<?= base_url() ?>Ticket/edit_record/<?= $data['ticket_id'] ?>" data-toggle="ajaxModal" aria-hidden="true" title="<?= lang('edit') ?>" ><i class="fa fa-pencil bluecol"></i></a><?php } ?>
                                <?php if (checkPermission("Ticket", 'delete')) { ?>&nbsp;&nbsp;<a href="javascript:;" title="<?= lang('delete') ?>" onclick="deleteTicket('<?php echo $data['ticket_id']; ?>');"><i class="fa fa-remove redcol"></i></a><?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr><td colspan="8" class="text-center"> <?= lang('common_no_record_found') ?></td></tr>
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
    function deleteTicket(id)
    {
        var delete_meg = "<?php echo lang('ticket_delete_message'); ?>";
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
                                window.location.href = "<?php echo base_url('Ticket/deletedata/'); ?>/" + id;
                                dialog.close();
                            }
                        }]
                });
    }
</script>