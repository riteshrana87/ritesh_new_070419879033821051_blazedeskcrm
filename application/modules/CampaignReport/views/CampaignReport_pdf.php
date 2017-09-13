<?php //pr($leads_by_marketing);
//pr($customer_by_source);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>Campaign Report</title>
<!-- BOOTSTRAP CORE STYLE  -->
<link href="<?php echo base_url(); ?>uploads/reports/assets/css/bootstrap.css" rel="stylesheet" />
<!-- CUSTOM STYLE  -->
<link href="<?php echo base_url(); ?>uploads/reports/assets/css/custom-style.css" rel="stylesheet" />
<!-- GOOGLE FONTS -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css' />

<!-- by brt-->

<link rel="stylesheet" href="<?php echo base_url(); ?>uploads/dist/css/bootstrap.min.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>uploads/dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>uploads/css/style.css">
<script src="<?php echo base_url(); ?>uploads/dist/js/jquery-2.1.4.min.js"> </script>
<script src="<?php echo base_url(); ?>uploads/assets/js/ie-emulation-modes-warning.js"> </script>
<script src="<?php echo base_url(); ?>uploads/dist/js/highchart.js"></script>
<script src="<?php echo base_url(); ?>uploads/dist/js/data.js"></script>
<script src="<?php echo base_url(); ?>uploads/dist/js/exporting.js"></script>
<script src="http://canvg.googlecode.com/svn/trunk/rgbcolor.js"></script>
<script src="http://canvg.googlecode.com/svn/trunk/canvg.js"></script>
<script src="<?php echo base_url(); ?>uploads/jsPDF/dist/jspdf.min.js"></script>

<link rel="stylesheet" href="<?php echo base_url(); ?>uploads/custom/css/development_css.css">

<!-- by brt ends-->

<style></style>
</head>

<body style="font-family:Arial,verdana, tahoma;font-size:12px;line-height:20px">
<div id="pdf_header"> 
<table cellpadding="0" cellspacing="0" border="0" width="100%"  style="margin-top:10px;text-align:left;">
	<tbody>
		<tr>
			<td width="70%" valign="top"><img src="<?php  echo base_url();?>uploads/images/logo.png" style="padding-bottom:20px; float:left;" alt="logo"/></td>
			<td width="30%" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
					<tbody>
						<tr>
							<td style="font-size:20px;font-weight:bold;text-align:left;padding:10px 0;">Campaign Report</td>
						</tr>
						<tr>
							<td style="font-size:20px;font-weight:bold;text-align:left;padding:10px 0;"><?php echo date('Y-m-d'); ?></td>
						</tr>
						
					</tbody>
				</table>
				</td>
		</tr>
</table>
</div>	
<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> Graph
						<div class="col-md-12 col-md-12">
								<div id="container" > ></div>
						</div>
				</td>
		</tr>
	</tbody>
</table>
<button id="export_all">export all</button>
<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> Graph
						<div class="col-md-12 col-md-12">
								<div id="container_market" class="myChart"></div>
						</div>
				</td>
		</tr>
	</tbody>
</table>

<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> Graph
						<div class="col-md-12 col-md-12">
								<div id="container_source" class="myChart"></div>
						</div>
				</td>
		</tr>
	</tbody>
</table>

<script type="text/javascript"> 

// create canvas function from highcharts example http://jsfiddle.net/highcharts/PDnmQ/
(function (H) {
    H.Chart.prototype.createCanvas = function (divId) {
        var svg = this.getSVG(),
            width = parseInt(svg.match(/width="([0-9]+)"/)[1]),
            height = parseInt(svg.match(/height="([0-9]+)"/)[1]),
            canvas = document.createElement('canvas');

        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);

        if (canvas.getContext && canvas.getContext('2d')) {

            canvg(canvas, svg);

            return canvas.toDataURL("image/jpeg");

        } 
        else {
            var delete_meg ="<?php echo lang('upgrade_your_browser');?>";
			BootstrapDialog.show(
				{
					title: '<?php echo $this->lang->line('Information');?>',
					message: delete_meg,
					buttons: [{
						label: '<?php echo $this->lang->line('ok');?>',
						action: function(dialog) {
							dialog.close();
						}
					}]
				});
            return false;
        }

    }
}(Highcharts));

