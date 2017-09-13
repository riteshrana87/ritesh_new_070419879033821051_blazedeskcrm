<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$formAction = 'insertdata';
$path = $main_content . '/' . $formAction;
?>
<!-- Example row of columns -->
  <div class="row">
    <div class="col-md-6 col-md-6">
    <ul class="breadcrumb nobreadcrumb-bg">
        <li><a href="<?php echo base_url("");?>"><?php echo lang('crm'); ?></a></li>
		<li class="active"><?php echo lang('estimates'); ?></li>
    </ul>
    </div>
      <div class="col-xs-12 col-md-3 pull-right text-right col-md-offset-3">
          <div class="row">
              <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
              <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                  <form class="navbar-form navbar-left pull-right" id="searchForm" method="post">
                      <div class="input-group">
                          <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                          <input type="text" name="searchtext" id="searchtext" class="form-control" placeholder="" aria-controls="example1" placeholder="Search" value="<?=!empty($searchtext)?$searchtext:''?>">
            <span class="input-group-btn">
             <button onclick="data_search('changesearch')" class="btn btn-default"  title="Search"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i></button>
             <button class="btn btn-default howler flt" title="Reset" onclick="reset_data()" title="Reset"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
                  </form>
              </div>

              <div class="clr"></div>
          </div>
          <div class="clr"></div>
      </div>
  <div class="clr"></div>
  <div class="col-xs-12 col-md-9">
	<div class="col-xs-6 col-md-6 no-left-pad"><h3 class="white-link"><?php echo lang('estimates'); ?></h3></div>
	<div class="col-xs-6 col-md-6 no-right-pad text-right">
            <a class="btn btn-white" href="<?php echo base_url().''.$estimate_view.'/add';?>">Create New Estimate</a>
	</div>
	<div class="clr"></div>
      <div class="whitebox" id="common_div">
          <?=$this->load->view('Estimates/ajax_list.php','',true);?>
          <div class="clr"></div>
      </div>
  </div>
  <div class="col-md-12 col-md-3">
  <div class=" whitebox">
  <div class="col-md-6 col-xs-6"> <span class="font-3em greencol"><b>491</b></span> </div>
  <div class="col-md-6 col-xs-6 no-left-pad">
    <p class="font-1em pad_top10"><b>Total<br/>
      Companies</b></p>
  </div>
  <div class="clr"></div>
  <div class="blackbottom"></div>
  <div class="clr"></div>
  <br/>
  <form>
    <div class="col-md-12 col-xs-12">
      <label><b>Filter Options:</b></label>
	   <div class="form-group">
        <select class="form-control">
          <option>Status</option>
          <option></option>
          <option></option>
        </select>
      </div>
      <div class="form-group">
        <select class="form-control">
          <option>Branche</option>
          <option></option>
          <option></option>
        </select>
      </div>
      <div class="form-group">
        <select class="form-control">
          <option>Owner</option>
          <option></option>
          <option></option>
        </select>
      </div>
     
      <label><b>Value between:</b></label>
      <div class="clr"></div>
      <div class="col-md-5 col-xs-5 no-left-pad">
        <div class="form-group">
          <input class="form-control" />
        </div>
      </div>
      <div class="col-md-2 col-xs-2"> and </div>
      <div class="col-md-5 col-xs-5 no-right-pad">
        <div class="form-group">
          <input class="form-control" />
        </div>
      </div>
   
    <div class="clr"></div>
    <label><b>Creation date between:</b></label>
    <div class="clr"></div>
   <div class="col-md-6 col-xs-6 no-left-pad">
      <div class="form-group">
        <div class='input-group date' id='datetimepicker1'>
          <input type='text' class="form-control" />
          <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
      </div>
    </div>
      <div class="col-md-6 col-xs-6 no-right-pad">
      <div class="form-group">
        <div class='input-group date' id='datetimepicker2'>
          <input type='text' class="form-control" />
          <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
      </div>
    </div>
    <div class="clr"></div>
     <div class="clr"></div>
    <label><b>Contract date between:</b></label>
    <div class="clr"></div>
    <div class="col-md-6 col-xs-6 no-left-pad">
      <div class="form-group">
        <div class='input-group date' id='datetimepicker3'>
          <input type='text' class="form-control" />
          <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
      </div>
    </div>
      <div class="col-md-6 col-xs-6 no-right-pad">
      <div class="form-group">
        <div class='input-group date' id='datetimepicker4'>
          <input type='text' class="form-control" />
          <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
      </div>
    </div>
    <div class="clr"></div>
	<div class="pad-10 text-center">
		<a href="#" class="width-100 btn small-white-btn2">Import Prospect (CSV)
		  </a>
    </div>
	 </div>
    <div class="clr"></div>
  </form>
</div>
<div class="clr"></div>
</div>
</div>
<?=$this->load->view('/Common/common','',true);?>
<script>
    function deleteRecord(id)
    {
        var delete_meg = 'Are you sure want to delete this estimate?';
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
                        window.location.href="<?php echo base_url($main_content.'/deletedata');?>/"+id;
                        dialog.close();
                    }
                }]
            });
        }
</script>