<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: Introduction Text Widget
  @Input 	: 
  @Output	: Show Introduction text.
  @Date   	: 09/03/2016
 */
?>
<?php if(isset($editRecord[0]['est_content']) && $editRecord[0]['est_content'] != "") {?>
<div class="parentSortable">
	<div class="mar_b6 CustWhiteBorder" id="IntroductionText">
	  <div class="pad-6">
		<div class=""><b>Introduction Text</b></div>
			<div>
				<?php 	echo $editRecord[0]['est_content']; ?>
			</div>
	  </div>
	</div>
	<div class="clr"></div>
</div>
<?php }?>
