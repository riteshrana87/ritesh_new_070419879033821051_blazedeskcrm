<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$redirect_link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REDIRECT_URL'];
$this->load->view('SupportReport/SupportList.php', '', true);
?>

<div class="row">
    <div class="col-md-6 col-md-6">
        <ul class="breadcrumb nobreadcrumb-bg">
            <li><a href="<?php echo base_url() . 'Support'; ?>">
                    <?= lang('support') ?>
                </a></li>
            <li class="active">
                <?= lang('support_report') ?>
            </li>
        </ul>
    </div>
    <div class="clr"></div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3"> 
        <h3 class="white-link">
            <?= lang('support_report') ?>
        </h3>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 ">
        <div class="row">
            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 width-23">
                <div class="form-group">
                    <select id="search_status"  name="search_status" class="chosen-select form-control" placeholder="Select a Status">
                        <option value=""><?= lang('select_status') ?></option>
                        <?php
                        if (!empty($status)) {
                            foreach ($status as $row) {
                                ?>
                                <option  value="<?php echo $row['status_id'] ?>"><?php echo ucfirst($row['status_name']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-sm-2 col-md-3 col-lg-3 width-23">
                <div class="form-group">
                    <select id="search_type"  name="search_type" class="chosen-select form-control" placeholder="Select a Type">
                        <option value=""><?= lang('select_type') ?></option>
                        <?php
                        if (!empty($type)) {
                            foreach ($type as $row) {
                                ?>
                                <option  value="<?php echo $row['support_type_id'] ?>"><?php echo ucfirst($row['type']); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2 width-23">
                <div class="form-group">
                    <div class='input-group date search_creation_date' >
                        <input type="text" class="form-control searchAjax" placeholder="<?= lang('create_date') ?>" id="search_creation_date" name="search_creation_date" onkeydown="return false">
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2 width-23">
                <div class="form-group">
                    <div class='input-group date creation_end_date' >
                        <input type="text" class="form-control searchAjax creation_end_date" placeholder="<?= lang('due_date') ?>" id="creation_end_date" name="creation_end_date" onkeydown="return false">
                        <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span> </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 text-right filtr-btn">
                <button class="btn btn-white" type="button" name="btn_type_of_rep" id="btn_type_of_rep"><?= lang('CR_FILTER_BTN') ?></button>
                <button class="btn btn-white" type="button" name="btn_type_of_rep" id="btn_type_of_rep" onclick="reset_form()"><?= lang('reset'); ?></button>
            </div>

            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12"> <?php echo $this->session->flashdata('msg'); ?>

        <div class="clr"></div>
        <!-- Listing of Product Table: Start -->
        <div  class="whitebox" id="common_div">
            <?php $this->load->view('SupportList'); ?>
        </div>
        <!-- Listing of Product Table: End -->
    </div>
    <div class="col-sm-12">
        <div class="row"><div class="col-md-3 col-lg-2 col-sm-4 col-xs-12 col-sm-offset-2 col-md-offset-3 col-lg-offset-4">
                <div class="text-center"> 
                    <a data-href="javascript:;" onclick="generate_pdf();" class="btn btn-white full-width" ><span class="glyphicon glyphicon-cloud-download"></span>
                        <?= lang('GENERATE_PDF') ?>  
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-lg-2 col-sm-4 col-xs-12">
                <div class="text-center">
                    <a data-href="javascript:;" onclick="generate_csv();" class="btn btn-white full-width" ><span class="glyphicon glyphicon-cloud-download"></span>
                        <?= lang('GENERATE_CSV') ?>  
                    </a>
                </div>
            </div></div>
    </div>
    <div class="clr"></div>
</div>
<div class="clr"></div>
<script>
    $(document).ready(function () {
        $('body').delegate('[data-toggle="ajaxModal"]', 'click', function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
            var $this = $(this)
                    , $remote = $this.data('remote') || $this.attr('href')
                    , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal();
            $modal.load($remote);
        });

        $("#typeofreport").on("change", function () {
            var optVal = $(this).val();

            var send_url = 'CampaignReport/index';
            $.ajax({
                type: "POST",
                url: send_url,
                data: {'type_of_report': optVal,
                    'result_type': 'ajax'},
                success: function (response)
                {
                    $("#common_div").html(response);
                }
            });
        });

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

        //pagination
        $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {

            $.ajax({
                type: "POST",
                url: $(this).attr('href'),
                data: {
                    result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), start_date: $("#search_creation_date").val(), end_date: $("#creation_end_date").val()
                },
                success: function (html) {
                    $("#common_div").html(html);
                }
            });
            return false;

        });

        $("#btn_type_of_rep").on("click", function () {
            var start_date = $("#search_creation_date").val();
            var end_date = $("#creation_end_date").val();
            var search_status = $("#search_status").val();
            var search_type = $("#search_type").val();
            var send_url = 'SupportReport/index';
            $.ajax({
                type: "POST",
                url: send_url,
                data: {'start_date': start_date,
                    'end_date': end_date,
                    'search_status': search_status,
                    'search_type': search_type,
                    'result_type': 'ajax'},
                success: function (response)
                {
                    $("#common_div").html(response);
                }
            });
        });
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            defaultDate: '<?= date("Y-m-d") ?>',
            defaultView: 'month',
            yearColumns: 2,
            selectable: false,
            selectHelper: true,
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: {
                url: '<?= base_url("SalesOverview/grantview") ?>',
                success: function (data) {
                },
                error: function () {
                    $('#script-warning').show();
                }}
            ,
            eventRender: function (event, element) {
                element.attr("data-toggle", "ajaxModal")
            },
        });

    });

    function getArchiveCampaignData(elm)
    {
        var isOff = $(elm).parent('div').hasClass('off');
        if (isOff)
        {
            var send_url = 'CampaignReport/index';
            $.ajax({
                type: "POST",
                url: send_url,
                data: {'result_type': 'ajax'},
                success: function (response)
                {
                    $("#common_div").html(response);
                }
            });
        }
        else
        {
            var valSwitch = $('#archive_camp').val();
            var send_url = 'CampaignReport/index';
            $.ajax({
                type: "POST",
                url: send_url,
                data: {'archive_campaign': valSwitch,
                    'result_type': 'ajax'},
                success: function (response)
                {
                    $("#common_div").html(response);
                }
            });
        }
    }
    
    function reset_form() {
      
        $('#search_status').val('');
        $('#search_type').val('');
        $('#search_creation_date').val('');
        $('#creation_end_date').val('');
        $("#searchtext").val("");
        apply_sorting('', '');
        data_search('all');
    }
    
    function generate_pdf()
    {
        var base_url = window.location.origin;
        var pathArray = window.location.pathname.split('/');
        var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + "/" + pathArray[3] + '/generatePDF';
        window.location.href = send_url;

    }
    function generate_csv()
    {
        var base_url = window.location.origin;
        var pathArray = window.location.pathname.split('/');
        var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + "/" + pathArray[3] + '/generateCSV';
        window.location.href = send_url;

    }
</script>
<?= $this->load->view('/Common/common', '', true); ?>
