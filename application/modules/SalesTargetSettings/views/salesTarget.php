<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;
if(isset($readonly)){	
	$disable = $readonly['disabled'];
}else{
	$disable = "";
}
$selected_month = "";
$selected_month = date('F', time());

//pr($editRecord);

?>

<?php  echo $this->session->flashdata('verify_msg'); ?>
<div class="modal-dialog">
    <div class="modal-content costmodaldiv">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" title="<?php echo lang('close') ?>" >&times;</button>
            <h4 class="modal-title"><div class="title"><?PHP if($formAction == "insertdata"){ ?><?=$this->lang->line('add_target_title')?><?php }elseif( $formAction == "updatedata" && !isset($readonly)){ ?><?=$this->lang->line('update_target_title')?><?php }else{ ?><?=$this->lang->line('view_target_title')?><?php }?></div></h4>
        </div>
        <form id="estimateSettings" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>
            <div class="modal-body">
          		<div class="form-group <?php if($disable !=""){?>viewPage<?php }?>" >          		
                <label for="name"><?php echo lang('employee_name'); ?><?php if($disable ==""){?>*<?php }?></label>
               <?php if($disable ==""){?>
                <select class="chosen-select form-control " data-parsley-errors-container="#employee_name_error" placeholder="<?php echo lang('employee_name'); ?>"  name="employee_name" id="employee_name" required <?php echo $disable; ?> >
                  <option value="">
                  <?= $this->lang->line('select_employee_name') ?>
                  </option>
                  <?php 
                        $login_id = $editRecord[0]['login_id'];?>
                       
                  <?php foreach($employee_name_list as $row){
                            if($login_id == $row['login_id']){?>
                  <option selected value="<?php echo $row['login_id'];?>"><?php echo $row['name']. '(' . $row['user_type'] . ')';?></option>
                  <?php }else{?>
                  <option value="<?php echo $row['login_id'];?>"><?php echo $row['name']. ' (' . $row['user_type'] . ')';?></option>
                  <?php }}?>
                </select>
               
               <?php }else{?>
               
               	<p><?php echo $employee_name; ?></p>
               
               <?php }?>                
                
                <span id="employee_name_error"></span>
                </div>   
				<div class="form-group <?php if($disable !=""){?>viewPage<?php }?>" >          		
                <label for="month"><?php echo lang('month'); ?><?php if($disable ==""){?>*<?php }?></label>
                
                <?php if($disable ==""){ ?>
                	  <select class="chosen-select form-control " data-parsley-errors-container="#month_list" placeholder="<?php echo lang('month'); ?>"  name="month" id="month" required <?php echo $disable; ?> >
                  <option value="">
                  <?= $this->lang->line('select_month') ?>
                  </option>                 
                        <?php foreach($months as $month){  ?>
                        <?php if(isset($editRecord[0]['month'])){?>
                        	<?php if($month==$editRecord[0]['month']){$sel = 'selected';?>
                        	
                        	<option <?php echo $sel; ?> value="<?php echo $month;?>"><?php echo $month;?></option> 
                        	<?php }else{ $sel = '';?>
                        	<option <?php echo $sel; ?> value="<?php echo $month;?>"><?php echo $month;?></option> 
                        	<?php }?>
                  			     
                        <?php }else{
                        	//  $selected = is_null($selected) ? date('F', time()) : $selected;
                        		if($month==$selected_month){
                        			$sel = 'selected';
                        		}else{
                        				$sel = '';
                        		}	
                        	?>
                         <option <?php echo $sel; ?> value="<?php echo $month;?>"><?php echo $month; ?></option>
                        <?php }?>
                        <?php }?>
                  
                </select>
                <?php }else{?>
                <p> <?php if(isset($editRecord[0]['month'])){ echo $editRecord[0]['month'];}?></p>
                <?php }?>
              
                
                <span id="month_list"></span>
                </div>  
                   <div class="row"><div class="col-md-4 col-sm-4">
         		 <div class="form-group <?php if($disable !=""){?>viewPage<?php }?>" >
          		
                <label for="currency_symbol"><?php echo lang('currency_symbol'); ?><?php if($disable ==""){?>*<?php }?></label>
                <?php if($disable ==""){ ?>
                 <select class="form-control" data-parsley-errors-container="#currency_symbol_error" placeholder="<?php echo lang('currency_symbol'); ?>"  name="currency_symbol" id="currency_symbol" required <?php echo $disable; ?> >
                  <option value="">
                  <?= $this->lang->line('select_currency_symbole') ?>
                  </option>
                  <?php
                        $salutions_id = $editRecord[0]['country_id'];?>
                  <?php foreach($currency_symbol as $row){ 
                            if($salutions_id == $row['country_id']){?>
                  <option selected value="<?php echo $row['country_id'];?>"><?php echo $row['currency_code']. ' (' . $row['currency_symbol'] . ')';?></option>
                  <?php }else{?>
                  <option value="<?php echo $row['country_id'];?>"><?php echo $row['currency_code']. ' (' . $row['currency_symbol'] . ')';?></option>
                  <?php }}?>
                </select>
                <?php }else{ ?>
                <?php if(isset($editRecord[0]['currency_code'])){?>
               		<?php echo "<p>".$editRecord[0]['currency_code']." ( ".$editRecord[0]['currency_symbol']." ) "."</p>";?>
                <?php }?>                
                <?php }?>
               
                <span id="currency_symbol_error"></span>
                </div>
              </div>
              <div class="col-md-8 col-sm-8">
                <div class="form-group">
                  <label for="target">
                    <?=$this->lang->line('target');?>
                    <?php if($disable ==""){?>*<?php }?></label>
                    <?php if($disable ==""){?>
                     <input class="form-control" name="target" placeholder="<?=$this->lang->line('target')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('target');?><?php }else{?><?=!empty($editRecord[0]['target'])?$editRecord[0]['target']:''?><?php }?>" min="0" required="" <?php echo $disable; ?> />
                    <?php }else{ ?>
                    <?php if(isset($editRecord[0]['target'])){ ?>
                    <p><?php  echo $editRecord[0]['target']; ?></p>
                    <?php }?>
                    <?php } ?>
                 
                </div>
              </div></div>
		       <div class="form-group">
           		 <label for="status">
              		<?=$this->lang->line('sales_targer_status')?>
            	</label>
            <?php
            if($disable ==""){
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
					if($editRecord[0]['status'] == 1){
						echo "<p>".lang('active')."</p>";
					}else{
						echo "<p>".lang('inactive')."</p>";
					}
					
				}
            }
				 
				 
			?>
          </div>
            </div>
            <?php if(!isset($readonly)){ ?>
            <div class="modal-footer">           
                <center> 
                <input name="target_id" type="hidden" value="<?=!empty($editRecord[0]['target_id'])?$editRecord[0]['target_id']:''?>" />
              <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
              <?php  if($formAction == "insertdata"){?>
              <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('add_target')?>" />
              <?php }else{?>
              <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_target')?>" />
              <?php }?>
					<input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" /></center>								
		    </div>
            <?php } ?>
        </form>
    </div>
</div>
<script> 

$(document).ready(function () {
	$('.chosen-select').chosen();   
	$("#termsError").css("display", "none");
	$('#estimateSettings').parsley();
	$('#terms').summernote({
		height: 150,   //set editable area's height
		  codemirror: { // codemirror options
		    theme: 'monokai'
		  }	   
	  });
	
	$('body').delegate('#estimateSettings', 'submit', function () {
		var code1 = $('#terms').code();
		if(code1 !== '' && code1 !== '<p><br></p>'){
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