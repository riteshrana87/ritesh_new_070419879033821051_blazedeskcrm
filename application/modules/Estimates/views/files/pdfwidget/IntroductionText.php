<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: PDF Introduction Text Widget
  @Input 	: 
  @Output	: Show Introduction text.
  @Date   	: 18/05/2016
 */
?>
<div style='page-break-before: always;'></div>
<table style="width: 100%;">
			<tbody>
			<tr>
				<td>
					<p style="text-align: left;font-size:20px;font-weight:bold;">Introduction</p>
					<p style="width: auto;">
						<?php if(isset($editRecord[0]['est_content']) && $editRecord[0]['est_content'] != ""){?>
							<?php echo $editRecord[0]['est_content']; ?>
						<?php }?>
					</p></td></tr>
			</tbody>
		</table>
			<table style="width: 100%;">
				<tbody>
				<tr>
					<td colspan="2">
						<p>Sincerely,</p></br>
						<?php if(isset($editRecord[0]['signature']) && $editRecord[0]['signature'] != ""){ ?>
							<img id="signImg" class="show" src="<?php echo $editRecord[0]['signature']; ?>" height="100px" width="100px"> 
						<?php }?>
					</td>
				</tr>
				<tr>
					<td>
						<p>
							<?php if(isset($PreviewLoginInfo[0]['firstname']) && $PreviewLoginInfo[0]['lastname'] != ""){ ?>
								<strong><?php echo $PreviewLoginInfo[0]['firstname'].' '.$PreviewLoginInfo[0]['lastname']; echo "<br>"; ?></strong>
							<?php }?>
							<?php if(isset($PreviewLoginInfo[0]['role_name']) && $PreviewLoginInfo[0]['role_name'] != ""){ ?>
							<?php echo $PreviewLoginInfo[0]['role_name'];?>   <?php echo "<br>";?>
							<?php }?>
							<?php /*?>C-Metric Solutions Pvt. Ltd  <?php echo "<br>"; */?>
							<?php if(isset($PreviewLoginInfo[0]['email']) && $PreviewLoginInfo[0]['email'] != ""){ ?>
								<?php echo $PreviewLoginInfo[0]['email'];?>
							<?php }?>
							<?php if(isset($PreviewLoginInfo[0]['telephone1']) && $PreviewLoginInfo[0]['telephone1'] != ""){ ?>
								<?php echo ' | '.$PreviewLoginInfo[0]['telephone1'];?>
							<?php }?>
							<?php if(isset($PreviewLoginInfo[0]['telephone2']) && $PreviewLoginInfo[0]['telephone2'] != ""){ ?>
								<?php echo ' | '.$PreviewLoginInfo[0]['telephone2'];?>
							<?php }?>
						</p>
					</td>
				</tr>
				</tbody>
			</table>