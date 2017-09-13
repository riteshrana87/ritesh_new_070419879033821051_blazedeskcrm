<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;
?>
 
<div class="row">
    <div class="col-md-6 col-sm-6">
       
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
            <button class="btn btn-default" type="submit" id="submit_search" name="submit" title="<?= lang('search')?>"><i class="fa fa-search fa-x"></i></button>&nbsp;
             <button class="btn btn-default" type="button" onclick="reset_data()" ><i class="fa fa-refresh fa-x"></i></button>
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
  
  <!--  <div class="col-md-6 col-md-6 text-right">
        <div class="pull-right settings"> <a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
        <div class="pull-right search-top">
            <form class="navbar-form navbar-left" id="searchForm">
                <div class="form-group">
                    <input type="text" name="search_input" id="search_input" placeholder="Search for..." class="form-control">
                </div>
                <button class="fa fa-search btn btn-default" type="submit" id="submit" name="submit"></button>
                 <button class="btn btn-default" type="button"  onclick="reset_data()"  ><i class="fa fa-refresh fa-x" ></i></button>
            </form>
        </div>
    </div> -->
<!-- Search: End -->  
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
 <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<h3 class="white-link"><?=$this->lang->line('estimate_settings_list')?></h3> 
	</div>
<?php if(checkPermission('EstimateSettings','add')){?>
 		 <div class="col-xs-12 col-sm-6 text-right col-md-2 col-lg-2 col-md-offset-4">
			<a data-href="<?php echo base_url($crnt_view.'/add');?>" data-toggle="ajaxModal" class=" btn btn-white"><?=$this->lang->line('estimate_settings_add')?></a>
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

    $('body').delegate('[data-toggle="ajaxModal"]', 'click',
            function (e) {
                $('#ajaxModal').remove();
                e.preventDefault();
                var $this = $(this)
                        , $remote = $this.data('remote') || $this.attr('data-href')
                        , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
                $('body').append($modal);
                $modal.modal();
                $modal.load($remote);
				//$("body").removeClass("modal-open");
				//$("body").css("padding-right", "0 !important");
				
            }
   // $('#ajaxModal').css({height:"350px", overflow:"auto"});
    );
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
