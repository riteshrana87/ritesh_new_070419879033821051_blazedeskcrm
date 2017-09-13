<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = 'SaveTeamData';
if (isset($editTeam)) {
    $formAction = 'editSupportTeam';
}
$formPath = $project_view . '/' . $formAction;
$imgUrl = base_url('uploads/images/mark-johnson.png');
//echo $formAction;
?>    
<?php if (!isset($editTeam)) { ?>
    <div class="modal-dialog"  id="teamReplacer">
        <?php
        if (isset($id)) {
            $validateUrl = base_url('SupportTeam/teamNameValidate/' . $id);
        } else {
            $validateUrl = base_url('SupportTeam/teamNameValidate/0');
        }
        ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><b><?php echo lang('add_team'); ?></b> </h4>
            </div>
            <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>

                <div class="modal-body">
                    <div class="row">

                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
    <!--                                data-parsley-remote="<?php echo $validateUrl; ?>"  data-parsley-trim-value="true"
                                       data-parsley-trigger="change"
                                       data-parsley-remote-options='{ "type": "POST", "dataType": "json" }'-->
                                <input type="text" maxlength="80" onchange="validateTeam(this.value);"  class="form-control" placeholder="<?php echo lang('team_name') ?> *" id="team_name" name="team_name" value="<?php echo (isset($team_name)) ? $team_name : ''; ?>" required="">
                            </div>
                            <ul class="parsley-errors-list filled hidden" id="premote"><li class="parsley-remote"><?php echo lang('team_name_error'); ?></li></ul>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
							
                                <select tabindex="-1" id="team_id" onchange="displayTeamById(this.value)"  name="team_id" class="chosen-select" data-placeholder="<?php echo lang('select_team_template'); ?>" >
                                    <option value="">
										
									<?php  if($formAction!='editSupportTeam'){ echo lang('select_team_template'); }else{echo lang('checkout');}?>
									</option>
                                    <?php
                                    if (!empty($team_list)) {
                                        foreach ($team_list as $row) {
                                            ?>
                                            <option  value="<?php echo $row['team_id']; ?>"  <?php echo (isset($id) && $id == $row['team_id']) ? 'selected' : ''; ?> ><?php echo ucfirst($row['team_name']); ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>         
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="clr"></div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <select tabindex="-1" id="team_lead_id"  name="team_lead_id" class="chosen-select" data-placeholder="Choose a Task">
                                    <option value=""><?php echo lang('select_team_leader'); ?></option>
                                    <?php
                                    if (!empty($team_leader)) {
                                        foreach ($team_leader as $row) {
                                            ?>
                                            <option id="<?php echo $row['login_id'] ?>"  <?php echo (isset($leadId) && $leadId == $row['login_id'] ) ? 'selected' : ''; ?> value="<?php echo $row['login_id'] ?>" data-name="<?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?> " data-role="<?php echo str_replace('_', ' ', $row['role_name']); ?>" data-img="<?php echo ($row['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $row['profile_photo'])) : $imgUrl; ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>

                    <div class = "teamLeader">
                        <?php if (isset($teamLeader) && !empty($teamLeader)) {
                            ?>
                            <div class="row" id='team_member<?php echo $teamLeader[0]['member_id']; ?>'>
                                <div class="col-xs-12 col-md-2 col-md-offset-2">
                                    <div class="text-center pad-tb6"><img src="<?php echo ($teamLeader[0]['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $teamLeader[0]['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                </div>
                                <div class="col-xs-6 col-md-4 pad-tb20">
                                    <span class="font-15em"><b><?php echo $teamLeader[0]['full_name']; ?></b></span>			
                                </div>
                                <div class="col-xs-6 col-md-4 pad-tb24">
                                    <span class="font-1em"><?php echo $teamLeader[0]['role_name']; ?></span>		
                                </div>
                                <div class="col-xs-12 col-md-1 pad-tb24 textr-right">
                                    <input type='hidden' name='team_members[]' value="<?php echo $teamLeader[0]['member_id']; ?>">
                                    <input type='hidden' name='team_leader_id' value="<?php echo $teamLeader[0]['member_id']; ?>">
                                </div>
                                <div class="clr"></div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="clr grayline-1 "></div>
                    <div class="theteam <?php echo (isset($teamData) && count($teamData) > 3) ? 'scrollDiv' : ''; ?>">

                        <?php
                        if (isset($teamData) && !empty($teamData)) {

                            foreach ($teamData as $team) {
                                ?>
                                <div class="row"  id='team_member<?php echo $team['member_id']; ?>'>
                                    <div class="col-xs-12 col-md-2">
                                        <div class="text-center pad-tb6"><img src="<?php echo ($team['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $team['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                    </div>
                                    <div class="col-xs-6 col-md-4 pad-tb20">
                                        <span class="font-15em"><b><?php echo $team['full_name']; ?></b></span>	
                                          	
                                    </div>
                                    <div class="col-xs-6 col-md-4 pad-tb24">
                                        <span class="font-1em"><?php echo $team['role_name']; ?></span>
                                    </div>
                                  
                                    <div class="col-xs-12 col-md-1 pad-tb24 textr-right">
                                        <input type='hidden' name='team_members[]' value="<?php echo $team['member_id']; ?>">
                                        <a href='javascript:;' class='removeTeamMembers' data-id='team_member<?php echo $team['member_id']; ?>'><i class='fa fa-remove redcol'></i></a>

                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="clr grayline-1 team_member<?php echo $team['member_id']; ?>"></div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12 pad-tb12">
                            

                            <div class="text-center font-15em"><u><b><?php echo lang('add_team_members');?></b></u></div>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">

                            <select tabindex="-1" id="team_member_id"  name="team_member_id" class="chosen-selectmember" >
                                <option value=""><?php echo lang('select_team_member'); ?> *</option>
                                <?php
                                if (!empty($team_members)) {
                                    foreach ($team_members as $row) {
                                        ?>
                                        <option id="<?php echo $row['login_id'] ?>" value="<?php echo $row['login_id'] ?>" data-img="<?php echo ($row['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $row['profile_photo'])) : $imgUrl; ?>" data-name="<?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?> " data-role="<?php echo str_replace('_', ' ', $row['role_name']); ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname']; ?><?php echo '(' . str_replace('_', ' ', $row['role_name']) . ')'; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
							<input type="text" style="display:none" class="team_member_cnt" data-parsley-required-message="Please select atleast one member"   name="team_member_cnt" id="team_member_cnt" required />
                            <ul class="parsley-errors-list filled hidden" id="team_err" ><li class="parsley-required">This value is required.</li></ul>
                        </div>
                    </div>
                    <div class="clr"></div><br/>
                    <div class="row">
                        <div class="col-xs-12 col-md-5">
                            <div class="form-group">
                               <div class="pull-left"> <input  data-toggle="toggle" data-onstyle="success" type="checkbox" id="notify_members" name="notify_members" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"
></div>
                               <div class="col-sm-6"> <label  for="within_project">
                                    <?php echo lang('notify_team_members'); ?>
                                </label></div>
                                <!--                        <div class="btn-group btn-toggle">
                                                            <button class="btn btn-xs btn-default">ON</button>
                                                            <button class="btn btn-xs btn-primary active">OFF</button>
                                                        </div>-->
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4 text-left">
                            <div class="form-group">
                                <a class="btn btn-green" href="javascript:;"  onClick="$('#schedule_meeting').toggleClass('hidden', 'show');"><i class="fa fa-clock-o"></i> Schedule Meeting</a>

                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 text-right">
                            <div class="form-group">
                                <input type="text" class="form-control hidden " onkeypress="return false;" placeholder="<?php echo lang('due_date') ?>" id="schedule_meeting" name="schedule_meeting">
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"> </div>
                    <!--<div class="row">
                        <div class="col-xs-12 col-md-5">
                            <div class="form-group">
                                <div class="pad_top20">
                                    <input <?php if (!empty($edit_record[0]['remember'])) { ?>checked="checked"<?php } ?> data-toggle="toggle" data-onstyle="success" type="checkbox"  id="reminder" name="reminder" onChange="$('#reminderBox').toggleClass('hidden', 'show');"/>
                                    <label>
                                        <?= $this->lang->line('reminder?') ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="clr"></div>
                    </div>-->
                    <div class="row">
                        <div id="reminderBox" class="hidden">
                            <div >
                                <div class ="row">
                                    <div class = "col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class = "col-xs-12 col-sm-6 form-group">
                                                <select name="before_after" class="form-control chosen-select" id="before_after">
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
                                            </div>
                                            <div class = "col-xs-12 col-sm-6 form-group">
                                                <select name="remind_time" class="form-control chosen-select" id="remind_time">
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
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                    <div class = "col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class = "col-xs-12 col-sm-6 form-group">
                                                <select name="repeat" class="form-control chosen-select" id="repeat">
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
                                            </div>
                                            <div class = "col-xs-12 col-sm-6 form-group">
                                                <select name="remind_day" class="form-control chosen-select" id="remind_day">
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
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clr"> </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?php echo lang('add_team'); ?>" />
                            <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" />
                        </div>
                        <div class="clr"> </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } else { ?>
    <?php
    if (isset($id)) {
        $validateUrl = base_url('SupportTeam/teamNameValidate/' . $id);
    } else {
        $validateUrl = base_url('SupportTeam/teamNameValidate/0');
    }
    ?>
    <div class="modal-dialog"  id="teamReplacer">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><b><?php echo lang('edit_team'); ?></b> </h4>
            </div>
            <form id="from-model" method="post" enctype="multipart/form-data" action="<?php echo base_url($formPath); ?>" data-parsley-validate>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
								
                                <input type="text" maxlength="80"  type="text" maxlength="80"  onchange="validateTeam(this.value);" class="form-control" placeholder="<?php echo lang('team_name') ?>" id="team_name" name="team_name" value="<?php echo (isset($team_details)) ? $team_details[0]['team_name'] : ''; ?>" required="">
                            </div>
                            <ul class="parsley-errors-list filled hidden" id="premote"><li class="parsley-remote"><?php echo lang('team_name_error'); ?></li></ul>

                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group">
                                <select tabindex="-1" data-parsley-errors-container="#team-errors"  <?php echo (isset($editTeam)) ? 'required' : ''; ?> id="team_id" onchange="displayTeamById(this.value,<?php echo (isset($editTeam)) ? 1 : 0; ?>);"  name="team_id" class="chosen-select" data-placeholder="<?php echo lang('select_team_template'); ?>" >
                                    <option value=""><?php echo lang('select_team'); ?></option>
                                    <?php
                                    if (!empty($team_list)) {
                                        foreach ($team_list as $row) {
                                            ?>
                                            <option  value="<?php echo $row['team_id'] ?>" <?php echo (isset($id) && $id == $row['team_id']) ? 'selected' : ''; ?>><?php echo ucfirst($row['team_name']); ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>         
                                <span id="team-errors"></span>
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>

                    <div class="clr"></div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <select tabindex="-1" id="team_lead_id"  name="team_lead_id" class="chosen-select" data-placeholder="Choose a Task">
                                    <option value=""><?php echo lang('select_team_leader'); ?></option>
                                    <?php
                                    if (!empty($team_leader)) {
                                        foreach ($team_leader as $row) {
                                            ?>
                                            <option id="<?php echo $row['login_id'] ?>"  <?php echo (isset($leadId) && $leadId == $row['login_id']) ? 'selected' : ''; ?> value="<?php echo $row['login_id'] ?>" data-name="<?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?> " data-role="<?php echo str_replace('_', ' ', $row['role_name']); ?>" data-img="<?php echo ($row['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $row['profile_photo'])) : $imgUrl; ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>

                    <div class = "teamLeader">
                        <?php if (isset($teamLeader) && !empty($teamLeader) && ($teamLeader[0]['team_lead_id']!=0)) {
							//pr($teamLeader);die();
                            ?>
                            <div class="row" id='team_member<?php echo $teamLeader[0]['member_id']; ?>'>
                                <div class="col-xs-12 col-md-2 col-md-offset-2">
                                    <div class="text-center pad-tb6"><img src="<?php echo ($teamLeader[0]['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $teamLeader[0]['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                </div>
                                <div class="col-xs-6 col-md-4 pad-tb20">
                                    <span class="font-15em"><b><?php echo $teamLeader[0]['full_name']; ?></b></span>			
                                </div>
                                <div class="col-xs-6 col-md-4 pad-tb24">
                                    <span class="font-1em"><?php echo $teamLeader[0]['role_name']; ?></span>			
                                </div>
                                <div class="col-xs-12 col-md-1 pad-tb24 textr-right">
        <!--                                    <input type='hidden' name='team_members[]' value="<?php echo $teamLeader[0]['member_id']; ?>">-->
                                    <input type='hidden' name='team_leader_id' value="<?php echo $teamLeader[0]['member_id']; ?>">
                                </div>
                                <div class="clr"></div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="clr grayline-1"></div>
                    <div class="theteam">

                        <?php
                        if (isset($teamData) && !empty($teamData)) {

                            foreach ($teamData as $team) {
								//pr($team);die();
                                ?>
                                <div class="row"  id='team_member<?php echo $team['member_id']; ?>'>
                                    <div class="col-xs-12 col-md-2">
                                        <div class="text-center pad-tb6"><img src="<?php echo ($team['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $team['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                    </div>
                                    <div class="col-xs-6 col-md-4 pad-tb20">
                                        <span class="font-15em"><b><?php echo $team['full_name']; ?></b></span>	
                                        	
                                    </div>
                                    <div class="col-xs-6 col-md-4 pad-tb24">
                                         <span class="font-1em"><?php echo $team['role_name']; ?></span>	
                                    </div>
                      
                                    <div class="col-xs-12 col-md-1 pad-tb24 textr-right">
                                        <input type='hidden' name='team_members[]' value="<?php echo $team['member_id']; ?>">
                                        <a href='javascript:;' class='removeTeamMembers' data-id='team_member<?php echo $team['member_id']; ?>'><i class='fa fa-remove redcol'></i></a>

                                    </div>
                                    <div class="clr"></div>
                                </div>
                                <div class="clr grayline-1 team_member<?php echo $team['member_id']; ?>"></div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12 pad-tb12">
                            <input type="text" style="display:none" class="team_member_cnt"  data-parsley-required-message="Please select atleast one member"  name="team_member_cnt" id="team_member_cnt" required />
                            <div class="text-center font-15em"><u><b><?php echo lang('add_team_members');?></b></u></div>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <select tabindex="-1" id="team_member_id"  name="team_member_id" class="chosen-selectmember" >
                                <option value=""><?php echo lang('select_team_member'); ?></option>
                                <?php
                                if (!empty($team_members)) {
                                    foreach ($team_members as $row) {
                                        ?>
                                        <option id="<?php echo $row['login_id'] ?>" value="<?php echo $row['login_id'] ?>" data-img="<?php echo ($row['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $row['profile_photo'])) : $imgUrl; ?>" data-name="<?php echo ucfirst($row['firstname']) . ' ' . $row['lastname'] ?> " data-role="<?php echo str_replace('_', ' ', $row['role_name']); ?>"><?php echo ucfirst($row['firstname']) . ' ' . $row['lastname']; ?><?php echo '(' . str_replace('_', ' ', $row['role_name']) . ')'; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="clr"></div><br/>
                    <div class="row">
                        <div class="col-xs-12 col-md-5">
                            <div class="form-group">
                                <div class="pull-left"><input  data-toggle="toggle" data-onstyle="success" type="checkbox" id="notify_members" name="notify_members" value="1" data-on="<?php echo lang('on'); ?>" data-off="<?php echo lang('off'); ?>"
></div>
                                <div class="col-sm-6"><label  for="within_project">
                                    <?php echo lang('notify_team_members'); ?>
                                </label></div>
                                <!--                        <div class="btn-group btn-toggle">
                                                            <button class="btn btn-xs btn-default">ON</button>
                                                            <button class="btn btn-xs btn-primary active">OFF</button>
                                                        </div>-->
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4 text-left">
                            <div class="form-group">
                                <a class="btn btn-green" href="javascript:;"  onClick="$('#schedule_meeting').toggleClass('hidden', 'show');"><i class="fa fa-clock-o"></i> Schedule Meeting</a>

                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3 text-right">
                            <div class="form-group">
                                <input type="text" class="form-control hidden " onkeypress="return false;" placeholder="<?php echo lang('due_date') ?>" id="schedule_meeting" name="schedule_meeting">
                            </div>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div class="clr"> </div>
                    <!--<div class="row">
                        <div class="col-xs-12 col-md-5">
                            <div class="form-group">
                                <div class="pad_top20">
                                    <input <?php if (!empty($edit_record[0]['remember'])) { ?>checked="checked"<?php } ?> data-toggle="toggle" data-onstyle="success" type="checkbox"  id="reminder" name="reminder" onChange="$('#reminderBox').toggleClass('hidden', 'show');"/>
                                    <label>
                                        <?= $this->lang->line('reminder?') ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="clr"></div>
                    </div>-->
                    <div class="row">
                        <div id="reminderBox" class="hidden">
                            <div >
                                <div class ="row">
                                    <div class = "col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class = "col-xs-12 col-sm-6 form-group">
                                                <select name="before_after" class="form-control chosen-select" id="before_after">
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
                                            </div>
                                            <div class = "col-xs-12 col-sm-6 form-group">
                                                <select name="remind_time" class="form-control chosen-select" id="remind_time">
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
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                    <div class = "col-xs-12 col-sm-6">
                                        <div class="row">
                                            <div class = "col-xs-12 col-sm-6 form-group">
                                                <select name="repeat" class="form-control chosen-select" id="repeat">
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
                                            </div>
                                            <div class = "col-xs-12 col-sm-6 form-group">
                                                <select name="remind_day" class="form-control chosen-select" id="remind_day">
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
                                            </div>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clr"> </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?php echo lang('edit_team'); ?>" />
                            <input type="button" onclick="removeTeam();"  class="btn btn-danger remove_btn hidden" name="remove" id="remove_btn" value="<?php echo lang('remove_team'); ?>" />
                        </div>
                        <div class="clr"> </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>
<script>

    $(document).ready(function () {
        $('.chosen-select').chosen();
        $('#from-model').parsley();
//disabled after submit
        $('body').delegate('#submit_btn', 'click', function () {

            if ($('#from-model').parsley().isValid()) {

                $('input[type="submit"]').prop('disabled', true);
                $('#from-model').submit();
            }

        });
        $('.chosen-selectmember').chosen({'display_selected_options': false});
        $('.chosen-results li:contains("Swatch 1")').hide();
        $('#notify_members').bootstrapToggle();
        $('#reminder').bootstrapToggle();
        $('.chosen-select-deselect').chosen({allow_single_deselect: true});
    });
    $(function () {
		var currentdate = new Date();
        $('#schedule_meeting').datetimepicker({minDate : currentdate});
    });
    function validateTeam(name)
    {
        if (name != '')
        {
            $.ajax({
                url: "<?php echo $validateUrl; ?>",
                data: {'team_name': name},
                type: "POST",
                dataType: "json",
                success: function (d)
                {
                    if (d.status == 1)
                    {
						
                        $('#team_name').val('');
                        $('#team_name').addClass('parsley-error');
                        $('#premote').removeClass('hidden');
                    }
                    else
                    {
						
                        $('#team_name').removeClass('parsley-error')
                        $('#premote').addClass('hidden');
                    }
                }
            });
        }

    }

</script>
