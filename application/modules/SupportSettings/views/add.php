
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;
$selected = "";
if(isset($readonly)){	
	$disable = $readonly['disabled'];
}else{
	$disable = "";
}
?>
<!-- <link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">
<script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script> -->
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('support_settings')?><?php }elseif($formAction == "updatedata" && !isset($readonly)){?><?=$this->lang->line('support_settings_update')?><?php }elseif(isset($readonly)){?><?=$this->lang->line('support_settings_view')?><?php } ?></div></h4>
        </div>
        <!--<form id="registration" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>-->
		<?php $attributes = array("name" => "support_settings", "id" => "support_settings", "data-parsley-validate" => "");
			echo form_open_multipart($path, $attributes);
		?>
            <div class="modal-body">                          
              	<div class="form-group">
					<label for="priority"><?=$this->lang->line('priority')?><?php if($disable ==""){?>*<?php }?></label>
					<input class="form-control" name="priority" placeholder="<?=$this->lang->line('priority')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('lname');?><?php }else{?><?=!empty($editRecord[0]['priority'])?$editRecord[0]['priority']:''?><?php }?>" required="" <?php echo $disable; ?>/>
					
				</div>  
				 <div class="form-group">
		            <label for="status">
		              <?=$this->lang->line('status')?>
		            </label>
		            <?php
						 $options = array('1'=>lang('active'),'0'=>lang('inactive'));
						 $name = "status";
						 if($formAction == "insertdata"){
							 	$selected = 1; 
						 }else{
							 	 $selected = $editRecord[0]['status']; 					 		 	
						 }		 		
						 echo dropdown( $name, $options, $selected,$disable ); 
					?>
		          </div>
            </div>
            <div class="modal-footer">
            <?php if(!isset($readonly)){?>
                <center> 
                	<input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
               		<input name="support_priority_id" type="hidden" value="<?=!empty($editRecord[0]['support_priority_id'])?$editRecord[0]['support_priority_id']:''?>" />
               		   <?php if($formAction == "insertdata"){?>
               		   <input id="submit_btn" type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_priority')?>" />
						<?php }else{?>
						  <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_priority')?>" />
						<?php }?>
					   <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" />
				</center>								
		           <?php }?> 
            </div>
       <?php echo form_close(); ?>
    </div>
</div>
<script> 
$(document).ready(function () {
	$('#support_settings').parsley();
	$('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
});

</script>