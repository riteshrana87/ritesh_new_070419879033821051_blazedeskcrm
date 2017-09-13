<?php
$sortDefault = '<i class="fa fa-sort"></i>';
$sortAsc     = '<i class="fa fa-sort-desc"></i>';
$sortDesc    = '<i class="fa fa-sort-asc"></i>';
if ($sortOrder == "asc")
    $sortOrder = "desc";
else
    $sortOrder = "asc";
?>

<div class="whitebox">
  <div class="table table-responsive">
    <table id="datatabletask" class="table table-striped  tree dataTable" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class='sortCost col-sm-1 col-lg-2'> <a href="<?php echo base_url (); ?>Projectmanagement/ProjectTask/index/<?= $page ?>/?orderField=task_code&sortOrder=<?= $sortOrder ?>">
            <?php
                        if ($sortOrder == 'asc' && $sortField == 'task_code') {
                            echo $sortAsc;
                        } else if ($sortOrder == 'desc' && $sortField == 'task_code') {
                            echo $sortDesc;
                        } else {
                            echo $sortDefault;
                        }
                        ?>
            <?php echo lang ('task_code') ?> </a> </th>
          <th  class='sortCost col-md-3'> <a href="<?php echo base_url (); ?>Projectmanagement/ProjectTask/index/<?= $page ?>/?orderField=task_name&sortOrder=<?= $sortOrder ?>">
            <?php
                        if ($sortOrder == 'asc' && $sortField == 'task_name') {
                            echo $sortAsc;
                        } else if ($sortOrder == 'desc' && $sortField == 'task_name') {
                            echo $sortDesc;
                        } else {
                            echo $sortDefault;
                        }
                        ?>
            <?php echo lang ('task_name') ?> </a> </th>
          <th  class='sortCost col-sm-2'> <a href="<?php echo base_url (); ?>Projectmanagement/ProjectTask/index/<?= $page ?>/?orderField=user_name&sortOrder=<?= $sortOrder ?>">
            <?php
                        if ($sortOrder == 'asc' && $sortField == 'user_name') {
                            echo $sortAsc;
                        } else if ($sortOrder == 'desc' && $sortField == 'user_name') {
                            echo $sortDesc;
                        } else {
                            echo $sortDefault;
                        }
                        ?>
            <?php echo lang ('employee') ?> </a> </th>
          <?php /*<th class='sortCost'>
                    <a href="<?php echo base_url (); ?>Projectmanagement/ProjectTask/index/<?= $page ?>/?orderField=milestone_name&sortOrder=<?= $sortOrder ?>">
                        <?php
                        if ($sortOrder == 'asc' && $sortField == 'milestone_name') {
                            echo $sortAsc;
                        } else if ($sortOrder == 'desc' && $sortField == 'milestone_name') {
                            echo $sortDesc;
                        } else {
                            echo $sortDefault;
                        }
                        ?><?php echo lang ('milestone_name') ?>
                    </a>
                </th> */?>
          <th class='sortCost'> <a href="<?php echo base_url (); ?>Projectmanagement/ProjectTask/index/<?= $page ?>/?orderField=start_date&sortOrder=<?= $sortOrder ?>">
            <?php
                        if ($sortOrder == 'asc' && $sortField == 'start_date') {
                            echo $sortAsc;
                        } else if ($sortOrder == 'desc' && $sortField == 'start_date') {
                            echo $sortDesc;
                        } else {
                            echo $sortDefault;
                        }
                        ?>
            <?php echo lang ('start_date') ?> </a> </th>
          <th class='sortCost'> <a href="<?php echo base_url (); ?>Projectmanagement/ProjectTask/index/<?= $page ?>/?orderField=due_date&sortOrder=<?= $sortOrder ?>">
            <?php
                        if ($sortOrder == 'asc' && $sortField == 'due_date') {
                            echo $sortAsc;
                        } else if ($sortOrder == 'desc' && $sortField == 'due_date') {
                            echo $sortDesc;
                        } else {
                            echo $sortDefault;
                        }
                        ?>
            <?php echo lang ('due_date') ?> </a> </th>
          <th width="10%" class='sortCost'> <a href="<?php echo base_url (); ?>Projectmanagement/ProjectTask/index/<?= $page ?>/?orderField=status&sortOrder=<?= $sortOrder ?>">
            <?php
                        if ($sortOrder == 'asc' && $sortField == 'status') {
                            echo $sortAsc;
                        } else if ($sortOrder == 'desc' && $sortField == 'status') {
                            echo $sortDesc;
                        } else {
                            echo $sortDefault;
                        }
                        ?>
            <?php echo lang ('status') ?> </a> </th>
          <?php if (checkPermission ("ProjectTask", 'edit') || checkPermission ("ProjectTask", 'delete') || checkPermission ("ProjectTask", 'view')) { ?>
          <th ><?= lang ('actions') ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php if (isset($task_data) && count ($task_data) > 0) { ?>
        <?php foreach ($task_data as $task_data) {
                    ?>
        <tr class="treegrid-<?= $task_data['task_id'] ?> ">
          <td><?= !empty($task_data['task_code']) ? $task_data['task_code'] : '' ?></td>
          <td><?= !empty($task_data['task_name']) ? $task_data['task_name'] : '' ?></td>
          <td><?= !empty($task_data['user_name']) ? $task_data['user_name'] : '' ?></td>
          <?php /*<td><?= !empty($task_data['milestone_name']) ? $task_data['milestone_name'] : '' ?></td> */?>
          <td><?php if ($task_data['start_date'] != '0000-00-00') {
                                echo configDateTime($task_data['start_date']);
                            } ?></td>
          <td><?php if ($task_data['due_date'] != '0000-00-00') {
                                echo configDateTime($task_data['due_date']);
                            } ?></td>
          <td><span class="color_box"
                                  style="background-color:<?= $task_data['status_color'] ?>">
            <?= $task_data['status_name'] ?>
            </span></td>
          <?php if (checkPermission ("ProjectTask", 'edit') || checkPermission ("ProjectTask", 'delete') || checkPermission ("ProjectTask", 'view')) { ?>
          <td class="bd-actbn-btn"><?php
                                $task_type = empty($task_data['sub_task_id']) ? 'edit_record' : 'edit_subtask';
                                ?>
            <?php if (checkPermission ("ProjectTask", 'view')) { ?>
            <a
                                    data-href="<?= base_url () ?>Projectmanagement/ProjectTask/view_record/<?= $task_data['task_id'] ?>"
                                    data-toggle="ajaxModal" aria-hidden="true" title="<?= lang ('view') ?>"
                                    class=""><i class="fa fa-search greencol"></i></a>
            <?php } ?>
            <?php if (checkPermission ("ProjectTask", 'edit')) { ?>
            <a
                                    data-href="<?= base_url () ?>Projectmanagement/ProjectTask/<?= $task_type ?>/<?= $task_data['task_id'] ?>"
                                    data-toggle="ajaxModal" aria-hidden="true" title="<?= lang ('edit') ?>"
                                    class=""><i class="fa fa-pencil bluecol"></i></a>
            <?php } ?>
            <?php if (checkPermission ("ProjectTask", 'delete')) { ?>
            <a href="javascript:void(0);" title="<?php echo lang('delete');?>"
                                                                                            onclick="deleteItem(<?php echo $task_data['task_id']; ?>)" class=""> <i class="fa fa-remove redcol"></i></a>
            <?php } ?></td>
          <?php } ?>
        </tr>
        <?php
                    if(!isset($search_view))
                    {
                    if (!empty($change_status)) {
                        $where = array('pt.is_delete'      => 0,'pt.status'      => $change_status,
                                       'pt.sub_task_id' => $task_data['task_id']);
                    } else {
                        $where = array('pt.is_delete'      => 0,'pt.sub_task_id' => $task_data['task_id']);
                    }
                    if (!empty($team_member)) {
                        $team_array = array('te.user_id' => $team_member);
                        $where      = array_merge ($where, $team_array);
                    }
                    $table         = PROJECT_TASK_MASTER . ' as pt';
                    $join_table    = array(
                        PROJECT_STATUS . ' as ps'         => 'ps.status_id=pt.status',
                        PROJECT_TASK_TEAM_TRAN . ' as te' => 'te.task_id=pt.task_id',
                        LOGIN . ' as l'                   => 'te.user_id=l.login_id');
                    $fields        = array('ps.status_name,ps.status_color,group_concat(l.firstname," ",l.lastname) as user_name,pt.sub_task_id,pt.task_id,pt.description,pt.task_code,pt.task_name,pt.start_date,pt.due_date,pt.status,pt.sub_task_id,pt.deal_id');
                    $group_by      = 'te.task_id';
                    $sub_task_data = $this->common_model->get_records ($table, $fields, $join_table, 'left', $where, '', '', '', '', '', $group_by);
                    if (!empty($sub_task_data)) {
                        foreach ($sub_task_data as $sub_task_data) {
                            $cls1 = !empty($sub_task_data['sub_task_id']) ? 'treegrid-parent-' . $sub_task_data['sub_task_id'] : ''; ?>
        <tr class="<?= $cls1 ?>">
          <td><?= !empty($sub_task_data['task_code']) ? $sub_task_data['task_code'] : '' ?></td>
          <td><?= !empty($sub_task_data['task_name']) ? $sub_task_data['task_name'] : '' ?></td>
          <td><?= !empty($sub_task_data['user_name']) ? $sub_task_data['user_name'] : '' ?></td>
          <?php /* <td><?= !empty($sub_task_data['milestone_name']) ? $sub_task_data['milestone_name'] : '' ?></td> */?>
          <td><?php if ($sub_task_data['start_date'] != '0000-00-00') {
                                    echo configDateTime($sub_task_data['start_date']);
                                } ?></td>
          <td><?php if ($sub_task_data['due_date'] != '0000-00-00') {
                                    echo configDateTime($sub_task_data['due_date']);
                                } ?></td>
          <td><span class="color_box"
                                      style="background-color:<?= $sub_task_data['status_color'] ?>">
            <?= $sub_task_data['status_name'] ?>
            </span></td>
          <?php if (checkPermission ("ProjectTask", 'edit') || checkPermission ("ProjectTask", 'delete') || checkPermission ("ProjectTask", 'view')) { ?>
          <td class="bd-actbn-btn"><?php
                                    $task_type = empty($sub_task_data['sub_task_id']) ? 'edit_record' : 'edit_subtask';
                                    ?>
            <?php if (checkPermission ("ProjectTask", 'view')) { ?>
            <a
                                        data-href="<?= base_url () ?>Projectmanagement/ProjectTask/view_record/<?= $sub_task_data['task_id'] ?>"
                                        data-toggle="ajaxModal" aria-hidden="true" title="<?= lang ('view') ?>"
                                        class=""><i class="fa fa-search greencol"></i></a>
            <?php } ?>
            <?php if (checkPermission ("ProjectTask", 'edit')) { ?>
            <a
                                        data-href="<?= base_url () ?>Projectmanagement/ProjectTask/<?= $task_type ?>/<?= $sub_task_data['task_id'] ?>"
                                        data-toggle="ajaxModal" aria-hidden="true" title="<?= lang ('edit') ?>"
                                        class=""><i class="fa fa-pencil bluecol"></i></a>
            <?php } ?>
            <?php if (checkPermission ("ProjectTask", 'delete')) { ?>
            <a
                                        href="javascript:void(0);"
                                        onclick="deleteItemSub(<?php echo $sub_task_data['task_id']; ?>)" title="<?= lang ('delete') ?>" class=""><i
                                                class="fa fa-remove redcol"></i></a>
            <?php } ?></td>
          <?php }
                        } ?>
        </tr>
        <?php } }?>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td colspan="7" class="text-center"><?= lang ('common_no_record_found') ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div class="clr"></div>
  <div class="row">
    <div class="col-md-12 text-center">
      <div id="common_tb" class="no_of_records">
        <?php
                if (isset($pagination)) {
                    echo $pagination;
                }
                ?>
      </div>
    </div>
  </div>
</div>

<!-- Projewct status and links -->
<?php $this->load->view ('Project_links') ?>
<script>
    function deleteItem(id) {
        var delete_meg ="<?php echo lang('task_delete_message'); ?>";
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
                        window.location.href = "<?php echo base_url('Projectmanagement/ProjectTask/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });

    }
      function deleteItemSub(id) {
        var delete_meg ="<?php echo lang('CONFIRM_DELETE_SUBTASK'); ?>";
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
                        window.location.href = "<?php echo base_url('Projectmanagement/ProjectTask/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });

    }
</script>