<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: PDF Terms and Condition Widget
  @Input 	: 
  @Output	: Terms and Condition text.
  @Date   	: 18/05/2016
 */
?>
<div style='page-break-before: always;'></div>
		<table style="width: 100%;">
		<tbody>
			<tr>
			<td>
                            <p style="text-align: left;font-size:20px;font-weight:bold;"><?php echo lang('EST_TITLE_TERMS_AND_CONDITIONS'); ?></p>
			</td>
			</tr>
			</tbody>
		</table>
		<table style="width: 100%;">
			<tbody>
			<tr>
				<td>
					<p style="width: auto">
						<?php
						if(isset($editRecord[0]['est_termcondition']) && $editRecord[0]['est_termcondition'] != ""){
							echo getTermsAndCondition($editRecord[0]['est_termcondition']);
						} elseif (!empty($TermsConditionDataArray) && count($TermsConditionDataArray) != 0)
						{
							foreach($TermsConditionDataArray as $TermsData){
								if($TermsData['terms'] != "")
								{
									echo $TermsData['terms'];
								}
							}
						}
						?>
					</p>
				</td>
			</tr>
			</tbody>
		</table>	