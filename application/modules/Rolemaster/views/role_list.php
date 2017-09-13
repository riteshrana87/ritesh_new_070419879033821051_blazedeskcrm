<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$formAction = !empty($editRecord) ? 'updatedata' : 'insertdata';

$path = $crnt_view . '/' . $formAction;
?>
<?php // echo $this->session->flashdata('msg'); ?>
<!DOCTYPE html>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <ul class="breadcrumb nobreadcrumb-bg">
          
            <li><a href="<?php echo base_url(); ?>Rolemaster">
        <?= lang('TOP_MENU_SETTINGS') ?>
        </a></li>
            <li><?= lang('TOP_MENU_ROLLMANAGEMENT') ?></li>
        </ul>
    </div>
    <!--  Search Start -->
    <div class="col-xs-12 col-sm-6 col-md-3 pull-right text-right col-md-offset-3">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
  <!--  <form class="navbar-form navbar-left pull-right" id="searchForm"> -->
          <div class="navbar-form navbar-left pull-right" id="searchForm">
          <div class="input-group">
            <input type="text" name="search_input" id="search_input" class="form-control" placeholder="<?= lang('EST_LISTING_SEARCH_FOR')?>">
            <span class="input-group-btn">
            <button class="btn btn-default" type="submit" id="submit_search" name="submit" title="<?= lang('search')?>" ><i class="fa fa-search fa-x"></i></button>&nbsp;
             <button class="btn btn-default" type="button" onclick="reset_data()" title="<?= lang('reset')?>" ><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
         </div>
   <!--     </form> -->
      </div>     
       <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
   <!-- <div class="col-xs-12 col-md-4 col-sm-6 pull-right text-right ">
    <div class="row">
      <div class=" settings  text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class=" text-right search-top pull-right">
       <div class="input-group">
            <input type="text" name="search_input" id="search_input" class="form-control" placeholder="Search for...">
            <span class="input-group-btn">
            <button class="btn btn-default" type="submit" id="submit_search" name="submit" ><i class="fa fa-search fa-x"></i></button>&nbsp;
             <button class="btn btn-default" type="button" onclick="reset_data()" ><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
      </div>     
       <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div> -->
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
<!--  Search End -->
 <div class="clr"></div>
    <?php echo $this->session->flashdata('msg'); ?>     
      <div class="clr"></div>
