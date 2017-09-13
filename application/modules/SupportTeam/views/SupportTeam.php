<?php $imgUrl = base_url('uploads/images/mark-johnson.png'); ?>

<div id="main-page" >
 
<div class="row">   <div class="col-lg-6 col-md-6 col-sm-6">
        <?php echo $this->breadcrumbs->show(); ?>
    </div>
    
        <!-- Search: End -->
       
        <div class="col-xs-12 col-sm-8 col-md-6 text-right">
		 <?php if (checkPermission('SupportTeam', 'edit')) { ?>
            <button class="btn btn-white" data-href="<?php echo base_url($project_view . '/editSupportTeam'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('edit_team'); ?></button>
            &nbsp;&nbsp;
		 <?php }?>
            <?php if (checkPermission('SupportTeam', 'add')) { ?>
                <a class="btn btn-white" data-href="<?php echo base_url($project_view . '/addTeam'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('add_team'); ?>                       </a>
				
				<?php } ?>

            &nbsp;&nbsp;
            <?php if (checkPermission('SupportTeam', 'add')) { ?>
                <a class="btn btn-white" data-href="<?php echo base_url($project_view . '/addTeamMembers'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('add_team_member') ?>                       </a>
            <?php } ?>

            <div class="clr"></div>
        </div>
		
    <div class="clr"></div></div>
