<div class="whitebox col-xs-12 col-md-12 calendar-div">
    <div id="display_list" class="col-sm-2 fc-view-container">
        <table id="project_data" class="today-task">

        </table>
    </div>
    <div class="col-sm-10">
        <div id='calendar'></div>
        <input type="hidden" id="startdate" value="">
    </div>

</div>
<!-- Projewct status and links -->
<?php $this->load->view ('Project_links') ?>
<script>
    $('body').delegate('.fc-basicDay-button', 'click', function () {
        var currentDate = $('#calendar').fullCalendar('getDate');
        var beginOfWeek = currentDate.startOf('day');
        $('#startdate').val(beginOfWeek);
    });
    $(document).ready(function () {

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'year,month,basicWeek,basicDay'
            },
            defaultDate: '<?=!empty($start)?$start:date("Y-m-d")?>',
            defaultView: '<?=!empty($cal_view)?$cal_view:"basicWeek"?>',
            yearColumns: 2,
            lazyFetching: false,
            selectable: false,
            selectHelper: true,
            //firstDay: 0,
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: {
                url: '<?=base_url("Projectmanagement/ProjectTask/grantview")?>?search=' + $('#search_input').val() + '&status=' + $('#change_status').val() + '&team_member=' + $('#team_member').val(),
                success: function (data) {
                    var currentDate = $('#calendar').fullCalendar('getDate');
                    var selectview = $('#calendar').fullCalendar('getView');
                    if (selectview.name == 'basicDay') {
                        var beginOfWeek = currentDate.startOf('day');
                    }
                    else if (selectview.name == 'month' || selectview.name == 'year') {
                        var beginOfWeek = currentDate.endOf('week');
                    }
                    else {
                        var beginOfWeek = currentDate.startOf('week');
                    }
                    $('#startdate').val(beginOfWeek);
                    $('#project_data tr').remove();
                    $.each(data, function (i, obj) {
                        if (i != 0 && obj.color == '#C1AA56') {
                            $('#project_data').append('<tr><td></td></tr>');
                        }
                        if (obj.title != 'undefined') {
                            $('#project_data').append('<tr><td style="background-color: ' + obj.color + ';">' + obj.title + '</td></tr>');
                        }

                    });
                },
                error: function () {
                    $('#script-warning').show();
                }
            },
            eventRender: function (event, element) {
                element.attr("data-toggle", "ajaxModal");
                var hr = element.attr('href');
                element.removeAttr("href");
                element.attr("data-href", hr);
            },
        });
        
    });

</script>