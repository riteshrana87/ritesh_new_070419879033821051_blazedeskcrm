<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}


?>
        <div class="table table-responsive">
            <input type="hidden" name="total_contact_count" id="total_contact_count" value="<?php echo isset($total_contact)?$total_contact:0;?> ">
            <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">

        <thead>
        <tr>
            <th <?php if(isset($sortfield) && $sortfield == 'contact_name'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-2'";}else{echo "class = 'sorting_asc col-md-2'";}}else{echo "class = 'sorting col-md-2'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_name','<?php echo $sorttypepass;?>')"><?=lang('contact_name')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'company_name'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-2'";}else{echo "class = 'sorting_asc col-md-2'";}}else{echo "class = 'sorting col-md-2'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('company_name','<?php echo $sorttypepass;?>')"><?=lang('company')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'job_title'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-2'";}else{echo "class = 'sorting_asc col-md-2'";}}else{echo "class = 'sorting col-md-2'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('job_title','<?php echo $sorttypepass;?>')"><?=lang('job_title')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'email'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-2'";}else{echo "class = 'sorting_asc col-md-2'";}}else{echo "class = 'sorting col-md-2'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('email','<?php echo $sorttypepass;?>')"><?=lang('email')?></th>
            <th <?php if(isset($sortfield) && $sortfield == 'contact_for'){if($sortby == 'asc'){echo "class = 'sorting_desc col-md-2'";}else{echo "class = 'sorting_asc col-md-2'";}}else{echo "class = 'sorting col-md-2'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_for','<?php echo $sorttypepass;?>')"><?=lang('contact_for')?></th>

            <th class="col-md-1"><?=$this->lang->line('status')?></th>
            <th class="col-md-1"> <?=$this->lang->line('actions')?>
                <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
                <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
            </th>
        </tr>
        </thead>
        <tbody >
        <?php if(isset($contact_info) && count($contact_info) > 0 ){ ?>
            <?php foreach($contact_info as $data){ ?>
                <tr>
                    <td class="col-md-2"><?php echo $data['contact_name'];?></td>
                    <td class="col-md-2"><?php echo $data['company_name'];?></td>
                    <td class="col-md-2"><?php echo $data['job_title'];?></td>
                    <td class="col-md-2"><?php echo $data['email'];?></td>
                    <td class="col-md-2"><?php echo $data['contact_for'];?></td>
                    <td class="col-md-1"><?php if($data['status']=="1"){echo "Active";} else {echo "Inactive";}?></td>
                    <td class="col-md-1 bd-actbn-btn">
                        <?php if(checkPermission('SupportContact','view')){ ?><a href="<?=base_url('SupportContact/view/'.$data['contact_id'])?>" class="edit_contact" title="<?=lang('view')?>" ><i class="fa fa-search fa-x greencol"></i></a><?php } ?>
 
                        <?php if(checkPermission('SupportContact','edit')){ ?><a data-href="<?=base_url()?>SupportContact/edit/<?=$data['contact_id']?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?=lang('edit')?>" class=""><i class="fa fa-pencil fa-x bluecol"></i></a><?php } ?>

                        
                        <?php if (checkPermission('SupportContact', 'delete')) { ?><a data-href="javascript:;" onclick="delete_request('<?php echo $data['contact_id']; ?>','<?php echo base_url('SupportContact');?>');" title="<?=lang('delete')?>"><i class="fa fa-remove fa-x redcol"></i></a><?php } ?>
                    </td>
                </tr>
            <?php }?>
      <?php }else{?>
            <tr>
                <td colspan="8" class="text-center"><?= lang ('common_no_record_found') ?></td>
            </tr>
        <?php }?>
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
            
function delete_request(contact_id,redirect_link)
{
    var delete_url = "SupportContact/deletedata/?id=" + contact_id+"&link="+redirect_link;
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