<?php
/*
* @Author	: RJ(Rupesh Jorkar)
* @Desc		: Show All Product based on selected Template(This page copy of "estimateProductSection.php")
* @Date		: 01-03-2016
*/ 
?>
<?php if (count($estimate_product) > 0) {
//Symbol Selection Code
	$dftSymbole = getDefaultCurrencyInfo(); 
	if(isset($estSymbolSelected) && $estSymbolSelected != ""){
	$symForSelect = $estSymbolSelected;
	} else {
	$symForSelect = $dftSymbole['symbol'];
	}
	    for ($i = 0; $i < count($estimate_product); $i++) {
            ?>
<div id="prodTemp-<?php echo $i; ?>">
  <div class="row">
    <div class="col-md-1 pull-right bd-error-control col-xs-12  col-lg-1"> <div class="bd-error"><a title="<?php echo lang('EST_TITLE_AUTOGRAPH_REMOVE_PRD'); ?>" onclick="removeItem('#prodTemp-<?php echo $i; ?>')"><i class="fa fa-trash redcol"></i></a></div> </div>
    <div class="col-md-11 col-xs-12  col-lg-11">
      <div class="row">
        <div class="col-xs-12 col-md-2 col-lg-2 col-sm-3 prodTemp-<?php echo $i; ?>">
          <div class="form-group">
			<div><label ><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?> *</label></div>
			<input class="form-control product_id" id="product_id" name="product_id[]" placeholder="<?php echo lang('EST_LISTING_LABEL_NAME'); ?>" value="<?php echo $estimate_product[$i]['product_id']; ?>" type="hidden" />
			<input class="form-control product_id" id="product_name" name="product_name[]" placeholder="<?php echo lang('EST_LISTING_LABEL_NAME'); ?>" value="<?php echo $estimate_product[$i]['product_name']; ?>" type="text" />
            <?php /*?><select class="form-control chosen-select product_id" name="product_id[]" id="product_id" required onchange="showInnerBox('prodTemp-<?php echo $i; ?>', this.value, '<?php echo $i; ?>')">
              <option value="">Select Product</option>
              <?php foreach ($product_info as $product) { ?>
              <option <?php echo($estimate_product[$i]['product_id'] == $product['product_id']) ? "selected" : ""; ?> value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
              <?php } ?>
            </select><?php */?>
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-sm-3 col-lg-2 prodTemp-<?php echo $i; ?>">
          <div class="form-group">
			<div><label ><?php echo lang('EST_LABEL_DESCRIPTION'); ?></label></div>
            <input class="form-control" id="product_description_<?php echo $i; ?>" name="product_description[]" placeholder="<?php echo lang('EST_LABEL_DESCRIPTION'); ?>" value="<?php echo $estimate_product[$i]['product_description']; ?>" type="text" />
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-sm-3 amt-width col-lg-2 prodTemp-<?php echo $i; ?>">
			<div><label class=""><?php echo lang('EST_LABEL_AMOUNT'); ?> * </label></div>
          <div class="input-group">
			<?php 
				$taxAmtForPrice = 0;
				$prdSalesPrice = $estimate_product[$i]['sales_price_unit'];
				if(isset($estimate_product[$i]['tax_percentage']) && $estimate_product[$i]['tax_percentage'] != "" && $estimate_product[$i]['is_delete'] != 1){
					$taxAmtForPrice = ($prdSalesPrice * $estimate_product[$i]['tax_percentage']) / 100; 
				}
				$taxIncludedPrice = $taxAmtForPrice + $prdSalesPrice;
			?>
			<span class="estCurSymbolDiv  input-group-addon" readonly><?php echo $symForSelect;?></span>
		    <input type="text" class="form-control product_amount_sales prdAmtSingle" id="product_TMP_amount_sales_<?php echo $i; ?>" name="product_amount_sales[]" value="<?php echo $estimate_product[$i]['sales_price_unit']; ?>" onchange="calculateSalesAmountFromField(this.value, '<?php echo $i; ?>', '_TMP')" data-taxexclude-amount="<?php echo $estimate_product[$i]['sales_price_unit']; ?>" data-taxincluded-amount="<?php echo $taxIncludedPrice; ?>" data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" data-parsley-errors-container="#prdCustAmtError<?php echo $i.'_TMP'; ?>" required />
		</div>
			<span id="prdCustAmtError<?php echo $i.'_TMP'; ?>"></span>
		</div>
        <div class="col-xs-12 col-md-2 col-sm-3 col-lg-1 prodTemp-<?php echo $i; ?>">
          <div class="form-group">
			<div><label ><?php echo lang('EST_LABEL_QUANTITY'); ?></label></div>
            <input class="form-control product_qty prdQtySingle" id="product_TMP_qty_<?php echo $i; ?>" onkeyup="changeAmountValueQty(this.value, '<?php echo $i; ?>', '_TMP')" name="product_qty[]" placeholder="1" value="<?php echo $estimate_product[$i]['product_qty']; ?>" type="" min="1" required="" data-parsley-trigger="change" />
          </div>
        </div>
		<div class="col-xs-12 col-md-1 col-lg-1 disc-optn col-sm-3 prod-<?php echo $i; ?>">
			<div class="form-group">
                            <div><label><?php echo lang('EST_LABEL_DISCOUNT_OPTION') ?></label></div>
				<select name="product_disOption[]" id="product_TMP_disOption_<?php echo $i; ?>" class="form-control chosen-select prdDiscountOptSng prdDisOptionSingle prdSngDisOpt" onchange="calDisAmtOptFrm(this.value, '<?php echo $i; ?>', '_TMP')">
					<option value="prsnt" <?php if($estimate_product[$i]['product_disoption'] == "prsnt"){ echo "selected"; } ?> >%</option>
					<option value="amt" <?php if($estimate_product[$i]['product_disoption'] == "amt"){ echo "selected"; } ?> ><?php echo getCurrencySymbol($editRecord[0]['country_id_symbol']);?></option>
				</select>
			</div>	
		</div>
		<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 prod-<?php echo $i; ?>">
			<div class="form-group">
				<div><label ><?php echo lang('EST_LABEL_DISCOUNT'); ?></label></div>
				<input type="text" class="form-control prdDisSng prdDiscountSingle prdSngDiscount" onchange="calDisAmtFrm(this.value, '<?php echo $i; ?>', '_TMP')" id="product_TMP_discount_<?php echo $i; ?>" name="product_discount[]" placeholder="<?php echo lang('EST_LABEL_DISCOUNT'); ?>" value="<?php echo $estimate_product[$i]['product_discount']?>" <?php if($estimate_product[$i]['product_disoption'] == "prsnt"){ ?> data-parsley-pattern="^([0-9]{1,3}){1}(\.[0-9]{1,2})?$" data-parsley-range="[0, 100]" <?php } else { ?> data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" <?php } ?> />
			</div>	
		</div>
        <div class="col-xs-12 col-md-1 col-sm-3 col-lg-1  prodTemp-<?php echo $i; ?>">
          <div class="form-group">
			<div><label><?php echo lang('EST_LABEL_TAX'); ?> *</label></div>
			<input class="form-control product_tax prdTaxidSingle" id="product_TMP_tax_<?php echo $i; ?>" name="product_tax[]" value="<?php if($estimate_product[$i]['tax_id'] != "" && $estimate_product[$i]['is_delete'] != 1){echo $estimate_product[$i]['tax_id']; }?>" type="hidden" readonly />
			<input class="form-control prdAllTaxPercentageVal" id="taxValueForInfo_grp_TMP_<?php echo $i; ?>" name="taxValueForInfo_grp_[]" value="<?php if($estimate_product[$i]['tax_percentage'] != "" && $estimate_product[$i]['is_delete'] != 1){ echo $estimate_product[$i]['tax_percentage']; }?>" readonly />
		  </div>
        </div>
		<div class=" col-xs-12 col-md-2 col-sm-3 col-lg-2 amt-width no-right-pad form-group  prodTemp-<?php echo $i; ?>">
			<?php 
				$productEditedSalesPrice = $estimate_product[$i]['product_qty'] * $estimate_product[$i]['sales_price_unit'];
				if($estimate_product[$i]['product_disoption'] == 'amt')
				{
					$productEditedSalesPrice = $productEditedSalesPrice - $estimate_product[$i]['product_discount'];
				}
				if($estimate_product[$i]['product_disoption'] == 'prsnt')
				{
					$discountedPrice = ($productEditedSalesPrice * $estimate_product[$i]['product_discount']) / 100;
					$productEditedSalesPrice = $productEditedSalesPrice - $discountedPrice;
				}
				$qtyAmtPlus = $productEditedSalesPrice;
				$taxAmtPlus = $qtyAmtPlus;
				if(isset($estimate_product[$i]['tax_percentage']) && $estimate_product[$i]['tax_percentage'] != "" && $estimate_product[$i]['is_delete'] != 1)
				{
					$taxAMTCal = ($estimate_product[$i]['tax_percentage'] * $qtyAmtPlus) / 100;
					$taxAmtPlus = $taxAMTCal + $qtyAmtPlus;
				} else 
				{	$taxAMTCal="";	}
				?>
			<?php /*
				$taxAmtPlus = $estimate_product[$i]['sales_price_unit'];
				if(isset($estimate_product[$i]['tax_percentage']) && $estimate_product[$i]['tax_percentage'] != "" && $estimate_product[$i]['is_delete'] != 1)
				{
					$taxAMTCal = ($estimate_product[$i]['tax_percentage'] * $estimate_product[$i]['sales_price_unit']) / 100;
					$taxAmtPlus = $taxAMTCal + $estimate_product[$i]['sales_price_unit'];
				} else 
				{	$taxAMTCal="";	}
*/				//pr($estimate_product);
			?>
          <input type="hidden" id="product_TMP_tax_calculated_<?php echo $i; ?>" name="product_tax_calculated[]" class="product_tax_calculated prdPerticularValue" value="<?php echo $taxAMTCal;?>" >
		<?php /*Following Input Box for Final Calculated Amount*/?>
		<div class="form-group"><div><label ><?php echo lang('EST_LABEL_AMOUNT'); ?> *</label></div> <div class="input-group"><span class="estCurSymbolDiv input-group-addon" readonly><?php echo $symForSelect;?></span>
		  <input class="form-control product_amount product_existing_amount" id="product_TMP_amount_<?php echo $i; ?>" name="product_amount[]" readonly value="<?php echo amtRound($taxAmtPlus); ?>"  placeholder="<?php echo lang('EST_LABEL_AMOUNT'); ?>" type="text" data-parsley-trigger="change" data-parsley-min="0" data-parsley-errors-container="#prdCustFnlAmtError<?php echo $i.'_TMP'; ?>" required /></div>
		  <span id="prdCustFnlAmtError<?php echo $i.'_TMP'; ?>"></span>
		  </div>
		  
        </div>
        <div class="clr"></div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<?php } ?>
<?php } ?>
