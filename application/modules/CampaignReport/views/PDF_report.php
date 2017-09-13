<!-- by brt-->
<link rel="stylesheet" href="<?php echo base_url(); ?>uploads/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>uploads/dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>uploads/css/style.css">
<style type="text/css">
	body{
		background-color: #fff !important;
	}

</style>
<!-- by brt ends-->
<div style="font-family:Arial,verdana, tahoma;font-size:12px;line-height:20px;background-color: #fff;">
<br><br><br><br><br><br><br><br>
<div id="pdf_header"> 

<table cellpadding="0" cellspacing="0" border="0" style="border-color:#ccc;text-align:center;width: 100%;" bordercolor="#cccccc">
		<tbody>
		<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h1>Monthly Marketing Report</h1> </td>
			</tr>
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h2>Blazedesk<h2></td>
			</tr>
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h2><?php echo $user_data['FIRSTNAME']." ".$user_data['LASTNAME']?></h2></td>
			</tr>
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h3><?php echo date('Y-m-d'); ?></h3></td>
			</tr>
			
		</tbody>
</table>


</div>

<div class="clr"></div>
<?php
$this->m_pdf->pdf->SetAutoPageBreak();?>
<pagebreak />


<!--   table of content code -->

<br><br><br><br><br><br><br><br>
<div id="pdf_header"> 

<table cellpadding="0" cellspacing="0" border="0" style="border-color:#ccc;width: 100%;" bordercolor="#cccccc">
		<tbody>
		<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;text-align:center;"><h1>Table of Contents</h1> </td>
			</tr>
			<br><br><br><br>
			
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h4>Monthly Highlights Template .............................................................  3
</h4></td>
			</tr>
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h4>Monthly Trends Graphs ......................................................................  5

</h4></td>
			</tr>
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h4>Top Campaigns Slide ..........................................................................  10
</h4></td>
			</tr>
			
		</tbody>
</table>


</div>

<div class="clr"></div>
<?php
$this->m_pdf->pdf->SetAutoPageBreak();?>
<pagebreak />

<br><br><br><br>

<!--   table of content code ends -->


<!--   month Highlight code -->



<br><br><br><br><br><br><br><br>
<div class="pdf_header"> 

<table cellpadding="0" cellspacing="0" border="0" style="border-color:#ccc;width: 100%;" bordercolor="#cccccc">
		<tbody>
		<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;text-align:center;"><h1><?php echo date('F')?> Highlights</h1> </td>
			</tr>
			<br><br><br><br>
			
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h4>Total Marketing reach grew <?php echo round($percentageReachGrew);?>%
</h4></td>
			</tr>
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h4>Overall website visits up <?php echo round($websiteVisit);?>% 

</h4><h4><span>  - Organic search traffic up <?php echo round($organicSearches);?>%</span></h4>
</td>
			</tr>
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h4>Generated <?php echo $newLeadsByMonthCount?> new leads, up <?php echo $newLeadsByMonth?>% 
</h4></td>
			</tr>
			
			<tr>
				<td style="font-size:20px;font-weight:bold;padding:10px 0;"><h4>Marketing brought in <?php echo $newcustomerByMonthCount?> customers, up <?php echo round($newcustomerByMonth)?>% 
</h4> <h4><span><br>  - <?php echo round($percentageLostCustomer);?>% of all customers closed </span></h4></td>
			</tr>
		</tbody>
</table>


</div>

<div class="clr"></div>
<?php
$this->m_pdf->pdf->SetAutoPageBreak();?>
<pagebreak />

<br><br><br><br>

<!--   month Highlight code ends -->

<?php //$this->m_pdf->pdf->SetAutoPageBreak();
$operationSystem='';
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $commandEXEC='shell_exec';
    $operationSystem="WIN";
} else {
   $commandEXEC='exec';
   $operationSystem="Linux";
}

 $dirPath=$this->config->item('directory_root')."uploads/mediagenerated/";
 $outputPath=  $this->config->item('directory_root')."uploads/phantomjs";
?>
<!-- marketing reach-->

