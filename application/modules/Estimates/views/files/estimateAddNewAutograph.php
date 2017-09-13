<?php
if (isset($editRecord) && !empty($editRecord)) {
	echo "In Edit mode";
} else { ?>
<div class="col-xs-12 col-md-12 no-left-pad" id="autoGraphCntdiv_<?php echo $autogrphCnt;?>">
	<div class="col-md-1 col-lg-1 pull-right bd-error-control"> 
		<div class="bd-error"><a onclick="removeItem('#autoGraphCntdiv_<?php echo $autogrphCnt;?>')" title="<?php echo lang('EST_TITLE_AUTOGRAPH_REMOVE_PRD'); ?>"><i class="fa fa-trash redcol"></i></a></div> 
	</div>
	<div class="form-group row signatureTypeDiv" id="signatureTypeDiv">    
		<div class="input-group date" id="">
			<input type="text" class="form-control signature_date-all" placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_DATE'); ?>" id="signature_date" name="signature_date[]" onkeydown="return false" data-parsley-errors-container="#signatureDateErrors" />
			<span class="input-group-addon">
				<span class="glyphicon glyphicon-calendar"></span>
			</span>
		</div>
		<span id="signatureDateErrors"></span>
		
		<input type="text" value="" placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_PLACE'); ?>" id="signature_place" class="form-control" name="signature_place[]" />
		<input type="text" value="" placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_NAME'); ?>" id="signature_name" class="form-control" name="signature_name[]" />
		<input type="text" value="" placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_JOBROLE'); ?>" id="signature_jobrole" class="form-control" name="signature_jobrole[]" />
		
		<input type="hidden" name="signature-digital[]" id="signature-digital_<?php echo $autogrphCnt;?>" class="input-group autographclass">
		<select id="signature_type" name="signature_type" class="form-control chosen-select signatureTypeChosen" onchange="displaySignature(this.value, '<?php echo $autogrphCnt;?>');" >
			<option value=""><?php echo lang('EST_TITLE_AUTOGRAPH_SELECT'); ?></option>
			<option value="0"><?php echo lang('EST_TITLE_AUTOGRAPH_DIGITAL_SIGNATURE'); ?></option>
			<option value="1"><?php echo lang('EST_TITLE_AUTOGRAPH_CANVAS'); ?></option>
		</select>
	</div>

	<div class="form-group row signature-upload-div" id="signature-upload_<?php echo $autogrphCnt;?>" style="display:none">    

		<label class="custom-upload btn btn-blue"><?php echo lang('EST_TITLE_AUTOGRAPH_UPLOAD'); ?><input type="file" onchange="autoGraphUpload(this, '<?php echo $autogrphCnt;?>')" name="signature-file[]" id="singnature-file" class="input-group"></label>
		<div>
			<img id="autographimg_<?php echo $autogrphCnt;?>" class="noimage" src="<?php echo base_url('uploads/contact').'/noimage.jpg'?>"  width="100" />
		</div>
		<div class="clr"> </div>  
	</div>
	<div class="form-group form-control row heightauto signature-Div" id="signature_<?php echo $autogrphCnt;?>" style="display:none">    

		<div class='js-signature' style="width:100px;height:100px"></div>
		<div class="clr"> </div>  
	</div>
</div>
<?php } ?>