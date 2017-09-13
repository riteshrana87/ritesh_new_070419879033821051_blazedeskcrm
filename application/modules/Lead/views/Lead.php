<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$path = $lead_view;
?>

<!-- Example row of columns -->
<div class="row">
    <div class="col-lg-6 col-md-3 col-sm-6 col-xs-12">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url(); ?>">
                    <?= lang('crm') ?>
                </a></li>
            <li><a href="<?php echo base_url() . 'SalesOverview'; ?>">
                    <?= lang('sales_overview') ?>
                </a></li>
            <li class="active">
                <?= lang('leads') ?>
            </li>
        </ul>
    </div>
    <!-- Search: Start -->
    <div class="col-xs-12 col-md-3 col-lg-3 col-sm-6 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                        <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?= $this->lang->line('EST_LISTING_SEARCH_FOR') ?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                        <span class="input-group-btn">
                            <button onclick="data_search('changesearch')" title="<?= $this->lang->line('search') ?>" class="btn btn-default" type="button"><i class="fa fa-search fa-x"></i></button>&nbsp;

                            <button class="btn btn-default" title="<?= $this->lang->line('reset') ?>" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
                        </span> </div>
                    <!-- /input-group -->
                </div>
            </div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <!-- Search: End -->
    <div class="clr"></div>
    <div class="col-xs-12 col-md-9 col-lg-9 col-sm-12">
        <?php if ($this->session->flashdata('msg')) { ?>
            <div class='alert alert-success text-center'>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        <?php } ?>

        <?php if ($this->session->flashdata('error')) { ?>
            <div class='alert alert-danger text-center'>
                <?php echo $this->session->flashdata('error'); ?>
            </div>
        <?php } ?>
        <div class="row"> <div class="col-xs-12 col-sm-6 col-md-6 no-left-pad">
                <h3 class="white-link">
                    <?= lang('lead') ?>
                </h3>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6 no-left-pad text-right">
                <?PHP //if (checkPermission('Lead', 'add')) {  ?>
              <!--<a data-href="<?php echo base_url('Lead/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true" class="btn btn-white add_lead" ><?= $this->lang->line('create_new_lead') ?></a>-->
                <?php //}  ?>
                <?php if (checkPermission('Lead', 'add')) { ?><a data-href="<?php echo base_url() . 'Lead/importLeads'; ?>" aria-hidden="true" data-refresh="true" data-toggle="ajaxModal" title="<?= lang('IMPORT_LEADS') ?>" class="btn btn-white"><?= $this->lang->line('IMPORT_LEADS') ?></a><?php } ?>
                <?php if (checkPermission('Lead', 'add')) { ?><a title="<?= lang('EXPORT_LEADS') ?>" class="btn btn-white" onclick="exportLeads();"><?= $this->lang->line('EXPORT_LEADS') ?></a><?php } ?>
            </div>
            <div class="clr"></div></div>

        <div class="clr"></div>
        <div class="whitebox" id="common_div">
            <?= $this->load->view('AjaxLeadList', '', true); ?>
            <div class="clr"></div>
        </div>
    </div>
    <div class="col-xs-12 col-md-3 col-lg-3 col-sm-12 col-xs-12">
        <div class=" whitebox">
            <div class="col-md-6 col-xs-6"> <span class="font-3em greencol"><b id="total_lead"><?php echo $total_lead; ?></b></span> </div>
            <div class="col-md-6 col-xs-6 no-left-pad">
                <p class="font-1em pad_top10"><b>
                        <?= lang('total') ?>
                        <br/>
                        <?= lang('lead') ?>
                    </b></p>
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
                    <select name="search_status" class="form-control chosen-select" id='search_status' onchange="data_search();">
                        <option value=""><?= $this->lang->line('status') ?></option>
                        <option value="1" <?php if (($status_show !== "") && $status_show == 1) {
                echo "selected='selected'";
            } ?>><?= $this->lang->line('active') ?></option>
                        <option value="0" <?php if (($status_show != "") && $status_show == 0) {
                echo "selected='selected'";
            } ?>><?= $this->lang->line('inactive') ?></option>
                    </select> 
                </div>
                <div class="form-group">
                    <select name="search_branch_id" class="form-control chosen-select" id="search_branch_id" onchange="data_search();">
                        <option value=""><?= $this->lang->line('branche') ?></option>
                        <?php if (isset($branch_data) && count($branch_data) > 0) { ?>
                            <?php foreach ($branch_data as $branch) { ?>
                                <option value="<?php echo $branch['branch_id']; ?>" <?php if (!empty($branch_show_id) && $branch_show_id == $branch['branch_id']) {
                            echo "selected='selected'";
                        } ?>><?php echo $branch['branch_name']; ?></option>
    <?php } ?>
<?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="search_company_id" class="form-control chosen-select" id="search_company_id" onchange="data_search();">
                        <option value=""><?= $this->lang->line('select_prospect_owner') ?></option>

                        <?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
                                    <?php foreach ($prospect_owner as $prospect) { ?>
                                <option value="<?php echo $prospect['login_id']; ?>" <?php
                                        if (!empty($edit_record[0]['prospect_owner_id']) && $edit_record[0]['prospect_owner_id'] == $prospect['login_id']) {
                                            echo 'selected';
                                        }
                                        ?>><?php echo ucfirst($prospect['firstname']) . " " . ucfirst($prospect['lastname']); ?></option>
    <?php } ?>
<?php } ?>
                    </select>
                </div>

                <label><b><?= $this->lang->line('value_between') ?></b></label>
                <div class="clr"></div>
                <div class="col-md-5 col-xs-5 no-left-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" name="start_value" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" id='start_value' value="<?php if (!empty($start_value_show)) {
    echo $start_value_show;
} ?>" onblur="data_search();"/>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2"><?= $this->lang->line('and') ?> </div>
                <div class="col-md-5 col-xs-5 no-right-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" name="end_value" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 0" id='end_value' value="<?php if (!empty($end_value_show)) {
    echo $end_value_show;
} ?>" onblur="data_search();"/>
                    </div>
                </div>

                <div class="clr"></div>
                <label><b><?= lang('creation_date_between'); ?></b></label>
                <div class="clr"></div>
                <div class="col-md-6 col-xs-6 no-left-pad">
                    <div class="form-group">
                        <div class='input-group date' id="search_creation_date">
                            <input type="text" class="form-control search_creation_date" placeholder="<?= lang('start_date'); ?>" id="search_creation_date" name="search_creation_date" value="<?php if (!empty($search_creation_date_show)) {
    echo configDateTime($search_creation_date_show);
} ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 no-right-pad">
                    <div class="form-group">
                        <div class='input-group date' id="creation_end_date">
                            <input type="text" class="form-control creation_end_date" placeholder="<?= lang('END_DATE'); ?>" id="creation_end_date" name="creation_end_date" value="<?php if (!empty($creation_end_date_show)) {
    echo configDateTime($creation_end_date_show);
} ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="clr"></div>
                <label><b><?= lang('contact_date_between'); ?></b></label>
                <div class="clr"></div>
                <div class="col-md-6 col-xs-6 no-left-pad">
                    <div class="form-group">
                        <div class='input-group date' id="search_contact_date">
                            <input type="text" class="form-control search_contact_date" placeholder="<?= lang('start_date'); ?>" id="search_contact_date" name="search_contact_date" value="<?php if (!empty($search_contact_date_show)) {
    echo configDateTime($search_contact_date_show);
} ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 no-right-pad">
                    <div class="form-group">
                        <div class='input-group date' id="contact_end_date">
                            <input type="text" class="form-control contact_end_date" placeholder="<?= lang('END_DATE'); ?>" id="contact_end_date" name="contact_end_date" value="<?php if (!empty($contact_end_date_show)) {
    echo configDateTime($contact_end_date_show);
} ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="pad-10 text-center">
                    <input type="button" value="<?= lang('reset'); ?>" name="reset" class="width-100 btn small-white-btn2" onclick="reset_form()">

                </div>
            </div>
            <div class="clr"></div>
        <?php echo form_close(); ?>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
