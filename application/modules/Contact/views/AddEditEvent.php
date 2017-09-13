<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!empty($editRecord)) {
    $formAction = 'updateEventRecord';
} else {
    $formAction = 'insertEvent';
}
$path = base_url() . 'Contact/' . $formAction;
?>
<div class="modal-dialog modal-lg">
    <?php
    $attributes = array("name" => "form_event", "id" => "form_event", 'data-parsley-validate' => "");
    echo form_open_multipart($path, $attributes);
    ?>
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="set_label"><?php echo $modal_title; ?>
            </h4>
        </div>
        <div class="modal-body">

            <div class="form-group row">
                <div class="col-sm-6">
                    <label><?= $this->lang->line('EVENT_TITLE') ?> : *</label>
                    <input type="text" name="event_title" required="" id="event_title" value="<?php
                    if (isset($editRecord[0]['event_title']) && $editRecord[0]['event_title'] != '') {
                        echo htmlentities($editRecord[0]['event_title']);
                    }
                    ?>" class="form-control"/>
                </div>

                <div class = "col-xs-12 col-sm-3">
                    <label><?= $this->lang->line('EVENT_DATE') ?> : *</label>
                    <div class="input-group date" id="event_date">

                        <input type="text" class="input-append date form_datetime form-control" data-parsley-errors-container="#event_date_error" required placeholder="<?= $this->lang->line('EVENT_DATE') ?>" id="event_date" name="event_date" onkeydown="return false" value="<?php
                        if (!empty($editRecord[0]['event_date']) && $editRecord[0]['event_date'] != '0000-00-00') {
                            echo date('Y-m-d', strtotime($editRecord[0]['event_date']));
                        }
                        ?>">
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                    </div>
                    <span id="event_date_error"></span>
                </div>
                <div class = "col-xs-12 col-sm-3">
                    <label><?= $this->lang->line('event_time') ?> : *</label>
                    <div class="input-group date" id="event_time1">

                        <input type="text" data-parsley-errors-container="#event_time_error"  class="input-append date form_datetime form-control" required placeholder="<?= $this->lang->line('EVENT_DATE') ?>" id="event_time" name="event_time" onkeydown="return false" value="<?php
                        if (!empty($editRecord[0]['event_time']) && $editRecord[0]['event_time'] != '0000-00-00') {
                            echo date('YYYY-m-d H:i', strtotime($editRecord[0]['event_time']));
                        }
                        ?>" >
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                    </div>
                    <span id="event_time_error"></span>
                </div>

                
            </div>
            
            <div class="form-group row">
                <div class = "col-sm-6">
                    <?php
                    if (!empty($editRecord[0]['event_remember'])) {

                        $reminder_id = "first_time_show";
                    } else {
                        $reminder_id = "first_time_hide";
                    }
                    ?>
                    <div class="pad_top20">
                        <input <?php if (!empty($editRecord[0]['event_remember'])) { ?>checked="checked"<?php } ?> data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"  class="event_reminder_tog" data-toggle="toggle" data-onstyle="success" type="checkbox"  id="event_reminder" name="event_reminder" onChange="toggle_show(<?php echo "'#" . $reminder_id . "'"; ?>, this)"/>
                        <label>
