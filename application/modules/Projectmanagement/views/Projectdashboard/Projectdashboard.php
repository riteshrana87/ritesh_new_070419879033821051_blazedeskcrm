<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
?>
<div class="row">
    <div id="main-page">

        <?php
       echo $error = $this->session->flashdata('msg');
        ?>

        <div id="sortableWidget">
            <?php
            if (count($widgets) > 0) {
                if (is_array($widgets['widgetOrder'])) {
                    //  $widgetOrder = explode(',', $widgets['widgetOrder']);
                } else {
                    $widgetOrder = $widgets['widgetOrder'];
                }
                ?>

                <?php foreach ($widgets['widgetOrder'] as $views) {
                    ?>
                    <?php echo $this->load->view('Widgets/' . $views); ?>
                <?php } ?>
            <?php } ?>

        </div>




        <div class="clr"></div>
    </div>
</div>
<script type="text/javascript">
    //Chart code
    $(function () {
        //total task chart
        $('#task_status_chart').highcharts({
            chart: {
                type: 'pie',
                marginBottom: 70,
                height: 285

            },
            colors: [<?= isset($status_color_str) ? $status_color_str : '' ?>],
            credits: {
                enabled: false
            },
            title: {
                text: "<?= isset($pie_chart_data_str) ? lang('task_status') : lang('no_data') ?>",
                // verticalAlign: 'bottom',
                //y: 50
                //margin:7
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}%</b><br/>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'normal',
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return this.y + '%';
                        },
                        distance: -10
                    },
                    shadow: false,
                    center: ['50%'],
                    showInLegend: true,
                }
            }, series: [{
                    name: 'Status',
                    type: 'pie',
                    innerSize: '50%',
                    data: [<?= isset($pie_chart_data_str) ? $pie_chart_data_str : '' ?>]
                }]
        });
        //completed status chart
        $('#completed_status_chart').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false,
                height: 285
            },
            credits: {
                enabled: false
            },
            colors: ['#9FBD4B', '#E1857A'],
            title: {
                text: "<?= isset($pie_completed_task_str) ? lang('status') : lang('no_data') ?>",
                //align: 'center',
                //verticalAlign: 'middle',
                //y: 30
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        distance: -10,
                        formatter: function () {
                            return this.y + '%';
                        },
                        style: {
                            fontWeight: 'bold',
                            color: 'white',
                            textShadow: '0px 1px 2px black'
                        }
                    },
                    showInLegend: true,
                    startAngle: -90,
                    endAngle: 90,
                    center: ['50%', '75%']
                }
            },
            series: [{
                    type: 'pie',
                    name: 'Status',
                    innerSize: '50%',
                    data: [<?= isset($pie_completed_task_str) ? $pie_completed_task_str : '' ?>]
                }]
        });
    });
</script>
<script type="text/javascript">
    var placeholderDiv;
    var sortorder;
    var placeholder2;
    var sortorder2;

    $(function () {
        placeholder2 = '';
        $('#sortableWidget').sortable({
            connectWith: '.connectedSortable',
            // items: "div"
//            handle: '.tab-header'
            cursor: 'move',
            placeholder: 'placeholder',
            forcePlaceholderSize: true,
            opacity: 0.4,
            stop: function (event, ui) {
                var sortorder = [];
                $('.connectedSortable').each(function () {
                    sortorder.push($(this).attr('id'));
                });
                $.ajax({
                    url: "<?php echo base_url('Projectmanagement/Projectdashboard/dashboardWidgetsOrder'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {'sortStr': sortorder},
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

    $('body').delegate('.fa-refresh', 'click', function () {
        var delete_meg ="<?php echo lang('dashboard_prompt'); ?>";
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
                            url: "<?php echo base_url('Projectmanagement/Projectdashboard/dashboardWidgetsOrder'); ?>/?resetWidgets=Yes",
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


</script>