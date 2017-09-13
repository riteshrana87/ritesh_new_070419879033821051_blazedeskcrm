<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata?id='.$id:'insertdata'; 
$path = $crnt_view.'/'.$formAction;
?>
<!DOCTYPE html>
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('add_role')?><?php }else{ ?><?=$this->lang->line('edit_role')?><?php }?></div></h4>
        </div>
        <form <?PHP if($formAction == "insertdata"){ ?>id="addrole"<?php }else{ ?>id="addrole1"<?php }?>id="addrole" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>
            <div class="modal-body">				
                <div class="form-group">
					<label for="role_name"><?=$this->lang->line('role_name')?>*</label>
					<input class="form-control" name="role_name" placeholder="<?=$this->lang->line('role_name')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('role_name');?><?php }else{?><?=!empty($editRecord[0]['role_name'])?htmlentities($editRecord[0]['role_name']):''?><?php }?>" required="" />
					
				</div>              
				<div class="form-group">
				 <label for="status"><?=$this->lang->line('role_status')?></label>
				 	<?php
				 		 $options = array('1'=>lang('active'),'0'=>lang('inactive'));
				 		 $name = "status";
				 		 if($formAction == "insertdata"){
				 		 	$selected = 1; 
				 		 }else{
				 		 	 $selected = $editRecord[0]['status']; 
				 		 }		 		
				 		 echo dropdown( $name, $options, $selected ); 
				 	?>
				 	 <span class="text-danger"><?php echo form_error('status'); ?></span>
				</div>
            </div>
            <div class="modal-footer">
                <center> 
                <input name="role_id" type="hidden" value="<?=!empty($editRecord[0]['role_id'])?$editRecord[0]['role_id']:''?>" />
               <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
               <?php  if($formAction == "insertdata"){?>
               <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_role')?>" />
               <?php }else{ ?>
               <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_role')?>" />
               <?php }?>
               
				<!-- <button name="cancel" type="reset" class="btn btn-default">Reset</button> -->
					<input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>								
		            
            </div>
        </form>
    </div>
</div>
<script> 
$(document).ready(function () {
	
	$('#addrole').parsley();

	$('form#addrole').submit(function(e) {

	    var form = $(this);

	    e.preventDefault();

	    $.ajax({
	        type: "POST",
	        url: "<?php echo base_url($path); ?>",
	        data: form.serialize(), // <--- THIS IS THE CHANGE
	        dataType: "html",
	        success: function(data){
		     
	        	$("#ajaxModal").html(data);
	            //$('#feed-container').prepend(data);
	        },
	        error: function() {
				var delete_meg ="Error posting feed.";
				BootstrapDialog.show(
					{
						title: '<?php echo $this->lang->line('Information');?>',
						message: delete_meg,
						buttons: [{
							label: '<?php echo $this->lang->line('ok');?>',
							action: function(dialog) {
								dialog.close();
							}
						}]
					});
			}
	   });

	});

	
});



</script>