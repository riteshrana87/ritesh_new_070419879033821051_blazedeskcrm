<div class="whitebox">
    <div class="row">
        <div class="col-xs-12 col-md-12 agili-view">
            <?php if (!empty($project_status)) {
                $total_status = count ($project_status);
                if ($total_status > 4) {
                    $col = 2;
                } else if ($total_status > 6) {
                    $col = 1;
                } else {
                    $col = 3;
                }

                foreach ($project_status as $project_status) { ?>
                    <div class="col-md-<?= $col ?> coloum agile-view-border">
                        <h5 class="text-center"><b><?= !empty($project_status['status_name']) ? $project_status['status_name'] : '' ?></b></h5 class="text-center">

                        <div id="taskStatus<?= $project_status['status_id'] ?>" class="agileSortable">
                            <?php if (!empty($task_status_data[$project_status['status_id']])) {
                                $status = !empty($project_status['status_name']) ? $project_status['status_name'] : '';
                                $color_cls = !empty($project_status['status_color']) ? $project_status['status_color'] : '';;

                                foreach ($task_status_data[$project_status['status_id']] as $row) { ?>
                                    <div class="task-box bd-customtask-box" id="task-<?= $row['task_id'] ?>"
                                         id="task-<?= $row['task_id'] ?>">
                                        <div class="task-first bd-task-head">
                                            <span class="col-lg-5"><b><?= !empty($row['task_name']) ? ucfirst ($row['task_name']) : '' ?></b></span>
                                            <span class="col-lg-7"><span class="col-lg-10 nopadding"><i
                                                    class="fa fa-user"></i> <b><?= !empty($row['employee_name']) ? ucfirst ($row['employee_name']) : '' ?></b></span> <span class="bd-arw-ico show_div"><i class="fa fa-arrow-down"></i></span></span><!-- <span style="float:right;">Employee name</span> -->
                                                   
                                                    <div class="clr"></div>
                                        </div>
                                        <div class="display-task">
                                            <div class="task-second">
                                                <span class="col-lg-12"><?= !empty($row['description']) ? substr ($row['milestone_name'], 0, 10) : '' ?></span>
                                                
                                                <span class="col-lg-12"><?= !empty($row['milestone_name']) ? $row['milestone_name'] : '' ?></span><span class="col-lg-12 text-right"><?php if (isset($row['due_date']) && $row['due_date'] != '0000-00-00') {
                                                        echo configDateTime($row['due_date']);
                                                    }; ?></span>
                                                     <div class="clr"></div>
                                            </div>
                                           
                                            <div class="task-third">
                                                <div class="col-lg-12"><span class="color_box_agile col-md-6 voilt-label pull-left" style="background-color:<?= $color_cls ?>"><b><?= $status ?></b></span>
                                                <span class="pull-right col-md-6"
                                                    ><a
                                                        data-href="<?= base_url () ?>Projectmanagement/ProjectTask/view_record/<?= $row['task_id'] ?>"
                                                        data-toggle="ajaxModal" aria-hidden="true"
                                                        title="<?= lang ('view') ?>"
                                                        class="btn btn-sm btn-default width-100"><?= lang ('view') ?></a></span>
                                                        <div class="clr"></div></div>
                                                        <div class="clr"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>
                <?php }
            } ?>

        </div>
    </div>
</div>

<!-- Projewct status and links -->
<?php $this->load->view ('Project_links') ?>
<style type="text/css">
    .show_div{cursor: pointer;}
</style>
<script type="text/javascript">
    //ajax popup
    function paginationClick() {
        var href = $(this).attr('href');
        $("#rounded-corner").css("opacity", "0.4");
        var search = $('#search_input').val();
        var view = $('.select-view .focus').val();
        var change_status = $('#change_status').val();
        var cal_view = $('.fc-right .fc-state-active').text();
        var team_member = $('#team_member').val();
        if (view == 3) {
            var start = $('#startdate').val();
        } else {
            start = '';
        }
        $.ajax({
            type: "GET",
            url: href,
            data: {
                team_member: team_member,
                search: search,
                view: view,
                change_status: change_status,
                cal_view: cal_view,
                start: start
            },
           /* beforeSend: function () {
                $('#list_view').block({message: '<img src="<?=base_url("uploads/images/ajax-loader.gif")?>"> Please wait...'});
            },*/
            success: function (response) {
                //alert(response);
                $("#rounded-corner").css("opacity", "1");
                $("#list_view").empty();
                $("#list_view").html(response);
                if (view == 1) {
                    $('.tree').treegrid({
                        'initialState': 'collapsed',
                        //'saveState': true,
                    });
                }

                //bindClicks();
                //$('#list_view').unblock();
            }
        });
        return false;
    }
    //Get header
    function getHomeHeader() {
        $.ajax({
            type: "GET",
            url: '<?=base_url("Projectmanagement/ProjectTask/get_home_header")?>',
            data: {},

            success: function (response) {
                $("#Task_header").empty();
                $("#Task_header").html(response);
            }
        });
    }
    //Get header
    function getTodayTask() {
        $.ajax({
            type: "GET",
            url: '<?=base_url("Projectmanagement/ProjectTask/get_today_task")?>',
            data: {},

            success: function (response) {
                $("#today_task").empty();
                $("#today_task").html(response);
            }
        });
    }
    //Get activity
    function getActivity() {
        $.ajax({
            type: "GET",
            url: '<?=base_url("Projectmanagement/ProjectTask/get_home_activity")?>',
            data: {},

            success: function (response) {
                $("#Task_activities").empty();
                $("#Task_activities").html(response);
            }
        });
    }
    $(document).ready(function () {
        
        $('.display-task').hide();
        $('.show_div').click(function(){
           var cls= $(this).children().attr('class');
           $(this).children().removeClass();
           if(cls == 'fa fa-arrow-down')
           {$(this).children().addClass('fa fa-arrow-up');}
           else
           {$(this).children().addClass('fa fa-arrow-down');}
            $(this).parent().parent().next('.display-task').toggle();
           
        })
        $(".agileSortable").sortable({
            connectWith: ".agileSortable",
            
            receive: function (event, ui) {
                // console.log(ui.item);
                var cls;
                var cur_status;
                sta = this.id;
                var status = sta.split('taskStatus');
                var tid = ui.item[0].id;
                attr_id = tid.split('-');
                task_id = attr_id[1];
                $.ajax({
                    url: "<?php echo base_url('Projectmanagement/ProjectTask/update_status'); ?>",
                    type: 'POST',
                    data: {'status': status[1], 'task_id': task_id},
                    success: function (res) {
                        if (res == 'done') {
                            paginationClick();
                            getHomeHeader();
                            getActivity();
                            getTodayTask();
                            return false;

                        }
                    },
                    error: function () {
                        console.log('Error in call');
                    }

                });
            }
        }).disableSelection();
    });
</script>