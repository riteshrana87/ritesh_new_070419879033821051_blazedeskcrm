<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: Paragraph Widget
  @Input 	: 
  @Output	:
  @Date   	: 10/03/2016
 */
?>
<?php $previewPara = (array)json_decode($editRecord[0]['text_paragraph']);?>
<div style='page-break-before: always;'></div>
<div class="parentSortable">
	<div class="clr mar_b6"></div>
	<div class="mar_b6 CustWhiteBorder" id="paragraph_<?php echo $ParagraphArray[1];?>">
	  <div class="">
              <div class=""><b><?php echo lang('EST_PARA'); ?></b></div>
		<div>
			<?php echo $previewPara['text_paragraph'][$ParagraphArray[1]]; ?>
		</div>
	  </div>
	</div>
</div>