<?php
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>
<div class="table table-responsive">
    <input type="hidden" name="total_opportunity_count" id="total_opportunity_count" value="<?php echo isset($total_opportunity) ? $total_opportunity : 0; ?> ">
    <input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
    <table id="opportunityable1" class="table table-striped dataTable" role="grid">
        <thead>
            <tr>
                <th <?php
if (isset($sortfield) && $sortfield == 'prospect_name') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
}
?> tabindex="0" aria-controls="opportunity" rowspan="1" colspan="1" onclick="apply_sorting('prospect_name', '<?php echo $sorttypepass; ?>')">
                        <?= lang('name') ?>
                </th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'prospect_auto_id') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="opportunity" rowspan="1" colspan="1" onclick="apply_sorting('prospect_auto_id', '<?php echo $sorttypepass; ?>')">
<?= lang('id') ?>        
                </th>
                <th <?php
                if (isset($sortfield) && $sortfield == 'contact_count') {
                    if ($sortby == 'asc') {
                        echo "class = 'sorting_desc'";
                    } else {
                        echo "class = 'sorting_asc'";
                    }
                } else {
                    echo "class = 'sorting'";
                }
                ?> tabindex="0" aria-controls="opportunity" rowspan="1" colspan="1" onclick="apply_sorting('contact_count', '<?php echo $sorttypepass; ?>')">
            <?= lang('no_of_contacts') ?>       
                </th>
                <th <?php
            if (isset($sortfield) && $sortfield == 'contact_name') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="opportunity" rowspan="1" colspan="1" onclick="apply_sorting('contact_name', '<?php echo $sorttypepass; ?>')">
                    <?= lang('contact_name') ?>        
                </th>
                <th <?php
                    if (isset($sortfield) && $sortfield == 'creation_date') {
                        if ($sortby == 'asc') {
                            echo "class = 'sorting_desc'";
                        } else {
                            echo "class = 'sorting_asc'";
                        }
                    } else {
                        echo "class = 'sorting'";
                    }
                    ?> tabindex="0" aria-controls="opportunity" rowspan="1" colspan="1" onclick="apply_sorting('creation_date', '<?php echo $sorttypepass; ?>')">
    <?= lang('opportunity_since') ?>
                </th>
                <th></th>
                <th></th>
                <th><?= lang('actions') ?></th>
            </tr>
        </thead>
        <tbody>
<?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
    <?php foreach ($prospect_data as $data) { ?>
                    <tr id="opportunity_<?php echo $data['prospect_id']; ?>">
                        <td class="col-md-2"><?php echo $data['prospect_name']; ?></td>
                        <td class="col-md-1"><?php echo $data['prospect_auto_id']; ?></td>
                        <td class="text-center"><?php if ($data['contact_count']) {
            echo $data['contact_count'];
        } else {
            echo '0';
        } ?></td>
                        <td class="col-md-2"><?php echo $data['contact_name']; ?></td>
                        <td><?php echo configDateTime($data['creation_date']); ?></td>
        <?php if (isset($_SERVER['HTTP_REFERER'])) {
            $redirect_link = $_SERVER['HTTP_REFERER'];
        } else {
            $redirect_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REDIRECT_URL'];
        }
        ?>
                        <td><a class="btn btn-sm btn-green" data-href="javascript:;" title="<?= $this->lang->line('win') ?>" onclick="ConvertWin('<?php echo $data['prospect_id']; ?>');" ><?= $this->lang->line('win') ?></a></td>
                        <td><a class="btn btn-sm btn-danger" data-href="javascript:;" title="<?= $this->lang->line('lost') ?>" onclick="ConvertLost('<?php echo $data['prospect_id']; ?>');" ><?= $this->lang->line('lost') ?></a></td>
                        <!--<td><?PHP if (checkPermission('Opportunity', 'edit')) { ?><a class="btn btn-sm btn-green convert_client" data-href="javascript:;" onclick="convert_request('<?php echo $data['prospect_id']; ?>','<?php echo $redirect_link; ?>');" ><?= $this->lang->line('close') ?></a><?php } ?></td>-->
                        <td class="bd-actbn-btn"><a href="<?= base_url('Opportunity/viewdata/' . $data['prospect_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a>
        <?PHP if (checkPermission('Opportunity', 'edit')) { ?><a  data-href="<?php echo base_url($opportunity_view . '/editdata/' . $data['prospect_id']); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_opportunity" id="edit_opportunity" ><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
        <?PHP if (checkPermission('Opportunity', 'delete')) { ?><a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="delete_request('<?php echo $data['prospect_id']; ?>', '<?php echo $redirect_link; ?>');" ><i class="fa fa-remove fa-x redcol" ></i></a><?php } ?></td>
                    </tr>
    <?php } ?>
<?php } else { ?>
                <tr>
                    <td colspan="8" class="text-center"> <?= lang('common_no_record_found') ?></td>
                </tr>
<?php } ?>
        </tbody>
    </table>
