   <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 connectedSortable" id="projectStatWidget">
        <div class="whitebox pad-6 pm-dbbox-height">
            <h4>
                <div class="col-sm-6">
                    <!-- <select class="form-control">
                      <option>Development</option>
                    </select> -->
                </div>
                <div class="col-sm-6"> <?= !empty($completed_status) ? $completed_status : '0' ?>% <?=lang('completed_status')?></div>
                <div class="clr"></div>
            </h4>
            <div class="grayline-1"></div>
            <div class="text-center">
                <div id="completed_status_chart"><?= !empty($pie_completed_task_str) ? "" : lang('no_data') ?></div>
            </div>
        </div>
    </div>