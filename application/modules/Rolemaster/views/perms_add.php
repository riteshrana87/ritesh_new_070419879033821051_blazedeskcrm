
<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($perms_list)?'updatePerms?id='.$id:'insertPerms';
$path = $crnt_view.'/'.$formAction;
//print_r($perms_list);
?>
<!DOCTYPE html>

<div class="container">
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<?php  echo $this->session->flashdata('verify_msg'); ?>
	</div>
</div>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
			<h4><?PHP if($formAction == "insertPerms"){ ?><?=$this->lang->line('add_perms')?><?php }else{ ?><?=$this->lang->line('edit_perms')?><?php }?>
				</h4>
			</div>
			<div class="panel-body">
				<?php $attributes = array("name" => "permissionform");
				echo form_open(base_url($path));?>
				<div class="form-group">
					<input name="id" type="hidden" value="<?=!empty($perms_list[0]['id'])?$perms_list[0]['id']:''?>" />
				</div>	
				
				<div class="form-group">
					<label for="perms_name"><?=$this->lang->line('perms_name')?>*</label>
					<input class="form-control" name="perms_name" placeholder="<?=$this->lang->line('perms_name')?>" type="text" value="<?=!empty($perms_list[0]['name'])?$perms_list[0]['name']:''?>" required />
					<span class="text-danger"><?php echo form_error('perms_name'); ?></span>
				</div>
				
				 <div class="form-group">
         			 <label for="perms_defination"><?=$this->lang->line('perms_defination')?>*</label>
          			 <textarea class="form-control" name="perms_defination" placeholder="<?=$this->lang->line('perms_defination')?>" value="" required><?=!empty($perms_list[0]['defination'])?$perms_list[0]['defination']:''?></textarea>  
                     <span class="text-danger"><?php echo form_error('perms_defination'); ?></span>
       			 </div>
       
				<div class="form-group">
				<input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
					<button name="submit" type="submit" class="btn btn-default">Submit</button>
					
				</div>
				<?php echo form_close(); ?>				
			</div>
		</div>
	</div>
</div>
</div>
