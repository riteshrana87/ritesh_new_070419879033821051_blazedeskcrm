<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$path = $crnt_view.'/'.$formAction;
?>
<?php  // echo $this->session->flashdata('msg'); ?>      
<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
           <li><a href="<?= base_url('') ?>"><?= lang('cms')?></a></li>
           <li><a href="<?= base_url('Emailtemplate') ?>"><?= lang('emailTemplate_list')?></a></li>	
            <li><?= lang('system_template_button')?></li>		
    	</ul>
    </div>

 <div class="clr"></div>
    <?php echo $this->session->flashdata('msg'); ?>     
      <div class="clr"></div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
 <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<h3 class="white-link"><?=$this->lang->line('system_emailTemplate_list')?></h3> 
	</div>	    
</div>
<div class="clr"></div>
 <!-- Listing of User List Table: Start -->
 <div class="whitebox" id="tableSystemEmailTemplateDiv">
    <?php $this->load->view('systemtemplateajaxlist');?>
    <!-- Listing of User List Table: End -->
    </div>
</div>

<!--  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <h3 class="white-link"><?=$this->lang->line('emailTemplate_list')?></h3>
    <div class="whitebox">
      <div class="table table-responsive">
        <table id="emailTemplate" class="table table-striped" cellspacing="0" width="100%">
          <thead>
            <tr>
            <th>#</th>
              <th><?=$this->lang->line('emailTemplate_sub')?></th>           
              <th><?=$this->lang->line('emailTemplate_body')?></th>
              <th><?=$this->lang->line('status')?></th>
              <th></th>
            </tr>
          </thead>       
         <tbody>
        <?php if(isset($information) && count($information) > 0 ){ ?>
        <?php foreach($information as $data){
          if($data['status'] == 1){ 
            $data['status'] = "Active"; 
          }else{ 
            $data['status'] = "InActive"; 
          }?>
        <tr>
         <td><?php echo $data['template_id'];?></td>
          <td><?php echo $data['subject'];?></td>         
          <td><?php if(checkPermission('Emailtemplate','view')){ ?><a href="<?php echo base_url($crnt_view.'/viewEmailTemplate/'.$data['template_id']);?>" data-toggle="ajaxModal"><?= $this->lang->line('emailTemplate_view') ?></a><?php }?></td>               
           <td><?php echo $data['status'];?></td>
          <td><?php if(checkPermission('Emailtemplate','edit')){ ?><a href="<?php echo base_url($crnt_view.'/edit/'.$data['template_id']);?>" data-toggle="ajaxModal"><i class="fa fa-pencil bluecol" ></i></a><?php }?>&nbsp;&nbsp;<?php if(checkPermission("Emailtemplate","delete")){?><a href="<?php echo base_url($crnt_view.'/deletedata/'.$data['template_id']);?>"><i class="fa fa-remove redcol" onclick="return confirm('Are you sure you want to delete this Email Template ?','yes','no');"></i></a><?php } ?></td>
        </tr>
        <?php } ?>
      <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div> -->
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
				$("body").removeClass("modal-open");
				$("body").css("padding-right", "0 !important");
				
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
                $("#tableSystemEmailTemplateDiv").empty();
                $("#tableSystemEmailTemplateDiv").html(response);
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