<?= $this->lang->line('reminder?') ?>
                        </label>
                    </div>
                </div> 
                <div id="<?php echo $reminder_id; ?>">
                    <div class = "col-sm-3">
                        <label><?= $this->lang->line('REMINDER_DATE') ?> : *</label>
                        <div class="input-group date" id="reminder_date1">

                            <input type="text" data-parsley-errors-container="#reminder_date_errors" class="input-append date form_datetime form-control" placeholder="<?= $this->lang->line('REMINDER_DATE') ?>" id="reminder_date" name="reminder_date" onkeydown="return false" value="<?php
                            if (!empty($editRecord[0]['reminder_date']) && $editRecord[0]['reminder_date'] != '0000-00-00') {
                                echo date('YYYY-m-d', strtotime($editRecord[0]['reminder_date']));
                            }
                            ?>" >
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>

                        </div>
                        <span id="reminder_date_errors"></span>

                    </div>
                    
                   
                    <div class = "col-sm-3">
                        <label><?= $this->lang->line('REMINDER_TIME') ?> : *</label>
                        <div class="input-group date" id="reminder_time1">

                            <input type="text" data-parsley-errors-container="#reminder_time_errors" class="input-append date form_datetime form-control" required placeholder="<?= $this->lang->line('REMINDER_TIME') ?>" id="reminder_time" name="reminder_time" onkeydown="return false" value="<?php
                            if (!empty($editRecord[0]['reminder_time']) && $editRecord[0]['reminder_time'] != '0000-00-00') {
                                echo date('H:i', strtotime($editRecord[0]['reminder_time']));
                            }
                            ?>" >
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> 
                        </div>
                        <span id="reminder_time_errors"></span>
                    </div>
                
                 </div>
                
            </div>
            
            <div class="clr"></div>

            <div class ="form-group row">

                <div class="col-sm-6">
                    <label><?= $this->lang->line('EVENT_NOTE') ?> : *</label>
                    <textarea class="form-control" required="" maxlength="150" onkeyup="checkTextareaWord()" rows="2" name="note_description" id="note_description" placeholder="<?php echo lang('EVENT_NOTE'); ?>"><?= !empty($editRecord[0]['event_note']) ? $editRecord[0]['event_note'] : '' ?></textarea>
                    <label id="QtChar"> </label>
                </div>
                <div class="col-sm-6">
                    <label><?php echo lang('event_place') ?> : *</label>
                    <textarea class="form-control" required="" maxlength="150" rows="2" name="event_place" id="event_place" placeholder="<?php echo lang('event_place') ?>"><?= !empty($editRecord[0]['event_place']) ? $editRecord[0]['event_place'] : '' ?></textarea>
                </div>
            </div>
            <div class ="form-group row">
                <div class="col-sm-6">

                    <label class="custom-upload btn btn-blue"><?php echo lang('EVENT_IMAGE');?> 
                        <input class="btn btn-default btn-file input-group " type="file" name="import_file" id="import_file" placeholder="Import File" onchange="$('#import_file_txt').html($('#import_file').val().split('\\').pop());" value="">
                    </label>

                    <?php
                    if (isset($editRecord[0]['event_image']) && $editRecord[0]['event_image']) {
                        $img_path = base_url() . "uploads/events/" . $editRecord[0]['event_image'];
                    } else {
                        $img_path = base_url() . "uploads/images/noimage.jpg";
                    }
                    ?>
                    <img src="<?php echo $img_path; ?>" width="48"/>
                    <p id="import_file_txt"></p>
                </div>

            </div>
            <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
            <input type="hidden" name="event_related_id" id="event_related_id" value="<?= !empty($event_related_id) ? $event_related_id : '' ?>">
            <input type="hidden" name="event_id" id="event_id" value="<?= !empty($event_id) ? $event_id : '' ?>  " />
            <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>"> 
        </div>
        <div class="modal-footer">
            <center> <input type="submit" class="btn btn-primary" id="contact_submit_btn" value="<?= $submit_button_title ?>"></center>
        </div>
    </div>
<?php echo form_close(); ?>
</div>

<script>
    $(document).ready(function () {
        $('#form_event').parsley();
    });
</script>
<script>

    $(function ()
    {
        $("#QtChar")[0].innerText = "Allowed Character :" + parseInt($("#note_description")[0].maxLength);
    });
    function checkTextareaWord()
    {
        $("#QtChar")[0].innerText = "Allowed Character : " + parseInt($("#note_description")[0].maxLength - $("#note_description").val().length);
    }

    var current_date = new Date();
    $('.event_reminder_tog').bootstrapToggle();

  
  
<?php
if (!empty($event_id)) {
    ?>
        var da= '<?php echo  date('m-d-Y', strtotime($editRecord[0]['event_date'])); ?>';
        $('#event_date').datepicker({autoclose: true,dateFormat: "yy-mm-dd",startDate : '-0m'}).datepicker("setDate",da);
      
<?php } else {
    ?>
      $("#event_date").datepicker({autoclose: true,dateFormat: "yy-mm-dd",startDate : '-0m'}).datepicker("setDate", new Date());
<?php } ?>

