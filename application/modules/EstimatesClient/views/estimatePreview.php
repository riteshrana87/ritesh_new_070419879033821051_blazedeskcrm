<?php 
if (isset($editRecord) && !empty($editRecord)) { ?>
	<div class="white-border-ox" id="previewBox">
	  <div class="pad-10">
		<div class="row" id="headerContainer">
		  <?php 
			if(isset($editRecord[0]['est_header_widget']) && $editRecord[0]['est_header_widget'] != "")
			{
				$HeaderWidget = json_decode($editRecord[0]['est_header_widget']); 
			} else {
				$HeaderWidget = array('EstAddress', 'EstLogo');	
			}
		  ?>
			<?php 
				if(isset($HeaderWidget) && !empty($HeaderWidget))
				{
					foreach ($HeaderWidget as $ViewName) {
						$this->load->view('widget/'.$ViewName);
					}
				}
			?>
		  <div class="clr"></div>
		</div>
		<div class="grayline-1"></div>
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
					$this->load->view('widget/'.$ViewName);
				}
			}
		}
	?>
		<div class="row">
		  <div class="col-xs-12 col-sm-8 col-md-8">
			<div class=""><label>Date :</label> </div>
			<div class=""><label>Location :</label></div>
		  </div>
		  <div class="col-xs-12 col-sm-4 col-md-4">
			<?php if (isset($editRecord[0]['signature_date']) && $editRecord[0]['signature_date'] != null) { ?>
			<div class=""><label>Date :</label> 
					<?php if(isset($editRecord[0]['signature_date']) && $editRecord[0]['signature_date'] != "" && $editRecord[0]['signature_date'] != "0000-00-00"){
							echo configDateTime($editRecord[0]['signature_date']); 
						}?>
			</div>
			<?php } ?>
			<div class="">
					<label>Location :</label> 
					<?php if (isset($editRecord[0]['signature_place']) && $editRecord[0]['signature_place'] != null) { ?>
						<?php echo $editRecord[0]['signature_place'];?>
					<?php }?>
			</div>
			<div>
				<?php if (isset($editRecord[0]['signature']) && $editRecord[0]['signature'] != null) { ?>
					<img class="show" src="<?php echo $editRecord[0]['signature']; ?>" height="100px" width="100px">
				<?php }else{	echo "<br><br><br><br>";	} ?>
			</div>
		  </div>
		  <div class="clr"></div>
		<div class="clr"></div>
		  <div class="col-xs-12 col-sm-8 col-md-8">
			<div class=""><label>Name :</label> </div>
		  </div>
		  <div class="col-xs-12 col-sm-4 col-md-4">
				<div class=""><label>Name :</label> 
					<?php if (isset($editRecord[0]['signature_name']) && $editRecord[0]['signature_name'] != null) { ?>
						<?php echo $editRecord[0]['signature_name'];?>
					<?php }?>
				</div>
		  </div>
		  <div class="col-xs-12 col-sm-8 col-md-8">
			<div class="form-group"><label>Job Role :</label> </div>
		  </div>
		  <div class="col-xs-12 col-sm-4 col-md-4">
				<div class="form-group"><label>Job Role:</label> 
					<?php if (isset($editRecord[0]['signature_jobrole']) && $editRecord[0]['signature_jobrole'] != null) { ?>
						<?php echo $editRecord[0]['signature_jobrole'];?>
					<?php }?>
				</div>
		  </div>
		</div>
	  </div>
	</div>
	<script src='<?= base_url() ?>uploads/custom/js/jQuery.print.js'></script>
<?php  }?>