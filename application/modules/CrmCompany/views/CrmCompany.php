<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url(); ?>"><?= lang('crm') ?></a></li>
            <li class="active"><?= lang('companies') ?></li>
        </ul>
    </div>
    <div class="col-xs-12 col-md-4 col-lg-3 col-sm-6 pull-right text-right ">
        <div class="row">
            <div class="col-xs-1 settings col-md-1 col-sm-1 text-right pull-right"><a href="#" title="<?php echo lang('settings'); ?>"><i class="fa fa-gear fa-2x"></i></a> </div>
            <div class="col-xs-10 col-md-10 col-sm-10 text-right search-top pull-right">
                <div class="navbar-form navbar-left pull-right" id="searchForm">
                    <div class="input-group">
                        <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?= !empty($uri_segment) ? $uri_segment : '0' ?>">
                        <input type="text" name="searchtext" id="searchtext"  class="form-control" placeholder="<?= $this->lang->line('EST_LISTING_SEARCH_FOR') ?>" value="<?= !empty($searchtext) ? $searchtext : '' ?>">
                        <span class="input-group-btn">
                            <button onclick="data_search('changesearch')" class="btn btn-default" title="<?= $this->lang->line('search') ?>" type="button"><i class="fa fa-search fa-x"></i></button>&nbsp;

                            <button class="btn btn-default" title="<?= $this->lang->line('reset') ?>" onclick="reset_data()"><i class="fa fa-refresh fa-x"></i></button>
                        </span> </div>
                    <!-- /input-group -->
                </div>
            </div>

            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>

    <div id="show_company_loader" class="text-center"> </div>
    <div class="clr"></div>
    <div class="col-xs-12 col-md-9">
         <?php if ($this->session->flashdata('msg')) { ?>
        <div class='alert alert-success text-center'>
            <?php echo $this->session->flashdata('msg'); ?>
        </div>
    <?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>
            <div class='alert alert-danger text-center'> <?php echo $this->session->flashdata('error'); ?></div>
        <?php } ?>
        <div class="row">
            <div class="col-xs-12 col-md-6 col-sm-6">
                <h3 class="white-link"><?= lang('companies') ?></h3>
            </div>
            <div class="col-xs-12 col-md-6 col-sm-6 pull-right text-right">
                <?PHP if (checkPermission('GetApiData', 'add')) { ?>
                    <a data-href="<?php echo base_url('GetApiData/GetCompanyReport'); ?>" data-toggle="ajaxModal" class="btn btn-white" id="report_data" aria-hidden="true" data-refresh="true">
                        <?php echo lang('company_report'); ?>
                    </a>
                <?php } ?>
                <?PHP if (checkPermission('CrmCompany', 'add')) { ?>
                    <a data-href="<?php echo base_url('CrmCompany/add'); ?>" data-toggle="ajaxModal" class="btn btn-white" aria-hidden="true" data-refresh="true">
                        <?= $this->lang->line('create_new_company') ?>
                    </a>
                <?php } ?>

            </div>

        </div>

        <div class="clr"></div>
        <div class="whitebox" id="common_div">
            <?= $this->load->view('AjaxCompanyList', '', true); ?>
        </div>
    </div>
    <div class="col-md-12 col-md-3 col-xs-12">
        <div class=" whitebox">
            <div class="col-md-6 col-xs-6"> <span class="font-3em greencol"><b id="totalCompanyText"><?php echo $total_company; ?></b></span> </div>
            <div class="col-md-6 col-xs-6 no-left-pad">
                <p class="font-1em pad_top10"><b><?= lang('total') ?><br/>
                        <?= lang('companies') ?></b></p>
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

                <div class="clr"></div>
                <div class="form-group">
                    <select name="search_country_id" class="form-control chosen-select" id="search_country_id" onchange="data_search();">
                        <option value=""><?= $this->lang->line('country') ?></option>

                        <?php if (isset($country_master) && count($country_master) > 0) { ?>
                            <?php foreach ($country_master as $country) { ?>
                                <option value="<?php echo $country['country_id']; ?>" <?php
                                if (!empty($country_show_id) && $country_show_id == $country['country_id']) {
                                    echo "selected='selected'";
                                }
                                ?>><?php echo $country['country_name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                    </select>
                </div>
                <label><b><?= lang('creation_date_between'); ?></b></label>
                <div class="clr"></div>
                <div class="col-md-6 col-xs-6 no-left-pad">
                    <div class="form-group">
                        <div class='input-group date'>
                            <input type="text" class="form-control search_creation_date" placeholder="<?= lang('start_date'); ?>" id="search_creation_date" name="search_creation_date" value="<?php
                            if (!empty($search_creation_date_show)) {
                                echo configDateTime($search_creation_date_show);
                            }
                            ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 no-right-pad">
                    <div class="form-group">
                        <div class='input-group date'>
                            <input type="text" class="form-control searchAjax creation_end_date" placeholder="<?= lang('END_DATE'); ?>" id="creation_end_date" name="creation_end_date" value="<?php
                            if (!empty($creation_end_date_show)) {
                                echo configDateTime($creation_end_date_show);
                            }
                            ?>" onkeydown="return false">
                            <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                    </div>
                </div>
                <div class="clr"></div>

                <div class="pad-10 text-center">
                    <input type="button" value="<?= lang('reset'); ?>" name="reset" class="width-100 btn small-white-btn2" onclick="reset_form()">

                </div>
            </div>
            <div class="clr"></div>
            </form>
        </div>
        <div class="clr"></div>
    </div>
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
            endDate = new Date(selected.date.valueOf());
            endDate.setDate(endDate.getDate(new Date(selected.date.valueOf())));
            $('.search_creation_date').datepicker('setEndDate', endDate);
            data_search();
        });

        $('.chosen-select').chosen();
    });
    function resetForm() {
        window.location.href = 'Contact';
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
                $('#totalCompanyText').text($('#total_company_count').val());
            }
        });
        return false;
    }
    function reset_form() {
        $(".chosen-select").val('').trigger("chosen:updated");
        $('#search_branch_id').val('');
        $('#search_country_id').val('');
        $('#search_status').val('');
        $('#search_creation_date').val('');
        $('#creation_end_date').val('');
        $('.creation_end_date').datepicker('setStartDate', '');
        $('.search_creation_date').datepicker('setEndDate', '');
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
                $('#totalAccountText').text($('#total_company_count').val());
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

<?php if ($this->session->flashdata('msg_data')) { ?>
        $(function () {
            var delete_meg = "<?php echo $this->session->flashdata('msg_data'); ?>";
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
        });
<?php } ?>

</script>