<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;
if(isset($readonly)){	
	$disable = $readonly['disabled'];
}else{
	$disable = "";
}
$main_user_data = $this->session->userdata('LOGGED_IN');
$main_user_id = $main_user_data['ID'];
// echo "</br>Edit Role NAme :". $checkUserCreateLimit;
?>
<?php  echo $this->session->flashdata('verify_msg'); ?>
	

<div class="modal-dialog modal-lg">
  <div class="modal-content costmodaldiv">
    <div class="modal-header">
      <button type="button" title="<?php echo lang('close') ?>" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">
        <div class="title" >
          <?PHP if($formAction == "insertdata" && $checkUserCreateLimit == "false"){ ?>
          <?=$this->lang->line('user_registration')?>
          <?php }elseif($formAction == "updatedata" && !isset($readonly)){ ?>
          <?=$this->lang->line('update_profile')?>
          <?php }elseif(isset($readonly)){?>
          <?=$this->lang->line('view_profile')?>
          <?php }elseif($formAction == "insertdata" && $checkUserCreateLimit == "true"){ ?>
            <?=$this->lang->line('UPLOAD_NOTE_IMPORTANT')?>
          <?php }?>
        </div>
      </h4>
    </div>
    
    <?php if($formAction == "insertdata" && $checkUserCreateLimit == "true"){ ?>
    <div class="modal-body"> <div class="form-group ">
                <div class=""><h4 class="mar_tp0"><code class="colo"><?php echo lang('user_limit_over');?></code></h4>
               </div>
               <div class="clr"></div>
     </div></div>
    
    <?php }else{ ?>
   
    <!--<form id="registration" method="post" enctype="multipart/form-data" action="<?php echo base_url($path); ?>" data-parsley-validate>-->
    <?php $attributes = array("name" => "registration", "id" => "registration", "data-parsley-validate" => "");
			echo form_open_multipart($path, $attributes);
		?>
    <div class="modal-body">
      <div class="form-group">
        <input name="login_id" type="hidden" value="<?=!empty($editRecord[0]['login_id'])?$editRecord[0]['login_id']:''?>"  />
            <input name="parent_id" type="hidden" value="<?php echo $this->config->item('master_user_id');?>"  />
             <input name="role_selected_id" id="role_selected_id" type="hidden" value="<?=!empty($editRecord[0]['user_type'])?$editRecord[0]['user_type']:''?>"  />          
           	<input name="selected_status" id="selected_status" type="hidden" value="<?=isset($editRecord[0]['status'])?$editRecord[0]['status']:''?>"  />	  
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-6 col-sm-6">
            <div class="row">
              <div class="col-md-4 col-sm-4">
              
          		<div class="form-group <?php if($disable !=""){?>viewPage<?php }?>" >
          		
                <label for="fname"><?php echo lang('SALUTION_PREFIX'); ?><?php if($disable ==""){?>*<?php }?></label>
                <?php if($disable ==""){?>
                <select class="chosen-select-salution form-control " data-parsley-errors-container="#SALUTION_PREFIX_error" placeholder="<?php echo lang('SALUTION_PREFIX'); ?>"  name="salutions_prefix" id="salutions_prefix" required <?php echo $disable; ?> >
                  <option value="">
                  <?= $this->lang->line('SALUTION_PREFIX_SELECT') ?>
                  </option>
                  <?php
                        $salutions_id = $editRecord[0]['salution_prefix'];?>
                  <?php foreach($salution_list as $row){ 
                            if($salutions_id == $row['s_id']){?>
                  <option selected value="<?php echo $row['s_id'];?>"><?php echo $row['s_name'];?></option>
                  <?php }else{?>
                  <option value="<?php echo $row['s_id'];?>"><?php echo $row['s_name'];?></option>
                  <?php }}?>
                </select>
                <span id="SALUTION_PREFIX_error"></span>
                <?php }else{?>
                <p><?php echo $salution_prefix_name; ?></p>
                <?php }?>
                </div>
              </div>
              <div class="col-md-8 col-sm-8">
                <div class="form-group">
                  <label for="name">
                    <?=$this->lang->line('firstname');?>
                    <?php if($disable ==""){?>*<?php }?></label>
                	<?php if($disable ==""){ ?>
                	   <input class="form-control" name="fname" placeholder="<?=$this->lang->line('firstname')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('fname');?><?php }else{?><?=!empty($editRecord[0]['firstname'])?$editRecord[0]['firstname']:''?><?php }?>" data-parsley-pattern="/^([^0-9]*)$/" required="" <?php echo $disable; ?> />
                	<?php }else{?>
                	<p><?php echo $editRecord[0]['firstname']; ?></p>
                	<?php }?>   
                </div>
              </div>            
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6">
           <div class="form-group">
            <label for="lname">
              <?=$this->lang->line('lastname')?>
              <?php if($disable ==""){?>*<?php }?></label>
              <?php if($disable ==""){ ?>
              <input class="form-control" name="lname" placeholder="<?=$this->lang->line('lastname')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('lname');?><?php }else{?><?=!empty($editRecord[0]['lastname'])?$editRecord[0]['lastname']:''?><?php }?>" data-parsley-pattern="/^([^0-9]*)$/" required="" <?php echo $disable; ?> />
              <?php }else{?>
              <p><?php echo $editRecord[0]['lastname']; ?></p>
              <?php }?>
            
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6">
        
        </div>
        <div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div class="row">
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="email">
              <?=$this->lang->line('emails')?>
              <?php if($disable ==""){?>*<?php }?></label>
              <?php if($disable ==""){?>
               <input class="form-control" id="email" name="email" autocomplete="false" placeholder="<?=$this->lang->line('emails')?>" data-parsley-trigger="change" required="" type="email" value="<?PHP if($formAction == "insertdata"){ echo set_value('email');?><?php }else{?><?=!empty($editRecord[0]['email'])?$editRecord[0]['email']:''?><?php }?>" <?php echo $disable; ?> data-parsley-email />
              <?php }else{?>
              <p><?php echo $editRecord[0]['email']; ?></p>
              <?php }?>
           
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6"> </div>
      </div>
      <div class="clr"></div>
      <div class="row">
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="address">
              <?=$this->lang->line('address1')?>
              </label>
              <?php if($disable ==""){?>
               <input class="form-control" name="address" autocomplete="false" placeholder="<?=$this->lang->line('address1')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('address');?><?php }else{?><?=!empty($editRecord[0]['address'])?$editRecord[0]['address']:''?><?php }?>" <?php echo $disable; ?> />
              <?php }else{?>
              <p><?php echo $editRecord[0]['address']; ?></p>
			<?php }?>		        
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="address1">
              <?=$this->lang->line('address2')?>
            </label>
            <?php if($disable ==""){ ?>
            <input class="form-control" name="address_1" autocomplete="false" placeholder="<?=$this->lang->line('address2')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('address_1');?><?php }else{?><?=!empty($editRecord[0]['address_1'])?$editRecord[0]['address_1']:''?><?php }?>" <?php echo $disable; ?> />
            <?php }else{?>
            <p><?php echo $editRecord[0]['address_1']; ?></p>
            <?php }?>
            
          </div>
        </div>
        <div class="clr"></div>
      </div>
      <?php  if(!isset($readonly)){ ?>
      <div class="row">
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="password">
              <?=$this->lang->line('password')?>
              <?php if($disable ==""){?>*<?php }?></label>
            <input class="form-control" id="password" name="password" placeholder="<?=$this->lang->line('password')?>" type="password" data-parsley-minlength="6" <?php if($formAction == "insertdata"){ ?> data-parsley-required="true"  <?php }?> <?php echo $disable; ?> />
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="cpassword">
              <?=$this->lang->line('cpassword')?>
             <?php if($disable ==""){?>*<?php }?></label>
            <input class="form-control" id="cpassword" name="cpassword" placeholder="<?=$this->lang->line('cpassword')?>" type="password" data-parsley-equalto="#password" data-parsley-minlength="6" <?php if($formAction == "insertdata"){ ?> data-parsley-required="true" <?php }?> <?php echo $disable; ?> />
          </div>
        </div>
        <div class="clr"></div>
      </div>
      <?php }?>
      <div class="row">
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="city"><?php echo lang('MY_PROFILE_CITY'); ?></label>
            <?php if($disable ==""){?>
            <input type="text" class="form-control" name=city id="city" placeholder="<?php echo lang('MY_PROFILE_CITY'); ?>" value="<?PHP if($formAction == "insertdata"){ echo set_value('city');?><?php }else{?><?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?><?php }?>" data-parsley-id="14"  <?php echo $disable; ?> />
			<?php }else{ ?>
			<p><?php echo $editRecord[0]['city']; ?></p>
			<?php }?>
		      
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="state"><?php echo lang('MY_PROFILE_STATE'); ?></label>
            <?php if($disable ==""){?>
            <input type="text" class="form-control" name="state" id="state" placeholder="<?php echo lang('MY_PROFILE_STATE'); ?>" value="<?PHP if($formAction == "insertdata"){ echo set_value('state');?><?php }else{?><?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?><?php }?>" data-parsley-id="14"  <?php echo $disable; ?> />
            <?php }else{?>
            <p><?php echo $editRecord[0]['state']; ?></p>
            <?php }?>            
          </div>
        </div>
        <div class="clr"></div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="pincode"><?php echo lang('MY_PROFILE_PINCODE'); ?></label>
            <?php if($disable ==""){?>
             <input type="text" class="form-control" name="pincode" id="pincode" placeholder="<?php echo lang('MY_PROFILE_PINCODE'); ?>" value="<?PHP if($formAction == "insertdata"){ echo set_value('pincode');?><?php }else{?><?=!empty($editRecord[0]['pincode'])?$editRecord[0]['pincode']:''?><?php }?>" data-parsley-id="14" <?php echo $disable; ?> />
            <?php }else{?>
            <p><?php  echo $editRecord[0]['pincode']; ?></p>
            <?php }?>
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group <?php if($disable !=""){?>viewPage<?php }?>">
            <label for="country">
              <?=$this->lang->line('country_name')?>
             </label>
             <?php if($disable ==""){?>
              <?php if($formAction == "updatedata"){ ?>
            <select name="country" id="country"  class="form-control selectpicker chosen-select" <?php echo $disable; ?> >
              <option value="">
              <?= $this->lang->line('select_country') ?>
              </option>
              <?php if (isset($country_data) && count($country_data) > 0) { ?>
              <?php foreach ($country_data as $country_data) { 
									if( $country_data['country_id'] == $editRecord[0]['country']){
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
            <select name="country" id="country" class="form-control selectpicker chosen-select">
              <option value="">
              <?= $this->lang->line('select_country') ?>
              </option>
              <?php if (isset($country_data) && count($country_data) > 0) { ?>
              <?php foreach ($country_data as $country_data) { ?>
              <option value="<?php echo $country_data['country_id']; ?>" ><?php echo $country_data['country_name'];  ?></option>
              <?php } ?>
              <?php } ?>
            </select>
            <?php } ?>
             <?php }else{?>
             <p><?php echo $countryName; ?></p>
             <?php }?>
           
          </div>
        </div>
        <div class="clr"></div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="telephone1">
              <?=$this->lang->line('telephone1')?>
              <?php if($disable ==""){?>*<?php }?></label>
              <?php if($disable ==""){?>
              <input class="form-control" name="telephone1" placeholder="<?=$this->lang->line('telephone1')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('telephone1');?><?php }else{?><?=!empty($editRecord[0]['telephone1'])?$editRecord[0]['telephone1']:''?><?php }?>" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" maxlength="25" required="" <?php echo $disable; ?> />
              <?php }else{?>
              <p><?php echo $editRecord[0]['telephone1']; ?></p>
              <?php }?>
          </div>
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="telephone2">
              <?=$this->lang->line('telephone2')?>
            </label>
            <?php if($disable ==""){?>
            <input class="form-control" name="telephone2" placeholder="<?=$this->lang->line('telephone2')?>" type="text" value="<?PHP if($formAction == "insertdata"){ echo set_value('telephone2');?><?php }else{?><?=!empty($editRecord[0]['telephone2'])?$editRecord[0]['telephone2']:''?><?php }?>" data-parsley-pattern="^[\d\+\-\.\(\)\/\s]*$" maxlength="25" <?php echo $disable; ?> />
            <?php }else{?>
            <p><?php echo $editRecord[0]['telephone2']; ?></p>
            <?php }?>
            
          </div>
        </div>
        <div class="clr"></div>
      </div>
      <div class="row">
        <div class="col-xs-12 col-md-6 col-sm-6">        
            <div class="form-group <?php if($disable !=""){?>viewPage<?php }?> ">
                <label for="usertype"><?=$this->lang->line('usertype')?><?php if($disable ==""){?>*<?php }?></label>
                <?php if($disable =="" && ($formAction == "insertdata")){ ?>
                <select class="chosen-select form-control" data-parsley-errors-container="#usertype_error" placeholder="<?=$this->lang->line('usertype')?>" onchange="assignModuleCount(this.value)"  name="usertype" id="usertype" required <?php echo $disable; ?> >
                  <option value="">
                  <?= $this->lang->line('usertype_select') ?>
                  </option>
                  <?php
                        $salutions_id = $editRecord[0]['user_type'];?>
                       
                  <?php foreach($userType as $row){ 
                            if($salutions_id == $row['role_id']){?>
                  <option selected value="<?php echo $row['role_id'];?>"><?php echo $row['role_name'];?></option>
                  <?php }else{?>
                  <option value="<?php echo $row['role_id'];?>"><?php echo $row['role_name'];?></option>
                  <?php }}?>
                </select>
                <?php }elseif ($disable =="" && ($this->session->userdata['LOGGED_IN']['ID'] == $editRecord[0]['login_id'])){?>
                	<p><?php echo $roleName;// pr($userType) ?></p>
                  <?php }elseif ($disable =="" && ($this->session->userdata['LOGGED_IN']['ID'] != $editRecord[0]['login_id'])){?>
                  <select class="chosen-select form-control" data-parsley-errors-container="#usertype_error" placeholder="<?=$this->lang->line('usertype')?>" onchange="assignModuleCount(this.value)"  name="usertype" id="usertype" required <?php echo $disable; ?> >
                  <option value="">
                  <?= $this->lang->line('usertype_select') ?>
                  </option>
                  <?php
                        $salutions_id = $editRecord[0]['user_type'];?>
                       
                  <?php foreach($userType as $row){ 
                            if($salutions_id == $row['role_id']){?>
                  <option selected value="<?php echo $row['role_id'];?>"><?php echo $row['role_name'];?></option>
                  <?php }else{?>
                  <option value="<?php echo $row['role_id'];?>"><?php echo $row['role_name'];?></option>
                  <?php }}?>
                </select>
                  <?php }else{?>
                <p><?php echo $roleName; ?></p>
                <?php }?>
                <span id="usertype_error"></span>
              </div>      
        </div>
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="status">
              <?=$this->lang->line('user_status')?>
            </label>
            <?php if((isset($editRecord[0]['login_id']) && $this->session->userdata['LOGGED_IN']['ID'] != $editRecord[0]['login_id']) && $disable ==""){?>
            
             <select class="chosen-select-status form-control " data-parsley-errors-container="#STATUS_error" placeholder="<?php echo lang('user_status'); ?>"  name="status" id="status" onchange="checkUserCounts(this.value);" required <?php echo $disable; ?> >
                   <!-- <option value="">
                  <?= $this->lang->line('user_status') ?>
                  </option> --> 
                  <?php 
                  $options = array(array('st_id'=>1,'s_status'=>lang('active')) ,array('st_id'=>0,'s_status'=>lang('inactive')));
                    //$options = array('1'=>lang('active'),'0'=>lang('inactive'));
                  	if(isset($editRecord[0]['status']) && $editRecord[0]['status'] != ""){
                  		  $selected = $editRecord[0]['status']; 
                  	}else{
                  		  $selected = 1;  
                  	}	
                	?>
                  <?php foreach($options as $rows){                   	
                            if($selected == $rows['st_id']){?>
                  <option selected value="<?php echo $rows['st_id'];?>"><?php echo $rows['s_status'];?></option>
                  <?php }else{?>   
                            
                  <option value="<?php echo $rows['st_id'];?>"><?php echo $rows['s_status'];?></option>
                  <?php }}?>
                </select>
             <span id="STATUS_error"></span>             
            <?php }elseif ( (isset($editRecord[0]['login_id']) && $this->session->userdata['LOGGED_IN']['ID'] == $editRecord[0]['login_id']) && $disable =="" ){ ?>
            		<?php if($editRecord[0]['status'] == 1){?>
            	<p><?=lang('active')?></p>
            	<?php }else{?>
            	<p><?=lang('inactive')?></p>
            	<?php }?>
            <?php }elseif($disable =="" && $formAction == "insertdata"){?>
            		<select class="chosen-select-status form-control " data-parsley-errors-container="#STATUS_error" placeholder="<?php echo lang('user_status'); ?>"  name="status" id="status" onchange="checkUserCounts(this.value);" required <?php echo $disable; ?> >
                   <!-- <option value="">
                  <?= $this->lang->line('user_status') ?>
                  </option> --> 
                  <?php 
                  $options = array(array('st_id'=>1,'s_status'=>lang('active')) ,array('st_id'=>0,'s_status'=>lang('inactive')));
                    //$options = array('1'=>lang('active'),'0'=>lang('inactive'));
                  	if(isset($editRecord[0]['status']) && $editRecord[0]['status'] != ""){
                  		  $selected = $editRecord[0]['status']; 
                  	}else{
                  		  $selected = 1;  
                  	}	
                	?>
                  <?php foreach($options as $rows){                   	
                            if($selected == $rows['st_id']){?>
                  <option selected value="<?php echo $rows['st_id'];?>"><?php echo $rows['s_status'];?></option>
                  <?php }else{?>   
                            
                  <option value="<?php echo $rows['st_id'];?>"><?php echo $rows['s_status'];?></option>
                  <?php }}?>
                </select>
            <?php }elseif($disable =! ""){?>
            	
            	<?php if( isset($editRecord[0]['status']) && $editRecord[0]['status'] == 1){?>
            	<p><?=lang('active')?></p>
            	<?php }else{?>
            	<p><?=lang('inactive')?></p>
            	<?php }?>
           
            <?php }?>
           
          </div>
        </div>
        <div class="clr"></div>
      </div>
      <div class="row">
  	
  	<!--<div class="col-xs-12 col-md-12 col-sm-12"><div class="form-group  ">
  	<?php if($disable ==""){?>
  	  <label for="select_user_of"><?=$this->lang->line('select_user_of')?><?php if($disable ==""){?> *<?php }?></label>
  	 <br/>
  	 <?php if(isset($purchasedDetails[0]['is_crm']) && $purchasedDetails[0]['is_crm'] ==1 ){?>
  	 <?php if(isset($editRecord[0]['is_crm_user']) &&  $editRecord[0]['is_crm_user'] ==1 ){?>
  	 <input type="checkbox" name="user_of[]" id="is_CRM_user" value="is_CRM_user"  required checked>&nbsp;<?=$this->lang->line('is_CRM_user')?>
  	 <?php }else{?>
  	 <input type="checkbox" name="user_of[]" id="is_CRM_user" value="is_CRM_user"  required >&nbsp;<?=$this->lang->line('is_CRM_user')?>
  	 <?php }?>
  	 &nbsp;
  	  &nbsp;&nbsp;
  	 <?php }?>
  	 <?php if(isset($purchasedDetails[0]['is_pm']) && $purchasedDetails[0]['is_pm'] ==1 ){ ?>
  	 <?php if(isset($editRecord[0]['is_pm_user']) &&  $editRecord[0]['is_pm_user'] ==1 ){?>
  	 <input type="checkbox" name="user_of[]" id="is_PM_user" value="is_PM_user"  required checked>&nbsp;<?=$this->lang->line('is_PM_user')?>
  	 <?php }else{?>
  	 <input type="checkbox" name="user_of[]" id="is_PM_user" value="is_PM_user"  required >&nbsp;<?=$this->lang->line('is_PM_user')?>
  	 <?php }?>
  	 &nbsp;&nbsp;
  	 <?php } ?>
  	 <?php if(isset($purchasedDetails[0]['is_support']) && $purchasedDetails[0]['is_support'] ==1 ){?>
  	 <?php if(isset($editRecord[0]['is_support_user']) &&  $editRecord[0]['is_support_user'] ==1 ){?>
  	 <input type="checkbox" name="user_of[]" id="is_Support_user" value="is_Support_user"  required checked >&nbsp;<?=$this->lang->line('is_Support_user')?>
  	 <?php }else{?>
  	 <input type="checkbox" name="user_of[]" id="is_Support_user" value="is_Support_user"  required >&nbsp;<?=$this->lang->line('is_Support_user')?>
  	 <?php }?>
  	 		
  	 <?php }?>
	<?php }else{?>
	<label for="select_user_of"><?=$this->lang->line('select_user_of')?><?php if($disable ==""){?> *<?php }?></label>
  	 <br/>
  	 <?php if(isset($purchasedDetails[0]['is_crm']) && $purchasedDetails[0]['is_crm'] ==1 ){?>
  	 	  	 <span><?php echo $this->lang->line('is_CRM_user'); ?> :<?php if(isset($editRecord[0]['is_crm_user']) &&  $editRecord[0]['is_crm_user'] ==1 ){ ?>Yes<?php }else{?>No<?php }?></span>
  	 <?php }?>
 	 <?php if(isset($purchasedDetails[0]['is_pm']) && $purchasedDetails[0]['is_pm'] ==1 ){ ?>
  	 		&nbsp;
  	  &nbsp;&nbsp;<span><?php echo $this->lang->line('is_PM_user'); ?> :<?php if(isset($editRecord[0]['is_pm_user']) &&  $editRecord[0]['is_pm_user'] ==1 ){ ?>Yes<?php }else{?>No<?php }?></span>
  	 <?php } ?>
  	 <?php if(isset($purchasedDetails[0]['is_support']) && $purchasedDetails[0]['is_support'] ==1 ){?>
  	 		&nbsp;
  	  &nbsp;&nbsp;<span><?php echo $this->lang->line('is_Support_user'); ?>:<?php if(isset($editRecord[0]['is_support_user']) &&  $editRecord[0]['is_support_user'] ==1 ){ ?>Yes<?php }else{?>No<?php }?></span>
  	 <?php }?>
	<?php }?>
  </div>
  </div>-->
  <div class="clr"></div>
<?php if($disable ==""){?>
          						  	<div class="col-xs-12 col-md-6 col-sm-6">
                                <div class="form-group">
<!--                                    <label for="profile_pic"><?php //echo lang('PROFILE_PIC'); ?></label>-->
                                    <label class="custom-upload btn btn-blue"><?php echo lang('UPLOAD_IMAGE'); ?>
                                        <input type="file" class="form-control" name="profile_photo" data-parsley-errors-container="#error_file" id="profile_photo" data-parsley-fileextension='png|jpeg|jpg|JPG|PNG|JPEG' data-parsley-max-file-size="2000" placeholder="Profile Pic">
                                    </label>
                                   
                                </div>
							 
        				<?php }?>
        			                          
                            <div class="col-xs-12 col-md-5 col-sm-4">
                                <div class="form-group">
<?php
if (isset($editRecord[0]['profile_photo']) && $editRecord[0]['profile_photo'] != '') {
    ?>
                                        <label for="profile_pic"><?php echo lang('PROFILE_PIC'); ?></label>
                                        <div class="clr"></div>
                                        <?php $path = FCPATH . PROFILE_PIC_UPLOAD_PATH . "/" . $editRecord[0]['profile_photo']; 
                                     
                                        if(file_exists($path)){?>
                                        	 <img height="150" width="150" src="<?php echo base_url() . PROFILE_PIC_UPLOAD_PATH . "/" . $editRecord[0]['profile_photo']; ?>"/>
                                        <?php }else{ ?>
                                        	 <img height="150" width="150" src="<?php echo base_url() . PROFILE_PIC_UPLOAD_PATH . "/noimage.jpg"?>"/>
                                        <?php }?>
                                       
<?php }?>

                                </div>
                            </div>
                            <div class="clr"></div>
                        </div>
                        <div id="error_file"></div>
    <!--   <div class="row">
        <?php
                        if(@$readonly['disabled'] != 'disabled')
                        { ?>
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <label for="profile_pic"><?php echo lang('PROFILE_PIC'); ?></label>
            <input type="file" class="form-control" name="profile_photo" id="profile_photo" placeholder="Profile Pic">
          </div>
        </div>
        <?php  } ?>
        <div class="col-xs-12 col-md-6 col-sm-6">
          <div class="form-group">
            <?php
                                if(isset($editRecord[0]['profile_photo']) && $editRecord[0]['profile_photo'] != '')
                                { ?>
            <label for="profile_pic"><?php echo lang('PROFILE_PIC'); ?></label>
            <div class="clr"></div>
            <img height="150" width="150" src="<?php echo base_url().PROFILE_PIC_UPLOAD_PATH."/".$editRecord[0]['profile_photo']; ?>"/>
            <?php  } ?>
          </div>
        </div>
        <div class="clr"></div>
      </div> -->
       <div class="clr"></div>
    </div>
  
    
    <div class="modal-footer">
      <?php if(!isset($readonly)){ ?>
      <center>
        <input name="login_id" id="login_id" type="hidden" value="<?=!empty($editRecord[0]['login_id'])?$editRecord[0]['login_id']:''?>" />
        <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken();?>">
        <?php if($formAction == "insertdata"){?>
        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('create_user')?>" />
        <?php }else{?>
        <input type="submit" class="btn btn-primary" name="submit_btn" id="submit_btn" value="<?=$this->lang->line('update_user')?>" />
        <?php }?>        
        <input type="button" style="display:none" class="btn btn-info remove_btn" name="remove" id="remove_btn" value="Remove" />
      </center>
      <?php } ?>
    </div>
    <?php echo form_close(); ?> </div>
    
      <?php }?>
</div>
</div>
<script> 
function assignModuleCount(sel){

	var loginID = $("#login_id").val();
	var selected_role_id = $("#role_selected_id").val();
	var selected_status = $("#selected_status").val();
	if(selected_status == ""){
		selected_status = 1;
	}	
	if(sel !=""){
		$.ajax({
	        type: "POST",
	        url: "<?php echo base_url('User/getCountofCreatedUser'); ?>",
	        data: {role_id:sel,login_id:loginID,selected_role_id:selected_role_id,selected_status:selected_status},	      
	        async: false,
	        success: function(result){		
	        	
	        	if(result == "true"){	        	
			    	 response = true;
			     }else{
			    	 $('#submit_btn').hide();
			    	 BootstrapDialog.confirm(result, function (result1)
			                 {			    		 		
			                     if (result1)
			                     {
			                    	 response = false;
			                    	 $('#confirm-id').on('hidden.bs.modal', function () {
				                         $('body').addClass('modal-open');
				                     });
			                    	 // Reset Role Type
				                     if($("#role_selected_id").val() != ""){					                   
				                    	 $('.chosen-select').val($("#role_selected_id").val()).trigger('chosen:updated');
				                    	 $('#submit_btn').show();
				                     }else{					                   
				                    	 $('.chosen-select').val('').trigger('chosen:updated');
				                    	 $('#submit_btn').show();
				                     }
				                     // Reset Status
				                     if($("#selected_status").val() != ""){						                   
						                $('.chosen-select-status').val($("#selected_status").val()).trigger('chosen:updated');
						                $('#submit_btn').show();
					                 }else{						                     
					                    $('.chosen-select-status').val(selected_status).trigger('chosen:updated');
					                    $('#submit_btn').show();
					                 }
			                     }else{
			                    	 response = false;
			                    	 $('#confirm-id').on('hidden.bs.modal', function () {
				                         $('body').addClass('modal-open');
				                     });
			                    	// Reset Role Type
			                    	 if($("#role_selected_id").val() != ""){			                    		 
						                  $('.chosen-select').val($("#role_selected_id").val()).trigger('chosen:updated');
						                  $('#submit_btn').show();
			                    	 }else{
					                      $('.chosen-select').val('').trigger('chosen:updated');
					                      $('#submit_btn').show();
					                 }
			                    	// Reset Status
			                    	 if($("#selected_status").val() != ""){
						                  $('.chosen-select-status').val($("#selected_status").val()).trigger('chosen:updated');
						                  $('#submit_btn').show();
					                 }else{
					                      $('.chosen-select-status').val(selected_status).trigger('chosen:updated');
					                      $('#submit_btn').show();
					                 }
			                    	
			                     }
			                    
			                 });
				    
			     }     	
	        },
	        error: function() { 
		        // alert("Error posting feed."); 
	        	var delete_meg ="<?php echo lang('error_psting_feed');?>";
                BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information');?>',
                        message: delete_meg,
                        buttons: [{
                            label: '<?php echo $this->lang->line('ok');?>',
                            action: function(dialog) {
                            	//$('input[type="submit"]').prop('disabled', false);
                                dialog.close();
                                
                            }
                        }]
                    });
		        }
	   });
	
	} 	
}
// Checking while User Status changes 
function checkUserCounts(sel){
	var loginID = $("#login_id").val();
	//alert(loginID);
	var selected_status = $("#selected_status").val();
	var selected_role_id = $("#role_selected_id").val();			
	if(sel ==1 && selected_role_id != ""){
		$.ajax({
	        type: "POST",
	        url: "<?php echo base_url('User/checkUserCounts'); ?>",
	        data: {selected_role_id:selected_role_id,role_id:$("#usertype").val(),selected_status:selected_status,login_id:loginID},	      
	        async: false,
	        success: function(result){		
		
	        	if(result == "true"){
			    	 response = true;
			     }else{
			    	 $('#submit_btn').hide();
			    	 BootstrapDialog.confirm(result, function (result1)
			                 {
			                     if (result1)
			                     {
			                    	 response = false;
			                    	 $('#confirm-id').on('hidden.bs.modal', function () {
				                         $('body').addClass('modal-open');
				                     });
										
			                    	 if($("#selected_status").val() != ""){						                    
						                  $('.chosen-select-status').val($("#selected_status").val()).trigger('chosen:updated');
						                  $('#submit_btn').show();
							         }else{
					                      $('.chosen-select-status').val('').trigger('chosen:updated');
					                      $('#submit_btn').show();
					                 }
			                    	
			                     }else{
			                    	 response = false;
			                    	
			                    	 $('#confirm-id').on('hidden.bs.modal', function () {
				                         $('body').addClass('modal-open');
				                     });
				                    
			                    	 if($("#selected_status").val() != ""){						                    
						                  $('.chosen-select-status').val($("#selected_status").val()).trigger('chosen:updated');
						                  $('#submit_btn').show();
					                 }else{
					                     $('.chosen-select-status').val('').trigger('chosen:updated');
					                     $('#submit_btn').show();
					                 }			                    	
			                    
							    	 //response = false;
			                     }
			                    
			                 });
				    
			     }     	
	        },
	        error: function() { 
		        //alert("Error posting feed."); 
	        	var delete_meg ="<?php echo lang('error_psting_feed');?>";
                BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information');?>',
                        message: delete_meg,
                        buttons: [{
                            label: '<?php echo $this->lang->line('ok');?>',
                            action: function(dialog) {
                            	//$('input[type="submit"]').prop('disabled', false);
                                dialog.close();
                            }
                        }]
                    });
		        }
	   });		
	}
}

