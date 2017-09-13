
function myDateFormatter(dateObject)
{
    var d = new Date(dateObject);
    var day = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }

    var date = day + "/" + month + "/" + year;


    return date;
}

function generate_pdf()
{
    var base_url = window.location.origin;
    var pathArray = window.location.pathname.split('/');
    var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + '/generatePDF';
    var chkVal = [];
    if($('input[name="check[]"]').is(":checked")){
        $('input[name="check[]"]:checked').each(function () {
            if($(this).val() != ''){
                chkVal.push($(this).val());
                window.location.href = send_url + '?campaign_id=' + chkVal;
            }
        });
    }else {
         BootstrapDialog.show({
             title: campaign_report,
             message: generate_pdf_msg,
             buttons: [{
                label: close,
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
         });
    }
    
}

function searchRecord()
{
    var base_url = window.location.origin;
    var pathArray = window.location.pathname.split('/');
    var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + '/search';

    $("#frm_search_box").attr("action", send_url);
    $("#frm_search_box").submit();
}

function generate_csv()
{
    var base_url = window.location.origin;
    var pathArray = window.location.pathname.split('/');
    var send_url = base_url + "/" + pathArray[1] + "/" + pathArray[2] + '/generateCSV';
    var chkVal = [];
    if($('input[name="check[]"]').is(":checked")){
        $('input[name="check[]"]:checked').each(function () {
            if($(this).val() != ''){
                chkVal.push($(this).val());
                window.location.href = send_url + '?campaign_id=' + chkVal;
            }
        });
    }else {
        BootstrapDialog.show({
            title: campaign_report,
             message: generate_csv_msg,
             buttons: [{
                label: close,
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
         });
    }
}

$(document).ready(function () {
    $('#searchbtn').prop('disabled', true);
    $('#txt_search_field').keyup(function () {
        $('#searchbtn').prop('disabled', this.value == "" ? true : false);
    })
});



$(document).ready(function () {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        defaultDate: date_formate,
        defaultView: 'month',
        yearColumns: 2,
        selectable: false,
        selectHelper: true,
        //firstDay: 0,
        editable: false,
        eventLimit: true, // allow "more" link when too many events
        events: {
            url: grantview,
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



$(document).ready(function () {
    $('body').delegate('[data-toggle="ajaxModal"]', 'click', function (e) {
        $('#ajaxModal').remove();
        e.preventDefault();
        var $this = $(this)
            , $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href')
            , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
        $('body').append($modal);
        $modal.modal();
        var url=$remote+"?token="+generateFormToken;
        $modal.load(url);

    });

    $('body').on('click','#selecctall',function(e){
        if(this.checked) { // check select status
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });

    $("#typeofreport").on("change", function () {
        var optVal = $(this).val();
        //alert(optVal);
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
    }).on('changeDate', function (selected) {
        endDate = new Date(selected.date.valueOf());
        endDate.setDate(endDate.getDate(new Date(selected.date.valueOf())));
        $('.search_creation_date').datepicker('setEndDate', endDate);
    });


});

function activat_campaign_archive(link)
{
    var myarray = new Array;
    var i = 0;
    $(boxes).each(function () {
        myarray[i] = this.value;
        i++;
    });
    $.ajax({
        type: "post",
        data: {'myarray': myarray},
        url: url,
        success: function (data) {
            window.location.href = link;
            //$("#common_div").html(data);
            return true;
        }
    });
}

function getDateByFilter()
{
    var start_date = $("#search_creation_date").val();
    var end_date = $("#creation_end_date").val();
    var isOff = $("#archive_camp").parent('div').hasClass('off');
    if (start_date != '' && end_date != '') {
        if(isOff){
            var send_url = 'CampaignReport/index';
            $.ajax({
                type: "POST",
                url: send_url,
                data: {'start_date': start_date,
                    'end_date': end_date,
                    'result_type': 'ajax',
                    'sortby': $("#sortby").val(),
                    'sortfield': 'campaign_id',
                    'allflag': 'changesearch',
                    'searchtext': $("#searchtext").val(),
                },
                success: function (response)
                {
                    $("#common_div").html(response);
                }
            });
        }else{
            var send_url = 'CampaignReport/index';
            $.ajax({
                type: "POST",
                url: send_url,
                data: {
                    'archive_campaign': valSwitch,
                    'start_date': start_date,
                    'end_date': end_date,
                    'result_type': 'ajax',
                    'sortby': $("#sortby").val(),
                    'sortfield': 'campaign_archive_id',
                    'allflag': 'changesearch',
                    'searchtext': $("#searchtext").val(),
                },
                success: function (response)
                {
                    $("#common_div").html(response);
                }
            });
        }
    }else{
        BootstrapDialog.show({
            message: select_both_date,
            buttons: [{
                label: close,
                action: function(dialogItself){
                    dialogItself.close();
                }
            }]
        });
    }
}

function getArchiveCampaignData(elm)
{
    var start_date = $("#search_creation_date").val();
    var end_date = $("#creation_end_date").val();
    var isOff = $(elm).parent('div').hasClass('off');
    if (isOff)
    {
        $("#sortfield").val('campaign_id');
        var send_url = 'CampaignReport/index';
        $.ajax({
            type: "POST",
            url: send_url,
            data: {'result_type': 'ajax',
                'allflag': 'changesearch',
                'searchtext': $("#searchtext").val(),
                'start_date': start_date,
                'end_date': end_date,
                'sortfield': $("#sortfield").val(),
                'sortby': $("#sortby").val()
            },
            success: function (response)
            {
                $("#common_div").html(response);
            }
        });
    }
    else
    {
        $("#sortfield").val('campaign_archive_id');
        var valSwitch = $('#archive_camp').val();
        var send_url = 'CampaignReport/index';
        $.ajax({
            type: "POST",
            url: send_url,
            data: {
                'start_date': start_date,
                'end_date': end_date,
                'archive_campaign': valSwitch,
                'result_type': 'ajax',
                'sortfield': 'campaign_archive_id',
                'allflag': 'changesearch',
                'searchtext': $("#searchtext").val(),
                'sortby': $("#sortby").val()
            },
            success: function (response)
            {
                $("#common_div").html(response);
            }
        });
    }
}

var valSwitch = $('#archive_camp').val();
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

    valSwitch = '';
    var elm = $('#archive_camp').parent('div').hasClass('off');
    if (elm)
    {
        valSwitch = '';
    }
    else
    {
        valSwitch = 'on';
    }
    var uri_segment = $("#uri_segment").val();

    /* Start Added By Sanket*/
    var request_url = '';
    if (uri_segment == 0)
    {
        request_url = url+'/'+uri_segment;
    } else
    {
        request_url = url_data+'/'+ uri_segment;
    }
    /* End Added By Sanket*/


    $.ajax({
        type: "POST",
        url: request_url,
        data: {
            result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), allflag: allflag, 'archive_campaign': valSwitch, start_date: $("#search_creation_date").val(), end_date: $("#creation_end_date").val()
        },
        success: function (html) {

            $("#common_div").html(html);
        }
    });
    return false;
}
function reset_data()
{
    $("#searchtext").val("");
    $("#sortfield").val('');
    $("#sortby").val('');
    data_search('all');
}

function reset_date_data(){
    $("#search_creation_date").val("");
    $("#creation_end_date").val('');
    data_search('all');
}

function reset_data_list(data)
{
    $("#searchtext").val(data);
    apply_sorting('', '');
    data_search('all');
}



function apply_sorting(sortfilter, sorttype)
{

    $("#sortfield").val(sortfilter);
    $("#sortby").val(sorttype);
    data_search('changesorting');
}
//pagination
$('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {
    valSwitch = '';
    var elm = $('#archive_camp').parent('div').hasClass('off');
    if (elm)
    {
        valSwitch = '';
    }
    else
    {
        valSwitch = 'on';
    }
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), 'archive_campaign': valSwitch, start_date: $("#search_creation_date").val(), end_date: $("#creation_end_date").val()
        },
        success: function (html) {
            $("#common_div").html(html);
        }
    });
    return false;

});

/*on and off button function */
function toggle_show(className, obj) {
    var $input = $(obj);
    if ($input.prop('checked')) $(className).show();
    else $(className).hide();
}



