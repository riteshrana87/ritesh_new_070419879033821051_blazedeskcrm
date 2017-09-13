
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
<!--
<link id="bsdp" href="<?= base_url() ?>uploads/custom/css/bootstrap-chosen.css" rel="stylesheet">
<script src="<?= base_url() ?>uploads/custom/js/chosen.jquery.js"></script>
-->
<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>">&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('currency_settings')?><?php }elseif($formAction == "updatedata" && !isset($readonly)){?><?=$this->lang->line('currency_settings_update')?><?php }elseif(isset($readonly)){?><?=$this->lang->line('view_currency')?><?php } ?></div></h4>
        </div>
        <!--<form id="registration" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>-->
		<?php $attributes = array("name" => "currency_settings", "id" => "currency_settings", "data-parsley-validate" => "");
			echo form_open_multipart($path, $attributes);
		?>
            <div class="modal-body">						
                <div class="form-group">
					 <label for="country_name"><?=$this->lang->line('country_name')?><?php if($disable ==""){?>*<?php }?></label>
					 	<?php if($formAction == "updatedata"){ ?>
					 	<select name="country_id" id="country_id"  class="form-control selectpicker" required="" disabled >
                            <option value=""><?= $this->lang->line('select_country') ?></option>
                            <?php if (isset($country_data) && count($country_data) > 0) { ?>
                                <?php foreach ($country_data as $country_data) { 
									if( $country_data['country_id'] == $editRecord[0]['country_id']){
										$selected = 'selected="selected"';										
									}else{
										$selected = '';									
									}
                                	?>
                                    <option value="<?php echo $country_data['country_id']; ?>" <?php echo $selected; ?> ><?php echo $country_data['country_name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>					 	
					 	<?php }else{?>
					 	<select name="country_id" id="country_id" class="form-control selectpicker chosen-select" required="" >
                            <option value=""><?= $this->lang->line('select_country') ?></option>
                            <?php if (isset($country_data) && count($country_data) > 0) { ?>
                                <?php foreach ($country_data as $country_data) { ?>
                                    <option value="<?php echo $country_data['country_id']; ?>" ><?php echo $country_data['country_name'];  ?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
					 	<?php } ?>
					 						 	 
				</div>				
                <div class="form-group">
					<label for="currency_name"><?=$this->lang->line('currency_name')?><?php if($disable ==""){?>*<?php }?></label>
					<?php if($disable == ""){?>
					<input class="form-control" name="currency_name" placeholder="<?=$this->lang->line('currency_name')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('lname');?><?php }else{?><?=!empty($editRecord[0]['currency_name'])?$editRecord[0]['currency_name']:''?><?php }?>" data-parsley-pattern="/^[a-zA-Z| ]*$/" required="" <?php echo $disable; ?>/>
					<?php }else{ ?>
					<p><?php if(isset($editRecord[0]['currency_name'])){ echo $editRecord[0]['currency_name']; }?></p>
					<?php } ?>					
				</div>
               <div class="form-group">
					<label for="currency_code"><?=$this->lang->line('currency_code')?><?php if($disable ==""){?>*<?php }?></label>
					<?php if($disable == ""){?>
					<input class="form-control" name="currency_code" placeholder="<?=$this->lang->line('currency_code')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('currency_code');?><?php }else{?><?=!empty($editRecord[0]['currency_code'])?$editRecord[0]['currency_code']:''?><?php }?>" data-parsley-pattern="/^[a-zA-Z| ]*$/" required="" <?php echo $disable; ?>/>
					<?php }else{ ?>
					<p><?php if(isset($editRecord[0]['currency_code'])){ echo $editRecord[0]['currency_code']; }?></p>
					<?php } ?>
				</div>
				<div class="form-group">
					<label for="currency_symbol"><?=$this->lang->line('currency_symbol')?><?php if($disable ==""){?>*<?php }?></label>
					<?php if($disable == ""){ ?>
					<input class="form-control" id="currency_symbol" name="currency_symbol" placeholder="<?=$this->lang->line('currency_symbol')?>" type="text"  value="<?PHP if($formAction == "insertdata"){ echo set_value('currency_symbol');?><?php }else{?><?=!empty($editRecord[0]['currency_symbol'])?$editRecord[0]['currency_symbol']:''?><?php }?>" required="" <?php echo $disable; ?>/>
					<?php }else{?>
					<p><?php if(isset($editRecord[0]['currency_symbol'])){ echo $editRecord[0]['currency_symbol']; } ?></p>
					<?php }?>
				</div>
              	<div class="form-group">
              		
					 <label for="use_status"><?=$this->lang->line('use_status')?></label>
					<?php if($disable==""){?>
					<?php
					 		 $options = array('0'=>lang('no'),'1'=>lang('yes'));
					 		 $name = "use_status";
					 		 if($formAction == "insertdata"){
					 		 	$selected = 1; 
					 		 }else{
					 		 	 $selected = $editRecord[0]['use_status']; 
					 		 	
					 		 }		 		
					 		 echo dropdown( $name, $options, $selected,$disable ); 
					 	?>
					<?php }else{ ?>
						<?php if(isset($editRecord[0]['use_status'])){ if($editRecord[0]['use_status'] == 0){ echo "<p>".lang('no')."</p>"; }else{ echo "<p>".lang('yes')."</p>"; }}?>
					<?php }?>					 	 
				</div>
				<div class="form-group">
					 <label for="country_status"><?=$this->lang->line('country_status')?></label>
					 	
					<?php if($disable==""){ ?>
					<?php
					 		 $options = array('0'=>lang('inactive'),'1'=>lang('active'));
					 		 $name = "country_status";
					 		 if($formAction == "insertdata"){
					 		 	$selected = 1; 
					 		 }else{
					 		 	 $selected = $editRecord[0]['country_status']; 
					 		 	
					 		 }		 		
					 		 echo dropdown( $name, $options, $selected,$disable ); 
					 	?>
					<?php }else{ ?>
					<?php if(isset($editRecord[0]['country_status'])){ if($editRecord[0]['country_status'] == 0){ echo "<p>".lang('inactive')."</p>";}else{ echo "<p>".lang('active')."</p>";}}?>
					<?php } ?>					 	 
				</div>              

            </div>
            <div class="modal-footer">
            <?php if(!isset($readonly)){?>
                <center> 
                        <input type="text" id="redirect_link" name="redirect_link"  hidden="" value="<?php  echo $_SERVER['HTTP_REFERER'];?>">
               		<input name="countryId" type="hidden" value="<?=!empty($editRecord[0]['country_id'])?$editRecord[0]['country_id']:''?>" />
               		<?php if($formAction == "insertdata"){?>
               		   <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_currency_button')?>" />
               		   <?php }else{?>
               		   <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_currency_button')?>" />
               		   <?php }?>
					  <!-- <button name="cancel" id="reset" type="reset" class="btn btn-default">Reset</button> -->
					   <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" />
				</center>								
		           <?php }?> 
            </div>
       <?php echo form_close(); ?>
    </div>
</div>
<script> 
$(document).ready(function () {
	$('#currency_settings').parsley();
	$('.chosen-select').chosen();
    $('.chosen-select-deselect').chosen({allow_single_deselect: true});
	/*
    $( "#reset" ).click(function() {      
    	 $('.chosen-select').val('').trigger('chosen:updated');
   });
   */
});
</script>