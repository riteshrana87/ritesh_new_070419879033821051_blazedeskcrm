<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php //pr($param);?>

<nav class="navbar navbar-default navbar-web">
  <div class="menu-whitebg3">
    <div class="container">
      <div class="navbar-header"> <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?=base_url()?>uploads/images/logo.png" alt="" /></a> </div>
      <div class="no-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo base_url('Help/add'); ?>" data-toggle="ajaxModal" class="" aria-hidden="true" data-refresh="true"><?php echo lang('COMMON_HELP_MENU')?></a></li>
          <li><a href="<?php echo base_url('');?>"><?php echo lang('COMMON_LABEL_LOGIN');?></a></li>
          <li class="lang"> <?php echo lang('LANGUAGE_HEADER_MENU_LABEL');?> :
            <div class="dropdown pull-right">
              <?php if(isset($selected_language) && $selected_language !=""){
                   foreach($selected_language as $row){ 
                  ?>
              <button class="dropdown-toggle cust-lang" style="background:#FFF;border:none;box-shadow:none;" type="button" data-toggle="dropdown"><?php echo $row['name']; ?><span class="caret"></span></button>
                   <?php } }else{ ?>
              <button class="dropdown-toggle" type="button" data-toggle="dropdown" style="background:#FFF;border:none;box-shadow:none;" ><?php echo lang('CR_SELECT_CAMPAIGN'); ?> <span class="caret"></span></button>
              <?php }?>
              <ul class="dropdown-menu absposition">
                <!--<li><a href="<?php echo base_url('Set_language?lang=english');?>">English</a></li>
                <li><a href="<?php echo base_url('Set_language?lang=spanish');?>">Spanish</a></li>-->
				<?php 
					$lang_data = getLanguages();					
					foreach($lang_data as $data){  ?>
						  <li><a href="<?php echo base_url('Set_language?lang='.$data['language_name']); ?>"><?=$data['name']?></a></li>
				<?php } ?>
              </ul>
            </div>
          </li>
        </ul>
      </div>
      <!--/.nav-collapse -->
      
      <div class="clr"></div>
    </div>
  </div>
  <div class="clr"></div>
</nav>
<?php /*Mobile Header Start for login page*/?>
<nav class="navbar navbar-default navbar-mobile">
  <div class="container">
    <div class="navbar-header"> <a href="#" class="navbar-brand"><img src="<?=base_url()?>uploads/images/logo.png" alt="" /></a> </div>
    <div class="navbar-collapse no-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><?php echo lang('COMMON_HELP_MENU')?></a></li>
        <li><a href="<?php echo base_url('');?>"><?php echo lang('COMMON_LABEL_LOGIN');?></a></li>
        <li class="dropdown">
          <?php  if (isset($selected_language) && $selected_language != "") { 
                   foreach($selected_language as $row){ ?>
          <a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $row['name']; ?> <span class="caret"></span></a>
                   <?php } } else {?>
          <a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo lang('CR_SELECT_CAMPAIGN'); ?> <span class="caret"></span></a>
          <?php }?>
          <ul class="dropdown-menu">
<!--            <li><a href="<?php echo base_url('Set_language?lang=english');?>">English</a></li>
            <li><a href="<?php echo base_url('Set_language?lang=spanish');?>">Spanish</a></li>-->
              <?php 
					$lang_data = getLanguages();					
					foreach($lang_data as $data){  ?>
						  <li><a href="<?php echo base_url('Set_language?lang='.$data['language_name']); ?>"><?=$data['name']?></a></li>
				<?php } ?>
          </ul>
        </li>
      </ul>
    </div>
    <div class="clr"></div>
  </div>
</nav>
<?php /*Following variable related to parsley JS*/?>
