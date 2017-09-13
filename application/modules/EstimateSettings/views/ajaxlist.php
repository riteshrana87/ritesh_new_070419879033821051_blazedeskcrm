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
    
$array_list = array("1");     

?>
<style>
#emailTemplateTable thead th:first-child, #emailTemplateTable tbody td:first-child {
    width: 20%;
}
</style>
<div class="table table-responsive" id="postList">
        <table id="emailTemplateTable" class="table table-striped">
            <thead>
            <tr>
                <th class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>EstimateSettings/index/<?= $page ?>/?orderField=name&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'name') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'name') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('estimate_settings_name')?>
                            </a>
                        </th>
                        <th  class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>EstimateSettings/index/<?= $page ?>/?orderField=terms&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'terms') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'terms') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('estimate_settings_terms')?>
                            </a>
                        </th>
                        <!-- <th class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>EstimateSettings/index/<?= $page ?>/?orderField=conditions&sortOrder=<?= $sortOrder ?>" >
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'conditions') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'conditions') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('estimate_settings_conditions')?>
                            </a>
                        </th> -->
                        <th class='sortEmailTemplateList'>
                            <a  href="<?php echo base_url(); ?>EstimateSettings/index/<?= $page ?>/?orderField=status&sortOrder=<?= $sortOrder ?>">
                            <?php
                                if ($sortOrder == 'asc' && $sortField == 'status') {
                                    echo $sortAsc;
                                } else if ($sortOrder == 'desc' && $sortField == 'status') {
                                    echo $sortDesc;
                                } else {
                                    echo $SortDefault;
                                }
                            ?>
                            <?= lang('estimate_settings_status')?>
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
		        <td><?php if(!empty($data['name'])){
                                   if(strlen($data['name']) > 40) {
                                       echo substr($data['name'],0, 32).'...';
                                   }else {
                                       echo $data['name'];
                                   }
                                } ?></td>	
                <td width="50%" ><?php if(!empty($data['terms'])){
                					$data['terms'] = strip_tags($data['terms']);
                                   if(strlen($data['terms']) > 40) {
                                       echo substr($data['terms'],0, 32).'...';
                                   }else {
                                       echo $data['terms'];
                                   }
                                } ?></td>	       
		         <!--  <td><?php //echo substr($data['name'], 0, 35);?></td>     
		          <td><?php //echo substr($data['terms'], 0, 35) ;?></td> --> 
	
		           <td><?php echo $data['status'];?></td>
		          <td class="bd-actbn-btn"><?php if(checkPermission('EstimateSettings','view')){ ?><a data-href="<?php echo base_url($crnt_view.'/view/'.$data['estimate_settings_id']);?>" data-toggle="ajaxModal" title="<?= lang('view')?>" ><i class="fa fa-search fa-x greencol" ></i></a><?php }?><?php if(checkPermission('EstimateSettings','edit')){ ?><a data-href="<?php echo base_url($crnt_view.'/edit/'.$data['estimate_settings_id']);?>" data-toggle="ajaxModal" title="<?= lang('edit')?>"><i class="fa fa-pencil bluecol" ></i></a><?php }?><?php if(checkPermission("EstimateSettings","delete")){ if(!in_array($data['estimate_settings_id'], $array_list)){ ?><a data-href="javascript:;" onclick="delete_request(<?php echo $data['estimate_settings_id']; ?>);" title="<?= lang('delete')?>" ><i class="fa fa-remove redcol" ></i></a><?php } }?></td>
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
					            	echo '<div class="no_of_records">'.'Showing :'.count($information).'</div>';
					            }
                        
                        //echo (!empty($pagination)) ? $pagination : ''; ?>
            </div>
        </div>
    </div>
<script> 
	function delete_request(template_id){
	    var delete_meg ="<?php echo lang('turm_condition_delete_confrim_msg');?>";
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
                        window.location.href = "<?php echo base_url() ?>EstimateSettings/deletedata/" + template_id;
                        dialog.close();
                    }
                }]
            });


}
</script>	