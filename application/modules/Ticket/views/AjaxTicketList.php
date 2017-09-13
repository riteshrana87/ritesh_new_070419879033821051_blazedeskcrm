<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
/*pr($uri_segment);
die();*/
?> 
<div class=" table table-responsive">
	<input type="hidden" name="total_ticket_count" id="total_ticket_count" value="<?php echo isset($total_ticket)?$total_ticket:0;?>" />
	<input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
    <input type="hidden" id="uri_segment" name="uri_segment" value="<?php if (isset($uri_segment)) echo $uri_segment; ?>" />
                    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
                        <thead>
                            <tr>
                                <th <?php if(isset($sortfield) && $sortfield == 'tk.ticket_subject'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('ticket_subject','<?php echo $sorttypepass;?>')"><?=$this->lang->line('ticket_subject')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'tk.ticket_desc'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1"  onclick="apply_sorting('ticket_desc','<?php echo $sorttypepass;?>')"><?=$this->lang->line('ticket_desc')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'tk.status'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('status')?></th>
								<th <?php if(isset($sortfield) && $sortfield == 'tk.milestone'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('milestone','<?php echo $sorttypepass;?>')"><?=$this->lang->line('milestone')?></th>
								<th <?php if(isset($sortfield) && $sortfield == 'tk.created_date'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('create_date')?></th>
								<th <?php if(isset($sortfield) && $sortfield == 'tk.due_date'){if($sortby == 'asc'){echo "class = 'sorting_desc '";}else{echo "class = 'sorting_asc '";}}else{echo "class = 'sorting '";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('due_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('due_date')?></th>
								<th ><?= lang('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
                            <?php foreach ($prospect_data as $data) { 
							/*pr($data);
		                    die('here'); */
							?>
                                <tr>
                                    <td><?php echo $data['ticket_subject']; ?></td>
                                    <td class="col-md-2"><?php if(!empty($data['ticket_desc'])){
													   if(strlen($data['ticket_desc']) > 50) {
														   echo substr($data['ticket_desc'],0, 40).'...';
													   }else {
														   echo $data['ticket_desc'];
													   }
													}   ?></td>

                                    <td><?php echo $data['status_name'];?></td>
                                   
                                    <td>No milestone yet</td>
									 <td class="col-md-2"><?php echo date('m/d/Y',strtotime($data['created_date'])); ?></td>
									 <td class="col-md-2"><?php echo date('m/d/Y',strtotime($data['due_date'])); ?></td>
                                    <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>
                                   
                            <td class="col-md-2 bd-actbn-btn"> 
                                <?php
                                    $task_type=empty($task_data['sub_task_id'])?'edit_record':'edit_subtask';
                                ?>
                                <a  href="<?=base_url()?>Ticket/view_record/<?=$data['ticket_id']?>" data-toggle="ajaxModal" aria-hidden="true" title="<?=lang('view')?>" ><i class="fa fa-search greencol"></i></a>
                                <?php if(checkPermission("Ticket",'edit')){ ?><a href="<?=base_url()?>Ticket/<?=$task_type?>/<?=$data['ticket_id']?>" data-toggle="ajaxModal" aria-hidden="true" title="<?=lang('edit')?>" ><i class="fa fa-pencil bluecol"></i></a><?php } ?>
                                <?php if(checkPermission("Ticket",'delete')){ ?><a href="javascript:;" title="<?=lang('delete')?>" onclick="deleteItem('<?php echo $data['ticket_id']; ?>');"><i class="fa fa-remove redcol"></i></a><?php } ?>
                            </td>
                            
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
        var delete_meg ="<?php echo $this->lang->line('ticket_delete_message');?>";
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
                        window.location.href = "<?php echo base_url('Ticket/deletedata/'); ?>/" + id;
                        dialog.close();
                    }
                }]
            });

    }

        </script>
