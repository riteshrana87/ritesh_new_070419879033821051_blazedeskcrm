if(linkedin_api_key != '' && linkedin_company_id != '') {
    // this function is to save the Linkedin data in cookies for ferther use
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        //exdayss = 1700;
        d.setTime(d.getTime() + (exdays * 60 * 1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    function onLinkedInLoad()
    {
        document.getElementById("linkedin_count").innerHTML = '<img src='+ajax_loader+'>';
        IN.API.Raw("/companies/"+linkedin_company_id+":(num-followers)").result( function(result)
        {
            document.getElementById("linkedin_count").innerHTML = result.numFollowers;
        })
        IN.API.Raw("/companies/"+linkedin_company_id+"/historical-follow-statistics?start-timestamp=1422144000000&time-granularity=month&end-timestamp=1458864000000&format=json")
            .result( function(result)
            {
                //console.log("/companies/"+linkedin_company_id+"/historical-follow-statistics?start-timestamp=1422144000&end-timestamp=1458864000&time-granularity=month&format=json");
                console.log(result);
                setCookie('LinkedinStat', JSON.stringify(result), 100);
                //document.getElementById("linkedin_count").innerHTML = result.numFollowers;
            })
            .error( function(error) {  /*do nothing*/  } )
        ;
    }
 }

    $(function () {
        $('#start_date').datepicker();
        $('#end_date').datepicker();
        $("#start_date").on("dp.change", function (e) {
            $('#end_date').data("DatePicker").minDate(e.date);
        });
        $("#end_date").on("dp.change", function (e) {
            $('#start_date').data("DatePicker").maxDate(e.date);
        });

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

    $('body').delegate('[data-toggle="ajaxModal"]', 'click',
        function (e) {
            $('#ajaxModal').remove();
            e.preventDefault();
            var $this = $(this)
                , $remote = $this.data('remote') || $this.attr('data-href') || $this.attr('href')
                , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
            $('body').append($modal);
            $modal.modal();
            var url=$remote+"?token="+generateFormToken;
            $modal.load(url);
            //$("body").addClass("modal-open");
            $("body").css("padding-right", "0 !important");
        }
    );


function delete_campaign(id)
{
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_meg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function(dialog) {
                    $.ajax({
                        type: "POST",
                        url: delete_url,
                        //dataType: 'json',
                        data: {'single_remove_id':id},
                        success: function(data){
                            //alert(data);
                            $.ajax({
                                type: "POST",
                                url: index_url +'/'+data,
                                data: {
                                    result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
                                },
                                success: function(html){
                                    var msg = campign_del_msg;
                                    $(".show-success").html(msg);
                                    $("#common_div").html(html);
                                    $( "div" ).removeClass( "hidden");
                                    $(".show-success").show();
                                    setTimeout(function () {
                                        $('.alert').fadeOut('5000');
                                    }, 3000);
                                }
                            });
                            return false;
                        }
                    });
                    dialog.close();
                }
            }]
        });
}

function get_twitter_count()
{
    var send_url = 'Marketingcampaign/get_twitter_follower_count';

    $.ajax({
        type: "POST",
        url: send_url,
        data: {'get_twitter_follower_count': 'get_twitter_follower_count'},
        success: function (data)
        {
            $('#twiiter_count').html(data);
        }
    });
}

function get_facebook_count()
{
    var send_url = 'Marketingcampaign/facebook_count';

    $.ajax({
        type: "POST",
        url: send_url,
        data: {'facebook_count': 'facebook_count'},
        success: function (data)
        {
            $('#facebook_count').html(data);
        }
    });
}

function get_visitors_count()
{
    var send_url = 'Marketingcampaign/getWebsiteVisitorCount';

    $.ajax({
        type: "POST",
        url: send_url,
        //  data: {'facebook_count': 'facebook_count'},
        success: function (data)
        {
            //	data= data-1;
            $('#visitors_count').html(data);
        }
    });
}

function delete_campaign_group(campaign_group_id)
{
    var delete_url = "Campaigngroup/deletedata?id=" + campaign_group_id ;
    BootstrapDialog.show(
        {
            title: Information,
            message: Campaigngroup_delete_msg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function(dialog) {
                    window.location.href = delete_url;
                    dialog.close();
                }
            }]
        });

}
if(brand_engagement == 'Marketingcampaign'){
    get_twitter_count();
    get_facebook_count();
    get_visitors_count();
}
//$('#visitors_count').html(15);
