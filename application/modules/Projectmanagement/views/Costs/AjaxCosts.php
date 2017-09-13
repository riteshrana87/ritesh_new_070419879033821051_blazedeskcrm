<?php
//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>  


<div class="table table-responsive">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
    <input type="hidden" id="uri_segment" name="uri_segment" value="<?php if (isset($uri_segment)) echo $uri_segment; ?>" />

    <table id="example1" class="table table-striped dataTable" role="grid" aria-describedby="example1_info" width="100%">
        <thead>

            <tr>
                <th <?php
                if (isset($sortfield) && $sortfield == 'cost_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('cost_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('cost_name') ?></th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'cost_type') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('cost_type', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('cost_type') ?></th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'ammount') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('ammount', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('cost_amount') ?></th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'supplier_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('supplier_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('cost_supplier') ?></th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'status') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('cost_status') ?></th>
                <?php if (checkPermission ('Costs', 'edit') || checkPermission ('Costs', 'delete') || checkPermission ("Costs", 'view')) { ?>
                <th><?php echo lang('actions'); ?></th>
                <?php } ?>

        </thead>
        <tbody>
            <?php if (isset($cost_data) && count($cost_data) > 0) { ?>
                <?php foreach ($cost_data as $cost_data) { ?>
                    <tr>
                        <td class="col-md-2"><?php echo!empty($cost_data['cost_name']) ? $cost_data['cost_name'] : '' ?></td>
                        <td class="col-md-2"><?php echo!empty($cost_data['cost_type']) ? $cost_data['cost_type'] : '' ?></td>
                        <td class="col-md-1"><?php echo!empty($cost_data['ammount']) ? $cost_data['ammount'] : '' ?></td>
                        <td class="col-md-2"><?php echo!empty($cost_data['supplier_name']) ? $cost_data['supplier_name'] : '' ?></td>
                        <td><?php echo $status[$cost_data['status']]; ?></td>
                        <?php if (checkPermission ('Costs', 'edit') || checkPermission ('Costs', 'delete') || checkPermission ("Costs", 'view')) { ?>
                        <td class="bd-actbn-btn"> 

                            <?php if (checkPermission("Costs", 'view')) { ?><a href="<?php echo base_url($project_view . '/view/' . $cost_data['cost_id']); ?>" data-toggle="ajaxModal" aria-hidden="true"title="<?= lang('view') ?>" class=""><i class="fa fa-search greencol"></i></a><?php } ?>
                            <?php if (checkPermission("Costs", 'edit')) { ?><a class="" href="<?php echo base_url($project_view . '/edit/' . $cost_data['cost_id']); ?>" title="<?php echo lang('edit');?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><i class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if (checkPermission("Costs", 'delete')) { ?><a  class="" href="javascript:;" onclick="deleteItem(<?php echo $cost_data['cost_id']; ?>)" title="<?php echo lang('delete');?>"><i class="fa fa-remove redcol"></i></a><?php } ?>
                        </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>
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
    function deleteItem(id)
    {
        var delete_meg ="<?= lang('delete_cost') ?>";
        BootstrapDialog.show(
            {
                title: '<?php echo $this->lang->line('Information');?>',
                message: delete_meg,
                buttons: [{
                    label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                    action: function(dialog) {
                        dialog.close();
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
                    }
                }, {
                    label: '<?php echo $this->lang->line('ok');?>',
                    action: function(dialog) {
                        window.location.href = "<?php echo base_url('Projectmanagement/Costs/delete/'); ?>/" + id;
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
                        dialog.close();
                    }

                }]
            });


    }
</script>