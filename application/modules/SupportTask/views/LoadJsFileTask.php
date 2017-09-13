<script type="text/javascript">
    //$(function () {
    $('#reminder').on('change', function () {

        var reminder_status = $('#reminder').is(":checked");

        if (reminder_status == true)
        {

            $('#before_after').show();
            $('#remind_time').show();
            $('#repeat').show();
            $('#remind_day').show();
        }
        else
        {
            $('#before_after').hide();
            $('#remind_time').hide();
            $('#repeat').hide();
            $('#remind_day').hide();
        }
    });
    $(document).on('click', '[type="submit"]', function () {
        var task_name_trim = $('#task_name').val();
        var task_name = $.trim(task_name_trim);
        if (task_name == "" || task_name == null)
        {
            $('#task_name_error').html("Please Enater Task Name");
            $('#task_name_error').focus();
            return false;
        }
        else
        {
            $('#task_name_error').html("");

        }
        var importance = $('#importance').val();
        if (importance == "" || importance == null)
        {
            $('#importance_error').html("Please Select Importance");
            $('#importance_error').focus();
            return false;
        }
        else
        {
            $('#importance_error').html("");

        }
        return true;

    });

    $('#start_date').datepicker().on('changeDate', function (ev) {

        $('#start_date').datepicker('hide');

    });
    $('#end_date').datepicker().on('changeDate', function (ev) {

        $('#end_date').datepicker('hide');

    });
    $('.add_task').on('click', function () {
        cleartaskdata();

        $('div.modelTaskTitle').html('Create New Task');
        $('#task_submit_btn').val('Create Task');
    });

    $('.edit_task').click(function () {
        cleartaskdata();
        //clear_task_data();
        var val = $(this).attr('data-id');
        var data = 'task_id=' + val;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url($task_view); ?>/edittask",
            data: data,
            dataType: 'json',
            success: function (data) {
                $('div.modelTaskTitle').html('Update Task');
                $('#task_submit_btn').val('Update Task');
                $('#task_id').val(data.task_id);
                $('#task_name').val(data.task_name);
                $('#importance').val(data.importance);
                $('#task_description').val(data.task_description);
                $('#before_after').val(data.before_status);
                $('#remind_time').val(data.repeat);
                $('#repeat').val(data.time);
                $('#remind_day').val(data.remind_before_min);
                $('#start_date').val(data.start_date);
                $('#end_date').val(data.end_date);
                if (data.remember == '1')
                {
                    $('#reminder').bootstrapToggle('on');
                    $('#before_after').val(data.before_status);
                    $('#remind_time').val(data.time);
                    $('#repeat').val(data.repeat);
                    $('#remind_day').val(data.remind_before_min);
                }

                $('#newTask').modal('show');
            }
        });
        return false;
    });
    $('.task_remove_btn').on('click', function () {
        var val = $('#task_id').val();

        var data = 'task_id=' + val;
        $.ajax({
            type: "POST",
            url: "<?php echo base_url($task_view); ?>/deletetask",
            data: data,
            success: function (data) {

                window.location.href = "<?php echo base_url(); ?>Task";
            }
        });
        return false;
    });

    function cleartaskdata() {
        $('#task_id').val('');
        $('#task_name').val('');
        $('#importance').val('');
        $('#task_description').val('');
        $('#before_after').val('');
        $('#remind_time').val('');
        $('#repeat').val('');
        $('#remind_day').val('');
        $('#start_date').val('');
        $('#end_date').val('');
        $('#importance_error').html("");
        $('#task_name_error').html("");
        $('#reminder').bootstrapToggle('off');

    }

</script>
