<?php
if (isset($editRecord) && !empty($editRecord)) {
	
	//if (count($groupIdArray) > 0) {
	    //for ($i = 0; $i < count($groupIdArray); $i++) { //pr($groupIdArray);
		$prdGroupFlag = $i + 1;
            ?>
  <div id="prod-group-<?php echo $prdGroupFlag; ?>" class="bd-error-wrap">
  <div class="row">
    <div class="col-xs-12 col-md-2 col-lg-2 col-sm-2 prod-<?php echo $prdGroupFlag; ?>">
      <div class="form-group">
		<div><label class=""><?php echo lang('EST_TITLE_PRD_GPR_NAME');?></label></div>
		<?php /*Following Hidden Field use for Set Product Order.*/?>
			<input type="hidden" name="estCustomPrdOrder_group[]" id="estCustomPrdOrder_group" class="estCustomPrdOrder estCustomPrdOrder_group" value="<?php echo $groupIdArray[$i]['product_order']; ?>" />
	    <select tabindex="-1"  id="product_group_id_<?php echo $prdGroupFlag; ?>" onchange="getproductListByGroup(this.value,<?php echo $prdGroupFlag; ?>)" name="product_group_id[]" class="chosen-select product_group_id" placeholder="<?php echo lang('EST_TITLE_PRD_GPR_NAME'); ?>"  required>
          <option value=""></option>
          <?php
		if (!empty($group_info)) {
			foreach ($group_info as $row) { ?>
          <option value = "<?php echo $row['product_group_id'] ?>"  <?php echo($groupIdArray[$i]['product_group_id'] == $row['product_group_id']) ? 'selected' : ''; ?>><?php echo ucfirst($row['product_group_name']) ?></option>
          <?php
			}
		}
        ?>
        </select>
      </div>
    </div>
	
	<div class="" id="prod-group-relatedData-<?php echo $prdGroupFlag; ?>">
  <div class="col-xs-12 col-md-2 col-lg-2 col-sm-2">
	<div class="form-group">
		<div><label><?php echo lang('EST_TITLE_PRD_GPR_BASE_PRICE');?></label></div>
 		<input type="text" class="form-control" name="product_group_total_amt[]" id="product_group_total_amt_<?php echo $prdGroupFlag; ?>" placeholder="<?php echo lang('EST_PRD_GRP_TOTAL_AMOUNT'); ?>" readonly value="<?php echo $groupIdArray[$i]['product_group_total_amt'];?>" />
	</div>
  </div>
  <div class="col-xs-12 col-md-2 col-lg-2 col-sm-1" id="prod-group-relatedData-<?php echo $prdGroupFlag; ?>">
	<div class="form-group">
		<div><label><?php echo lang('EST_TITLE_PRD_GPR_QTY');?></label></div>
 		<input type="text" class="form-control" name="prd_grpQty[]" id="prd_grpQty<?php echo $prdGroupFlag; ?>" placeholder="<?php echo lang('EST_LBL_PDF_QTY'); ?>" value="<?php echo $groupIdArray[$i]['product_group_qty'];?>" data-parsley-type="number" onchange="chngGrpfnlAmt(this.value, '<?php echo $prdGroupFlag; ?>');" data-parsley-type="number"/>
	</div>
  </div>
  <div class="col-xs-12 col-md-2 col-lg-2 col-sm-2" id="prod-group-relatedData-<?php echo $prdGroupFlag; ?>">
	<div class="form-group">
		<div><label><?php echo lang('EST_TITLE_PRD_GPR_DISCOUNTED_PRICE');?></label></div>
 		<input type="text" class="form-control" name="product_group_discounted_amt[]" id="product_group_discounted_amt_<?php echo $prdGroupFlag; ?>" placeholder="<?php echo lang('EST_TITLE_PRD_GPR_DISCOUNTED_PRICE'); ?>" readonly value="<?php echo $groupIdArray[$i]['product_group_discounted_amt'];?>"/>
	</div>
  </div>
  <div class="col-xs-12 col-md-2 col-lg-2 col-sm-2" id="prod-group-relatedData-<?php echo $prdGroupFlag; ?>">
	<div class="form-group">
		<div class="commonIncludTimeDiv hidden"><label><?php echo lang('EST_TITLE_PRD_GPR_TAX');?></label></div>
 		<input type="text" class="form-control commonIncludTimeDiv prdGrpTaxAmtInclud hidden" name="product_group_tax_amt[]" id="product_group_tax_amt_<?php echo $prdGroupFlag; ?>" placeholder="<?php echo lang('EST_TITLE_PRD_GPR_TOTAL_TAX'); ?>" readonly data-main-tax="<?php echo $groupIdArray[$i]['product_group_tax_amt'];?>" value="<?php echo $groupIdArray[$i]['product_group_tax_amt'];?>"/>
	</div>
  </div>
  <div class="col-xs-12 col-md-2 col-lg-2 col-sm-2" id="prod-group-relatedData-<?php echo $prdGroupFlag; ?>">
	<div class="form-group">
		<div class="commonIncludTimeDiv hidden"><label><?php echo lang('EST_TITLE_PRD_GPR_AMT');?></label></div>
		<?php /*Calculation for Final Amount*/
			$fnlAmount = ( $groupIdArray[$i]['product_group_discounted_amt'] * $groupIdArray[$i]['product_group_qty'] ) + $groupIdArray[$i]['product_group_tax_amt'];
		?>
 		<input type="text" class="form-control commonIncludTimeDiv prdGrpFnlAmtInclud hidden" name="product_group_fnl_amt[]" id="product_group_fnl_amt_<?php echo $prdGroupFlag; ?>" placeholder="<?php echo lang('EST_TITLE_PRD_GPR_TOTAL_GRP_AMT'); ?>" readonly value="<?php echo $fnlAmount;?>"/>
	</div>
  </div>
  </div>
	
	
    <div class="bd-error-control col-sm-1  col-md-1 pull-right col-lg-1">
      <div class="bd-error"> <a title="<?php echo lang(''); ?>" onclick="removeItem('#prod-group-<?php echo $prdGroupFlag; ?>')"><i class="fa fa-trash redcol"></i></a> </div>
    </div>
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
  <div id="prod-group-products-<?php echo $prdGroupFlag; ?>" >
  <?php
                    /*$group_info_products = array();
                    $group_info_products = $this->EstimateModel->getProductsListByExistingGroupId($groupIdArray[$i]);*/
                    ?>
  <?php			
		if (count($group_info_products[$i]) > 0) {
			$incId=0;
			?>
  <?php foreach ($group_info_products[$i] as $group) { ?>
  <div id="group-product-<?php echo $prdGroupFlag . '-' .$incId . '-' . $group['product_id']; ?>" class="bd-resp-btborder">
  <div class="row">
  <?php /* Description:- Comment code for remove Individual Product.
  if ($group['product_group_status'] == 0) { ?>
  <div class="col-md-1 pull-right bd-error-control col-xs-12 col-sm-1 col-lg-1">
    <div class="bd-error"> <a class="" title="Remove Product?" onclick="removeItem('#group-product-<?php echo $prdGroupFlag. '-' .$incId . '-' . $group['product_id']; ?>')"><i class="fa fa-trash redcol"></i></a></div>
  </div>
  <?php } */?>
<div class="col-md-11 col-sm-11 col-lg-11">
  <div class="row">
    <div class="col-xs-12 col-md-3 col-sm-3 col-lg-2 prod-<?php echo $incId; ?>">
      <div class="form-group">
        <div><label><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?></label></div>
        <input class="form-control" id="product_group_name_<?php echo $incId.'_'.$prdGroupFlag; ?>" name="product_group_name[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo $group['product_name']; ?>" placeholder="<?php echo lang('EST_LABEL_PRODUCT_NAME'); ?>" type="text" readonly/>
      </div>
    </div>
    <div class="col-xs-12 col-md-2 col-sm-3 col-lg-2 prod-<?php echo $incId; ?>">
      <div class="form-group">
        <div><label><?php echo lang('EST_LABEL_DESCRIPTION'); ?></label></div>
        <input class="form-control" id="product_group_description_<?php echo $incId.'_'.$prdGroupFlag; ?>" name="product_group_description[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo stripslashes($group['product_description']); ?>" placeholder="<?php echo lang('EST_LABEL_DESCRIPTION'); ?>" type="text" readonly/>
      </div>
    </div>
    <div class="col-xs-12 col-md-2 col-sm-3 amt-width col-lg-2 prod-<?php echo $incId; ?>">
		<div><label><?php echo lang('EST_LABEL_AMOUNT'); ?></label></div>
      <div class="input-group">
        	<?php 
				$taxAmtForPrice = 0;
				if($group['tax_percentage'] != "" && $group['is_delete'] != 1){
					$taxAmtForPrice = ( $group['sales_price_unit'] * $group['tax_percentage'] ) / 100; }
				$taxIncludedPrice = $taxAmtForPrice + $group['sales_price_unit'];
			?>
		<span class="estCurSymbolDiv input-group-addon"><?php echo getCurrencySymbol($editRecord[0]['country_id_symbol']);?></span>
        <input type="text" readonly class="form-control prdAmtSingle" id="product_group_amount_sales_<?php echo $incId.'_'.$prdGroupFlag; ?>" name="product_group_amount_sales[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo $group['sales_price_unit']; ?>" data-taxexclude-amount="<?php echo $group['sales_price_unit']; ?>" data-taxincluded-amount="<?php echo amtRound($taxIncludedPrice); ?>" data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" />
      </div>
    </div>
    <div class="col-xs-12 col-md-1 col-sm-3 col-lg-1 prod-<?php echo $incId; ?>">
      <div class="form-group">
		<div><label><?php echo lang('EST_LABEL_QUANTITY'); ?></label></div>
		<input class="form-control prdQtyGrpUniqNumber_<?php echo $prdGroupFlag;?> product_group_qty prdQtySingle product_group_qty_<?php echo $group['product_group_id']; ?>" id="product_group_qty_<?php echo $incId.'_'.$prdGroupFlag; ?>" onchange="changeAmountValueQty(this.value, '<?php echo $incId.'_'.$prdGroupFlag; ?>', '_group')" name="product_group_qty[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" placeholder="1" type="text" min="1" required="" data-parsley-trigger="change" data-parsley-type="number" value="<?php echo $group['product_qty'];?>" data-oldqty="<?php echo $group['prdMainQty'];?>" readonly />
      </div>
    </div>
	<div class="col-xs-12 col-md-1 disc-optn col-lg-1 col-sm-3 prod-<?php echo $incId; ?>">
		<div class="form-group">
			<div><label><?php echo lang('EST_LABEL_DISCOUNT_OPTION');?></label></div>
			<input type="hidden" name="product_group_disOption[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" id="product_group_disOption_<?php echo $incId.'_'.$prdGroupFlag; ?>" onchange="calDisAmtOptFrm(this.value, '<?php echo $incId.'_'.$prdGroupFlag; ?>', '_group')" value="<?php echo $group['product_disoption'];?>"/>
			<select name="prdGropJustForShow" id="prdGropJustForShow" class="form-control chosen-select prdDiscountOptSng prdDisOptionSingle prdGrpDisOpt_<?php echo $group['product_group_id']; ?>" disabled="true">
				<option value="prsnt" <?php if($group['product_disoption'] == 'prsnt'){ echo "selected"; } ?> >%</option>
				<option value="amt" <?php if($group['product_disoption'] == 'amt'){ echo "selected"; } ?>><?php echo getCurrencySymbol($editRecord[0]['country_id_symbol']);?></option>
			</select>
		</div>	
	</div>
	<div class="col-xs-12 col-md-1 col-lg-1 col-sm-3 prod-<?php echo $incId; ?>">
		<div class="form-group">
			<div><label><?php echo lang('EST_LABEL_DISCOUNT'); ?></label></div>
			<input type="text" class="form-control prdDisSng prdDiscountSingle prdGrpDiscount_<?php echo $group['product_group_id']; ?>" onchange="calDisAmtFrm(this.value, '<?php echo $incId.'_'.$prdGroupFlag; ?>', '_group')" id="product_group_discount_<?php echo $incId.'_'.$prdGroupFlag; ?>" name="product_group_discount[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" placeholder="<?php echo lang('EST_LABEL_DISCOUNT'); ?>" value="<?php echo $group['product_discount']; ?>" <?php /*if($group['product_disoption'] == 'prsnt'){ ?> data-parsley-pattern="^([0-9]{1,3}){1}(\.[0-9]{1,2})?$" data-parsley-range="[0, 100]" <?php } else { ?> data-parsley-pattern="^([0-9]{1,8}){1}(\.[0-9]{1,2})?$" <?php } */?> readonly/>
		</div>	
	</div>
    <div class="col-xs-12 col-md-1 col-sm-3 col-lg-1 prod-<?php echo $incId; ?>">
      <div class="form-group">
        <div><label><?php echo lang('EST_LABEL_TAX'); ?></label></div>
      <?php /* Following Input Box For Product Tax Id */?>
		<input class="form-control product_group_tax prdTaxidSingle product_group_tax_<?php echo $group['product_group_id']; ?>" id="product_group_tax_<?php echo $incId.'_'.$prdGroupFlag; ?>" name="product_group_tax[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php if($group['tax_id'] != "" && $group['is_delete'] != 1){echo $group['tax_id']; }?>" type="hidden" readonly/>
	  <?php /*
			 * Following Input Box for Show Tax Percentage Value
			 * "prdAllTaxPercentageVal" Class : This Class use for show in Calculation part
			 */?>
        <input class="form-control prdAllTaxPercentageVal" id="taxValueForInfo_grp_<?php echo $incId.'_'.$prdGroupFlag; ?>" name="taxValueForInfo_grp_[]" value="<?php if($group['tax_percentage'] != "" && $group['is_delete'] != 1){ echo $group['tax_percentage']; }?>" readonly>
	  </div>
    </div>
    <div class="col-xs-12 col-md-2 col-sm-3 no-right-pad amt-width col-lg-2 form-group  prod-<?php echo $incId; ?>">
      <div><label><?php echo lang('EST_LABEL_AMOUNT'); ?></label></div>
      <?php 
			//Multiple Quantity in sales_price_unit
				$qtyMultAmt = $group['product_qty'] * $group['sales_price_unit'];
			//Deduct Discount Amount from sales_price_unit
				if($group['product_disoption'] == 'amt')
				{
					$qtyMultAmt = $qtyMultAmt - $group['product_discount'];
				}
				if($group['product_disoption'] == 'prsnt')
				{
					$disCalculate = ( $qtyMultAmt * $group['product_discount'] ) / 100;
					$qtyMultAmt = $qtyMultAmt - $disCalculate; 
				}
			//Calculation for the tax
				$taxAmtPlus = $qtyMultAmt;
				if(isset($group['tax_percentage']) && $group['tax_percentage'] != "" && $group['is_delete'] != 1)
				{
					$taxAMTCal = ($group['tax_percentage'] * $taxAmtPlus) / 100;
					$taxAmtPlus = $taxAMTCal + $taxAmtPlus;
				} else 
				{	$taxAMTCal="";	}
			?>
      <input type="hidden" id="product_group_tax_calculated_<?php echo $incId.'_'.$prdGroupFlag; ?>" name="product_group_tax_calculated[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" class="product_tax_calculated" value="<?php echo $taxAMTCal;?>">
      <input type="hidden" id="product_group_product_id_<?php echo $incId.'_'.$prdGroupFlag; ?>" name="product_group_product_id[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" value="<?php echo $group['product_id']; ?>" class="product_group_product_id_<?php echo $group['product_group_id']; ?>">
	  <div class="input-group">
	  <span class="estCurSymbolDiv input-group-addon"><?php echo getCurrencySymbol($editRecord[0]['country_id_symbol']);?></span>
	<?php /*Following Input Box for Final Calculated Amount*/?>
      <input class="form-control prdGrpAmtUniqNumbr_<?php echo $prdGroupFlag;?> product_amount product_group_amount_<?php echo $group['product_group_id']; ?>" id="product_group_amount_<?php echo $incId.'_'.$prdGroupFlag; ?>" value="<?php echo amtRound($taxAmtPlus); ?>" name="product_group_amount[<?php echo $prdGroupFlag;?>][<?php echo $group['product_group_id']; ?>][]" readonly  placeholder="<?php echo lang('EST_LABEL_AMOUNT'); ?>" type="text" data-parsley-trigger="change">
	  </div>
    </div>
    <div class="clr"></div>
  </div>
</div>
</div>
</div>
<?php  $incId++;} ?>
<?php } ?>
</div>
</div>
<div class="clr"></div>
<?php
      //}// For Loop Close 
    //} //If Condition Close
   } else { ?>
<div id="prod-group-<?php echo $incId; ?>" class="row">
  <div class="col-xs-12 col-md-2 col-lg-2  col-sm-12 prod-<?php echo $incId; ?>">
    <div class="form-group ">
		<div><label><?php echo lang('EST_TITLE_PRD_GPR_NAME');?> *</label></div>
	<?php /*Following Hidden Field use for Set Product Order.*/?>
	<input type="hidden" name="estCustomPrdOrder_group[]" id="estCustomPrdOrder_group" class="estCustomPrdOrder estCustomPrdOrder_group" />
      <select tabindex="-1"  id="product_group_id_<?php echo $incId; ?>" onchange="getproductListByGroup(this.value,<?php echo $incId; ?>)" name="product_group_id[]" class="chosen-select product_group_id col-xs-12 col-md-11 col-lg-11" data-placeholder="<?php echo lang('EST_TITLE_PRD_GPR_NAME'); ?>"  required>
        <option value=""></option>
        <?php
    if (!empty($group_info)) {
        foreach ($group_info as $row) {
            ?>
        <option  value="<?= $row['product_group_id'] ?>">
        <?= ucfirst($row['product_group_name']) ?>
        </option>
        <?php
                        }
                    }
                    ?>
      </select>
    </div>
  </div>
  <div class="hidden col-lg-7" id="prod-group-relatedData-<?php echo $incId; ?>">
 <div class="row"> <div class="col-xs-12 col-md-1 col-lg-2 col-sm-3">
	<div class="form-group">
		<div><label><?php echo lang('EST_TITLE_PRD_GPR_BASE_PRICE');?></label></div>
 		<input type="text" class="form-control" name="product_group_total_amt[]" id="product_group_total_amt_<?php echo $incId; ?>" placeholder="<?php echo lang('EST_PRD_GRP_TOTAL_AMOUNT'); ?>" readonly/>
	</div>
  </div>
  <div class="col-xs-12 col-md-2 col-lg-2 col-sm-1" id="prod-group-relatedData-<?php echo $incId; ?>">
	<div class="form-group">
		<div><label><?php echo lang('EST_TITLE_PRD_GPR_QTY');?> *</label></div>
 		<input type="text" class="form-control" name="prd_grpQty[]" id="prd_grpQty<?php echo $incId; ?>" placeholder="<?php echo lang('EST_LABEL_QUANTITY'); ?>" value="1" data-parsley-type="number" onchange="chngGrpfnlAmt(this.value, '<?php echo $incId; ?>');" data-parsley-type="number" required/>
	</div>
  </div>
  <div class="col-xs-12 col-md-2 col-lg-2 col-sm-3" id="prod-group-relatedData-<?php echo $incId; ?>">
	<div class="form-group">
		<div><label><?php echo lang('EST_TITLE_PRD_GPR_DISCOUNTED_PRICE');?></label></div>
 		<input type="text" class="form-control" name="product_group_discounted_amt[]" id="product_group_discounted_amt_<?php echo $incId; ?>" placeholder="<?php echo lang('EST_TITLE_PRD_GPR_DISCOUNTED_PRICE') ; ?>" readonly/>
	</div>
  </div>
  <div class="col-xs-12 col-md-1 col-lg-2 col-sm-2" id="prod-group-relatedData-<?php echo $incId; ?>">
	<div class="form-group">
		<div class="commonIncludTimeDiv hidden"><label><?php echo lang('EST_TITLE_PRD_GPR_TAX');?></label></div>
 		<input type="text" class="form-control commonIncludTimeDiv prdGrpTaxAmtInclud hidden" name="product_group_tax_amt[]" id="product_group_tax_amt_<?php echo $incId; ?>" placeholder="<?php echo lang('EST_TITLE_PRD_GPR_TOTAL_TAX'); ?>" readonly data-main-tax=""/>
	</div>
  </div>
  <div class="col-xs-12 col-md-2 col-lg-2 col-sm-2" id="prod-group-relatedData-<?php echo $incId; ?>">
	<div class="form-group">
		<div class="commonIncludTimeDiv hidden"><label><?php echo lang('EST_TITLE_PRD_GPR_AMT');?></label></div>
 		<input type="text" class="form-control commonIncludTimeDiv prdGrpFnlAmtInclud hidden" name="product_group_fnl_amt[]" id="product_group_fnl_amt_<?php echo $incId; ?>" placeholder="<?php echo lang('EST_TITLE_PRD_GPR_TOTAL_GRP_AMT'); ?>" readonly/>
	</div>
  </div></div>
  </div>
  <div class="bd-error-control  col-md-1 pull-right col-lg-1">
    <div class="bd-error"><a  title="<?php echo lang('EST_TITLE_AUTOGRAPH_REMOVE_PRD'); ?>" onclick="removeItem('#prod-group-<?php echo $incId; ?>')"><i class="fa fa-trash redcol"></i></a></div>
  </div>
  <div class="clr"></div>
  <div id="prod-group-products-<?php echo $incId; ?>"></div>
</div>
<div class="clr"></div>
<?php } ?>