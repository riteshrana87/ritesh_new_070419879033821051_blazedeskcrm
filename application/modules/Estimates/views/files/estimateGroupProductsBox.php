<?php
if (count($group_info_products) > 0) { ?>
<?php $crntGroupId = $incId;?>
<?php $dftSymbole = getDefaultCurrencyInfo();?>
    <?php foreach ($group_info_products as $group) { ?>
		<div id="group-product-<?php echo $incId . '-' . $group['product_id']; ?>" class="bd-resp-btborder">
			<?php /*Following is Remove Particular Product Functionality ?>
			<div class="col-md-1 pull-right bd-error-control col-xs-12  col-lg-1">
				<div class="bd-error">
				<?php if ($group['product_group_status'] == 0) { ?>
					<a title="Remove Product?" onclick="removeItem('#group-product-<?php echo $incId . '-' . $group['product_id']; ?>')"><i class="fa fa-trash redcol"></i>
					</a>
				<?php } ?>
				</div>
			</div>
			<?php */?>
			<div class="col-md-11 col-xs-12  col-lg-11">
				<div class="row">
					<div class="col-xs-12 col-md-2 col-lg-2 col-sm-3 prod-<?php echo $incId; ?>">
						<div class="form-group">
							<div><label ><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?></label></div>
							<input class="form-control product_group_name_<?php echo $group['product_group_id']; ?>" id="product_group_name_<?php echo $incId.'_'.$crntGroupId; ?>" name="product_group_name[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo $group['product_name']; ?>" placeholder="<?php echo lang('EST_TITLE_PRD_GPR_NAME') ?>" type="text" readonly />
						</div>
					</div>
					<div class="col-xs-12 col-md-2 col-lg-2 col-sm-3  prod-<?php echo $incId; ?>">
						<div class="form-group">
							<div><label class=""><?php echo lang('EST_LABEL_DESCRIPTION'); ?></label></div>
							<input class="form-control" id="product_group_description_<?php echo $incId.'_'.$crntGroupId; ?>" name="product_group_description[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo stripslashes($group['product_description']); ?>" placeholder="<?php echo lang('project_desc') ?>" type="text" readonly />
						</div>
					</div>
					<div class="col-xs-12 col-md-2 amt-width col-lg-2 col-sm-3 prod-<?php echo $incId; ?>">
						<div class="form-group">
							<div><label class=""><?php echo lang('EST_LABEL_AMOUNT'); ?></label></div>
							<?php 
								$taxAmtForPrice = 0;
								if($group['tax_percentage'] != "" && $group['is_delete'] != 1){
									$taxAmtForPrice = ( $group['sales_price_unit'] * $group['tax_percentage'] ) / 100; }
								$taxIncludedPrice = $taxAmtForPrice + $group['sales_price_unit'];
							?>
							<?php /*Following Span For show added Currency*/?>
							<div class="input-group">
							<span class="estCurSymbolDiv input-group-addon"><?php echo $dftSymbole['symbol'];?></span>
							<input type="text" readonly class="form-control product_group_amount_sales_<?php echo $group['product_group_id']; ?> prdAmtSingle" id="product_group_amount_sales_<?php echo $incId.'_'.$crntGroupId; ?>" name="product_group_amount_sales[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo $group['sales_price_unit']; ?>" data-taxexclude-amount="<?php echo $group['sales_price_unit']; ?>" data-taxincluded-amount="<?php echo amtRound($taxIncludedPrice); ?>" data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" >
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 prod-<?php echo $incId; ?>">
						<div class="form-group">
							<div><label class=""><?php echo lang('EST_LABEL_QUANTITY'); ?></label></div>
							<input class="form-control prdQtyGrpUniqNumber_<?php echo $crntGroupId;?> prdQtySingle product_group_qty product_group_qty_<?php echo $incId; ?> product_group_qty_<?php echo $group['product_group_id']; ?>" id="product_group_qty_<?php echo $incId.'_'.$crntGroupId; ?>" onchange="changeAmountValueQty(this.value, '<?php echo $incId.'_'.$crntGroupId; ?>', '_group')" name="product_group_qty[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" placeholder="1" value="<?php echo $group['product_qty'];?>" type="text" min="1" required="" data-parsley-trigger="change" data-oldqty="<?php echo $group['product_qty'];?>" readonly data-parsley-type="number"/>
						</div>
					</div>
					<div class="col-xs-12 col-md-1 col-lg-1 disc-optn col-sm-3 prod-<?php echo $incId; ?>">
						<div class="form-group">
							<div><label><?php echo lang('EST_LABEL_DISCOUNT_OPTION'); ?></label></div>
							<input type="hidden" name="product_group_disOption[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" id="product_group_disOption_<?php echo $incId.'_'.$crntGroupId; ?>" onchange="calDisAmtOptFrm(this.value, '<?php echo $incId.'_'.$crntGroupId; ?>', '_group')" value="<?php echo $group['discount_option'];?>"/>
							<select name="prdGropJustForShow" id="prdGropJustForShow" class="form-control chosen-select prdDiscountOptSng prdDisOptionSingle prdGrpDisOpt_<?php echo $group['product_group_id']; ?>" disabled="true">
								<?php if($group['discount_option'] == 'prsnt'){ ?>
									<option value="prsnt">%</option>
								<?php }?>
								<?php if($group['discount_option'] == 'amt'){ ?>
									<option value="amt"><?php echo $dftSymbole['symbol'];?></option>
								<?php }?>
							</select>
						</div>	
					</div>
					<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 prod-<?php echo $incId; ?>">
						<div class="form-group">
							<div><label ><?php echo lang('EST_LABEL_DISCOUNT'); ?></label></div>
                                                        <input type="text" class="form-control prdDisSng prdDiscountSingle prdGrpDiscount_<?php echo $group['product_group_id']; ?>" onchange="calDisAmtFrm(this.value, '<?php echo $incId.'_'.$crntGroupId; ?>', '_group')" id="product_group_discount_<?php echo $incId.'_'.$crntGroupId; ?>" name="product_group_discount[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" placeholder="<?php echo lang('discount'); ?>" value="<?php echo $group['product_discount']; ?>" readonly/>
						</div>	
					</div>
					<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 prod-<?php echo $incId; ?>">
						<div class="form-group">
							<div><label ><?php echo lang('EST_LABEL_TAX'); ?></label></div>
						<?php /* Following Input Box For Product Tax Id */?>
							<input class="prdTaxidSingle product_group_tax product_group_tax_<?php echo $group['product_group_id']; ?>" id="product_group_tax_<?php echo $incId.'_'.$crntGroupId; ?>" name="product_group_tax[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php if($group['tax_id'] != "" && $group['is_delete'] != 1){echo $group['tax_id']; }?>" type="hidden"  data-parsley-trigger="change" readonly/>
					<?php /*
						   * Following Input Box for Show Tax Percentage Value
						   * "prdAllTaxPercentageVal" Class : This Class use for show in Calculation part
						   */?>
							<input class="form-control prdAllTaxPercentageVal" id="taxValueForInfo_grp_<?php echo $incId.'_'.$crntGroupId; ?>" name="taxValueForInfo_grp_[]" value="<?php if($group['tax_percentage'] != "" && $group['is_delete'] != 1){echo $group['tax_percentage']; }?>" readonly>
							
							<?php /*?><select class="form-control prdTaxidSingle product_group_tax product_group_tax_<?php echo $group['product_group_id']; ?> chosen-select" onchange="changeAmountValueTax(this.value, '<?php echo $incId; ?>', '_group')" id="product_group_tax_<?php echo $incId; ?>" name="product_group_tax[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" data-parsley-trigger="change" >
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
					<div class="col-xs-12 col-md-2 col-lg-2 form-group col-sm-3 amt-width no-right-pad prod-<?php echo $incId; ?>">
						<div><label class=""><?php echo lang('EST_LABEL_AMOUNT'); ?></label></div>
						<?php 
							$salesPriceUnit = $group['sales_price_unit'];
							if($group['discount_option'] == 'amt')
							{
								$salesPriceUnit = $salesPriceUnit - $group['product_discount'];
							}
							if($group['discount_option'] == 'prsnt')
							{
								$discountedPrice = ($salesPriceUnit * $group['product_discount']) / 100;
								$salesPriceUnit = $salesPriceUnit - $discountedPrice;
							}
							$taxAmtPlus =  $salesPriceUnit * $group['product_qty'];
							if(isset($group['tax_percentage']) && $group['tax_percentage'] != "" && $group['is_delete'] != 1)
							{
								$taxAMTCal = ($group['tax_percentage'] * $taxAmtPlus) / 100;
								$taxAmtPlus = $taxAMTCal + $taxAmtPlus;
							} else 
							{	$taxAMTCal="";	}
						?>
					<?php /*Following Input box for Tax Value*/?>
						<input type="hidden" id="product_group_tax_calculated_<?php echo $incId.'_'.$crntGroupId; ?>" name="product_group_tax_calculated[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo $taxAMTCal; ?>" class="product_tax_calculated">
					<?php /*Following Input Box for Product ID*/?>	
						<input type="hidden" id="product_group_product_id_<?php echo $incId.'_'.$crntGroupId; ?>" name="product_group_product_id[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo $group['product_id']; ?>" class="product_group_product_id_<?php echo $group['product_group_id']; ?>">
					<?php /*Following Span For show added Currency*/?>
					<div class="input-group">
						<span class="estCurSymbolDiv input-group-addon"><?php echo $dftSymbole['symbol'];?></span>
					<?php /*Following Input Box for Final Calculated Amount*/?>
						<input class="form-control prdGrpAmtUniqNumbr_<?php echo $crntGroupId;?> product_amount product_group_amount_<?php echo $group['product_group_id']; ?>" id="product_group_amount_<?php echo $incId.'_'.$crntGroupId; ?>" value="<?php echo $taxAmtPlus; ?>" name="product_group_amount[<?php echo $crntGroupId;?>][<?php echo $group['product_group_id']; ?>][]" readonly  placeholder="<?php echo lang('EST_LABEL_AMOUNT'); ?>" type="text" data-parsley-trigger="change">
					</div>
					</div>
					<div class="clr"></div>
				</div>
			</div>
			<div class="clr"></div>
        </div>
    <?php $incId++;
		} ?>
<?php } ?>