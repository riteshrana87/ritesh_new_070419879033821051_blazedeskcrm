<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: PDF User Description Widget
  @Input 	: 
  @Output	: Show User Description.
  @Date   	: 18/05/2016
 */
?>
<?php if(isset($editRecord[0]['est_userdescription_status']) && $editRecord[0]['est_userdescription_status'] == 1){?>
	<div style='page-break-before: always;'></div>
	<table style="width: 100%;">
		<tbody>
			<tr>
			<td>
                            <p style="text-align: left;font-size:20px;font-weight:bold;"><?php echo lang('EST_TITLE_USER_DESCRIPTION'); ?></p>
			</td>
			</tr>
			</tbody>
		</table>
	<table style="width: 100%;">
		<tbody>
			<tr>
				<td>
					<p style="width: auto;">
						<?php echo $editRecord[0]['est_userdescription'];	?>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php }?>