<?php
if (!empty($event_id)) {
    ?>
        $('#event_time').datetimepicker({format: 'HH:mm'});
<?php } else {
    ?>
        $('#event_time').datetimepicker({format: 'HH:mm',minDate: moment()});
<?php } ?>

     <?php 
    if(!empty($event_id))
    { ?>
        var re= '<?php echo  date('m-d-Y', strtotime($editRecord[0]['reminder_date'])); ?>';
        var da1= '<?php echo  date('m-d-Y', strtotime($editRecord[0]['event_date'])); ?>';
        $('#reminder_date').datepicker({autoclose: true,dateFormat: "yy-mm-dd",startDate : '-0m',endDate:da1}).datepicker("setDate",re);
    <?php }else{
    ?>
        $("#reminder_date").datepicker({autoclose: true,dateFormat: "yy-mm-dd",startDate : '-0m',endDate:'-0m'}).datepicker("setDate", new Date());
   <?php } ?>
    
    <?php 
    if(!empty($event_id))
    { ?>
        $('#reminder_time').datetimepicker({format: 'HH:mm'});
   <?php }else{
    ?>
        $('#reminder_time').datetimepicker({format: 'HH:mm',minDate:moment()});
        
   <?php } ?>
       
    $('.chosen-select').chosen();
    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
        {
           
            $(className).show();
            $('#form_event').parsley().reset();
            $('#reminder_date').attr('data-parsley-required', 'true');
            $('#reminder_time').attr('data-parsley-required', 'true');
            $('#form_event').parsley();
        }
        else
        {
           
            $(className).hide();
            $('#form_event').parsley().reset();
            $('#reminder_date').attr('data-parsley-required', 'false');
            $('#reminder_time').attr('data-parsley-required', 'false');
            $('#form_event').parsley();
        }
    }
    
    
    /*
    
    $('#event_date').on('change dp.change', function(e){
        
       
        var start_date= e.date._d;
        
        var picker3 = $('#event_time').data("DateTimePicker");
            if (picker3) {
                picker3.destroy();
            }
        $('#event_time').datetimepicker({format: 'HH:mm', maxDate:start_date, minDate:null});
        
        var picker2 = $('#reminder_date').data("DateTimePicker");
            if (picker2) {
                picker2.destroy();
            }
        $('#reminder_date').datetimepicker({format: 'YYYY-MM-DD', maxDate:start_date, minDate:moment()});
        
        var picker1 = $('#reminder_time').data("DateTimePicker");
            if (picker1) {
                picker1.destroy();
            }
            $('#reminder_time').datetimepicker({format: 'HH:mm',minDate:null});
    });
      
      
      */
     
     
     $('#event_date').change(function(e){
        startDate = $("#event_date").data('datepicker').getFormattedDate('mm/dd/yyyy');
        $('#reminder_date').datepicker('setStartDate', '-0m');
        $('#reminder_date').datepicker('setEndDate', startDate);
        $("#reminder_date").datepicker('setDate', startDate);
        
        var today = new Date();
        today.setHours(0);
        today.setMinutes(0);
        today.setSeconds(0);
        if (Date.parse(today) == Date.parse(startDate)) 
        {
            var picker1 = $('#event_time').data("DateTimePicker");
            if (picker1) {
                picker1.destroy();
            }
            $('#event_time').datetimepicker({format: 'HH:mm', minDate:moment()});
            
            var picker2 = $('#reminder_time').data("DateTimePicker");
            if (picker2) {
                picker2.destroy();
            }
            $('#reminder_time').datetimepicker({format: 'HH:mm', minDate:moment()});
        }else
        {
            var picker1 = $('#event_time').data("DateTimePicker");
            if (picker1) {
                picker1.destroy();
            }
            $('#event_time').datetimepicker({format: 'HH:mm', minDate:null});
            
            var picker2 = $('#reminder_time').data("DateTimePicker");
            if (picker2) {
                picker2.destroy();
            }
            $('#reminder_time').datetimepicker({format: 'HH:mm', minDate:null});
            
            
        }
        
        }   );
        
        
        $('#reminder_date').change(function(e)
        {
           
            var reminderdate = $("#reminder_date").data('datepicker').getFormattedDate('mm/dd/yyyy');
           
            
            var today = new Date();
            today.setHours(0);
            today.setMinutes(0);
            today.setSeconds(0);
            if (Date.parse(today) == Date.parse(reminderdate)) 
            {
                //today
                var picker2 = $('#reminder_time').data("DateTimePicker");
                if (picker2) {
                    picker2.destroy();
                }
                $('#reminder_time').datetimepicker({format: 'HH:mm', minDate:moment()});
            }else
            {
                //not today
                
                var picker2 = $('#reminder_time').data("DateTimePicker");
                if (picker2) {
                    picker2.destroy();
                }
                $('#reminder_time').datetimepicker({format: 'HH:mm', minDate:null});
            }
           
            
        });
</script>