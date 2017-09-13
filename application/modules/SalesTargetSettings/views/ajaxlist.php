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
<div class="table table-responsive" id="postList">
        <table id="emailTemplateTable" class="table table-striped">
            <thead>
            <tr>
                <th class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>SalesTargetSettings/index/<?= $page ?>/?orderField=name&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'name') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'name') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('select_employee_name')?>
                            </a>
                        </th>
                        <th class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>SalesTargetSettings/index/<?= $page ?>/?orderField=month&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'month') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'month') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('month')?>
                            </a>
                        </th>
                        <th class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>SalesTargetSettings/index/<?= $page ?>/?orderField=target&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'target') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'target') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('target')?>
                            </a>
                        </th>                       
                        <th class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>SalesTargetSettings/index/<?= $page ?>/?orderField=status&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'status') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'status') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('sales_targer_status')?>
                            </a>
                        </th>
                        <th><?= lang('actions')?></th>
            </tr>
            </thead>
            <tbody>
		        <?php if(isset($information) && count($information) > 0 ){ ?>
		        <?php foreach($information as $data){ if($data['status'] == 1){ 
		            $data['status'] = lang('active'); 
		          }else{ 
		            $data['status'] = lang('inactive'); 
		          }?>
		        <tr>
		         <td><?php  echo $data['name']. ' (' . $data['user_type'] . ')';?></td>
		          <td><?php echo $data['month'];?></td>
		          <td class="col-md-1"><?php echo $data['currency_symbol']." ".$data['target'];?></td>		         
		           <td><?php echo $data['status'];?></td>
		          <td class="bd-actbn-btn"><?php if(checkPermission('SalesTargetSettings','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/view/'.$data['target_id']);?>" data-toggle="ajaxModal" title="<?= lang('view')?>"><i class="fa fa-search fa-x greencol" ></i></a><?php }?><?php if(checkPermission('SalesTargetSettings','edit')){ ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['target_id']);?>" data-toggle="ajaxModal" title="<?= lang('edit')?>" ><i class="fa fa-pencil bluecol" ></i></a><?php }?><?php if(checkPermission("SalesTargetSettings","delete")){ ?><a data-href="javascript:;" onclick="delete_request(<?php echo $data['target_id']; ?>);" title="<?= lang('delete')?>" ><i class="fa fa-remove redcol" ></i></a><?php } ?></td>
		        </tr>
		        <?php } ?>
		      <?php } else { ?>
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
                        
                        //echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
<script> 
	function delete_request(template_id){
	    var delete_meg ="<?php echo $this->lang->line('salesTarget_delete_confrim_msg');?>";

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
                        window.location.href = "SalesTargetSettings/deletedata/" + template_id;
                        dialog.close();
                    }
                }]
            });
}
</script>	