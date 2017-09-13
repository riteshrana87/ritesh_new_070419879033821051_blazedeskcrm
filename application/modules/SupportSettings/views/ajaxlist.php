<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->viewname = $this->uri->segment(1);

$SortDefault = '<i class="fa fa-sort"></i>';
$sortAsc = '<i class="fa fa-sort-desc"></i>';
$sortDesc = '<i class="fa fa-sort-asc"></i>';
if ($sortOrder == "asc")
    $sortOrder = "desc";
else
    $sortOrder = "asc";

?>
<div class="table table-responsive" id="tableCurrencyDiv">
        <table id="productTable" class="table table-striped">
            <thead>
            <tr>               
						<th class='sortSettingsListtype col-lg-5'>
                            <a  href="<?php echo base_url(); ?>SupportSettings/taskAjaxList/<?= $page ?>/?orderField=priority&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'priority') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'priority') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('priority')?>
                            </a>
                        </th>
                        <th class='sortSettingsListtype col-lg-5'>
                            <a  href="<?php echo base_url(); ?>SupportSettings/taskAjaxList/<?= $page ?>/?orderField=status&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'status') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'status') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('status')?>
                            </a>
                        </th>					
                        <th class="col-lg-2"><?= lang('actions')?></th>
            </tr>
            </thead>
             <tbody>
		        <?php if(isset($information) && count($information) > 0 ){ ?>
		        <?php foreach($information as $data){ 
		        	if($data['status'] == 1){ 
		            $data['status'] = lang('active'); 
		          }else{ 
		            $data['status'] = lang('inactive'); 
		          }  ?>
		        <tr>
		     
		          <td><?php echo $data['priority'];?></td>
		          <td><?php echo $data['status'];?></td>		         
		          <td class="bd-actbn-btn"><?php if(checkPermission('SupportSettings','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/view/'.$data['support_priority_id']);?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" style="cursor:pointer;" title="<?= lang('view')?>" ><i class="fa fa-search greencol" ></i></a><?php }?><?php if(checkPermission('SupportSettings','edit')){ ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['support_priority_id']);?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" style="cursor:pointer;" title="<?= lang('edit')?>" ><i class="fa fa-pencil bluecol" ></i></a><?php }?><?php if(checkPermission("SupportSettings","delete")){ if( ($data['support_priority_id'] != 1) && ($data['support_priority_id'] != 2) && ($data['support_priority_id'] != 3) ){?><a  href="javascript:;" onclick="delete_request1(<?php echo $data['support_priority_id']; ?>);" title="<?= lang('delete')?>"><i class="fa fa-remove redcol"></i></a><?php } } ?></td>
		        </tr>
		        <?php } ?>
		      <?php }else { ?>
                <tr>
                    <td colspan="6" class="text-center"><?= lang('common_no_record_found') ?></td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="clr"></div>
		<div class="row">
                    <div class="col-md-12 text-center">
                        <?php 
                        
                        if (isset($pagination) && !empty($pagination)) {
					                echo $pagination;
					            }else{
					            	echo '<div class="no_of_records">'.lang('showing').' :'.count($information).'</div>';
					            }
                        
                        // echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
	<script> 
	function delete_request1(support_priority_id){
      var delete_meg ="<?php echo $this->lang->line('support_priority_delete_confrim_msg');?>";
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
                        window.location.href = "SupportSettings/deletedataPriority/" + support_priority_id;
                        dialog.close();
                    }
                }]
            });

}
</script>	
