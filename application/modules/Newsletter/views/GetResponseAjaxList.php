<?php

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
            <th><?= $this->lang->line ('NEWSLETTER_NAME') ?></th>
            <th><?= lang('NEWSLETTER_Email') ?></th>
            
            <th ><?php echo lang('GET_RESPONSE_CAMPAIGN_NAME');?></th>
            <th><?php echo lang('NEWSLETTER_DATE');?></th>
            <th><?= lang ('NEWSLETTER_SUBSCRIBE') ?></th>
            <?php if((checkPermission('Newsletter','view')) && (checkPermission('Newsletter','delete'))){?>
            <th data-orderable="false"><?= lang ('actions') ?></th>
            <?php }?> 
        </tr>
        </thead>
        
        <tbody>
        <?php
       // pr($get_response_data);
        if (isset($get_response_data) && count ($get_response_data) > 0) { ?>
            <?php foreach ($get_response_data as $key => $newsletter) {
               
                ?>
                <tr>
                    <td><?php echo $newsletter->name;?></td>
                    <td><?php echo $newsletter->email; ?></td>
                    <td><?php echo $newsletter->list_name;?></td>
                    <td><?php echo configDateTime($newsletter->created_on); ?></td>
                    <td><?php echo lang('SUBSCRIBED'); ?></td>
                     <?php if((checkPermission('Newsletter','view')) && (checkPermission('Newsletter','delete'))){?>
                    <td class="bd-subs-ico">
                        <a title="<?php echo lang('delete');?>" onclick="delete_contact('<?php echo $newsletter->contact_id;?>','<?php echo $newsletter->email;?>')"><i class="fa fa-remove fa-x redcol" ></i></a>
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
            /*
            if (isset($pagination)) {
                echo $pagination;
            }*/
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
function delete_contact(cotnact_Id,email)
{
    var export_url = '<?php echo base_url()."Newsletter/delete_contact_getResponse?contact_id=";?>'+cotnact_Id+'&email='+email;

    BootstrapDialog.show(
    {
        title: '<?php echo $this->lang->line('Information');?>',
        message: '<?php echo lang('CONFIRM_DELETE_FROM_GET_RESPONSE');?>',
        buttons: [{
            label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
            action: function(dialog) {
                dialog.close();
            }
        }, {
            label: '<?php echo $this->lang->line('ok');?>',
            action: function(dialog) {
                window.location.href = export_url;
            }
        }]
    });
}
</script>