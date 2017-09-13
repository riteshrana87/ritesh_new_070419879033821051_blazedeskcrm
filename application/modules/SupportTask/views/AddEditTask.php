<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$TaskAction = 'inserttask';
$taskPath = 'SupportTask/' . $TaskAction;
?>
<div class="modal-dialog modal-lg"> 
    <?php
    $attributes = array("name" => "frmtask", "id" => "frmtask", 'data-parsley-validate' => "");
    echo form_open_multipart($taskPath, $attributes);
    ?>
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="modelTaskTitle">
                    <?= $modal_title ?>
                </div>
            </h4>
        </div>
        <div class="modal-body">
            <div class = " row">
                <div class="row">  <div class = "col-xs-12 col-sm-12 form-group">
                        <input type="hidden" name="redirect" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
                        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                        <input type="hidden" name="hdn_contact_id" id="hdn_contact_id" value="<?php
                        if (isset($contact_id) && $contact_id != '') {
                            echo $contact_id;
                        } else {
                            echo "";
                        }
                        ?>"/>
                        <input type="text" id="task_id" name="task_id"  hidden="" value="<?php
                        if (!empty($edit_record[0]['task_id'])) {
                            echo $edit_record[0]['task_id'];
                        }
                        ?>">

                        <input type="text" class="form-control" placeholder="<?= $this->lang->line('task_name') ?> *" required id="task_name" name="task_name" maxlength="30" value="<?php
                        if (!empty($edit_record[0]['task_name'])) {
                            echo htmlentities($edit_record[0]['task_name']);
                        }
                        ?>">
                    </div></div>
                <div class ="form-group row">
                    <div class = "col-xs-12 col-sm-12">
                        <?php
                        if (!empty($edit_record[0]['task_description'])) {
                            $task_description = $edit_record[0]['task_description'];
                        } else {
                            $task_description = '';
                        }
                        ?>
                        <textarea  class="form-control" rows="4" required placeholder="<?= $this->lang->line('task_description') ?> *" name="task_description" id="task_description"><?php echo $task_description; ?></textarea>
                    </div>

                </div>
                <div class ="row">
                    <div class = "col-xs-12 col-sm-4 form-group">
                        <label><?= $this->lang->line('start_date') ?> : *</label>
                        <div class="input-group date" id="start_date">
                            <input type="text" class="form-control" required placeholder="<?= $this->lang->line('start_date') ?>" id="start_date" name="start_date" onkeydown="return false" value="<?php
                            if (!empty($edit_record[0]['start_date']) && $edit_record[0]['start_date'] != '0000-00-00') {
                                echo date('m/d/Y', strtotime($edit_record[0]['start_date']));
                            } else {
                                echo date("m/d/Y");
                            }
                            ?>">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                    <div class ="col-xs-12 col-sm-4 form-group">
                        <label><?= $this->lang->line('finish_before') ?> : *</label>
                        <div class="input-group date" id="end_date">
                            <input type="text" class="form-control" data-parsley-gteq="#start_date" required placeholder="<?= $this->lang->line('finish_before') ?> *" id="end_date" name="end_date" data-parsley-errors-container="#end-date" onkeydown="return false" value="<?php
                            if (!empty($edit_record[0]['end_date']) && $edit_record[0]['end_date'] != '0000-00-00') {
                                echo date('m/d/Y', strtotime($edit_record[0]['end_date']));
                            }
                            ?>">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                        <div id="end-date"></div>
                    </div>
                    <div class = "col-xs-12 col-sm-4 form-group ">
                        <label>
                            <?= $this->lang->line('importance') ?>
                        </label>
                        <select name="importance" class="form-control chosen-select" id="importance" required>
                            <option value="High"  class="bd-prior-high bd-prior-cust"  <?php
                            if (!empty($edit_record[0]['importance']) && ($edit_record[0]['importance'] == 'High')) {
                                echo 'selected';
                            }
                            ?>> <?= $this->lang->line('high') ?></option>
                            <option value="Medium" class="bd-prior-med bd-prior-cust"  <?php
                            if (!empty($edit_record[0]['importance']) && ($edit_record[0]['importance'] == 'Medium')) {
                                echo 'selected';
                            }
                            ?>> <?= $this->lang->line('medium') ?></option>
                            <option value="Low" class="bd-prior-low bd-prior-cust"  <?php
                            if (!empty($edit_record[0]['importance']) && ($edit_record[0]['importance'] == 'Low')) {
                                echo 'selected';
                            }
                            ?>><?= $this->lang->line('low') ?></option>
                        </select>
                    </div>
                </div>

                <?php
                if (!empty($edit_record[0]['remember'])) {
                    $reminder_id = "first_time_show";
                } else {
                    $reminder_id = "first_time_hide";
                }
                ?>
                <div class ="row">
                    <div class = "col-xs-12 col-sm-3">


                        <label>
                            <?= $this->lang->line('reminder?') ?>
                        </label>
                        <input <?php if (!empty($edit_record[0]['remember'])) { ?>checked="checked"<?php } ?> data-toggle="toggle" data-onstyle="success" type="checkbox"  id="reminder" name="reminder" onChange="toggle_show(<?php echo "'#" . $reminder_id . "'"; ?>, this)"/>


                    </div>
                    <div id="<?php echo $reminder_id; ?>" class="col-sm-9">


                        <div class = "col-xs-12 col-sm-6 bd-form-group">
                            <div class="row"> <div class="col-sm-5"><label><?= $this->lang->line('REMINDER_DATE') ?> : *</label></div>
                                <div class="input-group date col-sm-7" id="reminder_date">

                                    <input type="text" class="input-append date form_datetime form-control reminder_date" placeholder="<?= $this->lang->line('REMINDER_DATE') ?>" id="reminder_date" data-parsley-errors-container="#remind-date" name="reminder_date" onkeydown="return false" value="<?php
                                    if (!empty($edit_record[0]['reminder_date']) && $edit_record[0]['reminder_date'] != '0000-00-00') {
                                        echo date('m/d/Y', strtotime($edit_record[0]['reminder_date']));
                                    }
                                    ?>" >
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                                <div id="remind-date"></div></div>
                        </div>


                        <div class = "col-xs-12 col-sm-6 bd-form-group">
                            <div class="row"> <div class="col-sm-5"><label><?= $this->lang->line('REMINDER_TIME') ?> : *</label></div>
                                <div class="input-group date col-sm-7" id="reminder_time">

                                    <input type="text" class="input-append date form_datetime form-control reminder_time" placeholder="<?= $this->lang->line('REMINDER_TIME') ?>" id="reminder_time" name="reminder_time" onkeydown="return false" value="<?php
                                    if (!empty($edit_record[0]['reminder_time']) && $edit_record[0]['reminder_time'] != '0000-00-00') {
                                        echo date('H:i', strtotime($edit_record[0]['reminder_time']));
                                    }
                                    ?>" >
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div></div>
                        </div>
                        <div class="clr"></div>

                    </div>
                </div>
            </div>

            <div class="clr"></div>

        </div>
        <div class="modal-footer">
            <div class="text-right">
                <?php $redirect_link = $_SERVER['HTTP_REFERER']; ?>

                <input type="submit" class="btn btn-primary" name="task_submit" id="task_submit_btn" value="<?= $submit_button_title ?>" />
                <?php if (isset($edit_record)) { ?>
                    <?PHP if (checkPermission('SupportTask', 'delete')) { ?> <a data-href="javascript:;" onclick="delete_request('<?php echo $edit_record[0]['task_id']; ?>', '<?php echo $redirect_link; ?>');" ><input type="button" class="btn btn-primary" name="remove"  value="<?= $remove_button_title ?>" /></a><?php } ?>
                    <?PHP if (checkPermission('SupportTask', 'edit')) { ?> <a data-href="javascript:;" onclick="complete_request('<?php echo $edit_record[0]['task_id']; ?>', '<?php echo $redirect_link; ?>');" ><input type="button" class="btn btn-primary" name="completed"  value="<?= $this->lang->line('complete') ?>" /></a><?php } ?>
                    <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?> 
<script>
    $('#reminder').bootstrapToggle();

     $('#reminder_date')
        .datepicker({autoclose: true
        });
    $('body').delegate('#task_submit_btn', 'click', function () {
        if ($('#frmtask').parsley().isValid()) {
            $('input[type="submit"]').prop('disabled', true);
            $('#frmtask').submit();
        }
    });
    var dateToday = new Date();
    $('#start_date').datepicker({
        autoclose: true,
        startDate: '-0m'
    })
            .on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#end_date').datepicker('setStartDate', startDate);
                $('#reminder_date input').val("");
                <?php if (!empty($edit_record[0]['task_id'])) { ?>
                $('#reminder_date input').attr('data-parsley-required', 'true');
                <?php } ?>
                $('#reminder_date').datepicker('setStartDate', startDate);
            });

    //set end date as per start date
    $('#end_date')
            .datepicker({autoclose: true,
                startDate: '-0m'
            }).on('changeDate', function (selected) {
            endDate = new Date(selected.date.valueOf());
            endDate.setDate(endDate.getDate(new Date(selected.date.valueOf())));
            $('#start_date').datepicker('setEndDate', endDate);
            startDate = $("#start_date").data('datepicker').getFormattedDate('mm/dd/yyyy');
            $('#reminder_date input').val("");
            <?php if (!empty($edit_record[0]['task_id'])) { ?>
            $('#reminder_date input').attr('data-parsley-required', 'true');
            <?php } ?>
            $('#reminder_date').datepicker('setEndDate', endDate);
//         $('#reminder_date input').datepicker('update', '');
    });

    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
        {
            $(className).show();
            $('#frmtask').parsley().reset();
            $('.reminder_time').attr('data-parsley-required', 'true');
            $('.reminder_date').attr('data-parsley-required', 'true');
            $('#frmtask').parsley();
        }
        else
        {
            $(className).hide();
            $('#frmtask').parsley().reset();
            $('.reminder_time').attr('data-parsley-required', 'false');
            $('.reminder_date').attr('data-parsley-required', 'false');
            $('#frmtask').parsley();
        }
    }

