<?php
//$data['converted_amount'] = $converted_amount;
//$converted_amount_target;
//            $data['sales_difference'] = $target - $salesAmmount;
//            $data['sales_amount_by_user'] = $salesAmmount;
?>
<script type="text/javascript">

    $(function () {
        $('#container').highcharts({
            chart: {
                type: 'line'
            },
            credits: {
                enabled: false
            },
            title: {
                text: '<?php echo lang("monthly_sales_comparision"); ?>'
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: false
            },
            yAxis: {
                title: {
                    text: '<?php echo lang("monthly_comparision"); ?>'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            series: [{
                    name: '<?php echo $currentMonthName; ?>',
                    data: [<?php echo $currentMonthData; ?>]
                }, {
                    name: '<?php echo $nextMonthName; ?>',
                    data: [<?php echo $nextMonthData; ?>]
                }]
        });
    });</script>

<script type="text/javascript">
    $(document).ready(function () {
        var sortorder = '';
        $('.sortableDiv').each(function () {
            // var itemorder = $(this).sortable('toArray');
            var columnId = $(this).attr('id');
            var parent = $(this).parent('div').attr('id');
            sortorder += columnId + '=' + parent + '&';
        });
        //  alert('SortOrder: ' + sortorder);
    });
    $(function () {
        $('#pie1').highcharts({
            chart: {
                type: 'pie',
                marginBottom: 70
            },
            colors: ['#68CD64', '#DDDDDD'],
            credits: {
                enabled: false
            },
            title: {
                text: '<?php echo lang("sales_target"); ?>'
                        //  verticalAlign: 'bottom',
                        // y: 50
                        //margin:7
            },
            tooltip: {
                //pointFormat: '',//{series.name}: <b>{point.percentage}%</b>
                //percentageDecimals: 1
                formatter: function () {
                    return false;
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'normal',
                    dataLabels: {
                        enabled: false,
                        formatter: function () {
                            return this.y;
                        },
                        distance: -30
                    },
                    shadow: false,
                    center: ['50%'],
                    showInLegend: false,
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    }
//                    point: {
//                        events: {
//                            mouseOver: function () {
//                                $('div.' + this.name).addClass('highlight');
//                            },
//                            mouseOut: function () {
//                                $('div.' + this.name).removeClass('highlight');
//                            }
//                        }
//                    }
                }
            },
            series: [{
                    startAngle: 0,
                    type: 'pie',
                    innerSize: '50%',
                    data: [
                        {
                            name: 'Others',
                            y: <?php echo $sales_percentage; ?>,
                            dataLabels: {
                                enabled: true,
                                distance: -10,
                                align: 'right',
                                x: -130,
                                y: 10,
                                formatter: function () {
                                    return <?php echo $sales_amount_by_user; ?>;
                                },
                                style: {
                                    //   fontWeight: 'bold',
                                    //  background: '#68CD64',
                                    //   textShadow: '0px 1px 2px black'
                                }
                            },
                        },
<?php
//$sales_percentage=20;
if ($sales_percentage < 100) {
    ?>
                            ['Firefox',<?php echo ($sales_percentage < 100) ? 100 - $sales_percentage : 0; ?>],
<?php } ?>

                    ],
                }]
        });
        $('#pie2').highcharts({
            chart: {
                type: 'pie',
                marginBottom: 70

            },
            colors: ['#F39200', '#DDDDDD'],
            credits: {
                enabled: false
            },
            title: {
                text: '<?php echo lang("sales_forecast"); ?>'

//                verticalAlign: 'bottom',
//                y: 50
                        //margin:7
            },
            tooltip: {
                //pointFormat: '',//{series.name}: <b>{point.percentage}%</b>
                //percentageDecimals: 1
                formatter: function () {
                    return false;
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: false,
                    cursor: 'normal',
                    dataLabels: {
                        enabled: false,
                        formatter: function () {
                            return this.y;
                        },
                        distance: -30
                    },
                    shadow: false,
                    center: ['50%'],
                    showInLegend: false,
                    point: {
                        events: {
                            mouseOver: function () {
                                $('div.' + this.name).addClass('highlight');
                            },
                            mouseOut: function () {
                                $('div.' + this.name).removeClass('highlight');
                            }
                        }
                    }
                }
            }, series: [{
                    type: 'pie',
                    innerSize: '50%',
                    data: [
                        {
                            name: 'Others',
                            y: <?php echo $salesForecast; ?>,
                            dataLabels: {
                                enabled: true,
                                distance: -10,
                                align: 'right',
                                x: -130,
                                y: 10,
                                formatter: function () {
                                    return <?php echo $salesForecast; ?>;
                                },
                                style: {
                                    //   fontWeight: 'bold',
                                    //  color: 'white',
                                    //   textShadow: '0px 1px 2px black'
                                }
                            },
                        },
<?php
//$sales_percentage=20;
if ($salesForecast < 100) {
    ?>
                            ['Firefox',<?php echo ($salesForecast < 100) ? 100 - $salesForecast : 0; ?>],
<?php } ?>
                    ],
                }]
        });
    });</script>

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
                //console.log(placeholderDiv);
//                if (ui.item.attr('id') == 'position-left-top' )
//                {
//                    return false;
//                }

                $('#' + $(this).attr('id') + ' .sortableDiv').each(function () {
                    sortorder.push($(this).attr('id'));
                });
                if (placeholderDiv == 'sectionLeft')
                {
                    var placeholder2 = 'sectionRight';
                }
                else
                {
                    var placeholder2 = 'sectionLeft';
                }
                $('#' + placeholder2 + ' .sortableDiv').each(function () {
                    sortorder2.push($(this).attr('id'));
                });
//                $.ajax({
//                    url: "<?php echo base_url('Dashboard/dashboardWidgetsOrder'); ?>",
//                    type: "POST",
//                    dataType: "json",
//                    data: {'placeholder1': placeholderDiv, 'innerWidgets1': sortorder, 'placeholder2': placeholder2, 'innerWidgets2': sortorder2},
//                    success: function (data)
//                    {
//                        if (data.type == 'reset')
//                        {
//                            window.location.href = window.location.href;
//
//                        }
//                        else
//                        {
//                            $('#pie1').highcharts({
//                                chart: {
//                                    type: 'pie',
//                                    marginBottom: 70
//                                },
//                                colors: ['#68CD64', '#C9C9C9'],
//                                credits: {
//                                    enabled: false
//                                },
//                                title: {
//                                    text: 'Sales Target'
//                                            //  verticalAlign: 'bottom',
//                                            // y: 50
//                                            //margin:7
//                                },
//                                tooltip: {
//                                    //pointFormat: '',//{series.name}: <b>{point.percentage}%</b>
//                                    //percentageDecimals: 1
//                                    formatter: function () {
//                                        return false;
//                                    }
//                                },
//                                plotOptions: {
//                                    pie: {
//                                        allowPointSelect: false,
//                                        cursor: 'normal',
//                                        dataLabels: {
//                                            enabled: false,
//                                            formatter: function () {
//                                                return this.y;
//                                            },
//                                            distance: -30
//                                        },
//                                        shadow: false,
//                                        center: ['50%'],
//                                        showInLegend: false,
//                                        line: {
//                                            dataLabels: {
//                                                enabled: true
//                                            },
//                                            enableMouseTracking: true
//                                        }
////                    point: {
////                        events: {
////                            mouseOver: function () {
////                                $('div.' + this.name).addClass('highlight');
////                            },
////                            mouseOut: function () {
////                                $('div.' + this.name).removeClass('highlight');
////                            }
////                        }
////                    }
//                                    }
//                                },
//                                series: [{
//                                        type: 'pie',
//                                        innerSize: '50%',
//                                        data: [
//                                            ['Firefox', 100], {
//                                                name: 'Others',
//                                                y: 1000,
//                                                dataLabels: {
//                                                    enabled: true
//                                                }
//                                            }]
//                                    }]
//                            });
//                            $('#pie2').highcharts({
//                                chart: {
//                                    type: 'pie',
//                                    marginBottom: 70
//
//                                },
//                                colors: ['#F39200', '#C9C9C9'],
//                                credits: {
//                                    enabled: false
//                                },
//                                title: {
//                                    text: 'Sales Forecast'
////                verticalAlign: 'bottom',
////                y: 50
//                                            //margin:7
//                                },
//                                tooltip: {
//                                    //pointFormat: '',//{series.name}: <b>{point.percentage}%</b>
//                                    //percentageDecimals: 1
//                                    formatter: function () {
//                                        return false;
//                                    }
//                                },
//                                plotOptions: {
//                                    pie: {
//                                        allowPointSelect: false,
//                                        cursor: 'normal',
//                                        dataLabels: {
//                                            enabled: false,
//                                            formatter: function () {
//                                                return this.y;
//                                            },
//                                            distance: -30
//                                        },
//                                        shadow: false,
//                                        center: ['50%'],
//                                        showInLegend: false,
//                                        point: {
//                                            events: {
//                                                mouseOver: function () {
//                                                    $('div.' + this.name).addClass('highlight');
//                                                },
//                                                mouseOut: function () {
//                                                    $('div.' + this.name).removeClass('highlight');
//                                                }
//                                            }
//                                        }
//                                    }
//                                }, series: [{
//                                        type: 'pie',
//                                        innerSize: '50%',
//                                        data: [
//                                            ['Firefox', 100], {
//                                                name: 'Others',
//                                                y: 1000,
//                                                dataLabels: {
//                                                    enabled: true
//                                                }
//                                            }]
//                                    }]
//                            });
//                            $('#container').highcharts({
//                                chart: {
//                                    type: 'line'
//                                },
//                                credits: {
//                                    enabled: false
//                                },
//                                title: {
//                                    text: 'Monthly Sales Comparision'
//                                },
//                                subtitle: {
//                                    text: ''
//                                },
//                                xAxis: {
//                                    categories: false
//                                },
//                                yAxis: {
//                                    title: {
//                                        text: 'Monthly Comparision'
//                                    }
//                                },
//                                plotOptions: {
//                                    line: {
//                                        dataLabels: {
//                                            enabled: true
//                                        },
//                                        enableMouseTracking: true
//                                    }
//                                },
//                                series: [{
//                                        name: 'Feb',
//                                        data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
//                                    }, {
//                                        name: 'Jan',
//                                        data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
//                                    }]
//                            });
//                        }
//
//                    }
//
//                });
                /*Pass sortorder variable to server using ajax to save state*/
            },
            receive: function (event, ui) {
                var group = ui
                var placeholderDiv = $(this).attr('id');
                var sortorder = [];
                var sortorder2 = [];
                console.log(placeholderDiv);
                if (ui.item.attr('id') == 'position-left-top' && placeholderDiv == 'sectionRight')
                {
                    $('#position-left-top').html($('#graph-right').html());
                }
                else
                {
                    $('#position-left-top').html($('#graph-left').html());
                }
//             
                $('#' + $(this).attr('id') + ' .sortableDiv').each(function () {
                    sortorder.push($(this).attr('id'));
                });
                if (placeholderDiv == 'sectionLeft')
                {
                    var placeholder2 = 'sectionRight';
                }
                else
                {
                    var placeholder2 = 'sectionLeft';
                }
                $('#' + placeholder2 + ' .sortableDiv').each(function () {
                    sortorder2.push($(this).attr('id'));
                });
                $.ajax({
                    url: "<?php echo base_url('Dashboard/dashboardWidgetsOrder'); ?>",
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
                            $('#pie1').highcharts({
                                chart: {
                                    type: 'pie',
                                    marginBottom: 70
                                },
                                colors: ['#68CD64', '#DDDDDD'],
                                credits: {
                                    enabled: false
                                },
                                title: {
                                    text: '<?php echo lang("sales_target"); ?>'
                                            //  verticalAlign: 'bottom',
                                            // y: 50
                                            //margin:7
                                },
                                tooltip: {
                                    //pointFormat: '',//{series.name}: <b>{point.percentage}%</b>
                                    //percentageDecimals: 1
                                    formatter: function () {
                                        return false;
                                    }
                                },
                                plotOptions: {
                                    pie: {
                                        allowPointSelect: false,
                                        cursor: 'normal',
                                        dataLabels: {
                                            enabled: false,
                                            formatter: function () {
                                                return this.y;
                                            },
                                            distance: -30
                                        },
                                        shadow: false,
                                        center: ['50%'],
                                        showInLegend: false,
                                        line: {
                                            dataLabels: {
                                                enabled: true
                                            },
                                            enableMouseTracking: true
                                        }
//                    point: {
//                        events: {
//                            mouseOver: function () {
//                                $('div.' + this.name).addClass('highlight');
//                            },
//                            mouseOut: function () {
//                                $('div.' + this.name).removeClass('highlight');
//                            }
//                        }
//                    }
                                    }
                                },
                                series: [{
                                        startAngle: 0,
                                        type: 'pie',
                                        innerSize: '50%',
                                        data: [
                                            {
                                                name: 'Others',
                                                y: <?php echo $sales_percentage; ?>,
                                                dataLabels: {
                                                    enabled: true,
                                                    distance: -10,
                                                    align: 'right',
                                                    x: -130,
                                                    y: 10,
                                                    formatter: function () {
                                                        return <?php echo $sales_amount_by_user; ?>;
                                                    },
                                                    style: {
                                                        //   fontWeight: 'bold',
                                                        //  background: '#68CD64',
                                                        //   textShadow: '0px 1px 2px black'
                                                    }
                                                },
                                            },
<?php
//$sales_percentage=20;
if ($sales_percentage < 100) {
    ?>
                                                ['Firefox',<?php echo ($sales_percentage < 100) ? 100 - $sales_percentage : 0; ?>],
<?php } ?>

                                        ],
                                    }]
                            });
                            $('#pie2').highcharts({
                                chart: {
                                    type: 'pie',
                                    marginBottom: 70

                                },
                                colors: ['#F39200', '#DDDDDD'],
                                credits: {
                                    enabled: false
                                },
                                title: {
                                    text: '<?php echo lang('sales_forecast'); ?>'

//                verticalAlign: 'bottom',
//                y: 50
                                            //margin:7
                                },
                                tooltip: {
                                    //pointFormat: '',//{series.name}: <b>{point.percentage}%</b>
                                    //percentageDecimals: 1
                                    formatter: function () {
                                        return false;
                                    }
                                },
                                plotOptions: {
                                    pie: {
                                        allowPointSelect: false,
                                        cursor: 'normal',
                                        dataLabels: {
                                            enabled: false,
                                            formatter: function () {
                                                return this.y;
                                            },
                                            distance: -30
                                        },
                                        shadow: false,
                                        center: ['50%'],
                                        showInLegend: false,
                                        point: {
                                            events: {
                                                mouseOver: function () {
                                                    $('div.' + this.name).addClass('highlight');
                                                },
                                                mouseOut: function () {
                                                    $('div.' + this.name).removeClass('highlight');
                                                }
                                            }
                                        }
                                    }
                                }, series: [{
                                        type: 'pie',
                                        innerSize: '50%',
                                        data: [
                                            {
                                                name: 'Others',
                                                y: <?php echo $salesForecast; ?>,
                                                dataLabels: {
                                                    enabled: true,
                                                    distance: -10,
                                                    align: 'right',
                                                    x: -130,
                                                    y: 10,
                                                    formatter: function () {
                                                        return <?php echo $salesForecast; ?>;
                                                    },
                                                    style: {
                                                        //   fontWeight: 'bold',
                                                        //  color: 'white',
                                                        //   textShadow: '0px 1px 2px black'
                                                    }
                                                },
                                            },
<?php
//$sales_percentage=20;
if ($salesForecast < 100) {
    ?>
                                                ['Firefox',<?php echo ($salesForecast < 100) ? 100 - $salesForecast : 0; ?>],
<?php } ?>
                                        ],
                                    }]
                            });
                            $('#container').highcharts({
                                chart: {
                                    type: 'line'
                                },
                                credits: {
                                    enabled: false
                                },
                                title: {
                                    text: '<?php echo lang("monthly_sales_comparision"); ?>'
                                },
                                subtitle: {
                                    text: ''
                                },
                                xAxis: {
                                    categories: false
                                },
                                yAxis: {
                                    title: {
                                        text: '<?php echo lang("monthly_comparision"); ?>'
                                    }
                                },
                                plotOptions: {
                                    line: {
                                        dataLabels: {
                                            enabled: true
                                        },
                                        enableMouseTracking: true
                                    }
                                },
                                series: [{
                                        name: '<?php echo $currentMonthName; ?>',
                                        data: [<?php echo $currentMonthData; ?>]
                                    }, {
                                        name: '<?php echo $nextMonthName; ?>',
                                        data: [<?php echo $nextMonthData; ?>]
                                    }]
                            });
                        }

                    }

                });
            }
        });
    });
    $('body').delegate('.fa-refresh', 'click', function () {
        var delete_meg ="<?php echo lang('dashboard_prompt');?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                        return false;
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        $.ajax({
                            url: "<?php echo base_url('Dashboard/dashboardWidgetsOrder'); ?>/?resetWidgets=Yes",
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
    /*
     * function is used to  for getting monthly comparision
     */
    function getComparisionData(mnt)
    {
        if (mnt != '')
        {
            $.ajax({
                url: "<?php echo base_url('Dashboard/getMonthlySalesData'); ?>",
                data: {'month': mnt},
                type: "POST",
                dataType: 'json',
                success: function (d)
                {
                    $('#container').highcharts({
                        chart: {
                            type: 'line'
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '<?php echo lang("monthly_sales_comparision"); ?>'
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: false
                        },
                        yAxis: {
                            title: {
                                text: '<?php echo lang("monthly_comparision"); ?>'
                            }
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: true
                                },
                                enableMouseTracking: true
                            }
                        },
                        series: [{
                                name: d.currMonth,
                                data: (d.currMonthData)
                            }, {
                                name: d.nextMonth,
                                data: d.nextMonthData
                            }]
                    });
                }

            })
        }
    }
</script>

