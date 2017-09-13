<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'insertdata';
$path = $main_content . '/' . $formAction;
?>
<!-- Example row of columns -->
  <div class="row">
    <div class="col-md-6 col-lg-6 col-xs-12 col-sm-6">
           <?php echo $this->breadcrumbs->show(); ?>  
    </div>
      <div class="col-xs-12 col-md-3 col-lg-3 pull-right text-right col-md-offset-3 col-sm-6">
          <div class="row">
              <div class="col-xs-1 settings col-md-1 col-sm-1 col-lg-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
              <div class="col-xs-10 col-md-10 col-sm-10 col-lg-10 text-right search-top pull-right">
					<div class="navbar-form navbar-left pull-right">
						<div class="input-group">
                          <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                          <input type="text" name="searchtext" id="searchtext" class="form-control" aria-controls="example1" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR'); ?>" value="<?=!empty($searchtext)?$searchtext:''?>">
							<span class="input-group-btn">
							 <button onclick="data_search('changesearch')" class="btn btn-default"  title="<?php echo lang('search'); ?>"><?=$this->lang->line('common_search_title')?> <i class="fa fa-search fa-x"></i></button>
							 <button class="btn btn-default howler flt" title="<?php echo lang('reset') ?>" onclick="reset_data()"><?=$this->lang->line('common_reset_title')?><i class="fa fa-refresh fa-x"></i></button>
							</span>
						</div>
					</div>
              </div>

              <div class="clr"></div>
          </div>
          <div class="clr"></div>
      </div>
  <div class="clr"></div>
  <div class="col-xs-12 col-md-9 col-lg-9 col-sm-12" >
	<div class="row"><div class="col-xs-5 col-md-6 col-lg-6 no-left-pad"><h3 class="white-link"><?php echo lang('estimates'); ?></h3></div>
	<div class="col-xs-7 col-md-6 col-lg-6 no-right-pad text-right">
			<?php if(checkPermission('Estimates','add')){?>
            <a class="btn btn-white" href="<?php echo base_url().''.$estimate_view.'/add';?>"><?php echo lang('EST_LIST_CREATE_NEW_ESTIMATE');?> </a>
			<?php }?>
	</div>
	<div class="clr"></div></div>
	<?php if ($this->session->flashdata('msg')) { ?>
                <?php echo $this->session->flashdata('msg'); ?>
            <?php } ?>
      <div class="whitebox" id="common_div">
          <?=$this->load->view('Estimates/ajax_list.php','',true);?>
          <div class="clr"></div>
      </div>
  </div>
  <div class="col-xs-12 col-md-3 col-lg-3 col-sm-12">
  <div class=" whitebox">
  <div class="col-md-6 col-xs-6 col-lg-6"> <span class="font-3em greencol"><b id="totalAccountText"><?php echo $totalEstimate;?></b></span> </div>
  <div class="col-md-6 col-xs-6 no-left-pad col-lg-6">
    <p class="font-1em pad_top10">
	<b><?php echo lang('total'); ?><br/>
      <?php echo lang('EST_LABEL_ESTIMATE');?></b></p>
  </div>
  <div class="clr"></div>
  <div class="blackbottom"></div>
  <div class="clr"></div>
  <br/>
  <form id="search_form" name="search_form">
	<div class="col-md-12 col-xs-12 col-lg-12">
      <label><b><?php echo lang('EST_LISTING_LABEL_FILTER_OPTIONS');?></b></label>
	   <div class="form-group">
        <select class="form-control chosen-select" id="search_status" name="search_status" onchange="data_search();">
          <option value=""><?php echo lang('EST_LISTING_LABEL_FI_OPT_STATUS');?></option>
          <option value="0" <?php  if(($search_status!=="") && $search_status == 0) { echo "selected='selected'"; } ?>><?php echo lang('inactive');?></option>
          <option value="1" <?php  if(($search_status!=="") && $search_status == 1) { echo "selected='selected'"; } ?>><?php echo lang('active');?></option>
		  <option value="2" <?php  if(($search_status!=="") && $search_status == 2) { echo "selected='selected'"; } ?>><?php echo lang('EST_LISTING_LABEL_FI_OPT_DRAFT');?></option>
		  <!--<option value="3">Delete</option>-->
        </select>
      </div>
	  <div class="form-group">
			<select name="search_company_id" class="form-control chosen-select" id="search_company_id" onchange="data_search();">
				<optgroup label="Company">
					<option value=""><?= $this->lang->line('select_company') ?></option>
					<?php if (isset($company_data) && count($company_data) > 0) { ?>
						<?php foreach ($company_data as $companyInfo) { ?>
							<option value="<?php echo 'company_'.$companyInfo['company_id']; ?>"><?php echo $companyInfo['company_name']; ?></option>
						<?php } ?>
					<?php } ?>
				</optgroup>
				<optgroup label="Client">
					<?php if (isset($clientArray) && count($clientArray) > 0) { ?>
						<?php foreach ($clientArray as $clientInfo) { ?>
							<option value="<?php echo 'client_'.$clientInfo['prospect_id']; ?>"><?php echo $clientInfo['prospect_name']; ?></option>
						<?php } ?>
					<?php } ?>
				</optgroup>
			</select>
		</div>
      <div class="form-group">
		<select name="search_contact_id" class="form-control chosen-select" id="search_contact_id" onchange="data_search();">
		<option value=""><?= $this->lang->line('select_contact') ?></option>
		<?php if (isset($owner) && count($owner) > 0) { ?>
			<?php foreach ($owner as $owner) { ?>
				<option value="<?php echo $owner['contact_id']; ?>" <?php  if(!empty($contact_show_id) && $contact_show_id == $owner['contact_id']) { echo "selected='selected'"; } ?>><?php echo $owner['contact_name']; ?></option>
			<?php } ?>
		<?php } ?>
		</select>
	  </div>
	  <div class="form-group">
		<select name="prospect_owner_id" class="form-control chosen-select" id="prospect_owner_id" onchange="data_search();">
				<option value="">
					<?php echo $this->lang->line('select_prospect_owner'); ?>
				</option>
				<?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
					<?php foreach ($prospect_owner as $prospect) { ?>
						<option value="<?php echo $prospect['login_id']; ?>"><?php echo ucfirst($prospect['firstname']) . " " . ucfirst($prospect['lastname']); ?></option>
							<?php } ?>
						<?php } ?>
		</select>
	  </div>
	  <label><b><?php echo lang('EST_LISTING_LABEL_VALUE_BETWEEN'); ?></b></label>
      <div class="clr"></div>
      <div class="col-md-5 col-xs-5 no-left-pad col-lg-5">
        <div class="form-group">
          <input class="form-control" name="fstValue" id="fstValue" onchange="data_search();" min='0' type="number" />
        </div>
      </div>
      <div class="col-md-2 col-xs-2 col-lg-2"> <?php echo lang('and'); ?> </div>
      <div class="col-md-5 col-xs-5 col-lg-5 no-right-pad">
        <div class="form-group">
          <input class="form-control" name="sndValue" id="sndValue" oninput="data_search();" type="number"/>
        </div>
      </div>
    <div class="clr"></div>
    <label><b><?php echo lang('EST_LISTING_LABEL_CREATION_DATE_BETWEEN');?></b></label>
    <div class="clr"></div>
   <div class="col-md-6 col-xs-6 col-lg-6 no-left-pad">
      <div class="form-group">
        <div class='input-group date search_creation_date' >
			<input type="text" class="form-control search_creation_date" placeholder="<?php echo lang('start_date'); ?>" id="search_creation_date" name="search_creation_date" value="<?php
			if (!empty($search_creation_date_show)) {
				echo date('m/d/Y', strtotime($search_creation_date_show));
			}
			?>" onkeydown="return false">
			<span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
		</div>
    </div>
      <div class="col-md-6 col-xs-6 col-lg-6 no-right-pad">
      <div class="form-group">
		<div class='input-group date creation_end_date' >
			<input type="text" class="form-control searchAjax creation_end_date" placeholder="<?php echo lang('END_DATE'); ?>" id="creation_end_date" name="creation_end_date" value="<?php
			if (!empty($creation_end_date_show)) {
				echo date('m/d/Y', strtotime($creation_end_date_show));
			}
			?>" onkeydown="return false">
			<span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
		</div>
    </div>
    <div class="clr"></div>
	<div class="pad-10 text-center">
		<input type="button" value="<?= lang('reset'); ?>" name="reset" class="width-100 btn small-white-btn2" onclick="reset_form()">
		<!-- <a href="<?php echo base_url('Estimates');?>" onclick="return reset_form();" class="width-100 btn small-white-btn2"><?= lang('reset'); ?>
		  </a> -->
    </div>
	 </div>
    <div class="clr"></div>
  </form>
