<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script type="text/javascript">
// Assign permission Js
// For Assig Permission 

$(".CRM_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));   
    if($(this).prop('checked')==false){
    	$(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=all_CRM_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#CRM_LIST .child").length==$("#CRM_LIST input.child:checked").length){
	        $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=all_CRM_LIST]").prop('checked',$(this).prop("checked"));
	    }
    } 
});

$(".CRM_LIST_parent_horizontal_checkbox_All").change(function () {
	//var chkbx=$(this).attr('data-tag');
	//$("."+chkbx).prop('checked', $(this).prop("checked"));    	
	 $("#CRM_LIST input:checkbox").prop('checked', $(this).prop("checked"));
	 
});

$(".PM_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));    
    if($(this).prop('checked')==false){
    	$(".PM_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=all_PM_LIST]").prop('checked',$(this).prop("checked"));
    	
    }else{
    	if($("#PM_LIST .child").length==$("#PM_LIST input.child:checked").length){
	        $(".PM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=all_PM_LIST]").prop('checked',$(this).prop("checked"));
	       
	    }
    }
});

$(".PM_LIST_parent_horizontal_checkbox_All").change(function () {	
	 //$("input:checkbox").prop('checked', $(this).prop("checked"));
	$("#PM_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".Finance_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));  
    if($(this).prop('checked')==false){
    	$(".Finance_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=all_Finance_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#Finance_LIST .child").length==$("#Finance_LIST input.child:checked").length){
	        $(".Finance_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=all_Finance_LIST").prop('checked',$(this).prop("checked"));
	    }
    }   
});

$(".Finance_LIST_parent_horizontal_checkbox_All").change(function () {	
	// $("input:checkbox").prop('checked', $(this).prop("checked"));
	$("#Finance_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".Support_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));    
    if($(this).prop('checked')==false){
    	$(".Support_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=all_Support_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#Support_LIST .child").length==$("#Support_LIST input.child:checked").length){
	        $(".Support_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=all_Support_LIST]").prop('checked',$(this).prop("checked"));
	    }
    } 
});

$(".Support_LIST_parent_horizontal_checkbox_All").change(function () {	
	$("#Support_LIST input:checkbox").prop('checked', $(this).prop("checked"));
	// $("input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".HR_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));   
    if($(this).prop('checked')==false){
    	$(".HR_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=all_HR_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#HR_LIST .child").length==$("#HR_LIST input.child:checked").length){
	        $(".HR_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=all_HR_LIST]").prop('checked',$(this).prop("checked"));
	    }
    } 
});

$(".HR_LIST_parent_horizontal_checkbox_All").change(function () {	
	 // $("input:checkbox").prop('checked', $(this).prop("checked"));
	$("#HR_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".User_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked")); 
    if($(this).prop('checked')==false){
    	$(".User_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=all_User_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#User_LIST .child").length==$("#User_LIST input.child:checked").length){
	        $(".User_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=all_User_LIST]").prop('checked',$(this).prop("checked"));
	    }
    } 
       
});

$(".User_LIST_parent_horizontal_checkbox_All").change(function () {	
	 //$("input:checkbox").prop('checked', $(this).prop("checked"));
	$("#User_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".settings_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));   
    if($(this).prop('checked')==false){
    	$(".settings_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=all_settings_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#settings_LIST .child").length==$("#settings_LIST input.child:checked").length){
	        $(".settings_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=all_settings_LIST").prop('checked',$(this).prop("checked"));
	    }
    }  
});

$(".settings_LIST_parent_horizontal_checkbox_All").change(function () {	
	 // $("input:checkbox").prop('checked', $(this).prop("checked"));
	 $("#settings_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});


$("#CRM_LIST").show();
$("#PM_LIST").hide();
$("#Finance_LIST").hide();
$("#Support_LIST").hide();
$("#HR_LIST").hide();
$("#User_LIST").hide();
$("#settings_LIST").hide();
$("#CRM_LI").addClass("active");

// For Edit Permission 

$(".edit_CRM_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');		
    $("."+chkbx).prop('checked', $(this).prop("checked"));
    if($(this).prop('checked')==false){
    	$(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=edit_all_CRM_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#edit_CRM_LIST .child").length==$("#edit_CRM_LIST input.child:checked").length){
	        $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=edit_all_CRM_LIST]").prop('checked',$(this).prop("checked"));
	    }
    }     
});

$(".edit_CRM_LIST_parent_horizontal_checkbox_All").change(function () { 	
	 $("#edit_CRM_LIST input:checkbox").prop('checked', $(this).prop("checked"));
	 
});

$(".edit_PM_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));    
    if($(this).prop('checked')==false){
    	$(".edit_PM_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=edit_all_PM_LIST]").prop('checked',$(this).prop("checked"));
    	
    }else{
    	if($("#edit_PM_LIST .child").length==$("#edit_PM_LIST input.child:checked").length){
	        $(".edit_PM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=edit_all_PM_LIST]").prop('checked',$(this).prop("checked"));
	       
	    }
    }
    
});