</div>
    
    <div class="row">
        <?php if (checkPermission('Lead', 'add')) { ?>
            <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" data-href="<?php echo base_url('Lead/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><?php echo $this->lang->line('create_new_lead') ?></a> </div>
<?php } ?>
        <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" href="<?php echo base_url('Lead'); ?>"><?php echo $this->lang->line('manage_leads') ?></a> </div>
<?php if (checkPermission('Opportunity', 'add')) { ?>
            <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" data-href="<?php echo base_url('Opportunity/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><?php echo $this->lang->line('create_new_opportunity') ?></a> </div>
<?php } ?>
        <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" href="<?php echo base_url('Opportunity'); ?>"><?php echo $this->lang->line('manage_opportunities') ?></a> </div>

<?php if (checkPermission('Account', 'add')) { ?>
            <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" data-href="<?php echo base_url('Account/add'); ?>" data-toggle="ajaxModal" aria-hidden="true" data-refresh="true"><?php echo $this->lang->line('create_new_account') ?></a> </div>
<?php } ?>
        <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a class="btn btn-white full-width" href="<?php echo base_url('Account'); ?>"><?php echo $this->lang->line('manage_account') ?></a> </div>
        <div class="col-md-2 col-new-71 col-xs-12 lang-widthauto"> <a href="#" class="btn btn-white full-width"><?php echo $this->lang->line('manage_reports') ?></a> </div>
    </div>
    </div>
    <script>
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
                endDate = new Date(selected.date.valueOf());
                endDate.setDate(endDate.getDate(new Date(selected.date.valueOf())));
                $('.search_creation_date').datepicker('setEndDate', endDate);
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
                endDate = new Date(selected.date.valueOf());
                endDate.setDate(endDate.getDate(new Date(selected.date.valueOf())));
                $('.search_contact_date').datepicker('setEndDate', endDate);
                data_search();
            });
            $('.chosen-select').chosen();
        });
        //image upload
        function showimagepreview(input)
        {
            console.log(input);
            $('.upload_recent').remove();
            var url = '<?php echo base_url(); ?>';
            $.each(input.files, function (a, b) {
                var rand = Math.floor((Math.random() * 100000) + 3);
                var arr1 = b.name.split('.');
                var arr = arr1[1].toLowerCase();
                var filerdr = new FileReader();
                var img = b.name;
                filerdr.onload = function (e) {
                    var template = '<div class="eachImage upload_recent" id="' + rand + '">';
                    var randtest = 'delete_row("' + rand + '")';
                    template += '<a id="delete_row" title="<?php echo lang('delete') ?>" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                    if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                        template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                    } else {
                        template += '<span class="preview" id="' + rand + '"><div><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                    }
                    template += '<input type="hidden" name="file_data[]" value="' + b.name + '">';
                    template += '</span>';
                    $('#dragAndDropFiles').append(template);
                }
                filerdr.readAsDataURL(b);

            });

            var maximum = input.files[0].size / 1024;
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
                    $('#total_lead').text($('#total_lead_count').val());
                }
            });
            return false;
        }
        function reset_form() {
            $(".chosen-select").val('').trigger("chosen:updated");
            $('#search_branch_id').val('');
            $('#serch_prospect_owner_id').val('');
            $('#search_status').val('');
            $('#start_value').val('');
            $('#end_value').val('');
            $('.search_creation_date').val('');
            $('.creation_end_date').val('');
            $('.search_contact_date').val('');
            $('.contact_end_date').val('');
            $('.creation_end_date').datepicker('setStartDate', '');
            $('.search_creation_date').datepicker('setEndDate', '');
            $('.contact_end_date').datepicker('setStartDate', '');
            $('.search_contact_date').datepicker('setEndDate', '');
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
                    $('#total_lead').text($('#total_lead_count').val());
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
                    var delete_meg = "<?php echo lang('select_record_delete'); ?>";
                    BootstrapDialog.show(
                            {
                                title: '<?php echo $this->lang->line('Information'); ?>',
                                message: delete_meg,
                                buttons: [{
                                        label: '<?php echo $this->lang->line('ok'); ?>',
                                        action: function (dialog) {
                                            dialog.close();
                                        }
                                    }]
                            });

                    return false;
                }
            }
            if (id == '0')
            {
                var msg = '<?php echo lang("cnfrm_delete_record"); ?>';
            }
            else
            {
                var msg = '<?php echo lang("cnfrm_delete"); ?>  ' + name + '?';
            }
            $.confirm({
                title: '<?php echo lang("confirm"); ?>',
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

        function exportLeads()
        {
            var export_url = '<?php echo base_url() . "Lead/exportLeads"; ?>';
            var delete_meg = "<?php echo lang('CONFIRM_EXPORT_LEADS'); ?>";
            BootstrapDialog.show(
                    {
                        title: '<?php echo $this->lang->line('Information'); ?>',
                        message: delete_meg,
                        buttons: [{
                                label: '<?php echo $this->lang->line('COMMON_LABEL_CANCEL'); ?>',
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }, {
                                label: '<?php echo $this->lang->line('ok'); ?>',
                                action: function (dialog) {
                                    window.location.href = export_url;
                                    dialog.close();
                                }
                            }]
                    });

        }

    </script>
