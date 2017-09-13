<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->viewname = $this->uri->segment(1);

$SortDefault = '<i class="fa fa-sort"></i>';
$sortAsc = '<i class="fa fa-sort-desc"></i>';
$sortDesc = '<i class="fa fa-sort-asc"></i>';

if ($typesortOrder == "asc")
    $typesortOrder = "desc";
else
    $typesortOrder = "asc";
    
$array_list = array("1");     
?>
<div class="table table-responsive" id="tabletypeDiv">
        <table id="productTable" class="table table-striped">
            <thead>
            <tr>
                <th class='sortSettingsList col-lg-5'>
                            <a  href="<?php echo base_url(); ?>SupportSettings/typeAjaxList/<?= $typePage ?>/?orderTypeField=type&sortOrder=<?= $typesortOrder ?>" >
                            <?php
                                if ($typesortOrder == 'asc' && $typesortField == 'type') {
                                    echo $sortAsc;
                                } else if ($typesortOrder == 'desc' && $typesortField == 'type') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('type')?>
                            </a>
                        </th>					
                        <th class='sortSettingsList col-lg-5'>
                            <a  href="<?php echo base_url(); ?>SupportSettings/typeAjaxList/<?= $typePage ?>/?orderTypeField=status&sortOrder=<?= $typesortOrder ?>">
                            <?php
                                if ($typesortOrder == 'asc' && $typesortField == 'status') {
                                    echo $sortAsc;
                                } else if ($typesortOrder == 'desc' && $typesortField == 'status') {
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
		        <?php if(isset($information_type) && count($information_type) > 0 ){ ?>
		        <?php foreach($information_type as $data){
		        	if($data['status'] == 1){ 
		            $data['status'] = lang('active'); 
		          }else{ 
		            $data['status'] = lang('inactive'); 
		          }  ?>
		        <tr>
		          <td><?php echo $data['type'];?></td>		        
		          <td><?php echo $data['status'];?></td>		         
		          <td class="bd-actbn-btn"><?php if(checkPermission('SupportSettings','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/viewType/'.$data['support_type_id']);?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" style="cursor:pointer;" title="<?= lang('view')?>" ><i class="fa fa-search greencol" ></i></a><?php }?><?php if(checkPermission('SupportSettings','edit')){ ?><a data-href="<?php echo base_url($crnt_view.'/editType/'.$data['support_type_id']);?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" style="cursor:pointer;" title="<?= lang('edit')?>" ><i class="fa fa-pencil bluecol" ></i></a><?php }?><?php if(checkPermission("SupportSettings","delete")){ if(!in_array($data['support_type_id'], $array_list)){?><a  href="javascript:;" onclick="delete_request(<?php echo $data['support_type_id']; ?>);" title="<?= lang('delete')?>"><i class="fa fa-remove redcol"></i></a><?php } }?></td>
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
					            	echo '<div class="no_of_records">'.lang('showing').' :'.count($information_type).'</div>';
					            }
                        
                        //echo (!empty($paginationSales)) ? $paginationSales : ''; ?>
            </div>
        </div>
    </div>
	<script> 
	function delete_request(support_type_id){
	var delete_meg ="<?php echo $this->lang->line('support_type_delete_confrim_msg');?>";
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
                        window.location.href = "SupportSettings/deletedataType/" + support_type_id;
                        dialog.close();
                    }
                }]
            });



}
</script>	
