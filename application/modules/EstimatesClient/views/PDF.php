<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"" />
<meta name="description" content="" />
<meta name="author" content="" />
<!--[if IE]>
            <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
            <![endif]-->
<title>Campaign Report</title>
<!-- BOOTSTRAP CORE STYLE  -->
<link href="<?php echo base_url(); ?>uploads/reports/assets/css/bootstrap.css" rel="stylesheet" />
<!-- CUSTOM STYLE  -->
<link href="<?php echo base_url(); ?>uploads/reports/assets/css/custom-style.css" rel="stylesheet" />
<!-- GOOGLE FONTS -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css' />
<style></style>
</head>

<body style="font-family:Arial,verdana, tahoma;font-size:12px;line-height:20px">
   <?php 
if (isset($editRecord) && !empty($editRecord)) { ?>
	<div class="white-border-ox" id="previewBox">
	  <div class="pad-10">
		<div class="row" id="headerContainer">
		  <div class="col-xs-12 col-sm-6 col-md-7">
			<div class="font-15em"><b><?php echo $editRecord[0]['estimate_auto_id']; ?></b></div>
			<div class="">
				Client
			</div>
			<div class="">Address</div>
			<div class="row">
			  <div class="col-xs-6 col-sm-6 col-md-6"> City </div>
			  <div class="col-xs-6 col-sm-6 col-md-6"> Country </div>
			  <div class="clr"></div>
			</div>
		  </div>
		  <div class="col-xs-12 col-sm-6 col-md-5"> <!-- pull-right text-right -->
			<div>
				<?php if(isset($PreviewCompanyLogo[0]['value']) && $PreviewCompanyLogo[0]['value'] != ""){?>
					<img src="<?php echo base_url().COMPANY_PROFILE_PIC_UPLOAD_PATH."/".$PreviewCompanyLogo[0]['value']; ?>" alt="" />
				<?php }?>
			</div>
			<br/>
			<?php if(isset($PreviewClientInformation['name']) && $PreviewClientInformation['name'] != ""){?>
				<div class=""><?php echo $PreviewClientInformation['name']; ?></div>
			<?php }?>
			<div class="">
				<?php if(isset($PreviewClientInformation['address1']) && $PreviewClientInformation['address1'] != ""){?>
					<?php echo $PreviewClientInformation['address1']; ?>
				<?php }?>
			</div>
			<div class="">
				<?php if(isset($PreviewClientInformation['address2']) && $PreviewClientInformation['address2'] != ""){?>
					<?php echo $PreviewClientInformation['address2']; ?>
				<?php }?>
				<?php if(isset($PreviewClientInformation['postal_code']) && $PreviewClientInformation['postal_code'] != ""){?>
					<?php echo ','.$PreviewClientInformation['postal_code']; ?>
				<?php }?>
			</div>
			<div class="">
				<?php if(isset($PreviewClientInformation['city']) && $PreviewClientInformation['city'] != ""){?>
					<?php echo $PreviewClientInformation['city']; ?>
				<?php }?>
				<?php if(isset($PreviewClientInformation['state']) && $PreviewClientInformation['state'] != ""){?>
					<?php echo ' ,'.$PreviewClientInformation['state']; ?>
				<?php }?>
			</div>
		  </div>
		  <div class="clr"></div>
		</div>
		<div class="grayline-1"></div>
		<?php if(isset($editRecord[0]['est_content']) && $editRecord[0]['est_content'] != "") {?>
		<div class="parentSortable">
			<div class="white-border-box mar_b6">
			  <div class="pad-6">
				<div class=""><b>Introduction Text</b></div>
					<div>
						<?php 	echo $editRecord[0]['est_content']; ?>
					</div>
			  </div>
			</div>
			<div class="clr"></div>
		</div>
		<?php }?>
		
		
		<div id="tableRowContainer">
			<?php 
		//All Product amount get in array For Calculation
			$AllProductAmount = array();
			$TaxtCalculationArray = array();
			?>
		<?php foreach($previewAllProduct as $singleProduct){ ?>
			<?php $AllProductAmount[] = $singleProduct['product_amt'];?>
			<?php 
			//Tax Calculation push in array
				if(isset($singleProduct['tax_percentage']) && $singleProduct['tax_percentage'] != "")
				{
					$multiplication = $singleProduct['sales_price_unit'] * $singleProduct['product_qty'];
					$TaxtCalculationArray[] = ($multiplication * $singleProduct['tax_percentage'] ) / 100 ;
				}
			?>
			<div>
				<div class="row tableRowSortable" id="<?php echo $singleProduct['est_prd_id'];?>">
				  <div class="col-md-2">
					<input type="text" class="form-control" placeholder="product name" value="<?php echo $singleProduct['product_name'];?>" />
				  </div>
				  <div class="col-md-4">
					<input type="text" class="form-control" placeholder="description" value="<?php echo $singleProduct['product_description'];?>" />
				  </div>
				  <div class="col-md-2">
					<input type="text" class="form-control" placeholder="Amount" value="<?php echo $singleProduct['product_qty'];?>" />
				  </div>
				  <div class="col-md-2">
					<input type="text" class="form-control" placeholder="Amount" value="<?php echo $singleProduct['sales_price_unit'];?>" />
				  </div>
				  <div class="col-md-2">
					<select class="form-control">
					  <option></option>
					</select>
				  </div>
				  <div class="clr mar_b6"></div>
				</div>
			<div class="clr"></div>
			</div>
		<?php }?>
		</div>
		<div class="row">
		  <div class="col-xs-12 col-md-6 col-md-offset-6 ">
			<div class="form-group">
			  <div class="col-xs-12 col-md-6">
				<p class="pad-6" > Sub Total : </p>
			  </div>
			  <div class="col-xs-12 col-md-6">
				<?php if(!empty($AllProductAmount) && count($AllProductAmount) > 0){ $PrdAmountSum =  array_sum($AllProductAmount); } else{	$PrdAmountSum = "0";	}?>
				<input class="form-control" placeholder="00.00" value="<?php echo $PrdAmountSum;?>" type="text" readonly />
				<div class="clr"> </div>
			  </div>
			  <div class="clr"> </div>
			  <div class="form-group">
				<div class="col-xs-12 col-md-6">
				  <p class="pad-6" > Taxes : </p>
				</div>
				<div class="col-xs-12 col-md-6">
					<?php if(isset($TaxtCalculationArray) && count($TaxtCalculationArray) > 0){
						$TaxSum = array_sum($TaxtCalculationArray);
					}else {	$TaxSum = "0";	}?>
				 <input class="form-control" placeholder="0.00" type="text" value ="<?php echo $TaxSum; ?>" readonly/>
				  <div class="clr"> </div>
				</div>
				<div class="clr"> </div>
				<div class="col-xs-12 col-md-6">
				  <p class="pad-6" > Discount : </p>
				</div>
				<div class="col-xs-12 col-md-6">
				  <input class="form-control" placeholder="0.00" type="text" value="<?php if(isset($editRecord[0]['discount']) && $editRecord[0]['discount'] != ""){ echo $editRecord[0]['discount'];}?>" readonly />
				  <div class="clr"> </div>
				</div>
				<div class="clr"> </div>
				<div class="grayline-1"></div>
				<div class="clr"> </div>
				<div class="form-group">
				  <div class="col-xs-12 col-md-6">
					<p class="pad-6" > Total : </p>
				  </div>
				  <div class="col-xs-12 col-md-6">
					<?php 
					//Final Total Price calculation
						$TaxSubtotalSum = $TaxSum + $PrdAmountSum;
						if(isset($editRecord[0]['discount']) && $editRecord[0]['discount'] != ""){
							$DisountCalculate =($TaxSubtotalSum * $editRecord[0]['discount']) / 100;
							$FinalSum = $TaxSubtotalSum - $DisountCalculate;
						}else{
							$FinalSum = $TaxSubtotalSum ;
						}
					?>
					<input class="form-control" placeholder="00.00" type="text" value="<?php echo $FinalSum;?>" readonly />
					<div class="clr"> </div>
				  </div>
				</div>
				<div class="clr"> </div>
			  </div>
			  <div class="clr"> </div>
			</div>
		  </div>
		</div>
	   
		<div class="clr mar_b6"></div>
		<?php if(isset($editRecord[0]['est_userdescription_status']) && $editRecord[0]['est_userdescription_status'] == 1){?>
		<div class="parentSortable">
			<div class="white-border-box mar_b6">
			  <div class="pad-6">
				<div class=""><b>Description</b></div>
				<div>
					<?php 
						if(isset($editRecord[0]['est_userdescription']) && $editRecord[0]['est_userdescription'] != ""){
							echo $editRecord[0]['est_userdescription'];
							}
					?>
				</div>
			  </div>
			</div>
			<div class="clr"></div>
		</div>
		<?php }?>
		<?php //Start Multiple paragraph code?>
		<?php 
		$previewPara = (array)json_decode($editRecord[0]['text_paragraph']);
		if (!empty($previewPara) && count($previewPara['text_paragraph']) > 0) { 
			foreach($previewPara['text_paragraph'] as $paragraphInfo){ ?>
				<div class="parentSortable">
					<div class="clr mar_b6"></div>
					<div class="white-border-box mar_b6">
					  <div class="pad-6">
						<div class=""><b>Paragraph</b></div>
						<div>
							<?php echo $paragraphInfo; ?>
						</div>
					  </div>
					</div>
				</div>
			<?php }?>
		<?php }?>
		
		<?php if(isset($editRecord[0]['est_termcondition']) && $editRecord[0]['est_termcondition'] != ""){?>
		<div class="parentSortable">
			<div class="white-border-box mar_b6">
			  <div class="pad-6">
				<div class=""><b>Terms and Conditions </b></div>
				<div>
					<?php echo $editRecord[0]['est_termcondition'];?>
				</div>
			  </div>
			</div>
			<div class="clr "></div>
		</div>
		<?php }?>
		<div class="row">
		  <div class="col-xs-12 col-sm-6 col-md-6">
			<div class="">Date</div>
			<div class="">Location</div>
		  </div>
		  <div class="col-xs-12 col-sm-6 col-md-6">
			<div class="">Date : <?php echo datetimeformat();?></div>
			<div class="">Location : 
				<?php if(isset($PreviewClientInformation['city']) && $PreviewClientInformation['city'] != ""){?>
					<?php echo $PreviewClientInformation['city']; ?>
				<?php }?>
				<?php if(isset($PreviewClientInformation['state']) && $PreviewClientInformation['state'] != ""){?>
					<?php echo ' ,'.$PreviewClientInformation['state']; ?>
				<?php }?>
			</div>
		  </div>
		  <div class="clr"></div>
			<div>
				<?php if ($editRecord[0]['signature'] != null) { ?>
					<img class="show" src="<?php echo $editRecord[0]['signature']; ?>" height="100px" width="100px">
				<?php }else{	echo "<br><br><br><br>";	} ?>
			</div>
		<div class="clr"></div>
		  <div class="col-xs-12 col-sm-6 col-md-6">
			<div class="">Name</div>
		  </div>
		  <div class="col-xs-12 col-sm-6 col-md-6">
			<?php if(isset($PreviewLoginInfo) && $PreviewLoginInfo != ""){?>
				<div class="">Name : <?php echo $PreviewLoginInfo;?></div>
			<?php }?>
		  </div>
		</div>
	  </div>
	</div>
            
<?php  }?>

</body>
</html>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script>
    $(function () {
    $('#container').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Stacked bar chart'
        },
        xAxis: {
            categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total fruit consumption'
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
        series: [{
            name: 'John',
            data: [5, 3, 4, 7, 2]
        }, {
            name: 'Jane',
            data: [2, 2, 3, 2, 1]
        }, {
            name: 'Joe',
            data: [3, 4, 4, 2, 5]
        }]
    });
});
</script>