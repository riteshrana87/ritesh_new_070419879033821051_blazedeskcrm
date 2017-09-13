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
<div class="whitebox" id="table_communication">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead >
                <tr>
                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/getCommunicationData/?orderField=created_date&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'created_date') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('COMM_DATE') ?>
                        </a>

                    </th>

                    <th class='sortTask'>
                        <?= $this->lang->line('COMM_TYPE') ?>
                    </th>
                    <th class='sortTask'>
                        <?= $this->lang->line('COMM_CONTACT') ?>
                    </th>

                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/getCommunicationData/?orderField=subject&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'subject') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'subject') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('COMM_SUBJECT') ?>
                        </a>

                    </th>

                    <th class='sortTask'>
                        <a    href="<?php echo base_url(); ?>Account/getCommunicationData/?orderField=comm_sender&sortOrder=<?php echo $tasksortOrder ?>">
                            <?php
                            if ($tasksortOrder == 'asc' && $tasksortField == 'prospect_owner_id') {
                                echo $taskSortAsc;
                            } else if ($tasksortOrder == 'asc' && $tasksortField == 'prospect_owner_id') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?= $this->lang->line('COMM_EMPLOYEE') ?>
                        </a>

                    </th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php
                $communication_count = 0;
                if (isset($communication_data) && count($communication_data) > 0) {
                    foreach ($communication_data as $communication) {
                        ?>
                        <tr id="email_prospect_id<?php echo $communication['email_prospect_id']; ?>">
                            <td > <?php echo configDateTime($communication['created_date']); ?></td>

                            <td >
        <?php
        echo lang('EMAIL_TYPE_EMAIL_PROSPECT');
        ?>
                            </td>

                            <td >
        <?php
        $ContactIds[] = explode(",", $communication['comm_receiver']);
        $ContactNames[] = explode(",", $communication['receiver_name']);
        $contact_count = 0;
        foreach ($ContactIds[$communication_count] as $ContactId) {
            ?>
                                    <?php if (checkPermission('Contact', 'view')) { ?>
                                        <a href="<?= base_url('Contact/view/' . $ContactId) ?>" class="edit_contact" >
                                        <?php
                                        $custComma = "";
                                        if ($contact_count != 0) {
                                            $custComma = ",";
                                        }
                                        ?>
                                            <?php echo $custComma . $ContactNames[$communication_count][$contact_count]; ?></a>
                                        <?php } ?>
                                        <?php $contact_count++;
                                    }
                                    ?>
                            </td>

                            <td >
        <?php
        $in = $communication['subject'];
        echo strlen($in) > 50 ? substr($in, 0, 50) . "..." : $in;
        ?>
                            </td>

                            <td>  <?php echo $communication['firstname'] . " " . $communication['lastname']; ?>
                            </td>
                            <td>
        <?php if (checkPermission('Account', 'view')) { ?>
                                    <a data-href="<?= base_url() ?>Contact/viewCommunication/<?= $communication['comm_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_EMAIL_CONTENT'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                                <?php } ?>
                            </td>
                        </tr>
                                <?php
                                $communication_count++;
                            }
                        } else {
                            ?>
                    <tr><td colspan="6" class="text-center"> <?= lang('common_no_record_found') ?></td></tr>  
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
    $('.event_reminder_toggle').bootstrapToggle();

    function delete_event(campaign_contact_id, redirect_link)
    {
        var delete_url = "<?php echo base_url(); ?>Contact/delete_event?event_id=" + campaign_contact_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo lang('CONFIRM_DELETE_EVENT'); ?>";
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
                                window.location.href = delete_url;
                                dialog.close();
                            }
                        }]
                });

    }
</script>