<?php if ($project_viewname != 'TeamMembers') { ?>

<div class="row">
  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-4 pad-tb6">
      <ul class="breadcrumb nobreadcrumb-bg">
        <li><a href="<?= base_url('Projectmanagement/Projectdashboard') ?>">
          <?= lang('project_management') ?>
          </a></li>
        <?php if (!empty($project_viewname)) { ?>
        <li<?php if ($this->uri->segment(2) == $project_viewname) { ?> class="active" <?php } ?>>
          <?= lang(strtolower($project_viewname)) ?>
         </li>
        <?php } ?>
      </ul>
    
  </div>
  <?php if ($project_viewname == 'Projectdashboard') { ?>

      
  <div class="col-xs-12 col-md-1 col-sm-3 pull-right settings text-right" > <a class="pull-right" style="margin-left:15px;" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> <a class="pull-right" title="<?= $this->lang->line('reset_dashboard') ?>"><i class="fa fa-refresh fa-2x"></i></a> </div>
    
  <?php } ?>
  <div class="col-xs-12 col-sm-6 col-md-5 col-lg-3  text-right pull-right">
    <?php if ($project_viewname != 'Projectdashboard') { ?>
    <div class="pad-tb6 ">
      <div class="pull-right settings"> <a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="pull-right search-top">
        <form id="searchForm" class="navbar-form navbar-left pull-right">
          <div class="input-group">
            <input type="text" placeholder="<?= lang('EST_LISTING_SEARCH_FOR') ?>" name="search_input" id="search_input" class="form-control">
            <span class="input-group-btn">
            <button  type="submit" id="submit" name="submit" class="btn btn-default" title="<?= $this->lang->line('search') ?>"><i class="fa fa-search fa-x"></i></button>
            <button onclick="reset_data();" class="btn btn-default" type="reset" title="<?= $this->lang->line('reset') ?>"><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
          <!-- /input-group -->
        </form>
      </div>
    </div>
    <?php } ?>
  </div>
  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 pad-tb6">
    <form class="">
      <div class="form-group bd-form-group row">
       <?php if (checkPermission('ProjectTask', 'view')) { ?>
        <?php if ($project_viewname == 'Projectdashboard') { ?>
        <div class="col-sm-2 col-lg-1 pull-right">
          <button type="button" class="btn btn-success" onClick="goToProjectDashboard();"><?=lang('go')?></button>
        </div>
        <?php } } ?>
        <div class="col-md-6 col-lg-9 col-sm-9 pull-right">
          <select class="form-control" id="projects" onchange="change_project_view('<?= $project_viewname ?>')">
            <?php
                if (!empty($project_data)) {
                    foreach ($project_data as $row) {
                        ?>
                    <option <?= (!empty($cur_project_id) && $cur_project_id == $row['project_id']) ? 'selected="selected"' : '' ?> value="<?= $row['project_id'] ?>">
                    <?= ucfirst($row['project_name']) ?>
                    </option>
                    <?php
                  }
              }
              else
              {
              ?>
              <option value=""><?=lang('no_project_assign')?></option>
              <?php } ?>
          </select>
        </div>
        <label for="inputEmail3" class="pull-right white-link control-label text-right"><?php echo lang('project'); ?> :</label>
        <div class="clr"></div>
      </div>
    </form>
  </div>
  <?php if ($project_viewname == 'TeamMembers') { ?>
  <div class="col-xs-12 col-md-6 pull-right">
    <?php if (checkPermission('TeamMembers', 'add')) { ?>
    <button class="btn btn-white add_record" href="<?php echo base_url('Projectmanagement/editProjectTeam'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('edit_project_team'); ?></button>
    &nbsp;&nbsp;
    <?php } ?>
    <?php if (checkPermission('TeamMembers', 'add')) { ?>
    <a class="btn btn-white add_record" href="<?php echo base_url('Projectmanagement/addTeam'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('add_team'); ?> </a>
    <?php } ?>
    &nbsp;&nbsp;
    <?php if (checkPermission('TeamMembers', 'add')) { ?>
    <a class="btn btn-white add_record" href="<?php echo base_url('Projectmanagement/addTeamMembers'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('add_team_member') ?> </a>
    <?php } ?>
    <?php if (checkPermission('TeamMembers', 'add')) { ?>
    <a class="btn btn-white add_record" href="<?php echo base_url('Projectmanagement/assignProjectManager'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('select_project_manager') ?> </a>
    <?php } ?>
    <div class="clr"></div>
  </div>
  <?php } ?>
  <div class="col-sm-3 col-md-3 nodata">
    <?php if ($project_viewname == 'ProjectTask') { ?>
    <?php
                /* <a href="#" class="btn btn-white">Invoice Project</a>&nbsp;&nbsp; 
                  <a href="#" class="btn btn-white">Finish Project</a>&nbsp;&nbsp;
                  <a href="<?php echo base_url () . 'Projectmanagement/TeamMembers'; ?>" class="btn btn-white">Team Members</a>&nbsp;&nbsp;
                  <a data-href="<?php echo base_url () . 'Projectmanagement/edit_record/'.$cur_project_id.'/task_page'; ?>" data-toggle="ajaxModal" title="Edit Project" class="btn btn-white pull-right" >Edit Project</a>&nbsp;&nbsp;

                  <a data-href="<?php echo base_url () . 'Projectmanagement/view_record/'.$cur_project_id; ?>" data-toggle="ajaxModal" title="Project Information" class="btn btn-white pull-right" >Project Information</a>
                  <div class="clr"></div>
                  <?php */
            }
            ?>
  </div>
  <div class="clr"></div>
</div>
 <?php if ($project_viewname == 'Projectdashboard') { ?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 ">
          <?php if (checkPermission("Projectmanagement", "view")) { ?>
          <a data-href="<?php echo base_url() . 'Projectmanagement/view_record/' . $cur_project_id; ?>" data-toggle="ajaxModal" title=" <?= lang('project_information') ?>" class="btn btn-white " >
          <?= lang('project_information') ?>
          </a>
          <?php } ?>
          <?php if (checkPermission("Projectmanagement", "edit")) { ?>
          <a data-href="<?php echo base_url() . 'Projectmanagement/edit_record/' . $cur_project_id . '/task_page'; ?>" data-toggle="ajaxModal" title="<?= lang('edit_project') ?>" class="btn btn-white " >
          <?= lang('edit_project') ?>
          </a>
          <?php } ?>
          <?php if (checkPermission("TeamMembers", "add")) { ?>
          <a href="<?php echo base_url() . 'Projectmanagement/TeamMembers'; ?>" class="btn btn-white"> <?= lang('team_member') ?></a>
          <?php } ?>
          <?php if (checkPermission("Projectmanagement", "add")) { ?>
          <?php if ($current_project_status == 3) { ?>
          <a href="javascript:;" onclick="markAsFinishedProject('<?php echo $this->session->userdata('PROJECT_ID'); ?>', 0);"  class="btn btn-white">
          <?= lang('reopen_project') ?>
          </a>
          <?php } else { ?>
          <a href="javascript:;" onclick="markAsFinishedProject('<?php echo $this->session->userdata('PROJECT_ID'); ?>', 3);" class="btn btn-white">
          <?= lang('finish') ?>
          <?= lang('project') ?>
          </a>
          <?php } ?>
          <?php } ?>
          <?php if (checkPermission("Invoices", "add")) { ?>
          <a data-href="<?php echo base_url() . 'Projectmanagement/Invoices/add_record/' . $this->project_id; ?>" data-toggle="ajaxModal" class="btn btn-white">
          <?= lang('invoice') ?>
          <?= lang('project') ?>
          </a>
          <?php } ?>
        </div>
</div>
<?php }?>
<?php } else { ?>
<div class="row">
  <div class="col-xs-12 col-sm-6 col-md-3 pad-tb6 dutch-custm-width-20">
    <ul class="breadcrumb nobreadcrumb-bg">
      <li><a href="<?= base_url('Projectmanagement/Projectdashboard') ?>">
        <?= lang('project_management') ?>
        </a></li>
      <?php if (!empty($project_viewname)) { ?>
      <li<?php if ($this->uri->segment(2) == $project_viewname) { ?> class="active" <?php } ?>>
        <?= lang(strtolower($project_viewname)) ?>
       </li>
      <?php } ?>
    </ul>
  </div>
    <div class="col-xs-12 col-md-6 col-lg-6 pull-right pad-tb6 dutch-custm-width-50">
    <div class=" ">
      <?php if ($project_viewname != 'Projectdashboard') { ?>
      <div class="pull-right settings"> <a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
      <?php } ?>
    </div>
    <div class="col-lg-11 col-md-11 col-xs-11 pull-right text-right">
      <?php if (checkPermission('TeamMembers', 'add')) { ?>
      <button class="btn btn-white add_record" href="<?php echo base_url('Projectmanagement/TeamMembers/editProjectTeam'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('edit_project_team'); ?></button>
      &nbsp;&nbsp;
      <?php }?>
      <?php if (checkPermission('TeamMembers', 'add')) { ?>
      <a class="btn btn-white add_record" href="<?php echo base_url('Projectmanagement/TeamMembers/addTeam'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('add_team'); ?> </a>
      <?php } ?>
      &nbsp;&nbsp;
      <?php if (checkPermission('TeamMembers', 'add')) { ?>
      <a class="btn btn-white add_record" href="<?php echo base_url('Projectmanagement/TeamMembers/addTeamMembers'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('add_team_member') ?> </a>
      <?php } ?>
      &nbsp;&nbsp;
      <?php if (checkPermission('TeamMembers', 'add')) { ?>
      <a class="btn btn-white add_record" href="<?php echo base_url('Projectmanagement/TeamMembers/assignProjectManager'); ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true"><?php echo lang('select_project_manager') ?> </a>
      <?php } ?>
      <div class="clr"></div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-lg-4 pad-tb6 dutch-custm-width-20">
    <form class="">
      <div class="form-group bd-form-group row"> 
       
        <div class="col-md-8 col-lg-9 col-sm-9 pull-right">
          <select class="form-control" id="projects" onchange="change_project_view('<?= $project_viewname ?>')">
            <?php
                            if (!empty($project_data)) {
                                foreach ($project_data as $row) {
                                    ?>
            <option <?= (!empty($cur_project_id) && $cur_project_id == $row['project_id']) ? 'selected="selected"' : '' ?> value="<?= $row['project_id'] ?>">
            <?= ucfirst($row['project_name']) ?>
            </option>
            <?php
                                }
                            }
                            ?>
          </select>
        </div>
         <label for="inputEmail3" class="pull-right white-link control-label text-right"><?php echo lang('project'); ?> :</label>
      </div>
    </form>
  </div>

  <div class="clr"></div>
</div>

<?php } ?>
<script type="text/javascript">
    function change_project_view(view_name) {
        //alert(view_name);
        $.ajax({
            type: "POST",
            url: '<?= base_url("Projectmanagement/Projectdashboard/select_project") ?>/' + $('#projects').val(),
            data: {project_id: $('#projects').val()},
            success: function (response)
            {
                $.ajax({
                    type: "POST",
                    url: '<?= base_url("Projectmanagement") ?>/' + view_name,
                    data: {project_id: $('#projects').val(), 'project_ajax': 'ajax'},
                    beforeSend: function () {
                        //     $('#main-page').block({message: '<img src="<?= base_url("uploads/images/ajax-loader.gif") ?>"> Please wait...'});
                    },
                    success: function (html)
                    {
                        $("#main-page").empty();
                        $("#main-page").html(html);
                        if (view_name == 'ProjectTask')
                            $('.tree').treegrid({
                                'initialState': 'collapsed',
                                //'saveState': true,
                            });

                            window.location.href = window.location.href;
                        //  $('#main-page').unblock();
                    }
                });
            }
        });

    }

    //Projectmanagement/select_project/
    function goToProjectDashboard()
    {
        var projectId = $('#projects').val();
        window.location.href = "<?php echo base_url('Projectmanagement/select_project/'); ?>/" + projectId;
    }
    //finished project
    function markAsFinishedProject(projectId, ptype)
    {

        var msg = (ptype == 3) ? "<?php echo lang('project_finish_msg'); ?>" : "<?php echo lang('reopen_this_project'); ?>";
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
<?php /*Following variable related to parsley JS*/?>
