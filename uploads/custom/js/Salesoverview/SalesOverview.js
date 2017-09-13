
function reset_data()
{
    $("#search_input").val("");
    $('#searchForm #submit').click();
}

var placeholderDiv;
var sortorder;
$(function () {
    placeholder2 = '';
    $('#SalesOverviewDrag,.empty').sortable({
        connectWith: '.connectedSortable',
//            items: "div.sortableDiv",
//            handle: '.tab-header'
        cursor: 'move',
        placeholder: 'placeholder',
        forcePlaceholderSize: true,
        opacity: 0.4,
        stop: function (event, ui) {
            var group = ui
            var placeholderDiv = $(this).attr('id');
            var sortorder = [];
            $('#SalesOverviewDrag .sortableDiv').each(function () {
                sortorder.push($(this).attr('id'));
            });
            $.ajax({
                url: dashboardWidgetsOrder,
                type: "POST",
                dataType: "json",
                data: {'sortorder': sortorder},
                success: function (data)
                {
                    if (data.type == 'reset')
                    {
                        window.location.href = window.location.href;
                    }
                }

            });
        },
        receive: function (event, ui) {
            var group = ui
            var placeholderDiv = $(this).attr('id');
            var sortorder = [];
            $('#SalesOverviewDrag .sortableDiv').each(function () {
                sortorder.push($(this).attr('id'));
            });
            $.ajax({
                url: dashboardWidgetsOrder,
                type: "POST",
                dataType: "json",
                data: {'sortorder': sortorder},
                success: function (data)
                {
                    if (data.type == 'reset')
                    {
                        window.location.href = window.location.href;
                    }
                }

            });
        }
    });
});
$('body').delegate('.ResetWidget', 'click', function () {
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_meg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function (dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function (dialog) {
                    $.ajax({
                        url: dashboardWidgetsOrder_url,
                        type: "POST",
                        dataType: "json",
                        data: {'placeholder1': placeholderDiv, 'innerWidgets1': sortorder, 'placeholder2': placeholder2, 'innerWidgets2': sortorder2},
                        success: function (data)
                        {
                            if (data.type == 'reset')
                            {
                                window.location.href = window.location.href;
                            }
                            else
                            {

                            }

                        }

                    });
                    dialog.close();
                }
            }]
        });


});
/*
 * function is used to  for getting monthly comparision
 */
