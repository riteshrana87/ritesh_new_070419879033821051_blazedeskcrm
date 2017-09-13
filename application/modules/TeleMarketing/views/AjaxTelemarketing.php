<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
/*pr($role_type);
die();*/
?> 
<div class=" table table-responsive">
	<input type="hidden" name="total_tele_count" id="total_tele_count" value="<?php echo isset($total_tele)?$total_tele:0;?>" />
	<input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
    <input type="hidden" id="uri_segment" name="uri_segment" value="<?php if (isset($uri_segment)) echo $uri_segment; ?>" />
                    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
                        <thead>
                            <tr>
								<?php if($role_type=='39'){?>
								<th <?php if(isset($sortfield) && $sortfield == 'tm.user_id'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('user_id','<?php echo $sorttypepass;?>')"><?=$this->lang->line('EMPLOYEE_NAME')?></th>
								<?php }?>
                                <th <?php if(isset($sortfield) && $sortfield == 'tm.tele_name'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('tele_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('company_contact_name')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'tm.company_id'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  onclick="apply_sorting('ticket_desc','<?php echo $sorttypepass;?>')"><?=$this->lang->line('company_name')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'tm.status'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  onclick="apply_sorting('status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('status')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'tm.phone_no'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('phone_no')?></th>
								
								<th <?php if(isset($sortfield) && $sortfield == 'tm.created_date'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('create_date')?></th>
								<?php if(checkPermission("Support",'edit') || checkPermission("Support",'delete')){ ?>
								<th ><?= lang('actions') ?></th>
								<?php } ?>
								
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($tele_data) && count($tele_data) > 0) { ?>
                            <?php foreach ($tele_data as $data) { 
							/*pr($data);
		                    die('here'); */
							?>
                                <tr>
									<?php if($role_type=='39'){?>
									<td><?php echo $data['firstname'].' '.$data['lastname']; ?></td>
									<?php }?>
                                    <td><?php echo $data['tele_name']; ?></td>
									<td><?php echo $data['company_name']; ?></td>
									<?php if($data['status']=='1'){$data['status'] = lang('pos_req');}
									elseif($data['status']=='2'){$data['status']=lang('pos_demo');}
									elseif($data['status']=='3'){$data['status']=lang('pos_became_client');}
									elseif($data['status']=='4'){$data['status']=lang('neg_not_int');}
									elseif($data['status']=='5'){$data['status']=lang('voice');}
									elseif($data['status']=='6'){$data['status']=lang('call_back');}
										?>
									<td><?php echo $data['status']; ?></td>
                                    <td><?php echo $data['phone_no']; ?></td>
									
									<td class="col-md-2"><?php echo date('m/d/Y',strtotime($data['created_date'])); ?></td>
                                    <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>
                                    <?php if(checkPermission("Support",'edit') || checkPermission("Support",'delete')){ ?>
									<td class="col-md-2"> 
									<?php
                                    $task_type=empty($task_data['sub_task_id'])?'edit_record':'edit_subtask';
									?>
									<?php if(checkPermission("TeleMarketing",'view')){ ?><a  href="<?=base_url()?>TeleMarketing/view_record/<?=$data['tele_id']?>" data-toggle="ajaxModal" aria-hidden="true" title="<?=lang('view')?>" ><i class="fa fa-search greencol"></i></a><?php } ?>
									<?php if(checkPermission("TeleMarketing",'edit')){ ?>&nbsp;&nbsp;<a href="<?=base_url()?>TeleMarketing/<?=$task_type?>/<?=$data['tele_id']?>" data-toggle="ajaxModal" aria-hidden="true" title="<?=lang('edit')?>" ><i class="fa fa-pencil bluecol"></i></a><?php } ?>
									<?php if(checkPermission("TeleMarketing",'delete')){ ?>&nbsp;&nbsp;<a href="javascript:;" title="<?=lang('delete')?>" onclick="deleteItem('<?php echo $data['tele_id']; ?>');"><i class="fa fa-remove redcol"></i></a><?php } ?>
									</td>
									<?php } ?>
									</tr>
									<?php } ?>
									<?php } else { ?>
									<td colspan="8" class="text-center"><?= lang('common_no_record_found') ?></td>
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

function deleteItem(id)
    {
		
        var delete_meg ="<?php echo $this->lang->line('tele_delete_message');?>";
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
                        window.location.href = "<?php echo base_url('TeleMarketing/deletedata/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });

    }

        </script>