</div>
<div class="clr"></div>
<div class="col-xs-12 col-sm-12 col-md-12">
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
      <div class="row"><h3 class="white-link">
        <?= $this->lang->line('role_list') ?>
      </h3></div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 text-right">
    <div class="row"><?php if(checkPermission('Rolemaster','add')){?>
     <a class="btn btn-white" data-href="<?php echo base_url($crnt_view . '/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" >
      <?= $this->lang->line('add_role') ?>
      </a>
      <?php }?>
      <!--  <a class="btn btn-white" href="<?php echo base_url($crnt_view . '/assignPermission'); ?>" data-toggle="ajaxModal">
      <?= $this->lang->line('assign_perms') ?>
      </a> --> <a class="btn btn-white" data-href="<?php echo base_url($crnt_view . '/addModule'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" >
      <?= $this->lang->line('add_module') ?>
      </a></div> </div>
    <div class="clr"></div>
    <div class="whitebox" id="tableUserDiv">
     <?php $this->load->view('ajaxlist');?>
    <!-- <div class="table table-responsive">
        <table id="role_list_data" class="table table-striped" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th><?= $this->lang->line('role_name') ?></th>
              <th><?= $this->lang->line('status') ?></th>
             
              <th><?= $this->lang->line('edit_delete_role') ?></th>
              <th><?= $this->lang->line('edit_delete_perms') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($information) && count($information) > 0) { ?>
            <?php
                      foreach ($information as $data) {
                        if ($data['status'] == 1) {
                            $data['status'] = "Active";
                        } else {
                            $data['status'] = "InActive";
                        }
                    ?>
            <tr>
              <td><?php echo $data['role_name']; ?></td>
              <td><?php echo $data['status']; ?></td>             
              <td><a
                    href="<?php echo base_url($crnt_view . '/edit/' . $data['role_id']); ?>" data-toggle="ajaxModal"><i
                    class="fa fa-pencil greencol" ></i></a>&nbsp;&nbsp; <a
                    href="javascript:;" onclick="deleteRole(<?php echo $data['role_id']; ?>);" ><i class="fa fa-remove redcol"></i></a></td>
              <td><a style="cursor:pointer;" data-toggle="modal" class="permView" data-href="<?php echo base_url($crnt_view . '/view_perms_to_role_list/' . $data['role_id']); ?>" data-target="#myModal">
        			<i class="fa fa-search fa-x greencol" ></i>
                </a>&nbsp;&nbsp;<a href="<?php echo base_url($crnt_view . '/editPermission/' . $data['role_id']); ?>"  data-toggle="ajaxModal" ><i class="fa fa-pencil greencol"></i></a>&nbsp;&nbsp;<a  href="javascript:;" onclick="deleteAssignedPermission(<?php echo $data['role_id']; ?>);"><i class="fa fa-remove redcol"></i></a></td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div> -->
      <div class="clr"></div>
    </div>
  </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12">
  <div class="row">
    <h3 class="white-link">
      <?= $this->lang->line('module_list') ?>
    </h3>
    <div class="clr"></div>
    <div class="whitebox">
      <div class="table table-responsive">
        <table id="module_list_data" class="table table-striped" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th><?= $this->lang->line('module_name') ?></th>
              <th><?= $this->lang->line('controller_name') ?></th>
              <th><?= $this->lang->line('module_action') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($module_data_list) && count($module_data_list) > 0) {  ?>
            <?php foreach ($module_data_list as $data) { ?>
            <tr>
              <td class="col-md-5"><?php echo $data['module_name']; ?></td>
              <td class="col-md-2"><?php echo $data['controller_name']; ?></td>
              <td class="col-md-2"><a data-href="<?php echo base_url($crnt_view . '/editModule/' . $data['module_id']); ?>" data-toggle="ajaxModal"><i class="fa fa-pencil greencol"></i></a>&nbsp;&nbsp; <a  href="javascript:;" onclick="deleteModuleController(<?php echo $data['module_id']; ?>);"><i class="fa fa-remove redcol"></i></a></td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="clr"></div>
    </div>
  </div>
</div>
<div class="clr"></div>
<!-- Modal -->
<div class="modal fade" id="permissionView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Permission View</h4>
      </div>
      <div class="modal-body">
        <div class="te"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 
<?php echo $roleMasterJs; ?>
<script>


function deleteRole(role_id){

    var delete_meg ="Are you sure you want to delete this Role?";
    BootstrapDialog.show(
        {
            title: '<?php echo $this->lang->line('Information');?>',
            message: delete_meg,
            buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '<?php echo $this->lang->line('ok');?>',
                action: function(dialog) {
                    window.location.href = "Rolemaster/deletedata/" + role_id;
                    dialog.close();
                }
            }]
        });
    }
function deleteAssignedPermission(perms_id){
    var delete_meg ="Are you sure you want to delete this Assigned Permission ?";
    BootstrapDialog.show(
        {
            title: '<?php echo $this->lang->line('Information');?>',
            message: delete_meg,
            buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '<?php echo $this->lang->line('ok');?>',
                action: function(dialog) {
                    window.location.href = "Rolemaster/deleteAssignperms/" + perms_id;
                    dialog.close();
                }
            }]
        });
    }
function deleteModuleController(module_id){
    var delete_meg ="Are you sure you want to delete this Module & Controller from List ?";
    BootstrapDialog.show(
        {
            title: '<?php echo $this->lang->line('Information');?>',
            message: delete_meg,
            buttons: [{
                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL');?>',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '<?php echo $this->lang->line('ok');?>',
                action: function(dialog) {
                    window.location.href = "Rolemaster/deleteModuleData/" + module_id;
                    dialog.close();
                }
            }]
        });
    }
$(document).ready(function () {
	
	$('#role_list_data').dataTable({
		 "aoColumnDefs" : [
		                   {
		                     'bSortable' : false,
		                     'aTargets' : [ 2, 3 ]
		 }]
	});
	$('#module_list_data').dataTable({
		 "aoColumnDefs" : [
		                   {
		                     'bSortable' : false,
		                     'aTargets' : [ 2 ]
		 }]
	});

    function bindClicks() {
        $("ul.tsc_pagination a").click(paginationClick);
    }
    
    function bindClicksSort() {
        $("body").delegate('th.sortRoleList a', 'click', (paginationClick));
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
                $("#tableUserDiv").empty();
                $("#tableUserDiv").html(response);
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