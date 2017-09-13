
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;
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
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('estimate_settings_add')?><?php }elseif( $formAction == "updatedata" && !isset($readonly)){ ?><?=$this->lang->line('estimate_settings_update')?><?php }else{ ?><?=$this->lang->line('estimate_settings_view')?><?php }?></div></h4>
        </div>
        <form id="estimateSettings" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>
            <div class="modal-body">							
                <div class="form-group">
					<label for="name"><?=$this->lang->line('estimate_settings_name')?><?php if($disable ==""){?>*<?php }?></label>
					<?php if($disable == ""){?>
					<input class="form-control" name="name" placeholder="<?=$this->lang->line('estimate_settings_name')?>" type="text" maxlength="85" value="<?PHP if($formAction == "insertdata"){ echo set_value('name');?><?php }else{?><?=!empty($editRecord[0]['name'])?htmlentities($editRecord[0]['name']):''?><?php }?>" required="" <?php echo $disable; ?> />
					<?php }else{?>
					<p><?php if(isset($editRecord[0]['name'])){ echo $editRecord[0]['name']; }?></p>
					<?php }?>			
				</div>     
				<?php if( !isset($readonly) && ($formAction == "insertdata" || $formAction == "updatedata") ){?>          		    
		       <div class="form-group">
		          <label for="terms"><?=$this->lang->line('estimate_settings_terms')?><?php if($disable ==""){?>*<?php }?></label>
		        	<ul class="parsley-errors-list filled" id="termsError" ><li class="parsley-required">This value is required.</li></ul> 
		          <textarea class="form-control" id="terms" name="terms"  placeholder="<?=$this->lang->line('estimate_settings_terms')?>" value="" <?php echo $disable; ?> ><?PHP if($formAction == "insertdata"){ echo set_value('terms');?><?php }else{?><?=!empty($editRecord[0]['terms'])?$editRecord[0]['terms']:''?><?php }?></textarea> 
		       </div>
		       <?php }else{?>
		        <div class="form-group" style="max-height: 300px; overflow-x: hidden;" >
		         <label for="terms"><?=$this->lang->line('estimate_settings_terms')?></label>
		        <p><?PHP echo $editRecord[0]['terms']; ?></p>
		        </div>
		       <?php }?>
		       <div class="form-group">
           		 <label for="status">
              		<?=$this->lang->line('estimate_settings_status')?>
            	</label>
            <?php
            	if($disable == ""){
            		 $options = array('1'=>lang('active'),'0'=>lang('inactive'));
				 $name = "status";
				 if($formAction == "insertdata"){
					 	$selected = 1; 
				 }else{
					 	 $selected = $editRecord[0]['status']; 					 		 	
				 }		 		
				 echo dropdown( $name, $options, $selected,$disable ); 
            	}else{
            		if(isset($editRecord[0]['status'])){
            			if($editRecord[0]['status']==1 ){
            				echo "<p>Active</p>";
            			}else{
            				echo "<p>InActive</p>";
            			}
            		}
            	}
				
			?>
          </div>
            </div>
            <?php if(!isset($readonly)){ ?>
            <div class="modal-footer">           
                <center> 
                <input name="estimate_settings_id" type="hidden" value="<?=!empty($editRecord[0]['estimate_settings_id'])?$editRecord[0]['estimate_settings_id']:''?>" />
              <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
              <?php  if($formAction == "insertdata"){?>
              <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_estimate_button')?>" />
              <?php }else{?>
              <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_estimate_button')?>" />
              <?php }?>
					<input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>								
		            
            </div>
            <?php } ?>
        </form>
    </div>
</div>
<script> 

$(document).ready(function () {
	$("#termsError").css("display", "none");
	$('#estimateSettings').parsley();
	$('#terms').summernote({
		height: 150,   //set editable area's height
		  codemirror: { // codemirror options
		    theme: 'monokai'
		  }	   
	  });
	
	$('body').delegate('#estimateSettings', 'submit', function () {
		//var code1 = $('#terms').code();
		 var wys = $('.note-editable').html();
         var value =   wys.replace(/(<([^>]+)>)/ig,"");
         var final_value = value.replace(/&nbsp;/g,'');         
         var exp=/^\s+/g;
         final_value = final_value .replace(/^\s+/g,'');   
		//if(code1 !== '' && code1 !== '<p><br></p>'){
		if(final_value !== ''){
			$("#termsError").css("display", "none");
			response = true;
		    return true;
		}else{
			$("#termsError").css("display", "block");
			response = false;
			return false;
		}		
	});
	
});

/*
$(document).ready(function () {	 
	
	$("#termsError").css("display", "none");
	
	$('#estimateSettings').parsley();
	$('#terms').summernote({
		height: 150,   //set editable area's height
		  codemirror: { // codemirror options
		    theme: 'monokai'
		  }	   
	  });

	
	$('body').delegate('#estimateSettings', 'submit', function () {
		alert($('#terms').text());
	    var code = $('#terms').code(),
	
	    	filteredContent = $(code).text().replace(/\s+/g, '');
			
			if(filteredContent.length > 0) {
				$("#termsError").css("display", "none");
				response = true;
			   return true;
			} else {		
				$("#termsError").css("display", "block");
				response = false;
				return false;
			    // content is empty
			}
			
    });      
});
*/
</script>