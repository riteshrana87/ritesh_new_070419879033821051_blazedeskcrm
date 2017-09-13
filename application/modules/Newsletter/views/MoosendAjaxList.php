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
            <th ><?php echo lang('NEWSLETTER_LIST_NAME');?></th>
            <th><?php echo lang('NEWSLETTER_DATE');?></th>
            <th><?= lang ('NEWSLETTER_SUBSCRIBE') ?></th>
            
            <?php if((checkPermission('Newsletter','view')) && (checkPermission('Newsletter','delete'))){?>
            <th data-orderable="false"><?= lang ('actions') ?></th>
             <?php } ?>
        </tr>
        </thead>
        
        <tbody>
        <?php
        //pr($moosend_data);
        if (isset($moosend_data) && count ($moosend_data) > 0) { ?>
            <?php foreach ($moosend_data as $newsletterKey => $newsletter) {
                 
                ?>
                <tr>
                    <td><?php echo $newsletter->Name;?></td>
                    <td><?php echo $newsletter->Email; ?></td>
                    <td><?php echo $newsletter->list_name;?></td>
                    <td><?php 
                        $df =  substr($newsletter->CreatedOn,6,10);
                        echo change_moosend_date($df); ?>
                    </td>
                    <td>
                        <?php 
                            if($newsletter->SubscribeType == '1')
                            {
                                echo lang('SUBSCRIBED');
                            }else
                            {
                                echo lang('UNSUBSCRIBED');
                            }
                        ?>
                    </td>
                    <?php if((checkPermission('Newsletter','view')) && (checkPermission('Newsletter','delete'))){?>
                    <td>
                        <?php
                        /*
                            if($newsletter->SubscribeType == '2')
                            { ?>
                                <a class="btn btn-green" title="Subscribe" onclick="make_subscribe('<?php echo $newsletter->Email; ?>')"><?php echo lang('SUBSCRIBED'); ?></a>
                           <?php  }else
                            { ?>
                                <a class="btn btn-danger" title="Unsubscribe" onclick="make_unsubscribe('<?php echo $newsletter->Email; ?>')"><?php echo lang('UNSUBSCRIBED'); ?></a>
                          <?php  } 
                          */
                          ?>
                        
                        
                            <a data-href="javascript:;" title="<?= $this->lang->line('delete') ?>" onclick="delete_from_moosend('<?php echo $newsletter->list_id; ?>','<?php echo $newsletter->Email; ?>');" ><i class="fa fa-remove fa-x redcol" ></i></a>
                       
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
<script>
function make_subscribe(email_address)
{
    var export_url = '<?php echo base_url()."Newsletter/moosend_make_subscribe?email_address=";?>'+email_address;
    
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
    
function make_unsubscribe(email_address)
{
    var export_url = '<?php echo base_url()."Newsletter/moosend_make_unsubscribe?email_address=";?>'+email_address;
    
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
    
function delete_from_moosend(list_id,email_address)
{
    var export_url = '<?php echo base_url()."Newsletter/moosend_delete_contact?email_address=";?>'+email_address+'&list_id='+list_id;
    
    BootstrapDialog.show(
    {
        title: '<?php echo $this->lang->line('Information');?>',
        message: '<?php echo lang('CONFIRM_DELETE_FROM_MOOSEND');?>',
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
    BootstrapDialog.confirm('<?php //echo lang('CONFIRM_DELETE_FROM_MOOSEND');?>', function(result){
    if(result) 
    {
        window.location.href = export_url;
    }
    }); */
}
</script>