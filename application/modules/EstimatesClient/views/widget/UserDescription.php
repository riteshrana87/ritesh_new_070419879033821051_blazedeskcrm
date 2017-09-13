<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: User Description Widget
  @Input 	: 
  @Output	: Show User Description.
  @Date   	: 09/03/2016
 */
?>
<?php if(isset($editRecord[0]['est_userdescription_status']) && $editRecord[0]['est_userdescription_status'] == 1){?>
<div class="parentSortable">
	<div class="mar_b6 CustWhiteBorder" id="UserDescription">
	  <div class="pad-6">
		<div class=""><b>User Description</b></div>
		<div>
			<?php 
				if(isset($editRecord[0]['est_userdescription']) && $editRecord[0]['est_userdescription'] != ""){
					echo $editRecord[0]['est_userdescription'];
					}
			?>
		</div>
	  </div>
	</div>
	<div class="clr"></div>
</div>
<?php }?>
