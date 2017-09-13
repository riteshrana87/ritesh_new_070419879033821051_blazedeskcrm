
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;

?>

<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('emailTemplate_header')?><?php }else{ ?><?=$this->lang->line('emailTemplate_header_update')?><?php }?></div></h4>
        </div>
        <form id="emailtemplate" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>
            <div class="modal-body">							
                <div class="form-group">
					<label for="emailTemplate_sub"><?=$this->lang->line('emailTemplate_sub')?>*</label>
					<input class="form-control" name="emailTemplate_sub" placeholder="<?=$this->lang->line('emailTemplate_sub')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('emailTemplate_sub');?><?php }else{?><?=!empty($editRecord[0]['subject'])?$editRecord[0]['subject']:''?><?php }?>" required="" />					
				</div>               	    
		       <div class="form-group">
		       
		          <label for="emailTemplate_body"><?=$this->lang->line('emailTemplate_body')?>*</label>
		          <textarea class="form-control" id="emailTemplate_body" name="emailTemplate_body" placeholder="<?=$this->lang->line('emailTemplate_body')?>" value="" ><?PHP if($formAction == "insertdata"){ echo set_value('emailTemplate_body');?><?php }else{?><?=!empty($editRecord[0]['body'])?$editRecord[0]['body']:''?><?php }?></textarea>
		          <ul class="parsley-errors-list filled" id="emailTemplatebody" ><li class="parsley-required">This value is required.</li></ul> 
		       </div>  		   
			   <div class="form-group">
					 <label for="status"><?=$this->lang->line('emailTemplate_status')?></label>
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
				</div>
				
				<?php if($formAction == "updatedata" && $editRecord[0]['variable'] != ""){?>
				    <div class="form-group row">
                <div class="col-lg-12"><h4 class="mar_tp0"><code class="colo"><?php echo lang('UPLOAD_NOTE_IMPORTANT');?></code></h4>
                <div class="col-lg-12">
                <ul class="bd-gen-list">                    
                	<li><span class="colo"><?php echo lang('email_template_variables_note');?> <?php echo $editRecord[0]['variable'] ; ?></span></li>             
                </ul></div></div>
            </div>
            <?php }?>
            
            </div>
            <div class="modal-footer">
                <center> 
                <input name="template_id" type="hidden" value="<?=!empty($editRecord[0]['template_id'])?$editRecord[0]['template_id']:''?>" />
              <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
              <?php if($formAction == "insertdata" ){?>
                 <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('create_email_temp_button')?>" />
              <?php }else{?>
                 <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_email_temp_button')?>" />
              <?php }?>
            
				<!-- <button name="cancel" type="reset" class="btn btn-default">Reset</button> -->
					<input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>								
		            
            </div>
        </form>
    </div>
</div>
<script> 

$(document).ready(function () {
        $('body').addClass('modal-open');
	$("#emailTemplatebody").css("display", "none");
	$('#emailTemplate_body').summernote({
		height: 150,   //set editable area's height
		  codemirror: { // codemirror options
		    theme: 'monokai'
		  }	   
	  });	  
	$('#emailtemplate').parsley();
	$('body').delegate('#emailtemplate', 'submit', function () {
		//var code1 = $('#emailTemplate_body').code();		
		 var wys = $('.note-editable').html();
         var value =   wys.replace(/(<([^>]+)>)/ig,"");
         var final_value = value.replace(/&nbsp;/g,'');         
         var exp=/^\s+/g;
         final_value = final_value .replace(/^\s+/g,'');      
		if(final_value !== ''){			
			$("#emailTemplatebody").css("display", "none");
			response = true;
		    return true;
		}else{
			$("#emailTemplatebody").css("display", "block");
			response = false;
			return false;
		}		
	});
	
});
</script>