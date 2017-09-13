<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: Terms and Condition Widget
  @Input 	: 
  @Output	: Terms and Condition text.
  @Date   	: 09/03/2016
 */
?>
<?php if(isset($editRecord[0]['est_termcondition']) && $editRecord[0]['est_termcondition'] != ""){?>
<div class="parentSortable">
	<div class="mar_b6 CustWhiteBorder" id="TermsCondition">
	  <div class="">
		<div class=""><b><?php echo lang('EST_TITLE_TERMS_AND_CONDITIONS'); ?></b></div>
		<div>
			<?php echo getTermsAndCondition($editRecord[0]['est_termcondition']);?>
		</div>
	  </div>
	</div>
	<div class="clr "></div>
</div>
<?php }?>