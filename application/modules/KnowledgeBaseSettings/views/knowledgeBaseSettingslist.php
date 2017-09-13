<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;
?>
 
<div class="row">
    <div class="col-md-6 col-lg-6 col-xs-12 col-sm-6">
       
           <?php echo $this->breadcrumbs->show(); ?>		
    
    </div>
     <!-- Search: Start -->
     <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
      <div class="navbar-form navbar-left pull-right" id="searchForm">
       <div class="input-group">
            <input type="text" name="search_input" id="search_input" class="form-control" placeholder="<?= lang('EST_LISTING_SEARCH_FOR')?>">
            <span class="input-group-btn">
            <button class="btn btn-default" type="submit" id="submit_search" name="submit" ><i class="fa fa-search fa-x"></i></button>&nbsp;
             <button class="btn btn-default" type="button" onclick="reset_data()" title="<?= lang('reset')?>" ><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
       </div>     
      </div>     
       <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
    <div class="clr"></div>
    <?php echo $this->session->flashdata('msg'); ?>     
      <div class="clr"></div>
 
<!-- Search: End -->  
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
 <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<h3 class="white-link"><?=$this->lang->line('knowledgeBaseSettings_list')?></h3> 
	</div>
<?php if(checkPermission('KnowledgeBaseSettings','add')){?>
 		 <div class="pull-right col-sm-1 text-right">
			<a data-href="<?php echo base_url($crnt_view.'/add');?>" data-toggle="ajaxModal" class="btn-white btn small-white-btn"><?=$this->lang->line('knowledgeBaseSettings_add')?></a>
    	</div>
  	 <?php }?>  	    
</div>
<div class="clr"></div>
 <!-- Listing of User List Table: Start -->
 <div class="whitebox" id="tableEmailTemplateDiv">
    <?php $this->load->view('ajaxlist');?>
    <!-- Listing of User List Table: End -->
    </div>
</div>
<div class="clr"></div> 
</div>
<script>

$(document).ready(function () {  

    function bindClicks() {
        $("ul.tsc_pagination a").click(paginationClick);
    }
    
    function bindClicksSort() {
        $("body").delegate('th.sortEmailTemplateList a', 'click', (paginationClick));
    }
    
    $('body').delegate('#submit_search', 'click', function () {
        paginationClick();
        return false;
    });
    $('body').delegate('#search_input', 'keyup', function (event) {
    	  if (event.keyCode == 13) {
    		  paginationClick();
     	 }       
        return false;
    });
    
    function paginationClick() {
        var href = $(this).attr('href');
        $("#rounded-corner").css("opacity", "0.4");
        var search = $('#search_input').val();
        $.ajax({
            type: "GET",
            url: href,
            data: {search: search},
            success: function (response)
            {
                //alert(response);
                $("#rounded-corner").css("opacity", "1");
                $("#tableEmailTemplateDiv").empty();
                $("#tableEmailTemplateDiv").html(response);
                bindClicks();
            }
        });
        return false;
    }
    bindClicks();
    bindClicksSort();
    
});

function reset_data()
{	
    $("#search_input").val("");
    $("#submit_search").trigger( "click" );
 	  
}
   
</script>