$(".edit_PM_LIST_parent_horizontal_checkbox_All").change(function () {	
	
	$("#edit_PM_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".edit_Finance_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked")); 
    if($(this).prop('checked')==false){
    	$(".edit_Finance_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=edit_all_Finance_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#edit_Finance_LIST .child").length==$("#edit_Finance_LIST input.child:checked").length){
	        $(".edit_Finance_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=edit_all_Finance_LIST").prop('checked',$(this).prop("checked"));
	    }
    }   
});

$(".edit_Finance_LIST_parent_horizontal_checkbox_All").change(function () {	
	// $("input:checkbox").prop('checked', $(this).prop("checked"));
	$("#edit_Finance_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".edit_Support_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));    
    if($(this).prop('checked')==false){
    	$(".edit_Support_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=edit_all_Support_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#edit_Support_LIST .child").length==$("#edit_Support_LIST input.child:checked").length){
	        $(".edit_Support_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=edit_all_Support_LIST]").prop('checked',$(this).prop("checked"));
	    }
    } 
});

$(".edit_Support_LIST_parent_horizontal_checkbox_All").change(function () {	
	$("#edit_Support_LIST input:checkbox").prop('checked', $(this).prop("checked"));
	// $("input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".edit_HR_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));    
    if($(this).prop('checked')==false){
    	$(".edit_HR_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=edit_all_HR_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#edit_HR_LIST .child").length==$("#edit_HR_LIST input.child:checked").length){
	        $(".edit_HR_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=edit_all_HR_LIST]").prop('checked',$(this).prop("checked"));
	    }
    }
});

$(".edit_HR_LIST_parent_horizontal_checkbox_All").change(function () {	
	 // $("input:checkbox").prop('checked', $(this).prop("checked"));
	$("#edit_HR_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".edit_User_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));  
    if($(this).prop('checked')==false){
    	$(".edit_User_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=edit_all_User_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#edit_User_LIST .child").length==$("#edit_User_LIST input.child:checked").length){
	        $(".edit_User_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=edit_all_User_LIST]").prop('checked',$(this).prop("checked"));
	    }
    }  
});

