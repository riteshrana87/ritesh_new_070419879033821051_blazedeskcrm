<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>

<div class="table table-responsive">
    <input type="hidden" name="total_company_count" id="total_company_count" value="<?php echo isset($total_company) ? $total_company : 0; ?> ">
    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
        <thead>
            <tr>
                <th <?php if (isset($sortfield) && $sortfield == 'company_name') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('company_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('company_name') ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'email_id') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('email_id', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('email') ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'phone_no') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('phone_no', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('contact_no') ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'website') {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('website', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('website') ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'status') {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('status') ?></th>
                <th tabindex="0" aria-controls="example1" rowspan="1" colspan="1"> <?= lang('actions') ?>
                    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                </th>
            </tr>
        </thead>
        <tbody id="postList">
<?php if (isset($company_data) && count($company_data) > 0) { ?>
    <?php foreach ($company_data as $data) { ?>
                    <tr>
                        <td class="col-md-2"><?php echo ucfirst($data['company_name']); ?></td>
                        <td class="col-md-2"><?php echo ucfirst($data['email_id']); ?></td>
                        <td class="col-md-2"><?php echo $data['phone_no']; ?></td>
                        <td class="col-md-2"><?php echo ucfirst($data['website']); ?></td>
        <?php if ($data['status'] == 1) { ?>
                            <td>Active</td>
        <?php } else { ?>
                            <td>Inactive</td>
        <?php } ?>
                        <td class="bd-actbn-btn">
        <?php if (checkPermission('Company', 'view')) { ?><a data-href="<?= base_url() ?>Company/view/<?php echo $data['company_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?= lang('view') ?>" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
        <?php if (checkPermission('Company', 'edit')) { ?><a data-href="<?= base_url() ?>Company/edit/<?php echo $data['company_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?= lang('edit') ?>" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
        <?php if (checkPermission("Company", 'delete')) { ?><a href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="deleteItem('<?php echo $data['company_id']; ?>');"><i class="fa fa-remove redcol"></i></a><?php } ?>
                        </td>
                    </tr>
    <?php } ?>
<?php } else { ?>
                <tr>
                    <td colspan="8" class="text-center"><?= lang ('common_no_record_found') ?></td>
                </tr>
<?php } ?>

        </tbody>

    </table>
    <div class="clearfix visible-xs-block"></div>
    <div id="common_tb" class="no_of_records">
<?php
if (isset($pagination)) {
    echo $pagination;
}
?>
    </div>
</div>

<script>

    function deleteItem(id)
    {
        var delete_meg ="<?php echo $this->lang->line('company_del_msg');?>";
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
                        window.location.href = "<?php echo base_url('Company/deleteData/'); ?>/" + id;
                        dialog.close();
                    }

                }]
            });

    }

</script>
