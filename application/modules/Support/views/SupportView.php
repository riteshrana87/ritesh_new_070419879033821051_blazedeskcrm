<?php
$imgUrl = base_url('uploads/images/mark-johnson.png');

defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'saveTicketData';
$formPath = $project_view . '/' . $formAction;
//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'saveTicketData';
$formPath = $project_view . '/' . $formAction;
//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
?>

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 next">
        <?php echo $this->breadcrumbs->show(); ?>
    </div>
    <div class="col-md-5 col-lg-5 col-xs-5 col-sm-5 pull-right settings text-right" >
        <a class="pull-right" style="margin-left:15px;" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a>
        <a class="pull-right" title="<?php echo lang('reset_dashboard'); ?>"><i class="fa fa-refresh fa-2x"></i></a>
    </div>
    <div class="clr"></div>
    <?php echo $error = $this->session->flashdata('msg'); ?>

</div>
<div class="row">

    <div class="clr"></div>
    <div class="col-md-12 col-md-9 col-lg-9 connectedSortable" id="sectionLeft"  style="min-height: 1000px">

        <?php
        if (array_key_exists('sectionLeft', $widgets)) {
            foreach ($widgets['sectionLeft'] as $views) {
                if ($views == 'AjaxTasks') {
                    echo "<div id='taskDataId'>";
                    $this->load->view($views);
                    echo "</div>";
                } else {
                    $this->load->view($views);
                }
                echo '<div class="clr"></div>';
            }
        } else if (empty($widgets['sectionLeft'])) {
            echo "<div class='empty hidden sortableDiv'></div>";
        }
        ?>
    </div>
    <div class="col-md-12 col-md-3 col-lg-3 connectedSortable" id="sectionRight"  style="min-height: 1000px">
        <?php
        if (array_key_exists('sectionRight', $widgets)) {
            foreach ($widgets['sectionRight'] as $views) {

                if ($views == 'AjaxTasks') {
                    echo "<div id='taskDataId'>";
                    $this->load->view($views);
                    echo "</div>";
                } else {
                    $this->load->view($views);
                }
                echo '<div class="clr"></div>';
            }
        } else if (empty($widgets['sectionRight'])) {
            echo "<div class='empty hidden sortableDiv'></div>";
        }
        ?>

    </div>
    <div class="clr"></div>
