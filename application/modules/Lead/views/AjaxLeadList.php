<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?> 
<div class="table table-responsive">
    <input type="hidden" name="total_lead_count" id="total_lead_count" value="<?php echo isset($total_lead) ? $total_lead : 0; ?> ">
    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
        <thead>
            <tr>
                <th <?php if (isset($sortfield) && $sortfield == 'prospect_name') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('prospect_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('name') ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'prospect_auto_id') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('prospect_auto_id', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('id') ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'contact_count') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_count', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('no_of_contacts') ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'contact_name') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_name', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('primary_contact') ?></th>
                <th <?php if (isset($sortfield) && $sortfield == 'creation_date') {
    if ($sortby == 'asc') {
        echo "class = 'sorting_desc'";
    } else {
        echo "class = 'sorting_asc'";
    }
} else {
    echo "class = 'sorting'";
} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('creation_date', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line('lead_since') ?></th>
                <th></th>
                <th><?= lang('actions') ?><input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
                    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" /></th>
            </tr>
        </thead>
        <tbody>
<?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
    <?php foreach ($prospect_data as $data) { ?>
                    <tr id="lead_<?php echo $data['lead_id']; ?>">
                        <td class="col-md-2"><?php echo $data['prospect_name']; ?></td>
                        <td class="col-md-1"><?php echo $data['prospect_auto_id']; ?></td>
                        <td class="text-center"><?php if ($data['contact_count']) {
            echo $data['contact_count'];
        } else {
            echo '0';
        } ?></td>
                        <td class="col-md-2"><?php echo $data['contact_name']; ?></td>
                        <td><?php configDateTime($data['creation_date']); ?></td>
        <?php if (isset($_SERVER['HTTP_REFERER'])) {
            $redirect_link = $_SERVER['HTTP_REFERER'];
        } else {
            $redirect_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REDIRECT_URL'];
        }
        ?>
                        <td><?php if (checkPermission('Lead', 'edit')) { ?><a class="btn btn-sm btn-green" onclick="convertToOpporutnity('<?php echo $data['lead_id']; ?>');" ><?= $this->lang->line('qualify_lead') ?></a><?php } ?></td>

                        <td class="bd-actbn-btn"><a href="<?= base_url('Lead/viewdata/' . $data['lead_id']) ?>" title="<?= $this->lang->line('view') ?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a>
        <?php if (checkPermission('Lead', 'edit')) { ?><a data-href="<?php echo base_url($lead_view . '/editdata/' . $data['lead_id']); ?>" title="<?= $this->lang->line('edit') ?>" data-toggle="ajaxModal"  aria-hidden="true" data-refresh="true" class="edit_lead" id="edit_lead"><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>
        <?php if (checkPermission('Lead', 'delete')) { ?><a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="delete_request('<?php echo $data['lead_id']; ?>', '<?php echo $redirect_link; ?>');" ><i class="fa fa-remove fa-x redcol"></i> </a><?php } ?>
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

    function delete_request(lead_id, redirect_link) {
        var delete_url = "<?php echo base_url('Lead/deletedata/?id='); ?>" + lead_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo lang('confirm_delete_lead'); ?>";
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

    function convertToOpporutnity(lead_id)
    {
        var delete_meg = "<?php echo lang('confirm_convert_opportunity'); ?>";
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
                                    url: "Lead/converToQualified/",
                                    data: {'lead_id': lead_id},
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
                                                    message: '<?php echo lang('lead_convert_opportunity_success'); ?>',
                                                    buttons: [{
                                                            label: 'Close',
                                                            action: function (dialogItself) {

                                                                $("#common_div").html(html);
                                                                $("div").removeClass("hidden");
                                                                $(".show-success").show();
                                                                //$('#lead_'+lead_id).remove();
                                                                var total = $('#total_lead').html();
                                                                total = total - 1;
                                                                $('#total_lead').html(total);

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