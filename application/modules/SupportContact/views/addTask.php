<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$TaskAction = 'inserttask';
$taskPath = 'SupportContact/' . $TaskAction;
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
                <div class = "col-xs-12 col-sm-4 form-group">
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
                    <label><?= $this->lang->line('task_name') ?> * </label>
                    <input type="text" class="form-control" placeholder="<?= $this->lang->line('task_name') ?> *" required id="task_name" name="task_name" maxlength="30" value="<?php
                    if (!empty($edit_record[0]['task_name'])) {
                        echo $edit_record[0]['task_name'];
                    }
                    ?>">
                </div>
                <div class = "col-xs-12 col-sm-4 ">
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
                <?php
                if (!empty($edit_record[0]['remember'])) {
                    $reminder_id = "first_time_show";
                } else {
                    $reminder_id = "first_time_hide";
                }
                ?>

                <div class = "col-xs-12 col-sm-4 form-group">
                    <div class="pad_top20">
                        <input <?php if (!empty($edit_record[0]['remember'])) { ?>checked="checked"<?php } ?> data-toggle="toggle" data-onstyle="success" type="checkbox"  id="reminder" name="reminder" onChange="toggle_show(<?php echo "'#" . $reminder_id . "'"; ?>, this)"/>
                        <label>
                            <?= $this->lang->line('reminder?') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div id="<?php echo $reminder_id; ?>">
                <div class ="row">
                    <div class = "col-xs-12 col-sm-6">
                        <div class="row">
                            <div class = "col-xs-12 col-sm-6 form-group">
                                <select name="before_after" class="form-control chosen-select" id="before_after" data-parsley-errors-container="#rem-errors">
                                    <option value=""><?= $this->lang->line('select_before_after') ?></option>
                                    <option value="0" <?php
                                    if (isset($edit_record[0]['before_status']) && $edit_record[0]['before_status'] == "0") {
                                        echo 'selected=selected';
                                    }
                                    ?>><?= $this->lang->line('before') ?></option>
                                    <option value="1" <?php
                                    if (!empty($edit_record[0]['before_status']) && ($edit_record[0]['before_status'] == '1')) {
                                        echo 'selected=selected';
                                    }
                                    ?>><?= $this->lang->line('after') ?></option>
                                </select>
                                <div id="rem-errors"></div>
                            </div>
                            <div class = "col-xs-12 col-sm-6 form-group">
                                <select name="remind_time" class="form-control chosen-select" id="remind_time" data-parsley-errors-container="#rt-errors">
                                    <option value=""><?= $this->lang->line('select_remind_time') ?></option>
                                    <?php
                                    for ($hours = 0; $hours < 24; $hours++) { // the interval for hours is '1'
                                        for ($mins = 0; $mins < 60; $mins+=30) { // the interval for mins is '30'
                                            $time = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT);
                                            ?>
                                            <option value=" <?php echo date('H:i:s', strtotime($time)) ?> " <?php
                                            if (!empty($edit_record[0]['time']) && ($edit_record[0]['time'] == date('H:i:s', strtotime($time)) )) {
                                                echo 'selected';
                                            }
                                            ?>> <?php echo date('H:i:s', strtotime($time)) ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                </select>
                                <div id="rt-errors"></div>
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class = "col-xs-12 col-sm-6">
                        <div class="row">
                            <div class = "col-xs-12 col-sm-6 form-group">
                                <select name="repeat" class="form-control chosen-select" id="repeat" data-parsley-errors-container="#rp-errors">
                                    <option value=""><?= $this->lang->line('select_repeat') ?></option>
                                    <option value="daily" <?php
                                    if (!empty($edit_record[0]['repeat']) && ($edit_record[0]['repeat'] == 'daily')) {
                                        echo 'selected';
                                    }
                                    ?>><?= $this->lang->line('daily') ?></option>
                                    <option value="weekly" <?php
                                    if (!empty($edit_record[0]['repeat']) && ($edit_record[0]['repeat'] == 'weekly')) {
                                        echo 'selected';
                                    }
                                    ?>><?= $this->lang->line('weekly') ?></option>
                                    <option value="monthly" <?php
                                    if (!empty($edit_record[0]['repeat']) && ($edit_record[0]['repeat'] == 'monthly')) {
                                        echo 'selected';
                                    }
                                    ?>><?= $this->lang->line('monthly') ?></option>
                                    <option value="yearly" <?php
                                    if (!empty($edit_record[0]['repeat']) && ($edit_record[0]['repeat'] == 'yearly')) {
                                        echo 'selected';
                                    }
                                    ?>><?= $this->lang->line('yearly') ?></option>
                                </select>
                                <div id="rp-errors"></div>
                            </div>
                            <div class = "col-xs-12 col-sm-6 form-group">
                                <select name="remind_day" class="form-control chosen-select" id="remind_day" data-parsley-errors-container="#rd-errors">
                                    <option value=""><?= $this->lang->line('remind_before_after') ?></option>
                                    <option value="15" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '15')) {
                                        echo 'selected';
                                    }
                                    ?>>15 Minutes</option>
                                    <option value="30" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '30')) {
                                        echo 'selected';
                                    }
                                    ?>>30 Minutes</option>
                                    <option value="45" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '45')) {
                                        echo 'selected';
                                    }
                                    ?>>45 Minutes</option>
                                    <option value="60" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '60')) {
                                        echo 'selected';
                                    }
                                    ?>>1 Hour</option>
                                    <option value="120" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '120')) {
                                        echo 'selected';
                                    }
                                    ?>>2 Hour</option>
                                    <option value="180" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '180')) {
                                        echo 'selected';
                                    }
                                    ?>>3 Hour</option>
                                    <option value="1440" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '1440')) {
                                        echo 'selected';
                                    }
                                    ?>>1 Day</option>
                                    <option value="2880" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '2880')) {
                                        echo 'selected';
                                    }
                                    ?>>2 Day</option>
                                    <option value="4320" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '4320')) {
                                        echo 'selected';
                                    }
                                    ?>>3 Day</option>
                                    <option value="5760" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '5760')) {
                                        echo 'selected';
                                    }
                                    ?>>4 Day</option>
                                    <option value="7200" <?php
                                    if (!empty($edit_record[0]['remind_before_min']) && ($edit_record[0]['remind_before_min'] == '7200')) {
                                        echo 'selected';
                                    }
                                    ?>>>5 Day</option>
                                    <!-- <?php
                                    for ($hours = 0; $hours < 24; $hours++) { // the interval for hours is '1'
                                        for ($mins = 15; $mins < 60; $mins+=30) { // the interval for mins is '30'
                                            $time = str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT);
                                            ?>
                                                                                                      <option value=" <?php echo date('H:i', strtotime($time)) ?> " <?php
                                            if (!empty($edit_record[0]['remind_time']) && ($edit_record[0]['remind_time'] == " <?php echo  date('H:i', strtotime($time)) ?> ")) {
                                                echo 'selected';
                                            }
                                            ?>> <?php echo date('H:i', strtotime($time)) ?></option>
                                            <?php
                                        }
                                    }
                                    ?>-->
                                </select>
                                <div id="rd-errors"></div>
                                     
                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="clr"></div>
                </div>
            </div>
            <div class="clr"></div>

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
                <div class = "col-xs-12 col-sm-6 form-group">
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
                <div class ="col-xs-12 col-sm-6">
                    <div class="input-group date" id="end_date">
                        <input type="text" class="form-control" required placeholder="<?= $this->lang->line('finish_before') ?> *" id="end_date" name="end_date" data-parsley-errors-container="#end-date" onkeydown="return false" value="<?php
                        if (!empty($edit_record[0]['end_date']) && $edit_record[0]['end_date'] != '0000-00-00') {
                            echo date('m/d/Y', strtotime($edit_record[0]['end_date']));
                        }
                        ?>">
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    <div id="end-date"></div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <center>
                <?php $redirect_link = $_SERVER['HTTP_REFERER']; ?>

                <input type="submit" class="btn btn-primary" name="task_submit" id="task_submit_btn" value="<?= $submit_button_title ?>" />
                
            </center>
        </div>
    </div>
