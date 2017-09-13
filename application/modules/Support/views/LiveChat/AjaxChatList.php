<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//$this->viewname = $this->uri->segment(1);
if (isset($sortby) && $sortby == 'asc') {
    $sorttypepass = 'desc';
} else {
    $sorttypepass = 'asc';
}
?> 
<div class=" table table-responsive">
	<input type="hidden" id="sortfield" name="sortfield" value="<?php if (isset($sortfield)) echo $sortfield; ?>" />
    <input type="hidden" id="sortby" name="sortby" value="<?php if (isset($sortby)) echo $sortby; ?>" />
    <input type="hidden" id="uri_segment" name="uri_segment" value="<?php if (isset($uri_segment)) echo $uri_segment; ?>" />
                    <table class="table table-striped dataTable" id="example1" role="grid" aria-describedby="example1_info" width="100%">
                        <thead>
                            <tr>
                                <th <?php if(isset($sortfield) && $sortfield == 'lc.fromname'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('ticket_subject','<?php echo $sorttypepass;?>')"><?=$this->lang->line('sender_name')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'lc.msg'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('ticket_desc','<?php echo $sorttypepass;?>')"><?=$this->lang->line('ticket_desc')?></th>
								<th <?php if(isset($sortfield) && $sortfield == 'lc.toname'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('EMPLOYEE_NAME')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'lc.time'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('send_date')?></th>
								<th  tabindex="0" aria-controls="example1" rowspan="1" colspan="1"><?=$this->lang->line('option')?></th>
								
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($chat_data) && count($chat_data) > 0) { ?>
                            <?php foreach ($chat_data as $data) { 
							    /*pr($data);
								die('here'); */
							?>
                                <tr>
									
                                    <td><?php echo $data['fromname']; ?></td>
                                    <td><?php echo $data['msg']; ?></td>
                                    <td><?php echo $data['toname']; ?></td>
                                    <td><?php echo $data['time']; ?></td>
									<td><a href="<?php echo base_url('Support/LiveChat/chat?to_id='.$data['from_id']); ?>" data-id="<?php echo $data['chat_id']; ?>" data-toggle="ajaxModal" class="btn btn-white" aria-hidden="true" data-refresh="true">
                                        <?= $this->lang->line('chat') ?>
                                    </a></td>
                                   
                                </tr>
                             <?php } ?>
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
            
function delete_request(lead_id,redirect_link){
	var delete_url = "Lead/deletedata/?id=" + lead_id +"&link=" + redirect_link;
    var delete_meg ="Are You Sure Want to Delete This Lead ?";
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
function convert_request(lead_id,redirect_link){
	var convert_url = "Lead/convert_opportunity/?id=" + lead_id +"&link=" + redirect_link;
    var delete_meg ="Are You Sure Want to Convert this Lead to Opportunity?";
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
                    window.location.href = convert_url;
                    dialog.close();
                }
            }]
        });

}
        </script>