</div>
<script>

    $(document).ready(function () {
		var enjoyhint_instance = new EnjoyHint({
	onStart: function () {
        //alert('first');
    },
    onEnd: function () {
            
            $(".ticketadd").trigger("click");
    }
   
    });
		var enjoyhint_script_steps = [{
			
			
			'next .navbar-brand' : 'WelCome to Blazedesk',
			'shape': 'circle',
			/* selector:'.navbar-brand',//jquery selector
				event:'click',
				description:'Click on this btn',*/
				
			},
			{
			
			'next .pull-left' : 'Here You Can Start With Left Menu',
			
			},
			{
				'next .ticketadd' : 'This button allows you to switch between the search results',
				
					/*onBeforeStart:function(){
				  $(".ticketadd").trigger("click");
				}	*/
				 
			},
			
			
			];
		enjoyhint_instance.set(enjoyhint_script_steps);
enjoyhint_instance.run();
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
                var values = id;
                var html = "<div class='row' id='team_member" + id + "'><div class='col-xs-12 col-md-2'><div class='text-center pad-tb6'><img src='" + img + "' alt=''/></div></div><input type='hidden'  name='team_members[]' value=" + id + "><div class='col-xs-6 col-md-4 pad-tb20'><span class='font-15em'><b>" + name + "</b></span></div><div class='col-xs-6 col-md-3 pad-tb24'><span class='font-1em'>" + role + "</span></div><div class='bd-error-control'><div class='bd-error pad-tb15'><a href='javascript:;' class='removeTeamMembers' data-id='team_member" + id + "'><i class='fa fa-remove redcol'></i></a></div></div></div><div class='clr grayline-1 team_member" + id + "'></div>";
                //  var html = "<div id='team_member" + id + "'><img src='<?php echo $imgUrl; ?>' height='50' width='50'><h3>" + name + "</h3><h4>" + role + "</h4><input type='hidden' name='team_members[]' value=" + id + "><a href='javascript:;' class='removeTeamMembers' data-id='team_member" + id + "'><i class='fa fa-remove redcol'></i></a></div>";
                $('.theteam').append(html);
                //$('#team_member').val(values);
                // $('option:selected', this).remove();
                $('option:selected', this).prop('disabled', true);
                $(".chosen-selectmember").val('').trigger("chosen:updated");
                $('#team_member_id_chosen .result-selected').addClass('hidden');
                if ($("input[name='team_members[]']").length > 0)
                {
                    $('#parsley-id-16').css('display', 'none');
                    $('#parsley-id-6').css('display', 'none');

                    $('#team_member_cnt').val(1);
                }
                else
                {
                    //alert('bb');

                    $('#team_member_cnt').val('');
                    $('#parsley-id-16').css('display', 'block');
                    $('#parsley-id-6').css('display', 'block');
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
    });
</script>
<script>
    function displayTeamById(id, flag = '0')
    {
        var team_name = $('#team_name').val();
        if (id != '' && id != null) {
            $.ajax({
                url: "<?php echo base_url('SupportTeam/getTeamListbyId/'); ?>/" + id + '?flag=' + flag + '&team_name=' + team_name,
                type: "get",
                success: function (data)
                {
                    $('#ajaxModal').html(data);
                    $('#remove_btn').toggleClass('hidden', 'show');
                    $('#team_member_cnt').val(1);
                }
            });
        }
        else {
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

<script>
    $(document).ready(function () {

        $("#proposalType .oppSort").sortable({
            connectWith: ".oppSort",
            receive: function (event, ui) {
                var id = ui.item.attr('data-id');
                var currtype = ui.item.attr('data-type');
                var dataType = $(this).parent('div').attr('data-dataType');
                var ticket_id = ui.item.attr('data-id');
                var type = $(this).parent('div').attr('id');
                $.ajax({
                    url: "<?php echo base_url('Ticket/update_status'); ?>",
                    type: 'POST',
                    dataType: 'JSON',
                    data: {'id': id, 'type': type, 'currtype': currtype, 'dataType': dataType, 'ticket_id': ticket_id},
                    success: function (res)
                    {


                        if (type == '3')
                        {
                            $('.' + id).removeClass('blue-label');
                            $('.' + id).removeClass('red-label');
                            $('.' + id).removeClass('green-label');
                            $('.' + id).addClass('orange-label');
                            $('.orange-label').text('On Hold');
                            $('#onhold_count').text(res.count_onhold);
                            $('#assign_count').text(res.count_assign);
                            $('#complete_count').text(res.count_complete);
                            $('#new_count').text(res.count_new);
                            $('#due_count').text(res.count_today);
                            $('#over_count').text(res.count_overdue);
                            $('#open_count').text(res.count_open);


                            return false;
                        }
                        else if (type == '5')

                        {
                            $('.' + id).removeClass('orange-label');
                            $('.' + id).removeClass('red-label');
                            $('.' + id).removeClass('blue-label');
                            $('.' + id).addClass('green-label');
                            $('.green-label').text('Assigned');
                            $('#onhold_count').text(res.count_onhold);
                            $('#assign_count').text(res.count_assign);
                            $('#complete_count').text(res.count_complete);
                            $('#new_count').text(res.count_new);
                            $('#due_count').text(res.count_today);
                            $('#over_count').text(res.count_overdue);
                            $('#open_count').text(res.count_open);
                            return false;
                        }

                        else if (type == '4')

                        {
                            $('.' + id).removeClass('orange-label');
                            $('.' + id).removeClass('red-label');
                            $('.' + id).removeClass('green-label');
                            $('.' + id).addClass('blue-label');
                            $('.blue-label').text('Completed');
                            $('#onhold_count').text(res.count_onhold);
                            $('#assign_count').text(res.count_assign);
                            $('#complete_count').text(res.count_complete);
                            $('#new_count').text(res.count_new);
                            $('#due_count').text(res.count_today);
                            $('#over_count').text(res.count_overdue);
                            $('#open_count').text(res.count_open);
                            return false;
                        }
                        else if (type == '1')

                        {
                            $('.' + id).removeClass('orange-label');
                            $('.' + id).removeClass('blue-label');
                            $('.' + id).removeClass('green-label');
                            $('.' + id).addClass('red-label');
                            $('.red-label').text('New');
                            $('#onhold_count').text(res.count_onhold);
                            $('#assign_count').text(res.count_assign);
                            $('#complete_count').text(res.count_complete);
                            $('#new_count').text(res.count_new);
                            $('#due_count').text(res.count_today);
                            $('#over_count').text(res.count_overdue);
                            $('#open_count').text(res.count_open);

                            return false;
                        }


                    },
                    error: function ()
                    {
                        console.log('Error in call');
                    }

                });
            }
        }).disableSelection();
    });

</script>
<script type="text/javascript">
    var placeholderDiv;
    var sortorder;
    var placeholder2;
    var sortorder2;

    $(function () {
        placeholder2 = '';
        $('#sectionLeft,#sectionRight,.empty').sortable({
            connectWith: '.connectedSortable',
            items: "div.sortableDiv",
//            handle: '.tab-header'
            cursor: 'move',
            placeholder: 'placeholder',
            forcePlaceholderSize: true,
            opacity: 0.4,
            stop: function (event, ui) {
                var group = ui
                var placeholderDiv = $(this).attr('id');
                var sortorder = [];
                var sortorder2 = [];
                $('#sectionLeft .sortableDiv').each(function () {
                    if ($(this).attr('id') != 'position-right-top')
                    {
                        sortorder.push($(this).attr('id'));
                    }
                });

                $('#sectionRight .sortableDiv').each(function () {
                    if ($(this).attr('id') != 'position-right-top')
                    {
                        sortorder2.push($(this).attr('id'));
                    }
                });

                $.ajax({
                    url: "<?php echo base_url('Support/dashboardWidgetsOrder'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'placeholder1': 'sectionLeft', 'innerWidgets1': sortorder, 'placeholder2': 'sectionRight', 'innerWidgets2': sortorder2},
                    success: function (data)
                    {
                        if (data.type == 'reset')
                        {
                            window.location.href = window.location.href;

                        }
                        else
                        {

                        }

                    }

                });
                /*Pass sortorder variable to server using ajax to save state*/
            },
            receive: function (event, ui) {
                var group = ui
                var placeholderDiv = $(this).attr('id');
                var sortorder = [];
                var sortorder2 = [];
                $('#sectionLeft .sortableDiv').each(function () {
                    if ($(this).attr('id') != 'position-right-top')
                    {
                        sortorder.push($(this).attr('id'));
                    }
                });

                $('#sectionRight .sortableDiv').each(function () {
                    if ($(this).attr('id') != 'position-right-top')
                    {
                        sortorder2.push($(this).attr('id'));
                    }
                });
                $.ajax({
                    url: "<?php echo base_url('Support/dashboardWidgetsOrder'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'placeholder1': 'sectionLeft', 'innerWidgets1': sortorder, 'placeholder2': 'sectionRight', 'innerWidgets2': sortorder2},
                    success: function (data)
                    {
                        if (data.type == 'reset')
                        {
                            window.location.href = window.location.href;

                        }
                        else
                        {

                        }

                    }

                });
//             

            }
        }).disableSelection();
    });

    $('body').delegate('.fa-refresh', 'click', function () {
        var delete_meg ="<?php echo $this->lang->line('dashboard_prompt');?>";
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
                            url: "<?php echo base_url('Support/dashboardWidgetsOrder'); ?>/?resetWidgets=Yes",
                            type: "POST",
                            dataType: "json",
                            data: {'placeholder1': 'sectionLeft', 'innerWidgets1': '', 'placeholder2': 'sectionRight', 'innerWidgets2': ''},
                            success: function (data)
                            {
                                if (data.type == 'reset')
                                {
                                    window.location.href = window.location.href;
                                }
                                else
                                {

                                }
                            }
                        });
                        dialog.close();
                    }
                }]
            });


    });




</script>
