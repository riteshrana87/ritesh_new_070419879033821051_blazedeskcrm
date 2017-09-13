<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'insertdata';
$formPath = $timiesheet_view . '/' . $formAction;
?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <!-- Modal content-->
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
                <div class="modelMilestoneTitle"><?= $modal_title ?></div>
            </h4>
        </div>

        <div class="modal-body">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang('username') ?> : </label>
                </div>
                <div class="col-sm-9">
                    <span><?= !empty($edit_record[0]['username']) ? $edit_record[0]['username'] : '' ?></span>
                </div>

            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang('task_name') ?> : </label>
                </div>
                <div class="col-sm-9">
                    <span><?= !empty($edit_record[0]['task_name']) ? $edit_record[0]['task_name'] : '' ?></span>
                </div>

            </div>

            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang('description') ?> : </label>
                </div>
                <div class="col-lg-9">
                    <span><?= !empty($edit_record[0]['description']) ? $edit_record[0]['description'] : '' ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang('estimate_time') ?> : </label>
                </div>
                <div class="col-sm-9">
                    <span><?= !empty($edit_record[0]['estimate_time']) ? $edit_record[0]['estimate_time'] : '' ?></span>
                </div>

            </div>

            <div class="form-group row">
                <div class="col-sm-3">
                    <label class="control-label"><?= lang('total_spent') ?> :</label>
                </div>
                <div class="col-sm-9">
                    <?php //!empty($edit_record[0]['spent_time']) ? $edit_record[0]['spent_time'] : '' ?>
                    <?php if (isset($edit_record) && $edit_record[0]['timer_start_flag'] == 1 && $edit_record[0]['timer_end_flag'] == 0) { ?>
                        <div id="countdownTotal"></div>
                    <?php } else if (isset($edit_record) && $edit_record[0]['timer_start_flag'] == 1 && $edit_record[0]['timer_end_flag'] == 1) { ?>
                        <div id="countdownEnd">
                            <span class=" spendHrs"><?php echo ($edit_record[0]['spent_time'] != '') ? $edit_record[0]['spent_time'] : '' ?></span>
                        </div>
                        <?php
                    } else {
                        ?>
                        <?php echo ($edit_record[0]['spent_time'] != '') ? $edit_record[0]['spent_time'] : '' ?>
                    <?php } ?>

                </div>
            </div>
            <!--            <div class="form-group row text-center">
                              <div class="col-sm-12">
            <?php if (isset($edit_record)) { ?>
                <?php if ($edit_record[0]['timer_start_flag'] == 1 && $edit_record[0]['timer_end_flag'] == 0) { ?>
                                                                            <a href="javascript:;" class="btn btn-default hidden counterbtn"><div id="countdown2" class="hidden font-2em"></div></a>
                    <?php
                }
            }
            ?>
                                </div>
                        </div>-->


        </div>

        <div class="modal-footer">

        </div>

    </div>
</div>
</div><!-- /.modal-dialog -->
<!-- Timer when start -->
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

            $('#countdownTotal').html(hour+':'+minute+':'+second);
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
<!-- Timer when pause -->
<script type="text/javascript">
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
                //console.log(hour+':'+minute+':'+second);
                $('#countdownTotal').html(hour+':'+minute+':'+second);
        function calculation(elm)
        {
            
            $('#resume_timer').val('1');
            var push = $('#paush_timer').val();
            
            var intervalTimer = '';
            var timeStart = '';
            var timeEnd1 = '';
            var timePause ='';
            var hourDiff1 = '';

            var secDiff1 = '';
            var minDiff1 = '';
            var hDiff1 = '';

            var spentHrs ='<?php echo $edit_record[0]["spent_time"]; ?>';
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
            //console.log('timePause'+timePause);
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
                return clock=new Date("<?php echo date("F d, Y G:i:s", $timer_updatestart_timestamp); ?>").addTime(Math.round(humanReadable1.hours), Math.round(humanReadable1.minutes));
            <?php } else { ?>
                 return clock=new Date().subTime(Math.round(humanReadable1.hours), Math.round(humanReadable1.minutes));
            <?php } ?>
            
            
        }
        function startposeTimer(elm)
        {
            $(elm).val('Pause Timer');
            $(elm).attr('onclick','posetimer(this)');
            $('#hours_diff').val('<?=strtotime(datetimeformat())?>');
            var clock= calculation(elm);
            
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
                $('#countdownTotal').html(hour+':'+minute+':'+second);
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
</script>