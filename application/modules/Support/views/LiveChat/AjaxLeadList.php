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
                                <th <?php if(isset($sortfield) && $sortfield == 'tk.ticket_subject'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('ticket_subject','<?php echo $sorttypepass;?>')"><?=$this->lang->line('ticket_subject')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'tk.ticket_desc'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('ticket_desc','<?php echo $sorttypepass;?>')"><?=$this->lang->line('ticket_desc')?></th>
								<th <?php if(isset($sortfield) && $sortfield == 'cms.contact_name'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('contact_name','<?php echo $sorttypepass;?>')"><?=$this->lang->line('EMPLOYEE_NAME')?></th>
                                <th <?php if(isset($sortfield) && $sortfield == 'tk.status'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('status','<?php echo $sorttypepass;?>')"><?=$this->lang->line('status')?></th>
								<th <?php if(isset($sortfield) && $sortfield == 'tk.milestone'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('milestone','<?php echo $sorttypepass;?>')"><?=$this->lang->line('milestone')?></th>
								<th <?php if(isset($sortfield) && $sortfield == 'tk.created_date'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}}else{echo "class = 'sorting'";} ?> tabindex="0" aria-controls="example1" rowspan="1" colspan="1" onclick="apply_sorting('created_date','<?php echo $sorttypepass;?>')"><?=$this->lang->line('create_date')?></th>
								
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($prospect_data) && count($prospect_data) > 0) { ?>
                            <?php foreach ($prospect_data as $data) { 
							/* pr($data);
		                    die('here'); */
							?>
                                <tr>
                                    <td><?php echo $data['ticket_subject']; ?></td>
                                    <td><?php echo $data['ticket_desc']; ?></td>
                                    <td class="text-center"><?php echo $data['contact_name']; ?></td>
                                    <td><?php echo $data['status']; ?></td>
                                   
                                    <td>No milestone yet</td>
									 <td><?php echo date('m/d/Y',strtotime($data['created_date'])); ?></td>
                                    <?php $redirect_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'];?>
                                    
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