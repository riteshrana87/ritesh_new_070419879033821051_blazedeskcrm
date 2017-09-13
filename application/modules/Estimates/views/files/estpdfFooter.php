<?php 
if(!empty($BZCompanyInfo) && isset($BZCompanyInfo[0]->company_name) && $BZCompanyInfo[0]->company_name != "")
{?>
<div style="text-align: center; font-family: Arial, Helvetica,sans-serif; font-weight: bold;font-size: 7pt; ">
	<?php echo $BZCompanyInfo[0]->company_name;?>
</div>
<?php }?>
<div style="text-align: center; font-family: Arial, Helvetica,sans-serif; font-weight: bold;font-size: 7pt; "> 
	<?php if(!empty($BZCompanyInfo) && isset($BZCompanyInfo[0]->company_street) && $BZCompanyInfo[0]->company_street != "")
{ echo $BZCompanyInfo[0]->company_street.' | '; }?>
	<?php if(!empty($BZCompanyInfo) && isset($BZCompanyInfo[0]->pincode) && $BZCompanyInfo[0]->pincode != "")
{ echo $BZCompanyInfo[0]->pincode.' | '; }?>
	<?php if(!empty($BZCompanyInfo) && isset($BZCompanyInfo[0]->city) && $BZCompanyInfo[0]->city != "")
{ echo $BZCompanyInfo[0]->city.' | '; }?>
	<?php if(!empty($BZCompanyInfo) && isset($BZCompanyInfo[0]->state) && $BZCompanyInfo[0]->state != "")
{ echo $BZCompanyInfo[0]->state.' | '; }?>
	<?php if(!empty($country_name) && isset($country_name) && $country_name != "")
{ echo $country_name['country_name']; }?>
</div>
<?php /*?><div style="text-align: center; font-family: Arial, Helvetica,sans-serif; font-weight: bold;font-size: 7pt; ">
	Vat Code | Tax Code (if applicable)
</div><?php */?>