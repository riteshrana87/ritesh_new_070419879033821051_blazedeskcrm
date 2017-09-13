<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = !empty($editRecord)?'updatedata':'insertdata'; 

$path = $crnt_view.'/'.$formAction;

$SortDefault = '<i class="fa fa-sort"></i>';
$sortAsc = '<i class="fa fa-sort-desc"></i>';
$sortDesc = '<i class="fa fa-sort-asc"></i>';
$typesortOrder = "";

if ($sortOrder == "asc")
    $sortOrder = "desc";
else
    $sortOrder = "asc";
    
if ($typesortOrder == "asc")
    $typesortOrder = "desc";
else
    $typesortOrder = "asc";
    

if ($statussortOrder == "asc")
    $statussortOrder = "desc";
else
    $statussortOrder = "asc";

?>
<?php //echo $this->session->flashdata('msg'); ?>

<!-- Example row of columns -->

<div class="row">
  <div class="col-xs-12 col-md-6 col-lg-12 col-sm-12">
    <div class="row"><div class="col-md-6 col-md-6">
               
                    <?php echo $this->breadcrumbs->show(); ?>	
               
     </div></div>
  </div>
  <!-- Search: Start -->
<!--   <div class="col-md-6 col-md-6 text-right">
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
 <div class="clr"></div>
    <?php echo $this->session->flashdata('msg'); ?>     
      
  <div class="">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <h3 class="white-link">
        <?=$this->lang->line('Support_settings_menu_hader')?>
      </h3>
    </div>

  	<div class="col-xs-12 col-sm-6 col-md-6 text-right"> 
  	 <?php if(checkPermission('SupportSettings','add')){?>
  	  <a class="btn btn-white" data-href="<?php echo base_url($crnt_view . '/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" >
     <?=$this->lang->line('add_settings')?>
      </a>
      <?php }?>
      <?php if(checkPermission('SupportSettings','add')){ ?>
      <a class="btn btn-white" data-href="<?php echo base_url($crnt_view . '/addType'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" >
        <?=$this->lang->line('add_settings_type')?>
      </a> 
      <?php }?>
      <?php if(checkPermission('SupportSettings','add')){ ?>
      <a class="btn btn-white" data-href="<?php echo base_url($crnt_view . '/addStatus'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" >
        <?=$this->lang->line('add_settings_status')?>
      </a> 
      <?php }?>
   </div>
    <div class="clr"></div>
  </div>
  <div class="">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
      <!-- Listing of User List Table: Start -->
      <div class="whitebox" id="tableCurrencyDiv">
        <?php $this->load->view('ajaxlist');?>
        <!-- Listing of User List Table: End --> 
      </div>
    </div>
  </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <h3 class="white-link">
        <?=$this->lang->line('Support_type_menu_hader')?>
      </h3>
    </div>
    <div class="">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
      <!-- Listing of User List Table: Start -->
      <div class="whitebox" id="tabletypeDiv">
        <?php $this->load->view('type_ajaxlist');?>
        <!-- Listing of User List Table: End --> 
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
      <h3 class="white-link">
        <?=$this->lang->line('Support_status_menu_hader')?>
      </h3>
    </div>
    <div class="">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
      <!-- Listing of User List Table: Start -->
      <div class="whitebox" id="tablestatusDiv">
        <?php $this->load->view('status_ajaxlist');?>
        <!-- Listing of User List Table: End --> 
      </div>
    </div>
  </div>
<script>
$(document).ready(function () {
    function bindClicks() {
        $("#tableCurrencyDiv ul.tsc_pagination a").click(paginationClick);
    }
    
    function bindClicksSort() {
        $("body").delegate('th.sortSettingsListtype a', 'click', (paginationClick));
    }

    function typebindClicks() {
        $("#tabletypeDiv ul.tsc_pagination a").click(typepaginationClick);
    }
    
    function typebindClicksSort() {
        $("body").delegate('th.sortSettingsList a', 'click', (typepaginationClick));
    }

    function statusbindClicks() {
        $("#tablestatusDiv ul.tsc_pagination a").click(statuspaginationClick);
    }
    
    function statusbindClicksSort() {
        $("body").delegate('th.sortSettingsListStatus a', 'click', (statuspaginationClick));
    }
     
    $('body').delegate('#searchForm', 'submit', function () {
        paginationClick();
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
                $("#tableCurrencyDiv").empty();
                $("#tableCurrencyDiv").html(response);
                bindClicks();
            }
        });
        return false;
    }
    bindClicks();
    bindClicksSort();
    function typepaginationClick() {
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
                $("#tabletypeDiv").empty();
                $("#tabletypeDiv").html(response);
                typebindClicks();
            }
        });
        return false;
    }   
    typebindClicks();
    typebindClicksSort();
    
    function statuspaginationClick() {
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
                $("#tablestatusDiv").empty();
                $("#tablestatusDiv").html(response);
                statusbindClicks();
            }
        });
        return false;
    }
    statusbindClicks();
    statusbindClicksSort();
    
});
function reset_data()
{	
    $("#search_input").val("");
    $("#submit").trigger( "click" );
 	  
}
</script> 
