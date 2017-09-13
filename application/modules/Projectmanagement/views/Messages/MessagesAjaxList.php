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

    <table id="Messagestable1" class="table table-striped dataTable" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th <?php
                if (isset($sortfield) && $sortfield == 'message') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
                    onclick="apply_sorting('message', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('messages') ?>
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
                    onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('time') ?>
                </th>

                <?php if (checkPermission('Messages', 'delete')) { ?>
                    <th><?= lang('actions') ?></th>
                <?php } ?>
            </tr>
        </thead>

        <tbody>
            <?php if (isset($message_data) && count($message_data) > 0) { ?>
                <?php foreach ($message_data as $message_data) { ?>
                    <tr>
                        <td><?= !empty($message_data['message']) ? '<b>' . $message_data['username'] . ' :</b> ' . $message_data['message'] : '' ?></td>
                        <td><?php echo configDateTime($message_data['created_date']); ?></td>
                        <?php if (checkPermission('Messages', 'delete')) { ?>
                            <td>
                                <?php if (checkPermission('Messages', 'delete')) { ?><a href="javascript:void(0);"
                                   onclick="deleteItem(<?php echo $message_data['message_id']; ?>)" title="<?php echo lang('delete');?>">
                                        <i class="fa fa-remove redcol"></i></a><?php } ?>
                            </td>
                        <?php } ?>
                    </tr>

                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="2" class="text-center">
                        <?= lang('common_no_record_found') ?>
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
        var delete_meg ="<?php echo lang('message_delete_message'); ?>";
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
                        window.location.href = "<?php echo base_url('Projectmanagement/Messages/delete_record/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });


    }
</script>