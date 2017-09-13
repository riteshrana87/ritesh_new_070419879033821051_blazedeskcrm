<script type="text/javascript">
    $(document).ready(function () {
        $('.tree').treegrid({
            'initialState': 'collapsed',
            //'saveState': true,
        });
    });
</script>

<script type="text/javascript">
    function displayStatus(change_status) {
        var team_member = $('#main-page #team_member').val();
        var search = $('#search_input').val();
        $.ajax({
            type: "GET",
            url: '<?= base_url("Projectmanagement/ProjectTask") ?>',
            data: {view: 2, change_status: change_status, team_member: team_member, search: search, },
            beforeSend: function () {
                //  $('#list_view').block({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> Please wait...'});
            },
            success: function (response) {
                $('#change_status').val(change_status);
                $("#list_view").empty();
                $("#list_view").html(response);
                $('.change_view').removeClass('focus');
                $('#agileview').addClass('focus');
                //$('#list_view').unblock();
            }
        });
    }
    function reset_data() {
        $("#search_input").val("");
        $('#change_status').val('');
        $('#team_member').val('');
        paginationClick();
        return false;
    }
    function bindClicks() {
        $("#common_tb ul.pagination a.ajax_paging").click(paginationClick);
    }


    function bindClicksSort() {
        $("body").delegate('th.sortCost a', 'click', (paginationClick));
    }
    $('body').delegate('#searchForm', 'submit', function () {
        paginationClick();
        return false;
    });

    $('body').delegate('.change_view', 'click', function () {
        $('.change_view').removeClass('focus');
        $(this).addClass('focus');
        paginationClick();
        return false;
    });
    $('body').delegate('#team_member', 'change', function () {
        paginationClick();
        return false;
    });
    $('body').delegate('#change_status', 'change', function () {
        paginationClick();
        return false;
    });
    function paginationClick() {
        var href = $(this).attr('href');
        $("#rounded-corner").css("opacity", "0.4");
        var search = $('#search_input').val();
        var view = $('.select-view .focus').val();
        var change_status = $('#change_status').val();
        var team_member = $('#team_member').val();
        var cal_view = $('.fc-right .fc-state-active').text();
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
            beforeSend: function () {
                //   $('#list_view').block({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> Please wait...'});
            },
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

                bindClicks();
                //  $('#list_view').unblock();
            }
        });
        return false;
    }
    $(document).ready(function () {
        $('#datepickerhome').datepicker("update", "<?= date('m-d-Y') ?>");
        //load modal
        //ajax popup open
        $('body').delegate('[data-toggle="ajaxModal"]', 'click',
                function (e) {
                    $('#ajaxModal').remove();
                    e.preventDefault();
                    var $this = $(this)
                            , $remote = $this.data('remote') || $this.attr('data-href')
                            , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                    $('body').append($modal);
                    $modal.modal();
                    $modal.load($remote);
                });

        bindClicksSort();
        bindClicks();
    });
    function gotoSubTask(purl) {
        if (purl != '')
        {
            $.ajax({
                url: purl,
                type: "GET",
                success: function (d)
                {
                    $('#ajaxModal').html(d);
                }
            });
        }
    }
    function markAsFinishedProject(projectId, ptype)
    {

        var msg = (ptype == 3) ? "<?php echo lang('project_finish_msg'); ?>" : "Are you sure to Reopen this Project?";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: msg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $.ajax({
                            url: "<?php echo base_url('Projectmanagement/finishProject'); ?>",
                            type: "POST",
                            data: {id: projectId, ptype: ptype},
                            dataType: "json",
                            success: function (d)
                            {
                                if (d.status == 1)
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
</script>