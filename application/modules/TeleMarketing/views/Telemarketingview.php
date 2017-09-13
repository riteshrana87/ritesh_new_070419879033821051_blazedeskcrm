<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$formAction = !empty($editRecord)?'updatedata':'insertdata'; 
$formAction = 'insertdata';
$path = $lead_view . '/' . $formAction;
?>
<!-- Example row of columns -->

<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6">
         <?php echo $this->breadcrumbs->show();?>
    </div>
  <!-- Search: Start -->
 <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 pull-right text-right ">
    <div class="row">
      <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
      <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
        <div class="navbar-form navbar-left pull-right" id="searchForm">
          <div class="input-group">
            <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
            <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?php echo lang('EST_LISTING_SEARCH_FOR');?>" value="<?=!empty($searchtext)?$searchtext:''?>">
            <span class="input-group-btn">
            <button onclick="data_search('changesearch')" class="btn btn-default" type="button" title="<?= $this->lang->line('search') ?>"><i class="fa fa-search fa-x"></i></button>&nbsp;
             
             <button class="btn btn-default" title="<?= $this->lang->line('reset') ?>" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
            </span> </div>
          <!--  input-group -->
        </div>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
    <!-- Search: End -->
    <div class="clr"></div>
    <?php if($this->session->flashdata('msg')){ ?>
        <div class='alert alert-success text-center'>
            <?php  echo $this->session->flashdata('msg'); ?>
        </div>
    <?php }?>
  <div class="clr"></div>
  <div class="col-xs-12 col-md-9">
    <div class="col-xs-6 col-md-4 col-sm-3 no-left-pad">
      <h3 class="white-link">
        <?= lang('TeleMarketing') ?>
      </h3>
    </div>
    <div class="col-xs-12 col-md-8 col-sm-9 no-left-pad text-right">
      <?PHP if (checkPermission('TeleMarketing', 'add')) { ?>
      <a data-href="<?php echo base_url('TeleMarketing/add'); ?>" data-toggle="ajaxModal" class="btn btn-white add_lead" aria-hidden="true" data-refresh="true">
      <?= $this->lang->line('create_new_telemarketing') ?>
      </a>
      <?php } 
      if($role_type=='39'){
      ?>
      <a onclick="exportTeleMarketing();" title=" <?= lang('TeleMarketing_Report')?>" class="btn btn-white"><?= lang('TeleMarketing_Report')?></a>
      <?php }?>
      <?php if (checkPermission('TeleMarketing', 'add')) { ?><a data-href="<?php echo base_url() . 'TeleMarketing/importContact'; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?= lang('import_contact') ?>" class="btn btn-white" ><?= $this->lang->line('import_tele') ?></a><?php } ?>
    </div>

    <?php if($this->session->flashdata('error')){ ?>
    <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
    <?php } ?>
    <div class="clr"></div>
    <div class="whitebox" id="common_div">
      <?=$this->load->view('AjaxTelemarketing','',true);?>
       <div class="clr"></div>
    </div>
     <div class="clr"></div>
  </div>
   
  <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
    <div class=" whitebox">
      <div class="col-md-6 col-xs-6"> <span class="font-3em greencol"><b id="totaltele"><?php echo $total_tele; ?></b></span> </div>
      <div class="col-md-6 col-xs-6 no-left-pad">
        <p class="font-1em pad_top10"><b>
          <?= lang('total') ?>
          <br/>
          <?= lang('TeleMarketing') ?>
          </b></p>
      </div>
      <div class="clr"></div>
      <div class="blackbottom"></div>
      <div class="clr"></div>
      <br/>
      <form id="search_form" name="search_form">
        <div class="col-md-12 col-xs-12">
          <label><b>
            <?= lang('filter_options') ?>
            </b></label>
          <div class="form-group">
            <select name="select_status" class="form-control selectpicker" id="select_status" onchange="data_search();">
              <option value="">
                            <?= $this->lang->line('select_tele_status') ?> 
                        </option>
                        
                        <option value="1"><?= lang('pos_req');?></option>
                        <option value="2"><?= lang('pos_demo');?></option>
                        <option value="3"><?= lang('pos_became_client');?></option>
                        <option value="4"><?= lang('neg_not_int');?></option>
						<option value="5"><?= lang('voice');?></option>
						<option value="6"><?= lang('call_back');?></option>
                    
            </select>
          </div>
		 
          <div class="pad-10 text-center"> <a href="javascript:void(0)" class="width-100 btn small-white-btn2 reset">
           <?php echo lang('reset');?>
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

<script>
    $(document).ready(function () {
		$('.reset').click(function(){
			
			$('#select_status').val('');
			$('#search_assign').val('');
			$('.search_creation_date').val('');
			$('.creation_end_date').val('');
			data_search();
		});
        $('#search_creation_date').datepicker({
            dateFormat: 'yy/mm/dd',
            autoclose: true
        }).on('changeDate', function (selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('.creation_end_date').datepicker('setStartDate', startDate);
			 data_search();
        });
        
        $('#creation_end_date').datepicker({
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
    //Search data
    function data_search(allflag)
    {
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

        $.ajax({
            type: "POST",
            url: request_url,
            data: $('#search_form').serialize() + '&result_type=ajax&perpage=' + $("#perpage").val() + '&searchtext=' + $("#searchtext").val() + '&sortfield=' + $("#sortfield").val() + '&sortby=' + $("#sortby").val() + '&allflag=' + allflag,
            success: function (html) {

                $("#common_div").html(html);
                $('#totaltele').text($('#total_tele_count').val());
            }
        });
        return false;
    }
    function exportTeleMarketing()
    {
        var export_url = '<?php echo base_url()."TeleMarketing/exportTeleMarketing";?>';
        var delete_meg ="<?php echo lang('CONFIRM_tele_marketing');?>";
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
                        window.location.href = export_url;
                        dialog.close();
                    }
                }]
            });

    }
    function reset_form(){
        $(".chosen-select").val('').trigger("chosen:updated");
        $('#select_status').val('');
		$('#search_assign').val('');
        $('#serch_prospect_owner_id').val('');
        $('#search_status').val('');
        $('#start_value').val('');
        $('#end_value').val('');
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
    $('body').on('click', '#click', '#common_tb ul.pagination a.ajax_paging', function (e) {
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
                $('#totaltele').text($('#total_tele_count').val());
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
                    content: "<strong> Please select record(s) to delete.<strong>",
                    confirm: function () {
                    }
                });
                return false;
            }
        }
        if (id == '0')
        {
            var msg = 'Are you sure want to delete Record(s)';
        }
        else
        {
            var msg = 'Are you sure want to delete ' + name + '?';
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
    
    /* */
   $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {
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
            //    $.unblockUI();
            }
        });
        return false;

    });


</script>
