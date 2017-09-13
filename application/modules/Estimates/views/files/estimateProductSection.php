<?php
if (isset($editRecord) && !empty($editRecord)) {
	//if (count($estPrdInfo) > 0) {
      //  for ($i = 0; $i < count($estPrdInfo); $i++) { //pr($estPrdInfo);
            ?>
<div id="prod-<?php echo $i; ?>" class="bd-resp-btborder">
  <div class="row">
    <div class="col-md-1 pull-right col-lg-1 bd-error-control">
      <div class="bd-error"><a  title="<?php echo lang('EST_TITLE_AUTOGRAPH_REMOVE_PRD'); ?>" onclick="removeItem('#prod-<?php echo $i; ?>')"><i class="fa fa-trash redcol"></i></a></div>
    </div>
    <div class="col-md-11 col-lg-11 col-sm-11">
      <div class="row">
        <div class="col-xs-12 col-md-2 col-sm-3 col-lg-2  prod-<?php echo $i; ?>">
          <div class="form-group">
            <div><label ><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?> *</label></div>
			<?php //Following Hidden Field use for Set Product Order.?>
			<input type="hidden" name="estCustomPrdOrder[]" id="estCustomPrdOrder" class="estCustomPrdOrder" value="<?php echo $estPrdInfo[$i]['product_order']; ?>" />
            <select class="form-control chosen-select product_id" name="product_id[]" id="product_id" required onchange="showInnerBox('prod-<?php echo $i; ?>', this.value, '<?php echo $i; ?>')">
              <option value="">
              <?php lang('EST_LBL_SELECT_PRODUCT');?>
              </option>
              <?php foreach ($product_info as $product) { ?>
              <option <?php echo($estPrdInfo[$i]['product_id'] == $product['product_id']) ? "selected" : ""; ?> value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-sm-3 col-lg-2 prod-<?php echo $i; ?>">
          <div class="form-group">
            <div><label ><?php echo lang('EST_LABEL_DESCRIPTION'); ?></label></div>
            <input class="form-control" id="product_description_<?php echo $i; ?>" name="product_description[]" placeholder="<?php echo lang('EST_LABEL_DESCRIPTION'); ?>" value="<?php echo stripslashes($estPrdInfo[$i]['prdDescription']); ?>" type="text" />
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-sm-3 amt-width col-lg-2 prod-<?php echo $i; ?>">
       
          <div class="form-group"> 
           <div> <label><?php echo lang('EST_LABEL_AMOUNT'); ?> * </label></div>
			<?php 
				$taxAmtForPrice = 0;
				$prdSalesPrice = $estPrdInfo[$i]['product_sales_price'];
				if(isset($estPrdInfo[$i]['tax_percentage']) && $estPrdInfo[$i]['tax_percentage'] != "" && $estPrdInfo[$i]['is_delete'] != 1){
					$taxAmtForPrice = ( $prdSalesPrice * $estPrdInfo[$i]['tax_percentage']) / 100; }
				$taxIncludedPrice = $taxAmtForPrice + $prdSalesPrice;
			?>
			
            <div class="input-group"><span class="estCurSymbolDiv input-group-addon"><?php echo getCurrencySymbol($editRecord[0]['country_id_symbol']);?></span>
			<input type="text" class="form-control product_amount_sales prdAmtSingle" id="product_amount_sales_<?php echo $i; ?>" name="product_amount_sales[]" onchange="calculateSalesAmountFromField(this.value, '<?php echo $i; ?>', '')" value="<?php echo $estPrdInfo[$i]['product_sales_price']; ?>" data-taxexclude-amount="<?php echo $estPrdInfo[$i]['product_sales_price']; ?>" data-taxincluded-amount="<?php echo amtRound($taxIncludedPrice); ?>" data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" data-parsley-errors-container="#prdCustAmtError<?php echo $i; ?>" />
			</div>
			<span id="prdCustAmtError<?php echo $i; ?>"></span>
          </div>
        </div>
        <div class="col-xs-12 col-md-1 col-sm-3 col-lg-1 prod-<?php echo $i; ?>">
          <div class="form-group">
            <div><label ><?php echo lang('EST_LABEL_QUANTITY'); ?> * </label></div>
            <input class="form-control product_qty prdQtySingle" id="product_qty_<?php echo $i; ?>" onkeyup="changeAmountValueQty(this.value, '<?php echo $i; ?>', '')" name="product_qty[]" placeholder="1" value="<?php echo $estPrdInfo[$i]['product_qty']; ?>" type="text" min="1" required="" data-parsley-trigger="change" data-parsley-type="number" />
          </div>
        </div>
		<div class="col-xs-12 col-md-1 col-lg-1 disc-optn col-sm-3 prod-<?php echo $i; ?>">
         <div><label><?php echo lang('EST_LABEL_DISCOUNT_OPTION');?></label></div>
			<div class="form-group">
			
				<select name="product_disOption[]" id="product_disOption_<?php echo $i; ?>" class="form-control chosen-select prdDiscountOptSng prdDisOptionSingle prdSngDisOpt" onchange="calDisAmtOptFrm(this.value, '<?php echo $i; ?>', '')">
					<option value="prsnt" <?php if($estPrdInfo[$i]['product_disoption'] == "prsnt"){ echo "selected"; } ?> >%</option>
					<option value="amt" <?php if($estPrdInfo[$i]['product_disoption'] == "amt"){ echo "selected"; } ?> ><?php echo getCurrencySymbol($editRecord[0]['country_id_symbol']);?></option>
				</select>
			</div>	
		</div>
		<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 prod-<?php echo $i; ?>">
			<div class="form-group">
				<div><label ><?php echo lang('EST_LABEL_DISCOUNT'); ?></label></div>
				<input type="text" class="form-control prdDisSng prdDiscountSingle prdSngDiscount" onchange="calDisAmtFrm(this.value, '<?php echo $i; ?>', '')" id="product_discount_<?php echo $i; ?>" name="product_discount[]" placeholder="<?php echo lang('EST_LABEL_DISCOUNT'); ?>" value="<?php echo $estPrdInfo[$i]['product_discount']?>" <?php if($estPrdInfo[$i]['product_disoption'] == "prsnt"){ ?> data-parsley-pattern="^([0-9]{1,3}){1}(\.[0-9]{1,2})?$" data-parsley-range="[0, 100]" <?php } else { ?> data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" <?php } ?> />
			</div>	
		</div>
		<div class="col-xs-12 col-md-1 col-sm-3 col-lg-1 prod-<?php echo $i; ?>">
          <div class="form-group">
            <div><label><?php echo lang('EST_LABEL_TAX'); ?> * </label></div>
            <input class="form-control product_tax prdTaxidSingle" id="product_tax_<?php echo $i; ?>" name="product_tax[]" value="<?php if($estPrdInfo[$i]['is_delete'] != 1){ echo $estPrdInfo[$i]['tax_id']; }?>" type="hidden"  data-parsley-trigger="change" readonly/>
		<?php   //Following Input Box for Show Tax Percentage Value "prdAllTaxPercentageVal" Class : This Class use for show in Calculation part
				?>
            <input class="form-control prdAllTaxPercentageVal" id="taxValueForInfo_<?php echo $i; ?>" name="taxValueForInfo[]" value="<?php if($estPrdInfo[$i]['is_delete'] != 1){ echo $estPrdInfo[$i]['tax_percentage']; }?>" readonly>
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-sm-3 col-lg-2 amt-width no-right-pad form-group  prod-<?php echo $i; ?>">
          
          <?php 
				$productEditedSalesPrice = $estPrdInfo[$i]['product_qty'] * $estPrdInfo[$i]['product_sales_price'];
				if($estPrdInfo[$i]['product_disoption'] == 'amt')
				{
					$productEditedSalesPrice = $productEditedSalesPrice - $estPrdInfo[$i]['product_discount'];
				}
				if($estPrdInfo[$i]['product_disoption'] == 'prsnt')
				{
					$discountedPrice = ($productEditedSalesPrice * $estPrdInfo[$i]['product_discount']) / 100;
					$productEditedSalesPrice = $productEditedSalesPrice - $discountedPrice;
				}
				$qtyAmtPlus = $productEditedSalesPrice;
				$taxAmtPlus = $qtyAmtPlus;
				if(isset($estPrdInfo[$i]['tax_percentage']) && $estPrdInfo[$i]['tax_percentage'] != "" && $estPrdInfo[$i]['is_delete'] != 1)
				{
					$taxAMTCal = ($estPrdInfo[$i]['tax_percentage'] * $qtyAmtPlus) / 100;
					$taxAmtPlus = $taxAMTCal + $qtyAmtPlus;
				} else 
				{	$taxAMTCal="";	}
				?>
          <input type="hidden" id="product_tax_calculated_<?php echo $i; ?>" name="product_tax_calculated[]" class="product_tax_calculated" value="<?php echo $taxAMTCal;?>" >
		  
		<?php //Following Input Box for Final Calculated Amount?>
         <div class="form-group"><div><label ><?php echo lang('EST_LABEL_AMOUNT'); ?> *</label></div> <div class="input-group"><span class="estCurSymbolDiv input-group-addon" readonly><?php echo getCurrencySymbol($editRecord[0]['country_id_symbol']);?></span><input class="form-control product_amount product_existing_amount" id="product_amount_<?php echo $i; ?>" name="product_amount[]" readonly value="<?php echo amtRound($taxAmtPlus); ?>"  placeholder="<?php echo lang('EST_LABEL_AMOUNT'); ?>" type="text" data-parsley-trigger="change" data-parsley-min="0" data-parsley-errors-container="#prdCustFnlAmtError<?php echo $i; ?>" required/></div>
		 <span id="prdCustFnlAmtError<?php echo $i; ?>"></span>
		 </div>
        </div>
        <div class="clr"></div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<?php //} ?>
<?php //} ?>
<?php } else { ?>
<?php $dftSymbole = getDefaultCurrencyInfo();?>
<div id="prod-<?php echo $incId; ?>" class="bd-resp-btborder">
  <div class="row">
    <div class="col-md-1 col-lg-1 pull-right bd-error-control">
      <div class="bd-error"><a  title="<?php echo lang('EST_TITLE_AUTOGRAPH_REMOVE_PRD'); ?>" onclick="removeItem('#prod-<?php echo $incId; ?>')"><i class="fa fa-trash redcol"></i></a></div>
    </div>
    <div class="col-md-11 col-lg-11 ">
      <div  class="row">
        <div class="col-xs-12 col-md-2 no-left-pad col-lg-2 col-sm-3 prod-<?php echo $incId; ?>">
          <div class="form-group">
            <label ><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?> *</label>
		<?php /*Following Hidden Field use for Set Product Order.*/?>
			<input type="hidden" name="estCustomPrdOrder[]" id="estCustomPrdOrder" class="estCustomPrdOrder" />
            <select class="form-control chosen-select product_id" name="product_id[]" id="product_id" required onchange="showInnerBox('prod-<?php echo $incId; ?>', this.value, '<?php echo $incId; ?>')" data-placeholder = "<?php echo lang('select_option');?>">
              <option value="">
              <?php echo lang('select_option');?>
              </option>
              <?php foreach ($product_info as $product) { ?>
              <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-lg-2 col-sm-3 hidden prod-<?php echo $incId; ?>">
          <div class="form-group">
            <label><?php echo lang('EST_LABEL_DESCRIPTION'); ?></label>
            <input class="form-control" id="product_description_<?php echo $incId; ?>" name="product_description[]" placeholder="<?php echo lang('EST_LABEL_DESCRIPTION'); ?>" type="text" />
          </div>
        </div>
        <div class="col-xs-12 col-md-2 amt-width col-lg-2 col-sm-3 hidden prod-<?php echo $incId; ?>">
      
        
          <div class="form-group">
              <div><label ><?php echo lang('EST_LABEL_AMOUNT'); ?> * </label></div>
			
			<div class="input-group"><span class="estCurSymbolDiv input-group-addon"><?php echo $dftSymbole['symbol'];?></span><input type="text" data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" class="form-control product_amount_sales prdAmtSingle" id="product_amount_sales_<?php echo $incId; ?>" name="product_amount_sales[]" onchange="calculateSalesAmountFromField(this.value, '<?php echo $incId; ?>', '')" data-taxincluded-amount="" data-taxexclude-amount="" data-parsley-errors-container="#prdCustAmtError<?php echo $i; ?>" required /></div>
			<span id="prdCustAmtError<?php echo $i; ?>"></span>
          </div>
        </div>
        <div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 hidden prod-<?php echo $incId; ?>">
          <div class="form-group">
            <label><?php echo lang('EST_LABEL_QUANTITY'); ?> * </label>
            <input class="form-control product_qty prdQtySingle" id="product_qty_<?php echo $incId; ?>" onkeyup="changeAmountValueQty(this.value, '<?php echo $incId; ?>', '')" name="product_qty[]" placeholder="1" value="1" type="text" min="1" required="" data-parsley-trigger="change" data-parsley-type="number" />
          </div>
        </div>
		<div class="col-xs-12 col-md-1 col-lg-1 disc-optn col-sm-3 hidden prod-<?php echo $incId; ?>">
        <div><label><?php echo lang('EST_LABEL_DISCOUNT_OPTION');?></label></div>
			<div class="form-group">
            
				<select name="product_disOption[]" id="product_disOption_<?php echo $incId; ?>" class="form-control chosen-select prdDiscountOptSng prdDisOptionSingle prdSngDisOpt" onchange="calDisAmtOptFrm(this.value, '<?php echo $incId; ?>', '')">
					<option value="prsnt" >%</option>
					<option value="amt" ><?php echo $dftSymbole['symbol'];?></option>
				</select>
			</div>	
		</div>
		<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 hidden prod-<?php echo $incId; ?>">
			<div class="form-group">
				<div><label ><?php echo lang('EST_LABEL_DISCOUNT'); ?></label></div>
				<input type="text" class="form-control prdDisSng prdDiscountSingle prdSngDiscount" onchange="calDisAmtFrm(this.value, '<?php echo $incId; ?>', '')" id="product_discount_<?php echo $incId; ?>" name="product_discount[]" placeholder="<?php echo lang('EST_LABEL_DISCOUNT'); ?>" value="0" data-parsley-pattern="^([0-9]{1,2}){1}(\.[0-9]{1,2})?$" />
			</div>	
		</div>
        <div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 hidden prod-<?php echo $incId; ?>">
          <div class="form-group">
            <div><label ><?php echo lang('EST_LABEL_TAX'); ?> * </label></div>
		<?php /* Following Input Box For Product Tax Id */?>
            <input class="form-control product_tax prdTaxidSingle" id="product_tax_<?php echo $incId; ?>" name="product_tax[]" value="" type="hidden"  data-parsley-trigger="change" readonly/>
		<?php  /*
				* Following Input Box for Show Tax Percentage Value
				* "prdAllTaxPercentageVal" Class : This Class use for show in Calculation part
				*/?>
            <input class="form-control prdAllTaxPercentageVal" id="taxValueForInfo_<?php echo $incId; ?>" name="taxValueForInfo[]" value="" readonly>
            <?php /*?><select class="form-control product_tax prdTaxidSingle chosen-select" onchange="changeAmountValueTax(this.value, '<?php echo $incId; ?>', '')" id="product_tax_<?php echo $incId; ?>" name="product_tax[]" data-parsley-trigger="change" >
                                <option value="0"><?php echo lang('EST_LABEL_TAX'); ?></option>
                                <?php if (count($taxes) > 0) { ?>
                                    <?php foreach ($taxes as $tax) { ?>
                                        <option value="<?php echo $tax['tax_id']; ?>"><?php echo $tax['tax_percentage']; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select><?php */?>
          </div>
        </div>
        <div class="col-xs-12 col-md-2 col-lg-2 amt-width col-sm-3 form-group hidden prod-<?php echo $incId; ?>">
          
		<?php 
		/*
		 * Following Hidden field store Tax value - Calculated Tax value as per main amount
		 * "product_tax_calculated" Class for get All Tax Value
		 * "prdPerticularValue" Class for Get Perticular class
		 */?>
        
          <input type="hidden" id="product_tax_calculated_<?php echo $incId; ?>" name="product_tax_calculated[]" class="product_tax_calculated prdPerticularValue">
		  
         <div class="form-group">  <div><label ><?php echo lang('EST_LABEL_AMOUNT'); ?> *</label></div><div class="input-group"><span class="estCurSymbolDiv input-group-addon " readonly><?php echo $dftSymbole['symbol'];?></span><input class="form-control product_amount product_existing_amount" id="product_amount_<?php echo $incId; ?>" name="product_amount[]" readonly  placeholder="<?php echo lang('EST_LABEL_AMOUNT'); ?>" type="text" data-parsley-trigger="change" data-parsley-min="0"  data-parsley-errors-container="#prdCustFnlAmtError<?php echo $i; ?>" required/></div>
		 <span id="prdCustFnlAmtError<?php echo $i; ?>"></span>
		 </div>
        </div>
        <div class="clr"></div>
      </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
<?php }
?>
