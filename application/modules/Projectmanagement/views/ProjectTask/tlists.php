
<div class="innersortablediv row" id="tlists" style="min-height: 200px">
  <div class=" mar_b6">
    <div class="col-xs-12 col-sm-12 col-md-12">
      <?php if (checkPermission("Milestone", "add")) { ?>
      <a
                            data-href="<?= base_url() ?>Projectmanagement/Milestone/add_record/home" data-toggle="ajaxModal"
                            aria-hidden="true" title="" class="btn  add_task add_record btn-white" > +
      <?= lang('add_milestone') ?>
      </a>
      <?php } ?>
      &nbsp;&nbsp;
      <?php if (checkPermission("ProjectTask", "add")) { ?>
      <a
                            data-href="<?= base_url() ?>Projectmanagement/ProjectTask/add_record" data-toggle="ajaxModal"
                            data-refresh="true" aria-hidden="true" title="" class="btn  add_task add_record btn-white" > +
      <?= lang('create_task') ?>
      </a>
      <?php } ?>
      &nbsp;&nbsp;
      <?php if (checkPermission("ProjectTask", "add")) { ?>
      <a
                            data-href="<?= base_url() ?>Projectmanagement/ProjectTask/add_subtask" data-toggle="ajaxModal"
                            data-refresh="true" aria-hidden="true" title="" class="btn  add_task add_record btn-white" > +
      <?= lang('create_sub_task') ?>
      </a>
      <?php } ?>
      &nbsp;&nbsp; </div>
    <div class="clr"></div>
  </div>
  <div class="">
    <div class="col-xs-12 col-sm-6 col-md-6">
      <div class="row">
        <div class="form-group">
          <div class="col-xs-1 col-sm-2 col-md-2 col-lg-2">
            <label class="white-link pad-t6"><?=lang('CR_FILTER_BTN')?></label>
          </div>
          <div class="col-xs-4 col-sm-4 col-md-4">
            <select class="form-control" name="change_status" id="change_status">
              <option value=""><?=lang('select_status')?></option>
              <?php
                                    if (!empty($project_status)) {
                                        foreach ($project_status as $row) {
                                            ?>
              <option <?php
                                            if (!empty($change_status) && $row['status_id'] == $change_status) {
                                                echo 'selected="selected';
                                            }
                                            ?> value="<?= $row['status_id'] ?>">
              <?= $row['status_name'] ?>
              </option>
              <?php
                                            }
                                        }
                                        ?>
            </select>
          </div>
          <div class="col-xs-5 col-sm-4 col-md-5">
            <select name="team_member" id="team_member" class="form-control">
              <option value=""><?=lang('select_team_member')?></option>
              <?php
                                    if (!empty($team_members)) {
                                        foreach ($team_members as $row) {
                                          if(!empty($row['login_id'])){
                                            ?>
                                          }
              <option <?php
                                            if (!empty($team_member) && $row['login_id'] == $team_member) {
                                                echo 'selected="selected';
                                            }
                                            ?> value="<?= $row['login_id'] ?>">
              <?= $row['name'] ?>
              </option>
              <?php }
                                            }
                                        }
                                        ?>
            </select>
          </div>
          <div class="clr"></div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 text-right ">
      <div class="btn-group select-view">
        <button id="listview" title="<?=lang('list_view')?>" value="1"
                                class="change_view btn btn-white <?php
                                if (!empty($view) && $view == 1) {
                                    echo 'focus';
                                }
                                ?>"><i class="fa fa-list"></i></button>
        <button id="agileview" title="<?=lang('agile_view')?>" value="2"
                                class="change_view btn btn-white <?php
                                if (!empty($view) && $view == 2) {
                                    echo 'focus';
                                }
                                ?>"><i class="fa fa-th"></i></button>
        <button id="gantview" title="<?=lang('gantt_view')?>" value="3"
                                class="change_view btn btn-white <?php
                                if (!empty($view) && $view == 3) {
                                    echo 'focus';
                                }
                                ?>"><i class="fa fa-bar-chart"></i></button>
      </div>
    </div>
    <div class="clr"></div>
  </div>
  <div class="">
    <div class="col-xs-12 col-md-12">
      <div id="list_view"> <?php echo $this->load->view('List_view'); ?> </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
</div>
