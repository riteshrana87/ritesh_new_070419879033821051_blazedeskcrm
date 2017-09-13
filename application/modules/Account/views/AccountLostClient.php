<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$formAction = 'lostClient';
$path = $account_view . '/' . $formAction;
?>

<!-- Example row of columns -->
<div class="row">
    <div class="col-md-6 col-sm-6 col-lg-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url(); ?>"><?= lang('crm') ?></a></li>
            <li><a href="<?php echo base_url() . 'SalesOverview'; ?>"><?= lang('sales_overview') ?></a></li>
            <li><a href="<?php echo base_url() . 'Account'; ?>"><?= lang('accounts') ?></a></li>
            <li class="active"><?= lang('LOST_CLIENT') ?></li>
        </ul>
    </div>
    <!-- Search: Start -->
    <div class="col-md-3 col-sm-6 col-lg-3 pull-right text-right col-md-offset-3">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                        <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?= $this->lang->line('EST_LISTING_SEARCH_FOR') ?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                        <span class="input-group-btn">
                            <button onclick="data_search('changesearch')" title="<?php echo lang('search'); ?>" class="btn btn-default" type="button"><i class="fa fa-search fa-x"></i></button>&nbsp;

                            <button class="btn btn-default" title="button" title="<?php echo lang('reset'); ?>" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
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
    <div class="col-xs-12 col-md-9 col-sm-12 col-lg-9">
        <div class="row"><div class="col-xs-6 col-md-6 no-left-pad"><h3 class="white-link"><?= lang('LOST_CLIENT') ?></h3></div>
            <div class="col-xs-6 col-md-6 no-left-pad text-right">      


            </div>
            <div class="clr"></div></div>
        <?php if ($this->session->flashdata('msg')) { ?>
            <div class='alert alert-success text-center'> <?php echo $this->session->flashdata('msg'); ?></div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>
            <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
        <?php } ?>

        <div class="clr"></div>
        <div class="whitebox" id="common_div">
            <?= $this->load->view('AjaxAccountLostClientList', '', true); ?>
        </div>
    </div>
    <div class="col-md-3 col-lg-3 col-sm-12 col-xs-12">
        <div class=" whitebox">
            <div class="col-md-6 col-xs-6"> <span class="font-3em greencol"><b id="totalLostAccount"><?php echo $total_account; ?></b></span> </div>
            <div class="col-md-6 col-xs-6 no-left-pad">
                <p class="font-1em pad_top10"><b><?= lang('total') ?><br/>
                        <?= lang('account') ?></b></p>
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
                    <select name="search_branch_id" class="form-control chosen-select" id="search_branch_id" onchange="data_search();">
                        <option value=""><?= $this->lang->line('branche') ?></option>
                        <?php if (isset($branch_data) && count($branch_data) > 0) { ?>
                            <?php foreach ($branch_data as $branch) { ?>
                                <option value="<?php echo $branch['branch_id']; ?>" <?php
                                if (!empty($branch_show_id) && $branch_show_id == $branch['branch_id']) {
                                    echo "selected='selected'";
                                }
                                ?>><?php echo $branch['branch_name']; ?></option>
    <?php } ?>
<?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="search_prospect_owner_id" class="form-control chosen-select" id="serch_prospect_owner_id" onchange="data_search();">
                        <option value=""><?= $this->lang->line('select_prospect_owner') ?></option>

                        <?php if (isset($prospect_owner) && count($prospect_owner) > 0) { ?>
                            <?php foreach ($prospect_owner as $prospect) { ?>
                                <option value="<?php echo $prospect['login_id']; ?>" <?php
                                        if (!empty($prospect_show_id) && $prospect_show_id == $prospect['login_id']) {
                                            echo "selected='selected'";
                                        }
                                        ?>><?php echo ucfirst($prospect['firstname']) . " " . ucfirst($prospect['lastname']); ?></option>
    <?php } ?>
<?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="search_status" class="form-control chosen-select" id='search_status' onchange="data_search();">
                        <option value=""><?= $this->lang->line('status') ?></option>
                        <option value="1" <?php
                                if (($status_show !== "") && $status_show == 1) {
                                    echo "selected='selected'";
                                }
                                ?>><?= $this->lang->line('active') ?></option>
                        <option value="0" <?php
                                if (($status_show != "") && $status_show == 0) {
                                    echo "selected='selected'";
                                }
                                ?>><?= $this->lang->line('inactive') ?></option>
                    </select> 
                </div>
                <label><b><?= $this->lang->line('value_between') ?></b></label>
                <div class="clr"></div>
                <div class="col-md-5 col-xs-5 no-left-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" name="start_value" id='start_value' value="<?php
                        if (!empty($start_value_show)) {
                            echo $start_value_show;
                        }
                        ?>" onblur="data_search();"/>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2"><?= $this->lang->line('and') ?> </div>
                <div class="col-md-5 col-xs-5 no-right-pad">
                    <div class="form-group">
                        <input type="text" class="form-control" name="end_value" id='end_value' value="<?php
                        if (!empty($end_value_show)) {
                            echo $end_value_show;
                        }
                        ?>" onblur="data_search();"/>
                    </div>
                </div>

                <div class="clr"></div>
                <label><b><?= lang('creation_date_between'); ?></b></label>
                <div class="clr"></div>
                <div class="col-md-6 col-xs-6 no-left-pad">
                    <div class="form-group">
                        <div class='input-group date search_creation_date' >
                            <input type="text" class="form-control search_creation_date" placeholder="" id="search_creation_date" name="search_creation_date" value="<?php
                            if (!empty($search_creation_date_show)) {
                                echo date('m/d/Y', strtotime($search_creation_date_show));
                            }
                        ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 no-right-pad">
                    <div class="form-group">
                        <div class='input-group date creation_end_date' >
                            <input type="text" class="form-control searchAjax creation_end_date" placeholder="" id="creation_end_date" name="creation_end_date" value="<?php
                                   if (!empty($creation_end_date_show)) {
                                       echo date('m/d/Y', strtotime($creation_end_date_show));
                                   }
                                   ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="clr"></div>
                <label><b><?= lang('contact_date_between'); ?></b></label>
                <div class="clr"></div>
                <div class="col-md-6 col-xs-6 no-left-pad">
                    <div class="form-group">
                        <div class='input-group date search_contact_date'>
                            <input type="text" class="form-control searchAjax search_contact_date" placeholder="" id="search_contact_date" name="search_contact_date" value="<?php
                                   if (!empty($search_contact_date_show)) {
                                       echo date('m/d/Y', strtotime($search_contact_date_show));
                                   }
                                   ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 no-right-pad">
                    <div class="form-group">
                        <div class='input-group date contact_end_date'>
                            <input type="text" class="form-control searchAjax contact_end_date" placeholder="" id="contact_end_date" name="contact_end_date" value="<?php
                                   if (!empty($contact_end_date_show)) {
                                       echo date('m/d/Y', strtotime($contact_end_date_show));
                                   }
                                   ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="clr"></div>
                <div class="pad-10 text-center">
                    <input type="reset" value="<?= lang('reset'); ?>" name="reset" class="width-100 btn small-white-btn2" onclick="reset_form()">
                    <!--                    <button class="width-100 btn small-white-btn2" type="submit"><?= lang('export_opportunity'); ?>
                                        </button>-->
                </div>
            </div>

            <div class="clr"></div>
<?php echo form_close(); ?>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
</div>		
<script>

    function delete_request(prospect_id, redirect_link) {

        var delete_url = "Account/deletedata/?id=" + prospect_id + "&link=" + redirect_link;
        var delete_meg = "<?php echo $this->lang->line('confirm_delete_client'); ?>";

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
                                window.location.href = delete_url;
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
    function resetForm() {
        window.location.href = 'Account/lostClient';
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
            request_url = '<?php echo $this->config->item('base_url') . '/' . $this->viewname ?>/lostClient/' + uri_segment;
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
                $('#totalLostAccount').text($('#total_lostaccount_count').val());
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
                $('#totalLostAccount').text($('#total_lostaccount_count').val());
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
                /*$.alert({
                 title: 'Alert!',
                 //backgroundDismiss: false,
                 content: "<strong> Please select record(s) to delete.<strong>",
                 confirm: function () {
                 }
                 });
                 */
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
            var msg = '<?php echo lang("cnfrm_delete"); ?> ' + name + '?';
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

</script>

