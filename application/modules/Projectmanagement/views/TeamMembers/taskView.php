<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>    

<div class="modal-dialog modal-lg"  id="teamReplacer">
    <div class="modal-content ">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4><b>Task List</b> </h4>
        </div>
        <div class="modal-body">
            <div class="table table-responsive">

                <table id="datatabletask" class="table table-responsive">
                    <thead>
                        <tr>
                            <th class='sortCost'>
                                <?php echo lang('task_code') ?>

                            </th>
                            <th  class='sortCost'>
                                <?php echo lang('task_name') ?>

                            </th>
                            <th class='sortCost'>
                                <?php echo lang('employee') ?>
                            </th>
                            <th class='sortCost'>
                                <?php echo lang('milestone_name') ?>
                            </th>
                            <th class='sortCost'>
                                <?php echo lang('start_date') ?>
                            </th>
                            <th class='sortCost'>
                                <?php echo lang('due_date') ?>
                            </th>
                            <th class='sortCost'>
                                <?php echo lang('status') ?>
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        <?php
//                        pr($task_data);
                        if (isset($task_data) && count($task_data) > 0) {
                            ?>
                            <?php foreach ($task_data as $task_data) {
                                ?>
                                <tr class="treegrid-<?= $task_data['task_id'] ?> ">

                                    <td><?= !empty($task_data['task_code']) ? $task_data['task_code'] : '' ?></td>
                                    <td><?= !empty($task_data['task_name']) ? $task_data['task_name'] : '' ?></td>
                                    <td><?= !empty($task_data['user_name']) ? $task_data['user_name'] : '' ?></td>
                                    <td>
                                        <?php
                                        if ($task_data['sub_task_id'] <=0) {
                                             echo $task_data['milestone_name'];
                                        } else {
                                                                                       echo $task_data['master_name'];

                                        }
                                        ?></td>
                                    <td><?php
                                if ($task_data['start_date'] != '0000-00-00') {
                                    echo configDateTime($task_data['start_date']);
                                }
                                        ?></td>
                                    <td><?php
                                if ($task_data['due_date'] != '0000-00-00') {
                                    echo configDateTime($task_data['due_date']);
                                }
                                        ?></td>
                                    <td>
                                        <span class="color_box" style="background-color:<?= $task_data['status_color'] ?>"><?= $task_data['status_name'] ?></span>
                                    </td>

                                </tr>

                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <?= lang('common_no_record_found') ?>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#from-model').parsley();
        //disabled after submit
        $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }
        });
        $('.chosen-select').chosen({'display_selected_options': false});
        $('.chosen-results li:contains("Swatch 1")').hide();

        $('#notify_members').bootstrapToggle();
        $('#reminder').bootstrapToggle();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});

    });
    $(function () {
        $('#schedule_meeting').datetimepicker();
    });
</script>