$(document).ready(function () {

	$('#registration').parsley();
	$('.chosen-select').chosen(); 
	$('.chosen-select-salution').chosen();
	$('.chosen-select-status').chosen();
	   <?php if($formAction == "updatedata"){ ?>
		$("#password").keyup(function() {
			var password = $("#password").val();
			password = password.trim();
			if(password != ""){
				$("#cpassword").attr("data-parsley-required","true");
			}else{
				$("#cpassword").attr("data-parsley-required","false");
			}		
		});
	   <?php }?>

	   $('#is_CRM_user').click(function(){
		   
		   if(this.checked) {
	
			   $.ajax({
			        type: "POST",
			        url: "<?php echo base_url('User/checkCRMUserCreateLimites'); ?>",
			        data: {is_user:"is_CRM_user"}, // <--- THIS IS THE CHANGE
			        async: false,
			        success: function(result){
				   
				     if(result == "true"){
				    	 response = true;
				     }else{
				 		 BootstrapDialog.show({ message:result });
				 		 $('#is_CRM_user').attr('checked', false);
				    	 response = false;
				     }
			       
			        },
			        error: function() {
			        	var delete_meg ="<?php echo lang('error_psting_feed');?>";
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
				         // alert("Error posting feed."); 

				     }
			   });
			   
		    }

		    
		}); 


		
       $('#is_PM_user').click(function(){
		   
		   if(this.checked) {
			   $.ajax({
			        type: "POST",
			        url: "<?php echo base_url('User/checkPMUserCreateLimites'); ?>",
			        data: {is_user:"is_PM_user"}, // <--- THIS IS THE CHANGE
			        async: false,
			        success: function(result){			        
				     if(result == "true"){
				    	 response = true;
				     }else{ 
					     BootstrapDialog.show({ message:result });
			 		     $('#is_PM_user').attr('checked', false);
			    	     response = false;
				     }
			       
			        },
			        error: function() {
				         // alert("Error posting feed.");
			        	var delete_meg ="<?php echo lang('error_psting_feed');?>";
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
		    }
		}); 
		$('#is_Support_user').click(function(){
		   
		   if(this.checked) {
			   $.ajax({
			        type: "POST",
			        url: "<?php echo base_url('User/checkSupportUserCreateLimites'); ?>",
			        data: {is_user:"is_Support_user"}, // <--- THIS IS THE CHANGE
			        async: false,
			        success: function(result){			        	 
				     if(result == "true"){
				    	 response = true;
				     }else{
				    	 BootstrapDialog.show({ message:result });
			 		     $('#is_Support_user').attr('checked', false);
			    	     response = false;
				     }
			       
			        },
			        error: function() { 
				        // alert("Error posting feed.");
			        	var delete_meg ="<?php echo lang('error_psting_feed');?>";
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
		    }
		}); 
	   
});
window.Parsley.addValidator('email', function (value, requirement) {
    var response = false;
    var form = $(this);
	var email = $("#email").val();

	<?php if(!empty($editRecord[0]['login_id'])){?>
	var userId = <?php echo $editRecord[0]['login_id']; ?>
	<?php }else{?>
	var userId = "";
	<?php }?>

    $.ajax({
        type: "POST",
        url: "<?php echo base_url('User/isDuplicateEmail'); ?>",
        data: {emailID:email,userID:userId}, // <--- THIS IS THE CHANGE
        async: false,
        success: function(result){
	     if(result == "true"){
	    	 response = true;
	     }else{
	    	 response = false;
	     }
       
        },
        error: function() { 
            // alert("Error posting feed."); 
        	var delete_meg ="<?php echo lang('error_psting_feed');?>";
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
   
       return response;
}, 46)
.addMessage('en', 'email', '<?php echo $this->lang->line('emailIDexist');?>');

</script>
<script>
    $(document).ready(function () {
        window.ParsleyValidator
                .addValidator('fileextension', function (value, requirement) {
                    // the value contains the file path, so we can pop the extension
                    var fileExtension = value.split('.').pop();
                    var multipleFileType = requirement.split('|');
                   
                    if ($.inArray(fileExtension, multipleFileType) != -1)
                    {
                        return true;
                    }else
                    {
                        return false;
                    }
                    
                }, 32)
                .addMessage('en', 'fileextension', '<?php echo lang('MSG_UPLOAD_PROFILE_PIC');?>');

        $("#update_myprofile").parsley();

        /*
        window.Parsley.addValidator('maxFileSize', {
            validateString: function (_value, maxSize, parsleyInstance) {
                if (!window.FormData) {
                    alert('You are making all developpers in the world cringe. Upgrade your browser!');
                    return true;
                }
                var files = parsleyInstance.$element[0].files;
                return files.length != 1 || files[0].size <= maxSize * 1024;
            },
            requirementType: 'integer',
            messages: {
                en: 'This file should not be larger than %s Kb',
                fr: "Ce fichier est plus grand que %s Kb."
            }
        });
        */
    });
</script>