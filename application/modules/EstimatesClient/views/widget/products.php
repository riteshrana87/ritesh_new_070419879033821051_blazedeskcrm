<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: Product Widget
  @Input 	: 
  @Output	:
  @Date   	: 09/03/2016
 */
?>
<?php $Symbol = getCurrencySymbol($editRecord[0]['country_id_symbol']); ?>
<div class="parentSortable">
	<div class="CustWhiteBorder" id="products">
		<div class="hidden-xs">
				<div class="row tableRowSortable">
				  <div class="col-md-2 col-sm-2">
				  <p><label><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?></label></p>
					</div>
				  <div class="col-md-4 col-sm-4">
				  <p><label><?php echo lang('EST_LABEL_DESCRIPTION'); ?></label></p>
				  </div>
				  
				  <div class="col-md-2 col-sm-2">
					<p><label><?php echo lang('EST_LABEL_QUANTITY'); ?></label></p>
				  </div>
				  <div class="col-md-2 col-sm-2">
					<p><label><?php echo lang('EST_LABEL_TAX'); ?></label></p>
				  </div>
                  <div class="col-md-2 col-sm-2">
				  <p><label><?php echo lang('EST_LABEL_AMOUNT'); ?></label></p>
				  </div>
				  <div class="clr mar_b6"></div>
				</div>
			<div class="clr"></div>
			</div>	
		<div id="tableRowContainer">
			<?php 
		//All Product amount get in array For Calculation
			$AllProductAmount 		= array();
			$TaxtCalculationArray 	= array();
			$WholeTaxInArray 		= array();
			$DiscountArray 			= array();
			$totalAmtArray 			= array();
			$singleDiscount 		= array();
			$allTaxInArry 			= array();		//Store all Tax value with Key as a Tax 
			?>
		<?php foreach($previewAllProduct as $singleProduct){ ?>
			<?php 
				$singleDiscountAmt = 0;
				$discountAmtDiduct = 0;
				$singletaxAmt = 0;
				$singlediscount = 0;
				$singletax = 0;
				
				$singlePrdTax = $singleProduct['tax_percentage'];
				if(isset($singleProduct['product_sales_price']) && $singleProduct['product_sales_price'] != "")
				{
					$prdSalesPrice 		= $singleProduct['product_sales_price']; 
				} else {
					$prdSalesPrice 		= $singleProduct['sales_price_unit'];
				}
				$multPriceQty = $prdSalesPrice * $singleProduct['product_qty'];
			//Calculate Discount amount as per Single Amount
				$sngPrdDiscount 	= $singleProduct['product_discount'];
				$sngPrdDisOption 	= $singleProduct['product_disoption'];
				if(isset($sngPrdDiscount) && $sngPrdDiscount != "")
				{
					if($sngPrdDisOption == 'prsnt'){
						$singleDiscountAmt = ($multPriceQty * $sngPrdDiscount) / 100;
					//Deduct discount amount from Product amount
						$discountAmtDiduct = $multPriceQty - $singleDiscountAmt;
						$multPriceQty = $discountAmtDiduct;
					}
					if($sngPrdDisOption == 'amt') {
						$discountAmtDiduct = $multPriceQty - $sngPrdDiscount;
						$multPriceQty = $discountAmtDiduct;
					}
				}
				$AllProductAmount[] = $multPriceQty;
			
			//Get Tax as per discount Amount
				$singletaxAmt = ($multPriceQty * $singleProduct['tax_percentage'] ) / 100 ;
				
			//Create All Tax Included Array 
				if(!empty($allTaxInArry) && isset($allTaxInArry[$singleProduct['tax_percentage']]))
				{
					$allTaxInArry[$singleProduct['tax_percentage']][] = $singletaxAmt;
				} else {
					$allTaxInArry[$singleProduct['tax_percentage']] = array();
					$allTaxInArry[$singleProduct['tax_percentage']][] = $singletaxAmt;
				}
				
				//Push final amount in array 
					$totalAmtArray[] = $discountAmtDiduct + $singletaxAmt;
				//Single discount place in array for show
					$singleDiscount[] = $singleDiscountAmt;
				//Single tax place in array for show 
					$TaxtCalculationArray[] = $singletaxAmt;
				////RJ work from here for show Tax Whole Tax Array
					//echo $singletaxAmt;echo "<br>";
					if($singlePrdTax != "")
					{
						$WholeTaxInArray[] = $singlePrdTax;
						}
			?>
			<div>
				<div class="row tableRowSortable" id="<?php echo $singleProduct['est_prd_id'];?>">
				  <div class="col-md-2 col-sm-2">
					<label class="visible-xs"><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?></label>
				  <p><?php echo stripslashes($singleProduct['product_name']);?></p>
					
				  </div>
				  <div class="col-md-4 col-sm-4">
					<label class="visible-xs"><?php echo lang('EST_LABEL_DESCRIPTION'); ?></label>
				  <p><?php echo $singleProduct['product_description'];?></p>
					
				  </div>
				  <div class="col-md-2 col-sm-2">
					<label class="visible-xs"><?php echo lang('EST_LABEL_QUANTITY'); ?></label>
					<p><?php echo $singleProduct['product_qty'];?></p>
				  </div>
				  <div class="col-md-2 col-sm-2">
					<label class="visible-xs"><?php echo lang('EST_LABEL_TAX'); ?></label>
					<p><?php echo $singleProduct['tax_percentage'];?></p>
				  </div>
				  <div class="col-md-2 col-sm-2">
					<label class="visible-xs"><?php echo lang('EST_LABEL_AMOUNT'); ?></label>
					<p><?php echo $Symbol.$prdSalesPrice;?></p>
				  </div>
				  <div class="clr mar_b6"></div>
				</div>
			<div class="clr"></div>
			</div>
		<?php }?>
		<?php //RJ work from here for show Tax pr($WholeTaxInArray);?>
		</div>
		<div class="row">
		  <div class="col-xs-12 col-md-12  col-lg-12 ">
			<div class="form-group row">
			  <div class="col-md-offset-8 col-md-4 bg-gray"><div class="col-xs-6  col-sm-6 col-md-6  padding-leftnone">
				<label > Sub Total : </label>
			  </div>
			  <div class="col-xs-6 col-sm-6 col-md-6">
				<?php if(!empty($AllProductAmount) && count($AllProductAmount) > 0){ $PrdAmountSum =  array_sum($AllProductAmount); } else{	$PrdAmountSum = "0";	}?>
				<p><?php echo $Symbol.amtRound($PrdAmountSum);?></p>
				<div class="clr"> </div>
			  </div>
			  <div class="clr"> </div>
              <div class="grayline-1"></div>
			  <div class="form-group">
				<div class="col-xs-6 col-sm-6 col-md-6 padding-leftnone">
				  <label > Taxes  </label>
				  <?php /*Following show all tax with array*/ ?>
						<?php 
						ksort($allTaxInArry);
						foreach($allTaxInArry as $taxKey => $taxSingleWithValue){
							echo "<p>";
							echo $taxKey;
							echo "</p>";
						}?>
				</div>
				<div class="col-xs-6 col-sm-6 col-md-6">
                 <label> Amount  </label>
					<?php /*Following show all tax with array*/ ?>
						<?php 
						foreach($allTaxInArry as $taxKey => $taxSingleWithValue){
							echo "<p>";
								//echo $taxKey.' - ';
								echo $Symbol.amtRound(array_sum($taxSingleWithValue));
							echo "</p>";
						}?>
					<?php /*Following Hidden box show total tax */?>
					<?php /*
					if(isset($TaxtCalculationArray) && count($TaxtCalculationArray) > 0){
						$TaxSum = array_sum($TaxtCalculationArray);
					}else {	$TaxSum = "0";	} */?>
					<p><?php //echo amtRound($TaxSum); ?></p>
				 <div class="clr"> </div>
				</div>
				<div class="clr"> </div>
				<div class="grayline-1"></div>
				<div class="clr"> </div>
				<div class="form-group">
				  <div class="col-xs-6 col-sm-6 col-md-6 padding-leftnone">
					<label > Total : </label>
				  </div>
				  <div class="col-xs-6 col-sm-6 col-md-6">
					<?php 
				//Final Total Price calculation  - RJ Remove amt condition
					$afterDiscountOption = array_sum($totalAmtArray);
					?>
					<p><?php echo $Symbol.amtRound($afterDiscountOption);?></p>
					<div class="clr"> </div>
				  </div>
				</div>
				<div class="clr"> </div>
			  </div>
			  <div class="clr"> </div></div>
			</div>
		  </div>
		</div>
	</div>
</div>
	<div class="clr mar_b6"></div>