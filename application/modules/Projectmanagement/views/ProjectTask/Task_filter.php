
  <div class="col-md-12 col-xs-12 whitebox">
  <div class="col-md-12 col-xs-12">
    <label><b><?= lang('filter_options') ?></b></label>
    
    <div class="form-group">
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
    <div class="form-group">
        <select name="team_member" id="team_member" class="form-control">
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
    <div class="pad-10 text-center">
        <input type="button" value="<?= lang('reset'); ?>" name="reset" class="width-100 btn small-white-btn2" onclick="reset_data()">

    </div>
    </div>
</div>
<div class="clr"></div>