function getComparisionData(mnt)
{
    if (mnt != '')
    {
        $.ajax({
            url: getMonthlySalesData,
            data: {'month': mnt},
            type: "POST",
            dataType: 'json',
            success: function (d)
            {
                $('#container').highcharts({
                    chart: {
                        type: 'line'
                    },
                    credits: {
                        enabled: false
                    },
                    title: {
                        text: monthly_sales_comparision
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: false
                    },
                    yAxis: {
                        title: {
                            text: monthly_comparision
                        }
                    },
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                            enableMouseTracking: true
                        }
                    },
                    series: [{
                        name: d.currMonth,
                        data: (d.currMonthData)
                    }, {
                        name: d.nextMonth,
                        data: d.nextMonthData
                    }]
                });
            }

        })
    }
}
var config = {
    support: "*", // Valid file formats
    form: "demoFiler", // Form ID
    dragArea: "dragAndDropFiles", // Upload Area ID
    uploadUrl: upload_file				// Server side upload url
}
$(document).ready(function () {

    /*for enjoy hint code start*/

	var enjoyhint_instance = new EnjoyHint({
			onStart: function () {
				//alert('first');
			},
			onEnd: function () {
										//alert('aa');
					$(".leadadd").trigger("click");
					
			}
		   
		});
			var enjoyhint_script_steps = [{
			
			
			'next .leadadd' : 'Click On This To Create Lead!!',
			//'skipButton' : {className: "mySkip", text: "Got It!"},
				
			},
			
			];
			enjoyhint_instance.set(enjoyhint_script_steps);
			enjoyhint_instance.run();

    /*for enjoy hint code end*/

    //initMultiUploader(config);
    var dropbox;
    var oprand = {
        dragClass: "active",
        on: {
            load: function (e, file) {
                // check file size
                if (parseInt(file.size / 1024) > 20480) {
                    var delete_meg = 'File \"' + file.name + '\"'+ too_big_size +'';
                    BootstrapDialog.show(
                        {
                            title: Information,
                            message: delete_meg,
                            buttons: [{
                                label: ok,
                                action: function (dialog) {
                                    dialog.close();
                                }
                            }]
                        });
                    return false;
                }

                create_box(e, file);
            },
        }
    };
    FileReaderJS.setupDrop(document.getElementById('dragAndDropFiles'), oprand);
    var fileArr = [];

    create_box = function (e, file) {
        var rand = Math.floor((Math.random() * 100000) + 3);
        var imgName = file.name; // not used, Irand just in case if user wanrand to print it.
        var src = e.target.result;
        var xhr = new Array();
        xhr[rand] = new XMLHttpRequest();

        var filename = file.name;
        var fileext = filename.split('.').pop();

        xhr[rand].open("post", acc_upload_file + "/" + fileext, true);

        xhr[rand].upload.addEventListener("progress", function (event) {

            if (event.lengthComputable) {
                $(".progress[id='" + rand + "'] span").css("width", (event.loaded / event.total) * 100 + "%");
                $(".preview[id='" + rand + "'] .updone").html(((event.loaded / event.total) * 100).toFixed(2) + "%");
            }
            else {
                var delete_meg = failed_file_upload;
                BootstrapDialog.show(
                    {
                        title: Information,
                        message: delete_meg,
                        buttons: [{
                            label: ok,
                            action: function (dialog) {
                                dialog.close();
                            }
                        }]
                    });
            }
        }, false);

        xhr[rand].onreadystatechange = function (oEvent) {
            var img = xhr[rand].response;
            var url = url_data;
            if (xhr[rand].readyState === 4) {
                var filetype = img.split(".")[1];
                if (xhr[rand].status === 200) {
                    var template = '<div class="eachImage" id="' + rand + '">';
                    var randtest = 'delete_row("' + rand + '")';
                    template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
                    if (filetype == 'jpg' || filetype == 'jpeg' || filetype == 'png' || filetype == 'gif') {
                        template += '<span class="preview" id="' + rand + '"><img src="' + src + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                    } else {
                        template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + filetype + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
                    }
                    template += '<input type="hidden" name="fileToUpload[]" value="' + img + '">';
                    template += '</span>';
                    $("#dragAndDropFiles").append(template);
                }
            }
        };

        xhr[rand].setRequestHeader("Content-Type", "multipart/form-data");
        xhr[rand].setRequestHeader("X-File-Name", file.fileName);
        xhr[rand].setRequestHeader("X-File-Size", file.fileSize);
        xhr[rand].setRequestHeader("X-File-Type", file.type);

        // Send the file (doh)
        xhr[rand].send(file);

    }
    upload = function (file, rand) {
    }

});
function delete_row(rand) {
    jQuery('#' + rand).remove();
}

//image upload
function showimagepreview(input)
{
    console.log(input);
    $('.upload_recent').remove();
    var url = url_data;
    $.each(input.files, function (a, b) {
        var rand = Math.floor((Math.random() * 100000) + 3);
        var arr1 = b.name.split('.');
        var arr = arr1[1].toLowerCase();
        var filerdr = new FileReader();
        var img = b.name;
        filerdr.onload = function (e) {
            var template = '<div class="eachImage upload_recent" id="' + rand + '">';
            var randtest = 'delete_row("' + rand + '")';
            template += '<a id="delete_row" class="remove_drag_img" onclick=' + randtest + '>×</a>';
            if (arr == 'jpg' || arr == 'jpeg' || arr == 'png' || arr == 'gif') {
                template += '<span class="preview" id="' + rand + '"><img src="' + e.target.result + '"><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
            } else {
                template += '<span class="preview" id="' + rand + '"><div class="image_ext"><img src="' + url + '/uploads/images/icons64/file-64.png"><p class="img_show">' + arr + '</p></div><p class="img_name">' + img + '</p><span class="overlay"><span class="updone"></span></span>';
            }
            template += '<input type="hidden" name="file_data[]" value="' + b.name + '">';
            template += '</span>';
            $('#dragAndDropFiles').append(template);
        }
        filerdr.readAsDataURL(b);

    });
    var maximum = input.files[0].size / 1024;

}

