<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="resetWidget"></div>
<?php echo $this->session->flashdata('msg'); ?>
<div class="clr"></div>
<div id="main-page">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
            <?php /* <div class="row">
              <h3 class="white-link pull-left col-sm-12 col-md-3 col-xs-12 col-lg-3">
              <?= !empty($project_detail[0]['project_name']) ? ucfirst($project_detail[0]['project_name']) : '' ?>
              </h3>
              <div class="col-lg-9 col-md-9 col-sm-12 bd-resp-txtleft">
              <?php if (checkPermission("Invoices", "add")) { ?>
              <?php if ($current_project_status == 3) { ?>
              <a href="javascript:;" onclick="markAsFinishedProject('<?php echo $this->session->userdata('PROJECT_ID'); ?>', 0);"  class="btn btn-white">
              <?= lang('reopen_project') ?>
              </a>&nbsp;&nbsp;
              <?php } else { ?>
              <a href="javascript:;" onclick="markAsFinishedProject('<?php echo $this->session->userdata('PROJECT_ID'); ?>', 3);" class="btn btn-white">
              <?= lang('finish') ?>
              <?= lang('project') ?>
              </a>&nbsp;&nbsp;
              <?php } ?>
              <?php } ?>
              <?php if (checkPermission("Invoices", "add")) { ?>
              <a data-href="<?php echo base_url() . 'Projectmanagement/Invoices/add_record/' . $this->project_id; ?>" data-toggle="ajaxModal" class="btn btn-white">
              <?= lang('invoice') ?>
              <?= lang('project') ?>
              </a>&nbsp;&nbsp;
              <?php } ?>
              <?php if (checkPermission("TeamMembers", "add")) { ?>
              <a href="<?php echo base_url() . 'Projectmanagement/TeamMembers'; ?>" class="btn btn-white">Team Members</a>&nbsp;&nbsp;
              <?php } ?>
              <?php if (checkPermission("Projectmanagement", "add")) { ?>
              <a data-href="<?php echo base_url() . 'Projectmanagement/edit_record/' . $cur_project_id . '/task_page'; ?>" data-toggle="ajaxModal" title="Edit Project" class="btn btn-white " >
              <?= lang('edit_project') ?>
              </a>
              <?php } ?>
              &nbsp;&nbsp; <a data-href="<?php echo base_url() . 'Projectmanagement/view_record/' . $cur_project_id; ?>" data-toggle="ajaxModal" title="Project Information" class="btn btn-white " >
              <?= lang('project_information') ?>
              </a>
              </div>
              <div class="clr"></div>
              </div> */ ?>
        </div>
        <!-- /Left side Starts-->
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 connectedSortable" id="colleft" >
            <?php /* <h3 class="white-link">
              <?= !empty($project_detail[0]['prospect_name']) ? ucfirst($project_detail[0]['prospect_name']) : '' ?>
              </h3> */ ?>
            <div id="left-innersort" class="innersortable">
                <?php
                if (count($inner_widgets) > 0) {

                    if (array_key_exists('innerWidgets', $inner_widgets)) {
                        $innerWidgetLoop = $inner_widgets['innerWidgets'];
                    } else {
                        $innerWidgetLoop = $inner_widgets;
                    }
                    $innerWidgetLoop = array_unique($innerWidgetLoop);
                    foreach ($innerWidgetLoop as $innerWidgets) {
                        $this->load->view($innerWidgets);
                    }
                }
                ?>
            </div>
        </div>

        <!--/Left side Ends --> 
        <!--/right side Starts -->
        <div class = "col-xs-12 col-sm-12 col-md-3 connectedSortable" id = "colright" >
            <?php
            if (array_key_exists('sectionRight', $widgets)) {
                $widgets['sectionRight'] = array_unique($widgets['sectionRight']);
                foreach ($widgets['sectionRight'] as $views) {
                    $this->load->view('widgets/' . $views);
                }
            } else if (empty($widgets['sectionRight'])) {
                echo "<div class='empty hidden sortableDiv'></div>";
            }
            ?>

            <!-- Place for chat --> 
        </div>
        <!-- /right side ends -->
        <div class="clr"></div>
    </div>
</div>
<script type="text/javascript">
    var placeholderDiv;
    var sortorder;
    var placeholder2;
    var sortorder2;
    $(function () {
        placeholder2 = '';
        $('#colright,#headerTask').sortable({
            connectWith: '.connectedSortable',
            items: "div.sortableDiv",
            //            handle: '.tab-header'
            cursor: 'move',
            placeholder: 'placeholder',
            forcePlaceholderSize: true,
            opacity: 0.4,
            stop: function (event, ui) {
                var sortorderLeft = [];
                var sortorderRight = [];
                $('#headerTask .sortableDiv').each(function () {
                    if ($(this).attr('id')) {
                        sortorderLeft.push($(this).attr('id'));
                    }
                });
                $('#colright .sortableDiv').each(function () {
                    if ($(this).attr('id')) {
                        sortorderRight.push($(this).attr('id'));
                    }
                });
                $.ajax({
                    url: "<?php echo base_url('Projectmanagement/ProjectTask/dashboardWidgetsOrder'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'placeholder1': 'sectionLeft', 'innerWidgets1': sortorderLeft, 'placeholder2': 'sectionRight', 'innerWidgets2': sortorderRight},
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
            },
            receive: function (event, ui) {
            }
        });
    });
    $(function () {
        $('#left-innersort').sortable({
            connectWith: '.innersortablediv',
            //
            items: ".innersortablediv",
            //  handle: '.tab-header',
            cursor: 'move',
            placeholder: 'placeholder',
            forcePlaceholderSize: true,
            opacity: 0.4,
            stop: function (event, ui) {
                var innersortOrder = [];
                $('#left-innersort .innersortablediv').each(function () {
                    if ($(this).attr('id') != '' && typeof ($(this).attr('id')) != "undefined")
                    {
                        innersortOrder.push($(this).attr('id'));
                    }
                });
                //   console.log(innersortOrder);
                $.ajax({
                    url: "<?php echo base_url('Projectmanagement/ProjectTask/dashboardWidgetsInnerOrder'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'innerWidgets1': innersortOrder},
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
            },
            receive: function (event, ui) {


            }
        });
    });
    $('body').delegate('.resetWidget', 'click', function () {
        var delete_meg = "Are your sure to Reset Your Dashboard Widgets View to Default view?";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                $.ajax({
                                    url: "<?php echo base_url('Projectmanagement/ProjectTask/dashboardWidgetsOrder'); ?>/?resetWidgets=Yes",
                                    type: "POST",
                                    dataType: "json",
                                    data: {'placeholder1': placeholderDiv, 'innerWidgets1': sortorder, 'placeholder2': placeholder2, 'innerWidgets2': sortorder2},
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

    $(document).ready(function () {
        //    $('#text').val("");
        //    $('#text').attr("readonly",'false');

    })
</script>