$(".edit_User_LIST_parent_horizontal_checkbox_All").change(function () {	
	 //$("input:checkbox").prop('checked', $(this).prop("checked"));
	$("#edit_User_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(".edit_settings_LIST_parent_horizontal_checkbox").change(function () {		
	var chkbx=$(this).attr('data-tag');	
    $("."+chkbx).prop('checked', $(this).prop("checked"));    
    if($(this).prop('checked')==false){
    	$(".edit_settings_LIST_parent_horizontal_checkbox_All").prop('checked',$(this).prop("checked"));
    	$("input[data-all=edit_all_settings_LIST]").prop('checked',$(this).prop("checked"));
    }else{
    	if($("#edit_settings_LIST .child").length==$("#edit_settings_LIST input.child:checked").length){
	        $(".edit_settings_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	        $("input[data-all=edit_all_settings_LIST").prop('checked',$(this).prop("checked"));
	    }
    }  
});

$(".edit_settings_LIST_parent_horizontal_checkbox_All").change(function () {	
	 // $("input:checkbox").prop('checked', $(this).prop("checked"));
	 $("#edit_settings_LIST input:checkbox").prop('checked', $(this).prop("checked"));
});

$(document).ready(function() {
	
	$('.edit_CRM_LIST_parent_horizontal_checkbox').each(function(){
			var chkClass=$(this).attr('data-tag');
			var datachecked = $(this).attr('data-box');
			if($('.'+chkClass).length != 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
			{	
				$("input[data-box="+datachecked+"]").prop('checked',true);
				
			}else{
				$("input[data-box="+datachecked+"]").prop('checked',false);
			}
	});
	$('.edit_PM_LIST_parent_horizontal_checkbox').each(function(){
		var chkClass=$(this).attr('data-tag');
		var datachecked = $(this).attr('data-box');
			
		if($('.'+chkClass).length != 0 &&  ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{	
			$("input[data-box="+datachecked+"]").prop('checked',true);
		
		}else{
			$("input[data-box="+datachecked+"]").prop('checked',false);
		}
	});
	$('.edit_Finance_LIST_parent_horizontal_checkbox').each(function(){
		var chkClass=$(this).attr('data-tag');
		var datachecked = $(this).attr('data-box');
		if($('.'+chkClass).length != 0 &&  ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$("input[data-box="+datachecked+"]").prop('checked',true);
		
		}else{
			$("input[data-box="+datachecked+"]").prop('checked',false);
		}
	});
	$('.edit_Support_LIST_parent_horizontal_checkbox').each(function(){
		var chkClass=$(this).attr('data-tag');
		var datachecked = $(this).attr('data-box');
		if($('.'+chkClass).length != 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$("input[data-box="+datachecked+"]").prop('checked',true);
		
		}else{
			$("input[data-box="+datachecked+"]").prop('checked',false);
		}
	});

	$('.edit_HR_LIST_parent_horizontal_checkbox').each(function(){
		var chkClass=$(this).attr('data-tag');
		var datachecked = $(this).attr('data-box');
		if( $('.'+chkClass).length != 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			
			$("input[data-box="+datachecked+"]").prop('checked',true);
		
		}else{
		
			$("input[data-box="+datachecked+"]").prop('checked',false);
		}
	});
	
	$('.edit_User_LIST_parent_horizontal_checkbox').each(function(){
		var chkClass=$(this).attr('data-tag');
		var datachecked = $(this).attr('data-box');
		if( $('.'+chkClass).length != 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$("input[data-box="+datachecked+"]").prop('checked',true);
		
		}else{
			$("input[data-box="+datachecked+"]").prop('checked',false);
		}
	});
	
	$('.edit_settings_LIST_parent_horizontal_checkbox').each(function(){
		var chkClass=$(this).attr('data-tag');
		var datachecked = $(this).attr('data-box');
		if( $('.'+chkClass).length != 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$("input[data-box="+datachecked+"]").prop('checked',true);
		
		}else{
			$("input[data-box="+datachecked+"]").prop('checked',false);
		}
	});

	// For All Checkbox
	
	$('.edit_CRM_LIST_parent_horizontal_checkbox_All').each(function(){
		var chkClass=$(this).attr('data-tag');
		if($('.'+chkClass).length!= 0 && ( $('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			
			$('.edit_CRM_LIST_parent_horizontal_checkbox_All').prop('checked',true);
		
		}else{
			$('.edit_CRM_LIST_parent_horizontal_checkbox_All').prop('checked',false);
		}
	});
	$('.edit_PM_LIST_parent_horizontal_checkbox_All').each(function(){
		var chkClass=$(this).attr('data-tag');
		if($('.'+chkClass).length!= 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$('.edit_PM_LIST_parent_horizontal_checkbox_All').prop('checked',true);
		
		}else{
			$('.edit_PM_LIST_parent_horizontal_checkbox_All').prop('checked',false);
		}
	});

	$('.edit_Finance_LIST_parent_horizontal_checkbox_All').each(function(){
		var chkClass=$(this).attr('data-tag');
		if($('.'+chkClass).length!= 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$('.edit_Finance_LIST_parent_horizontal_checkbox_All').prop('checked',true);
		
		}else{
			$('.edit_Finance_LIST_parent_horizontal_checkbox_All').prop('checked',false);
		}
	});

	$('.edit_Support_LIST_parent_horizontal_checkbox_All').each(function(){
		var chkClass=$(this).attr('data-tag');
		if($('.'+chkClass).length!= 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$('.edit_Support_LIST_parent_horizontal_checkbox_All').prop('checked',true);
		
		}else{
			$('.edit_Support_LIST_parent_horizontal_checkbox_All').prop('checked',false);
		}
	});

	$('.edit_HR_LIST_parent_horizontal_checkbox_All').each(function(){
		var chkClass=$(this).attr('data-tag');
	
		if( $('.'+chkClass).length != 0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{	
					
			$('.edit_HR_LIST_parent_horizontal_checkbox_All').prop('checked',true);
		
		}else{
			$('.edit_HR_LIST_parent_horizontal_checkbox_All').prop('checked',false);
		}
	});
	
	$('.edit_User_LIST_parent_horizontal_checkbox_All').each(function(){
		var chkClass=$(this).attr('data-tag');
		if($('.'+chkClass).length !=0 && ( $('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$('.edit_User_LIST_parent_horizontal_checkbox_All').prop('checked',true);
		
		}else{
			$('.edit_User_LIST_parent_horizontal_checkbox_All').prop('checked',false);
		}
	});
	
	$('.edit_settings_LIST_parent_horizontal_checkbox_All').each(function(){
		var chkClass=$(this).attr('data-tag');
		if($('.'+chkClass).length !=0 && ($('.'+chkClass).length==$('.'+chkClass+':checked').length))
		{
			$('.edit_settings_LIST_parent_horizontal_checkbox_All').prop('checked',true);
		
		}else{
			$('.edit_settings_LIST_parent_horizontal_checkbox_All').prop('checked',false);
		}
	});
	
});

function permsTab(module){

	if(module == "CRM"){
		$("#CRM_LIST").show();
		$("#PM_LIST").hide();
		$("#Finance_LIST").hide();
		$("#Support_LIST").hide();
		$("#HR_LIST").hide();
		$("#User_LIST").hide();
		$("#settings_LIST").hide();
		$("#CRM_LI").addClass("active");
		$("#PM_LI").removeClass("active");
		$("#Finance_LI").removeClass("active");
		$("#Support_LI").removeClass("active");
		$("#HR_LI").removeClass("active");
		$("#User_LI").removeClass("active");
		$("#settings_LI").removeClass("active");
	}else if(module == "PM"){	
		$("#PM_LIST").show();
		$("#CRM_LIST").hide();
		$("#Finance_LIST").hide();
		$("#Support_LIST").hide();
		$("#HR_LIST").hide();
		$("#User_LIST").hide();
		$("#settings_LIST").hide();
		$("#CRM_LI").removeClass("active");
		$("#PM_LI").addClass("active");
		$("#Finance_LI").removeClass("active");
		$("#Support_LI").removeClass("active");
		$("#HR_LI").removeClass("active");
		$("#User_LI").removeClass("active");
		$("#settings_LI").removeClass("active");
	}else if(module == "Finance"){	
		$("#PM_LIST").hide();
		$("#CRM_LIST").hide();
		$("#Finance_LIST").show();
		$("#Support_LIST").hide();
		$("#HR_LIST").hide();
		$("#User_LIST").hide();
		$("#settings_LIST").hide();
		$("#CRM_LI").removeClass("active");
		$("#PM_LI").removeClass("active");
		$("#Finance_LI").addClass("active");
		$("#Support_LI").removeClass("active");
		$("#HR_LI").removeClass("active");
		$("#User_LI").removeClass("active");
		$("#settings_LI").removeClass("active");		
	}else if(module == "Support"){
		$("#PM_LIST").hide();
		$("#CRM_LIST").hide();
		$("#Finance_LIST").hide();
		$("#Support_LIST").show();
		$("#HR_LIST").hide();
		$("#User_LIST").hide();
		$("#settings_LIST").hide();
		$("#CRM_LI").removeClass("active");
		$("#PM_LI").removeClass("active");
		$("#Finance_LI").removeClass("active");
		$("#Support_LI").addClass("active");
		$("#HR_LI").removeClass("active");
		$("#User_LI").removeClass("active");
		$("#settings_LI").removeClass("active");
	}else if(module == "HR"){
		$("#PM_LIST").hide();
		$("#CRM_LIST").hide();
		$("#Finance_LIST").hide();
		$("#Support_LIST").hide();
		$("#HR_LIST").show();
		$("#User_LIST").hide();
		$("#settings_LIST").hide();
		$("#CRM_LI").removeClass("active");
		$("#PM_LI").removeClass("active");
		$("#Finance_LI").removeClass("active");
		$("#Support_LI").removeClass("active");
		$("#HR_LI").addClass("active");
		$("#User_LI").removeClass("active");
		$("#settings_LI").removeClass("active");
	}else if(module == "User"){
		$("#PM_LIST").hide();
		$("#CRM_LIST").hide();
		$("#Finance_LIST").hide();
		$("#Support_LIST").hide();
		$("#HR_LIST").hide();
		$("#User_LIST").show();
		$("#settings_LIST").hide();
		$("#CRM_LI").removeClass("active");
		$("#PM_LI").removeClass("active");
		$("#Finance_LI").removeClass("active");
		$("#Support_LI").removeClass("active");
		$("#HR_LI").removeClass("active");
		$("#User_LI").addClass("active");
		$("#settings_LI").removeClass("active");
	}else if(module == "settings"){
		$("#PM_LIST").hide();
		$("#CRM_LIST").hide();
		$("#Finance_LIST").hide();
		$("#Support_LIST").hide();
		$("#HR_LIST").hide();
		$("#User_LIST").hide();
		$("#settings_LIST").show();
		$("#CRM_LI").removeClass("active");
		$("#PM_LI").removeClass("active");
		$("#Finance_LI").removeClass("active");
		$("#Support_LI").removeClass("active");
		$("#HR_LI").removeClass("active");
		$("#User_LI").removeClass("active");
		$("#settings_LI").addClass("active");
	}
		
}

/*function permsTab(module){
	if(module == "CRM"){
		$("#CRM_LIST").show();
		$("#PM_LIST").hide();
		$("#CRM_LI").addClass("active");
		$("#PM_LI").removeClass("active");
	}else if(module == "PM"){		
		$("#PM_LIST").show();
		$("#CRM_LIST").hide();
		$("#CRM_LI").removeClass("active");
		$("#PM_LI").addClass("active");
	}				
}*/

$('.parent').click(function () {
    var child = $(this).attr('data-attr');
 
    if (this.checked) {
        $('.' + child).each(function () { //loop through each checkbox
            this.checked = true; //select all checkboxes with class "checkbox1"               
        });

    } else {
        $('.' + child).each(function () { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "checkbox1"                       
        });
        // For Edit  sections 
        $(".edit_CRM_LIST_parent_horizontal_checkbox").prop('checked',false);	
	    $(".edit_PM_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".edit_Finance_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".edit_Support_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".edit_HR_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".edit_User_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".edit_settings_LIST_parent_horizontal_checkbox").prop('checked',false);
	    
        $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',false);	
	    $(".edit_PM_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_Finance_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_Support_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_HR_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_User_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_settings_LIST_parent_horizontal_checkbox_All").prop('checked',false); 

	    // For assign sections
        $(".CRM_LIST_parent_horizontal_checkbox").prop('checked',false);	
	    $(".PM_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".Finance_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".Support_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".HR_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".User_LIST_parent_horizontal_checkbox").prop('checked',false);
	    $(".settings_LIST_parent_horizontal_checkbox").prop('checked',false);
	    
        $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',false);	
	    $(".PM_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".Finance_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".Support_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".HR_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".User_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".settings_LIST_parent_horizontal_checkbox_All").prop('checked',false);    

	  	        
    }
	// Edit sections 
    if($("input[data-all=edit_all_CRM_LIST]").length==$("input[data-all=edit_all_CRM_LIST]:checked").length){
	
    	$(".edit_CRM_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
    }

    if($("input[data-all=edit_all_PM_LIST]").length==$("input[data-all=edit_all_PM_LIST]:checked").length){
    	
    	$(".edit_PM_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".edit_PM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
    }

	if($("input[data-all=edit_all_Finance_LIST]").length==$("input[data-all=edit_all_Finance_LIST]:checked").length){
	    	
	    	$(".edit_Finance_LIST_parent_horizontal_checkbox").prop('checked',true);
	    	$(".edit_Finance_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
	if($("input[data-all=edit_all_Support_LIST]").length==$("input[data-all=edit_all_Support_LIST]:checked").length){
    	
    	$(".edit_Support_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".edit_Support_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
	if($("input[data-all=edit_all_HR_LIST]").length==$("input[data-all=edit_all_HR_LIST]:checked").length){
    	
    	$(".edit_HR_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".edit_HR_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
	if($("input[data-all=edit_all_User_LIST]").length==$("input[data-all=edit_all_User_LIST]:checked").length){
    	
    	$(".edit_User_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".edit_User_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
	if($("input[data-all=edit_all_settings_LIST]").length==$("input[data-all=edit_all_settings_LIST]:checked").length){
    	
    	$(".edit_settings_LIST_parent_horizontal_checkbox_All").prop('checked',true);
    	$(".edit_settings_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}

	//Assign sections 
    if($("input[data-all=all_CRM_LIST]").length==$("input[data-all=all_CRM_LIST]:checked").length){
	
    	$(".CRM_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
    }

    if($("input[data-all=all_PM_LIST]").length==$("input[data-all=all_PM_LIST]:checked").length){
    	
    	$(".PM_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".PM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
    }

	if($("input[data-all=all_Finance_LIST]").length==$("input[data-all=all_Finance_LIST]:checked").length){
	    	
	    	$(".Finance_LIST_parent_horizontal_checkbox").prop('checked',true);
	    	$(".Finance_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
	if($("input[data-all=all_Support_LIST]").length==$("input[data-all=all_Support_LIST]:checked").length){
    	
    	$(".Support_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".Support_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
	if($("input[data-all=all_HR_LIST]").length==$("input[data-all=all_HR_LIST]:checked").length){
    	
    	$(".HR_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".HR_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
	if($("input[data-all=all_User_LIST]").length==$("input[data-all=all_User_LIST]:checked").length){
    	
    	$(".User_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".User_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
	if($("input[data-all=all_settings_LIST]").length==$("input[data-all=all_settings_LIST]:checked").length){
    	
    	$(".settings_LIST_parent_horizontal_checkbox").prop('checked',true);
    	$(".settings_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	}
       
});

 

$('.child').click(function () {	
	 var chk=$(this).attr("data-parent");
	  if($(this).prop('checked')===false){  
		  // for Edit Section
	    $("input[data-tag='"+chk+"']").prop('checked',false);
	    $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',false);	
	    $(".edit_PM_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_Finance_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_Support_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_HR_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_User_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".edit_settings_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    // For Assign section 
	
	    $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',false);	
	    $(".PM_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".Finance_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".Support_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".HR_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".User_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	    $(".settings_LIST_parent_horizontal_checkbox_All").prop('checked',false);
	  }else{    
	     if ($("input[data-parent='"+chk+"']").length === $("input[data-parent='"+chk+"']:checked").length) {
	        $("input[data-tag='"+chk+"']").prop('checked',true);
	    }
		    // for Edit Section 
	     if($("#edit_CRM_LIST .child").length==$("#edit_CRM_LIST input.child:checked").length){
	         $(".edit_CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	       }
	     if($("#edit_PM_LIST .child").length==$("#edit_PM_LIST input.child:checked").length){
		    
	         $(".edit_PM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#edit_Finance_LIST .child").length==$("#edit_Finance_LIST input.child:checked").length){
	         $(".edit_Finance_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#edit_Support_LIST .child").length==$("#edit_Support_LIST input.child:checked").length){
	         $(".edit_Support_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#edit_HR_LIST .child").length==$("#edit_HR_LIST input.child:checked").length){
	         $(".edit_HR_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#edit_User_LIST .child").length==$("#edit_User_LIST input.child:checked").length){
	         $(".edit_User_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#edit_settings_LIST .child").length==$("#edit_settings_LIST input.child:checked").length){
	         $(".edit_settings_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }	

			// For Assign Section 
	     if($("#CRM_LIST .child").length==$("#CRM_LIST input.child:checked").length){
	         $(".CRM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	       }
	     if($("#PM_LIST .child").length==$("#PM_LIST input.child:checked").length){
		    
	         $(".PM_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#Finance_LIST .child").length==$("#Finance_LIST input.child:checked").length){
	         $(".Finance_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#Support_LIST .child").length==$("#Support_LIST input.child:checked").length){
	         $(".Support_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#HR_LIST .child").length==$("#HR_LIST input.child:checked").length){
	         $(".HR_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#User_LIST .child").length==$("#User_LIST input.child:checked").length){
	         $(".User_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }
	     if($("#settings_LIST .child").length==$("#settings_LIST input.child:checked").length){
	         $(".settings_LIST_parent_horizontal_checkbox_All").prop('checked',true);
	      }	
	      
	  }
	var parentCbox=$(this).attr('data-parent');
//		alert(parentCbox);
		var child = $(this).attr('data-attr');
	      if ($('.child.' + child).length === $('.child.' + child + ':checked').length) {
	        $('.parent.' + child).prop('checked', true);
	    }
	    else{
	          $('.parent.' + child).prop('checked', false);
	    }

});

// Edit permission Js 
 $('.permView').click(function (e) {
        var link = $(this).attr('data-href');
        e.preventDefault();
        $('#permissionView .modal-body').load(link);
        $('#permissionView').modal('show');

  });

/* $("#CRM_LIST").show();
 $("#PM_LIST").hide();
	function permsTab(module){

		if(module == "CRM"){
			$("#CRM_LIST").show();
			$("#PM_LIST").hide();
		}else if(module == "PM"){
			$("#PM_LIST").show();
			$("#CRM_LIST").hide();
		}
			
	}
*/
</script>