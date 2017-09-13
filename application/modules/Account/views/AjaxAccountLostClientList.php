<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?>         
<div class="table table-responsive">
    <input type="hidden" name="total_lostaccount_count" id="total_lostaccount_count" value="<?php echo isset($total_account)?$total_account:0;?> ">
    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
                    <thead>
                        <tr>
                                <th <?php if(isset($sortfield) && $sortfield == 'prospect_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('prospect_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('name')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'prospect_auto_id'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('prospect_auto_id','<?php echo $sorttypepass;?>')"><?= lang('id') ?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'contact_count'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_count','<?php echo $sorttypepass;?>')"><?=$this->lang->line('no_of_contacts')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'primary_contact'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('primary_contact','<?php echo $sorttypepass;?>')"><?=$this->lang->line('primary_contact')?></th>
                                
                                <th <?php if(isset($sortfield) && $sortfield == 'creation_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('creation_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('client_since')?></th>
                                
                                <th></th>
                                <th><?= lang('actions') ?><input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" /></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
                            <?php foreach ($prospect_data as $data_prospect) { ?>
                            <tr id="account_row_<?php echo $data_prospect['prospect_id']; ?>">
                                <td class="col-md-2"><?php echo $data_prospect['prospect_name']; ?></td>
                                        <td  class="col-md-1"><?php echo $data_prospect['prospect_auto_id']; ?></td>
                                        <td class="text-center"><?php if($data_prospect['contact_count']){ echo $data_prospect['contact_count']; }else{ echo '0'; } ?></td>
                                        <td  class="col-md-2"><?php  if($data_prospect['contact_name']){ echo $data_prospect['contact_name']; }else{ echo ''; }  ?></td>
                                        <td><?php echo configDateTime($data_prospect['creation_date']); ?></td>

                                        <td><a class="btn btn-sm btn-green" data-href="javascript:;" onclick="ConvertToClient('<?php echo $data_prospect['prospect_id'];?>');"><?php echo lang('CONVERT_TO_CLIENT');?></a></td>
                                       <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>
                                        <td class="bd-actbn-btn">
                                            <?PHP /* if (checkPermission('Account', 'edit')) { ?><a data-href="<?php echo base_url()."Account/send_email_view/".$data_prospect['prospect_id'];?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?php echo lang('SEND_EMAIL'); ?>"><i class="fa fa-envelope fa-x orangecol"></i></a><?php } 
                                            */
                                            ?>


                                            <?PHP if (checkPermission('Account', 'view')) { ?><a href="<?=base_url('Account/viewLostClient/'.$data_prospect['prospect_id'])?>" title="<?php echo lang('view');?>" class="edit_contact" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
                                            <?PHP if (checkPermission('Account', 'edit')) { ?><a data-href="<?php echo base_url('/Opportunity/editdata/'.$data_prospect['prospect_id']); ?>" title="<?php echo lang('edit');?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="edit_account" id="edit_account"  ><i class="fa fa-pencil fa-x bluecol"></i> </a><?php } ?>
                                            <?PHP if (checkPermission('Account', 'delete')) { ?><a data-href="javascript:;" onclick="deleteAcount('<?php echo $data_prospect['prospect_id']; ?>');" title="<?php echo lang('delete');?>"><i class="fa fa-remove fa-x redcol"></i></a><?php } ?>

                                        </td>
                            </tr>
                            <?php } ?>
                        <?php }else{ ?>
                        
                        <tr><td colspan="7" class="text-center"><?= lang ('common_no_record_found') ?></td></tr>
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
      
function deleteAcount(prospect_id)
{
    var delete_meg ="<?php echo lang('confirm_delete_client');?>";
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
                    $.ajax({
                        type: "GET",
                        url:"Account/deletedata/",
                        data: {'id': prospect_id},
                        success: function (data)
                        {
                            window.location.reload();
                            /*
                            BootstrapDialog.show({
                                message: '<?php echo lang('account_del_msg');?>',
                                buttons: [{
                                    label: '<?php echo lang('close');?>',
                                    action: function(dialogItself){

                                         var showing_html = $('#common_tb').html();
                                         var html_arr  = showing_html.split(":");
                                         var total_record = html_arr[1] - 1;

                                         $('#common_tb').html('Showing : '+total_record+''); 
                                        $('#account_row_'+prospect_id).remove();
                                        dialogItself.close();
                                    }
                                }]
                            });

                                */
                        }
                    });
                    dialog.close();
                }
            }]
        });


}      
function delete_request(prospect_id,redirect_link)
{
    var delete_url = "Account/deletedata/?id=" + prospect_id +"&link=" + redirect_link;
    var delete_meg ="<?php echo lang('confirm_delete_client');?>";
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

function ConvertToClient(prospect_id)
{
    var delete_meg ="<?php echo lang('CONFIRM_CONVERT_TO_CLIENT');?>";
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
                    $.ajax({
                        type: "POST",
                        url:"../Account/ConvertToClient/",
                        data: {'prospect_id': prospect_id},
                        success: function (data)
                        {
                            BootstrapDialog.show({
                                message: '<?php echo lang('SUCCESS_CONVERT_TO_CLIENT');?>',
                                buttons: [{
                                    label: '<?php echo lang('close');?>',
                                    action: function(dialogItself){

                                        /* var showing_html = $('#common_tb').html();
                                         var html_arr  = showing_html.split(":");
                                         var total_record = html_arr[1] - 1;

                                         $('#common_tb').html('Showing : '+total_record+''); */
                                        $('#account_row_'+prospect_id).remove();
                                        dialogItself.close();
                                    }
                                }]
                            });


                        }
                    });
                    dialog.close();
                }
            }]
        });
}
</script>