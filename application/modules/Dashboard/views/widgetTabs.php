<div id="position-left-bottom" class="sortableDiv">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#Email" aria-controls="Email" role="tab" data-toggle="tab"><?php echo  lang('emails');?></a></li>
        <li><a href="#todo" aria-controls="todo" role="tab" data-toggle="tab"><?php echo lang('to_do'); ?></a></li>
        <li><a href="#Events" aria-controls="Events" role="tab" data-toggle="tab"><?php echo lang('events');?></a></li>
        <li><a href="#Opportunities" aria-controls="Opportunities" role="tab" data-toggle="tab"><?php echo lang('opportunities');?></a></li>
        <li><a href="#Documents" aria-controls="Documents" role="tab" data-toggle="tab"><?php echo lang('documents');?></a></li>
        <li><a href="#Cases" aria-controls="Cases" role="tab" data-toggle="tab"><?php echo lang('cases');?></a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="Email">

        </div>
        <div role="tabpanel" class="tab-pane" id="todo">
            <div class="whitebox" id="taskTable">
                <div class="table table-responsive">
                    <table class="table table-responsive">
                        <thead>
                            <tr role="row">
                                <th class='sortTask col-md-4'>
                                    <?php echo lang('to_do'); ?>
                                </th>
                                <th class='sortTask col-md-2'>
                                    <?php echo lang('priority'); ?>
                                </th>
                                <th class='sortTask col-md-2'>
                                    <?php echo lang('deadline'); ?>
                                </th>
                                <th class="col-md-2"><?php if (checkPermission('Task', 'add')) { ?><a data-href="<?php echo base_url('Task/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true">+<?php echo lang('create'); ?></a><?php } ?> </th></tr>

                        </thead>
                        <tbody>
                            <?php
                            if (count($task_data) > 0) {
                                foreach ($task_data as $tasks) {
                                    ?>
                                    <tr>
                                        <td><?php echo $tasks['task_name']; ?></td>
                                        <td><?php
                                            if ($tasks['importance'] == 'High') {

                                                echo '<div class="redline"></div>';
                                            } elseif ($tasks['importance'] == 'Medium') {
                                                echo '<div class="blueline"></div>';
                                            } else
                                                echo '<div class="greenline"></div>';
                                            ?><?php //echo $task_data['importance']; ?></td>
                                        <td><?php echo configDateTime($tasks['end_date']); ?></td>
  <td> <?php if (checkPermission('Task', 'edit')) { ?><a data-href="<?php echo base_url('Task/edittask/'.$tasks['task_id'].'/Dashboard'); ?>" data-toggle="ajaxModal" title="<?= $this->lang->line('edit') ?>" aria-hidden="true" data-refresh="true" class="edit_lead" id="edit_lead"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;<?PHP if (checkPermission('Task', 'view')) { ?><a data-href="<?php echo base_url('Task/viewtask/'.$tasks['task_id'].'/SalesOverview'); ?>" title="<?= $this->lang->line('view') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="view_lead" id="view_lead"><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                            </td>

                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="4" class="text-center"><?= lang ('common_no_record_found') ?></td>
                                </tr>
                            <?php } ?> 
                        </tbody>
                    </table>

                </div>

                <div class="clr"></div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="Events">

        </div>
        <div role="tabpanel" class="tab-pane" id="Opportunities">
            <div class="whitebox">
                <?php echo Modules::run('Sidebar/opportunitiesTable'); ?>

                <div class="clr"></div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="Cases">

        </div>
        <div role="tabpanel" class="tab-pane" id="Campaigns">
            <div class="whitebox">
                <div class="table table-responsive">
                    <table id="7" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Campaign Name</th>
                                <th>Type</th>
                                <th>Campaign Date</th>
                                <th>Contact Included</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Project name x</td>
                                <td>Newsletter</td>
                                <td>01-01-2016</td>
                                <td>Yes</td>
                                <td>In Progress</td>
                                <td><a href="#"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;<a href="#"><i class="fa fa-pencil greencol"></i></a>&nbsp;&nbsp; <a href="#"><i class="fa fa-remove redcol"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="clr"></div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="Documents" >
            <?php //echo $this->load->view('widgetFiles');?>
            <div class="clr"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="imgviewpopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="$('#imgviewpopup').modal('hide');"  aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Preview</h4>
            </div>
            <div class="modal-body">
                <img id="previewImg" class="img-responsive" alt="no-img">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"  onclick=" $('#imgviewpopup').modal('hide');">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script>
    function getContactEvents()
    {
        var url_contact = '<?php echo base_url() . "Dashboard/getContactEvents/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getDealsData': 'Campaign'},
            success: function (data)
            {
                $('#Events').html(data);
            }
        });
    }

    function getContactCases()
    {
        var url_contact = '<?php echo base_url() . "Dashboard/getContactCases/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getDealsData': 'Campaign'},
            success: function (data)
            {
                $('#Cases').html(data);
            }
        });
    }

    function getEmails()
    {
        var url_contact = '<?php echo base_url() . "Dashboard/getEmails/" ?>';
        $.ajax({
            type: "POST",
            url: url_contact,
            data: {'getDealsData': 'Campaign'},
            success: function (data)
            {
                $('#Email').html(data);
            }
        });
    }


    $(document).ready(function ()
    {
        getEmails();
        getContactEvents();
        getContactCases();

        /* Start for Events */
        $("body").delegate("#table_events ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Events").empty();
                    $("#Events").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_events th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Events").empty();
                    $("#Events").html(response);

                    return false;
                }
            });
            return false;
        });
        /* End for Events */
        /* Start for Cases */
        $("body").delegate("#table_cases ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Cases").empty();
                    $("#Cases").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_cases th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Cases").empty();
                    $("#Cases").html(response);

                    return false;
                }
            });
            return false;
        });
        /* End for Cases */
        /* Start for Communciation */
        $("body").delegate("#table_emails ul.tsc_pagination a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Email").empty();
                    $("#Email").html(response);

                    return false;
                }
            });
            return false;
        });

        $("body").delegate("#table_emails th.sortTask a", "click", function () {
            var href = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: href,
                data: {},
                success: function (response)
                {
                    $("#Email").empty();
                    $("#Email").html(response);

                    return false;
                }
            });
            return false;
        });
        /* End for Communciation */
    });


    function getDocuments()
    {
        var url_doc = '<?php echo base_url('Dashboard/getFilesList') ?>';
        $.ajax({
            type: "GET",
            url: url_doc,
            success: function (data)
            {
                $('#Documents').html(data);
                var cnt = $('#cnt').val();
                if (cnt > 0)
                {
                    //  $('#Documents ').style('overflow-y: scroll; overflow-x: hidden; max-height: 509px;');
                }
            }
        });
    }
</script>