<?php $dd='';
$high=array();
//echo (string) $chartData;



        $high['chart']['type'] = "column";
        $high['credits']['enabled'] = false;
		$high['title']['text'] = "Marketing Reach";
		$high['subtitle']['text'] = "Marketing Reach by Channel";
        $high['xAxis']['categories'] = array_values($marketingReach['categoriesArray']);
        $high['yAxis']['title']['text'] = "Monthly Marketing Reach by Channel";
        $high['yAxis']['title']['stackLabels']['enabled'] =true;
        $high['yAxis']['title']['stackLabels']['style']['fontWeight'] ='bold';
        $high['yAxis']['title']['stackLabels']['style']['color'] ="(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";

		$high['legend']['shadow'] = false;

		$high['tooltip']['headerFormat']=	'<b>{point.x}</b><br/>';	
		$high['tooltip']['pointFormat']=	'{series.name}: {point.y}<br/>Total: {point.stackTotal}';
		$high['plotOptions']['column']['stacking']='normal';
		$high['plotOptions']['column']['dataLabels']['enabled']=true;
		$high['plotOptions']['column']['dataLabels']['color']="white";

		$high['plotOptions']['column']['dataLabels']['style']['textShadow']='0 0px 0px white';


		foreach ($marketingReach['data'] as $key => $campaign) {
			
			$tempArray=array();
				 foreach ($campaign['monthly_count'] as $key => $monthVal) {

					$tempArray[]=(int)$monthVal;
				 	
				 }
  						
			$high['series'][] = array('name' => ucfirst($campaign['campaign_name']),'data' => $tempArray);
			
			
			}
        
          
           $chartName="marketingReach";        
               // echo json_encode($high);exit;
            

           
		$myfile = fopen($dirPath."/inrep_$chartName.json", "w") or die("Unable to open file!");
		$txt = json_encode($high);
		fwrite($myfile, $txt);
		fclose($myfile);



		unlink($outputPath."/inrep_$chartName.png");

		if($operationSystem =='WIN'){

		$command = "C:/phantomjs/bin/phantomjs.exe  $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}else{

       $command = "/usr/local/bin/phantomjs   $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}


 	 $customerChart =	$commandEXEC($command); 
 
  
?>
<!-- <pagebreak /> -->
<br><br><br><br>
<div style="margin-bottom:50px !important;">
<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> 
						<div class="col-md-12 col-md-12">
						<img src='<?php echo base_url()."uploads/phantomjs/inrep_".$chartName.'.png' ?>'/>
							
						</div>
				</td>
		</tr>
	</tbody>
</table>
</div>
<div class="clr"></div>






<!-- marketing reach ends-->

<?php 


// this code for google analytics graph image



$dd='';
$high=array();
//echo (string) $chartData;



        $high['chart']['type'] = "column";
        $high['credits']['enabled'] = false;
		$high['title']['text'] = "Website Visits";
		$high['subtitle']['text'] = "Website Visits by Source";
        $high['xAxis']['categories'] = array_values($page_visit_by_source['categoriesArray']);
        $high['yAxis']['title']['text'] = "Monthly Website Visits by Source";
        $high['yAxis']['title']['stackLabels']['enabled'] =true;
        $high['yAxis']['title']['stackLabels']['style']['fontWeight'] ='bold';
        $high['yAxis']['title']['stackLabels']['style']['color'] ="(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";

		$high['legend']['shadow'] = false;

		$high['tooltip']['headerFormat']=	'<b>{point.x}</b><br/>';	
		$high['tooltip']['pointFormat']=	'{series.name}: {point.y}<br/>Total: {point.stackTotal}';
		$high['plotOptions']['column']['stacking']='normal';
		$high['plotOptions']['column']['dataLabels']['enabled']=true;
		$high['plotOptions']['column']['dataLabels']['color']="white";

		$high['plotOptions']['column']['dataLabels']['style']['textShadow']='0 0px 0px white';


		foreach ($page_visit_by_source['data'] as $key => $campaign) {
			
			$tempArray=array();
				 foreach ($campaign['monthly_count'] as $key => $monthVal) {

					$tempArray[]=(int)$monthVal;
				 	
				 }

			$high['series'][] = array('name' => $campaign['campaign_name'],'data' => $tempArray);
			
			
			}
        
          
           $chartName="page_visit_by_source";        
               // echo json_encode($high);exit;
            

           
		$myfile = fopen($dirPath."/inrep_$chartName.json", "w") or die("Unable to open file!");
		$txt = json_encode($high);
		fwrite($myfile, $txt);
		fclose($myfile);



		unlink($outputPath."/inrep_$chartName.png");

		if($operationSystem =='WIN'){

		$command = "C:/phantomjs/bin/phantomjs.exe  $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}else{

       $command = "/usr/local/bin/phantomjs   $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}


 	 $customerChart =	$commandEXEC($command); 
 
  
?>
<pagebreak />
<br><br><br><br>
<div style="margin-bottom:50px !important;">
<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> 
						<div class="col-md-12 col-md-12">
						<img src='<?php echo base_url()."uploads/phantomjs/inrep_".$chartName.'.png' ?>'/>
							
						</div>
				</td>
		</tr>
	</tbody>
</table>
</div>
<div class="clr"></div>



<?php
$this->m_pdf->pdf->SetAutoPageBreak();

// This code for generationg image for  Leads Generated by Marketing

	$dd='';
        $high=array();
        $high['chart']['type'] = "column";
        $high['credits']['enabled'] = false;
		$high['title']['text'] = "Leads Generated by Marketing";
		$high['subtitle']['text'] = "Leads Generated by Source";
        $high['xAxis']['categories'] = $leads_by_marketing['categoriesArray'];
        $high['yAxis']['title']['text'] = "Monthly Leads Generated by Marketing";
        $high['yAxis']['title']['stackLabels']['enabled'] =true;
        $high['yAxis']['title']['stackLabels']['style']['fontWeight'] ='bold';
        $high['yAxis']['title']['stackLabels']['style']['color'] ="(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";

		$high['legend']['shadow'] = false;

		$high['tooltip']['headerFormat']=	'<b>{point.x}</b><br/>';	
		$high['tooltip']['pointFormat']=	'{series.name}: {point.y}<br/>Total: {point.stackTotal}';
		$high['plotOptions']['column']['stacking']='normal';
		$high['plotOptions']['column']['dataLabels']['enabled']=true;
		$high['plotOptions']['column']['dataLabels']['color']="white";

		$high['plotOptions']['column']['dataLabels']['style']['textShadow']='0 0px 0px white';


		foreach ($leads_by_marketing['data'] as $key => $campaign) {
			
			$tempArray=array();
				 foreach ($campaign['monthly_count'] as $key => $monthVal) {

					$tempArray[]=(int)$monthVal;
				 	
				 }
  						
			$high['series'][] = array('name' => $campaign['campaign_name'],'data' => $tempArray);
			
			
			}

           $chartName="leads_by_marketing";        
                        
        // this code is to wrigt data in json file
 
		$myfile = fopen($dirPath."/inrep_$chartName.json", "w") or die("Unable to open file!");
		$txt = json_encode($high);
		fwrite($myfile, $txt);
		fclose($myfile);



		unlink($outputPath."/inrep_$chartName.png");

		if($operationSystem=='WIN'){

		$command = "C:/phantomjs/bin/phantomjs.exe  $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 3.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}else{

       $command = "/usr/local/bin/phantomjs   $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 3.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}

 	 $marketChart =	$commandEXEC($command); 
 
  
?>
<pagebreak>
<br><br><br><br>

<div style="margin-bottom:50px !important;">
<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> 
						<div class="col-md-12 col-md-12">
						<img src='<?php echo base_url()."uploads/phantomjs/inrep_".$chartName.'.png' ?>'/>
						
								<!-- <div id="container_market" class="myChart"></div> -->
						</div>
				</td>
		</tr>
	</tbody>
</table>

</div>
<div class="clr"></div>

<?php 
$this->m_pdf->pdf->SetAutoPageBreak();
// This code for generationg image for  New Customers by Source

$dd='';
$high=array();
//echo (string) $chartData;
//pr( $leads_by_marketing['categoriesArray']);


        $high['chart']['type'] = "column";
        $high['credits']['enabled'] = false;
		$high['title']['text'] = "New Customers by Source";
		$high['subtitle']['text'] = "Marketing-Generated Customers by Source";
        $high['xAxis']['categories'] = $customer_by_source['categoriesArray'];
        $high['yAxis']['title']['text'] = "Monthly New Customers by Source";
        $high['yAxis']['title']['stackLabels']['enabled'] =true;
        $high['yAxis']['title']['stackLabels']['style']['fontWeight'] ='bold';
        $high['yAxis']['title']['stackLabels']['style']['color'] ="(Highcharts.theme && Highcharts.theme.textColor) || 'gray'";

		$high['legend']['shadow'] = false;

		$high['tooltip']['headerFormat']=	'<b>{point.x}</b><br/>';	
		$high['tooltip']['pointFormat']=	'{series.name}: {point.y}<br/>Total: {point.stackTotal}';
		$high['plotOptions']['column']['stacking']='normal';
		$high['plotOptions']['column']['dataLabels']['enabled']=true;
		$high['plotOptions']['column']['dataLabels']['color']="white";

		$high['plotOptions']['column']['dataLabels']['style']['textShadow']='0 0px 0px white';


		foreach ($customer_by_source['data'] as $key => $campaign) {
			
			$tempArray=array();
				 foreach ($campaign['monthly_count'] as $key => $monthVal) {

					$tempArray[]=(int)$monthVal;
				 	
				 }
  						
			$high['series'][] = array('name' => $campaign['campaign_name'],'data' => $tempArray);
			
			
			}
        
          
           $chartName="customer_by_source";        
              
            

           
		$myfile = fopen($dirPath."/inrep_$chartName.json", "w") or die("Unable to open file!");
		$txt = json_encode($high);
		fwrite($myfile, $txt);
		fclose($myfile);



		unlink($outputPath."/inrep_$chartName.png");

		if($operationSystem =='WIN'){

		$command = "C:/phantomjs/bin/phantomjs.exe  $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}else{

       $command = "/usr/local/bin/phantomjs   $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}


 	 $customerChart =	$commandEXEC($command); 
 
  
?>
<pagebreak />
<br><br><br><br>
<div style="margin-bottom:50px !important;">
<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> 
						<div class="col-md-12 col-md-12">
						<img src='<?php echo base_url()."uploads/phantomjs/inrep_".$chartName.'.png' ?>'/>
							
						</div>
				</td>
		</tr>
	</tbody>
</table>
</div>
<div class="clr"></div>


<?php 
// This code for generationg image for  Customers Sourced by Marketing

/*
  xAxis: {
            categories: [ 'Mar', 'Apr', 'May', 'Jun' ]
        },
        yAxis: {
         min: 0, max: 100, tickInterval: 20,
            title: {
                text: 'Temperature (°C)'
            },
            
        },plotOptions: {
            series: {
            
                stacking: 'percent'
            }
        },
        
      
        series: [  {
            name: 'Delhi',
            data: [40,60,45,35]
           
        },{
            name: 'London',
            data: [60,40,55,65]
        }]
*/

//pr($customer_by_source);
$dd='';
$high=array();


        $high['chart']['type'] = "line";
        $high['credits']['enabled'] = false;
		$high['title']['text'] = "Customers Sourced by Marketing";
		$high['subtitle']['text'] = "% Customers Generated by Marketing";
        $high['xAxis']['categories'] = $customer_source_by_marketing['categoriesArray'];

         $high['yAxis']['min']="0";
         $high['yAxis']['max']="100";
         $high['yAxis']['tickInterval']="20";

        $high['yAxis']['title']['text'] = "% Customer by Marketing";
        
		$high['legend']['shadow'] = false;



		foreach ($customer_source_by_marketing['data'] as $key => $campaign) {
			
			$tempArray=array();
				 foreach ($campaign['monthly_count'] as $key => $monthVal) {

					$tempArray[]=(int)$monthVal;
				 	
				 }
  						
			$high['series'][] = array('name' => $campaign['campaign_name'],'data' => $tempArray);
			
			
			}
       
	           
          
           $chartName="customer_source_by_marketing";        
               // echo json_encode($high);exit;
            


		$myfile = fopen($dirPath."/inrep_$chartName.json", "w") or die("Unable to open file!");
		$txt = json_encode($high);
		fwrite($myfile, $txt);
		fclose($myfile);



		unlink($outputPath."/inrep_$chartName.png");

		if($operationSystem=='WIN'){

		$command = "C:/phantomjs/bin/phantomjs.exe  $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}else{

       $command = "/usr/local/bin/phantomjs   $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}


 	 $customerChart =	$commandEXEC($command); 
 	 
?>
<pagebreak />
<br><br><br><br>
<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> 
						<div class="col-md-12 col-md-12">
						<img src='<?php echo base_url()."uploads/phantomjs/inrep_".$chartName.'.png' ?>'/>
							
						</div>
				</td>
		</tr>
	</tbody>
</table>

<br><br>

<!-- Leads to customer performance-->


<div class="clr"></div>


<?php 
// This code for generationg image for  Leads to customer performance


$dd='';
$high=array();



        $high['chart']['type'] = "line";
        $high['credits']['enabled'] = false;
		$high['title']['text'] = "Lead to Customer Performance";
		$high['subtitle']['text'] = "Lead-to-Customer  % ";
        $high['xAxis']['categories'] = $lead_to_customer_performance['categoriesArray'];

         $high['yAxis']['min']="0";
         $high['yAxis']['max']="100";
         $high['yAxis']['tickInterval']="20";


        //$high['plotOptions']['series']['stacking']='percent';
        $high['yAxis']['title']['text'] = "% Lead to Customer Performance";
       	$high['legend']['shadow'] = false;



		foreach ($lead_to_customer_performance['data'] as $key => $campaign) {
			
			$tempArray=array();
				 foreach ($campaign['monthly_count'] as $key => $monthVal) {

					$tempArray[]=(int)$monthVal;
				 	
				 }
  						
			$high['series'][] = array('name' => $campaign['campaign_name'],'data' => $tempArray);
			
			
			}
       
	           
          
           $chartName="lead_to_customer_performance";        
             
            


		$myfile = fopen($dirPath."/inrep_$chartName.json", "w") or die("Unable to open file!");
		$txt = json_encode($high);
		fwrite($myfile, $txt);
		fclose($myfile);



		unlink($outputPath."/inrep_$chartName.png");

		if($operationSystem=='WIN'){

		$command = "C:/phantomjs/bin/phantomjs.exe  $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}else{

       $command = "/usr/local/bin/phantomjs   $outputPath/highcharts-convert.js -infile ".$dirPath."/inrep_$chartName.json -outfile ".$outputPath."/inrep_$chartName.png -scale 2.5 -width 700 -constr Chart -callback $outputPath/callback.js 2>&1";
		}


 	 $customerChart =	$commandEXEC($command); 
 	 
?>

<pagebreak />
<br><br><br><br>
<table cellpadding="10" cellspacing="10" border="0" width="100%" style="margin-top:10px;">
	<tbody>
		<tr>
			<td><span style="font-family:Arial,verdana, tahoma;font-size:12px;"><?php //echo $campaign_report_data->campaign_description; ?></span></td>
		</tr>
		<tr style="margin:10px 0;">
				<td> 
						<div class="col-md-12 col-md-12">
						<img src='<?php echo base_url()."uploads/phantomjs/inrep_".$chartName.'.png' ?>'/>
							
						</div>
				</td>
		</tr>
	</tbody>
</table>

<br><br>

<!-- Top marketing Campaign-->
<div class="clr"></div>
<?php
$this->m_pdf->pdf->SetAutoPageBreak();?>
<pagebreak />


<!--   table of content code -->

<br><br><br><br><br><br><br><br>
<div id="pdf_header"> 

<table cellpadding="0" cellspacing="0" border="0" style="border-color:#ccc;width: 100%;" bordercolor="#cccccc">
	<tbody>
		<tr>
			<td style="font-size:20px;font-weight:bold;padding:10px 0;text-align:center;"><h1>Top Marketing Campaigns </h1> </td>
		</tr>
			<br><br><br><br>
			<?php foreach ($topMarketingCampaign as $key => $topCampaign) {	 ?>
				<tr>
					<td style="font-size:20px;font-weight:bold;padding:10px 0;">
						<h4><?php echo ucfirst(str_replace(' ', '', $topCampaign->campaign_name)); ?>: <?php echo $topCampaign->lead_count;?> leads

						</h4>
					</td>
				</tr>
			<?php }?>
		
	</tbody>
</table>


</div>


</div>
