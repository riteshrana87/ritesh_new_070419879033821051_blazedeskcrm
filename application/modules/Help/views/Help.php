<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$formAction = 'insertdata';
$path = $lead_view . '/' . $formAction;
?>
<!-- Example row of columns -->

<div class="row">
  <div class="col-md-6 col-md-6">
  
    <ul class="breadcrumb nobreadcrumb-bg">
	
      <li><a href="<?php echo base_url().'Support';?>">
        <?= lang('support') ?>
        </a></li>
      <li><a href="<?php echo base_url().'Ticket';?>">
        <?= lang('ticket') ?>
        </a></li>
    
    </ul>
  </div>
  <!-- Search: Start -->
 <div class="col-xs-12 col-md-3 pull-right text-right col-md-offset-3">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
      
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <!-- Search: End -->
  <div class="clr"></div>
  <div class="col-xs-12 col-md-9">
    <div class="col-xs-6 col-md-6 no-left-pad">
      <h3 class="white-link">
        <?= lang('ticket') ?>
      </h3>
    </div>
    <div class="col-xs-6 col-md-6 no-left-pad text-right">
      <?PHP if (checkPermission('Ticket', 'add')) { ?>
      <a href="<?php echo base_url('Ticket/add'); ?>" data-toggle="ajaxModal" class="btn btn-white add_lead" aria-hidden="true" data-refresh="true">
      <?= $this->lang->line('create_new_ticket') ?>
      </a>
      <?php } ?>
    </div>
	
    <div class="clr"></div>
    <?php if($this->session->flashdata('msg')){ ?>
    <div class='alert alert-success text-center'>
      <?php  echo $this->session->flashdata('msg'); ?>
    </div>
    <?php } ?>
    <?php if($this->session->flashdata('error')){ ?>
    <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
    <?php } ?>
    <div class="clr"></div>
    <div class="whitebox" id="common_div">
      
       <div class="clr"></div>
    </div>
     <div class="clr"></div>
  </div>
  <div class="col-md-12 col-md-3">
    <div class=" whitebox">
      <div class="col-md-6 col-xs-6"> <span class="font-3em greencol"><b><?php echo $total_ticket; ?></b></span> </div>
      <div class="col-md-6 col-xs-6 no-left-pad">
        <p class="font-1em pad_top10"><b>
          <?= lang('total') ?>
          <br/>
          <?= lang('ticket') ?>
          </b></p>
      </div>
      <div class="clr"></div>
      <div class="blackbottom"></div>
      <div class="clr"></div>
      <br/>
      <form>
        <div class="col-md-12 col-xs-12">
          <label><b>
            <?= lang('filter_options') ?>
            </b></label>
          <div class="form-group">
            <select name="search_branch_id" class="form-control selectpicker" id="search_branch_id">
              <option value="">
              <?= $this->lang->line('branche') ?>
              </option>
              <?php if (isset($branch_data) && count($branch_data) > 0) { ?>
              <?php foreach ($branch_data as $branch) { ?>
              <option value="<?php echo $branch['branch_id']; ?>"><?php echo $branch['branch_name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <select name="search_prospect_owner_id" class="form-control selectpicker" id="serch_prospect_owner_id">
              <option value="">
              <?= $this->lang->line('select_prospect_owner') ?>
              </option>
              <?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
              <?php foreach ($prospect_owner as $prospect) { ?>
              <option value="<?php echo $prospect['contact_id']; ?>"><?php echo $prospect['contact_name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <select name="status" class="form-control" id='status'>
              <option value="">
              <?= $this->lang->line('status') ?>
              </option>
              <option value="1" >
              <?= $this->lang->line('active') ?>
              </option>
              <option value="0">
              <?= $this->lang->line('inactive') ?>
              </option>
            </select>
          </div>
          <label><b>
            <?= $this->lang->line('value_between') ?>
            </b></label>
          <div class="clr"></div>
          <div class="col-md-5 col-xs-5 no-left-pad">
            <div class="form-group">
              <input type="text" class="form-control" name="start_value" id='start_value' />
            </div>
          </div>
          <div class="col-md-2 col-xs-2">
            <?= $this->lang->line('and') ?>
          </div>
          <div class="col-md-5 col-xs-5 no-right-pad">
            <div class="form-group">
              <input type="text" class="form-control" name="end_value" id='end_value'/>
            </div>
          </div>
          <div class="clr"></div>
          <label><b>
            <?= lang('creation_date_between'); ?>
            </b></label>
          <div class="clr"></div>
          <div class="col-md-6 col-xs-6 no-left-pad">
            <div class="form-group">
              <div class='input-group date search_creation_date' >
                <input type="text" class="form-control search_creation_date" placeholder="" id="search_creation_date" name="search_creation_date" onkeydown="return false">
                <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
            </div>
          </div>
          <div class="col-md-6 col-xs-6 no-right-pad">
            <div class="form-group">
              <div class='input-group date creation_end_date' >
                <input type="text" class="form-control creation_end_date" placeholder="" id="creation_end_date" name="creation_end_date" onkeydown="return false">
                <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
            </div>
          </div>
          <div class="clr"></div>
          <div class="clr"></div>
          <label><b>
            <?= lang('contact_date_between'); ?>
            </b></label>
          <div class="clr"></div>
          <div class="col-md-6 col-xs-6 no-left-pad">
            <div class="form-group">
              <div class='input-group date search_contact_date' >
                <input type="text" class="form-control search_contact_date" placeholder="" id="search_contact_date" name="search_contact_date" onkeydown="return false">
                <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
            </div>
          </div>
          <div class="col-md-6 col-xs-6 no-right-pad">
            <div class="form-group">
              <div class='input-group date contact_end_date' >
                <input type="text" class="form-control contact_end_date" placeholder="" id="contact_end_date" name="contact_end_date" onkeydown="return false">
                <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
            </div>
          </div>
          <div class="clr"></div>
          <div class="pad-10 text-center"> <a href="#" class="width-100 btn small-white-btn2">
            <?= lang('import_csv'); ?>
            </a> </div>
        </div>
        <div class="clr"></div>
      </form>
    </div>
    <div class="clr"></div>
  </div>
  <div class="clr"></div>
</div>
<div class="clr"></div>

<?=$this->load->view('/Common/common','',true);?>

<script>
    $(document).ready(function () {
        $('.search_creation_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.creation_end_date').datepicker('setStartDate', startDate);
        });
        
        $('.creation_end_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        });
        
        $('.search_contact_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.contact_end_date').datepicker('setStartDate', startDate);
        });
        
        $('.contact_end_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        });
    });
	 function showimagepreview(input)
    {
        console.log(input);
        $('.upload_recent').remove();
        var url = '<?php echo base_url();?>';
        $.each(input.files, function(a,b){
            var rand = Math.floor((Math.random()*100000)+3);
            var arr1 = b.name.split('.');
            var arr= arr1[1].toLowerCase();
            var filerdr = new FileReader();
            var img = b.name;
            filerdr.onload = function(e) {
                var template = '<div class="eachImage upload_recent" id="'+rand+'">';
                var randtest = 'delete_row("' +rand+ '")';
                template += '<a id="delete_row" class="remove_drag_img" onclick='+randtest+'>×</a>';
                if(arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif'){
                    template += '<span class="preview" id="'+rand+'"><img src="'+e.target.result+'"><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                }else{
                    template += '<span class="preview" id="'+rand+'"><div><img src="'+url+'/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">'+img+'</p><span class="overlay"><span class="updone"></span></span>';
                }
                template += '<input type="hidden" name="file_data[]" value="'+b.name+'">';
                template += '</span>';
                $('#dragAndDropFiles').append(template);
            }
            filerdr.readAsDataURL(b);
        });
        var maximum = input.files[0].size/1024;

    }
	
	function delete_row(rand) {
        jQuery('#' + rand).remove();
    }
</script>