function delete_lead(lead_id) {
    var delete_url = deletedataSalesOverview +'id='+ lead_id;
    var delete_msg = confirm_delete_lead;
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_msg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function (dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function (dialog) {
                    window.location.href = delete_url;
                    dialog.close();
                }
            }]
        });

}
function delete_opportunity(prospect_id) {
    var delete_url = opp_deletedataSalesOverview + 'id=' + prospect_id;
    var delete_msg = confirm_delete_opportunity;
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_msg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function (dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function (dialog) {
                    window.location.href = delete_url;
                    dialog.close();
                }
            }]
        });
}

function delete_client(prospect_id) {
    var delete_url = acc_deletedataSalesOverview + 'id=' + prospect_id;
    var delete_msg = confirm_delete_account;
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_msg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function (dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function (dialog) {
                    window.location.href = delete_url;
                    dialog.close();
                }
            }]
        });
}
function convertToOpporutnity(lead_id)
{
    var delete_msg = confirm_convert_opportunity;
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_msg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function (dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function (dialog) {
                    $.ajax({
                        type: "POST",
                        url: "Lead/converToQualified/",
                        data: {'lead_id': lead_id},
                        success: function (data)
                        {
                            BootstrapDialog.show({
                                message: lead_convert_opportunity_success,
                                buttons: [{
                                    label: close,
                                    action: function (dialogItself) {
                                        window.location.href = window.location.href;
                                        dialogItself.close();
                                    }
                                }]
                            });
                        }
                    });
                    dialog.close();
                }
            }]
        });




}
function convert_account_request(prospect_id, redirect_link) {
    $('#prospect_id').val(prospect_id);
    $('#redirect_link').val(redirect_link);
    $('#convert_client_modal').modal('show');
}
function ConvertWin(prospect_id) {
    var delete_msg = confirm_convert_win;
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_msg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function (dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function (dialog) {
                    $.ajax({
                        type: "POST",
                        url: "Opportunity/convertWinAccount/",
                        data: {'prospect_id': prospect_id},
                        success: function (data)
                        {
                            BootstrapDialog.show({
                                message: account_win_convert_msg,
                                buttons: [{
                                    label: close,
                                    action: function (dialogItself) {
                                        //  $('#opportunity_'+prospect_id).remove();
                                        window.location.href = window.location.href;
                                        dialogItself.close();
                                    }
                                }]
                            });


                        }
                    });
                    dialog.close();
                }
            }]
        });

}
function ConvertLost(prospect_id) {

    var delete_msg = confirm_convert_lost;
    BootstrapDialog.show(
        {
            title: Information,
            message: delete_msg,
            buttons: [{
                label: COMMON_LABEL_CANCEL,
                action: function (dialog) {
                    dialog.close();
                }
            }, {
                label: ok,
                action: function (dialog) {
                    $.ajax({
                        type: "POST",
                        url: "Opportunity/convertLostAccount/",
                        data: {'prospect_id': prospect_id},
                        success: function (data)
                        {
                            BootstrapDialog.show({
                                message: account_lose_convert_msg,
                                buttons: [{
                                    label: close,
                                    action: function (dialogItself) {
                                        // $('#opportunity_'+prospect_id).remove();
                                        window.location.href = window.location.href;
                                        dialogItself.close();
                                    }
                                }]
                            });


                        }
                    });
                    dialog.close();
                }
            }]
        });


}
