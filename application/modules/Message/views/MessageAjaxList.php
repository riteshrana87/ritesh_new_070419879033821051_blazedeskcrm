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
            if (isset($sortfield) && $sortfield == 'message_subject') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('message_subject', '<?php echo $sorttypepass; ?>')"><?= $this->lang->line ('MESSAGE_SUBJECT') ?>
            </th>
            <th><?= lang('MESSAGE_DESCRIPTION') ?></th>
            <th ><?php echo lang('MESSAGE_TO');?></th>
            <th ><?php echo lang('MESSAGE_FROM');?></th>
            <th <?php if (isset($sortfield) && $sortfield == 'created_date') {
                if ($sortby == 'asc') {
                    echo "class = 'sorting_desc'";
                } else {
                    echo "class = 'sorting_asc'";
                }
            } else {
                echo "class = 'sorting'";
            }
            ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"
               onclick="apply_sorting('created_date', '<?php echo $sorttypepass; ?>')"><?php echo lang('CREATED_DATE');?></th>

            <?php if (checkPermission ('Meeting', 'delete')) { ?>
                <th><?= lang ('action') ?></th>
            <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php if (isset($project_incidenttype_data) && count ($project_incidenttype_data) > 0) { ?>
            <?php foreach ($project_incidenttype_data as $note) {
                 
                ?>
                <tr>
                    <td><?php
                            $note_dubject = $note['message_subject'];
                            echo  strlen($note_dubject) > 50 ? substr($note_dubject,0,50)."..." : $note_dubject; 
                            ?>
                    </td>
                    <td><?php
                            $in = $note['message_description'];
                            echo  strlen($in) > 50 ? substr($in,0,50)."..." : $in; 
                            ?>
                    </td>
                    <td><?php echo $note['contact_name'];?></td>
                    <td><?php echo $note['login_user_name'];?></td>
                    <td><?php echo configDateTime($note['created_date']); ?></td>
                    <?php if (checkPermission ('Message', 'delete')) { ?>
                        <td>
                             <a data-href="<?= base_url() ?>Message/view_record/<?= $note['message_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('VIEW_MESSAGE'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;
                            
                                
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
    function delete_message(message_id, redirect_link)
    {
        
        var delete_url = "<?php echo base_url();?>Message/delete_record/?message_id=" + message_id + "&link=" + redirect_link;
        var delete_meg ="<?php echo lang('CONFIRM_DELETE_MESSAGE');?>";
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
                        window.location.href = delete_url;
                        dialog.close();
                    }
                }]
            });


    }
</script>