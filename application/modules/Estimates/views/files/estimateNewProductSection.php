<div id="prod-new-<?php echo $incId; ?>" class="bd-resp-btborder">
<?php $dftSymbole = getDefaultCurrencyInfo();?>
    <div class="row">
        <div class="col-md-1 pull-right bd-error-control col-xs-12  col-lg-1">
            <div class="bd-error"><a  title="<?php echo lang('EST_NEW_PRD_REMOVE_PRD'); ?>" onclick="removeItem('#prod-new-<?php echo $incId; ?>')">
            <i class="fa fa-trash redcol "></i></a></div>
        </div>
        <div class="col-md-11 col-xs-12  col-lg-11">
            <div class="row">
                <div class="col-xs-12 col-md-2 col-lg-2 col-sm-3 prod-<?php echo $incId; ?>">
                    <div class="form-group">
						<div><label ><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?> * </label></div>
						<?php /*Following Hidden Field use for Set Product Order.*/?>
						<input type="hidden" name="estCustomPrdOrder_new[]" id="estCustomPrdOrder_new" class="estCustomPrdOrder estCustomPrdOrder_new" />
                        <input class="form-control" id="product_new_name_<?php echo $incId; ?>" name="product_new_name[]" placeholder="<?php echo lang('product_name'); ?>" type="text" onchange="checkPrdNameUnique(this.value, '<?php echo $incId; ?>','_new');" required />
						<span id="PrdNameUnique_new_<?php echo $incId; ?>"></span>
                    </div>	</div>
                <div class="col-xs-12 col-md-2 col-lg-2 col-sm-3 prod-<?php echo $incId; ?>">
                    <div class="form-group">
						<div><label><?php echo lang('EST_LABEL_DESCRIPTION'); ?></label></div>
                        <input class="form-control" id="product_new_description_<?php echo $incId; ?>" name="product_new_description[]" placeholder="<?php echo lang('EST_LABEL_DESCRIPTION'); ?>" type="text" />
                    </div>
                </div>
				<div class="col-xs-12 col-md-2 amt-width col-lg-2 col-sm-3 prod-<?php echo $incId; ?>">
               
                    <div class="form-group">
                     
						<div><label><?php echo lang('EST_LABEL_UNIT_PRICE'); ?> * </label></div>
                        
						<div class="input-group"><span class="estCurSymbolDiv input-group-addon"><?php echo $dftSymbole['symbol'];?></span><input type="text"class="form-control prdAmtSingle" onchange="calculateSalesAmountFromField(this.value, '<?php echo $incId; ?>', '_new')" id="product_new_amount_sales_<?php echo $incId; ?>" name="product_new_amount_sales[]" placeholder="<?php echo lang('EST_LABEL_AMOUNT'); ?>" data-taxexclude-amount="" data-taxincluded-amount="" data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" data-parsley-errors-container="#prdCustAmtError<?php echo $incId.'_new'; ?>" required /></div>
						<span id="prdCustAmtError<?php echo $incId.'_new'; ?>"></span>
                    </div>	
                </div>
				<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3  prod-<?php echo $incId; ?>">
                    <div class="form-group">
						<div><label><?php echo lang('EST_LABEL_QUANTITY'); ?> * </label></div>
                        <div class="form-group"><input class="form-control product_new_qty prdQtySingle" id="product_new_qty_<?php echo $incId; ?>" onkeyup="changeAmountValueQty(this.value, '<?php echo $incId; ?>', '_new')" name="product_new_qty[]" placeholder="1" value="1" type="text" min="1" required="" data-parsley-trigger="change" data-parsley-type="number"/></div>
                    </div>	
                </div>
                <div class="col-xs-12 col-md-1 col-lg-1 disc-optn col-sm-3 prod-<?php echo $incId; ?>">
                    <div class="form-group">
                    <div><label><?php echo lang('EST_LABEL_DISCOUNT_OPTION');?></label></div>
						<select name="product_new_disOption[]" id="product_new_disOption_<?php echo $incId; ?>" class="form-control chosen-select prdDiscountOptSng prdDisOptionSingle prdSngDisOpt" onchange="calDisAmtOptFrm(this.value, '<?php echo $incId; ?>', '_new')">
							<option value="prsnt" >%</option>
							<option value="amt" ><?php echo $dftSymbole['symbol'];?></option>
						</select>
					</div>	
                </div>
				<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 prod-<?php echo $incId; ?>">
                    <div class="form-group">
						<div><label><?php echo lang('EST_LABEL_DISCOUNT'); ?></label></div>
                        <input type="text"class="form-control prdDisSng prdDiscountSingle prdSngDiscount" onchange="calDisAmtFrm(this.value, '<?php echo $incId; ?>', '_new')" id="product_new_discount_<?php echo $incId; ?>" name="product_new_discount[]" placeholder="<?php echo lang('EST_LABEL_DISCOUNT'); ?>" value="0" data-parsley-pattern="^([0-9]{1,2}){1}(\.[0-9]{1,2})?$"/>
                    </div>	
                </div>
				<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 prod-<?php echo $incId; ?>">
                    <div class="form-group">
						<div><label><?php echo lang('EST_LABEL_TAX'); ?> * </label></div>
				<?php  /*
						* Following Input Box for Show Tax Percentage Value
						* "prdAllTaxPercentageVal" Class : This Class use for show in Calculation part
						*/?>
						<input type="hidden" class="form-control prdAllTaxPercentageVal" id="_newTaxValueForInfo_<?php echo $incId; ?>" name="taxValueForInfo[]" value="" readonly>
                        <select class="form-control product_new_tax prdTaxidSingle chosen-select" required onchange="changeAmountValueTax(this.value, '<?php echo $incId; ?>', '_new')" id="product_new_tax_<?php echo $incId; ?>" name="product_new_tax[]" data-parsley-trigger="change" data-parsley-errors-container="#newPrdTaxErrors" required>
                            <option value=""><?php echo lang('EST_LABEL_TAX'); ?></option>
                            <?php if (count($taxes) > 0) { ?>
                                <?php foreach ($taxes as $tax) { ?>
                                    <option value="<?php echo $tax['tax_id']; ?>"><?php echo $tax['tax_percentage']; ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
						<span id="newPrdTaxErrors"></span>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2 col-lg-2 amt-width col-sm-3  no-right-pad form-group prod-<?php echo $incId; ?>">
                   
					<input type="hidden" id="product_new_tax_calculated_<?php echo $incId; ?>" name="product_new_tax_calculated[]" class="product_tax_calculated">
					
				<?php /*Following Input Box for Final Calculated Amount*/?>
                   <div class="form-group"><div> <label><?php echo lang('EST_LABEL_AMOUNT'); ?></label></div> <div class="input-group">
					<span class="estCurSymbolDiv input-group-addon" readonly><?php echo $dftSymbole['symbol'];?></span>
					<input class="form-control product_amount" onchange="calculateAmountValueFromField(this.value, '<?php echo $incId; ?>', '_new')" id="product_new_amount_<?php echo $incId; ?>" name="product_new_amount[]"   placeholder="<?php echo lang('EST_LABEL_AMOUNT'); ?>" readonly type="text" data-parsley-trigger="change" data-parsley-min="0" data-parsley-errors-container="#prdCustFnlAmtError<?php echo $incId.'_new'; ?>" required />
					</div>
					<span id="prdCustFnlAmtError<?php echo $incId.'_new'; ?>"></span>
					</div>
                </div>
                <div class="clr"></div>
            </div>
            <div class="clr"></div>
        </div><div class="clr"></div></div></div>