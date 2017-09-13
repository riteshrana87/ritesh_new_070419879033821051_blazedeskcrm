<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path ='Masteradmin/resetpassword';

?>

<div class="row fix-height2">
  <div class="col-xs-8 col-xs-offset-2 col-md-4 col-sm-6 col-sm-offset-3 col-md-offset-4 login-custm-width">
    <div class="blue-logoin-box pad-top10">
      <div class="login-title "><?php echo lang('FORGOTPASSWORD')?></div>
      
      <div class="col-xs-2 col-md-2 col-lg-2 col-sm-2 nodata "></div>
      <div class="col-xs-10 col-xs-offset-1 col-md-10 col-md-offset-1">
       <?php echo $this->session->flashdata('msg'); ?>
      	<?php $attributes = array("name" => "resetpassword", "id" => "resetpassword", "data-parsley-validate" => "");
			echo form_open_multipart($path, $attributes);
		?>
      
       <?php //echo form_open('Masteradmin/resetpassword'); ?>
       
        <div class="form-group">
          <input class="form-control form-control-login" name="email" placeholder="<?php echo lang('COMMON_LABEL_EMAIL')?>" data-parsley-trigger="change" required="" type="email" data-parsley-email />
        </div>
        <div class="row">
          <div class="col-xs-8 col-xs-offset-2 col-sm-6 col-sm-offset-3  col-md-4 col-md-offset-4">
            <button name="submit_btn" type="submit" class="btn full-width btn-white"><?php echo lang('submit');?></button>
          </div>
          <div class="clr"></div>
        </div>
        <?php echo form_close(); ?>
        <!--<div class="whiteline"></div>
        <p class="whitefont-2"> <?php echo lang('LOGIN_DO_NOT_HAVE_ACNT');?> </p>
        <p class="whitefont-2 pad-b12">
          <button type="submit" class="btn btn-default btn-red1"><?php echo lang('LOGIN_REQUEST_YOUR_TRIAL');?></button>
        </p> -->
        <div class="clr"></div>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<div class="clr"></div>
<br/> 
</div>
