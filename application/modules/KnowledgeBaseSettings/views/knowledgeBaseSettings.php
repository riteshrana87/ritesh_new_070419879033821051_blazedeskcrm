
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

<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('knowledgeBaseSettings_add')?><?php }elseif($formAction == "updatedata" && !isset($readonly)){?><?=$this->lang->line('knowledgeBaseSettings_update')?><?php }elseif(isset($readonly)){?><?=$this->lang->line('knowledgeBaseSettings_view')?><?php } ?></div></h4>
        </div>
        <!--<form id="registration" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>-->
		<?php $attributes = array("name" => "knowledgeBaseSettings", "id" => "knowledgeBaseSettings", "data-parsley-validate" => "");
			echo form_open_multipart($path, $attributes);
		?>
            <div class="modal-body">
                <div class="form-group">
					<label for="type"><?=$this->lang->line('type')?><?php if($disable ==""){?>*<?php }?></label>
					<input class="form-control" name="type" placeholder="<?=$this->lang->line('knowledgeBaseSettings_type')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('type');?><?php }else{?><?=!empty($editRecord[0]['type'])?$editRecord[0]['type']:''?><?php }?>"  required="" <?php echo $disable; ?>/>
					
				</div>
				 <div class="form-group">
		            <label for="status">
		              <?=$this->lang->line('knowledgeBaseSettings_status')?>
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
               		<input name="type_id" type="hidden" value="<?=!empty($editRecord[0]['type_id'])?$editRecord[0]['type_id']:''?>" />
               		  <?php if($formAction == "insertdata"){?>
               		   <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_type')?>" />
               		  <?php }else{?>
               		   <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_type')?>" />
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
	$('#knowledgeBaseSettings').parsley();
	$('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
});
</script>