</div>
<div class="clr"></div>
</div>
</div>
<script>
    function deleteRecord(id)
    {
        var delete_meg ="<?php echo lang('cnfrm_delete_estimate');?>";
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
	$(document).ready(function () {
        $('.search_creation_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.creation_end_date').datepicker('setStartDate', startDate);
            data_search();
        });
        $('.creation_end_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            data_search();
        });
        $('.search_contact_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.contact_end_date').datepicker('setStartDate', startDate);
            data_search();
        });
        $('.contact_end_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            data_search();
        });
        $('.chosen-select').chosen();
    });
</script>
<?=$this->load->view('/Common/common','',true);?>
<script type="text/javascript">
    $(document).ready(function () {

        //serch by enter
        $('#searchtext').keyup(function (event)
        {
            if (event.keyCode == 13) {
                data_search('changesearch');
            }
        });
    });
	function filterByValue()
	{
		var fstVal = parseInt($('#fstValue').val());
		var sndVal = parseInt($('#sndValue').val());
		/*if(fstVal != "" && isNaN(fstVal) == true)
		{
		}*/
		if(fstVal != "" && sndVal != "" && isNaN(fstVal) != true && isNaN(sndVal) != true)
		{
			alert(fstVal+ '-' +sndVal);
		}
	}
	function searchFrmFields()
	{
		var frmFieldArray = [];
		var fstVal = parseInt($('#fstValue').val());
		var sndVal = parseInt($('#sndValue').val());
		var passVal = "";
		if(fstVal != "" && sndVal != "" && isNaN(fstVal) != true && isNaN(sndVal) != true)
		{
			frmFieldArray.push = '&sndVal=' + sndVal + '&fstVal=' + fstVal;
		}
		alert($('#search_form').serialize());
		return frmFieldArray;
	}
    //Search data
    function data_search(allflag)
    {
	//Function for filter By Value
		//filterByValue();
        var uri_segment = $("#uri_segment").val();
        /* Start Added By Sanket*/
        var request_url = '';
        if (uri_segment == 0)
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/index/' + uri_segment;
        } else
        {
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/' + uri_segment;
        }
        /* End Added By Sanket*/
	//Get First Box Value and Second Box Value
		var fstVal = parseInt($('#fstValue').val());
		var sndVal = parseInt($('#sndValue').val());
		var passVal = "";
		if(fstVal != "" && sndVal != "" && isNaN(fstVal) != true && isNaN(sndVal) != true)
		{
			passVal = '&sndVal=' + sndVal + '&fstVal=' + fstVal;
		}
		//console.log(searchFrmFields());
		$.ajax({
            type: "POST",
            url: request_url,
            data: $('#search_form').serialize() + '&result_type=ajax&perpage=' + $("#perpage").val() + '&searchtext=' + $("#searchtext").val() + '&sortfield=' + $("#sortfield").val() + '&sortby=' + $("#sortby").val() + '&allflag=' + allflag + '&searchAction=' + 'rightSideSearch',
            success: function (html) {

                $("#common_div").html(html);
                $('#totalAccountText').text($('#hdnTotalCount').val());
            }
        });
        return false;
    }
    function reset_form(){
        $(".chosen-select").val('').trigger("chosen:updated");
        $('#search_status').val('');
        $('#fstValue').val('');
        $('#sndValue').val('');
        $('#search_creation_date').val('');
        $('#creation_end_date').val('');
        $('#search_contact_date').val('');
        $('#contact_end_date').val('');
        $("#searchtext").val("");
        apply_sorting('', '');
        data_search('all');
    }
    function reset_data()
    {
        $("#searchtext").val("");
        apply_sorting('', '');
        data_search('all');
    }
    function reset_data_list(data)
    {
        $("#searchtext").val(data);
        apply_sorting('', '');
        data_search('all');
    }
    function changepages()
    {
        data_search('');
    }
    function apply_sorting(sortfilter, sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search('changesorting');
    }
    //pagination
    $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {
		//alert("Click on pagination");
		//alert($('#search_form').serialize());
        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: $('#search_form').serialize() + '&result_type=ajax&perpage=' + $("#perpage").val() + '&searchtext=' + $("#searchtext").val() + '&sortfield=' + $("#sortfield").val() + '&sortby=' + $("#sortby").val(),
            /*
             beforeSend: function () {
             $('#common_div').block({message: 'Loading...'});
             },
             */
            success: function (html) {
                $("#common_div").html(html);
                $('#totalAccountText').text($('#hdnTotalCount').val());
                //    $.unblockUI();
            }
        });
        return false;
    });
    function deletepopup(id, name)
    {
        if (id == '0')
        {
            var boxes = $('input[name="check[]"]:checked');
            if (boxes.length == '0')
            {
                $.alert({
                    title: 'Alert!',
                    //backgroundDismiss: false,
                    content: "<strong> <?php echo lang('select_record_delete'); ?><strong>",
                    confirm: function () {
                    }
                });
                return false;
            }
        }
        if (id == '0')
        {
            var msg = '<?php echo lang('cnfrm_delete_record'); ?>';
        }
        else
        {
            var msg = '<?php echo lang('cnfrm_delete'); ?>' + name + '?';
        }
        $.confirm({
            title: 'Confirm!',
            content: "<strong> " + msg + " " + "<strong>",
            confirm: function () {
                delete_all_multipal('delete', id);
            },
            cancel: function () {
            }
        });
    }
    /*on and off button function */
    function toggle_show(className, obj) {
        var $input = $(obj);
        if ($input.prop('checked'))
            $(className).show();
        else
            $(className).hide();
    }
</script>