<?php
if (!empty($edit_record[0]['task_id'])) {
    ?>
        startDate = $("#start_date").data('datepicker').getFormattedDate('mm/dd/yyyy');
        endDate = $("#end_date").data('datepicker').getFormattedDate('mm/dd/yyyy');
        $('#reminder_date').datepicker('setStartDate', startDate);
        $('#reminder_date').datepicker('setEndDate', endDate);
    <?php } else {
    ?>
        startDate = $("#start_date").data('datepicker').getFormattedDate('mm/dd/yyyy');
        $('#reminder_date').datepicker('setStartDate', startDate);

    <?php
}
?>


<?php
if (!empty($id)) {
    ?>
        $('#reminder_time').datetimepicker({format: 'HH:mm'});
<?php } else {
    ?>

        $('#reminder_time').datetimepicker({format: 'HH:mm', minDate: moment()});

<?php } ?>

</script>

<script>

    function delete_request(task_id, redirect_link) {
        task_id = $('#task_id').val();
        var delete_url = "<?php echo base_url(); ?>SupportTask/deletetask/?id=" + task_id + "&link=" + redirect_link;
        var delete_meg ="<?php echo $this->lang->line('task_delete_message');?>";
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
                        window.location.href = delete_url;
                        dialog.close();
                    }
                }]
            });

    }

    function complete_request(task_id, redirect_link) {
        task_id = $('#task_id').val();
        var complete_url = "<?php echo base_url(); ?>SupportTask/completeTask/?id=" + task_id + "&link=" + redirect_link;
        var delete_meg ="<?php echo $this->lang->line('confirm_complete_task');?>";
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
                        window.location.href = complete_url;
                        dialog.close();
                    }
                }]
            });

    }

    $('.task_remove_btn').on('click', function () {
        var val = $('#task_id').val();

        var data = 'task_id=' + val;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url($task_view); ?>/deletetask",
            data: data,
            success: function (data) {

                window.location.href = "<?php echo base_url($task_view); ?>";
            }
        });
        return false;
    });




</script>