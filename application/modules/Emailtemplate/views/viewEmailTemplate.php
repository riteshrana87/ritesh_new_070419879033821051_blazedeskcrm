
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($viewEmailTemplate)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;
?>

<?php  echo $this->session->flashdata('verify_msg'); ?>
<!-- summernote Core JS-->


<div class="modal-dialog modal-lg" style="width: 65%;">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('emailTemplate_header')?><?php }else{ ?><?=$this->lang->line('emailTemplate_view_header')?><?php }?></div></h4>
        </div>
        <form id="viewEmailTemplate" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>
            <div class="modal-body">							
                <div class="form-group">
					<label for="emailTemplate_sub"><?=$this->lang->line('emailTemplate_sub')?></label>
					<p><?=!empty($viewEmailTemplate[0]['subject'])?$viewEmailTemplate[0]['subject']:''?></p>
				</div>
               <!-- <div class="form-group">
		          <label for="emailTemplate_variable"><?=$this->lang->line('emailTemplate_variable')?>*</label>
		          <textarea class="form-control" name="emailTemplate_variable" placeholder="<?=$this->lang->line('emailTemplate_variable')?>" value="" readonly><?PHP if($formAction == "insertdata"){ echo set_value('emailTemplate_variable');?><?php }else{?><?=!empty($viewEmailTemplate[0]['variable'])?$viewEmailTemplate[0]['variable']:''?><?php }?></textarea> 
		       </div> -->		    
		       <div class="form-group" style="max-height: 300px; overflow-x: hidden;" >
		          <label for="emailTemplate_body"><?=$this->lang->line('emailTemplate_body')?></label>
		          <p> <?php if(isset($viewEmailTemplate[0]['body']) && $viewEmailTemplate[0]['body'] !=""){ echo $viewEmailTemplate[0]['body']; }?> </p> 
		        
		       </div>   
			   <div class="form-group">
					 <label for="status"><?=$this->lang->line('emailTemplate_status')?></label>
					 	<?php
					 	if(isset($viewEmailTemplate[0]['status']) && $viewEmailTemplate[0]['status'] == 1 ){
					 		echo "<p>".lang('active')."</p>";
					 	}else{
					 		echo "<p>".lang('inactive')."</p>";
					 	}
					 	//echo "HERE :".$viewEmailTemplate[0]['status'];
					 	/*
					 		$readonly="";
					 		 $options = array('1'=>"Active",'0'=>"InActive");
					 		 $name = "status";
					 		 if($formAction == "insertdata"){
					 		 	$selected = 1; 
					 		 }else{
					 		 	 $selected = $viewEmailTemplate[0]['status']; 
					 		 	$readonly = "disabled";
					 		 }	
					 		
					 		echo dropdown( $name, $options, $selected,$readonly ); 
					 		*/
					 	?>					 	 
				</div>
            </div>
            <div class="modal-footer">
                <center> 
                 <input name="template_id" type="hidden" value="<?=!empty($viewEmailTemplate[0]['template_id'])?$viewEmailTemplate[0]['template_id']:''?>" />
              	 <!-- <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="Test" /> -->              	
				</center>					
		    </div>
		    
        </form>
    </div>
</div>
<script> 
$(document).ready(function () {
	$('#viewEmailTemplate').parsley();
	//$('#emailTemplate_body').wysihtml5();
        $('body').addClass('modal-open');
});
</script>