</div>
<div id="common_tb" class="no_of_records">
<?php
if (isset($pagination)) {
    echo $pagination;
}
?>
</div>
<script>
    function delete_request(prospect_id, redirect_link)
    {
        var delete_url = "<?php echo base_url('Opportunity/deletedata/?id='); ?>" + prospect_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo lang('confirm_delete_opportunity'); ?>";
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

    function ConvertWin(prospect_id) {
        var delete_meg = "<?php echo lang('confirm_convert_win'); ?>";
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
                                $.ajax({
                                    type: "POST",
                                    url: "Opportunity/convertWinAccount/",
                                    data: {'prospect_id': prospect_id},
                                    success: function (data)
                                    {
                                        $.ajax({
                                            type: "POST",
                                            url: "<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index/" + data,
                                            data: {
                                                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: ''
                                            },
                                            success: function (html) {
                                                BootstrapDialog.show({
                                                    message: '<?php echo lang('account_win_convert_msg'); ?>',
                                                    buttons: [{
                                                            label: '<?php echo lang('close'); ?>',
                                                            action: function (dialogItself) {

                                                                $("#common_div").html(html);
                                                                $("div").removeClass("hidden");
                                                                $(".show-success").show();
                                                                //$('#opportunity_'+prospect_id).remove();
                                                                var total = $('#opportunity_count').html();
                                                                total = total - 1;
                                                                $('#opportunity_count').html(total);

                                                                setTimeout(function () {
                                                                    $('.alert').fadeOut('5000');
                                                                }, 3000);
                                                                dialogItself.close();
                                                            }
                                                        }]
                                                });

                                            }
                                        });
                                        return false;
                                    }
                                });
                                dialog.close();
                            }
                        }]
                });

    }
    function ConvertLost(prospect_id)
    {

        var delete_meg = "<?php echo lang('confirm_convert_lost'); ?>";

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
                                $.ajax({
                                    type: "POST",
                                    url: "Opportunity/convertLostAccount/",
                                    data: {'prospect_id': prospect_id},
                                    success: function (data)
                                    {
                                        $.ajax({
                                            type: "POST",
                                            url: "<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index/" + data,
                                            data: {
                                                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: ''
                                            },
                                            success: function (html) {
                                                BootstrapDialog.show({
                                                    message: '<?php echo lang('account_lose_convert_msg'); ?>',
                                                    buttons: [{
                                                            label: '<?php echo lang('close'); ?>',
                                                            action: function (dialogItself) {

                                                                $("#common_div").html(html);
                                                                $("div").removeClass("hidden");
                                                                $(".show-success").show();
                                                                //$('#opportunity_'+prospect_id).remove();
                                                                var total = $('#opportunity_count').html();
                                                                total = total - 1;
                                                                $('#opportunity_count').html(total);

                                                                setTimeout(function () {
                                                                    $('.alert').fadeOut('5000');
                                                                }, 3000);
                                                                dialogItself.close();
                                                            }
                                                        }]
                                                });

                                            }
                                        });
                                        return false;
                                    }
                                });
                                dialog.close();
                            }
                        }]
                });
    }
</script>