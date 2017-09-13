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
                if (isset($sortfield) && $sortfield == 'supplier_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('supplier_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('supplier_name') ?></th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'address') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('address', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('address') ?></th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'created_date') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('created_date') ?></th>
                <th><?php echo lang('actions'); ?></th>

        </thead>
        <tbody>
            <?php if (isset($supplier_data) && count($supplier_data) > 0) { ?>
                <?php foreach ($supplier_data as $supplier_data) { ?>
                    <tr>
                        <td class="col-sm-2"><?php echo!empty($supplier_data['supplier_name']) ? $supplier_data['supplier_name'] : '' ?></td>
                        <td class="col-sm-5"><?= !empty($supplier_data['address']) ? substr($supplier_data['address'],0, 50) : '' ?></td>
                        <td class="col-sm-2"><?php echo!empty($supplier_data['created_date']) ? configDateTime($supplier_data['created_date']) : '' ?></td>
                        <td class="col-sm-2 bd-actbn-btn"> 
                            <?php if (checkPermission('Supplier', 'view')) { ?><a href="<?php echo base_url('Supplier/view/' . $supplier_data['supplier_id']); ?>" data-toggle="ajaxModal" aria-hidden="true" title="<?= lang('view') ?>" ><i class="fa fa-search greencol"></i></a><?php } ?>
                           <?php if (checkPermission('Supplier', 'edit')) { ?><a  href="<?php echo base_url('Supplier/edit/' . $supplier_data['supplier_id']); ?>" data-toggle="ajaxModal" aria-hidden="true" title="<?= lang('edit') ?>" data-refresh="true"><i class="fa fa-pencil bluecol"></i></a><?php }?> 
                            <?php if (checkPermission('Supplier', 'delete')) { ?><a href="javascript:;" onclick="deleteItem(<?php echo $supplier_data['supplier_id']; ?>)" title="<?= lang('delete') ?>"><i class="fa fa-remove redcol"></i></a><?php }?>
                      </td>
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
        var delete_meg ="Are you Sure to delete this item?";

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
                        window.location.href = "<?php echo base_url('Supplier/deletedata/'); ?>/" + id;
                        $('#confirm-id').on('hidden.bs.modal', function () {
                            $('body').addClass('modal-open');
                        });
                        dialog.close();

                    }

                }]
            });

    }
</script>