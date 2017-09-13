<div class="col-xs-12 col-md-12  col-lg-12 " >
  <div class="row">
   
	<?php 
	/*Code for get selected Curruncy symbol*/
		$dftSymbole = getDefaultCurrencyInfo();
			//getCurrencySymbol($editRecord[0]['country_id_symbol'])
		if(isset($cntSymbol) && $cntSymbol != ""){
			$sysSelected = $cntSymbol;
		} else {
			$sysSelected = $dftSymbole['country_id'];
		} ?>
    <div class="form-group">
      <div class="col-xs-12 col-md-6 col-lg-2 col-sm-6 taxfont">
        <label><?php echo lang('EST_PREVIEW_TAXES'); ?></label>
		<?php 
		
		ksort($allTaxInArry); 
		foreach($allTaxInArry as $taxKey => $taxSingleWithValue){ ?>
			<label><?php if($taxKey != 'null'){ echo $taxKey.'%'; } ?></label>
		<?php }?>
      </div>
      <div class="col-xs-12 col-md-6 col-lg-2 col-sm-6">
	<?php /*Following show all tax with array*/ ?>
    	 <label><?php echo lang('EST_LABEL_AMOUNT'); ?></label>
		<?php  foreach($allTaxInArry as $taxKey => $taxSingleWithValue ){ if($taxKey != 'null'){  ?>
		<div><span class="estCurSymbolDiv"><?php echo getCurrencySymbol($sysSelected);?></span>
			<label><?php echo amtRound(array_sum($taxSingleWithValue)); ?></label></div>
		<?php } }?>
	<?php /*Following Hidden box show total tax */?>
		<input class="form-control" placeholder="0.00" type="hidden" value="<?php echo amtRound($taxes); ?>" readonly id="total_tax" name="total_tax" />
        <div class="clr"> </div>
      </div>
      <div class="clr"> </div>
    </div>
     <div class="form-group mt15 bd-form-group subTotalTaxArea">
      <div class="col-xs-12 col-md-6 col-lg-2 col-sm-6">
          <label><?php echo lang('EST_PREVIEW_SUB_TOTAL'); ?> :</label>
      </div>
      <div class="col-xs-12 col-md-6 col-lg-2 col-sm-6">
        <input class="form-control" placeholder="00.00" type="text" value="<?php echo amtRound($subtotal); ?>" readonly id="sub_total" name="sub_total" />
      </div>
      <div class="clr"> </div>
    </div>
    <div class="form-group bd-form-group">
		<?php /*Remove Following hidden div at the end - Its related with Discount*/?>
      <div class="form-group hidden">
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
          <label><?php echo lang('EST_LABEL_DISCOUNT'); ?> : </label>
        </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
          <div class="input-group row discountbx0" >
            <div class="col-lg-4 col-xs-4 ">
				<?php $currencyInfo = getDefaultCurrencyInfo(); ?>
                <select name="discount_Opt" id="discount_Opt" class="chosen-select" data-parsley-trigger="change" data-placeholder="<?php echo lang('EST_TITLE_AUTOGRAPH_SELECT'); ?>" onchange="doSubtotal();" required>
                <option value="prsnt" <?php if($discountOption == 'prsnt'){ echo 'selected=""'; }?> >%</option>
                <option value="amt" <?php if($discountOption == 'amt'){ echo 'selected=""'; }?>><?php echo $currencyInfo['symbol'];?></option>
              </select>
            </div>
            <div class="col-lg-8 col-xs-8">
              <input type="text" name="discount" onchange="showDiscount(this.value);" value="<?php echo $discount; ?>" id="discount" class="form-control" <?php if($discountOption == 'prsnt'){ ?> data-parsley-max='100' <?php }?> data-parsley-trigger="change" data-parsley-pattern="^([0-9]{1,2}){1}(\.[0-9]{1,2})?$" >
            </div>
            <div class="clr"> </div>
          </div>
        </div>
        <div class="clr"> </div>
      </div>
	  <?php /*Remove Following hidden div at the end - Its related with Discount*/?>
      <div class="form-group hidden" id="discountbox1" <?php if ($discount_earned == '') { ?>style="display:none"<?php } ?>>
        <div class="row">
          <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
              <p class="pad-6" ><?php echo lang('TOTAL_DISCOUNT'); ?> : </p>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-6 col-sm-6">
            <p class="pad-6"><b> <?php echo amtRound($discount_earned); ?> </b> </p>
          </div>
          <div class="clr"></div>
          <input class="form-control" placeholder="00.00" type="hidden" value="<?php echo $discount_earned; ?>" readonly id="sub_total" name="sub_total" />
        </div>
        <div class="clr"> </div>
      </div>
      <div class="form-group bd-form-group">
        <div class="col-xs-12 col-md-6 col-lg-2 col-sm-6">
          <label> <?php echo lang('total_amount'); ?> : </label>
        </div>
        <div class="col-xs-12 col-md-6 col-lg-2 col-sm-6">
          <div class="row input-group">
            <div class="col-xs-4 col-md-6 col-lg-4 col-sm-4">
				<?php /*Following Input box for store Old Country ID*/?>
				<?php /*?><input type="hidden" value="<?php echo $country['country_id'];?>" /><?php */?>
				<input type="hidden" name="country_id_symbol" id="inserted_country_id_symbol" value="<?php echo $sysSelected;?>" />
                <select name="country_id_symbolJustShow" id="country_id_symbol" class="chosen-select" data-parsley-trigger="change" data-placeholder="<?php echo lang('select'); ?>" onchange="SaveEstForm('currencyChange')" required>
			    <?php foreach ($country_info as $country) {?>
                <option value="<?php echo $country['country_id']; ?>" <?php if($sysSelected == $country['country_id']){ echo 'selected=""'; }?>><?php echo $country['currency_symbol']; ?></option>
                <?php } ?>
              </select>
              <div class="clr"> </div>
            </div>
            <div class=" col-xs-8  col-md-5 col-lg-8  col-sm-8">
              <input class="form-control prdTotal" placeholder="00.00" value="<?php echo amtRound($total); ?>" id="total" name="total" readonly type="text" />
            </div>
            <div class="clr"> </div>
          </div>
        </div>
        <div class="clr"> </div>
      </div>
      <div class="clr"> </div>
    </div>
  </div>
  <div class="clr"> </div>
</div>
<div class="clr"> </div>
</div>
