<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: Paragraph Widget
  @Input 	: 
  @Output	:
  @Date   	: 10/03/2016
 */
?>
<?php 
	if(isset($editRecord[0]['text_paragraph']) && $editRecord[0]['text_paragraph'] !="")
		{ ?>
		<div style='page-break-before: always;'></div>
		<table style="width: 100%;">
		<tbody>
			<tr>
			<td>
				<p style="text-align: left;font-size:20px;font-weight:bold;">Paragraph</p>
			</td>
			</tr>
			</tbody>
		</table>
		<table style="width: 100%;">
			<tbody>
				<tr>
					<td style="padding-top:40px;">
						<?php $previewPara = (array)json_decode($editRecord[0]['text_paragraph']); ?>
						<table>
							<tbody>
								<tr>
									<td>
										<?php echo $previewPara['text_paragraph'][$ParagraphArray[1]]; ?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	<?php }?>
<?php /*$previewPara = (array)json_decode($editRecord[0]['text_paragraph']);?>
<div class="parentSortable">
	<div class="clr mar_b6"></div>
	<div class="mar_b6 CustWhiteBorder" id="paragraph_<?php echo $ParagraphArray[1];?>">
	  <div class="">
		<div class=""><b>Paragraph</b></div>
		<div>
			<?php echo $previewPara['text_paragraph'][$ParagraphArray[1]]; ?>
		</div>
	  </div>
	</div>
</div><?php */?>