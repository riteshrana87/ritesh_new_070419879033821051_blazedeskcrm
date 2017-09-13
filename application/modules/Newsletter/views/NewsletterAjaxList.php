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
            <th ><?php echo lang('NEWSLETTER_LIST_ID');?></th>
            <th ><?php echo lang('NEWSLETTER_LIST_NAME');?></th>
            <th><?php echo lang('NEWSLETTER_DATE');?></th>
            <th><?= lang ('NEWSLETTER_SUBSCRIBE') ?></th>
            <?php if((checkPermission('Newsletter','view')) && (checkPermission('Newsletter','edit') || checkPermission('Newsletter','delete'))){?>
            <th data-orderable="false"><?= lang ('actions') ?></th>
             <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php
        
        if (isset($newsletter_data) && count ($newsletter_data) > 0) { ?>
            <?php foreach ($newsletter_data as $newsletterKey => $newsletterLists) {
                 
                   foreach ($newsletterLists as $newsletter) { 
                ?>
                <tr>
                    <td><?php echo $newsletter['merge_fields']['FNAME']." ".$newsletter['merge_fields']['LNAME'];?></td>
                    <td><?php echo $newsletter['email_address']; ?></td>
                    <td><?php echo $newsletter['list_id']; ?></td>
                    <td><?php echo $newsletterKey;?></td>
                    <td><?php echo configDateTime($newsletter['timestamp_opt']); ?></td>
                    <td><?php echo ucfirst($newsletter['status']); ?></td>
                    
                    <?php if((checkPermission('Newsletter','view')) && checkPermission('Newsletter','edit') || checkPermission('Newsletter','delete')){?>
                    <td class="bd-subs-ico">
                        
                        <?php
                        if((checkPermission('Newsletter','view')) && (checkPermission('Newsletter','edit')))
                        {
                            if(ucfirst($newsletter['status']) == 'Unsubscribed')
                            { ?>
                                <a title="<?php echo lang('SUBSCRIBE');?>" onclick="make_subscribe('<?php echo $newsletter['list_id']; ?>','<?php echo $newsletter['email_address']; ?>')"><i class="fa subs-ico"></i></a>
                                <?php 
                            }else
                            {   ?>
                                <a title="<?php echo lang('UNSUBSCRIBE');?>" onclick="make_unsubscribe('<?php echo $newsletter['list_id']; ?>','<?php echo $newsletter['email_address']; ?>')"><i class="fa unsubs-ico"></i></a>
                                <?php  
                            } 
                        }
                          ?>
                        
                        <?php
                        if((checkPermission('Newsletter','view')) && (checkPermission('Newsletter','delete')))
                        {   ?>
                            <a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="delete_from_mailchimp('<?php echo $newsletter['list_id']; ?>','<?php echo $newsletter['email_address']; ?>');" ><i class="fa fa-remove fa-x redcol" ></i></a>
                            <?php
                        }   ?>
                    </td>
                    <?php } ?>
                </tr>

        <?php } }?>
        <?php } else { ?>
            <tr>
                <td colspan="7" class="text-center">
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
function make_subscribe(listsId,email_address)
{
    var export_url = '<?php echo base_url()."Newsletter/mailchimp_make_subscribe?email_address=";?>'+email_address+'&listsId='+listsId;;
    
    BootstrapDialog.show(
    {
        title: '<?php echo $this->lang->line('Information');?>',
        message: '<?php echo lang('CONFIRM_MAKE_SUBSCRIBE');?>',
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
    
    /*
    BootstrapDialog.confirm('<?php //echo lang('CONFIRM_MAKE_SUBSCRIBE');?>', function(result){
        if(result) 
        {
            window.location.href = export_url;
        }
    }); */
}
    
function make_unsubscribe(listsId,email_address)
{
    var export_url = '<?php echo base_url()."Newsletter/mailchimp_make_unsubscribe?email_address=";?>'+email_address+'&listsId='+listsId;
    
    BootstrapDialog.show(
    {
        title: '<?php echo $this->lang->line('Information');?>',
        message: '<?php echo lang('CONFIRM_MAKE_UNSUBSCRIBE');?>',
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
    
    /*
    BootstrapDialog.confirm('<?php //echo lang('CONFIRM_MAKE_UNSUBSCRIBE');?>', function(result){
        if(result) 
        {
            window.location.href = export_url;
        }
    }); */
}

function delete_from_mailchimp(listsId,email_address)
{
    var export_url = '<?php echo base_url()."Newsletter/delete_from_mailchimp?email_address=";?>'+email_address+'&listsId='+listsId;;
    
    BootstrapDialog.show(
    {
        title: '<?php echo $this->lang->line('Information');?>',
        message: '<?php echo lang('CONFIRM_DELETE_FROM_MAILCHIMP');?>',
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
    
    /*
    BootstrapDialog.confirm('<?php //echo lang('CONFIRM_DELETE_FROM_MAILCHIMP');?>', function(result){
    if(result) 
    {
        window.location.href = export_url;
    }
    }); */
}
</script>