<?php echo $this->session->flashdata('msg'); ?>
    <div class="whitebox pad-10" >
	 
        <?php if (count($teams) > 0) { ?>
            <div id="teamMaster">
                <?php foreach ($teams as $team) { ?>
                    <div id="teamM_<?php echo $team['team_id']; ?>">
                        <div class="white-border-box2" >

                            <div class="col-md-11 white-border-title">
                                <?php echo $team['team_name']; ?>  
                            </div>
                            <div class="col-md-1 text-right pad-10 pad-b6" >
                                <a href="javascript:;" data-toggle="collapse" data-parent="#teamMaster" data-target="#team_<?php echo $team['team_id']; ?>"><i class="fa fa-arrow-up fa-2x updown"></i></a>
                            </div>
                            <div class="clr"></div>
                        </div>
						
                        <div id="team_<?php echo $team['team_id']; ?>" class="collapse in" >
                            <?php $members = $this->TeamMembers_model->getTeamMemberList($team['team_id']); ?>
                            <?php $teamLead = $this->TeamMembers_model->getTeamLeaderbyId($team['team_id']); ?>
                            <?php if (count($teamLead) > 0) { ?>
                                <?php foreach ($teamLead as $tl) { ?>
                                    <?php if ($tl['full_name'] != '') { ?>
                                        <div class="white-border-box">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="row">
                                                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                            <div class="text-center pad-tb6"><img src="<?php echo ($tl['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $tl['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                                        </div>
                                                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 pad-tb6"><span class="font-2em"><b><?php echo $tl['full_name']; ?></b></span> <br/>
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
                                                            <div class="pad_top25 black-link"><?php if(isset($member['telephone1']) && $member['telephone1']!='') {?><i class="fa fa-phone"></i><?php }?>
                                                                <?php echo $tl['telephone1']; ?>
                                                            </div>		
                                                        </div>
                                                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">  <div class="pad_top25 black-link"><?php if(isset($member['telephone2']) && $member['telephone2']!='') {?><i class="fa fa-phone"></i><?php }?><?php echo $tl['telephone2']; ?></div></div>
                                                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-3"><div class="pad_top25 black-link"> <a href="mailto: <?php echo $tl['email']; ?>"> <?php echo $tl['email']; ?></a> </div></div>

                                                        <div class="clr"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    <?php } else { ?>
                                        <div class="white-border-box">
                                            <h5 colspan="4"><?php echo lang('team_leader_empty_error'); ?></h5>
                                        </div>
                                    <?php } ?>
                                <?php } ?>

                            <?php } ?>
							
                            <?php if (count($members) > 0) { ?>
                                <?php foreach ($members as $member) {
								// pr($member);die('here');
								 ?>
                                    <?php if ($member['full_name'] != '') { ?>
                                        <div class="white-border-box" id="member_<?php echo $member['member_id']; ?>">
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                    <div class="row">
                                                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                            <div class="text-center pad-tb6"><img src="<?php echo ($member['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $member['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                                        </div>
                                                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 pad-tb6"><span class="font-2em"><b><?php echo $member['full_name']; ?></b></span> <br/>
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
                                                            <div class="pad_top25 black-link"><?php if(isset($member['telephone1']) && $member['telephone1']!='') {?><i class="fa fa-phone"></i><?php }?>
                                                                <?php echo $member['telephone1']; ?>
                                                            </div>		
                                                        </div>
														<?php //pr($member)?>
                                                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">  <div class="pad_top25 black-link"><?php if(isset($member['telephone2']) && $member['telephone2']!='') {?><i class="fa fa-phone"></i><?php }?><?php  echo $member['telephone2'];  ?></div></div>
                                                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3"><div class="pad_top25 black-link"> <a href="mailto: <?php echo $member['email']; ?>"> <?php echo $member['email']; ?></a> </div></div>

                                                      
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
			
        <?php } else { ?>
		<?php  $un_members = array();
        $un_members = $this->TeamMembers_model->getUnsortedMembers();
		if(count($un_members)==0){
		?>
		
		<div class="white-border">
                                        <h5 colspan="4" align="center"><?php echo lang('NO_RECORD_FOUND'); ?></h5>
                                    </div>
		<?php } } ?>
        <?php
        $members = array();
        $members = $this->TeamMembers_model->getUnsortedMembers();
		//echo $this->db->last_query();
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
                                                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                                        <div class="text-center pad-tb6"><img src="<?php echo ($member['profile_photo'] != '') ? base_url('uploads/profile_photo/' . str_replace('.', '_thumb.', $member['profile_photo'])) : $imgUrl; ?>" alt=""/> </div>
                                                    </div>
                                                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 pad-tb6"><span class="font-2em"><b><?php echo $member['full_name']; ?></b></span> <br/>
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
                                                        <div class="pad_top25 black-link"><?php if(isset($member['telephone1']) && $member['telephone1']!='') {?><i class="fa fa-phone"></i><?php }?>
                                                            <?php echo $member['telephone1']; ?>
                                                        </div>		
                                                    </div>
                                                    <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">  <div class="pad_top25 black-link"><?php if(isset($member['telephone2']) && $member['telephone2']!='') {?><i class="fa fa-phone"></i><?php }?><?php echo $member['telephone2']; ?></div></div>
                                                    <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3"><div class="pad_top25 black-link"> <a href="mailto: <?php echo $member['email']; ?>"> <?php echo $member['email']; ?></a> </div></div>
                                                    <div class="col-xs-6 col-sm-1 col-md-1 col-lg-1"> 

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
                        <?php } else { ?>
		
		<div class="white-border">
                                        <h5 colspan="4" align="center"><?php echo lang('no_team_found'); ?></h5>
                                    </div>
		<?php } ?>

                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <div class="clr"></div>
</div>
<style>#searchForm{display:none}</style>
<script>

    $(document).ready(function () {
        $("body").delegate('#team_lead_id', 'change', function () {
            if ($(this).val() != '' && $(this).val() != null)
            {
                var id = $('#team_lead_id option:selected').attr('id');
                var name = $('#team_lead_id option:selected').attr('data-name');
                var role = $('#team_lead_id option:selected').attr('data-role');
                var img = $('#team_lead_id option:selected').attr('data-img');
                var html = "<div class='row' id='team_member' ><div class='col-xs-6 col-md-2 col-md-offset-2'><div class='text-center pad-tb6'><img src='" + img + "' alt=''/></div></div><div class='col-xs-6 col-md-4 pad-tb20'><span class='font-15em'><b>" + name + "</b></span></div><div class='col-xs-6 col-md-4 pad-tb24'><span class='font-1em'>" + role + "</span></div><div class='clr'></div></div>";
                // var html = "<div><img src='' height='50' width='50'><h3>" + name + "</h3><h4>" + role + "</h4><input type='hidden' name='team_members[]' value=" + id + "></div>"
                $('.teamLeader').html(html);
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
                var html = "<div class='row' id='team_member" + id + "'><div class='col-xs-12 col-md-2'><div class='text-center pad-tb6'><img src='" + img + "' alt=''/></div></div><input type='hidden'  name='team_members[]' value=" + id + "><div class='col-xs-6 col-md-4 pad-tb20'><span class='font-15em'><b>" + name + "</b></span></div><div class='col-xs-6 col-md-3 pad-tb24'><span class='font-1em'>" + role + "</span></div><div class='bd-error-control'><div class='bd-error pad-tb15'><a href='javascript:;' class='removeTeamMembers' data-id='team_member" + id + "'><i class='fa fa-remove redcol'></i></a></div></div></div><div class='clr grayline-1 team_member" + id + "'></div>";
                //  var html = "<div id='team_member" + id + "'><img src='<?php echo $imgUrl; ?>' height='50' width='50'><h3>" + name + "</h3><h4>" + role + "</h4><input type='hidden' name='team_members[]' value=" + id + "><a href='javascript:;' class='removeTeamMembers' data-id='team_member" + id + "'><i class='fa fa-remove redcol'></i></a></div>";
                $('.theteam').append(html);
                $('option:selected', this).prop('disabled', true);
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
					$('#parsley-id-16').css('display','none');
					$('#parsley-id-6').css('display','none');
                    $('#team_member_cnt').val(1);
                }
                else
                {
					//alert('bb');
					
                    $('#team_member_cnt').val('');
					$('#parsley-id-16').css('display','block');
					$('#parsley-id-6').css('display','block');
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
            $('#' + id).remove();
            $('.' + id).remove();

            if ($("input[name='team_members[]']").length > 0)
            {
                $('#team_member_cnt').val(1);
            }
            else
            {

                $('#team_member_cnt').val('');
                $('#parsley-id-16').css('display', 'block');
                $('#parsley-id-6').css('display', 'block');
            }
        });
        
		$("body").delegate('.removeTeamlead', 'click', function () {
			
			var id = $(this).attr('data-id');
			$('#team_lead_id').val('');
            $('#' + id).remove();
            $('.' + id).remove();
            if ($("input[name='team_members[]']").length > 0)
            {
                $('#team_member_cnt').val(1);
            }
            else
            {
				
                $('#team_member_cnt').val('');
				$('#parsley-id-16').css('display','block');
				$('#parsley-id-6').css('display','block');
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
                    $('#ajaxModal').html(data);
                    $('#remove_btn').toggleClass('hidden', 'show');
                  
                    console.log($("input[name='team_members[]']").length);
                    if ($("input[name='team_members[]']").length > 0)
                    {
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
			$('#team_member_cnt').val('');
            $('.teamLeader').empty();
            $('.theteam').empty();
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
            var delete_meg ="<?php echo $this->lang->line('delete_member');?>";
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
            var delete_meg ="<?php echo $this->lang->line('delete_team');?>";
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
