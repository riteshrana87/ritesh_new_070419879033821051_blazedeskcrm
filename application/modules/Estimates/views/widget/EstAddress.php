<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: Header Address Widget
  @Input 	: 
  @Output	:
  @Date   	: 10/03/2016
 */
?>
<div class="col-xs-12 col-sm-8 col-md-6 CustHeaderSection mb15" id="EstAddress">
	<div class="font-15em"><b><?php echo $editRecord[0]['estimate_auto_id']; ?></b></div>
	<?php if(isset($PreviewClientInformation['name']) && $PreviewClientInformation['name'] != ""){?>
		<div class=""><?php echo $PreviewClientInformation['name']; ?></div>
	<?php }?>
	<div class="">
		<?php if(isset($PreviewClientInformation['address1']) && $PreviewClientInformation['address1'] != ""){?>
			<?php echo $PreviewClientInformation['address1']; ?>
		<?php }?>
	</div>
	<div class="">
		<?php if(isset($PreviewClientInformation['address2']) && $PreviewClientInformation['address2'] != ""){?>
			<?php echo $PreviewClientInformation['address2']; ?>
		<?php }?>
		<?php if(isset($PreviewClientInformation['postal_code']) && $PreviewClientInformation['postal_code'] != ""){?>
			<?php echo ','.$PreviewClientInformation['postal_code']; ?>
		<?php }?>
	</div>
	<div class="">
		<?php if(isset($PreviewClientInformation['city']) && $PreviewClientInformation['city'] != ""){?>
			 <?php echo $PreviewClientInformation['city'];?> 
		<?php }?>
		<?php if(isset($PreviewClientInformation['state']) && $PreviewClientInformation['state'] != ""){?>
			<?php echo $PreviewClientInformation['state'];?> 
		<?php }?>
	  <div class="clr"></div>
	</div>
</div>