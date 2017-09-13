  <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 connectedSortable" id="taskStatWidget">
        <div class="whitebox pad-6 pm-dbbox-height">
            <h4><b><?= lang ('task_status') ?></b></h4>

            <div class="grayline-1"></div>
            <div class="text-center">
                <div id="task_status_chart"><?= !empty($pie_chart_data_str) ? "" : "No Data Available" ?></div>
            </div>
        </div>
    </div>