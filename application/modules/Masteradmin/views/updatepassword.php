<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path ='Masteradmin/updatePasswords';
?>
<div class="row fix-height2">
		

 <div class="col-xs-8 col-xs-offset-2 col-md-4 col-sm-6 col-sm-offset-3 col-md-offset-4 login-custm-width">

 <div class="blue-logoin-box pad_tb1"> <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1">
  <?php echo $this->session->flashdata('msgs'); ?>
      <div class="login-title"><?php echo lang('updatepassword')?></div>
	 
	  <div class="">
		<?php $attributes = array("name" => "updatepassword", "id" => "updatepassword", "data-parsley-validate" => "");
			echo form_open_multipart($path, $attributes);
		?>

        <div class="form-group">
        	
			<input class="form-control form-control-login" id="password" name="password" placeholder="<?php echo lang('newpassword')?>" type="password" data-parsley-minlength="6" data-parsley-required="true"  />
       		<span class="text-danger"><?php echo form_error('password'); ?></span>
        </div>
        <div class="form-group">
        	
			<input class="form-control form-control-login" name="cpassword" placeholder="<?php echo lang('CONFIRM_PASSWORD')?>" type="password" data-parsley-equalto="#password" data-parsley-minlength="6" data-parsley-required="true" />
        	<span class="text-danger"><?php echo form_error('cpassword'); ?></span>
        </div>		
        <input type="hidden" id="tokenID" name="tokenID"  value="<?php echo $this->input->get('token');?>">	
       <div class="row"> <div class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3  col-md-4 col-md-offset-4"><button name="submit_btn" type="submit" class="btn full-width btn-white">Submit</button></div></div>
		
      <?php echo form_close(); ?>
	     
	 
	   <div class="clr"></div>
    </div>
	 <div class="col-xs-1 col-md-2 nodata"></div>
	   </div>
       <div class="clr"></div>
       </div>
      
    <div class="clr"></div>
       </div>

 </div>

