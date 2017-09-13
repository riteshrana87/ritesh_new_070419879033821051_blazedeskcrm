<?php 
if(isset($BZCompanyInfo[0]->profile_photo) && $BZCompanyInfo[0]->profile_photo != "")
{
	$BZComLogo = base_url().SETTINGS_PROFILE_PIC_UPLOAD_PATH."/".$BZCompanyInfo[0]->profile_photo;
	$BZComIMG = '<img src="'.$BZComLogo.'" alt="" style="width:25%"/>';
} else {
	$BZComIMG = "";
}
?>
<table style="width:100%;text-align: right;height:540px;">
<tbody>
<tr>
<td style="text-align: right;height:340px; vertical-align:top; display:inline-block;">
<?php echo $BZComIMG; ?>
</td>
</tr>
</tbody>
</table>