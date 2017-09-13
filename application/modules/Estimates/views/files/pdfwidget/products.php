<?php 
/*
  @Author 	: RJ(Rupesh Jorkar)
  @Desc   	: PDF Product Widget
  @Input 	: 
  @Output	:
  @Date   	: 18/05/2016
 */
?>
<?php $Symbol = $PDFCuntArray[0]['currency_symbol'];?>
<div style='page-break-before: always;'></div>
<table style="width: 100%;">
			<tbody>
				<tr>
				<td>
					<p style="text-align: left;font-size:20px;font-weight:bold;"><?php echo lang('EST_PDF_PRICING_OVERVIEW'); ?></p>
				</td>
				</tr>
				</tbody>
			</table>
			<table style="width: 100%;">
				<tbody>
				<tr>
					<th style="width:10%"><?php echo lang("EST_LBL_PDF_QTY");?></th>
					<th style="width:30%;text-align:left;"><?php echo lang('EST_LABEL_PRODUCT_NAME'); ?></th>
					<th style="width:20%;text-align:right;"><?php echo lang('EST_LABEL_SINGLE_PRICE'); ?></th>
					<th style="width:20%;text-align:right;"><?php echo lang('EST_LABEL_ITEM_TAX'); ?></th>
					<th style="width:20%;text-align:right;"><?php echo lang('EST_LABEL_TOTAL_LINE_PRICE'); ?></th>
				</tr>
				<?php
				//All Product amount get in array For Calculation
				$AllPRDAmount 			= array();
				$TaxtCalculationArray 	= array();
				$totalAmtArray 			= array();
				$allTaxInArry 			= array();		//Store all Tax value with Key as a Tax 
				?>
				<?php foreach($previewAllProduct as $singleProduct){
					$singleDiscountAmt = 0;
					$discountAmtDiduct = 0;
					$singletaxAmt = 0;
					$singlediscount = 0;
					$singletax = 0;
				//Quantity + Price
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
				//Place Discounted amount in array for Sub Total
					$AllPRDAmount[] = $multPriceQty;
					//Get Tax as per discount Amount
						$singletaxAmt = ($discountAmtDiduct * $singleProduct['tax_percentage'] ) / 100 ;
					
					//Push final amount in array 
						$finalAmount = $discountAmtDiduct + $singletaxAmt;
						$totalAmtArray[] = $finalAmount;
					//Single discount place in array for show
						$singleDiscount[] = $singleDiscountAmt;
					//Single tax place in array for show 
						$TaxtCalculationArray[] = $singletaxAmt;					
					?>
					<?php 
					//Create All Tax Included Array 
						if($allTaxInArry[$singleProduct['tax_percentage']])
						{
							$allTaxInArry[$singleProduct['tax_percentage']][] = $singletaxAmt;
						} else {
							$allTaxInArry[$singleProduct['tax_percentage']] = array();
							$allTaxInArry[$singleProduct['tax_percentage']][] = $singletaxAmt;
						}
					?>
					<tr>
						<td style="text-align: left"><?php if(isset($singleProduct['product_qty']) && $singleProduct['product_qty'] != ""){ echo  $singleProduct['product_qty']; }?></td>
						<td style="text-align: left">
							<?php if(isset($singleProduct['product_name']) && $singleProduct['product_name'] != ""){ echo $singleProduct['product_name']; }?>
							<?php if(isset($singleProduct['product_description']) && $singleProduct['product_description'] != ""){ echo '<br>'.substr($singleProduct['product_description'],20); }?>
						</td>
						<td style="text-align: right"><?php if(isset($prdSalesPrice) && $prdSalesPrice != ""){ echo $Symbol.amtRound($prdSalesPrice); }?></td>
						<td style="text-align: right"><?php if(isset($singleProduct['tax_percentage']) && $singleProduct['tax_percentage'] != ""){echo $singleProduct['tax_percentage'].' %'; }?></td>
						<td style="text-align: right">
							<?php echo $Symbol.amtRound($finalAmount); ?>

						</td>
					</tr>
				<?php }?>
				</tbody>
			</table>

	<table style="margin-top:8px; text-align: right; width: 100%; padding-top: 20px; font-size:20px; border-top: 1px solid #000;">
		<tbody>
		<?php
		$TotalAmount = "";	//Set Blank for Total amount
		$FinalCalculation = array();
		?>
		<?php
		if(!empty($AllPRDAmount) && count($AllPRDAmount) != 0){
			$TotalAmount = array_sum($AllPRDAmount);
			$FinalCalculation = 0;
			?>
			<tr>
				<td><?php echo lang('EST_PDF_TOTAL_EXC_TAX');?></td>
				<td><?php echo $Symbol;echo amtRound($TotalAmount);	?></td>
			</tr>
		<?php }?>
		<?php if(!empty($TaxtCalculationArray) && count($TaxtCalculationArray) != 0){?>
			<tr>
				<td><?php //echo $editRecord[0]['discount'].'%';?><?php echo lang('EST_TITLE_PRD_GPR_TAX'); ?>:  
					<?php
						ksort($allTaxInArry);
						foreach($allTaxInArry as $taxKey => $taxSingleWithValue){
							echo "<p>";
							echo $taxKey;
							echo "</p>";
						}
					?>
				</td>
				<td>
					<?php
						foreach($allTaxInArry as $taxKey => $taxSingleWithValue){
							echo "<p>";
							//echo $taxKey.'%'.' - ';
							echo $Symbol.amtRound(array_sum($taxSingleWithValue));
							echo "</p>";
						}
					?>
				</td>
			</tr>
		<?php }?>
		<tr>
			<td><?php echo lang('EST_PDF_TOTAL'); ?>:   </td>
			<td>
				<?php 
				//Final Total Price calculation
				if($editRecord[0]['discount_option'] == 'amt'){
					$singleDiscountAmt = $editRecord[0]['discount'];
					$afterDiscountOption = array_sum($totalAmtArray) - $singleDiscountAmt;
				} else {
					$afterDiscountOption = array_sum($totalAmtArray);
				} 
				?>
				<?php echo $Symbol.amtRound($afterDiscountOption); ?>
			</td>
		</tr>
		</tbody>
	</table>	