
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editModuleRecord)?'updateModule?id='.$id:'insertModule'; 
$path = $crnt_view.'/'.$formAction;
?>
<!DOCTYPE html>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertModule"){ ?><?=$this->lang->line('add_module')?><?php }else{ ?><?=$this->lang->line('edit_module')?><?php }?></div></h4>
        </div>
        <form id="moduleform" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>
            <div class="modal-body">
            	 <div class="form-group">
				 <label for="component_name"><?=$this->lang->line('component_name')?></label>
				 	<?php
				 		 $options = array('CRM'=>"CRM",'PM'=>"Project Management",'Finance'=>"Finance",'Support'=>"Support",'HR'=>"HR",'User'=>"User",'settings'=>"Settings");
				 		 $name = "component_name";
				 		 if($formAction == "insertModule"){
				 		 	$selected = 1; 
				 		 }else{
				 		 	 $selected = $editModuleRecord[0]['component_name']; 
				 		 }		 		
				 		 echo dropdown( $name, $options, $selected ); 
				 	?>
				 	 <span class="text-danger"><?php echo form_error('component_name'); ?></span>
				</div>
				
            	<!-- <div class="form-group">
					<label for=component_name><?=$this->lang->line('component_name')?></label>
					<input class="form-control" name="component_name" placeholder="<?=$this->lang->line('component_name')?>" type="text" value="<?=!empty($editModuleRecord[0]['component_name'])?$editModuleRecord[0]['component_name']:''?>" data-parsley-pattern="/^\S*$/" required="" />
					<span class="text-danger"><?php echo form_error('component_name'); ?></span>
				</div> -->	
			
				<div class="form-group">
					<label for="module_name"><?=$this->lang->line('module_name')?></label>
					<input class="form-control" name="module_name" placeholder="<?=$this->lang->line('module_name')?>" type="text" value="<?=!empty($editModuleRecord[0]['module_name'])?$editModuleRecord[0]['module_name']:''?>" data-parsley-pattern="/^\S*$/" required="" />
					<span class="text-danger"><?php echo form_error('module_name'); ?></span>
				</div>			
                <div class="form-group">
					<label for="controller_name"><?=$this->lang->line('controller_name')?></label>
					<input class="form-control" name="controller_name" placeholder="<?=$this->lang->line('controller_name')?>" type="text" value="<?=!empty($editModuleRecord[0]['controller_name'])?$editModuleRecord[0]['controller_name']:''?>" data-parsley-pattern="/^\S*$/" required="" />
					<span class="text-danger"><?php echo form_error('controller_name'); ?></span>
				</div>
               <div class="form-group">
				 <label for="module_status"><?=$this->lang->line('module_status')?></label>
				 	<?php
				 		 $options = array('1'=>"Active",'0'=>"InActive");
				 		 $name = "module_status";
				 		 if($formAction == "insertModule"){
				 		 	$selected = 1; 
				 		 }else{
				 		 	 $selected = $editModuleRecord[0]['status']; 
				 		 }		 		
				 		 echo dropdown( $name, $options, $selected ); 
				 	?>
				 	 <span class="text-danger"><?php echo form_error('module_status'); ?></span>
				</div>

            </div>
            <div class="modal-footer">
                <center> 
                <input name="module_id" type="hidden" value="<?=!empty($editModuleRecord[0]['module_id'])?$editModuleRecord[0]['module_id']:''?>" />
              <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
               <?php if($formAction == "insertModule"){?>
                <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_module')?>" />
               <?php }else{?>
                <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_module')?>" />
               <?php }?>
              
				
					<input type="button" style="display:none" class="btn btn-green remove_btn" name="remove" id="remove_btn" value="Remove" /></center>								
		            
            </div>
        </form>
    </div>
</div>
<script> 
$(document).ready(function () {
	$('#moduleform').parsley();
});
</script>