<?php
if (isset($editRecord) && !empty($editRecord)) { ?><?php 	//  pr($editRecord);exit; ?>
	<?php $Symbol = $PDFCuntArray[0]['currency_symbol'];?>
	<style>
		.white-border-ox{
			font-family: calibri;
		}
		.TopHeaderTitle{
			font-weight:bold;
			font-size:30px;
		}
		.IntroductionPage{

		}
	</style>

<table style="width:100%">
	<tbody>
	<tr>
		<td style="font-size: 65px;padding-top:154px;text-align: left; width:100%">
			Invoice
			<p style="padding-bottom: 0px; font-size:20px;"><?php echo $editRecord[0]['invoice_code']; ?>
			</p>
			<?php echo "<br><br>";?>

			<p style="padding-bottom: 20px; padding-top: 20px; font-size:20px; width:200px; border-top: 1px solid #000;">Prepared For</p>
			<?php if(isset($PreviewClientInformation['name']) && $PreviewClientInformation['name'] != ""){?>

				<p style="font-size: 40px;font-weight:bold;text-transform:capitalize;">
					<?php if(isset($PreviewClientInformation['name']) && $PreviewClientInformation['name'] != ""){ ?>
						<?php echo $PreviewClientInformation['name']; ?>
					<?php }?>
				</p>
			<?php }?>
			<p style="font-size: 30px;">
				<?php if(isset($PreviewClientInformation['address1']) && $PreviewClientInformation['address1'] != ""){?>
					<?php echo $PreviewClientInformation['address1']; echo "<br>";?>
				<?php }?>
				<?php if(isset($PreviewClientInformation['address2']) && $PreviewClientInformation['address2'] != ""){?>
					<?php echo $PreviewClientInformation['address2']; ?>
				<?php }?>
			</p>
			<p style="font-size: 30px;">
				<?php if(isset($PreviewClientInformation['postal_code']) && $PreviewClientInformation['postal_code'] != ""){?>
					<?php echo $PreviewClientInformation['postal_code']; ?>
				<?php }?>
				<?php if(isset($PreviewClientInformation['city']) && $PreviewClientInformation['city'] != ""){?>
					<?php echo ' | '.$PreviewClientInformation['city']; ?>
				<?php }?>
				<?php if(isset($PreviewClientInformation['state']) && $PreviewClientInformation['state'] != ""){?>
					<?php echo ' | '.$PreviewClientInformation['state']; ?>
				<?php }?>
			</p>
			<p  style="font-size: 30px;">
				<?php echo $PreviewClientInformation['country_name']; ?>
			</p>
		</td>
	</tr></tbody>
</table>

	<!--style="width:100%;border:2px #000 solid;text"-->
	<table style="width: 100%; padding-top: 50px;">
		<tbody>
		<tr>
			<td>
				<p style="text-align: left;font-size:20px;">
					<?php if(isset($editRecord[0]['created_date']) && $editRecord[0]['created_date'] != ""){?>
				<p style="text-align: left;font-size:20px;">
					Invoice Created:
					<?php echo "<br>";?>
					<?php echo configDateTime($editRecord[0]['created_date']); ?>
				</p>
				<?php }?>
				</p>
			</td>
			<td>
				
			</td>
		</tr>
		</tbody>
		</table>
		<!-- <div style='page-break-before: always;'></div> -->
		<br>
		<br>
		<table style="width: 100%;">
			<tbody>
			<tr>
				<td>
					<p style="text-align: left;font-size:20px;font-weight:bold;">Introduction</p>
					<p style="width: auto;">
						<?php if(isset($editRecord[0]['description']) && $editRecord[0]['description'] != ""){?>
							<?php echo $editRecord[0]['description']; ?>
						<?php }?>
					</p></td></tr>
			</tbody>
		</table>
			<table style="width: 100%;">
				<tbody>
				<?php /* <tr>
					<td colspan="2">
						<p>Sincerely,</p></br>
						<?php if(isset($editRecord[0]['signature']) && $editRecord[0]['signature'] != ""){ ?>
							<img id="signImg" class="show" src="<?php echo $editRecord[0]['signature']; ?>" height="100px" width="100px"> Autograph Blazedesk User
						<?php }?>
					</td>
				</tr> */?>
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
			<!-- <div style='page-break-before: always;'></div> -->
			<table style="width: 100%;">
			<tbody>
				<tr>
				<td>
					<p style="text-align: left;font-size:20px;font-weight:bold;">Invoice pricing Overview</p>
				</td>
				</tr>
				</tbody>
			</table>
			<table style="width: 100%;">
				<tbody>
				<tr>
					<th style="width:10%">Qty</th>
					<th style="width:40%">Item Name</th>
					<th style="width:10%">Item Rate</th>
					<th style="width:20%">Item Tax</th>
					<th style="width:20%">Item Discount</th>
					<th style="width:20%">Total Line price</th>
				</tr>
				<?php
				//All Product amount get in array For Calculation
				$AllPRDAmount = array();
				$TaxtCalculationArray = array();
				$totalAmtArray = array();
				?>
				<?php foreach($previewAllItems as $items){
								
					?>
					<tr>
						<td style="text-align: center"><?php if(isset($items['qty_hours']) && $items['qty_hours'] != ""){ echo  $items['qty_hours']; }?></td>
						<td style="text-align: center">
							<?php if(isset($items['item_name']) && $items['item_name'] != ""){ echo $items['item_name']; }?>
							
						</td>
						<td style="text-align: center"><?php if(isset($items['rate']) && $items['rate'] != ""){ echo '<br>'.$items['rate']; }?></td>
						<td style="text-align: center"><?php if(isset($items['tax_rate']) && $items['tax_rate'] != ""){ echo $items['tax_rate']; }?></td>
						<td style="text-align: center"><?php if(isset($items['discount']) && $items['discount'] != ""){echo $items['discount']; }?></td>
						<td style="text-align: center"><?php if(isset($items['cost']) && $items['cost'] != ""){echo $items['cost']; }?></td>
						
					</tr>
				<?php }?>
				</tbody>
			</table>

			<table style="margin-top:8px; text-align: right; width: 100%; padding-top: 20px; font-size:20px; border-top: 1px solid #000;">
				<tbody>
				<tr>
					<td>Total:   </td>
					<td>
						
						<?php echo $editRecord[0]['amount']; ?>
					</td>
				</tr>
				</tbody>
			</table>
			<!-- Notes -->
			<table style="width: 100%;">
				<tbody>
					<tr>
						<td>
							<p style="text-align: left;font-size:20px;font-weight:bold;">Notes </p>
							<p style="width: auto;">
								<?php if(isset($editRecord[0]['notes']) && $editRecord[0]['notes'] != ""){?>
									<?php echo $editRecord[0]['notes']; ?>
								<?php }?>
							</p>
						</td>
					</tr>
				</tbody>
			</table>
			<!-- payment -->
			<!-- <div style='page-break-before: always;'></div> -->
			<table style="width: 100%;">
			<tbody>
				<tr>
				<td>
					<p style="text-align: left;font-size:20px;font-weight:bold;">Invoice Payment Overview</p>
				</td>
				</tr>
				</tbody>
			</table>
			<table style="width: 100%;">
				<tbody>
				<tr>
					<th style="width:10%">Amount</th>
					<th style="width:40%">Due on</th>
					<th style="width:10%">Notes</th>
					
				</tr>
				<?php
				//All Product amount get in array For Calculation
				?>
				<?php foreach($previewAllPayment as $payments){
									
					?>
					<tr>
						<td style="text-align: center"><?php if(isset($payments['amount']) && $payments['amount'] != ""){ echo  $payments['amount'].$payments['currency_symbol']; }?></td>
						<td style="text-align: center">
							<?php if(isset($payments['due_on']) && $payments['due_on'] != ""){ echo $payments['due_on']; }?>
							
						</td>
						<td style="text-align: center"><?php if(isset($payments['notes']) && $payments['notes'] != ""){ echo $payments['notes']; }?></td>
						
					</tr>
				<?php }?>
				</tbody>
			</table>

	<table style="margin-top:8px; text-align: right; width: 100%; padding-top: 20px; font-size:20px; border-top: 1px solid #000;">
		<tbody>
		<tr>
			<td>Total Payment:   </td>
			<td>
				
				<?php echo $editRecord[0]['total_payment']; ?>
			</td>
		</tr>
		</tbody>
	</table>
			

			
			<!-- <div style='page-break-before: always;'></div> -->
			<br><br>
			
			<table style="width: 100%;">
				<tbody>
				<tr>
					<td style="width: 50%;">
						<?php if(isset($editRecord[0]['created_date']) && $editRecord[0]['created_date'] != ""){?>
							<p style="text-align: left; width: 50px; font-size:20px;">
								<?php if(isset($PreviewClientInformation['CompanyName']) && $PreviewClientInformation['CompanyName']!= "") 	  {
									echo $PreviewClientInformation['CompanyName'];
								}
								echo "<br>";
								?>
								Date:<?php if(isset($editRecord[0]['created_date']) && $editRecord[0]['created_date'] != ""){ echo configDateTime($editRecord[0]['created_date']); }?>
								<?php
								echo "<br>";
								if (isset($PreviewClientInformation['city']) && $PreviewClientInformation['city'] !== ""){
									echo 'Location: '.$PreviewClientInformation['city'];
								}
								?>
							</p>
						<?php }?>
					</td>
					<td style="width: 50%;">
						<p style="text-align: right;font-size:20px;">
							Blazedesk <!-- Company name:
							<?php //echo "<br>";?>
							Date: 
							<?php// echo "<br>";?>
							Location: City -->
						</p>

					</td>
				</tr>
				</tbody>
			</table>
			<?php }?>
		<table style="width: 100%;">
	
	</table>
	<script src='<?= base_url() ?>uploads/custom/js/jQuery.print.js'></script>