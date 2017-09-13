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


    <table id="milestonetable1" class="table table-striped dataTable" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th <?php
            if (isset($sortfield) && $sortfield == 'title') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc col-md-2'";
                } else {
                    echo "class = 'sorting_asc col-md-2'";
                }
            } else {
                echo "class = 'sorting col-md-2'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('title', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('title') ?>
            </th>
            <th  <?php
            if (isset($sortfield) && $sortfield == 'incident_type_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc col-md-2'";
                } else {
                    echo "class = 'sorting_asc col-md-2'";
                }
            } else {
                echo "class = 'sorting col-md-2'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('incident_type_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('type') ?>
            </th>
            
            <th <?php
            if (isset($sortfield) && $sortfield == 'responsible') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc col-md-2'";
                } else {
                    echo "class = 'sorting_asc col-md-2'";
                }
            } else {
                echo "class = 'sorting col-md-2'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('responsible', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('RESPONSIBLE') ?>
            </th>

            <th <?php
            if (isset($sortfield) && $sortfield == 'deadline') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc col-md-1'";
                } else {
                    echo "class = 'sorting_asc col-md-1'";
                }
            } else {
                echo "class = 'sorting col-md-1'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('deadline', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('DEADLINE') ?>
            </th>
            <th  <?php
            if (isset($sortfield) && $sortfield == 'incident_status') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc col-md-2'";
                } else {
                    echo "class = 'sorting_asc col-md-2'";
                }
            } else {
                echo "class = 'sorting col-md-2'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('incident_status', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('INCIDENT_STATUS') ?>
            </th>
            <th <?php
            if (isset($sortfield) && $sortfield == 'created_date') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc col-md-1'";
                } else {
                    echo "class = 'sorting_asc col-md-1'";
                }
            } else {
                echo "class = 'sorting col-md-1'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('create_date') ?>
            </th>
            <?php if (checkPermission ('ProjectIncidents', 'edit') || checkPermission ('ProjectIncidents', 'delete') || checkPermission ("ProjectIncidents", 'view')) { ?>
                <th><?= lang ('actions') ?></th>
            <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php if (isset($incidents_data) && count ($incidents_data) > 0) { ?>
            <?php foreach ($incidents_data as $incidents_data) { ?>
                <tr>
                    <td><?= !empty($incidents_data['title']) ? $incidents_data['title'] : '' ?></td>
                    <td><?= !empty($incidents_data['incident_type_name']) ? $incidents_data['incident_type_name'] : '' ?></td>
                    
                    <td><?= !empty($incidents_data['responsible_name']) ? $incidents_data['responsible_name'] : '' ?></td>
                    <td><?php if (isset($incidents_data['deadline']) && $incidents_data['deadline'] != '0000-00-00 00:00:00') {
                            echo configDateTime($incidents_data['deadline']);
                        }; ?>
                    </td>
                    <td>
                    <?php
                        if($incidents_data['incident_status'] == '1')
                        {
                            echo "In Process";
                        }else if($incidents_data['incident_status'] == '2')
                        {
                            echo "On Hold";
                        }
                    ?>
                        
                    </td>
                    <td><?php if (isset($incidents_data['created_date']) && $incidents_data['created_date'] != '0000-00-00 00:00:00') {
                            echo configDateTime($incidents_data['created_date']);
                        }; ?></td>
                    <?php if (checkPermission ('ProjectIncidents', 'edit') || checkPermission ('ProjectIncidents', 'delete') || checkPermission ("ProjectIncidents", 'view')) { ?>
                        <td class="bd-actbn-btn">
                            <?php if (checkPermission ("ProjectIncidents", 'view')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/ProjectIncidents/view_record/<?= $incidents_data['incident_id'] ?>"
                                data-toggle="ajaxModal" aria-hidden="true"title="<?= lang ('view') ?>"
                                class=""><i class="fa fa-search greencol"></i></a><?php } ?>
                            <?php if (checkPermission ('ProjectIncidents', 'edit')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/ProjectIncidents/edit_record/<?= $incidents_data['incident_id'] ?>"
                                data-toggle="ajaxModal" title="<?= lang ('edit') ?>" class=""><i
                                        class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if (checkPermission ('ProjectIncidents', 'delete')) { ?><a href="javascript:void(0);" title="<?php echo lang('delete');?>"
                               onclick="deleteItem('<?php echo $incidents_data['incident_id']; ?>','<?php echo base_url('Projectmanagement/ProjectIncidents');?>')">
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
    function deleteItem(id,redirect_link) {
        var delete_meg ="<?php echo lang('project_incidents_delete_message'); ?>";
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
                        window.location.href = "<?php echo base_url('Projectmanagement/ProjectIncidents/delete_record'); ?>?incident_id=" + id +"&link="+redirect_link;
                        dialog.close();
                    }
                }]
            });



    }
</script>