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

    <table id="invoicetable1" class="table table-striped dataTable" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th <?php
            if (isset($sortfield) && $sortfield == 'invoice_code') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('invoice_code', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('invoice_code') ?>
            </th>
            <?php /*<th <?php
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
            </th> */?>
            <th
                <?php
                if (isset($sortfield) && $sortfield == 'client_name') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                   onclick="apply_sorting('client_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('client') ?>
            </th>
            <th <?php
            if (isset($sortfield) && $sortfield == 'amount') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('amount', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('cost_amount') ?>
            </th>
            <th <?php
            if (isset($sortfield) && $sortfield == 'total_payment') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('total_payment', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('payment') ?>
            </th>
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
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                    onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('cost_placeholder_createddate') ?>
                </th>
            <?php if (checkPermission ('Invoices', 'edit') || checkPermission ('Invoices', 'delete') || checkPermission ('Invoices', 'view')) { ?>
                <th><?= lang ('actions') ?></th>
            <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php if (isset($invoice_data) && count ($invoice_data) > 0) { ?>
            <?php foreach ($invoice_data as $invoice_data) { ?>
                <tr>
                    <td class="col-md-2"><?= !empty($invoice_data['invoice_code']) ? $invoice_data['invoice_code'] : '' ?></td>
                    <td class="col-md-2"><?= !empty($invoice_data['client_name']) ? $invoice_data['client_name'] : '' ?></td>                 
                    <td class="col-md-2"><?= !empty($invoice_data['amount']) ? $invoice_data['amount'] : '' ?></td>
                    <td class="col-md-2"><?= !empty($invoice_data['total_payment']) ? $invoice_data['total_payment'] : '' ?></td>
                    <td><?php echo configDateTime($invoice_data['created_date']); ?></td>
                    
                    <?php if (checkPermission ('Invoices', 'edit') || checkPermission ('Invoices', 'delete') || checkPermission ('Invoices', 'view')) { ?>
                        <td class="bd-actbn-btn">
                            <?php if (checkPermission ("Invoices", 'view')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/Invoices/view_record/<?= $invoice_data['invoice_id'] ?>"
                                data-toggle="ajaxModal" aria-hidden="true"title="<?= lang ('view') ?>"
                                class=""><i class="fa fa-search greencol"></i></a><?php } ?>
                            <?php  if (checkPermission ('Invoices', 'edit')) { ?><a
                                data-href="<?= base_url () ?>Projectmanagement/Invoices/edit_record/<?= $invoice_data['invoice_id'] ?>"
                                data-toggle="ajaxModal" title="<?= lang ('edit') ?>" class=""><i
                                        class="fa fa-pencil bluecol"></i></a><?php } ?>
                            <?php if (checkPermission ('Invoices', 'delete')) { ?><a href="javascript:void(0);" title="<?php echo lang('delete');?>"
                                                                                      onclick="deleteItem(<?php echo $invoice_data['invoice_id']; ?>)">
                                    <i class="fa fa-remove redcol"></i></a><?php } ?>
                        </td>
                    <?php } ?>
                </tr>

            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6" class="text-center">
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
        var delete_meg ="<?php echo lang('invoice_delete_message'); ?>";
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
                        window.location.href = "<?php echo base_url('Projectmanagement/Invoices/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });


    }
</script>