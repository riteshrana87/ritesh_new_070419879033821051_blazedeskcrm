<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: Header Logo Widget
  @Input 	: 
  @Output	:
  @Date   	: 10/03/2016
 */
?>
<div class="col-xs-12 col-sm-4 col-md-6 CustHeaderSection mb15" id="EstLogo"> <!-- pull-right text-right -->
			<div><?php $BZInformation = (array)json_decode($PreBZCompanyInfo[0]['value']);?>
				<?php if(isset($BZInformation['profile_photo']) && $BZInformation['profile_photo'] != ""){?>
					<img src="<?php echo base_url().SETTINGS_PROFILE_PIC_UPLOAD_PATH."/".$BZInformation['profile_photo']; ?>" alt="" class="img-responsive" /><div class="clr"></div>
				<?php }?>
			</div>
			
			<?php if(isset($BZInformation['address1']) && $BZInformation['address1'] != ""){?>
				<div class=""><?php echo $BZInformation['address1']; ?></div>
			<?php }?>
				<div class="">
				<?php if(isset($BZInformation['address2']) && $BZInformation['address2'] != ""){?>
					<?php echo $BZInformation['address2']; ?>
				<?php }?>
				<?php if(isset($BZInformation['pincode']) && $BZInformation['pincode'] != ""){?>
					<?php echo ','.$BZInformation['pincode']; ?>
				<?php }?>
			</div>
			<div class="">
				<?php if(isset($BZInformation['city']) && $BZInformation['city'] != ""){?>
					<?php echo $BZInformation['city']; ?>
				<?php }?>
				<?php if(isset($BZInformation['state']) && $BZInformation['state'] != ""){?>
					<?php echo ' ,'.$BZInformation['state']; ?>
				<?php }?>
			</div>
		  </div>