<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>         
<div class="table table-responsive">
    <input type="hidden" name="total_account_count" id="total_account_count" value="<?php echo isset($total_account) ? $total_account : 0; ?> ">
    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
        <thead>
            <tr>
                <th <?php if (isset($sortfield) && $sortfield == 'prospect_name')
                    {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('prospect_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('name') ?>
                </th>
                <th <?php if (isset($sortfield) && $sortfield == 'prospect_auto_id')
                    {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('prospect_auto_id', '<?php echo $sorttypepass; ?>')"><?= lang('id') ?>
                </th>
                <th <?php if (isset($sortfield) && $sortfield == 'contact_count')
                    {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_count', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('no_of_contacts') ?>
                </th>
                <th <?php if (isset($sortfield) && $sortfield == 'primary_contact')
                    {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('primary_contact') ?>
                </th>
                <th <?php if (isset($sortfield) && $sortfield == 'creation_date') 
                    {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    } ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('creation_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('client_since') ?>
                </th>
                <th><?= lang('contract_expiration') ?></th>
                <th><?= lang('actions') ?><input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
                </th>
            </tr>
        </thead>
        <tbody>
<?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
    <?php foreach ($prospect_data as $data_prospect) { ?>
                    <tr id="account_row_<?php echo $data_prospect['prospect_id']; ?>">
                        <td class="col-md-2"><?php echo $data_prospect['prospect_name']; ?></td>
                        <td class="col-md-1"><?php echo $data_prospect['prospect_auto_id']; ?></td>
                        <td class="text-center"><?php if ($data_prospect['contact_count']) 
                            {
                                echo $data_prospect['contact_count'];
                            } else {
                                echo '0';
                            } ?>
                        </td>
                        <td class="col-md-2"><?php if ($data_prospect['contact_name'])
                            {
                                echo $data_prospect['contact_name'];
                            } else {
                                echo '';
                            } ?>
                        </td>
                        <td><?php echo configDateTime($data_prospect['creation_date']); ?></td>
                        <td><?php echo lang('client_expiration'); ?></td>
                            <?php if (isset($_SERVER['HTTP_REFERER'])) {
                                $redirect_link = $_SERVER['HTTP_REFERER'];
                            } else {
                                $redirect_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REDIRECT_URL'];
                            }
                            ?>
                        <td class="bd-actbn-btn">
                            <!-- As per client commnet "Cant USe email Button on account since it keeps asking for prospect name in this face it is already a client";-->
                            <?PHP /* if (checkPermission('Account', 'edit')) { ?>
                              <a data-href="<?php echo base_url()."Account/send_email_view/".$data_prospect['prospect_id'];?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('SEND_EMAIL'); ?>">
                              <i class="fa fa-envelope fa-x orangecol"></i>
                              </a><?php } */ ?>


                            <?PHP if (checkPermission('Account', 'view')) { ?><a href="<?= base_url('Account/viewdata/' . $data_prospect['prospect_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                            <?PHP if (checkPermission('Account', 'edit')) { ?><a data-href="<?php echo base_url($account_view . '/editdata/' . $data_prospect['prospect_id']); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_account" id="edit_account"  ><i class="fa fa-pencil fa-x bluecol"></i> </a><?php } ?>
                            <?PHP if (checkPermission('Account', 'delete')) { ?><a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="deleteAccount('<?php echo $data_prospect['prospect_id']; ?>', '<?php echo $redirect_link; ?>');" ><i class="fa fa-remove fa-x redcol"></i></a><?php } ?>
                        </td>
                    </tr>
    <?php } ?>
<?php } else { ?>
                    <tr>
                        <td colspan="8" class="text-center"><?= lang('common_no_record_found') ?></td>
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

    function deleteAccount(prospect_id, redirect_link)
    {
        var delete_url = "<?php echo base_url('Account/deletedata/?id='); ?>" + prospect_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo lang('confirm_delete_client'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                window.location.href = delete_url;
                                dialog.close();
                            }
                        }]
                });


    }
    function delete_request(prospect_id, redirect_link) {

        var delete_url = "<?php echo base_url('Account/deletedata/?id='); ?>" + prospect_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo lang('confirm_delete_client'); ?>";
        BootstrapDialog.show(
                {
                    title: '<?php echo $this->lang->line('Information'); ?>',
                    message: delete_meg,
                    buttons: [{
                            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                            action: function (dialog) {
                                dialog.close();
                            }
                        }, {
                            label: '<?php echo $this->lang->line('ok'); ?>',
                            action: function (dialog) {
                                window.location.href = delete_url;
                                dialog.close();
                            }
                        }]
                });
    }
</script>