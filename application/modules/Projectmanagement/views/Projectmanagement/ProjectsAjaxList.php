<?php
//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table table-responsive">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>"/>
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>"/>
    <input type="hidden" id="uri_segment" name="uri_segment"
           value="<?php if (isset($uri_segment)) echo $uri_segment; ?>"/>

    <table id="projecttable1" class="table table-striped dataTable" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th
                <?php
                if (isset($sortfield) && $sortfield == 'project_code') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                   onclick="apply_sorting('project_code', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('project_code') ?>
            </th>
            <th
                <?php
                if (isset($sortfield) && $sortfield == 'project_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                   onclick="apply_sorting('project_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('project_name') ?>
            </th>
            <th
                <?php
                if (isset($sortfield) && $sortfield == 'prospect_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                   onclick="apply_sorting('prospect_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('client') ?>
            </th> 
            <th
                <?php
                if (isset($sortfield) && $sortfield == 'employee_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" width="20%" aria-controls="example1" rowspan="1" colspan="1"
                   onclick="apply_sorting('employee_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('employee') ?>
            </th>
            <th
                <?php
                if (isset($sortfield) && $sortfield == 'start_date') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                   onclick="apply_sorting('start_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('start_date') ?>
            </th>

            <th
                <?php
                if (isset($sortfield) && $sortfield == 'due_date') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                   onclick="apply_sorting('due_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('due_date') ?>
            </th>

            <th><?= lang ('icon') ?></th>
            <?php if (checkPermission ('Projectmanagement', 'edit') || checkPermission ('Projectmanagement', 'delete')  || checkPermission ("Projectmanagement", 'view')) { ?>
                <th><?= lang ('actions') ?></th>
            <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php if (isset($project_details) && count ($project_details) > 0) { ?>
            <?php foreach ($project_details as $project_details) { ?>
                <tr>
                    <td class="col-md-1"><?= !empty($project_details['project_code']) ? $project_details['project_code'] : '' ?></td>
                    <td class="col-md-2">
                    <?php if (checkPermission ('ProjectTask', 'view')) { ?>
                    <a href="<?= base_url () ?>Projectmanagement/select_project/<?= $project_details['project_id'] ?>"><?= !empty($project_details['project_name']) ? $project_details['project_name'] : '' ?></a>
                    <?php } else { ?>
                        <?= !empty($project_details['project_name']) ? $project_details['project_name'] : '' ?>
                    <?php }  ?>
                    </td>
                    <td class="col-md-2"><?= !empty($project_details['prospect_name']) ? $project_details['prospect_name'] : '' ?></td> 
                    <td class="col-md-2"><?= !empty($project_details['employee_name']) ? $project_details['employee_name'] : '' ?></td>
                    <td><?php if ($project_details['start_date'] != '0000-00-00') {
                            echo configDateTime($project_details['start_date']);
                        } ?></td>
                    <td><?php if ($project_details['due_date'] != '0000-00-00') {
                            echo configDateTime($project_details['due_date']);
                        } ?></td>
                    
                        <td  class="col-md-1"><?php if (!empty($project_details['project_icon']) && file_exists ($project_details['icon_path'] . '/' . $project_details['project_icon'])) { ?>
                                <img alt="No Image"
                                     src="<?= base_url () ?><?= $project_details['icon_path'] ?>/<?= $project_details['project_icon'] ?>"
                                     height="80" width="80">
                            <?php } else { ?><img alt="No Image" src="<?= base_url () ?>uploads/images/noimage.jpg"
                                                  height="80" width="80"><?php } ?></td>
                        <?php if (checkPermission ('Projectmanagement', 'edit') || checkPermission ('Projectmanagement', 'delete')  || checkPermission ("Projectmanagement", 'view')) { ?>
                        <td class="bd-actbn-btn">
                            <?php if (checkPermission ("Projectmanagement", 'view')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/view_record/<?= $project_details['project_id'] ?>"
                                data-toggle="ajaxModal" aria-hidden="true"title="<?= lang ('view') ?>"
                                class=""><i class="fa fa-search greencol"></i></a><?php } ?>
                            <?php if (checkPermission ('Projectmanagement', 'edit')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/edit_record/<?= $project_details['project_id'] ?>"
                                data-toggle="ajaxModal" title="<?= lang ('edit') ?>" class=""><i
                                        class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if (checkPermission ('Projectmanagement', 'delete')) { ?><a href="javascript:void(0);" title="<?php echo lang('delete');?>"
                                                                                              onclick="deleteItem(<?php echo $project_details['project_id']; ?>)">
                                    <i class="fa fa-remove redcol"></i></a><?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="7" class="text-center">
                    <?= lang ('common_no_record_found') ?>
                </td>

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
<script>
    function deleteItem(id) {
        var delete_meg ="<?php echo lang('project_delete_message'); ?>";
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
                        window.location.href = "<?php echo base_url('Projectmanagement/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });
        }
</script>