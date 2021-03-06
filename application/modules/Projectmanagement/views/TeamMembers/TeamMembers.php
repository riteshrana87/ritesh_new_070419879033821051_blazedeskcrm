<?php $imgUrl = base_url('uploads/images/mark-johnson.png'); ?>

<div id="main-page" >
    <?php echo $this->session->flashdata('msg'); ?>
    <div class="row" >
        <!-- Search: End -->
    </div>
    <div class="whitebox pad-10" >
        <?php if (count($project_manager) > 0) { ?>
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 roundimg">
                    <div class="row">
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="text-center pad_top20"><img src="<?php echo ($project_manager[0]['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $project_manager[0]['profile_photo'])) : $imgUrl; ?>" alt=""/></div>
                        </div>
                        <div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 pad-top10"><span class="font-2em"><b><?php echo $project_manager[0]['full_name']; ?></b></span> <br/>
                            <span class="font-1em"><?php echo str_replace('_', ' ', $project_manager[0]['role_name']); ?></span> </div>
                        <div class="clr"></div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <div class="pad_top20">
                                <div class="square-label"><?php echo str_replace('_', ' ', $project_manager[0]['role_name']); ?></div></div>
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                            <div class="pad_top25 black-link">
                                <?php if ($project_manager[0]['telephone1'] != '') { ?>
                                    <i class="fa fa-phone"></i>
                                    +<?php echo $project_manager[0]['telephone1']; ?>
                                <?php } ?>
                            </div>		
                        </div>
                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">  <div class="pad_top25 black-link"> 
                                <?php if ($project_manager[0]['telephone2'] != '') { ?>
                                    <i class="fa fa-phone"></i> +<?php echo $project_manager[0]['telephone2']; ?>
                                <?php } else { ?>
                                    <p>
                                    </p>
                                <?php } ?>
                            </div></div>
                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3  form-group"><div class="pad_top25 black-link"><i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo $project_manager[0]['email']; ?>"><?php echo $project_manager[0]['email']; ?></a> </div></div>
                        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1"> 
                            <div class="round-button"><div class="round-button-circle"><a href="<?php echo base_url($project_view . '/getTaskByUser/' . $project_manager[0]['member_id']); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true" class="round-button"> <?php echo $project_manager[0]['count_task']; ?></a></div></div>
                            <div class="text-center clr">
                                <?php echo lang('projecttask'); ?></div>
                        </div> 
                        <div class="clr"></div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <h4 colspan="4"><?php echo lang('please_assign_project_manager'); ?></h4>
        <?php } ?>
        <?php if (count($teams) > 0) { ?>
            <div id="teamMaster">
                <?php foreach ($teams as $team) { ?>
                    <div id="teamM_<?php echo $team['team_id']; ?>">
                        <div class="white-border-box2" >

                            <div class="col-md-11 white-border-title col-xs-10">
                                <?php echo $team['team_name']; ?>  
                            </div>
                            <div class="col-md-1 text-right pad-10 pad-b6 col-xs-2">
                                <a id="teamarr_<?php echo $team['team_id']; ?>" href="javascript:;" data-toggle="collapse" data-parent="#teamMaster" onclick="$('#teamarr_<?php echo $team['team_id']; ?> i').toggleClass('fa-arrow-down fa-arrow-up');" data-target="#team_<?php echo $team['team_id']; ?>"><i class="fa fa-arrow-up fa-2x"></i></a>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div id="team_<?php echo $team['team_id']; ?>" class="collapse in" >
                            <?php $members = $this->TeamMembers_model->getTeamMemberList($project_id, $team['team_id']); ?>
                            <?php
                            $teamLead = $this->TeamMembers_model->getTeamLeaderbyId($project_id, $team['team_id']);
                            //echo $this->db->last_query();
                            ?>
                            <?php if (count($teamLead) > 0) { ?>
                                <?php foreach ($teamLead as $tl) { ?>
                                    <?php if ($tl['full_name'] != '') { ?>
                                        <div class="white-border-box">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="row">
                                                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                                            <div class="text-center pad-tb6"><img src="<?php echo ($t1['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $t1['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                                        </div>
                                                        <div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 pad-tb6"><span class="font-2em"><b><?php echo $tl['full_name']; ?></b></span> <br/>
                                                            <span class="font-1em">
                                                                <?php echo str_replace('_', ' ', $tl['role_name']); ?></span> </div>
                                                        <div class="clr"></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                            <div class="pad_top20">
                                                                <div class="square-label">  <?php echo str_replace('_', ' ', $tl['role_name']); ?></div></div>
                                                        </div>
                                                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
                                                            <div class="pad_top25 black-link">
                                                                <?php if ($tl['telephone1'] != '') { ?>
                                                                    <i class="fa fa-phone"></i>
                                                                    <?php echo $tl['telephone1']; ?>
                                                                <?php } ?>
                                                            </div>		
                                                        </div>
                                                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">  <div class="pad_top25 black-link">
                                                                <?php if ($tl['telephone1'] != '') { ?>
                                                                    <i class="fa fa-phone"></i><?php echo $tl['telephone2']; ?>
                                                                <?php } ?>
                                                            </div></div>
                                                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-3"><div class="pad_top25 black-link"> <a href="mailto: <?php echo $tl['email']; ?>"> <i class="fa fa-envelope-o"></i> <?php echo $tl['email']; ?></a> </div></div>
                                                        <div class="col-xs-6 col-sm-1 col-md-1 col-lg-1"> 

                                                            <div class="round-button"><div class="round-button-circle"><a href="<?php echo base_url($project_view . '/getTaskByUser/' . $tl['member_id']); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true" class="round-button"> <?php echo $tl['count_task']; ?></a></div></div>
                                                            <div class="text-center clr"> <?php echo lang('projecttask'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="clr"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    <?php } else { ?>
                                        <div class="white-border-box">
                                            <h5 colspan="4"><?php echo lang('no_team_members_found'); ?></h5>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                            <?php } ?>
                            <?php if (count($members) > 0) { ?>
                                <?php foreach ($members as $member) { ?>
                                    <?php if ($member['full_name'] != '') { ?>
                                        <div class="white-border-box" id="member_<?php echo $member['member_id']; ?>">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="row">
                                                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                                            <div class="text-center pad-tb6"><img src="<?php echo ($member['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $member['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                                        </div>
                                                        <div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 pad-tb6"><span class="font-2em"><b><?php echo $member['full_name']; ?></b></span> <br/>
                                                            <span class="font-1em">
                                                                <?php echo $member['role_name']; ?></span> </div>
                                                        <div class="clr"></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                            <div class="pad_top20">
                                                                <div class="square-label"> <?php echo $member['role_name']; ?></div></div>
                                                        </div>
                                                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
                                                            <div class="pad_top25 black-link">
                                                                <?php if ($member['telephone1'] != '') { ?>

                                                                    <i class="fa fa-phone"></i>
                                                                    <?php echo $member['telephone1']; ?>
                                                                <?php } ?>
                                                            </div>		
                                                        </div>
                                                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">  <div class="pad_top25 black-link">
                                                                <?php if ($member['telephone2'] != '') { ?>
                                                                    <i class="fa fa-phone"></i><?php echo $member['telephone2']; ?>
                                                                <?php } ?>
                                                            </div></div>
                                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3"><div class="pad_top25 black-link"> <a href="mailto: <?php echo $member['email']; ?>"> <i class="fa fa-envelope-o"></i> <?php echo $member['email']; ?></a> </div></div>
                                                        <div class="col-xs-6 col-sm-1 col-md-1 col-lg-1"> 

                                                            <div class="round-button"><div class="round-button-circle"><a href="<?php echo base_url($project_view . '/getTaskByUser/' . $member['member_id']); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true" class="round-button"> <?php echo $member['count_task']; ?></a></div></div>
                                                            <div class="text-center clr"> <?php echo lang('projecttask'); ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6 col-sm-1 col-md-1 col-lg-1 pad_top20 editTeamAction hidden"> 
                                                            <a href='javascript:;' onclick="removeMember(<?php echo $member['member_id']; ?>,<?php echo $team['team_id']; ?>, 'member_<?php echo $member['member_id']; ?>');" data-id='team_member<?php echo $member['member_id']; ?>' title="remove member?"><i class='fa fa-remove redcol'></i></a>
                                                        </div>
                                                        <div class="clr"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="white-border-box">
                                            <h5 colspan="4"><?php echo lang('no_team_members_found'); ?></h5>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>

                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php
        $members = array();
        $members = $this->TeamMembers_model->getUnsortedMembers($project_id);
        //  pr($members);
        if (count($members) > 0) {
            ?>
            <div id="teamMaster">
                <div id="teamM_0">
                    <div class="white-border-box2" >

                        <div class="col-md-11 white-border-title">
                            <?php echo lang('Unclassified'); ?>
                        </div>
                        <div class="col-md-1 text-right pad-10 pad-b6" >
                            <a id="teamarr_0" href="javascript:;" data-toggle="collapse" data-parent="#teamMaster" onclick="$('#teamarr_0 i').toggleClass('fa-arrow-down fa-arrow-up');" data-target="#team_0"><i class="fa fa-arrow-up fa-2x"></i></a>
                        </div>
                        <div class="clr"></div>
                    </div>
                    <div id="team_0" class="collapse in" >

                        <?php if (count($members) > 0) { ?>
                            <?php foreach ($members as $member) { ?>
                                <?php if ($member['full_name'] != '') { ?>
                                    <div class="white-border-box" id="member_<?php echo $member['member_id']; ?>">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="row">
                                                    <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                                        <div class="text-center pad-tb6"><img src="<?php echo ($member['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $member['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                                    </div>
                                                    <div class="col-xs-6 col-sm-8 col-md-8 col-lg-8 pad-tb6"><span class="font-2em"><b><?php echo $member['full_name']; ?></b></span> <br/>
                                                        <span class="font-1em">
                                                            <?php echo $member['role_name']; ?></span> </div>
                                                    <div class="clr"></div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                                        <div class="pad_top20">
                                                            <div class="square-label"> <?php echo $member['role_name']; ?></div></div>
                                                    </div>
                                                    <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
                                                        <div class="pad_top25 black-link">
                                                            <?php if ($member['telephone1'] != '') { ?>

                                                                <i class="fa fa-phone"></i>
                                                                <?php echo $member['telephone1']; ?>
                                                            <?php } ?>
                                                        </div>		
                                                    </div>
                                                    <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">  <div class="pad_top25 black-link">
                                                            <?php if ($member['telephone2'] != '') { ?>

                                                                <i class="fa fa-phone"></i>
                                                                <?php echo $member['telephone2']; ?>
                                                            <?php } ?>
                                                        </div></div>
                                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3"><div class="pad_top25 black-link"> <a href="mailto: <?php echo $member['email']; ?>"><i class="fa fa-envelope-o"></i> <?php echo $member['email']; ?></a> </div></div>
                                                    <div class="col-xs-6 col-sm-1 col-md-1 col-lg-1"> 

                                                        <div class="round-button"><div class="round-button-circle"><a href="<?php echo base_url($project_view . '/getTaskByUser/' . $member['member_id']); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true" class="round-button"> <?php echo $member['count_task']; ?></a></div></div>
                                                        <div class="text-center clr"> <?php echo lang('projecttask'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6 col-sm-1 col-md-1 col-lg-1 pad_top20 editTeamAction hidden"> 
                                                        <a href='javascript:;' onclick="removeMember(<?php echo $member['member_id']; ?>, 0, 'member_<?php echo $member['member_id']; ?>');" data-id='team_member<?php echo $member['member_id']; ?>' title="remove member?"><i class='fa fa-remove redcol'></i></a>
                                                    </div>
                                                    <div class="clr"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="white-border-box">
                                        <h5 colspan="4"><?php echo lang('no_team_members_found'); ?></h5>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>

                    </div>
                </div>
            </div>
        <?php } ?>


    </div>

    <div class="clr"></div>
</div>
<script>

    $(document).ready(function () {
        var currentdate = new Date();
        $('#schedule_meeting').datetimepicker({minDate: currentdate});
        $("body").delegate('#team_lead_id', 'change', function () {
            if ($(this).val() != '' && $(this).val() != null)
            {
                var id = $('#team_lead_id option:selected').attr('id');
                var name = $('#team_lead_id option:selected').attr('data-name');
                var role = $('#team_lead_id option:selected').attr('data-role');
                var img = $('#team_lead_id option:selected').attr('data-img');
                var html = "<div class='row' id='team_member' ><div class='col-xs-12 col-md-2 col-md-offset-2'><div class='text-center pad-tb6'><img src='" + img + "' alt=''/></div></div><div class='col-xs-6 col-md-4 pad-tb20'><span class='font-15em'><b>" + name + "</b></span></div><div class='col-xs-6 col-md-4 pad-tb24'><span class='font-1em'>" + role + "</span></div><div class='clr'></div></div>";
                // var html = "<div><img src='' height='50' width='50'><h3>" + name + "</h3><h4>" + role + "</h4><input type='hidden' name='team_members[]' value=" + id + "></div>"
                $('.teamLeader').html(html);
            }
            else
            {
                $('.teamLeader').empty();
            }
        });
        $("body").delegate('#team_member_id', 'change', function () {
            var id = $(this).val();

            if ($(this).val() != '' && $(this).val() != null && $('#team_member' + id).length == 0)
            {
                var id = $('#team_member_id option:selected').attr('id');
                var name = $('#team_member_id option:selected').attr('data-name');
                var role = $('#team_member_id option:selected').attr('data-role');
                var img = $('#team_member_id option:selected').attr('data-img');
                var html = "<div class='row' id='team_member" + id + "'><div class='col-xs-4 col-md-3'><div class='text-center pad-tb6'><img src='" + img + "' alt=''/></div></div><input type='hidden' name='team_members[]' value=" + id + "><div class='col-xs-7 col-md-4 pad-tb20'><span class='font-15em'><b>" + name + "</b></span></div><div class='col-xs-6 col-md-4 pad-tb24'><span class='font-1em'>" + role + "</span></div><div class='bd-error-control'><div class='bd-error pad-tb15'><a href='javascript:;' title='<?php echo lang('delete'); ?>' class='removeTeamMembers' data-id='team_member" + id + "'><i class='fa fa-remove redcol'></i></a></div></div></div><div class='clr grayline-1 team_member" + id + "'></div>";
                //  var html = "<div id='team_member" + id + "'><img src='<?php echo $imgUrl; ?>' height='50' width='50'><h3>" + name + "</h3><h4>" + role + "</h4><input type='hidden' name='team_members[]' value=" + id + "><a href='javascript:;' class='removeTeamMembers' data-id='team_member" + id + "'><i class='fa fa-remove redcol'></i></a></div>";
                $('.theteam').append(html);
                $('option:selected', this).prop('disabled', true);
                //    $('option:selected', this).attr('disabled', 'disabled');
                $(".chosen-selectmember").val('').trigger("chosen:updated");
                $('#team_member_id_chosen .result-selected').addClass('hidden');
                if ($("input[name='team_members[]']").length > 3)
                {
                    $('.theteam').addClass('scrollDiv');
                }
                else
                {
                    $('.theteam').removeClass('scrollDiv');
                }

                if ($("input[name='team_members[]']").length > 0)
                {
                    $('#parsley-id-14 li').remove();
                    $('#team_member_cnt').val(1);
                }
                else
                {

                    $('#team_member_cnt').val('');
                }

            }

        });
        $("body").delegate('.removeTeamMembers', 'click', function () {

            var id = $(this).attr('data-id');
            var optId = id.match(/\d+/)[0];
            // $("#team_member_id").remove(id);
            console.log(optId);
            //  $("#team_member_id option[value="+id+"]").remove();
            $("#team_member_id option[value=" + optId + "]").removeAttr('disabled');
            $('#' + id).remove();
            $('.' + id).remove();
            //$('select #' + id).prop("disabled", false);
            // $('option:selected', this).attr('disabled', 'disabled');
            $(".chosen-selectmember").val('').trigger("chosen:updated");
            $('#team_member_id_chosen .result-selected').addClass('hidden');
            if ($("input[name='team_members[]']").length > 3)
            {
                $('.theteam').addClass('scrollDiv');
            }
            else
            {
                $('.theteam').removeClass('scrollDiv');
            }

            if ($("input[name='team_members[]']").length > 0)
            {
                $('#team_member_cnt').val(1);
            }
            else
            {
                $('#team_member_cnt').val('');
            }
        });
    });
</script>
<script>
    function displayTeamById(id, flag = '0')
    {
        var team_name = $('#team_name').val();
        if (id != '' && id != null) {
            $.ajax({
                url: "<?php echo base_url($project_view . '/getTeamListbyId/'); ?>/" + id + '?flag=' + flag + '&team_name=' + team_name,
                type: "get",
                success: function (data)
                {
                    var currentdate = new Date();
                    $('#schedule_meeting').datetimepicker({minDate: currentdate});
                    $('#ajaxModal').html(data);
                    $('#remove_btn').toggleClass('hidden', 'show');
                    console.log($("input[name='team_members[]']").length);
                    if ($("input[name='team_members[]']").length > 3)
                    {
                        $('.theteam').addClass('scrollDiv');
                    }
                    else
                    {
                        $('.theteam').removeClass('scrollDiv');
                    }

                    if ($("input[name='team_members[]']").length > 0)
                    {

                        $('#parsley-id-182 li').remove();
                        $('#team_member_cnt').val(1);
                    }
                    else
                    {

                        $('#team_member_cnt').val('');
                    }
                    //  $('#team_member_cnt').val(1);
                }
            });
        }
        else {
            $('.teamLeader').empty();
            $('.theteam').empty();
             $('#team_member_cnt').val('');
            $('#team_lead_id').val('');
        }
    }

</script>
<script>
    function displayTeamByIdForMember(id)
    {
        if (id != '' && id != null) {
            $.ajax({
                url: "<?php echo base_url($project_view . '/addTeamMembers/'); ?>/",
                type: "POST",
                data: {id: id},
                success: function (data)
                {
                    var currentdate = new Date();
                    $('#schedule_meeting').datetimepicker({minDate: currentdate});
                    $('#ajaxModal').html(data);
                }
            });
        }
    }
    //    function editTeam()
    //    {
    //        $('.editTeamAction').toggleClass('hidden', 'show');
    //    }
    function removeMember(memberId, teamId, id)
    {
        if (memberId != '' && memberId != null) {

            var delete_meg ="<?php echo lang('delete_member'); ?>";
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
                            $.ajax({
                                url: "<?php echo base_url($project_view . '/removeTeamMembers/'); ?>/",
                                type: "POST",
                                data: {memberId: memberId, teamId: teamId},
                                success: function (data)
                                {
                                    if (data.status = '1')
                                    {
                                        $('#' + id).remove();
                                    }
                                }
                            });
                            dialog.close();
                        }
                    }]
                });
            }
    }
    function removeTeam()
    {
        var teamId = $('#team_id').val();
        if (teamId != '' && teamId != null) {
            var delete_meg ="<?php echo lang('delete_team'); ?>";
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
                            $.ajax({
                                url: "<?php echo base_url($project_view . '/removeTeam/'); ?>/",
                                type: "POST",
                                data: {teamId: teamId},
                                success: function (data)
                                {
                                    if (data.status = '1')
                                    {
                                        window.location.href = window.location.href;
                                    }
                                }
                            });
                            dialog.close();
                        }
                    }]
                });
        }

    }
    $('.updown').click(function () {
        $(this).toggleClass("fa-arrow-up fa-arrow-down");
    });
</script>