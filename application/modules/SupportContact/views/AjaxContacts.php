<?php
//if ($taskPage == 0)
//    $taskPage = 1;
$tasksSortDefault = '<i class="fa fa-sort"></i>';
$taskSortAsc = '<i class="fa fa-sort-desc"></i>';
$taskSortDesc = '<i class="fa fa-sort-asc"></i>';
if ($contactSortOrder == "asc") {
    $contactSortOrder = "desc";
} else {
    $contactSortOrder = "asc";
}

?> 

<div class="whitebox" id="table_contact">
    <div class="table table-responsive">
        <table class="table table-responsive" >
            <thead>
                <tr role="row">
                    <th class='sortTask col-md-4'>
                        
                        <a href="<?php echo base_url(); ?>SupportContact/view/?orderField=contact_name&sortOrder=<?php echo $contactSortOrder ?>">
                            <?php
                            if ($contactSortOrder == 'asc' && $contatcSortField == 'contact_name') {
                                echo $taskSortAsc;
                            } else if ($contactSortOrder == 'asc' && $contatcSortField == 'contact_name') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                           <?=$this->lang->line('name')?>
                        </a>
                    </th>
                    <th class='sortTask col-md-3'>
                        
                        <a href="<?php echo base_url(); ?>SupportContact/view/?orderField=email&sortOrder=<?php echo $contactSortOrder ?>">
                            <?php
                            if ($contactSortOrder == 'asc' && $contatcSortField == 'email') {
                                echo $taskSortAsc;
                            } else if ($contactSortOrder == 'asc' && $contatcSortField == 'email') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                           <?=$this->lang->line('email')?>
                        </a>
                    </th>
                    <th class='sortTask col-md-3'>
                        
                        <a href="<?php echo base_url(); ?>SupportContact/view/?orderField=number&sortOrder=<?php echo $contactSortOrder ?>">
                            <?php
                            if ($contactSortOrder == 'asc' && $contatcSortField == 'number') {
                                echo $taskSortAsc;
                            } else if ($contactSortOrder == 'asc' && $contatcSortField == 'number') {
                                echo $taskSortDesc;
                            } else {
                                echo $tasksSortDefault;
                            }
                            ?>
                            <?=$this->lang->line('number')?>
                        </a>
                    </th>
                    <th class="col-md-2"><?= lang('actions') ?></th>
                </tr>
            </thead>

            <tbody>
                <?php $redirect_link=$_SERVER['HTTP_REFERER']; ?>
                <?php
                //pr($note_data);
                if(isset($contact_info) && count($contact_info) > 0)
                { 
                    foreach ($contact_info as $contact) {
                    ?>
                    <tr id="contact_id_<?php echo $contact['contact_id']; ?>">
                        <td class="col-sm-4"><?php echo $contact['name']; ?></td>
                        <td><?php echo $contact['email']; ?></td>
                        <td><?php echo $contact['number']; ?></td>
                        <td>
                            <?php if (checkPermission ("SupportContact", 'view')) { ?><a href="<?= base_url() ?>SupportContact/view/<?= $contact['contact_id'] ?>" title="<?php echo lang('VIEW_CONTACT'); ?>"><i class="fa fa-search greencol"></i></a>&nbsp;&nbsp;
                            <?php  } ?>
                                <?php  if (strpos($redirect_link, 'Lead/viewdata') !== false) { ?>
                          <?php if (checkPermission ("Lead", 'edit')) { ?>  <a data-href="<?= base_url() ?>Lead/editdata/<?= $contact['prospect_id'] ?>" title="<?= $this->lang->line('edit') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_LEAD'); ?>"><i class="fa fa-pencil bluecol"></i></a>&nbsp;&nbsp; 
                          <?php } ?>
                              <?php if (checkPermission ("Lead", 'delete')) { ?>  <a onclick="delete_request_leadcontact('<?php echo $contact['contact_id']; ?>');" title="<?= $this->lang->line('delete') ?>"><i class="fa fa-remove redcol"></i></a>
                              <?php } } ?>
                            <?php  if (strpos($redirect_link, 'Opportunity/viewdata') !== false) { ?>
                           <?php if (checkPermission ("Opportunity", 'edit')) { ?> <a data-href="<?= base_url() ?>Opportunity/editdata/<?= $contact['prospect_id'] ?>" title="<?= $this->lang->line('edit') ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_LEAD'); ?>"><i class="fa fa-pencil bluecol"></i></a>&nbsp;&nbsp; 
                           <?php } ?>
                               <?php if (checkPermission ("Opportunity", 'delete')) { ?> <a title="<?= $this->lang->line('delete') ?>" onclick="delete_request_opportunitycontact('<?php echo $contact['contact_id']; ?>');"><i class="fa fa-remove redcol"></i></a>
                            <?php }  }?>
                            <?php  if (strpos($redirect_link, 'Account/viewdata') !== false) { ?>
                           <?php if (checkPermission ("Account", 'edit')) { ?> <a title="<?= $this->lang->line('edit') ?>" data-href="<?= base_url() ?>Account/editdata/<?= $contact['prospect_id'] ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('UPDATE_LEAD'); ?>"><i class="fa fa-pencil bluecol"></i></a>&nbsp;&nbsp; 
                           <?php } ?>
                               <?php if (checkPermission ("Account", 'delete')) { ?>     <a title="<?= $this->lang->line('delete') ?>" onclick="delete_request_accountcontact('<?php echo $contact['contact_id']; ?>');"><i class="fa fa-remove redcol"></i></a>
                           <?php }  }?>
                           </td>
                    </tr>
                <?php }
                }else
               { ?>
                    <tr><td colspan="4" class="text-center"> <?= lang ('common_no_record_found') ?></td></tr>  
              <?php }
                ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 text-center">
                <?php echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
    <div class="clr"></div>
</div>
<script>
            
function delete_request_leadcontact(contact_id)
{
    var delete_url = "<?php echo base_url('Lead/delete_contact_master/?id=');?>" + contact_id;
    var delete_meg ="<?php echo $this->lang->line('CONFIRM_DELETE_CONTACT');?>";
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
function delete_request_opportunitycontact(contact_id)
{
    var delete_url = "<?php echo base_url('Opportunity/delete_contact_master/?id=');?>" + contact_id;
    var delete_meg ="<?php echo $this->lang->line('CONFIRM_DELETE_CONTACT');?>";
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
function delete_request_accountcontact(contact_id)
{
    var delete_url = "<?php echo base_url('Account/delete_contact_master/?id=');?>" + contact_id;
    var delete_meg ="<?php echo $this->lang->line('CONFIRM_DELETE_CONTACT');?>";
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