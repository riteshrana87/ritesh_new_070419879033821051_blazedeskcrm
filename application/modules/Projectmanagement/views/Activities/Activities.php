<?php
defined ('BASEPATH') OR exit('No direct script access allowed');
?>

<div id="main-page">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-7 col-lg-7">
     
        <div class="form-group row">
          <div class="col-md-1 white-link pad-t6 col-xs-2">
            <label class="white-link"><?=lang('CR_FILTER_BTN')?></label>
          </div>
          <div class="col-md-3 col-xs-4">
            <select class="form-control filter" data-field="pa.status_id" id="filter_status" name="filter[]" onchange="data_group('pa.status_id', this.value);">
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
          <div class="col-md-4 col-xs-6">
            <select tabindex="-1" id="filter_user" data-field="pa.user_id"  name="filter[]" class="form-control filter" onchange="data_group('pa.user_id', this.value);" data-placeholder="Choose a user">
                <option value=""><?=lang('select_team_member')?></option>
                <?php
                                      if (!empty($team_members)) {
                                          foreach ($team_members as $row) {
                                              ?>
                <option <?php
                                              if (!empty($team_member) && $row['login_id'] == $team_member) {
                                                  echo 'selected="selected';
                                              }
                                              ?> value="<?= $row['login_id'] ?>">
                <?= $row['name'] ?>
                </option>
                <?php
                                              }
                                          }
                                          ?>
              </select>
          </div>
          <div class="clr"></div>
        </div>

    </div>

    </div>
    <div class="clr"></div>
    <?php echo $this->session->flashdata ('msg'); ?>
    <div class="clr"></div>
    <div class="whitebox" id="common_div">

        <?php $this->load->view ('ActivitiesAjaxList') ?>
    </div>
    <div class="clr"></div>

</div>
