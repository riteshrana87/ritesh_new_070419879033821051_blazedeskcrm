<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$path = $contact_view;

?>

<!-- Example row of columns -->
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
         <?php echo $this->breadcrumbs->show();?>
    </div>
    <!-- Search: Start -->
    <div class="col-xs-12 col-md-3 pull-right text-right col-md-offset-3 col-lg-3 col-sm-6">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                        <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?= lang('EST_LISTING_SEARCH_FOR') ?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                        <span class="input-group-btn">
                          <span class="input-group-btn">
                            <button onclick="data_search('changesearch')" class="btn btn-default"  title="<?= $this->lang->line('search') ?>"><?= $this->lang->line('common_search_title') ?> <i class="fa fa-search fa-x"></i></button>
                            <button class="btn btn-default howler flt" title="fg" onclick="reset_data()" title="Reset"><?= $this->lang->line('common_reset_title') ?><i class="fa fa-refresh fa-x"></i></button> 
                        </span></div>
                    <!-- /input-group -->
                </div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <!-- Search: End -->

    <div class="clearfix visible-xs-block"></div>

    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">

        <div class="row">
            <div class="col-xs-12 col-md-6 col-sm-4 col-lg-6">
                <h3 class="white-link"><?= lang('contacts') ?></h3>
            </div>
            <div class="col-xs-12 col-md-6 col-sm-8 col-lg-6 pull-right text-right">
                <?php if (checkPermission('Contact', 'add')) { ?><a data-href="<?php echo base_url() . 'Contact/addrecord'; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?= lang('create') ?>" class="btn btn-white" ><?= $this->lang->line('create_new_contact') ?></a><?php } ?>&nbsp;&nbsp;
                <?php if (checkPermission('Contact', 'add')) { ?><a data-href="<?php echo base_url() . 'Contact/importContact'; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?= lang('import_contact') ?>" class="btn btn-white" ><?= $this->lang->line('import_contact') ?></a><?php } ?>
                <?php if (checkPermission('Contact', 'add')) { ?><a title="<?= lang('export_contact') ?>" class="btn btn-white" onclick="exportContact();"><?= $this->lang->line('export_contact') ?></a><?php } ?>
            </div>
            

            <div class="clr"></div>
            <?php if ($this->session->flashdata('msg')) { ?>
                <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('msg'); ?></div>
            <?php } ?>
            <?php if ($this->session->flashdata('error')) { ?>
                <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
            <?php } ?>
        </div>
        <div class="clr"></div>
        <div class="whitebox" id="common_div">
            <?= $this->load->view('SupportContact/ajax_list.php', '', true); ?>
            <div class="clr"></div>
        </div>
    </div>

    <div class="col-sm-12 col-md-3 col-lg-3 col-xs-12">
        <div class=" whitebox">
            <div class="col-md-6 col-xs-6"> <span class="font-3em greencol"><b id="total_contact"><?php echo $total_contact; ?></b></span> </div>
            <div class="col-md-6 col-xs-6 no-left-pad">
                <p class="font-1em pad_top10"><b><?= lang('total') ?><br/>
                        <?= lang('contact') ?></b></p>
            </div>
            <div class="clr"></div>
            <div class="blackbottom"></div>
            <div class="clr"></div>
            <br/>
            
           <?php
            $attributes = array("name" => "search_form", "id" => "search_form", 'data-parsley-validate' => "");
            echo form_open_multipart($path, $attributes);
            ?>
                <div class="col-md-12 col-xs-12">
                    <label><b><?= lang('filter_options') ?></b></label>
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
                        <select name="search_company_id" class="form-control chosen-select" id="search_company_id" onchange="data_search();">
                    <option value=""><?= $this->lang->line('select_company') ?></option>

                    <?php if (isset($company_data) && count($company_data) > 0) { ?>
                        <?php foreach ($company_data as $company_data) { ?>
                            <option value="<?php echo $company_data['company_id']; ?>" <?php  if(!empty($company_show_id) && $company_show_id == $company_data['company_id']) { echo "selected='selected'"; } ?>><?php echo $company_data['company_name']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                    </div>
                    <div class="form-group">
                        <select name="search_ownwer_id" class="form-control chosen-select" id="search_ownwer_id" onchange="data_search();">
                    <option value=""><?= $this->lang->line('CONTACT_OWNER') ?></option>

                    <?php if (isset($company_owner) && count($company_owner) > 0) { ?>
                        <?php foreach ($company_owner as $company_owner_data) { ?>
                            <option value="<?php echo $company_owner_data['login_id']; ?>" <?php  if(!empty($contact_owner_id) && $contact_owner_id == $company_owner_data['login_id']) { echo "selected='selected'"; } ?>><?php echo ucfirst($company_owner_data['firstname'])." ".ucfirst($company_owner_data['lastname']); ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
                    </div>
                    <div class="form-group">
                    <select name="search_status" class="form-control chosen-select" id='search_status' onchange="data_search();">
                        <option value=""><?= $this->lang->line('status') ?></option>
                        <option value=1 <?php  if(($status_show!=="") && $status_show == 1) { echo "selected='selected'"; } ?>><?= $this->lang->line('active') ?></option>
                        <option value=0 <?php  if(($status_show!="") && $status_show == 0) { echo "selected='selected'"; } ?>><?= $this->lang->line('inactive') ?></option>
                    </select> 
                    </div>
                    
                    <div class="clr"></div>
                    <label><b><?= lang('creation_date_between'); ?></b></label>
                    <div class="clr"></div>
                     <div class="col-md-6 col-xs-6 no-left-pad">
                    <div class="form-group">
                        <div class='input-group date search_creation_date' >
                            <input type="text" class="form-control search_creation_date" placeholder="" id="search_creation_date" name="search_creation_date" value="<?php if(!empty($search_creation_date_show)) { echo date('m/d/Y',strtotime($search_creation_date_show)); } ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 no-right-pad">
                    <div class="form-group">
                        <div class='input-group date creation_end_date' >
                            <input type="text" class="form-control searchAjax creation_end_date" placeholder="" id="creation_end_date" name="creation_end_date" value="<?php if(!empty($creation_end_date_show)) { echo date('m/d/Y',strtotime($creation_end_date_show)); } ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="clr"></div>
                
                <div class="clr"></div>
                <div class="pad-10 text-center">
                    <input type="button" value="<?= lang('reset'); ?>" name="reset" class="width-100 btn small-white-btn2" onclick="reset_form()">
<!--                    <button class="width-100 btn small-white-btn2" type="submit"><?= lang('export_lead'); ?>
                    </button>-->
                </div>
                </div>
      <div class="clr"></div>
       <?php echo form_close(); ?>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <!--
    <div class="col-md-2 col-new-71 no-left-pad">
        <a class="btn btn-white full-width" data-href="<?php echo base_url('Account/add'); ?>" data-toggle="ajaxModal"><?php echo $this->lang->line('create_new_account') ?></a>
    </div>

    <div class="col-md-2 col-new-71">
        <a class="btn btn-white full-width" href="<?php echo base_url('Account'); ?>"><?php echo $this->lang->line('manage_account') ?></a>
    </div>

    <div class="col-md-2 col-new-71">
        <a class="btn btn-white full-width" data-href="<?php echo base_url('Lead/add'); ?>" data-toggle="ajaxModal"><?php echo $this->lang->line('create_new_lead') ?></a>
    </div>
    <div class="col-md-2 col-new-71">
        <a class="btn btn-white full-width" href="<?php echo base_url('Lead'); ?>"><?php echo $this->lang->line('manage_leads') ?></a> 
    </div>
    <div class="col-md-2 col-new-71">
        <a class="btn btn-white full-width" data-href="<?php echo base_url('Opportunity/add'); ?>" data-toggle="ajaxModal"><?php echo $this->lang->line('create_new_opportunity') ?></a>
    </div>
    <div class="col-md-2 col-new-71">
        <a class="btn btn-white full-width" href="<?php echo base_url('Opportunity'); ?>"><?php echo $this->lang->line('manage_opportunities') ?></a>
    </div>
    <div class="col-md-2 col-new-71">
        <a href="#" class="width-100 btn small-white-btn">
         <?php echo $this->lang->line('manage_reports') ?>
        </a>
    </div>
    -->
    <div class="clr"></div>
</div>

<script type="text/javascript">
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
                $('#total_contact').text($('#total_contact_count').val());
            }
        });
        return false;
    }
    function reset_form(){
        $(".chosen-select").val('').trigger("chosen:updated");
        $('#search_branch_id').val('');
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
                $('#total_contact').text($('#total_contact_count').val());
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
    
    function exportContact()
    {
        var export_url = '<?php echo base_url()."Contact/exportContact";?>';
        var delete_meg ="<?php echo $this->lang->line('CONFIRM_EXPORT_CONTACT');?>";
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

</script>