//$('#export_all').click(function () {
	$( document ).ready(function () {


    var doc = new jsPDF();
    
    // chart height defined here so each chart can be palced
    // in a different position
    var chartHeight = 120;
    
    // All units are in the set measurement for the document
    // This can be changed to "pt" (points), "mm" (Default), "cm", "in"
    doc.setFontSize(30);
    doc.text(35, 25, "Campaign Report");
    
    //loop through each chart
   
  var pdf_header= $('#pdf_header').html();



    $('.myChart').each(function (index) {
        var imageData = $(this).highcharts().createCanvas();
        
        // add image to doc, if you have lots of charts,
        // you will need to check if you have gone bigger 
        // than a page and do doc.addPage() before adding 
        // another image.
        
        /**
        * addImage(imagedata, type, x, y, width, height)
        */
 var imgTag='<img src="'+imageData+'" width="600"/>';

 //$( ".myChart" ).html(imgTag);

var nodes = document.getElementsByClassName('myChart');
 nodes[index].innerHTML= '';
    nodes[index].innerHTML= imgTag;
 //alert(index);

       // doc.addImage(imageData, 'JPEG', 5, (index * chartHeight) + 40, 200, chartHeight);
    });
    


    //save with name
    //doc.save('Campaign_Report.pdf');
});


//charts


// 			 $(function () {

				$('#container_market').highcharts({
									chart: {
											type: 'column'
									},
									title: {
											text: 'Leads Generated by Marketing'
									},
									subtitle: {
											text: 'Leads Generated by Source'
									},
									xAxis: {
											categories: [<?php echo $leads_by_marketing['categories'];?>]
									},
									yAxis: {
											min: 0,
											title: {
													text: 'Monthly Leads Generated by Marketing'
											},
											stackLabels: {
													enabled: true,
													style: {
															//fontSize: '13px',
															fontWeight: 'bold',
															color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
													}
											}
									},
									legend: {
											//  align: 'right',
											// x: -30,
											// verticalAlign: 'top',
											// y: 65,
											// floating: true,
											// backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
											// borderColor: '#CCC',
											// borderWidth: 1,
											shadow: false
											//shadow: false
									},
									tooltip: {
											headerFormat: '<b>{point.x}</b><br/>',
											pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
									},
									plotOptions: {
											column: {
													stacking: 'normal',
													dataLabels: {
															enabled: true,
															color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
															style: {
																	textShadow: '0 0 3px black'
															}
													}
											}
									},
									
									series: [  <?php foreach ($leads_by_marketing['data'] as $key => $campaign) {
										
											echo " {";
											echo " name: "."'".$campaign['campaign_name']."',";
											echo "  data: [".implode(",", $campaign['monthly_count'])."]";
											echo " }, ";
										}?>]
				
				});

		 			$('#container_source').highcharts({
									chart: {
											type: 'column'
									},
									title: {
											text: 'New Customers by Source'
									},
									subtitle: {
											text: 'Marketing-Generated Customers by Source'
									},
									xAxis: {
											categories: [<?php echo $customer_by_source['categories'];?>]
									},
									yAxis: {
											min: 0,
											title: {
													text: 'Monthly New Customers by Source'
											},
											stackLabels: {
													enabled: true,
													style: {
															//fontSize: '13px',
															fontWeight: 'bold',
															color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
													}
											}
									},
									legend: {
											//  align: 'right',
											// x: -30,
											// verticalAlign: 'top',
											// y: 65,
											// floating: true,
											// backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
											// borderColor: '#CCC',
											// borderWidth: 1,
											shadow: false
											//shadow: false
									},
									tooltip: {
											headerFormat: '<b>{point.x}</b><br/>',
											pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
									},
									plotOptions: {
											column: {
													stacking: 'normal',
													dataLabels: {
															enabled: true,
															color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
															style: {
																	textShadow: '0 0 3px black'
															}
													}
											}
									},
									
									

									series: [  <?php foreach ($customer_by_source['data'] as $key => $campaign) {
										
									    echo " {";
									    echo " name: "."'".$campaign['campaign_name']."',";
									    echo "  data: [".implode(",", $campaign['monthly_count'])."]";
									    echo " }, ";
									  }?>]
		
				});




	//});

setTimeout( 5000);

</script>

</body>
</html>