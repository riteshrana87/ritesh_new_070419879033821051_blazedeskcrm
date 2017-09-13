<div id="prntViewPage" <?php if($section == 'print'){ ?> style="display:none;" <?php }?>>
<?php
if (isset($editRecord) && !empty($editRecord)) { ?>
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
			<?php echo $editRecord[0]['subject'];?>
			<p style="padding-bottom: 0px; font-size:20px;"><?php echo $editRecord[0]['estimate_auto_id']; ?>
			</p>
			<?php echo "<br><br>";?>

			<p style="padding-bottom: 20px; padding-top: 20px; font-size:20px; width:200px; border-top: 1px solid #000;"><?php echo lang('PREPARED_FOR') ?></p>
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
					<?php if(isset($editRecord[0]['send_date']) && $editRecord[0]['send_date'] != ""){?>
				<p style="text-align: left;font-size:20px;">
					<?php echo lang('EST_CREATE'); ?>:
					<?php echo "<br>";?>
					<?php echo configDateTime($editRecord[0]['send_date']); ?>
				</p>
				<?php }?>
				</p>
			</td>
			<td>
				<p style="text-align: right;font-size:20px;">
					<?php if(isset($editRecord[0]['due_date']) && $editRecord[0]['due_date'] != ""){?>
				<p style="text-align: right;font-size:20px;">
					<?php echo lang('EST_VALID_TIL'); ?>:
					<?php echo "<br>";?>
					<?php echo configDateTime($editRecord[0]['due_date']); ?>
				</p>
				<?php }?>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
		
	<?php 	
	if(isset($editRecord[0]['est_content_widgets']) && $editRecord[0]['est_content_widgets'] != "")
	{
		$ContentWidget = json_decode($editRecord[0]['est_content_widgets']); 
	} else {
		$ContentWidget = array('IntroductionText', 'products', 'UserDescription', 'TermsCondition');	
		$dftParagraph = (array)json_decode($editRecord[0]['text_paragraph']);
		$ParagraphArray = array();
		if(isset($dftParagraph['text_paragraph']) && count($dftParagraph['text_paragraph']) > 0) {
			for($ii = 0; $ii < count($dftParagraph['text_paragraph']); $ii++)
			{
				array_push($ContentWidget, 'paragraph_'.$ii);
			}
		}
	}
	?>
	<?php 
	if(isset($ContentWidget) && !empty($ContentWidget))
	{
		foreach ($ContentWidget as $ViewName) {
			$ProductMode = strpos($ViewName, 'paragraph_');
			if(isset($ProductMode) && $ProductMode === 0)
			{	
				$ParagraphData['ParagraphArray'] = explode("_",$ViewName);
				$this->load->view('widget/Paragraph',$ParagraphData);
			} else {
				$this->load->view('pdfwidget/'.$ViewName);
			}
		}
	}
?>
		<?php /*$this->load->view('pdfwidget/IntroductionText');?>
		<?php $this->load->view('pdfwidget/products');?>
		<?php $this->load->view('pdfwidget/UserDescription');?>
		<?php $this->load->view('pdfwidget/Paragraph');?>
		<?php $this->load->view('pdfwidget/TermsCondition'); */?>
	
		<table style="width: 100%;">
			<tbody>
			<tr>
				<td style="width: 50%;">
					<?php if(isset($editRecord[0]['send_date']) && $editRecord[0]['send_date'] != ""){?>
						<p style="text-align: left; width: 50px; font-size:20px;">
							<?php if(isset($PreviewClientInformation['CompanyName']) && $PreviewClientInformation['CompanyName']!= "") 	  {
								//echo $PreviewClientInformation['CompanyName'];
							}
							echo "<br>";
							?>
							<?php echo lang('EST_PREVIEW_DATE'); ?>
							<?php
							echo "<br>"; ?>
							<?php echo lang('EST_PREVIEW_LOCATION'); ?>
							<?php 
							/*if (isset($PreviewClientInformation['city']) && $PreviewClientInformation['city'] !== ""){
								echo 'Location: '.$PreviewClientInformation['city'];
							}*/
							?>
						</p>
					<?php }?>
				</td>
				<td style="width: 50%;">
					<p style="text-align: right;font-size:20px;">
						<?php //Name?>
						<?php if (isset($editRecord[0]['signature_name']) && $editRecord[0]['signature_name'] != null) { echo $editRecord[0]['signature_name']; }?>
						<?php echo "<br>";?>
						<?php echo lang('EST_PREVIEW_DATE'); ?>
						<?php if (isset($editRecord[0]['signature_date']) && $editRecord[0]['signature_date'] != "0000-00-00" && $editRecord[0]['signature_date'] != null) { ?>
						<?php echo configDateTime($editRecord[0]['signature_date']);?>
						<?php }?>
						<?php echo "<br>";?>
						<?php echo lang('EST_PREVIEW_LOCATION'); ?>
						<?php if (isset($editRecord[0]['signature_place']) && $editRecord[0]['signature_place'] != null) { ?>
						<?php echo $editRecord[0]['signature_place'];?>
						<?php }?>
					</p>
				</td>
			</tr>
			</tbody>
		</table>
		<?php }?>
	<table style="width: 100%;">
	<tr>
		<td style="width: 50%;"></td>
		<td style="width: 50%;">
			<p style="text-align: left;font-size:20px;">
			<?php if(isset($editRecord[0]['signature']) && $editRecord[0]['signature'] != ""){ ?>
				<img id="signImg" class="show" src="<?php echo $editRecord[0]['signature']; ?>" height="100px" width="100px"> 
			<?php }?>
			</p>
		</td>
	</tr>
	</table>
	<table style="width: 100%;">
		<tr>
			<td style="width: 50%;"> 
				<p style="text-align: left;font-size:20px;">
				<?php echo lang('EST_LISTING_LABEL_NAME'); ?> : <?php echo "<br>";?>
				<?php echo lang('EST_PREVIEW_JOB_ROLE'); ?> : <?php echo "<br>";?>
				</p>
			</td>
			<td style="width: 50%;">
				<p style="text-align: right;font-size:20px;">
				<?php echo lang('EST_LISTING_LABEL_NAME'); ?> :
				<?php if (isset($editRecord[0]['signature_name']) && $editRecord[0]['signature_name'] != null) { ?>
				<?php echo $editRecord[0]['signature_name']; ?>
				<?php }?> 
				<?php echo "<br>";?>
				<?php echo lang('EST_PREVIEW_JOB_ROLE'); ?> :
				<?php if (isset($editRecord[0]['signature_jobrole']) && $editRecord[0]['signature_jobrole'] != null) { ?>
				<?php echo $editRecord[0]['signature_jobrole'];?>
				<?php }?>
				</p>
			</td>
		</tr>
	</table>	
</div>
	<script src='<?= base_url() ?>uploads/custom/js/jQuery.print.js'></script>
	<?php if($section == 'print'){ ?>
		<script>
			$("#prntViewPage").print();
			//window.top.close(); 
		</script>
	<?php }?>