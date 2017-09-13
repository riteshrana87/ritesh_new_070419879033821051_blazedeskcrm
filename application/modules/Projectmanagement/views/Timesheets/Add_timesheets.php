<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath = $timiesheet_view . '/' . $formAction;
date_default_timezone_set($this->session->userdata('LOGGED_IN')['TIMEZONE']);
?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <form id="from-model" method="post" action="<?php echo base_url($formPath); ?>" enctype="multipart/form-data"
              data-parsley-validate>
            <!-- Modal content-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <div class="modelMilestoneTitle"><?= $modal_title ?></div>
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="  control-label"><?= lang('selecting_task') ?> <span class="viewtimehide">*</span></label>
                        <select tabindex="-1" name="task_id" class="chosen-select form-control" onchange="checkEstimateHours(this.value);"
                                data-placeholder="<?= lang('selecting_task') ?> " required>
                            <option value=""></option>
                            <?php
                            if (!empty($task_data)) {
                                foreach ($task_data as $row) {
                                    ?>
                                    <option <?php
                                    if (!empty($edit_record[0]['task_id']) && ($edit_record[0]['task_id'] == $row['task_id'])) {
                                        echo 'selected="selected"';
                                    }
                                    ?> value="<?= $row['task_id'] ?>"><?= ucfirst($row['task_name']) ?></option>
                                        <?php
                                    }
                                }
                                ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">

                    <div class="col-sm-12">
                        <label class="control-label"><?= lang('estimate_time') ?> <span class="viewtimehide">*</span> </label>
                        <?php if (isset($edit_record) && isset($estimate_time_data) && count($estimate_time_data) > 0 && ($estimate_time_data[0]['estimate_time'] != '')) { ?>
                            <?php if ($estimate_time_data[0]['timesheet_id'] == $edit_record[0]['timesheet_id']) { ?>
                                <input type="text"  maxlength="5" onkeypress="return numericDecimal(event)" class="form-control"
                                       placeholder="<?= lang('estimate_time') ?>" id="estimate_time" name="estimate_time"
                                       value="<?= !empty($edit_record[0]['estimate_time']) ? $edit_record[0]['estimate_time'] : '' ?>"
                                       required >
                                   <?php } else { ?>
                                <b><?php echo!empty($estimate_time_data[0]['estimate_time']) ? $estimate_time_data[0]['estimate_time'] : '' ?> Hours</b>
                                <input type="hidden"  id="estimate_time" name="estimate_time" value="<?= !empty($edit_record[0]['estimate_time']) ? $edit_record[0]['estimate_time'] : '' ?>">
                            <?php } ?>
                        <?php } else { ?>
                            <input type="text"  maxlength="5" onkeypress="return numericDecimal(event)" data-parsley-pattern="/^\d{0,3}(\.\d{0,2})?$/" class="form-control"
                                   placeholder="<?= lang('estimate_time') ?>" id="estimate_time" name="estimate_time"
                                   value="<?= !empty($edit_record[0]['estimate_time']) ? $edit_record[0]['estimate_time'] : '' ?>"
                                   required >
                            <div id="EstimateTimeHolder"></div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-12">
                        <label class="control-label"><?= lang('spent_time') ?> <span class="viewtimehide">*</span> </label>
                        <input type="text"  maxlength="5" onkeypress="return numericDecimal(event)" data-parsley-pattern="/^\d{0,3}(\.\d{0,2})?$/"  class="form-control"
                               placeholder="<?= lang('spent_time') ?>" id="spent_time" name="spent_time"
                               value="<?php echo (isset($edit_record) && $edit_record[0]['spent_time'] != '') ? $edit_record[0]['spent_time'] : '' ?>"
                               required >
                    </div>
                </div>
                <div class="form-group row text-center">
                    <div class="<?php echo (isset($edit_record) && $edit_record[0]['timer_end_flag'] == 1) ? 'col-sm-12' : ''; ?>">

                        <?php if (isset($edit_record)) { ?>
                            <input type='hidden' name='timer_start_flag_old' id='timer_start_flag_old' value=<?php echo $edit_record[0]['timer_start_flag']; ?>>
                            <input type='hidden' name='timer_end_flag_old' id='timer_end_flag_old' value=<?php echo $edit_record[0]['timer_end_flag']; ?>>
                            <?php if ($edit_record[0]['timer_start_flag'] == 1) { ?>
                                <input type="button" class="btn btn-green" value="<?= lang('started_on') ?> <?php echo date('m/d/Y H:i:s',$edit_record[0]['timer_start_timestamp']); ?>" >

                            <?php } else { ?>
                                <input type="button" class="btn btn-green" value="<?= lang('start_timer') ?>" onclick="startTimer(this);" >
                            <?php } ?>
                            <?php if ($edit_record[0]['timer_end_flag'] == 1) { ?>
                                <input type="button" class="btn btn-green" value="Ended on <?php echo date('m/d/Y H:i:s',$edit_record[0]['timer_end_timestamp']); ?>" >
                            <?php } else if ($edit_record[0]['timer_start_flag'] == 1) { ?>
                                <input type="button" class="btn btn-green " value="<?= lang('stop_timer') ?>" onclick="endTimer(this);">
                               
                            <?php } else { ?>

                            <?php } ?>

                        <?php } else { ?>
                            <input type="button" class="btn btn-green" value="<?= lang('start_timer') ?>" onclick="startTimer(this);" >
                        <?php } ?>
				
                  
                   
                     <?php if(!empty($edit_record[0]['timer_start_flag']) && empty($edit_record[0]['timer_end_flag'])){ ?>
               
               
                     <?php if(empty($edit_record[0]['timer_pause_flag'])){ ?>
                    <input type="button" class="btn btn-green" value="<?= lang('pause_timer') ?>" onclick="posetimer(this);" >
                    <?php } else {?>
                    <input type="button" class="btn btn-green" id="stop_timer" value="<?= lang('start_timer') ?>" onclick="startposeTimer(this);" >
                    <?php }?>
                   
                
                                  </div>
                 <div class="clr"></div>
                 <?php }?>
               
                </div>
                 <div class="form-group row text-center">
                        <?php if (isset($edit_record)) { ?>
                            <?php if ($edit_record[0]['timer_start_flag'] == 1 && $edit_record[0]['timer_end_flag'] == 0) { ?>
                                <a href="javascript:;" class="btn btn-green counterbtn"><div id="countdown2" ></div></a>
                                <?php
                            }
                        }
                        ?>
                            <input type="hidden" value="" id="countdown" name="countdown">
                    </div>
                <div class="form-group row text-center">
                    <div class="col-lg-12">
                        <?php if (isset($edit_record) && $edit_record[0]['timer_start_flag'] == 1 && $edit_record[0]['timer_end_flag'] == 0) { ?>
                            <br/>
                            <b><div id="countdownTotal" class="font-2em"></div></b>
                        <?php } else if (isset($edit_record) && $edit_record[0]['timer_start_flag'] == 1 && $edit_record[0]['timer_end_flag'] == 1) { ?>
                            <div id="countdownEnd">

                                <span class="font-2em spendHrs"> <b><?php
//                                        $diff = $edit_record[0]['timer_end_timestamp'] - $edit_record[0]['timer_start_timestamp'];
//                                        $t = gmdate("H:i", $diff + (3600));
                                        echo "Total Spent " . $edit_record[0]['spent_time'] . " Hours";
                                        ?></b></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <label class="control-label text-left width-100"><?= lang('description') ?> </label>
                        <textarea name="description" id="description"
                                  placeholder="<?= lang('description') ?>" class="form-control"
                                  ><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></textarea>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <center>
               
                    <input type="hidden" id="timesheet_id" name="timesheet_id"
                           value="<?= !empty($edit_record[0]['timesheet_id']) ? $edit_record[0]['timesheet_id'] : '' ?>">
                    <input type="hidden" id="display" name="display" value="<?= !empty($home) ? $home : '' ?>">
                    <input type="hidden" id="total_spent" name="total_spent" value="<?= !empty($edit_record[0]['spent_time']) ? $edit_record[0]['spent_time'] : '' ?>">
                    <input type="hidden" id="paush_timer" name="paush_timer" value="">
                    <input type="hidden" id="resume_timer" name="resume_timer" value="<?= !empty($edit_record[0]['timer_pause_flag']) ? '' : '1' ?>">
                    <input type="hidden" id="hours_diff" name="hours_diff" value="">
                    <input type="hidden" id="curr_date" name="curr_date" value="">
                    <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
                    <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn"
                           value="<?= $submit_button_title ?>"/>


            </div>

    </div>
</form>
</div>
</div><!-- /.modal-dialog -->


<?php //echo form_close();          ?>

<?php if (isset($edit_record) && $edit_record[0]['timer_start_flag'] == 1 && $edit_record[0]['timer_pause_flag'] == 0 && $edit_record[0]['timer_end_flag'] == 0) { ?>
    <script>

        var intervalTimer = '';
        var timeStart = '';
        var timeEnd = '';
        var hourDiff = '';

        var secDiff = '';
        var minDiff = '';
        var hDiff = '';
        var spentHrs ='<?php echo $edit_record[0]["spent_time"]; ?>';
        
        var humanReadable = {
        };
        intervalTimer = setInterval(function () {

            timeEnd = new Date();
            //console.log('start'+timeEnd);
            timeEnd = timeEnd.getTime();
           
            timeStart = new Date("<?php echo date("F d, Y G:i:s", $timer_updatestart_timestamp); ?>");
            //console.log('update'+timeStart);
            timeStart = timeStart.getTime();
            hourDiff = timeEnd - timeStart; //in ms
            secDiff = hourDiff / 1000; //in s
            minDiff = hourDiff / 60 / 1000; //in minutes
            hDiff = hourDiff / 3600 / 1000; //in hours
            humanReadable.hours = Math.floor(hDiff);
            humanReadable.minutes = minDiff - 60 * humanReadable.hours;
            $('#countdown2').empty();
            $('#countdown2').html("" + Math.round(humanReadable.hours) + ":" + Math.round(humanReadable.minutes) + " Hours");
            $('#countdown').val(Math.round(humanReadable.hours) + ":" + Math.round(humanReadable.minutes));
            if(spentHrs != '')
            {
                spt=spentHrs.split('.');
                
                if(spt[1])
                {spt[1]=spt[1];}else{spt[1] ='00';}
                var time1 = spt[0]+":"+spt[1]+":00";          
            }
            else
            {
                var time1 = "00:00:00";    
            }
            var time2 = Math.round(humanReadable.hours) + ":" + Math.round(humanReadable.minutes)+":00";   
            
            
            var hour=0;
            var minute=0;
            var second=0;
            
            var splitTime1= time1.split(':');
            var splitTime2= time2.split(':');
            //var splitTime3= time3.split(':');
            
            hour = parseInt(splitTime1[0])+parseInt(splitTime2[0]);
            minute = parseInt(splitTime1[1])+parseInt(splitTime2[1]);
            if(minute > 60)
            {hour = (hour + minute/60).toFixed(0);}
            else{hour = Math.round(parseInt(humanReadable.hours) + parseInt(spentHrs));}    
            //var hour = Math.round(parseInt(humanReadable.hours) + parseInt(spentHrs));
            minute = minute%60;
            second = parseInt(splitTime1[2])+parseInt(splitTime2[2]);
            minute = (minute + second/60).toFixed(0);
            second = (second%60).toFixed(0);

            $('#countdownTotal').html('<?= lang('total_spent') ?>'+hour+':'+minute+':'+second);
            $('#total_spent').val(hour+'.'+minute);
            //$('#countdownTotal').html("Total Spent " + Math.round(humanReadable.hours + spentHrs) + ":" + Math.round(humanReadable.minutes) + " Hours");
            $('#countdown2').removeClass('hidden');
            $('.counterbtn').removeClass('hidden');
            $('#countdownTotal').removeClass('hidden');
        }, 1000);
        $('#ajaxModal').on('hidden.bs.modal', function () {
            clearInterval(intervalTimer);
        })

    </script>

<?php } ?>
<script type="text/javascript">

    $(function () {
    //subtract time
    Date.prototype.subTime= function(h,m){
    this.setHours(this.getHours()-h);
    this.setMinutes(this.getMinutes()-m);
    return this;
    }
     Date.prototype.addTime= function(h,m){
    this.setHours(this.getHours()+h);
    this.setMinutes(this.getMinutes()+m);
    return this;
    }  
    //Valiadtion for not valida time
    /*window.Parsley.addValidator('ptime',
            function (value, requirement) {
                return value > 0;
            }, 32)
            .addMessage('en', 'ptime', 'Hours should be greater than 0.');*/
            
        $('#description').summernote({
            height: 70, //set editable area's height
            codemirror: {// codemirror options
                theme: 'monokai'
            },
            focus: true
        });
        
        $('.chosen-select').chosen();
        //$('.chosen-select-deselect').chosen({ allow_single_deselect: true });

        $('#from-model').parsley(); //parsaley validation reload

        //disabled after submit
        $('body').delegate('#submit_btn', 'click', function () {
            if ($('#from-model').parsley().isValid()) {
                 var today = new Date();

                  var yyyy = today.getFullYear();
                  var mm   = today.getMonth()+1; //January is 0!
                  if(mm<10)  { mm="0"+mm } 
                  var dd   = today.getDate();
                  if(dd<10)  { dd="0"+dd } 
                  var hour = today.getHours();
                  var minu = today.getMinutes();
                  if(minu<10){ minu="0"+minu } 
                  var serverdate=yyyy+"-"+mm+"-"+dd+" "+hour+":"+minu+":00";
                  $('#curr_date').val(serverdate);
                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }
        });
    });
    //numeric decimal number
    function numericDecimal(e) {
        var unicode = e.charCode ? e.charCode : e.keyCode;
        //alert(unicode);
        if (unicode != 8) {
            if (unicode < 9 || unicode > 9 && unicode < 46 || unicode > 57 || unicode == 47) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return true;
        }
    }
    //function for start timer
    function startTimer(elm)
    {
        if ($(elm).val() != '<?= lang('started') ?>')
        {
            $('#timer_start_flag').remove();
            $(elm).before().append("<input type='hidden' name='timer_start_flag' id='timer_start_flag' value='1'>");
            $(elm).val('<?= lang('started') ?>');
        }else{
			$('#timer_start_flag').remove();
            $(elm).before().append("<input type='hidden' name='timer_start_flag' id='timer_start_flag' value='0'>");
            $(elm).val('<?= lang('start_timer') ?>');
		}

    }
    /*<!-- Timer when pause -->*/
    <?php if(!empty($edit_record) && $edit_record[0]['timer_pause_flag'] == 1) {?>

        var elm= document.getElementById('stop_timer');
         
         //var clock =new Date('Thu Apr 14 2016 12:38:46 GMT+0530 (India Standard Time)');

         
        var humanReadable = {
        };
         var spentHrs ='<?php echo $edit_record[0]["spent_time"]; ?>';
       
            
            <?php if(!empty($edit_record[0]['timer_restart_timestamp'])){ ?> 
                timeEnd =new Date("<?php echo date("F d, Y G:i:s", $edit_record[0]['timer_pause_timestamp']); ?>");
                clock = new Date("<?php echo date("F d, Y G:i:s", $timer_updatestart_timestamp); ?>")
            <?php } else { ?>
                timeEnd =new Date();
                var clock= calculation(elm);
            <?php } ?>
            //console.log('clock'+clock);
            //console.log('timeEnd'+timeEnd);
            timeEnd = timeEnd.getTime();
            timeStart = clock;
            timeStart = timeStart.getTime();
            hourDiff = timeEnd - timeStart; //in ms
            secDiff = hourDiff / 1000; //in s
            minDiff = hourDiff / 60 / 1000; //in minutes
            hDiff = hourDiff / 3600 / 1000; //in hours
            humanReadable.hours = Math.floor(hDiff);
            humanReadable.minutes = minDiff - 60 * humanReadable.hours;
            $('#countdown').val(Math.round(humanReadable.hours) + ":" + Math.round(humanReadable.minutes));
            $('#countdown2').empty();
            $('#countdown2').html("" + Math.round(humanReadable.hours) + ":" + Math.round(humanReadable.minutes) + " Hours");
            if(spentHrs != '')
                {
                    spt=spentHrs.split('.');
                    
                    if(spt[1])
                    {spt[1]=spt[1];}else{spt[1] ='00';}
                    var time1 = spt[0]+":"+spt[1]+":00";          
                }
                else
                {
                    var time1 = "00:00:00";    
                }
                var time2 = Math.round(humanReadable.hours) + ":" + Math.round(humanReadable.minutes)+":00";   
                
                
                var hour=0;
                var minute=0;
                var second=0;
                
                var splitTime1= time1.split(':');
                var splitTime2= time2.split(':');
                //var splitTime3= time3.split(':');
                
                hour = parseInt(splitTime1[0])+parseInt(splitTime2[0]);
                minute = parseInt(splitTime1[1])+parseInt(splitTime2[1]);
                if(minute > 60)
                {hour = (hour + minute/60).toFixed(0);}
                else{hour = Math.round(parseInt(humanReadable.hours) + parseInt(spentHrs));}    
                //var hour = Math.round(parseInt(humanReadable.hours) + parseInt(spentHrs));
                minute = minute%60;
                second = parseInt(splitTime1[2])+parseInt(splitTime2[2]);
                minute = (minute + second/60).toFixed(0);
                second = (second%60).toFixed(0);
                //console.log('Total Spent '+hour+':'+minute+':'+second);
                $('#countdownTotal').html('<?= lang('total_spent') ?>'+hour+':'+minute+':'+second);
                $('#total_spent').val(hour+'.'+minute);
        function calculation(elm)
        {
            
            var push = $('#paush_timer').val();
            
            var intervalTimer = '';
            var timeStart = '';
            var timeEnd1 = '';
            var timePause ='';
            var hourDiff1 = '';

            var secDiff1 = '';
            var minDiff1 = '';
            var hDiff1 = '';

            var intervalTimer = '';
            var timeStart = '';
            var timeEnd = '';
            var timePause ='';
            var hourDiff = '';

            var secDiff = '';
            var minDiff = '';
            var hDiff = '';
            var humanReadable = {
            };
            var humanReadable1 = {
            };
            
            
            timeEnd1 = new Date("<?php echo date("F d, Y G:i:s", $edit_record[0]['timer_pause_timestamp']); ?>");;
            //console.log('timeEnd1'+timeEnd1);
            timeEnd1 = timeEnd1.getTime();
            <?php if(!empty($edit_record[0]['timer_restart_timestamp'])){ ?> 
                timePause = new Date("<?php echo date("F d, Y G:i:s", $edit_record[0]['timer_restart_timestamp']); ?>");
            <?php } else { ?>
                timePause = new Date("<?php echo date("F d, Y G:i:s", $edit_record[0]['timer_start_timestamp']); ?>");
            <?php } ?>
           // console.log('timePause'+timePause);
            timePause = timePause.getTime();

            
            
            
            hourDiff1 = timeEnd1 - timePause; //in ms
            secDiff1 = hourDiff1 / 1000; //in s
            minDiff1 = hourDiff1 / 60 / 1000; //in minutes
            hDiff1 = hourDiff1 / 3600 / 1000; //in hours
            humanReadable1.hours = Math.floor(hDiff1);
            humanReadable1.minutes = minDiff1 - 60 * humanReadable1.hours;
            //console.log('h'+humanReadable1.hours);
            //console.log('m'+humanReadable1.minutes);
            //var clock=new Date().subTime(Math.round(humanReadable1.hours), Math.round(humanReadable1.minutes));
            
            <?php if(!empty($edit_record[0]['timer_restart_timestamp'])){ ?> 
                console.log('here');
                return clock=new Date("<?php echo date("F d, Y G:i:s", $timer_updatestart_timestamp); ?>").addTime(Math.round(humanReadable1.hours), Math.round(humanReadable1.minutes));
            <?php } else { ?>
                console.log('here1');
                 return clock=new Date().subTime(Math.round(humanReadable1.hours), Math.round(humanReadable1.minutes));
            <?php } ?>

            
            
        }
        function startposeTimer(elm)
        {
            $('#resume_timer').val('1');
            $(elm).val('Pause Timer');
            $(elm).attr('onclick','posetimer(this)');
            $('#hours_diff').val('<?=strtotime(datetimeformat())?>');
            var intervalTimer = '';
            var timeStart = '';
            var timeEnd1 = '';
            var timePause ='';
            var hourDiff1 = '';

            var secDiff1 = '';
            var minDiff1 = '';
            var hDiff1 = '';
            var humanReadable1 = {
            };
            
            timeEnd1 = new Date();
            //console.log('timeEnd1'+timeEnd1);
            timeEnd1 = timeEnd1.getTime();
            timePause = new Date("<?php echo date("F d, Y G:i:s", $edit_record[0]['timer_pause_timestamp']); ?>");
           // console.log('timePause'+timePause);
            timePause = timePause.getTime();

            
            
            
            hourDiff1 = timeEnd1 - timePause; //in ms
            secDiff1 = hourDiff1 / 1000; //in s
            minDiff1 = hourDiff1 / 60 / 1000; //in minutes
            hDiff1 = hourDiff1 / 3600 / 1000; //in hours
            humanReadable1.hours = Math.floor(hDiff1);
            humanReadable1.minutes = minDiff1 - 60 * humanReadable1.hours;
            //console.log('h'+humanReadable1.hours);
            //console.log('m'+humanReadable1.minutes);
            //var clock=new Date().subTime(Math.round(humanReadable1.hours), Math.round(humanReadable1.minutes));
            
            var clock=new Date("<?php echo date("F d, Y G:i:s", $timer_updatestart_timestamp); ?>").addTime(Math.round(humanReadable1.hours), Math.round(humanReadable1.minutes));
            
            //var clock= calculation(elm);
            start_interval(clock);
           
        }
        function start_interval(clock)
        {
             intervalTimer = setInterval(function () {timer_cal(clock)
            }, 1000);
        }
        function timer_cal(clock)
        {
            var humanReadable = {
            };
             var spentHrs ='<?php echo $edit_record[0]["spent_time"]; ?>';
           
                timeEnd = new Date();
                timeEnd = timeEnd.getTime();
                timeStart = clock;
                timeStart = timeStart.getTime();
                hourDiff = timeEnd - timeStart; //in ms
                secDiff = hourDiff / 1000; //in s
                minDiff = hourDiff / 60 / 1000; //in minutes
                hDiff = hourDiff / 3600 / 1000; //in hours
                humanReadable.hours = Math.floor(hDiff);
                humanReadable.minutes = minDiff - 60 * humanReadable.hours;
                $('#countdown2').empty();
                $('#countdown2').html("" + Math.round(humanReadable.hours) + ":" + Math.round(humanReadable.minutes) + " Hours");
                
                if(spentHrs != '')
                {
                    spt=spentHrs.split('.');
                    
                    if(spt[1])
                    {spt[1]=spt[1];}else{spt[1] ='00';}
                    var time1 = spt[0]+":"+spt[1]+":00";          
                }
                else
                {
                    var time1 = "00:00:00";    
                }
                var time2 = Math.round(humanReadable.hours) + ":" + Math.round(humanReadable.minutes)+":00";   
                
                
                var hour=0;
                var minute=0;
                var second=0;
                
                var splitTime1= time1.split(':');
                var splitTime2= time2.split(':');
                //var splitTime3= time3.split(':');
                
                hour = parseInt(splitTime1[0])+parseInt(splitTime2[0]);
                minute = parseInt(splitTime1[1])+parseInt(splitTime2[1]);
                if(minute > 60)
                {hour = (hour + minute/60).toFixed(0);}
                else{hour = Math.round(parseInt(humanReadable.hours) + parseInt(spentHrs));}    
                //var hour = Math.round(parseInt(humanReadable.hours) + parseInt(spentHrs));
                minute = minute%60;
                second = parseInt(splitTime1[2])+parseInt(splitTime2[2]);
                minute = (minute + second/60).toFixed(0);
                second = (second%60).toFixed(0);
                //console.log(minute);
                $('#countdownTotal').html('<?= lang('total_spent') ?>'+hour+':'+minute+':'+second);
                $('#total_spent').val(hour+'.'+minute);
                //$('#countdownTotal').html("Total Spent " + Math.round(humanReadable.hours + spentHrs) + ":" + Math.round(humanReadable.minutes) + " Hours");
                $('#countdown2').removeClass('hidden');
                $('.counterbtn').removeClass('hidden');
                $('#countdownTotal').removeClass('hidden');
        }
    
   
    //subtract time
    Date.prototype.subTime= function(h,m){
    this.setHours(this.getHours()-h);
    this.setMinutes(this.getMinutes()-m);
    return this;
    }
    Date.prototype.addTime= function(h,m){
    this.setHours(this.getHours()+h);
    this.setMinutes(this.getMinutes()+m);
    return this;
    }
    
    
    <?php }?>
    <?php if(!empty($edit_record)) {?>
    //function for pause timer
    function posetimer(elm)
    {
        //$(elm).val('Start Timer');
        //$(elm).attr('onclick','startposeTimer(this)');
        clearInterval(intervalTimer);
        $('#paush_timer').val('<?=strtotime(datetimeformat())?>');
        $('#resume_timer').val('');
        $(elm).prop('disabled', true);
    }
    function endTimer(elm)
    {
        if ($(elm).val() != 'Stopped')
        {
            var timer_status = $('#resume_timer').val();
            if(timer_status != '')
            {
                clearInterval(intervalTimer);    
            }
            
            $('#timer_end_flag').remove();
            $(elm).before().append("<input type='hidden' name='timer_end_flag' id='timer_end_flag' value='1'>");
            $(elm).val('<?= lang('stopped') ?>');
            var resume_timer = $('#resume_timer').val();
            if(resume_timer !='')
            {
                var spentHrs ='<?php echo $edit_record[0]["spent_time"]; ?>';
                if(spentHrs != '')
                {
                    spt=spentHrs.split('.');
                    
                    if(spt[1])
                    {spt[1]=spt[1];}else{spt[1] ='00';}
                    var time1 = spt[0]+":"+spt[1]+":00";          
                }
                else
                {
                    var time1 = "00:00:00";    
                }
                var time2 = $('#countdown').val()+":00";   
                
                //console.log('t1'+time1);
                //console.log('t2'+time2);
                var hour=0;
                var minute=0;
                var second=0;
                
                var splitTime1= time1.split(':');
                var splitTime2= time2.split(':');
                //var splitTime3= time3.split(':');
                
                hour = parseInt(splitTime1[0])+parseInt(splitTime2[0]);
                minute = parseInt(splitTime1[1])+parseInt(splitTime2[1]);
                if(minute > 60)
                {hour = (hour + minute/60).toFixed(0);}
                else{hour = Math.round(parseInt(humanReadable.hours) + parseInt(spentHrs));}    
                //var hour = Math.round(parseInt(humanReadable.hours) + parseInt(spentHrs));
                minute = minute%60;
                second = parseInt(splitTime1[2])+parseInt(splitTime2[2]);
                minute = (minute + second/60).toFixed(0);
                second = (second%60).toFixed(0);
                $('#total_spent').val(hour+'.'+minute);
            }
            
        }
        else
        {
            $('#timer_end_flag').remove();
            $(elm).val('<?= lang('stop_timer') ?>');
        }
    }
    <?php }?>
    function checkEstimateHours(elid)
    {
        if (elid != "")
            $.ajax({
                url: "<?php echo base_url('Projectmanagement/Timesheets/getEstimateHrs'); ?>",
                data: {task_id: elid},
                dataType: "JSON",
                type: "POST",
                success: function (d)
                {
                    if (d.estimate_time > 0)
                    {
                        $('#estimate_time').attr('readonly', 'true');
                        $('#estimate_time').val(d.estimate_time);
                        //$('#spent_time').attr('readonly', 'true');
                        $('#spent_time').val(d.spent_time);
                    }
                    else
                    {
                        $('#estimate_time').val('');
                        $('#estimate_time').removeAttr('readonly');
                        $('#spent_time').val('');
                        //$('#spent_time').removeAttr('readonly');
                    }
                }
            });
    }
    $('#modalGallery,.note-help-dialog,.note-image-dialog,.note-link-dialog,.note-video-dialog').on('hidden.bs.modal', function () {

        $('body').addClass('modal-open');
    });
</script>