</div>
<?php echo form_close(); ?> 
<script>
   $('#reminder').bootstrapToggle(); 
   
  var dateToday = new Date();
    $('#start_date').datepicker({
        autoclose: true,
        startDate: '-0m'
    })
            .on('changeDate', function (selected) {
                startDate = new Date(selected.date.valueOf());
                startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                $('#end_date').datepicker('setStartDate', startDate);
            });
            
    //set end date as per start date
    $('#end_date')
            .datepicker({autoclose: true,
                startDate: '-0m'
            }).on('changeDate', function (selected) {
            endDate = new Date(selected.date.valueOf());
            endDate.setDate(endDate.getDate(new Date(selected.date.valueOf())));
            $('#start_date').datepicker('setEndDate', endDate);
        });
        
            function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
        {
            $(className).show();
            $('#frmtask').parsley().reset();
            $('#before_after').attr('data-parsley-required', 'true');
            $('#remind_time').attr('data-parsley-required', 'true');
            $('#repeat').attr('data-parsley-required', 'true');
            $('#remind_day').attr('data-parsley-required', 'true');
            $('#frmtask').parsley();
        }
        else
        {
            $(className).hide();
            $('#frmtask').parsley().reset();
            $('#before_after').attr('data-parsley-required', 'false');
            $('#remind_time').attr('data-parsley-required', 'false');
            $('#repeat').attr('data-parsley-required', 'false');
            $('#remind_day').attr('data-parsley-required', 'false');
            $('#frmtask').parsley();
        }
    }
   
    </script>
